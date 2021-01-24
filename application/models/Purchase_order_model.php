<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Purchase_order_model class
 *
 * @author Victor Rincon
 */
class Purchase_order_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'purchase_orders';

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
            // Provider
            'field' => 'provider_id',
            'label' => 'lang:provider',
            'rules' => 'trim|required|is_natural_no_zero|exist[providers.id]'
        ],
        [
            // Warehouse
            'field' => 'warehouse_id',
            'label' => 'lang:warehouse',
            'rules' => 'trim|required|is_natural_no_zero|exist[warehouses.id]'
        ],
        [
            // Currency
            'field' => 'currency_id',
            'label' => 'lang:currency',
            'rules' => 'trim|required|is_natural_no_zero|exist[currencies.id]'
        ],
        [
            // Code
            'field' => 'code',
            'label' => 'lang:code',
            'rules' => 'trim|min_length[3]|max_length[20]'
        ],
        [
            // Reference
            'field' => 'reference',
            'label' => 'lang:reference',
            'rules' => 'trim|min_length[4]|max_length[25]'
        ],
        [
            // Annotations
            'field' => 'annotations',
            'label' => 'lang:annotations',
            'rules' => 'trim|max_length[500]'
        ],
        [
            // Status
            'field' => 'status',
            'label' => 'lang:status',
            'rules' => 'trim|in_list[draft,pending,partial,close]'
        ],
        [
            // Total
            'field' => 'total',
            'label' => 'lang:total',
            'rules' => 'trim|prep_currency_format|decimal'
        ],
        [
            // Date
            'field' => 'date',
            'label' => 'lang:date',
            'rules' => 'trim|required|prep_date_formart'
        ],
        [
            //Expected At
            'field' => 'expected_at',
            'label' => 'lang:expected_at',
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
        'user' => [
            'foreign_key' => 'user_id',
            'model' => 'user_model',
            'field' => 'id'
        ],
        'provider' => [
            'foreign_key' => 'provider_id',
            'model' => 'provider_model',
            'field' => 'id'
        ],
        'warehouse' => [
            'foreign_key' => 'warehouse_id',
            'model' => 'warehouse_model',
            'field' => 'id'
        ],
        'currency' => [
            'foreign_key' => 'currency_id',
            'model' => 'currency_model',
            'field' => 'id'
        ],
        'details' => [
            'foreign_key' => 'id',
            'model' => 'purchase_order_detail_model',
            'field' => 'purchase_order_id'
        ]
    ];

    /**
	 * [datatable_json description]
	 * @return string|object Listado de registro encontrado segun los filtros aplicados
	 */
	public function datatable_json()
	{
		$this->load->library('datatables');

		$this->datatables->select("
            {$this->_table}.id,
            {$this->_table}.code,
            {$this->_table}.reference,
            {$this->_table}.total,
            {$this->_table}.status,
            {$this->_table}.date,
            {$this->_table}.expected_at,
			persons.first_name,
            persons.last_name,
            SUM(purchase_order_details.quantity) AS quantity,
            SUM(purchase_order_details.starters) AS starters
        ")->from($this->_table)
        ->join('purchase_order_details', "{$this->_table}.id = purchase_order_details.purchase_order_id")
        ->join("(
                SELECT providers.id, persons.first_name, persons.middle_name, persons.last_name, persons.last_name2
                FROM providers
                INNER JOIN persons ON providers.entity_id = persons.id AND providers.entity_type = 'person'
                UNION
                SELECT providers.id, businesses.trade_name, 'N/A', businesses.business_name, 'N/A'
                FROM providers
                INNER JOIN businesses ON providers.entity_id = businesses.id AND providers.entity_type = 'business'
            ) AS providers", "{$this->_table}.provider_id = providers.entity_id", 'INNER', FALSE)
        ->where("{$this->_table}.tenant_id", $this->session->userdata('tenant_id'))
        ->group_by("{$this->_table}.id");

		return $this->datatables->generate();
    }

    public function sum_items($id) 
    {
        return $this->db->select('SUM(quantity) AS quantity, SUM(starters) AS starters')
            ->from('purchase_order_details')
            ->where('purchase_order_id', $id)
            ->get()
            ->row(1);
    }
    
    /**
     * Close the Purchase Order
     * 
     * @param int $id the id of purcha order
     * @return bool
     */
    public function close($id)
    {
        $result = $this->update($id, [
            'status' => 'close'
        ]);

        if ( $result )
        {
            return $this->create_order($id);
        }

        return false;
    }

    public function partial($id)
    {
        return $this->update($id, [
            'status' => 'partial'
        ]);
    }

    public function analyze($id, $count)
    {
        if( $count > 0)
        {
            return $this->partial($id);
        }
        else
        {
            return $this->close($id);
        }
    }

    /**
     * Duplicate a purchase Order
     * 
     * @param int $id the id of purchase order
     * @return int|string|bool
     */
    public function duplicate($id)
    {
        // Load the purchase order detail model.
        $this->load->model('purchase_order_detail_model');
        // Get the purchas order with details.
        $row = $this->get($id)->with(['details']);
        $row->date = strftime("%d %B %Y");
        $row->expected_at = strftime("%d %B %Y");
        $row->status = 'pending';

        $data = (array) $row;
        unset($data['id']);

        if ( $purchaseOrderId = $this->insert($data) )
        {
            // We iterated the elements that the purchase order has
            foreach ($row->details as $detail) {
                $detail->starters = 0;
                $detail->purchase_order_id = $purchaseOrderId;

                $detail = (array) $detail;
                unset($detail['id']);
                
                $this->purchase_order_detail_model->insert($detail);
            }

            return $purchaseOrderId;
        }

        return false;
    }

    /**
     * Create a purcha from purchase Order
     * 
     * @param int $id the id of purchase order
     * @return int|string|bool
     */
    protected function create_order($id)
    {
        // Load the purchase model.
        $this->load->model('purchase_model');
        // Load the purchase detail model.
        $this->load->model('purchase_detail_model');
        // Load the Product model.
        $this->load->model('product_model');
        
        // Get the purchase order.
        $row = $this->get($id)->with(['details']);
        $row->status = 'draft';
        $row->reference = "OC" . str_pad($row->id, 6, "0", STR_PAD_LEFT);
        $row->date = strftime("%d %B %Y");

        $data = (array) $row;
        $data['expired_at'] = strftime("%d %B %Y");
        $data['expiration_type_id'] = 7;
        unset($data['id']);

        // insert the purchase order in the purchase.
        $purchaseId = $this->purchase_model->insert($data);

        if ( $purchaseId )
        {
            // We iterated the elements that the purchase order has.
            foreach ($row->details as $detail) {
                $detail->quantity = $detail->starters;
                $detail->total = ($detail->starters * $detail->cost);

                $detail = (array) $detail;
                $detail['purchase_id'] = $purchaseId;
                unset($detail['id']);

                $this->purchase_detail_model->insert($detail);
                $this->product_model->update($detail->product_id, ['cost' => $detail->cost]);
            }

            return $purchaseId;
        }

        return false;
    }
}