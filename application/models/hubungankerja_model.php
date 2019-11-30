<?php

class Hubungankerja_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Hubungankerja_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'hubungankerja';
	
	/**
	 * Mendapatkan data semua hubungankerja karyawan
	 */	
	function get_all($limit, $offset, $fieldpencarian, $pencarian, $status)
	{
		$this->db->select('*');
		$this->db->from('karyawan, hubungankerja');
		$this->db->where('karyawan.nik = hubungankerja.nik');
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		$this->db->like('status_kontrak', $status);
		$this->db->limit($limit, $offset);
		$this->db->order_by('hubungankerja.nik');
		return $this->db->get()->result();
	}	
	
	//pdf
	function alldata()
	{
		$this->db->select('*');
		$this->db->from('karyawan, hubungankerja');
		$this->db->where('karyawan.nik = hubungankerja.nik');
		$this->db->order_by('hubungankerja.nik');
		$getData = $this->db->get();
		if($getData->num_rows() > 0)
			return $getData->result_array();
		else 
			return null;
	}
	
	//doc
	function get_allHubungankerja()
	{
		$this->db->select('*');
		$this->db->from('karyawan, hubungankerja');
		$this->db->where('karyawan.nik = hubungankerja.nik');
		$this->db->order_by('hubungankerja.nik');
		return $this->db->get()->result();
	}
	
	// mendapatkan status kontrak
	function get_status_kontrak()
	{
		$this->db->select('status_kontrak');
		$this->db->group_by('status_kontrak');
		$this->db->order_by('status_kontrak');
		return $this->db->get($this->table);
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
	 * Mendapatkan data sebuah hubungan kerja karyawan
	 */
	function get_hubungankerja_by_id($id_hubungankerja)
	{
		$this->db->select('*');
		$this->db->from('karyawan, hubungankerja');
		$this->db->where('karyawan.nik = hubungankerja.nik');
		$this->db->where('hubungankerja.id_hubungankerja', $id_hubungankerja);
		$this->db->order_by('hubungankerja.nik');
		return $this->db->get()->row();
	}
	
	/**
	 * Menghitung jumlah baris tabel pegawai
	 */
	function count_all($fieldpencarian, $pencarian, $status)
	{
		$this->db->select('*');
		$this->db->from('karyawan, hubungankerja');
		$this->db->where('karyawan.nik = hubungankerja.nik');
		if(!empty($pencarian)) {
			$this->db->like($fieldpencarian, $pencarian);
	   	}
		$this->db->like('status_kontrak', $status);
		return $this->db->count_all_results();
	}
	
	/**
	 * Menghapus sebuah data pegawai
	 */
	function delete($id_hubungankerja)
	{
		$this->db->delete($this->table, array('id_hubungankerja' => $id_hubungankerja));
	}
	
	/**
	 * Tambah data pegawai
	 */
	function add($hubungankerja)
	{
		$this->db->insert($this->table, $hubungankerja);
	}
	
	/**
	 * Update data pegawai
	 */
	function update($id_hubungankerja, $hubungankerja)
	{
		$this->db->where('id_hubungankerja', $id_hubungankerja);
		$this->db->update($this->table, $hubungankerja);
	}
	}