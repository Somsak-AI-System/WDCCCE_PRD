<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once("config.inc.php");
	require_once("library/dbconfig.php");
	include_once("library/myLibrary_mysqli.php");
	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;
	$date=date('d-m-Y');

	$crmid = $_REQUEST["crmid"];
	$samplerequisitionstatus = $_REQUEST["samplerequisitionstatus"];
	$level = $_REQUEST["level"];

	require_once('modules/Samplerequisition/Samplerequisition.php');
	require_once('modules/Users/Users.php');
	require_once('include/utils/UserInfoUtil.php');

    $_REQUEST["ajxaction"] = "DETAILVIEW";

	$current_user = new Users();
	$result = $current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id']);
	global $current_user;

	$assignto = '';
	$sql = "insert into tbt_samplerequisition_log(crmid,assignto,samplerequisition_status,adduser,adddate)
			values('".$crmid."','".$assignto."','".$samplerequisitionstatus."','".$_SESSION['authenticated_user_id']."','".date('Y-m-d H:i:s')."');";
	$myLibrary_mysqli->Query($sql);
	
	$a_usermail = array();
	if($samplerequisitionstatus == 'Approve' || $samplerequisitionstatus == 'Cancel_Approve'){
		
		if($samplerequisitionstatus == 'Approve'){
			$msg = "Approve";
			$stt = "Approved";
			$appstt = "1";
		}else{
			$msg = "Cancel Approve";
			$stt = "Rejected";
			$appstt = "2";
		}
		$sam_focus = new Samplerequisition();
		$sam_focus->retrieve_entity_info($crmid,"Samplerequisition");
		$sql = "update tbt_samplerequisition_approve 
					set appstatus='".$appstt."'
					,upuser = '".$_SESSION['authenticated_user_id']."'
					,updatedate = '".date('Y-m-d H:i:s')."'		
					where crmid='".$crmid."'				
					and userid = '".$_SESSION['authenticated_user_id']."'
					and level = '".$level."' ";
		$myLibrary_mysqli->Query($sql);
		
		$approveflag = 0;
		if($samplerequisitionstatus == 'Approve'){
			$sql = "select count(id) as countid,crmid
			from tbt_samplerequisition_approve
			where crmid='".$crmid."'
			and appstatus = 0
			group by crmid ";
			$a_data = $myLibrary_mysqli->select($sql);
			
			if($a_data[0]["countid"] == 0 ){
				$sam_focus->column_fields['samplerequisition_status'] = $stt;

				$sam_focus->id = $crmid;
				$sam_focus->mode = "edit";
				$sam_focus->save("Samplerequisition");

				$approveflag = 1;
			}	
		}else if($samplerequisitionstatus == 'Cancel_Approve'){			
			$sam_focus->column_fields['samplerequisition_status'] = $stt;
			$sam_focus->column_fields['rejected_reason'] = $_REQUEST["rejected_reason"];

			$sam_focus->id = $crmid;
			$sam_focus->mode = "edit";
			$sam_focus->save("Samplerequisition");

			$approveflag = 1;
			
			$sql = " select userid
					from tbt_samplerequisition_approve
					where crmid='".$crmid."'
					and appstatus != 0
					order by level,username	 ";
			$a_usermail = $myLibrary_mysqli->select($sql);
		}
				
		$assignto = get_update_assignto($samplerequisitionstatus,$crmid,$approveflag);
		
		if($assignto!=""){
			$sam_focus->column_fields['assigned_user_id'] =$assignto;
		}

		/*$sam_focus->id = $crmid;
		$sam_focus->mode = "edit";
		$sam_focus->save("Samplerequisition");*/

        /*if($samplerequisitionstatus == 'Approve' && $level == 4){
            $sql = "SELECT aicrm_users.id as userid
                    FROM aicrm_users
                    LEFT JOIN aicrm_samplerequisition ON aicrm_quotes.quota1=aicrm_users.user_name OR aicrm_samplerequisition.quota1=aicrm_users.user_name
                    WHERE aicrm_samplerequisition.quoteid='".$crmid."'";
            $a_usermail = $myLibrary_mysqli->select($sql);
            $mail_status = send_mail_quotes($sam_focus,$a_usermail);
        }else{
            $mail_status = send_mail_quotes($sam_focus,$a_usermail);
        }*/

        $a_reponse["status"] = true;
		$a_reponse["error"] = "" ;
		$a_reponse["msg"] = $msg. " Complete" ;
		$a_reponse["result"] = "";
        $a_reponse["mail_status"] = '';
		$a_reponse["url"] = "index.php";
        $a_reponse["assignto"] = $assignto;
		echo json_encode($a_reponse);

	}else{
		if($samplerequisitionstatus=="Request_Approve"){
			$msg = "Request Approve";
			$stt = "Request for Approve";
		}else if($samplerequisitionstatus == 'Cancel_Samplerequisition'){
			$msg = "Cancel Sample Requisition";
			$stt = "Cancelled";
		}
	
		$sam_focus = new Samplerequisition();
		$sam_focus->retrieve_entity_info($crmid,"Samplerequisition");

		if($samplerequisitionstatus=="Request_Approve"){
			//Approve Level 1
			$approvelevel1 = $sam_focus->column_fields['approver'];
			$a_appr_1 = explode(' |##| ', $approvelevel1);
			if(!empty($a_appr_1)){
				$sql = "
					insert into tbt_samplerequisition_approve (id,crmid,userid,username,level,appstatus)	
					select 0,'".$crmid."',id,user_name,1,0
					from aicrm_users
					where CONCAT(first_name,' ',last_name) in ('".implode("','", $a_appr_1)."')";
				$myLibrary_mysqli->Query($sql);
			}
			
			//Approve Level 2
			$approvelevel2 = $sam_focus->column_fields['approver2'];
			$a_appr_2 = explode(' |##| ', $approvelevel2);
			if(!empty($a_appr_2)){
				$sql = "
					insert into tbt_samplerequisition_approve (id,crmid,userid,username,level,appstatus)
					select 0,'".$crmid."',id,user_name,2,0
					from aicrm_users
					where CONCAT(first_name,' ',last_name) in ('".implode("','", $a_appr_2)."') ";
				$myLibrary_mysqli->Query($sql);
			}
			
			//Approve Level 3
			$approvelevel3 = $sam_focus->column_fields['approver3'];
			$a_appr_3 = explode(' |##| ', $approvelevel3);
			if(!empty($a_appr_3)){
				$sql = "
					insert into tbt_samplerequisition_approve (id,crmid,userid,username,level,appstatus)
					select 0,'".$crmid."',id,user_name,3,0
					from aicrm_users
					where CONCAT(first_name,' ',last_name) in ('".implode("','", $a_appr_3)."') ";
				$myLibrary_mysqli->Query($sql);
			}

			//Approve Level 4
			$approvelevel4 = $sam_focus->column_fields['f_approver'];
			$a_appr_4 = explode(' |##| ', $approvelevel4);
			if(!empty($a_appr_4)){
				$sql = "
					insert into tbt_samplerequisition_approve (id,crmid,userid,username,level,appstatus)
					select 0,'".$crmid."',id,user_name,4,0
					from aicrm_users
					where CONCAT(first_name,' ',last_name) in ('".implode("','", $a_appr_4)."') ";
				$myLibrary_mysqli->Query($sql);
			}
			
			$assignto = get_update_assignto($samplerequisitionstatus,$crmid);
		
		}else if($samplerequisitionstatus=="Cancel_Samplerequisition"){
			$sam_focus->column_fields['cancel_reason'] = $_REQUEST["cancel_reason"];	
			$a_reponse["msg"] = '<center>ยกเลิกใบขอตัวอย่างสำเร็จ</center>';
		}
		
		if($assignto!=""){
			$sam_focus->column_fields['assigned_user_id'] =$assignto;
		}
		
		$sam_focus->column_fields['samplerequisition_status'] = $stt;

		$sam_focus->id = $crmid;
		$sam_focus->mode = "edit";
		$sam_focus->save("Samplerequisition");
		
		$mail_status = send_mail_quotes($sam_focus,$a_usermail);
		$a_reponse["status"] = true;
		$a_reponse["error"] = "" ;
		if($samplerequisitionstatus!="Complete"){
			$a_reponse["msg"] = $msg. " Complete" ;
		}
		$a_reponse["result"] = "";
		$a_reponse["url"] = "index.php";
		echo json_encode($a_reponse);
	}	
	
function get_update_assignto($samplerequisitionstatus="",$crmid,$approveflag=0)
{
	global $myLibrary_mysqli;
	if(($approveflag==1 && $samplerequisitionstatus == 'Approve') || $samplerequisitionstatus == 'Cancel_Approve'){
		$sql = "select smcreatorid as userid
			from aicrm_crmentity
			where crmid='".$crmid."'
			limit 1	";
	}else{
		$sql = "select userid
			from tbt_samplerequisition_approve
			where crmid='".$crmid."'
			and appstatus = 0
			order by level,username
			limit 1	";
	}
	$a_data = $myLibrary_mysqli->select($sql);
	return $a_data[0]["userid"];
}


function send_mail_quotes($focus,$a_usermail)
{
	require_once("library/genarate.inc.php");
	global $dbconfig,$genarate,$site_URL,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID;
	$genarate = new genarate($dbconfig ,"DB");

	require_once('include/email_alert/email_alert.php');
	$email_body =GetEmail("Samplerequisition",'header_alert.jpg',$focus->id,"quoteid");
	
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

	$status = $focus->column_fields['samplerequisition_status'];	
	$a_mail = array_keys(array_flip($a_mail));
	$user_emailid = implode(",", $a_mail);
	$mail_status = send_mail('Samplerequisition',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
	return 	$user_emailid;
}
?>