<?php

class Kehadiran_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Kehadiran_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel presensi
	var $table = 'presensi';
	
	function get_kehadiran($tanggal,$departemen)
	{
		$sql = "SELECT * FROM ".$this->table." WHERE tanggal LIKE '%".$tanggal."%' AND departemen LIKE '%".$departemen."%' ORDER BY nama ASC";
		return $this->db->query($sql);
	}
	
	function get_departemen()
	{
		$this->db->select('departemen');
		$this->db->group_by('departemen');
		$this->db->order_by('departemen');
		return $this->db->get($this->table);
	}
}