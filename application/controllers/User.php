<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User class
 *
 * @author Victor Rincon
 */
class User extends MY_Controller {
    /**
	 * Name of this controller.
	 * @var string
	 */
	protected $_controller = 'user';

	/**
	 * Name of the model that with use.
	 * @var string
	 */
    protected $_model = 'user_model';
    
    /**
     * Esta funcion muestra la pagina de autenticacion de un usario y valida 
     * que los datos introduccido son lo correcto para entrar al sistema.
     * 
     * @access public
     * @return void
     */
	public function login()
	{
        $this->lang->load('login');

		// If already logged redirect to welcome page
		if ( $this->session->userdata('logged_in') === TRUE )
		{
			redirect('welcome');
			exit();
		}

		if ( $this->input->method() === 'post' ) {
			$this->load->library('form_validation');
			$this->form_validation->reset_validation();
			$this->form_validation->set_rules(array(
				array( // Username
					'field'     => 'username',
					'label'     => 'lang:username',
					'rules'     => 'trim|required|min_length[4]|max_length[25]'
				),
				array( // Password
					'field'     => 'password',
					'label'     => 'lang:password',
					'rules'     => 'trim|required|min_length[4]'
				),
			));
		}

		if ( $this->form_validation->run() === TRUE )
		{
			$user = $this->{$this->_model}->auth($this->input->post());

			if ($user)
			{
				$user->with('person|tenant.timezone');
				$access = $this->access_model->first();

				$data = array(
                    'logged_in'         => TRUE,
                    'user_id'           => $user->id,
                    'username'          => $user->username,
                    'first_name'        => $user->person->first_name,
                    'last_name'         => $user->person->last_name,
					'email'             => $user->email,
					'short_name'        => "{$user->person->first_name} {$user->person->last_name}",
					'full_name' 		=> "{$user->person->first_name} {$user->person->middle_name} {$user->person->last_name} {$user->person->last_name2}",
					'tenant_id' 		=> $user->tenant->id,
					'tenant' 			=> $user->tenant->name,
					'timezone' 			=> $user->tenant->timezone->format,
					'lang' 				=> $user->tenant->timezone->lang,
					'language' 			=> $user->tenant->timezone->language,
					'lc_time_names'		=> $user->tenant->timezone->lc_time_names,
					'hour' 				=> $user->tenant->timezone->hour,
                    'grants'            => $this->user_model->get_grants($user->id),
                    'access'            => ($access == NULL) ? 'normal' : $access->status,
                    'access_message'    => ($access == NULL) ? '' : $access->message
				);

				$this->session->set_userdata($data);
				
				redirect('welcome');
			}
			else
			{
			    $this->_alert(lang('wrong_user_or_password'), 'danger');
			}
		}

		$this->load->view('user/login');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('user/login');
	}

	public function create()
	{
		// Load the model's
		$this->load->model('document_type_model');
		$this->load->model('gender_model');
		$this->load->model('country_model');
		$this->_assets_create = [
			'styles' => [
				'public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css',
				'public/plugins/waitme/waitMe.css',
				'public/plugins/bootstrap-select/css/bootstrap-select.css'
			],
			'scripts' => [
				'public/plugins/momentjs/moment-with-locales.min.js',
				'public/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js',
				'public/plugins/jquery-inputmask/jquery.inputmask.bundle.js',
				'public/plugins/jquery-validation/jquery.validate.js',
				'public/plugins/jquery-validation/additional-methods.js',
				'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
			]
		];

		$this->data = [
			'documents_types' => $this->document_type_model->dropdown('id', 'lang', [], TRUE),
			'genders' => $this->gender_model->dropdown('id', 'lang', [], TRUE),
			'countries' => $this->country_model->dropdown('id', 'name')
		];
		
		parent::create();
	}

	public function edit($id)
	{
		if ( $this->input->method() === 'post' )
		{
            // Remove password if is empty
			if ( isset($_POST['password']) && trim($_POST['password']) === '' )
			{
				unset($_POST['password']);
			}
		}

		// Load the model's
		$this->load->model('document_type_model');
		$this->load->model('gender_model');
		$this->load->model('country_model');
		$this->_assets_edit = [
			'styles' => [
				'public/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css',
				'public/plugins/waitme/waitMe.css',
				'public/plugins/bootstrap-select/css/bootstrap-select.css'
			],
			'scripts' => [
				'public/plugins/momentjs/moment-with-locales.min.js',
				'public/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js',
				'public/plugins/jquery-inputmask/jquery.inputmask.bundle.js',
				'public/plugins/jquery-validation/jquery.validate.js',
				'public/plugins/jquery-validation/additional-methods.js',
				'public/plugins/jquery-validation/localization/messages_' . $this->session->userdata('lang') . '.js',
				'public/js/language/' . $this->session->userdata('language') . '/language.js'
			]
		];

		$this->data = [
			'documents_types' => $this->document_type_model->dropdown('id', 'lang', [], TRUE),
			'genders' => $this->gender_model->dropdown('id', 'lang', [], TRUE),
			'countries' => $this->country_model->dropdown('id', 'name')
		];

		parent::edit($id);
	}

	public function datatable_json()
	{
		echo $this->{$this->_model}->datatable_json();
	}

	protected function _after_exist($row)
	{
		$row->with('person');
		$row->person->with(['city', 'document_type']);
		return $row;
	}
}