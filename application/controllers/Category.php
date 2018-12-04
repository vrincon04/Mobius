<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Category class
 *
 * @author Victor Rincon
 */
class Category extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'category';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'category_model';

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
        $this->_assets_create = [
			'styles' => [
				'public/plugins/waitme/waitMe.css',
			],
			'scripts' => [
                'public/plugins/autosize/autosize.js'
			]
        ];
        
        parent::create();
    }

    public function edit($id)
    {
        $this->_assets_edit = [
			'styles' => [
				'public/plugins/waitme/waitMe.css',
			],
			'scripts' => [
                'public/plugins/autosize/autosize.js'
			]
        ];
        
        parent::edit($id);
    }
}