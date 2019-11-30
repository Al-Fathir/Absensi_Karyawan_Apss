<?php

class Karyawan_terbaik_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Karyawan_terbaik_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'presensi';
	
	/**
	 * Mendapatkan data
	 */
	function get_presensi($tanggal, $departemen){
		$sql = "SELECT nik,nama as namae, 
					(SELECT COUNT(terlambat) FROM presensi WHERE terlambat<>'' AND nama=namae AND tanggal LIKE '%".$tanggal."%') AS terlambat,
					(SELECT COUNT(pulang_cepat) FROM presensi WHERE pulang_cepat<>'' AND nama=namae AND tanggal LIKE '%".$tanggal."%') AS pulang_cepat,
					(SELECT COUNT(nama) FROM presensi WHERE terlambat='' AND nama=namae AND tanggal LIKE '%".$tanggal."%') AS tepat_waktu,
				COUNT(nama) AS hadir 
				FROM presensi 
				WHERE tanggal LIKE '%".$tanggal."%'
				AND departemen LIKE '%".$departemen."%'
				GROUP BY nama ORDER BY hadir DESC, tepat_waktu DESC, terlambat, pulang_cepat
				LIMIT 2";
		return $this->db->query($sql);
	}
	
	function get_departemen()
	{
		$this->db->select('departemen');
		$this->db->group_by('departemen');
		$this->db->order_by('departemen');
		return $this->db->get($this->table);
	}
}