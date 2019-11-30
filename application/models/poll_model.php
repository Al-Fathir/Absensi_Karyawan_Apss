<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class poll_model extends CI_Model{

	function __construct()
	{
		parent::__construct();
	}
	function tampil_soal()
	{
		$q = $this->db->query("select * from tbl_pollingsoal where status='Y'");
		return $q;
	}
	function tampil_jawaban($id)
	{
		$q = $this->db->query("select * from tbl_pollingjawaban where id_soal=$id");
		return $q;
	}
	function simpan_poll($id)
	{
		$q = $this->db->query("update tbl_pollingjawaban set counter=counter+1 where id_jawaban=$id");
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */