<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Product_component_model class
 *
 * @author Victor Rincon
 */
class Product_component_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'product_components';

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
            // Product
            'field' => 'product_id',
            'label' => 'lang:user',
            'rules' => 'trim|required|is_natural_no_zero|exist[products.id]'
        ],
        [
            // Quantity
            'field' => 'quantity',
            'label' => 'lang:quantity',
            'rules' => 'trim|prep_currency_format|required|decimal'
        ],
        [
            // Cost
            'field' => 'cost',
            'label' => 'lang:cost',
            'rules' => 'trim|prep_currency_format|required|decimal'
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
        'product' => [
            'foreign_key' => 'product_id',
            'model' => 'product_model',
            'field' => 'id'
        ]
    ];
}