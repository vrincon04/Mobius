<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Invoice_detail_model class
 *
 * @author Victor Rincon
 */
class Invoice_detail_model extends MY_Model {
    /**
     * Name of the table for this model.
     * @var string
     */
    protected $_table = 'invoice_details';

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
            // Invoice
            'field' => 'invoice_id',
            'label' => 'lang:invoice',
            'rules' => 'trim|required|is_natural_no_zero|exist[invoices.id]'
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
            'field' => 'prince',
            'label' => 'lang:prince',
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
        'invoice' => [
            'foreign_key' => 'invoice_id',
            'model' => 'invoice_model',
            'field' => 'id'
        ],
        'product' => [
            'foreign_key' => 'product_id',
            'model' => 'product_model',
            'field' => 'id'
        ]
    ];
}