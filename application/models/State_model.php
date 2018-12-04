<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * State_model class
 *
 * @author Victor Rincon
 */
class State_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'states';

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
            // Country
            'field' => 'country_id',
            'label' => 'lang:country',
            'rules' => 'trim|required|is_natural_no_zero|exist[countries.id]'
        ],
        [
            // Name
            'field' => 'name',
            'label' => 'lang:name',
            'rules' => 'trim|required|max_length[150]|ucwords'
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