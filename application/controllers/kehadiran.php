<?php

class Kehadiran extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Kehadiran_model', '', TRUE);
	}
	
	var $title = 'Rekap Kehadiran Karyawan';
	
	/**
	 * Memeriksa user state, jika dalam keadaan login akan menjalankan fungsi main()
	 * jika tidak akan meredirect ke halaman login
	 */
	function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->main();
		}
		else
		{
			redirect('login');
		}
	}
	
	/**
	 * Menampilkan halaman utama rekap kehadiran
	 */
	function main()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = $this->title;
		$data['main_view'] = 'kehadiran/kehadiran';
		$data['form_action'] = site_url('kehadiran/get_kehadiran');
				
		$tahunini = date("Y");
		
		// data - data untuk dropdown menu
		
		$departemen = $this->Kehadiran_model->get_departemen()->result();
		$data['options_departemen'][''] = '- Semua Departemen -';
		foreach($departemen as $row)
		{
			$data['options_departemen'][$row->departemen] = 'Departemen '.$row->departemen;
		}
		
		for($i=1;$i<=31;$i++)
		{
			$data['options_tgl'][$i] = $i;
		}
		
		$data['options_bln'][1] = "Januari";
		$data['options_bln'][2] = "Februari";
		$data['options_bln'][3] = "Maret";
		$data['options_bln'][4] = "April";
		$data['options_bln'][5] = "Mei";
		$data['options_bln'][6] = "Juni";
		$data['options_bln'][7] = "Juli";
		$data['options_bln'][8] = "Agustus";
		$data['options_bln'][9] = "September";
		$data['options_bln'][10] = "Oktober";
		$data['options_bln'][11] = "November";
		$data['options_bln'][12] = "Desember";
		
		for($k=2000;$k<=$tahunini;$k++)
		{
			$data['options_thn'][$k] = $k;
		}

		// untuk tanggal, bulan, dan tahun sekarang
		$data['default']['tgl'] = date("j");
		$data['default']['bln'] = date("n");
		$data['default']['thn'] = date("Y");
						
		$this->load->view('template', $data);
	}
		
	function duration_time($parambegindate, $paramenddate) {
	  $begindate = strtotime($parambegindate);
	  $enddate = strtotime($paramenddate);
	  $diff = 0;
	  if($begindate <= $enddate){
		$diff = intval($enddate) - intval($begindate); 
	  }

	  else{
		$diff = 86400 - intval($begindate) + intval($enddate); 
	  }               
	  $diffday = intval(floor($diff/86400));
	  $modday = ($diff%86400);
	  $diffhour = intval(floor($modday/3600));
	  $diffminute = intval(floor(($modday%3600)/60));
	  $diffsecond = ($modday%60);
	  //return round($diffday)." Day(s), ".round($diffhour)." Hour(s), ".round($diffminute,0)." Minute(s), ".round($diffsecond,0)." Second(s).";
	  return round($diffhour)." Jam, ".round($diffminute,0)." Menit";
	}
	
	/**
	 * Mendapatkan data rekap kehadiran dari database, kemudian menampilkan di halaman
	 */
	function get_kehadiran($id_semester = 0, $id_kelas = 0)
	{		
		$data['title'] = $this->title;
		$data['h2_title'] = $this->title;
		$data['main_view'] = 'kehadiran/kehadiran';
		$data['form_action'] = site_url('kehadiran/get_kehadiran');
		
		$tahunini = date("Y");
		
		// data - data untuk dropdown menu		
		
		$departemen = $this->Kehadiran_model->get_departemen()->result();
		$data['options_departemen'][''] = '- Semua Departemen -';
		foreach($departemen as $row)
		{
			$data['options_departemen'][$row->departemen] = 'Departemen '.$row->departemen;
		}
		
		for($i=1;$i<=31;$i++)
		{
			$data['options_tgl'][$i] = $i;
		}
		
		$data['options_bln'][1] = "Januari";
		$data['options_bln'][2] = "Februari";
		$data['options_bln'][3] = "Maret";
		$data['options_bln'][4] = "April";
		$data['options_bln'][5] = "Mei";
		$data['options_bln'][6] = "Juni";
		$data['options_bln'][7] = "Juli";
		$data['options_bln'][8] = "Agustus";
		$data['options_bln'][9] = "September";
		$data['options_bln'][10] = "Oktober";
		$data['options_bln'][11] = "November";
		$data['options_bln'][12] = "Desember";
		
		for($k=2000;$k<=$tahunini;$k++)
		{
			$data['options_thn'][$k] = $k;
		}

		// untuk tanggal, bulan, dan tahun yang terpilih
		$data['default']['tgl'] = $_POST['tgl'];
		$data['default']['bln'] = $_POST['bln'];
		$data['default']['thn'] = $_POST['thn'];
		$data['default']['departemen'] = $_POST['departemen'];
		
		$tgldilut = $_POST['thn'].'-'.$_POST['bln'].'-'.$_POST['tgl'];
		$tanggalnya = date("Y-m-d",strtotime($tgldilut));
		$departemen = $_POST['departemen'];
		
		$kehadiran = $this->Kehadiran_model->get_kehadiran($tanggalnya,$departemen)->result();
		$num_rows 	= $this->Kehadiran_model->get_kehadiran($tanggalnya,$departemen)->num_rows();
		
		if ($num_rows > 0)
		{
				$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
							  'row_alt_start'=>'<tr class="zebra">',
							  'row_alt_end'=>'</tr>'
							  );
				$this->table->set_template($tmpl);
				
				// set table header
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('No', 'NIK', 'Nama', 'Shift', 'Datang', 'Pulang', 'Lama Kerja', 'Keterangan', 'Terlambat', 'Pulang Cepat');
				$i = 0;
				
				foreach ($kehadiran as $row)
				{					
					$terlambat = "-";
					$pulangCepat = "-";
					$waktuKerja = "-";
					
					// Tidak Scan Masuk
					if(empty($row->scan_masuk))
					{
						$keterangan = "Tidak Scan Masuk";
						
						// Pulang Cepat
						if(date('H:i', strtotime($row->scan_pulang)) < date('H:i', strtotime($row->jam_pulang)))
						{
							$pulangCepat = $this->duration_time(date('H:i', strtotime($row->scan_pulang)),date('H:i', strtotime($row->jam_pulang)));
						}
					}
					
					// Tidak Scan Pulang
					else if(empty($row->scan_pulang))
					{
						$keterangan = "Tidak Scan Pulang";
						
						// Terlambat
						if(date('H:i', strtotime($row->scan_masuk)) >= date('H:i', strtotime($row->jam_masuk)))
						{
							$terlambat  = $this->duration_time(date('H:i', strtotime($row->jam_masuk)),date('H:i', strtotime($row->scan_masuk)));
						}
						
					}
					
					// Scan Masuk dan Scan Pulang
					else{
						
						// Terlambat
						if(date('H:i', strtotime($row->scan_masuk)) >= date('H:i', strtotime($row->jam_masuk)))
						{
							$keterangan = "Terlambat";
							$terlambat  = $this->duration_time(date('H:i', strtotime($row->jam_masuk)),date('H:i', strtotime($row->scan_masuk)));
						}else{
							$keterangan = "Tepat Waktu";
						}
						
						// Pulang Cepat
						if(date('H:i', strtotime($row->scan_pulang)) < date('H:i', strtotime($row->jam_pulang)))
						{
							$pulangCepat = $this->duration_time(date('H:i', strtotime($row->scan_pulang)),date('H:i', strtotime($row->jam_pulang)));
						}
						
						$waktuKerja = $this->duration_time($row->scan_masuk,$row->scan_pulang);
					}
					
					$this->table->add_row(++$i, $row->nik, $row->nama, $row->shift, ($row->scan_masuk == '' ? '-' : $row->scan_masuk), ($row->scan_pulang == '' ? '-' : $row->scan_pulang), $waktuKerja, $keterangan, $terlambat, $pulangCepat);
				}
				
				$data['table'] = $this->table->generate();
				
				if(empty($departemen)){
					$departemennya = 'alldepartemen';
				}
				else
				{
					$departemennya = $departemen;
				}
				
				$data['link'] = array('link_download' => anchor("kehadiran/downloadexcel/$departemennya/$tanggalnya",'download excel', array('class' => 'button'))			);
				
				// Load view
				$this->load->view('template', $data);
		
		}
		// jika query < 0
		else
		{
			$this->session->set_flashdata('message', 'Data tidak ditemukan!');
			redirect('kehadiran');
		}
		
	}
	
	// Download ke excel
	function downloadexcel($departemen, $tanggalnya) {
				
				if($departemen=='alldepartemen'){
					$departemennya = '';
				}
				else
				{
					$departemennya = $departemen;
				}
				
				$kehadiran = $this->Kehadiran_model->get_kehadiran($tanggalnya,$departemennya)->result();
		
				$tmpl = array('table_open'=>'<table border="1">',
							  'row_alt_start'=>'<tr class="zebra">',
							  'row_alt_end'=>'</tr>'
							  );
				$this->table->set_template($tmpl);
				
				// set table header
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('No', 'NIK', 'Nama', 'Shift', 'Datang', 'Pulang', 'Lama Kerja', 'Keterangan', 'Terlambat', 'Pulang Cepat');
				$i = 0;
				
				foreach ($kehadiran as $row)
				{					
					$terlambat = "-";
					$pulangCepat = "-";
					$waktuKerja = "-";
					
					// Tidak Scan Masuk
					if(empty($row->scan_masuk))
					{
						$keterangan = "Tidak Scan Masuk";
						
						// Pulang Cepat
						if(date('H:i', strtotime($row->scan_pulang)) < date('H:i', strtotime($row->jam_pulang)))
						{
							$pulangCepat = $this->duration_time(date('H:i', strtotime($row->scan_pulang)),date('H:i', strtotime($row->jam_pulang)));
						}
					}
					
					// Tidak Scan Pulang
					else if(empty($row->scan_pulang))
					{
						$keterangan = "Tidak Scan Pulang";
						
						// Terlambat
						if(date('H:i', strtotime($row->scan_masuk)) >= date('H:i', strtotime($row->jam_masuk)))
						{
							$terlambat  = $this->duration_time(date('H:i', strtotime($row->jam_masuk)),date('H:i', strtotime($row->scan_masuk)));
						}
						
					}
					
					// Scan Masuk dan Scan Pulang
					else{
						
						// Terlambat
						if(date('H:i', strtotime($row->scan_masuk)) >= date('H:i', strtotime($row->jam_masuk)))
						{
							$keterangan = "Terlambat";
							$terlambat  = $this->duration_time(date('H:i', strtotime($row->jam_masuk)),date('H:i', strtotime($row->scan_masuk)));
						}else{
							$keterangan = "Tepat Waktu";
						}
						
						// Pulang Cepat
						if(date('H:i', strtotime($row->scan_pulang)) < date('H:i', strtotime($row->jam_pulang)))
						{
							$pulangCepat = $this->duration_time(date('H:i', strtotime($row->scan_pulang)),date('H:i', strtotime($row->jam_pulang)));
						}
						
						$waktuKerja = $this->duration_time($row->scan_masuk,$row->scan_pulang);
					}
					
					$this->table->add_row(++$i, $row->nik, $row->nama, $row->shift, ($row->scan_masuk == '' ? '-' : $row->scan_masuk), ($row->scan_pulang == '' ? '-' : $row->scan_pulang), $waktuKerja, $keterangan, $terlambat, $pulangCepat);
				}
				
				$data['table'] = $this->table->generate();
				$data['tanggal'] = $tanggalnya;
				$data['departemen'] = $departemennya;
		
		$this->load->view('kehadiran/excel_view', $data);
	}
	
}
