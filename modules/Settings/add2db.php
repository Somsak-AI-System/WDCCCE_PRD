<?php

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
* 
 ********************************************************************************/

require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');


	$uploaddir = $root_directory ."/test/logo/" ;// set this to wherever
	$uploaddirFooter = $root_directory ."/test/footer/" ;// set this to wherever
	$saveflag="true";
	$nologo_specified="true";
	$error_flag ="";
	$nologo_specified="false";
	$filedname = "binFile";
	$filedhidden = $_REQUEST["binFile_hidden"];
	list($logo_error_flag,$logo_savelogo,$logo_nologo_specified,$logo_filename) = saveimg($filedname,$filedhidden,$uploaddir);
	
	$filedname = "binFooterFile";
	$filedhidden = $_REQUEST["binFooterFile_hidden"];
	list($footer_error_flag,$footer_savelogo,$footer_nologo_specified,$footer_filename) = saveimg($filedname,$filedhidden,$uploaddirFooter);
	
	
	if($saveflag=="true")
	{
		$organization_name=from_html($_REQUEST['organization_name']);
		$org_name=$_REQUEST['org_name'];
		$organization_address=from_html($_REQUEST['organization_address']);
		$organization_city=from_html($_REQUEST['organization_city']);
		$organization_state=from_html($_REQUEST['organization_state']);
		$organization_code=from_html($_REQUEST['organization_code']);
		$organization_country=from_html($_REQUEST['organization_country']);
		$organization_phone=from_html($_REQUEST['organization_phone']);
		$organization_fax=from_html($_REQUEST['organization_fax']);
        $organization_tax=from_html($_REQUEST['organization_tax']);
		$organization_website=from_html($_REQUEST['organization_website']);
		$organization_logo=from_html($_REQUEST['organization_logo']);
		$organization_limit_time=from_html($_REQUEST['organization_limit_time']);
		$organization_payterm=from_html($_REQUEST['organization_payterm']);
		
		$organization_email=from_html($_REQUEST['organization_email']);
		$organization_purchase_cost=from_html($_REQUEST['organization_purchase_cost']);
		$organization_consultant_cost=from_html($_REQUEST['organization_consultant_cost']);
		$organization_cam_bath=from_html($_REQUEST['organization_cam_bath']);
		$organization_exp_year=from_html($_REQUEST['organization_exp_year']);
		$organization_sms_url=from_html($_REQUEST['organization_sms_url']);
		$organization_sms_sendername=from_html($_REQUEST['organization_sms_sendername']);
		$organization_sms_username=from_html($_REQUEST['organization_sms_username']);
		$organization_sms_password=from_html($_REQUEST['organization_sms_password']);
		$organization_questionnaire_backup=from_html($_REQUEST['organization_questionnaire_backup']);
		//echo $organization_purchase_cost;
		
		$organization_logoname=$logo_filename;
		if(!isset($organization_logoname))
			$organization_logoname="";
		
		$organization_footername=$footer_filename;
		if(!isset($organization_footername))
			$organization_footername="";

		$sql="SELECT * FROM aicrm_organizationdetails WHERE organizationname = ?";
		$result = $adb->pquery($sql, array($org_name));
		$org_name = decode_html($adb->query_result($result,0,'organizationname'));
		$org_logo = $adb->query_result($result,0,'logoname'); 
		$org_imgfootername = $adb->query_result($result,0,'imgfootername');

		if($org_name=='')
		{
			$sql="INSERT INTO aicrm_organizationdetails
				(organizationname, 
				address, 
				city, 
				state, 
				code, 
				country, 
				phone, 
				fax,
				tax,
				website, 
				logoname,
				limit_time,
				payterm,
				email, 
				purchase_cost,
				consultant_cost,
				cam_bath,
				exp_year,
				sms_url,
				sms_sendername,
				sms_username,
				sms_password,
				questionnaire_backup,
				imgfootername) 
				values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$params = array($organization_name,
				$organization_address,
				$organization_city,
				$organization_state,
				$organization_code,
				$organization_country,
				$organization_phone,
				$organization_fax,
                $organization_tax,
				$organization_website,
				$organization_logoname,
				$organization_limit_time,
				$organization_payterm,
				$organization_email,
				$organization_purchase_cost,
				$organization_consultant_cost,
				$organization_cam_bath,
				$organization_exp_year,
				$organization_sms_url,
				$organization_sms_sendername,
				$organization_sms_username,
				$organization_sms_password,
				$organization_questionnaire_backup,
				$organization_footername);
		}
		else
		{//echo "666";
			if($logo_savelogo=="true")
			{
				$organization_logoname=$logo_filename;
			}
			elseif($logo_savelogo=="false" && $logo_error_flag=="")
			{
				$logo_savelogo="true";
				$organization_logoname=$_REQUEST['PREV_FILE'];
			}
			else
			{
				$organization_logoname=$_REQUEST['PREV_FILE'];
			}	
			if($logo_nologo_specified=="true")
			{
				$logo_savelogo="true";
				$organization_logoname=$org_logo;
			}
			
			
			//#################### Footer ###################################
			if($footer_savelogo=="true")
			{
				$organization_footername=$footer_filename;
			}
			elseif($footer_savelogo=="false" && $footer_error_flag=="")
			{
				$footer_savelogo="true";
				$organization_footername=$_REQUEST['PREVFOOTER_FILE'];
			}
			else
			{
				$organization_footername=$_REQUEST['PREVFOOTER_FILE'];
			}
			if($footer_nologo_specified=="true")
			{
				$footer_savelogo="true";
				$organization_footername=$org_imgfootername;
			}
			//#################### Footer ###################################
			
			//echo $organization_email."  ".$organization_purchase_cost."  ".$organization_consultant_cost;
			$sql = "UPDATE aicrm_organizationdetails
				SET organizationname = ?, 
				address = ?, 
				city = ?, 
				state = ?, 
				code = ?, 
				country = ?, 
				phone = ?, 
				fax = ?,
				tax = ?, 
				website = ?,
				email = ?,
				limit_time=?, 
				cam_bath=?,
				exp_year=?,
				sms_url=?,
				sms_sendername=?,
				sms_username=?,
				sms_password=?,
				questionnaire_backup=?,
				logoname = ?, 
				imgfootername = ? 
					WHERE organizationname = ?";
			$params = array($organization_name,
				$organization_address,
				$organization_city,
				$organization_state,
				$organization_code,
				$organization_country,
				$organization_phone,
				$organization_fax,
                $organization_tax,
				$organization_website,
                $organization_email,
				$organization_limit_time,
				$organization_cam_bath,
				$organization_exp_year,
				$organization_sms_url,
				$organization_sms_sendername,
				$organization_sms_username,
				$organization_sms_password,
				$organization_questionnaire_backup
					, decode_html($organization_logoname)
					, decode_html($organization_footername)
					, $org_name);
					//echo $organization_sms_url;exit;
		}
		//echo $sql;
		//print_r($params);
		$adb->pquery($sql, $params);

		if($logo_savelogo=="true" && $footer_savelogo =="true" )
		{//echo "55";
			header("Location: index.php?parenttab=Settings&module=Settings&action=OrganizationConfig");
		}
		elseif($savelogo=="false" || $footer_savelogo=="false")
		{
			$msg = $logo_error_flag ." ".$footer_error_flag ;
    		header("Location: index.php?parenttab=Settings&module=Settings&action=EditCompanyDetails&flag=".$msg);
		}
	

	}
	
	//############### save ######################
	
	function saveimg($filedname,$filedhidden,$uploaddir)
	{
		//echo $filedhidden;
		$savelogo = true;
		
		$binFile = $_FILES[$filedname]['name'];
		if(isset($filedhidden)) {
			$filename = $filedhidden;
		} else {
			$filename = ltrim(basename(" ".$binFile));
		}
		//echo $filename;
		$filetype= $_FILES[$filedname]['type'];
		$filesize = $_FILES[$filedname]['size'];
		//echo $filetype;
		//echo $filesize;
		$filetype_array=explode("/",$filetype);
	
		$file_type_val=strtolower($filetype_array[1]);
		//echo $file_type_val;
		if($filesize != 0)
		{
			if (($file_type_val == "jpeg" ) || ($file_type_val == "gif" ) || ($file_type_val == "png") || ($file_type_val == "jpg" ) ||  ($file_type_val == "pjpeg" ) || ($file_type_val == "x-png") ) //Checking whether the file is an image or not
			{
				/*if(stristr($binFile, '.gif') != FALSE)
				{
					
					$savelogo="false";
					$error_flag ="1";
				}
				else if($result!=false)
				{*/
					$savelogo="true";
				//}
			}
			else
			{
				$savelogo="false";
				$error_flag ="1";
			}
	
		}
		else
		{
			$savelogo="false";
			if($filename != "")
				$error_flag ="2";
		}
	
		$errorCode =  $_FILES[$filedname]['error'];
		if($errorCode == 4)
		{
			$savelogo="false";
			$errorcode="";
			$error_flag="5";
			$nologo_specified="true";
		}
		else if($errorCode == 2)
		{
			$error_flag ="3";
			$savelogo="false";
			$nologo_specified="false";
		}
		else if($errorCode == 3 )
		{
			$error_flag ="4";
			$savelogo="false";
			$nologo_specified="false";
		}
	
		if($savelogo=="true")
		{
			//echo $filedname;
			//echo $uploaddir.$_FILES[$filedname]["name"];
			move_uploaded_file($_FILES[$filedname]["tmp_name"],$uploaddir.$_FILES[$filedname]["name"]);
		}
		return array($error_flag,$savelogo,$nologo_specified,$filename);
	}
?>

