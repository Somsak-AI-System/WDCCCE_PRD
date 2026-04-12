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
 * $Header: /advent/projects/wesat/aicrm_crm/sugarcrm/include/utils/EditViewUtils.php,v 1.188 2005/04/29 05:5 * 4:39 rank Exp
 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php'); //new
require_once('include/utils/CommonUtils.php'); //new
require_once 'modules/PickList/DependentPickListUtils.php';
/** This function returns the aicrm_field details for a given aicrm_fieldname.
  * Param $uitype - UI type of the aicrm_field
  * Param $fieldname - Form aicrm_field name
  * Param $fieldlabel - Form aicrm_field label name
  * Param $maxlength - maximum length of the aicrm_field
  * Param $col_fields - array contains the aicrm_fieldname and values
  * Param $generatedtype - Field generated type (default is 1)
  * Param $module_name - module name
  * Return type is an array
  */

function getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields,$generatedtype,$module_name,$mode='', $typeofdata=null)
{
	global $log,$app_strings;
	$log->debug("Entering getOutputHtml(".$uitype.",". $fieldname.",". $fieldlabel.",". $maxlength.",". $col_fields.",".$generatedtype.",".$module_name.") method ...");
	global $adb,$log,$default_charset;
	global $theme;
	global $mod_strings;
	global $app_strings;
	global $current_user;

	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	require('user_privileges/user_privileges_'.$current_user->id.'.php');

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$fieldlabel = from_html($fieldlabel);
	$fieldvalue = Array();
	$final_arr = Array();

	if($fieldname=="taskstatus"){
		$value = $col_fields["eventstatus"];
	}else{
		$value = $col_fields[$fieldname];
	}
	
	$custfld = '';
	$ui_type[]= $uitype;
	$editview_fldname[] = $fieldname;

	// vtlib customization: Related type field
	if($uitype == '10') {
		global $adb;
		$fldmod_result = $adb->pquery('SELECT relmodule, status FROM aicrm_fieldmodulerel WHERE fieldid=
			(SELECT fieldid FROM aicrm_field, aicrm_tab WHERE aicrm_field.tabid=aicrm_tab.tabid AND fieldname=? AND name=? and aicrm_field.presence in (0,2))',
			Array($fieldname, $module_name));

		$entityTypes = Array();
		$parent_id = $value;
		for($index = 0; $index < $adb->num_rows($fldmod_result); ++$index) {
			$entityTypes[] = $adb->query_result($fldmod_result, $index, 'relmodule');
		}

		if(!empty($value)) {
			$valueType = getSalesEntityType($value);
			$displayValueArray = getEntityName($valueType, $value);
			if(!empty($displayValueArray)){
				foreach($displayValueArray as $key=>$value){
					$displayValue = $value;
				}
			}
		} else {
			$displayValue='';
			$valueType='';
			$value='';
		}

		$editview_label[] = Array('options'=>$entityTypes, 'selected'=>$valueType, 'displaylabel'=>getTranslatedString($fieldlabel, $module_name));
		$fieldvalue[] = Array('displayvalue'=>$displayValue,'entityid'=>$parent_id);

	} // END
	else if($uitype == 5 || $uitype == 6 || $uitype ==23)
	{
		$log->info("uitype is ".$uitype);
		if($value=='')
		{
			//modified to fix the issue in trac(http://vtiger.fosslabs.com/cgi-bin/trac.cgi/ticket/1469)
			if($fieldname != 'birthday' && $generatedtype != 2 && getTabid($module_name) !=14)// && $fieldname != 'due_date')//due date is today's date by default
				$disp_value=getNewDisplayDate();

			//Added to display the Contact - Support End Date as one year future instead of today's date -- 30-11-2005
			if($fieldname == 'support_end_date' && $_REQUEST['module'] == 'Contacts')
			{
				$addyear = strtotime("+1 year");
				global $current_user;
				$dat_fmt = (($current_user->date_format == '')?('dd-mm-yyyy'):($current_user->date_format));

				$disp_value = (($dat_fmt == 'dd-mm-yyyy')?(date('d-m-Y',$addyear)):(($dat_fmt == 'mm-dd-yyyy')?(date('m-d-Y',$addyear)):(($dat_fmt == 'yyyy-mm-dd')?(date('Y-m-d', $addyear)):(''))));
			}

			if($fieldname == 'validtill' && $_REQUEST['module'] == 'Quotes')
            {
                $disp_value = '';
            }

		}
		else
		{
			$disp_value = getDisplayDate($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
		if($uitype == 6)
		{
			if($col_fields['time_start']!='')
			{
				$curr_time = $col_fields['time_start'];
			}
			else
			{
				$curr_time = date('H:i',(time() + (5 * 60)));
			}
		}
		if($module_name == 'Events' && $uitype == 23)
		{
			if($col_fields['time_end']!='')
			{
				$curr_time = $col_fields['time_end'];
			}
			else
			{
				$endtime = time() + (10 * 60);
				$curr_time = date('H:i',$endtime);
			}
		}
		$fieldvalue[] = array($disp_value => $curr_time) ;
		if($uitype == 5 || $uitype == 23)
		{
			if($module_name == 'Events' && $uitype == 23)
			{
				$fieldvalue[] = array($date_format=>$current_user->date_format.' '.$app_strings['YEAR_MONTH_DATE']);
			}
			else
				$fieldvalue[] = array($date_format=>$current_user->date_format);
		}
		else
		{
			$fieldvalue[] = array($date_format=>$current_user->date_format.' '.$app_strings['YEAR_MONTH_DATE']);
		}
	}
	elseif($uitype == 16) {
		require_once 'modules/PickList/PickListUtils.php';
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);

		$fieldname = $adb->sql_escape_string($fieldname);
		$pick_query="select $fieldname from aicrm_$fieldname order by sortorderid";
		$params = array();
		$pickListResult = $adb->pquery($pick_query, $params);
		$noofpickrows = $adb->num_rows($pickListResult);

		$options = array();
		$pickcount=0;
		$found = false;
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$value = decode_html($value);
			$pickListValue=decode_html($adb->query_result($pickListResult,$j,strtolower($fieldname)));
			if($value == trim($pickListValue))
			{

				$chk_val = "selected";
				$pickcount++;
				$found = true;
			}
			else
			{
				$chk_val = '';
			}
			$pickListValue = to_html($pickListValue);
			if(isset($_REQUEST['file']) && $_REQUEST['file'] == 'QuickCreate')
				$options[] = array(htmlentities(getTranslatedString($pickListValue),ENT_QUOTES,$default_charset),$pickListValue,$chk_val );
			else
				$options[] = array(getTranslatedString($pickListValue),$pickListValue,$chk_val );
		}
		$fieldvalue [] = $options;
	}
	elseif($uitype == 15 || $uitype == 33){
		//echo $module_name; exit;
		if($fieldname=="approve_level1" || $fieldname=="approve_level2" || $fieldname=="approve_level3" || $fieldname=="approve_level4"){

			require_once 'modules/PickList/PickListUtils.php';
			$roleid=$current_user->roleid;
			$section=$current_user->column_fields["section"];
			$sql = "select  a.user_name, concat( first_name, ' ', last_name ) as user_name
					from aicrm_users as a
					where a.deleted=0
					and a.status='Active' 
					";
			if(($fieldname=="approve_level1" || $fieldname=="approve_level2" || $fieldname=="approve_level3" || $fieldname=="approve_level4" || $fieldname=="pjorder_employee" ) and $is_admin==false){
				$sql.= " and a.section = '".$section."'";
			}

			if($fieldname=="approve_level1"){
				$sql.= " and a.approve_level1='1'";
			}
			if($fieldname=="approve_level2"){
				$sql.= " and a.approve_level2='1'";
			}
			if($fieldname=="approve_level3"){
				$sql.= " and a.approve_level3='1'";
			}
			if($fieldname=="approve_level4"){
				$sql.= " and a.approve_level4='1'";
			}

			$sql.=" order by a.user_name";
			$result = $adb->pquery($sql,"");
			$count = $adb->num_rows($result);

			if($count) {
				while($resultrow = $adb->fetch_array($result)) {
					$arr[] = $resultrow["user_name"];
				}
			}
			$picklistValues =$arr;
			$valueArr = explode("|##|", $value);
			$pickcount = 0;

			if(!empty($picklistValues)){
				foreach($picklistValues as $order=>$pickListValue){

					if(in_array(trim($pickListValue),array_map("trim", $valueArr))){
						$chk_val = "selected";
						$pickcount++;
					}else{
						$chk_val = '';
					}
					if(isset($_REQUEST['file']) && $_REQUEST['file'] == 'QuickCreate'){
						$options[] = array(htmlentities(getTranslatedString($pickListValue),ENT_QUOTES,$default_charset),$pickListValue,$chk_val );
					}else{
						$options[] = array(getTranslatedString($pickListValue),$pickListValue,$chk_val );
					}
				}

				if($pickcount == 0 && !empty($value)){
					$options[] =  array($app_strings['LBL_NOT_ACCESSIBLE'],$value,'selected');
				}
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$fieldvalue [] = $options;

		}else if(($fieldname=="approver" || $fieldname=="approver2" || $fieldname=="approver3" || $fieldname=="f_approver") && ($module_name =='Expense')){
			require_once 'modules/PickList/PickListUtils.php';
			$roleid=$current_user->roleid;
			$section=$current_user->column_fields["section"];
			$sql = "select  a.user_name, concat( first_name, ' ', last_name ) as user_name
					from aicrm_users as a
					where a.deleted=0
					and a.status='Active' 
					";
			if(($fieldname=="approver" || $fieldname=="approver2" || $fieldname=="approver3") and $is_admin==false){
				$sql.= " and a.section = '".$section."'";
			}

			if($fieldname=="approver"){
				$sql.= " and a.approve_expense_level1='1'";
			}
			if($fieldname=="approver2"){
				$sql.= " and a.approve_expense_level2='1'";
			}
			if($fieldname=="approver3"){
				$sql.= " and a.approve_expense_level3='1'";
			}
			if($fieldname=="f_approver"){
				$sql.= " and a.approve_expense_level4='1'";
			}

			$sql.=" order by a.user_name";
			$result = $adb->pquery($sql,"");
			$count = $adb->num_rows($result);

			if($count) {
				while($resultrow = $adb->fetch_array($result)) {
					$arr[] = $resultrow["user_name"];
				}
			}
			$picklistValues =$arr;
			$valueArr = explode("|##|", $value);
			$pickcount = 0;

			if(!empty($picklistValues)){
				foreach($picklistValues as $order=>$pickListValue){

					if(in_array(trim($pickListValue),array_map("trim", $valueArr))){
						$chk_val = "selected";
						$pickcount++;
					}else{
						$chk_val = '';
					}
					if(isset($_REQUEST['file']) && $_REQUEST['file'] == 'QuickCreate'){
						$options[] = array(htmlentities(getTranslatedString($pickListValue),ENT_QUOTES,$default_charset),$pickListValue,$chk_val );
					}else{
						$options[] = array(getTranslatedString($pickListValue),$pickListValue,$chk_val );
					}
				}

				if($pickcount == 0 && !empty($value)){
					$options[] =  array($app_strings['LBL_NOT_ACCESSIBLE'],$value,'selected');
				}
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$fieldvalue [] = $options;

		}else if(($fieldname=="approver" || $fieldname=="approver2" || $fieldname=="approver3" || $fieldname=="f_approver") && ($module_name =='Samplerequisition')){
			require_once 'modules/PickList/PickListUtils.php';
			$roleid=$current_user->roleid;
			$section=$current_user->column_fields["section"];
			$sql = "select  a.user_name, concat( first_name, ' ', last_name ) as user_name
					from aicrm_users as a
					where a.deleted=0
					and a.status='Active' 
					";
			if($fieldname=="approver"){
				$sql.= " and a.approve_sample_level1='1'";
			}
			if($fieldname=="approver2"){
				$sql.= " and a.approve_sample_level2='1'";
			}
			if($fieldname=="approver3"){
				$sql.= " and a.approve_sample_level3='1'";
			}
			if($fieldname=="f_approver"){
				$sql.= " and a.approve_sample_level4='1'";
			}

			$sql.=" order by a.user_name";
			$result = $adb->pquery($sql,"");
			$count = $adb->num_rows($result);

			if($count) {
				while($resultrow = $adb->fetch_array($result)) {
					$arr[] = $resultrow["user_name"];
				}
			}
			$picklistValues =$arr;
			$valueArr = explode("|##|", $value);
			$pickcount = 0;

			if(!empty($picklistValues)){
				foreach($picklistValues as $order=>$pickListValue){

					if(in_array(trim($pickListValue),array_map("trim", $valueArr))){
						$chk_val = "selected";
						$pickcount++;
					}else{
						$chk_val = '';
					}
					if(isset($_REQUEST['file']) && $_REQUEST['file'] == 'QuickCreate'){
						$options[] = array(htmlentities(getTranslatedString($pickListValue),ENT_QUOTES,$default_charset),$pickListValue,$chk_val );
					}else{
						$options[] = array(getTranslatedString($pickListValue),$pickListValue,$chk_val );
					}
				}

				if($pickcount == 0 && !empty($value)){
					$options[] =  array($app_strings['LBL_NOT_ACCESSIBLE'],$value,'selected');
				}
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$fieldvalue [] = $options;

		}elseif($fieldname=="pjorder_employee"){

			require_once 'modules/PickList/PickListUtils.php';
			$roleid=$current_user->roleid;
			$section=$current_user->column_fields["section"];
			$sql = "select  a.user_name, concat(a.user_name,' [ ',first_name,' ', last_name ,' ] ') as user_name
					from aicrm_users as a
					where a.deleted=0
					and a.status='Active' 
					";
			$sql.=" order by a.user_name";
			$result = $adb->pquery($sql,"");
			$count = $adb->num_rows($result);

			if($count) {
				while($resultrow = $adb->fetch_array($result)) {
					$arr[] = $resultrow["user_name"];
				}
			}
			$picklistValues =$arr;
			$valueArr = explode("|##|", $value);
			$pickcount = 0;

			if(!empty($picklistValues)){
				foreach($picklistValues as $order=>$pickListValue){

					if(in_array(trim($pickListValue),array_map("trim", $valueArr))){
						$chk_val = "selected";
						$pickcount++;
					}else{
						$chk_val = '';
					}
					if(isset($_REQUEST['file']) && $_REQUEST['file'] == 'QuickCreate'){
						$options[] = array(htmlentities(getTranslatedString($pickListValue),ENT_QUOTES,$default_charset),$pickListValue,$chk_val );
					}else{
						$options[] = array(getTranslatedString($pickListValue),$pickListValue,$chk_val );
					}
				}

				if($pickcount == 0 && !empty($value)){
					$options[] =  array($app_strings['LBL_NOT_ACCESSIBLE'],$value,'selected');
				}
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$fieldvalue [] = $options;

		}elseif($fieldname=="related_sales_person"){

			require_once 'modules/PickList/PickListUtils.php';
			$roleid=$current_user->roleid;
			$section=$current_user->column_fields["section"];
			$sql = "select  a.user_name, concat(first_name,' ', last_name) as user_name
					from aicrm_users as a
					where a.deleted=0
					and a.status='Active' 
					";
			$sql.=" order by a.user_name";
			$result = $adb->pquery($sql,"");
			$count = $adb->num_rows($result);

			if($count) {
				while($resultrow = $adb->fetch_array($result)) {
					$arr[] = $resultrow["user_name"];
				}
			}
			$picklistValues =$arr;
			$valueArr = explode("|##|", $value);
			$pickcount = 0;

			if(!empty($picklistValues)){
				foreach($picklistValues as $order=>$pickListValue){

					if(in_array(trim($pickListValue),array_map("trim", $valueArr))){
						$chk_val = "selected";
						$pickcount++;
					}else{
						$chk_val = '';
					}
					if(isset($_REQUEST['file']) && $_REQUEST['file'] == 'QuickCreate'){
						$options[] = array(htmlentities(getTranslatedString($pickListValue),ENT_QUOTES,$default_charset),$pickListValue,$chk_val );
					}else{
						$options[] = array(getTranslatedString($pickListValue),$pickListValue,$chk_val );
					}
				}

				if($pickcount == 0 && !empty($value)){
					$options[] =  array($app_strings['LBL_NOT_ACCESSIBLE'],$value,'selected');
				}
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$fieldvalue [] = $options;


		}elseif($fieldname=="email_from_name"){
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $col_fields[$fieldname];
			$sql = "select concat(email_from_name,':',email_user) as from_name from aicrm_config_sendemail  
			where email_status='Active' and deleted=0 
			and email_type='account'
			order by email_from_name ";
			$pickListResult = $adb->pquery($sql, array());
			$noofpickrows = $adb->num_rows($pickListResult);
			$options = array();
			$found = false;
			$options[] =array("--None--","--None--","" );
			for($i=0;$i<$noofpickrows;$i++){
				$pickListValue=$adb->query_result($pickListResult,$i,'from_name');
				if($col_fields[$fieldname] == $pickListValue){
					$chk_val = "selected";
					$found = true;
				}else{
					$chk_val = '';
				}
				 $options[] =array(getTranslatedString($pickListValue),$pickListValue,$chk_val );
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$fieldvalue [] = $options;
		}elseif($fieldname=="email_reply_email"){
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $col_fields[$fieldname];
			$sql = "select concat(email_from_name,':',email_user) as from_name from aicrm_config_sendemail  
			where email_status='Active' and deleted=0 
			and email_type='reply'
			order by email_from_name ";
			$pickListResult = $adb->pquery($sql, array());
			$noofpickrows = $adb->num_rows($pickListResult);
			$options = array();
			$found = false;
			$options[] =array("--None--","--None--","" );
			for($i=0;$i<$noofpickrows;$i++){
				$pickListValue=$adb->query_result($pickListResult,$i,'from_name');
				if($col_fields[$fieldname] == $pickListValue){
					$chk_val = "selected";
					$found = true;
				}else{
					$chk_val = '';
				}
				 $options[] =array(getTranslatedString($pickListValue),$pickListValue,$chk_val );
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$fieldvalue [] = $options;
        }elseif($fieldname=="email_bounce"){
            $label_fld[] = getTranslatedString($fieldlabel, $module);
            $label_fld[] = $col_fields[$fieldname];
            $sql = "select id, concat(email_from_name,':',email_user) as from_name from aicrm_config_sendemail  
			where email_status='Active' and deleted=0 
			and email_type='bounce'
			order by id ";
            $pickListResult = $adb->pquery($sql, array());
            $noofpickrows = $adb->num_rows($pickListResult);
            $options = array();
            $found = false;
            $options[] =array("--None--","--None--","" );
            for($i=0;$i<$noofpickrows;$i++){
                $pickListValue=$adb->query_result($pickListResult,$i,'id');
                $pickListName=$adb->query_result($pickListResult,$i,'from_name');
                if($col_fields[$fieldname] == $pickListName){
                    $chk_val = "selected";
                    $found = true;
                }else{
                    $chk_val = '';
                }
                $options[] =array(getTranslatedString($pickListName),$pickListName,$chk_val );
            }
            //print_r($options);exit;
            $editview_label[]=getTranslatedString($fieldlabel, $module_name);
            $fieldvalue [] = $options;
        }elseif($fieldname=="email_server"){
            $label_fld[] = getTranslatedString($fieldlabel, $module);
            $label_fld[] = $col_fields[$fieldname];
            $sql = "select id, concat(email_from_name,':',email_server) as from_name from aicrm_config_sendemail  
			where email_status='Active' and deleted=0 
			and email_type='edm'
			order by id ";
            $pickListResult = $adb->pquery($sql, array());
            $noofpickrows = $adb->num_rows($pickListResult);
            $options = array();
            $found = false;
            $options[] =array("--None--","--None--","" );
            for($i=0;$i<$noofpickrows;$i++){
                $pickListValue=$adb->query_result($pickListResult,$i,'id');
                $pickListName=$adb->query_result($pickListResult,$i,'from_name');
                if($col_fields[$fieldname] == $pickListValue){
                    $chk_val = "selected";
                    $found = true;
                }else{
                    $chk_val = '';
                }
                $options[] =array(getTranslatedString($pickListName),$pickListValue,$chk_val );
            }
            //print_r($options);exit;
            $editview_label[]=getTranslatedString($fieldlabel, $module_name);
            $fieldvalue [] = $options;
		}elseif($fieldname=="sms_sender_name"){
            $label_fld[] = getTranslatedString($fieldlabel, $module);
            $label_fld[] = $col_fields[$fieldname];
            $sql = "select id,name,sms_sender , sms_url FROM aicrm_config_sender_sms WHERE 1 and sms_status='Active' and deleted=0 ";
            $pickListResult = $adb->pquery($sql, array());
            $noofpickrows = $adb->num_rows($pickListResult);
            $options = array();
            $found = false;
            $options[] =array("--None--","--None--","" );
            for($i=0;$i<$noofpickrows;$i++){
                $pickListValue=$adb->query_result($pickListResult,$i,'name');
                $pickListId=$adb->query_result($pickListResult,$i,'id');
                if($col_fields[$fieldname] == $pickListId){
                    $chk_val = "selected";
                    $found = true;
                }else{
                    $chk_val = '';
                }
                $options[] =array(getTranslatedString($pickListValue),$pickListId,$chk_val );
            }
            $editview_label[]=getTranslatedString($fieldlabel, $module_name);
            $fieldvalue [] = $options;
		}elseif($fieldname=="vender"){
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $col_fields[$fieldname];

			if($module_name=="Quotes"){
				$show_in_module = " and (show_in_module like '%Quotation%')";
			}elseif($module_name=="Purchasesorder"){
				$show_in_module = " and (show_in_module like '%Purchases Order%')";
			}elseif($module_name=="Goodsreceive"){
				$show_in_module = " and (show_in_module like '%Goods Receive%')";
			}else{
				$show_in_module = '';
			}
			$sql = "select * from aicrm_config_vendorbuyer where type='Vender' and deleted = 0 and status = 'Active'".$show_in_module;
			$pickListResult = $adb->pquery($sql, array());
			$noofpickrows = $adb->num_rows($pickListResult);
			$options = array();
			$found = false;
			$options[] =array("--None--","--None--","","data-id=''" );
			for($i=0;$i<$noofpickrows;$i++){
				$pickListValue=$adb->query_result($pickListResult,$i,'name');
				$pickListId = 'data-id="'.$adb->query_result($pickListResult,$i,'id').'"';
				if($col_fields[$fieldname] == $pickListValue){
					$chk_val = "selected";
					$found = true;
				}else{
					$chk_val = '';
				}
				 $options[] =array(getTranslatedString($pickListValue),$pickListValue,$chk_val,$pickListId);
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$fieldvalue [] = $options;
		}elseif($fieldname=="buyer"){
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $col_fields[$fieldname];
			if($module_name=="Quotes"){
				$show_in_module = " and (show_in_module like '%Quotation%')";
			}elseif($module_name=="Purchasesorder"){
				$show_in_module = " and (show_in_module like '%Purchases Order%')";
			}elseif($module_name=="Goodsreceive"){
				$show_in_module = " and (show_in_module like '%Goods Receive%')";
			}else{
				$show_in_module = '';
			}
			$sql = "select * from aicrm_config_vendorbuyer where type='Buyer' and deleted = 0 and status = 'Active'".$show_in_module;
			$pickListResult = $adb->pquery($sql, array());
			$noofpickrows = $adb->num_rows($pickListResult);
			$options = array();
			$found = false;
			$options[] =array("--None--","--None--","","data-id=''" );
			for($i=0;$i<$noofpickrows;$i++){
				$pickListValue=$adb->query_result($pickListResult,$i,'name');
				$pickListId = 'data-id="'.$adb->query_result($pickListResult,$i,'id').'"';
				if($col_fields[$fieldname] == $pickListValue){
					$chk_val = "selected";
					$found = true;
				}else{
					$chk_val = '';
				}
				 $options[] =array(getTranslatedString($pickListValue),$pickListValue,$chk_val,$pickListId);
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$fieldvalue [] = $options;
		}elseif($fieldname=="quotation_buyer"){
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $col_fields[$fieldname];
			$sql = "select * from aicrm_config_vendorbuyer where type='Buyer' and deleted = 0 and status = 'Active' and (show_in_module like '%Quotation%')  ";
			$pickListResult = $adb->pquery($sql, array());
			$noofpickrows = $adb->num_rows($pickListResult);
			$options = array();
			$found = false;
			$options[] =array("--None--","--None--","","data-id=''" );
			for($i=0;$i<$noofpickrows;$i++){
				$pickListValue=$adb->query_result($pickListResult,$i,'name');
				$pickListId = 'data-id="'.$adb->query_result($pickListResult,$i,'id').'"';
				if($col_fields[$fieldname] == $pickListValue){
					$chk_val = "selected";
					$found = true;
				}else{
					$chk_val = '';
				}
				 $options[] =array(getTranslatedString($pickListValue),$pickListValue,$chk_val,$pickListId);
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$fieldvalue [] = $options;
		}else{
			require_once 'modules/PickList/PickListUtils.php';
			$roleid=$current_user->roleid;
			$picklistValues = getAssignedPicklistValues($fieldname, $roleid, $adb);
			$valueArr = explode("|##|", $value);
			$pickcount = 0;

			if(!empty($picklistValues)){
				foreach($picklistValues as $order=>$pickListValue){
					if(in_array(trim($pickListValue),array_map("trim", $valueArr))){
						$chk_val = "selected";
						$pickcount++;
					}else{
						$chk_val = '';
					}
					if(isset($_REQUEST['file']) && $_REQUEST['file'] == 'QuickCreate'){
						$options[] = array(htmlentities(getTranslatedString($pickListValue),ENT_QUOTES,$default_charset),$pickListValue,$chk_val );
					}else{
						$options[] = array(getTranslatedString($pickListValue),$pickListValue,$chk_val );
					}
				}

				if($pickcount == 0 && !empty($value)){
					$options[] =  array($app_strings['LBL_NOT_ACCESSIBLE'],$value,'selected');
				}
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$fieldvalue [] = $options;
		}
	}
	elseif($uitype == 201)
	{
		if(isset($_REQUEST['parentid']) && $_REQUEST['parentid'] != '')
			$value = $_REQUEST['parentid'];

		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Accounts")
			{
				$sql = "select * from  aicrm_account where accountid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"accountname");
				$acc_selected = "selected";
			}
			elseif($parent_module == "Leads")
			{
				$sql = "select concat(firstname,' ',lastname) as full_name from  aicrm_leaddetails where leadid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"full_name");
				$leads_selected = "selected";
			}
			else
			{
				$parent_name = "";
				$value = "";
			}
		}
		$editview_label[] = array("Accounts","Leads");
		$editview_label[] = array($acc_selected,$leads_selected);
		$editview_label[] = array("Accounts","Leads");
		$fieldvalue[] = $parent_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 800){
		if($value != '')
		{
			$name = getAccountNameRel($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 801){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 802){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 803){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 804){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 805){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 806){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 807){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 808){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 809){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 810){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 811){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 812){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 813){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 814){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 815){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 816){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 817){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 818){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 819){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 820){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 821){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 822){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 823){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 824){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 825){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 826){
		if($value != '')
		{
			$name = getContact_id($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 901)
	{
		global $noof_group_rows;
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$sql = "select  a.productname as productname ,c.cf_566 as cf_566
					from aicrm_products as a
					left join aicrm_crmentity as b  on a.productid=b.crmid
					left join aicrm_productcf as c on a.productid=c.productid
					where b.deleted=0 order by a.productname ";
		$res=$adb->pquery($sql, array());
		$noofrows = $adb->num_rows($res);
		for($i=0;$i<$noofrows;$i++)
		{
			$productname[$i]['productname'] = ($adb->query_result($res,$i,'productname').' : '.$adb->query_result($res,$i,'cf_566'));
			$productname[$i]['product_select'] = $value;
		}
		$fieldvalue[]=$productname;
	}
	elseif($uitype == 902)
	{
		global $noof_group_rows;
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$sql = "select a.accountname from aicrm_account as a left join aicrm_crmentity as b  on a.accountid=b.crmid  where b.deleted=0 order by a.accountname ";
		$res=$adb->pquery($sql, array());
		$noofrows = $adb->num_rows($res);
		for($i=0;$i<$noofrows;$i++)
		{
			$accountname[$i]['accountname'] = $adb->query_result($res,$i,'accountname');
			$accountname[$i]['account_select'] = $value;
		}
		$fieldvalue[]=$accountname;
	}
	elseif($uitype == 903)
	{
		global $noof_group_rows;
		
		if ($fieldname=="quota1" || $fieldname=="quota_publisher" ){
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$section=$current_user->column_fields["section"];
			$sql = "select concat( a.first_name, ' ', a.last_name ) as full_name
					, a.id
					from aicrm_users as a 
					where  deleted=0  
					and bidder=1
					and  status='Active'
					";
			$sql .= " and section = '".$section."'  ";
			$sql .= " order by concat( a.first_name, ' ', a.last_name ) ";
	
			$res=$adb->pquery($sql, array());
			$noofrows = $adb->num_rows($res);
			for($i=0;$i<$noofrows;$i++)
			{
				$username[$i]['username'] = $adb->query_result($res,$i,'full_name');
				$username[$i]['user_select'] = $value;
			}
			$fieldvalue[]=$username;
		}
		else if ($fieldname=="quota_signa"){
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$section=$current_user->column_fields["section"];
			$sql = "select concat( a.first_name, ' ', a.last_name ) as full_name 
					from aicrm_users as a 
					where  deleted=0  
					and approve_level4=1
					and status='Active'
					";
			$sql .= " and section = '".$section."'  ";
			$sql .= " order by concat( a.first_name, ' ', a.last_name ) ";

			$res=$adb->pquery($sql, array());
			$noofrows = $adb->num_rows($res);
			for($i=0;$i<$noofrows;$i++)
			{
				$username[$i]['username'] = $adb->query_result($res,$i,'full_name');
				$username[$i]['user_select'] = $value;
			}
			$fieldvalue[]=$username;
		}elseif($module_name == 'Claim'){
			$query = "SELECT * FROM aicrm_role WHERE roleid=?";
			$rs = $adb->pquery($query, [$current_user->roleid]);
			$role = $adb->fetch_array($rs);

			$parentRole = explode('::', $role['parentrole']);
			$parentRole = implode("','", array_slice($parentRole, 0, (count($parentRole) - 1)));

			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$sql = "SELECT concat( aicrm_users.first_name_th, ' ', aicrm_users.last_name_th) AS full_name
					FROM aicrm_users
					INNER JOIN aicrm_user2role ON aicrm_user2role.userid = aicrm_users.id
					WHERE 1
					AND status = 'Active'
					AND deleted = 0";

			$res=$adb->pquery($sql, []);
			$noofrows = $adb->num_rows($res);
			for($i=0;$i<$noofrows;$i++)
			{
				$username[$i]['username'] = $adb->query_result($res,$i,'full_name');
				$username[$i]['user_select'] = $value;
			}
			$fieldvalue[]=$username;
		}else{
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$sql = "select concat( a.first_name, ' ', a.last_name ,' [',a.user_name,']') as full_name from aicrm_users as a left join aicrm_crmentity as b  on a.id=b.crmid   order by concat( a.first_name, ' ', a.last_name ) ";

			$res=$adb->pquery($sql, array());
			$noofrows = $adb->num_rows($res);
			for($i=0;$i<$noofrows;$i++)
			{
				$username[$i]['username'] = $adb->query_result($res,$i,'full_name');
				$username[$i]['user_select'] = $value;
			}
			$fieldvalue[]=$username;
			//echo "<pre>";print_r($fieldvalue); echo "</pre>";
		}

	}
	elseif($uitype == 904){
		if($value != '' && $value != 0)
		{
			$name = getProject($value);

		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 906){
		if($_REQUEST['module'] == 'HelpDesk')
		{
			if(isset($_REQUEST['product_id']) & $_REQUEST['product_id'] != '')
				$value = $_REQUEST['product_id'];
		}
		elseif(isset($_REQUEST['branchsid']) & $_REQUEST['branchsid'] != '')
			$value = $_REQUEST['branchsid'];

		if($value != '')
		{
			$branchs_name = getBranchName($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$branchs_name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 912){
		if(isset($_REQUEST['product_id1']) & $_REQUEST['product_id1'] != '')
				$value = $_REQUEST['product_id1'];

		if($value != '')
		{
			$product_name = getProductName($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$product_name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 914)
	{
		if(isset($_REQUEST['event_id']) && $_REQUEST['event_id'] != '')
			$value = $_REQUEST['event_id'];

		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Job")
			{
				$sql = "select * from  aicrm_jobs where jobid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"job_name");
				$job_selected = "selected";
			}
			elseif($parent_module == "HelpDesk")
			{
				$sql = "select * from  aicrm_troubletickets where ticketid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"ticket_no");
				$case_selected = "selected";
			}
			elseif($parent_module == "Projects")
			{
				$sql = "select * from  aicrm_projects where projectsid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"projects_name");
				$project_selected = "selected";
			}
			elseif($parent_module == "Deal")
			{
				$sql = "select * from  aicrm_deal where dealid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"deal_name");
				$deal_selected = "selected";
			}
			else
			{
				$parent_name = "";
				$value = "";
			}

		}

		$editview_label[] = array("Deal","Case","Project Order");
		$editview_label[] = array($deal_selected,$case_selected,$project_selected);
		$editview_label[] = array("Deal","HelpDesk","Projects");
		//echo "<pre>"; print_r($editview_label); echo "</pre>"; exit;
		$fieldvalue[] = $parent_name;
		$fieldvalue[] = $value;
	}	
	elseif($uitype == 921){
		if(isset($_REQUEST['prevaccid']) & $_REQUEST['prevaccid'] != '')
			$value = $_REQUEST['prevaccid'];

		if($value != '')
		{
			$name = getAccountPrevName($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 923 || $uitype == 924 ){
		//telephone country code
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$salt_value = $col_fields["telephonecountrycode"];
		$fieldvalue[] = $value;
		$fieldvalue[] = $salt_value;
	}
	elseif($uitype == 925 || $uitype == 926 ){
		//mobile country code
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$salt_value = $col_fields["mobilecountrycode"];
		$fieldvalue[] = $value;
		$fieldvalue[] = $salt_value;
	}
	elseif($uitype == 927 || $uitype == 928 ){
		//fax country code
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$salt_value = $col_fields["faxcountrycode"];
		$fieldvalue[] = $value;
		$fieldvalue[] = $salt_value;
	}
	elseif($uitype == 929){
		if($value != '')
		{
			$name = getAccountNameField($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 930){
		if($value != '')
		{
			$code = getAccountcoderms($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$code;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 931){
		if($value != '')
		{
			$code = getContactcode($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$code;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 934){
		if($value != '')
		{
			$name = getProject($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 935){
		if($value != '')
		{
			$name = getSerial($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 936){
		if($value != '')
		{
			$name = getSparepart($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 937){
		if($value != '')
		{
			$name = geterrorno($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 938){
		if($value != '')
		{
			$name = getjob($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 939){
		if($value != '')
		{
			$name = getcase($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}elseif($uitype == 910) {
        if ($value != '') {
            $name = getquestionnairetemplatename($value);
        }
        $editview_label[] = getTranslatedString($fieldlabel, $module_name);
        $fieldvalue[] = $name;
        $fieldvalue[] = $value;
	} elseif($uitype == 941){
		if($value != '')
		{
			$name = getplant($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	
	elseif($uitype == 943){
		if($value != '')
		{
			$name = getLeadName($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 944){
		if($value != '')
		{
			$name = getactivity($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 963){
		if($value != '')
		{
			$name = getinspection_template_name($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 301){
		if($value != '')
		{
			$name = getdealname($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 302){
		if($value != '')
		{
			$name = getcompetitorname($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 303){
		if($value != '')
		{
			$name = getpromotionvouchername($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 304){
		if($value != '')
		{
			$name = getpromotion($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 305){
		if($value != '')
		{
			$name = getsalesorderno($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 306){
		if($value != '')
		{
			$name = getpremuimproductno($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 307){
		if($value != '')
		{
			$name = getquotesno($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 308){
		if($value != '')
		{
			$name = getservicerequest($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 309){
		if($value != '')
		{
			$name = getticket($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 310){
		if($value != '' && $value != 0)
		{
			$name = getactivity_name($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 946){
		if($value != '')
		{
			$name = getquestionnairetemplatename($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 947){
		if($value != '')
		{
			$name = getquestionnairename($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 948){
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[] = $value;
	}
	elseif($uitype == 949){
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[] = $value;
	}
	elseif($uitype == 997)
  	{
  		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
 		if( $col_fields['record_id'] != "")
  		{
 		    //This query is for Products only
			if($module_name == 'Order')
 		    {
			    $query = 'select aicrm_attachments.path, aicrm_attachments.attachmentsid, aicrm_attachments.name ,aicrm_crmentity.setype from aicrm_order 
			    	left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_order.orderid 
			    	inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid 
			    	inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid 
			    	where aicrm_crmentity.setype="Image 997" and aicrm_order.orderid=?';
 		    }else{

				$query="select aicrm_attachments.*,aicrm_crmentity.setype from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid where aicrm_crmentity.setype='Image 997' and aicrm_seattachmentsrel.crmid=?";
			}

 		    $result_image = $adb->pquery($query, array($col_fields['record_id']));
 		    for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
 		    {
			    $image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
 			    $image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
			    $image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));
 			    $image_path_array[] = $adb->query_result($result_image,$image_iter,'path');
 		    }
 		    if(is_array($image_array))
 			    for($img_itr=0;$img_itr<count($image_array);$img_itr++)
 			    {
 				    $fieldvalue[] = array('name'=>$image_array[$img_itr],'path'=>$image_path_array[$img_itr].$image_id_array[$img_itr]."_","orgname"=>$image_orgname_array[$img_itr]);
 			    }
 		    else
 			    $fieldvalue[] = '';
  		}
  		else
  			$fieldvalue[] = '';
  	}
	elseif($uitype == 998)
  	{
  		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
 		if( $col_fields['record_id'] != "")
  		{
 		    //This query is for Products only
			if($module_name == 'Job')
 		    {
			    $query = 'select aicrm_attachments.path, aicrm_attachments.attachmentsid, aicrm_attachments.name ,aicrm_crmentity.setype from aicrm_jobs left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_jobs.jobid inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid where aicrm_crmentity.setype="Image 998" and aicrm_jobs.jobid=?';
 		    }else{

				$query="select aicrm_attachments.*,aicrm_crmentity.setype from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid where aicrm_crmentity.setype='Image 998' and aicrm_seattachmentsrel.crmid=?";
			}

 		    $result_image = $adb->pquery($query, array($col_fields['record_id']));
 		    for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
 		    {
			    $image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
 			    $image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
			    $image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));
 			    $image_path_array[] = $adb->query_result($result_image,$image_iter,'path');
 		    }
 		    if(is_array($image_array))
 			    for($img_itr=0;$img_itr<count($image_array);$img_itr++)
 			    {
 				    $fieldvalue[] = array('name'=>$image_array[$img_itr],'path'=>$image_path_array[$img_itr].$image_id_array[$img_itr]."_","orgname"=>$image_orgname_array[$img_itr]);
 			    }
 		    else
 			    $fieldvalue[] = '';
  		}
  		else
  			$fieldvalue[] = ''; 
  	}
  	elseif($uitype == 999)
  	{
  		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
 		if( $col_fields['record_id'] != "")
  		{
 		   //This query is for Products only
  			if($module_name == 'Job')
 		    {
			    $query = 'select aicrm_attachments.path, aicrm_attachments.attachmentsid, aicrm_attachments.name ,aicrm_crmentity.setype from aicrm_jobs left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_jobs.jobid inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid where aicrm_crmentity.setype="Image 999" and aicrm_jobs.jobid=?';
 		    }else{
				$query="select aicrm_attachments.*,aicrm_crmentity.setype from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid where aicrm_crmentity.setype='Image 999' and aicrm_seattachmentsrel.crmid=?";
			}

 		    $result_image = $adb->pquery($query, array($col_fields['record_id']));
 		    for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
 		    {
			    $image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
 			    $image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
			    $image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));
 			    $image_path_array[] = $adb->query_result($result_image,$image_iter,'path');
 		    }
 		    if(is_array($image_array))
 			    for($img_itr=0;$img_itr<count($image_array);$img_itr++)
 			    {
 				$fieldvalue[] = array('name'=>$image_array[$img_itr],'path'=>$image_path_array[$img_itr].$image_id_array[$img_itr]."_","orgname"=>$image_orgname_array[$img_itr]);
 			    }
 		    else
 			    $fieldvalue[] = '';
  		}
  		else
  			$fieldvalue[] = '';
  	}
	elseif($uitype == 17)
	{
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue [] = $value;
	}
	elseif($uitype == 85) //added for Skype by Minnie
	{
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue [] = $value;
	}elseif($uitype == 19 || $uitype == 20)
	{
		if(isset($_REQUEST['body']))
		{
			$value = ($_REQUEST['body']);
		}

		if($fieldname == 'terms_conditions')//for default Terms & Conditions
		{
			//Assign the value from focus->column_fields (if we create Invoice from SO the SO's terms and conditions will be loaded to Invoice's terms and conditions, etc.,)
			$value = $col_fields['terms_conditions'];

			//if the value is empty then only we should get the default Terms and Conditions
			if($value == '' && $mode != 'edit')
				$value=getTermsandConditions();

		}else if($fieldname == 'quote_termcondition'){
			//Assign the value from focus->column_fields (if we create Invoice from SO the SO's terms and conditions will be loaded to Invoice's terms and conditions, etc.,)
			$value = $col_fields['quote_termcondition'];

			//if the value is empty then only we should get the default Terms and Conditions
			if($value == '' && $mode != 'edit')
				$value=getTermsandConditions_en();

		}

		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue [] = $value;
	}
	elseif($uitype == 21 || $uitype == 24)
	{
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue [] = $value;
	}
	elseif($uitype == 22)
	{
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[] = $value;
	}
	elseif($uitype == 52 || $uitype == 77)
	{
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		global $current_user;
		if($value != '')
		{
			$assigned_user_id = $value;
		}
		else
		{
			$assigned_user_id = $current_user->id;
		}
		if($uitype == 52)
		{
			$combo_lbl_name = 'assigned_user_id';
		}
		elseif($uitype == 77)
		{
			$combo_lbl_name = 'assigned_user_id1';
		}

		//Control will come here only for Products - Handler and Quotes - Inventory Manager
		if($is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid($module_name)] == 3 or $defaultOrgSharingPermission[getTabid($module_name)] == 0))
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $assigned_user_id,'private'), $assigned_user_id);
		}
		else
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $assigned_user_id), $assigned_user_id);
		}
		$fieldvalue [] = $users_combo;
	}
	elseif($uitype == 53)
	{
		global $noof_group_rows;
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		//Security Checks
		if($fieldlabel == 'Assigned To' && $is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid($module_name)] == 3 or $defaultOrgSharingPermission[getTabid($module_name)] == 0))
		{
			$result=get_current_user_access_groups($module_name);
		}
		else
		{
			$result = get_group_options();
		}
		if($result) $nameArray = $adb->fetch_array($result);

		if($value != '' && $value != 0)
			$assigned_user_id = $value;
		else{
			if($value=='0'){
				if (isset($col_fields['assigned_group_info']) && $col_fields['assigned_group_info'] != '') {
					$selected_groupname = $col_fields['assigned_group_info'];
				} else {
					$record = $col_fields["record_id"];
					$module = $col_fields["record_module"];
					$selected_groupname = getGroupName($record, $module);
				}
			}else
				$assigned_user_id = $current_user->id;
		}

		if($fieldlabel == 'Assigned To' && $is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid($module_name)] == 3 or $defaultOrgSharingPermission[getTabid($module_name)] == 0))
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $assigned_user_id,'private'), $assigned_user_id);
		}
		else
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $assigned_user_id), $assigned_user_id);
		}

		if($noof_group_rows!=0)
		{
			if($fieldlabel == 'Assigned To' && $is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid($module_name)] == 3 or $defaultOrgSharingPermission[getTabid($module_name)] == 0))
			{
				$groups_combo = get_select_options_array(get_group_array(FALSE, "Active", $assigned_user_id,'private'), $assigned_user_id);
			}
			else
			{
				$groups_combo = get_select_options_array(get_group_array(FALSE, "Active", $assigned_user_id), $assigned_user_id);
			}
		}
		$fieldvalue[]=$users_combo;
		$fieldvalue[] = $groups_combo;
	}
	elseif($uitype == 51 || $uitype == 50 || $uitype == 73)
	{
		if($_REQUEST['convertmode'] != 'update_quote_val' && $_REQUEST['convertmode'] != 'update_so_val')
		{
			if(isset($_REQUEST['account_id']) && $_REQUEST['account_id'] != '')
				$value = $_REQUEST['account_id'];
		}
		if($value != '') {
			$account_name = getAccountName($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$account_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 54)
	{
		$options = array();
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$pick_query="select * from aicrm_groups";
		$pickListResult = $adb->pquery($pick_query, array());
		$noofpickrows = $adb->num_rows($pickListResult);
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$pickListValue=$adb->query_result($pickListResult,$j,"name");

			if($value == $pickListValue)
			{
				$chk_val = "selected";
			}
			else
			{
				$chk_val = '';
			}
			$options[] = array($pickListValue => $chk_val );
		}
		$fieldvalue[] = $options;
	}
	elseif($uitype == 55 || $uitype == 255){
		require_once 'modules/PickList/PickListUtils.php';
		if($uitype==255){
			$fieldpermission = getFieldVisibilityPermission($module_name, $current_user->id,'firstname');
		}
		if($uitype == 255 && $fieldpermission == '0'){
			$fieldvalue[] = '';
		}else{
			$roleid=$current_user->roleid;
			$picklistValues = getAssignedPicklistValues('salutationtype', $roleid, $adb);
			$pickcount = 0;
			$salt_value = $col_fields["salutationtype"];
			foreach($picklistValues as $order=>$pickListValue){
				if($salt_value == trim($pickListValue)){
					$chk_val = "selected";
					$pickcount++;
				}else{
					$chk_val = '';
				}
				if(isset($_REQUEST['file']) && $_REQUEST['file'] == 'QuickCreate'){
					$options[] = array(htmlentities(getTranslatedString($pickListValue),ENT_QUOTES,$default_charset),$pickListValue,$chk_val );
				}else{
					$options[] = array(getTranslatedString($pickListValue),$pickListValue,$chk_val);
				}
			}
			if($pickcount == 0 && $salt_value != ''){
				$options[] =  array($app_strings['LBL_NOT_ACCESSIBLE'],$salt_value,'selected');
			}
			$fieldvalue [] = $options;
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[] = $value;
	}
	elseif($uitype == 59){
		if($_REQUEST['module'] == 'HelpDesk')
		{
			if(isset($_REQUEST['product_id']) & $_REQUEST['product_id'] != '')
				$value = $_REQUEST['product_id'];
		}
		elseif(isset($_REQUEST['parent_id']) & $_REQUEST['parent_id'] != '')
			$value = $_REQUEST['parent_id'];


		if($value != '')
		{
			$product_name = getProductName($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$product_name;
		$fieldvalue[]=$value;
	}
	elseif($uitype == 64)
	{
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
		$fieldvalue[] = $value;
	}
	elseif($uitype == 156)
	{
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[] = $value;
		$fieldvalue[] = $is_admin;
	}
	elseif($uitype == 56)
	{
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[] = $value;
	}
	elseif($uitype == 57){
		if($value != ''){
			$contact_name = getContactName($value);
		}elseif(isset($_REQUEST['contact_id']) && $_REQUEST['contact_id'] != ''){
			if($_REQUEST['module'] == 'Contacts' && $fieldname = 'contact_id'){
				$contact_name = '';
			}else{
				$value = $_REQUEST['contact_id'];
				$contact_name = getContactName($value);
			}

		}
		//Checking for contacts duplicate
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[] = $contact_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 58)
	{
		if($value != '')
		{
			$campaign_name = getCampaignName($value);
		}
		elseif(isset($_REQUEST['campaignid']) && $_REQUEST['campaignid'] != '')
		{
			if($_REQUEST['module'] == 'Campaigns' && $fieldname = 'campaignid')
			{
				$campaign_name = '';
			}
			else
			{
				$value = $_REQUEST['campaignid'];
				$campaign_name = getCampaignName($value);
			}

		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$campaign_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 61)
	{
		if($value != '')
		{
			$assigned_user_id = $value;
		}
		else
		{
			$assigned_user_id = $current_user->id;
		}
		if($module_name == 'Emails' && $col_fields['record_id'] != '')
		{
			$attach_result = $adb->pquery("select * from aicrm_seattachmentsrel where crmid = ?", array($col_fields['record_id']));
			//to fix the issue in mail attachment on forwarding mails
			if(isset($_REQUEST['forward']) && $_REQUEST['forward'] != '')
				global $att_id_list;
			for($ii=0;$ii < $adb->num_rows($attach_result);$ii++)
			{
				$attachmentid = $adb->query_result($attach_result,$ii,'attachmentsid');
				if($attachmentid != '')
				{
					$attachquery = "select * from aicrm_attachments where attachmentsid=?";
					$attachmentsname = $adb->query_result($adb->pquery($attachquery, array($attachmentid)),0,'name');
					if($attachmentsname != '')
						$fieldvalue[$attachmentid] = '[ '.$attachmentsname.' ]';
					if(isset($_REQUEST['forward']) && $_REQUEST['forward'] != '')
						$att_id_list .= $attachmentid.';';
				}

			}
		}else
		{
			if($col_fields['record_id'] != '')
			{
				$attachmentid=$adb->query_result($adb->pquery("select * from aicrm_seattachmentsrel where crmid = ?", array($col_fields['record_id'])),0,'attachmentsid');
				if($col_fields[$fieldname] == '' && $attachmentid != '')
				{
					$attachquery = "select * from aicrm_attachments where attachmentsid=?";
					$value = $adb->query_result($adb->pquery($attachquery, array($attachmentid)),0,'name');
				}
			}
			if($value!='')
				$filename=' [ '.$value. ' ]';

			if($filename != '')
				$fieldvalue[] = $filename;
			if($value != '')
				$fieldvalue[] = $value;
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
	}
	elseif($uitype == 28){
		if($col_fields['record_id'] != '')
			{
				$attachmentid=$adb->query_result($adb->pquery("select * from aicrm_seattachmentsrel where crmid = ?", array($col_fields['record_id'])),0,'attachmentsid');
				if($col_fields[$fieldname] == '' && $attachmentid != '')
				{
					$attachquery = "select * from aicrm_attachments where attachmentsid=?";
					$value = $adb->query_result($adb->pquery($attachquery, array($attachmentid)),0,'name');
				}
			}
			if($value!='' && $module_name != 'Documents')
				$filename=' [ '.$value. ' ]';
			elseif($value != '' && $module_name == 'Documents')
				$filename= $value;
			if($filename != '')
				$fieldvalue[] = $filename;
			if($value != '')
				$fieldvalue[] = $value;

		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
	}
	elseif($uitype == 69)
  	{
  		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
 		if( $col_fields['record_id'] != "")
  		{
 		    //This query is for Products only
 		    if($module_name == 'Products')
 		    {
			    $query = 'select aicrm_attachments.path, aicrm_attachments.attachmentsid, aicrm_attachments.name ,aicrm_crmentity.setype from aicrm_products left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_products.productid inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid where aicrm_crmentity.setype="Products Image" and productid=?';
 		    }
            else if($module_name == 'Smartquestionnaire' || $module_name == 'Questionnairetemplate')
            {
                $query="select aicrm_attachments.*,aicrm_crmentity.setype from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid where aicrm_crmentity.setype='".$fieldname."' and aicrm_seattachmentsrel.crmid=? order by aicrm_crmentity.crmid desc";
            }
			else if($module_name == 'KnowledgeBase')
 		    {
			    $query = 'select
				aicrm_attachments.path,
				aicrm_attachments.attachmentsid,
				aicrm_attachments.name ,
				aicrm_crmentity.setype
				from aicrm_knowledgebasecf
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_knowledgebasecf.knowledgebaseid
				inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
				where aicrm_crmentity.setype="KnowledgeBase Image"
				and knowledgebaseid=?';
 		    }
 		    else if($module_name == 'Order')
 		    {
			    $query = 'select
				aicrm_attachments.path,
				aicrm_attachments.attachmentsid,
				aicrm_attachments.name ,
				aicrm_crmentity.setype
				from aicrm_order
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_order.orderid
				inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
				where aicrm_crmentity.setype="Order Attachment"
				and aicrm_order.orderid=?';
 		    }
 		    else if($module_name == 'HelpDesk')
 		    {
			    $query = 'select
				aicrm_attachments.path,
				aicrm_attachments.attachmentsid,
				aicrm_attachments.name ,
				aicrm_crmentity.setype
				from aicrm_troubletickets
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_troubletickets.ticketid
				inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
				where aicrm_crmentity.setype="HelpDesk Image"
				and aicrm_troubletickets.ticketid=?';
 		    }
 		    else if($module_name == 'Leads')
 		    {
			    $query = 'select
				aicrm_attachments.path,
				aicrm_attachments.attachmentsid,
				aicrm_attachments.name ,
				aicrm_crmentity.setype
				from aicrm_leaddetails
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_leaddetails.leadid
				inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
				where aicrm_crmentity.setype="Leads Image"
				and aicrm_leaddetails.leadid=?';
 		    }
 		    else if($module_name == 'Campaigns')
 		    {
			    $query = 'select
				aicrm_attachments.path,
				aicrm_attachments.attachmentsid,
				aicrm_attachments.name ,
				aicrm_crmentity.setype
				from aicrm_campaign
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_campaign.campaignid
				inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
				where aicrm_crmentity.setype="Campaigns Image"
				and aicrm_campaign.campaignid=?';
 		    }
 		    else if($module_name == 'Deal')
 		    {
			    $query = 'select
				aicrm_attachments.path,
				aicrm_attachments.attachmentsid,
				aicrm_attachments.name ,
				aicrm_crmentity.setype
				from aicrm_deal
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_deal.dealid
				inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
				where aicrm_crmentity.setype="Deal Image"
				and aicrm_deal.dealid=?';
 		    }
 		    else if($module_name == 'Faq')
 		    {
			    $query = 'select
				aicrm_attachments.path,
				aicrm_attachments.attachmentsid,
				aicrm_attachments.name ,
				aicrm_crmentity.setype
				from aicrm_faq
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_faq.faqid
				inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
				where aicrm_crmentity.setype="Faq Image"
				and aicrm_faq.faqid=?';
 		    }
 		    else if($module_name == 'Competitorproduct')
 		    {
			    $query = 'select
				aicrm_attachments.path,
				aicrm_attachments.attachmentsid,
				aicrm_attachments.name ,
				aicrm_crmentity.setype
				from aicrm_competitorproduct
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_competitorproduct.competitorproductid
				inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
				where aicrm_crmentity.setype="Competitorproduct Image"
				and aicrm_competitorproduct.competitorproductid=?';
 		    }
 		    else if($module_name == 'Premuimproduct')
 		    {
			    $query = 'select
				aicrm_attachments.path,
				aicrm_attachments.attachmentsid,
				aicrm_attachments.name ,
				aicrm_crmentity.setype
				from aicrm_premuimproduct
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_premuimproduct.premuimproductid
				inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
				where aicrm_crmentity.setype="Premuimproduct Image"
				and aicrm_premuimproduct.premuimproductid=?';
 		    }
 		    else if($module_name == 'Promotion')
 		    {
			    $query = 'select
				aicrm_attachments.path,
				aicrm_attachments.attachmentsid,
				aicrm_attachments.name ,
				aicrm_crmentity.setype
				from aicrm_promotion
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_promotion.promotionid
				inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
				where aicrm_crmentity.setype="Promotion Image"
				and aicrm_promotion.promotionid=?';
 		    }
 		    else if($module_name == 'Promotionvoucher')
 		    {
			    $query = 'select
				aicrm_attachments.path,
				aicrm_attachments.attachmentsid,
				aicrm_attachments.name ,
				aicrm_crmentity.setype
				from aicrm_promotionvoucher
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_promotionvoucher.promotionvoucherid
				inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
				where aicrm_crmentity.setype="Promotionvoucher Image"
				and aicrm_promotionvoucher.promotionvoucherid=?';
 		    }
 		    else if($module_name == 'Questionnaire')
 		    {
			    $query = 'select
				aicrm_attachments.path,
				aicrm_attachments.attachmentsid,
				aicrm_attachments.name ,
				aicrm_crmentity.setype
				from aicrm_questionnaire
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_questionnaire.questionnaireid
				inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
				where aicrm_crmentity.setype="Questionnaire Image"
				and aicrm_questionnaire.questionnaireid=?';
 		    }
 		    else if($module_name == 'Announcement')
 		    {
			    $query = 'select
				aicrm_attachments.path,
				aicrm_attachments.attachmentsid,
				aicrm_attachments.name ,
				aicrm_crmentity.setype
				from aicrm_announcement
				left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_announcement.announcementid
				inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
				where aicrm_crmentity.setype="Announcement Image"
				and aicrm_announcement.announcementid=?';
 		    }
 		    else
		    {
			    $query="select aicrm_attachments.*,aicrm_crmentity.setype from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid where aicrm_crmentity.setype='".$module_name." Image' and aicrm_seattachmentsrel.crmid=?";
 		    }
 		    $result_image = $adb->pquery($query, array($col_fields['record_id']));
 		    for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
 		    {
			    $image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
			    //decode_html  - added to handle UTF-8   characters in file names
			    //urlencode    - added to handle special characters like #, %, etc.,
 			    $image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
			    $image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));

 			    $image_path_array[] = $adb->query_result($result_image,$image_iter,'path');
 		    }
 		    if(is_array($image_array))
 			    for($img_itr=0;$img_itr<count($image_array);$img_itr++)
 			    {
 				    $fieldvalue[] = array('name'=>$image_array[$img_itr],'path'=>$image_path_array[$img_itr].$image_id_array[$img_itr]."_","orgname"=>$image_orgname_array[$img_itr]);
 			    }
 		    else
 			    $fieldvalue[] = '';
  		}
  		else
  			$fieldvalue[] = '';
  	}
	elseif($uitype == 62)
	{
		if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != '')
			$value = $_REQUEST['parent_id'];
		if($value != '')
			$parent_module = getSalesEntityType($value);
		if(isset($_REQUEST['account_id']) && $_REQUEST['account_id'] != '')
		{
			$parent_module = "Accounts";
			$value = $_REQUEST['account_id'];
		}
		if($parent_module != 'Contacts')
		{
			if($parent_module == "Leads" || $parent_module == "LeadManagement" )
			{
				$parent_name = getLeadName($value);
				$lead_selected = "selected";

			}
			elseif($parent_module == "Accounts")
			{
				$sql = "select * from  aicrm_account where accountid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"accountname");
				$account_selected = "selected";

			}
			elseif($parent_module == "Potentials")
			{
				$sql = "select * from  aicrm_potential where potentialid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"potentialname");
				$potential_selected = "selected";

			}
			elseif($parent_module == "Products")
			{
				$sql = "select * from  aicrm_products where productid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name= $adb->query_result($result,0,"productname");
				$product_selected = "selected";

			}
			elseif($parent_module == "Quotes")
			{
				$sql = "select * from  aicrm_quotes where quoteid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name= $adb->query_result($result,0,"subject");
				$quote_selected = "selected";
			}elseif($parent_module == "HelpDesk")
			{
				$sql = "select * from  aicrm_troubletickets where ticketid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name= $adb->query_result($result,0,"title");
				$ticket_selected = "selected";
			}
			elseif($parent_module == "PriceList")
			{
				$sql = "select * from  aicrm_pricelists where pricelistid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"pricelist_name");
				$pricelist_selected = "selected";
			}
			elseif($parent_module == "Activitys")
			{
				$sql = "select * from  aicrm_activitys where activitysid=".$value;
				echo $sql;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"activitys_name");
				$activitys_selected = "selected";
			}
			elseif($parent_module == "Opportunity")
			{
				$sql = "select * from  aicrm_opportunity where opportunityid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"opportunity_name");
				$opportunity_selected = "selected";
			}
			elseif($parent_module == "KnowledgeBase")
			{
				$sql = "select * from  aicrm_knowledgebase where knowledgebaseid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"knowledgebase_name");
				$knowledgebase_selected = "selected";
			}
			elseif($parent_module == "Quotation")
			{
				$sql = "select * from  aicrm_quotation where quotationid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"quotation_name");
				$quotation_selected = "selected";
			}
			elseif($parent_module == "Job")
			{
				$sql = "select * from  aicrm_jobs where jobid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"job_name");
				$job_selected = "selected";
			}
			elseif($parent_module == "SmartSms")
			{
				$sql = "select * from  aicrm_smartsms where smartsmsid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"smartsms_name");
				$smartsms_selected = "selected";
			}
			elseif($parent_module == "Smartquestionnaire")
            {
                $sql = "select * from aicrm_smartquestionnaire where smartquestionnaireid=?";
                $result = $adb->pquery($sql, array($value));
                $parent_name = $adb->query_result($result,0,"smartquestionnaire_name");
                $smartquestionnaire_selected = "selected";
            }

		}

		$editview_label[] = array($app_strings['COMBO_LEADS'],
	                              $app_strings['COMBO_ACCOUNTS'],
	                              $app_strings['COMBO_POTENTIALS'],
	                              $app_strings['COMBO_PRODUCTS'],
								  $app_strings['COMBO_ACTIVITYS'],
								  $app_strings['COMBO_OPPORTUNITY'],
								  $app_strings['COMBO_KNOWLEDGE_BASE'],
								  $app_strings['COMBO_QUOTATION'],
								  $app_strings['COMBO_PRICELIST'],
								  $app_strings['COMBO_JOB'],
								  $app_strings['COMBO_QUOTES'],
								  $app_strings['COMBO_HELPDESK']
    					);
        $editview_label[] = array($lead_selected,
                                  $account_selected,
			  					  $potential_selected,
                                  $product_selected,
								  $quote_selected,
								  $ticket_selected
                        );
        $editview_label[] = array("Leads&action=Popup","Accounts&action=Popup","Potentials&action=Popup","Products&action=Popup","Activitys&action=Popup","Opportunity&action=Popup","KnowledgeBase&action=Popup","Quotation&action=Popup","PriceList&action=Popup","Job&action=Popup","SmartSms&action=Popup","Quotes&action=Popup","HelpDesk&action=Popup");
		$fieldvalue[] =$parent_name;
		$fieldvalue[] =$value;
	}
	elseif($uitype == 66)
	{
		if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != '')
			$value = $_REQUEST['parent_id'];
		if($value != '')
			$parent_module = getSalesEntityType($value);
		// Check for aicrm_activity type if task orders to be added in select option
		$act_mode = $_REQUEST['activity_mode'];

		if($parent_module != "Contacts")
		{
			if($parent_module == "Leads" || $parent_module == "LeadManagement")
			{
				$parent_name = getLeadName($value);
				$lead_selected = "selected";

			}
			elseif($parent_module == "Accounts")
			{
				$sql = "select * from  aicrm_account where accountid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"accountname");
				$account_selected = "selected";

			}
			elseif($parent_module == "Potentials")
			{
				$sql = "select * from  aicrm_potential where potentialid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"potentialname");
				$potential_selected = "selected";

			}
			elseif($parent_module == "Branch") {
				$sql = "select * from  aicrm_branchs where branchsid=".$value;
				$result = $adb->query($sql);
				$branchs_name = $adb->query_result($result,0,"branchs_name");
				$branchs_selected = "selected";
			}
			elseif($parent_module == "Projects") {
				$sql = "select * from  aicrm_projects where projectsid=".$value;
				$result = $adb->query($sql);
				$projects_name = $adb->query_result($result,0,"projects_name");
				$projects_selected = "selected";
			}
			elseif($parent_module == "Job")
			{
				$sql = "select * from  aicrm_jobs where jobid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"job_name");
				$job_selected = "selected";
			}
			elseif($parent_module == "PriceList")
			{
				$sql = "select * from  aicrm_pricelists where pricelistid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"pricelist_name");
				$pricelist_selected = "selected";
			}
			elseif($parent_module == "Activitys")
			{
				$sql = "select * from  aicrm_activitys where activitysid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"activitys_name");
				$activitys_selected = "selected";
			}
			elseif($parent_module == "Opportunity")
			{
				$sql = "select * from  aicrm_opportunity where opportunityid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"opportunity_name");
				$opportunity_selected = "selected";
			}
			elseif($parent_module == "KnowledgeBase")
			{
				$sql = "select * from  aicrm_knowledgebase where knowledgebaseid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"knowledgebase_name");
				$knowledgebase_selected = "selected";
			}
			elseif($parent_module == "Quotation")
			{
				$sql = "select * from  aicrm_quotation where quotationid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"quotation_name");
				$quotation_selected = "selected";
			}
			elseif($parent_module == "SmartSms")
			{
				$sql = "select * from  aicrm_smartsms where smartsmsid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"smartsms_name");
				$smartsms_selected = "selected";
			}
			elseif($parent_module == "Smartemail") {
				$sql = "select * from  aicrm_smartemail where smartemailid=".$value;
				$result = $adb->query($sql);
				$smartemail_name = $adb->query_result($result,0,"smartemail_name");
				$smartemail_selected = "selected";
			}
			elseif($parent_module == "Competitor") {
				$sql = "select * from  aicrm_competitor where competitorid=".$value;
				$result = $adb->query($sql);
				$competitor_name = $adb->query_result($result,0,"competitor_name");
				$competitor_selected = "selected";
			}
			elseif($parent_module == "Competitorproduct") {
				$sql = "select * from  aicrm_competitorproduct where competitorproductid=".$value;
				$result = $adb->query($sql);
				$competitorproduct_name = $adb->query_result($result,0,"competitorproduct_name");
				$competitorproduct_selected = "selected";
			}
			elseif($parent_module == "Premuimproduct") {
				$sql = "select * from  aicrm_premuimproduct where premuimproductid=".$value;
				$result = $adb->query($sql);
				$premuimproduct_name = $adb->query_result($result,0,"premuimproduct_name");
				$premuimproduct_selected = "selected";
			}
			elseif($parent_module == "Servicerequest") {
				$sql = "select * from  aicrm_servicerequest where servicerequestid=".$value;
				$result = $adb->query($sql);
				$servicerequest_name = $adb->query_result($result,0,"servicerequest_name");
				$servicerequest_selected = "selected";
			}
			elseif($parent_module == "Serial") {
				$sql = "select * from  aicrm_serial where serialid=".$value;
				$result = $adb->query($sql);
				$serial_name = $adb->query_result($result,0,"serial_name");
				$serial_selected = "selected";
			}
			elseif($parent_module == "Seriallist") {
				$sql = "select * from  aicrm_seriallist where seriallistid=".$value;
				$result = $adb->query($sql);
				$seriallist_name = $adb->query_result($result,0,"seriallist_name");
				$seriallist_selected = "selected";
			}
			elseif($parent_module == "Inspection") {
				$sql = "select * from aicrm_inspection where inspectionid=".$value;
				$result = $adb->query($sql);
				$inspection_name = $adb->query_result($result,0,"inspection_name");
				$inspection_selected = "selected";
			}
			elseif($parent_module == "Inspectiontemplate") {
				$sql = "select * from aicrm_inspectiontemplate where inspectiontemplateid=".$value;
				$result = $adb->query($sql);
				$inspectiontemplate_name = $adb->query_result($result,0,"inspectiontemplate_name");
				$inspectiontemplate_selected = "selected";
			}
			elseif($parent_module == "Errors") {
				$sql = "select * from  aicrm_errors where errorsid=".$value;
				$result = $adb->query($sql);
				$errors_name = $adb->query_result($result,0,"errors_name");
				$errors_selected = "selected";
			}
			elseif($parent_module == "Errorslist") {
				$sql = "select * from  aicrm_errorslist where errorslistid=".$value;
				$result = $adb->query($sql);
				$errorslist_name = $adb->query_result($result,0,"errorslist_name");
				$errorslist_selected = "selected";
			}
			elseif($parent_module == "Sparepart") {
				$sql = "select * from  aicrm_sparepart where sparepartid=".$value;
				$result = $adb->query($sql);
				$sparepart_name = $adb->query_result($result,0,"sparepart_name");
				$sparepart_selected = "selected";
			}
			elseif($parent_module == "Sparepartlist") {
				$sql = "select * from  aicrm_sparepartlist where sparepartlistid=".$value;
				$result = $adb->query($sql);
				$sparepartlist_name = $adb->query_result($result,0,"sparepartlist_name");
				$sparepartlist_selected = "selected";
			}
			elseif($parent_module == "HelpDesk")
			{
				$sql = "select title from aicrm_troubletickets where ticketid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"title");
				$ticket_selected = "selected";
			}
			elseif($parent_module == "Faq")
			{
				$sql = "select * from  aicrm_faq where faqid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"faq_name");
				$faq_selected = "selected";
			}
			elseif($parent_module == "Plant")
			{
				$sql = "select * from  aicrm_plant where plantid=".$value;
				$result = $adb->query($sql);
				$parent_name= $adb->query_result($result,0,"plant_name");
				$faq_selected = "selected";
			}
			elseif($parent_module == "Order")
			{
				$sql = "select * from  aicrm_order where orderid=".$value;
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"order_name");
				$order_selected = "selected";
			}

			elseif($parent_module == "Deal")
			{
				$sql = "select * from aicrm_deal where dealid=".$value;
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"deal_name");
				$deal_selected = "selected";
			}
			elseif($parent_module == "Promotion")
			{
				$sql = "select * from  aicrm_promotion where promotionid=".$value;
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"promotion_name");
				$promotion_selected = "selected";
			}
			elseif($parent_module == "Promotionvoucher")
			{
				$sql = "select * from  aicrm_promotionvoucher where promotionvoucherid=".$value;
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"promotionvoucher_name");
				$promotionvoucher_selected = "selected";
			}
			elseif($parent_module == "Voucher")
			{
				$sql = "select * from aicrm_voucher where voucherid=".$value;
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"voucher_name");
				$voucher_selected = "selected";
			}
			elseif($parent_module == "Questionnaire")
			{
				$sql = "select * from aicrm_questionnaire where questionnaireid=".$value;
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"questionnaire_name");
				$questionnaire_selected = "selected";
			}
			elseif($parent_module == "Questionnairetemplate")
			{
				$sql = "select * from  aicrm_questionnairetemplate where questionnairetemplateid=".$value;
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"questionnairetemplate_name");
				$questionnairetemplate_selected = "selected";
			}
			elseif($parent_module == "Questionnaireanswer")
            {
                $sql = "select * from aicrm_questionnaireanswer where questionnaireanswerid=?";
                $result = $adb->pquery($sql, array($value));
                $parent_name = $adb->query_result($result,0,"questionnaireanswer_name");
                $questionnaireanswer_selected = "selected";
            }
			elseif($parent_module == "Announcement")
			{
				$sql = "select * from  aicrm_announcement where announcementid=".$value;
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"announcement_name");
				$announcement_selected = "selected";
			}
			elseif($parent_module == "Point")
			{
				$sql = "select * from  aicrm_point where pointid=".$value;
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"point_name");
				$point_selected = "selected";
			}
			elseif($parent_module == "Redemption")
			{
				$sql = "select * from  aicrm_redemption where redemptionid=".$value;
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"redemption_name");
				$redemption_selected = "selected";
			}
			elseif($parent_module == "Salesorder")
			{
				$sql = "select * from  aicrm_salesorder where salesorderid=".$value;
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"salesorder_name");
				$salesorder_selected = "selected";
			}
			elseif($parent_module == "Projects") {
				$sql = "select * from  aicrm_projects where projectsid=".$value;
				$result = $adb->query($sql);
				$projects_name = $adb->query_result($result,0,"projects_name");
				$projects_selected = "selected";
			}
			elseif($parent_module == "Expense") {
				$sql = "select * from  aicrm_expense where expenseid=".$value;
				$result = $adb->query($sql);
				$expense_name = $adb->query_result($result,0,"expense_name");
				$expense_selected = "selected";
			}
			elseif($parent_module == "Purchasesorder") {
				$sql = "select * from  aicrm_purchasesorder where purchasesorderid=".$value;
				$result = $adb->query($sql);
				$purchasesorder_name = $adb->query_result($result,0,"purchasesorder_name");
				$purchasesorder_selected = "selected";
			}
			elseif($parent_module == "Samplerequisition") {
				$sql = "select * from  aicrm_samplerequisition where samplerequisitionid=".$value;
				$result = $adb->query($sql);
				$samplerequisition_name = $adb->query_result($result,0,"samplerequisition_name");
				$samplerequisition_selected = "selected";
			}
			elseif($parent_module == "Goodsreceive") {
				$sql = "select * from  aicrm_goodsreceive where goodsreceiveid=".$value;
				$result = $adb->query($sql);
				$goodsreceive_name = $adb->query_result($result,0,"goodsreceive_name");
				$goodsreceive_selected = "selected";
			}
			elseif($parent_module == "Marketingtools") {
				$sql = "select * from  aicrm_marketingtools where marketingtoolsid=".$value;
				$result = $adb->query($sql);
				$marketingtools_name = $adb->query_result($result,0,"marketingtools_name");
				$marketingtools_selected = "selected";
			}
			elseif($act_mode == "Task")
			{
				if($parent_module == "Campaigns")
				{
					$sql = "select campaignname from aicrm_campaign where campaignid=?";
					$result = $adb->pquery($sql, array($value));
					$parent_name = $adb->query_result($result,0,"campaignname");
					$campaign_selected = "selected";
				}
				if($parent_module == "Quotes")
				{
					$sql = "select * from  aicrm_quotes where quoteid=?";
					$result = $adb->pquery($sql, array($value));
					$parent_name = $adb->query_result($result,0,"subject");
					$quote_selected = "selected";
				}
			}
			$fieldvalue[] =$parent_name;
			$fieldvalue[] = $value;
		}
		$editview_label[0] = array(
			$app_strings['COMBO_LEADS'],
			$app_strings['COMBO_ACCOUNTS'],
			$app_strings['COMBO_POTENTIALS'],
			$app_strings['COMBO_ACTIVITYS'],
			$app_strings['COMBO_OPPORTUNITY'],
			$app_strings['COMBO_KNOWLEDGE_BASE'],
			$app_strings['COMBO_QUOTATION'],
			$app_strings['COMBO_JOB'],
			$app_strings['COMBO_BRANCH'],
			$app_strings['COMBO_PROJECTS'],
			$app_strings['COMBO_SMARTEMAIL'],
			$app_strings['COMBO_SERIAL'],
			$app_strings['COMBO_ERRORS'],
			$app_strings['COMBO_ERRORSLIST'],
			$app_strings['COMBO_SPAREPART'],
			$app_strings['COMBO_SPAREPARTLIST'],
			$app_strings['COMBO_COMPETITOR'],
			$app_strings['COMBO_FAQ'],
			$app_strings['COMBO_PLANT'],
			$app_strings['COMBO_ORDER'],
			$app_strings['COMBO_DEAL'],
			$app_strings['COMBO_PROMOTION'],
			$app_strings['COMBO_VOUCHER'],
			$app_strings['COMBO_QUESTIONNAIRE'],
			$app_strings['COMBO_QUESTIONNAIRETEMOLATE'],
			$app_strings['COMBO_ANNOUNCEMENT'],
            $app_strings['COMBO_SMARTQUESTIONNAIRE'],
            $app_strings['COMBO_QUESTIONNAIREANSWER'],
            $app_strings['COMBO_PROMOTIONVOUCHER'],
            $app_strings['COMBO_COMPETITORPRODUCT'],
            $app_strings['COMBO_PREMUIMPRODUCT'],
            $app_strings['COMBO_SERVICEREQUEST'],
            $app_strings['COMBO_POINT'],
            $app_strings['COMBO_REDEMPTION'],
            $app_strings['COMBO_SALESORDER'],
            $app_strings['COMBO_SERIALLIST'],
            $app_strings['COMBO_INSPECTION'],
            $app_strings['COMBO_INSPECTIONTEMPLATE'],
            $app_strings['COMBO_EXPENSE'],
            $app_strings['COMBO_PURCHASESORDER'],
            $app_strings['COMBO_SAMPLEREQUISITION'],
            $app_strings['COMBO_GOODSRECEIVE'],
			$app_strings['COMBO_MARKETINGTOOLS'],
		);
		$editview_label[1] = array(
			$lead_selected,
			$account_selected,
			$potential_selected
		);
		$editview_label[2] = array(
			"Leads&action=Popup",
			"Accounts&action=Popup",
			"Potentials&action=Popup",
			"Opportunity&action=Popup",
			"KnowledgeBase&action=Popup",
			"Quotation&action=Popup",
			"PriceList&action=Popup",
			"Job&action=Popup",
			"SmartSms&action=Popup",
			"Branchs&action=Popup",
			"Projects&action=Popup",
			"Smartemail&action=Popup",
			"Competitor&action=Popup",
			"Serial&action=Popup",
			"Errors&action=Popup",
			"Errorslist&action=Popup",
			"Sparepart&action=Popup",
			"Sparepartlist&action=Popup",
			"Faq&action=Popup",
			"Plant&action=Popup",
			"Order&action=Popup",
			"Deal&action=Popup",
			"Promotion&action=Popup",
			"Voucher&action=Popup",
			"Questionnaire&action=Popup",
			"Questionnairetemplate&action=Popup",
			"Announcement&action=Popup",
            "Smartquestionnaire&action=Popup",
            "Questionnaireanswer&action=Popup",
            "Promotionvoucher&action=Popup",
            "Competitorproduct&action=Popup",
            "Premuimproduct&action=Popup",
            "Servicerequest&action=Popup",
            "Redemption&action=Popup",
            "Point&action=Popup",
            "Salesorder&action=Popup",
            "Seriallist&action=Popup",
            "Inspection&action=Popup",
            "Inspectiontemplate&action=Popup",
            "Expense&action=Popup",
            "Purchasesorder&action=Popup",
            "Samplerequisition&action=Popup",
            "Goodsreceive&action=Popup",
			"Marketingtools&action=Popup",
		);

		if($act_mode == "Task"){
            array_push($editview_label[0],
                $app_strings['COMBO_QUOTES'],
				$app_strings['COMBO_CAMPAIGNS'],
				$app_strings['COMBO_HELPDESK']
    		);
			array_push($editview_label[1],
                $quote_selected,
				$campaign_selected,
				$ticket_selected
           	);
            array_push($editview_label[2],"Quotes&action=Popup","Campaigns&action=Popup","HelpDesk&action=Popup");
        }
		elseif($act_mode == "Events")
		{
			array_push($editview_label[0],$app_strings['COMBO_HELPDESK']);
			array_push($editview_label[1],$ticket_selected);
			array_push($editview_label[2],"HelpDesk&action=Popup");
		}
	}
	//added by rdhital/Raju for better email support
	elseif($uitype == 357)
	{
		if($_REQUEST['pmodule'] == 'Contacts')
		{
			$contact_selected = 'selected';
		}
		elseif($_REQUEST['pmodule'] == 'Accounts')
		{
			$account_selected = 'selected';
		}
		elseif($_REQUEST['pmodule'] == 'Leads' || $_REQUEST['pmodule'] == 'LeadManagement')
		{
			$lead_selected = 'selected';
		}
		if(isset($_REQUEST['emailids']) && $_REQUEST['emailids'] != '')
		{
			$parent_id = $_REQUEST['emailids'];
			$parent_name='';
			$pmodule=$_REQUEST['pmodule'];
			$myids=explode("|",$parent_id);
			for ($i=0;$i<(count($myids)-1);$i++)
			{
				$realid=explode("@",$myids[$i]);
				$entityid=$realid[0];
				$nemail=count($realid);

				if ($pmodule=='Accounts'){
					require_once('modules/Accounts/Accounts.php');
					$myfocus = new Accounts();
					$myfocus->retrieve_entity_info($entityid,"Accounts");
					$fullname=br2nl($myfocus->column_fields['accountname']);
					$account_selected = 'selected';
				}
				elseif ($pmodule=='Contacts'){
					require_once('modules/Contacts/Contacts.php');
					$myfocus = new Contacts();
					$myfocus->retrieve_entity_info($entityid,"Contacts");
					$fname=br2nl($myfocus->column_fields['firstname']);
					$lname=br2nl($myfocus->column_fields['lastname']);
					$fullname=$lname.' '.$fname;
					$contact_selected = 'selected';
				}
				elseif ($pmodule=='Leads'){
					require_once('modules/Leads/Leads.php');
					$myfocus = new Leads();
					$myfocus->retrieve_entity_info($entityid,"Leads");
					$fname=br2nl($myfocus->column_fields['firstname']);
					$lname=br2nl($myfocus->column_fields['lastname']);
					$fullname=$lname.' '.$fname;
					$lead_selected = 'selected';
				}
				elseif ($pmodule=='LeadManagement'){
					require_once('modules/LeadManagement/LeadManagement.php');
					$myfocus = new LeadManagement();
					$myfocus->retrieve_entity_info($entityid,"LeadManagement");
					$fname=br2nl($myfocus->column_fields['firstname']);
					$lname=br2nl($myfocus->column_fields['lastname']);
					$fullname=$lname.' '.$fname;
					$lead_selected = 'selected';
				}
				for ($j=1;$j<$nemail;$j++){
					$querystr='select columnname from aicrm_field where fieldid=? and aicrm_field.presence in (0,2)';
					$result=$adb->pquery($querystr, array($realid[$j]));
					$temp=$adb->query_result($result,0,'columnname');
					$temp1=br2nl($myfocus->column_fields[$temp]);

					//Modified to display the entities in red which don't have email id
					if(!empty($temp_parent_name) && strlen($temp_parent_name) > 150)
					{
						$parent_name .= '<br>';
						$temp_parent_name = '';
					}

					if($temp1 != '')
					{
						$parent_name .= $fullname.'&lt;'.$temp1.'&gt;; ';
						$temp_parent_name .= $fullname.'&lt;'.$temp1.'&gt;; ';
					}
					else
					{
						$parent_name .= "<b style='color:red'>".$fullname.'&lt;'.$temp1.'&gt;; '."</b>";
						$temp_parent_name .= "<b style='color:red'>".$fullname.'&lt;'.$temp1.'&gt;; '."</b>";
					}

				}
			}
		}
		else
		{
			if($_REQUEST['record'] != '' && $_REQUEST['record'] != NULL)
			{
				$parent_name='';
				$parent_id='';
				$myemailid= $_REQUEST['record'];
				$mysql = "select crmid from aicrm_seactivityrel where activityid=?";
				$myresult = $adb->pquery($mysql, array($myemailid));
				$mycount=$adb->num_rows($myresult);
				if($mycount >0)
				{
					for ($i=0;$i<$mycount;$i++)
					{
						$mycrmid=$adb->query_result($myresult,$i,'crmid');
						$parent_module = getSalesEntityType($mycrmid);
						if($parent_module == "Leads")
						{
							$sql = "select firstname,lastname,email from aicrm_leaddetails where leadid=?";
							$result = $adb->pquery($sql, array($mycrmid));
							$full_name = getFullNameFromQResult($result,0,"Leads");
							$myemail=$adb->query_result($result,0,"email");
							$parent_id .=$mycrmid.'@0|' ; //make it such that the email adress sent is remebered and only that one is retrived
							$parent_name .= $full_name.'<'.$myemail.'>; ';
							$lead_selected = 'selected';
						}
						elseif($parent_module == "LeadManagement")
						{
							$sql = "select firstname,lastname,email from aicrm_leadmanage where leadid=?";
							$result = $adb->pquery($sql, array($mycrmid));
							$full_name = getFullNameFromQResult($result,0,"LeadManagement");
							$myemail=$adb->query_result($result,0,"email");
							$parent_id .=$mycrmid.'@0|' ; //make it such that the email adress sent is remebered and only that one is retrived
							$parent_name .= $full_name.'<'.$myemail.'>; ';
							$lead_selected = 'selected';
						}
						elseif($parent_module == "Contacts")
						{
							$sql = "select * from  aicrm_contactdetails where contactid=?";
							$result = $adb->pquery($sql, array($mycrmid));
							$full_name = getFullNameFromQResult($result,0,"Contacts");
							$myemail=$adb->query_result($result,0,"email");
							$parent_id .=$mycrmid.'@0|'  ;//make it such that the email adress sent is remebered and only that one is retrived
							$parent_name .= $full_name.'<'.$myemail.'>; ';
							$contact_selected = 'selected';
						}
						elseif($parent_module == "Accounts")
						{
							$sql = "select * from  aicrm_account where accountid=?";
							$result = $adb->pquery($sql, array($mycrmid));
							$account_name = $adb->query_result($result,0,"accountname");
							$myemail=$adb->query_result($result,0,"email1");
							$parent_id .=$mycrmid.'@0|'  ;//make it such that the email adress sent is remebered and only that one is retrived
							$parent_name .= $account_name.'<'.$myemail.'>; ';
							$account_selected = 'selected';
						}elseif($parent_module == "Users")
						{
							$sql = "select user_name,email1 from aicrm_users where id=?";
							$result = $adb->pquery($sql, array($mycrmid));
							$account_name = $adb->query_result($result,0,"user_name");
							$myemail=$adb->query_result($result,0,"email1");
							$parent_id .=$mycrmid.'@0|'  ;//make it such that the email adress sent is remebered and only that one is retrived
							$parent_name .= $account_name.'<'.$myemail.'>; ';
							$user_selected = 'selected';
						}
						/*elseif($parent_module == "Vendors")
						{
							$sql = "select * from  aicrm_vendor where vendorid=?";
							$result = $adb->pquery($sql, array($mycrmid));
							$vendor_name = $adb->query_result($result,0,"vendorname");
							$myemail=$adb->query_result($result,0,"email");
							$parent_id .=$mycrmid.'@0|'  ;//make it such that the email adress sent is remebered and only that one is retrived
							$parent_name .= $vendor_name.'<'.$myemail.'>; ';
							$vendor_selected = 'selected';
						}*/
					}
				}
			}
			$custfld .= '<td width="20%" class="dataLabel">'.$app_strings['To'].'&nbsp;</td>';
			$custfld .= '<td width="90%" colspan="3"><input name="parent_id" type="hidden" value="'.$parent_id.'"><textarea readonly name="parent_name" cols="70" rows="2">'.$parent_name.'</textarea>&nbsp;<select name="parent_type" >';
			$custfld .= '<OPTION value="Contacts" selected>'.$app_strings['COMBO_CONTACTS'].'</OPTION>';
			$custfld .= '<OPTION value="Accounts" >'.$app_strings['COMBO_ACCOUNTS'].'</OPTION>';
			$custfld .= '<OPTION value="Leads" >'.$app_strings['COMBO_LEADS'].'</OPTION>';
			/*$custfld .= '<OPTION value="Vendors" >'.$app_strings['COMBO_VENDORS'].'</OPTION></select><img src="' . aicrm_imageurl('select.gif', $theme) . '" alt="Select" title="Select" LANGUAGE=javascript onclick=\'$log->debug("Exiting getOutputHtml method ..."); return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&popuptype=set_$log->debug("Exiting getOutputHtml method ..."); return_emails&form=EmailEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="' . aicrm_imageurl('clear_field.gif', $theme) . '" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.parent_id.value=\'\';this.form.parent_name.value=\'\';$log->debug("Exiting getOutputHtml method ..."); return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>';*/
			$editview_label[] = array(
					'Contacts'=>$contact_selected,
					'Accounts'=>$account_selected,
					//'Vendors'=>$vendor_selected,
					'Leads'=>$lead_selected,
					'Users'=>$user_selected
					);
			$fieldvalue[] =$parent_name;
			$fieldvalue[] = $parent_id;
		}
	}
	//end of rdhital/Raju
	elseif($uitype == 68)
	{
		if(isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] != '')
			$value = $_REQUEST['parent_id'];

		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Contacts")
			{
				$parent_name = getContactName($value);
				$contact_selected = "selected";

			}
			elseif($parent_module == "Accounts")
			{
				$sql = "select * from  aicrm_account where accountid=?";
				$result = $adb->pquery($sql, array($value));
				$parent_name = $adb->query_result($result,0,"accountname");
				$account_selected = "selected";
			}
			else
			{
				$parent_name = "";
				$value = "";
			}
		}
		$editview_label[] = array($app_strings['COMBO_ACCOUNTS']);
		$editview_label[] = array($account_selected);
		$editview_label[] = array("Accounts");
		$fieldvalue[] = $parent_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 71 || $uitype == 72)
	{
		if($col_fields['record_id'] != '' && $fieldname == 'unit_price') {
			$rate_symbol=getCurrencySymbolandCRate(getProductBaseCurrency($col_fields['record_id'],$module_name));
			$fieldvalue[] = $value;
		} else {
			$currency_id = fetchCurrency($current_user->id);
			$rate_symbol=getCurrencySymbolandCRate($currency_id);
			$rate = $rate_symbol['rate'];
			$val_number = convertFromDollar($value,$rate);
			$fieldvalue[] = ($val_number == 0) ? '0' : $val_number;
		}
        $currency = $rate_symbol['symbol'];
		$editview_label[]=getTranslatedString($fieldlabel, $module_name).': ('.$currency.')';
	}
	elseif($uitype == 7 )
	{
		if($fieldname == 'pro_priceinclude'){
			if($typeofdata <> '' ){
			$type1 = explode(",", $typeofdata );
			
				if(count($type1)>1){
					$lenght  = $type1[1];
				}else{
					$lenght = 0;
				}
			}else{
				$lenght = 0;
			}
			$fieldvalue[] = number_format($value,$lenght);
		}else{
			$fieldvalue[] = $value;
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
	}
	elseif($uitype == 76)
	{
		if($value != '')
		{
			$potential_name = getPotentialName($value);
		}
		elseif(isset($_REQUEST['potential_id']) && $_REQUEST['potential_id'] != '')
		{
			$value = $_REQUEST['potental_id'];
			$potential_name = getPotentialName($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[] = $potential_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 78)
	{
		if($value != '')
		{
			$quote_name = getQuoteName($value);
		}
		elseif(isset($_REQUEST['quote_id']) && $_REQUEST['quote_id'] != '')
		{
			$value = $_REQUEST['quote_id'];
			$potential_name = getQuoteName($value);
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[] = $quote_name;
		$fieldvalue[] = $value;
	}
	elseif($uitype == 30)
	{
		$rem_days = 0;
		$rem_hrs = 0;
		$rem_min = 0;
		if($value!='')
			$SET_REM = "CHECKED";
		$rem_days = floor($col_fields[$fieldname]/(24*60));
		$rem_hrs = floor(($col_fields[$fieldname]-$rem_days*24*60)/60);
		$rem_min = ($col_fields[$fieldname]-$rem_days*24*60)%60;
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$day_options = getReminderSelectOption(0,31,'remdays',$rem_days);
		$hr_options = getReminderSelectOption(0,23,'remhrs',$rem_hrs);
		$min_options = getReminderSelectOption(1,59,'remmin',$rem_min);
		$fieldvalue[] = array(array(0,32,'remdays',getTranslatedString('LBL_DAYS'),$rem_days),array(0,24,'remhrs',getTranslatedString('LBL_HOURS'),$rem_hrs),array(1,60,'remmin',getTranslatedString('LBL_MINUTES').'&nbsp;&nbsp;'.getTranslatedString('LBL_BEFORE_EVENT'),$rem_min));
		$fieldvalue[] = array($SET_REM,getTranslatedString('LBL_YES'),getTranslatedString('LBL_NO'));
		$SET_REM = '';
	}
	elseif($uitype == 115)
	{
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		if($module_name == "Users" && $fieldname == 'status'){
			$pick_query="select * from aicrm_" . $adb->sql_escape_string($fieldname).'user';
		}else{
			$pick_query="select * from aicrm_" . $adb->sql_escape_string($fieldname);
		}
		$pickListResult = $adb->pquery($pick_query, array());
		$noofpickrows = $adb->num_rows($pickListResult);

		//Mikecrowe fix to correctly default for custom pick lists
		$options = array();
		$found = false;
		for($j = 0; $j < $noofpickrows; $j++)
		{
			if($module_name == "Users" && $fieldname == 'status'){
				$pickListValue=$adb->query_result($pickListResult,$j,strtolower($fieldname."user"));
			}else{
				$pickListValue=$adb->query_result($pickListResult,$j,strtolower($fieldname));
			}

			if($value == $pickListValue)
			{
				$chk_val = "selected";
				$found = true;
			}
			else
			{
				$chk_val = '';
			}
			$options[] = array(getTranslatedString($pickListValue),$pickListValue,$chk_val );
		}
		$fieldvalue [] = $options;
		$fieldvalue [] = $is_admin;
	}
	elseif($uitype == 116 || $uitype == 117)
	{
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$pick_query="select * from aicrm_currency_info where currency_status = 'Active' and deleted=0";
		$pickListResult = $adb->pquery($pick_query, array());
		$noofpickrows = $adb->num_rows($pickListResult);

		//Mikecrowe fix to correctly default for custom pick lists
		$options = array();
		$found = false;
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$pickListValue=$adb->query_result($pickListResult,$j,'currency_name');
			$currency_id=$adb->query_result($pickListResult,$j,'id');
			if($value == $currency_id)
			{
				$chk_val = "selected";
				$found = true;
			}
			else
			{
				$chk_val = '';
			}
			$options[$currency_id] = array($pickListValue=>$chk_val );
		}
		$fieldvalue [] = $options;
		$fieldvalue [] = $is_admin;
	}
	elseif($uitype ==98)
	{
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$fieldvalue[]=$value;
        $fieldvalue[]=getRoleName($value);
		$fieldvalue[]=$is_admin;
	}
	elseif($uitype == 105)
	{
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		 if( isset( $col_fields['record_id']) && $col_fields['record_id'] != '') {
			$query = "select aicrm_attachments.path, aicrm_attachments.name from aicrm_contactdetails left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_contactdetails.contactid inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid where aicrm_contactdetails.imagename=aicrm_attachments.name and contactid=?";
			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
				$image_array[] = $adb->query_result($result_image,$image_iter,'name');
				$image_path_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
		}
		if(is_array($image_array))
			for($img_itr=0;$img_itr<count($image_array);$img_itr++)
			{
				$fieldvalue[] = array('name'=>$image_array[$img_itr],'path'=>$image_path_array[$img_itr]);
			}
		else
			$fieldvalue[] = '';
	}elseif($uitype == 101)
	{
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
        $fieldvalue[] = getUserName($value);
        $fieldvalue[] = $value;
	}
	elseif($uitype == 26){
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		$folderid=$col_fields['folderid'];
		$foldername_query = 'select foldername from aicrm_attachmentsfolder where folderid = ?';
		$res = $adb->pquery($foldername_query,array($folderid));
		$foldername = $adb->query_result($res,0,'foldername');
		if($foldername != '' && $folderid != ''){
			$fldr_name[$folderid]=$foldername;
			}
		$sql="select foldername,folderid from aicrm_attachmentsfolder order by foldername";
		$res=$adb->pquery($sql,array());
		for($i=0;$i<$adb->num_rows($res);$i++)
			{
				$fid=$adb->query_result($res,$i,"folderid");
				$fldr_name[$fid]=$adb->query_result($res,$i,"foldername");
			}
		$fieldvalue[] = $fldr_name;
		}
	elseif($uitype == 27){
		if($value == 'I'){
			$internal_selected = "selected";
			$filename = $col_fields['filename'];
		}
		else{
			$external_selected = "selected";
			$filename = $col_fields['filename'];
		}
		$editview_label[] = array(getTranslatedString('Internal'),
                                getTranslatedString('External')
                                );
        $editview_label[] = array($internal_selected,
                                $external_selected
                                );
        $editview_label[] = array("I","E");
        $editview_label[] = getTranslatedString($fieldlabel, $module_name);
        $fieldvalue[] = $value;
        $fieldvalue[] = $filename;
	}
	else
	{
		//Added condition to set the subject if click Reply All from web mail
		if($_REQUEST['module'] == 'Emails' && $_REQUEST['mg_subject'] != '')
		{
			$value = $_REQUEST['mg_subject'];
		}
		$editview_label[]=getTranslatedString($fieldlabel, $module_name);
		if($uitype == 1 && ($fieldname=='expectedrevenue' || $fieldname=='budgetcost' || $fieldname=='actualcost' || $fieldname=='expectedroi' || $fieldname=='actualroi' ) && ($module_name=='Campaigns'))
		{
			$rate_symbol = getCurrencySymbolandCRate($user_info['currency_id']);
			$fieldvalue[] = convertFromDollar($value,$rate_symbol['rate']);
		}
		elseif($fieldname == 'fileversion'){
			if(empty($value)){
				$value = '';
			}
			else{
				$fieldvalue[] = $value;
			}
		}
		else
			$fieldvalue[] = $value;
	}

	if ( !eregi("id=",$custfld) )
		$custfld = preg_replace("/<input/iS","<input id='$fieldname' ",$custfld);

	if ( in_array($uitype,array(71,72,7,9,90)) )
	{
		$custfld = preg_replace("/<input/iS","<input align=right ",$custfld);
	}
	$final_arr[]=$ui_type;
	$final_arr[]=$editview_label;
	$final_arr[]=$editview_fldname;
	$final_arr[]=$fieldvalue;
	$type_of_data  = explode('~',$typeofdata);
	$final_arr[]=$type_of_data[1];
	$log->debug("Exiting getOutputHtml method ...");
	return $final_arr;
}

/** This function returns the aicrm_invoice object populated with the details from sales order object.
* Param $focus - Invoice object
* Param $so_focus - Sales order focus
* Param $soid - sales order id
* Return type is an object array
*/

function getConvertSoToInvoice($focus,$so_focus,$soid)
{
	global $log,$current_user;
	$log->debug("Entering getConvertSoToInvoice(".get_class($focus).",".get_class($so_focus).",".$soid.") method ...");
    $log->info("in getConvertSoToInvoice ".$soid);
    $xyz=array('bill_street','bill_city','bill_code','bill_pobox','bill_country','bill_state','ship_street','ship_city','ship_code','ship_pobox','ship_country','ship_state');
	for($i=0;$i<count($xyz);$i++){
		if (getFieldVisibilityPermission('SalesOrder', $current_user->id,$xyz[$i]) == '0'){
			$so_focus->column_fields[$xyz[$i]] = $so_focus->column_fields[$xyz[$i]];
		}
		else
			$so_focus->column_fields[$xyz[$i]] = '';
	}
	$focus->column_fields['salesorder_id'] = $soid;
	$focus->column_fields['subject'] = $so_focus->column_fields['subject'];
	$focus->column_fields['customerno'] = $so_focus->column_fields['customerno'];
	$focus->column_fields['duedate'] = $so_focus->column_fields['duedate'];
	$focus->column_fields['contact_id'] = $so_focus->column_fields['contact_id'];//to include contact name in Invoice
	$focus->column_fields['account_id'] = $so_focus->column_fields['account_id'];
	$focus->column_fields['exciseduty'] = $so_focus->column_fields['exciseduty'];
	$focus->column_fields['salescommission'] = $so_focus->column_fields['salescommission'];
	$focus->column_fields['purchaseorder'] = $so_focus->column_fields['purchaseorder'];
	$focus->column_fields['bill_street'] = $so_focus->column_fields['bill_street'];
	$focus->column_fields['ship_street'] = $so_focus->column_fields['ship_street'];
	$focus->column_fields['bill_city'] = $so_focus->column_fields['bill_city'];
	$focus->column_fields['ship_city'] = $so_focus->column_fields['ship_city'];
	$focus->column_fields['bill_state'] = $so_focus->column_fields['bill_state'];
	$focus->column_fields['ship_state'] = $so_focus->column_fields['ship_state'];
	$focus->column_fields['bill_code'] = $so_focus->column_fields['bill_code'];
	$focus->column_fields['ship_code'] = $so_focus->column_fields['ship_code'];
	$focus->column_fields['bill_country'] = $so_focus->column_fields['bill_country'];
	$focus->column_fields['ship_country'] = $so_focus->column_fields['ship_country'];
	$focus->column_fields['bill_pobox'] = $so_focus->column_fields['bill_pobox'];
	$focus->column_fields['ship_pobox'] = $so_focus->column_fields['ship_pobox'];
	$focus->column_fields['description'] = $so_focus->column_fields['description'];
	$focus->column_fields['terms_conditions'] = $so_focus->column_fields['terms_conditions'];
    $focus->column_fields['currency_id'] = $so_focus->column_fields['currency_id'];
    $focus->column_fields['conversion_rate'] = $so_focus->column_fields['conversion_rate'];

	$log->debug("Exiting getConvertSoToInvoice method ...");
	return $focus;

}

/** This function returns the aicrm_invoice object populated with the details from quote object.
* Param $focus - Invoice object
* Param $quote_focus - Quote order focus
* Param $quoteid - quote id
* Return type is an object array
*/


function getConvertQuoteToInvoice($focus,$quote_focus,$quoteid)
{
	global $log,$current_user;
	$log->debug("Entering getConvertQuoteToInvoice(".get_class($focus).",".get_class($quote_focus).",".$quoteid.") method ...");
        $log->info("in getConvertQuoteToInvoice ".$quoteid);
    $xyz=array('bill_street','bill_city','bill_code','bill_pobox','bill_country','bill_state','ship_street','ship_city','ship_code','ship_pobox','ship_country','ship_state');
	for($i=0;$i<12;$i++){
		if (getFieldVisibilityPermission('Quotes', $current_user->id,$xyz[$i]) == '0'){
			$quote_focus->column_fields[$xyz[$i]] = $quote_focus->column_fields[$xyz[$i]];
		}
		else
			$quote_focus->column_fields[$xyz[$i]] = '';
	}
	$focus->column_fields['subject'] = $quote_focus->column_fields['subject'];
	$focus->column_fields['account_id'] = $quote_focus->column_fields['account_id'];
	$focus->column_fields['bill_street'] = $quote_focus->column_fields['bill_street'];
	$focus->column_fields['ship_street'] = $quote_focus->column_fields['ship_street'];
	$focus->column_fields['bill_city'] = $quote_focus->column_fields['bill_city'];
	$focus->column_fields['ship_city'] = $quote_focus->column_fields['ship_city'];
	$focus->column_fields['bill_state'] = $quote_focus->column_fields['bill_state'];
	$focus->column_fields['ship_state'] = $quote_focus->column_fields['ship_state'];
	$focus->column_fields['bill_code'] = $quote_focus->column_fields['bill_code'];
	$focus->column_fields['ship_code'] = $quote_focus->column_fields['ship_code'];
	$focus->column_fields['bill_country'] = $quote_focus->column_fields['bill_country'];
	$focus->column_fields['ship_country'] = $quote_focus->column_fields['ship_country'];
	$focus->column_fields['bill_pobox'] = $quote_focus->column_fields['bill_pobox'];
	$focus->column_fields['ship_pobox'] = $quote_focus->column_fields['ship_pobox'];
	$focus->column_fields['description'] = $quote_focus->column_fields['description'];
	$focus->column_fields['terms_conditions'] = $quote_focus->column_fields['terms_conditions'];
    $focus->column_fields['currency_id'] = $quote_focus->column_fields['currency_id'];
    $focus->column_fields['conversion_rate'] = $quote_focus->column_fields['conversion_rate'];

	$log->debug("Exiting getConvertQuoteToInvoice method ...");
	return $focus;

}

/** This function returns the sales order object populated with the details from quote object.
* Param $focus - Sales order object
* Param $quote_focus - Quote order focus
* Param $quoteid - quote id
* Return type is an object array
*/

function getConvertQuoteToSoObject($focus,$quote_focus,$quoteid)
{
	global $log,$current_user;
	$log->debug("Entering getConvertQuoteToSoObject(".get_class($focus).",".get_class($quote_focus).",".$quoteid.") method ...");
        $log->info("in getConvertQuoteToSoObject ".$quoteid);
	    $xyz=array('bill_street','bill_city','bill_code','bill_pobox','bill_country','bill_state','ship_street','ship_city','ship_code','ship_pobox','ship_country','ship_state');
		for($i=0;$i<12;$i++){
			if (getFieldVisibilityPermission('Quotes', $current_user->id,$xyz[$i]) == '0'){
				$quote_focus->column_fields[$xyz[$i]] = $quote_focus->column_fields[$xyz[$i]];
			}
			else
				$quote_focus->column_fields[$xyz[$i]] = '';
		}
        $focus->column_fields['quote_id'] = $quoteid;
        $focus->column_fields['subject'] = $quote_focus->column_fields['subject'];
        $focus->column_fields['contact_id'] = $quote_focus->column_fields['contact_id'];
        $focus->column_fields['potential_id'] = $quote_focus->column_fields['potential_id'];
        $focus->column_fields['account_id'] = $quote_focus->column_fields['account_id'];
        $focus->column_fields['carrier'] = $quote_focus->column_fields['carrier'];
        $focus->column_fields['bill_street'] = $quote_focus->column_fields['bill_street'];
        $focus->column_fields['ship_street'] = $quote_focus->column_fields['ship_street'];
        $focus->column_fields['bill_city'] = $quote_focus->column_fields['bill_city'];
        $focus->column_fields['ship_city'] = $quote_focus->column_fields['ship_city'];
        $focus->column_fields['bill_state'] = $quote_focus->column_fields['bill_state'];
        $focus->column_fields['ship_state'] = $quote_focus->column_fields['ship_state'];
        $focus->column_fields['bill_code'] = $quote_focus->column_fields['bill_code'];
        $focus->column_fields['ship_code'] = $quote_focus->column_fields['ship_code'];
        $focus->column_fields['bill_country'] = $quote_focus->column_fields['bill_country'];
        $focus->column_fields['ship_country'] = $quote_focus->column_fields['ship_country'];
        $focus->column_fields['bill_pobox'] = $quote_focus->column_fields['bill_pobox'];
        $focus->column_fields['ship_pobox'] = $quote_focus->column_fields['ship_pobox'];
		$focus->column_fields['description'] = $quote_focus->column_fields['description'];
        $focus->column_fields['terms_conditions'] = $quote_focus->column_fields['terms_conditions'];
        $focus->column_fields['currency_id'] = $quote_focus->column_fields['currency_id'];
        $focus->column_fields['conversion_rate'] = $quote_focus->column_fields['conversion_rate'];

	$log->debug("Exiting getConvertQuoteToSoObject method ...");
        return $focus;

}

/** This function returns the detailed list of aicrm_products associated to a given entity or a record.
* Param $module - module name
* Param $focus - module object
* Param $seid - sales entity id
* Return type is an object array
*/
function getEdit_CAM_Tab1($module,$focus,$seid=''){
	global $log;
	$log->debug("Entering getAssociatedProducts(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();
	$crmid=$focus->id;
	$sql="
	select
	*
	from aicrm_inventory_campaign_dtl1
	left join aicrm_products on aicrm_products.productid=aicrm_inventory_campaign_dtl1.productid
	left join aicrm_productcf on aicrm_products.productid=aicrm_productcf.productid
	where 1
	and aicrm_inventory_campaign_dtl1.id='".$crmid."'
	";

	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);

	for($i=1;$i<=$num_rows;$i++){
		$sequence_no = $adb->query_result($data,$i-1,'sequence_no');
		$productid = $adb->query_result($data,$i-1,'productid');
		$productname = $adb->query_result($data,$i-1,'productname');
		$comment = $adb->query_result($data,$i-1,'comment ');
        $pack_size = $adb->query_result($data,$i-1,'pack_size');
        $test_box = $adb->query_result($data,$i-1,'test_box');
		$uom = $adb->query_result($data,$i-1,'uom');
		$quantity = $adb->query_result($data,$i-1,'quantity');
		$listprice = $adb->query_result($data,$i-1,'listprice');

		$product_Detail[$i]['sequence_no'.$i] = $sequence_no;
		$product_Detail[$i]['mySetup_Tab1_hdnProductId'.$i] = $productid;
		$product_Detail[$i]['mySetup_Tab1_productName'.$i] = $productname;
		$product_Detail[$i]['mySetup_Tab1_comment'.$i] = $comment;
        $product_Detail[$i]['mySetup_Tab1_pack_size'.$i] = $pack_size;
        $product_Detail[$i]['mySetup_Tab1_test_box'.$i] = $test_box;
		$product_Detail[$i]['mySetup_Tab1_uom'.$i] = $uom;
		$product_Detail[$i]['mySetup_Tab1_qty'.$i] = $quantity;
		$product_Detail[$i]['mySetup_Tab1_listPrice'.$i] = $listprice;
		$product_Detail[$i]['mySetup_Tab1_Count'.$i] = $num_rows;
	}
	$log->debug("Exiting getEdit_CAM_Tab1 method ...");
	return $product_Detail;
}

function getEdit_CAM_Tab2($module,$focus,$seid=''){
	global $log;
	$log->debug("Entering getAssociatedProducts(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();
	$crmid=$focus->id;
	$sql="
	select
	*
	from aicrm_inventory_campaign_dtl2
	where 1
	and aicrm_inventory_campaign_dtl2.id='".$crmid."'
	";
	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);

	for($i=1;$i<=$num_rows;$i++){
		$sequence_no = $adb->query_result($data,$i-1,'sequence_no');
		$campaign_from = $adb->query_result($data,$i-1,'campaign_from');
		$campaign_to = $adb->query_result($data,$i-1,'campaign_to');
		$campaign_fomula = $adb->query_result($data,$i-1,'campaign_fomula');
		$campaign_parameter = $adb->query_result($data,$i-1,'campaign_parameter');

		$product_Detail[$i]['sequence_no'.$i] = $sequence_no;
		$product_Detail[$i]['mySetup_Tab2_campaign_from'.$i] = $campaign_from;
		$product_Detail[$i]['mySetup_Tab2_campaign_to'.$i] = $campaign_to;
		$product_Detail[$i]['mySetup_Tab2_campaign_fomula'.$i] = $campaign_fomula;
		$product_Detail[$i]['mySetup_Tab2_campaign_parameter'.$i] = $campaign_parameter;
		$product_Detail[$i]['mySetup_Tab2_Count'.$i] = $num_rows;
	}
	$log->debug("Exiting getEdit_CAM_Tab2 method ...");
	return $product_Detail;
}

function getEdit_Tab1($module,$focus,$seid=''){
	global $log;
	$log->debug("Entering getAssociatedProducts(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();

	$sql="
	select
	*
	from aicrm_inventory_protab1_dtl1
	where 1
	and aicrm_inventory_protab1_dtl1.id='".$focus->id."'
	";
	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);
	for($i=1;$i<=$num_rows;$i++){
		$sequence_no = $adb->query_result($data,$i-1,'sequence_no');
		$productprice_from = $adb->query_result($data,$i-1,'productprice_from');
		$productprice_to = $adb->query_result($data,$i-1,'productprice_to');
		$campaign_fomula = $adb->query_result($data,$i-1,'campaign_fomula');

		$premium=getEdit_Tab1_1($focus->id,$sequence_no);
		$product_Detail[$i]['sequence_no'.$i] = $sequence_no;
		$product_Detail[$i]['mySetup_Tab1_1_productprice_from'.$i] = $productprice_from;
		$product_Detail[$i]['mySetup_Tab1_1_productprice_to'.$i] = $productprice_to;
		$product_Detail[$i]['campaign_fomula'.$i] = $campaign_fomula;
		$product_Detail[$i]['mySetup_Tab1_1_premium'.$i] = $premium;
		$product_Detail[$i]['mySetup_Tab1_1_totalProductCount'.$i] = $num_rows;
	}
	$log->debug("Exiting getEdit_Tab3 method ...");
	return $product_Detail;
}
function getEdit_Tab1_1($crmid,$row_id){
	global $log;
	$log->debug("Entering getAssociatedProducts(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();
	$sql="
	SELECT  row_id , aicrm_premuimproduct.premuimproductid, aicrm_premuimproduct. * , COUNT(  sequence_no ) AS count
	FROM  aicrm_inventory_protab1_dtl2
	LEFT JOIN aicrm_premuimproduct ON aicrm_premuimproduct.premuimproductid = aicrm_inventory_protab1_dtl2.premiumproductid
	WHERE 1
	AND  id ='".$crmid."'
	and row_id='".$row_id."'
	GROUP BY  row_id ,  premiumproductid
	ORDER BY lineitem_id
	";

	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);
	$out_put='';
	$out_put='<div class="mySetup_Tab1_1_premium">';
	for($i=1;$i<=$num_rows;$i++){
		$out_put.='<table width="100%" id="table_mySetup_Tab1_1_row'.$adb->query_result($data,$i-1,'row_id').'_'.$i.'" border="0" cellpadding="5" cellspacing="0" class="crmTable">
		<tbody>
			<tr>
				<td colspan="6" class="detailedViewHeader">
				<img src="themes/images/delete.gif" border="0" onclick="delete_premium(\'table_mySetup_Tab1_1_row'.$adb->query_result($data,$i-1,'row_id').'_'.$i.'\')">
				<input type="hidden" id="mySetup_Tab1_1_premiumrecord_'.$adb->query_result($data,$i-1,'row_id').'_'.$i.'" name="mySetup_Tab1_1_premiumrecord_'.$adb->query_result($data,$i-1,'row_id').'_'.$i.'" value="'.$adb->query_result($data,$i-1,'count').'"><strong> Premium '.$adb->query_result($data,$i-1,'premuimproductid').' :: '.$adb->query_result($data,$i-1,'premuimproduct_name').'</strong> </td>
			</tr>
			<tr class="lvtCol">
				<td>ลำดับ</td>
				<td class="lvtCol">รหัสสินค้าพรีเมี่ยม</td>
				<td class="lvtCol">ชื่อสินค้าพรีเมี่ยม</td>
				<td class="lvtCol">หน่วยการนับ</td>
				<td class="lvtCol">จำนวน</td>
				<td class="lvtCol">ราคาสินค้า</td>
			</tr>';
			$sql="
			select
			aicrm_inventory_protab1_dtl2.*
			,aicrm_premuimproduct.*
			,aicrm_premuimproductcf.*
			from aicrm_inventory_protab1_dtl2
			left join aicrm_premuimproduct on aicrm_premuimproduct.premuimproductid=aicrm_inventory_protab1_dtl2.premiumproductid
			left join aicrm_premuimproductcf on aicrm_premuimproductcf.premuimproductid=aicrm_premuimproduct.premuimproductid
			where 1
			and aicrm_inventory_protab1_dtl2.id='".$crmid."'
			and aicrm_inventory_protab1_dtl2.row_id='".$adb->query_result($data,$i-1,'row_id')."'
			and aicrm_inventory_protab1_dtl2.premiumproductid='".$adb->query_result($data,$i-1,'premuimproductid')."'
			";
			$data_r = $adb->pquery($sql, "");
			$num_rowss=$adb->num_rows($data_r);
			for($k=1;$k<=$num_rowss;$k++){

			$out_put.='
			<tr>
				<td class="crmTableRow small lineOnTop">'.$adb->query_result($data_r,$k-1,'sequence_no').'</td>
				<td class="crmTableRow small lineOnTop">
				<input type="hidden" id="mySetup_Tab1_1_premiumid_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab1_1_premiumid_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" value="'.$adb->query_result($data_r,$k-1,'premiumproductid').'">
				<input type="hidden" id="mySetup_Tab1_1_productid_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab1_1_productid_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" class="small" style="width:70%" value="'.$adb->query_result($data_r,$k-1,'premiumproductid').'" readonly="readonly">
				<input type="text" id="mySetup_Tab1_1_premiumcode_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab1_1_premiumcode_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" class="small" style="width:70%" value="'.$adb->query_result($data_r,$k-1,'premium_product_code').'" readonly="readonly"></td>

				<td class="crmTableRow small lineOnTop">
				<input type="text" id="mySetup_Tab1_1_productname_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab1_1_productname_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" class="small" style="width:70%" value="'.$adb->query_result($data_r,$k-1,'premuimproduct_name').'" readonly="readonly"></td>
				<td class="crmTableRow small lineOnTop">
				<input type="text" id="mySetup_Tab1_1_uom_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab1_1_uom_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" class="small" style="width:70%" value="'.$adb->query_result($data_r,$k-1,'uom').'" readonly="readonly"></td>
				<td class="crmTableRow small lineOnTop">
				<input type="text" id="mySetup_Tab1_1_qty_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab1_1_qty_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" class="small" style="width:70%" value="'.$adb->query_result($data_r,$k-1,'quantity').'" readonly="readonly"></td>
				<td class="crmTableRow small lineOnTop">
				<input type="text" id="mySetup_Tab1_1_price_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab1_1_price_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" class="small" style="width:70%" value="'.$adb->query_result($data_r,$k-1,'listprice').'" readonly="readonly">
				</td>
			</tr>';
			}
			$out_put.='
		</tbody>
	</table>';
	}
	$out_put.='</div><input type="hidden" name="total_row_premium'.$row_id.'" id="total_row_premium'.$row_id.'" value="'.$num_rows.'">';
	return $out_put;
}

function getEdit_Tab1_2($module,$focus,$seid=''){
	global $log;
	$log->debug("Entering getAssociatedProducts(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();

	$sql="
	select
	aicrm_products.*
	,aicrm_productcf.*
	,aicrm_inventory_protab1_dtl3.*
	from aicrm_inventory_protab1_dtl3
	left join aicrm_products on aicrm_products.productid=aicrm_inventory_protab1_dtl3.productid
	left join aicrm_productcf on aicrm_products.productid=aicrm_productcf.productid
	where 1
	and aicrm_inventory_protab1_dtl3.id='".$focus->id."'
	";

	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);
	for($i=1;$i<=$num_rows;$i++){
		$sequence_no = $adb->query_result($data,$i-1,'sequence_no');
		$productid = $adb->query_result($data,$i-1,'productid');
		$productname = $adb->query_result($data,$i-1,'productname');
		$comment = $adb->query_result($data,$i-1,'comment ');
        $pack_size = $adb->query_result($data,$i-1,'pack_size');
        $test_box = $adb->query_result($data,$i-1,'test_box');
		$uom = $adb->query_result($data,$i-1,'uom');
		$quantity = $adb->query_result($data,$i-1,'quantity');
		$listprice = $adb->query_result($data,$i-1,'listprice');
		$campaign_fomula = $adb->query_result($data,$i-1,'campaign_fomula');

		$product_Detail[$i]['sequence_no'.$i] = $sequence_no;
		$product_Detail[$i]['mySetup_Tab1_2_hdnProductId'.$i] = $productid;
		$product_Detail[$i]['mySetup_Tab1_2_productName'.$i] = $productname;
		$product_Detail[$i]['mySetup_Tab1_2_comment'.$i] = $comment;
        $product_Detail[$i]['mySetup_Tab1_2_pack_size'.$i] = $pack_size;
        $product_Detail[$i]['mySetup_Tab1_2_test_box'.$i] = $test_box;
		$product_Detail[$i]['mySetup_Tab1_2_uom'.$i] = $uom;
		$product_Detail[$i]['mySetup_Tab1_2_qty'.$i] = $quantity;
		$product_Detail[$i]['mySetup_Tab1_2_listPrice'.$i] = $listprice;
		$product_Detail[$i]['mySetup_Tab1_2_totalProductCount'.$i] = $num_rows;
		$product_Detail[$i]['campaign_fomula_pro'.$i] = $campaign_fomula;
	}
	$log->debug("Exiting getEdit_Tab1_2 method ...");
	return $product_Detail;
}

function getEdit_Tab2($module,$focus,$seid=''){
	global $log;
	$log->debug("Entering getAssociatedProducts(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();

	$sql="
	select
	*
	from aicrm_inventory_protab2_dtl1
	left join aicrm_products on aicrm_products.productid=aicrm_inventory_protab2_dtl1.productid
	left join aicrm_productcf on aicrm_products.productid=aicrm_productcf.productid
	where 1
	and aicrm_inventory_protab2_dtl1.id='".$focus->id."'
	";
	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);
	for($i=1;$i<=$num_rows;$i++){
		$sequence_no = $adb->query_result($data,$i-1,'sequence_no');
		$productid = $adb->query_result($data,$i-1,'productid');
		$productname = $adb->query_result($data,$i-1,'productname');
		$comment = $adb->query_result($data,$i-1,'comment ');
        //$pack_size = $adb->query_result($data,$i-1,'pack_size');
        //$test_box = $adb->query_result($data,$i-1,'test_box');
		$uom = $adb->query_result($data,$i-1,'uom');
		$quantity = $adb->query_result($data,$i-1,'quantity');
		$listprice = $adb->query_result($data,$i-1,'listprice');
		$campaign_fomula = $adb->query_result($data,$i-1,'campaign_fomula');
		$hearder_fomula = $adb->query_result($data,$i-1,'hearder_fomula');
		$hearder_qty = $adb->query_result($data,$i-1,'hearder_qty');
		
		if($i=="1"){
			$premium=getEdit_Tab2_1($focus->id,$sequence_no);
		}else{
			$premium="";
		}

		$product_Detail[$i]['sequence_no'.$i] = $sequence_no;
		$product_Detail[$i]['mySetup_Tab2_hdnProductId'.$i] = $productid;
		$product_Detail[$i]['mySetup_Tab2_productName'.$i] = $productname;
		$product_Detail[$i]['mySetup_Tab2_comment'.$i] = $comment;
        //$product_Detail[$i]['mySetup_Tab2_pack_size'.$i] = $pack_size;
        //$product_Detail[$i]['mySetup_Tab2_test_box'.$i] = $test_box;
		$product_Detail[$i]['mySetup_Tab2_uom'.$i] = $uom;
		$product_Detail[$i]['mySetup_Tab2_qty'.$i] = $quantity;
		$product_Detail[$i]['mySetup_Tab2_listPrice'.$i] = $listprice;
		$product_Detail[$i]['mySetup_Tab2_campaign_fomula'.$i] = $campaign_fomula;
		$product_Detail[$i]['mySetup_Tab2_premium'.$i] = $premium;
		$product_Detail[$i]['mySetup_Tab2_totalProductCount'.$i] = $num_rows;
		$product_Detail[$i]['mySetup_Tab2_campaign_fomula_head'.$i] = $hearder_fomula;
		//$product_Detail[$i]['mySetup_Tab2_qty'.$i] = $hearder_qty;
	}
	$log->debug("Exiting getEdit_Tab3 method ...");
	return $product_Detail;
}
function getEdit_Tab2_1($crmid,$row_id){
	global $log;
	$log->debug("Entering getAssociatedProducts(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();
	$sql="
	SELECT  row_id , aicrm_premuimproduct.premuimproductid, aicrm_premuimproduct. * , COUNT(  sequence_no ) AS count
	FROM  aicrm_inventory_protab2_dtl2
	LEFT JOIN aicrm_premuimproduct ON aicrm_premuimproduct.premuimproductid = aicrm_inventory_protab2_dtl2.premiumproductid
	WHERE 1
	AND  id ='".$crmid."'
	and row_id='".$row_id."'
	GROUP BY  row_id ,  premiumproductid
	ORDER BY lineitem_id
	";
	
	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);
	$out_put='';
	$out_put='<div class="mySetup_Tab2_premium">';
	for($i=1;$i<=$num_rows;$i++){
		$out_put.='<table width="100%" id="table_mySetup_Tab2_row'.$adb->query_result($data,$i-1,'row_id').'_'.$i.'" border="0" cellpadding="5" cellspacing="0" class="crmTable">
		<tbody>
			<tr>
				<td colspan="6" class="detailedViewHeader">
				<img src="themes/images/delete.gif" border="0" onclick="delete_premium(\'table_mySetup_Tab2_row'.$adb->query_result($data,$i-1,'row_id').'_'.$i.'\')">
				<input type="hidden" id="mySetup_Tab2_premiumrecord_'.$adb->query_result($data,$i-1,'row_id').'_'.$i.'" name="mySetup_Tab2_premiumrecord_'.$adb->query_result($data,$i-1,'row_id').'_'.$i.'" value="'.$adb->query_result($data,$i-1,'count').'"><strong> Premium '.$adb->query_result($data,$i-1,'premuimproductid').' :: '.$adb->query_result($data,$i-1,'premuimproduct_name').'</strong> </td>
			</tr>
			<tr class="lvtCol">
				<td>ลำดับ</td>
				<td class="lvtCol">รหัสสินค้าพรีเมี่ยม</td>
				<td class="lvtCol">ชื่อสินค้าพรีเมี่ยม</td>
				<td class="lvtCol">หน่วยการนับ</td>
				<td class="lvtCol">จำนวน</td>
				<td class="lvtCol">ราคาสินค้า</td>
			</tr>';

			$sql="
			select
			aicrm_inventory_protab2_dtl2.*
			,aicrm_premuimproduct.premuimproductid , aicrm_premuimproduct.premium_product_code ,aicrm_premuimproduct.premuimproduct_name
			,aicrm_premuimproductcf.*
			from aicrm_inventory_protab2_dtl2
			left join aicrm_premuimproduct on aicrm_premuimproduct.premuimproductid=aicrm_inventory_protab2_dtl2.premiumproductid
			left join aicrm_premuimproductcf on aicrm_premuimproductcf.premuimproductid=aicrm_premuimproduct.premuimproductid
			where 1
			and aicrm_inventory_protab2_dtl2.id='".$crmid."'
			and aicrm_inventory_protab2_dtl2.row_id='".$adb->query_result($data,$i-1,'row_id')."'
			and aicrm_inventory_protab2_dtl2.premiumproductid='".$adb->query_result($data,$i-1,'premuimproductid')."'
			";
			
			$data_r = $adb->pquery($sql, "");
			$num_rowss=$adb->num_rows($data_r);
			
			for($k=1;$k<=$num_rowss;$k++){

			$out_put.='
			<tr>
				<td class="crmTableRow small lineOnTop">'.$adb->query_result($data_r,$k-1,'sequence_no').'</td>
				<td class="crmTableRow small lineOnTop">
				<input type="hidden" id="mySetup_Tab2_premiumid_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab2_premiumid_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" value="'.$adb->query_result($data_r,$k-1,'premuimproductid').'">
				<input type="hidden" id="mySetup_Tab2_productid_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab2_productid_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" class="small" style="width:70%" value="'.$adb->query_result($data_r,$k-1,'premuimproductid').'" readonly="readonly">
				<input type="text" id="mySetup_Tab2_premiumcode_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab2_premiumcode_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" class="small" style="width:70%" value="'.$adb->query_result($data_r,$k-1,'premium_product_code').'" readonly="readonly">
				</td>

				<td class="crmTableRow small lineOnTop">
				<input type="text" id="mySetup_Tab2_productname_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab2_productname_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" class="small" style="width:70%" value="'.$adb->query_result($data_r,$k-1,'premuimproduct_name').'" readonly="readonly"></td>
								
				<td class="crmTableRow small lineOnTop">
				<input type="text" id="mySetup_Tab2_uom_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab2_uom_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" class="small" style="width:70%" value="'.$adb->query_result($data_r,$k-1,'uom').'" readonly="readonly"></td>
				<td class="crmTableRow small lineOnTop">
				<input type="text" id="mySetup_Tab2_qty_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab2_qty_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" class="small" style="width:70%" value="'.$adb->query_result($data_r,$k-1,'quantity').'" readonly="readonly"></td>
				<td class="crmTableRow small lineOnTop">
				<input type="text" id="mySetup_Tab2_price_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" name="mySetup_Tab2_price_dtl_'.$row_id.'_'.$i.'_'.$adb->query_result($data_r,$k-1,'sequence_no').'" class="small" style="width:70%" value="'.$adb->query_result($data_r,$k-1,'listprice').'" readonly="readonly">
				</td>
			</tr>';
			}
			$out_put.='
		</tbody>
	</table>';
	}
	$out_put.='</div><input type="hidden" name="total_row_mySetup_Tab2_'.$row_id.'" id="total_row_mySetup_Tab2_'.$row_id.'" value="'.$num_rows.'">';
	return $out_put;
}

function getEdit_Tab3($module,$focus,$seid=''){
	global $log;
	$log->debug("Entering getAssociatedProducts(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();

	$sql="
	select
	*
	from aicrm_inventory_protab3
	left join aicrm_inventory_protab3_dtl on aicrm_inventory_protab3_dtl.id=aicrm_inventory_protab3.id
	left join aicrm_products on aicrm_products.productid=aicrm_inventory_protab3_dtl.productid
	where 1
	and aicrm_inventory_protab3.id='".$focus->id."'
	";
	
	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);
	for($i=1;$i<=$num_rows;$i++){
		$disc_name = $adb->query_result($data,$i-1,'disc_name');
		$disc_discount_type = $adb->query_result($data,$i-1,'disc_discount_type');
		$disc_dis_value = $adb->query_result($data,$i-1,'disc_dis_value');
		$disc_total = $adb->query_result($data,$i-1,'disc_total');
		$disc_discount = $adb->query_result($data,$i-1,'disc_discount');
		$disc_net = $adb->query_result($data,$i-1,'disc_net');

		$hdnProductId = $adb->query_result($data,$i-1,'productid');
		$productname = $adb->query_result($data,$i-1,'productname');
		$comment = $adb->query_result($data,$i-1,'comment');
        $pack_size = $adb->query_result($data,$i-1,'pack_size');
        $test_box = $adb->query_result($data,$i-1,'test_box');
		$uom = $adb->query_result($data,$i-1,'uom');
		$qty = $adb->query_result($data,$i-1,'quantity');
		$listPrice = $adb->query_result($data,$i-1,'listprice');

		$product_Detail[$i]['mySetup_Disc_Name'.$i] = $disc_name;
		$product_Detail[$i]['disc_discount_type'.$i] = $disc_discount_type;
		$product_Detail[$i]['disc_dis_value'.$i] = $disc_dis_value;
		$product_Detail[$i]['disc_total'.$i] = $disc_total;
		$product_Detail[$i]['disc_discount'.$i] = $disc_discount;
		$product_Detail[$i]['disc_net'.$i] = $disc_net;

		$product_Detail[$i]['mySetup_Disc_hdnProductId'.$i] = $hdnProductId;
		$product_Detail[$i]['mySetup_Disc_productName'.$i] = $productname;
		$product_Detail[$i]['mySetup_Disc_comment'.$i] = $comment;
        $product_Detail[$i]['mySetup_Disc_pack_size'.$i] = $pack_size;
        $product_Detail[$i]['mySetup_Disc_test_box'.$i] = $test_box;
		$product_Detail[$i]['mySetup_Disc_uom'.$i] = $uom;
		$product_Detail[$i]['mySetup_Disc_qty'.$i] = $qty;
		$product_Detail[$i]['mySetup_Disc_listPrice'.$i] = $listPrice;
		$product_Detail[$i]['mySetup_Disc_totalProductCount'.$i] = $num_rows;
	}
	$log->debug("Exiting getEdit_Tab3 method ...");
	return $product_Detail;
}


function getEdit_Tab_Promotion($module,$focus,$seid=''){
	global $log;
	$log->debug("Entering getAssociatedPromotion(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$promotion_Detail = Array();

	$sql="
	select
		aicrm_promotion.*,
		DATE_FORMAT(aicrm_promotion.startdate, '%d-%m-%Y') as startdate,
		DATE_FORMAT(aicrm_promotion.enddate, '%d-%m-%Y') as enddate,
		case when aicrm_promotion.set_tab = 1 then 'Product Price'
			 when aicrm_promotion.set_tab = 2 then 'Product'
			 when aicrm_promotion.set_tab = 3 then 'Discount Product'
			 else '' end as set_tab,
		aicrm_inventory_campaign_promotion.sequence_no
		from aicrm_inventory_campaign_promotion
		left join aicrm_promotion on aicrm_promotion.promotionid=aicrm_inventory_campaign_promotion.promotionid
		left join aicrm_promotioncf on aicrm_promotioncf.promotionid=aicrm_promotion.promotionid
		where 1
		and aicrm_inventory_campaign_promotion.id='".$focus->id."'
	";
	//echo $sql;
	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);
	for($i=1;$i<=$num_rows;$i++){
		$sequence_no = $adb->query_result($data,$i-1,'sequence_no');
		$promotionid = $adb->query_result($data,$i-1,'promotionid');
		$promotion_name = $adb->query_result($data,$i-1,'promotion_name');
		$set_tab = $adb->query_result($data,$i-1,'set_tab');
        $startdate = $adb->query_result($data,$i-1,'startdate');
        $enddate = $adb->query_result($data,$i-1,'enddate');
		$promotion_status = $adb->query_result($data,$i-1,'promotion_status');
		$budget_cost = $adb->query_result($data,$i-1,'budget_cost');
		$actual_cost = $adb->query_result($data,$i-1,'actual_cost');
		$expected_audience = $adb->query_result($data,$i-1,'expected_audience');
		$actual_audience = $adb->query_result($data,$i-1,'actual_audience');
		$expected_revenue = $adb->query_result($data,$i-1,'expected_revenue');
		$actual_revenue = $adb->query_result($data,$i-1,'actual_revenue');

		$promotion_Detail[$i]['sequence_no'.$i] = $sequence_no;
		$promotion_Detail[$i]['mySetup_Tab1_2_hdnpromotionid'.$i] = $promotionid;
		$promotion_Detail[$i]['mySetup_Tab1_2_promotionname'.$i] = $promotion_name;
		$promotion_Detail[$i]['promotiontype'.$i] = $set_tab;
        $promotion_Detail[$i]['startdate'.$i] = $startdate;
        $promotion_Detail[$i]['enddate'.$i] = $enddate;
		$promotion_Detail[$i]['status'.$i] = $promotion_status;
		$promotion_Detail[$i]['budgetcost'.$i] = number_format($budget_cost,2);
		$promotion_Detail[$i]['actualcost'.$i] = number_format($actual_cost,2);
		$promotion_Detail[$i]['expectedaudience'.$i] = number_format($expected_audience,2);
		$promotion_Detail[$i]['actualaudience'.$i] = number_format($actual_audience,2);
		$promotion_Detail[$i]['expectedrevenue'.$i] = number_format($expected_revenue,2);
		$promotion_Detail[$i]['actualrevenue'.$i] = number_format($actual_revenue,2);
		$promotion_Detail[$i]['mySetup_Tab1_2_totalPromotionCount'.$i] = $num_rows;
	}
	$log->debug("Exiting getEdit_Tab_Promotion method ...");
	return $promotion_Detail;
}

function getEdit_Tab_Promotion_Sum($module,$focus,$seid=''){
	global $log;
	$log->debug("Entering getAssociatedPromotionSUM(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$promotion_SUM = Array();

	$sql="
	select
		sum(aicrm_promotion.budget_cost) as budget_cost,
		sum(aicrm_promotion.actual_cost) as actual_cost,
		sum(aicrm_promotion.expected_audience) as expected_audience,
		sum(aicrm_promotion.actual_audience) as actual_audience,
		sum(aicrm_promotion.expected_revenue) as expected_revenue,
		sum(aicrm_promotion.actual_revenue) as actual_revenue
		from aicrm_inventory_campaign_promotion
		left join aicrm_promotion on aicrm_promotion.promotionid=aicrm_inventory_campaign_promotion.promotionid
		left join aicrm_promotioncf on aicrm_promotioncf.promotionid=aicrm_promotion.promotionid
		where aicrm_inventory_campaign_promotion.id='".$focus->id." group by id'
	";
	//echo $sql;
	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);
	for($i=1;$i<=$num_rows;$i++){
		$budget_cost = $adb->query_result($data,$i-1,'budget_cost');
		$actual_cost = $adb->query_result($data,$i-1,'actual_cost');
		$expected_audience = $adb->query_result($data,$i-1,'expected_audience');
		$actual_audience = $adb->query_result($data,$i-1,'actual_audience');
        $expected_revenue = $adb->query_result($data,$i-1,'expected_revenue');
        $actual_revenue = $adb->query_result($data,$i-1,'actual_revenue');
		
		$promotion_SUM[$i]['totalbudgetcost'] = number_format($budget_cost,2);
		$promotion_SUM[$i]['totalactualcost'] = number_format($actual_cost,2);
		$promotion_SUM[$i]['totalexpectedaudience'] = number_format($expected_audience,2);
		$promotion_SUM[$i]['totalactualaudience'] = number_format($actual_audience,2);
        $promotion_SUM[$i]['totalexpectedrevenue'] = number_format($expected_revenue,2);
        $promotion_SUM[$i]['totalactualrevenue'] = number_format($actual_revenue,2);
	}
	$log->debug("Exiting getAssociatedPromotionSUM method ...");
	return $promotion_SUM;
}

function getAssociatedProducts($module,$focus,$seid='')
{

	global $log;
	$log->debug("Entering getAssociatedProducts(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();

	// DG 15 Aug 2006
	// Add "ORDER BY sequence_no" to retain add order on all inventoryproductrel items
	if($module == 'InternalTraining'){
		$query="SELECT
			aicrm_contactdetails.*,
			aicrm_inventoryproductrel.*
			FROM aicrm_inventoryproductrel
			LEFT JOIN aicrm_contactdetails
			ON aicrm_contactdetails.contactid=aicrm_inventoryproductrel.productid
			LEFT JOIN aicrm_contactscf
			ON aicrm_contactscf.contactid=aicrm_inventoryproductrel.productid
			WHERE id=?
			ORDER BY sequence_no";
		$params = array($focus->id);
		$result = $adb->pquery($query, $params);

		$num_rows=$adb->num_rows($result);

		for($i=1;$i<=$num_rows;$i++)
		{
			$hdnProductId = $adb->query_result($result,$i-1,'contactid');
			$hdnProductcode = $adb->query_result($result,$i-1,'	contact_no');
			$productname=$adb->query_result($result,$i-1,'firstname')." ".$adb->query_result($result,$i-1,'lastname');
			$productdescription=$adb->query_result($result,$i-1,'product_description');
			$comment=$adb->query_result($result,$i-1,'comment');
			$qtyinstock=$adb->query_result($result,$i-1,'qtyinstock');
            $test_box=$adb->query_result($result,$i-1,'test_box');
			$qty=$adb->query_result($result,$i-1,'quantity');
			$problem=$adb->query_result($result,$i-1,'problem');
			$cause=$adb->query_result($result,$i-1,'cause');
			$protect=$adb->query_result($result,$i-1,'protect');
			$startdt=$adb->query_result($result,$i-1,'startdt');
			$tik_status=$adb->query_result($result,$i-1,'tik_status');
			$lot_no=$adb->query_result($result,$i-1,'lot_no');

			$no_head=$adb->query_result($result,$i-1,'no_head');
			$no_sub=$adb->query_result($result,$i-1,'no_sub');
			$type_pro=$adb->query_result($result,$i-1,'type_pro');
			$count_osp=$adb->query_result($result,$i-1,'count_osp');
			$purchase_cost=$adb->query_result($result,$i-1,'purchase_cost');
			$count_store=$adb->query_result($result,$i-1,'count_store');
			$cost_sales=$adb->query_result($result,$i-1,'cost_sales');
			$consult_cost=$adb->query_result($result,$i-1,'consult_cost');
			$consult_cost=$adb->query_result($result,$i-1,'consult_cost');
			$sale_com=$adb->query_result($result,$i-1,'sale_com');
			$plan_com=$adb->query_result($result,$i-1,'plan_com');
			$status1=$adb->query_result($result,$i-1,'status1');
			$status2=$adb->query_result($result,$i-1,'status2');
			$status3=$adb->query_result($result,$i-1,'status3');

			$type_application=$adb->query_result($result,$i-1,'type_application');
			$coordinator=$adb->query_result($result,$i-1,'coordinator');
			$coordinator_phone=$adb->query_result($result,$i-1,'coordinator_phone');
			$coordinator_fax=$adb->query_result($result,$i-1,'coordinator_fax');
			$coordinator_mobile=$adb->query_result($result,$i-1,'coordinator_mobile');
			$coordinator_email=$adb->query_result($result,$i-1,'coordinator_email');
			$request_cer=$adb->query_result($result,$i-1,'request_cer');
			$remark=$adb->query_result($result,$i-1,'remark');
			$payment=$adb->query_result($result,$i-1,'payment');
			$payment_status=$adb->query_result($result,$i-1,'payment_status');

			$line=$adb->query_result($result,$i-1,'line');
			$type=$adb->query_result($result,$i-1,'type');
			$tik_status=$adb->query_result($result,$i-1,'tik_status');
			$unitprice=$adb->query_result($result,$i-1,'unit_price');
			$listprice=$adb->query_result($result,$i-1,'listprice');
			$entitytype=$adb->query_result($result,$i-1,'entitytype');

			if (!empty($entitytype)) {
				$product_Detail[$i]['entityType'.$i]=$entitytype;
			}

			if($listprice == '')
				$listprice = $unitprice;
			if($qty =='')
				$qty = 1;

			//calculate productTotal
			$productTotal = $qty*$listprice;

			//Delete link in First column
			if($i != 1)
			{
				$product_Detail[$i]['delRow'.$i]="Del";
			}
			if(!isset($focus->mode) && $seid!=''){
				$sub_prod_query = $adb->pquery("SELECT crmid as prod_id from aicrm_seproductsrel WHERE productid=? AND setype='Products'",array($seid));
			} else {
				$sub_prod_query = $adb->pquery("SELECT productid as prod_id from aicrm_inventorysubproductrel WHERE id=? AND sequence_no=?",array($focus->id,$i));
			}
			$subprodid_str='';
			$subprodname_str='';

			if($adb->num_rows($sub_prod_query)>0){
				for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
					$sprod_id = $adb->query_result($sub_prod_query,$j,'prod_id');
					$sprod_name = getProductName($sprod_id);
					$str_sep = "";
					if($j>0) $str_sep = ":";
					$subprodid_str .= $str_sep.$sprod_id;
					$subprodname_str .= $str_sep." - ".$sprod_name;
				}
			}

			$subprodname_str = str_replace(":","<br>",$subprodname_str);

			$product_Detail[$i]['hdnProductId'.$i] = $hdnProductId;
			$product_Detail[$i]['productName'.$i]= from_html($productname);
			/* Added to fix the issue Product Pop-up name display*/
			if($_REQUEST['action'] == 'CreateSOPDF' || $_REQUEST['action'] == 'CreatePDF' || $_REQUEST['action'] == 'SendPDFMail')
				$product_Detail[$i]['productName'.$i]= htmlspecialchars($product_Detail[$i]['productName'.$i]);
				$product_Detail[$i]['hdnProductcode'.$i] = $hdnProductcode;
				$product_Detail[$i]['productDescription'.$i]= from_html($productdescription);
				$product_Detail[$i]['comment'.$i]= $comment;

				if($module != 'PurchaseOrder' && $focus->object_name != 'Order')
				{
					$product_Detail[$i]['qtyInStock'.$i]=$qtyinstock;
				}
            $product_Detail[$i]['test_box'.$i]=  $test_box;
			$product_Detail[$i]['qty'.$i]=  $qty;
			$product_Detail[$i]['problem'.$i] = $problem;
			$product_Detail[$i]['cause'.$i] = $cause;
			$product_Detail[$i]['protect'.$i] = $protect;
			$product_Detail[$i]['startdt'.$i] = $startdt;
			$product_Detail[$i]['tik_status'.$i] = $tik_status;
			$product_Detail[$i]['lot_no'.$i] = $lot_no;

			$product_Detail[$i]['no_head'.$i] = $no_head;
			$product_Detail[$i]['no_sub'.$i] = $no_sub;
			$product_Detail[$i]['type_pro'.$i] = $type_pro;
			$product_Detail[$i]['count_osp'.$i] = $count_osp;
			$product_Detail[$i]['purchase_cost'.$i] = $purchase_cost;
			$product_Detail[$i]['count_store'.$i] = $count_store;
			$product_Detail[$i]['cost_sales'.$i] = $cost_sales;
			$product_Detail[$i]['consult_cost'.$i] = $consult_cost;
			$product_Detail[$i]['sale_com'.$i] = $sale_com;
			$product_Detail[$i]['plan_com'.$i] = $plan_com;
			$product_Detail[$i]['status1'.$i] = $status1;
			$product_Detail[$i]['status2'.$i] = $status2;
			$product_Detail[$i]['status3'.$i] = $status3;


			$product_Detail[$i]['type_application'.$i] = $type_application;
			$product_Detail[$i]['coordinator'.$i] = $coordinator;
			$product_Detail[$i]['coordinator_phone'.$i] = $coordinator_phone;
			$product_Detail[$i]['coordinator_fax'.$i] = $coordinator_fax;
			$product_Detail[$i]['coordinator_mobile'.$i] = $coordinator_mobile;
			$product_Detail[$i]['coordinator_email'.$i] = $coordinator_email;
			$product_Detail[$i]['request_cer'.$i] = $request_cer;
			$product_Detail[$i]['remark'.$i] = $remark;
			$product_Detail[$i]['payment'.$i] = $payment;
			$product_Detail[$i]['payment_status'.$i] = $payment_status;

			$product_Detail[$i]['line'.$i] = $line;
			$product_Detail[$i]['type'.$i] = $type;
			$product_Detail[$i]['listPrice'.$i]=$listprice;
			$product_Detail[$i]['unitPrice'.$i]=$unitprice;
			$product_Detail[$i]['productTotal'.$i]=$productTotal;
			$product_Detail[$i]['subproduct_ids'.$i]=$subprodid_str;
			$product_Detail[$i]['subprod_names'.$i]=$productname;
			$discount_percent=$adb->query_result($result,$i-1,'discount_percent');
			$discount_amount=$adb->query_result($result,$i-1,'discount_amount');
				$discountTotal = '0.00';
				//Based on the discount percent or amount we will show the discount details

				//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(for Each Product)
				$product_Detail[$i]['discount_percent'.$i] = 0;
				$product_Detail[$i]['discount_amount'.$i] = 0;

			if($discount_percent != 'NULL' && $discount_percent != '')
			{
				$product_Detail[$i]['discount_type'.$i] = "percentage";
				$product_Detail[$i]['discount_percent'.$i] = $discount_percent;
				$product_Detail[$i]['checked_discount_percent'.$i] = ' checked';
				$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:visible"';
				$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:hidden"';
				$discountTotal = $productTotal*$discount_percent/100;
			}
			elseif($discount_amount != 'NULL' && $discount_amount != '')
			{
				$product_Detail[$i]['discount_type'.$i] = "amount";
				$product_Detail[$i]['discount_amount'.$i] = $discount_amount;
				$product_Detail[$i]['checked_discount_amount'.$i] = ' checked';
				$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:visible"';
				$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:hidden"';
				$discountTotal = $discount_amount;
			}
			else
			{
				$product_Detail[$i]['checked_discount_zero'.$i] = ' checked';
			}
			$totalAfterDiscount = $productTotal-$discountTotal;
			$product_Detail[$i]['discountTotal'.$i] = $discountTotal;
			$product_Detail[$i]['totalAfterDiscount'.$i] = $totalAfterDiscount;

			$taxTotal = '0.00';
			$product_Detail[$i]['taxTotal'.$i] = $taxTotal;

			//Calculate netprice
			$netPrice = $totalAfterDiscount+$taxTotal;
			//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
			if($module == 'PurchaseOrder' || $module == 'SalesOrder' || $module == 'Quotes' || $module == 'Invoice' || $module == 'Order') 
			{
				$taxtype = getInventoryTaxType($module,$focus->id);
				if($taxtype == 'individual')
				{
					//Add the tax with product total and assign to netprice
					$netPrice = $netPrice+$taxTotal;
				}
			}
			$product_Detail[$i]['netPrice'.$i] = $netPrice;

			//First we will get all associated taxes as array
			$tax_details = getTaxDetailsForProduct($hdnProductId,'all');
			//Now retrieve the tax values from the current query with the name
			for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
			{
				$tax_name = $tax_details[$tax_count]['taxname'];
				$tax_label = $tax_details[$tax_count]['taxlabel'];
				$tax_value = '0.00';

				//condition to avoid this function call when create new PO/SO/Quotes/Invoice from Product module
				if($focus->id != '')
				{
					if($taxtype == 'individual')//if individual then show the entered tax percentage
						$tax_value = getInventoryProductTaxValue($focus->id, $hdnProductId, $tax_name);
					else//if group tax then we have to show the default value when change to individual tax
						$tax_value = $tax_details[$tax_count]['percentage'];
				}
				else//if the above function not called then assign the default associated value of the product
					$tax_value = $tax_details[$tax_count]['percentage'];

				$product_Detail[$i]['taxes'][$tax_count]['taxname'] = $tax_name;
				$product_Detail[$i]['taxes'][$tax_count]['taxlabel'] = $tax_label;
				$product_Detail[$i]['taxes'][$tax_count]['percentage'] = $tax_value;
			}

		}

		//set the taxtype
		$product_Detail[1]['final_details']['taxtype'] = $taxtype;

		//Get the Final Discount, S&H charge, Tax for S&H and Adjustment values
		//To set the Final Discount details
		$finalDiscount = '0.00';
		$product_Detail[1]['final_details']['discount_type_final'] = 'zero';

		$subTotal = ($focus->column_fields['hdnSubTotal'] != '')?$focus->column_fields['hdnSubTotal']:'0.00';

		$product_Detail[1]['final_details']['hdnSubTotal'] = $subTotal;
		$discountPercent = ($focus->column_fields['hdnDiscountPercent'] != '')?$focus->column_fields['hdnDiscountPercent']:'0.00';
		$discountAmount = ($focus->column_fields['hdnDiscountAmount'] != '')?$focus->column_fields['hdnDiscountAmount']:'0.00';

		//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(For Final Discount)
		$product_Detail[1]['final_details']['discount_percentage_final'] = 0;
		$product_Detail[1]['final_details']['discount_amount_final'] = 0;

		if($focus->column_fields['hdnDiscountPercent'] != '0')
		{
			$finalDiscount = ($subTotal*$discountPercent/100);
			$product_Detail[1]['final_details']['discount_type_final'] = 'percentage';
			$product_Detail[1]['final_details']['discount_percentage_final'] = $discountPercent;
			$product_Detail[1]['final_details']['checked_discount_percentage_final'] = ' checked';
			$product_Detail[1]['final_details']['style_discount_percentage_final'] = ' style="visibility:visible"';
			$product_Detail[1]['final_details']['style_discount_amount_final'] = ' style="visibility:hidden"';
		}
		elseif($focus->column_fields['hdnDiscountAmount'] != '0')
		{
			$finalDiscount = $focus->column_fields['hdnDiscountAmount'];
			$product_Detail[1]['final_details']['discount_type_final'] = 'amount';
			$product_Detail[1]['final_details']['discount_amount_final'] = $discountAmount;
			$product_Detail[1]['final_details']['checked_discount_amount_final'] = ' checked';
			$product_Detail[1]['final_details']['style_discount_amount_final'] = ' style="visibility:visible"';
			$product_Detail[1]['final_details']['style_discount_percentage_final'] = ' style="visibility:hidden"';
		}
		$product_Detail[1]['final_details']['discountTotal_final'] = $finalDiscount;
		//To set the Final Tax values
		//we will get all taxes. if individual then show the product related taxes only else show all taxes
		//suppose user want to change individual to group or vice versa in edit time the we have to show all taxes. so that here we will store all the taxes and based on need we will show the corresponding taxes

		$taxtotal = '0.00';
		//First we should get all available taxes and then retrieve the corresponding tax values
		$tax_details = getAllTaxes('available','','edit',$focus->id);

		for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
		{
			$tax_name = $tax_details[$tax_count]['taxname'];
			$tax_label = $tax_details[$tax_count]['taxlabel'];

			//if taxtype is individual and want to change to group during edit time then we have to show the all available taxes and their default values
			//Also taxtype is group and want to change to individual during edit time then we have to provide the asspciated taxes and their default tax values for individual products
			if($taxtype == 'group')
				$tax_percent = $adb->query_result($result,0,$tax_name);
			else
				$tax_percent = $tax_details[$tax_count]['percentage'];//$adb->query_result($result,0,$tax_name);

			if($tax_percent == '' || $tax_percent == 'NULL')
				$tax_percent = '0.00';
			$taxamount = ($subTotal-$finalDiscount)*$tax_percent/100;
			$taxtotal = $taxtotal + $taxamount;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['taxname'] = $tax_name;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['taxlabel'] = $tax_label;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['percentage'] = $tax_percent;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['amount'] = $taxamount;
		}
		$product_Detail[1]['final_details']['tax_totalamount'] = $taxtotal;

		//To set the Shipping & Handling charge
		$shCharge = ($focus->column_fields['hdnS_H_Amount'] != '')?$focus->column_fields['hdnS_H_Amount']:'0.00';
		$product_Detail[1]['final_details']['shipping_handling_charge'] = $shCharge;

		//To set the Shipping & Handling tax values
		//calculate S&H tax
		$shtaxtotal = '0.00';
		//First we should get all available taxes and then retrieve the corresponding tax values
		$shtax_details = getAllTaxes('available','sh','edit',$focus->id);

		//if taxtype is group then the tax should be same for all products in aicrm_inventoryproductrel table
		for($shtax_count=0;$shtax_count<count($shtax_details);$shtax_count++)
		{
			$shtax_name = $shtax_details[$shtax_count]['taxname'];
			$shtax_label = $shtax_details[$shtax_count]['taxlabel'];
			$shtax_percent = '0.00';
			//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
			if($module == 'PurchaseOrder' || $module == 'SalesOrder' || $module == 'Quotes' || $module == 'Invoice')
			{
				$shtax_percent = getInventorySHTaxPercent($focus->id,$shtax_name);
			}
			$shtaxamount = $shCharge*$shtax_percent/100;
			$shtaxtotal = $shtaxtotal + $shtaxamount;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxname'] = $shtax_name;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxlabel'] = $shtax_label;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['percentage'] = $shtax_percent;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['amount'] = $shtaxamount;
		}
		$product_Detail[1]['final_details']['shtax_totalamount'] = $shtaxtotal;

		//To set the Adjustment value
		$adjustment = ($focus->column_fields['txtAdjustment'] != '')?$focus->column_fields['txtAdjustment']:'0.00';
		$product_Detail[1]['final_details']['adjustment'] = $adjustment;

		//To set the grand total
		$grandTotal = ($focus->column_fields['hdnGrandTotal'] != '')?$focus->column_fields['hdnGrandTotal']:'0.00';
		$product_Detail[1]['final_details']['grandTotal'] = $grandTotal;

		$log->debug("Exiting getAssociatedProducts method ...");

	}else if($module == 'Campaigns'){
		//echo "55";
		$query="select case when aicrm_premiums.premiumid != '' then aicrm_premiums.premium_name else aicrm_service.service_name end as productname," .
						" case when aicrm_premiums.premiumid != '' then 'premiums' else 'Services' end as entitytype," .
						"  aicrm_inventoryproductrel.* ,aicrm_premiums.premium_no" .
						" from aicrm_inventoryproductrel" .
						" left join aicrm_premiums on aicrm_premiums.premiumid=aicrm_inventoryproductrel.productid " .
						" left join aicrm_service on aicrm_service.serviceid=aicrm_inventoryproductrel.productid " .
						" where id=? ORDER BY sequence_no";
		//echo $focus->id." ".$query;
		$params = array($focus->id);
		$result = $adb->pquery($query, $params);
		$num_rows=$adb->num_rows($result);

		for($i=1;$i<=$num_rows;$i++)
		{
			$hdnProductId = $adb->query_result($result,$i-1,'productid');
			$hdnProductcode = $adb->query_result($result,$i-1,'premium_no');
			$productname=$adb->query_result($result,$i-1,'productname');
			$productdescription=$adb->query_result($result,$i-1,'product_description');
			$comment=$adb->query_result($result,$i-1,'comment');
			$qtyinstock=$adb->query_result($result,$i-1,'qtyinstock');
            $test_box=$adb->query_result($result,$i-1,'test_box');
			$qty=$adb->query_result($result,$i-1,'quantity');
			$problem=$adb->query_result($result,$i-1,'problem');
			//echo $problem;
			$cause=$adb->query_result($result,$i-1,'cause');
			$protect=$adb->query_result($result,$i-1,'protect');
			$startdt=$adb->query_result($result,$i-1,'startdt');
			$tik_status=$adb->query_result($result,$i-1,'tik_status');
			$lot_no=$adb->query_result($result,$i-1,'lot_no');

			$no_head=$adb->query_result($result,$i-1,'no_head');
			$no_sub=$adb->query_result($result,$i-1,'no_sub');
			$type_pro=$adb->query_result($result,$i-1,'type_pro');
			$count_osp=$adb->query_result($result,$i-1,'count_osp');
			$purchase_cost=$adb->query_result($result,$i-1,'purchase_cost');
			$count_store=$adb->query_result($result,$i-1,'count_store');
			$cost_sales=$adb->query_result($result,$i-1,'cost_sales');
			$consult_cost=$adb->query_result($result,$i-1,'consult_cost');
			$consult_cost=$adb->query_result($result,$i-1,'consult_cost');
			$sale_com=$adb->query_result($result,$i-1,'sale_com');
			$plan_com=$adb->query_result($result,$i-1,'plan_com');
			$status1=$adb->query_result($result,$i-1,'status1');
			$status2=$adb->query_result($result,$i-1,'status2');
			$status3=$adb->query_result($result,$i-1,'status3');

			$type_application=$adb->query_result($result,$i-1,'type_application');
			$coordinator=$adb->query_result($result,$i-1,'coordinator');
			$coordinator_phone=$adb->query_result($result,$i-1,'coordinator_phone');
			$coordinator_fax=$adb->query_result($result,$i-1,'coordinator_fax');
			$coordinator_mobile=$adb->query_result($result,$i-1,'coordinator_mobile');
			$coordinator_email=$adb->query_result($result,$i-1,'coordinator_email');
			$request_cer=$adb->query_result($result,$i-1,'request_cer');
			$remark=$adb->query_result($result,$i-1,'remark');
			$payment=$adb->query_result($result,$i-1,'payment');
			$payment_status=$adb->query_result($result,$i-1,'payment_status');
			$premium_code=$adb->query_result($result,$i-1,'premium_code');
			$score=$adb->query_result($result,$i-1,'score');

			//echo $type_application;

			$line=$adb->query_result($result,$i-1,'line');
			$type=$adb->query_result($result,$i-1,'type');
			$tik_status=$adb->query_result($result,$i-1,'tik_status');
			$unitprice=$adb->query_result($result,$i-1,'unit_price');
			$listprice=$adb->query_result($result,$i-1,'listprice');
			$entitytype=$adb->query_result($result,$i-1,'entitytype');
			//echo $complete;
			if (!empty($entitytype)) {
				$product_Detail[$i]['entityType'.$i]=$entitytype;
			}

			if($listprice == '')
				$listprice = $unitprice;
			if($qty =='')
				$qty = 1;

			//calculate productTotal
			$productTotal = $qty*$listprice;

			//Delete link in First column
			if($i != 1)
			{
				$product_Detail[$i]['delRow'.$i]="Del";
			}
			if(!isset($focus->mode) && $seid!=''){
				$sub_prod_query = $adb->pquery("SELECT crmid as prod_id from aicrm_seproductsrel WHERE productid=? AND setype='Products'",array($seid));
			} else {
				$sub_prod_query = $adb->pquery("SELECT productid as prod_id from aicrm_inventorysubproductrel WHERE id=? AND sequence_no=?",array($focus->id,$i));
			}
			$subprodid_str='';
			$subprodname_str='';

			if($adb->num_rows($sub_prod_query)>0){
				for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
					$sprod_id = $adb->query_result($sub_prod_query,$j,'prod_id');
					$sprod_name = getProductName($sprod_id);
					$str_sep = "";
					if($j>0) $str_sep = ":";
					$subprodid_str .= $str_sep.$sprod_id;
					$subprodname_str .= $str_sep." - ".$sprod_name;
				}
			}

			$subprodname_str = str_replace(":","<br>",$subprodname_str);

			$product_Detail[$i]['hdnProductId'.$i] = $hdnProductId;
			$product_Detail[$i]['productName'.$i]= from_html($productname);
			/* Added to fix the issue Product Pop-up name display*/
			if($_REQUEST['action'] == 'CreateSOPDF' || $_REQUEST['action'] == 'CreatePDF' || $_REQUEST['action'] == 'SendPDFMail')
				$product_Detail[$i]['productName'.$i]= htmlspecialchars($product_Detail[$i]['productName'.$i]);
				$product_Detail[$i]['hdnProductcode'.$i] = $hdnProductcode;
				$product_Detail[$i]['productDescription'.$i]= from_html($productdescription);
				$product_Detail[$i]['comment'.$i]= $comment;

				if($module != 'PurchaseOrder' && $focus->object_name != 'Order')
				{
					$product_Detail[$i]['qtyInStock'.$i]=$qtyinstock;
				}
            $product_Detail[$i]['test_box'.$i]=$test_box;
			$product_Detail[$i]['qty'.$i]=$qty;
			$product_Detail[$i]['problem'.$i] = $problem;
			$product_Detail[$i]['cause'.$i] = $cause;
			$product_Detail[$i]['protect'.$i] = $protect;
			$product_Detail[$i]['startdt'.$i] = $startdt;
			$product_Detail[$i]['tik_status'.$i] = $tik_status;
			$product_Detail[$i]['lot_no'.$i] = $lot_no;

			$product_Detail[$i]['no_head'.$i] = $no_head;
			$product_Detail[$i]['no_sub'.$i] = $no_sub;
			$product_Detail[$i]['type_pro'.$i] = $type_pro;
			$product_Detail[$i]['count_osp'.$i] = $count_osp;
			$product_Detail[$i]['purchase_cost'.$i] = $purchase_cost;
			$product_Detail[$i]['count_store'.$i] = $count_store;
			$product_Detail[$i]['cost_sales'.$i] = $cost_sales;
			$product_Detail[$i]['consult_cost'.$i] = $consult_cost;
			$product_Detail[$i]['sale_com'.$i] = $sale_com;
			$product_Detail[$i]['plan_com'.$i] = $plan_com;
			$product_Detail[$i]['status1'.$i] = $status1;
			$product_Detail[$i]['status2'.$i] = $status2;
			$product_Detail[$i]['status3'.$i] = $status3;


			$product_Detail[$i]['type_application'.$i] = $type_application;
			$product_Detail[$i]['coordinator'.$i] = $coordinator;
			$product_Detail[$i]['coordinator_phone'.$i] = $coordinator_phone;
			$product_Detail[$i]['coordinator_fax'.$i] = $coordinator_fax;
			$product_Detail[$i]['coordinator_mobile'.$i] = $coordinator_mobile;
			$product_Detail[$i]['coordinator_email'.$i] = $coordinator_email;
			$product_Detail[$i]['request_cer'.$i] = $request_cer;
			$product_Detail[$i]['remark'.$i] = $remark;
			$product_Detail[$i]['payment'.$i] = $payment;
			$product_Detail[$i]['payment_status'.$i] = $payment_status;
			$product_Detail[$i]['premium_code'.$i] = $premium_code;
			$product_Detail[$i]['score'.$i] = $score;

			$product_Detail[$i]['line'.$i] = $line;
			$product_Detail[$i]['type'.$i] = $type;
			$product_Detail[$i]['listPrice'.$i]=$listprice;
			$product_Detail[$i]['unitPrice'.$i]=$unitprice;
			$product_Detail[$i]['productTotal'.$i]=$productTotal;
			$product_Detail[$i]['subproduct_ids'.$i]=$subprodid_str;
			$product_Detail[$i]['subprod_names'.$i]=$productname;
			$discount_percent=$adb->query_result($result,$i-1,'discount_percent');
			$discount_amount=$adb->query_result($result,$i-1,'discount_amount');
				$discountTotal = '0.00';
				//Based on the discount percent or amount we will show the discount details

				//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(for Each Product)
				$product_Detail[$i]['discount_percent'.$i] = 0;
				$product_Detail[$i]['discount_amount'.$i] = 0;

			if($discount_percent != 'NULL' && $discount_percent != '')
			{
				$product_Detail[$i]['discount_type'.$i] = "percentage";
				$product_Detail[$i]['discount_percent'.$i] = $discount_percent;
				$product_Detail[$i]['checked_discount_percent'.$i] = ' checked';
				$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:visible"';
				$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:hidden"';
				$discountTotal = $productTotal*$discount_percent/100;
			}
			elseif($discount_amount != 'NULL' && $discount_amount != '')
			{
				$product_Detail[$i]['discount_type'.$i] = "amount";
				$product_Detail[$i]['discount_amount'.$i] = $discount_amount;
				$product_Detail[$i]['checked_discount_amount'.$i] = ' checked';
				$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:visible"';
				$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:hidden"';
				$discountTotal = $discount_amount;
			}
			else
			{
				$product_Detail[$i]['checked_discount_zero'.$i] = ' checked';
			}
			$totalAfterDiscount = $productTotal-$discountTotal;
			$product_Detail[$i]['discountTotal'.$i] = $discountTotal;
			$product_Detail[$i]['totalAfterDiscount'.$i] = $totalAfterDiscount;

			$taxTotal = '0.00';
			$product_Detail[$i]['taxTotal'.$i] = $taxTotal;

			//Calculate netprice
			$netPrice = $totalAfterDiscount+$taxTotal;
			//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
			if($module == 'PurchaseOrder' || $module == 'SalesOrder' || $module == 'Quotes' || $module == 'Invoice')
			{
				$taxtype = getInventoryTaxType($module,$focus->id);
				if($taxtype == 'individual')
				{
					//Add the tax with product total and assign to netprice
					$netPrice = $netPrice+$taxTotal;
				}
			}
			$product_Detail[$i]['netPrice'.$i] = $netPrice;

			//First we will get all associated taxes as array
			$tax_details = getTaxDetailsForProduct($hdnProductId,'all');
			//Now retrieve the tax values from the current query with the name
			for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
			{
				$tax_name = $tax_details[$tax_count]['taxname'];
				$tax_label = $tax_details[$tax_count]['taxlabel'];
				$tax_value = '0.00';

				//condition to avoid this function call when create new PO/SO/Quotes/Invoice from Product module
				if($focus->id != '')
				{
					if($taxtype == 'individual')//if individual then show the entered tax percentage
						$tax_value = getInventoryProductTaxValue($focus->id, $hdnProductId, $tax_name);
					else//if group tax then we have to show the default value when change to individual tax
						$tax_value = $tax_details[$tax_count]['percentage'];
				}
				else//if the above function not called then assign the default associated value of the product
					$tax_value = $tax_details[$tax_count]['percentage'];

				$product_Detail[$i]['taxes'][$tax_count]['taxname'] = $tax_name;
				$product_Detail[$i]['taxes'][$tax_count]['taxlabel'] = $tax_label;
				$product_Detail[$i]['taxes'][$tax_count]['percentage'] = $tax_value;
			}

		}
		//set the taxtype
		$product_Detail[1]['final_details']['taxtype'] = $taxtype;

		//Get the Final Discount, S&H charge, Tax for S&H and Adjustment values
		//To set the Final Discount details
		$finalDiscount = '0.00';
		$product_Detail[1]['final_details']['discount_type_final'] = 'zero';

		$subTotal = ($focus->column_fields['hdnSubTotal'] != '')?$focus->column_fields['hdnSubTotal']:'0.00';

		$product_Detail[1]['final_details']['hdnSubTotal'] = $subTotal;
		$discountPercent = ($focus->column_fields['hdnDiscountPercent'] != '')?$focus->column_fields['hdnDiscountPercent']:'0.00';
		$discountAmount = ($focus->column_fields['hdnDiscountAmount'] != '')?$focus->column_fields['hdnDiscountAmount']:'0.00';

		//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(For Final Discount)
		$product_Detail[1]['final_details']['discount_percentage_final'] = 0;
		$product_Detail[1]['final_details']['discount_amount_final'] = 0;

		if($focus->column_fields['hdnDiscountPercent'] != '0')
		{
			$finalDiscount = ($subTotal*$discountPercent/100);
			$product_Detail[1]['final_details']['discount_type_final'] = 'percentage';
			$product_Detail[1]['final_details']['discount_percentage_final'] = $discountPercent;
			$product_Detail[1]['final_details']['checked_discount_percentage_final'] = ' checked';
			$product_Detail[1]['final_details']['style_discount_percentage_final'] = ' style="visibility:visible"';
			$product_Detail[1]['final_details']['style_discount_amount_final'] = ' style="visibility:hidden"';
		}
		elseif($focus->column_fields['hdnDiscountAmount'] != '0')
		{
			$finalDiscount = $focus->column_fields['hdnDiscountAmount'];
			$product_Detail[1]['final_details']['discount_type_final'] = 'amount';
			$product_Detail[1]['final_details']['discount_amount_final'] = $discountAmount;
			$product_Detail[1]['final_details']['checked_discount_amount_final'] = ' checked';
			$product_Detail[1]['final_details']['style_discount_amount_final'] = ' style="visibility:visible"';
			$product_Detail[1]['final_details']['style_discount_percentage_final'] = ' style="visibility:hidden"';
		}
		$product_Detail[1]['final_details']['discountTotal_final'] = $finalDiscount;

		//To set the Final Tax values
		//we will get all taxes. if individual then show the product related taxes only else show all taxes
		//suppose user want to change individual to group or vice versa in edit time the we have to show all taxes. so that here we will store all the taxes and based on need we will show the corresponding taxes

		$taxtotal = '0.00';
		//First we should get all available taxes and then retrieve the corresponding tax values
		$tax_details = getAllTaxes('available','','edit',$focus->id);

		for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
		{
			$tax_name = $tax_details[$tax_count]['taxname'];
			$tax_label = $tax_details[$tax_count]['taxlabel'];

			//if taxtype is individual and want to change to group during edit time then we have to show the all available taxes and their default values
			//Also taxtype is group and want to change to individual during edit time then we have to provide the asspciated taxes and their default tax values for individual products
			if($taxtype == 'group')
				$tax_percent = $adb->query_result($result,0,$tax_name);
			else
				$tax_percent = $tax_details[$tax_count]['percentage'];//$adb->query_result($result,0,$tax_name);

			if($tax_percent == '' || $tax_percent == 'NULL')
				$tax_percent = '0.00';
			$taxamount = ($subTotal-$finalDiscount)*$tax_percent/100;
			$taxtotal = $taxtotal + $taxamount;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['taxname'] = $tax_name;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['taxlabel'] = $tax_label;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['percentage'] = $tax_percent;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['amount'] = $taxamount;
		}
		$product_Detail[1]['final_details']['tax_totalamount'] = $taxtotal;

		//To set the Shipping & Handling charge
		$shCharge = ($focus->column_fields['hdnS_H_Amount'] != '')?$focus->column_fields['hdnS_H_Amount']:'0.00';
		$product_Detail[1]['final_details']['shipping_handling_charge'] = $shCharge;

		//To set the Shipping & Handling tax values
		//calculate S&H tax
		$shtaxtotal = '0.00';
		//First we should get all available taxes and then retrieve the corresponding tax values
		$shtax_details = getAllTaxes('available','sh','edit',$focus->id);

		//if taxtype is group then the tax should be same for all products in aicrm_inventoryproductrel table
		for($shtax_count=0;$shtax_count<count($shtax_details);$shtax_count++)
		{
			$shtax_name = $shtax_details[$shtax_count]['taxname'];
			$shtax_label = $shtax_details[$shtax_count]['taxlabel'];
			$shtax_percent = '0.00';
			//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
			if($module == 'PurchaseOrder' || $module == 'SalesOrder' || $module == 'Quotes' || $module == 'Invoice')
			{
				$shtax_percent = getInventorySHTaxPercent($focus->id,$shtax_name);
			}
			$shtaxamount = $shCharge*$shtax_percent/100;
			$shtaxtotal = $shtaxtotal + $shtaxamount;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxname'] = $shtax_name;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxlabel'] = $shtax_label;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['percentage'] = $shtax_percent;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['amount'] = $shtaxamount;
		}
		$product_Detail[1]['final_details']['shtax_totalamount'] = $shtaxtotal;

		//To set the Adjustment value
		$adjustment = ($focus->column_fields['txtAdjustment'] != '')?$focus->column_fields['txtAdjustment']:'0.00';
		$product_Detail[1]['final_details']['adjustment'] = $adjustment;

		//To set the grand total
		$grandTotal = ($focus->column_fields['hdnGrandTotal'] != '')?$focus->column_fields['hdnGrandTotal']:'0.00';
		$product_Detail[1]['final_details']['grandTotal'] = $grandTotal;

		$log->debug("Exiting getAssociatedProducts method ...");
	
	}else if($module == 'PriceList'){

		$query="SELECT
			case when aicrm_inventoryproductrel.module = 'Products' then aicrm_products.productname 
				 when aicrm_inventoryproductrel.module = 'Service' then aicrm_service.service_name 
				 when aicrm_inventoryproductrel.module = 'Sparepart' then aicrm_sparepart.sparepart_name 
				 else '' end as productname,
			aicrm_products.productcategory ,
			case when aicrm_inventoryproductrel.module = 'Products' then aicrm_products.productstatus 
  				 when aicrm_inventoryproductrel.module = 'Service' then ''
  				 when aicrm_inventoryproductrel.module = 'Sparepart' then aicrm_sparepart.sparepart_status 
  				 else '' end as productstatus,
			aicrm_products.unit ,
			case when aicrm_products.productid != '' then 'Products' else 'Services' end as entitytype,
			aicrm_inventoryproductrel.listprice,
			aicrm_inventoryproductrel.description AS product_description,
			aicrm_inventoryproductrel.*
			FROM aicrm_inventoryproductrel
			LEFT JOIN aicrm_products ON aicrm_products.productid=aicrm_inventoryproductrel.productid
			LEFT JOIN aicrm_service ON aicrm_service.serviceid=aicrm_inventoryproductrel.productid
			left join aicrm_sparepart on aicrm_sparepart.sparepartid=aicrm_inventoryproductrel.productid
			WHERE id=?
			ORDER BY sequence_no";

		$params = array($focus->id);
		$result = $adb->pquery($query, $params);
		$num_rows=$adb->num_rows($result);

		for($i=1;$i<=$num_rows;$i++)
		{
			$hdnProductId = $adb->query_result($result,$i-1,'productid');
			$productname=$adb->query_result($result,$i-1,'productname');
			$productdescription=$adb->query_result($result,$i-1,'product_description');
			$comment=$adb->query_result($result,$i-1,'comment');
			$listPrice=$adb->query_result($result,$i-1,'listprice');

			$rel_module =$adb->query_result($result,$i-1,'module');
			$monetary =$adb->query_result($result,$i-1,'monetary');
			$productcategory=$adb->query_result($result,$i-1,'productcategory');
			$productstatus=$adb->query_result($result,$i-1,'productstatus');
			$unit=$adb->query_result($result,$i-1,'unit');
			$entityType =$adb->query_result($result,$i-1,'entityType');

			$product_Detail[$i]['productName'.$i]= htmlspecialchars($productname);
			$product_Detail[$i]['hdnProductId'.$i]= $hdnProductId;
			$product_Detail[$i]['comment'.$i]= $comment;	
			$product_Detail[$i]['listPrice'.$i]= $listPrice;
			$product_Detail[$i]['productcategory'.$i]= $productcategory;
			//$product_Detail[$i]['productstatus'.$i]= ($productstatus == '1') ? 'Active' : 'InActive';
			if($rel_module == 'Products'){
			  	$product_Detail[$i]['productstatus'.$i]= ($productstatus == '1') ? 'Active' : 'InActive';
			}else if($rel_module == 'Sparepart'){
			  	$product_Detail[$i]['productstatus'.$i]= $productstatus;
			}else{
			  	$product_Detail[$i]['productstatus'.$i]='';
			}
			$product_Detail[$i]['monetary'.$i]= $monetary;
			$product_Detail[$i]['unit'.$i]= $unit;
			$product_Detail[$i]['entityType'.$i]= $entityType;

			$product_Detail[$i]['rel_module'.$i]= $rel_module;
			
		}

		$log->debug("Exiting getAssociatedProducts method ...");

	}else if($module == 'Deal'){
		
		$query="SELECT
			case when aicrm_inventoryproductrel.module = 'Products' then aicrm_products.productname 
				 when aicrm_inventoryproductrel.module = 'Service' then aicrm_service.service_name 
				 when aicrm_inventoryproductrel.module = 'Sparepart' then aicrm_sparepart.sparepart_name 
			else '' end as productname,
			case when aicrm_inventoryproductrel.module = 'Products' then aicrm_products.product_no
				 when aicrm_inventoryproductrel.module = 'Service' then aicrm_service.service_no 
				 when aicrm_inventoryproductrel.module = 'Sparepart' then aicrm_sparepart.sparepart_no 
			else '' end as productcode,

			case when aicrm_products.productid != '' then aicrm_products.sellingprice else aicrm_service.unit_price end as unit_price,
			case when aicrm_products.productid != '' then aicrm_products.stockavailable else 'NA' end as qtyinstock,
			aicrm_crmentity.setype as entitytype,
			aicrm_inventoryproductrel.listprice,
			aicrm_inventoryproductrel.description AS product_description,
			aicrm_products.productcategory,
			aicrm_inventoryproductrel.*
			FROM aicrm_inventoryproductrel

			LEFT join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inventoryproductrel.productid
			LEFT JOIN aicrm_products ON aicrm_products.productid=aicrm_inventoryproductrel.productid
			LEFT JOIN aicrm_service ON aicrm_service.serviceid=aicrm_inventoryproductrel.productid
			LEFT JOIN aicrm_sparepart ON aicrm_sparepart.sparepartid=aicrm_inventoryproductrel.productid
			WHERE id=?
			ORDER BY sequence_no";
		
		$params = array($focus->id);
	
	}else{
		if($module == 'Projects'|| $module == 'Quotes'|| $module == 'Salesorder'|| $module == 'Order'|| $module == 'HelpDesk')
		{	
			$query="SELECT
					
						case when aicrm_inventoryproductrel.module = 'Products' then aicrm_products.productname 
							 when aicrm_inventoryproductrel.module = 'Service' then aicrm_service.servicename 
							 when aicrm_inventoryproductrel.module = 'Sparepart' then aicrm_sparepart.sparepart_name 
						else '' end as productname,
						
						
						case when aicrm_inventoryproductrel.module = 'Products' then aicrm_products.product_no
							 when aicrm_inventoryproductrel.module = 'Service' then aicrm_service.service_no 
							 when aicrm_inventoryproductrel.module = 'Sparepart' then aicrm_sparepart.sparepart_no 
						else '' end as productcode,

						case when aicrm_products.productid != '' then aicrm_products.sellingprice else aicrm_service.unit_price end as unit_price,
						case when aicrm_products.productid != '' then aicrm_products.stockavailable else 'NA' end as qtyinstock,
						aicrm_crmentity.setype as entitytype,
						aicrm_inventoryproductrel.listprice,
						aicrm_inventoryproductrel.description AS product_description,
						aicrm_products.productcategory,
						aicrm_inventoryproductrel.*
						FROM aicrm_inventoryproductrel

						LEFT join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inventoryproductrel.productid
						LEFT JOIN aicrm_products ON aicrm_products.productid=aicrm_inventoryproductrel.productid
						LEFT JOIN aicrm_service ON aicrm_service.serviceid=aicrm_inventoryproductrel.productid
						LEFT JOIN aicrm_sparepart ON aicrm_sparepart.sparepartid=aicrm_inventoryproductrel.productid
						WHERE id=?
						ORDER BY sequence_no";
			
				// echo $query;
				$params = array($focus->id);
		}
		elseif($module == 'Potentials')
		{
			$query="SELECT
									aicrm_products.productname,
									aicrm_products.productcode,
									aicrm_products.unit_price,
									aicrm_products.qtyinstock,
									aicrm_seproductsrel.*,
									aicrm_crmentity.description AS product_description
									FROM aicrm_products
									INNER JOIN aicrm_crmentity
											ON aicrm_crmentity.crmid=aicrm_products.productid
									INNER JOIN aicrm_seproductsrel
											ON aicrm_seproductsrel.productid=aicrm_products.productid
									WHERE aicrm_seproductsrel.crmid=?";
				$params = array($seid);
		}
		elseif($module == 'Products')
		{
			$query="SELECT
									aicrm_products.productid,
									aicrm_products.productcode,
									aicrm_products.productname,
									aicrm_products.unit_price,
									aicrm_products.qtyinstock,
									aicrm_crmentity.description AS product_description,
									'Products' AS entitytype
									FROM aicrm_products
									INNER JOIN aicrm_crmentity
											ON aicrm_crmentity.crmid=aicrm_products.productid
									WHERE aicrm_crmentity.deleted=0
											AND productid=?";
				$params = array($seid);
		}
		
		$result = $adb->pquery($query, $params);
		$num_rows=$adb->num_rows($result);

		for($i=1;$i<=$num_rows;$i++)
		{
			$hdnProductId = $adb->query_result($result,$i-1,'productid');
			$hdnProductcode = $adb->query_result($result,$i-1,'productcode');
			$productname=$adb->query_result($result,$i-1,'productname');
			$hdn_cal_total = $adb->query_result($result,$i-1,'hdn_cal_total');
			$hdn_cal_discount = $adb->query_result($result,$i-1,'hdn_cal_discount');

			$productdescription=$adb->query_result($result,$i-1,'product_description');
			$comment=$adb->query_result($result,$i-1,'comment');
			$qtyinstock=$adb->query_result($result,$i-1,'qtyinstock');
			$productcategory=$adb->query_result($result,$i-1,'productcategory');
            $test_box=$adb->query_result($result,$i-1,'test_box');
			$qty=$adb->query_result($result,$i-1,'quantity');
			$problem=$adb->query_result($result,$i-1,'problem');
			//echo $problem;
			$cause=$adb->query_result($result,$i-1,'cause');
			$protect=$adb->query_result($result,$i-1,'protect');
			$startdt=$adb->query_result($result,$i-1,'startdt');
			$tik_status=$adb->query_result($result,$i-1,'tik_status');
			$lot_no=$adb->query_result($result,$i-1,'lot_no');

			$no_head=$adb->query_result($result,$i-1,'no_head');
			$no_sub=$adb->query_result($result,$i-1,'no_sub');
			$type_pro=$adb->query_result($result,$i-1,'type_pro');
			$count_osp=$adb->query_result($result,$i-1,'count_osp');
			$purchase_cost=$adb->query_result($result,$i-1,'purchase_cost');
			$count_store=$adb->query_result($result,$i-1,'count_store');
			$cost_sales=$adb->query_result($result,$i-1,'cost_sales');
			$consult_cost=$adb->query_result($result,$i-1,'consult_cost');
			$consult_cost=$adb->query_result($result,$i-1,'consult_cost');
			$sale_com=$adb->query_result($result,$i-1,'sale_com');
			$plan_com=$adb->query_result($result,$i-1,'plan_com');
			$status1=$adb->query_result($result,$i-1,'status1');
			$status2=$adb->query_result($result,$i-1,'status2');
			$status3=$adb->query_result($result,$i-1,'status3');

			$type_application=$adb->query_result($result,$i-1,'type_application');
			$coordinator=$adb->query_result($result,$i-1,'coordinator');
			$coordinator_phone=$adb->query_result($result,$i-1,'coordinator_phone');
			$coordinator_fax=$adb->query_result($result,$i-1,'coordinator_fax');
			$coordinator_mobile=$adb->query_result($result,$i-1,'coordinator_mobile');
			$coordinator_email=$adb->query_result($result,$i-1,'coordinator_email');
			$request_cer=$adb->query_result($result,$i-1,'request_cer');
			$remark=$adb->query_result($result,$i-1,'remark');
			$payment=$adb->query_result($result,$i-1,'payment');
			$payment_status=$adb->query_result($result,$i-1,'payment_status');
            $pack_size=$adb->query_result($result,$i-1,'pack_size');
			//$test_box=$adb->query_result($result,$i-1,'test_box');
			$uom=$adb->query_result($result,$i-1,'uom');
			$line=$adb->query_result($result,$i-1,'line');
			$type=$adb->query_result($result,$i-1,'type');
			$tik_status=$adb->query_result($result,$i-1,'tik_status');
			$unitprice=$adb->query_result($result,$i-1,'unit_price');
			$listprice=$adb->query_result($result,$i-1,'listprice');

			$listprice_inc=$adb->query_result($result,$i-1,'listprice_inc');
			$listprice_exc=$adb->query_result($result,$i-1,'listprice_exc');

			$entitytype=$adb->query_result($result,$i-1,'entitytype');

			$pro_type=$adb->query_result($result,$i-1,'pro_type');
			$promotion_id=$adb->query_result($result,$i-1,'promotion_id');

			/*$listprice_project=$adb->query_result($result,$i-1,'listprice');*/
			$qty_act=$adb->query_result($result,$i-1,'quantity_act');

			$listprice_total=$adb->query_result($result,$i-1,'listprice_total');
			$qty_ship=$adb->query_result($result,$i-1,'quantity_ship');
			$status_dtl=$adb->query_result($result,$i-1,'status');

			$qty_remain=$adb->query_result($result,$i-1,'quantity_remain');

			$price_list_std=$adb->query_result($result,$i-1,'standard_price');
			$price_list_inv=$adb->query_result($result,$i-1,'last_price');

			$product_name=$adb->query_result($result,$i-1,'product_name');
			$products_business=$adb->query_result($result,$i-1,'products_businessplusno');

			$product_finish=$adb->query_result($result,$i-1,'product_finish');
			$product_size_mm=$adb->query_result($result,$i-1,'product_size_mm');
			$product_thinkness=$adb->query_result($result,$i-1,'product_thinkness');
			$product_cost_avg=$adb->query_result($result,$i-1,'product_cost_avg');
			$competitor_price=$adb->query_result($result,$i-1,'competitor_price');

			$competitor_brand=$adb->query_result($result,$i-1,'competitor_brand');
			$compet_brand_in_proj=$adb->query_result($result,$i-1,'compet_brand_in_proj');
			$compet_brand_in_proj_price=$adb->query_result($result,$i-1,'compet_brand_in_proj_price');
			$product_unit=$adb->query_result($result,$i-1,'product_unit');
			$selling_price=$adb->query_result($result,$i-1,'selling_price');


			$package_size_sheet_per_box=$adb->query_result($result,$i-1,'package_size_sheet_per_box');
			$package_size_sqm_per_box=$adb->query_result($result,$i-1,'package_size_sqm_per_box');
			$box_quantity=$adb->query_result($result,$i-1,'box_quantity');
			$sales_unit=$adb->query_result($result,$i-1,'sales_unit');
			$sheet_quantity=$adb->query_result($result,$i-1,'sheet_quantity');
			$sqm_quantity=$adb->query_result($result,$i-1,'sqm_quantity');
			$regular_price=$adb->query_result($result,$i-1,'regular_price');
			$product_discount=$adb->query_result($result,$i-1,'product_discount');
			$product_price_type=$adb->query_result($result,$i-1,'product_price_type');
			$pricelist_type=$adb->query_result($result,$i-1,'pricelist_type');
			if (!empty($entitytype)) {
				$product_Detail[$i]['entityType'.$i]=$entitytype;
			}

			if($listprice == '')
				$listprice = $unitprice;
			if($qty =='')
				$qty = 1;

			//calculate productTotal
			$productTotal = $qty*$listprice;

			//Delete link in First column
			if($i != 1)
			{
				$product_Detail[$i]['delRow'.$i]="Del";
			}
			if(!isset($focus->mode) && $seid!=''){
				$sub_prod_query = $adb->pquery("SELECT crmid as prod_id from aicrm_seproductsrel WHERE productid=? AND setype='Products'",array($seid));
			} else {
				$sub_prod_query = $adb->pquery("SELECT productid as prod_id from aicrm_inventorysubproductrel WHERE id=? AND sequence_no=?",array($focus->id,$i));
			}
			$subprodid_str='';
			$subprodname_str='';

			if($adb->num_rows($sub_prod_query)>0){
				for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
					$sprod_id = $adb->query_result($sub_prod_query,$j,'prod_id');
					$sprod_name = getProductName($sprod_id);
					$str_sep = "";
					if($j>0) $str_sep = ":";
					$subprodid_str .= $str_sep.$sprod_id;
					$subprodname_str .= $str_sep." - ".$sprod_name;
				}
			}

			$subprodname_str = str_replace(":","<br>",$subprodname_str);

			$product_Detail[$i]['hdnProductId'.$i] = $hdnProductId;
			$product_Detail[$i]['productName'.$i]= from_html($productname);

			$product_Detail[$i]['hdn_cal_total'.$i] = $hdn_cal_total;
			$product_Detail[$i]['hdn_cal_discount'.$i] = $hdn_cal_discount;
			/* Added to fix the issue Product Pop-up name display*/
			if($_REQUEST['action'] == 'CreateSOPDF' || $_REQUEST['action'] == 'CreatePDF' || $_REQUEST['action'] == 'SendPDFMail')
				$product_Detail[$i]['productName'.$i]= htmlspecialchars($product_Detail[$i]['productName'.$i]);
				$product_Detail[$i]['hdnProductcode'.$i] = $hdnProductcode;
				$product_Detail[$i]['productDescription'.$i]= from_html($productdescription);
				$product_Detail[$i]['comment'.$i]= $comment;

				if($module != 'PurchaseOrder' && $focus->object_name != 'Order')
				{
					$product_Detail[$i]['qtyInStock'.$i]=$qtyinstock;
				}
			if($module=="Quotes" || $module=="Order"){
				$product_Detail[$i]['productName'.$i]=$product_name;
			}
			$product_Detail[$i]['price_list_std'.$i]=number_format($price_list_std,2, '.', '');
			$product_Detail[$i]['price_list_inv'.$i]=number_format($price_list_inv,2, '.', '');
			$product_Detail[$i]['productcode'.$i] = $hdnProductcode;
            $product_Detail[$i]['test_box'.$i]= $test_box==0 ? '':number_format($test_box, 2 , '.', '');
			$product_Detail[$i]['qty'.$i]= $qty==0 ? '':number_format($qty, 2 , '.', '');
			$product_Detail[$i]['problem'.$i] = $problem;
			$product_Detail[$i]['cause'.$i] = $cause;
			$product_Detail[$i]['protect'.$i] = $protect;
			$product_Detail[$i]['startdt'.$i] = $startdt;
			$product_Detail[$i]['tik_status'.$i] = $tik_status;
			$product_Detail[$i]['lot_no'.$i] = $lot_no;
            $product_Detail[$i]['pack_size'.$i] = $pack_size;
			$product_Detail[$i]['uom'.$i] = $uom;
			$product_Detail[$i]['productcategory'.$i] = $productcategory;
			$product_Detail[$i]['no_head'.$i] = $no_head;
			$product_Detail[$i]['no_sub'.$i] = $no_sub;
			$product_Detail[$i]['type_pro'.$i] = $type_pro;
			$product_Detail[$i]['count_osp'.$i] = $count_osp;
			$product_Detail[$i]['purchase_cost'.$i] = $purchase_cost;
			$product_Detail[$i]['count_store'.$i] = $count_store;
			$product_Detail[$i]['cost_sales'.$i] = $cost_sales;
			$product_Detail[$i]['consult_cost'.$i] = $consult_cost;
			$product_Detail[$i]['sale_com'.$i] = $sale_com;
			$product_Detail[$i]['plan_com'.$i] = $plan_com;
			$product_Detail[$i]['status1'.$i] = $status1;
			$product_Detail[$i]['status2'.$i] = $status2;
			$product_Detail[$i]['status3'.$i] = $status3;

			$product_Detail[$i]['line'.$i] = $line;
			$product_Detail[$i]['type'.$i] = $type;
            $product_Detail[$i]['listPrice'.$i]= $test_box==0&&$listprice==0 ? '':number_format($listprice, 2, '.', '');
			$product_Detail[$i]['listPrice'.$i]= $qty==0&&$listprice==0 ? '':number_format($listprice, 2, '.', '');
			$product_Detail[$i]['listprice_inc'.$i]=number_format($listprice_inc,2, '.', '');
			$product_Detail[$i]['listprice_exc'.$i]=number_format($listprice_exc,2, '.', '');
			$product_Detail[$i]['unitPrice'.$i]=number_format($unitprice,2, '.', '');
			$product_Detail[$i]['productTotal'.$i]=number_format($productTotal,2, '.', '');
			$product_Detail[$i]['subproduct_ids'.$i]=$subprodid_str;
			$product_Detail[$i]['subprod_names'.$i]=$subprodname_str;

			$product_Detail[$i]['business_code'.$i]=$products_business;

			$discount_percent=$adb->query_result($result,$i-1,'discount_percent');
			$discount_amount=$adb->query_result($result,$i-1,'discount_amount');
			$discount_coupon=$adb->query_result($result,$i-1,'discount_coupon');

			$product_Detail[$i]['listprice_project'.$i]=$listprice_project;
			$product_Detail[$i]['qty_act'.$i]=number_format($qty_act, 0, '.', '');
			$product_Detail[$i]['listprice_total'.$i]=$listprice_total;
			$product_Detail[$i]['qty_ship'.$i]=number_format($qty_ship, 0, '.', '');
			$product_Detail[$i]['status_dtl'.$i]=$status_dtl;
			$product_Detail[$i]['qty_remain'.$i]=number_format($qty_remain, 0, '.', '');

			$product_Detail[$i]['product_finish'.$i]=$product_finish;
			$product_Detail[$i]['product_size_mm'.$i]=$product_size_mm;
			$product_Detail[$i]['product_thinkness'.$i]=$product_thinkness;
			$product_Detail[$i]['product_cost_avg'.$i]=$product_cost_avg;
			$product_Detail[$i]['competitor_price'.$i]=$competitor_price;

			$product_Detail[$i]['competitor_brand'.$i]=$competitor_brand;
			$product_Detail[$i]['compet_brand_in_proj'.$i]=$compet_brand_in_proj;
			$product_Detail[$i]['compet_brand_in_proj_price'.$i]=$compet_brand_in_proj_price;
			$product_Detail[$i]['product_unit'.$i]=$product_unit;
			$product_Detail[$i]['selling_price'.$i]=$selling_price;
			$product_Detail[$i]['pro_type'.$i] = $pro_type;
			$product_Detail[$i]['promotion_id'.$i] = $promotion_id;

			$product_Detail[$i]['package_size_sheet_per_box'.$i]=$package_size_sheet_per_box;
			$product_Detail[$i]['package_size_sqm_per_box'.$i]=$package_size_sqm_per_box;
			$product_Detail[$i]['box_quantity'.$i]=$box_quantity;
			$product_Detail[$i]['sales_unit'.$i]=$sales_unit;
			$product_Detail[$i]['sheet_quantity'.$i]=$sheet_quantity;
			$product_Detail[$i]['sqm_quantity'.$i]=$sqm_quantity;
			$product_Detail[$i]['regular_price'.$i]=$regular_price;
			$product_Detail[$i]['product_discount'.$i]=$product_discount;
			$product_Detail[$i]['product_price_type'.$i]=$product_price_type;
			$product_Detail[$i]['pricelist_type'.$i]=$pricelist_type;

				$discountTotal = '0.00';
				//Based on the discount percent or amount we will show the discount details

				//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(for Each Product)
				$product_Detail[$i]['discount_percent'.$i] = 0;
				$product_Detail[$i]['discount_amount'.$i] = 0;

			if($discount_percent != 'NULL' && $discount_percent != '')
			{
				$product_Detail[$i]['discount_type'.$i] = "percentage";
				$product_Detail[$i]['discount_percent'.$i] = $discount_percent;
				$product_Detail[$i]['checked_discount_percent'.$i] = ' checked';
				$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:visible"';
				$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:hidden"';
				$discountTotal = $productTotal*$discount_percent/100;
			}
			elseif($discount_amount != 'NULL' && $discount_amount != '')
			{
				$product_Detail[$i]['discount_type'.$i] = "amount";
				$product_Detail[$i]['discount_amount'.$i] = $discount_amount;
				$product_Detail[$i]['checked_discount_amount'.$i] = ' checked';
				$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:visible"';
				$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:hidden"';
				$discountTotal = $discount_amount;
			}
			else
			{
				$product_Detail[$i]['checked_discount_zero'.$i] = ' checked';
			}
			$totalAfterDiscount = $productTotal-$discountTotal;
			$product_Detail[$i]['discountTotal'.$i] = number_format($discountTotal , 2, '.', '');
			$product_Detail[$i]['totalAfterDiscount'.$i] = number_format($totalAfterDiscount, 2, '.', '');

			$taxTotal = '0.00';
			$product_Detail[$i]['taxTotal'.$i] = number_format($taxTotal, 2, '.', '');

			//Calculate netprice
			$netPrice = $totalAfterDiscount+$taxTotal;
			//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
			if($module == 'PurchaseOrder' || $module == 'SalesOrder' || $module == 'Quotes' || $module == 'Order' || $module == 'Invoice')
			{
				$taxtype = getInventoryTaxType($module,$focus->id);
				if($taxtype == 'individual')
				{
					//Add the tax with product total and assign to netprice
					$netPrice = $netPrice+$taxTotal;
				}
			}
			$product_Detail[$i]['netPrice'.$i] = number_format($netPrice, 2, '.', '');

			//First we will get all associated taxes as array
			$tax_details = getTaxDetailsForProduct($hdnProductId,'all');
			//Now retrieve the tax values from the current query with the name
			for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
			{
				$tax_name = $tax_details[$tax_count]['taxname'];
				$tax_label = $tax_details[$tax_count]['taxlabel'];
				$tax_value = '0.00';

				//condition to avoid this function call when create new PO/SO/Quotes/Invoice from Product module
				if($focus->id != '')
				{
					if($taxtype == 'individual')//if individual then show the entered tax percentage
						$tax_value = getInventoryProductTaxValue($focus->id, $hdnProductId, $tax_name);
					else//if group tax then we have to show the default value when change to individual tax
						$tax_value = $tax_details[$tax_count]['percentage'];
				}
				else//if the above function not called then assign the default associated value of the product
					$tax_value = $tax_details[$tax_count]['percentage'];

				$product_Detail[$i]['taxes'][$tax_count]['taxname'] = $tax_name;
				$product_Detail[$i]['taxes'][$tax_count]['taxlabel'] = $tax_label;
				$product_Detail[$i]['taxes'][$tax_count]['percentage'] = $tax_value;
			}

		}

		//set the taxtype
		$product_Detail[1]['final_details']['taxtype'] = $taxtype;

		//Get the Final Discount, S&H charge, Tax for S&H and Adjustment values
		//To set the Final Discount details
		$finalDiscount = '0.00';
		$product_Detail[1]['final_details']['discount_type_final'] = 'zero';

		$subTotal = ($focus->column_fields['hdnSubTotal'] != '')?$focus->column_fields['hdnSubTotal']:'0.00';

		$product_Detail[1]['final_details']['hdnSubTotal'] = number_format($subTotal,2 , '.', '');
		$discountPercent = ($focus->column_fields['hdnDiscountPercent'] != '')?$focus->column_fields['hdnDiscountPercent']:'0.00';
		$discountAmount = ($focus->column_fields['hdnDiscountAmount'] != '')?$focus->column_fields['hdnDiscountAmount']:'0.00';

		//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(For Final Discount)
		$product_Detail[1]['final_details']['discount_percentage_final'] = '0.00';
		$product_Detail[1]['final_details']['discount_amount_final'] = '0.00';

		$hdn_bill_discount = getBillDiscount($focus->id);

		if($focus->column_fields['hdnDiscountPercent'] != '0')
		{
			$finalDiscount = ($subTotal*$discountPercent/100);
			$product_Detail[1]['final_details']['discount_type_final'] = 'percentage';
			$product_Detail[1]['final_details']['discount_percentage_final'] = $discountPercent;
			$product_Detail[1]['final_details']['checked_discount_percentage_final'] = ' checked';
			$product_Detail[1]['final_details']['style_discount_percentage_final'] = ' style="visibility:visible"';
			$product_Detail[1]['final_details']['style_discount_amount_final'] = ' style="visibility:hidden"';
		}
		elseif($hdn_bill_discount != '0')
		{
			$finalDiscount = $focus->column_fields['hdnDiscountAmount'];
			$product_Detail[1]['final_details']['discount_type_final'] = 'amount';
			$product_Detail[1]['final_details']['discount_amount_final'] =  number_format($hdn_bill_discount,2, '.', '');
			$product_Detail[1]['final_details']['checked_discount_amount_final'] = ' checked';
			$product_Detail[1]['final_details']['style_discount_amount_final'] = ' style="visibility:visible"';
			$product_Detail[1]['final_details']['style_discount_percentage_final'] = ' style="visibility:hidden"';
		}
		$product_Detail[1]['final_details']['discountTotal_final'] = number_format($finalDiscount,2, '.', '');
		$product_Detail[1]['final_details']['discount_coupon'] = getDiscountCoupon($focus->id);
		
		//echo $product_Detail[1]['final_details']['discount_amount_final'];
		//To set the Final Tax values
		//we will get all taxes. if individual then show the product related taxes only else show all taxes
		//suppose user want to change individual to group or vice versa in edit time the we have to show all taxes. so that here we will store all the taxes and based on need we will show the corresponding taxes

		$taxtotal = '0.00';
		//First we should get all available taxes and then retrieve the corresponding tax values
		$tax_details = getAllTaxes('available','','edit',$focus->id);

		for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
		{
			$tax_name = $tax_details[$tax_count]['taxname'];
			$tax_label = $tax_details[$tax_count]['taxlabel'];

			//if taxtype is individual and want to change to group during edit time then we have to show the all available taxes and their default values
			//Also taxtype is group and want to change to individual during edit time then we have to provide the asspciated taxes and their default tax values for individual products
			if($taxtype == 'group')
				$tax_percent = $adb->query_result($result,0,$tax_name);
			else
				$tax_percent = $tax_details[$tax_count]['percentage'];//$adb->query_result($result,0,$tax_name);

			if($tax_percent == '' || $tax_percent == 'NULL')
				$tax_percent = '0.00';
			$taxamount = ($subTotal-$finalDiscount)*$tax_percent/100;
			$taxtotal = $taxtotal + $taxamount;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['taxname'] = $tax_name;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['taxlabel'] = $tax_label;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['percentage'] = $tax_percent;
			$product_Detail[1]['final_details']['taxes'][$tax_count]['amount'] = $taxamount;
		}


		if($module == 'Quotes' || $module == 'Order' ){
			$product_Detail[1]['final_details']['tax_totalamount'] = $focus->column_fields['tax_final'];
			// $discount_coupon = ($focus->column_fields['discount_coupon'] != '')?$focus->column_fields['discount_coupon']:'0.00';
		}else{
			$product_Detail[1]['final_details']['tax_totalamount'] = $taxtotal;
		}
		//To set the Shipping & Handling charge
		$shCharge = ($focus->column_fields['hdnS_H_Amount'] != '')?$focus->column_fields['hdnS_H_Amount']:'0.00';
		$product_Detail[1]['final_details']['shipping_handling_charge'] = $shCharge;

		//To set the Shipping & Handling tax values
		//calculate S&H tax
		$shtaxtotal = '0.00';
		//First we should get all available taxes and then retrieve the corresponding tax values
		$shtax_details = getAllTaxes('available','sh','edit',$focus->id);

		//if taxtype is group then the tax should be same for all products in aicrm_inventoryproductrel table
		for($shtax_count=0;$shtax_count<count($shtax_details);$shtax_count++)
		{
			$shtax_name = $shtax_details[$shtax_count]['taxname'];
			$shtax_label = $shtax_details[$shtax_count]['taxlabel'];
			$shtax_percent = '0.00';
			//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
			if($module == 'PurchaseOrder' || $module == 'SalesOrder' || $module == 'Quotes' || $module == 'Order' || $module == 'Invoice')
			{
				$shtax_percent = getInventorySHTaxPercent($focus->id,$shtax_name);
			}
			$shtaxamount = $shCharge*$shtax_percent/100;
			$shtaxtotal = $shtaxtotal + $shtaxamount;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxname'] = $shtax_name;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxlabel'] = $shtax_label;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['percentage'] = $shtax_percent;
			$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['amount'] = $shtaxamount;
		}
		$product_Detail[1]['final_details']['shtax_totalamount'] = $shtaxtotal;

		//To set the Adjustment value
		$adjustment = ($focus->column_fields['txtAdjustment'] != '')?$focus->column_fields['txtAdjustment']:'0.00';
		$product_Detail[1]['final_details']['adjustment'] = $adjustment;

		//To set the grand total
		$grandTotal = ($focus->column_fields['hdnGrandTotal'] != '')?$focus->column_fields['hdnGrandTotal']:'0.00';
		$product_Detail[1]['final_details']['grandTotal'] = $grandTotal;

		$log->debug("Exiting getAssociatedProducts method ...");
	}

	//echo "<pre>";print_r($product_Detail);echo "</pre>";
	return $product_Detail;

}

function getAssociatedProducts_deal($module,$dealid)
{

	global $log;
	$log->debug("Entering getAssociatedProducts_deal(".$module.",".$dealid.") method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();

	$query="SELECT
		case when aicrm_crmentityrel.relmodule = 'Products' then aicrm_products.productid 
			 when aicrm_crmentityrel.relmodule = 'Service' then aicrm_service.serviceid 
			 when aicrm_crmentityrel.relmodule = 'Sparepart' then aicrm_sparepart.sparepartid 
		else '' end as productid,
		case when aicrm_crmentityrel.relmodule = 'Products' then aicrm_products.productname 
			 when aicrm_crmentityrel.relmodule = 'Service' then aicrm_service.service_name 
			 when aicrm_crmentityrel.relmodule = 'Sparepart' then aicrm_sparepart.sparepart_name 
		else '' end as productname,
		case when aicrm_crmentityrel.relmodule = 'Products' then aicrm_products.product_no
			 when aicrm_crmentityrel.relmodule = 'Service' then aicrm_service.service_no 
			 when aicrm_crmentityrel.relmodule = 'Sparepart' then aicrm_sparepart.sparepart_no 
		else '' end as productcode,

		case when aicrm_crmentityrel.relmodule = 'Products' then aicrm_products.sellingprice
			 when aicrm_crmentityrel.relmodule = 'Service' then aicrm_service.unit_price 
			 when aicrm_crmentityrel.relmodule = 'Sparepart' then aicrm_sparepart.sparepart_price 
		else '' end as listprice,

		case when aicrm_crmentityrel.relmodule = 'Products' then aicrm_products.unit
			 when aicrm_crmentityrel.relmodule = 'Service' then '' 
			 when aicrm_crmentityrel.relmodule = 'Sparepart' then ''
		else '' end as uom,
		aicrm_crmentityrel.relmodule as entitytype

		FROM aicrm_crmentityrel

		LEFT JOIN (
			Select aicrm_products.* from aicrm_products
			inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
			where aicrm_crmentity.deleted = 0
		) as aicrm_products ON aicrm_products.productid=aicrm_crmentityrel.relcrmid
		
		LEFT JOIN (
			Select aicrm_service.* from aicrm_service
			inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_service.serviceid
			where aicrm_crmentity.deleted = 0
		) as aicrm_service ON aicrm_service.serviceid=aicrm_crmentityrel.relcrmid
		LEFT JOIN (
			Select aicrm_sparepart.* from aicrm_sparepart
			inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_sparepart.sparepartid
			where aicrm_crmentity.deleted = 0
		) as aicrm_sparepart ON aicrm_sparepart.sparepartid=aicrm_crmentityrel.relcrmid
		
		
		WHERE aicrm_crmentityrel.crmid=?
		ORDER BY aicrm_crmentityrel.relcrmid";
		$params = array($dealid);

		//echo $query; 
		//print_r($dealid); exit;

	$result = $adb->pquery($query, $params);
	$num_rows=$adb->num_rows($result);

	for($i=1;$i<=$num_rows;$i++)
	{
		$hdnProductId = $adb->query_result($result,$i-1,'productid');
		$hdnProductcode = $adb->query_result($result,$i-1,'productcode');
		$productname=$adb->query_result($result,$i-1,'productname');

		//$productdescription=$adb->query_result($result,$i-1,'product_description');
		$comment=$adb->query_result($result,$i-1,'productcode');
		//$qtyinstock=$adb->query_result($result,$i-1,'qtyinstock');
		//$productcategory=$adb->query_result($result,$i-1,'productcategory');
        //$test_box=$adb->query_result($result,$i-1,'test_box');
		//$qty=$adb->query_result($result,$i-1,'quantity');
		//$problem=$adb->query_result($result,$i-1,'problem');
		//echo $problem;
		//$cause=$adb->query_result($result,$i-1,'cause');
		//$protect=$adb->query_result($result,$i-1,'protect');
		//$startdt=$adb->query_result($result,$i-1,'startdt');
		//$tik_status=$adb->query_result($result,$i-1,'tik_status');
		//$lot_no=$adb->query_result($result,$i-1,'lot_no');

		/*$no_head=$adb->query_result($result,$i-1,'no_head');
		$no_sub=$adb->query_result($result,$i-1,'no_sub');
		$type_pro=$adb->query_result($result,$i-1,'type_pro');
		$count_osp=$adb->query_result($result,$i-1,'count_osp');
		$purchase_cost=$adb->query_result($result,$i-1,'purchase_cost');
		$count_store=$adb->query_result($result,$i-1,'count_store');
		$cost_sales=$adb->query_result($result,$i-1,'cost_sales');
		$consult_cost=$adb->query_result($result,$i-1,'consult_cost');
		$consult_cost=$adb->query_result($result,$i-1,'consult_cost');
		$sale_com=$adb->query_result($result,$i-1,'sale_com');
		$plan_com=$adb->query_result($result,$i-1,'plan_com');
		$status1=$adb->query_result($result,$i-1,'status1');
		$status2=$adb->query_result($result,$i-1,'status2');
		$status3=$adb->query_result($result,$i-1,'status3');*/

		/*$type_application=$adb->query_result($result,$i-1,'type_application');
		$coordinator=$adb->query_result($result,$i-1,'coordinator');
		$coordinator_phone=$adb->query_result($result,$i-1,'coordinator_phone');
		$coordinator_fax=$adb->query_result($result,$i-1,'coordinator_fax');
		$coordinator_mobile=$adb->query_result($result,$i-1,'coordinator_mobile');
		$coordinator_email=$adb->query_result($result,$i-1,'coordinator_email');
		$request_cer=$adb->query_result($result,$i-1,'request_cer');
		$remark=$adb->query_result($result,$i-1,'remark');
		$payment=$adb->query_result($result,$i-1,'payment');
		$payment_status=$adb->query_result($result,$i-1,'payment_status');
        $pack_size=$adb->query_result($result,$i-1,'pack_size');*/
		//$test_box=$adb->query_result($result,$i-1,'test_box');
		$uom=$adb->query_result($result,$i-1,'uom');
		//$line=$adb->query_result($result,$i-1,'line');
		//$type=$adb->query_result($result,$i-1,'type');
		//$tik_status=$adb->query_result($result,$i-1,'tik_status');
		//$unitprice=$adb->query_result($result,$i-1,'unit_price');
		$listprice=$adb->query_result($result,$i-1,'listprice');

		//$listprice_inc=$adb->query_result($result,$i-1,'listprice_inc');
		//$listprice_exc=$adb->query_result($result,$i-1,'listprice_exc');

		$entitytype=$adb->query_result($result,$i-1,'entitytype');

		//$pro_type=$adb->query_result($result,$i-1,'pro_type');
		//$promotion_id=$adb->query_result($result,$i-1,'promotion_id');

		/*$listprice_project=$adb->query_result($result,$i-1,'listprice');*/
		//$qty_act=$adb->query_result($result,$i-1,'quantity_act');

		//$listprice_total=$adb->query_result($result,$i-1,'listprice_total');
		//$qty_ship=$adb->query_result($result,$i-1,'quantity_ship');
		//$status_dtl=$adb->query_result($result,$i-1,'status');

		//$qty_remain=$adb->query_result($result,$i-1,'quantity_remain');

		//$price_list_std=$adb->query_result($result,$i-1,'standard_price');
		//$price_list_inv=$adb->query_result($result,$i-1,'last_price');

		$product_name=$adb->query_result($result,$i-1,'product_name');
		//$products_business=$adb->query_result($result,$i-1,'products_businessplusno');

		if (!empty($entitytype)) {
			$product_Detail[$i]['entityType'.$i]=$entitytype;
		}

		if($listprice == '')
			$listprice = $unitprice;
		if($qty =='')
			$qty = 1;

		//calculate productTotal
		$productTotal = $qty*$listprice;

		//Delete link in First column
		if($i != 1)
		{
			$product_Detail[$i]['delRow'.$i]="Del";
		}
		/*if(!isset($focus->mode) && $seid!=''){
			$sub_prod_query = $adb->pquery("SELECT crmid as prod_id from aicrm_seproductsrel WHERE productid=? AND setype='Products'",array($seid));
		} else {
			$sub_prod_query = $adb->pquery("SELECT productid as prod_id from aicrm_inventorysubproductrel WHERE id=? AND sequence_no=?",array($focus->id,$i));
		}
		$subprodid_str='';
		$subprodname_str='';
		
		if($adb->num_rows($sub_prod_query)>0){
			for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
				$sprod_id = $adb->query_result($sub_prod_query,$j,'prod_id');
				$sprod_name = getProductName($sprod_id);
				$str_sep = "";
				if($j>0) $str_sep = ":";
				$subprodid_str .= $str_sep.$sprod_id;
				$subprodname_str .= $str_sep." - ".$sprod_name;
			}
		}

		$subprodname_str = str_replace(":","<br>",$subprodname_str);*/

		$product_Detail[$i]['hdnProductId'.$i] = $hdnProductId;
		$product_Detail[$i]['productName'.$i]= from_html($productname);
		/* Added to fix the issue Product Pop-up name display*/
		if($_REQUEST['action'] == 'CreateSOPDF' || $_REQUEST['action'] == 'CreatePDF' || $_REQUEST['action'] == 'SendPDFMail')
			$product_Detail[$i]['productName'.$i]= htmlspecialchars($product_Detail[$i]['productName'.$i]);
			$product_Detail[$i]['hdnProductcode'.$i] = $hdnProductcode;
			$product_Detail[$i]['productDescription'.$i]= from_html($productdescription);
			$product_Detail[$i]['comment'.$i]= $comment;

			if($module != 'PurchaseOrder' && $focus->object_name != 'Order')
			{
				$product_Detail[$i]['qtyInStock'.$i]=$qtyinstock;
			}
		if($module=="Quotes" || $module=="Order"){
			$product_Detail[$i]['productName'.$i]=$product_name;
		}
		$product_Detail[$i]['price_list_std'.$i]=number_format($price_list_std,2, '.', '');
		$product_Detail[$i]['price_list_inv'.$i]=number_format($price_list_inv,2, '.', '');
		$product_Detail[$i]['productcode'.$i] = $hdnProductcode;
        $product_Detail[$i]['test_box'.$i]= $test_box==0 ? '':number_format($test_box, 2 , '.', '');
		$product_Detail[$i]['qty'.$i]= $qty==0 ? '':number_format($qty, 2 , '.', '');
		$product_Detail[$i]['problem'.$i] = $problem;
		$product_Detail[$i]['cause'.$i] = $cause;
		$product_Detail[$i]['protect'.$i] = $protect;
		$product_Detail[$i]['startdt'.$i] = $startdt;
		$product_Detail[$i]['tik_status'.$i] = $tik_status;
		$product_Detail[$i]['lot_no'.$i] = $lot_no;
        $product_Detail[$i]['pack_size'.$i] = $pack_size;
		$product_Detail[$i]['uom'.$i] = $uom;
		$product_Detail[$i]['productcategory'.$i] = $productcategory;
		$product_Detail[$i]['no_head'.$i] = $no_head;
		$product_Detail[$i]['no_sub'.$i] = $no_sub;
		$product_Detail[$i]['type_pro'.$i] = $type_pro;
		$product_Detail[$i]['count_osp'.$i] = $count_osp;
		$product_Detail[$i]['purchase_cost'.$i] = $purchase_cost;
		$product_Detail[$i]['count_store'.$i] = $count_store;
		$product_Detail[$i]['cost_sales'.$i] = $cost_sales;
		$product_Detail[$i]['consult_cost'.$i] = $consult_cost;
		$product_Detail[$i]['sale_com'.$i] = $sale_com;
		$product_Detail[$i]['plan_com'.$i] = $plan_com;
		$product_Detail[$i]['status1'.$i] = $status1;
		$product_Detail[$i]['status2'.$i] = $status2;
		$product_Detail[$i]['status3'.$i] = $status3;

		$product_Detail[$i]['line'.$i] = $line;
		$product_Detail[$i]['type'.$i] = $type;
        $product_Detail[$i]['listPrice'.$i]= $test_box==0&&$listprice==0 ? '':number_format($listprice, 2, '.', '');
		$product_Detail[$i]['listPrice'.$i]= $qty==0&&$listprice==0 ? '':number_format($listprice, 2, '.', '');
		$product_Detail[$i]['listprice_inc'.$i]=number_format($listprice_inc,2, '.', '');
		$product_Detail[$i]['listprice_exc'.$i]=number_format($listprice_exc,2, '.', '');
		$product_Detail[$i]['unitPrice'.$i]=number_format($unitprice,2, '.', '');
		$product_Detail[$i]['productTotal'.$i]=number_format($productTotal,2, '.', '');
		$product_Detail[$i]['subproduct_ids'.$i]=$subprodid_str;
		$product_Detail[$i]['subprod_names'.$i]=$subprodname_str;

		$product_Detail[$i]['business_code'.$i]=$products_business;

		$discount_percent=$adb->query_result($result,$i-1,'discount_percent');
		$discount_amount=$adb->query_result($result,$i-1,'discount_amount');

		$product_Detail[$i]['listprice_project'.$i]=$listprice_project;
		$product_Detail[$i]['qty_act'.$i]=number_format($qty_act, 0, '.', '');
		$product_Detail[$i]['listprice_total'.$i]=$listprice_total;
		$product_Detail[$i]['qty_ship'.$i]=number_format($qty_ship, 0, '.', '');
		$product_Detail[$i]['status_dtl'.$i]=$status_dtl;
		$product_Detail[$i]['qty_remain'.$i]=number_format($qty_remain, 0, '.', '');

		$product_Detail[$i]['pro_type'.$i] = $pro_type;
		$product_Detail[$i]['promotion_id'.$i] = $promotion_id;
		$discountTotal = '0.00';
		//Based on the discount percent or amount we will show the discount details

		//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(for Each Product)
		$product_Detail[$i]['discount_percent'.$i] = 0;
		$product_Detail[$i]['discount_amount'.$i] = 0;

		if($discount_percent != 'NULL' && $discount_percent != '')
		{
			$product_Detail[$i]['discount_type'.$i] = "percentage";
			$product_Detail[$i]['discount_percent'.$i] = $discount_percent;
			$product_Detail[$i]['checked_discount_percent'.$i] = ' checked';
			$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:visible"';
			$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:hidden"';
			$discountTotal = $productTotal*$discount_percent/100;
		}
		elseif($discount_amount != 'NULL' && $discount_amount != '')
		{
			$product_Detail[$i]['discount_type'.$i] = "amount";
			$product_Detail[$i]['discount_amount'.$i] = $discount_amount;
			$product_Detail[$i]['checked_discount_amount'.$i] = ' checked';
			$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:visible"';
			$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:hidden"';
			$discountTotal = $discount_amount;
		}
		else
		{
			$product_Detail[$i]['checked_discount_zero'.$i] = ' checked';
		}
		$totalAfterDiscount = $productTotal-$discountTotal;
		$product_Detail[$i]['discountTotal'.$i] = number_format($discountTotal , 2, '.', '');
		$product_Detail[$i]['totalAfterDiscount'.$i] = number_format($totalAfterDiscount, 2, '.', '');

		$taxTotal = '0.00';
		$product_Detail[$i]['taxTotal'.$i] = number_format($taxTotal, 2, '.', '');

		//Calculate netprice
		$netPrice = $totalAfterDiscount+$taxTotal;
		//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
		if($module == 'PurchaseOrder' || $module == 'SalesOrder' || $module == 'Quotes' || $module == 'Order' || $module == 'Invoice')
		{
			$taxtype = getInventoryTaxType($module,$focus->id);
			if($taxtype == 'individual')
			{
				//Add the tax with product total and assign to netprice
				$netPrice = $netPrice+$taxTotal;
			}
		}
		$product_Detail[$i]['netPrice'.$i] = number_format($netPrice, 2, '.', '');

		//First we will get all associated taxes as array
		$tax_details = getTaxDetailsForProduct($hdnProductId,'all');
		//Now retrieve the tax values from the current query with the name
		for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
		{
			$tax_name = $tax_details[$tax_count]['taxname'];
			$tax_label = $tax_details[$tax_count]['taxlabel'];
			$tax_value = '0.00';

			//condition to avoid this function call when create new PO/SO/Quotes/Invoice from Product module
			if($focus->id != '')
			{
				if($taxtype == 'individual')//if individual then show the entered tax percentage
					$tax_value = getInventoryProductTaxValue($focus->id, $hdnProductId, $tax_name);
				else//if group tax then we have to show the default value when change to individual tax
					$tax_value = $tax_details[$tax_count]['percentage'];
			}
			else//if the above function not called then assign the default associated value of the product
				$tax_value = $tax_details[$tax_count]['percentage'];

			$product_Detail[$i]['taxes'][$tax_count]['taxname'] = $tax_name;
			$product_Detail[$i]['taxes'][$tax_count]['taxlabel'] = $tax_label;
			$product_Detail[$i]['taxes'][$tax_count]['percentage'] = $tax_value;
		}

	}

	//set the taxtype
	$product_Detail[1]['final_details']['taxtype'] = 'group';

	//Get the Final Discount, S&H charge, Tax for S&H and Adjustment values
	//To set the Final Discount details
	$finalDiscount = '0.00';
	$product_Detail[1]['final_details']['discount_type_final'] = 'zero';

	$subTotal = ($focus->column_fields['hdnSubTotal'] != '')?$focus->column_fields['hdnSubTotal']:'0.00';

	$product_Detail[1]['final_details']['hdnSubTotal'] = number_format($subTotal,2 , '.', '');
	$discountPercent = ($focus->column_fields['hdnDiscountPercent'] != '')?$focus->column_fields['hdnDiscountPercent']:'0.00';
	$discountAmount = ($focus->column_fields['hdnDiscountAmount'] != '')?$focus->column_fields['hdnDiscountAmount']:'0.00';

	//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(For Final Discount)
	$product_Detail[1]['final_details']['discount_percentage_final'] = '0.00';
	$product_Detail[1]['final_details']['discount_amount_final'] = '0.00';

	/*if($focus->column_fields['hdnDiscountPercent'] != '0')
	{
		$finalDiscount = ($subTotal*$discountPercent/100);
		$product_Detail[1]['final_details']['discount_type_final'] = 'percentage';
		$product_Detail[1]['final_details']['discount_percentage_final'] = $discountPercent;
		$product_Detail[1]['final_details']['checked_discount_percentage_final'] = ' checked';
		$product_Detail[1]['final_details']['style_discount_percentage_final'] = ' style="visibility:visible"';
		$product_Detail[1]['final_details']['style_discount_amount_final'] = ' style="visibility:hidden"';
	}
	elseif($focus->column_fields['hdnDiscountAmount'] != '0')
	{
		$finalDiscount = $focus->column_fields['hdnDiscountAmount'];
		$product_Detail[1]['final_details']['discount_type_final'] = 'amount';
		$product_Detail[1]['final_details']['discount_amount_final'] =  number_format($discountAmount,2, '.', '');
		$product_Detail[1]['final_details']['checked_discount_amount_final'] = ' checked';
		$product_Detail[1]['final_details']['style_discount_amount_final'] = ' style="visibility:visible"';
		$product_Detail[1]['final_details']['style_discount_percentage_final'] = ' style="visibility:hidden"';
	}*/
	/*$product_Detail[1]['final_details']['checked_discount_amount_final'] = ' checked';
	$product_Detail[1]['final_details']['style_discount_amount_final'] = ' style="visibility:visible"';
	$product_Detail[1]['final_details']['style_discount_percentage_final'] = ' style="visibility:hidden"';*/
	$product_Detail[1]['final_details']['discountTotal_final'] = number_format($finalDiscount,2, '.', '');
	
	$taxtotal = '0.00';
	//First we should get all available taxes and then retrieve the corresponding tax values
	$tax_details = getAllTaxes('available','','edit',$focus->id);

	for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
	{
		$tax_name = $tax_details[$tax_count]['taxname'];
		$tax_label = $tax_details[$tax_count]['taxlabel'];

		if($taxtype == 'group')
			$tax_percent = $adb->query_result($result,0,$tax_name);
		else
			$tax_percent = $tax_details[$tax_count]['percentage'];//$adb->query_result($result,0,$tax_name);

		if($tax_percent == '' || $tax_percent == 'NULL')
			$tax_percent = '0.00';
		$taxamount = ($subTotal-$finalDiscount)*$tax_percent/100;
		$taxtotal = $taxtotal + $taxamount;
		$product_Detail[1]['final_details']['taxes'][$tax_count]['taxname'] = $tax_name;
		$product_Detail[1]['final_details']['taxes'][$tax_count]['taxlabel'] = $tax_label;
		$product_Detail[1]['final_details']['taxes'][$tax_count]['percentage'] = $tax_percent;
		$product_Detail[1]['final_details']['taxes'][$tax_count]['amount'] = $taxamount;
	}


	if($module == 'Quotes' || $module == 'Order' ){
		$product_Detail[1]['final_details']['tax_totalamount'] = $focus->column_fields['tax_final'];
	}else{
		$product_Detail[1]['final_details']['tax_totalamount'] = $taxtotal;
	}
	//To set the Shipping & Handling charge
	$shCharge = ($focus->column_fields['hdnS_H_Amount'] != '')?$focus->column_fields['hdnS_H_Amount']:'0.00';
	$product_Detail[1]['final_details']['shipping_handling_charge'] = $shCharge;

	//To set the Shipping & Handling tax values
	//calculate S&H tax
	$shtaxtotal = '0.00';
	//First we should get all available taxes and then retrieve the corresponding tax values
	$shtax_details = getAllTaxes('available','sh','edit',$focus->id);

	//if taxtype is group then the tax should be same for all products in aicrm_inventoryproductrel table
	for($shtax_count=0;$shtax_count<count($shtax_details);$shtax_count++)
	{
		$shtax_name = $shtax_details[$shtax_count]['taxname'];
		$shtax_label = $shtax_details[$shtax_count]['taxlabel'];
		$shtax_percent = '0.00';
		//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
		if($module == 'PurchaseOrder' || $module == 'SalesOrder' || $module == 'Quotes' || $module == 'Order' || $module == 'Invoice')
		{
			$shtax_percent = getInventorySHTaxPercent($focus->id,$shtax_name);
		}
		$shtaxamount = $shCharge*$shtax_percent/100;
		$shtaxtotal = $shtaxtotal + $shtaxamount;
		$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxname'] = $shtax_name;
		$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxlabel'] = $shtax_label;
		$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['percentage'] = $shtax_percent;
		$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['amount'] = $shtaxamount;
	}
	$product_Detail[1]['final_details']['shtax_totalamount'] = $shtaxtotal;

	//To set the Adjustment value
	$adjustment = ($focus->column_fields['txtAdjustment'] != '')?$focus->column_fields['txtAdjustment']:'0.00';
	$product_Detail[1]['final_details']['adjustment'] = $adjustment;

	//To set the grand total
	$grandTotal = ($focus->column_fields['hdnGrandTotal'] != '')?$focus->column_fields['hdnGrandTotal']:'0.00';
	$product_Detail[1]['final_details']['grandTotal'] = $grandTotal;

	$log->debug("Exiting getAssociatedProducts method ...");

	//echo "<pre>";print_r($product_Detail);echo "</pre>";
	return $product_Detail;

}

function getAssociatedProducts_Projects($module,$dealid)
{

	global $log;
	$log->debug("Entering getAssociatedProducts_Projects(".$module.",".$dealid.") method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();

	$query="SELECT 
		aicrm_products.productid,
		aicrm_products.productname,
		aicrm_products.product_finish,
		aicrm_products.product_size_mm,
		aicrm_products.product_thinkness,
		aicrm_products.unit,
		aicrm_products.product_cost_avg,
		aicrm_inventoryprojects.listprice,
		aicrm_inventoryprojects.comment
		FROM aicrm_inventoryprojects
		inner join aicrm_products on aicrm_products.productid = aicrm_inventoryprojects.productid
		where aicrm_inventoryprojects.id = ?
		order by aicrm_inventoryprojects.sequence_no asc;";
	$params = array($dealid);

	$result = $adb->pquery($query, $params);
	$num_rows=$adb->num_rows($result);

	for($i=1;$i<=$num_rows;$i++)
	{
		$hdnProductId = $adb->query_result($result,$i-1,'productid');
		$hdnProductcode = $adb->query_result($result,$i-1,'productcode');
		$productname=$adb->query_result($result,$i-1,'productname');
		$comment=$adb->query_result($result,$i-1,'comment');
		$product_finish=$adb->query_result($result,$i-1,'product_finish');
		$product_size_mm=$adb->query_result($result,$i-1,'product_size_mm');
		$product_thinkness=$adb->query_result($result,$i-1,'product_thinkness');
		$uom=$adb->query_result($result,$i-1,'unit');
		$product_cost_avg=$adb->query_result($result,$i-1,'product_cost_avg');
		$listprice=$adb->query_result($result,$i-1,'listprice');

		if (!empty($entitytype)) {
			$product_Detail[$i]['entityType'.$i]=$entitytype;
		}

		if($listprice == '')
			$listprice = $unitprice;
		if($qty =='')
			$qty = 0;

		//calculate productTotal
		$productTotal = $qty*$listprice;

		//Delete link in First column
		if($i != 1)
		{
			$product_Detail[$i]['delRow'.$i]="Del";
		}
		
		$product_Detail[$i]['hdnProductId'.$i] = $hdnProductId;
		$product_Detail[$i]['productName'.$i]= from_html($productname);
		
		$product_Detail[$i]['uom'.$i] = $uom;
		$product_Detail[$i]['product_finish'.$i] = $product_finish;
		$product_Detail[$i]['product_size_mm'.$i] = $product_size_mm;
		$product_Detail[$i]['product_thinkness'.$i] = $product_thinkness;
		$product_Detail[$i]['product_cost_avg'.$i] = $product_cost_avg;

		$product_Detail[$i]['line'.$i] = $line;
		$product_Detail[$i]['type'.$i] = $type;

		$product_Detail[$i]['listPrice'.$i]= $qty==0&&$listprice==0 ? '':number_format($listprice, 2, '.', '');
		$product_Detail[$i]['listprice_inc'.$i]=number_format($listprice_inc,2, '.', '');
		$product_Detail[$i]['listprice_exc'.$i]=number_format($listprice_exc,2, '.', '');
		
		$discount_percent=$adb->query_result($result,$i-1,'discount_percent');
		$discount_amount=$adb->query_result($result,$i-1,'discount_amount');
		$discountTotal = '0.00';
		//Based on the discount percent or amount we will show the discount details

		//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(for Each Product)
		$product_Detail[$i]['discount_percent'.$i] = 0;
		$product_Detail[$i]['discount_amount'.$i] = 0;

		if($discount_percent != 'NULL' && $discount_percent != '')
		{
			$product_Detail[$i]['discount_type'.$i] = "percentage";
			$product_Detail[$i]['discount_percent'.$i] = $discount_percent;
			$product_Detail[$i]['checked_discount_percent'.$i] = ' checked';
			$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:visible"';
			$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:hidden"';
			$discountTotal = $productTotal*$discount_percent/100;
		}
		elseif($discount_amount != 'NULL' && $discount_amount != '')
		{
			$product_Detail[$i]['discount_type'.$i] = "amount";
			$product_Detail[$i]['discount_amount'.$i] = $discount_amount;
			$product_Detail[$i]['checked_discount_amount'.$i] = ' checked';
			$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:visible"';
			$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:hidden"';
			$discountTotal = $discount_amount;
		}
		else
		{
			$product_Detail[$i]['checked_discount_zero'.$i] = ' checked';
		}
		$totalAfterDiscount = $productTotal-$discountTotal;
		$product_Detail[$i]['discountTotal'.$i] = number_format($discountTotal , 2, '.', '');
		$product_Detail[$i]['totalAfterDiscount'.$i] = number_format($totalAfterDiscount, 2, '.', '');

		$taxTotal = '0.00';
		$product_Detail[$i]['taxTotal'.$i] = number_format($taxTotal, 2, '.', '');

		//Calculate netprice
		$netPrice = $totalAfterDiscount+$taxTotal;
		//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
		if($module == 'PurchaseOrder' || $module == 'SalesOrder' || $module == 'Quotes' || $module == 'Order' || $module == 'Invoice')
		{
			$taxtype = getInventoryTaxType($module,$focus->id);
			if($taxtype == 'individual')
			{
				//Add the tax with product total and assign to netprice
				$netPrice = $netPrice+$taxTotal;
			}
		}
		$product_Detail[$i]['netPrice'.$i] = number_format($netPrice, 2, '.', '');

		//First we will get all associated taxes as array
		$tax_details = getTaxDetailsForProduct($hdnProductId,'all');
		//Now retrieve the tax values from the current query with the name
		for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
		{
			$tax_name = $tax_details[$tax_count]['taxname'];
			$tax_label = $tax_details[$tax_count]['taxlabel'];
			$tax_value = '0.00';

			//condition to avoid this function call when create new PO/SO/Quotes/Invoice from Product module
			if($focus->id != '')
			{
				if($taxtype == 'individual')//if individual then show the entered tax percentage
					$tax_value = getInventoryProductTaxValue($focus->id, $hdnProductId, $tax_name);
				else//if group tax then we have to show the default value when change to individual tax
					$tax_value = $tax_details[$tax_count]['percentage'];
			}
			else//if the above function not called then assign the default associated value of the product
				$tax_value = $tax_details[$tax_count]['percentage'];

			$product_Detail[$i]['taxes'][$tax_count]['taxname'] = $tax_name;
			$product_Detail[$i]['taxes'][$tax_count]['taxlabel'] = $tax_label;
			$product_Detail[$i]['taxes'][$tax_count]['percentage'] = $tax_value;
		}

	}

	//set the taxtype
	$product_Detail[1]['final_details']['taxtype'] = 'group';

	//Get the Final Discount, S&H charge, Tax for S&H and Adjustment values
	//To set the Final Discount details
	$finalDiscount = '0.00';
	$product_Detail[1]['final_details']['discount_type_final'] = 'zero';

	$subTotal = ($focus->column_fields['hdnSubTotal'] != '')?$focus->column_fields['hdnSubTotal']:'0.00';

	$product_Detail[1]['final_details']['hdnSubTotal'] = number_format($subTotal,2 , '.', '');
	$discountPercent = ($focus->column_fields['hdnDiscountPercent'] != '')?$focus->column_fields['hdnDiscountPercent']:'0.00';
	$discountAmount = ($focus->column_fields['hdnDiscountAmount'] != '')?$focus->column_fields['hdnDiscountAmount']:'0.00';

	//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(For Final Discount)
	$product_Detail[1]['final_details']['discount_percentage_final'] = '0.00';
	$product_Detail[1]['final_details']['discount_amount_final'] = '0.00';

	$product_Detail[1]['final_details']['discountTotal_final'] = number_format($finalDiscount,2, '.', '');
	
	$taxtotal = '0.00';
	//First we should get all available taxes and then retrieve the corresponding tax values
	$tax_details = getAllTaxes('available','','edit',$focus->id);

	for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
	{
		$tax_name = $tax_details[$tax_count]['taxname'];
		$tax_label = $tax_details[$tax_count]['taxlabel'];

		if($taxtype == 'group')
			$tax_percent = $adb->query_result($result,0,$tax_name);
		else
			$tax_percent = $tax_details[$tax_count]['percentage'];//$adb->query_result($result,0,$tax_name);

		if($tax_percent == '' || $tax_percent == 'NULL')
			$tax_percent = '0.00';
		$taxamount = ($subTotal-$finalDiscount)*$tax_percent/100;
		$taxtotal = $taxtotal + $taxamount;
		$product_Detail[1]['final_details']['taxes'][$tax_count]['taxname'] = $tax_name;
		$product_Detail[1]['final_details']['taxes'][$tax_count]['taxlabel'] = $tax_label;
		$product_Detail[1]['final_details']['taxes'][$tax_count]['percentage'] = $tax_percent;
		$product_Detail[1]['final_details']['taxes'][$tax_count]['amount'] = $taxamount;
	}


	if($module == 'Quotes' || $module == 'Order' ){
		$product_Detail[1]['final_details']['tax_totalamount'] = $focus->column_fields['tax_final'];
	}else{
		$product_Detail[1]['final_details']['tax_totalamount'] = $taxtotal;
	}
	//To set the Shipping & Handling charge
	$shCharge = ($focus->column_fields['hdnS_H_Amount'] != '')?$focus->column_fields['hdnS_H_Amount']:'0.00';
	$product_Detail[1]['final_details']['shipping_handling_charge'] = $shCharge;

	//To set the Shipping & Handling tax values
	//calculate S&H tax
	$shtaxtotal = '0.00';
	//First we should get all available taxes and then retrieve the corresponding tax values
	$shtax_details = getAllTaxes('available','sh','edit',$focus->id);

	//if taxtype is group then the tax should be same for all products in aicrm_inventoryproductrel table
	for($shtax_count=0;$shtax_count<count($shtax_details);$shtax_count++)
	{
		$shtax_name = $shtax_details[$shtax_count]['taxname'];
		$shtax_label = $shtax_details[$shtax_count]['taxlabel'];
		$shtax_percent = '0.00';
		//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
		if($module == 'PurchaseOrder' || $module == 'SalesOrder' || $module == 'Quotes' || $module == 'Order' || $module == 'Invoice')
		{
			$shtax_percent = getInventorySHTaxPercent($focus->id,$shtax_name);
		}
		$shtaxamount = $shCharge*$shtax_percent/100;
		$shtaxtotal = $shtaxtotal + $shtaxamount;
		$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxname'] = $shtax_name;
		$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxlabel'] = $shtax_label;
		$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['percentage'] = $shtax_percent;
		$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['amount'] = $shtaxamount;
	}
	$product_Detail[1]['final_details']['shtax_totalamount'] = $shtaxtotal;

	//To set the Adjustment value
	$adjustment = ($focus->column_fields['txtAdjustment'] != '')?$focus->column_fields['txtAdjustment']:'0.00';
	$product_Detail[1]['final_details']['adjustment'] = $adjustment;

	//To set the grand total
	$grandTotal = ($focus->column_fields['hdnGrandTotal'] != '')?$focus->column_fields['hdnGrandTotal']:'0.00';
	$product_Detail[1]['final_details']['grandTotal'] = $grandTotal;

	$log->debug("Exiting getAssociatedProducts_Projects method ...");

	//echo "<pre>";print_r($product_Detail);echo "</pre>";
	return $product_Detail;

}

function getAssociatedSamplerequisition($module,$focus,$seid='')
{

	global $log;
	$log->debug("Entering getAssociatedSamplerequisition(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();
	
	$query="SELECT
			aicrm_products.productname,
			aicrm_inventorysamplerequisition.*
			FROM aicrm_inventorysamplerequisition
			LEFT join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inventorysamplerequisition.productid
			LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_inventorysamplerequisition.productid
			WHERE id=?
			ORDER BY sequence_no";
	$params = array($focus->id);
	
	$result = $adb->pquery($query, $params);
	$num_rows=$adb->num_rows($result);

	$total_amount_of_sample = 0;
	$total_amount_of_purchase = 0;

	for($i=1;$i<=$num_rows;$i++)
	{
		$hdnProductId = $adb->query_result($result,$i-1,'productid');
		$hdnProductcode = $adb->query_result($result,$i-1,'productcode');
		$productname=$adb->query_result($result,$i-1,'productname');
		$comment=$adb->query_result($result,$i-1,'comment');

		$subproduct_ids=$adb->query_result($result,$i-1,'subproduct_ids');
		$sr_finish=$adb->query_result($result,$i-1,'sr_finish');
		$sr_size_mm=$adb->query_result($result,$i-1,'sr_size_mm');
		$sr_thickness_mm=$adb->query_result($result,$i-1,'sr_thickness_mm');
		$sr_product_unit=$adb->query_result($result,$i-1,'sr_product_unit');
		$amount_of_sample=$adb->query_result($result,$i-1,'amount_of_sample');
		$amount_of_purchase=$adb->query_result($result,$i-1,'amount_of_purchase');

		$product_Detail[$i]['hdnProductId'.$i] = $hdnProductId;
		$product_Detail[$i]['productName'.$i]= from_html($productname);
		$product_Detail[$i]['subproduct_ids'.$i]= $subproduct_ids;
		$product_Detail[$i]['comment'.$i]= $comment;
		$product_Detail[$i]['sr_finish'.$i]= $sr_finish;
		$product_Detail[$i]['sr_size_mm'.$i]= $sr_size_mm;
		$product_Detail[$i]['sr_thickness_mm'.$i]= $sr_thickness_mm;
		$product_Detail[$i]['sr_product_unit'.$i]= $sr_product_unit;

		$product_Detail[$i]['amount_of_sample'.$i]= $amount_of_sample;
		$product_Detail[$i]['amount_of_purchase'.$i]= $amount_of_purchase;

		$total_amount_of_sample=$total_amount_of_sample+$adb->query_result($result,$i-1,'amount_of_sample');
		$total_amount_of_purchase=$total_amount_of_purchase+$adb->query_result($result,$i-1,'amount_of_purchase');
	}
	
	$product_Detail[1]['final_details']['total_amount_of_sample'] = $total_amount_of_sample;
	$product_Detail[1]['final_details']['total_amount_of_purchase'] = $total_amount_of_purchase;
 
	$log->debug("Exiting getAssociatedProducts method ...");
	
	return $product_Detail;

}
/** This function returns the no of aicrm_products associated to the given entity or a record.
* Param $module - module name
* Param $focus - module object
* Param $seid - sales entity id
* Return type is an object array
*/

function getNoOfAssocProducts($module,$focus,$seid='')
{
	global $log;
	$log->debug("Entering getNoOfAssocProducts(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	if($module == 'Quotes')
	{
		$query="select aicrm_products.productname, aicrm_products.unit_price, aicrm_inventoryproductrel.* from aicrm_inventoryproductrel inner join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrel.productid where id=?";
		$params = array($focus->id);
	}
	elseif($module == 'PurchaseOrder')
	{
		$query="select aicrm_products.productname, aicrm_products.unit_price, aicrm_inventoryproductrel.* from aicrm_inventoryproductrel inner join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrel.productid where id=?";
		$params = array($focus->id);
	}
	elseif($module == 'SalesOrder')
	{
		$query="select aicrm_products.productname, aicrm_products.unit_price, aicrm_inventoryproductrel.* from aicrm_inventoryproductrel inner join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrel.productid where id=?";
		$params = array($focus->id);
	}
	elseif($module == 'Invoice')
	{
		$query="select aicrm_products.productname, aicrm_products.unit_price, aicrm_inventoryproductrel.* from aicrm_inventoryproductrel inner join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrel.productid where id=?";
		$params = array($focus->id);
	}
	elseif($module == 'Potentials')
	{
		$query="select aicrm_products.productname,aicrm_products.unit_price,aicrm_seproductsrel.* from aicrm_products inner join aicrm_seproductsrel on aicrm_seproductsrel.productid=aicrm_products.productid where crmid=?";
		$params = array($seid);
	}
	elseif($module == 'Products')
	{
		$query="select aicrm_products.productname,aicrm_products.unit_price, aicrm_crmentity.* from aicrm_products inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_products.productid where aicrm_crmentity.deleted=0 and productid=?";
		$params = array($seid);
	}

	$result = $adb->pquery($query, $params);
	$num_rows=$adb->num_rows($result);
	$log->debug("Exiting getNoOfAssocProducts method ...");
	return $num_rows;
}

/** This function returns the detail block information of a record for given block id.
* Param $module - module name
* Param $block - block name
* Param $mode - view type (detail/edit/create)
* Param $col_fields - aicrm_fields array
* Param $tabid - aicrm_tab id
* Param $info_type - information type (basic/advance) default ""
* Return type is an object array
*/

function getBlockInformation($module, $result, $col_fields,$tabid,$block_label,$mode)
{
	global $log;
	$log->debug("Entering getBlockInformation(".$module.",". $result.",". $col_fields.",".$tabid.",".$block_label.") method ...");
	global $adb;
	$editview_arr = Array();

	global $current_user,$mod_strings;

	$noofrows = $adb->num_rows($result);
	if (($module == 'Accounts' || $module == 'Contacts' || $module == 'Quotes') && $block == 2)
	{
		 global $log;
                $log->info("module is ".$module);

			$mvAdd_flag = true;
			$moveAddress = "<td rowspan='6' valign='middle' align='center'><input title='Copy billing address to shipping address'  class='button' onclick='return copyAddressRight(EditView)'  type='button' name='copyright' value='&raquo;' style='padding:0px 2px 0px 2px;font-size:12px'><br><br>
				<input title='Copy shipping address to billing address'  class='button' onclick='return copyAddressLeft(EditView)'  type='button' name='copyleft' value='&laquo;' style='padding:0px 2px 0px 2px;font-size:12px'></td>";
	}


	for($i=0; $i<$noofrows; $i++)
	{
		$fieldtablename = $adb->query_result($result,$i,"tablename");
		$fieldcolname = $adb->query_result($result,$i,"columnname");
		$uitype = $adb->query_result($result,$i,"uitype");
		$fieldname = $adb->query_result($result,$i,"fieldname");
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$block = $adb->query_result($result,$i,"block");
		$maxlength = $adb->query_result($result,$i,"maximumlength");
		$generatedtype = $adb->query_result($result,$i,"generatedtype");
		$typeofdata = $adb->query_result($result,$i,"typeofdata");

		$custfld = getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields,$generatedtype,$module,$mode,$typeofdata);

		$editview_arr[$block][]=$custfld;
		if ($mvAdd_flag == true)
		$mvAdd_flag = false;
		$i++;
		if($i<$noofrows)
		{
			$fieldtablename = $adb->query_result($result,$i,"tablename");
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$uitype = $adb->query_result($result,$i,"uitype");
			$fieldname = $adb->query_result($result,$i,"fieldname");
			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			$block = $adb->query_result($result,$i,"block");
			$maxlength = $adb->query_result($result,$i,"maximumlength");
			$generatedtype = $adb->query_result($result,$i,"generatedtype");
			$typeofdata = $adb->query_result($result,$i,"typeofdata");
			$custfld = getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields,$generatedtype,$module,$mode,$typeofdata);
			$editview_arr[$block][]=$custfld;
		}
	}
	foreach($editview_arr as $headerid=>$editview_value)
	{
		$editview_data = Array();
		for ($i=0,$j=0;$i<count($editview_value);$j++)
		{
			$key1=$editview_value[$i];
			if(is_array($editview_value[$i+1]) && ($key1[0][0]!=19 && $key1[0][0]!=20))
			{
				$key2=$editview_value[$i+1];
			}
			else
			{
				$key2 =array();
			}
			if($key1[0][0]!=19 && $key1[0][0]!=20){
				$editview_data[$j]=array(0 => $key1,1 => $key2);
				$i+=2;
			}
			else{
				$editview_data[$j]=array(0 => $key1);
				$i++;
			}
		}
		$editview_arr[$headerid] = $editview_data;
	}
	foreach($block_label as $blockid=>$label)
	{
		if($label == '')
		{
			$returndata[getTranslatedString($curBlock,$module)]=array_merge((array)$returndata[getTranslatedString($curBlock,$module)],(array)$editview_arr[$blockid]);
		}
		else
		{
			$curBlock = $label;
			if(is_array($editview_arr[$blockid]))
				$returndata[getTranslatedString($curBlock,$module)]=array_merge((array)$returndata[getTranslatedString($curBlock,$module)],(array)$editview_arr[$blockid]);
		}
	}
	$log->debug("Exiting getBlockInformation method ...");
	return $returndata;

}

/** This function returns the data type of the aicrm_fields, with aicrm_field label, which is used for javascript validation.
* Param $validationData - array of aicrm_fieldnames with datatype
* Return type array
*/

function split_validationdataArray($validationData)
{
	global $log;
	$log->debug("Entering split_validationdataArray(".$validationData.") method ...");
	$fieldName = '';
	$fieldLabel = '';
	$fldDataType = '';
	$rows = count($validationData);
	foreach($validationData as $fldName => $fldLabel_array)
	{
		if($fieldName == '')
		{
			$fieldName="'".$fldName."'";
		}
		else
		{
			$fieldName .= ",'".$fldName ."'";
		}

		foreach($fldLabel_array as $fldLabel => $datatype)
		{
			if($fieldLabel == '')
			{
				$fieldLabel = "'".addslashes($fldLabel)."'";
			}
			else
			{
				$fieldLabel .= ",'".addslashes($fldLabel)."'";
			}
			
			if($fldDataType == '')
			{
				$fldDataType = "'".$datatype ."'";
			}
			else
			{
				$fldDataType .= ",'".$datatype ."'";
			}
		}
	}
	$data['fieldname'] = $fieldName;
	$data['fieldlabel'] = $fieldLabel;
	$data['datatype'] = $fldDataType;
	$log->debug("Exiting split_validationdataArray method ...");
	return $data;
}

function split_validationdataUitype($validationDataUitype)
{
	global $log;
	$log->debug("Entering split_validationdataUitype(".$validationDataUitype.") method ...");
	$fieldName = '';
	$fieldLabel = '';
	$fldDataType = '';
	$fieldUitype = '';
	$rows = count($validationDataUitype);
	foreach($validationDataUitype as $fldName => $fldLabel_array)
	{
		foreach($fldLabel_array as $fldLabel => $datauitype)
		{
			if($fieldUitype == '')
			{
				$fieldUitype = "'".$datauitype ."'";
			}
			else
			{
				$fieldUitype .= ",'".$datauitype ."'";
			}
		}
	}
	$data['datauitype'] = $fieldUitype;
	$log->debug("Exiting validationDataUitype method ...");
	return $data;
}
function getAssociatedProductsOrder($module,$focus,$seid='')
{
	global $log;
	$log->debug("Entering getAssociatedProductsOrder(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();
	
	$query="SELECT aicrm_inventoryproductrelorder.*
				FROM aicrm_inventoryproductrelorder
				LEFT JOIN aicrm_products ON aicrm_products.productid=aicrm_inventoryproductrelorder.productid
				WHERE id=?
				ORDER BY sequence_no";
	$params = array($focus->id);
	$result = $adb->pquery($query, $params);
	$num_rows=$adb->num_rows($result);

	for($i=1;$i<=$num_rows;$i++)
	{
		$hdnProductId = $adb->query_result($result,$i-1,'productid');
		$productname=$adb->query_result($result,$i-1,'productname');
		$producttype=$adb->query_result($result,$i-1,'producttype');
		$km=$adb->query_result($result,$i-1,'km');
		$zone=$adb->query_result($result,$i-1,'zone');
        $carsize=$adb->query_result($result,$i-1,'carsize');
		$unit=$adb->query_result($result,$i-1,'unit');
		$number=$adb->query_result($result,$i-1,'number');
		$priceperunit=$adb->query_result($result,$i-1,'priceperunit');
		$amount=$adb->query_result($result,$i-1,'amount');
		$min=$adb->query_result($result,$i-1,'min');
		$dlv_c=$adb->query_result($result,$i-1,'dlv_c');
		$dlv_cvat=$adb->query_result($result,$i-1,'dlv_cvat');
		$dlv_pvat=$adb->query_result($result,$i-1,'dlv_pvat');
		$lp=$adb->query_result($result,$i-1,'lp');
		$discount=$adb->query_result($result,$i-1,'discount');
		$c_cost=$adb->query_result($result,$i-1,'c_cost');
		$afterdiscount=$adb->query_result($result,$i-1,'afterdiscount');
		$purchaseamount=$adb->query_result($result,$i-1,'purchaseamount');
		
		$subtotal1=$adb->query_result($result,$i-1,'subtotal1');
		$vat1=$adb->query_result($result,$i-1,'vat1');
		$total1=$adb->query_result($result,$i-1,'total1');
		$subtotal2=$adb->query_result($result,$i-1,'subtotal2');
		$vat2=$adb->query_result($result,$i-1,'vat2');
		$total2=$adb->query_result($result,$i-1,'total2');

		//
		$product_Detail[$i]['hdnProductId'.$i] = $hdnProductId;
		$product_Detail[$i]['productName'.$i]= from_html($productname);
		$product_Detail[$i]['producttype'.$i] = $producttype;
		$product_Detail[$i]['km'.$i] = $km;
		$product_Detail[$i]['zone'.$i] = $zone;
		$product_Detail[$i]['carsize'.$i] = $carsize;
		$product_Detail[$i]['unit'.$i] = $unit;
		$product_Detail[$i]['number'.$i] = $number;
        $product_Detail[$i]['priceperunit'.$i] = $priceperunit;
		$product_Detail[$i]['amount'.$i] = $amount;
		$product_Detail[$i]['min'.$i] = $min;
		$product_Detail[$i]['dlv_c'.$i] = $dlv_c;
		$product_Detail[$i]['dlv_cvat'.$i] = $dlv_cvat;
		$product_Detail[$i]['dlv_pvat'.$i] = $dlv_pvat;
		$product_Detail[$i]['lp'.$i] = $lp;
		$product_Detail[$i]['discount'.$i] = $discount;
		$product_Detail[$i]['c_cost'.$i] = $c_cost;
		$product_Detail[$i]['afterdiscount'.$i] = $afterdiscount;
		$product_Detail[$i]['purchaseamount'.$i] = $purchaseamount;
		$product_Detail[$i]['subtotal1'] = $subtotal1;
		$product_Detail[$i]['vat1'] = $vat1;
		$product_Detail[$i]['total1'] = $total1;

		//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
		$taxtype = getInventoryTaxType($module,$focus->id);
		//First we will get all associated taxes as array
		$tax_details = getTaxDetailsForProduct($hdnProductId,'all');
		//Now retrieve the tax values from the current query with the name
	}

	//set the taxtype
	$product_Detail[1]['final_details']['taxtype'] = $taxtype;

	$product_Detail[1]['final_details']['subtotal1'] = $subtotal1;
	$product_Detail[1]['final_details']['vat1'] = $vat1;
	$product_Detail[1]['final_details']['total1'] = $total1;

	$product_Detail[1]['final_details']['subtotal2'] = $subtotal2;
	$product_Detail[1]['final_details']['vat2'] = $vat2;
	$product_Detail[1]['final_details']['total2'] = $total2;
	//$discountPercent = ($focus->column_fields['hdnDiscountPercent'] != '')?$focus->column_fields['hdnDiscountPercent']:'0.00';
	//$product_Detail[1]['final_details']['discount_type_final'] = 'zero';
	$log->debug("Exiting getAssociatedProductsOrder method ...");
	
	//echo "<pre>";print_r($product_Detail);echo "</pre>";exit;
	return $product_Detail;

}

function getAssociatedProductsPurchasesorder($module,$focus,$seid='')
{
	global $log;
	$log->debug("Entering getAssociatedProductsPurchasesorder(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();

	$query="SELECT
		aicrm_products.productname,
		aicrm_products.product_no as productcode,
		case when aicrm_products.productid != '' then 'Products' else 'Services' end as entitytype,
		aicrm_products.*,
		aicrm_inventorypurchasesorderrel.*,
		ifnull(aicrm_projects.projectsid,'') as projectsid,
		ifnull(aicrm_projects.projects_name,'') as projects_name,
		ifnull(aicrm_crmentity.smownerid,'') as smownerid,
		ifnull(concat(aicrm_users.first_name,' ',aicrm_users.last_name),'') as fullname
		FROM aicrm_inventorypurchasesorderrel
		LEFT JOIN aicrm_products ON aicrm_products.productid=aicrm_inventorypurchasesorderrel.productid
		LEFT JOIN aicrm_projects ON aicrm_projects.projectsid=aicrm_inventorypurchasesorderrel.projectsid 
		LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid=aicrm_projects.projectsid
		LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
		WHERE aicrm_inventorypurchasesorderrel.id=?
		ORDER BY sequence_no";
	$params = array($focus->id);

	$result = $adb->pquery($query, $params);
	$num_rows=$adb->num_rows($result);

	for($i=1;$i<=$num_rows;$i++)
	{
		$sequence_no = $adb->query_result($result,$i-1,'sequence_no');
		$hdnProductId = $adb->query_result($result,$i-1,'productid');
		$hdnProductcode = $adb->query_result($result,$i-1,'productcode');
		$productname=$adb->query_result($result,$i-1,'productname');
		$comment=$adb->query_result($result,$i-1,'comment');
		$entitytype=$adb->query_result($result,$i-1,'entitytype');

		$projectsid=$adb->query_result($result,$i-1,'projectsid');
		$projects_name=$adb->query_result($result,$i-1,'projects_name');
		$smownerid=$adb->query_result($result,$i-1,'smownerid');
		$fullname=$adb->query_result($result,$i-1,'fullname');

		$product_brand=$adb->query_result($result,$i-1,'product_brand');
		$product_group=$adb->query_result($result,$i-1,'product_group');
		$product_code_crm=$adb->query_result($result,$i-1,'product_code_crm');
		$product_prefix=$adb->query_result($result,$i-1,'product_prefix');
		$product_factory_code=$adb->query_result($result,$i-1,'product_factory_code');
		$product_design_name=$adb->query_result($result,$i-1,'product_design_name');
		$product_finish_name=$adb->query_result($result,$i-1,'product_finish_name');
		$product_size_ft=$adb->query_result($result,$i-1,'product_size_ft');
		$product_thinkness=$adb->query_result($result,$i-1,'product_thinkness');
		$product_grade=$adb->query_result($result,$i-1,'product_grade');
		$product_film=$adb->query_result($result,$i-1,'product_film');
		$po_quantity=$adb->query_result($result,$i-1,'po_quantity');
		$gr_quantity=$adb->query_result($result,$i-1,'gr_quantity');
		$defects_quantity=$adb->query_result($result,$i-1,'defects_quantity');
		$remain_quantity=$adb->query_result($result,$i-1,'remain_quantity');
		$gr_qty_percent=$adb->query_result($result,$i-1,'gr_qty_percent');
		$price_usd=$adb->query_result($result,$i-1,'price_usd');
		$unit_price=$adb->query_result($result,$i-1,'unit_price');

		$gr_percentage=$adb->query_result($result,$i-1,'gr_percentage');
		$item_status=$adb->query_result($result,$i-1,'item_status');
		$po_price_type=$adb->query_result($result,$i-1,'po_price_type');
		$product_backprint=$adb->query_result($result,$i-1,'product_backprint');
		if (!empty($entitytype)) {
			$product_Detail[$i]['entityType'.$i]=$entitytype;
		}
		//calculate productTotal
		$productTotal = $po_quantity*$unit_price;

		$product_Detail[$i]['productTotal'.$i]=number_format($productTotal,2, '.', '');
		//Delete link in First column
		if($i != 1)
		{
			$product_Detail[$i]['delRow'.$i]="Del";
		}
		/*if(!isset($focus->mode) && $seid!=''){
			$sub_prod_query = $adb->pquery("SELECT crmid as prod_id from aicrm_seproductsrel WHERE productid=? AND setype='Products'",array($seid));
		} else {
			$sub_prod_query = $adb->pquery("SELECT productid as prod_id from aicrm_inventorysubproductrel WHERE id=? AND sequence_no=?",array($focus->id,$i));
		}
		$subprodid_str='';
		$subprodname_str='';

		if($adb->num_rows($sub_prod_query)>0){
			for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
				$sprod_id = $adb->query_result($sub_prod_query,$j,'prod_id');
				$sprod_name = getProductName($sprod_id);
				$str_sep = "";
				if($j>0) $str_sep = ":";
				$subprodid_str .= $str_sep.$sprod_id;
				$subprodname_str .= $str_sep." - ".$sprod_name;
			}
		}

		$subprodname_str = str_replace(":","<br>",$subprodname_str);*/
		$product_Detail[$i]['po_detail_no'.$i] = $sequence_no;
		$product_Detail[$i]['gr_percentage'.$i] = $gr_percentage;
		$product_Detail[$i]['item_status'.$i] = $item_status;
		$product_Detail[$i]['po_price_type'.$i] = $po_price_type;
		$product_Detail[$i]['product_backprint'.$i] = $product_backprint;

		$product_Detail[$i]['hdnProductId'.$i] = $hdnProductId;
		$product_Detail[$i]['productName'.$i]= from_html($productname);
		/* Added to fix the issue Product Pop-up name display*/
		if($_REQUEST['action'] == 'CreateSOPDF' || $_REQUEST['action'] == 'CreatePDF' || $_REQUEST['action'] == 'SendPDFMail')
			$product_Detail[$i]['productName'.$i]= htmlspecialchars($product_Detail[$i]['productName'.$i]);
			$product_Detail[$i]['hdnProductcode'.$i] = $hdnProductcode;
			$product_Detail[$i]['productDescription'.$i]= from_html($productdescription);
			$product_Detail[$i]['comment'.$i]= $comment;

		$product_Detail[$i]['projectsid'.$i]=$projectsid;
		$product_Detail[$i]['projects_name'.$i]=$projects_name;
		$product_Detail[$i]['smownerid'.$i]=$smownerid;
		$product_Detail[$i]['assignto'.$i]=$fullname;
		$product_Detail[$i]['product_brand'.$i]=$product_brand;
		$product_Detail[$i]['product_group'.$i]=$product_group;
		$product_Detail[$i]['product_code_crm'.$i]=$product_code_crm;
		$product_Detail[$i]['product_prefix'.$i]=$product_prefix;
		$product_Detail[$i]['product_factory_code'.$i]=$product_factory_code;
		$product_Detail[$i]['product_design_name'.$i]=$product_design_name;
		$product_Detail[$i]['product_finish_name'.$i]=$product_finish_name;
		$product_Detail[$i]['product_size_ft'.$i]=$product_size_ft;
		$product_Detail[$i]['product_thinkness'.$i]=$product_thinkness;
		$product_Detail[$i]['product_grade'.$i]=$product_grade;
		$product_Detail[$i]['product_film'.$i]=$product_film;
		$product_Detail[$i]['po_quantity'.$i]=$po_quantity;
		$product_Detail[$i]['gr_quantity'.$i]=$gr_quantity;
		$product_Detail[$i]['defects_quantity'.$i]=$defects_quantity;
		$product_Detail[$i]['remain_quantity'.$i]=$remain_quantity;
		$product_Detail[$i]['gr_qty_percent'.$i]=$gr_qty_percent;
		$product_Detail[$i]['price_usd'.$i]=$price_usd;
		$product_Detail[$i]['unit_price'.$i]=$unit_price;
		
		$discount_percent=$adb->query_result($result,$i-1,'discount_percent');
		$discount_amount=$adb->query_result($result,$i-1,'discount_amount');
		
		$discountTotal = '0.00';
		//Based on the discount percent or amount we will show the discount details

		//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(for Each Product)
		$product_Detail[$i]['discount_percent'.$i] = 0;
		$product_Detail[$i]['discount_amount'.$i] = 0;


		if($discount_percent != 'NULL' && $discount_percent != '')
		{
			$product_Detail[$i]['discount_type'.$i] = "percentage";
			$product_Detail[$i]['discount_percent'.$i] = $discount_percent;
			$product_Detail[$i]['checked_discount_percent'.$i] = ' checked';
			$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:visible"';
			$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:hidden"';
			$discountTotal = $productTotal*$discount_percent/100;
		}
		elseif($discount_amount != 'NULL' && $discount_amount != '')
		{
			$product_Detail[$i]['discount_type'.$i] = "amount";
			$product_Detail[$i]['discount_amount'.$i] = $discount_amount;
			$product_Detail[$i]['checked_discount_amount'.$i] = ' checked';
			$product_Detail[$i]['style_discount_amount'.$i] = ' style="visibility:visible"';
			$product_Detail[$i]['style_discount_percent'.$i] = ' style="visibility:hidden"';
			$discountTotal = $discount_amount;
		}
		else
		{
			$product_Detail[$i]['checked_discount_zero'.$i] = ' checked';
		}

		$totalAfterDiscount = $productTotal-$discountTotal;
		$product_Detail[$i]['discountTotal'.$i] = number_format($discountTotal , 2, '.', '');
		$product_Detail[$i]['totalAfterDiscount'.$i] = number_format($totalAfterDiscount, 2, '.', '');

		$taxTotal = '0.00';
		$product_Detail[$i]['taxTotal'.$i] = number_format($taxTotal, 2, '.', '');

		//Calculate netprice
		$netPrice = $totalAfterDiscount+$taxTotal;
		//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
		
		$taxtype = getInventoryTaxType($module,$focus->id);
		if($taxtype == 'individual')
		{
			//Add the tax with product total and assign to netprice
			$netPrice = $netPrice+$taxTotal;
		}
		
		$product_Detail[$i]['netPrice'.$i] = number_format($netPrice, 2, '.', '');

		//First we will get all associated taxes as array
		$tax_details = getTaxDetailsForProduct($hdnProductId,'all');
		//Now retrieve the tax values from the current query with the name
		for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
		{
			$tax_name = $tax_details[$tax_count]['taxname'];
			$tax_label = $tax_details[$tax_count]['taxlabel'];
			$tax_value = '0.00';

			//condition to avoid this function call when create new PO/SO/Quotes/Invoice from Product module
			if($focus->id != '')
			{
				if($taxtype == 'individual')//if individual then show the entered tax percentage
					$tax_value = getInventoryProductTaxValue($focus->id, $hdnProductId, $tax_name);
				else//if group tax then we have to show the default value when change to individual tax
					$tax_value = $tax_details[$tax_count]['percentage'];
			}
			else//if the above function not called then assign the default associated value of the product
				$tax_value = $tax_details[$tax_count]['percentage'];

			$product_Detail[$i]['taxes'][$tax_count]['taxname'] = $tax_name;
			$product_Detail[$i]['taxes'][$tax_count]['taxlabel'] = $tax_label;
			$product_Detail[$i]['taxes'][$tax_count]['percentage'] = $tax_value;
		}

	}

	//set the taxtype
	$product_Detail[1]['final_details']['taxtype'] = $taxtype;

	//Get the Final Discount, S&H charge, Tax for S&H and Adjustment values
	//To set the Final Discount details
	$finalDiscount = '0.00';
	$product_Detail[1]['final_details']['discount_type_final'] = 'zero';

	$subTotal = ($focus->column_fields['hdnSubTotal'] != '')?$focus->column_fields['hdnSubTotal']:'0.00';

	$product_Detail[1]['final_details']['hdnSubTotal'] = number_format($subTotal,2 , '.', '');
	$discountPercent = ($focus->column_fields['hdnDiscountPercent'] != '')?$focus->column_fields['hdnDiscountPercent']:'0.00';
	$discountAmount = ($focus->column_fields['hdnDiscountAmount'] != '')?$focus->column_fields['hdnDiscountAmount']:'0.00';

	//To avoid NaN javascript error, here we assign 0 initially to' %of price' and 'Direct Price reduction'(For Final Discount)
	$product_Detail[1]['final_details']['discount_percentage_final'] = '0.00';
	$product_Detail[1]['final_details']['discount_amount_final'] = '0.00';

	if($focus->column_fields['hdnDiscountPercent'] != '0')
	{
		$finalDiscount = ($subTotal*$discountPercent/100);
		$product_Detail[1]['final_details']['discount_type_final'] = 'percentage';
		$product_Detail[1]['final_details']['discount_percentage_final'] = $discountPercent;
		$product_Detail[1]['final_details']['checked_discount_percentage_final'] = ' checked';
		$product_Detail[1]['final_details']['style_discount_percentage_final'] = ' style="visibility:visible"';
		$product_Detail[1]['final_details']['style_discount_amount_final'] = ' style="visibility:hidden"';
	}
	elseif($focus->column_fields['hdnDiscountAmount'] != '0')
	{
		$finalDiscount = $focus->column_fields['hdnDiscountAmount'];
		$product_Detail[1]['final_details']['discount_type_final'] = 'amount';
		$product_Detail[1]['final_details']['discount_amount_final'] =  number_format($discountAmount,2, '.', '');
		$product_Detail[1]['final_details']['checked_discount_amount_final'] = ' checked';
		$product_Detail[1]['final_details']['style_discount_amount_final'] = ' style="visibility:visible"';
		$product_Detail[1]['final_details']['style_discount_percentage_final'] = ' style="visibility:hidden"';
	}
	$product_Detail[1]['final_details']['discountTotal_final'] = number_format($finalDiscount,2, '.', '');
		
	$taxtotal = '0.00';
	//First we should get all available taxes and then retrieve the corresponding tax values
	$tax_details = getAllTaxes('available','','edit',$focus->id);

	for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
	{
		$tax_name = $tax_details[$tax_count]['taxname'];
		$tax_label = $tax_details[$tax_count]['taxlabel'];

		//if taxtype is individual and want to change to group during edit time then we have to show the all available taxes and their default values
		//Also taxtype is group and want to change to individual during edit time then we have to provide the asspciated taxes and their default tax values for individual products
		if($taxtype == 'group')
			$tax_percent = $adb->query_result($result,0,$tax_name);
		else
			$tax_percent = $tax_details[$tax_count]['percentage'];//$adb->query_result($result,0,$tax_name);

		if($tax_percent == '' || $tax_percent == 'NULL')
			$tax_percent = '0.00';
		$taxamount = ($subTotal-$finalDiscount)*$tax_percent/100;
		$taxtotal = $taxtotal + $taxamount;
		$product_Detail[1]['final_details']['taxes'][$tax_count]['taxname'] = $tax_name;
		$product_Detail[1]['final_details']['taxes'][$tax_count]['taxlabel'] = $tax_label;
		$product_Detail[1]['final_details']['taxes'][$tax_count]['percentage'] = $tax_percent;
		$product_Detail[1]['final_details']['taxes'][$tax_count]['amount'] = $taxamount;
	}


		$product_Detail[1]['final_details']['tax_totalamount'] = $taxtotal;
	
	
	//To set the Shipping & Handling charge
	$shCharge = ($focus->column_fields['hdnS_H_Amount'] != '')?$focus->column_fields['hdnS_H_Amount']:'0.00';
	$product_Detail[1]['final_details']['shipping_handling_charge'] = $shCharge;

	//To set the Shipping & Handling tax values
	//calculate S&H tax
	$shtaxtotal = '0.00';
	//First we should get all available taxes and then retrieve the corresponding tax values
	$shtax_details = getAllTaxes('available','sh','edit',$focus->id);

	//if taxtype is group then the tax should be same for all products in aicrm_inventoryproductrel table
	for($shtax_count=0;$shtax_count<count($shtax_details);$shtax_count++)
	{
		$shtax_name = $shtax_details[$shtax_count]['taxname'];
		$shtax_label = $shtax_details[$shtax_count]['taxlabel'];
		$shtax_percent = '0.00';
		//if condition is added to call this function when we create PO/SO/Quotes/Invoice from Product module
		if($module == 'PurchaseOrder' || $module == 'SalesOrder' || $module == 'Quotes' || $module == 'Order' || $module == 'Invoice')
		{
			$shtax_percent = getInventorySHTaxPercent($focus->id,$shtax_name);
		}
		$shtaxamount = $shCharge*$shtax_percent/100;
		$shtaxtotal = $shtaxtotal + $shtaxamount;
		$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxname'] = $shtax_name;
		$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['taxlabel'] = $shtax_label;
		$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['percentage'] = $shtax_percent;
		$product_Detail[1]['final_details']['sh_taxes'][$shtax_count]['amount'] = $shtaxamount;
	}
	$product_Detail[1]['final_details']['shtax_totalamount'] = $shtaxtotal;

	//To set the Adjustment value
	$adjustment = ($focus->column_fields['txtAdjustment'] != '')?$focus->column_fields['txtAdjustment']:'0.00';
	$product_Detail[1]['final_details']['adjustment'] = $adjustment;

	//To set the grand total
	$grandTotal = ($focus->column_fields['hdnGrandTotal'] != '')?$focus->column_fields['hdnGrandTotal']:'0.00';
	$product_Detail[1]['final_details']['grandTotal'] = $grandTotal;

	$log->debug("Exiting getAssociatedProductsPurchasesorder method ...");
	//echo "<pre>"; print_r($product_Detail); echo "</pre>"; exit;
	return $product_Detail;
}

function getAssociatedGoodsreceive($module,$focus,$seid='')
{

	global $log;
	$log->debug("Entering getAssociatedGoodsreceive(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();
	/*$query="SELECT
			aicrm_products.productname,
			aicrm_inventorysamplerequisition.*
			FROM aicrm_inventorysamplerequisition
			LEFT join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inventorysamplerequisition.productid
			LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_inventorysamplerequisition.productid
			WHERE id=?
			ORDER BY sequence_no";*/
	$query="select aicrm_inventorygoodsreceive.* ,
			aicrm_products.productid,
			aicrm_products.productname,
			aicrm_purchasesorder.purchasesorderid,
			aicrm_purchasesorder.purchasesorder_name,
			aicrm_purchasesorder.purchasesorder_no,
			aicrm_projects.projectsid,
			aicrm_projects.projects_name,
			aicrm_projects.projects_no,
			aicrm_crmentity.smownerid,
			ifnull(concat(aicrm_users.first_name,' ',aicrm_users.last_name),'') as fullname" .
			" from aicrm_inventorygoodsreceive" .
			" left join aicrm_purchasesorder on aicrm_purchasesorder.purchasesorderid=aicrm_inventorygoodsreceive.purchasesorderid " .
			" left join aicrm_products on aicrm_products.productid=aicrm_inventorygoodsreceive.productid " .
			" left join aicrm_projects on aicrm_projects.projectsid=aicrm_inventorygoodsreceive.projectsid " .
			" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_projects.projectsid ".
  			" left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid ".
			" where aicrm_inventorygoodsreceive.id=? ORDER BY sequence_no";
	$params = array($focus->id);
	
	$result = $adb->pquery($query, $params);
	$num_rows=$adb->num_rows($result);

	for($i=1;$i<=$num_rows;$i++)
	{
		$comment = $adb->query_result($result,$i-1,'comment');
		$purchasesorder_no = $adb->query_result($result,$i-1,'purchasesorder_no');
		$hdnPoId = $adb->query_result($result,$i-1,'purchasesorderid');
		$lineItemType = $adb->query_result($result,$i-1,'lineitemtype');
		$poName = $adb->query_result($result,$i-1,'purchasesorder_name');
		$purchase_order_date=$adb->query_result($result,$i-1,'purchase_order_date');
		$projects_code=$adb->query_result($result,$i-1,'projects_no');
		$projectsid=$adb->query_result($result,$i-1,'projectsid');
		$projects_name=$adb->query_result($result,$i-1,'projects_name');
		$assignto=$adb->query_result($result,$i-1,'assignto');
		$smownerid=$adb->query_result($result,$i-1,'smownerid');
		$product_code_crm=$adb->query_result($result,$i-1,'product_code_crm');
		$productid=$adb->query_result($result,$i-1,'productid');
		$productname=$adb->query_result($result,$i-1,'productname');
		$po_detail_no=$adb->query_result($result,$i-1,'po_detail_no');
		$po_quantity=$adb->query_result($result,$i-1,'po_quantity');
		$gr_percentage=$adb->query_result($result,$i-1,'gr_percentage');
		$item_status=$adb->query_result($result,$i-1,'item_status');
		$gr_qty_percent=$adb->query_result($result,$i-1,'gr_qty_percent');
		$gr_quantity=$adb->query_result($result,$i-1,'gr_quantity');
		$defects_quantity=$adb->query_result($result,$i-1,'defects_quantity');
		$remain_quantity=$adb->query_result($result,$i-1,'remain_quantity');
		$unit_price=$adb->query_result($result,$i-1,'unit_price');
		$amount=$adb->query_result($result,$i-1,'amount');
		$total_defects_quantity=$adb->query_result($result,$i-1,'total_defects_quantity');
		$total_gr_quantity=$adb->query_result($result,$i-1,'total_gr_quantity');
		$total_amount=$adb->query_result($result,$i-1,'total_amount');
		$defects_remark=$adb->query_result($result,$i-1,'defects_remark');

        $product_Detail[$i]['comment'.$i] = $comment;
		$product_Detail[$i]['purchasesorder_no'.$i] = $purchasesorder_no;
		$product_Detail[$i]['hdnPoId'.$i] = $hdnPoId;
		$product_Detail[$i]['lineItemType'.$i] = $lineItemType;
		$product_Detail[$i]['poName'.$i] = $poName;
		$product_Detail[$i]['purchase_order_date'.$i] = $purchase_order_date;
		$product_Detail[$i]['projects_code'.$i] = $projects_code;
		$product_Detail[$i]['projectsid'.$i] = $projectsid;
		$product_Detail[$i]['projects_name'.$i] = $projects_name;
		$product_Detail[$i]['assignto'.$i] = $assignto;
		$product_Detail[$i]['smownerid'.$i] = $smownerid;
		$product_Detail[$i]['product_code_crm'.$i] = $product_code_crm;
		$product_Detail[$i]['productid'.$i] = $productid;
		$product_Detail[$i]['productname'.$i] = $productname;
		$product_Detail[$i]['po_detail_no'.$i] = $po_detail_no;
		$product_Detail[$i]['po_quantity'.$i] = $po_quantity;
		$product_Detail[$i]['gr_percentage'.$i] = $gr_percentage;
		$product_Detail[$i]['item_status'.$i] = $item_status;
		$product_Detail[$i]['gr_qty_percent'.$i] = $gr_qty_percent;
		$product_Detail[$i]['gr_quantity'.$i] = 0;
		$product_Detail[$i]['defects_quantity'.$i] = 0;
		$product_Detail[$i]['remain_quantity'.$i] = $remain_quantity;
		$product_Detail[$i]['unit_price'.$i] = $unit_price;
		$product_Detail[$i]['amount'.$i] = $amount;
		$product_Detail[$i]['total_defects_quantity'.$i] = $total_defects_quantity;
		$product_Detail[$i]['total_gr_quantity'.$i] = $total_gr_quantity;
		$product_Detail[$i]['total_amount'.$i] = $total_amount;
		$product_Detail[$i]['defects_remark'.$i] = $defects_remark;
	}
	
	$log->debug("Exiting getAssociatedGoodsreceive method ...");
	
	return $product_Detail;
}

function getAssociatedPriceList($module,$focus,$seid='')
{

	global $log;
	$log->debug("Entering getAssociatedPriceList(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();
	
	$query="select aicrm_inventorypricelist.* ,
			aicrm_products.productid,
			aicrm_products.productname" .
			" from aicrm_inventorypricelist" .
			" left join aicrm_products on aicrm_products.productid=aicrm_inventorypricelist.productid " .
			" where aicrm_inventorypricelist.id=? ORDER BY sequence_no";
	$params = array($focus->id);
	
	$result = $adb->pquery($query, $params);
	$num_rows=$adb->num_rows($result);

	for($i=1;$i<=$num_rows;$i++)
	{
		$hdnProductId = $adb->query_result($result,$i-1,'productid');
		$productName = $adb->query_result($result,$i-1,'productname');
		$comment=$adb->query_result($result,$i-1,'comment');

		$product_brand=$adb->query_result($result,$i-1,'product_brand');
		$product_weight_per_box=$adb->query_result($result,$i-1,'product_weight_per_box');
		$productstatus=$adb->query_result($result,$i-1,'productstatus');
		$pricelist_showroom=$adb->query_result($result,$i-1,'pricelist_showroom');
		$listprice_project=$adb->query_result($result,$i-1,'listprice_project');
		$pricelist_nomal=$adb->query_result($result,$i-1,'pricelist_nomal');
		$pricelist_first_tier=$adb->query_result($result,$i-1,'pricelist_first_tier');
		$pricelist_second_tier=$adb->query_result($result,$i-1,'pricelist_second_tier');
		$pricelist_third_tier=$adb->query_result($result,$i-1,'pricelist_third_tier');
		$selling_price=$adb->query_result($result,$i-1,'selling_price');
		$selling_price_inc=$adb->query_result($result,$i-1,'selling_price_inc');

		$product_Detail[$i]['hdnProductId'.$i] = $hdnProductId;
		$product_Detail[$i]['productName'.$i] = $productName;
		$product_Detail[$i]['comment'.$i]= $comment;
		$product_Detail[$i]['product_brand'.$i]= $product_brand;
		$product_Detail[$i]['product_weight_per_box'.$i]= $product_weight_per_box;
		$product_Detail[$i]['productstatus'.$i]= $productstatus;
		$product_Detail[$i]['pricelist_showroom'.$i]= $pricelist_showroom;
		$product_Detail[$i]['listprice_project'.$i]= $listprice_project;
		$product_Detail[$i]['pricelist_nomal'.$i]= $pricelist_nomal;
		$product_Detail[$i]['pricelist_first_tier'.$i]= $pricelist_first_tier;
		$product_Detail[$i]['pricelist_second_tier'.$i]= $pricelist_second_tier;
		$product_Detail[$i]['pricelist_third_tier'.$i]= $pricelist_third_tier;
		$product_Detail[$i]['selling_price'.$i]= $selling_price;
		$product_Detail[$i]['selling_price_inc'.$i]= $selling_price_inc;
	}
	
	$log->debug("Exiting getAssociatedPriceList method ...");
	
	return $product_Detail;
}


function getAssociatedProjects($module,$focus,$seid='')
{

	global $log;
	$log->debug("Entering getAssociatedProjects(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();
	
	$query="select aicrm_inventoryprojects.* ,
			aicrm_products.productid,
			aicrm_products.productname,
			aicrm_account.accountid,
			aicrm_account.accountname " .
			" from aicrm_inventoryprojects" .
			" left join aicrm_products on aicrm_products.productid=aicrm_inventoryprojects.productid " .
			" left join aicrm_account on aicrm_account.accountid=aicrm_inventoryprojects.accountid " .
			" where aicrm_inventoryprojects.id=? ORDER BY sequence_no";
	$params = array($focus->id);
	
	$result = $adb->pquery($query, $params);
	$num_rows=$adb->num_rows($result);

	for($i=1;$i<=$num_rows;$i++)
	{
		$hdnProductId = $adb->query_result($result,$i-1,'productid');
		$productName = $adb->query_result($result,$i-1,'productname');
		$comment=$adb->query_result($result,$i-1,'comment');

		$product_brand=$adb->query_result($result,$i-1,'product_brand');
		$product_group=$adb->query_result($result,$i-1,'product_group');
		$accountid=$adb->query_result($result,$i-1,'accountid');
		$accountname=$adb->query_result($result,$i-1,'accountname');
		
		$first_delivered_date=$adb->query_result($result,$i-1,'first_delivered_date');
		$s_first_delivered_date = '';
		if($first_delivered_date != '0000-00-00'){
			$s_first_delivered_date = date("d-m-Y", strtotime($first_delivered_date));
		}
		
		$last_delivered_date=$adb->query_result($result,$i-1,'last_delivered_date');
		$e_last_delivered_date = '';
		if($last_delivered_date != '0000-00-00'){
			$e_last_delivered_date = date("d-m-Y", strtotime($last_delivered_date));
		}

		$plan=$adb->query_result($result,$i-1,'plan');
		$estimated=$adb->query_result($result,$i-1,'estimated');
		$delivered=$adb->query_result($result,$i-1,'delivered');
		$remain_on_hand=$adb->query_result($result,$i-1,'remain_on_hand');
		$listprice=$adb->query_result($result,$i-1,'listprice');

		$product_Detail[$i]['hdnProductId'.$i] = $hdnProductId;
		$product_Detail[$i]['productName'.$i] = $productName;
		$product_Detail[$i]['comment'.$i]= $comment;

		$product_Detail[$i]['product_brand'.$i]= $product_brand;
		$product_Detail[$i]['product_group'.$i]= $product_group;
		$product_Detail[$i]['accountid'.$i]= $accountid;
		$product_Detail[$i]['account_name'.$i]= $accountname;
		$product_Detail[$i]['first_delivered_date'.$i]= $s_first_delivered_date;
		$product_Detail[$i]['last_delivered_date'.$i]= $e_last_delivered_date;
		$product_Detail[$i]['plan'.$i]= $plan;
		$product_Detail[$i]['estimated'.$i]= $estimated;
		$product_Detail[$i]['delivered'.$i]= $delivered;
		$product_Detail[$i]['remain_on_hand'.$i]= $remain_on_hand;
		$product_Detail[$i]['listprice'.$i]= $listprice;
	}
	
	$log->debug("Exiting getAssociatedProjects method ...");
	
	return $product_Detail;
}

function getAssociatedCompetitorproduct($module,$focus,$seid='')
{
	global $log;
	$log->debug("Entering getAssociatedCompetitorproduc(".$module.",".get_class($focus).",".$seid."='') method ...");
	global $adb;
	$output = '';
	global $theme,$current_user;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$product_Detail = Array();
	
	$query="select aicrm_inventorycompetitorproduct.* ,
			aicrm_competitorproduct.competitorproductid,
			aicrm_competitorproduct.competitorproduct_name_th " .
			" from aicrm_inventorycompetitorproduct" .
			" left join aicrm_competitorproduct on aicrm_competitorproduct.competitorproductid=aicrm_inventorycompetitorproduct.competitorproductid " .
			" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_competitorproduct.competitorproductid " .
			" where aicrm_inventorycompetitorproduct.id=? ORDER BY sequence_no";
	$params = array($focus->id);
	
	$result = $adb->pquery($query, $params);
	$num_rows=$adb->num_rows($result);

	for($i=1;$i<=$num_rows;$i++)
	{
		$hdnCompetitorProductId = $adb->query_result($result,$i-1,'competitorproductid');
		$CompetitorproductName = $adb->query_result($result,$i-1,'competitorproduct_name_th');
		$competitorcomment=$adb->query_result($result,$i-1,'competitorcomment');

		$competitor_brand=$adb->query_result($result,$i-1,'competitor_brand');
		$comprtitor_product_group=$adb->query_result($result,$i-1,'comprtitor_product_group');
		$comprtitor_product_size=$adb->query_result($result,$i-1,'comprtitor_product_size');
		$comprtitor_product_thickness=$adb->query_result($result,$i-1,'comprtitor_product_thickness');
		$comprtitor_estimated_unit=$adb->query_result($result,$i-1,'comprtitor_estimated_unit');
		$competitor_price=$adb->query_result($result,$i-1,'competitor_price');

		$product_Detail[$i]['hdnCompetitorProductId'.$i] = $hdnCompetitorProductId;
		$product_Detail[$i]['CompetitorproductName'.$i] = $CompetitorproductName;
		$product_Detail[$i]['competitorcomment'.$i]= $competitorcomment;

		$product_Detail[$i]['competitor_brand'.$i]= $competitor_brand;
		$product_Detail[$i]['comprtitor_product_group'.$i]= $comprtitor_product_group;
		$product_Detail[$i]['comprtitor_product_size'.$i]= $comprtitor_product_size;
		$product_Detail[$i]['comprtitor_product_thickness'.$i]= $comprtitor_product_thickness;
		$product_Detail[$i]['comprtitor_estimated_unit'.$i]= $comprtitor_estimated_unit;
		$product_Detail[$i]['competitor_price'.$i]= $competitor_price;
	}
	
	$log->debug("Exiting getAssociatedCompetitorproduc method ...");
	
	return $product_Detail;
}

?>
