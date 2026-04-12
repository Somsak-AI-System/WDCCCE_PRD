<?php
	header('Content-Type: text/html; charset=utf-8');
	session_start();
	require_once("config.inc.php");
	require_once("library/dbconfig.php");
	include_once("library/myLibrary_mysqli.php");
	require_once("library/general.php");
	$General = new libGeneral();

	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;

	$sql = "select aicrm_users.id,aicrm_users.user_name,aicrm_role.roleid,aicrm_users.first_name,aicrm_users.last_name,aicrm_users.email1,aicrm_users.phone_mobile,
	aicrm_users.phone_work,aicrm_users.is_admin,aicrm_users.status ,aicrm_role.rolename ,aicrm_users.section ,aicrm_users.position
	from aicrm_users 
	inner join aicrm_user2role on aicrm_user2role.userid=aicrm_users.id 
	inner join aicrm_role on aicrm_role.roleid = aicrm_user2role.roleid 
	where aicrm_users.deleted=0 ORDER BY aicrm_users.user_name ASC ";
	
	$data = $myLibrary_mysqli->select($sql);
	$a_return['total'] = count($data);
	$a_return['rows'] = $data;
	$a_return['user_login'] = $_SESSION['user_id'];

	echo json_encode($a_return);

	
?>
