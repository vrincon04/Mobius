<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Employee_model class
 *
 * @author Victor Rincon
 */
class Employee_model extends MY_Model {
    /**
     * Name of the table for this model.
     * @var string
     */
    protected $_table = 'employees';
    
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
            // User
            'field' => 'user_id',
            'label' => 'lang:user',
            'rules' => 'trim|is_natural_no_zero|exist[users.id]'
        ],
        [
            // Area
            'field' => 'area_id',
            'label' => 'lang:area',
            'rules' => 'trim|required|is_natural_no_zero|exist[areas.id]'
        ],
        [
            // Is Active
            'field' => 'is_active',
            'label' => 'lang:is_active',
            'rules' => 'trim'
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
        ],
        'user' => [
            'foreign_key' => 'user_id',
            'model' => 'user_model',
            'field' => 'id'
        ],
        'area' => [
            'foreign_key' => 'area_id',
            'model' => 'area_model',
            'field' => 'id'
        ]
    ];

    /**
     * @return string
     */
	public function datatable_json()
	{
		$this->load->library('datatables');
		$this->datatables->select("
			{$this->_table}.id,
            {$this->_table}.area_id,
            {$this->_table}.user_id,
            {$this->_table}.is_active,
            persons.first_name,
            persons.middle_name,
            persons.last_name,
            persons.last_name2,
            areas.name AS area,
            IFNULL(users.username, 'No Aplica') AS username
        ")
            ->from($this->_table)
            ->join('persons', "{$this->_table}.person_id = persons.id")
            ->join('areas', "{$this->_table}.area_id = areas.id")
            ->join('users', "{$this->_table}.person_id = users.person_id", "LEFT")
            ->where("{$this->_table}.tenant_id", $this->session->userdata('tenant_id'));
        
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
            return FALSE;

        // Asignamos el identificador unico de la persona a nuestro proveedor.
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
            return FALSE;

        // Asignamos el identificador unico de la persona a nuestro proveedor.
        $data['person_id'] = $personId;
        
        return parent::update($pk, $data, $skip_validation);
    }
        
}