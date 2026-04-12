<?php

include("config.inc.php");
include("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");

global $myLibrary_mysqli;
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$sql = "select ifnull(date(tbt_salesorder_log.adddate),'') as log_date
		,ifnull(TIME(tbt_salesorder_log.adddate),'') as log_time
		,REPLACE(tbt_salesorder_log.salesorderstatus,'_',' ') as salesorderstatus
		,concat(assignto.first_name,' ' ,assignto.last_name,' [',assignto.user_name ,']') as assigntoname
		,concat(userlog.first_name,' ' ,userlog.last_name,' [',userlog.user_name ,']') as userlogname
		from tbt_salesorder_log
		left join aicrm_users assignto on tbt_salesorder_log.assignto = assignto.id
		left join aicrm_users userlog on tbt_salesorder_log.adduser = userlog.id
		where 1=1
		and tbt_salesorder_log.crmid = '".$_REQUEST["record"]."'
		order by tbt_salesorder_log.adddate asc ";
$data = $myLibrary_mysqli->select($sql);

$sql = "select ifnull(date(tbt_salesorder_approve.updatedate),'') as log_date
		,ifnull(TIME(tbt_salesorder_approve.updatedate),'') as log_time
		,case when tbt_salesorder_approve.appstatus = 1 then 'อนุมัติ'
		 when tbt_salesorder_approve.appstatus = 2 then 'ไม่อนุมัติ'
		 else 'รออนุมัติ' end as appstatus
		,concat(assignto.first_name,' ' ,assignto.last_name,' [',assignto.user_name ,']') as assigntoname
		,concat(userlog.first_name,' ' ,userlog.last_name,' [',userlog.user_name ,']') as userupdatename
		,'ผู้อนุมัติ' as level
		
		from tbt_salesorder_approve
		left join aicrm_users assignto on tbt_salesorder_approve.userid = assignto.id
		left join aicrm_users userlog on tbt_salesorder_approve.upuser = userlog.id
		where 1=1
		and tbt_salesorder_approve.crmid = '".$_REQUEST["record"]."'
		order by tbt_salesorder_approve.level asc ";
		//,concat('ผู้อนุมัติ Level ',tbt_salesorder_approve.level) as level
$data_approve = $myLibrary_mysqli->select($sql);

include_once 'view/view_log.php';

?>