<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Class Grant
 *
 * @property Grant_model $grant_model
 * @property Role_model $role_model
 * @property Module_model $module_model
 *
 */
class Grant extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
    protected $_controller = 'grant';
    
	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'grant_model';
    
    public function index()
    {
        // Load Role Model.
        $this->load->model('rol_model');
        // Load Module Model.
        $this->load->model('modul_model');
        // Get all Roles of the tenant.
        $roles = $this->rol_model->all([
            'order_by' => 'name ASC'
        ]);
        // Get all Modules of the system.
		$modules = $this->modul_model->all([
            'where' => ['is_active' => true],
            'order_by' => 'name ASC'
        ]);
        // Get all grans of the tenant.
        $grants = $this->grant_model->get_grants_array();
        // Set the grants DataBase result to post.
        $post_grants = $grants;

        if ( $this->input->method() === 'post' )
        {
            $result = $this->grant_model->apply_changes($this->input->post('grants'));

            if ( $result )
            {
                // Set the success message.
                $this->_alert(lang('success_message'), 'success');
                // Get the new configuration of grants as array.
                $grants = $this->grant_model->get_grants_array();
                // Set the grants DataBase result to post.
                $post_grants = $grants;
            }
            else
            {
                // Set the grants post by the user..
                $post_grants = array_replace_recursive($grants, $this->input->post('grants'));
            }
        }

        $actions = ['view', 'create', 'edit', 'delete'];

        $data = [
			'roles'		    => $roles,
			'modules'	    => $modules,
			'actions'	    => $actions,
			'grants'	    => $grants,
            'post_grants'   => $post_grants,
			'scripts'	    => [
                'public/js/language/' . $this->session->userdata('language') . '/language.js',
                'public/plugins/sticky/jquery.sticky.js',
                'public/js/src/grant.js'
            ]
        ];
        
		$this->_template('grant/list', $data);
    }

    /**
     * @param $id
     */
	public function view($id)
    {
        redirect($this->_controller);
    }
    /**
     *
     */
    public function create()
    {
        redirect($this->_controller);
    }
    /**
     * @param $id
     */
    public function edit($id)
    {
        redirect($this->_controller);
    }
}