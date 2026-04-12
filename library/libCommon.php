<?php

class libCommon{


	public function __construct(){
		include_once("config.inc.php");

	}

	public function get_quotes_user_request($acct_focus)
	{
		//echo sssl;
		global $current_user,$adb;
		//echo "<pre>";print_r($current_user);echo "</pre>";
		$section = $current_user->column_fields["section"];
		//echo $section;
		if($section=="DIAG"){
			$columnname = "cf_4104";
		}
		else if($section=="SCI"){
			$columnname = "cf_4105";
		}
		else if($section=="PCD"){
			$columnname = "cf_4118";
		}
		else if($section=="CCD"){
			$columnname = "cf_4119";
		}
		else if($section=="RND"){
			$columnname = "cf_4120";
		}
		else if($section=="CVD"){
			$columnname = "cf_4121";
		}
		else if($section=="PMD"){
			$columnname = "cf_4122";
		}
		else if($section=="INT"){
			$columnname = "cf_4123";
		}
		//echo "<pre>";print_r($acct_focus);echo "</pre>";
		$user = $acct_focus->column_fields[$columnname];
		//echo $user;
		if($user!=""){
			$sql = "select user_name as  username
								,phone_mobile as usertel
								,position as userposition
								from aicrm_users
								where deleted=0 ";
			$sql .= " and user_name = '".$user."' ";
			//echo $sql;
			$result = $adb->pquery($sql, array());
			//echo "<pre>";print_r($result);echo "</pre>";
			$quotes_user = $adb->query_result($result,0,"username");
			$quotes_user_tel = $adb->query_result($result,0,"usertel");
			$quotes_user_position = $adb->query_result($result,0,"userposition");
		}else{
			$quotes_user ="";
			$quotes_user_tel ="";
			$quotes_user_position ="";
		}		
		
		return array($quotes_user,$quotes_user_tel,$quotes_user_position);
	}
	
}


?>
