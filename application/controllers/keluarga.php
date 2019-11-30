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
	var $title = 'Data Keluarga | Grand Palace Hotel';
	
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
	
	/*public function readBook() {
		$nik = $_GET['idnya'];
		echo json_encode( $this->Keluarga_model->getBook($nik) );
	}*/
	
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
		
		// mengisi form dropdown
		$opsi_keluarga = $this->Keluarga_model->get_status_keluarga()->result();
		$data['options_keluarga'][''] = '- Semua Keluarga -';
		foreach($opsi_keluarga as $row)
		{
			$data['options_keluarga'][$row->status] = $row->status;
		}
		
		// Load data
		if(isset($_POST['cari']))
		{
			$data['fieldpencarian'] = $this->input->post('field');
			$data['pencarian'] = $this->input->post('kunci');
			$data['status_keluarga'] = $this->input->post('status');
			
			//set session user data untuk pencarian, untuk paging pencarian
			$this->session->set_userdata('sess_fieldpencarianKeluarga', $data['fieldpencarian']);
			$this->session->set_userdata('sess_pencarianKeluarga', $data['pencarian']);
			$this->session->set_userdata('sess_status_keluarga', $data['status_keluarga']);
			
		} else {
			$data['fieldpencarian'] = $this->session->userdata('sess_fieldpencarianKeluarga');
			$data['pencarian'] = $this->session->userdata('sess_pencarianKeluarga');
			$data['status_keluarga'] = $this->session->userdata('sess_status_keluarga');
		}
		
		$keluarga = $this->Keluarga_model->get_all($this->limit, $offset, $data['fieldpencarian'], $data['pencarian'], $data['status_keluarga']);
		$num_rows = $this->Keluarga_model->count_all($data['fieldpencarian'], $data['pencarian'], $data['status_keluarga']);
		
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
			$this->table->set_heading('No', 'NIK', 'Nama Karyawan', 'Nama Keluarga', 'Jenis Kelamin' , 'Status', 'Actions');
			$i = 0 + $offset;
			
			foreach ($keluarga as $row)
			{
			$this->table->add_row(++$i, $row->nik, $row->nama_karyawan, $row->nama, ($row->jenis_kelamin == 'L' ? "Laki-laki" : "Perempuan"), $row->status,
					anchor('keluarga/detail/'.$row->id_keluarga,'<img alt="Detail" src="'.base_url().'/asset/images/icons/view.png"> View').' '.
					anchor('keluarga/update/'.$row->id_keluarga,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit').' '.
					anchor('keluarga/delete/'.$row->id_keluarga,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))
			);
									
			}
			$data['table'] = $this->table->generate();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan satupun data karyawan!';
		}		
		
		$data['link'] = array('link_add' => anchor('keluarga/add/','Add New Data Keluarga', array('class' => 'button')));
		$data['download'] = array('link_excel' => anchor('keluarga/toexcel/','EXCEL', array('class' => 'excel')),
									'link_pdf' => anchor('keluarga/topdf/','PDF', array('class' => 'pdf', 'target' => '_blank')),
									'link_word' => anchor('keluarga/toword/','WORD', array('class' => 'word'))
								);
		
		// Load view
		$this->load->view('template', $data);
	}
	
	//function detail
	function detail($id_keluarga)
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Keluarga > Detail';
		$data['main_view'] 		= 'keluarga/keluarga_view';
		$data['link'] 			= array('link_back' => anchor('keluarga','Kembali Ke List Data Keluarga', array('class' => 'back'))
										);	
											
		$data['edit_link'] = anchor('keluarga/update/'.$id_keluarga,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit');
		$data['delete_link'] = anchor('keluarga/delete/'.$id_keluarga,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
		
		// Load data
		$keluarga = $this->Keluarga_model->get_keluarga_by_id($id_keluarga);
		
		// Table
		/*Set table template for alternating row 'zebra'*/
		$tmpl = array( 'table_open'    => '<table>',
					  'row_alt_start'  => '<tr>',
						'row_alt_end'    => '</tr>'
					  );
		$this->table->set_template($tmpl);
		
		$this->table->add_row('<strong>NIK</strong>',':',$keluarga->nik);
		$this->table->add_row('<strong>Nama Karyawan</strong>',':',$keluarga->nama_karyawan);
		$this->table->add_row('<strong>Nama Keluarga</strong>',':',$keluarga->nama);
		$this->table->add_row('<strong>Jenis Kelamin</strong>',':',($keluarga->jenis_kelamin == 'L' ? "Laki-laki" : "Perempuan"));
		$this->table->add_row('<strong>Tempat, Tgl Lahir</strong>',':',$keluarga->tempat_lahir.', '.$this->DateToIndo($keluarga->tgl_lahir));
		$this->table->add_row('<strong>Status</strong>',':',$keluarga->status);
		
		$data['table'] = $this->table->generate();
				
		$this->load->view('template', $data);
	}
		
	/**
	 * Hapus data keluarga
	 */
	function delete($id_keluarga)
	{
		$this->Keluarga_model->delete($id_keluarga);
		$this->session->set_flashdata('message', '1 data keluarga berhasil dihapus');
		
		redirect('keluarga');
	}
	
	/**
	 * Pindah ke halaman tambah keluarga
	 */
	function add()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Keluarga > Tambah Data';
		$data['main_view'] 		= 'keluarga/keluarga_form';
		$data['form_action']	= site_url('keluarga/add_process');
		$data['link'] 			= array('link_back' => anchor('keluarga','Kembali Ke List Data Keluarga', array('class' => 'back'))
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
				
		$this->load->view('template', $data);	
	}
	
	function add_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Keluarga > Tambah Data';
		$data['main_view'] 		= 'keluarga/keluarga_form';
		$data['form_action']	= site_url('keluarga/add_process');
		$data['link'] 			= array('link_back' => anchor('keluarga','Kembali Ke List Data Keluarga', array('class' => 'back'))
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
		$this->form_validation->set_rules('jenis_kelamin', '', '');
		$this->form_validation->set_rules('agama', '', '');
		$this->form_validation->set_rules('tempat_lahir', '', '');
		$this->form_validation->set_rules('tgl_lahir', '', '');
		$this->form_validation->set_rules('status', 'Status Hubungan Keluarga', 'required|max_length[32]');
		
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
	function update($id_keluarga)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Keluarga> Update';
		$data['main_view'] 		= 'keluarga/keluarga_form';
		$data['form_action']	= site_url('keluarga/update_process');
		$data['link'] 			= array('link_back' => anchor('keluarga','Kembali Ke List Data Keluarga', array('class' => 'back'))
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
		$keluarga = $this->Keluarga_model->get_keluarga_by_id($id_keluarga);
		
		// buat session untuk menyimpan data primary key (nik)
		$this->session->set_userdata('id_keluarga', $keluarga->id_keluarga);
		
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
		$data['link'] 			= array('link_back' => anchor('keluarga','Kembali Ke List Data Keluarga', array('class' => 'back'))
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
		$this->form_validation->set_rules('jenis_kelamin', '', '');
		$this->form_validation->set_rules('agama', '', '');
		$this->form_validation->set_rules('tempat_lahir', '', '');
		$this->form_validation->set_rules('tgl_lahir', '', '');
		$this->form_validation->set_rules('status', 'Status Hubungan Keluarga', 'required|max_length[32]');
		
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
			$this->Keluarga_model->update($this->session->userdata('id_keluarga'), $keluarga);
			
			$this->session->set_flashdata('message', 'Satu data keluarga berhasil diupdate!');
			redirect('keluarga');
		}
		else
		{		
			$data['default']['nik'] = $this->input->post('nik');
			$this->load->view('template', $data);
		}
	}
	
	//function to export to excel
	function toexcel() {
		$query['data1'] = $this->Keluarga_model->alldata();
		//print_r($query['data1']);exit;
		$this->load->view('keluarga/excel_view',$query);
	}
	
	//convert to pdf	
	function topdf () {
		$this->load->library('cezpdf');
		$this->load->helper('pdf');
		prep_pdf();
		$data['member']= $this->Keluarga_model->alldata();
		$titlecolumn = array(
							'nik' 				=> 'NIK',
							'nama_karyawan' 	=> 'NAMA KARYAWAN',
							'nama' 				=> 'NAMA KELUARGA',
							'tempat_lahir' 		=> 'TEMPAT LAHIR',
							'tgl_lahir' 		=> 'TGL LAHIR',
							'jenis_kelamin' 	=> 'JENIS KELAMIN',
							'status' 			=> 'HUBUNGAN'
							
		);
		$this->cezpdf->ezTable($data['member'], $titlecolumn,'Rekap Data Keluarga The Grand Palace Hotel Yogyakarta');
		$this->cezpdf->ezStream();
	}
	
	//convert to word	
	function toword () {
	
		$this->load->library('HTML_TO_DOC');
		$keluarga = $this->Keluarga_model->get_allKeluarga();
		
		$strHtml = '<center><p><img src="'.base_url().'/asset/images/gph.png"/><br><br>';
		$strHtml .= '<strong>THE GRAND PALACE HOTEL YOGYAKARTA</strong><br>';
		$strHtml .= '<small>Jl. Mangkuyudan No. 32 Yogyakarta 55143</small><br>';
		$strHtml .= '<small>Phone +62 274 414590 . Fax +62 274 417613</small></p><hr></center><br>';
		
		$strHtml .= '<center><h3>Data Keluarga The Grand Palace Hotel Yogyakarta '.date("j M Y").'</h3></center><br>';
		if(!empty($keluarga)){
			$strHtml .= '<table width="100%" border="1">';
			$strHtml .= '<tr><td>NO</td><td>NIK</td><td>NAMA KARYAWAN</td><td>NAMA KELUARGA</td><td>TEMPAT LAHIR</td><td>TGL LAHIR</td><td>JENIS KELAMIN</td><td>STATUS</td></tr>';
			$i = 0;
			foreach ($keluarga as $row)
			{
				$strHtml .= '<tr><td>'.++$i.'</td><td>'.$row->nik.'</td><td>'.$row->nama_karyawan.'</td><td>'.$row->nama.'</td><td>'.$row->tempat_lahir.'</td><td>'.$row->tgl_lahir.'</td><td>'.($row->jenis_kelamin == 'L' ? "Laki-laki" : "Perempuan").'</td><td>'.$row->status.'</td></tr>';
			}
			$strHtml .= '</table>';
		}else{
			$strHtml .= '<br>( Tidak ada data keluarga )<br>';
		}
		
		$htmltodoc = new HTML_TO_DOC();
		$htmltodoc->createDoc($strHtml,'Data-Keluarga-GPH-'.date("j-M-Y").'.doc',$download=true);
	}
}