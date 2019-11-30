<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Pendidikan.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border='1' width="70%">
<tr>
<td>NIK</td>
<td>NAMA KARYAWAN</td>
<td>JENJANG</td>
<td>NAMA SEKOLAH</td>
<td>JURUSAN</td>
<td>TANGGAL MASUK</td>
<td>TANGGAL LULUS</td>
<td>NO IJAZAH</td>
</tr>

<?php
foreach($data1 as $item) {
?>
<tr>
<td><?php echo $item['nik']?></td>
<td><?php echo $item['nama_karyawan']?></td>
<td><?php echo $item['status_pendidikan']?></td>
<td><?php echo $item['nama_sekolah']?></td>
<td><?php echo $item['jurusan']?></td>
<td><?php echo $item['tahun_masuk']?></td>
<td><?php echo $item['tahun_lulus']?></td>
<td><?php echo $item['no_ijazah']?></td>
</tr>
<?php } ?>
</table>
