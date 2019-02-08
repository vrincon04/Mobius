<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Purchase_payment class
 *
 * @author Victor Rincon
 */
class Purchase_payment extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'purchase_payment';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'purchase_payment_model';

    /**
     * 
     * @return void
     */
    public function datatable_json()
	{
		echo $this->{$this->_model}->datatable_json();
    }
    
    public function create()
    {
        // Load the model's
		$this->load->model('currency_model');
		$this->load->model('payment_method_model');
        
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

        $this->data = [
			'currencies' => $this->currency_model->dropdown('id', 'unit', [
				'select' => 'currencies.id, CONCAT (currencies.code, " (", currencies.symbol, ")") AS unit' ,
				'where' => ['is_active' => 1]
			]),
			'payment_methods' => $this->payment_method_model->dropdown('id', 'lang', [], TRUE)
		];
        
        parent::create();
    }

    public function edit($id)
	{
		// Load the model's
		$this->load->model('expiration_type_model');
		$this->load->model('currency_model');
		$this->load->model('purchase_detail_model');
        
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
			$pre_products = (array) $this->purchase_detail_model->find([
				'select' 	=> "purchase_details.*, products.name, products.sale, (
					SELECT SUM(stocks.count) FROM stocks WHERE stocks.product_id = products.id
				) AS stock",
				'where' 	=> ['purchase_id' => $id],
				'joins' 	=> [
					['products', 'purchase_details.product_id = products.id', 'INNER']
				]
			]);
		}

		$this->data = [
			'expirations_types' => $this->expiration_type_model->all([
				'select' => 'id, lang, value',
				'order_by' => 'value'
			]),
			'currencies' => $this->currency_model->dropdown('id', 'unit', [
				'select' => 'currencies.id, CONCAT (currencies.code, " (", currencies.symbol, ")") AS unit' ,
				'where' => ['is_active' => 1]
			]),
			'products' => $pre_products,
		];

		parent::edit($id);
	}
	
	protected function _on_insert_success($id)
    {
        return $this->_insert_details($id);
	}

	protected function _insert_details($id)
    {
		$this->load->model('payment_detail_model');
		$this->load->model('purchase_model');

		foreach ($this->input->post('pruchases') as $purchase) {
			$row = $this->purchase_model->get($purchase['id']);

			if ($this->update_purchase($row, $purchase))
			{
				$this->payment_detail_model->insert([
					'payment_id' => $id,
					'document_id' => $row->id,
					'amount' => $purchase['amount']
				]);

				continue;
			}

			return FALSE;
		}

        return TRUE;
	}

	protected function _after_exist($row)
	{
		$row->with(['details', 'currency', 'payment_method', 'user' => ['person'], 'provider' => ['person']]);
		return $row;
	}

	protected function update_purchase($purchase, $data) {
		if ($purchase->balance < $data['amount'] )
			$purchase->status = 'partial';
		if ($purchase->balance == $data['amount'])
			$purchase->status = 'paid';
	
		$purchase->balance = $purchase->balance - (float) preg_replace("/[^0-9.]/", "", $data['amount']);

		$update = [
			'balance' => $purchase->balance,
			'status' => $purchase->status
		];

		if ($this->purchase_model->update($purchase->id, $update))
		{
			return TRUE;
		}

		return FALSE;
	}
}