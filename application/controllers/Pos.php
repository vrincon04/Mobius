<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pos extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'pos';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'pos_model';

	public function index()
	{
		// Cargamos el modelo de Cliente.
		$this->load->model('customer_model');
		// Cargamos el modelo de Caja Registradora.
		$this->load->model('cash_drawer_model');

		if ( $this->cash_drawer_model->get_open() == NULL )
			redirect("{$this->_controller}/cash_register_not_opened");

		$pre_products = array();
        if ( isset($_POST['products']) )
            $pre_products = $_POST['products'];

		$this->data = [
			'styles' => [
				'public/plugins/select2/css/select2.min.css',
				'public/plugins/select2/css/select2-bootstrap.css'
			],
			'scripts' => [
				'public/plugins/autosize/autosize.js',
				'public/plugins/jquery-sheepIt/jquery.sheepItPlugin.js',
				'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
				'public/plugins/select2/js/select2.full.min.js',
				'public/plugins/select2/js/i18n/' . $this->session->userdata('lang') . '.js',
				'public/plugins/jquery-maskMoney/jquery.maskMoney.js',
                'public/plugins/jquery-maskMoney/jquery.region.maskMoney.js'
			],
			'customers' => $this->customer_model->all(),
			'products' => $pre_products
		];

		// Set the sistema locale configuration.
		$this->set_locale();
		
        $this->_template("{$this->_controller}/template1", $this->_get_assets('create', $this->data), 'pos_layout/main');
	}

	public function pending_orders()
	{
		// Cargamos el modelo de Orden.
		$this->load->model('order_model');

		$data = [
			'orders' => $this->order_model->pending_by_user($this->session->userdata('user_id'))
		];

		$this->_template("{$this->_controller}/order_list", $this->_get_assets('create', $this->data), 'pos_layout/main');
	}

	public function send_order()
	{
		
		//
		$this->load->model('order_model');
		//
		$this->load->model('ticket_model');
		//
		$id = $this->order_model->insert($this->input->post());

		if ( $id ) {
			if ( $this->insert_detial($id) )
			{
				$this->ticket_model->printOrder($id);
			} else {
				$this->_return_json_error(validation_errors());
			}

			$this->_return_json_success(lang('success_message'), $id);
		}
		else {
			$this->_return_json_error(validation_errors());
		}
	}

	public function pending_invoices()
	{
		
	}

	public function cash_register_not_opened()
	{
		$this->load->view('errors/html/error_cash_drawer', [
			'heading' => lang('cash_register_not_opened'),
			'message' => lang('contact_your_supervisor_or_administrator'),
			'code' => 601
		]);
	}

	private function _insert_order_detial($id)
	{
		$main_total = 0;

		$this->load->model('order_detail_model');
		$this->load->model('stock_model');

        foreach ($this->input->post('products') as $product) {
			$stock = $this->product_model->find([
				'select' => 'id, product_id, warehoese_id',
				'limit' => 1,
				'where' => [
					'product_id' => $product['product_id'],
					'warehoese_id' => $this->sessiion->userdata('warehoese_id')
				]
			])->with([
				'product' => [
					'components'
				]
			]);

			if ( $productDb->is_composed == 1 ) {
				foreach ($productDb->components as $component) {
					$count = (float)$component->quantity * (float)($product['quantity']);
					// decrease the stock.
					$this->item_history_model->decrease($stock->id, $count, 'Producto despachado en la orden <a href="' . base_url("order/view/{$id}") . '">#' . str_pad($id, 6, '0', STR_PAD_LEFT) . '</a>');
				}
			} else {
				// decrease the stock.
				$this->item_history_model->decrease($stock->id, $product['quantity'], 'Producto despachado en la orden <a href="' . base_url("order/view/{$id}") . '">#' . str_pad($id, 6, '0', STR_PAD_LEFT) . '</a>');
			}
            // Removemos el id del arreglo.
            unset($product['id']);
            // Insertamos el id del producto.
			$product['order_id'] = $id;
			$product['price'] = $product['sale'];
			$product['total'] = (float) preg_replace("/[^0-9.]/", "", $product['quantity']) * (float) preg_replace("/[^0-9.]/", "", $product['price']);
			// Insertamos nuestro nueva existencia en la base de datos.
			$result = $this->order_detail_model->insert($product);
			// Sumamos el total del la linea con el gran total.
			$main_total += (float) $product['total'];
			// Validamos si se inserto correctamente
            if (! $result ) 
            {
                $this->_response_error(validation_errors());
                return FALSE;
			}
		}

        return TRUE;
	}
}
