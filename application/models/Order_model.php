<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Order_model class
 *
 * @author Victor Rincon
 */
class Order_model extends MY_Model {
    /**
     * Name of the table for this model.
     * @var string
     */
    protected $_table = 'orders';

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
            // User
            'field' => 'user_id',
            'label' => 'lang:user',
            'rules' => 'trim|required|is_natural_no_zero|exist[users.id]'
        ],
        [
            // Customer
            'field' => 'customer_id',
            'label' => 'lang:customer',
            'rules' => 'trim|required|is_natural_no_zero|exist[customers.id]'
        ],
        [
            // Date
            'field' => 'date',
            'label' => 'lang:date',
            'rules' => 'trim|prep_date_formart'
        ],
        [
            // Delivered At
            'field' => 'delivered_at',
            'label' => 'lang:delivered_at',
            'rules' => 'trim|prep_date_formart'
        ],
        [
            // Status
            'field' => 'status',
            'label' => 'lang:status',
            'rules' => 'trim|in_list[canceled,pending,dispatched,invoiced]'
        ],
        [
            // Subtotal
            'field' => 'subtotal',
            'label' => 'lang:subtotal',
            'rules' => 'trim|prep_currency_format|decimal'
        ],
        [
            // Tax
            'field' => 'tax',
            'label' => 'lang:tax',
            'rules' => 'trim|prep_currency_format|decimal'
        ],
        [
            // Total
            'field' => 'total',
            'label' => 'lang:total',
            'rules' => 'trim|prep_currency_format|decimal'
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
        'user' => [
            'foreign_key' => 'user_id',
            'model' => 'user_model',
            'field' => 'id'
        ],
        'customer' => [
            'foreign_key' => 'customer_id',
            'model' => 'customer_model',
            'field' => 'id'
        ],
        'details' => [
            'foreign_key' => 'id',
            'model' => 'order_detail_model',
            'field' => 'order_id'
        ]
    ];

    public function pending()
    {
        return $this->find([
            'where' => [
                'status' => 'pending'
            ]
        ]);
    }

    public function pending_by_user($user)
    {
        return $this->find([
            'where' => [
                'status' => 'pending',
                'user_id' => $user
            ]
        ]);
    }

    /**
     * Create a invoice from Order
     * 
     * @param int $id the id of order
     * @return int|string|bool
     */
    public function create_invoice($id, $tax_type)
    {
        $this->load->model('invoice_model');
        $this->load->model('invoice_detail_model');
        
        // Get the purchase order.
        $row = $this->get($id)->with(['details']);
        $row->status = 'draft';
        $row->date = strftime("%d %B %Y");

        $data = (array) $row;
        $data['expired_at'] = strftime("%d %B %Y");
        $data['expiration_type_id'] = 7;
        $data['order_id'] = $id;
        $data['user_id'] = $this->session->userdata('user_id');
        $data['currency_id'] = $this->session->userdata('currency_id');
        $data['tax_type_id'] = $tax_type;
        $data['balance'] = $row->total;
        unset($data['id']);

        // insert the purchase order in the purchase.
        $invoiceId = $this->invoice_model->insert($data);

        if ( $invoiceId )
        {
            // We iterated the elements that the purchase order has.
            foreach ($row->details as $detail) {
                $detail = (array) $detail;
                $detail['invoice_id'] = $invoiceId;
                unset($detail['id']);

                $this->invoice_detail_model->insert($detail);
            }

            return $invoiceId;
        }

        return false;
    }
}