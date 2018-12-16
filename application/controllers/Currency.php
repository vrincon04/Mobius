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
}
