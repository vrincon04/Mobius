<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Order_model class
 *
 * @author Victor Rincon
 */
class Order_model extends MY_Model {
    /**
     * Name of the table for this model.
     * @var string
     */
    protected $_table = 'orders';

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
            // Customer
            'field' => 'customer_id',
            'label' => 'lang:customer',
            'rules' => 'trim|required|is_natural_no_zero|exist[customers.id]'
        ],
        [
            // Date
            'field' => 'date',
            'label' => 'lang:date',
            'rules' => 'trim|prep_date_formart'
        ],
        [
            // Delivered At
            'field' => 'delivered_at',
            'label' => 'lang:delivered_at',
            'rules' => 'trim|prep_date_formart'
        ],
        [
            // Status
            'field' => 'status',
            'label' => 'lang:status',
            'rules' => 'trim|in_list[canceled,pending,dispatched,invoiced]'
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
        'details' => [
            'foreign_key' => 'id',
            'model' => 'order_detail_model',
            'field' => 'order_id'
        ]
    ];

    public function pending()
    {
        return $this->find([
            'where' => [
                'status' => 'pending'
            ]
        ]);
    }

    public function pending_by_user($user)
    {
        return $this->find([
            'where' => [
                'status' => 'pending',
                'user_id' => $user
            ]
        ]);
    }
}