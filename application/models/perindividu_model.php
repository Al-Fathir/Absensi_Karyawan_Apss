<?php

class Perindividu_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Perindividu_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel presensi
	var $table = 'presensi';
	
	function get_individu($tanggal,$nik)
	{
		$sql = "SELECT * FROM ".$this->table." WHERE tanggal LIKE '%".$tanggal."%' AND nik LIKE '%".$nik."%' ORDER BY tanggal ASC";
		return $this->db->query($sql);
	}
		
	function get_karyawan()
	{
		$this->db->select('nik,nama');
		$this->db->group_by('nama');
		$this->db->order_by('nama');
		return $this->db->get($this->table);
	}
}