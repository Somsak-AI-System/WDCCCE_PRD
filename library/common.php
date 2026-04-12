<?php
	require_once("library/config.php");
	require_once("library/sql.php");

	//$dbtype=$db2type;
	//$dbi = sql_connect($db2host, $db2uname, $db2pass, $db2name);

// ------------------------- Function List----------------------->
// 1.GetLabel($labelid,$langid)
// 2.GetTitle($labelid,$langid)
// 3.chklogin($userid,$password)
// 4.chkverifypasswd($userid,$password)
// 5.GetScreen($screenid,$langid)
// 6.GetSecure($screenid,$userid,$langid)
// 7.GetDefaultPage($userid)
// 8.GetUserName($userid)
// 9.null_to_nbsp($string)
// 10.Cm_isBack($pagename,$page)
// 11.Cm_isMore($pagename,$count,$page)
// 12.Is_Date($date)
// 13.Switch_Date($date)
// 14.Ck_Email($email)
// 15.GetCharSet($langid)
// 16.GetScreenName($screenid,$langid) 
// 17.GetLangName($lang,$langid)
// 18.chkvalidxls($filename)
// 19.CheckValidData($data)
// 20.CreateTransNo($cuscd)
// 21.Switch_Date_MySQL($date)
// 21.GetItemCode($itemnm)
// 22.GetCuscd($transno)
// 23.Switch_Date_MySQL_For_SaleIMPORT($date)

//###################################################
Function GetLabel($screenid,$labelid,$langid) {
global $dbi;

	$sql = " select labelname from label where screenid=$screenid and labelid='$labelid' and langid='$langid' ";
	$result = Sql_Query($sql,$dbi);
	list($label) = sql_fetch_array($result, $dbi);
	return $label;
}
//###################################################

//###################################################
Function GetTitle($langid) {
global $dbi;

	if ($langid==1) {
		$sql = " select titlethai from title where id=1 ";
	}elseif ($langid==2) {
		$sql = " select titleeng from title where id=1 ";
	}elseif ($langid==3) {
		$sql = " select titlejapan from title where id=1 ";
	}
	$result = Sql_Query($sql,$dbi);
	list($label) = sql_fetch_array($result, $dbi);
	return $label;
}
//###################################################

//###################################################
function chklogin($userid,$password){
global $dbi;
	$sql = "select userid from user where userid='$userid'";
	$result = Sql_Query($sql,$dbi);

	if (sql_num_rows($result,$dbi) != 0) {
		$sql = "select userid from user where userid='$userid' and password='$password' ";
		$result = Sql_Query($sql,$dbi);
		if (sql_num_rows($result,$dbi) != 0) {
			return "0"; // login valid
		}else{
			return "2"; // password incorrect
		}
	}else{
		return "1"; // user name not found
	}
}
//###################################################

//###################################################
function chkverifypasswd($userid,$password){
global $dbi;
	$sql = " select * from user where userid='$userid' and password='$password' ";
	$result = Sql_Query($sql,$dbi);
	if (sql_num_rows($result,$dbi) != 0) {
		return true;
	}else{
		return false;
	}
}
//###################################################

//###################################################
Function GetScreen($screenid,$langid) {
global $dbi;

	if ($langid==1) {
		$sql = " select scrthainame from screen where screenid=$screenid ";
	}elseif ($langid==2) {
		$sql = " select screngname from screen where screenid=$screenid ";
	}elseif ($langid==3) {
		$sql = " select scrjapname from screen where screenid=$screenid ";
	}
	$result = Sql_Query($sql,$dbi);
	list($label) = sql_fetch_array($result, $dbi);
	return $label;
}
//###################################################

//###################################################
Function GetSecure($screenid,$userid,$langid) {
global $dbi;

	if ($userid!="") {
		$sql = "select * from secure where screenid=$screenid and userid='$userid' and permission='1' ";
		$result = Sql_Query($sql,$dbi);

		if (sql_num_rows($result,$dbi) != 0) {
			$sql = "select filename from screen where screenid=$screenid ";
			$result = Sql_Query($sql,$dbi);
			list($ret) = Sql_Fetch_Array($result,$dbi);
		}else{
			$ret = "permissiondeny.php";
		}
	}else{
		$ret = "permissiondeny.php";
	}
	
	return $ret;
}
//###################################################

//###################################################
Function GetDefaultPage($userid) {
global $dbi;

	$sql = " select screen.filename from screen,secure where screen.screenid=secure.screenid and secure.userid='$userid' and secure.permission='1' ";
	$sql .= " order by screen.sort asc LIMIT 0 , 1 ";
	$result = Sql_Query($sql,$dbi);
	list($ret) = Sql_Fetch_Array($result,$dbi);
	$ret = "firstpage.php";
	return $ret;
}
//###################################################

//###################################################
Function GetUserName($userid) {
global $dbi;

	$sql = " select name from user where userid='$userid' ";
	$result = Sql_Query($sql,$dbi);
	list($ret) = Sql_Fetch_Array($result,$dbi);
	return $ret;
}
//###################################################

//###################################################
Function null_to_nbsp($string) {
	if ($string=="") 
		return "&nbsp;";
	else
		return $string;
}
//###################################################

//###################################################
Function Cm_isBack($pagename,$page){
	global $MAX_MESSAGE_ONSHOW;
	global $MAX_PAGE;
	global $langid;
	if(empty($page))  $page=0;
	$index = floor(($page) / ($MAX_MESSAGE_ONSHOW*$MAX_PAGE)) ;
	if ($index == 0) {
		return "";
	}else {
		$backpage = floor(($page)/($MAX_MESSAGE_ONSHOW*$MAX_PAGE))*($MAX_MESSAGE_ONSHOW*$MAX_PAGE)-$MAX_MESSAGE_ONSHOW ;
		$pos = strpos($pagename,"?");
		if ($pos==""){
			$ref = "<a href='$pagename?param_page=$backpage' class='link'>&lt; &lt; ".GetLabel(999,6,$langid)."</a>&nbsp;";
		}else{
			$split_array = split("\?",$pagename);
			$ref = "<a href='".$split_array[0]."?param_page=$backpage&".$split_array[1]."' class='link'>&lt; &lt; ".GetLabel(999,6,$langid)."</a>&nbsp;";
		}
		return $ref;
	}
}
//###################################################

//###################################################
Function Cm_isMore($pagename,$count,$page){
	global $MAX_MESSAGE_ONSHOW;
	global $MAX_PAGE;
	global $langid;
	if(empty($page))  $page=0;
	

	if ($page==0)
		$maxdata = $MAX_MESSAGE_ONSHOW*$MAX_PAGE;
	else
		$maxdata =  ceil(($page+$MAX_MESSAGE_ONSHOW)/($MAX_MESSAGE_ONSHOW*$MAX_PAGE))*$MAX_PAGE*$MAX_MESSAGE_ONSHOW;

	if ($maxdata >= $count) {
		return "";
	}else {
		$morepage = $maxdata;
		$pos = strpos($pagename,"?");
		if ($pos==""){
			$ref = "&nbsp;<a href='$pagename?param_page=$morepage' class='link'>".GetLabel(999,7,$langid)." &gt; &gt;</a>&nbsp;&nbsp;&nbsp;";
		}else{
			$split_array = split("\?",$pagename);
			$ref = "&nbsp;<a href='".$split_array[0]."?param_page=$morepage&".$split_array[1]."' class='link'>".GetLabel(999,7,$langid)." &gt; &gt;</a>&nbsp;&nbsp;&nbsp;";
		}
		return $ref;
	}
}
//###################################################

//###################################################
Function Is_Date($date){
	if (ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $date) || ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{1,2})", $date) ) {
		return true;
	} else {
		return false;
		//echo "Invalid date format: $date";
	}
}
//###################################################

//###################################################
Function Switch_Date($date){
	$arr_date = split("/",$date);
	
	//if ($arr_date[0]>=12) {
		return $arr_date[1]."/".$arr_date[0]."/".$arr_date[2];
	//}else{
	//	return $date;
	//}
}
//###################################################

//###################################################
Function Ck_Email($email){
	$email = strtolower($email);
	if(ereg("^[0-9a-z_]([-_.]?[0-9a-z])*@[0-9a-z][-.0-9a-z]*\\.[a-z]{2,3}[.]?$", $email, $check)) { 
		$host = substr(strstr($check[0], '@'), 1); 
		if ( getmxrr($host, $validate_email_temp) ){ 
			return "0";
		}
		// THIS WILL CATCH DNSs THAT ARE NOT MX. 
		if( checkdnsrr($host,"ANY")) {
			return "0";
		}
		return "2";
	}else{
		return "1";
	}
}
//###################################################

//###################################################
Function GetCharSet($langid){
global $dbi;

	$sql = " select charset from language where langid='$langid' ";
	$result = Sql_Query($sql,$dbi);
	list($ret) = Sql_Fetch_Array($result,$dbi);
	return $ret;
}
//###################################################

//###################################################
Function GetScreenName($screenid,$langid){
global $dbi;

	if ($langid==1)	 $sql = " select scrthainame from screen where screenid='$screenid' ";
	if ($langid==2)	 $sql = " select screngname from screen where screenid='$screenid' ";
	if ($langid==3)	 $sql = " select scrjapname from screen where screenid='$screenid' ";
	$result = Sql_Query($sql,$dbi);
	list($ret) = Sql_Fetch_Array($result,$dbi);
	return $ret;
}
//###################################################

//###################################################
Function GetLangName($lang,$langid){
global $dbi;

	if ($langid=="") $langid=2;
	if ($lang==1) 	return GetLabel(999,9,$langid);
	if ($lang==2) 	return GetLabel(999,10,$langid);
	if ($lang==3) 	return GetLabel(999,11,$langid);
}
//###################################################

//###################################################
Function chkvalidxls($filename){
	$pos = strrpos($filename, "xls");
	if ($pos === false) { // note: three equal signs
		// not found...
		return	false;
	}else{
		return	true;
	}
}
//###################################################

//###################################################
Function CheckValidData($data,$langid){
	/*
	0 = item name
	1 = sale order qty
	2 = ship plan date
	3 = transport code
	*/
	$msg = "";

	if ($data[0]==""){
		$msg .= "Check ".GetLabel(6,4,$langid)."<br>";
	}

	if (!is_numeric($data[1]) || $data[1]==""){
		$msg .= "Check ".GetLabel(6,6,$langid)."<br>";
	}

	if ($data[2]==""){
		$msg .= "Check ".GetLabel(6,5,$langid)."<br>";
	}

	if ($data[3]==""){
		$msg .= "Check ".GetLabel(6,7,$langid)."<br>";
	}

	if ($msg!="") {
		return GetLabel(6,14,$langid);
	}
}
//###################################################

//###################################################
Function CreateTransNo($cuscd){
	return	$cuscd.date("y").date("m").date("d").date("H").date("i").date("s");
}
//###################################################

//###################################################
Function Switch_Date_MySQL($date){
	$arr_date = split("/",$date);
	return trim($arr_date[2])."/".trim($arr_date[1])."/".trim($arr_date[0]);
}
//###################################################

//###################################################
Function GetItemCode($itemnm){
global $dbi;

	$sql = " select itemcd from itemmst where itemnm='".$itemnm."' ";

	$result = Sql_Query($sql,$dbi);
	list($itemcd) = Sql_Fetch_Array($result,$dbi);

	return	$itemcd;
}
//###################################################

//###################################################
Function GetCuscd($transno){
global $dbi;

	$sql = " select cuscd from soimpmst where transno='".$transno."' ";

	$result = Sql_Query($sql,$dbi);
	list($cuscd) = Sql_Fetch_Array($result,$dbi);

	return	$cuscd;
}
//###################################################

//###################################################
Function Switch_Date_MySQL_For_SaleIMPORT($date){
	$arr_date = split("-",$date);
	return trim($arr_date[2])."/".trim($arr_date[0])."/".trim($arr_date[1]);
}
//###################################################
?>
