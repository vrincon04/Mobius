<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date_format_model class
 *
 * @author Victor Rincon
 */
class Date_format_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'date_formats';

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
            // format
            'field' => 'format',
            'label' => 'lang:format',
            'rules' => 'trim|required'
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
            'field' => 'date_format_id'
        ]
    ];
}