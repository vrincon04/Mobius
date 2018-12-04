<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * City_model class
 *
 * @author Victor Rincon
 */
class City_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'cities';

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
            // State
            'field' => 'state_id',
            'label' => 'lang:state',
            'rules' => 'trim|required|is_natural_no_zero|exist[states.id]'
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
        'state' => [
            'foreign_key' => 'state_id',
            'model' => 'state_model',
            'field' => 'id'
        ],
        'persons' => [
            'foreign_key' => 'id',
            'model' => 'person_model',
            'field' => 'city_id'
        ],
        'tenants' => [
            'foreign_key' => 'id',
            'model' => 'tenant_model',
            'field' => 'city_id'
        ]
    ];
}