<?php

class User extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('User_model', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>), dan 
	 * $limit untuk membatasi penampilan data di tabel
	 */
	var $limit = 10;
	var $title = 'Data User Hotel';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman user,
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
	 * Tampilkan semua data user
	 */
	function get_all($offset = 0)
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data User';
		$data['main_view'] = 'user/user';
		
		// Offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// Load data
		$user = $this->User_model->get_all($this->limit, $offset);
		$num_rows = $this->User_model->count_all();
		
		if ($num_rows > 0)
		{
			// Generate pagination			
			$config['base_url'] = site_url('user/get_all');
			$config['total_rows'] = $num_rows;
			$config['per_page'] = $this->limit;
			$config['num_links'] = 4;
			$config['next_link'] = 'Next &raquo;';
			$config['prev_link'] = '&laquo; Previous';
			$config['next_link'] = 'Next &raquo;';
			$config['cur_tag_open'] = '<a class="number current">';
			$config['cur_tag_close'] = '</a>';
			$config['last_link'] = 'Last &raquo;';
			$config['uri_segment'] = $uri_segment;
			$config['anchor_class'] = 'class="number"';
			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();
			
			// Table
			/*Set table template for alternating row 'zebra'*/
			$tmpl = array( 'table_open'    => '<table border="0" cellpadding="0" cellspacing="0">',
						  'row_alt_start'  => '<tr class="zebra">',
							'row_alt_end'    => '</tr>'
						  );
			$this->table->set_template($tmpl);

			/*Set table heading */
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('No', 'Username', 'Level', 'Nama Lengkap', 'Alamat', 'Telepon', 'Actions To Do');
			$i = 0 + $offset;
		//	$level = '';
		
			foreach ($user as $row)
			{
				//if($row->level=='2'){$level='Human Resources';}
				//if($row->level=='3'){$level='Accounting &amp; Finansial';}
				//if($row->level=='4'){$level='Logistik';}
				//if($row->level=='5'){$level='Sales &amp; Marketing';}
				//if($row->level=='6'){$level='Engineering';}
				$this->table->add_row(++$i, $row->username, $row->nama_lengkap, $row->alamat, $row->telepon,
				
										anchor('user/update/'.$row->id_user,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit').' '.
										anchor('user/delete/'.$row->id_user,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
										);
			}
			$data['table'] = $this->table->generate();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan satupun data user!';
		}		
		
		$data['link'] = array('link_add' => anchor('user/add/','Add New Data User', array('class' => 'button'))
								);
		
		// Load view
		$this->load->view('template', $data);
	}
		
	/**
	 * Hapus data user
	 */
	function delete($id_user)
	{
		$this->User_model->delete($id_user);
		$this->session->set_flashdata('message', '1 data user berhasil dihapus');
		
		redirect('user');
	}
	
	/**
	 * Pindah ke halaman tambah user
	 */
	function add()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'User > Tambah Data';
		$data['main_view'] 		= 'user/user_form';
		$data['form_action']	= site_url('user/add_process');
		$data['link'] 			= array('link_back' => anchor('user','Kembali Ke List Data User', array('class' => 'back'))
										);		
		
		$data['options_level']['2'] = 'Human Resources';
		$data['options_level']['3'] = 'Accounting dan Finansial';
		$data['options_level']['4'] = 'Logistik';
		$data['options_level']['5'] = 'Sales dan Marketing';
		$data['options_level']['6'] = 'Engineering';
		
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses tambah data user
	 */
	function add_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'User > Tambah Data';
		$data['main_view'] 		= 'user/user_form';
		$data['form_action']	= site_url('user/add_process');
		$data['link'] 			= array('link_back' => anchor('user','Kembali Ke List Data User', array('class' => 'back'))
										);
		
		$data['options_level']['2'] = 'Human Resources';
		$data['options_level']['3'] = 'Accounting dan Finansial';
		$data['options_level']['4'] = 'Logistik';
		$data['options_level']['5'] = 'Sales dan Marketing';
		$data['options_level']['6'] = 'Engineering';
		
		// Set validation rules
		$this->form_validation->set_rules('username', 'username', 'required|callback_valid_id');
		$this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');
		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|max_length[32]');
		
		// Jika validasi sukses
		if ($this->form_validation->run() == TRUE)
		{
			// Persiapan data
			$user = array('id_user'					=> $this->input->post('id_user'),
							'username'				=> $this->input->post('username'),
							'password'				=> $this->input->post('password'),
							'nama_lengkap'			=> $this->input->post('nama_lengkap'),
							'alamat'				=> $this->input->post('alamat'),
							'telepon'				=> $this->input->post('telepon')
							
						);
			// Proses penyimpanan data di table user
			$this->User_model->add($user);
			
			$this->session->set_flashdata('message', 'Satu data user berhasil disimpan!');
			redirect('user');
		}
		// Jika validasi gagal
		else
		{		
			$this->load->view('template', $data);
		}		
	}
	
	/**
	 * Pindah ke halaman update user
	 */
	function update($id_user)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'User > Update';
		$data['main_view'] 		= 'user/user_form';
		$data['form_action']	= site_url('user/update_process');
		$data['link'] 			= array('link_back' => anchor('user','Kembali Ke List Data User', array('class' => 'back'))
										);
		
		$data['options_level']['2'] = 'Human Resources';
		$data['options_level']['3'] = 'Accounting dan Finansial';
		$data['options_level']['4'] = 'Logistik';
		$data['options_level']['5'] = 'Sales dan Marketing';
		$data['options_level']['6'] = 'Engineering';
		
		// cari data dari database
		$user = $this->User_model->get_user_by_id($id_user);
		
		// buat session untuk menyimpan data session
		$this->session->set_userdata('id_userUPD', $user->id_user);
		$this->session->set_userdata('username', $user->username);
		
		// Data untuk mengisi field2 form
		$data['default']['id_user'] 				= $user->id_user;		
		$data['default']['username']				= $user->username;		
		$data['default']['password']				= $user->password;
		$data['default']['nama_lengkap'] 			= $user->nama_lengkap;		
		$data['default']['alamat']					= $user->alamat;		
		$data['default']['telepon']					= $user->telepon;
		$data['default']['level']					= $user->level;
				
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data user
	 */
	function update_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'User > Update';
		$data['main_view'] 		= 'user/user_form';
		$data['form_action']	= site_url('user/update_process');
		$data['link'] 			= array('link_back' => anchor('user','Kembali Ke List Data User', array('class' => 'back'))
										);
		
		$data['options_level']['2'] = 'Human Resources';
		$data['options_level']['3'] = 'Accounting dan Finansial';
		$data['options_level']['4'] = 'Logistik';
		$data['options_level']['5'] = 'Sales dan Marketing';
		$data['options_level']['6'] = 'Engineering';
		
		// Set validation rules
		$this->form_validation->set_rules('username', 'username', 'required|callback_valid_id2');
		$this->form_validation->set_rules('password', 'Password', 'required|max_length[32]');
		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|max_length[32]');
		
		if ($this->form_validation->run() == TRUE)
		{
			// save data
			$user	 = array(
							'username'				=> $this->input->post('username'),
							'password'				=> $this->input->post('password'),
							'nama_lengkap'			=> $this->input->post('nama_lengkap'),
							'alamat'				=> $this->input->post('alamat'),
							'telepon'				=> $this->input->post('telepon'),
							'level'					=> $this->input->post('level')
							
						);
			$this->User_model->update($this->session->userdata('id_userUPD'), $user);
			
			$this->session->set_flashdata('message', 'Satu data user berhasil diupdate!');
			redirect('user');
		}
		else
		{		
			$data['default']['id_user'] = $this->input->post('id_user');
			$this->load->view('template', $data);
		}
	}
	
	/**
	 * Cek apakah username valid, agar tidak ganda
	 */
	function valid_id($username)
	{
		if ($this->User_model->valid_id($username) == TRUE)
		{
			$this->form_validation->set_message('valid_id', "Username <strong>$username</strong> sudah terdaftar, silahkan isi dengan username yang lain");
			return FALSE;
		}
		else
		{			
			return TRUE;
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
			if($this->User_model->valid_id($new_id) === TRUE) // cek database untuk entry yang sama memakai valid_entry()
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