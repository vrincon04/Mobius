<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Item_history_model class
 *
 * @author Victor Rincon
 */
class Item_history_model extends MY_Model {
    /**
     * Name of the table to which a social model is going.
     * @var string
     */
    protected $_table = 'items_histories';

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
            // User
            'field' => 'user_id',
            'label' => 'lang:user',
            'rules' => 'trim|required|is_natural_no_zero|exist[users.id]'
        ],
        [
            // Stock
            'field' => 'stock_id',
            'label' => 'lang:stock',
            'rules' => 'trim|required|is_natural_no_zero|exist[stocks.id]'
        ],
        [
            // Type
            'field' => 'type_id',
            'label' => 'lang:type',
            'rules' => 'trim|required|is_natural_no_zero|exist[types_transfers.id]'
        ],
        [
            // Description
            'field' => 'description',
            'label' => 'lang:description',
            'rules' => 'trim'  
        ],
        [
            // Count
            'field' => 'count',
            'label' => 'lang:count',
            'rules' => 'trim|prep_currency_format|required|decimal'
        ],
        [
            // Balance
            'field' => 'balance',
            'label' => 'lang:balance',
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
        'user' => [
            'foreign_key' => 'user_id',
            'model' => 'user_model',
            'field' => 'id'
        ],
        'stock' => [
            'foreign_key' => 'stock_id',
            'model' => 'stock_model',
            'field' => 'id'
        ],
        'type' => [
            'foreign_key' => 'type_id',
            'model' => 'transfer_model',
            'field' => 'id'
        ],
    ];

    public function get_balance($stockId)
    {
        $result = $this->last([
            'where' => ['stock_id' => $stockId],
            'select' => 'IFNULL(balance, 0) AS balance'
        ]);

        return ($result == null) ? 0 : $result->balance;
    }
    
    public function increase($stockId, $count, $description, $type=1)
    {
        $value = preg_replace("/[^0-9.]/", "", $count);
        $insert = $this->insert([
            'count' => $value,
            'type_id' => $type,
            'stock_id' => $stockId,
            'balance' => $this->get_balance($stockId) + $value,
            'description' => $description
        ]);

        if ( $insert )
        {
            if ( $this->update_stock($stockId) )
            {
                return $insert;
            } 
            
            return false;
        }

        return false;
    }

    public function decrease($stockId, $count, $description)
    {
        $value = preg_replace("/[^0-9.]/", "", $count);

        if ($this->validate_negative($stockId, $value))
        {
            throw new Exception('No cuenta con suficiente articulos', 1);
        }

        $insert = $this->insert([
            'count' => $value,
            'type_id' => 2,
            'stock_id' => $stockId,
            'balance' => $this->get_balance($stockId) - $value,
            'description' => $description
        ]);

        if ( $insert )
        {
            if ( $this->update_stock($stockId) )
            {
                return $insert;
            } 
            
            return false;
        }

        return false;
    }
    
    protected function validate_negative($stockId, $count)
    {
        return (($this->get_balance($stockId) - $count) < 0);
    }

    protected function update_stock($stockId)
    {
        // Load the stock model.
        $this->load->model('stock_model');

        return $this->stock_model->update($stockId, [
            'count' => $this->get_balance($stockId)
        ]);
    }
}