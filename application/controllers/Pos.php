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
    protected $_model = 'order_model';

	public function index()
	{
		// Load the model's
		$this->load->model('customer_model');
		$this->load->model('cash_drawer_model');
		$this->load->model('area_model');
		$this->load->model('tenant_tax_type_model');
		$this->load->model('currency_model');
		$this->load->model('payment_method_model');

		/**
		 * Buscamos todos los empelados que pertenescan a una area habil 
		 * para el POS.
		 */
		$areas = $this->area_model->find([
			'where' => [
				'is_pos' => true
			]
		]);

		$employees = [];
		
		foreach ($areas as $area) {

			$employees = array_merge($employees, $area->with(['employees'], TRUE)->employees);
		}

		if ( $this->cash_drawer_model->get_open() == NULL )
			redirect("{$this->_controller}/cash_register_not_opened");

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
				'public/plugins/jquery-maskMoney/jquery.region.maskMoney.js',
				'public/plugins/waitme/waitMe.js',
				'public/js/src/template_pos/touch_monitor.js'
			],
			'customers' => $this->customer_model->all([
				'order_by' => 'person_id'
			]),
			'employees' => $employees,
			'tax_types' => $this->tenant_tax_type_model->all([
                'select' => 'tax_types.lang, tax_types.id AS type_id, tenant_tax_types.*',
                'joins' => [
                    ['tax_types', 'tenant_tax_types.tax_type_id = tax_types.id', 'RIGHT']
				],
				'where' => [
					'is_active' => true
				],
			]),
			'currencies' => $this->currency_model->dropdown('id', 'unit', [
				'select' => 'currencies.id, CONCAT (currencies.code, " (", currencies.symbol, ")") AS unit' ,
				'where' => ['is_active' => 1]
			]),
			'payment_methods' => $this->payment_method_model->dropdown('id', 'lang', [], TRUE)
		];

		// Set the sistema locale configuration.
		$this->set_locale();
		
        $this->_template("{$this->_controller}/template/touch_monitor", $this->_get_assets('create', $this->data), 'pos_layout/main');
	}

	public function pending_orders()
	{
		$data = [
			'orders' => $this->{$this->_model}->pending_by_user($this->session->userdata('user_id'))
		];

		$this->_template("{$this->_controller}/order_list", $this->_get_assets('create', $this->data), 'pos_layout/main');
	}

	public function print_order($id)
	{
		$this->load->model('ticket_model');

		$print = $this->ticket_model->printOrder($id);

		if ( $print['error'] )
			return $this->_return_json_error($print['message']);

		return $this->_return_json_success(lang('success_message'));
		
	}

	public function hold_order()
	{

		$order = json_decode(file_get_contents('php://input'));
		$id = $this->{$this->_model}->insert_update([
			'id' => $order->id,
			'customer_id' => $order->customer_id,
			'status' => $order->status,
			'subtotal' => $order->subtotal,
			'tax' => $order->tax,
			'total' => $order->total,
			'date' => strftime("%d %B %Y")
		], ['id']);

		if ( $id ) {
			$order->id = $id;
			if ( ! $this->insert_detial($id, $order) )
				return $this->_return_json_error(validation_errors());

			return $this->_return_json_success(lang('success_message'), $order);
		}
		else {
			return $this->_return_json_error(validation_errors());
		}
	}

	public function make_payment() {
		if ( $this->input->method() === 'get' )
			$this->_retunr_json_error(lang('invalid_method'));
		if ( !$this->input->is_ajax_request() )
			$this->_retunr_json_error(lang('ajax_requests_are_allowed'));

		$this->load->model('');

		$result = $this->{$this->_model}->create_invoice(
			$this->input->post('order_id'), 
			$this->input->post('tax_type_id')
		);

		if ( ! $result )
			return $this->_return_json_error(validation_errors());

		//$result = $this->

		return $result;
		
	}


	public function cash_register_not_opened()
	{
		$this->load->view('errors/html/error_cash_drawer', [
			'heading' => lang('cash_register_not_opened'),
			'message' => lang('contact_your_supervisor_or_administrator'),
			'code' => 601
		]);
	}

	private function insert_detial($id, $order)
	{
		$this->load->model('order_detail_model');
		$this->load->model('product_model');
		$this->load->model('item_history_model');

		$orderTemp = $this->{$this->_model}->get($id);

        foreach ($order->products as $item) {
			$product = $this->product_model->find([
				'limit' => 1,
				'where' => [
					'id' => $item->productId,
				]
			])->with([
				'components',
				'stocks'
			]);

			if ( $product->is_stock ) {
				$message = 'Producto despachado en la orden <a href="' . base_url("order/view/{$orderTemp->id}") . '">#' . $orderTemp->number . '</a>';
				$stockKey = array_search($this->session->userdata('warehouse_id'), array_column($product->stocks, 'warehouse_id'));
				$this->item_history_model->decrease($product->stocks[$stockKey]->id, $item->quantity, $message);
			} else if ( $product->is_composed )
			{
				foreach ($product->components as $component) {
					$message = "Producto forma parte del servicio {$item->name} que fue despachado en la orden <a href='" . base_url("order/view/{$orderTemp->id}") . "'>#{$orderTemp->number}</a>";
					$count = (float)$component->quantity * (float)$item->quantity;
					$stockKey = array_search($this->session->userdata('warehouse_id'), array_column($product->stocks, 'warehouse_id'));
					$this->item_history_model->decrease($product->stocks[$stockKey]->id, $item->quantity, $message);
				}
			}

			$result = $this->order_detail_model->insert_update([
				'order_id' => $orderTemp->id,
				'product_id' => $product->id,
				'quantity' => $item->quantity,
				'price' => $item->price,
				'tax' => ($this->session->userdata('is_tax')) ? ((float)$item->price * 0.18) : 0,
				'total' => (float)$item->price * (float)$item->quantity
			], ['order_id', 'product_id']);

			if ( ! $result ) 
            {
                $this->_response_error(validation_errors());
                return FALSE;
			}
		}

        return TRUE;
	}
}
