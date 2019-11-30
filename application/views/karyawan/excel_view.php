<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Karyawan.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border='1' width="70%">
<tr>
<td>NIK</td>
<td>NAMA KARYAWAN</td>
<td>TEMPAT LAHIR</td>
<td>TANGGAL LAHIR</td>
<td>JENIS KELAMIN</td>
<td>AGAMA</td>
<td>ALAMAT TINGGAL</td>
<td>ALAMAT ASAL</td>
<td>TELEPHONE</td>
<td>STATUS PERKAWINAN</td>
<td>TANGGAL MASUK</td>
<td>STATUS</td>
</tr>

<?php
foreach($data1 as $item) {
?>
<tr>
<td><?php echo $item['nik']?></td>
<td><?php echo $item['nama_karyawan']?></td>
<td><?php echo $item['tempat_lahir']?></td>
<td><?php echo $item['tgl_lahir']?></td>
<td><?php echo $item['jenis_kelamin']?></td>
<td><?php echo $item['agama']?></td>
<td><?php echo $item['alamat_tinggal']?></td>
<td><?php echo $item['alamat_asal']?></td>
<td><?php echo $item['telepon']?></td>
<td><?php echo $item['status_perkawinan']?></td>
<td><?php echo $item['tgl_masuk']?></td>
<td><?php echo $item['status_aktif']?></td>
</tr>
<?php } ?>
</table>
