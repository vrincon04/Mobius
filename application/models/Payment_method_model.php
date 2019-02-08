<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Payment_method_model class
 *
 * @author Victor Rincon
 */
class Payment_method_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'payment_methods';

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
            // Name
            'field' => 'name',
            'label' => 'lang:name',
            'rules' => 'trim|required|max_length[50]|ucwords'
        ],
        [
            // Language
            'field' => 'lang',
            'label' => 'lang:lang',
            'rules' => 'trim|required|max_length[60]'
        ],
        [
            // Description
            'field' => 'description',
            'label' => 'lang:description',
            'rules' => 'trim|max_length[500]'
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
        ]
    ];
}