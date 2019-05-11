<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Area_model class
 *
 * @author Victor Rincon
 */
class Area_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'areas';

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
            // Department
            'field' => 'department_id',
            'label' => 'lang:department',
            'rules' => 'trim|required|is_natural_no_zero|exist[departments.id]'
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
            // Is POS
            'field' => 'is_pos',
            'label' => 'lang:is_pos_area',
            'rules' => 'trim'
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
        ],
        'department' => [
            'foreign_key' => 'department_id',
            'model' => 'department_model',
            'field' => 'id'
        ],
        'employees' => [
            'foreign_key' => 'id',
            'model' => 'employee_model',
            'field' => 'area_id'
        ]
    ];

    /**
	 * [datatable_json description]
	 * @return string|object Listado de registro encontrado segun los filtros aplicados
	 */
	public function datatable_json()
	{
		$this->load->library('datatables');

		$this->datatables->select("
            {$this->_table}.id,
            {$this->_table}.department_id,
			{$this->_table}.name,
			{$this->_table}.description,
            {$this->_table}.is_active,
            departments.name AS 'department'
        ")->from($this->_table)
        ->join('departments', "{$this->_table}.department_id = departments.id")
        ->where("{$this->_table}.tenant_id", $this->session->userdata('tenant_id'));

		return $this->datatables->generate();
	}
}