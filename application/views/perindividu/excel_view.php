<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=rekap_perindividu_karyawan.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<br />
Rekap Per Individu Bulan : <strong><?php echo ! empty($tanggal) ? $tanggal : ''; ?></strong>
<br /><br />

<?php echo ! empty($table) ? $table : ''; ?>
