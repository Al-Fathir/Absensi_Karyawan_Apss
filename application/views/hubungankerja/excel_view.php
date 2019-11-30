<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Hubungan_Kerja.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border='1' width="70%">
<tr>
<td>NIK</td>
<td>NAMA KARYAWAN</td>
<td>TANGGAL AWAL</td>   
<td>TANGGAL AKHIR</td>
<td>HUBUNGAN KERJA</td>
</tr>
<?php
foreach($data1 as $item) {
?>
<tr>
<td><?php echo $item['nik']?></td>
<td><?php echo $item['nama_karyawan']?></td>
<td><?php echo $item['tanggal_masuk']?></td>
<td><?php echo $item['tanggal_keluar']?></td>
<td><?php echo $item['status_kontrak']?></td>
</tr>
<?php } ?>
</table>
