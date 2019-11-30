<?php

class Karyawan extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Karyawan_model', '', TRUE);
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>), dan 
	 * $limit untuk membatasi penampilan data di tabel
	 */
	var $limit = 10;
	var $title = 'Data Karyawan | Grand Palace Hotel';
	
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
	 * Tampilkan semua data Karyawan
	 */
	function get_all($offset = 0)
	{
		$data['title'] = $this->title;
		$data['h2_title'] = 'Data Karyawan';
		$data['main_view'] = 'karyawan/karyawan';
		
		// Offset
		$uri_segment = 3;
		$offset = $this->uri->segment($uri_segment);
		
		// mengisi dropdown
		$data['options_status'][''] = '- Semua Karyawan -';
		$data['options_status'][1] = "Aktif";
		$data['options_status'][0] = "Ex-Karyawan";
		
		// Load data
		if(isset($_POST['cari']))
		{	
			$data['fieldpencarian'] = $this->input->post('field');
			$data['pencarian'] = $this->input->post('kunci');
			$data['status_aktif'] = $this->input->post('status_aktif');
			
			//set session user data untuk pencarian, untuk paging pencarian
			$this->session->set_userdata('sess_fieldpencarianKaryawan', $data['fieldpencarian']);
			$this->session->set_userdata('sess_pencarianKaryawan', $data['pencarian']);
			$this->session->set_userdata('sess_status_aktif', $data['status_aktif']);
			
		} else {
		
			$data['fieldpencarian'] = $this->session->userdata('sess_fieldpencarianKaryawan');
			$data['pencarian'] = $this->session->userdata('sess_pencarianKaryawan');
			$data['status_aktif'] = $this->session->userdata('sess_status_aktif');
			
		}
		
		$karyawan = $this->Karyawan_model->get_all($this->limit, $offset, $data['fieldpencarian'], $data['pencarian'], $data['status_aktif']);
		$num_rows = $this->Karyawan_model->count_all($data['fieldpencarian'], $data['pencarian'], $data['status_aktif']);
		
		if ($num_rows > 0)
		{
			// Generate pagination			
			$config['base_url'] = site_url('karyawan/get_all');
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
			$tmpl = array( 'table_open'    => '<table>',
						  'row_alt_start'  => '<tr>',
							'row_alt_end'    => '</tr>'
						  );
			$this->table->set_template($tmpl);

			/*Set table heading */
			$this->table->set_empty("&nbsp;");	
			$this->table->set_heading('No', 'NIK', 'Foto', 'Nama Karyawan', 'Telepon', 'Join Date', 'Status', 'Actions To Do');
			$i = 0 + $offset;
			
			foreach ($karyawan as $row)
			{
				$this->table->add_row(++$i, $row->nik, '<img src="'.base_url().$row->img.'" width : 20px height :	 30px>' , $row->nama_karyawan, $row->telepon, $this->DateToIndo($row->tgl_masuk), ($row->status_aktif == '1' ? 'Aktif' : '<span style="text-decoration:blink; color:#ff0000">Ex-Karyawan</span>'),
										anchor('karyawan/detail/'.$row->nik,'<img alt="Detail" src="'.base_url().'/asset/images/icons/view.png"> View').' '.
										anchor('karyawan/update/'.$row->nik,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit').' '.
										anchor('karyawan/delete/'.$row->nik,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"))										
										);
			}
			$data['table'] = $this->table->generate();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan satupun data karyawan!';
		}		
		
		$data['link'] = array('link_add' => anchor('karyawan/add/','Add New Data Karyawan', array('class' => 'button'))
								);
		$data['download'] = array('link_excel' => anchor('karyawan/toexcel/','EXCEL', array('class' => 'excel')),
									'link_pdf' => anchor('karyawan/topdf/','PDF', array('class' => 'pdf', 'target' => '_blank')),
									'link_word' => anchor('karyawan/toword/','WORD', array('class' => 'word'))
								);
		
		// Load view
		$this->load->view('template', $data);
	}
		
	/**
	 * Hapus data karyawan
	 */
	function delete($nik)
	{
		$this->Karyawan_model->delete($nik);
		$this->session->set_flashdata('message', '1 data karyawan berhasil dihapus');
		
		redirect('karyawan');
	}
	
	/**
	 * halaman detail karyawan
	 */
	function detail($nik)
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Karyawan > Detail';
		$data['main_view'] 		= 'karyawan/karyawan_view';
		$data['link'] 			= array('link_back' => anchor('karyawan','Kembali Ke List Data Karyawan', array('class' => 'back'))
										);	
		
		$data['edit_link'] = anchor('karyawan/update/'.$nik,'<img alt="Edit" src="'.base_url().'/asset/images/icons/pencil.png"> Edit');
		$data['delete_link'] = anchor('karyawan/delete/'.$nik,'<img alt="Delete" src="'.base_url().'/asset/images/icons/cross.png"> Delete',array('onclick'=>"return confirm('Anda yakin akan menghapus data ini?')"));
		
		// Load data
		$karyawan = $this->Karyawan_model->get_karyawan_by_id($nik);
		
		// Table
		$tmpl = array( 'table_open'    => '<table>',
					  'row_alt_start'  => '<tr>',
						'row_alt_end'    => '</tr>'
					  );
		$this->table->set_template($tmpl);
		
		$this->table->add_row('<strong>NIK</strong>',':',$karyawan->nik);
		$this->table->add_row('<strong>Nama Karyawan</strong>',':',$karyawan->nama_karyawan);
		$this->table->add_row('<strong>Tempat, Tgl Lahir</strong>',':',$karyawan->tempat_lahir.', '.$this->DateToIndo($karyawan->tgl_lahir));
		$this->table->add_row('<strong>Jenis kelamin</strong>',':',($karyawan->jenis_kelamin == 'L' ? "Laki-laki" : "Perempuan"));
		$this->table->add_row('<strong>Agama</strong>',':',$karyawan->agama);
		$this->table->add_row('<strong>Alamat tinggal</strong>',':',$karyawan->alamat_tinggal);
		$this->table->add_row('<strong>Alamat asal</strong>',':',$karyawan->alamat_asal);
		$this->table->add_row('<strong>Telepon</strong>',':',$karyawan->telepon);
		$this->table->add_row('<strong>Status perkawinan</strong>',':',$karyawan->status_perkawinan);
		$this->table->add_row('<strong>Join Date</strong>',':',$this->DateToIndo($karyawan->tgl_masuk));
		$this->table->add_row('<strong>Status</strong>',':',($karyawan->status_aktif == '1' ? 'Aktif' : '<span style="text-decoration:blink; color:#ff0000">Ex-Karyawan</span>'));
				
		$data['table'] = $this->table->generate();
				
		$this->load->view('template', $data);
	}
	
	/**
	 * Pindah ke halaman tambah karyawan
	 */
	function add()
	{		
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Karyawan > Tambah Data';
		$data['main_view'] 		= 'karyawan/karyawan_form';
		$data['form_action']	= site_url('karyawan/add_process');
		$data['link'] 			= array('link_back' => anchor('karyawan','Kembali Ke List Data Karyawan', array('class' => 'back'))
										);		
		//data dropdown status
		$data['options_statuskaryawan']['1'] = 'Aktif';
		$data['options_statuskaryawan']['0'] = 'Non Aktif';
									
		// data agama untuk dropdown menu
		$data['options_agama']['Islam'] = 'Islam';
		$data['options_agama']['Budha'] = 'Budha';
		$data['options_agama']['Kristen Katolik'] = 'Kristen Katolik';
		$data['options_agama']['Kristen Protestan'] = 'Kristen Protestan';
		$data['options_agama']['Hindu'] = 'Hindu';
		
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses tambah data karyawan
	 */
	function add_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Karyawan > Tambah Data';
		$data['main_view'] 		= 'karyawan/karyawan_form';
		$data['form_action']	= site_url('karyawan/add_process');
		$data['link'] 			= array('link_back' => anchor('karyawan','Kembali Ke List Data Karyawan', array('class' => 'back'))
										);
		
		//data dropdown status
		$data['options_statuskaryawan']['1'] = 'Aktif';
		$data['options_statuskaryawan']['0'] = 'Non Aktif';
		
		// data agama untuk dropdown menu
		$data['options_agama']['Islam'] = 'Islam';
		$data['options_agama']['Budha'] = 'Budha';
		$data['options_agama']['Kristen Katolik'] = 'Kristen Katolik';
		$data['options_agama']['Kristen Protestan'] = 'Kristen Protestan';
		$data['options_agama']['Hindu'] = 'Hindu';
	
		// Set validation rules
		$this->form_validation->set_rules('nik', 'NIK', 'required|numeric|max_length[12]|callback_valid_id');
		$this->form_validation->set_rules('nama', 'Nama Karyawan', 'required|max_length[32]');
		$this->form_validation->set_rules('tempat_lahir', '', '');
		$this->form_validation->set_rules('tgl_lahir', '', '');
		$this->form_validation->set_rules('jenis_kelamin', '', '');
		$this->form_validation->set_rules('agama', '', '');
		$this->form_validation->set_rules('alamat_tinggal', '', '');
		$this->form_validation->set_rules('alamat_asal', '', '');
		$this->form_validation->set_rules('telepon', '', '');
		$this->form_validation->set_rules('status_perkawinan', '', '');
		$this->form_validation->set_rules('tgl_masuk', '', '');
		$this->form_validation->set_rules('status_aktif', '', '');
		
		// Jika validasi sukses
		if ($this->form_validation->run() == TRUE)
		{
			// Persiapan data
			$karyawan = array('nik'					=> $this->input->post('nik'),
							'nama_karyawan'			=> $this->input->post('nama'),
							'tempat_lahir'			=> $this->input->post('tempat_lahir'),
							'tgl_lahir'				=> $this->input->post('tgl_lahir'),
							'jenis_kelamin'			=> $this->input->post('jenis_kelamin'),
							'agama'					=> $this->input->post('agama'),
							'alamat_tinggal'		=> $this->input->post('alamat_tinggal'),
							'alamat_asal'			=> $this->input->post('alamat_asal'),
							'telepon'				=> $this->input->post('telepon'),
							'status_perkawinan'		=> $this->input->post('status_perkawinan'),
							'tgl_masuk'				=> $this->input->post('tgl_masuk'),
							'status_aktif'			=> $this->input->post('status_aktif'),
							'img'					=> $this->_uploadImage(),
						);
			// Proses penyimpanan data di table karyawan
			$this->Karyawan_model->add($karyawan);
			
			$this->session->set_flashdata('message', 'Satu data karyawan berhasil disimpan!');
			redirect('karyawan');
		}
		// Jika validasi gagal
		else
		{		
			$this->load->view('template', $data);
		}		
	}
	
	/**
	 * Pindah ke halaman update karyawan
	 */
	function update($nik)
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Karyawan > Update';
		$data['main_view'] 		= 'karyawan/karyawan_form';
		$data['form_action']	= site_url('karyawan/update_process');
		$data['link'] 			= array('link_back' => anchor('karyawan','Kembali Ke List Data Karyawan', array('class' => 'back'))
										);
		
		//data dropdown status
		$data['options_statuskaryawan']['1'] = 'Aktif';
		$data['options_statuskaryawan']['0'] = 'Non Aktif';
										
		// data agama untuk dropdown menu
		$data['options_agama']['Islam'] = 'Islam';
		$data['options_agama']['Budha'] = 'Budha';
		$data['options_agama']['Kristen Katolik'] = 'Kristen Katolik';
		$data['options_agama']['Kristen Protestan'] = 'Kristen Protestan';
		$data['options_agama']['Hindu'] = 'Hindu';
												
		// cari data dari database
		$karyawan = $this->Karyawan_model->get_karyawan_by_id($nik);
		
		// buat session untuk menyimpan data primary key (nik)
		$this->session->set_userdata('nik', $karyawan->nik);
		
		// Data untuk mengisi field2 form
		$data['default']['nik'] 					= $karyawan->nik;		
		$data['default']['nama'] 					= $karyawan->nama_karyawan;		
		$data['default']['tempat_lahir']			= $karyawan->tempat_lahir;
		$data['default']['tgl_lahir'] 				= $karyawan->tgl_lahir;	
		$data['default']['jenis_kelamin'] 			= $karyawan->jenis_kelamin;		
		$data['default']['agama'] 					= $karyawan->agama;		
		$data['default']['alamat_tinggal']			= $karyawan->alamat_tinggal;
		$data['default']['alamat_asal'] 			= $karyawan->alamat_asal;		
		$data['default']['telepon']					= $karyawan->telepon;	
		$data['default']['status_perkawinan']		= $karyawan->status_perkawinan;
		$data['default']['tgl_masuk']				= $karyawan->tgl_masuk;
		$data['default']['status_aktif']			= $karyawan->status_aktif;
				
		$this->load->view('template', $data);
	}
	
	/**
	 * Proses update data karyawan
	 */
	function update_process()
	{
		$data['title'] 			= $this->title;
		$data['h2_title'] 		= 'Karyawan > Update';
		$data['main_view'] 		= 'karyawan/karyawan_form';
		$data['form_action']	= site_url('karyawan/update_process');
		$data['link'] 			= array('link_back' => anchor('karyawan','Kembali Ke List Data Karyawan', array('class' => 'back'))
										);
		
		//data dropdown status
		$data['options_statuskaryawan']['1'] = 'Aktif';
		$data['options_statuskaryawan']['0'] = 'Non Aktif';
		
		// data agama untuk dropdown menu
		$data['options_agama']['Islam'] = 'Islam';
		$data['options_agama']['Budha'] = 'Budha';
		$data['options_agama']['Kristen Katolik'] = 'Kristen Katolik';
		$data['options_agama']['Kristen Protestan'] = 'Kristen Protestan';
		$data['options_agama']['Hindu'] = 'Hindu';
										
		// Set validation rules
		$this->form_validation->set_rules('nik', 'NIK', 'required|callback_valid_id2');
		$this->form_validation->set_rules('nama', 'Nama Karyawan', 'required|max_length[32]');
		$this->form_validation->set_rules('tempat_lahir', '', '');
		$this->form_validation->set_rules('tgl_lahir', '', '');
		$this->form_validation->set_rules('jenis_kelamin', '', '');
		$this->form_validation->set_rules('agama', '', '');
		$this->form_validation->set_rules('alamat_tinggal', '', '');
		$this->form_validation->set_rules('alamat_asal', '', '');
		$this->form_validation->set_rules('telepon', '', '');
		$this->form_validation->set_rules('status_perkawinan', '', '');
		$this->form_validation->set_rules('tgl_masuk', '', '');
		$this->form_validation->set_rules('status_aktif', '', '');
		
		if ($this->form_validation->run() == TRUE)
		{
			// save data
			$karyawan = array('nik'					=> $this->input->post('nik'),
							'nama_karyawan'			=> $this->input->post('nama'),
							'tempat_lahir'			=> $this->input->post('tempat_lahir'),
							'tgl_lahir'				=> $this->input->post('tgl_lahir'),
							'jenis_kelamin'			=> $this->input->post('jenis_kelamin'),
							'agama'					=> $this->input->post('agama'),
							'alamat_tinggal'		=> $this->input->post('alamat_tinggal'),
							'alamat_asal'			=> $this->input->post('alamat_asal'),
							'telepon'				=> $this->input->post('telepon'),
							'status_perkawinan'		=> $this->input->post('status_perkawinan'),
							'tgl_masuk'				=> $this->input->post('tgl_masuk'),
							'status_aktif'			=> $this->input->post('status_aktif')
						);
			$this->Karyawan_model->update($this->session->userdata('nik'), $karyawan);
			
			$this->session->set_flashdata('message', 'Satu data karyawan berhasil diupdate!');
			redirect('karyawan');
		}
		else
		{		
			$data['default']['nik'] = $this->input->post('nik');
			$this->load->view('template', $data);
		}
	}

	function _uploadImage(){
		$config['upload_path']          = './upload/images/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['file_name']            = $this->product_id;
		$config['overwrite']			= true;
		$config['max_size']             = 1024; // 1MB
		// $config['max_width']            = 1024;
		// $config['max_height']           = 768;

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('image')) {
			return $this->upload->data("file_name");
		}
		
		return "default.jpg";
	}

	/**
	 * Cek apakah $nik valid, agar tidak ganda
	 */
	function valid_id($nik)
	{
		if ($this->Karyawan_model->valid_id($nik) == TRUE)
		{
			$this->form_validation->set_message('valid_id', "Karyawan dengan kode $nik sudah terdaftar");
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
			if($this->Karyawan_model->valid_id($new_id) === TRUE) // cek database untuk entry yang sama memakai valid_entry()
			{
				$this->form_validation->set_message('valid_id2', "Karyawan dengan kode $new_id sudah terdaftar");
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
		$query['data1'] = $this->Karyawan_model->alldata();
		//print_r($query['data1']);exit;
		$this->load->view('karyawan/excel_view',$query);
	}
	
	//convert to pdf	
	function topdf () {
		$this->load->library('cezpdf');
		$this->load->helper('pdf');
		prep_pdf();
		$data['member']= $this->Karyawan_model->alldata();
		$titlecolumn = array(
							'nik' => 'NIK',
							'nama_karyawan' 	=> 'NAMA KARYAWAN',
							'tempat_lahir' 		=> 'TEMPAT LAHIR',
							'tgl_lahir' 		=> 'TGL LAHIR',
							'jenis_kelamin' 	=> 'JENIS KELAMIN',
							'agama' 			=> 'AGAMA',
							'alamat_tinggal'	=> 'ALAMAT TINGGAL',
							'alamat_asal' 		=> 'ALAMAT ASAL',
							'telepon' 			=> 'TELEPON',
							'status_perkawinan' => 'STATUS PERKAWINAN',
							'tgl_masuk' 		=> 'JOIN DATE'
		);
		$this->cezpdf->ezTable($data['member'], $titlecolumn,'Rekap Data Karyawan The Grand Palace Hotel Yogyakarta');
		$this->cezpdf->ezStream();
	}
	
	//convert to word	
	function toword () {
	
		$this->load->library('HTML_TO_DOC');
		$karyawan = $this->Karyawan_model->get_allKaryawan();
		
		$strHtml = '<center><p><img src="'.base_url().'/asset/images/gph.png"/><br><br>';
		$strHtml .= '<strong>THE GRAND PALACE HOTEL YOGYAKARTA</strong><br>';
		$strHtml .= '<small>Jl. Mangkuyudan No. 32 Yogyakarta 55143</small><br>';
		$strHtml .= '<small>Phone +62 274 414590 . Fax +62 274 417613</small></p><hr></center><br>';
		
		$strHtml .= '<center><h3>Data Karyawan The Grand Palace Hotel Yogyakarta '.date("j M Y").'</h3></center><br>';
		if(!empty($karyawan)){
			$strHtml .= '<table width="100%" border="1">';
			$strHtml .= '<tr><td>NO</td><td>NIK</td><td>NAMA KARYAWAN</td><td>TEMPAT LAHIR</td><td>TGL LAHIR</td><td>JENIS KELAMIN</td><td>AGAMA</td><td>ALAMAT TINGGAL</td><td>ALAMAT ASAL</td><td>TELEPON</td><td>STATUS PERKAWINAN</td><td>JOIN DATE</td></tr>';
			$i = 0;
			foreach ($karyawan as $row)
			{
				$strHtml .= '<tr><td>'.++$i.'</td><td>'.$row->nik.'</td><td>'.$row->nama_karyawan.'</td><td>'.$row->tempat_lahir.'</td><td>'.$row->tgl_lahir.'</td><td>'.($row->jenis_kelamin == 'L' ? "Laki-laki" : "Perempuan").'</td><td>'.$row->agama.'</td><td>'.$row->alamat_tinggal.'</td><td>'.$row->alamat_asal.'</td><td>'.$row->telepon.'</td><td>'.$row->status_perkawinan.'</td><td>'.$row->tgl_masuk.'</td></tr>';
			}
			$strHtml .= '</table>';
		}else{
			$strHtml .= '<br>( Tidak ada data karyawan )<br>';
		}
		
		$htmltodoc = new HTML_TO_DOC();
		$htmltodoc->createDoc($strHtml,'Data-Karyawan-GPH-'.date("j-M-Y").'.doc',$download=true);
	}
}