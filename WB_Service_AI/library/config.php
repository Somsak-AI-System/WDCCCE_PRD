<?php
	/* #################### Database Sql Server path #################### */
	$db1type = "Mssql";
	/*$db1host = "CHOKHON\SQL2008";
	$db1uname = "sa";
	$db1pass = "123456";
	$db1name = "AMCPTEST";*/
	$db1host = "192.168.0.191\sql2008";
	//$db1host = "10.17.81.30";
	$db1uname = "sa";
	$db1pass = "123456";
	//$db1pass = "S@p0sswrd";
	$db1name = "SCCC";
	$max_rows = 25;
	$max_page_item = 4;
	$max_rows_board = 9;
	$max_rows_vaja = 3;
	$main_page = 30;
	$detail_page = 10;
	$delay_vaja = 5;
	$path = "ACMP";
	$refresh=10;	
	$pcnm= GetHostByaddr($_SERVER['REMOTE_ADDR']);
	
	/* #################### Database Sql Server path #################### */
/*	$db1type = "Mssql";
	$db1host = "192.168.0.1\sql2008";
	$db1uname = "sa";
	$db1pass = "123456";
	$db1name = "ACMPTEST";
	$max_rows = 25;
	$path = "ACMP";*/
	/* #################### Database MySQL path #################### */
	$db2type = "MySQL";
	$db2host = "localhost";
	$db2uname = "root";
	$db2pass = "";
	$db2pass = "";
	$db2name = "db_medt";
	
?>
