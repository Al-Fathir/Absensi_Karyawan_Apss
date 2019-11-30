<?php

class Jabatan extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Jabatan_model', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>), dan 
	 * $limit untuk membatasi penampilan data di tabel
	 */
	var $limit = 10;
	var $title = 'Data Jabatan | Grand Palace Hotel';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman karyawan,
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
	 * Tampilkan semua data karyawan
	 */
	function get_all($offset = 0)
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Jabatan';
		$data['main_view'] = 'jabatan/jabatan';
		
		// Offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// mengisi form dropdown
		$opsi_departemen = $this->Jabatan_model->get_departemen()->result();
		$data['options_departemen'][''] = '- Semua Departemen -';
		foreach($opsi_departemen as $row)
		{
			$data['options_departemen'][$row->nama_departemen] = $row->nama_departemen;
		}
		
		// Load data
		if(isset($_POST['cari']))
		{
			$data['fieldpencarian'] = $this->input->post('field');
			$data['pencarian'] = $this->input->post('kunci');
			$data['departemen'] = $this->input->post('departemen');
			
			//set session user data untuk pencarian, untuk paging pencarian
			$this->session->set_userdata('sess_fieldpencarianJabatan', $data['fieldpencarian']);
			$this->session->set_userdata('sess_pencarianJabatan', $data['pencarian']);
			$this->session->set_userdata('sess_departemenJabatan', $data['departemen']);
			
		} else {
			$data['fieldpencarian'] = $this->session->userdata('sess_fieldpencarianJabatan');
			$data['pencarian'] = $this->session->userdata('sess_pencarianJabatan');
			$data['departemen'] = $this->session->userdata('sess_departemenJabatan');
		}
		
		$jabatan = $this->Jabatan_model->get_all($this->limit, $offset, $data['fieldpencarian'], $data['pencarian'], $data['departemen']);
		$num_rows = $this->Jabatan_model->count_all($data['fieldpencarian'], $data['pencarian'], $data['departemen']);
		
		if ($num_rows > 0)
		{
			// Generate pagination			
			$config['base_url'] = site_url('jabatan/get_all');
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
			$this->table->set_heading('No', 'NIK', 'Nama Karyawan', 'Departemen', 'Jabatan', 'Gol.', 'Actions');
			$i = 0 + $offset;
			
			foreach ($jabatan as $row)
			{
				$this->table->add_row(++$i, $row->nik, $row->nama_karyawan, $row->nama_departemen, $row->jabatan, $row->golongan,
										anchor('jabatan/detail/'.$row->nik,'<img alt="Detail" src="'.base_url().'/asset/images/icons/view.png"> View').' '.
										anchor('jabatan/update/'.$row->nik,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit').' '.
										anchor('jabatan/delete/'.$row->nik,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
										);
			}
			$data['table'] = $this->table->generate();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan satupun data jabatan karyawan!';
		}		
		
		$data['link'] = array('link_add' => anchor('jabatan/add/','Add New Data Jabatan', array('class' => 'button'))
								);
		$data['download'] = array('link_excel' => anchor('jabatan/toexcel/','EXCEL', array('class' => 'excel')),
									'link_pdf' => anchor('jabatan/topdf/','PDF', array('class' => 'pdf', 'target' => '_blank')),
									'link_word' => anchor('jabatan/toword/','WORD', array('class' => 'word'))
								);
		
		// Load view
		$this->load->view('template', $data);
	}
		
		
	function detail($nik)
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Jabatan > Detail';
		$data['main_view'] 		= 'jabatan/jabatan_view';
		$data['link'] 			= array('link_back' => anchor('jabatan','Kembali Ke List Data Jabatan', array('class' => 'back'))
										);

		$data['edit_link'] = anchor('jabatan/update/'.$nik,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit');
		$data['delete_link'] = anchor('jabatan/delete/'.$nik,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
		
		// Load data
		$jabatan = $this->Jabatan_model->get_jabatan_by_id($nik);
		
		// Table
		/*Set table template for alternating row 'zebra'*/
		$tmpl = array( 'table_open'    => '<table>',
					  'row_alt_start'  => '<tr>',
						'row_alt_end'    => '</tr>'
					  );
		$this->table->set_template($tmpl);
		
		$this->table->add_row('<strong>NIK</strong>',':',$jabatan->nik);
		$this->table->add_row('<strong>Nama Karyawan</strong>',':',$jabatan->nama_karyawan);
		$this->table->add_row('<strong>Golongan</strong>',':',$jabatan->golongan);
		$this->table->add_row('<strong>Departemen</strong>',':',$jabatan->nama_departemen);
		$this->table->add_row('<strong>Jabatan</strong>',':',$jabatan->jabatan);
		$this->table->add_row('<strong>Masa kerja</strong>',':',$jabatan->masa_kerja);
		
		$data['table'] = $this->table->generate();
				
		$this->load->view('template', $data);
	}
		
		
	/**
	 * Hapus data karyawan
	 */
	function delete($nik)
	{
		$this->Jabatan_model->delete($nik);
		$this->session->set_flashdata('message', '1 data jabatan berhasil dihapus');
		
		redirect('jabatan');
	}
	
	//function add
	
	function add()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Jabatan > Tambah Data';
		$data['main_view'] 		= 'jabatan/jabatan_form';
		$data['form_action']	= site_url('jabatan/add_process');
		$data['link'] 			= array('link_back' => anchor('jabatan','Kembali Ke List Data Jabatan', array('class' => 'back'))
								);	  
		
		// data NIK untuk dropdown menu
		$karyawan = $this->Jabatan_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}		
		// data Jabatan untuk dropdown menu
		$departemen_jabatan = $this->Jabatan_model->get_jabatan()->result();
		foreach($departemen_jabatan as $row)
		{
			$data['options_jabatan'][$row->id_jabatan] = $row->jabatan;
		}                         
		
		$this->load->view('template', $data);	
	}
	
	function add_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Jabatan > Tambah Data';
		$data['main_view'] 		= 'jabatan/jabatan_form';
		$data['form_action']	= site_url('jabatan/add_process');
		$data['link'] 			= array('link_back' => anchor('jabatan','Kembali Ke List Data Jabatan', array('class' => 'back'))
										);
		
		// data NIK untuk dropdown menu
		$karyawan = $this->Jabatan_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		// data Jabatan untuk dropdown menu
		$departemen_jabatan = $this->Jabatan_model->get_jabatan()->result();
		foreach($departemen_jabatan as $row)
		{
			$data['options_jabatan'][$row->id_jabatan] = $row->jabatan;
		}                          
												
		// Set validation rules
		$this->form_validation->set_rules('nik', 'NIK', 'required|numeric|max_length[4]|callback_valid_id');
		$this->form_validation->set_rules('id_jabatan', '', '');
		$this->form_validation->set_rules('golongan', '', '');
		$this->form_validation->set_rules('masa_kerja', '', '');
		
		// Jika validasi sukses
		if ($this->form_validation->run() == TRUE)
		{
			// Persiapan data
			$jabatan = array('nik'					=> $this->input->post('nik'),
							'id_jabatan'			=> $this->input->post('id_jabatan'),
							'golongan'				=> $this->input->post('golongan'),
							'masa_kerja'			=> $this->input->post('masa_kerja')							
						);
			// Proses penyimpanan data di table karyawan
			$this->Jabatan_model->add($jabatan);
			
			$this->session->set_flashdata('message', 'Satu data jabatan berhasil disimpan!');
			redirect('jabatan');
		}
		// Jika validasi gagal
		else
		{		
			$this->load->view('template', $data);
		}		
	}
	
	
	function update($nik)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Jabatan > Update';
		$data['main_view'] 		= 'jabatan/jabatan_form';
		$data['form_action']	= site_url('jabatan/update_process');
		$data['link'] 			= array('link_back' => anchor('jabatan','Kembali Ke List Data Jabatan', array('class' => 'back'))
										);
										
		// data NIK untuk dropdown menu
		$karyawan = $this->Jabatan_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}      
		
		// data Jabatan untuk dropdown menu
		$departemen_jabatan = $this->Jabatan_model->get_jabatan()->result();
		foreach($departemen_jabatan as $row)
		{
			$data['options_jabatan'][$row->id_jabatan] = $row->jabatan;
		}                        
														
		// cari data dari database
		$jabatan = $this->Jabatan_model->get_jabatan_by_id($nik);
		
		// buat session untuk menyimpan data primary key (nik)
		$this->session->set_userdata('nik', $jabatan->nik);
		
		// Data untuk mengisi field2 form
		$data['default']['nik'] 					= $jabatan->nik;
		$data['default']['id_jabatan'] 				= $jabatan->id_jabatan;
		$data['default']['golongan'] 				= $jabatan->golongan;	
		$data['default']['masa_kerja']				= $jabatan->masa_kerja;
		
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data keluarga
	 */
	function update_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Jabatan > Update';
		$data['main_view'] 		= 'jabatan/jabatan_form';
		$data['form_action']	= site_url('jabatan/update_process');
		$data['link'] 			= array('link_back' => anchor('jabatan','Kembali Ke List Data Jabatan', array('class' => 'back'))
										);
		
		// data NIK untuk dropdown menu
		$karyawan = $this->Jabatan_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}     
		
		// data Jabatan untuk dropdown menu
		$departemen_jabatan = $this->Jabatan_model->get_jabatan()->result();
		foreach($departemen_jabatan as $row)
		{
			$data['options_jabatan'][$row->id_jabatan] = $row->jabatan;
		}   
		        
		// Set validation rules
		$this->form_validation->set_rules('nik', 'NIK', 'required|callback_valid_id2');
		$this->form_validation->set_rules('id_jabatan', '', '');
		$this->form_validation->set_rules('golongan', '', '');
		$this->form_validation->set_rules('masa_kerja', '', '');

		if ($this->form_validation->run() == TRUE)
		{
			// save data
			$jabatan= array('nik'					=> $this->input->post('nik'),
							'id_jabatan'			=> $this->input->post('id_jabatan'),
							'golongan'				=> $this->input->post('golongan'),
							'masa_kerja'			=> $this->input->post('masa_kerja'),
						);
			$this->Jabatan_model->update($this->session->userdata('nik'), $jabatan);
			
			$this->session->set_flashdata('message', 'Satu data jabatan berhasil diupdate!');
			redirect('jabatan');
		}
		else
		{		
			$data['default']['nik'] = $this->input->post('nik');
			$this->load->view('template', $data);
		}
	}	
	
	/**
	 * Cek apakah $nik valid, agar tidak ganda
	 */
	function valid_id($nik)
	{
		if ($this->Jabatan_model->valid_id($nik) == TRUE)
		{
			$this->form_validation->set_message('valid_id', "Karyawan dengan NIK $nik sudah punya jabatan");
			return FALSE;
		}
		else
		{			
			return TRUE;
		}
	}
	
	/**
	 * Cek apakah $nik valid, agar tidak ganda. Hanya untuk proses update data karyawan
	 */
	function valid_id2()
	{
		// cek apakah data tanggal pada session sama dengan isi field
		$current_id 	= $this->session->userdata('nik');
		$new_id			= $this->input->post('nik');
				
		if ($new_id === $current_id)
		{
			return TRUE;
		}
		else
		{
			if($this->Jabatan_model->valid_id($new_id) === TRUE) // cek database untuk entry yang sama memakai valid_entry()
			{
				$this->form_validation->set_message('valid_id2', "Karyawan dengan NIK $new_id sudah punya jabatan");
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
	}
	
	//function to export to excel
	function toexcel() {
		$query['data1'] = $this->Jabatan_model->alldata();
		//print_r($query['data1']);exit;
		$this->load->view('jabatan/excel_view',$query);
	}
	
	//convert to pdf	
	function topdf () {
		$this->load->library('cezpdf');
		$this->load->helper('pdf');
		prep_pdf();
		$data['member']= $this->Jabatan_model->alldata();
		$titlecolumn = array(	
							'nik' 				=> 'NIK',
							'nama_karyawan' 	=> 'NAMA KARYAWAN',
							'golongan' 			=> 'GOLONGAN',
							'nama_departemen' 	=> 'DEPARTEMEN',
							'jabatan' 			=> 'JABATAN',
							'masa_kerja' 		=> 'MASA KERJA',	
		);
		$this->cezpdf->ezTable($data['member'], $titlecolumn,'Rekap Data Jabatan The Grand Palace Hotel Yogyakarta');
		$this->cezpdf->ezStream();
	}
	
	//convert to word	
	function toword () {
	
		$this->load->library('HTML_TO_DOC');
		$jabatan = $this->Jabatan_model->get_allJabatan();
		
		$strHtml = '<center><p><img src="'.base_url().'/asset/images/gph.png"/><br><br>';
		$strHtml .= '<strong>THE GRAND PALACE HOTEL YOGYAKARTA</strong><br>';
		$strHtml .= '<small>Jl. Mangkuyudan No. 32 Yogyakarta 55143</small><br>';
		$strHtml .= '<small>Phone +62 274 414590 . Fax +62 274 417613</small></p><hr></center><br>';
		
		$strHtml .= '<center><h3>Data Jabatan The Grand Palace Hotel Yogyakarta '.date("j M Y").'</h3></center><br>';
		if(!empty($jabatan)){
			$strHtml .= '<table width="100%" border="1">';
			$strHtml .= '<tr><td>NO</td><td>NIK</td><td>NAMA KARYAWAN</td><td>GOLONGAN</td><td>DEPARTEMEN</td><td>JABATAN</td><td>MASA KERJA</td></tr>';
			$i = 0;
			foreach ($jabatan as $row)
			{ 
				$strHtml .= '<tr><td>'.++$i.'</td><td>'.$row->nik.'</td><td>'.$row->nama_karyawan.'</td><td>'.$row->golongan.'</td><td>'.$row->nama_departemen.'</td><td>'.$row->jabatan.'</td><td>'.$row->masa_kerja.'</td></tr>';
			}
			$strHtml .= '</table>';
		}else{
			$strHtml .= '<br>( Tidak ada data jabatan )<br>';
		}
		
		$htmltodoc = new HTML_TO_DOC();
		$htmltodoc->createDoc($strHtml,'Data-Jabatan-GPH-'.date("j-M-Y").'.doc',$download=true);
	}
}