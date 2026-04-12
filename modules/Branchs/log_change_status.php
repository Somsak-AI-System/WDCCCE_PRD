<?php

include("config.inc.php");
include("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");

global $myLibrary_mysqli;
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$sql = "select date(tbt_job_log.adddate) as log_date
		,TIME(tbt_job_log.adddate) as log_time
		,tbt_job_log.job_status
		,concat(assignto.first_name,' ' ,assignto.last_name,' [',assignto.user_name ,']') as assigntoname
		,concat(userlog.first_name,' ' ,userlog.last_name,' [',userlog.user_name ,']') as userlogname
		,userlog.section as userlog_section
		,assignto.section as assignto_section
		from tbt_job_log
		left join aicrm_users assignto on tbt_job_log.assignto = assignto.id
		left join aicrm_users userlog on tbt_job_log.adduser = userlog.id
		where 1=1
		and tbt_job_log.crmid = '".$_REQUEST["record"]."'
		order by tbt_job_log.adddate asc ";
$data = $myLibrary_mysqli->select($sql);

include_once 'view/view_log.php';
?>