<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Expiration_type_model class
 *
 * @author Victor Rincon
 */
class Expiration_type_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'expirations_types';

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
            'rules' => 'trim|required|max_length[60]|ucwords'
        ],
        [
            // Language
            'field' => 'lang',
            'label' => 'lang:lang',
            'rules' => 'trim|required|max_length[60]'
        ],
        [
            // Value
            'field' => 'value',
            'label' => 'lang:value',
            'rules' => 'trim'
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
        'purchases' => [
            'foreign_key' => 'id',
            'model' => 'purchase_model',
            'field' => 'expiration_type_id'
        ]
    ];
}