<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Department class
 *
 * @author Victor Rincon
 */
class Department extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'department';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'department_model';

    /**
     * 
     * @return void
     */
    public function datatable_json()
	{
		echo $this->{$this->_model}->datatable_json();
    }
}