<?
$Upload_Dir1 = $_REQUEST["path1"]; //กำหนดว่าจะให้ copy ไฟล์ที่มาจากเครื่องผู้ใช้ไปที่ใด ระบุที่นี่ได้ครับ
$Upload_Dir2 = $_REQUEST["path2"]; //กำหนดว่าจะให้ copy ไฟล์ที่มาจากเครื่องผู้ใช้ไปที่ใด ระบุที่นี่ได้ครับ
$Upload_Dir3 = $_REQUEST["path3"]; //กำหนดว่าจะให้ copy ไฟล์ที่มาจากเครื่องผู้ใช้ไปที่ใด ระบุที่นี่ได้ครับ
$Upload_Dir4 = $_REQUEST["path4"]; //กำหนดว่าจะให้ copy ไฟล์ที่มาจากเครื่องผู้ใช้ไปที่ใด ระบุที่นี่ได้ครับ
$Upload_Dir5 = $_REQUEST["path5"]; //กำหนดว่าจะให้ copy ไฟล์ที่มาจากเครื่องผู้ใช้ไปที่ใด ระบุที่นี่ได้ครับ
$Upload_Dir6 = $_REQUEST["path6"]; //กำหนดว่าจะให้ copy ไฟล์ที่มาจากเครื่องผู้ใช้ไปที่ใด ระบุที่นี่ได้ครับ
$Upload_Dir7 = $_REQUEST["path7"]; //กำหนดว่าจะให้ copy ไฟล์ที่มาจากเครื่องผู้ใช้ไปที่ใด ระบุที่นี่ได้ครับ
$Upload_Dir8 = $_REQUEST["path8"]; //กำหนดว่าจะให้ copy ไฟล์ที่มาจากเครื่องผู้ใช้ไปที่ใด ระบุที่นี่ได้ครับ
$Upload_Dir9 = $_REQUEST["path9"]; //กำหนดว่าจะให้ copy ไฟล์ที่มาจากเครื่องผู้ใช้ไปที่ใด ระบุที่นี่ได้ครับ
$Upload_Dir10 = $_REQUEST["path10"]; //กำหนดว่าจะให้ copy ไฟล์ที่มาจากเครื่องผู้ใช้ไปที่ใด ระบุที่นี่ได้ครับ

if($_FILES['userfile1']){
	if (copy($_FILES['userfile1']['tmp_name'],$Upload_Dir1."/".$_FILES['userfile1']['name'])) { //ทำการ copy ไฟล์มาที่ Server
		echo "<br>"."ไฟล์1 Upload เรียบร้อย";
	} else {
		echo "<br>"."ไฟล์1 Upload มีปัญหา";
	}
}
if($_FILES['userfile2']){
	if (copy($_FILES['userfile2']['tmp_name'],$Upload_Dir2."/".$_FILES['userfile2']['name'])) { //ทำการ copy ไฟล์มาที่ Server
		echo "<br>"."ไฟล์2 Upload เรียบร้อย";
	} else {
		echo "<br>"."ไฟล์2 Upload มีปัญหา";
	}
}
if($_FILES['userfile3']){
	if (copy($_FILES['userfile3']['tmp_name'],$Upload_Dir3."/".$_FILES['userfile3']['name'])) { //ทำการ copy ไฟล์มาที่ Server
		echo "<br>"."ไฟล์3 Upload เรียบร้อย";
	} else {
		echo "<br>"."ไฟล์3 Upload มีปัญหา";
	}
}
if($_FILES['userfile4']){
	if (copy($_FILES['userfile4']['tmp_name'],$Upload_Dir4."/".$_FILES['userfile4']['name'])) { //ทำการ copy ไฟล์มาที่ Server
		echo "<br>"."ไฟล์4 Upload เรียบร้อย";
	} else {
		echo "<br>"."ไฟล์4 Upload มีปัญหา";
	}
}
if($_FILES['userfile5']){
	if (copy($_FILES['userfile5']['tmp_name'],$Upload_Dir5."/".$_FILES['userfile5']['name'])) { //ทำการ copy ไฟล์มาที่ Server
		echo "<br>"."ไฟล์5 Upload เรียบร้อย";
	} else {
		echo "<br>"."ไฟล์5 Upload มีปัญหา";
	}
}
if($_FILES['userfile6']){
	if (copy($_FILES['userfile6']['tmp_name'],$Upload_Dir6."/".$_FILES['userfile6']['name'])) { //ทำการ copy ไฟล์มาที่ Server
		echo "<br>"."ไฟล์6 Upload เรียบร้อย";
	} else {
		echo "<br>"."ไฟล์6 Upload มีปัญหา";
	}
}
if($_FILES['userfile7']){
	if (copy($_FILES['userfile7']['tmp_name'],$Upload_Dir7."/".$_FILES['userfile7']['name'])) { //ทำการ copy ไฟล์มาที่ Server
		echo "<br>"."ไฟล์7 Upload เรียบร้อย";
	} else {
		echo "<br>"."ไฟล์7 Upload มีปัญหา";
	}
}
if($_FILES['userfile8']){
	if (copy($_FILES['userfile8']['tmp_name'],$Upload_Dir8."/".$_FILES['userfile8']['name'])) { //ทำการ copy ไฟล์มาที่ Server
		echo "<br>"."ไฟล์8 Upload เรียบร้อย";
	} else {
		echo "<br>"."ไฟล์8 Upload มีปัญหา";
	}
}
if($_FILES['userfile9']){
	if (copy($_FILES['userfile9']['tmp_name'],$Upload_Dir9."/".$_FILES['userfile9']['name'])) { //ทำการ copy ไฟล์มาที่ Server
		echo "<br>"."ไฟล์9 Upload เรียบร้อย";
	} else {
		echo "<br>"."ไฟล์9 Upload มีปัญหา";
	}
}
if($_FILES['userfile10']){
	if (copy($_FILES['userfile10']['tmp_name'],$Upload_Dir10."/".$_FILES['userfile10']['name'])) { //ทำการ copy ไฟล์มาที่ Server
		echo "<br>"."ไฟล์10 Upload เรียบร้อย";
	} else {
		echo "<br>"."ไฟล์10 Upload มีปัญหา";
	}
}
?>