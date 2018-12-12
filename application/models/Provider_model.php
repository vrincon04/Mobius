<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Provider_model class
 *
 * @author Victor Rincon
 */
class Provider_model extends MY_Model {
    /**
     * Name of the table for this model.
     * @var string
     */
    protected $_table = 'providers';
    
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
            // Email
            'field' => 'email',
            'label' => 'lang:email',
            'rules' => 'trim|required|valid_email|max_length[250]|strtolower'
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
        ]
    ];

    /**
     * @return string
     */
	public function datatable_json()
	{
		$this->load->library('datatables');
        $grant_edit = grant_access('provider', 'edit') ? 'true' : 'false';
        $grant_delete = grant_access('provider', 'delete') ? 'true' : 'false';
		$this->datatables->select("
			{$this->_table}.id,
			{$this->_table}.email,
            {$this->_table}.is_active,
            persons.first_name,
            persons.middle_name,
            persons.last_name,
            persons.last_name2
        ")
            ->from($this->_table)
            ->join('persons', "{$this->_table}.person_id = persons.id")
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