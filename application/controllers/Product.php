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

        $filter = [
            'like' => ['name' => $this->input->get('term')],
            //'or_like' => ['code' => $this->input->get('term')]
        ];
        // Verificamos si exito la llave is_stock
        if ( isset($_GET['is_stock']) )
        {
            $filter = array_merge_recursive($filter, [
                'where' => [
                    'is_stock' => $_GET['is_stock']
                ]
            ]);
        }

        $results = $this->{$this->_model}->find($filter);
        
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
				'public/plugins/bootstrap-select/css/bootstrap-select.css',
				'public/plugins/select2/css/select2.min.css',
				'public/plugins/select2/css/select2-bootstrap.css'
			],
			'scripts' => [
                'public/plugins/autosize/autosize.js',
                'public/plugins/jquery-validation/jquery.validate.js',
                'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
                'public/plugins/jquery-sheepIt/jquery.sheepItPlugin.js',
                'public/plugins/jquery-maskMoney/jquery.maskMoney.js',
                'public/plugins/jquery-maskMoney/jquery.region.maskMoney.js',
                'public/plugins/select2/js/select2.full.min.js',
				'public/plugins/select2/js/i18n/' . $this->session->userdata('lang') . '.js'
			]
        ];

        $pre_warehouses = array();
        $pre_compounds = array();

        if ( isset($_POST['warehouses']) )
            $pre_warehouses = $_POST['warehouses'];
        
        if ( isset($_POST['compounds']) )
            $pre_compounds = $_POST['compounds'];

        $this->data = [
            'categories' => $this->category_model->dropdown('id', 'name'),
            'warehouses_drop' => $this->warehouse_model->dropdown('id', 'name'),
            'types_measures' => $this->type_measure_model->all(),
            'warehouses' => $pre_warehouses,
            'compounds' => $pre_compounds
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
        if ( isset($_POST['is_stock']) )
            return $this->_insertStock($id);
        if ( isset($_POST['is_composed']) )
            return $this->_insertComponent($id);
        return TRUE;
    }

    protected function _insertStock($id)
    {
        $this->load->model('stock_model');
        $this->load->model('item_history_model');

        foreach ($this->input->post('warehouses') as $warehouse) {
            if (trim($warehouse['warehouse_id']) != '')
            {
                $count = $warehouse['count'];
                $warehouse['count'] = 0;
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

                // Increase the stock.
                $this->item_history_model->increase($result, $count, lang('initial_entry_for_product_creation') . ' <a href="' . base_url("product/view/{$id}") . '">#' . str_pad($id, 6, '0', STR_PAD_LEFT) . '</a>');
            }
            
        }

        return TRUE;
    }

    protected function _insertComponent($id)
    {
        // Load the product component model.
        $this->load->model('product_component_model');
        // Inicializamos la variable del costo total del producto.
        $total_cost = 0;

        foreach ($this->input->post('components') as $component) {
            if (trim($component['product_id']) != '')
            {
                // Removemos el id del arreglo.
                unset($component['id']);
                // Insertamos el id del producto.
                $component['product_id'] = $id;
                $component['cost'] = $component['quantity'] * $component['cost'];
                $total_cost += $component['cost'];
                // Insertamos nuestro nueva existencia en la base de datos.
                $result = $this->product_component_model->insert($component);

                if (! $result ) 
                {
                    $this->_response_error(validation_errors());
                    return FALSE;
                }
            }             
        }

        // Actualizamos el costo del producto.
        $this->{$this->_model}->update($id, ['cost' => $total_cost]);

        return TRUE;
    }
}