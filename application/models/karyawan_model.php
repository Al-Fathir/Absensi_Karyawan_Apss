<?php

class Karyawan_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Karyawan_model()
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
	 * Mendapatkan data semua karyawan
	 */
	function get_allKaryawan()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->order_by('nik', 'asc');
		return $this->db->get()->result();
	}
	
	function alldata()
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->order_by('nik','ASC');
		$getData = $this->db->get();
		if($getData->num_rows() > 0)
			return $getData->result_array();
		else 
			return null;
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
	 * Menghapus sebuah data karyawan
	 */
	function delete($nik)
	{
		$this->db->delete($this->table, array('nik' => $nik));
	}
	
	/**
	 * Tambah data karyawan
	 */
	function add($karyawan)
	{
		$this->db->insert($this->table, $karyawan);
	}
	
	/**
	 * Update data karyawan
	 */
	function update($nik, $karyawan)
	{
		$this->db->where('nik', $nik);
		$this->db->update($this->table, $karyawan);
	}
	
	/**
	 * Validasi agar tidak ada nik dengan id ganda
	 */
	function valid_id($nik)
	{
		$query = $this->db->get_where($this->table, array('nik' => $nik));
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}