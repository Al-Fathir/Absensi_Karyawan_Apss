<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Pengalaman_Kerja.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border='1' width="70%">
<tr>
<td>NIK</td>
<td>NAMA KARYAWAN</td>
<td>NAMA PERUSAHAAN</td>
<td>ALAMAT PERUSAHAAN</td>
<td>TAHUN MULAI KERJA</td>
<td>TAHUN SELESAI KERJA</td>
<td>JABATAN</td>
<td>ALASAN BERHENTI</td>
</tr>
<?php
foreach($data1 as $item) {
?>
<tr>
<td><?php echo $item['nik']?></td>
<td><?php echo $item['nama_karyawan']?></td>
<td><?php echo $item['nama_perusahaan']?></td>
<td><?php echo $item['alamat_perusahaan']?></td>
<td><?php echo $item['tahun_mulai_kerja']?></td>
<td><?php echo $item['tahun_selesai_kerja']?></td>
<td><?php echo $item['jabatan']?></td>
<td><?php echo $item['alasan_berhenti']?></td>
</tr>
<?php } ?>
</table>
