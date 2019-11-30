<?php

class Perbulan extends CI_Controller {
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('Perbulan_model', '', TRUE);
	}
	
	var $title = 'Rekap Per Bulan';
	
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
	 * Menampilkan halaman utama rekap per bulan
	 */
	function main()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = $this->title;
		$data['main_view'] = 'perbulan/perbulan';
		$data['form_action'] = site_url('perbulan/get_perbulan');
		
		$tahunini = date("Y");
		
		// data - data untuk dropdown menu		
		$departemen = $this->Perbulan_model->get_departemen()->result();
		$data['options_departemen'][''] = '- Semua Departemen -';
		foreach($departemen as $row)
		{
			$data['options_departemen'][$row->departemen] = 'Departemen '.$row->departemen;
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
		
		// untuk bulan dan tahun sekarang
		$data['default']['bln'] = date("n");
		$data['default']['thn'] = date("Y");
						
		$this->load->view('template', $data);
	}
	
	function get_perbulan()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = $this->title;
		$data['main_view'] = 'perbulan/perbulan';
		$data['form_action'] = site_url('perbulan/get_perbulan');
		
		$tahunini = date("Y");
		
		// data - data untuk dropdown menu
		$departemen = $this->Perbulan_model->get_departemen()->result();
		$data['options_departemen'][''] = '- Semua Departemen -';
		foreach($departemen as $row)
		{
			$data['options_departemen'][$row->departemen] = 'Departemen '.$row->departemen;
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
		
		// untuk bulan dan tahun sekarang
		$data['default']['bln'] = $_POST['bln'];
		$data['default']['thn'] = $_POST['thn'];
		$data['default']['departemen'] = $_POST['departemen'];
		
		$tgldilut = $_POST['thn'].'-'.$_POST['bln'];
		$tanggalnya = date("Y-m",strtotime($tgldilut));
		$departemen = $_POST['departemen'];
		
		$presensi = $this->Perbulan_model->get_presensi($tanggalnya,$departemen)->result();
		$num_rows = $this->Perbulan_model->get_presensi($tanggalnya,$departemen)->num_rows();
		//print_r($presensi);exit;
		
		if ($num_rows > 0)
		{
			$tmpl = array( 'table_open'    => '<table border="0" cellpadding="0" cellspacing="0">',
						  'row_alt_start'  => '<tr class="zebra">',
						  'row_alt_end'    => '</tr>'
						  );
			$this->table->set_template($tmpl);
				
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('No', 'NIK', 'Nama', 'Hadir', 'Tepat Waktu', 'Terlambat', 'Pulang Cepat');
			$i=1;
			 foreach ($presensi as $row)
			 {
				$this->table->add_row($i++, $row->nik,$row->namae,$row->hadir,$row->tepat_waktu,$row->terlambat,$row->pulang_cepat);
			 }
	
			$data['table'] = $this->table->generate();	
			
			if(empty($departemen)){
				$departemennya = 'alldepartemen';
			}
			else
			{
				$departemennya = $departemen;
			}
						
			$data['link'] = array('link_download' => anchor("perbulan/downloadexcel/$departemennya/$tanggalnya",'download excel', array('class' => 'button'))
								);
						
			$this->load->view('template', $data);
		}
		// jika query < 0
		else
		{
			$this->session->set_flashdata('message', 'Data tidak ditemukan!');
			redirect('perbulan');
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
		
		$presensi = $this->Perbulan_model->get_presensi($tanggalnya,$departemennya)->result();
			
			$tmpl = array( 'table_open'    => '<table border="1">',
						  'row_alt_start'  => '<tr class="zebra">',
						  'row_alt_end'    => '</tr>'
						  );
			$this->table->set_template($tmpl);
				
			$this->table->set_empty("&nbsp;");
			$this->table->set_heading('No', 'NIK', 'Nama', 'Hadir', 'Tepat Waktu', 'Terlambat', 'Pulang Cepat');
			$i=1;
			 foreach ($presensi as $row)
			 {
				$this->table->add_row($i++, $row->nik,$row->namae,$row->hadir,$row->tepat_waktu,$row->terlambat,$row->pulang_cepat);
			 }
	
			$data['table'] = $this->table->generate();
			$data['tanggal'] = $tanggalnya;
			$data['departemen'] = $departemennya;
		
		$this->load->view('perbulan/excel_view', $data);
		
	}
	
}
