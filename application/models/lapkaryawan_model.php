<?php

class Lapkaryawan_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Lapkaryawan_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'karyawan';
	
	/**
	 * Mendapatkan data semua karyawan
	 */
	function get_all($limit, $offset, $fieldpencarian, $pencarian, $status)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		$this->db->like('status_aktif', $status);
		$this->db->limit($limit, $offset);
		$this->db->order_by('nik', 'asc');
		return $this->db->get()->result();
	}
	
	/**
	 * Mendapatkan data sebuah karyawan
	 */
	function get_karyawan_by_id($nik)
	{
		return $this->db->get_where($this->table, array('nik' => $nik), 1)->row();
	}
	
	/**
	 * Menghitung jumlah baris tabel karyawan
	 */
	function count_all($fieldpencarian, $pencarian, $status)
	{
		//return $this->db->count_all($this->table);
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		$this->db->like('status_aktif', $status);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	/**
	 * Mendapatkan data keluarga seorang karyawan
	 */
	function get_keluarga_by_id($nik)
	{
		//return $this->db->get_where('keluarga', array('nik' => $nik))->result();
		$this->db->select('*');
		$this->db->from('keluarga');
		$this->db->where('nik', $nik);
		$this->db->order_by('status', 'desc');
		return $this->db->get()->result();
	}
	
	/**
	 * Mendapatkan data pendidikan seorang karyawan
	 */
	function get_pendidikan_by_id($nik)
	{
		$this->db->select('*');
		$this->db->from('karyawan, pendidikan');
		$this->db->where('karyawan.nik = pendidikan.nik');
		$this->db->where('pendidikan.nik', $nik);
		$this->db->order_by('pendidikan.nik');
		return $this->db->get()->result();
	}
	
	/**
	 * Mendapatkan data pengalaman seorang karyawan
	 */
	function get_pengalamankerja_by_id($nik)
	{
		$this->db->select('*');
		$this->db->from('karyawan, pengalamankerja');
		$this->db->where('karyawan.nik = pengalamankerja.nik');
		$this->db->where('pengalamankerja.nik', $nik);
		$this->db->order_by('pengalamankerja.nik');
		return $this->db->get()->result();
	}
}