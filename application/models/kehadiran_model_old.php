<?php

class Kehadiran_model extends CI_Model {
	/**
	 * Constructor
	 */
	function __Kehadiran_model()
	{
		parent::__construct();
	}
	
	// Inisialisasi nama tabel presensi
	var $table = 'presensi_old';
	
	function get_kehadiran($tanggal)
	{
		$sql = "SELECT * FROM presensi,karyawan WHERE presensi.nik=karyawan.nik AND presensi.tgl_presensi='$tanggal'";
		return $this->db->query($sql);
	}
	
	function get_kehadiranNew($tanggal)
	{
		$sql = "
		select log_presensi.departemen,log_presensi.nama as namanya,(select tgl from log_presensi 

where nama=namanya and tgl like '%$tanggal%' and status=1 group by 

nama) as masuk,(select tgl from log_presensi where nama=namanya and tgl like 

'%$tanggal%' and status=2 group by nama) as keluar
from log_presensi where tgl like '%$tanggal%' group by nama;
		";
		return $this->db->query($sql);
	}
	
	/**
	 * Proses rekap data absensi dengan kriteria semester dan kelas tertentu
	 */
	function get_rekap($id_semester, $id_kelas)
	{
		$sql = "SELECT siswa.nis, siswa.nama,

				/* ----------- jumlah sakit ------------*/
				(SELECT COUNT(absen.absen)
				FROM absen
				WHERE absen.absen = 'S'
				AND absen.id_semester = '$id_semester'
				AND absen.nis = siswa.nis
				AND absen.nis IN (SELECT siswa.nis
								  FROM siswa
								  WHERE siswa.id_kelas = '$id_kelas'
								  ORDER BY siswa.nis ASC)
				GROUP BY absen.nis
				ORDER BY absen.nis ASC) AS Sakit,

				/* ----------- jumlah ijin ------------*/
				(SELECT COUNT(absen.absen)
				FROM absen
				WHERE absen.absen = 'I'
				AND absen.id_semester = '$id_semester'
				AND absen.nis = siswa.nis
				AND absen.nis IN (SELECT siswa.nis
								  FROM siswa
								  WHERE siswa.id_kelas = '$id_kelas'
								  ORDER BY siswa.nis ASC)
				GROUP BY absen.nis
				ORDER BY absen.nis ASC) AS Ijin,

				/* ----------- jumlah alpa ------------*/
				(SELECT COUNT(absen.absen)
				FROM absen
				WHERE absen.absen = 'A'
				AND absen.id_semester = '$id_semester'
				AND absen.nis = siswa.nis
				AND absen.nis IN (SELECT siswa.nis
								  FROM siswa
								  WHERE siswa.id_kelas = '$id_kelas'
								  ORDER BY siswa.nis ASC)
				GROUP BY absen.nis
				ORDER BY absen.nis ASC) AS Alpa,

				/* ----------- jumlah telat ------------*/
				(SELECT COUNT(absen.absen)
				FROM absen
				WHERE absen.absen = 'T'
				AND absen.id_semester = '$id_semester'
				AND absen.nis = siswa.nis
				AND absen.nis IN (SELECT siswa.nis
								  FROM siswa
								  WHERE siswa.id_kelas = '$id_kelas'
								  ORDER BY siswa.nis ASC)
				GROUP BY absen.nis
				ORDER BY absen.nis ASC) AS Telat

			FROM siswa
			WHERE siswa.id_kelas = '$id_kelas'
			GROUP BY siswa.nis
			ORDER BY siswa.nis ASC;";
			
		return $this->db->query($sql);
	}
}