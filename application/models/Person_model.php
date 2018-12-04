<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Person_model class
 *
 * @author Victor Rincon
 */
class Person_model extends MY_Model {
    /**
     * Name of the table for this model.
     * @var string
     */
    protected $_table = 'persons';
    
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
            // Gender
            'field' => 'gender_id',
            'label' => 'lang:gender',
            'rules' => 'trim|required|is_natural_no_zero|exist[genders.id]'
        ],
        [
            // City
            'field' => 'city_id',
            'label' => 'lang:city',
            'rules' => 'trim|required|is_natural_no_zero|exist[cities.id]'
        ],
        [
            // First Name
            'field' => 'first_name',
            'label' => 'lang:first_name',
            'rules' => 'trim|required|max_length[60]|ucwords'
        ],
        [
            // Middle Name
            'field' => 'middle_name',
            'label' => 'lang:middle_name',
            'rules' => 'trim|max_length[60]|ucwords'
        ],
        [
            // Last Name
            'field' => 'last_name',
            'label' => 'lang:last_name',
            'rules' => 'trim|required|max_length[70]|ucwords'
        ],
        [
            // Last Name 2
            'field' => 'last_name2',
            'label' => 'lang:last_name',
            'rules' => 'trim|max_length[70]|ucwords'
        ],
        [
            // Document Number
            'field' => 'document_number',
            'label' => 'lang:document_number',
            'rules' => 'trim|required|max_length[100]'
        ],
        [
            // Dob
            'field' => 'dob',
            'label' => 'lang:dob',
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
        'gender' => [
            'foreign_key' => 'gender_id',
            'model' => 'gender_model',
            'field' => 'id'
        ],
        'document_type' => [
            'foreign_key' => 'document_type_id',
            'model' => 'document_type_model',
            'field' => 'id'
        ],
        'users' => [
            'foreign_key' => 'id',
            'model' => 'user_model',
            'field' => 'person_id'
        ]
    ];
}
