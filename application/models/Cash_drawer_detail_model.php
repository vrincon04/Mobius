<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Cash_drawer_detail_model class
 *
 * @author Victor Rincon
 */
class Cash_drawer_detail_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'cash_drawer_details';

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
            // Cash Drawer
            'field' => 'cash_drawer_id',
            'label' => 'lang:cash_drawer',
            'rules' => 'trim|required|is_natural_no_zero|exist[cash_drawers.id]'
        ],
        [
            // Payment Method
            'field' => 'payment_method_id',
            'label' => 'lang:payment_method',
            'rules' => 'trim|is_natural_no_zero|exist[payment_methods.id]'
        ],
        [
            // Type
            'field' => 'type',
            'label' => 'lang:type',
            'rules' => 'trim|required|in_list[input,output]'
        ],
        [
            // Description
            'field' => 'description',
            'label' => 'lang:description',
            'rules' => 'trim'
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
        'cash_drawer' => [
            'foreign_key' => 'cash_drawer_id',
            'model' => 'cash_drawer_model',
            'field' => 'id'
        ],
        'payment_method' => [
            'foreign_key' => 'payment_method_id',
            'model' => 'payment_method_model',
            'field' => 'id'
        ]
    ];

    public function input($cash_drawer, $amount, $payment_method, $description)
    {
        $data = [
            'cash_drawer_id' => $cash_drawer,
            'description' => $description,
            'type' => 'input',
            'amount' => $amount,
            'payment_method' => $payment_method
        ];

        return $this->insert($data);
    }

    public function output($cash_drawer, $amount, $description)
    {
        $data = [
            'cash_drawer_id' => $cash_drawer,
            'description' => $description,
            'type' => 'output',
            'amount' => -1 * $amount,
            'payment_method' => 1
        ];

        return $this->insert($data);
    }
}