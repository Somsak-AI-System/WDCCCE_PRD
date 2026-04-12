<?
/*$pattern = '/^senawecare.com/';
if(preg_match($pattern, $_SERVER['HTTP_HOST'])) {*/
	//$config['url'] = "http://crm.sena-it.com:8080";
	$config['url'] = "http://www.senawecare.com:8080";
/*}else{
	$config['url'] = "http://".$_SERVER['HTTP_HOST'].":8080";
	//เพิ่ม sub if > ตรวจสอบ senawecare.com อีกที เป็น localhost port 8080
}*/

?>
<script>
	//window.open('http://127.0.0.1:8080/birt-viewer/frameset?__report=sena_servicerequest-1.rptdesign&__showtitle=false&servicerequestid=1219177', "New window", "height=200,width=200");
	//window.location.replace("http://127.0.0.1:8080/birt-viewer/frameset?__report=sena_servicerequest-1.rptdesign&__showtitle=false&servicerequestid=<?=$_REQUEST['aicrm']?>");
	window.location.replace("<?php echo $config['url'];?>/birt-viewer/frameset?__report=sena_servicerequest-1.rptdesign&__showtitle=false&servicerequestid=<?=$_REQUEST['aicrm']?>");
</script>