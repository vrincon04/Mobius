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
            'rules' => 'trim|required|min_length[3]|max_length[20]|is_unique[products.code]'
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
            'rules' => 'trim|prep_currency_format|required|decimal'
        ],
        [
            // Cost
            'field' => 'cost',
            'label' => 'lang:cost',
            'rules' => 'trim|prep_currency_format|decimal'
        ],
        [
            // Wholesale Price
            'field' => 'wholesale_price',
            'label' => 'lang:wholesale_price',
            'rules' => 'trim|prep_currency_format|decimal'
        ],
        [
            // Quantity Wholesale
            'field' => 'quantity_wholesale',
            'label' => 'lang:quantity_for_wholesale',
            'rules' => 'trim|prep_currency_format|decimal'
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
            // Is Composed
            'field' => 'is_composed',
            'label' => 'lang:is_composed',
            'rules' => 'trim'
        ],
        [
            // Is Salable
            'field' => 'is_salable',
            'label' => 'lang:is_salable',
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
        ],
        'stocks' => [
            'foreign_key' => 'id',
            'model' => 'stock_model',
            'field' => 'product_id' 
        ],
        'components' => [
            'foreign_key' => 'id',
            'model' => 'product_component_model',
            'field' => 'product_id'
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
            {$this->_table}.cost,
            {$this->_table}.wholesale_price,
            {$this->_table}.quantity_wholesale,
            {$this->_table}.is_active,
            {$this->_table}.is_stock,
            {$this->_table}.image_path,
            categories.name AS category,
            SUM(stocks.count) AS stock
        ")->from($this->_table)
        ->join('categories', "{$this->_table}.category_id = categories.id", 'INNER')
        ->join('stocks',  "{$this->_table}.id = stocks.product_id", "LEFT")
        ->where("{$this->_table}.tenant_id", $this->session->userdata('tenant_id'))
        ->group_by("{$this->_table}.id");

		return $this->datatables->generate();
	}
}