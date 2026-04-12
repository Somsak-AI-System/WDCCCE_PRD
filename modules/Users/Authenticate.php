<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/aicrm_crm/sugarcrm/modules/Users/Authenticate.php,v 1.10 2005/02/28 05:25:22 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('modules/Users/Users.php');
require_once('modules/Users/CreateUserPrivilegeFile.php');
require_once('include/logging.php');
require_once('user_privileges/audit_trail.php');

global $mod_strings, $default_charset;
global $list_max_user;
$focus = new Users();

// Add in defensive code here.
$focus->column_fields["user_name"] = to_html($_REQUEST['user_name']);
$user_password = vtlib_purify($_REQUEST['user_password']);

$login_mode = $_REQUEST["login_mode"];
$focus->load_user($user_password);
//echo $login_mode;exit();

if($focus->is_authenticated())
{
//check user===================================================================================

	$query = "SELECT first_login FROM aicrm_users WHERE id=?";
	$rs = $adb->pquery($query, [$focus->id]);
	$user = $adb->fetch_array($rs);

	if($user['first_login'] == '0'){
		$_SESSION['id'] = $focus->id;
		$_SESSION['user_password'] = $_REQUEST['user_password'];
		header("Location: updatepassword.php");
		exit();
	}

	$ipaddress=gethostbyaddr($_SERVER['REMOTE_ADDR'])." (".$_SERVER["REMOTE_ADDR"].")";
	$start_date=date("Y-m-d H:i:s");
	$end_time_H=date('H');
	$end_time_I=date('i');
	$result = $adb->query("SELECT limit_time FROM aicrm_organizationdetails");
	$limit_time = $adb->query_result($result, 0, 'limit_time');
	$end_time=date("Y-m-d H:i:s", mktime(date("H")  , date("i")+$limit_time, date("s"), date("m")  , date("d"), date("Y")));

	$_SESSION["user_id"]=$focus->id;
	$_SESSION["user_start_time"]= date("Y-m-d ").date('H:i:s');
	$_SESSION["user_end_time"]= date("Y-m-d H:i:s", mktime(date("H")  , date("i")+$limit_time, date("s"), date("m")  , date("d"), date("Y")));
	$_SESSION["ipaddress"]=$ipaddress;
	//echo $limit_time;exit;
	$sql="SELECT count(*) as count FROM ai_check_user_login where  user_id='".$focus->id."' and end_time>'".date("Y-m-d H:i:s")."' and status=0 ";
	$result = $adb->query($sql);
	$count = $adb->query_result($result, 0, 'count');
	
	if($count>0){
		//�մ user ����͡�ء���駷��ӡ�� login
		$sql="update ai_check_user_login set status=1 where user_id=?";
		$params = array($focus->id);
		$adb->pquery($sql, $params);

		$query = "insert into ai_check_user_login (user_id,username,start_time,end_time,ipaddress) values(?,?,?,?,?)";
		$params = array($focus->id,$focus->column_fields["user_name"],$start_date,$end_time,$ipaddress);
		$adb->pquery($query, $params);
		
		$queryhistory ="INSERT INTO ai_check_user_login_system (user_id ,username ,ipaddress ,use_date ,sysytem_name ,status) VALUES 
					('".$focus->id."', '".$focus->column_fields["user_name"]."', '".$ipaddress."', now() , 'CRM', '1')";
		$adb->query($queryhistory);
		
	}else{
		$sql="SELECT count(*) as count FROM ai_check_user_login where end_time > '".date("Y-m-d H:i:s")."' and status=0";
		//echo $sql;
		$result = $adb->query($sql);
		$dbversion = $adb->query_result($result, 0, 'count');
		$chk=$dbversion;
		if($chk==$list_max_user){
			$_SESSION['login_error'] = $mod_strings['ERR_USER_LOGIN'];
			header("Location: index.php");
			exit;
		}else{
			//�մ user ����͡�ء���駷��ӡ�� login
			$sql="update ai_check_user_login set status=1 where user_id=?";
			$params = array($focus->id);
			$adb->pquery($sql, $params);

			$query = "insert into ai_check_user_login (user_id,username,start_time,end_time,ipaddress) values(?,?,?,?,?)";
			$params = array($focus->id,$focus->column_fields["user_name"],$start_date,$end_time,$ipaddress);
			$adb->pquery($query, $params);
			
			$queryhistory ="INSERT INTO ai_check_user_login_system (user_id ,username ,ipaddress ,use_date ,sysytem_name ,status) VALUES 
					('".$focus->id."', '".$focus->column_fields["user_name"]."', '".$ipaddress."', now(), 'CRM', '1')";
			$adb->query($queryhistory);
		}
	}
	$sql="SELECT id as login_id FROM ai_check_user_login where user_id='".$focus->id."' and start_time='".$start_date."' and ipaddress='".$ipaddress."' and status=0";
	//echo $sql; exit;
	$result = $adb->query($sql);
	$login_id = $adb->query_result($result, 0, 'login_id');
	$_SESSION["login_id"]=$login_id;
//check user===================================================================================

	//Inserting entries for audit trail during login
	if($audit_trail == 'true')
	{
		if($record == '')
			$auditrecord = '';
		else
			$auditrecord = $record;

		$date_var = $adb->formatDate(date('Y-m-d H:i:s'), true);
 	    $query = "insert into aicrm_audit_trial values(?,?,?,?,?,?)";
		$params = array($adb->getUniqueID('aicrm_audit_trial'), $focus->id, 'Users','Authenticate','',$date_var);
		$adb->pquery($query, $params);
	}


	// Recording the login info
        $usip=$_SERVER['REMOTE_ADDR'];
        $intime=date("Y/m/d H:i:s");
        require_once('modules/Users/LoginHistory.php');
        $loghistory=new LoginHistory();
        $Signin = $loghistory->user_login($focus->column_fields["user_name"],$usip,$intime);

	//Security related entries start
	require_once('include/utils/UserInfoUtil.php');

	createUserPrivilegesfile($focus->id);

	unset($_SESSION['login_password']);
	unset($_SESSION['login_error']);
	unset($_SESSION['login_user_name']);

	$_SESSION['authenticated_user_id'] = $focus->id;
	$_SESSION['app_unique_key'] = $application_unique_key;

	// store the user's theme in the session
	if (isset($_REQUEST['login_theme'])) {
		$authenticated_user_theme = $_REQUEST['login_theme'];
	}
	elseif (isset($_REQUEST['ck_login_theme']))  {
		$authenticated_user_theme = $_REQUEST['ck_login_theme'];
	}
	else {
		$authenticated_user_theme = $default_theme;
	}

	// store the user's language in the session
	if (isset($_REQUEST['login_language'])) {
		$authenticated_user_language = $_REQUEST['login_language'];
	}
	elseif (isset($_REQUEST['ck_login_language']))  {
		$authenticated_user_language = $_REQUEST['ck_login_language'];
	}
	else {
		$authenticated_user_language = $default_language;
	}

	// If this is the default user and the default user theme is set to reset, reset it to the default theme value on each login
	if($reset_theme_on_default_user && $focus->user_name == $default_user_name)
	{
		$authenticated_user_theme = $default_theme;
	}
	if(isset($reset_language_on_default_user) && $reset_language_on_default_user && $focus->user_name == $default_user_name)
	{
		$authenticated_user_language = $default_language;
	}

	$_SESSION['aicrm_authenticated_user_theme'] = $authenticated_user_theme;
	$_SESSION['authenticated_user_language'] = $authenticated_user_language;

	$log->debug("authenticated_user_theme is $authenticated_user_theme");
	$log->debug("authenticated_user_language is $authenticated_user_language");
	$log->debug("authenticated_user_id is ". $focus->id);
    $log->debug("app_unique_key is $application_unique_key");


// Clear all uploaded import files for this user if it exists

	global $import_dir;

	$tmp_file_name = $import_dir. "IMPORT_".$focus->id;

	if (file_exists($tmp_file_name))
	{
		unlink($tmp_file_name);
	}
	$arr = $_SESSION['lastpage'];

//ekk edit ==================================================================================
	$sql="SELECT aicrm_users.* FROM aicrm_users where aicrm_users.id='".$focus->id."' ";
	$result = $adb->query($sql);
	$first_name = $adb->query_result($result, 0, 'first_name');
	$last_name = $adb->query_result($result, 0, 'last_name');
	//echo $login_mode; exit;
	$_SESSION['login_user_name'] = $focus->column_fields["user_name"];
	$_SESSION['fullname'] = $first_name.' '.$last_name;
	$_SESSION['lastlogin'] = date('d M Y');

	$sql_img="SELECT aicrm_attachments.*
		FROM aicrm_attachments
		LEFT JOIN aicrm_salesmanattachmentsrel ON aicrm_salesmanattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid
		WHERE aicrm_salesmanattachmentsrel.smid = '".$focus->id."' ";
	$result_img = $adb->query($sql_img);
	$attachmentsid = $adb->query_result($result_img, 0, 'attachmentsid');
	$path = $adb->query_result($result_img, 0, 'path');
	$imgname = $adb->query_result($result_img, 0, 'name');

	if($result_img->_numOfRows > 0){
		$_SESSION['imageuser'] = $site_URL.$path.$attachmentsid.'_'.$imgname;
	}else{
		$_SESSION['imageuser'] = '';
	}
	
	//echo $login_mode; exit;
	if(isset($_SESSION['lastpage'])){
		if($login_mode!=""){
			if($_SESSION["mobile"]!=""){
				header("Location: extend/home?mobile=".$_SESSION["mobile"]."");
			}else{
				header("Location: extend");
			}
		}else{
			header("Location: index.php?".$arr[0]);
		}
	}else{
		if($login_mode!=""){
			header("Location: extend");
		}else{
			header("Location: index.php");
		}
	}

}
else
{
	$_SESSION['login_user_name'] = $focus->column_fields["user_name"];
	$_SESSION['login_password'] = $user_password;
	$_SESSION['login_error'] = $mod_strings['ERR_INVALID_PASSWORD'];

	// go back to the login screen.
	// create an error message for the user.
	
	if($login_mode!=""){
		header("Location: extend/index.php");
	}else{
		header("Location: index.php");
	}
}
//ekk edit ==================================================================================

?>