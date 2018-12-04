<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Rol_model class
 *
 * @author Victor Rincon
 */
class Rol_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'roles';

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
            // Name
            'field' => 'name',
            'label' => 'lang:name',
            'rules' => 'trim|required|max_length[100]|ucwords'
        ],
        [
            // Description
            'field' => 'description',
            'label' => 'lang:description',
            'rules' => 'trim'
        ],
        [
            // Created At
            'field'     => 'created_at',
            'label'     => 'created_at',
            'rules'     => 'trim'
        ],
        [
            // Updated At
            'field'     => 'updated_at',
            'label'     => 'updated_at',
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
        ]
    ];

    /**
	 * [datatable_json description]
	 * @return string|object Listado de registro encontrado segun los filtros aplicados
	 */
	public function datatable_json()
	{
		$this->load->library('datatables');

		$this->datatables->select('
			id,
			name,
			description,
			is_active
        ')->from('roles')
        ->where('tenant_id', $this->session->userdata('tenant_id'));

		return $this->datatables->generate();
	}
}