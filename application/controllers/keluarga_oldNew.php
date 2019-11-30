<?php

class Keluarga extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Keluarga_model', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>), dan 
	 * $limit untuk membatasi penampilan data di tabel
	 */
	var $limit = 10;
	var $title = 'keluarga';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman keluarga,
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
	
	public function readBook() {
		$nik = $_GET['idnya'];
		echo json_encode( $this->Keluarga_model->getBook($nik) );
	}
	
	/**
	 * Tampilkan semua data keluarga
	 */
	function get_all($offset = 0)
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Keluarga';
		$data['main_view'] = 'keluarga/keluarga';
		
		// Offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// Load data
		$karyawan = $this->Keluarga_model->get_Allkaryawan($this->limit, $offset);
		$num_rows = $this->Keluarga_model->count_all();
		
		if ($num_rows > 0)
		{
			// Generate pagination			
			$config['base_url'] = site_url('keluarga/get_all');
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
			$this->table->set_heading('No', 'NIK', 'Nama karyawan', 'Jenis Kelamin' , 'Telepon', 'Actions');
			$i = 0 + $offset;
			
			foreach ($karyawan as $row)
			{
			$this->table->add_row(++$i, $row->nik, $row->nama_karyawan, $row->jenis_kelamin, $row->telepon, 
										'<a onClick="editKeluarga('.$row->nik.')" href="javascript:void(0)">view detail</a> | '.
										anchor('keluarga/add/'.$row->nik,'add new',array('class' => 'update'))
										);
			}
			$data['table'] = $this->table->generate();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan satupun data karyawan!';
		}		
		
		//$data['link'] = array('link_add' => anchor('keluarga/add/','Add New Data Keluarga', array('class' => 'button')));
		
		// Load view
		$this->load->view('template', $data);
	}
		
	/**
	 * Hapus data keluarga
	 */
	function delete($nik)
	{
		$this->Keluarga_model->delete($nik);
		$this->session->set_flashdata('message', '1 data keluarga berhasil dihapus');
		
		redirect('keluarga');
	}
	
	/**
	 * Pindah ke halaman tambah keluarga
	 */
	function add($nik)
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Keluarga > Tambah Data';
		$data['main_view'] 		= 'keluarga/keluarga_form';
		$data['form_action']	= site_url('keluarga/add_process');
		$data['link'] 			= array('link_back' => anchor('keluarga','kembali', array('class' => 'back'))
								);	                               
								
		// data NIK untuk dropdown menu
		$karyawan = $this->Keluarga_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		// data agama untuk dropdown menu
		$data['options_agama']['Islam'] = 'Islam';
		$data['options_agama']['Budha'] = 'Budha';
		$data['options_agama']['Kristen Katolik'] = 'Kristen Katolik';
		$data['options_agama']['Kristen Protestan'] = 'Kristen Protestan';
		$data['options_agama']['Hindu'] = 'Hindu';
		
		// Data untuk mengisi field2 form
		$data['default']['nik'] = $nik;
		
		$this->load->view('template', $data);	
	}
	
	function add_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Keluarga > Tambah Data';
		$data['main_view'] 		= 'keluarga/keluarga_form';
		$data['form_action']	= site_url('keluarga/add_process');
		$data['link'] 			= array('link_back' => anchor('keluarga','kembali', array('class' => 'back'))
										);
										
		// data NIK untuk dropdown menu
		$karyawan = $this->Keluarga_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		// data agama untuk dropdown menu								
		$data['options_agama']['Islam'] = 'Islam';
		$data['options_agama']['Budha'] = 'Budha';
		$data['options_agama']['Kristen Katolik'] = 'Kristen Katolik';
		$data['options_agama']['Kristen Protestan'] = 'Kristen Protestan';
		$data['options_agama']['Hindu'] = 'Hindu';

		// Set validation rules
		$this->form_validation->set_rules('nama', 'Nama', 'required|max_length[32]');
		
		// Jika validasi sukses
		if ($this->form_validation->run() == TRUE)
		{
			// Persiapan data
			$keluarga = array('nik'					=> $this->input->post('nik'),
							'nama'					=> $this->input->post('nama'),
							'jenis_kelamin'			=> $this->input->post('jenis_kelamin'),
							'agama'					=> $this->input->post('agama'),
							'tempat_lahir'			=> $this->input->post('tempat_lahir'),
							'tgl_lahir'				=> $this->input->post('tgl_lahir'),			
							'status'				=> $this->input->post('status')
						);
			// Proses penyimpanan data di table keluarga
			$this->Keluarga_model->add($keluarga);
			
			$this->session->set_flashdata('message', 'Satu data keluarga berhasil disimpan!');
			redirect('keluarga');
		}
		// Jika validasi gagal
		else
		{		
			$this->load->view('template', $data);
		}		
	}
	
	/**
	 * Pindah ke halaman update keluarga
	 */
	function update($nik)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Keluarga> Update';
		$data['main_view'] 		= 'keluarga/keluarga_form';
		$data['form_action']	= site_url('keluarga/update_process');
		$data['link'] 			= array('link_back' => anchor('keluarga','kembali', array('class' => 'back'))
										);
										
		// data NIK untuk dropdown menu
		$karyawan = $this->Keluarga_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
										
		// data agama untuk dropdown menu
		$data['options_agama']['Islam'] = 'Islam';
		$data['options_agama']['Budha'] = 'Budha';
		$data['options_agama']['Kristen Katolik'] = 'Kristen Katolik';
		$data['options_agama']['Kristen Protestan'] = 'Kristen Protestan';
		$data['options_agama']['Hindu'] = 'Hindu';
												
		// cari data dari database
		$keluarga = $this->Keluarga_model->get_keluarga_by_id($nik);
		
		// buat session untuk menyimpan data primary key (nik)
		$this->session->set_userdata('nik', $keluarga->nik);
		
		// Data untuk mengisi field2 form
		$data['default']['nik'] 					= $keluarga->nik;		
		$data['default']['nama'] 					= $keluarga->nama;
		$data['default']['jenis_kelamin'] 			= $keluarga->jenis_kelamin;
		$data['default']['agama'] 					= $keluarga->agama;	
		$data['default']['tempat_lahir']			= $keluarga->tempat_lahir;
		$data['default']['tgl_lahir'] 				= $keluarga->tgl_lahir;	
		$data['default']['status']					= $keluarga->status;	
		
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data keluarga
	 */
	function update_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Keluarga > Update';
		$data['main_view'] 		= 'keluarga/keluarga_form';
		$data['form_action']	= site_url('keluarga/update_process');
		$data['link'] 			= array('link_back' => anchor('keluarga','kembali', array('class' => 'back'))
										);
										
		// Set validation rules
		$this->form_validation->set_rules('nik', 'NIK', 'required|callback_valid_id2');
		$this->form_validation->set_rules('nama', 'Nama Keluarga', 'required|max_length[32]');
		$this->form_validation->set_rules('jenis_kelamin', '', '');
		$this->form_validation->set_rules('agama', '', '');
		$this->form_validation->set_rules('tempat_lahir', '', '');
		$this->form_validation->set_rules('tgl_lahir', '', '');
		$this->form_validation->set_rules('status', '', '');
		
		if ($this->form_validation->run() == TRUE)
		{
			// save data
			$keluarga= array('nik'					=> $this->input->post('nik'),
							'nama'					=> $this->input->post('nama'),
							'jenis_kelamin'			=> $this->input->post('jenis_kelamin'),
							'agama'					=> $this->input->post('agama'),
							'tempat_lahir'			=> $this->input->post('tempat_lahir'),
							'tgl_lahir'				=> $this->input->post('tgl_lahir'),
							'status'				=> $this->input->post('status')							
						);
			$this->Keluarga_model->update($this->session->userdata('nik'), $keluarga);
			
			$this->session->set_flashdata('message', 'Satu data keluarga berhasil diupdate!');
			redirect('keluarga');
		}
		else
		{		
			$data['default']['nik'] = $this->input->post('nik');
			$this->load->view('template', $data);
		}
	}
}