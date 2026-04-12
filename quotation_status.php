<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once("config.inc.php");
	require_once("library/dbconfig.php");
	include_once("library/myLibrary_mysqli.php");
	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;
	//echo "<pre>"; print_r($myLibrary_mysqli); echo "</pre>"; exit;
	$date=date('d-m-Y');

	$crmid = $_REQUEST["crmid"];
	$quotationstatus = $_REQUEST["quotationstatus"];
	$level = $_REQUEST["level"];

	require_once('modules/Quotes/Quotes.php');
	require_once('modules/Users/Users.php');
	require_once('modules/PriceList/PriceList.php');
	require_once('include/utils/UserInfoUtil.php');

    $_REQUEST["ajxaction"] = "DETAILVIEW";

	$current_user = new Users();
	$result = $current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id']);
	global $current_user;

	$assignto = '';
	$sql = "insert into tbt_quotes_log(crmid,assignto,quotesstatus,adduser,adddate)
			values('".$crmid."','".$assignto."','".$quotationstatus."','".$_SESSION['authenticated_user_id']."','".date('Y-m-d H:i:s')."');";
	$myLibrary_mysqli->Query($sql);


	$sql_quotes = "SELECT aicrm_quotes.pricetype FROM aicrm_quotes
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
				INNER JOIN aicrm_quotescf ON aicrm_quotes.quoteid = aicrm_quotescf.quoteid
				WHERE
					aicrm_crmentity.deleted = 0 
					AND aicrm_quotes.quoteid = '".$crmid."'";
   $a_data_quotes = $myLibrary_mysqli->select($sql_quotes);
   $pricetype = $a_data_quotes[0]['pricetype'];


   $sql = "insert into tbt_quotes_approve (id,crmid,userid,username,level,appstatus)	
					select 0,'".$crmid."',id,user_name,1,0
					from aicrm_users
					where aicrm_users = '".$_SESSION['authenticated_user_id']."'";
	$myLibrary_mysqli->Query($sql);
	
	$a_usermail = array();
	if($quotationstatus == 'Approve' || $quotationstatus == 'Cancel_Approve'){
		
		if($quotationstatus == 'Approve'){
			$msg = "Approve";
			$stt = "อนุมัติใบเสนอราคา";
			$appstt = "1";
		}else{
			$msg = "Cancel Approve";
			$stt = "ไม่อนุมัติใบเสนอราคา";
			$appstt = "2";
		}
		$quotes_focus = new Quotes();
		$quotes_focus->retrieve_entity_info($crmid,"Quotes");
		$sql = "update tbt_quotes_approve 
					set appstatus='".$appstt."'
					,upuser = '".$_SESSION['authenticated_user_id']."'
					,updatedate = '".date('Y-m-d H:i:s')."'		
					where crmid='".$crmid."'				
					and userid = '".$_SESSION['authenticated_user_id']."'
					and level = '".$level."' ";
		$myLibrary_mysqli->Query($sql); //ปิดไปก่อน
		
		$approveflag = 0;
		if($quotationstatus == 'Approve'){
			$sql_user_approve = "select count(id) as countid,crmid
			from tbt_quotes_approve
			where crmid='".$crmid."'
			and appstatus = 0
			and level != 4
			group by crmid ";
			$a_user_approve = $myLibrary_mysqli->select($sql_user_approve);
			// echo $a_user_approve[0]["countid"]; exit;
			if($a_user_approve[0]["countid"] == 0 ){
			
				//เงื่อนไข Approve ปกติ
				$sql = "select count(id) as countid,crmid
				from tbt_quotes_approve
					where crmid='".$crmid."'
					and appstatus = 0
					group by crmid ";
				$a_data = $myLibrary_mysqli->select($sql);
					
				if($a_data[0]["countid"] == 0 ){
					$quotes_focus->column_fields['quotation_status'] = $stt;
					$approveflag = 1;
				}
				
			}
				

			
		}else if($quotationstatus == 'Cancel_Approve'){			
			$quotes_focus->column_fields['quotation_status'] = $stt;

			//เหตุผลการไม่อนุมัติใบเสนอราคา
			if($_REQUEST["quota_notapprove"] !=''){
				$quotes_focus->column_fields['quota_notapprove'] = $_REQUEST["quota_notapprove"];
			}
					
			$approveflag = 1;
			
			$sql = " select userid
					from tbt_quotes_approve
					where crmid='".$crmid."'
					and appstatus != 0
					order by level,username	 ";
			$a_usermail = $myLibrary_mysqli->select($sql);
		}
				
		$assignto = get_update_assignto($quotationstatus,$crmid,$approveflag);
		
		if($assignto!=""){
			// $quotes_focus->column_fields['assigned_user_id'] =$assignto;
		}

		$quotes_focus->id = $crmid;
		$quotes_focus->mode = "edit";
		$quotes_focus->save("Quotes");

        if($quotationstatus == 'Approve' && $level == 4){
            $sql = "SELECT aicrm_crmentity.smownerid AS userid  FROM aicrm_quotes
INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
WHERE
	aicrm_quotes.quoteid='".$crmid."'";
            $a_usermail = $myLibrary_mysqli->select($sql);
            $mail_status = send_mail_quotes($quotes_focus,$a_usermail);
        }else{
            $mail_status = send_mail_quotes($quotes_focus,$a_usermail);
        }

        $a_reponse["status"] = true;
		$a_reponse["error"] = "" ;
		$a_reponse["msg"] = $msg. " Complete" ;
		$a_reponse["result"] = "";
        $a_reponse["mail_status"] = '';
		$a_reponse["url"] = "index.php";
        $a_reponse["assignto"] = $assignto;
		echo json_encode($a_reponse);

	}else{
		if($quotationstatus=="Request_Approve"){
			$msg = "Request Approve";
			$stt = "ขออนุมัติใบเสนอราคา";
		}else if($quotationstatus == 'Cancel_Quotation'){
			$msg = "Cancel Quotation";
			$stt = "ยกเลิกใบเสนอราคา";
		}else if($quotationstatus=="Complete"){
			$msg = "Complete";
			$stt = "ปิดการขาย";			
		}
	
		$quotes_focus = new Quotes();
		$quotes_focus->retrieve_entity_info($crmid,"Quotes");

		if($quotationstatus=="Request_Approve"){
			//Approve Level 1
			
			$approvelevel1 = $quotes_focus->column_fields['approve_level1'];
			$a_appr_1 = explode(' |##| ', $approvelevel1);
			if(!empty($a_appr_1)){
				$sql = "
					insert into tbt_quotes_approve (id,crmid,userid,username,level,appstatus)	
					select 0,'".$crmid."',id,user_name,1,0
					from aicrm_users
					where CONCAT(first_name,' ',last_name) in ('".implode("','", $a_appr_1)."')";
				$myLibrary_mysqli->Query($sql);
			}
			
			//Approve Level 2
			$approvelevel2 = $quotes_focus->column_fields['approve_level2'];
			$a_appr_2 = explode(' |##| ', $approvelevel2);
			if(!empty($a_appr_2)){
				$sql = "
					insert into tbt_quotes_approve (id,crmid,userid,username,level,appstatus)
					select 0,'".$crmid."',id,user_name,2,0
					from aicrm_users
					where CONCAT(first_name,' ',last_name) in ('".implode("','", $a_appr_2)."') ";
				$myLibrary_mysqli->Query($sql);
			}
			
			
			//Approve Level 3
			$approvelevel3 = $quotes_focus->column_fields['approve_level3'];
			$a_appr_3 = explode(' |##| ', $approvelevel3);
			if(!empty($a_appr_3)){
				$sql = "
					insert into tbt_quotes_approve (id,crmid,userid,username,level,appstatus)
					select 0,'".$crmid."',id,user_name,3,0
					from aicrm_users
					where CONCAT(first_name,' ',last_name) in ('".implode("','", $a_appr_3)."') ";
				$myLibrary_mysqli->Query($sql);
			}
			
			
			//Approve Level 4
			$approvelevel4 = $quotes_focus->column_fields['approve_level4'];
			$a_appr_4 = explode(' |##| ', $approvelevel4);
			if(!empty($a_appr_4)){
				$sql = "
					insert into tbt_quotes_approve (id,crmid,userid,username,level,appstatus)
					select 0,'".$crmid."',id,user_name,4,0
					from aicrm_users
					where CONCAT(first_name,' ',last_name) in ('".implode("','", $a_appr_4)."') ";
				$myLibrary_mysqli->Query($sql);
			}
			
			$assignto = get_update_assignto($quotationstatus,$crmid);
		
		}else if($quotationstatus=="Cancel_Quotation"){
			//เหตุผลการยกเลิกใบเสนอราคา
			if($_REQUEST["quota_cancel"] !=''){
				$quotes_focus->column_fields['quota_cancel'] = $_REQUEST["quota_cancel"];
			}
			$a_reponse["msg"] = '<center>ยกเลิกใบเสนอราคาสำเร็จ</center>';	
		}else if($quotationstatus=="Complete"){
			$a_reponse["msg"] = '<center>ปิดการขายสำเร็จ</center>';
		}

        //echo $assignto;
		$quotes_focus->column_fields['quotation_status'] = $stt;
		if($assignto!=""){
			// $quotes_focus->column_fields['assigned_user_id'] =$assignto;
		}
		$quotes_focus->id = $crmid;
		$quotes_focus->mode = "edit";
		$quotes_focus->save("Quotes");
		
		$mail_status = send_mail_quotes($quotes_focus,$a_usermail);
		$a_reponse["status"] = true;
		$a_reponse["error"] = "" ;
		if($quotationstatus!="Complete"){
			$a_reponse["msg"] = $msg. " Complete" ;
		}
		$a_reponse["result"] = "";
		$a_reponse["url"] = "index.php";
		echo json_encode($a_reponse);
	}	
	
function get_update_assignto($quotationstatus="",$crmid,$approveflag=0)
{
	global $myLibrary_mysqli;
	if(($approveflag==1 && $quotationstatus == 'Approve') || $quotationstatus == 'Cancel_Approve'){
		$sql = "select smcreatorid as userid
			from aicrm_crmentity
			where crmid='".$crmid."'
			limit 1	";
	}else{
		$sql = "select userid
			from tbt_quotes_approve
			where crmid='".$crmid."'
			and appstatus = 0
			order by level,username
			limit 1	";
	}
	//echo $sql;
	$a_data = $myLibrary_mysqli->select($sql);
	//echo "<pre>"; print_r($a_data); echo "</pre>"; exit;
	return $a_data[0]["userid"];

}


function send_mail_quotes($focus,$a_usermail)
{
	require_once("library/genarate.inc.php");
	//require_once("library/dbconfig.php");
	global $dbconfig,$genarate,$site_URL,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID;
	$genarate = new genarate($dbconfig ,"DB");

	require_once('include/email_alert/email_alert.php');
	$email_body =GetEmail("Quotes",'header_alert.jpg',$focus->id,"quoteid");
	
	$type = 'เรื่อง';

	$status = $focus->column_fields['quotation_status'];
	//echo $status;
	if($focus->column_fields['quotation_status']=="ขออนุมัติใบเสนอราคา"){
		$subject = "Request for Quotation Approval - Quote No. [".$focus->column_fields['quote_no']."] เรื่อง : ".$focus->column_fields['quote_name'];
		$Dear ="Dear User,<br>You have a new quotation. Please take a moment to review and approve in this quote.<br><br>";
	}else{
		$subject = "Quote No. [".$focus->column_fields['quote_no']."] เรื่อง : ".$focus->column_fields['quote_name'];
		if($focus->column_fields['quotation_status']=="เปิดใบเสนอราคา"){
			$Dear .="Dear User,<br>Your Quotation has been created.<br><br>";
		}elseif($focus->column_fields['quotation_status']=="อนุมัติใบเสนอราคา"){
			$Dear .="Dear User,<br>Your Quotation has been approved.<br><br>";
		}elseif($focus->column_fields['quotation_status']=="ไม่อนุมัติใบเสนอราคา"){
			$Dear .="Dear User,<br>Your Quotation has been rejected.<br><br>";
		}elseif($focus->column_fields['quotation_status']=="ยกเลิกใบเสนอราคา"){
			$Dear .="Dear User,<br>Your Quotation has been cancelled.<br><br>";
		}
	}
	

	//send mail to the assigned to user and the parent to whom this ticket is assigned
	require_once('modules/Emails/mail.php');
	$a_mail[] = getUserEmailId('id',$focus->column_fields['assigned_user_id']);
	if(!empty($a_usermail)){
		foreach ($a_usermail as $k => $v){
			$userid = $v["userid"];
			$a_mail[] = getUserEmailId('id',$userid);
		}
	}
	
	/*if($status=="ปิดการขาย" || $status=="ไม่อนุมัติใบเสนอราคา"){
		$user_request_1 = $focus->column_fields['quota1'];//Username
		$user_request_2 = $focus->column_fields['quota2'];//Username

		if($user_request_1!=""){
			$a_mail[] = getUserEmailId('user_name',$user_request_1);
		}
		if($user_request_2!=""){
			$a_mail[] = getUserEmailId('user_name',$user_request_2);
		}
	}*/
	
	$a_mail = array_keys(array_flip($a_mail));
	$user_emailid = implode(",", $a_mail);
	$mail_status = send_mail('Quotes',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$Dear.$email_body);
	return 	$user_emailid;
}
$sql_update = "UPDATE aicrm_quotes SET pricetype='".$pricetype."' WHERE quoteid = '".$crmid."'";
$myLibrary_mysqli->Query($sql_update);
// echo  $pricetype; exit;

function get_AccountName($accountid){
	global $myLibrary_mysqli;
	if($accountid != ''){
		$sql = "select accountname
			from aicrm_account
			where accountid='".$accountid."'
			limit 1	";
		$a_data = $myLibrary_mysqli->select($sql);
		$accountname = $a_data[0]["accountname"];
	}else{
		$accountname = '';
	}
	return $accountname ;
}
?>