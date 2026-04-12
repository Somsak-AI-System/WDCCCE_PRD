<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include("config.inc.php");
include("library/dbconfig.php");
require_once("library/generate_MYSQL.php");
require_once("library/Quotation.php");
require_once("library/general.php");
include_once("library/log.php");
include_once("library/myLibrary.php");
global $generate,$current_user,$root_directory,$root_directory,$site_URL;

$url_webservice = "http://localhost:11030/MJDPDataControl/" ;
$method = "GetPrintQuotation";
$directory ="export/Quotation/";
$url = $url_webservice.$method;
$url_path = "http://crm.mjd.co.th/";
$Quotation = new libQuotation($dbconfig);
$General = new libGeneral();
$Log = new log();
$Log->_logname ="logs/quotation_print";


	$productid = $_REQUEST["id"];
	$userid= $_REQUEST["userid"];
	$mobile= $_REQUEST["mobile"];
	$a_product = $Quotation->get_product($productid);
	//alert($a_product);exit();
	if(empty($a_product)){
		$a_reponse["msg"] ="Try agian";
		$a_reponse["url"] = "index.php";
		$a_reponse["status"] = false;
		$a_reponse["error"] =  "Empty Data";
		$a_reponse["result"] = "";
		$Quotation->set_transaction_print($productid,$userid,$a_reponse["status"],$a_reponse["error"]);
		echo json_encode($a_reponse);
		exit();
	}

	/*$a_sale = $Quotation->get_salename($userid);
	$salename = "";
	if(!empty($a_sale)){
		$salename = $a_sale[0]["first_name"] ." ". $a_sale[0]["last_name"] ;
	}*/
	

	//echo "<pre>";print_r($a_product);echo "</pre>";
	$branchid= $a_product[0]["branchid"];
	//echo $branchcode;
	//exit();



	$a_branch = $Quotation->get_branch($branchid);
	//alert($a_branch);exit();
	//echo "<pre>";print_r($a_product);echo "</pre>";
	//exit();
	//$url = "http://192.168.0.168:8080";
	//$url = "http://192.168.0.174:8080";
	$ipaddress = $a_branch[0]["ipaddress"];
	$printername = $a_branch[0]["printername"];
	$path = $root_directory.$directory;
	//$ipaddress = "http://192.168.0.168";
	//$port = "8080";
	if($printername=="" && $mobile=="1" ){
		$a_reponse["msg"] ="Try agian";
		$a_reponse["url"] = "index.php";
		$a_reponse["status"] = false;
		$a_reponse["error"] =  "Not Set Printer Name";
		$a_reponse["result"] = "";
		$Quotation->set_transaction_print($productid,$userid,$a_reponse["status"],$a_reponse["error"]);
		echo json_encode($a_reponse);
		exit();
	}

	
	
	$a_param["Value1"] = $a_product[0]["projectcode"];//projectcode
	$a_param["Value2"] =$a_product[0]["sbucode"];
	$a_param["Value3"] = $a_product[0]["producttypecode"];
	$a_param["Value4"] = $a_product[0]["productcode"];
	$a_param["Value5"] = $userid;
	$a_param["Value6"] =  @$printername;
	$a_param["Value7"] =  @$ipaddress;

	$a_param["Value8"] =  ($mobile=="1") ? "Print":"PDF";//Type Print
	
	//$path = "D:/Folder";
	$a_param["Value9"] =  $path;
	$a_param["UserName"] =  $userid;
	$a_param["ComputerName"] = $General->get_server_name();
	
	//alert($a_param);

	//echo "<pre>";print_r($a_param);echo"</pre>";exit();

	

	


		$a_curl = $General->curl($url,$a_param,"json");
		//alert($a_curl);
		/*$a_curl["status"]=false;
		$a_curl["result"]= array("Message"=>"Quotation_25590708_175109.pdf");
		$a_curl["error"]="You have an error in your SQL syntax; check the manual that corresponds
 to your MySQL server version for the right syntax to use near 'NULL' at line 1";
		$a_curl["url"]="";*/
		$Log->write_log("url =>".$url);
		$Log->write_log("parameter =>".json_encode($a_param));
		$Log->write_log("sparameter =>".$s_param);
		$Log->write_log("response =>".json_encode($a_curl));
		
		
		if($a_curl["status"]==true || $a_curl["error"] =="" ){
			$filename = $a_curl["result"]["Message"];
		
				$a_reponse["msg"] ="Print Complete";
				$a_reponse["error"] = "";
				$a_reponse["status"] = true;
				$a_reponse["result"] = $a_curl["result"];
				$a_reponse["url"] = $url_path .$directory.$filename;
			
			
			


		}else{
			$a_reponse["msg"] ="Try agian";
			$a_reponse["error"] =$a_curl["error"] ;
			$a_reponse["status"] = false;
			$a_reponse["result"] =  $a_curl["result"];
		}
		//$a_reponse["url"] = "index.php";
		$Quotation->set_transaction_print($productid,$userid,$a_reponse["status"],$a_reponse["error"]);
		
		//alert($a_curl);
		echo json_encode($a_reponse);


		//echo $param;exit;


?>