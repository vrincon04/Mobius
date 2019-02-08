<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Purchase class
 *
 * @author Victor Rincon
 */
class Purchase extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'purchase';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'purchase_model';

    /**
     * 
     * @return void
     */
    public function datatable_json()
	{
		echo $this->{$this->_model}->datatable_json();
	}
	
	public function get_by_provider_json($id)
	{
		if ( $this->input->method() === 'post' )
			$this->_retunr_json_error(lang('invalid_method'));

		$results = $this->{$this->_model}->find([
			'select' => 'id, date, total, expired_at, currency_id',
			'where' => [
				'provider_id' => $id,
			],
			'where_in' => [
				'key' => 'status',
				'values' => ['unpaid', 'partial']
			]
		]);

		$this->_return_json_success(lang('success_message'), $results);
	}
    
    public function create()
    {
        // Load the model's
		$this->load->model('currency_model');
		$this->load->model('expiration_type_model');
        
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
			'expirations_types' => $this->expiration_type_model->all([
				'order_by' => 'value',
				'select' => 'id, value, lang'
			]),
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
		$main_total = 0;
        $this->load->model('purchase_detail_model');

        foreach ($this->input->post('products') as $product) {
            // Removemos el id del arreglo.
            unset($product['id']);
            // Insertamos el id del producto.
			$product['purchase_id'] = $id;
			$product['total'] = (float) preg_replace("/[^0-9.]/", "", $product['quantity']) * (float) preg_replace("/[^0-9.]/", "", $product['cost']);
			// Insertamos nuestro nueva existencia en la base de datos.
			$result = $this->purchase_detail_model->insert($product);
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
		$row->with(['details', 'currency', 'expiration_type', 'user' => ['person'], 'provider' => ['person']]);
		return $row;
	}
}