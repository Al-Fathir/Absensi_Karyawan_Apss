<?php

class Departemen extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Departemen_model', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>), dan 
	 * $limit untuk membatasi penampilan data di tabel
	 */
	var $limit = 10;
	var $title = 'Data Departemen | Grand Palace Hotel';
	
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
		$data['h2_title'] = 'Data Departemen';
		$data['main_view'] = 'departemen/departemen';
		
		// Offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// Load data
		$departemen = $this->Departemen_model->get_all($this->limit, $offset);
		$num_rows = $this->Departemen_model->count_all();
		
		if ($num_rows > 0)
		{
			// Generate pagination			
			$config['base_url'] = site_url('departemen/get_all');
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
			$this->table->set_heading('No', 'Nama Departemen', 'Keterangan', 'Actions To Do');
			$i = 0 + $offset;
			
			foreach ($departemen as $row)
			{
			$this->table->add_row(++$i, $row->nama_departemen, $row->keterangan,
										anchor('departemen/update/'.$row->id_departemen,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit').' '.
										anchor('departemen/delete/'.$row->id_departemen,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
										);
			}
			$data['table'] = $this->table->generate();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan satupun data departemen!';
		}		
		
		$data['link'] = array('link_add' => anchor('departemen/add/','Add New Data Departemen', array('class' => 'button'))
								);
		
		// Load view
		$this->load->view('template', $data);
	}
		
	/**
	 * Hapus data departemen
	 */
	function delete($id_departemen)
	{
		$this->Departemen_model->delete($id_departemen);
		$this->session->set_flashdata('message', '1 data departemen berhasil dihapus');
		
		redirect('departemen');
	}
	
	/**
	 * Pindah ke halaman tambah departemen
	 */
	function add()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Departemen > Tambah Data';
		$data['main_view'] 		= 'departemen/departemen_form';
		$data['form_action']	= site_url('departemen/add_process');
		$data['link'] 			= array('link_back' => anchor('departemen','Kembali Ke List Data Departemen', array('class' => 'back'))
								);	                               
		
		$this->load->view('template', $data);	
	}
	
	function add_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Departemen > Tambah Data';
		$data['main_view'] 		= 'departemen/departemen_form';
		$data['form_action']	= site_url('departemen/add_process');
		$data['link'] 			= array('link_back' => anchor('departemen','Kembali Ke List Data Departemen', array('class' => 'back'))
										);
										
		
		// Set validation rules
		$this->form_validation->set_rules('nama_departemen', 'Nama Departemen', 'required|max_length[32]');
		$this->form_validation->set_rules('keterangan', '', '');
		
		// Jika validasi sukses
		if ($this->form_validation->run() == TRUE)
		{
			// Persiapan data
			$departemen = array('id_departemen'					=> $this->input->post('id_departemen'),
							  'nama_departemen'					=> $this->input->post('nama_departemen'),
							  'keterangan'					=> $this->input->post('keterangan')	
						);
			// Proses penyimpanan data di table keluarga
			$this->Departemen_model->add($departemen);
			
			$this->session->set_flashdata('message', 'Satu data departemen berhasil disimpan!');
			redirect('departemen');
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
	function update($id_departemen)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Departemen > Update';
		$data['main_view'] 		= 'departemen/departemen_form';
		$data['form_action']	= site_url('departemen/update_process');
		$data['link'] 			= array('link_back' => anchor('departemen','Kembali Ke List Data Departemen', array('class' => 'back'))
										);
												
		// cari data dari database
		$departemen = $this->Departemen_model->get_departemen_by_id($id_departemen);
		
		// buat session untuk menyimpan data primary key (id_departemen)
		$this->session->set_userdata('id_departemen', $departemen->id_departemen);
		
		// Data untuk mengisi field2 form
		$data['default']['id_departemen'] 		= $departemen->id_departemen;		
		$data['default']['nama_departemen'] 	= $departemen->nama_departemen;	
		$data['default']['keterangan'] 			= $departemen->keterangan;
				
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data departemen
	 */
	function update_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Departemen > Update';
		$data['main_view'] 		= 'departemen/departemen_form';
		$data['form_action']	= site_url('departemen/update_process');
		$data['link'] 			= array('link_back' => anchor('departemen','Kembali Ke List Data Departemen', array('class' => 'back'))
										);
										
		// Set validation rules
		$this->form_validation->set_rules('nama_departemen', 'Nama departemen', 'required|max_length[32]');
		$this->form_validation->set_rules('keterangan', '', '');
		
		if ($this->form_validation->run() == TRUE)
		{
			// save data
			$departemen = array('id_departemen'					=> $this->input->post('id_departemen'),
							'nama_departemen'			=> $this->input->post('nama_departemen'),
							  'keterangan'					=> $this->input->post('keterangan')							
						);
			$this->Departemen_model->update($this->session->userdata('id_departemen'), $departemen);
			
			$this->session->set_flashdata('message', 'Satu data departemen berhasil diupdate!');
			redirect('departemen');
		}
		else
		{		
			$data['default']['id_departemen'] = $this->input->post('id_departemen');
			$this->load->view('template', $data);
		}
	}
	
	/**
	 * Cek apakah $id_departemen valid, agar tidak ganda
	 */
	function valid_id($id_departemen)
	{
		if ($this->Departemen_model->valid_id($id_departemen) == TRUE)
		{
			$this->form_validation->set_message('valid_id', "Departemen dengan kode $id_departemen sudah terdaftar");
			return FALSE;
		}
		else
		{			
			return TRUE;
		}
	}
	
	/**
	 * Cek apakah $id_departemen valid, agar tidak ganda. Hanya untuk proses update data departemen
	 */
	function valid_id2()
	{
		// cek apakah data tanggal pada session sama dengan isi field
		$current_id 	= $this->session->userdata('id_departemen');
		$new_id			= $this->input->post('id_departemen');
				
		if ($new_id === $current_id)
		{
			return TRUE;
		}
		else
		{
			if($this->Departemen_model->valid_id($new_id) === TRUE) // cek database untuk entry yang sama memakai valid_entry()
			{
				$this->form_validation->set_message('valid_id2', "Departemen dengan kode $new_id sudah terdaftar");
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}
}