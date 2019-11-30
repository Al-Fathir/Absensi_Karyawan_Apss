<?php

class Handbook extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
	
	var $title = 'Download Manual Book | Grand Palace Hotel';
	
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
		$data['h2_title'] = 'Download Manual Book';
		$data['main_view'] = 'handbook/handbook';
		
		// Load view
		$this->load->view('template', $data);
	}
}