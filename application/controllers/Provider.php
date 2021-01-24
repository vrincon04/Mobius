<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Provider class
 *
 * @author Victor Rincon
 */
class Provider extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'provider';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'provider_model';

    /**
     * 
     * @return void
     */
    public function datatable_json()
	{
		echo $this->{$this->_model}->datatable_json();
	}
	
	public function get_by_name_json()
	{
		if ( $this->input->method() === 'post' )
			$this->_retunr_json_error(lang('invalid_method'));
			
		$results = $this->{$this->_model}->find([
			'like' => ['persons.first_name' => $this->input->get('term')],
			'or_like' => [
				'persons.last_name' => $this->input->get('term'),
				'persons.middle_name' => $this->input->get('term'),
				'persons.last_name2' => $this->input->get('term'),
                'businesses.trade_name' => $this->input->get('term'),
                'businesses.business_name' => $this->input->get('term'),
 			],
			'select' => 'providers.id, CONCAT(persons.first_name, " ", persons.last_name, " ", businesses.business_name) AS name',
			'joins' => [
			    ['persons', 'providers.entity_id = persons.id AND providers.entity_type = "person"', 'LEFT'],
                ['businesses', 'providers.entity_id = businesses.id AND providers.entity_type = "business"', 'LEFT']
            ]
		]);
		
		$this->_return_json_success(lang('success_message'), $results);
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
				'public/plugins/waitme/waitMe.js',
				'public/js/src/contact.js',
				'public/js/src/address.js'
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
				'public/plugins/waitme/waitMe.js',
				'public/js/src/contact.js',
				'public/js/src/address.js'
			]
		];

		$this->data = [
			'documents_types' => $this->document_type_model->dropdown('id', 'lang', [], TRUE),
			'genders' => $this->gender_model->dropdown('id', 'lang', [], TRUE),
			'countries' => $this->country_model->dropdown('id', 'name')
		];

		parent::edit($id);
    }
    
    protected function _after_exist($row)
	{
		$row->with([$row->entity_type => ['city', 'document_type']]);
		return $row;
	}
}