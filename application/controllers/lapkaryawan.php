<?php

class Lapkaryawan extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Lapkaryawan_model', '', TRUE);
		$this->load->library('HTML_TO_DOC');
	}
	
	/**
	 * Inisialisasi variabel untuk $title(untuk id element <body>), dan 
	 * $limit untuk membatasi penampilan data di tabel
	 */
	var $limit = 10;
	var $title = 'Laporan Karyawan Hotel';
	
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
		$data['main_view'] = 'laporan/karyawan';
		
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
			$this->session->set_userdata('sess_fieldpencarianLapKaryawan', $data['fieldpencarian']);
			$this->session->set_userdata('sess_pencarianLapKaryawan', $data['pencarian']);
			$this->session->set_userdata('sess_status_aktifLapKaryawan', $data['status_aktif']);
			
		} else {
		
			$data['fieldpencarian'] = $this->session->userdata('sess_fieldpencarianLapKaryawan');
			$data['pencarian'] = $this->session->userdata('sess_pencarianLapKaryawan');
			$data['status_aktif'] = $this->session->userdata('sess_status_aktifLapKaryawan');
			
		}
		
		$karyawan = $this->Lapkaryawan_model->get_all($this->limit, $offset, $data['fieldpencarian'], $data['pencarian'], $data['status_aktif']);
		$num_rows = $this->Lapkaryawan_model->count_all($data['fieldpencarian'], $data['pencarian'], $data['status_aktif']);
		
		if ($num_rows > 0)
		{
			// Generate pagination			
			$config['base_url'] = site_url('lapkaryawan/get_all');
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
			$this->table->set_heading('No', 'NIK', 'Nama Karyawan', 'Telepon', 'Join Date', 'Status', 'Actions To Do');
			$i = 0 + $offset;
			foreach ($karyawan as $row)
			{
				$this->table->add_row(++$i, $row->nik, $row->nama_karyawan, $row->telepon, $this->DateToIndo($row->tgl_masuk), ($row->status_aktif == '1' ? 'Aktif' : '<span style="text-decoration:blink; color:#ff0000">Ex-Karyawan</span>'),
										'<a href="javascript:popUp('."'".base_url()."index.php/lapkaryawan/cetak/".$row->nik."'".')"><img src="'.base_url().'/asset/images/saveprint.png"> PRINT</a>'.' '. 
										anchor('lapkaryawan/savedoc/'.$row->nik,'<img src="'.base_url().'/asset/images/saveword.png"> WORD')
										);
			}
			$data['table'] = $this->table->generate();
		}
		else
		{
			$data['message'] = 'Tidak ditemukan satupun data karyawan!';
		}		
		
		// Load view
		$this->load->view('template', $data);
	}
			
	/**
	 * halaman cetak karyawan
	 */
	function cetak($nik)
	{				
		// Load data
		$karyawan = $this->Lapkaryawan_model->get_karyawan_by_id($nik);
		$keluarga = $this->Lapkaryawan_model->get_keluarga_by_id($nik);
		$pendidikan = $this->Lapkaryawan_model->get_pendidikan_by_id($nik);
		$pengKerja = $this->Lapkaryawan_model->get_pengalamankerja_by_id($nik);
		
		// Table Identitas Pribadi
		/*Set table template for alternating row 'zebra'*/
		$tmpl = array( 'table_open'    => '<table>',
					  'row_alt_start'  => '<tr>',
						'row_alt_end'    => '</tr>'
					  );
		$this->table->set_template($tmpl);
		
		$this->table->add_row('NIK',':',$karyawan->nik);
		$this->table->add_row('Nama Karyawan',':',$karyawan->nama_karyawan);
		$this->table->add_row('Tempat lahir',':',$karyawan->tempat_lahir);
		$this->table->add_row('Tanggal lahir',':',$karyawan->tgl_lahir);
		$this->table->add_row('Jenis kelamin',':',($karyawan->jenis_kelamin == 'L' ? "Laki-laki" : "Perempuan"));
		$this->table->add_row('Agama',':',$karyawan->agama);
		$this->table->add_row('Alamat tinggal',':',$karyawan->alamat_tinggal);
		$this->table->add_row('Alamat asal',':',$karyawan->alamat_asal);
		$this->table->add_row('Telepon',':',$karyawan->telepon);
		$this->table->add_row('Status perkawinan',':',$karyawan->status_perkawinan);
		$this->table->add_row('Tanggal masuk',':',$karyawan->tgl_masuk);
		
		$data['table_pribadi'] = $this->table->generate();
		
		// Table Keluarga
		/*Set table template for alternating row 'zebra'*/
		$tmpl = array( 'table_open'    => '<table cellpadding="4" cellspacing="0" border="1">',
					  'row_alt_start'  => '<tr>',
						'row_alt_end'    => '</tr>'
					  );
		$this->table->set_template($tmpl);
		
		if(!empty($keluarga)){
			/*Set table heading */
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('No', 'Status', 'Nama Keluarga', 'Tgl. Lahir', 'Agama', 'Jenis Kelamin');
			$i = 0;
			foreach ($keluarga as $row)
			{
				$this->table->add_row(++$i, $row->status, $row->nama, $row->tgl_lahir, $row->agama, ($row->jenis_kelamin == 'L' ? "Laki-laki" : "Perempuan"));
			}
		}else{
			$this->table->add_row('Tidak ada data keluarga');
		}
		
		$data['table_keluarga'] = $this->table->generate();
		
		// Table Pendidikan
		/*Set table template for alternating row 'zebra'*/
		$tmpl = array( 'table_open'    => '<table cellpadding="4" cellspacing="0" border="1">',
					  'row_alt_start'  => '<tr>',
						'row_alt_end'    => '</tr>'
					  );
		$this->table->set_template($tmpl);
		
		if(!empty($pendidikan)){
			/*Set table heading */
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('No', 'Jenjang', 'Nama Sekolah', 'Jurusan', 'Tahun Sekolah', 'No. Ijasah');
			$i = 0;
			foreach ($pendidikan as $row)
			{
				$this->table->add_row(++$i, $row->status_pendidikan, $row->nama_sekolah, $row->jurusan, $row->tahun_masuk.'-'.$row->tahun_lulus, $row->no_ijazah);
			}
		}else{
			$this->table->add_row('Tidak ada data pendidikan');
		}
		
		$data['table_pendidikan'] = $this->table->generate();
		
		// Table Pengalaman Kerja
		/*Set table template for alternating row 'zebra'*/
		$tmpl = array( 'table_open'    => '<table cellpadding="4" cellspacing="0" border="1">',
					  'row_alt_start'  => '<tr>',
						'row_alt_end'    => '</tr>'
					  );
		$this->table->set_template($tmpl);
		
		if(!empty($pengKerja)){
			/*Set table heading */
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('No', 'Nama Perusahaan', 'Alamat Perusahaan', 'Jabatan', 'Tahun Bekerja');
			$i = 0;
			foreach ($pengKerja as $row)
			{
				$this->table->add_row(++$i, $row->nama_perusahaan, $row->alamat_perusahaan, $row->jabatan, $row->tahun_mulai_kerja 	.'-'.$row->tahun_selesai_kerja);
			}
		}else{
			$this->table->add_row('Tidak ada data pengalaman kerja');
		}
		
		$data['table_pengalamankerja'] = $this->table->generate();
		
		$this->load->view('laporan/lapkaryawan', $data);
	}
	
	/**
	 * halaman simpan word karyawan
	 */
	function savedoc($nik)
	{
		// Load data
		$karyawan = $this->Lapkaryawan_model->get_karyawan_by_id($nik);
		$keluarga = $this->Lapkaryawan_model->get_keluarga_by_id($nik);
		$pendidikan = $this->Lapkaryawan_model->get_pendidikan_by_id($nik);
		$pengKerja = $this->Lapkaryawan_model->get_pengalamankerja_by_id($nik);
		
		$strHtml = '<center><p><img src="'.base_url().'/asset/images/gph.png"/><br><br>';
		$strHtml .= '<strong>ERP SOFTWARE FOR HOTELS</strong><br>';
		
		$strHtml .= '<small>Oleh Kelompok 5</small></p><hr></center><br>';
		$strHtml .= '<div align="right"><small>Export date : '. date("D, j M Y G:i:s") .'</small></div>';
		
		// Identitas Pribadi
		$strHtml .= '<h2>Data Karyawan '.$nik.'</h2>';
		$strHtml .= 'Saya yang bertanda tangan dibawah ini adalah Karyawan<br><br>';
		$strHtml .= '<strong>Identitas Pribadi</strong>';
		$strHtml .= '<table width="70%" border="0">
					  <tr>
						<td width="35%">NIK</td>
						<td>:</td>
						<td>'.$karyawan->nik.'</td>
					  </tr>
					  <tr>
						<td width="35%">Nama Karyawan</td>
						<td>:</td>
						<td>'.$karyawan->nama_karyawan.'</td>
					  </tr>
					  <tr>
						<td>Tempat lahir</td>
						<td>:</td>
						<td>'.$karyawan->tempat_lahir.'</td>
					  </tr>
					  <tr>
						<td>Tanggal lahir</td>
						<td>:</td>
						<td>'.$karyawan->tgl_lahir.'</td>
					  </tr>
					  <tr>
						<td>Jenis kelamin</td>
						<td>:</td>
						<td>'.($karyawan->jenis_kelamin == 'L' ? "Laki-laki" : "Perempuan").'</td>
					  </tr>
					  <tr>
						<td>Agama</td>
						<td>:</td>
						<td>'.$karyawan->agama.'</td>
					  </tr>
					  <tr>
						<td width="35%">Alamat tinggal</td>
						<td>:</td>
						<td>'.$karyawan->alamat_tinggal.'</td>
					  </tr>
					  <tr>
						<td width="35%">Alamat asal</td>
						<td>:</td>
						<td>'.$karyawan->alamat_asal.'</td>
					  </tr>
					  <tr>
						<td>Telepon</td>
						<td>:</td>
						<td>'.$karyawan->telepon.'</td>
					  </tr>
					  <tr>
						<td>Status perkawinan</td>
						<td>:</td>
						<td>'.$karyawan->status_perkawinan.'</td>
					  </tr>
					  <tr>
						<td>Tanggal masuk</td>
						<td>:</td>
						<td>'.$karyawan->tgl_masuk.'</td>
					  </tr>
					</table>';
		
		// Data Keluarga
		$strHtml .= '<br><strong>Data Keluarga</strong>';
		if(!empty($keluarga)){
			$strHtml .= '<table width="100%" border="1">';
			$strHtml .= '<tr><strong><td>No</td><td>Status</td><td>Nama Keluarga</td><td>Tgl. Lahir</td><td>Agama</td><td>Jenis Kelamin</td></strong></tr>';
			$i = 0;
			foreach ($keluarga as $row)
			{
				$strHtml .= '<tr><td>'.++$i.'</td><td>'.$row->status.'</td><td>'.$row->nama.'</td><td>'.$row->tgl_lahir.'</td><td>'.$row->agama.'</td><td>'.($row->jenis_kelamin == 'L' ? "Laki-laki" : "Perempuan").'</td></tr>';
			}
			$strHtml .= '</table>';
		}else{
			$strHtml .= '<br>( Tidak ada data keluarga )<br>';
		}
		
		// Data Pendidikan
		$strHtml .= '<br><strong>Data Pendidikan</strong>';
		if(!empty($pendidikan)){
			$strHtml .= '<table width="100%" border="1">';
			$strHtml .= '<tr><strong><td>No</td><td>Jenjang</td><td>Nama Sekolah</td><td>Jurusan</td><td>Tahun Sekolah</td><td>No. Ijasah</td></strong></tr>';
			$i = 0;
			foreach ($pendidikan as $row)
			{
				$strHtml .= '<tr><td>'.++$i.'</td><td>'.$row->status_pendidikan.'</td><td>'.$row->nama_sekolah.'</td><td>'.$row->jurusan.'</td><td>'.$row->tahun_masuk.'-'.$row->tahun_lulus.'</td><td>'.$row->no_ijazah.'</td></tr>';
			}
			$strHtml .= '</table>';
		}else{
			$strHtml .= '<br>( Tidak ada data pendidikan )<br>';
		}
		
		// Data Pengalaman Kerja
		$strHtml .= '<br><strong>Data Pengalaman Kerja</strong>';
		if(!empty($pengKerja)){
			$strHtml .= '<table width="100%" border="1">';
			$strHtml .= '<tr><strong><td>No</td><td>Nama Perusahaan</td><td>Alamat Perusahaan</td><td>Jabatan</td><td>Tahun Bekerja</td></strong></tr>';
			$i = 0;
			foreach ($pengKerja as $row)
			{
				$strHtml .= '<tr><td>'.++$i.'</td><td>'.$row->nama_perusahaan.'</td><td>'.$row->alamat_perusahaan.'</td><td>'.$row->jabatan.'</td><td>'.$row->tahun_mulai_kerja 	.'-'.$row->tahun_selesai_kerja.'</td></tr>';
			}
			$strHtml .= '</table>';
		}else{
			$strHtml .= '<br>( Tidak ada data pengalaman kerja )<br>';
		}

		$htmltodoc = new HTML_TO_DOC();
		$htmltodoc->createDoc($strHtml,'Data-Karyawan-'.$nik.'.doc',$download=true);
	}
}