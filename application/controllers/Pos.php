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
        $this->_template("{$this->_controller}/list", $this->_get_assets('create', $this->data), 'pos_layout/main');
	}

	public function cash_register_not_opened()
	{
		$this->load->view('errors/html/error_cash_drawer', [
			'heading' => lang('cash_register_not_opened'),
			'message' => lang('contact_your_supervisor_or_administrator'),
			'code' => 601
		]);
	}
}
