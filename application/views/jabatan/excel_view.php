<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Jabatan.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border='1' width="70%">
<tr>
<td>NIK</td>
<td>NAMA KARYAWAN</td>
<td>GOLONGAN</td>
<td>DEPARTEMEN</td>
<td>JABATAN</td>
<td>MASA KERJA</td>
</tr>
<?php
foreach($data1 as $item) {
?>
<tr>
<td><?php echo $item['nik']?></td>
<td><?php echo $item['nama_karyawan']?></td>
<td><?php echo $item['golongan']?></td>
<td><?php echo $item['nama_departemen']?></td>
<td><?php echo $item['jabatan']?></td>
<td><?php echo $item['masa_kerja']?></td>
</tr>
<?php } ?>
</table>
