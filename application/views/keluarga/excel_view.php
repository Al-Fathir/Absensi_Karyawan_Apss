<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Keluarga.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border='1' width="70%">
<tr>
<td>NIK</td>
<td>NAMA KARYAWAN</td>
<td>NAMA KELUARGA</td>
<td>TEMPAT LAHIR</td>
<td>TANGGAL LAHIR</td>
<td>JENIS KELAMIN</td>
<td>STATUS</td>
</tr>
<?php
foreach($data1 as $item) {
?>
<tr>
<td><?php echo $item['nik']?></td>
<td><?php echo $item['nama_karyawan']?></td>
<td><?php echo $item['nama']?></td>
<td><?php echo $item['tempat_lahir']?></td>
<td><?php echo $item['tgl_lahir']?></td>
<td><?php echo $item['jenis_kelamin']?></td>
<td><?php echo $item['status']?></td>
</tr>
<?php } ?>
</table>
