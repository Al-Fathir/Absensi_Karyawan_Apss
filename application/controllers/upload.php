<?php

class Upload extends CI_Controller {

	function __construct()
	{
		parent::__construct();
        $this->load->helper('file');
		$this->load->helper(array('form', 'url', 'inflector'));
		$this->load->library('form_validation');
		$this->load->model('Upload_model');
	}
	
	var $title = 'Upload Data Log Presensi';
	
	function index()
	{	
		$data['title'] = $this->title;
		$data['h2_title'] = $this->title;
		$data['main_view'] = 'upload/upload_form';
		$this->load->view('template', $data);
	}

    function do_upload()
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
							if($data['cells'][1][1] == 'Departemen') {
                            	$dataexcel[$i-1]['departemen'] = $data['cells'][$i][1];
							}
                            $dataexcel[$i-1]['nama'] = $data['cells'][$i][2];
							$dataexcel[$i-1]['no'] = $data['cells'][$i][3];
							$dataexcel[$i-1]['tanggal'] = $data['cells'][$i][4];
							$dataexcel[$i-1]['status'] = $data['cells'][$i][5];

			}

            delete_files($upload_data['file_path']);
            $this->load->model('Upload_model');
            $this->Upload_model->tambahpresensi($dataexcel);
			$this->session->set_flashdata('message', 'Data Presensi berhasil diupload');
		}
		
		redirect('upload');
		$this->load->view('template', $data);
	}
}
