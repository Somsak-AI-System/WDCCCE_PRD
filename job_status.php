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
$job_status = $_REQUEST["job_status"];
$assignto =  $_REQUEST["assignto"];
$ownerid = $_REQUEST["assigned_user_id"];
$groupid = $_REQUEST["assigned_group_id"];
$user_login = $_SESSION['authenticated_user_id'];

require_once('modules/Job/Job.php');
require_once('modules/Users/Users.php');
require_once('include/utils/UserInfoUtil.php');

//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
$_REQUEST["ajxaction"] = "DETAILVIEW";

$current_user = new Users();
$result = $current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id']);
global $current_user;


if($crmid!=''){
    if($job_status=="ส่งต่อ"){
        $flag_ass = 0;
        if($_REQUEST['assigntype']=='T'){
            $flag_ass=$groupid ;
        }else{
            $flag_ass=$ownerid ;
        }
        $sql = "insert into tbt_job_log(crmid,assignto,job_status,adduser,adddate)
            values('".$crmid."','".$flag_ass."','".$job_status."','".$_SESSION['authenticated_user_id']."','".date('Y-m-d H:i:s')."');";
    }else{
        $sql = "insert into tbt_job_log(crmid,assignto,job_status,adduser,adddate)
            values('".$crmid."','".$assignto."','".$job_status."','".$_SESSION['authenticated_user_id']."','".date('Y-m-d H:i:s')."');";  
    }
    
    $generate->query($sql);
}

if($job_status=="ส่งต่อ" /*&& $_REQUEST["sendmailto"] =="1"*/){
    //############  Job  ###########
    $job_focus = new Job();
    $job_focus->retrieve_entity_info($crmid,"Job");
    $job_focus->column_fields['comments'] = '';
    try {
        if($crmid!=''){
            //$_REQUEST['assigntype']= "U";
            $job_focus->column_fields['assigned_user_id'] =$assignto;
            //$job_focus->column_fields['ticketstatus'] = $job_status;
            $job_focus->id = $crmid;
            $job_focus->mode = "edit";

            if(isset($_REQUEST["sendmailto"]) && $_REQUEST["sendmailto"]== '1' && $_REQUEST["sendmailtogroup"]== '0'){

                $sql1 = "UPDATE aicrm_crmentity SET smownerid =  $ownerid  WHERE crmid = '" . $job_focus->id . "' ";
                $generate->query($sql1);

                require_once('include/email_alert/email_alert.php');
                $email_body =GetEmail("Job",'header_alert.jpg',$job_focus->id,"jobid");

                $type = 'รูปแบบใบงาน';
                $subject = "หมายเลขใบงาน : [".$job_focus->column_fields['job_no']."] ".$type." : ".$job_focus->column_fields['job_type'];
                
                //get email========================================================================

                //send mail to the assigned to user and the parent to whom this ticket is assigned
                require_once('modules/Emails/mail.php');

                $a_mail[] = getUserEmailId($ownerid);
                $sql = "SELECT DISTINCT (`smcreatorid`) as smcreatorid
							FROM `aicrm_crmentity`
							WHERE `crmid` = '".$job_focus->id."' ";
                $data = $generate->process($sql,"all");
                if(!empty($data) && count($data)>0){
                    foreach ($data as $k => $v){
                        $userid  = $v["smcreatorid"];
                        $a_mail[] = getUserEmailId('id',$ownerid);
                    }
                }
                $a_mail = array_keys(array_flip($a_mail));
                $user_emailid = implode(",", $a_mail);

                $mail_status = send_mail('Job',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);

            }else if(isset($_REQUEST["sendmailtogroup"]) && $_REQUEST["sendmailtogroup"]== '2' && $_REQUEST["sendmailto"]== '0'){

                $sql2 = "UPDATE aicrm_crmentity crm INNER JOIN  aicrm_jobs jb ON (crm.crmid = jb.jobid)
                     SET crm.smownerid =  $groupid WHERE crm.crmid = '".$job_focus->id."'";
                $generate->query($sql2);

                sendNotificationToGroups($groupid, $crmid, 'Job');

            }else if($_REQUEST["assigntype"]== 'T' && $_REQUEST["sendmailtogroup"]== '0' && $_REQUEST["sendmailto"]== '0'){

                $sql3 = "UPDATE aicrm_crmentity crm INNER JOIN  aicrm_jobs jb ON (crm.crmid = jb.jobid)
                     SET crm.smownerid =  $groupid  WHERE crm.crmid = '".$job_focus->id."'";
                $generate->query($sql3);

            }else{
                //$job_focus->column_fields['date_of_execution'] = date('Y-m-d');
                //$job_focus->column_fields['execution_time'] = date('H:i');
                $job_focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_user_id'];
                $job_focus->save("Job");
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

    $msg = $job_status;
    $job_focus = new Job();
    $job_focus->retrieve_entity_info($crmid,"Job");
    $job_focus->column_fields['comments'] = '';

    if($job_status=="ปิดงาน"){
        $job_focus->column_fields['job_status'] = 'ปิดงาน';
        $job_focus->column_fields['close_date'] = date('d-m-Y');//วันที่ปิด
        $job_focus->column_fields['closed_time'] = date('H:i');//เวลาที่ปิด
    }else if($job_status=="รับงาน"){
        $job_focus->column_fields['job_status'] = 'อยู่ระหว่างดำเนินงาน';
        $job_focus->column_fields['operation_date'] = date('d-m-Y');//วันที่ดำเนินการ
        $job_focus->column_fields['operation_time'] = date('H:i');//เวลาที่ดำเนินการ
    }else if($job_status=="อยู่ระหว่างดำเนินงาน"){
        $job_focus->column_fields['job_status'] = 'อยู่ระหว่างดำเนินงาน';
        $job_focus->column_fields['operation_date'] = date('d-m-Y');//วันที่เข้าดำเนินการ
        $job_focus->column_fields['operation_time'] = date('H:i');//เวลาที่เข้าดำเนินการ
    }else if($job_status=='ยกเลิก'){
        $job_focus->column_fields['job_status'] = 'ยกเลิกงาน';
        $job_focus->column_fields['cancelled_date'] = date('d-m-Y');//วันที่ยกเลิก
        $job_focus->column_fields['cancelled_time'] = date('H:i');//เวลาที่ยกเลิก
    }
    
    $job_focus->id = $crmid;
    $job_focus->mode = "edit";
    
    $job_focus->save("Job");

    if($job_status=="ปิดงาน"){

        require_once('include/email_alert/email_alert.php');
        $email_body =GetEmail("Job",'header_alert.jpg',$job_focus->id,"jobid");

        $type = 'รูปแบบใบงาน';
        $subject = "หมายเลขใบงาน : [".$job_focus->column_fields['job_no']."] ".$type." : ".$job_focus->column_fields['job_type'];
        //echo $email_body;exit;
        //get email========================================================================

        //send mail to the assigned to user and the parent to whom this ticket is assigned
        require_once('modules/Emails/mail.php');
        $a_mail[] = getUserEmailId('id',$job_focus->column_fields['assigned_user_id']);
        $sql = "SELECT DISTINCT (`adduser`) as adduser
					FROM `tbt_job_log`
					WHERE `crmid` = '".$job_focus->id."' ";
        $data = $generate->process($sql,"all");
        if(!empty($data) && count($data)>0){
            foreach ($data as $k => $v){
                $userid  = $v["adduser"];

                $a_mail[] = getUserEmailId('id',$userid);
            }

        }
        $a_mail = array_keys(array_flip($a_mail));
        $user_emailid = implode(",", $a_mail);

        $mail_status = send_mail('Job',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);

    }else if ($job_status=="รับงาน"){

        require_once('include/utils/GetGroupUsers.php');
        require_once('modules/Job/Job.php');

        $help_focus = new Job();
        $help_focus->retrieve_entity_info($crmid,"Job");

        $groupid = $help_focus->column_fields['assigned_user_id'];

        $sql3 = "UPDATE aicrm_crmentity crm INNER JOIN  aicrm_jobs jb ON (crm.crmid = jb.jobid)
                    SET crm.smownerid =  $user_login
                    WHERE crm.crmid = '".$job_focus->id."'";
        $generate->query($sql3);

        sendNotificationToGroups($groupid, $crmid, 'Job');

    }else if ($job_status=="อยู่ระหว่างดำเนินงาน"){

        $sql3 = "UPDATE aicrm_crmentity crm INNER JOIN  aicrm_jobs jb ON (crm.crmid = jb.jobid)
                    SET crm.smownerid =  $user_login
                    WHERE crm.crmid = '".$job_focus->id."'";

        $generate->query($sql3);

    }

    if($job_status=="ปิดงาน" || $job_status=='ยกเลิก'){

        $service = "select servicerequestid from aicrm_jobs where jobid = '".$crmid."' ";
        $data_service = $generate->process($service,"all");
        $servicerequestid = $data_service[0]['servicerequestid'];

        $sql = "select aicrm_jobs.jobid from aicrm_jobs 
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid
                where aicrm_crmentity.deleted = 0 and aicrm_jobs.job_status not in ('ปิดงาน','ยกเลิกงาน') 
                and aicrm_jobs.servicerequestid ='".$servicerequestid ."' " ;
        $data_job = $generate->process($sql,"all");
        
        if(empty($data_job)){

            $sql_service_log = "insert into tbt_servicerequest_log(crmid,assignto,service_request_status,adduser,adddate)
                        values('".$servicerequestid."','0','ปิดงาน','".$_SESSION['authenticated_user_id']."','".date('Y-m-d H:i:s')."');";  
            $generate->query($sql_service_log);

            $update_service = "UPDATE aicrm_crmentity crm INNER JOIN  aicrm_servicerequest sr ON (crm.crmid = sr.servicerequestid)
                            SET crm.modifiedtime ='".date('Y-m-d H:i:s')."' , crm.modifiedby = '".$_SESSION['authenticated_user_id']."' ,
                            sr.service_request_status = 'ปิดงาน' , sr.closed_date = '".date('Y-m-d')."' , sr.closed_time = '".date('H:i')."'
                            WHERE crm.crmid ='".$servicerequestid."' ";
            $generate->query($update_service);

        }
    }

    echo "<script type='text/javascript'>alert('".$msg." Complete');window.close();  window.opener.parent.location.replace('index.php?action=DetailView&module=Job&record=".$crmid."&parenttab=Service');</script>";
}

?>