<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Tenant_model class
 *
 * @author Victor Rincon
 */
class Tenant_model extends MY_Model {
    /**
     * Name of the table for this model.
     * @var string
     */
    protected $_table = 'tenants';
    
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
            // City
            'field' => 'city_id',
            'label' => 'lang:city',
            'rules' => 'trim|required|is_natural_no_zero|exist[cities.id]'
        ],
        [
            // Timezone
            'field' => 'timezone_id',
            'label' => 'lang:timezone',
            'rules' => 'trim|required|is_natural_no_zero|exist[timezones.id]'
        ],
        [
            // Currency
            'field' => 'currency_id',
            'label' => 'lang:currency',
            'rules' => 'trim|required|is_natural_no_zero|exist[currencies.id]'
        ],
        [
            // Date Format
            'field' => 'date_format_id',
            'label' => 'lang:date_format',
            'rules' => 'trim|required|is_natural_no_zero|exist[date_formats.id]'
        ],
        [
            // Name
            'field' => 'name',
            'label' => 'lang:name',
            'rules' => 'trim|required|max_length[250]|ucwords'
        ],
        [
            // Business Name
            'field' => 'business_name',
            'label' => 'lang:business_name',
            'rules' => 'trim|required|max_length[250]|ucwords'
        ],
        [
            // TIN (Taxpayer Identification Number)
            'field' => 'tin',
            'label' => 'lang:tin',
            'rules' => 'trim|required|max_length[30]'
        ],
        [
            // Address
            'field' => 'address',
            'label' => 'lang:address',
            'rules' => 'trim|required|max_length[250]|ucwords'
        ],
        [
            // Phone
            'field' => 'phone',
            'label' => 'lang:phone',
            'rules' => 'trim|required|max_length[60]'
        ],
        [
            // Email
            'field' => 'email',
            'label' => 'lang:email',
            'rules' => 'trim|valid_email|max_length[250]|strtolower'
        ],
        [
            // web
            'field' => 'web',
            'label' => 'lang:web',
            'rules' => 'trim|prep_url|valid_url|max_length[250]|strtolower'
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
        'timezone' => [
            'foreign_key' => 'timezone_id',
            'model' => 'timezone_model',
            'field' => 'id'
        ],
        'date_format' => [
            'foreign_key' => 'date_format_id',
            'model' => 'date_format_model',
            'field' => 'id'
        ],
        'currency' => [
            'foreign_key' => 'currency_id',
            'model' => 'currency_model',
            'field' => 'id'
        ],
        'users' => [
            'foreign_key' => 'id',
            'model' => 'user_model',
            'field' => 'tenant_id'
        ]
    ];
}
