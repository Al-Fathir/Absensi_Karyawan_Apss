<?php
/**
 * Login Class
 *
 */
class Login extends CI_Controller {
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Login_model', '', TRUE);	
	}
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman karyawan,
	 * jika tidak akan meload halaman login
	 */
	function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			redirect('home');
		}
		else
		{
			$this->load->view('login/login_view');
		}
	}
	
	/**
	 * Memproses login
	 */
	function process_login()
	{
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');		
		if ($this->form_validation->run() == TRUE)
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			if ($this->Login_model->check_user($username, $password) == TRUE)
			{	
				//$datalevel = $this->Login_model->check_user($username);
				$data = array('username' => $username, 'login' => TRUE);
				$this->session->set_userdata($data);
				redirect('home');
			}
			else
			{
				$this->session->set_flashdata('message', 'Username dan/atau password Anda salah');
				redirect('login/index');
			}
		}
		else
		{
			$this->load->view('login/login_view');
		}
	}
	
	/**
	 * Memproses logout
	 */
	function process_logout()
	{
		$this->session->sess_destroy();
		redirect('login', 'refresh');
	}
}
// END Login Class

/* End of file login.php */
/* Location: ./system/application/controllers/login.php */