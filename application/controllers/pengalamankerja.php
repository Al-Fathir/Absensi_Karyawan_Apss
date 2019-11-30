<?php

class Pengalamankerja extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Pengalamankerja_model', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>), dan 
	 * $limit untuk membatasi penampilan data di tabel
	 */
	var $limit = 10;
	var $title = 'Data Pengalaman Kerja Hotel';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman pengalamankerja,
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
	 * Tampilkan semua data pengalamankerja
	 */
	function get_all($offset = 0)
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Pengalaman Kerja';
		$data['main_view'] = 'pengalamankerja/pengalamankerja';
		
		// Offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// Load data
		if(isset($_POST['cari']))
		{
			$data['fieldpencarian'] = $this->input->post('field');
			$data['pencarian'] = $this->input->post('kunci');
			//set session user data untuk pencarian, untuk paging pencarian
			$this->session->set_userdata('sess_fieldpencarianPengalamanKerja', $data['fieldpencarian']);
			$this->session->set_userdata('sess_pencarianPengalamanKerja', $data['pencarian']);
		} else {
			$data['fieldpencarian'] = $this->session->userdata('sess_fieldpencarianPengalamanKerja');
			$data['pencarian'] = $this->session->userdata('sess_pencarianPengalamanKerja');
		}
		
		$pengalamankerja = $this->Pengalamankerja_model->get_all($this->limit, $offset, $data['fieldpencarian'], $data['pencarian']);
		$num_rows = $this->Pengalamankerja_model->count_all($data['fieldpencarian'], $data['pencarian']);
		
		if ($num_rows > 0)
		{
			// Generate pagination			
			$config['base_url'] = site_url('pengalamankerja/get_all');
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
			$this->table->set_heading('No', 'NIK', 'Nama karyawan', 'Nama Perusahaan', 'Jabatan', 'Tahun Bekerja', 'Actions');
			$i = 0 + $offset;
			
			foreach ($pengalamankerja as $row)
			{
			$this->table->add_row(++$i, $row->nik, $row->nama_karyawan, $row->nama_perusahaan, $row->jabatan, $row->tahun_mulai_kerja.' - '.$row->tahun_selesai_kerja,
										anchor('pengalamankerja/detail/'.$row->id_pengalamankerja,'<img alt="Detail" src="'.base_url().'/asset/images/icons/view.png"> View').' '.
										anchor('pengalamankerja/update/'.$row->id_pengalamankerja,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit').' '.
										anchor('pengalamankerja/delete/'.$row->id_pengalamankerja,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
										);
			}
			$data['table'] = $this->table->generate();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan satupun data pengalaman kerja';
		}		
		
		$data['link'] = array('link_add' => anchor('pengalamankerja/add/','Add New Data Pengalaman Kerja', array('class' => 'button'))
								);
		$data['download'] = array('link_excel' => anchor('pengalamankerja/toexcel/','EXCEL', array('class' => 'excel')),
									'link_pdf' => anchor('pengalamankerja/topdf/','PDF', array('class' => 'pdf', 'target' => '_blank')),
									'link_word' => anchor('pengalamankerja/toword/','WORD', array('class' => 'word'))
								);
		
		// Load view
		$this->load->view('template', $data);
	}
	
	function detail($id_pengalamankerja)
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Pengalaman Kerja > Detail';
		$data['main_view'] 		= 'pengalamankerja/pengalamankerja_view';
		$data['link'] 			= array('link_back' => anchor('pengalamankerja','Kembali Ke List Data Pengalaman Kerja', array('class' => 'back'))
										);		
		
		$data['edit_link'] = anchor('pengalamankerja/update/'.$id_pengalamankerja,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit');
		$data['delete_link'] = anchor('pengalamankerja/delete/'.$id_pengalamankerja,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
		
		// Load data
		$pengalamankerja = $this->Pengalamankerja_model->get_pengalamankerja_by_id($id_pengalamankerja);
		
		// Table
		/*Set table template for alternating row 'zebra'*/
		$tmpl = array( 'table_open'    => '<table>',
					  'row_alt_start'  => '<tr>',
						'row_alt_end'    => '</tr>'
					  );
		$this->table->set_template($tmpl);
		
		$this->table->add_row('<strong>NIK</strong>',':',$pengalamankerja->nik);
		$this->table->add_row('<strong>Nama Karyawan</strong>',':',$pengalamankerja->nama_karyawan);
		$this->table->add_row('<strong>Nama Perusahaan</strong>',':',$pengalamankerja->nama_perusahaan);
		$this->table->add_row('<strong>Alamat Perusahaan</strong>',':',$pengalamankerja->alamat_perusahaan);
		$this->table->add_row('<strong>Tahun Bekerja</strong>',':',($pengalamankerja->tahun_mulai_kerja.' - '.$pengalamankerja->tahun_selesai_kerja));
		$this->table->add_row('<strong>Jabatan</strong>',':',$pengalamankerja->jabatan);
		$this->table->add_row('<strong>Alasan Berhenti</strong>',':',$pengalamankerja->alasan_berhenti);
		
		$data['table'] = $this->table->generate();
				
		$this->load->view('template', $data);
	}
	
		
	/**
	 * Hapus data pengalaman kerja
	 */
	function delete($id_pengalamankerja)
	{
		$this->Pengalamankerja_model->delete($id_pengalamankerja);
		$this->session->set_flashdata('message', '1 data pengalaman kerja berhasil dihapus');
		
		redirect('pengalamankerja');
	}
	
	/**
	 * Pindah ke halaman tambah pengalaman mkerja
	 */
	function add()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Pengalaman Kerja > Tambah Data';
		$data['main_view'] 		= 'pengalamankerja/Pengalamankerja_form';
		$data['form_action']	= site_url('pengalamankerja/add_process');
		$data['link'] 			= array('link_back' => anchor('pengalamankerja','Kembali Ke List Data Pengalaman Kerja', array('class' => 'back'))
								);	                               
								
		// data NIK untuk dropdown menu
		$karyawan = $this->Pengalamankerja_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		$tahunini = date("Y");		
		for($i=1920;$i<=$tahunini;$i++)
		{
			$data['options_thn'][$i] = $i;
		}
		
		// data agama untuk dropdown menu		
		$this->load->view('template', $data);	
	}
	
	function add_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Pengalaman Kerja > Tambah Data';
		$data['main_view'] 		= 'pengalamankerja/pengalamankerja_form';
		$data['form_action']	= site_url('pengalamankerja/add_process');
		$data['link'] 			= array('link_back' => anchor('pengalamankerja','Kembali Ke List Data Pengalaman Kerja', array('class' => 'back'))
										);
										
		// data NIK untuk dropdown menu
		$karyawan = $this->Pengalamankerja_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		$tahunini = date("Y");		
		for($i=1920;$i<=$tahunini;$i++)
		{
			$data['options_thn'][$i] = $i;
		}

		// Set validation rules
		$this->form_validation->set_rules('nama_perusahaan', 'Nama Perusahaan', 'required|max_length[50]');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'required|max_length[50]');
		$this->form_validation->set_rules('alamat_perusahaan', '', '');
		$this->form_validation->set_rules('tahun_mulai_kerja', '', '');
		$this->form_validation->set_rules('tahun_selesai_kerja', '', '');
		$this->form_validation->set_rules('alasan_berhenti', '', '');
		
		
		// Jika validasi sukses
		if ($this->form_validation->run() == TRUE)
		{
			// Persiapan data
			//nama_perusahaan	alamat_perusahaan	tahun_mulai_kerja	tahun_selesai_kerja	jabatan	alasan_berhenti
			$pengalamankerja = array('nik'						=> $this->input->post('nik'),
									'nama_perusahaan'			=> $this->input->post('nama_perusahaan'),
									'alamat_perusahaan'			=> $this->input->post('alamat_perusahaan'),
									'tahun_mulai_kerja'			=> $this->input->post('tahun_mulai_kerja'),
									'tahun_selesai_kerja'		=> $this->input->post('tahun_selesai_kerja'),
									'jabatan'					=> $this->input->post('jabatan'),
									'alasan_berhenti'			=> $this->input->post('alasan_berhenti')
						);
			// Proses penyimpanan data di table keluarga
			$this->Pengalamankerja_model->add($pengalamankerja);
			
			$this->session->set_flashdata('message', 'Satu data pengalaman kerja berhasil disimpan!');
			redirect('pengalamankerja');
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
	function update($id_pengalamankerja)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Pengalaman Kerja> Update';
		$data['main_view'] 		= 'pengalamankerja/pengalamankerja_form';
		$data['form_action']	= site_url('pengalamankerja/update_process');
		$data['link'] 			= array('link_back' => anchor('pengalamankerja','Kembali Ke List Data Pengalaman Kerja', array('class' => 'back'))
										);
										
		// data NIK untuk dropdown menu
		$karyawan = $this->Pengalamankerja_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		$tahunini = date("Y");		
		for($i=1920;$i<=$tahunini;$i++)
		{
			$data['options_thn'][$i] = $i;
		}
		
		// cari data dari database
		$pengalamankerja = $this->Pengalamankerja_model->get_pengalamankerja_by_id($id_pengalamankerja);
		
		// buat session untuk menyimpan data primary key (id_pengalamankerja)
		$this->session->set_userdata('id_pengalamankerja', $pengalamankerja->id_pengalamankerja);
		
		// Data untuk mengisi field2 form
		
		$data['default']['nik'] 					= $pengalamankerja->nik;
		$data['default']['nama_perusahaan'] 		= $pengalamankerja->nama_perusahaan;
		$data['default']['alamat_perusahaan'] 		= $pengalamankerja->alamat_perusahaan;
		$data['default']['tahun_mulai_kerja'] 		= $pengalamankerja->tahun_mulai_kerja;
		$data['default']['tahun_selesai_kerja'] 	= $pengalamankerja->tahun_selesai_kerja;
		$data['default']['jabatan'] 				= $pengalamankerja->jabatan;
		$data['default']['alasan_berhenti'] 		= $pengalamankerja->alasan_berhenti;
		
		
		
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data keluarga
	 */
	function update_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Pengalaman Kerja > Update';
		$data['main_view'] 		= 'pengalamankerja/pengalamankerja_form';
		$data['form_action']	= site_url('pengalamankerja/update_process');
		$data['link'] 			= array('link_back' => anchor('pengalamankerja','Kembali Ke List Data Pengalaman Kerja', array('class' => 'back'))
										);
										
		// data NIK untuk dropdown menu
		$karyawan = $this->Pengalamankerja_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		$tahunini = date("Y");		
		for($i=1920;$i<=$tahunini;$i++)
		{
			$data['options_thn'][$i] = $i;
		}

		// Set validation rules
		$this->form_validation->set_rules('nama_perusahaan', 'Nama Perusahaan', 'required|max_length[50]');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'required|max_length[50]');		
		$this->form_validation->set_rules('alamat_perusahaan', '', '');
		$this->form_validation->set_rules('tahun_mulai_kerja', '', '');
		$this->form_validation->set_rules('tahun_selesai_kerja', '', '');
		$this->form_validation->set_rules('alasan_berhenti', '', '');
		
		if ($this->form_validation->run() == TRUE)
		{
			// save data
		
			$pengalaman_kerja= array('nik'					=> $this->input->post('nik'),
									'nama_perusahaan'			=> $this->input->post('nama_perusahaan'),
									'alamat_perusahaan'			=> $this->input->post('alamat_perusahaan'),
									'tahun_mulai_kerja'			=> $this->input->post('tahun_mulai_kerja'),
									'tahun_selesai_kerja'		=> $this->input->post('tahun_selesai_kerja'),
									'jabatan'					=> $this->input->post('jabatan'),
									'alasan_berhenti'			=> $this->input->post('alasan_berhenti')
							
						);
			$this->Pengalamankerja_model->update($this->session->userdata('id_pengalamankerja'), $pengalaman_kerja);
			
			$this->session->set_flashdata('message', 'Satu data Pengalaman Kerja berhasil diupdate!');
			redirect('pengalamankerja');
		}
		else
		{		
			$data['default']['id_pengalamankerja'] = $this->input->post('id_pengalamankerja');
			$this->load->view('template', $data);
		}
	}
	
	//function to export to excel
	function toexcel() {
		$query['data1'] = $this->Pengalamankerja_model->alldata();
		//print_r($query['data1']);exit;
		$this->load->view('pengalamankerja/excel_view',$query);
	}
	
	//convert to pdf	
	function topdf () {
		$this->load->library('cezpdf');
		$this->load->helper('pdf');
		prep_pdf();
		$data['member']= $this->Pengalamankerja_model->alldata();
		$titlecolumn = array(
							'nik' 					=> 'NIK',
							'nama_karyawan' 		=> 'NAMA KARYAWAN',
							'nama_perusahaan' 		=> 'NAMA PERUSAHAAN',
							'alamat_perusahaan' 	=> 'ALAMAT PERUSAHAAN',
							'tahun_mulai_kerja' 	=> 'TAHUN MULAI KERJA',
							'tahun_selesai_kerja'	=> 'TAHUN SELESAI KERJA',
							'jabatan' 				=> 'JABATAN',
							'alasan_berhenti' 		=> 'ALASAN BERHENTI',
								
		);
		$this->cezpdf->ezTable($data['member'], $titlecolumn,'Rekap Data Pengalaman Kerja');
		$this->cezpdf->ezStream();
	}
	
	//convert to word	
	function toword () {
	
		$this->load->library('HTML_TO_DOC');
		$pengalamankerja = $this->Pengalamankerja_model->get_allPengalamankerja();
		
		$strHtml = '<center><p><img src="'.base_url().'/asset/images/gph.png"/><br><br>';
		$strHtml .= '<strong>ERP SOFTWARE FOR HOTELS</strong><br>';
		
		$strHtml .= '<small>Oleh Kelompok 5</small></p><hr></center><br>';
		
		$strHtml .= '<center><h3>Data Pengalaman Kerja '.date("j M Y").'</h3></center><br>';
		if(!empty($pengalamankerja)){
			$strHtml .= '<table width="100%" border="1">';
			$strHtml .= '<tr><td>NO</td><td>NIK</td><td>NAMA KARYAWAN</td><td>NAMA PERUSAHAAN</td><td>ALAMAT PERUSAHAAN</td><td>TAHUN MULAI KERJA</td><td>TAHUN SELESAI KERJA</td><td>JABATAN</td><td>ALASAN BERHENTI</td></tr>';
			$i = 0;
			foreach ($pengalamankerja as $row)
			{
				
				$strHtml .= '<tr><td>'.++$i.'</td><td>'.$row->nik.'</td><td>'.$row->nama_karyawan.'</td><td>'.$row->nama_perusahaan.'</td><td>'.$row->alamat_perusahaan.'</td><td>'.$row->tahun_mulai_kerja.'</td><td>'.$row->tahun_selesai_kerja.'</td><td>'.$row->jabatan.'</td><td>'.$row->alasan_berhenti.'</td></tr>';
			}
			$strHtml .= '</table>';
		}else{
			$strHtml .= '<br>( Tidak ada data pengalaman kerja )<br>';
		}
		
		$htmltodoc = new HTML_TO_DOC();
		$htmltodoc->createDoc($strHtml,'Data-PengalamanKerja-'.date("j-M-Y").'.doc',$download=true);
	}
	
}