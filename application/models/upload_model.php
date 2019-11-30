<?php
class Upload_model extends CI_Model {

    function __Upload_model()
    {
	parent::__construct();
        parent::__construct();
    }

    function tambahpresensi($dataarray)
    {
        //for($i=0;$i<count($dataarray);$i++){
		for($i=1;$i<count($dataarray);$i++){
            $data = array(
                'departemen'=>$dataarray[$i]['departemen'],
                'nama'=>$dataarray[$i]['nama'],
				'no'=>$dataarray[$i]['no'],
                'tanggal'=>$dataarray[$i]['tanggal'],
				'status'=>$dataarray[$i]['status']
            );
			$departemen = $data['departemen'];
			$nama = $data['nama'];
			$no = $data['no'];
			$tanggal = $data['tanggal'];
			$status = $data['status'];
			
			if($status== 'C/Masuk'){
				$statuse = 1;
			}elseif($status== 'C/Keluar'){
				$statuse = 2;
			}
			
			$tanggale = date('Y-m-d H:i:s', strtotime($tanggal));
			
			$sql = "INSERT IGNORE INTO log_presensi VALUES ('$departemen', '$nama', '$no', '$tanggale', '$tanggal', '$statuse')";
			$this->db->query($sql);
        }
    }
}
?>