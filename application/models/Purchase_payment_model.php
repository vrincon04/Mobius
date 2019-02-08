<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Purchase_payment_model class
 *
 * @author Victor Rincon
 */
class Purchase_payment_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'payments';

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
            // Payment Method
            'field' => 'payment_method_id',
            'label' => 'lang:payment_method',
            'rules' => 'trim|required|is_natural_no_zero|exist[payment_methods.id]'
        ],
        [
            // Currency
            'field' => 'currency_id',
            'label' => 'lang:currency',
            'rules' => 'trim|required|is_natural_no_zero|exist[currencies.id]'
        ],
        [
            // Provider
            'field' => 'entity_id',
            'label' => 'lang:provider',
            'rules' => 'trim|required|is_natural_no_zero|exist[providers.id]'
        ],
        [
            // Type
            'field' => 'type',
            'label' => 'lang:type',
            'rules' => 'trim|in_list[expenses,income]'
        ],
        [
            // Annotations
            'field' => 'annotations',
            'label' => 'lang:annotations',
            'rules' => 'trim|max_length[500]'
        ],
        [
            // Date
            'field' => 'date',
            'label' => 'lang:date',
            'rules' => 'trim|required|prep_date_formart'
        ],
        [
            // Amount
            'field' => 'amount',
            'label' => 'lang:amount',
            'rules' => 'trim|prep_currency_format|required|decimal'
        ],
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
        'provider' => [
            'foreign_key' => 'entity_id',
            'model' => 'provider_model',
            'field' => 'id'
        ],
        'purchase' => [
            'foreign_key' => 'purchase_id',
            'model' => 'purchase_model',
            'field' => 'id'
        ],
        'payment_method' => [
            'foreign_key' => 'payment_method_id',
            'model' => 'payment_method_model',
            'field' => 'id'
        ],
        'currency' => [
            'foreign_key' => 'currency_id',
            'model' => 'currency_model',
            'field' => 'id'
        ],
        'details' => [
            'foreign_key' => 'id',
            'model' => 'payment_detail_model',
            'field' => 'payment_id'
        ]
    ];

    public function datatable_json()
    {
        $this->load->library('datatables');

        $this->datatables->select("
            {$this->_table}.id,
            {$this->_table}.amount,
            {$this->_table}.date,
            {$this->_table}.amount,
            {$this->_table}.status,
            payment_methods.lang,
			persons.first_name,
            persons.last_name
        ")->from($this->_table)
        ->join('payment_methods', "{$this->_table}.payment_method_id = payment_methods.id")
        ->join('providers', "{$this->_table}.entity_id = providers.id")
        ->join('persons',  "providers.person_id = persons.id")
        ->where("{$this->_table}.tenant_id", $this->session->userdata('tenant_id'))
        ->where("{$this->_table}.type", 'expenses');

		return $this->datatables->generate();
    }
}