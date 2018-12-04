<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * MY_Controller class
 *
 * @author Victor Rincon
 */
class MY_Controller extends CI_Controller {
    /**
     * Base controller name.
     * @var null|string
     */
	protected $_controller = NULL;

    /**
     * Base model name.
     * @var null|string
     */
	protected $_model = NULL;

    /**
     * Data that we will send in view.
     * @var array
     */
    protected $data = array();
    
    /**
     * Array that contains the styles and javascript for the listing page.
     * @var array
     */
    protected $_assets_list = [
        'styles' => [
            'public/plugins/bootstrap-select/css/bootstrap-select.min.css',
            'public/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.min.css',
            'public/plugins/jquery-datatable/extensions/responsive/responsive.bootstrap.min.css'
        ],
        'scripts' => [
            'public/plugins/bootstrap-select/js/bootstrap-select.js',
            'public/plugins/jquery-datatable/jquery.dataTables.min.js',
            'public/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.min.js',
            'public/plugins/jquery-datatable/extensions/responsive/dataTables.responsive.min.js',
            'public/plugins/jquery-datatable/extensions/responsive/responsive.bootstrap.min.js',
            'public/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js',
            'public/plugins/jquery-datatable/extensions/export/buttons.flash.min.js',
            'public/plugins/jquery-datatable/extensions/export/jszip.min.js',
            'public/plugins/jquery-datatable/extensions/export/pdfmake.min.js',
            'public/plugins/jquery-datatable/extensions/export/vfs_fonts.js',
            'public/plugins/jquery-datatable/extensions/export/buttons.html5.min.js',
            'public/plugins/jquery-datatable/extensions/export/buttons.print.min.js',
        ]
    ];

    /**
     * Array that contains the styles and javascript for the create page.
     * @var array
     */
    protected $_assets_create = array();
    
    /**
     * Array that contains the styles and javascript for the edit page.
     * @var array
     */
    protected $_assets_edit = array();
    
    /**
     * Array that contains the styles and javascript for the view page.
     * @var array
     */
    protected $_assets_view = array();
    
    /**
     * MY_Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->set_timezone();

        $this->set_language();
        
        if ( ENVIRONMENT === 'development' && !$this->input->is_ajax_request() )
        {
            $this->output->enable_profiler(TRUE);
        }

        // Check authentication
		$no_redirect = array(
			'user/login',
            'user/acceso',
            'user/get_captcha',
            'migration'
        );

        if ( $this->session->userdata('acceso') == 'bloqueado' && $this->uri->uri_string() !== 'user/acceso' && $this->uri->uri_string() !== 'user/logout' )
        {
            redirect('user/acceso');
            exit();
        }

		if ( $this->session->userdata('logged_in') !== TRUE && !in_array(uri_string(), $no_redirect) )
		{
			redirect('user/login');
		}

		if ( ! grant_access($this->router->class, $this->router->method) )
		{
		    redirect();
		}

        if ( $this->_model !== FALSE )
        {
            $this->_guess_model();
            $this->load->model($this->_model);
        }

        if ( $this->_controller === NULL )
        {
            $this->_controller = strtolower(get_class($this));
        }
    }

    /**
     * Method of the index page of the base controller.
     * 
     * @access public
     * @return void
     */
	public function index()
	{
		$this->_template("{$this->_controller}/list", $this->_get_assets('list', $this->data));
    }
    
    /**
     * Method of the create page of the base controller.
     * 
     * @access public
     * @return void
     */
    public function create()
	{
		if ( $this->input->method() === 'post' )
		{
            if ( $id = $this->{$this->_model}->insert($this->input->post()) )
			{
			    $this->_on_insert_success($id);
                $this->_response_success();
                if ( !$this->input->is_ajax_request() )
                {
                    redirect($this->_controller);
                }
			}
            else
            {
                $this->_response_error(validation_errors());
            }
		}

        $this->_template("{$this->_controller}/create", $this->_get_assets('create', $this->data));
    }
    
    /**
     * Method of the edit page of the base controller.
     * 
     * @access public
     * @param int|string $id unique identifier of the record that is going to be modified.
     * @return mix
     */
    public function edit($id)
	{
		$row = $this->_exist($id);

		if ( $this->input->method() === 'post' )
		{
			if ( $this->{$this->_model}->update($id, $this->input->post()) )
			{
                $this->_on_edit_success($id);
                $this->_response_success();

                if ( !$this->input->is_ajax_request() )
                {
                    redirect("{$this->_controller}/view/{$id}");
                }
			}
            else
            {
                $this->_response_error(validation_errors());
            }
		}

		$this->data[$this->_controller] = $row;
		$this->_template("{$this->_controller}/edit", $this->_get_assets('edit', $this->data));
    }
    
    /**
     * Method of the delete page of the base controller.
     * 
     * @access public
     * @param int|string $id unique identifier of the record that is going to be modified.
     * @return mix
     */
	public function delete($id)
    {
        if ( !$this->input->method() === 'post' ) { 
            $this->_response_error(lang('invalid_method')); 
            exit(); 
        }

        $this->_exist($id);

        $res = FALSE;

        try {
            $res = $this->{$this->_model}->delete($id);
        } catch (Exception $error) {
            $this->_response_error($error->getMessage());
        }

        if ( $res )
            $this->_response_success();
        else
            $this->_response_error(lang('could_not_delete_this_item'));
    }

    /**
     * Method of the view page of the base controller.
     * 
     * @access public
     * @param int|string $id unique identifier of the record that is going to be modified.
     * @return mix
     */
    public function view($id)
	{
		$row = $this->_exist($id);

        if ( $this->input->is_ajax_request() )
        {
            $this->_return_json_success(lang('success_message'), array($this->_controller => $row));
        }

		$this->data[$this->_controller] = $row;
        $this->_template("{$this->_controller}/view", $this->_get_assets('view', $this->data));
    }

    /**
     * Set the time zone settings, according to the tenant's parameters.
     * 
     * @access protected
     * @return void
     */
    protected function set_timezone()
    {
        if ( $this->session->has_userdata('timezone') )
        {
            date_default_timezone_set($this->session->userdata('timezone'));
        }
    }

    /**
     * It establishes the configuration of the language, according to the parameters of the tenant.
     * 
     * @access protected
     * @return void
     */
    protected function set_language()
    {
        if ( $this->session->has_userdata('language') ) 
        {
            $this->lang->load('general', $this->session->userdata('language'));
            //$this->config->set_item('language', $this->session->userdata('language'));
        }
    }
    
    /**
     * 
     * @access protected
     * @param null|string $content
     * @param array $data
     * @param string $template
     * @param bool $return
     * @return null|string
     */
    protected function _template($content = NULL, $data = array(), $template = 'layout/main', $return = FALSE)
    {
        $data['content'] = $content;
		return $this->load->view($template, $data, $return);
    }

    /**
     * @access protected
     * @param string $message 
     * @return void
     */
    protected function _return_json_error($message)
    {
        $response = array(
            'error'     => TRUE,
            'message'   => $message
        );

        echo json_encode($response);
        exit();
    }

    /**
     * @access protected
     * @param null|string $message
     * @param null|mixed $data
     * @return void
     */
    protected function _return_json_success($message = NULL, $data = NULL)
    {
        $response = array(
            'error'     => FALSE,
            'message'   => ($message !== NULL) ? $message : lang('success_message')
        );

        if ( $data !== NULL )
            $response['data'] = $data;

        echo @json_encode($response);
        exit();
    }

    /**
     * @access protected
     * @param int|string $id
     * @return null|array|object
     */
    protected function _exist($id)
    {
		if ( ! ($row = $this->{$this->_model}->get($id)) )
		{
            if ( $this->input->is_ajax_request() )
            {
                $this->_return_json_error(lang('not_found'));
            }
            else
            {
                $this->_alert(lang('not_found'), 'warning');
                redirect($this->_controller);
            }
        }
        
        $row = $this->_after_exist($row);

        return $row;
    }

    /**
     * @param string $message
     * @param string $type
     */
    protected function _alert($message, $type = 'info')
    {
        $_SESSION['alerts'][] = array(
            'type'      => $type,
            'message'   => $message
        );
    }

    /**
     * @param int|string $id
     */
    protected function _on_insert_success($id) {}

    /**
     * @param int|string $id
     */
    protected function _on_edit_success($id) {}

    /**
     *
     */
    protected function _get_javascript()
    {
        $js = "public/js/src/{$this->_controller}.js";
        return file_exists("./{$js}") ? $js : NULL;
    }

    protected function _get_language_javascript()
    {
        $js = 'public/js/language/' . $this->session->userdata('language') . '/language.js';
        return file_exists("./{$js}") ? $js : NULL;
    }

    /**
     * @param null|string $assets
     * @param null|array $arr
     * @return array|null
     */
    protected function _get_assets($assets = NULL, $arr = NULL)
    {
        if ( $assets !== NULL && isset($this->{'_assets_'.$assets}) )
        {
            $assets = $this->{'_assets_'.$assets};
        }
        else
        {
            $assets = array();
        }

        if ( is_array($arr) && count($arr) > 0 )
            $assets = array_merge_recursive($assets, $arr);

        $js = $this->_get_javascript();
        if ( $js !== NULL )
            $assets['scripts'][] = $js;
        
        $js = $this->_get_language_javascript();
        if ( $js !== NULL )
            $assets['scripts'][] = $js;




        return $assets;
    }

    /**
     * @param string $message
     */
    protected function _response_error($message)
    {
        if ( $this->input->is_ajax_request() )
        {
            $this->_return_json_error($message);
        }
    }

    /**
     * @param string $message
     * @param null|mixed $data
     */
    protected function _response_success($message = NULL, $data = NULL)
    {
        if ( $this->input->is_ajax_request() )
        {
            $this->_return_json_success($message, $data);
        }
        else
        {
            $message = ($message !== NULL) ? $message : lang('success_message');
            $this->_alert($message, 'success');
        }
    }

    /**
     *
     */
    protected function _guess_model()
    {
        if ( $this->_model === NULL )
        {
            $this->_model = strtolower(get_class($this)).'_model';
        }
    }

    protected function _after_exist($row)
    {
        return $row;
    }
}