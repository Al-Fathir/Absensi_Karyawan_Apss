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
				
		// data kelas untuk dropdown menu		
		for($i=1;$i<=30;$i++)
		{
			$data['options_tgl'][$i] = $i;
		}
		for($j=1;$j<=12;$j++)
		{
			$data['options_bln'][$j] = $j;
		}
		for($k=2010;$k<=2013;$k++)
		{
			$data['options_thn'][$k] = $k;
		}
						
		$this->load->view('template', $data);
	}
		
	function duration_time($parambegindate, $paramenddate) {
	  $begindate = strtotime($parambegindate);
	  $enddate = strtotime($paramenddate);
	  $diff = intval($enddate) - intval($begindate);                 
	  $diffday = intval(floor($diff/86400));
	  $modday = ($diff%86400);
	  $diffhour = intval(floor($modday/3600));
	  $diffminute = intval(floor(($modday%3600)/60));
	  $diffsecond = ($modday%60);
	  //return round($diffday)." Day(s), ".round($diffhour)." Hour(s), ".round($diffminute,0)." Minute(s), ".round($diffsecond,0)." Second(s).";
	  return round($diffhour)." Jam, ".round($diffminute,0)." Menit, ".round($diffsecond,0)." Detik.";
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
		
		// data kelas untuk dropdown menu		
		for($i=1;$i<=30;$i++)
		{
			$data['options_tgl'][$i] = $i;
		}
		for($j=1;$j<=12;$j++)
		{
			$data['options_bln'][$j] = $j;
		}
		for($k=2010;$k<=2013;$k++)
		{
			$data['options_thn'][$k] = $k;
		}
		
		// untuk tanggal, bulan, dan tahun yang terpilih
		$data['default']['tgl'] = $_POST['tgl'];
		$data['default']['bln'] = $_POST['bln'];
		$data['default']['thn'] = $_POST['thn'];
		
		$tgldilut = $_POST['thn'].'-'.$_POST['bln'].'-'.$_POST['tgl'];
		$tanggalnya = date("Y-m-d",strtotime($tgldilut));
		
		$kehadiran = $this->Kehadiran_model->get_kehadiranNew($tanggalnya)->result();
		$num_rows 	= $this->Kehadiran_model->get_kehadiranNew($tanggalnya)->num_rows();
		
		if ($num_rows > 0)
		{
				$tmpl = array('table_open'=>'<table border="0" cellpadding="0" cellspacing="0">',
							  'row_alt_start'=>'<tr class="zebra">',
							  'row_alt_end'=>'</tr>'
							  );
				$this->table->set_template($tmpl);
				
				// set table header
				$this->table->set_empty("&nbsp;");
				$this->table->set_heading('No', 'NIK', 'Nama', 'Tgl Presensi', 'Jam Masuk', 'Jam Keluar', 'Status', 'Waktu Kerja', 'Keterangan');
				$i = 0;
				
				foreach ($kehadiran as $row)
				{
					//$status = $row->status == '0' ? 'Hadir' : '';
					$status = 'Hadir';
					$waktuKerja = $this->duration_time($row->masuk,$row->keluar);
					$keterangan = date('H:i:s', strtotime($row->masuk)) <= '08:00:00' ? 'Tepat Waktu' : 'Terlambat '.$this->duration_time("08:00:00",date('H:i:s', strtotime($row->masuk)));
					$this->table->add_row(++$i, $row->departemen, $row->namanya, $tanggalnya, date('H:i:s', strtotime($row->masuk)), date('H:i:s', strtotime($row->keluar)), $status, $waktuKerja, $keterangan);
				}
				
				$data['table'] = $this->table->generate();
				$data['link'] = array('link_add' => anchor("rekap/download/$id_semester/$id_kelas",'download', array('class' => 'excel'))
								);
				
				// Load view
				$this->load->view('template', $data);
		
		}
		// jika query < 0
		else
		{
			$this->session->set_flashdata('message', 'Data tidak ditemukan!');
			redirect('kehadiran');
		}
		//print_r($kehadiran);exit;
		
	}
	
}
