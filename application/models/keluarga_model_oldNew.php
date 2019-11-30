<?php

class Keluarga_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Keluarga_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'keluarga';
	
	/**
	 * Mendapatkan data semua karyawan
	 */
	function get_Allkaryawan($limit, $offset)
	{
		$this->db->select('*');
		$this->db->from('karyawan');
		$this->db->limit($limit, $offset);
		$this->db->order_by('nik', 'asc');
		return $this->db->get()->result();
	}
	
	/**
	 * Mendapatkan data semua keluarga karyawan
	 */	
	function get_all($limit, $offset)
	{
		$this->db->select('*');
		$this->db->from('karyawan, keluarga');
		$this->db->where('karyawan.nik = keluarga.nik');
		$this->db->limit($limit, $offset);
		$this->db->order_by('keluarga.nik');
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
	
	public function getBook($nik) {
        //$query = $this->db->get( 'karyawan' );
		$query = $this->db->get_where($this->table, array('nik' => $nik));
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return array();
        }
    }
	
	/**
	 * Mendapatkan data sebuah keluarga
	 */
	function get_keluarga_by_id($nik)
	{
		return $this->db->get_where($this->table, array('nik' => $nik), 1)->row();
	}
	
	/**
	 * Menghitung jumlah baris tabel karyawan
	 */
	function count_all()
	{
		return $this->db->count_all('karyawan');
	}
	
	/**
	 * Menghapus sebuah data keluarga
	 */
	function delete($nik)
	{
		$this->db->delete($this->table, array('nik' => $nik));
	}
	
	/**
	 * Tambah data keluarga
	 */
	function add($keluarga)
	{
		$this->db->insert($this->table, $keluarga);
	}
	
	/**
	 * Update data keluarga
	 */
	function update($nik, $keluarga)
	{
		$this->db->where('nik', $nik);
		$this->db->update($this->table, $keluarga);
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