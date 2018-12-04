<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Access_model class
 *
 * @author Victor Rincon
 */
class Access_model extends MY_Model {
    /**
     * @var string
     */
    protected $_table = 'accesses';

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
            // Status
            'field' => 'status',
            'label' => 'lang:status',
            'rules' => 'trim|in_list[normal,locked,warning]'
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
}