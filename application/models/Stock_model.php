<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Stock_model class
 *
 * @author Victor Rincon
 */
class Stock_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'stocks';

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
            // Warehouse
            'field' => 'warehouse_id',
            'label' => 'lang:warehouse',
            'rules' => 'trim|required|is_natural_no_zero|exist[warehouses.id]'
        ],
        [
            // Measurement
            'field' => 'measurement_id',
            'label' => 'lang:measurement',
            'rules' => 'trim|required|is_natural_no_zero|exist[measurements.id]'
        ],
        [
            // Product
            'field' => 'product_id',
            'label' => 'lang:product',
            'rules' => 'trim|required|is_natural_no_zero|exist[products.id]'
        ],
        [
            // Cost
            'field' => 'cost',
            'label' => 'lang:cost',
            'rules' => 'trim|prep_currency_format|required|decimal'
        ],
        [
            // Count
            'field' => 'count',
            'label' => 'lang:count',
            'rules' => 'trim|prep_currency_format|required|decimal'
        ],
        [
            // Min
            'field' => 'min',
            'label' => 'lang:min',
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
        'warehouse' => [
            'foreign_key' => 'warehouse_id',
            'model' => 'warehouse_model',
            'field' => 'id'
        ],
        'measurement' => [
            'foreign_key' => 'measurement_id',
            'model' => 'measurement_model',
            'field' => 'id'
        ],
        'product' => [
            'foreign_key' => 'produc_id',
            'model' => 'product_model',
            'field' => 'id'
        ]
    ];
}