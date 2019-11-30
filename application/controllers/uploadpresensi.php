<?php

class Uploadpresensi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
        $this->load->helper('file');
		$this->load->helper(array('form', 'url', 'inflector'));
		$this->load->library('form_validation');
		$this->load->model('Uploadpresensi_model');
	}
	
	var $title = 'Upload Data Presensi';
	
	function index()
	{	
		$data['title'] = $this->title;
		$data['h2_title'] = $this->title;
		$data['main_view'] = 'uploadpresensi/uploadpresensi_form';
		$this->load->view('template', $data);
	}

    function do_upload_presensi()
	{
		$data['title'] = $this->title;
		$data['h2_title'] = $this->title;
		$config['upload_path'] = './temp_upload/';
		$config['allowed_types'] = 'xls';
                
		$this->load->library('upload', $config);
        
		if ( ! $this->upload->do_upload())
		{
			$data = array('error' => $this->upload->display_errors());
			$this->session->set_flashdata('message', $data['error']);
		}
		else
		{
            $data = array('error' => false);
			$upload_data = $this->upload->data();

            $this->load->library('excel_reader');
			$this->excel_reader->setOutputEncoding('CP1251');

			$file =  $upload_data['full_path'];
			$this->excel_reader->read($file);
			error_reporting(E_ALL ^ E_NOTICE);

			// Sheet 1
			$data = $this->excel_reader->sheets[0] ;
			$dataexcel = Array();
			//for ($i = 1; $i <= $data['numRows']; $i++) {
			for ($i = 2; $i <= $data['numRows']; $i++) {

                            if($data['cells'][$i][1] == '') break;
		                 	$dataexcel[$i-1]['nik'] = $data['cells'][$i][1];
                            $dataexcel[$i-1]['nama'] = $data['cells'][$i][2];
							$dataexcel[$i-1]['tanggal'] = $data['cells'][$i][3];
							$dataexcel[$i-1]['shift'] = $data['cells'][$i][4];
							$dataexcel[$i-1]['jam_masuk'] = $data['cells'][$i][5];
							$dataexcel[$i-1]['jam_pulang'] = $data['cells'][$i][6];
							$dataexcel[$i-1]['scan_masuk'] = $data['cells'][$i][7];
							$dataexcel[$i-1]['scan_pulang'] = $data['cells'][$i][8];
							$dataexcel[$i-1]['normal'] = $data['cells'][$i][9];
							$dataexcel[$i-1]['realtime'] = $data['cells'][$i][10];
							$dataexcel[$i-1]['terlambat'] = $data['cells'][$i][11];
							$dataexcel[$i-1]['pulang_cepat'] = $data['cells'][$i][12];
							$dataexcel[$i-1]['absen'] = $data['cells'][$i][13];
							$dataexcel[$i-1]['lembur'] = $data['cells'][$i][14];
							$dataexcel[$i-1]['jam_kerja'] = $data['cells'][$i][15];
							$dataexcel[$i-1]['pengecualian'] = $data['cells'][$i][16];
							$dataexcel[$i-1]['harus_c_in'] = $data['cells'][$i][17];
							$dataexcel[$i-1]['harus_c_out'] = $data['cells'][$i][18];
							$dataexcel[$i-1]['departemen'] = $data['cells'][$i][19];
							$dataexcel[$i-1]['ndays'] = $data['cells'][$i][20];
							$dataexcel[$i-1]['weekend'] = $data['cells'][$i][21];
							$dataexcel[$i-1]['holiday'] = $data['cells'][$i][22];
							$dataexcel[$i-1]['lama_kerja'] = $data['cells'][$i][23];
							$dataexcel[$i-1]['ndays_ot'] = $data['cells'][$i][24];
							$dataexcel[$i-1]['weekend_ot'] = $data['cells'][$i][25];
							$dataexcel[$i-1]['holiday_ot'] = $data['cells'][$i][26];
							

			}

            delete_files($upload_data['file_path']);
            $this->load->model('Uploadpresensi_model');
            $this->Uploadpresensi_model->tambahpresensi($dataexcel);
			$this->session->set_flashdata('message', 'Data Presensi berhasil diupload');
		}
		
		redirect('uploadpresensi');
		$this->load->view('template', $data);
	}
}
