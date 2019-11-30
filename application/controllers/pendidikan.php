<?php

class Pendidikan extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Pendidikan_model', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>), dan 
	 * $limit untuk membatasi penampilan data di tabel
	 */
	var $limit = 10;
	var $title = 'Data Pendidikan Hotel';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menampilkan halaman pendidikan,
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
	 * Tampilkan semua data pendidikan
	 */
	function get_all($offset = 0)
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Pendidikan';
		$data['main_view'] = 'pendidikan/pendidikan';
		
		// Offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// mengisi form dropdown
		$data['options_jenjang'][''] = '- Semua Jenjang -';
		$data['options_jenjang']['SD/MI'] = 'SD/MI';
		$data['options_jenjang']['SMP/MTs'] = 'SMP/MTs';
		$data['options_jenjang']['SMA/SMK/MA'] = 'SMA/SMK/MA';
		$data['options_jenjang']['D1'] = 'D1';
		$data['options_jenjang']['D2'] = 'D2'; 
		$data['options_jenjang']['D3'] = 'D3';
		$data['options_jenjang']['D4'] = 'D4';
		$data['options_jenjang']['S1'] = 'S1';
		$data['options_jenjang']['S2'] = 'S2'; 
		$data['options_jenjang']['S3'] = 'S3';
		
		// Load data
		if(isset($_POST['cari']))
		{
			$data['fieldpencarian'] = $this->input->post('field');
			$data['pencarian'] = $this->input->post('kunci');
			$data['status_jenjang'] = $this->input->post('jenjang');
			
			//set session user data untuk pencarian, untuk paging pencarian
			$this->session->set_userdata('sess_fieldpencarianPendidikan', $data['fieldpencarian']);
			$this->session->set_userdata('sess_pencarianPendidikan', $data['pencarian']);
			$this->session->set_userdata('sess_status_jenjang', $data['status_jenjang']);
			
		} else {
			$data['fieldpencarian'] = $this->session->userdata('sess_fieldpencarianPendidikan');
			$data['pencarian'] = $this->session->userdata('sess_pencarianPendidikan');
			$data['status_jenjang'] = $this->session->userdata('sess_status_jenjang');
		}
		
		$pendidikan = $this->Pendidikan_model->get_all($this->limit, $offset, $data['fieldpencarian'], $data['pencarian'], $data['status_jenjang']);
		$num_rows = $this->Pendidikan_model->count_all($data['fieldpencarian'], $data['pencarian'], $data['status_jenjang']);
		
		if ($num_rows > 0)
		{
			// Generate pagination			
			$config['base_url'] = site_url('pendidikan/get_all');
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
			$this->table->set_heading('No', 'NIK','Nama Karyawan', 'Jenjang', 'Nama Sekolah', 'Tahun Sekolah', 'Actions');
			$i = 0 + $offset;
			
			foreach ($pendidikan as $row)
			{
			$this->table->add_row(++$i, $row->nik, $row->nama_karyawan, $row->status_pendidikan, $row->nama_sekolah, $row->tahun_masuk.' - '.$row->tahun_lulus,
										anchor('pendidikan/detail/'.$row->id_pendidikan,'<img alt="Detail" src="'.base_url().'/asset/images/icons/view.png"> View').' '.
										anchor('pendidikan/update/'.$row->id_pendidikan,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit').' '.
										anchor('pendidikan/delete/'.$row->id_pendidikan,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
										);
			}
			$data['table'] = $this->table->generate();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan satupun data pendidikan!';
		}		
		
		$data['link'] = array('link_add' => anchor('pendidikan/add/','Add New Data Pendidikan', array('class' => 'button'))
								);
		$data['download'] = array('link_excel' => anchor('pendidikan/toexcel/','EXCEL', array('class' => 'excel')),
									'link_pdf' => anchor('pendidikan/topdf/','PDF', array('class' => 'pdf', 'target' => '_blank')),
									'link_word' => anchor('pendidikan/toword/','WORD', array('class' => 'word'))
								);
		
		// Load view
		$this->load->view('template', $data);
	}
	
	
	function detail($id_pendidikan)
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Pendidikan > Detail';
		$data['main_view'] 		= 'pendidikan/pendidikan_view';
		$data['link'] 			= array('link_back' => anchor('pendidikan','Kembali Ke List Data Pendidikan', array('class' => 'back'))
										);		
		
		$data['edit_link'] = anchor('pendidikan/update/'.$id_pendidikan,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit');
		$data['delete_link'] = anchor('pendidikan/delete/'.$id_pendidikan,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
		
		// Load data
		$pendidikan = $this->Pendidikan_model->get_pendidikan_by_id($id_pendidikan);
		
		// Table
		/*Set table template for alternating row 'zebra'*/
		$tmpl = array( 'table_open'    => '<table>',
					  'row_alt_start'  => '<tr>',
						'row_alt_end'    => '</tr>'
					  );
		$this->table->set_template($tmpl);
	
		$this->table->add_row('<strong>NIK</strong>',':',$pendidikan->nik);
		$this->table->add_row('<strong>Nama karyawan</strong>',':',$pendidikan->nama_karyawan);
		$this->table->add_row('<strong>Jenjang</strong>',':',$pendidikan->status_pendidikan);
		$this->table->add_row('<strong>Nama Sekolah</strong>',':',$pendidikan->nama_sekolah);
		$this->table->add_row('<strong>Jurusan</strong>',':',$pendidikan->jurusan);
		$this->table->add_row('<strong>Tahun Sekolah</strong>',':',($pendidikan->tahun_masuk.' - '.$pendidikan->tahun_lulus));
		$this->table->add_row('<strong>No ijazah</strong>',':',$pendidikan->no_ijazah);
		
		
		$data['table'] = $this->table->generate();
				
		$this->load->view('template', $data);
	}
	
		
	/**
	 * Hapus data pendidikan
	 */
	function delete($id_pendidikan)
	{
		$this->Pendidikan_model->delete($id_pendidikan);
		$this->session->set_flashdata('message', '1 data pendidikan berhasil dihapus');
		
		redirect('pendidikan');
	}
	
	/**
	 * Pindah ke halaman tambah pendidikan
	 */
	function add()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Pendidikan > Tambah Data';
		$data['main_view'] 		= 'pendidikan/pendidikan_form';
		$data['form_action']	= site_url('pendidikan/add_process');
		$data['link'] 			= array('link_back' => anchor('pendidikan','Kembali Ke List Data Pendidikan', array('class' => 'back'))
								);	                    
								
		$karyawan = $this->Pendidikan_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		$data['options_pendidikan']['SD/MI'] = 'SD/MI';
		$data['options_pendidikan']['SMP/MTs'] = 'SMP/MTs';
		$data['options_pendidikan']['SMA/SMK/MA'] = 'SMA/SMK/MA';
		$data['options_pendidikan']['D1'] = 'D1';
		$data['options_pendidikan']['D2'] = 'D2'; 
		$data['options_pendidikan']['D3'] = 'D3';
		$data['options_pendidikan']['D4'] = 'D4';
		$data['options_pendidikan']['S1'] = 'S1';
		$data['options_pendidikan']['S2'] = 'S2'; 
		$data['options_pendidikan']['S3'] = 'S3';   
		
		$tahunini = date("Y");		
		for($i=1920;$i<=$tahunini;$i++)
		{
			$data['options_thn'][$i] = $i;
		}       
		
		$this->load->view('template', $data);	
	}
	
	function add_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Pendidikan > Tambah Data';
		$data['main_view'] 		= 'pendidikan/pendidikan_form';
		$data['form_action']	= site_url('pendidikan/add_process');
		$data['link'] 			= array('link_back' => anchor('pendidikan','Kembali Ke List Data Pendidikan', array('class' => 'back'))
										);
										
		$karyawan = $this->Pendidikan_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		$data['options_pendidikan']['SD/MI'] = 'SD/MI';
		$data['options_pendidikan']['SMP/MTs'] = 'SMP/MTs';
		$data['options_pendidikan']['SMA/SMK/MA'] = 'SMA/SMK/MA';
		$data['options_pendidikan']['D1'] = 'D1';
		$data['options_pendidikan']['D2'] = 'D2'; 
		$data['options_pendidikan']['D3'] = 'D3';
		$data['options_pendidikan']['D4'] = 'D4';
		$data['options_pendidikan']['S1'] = 'S1';
		$data['options_pendidikan']['S2'] = 'S2'; 
		$data['options_pendidikan']['S3'] = 'S3';   
		
		$tahunini = date("Y");		
		for($i=1920;$i<=$tahunini;$i++)
		{
			$data['options_thn'][$i] = $i;
		}   							
		
		// Set validation rules
		$this->form_validation->set_rules('nama_sekolah', 'Nama Sekolah', 'required|max_length[32]');
		$this->form_validation->set_rules('jurusan', '', '');
		$this->form_validation->set_rules('no_ijazah', '', '');
		
		// Jika validasi sukses
		if ($this->form_validation->run() == TRUE)
		{
			// Persiapan data
			$pendidikan = array('nik'					=> $this->input->post('nik'),
								'status_pendidikan'					=> $this->input->post('status_pendidikan'),
								'nama_sekolah'					=> $this->input->post('nama_sekolah'),
								'jurusan'					=> $this->input->post('jurusan'),
								'tahun_masuk'					=> $this->input->post('tahun_masuk'),
								'tahun_lulus'					=> $this->input->post('tahun_lulus'),
							  	'no_ijazah'					=> $this->input->post('no_ijazah')	
						);
			// Proses penyimpanan data di table pendidikan
			$this->Pendidikan_model->add($pendidikan);
			
			$this->session->set_flashdata('message', 'Satu data pendidikan berhasil disimpan!');
			redirect('pendidikan');
		}
		// Jika validasi gagal
		else
		{		
			$this->load->view('template', $data);
		}		
	}
	
	/**
	 * Pindah ke halaman update pendidikan
	 */
	function update($id_pendidikan)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Pendidikan > Update';
		$data['main_view'] 		= 'pendidikan/pendidikan_form';
		$data['form_action']	= site_url('pendidikan/update_process');
		$data['link'] 			= array('link_back' => anchor('pendidikan','Kembali Ke List Data Pendidikan', array('class' => 'back'))
										);
												
		$karyawan = $this->Pendidikan_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		$data['options_pendidikan']['SD/MI'] = 'SD/MI';
		$data['options_pendidikan']['SMP/MTs'] = 'SMP/MTs';
		$data['options_pendidikan']['SMA/SMK/MA'] = 'SMA/SMK/MA';
		$data['options_pendidikan']['D1'] = 'D1';
		$data['options_pendidikan']['D2'] = 'D2'; 
		$data['options_pendidikan']['D3'] = 'D3';
		$data['options_pendidikan']['D4'] = 'D4';
		$data['options_pendidikan']['S1'] = 'S1';
		$data['options_pendidikan']['S2'] = 'S2'; 
		$data['options_pendidikan']['S3'] = 'S3';   
		
		$tahunini = date("Y");		
		for($i=1920;$i<=$tahunini;$i++)
		{
			$data['options_thn'][$i] = $i;
		}         
		
		// cari data dari database
		$pendidikan = $this->Pendidikan_model->get_pendidikan_by_id($id_pendidikan);
		
		// buat session untuk menyimpan data primary key (id_pendidikan)
		$this->session->set_userdata('id_pendidikan', $pendidikan->id_pendidikan);
		
		// Data untuk mengisi field2 form
		$data['default']['nik'] 					= $pendidikan->nik;		
		$data['default']['status_pendidikan'] 		= $pendidikan->status_pendidikan;
		$data['default']['nama_sekolah'] 			= $pendidikan->nama_sekolah;	
		$data['default']['jurusan'] 				= $pendidikan->jurusan;	
		$data['default']['tahun_masuk'] 			= $pendidikan->tahun_masuk;	
		$data['default']['tahun_lulus'] 			= $pendidikan->tahun_lulus;		
		$data['default']['no_ijazah'] 				= $pendidikan->no_ijazah;	
		
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data karyawan
	 */
	function update_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Pendidikan > Update';
		$data['main_view'] 		= 'pendidikan/pendidikan_form';
		$data['form_action']	= site_url('pendidikan/update_process');
		$data['link'] 			= array('link_back' => anchor('pendidikan','Kembali Ke List Data Pendidikan', array('class' => 'back'))
										);
		
		$karyawan = $this->Pendidikan_model->get_karyawan()->result();
		foreach($karyawan as $row)
		{
			$data['options_karyawan'][$row->nik] = $row->nama_karyawan;
		}
		
		$data['options_pendidikan']['SD/MI'] = 'SD/MI';
		$data['options_pendidikan']['SMP/MTs'] = 'SMP/MTs';
		$data['options_pendidikan']['SMA/SMK/MA'] = 'SMA/SMK/MA';
		$data['options_pendidikan']['D1'] = 'D1';
		$data['options_pendidikan']['D2'] = 'D2'; 
		$data['options_pendidikan']['D3'] = 'D3';
		$data['options_pendidikan']['D4'] = 'D4';
		$data['options_pendidikan']['S1'] = 'S1';
		$data['options_pendidikan']['S2'] = 'S2'; 
		$data['options_pendidikan']['S3'] = 'S3';   
		
		$tahunini = date("Y");		
		for($i=1920;$i<=$tahunini;$i++)
		{
			$data['options_thn'][$i] = $i;
		}
										
		// Set validation rules
		$this->form_validation->set_rules('nama_sekolah', 'Nama Sekolah', 'required|max_length[32]');
		$this->form_validation->set_rules('jurusan', '', '');
		$this->form_validation->set_rules('no_ijazah', '', '');
		
		if ($this->form_validation->run() == TRUE)
		{
			// save data
			$pendidikan = array('nik'					=> $this->input->post('nik'),
								'status_pendidikan'		=> $this->input->post('status_pendidikan'),
								'nama_sekolah'			=> $this->input->post('nama_sekolah'),
								'jurusan'				=> $this->input->post('jurusan'),
								'tahun_masuk'			=> $this->input->post('tahun_masuk'),
								'tahun_lulus'			=> $this->input->post('tahun_lulus'),
							  	'no_ijazah'				=> $this->input->post('no_ijazah')	
							
						);
			$this->Pendidikan_model->update($this->session->userdata('id_pendidikan'), $pendidikan);
			
			$this->session->set_flashdata('message', 'Satu data Pendidikan berhasil diupdate!');
			redirect('pendidikan');
		}
		else
		{		
			$data['default']['nik'] = $this->input->post('nik');
			$this->load->view('template', $data);
		}
	}
	
	//function to export to excel
	function toexcel() {
		$query['data1'] = $this->Pendidikan_model->alldata();
		//print_r($query['data1']);exit;
		$this->load->view('pendidikan/excel_view',$query);
	}
	
	//convert to pdf	
	function topdf () {
		$this->load->library('cezpdf');
		$this->load->helper('pdf');
		prep_pdf();
		$data['member']= $this->Pendidikan_model->alldata();
		$titlecolumn = array(
							'nik' 				=> 'NIK',
							'nama_karyawan' 	=> 'NAMA KARYAWAN',
							'status_pendidikan' => 'STATUS PENDIDIKAN',
							'nama_sekolah' 		=> 'NAMA SEKOLAH',
							'jurusan' 			=> 'JURUSAN',
							'tahun_masuk' 		=> 'TANGGAL MASUK',
							'tahun_lulus' 		=> 'TANGGAL LULUS',
							'no_ijazah' 		=> 'NO IJAZAH',
	
		);
		$this->cezpdf->ezTable($data['member'], $titlecolumn,'Rekap Data Keluarga');
		$this->cezpdf->ezStream();
	}
	
	//convert to word	
	function toword () {
	
		$this->load->library('HTML_TO_DOC');
		$pendidikan = $this->Pendidikan_model->get_allPendidikan();
		
		$strHtml = '<center><p><img src="'.base_url().'/asset/images/gph.png"/><br><br>';
		$strHtml .= '<strong>ERP SOFTWARE FOR HOTELS</strong><br>';
		
		$strHtml .= '<small>Oleh Kelompok 5</small></p><hr></center><br>';
		
		$strHtml .= '<center><h3>Data Pendidikan '.date("j M Y").'</h3></center><br>';
		if(!empty($pendidikan)){
			$strHtml .= '<table width="100%" border="1">';
			$strHtml .= '<tr><td>NO</td><td>NIK</td><td>NAMA KARYAWAN</td><td>NAMA SEKOLAH</td><td>JURUSAN</td><td>TANGGAL MASUK</td><td>TANGGAL LULUS</td><td>NO IJAZAH</td></tr>';
			$i = 0;
			foreach ($pendidikan as $row)
			{
				
				$strHtml .= '<tr><td>'.++$i.'</td><td>'.$row->nik.'</td><td>'.$row->nama_karyawan.'</td><td>'.$row->nama_sekolah.'</td><td>'.$row->jurusan.'</td><td>'.$row->jurusan.'</td><td>'.$row->tahun_masuk.'</td><td>'.$row->tahun_lulus.'</td></tr>'.$row->no_ijazah.'</td><td>';
			}
			$strHtml .= '</table>';
		}else{
			$strHtml .= '<br>( Tidak ada data pendidikan )<br>';
		}
		
		$htmltodoc = new HTML_TO_DOC();
		$htmltodoc->createDoc($strHtml,'Data-Pendidikan-'.date("j-M-Y").'.doc',$download=true);
	}
}