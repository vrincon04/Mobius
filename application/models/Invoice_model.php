<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Invoice_model class
 *
 * @author Victor Rincon
 */
class Invoice_model extends MY_Model {
    /**
     * Name of the table for this model.
     * @var string
     */
    protected $_table = 'invoices';

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
            // User
            'field' => 'user_id',
            'label' => 'lang:user',
            'rules' => 'trim|required|is_natural_no_zero|exist[users.id]'
        ],
        [
            // Order
            'field' => 'order_id',
            'label' => 'lang:order',
            'rules' => 'trim|is_natural_no_zero'
        ],
        [
            // Customer
            'field' => 'customer_id',
            'label' => 'lang:customer',
            'rules' => 'trim|required|is_natural_no_zero|exist[customers.id]'
        ],
        [
            // Currency
            'field' => 'currency_id',
            'label' => 'lang:currency',
            'rules' => 'trim|required|is_natural_no_zero|exist[currencies.id]'
        ],
        [
            // Expiration Type
            'field' => 'expiration_type_id',
            'label' => 'lang:expiration_type',
            'rules' => 'trim|required|is_natural_no_zero|exist[expirations_types.id]'
        ],
        [
            // tax Type
            'field' => 'tax_type_id',
            'label' => 'lang:tax_voucher',
            'rules' => 'trim|is_natural_no_zero'
        ],
        [
            // Date
            'field' => 'date',
            'label' => 'lang:date',
            'rules' => 'trim|required|prep_date_formart'
        ],
        [
            //Expired At
            'field' => 'expired_at',
            'label' => 'lang:expired_at',
            'rules' => 'trim|required|prep_date_formart'
        ],
        [
            // Note
            'field' => 'Note',
            'label' => 'lang:Note',
            'rules' => 'trim:max_length[500]'
        ],
        [
            // Status
            'field' => 'status',
            'label' => 'lang:status',
            'rules' => 'trim|in_list[canceled,draft,paid,partial,unpaid]'
        ],
        [
            // Subtotal
            'field' => 'subtotal',
            'label' => 'lang:subtotal',
            'rules' => 'trim|prep_currency_format|decimal'
        ],
        [
            // Discount
            'field' => 'discount',
            'label' => 'lang:discount',
            'rules' => 'trim|prep_currency_format|decimal'
        ],
        [
            // Shipping
            'field' => 'shipping',
            'label' => 'lang:shipping',
            'rules' => 'trim|prep_currency_format|decimal'
        ],
        [
            // Tax
            'field' => 'tax',
            'label' => 'lang:tax',
            'rules' => 'trim|prep_currency_format|decimal'
        ],
        [
            // Total
            'field' => 'total',
            'label' => 'lang:total',
            'rules' => 'trim|prep_currency_format|decimal'
        ],
        [
            // Balance
            'field' => 'balance',
            'label' => 'lang:balance',
            'rules' => 'trim|prep_currency_format|decimal'
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
        'user' => [
            'foreign_key' => 'user_id',
            'model' => 'user_model',
            'field' => 'id'
        ],
        'customer' => [
            'foreign_key' => 'customer_id',
            'model' => 'customer_model',
            'field' => 'id'
        ],
        'currency' => [
            'foreign_key' => 'currency_id',
            'model' => 'currency_model',
            'field' => 'id'
        ],
        'expiration_type' => [
            'foreign_key' => 'expiration_type_id',
            'model' => 'expiration_type_model',
            'field' => 'id'
        ],
        'details' => [
            'foreign_key' => 'id',
            'model' => 'invoice_detail_model',
            'field' => 'invoice_id'
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
            {$this->_table}.number,
            {$this->_table}.total,
            {$this->_table}.status,
            {$this->_table}.date,
            {$this->_table}.expired_at,
			persons.first_name,
            persons.last_name
        ")->from($this->_table)
        ->join('customers', "{$this->_table}.customer_id = customers.id")
        ->join('persons',  "customers.entity_id = persons.id")
        ->where("{$this->_table}.tenant_id", $this->session->userdata('tenant_id'));

		return $this->datatables->generate();
    }
}