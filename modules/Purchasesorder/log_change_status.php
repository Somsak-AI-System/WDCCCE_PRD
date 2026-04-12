<?php

include("config.inc.php");
include("library/dbconfig.php");
require_once("library/generate_MYSQL.php");
require_once("library/general.php");
global $generate,$current_user;
$generate = new generate($dbconfig ,"DB");


$sql = "select date(tbt_project_log.adddate) as log_date
		,TIME(tbt_project_log.adddate) as log_time
		,REPLACE(tbt_project_log.purchasesordertatus,'_',' ') as purchasesordertatus
		,concat(assignto.first_name,' ' ,assignto.last_name,' [',assignto.user_name ,']') as assigntoname
		,concat(userlog.first_name,' ' ,userlog.last_name,' [',userlog.user_name ,']') as userlogname
		from tbt_project_log
		left join aicrm_users assignto on tbt_project_log.assignto = assignto.id
		left join aicrm_users userlog on tbt_project_log.adduser = userlog.id
		where 1=1
		and tbt_project_log.crmid = '".$_REQUEST["record"]."'
		order by tbt_project_log.adddate asc ";

$data = $generate->process($sql,"all");
include_once 'view/view_log.php';
?>