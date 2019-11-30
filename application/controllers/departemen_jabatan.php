<?php

class Departemen_jabatan extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Departemen_jabatan_model', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>), dan 
	 * $limit untuk membatasi penampilan data di tabel
	 */
	var $limit = 10;
	var $title = 'Data Departemen Jabatan | Grand Palace Hotel';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman departemen,
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
	 * Tampilkan semua data departemen
	 */
	function get_all($offset = 0)
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Jabatan';
		$data['main_view'] = 'departemen_jabatan/departemen_jabatan';
		
		// Offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// Load data
		$departemen_jabatan = $this->Departemen_jabatan_model->get_all($this->limit, $offset);
		$num_rows = $this->Departemen_jabatan_model->count_all();
		
		if ($num_rows > 0)
		{
			// Generate pagination			
			$config['base_url'] = site_url('departemen_jabatan/get_all');
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
			$this->table->set_heading('No', 'Nama Departemen', 'Keterangan', 'Nama Jabatan', 'Actions To Do');
			$i = 0 + $offset;
			
			foreach ($departemen_jabatan as $row)
			{
			$this->table->add_row(++$i, $row->nama_departemen, $row->keterangan, $row->jabatan,
										anchor('departemen_jabatan/update/'.$row->id_jabatan,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit').' '.
										anchor('departemen_jabatan/delete/'.$row->id_jabatan,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
										);
			}
			$data['table'] = $this->table->generate();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan satupun data departemen!';
		}		
		
		$data['link'] = array('link_add' => anchor('departemen_jabatan/add/','Add New Data Jabatan', array('class' => 'button'))
								);
		
		// Load view
		$this->load->view('template', $data);
	}
		
	/**
	 * Hapus data departemen
	 */
	function delete($id_jabatan)
	{
		$this->Departemen_jabatan_model->delete($id_jabatan);
		$this->session->set_flashdata('message', '1 data departemen berhasil dihapus');
		
		redirect('departemen_jabatan');
	}
	
	/**
	 * Pindah ke halaman tambah departemen
	 */
	function add()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Jabatan > Tambah Data';
		$data['main_view'] 		= 'departemen_jabatan/departemen_jabatan_form';
		$data['form_action']	= site_url('departemen_jabatan/add_process');
		$data['link'] 			= array('link_back' => anchor('departemen_jabatan','Kembali Ke List Jabatan', array('class' => 'back'))
								);	                               
		
		$departemen_jabatan = $this->Departemen_jabatan_model->get_departemen()->result();
		foreach($departemen_jabatan as $row)
		{
			$data['options_jabatan'][$row->id_departemen] = $row->nama_departemen;
		}
		
		
		$this->load->view('template', $data);	
	}
	
	function add_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Jabatan > Tambah Data';
		$data['main_view'] 		= 'departemen_jabatan/departemen_jabatan_form';
		$data['form_action']	= site_url('departemen_jabatan/add_process');
		$data['link'] 			= array('link_back' => anchor('departemen_jabatan','Kembali Ke List Jabatan', array('class' => 'back'))
										);
										
		$departemen_jabatan = $this->Departemen_jabatan_model->get_departemen()->result();
		foreach($departemen_jabatan as $row)
		{
			$data['options_jabatan'][$row->id_departemen] = $row->nama_departemen;
		}
		
		// Set validation rules
		$this->form_validation->set_rules('jabatan', 'Nama Jabatan', 'required|max_length[32]');
		
		// Jika validasi sukses
		if ($this->form_validation->run() == TRUE)
		{
			// Persiapan data
			$departemen_jabatan = array(
								'id_departemen'	=> $this->input->post('id_departemen'),
								'id_jabatan'	=> $this->input->post('id_jabatan'),
							  	'jabatan'		=> $this->input->post('jabatan')	
						);
			// Proses penyimpanan data di table departemen jabatan
			$this->Departemen_jabatan_model->add($departemen_jabatan);
			
			$this->session->set_flashdata('message', 'Satu data jabatan berhasil disimpan!');
			redirect('departemen_jabatan');
		}
		// Jika validasi gagal
		else
		{		
			$this->load->view('template', $data);
		}		
	}
	
	/**
	 * Pindah ke halaman update departemen
	 */
	function update($id_jabatan)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Jabatan > Update';
		$data['main_view'] 		= 'departemen_jabatan/departemen_jabatan_form';
		$data['form_action']	= site_url('departemen_jabatan/update_process');
		$data['link'] 			= array('link_back' => anchor('departemen_jabatan','Kembali Ke List Jabatan', array('class' => 'back'))
										);
										
		$departemen_jabatan = $this->Departemen_jabatan_model->get_departemen()->result();
		foreach($departemen_jabatan as $row)
		{
			$data['options_jabatan'][$row->id_departemen] = $row->nama_departemen;
		}
												
		// cari data dari database
		$departemen_jabatan = $this->Departemen_jabatan_model->get_departemen_jabatan_by_id($id_jabatan);
		
		// buat session untuk menyimpan data primary key (id_jabatan)
		$this->session->set_userdata('id_jabatan', $departemen_jabatan->id_jabatan);
		
		// Data untuk mengisi field2 form
		$data['default']['id_departemen'] 	= $departemen_jabatan->id_departemen;		
		$data['default']['id_jabatan'] 		= $departemen_jabatan->id_jabatan;		
		$data['default']['jabatan'] 		= $departemen_jabatan->jabatan;		
				
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data departemen
	 */
	function update_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Jabatan > Update';
		$data['main_view'] 		= 'departemen_jabatan/departemen_jabatan_form';
		$data['form_action']	= site_url('departemen_jabatan/update_process');
		$data['link'] 			= array('link_back' => anchor('departemen_jabatan','Kembali Ke List Jabatan', array('class' => 'back'))
										);
										
										
		$departemen_jabatan = $this->Departemen_jabatan_model->get_departemen()->result();
		foreach($departemen_jabatan as $row)
		{
			$data['options_jabatan'][$row->id_departemen] = $row->nama_departemen;
		}
		
		// Set validation rules
		$this->form_validation->set_rules('jabatan', 'Nama jabatan', 'required|max_length[32]');
		
		if ($this->form_validation->run() == TRUE)
		{
			// save data
			$departemen_jabatan = array(
								'id_departemen'	=> $this->input->post('id_departemen'),
								'id_jabatan'	=> $this->input->post('id_jabatan'),
								'jabatan'		=> $this->input->post('jabatan')
							
						);
			$this->Departemen_jabatan_model->update($this->session->userdata('id_jabatan'), $departemen_jabatan);
			
			$this->session->set_flashdata('message', 'Satu data jabatan berhasil diupdate!');
			redirect('departemen_jabatan');
		}
		else
		{		
			$data['default']['id_jabatan'] = $this->input->post('id_jabatan');
			$this->load->view('template', $data);
		}
	}
	
}