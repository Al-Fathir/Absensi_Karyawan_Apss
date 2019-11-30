<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Gaji_Karyawan.xls");
header("Pragma: no-cache");
header("Expires: 0");

?>
<table border='1' width="70%">
<tr>
<td>NIK</td>
<td>NAMA KARYAWAN</td>
<td>GAJI POKOK</td>
<td>TUNJANGAN TETAP</td>
<td>TUNJANGAN TIDAK TETAP</td>
<td>TUNJANGAN LAINNYA</td>
</tr>
<?php
foreach($data1 as $item) {
?>
<tr>
<td><?php echo $item['nik']?></td>
<td><?php echo $item['nama_karyawan']?></td>
<td><?php echo $item['gajipokok']?></td>
<td><?php echo $item['tunjangan_tetap']?></td>
<td><?php echo $item['tunjangan_tidak_tetap']?></td>
<td><?php echo $item['tunjangan_lainnya']?></td>
</tr>
<?php } ?>
</table>
