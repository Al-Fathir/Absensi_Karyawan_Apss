<?php

class Profil extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Profil_model', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>), dan 
	 * $limit untuk membatasi penampilan data di tabel
	 */
	var $limit = 10;
	var $title = 'Profil User Hotel';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman profil,
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->get_all();
		}
		else
		{
			redirect('login');
		}
	}
	
	/**
	 * Tampilkan data profil user
	 */
	function get_all($offset = 0)
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Profil User';
		$data['main_view'] = 'profil/profil';
		
		// Load data
		$profil = $this->Profil_model->get_user_by_username($this->session->userdata('username'));
		
		// Table
		$tmpl = array( 'table_open'    => '<table>',
					  'row_alt_start'  => '<tr>',
						'row_alt_end'    => '</tr>'
					  );
		$this->table->set_template($tmpl);
		
		$this->table->add_row('<strong>Username</strong>',':',$profil->username);
		$this->table->add_row('<strong>Nama Lengkap</strong>',':',$profil->nama_lengkap);
		$this->table->add_row('<strong>Alamat</strong>',':',$profil->alamat);
		$this->table->add_row('<strong>Telepon</strong>',':',$profil->telepon);
		
		$data['table'] = $this->table->generate();
		
		$data['link'] = array('link_edit' => anchor('profil/update/'.$profil->id_user,'Edit Profile', array('class' => 'button'))
								);
		
		// Load view
		$this->load->view('template', $data);
	}
	
	/**
	 * Pindah ke halaman update profil user
	 */
	function update($id_user)
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Profil User > Update';
		$data['main_view'] = 'profil/profil_form';
		$data['form_action']	= site_url('profil/update_process');
		$data['link'] 			= array('link_back' => anchor('profil','Kembali Ke Profil', array('class' => 'back'))
										);
										
		// cari data dari database
		$profil = $this->Profil_model->get_user_by_username($this->session->userdata('username'));
		
		// buat session untuk menyimpan data session
		$this->session->set_userdata('id_user', $profil->id_user);
		$this->session->set_userdata('username', $profil->username);
		
		// Data untuk mengisi field2 form
		$data['default']['id_user'] 				= $profil->id_user;		
		$data['default']['username']				= $profil->username;		
		$data['default']['password']				= $profil->password;
		$data['default']['nama_lengkap'] 			= $profil->nama_lengkap;		
		$data['default']['alamat']					= $profil->alamat;		
		$data['default']['telepon']					= $profil->telepon;
				
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data user
	 */
	function update_process()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Profil User > Update';
		$data['main_view'] = 'profil/profil_form';
		$data['form_action']	= site_url('profil/update_process');
		$data['link'] 			= array('link_back' => anchor('profil','Kembali Ke Profil', array('class' => 'back'))
										);
										
		// Set validation rules
		$this->form_validation->set_rules('username', 'username', 'required|callback_valid_id2');
		$this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');
		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|max_length[32]');
		$this->form_validation->set_rules('alamat', 'Alamat', '');
		$this->form_validation->set_rules('telepon', 'Telepon', '');
		
		if ($this->form_validation->run() == TRUE)
		{
			// save data
			$profil	 = array(
							'username'				=> $this->input->post('username'),
							'password'				=> $this->input->post('password'),
							'nama_lengkap'			=> $this->input->post('nama_lengkap'),
							'alamat'				=> $this->input->post('alamat'),
							'telepon'				=> $this->input->post('telepon')
							
						);
			$this->Profil_model->update($this->session->userdata('id_user'), $profil);
			
			$this->session->set_flashdata('message', 'Profil user anda berhasil diupdate!');
			redirect('profil');
		}
		else
		{		
			$data['default']['id_user'] = $this->input->post('id_user');
			$this->load->view('template', $data);
		}
	}
	
	/**
	 * Cek apakah username user valid, agar tidak ganda. Hanya untuk proses update data user
	 */
	function valid_id2()
	{
		// cek apakah data tanggal pada session sama dengan isi field
		$current_id 	= $this->session->userdata('username');
		$new_id			= $this->input->post('username');
				
		if ($new_id === $current_id)
		{
			return TRUE;
		}
		else
		{
			if($this->Profil_model->valid_id($new_id) === TRUE) // cek database untuk entry yang sama memakai valid_entry()
			{
				$this->form_validation->set_message('valid_id2', "Username <strong>$new_id</strong> sudah terdaftar, silahkan isi dengan username yang lain");
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}
}