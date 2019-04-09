<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Purchase_detail_model class
 *
 * @author Victor Rincon
 */
class Purchase_detail_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'purchase_details';

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
            // Purchase Order
            'field' => 'purchase_id',
            'label' => 'lang:purchase',
            'rules' => 'trim|required|is_natural_no_zero|exist[purchases.id]'
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
            // Cost
            'field' => 'cost',
            'label' => 'lang:cost',
            'rules' => 'trim|prep_currency_format|required|decimal'
        ],
        [
            // Total
            'field' => 'total',
            'label' => 'lang:total',
            'rules' => 'trim|prep_currency_format|required|decimal'
        ]
    ];

    /**
     * Relationships for other models.
     * @var array
     */
    protected $_relationships = [
        'purchase' => [
            'foreign_key' => 'purchase_id',
            'model' => 'purchase_model',
            'field' => 'id'
        ],
        'product' => [
            'foreign_key' => 'product_id',
            'model' => 'product_model',
            'field' => 'id'
        ]
    ];
}