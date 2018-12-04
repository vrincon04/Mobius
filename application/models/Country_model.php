<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Country_model class
 *
 * @author Victor Rincon
 */
class Country_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'countries';

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
            'rules' => 'trim|required|max_length[150]|ucwords'
        ],
        [
            // Lang
            'field' => 'lang',
            'label' => 'lang:lang',
            'rules' => 'trim|max_length[150]|alpha_numeric_spaces'
        ],
        [
            // ISO Code 2
            'field' => 'code2',
            'label' => 'lang:code2',
            'rules' => 'trim|required|max_length[2]|strtoupper'
        ],
        [
            // ISO Code 3
            'field' => 'code3',
            'label' => 'lang:code3',
            'rules' => 'trim|required|max_length[3]|strtoupper'
        ],
        [
            // Currency Code
            'field' => 'currency_code',
            'label' => 'lang:currency_code',
            'rules' => 'trim|required|max_length[5]|strtoupper'
        ],
        [
            // Currency Sign
            'field' => 'currency_sign',
            'label' => 'lang:currency_sign',
            'rules' => 'trim|required|max_length[5]|strtoupper'
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
        'states' => [
            'foreign_key' => 'id',
            'model' => 'state_model',
            'field' => 'country_id'
        ]
    ];
}