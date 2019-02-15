<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Purchase_order class
 *
 * @author Victor Rincon
 */
class Purchase_order extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'purchase_order';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'purchase_order_model';

    /**
     * 
     * @return void
     */
    public function datatable_json()
	{
		echo $this->{$this->_model}->datatable_json();
	}
	
	public function test_json($value, $value2)
	{
		echo json_encode((float) preg_replace("/[^0-9.]/", "", $value) * (float) preg_replace("/[^0-9.]/", "", $value2));

	}
    
    public function create()
    {
        // Load the model's
		$this->load->model('warehouse_model');
		$this->load->model('currency_model');
        
        $this->_assets_create = [
			'styles' => [
				'public/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css',
				'public/plugins/waitme/waitMe.css',
				'public/plugins/bootstrap-select/css/bootstrap-select.css',
				'public/plugins/select2/css/select2.min.css',
				'public/plugins/select2/css/select2-bootstrap.css'
			],
			'scripts' => [
				'public/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
				'public/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.' . $this->session->userdata('lang') . '.min.js',
				'public/plugins/autosize/autosize.js',
				'public/plugins/jquery-sheepIt/jquery.sheepItPlugin.js',
				'public/plugins/jquery-validation/jquery.validate.js',
				'public/plugins/jquery-validation/additional-methods.js',
				'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
				'public/plugins/select2/js/select2.full.min.js',
				'public/plugins/select2/js/i18n/' . $this->session->userdata('lang') . '.js',
				'public/plugins/jquery-maskMoney/jquery.maskMoney.js',
                'public/plugins/jquery-maskMoney/jquery.region.maskMoney.js'
			]
		];

		$pre_products = array();
        if ( isset($_POST['products']) )
            $pre_products = $_POST['products'];

        $this->data = [
			'warehouses' => $this->warehouse_model->dropdown('id', 'name'),
			'currencies' => $this->currency_model->dropdown('id', 'unit', [
				'select' => 'currencies.id, CONCAT (currencies.code, " (", currencies.symbol, ")") AS unit' ,
				'where' => ['is_active' => 1]
			]),
			'products' => $pre_products,
		];
        
        parent::create();
    }

    public function edit($id)
	{
		// Load the model's
		$this->load->model('warehouse_model');
		$this->load->model('currency_model');
		$this->load->model('purchase_order_detail_model');
        
		$this->_assets_edit = [
			'styles' => [
				'public/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css',
				'public/plugins/waitme/waitMe.css',
				'public/plugins/bootstrap-select/css/bootstrap-select.css',
				'public/plugins/select2/css/select2.min.css',
				'public/plugins/select2/css/select2-bootstrap.css'
			],
			'scripts' => [
				'public/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
				'public/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.' . $this->session->userdata('lang') . '.min.js',
				'public/plugins/autosize/autosize.js',
				'public/plugins/jquery-sheepIt/jquery.sheepItPlugin.js',
				'public/plugins/jquery-validation/jquery.validate.js',
				'public/plugins/jquery-validation/additional-methods.js',
				'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
				'public/plugins/select2/js/select2.full.min.js',
				'public/plugins/select2/js/i18n/' . $this->session->userdata('lang') . '.js',
				'public/plugins/jquery-maskMoney/jquery.maskMoney.js',
                'public/plugins/jquery-maskMoney/jquery.region.maskMoney.js'
			]
		];

		$pre_products = array();
		
		if ( isset($_POST['products']) ) 
		{ 
			$pre_products = $_POST['products']; 
		}
		else 
		{ 
			$pre_products = (array) $this->purchase_order_detail_model->find([
				'select' 	=> "purchase_order_details.*, products.name, products.sale, (
					SELECT SUM(stocks.count) FROM stocks WHERE stocks.product_id = products.id
				) AS stock",
				'where' 	=> ['purchase_order_id' => $id],
				'joins' 	=> [
					['products', 'purchase_order_details.product_id = products.id', 'INNER']
				]
			]);
		}

		$this->data = [
			'warehouses' => $this->warehouse_model->dropdown('id', 'name'),
			'currencies' => $this->currency_model->dropdown('id', 'unit', [
				'select' => 'currencies.id, CONCAT (currencies.code, " (", currencies.symbol, ")") AS unit' ,
				'where' => ['is_active' => 1]
			]),
			'products' => $pre_products,
		];

		parent::edit($id);
	}

	public function duplicate($id)
	{
		$row = $this->_exist($id);

		if ( $result = $this->{$this->_model}->duplicate($id) )
		{
			redirect("{$this->_controller}/view/{$result}");
		}
		else
		{
			$this->_response_error(validation_errors());
		}

		redirect("{$this->_controller}/view/{$id}");
	}

	public function receive($id)
	{
		$row = $this->_exist($id);

		$this->_assets_create = [
			'styles' => [
				'public/plugins/waitme/waitMe.css',
			],
			'scripts' => [
				'public/plugins/jquery-validation/jquery.validate.js',
				'public/plugins/jquery-validation/additional-methods.js',
				'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
				'public/plugins/jquery-maskMoney/jquery.maskMoney.js',
                'public/plugins/jquery-maskMoney/jquery.region.maskMoney.js'
			]
		];

		if ( $this->input->method() === 'post' ) {
			// Load purchase order details.
			$this->load->model('purchase_order_detail_model');
			// Load stock.
			$this->load->model('stock_model');
			// Load Item History.
			$this->load->model('item_history_model');
			// Contador de pruductos entregados en su totalidad.
			$count = 0;

			foreach ($this->input->post('receive') as $key => $value) {
				$value = preg_replace("/[^0-9.]/", "", $value);
				$_POST['starters'][$key] += $value;

				if ((float)$_POST['starters'][$key] < (float)$_POST['quantity'][$key])
					$count++;
				
				if ( ! $this->purchase_order_detail_model->update($key, ['starters' => $_POST['starters'][$key]]) )
				{
					$this->_response_error(validation_errors());
					break;
				}
				// find the stock.
				$stock = $this->stock_model->find([
					'where' => [
						'warehouse_id' => $row->warehouse_id,
						'product_id' => $_POST['product'][$key]
					],
					'limit' => 1
				]);

				// Increase the stock.
				$this->item_history_model->increase($stock->id, $value, lang('adjustment_from_purchase_order_no') . ' <a href="' . base_url("purchase_order/view/{$id}") . '">#' . str_pad($id, 6, '0', STR_PAD_LEFT) . '</a>');
			}

			if ( $this->{$this->_model}->analyze($this->input->post('purchase_order_id'), $count) )
			{
                $this->_response_success();

                if ( !$this->input->is_ajax_request() )
                {
                    redirect("{$this->_controller}/view/{$id}");
                }
			}
            else
            {
                $this->_response_error(validation_errors());
            }
		}

		$this->data[$this->_controller] = $row;
		$this->_template("{$this->_controller}/receive", $this->_get_assets('create', $this->data));
	}
	
	protected function _on_insert_success($id)
    {
        return $this->_insertDetails($id);
	}
	
	protected function _on_edit_success($id)
	{
		$this->_deleteDetails($id);
		$this->_insertDetails($id);
	}

    protected function _insertDetails($id)
    {
		$main_total = 0;
        $this->load->model('purchase_order_detail_model');

        foreach ($this->input->post('products') as $product) {
            // Removemos el id del arreglo.
            unset($product['id']);
            // Insertamos el id del producto.
			$product['purchase_order_id'] = $id;
			$product['starters'] = '0.00';
			$product['total'] = (float) preg_replace("/[^0-9.]/", "", $product['quantity']) * (float) preg_replace("/[^0-9.]/", "", $product['cost']);
			// Insertamos nuestro nueva existencia en la base de datos.
			$result = $this->purchase_order_detail_model->insert($product);
			// Sumamos el total del la linea con el gran total.
			$main_total += (float) $product['total'];
			// Validamos si se inserto correctamente
            if (! $result ) 
            {
                $this->_response_error(validation_errors());
                return FALSE;
            }
		}
		
		$this->{$this->_model}->update($id, ['total' => $main_total]);
        return TRUE;
	}
	
	protected function _after_exist($row)
	{
		$row->with(['details', 'currency', 'warehouse', 'user' => ['person'], 'provider' => ['person']]);
		$items = $this->{$row->object_model}->sum_items($row->id);
		$row->quantity = $items->quantity;
		$row->starters = $items->starters;
		$row->percentage = ( $items->starters / $items->quantity ) * 100;

		return $row;
	}

	protected function _deleteDetails($id)
	{
		$this->load->model('purchase_order_detail_model');

		return $this->purchase_order_detail_model->delete_where("purchase_order_id = {$id}");
	}
}