<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Modul_model class
 *
 * @author Victor Rincon
 */
class Modul_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'modules';

    /**
     * Primary keies for this model.
     * @var array
     */
    protected $_pk = ['user_id','role_id'];

    /**
     * Validation rules for this model.
     * @var array
     */
    protected $_validation_rules = [
        // Id
        [
            'field' => 'id',
            'label' => 'id',
            'rules' => 'is_natural_no_zero'
        ],
        // Name
        [
            'field' => 'name',
            'label' => 'lang:name',
            'rules' => 'trim|required|max_length[60]|ucwords'
        ],
        // Language
        [
            'field' => 'lang',
            'label' => 'lang:language',
            'rules' => 'trim|required|max_length[60]|alpha_numeric_spaces'
        ],
        // Description
        [
            'field' => 'description',
            'label' => 'lang:description',
            'rules' => 'trim'
        ],
        // Controller
        [
            'field' => 'controller',
            'label' => 'lang:controller',
            'rules' => 'trim|min_length[2]|max_length[30]|is_unique[modules.controller]|strtolower'
        ],
        // Active
        [
            'field'     => 'is_active',
			'label'     => 'lang:active',
			'rules'     => 'trim|less_than[2]'
        ],
        // Action Crete
        [
            'field'     => 'action_create',
            'label'     => 'lang:method_create',
            'rules'     => 'trim|less_than[2]'
        ],
        // Action View
        [
            'field'     => 'action_view',
            'label'     => 'lang:method_view',
            'rules'     => 'trim|less_than[2]'
        ],
        // Action Edit
        [
            'field'     => 'action_edit',
            'label'     => 'lang:method_edit',
            'rules'     => 'trim|less_than[2]'
        ],
        // Action Delete
        [
            'field'     => 'action_delete',
            'label'     => 'lang:method_delete',
            'rules'     => 'trim|less_than[2]'
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
        'grants' => [
            'foreign_key' => 'id',
            'model' => 'gran_model',
            'field' => 'model_id'
        ],
    ];
}