<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Cash_drawer_model class
 *
 * @author Victor Rincon
 */
class Cash_drawer_model extends MY_Model {
    /**
     * @var string
     */
    protected $_table = 'cash_drawers';

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
            'rules' => 'trim|required|is_natural_no_zero|exist[users.id]|two_cash_register_opened[cash_drawers.user_id]'
        ],
        [
            // Currency
            'field' => 'currency_id',
            'label' => 'lang:currency',
            'rules' => 'trim|is_natural_no_zero|exist[currencies.id]'
        ],
        [
            // Opened User
            'field' => 'opened_by',
            'label' => 'lang:opened_by',
            'rules' => 'trim|required|is_natural_no_zero|exist[users.id]'
        ],
        [
            // Closed User
            'field' => 'closed_by',
            'label' => 'lang:closed_by',
            'rules' => 'trim|is_natural_no_zero|exist[users.id]'
        ],
        [
            // Status
            'field' => 'status',
            'label' => 'lang:status',
            'rules' => 'trim|required|in_list[open,close]'
        ],
        [
            // Block Cash
            'field' => 'block_cash',
            'label' => 'lang:block_cash',
            'rules' => 'trim|prep_currency_format|decimal'
        ],
        [
            // Opened At
            'field'     => 'opened_at',
            'label'     => 'lang:opened_at',
            'rules'     => 'trim|required'
        ],
        [
            // Closed At
            'field'     => 'closed_at',
            'label'     => 'lang:closed_at',
            'rules'     => 'trim'
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
        'user' => [
            'foreign_key' => 'user_id',
            'model' => 'user_model',
            'field' => 'id'
        ],
        'open' => [
            'foreign_key' => 'opened_by',
            'model' => 'user_model',
            'field' => 'id'
        ],
        'close' => [
            'foreign_key' => 'closed_by',
            'model' => 'user_model',
            'field' => 'id'
        ],
        'currency' => [
            'foreign_key' => 'currency_id',
            'model' => 'currency_model',
            'field' => 'id'
        ],
        'details' => [
            'foreign_key' => 'id',
            'model' => 'cash_drawer_detail_model',
            'field' => 'cash_drawer_id'
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
            users.username AS name,
            open.username AS open_name,
            close.username AS close_name,
            {$this->_table}.opened_at,
            {$this->_table}.closed_at,
            {$this->_table}.block_cash,
            {$this->_table}.status
        ")->from($this->_table)
        ->join('users', "{$this->_table}.user_id = users.id",'INNER')
        ->join('users AS open', "{$this->_table}.opened_by = open.id",'INNER')
        ->join('users AS close', "{$this->_table}.closed_by = close.id",'LEFT')
        ->where("{$this->_table}.tenant_id", $this->session->userdata('tenant_id'));

		return $this->datatables->generate();
    }
}