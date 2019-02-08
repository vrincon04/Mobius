<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Payment_detail_model class
 *
 * @author Victor Rincon
 */
class Payment_detail_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'payment_details';

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
            // Payment
            'field' => 'payment_id',
            'label' => 'lang:payment',
            'rules' => 'trim|required|is_natural_no_zero|exist[payments.id]'
        ],
        [
            // Document
            'field' => 'document_id',
            'label' => 'lang:document',
            'rules' => 'trim|required|is_natural_no_zero'
        ],
        [
            // Amount
            'field' => 'amount',
            'label' => 'lang:amount',
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
        'purchase_payments' => [
            'foreign_key' => 'id',
            'model' => 'purchase_payment_model',
            'field' => 'payment_method_id'
        ],
        'purchase' => [
            'foreign_key' => 'document_id',
            'model' => 'purchase_model',
            'field' => 'id'
        ]
    ];
}