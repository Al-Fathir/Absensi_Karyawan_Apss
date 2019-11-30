<?php

class Gaji_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Gaji_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'gaji';
	
	/**
	 * Mendapatkan data semua gaji karyawan
	 */
	function get_all($limit, $offset, $fieldpencarian, $pencarian, $departemen)
	{
		$this->db->select('*');
		$this->db->from('karyawan, gaji, jabatan, departemen_jabatan, departemen');
		$this->db->where('karyawan.nik = gaji.nik');
		$this->db->where('gaji.nik = jabatan.nik');
		$this->db->where('jabatan.id_jabatan = departemen_jabatan.id_jabatan');
		$this->db->where('departemen_jabatan.id_departemen = departemen.id_departemen');
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		$this->db->limit($limit, $offset);
		$this->db->order_by('gaji.nik');
		return $this->db->get()->result();
	}
	
	//pdf
	function alldata()
	{
		$this->db->select('*');
		$this->db->from('karyawan, gaji');
		$this->db->where('karyawan.nik = gaji.nik');
		$this->db->order_by('gaji.nik');
		$getData = $this->db->get();
		if($getData->num_rows() > 0)
			return $getData->result_array();
		else 
			return null;
	}
	
	//doc
	function get_allGaji()
	{
		$this->db->select('*');
		$this->db->from('karyawan, gaji');
		$this->db->where('karyawan.nik = gaji.nik');
		$this->db->order_by('gaji.nik');
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
	 * Mendapatkan semua data departemen, diurutkan berdasarkan nama_departemen
	 */
	function get_departemen()
	{
		$this->db->order_by('nama_departemen');
		return $this->db->get('departemen');
	}
	
	/**
	 * Mendapatkan data sebuah gaji karyawan
	 */
	function get_gaji_by_id($nik)
	{
		$this->db->select('*');
		$this->db->from('karyawan, gaji');
		$this->db->where('karyawan.nik = gaji.nik ');
		$this->db->where('gaji.nik', $nik);
		$this->db->order_by('gaji.nik');
		return $this->db->get()->row();
	}
	
	/**
	 * Menghitung jumlah baris tabel gaji karyawan
	 */
	function count_all($fieldpencarian, $pencarian, $departemen)
	{
		//return $this->db->count_all($this->table);
		$this->db->select('*');
		$this->db->from('karyawan, gaji, jabatan, departemen_jabatan, departemen');
		$this->db->where('karyawan.nik = gaji.nik');
		$this->db->where('gaji.nik = jabatan.nik');
		$this->db->where('jabatan.id_jabatan = departemen_jabatan.id_jabatan');
		$this->db->where('departemen_jabatan.id_departemen = departemen.id_departemen');
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		return $this->db->count_all_results();
	}
	
	/**
	 * Menghapus sebuah data gaji karyawan
	 */
	function delete($nik)
	{
		$this->db->delete($this->table, array('nik' => $nik));
	}
	
	/**
	 * Tambah data gaji karyawan
	 */
	function add($gaji)
	{
		$this->db->insert($this->table, $gaji);
	}
	
	/**
	 * Update data gaji karyawan
	 */
	function update($nik, $gaji)
	{
		$this->db->where('nik', $nik);
		$this->db->update($this->table, $gaji);
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