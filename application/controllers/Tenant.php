<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Tenant
 *
 */
class Tenant extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
    protected $_controller = 'tenant';
    
	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'tenant_model';

    public function index()
    {
        // Load the model's
        $this->load->model('country_model');
        $this->load->model('state_model');
        $this->load->model('city_model');
        $this->load->model('currency_model');
        $this->load->model('date_format_model');
        $this->load->model('timezone_model');
        
        $row = $this->_exist($this->session->userdata('tenant_id'));

        $this->_assets_view = [
            'styles' => [
				'public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css',
				'public/plugins/waitme/waitMe.css',
				'public/plugins/bootstrap-select/css/bootstrap-select.css'
            ],
            'scripts' => [
				'public/plugins/momentjs/moment-with-locales.min.js',
				'public/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js',
				'public/plugins/jquery-inputmask/jquery.inputmask.bundle.js',
				'public/plugins/jquery-validation/jquery.validate.js',
				'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
			]
        ];

        $this->data = [
            $this->_controller => $row,
            'countries' => $this->country_model->dropdown('id', 'name'),
            'timezones' => $this->timezone_model->all(),
            'currencies' => $this->currency_model->all(),
            'date_formats' => $this->date_format_model->dropdown('id', 'format'),
            'states' => $this->state_model->dropdown('id', 'name', [
                'where' => ['states.country_id' => $row->city->state->country_id]
            ]),
            'cities' => $this->city_model->dropdown('id', 'name', [
                'where' => ['cities.state_id' => $row->city->state_id]
            ])
        ];

        $this->_template('tenant/view', $this->_get_assets('view', $this->data));
    }

    public function updateInfo()
    {
        $row = $this->_exist($this->session->userdata('tenant_id'));

        if ( $this->input->method() === 'post' ) {

            if ( $this->{$this->_model}->update($this->session->userdata('tenant_id'), $this->input->post()) )
			{
                $this->_on_edit_success($this->session->userdata('tenant_id'));
                $this->_response_success();
			}
            else
            {
                $this->_response_error(validation_errors());
            }

            if ( !$this->input->is_ajax_request() )
            {
                redirect("{$this->_controller}/index");
            }
        }
    }

    protected function _after_exist($row)
    {
        $row->with('city.state');
        return $row;
    }
}