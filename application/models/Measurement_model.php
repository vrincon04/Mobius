<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Measurement_model class
 *
 * @author Victor Rincon
 */
class Measurement_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'measurements';

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
            // Type
            'field' => 'type_id',
            'label' => 'lang:type_unit',
            'rules' => 'trim|required|is_natural_no_zero|exist[types_measures.id]'
        ],
        [
            // Name
            'field' => 'name',
            'label' => 'lang:name',
            'rules' => 'trim|required|max_length[100]|ucwords'
        ],
        [
            // Lang
            'field' => 'lang',
            'label' => 'lang:lang',
            'rules' => 'trim|required|max_length[100]'
        ],
        [
            // Description
            'field' => 'description',
            'label' => 'lang:description',
            'rules' => 'trim'
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
        'type_measure' => [
            'foreign_key' => 'type_id',
            'model' => 'type_measure_model',
            'field' => 'id'
        ]
    ];
}