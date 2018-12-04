<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User_model class
 *
 * @author Victor Rincon
 */
class User_model extends MY_Model {
    /**
     * Name of the table for this model.
     * @var string
     */
    protected $_table = 'users';
    
    /**
     * Validation rules for this model.
     * @var array
     */
    protected $_validation_rules = [
        [
            // Id
            'field' => 'id',
            'label' => 'id',
            'rules' => 'is_natural_no_zero'
        ],
        [
            // Tenant
            'field' => 'tenant_id',
            'label' => 'lang:tenant',
            'rules' => 'trim|required|is_natural_no_zero|exist[tenants.id]'
        ],
        [
            // Person
            'field' => 'person_id',
            'label' => 'lang:person',
            'rules' => 'trim|required|is_natural_no_zero|exist[persons.id]'
        ],
        [
            // Username
            'field' => 'username',
            'label' => 'lang:username',
            'rules' => 'trim|required|min_length[4]|max_length[30]|is_unique[users.username]|strtolower'
        ],
        [
            // Password
            'field' => 'password',
            'label' => 'lang:password',
            'rules' => 'trim|required|min_length[4]|max_length[72]|prep_password_hash'
        ],
        [
            // Email
            'field' => 'email',
            'label' => 'lang:email',
            'rules' => 'trim|required|valid_email|max_length[250]|strtolower'
        ],
        [
            // Status
            'field' => 'status',
            'label' => 'lang:status',
            'rules' => 'trim|in_list[active,inactive]'
        ],
        [
            // Created At
            'field'     => 'created_at',
            'label'     => 'lang:created_at',
            'rules'     => 'trim'
        ],
        [
            // Updated At
            'field'     => 'updated_at',
            'label'     => 'lang:updated_at',
            'rules'     => 'trim'
        ]
    ];

    /**
     * Relationships for other models.
     * @var array
     */
    protected $_relationships = [
        'tenant' => [
            'foreign_key' => 'tenant_id',
            'model' => 'tenant_model',
            'field' => 'id'
        ],
        'person' => [
            'foreign_key' => 'person_id',
            'model' => 'person_model',
            'field' => 'id'
        ]
    ];
    
    /**
     * Method that validates the data supplied with the user table and returns
     * a boolean value in case it does not find the record or an object with 
     * the data of the user that is authenticating.
     * 
     * @access public
     * @param array $data datos suministrados que se va a comparar con la table de usuario.
     * @return object|bool
     */
    public function auth($data)
    {
        $user = $this->find([
			'where'		=> ['username' => $data['username']],
			'limit'		=> 1
        ]);

        if ( is_null($user) )
            return FALSE;
        
        $res = password_verify($data['password'], $user->password);

        if ( !$res )
        {
            $user->password = substr_replace($user->password, '$2a', 0, 3);
            $res = password_verify($data['password'], $user->password);
        }

        return ($res === TRUE) ? $user : FALSE;
    }

    /**
     * @param $id
     * @return array
     */
    public function get_grants($id)
    {
        $result = $this->db->select('modules.controller, grants.method')
        ->from('users')
        ->join('users_roles', 'users_roles.user_id = users.id')
        ->join('roles', 'users_roles.rol_id = roles.id')
        ->join('grants', 'grants.rol_id = roles.id')
        ->join('modules', 'grants.module_id = modules.id')
        ->where(array('users.id' => $id))
        ->group_by(array('grants.module_id', 'grants.method'))
        ->get()->result();

        $grants = array();
        foreach ($result as $grant)
        {
            $grants[$grant->controller][$grant->method] = TRUE;
        }

        return $grants;
    }

    /**
     * @return string
     */
	public function datatable_json()
	{
		$this->load->library('datatables');
        $grant_edit = grant_access('user', 'edit') ? 'true' : 'false';
        $grant_delete = grant_access('user', 'delete') ? 'true' : 'false';
		$this->datatables->select("
			{$this->_table}.id,
			{$this->_table}.username,
			{$this->_table}.email,
            {$this->_table}.status,
            {$this->_table}.avatar_path,
            persons.first_name,
            persons.middle_name,
            persons.last_name,
            persons.last_name2,
			
        ")
            ->from($this->_table)
            ->join('persons', "{$this->_table}.person_id = persons.id")
            ->where('users.tenant_id', $this->session->userdata('tenant_id'));
        
        return $this->datatables->generate();
    }
    
    /**
     * @param array|object $data
     * @param bool $skip_validation
     * @return int|string|bool
     */
    public function insert($data, $skip_validation = FALSE)
    {
        // Cargamos el modelo de person.
        $this->load->model('person_model');

        // Insertamos o modificamos a la persona.
        $personId = $this->person_model->insert_update($data, 'document_number');

        if ( ! $personId )
            return false;

        // Asignamos el identificador unico de la persona a nuestro usuario.
        $data['person_id'] = $personId;

        return parent::insert($data, $skip_validation);
    }

    /**
     * @param int|string|array $pk
     * @param array|object $data
     * @param bool $skip_validation
     * @return bool
     */
    public function update($pk, $data, $skip_validation = FALSE)
    {
        // Cargamos el modelo de person.
        $this->load->model('person_model');

        // Insertamos o modificamos a la persona.
        $personId = $this->person_model->insert_update($data, 'document_number');

        if ( ! $personId )
            return false;

        // Asignamos el identificador unico de la persona a nuestro usuario.
        $data['person_id'] = $personId;
        
        return parent::update($pk, $data, $skip_validation);
    }
        
}