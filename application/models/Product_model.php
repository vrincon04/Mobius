<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Product_model class
 *
 * @author Victor Rincon
 */
class Product_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'products';

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
            // Category
            'field' => 'category_id',
            'label' => 'lang:category',
            'rules' => 'trim|required|is_natural_no_zero|exist[categories.id]'
        ],
        [
            // Code
            'field' => 'code',
            'label' => 'lang:code',
            'rules' => 'trim|required|min_length[3]|max_length[20]'
        ],
        [
            // Name
            'field' => 'name',
            'label' => 'lang:name',
            'rules' => 'trim|required|min_length[4]|max_length[100]|ucwords'
        ],
        [
            // Description
            'field' => 'description',
            'label' => 'lang:description',
            'rules' => 'trim'
        ],
        [
            // Sale
            'field' => 'sale',
            'label' => 'lang:price',
            'rules' => 'trim|required|decimal'
        ],
        [
            // Is Active
            'field' => 'is_active',
            'label' => 'lang:is_active',
            'rules' => 'trim'
        ],
        [
            // Is Stock
            'field' => 'is_stock',
            'label' => 'lang:is_stock',
            'rules' => 'trim'
        ],
        [
            // Image Path
            'field' => 'image_path',
            'label' => 'lang:image_path',
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
        'category' => [
            'foreign_key' => 'category_id',
            'model' => 'category_model',
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

		$this->datatables->select("
            {$this->_table}.id,
            {$this->_table}.code,
			{$this->_table}.name,
            {$this->_table}.description,
            {$this->_table}.sale,
            categories.name AS category,
            {$this->_table}.is_active,
            {$this->_table}.is_stock,
            {$this->_table}.image_path
        ")->from($this->_table)
        ->join('categories', "{$this->_table}.category_id = categories.id", 'INNER')
        ->where("{$this->_table}.tenant_id", $this->session->userdata('tenant_id'));

		return $this->datatables->generate();
	}
}