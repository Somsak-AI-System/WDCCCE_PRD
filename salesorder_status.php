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
	$salesorderstatus = $_REQUEST["salesorderstatus"];
	$level = $_REQUEST["level"];

	require_once('modules/Salesorder/Salesorder.php');
	require_once('modules/Users/Users.php');
	require_once('include/utils/UserInfoUtil.php');

    $_REQUEST["ajxaction"] = "DETAILVIEW";

	$current_user = new Users();
	$result = $current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id']);
	global $current_user;

	$assignto = '';
	$sql = "insert into tbt_salesorder_log(crmid,assignto,salesorderstatus,adduser,adddate)
			values('".$crmid."','".$assignto."','".$salesorderstatus."','".$_SESSION['authenticated_user_id']."','".date('Y-m-d H:i:s')."');";
	$myLibrary_mysqli->Query($sql);
	
	$a_usermail = array();
	if($salesorderstatus == 'Approve' || $salesorderstatus == 'Cancel_Approve'){
		
		if($salesorderstatus == 'Approve'){
			$msg = "Approve";
			$stt = "อนุมัติใบขาย";
			$appstt = "1";
		}else{
			$msg = "Cancel Approve";
			$stt = "ไม่อนุมัติใบขาย";
			$appstt = "2";
		}
		$salesorder_focus = new Salesorder();
		$salesorder_focus->retrieve_entity_info($crmid,"Salesorder");
		$sql = "update tbt_salesorder_approve 
					set appstatus='".$appstt."'
					,upuser = '".$_SESSION['authenticated_user_id']."'
					,updatedate = '".date('Y-m-d H:i:s')."'		
					where crmid='".$crmid."'				
					and userid = '".$_SESSION['authenticated_user_id']."'
					and level = '".$level."' ";
		$myLibrary_mysqli->Query($sql);
		
		$approveflag = 0;
		if($salesorderstatus == 'Approve'){
			$sql = "select count(id) as countid,crmid
			from tbt_salesorder_approve
			where crmid='".$crmid."'
			and appstatus = 0
			group by crmid ";
			$a_data = $myLibrary_mysqli->select($sql);
			
			if($a_data[0]["countid"] == 0 ){
				$salesorder_focus->column_fields['salesorder_status'] = $stt;
				$approveflag = 1;
			}	
		}else if($salesorderstatus == 'Cancel_Approve'){			
			$salesorder_focus->column_fields['salesorder_status'] = $stt;
			$salesorder_focus->column_fields['salesorder_notapprove'] = @$_REQUEST["salesorder_notapprove"];		
			$approveflag = 1;
			
			$sql = " select userid
					from tbt_salesorder_approve
					where crmid='".$crmid."'
					and appstatus != 0
					order by level,username	 ";
			$a_usermail = $myLibrary_mysqli->select($sql);

			$sql ="select * from aicrm_inventoryproductrel where id = '".$crmid."' ";
			$after_p = $myLibrary_mysqli->select($sql);
			foreach ($after_p as $key => $value) {
				$sql_p = "update aicrm_products set stockbooking = (stockbooking-".$value['quantity'].") , stockavailable = (stockavailable+".$value['quantity'].") where productid = '".$value['productid']."' ";
				$myLibrary_mysqli->Query($sql_p);
			}

		}
				
		$assignto = get_update_assignto($salesorderstatus,$crmid,$approveflag);
		
		if($assignto!=""){
			$salesorder_focus->column_fields['assigned_user_id'] =$assignto;
		}

		$salesorder_focus->id = $crmid;
		$salesorder_focus->mode = "edit";
		$salesorder_focus->save("Salesorder");

        if($salesorderstatus == 'Approve' && $level == 4){
            $sql = "SELECT
                        aicrm_users.id as userid
                    FROM
                        aicrm_users
                    LEFT JOIN aicrm_salesorder ON aicrm_salesorder.quota1=aicrm_users.user_name OR aicrm_salesorder.quota1=aicrm_users.user_name
                    WHERE
                        aicrm_salesordercf.salesorderid='".$crmid."'";
            $a_usermail = $myLibrary_mysqli->select($sql);
            //$mail_status = send_mail_salesorder($salesorder_focus,$a_usermail);
        }else{
            //$mail_status = send_mail_salesorder($salesorder_focus,$a_usermail);
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
		if($salesorderstatus=="Request_Approve"){
			$msg = "Request Approve";
			$stt = "ขออนุมัติใบขาย";
		}else if($salesorderstatus == 'Cancel_Salesorder'){
			$msg = "Cancel Salesorder";
			$stt = "ยกเลิกใบขาย";

			$sql ="select * from aicrm_inventoryproductrel where id = '".$crmid."' ";
			$after_p = $myLibrary_mysqli->select($sql);
			foreach ($after_p as $key => $value) {
				$sql_p = "update aicrm_products set stockbooking = (stockbooking-".$value['quantity'].") , stockavailable = (stockavailable+".$value['quantity'].") where productid = '".$value['productid']."' ";
				$myLibrary_mysqli->Query($sql_p);
			}
		}else if($salesorderstatus == 'Payments'){
			$msg = "Payments";
			$stt = "ชำระแล้ว";

			$sql ="select * from aicrm_inventoryproductrel where id = '".$crmid."' ";
			$after_p = $myLibrary_mysqli->select($sql);
			foreach ($after_p as $key => $value) {
				$sql_p = "update aicrm_products set stockbooking = (stockbooking-".$value['quantity'].") , stockqty = (stockqty-".$value['quantity'].") where productid = '".$value['productid']."' ";
				$myLibrary_mysqli->Query($sql_p);
			}

		}else if($salesorderstatus=="Complete"){
			$msg = "Complete";
			$stt = "ชำระแล้ว";			
		}
	
		$salesorder_focus = new Salesorder();
		$salesorder_focus->retrieve_entity_info($crmid,"Salesorder");

		if($salesorderstatus=="Request_Approve"){
			/*//Approve Level 1
			$approvelevel1 = $salesorder_focus->column_fields['approve_level1'];
			$a_appr_1 = explode(' |##| ', $approvelevel1);
			if(!empty($a_appr_1)){
				$sql = "
					insert into tbt_salesorder_approve (id,crmid,userid,username,level,appstatus)	
					select 0,'".$crmid."',id,user_name,1,0
					from aicrm_users
					where CONCAT(first_name,' ',last_name) in ('".implode("','", $a_appr_1)."')";
				$myLibrary_mysqli->Query($sql);
			}
			
			//Approve Level 2
			$approvelevel2 = $salesorder_focus->column_fields['approve_level2'];
			$a_appr_2 = explode(' |##| ', $approvelevel2);
			if(!empty($a_appr_2)){
				$sql = "
					insert into tbt_salesorder_approve (id,crmid,userid,username,level,appstatus)
					select 0,'".$crmid."',id,user_name,2,0
					from aicrm_users
					where CONCAT(first_name,' ',last_name) in ('".implode("','", $a_appr_2)."') ";
				$myLibrary_mysqli->Query($sql);
			}
			//Approve Level 3
			$approvelevel3 = $salesorder_focus->column_fields['approve_level3'];
			$a_appr_3 = explode(' |##| ', $approvelevel3);
			if(!empty($a_appr_3)){
				$sql = "
					insert into tbt_salesorder_approve (id,crmid,userid,username,level,appstatus)
					select 0,'".$crmid."',id,user_name,3,0
					from aicrm_users
					where CONCAT(first_name,' ',last_name) in ('".implode("','", $a_appr_3)."') ";
				$myLibrary_mysqli->Query($sql);
			}*/
			
			//Approve Level 4
			$approvelevel4 = $salesorder_focus->column_fields['approve_level4'];
			$a_appr_4 = explode(' |##| ', $approvelevel4);
			if(!empty($a_appr_4)){
				$sql = "
					insert into tbt_salesorder_approve (id,crmid,userid,username,level,appstatus)
					select 0,'".$crmid."',id,user_name,4,0
					from aicrm_users
					where CONCAT(first_name,' ',last_name) in ('".implode("','", $a_appr_4)."') ";
				$myLibrary_mysqli->Query($sql);
			}
			
			$assignto = get_update_assignto($salesorderstatus,$crmid);
		
		}else if($salesorderstatus=="Cancel_Salesorder"){
			$a_reponse["msg"] = '<center>ยกเลิกใบขายสำเร็จ</center>';
			$salesorder_focus->column_fields['salesorder_cancel'] = $_REQUEST["salesorder_cancel"];
		}else if($salesorderstatus=="Complete"){
			$a_reponse["msg"] = '<center>ปิดการขายสำเร็จ</center>';
		}

        //echo $assignto;
		$salesorder_focus->column_fields['salesorder_status'] = $stt;
		if($assignto!=""){
			$salesorder_focus->column_fields['assigned_user_id'] =$assignto;
		}
		$salesorder_focus->id = $crmid;
		$salesorder_focus->mode = "edit";
		//echo "<pre>"; print_r($salesorder_focus); echo "</pre>"; exit;
		$salesorder_focus->save("Salesorder");
		
		//$mail_status = send_mail_salesorder($salesorder_focus,$a_usermail);
		$a_reponse["status"] = true;
		$a_reponse["error"] = "" ;
		if($salesorderstatus!="Complete"){
			$a_reponse["msg"] = $msg. " Complete" ;
		}
		$a_reponse["result"] = "";
		$a_reponse["url"] = "index.php";
		echo json_encode($a_reponse);
	}	
	
function get_update_assignto($salesorderstatus="",$crmid,$approveflag=0)
{
	global $myLibrary_mysqli;
	if(($approveflag==1 && $salesorderstatus == 'Approve') || $salesorderstatus == 'Cancel_Approve'){
		$sql = "select smcreatorid as userid
			from aicrm_crmentity
			where crmid='".$crmid."'
			limit 1	";
	}else{
		$sql = "select userid
			from tbt_salesorder_approve
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

function send_mail_salesorder($focus,$a_usermail)
{
	require_once("library/genarate.inc.php");
	//require_once("library/dbconfig.php");
	global $dbconfig,$genarate,$site_URL,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID;
	$genarate = new genarate($dbconfig ,"DB");

	require_once('include/email_alert/email_alert.php');
	$email_body =GetEmail("Salesorder",'header_alert.jpg',$focus->id,"salesorderid");
	
	$type = 'เรื่อง';
	$subject = "Quote No : [".$focus->column_fields['quote_no']."] เรื่อง : ".$focus->column_fields['quote_name'];

	//send mail to the assigned to user and the parent to whom this ticket is assigned
	require_once('modules/Emails/mail.php');
	$a_mail[] = getUserEmailId('id',$focus->column_fields['assigned_user_id']);
	if(!empty($a_usermail)){
		foreach ($a_usermail as $k => $v){
			$userid = $v["userid"];
			$a_mail[] = getUserEmailId('id',$userid);
		}
	}
	$status = $focus->column_fields['salesorder_status'];
	//echo $status;
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
	$mail_status = send_mail('Salesorder',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
	return 	$user_emailid;
}

?>