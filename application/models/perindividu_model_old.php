<?php

class Perindividu_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Perindividu_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel yang digunakan
	var $table = 'presensi_old';
	
	/**
	 * Mendapatkan data
	 */
	function get_presensi()
	{
		$sql = "SELECT karyawan.nik, karyawan.nama_karyawan,
				/* ----------- jumlah tepat waktu ------------*/
				(SELECT COUNT(presensi_old.status)
				FROM presensi_old
				WHERE presensi_old.status = '0'
				AND presensi_old.nik = karyawan.nik
				AND presensi_old.nik IN (SELECT karyawan.nik
								  FROM karyawan
								  ORDER BY karyawan.nik ASC)
				GROUP BY presensi_old.nik
				ORDER BY presensi_old.nik ASC) AS tepat_waktu,
				
				/* ----------- jumlah terlambat ------------*/
				(SELECT COUNT(presensi_old.status)
				FROM presensi_old
				WHERE presensi_old.status = '1'
				AND presensi_old.nik = karyawan.nik
				AND presensi_old.nik IN (SELECT karyawan.nik
								  FROM karyawan
								  ORDER BY karyawan.nik ASC)
				GROUP BY presensi_old.nik
				ORDER BY presensi_old.nik ASC) AS terlambat
			FROM karyawan
			GROUP BY karyawan.nik
			ORDER BY karyawan.nik ASC;";
		return $this->db->query($sql);
	}
	
	/**
	 * Mendapatkan data
	 */
	function get_presensi2()
	{
		$sql = "SELECT karyawan.nik, karyawan.nama_karyawan,
				/* ----------- jumlah tepat waktu ------------*/
				(SELECT COUNT(presensi_old.status)
				FROM presensi_old
				WHERE presensi_old.status = '0'
				AND presensi_old.nik = karyawan.nik
				AND presensi_old.nik IN (SELECT karyawan.nik
								  FROM karyawan
								  ORDER BY karyawan.nik ASC)
				GROUP BY presensi_old.nik
				ORDER BY presensi_old.nik ASC) AS tepat_waktu,
				
				/* ----------- jumlah terlambat ------------*/
				(SELECT COUNT(presensi_old.status)
				FROM presensi_old
				WHERE presensi_old.status = '1'
				AND presensi_old.nik = karyawan.nik
				AND presensi_old.nik IN (SELECT karyawan.nik
								  FROM karyawan
								  ORDER BY karyawan.nik ASC)
				GROUP BY presensi_old.nik
				ORDER BY presensi_old.nik ASC) AS terlambat
			FROM karyawan
			GROUP BY karyawan.nik
			ORDER BY karyawan.nik ASC;";
		return $this->db->query($sql);
	}
}