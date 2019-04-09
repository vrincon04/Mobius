<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Order_detail_model class
 *
 * @author Victor Rincon
 */
class Order_detail_model extends MY_Model {
    /**
     * Name of the table for this model.
     * @var string
     */
    protected $_table = 'order_details';

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
            // Order
            'field' => 'order_id',
            'label' => 'lang:order',
            'rules' => 'trim|required|is_natural_no_zero|exist[orders.id]'
        ],
        [
            // Product
            'field' => 'product_id',
            'label' => 'lang:product',
            'rules' => 'trim|required|is_natural_no_zero|exist[products.id]'
        ],
        [
            // Quantity
            'field' => 'quantity',
            'label' => 'lang:quantity',
            'rules' => 'trim|prep_currency_format|required|decimal'
        ],
        [
            // Price
            'field' => 'price',
            'label' => 'lang:price',
            'rules' => 'trim|prep_currency_format|required|decimal'
        ],
        [
            // Total
            'field' => 'total',
            'label' => 'lang:total',
            'rules' => 'trim|prep_currency_format|required|decimal'
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
        'order' => [
            'foreign_key' => 'order_id',
            'model' => 'order_model',
            'field' => 'id'
        ],
        'product' => [
            'foreign_key' => 'product_id',
            'model' => 'product_model',
            'field' => 'id'
        ]
    ];
}