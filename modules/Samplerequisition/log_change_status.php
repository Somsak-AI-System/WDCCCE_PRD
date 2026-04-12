<?php

include("config.inc.php");
include("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");

global $myLibrary_mysqli;
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$sql = "select date(tbt_samplerequisition_log.adddate) as log_date
		,TIME(tbt_samplerequisition_log.adddate) as log_time
		,REPLACE(tbt_samplerequisition_log.samplerequisition_status,'_',' ') as samplerequisition_status
		,concat(assignto.first_name,' ' ,assignto.last_name,' [',assignto.user_name ,']') as assigntoname
		,concat(userlog.first_name,' ' ,userlog.last_name,' [',userlog.user_name ,']') as userlogname
		from tbt_samplerequisition_log
		left join aicrm_users assignto on tbt_samplerequisition_log.assignto = assignto.id
		left join aicrm_users userlog on tbt_samplerequisition_log.adduser = userlog.id
		where 1=1
		and tbt_samplerequisition_log.crmid = '".$_REQUEST["record"]."'
		order by tbt_samplerequisition_log.adddate asc ";
$data = $myLibrary_mysqli->select($sql);

$sql = "select date(tbt_samplerequisition_approve.updatedate) as log_date
		,TIME(tbt_samplerequisition_approve.updatedate) as log_time
		,case when tbt_samplerequisition_approve.appstatus = 1 then 'อนุมัติ'
		 when tbt_samplerequisition_approve.appstatus = 2 then 'ไม่อนุมัติ'
		 else 'รออนุมัติ' end as appstatus
		,concat(assignto.first_name,' ' ,assignto.last_name,' [',assignto.user_name ,']') as assigntoname
		,concat(userlog.first_name,' ' ,userlog.last_name,' [',userlog.user_name ,']') as userupdatename
		,concat('ผู้อนุมัติ Level ',tbt_samplerequisition_approve.level) as level
		
		from tbt_samplerequisition_approve
		left join aicrm_users assignto on tbt_samplerequisition_approve.userid = assignto.id
		left join aicrm_users userlog on tbt_samplerequisition_approve.upuser = userlog.id
		where 1=1
		and tbt_samplerequisition_approve.crmid = '".$_REQUEST["record"]."'
		order by tbt_samplerequisition_approve.level asc ";
$data_approve = $myLibrary_mysqli->select($sql);

include_once 'view/view_log.php';

?>