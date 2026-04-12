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
require_once('modules/Users/config_ldap.php');
require_once('modules/Users/Users.php');
require_once('modules/Users/CreateUserPrivilegeFile.php');
require_once('include/logging.php');
require_once('user_privileges/audit_trail.php');

global $mod_strings, $default_charset;
global $list_max_user,$ldap_url,$ldap_domain,$ldap_dn,$roleid;
$focus = new Users();
error_reporting(E_ALL);          // place these two lines at the top of 
ini_set('display_errors', 0);    // the script you are debugging
/*$ldap_url = '192.168.0.12';
$ldap_domain = 'aisystem.co.th';
$ldap_dn = "dc=aisystem,dc=co,dc=th";
$roleid="H13";*/
//$username = "test1";
//$password = "qazxsw123$%^";
//$password = "qazxsw1%^";
$username =to_html($_REQUEST['user_name']);
$password =to_html($_REQUEST['user_password']);
$data=get_ldap($ldap_url,$ldap_domain,$ldap_dn,$username,$password);
//print_r($data);exit;
if($data["count"] > 0){
	//echo $user_frist_name."<br>";
	//echo $user_email;
	//exit;
//check User in Ai-CRM==========================================================	
	// Add in defensive code here.
	$focus->column_fields["user_name"] = to_html($_REQUEST['user_name']);
	$user_password = vtlib_purify($_REQUEST['user_password']);
	$focus->load_user($user_password);
	//echo "555";exit;
	//echo  $user_password;exit;
	if($focus->is_authenticated()){
		$result = $adb->query("
		SELECT aicrm_user2role.roleid
		FROM aicrm_users	
		left join aicrm_user2role on aicrm_user2role.userid=aicrm_users.id
		where aicrm_users.user_name='".to_html($_REQUEST['user_name'])."'
		");
		$roleid_user = $adb->query_result($result, 0, 'roleid');
		if($roleid_user=="H13"){
			echo "<script type='text/javascript'>window.location.replace('check_authenticate.php');</script>";
			exit;
		}
		//กรณีมีใน Ai-CRM-----------------------------------------
		//check user===================================================================================	
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
		$sql="SELECT count(*) as count FROM ai_check_user_login where  user_id='".$focus->id."' 
		and end_time>'".$start_date."' and ipaddress='".$ipaddress."'  and status<>'1'";
		$result = $adb->query($sql);
		$count = $adb->query_result($result, 0, 'count');
		if($count>0){
			$query = "update ai_check_user_login set start_time=?,end_time=? where user_id=? and ipaddress=? and status <>?";
			$params = array($start_date,$end_time,$focus->id,$ipaddress,1);				
			$adb->pquery($query, $params);
		}else{
			$sql="SELECT count(*) as count FROM ai_check_user_login where end_time>'".$start_date."' and status<>'1'";
			$result = $adb->query($sql);
			$dbversion = $adb->query_result($result, 0, 'count');
			$chk=$dbversion;
			if($chk==$list_max_user){
				$_SESSION['login_error1'] = $mod_strings['ERR_USER_LOGIN'];
				echo "<script type='text/javascript'>window.location.replace('index.php');</script>";
				exit;
			}else{
				$query = "insert into ai_check_user_login (user_id,username,start_time,end_time,ipaddress) values(?,?,?,?,?)";
				$params = array($focus->id,$focus->column_fields["user_name"],$start_date,$end_time,$ipaddress);				
				$adb->pquery($query, $params);
			}
		}
		$sql="SELECT id as login_id FROM ai_check_user_login where user_id='".$focus->id."' and start_time='".$start_date."' and ipaddress='".$ipaddress."' and status<>'1'";
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
		//Security related entries end
		session_unregister('login_password');
		session_unregister('login_error');
		session_unregister('login_user_name');
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
		if(isset($_SESSION['lastpage'])){
			if($login_mode!=""){
				header("Location: callcenter_main.php");
			}else{
				//header("Location: index.php?".$arr[0]);
				echo "<script type='text/javascript'>window.location.replace('index.php?'".$arr[0].");</script>";
				exit;	
			}		
		}else{
			if($login_mode!=""){
				header("Location: callcenter_main.php");
			}else{
				//header("Location: index.php");
				echo "<script type='text/javascript'>window.location.replace('index.php');</script>";
				exit;
			}
		}
	}else{
		//กรณีไม่มีใน Ai-CRM-----------------------------------------
		//Create AUto Users======================================================
		$result = $adb->query("select (id+1) as id from aicrm_users_seq");
	
		$id= $adb->query_result($result, 0, 'id');
		$user_name=$_REQUEST['user_name'];
		$salt = substr($user_name, 0, 2);
		$salt = '$1$' . $salt . '$';
		//echo "create->".$user_password."->".$salt."<br>";
		
		$encrypted_password = crypt($user_password, $salt);
		$is_admin="off";
		$user_password=$encrypted_password;
		$confirm_password=$encrypted_password;
		$first_name=$data[0]['displayname'][0];
		$last_name=$data[0]['displayname'][0];
		//$email1=$data[0]['mail'][0];
		$email1="";
		$status="Active";
		$activity_view="Today";
		$lead_view="Today";
		$currency_id="1";
		$hour_format="";
		$end_hour="";
		$start_hour="";
		$title="";
		$phone_work="";
		$department="";
		$phone_mobile="";
		$reports_to_id="";
		$phone_other="";
		$email2="";
		$phone_fax="";
		$yahoo_id="";
		$phone_home="";
		$date_format="dd-mm-yyyy";
		$signature="";
		$description="";
		$address_street="";
		$address_city="";
		$address_state="";
		$address_postalcode="";
		$address_country="";
		$imagename="";
		$internal_mailer="1";
		$reminder_interval="None";
		$cal_color="#E6FAD8";
		$date_entered=date('Y-m-d H:i:s');
		$crypt_type="MD5";
		$accesskey=vtws_generateRandomAccessKey(16);
		//print_r($accesskey);exit;
		//$currentuser_id = $this->db->getUniqueID("aicrm_users");
		$sql= "insert into aicrm_users (
		id, user_name, is_admin, user_password, confirm_password, first_name, last_name, email1, 
		status, activity_view, lead_view, currency_id, hour_format, end_hour, start_hour, title, phone_work, 
		department, phone_mobile, reports_to_id, phone_other, email2, phone_fax, yahoo_id, phone_home, 
		date_format, signature, description, address_street, address_city, address_state, address_postalcode, 
		address_country, imagename, internal_mailer, reminder_interval,
		cal_color,date_entered,crypt_type,accesskey) 
		values('".id."','".$user_name."','".$is_admin."','".$user_password."','".$confirm_password."','".$first_name."','".$last_name."','".$email1."',
		'".$status."','".$activity_view."','".$lead_view."','".$currency_id."','".$hour_format."','".$end_hour."','".$start_hour."','".$title."','".$phone_work."',
		'".$department."','".$phone_mobile."','".$reports_to_id."','".$phone_other."','".$email2."','".$phone_fax."','".$yahoo_id."','".$phone_home."',
		'".$date_format."','".$signature."','".$description."','".$address_street."','".$address_city."','".$address_state."','".$address_postalcode."',
		'".$address_country."','".$imagename."','".$internal_mailer."','".$reminder_interval."',
		'".$cal_color."','".$date_entered."','".$crypt_type."','".$accesskey."')
		";
		
		$adb->pquery($sql, array());
		//echo $sql."<br>";
		$sql="update aicrm_users_seq set id='".$id."'";
		$adb->pquery($sql, array());
		//echo $sql."<br>";
		$sql="insert into aicrm_user2role (userid, roleid) values('".$id."','".$roleid."')";
		$adb->pquery($sql, array());
		//echo $sql."<br>";
		$sql="insert into aicrm_asteriskextensions (userid, asterisk_extension, use_asterisk) values('".$id."','','1')";
		$adb->pquery($sql, array());
		
		createUserPrivilegesfile($id);
		createUserSharingPrivilegesfile($id);
		//echo $sql."<br>";
		//Create AUto Users======================================================
		$focus->column_fields["user_name"] = to_html($_REQUEST['user_name']);
		$user_password = vtlib_purify($_REQUEST['user_password']);
		$focus->load_user($user_password);
		//print_r($focus->is_authenticated());
		
		
		if($focus->is_authenticated()){
			echo "<script type='text/javascript'>window.location.replace('check_authenticate.php');</script>";
			exit;
			//check user===================================================================================	
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
			$sql="SELECT count(*) as count FROM ai_check_user_login where  user_id='".$focus->id."' 
			and end_time>'".$start_date."' and ipaddress='".$ipaddress."'  and status<>'1'";
			$result = $adb->query($sql);
			$count = $adb->query_result($result, 0, 'count');
			if($count>0){
				$query = "update ai_check_user_login set start_time=?,end_time=? where user_id=? and ipaddress=? and status <>?";
				$params = array($start_date,$end_time,$focus->id,$ipaddress,1);				
				$adb->pquery($query, $params);
			}else{
				$sql="SELECT count(*) as count FROM ai_check_user_login where end_time>'".$start_date."' and status<>'1'";
				$result = $adb->query($sql);
				$dbversion = $adb->query_result($result, 0, 'count');
				$chk=$dbversion;
				if($chk==$list_max_user){
					$_SESSION['login_error1'] = $mod_strings['ERR_USER_LOGIN'];
					echo "<script type='text/javascript'>window.location.replace('index.php');</script>";
					exit;
				}else{
					$query = "insert into ai_check_user_login (user_id,username,start_time,end_time,ipaddress) values(?,?,?,?,?)";
					$params = array($focus->id,$focus->column_fields["user_name"],$start_date,$end_time,$ipaddress);				
					$adb->pquery($query, $params);
				}
			}
			$sql="SELECT id as login_id FROM ai_check_user_login where user_id='".$focus->id."' and start_time='".$start_date."' and ipaddress='".$ipaddress."' and status<>'1'";
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
			//Security related entries end
			session_unregister('login_password');
			session_unregister('login_error');
			session_unregister('login_user_name');
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
			if(isset($_SESSION['lastpage'])){
				if($login_mode!=""){
					header("Location: callcenter_main.php");
				}else{
					//header("Location: index.php?".$arr[0]);
					echo "<script type='text/javascript'>window.location.replace('index.php?'".$arr[0].");</script>";
					exit;	
				}		
			}else{
				if($login_mode!=""){
					header("Location: callcenter_main.php");
				}else{
					//header("Location: index.php");
					echo "<script type='text/javascript'>window.location.replace('index.php');</script>";
					exit;
				}
			}
		}else{
			$_SESSION['login_error'] = $mod_strings['ERR_INVALID_PASSWORD'];
			echo "<script type='text/javascript'>window.location.replace('index.php');</script>";
			exit;	
		}
	}
	//ekk edit ==================================================================================	
//check User in Ai-CRM==========================================================	
}else{
	$_SESSION['login_error'] = $mod_strings['ERR_INVALID_PASSWORD_LDAP'];
	echo "<script type='text/javascript'>window.location.replace('index.php');</script>";
	exit;
}

?>