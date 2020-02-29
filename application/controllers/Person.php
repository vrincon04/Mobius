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
	
	public function get_by_document_number_json()
	{
		if ( $this->input->method() !== 'get' )
			$this->_retunr_json_error(lang('invalid_method'));
	
		$results = $this->{$this->_model}->find([
			'where' => [
				'document_number' => $this->input->get('number'),
				'document_type_id' => $this->input->get('type')
			]
		]);

		$this->_return_json_success(lang('success_message'), $results);
	}
}