<?php

class Pendidikan_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Pendidikan_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'pendidikan';
	
	/**
	 * Mendapatkan data semua pendidikan karyawan
	 */
	function get_all($limit, $offset, $fieldpencarian, $pencarian, $status)
	{
		$this->db->select('*');
		$this->db->from('karyawan, pendidikan');
		$this->db->where('karyawan.nik = pendidikan.nik');
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		$this->db->like('status_pendidikan', $status);
		$this->db->limit($limit, $offset);
		$this->db->order_by('pendidikan.nik');
		return $this->db->get()->result();
	}
	
	//pdf
	function alldata()
	{
		$this->db->select('*');
		$this->db->from('karyawan, pendidikan');
		$this->db->where('karyawan.nik = pendidikan.nik');
		$this->db->order_by('pendidikan.nik');
		$getData = $this->db->get();
		if($getData->num_rows() > 0)
			return $getData->result_array();
		else 
			return null;
	}
	
	//doc
	function get_allPendidikan()
	{
		$this->db->select('*');
		$this->db->from('karyawan, pendidikan');
		$this->db->where('karyawan.nik = pendidikan.nik');
		$this->db->order_by('pendidikan.nik');
		return $this->db->get()->result();
	}
	
	
	function get_karyawan()
	{
		$this->db->order_by('nama_karyawan');
		return $this->db->get('karyawan');
	}
	/**
	 * Mendapatkan data sebuah pendidikan
	 */
	function get_pendidikan_by_id($id_pendidikan)
	{
		$this->db->select('*');
		$this->db->from('karyawan, pendidikan');
		$this->db->where('karyawan.nik = pendidikan.nik');
		$this->db->where('pendidikan.id_pendidikan', $id_pendidikan);
		$this->db->order_by('pendidikan.nik');
		return $this->db->get()->row();
	}
	
	/**
	 * Menghitung jumlah baris tabel pendidikan
	 */
	function count_all($fieldpencarian, $pencarian, $status)
	{
		$this->db->select('*');
		$this->db->from('karyawan, pendidikan');
		$this->db->where('karyawan.nik = pendidikan.nik');
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		$this->db->like('status_pendidikan', $status);
		return $this->db->count_all_results();
	}
	
	/**
	 * Menghapus sebuah data pendidikan
	 */
	function delete($id_pendidikan)
	{
		$this->db->delete($this->table, array('id_pendidikan' => $id_pendidikan));
	}
	
	/**
	 * Tambah data pendidikan
	 */
	function add($pendidikan)
	{
		$this->db->insert($this->table, $pendidikan);
	}
	
	/**
	 * Update data pendidikan
	 */
	function update($id_pendidikan, $pendidikan)
	{
		$this->db->where('id_pendidikan', $id_pendidikan);
		$this->db->update($this->table, $pendidikan);
	}
}