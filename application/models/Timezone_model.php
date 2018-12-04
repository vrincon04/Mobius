<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Timezone_model class
 *
 * @author Victor Rincon
 */
class Timezone_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'timezones';

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
            // Continent
            'field' => 'continent_id',
            'label' => 'lang:continent',
            'rules' => 'trim|required|is_natural_no_zero|exist[continents.id]'
        ],
        [
            // Name
            'field' => 'name',
            'label' => 'lang:name',
            'rules' => 'trim|required|max_length[150]|ucwords'
        ],
        [
            // Format
            'field' => 'format',
            'label' => 'lang:format',
            'rules' => 'trim|required|max_length[150]'
        ],
        [
            // Hour
            'field' => 'hour',
            'label' => 'lang:hour',
            'rules' => 'trim|required|max_length[6]'
        ],
        [
            // Local Time Name
            'field' => 'lc_time_names',
            'label' => 'lang:lc_time_names',
            'rules' => 'trim|required|max_length[10]'
        ],
        [
            // Language
            'field' => 'language',
            'label' => 'lang:language',
            'rules' => 'trim|required|max_length[30]'
        ],
        [
            // ISO Lang
            'field' => 'lang',
            'label' => 'lang:lang',
            'rules' => 'trim|required|max_length[3]'
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
        'country' => [
            'foreign_key' => 'country_id',
            'model' => 'country_model',
            'field' => 'id'
        ],
        'cities' => [
            'foreign_key' => 'id',
            'model' => 'city_model',
            'field' => 'state_id'
        ]
    ];
}