<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Currency class
 *
 * @author Victor Rincon
 */
class Currency extends MY_Controller {

  	/**
     * Nombre del controlador base para esta clase
  	 * @access protected
  	 * @var string
  	 */
  	protected $_controller = 'currency';

  	/**
  	 * Nombre del modelo base para esta clase.
     * @access protected
  	 * @var string
  	 */
    protected $_model = 'currency_model';
    
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

    public function getRate() {
        $this->load->library('fixer');
        $code = ['EUR', 'USD', 'DOP'];
        $response = $this->fixer->latest($code);

        if ($response['error'] == FALSE) {
            if ($response['body']->success == TRUE) {
                $rates = $response['body']->rates;
                $currencies = $this->{$this->_model}->find([
                    'where_in' => [
                        'key' => 'code',
				        'values' => $code
                    ]
                ]);

                foreach ($currencies as $currency) {
                    $value = $this->{$this->_model}->convertRate($rates->DOP, $rates->{trim($currency->code)});
                    $this->{$this->_model}->update($currency->id, ['value' => $value]);
                }
            }

            echo "Las tasas fueron actualizadas con exito.";
        } else {

        }
    }
}
