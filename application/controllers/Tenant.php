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
        $this->load->model('tenant_tax_type_model');
        $this->load->model('date_format_model');
        $this->load->model('timezone_model');
        
        $row = $this->_exist($this->session->userdata('tenant_id'));

        $this->_assets_view = [
            'styles' => [
                'public/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css',
				'public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css',
				'public/plugins/waitme/waitMe.css',
				'public/plugins/bootstrap-select/css/bootstrap-select.css'
            ],
            'scripts' => [
                'public/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
				'public/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.' . $this->session->userdata('lang') . '.min.js',
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
            'tax_types' => $this->tenant_tax_type_model->all([
                'select' => 'tax_types.lang, tax_types.id AS type_id, tenant_tax_types.*',
                'joins' => [
                    ['tax_types', 'tenant_tax_types.tax_type_id = tax_types.id', 'RIGHT']
                ],
                'or_where' => [
                    'key' => 'tenant_id IS NULL',
                    'value' => null
                ]
            ]),
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

    public function tax()
    {
        $row = $this->_exist($this->session->userdata('tenant_id'));

        if ( $this->input->method() === 'post' ) {
            $has_error = FALSE;
            $this->load->model('tenant_tax_type_model');

            $this->{$this->_model}->update($this->session->userdata('tenant_id'), [
                'tin' => $this->input->post('tin'),
                'is_tax' => $this->input->post('is_tax')
            ]);

            foreach ($this->input->post('prefix') as $key => $value) {
                if ($value != '') {
                    $data = [
                        'tenant_id' => $this->session->userdata('tenant_id'),
                        'tax_type_id' => $key,
                        'prefix' => $value,
                        'expired_at' => $_POST['expired_at'][$key],
                        'from' => $_POST['from'][$key],
                        'to' => $_POST['to'][$key],
                        'is_active' => isset($_POST['is_active'][$key])
                    ];
    
                    $reference = [
                        'tenant_id',
                        'tax_type_id'
                    ];
    
                    if ( $this->tenant_tax_type_model->insert_update($data, $reference) )
                        $has_error = TRUE;
                    
                }
            }

            if ( $has_error )
                $this->_response_success();
            else
                $this->_response_error(validation_errors());
        }

        if ( !$this->input->is_ajax_request() )
        {
            redirect("{$this->_controller}/index");
        }
    }

    protected function _after_exist($row)
    {
        $row->with(['city' => ['state']]);
        return $row;
    }
}