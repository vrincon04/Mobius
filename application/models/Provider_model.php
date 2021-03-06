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
            // Entity id
            'field' => 'entity_id',
            'label' => 'lang:entity_id',
            'rules' => 'trim|required|is_natural_no_zero'
        ],
        [
            // Entity type
            'field' => 'entity_type',
            'label' => 'lang:entity_type',
            'rules' => 'trim|required|strtolower'
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
            'foreign_key' => 'entity_id',
            'model' => 'person_model',
            'field' => 'id'
        ],
        'business' => [
            'foreign_key' => 'entity_id',
            'model' => 'business_model',
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
            {$this->_table}.is_modifiable,
            {$this->_table}.first_name,
            {$this->_table}.middle_name,
            {$this->_table}.last_name,
            {$this->_table}.last_name2,
            {$this->_table}.entity_type
        ")
            ->from("(
                SELECT {$this->_table}.id, {$this->_table}.email, {$this->_table}.is_active, {$this->_table}.is_modifiable, {$this->_table}.tenant_id, {$this->_table}.entity_type, persons.first_name, persons.middle_name, persons.last_name, persons.last_name2
                FROM {$this->_table}
                INNER JOIN persons ON {$this->_table}.entity_id = persons.id AND {$this->_table}.entity_type = 'person'
                UNION
                SELECT {$this->_table}.id, {$this->_table}.email, {$this->_table}.is_active, {$this->_table}.is_modifiable, {$this->_table}.tenant_id,{$this->_table}.entity_type, businesses.trade_name, 'N/A', businesses.business_name, 'N/A'
                FROM {$this->_table}
                INNER JOIN businesses ON {$this->_table}.entity_id = businesses.id AND {$this->_table}.entity_type = 'business'
            ) AS {$this->_table}", FALSE)
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
        $entity = [
            1 => [
                'name' => 'person',
                'model' => 'person_model'
            ],
            4 => [
                'name' => 'business',
                'model' => 'business_model'
            ]
        ];
        $document = $this->input->post('document_type_id');
        
        // Cargamos el modelo de empresa.
        $this->load->model($entity[$document]['model']);
        // Insertamos o modificamos a la entidad.
        $entityId = $this->{$entity[$document]['model']}->insert_update($data, 'document_number');

        if ( ! $entityId )
            return FALSE;

        // Asignamos el identificador unico de la persona o empreda a nuestro proveedor.
        $data['entity_id'] = $entityId;
        $data['entity_type'] = $entity[$document]['name'];

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
        $entity = [
            1 => [
                'name' => 'person',
                'model' => 'person_model'
            ],
            4 => [
                'name' => 'business',
                'model' => 'business_model'
            ]
        ];
        $document = $this->input->post('document_type_id');
        
        // Cargamos el modelo de empresa.
        $this->load->model($entity[$document]['model']);
        // Insertamos o modificamos a la entidad.
        $entityId = $this->{$entity[$document]['model']}->insert_update($data, 'document_number');

        if ( ! $entityId )
            return FALSE;

        // Asignamos el identificador unico de la persona o empreda a nuestro proveedor.
        $data['entity_id'] = $entityId;
        $data['entity_type'] = $entity[$document]['name'];
        
        return parent::update($pk, $data, $skip_validation);
    }
        
}