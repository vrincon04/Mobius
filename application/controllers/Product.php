<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Product class
 *
 * @author Victor Rincon
 */
class Product extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'product';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'product_model';

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
        $this->load->model('category_model');
        
        $this->_assets_create = [
			'styles' => [
                'public/plugins/waitme/waitMe.css',
                'public/plugins/bootstrap-select/css/bootstrap-select.css'
			],
			'scripts' => [
                'public/plugins/autosize/autosize.js',
                'public/plugins/jquery-validation/jquery.validate.js',
				'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
			]
        ];

        $this->data = [
			'categories' => $this->category_model->dropdown('id', 'name')
		];
        
        parent::create();
    }

    public function edit($id)
    {
        $this->_assets_edit = [
			'styles' => [
				'public/plugins/waitme/waitMe.css',
			],
			'scripts' => [
                'public/plugins/autosize/autosize.js'
			]
        ];
        
        parent::edit($id);
    }
}