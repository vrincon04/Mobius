<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Product class
 *
 * @author Victor Rincon
 */
class Product extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'product';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'product_model';

    /**
     * 
     * @return void
     */
    public function datatable_json()
	{
		echo $this->{$this->_model}->datatable_json();
    }

    public function get_by_name_or_code_json()
    {
        if ( $this->input->method() === 'post' )
            $this->_retunr_json_error(lang('invalid_method'));

        $results = $this->{$this->_model}->find([
            'like' => ['name' => $this->input->get('term')],
            'or_like' => ['code' => $this->input->get('term')]
        ]);
        
        if ( count($results) > 0 )
        {
            foreach ($results as &$result) {
                $result->with(['stocks']);
            }
        }
        
        $this->_return_json_success(lang('success_message'), $results);
    }
    
    public function create()
    {
        // Load the model's
        $this->load->model('category_model');
        $this->load->model('warehouse_model');
        $this->load->model('type_measure_model');
        
        $this->_assets_create = [
			'styles' => [
                'public/plugins/waitme/waitMe.css',
                'public/plugins/bootstrap-select/css/bootstrap-select.css'
			],
			'scripts' => [
                'public/plugins/autosize/autosize.js',
                'public/plugins/jquery-validation/jquery.validate.js',
                'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
                'public/plugins/jquery-sheepIt/jquery.sheepItPlugin.js',
                'public/plugins/jquery-maskMoney/jquery.maskMoney.js',
                'public/plugins/jquery-maskMoney/jquery.region.maskMoney.js'
			]
        ];

        $pre_warehouses = array();
        if ( isset($_POST['warehouses']) )
            $pre_warehouses = $_POST['warehouses'];

        $this->data = [
            'categories' => $this->category_model->dropdown('id', 'name'),
            'warehouses_drop' => $this->warehouse_model->dropdown('id', 'name'),
            'types_measures' => $this->type_measure_model->all(),
            'warehouses' => $pre_warehouses,
		];
        
        parent::create();
    }

    public function edit($id)
    {
        // Load the model's
        $this->load->model('category_model');

        $this->_assets_edit = [
			'styles' => [
                'public/plugins/waitme/waitMe.css',
                'public/plugins/bootstrap-select/css/bootstrap-select.css'
			],
			'scripts' => [
                'public/plugins/autosize/autosize.js',
                'public/plugins/jquery-validation/jquery.validate.js',
                'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
                'public/plugins/jquery-maskMoney/jquery.maskMoney.js',
                'public/plugins/jquery-maskMoney/jquery.region.maskMoney.js'
			]
        ];
        
        $this->data = [
            'categories' => $this->category_model->dropdown('id', 'name')
        ];
        
        parent::edit($id);
    }

    protected function _on_insert_success($id)
    {
        return $this->_insertStock($id);
    }

    protected function _insertStock($id)
    {
        $this->load->model('stock_model');

        foreach ($this->input->post('warehouses') as $warehouse) {
            // Removemos el id del arreglo.
            unset($warehouse['id']);
            // Insertamos el id del producto.
            $warehouse['product_id'] = $id;
            // Insertamos nuestro nueva existencia en la base de datos.
            $result = $this->stock_model->insert($warehouse);

            if (! $result ) 
            {
                $this->_response_error(validation_errors());
                return FALSE;
            }
        }

        return TRUE;
    }
}