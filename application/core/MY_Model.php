<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Controller class 
 *
 * @author Victor Rincon
 */
class MY_Model extends CI_Model {

    /**
     * @var null|string
     */
    protected $_table = NULL;
    /**
     * @var null|string|array
     */
	protected $_pk = 'id';
    /**
     * @var null|array
     */
    protected $_validation_rules = NULL;
    /**
     * @var null|array
     */
    protected $_fields = NULL;
    
    protected $_relationships = NULL;

    /**
     * MY_Model constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

		if ( is_null($this->_validation_rules) )
        {
            throw new Exception('"$_validation_rules" property not set for the table ' . $this->_table . '.', 1);
        }

        $this->_fields = array_fill_keys(array_column($this->_validation_rules, 'field'), TRUE);

        $this->_guess_table();

        $this->_guess_primary_key();
    }

    /**
     * @param int|string|array $pk
     * @return null|object|array
     */
	public function get($pk)
	{
        $this->_check_pk($pk);
		$filter = is_array($this->_pk) ? $pk : array("{$this->_table}.{$this->_pk}" => $pk);

        $options = array(
            'where'     => $filter,
            'limit'     => 1
        );

		return $this->find($options);
	}

    /**
     * @param int|string|array $pk
     * @return bool
     */
	public function exist($pk)
    {
        $this->_check_pk($pk);

        $select = is_array($this->_pk) ? $this->_pk[0] : $this->_pk;
        $filter = is_array($this->_pk) ? $pk : array($this->_pk => $pk);

        return $this->db->select($select)->from($this->_table)->where($filter)->limit(1)->count_all_results() > 0;
    }

    /**
     * @param string|array $condition
     * @param bool $get_id
     * @return int|string|array|bool
     */
    public function exist_where($condition, $get_id = FALSE)
    {
        if ( $get_id )
        {
            $result = $this->db->select($this->_pk)->from($this->_table)->where($condition)->limit(1)->get()->row_array();
            if ( $result )
            {
                return is_array($this->_pk) ? $result : $result[$this->_pk];
            }
            else { return FALSE; }
        }
        else
        {
            return $this->db->select($this->_pk)->from($this->_table)->where($condition)->limit(1)->count_all_results() > 0;
        }
    }

    /**
     * @param null|array $options
     * @return array|null|object
     */
	public function all($options = NULL)
	{
		return $this->find($options);
	}

    /**
     * @param string $field
     * @return array|null|object
     * @throws Exception
     */
	public function first($field = 'created_at')
	{
        if ( ! isset($this->_fields[$field]) )
        {
            throw new Exception("Field '{$field}' doesn't exist for this model.", 1);
        }

        $options = array(
            'limit'     => 1,
            'order_by'  => "{$field} ASC"
        );

		return $this->find($options);
	}

    /**
     * @param string $field
     * @return array|null|object
     * @throws Exception
     */
	public function last($options = [], $field = 'created_at')
	{
        if ( ! isset($this->_fields[$field]) )
        {
            throw new Exception("Field '{$field}' doesn't exist for this model.", 1);
        }

        $options = array_merge_recursive_distinct($options, [
            'limit'     => 1,
            'order_by'  => "{$field} DESC"
        ]);

		return $this->find($options);
	}

    /**
     * @param array|null $options
     * @param string     $object_class
     * @return object|array|null
     */
    public function find($options = NULL, $array_result = FALSE, $debug = FALSE,  $object_class = 'base_register')
    {
        $class = strtolower(get_class($this));
        $append = ", '{$class}' AS object_model";

        $this->db->from($this->_table);

        if ( isset($options['joins']) ) {
            foreach ($options['joins'] as $value) {
                $this->db->join($value[0], $value[1], $value[2]);
            }
        }

        if ( isset($this->_fields['tenant_id']) && $this->session->userdata('logged_in') )
            $this->db->where('tenant_id', $this->session->userdata('tenant_id'));

        if ( ! isset($options['select']) )
            $this->db->select("{$this->_table}.*{$append}", FALSE);
        else 
            $this->db->select($options['select'].$append, FALSE);

        if ( isset($options['where']) )
            $this->db->where($options['where']);
        if ( isset($options['where_in']) )
            $this->db->where_in($options['where_in']['key'], $options['where_in']['values']);

        if ( isset($options['like']) )
            $this->db->like($options['like']);
        
        if ( isset($options['or_like']) )
            $this->db->or_like($options['or_like']);

        if ( isset($options['order_by']) )
            $this->db->order_by($options['order_by']);

        if ( isset($options['group_by']) )
            $this->db->group_by($options['group_by']);
        
        if ( isset($options['limit']) )
            $this->db->limit($options['limit']);

        if ( isset($options['offset']) )
            $this->db->offset($options['offset']);
        
        $query = $this->db->get();

        if ( $debug )
            echo $this->db->last_query();

        if( isset($options['limit']) && $options['limit'] === 1 )
            return ($array_result) ? $query->row_array() : $query->row(1, $object_class); 

        return ($array_result) ? $query->result_array() : $query->result($object_class);
    }

    /**
     * @param array|object $data
     * @param bool $skip_validation
     * @return int|string|bool
     */
	public function insert($data, $skip_validation = FALSE)
    {
        $this->_filter_inputs($data);

        if ( isset($this->_fields['created_at']) )
            $data['created_at'] = date('Y-m-d H:i:s');

        if ( isset($this->_fields['tenant_id']) )
            $data['tenant_id'] = $this->session->userdata('tenant_id');

        if ( isset($this->_fields['user_id']) && !isset($data['user_id']))
            $data['user_id'] = $this->session->userdata('user_id');

		if ( !$skip_validation && !$this->_validate($data) ) { return FALSE; }

		$result = $this->db->insert($this->_table, $data);
        if ( $result )
        {
            $result = (is_array($this->_pk)) ? $result : $this->db->insert_id();
            if ( $result === 0 )
            {
                return is_object($data) ? $data->{$this->_pk} : $data[$this->_pk];
            }
        }

		return $result;
	}

    /**
     * @param int|string|array $pk
     * @param array|object $data
     * @param bool $skip_validation
     * @return bool
     */
	public function update($pk, $data, $skip_validation = FALSE)
	{
		$this->_check_pk($pk);
		$this->_filter_inputs($data);

        if ( isset($this->_fields['updated_at']) )
        {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

		if ( ! $skip_validation && ! $this->_validate($data, $pk) ) { return FALSE; }

		$filter = is_array($this->_pk) ? $pk : array($this->_pk => $pk);

		return $this->db->update($this->_table, $data, $filter);
	}

    /**
     * @param array|object $data
     * @param array|string $reference
     * @param bool $skip_validation
     * @return bool|int|string
     */
	public function insert_update($data, $reference, $skip_validation = FALSE)
    {
        if ( is_array($reference) )
            { $filter = array_intersect_key($data, array_flip($reference)); }
        else
            { $filter = array($reference => $data[$reference]); }

        if ( !$filter ) { return FALSE; }

        if ( $id = $this->exist_where($filter, TRUE) )
        {
            $id = !is_array($id) ? $id : array($this->_pk => $id);
            return $this->update($id, $data, $skip_validation) ? $id : FALSE;
        }
        else
        {
            return $this->insert($data, $skip_validation);
        }
    }

    /**
     * @param int|string|array $pk
     * @return bool
     */
    public function delete($pk)
    {
        $this->_check_pk($pk);

		$filter = is_array($this->_pk) ? $pk : array($this->_pk => $pk);
        return $this->db->delete($this->_table, $filter);
    }

    /**
     * @param string|array $condition
     * @return bool
     * @throws Exception
     */
    public function delete_where($condition)
    {
        $elements = $this->find(array('where' => $condition));

        $this->db->trans_start();
        foreach ($elements as $element)
        {
            if ( is_array($this->_pk) )
            {
                $pk = array();
                foreach ($this->_pk as $_pk)
                {
                    $pk[$_pk] = $element->{$_pk};
                }
                $this->delete($pk);
            }
            else
            {
                $this->delete($element->{$this->_pk});
            }
        }
        $this->db->trans_complete();
        if ( !$this->db->trans_status() )
        {
            throw new Exception("No se pudieron eliminar todos los elementos.");
        }

        return TRUE;
    }

    /**
     * @param string $key_field
     * @param string $value_field
     * @param array $options
     * @param string|null $default_option
     * @param string|null $optgroup_field
     * @return array|null
     */
    public function dropdown($key_field, $value_field, $options = array(), $is_lang = FALSE, $default_option = NULL, $optgroup_field = NULL)
    {
        if ( isset($this->_fields[$key_field]) && isset($this->_fields[$value_field]) && ($optgroup_field === NULL || isset($this->_fields[$optgroup_field])) )
        {
            $options['select'] = "{$this->_table}.{$key_field}, {$this->_table}.{$value_field}";
        }

        $data = $this->find($options, TRUE);

        if ( $optgroup_field === NULL )
        {
            $data = array_column($data, $value_field, $key_field);

            if ($is_lang == TRUE) {
                $temp = array();
                
                foreach ( $data as $key => $value ) {
                    $temp[$key] = ($is_lang === TRUE) ? lang($value) : $value;
                }

                $data = $temp;
            }
        }
        else
        {
            $temp = array();
            foreach ( $data as $element )
            {
                $temp[$element[$optgroup_field]][$element[$key_field]] = ($is_lang === TRUE) ? lang($element[$value_field]) : $element[$value_field];
            }
            $data = $temp;
        }

        $default_option = is_null($default_option) ? lang('choose_an_option') : $default_option;

        return ($default_option === FALSE) ? $data : array('' => $default_option ) + $data;
    }

    /**
     * @param null $alias
     * @return bool
     */
    public function relationships($alias = NULL)
    {
        if ( is_null($this->_relationships) ) { return FALSE; }

        if ( is_null($alias) ) { return $this->_relationships; }
        elseif ( isset($this->_relationships[$alias]) ) { return $this->_relationships[$alias]; }

        return FALSE;
    }

    /**
     * @param array|object $data
     * @param bool $edit
     * @return bool
     */
	protected function _validate(&$data, $edit = FALSE)
	{
		if ( is_null($this->_validation_rules) ) { return FALSE; }

		$validation_rules = ($edit !== FALSE) ? $this->_validation_rules_for_edit($this->_validation_rules, $edit) : $this->_validation_rules;

        $this->form_validation->reset_validation();
        $this->form_validation->set_data_ref($data);
		$this->form_validation->set_rules($validation_rules);

		return ($this->form_validation->run() === TRUE);
	}

    /**
     * @param array $rules
     * @param null|int|string $id
     * @return array
     */
	protected function _validation_rules_for_edit($rules, $id = NULL)
	{
		foreach ($rules as $k => $field)
		{
			$conditions = explode('|', $field['rules']);
			$temp = '';
			$first = TRUE;
			foreach ($conditions as $cond)
			{
                $tmp = explode('[', $cond);

                switch ($tmp[0])
                {
                    case 'required':
                        break;
                    /** @noinspection PhpMissingBreakStatementInspection */
                    case 'is_unique':
                        if ( $this->_pk !== 'id' || is_null($id) ) { break; }
                        $par = explode(']', $tmp[1]);
                        $cond = "is_unique[{$par[0]}.{$id}]";
                    default:
                    if ( $first ) { $first = FALSE; } else { $temp .= '|'; }
                    $temp .= $cond;
                }
			}
			$rules[$k]['rules'] = $temp;
		}

		return $rules;
	}

    /**
     * @param int|string|array $pk
     * @return bool
     * @throws Exception
     */
	protected function _check_pk($pk)
    {
        if ( is_array($this->_pk) )
        {
            if ( !is_array($pk) )
            {
                throw new Exception('Array expected as parameter.', 1);
            }

            if ( count($this->_pk) !== count($pk) )
            {
                throw new Exception('Incorrect PK parameters for this model. PK\'s are: '.implode(', ', $this->_pk), 1);
            }

            foreach ($pk as $k => $v)
            {
                if ( !in_array($k, $this->_pk) )
                {
                    throw new Exception("'{$k}' it's not part of the primary key for this model.", 1);
                }
            }
        }

        return TRUE;
    }

    /**
     * @param $data
     */
    protected function _filter_inputs(&$data)
    {
        foreach ($data as $k => $v)
        {
            if ( !isset($this->_fields[$k]) ) 
                unset($data[$k]);
        }
    }

    /**
     *
     */
    private function _guess_table()
    {
        if ($this->_table === NULL)
        {
            $this->load->helper('inflector');
            $this->_table = plural(preg_replace('/(_m|_model)?$/', '', strtolower(get_class($this))));
        }
    }

    /**
     *
     */
    private function _guess_primary_key()
    {
        if( $this->_pk !== NULL ) 
            return;

        if ( isset($this->_fields['id']) )
            $this->_pk = 'id';
        else
            $this->_pk = $this->db->query("SHOW KEYS FROM `".$this->_table."` WHERE Key_name = 'PRIMARY'")
            ->row()->Column_name;
    }
}

class base_register {
    /**
     * Object Read Mapping
     * 
     * @var array
     */
    protected $_with = [];

    /**
     * public function with($requests)
     * allows the user to retrieve records from other interconnected tables depending on the relations defined before the constructor
     * @param string $requests
     * @return $this
     */
    public function with($requests)
    {
        if ( ! is_array($requests) )
            throw new Exception('"$requests" property not set array.', 1);

        $this->_with = $requests;
        
        return $this->get_property($this->_with);
    }

    /**
     * @param $rel_alias
     * @param array $options
     * @return mixed
     */
    public function get_related($rel_alias, $options = array())
    {
        $_CI = &get_instance();

        $relation = $_CI->{$this->object_model}->relationships($rel_alias);
        if ( ! $relation ) { return FALSE; }

        $_CI->load->model($relation['model']);
        $options = array_merge_recursive_distinct($options, array('where' => array($relation['field'] => $this->{$relation['foreign_key']})));
        
        if ($relation['foreign_key'] == 'id') 
            unset($options['limit']);
        
        return $_CI->{$relation['model']}->find($options);
    }

    protected function get_property($with, $parent = '')
    {
        foreach ($with as $key => $value) {
            if ( is_array($value) ) {
                $this->{$key} = $this->get_related($key, ['limit' => 1]);
                $this->get_property($value, $key);
            } else {
                if ( $parent == '' )
                    $this->{$value} = $this->get_related($value, ['limit' => 1]);
                else {
                    $this->{$parent}->{$value} = $this->{$parent}->get_related($value, ['limit' => 1]);
                }
            }
        }
        
        return $this;
    }

}

