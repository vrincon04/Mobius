<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Tax_type_model class
 *
 * @author Victor Rincon
 */
class Tax_type_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'tax_types';

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
        'tenants' => [
            'foreign_key' => 'id',
            'model' => 'tenant_model',
            'field' => 'tax_type_id'
        ]
    ];
}