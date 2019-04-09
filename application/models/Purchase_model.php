<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Purchase_model class
 *
 * @author Victor Rincon
 */
class Purchase_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'purchases';

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
            // Provider
            'field' => 'provider_id',
            'label' => 'lang:provider',
            'rules' => 'trim|required|is_natural_no_zero|exist[providers.id]'
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
            // Code
            'field' => 'code',
            'label' => 'lang:code',
            'rules' => 'trim|min_length[3]|max_length[20]'
        ],
        [
            // Reference
            'field' => 'reference',
            'label' => 'lang:reference',
            'rules' => 'trim|min_length[4]|max_length[25]'
        ],
        [
            // Status
            'field' => 'status',
            'label' => 'lang:status',
            'rules' => 'trim|in_list[canceled,draft,paid,partial,unpaid]'
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
            //annotations
            'field' => 'annotations',
            'label' => 'lang:annotations',
            'rules' => 'trim:max_length[500]'
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
        'provider' => [
            'foreign_key' => 'provider_id',
            'model' => 'provider_model',
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
            'model' => 'purchase_detail_model',
            'field' => 'purchase_id'
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
            {$this->_table}.reference,
            {$this->_table}.total,
            {$this->_table}.status,
            {$this->_table}.date,
            {$this->_table}.expired_at,
			persons.first_name,
            persons.last_name
        ")->from($this->_table)
        ->join('providers', "{$this->_table}.provider_id = providers.id")
        ->join('persons',  "providers.person_id = persons.id")
        ->where("{$this->_table}.tenant_id", $this->session->userdata('tenant_id'));

		return $this->datatables->generate();
    }
}