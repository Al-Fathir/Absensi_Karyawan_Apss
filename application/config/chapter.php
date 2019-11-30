<?php
class Chapter extends CI_Controller {
 function __construct()
 {
   parent::__construct();
   $this->load->model('Querypage');
   $this->load->helper(array('form', 'url', 'inflector'));
   $this->load->library('form_validation');
 }
 public function index()
 {
       if ($this->input->post('submit'))
       {   
	$this->do_upload();
		   // $this->load->view('chapter', $data);
	}
	  else
	  {
	   $this->load->view('chapter');
	  }
 }
function do_upload()
{

    $config['upload_path'] = './temp_upload/';
    $config['allowed_types'] = 'xls';
                
    $this->load->library('upload', $config);

     if ( ! $this->upload->do_upload())
     {
            $data = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('msg_excel', 'Upload data ke database gagal, file hanya ekstensi .xls');
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
            for ($i = 1; $i <= $data['numRows']; $i++) {
               if($data['cells'][$i][1] == '') break;
               $dataexcel[$i-1]['departemen'] = $data['cells'][$i][1];
               $dataexcel[$i-1]['nama'] = $data['cells'][$i][2];
               $dataexcel[$i-1]['no'] = $data['cells'][$i][3];
               $dataexcel[$i-1]['tanggal'] = $data['cells'][$i][4];
			   $dataexcel[$i-1]['status'] = $data['cells'][$i][5];			 
            }
    //cek data
    $check= $this->Querypage->search_chapter($dataexcel);
    if (count($check) > 0)
    {
      $this->Querypage->update_chapter($dataexcel);
      // set pesan
      $this->session->set_flashdata('msg_excel', 'update data success');
  }else{
      $this->Querypage->insert_chapter($dataexcel);
      // set pesan
      $this->session->set_flashdata('msg_excel', 'inserting data success');
  }
  }
  redirect('chapter');
  }
}
?>