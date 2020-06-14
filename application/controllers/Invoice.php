<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Invoice class
 *
 * @author Victor Rincon
 */
class Invoice extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'invoice';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'invoice_model';

    /**
     * List of invoices
     * @return string
     */
    public function datatable_json()
	{
		echo $this->{$this->_model}->datatable_json();
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

}