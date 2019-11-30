<?php

class Home extends CI_Controller {
	/**
	 * Constructor
	 */
	function __Home()
	{
		parent::__construct();
	}
	
	var $title = 'Dashboard|Airlangga';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman profil,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->main();
		}
		else
		{
			redirect('login');
		}
	}
	
	/**
	 * Tampilkan dashboard
	 */
	function main()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Dashboard';
		$data['main_view'] = 'home/home';
		
		// Load view
		$this->load->view('template', $data);
	}
}