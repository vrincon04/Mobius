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

    protected function _on_insert_success($id)
    {
        if ( (float) preg_replace("/[^0-9.]/", "", $this->input->post('initial_balance')) > 0 ) {
            // load the cash drawer detail model.
            $this->load->model('cash_drawer_detail_model');
            // Insert the detail
            $insert = $this->cash_drawer_detail_model->input($id, $this->input->post('initial_balance'), 1, lang('open_cash_drawer'));

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
        if ($row->status == 'close')
            $row->with(['details', 'currency', 'user' => ['person'], 'open' => ['person'], 'close' => ['person']]);
        else 
        $row->with(['details', 'currency', 'user' => ['person'], 'open' => ['person']]);
		return $row;
	}
}