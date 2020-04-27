<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Currency_model class
 *
 * @author Victor Rincon
 */
class Currency_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'currencies';

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
            'rules' => 'trim|required|max_length[100]|ucwords'
        ],
        [
            // Code
            'field' => 'code',
            'label' => 'lang:code',
            'rules' => 'trim|max_length[5]'
        ],
        [
            // Symbol
            'field' => 'symbol',
            'label' => 'lang:symbol',
            'rules' => 'trim|required|max_length[5]'
        ],
        [
            // Value
            'field' => 'value',
            'label' => 'lang:value',
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
        'tenants' => [
            'foreign_key' => 'id',
            'model' => 'tenant_model',
            'field' => 'currency_id'
        ]
    ];

    public function convertRate($from, $to) {
        return $from / $to;
    }
}