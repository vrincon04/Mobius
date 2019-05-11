<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Cash_drawer class
 *
 * @author Victor Rincon
 */
class Cash_drawer extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'cash_drawer';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'cash_drawer_model';

    /**
     * 
     * @return void
     */
    public function datatable_json()
	{
		echo $this->{$this->_model}->datatable_json();
    }

    public function get_open_json()
    {
        if ( !$this->input->is_ajax_request() )
            show_404();

        if ( $this->input->method() !== 'get' )
            $this->_return_json_error(lang('invalid_method'));

        $cash_drawer = $this->{$this->_model}->get_open();

        if ($cash_drawer)
            $this->_return_json_success(lang('success_message'), $cash_drawer);
        else
            $this->_return_json_error(validation_errors()); 
    }
    
    public function create()
    {
        if ( $this->input->method() === 'post' ) {
            $_POST['opened_at'] = date('Y-m-d H:i:s');
            $_POST['opened_by'] = $this->session->userdata('user_id');
        }
        // Load the model's
        $this->load->model('user_model');
        // Set the data wi
        $this->data = [
            'scripts' => [
                'public/plugins/jquery-maskMoney/jquery.maskMoney.js',
                'public/plugins/jquery-maskMoney/jquery.region.maskMoney.js',
                'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
            ],
            'users' => $this->user_model->dropdown('id', 'username')
        ];

        parent::create();
    }

    public function register_income()
    {
        if ( !$this->input->is_ajax_request() )
            show_404();
            
        if ( $this->input->method() !== 'post' )
            $this->_return_json_error(lang('invalid_method'));

        // load the cash drawer detail model.
        $this->load->model('cash_drawer_detail_model');

        $cash_drawer = $this->{$this->_model}->get_open();

        // Insert the detail
        $insert = $this->cash_drawer_detail_model->input($cash_drawer->id, $this->input->post('amount'), $this->input->post('description'), 1);

        if ($insert)
            $this->_return_json_success(lang('success_message'), $insert);
        else
            $this->_return_json_error(validation_errors());  
    }

    public function register_expense()
    {
        if ( !$this->input->is_ajax_request() )
            show_404();
            
        if ( $this->input->method() === 'get' )
            $this->_return_json_error(lang('invalid_method'));

        // load the cash drawer detail model.
        $this->load->model('cash_drawer_detail_model');

        $cash_drawer = $this->{$this->_model}->get_open();

        // Insert the detail
        $insert = $this->cash_drawer_detail_model->output($cash_drawer->id, $this->input->post('amount'), $this->input->post('description'));

        if ($insert)
            $this->_return_json_success(lang('success_message'), $insert);
        else
            $this->_return_json_error(validation_errors());  
    }

    public function close()
    {
        if ( !$this->input->is_ajax_request() )
            show_404();
            
        if ( $this->input->method() !== 'post' )
            $this->_return_json_error(lang('invalid_method'));

        // Buscamos la caja aperturada para el usuario que esta autenticado.
        $cash_drawer = $this->{$this->_model}->get_open();
        // Cerramos la caja que esta aperturada para el usuario que esta autenticado.
        $result = $this->{$this->_model}->close($cash_drawer->id, $this->input->post());
        
        if ($result) {
            $this->_return_json_success(lang('success_message'), $result);
        } else {
            $this->_return_json_error(validation_errors());
        }

    }

    protected function _on_insert_success($id)
    {
        if ( (float) preg_replace("/[^0-9.]/", "", $this->input->post('initial_balance')) > 0 ) {
            // load the cash drawer detail model.
            $this->load->model('cash_drawer_detail_model');
            // Insert the detail
            $insert = $this->cash_drawer_detail_model->input($id, $this->input->post('initial_balance'), lang('open_cash_drawer'), 1);

            if ( $insert )
                return TRUE;
            else
                return FALSE;
        } else {
            return TRUE;
        }
    }

    protected function _after_exist($row)
	{
        if ($row->status == 'closed')
            $row->with(['details', 'currency', 'user' => ['person'], 'open' => ['person'], 'close' => ['person']]);
        else 
            $row->with(['details', 'currency', 'user' => ['person'], 'open' => ['person']]);
		return $row;
	}
}