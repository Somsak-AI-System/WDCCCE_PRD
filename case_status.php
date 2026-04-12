<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include("config.inc.php");

include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/generate_MYSQL.php");

global $generate;
$generate = new generate($dbconfig ,"DB");

$date=date('Y-m-d H:i:s');

$crmid = $_REQUEST["crmid"];
$casestatus = $_REQUEST["casestatus"];
$assignto =  $_REQUEST["assignto"];
$ownerid = $_REQUEST["assigned_user_id"];
$groupid = $_REQUEST["assigned_group_id"];
$user_login = $_SESSION['authenticated_user_id'];

require_once('modules/HelpDesk/HelpDesk.php');
require_once('modules/Users/Users.php');
require_once('include/utils/UserInfoUtil.php');


$_REQUEST["ajxaction"] = "DETAILVIEW";

$current_user = new Users();
$result = $current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id']);
global $current_user;

if($_REQUEST["assigntype"]== 'U'){
    $assignto = $_REQUEST["assigned_user_id"];
}else if($_REQUEST["assigntype"]== 'T'){
    $assignto = $_REQUEST["assigned_group_id"];
}


if($crmid!=''){
    $sql = "insert into tbt_case_log(crmid,assignto,casestatus,adduser,adddate)
			values('".$crmid."','".$assignto."','".$casestatus."','".$_SESSION['authenticated_user_id']."','".date('Y-m-d H:i:s')."');";
    $generate->query($sql);
}

if($casestatus=="ส่งต่อ" /*&& $_REQUEST["sendmailto"] =="1"*/){
    //############  HelpDesk  ###########
    $case_focus = new HelpDesk();
    $case_focus->retrieve_entity_info($crmid,"HelpDesk");
    try {
        if($crmid!=''){
            //$_REQUEST['assigntype']= "U";
            $case_focus->column_fields['assigned_user_id'] =$assignto;
            $case_focus->column_fields['ticketstatus'] = $casestatus;
            $case_focus->id = $crmid;
            $case_focus->mode = "edit";

            if(isset($_REQUEST["sendmailto"]) && $_REQUEST["sendmailto"]== '1' && $_REQUEST["sendmailtogroup"]== '0'){
                $sql1 = "UPDATE aicrm_crmentity SET smownerid =  $ownerid  WHERE crmid = '" . $case_focus->id . "' ";
                $generate->query($sql1);

                require_once('include/email_alert/email_alert.php');
                $email_body =GetEmail("HelpDesk",'header_alert.jpg',$case_focus->id,"ticketid");

                $type = 'ประเภทการรับเรื่อง';
                $subject = "Case No : [".$case_focus->column_fields['ticket_no']."] ".$type." : ".$case_focus->column_fields['case_category'];
                //echo $email_body;exit;
                //get email========================================================================

                //send mail to the assigned to user and the parent to whom this ticket is assigned
                require_once('modules/Emails/mail.php');

                $a_mail[] = getUserEmailId($ownerid);
                $sql = "SELECT DISTINCT (`smcreatorid`) as smcreatorid
							FROM `aicrm_crmentity`
							WHERE `crmid` = '".$case_focus->id."' ";
                $data = $generate->process($sql,"all");
                if(!empty($data) && count($data)>0){
                    foreach ($data as $k => $v){
                        $userid  = $v["smcreatorid"];
                        $a_mail[] = getUserEmailId('id',$ownerid);
                    }
                }
                $a_mail = array_keys(array_flip($a_mail));
                $user_emailid = implode(",", $a_mail);

                $mail_status = send_mail('HelpDesk',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);

            }else if(isset($_REQUEST["sendmailtogroup"]) && $_REQUEST["sendmailtogroup"]== '2' && $_REQUEST["sendmailto"]== '0'){

                $sql2 = "UPDATE aicrm_crmentity crm INNER JOIN  aicrm_troubletickets tk ON (crm.crmid = tk.ticketid)
                     SET crm.smownerid =  $groupid, tk.case_status ='ส่งต่อ' WHERE crm.crmid = '".$case_focus->id."'";
                $generate->query($sql2);

                sendNotificationToGroups($groupid, $crmid, 'HelpDesk');

            }else if($_REQUEST["assigntype"]== 'T' && $_REQUEST["sendmailtogroup"]== '0' && $_REQUEST["sendmailto"]== '0'){

                $sql3 = "UPDATE aicrm_crmentity crm INNER JOIN  aicrm_troubletickets tk ON (crm.crmid = tk.ticketid)
                     SET crm.smownerid =  $groupid, tk.case_status ='ส่งต่อ' WHERE crm.crmid = '".$case_focus->id."'";
                $generate->query($sql3);

            }else{
                $case_focus->column_fields['date_of_execution'] = date('Y-m-d');
                $case_focus->column_fields['execution_time'] = date('H:i');
                $case_focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_user_id'];
                $case_focus->save("HelpDesk");
            }
        }

        $a_reponse["status"] = true;
        $a_reponse["error"] = "" ;
        $a_reponse["msg"] = "บันทึกเรียบร้อย" ;
        $a_reponse["result"] = "";
        $a_reponse["url"] = "index.php";

    } catch (Exception $e) {
        $a_reponse["status"] = false;
        $a_reponse["error"] = $e->getMessage();
        $a_reponse["msg"] = "ลองอีกครั้ง" ;
        $a_reponse["result"] = "";
    }
    echo json_encode($a_reponse);

}else{

    $msg = $casestatus;
    $case_focus = new HelpDesk();
    $case_focus->retrieve_entity_info($crmid,"HelpDesk");
    $case_focus->column_fields['case_status'] = $casestatus;
    
    if($casestatus=="ปิดงาน"){
        $case_focus->column_fields['closed_date'] = date('d-m-Y');//close date
        $case_focus->column_fields['closed_time'] = date('H:i');//close time
    }
    
    $case_focus->id = $crmid;
    $case_focus->mode = "edit";
    $case_focus->save("HelpDesk");

    if($casestatus=="ปิดงาน"){

        /*$sql = "update aicrm_troubletickets 
                INNER JOIN aicrm_ticketcf on aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
                set cf_4687 = '".$_REQUEST['data'][0]['closed_reason']."' ,case_perfomance='".$_REQUEST['data'][0]['case_solution']."',cf_4616='".$_REQUEST['data'][0]['case_open_date']."',
                aicrm_troubletickets.status ='ปิดงาน'
                WHERE aicrm_troubletickets.ticketid='".$crmid."'";

        $generate->query($sql);

        $sql1 = "insert into aicrm_ticketcomments values('',$crmid,'".$_REQUEST['data'][0]['case_solution']."','".$current_user->id."','user','".$date."')";
        $generate->query($sql1);*/

        //$case_focus->save("HelpDesk");
        require_once('include/email_alert/email_alert.php');
        $email_body =GetEmail("HelpDesk",'header_alert.jpg',$case_focus->id,"ticketid");

        $type = 'ประเภทการรับเรื่อง';
        $subject = "Case No : [".$case_focus->column_fields['ticket_no']."] ".$type." : ".$case_focus->column_fields['case_category'];
        //echo $email_body;exit;
        //get email========================================================================

        //send mail to the assigned to user and the parent to whom this ticket is assigned
        require_once('modules/Emails/mail.php');
        $a_mail[] = getUserEmailId('id',$case_focus->column_fields['assigned_user_id']);
        $sql = "SELECT DISTINCT (`adduser`) as adduser
					FROM `tbt_case_log`
					WHERE `crmid` = '".$case_focus->id."' ";
        $data = $generate->process($sql,"all");
        if(!empty($data) && count($data)>0){
            foreach ($data as $k => $v){
                $userid  = $v["adduser"];

                $a_mail[] = getUserEmailId('id',$userid);
            }

        }
        $a_mail = array_keys(array_flip($a_mail));
        $user_emailid = implode(",", $a_mail);

        $mail_status = send_mail('HelpDesk',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);

    }else if ($casestatus=="รับงาน"){

        require_once('include/utils/GetGroupUsers.php');
        require_once('modules/HelpDesk/HelpDesk.php');
        $help_focus = new HelpDesk();
        $help_focus->retrieve_entity_info($crmid,"HelpDesk");

        $groupid = $help_focus->column_fields['assigned_user_id'];

        $sql3 = "UPDATE aicrm_crmentity crm INNER JOIN  aicrm_troubletickets tk ON (crm.crmid = tk.ticketid)
                    SET crm.smownerid =  $user_login, tk.date_completed = '".date('Y-m-d')."' , tk.time_completed = '".date('H:i')."'
                    WHERE crm.crmid = '".$case_focus->id."'";
        $generate->query($sql3);

        sendNotificationToGroups($groupid, $crmid, 'HelpDesk');

    }else if ($casestatus=="อยู่ระหว่างดำเนินการ"){

        $sql3 = "UPDATE aicrm_crmentity crm INNER JOIN  aicrm_troubletickets tk ON (crm.crmid = tk.ticketid)
                    SET crm.smownerid =  $user_login
                    WHERE crm.crmid = '".$case_focus->id."'";

        $generate->query($sql3);

    }

    echo "<script type='text/javascript'>alert('".$msg." Complete');window.close();  window.opener.parent.location.replace('index.php?action=DetailView&module=HelpDesk&record=".$crmid."&parenttab=Service');</script>";
}

//	echo "<script type='text/javascript'>alert('".$msg." Complete');window.close();  window.opener.parent.location.replace('index.php?action=DetailView&module=Accounts&record=".$crmid."&parenttab=Support');</script>";

?>