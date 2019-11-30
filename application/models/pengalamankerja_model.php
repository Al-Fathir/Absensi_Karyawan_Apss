<?php

class Pengalamankerja_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Pengalamankerja_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'pengalamankerja';
	
	/**
	 * Mendapatkan data semua pengalamankerja karyawan
	 */	
	function get_all($limit, $offset, $fieldpencarian, $pencarian)
	{
		$this->db->select('*');
		$this->db->from('karyawan, pengalamankerja');
		$this->db->where('karyawan.nik = pengalamankerja.nik');
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		$this->db->limit($limit, $offset);
		$this->db->order_by('pengalamankerja.nik');
		return $this->db->get()->result();
	}
	
	//pdf
	function alldata()
	{
		$this->db->select('*');
		$this->db->from('karyawan, pengalamankerja');
		$this->db->where('karyawan.nik = pengalamankerja.nik');
		$this->db->order_by('pengalamankerja.nik');
		$getData = $this->db->get();
		if($getData->num_rows() > 0)
			return $getData->result_array();
		else 
			return null;
	}
	
	//doc
	function get_allPengalamankerja()
	{
		$this->db->select('*');
		$this->db->from('karyawan, pengalamankerja');
		$this->db->where('karyawan.nik = pengalamankerja.nik');
		$this->db->order_by('pengalamankerja.nik');
		return $this->db->get()->result();
	}
	
	/**
	 * Mendapatkan semua data karyawan, diurutkan berdasarkan nik
	 */
	function get_karyawan()
	{
		$this->db->order_by('nama_karyawan');
		return $this->db->get('karyawan');
	}
	
	/**
	 * Mendapatkan data sebuah karyawan
	 */
	function get_pengalamankerja_by_id($id_pengalamankerja)
	{
		$this->db->select('*');
		$this->db->from('karyawan, pengalamankerja');
		$this->db->where('karyawan.nik = pengalamankerja.nik');
		$this->db->where('pengalamankerja.id_pengalamankerja', $id_pengalamankerja);
		$this->db->order_by('pengalamankerja.nik');
		return $this->db->get()->row();
	}
	
	/**
	 * Menghitung jumlah baris tabel karyawan
	 */
	function count_all($fieldpencarian, $pencarian)
	{
		$this->db->select('*');
		$this->db->from('karyawan, pengalamankerja');
		$this->db->where('karyawan.nik = pengalamankerja.nik');
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		return $this->db->count_all($this->table);
	}
	
	/**
	 * Menghapus sebuah data karyawan
	 */
	function delete($id_pengalamankerja)
	{
		$this->db->delete($this->table, array('id_pengalamankerja' => $id_pengalamankerja));
	}
	
	/**
	 * Tambah data karyawan
	 */
	function add($pengalamankerja)
	{
		$this->db->insert($this->table, $pengalamankerja);
	}
	
	/**
	 * Update data karyawan
	 */
	function update($id_pengalamankerja, $pengalamankerja)
	{
		$this->db->where('id_pengalamankerja', $id_pengalamankerja);
		$this->db->update($this->table, $pengalamankerja);
	}
}