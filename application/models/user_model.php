<?php

class User_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __User_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'user';
	
	/**
	 * Mendapatkan data semua user
	 */
	function get_all($limit, $offset)
	{
		$userlogin = $this->session->userdata('username');
		$this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('username !=', $userlogin);
		$this->db->limit($limit, $offset);
		$this->db->order_by('id_user', 'asc');
		return $this->db->get()->result();
	}
	
	/**
	 * Mendapatkan data sebuah user
	 */
	function get_user_by_id($id_user)
	{
		return $this->db->get_where($this->table, array('id_user' => $id_user), 1)->row();
	}
	
	/**
	 * Menghitung jumlah baris tabel user
	 */
	function count_all()
	{
		return $this->db->count_all($this->table);
	}
	
	/**
	 * Menghapus sebuah data user
	 */
	function delete($id_user)
	{
		$this->db->delete($this->table, array('id_user' => $id_user));
	}
	
	/**
	 * Tambah data user
	 */
	function add($user)
	{
		$this->db->insert($this->table, $user);
	}
	
	/**
	 * Update data user
	 */
	function update($id_user, $user)
	{
		$this->db->where('id_user', $id_user);
		$this->db->update($this->table, $user);
	}
	
	/**
	 * Validasi agar tidak ada username dengan id ganda
	 */
	function valid_id($username)
	{
		$query = $this->db->get_where($this->table, array('username' => $username));
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