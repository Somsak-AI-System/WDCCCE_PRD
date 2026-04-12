<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once 'excel_reader2.php';
$data = new Spreadsheet_Excel_Reader("product.xls");
?>
<html>
<head>
<style>
table.excel {
	border-style:ridge;
	border-width:1;
	border-collapse:collapse;
	font-family:sans-serif;
	font-size:12px;
}
table.excel thead th, table.excel tbody th {
	background:#CCCCCC;
	border-style:ridge;
	border-width:1;
	text-align: center;
	vertical-align:bottom;
}
table.excel tbody th {
	text-align:center;
	width:20px;
}
table.excel tbody td {
	vertical-align:bottom;
}
table.excel tbody td {
    padding: 0 3px;
	border: 1px solid #EEEEEE;
}
</style>
</head>

<body>
<?php
echo $data->dump(true,true); 
//print_r($data);
//echo $data->val(4,1);
$dd =array();
for($i=3;$i<10000;$i++){
	if(trim($data->val($i,1))!=""){
		//echo ($i-2)."=>".trim($data->val($i,1))."<br>";
		$dd[($i-3)][0]=$data->val($i,1);
		$dd[($i-3)][1]=$data->val($i,2);
		//echo  $dd[][0]." ".$dd[][1]."<br>";
	}else{
		break;
	}
}
?>
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <?
  	  //print_r($dd);
	  for($k=0;$k<count($dd);$k++){
  ?>
  <tr>
    <td>1<?=$dd[$k][0]?></td>
    <td>2<?=$dd[$k][1]?></td>
  </tr>
<?
}
?>  
</table>

</body>
</html>
