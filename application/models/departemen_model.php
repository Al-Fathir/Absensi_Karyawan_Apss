<?php

class Departemen_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Departemen_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'departemen';
	
	/**
	 * Mendapatkan data semua departemen
	 */	
	function get_all($limit, $offset)
	{
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->limit($limit, $offset);
		$this->db->order_by('id_departemen', 'asc');
		return $this->db->get()->result();
	}
	/**
	 * Mendapatkan data sebuah departemen
	 */
	function get_departemen_by_id($id_departemen)
	{
		return $this->db->get_where($this->table, array('id_departemen' => $id_departemen), 1)->row();
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
	function delete($id_departemen)
	{
		$this->db->delete($this->table, array('id_departemen' => $id_departemen));
	}
	
	/**
	 * Tambah data departemen
	 */
	function add($departemen)
	{
		$this->db->insert($this->table, $departemen);
	}
	
	/**
	 * Update data departemen
	 */
	function update($id_departemen, $departemen)
	{
		$this->db->where('id_departemen', $id_departemen);
		$this->db->update($this->table, $departemen);
	}
	
	/**
	 * Validasi agar tidak ada departemen dengan id ganda
	 */
	function valid_id($id_departemen)
	{
		$query = $this->db->get_where($this->table, array('id_departemen' => $id_departemen));
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