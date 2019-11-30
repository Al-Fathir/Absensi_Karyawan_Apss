<?php

class Hubungankerja extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Hubungankerja_model', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>), dan 
	 * $limit untuk membatasi penampilan data di tabel
	 */
	var $limit = 10;
	var $title = 'Data Hubungan Kerja | Grand Palace Hotel';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman hubungankerja,
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
	 * Tampilkan semua data hubungankerja
	 */
	
	// menghitung selisih tanggal
	function datediff($tglAwal,$tglAkhir){
		$tgl1 = strtotime($tglAwal); 
		$tgl2 = strtotime($tglAkhir);
		$diff_secs = abs($tgl2-$tgl1);
		$base_year = min(date("Y", $tgl2), date("Y", $tgl1));
		$diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);
		$tahun = date("Y", $diff) - $base_year;
		$bulan = date("n", $diff) - 1;
		$hari = date("j", $diff) - 1;
		return $tahun." tahun ".$bulan." bulan ".$hari." hari";
	}
	
	// mengubah ke format tanggal indonesia
	function DateToIndo($date){
		$BulanIndo = array("Januari", "Februari", "Maret",
						   "April", "Mei", "Juni",
						   "Juli", "Agustus", "September",
						   "Oktober", "November", "Desember");
	
		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$tgl   = substr($date, 8, 2);
		
		$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;		
		return($result);
	}

	function get_all($offset = 0)
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Hubungan Kerja';
		$data['main_view'] = 'hubungankerja/hubungankerja';
		
		// Offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// mengisi form dropdown
		$opsi_kontrak = $this->Hubungankerja_model->get_status_kontrak()->result();
		$data['options_kontrak'][''] = '- Semua Kontrak -';
		foreach($opsi_kontrak as $row)
		{
			$data['options_kontrak'][$row->status_kontrak] = $row->status_kontrak;
		}
		
		// Load data
		if(isset($_POST['cari']))
		{
			$data['fieldpencarian'] = $this->input->post('field');
			$data['pencarian'] = $this->input->post('kunci');
			$data['status_kontrak'] = $this->input->post('status_kontrak');
			
			//set session user data untuk pencarian, untuk paging pencarian
			$this->session->set_userdata('sess_fieldpencarianHubungankerja', $data['fieldpencarian']);
			$this->session->set_userdata('sess_pencarianHubungankerja', $data['pencarian']);
			$this->session->set_userdata('sess_status_kontrak', $data['status_kontrak']);
			
		} else {
		
			$data['fieldpencarian'] = $this->session->userdata('sess_fieldpencarianHubungankerja');
			$data['pencarian'] = $this->session->userdata('sess_pencarianHubungankerja');
			$data['status_kontrak'] = $this->session->userdata('sess_status_kontrak');
		}
		
		$hubungankerja = $this->Hubungankerja_model->get_all($this->limit, $offset, $data['fieldpencarian'], $data['pencarian'], $data['status_kontrak']);
		$num_rows = $this->Hubungankerja_model->count_all($data['fieldpencarian'], $data['pencarian'], $data['status_kontrak']);
		
		if ($num_rows > 0)
		{
			// Generate pagination			
			$config['base_url'] = site_url('hubungankerja/get_all');
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
			$this->table->set_heading('No', 'NIK', 'Nama karyawan','Lama kontrak' , 'Masa berakhir kontrak kerja','Actions');
			$i = 0 + $offset;
			$today = date("Y-m-d");
			
			foreach ($hubungankerja as $row)
			{
			$lamakontrak = $this->datediff($row->tanggal_masuk, $row->tanggal_keluar);

			if($row->tanggal_keluar<=$today)
			{
				$sisakontrak = '<span style="text-decoration:blink; color:#ff0000">* kontrak habis</span>';
			}else
			{
				$sisakontrak = 'sisa kontrak '.$this->datediff($today, $row->tanggal_keluar);
			}
			
			$this->table->add_row(++$i, $row->nik, $row->nama_karyawan, $lamakontrak, $this->DateToIndo($row->tanggal_keluar).' '.$sisakontrak,
										anchor('hubungankerja/detail/'.$row->id_hubungankerja,'<img alt="Detail" src="'.base_url().'/asset/images/icons/view.png"> View').' '.
										anchor('hubungankerja/update/'.$row->id_hubungankerja,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit').' '.
										anchor('hubungankerja/delete/'.$row->id_hubungankerja,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
										);
			}
			$data['table'] = $this->table->generate();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan satupun data hubungan kerja!';
		}		
		
		$data['link'] = array('link_add' => anchor('hubungankerja/add/','Add New Data Hubungan Kerja', array('class' => 'button'))
								);
								
		$data['download'] = array('link_excel' => anchor('hubungankerja/toexcel/','EXCEL', array('class' => 'excel')),
									'link_pdf' => anchor('hubungankerja/topdf/','PDF', array('class' => 'pdf', 'target' => '_blank')),
									'link_word' => anchor('hubungankerja/toword/','WORD', array('class' => 'word'))
								);
								
		// Load view
		$this->load->view('template', $data);
	}
		
	/**
	 * Detail data hubungankerja
	 */
	 function detail($id_hubungankerja)
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Hubungan Kerja > Detail';
		$data['main_view'] 		= 'hubungankerja/hubungankerja_view';
		$data['link'] 			= array('link_back' => anchor('hubungankerja','Kembali Ke List Data Hubungan kerja', array('class' => 'back'))
										);		
		
		$data['edit_link'] = anchor('hubungankerja/update/'.$id_hubungankerja,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit');
		$data['delete_link'] = anchor('hubungankerja/delete/'.$id_hubungankerja,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
		
		// Load data
		$hubungankerja = $this->Hubungankerja_model->get_hubungankerja_by_id($id_hubungankerja);
		
		$today = date("Y-m-d");
		if($hubungankerja->tanggal_keluar<=$today)
		{
			$sisakontrak = '<span style="text-decoration:blink; color:#ff0000">* kontrak habis</span>';
		}else
		{
			$sisakontrak = $this->datediff($today, $hubungankerja->tanggal_keluar);
		}
		
		// Table
		/*Set table template for alternating row 'zebra'*/
		$tmpl = array( 'table_open'    => '<table>',
					  'row_alt_start'  => '<tr>',
						'row_alt_end'    => '</tr>'
					  );
		$this->table->set_template($tmpl);
		
		$this->table->add_row('<strong>NIK</strong>',':',$hubungankerja->nik);
		$this->table->add_row('<strong>Nama Karyawan</strong>',':',$hubungankerja->nama_karyawan);
		$this->table->add_row('<strong>Tanggal Awal</strong>',':',$this->DateToIndo($hubungankerja->tanggal_masuk));
		$this->table->add_row('<strong>Tanggal Akhir</strong>',':',$this->DateToIndo($hubungankerja->tanggal_keluar));
		$this->table->add_row('<strong>Lama Kontrak</strong>',':',$this->datediff($hubungankerja->tanggal_masuk, $hubungankerja->tanggal_keluar));
		$this->table->add_row('<strong>Sisa Kontrak</strong>',':',$sisakontrak);
		$this->table->add_row('<strong>Status Hubungan Kerja</strong>',':',$hubungankerja->status_kontrak);

		$data['table'] = $this->table->generate();
				
		$this->load->view('template', $data);
	}
	 
	function delete($id_hubungankerja)
	{
		$this->Hubungankerja_model->delete($id_hubungankerja);
		$this->session->set_flashdata('message', '1 data hubungan kerja berhasil dihapus');
		
		redirect('hubungankerja');
	}
	
	/**
	 * Pindah ke halaman tambah hubungankerja
	 */
	 function add()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Hubungan Kerja > Tambah Data';
		$data['main_view'] 		= 'hubungankerja/hubungankerja_form';
		$data['form_action']	= site_url('hubungankerja/add_process');
		$data['link'] 			= array('link_back' => anchor('hubungankerja','Kembali Ke List Data Hubungan kerja', array('class' => 'back'))
								);	                               
								
		// data NIK untuk dropdown menu
		$karyawan = $this->Hubungankerja_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
				
		$this->load->view('template', $data);	
	}
	
	function add_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Karyawan > Tambah Data';
		$data['main_view'] 		= 'hubungankerja/hubungankerja_form';
		$data['form_action']	= site_url('hubungankerja/add_process');
		$data['link'] 			= array('link_back' => anchor('hubungankerja','Kembali Ke List Data Hubungan kerja', array('class' => 'back'))
										);
										
		// data NIK untuk dropdown menu
		$karyawan = $this->Hubungankerja_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		// Set validation rules
		$this->form_validation->set_rules('tanggal_masuk', 'Tanggal Awal', 'required|max_length[32]');
		$this->form_validation->set_rules('tanggal_keluar', 'Tanggal Akhir', 'required|max_length[32]');
		$this->form_validation->set_rules('statuspekerjaan', 'Status Kontrak', 'required|max_length[32]');
		
		// Jika validasi sukses
		if ($this->form_validation->run() == TRUE)
		{
			// Persiapan data
			$hubungankerja = array('nik'				=> $this->input->post('nik'),
							'tanggal_masuk'				=> $this->input->post('tanggal_masuk'),
							'tanggal_keluar'			=> $this->input->post('tanggal_keluar'),
							'status_kontrak'			=> $this->input->post('statuspekerjaan')
						);
			// Proses penyimpanan data di table keluarga
			$this->Hubungankerja_model->add($hubungankerja);
			
			$this->session->set_flashdata('message', 'Satu data hubungan kerja berhasil disimpan!');
			redirect('hubungankerja');
		}
		// Jika validasi gagal
		else
		{		
			$this->load->view('template', $data);
		}		
	}
	
	/**
	 * Pindah ke halaman update hubungankerja
	 */
	function update($id_hubungankerja)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Hubungan Kerja > Update';
		$data['main_view'] 		= 'hubungankerja/hubungankerja_form';
		$data['form_action']	= site_url('hubungankerja/update_process');
		$data['link'] 			= array('link_back' => anchor('hubungankerja','Kembali Ke List Data Hubungan kerja', array('class' => 'back'))
										);
										
		// data NIK untuk dropdown menu
		$karyawan = $this->Hubungankerja_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}   
													
		// cari data dari database
		$hubungankerja = $this->Hubungankerja_model->get_hubungankerja_by_id($id_hubungankerja);
		
		// buat session untuk menyimpan data primary key (id_hubungankerja)
		$this->session->set_userdata('id_hubungankerja', $hubungankerja->id_hubungankerja);
		
		// Data untuk mengisi field2 form
		$data['default']['nik'] 					= $hubungankerja->nik;		
		$data['default']['tanggal_masuk'] 			= $hubungankerja->tanggal_masuk;
		$data['default']['tanggal_keluar'] 			= $hubungankerja->tanggal_keluar;
		$data['default']['statuspekerjaan'] 		= $hubungankerja->status_kontrak;	
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data hubungankerja
	 */
	function update_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Hubungan kerja > Update';
		$data['main_view'] 		= 'hubungankerja/khubungankerja_form';
		$data['form_action']	= site_url('hubungankerja/update_process');
		$data['link'] 			= array('link_back' => anchor('hubungankerja','Kembali Ke List Data Hubungan kerja', array('class' => 'back'))
										);
										
		// Set validation rules
		$this->form_validation->set_rules('tanggal_masuk', 'Tanggal Awal', 'required|max_length[32]');
		$this->form_validation->set_rules('tanggal_keluar', 'Tanggal Akhir', 'required|max_length[32]');
		$this->form_validation->set_rules('statuspekerjaan', 'Status Kontrak', 'required|max_length[32]');
		
		if ($this->form_validation->run() == TRUE)
		{
			// save data
			$hubungankerja= array('nik'					=> $this->input->post('nik'),
							'tanggal_masuk'			=> $this->input->post('tanggal_masuk'),
							'tanggal_keluar'		=> $this->input->post('tanggal_keluar'),
							'status_kontrak'			=> $this->input->post('statuspekerjaan')						
						);
			$this->Hubungankerja_model->update($this->session->userdata('id_hubungankerja'), $hubungankerja);
			
			$this->session->set_flashdata('message', 'Satu data hubungan kerja berhasil diupdate!');
			redirect('hubungankerja');
		}
		else
		{		
			$data['default']['nik'] = $this->input->post('nik');
			$this->load->view('template', $data);
		}
	}
	
	//function to export to excel
	function toexcel() {
		$query['data1'] = $this->Hubungankerja_model->alldata();
		//print_r($query['data1']);exit;
		$this->load->view('hubungankerja/excel_view',$query);
	}
	
	//convert to pdf	
	function topdf () {
		$this->load->library('cezpdf');
		$this->load->helper('pdf');
		prep_pdf();
		$data['member']= $this->Hubungankerja_model->alldata();
		$titlecolumn = array(
							'nik' 				=> 'NIK',
							'nama_karyawan' 	=> 'NAMA KARYAWAN',
							'tanggal_masuk' 	=> 'TANGGAL AWAL',
							'tanggal_keluar' 	=> 'TANGGAL AKHIR',
							'status_kontrak' 	=> 'STATUS KONTRAK'						
		);
		$this->cezpdf->ezTable($data['member'], $titlecolumn,'Rekap Data Kontrak Kerja Karyawan The Grand Palace Hotel Yogyakarta');
		$this->cezpdf->ezStream();
	}
	
	//convert to word	
	function toword () {
	
		$this->load->library('HTML_TO_DOC');
		$hubungankerja = $this->Hubungankerja_model->get_allHubungankerja();
		
		$strHtml = '<center><p><img src="'.base_url().'/asset/images/gph.png"/><br><br>';
		$strHtml .= '<strong>THE GRAND PALACE HOTEL YOGYAKARTA</strong><br>';
		$strHtml .= '<small>Jl. Mangkuyudan No. 32 Yogyakarta 55143</small><br>';
		$strHtml .= '<small>Phone +62 274 414590 . Fax +62 274 417613</small></p><hr></center><br>';
		
		$strHtml .= '<center><h3>Data Hubungan Kerja The Grand Palace Hotel Yogyakarta '.date("j M Y").'</h3></center><br>';
		if(!empty($hubungankerja)){
			$strHtml .= '<table width="100%" border="1">';
			$strHtml .= '<tr><td>NO</td><td>NIK</td><td>NAMA KARYAWAN</td><td>TANGGAL AWAL</td><td>TANGGAL AKHIR</td><td>LAMA KONTRAK</td></tr>';
			$i = 0;
			foreach ($hubungankerja as $row)
			{
				$strHtml .= '<tr><td>'.++$i.'</td><td>'.$row->nik.'</td><td>'.$row->nama_karyawan.'</td><td>'.$row->tanggal_masuk.'</td><td>'.$row->tanggal_keluar.'</td><td>'.$this->datediff($row->tanggal_masuk, $row->tanggal_keluar).'</td><tr>';
			}
			$strHtml .= '</table>';
		}else{
			$strHtml .= '<br>( Tidak ada data hubungan kerja )<br>';
		}
		//$this->table->add_row('<strong>Lama Kontrak</strong>',':',$this->datediff($hubungankerja->tanggal_masuk, $hubungankerja->tanggal_keluar));		
		$htmltodoc = new HTML_TO_DOC();
		$htmltodoc->createDoc($strHtml,'Data-HubunganKerja-GPH-'.date("j-M-Y").'.doc',$download=true);
	}
}