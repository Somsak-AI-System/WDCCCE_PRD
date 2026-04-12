<?php
	$con = mysql_connect("localhost","root","");
	mysql_query("SET character_set_results=utf8");
	mysql_query("SET character_set_client=utf8");
	mysql_query("SET character_set_connection=utf8");
	mysql_query("SET NAMES UTF8");
	mysql_select_db("bmmt_new", $con);
?>

