<?php

include("config.inc.php");
include("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");

global $myLibrary_mysqli;
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$sql = "select date(tbt_case_log.adddate) as log_date
		,TIME(tbt_case_log.adddate) as log_time
		,tbt_case_log.casestatus
		,case when (assignto.user_name not like '') then concat(assignto.first_name,' ' ,assignto.last_name,' [',assignto.user_name ,']') else aicrm_groups.groupname end as assigntoname
		,concat(userlog.first_name,' ' ,userlog.last_name,' [',userlog.user_name ,']') as userlogname
		,userlog.section as userlog_section
		,assignto.section as assignto_section
		from tbt_case_log
		left join aicrm_users assignto on tbt_case_log.assignto = assignto.id
		left join aicrm_groups on aicrm_groups.groupid = tbt_case_log.assignto
		left join aicrm_users userlog on tbt_case_log.adduser = userlog.id
		where 1=1
		and tbt_case_log.crmid = '".$_REQUEST["record"]."'
		order by tbt_case_log.adddate asc ";
$data = $myLibrary_mysqli->select($sql);

include_once 'view/view_log.php';

//case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
?>