<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=rekap_karyawan_terbaik.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<br />
Rekap Karyawan Terbaik : <strong><?php echo ! empty($tanggal) ? $tanggal : ''; ?></strong>
<br />
<?php echo ! empty($departemen) ? 'Departemen : '.$departemen : ''; ?>
<br /><br />

<?php echo ! empty($table) ? $table : ''; ?>
