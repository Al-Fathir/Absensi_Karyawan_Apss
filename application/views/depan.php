<style>
body{
font-family:Tahoma;
font-size:12px;
}
#chart{
margin:0px auto;
width:800px;
float:none;
}
#soal{
margin:0px auto;
width:500px;
float:none;
font-size:14px;
padding:5px;
}
#jwb{
margin:0px auto;
padding:5px;
width:500px;
float:none;
font-size:12px;
}
.tombol{
background-color:#FF9900;
padding:5px 20px 5px 20px;
border:1px dashed #000000;
}
.tombol:hover{
background-color:#FF0000;
cursor:pointer;
}
</style>
<div id="chart">
<?php
echo $this->graph->render();
?>
</div>