<?php
class Jabatan_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Jabatan_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'jabatan';
	
	/**
	 * Mendapatkan data semua jabatan karyawan
	 */
	function get_all($limit, $offset, $fieldpencarian, $pencarian, $departemen)
	{
		$this->db->select('*');
		$this->db->from('karyawan, jabatan, departemen, departemen_jabatan');
		$this->db->where('karyawan.nik = jabatan.nik');
		$this->db->where('jabatan.id_jabatan = departemen_jabatan.id_jabatan');
		$this->db->where('departemen_jabatan.id_departemen = departemen.id_departemen');
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		$this->db->like('departemen.nama_departemen', $departemen);
		$this->db->limit($limit, $offset);
		$this->db->order_by('jabatan.nik');
		return $this->db->get()->result();
	}
	
	//pdf
	function alldata()
	{
		$this->db->select('*');
		$this->db->from('karyawan, jabatan, departemen, departemen_jabatan');
		$this->db->where('karyawan.nik = jabatan.nik');
		$this->db->where('jabatan.id_jabatan = departemen_jabatan.id_jabatan');
		$this->db->where('departemen_jabatan.id_departemen = departemen.id_departemen');
		$this->db->order_by('jabatan.nik');
		$getData = $this->db->get();
		if($getData->num_rows() > 0)
			return $getData->result_array();
		else 
			return null;
	}
	
	//doc
	function get_allJabatan()
	{
		$this->db->select('*');
		$this->db->from('karyawan, jabatan, departemen, departemen_jabatan');
		$this->db->where('karyawan.nik = jabatan.nik');
		$this->db->where('jabatan.id_jabatan = departemen_jabatan.id_jabatan');
		$this->db->where('departemen_jabatan.id_departemen = departemen.id_departemen');
		$this->db->order_by('jabatan.nik');
		return $this->db->get()->result();
	}
	
	/**
	 * Mendapatkan semua data karyawan, diurutkan berdasarkan nama_karyawan
	 */
	function get_karyawan()
	{
		$this->db->order_by('nama_karyawan');
		return $this->db->get('karyawan');
	}
	
	/**
	 * Mendapatkan semua data jabatan, diurutkan berdasarkan jabatan_departemen
	 */
	function get_jabatan()
	{
		$this->db->order_by('jabatan');
		return $this->db->get('departemen_jabatan');
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
	 * Mendapatkan data sebuah jabatan
	 */
	function get_jabatan_by_id($nik)
	{
		$this->db->select('*');
		$this->db->from('karyawan, jabatan, departemen, departemen_jabatan');
		$this->db->where('karyawan.nik = jabatan.nik');
		$this->db->where('jabatan.id_jabatan = departemen_jabatan.id_jabatan');
		$this->db->where('departemen_jabatan.id_departemen = departemen.id_departemen');
		$this->db->where('jabatan.nik', $nik);
		$this->db->order_by('jabatan.nik');
		return $this->db->get()->row();
	}
	
	/**
	 * Menghitung jumlah baris tabel jabatan
	 */
	function count_all($fieldpencarian, $pencarian, $departemen)
	{
		//return $this->db->count_all($this->table);
		$this->db->select('*');
		$this->db->from('karyawan, jabatan, departemen, departemen_jabatan');
		$this->db->where('karyawan.nik = jabatan.nik');
		$this->db->where('jabatan.id_jabatan = departemen_jabatan.id_jabatan');
		$this->db->where('departemen_jabatan.id_departemen = departemen.id_departemen');
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		$this->db->like('departemen.nama_departemen', $departemen);
		return $this->db->count_all_results();
	}
	
	/**
	 * Menghapus sebuah data jabatan
	 */
	function delete($nik)
	{
		$this->db->delete($this->table, array('nik' => $nik));
	}
	
	/**
	 * Tambah data jabatan
	 */
	function add($jabatan)
	{
		$this->db->insert($this->table, $jabatan);
	}
	
	/**
	 * Update data jabatan
	 */
	function update($nik, $jabatan)
	{
		$this->db->where('nik', $nik);
		$this->db->update($this->table, $jabatan);
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