<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Business_model class
 *
 * @author Victor Rincon
 */
class Business_model extends MY_Model {
    /**
     * Name of the table for this model.
     * @var string
     */
    protected $_table = 'businesses';
    
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
            // Document Type
            'field' => 'document_type_id',
            'label' => 'lang:document_type',
            'rules' => 'trim|required|is_natural_no_zero|exist[document_types.id]'
        ],
        [
            // City
            'field' => 'city_id',
            'label' => 'lang:city',
            'rules' => 'trim|required|is_natural_no_zero|exist[cities.id]'
        ],
        [
            // Trade name
            'field' => 'trade_name',
            'label' => 'lang:trade_name',
            'rules' => 'trim|required|max_length[120]|ucwords'
        ],
        [
            // Business name
            'field' => 'business_name',
            'label' => 'lang:business_name',
            'rules' => 'trim|required|max_length[120]|ucwords'
        ],
        [
            // Document Number
            'field' => 'document_number',
            'label' => 'lang:document_number',
            'rules' => 'trim|required|max_length[100]'
        ],
        [
            // Constitution Date
            'field' => 'constitution_date',
            'label' => 'lang:constitution_date',
            'rules' => 'trim|prep_date_formart'
        ],
        [
            // Address
            'field' => 'address',
            'label' => 'lang:address',
            'rules' => 'trim|required|max_length[150]|ucwords'
        ],
        [
            // Mobile
            'field' => 'mobile',
            'label' => 'lang:mobile',
            'rules' => 'trim|max_length[60]'
        ],
        [
            // Phone
            'field' => 'phone',
            'label' => 'lang:phone',
            'rules' => 'trim|max_length[60]'
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
        'city' => [
            'foreign_key' => 'city_id',
            'model' => 'city_model',
            'field' => 'id'
        ],
        'document_type' => [
            'foreign_key' => 'document_type_id',
            'model' => 'document_type_model',
            'field' => 'id'
        ]
    ];
}
