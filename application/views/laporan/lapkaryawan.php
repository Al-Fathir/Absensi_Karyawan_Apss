<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="72sFF3" />

	<title>Print Data Karyawan</title>
</head>

<style type="text/css">

    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      margin: 20px 50px 20px 50px;
    }
    table {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
    }
    ol {
      margin-top: 3px;
      padding-top: 0px;
    }
    .pagetitle {
      font-size: 18px;
      font-weight: bold;
      line-height: 25px;
      text-align: center;
      margin-bottom: 15px;
    }
    .box {
      margin-bottom: 10px;
      width: 600px;
    }
    table.ipdet {
      border-collapse: collapse;
    }
    table.ipdet td {
      border: 1px solid black;
      padding: 0 3px 0 3px;
      width: 80px;
    }
    .materai-box {
      border: 1px solid black;
      font-size: 10px;
      height: 50px;
      margin: 15px 0 15px 0;
      text-align: center;
      width: 50px;
    }
    
    .debugborder1 { border: 1px solid black }
    .debugborder2 { border: 1px solid red }
.form {
	border: 1px solid black;
	text-align: right;
	width: auto;
	float: right;
	font-weight: bold;
	padding: 3px;
}
.textHeader{
    font-size: 14px;
    font-family: Arial, Helvetica, sans-serif;;
}

.imageborder{
   padding:4px;
   border :1px solid black;
   background-color:white;
}

.tableClass {
    padding:0px; 
    border: 1px solid black;
    
    
}
.tableClass th{
  font-weight: bold;
  border: 1px solid black;
  
    
}

.tableClass td{
  padding:  5px;
  border: 1px solid black;
    
}
  </style>
<body onload="print()">
<div class="box">
<table cellpadding="3" cellspacing="0" width="100%">
<tr>
    <td align="center">&nbsp;</td>
    <td align="center"><h3>THE GRAND PALACE HOTEL YOGYAKARTA</h3>Jl. Mangkuyudan No. 32 Yogyakarta 55143<br /> Phone +62 274 414590 . Fax +62 274 417613</td>
</tr>
<tr>
    <td colspan="2">&nbsp; </td>
</tr>
<tr>
    <td colspan="2" style="border-top: 2px black solid;">&nbsp; </td>
</tr>

<tr>
    <td></td>
    <td align="center"><strong class="textHeader"><u>DATA KARYAWAN THE GRAND PALACE HOTEL YOGYAKARTA</u></strong></td>
</tr>

</table>
</div>

<div class="box"> 

  <p>Saya yang bertanda tangan dibawah ini adalah Karyawan The Grand Palace Hotel Yogyakarta</p>
  <strong>IDENTITAS PRIBADI</strong>
  <?php echo ! empty($table_pribadi) ? $table_pribadi : ''; ?>
  <br />
  <strong>DATA KELUARGA</strong>
  <?php echo ! empty($table_keluarga) ? $table_keluarga : ''; ?>
  <br />
  <strong>DATA PENDIDIKAN</strong>
  <?php echo ! empty($table_pendidikan) ? $table_pendidikan : ''; ?>
  <br />
  <strong>DATA PENGALAMAN KERJA</strong>
  <?php echo ! empty($table_pengalamankerja) ? $table_pengalamankerja : ''; ?>
</div>


</body>
</html>
