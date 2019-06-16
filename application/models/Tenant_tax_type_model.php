<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Tenant_tax_type_model class
 *
 * @author Victor Rincon
 */
class Tenant_tax_type_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'tenant_tax_types';

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
            // Tenant
            'field' => 'tenant_id',
            'label' => 'lang:tenant',
            'rules' => 'trim|required|is_natural_no_zero|exist[tenants.id]'
        ],
        [
            // Tax Type
            'field' => 'tax_type_id',
            'label' => 'lang:tax_type',
            'rules' => 'trim|required|is_natural_no_zero|exist[tax_types.id]'
        ],
        [
            // Prefix
            'field' => 'prefix',
            'label' => 'lang:prefix',
            'rules' => 'trim|required|max_length[15]'
        ],
        [
            // From
            'field' => 'from',
            'label' => 'lang:from',
            'rules' => 'trim|required|numeric'
        ],
        [
            // To
            'field' => 'to',
            'label' => 'lang:to',
            'rules' => 'trim|required|numeric'
        ],
        [
            // Is Active
            'field' => 'is_active',
            'label' => 'lang:is_active',
            'rules' => 'trim'
        ],
        [
            //Expired At
            'field' => 'expired_at',
            'label' => 'lang:expired_at',
            'rules' => 'trim|required|prep_date_formart'
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
        'tenant' => [
            'foreign_key' => 'tenant_id',
            'model' => 'tenant_model',
            'field' => 'id'
        ],
        'taxt_type' => [
            'foreign_key' => 'tax_type_id',
            'model' => 'tax_type_model',
            'field' => 'id'
        ],
    ];

    public function get_all_type($tenant_id, $array_result = FALSE, $debug = FALSE,  $object_class = 'base_register')
    {
        $query = $this->db->query("SELECT tax_types.lang, tax_types.id AS type_id, tenant_tax_types.*
        FROM `tax_types`
        LEFT JOIN `tenant_tax_types`
        ON `tax_types`.id = `tenant_tax_types`.`tax_type_id` AND tenant_id = $tenant_id");

        return ($array_result) ? $query->result_array() : $query->result($object_class);
    }
}