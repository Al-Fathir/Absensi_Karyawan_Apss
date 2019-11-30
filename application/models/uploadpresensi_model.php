<?php
class Uploadpresensi_model extends CI_Model {

    function __Uploadpresensi_model()
    {
	parent::__construct();
        parent::__construct();
    }

    function tambahpresensi($dataarray)
    {
        //for($i=0;$i<count($dataarray);$i++){
		for($i=1;$i<count($dataarray);$i++){
            $data = array(
	
                'nik'=>$dataarray[$i]['nik'],
                'nama'=>$dataarray[$i]['nama'],
				'tanggal'=>$dataarray[$i]['tanggal'],
                'shift'=>$dataarray[$i]['shift'],
				'jam_masuk'=>$dataarray[$i]['jam_masuk'],
				'jam_pulang'=>$dataarray[$i]['jam_pulang'],
				'scan_masuk'=>$dataarray[$i]['scan_masuk'],
                'scan_pulang'=>$dataarray[$i]['scan_pulang'],
				'normal'=>$dataarray[$i]['normal'],
				'relatime'=>$dataarray[$i]['realtime'],
				'terlambat'=>$dataarray[$i]['terlambat'],
                'pulang_cepat'=>$dataarray[$i]['pulang_cepat'],
				'absen'=>$dataarray[$i]['absen'],
				'lembur'=>$dataarray[$i]['lembur'],
				'jam_kerja'=>$dataarray[$i]['jam_kerja'],
				'pengecualian'=>$dataarray[$i]['pengecualian'],
				'harus_c_in'=>$dataarray[$i]['harus_c_in'],
				'departemen'=>$dataarray[$i]['departemen'],
				'ndays'=>$dataarray[$i]['ndays'],
				'weekend'=>$dataarray[$i]['weekend'],
				'holiday'=>$dataarray[$i]['holiday'],
				'lama_kerja'=>$dataarray[$i]['lama_kerja'],
				'ndays_ot'=>$dataarray[$i]['ndays_ot'],
				'weekend_ot'=>$dataarray[$i]['weekend_ot'],
				'holiday_ot'=>$dataarray[$i]['holiday_ot']			
            );

			$nik = $data['nik'];
			$nama = $data['nama'];
			$tanggal = $data['tanggal'];
			$shift = $data['shift'];
			$jam_masuk = $data['jam_masuk'];
			$jam_pulang = $data['jam_pulang'];
			$scan_masuk = $data['scan_masuk'];
			$scan_pulang = $data['scan_pulang'];
			$normal = $data['normal'];
			$realtime = $data['realtime'];
			$terlambat = $data['terlambat'];
			$pulang_cepat = $data['pulang_cepat'];
			$absen = $data['absen'];
			$lembur = $data['lembur'];
			$jam_kerja = $data['jam_kerja'];
			$pengecualian = $data['pengecualian'];
			$harus_c_in = $data['harus_c_in'];
			$harus_c_out = $data['harus_c_out'];
			$departemen = $data['departemen'];
			$ndays = $data['ndays'];
			$weekend = $data['weekend'];
			$holiday = $data['holiday'];
			$lama_kerja = $data['lama_kerja'];
			$ndays_ot = $data['ndays_ot'];
			$weekend = $data['weekend_ot'];
			$holiday = $data['holiday_ot'];
			
			$tanggale = date('Y-m-d', strtotime($tanggal));
			$sql = "INSERT IGNORE INTO presensi (nik, nama, tanggal, shift, jam_masuk, jam_pulang, scan_masuk, scan_pulang, normal, realtime, terlambat, pulang_cepat, absen, lembur, jam_kerja, pengecualian, harus_c_in, harus_c_out, departemen, ndays, weekend, holiday, lama_kerja, ndays_ot, weekend_ot, holiday_ot) 
			VALUES ('$nik', '$nama', '$tanggale', '$shift', '$jam_masuk', '$jam_pulang', '$scan_masuk', '$scan_pulang', '$normal' , '$realtime', '$terlambat', '$pulang_cepat', '$absen', '$lembur',	'$jam_kerja', '$pengecualian', '$harus_c_in', '$harus_c_out', '$departemen', '$ndays', '$weekend',	'$holiday', '$lama_kerja', '$ndays_ot', '$weekend_ot', '$holiday_ot')";
			$this->db->query($sql);
        }
    }
}
?>