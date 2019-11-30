<?php

class Gaji extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Gaji_model', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>), dan 
	 * $limit untuk membatasi penampilan data di tabel
	 */
	var $limit = 10;
	var $title = 'Data Gaji | Grand Palace Hotel';
	
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
	 * Tampilkan semua data gaji karyawan
	 */
	function get_all($offset = 0)
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Gaji';
		$data['main_view'] = 'gaji/gaji';
		
		// Offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// mengisi form dropdown
		$opsi_departemen = $this->Gaji_model->get_departemen()->result();
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
			$this->session->set_userdata('sess_fieldpencarianGaji', $data['fieldpencarian']);
			$this->session->set_userdata('sess_pencarianGaji', $data['pencarian']);
			$this->session->set_userdata('sess_departemenGaji', $data['departemen']);
			
		} else {
			$data['fieldpencarian'] = $this->session->userdata('sess_fieldpencarianGaji');
			$data['pencarian'] = $this->session->userdata('sess_pencarianGaji');
			$data['departemen'] = $this->session->userdata('sess_departemenGaji');
		}
		
		$gaji = $this->Gaji_model->get_all($this->limit, $offset, $data['fieldpencarian'], $data['pencarian'], $data['departemen']);
		$num_rows = $this->Gaji_model->count_all($data['fieldpencarian'], $data['pencarian'], $data['departemen']);
		
		if ($num_rows > 0)
		{
			// Generate pagination			
			$config['base_url'] = site_url('gaji/get_all');
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
			$this->table->set_heading('No', 'NIK', 'Nama karyawan', 'Gaji Pokok', 'Total Tunjangan', 'Total Gaji', 'Actions');
			$i = 0 + $offset;
			
			//$this->table->add_row('<strong>Gaji Pokok</strong>',':','Rp. '.number_format($gaji->gajipokok,0, ",", ".").',-');
			
			foreach ($gaji as $row)
			{
				$this->table->add_row(++$i, $row->nik, $row->nama_karyawan, 
				'Rp. '.number_format($row->gajipokok,0, ",", "."), 
				'Rp. '.number_format(($row->tunjangan_tetap + $row->tunjangan_tidak_tetap + $row->tunjangan_lainnya),0, ",", "."), 
				'Rp. '.number_format(($row->gajipokok + $row->tunjangan_tetap + $row->tunjangan_tidak_tetap + $row->tunjangan_lainnya),0, ",", "."),
				anchor('gaji/detail/'.$row->nik,'<img alt="Detail" src="'.base_url().'/asset/images/icons/view.png"> View').' '.
				anchor('gaji/update/'.$row->nik,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit').' '.
				anchor('gaji/delete/'.$row->nik,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
										);
			}
			$data['table'] = $this->table->generate();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan satupun data gaji!';
		}		
		
		$data['link'] = array('link_add' => anchor('gaji/add/','Add New Data Gaji', array('class' => 'button'))
								);
		$data['download'] = array('link_excel' => anchor('gaji/toexcel/','EXCEL', array('class' => 'excel')),
									'link_pdf' => anchor('gaji/topdf/','PDF', array('class' => 'pdf', 'target' => '_blank')),
									'link_word' => anchor('gaji/toword/','WORD', array('class' => 'word'))
								);
		
		// Load view
		$this->load->view('template', $data);
	}
	
	
	function detail($nik)
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Data Gaji > Detail';
		$data['main_view'] 		= 'gaji/gaji_view';
		$data['link'] 			= array('link_back' => anchor('gaji','Kembali Ke List Data Gaji', array('class' => 'back'))
										);		
		
		$data['edit_link'] = anchor('gaji/update/'.$nik,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit');
		$data['delete_link'] = anchor('gaji/delete/'.$nik,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
		
		// Load data
		$gaji = $this->Gaji_model->get_gaji_by_id($nik);
		
		// Table
		/*Set table template for alternating row 'zebra'*/
		$tmpl = array( 'table_open'    => '<table>',
					  'row_alt_start'  => '<tr>',
						'row_alt_end'    => '</tr>'
					  );
		$this->table->set_template($tmpl);
		
		$this->table->add_row('<strong>NIK</strong>',':',$gaji->nik);
		$this->table->add_row('<strong>Nama Karyawan</strong>',':',$gaji->nama_karyawan);
		$this->table->add_row('<strong>Gaji Pokok</strong>',':','Rp. '.number_format($gaji->gajipokok,0, ",", ".").',-');
		$this->table->add_row('<strong>Tunjangan tetap</strong>',':','Rp. '.number_format($gaji->tunjangan_tetap,0, ",", ".").',-');
		$this->table->add_row('<strong>Tunjangan tidak tetap</strong>',':','Rp. '.number_format($gaji->tunjangan_tidak_tetap,0, ",", ".").',-');
		$this->table->add_row('<strong>Tunjangan lainnya</strong>',':','Rp. '.number_format($gaji->tunjangan_lainnya,0, ",", ".").',-');
		$this->table->add_row('<strong>Total Gaji</strong>',':','Rp. '.number_format(($gaji->gajipokok + $gaji->tunjangan_tetap + $gaji->tunjangan_tidak_tetap + $gaji->tunjangan_lainnya),0, ",", ".").',-');
		
		$data['table'] = $this->table->generate();
				
		$this->load->view('template', $data);
	}
		
	/**
	 * Hapus data gaji
	 */
	function delete($nik)
	{
		$this->Gaji_model->delete($nik);
		$this->session->set_flashdata('message', '1 data gaji berhasil dihapus');
		
		redirect('gaji');
	}
	
	/**
	 * Pindah ke halaman tambah gaji karyawan
	 */
	function add()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Data Gaji > Tambah Data';
		$data['main_view'] 		= 'gaji/gaji_form';
		$data['form_action']	= site_url('gaji/add_process');
		$data['link'] 			= array('link_back' => anchor('gaji','Kembali Ke List Data Gaji', array('class' => 'back'))
								);	                               
		
		// data NIK untuk dropdown menu
		$karyawan = $this->Gaji_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
							
		$this->load->view('template', $data);	
	}
	
	function add_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Data Gaji > Tambah Data';
		$data['main_view'] 		= 'gaji/gaji_form';
		$data['form_action']	= site_url('gaji/add_process');
		$data['link'] 			= array('link_back' => anchor('gaji','Kembali Ke List Data Gaji', array('class' => 'back'))
								);	
		
		// data NIK untuk dropdown menu
		$karyawan = $this->Gaji_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		// Set validation rules
		$this->form_validation->set_rules('nik', 'NIK', 'required|numeric|max_length[4]|callback_valid_id');
		$this->form_validation->set_rules('gajipokok', 'Gaji Pokok', 'numeric');
		$this->form_validation->set_rules('tunjangan_tetap', 'Tunjangan Tetap', 'numeric');
		$this->form_validation->set_rules('tunjangan_tidak_tetap', 'Tunjangan Tidak Tetap', 'numeric');
		$this->form_validation->set_rules('tunjangan_lainnya', 'Tunjangan Lainnya', 'numeric');
		
		// Jika validasi sukses
		if ($this->form_validation->run() == TRUE)
		{
			// Persiapan data
			$gaji = array('nik'				=> $this->input->post('nik'),
							'gajipokok'				=> $this->input->post('gajipokok'),
							'tunjangan_tetap'		=> $this->input->post('tunjangan_tetap'),
							'tunjangan_tidak_tetap'	=> $this->input->post('tunjangan_tidak_tetap'),
							'tunjangan_lainnya'		=> $this->input->post('tunjangan_lainnya')							
						);
			// Proses penyimpanan data di table karyawan
			$this->Gaji_model->add($gaji);
			
			$this->session->set_flashdata('message', 'Satu data gaji berhasil disimpan!');
			redirect('gaji');
		}
		// Jika validasi gagal
		else
		{		
			$this->load->view('template', $data);
		}		
	}
	/**
	 * Pindah ke halaman update tunjangan
	 */
	function update($nik)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Data Gaji > Update';
		$data['main_view'] 		= 'gaji/gaji_form';
		$data['form_action']	= site_url('gaji/update_process');
		$data['link'] 			= array('link_back' => anchor('gaji','Kembali Ke List Data Gaji', array('class' => 'back'))
										);
		
		// data NIK untuk dropdown menu
		$karyawan = $this->Gaji_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
														
		// cari data dari database
		$gaji = $this->Gaji_model->get_gaji_by_id($nik);
		
		// buat session untuk menyimpan data primary key (nik)
		$this->session->set_userdata('nik', $gaji->nik);
		
		// Data untuk mengisi field2 form
		$data['default']['nik'] 					= $gaji->nik;	
		$data['default']['gajipokok'] 				= $gaji->gajipokok;	
		$data['default']['tunjangan_tetap'] 		= $gaji->tunjangan_tetap;		
		$data['default']['tunjangan_tidak_tetap']	= $gaji->tunjangan_tidak_tetap;
		$data['default']['tunjangan_lainnya'] 		= $gaji->tunjangan_lainnya;			
		
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data tunjangan
	 */
	function update_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Data Gaji > Update';
		$data['main_view'] 		= 'gaji/gaji_form';
		$data['form_action']	= site_url('gaji/update_process');
		$data['link'] 			= array('link_back' => anchor('gaji','Kembali Ke List Data Gaji', array('class' => 'back'))
										);
		
		// data NIK untuk dropdown menu
		$karyawan = $this->Gaji_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		// Set validation rules
		$this->form_validation->set_rules('nik', 'NIK', 'required|numeric|max_length[4]|callback_valid_id2');
		$this->form_validation->set_rules('gajipokok', 'Gaji Pokok', 'numeric');
		$this->form_validation->set_rules('tunjangan_tetap', 'Tunjangan Tetap', 'numeric');
		$this->form_validation->set_rules('tunjangan_tidak_tetap', 'Tunjangan Tidak Tetap', 'numeric');
		$this->form_validation->set_rules('tunjangan_lainnya', 'Tunjangan Lainnya', 'numeric');
							
		if ($this->form_validation->run() == TRUE)
		{
			// save data
			$gaji = array('nik'					=> $this->input->post('nik'),
							'gajipokok'					=> $this->input->post('gajipokok'),
							'tunjangan_tetap'			=> $this->input->post('tunjangan_tetap'),
							'tunjangan_tidak_tetap'		=> $this->input->post('tunjangan_tidak_tetap'),
							'tunjangan_lainnya'			=> $this->input->post('tunjangan_lainnya')							
		
						);
			$this->Gaji_model->update($this->session->userdata('nik'), $gaji);
			
			$this->session->set_flashdata('message', 'Satu data gaji berhasil diupdate!');
			redirect('gaji');
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
		if ($this->Gaji_model->valid_id($nik) == TRUE)
		{
			$this->form_validation->set_message('valid_id', "Karyawan dengan NIK $nik sudah punya data gaji");
			return FALSE;
		}
		else
		{			
			return TRUE;
		}
	}
	
	/**
	 * Cek apakah $nik valid, agar tidak ganda. Hanya untuk proses update data gaji karyawan
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
			if($this->Gaji_model->valid_id($new_id) === TRUE) // cek database untuk entry yang sama memakai valid_entry()
			{
				$this->form_validation->set_message('valid_id2', "Karyawan dengan NIK $new_id sudah punya data gaji");
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
		$query['data1'] = $this->Gaji_model->alldata();
		//print_r($query['data1']);exit;
		$this->load->view('gaji/excel_view',$query);
	}
	
	//convert to pdf	
	function topdf () {
		$this->load->library('cezpdf');
		$this->load->helper('pdf');
		prep_pdf();
		$data['member']= $this->Gaji_model->alldata();
		$titlecolumn = array( 	
							'nik' 					=> 'NIK',
							'nama_karyawan' 		=> 'NAMA KARYAWAN',
							'gajipokok' 			=> 'GAJI POKOK',
							'tunjangan_tetap' 		=> 'TUNJANGAN TETAP',
							'tunjangan_tidak_tetap' => 'TUNJANGAN TIDAK TETAP',
							'tunjangan_lainnya' 	=> 'TUNJANGAN LAINNYA',
							

	
		);
		$this->cezpdf->ezTable($data['member'], $titlecolumn,'Rekap Data Gaji The Grand Palace Hotel Yogyakarta');
		$this->cezpdf->ezStream();
	}
	
	//convert to word	
	function toword () {
	
		$this->load->library('HTML_TO_DOC');
		$gaji = $this->Gaji_model->get_allGaji();
		
		$strHtml = '<center><p><img src="'.base_url().'/asset/images/gph.png"/><br><br>';
		$strHtml .= '<strong>THE GRAND PALACE HOTEL YOGYAKARTA</strong><br>';
		$strHtml .= '<small>Jl. Mangkuyudan No. 32 Yogyakarta 55143</small><br>';
		$strHtml .= '<small>Phone +62 274 414590 . Fax +62 274 417613</small></p><hr></center><br>';
		
		$strHtml .= '<center><h3>Data Gaji The Grand Palace Hotel Yogyakarta '.date("j M Y").'</h3></center><br>';
		if(!empty($gaji)){
			$strHtml .= '<table width="100%" border="1">';
			$strHtml .= '<tr><td>NO</td><td>NIK</td><td>NAMA KARYAWAN</td><td>GAJI POKOK</td><td>TUNJANGAN TETAP</td><td>TUNJANGAN TIDAK TETAP</td><td>TUNJANGAN LAINNYA</td></tr>';
			$i = 0;
			foreach ($gaji as $row)
			{				
				$strHtml .= '<tr><td>'.++$i.'</td><td>'.$row->nik.'</td><td>'.$row->nama_karyawan.'</td><td>'.$row->gajipokok.'</td><td>'.$row->tunjangan_tetap.'</td><td>'.$row->tunjangan_tidak_tetap.'</td><td>'.$row->tunjangan_lainnya.'</td>';
			}
			$strHtml .= '</table>';
		}else{
			$strHtml .= '<br>( Tidak ada data gaji )<br>';
		}
		
		$htmltodoc = new HTML_TO_DOC();
		$htmltodoc->createDoc($strHtml,'Data-Gaji-GPH-'.date("j-M-Y").'.doc',$download=true);
	}
}