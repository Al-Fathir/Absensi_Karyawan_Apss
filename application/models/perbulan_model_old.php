<?php

class Perbulan_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Perbulan_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'presensi_old';
	
	/**
	 * Mendapatkan data
	 */
	function get_presensi()
	{
		$this->db->select('tgl_presensi, count(status) as tepat');
		$this->db->from($this->table);
		$this->db->where('status', 0);
		$this->db->group_by('tgl_presensi');
		$this->db->order_by('tgl_presensi', 'asc');
		return $this->db->get()->result();
	}
}