<?php

class Profil_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Profil_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'user';
		
	/**
	 * Mendapatkan data sebuah user
	 */
	function get_user_by_username($username)
	{
		return $this->db->get_where($this->table, array('username' => $username), 1)->row();
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