<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Grant_model
 */
class Grant_model extends MY_Model {
    /**
     * @var array
     */
    protected $_pk = array('rol_id', 'module_id', 'method');
    /**
     * @var string
     */
    protected $_table = 'grants';
    /**
     * @var array
     */
    protected $_validation_rules = [
        [ // rol_id
            'field' => 'rol_id',
            'label' => 'Role',
            'rules' => 'trim|required|is_natural_no_zero|exist[roles.id]'
        ],
        [ // module_id
            'field' => 'module_id',
            'label' => 'Module',
            'rules' => 'trim|required|is_natural_no_zero|exist[modules.id]'
        ],
        [ // method
            'field' => 'method',
            'label' => 'lang:method',
            'rules' => 'trim|required|min_length[2]|max_length[60]|strtolower'
        ],
        [ // created_at
            'field' => 'created_at',
            'label' => 'created_at',
            'rules' => 'trim'
        ],
        [ // updated_at
            'field' => 'updated_at',
            'label' => 'updated_at',
            'rules' => 'trim'
        ]
    ];

    /**
     * @param null $options
     * @param bool $debug
     * @param string $object_class
     * @return mixed
     */
    public function find($options = NULL, $debug = FALSE,  $object_class = 'base_register')
    {
        if ( !isset($options['select']) )
        {
            $this->db->select('
                grants.*,
                roles.name              AS role,
                roles.is_active         AS role_active,
                modules.name            AS module,
                modules.controller      AS module_controller,
                modules.is_active       AS module_active,
                modules.action_create   AS module_create,
                modules.action_view     AS module_view,
                modules.action_edit     AS module_edit,
                modules.action_delete   AS module_delete,
            ');
        }
        else 
        { 
            $this->db->select($options['select'], FALSE); 
        }

        $this->db->from('grants')
            ->join('roles', 'grants.rol_id = roles.id')
            ->join('modules', 'grants.module_id = modules.id');
        
        if ( isset($options['where']) )
            $this->db->where($options['where']);

        if ( isset($options['order_by']) )
            $this->db->order_by($options['order_by']);

        if ( isset($options['group_by']) )
            $this->db->order_by($options['group_by']);
            
        if ( isset($options['limit']) )
            $this->db->limit($options['limit']);
            
        if ( isset($options['offset']) )
            $this->db->offset($options['offset']);
        
        $query = $this->db->get();

        if ( $debug )
            echo $this->db->last_query();

        if( isset($options['limit']) && $options['limit'] === 1 )
            return $query->row(1, $object_class);

        return $query->result($object_class);
    }

    /**
     * @param $role
     * @param array $options
     * @return mixed
     */
    public function get_by_role($role, $options = array())
    {
        $options['where']['rol_id'] = $role;
        return $this->find($options);
    }
    /**
     * @param $role
     * @return mixed
     */
    public function delete_by_role($role)
    {
        return $this->delete_where(array('rol_id' => $role));
    }
    /**
     * @param $method
     * @return mixed
     */
    public function delete_by_method($method)
    {
        return $this->db->delete($this->_table, array('method' => $method));
    }

    /**
     * @return array
     */
    public function get_grants_array()
    {
        $temp = $this->all();
        $grants = [];
        foreach ($temp as $grant)
        {
            $grants[$grant->rol_id][$grant->module_id][$grant->method] = 'ignore';
        }
        return $grants;
    }

    /**
     * @param $grants
     * @return bool
     */
    public function apply_changes($grants)
    {
        $this->db->trans_begin();
        $success = TRUE;
        foreach ($grants as $rol_id => $modules)
        {
            foreach ($modules as $module_id => $methods)
            {
                foreach ($methods as $method => $change)
                {
                    switch ($change)
                    {
                        case 'ignore':
                            break;
                        case 'add':
                            $success = $success && $this->insert(array(
                                'rol_id'        => $rol_id,
                                'module_id'     => $module_id,
                                'method'        => $method
                            ));
                            break;
                        case 'delete':
                            $success = $success && $this->delete(array(
                                'rol_id'        => $rol_id,
                                'module_id'     => $module_id,
                                'method'        => $method
                            ));
                            break;
                        default:
                            $this->db->trans_rollback();
                            return FALSE;
                    }
                    if ( ! $success )
                    {
                        $this->db->trans_rollback();
                        return FALSE;
                    }
                }
            }
        }
        if ( $this->db->trans_status() !== FALSE )
        {
            return $this->db->trans_commit();
        }
        else
        {
            $this->db->trans_rollback();
        }
        return FALSE;
    }
}