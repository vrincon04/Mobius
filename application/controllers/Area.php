<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Area class
 *
 * @author Victor Rincon
 */
class Area extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'area';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'area_model';

    /**
     * 
     * @return void
     */
    public function datatable_json()
	{
		echo $this->{$this->_model}->datatable_json();
    }

    public function get_json()
    {
        if ( $this->input->method() === 'post' )
            $this->_retunr_json_error(lang('invalid_method'));
        
        $filters = [];

        foreach ($this->input->get() as $key => $value) {
            $filters[preg_replace('/_/', '.', $key, 1)] = $value;
        }
        
        $result = $this->{$this->_model}->find([
            'where' => $filters
        ]);

        if (! $result )
            $this->_return_json_error(lang('not_found'));
        else
            $this->_return_json_success(lang('success_message'), $result);
    }

    public function create()
    {
        // Load the department model.
        $this->load->model('department_model');
        // Set de data.
        $this->data = [
            'departments' => $this->department_model->dropdown('id', 'name'),
        ];

        parent::create();
    }

    public function edit($id)
    {
        // Load the department model.
        $this->load->model('department_model');
        // Set de data.
        $this->data = [
            'departments' => $this->department_model->dropdown('id', 'name'),
        ];

        parent::edit($id);
    }
}