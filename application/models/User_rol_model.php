<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User_rol_model class
 *
 * @author Victor Rincon
 */
class User_rol_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'users_roles';

    /**
     * Primary keies for this model.
     * @var array
     */
    protected $_pk = ['user_id','rol_id'];

    /**
     * Validation rules for this model.
     * @var array
     */
    protected $_validation_rules = [
        [
            // User
            'field'     => 'user_id',
            'label'     => 'lang:user',
            'rules'     => 'trim|required|is_natural_no_zero|exist[users.id]'
        ],
        [
            // Rol
            'field'     => 'rol_id',
            'label'     => 'lang:rol',
            'rules'     => 'trim|required|is_natural_no_zero|exist[roles.id]'
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
        'user' => [
            'foreign_key' => 'user_id',
            'model' => 'user_model',
            'field' => 'id'
        ],
        'rol' => [
            'foreign_key' => 'rol_id',
            'model' => 'rol_model',
            'field' => 'id'
        ]
    ];
}