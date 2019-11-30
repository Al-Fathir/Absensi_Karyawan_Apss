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
	function get_all($limit, $offset, $fieldpencarian, $pencarian, $status)
	{
		$this->db->select('*');
		$this->db->from('karyawan, keluarga');
		$this->db->where('karyawan.nik = keluarga.nik');
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		$this->db->like('status', $status);
		$this->db->limit($limit, $offset);
		$this->db->order_by('keluarga.nik');
		return $this->db->get()->result();
	}
	
	//pdf
	function alldata()
	{
		$this->db->select('*');
		$this->db->from('karyawan, keluarga');
		$this->db->where('karyawan.nik = keluarga.nik');
		$this->db->order_by('keluarga.nik');
		$getData = $this->db->get();
		if($getData->num_rows() > 0)
			return $getData->result_array();
		else 
			return null;
	}
	
	//doc
	function get_allKeluarga()
	{
		$this->db->select('*');
		$this->db->from('karyawan, keluarga');
		$this->db->where('karyawan.nik = keluarga.nik');
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
	
	public function getBook($id_keluarga) {
        //$query = $this->db->get( 'karyawan' );
		$query = $this->db->get_where($this->table, array('id_keluarga' => $id_keluaraga));
        if( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return array();
        }
    }
	
	// mendapatkan status keluarga
	function get_status_keluarga()
	{
		$this->db->select('status');
		$this->db->group_by('status');
		$this->db->order_by('status');
		return $this->db->get($this->table);
	}
	
	/**
	 * Mendapatkan data sebuah keluarga
	 */
	function get_keluarga_by_id($id_keluarga)
	{
		//return $this->db->get_where($this->table, array('id_keluarga' => $id_keluarga), 1)->row();
		$this->db->select('karyawan.nama_karyawan, keluarga.*');
		$this->db->from('karyawan, keluarga');
		$this->db->where('karyawan.nik = keluarga.nik');
		$this->db->where('keluarga.id_keluarga', $id_keluarga);
		return $this->db->get()->row();
	}
	
	/**
	 * Menghitung jumlah baris tabel keluarga karyawan
	 */
	function count_all($fieldpencarian, $pencarian, $status)
	{
		$this->db->select('*');
		$this->db->from('karyawan, keluarga');
		$this->db->where('karyawan.nik = keluarga.nik');
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		$this->db->like('status', $status);
		return $this->db->count_all_results();
	}
	
	/**
	 * Menghapus sebuah data keluarga
	 */
	function delete($id_keluarga)
	{
		$this->db->delete($this->table, array('id_keluarga' => $id_keluarga));
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
	function update($id_keluarga, $keluarga)
	{
		$this->db->where('id_keluarga', $id_keluarga);
		$this->db->update($this->table, $keluarga);
	}
	
	/**
	 * Validasi agar tidak ada nik dengan id ganda
	 */
}