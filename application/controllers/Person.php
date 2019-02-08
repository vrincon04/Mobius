<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Person class
 *
 * @author Victor Rincon
 */
class Person extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'person';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'person_model';

    /**
     * 
     * @return void
     */
    public function info_json()
	{
        $get = $this->input->get();
        $filters = [];
        $options = [];
        $result = NULL;

        if ( $this->input->method() === 'post' )
            $this->_return_json_error(lang('invalid_method'));

        array_walk( $get, function($item, $key) use (&$filters) {
            $filters[str_replace('|', '.', $key)] =  $item;
        });

        $options['where'] = $filters;
            
        $result = $this->{$this->_model}->find($options);

        if ( $result )
            $this->_return_json_success(lang('success_message'), $result);
        else 
            $this->_return_json_error(lang('not_found'));
    }
    
    public function create()
    {
        // Load the model's
		$this->load->model('document_type_model');
		$this->load->model('gender_model');
		$this->load->model('country_model');
        
        $this->_assets_create = [
			'styles' => [
				'public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css',
				'public/plugins/waitme/waitMe.css',
				'public/plugins/bootstrap-select/css/bootstrap-select.css'
			],
			'scripts' => [
				'public/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js',
				'public/plugins/jquery-inputmask/jquery.inputmask.bundle.js',
				'public/plugins/jquery-validation/jquery.validate.js',
				'public/plugins/jquery-validation/additional-methods.js',
				'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
			]
		];

        $this->data = [
			'documents_types' => $this->document_type_model->dropdown('id', 'lang', [], TRUE),
			'genders' => $this->gender_model->dropdown('id', 'lang', [], TRUE),
			'countries' => $this->country_model->dropdown('id', 'name')
		];
        
        parent::create();
    }

    public function edit($id)
	{
		// Load the model's
		$this->load->model('document_type_model');
		$this->load->model('gender_model');
        $this->load->model('country_model');
        
		$this->_assets_edit = [
			'styles' => [
				'public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css',
				'public/plugins/waitme/waitMe.css',
				'public/plugins/bootstrap-select/css/bootstrap-select.css'
			],
			'scripts' => [
				'public/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js',
				'public/plugins/jquery-inputmask/jquery.inputmask.bundle.js',
				'public/plugins/jquery-validation/jquery.validate.js',
				'public/plugins/jquery-validation/additional-methods.js',
				'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
			]
		];

		$this->data = [
			'documents_types' => $this->document_type_model->dropdown('id', 'lang', [], TRUE),
			'genders' => $this->gender_model->dropdown('id', 'lang', [], TRUE),
			'countries' => $this->country_model->dropdown('id', 'name')
		];

		parent::edit($id);
	}
}