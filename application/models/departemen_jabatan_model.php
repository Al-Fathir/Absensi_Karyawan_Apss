<?php

class Departemen_jabatan_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Departemen_jabatan_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'departemen_jabatan';
	
	/**
	 * Mendapatkan data semua departemen
	 */	
	function get_all($limit, $offset)
	{
		$this->db->select('*');
		$this->db->from('departemen, departemen_jabatan');
		$this->db->where('departemen.id_departemen = departemen_jabatan.id_departemen');
		$this->db->limit($limit, $offset);
		$this->db->order_by('departemen_jabatan.id_departemen');
		return $this->db->get()->result();
	}
	
	function get_departemen()
	{
		$this->db->order_by('nama_departemen');
		return $this->db->get('departemen');
	}
	/**
	 * Mendapatkan data sebuah departemen
	 */
	function get_departemen_jabatan_by_id($id_jabatan)
	{
		return $this->db->get_where($this->table, array('id_jabatan' => $id_jabatan), 1)->row();
	}
	/**
	 * Menghitung jumlah baris tabel departemen
	 */
	function count_all()
	{
		return $this->db->count_all($this->table);
	}
	
	/**
	 * Menghapus sebuah data departemen
	 */
	function delete($id_jabatan)
	{
		$this->db->delete($this->table, array('id_jabatan' => $id_jabatan));
	}
	
	/**
	 * Tambah data departemen
	 */
	function add($departemen_jabatan)
	{
		$this->db->insert($this->table, $departemen_jabatan);
	}
	
	/**
	 * Update data departemen
	 */
	function update($id_jabatan, $departemen_jabatan)
	{
		$this->db->where('id_jabatan', $id_jabatan);
		$this->db->update($this->table, $departemen_jabatan);
	}
	
}