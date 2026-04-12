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
 * $Header: /advent/projects/wesat/aicrm_crm/sugarcrm/include/utils/DetailViewUtils.php,v 1.188 2005/04/29 05:5 * 4:39 rank Exp
 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php'); //new
require_once('include/utils/CommonUtils.php'); //new

/** This function returns the detail view form aicrm_field and and its properties in array format.
  * Param $uitype - UI type of the aicrm_field
  * Param $fieldname - Form aicrm_field name
  * Param $fieldlabel - Form aicrm_field label name
  * Param $col_fields - array contains the aicrm_fieldname and values
  * Param $generatedtype - Field generated type (default is 1)
  * Param $tabid - aicrm_tab id to which the Field belongs to (default is "")
  * Return type is an array
  */

function getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype,$tabid='',$module='')
{
	global $log;
	global $list_max_tags;
	$log->debug("Entering getDetailViewOutputHtml(".$uitype.",". $fieldname.",". $fieldlabel.",". $col_fields.",".$generatedtype.",".$tabid.") method ...");
	global $adb;
	global $mod_strings;
	global $app_strings;
	global $current_user;
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$fieldlabel = from_html($fieldlabel);
	$custfld = '';
	$value ='';
	$arr_data =Array();
	$label_fld = Array();
	$data_fld = Array();
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
//	echo '<pre>';print_r($uitype); echo '</pre>';
	// vtlib customization: New uitype to handle relation between modules
	if($uitype == '10'){
		$fieldlabel = getTranslatedString($fieldlabel, $module);

		$parent_id = $col_fields[$fieldname];
		if(!empty($parent_id)) {
			$parent_module = getSalesEntityType($parent_id);
			$valueTitle=$parent_module;
			if($app_strings[$valueTitle]) $valueTitle = $app_strings[$valueTitle];

			$displayValueArray = getEntityName($parent_module, $parent_id);
			if(!empty($displayValueArray)){
				foreach($displayValueArray as $key=>$value){
					$displayValue = $value;
				}
			}
			$label_fld=array($fieldlabel,
				"<a href='index.php?module=$parent_module&action=DetailView&record=$parent_id' title='$valueTitle'>$displayValue</a>");
		} else {
			$moduleSpecificMessage = 'MODULE_NOT_SELECTED';
			if($mod_strings[$moduleSpecificMessage] != ""){
				$moduleSpecificMessage = $mod_strings[$moduleSpecificMessage];
			}
			$label_fld=array($fieldlabel, '');
		}
	} // END
	
	else if($uitype == 99)
	{
		$label_fld[] = getTranslatedString($fieldlabel, $module);
		$label_fld[] = $col_fields[$fieldname];
		if($fieldname == 'confirm_password')
			return null;
	}elseif($uitype == 116 || $uitype == 117)
	{
		$label_fld[] = getTranslatedString($fieldlabel, $module);
        $label_fld[] = getCurrencyName($col_fields[$fieldname]);
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
			if($col_fields[$fieldname] == $currency_id)
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
		$label_fld ["options"] = $options;
	}
	elseif($uitype == 13 || $uitype == 104)
	{
		$label_fld[] = getTranslatedString($fieldlabel, $module);
		$label_fld[] = $col_fields[$fieldname];
	}
	elseif($uitype == 201)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Accounts")
			{
				$label_fld[] = getTranslatedString($fieldlabel, $module);
				//$event_name = get_Job_Name($value);
				$event_name = getAccountName($value);
				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$event_name.'</a>';
			}
			elseif($parent_module == "Leads")
			{
				$label_fld[] = getTranslatedString($fieldlabel, $module);
				$event_name = getLeadName($value);
				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$event_name.'</a>';
			}
			else
			{
				$value ='';
				$label_fld[] = getTranslatedString($fieldlabel, $module);
				$label_fld[] = $value;
			}
		}
		else
		{
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $value;
		}
	}
	elseif($uitype == 800)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getAccountNameRel($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Accounts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 801)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 802)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != ''&& $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 803)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 804)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 805)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 806)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 807)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 808)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 809)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 810)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 811)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 812)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 813)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 814)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 815)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 816)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 817)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 818)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 819)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 820)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 821)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 822)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 823)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 824)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 825)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 826)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '' && $crmid != 0)
		{
			$name = getContact_id($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
    }
	elseif($uitype == 900)
    {	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];	
		if($crmid != '')
		{
			$name = getsalesinvoice($crmid);
		}

		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Salesinvoice&action=DetailView&record=".$crmid;

    }
	elseif($uitype == 901)
	{
		$label_fld[] = getTranslatedString($fieldlabel, $module);
		$label_fld[] = $col_fields[$fieldname];
		$sql = "select   a.productname as productname ,c.cf_566 as cf_566
					from aicrm_products as a
					left join aicrm_crmentity as b  on a.productid=b.crmid
					left join aicrm_productcf as c on a.productid=c.productid
					where b.deleted=0 order by a.productname ";
		$res=$adb->pquery($sql, array());
		$noofrows = $adb->num_rows($res);
		for($i=0;$i<$noofrows;$i++)
		{
			$productname[$i]['productname'] = ($adb->query_result($res,$i,'productname').' : '.$adb->query_result($res,$i,'cf_566'));
			$productname[$i]['product_select'] = $col_fields[$fieldname];
		}
		$label_fld ["options"] = $productname;
	}
	elseif($uitype == 902)
	{
		$label_fld[] = getTranslatedString($fieldlabel, $module);
		$label_fld[] = $col_fields[$fieldname];
		$sql = "select a.accountname from aicrm_account as a left join aicrm_crmentity as b  on a.accountid=b.crmid  where b.deleted=0 order by a.accountname ";
		$res=$adb->pquery($sql, array());
		$noofrows = $adb->num_rows($res);
		for($i=0;$i<$noofrows;$i++)
		{
			$accountname[$i]['accountname'] = $adb->query_result($res,$i,'accountname');
			$accountname[$i]['account_select'] = $col_fields[$fieldname];
		}
		$label_fld ["options"] = $accountname;
	}
	elseif($uitype == 903)
	{
		if($fieldname=="quota1" || $fieldname=="quota_signa" || $fieldname=="quota_publisher"){
			$editview_label[]=getTranslatedString($fieldlabel, $module_name);
			$section=$current_user->column_fields["section"];
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $col_fields[$fieldname];
			$sql = "select concat(a.first_name,' ',a.last_name) as full_name 
					from aicrm_users as a 
					where  deleted=0  
					and bidder=1
					and  status='Active'
					";
			$sql .= " and section = '".$section."'  ";
			$sql .= " order by concat(a.first_name,' ',a.last_name) ";
			$res=$adb->pquery($sql, array());
			$noofrows = $adb->num_rows($res);
			for($i=0;$i<$noofrows;$i++)
			{
				$username[$i]['username'] = $adb->query_result($res,$i,'full_name');
				$username[$i]['user_select'] = $col_fields[$fieldname];
			}
			$label_fld ["options"] = $username;
		
		}else{
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $col_fields[$fieldname];
			$sql = "select concat( a.first_name, ' ', a.last_name ,' [',a.user_name,']') as full_name from aicrm_users as a left join aicrm_crmentity as b  on a.id=b.crmid and a.status='Active'   order by concat(a.first_name,' ',a.last_name) ";

			$res=$adb->pquery($sql, array());
			$noofrows = $adb->num_rows($res);
			for($i=0;$i<$noofrows;$i++)
			{
				$username[$i]['username'] = $adb->query_result($res,$i,'full_name');
				$username[$i]['user_select'] = $col_fields[$fieldname];
			}
			$label_fld ["options"] = $username;
		}
	}
	elseif($uitype == 904)
    {	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];	
		if($crmid != '')
		{
			$name = getProject($crmid);
		}

		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Projects&action=DetailView&record=".$crmid;

    }
	elseif($uitype == 912)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$product_id = $col_fields[$fieldname];
		if($product_id != '')
		{
			$product_name = getProductName($product_id);
		}
		//Account Name View
		$label_fld[] = $product_name;
		$label_fld["secid"] = $product_id;
		$label_fld["link"] = "index.php?module=Products&action=DetailView&record=".$product_id;

	}
	elseif($uitype == 914)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Job")
			{
				$label_fld[] = getTranslatedString($fieldlabel, $module);
				//$event_name = get_Job_Name($value);
				$event_name = getjob($value);
				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$event_name.'</a>';
			}
			elseif($parent_module == "HelpDesk")
			{
				$label_fld[] = getTranslatedString($fieldlabel, $module);
				$event_name = get_HelpDesk_No($value);
				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$event_name.'</a>';
			}
			elseif($parent_module == "Projects")
			{
				$label_fld[] = getTranslatedString($fieldlabel, $module);
				$event_name = get_Projects_Name($value);
				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$event_name.'</a>';
			}
			elseif($parent_module == "Deal")
			{
				$label_fld[] = getTranslatedString($fieldlabel, $module);
				$event_name = getdealname($value);
				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$event_name.'</a>';
			}
			else
			{
				$value ='';
				$label_fld[] = getTranslatedString($fieldlabel, $module);
				$label_fld[] = $value;
			}
		}
		else
		{
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $value;
		}
	}
	elseif($uitype == 921)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '')
		{
			$name = getAccountPrevName($crmid);
		}
		//Account Name View
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Accounts&action=DetailView&record=".$crmid;

	}
	elseif($uitype == 923 || $uitype == 924)
	{
		//telephone country code
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$label_fld["salut"] = $col_fields["telephonecountrycode"];
		$label_fld[] = $col_fields[$fieldname];

	}
	elseif($uitype == 925 || $uitype == 926)
	{
		//telephone country code
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$label_fld["salut"] = $col_fields["mobilecountrycode"];
		$label_fld[] = $col_fields[$fieldname];

	}
	elseif($uitype == 927 || $uitype == 928)
	{
		//telephone country code
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$label_fld["salut"] = $col_fields["faxcountrycode"];
		$label_fld[] = $col_fields[$fieldname];

	}
	elseif($uitype == 929)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		if($crmid != '')
		{
			$name = getAccountNameField($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Accounts&action=DetailView&record=".$crmid;
	}
	elseif($uitype == 930)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];


		if($crmid != '')
		{
			$name = getAccountcoderms($crmid);
		}

		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Accounts&action=DetailView&record=".$crmid;

	}
	elseif($uitype == 931)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];


		if($crmid != '')
		{
			$name = getContactcode($crmid);
		}
		//echo $name;
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;

	}
	elseif($uitype == 932)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
        $crmid = $col_fields[$fieldname];
        if($crmid != '' && $crmid !='0')
        {
            $crm_name = getOwnerName($crmid);
        }
        //Modify by
        $label_fld[] = $crm_name;
        $label_fld["secid"] = $crmid;
        $label_fld["link"] = "index.php?module=Users&action=DetailView&record=".$crmid;
    }
	elseif($uitype == 934)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
		
		if($crmid != '')
		{
			$name = getContactcode($crmid);
		}
		//echo $name;
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Contacts&action=DetailView&record=".$crmid;
		/*if($crmid != '')
		{
			$name = getProject($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Projects&action=DetailView&record=".$crmid;*/
	}
	elseif($uitype == 935)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
				
		if($crmid != '')
		{
			$name = getSerial($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Serial&action=DetailView&record=".$crmid;
	}

	elseif($uitype == 936)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
				
		if($crmid != '')
		{
			$name = getSparepart($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Sparepart&action=DetailView&record=".$crmid;
	}

	elseif($uitype == 937)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
				
		if($crmid != '')
		{
			$name = geterrorno($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Errors&action=DetailView&record=".$crmid;
	}

	elseif($uitype == 938)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];
				
		if($crmid != '')
		{
			$name = getjob($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Job&action=DetailView&record=".$crmid;
	}

	elseif($uitype == 939)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getcase($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=HelpDesk&action=DetailView&record=".$crmid;
		//print_r($label_fld);
	}
    elseif($uitype == 940)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
        $crmid = $col_fields[$fieldname];
        if($crmid != '' && $crmid !='0')
        {
            $crm_name = getOwnerName($crmid);
        }
        //create by
        $label_fld[] = $crm_name;
        $label_fld["secid"] = $crmid;
        $label_fld["link"] = "index.php?module=Users&action=DetailView&record=".$crmid;

    }elseif($uitype == 910) {

        $label_fld[] = getTranslatedString($fieldlabel, $module);
        $crmid = $col_fields[$fieldname];
        if ($crmid != '') {
            $name = getquestionnairetemplatename($crmid);
        }
        $label_fld[] = $name;
        $label_fld["secid"] = $crmid;
        $label_fld["link"] = "index.php?module=Questionnairetemplate&action=DetailView&record=" . $crmid;
    }
    elseif($uitype == 941)
    {	
    	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getplant($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Plant&action=DetailView&record=".$crmid;

    }
    
    elseif($uitype == 943)
    {	
    	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getLeadName($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Leads&action=DetailView&record=".$crmid;

    }
    elseif($uitype == 944)
    {	
    	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getactivity($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Calendar&action=DetailView&record=".$crmid;

    }
    elseif($uitype == 963)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
        $crmid = $col_fields[$fieldname];

        if($crmid != '')
        {
            $name = getinspection_template_name($crmid);
        }
       // echo $name; exit;
        $label_fld[] = $name;
        $label_fld["secid"] = $crmid;
        $label_fld["link"] = "index.php?module=Inspectiontemplate&action=DetailView&record=".$crmid;

    }
    elseif($uitype == 301)
    {	
    	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getdealname($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Deal&action=DetailView&record=".$crmid;

    }
    elseif($uitype == 302)
    {	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getcompetitorname($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Competitor&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 303)
    {	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getpromotionvouchername($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Promotionvoucher&action=DetailView&record=".$crmid;
    }
    elseif($uitype == 304)
    {	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getpromotion($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Promotion&action=DetailView&record=".$crmid;

    }
    elseif($uitype == 305)
    {	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getsalesorderno($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Salesorder&action=DetailView&record=".$crmid;

    }
    elseif($uitype == 306)
    {	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getpremuimproductno($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Premuimproduct&action=DetailView&record=".$crmid;

    }
    elseif($uitype == 307)
    {	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getquotesno($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Quotes&action=DetailView&record=".$crmid;

    }
    elseif($uitype == 308)
    {	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getservicerequest($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Servicerequest&action=DetailView&record=".$crmid;

    }
    elseif($uitype == 309)
    {	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getticket($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=HelpDesk&action=DetailView&record=".$crmid;

    }
    elseif($uitype == 310)
    {
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '' && $crmid != 0)
		{
			$name = getactivity_name($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Calendar&action=DetailView&record=".$crmid;

    }
    elseif($uitype == 946)
    {	
    	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getquestionnairetemplatename($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Questionnairetemplate&action=DetailView&record=".$crmid;

    }
    elseif($uitype == 947)
    {	
    	
        $label_fld[] =getTranslatedString($fieldlabel, $module);
		$crmid = $col_fields[$fieldname];		
		if($crmid != '')
		{
			$name = getquestionnairename($crmid);
		}
		$label_fld[] = $name;
		$label_fld["secid"] = $crmid;
		$label_fld["link"] = "index.php?module=Questionnaire&action=DetailView&record=".$crmid;

    }
    elseif($uitype == 948)
    {
    	$label_fld[] = getTranslatedString($fieldlabel, $module);
        $value = $col_fields[$fieldname];
        if($value != ''){
        	$label_fld[] = '<div class="mapouter"><div class="gmap_canvas"><iframe class="gmap_iframe" width="100%" height="400px" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&height=400&hl=en&q='.$value.'&t=&z=14&ie=UTF8&iwloc=B&output=embed"></iframe></div></div>';
        }else{
        	$label_fld[] = '<div class="mapouter"><div class="gmap_canvas"><iframe class="gmap_iframe" width="100%" height="400px" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&height=400&hl=en&q='.$value.'&t=&z=14&ie=UTF8&iwloc=B&output=embed"></iframe></div></div>';
        }
       	
    }	
    elseif($uitype == 949)
    {
    	$label_fld[] = getTranslatedString($fieldlabel, $module);
        $value = $col_fields[$fieldname];
       	$label_fld[] = $value;
    }	
    elseif($uitype == 997)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		
		if($tabid==88)
		{
			$images=array();
			$query = 'select
			aicrm_attachments.path,
			aicrm_attachments.attachmentsid,
			aicrm_attachments.name ,
			aicrm_crmentity.setype
			from aicrm_order
			left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_order.orderid
			inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
			where aicrm_crmentity.setype="Image 997"
			and aicrm_order.orderid=?';
			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
			$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');

			$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
			$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));

			$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
					$sides=8;

					$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

				for($image_iter=0;$image_iter < count($image_array);$image_iter++)
					{
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
					$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;

			}elseif(count($image_array)==1){
			
			list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
			if($pro_image_width  > 450 ||  $pro_image_height > 300)
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
				else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
			}else
			{
				$label_fld[] ='';
			}
		
		}else{
			
			$images=array();
			$query="select aicrm_attachments.*,aicrm_crmentity.setype from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid where aicrm_crmentity.setype='Image 997' and aicrm_seattachmentsrel.crmid=?";
			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
				$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
				$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
				$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));
				$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
					$sides=8;
					$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

				for($image_iter=0;$image_iter < count($image_array);$image_iter++)
							{
							$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
					$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
			{
			list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
			if($pro_image_width  > 450 ||  $pro_image_height > 300)
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
				else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
			}else
			{
				$label_fld[] ='';
			}
		}

	}
    elseif($uitype == 998)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		
		if($tabid==41)
		{
			$images=array();
			$query = 'select
			aicrm_attachments.path,
			aicrm_attachments.attachmentsid,
			aicrm_attachments.name ,
			aicrm_crmentity.setype
			from aicrm_jobs
			left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_jobs.jobid
			inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
			where aicrm_crmentity.setype="Image 998"
			and aicrm_jobs.jobid=?';
			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
			$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');

			$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
			$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));

			$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
					$sides=8;

					$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

				for($image_iter=0;$image_iter < count($image_array);$image_iter++)
							{
							$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
					$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
			{
			list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
			if($pro_image_width  > 450 ||  $pro_image_height > 300)
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
				else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
			}else
			{
				$label_fld[] ='';
			}
		
		}else{
			
			$images=array();
			$query="select aicrm_attachments.*,aicrm_crmentity.setype from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid where aicrm_crmentity.setype='Image 998' and aicrm_seattachmentsrel.crmid=?";
			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
			$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');

			$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
			$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));

			$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
					$sides=8;
					$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

				for($image_iter=0;$image_iter < count($image_array);$image_iter++)
							{
							$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
					$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
			{
			list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
			if($pro_image_width  > 450 ||  $pro_image_height > 300)
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
				else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
			}else
			{
				$label_fld[] ='';
			}
		}

	}
	elseif($uitype == 999)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);

		if($tabid==41)
		{
			$images=array();
			$query = 'select
			aicrm_attachments.path,
			aicrm_attachments.attachmentsid,
			aicrm_attachments.name ,
			aicrm_crmentity.setype
			from aicrm_jobs
			left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_jobs.jobid
			inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
			where aicrm_crmentity.setype="Image 999"
			and aicrm_jobs.jobid=?';
			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
			$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');

			$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
			$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));

			$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
					$sides=8;

					$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

				for($image_iter=0;$image_iter < count($image_array);$image_iter++)
							{
							$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
					$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
			{
			list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
			if($pro_image_width  > 450 ||  $pro_image_height > 300)
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
				else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
			}else{
				$label_fld[] ='';
			}
	
		}else{

			$query="select aicrm_attachments.*,aicrm_crmentity.setype from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid where aicrm_crmentity.setype='Image 999' and aicrm_seattachmentsrel.crmid=?";
			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
			$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');

			$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
			$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));

			$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
					$sides=8;

					$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

				for($image_iter=0;$image_iter < count($image_array);$image_iter++)
							{
							$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
					$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
			{
			list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
			if($pro_image_width  > 450 ||  $pro_image_height > 300)
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
				else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
			}else
			{
				$label_fld[] ='';
			}

		}
	
	}
	elseif($uitype == 16) {
		$label_fld[] = getTranslatedString($fieldlabel, $module);
		$label_fld[] = $col_fields[$fieldname];

		$fieldname = $adb->sql_escape_string($fieldname);
		$pick_query="select $fieldname from aicrm_$fieldname order by sortorderid";
		$params = array();
		$pickListResult = $adb->pquery($pick_query, $params);
		$noofpickrows = $adb->num_rows($pickListResult);

		$options = array();
		$count=0;
		$found = false;
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$pickListValue=decode_html($adb->query_result($pickListResult,$j,strtolower($fieldname)));
			$col_fields[$fieldname] = decode_html($col_fields[$fieldname]);

			if($col_fields[$fieldname] == $pickListValue)
			{
				$chk_val = "selected";
				$count++;
				$found = true;
			}
			else
			{
				$chk_val = '';
			}
			$pickListValue = to_html($pickListValue);
			$options[] = array(getTranslatedString($pickListValue),$pickListValue,$chk_val );
		}

		$label_fld ["options"] = $options;
	}
	elseif($uitype == 15)
	{
		if($fieldname=="email_from_name"){
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
			$label_fld ["options"] = $options;
		}else if($fieldname=="email_reply_email"){
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
			$label_fld ["options"] = $options;
        }else if($fieldname=="email_server"){
            $label_fld[] = getTranslatedString($fieldlabel, $module);
            $label_fld[] = $col_fields[$fieldname];
            $sql = "select id,concat(email_from_name,':',email_server) as from_name from aicrm_config_sendemail  
			where email_status='Active' and deleted=0 
			and email_type='edm'
			order by email_from_name ";
            $pickListResult = $adb->pquery($sql, array());
            $noofpickrows = $adb->num_rows($pickListResult);
            $options = array();
            $found = false;
            $options[] =array("--None--","--None--","" );
            for($i=0;$i<$noofpickrows;$i++){

                $pickListValue=$adb->query_result($pickListResult,$i,'from_name');

                $pickListId=$adb->query_result($pickListResult,$i,'id');
                //echo $col_fields[$fieldname]."=>".$pickListId."<br>";
                if($col_fields[$fieldname] == $pickListId){
                    $chk_val = "selected";
                    $found = true;
                }else{
                    $chk_val = '';
                }
                $options[] =array(getTranslatedString($pickListValue),$pickListId,$chk_val);
            }
            //print_r($options);
            $label_fld ["options"] = $options;
        }else if($fieldname=="email_bounce"){
            $label_fld[] = getTranslatedString($fieldlabel, $module);
            $label_fld[] = $col_fields[$fieldname];
            $sql = "select id,concat(email_from_name,':',email_server) as name from aicrm_config_sendemail  
			where email_status='Active' and deleted=0 
			and email_type='bounce'
			order by email_from_name ";
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
                $options[] =array(getTranslatedString($pickListValue),$pickListId,$chk_val);
            }

            $label_fld ["options"] = $options;
		}else if($fieldname=="sms_sender_name"){
            $label_fld[] = getTranslatedString($fieldlabel, $module);
            $label_fld[] = $col_fields[$fieldname];
            $sql = "select id,name,sms_sender , sms_url FROM aicrm_config_sender_sms WHERE 1 and sms_status='Active' and deleted=0 ";
            $pickListResult = $adb->pquery($sql, array());
            $noofpickrows = $adb->num_rows($pickListResult);
            $options = array();
            $found = false;
            $options[] =array("--None--","--None--","" );
            for($i=0;$i<$noofpickrows;$i++){

                $pickListValue=$adb->query_result($pickListResult,$i,'sms_sender');
                $pickListId=$adb->query_result($pickListResult,$i,'id');

                //echo $col_fields[$fieldname]."=>".$pickListId."<br>";
                //echo $col_fields[$fieldname]."=>".$pickListId."=>".$pickListValue."<br>";
                if($col_fields[$fieldname] == $pickListId){
                    $chk_val = "selected";
                    $found = true;
                }else{
                    $chk_val = '';
                }
                $options[] =array(getTranslatedString($pickListValue),$pickListId,$chk_val);
            }
            //echo "<pre>";print_r($options);echo "</pre>";
            $label_fld ["options"] = $options;
		}elseif($fieldname=="vender"){
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $col_fields[$fieldname];
			$sql = "select * from aicrm_config_vendorbuyer where type='Vender' and deleted = 0";
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
			$sql = "select * from aicrm_config_vendorbuyer where type='Buyer' and deleted = 0";
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
			$sql = "select * from aicrm_config_vendorbuyer where type='Buyer' and deleted = 0";
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
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $col_fields[$fieldname];
			$roleid=$current_user->roleid;
			$subrole = getRoleSubordinates($roleid);
			if(count($subrole)> 0)
			{
				$roleids = $subrole;
				array_push($roleids, $roleid);
			}
			else
			{
				$roleids = $roleid;
			}
			
			$sql = "select picklistid from aicrm_picklist where name = ?";
			$result = $adb->pquery($sql, array($fieldname));
			$picklistid = $adb->query_result($result, 0, "picklistid");
			
			if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
			{
				//$pick_query="select distinct $fieldname from aicrm_$fieldname inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_$fieldname.picklist_valueid where aicrm_role2picklist.picklistid in (select picklistid from aicrm_picklist) order by $fieldname asc";
				//$params = array();

				$pick_query="select distinct $fieldname from aicrm_$fieldname inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_$fieldname.picklist_valueid  and roleid in (". generateQuestionMarks($roleids) .") where aicrm_role2picklist.picklistid = $picklistid order by $fieldname asc";
				$params = array($roleids);
			}
			else
			{
				if (count($roleids) > 0) {
					//$pick_query="select distinct $fieldname from aicrm_$fieldname inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_$fieldname.picklist_valueid where roleid in (". generateQuestionMarks($roleids) .") and picklistid in (select picklistid from aicrm_picklist) order by $fieldname asc";
					$pick_query="select distinct $fieldname from aicrm_$fieldname inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_$fieldname.picklist_valueid where roleid in (". generateQuestionMarks($roleids) .") and aicrm_role2picklist.picklistid = $picklistid order by $fieldname asc";
					$params = array($roleids);
				} else {
					//$pick_query="select distinct $fieldname from aicrm_$fieldname inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_$fieldname.picklist_valueid where picklistid in (select picklistid from aicrm_picklist) order by $fieldname asc";
					$pick_query="select distinct $fieldname from aicrm_$fieldname inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_$fieldname.picklist_valueid where aicrm_role2picklist.picklistid = $picklistid order by $fieldname asc";
					$params = array();
				}
			}

			$pickListResult = $adb->pquery($pick_query, $params);
			$noofpickrows = $adb->num_rows($pickListResult);

			//Mikecrowe fix to correctly default for custom pick lists
			$options = array();
			$count=0;
			$found = false;
			for($j = 0; $j < $noofpickrows; $j++)
			{
				if($fieldname=="taskstatus"){

					$pickListValue=decode_html($adb->query_result($pickListResult,$j,strtolower($fieldname)));
					$col_fields["eventstatus"] = decode_html($col_fields["eventstatus"]);
					
					if($col_fields["eventstatus"] == $pickListValue)
					{
						$chk_val = "selected";
						$count++;
						$found = true;
					}
					else
					{
						$chk_val = '';
					}

				}else{
					$pickListValue=decode_html($adb->query_result($pickListResult,$j,strtolower($fieldname)));
					$col_fields[$fieldname] = decode_html($col_fields[$fieldname]);

					if($col_fields[$fieldname] == $pickListValue)
					{
						$chk_val = "selected";
						$count++;
						$found = true;
					}
					else
					{
						$chk_val = '';
					}
				}
				$pickListValue = to_html($pickListValue);
				$options[] = array(getTranslatedString($pickListValue),$pickListValue,$chk_val );
			}
			if($count == 0 && $col_fields[$fieldname] != '')
			{
				$options[] =  array($app_strings['LBL_NOT_ACCESSIBLE'],$col_fields[$fieldname],'selected');
			}
			$label_fld ["options"] = $options;
		}
	}
	elseif($uitype == 115)
	{
		$label_fld[] = getTranslatedString($fieldlabel, $module);
		$label_fld[] = getTranslatedString($col_fields[$fieldname]);

		$pick_query="select * from aicrm_" . $adb->sql_escape_string($fieldname);
		$pickListResult = $adb->pquery($pick_query, array());
		$noofpickrows = $adb->num_rows($pickListResult);
		$options = array();
		$found = false;
		for($j = 0; $j < $noofpickrows; $j++)
		{
			$pickListValue=$adb->query_result($pickListResult,$j,strtolower($fieldname));

			if($col_fields[$fieldname] == $pickListValue)
			{
				$chk_val = "selected";
				$found = true;
			}
			else
			{
				$chk_val = '';
			}
			$options[] = array($pickListValue=>$chk_val );
		}
		$label_fld ["options"] = $options;
	}
	elseif($uitype == 33) //uitype 33 added for multiselector picklist - Jeri
	{	
		
		if($fieldname=="approve_level1" || $fieldname=="approve_level2" || $fieldname=="approve_level3" || $fieldname=="approve_level4"){
			$roleid=$current_user->roleid;
			$section=$current_user->column_fields["section"];

			$subrole = getRoleSubordinates($roleid);
			if(count($subrole)> 0)
			{
				$roleids = $subrole;
				array_push($roleids, $roleid);
			}
			else
			{
				$roleids = $roleid;
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module);

			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = str_ireplace(' |##| ',', ',$col_fields[$fieldname]);

			$sql = "select  a.user_name, concat( first_name, ' ', last_name ) as  ".$fieldname."
					from aicrm_users as a
					where a.deleted=0
					";
			if($is_admin==false){
				$sql.="and a.section = '".$section."'";
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
			
			$pickListResult = $adb->pquery($sql,"");
			$noofpickrows = $adb->num_rows($pickListResult);

			$options = array();
			$selected_entries = Array();
			$selected_entries = explode(' |##| ',$col_fields[$fieldname]);
			for($j = 0; $j < $noofpickrows; $j++)
			{
				$pickListValue = $adb->query_result($pickListResult,$j,strtolower($fieldname));
				$chk_val = '';
				foreach($selected_entries as $selected_entries_value)
				{
					if(trim($selected_entries_value) == trim($pickListValue))
					{
						$chk_val = 'selected';
						break;
					}
					else
					{
						$chk_val = '';
					}
				}
				$options[] = array($pickListValue,$pickListValue,$chk_val);
			}
			foreach($selected_entries as $selected_entries_value)
			{
				$mul_count =0;
				$options_length = count($options);
				for($j=0;$j<$options_length;$j++)
				{
					if(in_array($selected_entries_value,$options[$j]))
					{
						$mul_count++;
					}

				}
				if($mul_count == 0 && $options_length > 0)
				{
					$not_access_lbl = "<font color='red'>".$app_strings['LBL_NOT_ACCESSIBLE']."</font>";
					$options[]=array($not_access_lbl,trim($selected_entries_value),'selected');
				}
				$mul_count=0;
			}
			$label_fld ["options"] = $options;

		}else if(($fieldname=="approver" || $fieldname=="approver2" || $fieldname=="approver3" || $fieldname=="f_approver") && ($module == 'Expense')){
			$roleid=$current_user->roleid;
			$section=$current_user->column_fields["section"];

			$subrole = getRoleSubordinates($roleid);
			if(count($subrole)> 0)
			{
				$roleids = $subrole;
				array_push($roleids, $roleid);
			}
			else
			{
				$roleids = $roleid;
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module);

			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = str_ireplace(' |##| ',', ',$col_fields[$fieldname]);

			$sql = "select  a.user_name, concat( first_name, ' ', last_name ) as  ".$fieldname."
					from aicrm_users as a
					where a.deleted=0
					";
			if($is_admin==false){
				$sql.="and a.section = '".$section."'";
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
			
			$pickListResult = $adb->pquery($sql,"");
			$noofpickrows = $adb->num_rows($pickListResult);

			$options = array();
			$selected_entries = Array();
			$selected_entries = explode(' |##| ',$col_fields[$fieldname]);
			for($j = 0; $j < $noofpickrows; $j++)
			{
				$pickListValue = $adb->query_result($pickListResult,$j,strtolower($fieldname));
				$chk_val = '';
				foreach($selected_entries as $selected_entries_value)
				{
					if(trim($selected_entries_value) == trim($pickListValue))
					{
						$chk_val = 'selected';
						break;
					}
					else
					{
						$chk_val = '';
					}
				}
				$options[] = array($pickListValue,$pickListValue,$chk_val);
			}
			foreach($selected_entries as $selected_entries_value)
			{
				$mul_count =0;
				$options_length = count($options);
				for($j=0;$j<$options_length;$j++)
				{
					if(in_array($selected_entries_value,$options[$j]))
					{
						$mul_count++;
					}

				}
				if($mul_count == 0 && $options_length > 0)
				{
					$not_access_lbl = "<font color='red'>".$app_strings['LBL_NOT_ACCESSIBLE']."</font>";
					$options[]=array($not_access_lbl,trim($selected_entries_value),'selected');
				}
				$mul_count=0;
			}
			$label_fld ["options"] = $options;
		}else if(($fieldname=="approver" || $fieldname=="approver2" || $fieldname=="approver3" || $fieldname=="f_approver") && ($module == 'Samplerequisition')){
			$roleid=$current_user->roleid;
			$section=$current_user->column_fields["section"];

			$subrole = getRoleSubordinates($roleid);
			if(count($subrole)> 0)
			{
				$roleids = $subrole;
				array_push($roleids, $roleid);
			}
			else
			{
				$roleids = $roleid;
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module);

			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = str_ireplace(' |##| ',', ',$col_fields[$fieldname]);

			$sql = "select  a.user_name, concat( first_name, ' ', last_name ) as  ".$fieldname."
					from aicrm_users as a
					where a.deleted=0
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
			
			$pickListResult = $adb->pquery($sql,"");
			$noofpickrows = $adb->num_rows($pickListResult);

			$options = array();
			$selected_entries = Array();
			$selected_entries = explode(' |##| ',$col_fields[$fieldname]);
			for($j = 0; $j < $noofpickrows; $j++)
			{
				$pickListValue = $adb->query_result($pickListResult,$j,strtolower($fieldname));
				$chk_val = '';
				foreach($selected_entries as $selected_entries_value)
				{
					if(trim($selected_entries_value) == trim($pickListValue))
					{
						$chk_val = 'selected';
						break;
					}
					else
					{
						$chk_val = '';
					}
				}
				$options[] = array($pickListValue,$pickListValue,$chk_val);
			}
			foreach($selected_entries as $selected_entries_value)
			{
				$mul_count =0;
				$options_length = count($options);
				for($j=0;$j<$options_length;$j++)
				{
					if(in_array($selected_entries_value,$options[$j]))
					{
						$mul_count++;
					}

				}
				if($mul_count == 0 && $options_length > 0)
				{
					$not_access_lbl = "<font color='red'>".$app_strings['LBL_NOT_ACCESSIBLE']."</font>";
					$options[]=array($not_access_lbl,trim($selected_entries_value),'selected');
				}
				$mul_count=0;
			}
			$label_fld ["options"] = $options;
		}elseif($fieldname=="pjorder_employee"){
			$roleid=$current_user->roleid;
			$section=$current_user->column_fields["section"];

			$subrole = getRoleSubordinates($roleid);
			if(count($subrole)> 0)
			{
				$roleids = $subrole;
				array_push($roleids, $roleid);
			}
			else
			{
				$roleids = $roleid;
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module);

			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = str_ireplace(' |##| ',', ',$col_fields[$fieldname]);

			$sql = "select  a.user_name, concat(a.user_name,' [ ',first_name,' ', last_name ,' ] ') as ".$fieldname."
					from aicrm_users as a
					where a.deleted=0
					";
			
			$sql.=" order by a.user_name";
			
			$pickListResult = $adb->pquery($sql,"");
			$noofpickrows = $adb->num_rows($pickListResult);

			$options = array();
			$selected_entries = Array();
			$selected_entries = explode(' |##| ',$col_fields[$fieldname]);
			for($j = 0; $j < $noofpickrows; $j++)
			{
				$pickListValue = $adb->query_result($pickListResult,$j,strtolower($fieldname));
				$chk_val = '';
				foreach($selected_entries as $selected_entries_value)
				{
					if(trim($selected_entries_value) == trim($pickListValue))
					{
						$chk_val = 'selected';
						break;
					}
					else
					{
						$chk_val = '';
					}
				}
				$options[] = array($pickListValue,$pickListValue,$chk_val);
			}
			foreach($selected_entries as $selected_entries_value)
			{
				$mul_count =0;
				$options_length = count($options);
				for($j=0;$j<$options_length;$j++)
				{
					if(in_array($selected_entries_value,$options[$j]))
					{
						$mul_count++;
					}

				}
				if($mul_count == 0 && $options_length > 0)
				{
					$not_access_lbl = "<font color='red'>".$app_strings['LBL_NOT_ACCESSIBLE']."</font>";
					$options[]=array($not_access_lbl,trim($selected_entries_value),'selected');
				}
				$mul_count=0;
			}
			$label_fld ["options"] = $options;
		}elseif($fieldname=="related_sales_person"){
			$roleid=$current_user->roleid;
			$section=$current_user->column_fields["section"];

			$subrole = getRoleSubordinates($roleid);
			if(count($subrole)> 0)
			{
				$roleids = $subrole;
				array_push($roleids, $roleid);
			}
			else
			{
				$roleids = $roleid;
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module);

			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = str_ireplace(' |##| ',', ',$col_fields[$fieldname]);

			$sql = "select  a.user_name, concat(first_name,' ', last_name) as ".$fieldname."
					from aicrm_users as a
					where a.deleted=0
					";
			
			$sql.=" order by a.user_name";
			
			$pickListResult = $adb->pquery($sql,"");
			$noofpickrows = $adb->num_rows($pickListResult);

			$options = array();
			$selected_entries = Array();
			$selected_entries = explode(' |##| ',$col_fields[$fieldname]);
			for($j = 0; $j < $noofpickrows; $j++)
			{
				$pickListValue = $adb->query_result($pickListResult,$j,strtolower($fieldname));
				$chk_val = '';
				foreach($selected_entries as $selected_entries_value)
				{
					if(trim($selected_entries_value) == trim($pickListValue))
					{
						$chk_val = 'selected';
						break;
					}
					else
					{
						$chk_val = '';
					}
				}
				$options[] = array($pickListValue,$pickListValue,$chk_val);
			}
			foreach($selected_entries as $selected_entries_value)
			{
				$mul_count =0;
				$options_length = count($options);
				for($j=0;$j<$options_length;$j++)
				{
					if(in_array($selected_entries_value,$options[$j]))
					{
						$mul_count++;
					}

				}
				if($mul_count == 0 && $options_length > 0)
				{
					$not_access_lbl = "<font color='red'>".$app_strings['LBL_NOT_ACCESSIBLE']."</font>";
					$options[]=array($not_access_lbl,trim($selected_entries_value),'selected');
				}
				$mul_count=0;
			}
			$label_fld ["options"] = $options;
		
		}else{
			$roleid=$current_user->roleid;
			$subrole = getRoleSubordinates($roleid);
			if(count($subrole)> 0)
			{
				$roleids = $subrole;
				array_push($roleids, $roleid);
			}
			else
			{
				$roleids = $roleid;
			}
			$editview_label[]=getTranslatedString($fieldlabel, $module);
			if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
			{
				$pick_query="select $fieldname from aicrm_$fieldname order by $fieldname asc";
				$params = array();
			}else
			{
				if (count($roleids) > 0) {
					$pick_query="select distinct $fieldname from aicrm_$fieldname inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_$fieldname.picklist_valueid where roleid in (". generateQuestionMarks($roleids) .") and picklistid in (select picklistid from aicrm_picklist) order by $fieldname asc";
					$params = array($roleids);
				} else {
					$pick_query="select distinct $fieldname from aicrm_$fieldname inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_$fieldname.picklist_valueid where picklistid in (select picklistid from aicrm_picklist) order by $fieldname asc";
					$params = array();
				}
			}
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = str_ireplace(' |##| ',', ',$col_fields[$fieldname]);

			$pickListResult = $adb->pquery($pick_query, $params);
			$noofpickrows = $adb->num_rows($pickListResult);

			$options = array();
			$selected_entries = Array();
			$selected_entries = explode(' |##| ',$col_fields[$fieldname]);
			for($j = 0; $j < $noofpickrows; $j++)
			{
				$pickListValue = $adb->query_result($pickListResult,$j,strtolower($fieldname));
				$chk_val = '';
				foreach($selected_entries as $selected_entries_value)
				{
					if(trim($selected_entries_value) == trim($pickListValue))
					{
						$chk_val = 'selected';
						break;
					}
					else
					{
						$chk_val = '';
					}
				}
				$options[] = array($pickListValue,$pickListValue,$chk_val);
			}
			foreach($selected_entries as $selected_entries_value)
			{
				$mul_count =0;
				$options_length = count($options);
				for($j=0;$j<$options_length;$j++)
				{
					if(in_array($selected_entries_value,$options[$j]))
					{
						$mul_count++;
					}

				}
				if($mul_count == 0 && $options_length > 0)
				{
					$not_access_lbl = "<font color='red'>".$app_strings['LBL_NOT_ACCESSIBLE']."</font>";
					$options[]=array($not_access_lbl,trim($selected_entries_value),'selected');
				}
				$mul_count=0;
			}
			$label_fld ["options"] = $options;
		}
	}
	elseif($uitype == 17)
	{
		$label_fld[] = getTranslatedString($fieldlabel, $module);
		$label_fld[] = $col_fields[$fieldname];
	}
	
	elseif($uitype == 19)
	{
		if( $fieldname == 'promotion_detail' || $fieldname == 'detail' ||  $fieldname == 'notecontent' || $fieldname == 'know_detail' || $fieldname == 'know_detail_en' || $fieldname == 'camp_detail'|| $fieldname == 'email_message'|| ($module=="Products" && $fieldname == 'description'))
			$col_fields[$fieldname]= decode_html($col_fields[$fieldname]);
		else
			$col_fields[$fieldname]= str_replace("&lt;br /&gt;","<br>",$col_fields[$fieldname]);

		$label_fld[] = getTranslatedString($fieldlabel, $module);
		$label_fld[] = $col_fields[$fieldname];
	}

	elseif($uitype == 20 || $uitype == 21 || $uitype == 22 || $uitype == 24) 
	// Armando LC<scher 11.08.2005 -> B'descriptionSpan -> Desc: removed $uitype == 19 and made an aditional elseif above
	{
		if($uitype == 20)//Fix the issue #4680
	        $col_fields[$fieldname]=$col_fields[$fieldname];
	    else
	        $col_fields[$fieldname]=nl2br($col_fields[$fieldname]);
		
		$label_fld[] = getTranslatedString($fieldlabel, $module);
		$label_fld[] = $col_fields[$fieldname];
	}
	elseif($uitype == 51 || $uitype == 50 || $uitype == 73)
	{
		$account_id = $col_fields[$fieldname];
		if($account_id != '')
		{
			$account_name = getAccountName($account_id);
		}
		$label_fld[] = getTranslatedString($fieldlabel, $module);
		
        $label_fld[] = $account_name;
	    $label_fld["secid"] = $account_id;
        $label_fld["link"] = "index.php?module=Accounts&action=DetailView&record=".$account_id;

	}

	elseif($uitype == 52 || $uitype == 77  || $uitype == 101)
	{
		$label_fld[] = getTranslatedString($fieldlabel, $module);
		$user_id = $col_fields[$fieldname];
		$user_name = getUserName($user_id);
		$assigned_user_id = $current_user->id;
		if(is_admin($current_user))
		{
			$label_fld[] ='<a href="index.php?module=Users&action=DetailView&record='.$user_id.'">'.$user_name.'</a>';
		}
		else
		{
			$label_fld[] =$user_name;
		}
		if($is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid($module)] == 3 or $defaultOrgSharingPermission[getTabid($module)] == 0))
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $assigned_user_id,'private'), $assigned_user_id);
		}
		else
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $user_id), $assigned_user_id);
		}
		$label_fld ["options"] = $users_combo;

	}
	elseif($uitype == 11){

		$label_fld[] = getTranslatedString($fieldlabel, $module);
		$label_fld[] = $col_fields[$fieldname];
	}

	elseif($uitype == 53)
	{
		global $noof_group_rows, $adb;
		$owner_id = $col_fields[$fieldname];

		$user = 'no';
		$result = $adb->pquery("SELECT count(*) as count from aicrm_users where id = ?",array($owner_id));
		if($adb->query_result($result,0,'count') > 0) {
			$user = 'yes';
		}

		$owner_name = getOwnerName($owner_id);
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$label_fld[] =$owner_name;

		if(is_admin($current_user))
		{
			$label_fld["secid"][] = $owner_id;
			if($user == 'no') {
				$label_fld["link"][] = "index.php?module=Settings&action=GroupDetailView&groupId=".$owner_id;
			}
			else {
				$label_fld["link"][] = "index.php?module=Users&action=DetailView&record=".$owner_id;
			}
		}

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


		global $current_user;
		if($owner_id != '') {
			if($user == 'yes') {
				$label_fld ["options"][] = 'User';
				$assigned_user_id = $owner_id;
				$user_checked = "checked";
				$team_checked = '';
				$user_style='display:block';
				$team_style='display:none';
			}else{
				$label_fld ["options"][] = 'Group';
				$assigned_group_id = $owner_id;
				$user_checked = '';
				$team_checked = 'checked';
				$user_style='display:none';
				$team_style='display:block';
			}

		}else{
			$label_fld ["options"][] = 'User';
			$assigned_user_id = $current_user->id;
			$user_checked = "checked";
			$team_checked = '';
			$user_style='display:block';
			$team_style='display:none';
		}

		if($fieldlabel == 'Assigned To' && $is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid($module)] == 3 or $defaultOrgSharingPermission[getTabid($module)] == 0))
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $current_user->id,'private'), $current_user->id);
		}
		else
		{
			$users_combo = get_select_options_array(get_user_array(FALSE, "Active", $current_user->id), $current_user->id);
		}

		if($noof_group_rows!=0)
		{
			if($fieldlabel == 'Assigned To' && $is_admin==false && $profileGlobalPermission[2] == 1 && ($defaultOrgSharingPermission[getTabid($module)] == 3 or $defaultOrgSharingPermission[getTabid($module)] == 0))
			{
				$groups_combo = get_select_options_array(get_group_array(FALSE, "Active", $current_user->id,'private'), $current_user->id);
			}
			else
			{
				$groups_combo = get_select_options_array(get_group_array(FALSE, "Active", $current_user->id), $current_user->id);
			}
		}

		$label_fld ["options"][] = $users_combo;
		$label_fld ["options"][] = $groups_combo;
	}
	elseif($uitype == 55 || $uitype == 255)
        {
			if($tabid == 4)
            {
                   /*$query="select aicrm_contactdetails.imagename from aicrm_contactdetails where contactid=?";
                   $result = $adb->pquery($query, array($col_fields['record_id']));
                   $imagename=$adb->query_result($result,0,'imagename');*/
                   /*if($imagename != '')
                   {
                           $imgpath = "test/contact/".$imagename;
                           $label_fld[] =getTranslatedString($fieldlabel, $module);
                   }
                   else
                   {*/
                         $label_fld[] =getTranslatedString($fieldlabel, $module);
                   /*}*/

            }
            else
            {
                   $label_fld[] =getTranslatedString($fieldlabel, $module);
            }
		$value = $col_fields[$fieldname];
		if($uitype==255)
		{
			global $currentModule;
			$fieldpermission = getFieldVisibilityPermission($currentModule, $current_user->id,'firstname');
		}
		if($uitype == 255 && $fieldpermission == 0 && $fieldpermission != '')
		{
			$fieldvalue[] = '';
		}
		else
		{
			$roleid=$current_user->roleid;
			$subrole = getRoleSubordinates($roleid);
			if(count($subrole)> 0)
			{
				$roleids = implode("','",$subrole);
				$roleids = $roleids."','".$roleid;
			}
			else
			{
				$roleids = $roleid;
			}
			if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
			{
				$pick_query="select salutation from aicrm_salutation order by salutation";
				$params = array();
			}
			else
			{
				$pick_query="select * from aicrm_salutation left join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid=aicrm_salutation.picklist_valueid where picklistid in (select picklistid from aicrm_picklist where name='salutation') and roleid=? order by salutation";
				$params = array($current_user->roleid);
			}
			//echo "<pre>"; print_r($col_fields); echo "</pre>"; exit;
			//echo $pick_query;
			//print_r($current_user->roleid); exit;
			$pickListResult = $adb->pquery($pick_query, $params);
			$noofpickrows = $adb->num_rows($pickListResult);
			$sal_value = $col_fields["salutationtype"];
			//echo $sal_value; exit;
			$salcount =0;
			for($j = 0; $j < $noofpickrows; $j++)
			{
				$pickListValue=$adb->query_result($pickListResult,$j,"salutation");

				if($sal_value == $pickListValue)
				{
					$chk_val = "selected";
					$salcount++;
				}
				else
				{
					$chk_val = '';
				}
			}
			if($salcount == 0 && $sal_value != '')
			{
				$notacc =  $app_strings['LBL_NOT_ACCESSIBLE'];
			}
           		$sal_value = $col_fields["salutationtype"];
           		if($sal_value == '--None--')
           		{
                   		$sal_value='';
	   		}
	   		$label_fld["salut"] = getTranslatedString($sal_value);
           	$label_fld["notaccess"] = $notacc;
	   	}
		$label_fld[] = $value;
        
        }
	elseif($uitype == 56)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$value = $col_fields[$fieldname];
		if($value == 1)
		{
			//Since "yes" is not been translated it is given as app strings here..
			$display_val = $app_strings['yes'];
		}
		else
		{
			$display_val = $app_strings['no'];
		}
		$label_fld[] = $display_val;
	}
	elseif($uitype == 57)
        {
		 $label_fld[] =getTranslatedString($fieldlabel, $module);
           $contact_id = $col_fields[$fieldname];
           if($contact_id != '')
           {
                   $contact_name = getContactName($contact_id);
           }
          $label_fld[] = $contact_name;
		$label_fld["secid"] = $contact_id;
		$label_fld["link"]= "index.php?module=Contacts&action=DetailView&record=".$contact_id;
        }
	elseif($uitype == 58)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$campaign_id = $col_fields[$fieldname];
		if($campaign_id != '')
		{
			$campaign_name = getCampaignName($campaign_id);
		}
		$label_fld[] = $campaign_name;
		$label_fld["secid"] = $campaign_id;
		$label_fld["link"] = "index.php?module=Campaigns&action=DetailView&record=".$campaign_id;

	}
	elseif($uitype == 59)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$product_id = $col_fields[$fieldname];
		if($product_id != '')
		{
			$product_name = getProductName($product_id);
		}
		//Account Name View
		$label_fld[] = $product_name;
		$label_fld["secid"] = $product_id;
		$label_fld["link"] = "index.php?module=Products&action=DetailView&record=".$product_id;


	}
    elseif($uitype == 61)
	{
			global $adb;
			$label_fld[] =getTranslatedString($fieldlabel, $module);

			if($tabid ==10)
			{
				$attach_result = $adb->pquery("select * from aicrm_seattachmentsrel where crmid = ?", array($col_fields['record_id']));
				for($ii=0;$ii < $adb->num_rows($attach_result);$ii++)
				{
					$attachmentid = $adb->query_result($attach_result,$ii,'attachmentsid');
					if($attachmentid != '')
					{
						$attachquery = "select * from aicrm_attachments where attachmentsid=?";
						$attachmentsname = $adb->query_result($adb->pquery($attachquery, array($attachmentid)),0,'name');
						if($attachmentsname != '')
							$custfldval = '<a href = "index.php?module=uploads&action=downloadfile&return_module='.$col_fields['record_module'].'&fileid='.$attachmentid.'&entityid='.$col_fields['record_id'].'">'.$attachmentsname.'</a>';
						else
							$custfldval = '';
					}
					$label_fld['options'][] = $custfldval;
				}
			}
	  else
		{
			$attachmentid=$adb->query_result($adb->pquery("select * from aicrm_seattachmentsrel where crmid = ?", array($col_fields['record_id'])),0,'attachmentsid');
			if($col_fields[$fieldname] == '' && $attachmentid != '')
			{
				$attachquery = "select * from aicrm_attachments where attachmentsid=?";
				$col_fields[$fieldname] = $adb->query_result($adb->pquery($attachquery, array($attachmentid)),0,'name');
			}

			//This is added to strip the crmid and _ from the file name and show the original filename
			//$org_filename = ltrim($col_fields[$fieldname],$col_fields['record_id'].'_');
			/*Above line is not required as the filename in the database is stored as it is and doesn't have crmid attached to it.
			This was the cause for the issue reported in ticket #4645 */
			$org_filename = $col_fields[$fieldname];
        	// For Backward Compatibility version < 5.0.4
        	$filename_pos = strpos($org_filename, $col_fields['record_id'].'_');
       		if ($filename_pos === 0) {
				$start_idx = $filename_pos+strlen($col_fields['record_id'].'_');
				$org_filename = substr($org_filename, $start_idx);
            }
			if($org_filename != '') {
				if($col_fields['filelocationtype'] == 'E' ){
					if($col_fields['filestatus'] == 1 ){//&& strlen($col_fields['filename']) > 7  ){
					$custfldval = '<a target="_blank" href ='.$col_fields['filename'].' onclick=\'javascript:dldCntIncrease('.$col_fields['record_id'].');\'>'.$col_fields[$fieldname].'</a>';
					}
					else{
						$custfldval = $col_fields[$fieldname];
					}
				}elseif($col_fields['filelocationtype'] == 'I') {
					if($col_fields['filestatus'] == 1){
					$custfldval = '<a href = "index.php?module=uploads&action=downloadfile&return_module='.$col_fields['record_module'].'&fileid='.$attachmentid.'&entityid='.$col_fields['record_id'].'" onclick=\'javascript:dldCntIncrease('.$col_fields['record_id'].');\'>'.$col_fields[$fieldname].'</a>';
					}
					else{
						$custfldval = $col_fields[$fieldname];
					}
			} else
				$custfldval = '';
		}
		$label_fld[] =$custfldval;
	}
	}
	elseif($uitype==28){
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$attachmentid=$adb->query_result($adb->pquery("select * from aicrm_seattachmentsrel where crmid = ?", array($col_fields['record_id'])),0,'attachmentsid');
		if($col_fields[$fieldname] == '' && $attachmentid != '')
		{
			$attachquery = "select * from aicrm_attachments where attachmentsid=?";
			$col_fields[$fieldname] = $adb->query_result($adb->pquery($attachquery, array($attachmentid)),0,'name');
		}
		$org_filename = $col_fields[$fieldname];
    	// For Backward Compatibility version < 5.0.4
    	$filename_pos = strpos($org_filename, $col_fields['record_id'].'_');
   		if ($filename_pos === 0) {
			$start_idx = $filename_pos+strlen($col_fields['record_id'].'_');
			$org_filename = substr($org_filename, $start_idx);
        }
		if($org_filename != '') {
			if($col_fields['filelocationtype'] == 'E' ){
					if($col_fields['filestatus'] == 1 ){//&& strlen($col_fields['filename']) > 7  ){
					$custfldval = '<a target="_blank" href ='.$col_fields['filename'].' onclick=\'javascript:dldCntIncrease('.$col_fields['record_id'].');\'>'.$col_fields[$fieldname].'</a>';
					}
					else{
						$custfldval = $col_fields[$fieldname];

					}
				}elseif($col_fields['filelocationtype'] == 'I') {
					if($col_fields['filestatus'] == 1){
					$custfldval = '<a href = "index.php?module=uploads&action=downloadfile&return_module='.$col_fields['record_module'].'&fileid='.$attachmentid.'&entityid='.$col_fields['record_id'].'" onclick=\'javascript:dldCntIncrease('.$col_fields['record_id'].');\'>'.$col_fields[$fieldname].'</a>';
					}
					else{
						$custfldval = $col_fields[$fieldname];
					}
			} else
				$custfldval = '';
		}
	$label_fld[] =$custfldval;
	}
	elseif($uitype == 69)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);

		if($tabid==4)
		{
			$sql = "select aicrm_attachments.*,aicrm_crmentity.setype from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_attachments.attachmentsid where aicrm_crmentity.setype='Contacts Image' and aicrm_seattachmentsrel.crmid=?";
			$image_res = $adb->pquery($sql, array($col_fields['record_id']));
			$image_id = $adb->query_result($image_res,0,'attachmentsid');
			$image_path = $adb->query_result($image_res,0,'path');

			//decode_html  - added to handle UTF-8   characters in file names
			//urlencode    - added to handle special characters like #, %, etc.,
			$image_name = urlencode(decode_html($adb->query_result($image_res,0,'name')));

			$imgpath = $image_path.$image_id."_".$image_name;
			if($image_name != '')
				$label_fld[] ='<img src="'.$imgpath.'" alt="'.$mod_strings['Contact Image'].'" title= "'.$mod_strings['Contact Image'].'" style="width:450px;height:300px;">';
			else
				$label_fld[] = '';
		}
		if($tabid==6)
		{
			$sql = "select aicrm_attachments.*,aicrm_crmentity.setype from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_attachments.attachmentsid where aicrm_crmentity.setype='Accounts Image' and aicrm_seattachmentsrel.crmid=?";
			$image_res = $adb->pquery($sql, array($col_fields['record_id']));
			$image_id = $adb->query_result($image_res,0,'attachmentsid');
			$image_path = $adb->query_result($image_res,0,'path');

			//decode_html  - added to handle UTF-8   characters in file names
			//urlencode    - added to handle special characters like #, %, etc.,
			$image_name = urlencode(decode_html($adb->query_result($image_res,0,'name')));

			$imgpath = $image_path.$image_id."_".$image_name;
			if($image_name != '')
				$label_fld[] ='<img src="'.$imgpath.'" alt="'.$mod_strings['Contact Image'].'" title= "'.$mod_strings['Contact Image'].'" style="width:450px;height:300px;">';
			else
				$label_fld[] = '';
		}
		if($tabid==7)
		{
			$images=array();
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

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
			$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');

			//decode_html  - added to handle UTF-8   characters in file names
			//urlencode    - added to handle special characters like #, %, etc.,
			$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
			$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));

			$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
				$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

					for($image_iter=0;$image_iter < count($image_array);$image_iter++){
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
			$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
					{
					list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
					if($pro_image_width  > 450 ||  $pro_image_height > 300)
						$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
						else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
					}else
					{
				$label_fld[] ='';
					}
		}
		if($tabid==11)
		{
			$images=array();
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

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
				$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
				//decode_html  - added to handle UTF-8   characters in file names
				//urlencode    - added to handle special characters like #, %, etc.,
				$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
				$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));
				$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
				$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

					for($image_iter=0;$image_iter < count($image_array);$image_iter++){
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
			$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
					{
					list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
					if($pro_image_width  > 450 ||  $pro_image_height > 300)
						$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
						else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
					}else
					{
				$label_fld[] ='';
			}
		}
		if($tabid==13)
		{
			$images=array();
			$query = 'select
			aicrm_attachments.path,
			aicrm_attachments.attachmentsid,
			aicrm_attachments.name ,
			aicrm_crmentity.setype
			from  aicrm_troubletickets
			left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_troubletickets.ticketid
			inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
			where aicrm_crmentity.setype="HelpDesk Image"
			and aicrm_troubletickets.ticketid=?';

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
				$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
				//decode_html  - added to handle UTF-8   characters in file names
				//urlencode    - added to handle special characters like #, %, etc.,
				$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
				$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));

				$image_array_name[] = $adb->query_result($result_image,$image_iter,'name');
				
				$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}

			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
				$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

					for($image_iter=0;$image_iter < count($image_array);$image_iter++){
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
					$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
					$label_fld[] =$image_lists;

			}elseif(count($image_array)==1){

				list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
				if($pro_image_width  > 450 ||  $pro_image_height > 300){
					$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
				}else{
					/*$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';*/
					//$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
					
					$label_fld[] = '<a href="'.$imagepath_array[0].$image_array_name[0].'">'.$image_array_name[0].'</a>';
					//$label_fld[] = $image_array[0];
				}
			
			}else{
				$label_fld[] ='';
			}
		}
		if($tabid==14)
		{
			$images=array();
			$query = 'select productname, aicrm_attachments.path, aicrm_attachments.attachmentsid, aicrm_attachments.name,aicrm_crmentity.setype from aicrm_products left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_products.productid inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_attachments.attachmentsid where aicrm_crmentity.setype="Products Image" and productid=?';
			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
				$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');

				//decode_html  - added to handle UTF-8   characters in file names
				//urlencode    - added to handle special characters like #, %, etc.,
				$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
				$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));

				$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
				if(count($image_array) < 4)
					$sides=count($image_array)*2;
				else
					$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

				for($image_iter=0;$image_iter < count($image_array);$image_iter++)
				{
					$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
				}
				$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
			{
				list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
				if($pro_image_width  > 450 ||  $pro_image_height > 300)
					$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
				else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
			}else
			{
				$label_fld[] ='';
			}
		}
		if($tabid==23)
		{
			$images=array();
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

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
			$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');

			//decode_html  - added to handle UTF-8   characters in file names
			//urlencode    - added to handle special characters like #, %, etc.,
			$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
			$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));

			$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
				$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

					for($image_iter=0;$image_iter < count($image_array);$image_iter++){
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
			$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
					{
					list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
					if($pro_image_width  > 450 ||  $pro_image_height > 300)
						$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
						else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
					}else
					{
				$label_fld[] ='';
					}
		}
       	if($tabid==26)
		{
			$images=array();
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

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
				$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
				//decode_html  - added to handle UTF-8   characters in file names
				//urlencode    - added to handle special characters like #, %, etc.,
				$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
				$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));
				$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
				$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

					for($image_iter=0;$image_iter < count($image_array);$image_iter++){
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
			$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
					{
					list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
					if($pro_image_width  > 450 ||  $pro_image_height > 300)
						$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
						else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
					}else
					{
				$label_fld[] ='';
			}
		}
		if($tabid==30)
		{
			$images=array();
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

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
				$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
				//decode_html  - added to handle UTF-8   characters in file names
				//urlencode    - added to handle special characters like #, %, etc.,
				$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
				$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));
				$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
				$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

					for($image_iter=0;$image_iter < count($image_array);$image_iter++){
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
			$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
					{
					list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
					if($pro_image_width  > 450 ||  $pro_image_height > 300)
						$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
						else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
					}else
					{
				$label_fld[] ='';
			}
		}
		if($tabid==31)
		{
			$images=array();
			$query = 'select
			aicrm_attachments.path,
			aicrm_attachments.attachmentsid,
			aicrm_attachments.name ,
			aicrm_crmentity.setype
			from  aicrm_knowledgebasecf
			left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_knowledgebasecf.knowledgebaseid
			inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
			where aicrm_crmentity.setype="KnowledgeBase Image"
			and knowledgebaseid=?';

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
			$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');

			//decode_html  - added to handle UTF-8   characters in file names
			//urlencode    - added to handle special characters like #, %, etc.,
			$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
			$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));

			$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
				$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

					for($image_iter=0;$image_iter < count($image_array);$image_iter++){
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
			$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
					{
					list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
					if($pro_image_width  > 450 ||  $pro_image_height > 300)
						$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
						else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
					}else
					{
				$label_fld[] ='';
					}
		}
		if($tabid==39)
		{
			$images=array();
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

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
				$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
				//decode_html  - added to handle UTF-8   characters in file names
				//urlencode    - added to handle special characters like #, %, etc.,
				$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
				$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));
				$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
				$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

					for($image_iter=0;$image_iter < count($image_array);$image_iter++){
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
			$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
					{
					list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
					if($pro_image_width  > 450 ||  $pro_image_height > 300)
						$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
						else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
					}else
					{
				$label_fld[] ='';
			}
		}
		/*if($tabid==41)
		{
			$images=array();
			$query = 'select
			aicrm_attachments.path,
			aicrm_attachments.attachmentsid,
			aicrm_attachments.name ,
			aicrm_crmentity.setype
			from aicrm_job
			left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_promotion.promotionid
			inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
			where aicrm_crmentity.setype="Promotion Image"
			and aicrm_job.jobid=?';

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
				$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
				//decode_html  - added to handle UTF-8   characters in file names
				//urlencode    - added to handle special characters like #, %, etc.,
				$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
				$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));
				$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
				$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

					for($image_iter=0;$image_iter < count($image_array);$image_iter++){
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
			$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
					{
					list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
					if($pro_image_width  > 450 ||  $pro_image_height > 300)
						$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
						else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
					}else
					{
				$label_fld[] ='';
			}
		}*/
		if($tabid==43)
		{
			$images=array();
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

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
				$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
				//decode_html  - added to handle UTF-8   characters in file names
				//urlencode    - added to handle special characters like #, %, etc.,
				$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
				$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));
				$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
				$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

					for($image_iter=0;$image_iter < count($image_array);$image_iter++){
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
			$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
					{
					list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
					if($pro_image_width  > 450 ||  $pro_image_height > 300)
						$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
						else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
					}else
					{
				$label_fld[] ='';
			}
		}
		if($tabid==45)
		{
			$images=array();
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

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
				$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
				//decode_html  - added to handle UTF-8   characters in file names
				//urlencode    - added to handle special characters like #, %, etc.,
				$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
				$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));
				$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
				$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

					for($image_iter=0;$image_iter < count($image_array);$image_iter++){
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
			$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
					{
					list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
					if($pro_image_width  > 450 ||  $pro_image_height > 300)
						$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
						else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
					}else
					{
				$label_fld[] ='';
			}
		}
		if($tabid==46)
		{
			$images=array();
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

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
				$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
				//decode_html  - added to handle UTF-8   characters in file names
				//urlencode    - added to handle special characters like #, %, etc.,
				$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
				$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));

				$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
				if(count($image_array) < 4)
					$sides=count($image_array)*2;
				else
					$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

				for($image_iter=0;$image_iter < count($image_array);$image_iter++)
				{
					$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
				}
				$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
			{
				list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
				if($pro_image_width  > 450 ||  $pro_image_height > 300)
					$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
				else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
			}else
			{
				$label_fld[] ='';
			}
		}
        if($tabid==53)
        {
            $sql = "select aicrm_attachments.*,aicrm_crmentity.setype from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_attachments.attachmentsid where aicrm_crmentity.setype='".$fieldname."' and aicrm_seattachmentsrel.crmid=? order by aicrm_crmentity.crmid desc";

            $image_res = $adb->pquery($sql, array($col_fields['record_id']));
            $image_id = $adb->query_result($image_res,0,'attachmentsid');
            $image_path = $adb->query_result($image_res,0,'path');

            $image_name = urlencode(decode_html($adb->query_result($image_res,0,'name')));

            $imgpath = $image_path.$image_id."_".$image_name;
            if($image_name != '')
                $label_fld[] ='<img src="'.$imgpath.'" alt="'.$mod_strings[$fieldname].'" style="max-height: 200px; title= "'.$mod_strings[$fieldname].'">';
            else
                $label_fld[] = '';
        }	
		if($tabid==88)
		{
			$images=array();
			$query = 'select
			aicrm_attachments.path,
			aicrm_attachments.attachmentsid,
			aicrm_attachments.name ,
			aicrm_crmentity.setype
			from  aicrm_order
			left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_order.orderid
			inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
			where aicrm_crmentity.setype="Order Attachment"
			and aicrm_order.orderid=?';

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
			$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');

			//decode_html  - added to handle UTF-8   characters in file names
			//urlencode    - added to handle special characters like #, %, etc.,
			$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
			$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));

			$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
				$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

					for($image_iter=0;$image_iter < count($image_array);$image_iter++){
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
			$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
					{
					list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
					if($pro_image_width  > 450 ||  $pro_image_height > 300)
						$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
						else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
					}else
					{
				$label_fld[] ='';
					}
		}
		if($tabid==93)
		{
			$images=array();
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

			$result_image = $adb->pquery($query, array($col_fields['record_id']));
			for($image_iter=0;$image_iter < $adb->num_rows($result_image);$image_iter++)
			{
				$image_id_array[] = $adb->query_result($result_image,$image_iter,'attachmentsid');
				//decode_html  - added to handle UTF-8   characters in file names
				//urlencode    - added to handle special characters like #, %, etc.,
				$image_array[] = urlencode(decode_html($adb->query_result($result_image,$image_iter,'name')));
				$image_orgname_array[] = decode_html($adb->query_result($result_image,$image_iter,'name'));
				$imagepath_array[] = $adb->query_result($result_image,$image_iter,'path');
			}
			if(count($image_array)>1)
			{
			if(count($image_array) < 4)
				$sides=count($image_array)*2;
				else
				$sides=8;

				$image_lists = '<div id="Carousel" style="position:relative;vertical-align: middle;">
					<img src="modules/Products/placeholder.gif" width="571" height="117" style="position:relative;">
					</div><script>var Car_NoOfSides='.$sides.'; Car_Image_Sources=new Array(';

					for($image_iter=0;$image_iter < count($image_array);$image_iter++){
						$images[]='"'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'","'.$imagepath_array[$image_iter].$image_id_array[$image_iter]."_".$image_array[$image_iter].'"';
					}
			$image_lists .=implode(',',$images).');</script><script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script><script language="JavaScript" type="text/javascript">Carousel();</script>';
				$label_fld[] =$image_lists;
			}elseif(count($image_array)==1)
					{
					list($pro_image_width, $pro_image_height) = getimagesize($imagepath_array[0].$image_id_array[0]."_".$image_orgname_array[0]);
					if($pro_image_width  > 450 ||  $pro_image_height > 300)
						$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="450" height="300">';
						else
				$label_fld[] ='<img src="'.$imagepath_array[0].$image_id_array[0]."_".$image_array[0].'" border="0" width="'.$pro_image_width.'" height="'.$pro_image_height.'">';
					}else
					{
				$label_fld[] ='';
			}
		}
	}
	elseif($uitype == 62)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads" || $parent_module == "LeadManagement" )
			{
				$label_fld[] =$app_strings['LBL_LEAD_NAME'];
				$lead_name = getLeadName($value);

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$lead_name.'</a>';
			}
			elseif($parent_module == "Accounts")
			{
				$label_fld[] = $app_strings['LBL_ACCOUNT_NAME'];
				$sql = "select * from  aicrm_account where accountid=?";
				$result = $adb->pquery($sql, array($value));
				$account_name = $adb->query_result($result,0,"accountname");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$account_name.'</a>';
		}
			elseif($parent_module == "Potentials")
			{
				$label_fld[] =$app_strings['LBL_POTENTIAL_NAME'];
				$sql = "select * from  aicrm_potential where potentialid=?";
				$result = $adb->pquery($sql, array($value));
				$potentialname = $adb->query_result($result,0,"potentialname");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$potentialname.'</a>';
			}
			elseif($parent_module == "Products")
			{
				$label_fld[] =$app_strings['LBL_PRODUCT_NAME'];
				$sql = "select * from  aicrm_products where productid=?";
				$result = $adb->pquery($sql, array($value));
				$productname= $adb->query_result($result,0,"productname");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$productname.'</a>';
			}
			elseif($parent_module == "Quotes")
			{
				$label_fld[] = $app_strings['LBL_QUOTES_NAME'];
				$sql = "select * from  aicrm_quotes where quoteid=?";
				$result = $adb->pquery($sql, array($value));
				$quotename= $adb->query_result($result,0,"subject");

				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$quotename.'</a>';
			}
			elseif($parent_module == "HelpDesk")
			{
				$label_fld[] = $app_strings['LBL_HELPDESK_NAME'];
				$sql = "select * from  aicrm_troubletickets where ticketid=?";
				$result = $adb->pquery($sql, array($value));
				$title= $adb->query_result($result,0,"title");
				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$title.'</a>';
			}
			elseif($parent_module == "Activitys")
			{
				$label_fld[] =$app_strings['LBL_ACTIVITYS_NAME'];
				$sql = "select * from  aicrm_activitys where activitysid=".$value;
				$result = $adb->query($sql);
				$activitys_name= $adb->query_result($result,0,"activitys_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$activitys_name.'</a>';
			}
			elseif($parent_module == "Opportunity")
			{
				$label_fld[] =$app_strings['LBL_OPPORTUNITY_NAME'];
				$sql = "select * from  aicrm_opportunity where opportunityid=".$value;
				$result = $adb->query($sql);
				$opportunity_name= $adb->query_result($result,0,"opportunity_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$opportunity_name.'</a>';
			}
			elseif($parent_module == "KnowledgeBase")
			{
				$label_fld[] =$app_strings['LBL_KNOWLEDGEBASE_NAME'];
				$sql = "select * from  aicrm_knowledgebase where knowledgebaseid=".$value;
				$result = $adb->query($sql);
				$knowledgebase_name= $adb->query_result($result,0,"knowledgebase_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$knowledgebase_name.'</a>';
			}
			elseif($parent_module == "Quotation")
			{
				$label_fld[] =$app_strings['LBL_QUESTION_NAME'];
				$sql = "select * from  aicrm_quotation where quotationid=".$value;
				$result = $adb->query($sql);
				$quotation_name= $adb->query_result($result,0,"quotation_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$quotation_name.'</a>';
			}
			elseif($parent_module == "PriceList")
			{
				$label_fld[] =$app_strings['LBL_PRICELIST_NAME'];
				$sql = "select * from  aicrm_pricelists where pricelistid=".$value;
				$result = $adb->query($sql);
				$pricelistname= $adb->query_result($result,0,"pricelist_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$pricelistname.'</a>';
			}
			elseif($parent_module == "Job")
			{
				$label_fld[] =$app_strings['LBL_JOB_NAME'];
				$sql = "select * from  aicrm_jobs where jobid=".$value;
				$result = $adb->query($sql);
				$jobname= $adb->query_result($result,0,"job_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$jobname.'</a>';
			}
			elseif($parent_module == "SmartSms")
			{
				$label_fld[] =$app_strings['LBL_SMARTSMS_NAME'];
				$sql = "select * from  aicrm_smartsms where smartsmsid=".$value;
				$result = $adb->query($sql);
				$smartsmsname= $adb->query_result($result,0,"smartsms_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$smartsmsname.'</a>';
			}
			elseif($parent_module == "Smartquestionnaire")
            {
                $label_fld[] =$app_strings['LBL_SMARTQUESTIONNAIRE_NAME'];
                $sql = "select * from  aicrm_smartquestionnaire where smartquestionnaireid=".$value;
                $result = $adb->query($sql);
                $smartquestionnairename= $adb->query_result($result,0,"smartquestionnaire_name");
                $label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Marketing">'.$smartquestionnairename.'</a>';
            }
			elseif($parent_module == "EmailTargetList")
			{
				$label_fld[] =$app_strings['LBL_EMAILTARGETKIST_NAME'];
				$sql = "select * from  aicrm_emailtargetlists where emailtargetlistid=".$value;
				$result = $adb->query($sql);
				$emailtargetlistname= $adb->query_result($result,0,"emailtargetlist_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$emailtargetlistname.'</a>';
			}

		}
		else
		{
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $value;
		}


	}
	elseif($uitype == 105)//Added for user image
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$sql = "select aicrm_attachments.* from aicrm_attachments left join aicrm_salesmanattachmentsrel on aicrm_salesmanattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid where aicrm_salesmanattachmentsrel.smid=?";
		$image_res = $adb->pquery($sql, array($col_fields['record_id']));
		$image_id = $adb->query_result($image_res,0,'attachmentsid');
		$image_path = $adb->query_result($image_res,0,'path');
		$image_name = $adb->query_result($image_res,0,'name');
		$imgpath = $image_path.$image_id."_".$image_name;
		if($image_name != '') {
			//Added the following check for the image to retain its in original size.
			list($pro_image_width, $pro_image_height) = getimagesize(decode_html($imgpath));
				$label_fld[] ='<a href="'.$imgpath.'" target="_blank"><img src="'.$imgpath.'" width="'.$pro_image_width.'" height="'.$pro_image_height.'" alt="'.$col_fields['user_name'].'" title="'.$col_fields['user_name'].'" border="0"></a>';
		} else
			$label_fld[] = '';
	}
	elseif($uitype == 66)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads" || $parent_module == "LeadManagement")
			{
				$label_fld[] =$app_strings['LBL_LEAD_NAME'];
				$lead_name = getLeadName($value);
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$lead_name.'</a>';
			}
			elseif($parent_module == "Accounts")
			{
				$label_fld[] = $app_strings['LBL_ACCOUNT_NAME'];
				$sql = "select * from  aicrm_account where accountid=?";
				$result = $adb->pquery($sql, array($value));
				$account_name = $adb->query_result($result,0,"accountname");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$account_name.'</a>';
			}
			elseif($parent_module == "Potentials")
			{
				$label_fld[] =$app_strings['LBL_POTENTIAL_NAME'];
				$sql = "select * from  aicrm_potential where potentialid=?";
				$result = $adb->pquery($sql, array($value));
				$potentialname = $adb->query_result($result,0,"potentialname");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$potentialname.'</a>';
			}
			elseif($parent_module == "Quotes")
                        {
				$label_fld[] =$app_strings['LBL_QUOTE_NAME'];
                $sql = "select * from  aicrm_quotes where quoteid=?";
				$result = $adb->pquery($sql, array($value));
                $quotename = $adb->query_result($result,0,"subject");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$quotename.'</a>';
            
            }
            elseif($parent_module == "Campaigns")
			{
				$label_fld[] = $app_strings['LBL_CAMPAIGN_NAME'];
				$sql = "select * from  aicrm_campaign where campaignid=?";
				$result = $adb->pquery($sql, array($value));
				$campaignname = $adb->query_result($result,0,"campaignname");
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$campaignname.'</a>';
			}
			elseif($parent_module == "HelpDesk")
			{
				$label_fld[] = $app_strings['LBL_HELPDESK_NAME'];
				$sql = "select * from  aicrm_troubletickets where ticketid=?";
				$result = $adb->pquery($sql, array($value));
				$tickettitle = $adb->query_result($result,0,"title");
				if(strlen($tickettitle) > 25)
				{
					$tickettitle = substr($tickettitle,0,25).'...';
				}
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$tickettitle.'</a>';
			}
			elseif($parent_module == "Branchs")
			{
				$label_fld[] =$app_strings['LBL_BRANCH_NAME'];
				$sql = "select * from aicrm_branchs where branchsid=".$value;
				$result = $adb->query($sql);
				$branchs_name= $adb->query_result($result,0,"branchs_name");
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Inventory">'.$branchs_name.'</a>';
			}
			elseif($parent_module == "Projects")
			{
				$label_fld[] =$app_strings['LBL_PROJECTS_NAME'];
				$sql = "select * from  aicrm_projects where projectsid=".$value;
				$result = $adb->query($sql);
				$projects_name= $adb->query_result($result,0,"projects_name");
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$projects_name.'</a>';
			}
			elseif($parent_module == "Competitor")
			{
				$label_fld[] =$app_strings['LBL_COMPETITOR_NAME'];
				$sql = "select * from  aicrm_competitor where competitorid=".$value;
				$result = $adb->query($sql);
				$competitor_name= $adb->query_result($result,0,"competitor_name");
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Marketing">'.$competitor_name.'</a>';
			}
			elseif($parent_module == "Competitorproduct")
			{
				$label_fld[] =$app_strings['LBL_COMPETITORPRODUCT_NAME'];
				$sql = "select * from  aicrm_competitorproduct where competitorproductid=".$value;
				$result = $adb->query($sql);
				$competitorproduct_name= $adb->query_result($result,0,"competitorproduct_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Marketing">'.$competitorproduct_name.'</a>';
			}

			elseif($parent_module == "Premuimproduct")
			{
				$label_fld[] =$app_strings['LBL_PROMOTIONVOUCHER_NAME'];
				$sql = "select * from  aicrm_premuimproduct where premuimproductid=".$value;
				$result = $adb->query($sql);
				$premuimproduct_name= $adb->query_result($result,0,"premuimproduct_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Marketing">'.$premuimproduct_name.'</a>';
			}

			elseif($parent_module == "Service")
			{
				$label_fld[] =$app_strings['LBL_SERVICE_NAME'];
				$sql = "select * from  aicrm_service where serviceid=".$value;
				$result = $adb->query($sql);
				$service_name= $adb->query_result($result,0,"service_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Marketing">'.$service_name.'</a>';
			}

			elseif($parent_module == "Servicerequest")
			{
				$label_fld[] =$app_strings['LBL_SERVICEREQUEST_NAME'];
				$sql = "select * from  aicrm_servicerequest where servicerequestid=".$value;
				$result = $adb->query($sql);
				$servicerequest_name= $adb->query_result($result,0,"servicerequest_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Marketing">'.$servicerequest_name.'</a>';
			}

			elseif($parent_module == "Serial")
			{
				$label_fld[] =$app_strings['LBL_SERIAL_NAME'];
				$sql = "select * from  aicrm_serial where serialid=".$value;
				$result = $adb->query($sql);
				$serial_name= $adb->query_result($result,0,"serial_name");
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Inventory">'.$serial_name.'</a>';
			}
			elseif($parent_module == "Seriallist")
			{
				$label_fld[] =$app_strings['LBL_SERIALLIST_NAME'];
				$sql = "select * from  aicrm_seriallist where seriallistid=".$value;
				$result = $adb->query($sql);
				$seriallist_name= $adb->query_result($result,0,"seriallist_name");
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Inventory">'.$seriallist_name.'</a>';
			}
			elseif($parent_module == "Inspection")
			{
				$label_fld[] =$app_strings['LBL_INSPECTION_NAME'];
				$sql = "select * from  aicrm_inspection where inspectionid=".$value;
				$result = $adb->query($sql);
				$inspection_name= $adb->query_result($result,0,"inspection_name");
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Inventory">'.$inspection_name.'</a>';
			}
			elseif($parent_module == "Inspectiontemplate")
			{
				$label_fld[] =$app_strings['LBL_INSPECTIONTEMPLATE_NAME'];
				$sql = "select * from  aicrm_inspectiontemplate where inspectiontemplateid=".$value;
				$result = $adb->query($sql);
				$inspectiontemplate_name= $adb->query_result($result,0,"inspectiontemplate_name");
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Inventory">'.$inspectiontemplate_name.'</a>';
			}
			elseif($parent_module == "Errors")
			{
				$label_fld[] =$app_strings['LBL_ERRORS_NAME'];
				$sql = "select * from  aicrm_errors where errorsid=".$value;
				$result = $adb->query($sql);
				$errors_name= $adb->query_result($result,0,"errors_name");
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Inventory">'.$errors_name.'</a>';
			}
			elseif($parent_module == "Errorslist")
			{
				$label_fld[] =$app_strings['LBL_ERRORSLIST_NAME'];
				$sql = "select * from  aicrm_errorslist where errorslistid=".$value;
				$result = $adb->query($sql);
				$errorslist_name= $adb->query_result($result,0,"errorslist_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Inventory">'.$errorslist_name.'</a>';
			}
			elseif($parent_module == "Sparepart")
			{
				$label_fld[] =$app_strings['LBL_SPAREPART_NAME'];
				$sql = "select * from  aicrm_sparepart where sparepartid=".$value;
				$result = $adb->query($sql);
				$sparepart_name= $adb->query_result($result,0,"sparepart_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Inventory">'.$sparepart_name.'</a>';
			}
			elseif($parent_module == "Sparepartlist")
			{
				$label_fld[] =$app_strings['LBL_SPAREPARTLIST_NAME'];
				$sql = "select * from  aicrm_sparepartlist where sparepartlistid=".$value;
				$result = $adb->query($sql);
				$sparepartlist_name= $adb->query_result($result,0,"sparepartlist_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Inventory">'.$sparepartlist_name.'</a>';
			}
			elseif($parent_module == "Activitys")
			{
				$label_fld[] =$app_strings['LBL_ACTIVITYS_NAME'];
				$sql = "select * from  aicrm_activitys where activitysid=".$value;
				$result = $adb->query($sql);
				$activitys_name= $adb->query_result($result,0,"activitys_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$activitys_name.'</a>';
			}
			elseif($parent_module == "Opportunity")
			{
				$label_fld[] =$app_strings['LBL_OPPORTUNITY_NAME'];
				$sql = "select * from  aicrm_opportunity where opportunityid=".$value;
				$result = $adb->query($sql);
				$opportunity_name= $adb->query_result($result,0,"opportunity_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$opportunity_name.'</a>';
			}
			elseif($parent_module == "KnowledgeBase")
			{
				$label_fld[] =$app_strings['LBL_KNOWLEDGE_BASE_NAME'];
				$sql = "select * from  aicrm_knowledgebase where knowledgebaseid=".$value;
				$result = $adb->query($sql);
				$knowledgebase_name= $adb->query_result($result,0,"knowledgebase_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$knowledgebase_name.'</a>';
			}
			elseif($parent_module == "Quotation")
			{
				$label_fld[] =$app_strings['LBL_QUESTION_NAME'];
				$sql = "select * from  aicrm_quotation where quotationid=".$value;
				$result = $adb->query($sql);
				$quotation_name= $adb->query_result($result,0,"quotation_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$quotation_name.'</a>';
			}
			elseif($parent_module == "PriceList")
			{
				$label_fld[] =$app_strings['LBL_PRICELIST_NAME'];
				$sql = "select * from  aicrm_pricelists where pricelistid=".$value;
				$result = $adb->query($sql);
				$pricelistname= $adb->query_result($result,0,"pricelist_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$pricelistname.'</a>';
			}
			elseif($parent_module == "Job")
			{
				$label_fld[] =$app_strings['LBL_JOB_NAME'];
				$sql = "select * from  aicrm_jobs where jobid=".$value;
				$result = $adb->query($sql);
				$jobname= $adb->query_result($result,0,"job_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$jobname.'</a>';
			}
			elseif($parent_module == "SmartSms")
			{
				$label_fld[] =$app_strings['LBL_SMARTSMS_NAME'];
				$sql = "select * from  aicrm_smartsms where smartsmsid=".$value;
				$result = $adb->query($sql);
				$smartsmsname= $adb->query_result($result,0,"smartsms_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$smartsmsname.'</a>';
			}
			elseif($parent_module == "Smartemail")
			{
				$label_fld[] =$app_strings['LBL_SMARTEMAIL_NAME'];
				$sql = "select * from  aicrm_smartemail where smartemailid=".$value;
				$result = $adb->query($sql);
				$smartemail_name= $adb->query_result($result,0,"smartemail_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$smartemail_name.'</a>';
			}
			elseif($parent_module == "Faq")
			{
				$label_fld[] =$app_strings['LBL_FAQ_NAME'];
				$sql = "select * from  aicrm_faq where faqid=".$value;
				$result = $adb->query($sql);
				$faqname= $adb->query_result($result,0,"faq_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Service">'.$faqname.'</a>';
			}
			elseif($parent_module == "Plant")
			{
				$label_fld[] =$app_strings['LBL_PLANT_NAME'];
				$sql = "select * from  aicrm_plant where plantid=".$value;
				$result = $adb->query($sql);
				$plant_name= $adb->query_result($result,0,"plant_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Service">'.$plant_name.'</a>';
			}elseif($parent_module == "Order")
			{
				$label_fld[] =$app_strings['LBL_ORDER_NAME'];
				$sql = "select * from  aicrm_order where orderid=".$value;
				$result = $adb->query($sql);
				$order_name= $adb->query_result($result,0,"order_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Service">'.$order_name.'</a>';
			
			}elseif($parent_module == "Deal")
			{
				$label_fld[] =$app_strings['LBL_DEAL_NAME'];
				$sql = "select * from  aicrm_deal where dealid=".$value;
				$result = $adb->query($sql);
				$deal_name= $adb->query_result($result,0,"deal_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Marketing">'.$deal_name.'</a>';
			}elseif($parent_module == "Promotion")
			{
				$label_fld[] =$app_strings['LBL_PROMOTION_NAME'];
				$sql = "select * from  aicrm_promotion where promotionid=".$value;
				$result = $adb->query($sql);
				$promotion_name= $adb->query_result($result,0,"promotion_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Marketing">'.$promotion_name.'</a>';

			}elseif($parent_module == "Promotionvoucher")
			{
				$label_fld[] =$app_strings['LBL_PROMOTIONVOUCHER_NAME'];
				$sql = "select * from  aicrm_promotionvoucher where promotionvoucherid=".$value;
				$result = $adb->query($sql);
				$promotionvoucher_name= $adb->query_result($result,0,"promotionvoucher_name");
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Marketing">'.$promotionvoucher_name.'</a>';
					
			}elseif($parent_module == "Voucher")
			{
				$label_fld[] =$app_strings['LBL_VOUCHER_NAME'];
				$sql = "select * from  aicrm_voucher where voucherid=".$value;
				$result = $adb->query($sql);
				$voucher_name= $adb->query_result($result,0,"voucher_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Marketing">'.$voucher_name.'</a>';
			}elseif($parent_module == "Questionnaire")
			{
				$label_fld[] =$app_strings['LBL_QUESTIONNAIRE_NAME'];
				$sql = "select * from  aicrm_questionnaire where questionnaireid=".$value;
				$result = $adb->query($sql);
				$questionnaire_name= $adb->query_result($result,0,"questionnaire_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Marketing">'.$questionnaire_name.'</a>';
			}elseif($parent_module == "Questionnairetemplate") {
                $label_fld[] = $app_strings['LBL_QUESTIONNAIRETEMOLATE_NAME'];
                $sql = "select * from  aicrm_questionnairetemplate where questionnairetemplateid=" . $value;
                $result = $adb->query($sql);
                $questionnairetemplate_name = $adb->query_result($result, 0, "questionnairetemplate_name");

                $label_fld[] = '<a href="index.php?module=' . $parent_module . '&action=DetailView&record=' . $value . '&parenttab=Marketing">' . $questionnairetemplate_name . '</a>';

			}elseif($parent_module == "Questionnaireanswer") {

            	$label_fld[] =$app_strings['LBL_QUESTIONNAIREANSWES_NAME'];
                $sql = "select * from  aicrm_questionnaireanswer where questionnaireanswerid=".$value;
                $result = $adb->query($sql);
                $questionnaireanswername= $adb->query_result($result,0,"questionnaireanswer_name");
                $label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Marketing">'.$questionnaireanswername.'</a>';
			}
			elseif($parent_module == "Announcement")
			{
				$label_fld[] =$app_strings['LBL_ANNOUNCEMENT_NAME'];
				$sql = "select * from  aicrm_announcement where announcementid=".$value;
				$result = $adb->query($sql);
				$announcement_name= $adb->query_result($result,0,"announcement_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Marketing">'.$announcement_name.'</a>';
			}
			elseif($parent_module == "Point")
			{
				$label_fld[] =$app_strings['LBL_POINT_NAME'];
				$sql = "select * from  aicrm_point where pointid=".$value;
				$result = $adb->query($sql);
				$pointname= $adb->query_result($result,0,"point_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$pointname.'</a>';
			}
			elseif($parent_module == "Redemption")
			{
				$label_fld[] =$app_strings['LBL_REDEMPTION_NAME'];
				$sql = "select * from  aicrm_redemption where redemptionid=".$value;
				$result = $adb->query($sql);
				$redemptionname= $adb->query_result($result,0,"redemption_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$redemptionname.'</a>';
			}
			elseif($parent_module == "Salesorder")
			{
				$label_fld[] =$app_strings['LBL_SALESORDER_NAME'];
				$sql = "select * from  aicrm_salesorder where salesorderid=".$value;
				$result = $adb->query($sql);
				$salesorder_name= $adb->query_result($result,0,"salesorder_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$salesorder_name.'</a>';
			}
			elseif($parent_module == "Tools")
            {
                $label_fld[] = $app_strings['LBL_TOOLS_NAME'];
                $sql = "select * from  aicrm_tools where toolsid=?";
                $result = $adb->pquery($sql, array($value));
                $tools_name = $adb->query_result($result,0,"tools_name");

                $label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$tools_name.'</a>';
            }
			elseif($parent_module == "Salesinvoice")
			{
				$label_fld[] =$app_strings['LBL_SALESINVOICE_NAME'];
				$sql = "select * from  aicrm_salesinvoice where salesinvoiceid=".$value;
				$result = $adb->query($sql);
				$salesinvoice_name= $adb->query_result($result,0,"salesinvoice_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$salesinvoice_name.'</a>';
			}
			elseif($parent_module == "Expense")
			{
				$label_fld[] =$app_strings['LBL_EXPENSE_NAME'];
				$sql = "select * from  aicrm_expense where expenseid=".$value;
				$result = $adb->query($sql);
				$expense_name= $adb->query_result($result,0,"expense_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Inventory">'.$expense_name.'</a>';
			}
			elseif($parent_module == "Purchasesorder")
			{
				$label_fld[] =$app_strings['LBL_PURCHASESORDER_NAME'];
				$sql = "select * from  aicrm_purchasesorder where purchasesorderid=".$value;
				$result = $adb->query($sql);
				$purchasesorder_name= $adb->query_result($result,0,"purchasesorder_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$purchasesorder_name.'</a>';
			}
			elseif($parent_module == "Samplerequisition")
			{
				$label_fld[] =$app_strings['LBL_SAMPLEREQUISITION_NAME'];
				$sql = "select * from  aicrm_samplerequisition where samplerequisitionid=".$value;
				$result = $adb->query($sql);
				$samplerequisition_name= $adb->query_result($result,0,"samplerequisition_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$samplerequisition_name.'</a>';
			}
			elseif($parent_module == "Goodsreceive")
			{
				$label_fld[] =$app_strings['LBL_GOODSRECEIVE_NAME'];
				$sql = "select * from  aicrm_goodsreceive where goodsreceiveid=".$value;
				$result = $adb->query($sql);
				$goodsreceive_name= $adb->query_result($result,0,"goodsreceive_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$goodsreceive_name.'</a>';
			}
			elseif($parent_module == "Marketingtools")
			{
				$label_fld[] =$app_strings['LBL_MARKETINGTOOLS_NAME'];
				$sql = "select * from  aicrm_marketingtools where marketingtoolsid=".$value;
				$result = $adb->query($sql);
				$marketingtools_name= $adb->query_result($result,0,"marketingtools_name");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'&parenttab=Sales">'.$marketingtools_name.'</a>';
			}
		}
		else
		{
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $value;
		}
	}
	elseif($uitype == 67)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Leads" || $parent_module == "LeadManagement")
			{
				$label_fld[] = $app_strings['LBL_LEAD_NAME'];
				$lead_name = getLeadName($value);
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$lead_name.'</a>';
			}
			elseif($parent_module == "Contacts")
			{
				$label_fld[] = $app_strings['LBL_CONTACT_NAME'];
				$contact_name = getContactName($value);
				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$contact_name.'</a>';
			}
		}
		else
		{
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $value;

		}
	}
	//added by raju/rdhital for better emails
	elseif($uitype == 357)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_name='';
			$parent_id='';
			$myemailid= $_REQUEST['record'];
			$mysql = "select crmid from aicrm_seactivityrel where activityid=?";
			$myresult = $adb->pquery($mysql, array($myemailid));
			$mycount=$adb->num_rows($myresult);
			if ($mycount>1){
				$label_fld[] = $app_strings['LBL_RELATED_TO'];
				$label_fld[] =$app_strings['LBL_MULTIPLE'];
			}
			else
			{
				$parent_module = getSalesEntityType($value);
				if($parent_module == "Leads"||$parent_module == "LeadManagement")
				{
					$label_fld[] = $app_strings['LBL_LEAD_NAME'];
					$lead_name = getLeadName($value);
					$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$lead_name.'</a>';
				}
				elseif($parent_module == "Contacts")
				{
					$label_fld[] = $app_strings['LBL_CONTACT_NAME'];
					$contact_name = getContactName($value);
					$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$contact_name.'</a>';
				}
				elseif($parent_module == "Accounts")
				{
					$label_fld[] = $app_strings['LBL_ACCOUNT_NAME'];
					$sql = "select * from  aicrm_account where accountid=?";
					$result = $adb->pquery($sql, array($value));
					$accountname = $adb->query_result($result,0,"accountname");
					$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$accountname.'</a>';
				}

			}
		}
		else
		{
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $value;
		}
	}//Code added by raju for better email ends

	elseif($uitype == 68)
	{
		$value = $col_fields[$fieldname];
		if($value != '')
		{
			$parent_module = getSalesEntityType($value);
			if($parent_module == "Contacts")
			{
				$label_fld[] = $app_strings['LBL_CONTACT_NAME'];
				$contact_name = getContactName($value);
				$label_fld[] ='<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$contact_name.'</a>';
			}
			elseif($parent_module == "Accounts")
			{
				$label_fld[] = $app_strings['LBL_ACCOUNT_NAME'];
				$sql = "select * from aicrm_account where accountid=?";
				$result = $adb->pquery($sql, array($value));
				$account_name = $adb->query_result($result,0,"accountname");

				$label_fld[] = '<a href="index.php?module='.$parent_module.'&action=DetailView&record='.$value.'">'.$account_name.'</a>';
			}
			else
			{
				$value ='';
				$label_fld[] = getTranslatedString($fieldlabel, $module);
				$label_fld[] = $value;
			}


		}
		else
		{
			$label_fld[] = getTranslatedString($fieldlabel, $module);
			$label_fld[] = $value;
		}
	}

	elseif($uitype==63)
        {
	   $label_fld[] =getTranslatedString($fieldlabel, $module);
	   $label_fld[] = $col_fields[$fieldname].'h&nbsp; '.$col_fields['duration_minutes'].'m';
        }

	elseif($uitype == 6)
        {
		$label_fld[] =getTranslatedString($fieldlabel, $module);
          	if($col_fields[$fieldname]=='0')
                $col_fields[$fieldname]='';
		if($col_fields['time_start']!='')
                {
                       $start_time = $col_fields['time_start'];
                }
		if($col_fields[$fieldname] == '0000-00-00')
		{
			$displ_date = '';
		}
		else
		{
			$displ_date = getDisplayDate($col_fields[$fieldname]);
		}
		if(empty($start_time)) $label_fld[] = $displ_date;
		else $label_fld[] = $displ_date.' '.$start_time;

	}
	elseif($uitype == 5 || $uitype == 23 || $uitype == 70)
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$cur_date_val = $col_fields[$fieldname];
		if($col_fields['time_end']!='' && ($tabid == 9 || $tabid == 16) && $uitype == 23)
		{
			$end_time = $col_fields['time_end'];
		}
		if($cur_date_val == '0000-00-00')
		{
			$display_val = '';
		}
		else
		{
			$display_val = getDisplayDate($cur_date_val);
		}
		if(empty($end_time)) $label_fld[] = $display_val;
		else $label_fld[] = $display_val . ' ' . $end_time;
	}
	//ekk======================================================================
	elseif($uitype == 71 || $uitype == 72)
	   {
		  $label_fld[] = getTranslatedString($fieldlabel, $module);
		  $type_query="select typeofdata  from aicrm_field  where fieldlabel = ? and tabid = ?";
		  $typeResult = $adb->pquery($type_query, array($fieldlabel,$tabid));
		  $typeRows = $adb->num_rows($pickListResult);
		  
		  if($adb->query_result($typeResult,0,'typeofdata')<>''){
			 $typeofdata = $adb->query_result($typeResult,0,'typeofdata');
			 $type1 = explode(",", $typeofdata );
			 if(count($type1)>1){
				$lenght  = $type1[1];
			 }else{
				$lenght = 0;
			 }
		  }else{
			 $lenght = 0;
		  }
		  if($fieldname == 'unit_price') {
			//  $rate_symbol=getCurrencySymbolandCRate(getProductBaseCurrency($col_fields['record_id'], $module));
			//  $label_fld[]  = number_format($col_fields[$fieldname],$lenght);
			 $rate_symbol=getCurrencySymbolandCRate($user_info['currency_id']);
			 $rate = $rate_symbol['rate'];
			 $label_fld[]  = number_format(convertFromDollar($col_fields[$fieldname],$rate),$lenght);
		  } else {
			 $rate_symbol=getCurrencySymbolandCRate($user_info['currency_id']);
			 $rate = $rate_symbol['rate'];
			 $label_fld[]  = number_format(convertFromDollar($col_fields[$fieldname],$rate),$lenght);
		  }
			$currency = $rate_symbol['symbol'];
			$label_fld["cursymb"] = $currency;
	}
	elseif($uitype == 76)
    {
		 $label_fld[] =getTranslatedString($fieldlabel, $module);
           $potential_id = $col_fields[$fieldname];
           if($potential_id != '')
           {
                   $potential_name = getPotentialName($potential_id);
           }
          $label_fld[] = $potential_name;
		$label_fld["secid"] = $potential_id;
		$label_fld["link"] = "index.php?module=Potentials&action=DetailView&record=".$potential_id;
    }
	elseif($uitype == 78)
    {
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$quote_id = $col_fields[$fieldname];
		if($quote_id != '')
		{
		       $quote_name = getQuoteName($quote_id);
		}
		$label_fld[] = $quote_name;
		$label_fld["secid"] = $quote_id;
		$label_fld["link"] = "index.php?module=Quotes&action=DetailView&record=".$quote_id;
    }
	elseif($uitype == 30)
	{
		$rem_days = 0;
		$rem_hrs = 0;
		$rem_min = 0;
		$reminder_str ="";
		$rem_days = floor($col_fields[$fieldname]/(24*60));
		$rem_hrs = floor(($col_fields[$fieldname]-$rem_days*24*60)/60);
		$rem_min = ($col_fields[$fieldname]-$rem_days*24*60)%60;

		$label_fld[] =getTranslatedString($fieldlabel, $module);
		if($col_fields[$fieldname])
                {
                    $reminder_str= $rem_days.'&nbsp;'.$mod_strings['LBL_DAYS'].'&nbsp;'.$rem_hrs.'&nbsp;'.$mod_strings['LBL_HOURS'].'&nbsp;'.$rem_min.'&nbsp;'.$mod_strings['LBL_MINUTES'].'&nbsp;&nbsp;'.$mod_strings['LBL_BEFORE_EVENT'];
                }
		$label_fld[] = '&nbsp;'.$reminder_str;
	}elseif($uitype == 98)
	{
	 	$label_fld[] =getTranslatedString($fieldlabel, $module);
		if(is_admin($current_user))
			$label_fld[] = '<a href="index.php?module=Settings&action=RoleDetailView&roleid='.$col_fields[$fieldname].'">'.getRoleName($col_fields[$fieldname]).'</a>';
		else
			$label_fld[] = getRoleName($col_fields[$fieldname]);
	}elseif($uitype == 85) //Added for Skype by Minnie
	{
		$label_fld[] =getTranslatedString($fieldlabel, $module);
		$label_fld[]= $col_fields[$fieldname];
	}
	elseif($uitype == 26){
		 $label_fld[] =getTranslatedString($fieldlabel, $module);
		 $query = "select foldername from aicrm_attachmentsfolder where folderid = ?";
		 $result = $adb->pquery($query, array($col_fields[$fieldname]));
		 $folder_name = $adb->query_result($result,0,"foldername");
		 $label_fld[] = $folder_name;
	}
	elseif($uitype == 27){
		if($col_fields[$fieldname] == 'I'){
			$label_fld[]=getTranslatedString($fieldlabel, $module);
			$label_fld[]= $mod_strings['LBL_INTERNAL'];
		}
		else{
			$label_fld[]=getTranslatedString($fieldlabel, $module);
			$label_fld[]= $mod_strings['LBL_EXTERNAL'];
		}
	}
	else
	{
	 $label_fld[] =getTranslatedString($fieldlabel, $module);
        if($col_fields[$fieldname]=='0' && $fieldname != 'filedownloadcount' && $fieldname != 'filestatus' && $fieldname != 'filesize')
              $col_fields[$fieldname]='';
		if($uitype == 1 && ($fieldname=='expectedrevenue' || $fieldname=='budgetcost' || $fieldname=='actualcost' || $fieldname=='expectedroi' || $fieldname=='actualroi' || $fieldname=='location' || $fieldname=='location_chkout'  ))
		{
			if($fieldname=='location' || $fieldname=='location_chkout' ){
			    $value = $col_fields[$fieldname];
			    $value_array = split(',', $value);
			    $label_fld[] = "<a href='https://www.google.co.th/maps/dir//".$value_array[0].",".$value_array[1]."/@".$value_array[0].",".$value_array[1].",20z' target=_blank >".$value."</a>" ;
			}else{
			 	$rate_symbol=getCurrencySymbolandCRate($user_info['currency_id']);
				$label_fld[]=convertFromDollar($col_fields[$fieldname],$rate_symbol['rate']);
			}
		}
		else
		{
			if($tabid == 8)
			{
				$downloadtype = $col_fields['filelocationtype'];
				if($fieldname == 'filename')
				{
					if($downloadtype == 'I')
					{
						//$file_value = $mod_strings['LBL_INTERNAL'];
						$fld_value = $col_fields['filename'];
						$ext_pos = strrpos($fld_value, ".");
						$ext =substr($fld_value, $ext_pos + 1);
						$ext = strtolower($ext);
						if($ext == 'bin' || $ext == 'exe' || $ext == 'rpm')
							$fileicon="<img src='" . aicrm_imageurl('fExeBin.gif', $theme) . "' hspace='3' align='absmiddle' border='0'>";
						elseif($ext == 'jpg' || $ext == 'gif' || $ext == 'bmp')
							$fileicon="<img src='" . aicrm_imageurl('fbImageFile.gif', $theme) . "' hspace='3' align='absmiddle' border='0'>";
						elseif($ext == 'txt' || $ext == 'doc' || $ext == 'xls')
							$fileicon="<img src='" . aicrm_imageurl('fbTextFile.gif', $theme) . "' hspace='3' align='absmiddle' border='0'>";
						elseif($ext == 'zip' || $ext == 'gz' || $ext == 'rar')
							$fileicon="<img src='" . aicrm_imageurl('fbZipFile.gif', $theme) . "' hspace='3' align='absmiddle'	border='0'>";
						else
							$fileicon="<img src='" . aicrm_imageurl('fbUnknownFile.gif', $theme) . "' hspace='3' align='absmiddle' border='0'>";
					}
					else
					{
						$fld_value = $col_fields['filename'];
						$fileicon = "<img src='" . aicrm_imageurl('fbLink.gif', $theme) . "' alt='".$mod_strings['LBL_EXTERNAL_LNK']."' title='".$mod_strings['LBL_EXTERNAL_LNK']."' hspace='3' align='absmiddle' border='0'>";
					}
					$label_fld[] = $fileicon.$fld_value;
				}
				if($fieldname == 'filesize')
				{
					if($col_fields['filelocationtype'] == 'I')
					{
						$filesize = $col_fields[$fieldname];
						if($filesize < 1024)
							$label_fld[]=$filesize.' B';
						elseif($filesize > 1024 && $filesize < 1048576)
							$label_fld[]=round($filesize/1024,2).' KB';
						else if($filesize > 1048576)
							$label_fld[]=round($filesize/(1024*1024),2).' MB';
					}
					else
					{
						$label_fld[]=' --';
					}
				}
				if($fieldname == 'filetype' && $col_fields['filelocationtype'] == 'E')
				{
					$label_fld[]=' --';
				}
				
			}
		
			if($uitype== 7){
				
				$type_query="select typeofdata from aicrm_field where fieldlabel = ? ";
				$typeResult = $adb->pquery($type_query, array($fieldlabel));
								  
				if($adb->query_result($typeResult,0,'typeofdata')<>''){
					$typeofdata = $adb->query_result($typeResult,0,'typeofdata');
					$type1 = explode(",", $typeofdata );
					
					if(count($type1)>1){
						$lenght  = $type1[1];
					}else{
						$lenght = 0;
					}

				}else{
					$lenght = 0;
				}

				$label_fld[] = number_format($col_fields[$fieldname],$lenght);
			
			}else{
				$label_fld[] = $col_fields[$fieldname];
			}
			//code for Documents module :end

		}
	}
	$label_fld[]=$uitype;
	// print_r($label_fld);
	//sets whether the currenct user is admin or not
	if(is_admin($current_user))
	{
	    $label_fld["isadmin"] = 1;
	}else
	{
	   $label_fld["isadmin"] = 0;
  }

	$log->debug("Exiting getDetailViewOutputHtml method ...");
	return $label_fld;
}

function getDetail_Campaigns($module,$focus){
	global $log;
	$log->debug("Entering getDetail_Campaigns(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$output = '';
	$crmid=$focus->id;

	//Set Up Product Price Detail
	//tab1
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

	$output.='
	<table width="98%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td colspan="5" align="left" class="dvInnerHeader"><strong>Set Up Product Detail</strong></td>
	  </tr>
	  <tr>
		<td width="5%" height="30px" align="center" class="dvtCellLabel"><strong>No</strong></td>
		<td width="15%" align="left" class="dvtCellLabel"><strong>Product Code</strong></td>
		<td width="30%" align="left" class="dvtCellLabel"><strong>Product Name</strong></td>
		<td width="5%" align="center" class="dvtCellLabel"><strong>Qty</strong></td>
	  </tr>';
		for($i=0;$i<$num_rows;$i++){
			$output.='
			  <tr>
				<td height="30px" align="center" class="dvtCellInfo" valign="top">'.($i+1).'</td>
				<td align="left" class="dvtCellInfo">'.$adb->query_result($data,$i,'cf_1124').'</td>
				<td align="left" class="dvtCellInfo">'.$adb->query_result($data,$i,'productname').'</td>
				<td align="center" class="dvtCellInfo">'.number_format($adb->query_result($data,$i,'quantity'),0).'</td>
			  </tr>';
		}
	$output.='
	</table>
	';

	//tab2
	$sql="
	select
	*
	from aicrm_inventory_campaign_dtl2
	where 1
	and aicrm_inventory_campaign_dtl2.id='".$crmid."'
	";
	
	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);

	$output.='<br>';
	$output.='
	<table width="98%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td colspan="5" align="left" class="dvInnerHeader"><strong>Set Up Detail</strong></td>
	  </tr>
	  <tr>
		<td width="5%" height="30px" align="center" class="dvtCellLabel"><strong>No</strong></td>
		<td width="25%" align="center" class="dvtCellLabel"><strong>From</strong></td>
		<td width="25%" align="center" class="dvtCellLabel"><strong>To</strong></td>
		<td width="15%" align="center" class="dvtCellLabel"><strong>Fomula</strong></td>
		<td width="15%" align="center" class="dvtCellLabel"><strong>Parameter</strong></td>
	  </tr>';
		for($i=0;$i<$num_rows;$i++){
			$fomula="";
			if($adb->query_result($data,$i,'campaign_fomula')=="plus"){
				$fomula="+";
			}else if($adb->query_result($data,$i,'campaign_fomula')=="multiply"){
				$fomula="*";
			}
			$output.='
			  <tr>
				<td height="30px" align="center" class="dvtCellInfo" valign="top">'.($i+1).'</td>
				<td align="center" class="dvtCellInfo">'.number_format($adb->query_result($data,$i,'campaign_from'),0).'</td>
				<td align="center" class="dvtCellInfo">'.number_format($adb->query_result($data,$i,'campaign_to'),0).'</td>
				<td align="center" class="dvtCellInfo">'.$fomula.'</td>
				<td align="center" class="dvtCellInfo">'.number_format($adb->query_result($data,$i,'campaign_parameter'),0).'</td>
			  </tr>';
		}
	$output.='
	</table>
	';
	$output.='<br>';
	return $output;
}

function getDetail_Tab1($module,$focus){
	global $log;
	$log->debug("Entering getDetail_Tab1(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$output = '';
	$crmid=$focus->id;
	//$sub_prod_query = $adb->pquery("SELECT cf_1726 from aicrm_promotioncf WHERE promotionid=? ",array($crmid));
	//$tab_id = $adb->query_result($sub_prod_query,0,'cf_1726');
	$sql="
	select
	aicrm_products.*
	,aicrm_productcf.*
	,aicrm_inventory_protab1_dtl3.*
	from aicrm_inventory_protab1_dtl3
	left join aicrm_products on aicrm_products.productid=aicrm_inventory_protab1_dtl3.productid
	left join aicrm_productcf on aicrm_products.productid=aicrm_productcf.productid
	where 1
	and aicrm_inventory_protab1_dtl3.id='".$crmid."'
	";
	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);
	$output.='
	<table width="100%" border="0" cellspacing="0" cellpadding="0" >
	  <tr>
		<td colspan="4" align="left" class="dvInnerHeader"><strong>สินค้าที่เข้าร่วมรายการ เงื่อนไข : </strong>'.$adb->query_result($data,0,'campaign_fomula').'</td>
	  </tr>
	  <tr>
		<td width="10%" height="30px" align="center" class="dvtCellLabel lineOnBottom"><strong>ลำดับ</strong></td>
		<td width="60%" height="30px" align="left" class="dvtCellLabel lineOnBottom"><strong>ชื่อสินค้า</strong></td>
		<td width="30%" align="center" class="dvtCellLabel lineOnBottom"><strong>หน่วยนับ</strong></td>
	  </tr>
		';
		for($i=0;$i<$num_rows;$i++){
			$output.='
				  <tr>
				  	<td width="10%" height="30px" align="center" class="dvtCellInfo crmTableRow ">'.($i+1).'</td>
					<td width="60%" height="30px" align="left" class="dvtCellInfo crmTableRow ">'.$adb->query_result($data,$i,'productname').'</td>
					<td width="30%" align="center" class="dvtCellInfo crmTableRow ">'.$adb->query_result($data,$i,'uom').'</td>
				  </tr>
			';
		}
		$output.='
		</table><br>
		';

		$sql="
		select
		*
		from aicrm_inventory_protab1_dtl1
		where 1
		and aicrm_inventory_protab1_dtl1.id='".$crmid."'
		";
		
		$data = $adb->pquery($sql, "");
		$num_rows=$adb->num_rows($data);


		$output.='
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td colspan="4" align="left" class="dvInnerHeader"><strong>Set Up Product Price Detail</strong></td>
		  </tr>
		  <tr>
			<td width="15%" height="30px" align="center" class="dvtCellLabel lineOnBottom"><strong>ราคาสินค้า จาก</strong></td>
			<td width="15%" align="center" class="dvtCellLabel lineOnBottom"><strong>ถึง ราคาสินค้า</strong></td>
			<td width="70%" align="center" class="dvtCellLabel lineOnBottom"><strong>ชื่อพรีเมียม</strong></td>
		  </tr>';
		for($i=0;$i<$num_rows;$i++){
		$output.='
		  <tr>
			<td height="30px" align="center" class="dvtCellInfo crmTableRow">'.number_format($adb->query_result($data,$i,'productprice_from'),2).'</td>
			<td align="center" class="dvtCellInfo crmTableRow" >'.number_format($adb->query_result($data,$i,'productprice_to'),2).'</td>
			<td align="left" class="crmTableRow"><strong>เงื่อนไข : </strong>'.$adb->query_result($data,0,'campaign_fomula').'';

			$sql="
			select
			aicrm_inventory_protab1_dtl2.premiumproductid
			from aicrm_inventory_protab1_dtl2
			where 1
			and aicrm_inventory_protab1_dtl2.id='".$crmid."'
			and aicrm_inventory_protab1_dtl2.row_id='".$adb->query_result($data,$i,'sequence_no')."'
			group by aicrm_inventory_protab1_dtl2.premiumproductid
			order by row_id
			";
		
			$data_rr = $adb->pquery($sql, "");
			$num_rows_rr=$adb->num_rows($data_rr);
			for($kk=0;$kk<$num_rows_rr;$kk++){
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
				and aicrm_inventory_protab1_dtl2.row_id='".$adb->query_result($data,$i,'sequence_no')."'
				and aicrm_inventory_protab1_dtl2.premiumproductid='".$adb->query_result($data_rr,$kk,'premiumproductid')."'
				";
			
				$data_r = $adb->pquery($sql, "");
				$num_rows_r=$adb->num_rows($data_r);
				
				$output.='
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td colspan="7" align="left" class="dvInnerHeader"><strong>Premium Name :'.$adb->query_result($data_r,0,'premium_name').'</strong> </td>
				  </tr>
				  <tr>
					<td width="5%" height="30px" align="center" class="dvtCellLabel"><strong>ลำดับ</strong></td>
					<td width="25%" align="left" class="dvtCellLabel"><strong>รหัสสินค้าพรีเมี่ยม</strong></td>
					<td width="40%" align="left" class="dvtCellLabel"><strong>ชื่อสินค้าพรีเมี่ยม</strong></td>
					<td width="10%" align="center" class="dvtCellLabel"><strong>หน่วยการนับ</strong></td>
					<td width="10%" align="center" class="dvtCellLabel"><strong>จำนวน</strong></td>
					<td width="10%" align="right" class="dvtCellLabel"><strong>ราคาสินค้า&nbsp;</strong></td>
				  </tr>';
				for($k=0;$k<$num_rows_r;$k++){
					$output.='
					<tr>
						<td height="30px" align="center" class="dvtCellInfo">'.($k+1).'</td>
						<td align="left" class="dvtCellInfo">'.$adb->query_result($data_r,$k,'premium_product_code').'</td>
						<td align="left" class="dvtCellInfo">'.$adb->query_result($data_r,$k,'premuimproduct_name').'</td>
						<td align="center" class="dvtCellInfo">'.$adb->query_result($data_r,$k,'uom').'</td>
						<td align="center" class="dvtCellInfo">'.number_format($adb->query_result($data_r,$k,'quantity'),0).'</td>
						<td align="right" class="dvtCellInfo">'.number_format($adb->query_result($data_r,$k,'listprice'),2).'</td>
					</tr>';
				}

				$output.='<br></table>';
			}
		$output.='
				</td>
		  </tr>';
		}
		$output.='
		</table>
		';

	return $output;
}

function getDetail_Tab2($module,$focus){
	global $log;
	$log->debug("Entering getDetail_Tab1(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$output = '';
	$crmid=$focus->id;
	
	//Set Up Product Price Detail
		$sql="
		select
		*
		from aicrm_inventory_protab2_dtl1
		left join aicrm_products on aicrm_products.productid=aicrm_inventory_protab2_dtl1.productid
		left join aicrm_productcf on aicrm_products.productid=aicrm_productcf.productid
		where 1
		and aicrm_inventory_protab2_dtl1.id='".$crmid."'
		";
		
		$data = $adb->pquery($sql, "");
		$num_rows=$adb->num_rows($data);

		$output.='
		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
		  <tr>
			<td colspan="4" align="left" class="dvInnerHeader"><strong>Set Up Condition</strong></td>
		  </tr>
		  <tr>
			<td width="25%" class="dvtCellLabel" align="right">&nbsp;เงื่อนไข</td>
			<td width="25%" class="dvtCellInfo">'.$adb->query_result($data,0,'hearder_fomula').'</td>
			<td width="25%" class="dvtCellLabel" align="right">&nbsp;จำนวน</td>
			<td width="25%" class="dvtCellInfo" style="border-right:1px solid #EDEDED;">'.number_format($adb->query_result($data,0,'hearder_qty'),0).'</td>
		  </tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
		  <tr>
			<td colspan="5" align="left" class="dvInnerHeader"><strong>Set Up Product Price Detail</strong></td>
		  </tr>
		  <tr>
			<td width="5%" height="30px" align="center" class="dvtCellLabel lineOnBottom"><strong>ลำดับ</strong></td>
			<td width="15%" align="left" class="dvtCellLabel lineOnBottom"><strong>รหัสสินค้า</strong></td>
			<td width="30%" align="left" class="dvtCellLabel lineOnBottom"><strong>ชื่อสินค้า</strong></td>
			<td width="5%" align="center" class="dvtCellLabel lineOnBottom"><strong>จำนวน</strong></td>
			<td width="45%" align="center" class="dvtCellLabel lineOnBottom"><strong>ชื่อพรีเมี่ยม</strong></td>
		  </tr>';
		
		for($i=0;$i<$num_rows;$i++){
		$output.='
		  <tr>
		  	<td height="30px" align="center" class="dvtCellInfo crmTableRow" style="vertical-align: middle;">'.($i+1).'</td>
			<td align="left" class="dvtCellInfo crmTableRow">'.$adb->query_result($data,$i,'product_code').'</td>
			<td align="left" class="dvtCellInfo crmTableRow">'.$adb->query_result($data,$i,'productname').'</td>
			<td align="center" class="dvtCellInfo crmTableRow">'.number_format($adb->query_result($data,$i,'quantity'),0).'</td>
			';
			if($i=="0"){
				$output.='<td align="left" class="dvtCellInfo crmTableRow"><div><strong>เงื่อนไข : </strong> '.$adb->query_result($data,$i,'campaign_fomula').'</div>';
				$sql="
				select
				aicrm_inventory_protab2_dtl2.premiumproductid
				from aicrm_inventory_protab2_dtl2
				where 1
				and aicrm_inventory_protab2_dtl2.id='".$crmid."'
				and aicrm_inventory_protab2_dtl2.row_id='".$adb->query_result($data,$i,'sequence_no')."'
				group by aicrm_inventory_protab2_dtl2.premiumproductid
				order by row_id
				";

				$data_rr = $adb->pquery($sql, "");
				$num_rows_rr=$adb->num_rows($data_rr);
				for($kk=0;$kk<$num_rows_rr;$kk++){
					$sql="
					select
					aicrm_inventory_protab2_dtl2.*
					,aicrm_premuimproduct.*
					,aicrm_premuimproductcf.*
					from aicrm_inventory_protab2_dtl2
					left join aicrm_premuimproduct on aicrm_premuimproduct.premuimproductid=aicrm_inventory_protab2_dtl2.premiumproductid
					left join aicrm_premuimproductcf on aicrm_premuimproductcf.premuimproductid=aicrm_premuimproduct.premuimproductid
					where 1
					and aicrm_inventory_protab2_dtl2.id='".$crmid."'
					and aicrm_inventory_protab2_dtl2.row_id='".$adb->query_result($data,$i,'sequence_no')."'
					and aicrm_inventory_protab2_dtl2.premiumproductid='".$adb->query_result($data_rr,$kk,'premiumproductid')."'
					";
					//echo $sql; echo "<br>";
					$data_r = $adb->pquery($sql, "");
					$num_rows_r=$adb->num_rows($data_r);
					
					$output.='
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td colspan="7" align="left" class="dvInnerHeader"><strong>Premium Name :'.$adb->query_result($data_r,0,'premuimproduct_name').'</strong> </td>
					  </tr>
					  <tr>
						<td width="5%" height="30px" align="center" class="dvtCellLabel"><strong>ลำดับ</strong></td>
						<td width="25%" align="left" class="dvtCellLabel"><strong>รหัสสินค้าพรีเมี่ยม</strong></td>
						<td width="40%" align="left" class="dvtCellLabel"><strong>ชื่อสินค้าพรีเมี่ยม</strong></td>
						<td width="10%" align="center" class="dvtCellLabel"><strong>หน่วยการนับ</strong></td>
						<td width="10%" align="center" class="dvtCellLabel"><strong>จำนวน</strong></td>
						<td width="10%" align="right" class="dvtCellLabel"><strong>ราคาสินค้า&nbsp;</strong></td>
					  </tr>';
					for($k=0;$k<$num_rows_r;$k++){
						$output.='
						<tr>
							<td height="30px" align="center" class="dvtCellInfo" >'.($k+1).'</td>
							<td align="left" class="dvtCellInfo">'.$adb->query_result($data_r,$k,'premium_product_code').'</td>
							<td align="left" class="dvtCellInfo">'.$adb->query_result($data_r,$k,'premuimproduct_name').'</td>
							<td align="center" class="dvtCellInfo">'.$adb->query_result($data_r,$k,'uom').'</td>
							<td align="center" class="dvtCellInfo">'.number_format($adb->query_result($data_r,$k,'quantity'),0).'</td>
							<td align="right" class="dvtCellInfo">'.number_format($adb->query_result($data_r,$k,'listprice'),2).'</td>
						</tr>';
					}
					$output.='<br></table>';
				}
				//$output.='<br></table>
				//</td>';
			}else{
				$output.='
				<td align="center" class="dvtCellInfo crmTableRow">&nbsp;<td>
				';
			}

		$output.='
		  </tr>';
		}
		$output.='
		</table>
		';

	return $output;
}

function getDetail_Tab3($module,$focus){
	global $log;
	$log->debug("Entering getDetail_Tab3(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$output = '';
	$crmid=$focus->id;
	//$sub_prod_query = $adb->pquery("SELECT set_tab from aicrm_promotion WHERE promotionid=? ",array($crmid));
	//$tab_id = $adb->query_result($sub_prod_query,0,'set_tab');

		$sql="
		select
		*
		from aicrm_inventory_protab3
		left join aicrm_inventory_protab3_dtl on aicrm_inventory_protab3_dtl.id=aicrm_inventory_protab3.id
		left join aicrm_products on aicrm_products.productid=aicrm_inventory_protab3_dtl.productid
		where 1
		and aicrm_inventory_protab3.id='".$crmid."'
		";
		//echo $sql; exit;
		$data = $adb->pquery($sql, "");
		$num_rows=$adb->num_rows($data);

		$output = '
			<table width="100%%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td colspan="4" align="left" class="dvInnerHeader"><strong>Set Up Discount Product Detail</strong></td>
			  </tr>
			  <tr>
				<td width="25%" class="dvtCellLabel" align="right">&nbsp;ชื่อส่วนลด</td>
				<td width="25%" class="dvtCellInfo" style="border-right:1px solid #EDEDED;">'.$adb->query_result($data,0,'disc_name').'</td>
				<td width="25%" class="dvtCellLabel" align="right">&nbsp;ประเภทส่วนลด</td>
				<td width="25%" class="dvtCellInfo" style="border-right:1px solid #EDEDED;">'.$adb->query_result($data,0,'disc_discount_type')." (".number_format($adb->query_result($data,0,'disc_dis_value'),2).')</td>
			  </tr>
			  <tr>
				<td class="dvtCellLabel" align="right">&nbsp;รวมราคา</td>
				<td class="dvtCellInfo">'.number_format($adb->query_result($data,0,'disc_total'),2).'</td>
				<td class="dvtCellLabel" align="right">&nbsp;ส่วนลด</td>
				<td class="dvtCellInfo" style="border-right:1px solid #EDEDED;">'.number_format($adb->query_result($data,0,'disc_discount'),2).'</td>
			  </tr>
			  <tr>
				<td class="dvtCellLabel" align="right">&nbsp;รวมราคาทั้งสิ้น</td>
				<td class="dvtCellInfo">'.number_format($adb->query_result($data,0,'disc_net'),2).'</td>
				<td class="dvtCellLabel">&nbsp;</td>
				<td class="dvtCellInfo" style="border-right:1px solid #EDEDED;">&nbsp;</td>
			  </tr>

			  <tr>
				<td colspan="4" align="left" class="dvInnerHeader"><b>'.$app_strings['LBL_ITEM_DETAILS'].'</b></td>
			  </tr>
			  <tr>
				<td colspan="4" align="center">
				   <table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="lvtCol" height="25px" width="40%">&nbsp;<strong>ชื่อสินค้า</strong></td>
						<td class="lvtCol" align="center" width="20%">&nbsp;<strong>หน่วยนับ</strong></td>
						<td class="lvtCol" align="center" width="20%">&nbsp;<strong>จำนวน</strong></td>
						<td class="lvtCol" width="20%" align="right" ><strong>ราคาต่อหน่วย</strong>&nbsp;</td>
					  </tr>
		';
				for($i=0;$i<$num_rows;$i++){
					$output.= '
					  <tr>
						<td class="crmTableRow small lineOnTop" height="25px">&nbsp;'.$adb->query_result($data,$i,'productname').'</td>
						<td class="crmTableRow small lineOnTop" align="center">&nbsp;'.$adb->query_result($data,$i,'uom').'</td>
						<td class="crmTableRow small lineOnTop" align="center">&nbsp;'.number_format($adb->query_result($data,$i,'quantity'),0).'</td>
						<td class="crmTableRow small lineOnTop" align="right" >'.number_format($adb->query_result($data,$i,'listprice'),2).'&nbsp;</td>
					  </tr>
					';
				}
		$output.= '
					</table>
				</td>
			  </tr>
			</table>';

	return $output;
}

function getDetail_PromotionTab($module,$focus){
	global $log;
	$log->debug("Entering getDetail_PromotionTab(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$output = '';
	$crmid=$focus->id;
		$sql="
		select
		aicrm_promotion.*,
		DATE_FORMAT(aicrm_promotion.startdate, '%d-%m-%Y') as startdate,
		DATE_FORMAT(aicrm_promotion.enddate, '%d-%m-%Y') as enddate
		from aicrm_inventory_campaign_promotion
		left join aicrm_promotion on aicrm_promotion.promotionid=aicrm_inventory_campaign_promotion.promotionid
		left join aicrm_promotioncf on aicrm_promotioncf.promotionid=aicrm_promotion.promotionid
		where 1
		and aicrm_inventory_campaign_promotion.id='".$crmid."'
		";
		
		$data = $adb->pquery($sql, "");
		$num_rows=$adb->num_rows($data);

		$output.='
		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
		  <tr>
			<td colspan="12" align="left" class="dvInnerHeader"><strong>Promotion Information</strong></td>
		  </tr>
		  <tr>
			<td width="5%" height="30px" align="center" class="dvtCellLabel lineOnBottom">ลำดับ</td>
			<td width="20%" align="left" class="dvtCellLabel lineOnBottom">ชื่อโปรโมชั่น</td>
			<td width="7%" align="left" class="dvtCellLabel lineOnBottom">ประเภทโปรโมชั่น</td>
			<td width="7%" align="center" class="dvtCellLabel lineOnBottom">วันที่เริ่มโปรโมชั่น</td>
			<td width="7%" align="center" class="dvtCellLabel lineOnBottom">วันที่สิ้นสุดโปรโมชั่น</td>

			<td width="7%" align="center" class="dvtCellLabel lineOnBottom">สถานะโปรโมชั่น</td>
			<td width="8%" align="center" class="dvtCellLabel lineOnBottom">ต้นทุน (งบประมาณ)</td>
			<td width="7%" align="center" class="dvtCellLabel lineOnBottom">ต้นทุน (จริง)</td>
			<td width="8%" align="center" class="dvtCellLabel lineOnBottom">จำนวนผู้เข้าร่วม (คาดหวัง)</td>
			<td width="8%" align="center" class="dvtCellLabel lineOnBottom">จำนวนผู้เข้าร่วม (จริง)</td>
			<td width="8%" align="center" class="dvtCellLabel lineOnBottom">รายได้ (คาดหวัง)</td>
			<td width="8%" align="center" class="dvtCellLabel lineOnBottom">รายได้ (จริง)</td>
		  </tr>';
		
		$budget_cost = 0 ;
		$actual_cost = 0 ;
		$expected_audience = 0 ;
		$actual_audience = 0 ;
		$expected_revenue = 0 ;
		$actual_revenue = 0 ;

		for($i=0;$i<$num_rows;$i++){
			
			if($adb->query_result($data,$i,'set_tab') ==1){
		       $promotiontype = 'Product Price';
		    }else if($adb->query_result($data,$i,'set_tab') ==2){
		       $promotiontype = 'Product';
		    }else if($adb->query_result($data,$i,'set_tab') ==3){
		       $promotiontype = 'Discount Product';
		    }
		$output.='
		  <tr>
		  	<td height="30px" align="center" class="dvtCellInfo crmTableRow" style="vertical-align: middle;">'.($i+1).'</td>
			<td align="left" class="dvtCellInfo crmTableRow">'.$adb->query_result($data,$i,'promotion_name').'</td>
			<td align="left" class="dvtCellInfo crmTableRow">'.$promotiontype.'</td>
			<td align="center" class="dvtCellInfo crmTableRow">'.$adb->query_result($data,$i,'startdate').'</td>
			<td align="center" class="dvtCellInfo crmTableRow">'.$adb->query_result($data,$i,'enddate').'</td>
			<td align="center" class="dvtCellInfo crmTableRow">'.$adb->query_result($data,$i,'promotion_status').'</td>
			<td align="right" class="dvtCellInfo crmTableRow">'.number_format($adb->query_result($data,$i,'budget_cost'),2).'</td>
			<td align="right" class="dvtCellInfo crmTableRow">'.number_format($adb->query_result($data,$i,'actual_cost'),2).'</td>
			<td align="right" class="dvtCellInfo crmTableRow">'.number_format($adb->query_result($data,$i,'expected_audience'),2).'</td>
			<td align="right" class="dvtCellInfo crmTableRow">'.number_format($adb->query_result($data,$i,'actual_audience'),2).'</td>
			<td align="right" class="dvtCellInfo crmTableRow">'.number_format($adb->query_result($data,$i,'expected_revenue'),2).'</td>
			<td align="right" class="dvtCellInfo crmTableRow">'.number_format($adb->query_result($data,$i,'actual_revenue'),2).'</td>
			';
		$output.='
		  </tr>';

		$budget_cost = ($budget_cost+$adb->query_result($data,$i,'budget_cost'));
		$actual_cost = ($actual_cost+$adb->query_result($data,$i,'actual_cost'));
		$expected_audience = ($expected_audience+$adb->query_result($data,$i,'expected_audience'));
		$actual_audience = ($actual_audience+$adb->query_result($data,$i,'actual_audience'));
		$expected_revenue = ($expected_revenue+$adb->query_result($data,$i,'expected_revenue'));
		$actual_revenue = ($actual_revenue+$adb->query_result($data,$i,'actual_revenue'));

		}
		//<td align="center" class="dvtCellInfo crmTableRow">'.number_format($adb->query_result($data,$i,'quantity'),0).'</td>
		$output.='<tr valign="top">
					<td width="5%" height="30px" class="crmTableRow dvtCellInfo" align="center" style="vertical-align: middle;">
						Total
					</td>
					<td width="20%" valign="top" class="crmTableRow small"></td>
					<td width="7%" align="left" class="crmTableRow small text-middle"></td>
					<td width="7%" align="center" class="crmTableRow small text-middle"></td>
					<td width="7%" align="center" class="crmTableRow small text-middle"></td>
					<td width="7%" align="left" class="crmTableRow small text-middle"></td>
					<td width="8%" align="right" class="crmTableRow dvtCellInfo text-middle">'.number_format($budget_cost,2).'</td>
					<td width="7%" align="right" class="crmTableRow dvtCellInfo text-middle">'.number_format($actual_cost,2).'</td>
					<td width="8%" align="right" class="crmTableRow dvtCellInfo text-middle">'.number_format($expected_audience,2).'</td>
					<td width="8%" align="right" class="crmTableRow dvtCellInfo text-middle">'.number_format($actual_audience,2).'</td>
					<td width="8%" align="right" class="crmTableRow dvtCellInfo text-middle">'.number_format($expected_revenue,2).'</td>
                    <td width="8%" align="right" class="crmTableRow dvtCellInfo text-middle">'.number_format($actual_revenue,2).'</td>
				</tr>';
		$output.='
		</table>
		';
		/*$output.='
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
			<tbody>
				
			</tbody>
		</table>';*/
		//Foter

	return $output;
}
function get_redemption($module,$focus){
	global $log;
	$log->debug("Entering get_redemption(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$output = '';
	$crmid=$focus->id;
	$sql="
	SELECT
	aicrm_redemption.*
	,aicrm_redemptioncf.*
	,aicrm_products.*
	,aicrm_inventoryproductrel.*
	FROM aicrm_redemption
	INNER JOIN aicrm_redemptioncf ON aicrm_redemptioncf.redemptionid = aicrm_redemption.redemptionid
	left join aicrm_account on aicrm_account.accountid=aicrm_redemption.accountid
	INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_redemption.redemptionid
	LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
	LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	left join aicrm_premiums on aicrm_premiums.premiumid=aicrm_redemption.premiumid
	left join aicrm_premiumscf on aicrm_premiums.premiumid=aicrm_premiumscf.premiumid
	left join aicrm_inventoryproductrel on aicrm_inventoryproductrel.id=aicrm_premiums.premiumid
	left join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrel.productid
	WHERE aicrm_crmentity.deleted = 0
	and aicrm_redemptioncf.cf_1754='".$crmid."' ";

	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);

	$output = '
			<table width="100%" border="0" cellspacing="0" cellpadding="0" >
			  <tr>
				<td colspan="4" align="left" class="dvInnerHeader"><strong>รายการแลกของรางวัล</strong></td>
			  </tr>
			  <tr>
				<td colspan="4" align="left" >';
	$output.= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="crmTable">
					  <tr bordercolor="#FFFFFF">
						<td class="lvtCol" height="25px" width="40%">&nbsp;<strong>Product</strong></td>
						<td class="lvtCol" align="center" width="20%">&nbsp;<strong>UOM</strong></td>
						<td class="lvtCol" align="center" width="20%">&nbsp;<strong>Qty</strong></td>
						<td class="lvtCol" width="20%" align="right" ><strong>List Price</strong>&nbsp;</td>
					  </tr>
				';
				for($i=0;$i<$num_rows;$i++){
					$output.= '
					  <tr bordercolor="#FFFFFF">
						<td class="crmTableRow small lineOnTop" height="25px">&nbsp;'.$adb->query_result($data,$i,'productname').'</td>
						<td class="crmTableRow small lineOnTop" align="center">&nbsp;'.$adb->query_result($data,$i,'uom').'</td>
						<td class="crmTableRow small lineOnTop" align="center">&nbsp;'.number_format($adb->query_result($data,$i,'cf_1431'),0).'</td>
						<td class="crmTableRow small lineOnTop" align="right" >0</td>
					  </tr>
					';
				}
				$output.= '
					</table>
				</td>
			  </tr>
			</table><br>
	';
	return $output;
}

function get_promotion($module,$focus){
	global $log;
	$log->debug("Entering getDetail_Tab3(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$output = '';
	$crmid=$focus->id;
	$sql="
	select
	aicrm_productcf.cf_1124
	,aicrm_products.productname
	,ifnull(tbt_promotion.qty*aicrm_inventoryproductrel.quantity,0) as qty
	,aicrm_inventoryproductrel.uom
	from tbt_promotion
	left join aicrm_premiums on aicrm_premiums.premiumid=tbt_promotion.premiumid
	left join aicrm_premiumscf on aicrm_premiumscf.premiumid=aicrm_premiums.premiumid
	left join aicrm_inventoryproductrel on aicrm_inventoryproductrel.id=aicrm_premiums.premiumid
	left join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrel.productid
	left join aicrm_productcf on aicrm_products.productid=aicrm_productcf.productid
	where 1
	and tbt_promotion.salesorderid='".$crmid."'

	";
	//echo $sql;
	$data = $adb->pquery($sql, "");
	$num_rows=$adb->num_rows($data);
	//echo $num_rows;
	$output = '
			<table width="100%" border="0" cellspacing="0" cellpadding="0" >
			  <tr>
				<td colspan="4" align="left" class="dvInnerHeader"><strong>รายการโปรโมชั่นที่ได้รับ</strong></td>
			  </tr>
			  <tr>
				<td colspan="4" align="left" >';
	$output.= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="crmTable">
					  <tr bordercolor="#FFFFFF">
						<td class="lvtCol" height="25px" width="40%">&nbsp;<strong>Product</strong></td>
						<td class="lvtCol" align="center" width="20%">&nbsp;<strong>UOM</strong></td>
						<td class="lvtCol" align="center" width="20%">&nbsp;<strong>Qty</strong></td>
						<td class="lvtCol" width="20%" align="right" ><strong>List Price</strong>&nbsp;</td>
					  </tr>
				';
				for($i=0;$i<$num_rows;$i++){
					$output.= '
					  <tr bordercolor="#FFFFFF">
						<td class="crmTableRow small lineOnTop" height="25px">&nbsp;'.$adb->query_result($data,$i,'productname').'</td>
						<td class="crmTableRow small lineOnTop" align="center">&nbsp;'.$adb->query_result($data,$i,'uom').'</td>
						<td class="crmTableRow small lineOnTop" align="center">&nbsp;'.number_format($adb->query_result($data,$i,'qty'),0).'</td>
						<td class="crmTableRow small lineOnTop" align="right" >0</td>
					  </tr>
					';
				}
				$output.= '
					</table>
				</td>
			  </tr>
			</table><br>
	';
	return $output;
}
/** This function returns a HTML output of associated aicrm_products for a given entity (Quotes,Invoice,Sales order or Purchase order)
  * Param $module - module name
  * Param $focus - module object
  * Return type string
  */

function getDetailAssociatedProducts($module,$focus)
{
  global $log;
  $log->debug("Entering getDetailAssociatedProducts(".$module.",".get_class($focus).") method ...");
  global $adb;
  global $mod_strings;
  global $theme;
  global $log;
  global $app_strings,$current_user;
  $theme_path="themes/".$theme."/";
  $image_path=$theme_path."images/";

  if($module != 'PurchaseOrder')
  {
	  $colspan = '2';
  }
  else
  {
	  $colspan = '1';
  }
  //Get the taxtype of this entity
  if($module != 'Branchs' && $module != 'Questionnaireanswer' && $module != 'Smartquestionnaire' && $module != 'Serial' && $module != 'Errors' && $module != 'Errorslist' && $module != 'Sparepart' && $module != 'Sparepartlist' && $module != 'Competitor' && $module != 'Opportunity' && $module != 'Campaigns' && $module != 'Activitys' && $module != 'KnowledgeBase' && $module != 'Quotation' && $module != 'PriceList' && $module != 'Job' && $module != 'HelpDesk' && $module != 'SmartSms' && $module !="Smartemail" && $module !="Faq" && $module !="Plant" && $module !="Deal" && $module !="Promotion" && $module !="Voucher" && $module !="Questionnaire" && $module !="Questionnairetemplate" && $module !="Announcement" && $module !="Promotionvoucher" && $module !="Competitorproduct" && $module !="Premuimproduct" && $module !="Servicerequest" && $module !="Redemption" && $module !="Point" && $module !="Seriallist" && $module !="Inspection" && $module !="Inspectiontemplate" && $module !="Service" && $module !="Expense" && $module !="Purchasesorder" && $module !="Samplerequisition" && $module !="Goodsreceive" && $module !="Marketingtools"){
	  $taxtype = getInventoryTaxType($module,$focus->id);
	  $currencytype = getInventoryCurrencyInfo($module, $focus->id);
  }
	  
  if($module != 'Branchs' && $module != 'Questionnaireanswer' && $module != 'Smartquestionnaire' && $module !="Serial" && $module !="Errors" && $module !="Errorslist" && $module !="Sparepart" && $module !="Sparepartlist" && $module !="Competitor" &&  $module !="Quotes" && $module != 'Opportunity' && $module != 'Campaigns' && $module != 'Activitys' &&  $module != 'KnowledgeBase' && $module != 'Quotation' && $module != 'PriceList' && $module != 'Job' && $module != 'SmartSms' && $module !="Smartemail" && $module !="Faq" && $module !="Plant" && $module !="Order" && $module !="Deal" && $module !="Promotion" && $module !="Voucher" && $module !="Questionnaire" && $module !="Questionnairetemplate" && $module !="Announcement" && $module !="Promotionvoucher" && $module !="Competitorproduct" && $module !="Premuimproduct" && $module !="Servicerequest" && $module !="Redemption" && $module !="Point" && $module !="Salesorder" && $module != 'Projects' && $module != 'Seriallist' && $module != 'Inspection' && $module != 'Inspectiontemplate' && $module !="Service" && $module !="Expense" && $module !="Purchasesorder" && $module !="Samplerequisition" && $module !="Goodsreceive" && $module !="Marketingtools"){
	  
	  $output = '';
	  //Header Rows
	  $output .= '
		<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable" id="proTab">
		 <tr valign="top">
		  <td colspan="'.$colspan.'" class="dvInnerHeader"><b>'.$app_strings['LBL_ITEM_DETAILS'].'</b></td>
		  <td class="dvInnerHeader" align="center" colspan="2"><b>'.
			  $app_strings['LBL_CURRENCY'].' : </b>'. getTranslatedCurrencyString($currencytype['currency_name']). ' ('. $currencytype['currency_symbol'] .')
		  </td>
		  <td class="dvInnerHeader" align="center" colspan="2"><b>'.
			  $app_strings['LBL_TAX_MODE'].' : </b>'.$app_strings[$taxtype].'
		  </td>
		 </tr>
		 <tr valign="top">
		  <td width=40% class="lvtCol"><font color="red">*</font>
			  <b>'.$app_strings['LBL_ITEM_NAME'].'</b>
		  </td>';

	  //Add Quantity in Stock column for SO, Quotes and Invoice
	  if($module == 'Quotes' || $module == 'Salesorder' || $module == 'Invoice')
		  $output .= '<td width=10% class="lvtCol"><b>'.$app_strings['LBL_QTY_IN_STOCK'].'</b></td>';

		  $output .= '
			  <td width=10% class="lvtCol"><b>'.$app_strings['LBL_TEST_BOX'].'</b></td>
			  <td width=10% class="lvtCol"><b>'.$app_strings['LBL_QTY'].'</b></td>
			  <td width=10% class="lvtCol" align="right"><b>'.$app_strings['LBL_LIST_PRICE'].'</b></td>
			  <td width=12% nowrap class="lvtCol" align="right"><b>'.$app_strings['LBL_TOTAL'].'</b></td>
			  <td width=13% valign="top" class="lvtCol" align="right"><b>'.$app_strings['LBL_NET_PRICE'].'</b></td>
			 </tr>
			  ';
	  // DG 15 Aug 2006
	  // Add "ORDER BY sequence_no" to retain add order on all inventoryproductrel items

	  if($module == 'Quotes' || $module == 'Order' || $module == 'Salesorder' )
	  {
		  $query="select case when aicrm_products.productid != '' then aicrm_products.productname else aicrm_service.service_name end as productname," .
				  " case when aicrm_products.productid != '' then 'Products' else 'Services' end as entitytype," .
				  " case when aicrm_products.productid != '' then aicrm_products.sellingprice else aicrm_service.unit_price end as unit_price," .
				  " case when aicrm_products.productid != '' then aicrm_products.stockavailable else 'NA' end as qtyinstock, aicrm_inventoryproductrel.* " .
				  " from aicrm_inventoryproductrel" .
				  " left join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrel.productid " .
				  " left join aicrm_service on aicrm_service.serviceid=aicrm_inventoryproductrel.productid " .
				  " left join aicrm_sparepart on aicrm_sparepart.sparepartid=aicrm_inventoryproductrel.productid " .
				  " where id=? ORDER BY sequence_no";
	  }
	  $result = $adb->pquery($query, array($focus->id));
	  $num_rows=$adb->num_rows($result);
	  $netTotal = '0.00';
	  for($i=1;$i<=$num_rows;$i++)
	  {
		  $sub_prod_query = $adb->pquery("SELECT productid from aicrm_inventorysubproductrel WHERE id=? AND sequence_no=?",array($focus->id,$i));
		  $subprodname_str='';
		  if($adb->num_rows($sub_prod_query)>0){
			  for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
				  $sprod_id = $adb->query_result($sub_prod_query,$j,'productid');
				  $sprod_name = getProductName($sprod_id);
				  $str_sep = "";
				  if($j>0) $str_sep = ":";
				  $subprodname_str .= $str_sep." - ".$sprod_name;
			  }
		  }
		  $subprodname_str = str_replace(":","<br>",$subprodname_str);

		  $productid=$adb->query_result($result,$i-1,'productid');
		  $entitytype=$adb->query_result($result,$i-1,'entitytype');
		  $productname=$adb->query_result($result,$i-1,'productname');
		  if($subprodname_str!='') $productname .= "<br/><span style='color:#C0C0C0;font-style:italic;'>".$subprodname_str."</span>";
		  $comment=$adb->query_result($result,$i-1,'comment');
		  $qtyinstock=$adb->query_result($result,$i-1,'qtyinstock');

		  $qty=$adb->query_result($result,$i-1,'quantity');
		  $unitprice=$adb->query_result($result,$i-1,'unit_price');
		  $listprice=$adb->query_result($result,$i-1,'listprice');
		  $total = $qty*$listprice;
		  //Product wise Discount calculation - starts
		  $discount_percent=$adb->query_result($result,$i-1,'discount_percent');
		  $discount_amount=$adb->query_result($result,$i-1,'discount_amount');
		  $totalAfterDiscount = $total;

		  $productDiscount = '0.00';
		  if($discount_percent != 'NULL' && $discount_percent != '')
		  {
			  $productDiscount = $total*$discount_percent/100;
			  $totalAfterDiscount = $total-$productDiscount;
			  //if discount is percent then show the percentage
			  $discount_info_message = "$discount_percent % of $total = $productDiscount";
		  }
		  elseif($discount_amount != 'NULL' && $discount_amount != '')
		  {
			  $productDiscount = $discount_amount;
			  $totalAfterDiscount = $total-$productDiscount;
			  $discount_info_message = $app_strings['LBL_DIRECT_AMOUNT_DISCOUNT']." = $productDiscount";
		  }
		  else
		  {
			  $discount_info_message = $app_strings['LBL_NO_DISCOUNT_FOR_THIS_LINE_ITEM'];
		  }
		  //Product wise Discount calculation - ends

		  $netprice = $totalAfterDiscount;
		  //Calculate the individual tax if taxtype is individual
		  if($taxtype == 'individual')
		  {
			  $taxtotal = '0.00';
			  $tax_info_message = $app_strings['LBL_TOTAL_AFTER_DISCOUNT']." = $totalAfterDiscount \\n";
			  $tax_details = getTaxDetailsForProduct($productid,'all');
			  for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
			  {
				  $tax_name = $tax_details[$tax_count]['taxname'];
				  $tax_label = $tax_details[$tax_count]['taxlabel'];
				  $tax_value = getInventoryProductTaxValue($focus->id, $productid, $tax_name);

				  $individual_taxamount = $totalAfterDiscount*$tax_value/100;
				  $taxtotal = $taxtotal + $individual_taxamount;
				  $tax_info_message .= "$tax_label : $tax_value % = $individual_taxamount \\n";
			  }
			  $tax_info_message .= "\\n ".$app_strings['LBL_TOTAL_TAX_AMOUNT']." = $taxtotal";
			  $netprice = $netprice + $taxtotal;
		  }

		  $sc_image_tag = '';
		  if ($entitytype == 'Services') {
			  $sc_image_tag = '<a href="index.php?module=ServiceContracts&action=EditView&service_id='.$productid.'&return_module='.$module.'&return_id='.$focus->id.'">' .
						  '<img border="0" src="'.aicrm_imageurl('handshake.gif', $theme).'" title="'. getTranslatedString('Add Service Contract').'" style="cursor: pointer;" align="absmiddle" />' .
						  '</a>';
		  }
		  //For Product Name
		  $output .= '
				 <tr valign="top">
				  <td class="crmTableRow small lineOnTop">
					  '.$productname.'&nbsp;'.$sc_image_tag.'
					  <br>'.$comment.'
				  </td>';
		  //Upto this added to display the Product name and comment
		  $output .= '<td class="crmTableRow small lineOnTop">'.number_format($qty, 2, '.', ',').'</td>';
		  $output .= '
			  <td class="crmTableRow small lineOnTop" align="right">
				  <table width="100%" border="0" cellpadding="5" cellspacing="0">
					 <tr>
					  <td align="right">'.number_format($listprice, 2, '.', ',').'</td>
					 </tr>
					 <tr>
						 <td align="right">(-)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$discount_info_message.'\'); ">'.$app_strings['LBL_DISCOUNT'].' : </a></b></td>
					 </tr>
					 <tr>
					  <td align="right" nowrap>'.$app_strings['LBL_TOTAL_AFTER_DISCOUNT'].' : </td>
					 </tr>';
		  if($taxtype == 'individual')
		  {
			  $output .= '
					 <tr>
						 <td align="right" nowrap>(+)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$tax_info_message.'\');">'.$app_strings['LBL_TAX'].' : </a></b></td>
					 </tr>';
		  }
		  $output .= '
				  </table>
			  </td>';

		  $output .= '
			  <td class="crmTableRow small lineOnTop" align="right">
				  <table width="100%" border="0" cellpadding="5" cellspacing="0">
					 <tr><td align="right">'.number_format($total, 2, '.', ',').'</td></tr>
					 <tr><td align="right">'.number_format($productDiscount, 2, '.', ',').'</td></tr>
					 <tr><td align="right" nowrap>'.number_format($totalAfterDiscount, 2, '.', ',').'</td></tr>';

		  if($taxtype == 'individual')
		  {
			  $output .= '<tr><td align="right" nowrap>'.number_format($taxtotal, 2, '.', ',').'</td></tr>';
		  }

		  $output .= '
				  </table>
			  </td>';
		  $output .= '<td class="crmTableRow small lineOnTop" valign="bottom" align="right">'.number_format($netprice, 2, '.', ',').'</td>';
		  $output .= '</tr>';

		  $netTotal = $netTotal + $netprice;
	  }

	  $output .= '</table>';
	  //$netTotal should be equal to $focus->column_fields['hdnSubTotal']
	  $netTotal = $focus->column_fields['hdnSubTotal'];
	  //Display the total, adjustment, S&H details
	  $output .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="crmTable">';
	  $output .= '<tr>';
	  $output .= '<td width="88%" class="crmTableRow small" align="right"><b>'.$app_strings['LBL_NET_TOTAL'].'</td>';
	  $output .= '<td width="12%" class="crmTableRow small" align="right"><b>'.number_format($netTotal, 2, '.', ',').'</b></td>';
	  $output .= '</tr>';
	  //Decide discount
	  $finalDiscount = '0.00';
	  $final_discount_info = '0';

	  //if($focus->column_fields['hdnDiscountPercent'] != '') - previously (before changing to prepared statement) the selected option (either percent or amount) will have value and the other remains empty. So we can find the non selected item by empty check. But now with prepared statement, the non selected option stored as 0
	  if($focus->column_fields['hdnDiscountPercent'] != '0')
	  {
		  $finalDiscount = ($netTotal*$focus->column_fields['hdnDiscountPercent']/100);
		  $final_discount_info = $focus->column_fields['hdnDiscountPercent']." % of $netTotal = $finalDiscount";
	  }
	  elseif($focus->column_fields['hdnDiscountAmount'] != '0')
	  {
		  $finalDiscount = $focus->column_fields['hdnDiscountAmount'];
		  $final_discount_info = $finalDiscount;
	  }

	  //Alert the Final Discount amount even it is zero
	  $final_discount_info = $app_strings['LBL_FINAL_DISCOUNT_AMOUNT']." = $final_discount_info";
	  $final_discount_info = 'onclick="alert(\''.$final_discount_info.'\');"';

	  $output .= '<tr>';
	  $output .= '<td align="right" class="crmTableRow small lineOnTop">(-)&nbsp;<b><a href="javascript:;" '.$final_discount_info.'>'.$app_strings['LBL_DISCOUNT'].'</a></b></td>';
	  $output .= '<td align="right" class="crmTableRow small lineOnTop">'.number_format($finalDiscount, 2, '.', ',').'</td>';
	  $output .= '</tr>';

	  if($taxtype == 'group')
	  {
		  $taxtotal = '0.00';
		  $final_totalAfterDiscount = $netTotal - $finalDiscount;
		  $tax_info_message = $app_strings['LBL_TOTAL_AFTER_DISCOUNT']." = $final_totalAfterDiscount \\n";
		  //First we should get all available taxes and then retrieve the corresponding tax values
		  $tax_details = getAllTaxes('available','','edit',$focus->id);
		  //if taxtype is group then the tax should be same for all products in aicrm_inventoryproductrel table
		  for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
		  {
			  $tax_name = $tax_details[$tax_count]['taxname'];
			  $tax_label = $tax_details[$tax_count]['taxlabel'];
			  $tax_value = $adb->query_result($result,0,$tax_name);
			  if($tax_value == '' || $tax_value == 'NULL')
				  $tax_value = '0.00';

			  $taxamount = ($netTotal-$finalDiscount)*$tax_value/100;
			  $taxtotal = $taxtotal + $taxamount;
			  $tax_info_message .= "$tax_label : $tax_value % = $taxamount \\n";
		  }
		  $tax_info_message .= "\\n ".$app_strings['LBL_TOTAL_TAX_AMOUNT']." = $taxtotal";

		  $output .= '<tr>';
		  $output .= '<td align="right" class="crmTableRow small">(+)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$tax_info_message.'\');">'.$app_strings['LBL_TAX'].'</a></b></td>';
		  $output .= '<td align="right" class="crmTableRow small">'.number_format($taxtotal, 2, '.', ',').'</td>';
		  $output .= '</tr>';
	  }

	  $shAmount = ($focus->column_fields['hdnS_H_Amount'] != '')?$focus->column_fields['hdnS_H_Amount']:'0.00';
	  $output .= '<tr>';
	  $output .= '<td align="right" class="crmTableRow small">(+)&nbsp;<b>'.$app_strings['LBL_SHIPPING_AND_HANDLING_CHARGES'].'</b></td>';
	  $output .= '<td align="right" class="crmTableRow small">'.number_format($shAmount, 2, '.', ',').'</td>';
	  $output .= '</tr>';

	  //calculate S&H tax
	  $shtaxtotal = '0.00';
	  //First we should get all available taxes and then retrieve the corresponding tax values
	  $shtax_details = getAllTaxes('available','sh','edit',$focus->id);
	  //if taxtype is group then the tax should be same for all products in aicrm_inventoryproductrel table
	  $shtax_info_message = $app_strings['LBL_SHIPPING_AND_HANDLING_CHARGE']." = $shAmount \\n";
	  for($shtax_count=0;$shtax_count<count($shtax_details);$shtax_count++)
	  {
		  $shtax_name = $shtax_details[$shtax_count]['taxname'];
		  $shtax_label = $shtax_details[$shtax_count]['taxlabel'];
		  $shtax_percent = getInventorySHTaxPercent($focus->id,$shtax_name);
		  $shtaxamount = $shAmount*$shtax_percent/100;
		  $shtaxtotal = $shtaxtotal + $shtaxamount;
		  $shtax_info_message .= "$shtax_label : $shtax_percent % = $shtaxamount \\n";
	  }
	  $shtax_info_message .= "\\n ".$app_strings['LBL_TOTAL_TAX_AMOUNT']." = $shtaxtotal";

	  $output .= '<tr>';
	  $output .= '<td align="right" class="crmTableRow small">(+)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$shtax_info_message.'\')">'.$app_strings['LBL_TAX_FOR_SHIPPING_AND_HANDLING'].'</a></b></td>';
	  $output .= '<td align="right" class="crmTableRow small">'.number_format($shtaxtotal, 2, '.', ',').'</td>';
	  $output .= '</tr>';

	  $adjustment = ($focus->column_fields['txtAdjustment'] != '')?$focus->column_fields['txtAdjustment']:'0.00';
	  $output .= '<tr>';
	  $output .= '<td align="right" class="crmTableRow small">&nbsp;<b>'.$app_strings['LBL_ADJUSTMENT'].'</b></td>';
	  $output .= '<td align="right" class="crmTableRow small">'.number_format($adjustment, 2, '.', ',').'</td>';
	  $output .= '</tr>';

	  $grandTotal = ($focus->column_fields['hdnGrandTotal'] != '')?$focus->column_fields['hdnGrandTotal']:'0.00';
	  $output .= '<tr>';
	  $output .= '<td align="right" class="crmTableRow small lineOnTop"><b>'.$app_strings['LBL_GRAND_TOTAL'].'</b></td>';
	  $output .= '<td align="right" class="crmTableRow small lineOnTop">'.number_format($grandTotal, 2, '.', ',').'</td>';
	  $output .= '</tr>';
	  $output .= '</table>';
  }
  
  else if($module =="Quotes" || $module =="Salesorder"){ 
	  
	  $output = '';
	  //Header Rows
	  $colspan=5;
	  /*'.$app_strings['LBL_ITEM_NAME'].'*/
	  //Add Quantity in Stock column for SO, Quotes and Invoice
	  /*if($module == 'Salesorder' || $module == 'Invoice'){
		  $output .= '<td width=10% class="lvtCol"><b>'.$app_strings['LBL_QTY_IN_STOCK'].'</b></td>';
	  }*/

	  /*'.$app_strings['LBL_QTY'].'*/
	  if($module =="Salesorder"){
		  $output .= '
		  <table width="100%"  border="0" align="center" cellpadding="7" cellspacing="0" class="crmTable" id="proTab">
			 <tr valign="top">
			  <td colspan="'.$colspan.'" class="dvInnerHeader"><b>'.$app_strings['LBL_ITEM_DETAILS'].'</b></td>
			  <td class="dvInnerHeader" align="center" colspan="2">
				  <b>Price type : </b>'.$focus->column_fields['pricetype'].'
			  </td>
			  <td class="dvInnerHeader" align="center" colspan="2"><b>'.
				  $app_strings['LBL_CURRENCY'].' : </b>'. getTranslatedCurrencyString($currencytype['currency_name']). ' ('. $currencytype['currency_symbol'] .')
			  </td>
			  <td class="dvInnerHeader" align="center" ><b>'.
				  $app_strings['LBL_TAX_MODE'].' : </b>'.$app_strings[$taxtype].'
			  </td>
			  <td class="dvInnerHeader" align="center" colspan="2" cellpadding="5" style ="display:none">&nbsp</td>		
			 </tr>
			 
			 <tr valign="top">
				 <td width=5% class="lvtCol" align="center">ลำดับ</td>
			  <td width=47%" class="lvtCol"><font color="red">*</font>
				  <b>ชื่อสินค้า</b>
			  </td>';

		  $output .= '
		  <td width=6% class="lvtCol" align="center"><b>จำนวนที่พร้อมขาย</b></td><td width=10% class="lvtCol" align="center"><b>หน่วยนับ</b></td>
		  <td width=10% class="lvtCol" align="center"><b>จำนวน</b></td>
		  <td class="lvtCol" style="display:none"  align="right"><b>Price List</b></td>		
		  <td class="lvtCol" style="display:none" align="center"><b>Price List <font color="red"> Exclude Vat</b></td>
		  <td class="lvtCol" style="display:none" align="center"><b>Price List <font color="red"> Include Vat</b></td>	
				  
		  <td width=10% class="lvtCol" align="center"><b>ราคาขาย</b></td>
		  <td width=12% nowrap class="lvtCol" align="center" colspan=""><b>รวม</b></td>
		  <td valign="top" style="display:none" class="lvtCol" align="center"><b>'.$app_strings['LBL_NET_PRICE'].'</b></td>
		 </tr>
		 ';
	  }else if($module =="Quotes"){

		$output .= '
		<table width="100%"  border="0" align="center" cellpadding="12" cellspacing="0" class="crmTable" id="proTab">
		   <tr valign="top">
			<td colspan="6" class="dvInnerHeader"><b>'.$app_strings['LBL_ITEM_DETAILS'].'</b></td>
			
			<td class="dvInnerHeader" align="center" colspan="4">
				<b>Price type : </b>'.$focus->column_fields['pricetype'].'
				<input type="hidden" name="pricetype" id="pricetype" value="'.$focus->column_fields['pricetype'].'">
			</td>
			<td class="dvInnerHeader" align="center" colspan="2" style="display:none;"><b>'.
				$app_strings['LBL_CURRENCY'].' : </b>'. getTranslatedCurrencyString($currencytype['currency_name']). ' ('. $currencytype['currency_symbol'] .')
			</td>
			<td class="dvInnerHeader" align="center" colspan="2"><b>'.
				$app_strings['LBL_TAX_MODE'].' : </b>'.$app_strings[$taxtype].'
			</td>
			<td class="dvInnerHeader" align="center" colspan="2" cellpadding="5" style ="display:none">&nbsp</td>		
		   </tr>
		   
		   <tr valign="top">
			<td width=3% valign="top" class="lvtCol" align="center"><b>ลำดับที่</b></td>
			<td width=20% class="lvtCol"><font color="red">* </font><b>รายการสินค้า</b></td>
			<td width=5% class="lvtCol" align="center"><strong>ขนาดบรรจุแผ่น</strong></td>
			<td width=5% class="lvtCol" align="center"><strong>ขนาดบรรจุ ตรม</strong></td>
			<td width=5% class="lvtCol" align="center"><strong>จำนวน</strong></td>
			<td width=5% class="lvtCol" align="center"><strong>หน่วยขาย</strong></td>
			<td width=5% class="lvtCol" align="center"><strong>จำนวนแผ่น</strong></td>
			<td width=5% class="lvtCol" align="center"><strong>จำนวน ตรม.</strong></td>
			<td width=6% class="lvtCol" align="center"><b>ราคาปกติ</b></td>
			<td width=5% class="lvtCol" align="center"><b>ราคาขาย</b></td>
			<td width=5% class="lvtCol" align="center"><b>ส่วนลด</b></td>
			<td width=8.5% nowrap class="lvtCol" align="center"><b>ราคารวม</b></td>
		</tr>
			';
	  }else{
		  $output .= '
		  <table width="100%"  border="0" align="center" cellpadding="11" cellspacing="0" class="crmTable" id="proTab">
			 <tr valign="top">
			  <td colspan="'.$colspan.'" class="dvInnerHeader"><b>'.$app_strings['LBL_ITEM_DETAILS'].'</b></td>
			  
			  <td class="dvInnerHeader" align="center" colspan="2">
				  <b>Price type : </b>'.$focus->column_fields['pricetype'].'
			  </td>
			  <td class="dvInnerHeader" align="center" colspan="2"><b>'.
				  $app_strings['LBL_CURRENCY'].' : </b>'. getTranslatedCurrencyString($currencytype['currency_name']). ' ('. $currencytype['currency_symbol'] .')
			  </td>
			  <td class="dvInnerHeader" align="center" colspan="2"><b>'.
				  $app_strings['LBL_TAX_MODE'].' : </b>'.$app_strings[$taxtype].'
			  </td>
			  <td class="dvInnerHeader" align="center" colspan="2" cellpadding="5" style ="display:none">&nbsp</td>		
			 </tr>
			 
			 <tr valign="top">
				<td width=5% class="lvtCol" align="center">ลำดับ</td>
			  	<td width=32%" class="lvtCol"><font color="red">*</font><b>รายการสินค้า</b></td>';

		  $output .= '
		  		<td width=7% class="lvtCol" align="center"><strong>ชนิดผิว</strong></td>
				<td width=7% class="lvtCol" align="center"><strong>ขนาด (มม.)</strong></td>
				<td width=7% class="lvtCol" align="center"><strong>ความหนา (มม.)</strong></td>
			
				<td width=7% class="lvtCol" align="center"><strong>จำนวน (หน่วย)</strong></td>
				<td width=7% class="lvtCol" align="center"><strong>หน่วยนับ</strong></td>

				<td width=7% class="lvtCol" align="center"><strong>รวมต้นทุนจริงเฉลี่ย</strong></td>
				<td width=7% class="lvtCol" align="center"><strong>ราคาคู่แข่ง</strong></td>

				<td class="lvtCol" style="display:none"  align="right"><b>Price List</b></td>		
				<td class="lvtCol" style="display:none" align="center"><b>Price List <font color="red"> Exclude Vat</b></td>
				<td class="lvtCol" style="display:none" align="center"><b>Price List <font color="red"> Include Vat</b></td>	
						  
				<td width=7% class="lvtCol" align="center"><b>ราคาขาย</b></td>
				<td width=12% nowrap class="lvtCol" align="center" colspan=""><b>รวม</b></td>
				<td valign="top" style="display:none" class="lvtCol" align="center"><b>'.$app_strings['LBL_NET_PRICE'].'</b></td>
			 </tr>
		  	';/*'.$app_strings['LBL_TOTAL'].'*/

		  // DG 15 Aug 2006
		  // Add "ORDER BY sequence_no" to retain add order on all inventoryproductrel items
	  }
	  
		/*$query="select case when aicrm_products.productid != '' then aicrm_products.productname else aicrm_service.servicename end as productname," .
			" case when aicrm_products.productid != '' then 'Products' else 'Services' end as entitytype," .
			" aicrm_products.*," .
			" case when aicrm_products.productid != '' then aicrm_products.stockavailable else 'NA' end as qtyinstock, aicrm_inventoryproductrel.* " .
			" from aicrm_inventoryproductrel" .
			" left join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrel.productid " .
			" left join aicrm_service on aicrm_service.serviceid=aicrm_inventoryproductrel.productid " .
			" left join aicrm_sparepart on aicrm_sparepart.sparepartid=aicrm_inventoryproductrel.productid " .
			" where id=? ORDER BY sequence_no";*/
				//case when aicrm_products.productid != '' then aicrm_products.productname else aicrm_service.service_name end as productname
			$query="select 
					case when aicrm_inventoryproductrel.module = 'Products' then aicrm_products.productname 
						 when aicrm_inventoryproductrel.module = 'Service' then aicrm_service.servicename
						 when aicrm_inventoryproductrel.module = 'Sparepart' then aicrm_sparepart.sparepart_name
						 else '' end as  productname ," .
					"case when aicrm_inventoryproductrel.module = 'Products' then aicrm_products.product_no 
						 when aicrm_inventoryproductrel.module = 'Service' then aicrm_service.service_no
						 when aicrm_inventoryproductrel.module = 'Sparepart' then aicrm_sparepart.sparepart_no
						 else '' end as  crmno ," .
			  " case when aicrm_products.productid != '' then 'Products' else 'Services' end as entitytype," .
			  " aicrm_products.*," .
			  " case when aicrm_products.productid != '' then aicrm_products.stockavailable else 'NA' end as qtyinstock, aicrm_inventoryproductrel.* " .
			  " from aicrm_inventoryproductrel" .
			  " left join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrel.productid " .
			  " left join aicrm_service on aicrm_service.serviceid=aicrm_inventoryproductrel.productid " .
			  " left join aicrm_sparepart on aicrm_sparepart.sparepartid=aicrm_inventoryproductrel.productid " .
			  " where id=? ORDER BY sequence_no";

	  $result = $adb->pquery($query, array($focus->id));
	  $num_rows=$adb->num_rows($result);
	  $netTotal = '0.00';
	   
	  if($module =="Salesorder"){
		  $sql_quotes = "select * from aicrm_salesorder where salesorderid=".$focus->id;
		  $query_quotes = mysql_query($sql_quotes);//$adb->pquery($sql_quotes,'');
		  $rs_quotes = mysql_fetch_array($query_quotes); //$adb->query_result($query_quotes, 0, 'discountTotal_final');
	  }else {
		  $sql_quotes = "select * from aicrm_quotes where quoteid=".$focus->id;
		  $query_quotes = mysql_query($sql_quotes);//$adb->pquery($sql_quotes,'');
		  $rs_quotes = mysql_fetch_array($query_quotes); //$adb->query_result($query_quotes, 0, 'discountTotal_final');
	  }
	  

	  $pquery = "
	  select
	  aicrm_account.*
	  ,aicrm_quotes.*
	  from aicrm_quotes
	  left join aicrm_account on aicrm_account.accountid=aicrm_quotes.accountid
	  where aicrm_quotes.quoteid = '".$focus->id."' ";
	  $data_q = $adb->pquery($pquery,'');
	  $pop_accountid=$adb->query_result($data_q, 0, "accountid");
	  $pricetype=$adb->query_result($data_q, 0, "pricetype");
	  $count_qty = 0;
	  $sum_netPrice = 0; // สำหรับคำนวณ sum_netPrice สำหรับ pricetype = "Exclude Vat"
	  for($i=1;$i<=$num_rows;$i++)
	  {
		  $sub_prod_query = $adb->pquery("SELECT productid from aicrm_inventorysubproductrel WHERE id=? AND sequence_no=?",array($focus->id,$i));
		  $subprodname_str='';
		  if($adb->num_rows($sub_prod_query)>0){
			  for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
				  $sprod_id = $adb->query_result($sub_prod_query,$j,'productid');
				  $sprod_name = getProductName($sprod_id);
				  $str_sep = "";
				  if($j>0) $str_sep = ":";
				  $subprodname_str .= $str_sep." - ".$sprod_name;
			  }
		  }
		  $subprodname_str = str_replace(":","<br>",$subprodname_str);

		  $productid=$adb->query_result($result,$i-1,'productid');
		  $entitytype=$adb->query_result($result,$i-1,'entitytype');
		  $productname=$adb->query_result($result,$i-1,'product_name');
		  $product_price_type=$adb->query_result($result,$i-1,'product_price_type');
		  $comment=$adb->query_result($result,$i-1,'comment');
		  $qtyinstock=$adb->query_result($result,$i-1,'qtyinstock');
		  $qty=$adb->query_result($result,$i-1,'quantity');
		  $unitprice=$adb->query_result($result,$i-1,'unit_price');
		  $listprice=$adb->query_result($result,$i-1,'listprice');
		  $listprice_inc=$adb->query_result($result,$i-1,'listprice_inc');
		  $listprice_exc=$adb->query_result($result,$i-1,'listprice_exc');
		  $pack_size=$adb->query_result($result,$i-1,'pack_size');
		  $test_box=$adb->query_result($result,$i-1,'test_box');
		  $uom=$adb->query_result($result,$i-1,'uom');

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
		  $selling_price_product=$adb->query_result($result,$i-1,'selling_price_product');
		  $selling_price_delivery=$adb->query_result($result,$i-1,'selling_price_delivery');
		  if ($selling_price_product === null || $selling_price_product === '') $selling_price_product = $selling_price;
		  if ($selling_price_delivery === null || $selling_price_delivery === '') $selling_price_delivery = 0;
		  $total = $qty*$listprice;

		  $package_size_sheet_per_box=$adb->query_result($result,$i-1,'package_size_sheet_per_box');
		  $package_size_sqm_per_box=$adb->query_result($result,$i-1,'package_size_sqm_per_box');
		  $box_quantity=$adb->query_result($result,$i-1,'box_quantity');
		  $sales_unit=$adb->query_result($result,$i-1,'sales_unit');
		  $sheet_quantity=$adb->query_result($result,$i-1,'sheet_quantity');
		  $sqm_quantity=$adb->query_result($result,$i-1,'sqm_quantity');
		  $regular_price=$adb->query_result($result,$i-1,'regular_price');
		  $product_discount=$adb->query_result($result,$i-1,'product_discount');

		  $standard_price=$adb->query_result($result,$i-1,'standard_price');
		  $last_price=$adb->query_result($result,$i-1,'last_price');

		  //Product wise Discount calculation - starts
		  $discount_percent=$adb->query_result($result,$i-1,'discount_percent');
		  $discount_amount=$adb->query_result($result,$i-1,'discount_amount');

		  $productcode=$adb->query_result($result,$i-1,'productcode');

		  $tax1=$adb->query_result($result,$i-1,'tax1');

		  $pricelist_type=$adb->query_result($result,$i-1,'pricelist_type');

		  //$products_businessplusno=$adb->query_result($result,$i-1,'products_businessplusno');
			  $crmno = $adb->query_result($result,$i-1,'crmno');
		  
		  $totalAfterDiscount = $total;
		  $productDiscount = '0.00';
		  if($discount_percent != 'NULL' && $discount_percent != '')
		  {
			  $productDiscount = $total*$discount_percent/100;
			  $totalAfterDiscount = $total-$productDiscount;
			  //if discount is percent then show the percentage
			  $discount_info_message = "$discount_percent % of $total = $productDiscount";
		  }
		  elseif($discount_amount != 'NULL' && $discount_amount != '')
		  {
			  $productDiscount = $discount_amount;
			  $totalAfterDiscount = $total-$productDiscount;
			  $discount_info_message = $app_strings['LBL_DIRECT_AMOUNT_DISCOUNT']." = $productDiscount";
		  }
		  else
		  {
			  $discount_info_message = $app_strings['LBL_NO_DISCOUNT_FOR_THIS_LINE_ITEM'];
		  }
		  //Product wise Discount calculation - ends

		  $netprice = $totalAfterDiscount;
		  //Calculate the individual tax if taxtype is individual
		  if($taxtype == 'individual')
		  {
			  $taxtotal = '0.00';
			  $tax_info_message = $app_strings['LBL_TOTAL_AFTER_DISCOUNT']." = $totalAfterDiscount \\n";
			  $tax_details = getTaxDetailsForProduct($productid,'all');
			  for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
			  {
				  $tax_name = $tax_details[$tax_count]['taxname'];
				  $tax_label = $tax_details[$tax_count]['taxlabel'];
				  $tax_value = getInventoryProductTaxValue($focus->id, $productid, $tax_name);

				  $individual_taxamount = $totalAfterDiscount*$tax_value/100;
				  $taxtotal = $taxtotal + $individual_taxamount;
				  $tax_info_message .= "Vat : $tax_value % = $individual_taxamount \\n";
			  }
			  $tax_info_message .= "\\n ".$app_strings['LBL_TOTAL_TAX_AMOUNT']." = $taxtotal";
			  $netprice = $netprice + $taxtotal;
		  }

		  $sc_image_tag = '';
		  /*if ($entitytype == 'Services') {
			  $sc_image_tag = '<a href="index.php?module=ServiceContracts&action=EditView&service_id='.$productid.'&return_module='.$module.'&return_id='.$focus->id.'">' .
				  '<img border="0" src="'.aicrm_imageurl('handshake.gif', $theme).'" title="'. getTranslatedString('Add Service Contract').'" style="cursor: pointer;" align="absmiddle" />' .
				  '</a>';
		  }*/

		  $foc_pattern = '[FOC]';
		  $match = preg_match('/'.$foc_pattern.'/', $comment);
		  if($match==1 && $listprice==0){
			  $comment = str_replace($foc_pattern, '', $comment);
			  $listprice = 'FOC';
		  }else{
			  if($listprice==0){

				  $listprice = '';
			  }else{
				 //$listprice = number_format($listprice, 2, '.', ',');
			  }
		  }

		  //For Product Name
		  $output .= '
				 <tr valign="top">
					<td class="crmTableRow small lineOnTop" align="center">'. $i .'</td>
				  	<td class="crmTableRow small lineOnTop">
					  '. $crmno.' : '.$productname.'&nbsp;'.$sc_image_tag.'
					  <br><br>'.$product_price_type.'
					  <br><br>'.$comment.'
				  </td>';
		  //Upto this added to display the Product name and comment

		  if($module != 'Quotes' && $module != 'Salesinvoice')
		  {
			$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($qtyinstock, 2, '.', ',').'</td>';

			$output .= '<td class="crmTableRow small lineOnTop product_finish" align="left">'.$product_finish.'</td>';
			$output .= '<td class="crmTableRow small lineOnTop product_size_mm" align="left">'.$product_size_mm.'</td>';
			$output .= '<td class="crmTableRow small lineOnTop product_thinkness" align="left">'.$product_thinkness.'</td>';

			if($qty>0){
				$output .= '<td class="crmTableRow small lineOnTop qty" align="right">'.number_format($qty, 2, '.', ',').'</td>';
				
			}else{
				$output .= '<td class="crmTableRow small lineOnTop" align="right">&nbsp;</td>';
			}
			
		  }

		  if($module =="Quotes" || $module =="Salesinvoice"){
			$output .= '<td class="crmTableRow small lineOnTop package_size_sheet_per_box" align="left">'.$package_size_sheet_per_box.'</td>';
			$output .= '<td class="crmTableRow small lineOnTop package_size_sqm_per_box" align="left">'.$package_size_sqm_per_box.'</td>';
			$output .= '<td class="crmTableRow small lineOnTop box_quantity" align="left">'.$box_quantity.'</td>';
			$output .= '<td class="crmTableRow small lineOnTop sales_unit" align="left">'.$sales_unit.'</td>';
			$output .= '<td class="crmTableRow small lineOnTop sheet_quantity" align="left">'.$sheet_quantity.'</td>';
			$output .= '<td class="crmTableRow small lineOnTop sheet_quantity" align="left">'.$sqm_quantity.'</td>';

			$output .= '<td class="crmTableRow small lineOnTop regular_price" align="right">'.number_format($regular_price, 2, '.', ',').'<br><br>'.$pricelist_type.'</td>';
			$output .= '<td class="crmTableRow small lineOnTop selling_price" align="right"><span class="small">ราคาขายไม่รวมค่าขนส่ง: '.number_format($selling_price_product, 2, '.', ',').'</span><br/><span class="small">ราคาค่าขนส่ง: '.number_format($selling_price_delivery, 2, '.', ',').'</span></td>';
			$output .= '<td class="crmTableRow small lineOnTop product_discount" align="right">'.number_format($product_discount, 2, '.', ',').'</td>';

			// คำนวณ netPrice สำหรับแต่ละรายการและรวมเป็น sum_netPrice
			$line_netPrice = 0;
			if($pricelist_type == "ราคาต่อตร.ม." && $sales_unit == "Box"){
				$net_price = round(($selling_price*$package_size_sqm_per_box), 2);
				$line_netPrice = $net_price*$box_quantity;
				$sum_netPrice += $line_netPrice;
				$output .= '<td class="crmTableRow small lineOnTop netPrice" align="right">'.number_format($line_netPrice, 2, '.', ',').'</td>';
			}elseif($pricelist_type == "ราคาต่อแผ่น" && $sales_unit == "Box"){
				$line_netPrice = ($selling_price*$box_quantity)*$package_size_sheet_per_box;
				$sum_netPrice += $line_netPrice;
				$output .= '<td class="crmTableRow small lineOnTop netPrice" align="right">'.number_format($line_netPrice, 2, '.', ',').'</td>';
			}elseif($pricelist_type == "ราคาต่อตร.ม." && ($sales_unit == "PCS" || $sales_unit == "Pcs" || $sales_unit == "PCS.")){
				$net_price = round(($selling_price*$box_quantity), 2) * ($package_size_sqm_per_box/$package_size_sheet_per_box);
				$line_netPrice = $net_price;
				$sum_netPrice += $line_netPrice;
				$output .= '<td class="crmTableRow small lineOnTop netPrice" align="right">'.number_format($line_netPrice, 2, '.', ',').'</td>';
			}elseif($pricelist_type == "ราคาต่อตร.ม." && ($sales_unit == "Sq.m." || $sales_unit == "SQ.M." || $sales_unit == "SQ.M")){
				$net_price = round(($selling_price*$box_quantity), 2) * ($package_size_sqm_per_box);
				$line_netPrice = $net_price;
				$sum_netPrice += $line_netPrice;
				$output .= '<td class="crmTableRow small lineOnTop netPrice" align="right">'.number_format($line_netPrice, 2, '.', ',').'</td>';
			}else{
				$line_netPrice = ($selling_price*$box_quantity);
				$sum_netPrice += $line_netPrice;
				$output .= '<td class="crmTableRow small lineOnTop netPrice" align="right">'.number_format($line_netPrice, 2, '.', ',').'</td>';
				
			}

			
			
			// $output .= '<td class="crmTableRow small lineOnTop product_unit" align="center">'.$product_unit.'</td>';
			// $output .= '<td class="crmTableRow small lineOnTop product_cost_avg" align="right">'.number_format($product_cost_avg, 2, '.', ',').'</td>';
		  }else{
			$output .= '<td class="crmTableRow small lineOnTop" align="center">'.$uom.'</td>';
			$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($product_cost_avg, 2, '.', ',').'</td>';
			$output .= '<td class="crmTableRow small lineOnTop" style="display:none"  align="right">'.number_format($standard_price, 2, '.', ',').'</td>';
		  }


		  if($module !="Quotes" && $module !="Salesinvoice"){
			if($qty>0){
				$output .= '<td class="crmTableRow small lineOnTop" style="display:none" align="right">'.number_format($listprice_exc, 2, '.', ',').'</td>';
			}else{
				$output .= '<td class="crmTableRow small lineOnTop" style="display:none" align="right">&nbsp;</td>';
			}

			if($qty>0){
				$output .= '<td class="crmTableRow small lineOnTop" style="display:none" align="right">'.number_format($listprice_inc, 2, '.', ',').'</td>';
			}else{
				$output .= '<td class="crmTableRow small lineOnTop" style="display:none" align="right">&nbsp;</td>';
			}

			$output .= '
			<td class="crmTableRow small lineOnTop" align="right">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
				   <tr>
				   ';
				   if($qty>0){
						if($listprice>0){
							$output .= '<td align="right">'.number_format($listprice, 2, '.', ',').'</td>';
						}else{
							//$output .= '<td align="center">FOC</td>';
							$output .= '<td align="right">0.00</td>';
						}
				   }else{
					   $output .= '<td align="center">&nbsp;</td>';
				   }
			$output .= '			
				   </tr>
				  ';

				  if($taxtype == 'individual')
				  {
					  $output .= '
							 <tr>
								 <td align="right" nowrap>(+)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$tax_info_message.'\');">Vat : </a></b></td>
							 </tr>';
				  }
				  $output .= '
						  </table>
					  </td>';
		
		  }


		 }

		 
	if($module !="Quotes" && $module !="Salesinvoice"){

		  $output .= '
			  <td class="crmTableRow small lineOnTop" align="right">
				  <table width="100%" border="0" cellpadding="0" cellspacing="0">';

		  if($qty>0){
			// if($module =="Quotes"){
			// 	if($selling_price>0){
			// 		$output .= '<tr><td align="right" nowrap>'.number_format(($selling_price*$qty), 2, '.', ',').'</td></tr>';
			// 	}else{
			// 		$output .= '<td align="right">0.00</td>';
			// 	}
			// }else{
				if($listprice>0){
					$output .= '<tr><td align="right" nowrap>'.number_format($totalAfterDiscount, 2, '.', ',').'</td></tr>';
				}else{
					$output .= '<td align="right">0.00</td>';
				}
			// }
		  }


		  if($taxtype == 'individual')
		  {
			  $output .= '<tr><td align="right" nowrap>'.number_format($taxtotal, 2, '.', ',').'</td></tr>';
		  }

		  $output .= '
				  </table>
			  </td>';
		  $output .= '<td class="crmTableRow small lineOnTop" style="display:none"  valign="bottom" align="right">'.number_format($netprice, 2, '.', ',').'</td>';
		  $output .= '</tr>';

		  $netTotal = $netTotal + $netprice;
		  $count_qty += $qty;
	}

	  $output .= '</table>';

	  $netTotal = $focus->column_fields['hdnSubTotal'];

	  //Display the total, adjustment, S&H details
	  $output .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="crmTable">';
	  
	  $output .= '<tr>';
	  $output .= '<td width="88%" class="crmTableRow small" align="right"><b>'.$app_strings['LBL_TOTAL_PRICE'].'</td>';
	  $output .= '<td width="12%" class="crmTableRow small" align="right"><b>'.number_format($netTotal, 2, '.', ',').'</b></td>';
	  $output .= '</tr>';

	  //Decide discount
	  $finalDiscount = '0.00';
	  $final_discount_info = '0';
	  //if($focus->column_fields['hdnDiscountPercent'] != '') - previously (before changing to prepared statement) the selected option (either percent or amount) will have value and the other remains empty. So we can find the non selected item by empty check. But now with prepared statement, the non selected option stored as 0
	  if($focus->column_fields['hdnDiscountPercent'] != '0')
	  {
		  $finalDiscount = ($netTotal*$focus->column_fields['hdnDiscountPercent']/100);
		  $final_discount_info = $focus->column_fields['hdnDiscountPercent']." % of $netTotal = $finalDiscount";
	  }
	  elseif($focus->column_fields['hdnDiscountAmount'] != '0')
	  {
		  $finalDiscount = $focus->column_fields['hdnDiscountAmount'];
		  $final_discount_info = $finalDiscount;
	  }

	  //Alert the Final Discount amount even it is zero
	  $final_discount_info = $app_strings['LBL_FINAL_DISCOUNT_AMOUNT']." = $final_discount_info";
	  $final_discount_info = 'onclick="alert(\''.$final_discount_info.'\');"';

	  //show hide discount
		  $price_type = $focus->column_fields['pricetype'];
		  if($price_type == 'Exclude Vat'){
			  $total_after_discount = number_format($netTotal - $finalDiscount, 2, '.', ',');
		  }else{
			  $total_net = (($netTotal - $finalDiscount)*100) / 107;
			  $total_after_discount = $focus->column_fields['hdnSubTotal'] - $focus->column_fields['hdnDiscountAmount'];
		  }

		  $output .= '<tr>';

		  if($module == 'Quotes' || $module == 'Salesinvoice'){
			$subtotal=$adb->query_result($data_q, 0, "subtotal");
			$discount_amount=$adb->query_result($data_q, 0, "discount_amount");
			// $subtotal=$adb->query_result($result,$i-1,'subtotal');
			// $discount_amount=$adb->query_result($result,$i-1,'discount_amount');
			// echo $subtotal; exit;

			if($module == 'Salesinvoice') {
				$pquery = "select salesinvoiceid, subtotal, discount_amount
					from aicrm_salesinvoice
					where salesinvoiceid = '".$focus->id."' ";
					$data_s = $adb->pquery($pquery,'');
				$subtotal=$adb->query_result($data_s, 0, "subtotal");
				$discount_amount=$adb->query_result($data_s, 0, "discount_amount");
			}

			if($price_type == 'Exclude Vat'){
				// สำหรับ pricetype = "Exclude Vat" ใช้ sum_netPrice เป็น Total After Discount
				$afterdis_final = $sum_netPrice;
				// คำนวณ Discount จาก TOTAL PRICE - Total After Discount
				$discount_calculated = $netTotal - $afterdis_final;
			}else{
				$vat = 1 + ($tax1 / 100);
				// Total After Discount (ก่อน VAT) ในหน้า Edit มาจากฐานในตัวแปรหน้าเว็บแล้วปัดเศษ
				// ดังนั้นเพื่อให้ได้เลขตรง (เช่น 3,732.10) เราคำนวณ "ส่วนลดท้ายบิล" ตามสูตรเดียวกับ Edit:
				//   ส่วนลดท้ายบิล = Total After Discount * discount_percent / 100
				// ถ้าไม่มี discount_percent (เคสเลือกแบบ amount) ค่อย fallback กลับเป็นการแปลงจาก hdn_bill_discount
				$afterdis_final = $rs_quotes['subtotal'];

				$discount_percent_final = isset($rs_quotes['discount_percent']) ? $rs_quotes['discount_percent'] : '';
				if($discount_percent_final !== '' && $discount_percent_final != '0'){
					$bill_discount_calculated = round(((float)$afterdis_final * (float)$discount_percent_final) / 100, 2, PHP_ROUND_HALF_UP);
					$total_after_bill_discount_calculated = round(((float)$afterdis_final - (float)$bill_discount_calculated), 2, PHP_ROUND_HALF_UP);
				}else{
					// Fallback: hdn_bill_discount เก็บเป็นแบบรวม VAT -> แปลงกลับเป็นก่อน VAT
					$bill_discount_calculated = ($vat != 0) ? ($rs_quotes['hdn_bill_discount'] / $vat) : $rs_quotes['hdn_bill_discount'];
					$bill_discount_calculated = round((float)$bill_discount_calculated, 2, PHP_ROUND_HALF_UP);
					$total_after_bill_discount_calculated = $rs_quotes['hdn_total_after_bill_discount'];
				}

				// discount_amount ใน DB เป็นแบบรวม VAT ให้แปลงเป็นก่อน VAT เพื่อแสดงให้ถูกต้อง
				$discount_calculated = ($vat != 0) ? ($rs_quotes['discount_amount'] / $vat) : $rs_quotes['discount_amount'];
			}
		  	$output .= '<td align="right" class="crmTableRow small lineOnTop">(-)&nbsp;<b><span>'.$app_strings['LBL_DISCOUNT'].'</span></b></td>';

			  // สำหรับ pricetype = "Exclude Vat" ใช้ Discount ที่คำนวณจาก TOTAL PRICE - Total After Discount
			  if($price_type == 'Exclude Vat'){
				  $output .= '<td align="right" class="crmTableRow small lineOnTop">'.number_format($discount_calculated, 2, '.', ',').'</td>';
			  }else{
				  $output .= '<td align="right" class="crmTableRow small lineOnTop">'.number_format($discount_calculated, 2, '.', ',').'</td>';
			  }
			  $output .= '</tr>';
	
			  $output .= '<tr>';
			  $output .= '<td align="right" class="crmTableRow small lineOnTop">Total After Discount</td>';
			  $output .= '<td align="right" class="crmTableRow small lineOnTop">'. number_format($afterdis_final, 2, '.', ',') .'</td>';
			  $output .= '</tr>';

			  $output .= '<tr>';
			  $output .= '<td align="right" class="crmTableRow small lineOnTop">ส่วนลดท้ายบิล</td>';
			  $output .= '<td align="right" class="crmTableRow small lineOnTop">'.number_format(isset($bill_discount_calculated) ? $bill_discount_calculated : $rs_quotes['hdn_bill_discount'], 2, '.', ',').'</td>';
			  $output .= '</tr>';

			  $output .= '<tr>';
			  $output .= '<td align="right" class="crmTableRow small lineOnTop">Total หลังลดท้ายบิล</td>';
			  $output .= '<td align="right" class="crmTableRow small lineOnTop">'.number_format(isset($total_after_bill_discount_calculated) ? $total_after_bill_discount_calculated : $rs_quotes['hdn_total_after_bill_discount'], 2, '.', ',').'</td>';
			  $output .= '</tr>';

			  $output .= '<tr>';
			  $output .= '<td align="right" class="crmTableRow small lineOnTop">มัดจำ</td>';
			  $output .= '<td align="right" class="crmTableRow small lineOnTop">'.number_format($rs_quotes['deposit_amount'], 2, '.', ',').'</td>';
			  $output .= '</tr>';

			  $output .= '<tr>';
			  $output .= '<td align="right" class="crmTableRow small lineOnTop">หลังหักมัดจำ</td>';
			  $output .= '<td align="right" class="crmTableRow small lineOnTop">'.number_format($rs_quotes['deposit_amount_after'], 2, '.', ',').'</td>';
			  $output .= '</tr>';
		  }else{
			$output .= '<td align="right" class="crmTableRow small lineOnTop">(-)&nbsp;<b><a href="javascript:;" '.$final_discount_info.'>'.$app_strings['LBL_DISCOUNT'].'</a></b></td>';

			$output .= '<td align="right" class="crmTableRow small lineOnTop">'.number_format($rs_quotes['discountTotal_final'], 2, '.', ',').'</td>';
			$output .= '</tr>';

			$output .= '<tr>';
			$output .= '<td align="right" class="crmTableRow small lineOnTop">Total After Discount</td>';
			$output .= '<td align="right" class="crmTableRow small lineOnTop">'. number_format($rs_quotes['total_after_discount'], 2, '.', ',') .'</td>';
			$output .= '</tr>';
		  }
		  
		  

		  

	  $grandTotal = ($focus->column_fields['hdnGrandTotal'] != '')?$focus->column_fields['hdnGrandTotal']:'0.00';

	  if($taxtype == 'group')
	  {
		  $taxtotal = '0.00';

		  $price_type = $focus->column_fields['pricetype'];
		  // echo $price_type; exit();
		  /*$dis_invat = $focus->column_fields['cf_4352'];
		  $show_vat = $focus->column_fields['cf_4052'];
		  $show_dis = $focus->column_fields['cf_4126'];*/

		  //$tax_info_message = $app_strings['LBL_TOTAL_AFTER_DISCOUNT']." = ".number_format($final_totalAfterDiscount,2)." \\n";
		  //First we should get all available taxes and then retrieve the corresponding tax values
		  $tax_details = getAllTaxes('available','','edit',$focus->id);
		  //if taxtype is group then the tax should be same for all products in aicrm_inventoryproductrel table
		  // echo "<pre>"; print_r($result); echo "</pre>"; exit;
		  for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
		  {
			  $tax_name = $tax_details[$tax_count]['taxname'];
			  $tax_label = $tax_details[$tax_count]['taxlabel'];
			  $tax_value = $adb->query_result($result,0,$tax_name);
			  if($tax_value == '' || $tax_value == 'NULL')
				  $tax_value = '0.00';


			  if($price_type == 'Exclude Vat'){
				  $taxamount = ($netTotal-$finalDiscount)*$tax_value/100;
			  }else{
				  $taxamount = ((($netTotal - $finalDiscount)*100) / (100+$tax_value))*$tax_value/100;
			  }
			  $taxtotal = $taxtotal + $taxamount;
			  $tax_info_message .= "Vat : $tax_value % = ".number_format($taxamount,2);
		  }

		  if($module=="Quotes"){
			$tax_info_message = "Vat : $tax_value % = ".number_format($rs_quotes['tax_final'], 2, '.', ',');
		  }

		  
			//show hide tax
			$output .= '<tr>';
			$output .= '<td align="right" class="crmTableRow small">(+)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$tax_info_message.'\');">Vat</a></b></td>';
			$output .= '<td align="right" class="crmTableRow small">'.number_format($rs_quotes['tax_final'], 2, '.', ',').'</td>';
			$output .= '</tr>';
		  
			  
	  }

	  if($module =="Quotes"){
		$output .= '<tr>';
		$output .= '<td align="right" class="crmTableRow small lineOnTop">Total Net Amount including VAT</td>';
		$output .= '<td align="right" class="crmTableRow small lineOnTop">'. number_format($rs_quotes['total_without_vat'], 2, '.', ',') .'</td>';
		$output .= '</tr>';

		$output .= '<tr>';
		$output .= '<td align="right" class="crmTableRow small lineOnTop">คูปองส่วนลด (บาท)</td>';
		$output .= '<td align="right" class="crmTableRow small lineOnTop">'. number_format($rs_quotes['discount_coupon'], 2, '.', ',') .'</td>';
		$output .= '</tr>';
	  }

	  /*$shAmount = ($focus->column_fields['hdnS_H_Amount'] != '')?$focus->column_fields['hdnS_H_Amount']:'0.00';
	  $output .= '<tr>';
	  $output .= '<td align="right" class="crmTableRow small">(+)&nbsp;<b>'.$app_strings['LBL_SHIPPING_AND_HANDLING_CHARGES'].'</b></td>';
	  $output .= '<td align="right" class="crmTableRow small">'.number_format($shAmount, 3, '.', ',').'</td>';
	  $output .= '</tr>';*/

	  /*//calculate S&H tax
	  $shtaxtotal = '0.00';
	  //First we should get all available taxes and then retrieve the corresponding tax values
	  $shtax_details = getAllTaxes('available','sh','edit',$focus->id);
	  //if taxtype is group then the tax should be same for all products in aicrm_inventoryproductrel table
	  $shtax_info_message = $app_strings['LBL_SHIPPING_AND_HANDLING_CHARGE']." = $shAmount \\n";
	  for($shtax_count=0;$shtax_count<count($shtax_details);$shtax_count++)
	  {
		  $shtax_name = $shtax_details[$shtax_count]['taxname'];
		  $shtax_label = $shtax_details[$shtax_count]['taxlabel'];
		  $shtax_percent = getInventorySHTaxPercent($focus->id,$shtax_name);
		  $shtaxamount = $shAmount*$shtax_percent/100;
		  $shtaxtotal = $shtaxtotal + $shtaxamount;
		  $shtax_info_message .= "$shtax_label : $shtax_percent % = $shtaxamount \\n";
	  }
	  $shtax_info_message .= "\\n ".$app_strings['LBL_TOTAL_TAX_AMOUNT']." = $shtaxtotal";

	  $output .= '<tr>';
	  $output .= '<td align="right" class="crmTableRow small">(+)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$shtax_info_message.'\')">'.$app_strings['LBL_TAX_FOR_SHIPPING_AND_HANDLING'].'</a></b></td>';
	  $output .= '<td align="right" class="crmTableRow small">'.number_format($shtaxtotal, 3, '.', ',').'</td>';
	  $output .= '</tr>';

	  $adjustment = ($focus->column_fields['txtAdjustment'] != '')?$focus->column_fields['txtAdjustment']:'0.00';
	  $output .= '<tr>';
	  $output .= '<td align="right" class="crmTableRow small">&nbsp;<b>'.$app_strings['LBL_ADJUSTMENT'].'</b></td>';
	  $output .= '<td align="right" class="crmTableRow small">'.number_format($adjustment, 3, '.', ',').'</td>';
	  $output .= '</tr>';*/

	  $output .= '<tr>';
	  $output .= '<td align="right" class="crmTableRow small lineOnTop"><b>'.$app_strings['LBL_GRAND_TOTAL'].'</b></td>';
	  $output .= '<td align="right" class="crmTableRow small lineOnTop" id="hdn_grandTotal" data-id="'.$grandTotal.'">'.number_format($grandTotal, 2, '.', ',').'</td>';
	  $output .= '</tr>';
	  $output .= '</table>';

  }
  else if($module == 'Projects'){
	  $output = '';
	  //Header Rows
	  $output .= '
	  <table width="100%"  border="0" align="center" cellpadding="10" cellspacing="0" class="crmTable" id="proTab">
	  <tr>
		<td colspan="10" class="dvInnerHeader"><strong>Item Details</strong>   </tr>
		  <tr valign="top">
			  <td width=5% class="lvtCol" align="left"><strong>ลำดับ</strong></td>
			  <td width=40% class="lvtCol"><b>ชื่อสินค้า</b></td>
			  <td width="8%" align="center" class="lvtCol"><strong>ประเภทสินค้า</strong></td>
			  <td width=5% class="lvtCol" align="center"><strong>หน่วย</strong></td>
			  <td width=8% align="center" class="lvtCol"><strong>จำนวนประมาณการ</strong></td>
			  <td width=6% align="center" class="lvtCol"><strong>จำนวนจริง</strong></td>
			  <td width=6% align="center" class="lvtCol"><strong>จำนวนส่ง</strong></td>
			  <td width="10%" align="center" class="lvtCol"><strong>ราคาขาย</td>
			  <td width=9% align="center" class="lvtCol"><strong>ราคารวม</strong></td>
		  </tr>
		  ';
	  if($module == 'Projects')
	  {
		  $query="select case when aicrm_products.productid != '' then aicrm_products.productname else aicrm_service.service_name end as productname," .
				  " case when aicrm_products.productid != '' then 'Products' else 'Services' end as entitytype," .
				  " case when aicrm_products.productid != '' then aicrm_products.sellingprice else aicrm_service.unit_price end as unit_price," .
				  " case when aicrm_products.productid != '' then aicrm_products.stockavailable else 'NA' end as qtyinstock, aicrm_inventoryproductrel.* ," .
				  " aicrm_products.productcategory " .
				  " from aicrm_inventoryproductrel" .
				  " left join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrel.productid " .
				  " left join aicrm_service on aicrm_service.serviceid=aicrm_inventoryproductrel.productid " .
				  " where id=? ORDER BY sequence_no";
	  }

	  $result = $adb->pquery($query, array($focus->id));
	  $num_rows=$adb->num_rows($result);

	  for($i=1;$i<=$num_rows;$i++){
		  $subprodname_str='';
		  if($adb->num_rows($sub_prod_query)>0){
			  for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
				  $sprod_id = $adb->query_result($sub_prod_query,$j,'productid');
				  $sprod_name = getProductName($sprod_id);
				  $str_sep = "";
				  if($j>0) $str_sep = ":";
				  $subprodname_str .= $str_sep." - ".$sprod_name;
			  }
		  }
		  $subprodname_str = str_replace(":","<br>",$subprodname_str);

		  $productid=$adb->query_result($result,$i-1,'productid');
		  $entitytype=$adb->query_result($result,$i-1,'entitytype');
		  $productname=$adb->query_result($result,$i-1,'productname');

		  $productcategory=$adb->query_result($result,$i-1,'productcategory');

		  if($subprodname_str!='') $productname .= "<br/><span style='color:#C0C0C0;font-style:italic;'>".$subprodname_str."</span>";
		  $comment=$adb->query_result($result,$i-1,'comment');
		  $qtyinstock=$adb->query_result($result,$i-1,'qtyinstock');
		  $qty=$adb->query_result($result,$i-1,'quantity');
		  $unitprice=$adb->query_result($result,$i-1,'unit_price');
		  $listprice=$adb->query_result($result,$i-1,'listprice');
		  $problem=$adb->query_result($result,$i-1,'problem');
		  $cause=$adb->query_result($result,$i-1,'cause');
		  $protect=$adb->query_result($result,$i-1,'protect');
		  $startdt=$adb->query_result($result,$i-1,'startdt');
		  $tik_status=$adb->query_result($result,$i-1,'tik_status');
		  $lot_no=$adb->query_result($result,$i-1,'lot_no');
		  $status1=$adb->query_result($result,$i-1,'status1');
		  $status2=$adb->query_result($result,$i-1,'status2');
		  $status3=$adb->query_result($result,$i-1,'status3');
		  $premium_code=$adb->query_result($result,$i-1,'premium_code');
		  $score=$adb->query_result($result,$i-1,'score');

		  $uom=$adb->query_result($result,$i-1,'uom');
		  //echo $lot_no."<br>";
		  $listprice_total = $adb->query_result($result,$i-1,'listprice_total');
		  $line=$adb->query_result($result,$i-1,'line');
		  $type=$adb->query_result($result,$i-1,'type');

		  $quantity_act=$adb->query_result($result,$i-1,'quantity_act');
		  $quantity_ship=$adb->query_result($result,$i-1,'quantity_ship');
		  $status_dtl=$adb->query_result($result,$i-1,'status_dtl');

		  $status = $adb->query_result($result,$i-1,'status');
		  $quantity_remain=$adb->query_result($result,$i-1,'quantity_remain');
		  $total = $qty*$listprice;

		  $products_businessplusno = $adb->query_result($result,$i-1,'products_businessplusno');
		  
		  //Product wise Discount calculation - starts
		  $discount_percent=$adb->query_result($result,$i-1,'discount_percent');
		  $discount_amount=$adb->query_result($result,$i-1,'discount_amount');
		  $totalAfterDiscount = $total;
		  if($complete=="0"){

			  $complete="N";
		  }else{
			  $complete="Y";
		  }
		  $output .= '<tr valign="top">';
		  $output .= '<td class="crmTableRow small lineOnTop" valign="top" align="left">'.($i).'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop">'.$productname.'<br>'.$comment.'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="left">'.$productcategory.'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="center">'.$uom.'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($qty, 0, '.', ',').'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($quantity_act, 0, '.', ',').'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($quantity_ship, 0, '.', ',').'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($listprice, 2, '.', ',').'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($listprice_total, 2, '.', ',').'</td>';
		  $output .= '</tr>';
	  }
	  $output .= '</table>';
  }
  else if($module == 'Campaigns'){
	  $output = '';
	  //Header Rows
	  $output .= '
	  <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable" id="proTab">
	  <tr>
	<td colspan="4" class="dvInnerHeader"><strong>รายละเอียดของรางวัล</strong>   </tr>
		  <tr valign="top">
			  <td width=50% class="lvtCol"><font color="red">*</font><b>ชื่อของรางวัล</b></td>
			  <td width=25% class="lvtCol" align="center"><b>รหัสของรางวัล</b></td>
			  <td width=25% class="lvtCol" align="right"><b>จำนวนคะแนน</b></td>
		  </tr>
		  ';

	  // DG 15 Aug 2006
	  // Add "ORDER BY sequence_no" to retain add order on all inventoryproductrel items

	  if($module == 'Campaigns')
	  {
		  $query="select case when aicrm_premiums.premiumid != '' then aicrm_premiums.premium_name else aicrm_service.service_name end as productname," .
					  " case when aicrm_premiums.premiumid != '' then 'premiums' else 'Services' end as entitytype," .
					  "  aicrm_inventoryproductrel.* " .
					  " from aicrm_inventoryproductrel" .
					  " left join aicrm_premiums on aicrm_premiums.premiumid=aicrm_inventoryproductrel.productid " .
					  " left join aicrm_service on aicrm_service.serviceid=aicrm_inventoryproductrel.productid " .
					  " where id=? ORDER BY sequence_no";
	  }
	  
	  $result = $adb->pquery($query, array($focus->id));
	  $num_rows=$adb->num_rows($result);

	  for($i=1;$i<=$num_rows;$i++){
		  $productid=$adb->query_result($result,$i-1,'productid');
		  $entitytype=$adb->query_result($result,$i-1,'entitytype');
		  $productname=$adb->query_result($result,$i-1,'productname');
		  if($subprodname_str!='') $productname .= "<br/><span style='color:#C0C0C0;font-style:italic;'>".$subprodname_str."</span>";
		  $comment=$adb->query_result($result,$i-1,'comment');
		  $qtyinstock=$adb->query_result($result,$i-1,'qtyinstock');
		  $qty=$adb->query_result($result,$i-1,'quantity');
		  $unitprice=$adb->query_result($result,$i-1,'unit_price');
		  $listprice=$adb->query_result($result,$i-1,'listprice');
		  $problem=$adb->query_result($result,$i-1,'problem');
		  $cause=$adb->query_result($result,$i-1,'cause');
		  $protect=$adb->query_result($result,$i-1,'protect');
		  $startdt=$adb->query_result($result,$i-1,'startdt');
		  $tik_status=$adb->query_result($result,$i-1,'tik_status');
		  $lot_no=$adb->query_result($result,$i-1,'lot_no');
		  $status1=$adb->query_result($result,$i-1,'status1');
		  $status2=$adb->query_result($result,$i-1,'status2');
		  $status3=$adb->query_result($result,$i-1,'status3');
		  $premium_code=$adb->query_result($result,$i-1,'premium_code');
		  $score=$adb->query_result($result,$i-1,'score');
		  $line=$adb->query_result($result,$i-1,'line');
		  $type=$adb->query_result($result,$i-1,'type');
		  $total = $qty*$listprice;

		  //Product wise Discount calculation - starts
		  $discount_percent=$adb->query_result($result,$i-1,'discount_percent');
		  $discount_amount=$adb->query_result($result,$i-1,'discount_amount');
		  $totalAfterDiscount = $total;
		  if($complete=="0"){
			  $complete="N";
		  }else{
			  $complete="Y";
		  }
		  $output .= '<tr valign="top">';
		  $output .= '<td class="crmTableRow small lineOnTop">	'.$productname.'&nbsp;'.$sc_image_tag.'<br>'.$comment.'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="center">&nbsp;'.$premium_code.'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($score, 2, '.', ',').'</td>';
		  $output .= '</tr>';
	  }
	  $output .= '</table>';
  }
  else if($module == 'PriceList'){
	  $output = '';
	  //Header Rows
	  $output .= '
	  <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable" id="proTab">
	  <tr>
	
	  <td colspan="6" class="dvInnerHeader"><strong>Item Details</strong>   </tr>
		  <tr valign="top">
			  <td width=50% class="lvtCol"><font color="red">* </font><b>ชื่อสินค้า</b></td>
			  <td width=10% class="lvtCol" align="center"><b>สถานะของสินค้า</b></td>
			  <td width=10% class="lvtCol" align="center"><b>ประเภทสินค้า</b></td>
			  <td width=10% class="lvtCol" align="center"><b>หน่วยการนับ</b></td>
			  <td width=10% class="lvtCol" align="center"><b>ราคาขาย</b>
			  <td width=10% class="lvtCol" align="center"><b>สกุลเงิน</b>
		  </tr>
		  ';
	  /*if($module == 'PriceList')
	  {*/
	  	/*case when aicrm_products.productid != '' then aicrm_products.productname 
		  			else aicrm_service.service_name end as productname,*/
		  $query="select 
		  			case when aicrm_inventoryproductrel.module = 'Products' then aicrm_products.productname 
		  				 when aicrm_inventoryproductrel.module = 'Service' then aicrm_service.service_name 
		  				 when aicrm_inventoryproductrel.module = 'Sparepart' then aicrm_sparepart.sparepart_name 
		  				 else '' end as productname,
		  			" .
				  " aicrm_products.productcategory ," .
				  //" aicrm_products.productstatus ," .
				  " case when aicrm_inventoryproductrel.module = 'Products' then aicrm_products.productstatus 
		  				 when aicrm_inventoryproductrel.module = 'Service' then ''
		  				 when aicrm_inventoryproductrel.module = 'Sparepart' then aicrm_sparepart.sparepart_status 
		  				 else '' end as productstatus," .
				  " aicrm_products.unit ," .
				  " aicrm_inventoryproductrel.* " .
				  " from aicrm_inventoryproductrel" .
				  " left join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrel.productid " .
				  " left join aicrm_service on aicrm_service.serviceid=aicrm_inventoryproductrel.productid " .
				  " left join aicrm_sparepart on aicrm_sparepart.sparepartid=aicrm_inventoryproductrel.productid " .
				  " where id=? ORDER BY sequence_no";
	  /*}*/
	  //echo $query;
	  $result = $adb->pquery($query, array($focus->id));
	  $num_rows=$adb->num_rows($result);

	  for($i=1;$i<=$num_rows;$i++){
		  $productid=$adb->query_result($result,$i-1,'productid');
		  $entitytype=$adb->query_result($result,$i-1,'entitytype');
		  $productname=$adb->query_result($result,$i-1,'productname');
		  if($subprodname_str!='') $productname .= "<br/><span style='color:#C0C0C0;font-style:italic;'>".$subprodname_str."</span>";
		  $comment=$adb->query_result($result,$i-1,'comment');
		  $listprice=$adb->query_result($result,$i-1,'listprice');
	
			  $rel_module=$adb->query_result($result,$i-1,'module');

		  //$productstatus = ($adb->query_result($result,$i-1,'productstatus') == '1') ? 'Active' : 'InActive';
			  if($rel_module == 'Products'){
			  	$productstatus = ($adb->query_result($result,$i-1,'productstatus') == '1') ? 'Active' : 'InActive';
			  }else if($rel_module == 'Sparepart'){
			  	$productstatus = $adb->query_result($result,$i-1,'productstatus');
			  }else{
			  	$productstatus =  '';
			  }
		  $monetary=$adb->query_result($result,$i-1,'monetary');

		  $productcategory= $adb->query_result($result,$i-1,'productcategory');
		  $unit=$adb->query_result($result,$i-1,'unit');
		
		  $standard_price=$adb->query_result($result,$i-1,'standard_price');

		  $line=$adb->query_result($result,$i-1,'line');
		  $type=$adb->query_result($result,$i-1,'type');
		  $total = $qty*$listprice;

		  //Product wise Discount calculation - starts
		  $totalAfterDiscount = $total;
		  if($complete=="0"){
			  $complete="N";
		  }else{
			  $complete="Y";
		  }
		  $output .= '<tr valign="top">';
		  $output .= '<td class="crmTableRow small lineOnTop">'.$productname.'&nbsp;'.$sc_image_tag.'<br>'.$comment.'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="left">&nbsp;'.$productstatus.'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="left">&nbsp;'.$productcategory.'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="left">&nbsp;'.$unit.'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="right">&nbsp;'.number_format($listprice, 2, '.', ',').'</td>';
		  $output .= '<td class="crmTableRow small lineOnTop" align="left">&nbsp;'.$monetary.'</td>';
		  $output .= '</tr>';
	  }
	  $output .= '</table>';
  }
  else if($module =="Order"){ 
	  
	  $output = '';
	  $colspan=2;
	  $output .= '

	  <table width="100%"  border="0" align="center" cellpadding="7" cellspacing="0" class="crmTable" id="proTab">
		 <tr valign="top">
		  <td colspan="'.$colspan.'" class="dvInnerHeader"><b>'.$app_strings['LBL_ITEM_DETAILS'].'</b></td>
		  <td class="dvInnerHeader" align="center" colspan="2">
			  <b>Price type : </b>'.$focus->column_fields['pricetype'].'
		  </td>
		  <td class="dvInnerHeader" align="center" colspan="3"><b>'.
			  $app_strings['LBL_CURRENCY'].' : </b>'. getTranslatedCurrencyString($currencytype['currency_name']). ' ('. $currencytype['currency_symbol'] .')
		  </td>
		  <td class="dvInnerHeader" align="center" ><b>'.
			  $app_strings['LBL_TAX_MODE'].' : </b>'.$app_strings[$taxtype].'
		  </td>
		  <td class="dvInnerHeader" align="center" colspan="2" cellpadding="5" style ="display:none">&nbsp</td>		
		 </tr>
		 
		 <tr valign="top">
			 <td width=5% class="lvtCol">No</td>
		  <td width=60%" class="lvtCol"><font color="red">*</font>
			  <b>'.$app_strings['LBL_ITEM_NAME'].'</b>
		  </td>';

	  //Add Quantity in Stock column for SO, Quotes and Invoice
	  if($module == 'Salesorder' || $module == 'Invoice' || $module == 'Projects')
		  $output .= '<td width=10% class="lvtCol"><b>'.$app_strings['LBL_QTY_IN_STOCK'].'</b></td>';

	  $output .= '
		  <td width=5% class="lvtCol" align="center"><b>UOM</b></td>
		  <td width=5% class="lvtCol" align="center"><b>'.$app_strings['LBL_QTY'].'</b></td>
		  <td width=5% class="lvtCol" style="display:none"  align="right"><b>Price List</b></td>		
		  <td width=5% class="lvtCol" align="center"><b>Price List <font color="red"> Exclude Vat</b></td>
		  <td width=5% class="lvtCol" align="center"><b>Price List <font color="red"> Include Vat</b></td>	
				  
		  <td width=5% class="lvtCol" align="center"><b>Selling Price <font color="red">'.$focus->column_fields['pricetype'].'</b></td>
		  <td width=15% nowrap class="lvtCol" align="center" colspan=""><b>'.$app_strings['LBL_TOTAL'].'</b></td>
		  <td width=10% valign="top" style="display:none" class="lvtCol" align="center"><b>'.$app_strings['LBL_NET_PRICE'].'</b></td>
		 </tr>
		  ';

	  // DG 15 Aug 2006
	  // Add "ORDER BY sequence_no" to retain add order on all inventoryproductrel items
		  $query="select case when aicrm_products.productid != '' then aicrm_products.productname else aicrm_service.service_name end as productname," .
				  " case when aicrm_products.productid != '' then 'Products' else 'Services' end as entitytype," .
				  " case when aicrm_products.productid != '' then aicrm_products.unit_price else aicrm_service.unit_price end as unit_price,aicrm_products.*," .
				  " case when aicrm_products.productid != '' then aicrm_products.qtyinstock else 'NA' end as qtyinstock, aicrm_inventoryproductrel.* " .
				  " from aicrm_inventoryproductrel" .
				  " left join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrel.productid " .
				  " left join aicrm_service on aicrm_service.serviceid=aicrm_inventoryproductrel.productid " .
				  " where id=? ORDER BY sequence_no";

	  $result = $adb->pquery($query, array($focus->id));
	  $num_rows=$adb->num_rows($result);
	  $netTotal = '0.00';
	  //echo $query;
	  $sql_quotes = "select * from aicrm_order where orderid=".$focus->id;
	  $query_quotes = mysql_query($sql_quotes);//$adb->pquery($sql_quotes,'');
	  $rs_quotes = mysql_fetch_array($query_quotes); //$adb->query_result($query_quotes, 0, 'discountTotal_final');

	  $pquery = "
	  select
	  aicrm_account.*
	  from aicrm_order
	  left join aicrm_account on aicrm_account.accountid=aicrm_order.accountid
	  where aicrm_order.orderid = '".$focus->id."' ";
	  $data_q = $adb->pquery($pquery,'');
	  $pop_accountid=$adb->query_result($data_q, 0, "accountid");
	  for($i=1;$i<=$num_rows;$i++)
	  {
		  $sub_prod_query = $adb->pquery("SELECT productid from aicrm_inventorysubproductrel WHERE id=? AND sequence_no=?",array($focus->id,$i));
		  $subprodname_str='';
		  if($adb->num_rows($sub_prod_query)>0){
			  for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
				  $sprod_id = $adb->query_result($sub_prod_query,$j,'productid');
				  $sprod_name = getProductName($sprod_id);
				  $str_sep = "";
				  if($j>0) $str_sep = ":";
				  $subprodname_str .= $str_sep." - ".$sprod_name;
			  }
		  }
		  $subprodname_str = str_replace(":","<br>",$subprodname_str);

		  $productid=$adb->query_result($result,$i-1,'productid');
		  $entitytype=$adb->query_result($result,$i-1,'entitytype');
		  $productname=$adb->query_result($result,$i-1,'product_name');
		  $comment=$adb->query_result($result,$i-1,'comment');
		  $qtyinstock=$adb->query_result($result,$i-1,'qtyinstock');
		  $qty=$adb->query_result($result,$i-1,'quantity');
		  $unitprice=$adb->query_result($result,$i-1,'unit_price');
		  $listprice=$adb->query_result($result,$i-1,'listprice');
		  $listprice_inc=$adb->query_result($result,$i-1,'listprice_inc');
		  $listprice_exc=$adb->query_result($result,$i-1,'listprice_exc');
		  $pack_size=$adb->query_result($result,$i-1,'pack_size');
		  $test_box=$adb->query_result($result,$i-1,'test_box');
		  $uom=$adb->query_result($result,$i-1,'uom');
		  $total = $qty*$listprice;

		  $standard_price=$adb->query_result($result,$i-1,'standard_price');
		  $last_price=$adb->query_result($result,$i-1,'last_price');

		  //Product wise Discount calculation - starts
		  $discount_percent=$adb->query_result($result,$i-1,'discount_percent');
		  $discount_amount=$adb->query_result($result,$i-1,'discount_amount');

		  $productcode=$adb->query_result($result,$i-1,'productcode');

		  $products_businessplusno=$adb->query_result($result,$i-1,'products_businessplusno');

		  $totalAfterDiscount = $total;
		  $productDiscount = '0.00';
		  if($discount_percent != 'NULL' && $discount_percent != '')
		  {
			  $productDiscount = $total*$discount_percent/100;
			  $totalAfterDiscount = $total-$productDiscount;
			  //if discount is percent then show the percentage
			  $discount_info_message = "$discount_percent % of $total = $productDiscount";
		  }
		  elseif($discount_amount != 'NULL' && $discount_amount != '')
		  {
			  $productDiscount = $discount_amount;
			  $totalAfterDiscount = $total-$productDiscount;
			  $discount_info_message = $app_strings['LBL_DIRECT_AMOUNT_DISCOUNT']." = $productDiscount";
		  }
		  else
		  {
			  $discount_info_message = $app_strings['LBL_NO_DISCOUNT_FOR_THIS_LINE_ITEM'];
		  }
		  //Product wise Discount calculation - ends

		  $netprice = $totalAfterDiscount;
		  //Calculate the individual tax if taxtype is individual
		  if($taxtype == 'individual')
		  {
			  $taxtotal = '0.00';
			  $tax_info_message = $app_strings['LBL_TOTAL_AFTER_DISCOUNT']." = $totalAfterDiscount \\n";
			  $tax_details = getTaxDetailsForProduct($productid,'all');
			  for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
			  {
				  $tax_name = $tax_details[$tax_count]['taxname'];
				  $tax_label = $tax_details[$tax_count]['taxlabel'];
				  $tax_value = getInventoryProductTaxValue($focus->id, $productid, $tax_name);

				  $individual_taxamount = $totalAfterDiscount*$tax_value/100;
				  $taxtotal = $taxtotal + $individual_taxamount;
				  $tax_info_message .= "Vat : $tax_value % = $individual_taxamount \\n";
			  }
			  $tax_info_message .= "\\n ".$app_strings['LBL_TOTAL_TAX_AMOUNT']." = $taxtotal";
			  $netprice = $netprice + $taxtotal;
		  }

		  $sc_image_tag = '';
		  if ($entitytype == 'Services') {
			  $sc_image_tag = '<a href="index.php?module=ServiceContracts&action=EditView&service_id='.$productid.'&return_module='.$module.'&return_id='.$focus->id.'">' .
				  '<img border="0" src="'.aicrm_imageurl('handshake.gif', $theme).'" title="'. getTranslatedString('Add Service Contract').'" style="cursor: pointer;" align="absmiddle" />' .
				  '</a>';
		  }

		  $foc_pattern = '[FOC]';
		  $match = preg_match('/'.$foc_pattern.'/', $comment);
		  if($match==1 && $listprice==0){
			  $comment = str_replace($foc_pattern, '', $comment);
			  $listprice = 'FOC';
		  }else{
			  if($listprice==0){

				  $listprice = '';
			  }else{
				 //$listprice = number_format($listprice, 2, '.', ',');
			  }
		  }

		  //For Product Name
		  $output .= '
				 <tr valign="top">
					 <td class="crmTableRow small lineOnTop">'. $i .'</td>
				  <td class="crmTableRow small lineOnTop">
					  '. $products_businessplusno.': '.$productname.'&nbsp;'.$sc_image_tag.'
					  <br>'.$comment.'
				  </td>';
		  //Upto this added to display the Product name and comment

		  if($module != 'Quotes' || $module != 'Order')

		  {
			  $output .= '<td class="crmTableRow small lineOnTop">'.number_format($qtyinstock, 2, '.', ',').'</td>';
		  }

		  $output .= '<td class="crmTableRow small lineOnTop" align="center">'.$uom.'</td>';

		  if($qty>0){
			  $output .= '<td class="crmTableRow small lineOnTop" align="center">'.number_format($qty, 2, '.', ',').'</td>';

		  }else{
			  $output .= '<td class="crmTableRow small lineOnTop" align="center">&nbsp;</td>';
		  }
		  $output .= '<td class="crmTableRow small lineOnTop" style="display:none"  align="right">'.number_format($standard_price, 2, '.', ',').'</td>';

		  if($qty>0){
			  $output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($listprice_exc, 2, '.', ',').'</td>';
		  }else{
			 $output .= '<td class="crmTableRow small lineOnTop" align="right">&nbsp;</td>';
		  }

		  if($qty>0){
			  $output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($listprice_inc, 2, '.', ',').'</td>';
		  }else{
			 $output .= '<td class="crmTableRow small lineOnTop" align="right">&nbsp;</td>';
		  }
		  
		  $output .= '
			  <td class="crmTableRow small lineOnTop" align="right">
				  <table width="100%" border="0" cellpadding="0" cellspacing="0">
					 <tr>
					 ';
					 if($qty>0){
						  if($listprice>0){
							  $output .= '<td align="right">'.number_format($listprice, 2, '.', ',').'</td>';
						  }else{
							  $output .= '<td align="center">FOC</td>';
						  }
					 }else{
						 $output .= '<td align="center">&nbsp;</td>';
					 }

		  $output .= '			
					 </tr>
					';

		  if($taxtype == 'individual')
		  {
			  $output .= '
					 <tr>
						 <td align="right" nowrap>(+)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$tax_info_message.'\');">Vat : </a></b></td>
					 </tr>';
		  }
		  $output .= '
				  </table>
			  </td>';

		  $output .= '
			  <td class="crmTableRow small lineOnTop" align="right">
				  <table width="100%" border="0" cellpadding="0" cellspacing="0">';

		  if($qty>0){
			  if($listprice>0){
				  $output .= '<tr><td align="right" nowrap>'.number_format($totalAfterDiscount, 2, '.', ',').'</td></tr>';
			  }else{
				  $output .= '<td align="right"></td>';
			  }
		  }


		  if($taxtype == 'individual')
		  {
			  $output .= '<tr><td align="right" nowrap>'.number_format($taxtotal, 2, '.', ',').'</td></tr>';
		  }

		  $output .= '
				  </table>
			  </td>';
		  $output .= '<td class="crmTableRow small lineOnTop" style="display:none"  valign="bottom" align="right">'.number_format($netprice, 2, '.', ',').'</td>';
		  $output .= '</tr>';

		  $netTotal = $netTotal + $netprice;
	  }

	  $output .= '</table>';

	  $netTotal = $focus->column_fields['hdnSubTotal'];

	  //Display the total, adjustment, S&H details
	  $output .= '<table width="100%" border="0" cellspacing="0" cellpadding="5" class="crmTable">';
	  $output .= '<tr>';
	  $output .= '<td width="88%" class="crmTableRow small" align="right"><b>'.$app_strings['LBL_TOTAL_PRICE'].'</td>';
	  $output .= '<td width="12%" class="crmTableRow small" align="right"><b>'.number_format($netTotal, 2, '.', ',').'</b></td>';
	  $output .= '</tr>';

	  //Decide discount
	  $finalDiscount = '0.00';
	  $final_discount_info = '0';
	  //if($focus->column_fields['hdnDiscountPercent'] != '') - previously (before changing to prepared statement) the selected option (either percent or amount) will have value and the other remains empty. So we can find the non selected item by empty check. But now with prepared statement, the non selected option stored as 0
	  if($focus->column_fields['hdnDiscountPercent'] != '0')
	  {
		  $finalDiscount = ($netTotal*$focus->column_fields['hdnDiscountPercent']/100);
		  $final_discount_info = $focus->column_fields['hdnDiscountPercent']." % of $netTotal = $finalDiscount";
	  }
	  elseif($focus->column_fields['hdnDiscountAmount'] != '0')
	  {
		  $finalDiscount = $focus->column_fields['hdnDiscountAmount'];
		  $final_discount_info = $finalDiscount;
	  }

	  //Alert the Final Discount amount even it is zero
	  $final_discount_info = $app_strings['LBL_FINAL_DISCOUNT_AMOUNT']." = $final_discount_info";
	  $final_discount_info = 'onclick="alert(\''.$final_discount_info.'\');"';

	  //show hide discount
		  $price_type = $focus->column_fields['pricetype'];
		  if($price_type == 'Exclude Vat'){
			  $total_after_discount = number_format($netTotal - $finalDiscount, 2, '.', ',');
		  }else{
			  $total_net = (($netTotal - $finalDiscount)*100) / 107;
			  $total_after_discount = $focus->column_fields['hdnSubTotal'] - $focus->column_fields['hdnDiscountAmount'];
		  }

		  $output .= '<tr>';
		  $output .= '<td align="right" class="crmTableRow small lineOnTop">(-)&nbsp;<b><a href="javascript:;" '.$final_discount_info.'>'.$app_strings['LBL_DISCOUNT'].'</a></b></td>';
		  $output .= '<td align="right" class="crmTableRow small lineOnTop">'.number_format($rs_quotes['discountTotal_final'], 2, '.', ',').'</td>';
		  $output .= '</tr>';

		  $output .= '<tr>';
		  $output .= '<td align="right" class="crmTableRow small lineOnTop">Total After Discount</td>';
		  $output .= '<td align="right" class="crmTableRow small lineOnTop">'. number_format($rs_quotes['total_after_discount'], 2, '.', ',') .'</td>';
		  $output .= '</tr>';

	  $grandTotal = ($focus->column_fields['hdnGrandTotal'] != '')?$focus->column_fields['hdnGrandTotal']:'0.00';
	  if($taxtype == 'group')
	  {
		  $taxtotal = '0.00';

		  $price_type = $focus->column_fields['pricetype'];
		  $dis_invat = $focus->column_fields['cf_4352'];
		  $show_vat = $focus->column_fields['cf_4052'];
		  $show_dis = $focus->column_fields['cf_4126'];
		  $tax_details = getAllTaxes('available','','edit',$focus->id);
		  //if taxtype is group then the tax should be same for all products in aicrm_inventoryproductrel table
		  for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
		  {
			  $tax_name = $tax_details[$tax_count]['taxname'];
			  $tax_label = $tax_details[$tax_count]['taxlabel'];
			  $tax_value = $adb->query_result($result,0,$tax_name);
			  if($tax_value == '' || $tax_value == 'NULL')
				  $tax_value = '0.00';


			  if($price_type == 'Exclude Vat'){
				  $taxamount = ($netTotal-$finalDiscount)*$tax_value/100;
			  }else{
				  $taxamount = ((($netTotal - $finalDiscount)*100) / (100+$tax_value))*$tax_value/100;
			  }
			  $taxtotal = $taxtotal + $taxamount;
			  $tax_info_message .= "Vat : $tax_value % = ".number_format($taxamount,2);
		  }
			  //show hide tax
			  $output .= '<tr>';
			  $output .= '<td align="right" class="crmTableRow small">(+)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$tax_info_message.'\');">Vat</a></b></td>';
			  $output .= '<td align="right" class="crmTableRow small">'.number_format($rs_quotes['tax_final'], 2, '.', ',').'</td>';
			  $output .= '</tr>';
	  }

	  $output .= '<tr>';
	  $output .= '<td align="right" class="crmTableRow small lineOnTop"><b>'.$app_strings['LBL_GRAND_TOTAL'].'</b></td>';
	  $output .= '<td align="right" class="crmTableRow small lineOnTop">'.number_format($grandTotal, 2, '.', ',').'</td>';
	  $output .= '</tr>';
	  $output .= '</table>';

  }

  $log->debug("Exiting getDetailAssociatedProducts method ...");
  return $output;

}

function getTimelineLists($module,$focus,$condition=null)
{
	global $log;
	$log->debug("Entering getTimelineLists(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $current_user;
	global $app_strings;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	$cur_tab_id = getTabid($module);
	// vtlib customization: Do not picklist module which are set as in-active
	
	$sql1 = "select aicrm_activity_timeline.id,
		aicrm_activity_timeline.crmid,
		aicrm_activity_timeline.action,
		aicrm_activity_timeline.userid,
		aicrm_activity_timeline.createdtime,
		DATE_FORMAT(aicrm_activity_timeline.createdtime,'%d %M, %Y at %T %p') as format_date ,
		aicrm_activity_timeline_detail.id,
		aicrm_activity_timeline_detail.fieldid,
		aicrm_activity_timeline_detail.old_value,
		aicrm_activity_timeline_detail.new_value,
		aicrm_field.fieldlabel,
		aicrm_field.uitype, 
		aicrm_users.user_name,
		CASE WHEN ifnull(aicrm_attachments.path,'') != '' THEN concat(aicrm_attachments.path,aicrm_attachments.attachmentsid,'_',aicrm_attachments.name)  ELSE '' END AS profile_image
		from aicrm_activity_timeline 
		LEFT JOIN aicrm_activity_timeline_detail on aicrm_activity_timeline_detail.activitytimelineid = aicrm_activity_timeline.id
		LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_activity_timeline_detail.fieldid
		INNER JOIN aicrm_users on aicrm_users.id = aicrm_activity_timeline.userid
		LEFT JOIN aicrm_salesmanattachmentsrel ON aicrm_salesmanattachmentsrel.smid = aicrm_activity_timeline.userid
		LEFT JOIN aicrm_attachments ON aicrm_attachments.attachmentsid = aicrm_salesmanattachmentsrel.attachmentsid
		where aicrm_activity_timeline.crmid = ?
		ORDER BY aicrm_activity_timeline.id DESC,aicrm_activity_timeline_detail.id DESC;";
	$result = $adb->pquery($sql1, array($focus->id));
	$num_row = $adb->num_rows($result);
	
	for($i=0; $i<$num_row; $i++)
	{
		//$id = $adb->query_result($result,$i,"id");
		$focus_list[$i]['crmid'] = $adb->query_result($result,$i,"crmid");
		$focus_list[$i]['action'] =$adb->query_result($result,$i,"action");
		$focus_list[$i]['userid'] = $adb->query_result($result,$i,"userid");
		$focus_list[$i]['createdtime'] = $adb->query_result($result,$i,"createdtime");
		$focus_list[$i]['format_date'] = $adb->query_result($result,$i,"format_date");
		$focus_list[$i]['fieldid'] = $adb->query_result($result,$i,"fieldid");
				
		$focus_list[$i]['fieldlabel'] = $adb->query_result($result,$i,"fieldlabel");
		$focus_list[$i]['fieldlabel'] = $adb->query_result($result,$i,"fieldlabel");
		$focus_list[$i]['user_name'] = $adb->query_result($result,$i,"user_name");
		$focus_list[$i]['profile_image'] = $adb->query_result($result,$i,"profile_image");

		$uitype = @$adb->query_result($result,$i,"uitype");

		if($uitype == 5){
			if($adb->query_result($result,$i,"old_value") == '0000-00-00' || $adb->query_result($result,$i,"old_value") == "")
			{
				$focus_list[$i]['old_value'] = '';
			}
			else
			{
				$focus_list[$i]['old_value'] = getDisplayDate($adb->query_result($result,$i,"old_value"));
			}

			if($adb->query_result($result,$i,"new_value") == '0000-00-00' || $adb->query_result($result,$i,"new_value") == "")
			{
				$focus_list[$i]['new_value'] = '';
			}
			else
			{
				$focus_list[$i]['new_value'] = getDisplayDate($adb->query_result($result,$i,"new_value"));
			}
		
		}else if($uitype == 33){

			$focus_list[$i]['old_value'] = str_ireplace(' |##| ',', ',$adb->query_result($result,$i,"old_value"));
			$focus_list[$i]['new_value'] = str_ireplace(' |##| ',', ',$adb->query_result($result,$i,"new_value"));
		
		}else if($uitype == 51){
			
			$old_account_id = $adb->query_result($result,$i,"old_value");
			$new_account_id = $adb->query_result($result,$i,"new_value");
			$old_account_name = '';
			$new_account_name = '';

			if($old_account_id != '' || $old_account_id != 0)
			{
				$old_account_name = getAccountName($old_account_id);
				$link_old = '<a href="index.php?module=Accounts&action=DetailView&record='.$old_account_id.'">'.$old_account_name.'</a>';
			}

			if($new_account_id != '' || $new_account_id != 0)
			{
				$new_account_name = getAccountName($new_account_id);
				$link_new = '<a href="index.php?module=Accounts&action=DetailView&record='.$new_account_id.'">'.$new_account_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 53){
			
			$old_owner_id =  $adb->query_result($result,$i,"old_value");
			$old_user = 'no';
			$result_old = $adb->pquery("SELECT count(*) as count from aicrm_users where id = ?",array($old_owner_id));
			if($adb->query_result($result_old,0,'count') > 0) {
				$old_user = 'yes';
			}
			$old_owner_name = getOwnerName($old_owner_id);
			if(is_admin($current_user))
			{
				if($old_user == 'no') {
					$focus_list[$i]['old_value']  = '<a href="index.php?module=Settings&action=GroupDetailView&groupId='.$old_owner_id.'">'.$old_owner_name.'</a>';
				}
				else {
					$focus_list[$i]['old_value']  = '<a href="index.php?module=Users&action=DetailView&record='.$old_owner_id.'">'.$old_owner_name.'</a>';
				}
			}
			
			$new_owner_id =  $adb->query_result($result,$i,"new_value");
			$new_user = 'no';
			$result_new = $adb->pquery("SELECT count(*) as count from aicrm_users where id = ?",array($new_owner_id));
			if($adb->query_result($result_new,0,'count') > 0) {
				$new_user = 'yes';
			}
			$old_owner_name = getOwnerName($new_owner_id);
			if(is_admin($current_user))
			{
				if($new_user == 'no') {
					$focus_list[$i]['new_value']  = '<a href="index.php?module=Settings&action=GroupDetailView&groupId='.$new_owner_id.'">'.$old_owner_name.'</a>';
				}
				else {
					$focus_list[$i]['new_value']  = '<a href="index.php?module=Users&action=DetailView&record='.$new_owner_id.'">'.$old_owner_name.'</a>';
				}
			}

		}else if($uitype == 56){
			
			if($adb->query_result($result,$i,"old_value") == 1)
			{
				$focus_list[$i]['old_value'] = $app_strings['yes'];
			}
			else
			{
				$focus_list[$i]['old_value'] = $app_strings['no'];
			}

			if($adb->query_result($result,$i,"new_value") == 1)
			{
				$focus_list[$i]['new_value'] = $app_strings['yes'];
			}
			else
			{
				$focus_list[$i]['new_value'] = $app_strings['no'];
			}

		}else if($uitype == 57){//contact_id

			$link_old ='';
			$link_new = '';

			$old_contactid = $adb->query_result($result,$i,"old_value");	
			if($old_contactid != '' || $old_contactid != 0)
			{
				$old_contact_name = getContactName($old_contactid);
				$link_old = '<a href="index.php?module=Contacts&action=DetailView&record='.$old_contactid.'">'.$old_contact_name.'</a>';
			}

			$new_contactid = $adb->query_result($result,$i,"new_value");
			if($new_contactid != '' || $new_contactid != 0)
			{
				$new_contact_name = getContactName($new_contactid);
				$link_new = '<a href="index.php?module=Contacts&action=DetailView&record='.$new_contactid.'">'.$new_contact_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 58){//campaignid
			
			$old_campaign_id = $adb->query_result($result,$i,"old_value");
			$link_old = '';
			$link_new = '';

			if($old_campaign_id != '' || $old_campaign_id != 0)
			{
				$old_campaign_name = getCampaignName($old_campaign_id);
				$link_old = '<a href="index.php?module=Campaigns&action=DetailView&record='.$old_campaign_id.'">'.$old_campaign_name.'</a>';
			}

			$new_campaign_id = $adb->query_result($result,$i,"new_value");
			if($new_campaign_id != '' || $new_campaign_id != 0)
			{
				$new_campaign_name = getCampaignName($new_campaign_id);
				$link_new = '<a href="index.php?module=Campaigns&action=DetailView&record='.$new_campaign_id.'">'.$new_campaign_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 59){//product_id

			$link_old ='';
			$link_new = '';

			$old_product_id = $adb->query_result($result,$i,"old_value");	
			if($old_product_id != '' || $old_product_id != 0)
			{
				$old_product_name = getProductName($old_product_id);
				$link_old = '<a href="index.php?module=Products&action=DetailView&record='.$old_product_id.'">'.$old_product_name.'</a>';
			}

			$new_product_id = $adb->query_result($result,$i,"new_value");
			if($new_product_id != '' || $new_product_id != 0)
			{
				$new_product_name = getProductName($new_product_id);
				$link_new = '<a href="index.php?module=Products&action=DetailView&record='.$new_product_id.'">'.$new_product_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 73){//account_id
			
			$old_account_id = $adb->query_result($result,$i,"old_value");
			$new_account_id = $adb->query_result($result,$i,"new_value");
			$old_account_name = '';
			$new_account_name = '';

			if($old_account_id != '' || $old_account_id != 0)
			{
				$old_account_name = getAccountName($old_account_id);
				$link_old = '<a href="index.php?module=Accounts&action=DetailView&record='.$old_account_id.'">'.$old_account_name.'</a>';
			}

			if($new_account_id != '' || $new_account_id != 0)
			{
				$new_account_name = getAccountName($new_account_id);
				$link_new = '<a href="index.php?module=Accounts&action=DetailView&record='.$new_account_id.'">'.$new_account_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;
		
		}else if($uitype == 71 || $uitype == 7){
			
			$focus_list[$i]['old_value'] = number_format($adb->query_result($result,$i,"old_value"));
			$focus_list[$i]['new_value'] = number_format($adb->query_result($result,$i,"new_value"));

		}else if($uitype == 201) {//parentid
			
			$link_old ='';
			$link_new = '';

			$old_parentid = $adb->query_result($result,$i,"old_value");
			if($old_parentid != '')
			{
				$parent_module_old = getSalesEntityType($old_parentid);
				if($parent_module_old == "Accounts")
				{
					$event_name_old = getAccountName($old_parentid);
					$link_old ='<a href="index.php?module='.$parent_module_old.'&action=DetailView&record='.$old_parentid.'">'.$event_name_old.'</a>';
				}
				elseif($parent_module_old == "Leads")
				{
					$event_name_old = getLeadName($old_parentid);
					$link_old ='<a href="index.php?module='.$parent_module_old.'&action=DetailView&record='.$old_parentid.'">'.$event_name_old.'</a>';
				}
				else
				{
					$link_old[] = '';
				}
			}
			else
			{
				$link_old[] = '';
			}

			$new_parentid = $adb->query_result($result,$i,"new_value");
			if($new_parentid != '')
			{
				$parent_module_new = getSalesEntityType($new_parentid);
				if($parent_module_new == "Accounts")
				{
					$event_name_new = getAccountName($new_parentid);
					$link_new ='<a href="index.php?module='.$parent_module_new.'&action=DetailView&record='.$new_parentid.'">'.$event_name_new.'</a>';
				}
				elseif($parent_module_new == "Leads")
				{
					$event_name_new = getLeadName($new_parentid);
					$link_new ='<a href="index.php?module='.$parent_module_new.'&action=DetailView&record='.$new_parentid.'">'.$event_name_new.'</a>';
				}
				else
				{
					$link_new[] = '';
				}
			}
			else
			{
				$link_new[] = '';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;
		
		}else if($uitype == 301){
			
			$link_old ='';
			$link_new = '';

			$old_dealid = $adb->query_result($result,$i,"old_value");	
			if($old_dealid != '' || $old_dealid != 0)
			{
				$old_deal_name = getdealname($old_dealid);
				$link_old = '<a href="index.php?module=Deal&action=DetailView&record='.$old_dealid.'">'.$old_deal_name.'</a>';
			}

			$new_dealid = $adb->query_result($result,$i,"new_value");
			if($new_dealid != '' || $new_dealid != 0)
			{
				$new_deal_name = getdealname($new_dealid);
				$link_new = '<a href="index.php?module=Deal&action=DetailView&record='.$new_dealid.'">'.$new_deal_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 302){//competitorid

			$link_old ='';
			$link_new = '';
			
			$old_competitorid = $adb->query_result($result,$i,"old_value");	
			if($old_competitorid != '' || $old_competitorid != 0)
			{
				$old_competitor_name = getcompetitorname($old_competitorid);
				$link_old = '<a href="index.php?module=Competitor&action=DetailView&record='.$old_competitorid.'">'.$old_competitor_name.'</a>';
			}

			$new_competitorid = $adb->query_result($result,$i,"new_value");
			if($new_competitorid != '' || $new_competitorid != 0)
			{
				$new_competitor_name = getcompetitorname($new_competitorid);
				$link_new = '<a href="index.php?module=Competitor&action=DetailView&record='.$new_competitorid.'">'.$new_competitor_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 303){//promotionvoucherid
			$link_old ='';
			$link_new = '';
			
			$old_promotionid = $adb->query_result($result,$i,"old_value");	
			if($old_promotionid != '' || $old_promotionid != 0)
			{
				$old_promotion_name = getpromotionvouchername($old_promotionid);
				$link_old = '<a href="index.php?module=Promotionvoucher&action=DetailView&record='.$old_promotionid.'">'.$old_promotion_name.'</a>';
			}

			$new_promotionid = $adb->query_result($result,$i,"new_value");
			if($new_promotionid != '' || $new_promotionid != 0)
			{
				$new_promotion_name = getpromotionvouchername($new_promotionid);
				$link_new = '<a href="index.php?module=Promotionvoucher&action=DetailView&record='.$new_promotionid.'">'.$new_promotion_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 304){//promotionid
			$link_old ='';
			$link_new = '';
			
			$old_promotionid = $adb->query_result($result,$i,"old_value");	
			if($old_promotionid != '' || $old_promotionid != 0)
			{
				$old_promotion_name = getpromotion($old_promotionid);
				$link_old = '<a href="index.php?module=Promotion&action=DetailView&record='.$old_promotionid.'">'.$old_promotion_name.'</a>';
			}

			$new_promotionid = $adb->query_result($result,$i,"new_value");
			if($new_promotionid != '' || $new_promotionid != 0)
			{
				$new_promotion_name = getpromotion($new_promotionid);
				$link_new = '<a href="index.php?module=Promotion&action=DetailView&record='.$new_promotionid.'">'.$new_promotion_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 307){//quoteid
			$link_old ='';
			$link_new = '';
			
			$old_quoteid = $adb->query_result($result,$i,"old_value");	
			if($old_quoteid != '' || $old_quoteid != 0)
			{
				$old_quote_name = getquotesno($old_quoteid);
				$link_old = '<a href="index.php?module=Quotes&action=DetailView&record='.$old_quoteid.'">'.$old_quote_name.'</a>';
			}

			$new_quoteid = $adb->query_result($result,$i,"new_value");
			if($new_quoteid != '' || $new_quoteid != 0)
			{
				$new_quote_name = getquotesno($new_quoteid);
				$link_new = '<a href="index.php?module=Quotes&action=DetailView&record='.$new_quoteid.'">'.$new_quote_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 308){//servicerequestid
			$link_old ='';
			$link_new = '';

			$old_id = $adb->query_result($result,$i,"old_value");	
			if($old_id != '' || $old_id != 0)
			{
				$old_name = getservicerequest($old_id);
				$link_old = '<a href="index.php?module=Servicerequest&action=DetailView&record='.$old_id.'">'.$old_name.'</a>';
			}

			$new_id = $adb->query_result($result,$i,"new_value");
			if($new_id != '' || $new_id != 0)
			{
				$new_name = getservicerequest($new_id);
				$link_new = '<a href="index.php?module=Servicerequest&action=DetailView&record='.$new_id.'">'.$new_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 309){//ticketid
			$link_old ='';
			$link_new = '';

			$old_id = $adb->query_result($result,$i,"old_value");	
			if($old_id != '' || $old_id != 0)
			{
				$old_name = getticket($old_id);
				$link_old = '<a href="index.php?module=HelpDesk&action=DetailView&record='.$old_id.'">'.$old_name.'</a>';
			}

			$new_id = $adb->query_result($result,$i,"new_value");
			if($new_id != '' || $new_id != 0)
			{
				$new_name = getticket($new_id);
				$link_new = '<a href="index.php?module=HelpDesk&action=DetailView&record='.$new_id.'">'.$new_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 910){//questionnairetemplateid
			$link_old ='';
			$link_new = '';
			
			$old_questionnairetemplateid = $adb->query_result($result,$i,"old_value");	
			if($old_questionnairetemplateid != '' || $old_questionnairetemplateid != 0)
			{
				$old_questionnairetemplate_name = getquestionnairetemplatename($old_questionnairetemplateid);
				$link_old = '<a href="index.php?module=Questionnairetemplate&action=DetailView&record='.$old_questionnairetemplateid.'">'.$old_questionnairetemplate_name.'</a>';
			}

			$new_questionnairetemplateid = $adb->query_result($result,$i,"new_value");
			if($new_questionnairetemplateid != '' || $new_questionnairetemplateid != 0)
			{
				$new_questionnairetemplate_name = getquestionnairetemplatename($new_questionnairetemplateid);
				$link_new = '<a href="index.php?module=Questionnairetemplate&action=DetailView&record='.$new_questionnairetemplateid.'">'.$new_questionnairetemplate_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 914){//event_id
			
			$link_old ='';
			$link_new = '';

			$old_parentid = $adb->query_result($result,$i,"old_value");
			if($old_parentid != '')
			{
				$old_parent_module = getSalesEntityType($old_parentid);
				if($old_parent_module == "Job")
				{
					$old_event_name = getjob($old_parentid);
					$link_old ='<a href="index.php?module='.$old_parent_module.'&action=DetailView&record='.$old_parentid.'">'.$old_event_name.'</a>';
				}
				elseif($old_parent_module == "HelpDesk")
				{
					$old_event_name = get_HelpDesk_No($old_parentid);
					$link_old ='<a href="index.php?module='.$old_parent_module.'&action=DetailView&record='.$old_parentid.'">'.$old_event_name.'</a>';
				}
				elseif($old_parent_module == "Projects")
				{
					$old_event_name = get_Projects_Name($old_parentid);
					$link_old ='<a href="index.php?module='.$old_parent_module.'&action=DetailView&record='.$old_parentid.'">'.$old_event_name.'</a>';
				}
				elseif($old_parent_module == "Deal")
				{
					$old_event_name = getdealname($old_parentid);
					$link_old ='<a href="index.php?module='.$old_parent_module.'&action=DetailView&record='.$old_parentid.'">'.$old_event_name.'</a>';
				}
				else
				{
					$link_old = '';
				}
			}
			else
			{
				$link_old = '';
			}

			$new_parentid = $adb->query_result($result,$i,"new_value");
			if($new_parentid != '')
			{
				$new_parent_module = getSalesEntityType($new_parentid);
				if($new_parent_module == "Job")
				{
					$new_event_name = getjob($new_parentid);
					$link_new ='<a href="index.php?module='.$new_parent_module.'&action=DetailView&record='.$new_parentid.'">'.$new_event_name.'</a>';
				}
				elseif($new_parent_module == "HelpDesk")
				{
					$new_event_name = get_HelpDesk_No($new_parentid);
					$link_new ='<a href="index.php?module='.$new_parent_module.'&action=DetailView&record='.$new_parentid.'">'.$new_event_name.'</a>';
				}
				elseif($new_parent_module == "Projects")
				{
					$new_event_name = get_Projects_Name($new_parentid);
					$link_new ='<a href="index.php?module='.$new_parent_module.'&action=DetailView&record='.$new_parentid.'">'.$new_event_name.'</a>';
				}
				else
				{
					$link_new = '';
				}
			}
			else
			{
				$link_new = '';
			}

			
			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 930){//account_id1
			$link_old ='';
			$link_new = '';

			$old_account_id = $adb->query_result($result,$i,"old_value");	
			if($old_account_id != '' || $old_account_id != 0)
			{
				$old_account_name = getAccountcoderms($old_account_id);
				$link_old = '<a href="index.php?module=Accounts&action=DetailView&record='.$old_account_id.'">'.$old_account_name.'</a>';
			}

			$new_account_id = $adb->query_result($result,$i,"new_value");
			if($new_account_id != '' || $new_account_id != 0)
			{
				$new_account_name = getAccountcoderms($new_account_id);
				$link_new = '<a href="index.php?module=Accounts&action=DetailView&record='.$new_account_id.'">'.$new_account_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 931){//contactid Multiple
			$link_old ='';
			$link_new = '';

			$old_contactid = $adb->query_result($result,$i,"old_value");	
			if($old_contactid != '' || $old_contactid != 0)
			{
				$old_contact_name = getContactcode($old_contactid);
				$link_old = '<a href="index.php?module=Contacts&action=DetailView&record='.$old_contactid.'">'.$old_contact_name.'</a>';
			}

			$new_contactid = $adb->query_result($result,$i,"new_value");
			if($new_contactid != '' || $new_contactid != 0)
			{
				$new_contact_name = getContactcode($new_contactid);
				$link_new = '<a href="index.php?module=Contacts&action=DetailView&record='.$new_contactid.'">'.$new_contact_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 934){//contactid1
			$link_old ='';
			$link_new = '';

			$old_contactid = $adb->query_result($result,$i,"old_value");	
			if($old_contactid != '' || $old_contactid != 0)
			{
				$old_contact_name = getContactcode($old_contactid);
				$link_old = '<a href="index.php?module=Contacts&action=DetailView&record='.$old_contactid.'">'.$old_contact_name.'</a>';
			}

			$new_contactid = $adb->query_result($result,$i,"new_value");
			if($new_contactid != '' || $new_contactid != 0)
			{
				$new_contact_name = getContactcode($new_contactid);
				$link_new = '<a href="index.php?module=Contacts&action=DetailView&record='.$new_contactid.'">'.$new_contact_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 935){//serialid
			$link_old ='';
			$link_new = '';

			$old_serialid = $adb->query_result($result,$i,"old_value");	
			if($old_serialid != '' || $old_serialid != 0)
			{
				$old_serial_name = getSerial($old_serialid);
				$link_old = '<a href="index.php?module=Serial&action=DetailView&record='.$old_serialid.'">'.$old_serial_name.'</a>';
			}

			$new_serialid = $adb->query_result($result,$i,"new_value");
			if($new_serialid != '' || $new_serialid != 0)
			{
				$new_serial_name = getSerial($new_serialid);
				$link_new = '<a href="index.php?module=Serial&action=DetailView&record='.$new_serialid.'">'.$new_serial_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 937){//errorsid
			$link_old ='';
			$link_new = '';

			$old_id = $adb->query_result($result,$i,"old_value");	
			if($old_id != '' || $old_id != 0)
			{
				$old_name = geterrorno($old_id);
				$link_old = '<a href="index.php?module=Errors&action=DetailView&record='.$old_id.'">'.$old_name.'</a>';
			}

			$new_id = $adb->query_result($result,$i,"new_value");
			if($new_id != '' || $new_id != 0)
			{
				$new_name = geterrorno($new_id);
				$link_new = '<a href="index.php?module=Errors&action=DetailView&record='.$new_id.'">'.$new_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else if($uitype == 938){//jobid
			$link_old ='';
			$link_new = '';

			$old_jobid = $adb->query_result($result,$i,"old_value");	
			if($old_jobid != '' || $old_jobid != 0)
			{
				$old_job_name = getjob($old_jobid);
				$link_old = '<a href="index.php?module=Job&action=DetailView&record='.$old_jobid.'">'.$old_job_name.'</a>';
			}

			$new_jobid = $adb->query_result($result,$i,"new_value");
			if($new_jobid != '' || $new_jobid != 0)
			{
				$new_job_name = getjob($new_jobid);
				$link_new = '<a href="index.php?module=Job&action=DetailView&record='.$new_jobid.'">'.$new_job_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		//}else if($uitype == 965){//sparepartlistid

		}else if($uitype == 963){//inspectiontemplateid

			$link_old ='';
			$link_new = '';

			$old_id = $adb->query_result($result,$i,"old_value");	
			if($old_id != '' || $old_id != 0)
			{
				$old_name = getinspection_template_name($old_id);
				$link_old = '<a href="index.php?module=Inspectiontemplate&action=DetailView&record='.$old_id.'">'.$old_name.'</a>';
			}

			$new_id = $adb->query_result($result,$i,"new_value");
			if($new_id != '' || $new_id != 0)
			{
				$new_name = getinspection_template_name($new_id);
				$link_new = '<a href="index.php?module=Inspectiontemplate&action=DetailView&record='.$new_id.'">'.$new_name.'</a>';
			}

			$focus_list[$i]['old_value'] = $link_old;
			$focus_list[$i]['new_value'] = $link_new;

		}else {
			$focus_list[$i]['old_value'] = $adb->query_result($result,$i,"old_value");
			$focus_list[$i]['new_value'] = $adb->query_result($result,$i,"new_value");
		}
	}

	$log->debug("Exiting getTimelineLists method ...");
	//echo "<pre>"; print_r($focus_list); echo "</pre>"; exit;
	return $focus_list;

}

function getFilterByField($module,$focus,$condition=null)
{
	global $log;
	$log->debug("Entering getFilterByField(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $current_user;
	global $app_strings;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	$cur_tab_id = getTabid($module);
	
	$sql1 = "select
		aicrm_activity_timeline_detail.fieldid,
		aicrm_field.fieldlabel
		FROM aicrm_activity_timeline 
		INNER JOIN aicrm_activity_timeline_detail on aicrm_activity_timeline_detail.activitytimelineid = aicrm_activity_timeline.id
		INNER JOIN aicrm_field on aicrm_field.fieldid = aicrm_activity_timeline_detail.fieldid
		WHERE aicrm_activity_timeline.crmid = ?
		GROUP BY aicrm_activity_timeline_detail.fieldid
		ORDER BY aicrm_field.fieldlabel ASC;";
	
	$result = $adb->pquery($sql1, array($focus->id));
	$num_row = $adb->num_rows($result);
	
	for($i=0; $i<$num_row; $i++)
	{
		$field_list[$i]['fieldid'] = $adb->query_result($result,$i,"fieldid");
		$field_list[$i]['fieldlabel'] =$adb->query_result($result,$i,"fieldlabel");
	}

	$log->debug("Exiting getFilterByField method ...");
	return $field_list;

}

function getFilterByUser($module,$focus,$condition=null)
{
	global $log;
	$log->debug("Entering getFilterByUser(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $current_user;
	global $app_strings;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	$cur_tab_id = getTabid($module);
	
	$sql1 = "select
		aicrm_activity_timeline.userid,
		aicrm_users.user_name
		from aicrm_activity_timeline 
		INNER JOIN aicrm_users on aicrm_users.id = aicrm_activity_timeline.userid
		where aicrm_activity_timeline.crmid = ?
		GROUP BY aicrm_activity_timeline.userid 
		ORDER BY aicrm_users.user_name ASC;";
	
	$result = $adb->pquery($sql1, array($focus->id));
	$num_row = $adb->num_rows($result);
	
	for($i=0; $i<$num_row; $i++)
	{
		$user_list[$i]['userid'] = $adb->query_result($result,$i,"userid");
		$user_list[$i]['user_name'] =$adb->query_result($result,$i,"user_name");
	}

	$log->debug("Exiting getFilterByUser method ...");
	return $user_list;

}
/** This function returns the related aicrm_tab details for a given entity or a module.
* Param $module - module name
* Param $focus - module object
* Return type is an array
*/

function getRelatedLists($module,$focus,$condition=null)
{
	global $log;
	$log->debug("Entering getRelatedLists(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');

	$cur_tab_id = getTabid($module);
	// vtlib customization: Do not picklist module which are set as in-active
	$sql1 = "select * from aicrm_relatedlists where tabid=? and related_tabid not in (SELECT tabid FROM aicrm_tab WHERE presence = 1)
	and presence = 0 ";
	if($condition!=""){
		$sql1 .= $condition;
	}
	$sql1 .= "order by sequence";
	// echo $sql1; exit;
	// END
	$result = $adb->pquery($sql1, array($cur_tab_id));

	$num_row = $adb->num_rows($result);
	for($i=0; $i<$num_row; $i++)
	{
		$rel_tab_id = $adb->query_result($result,$i,"related_tabid");
		$function_name = $adb->query_result($result,$i,"name");
		$label = $adb->query_result($result,$i,"label");
		$actions = $adb->query_result($result,$i,"actions");
		if($rel_tab_id != 0)
		{
			if($profileTabsPermission[$rel_tab_id] == 0)
			{
	        	if($profileActionPermission[$rel_tab_id][3] == 0)
				{
					// vtlib customization: Send more information (from module, related module) to the callee
					$focus_list[$label] = $focus->$function_name($focus->id, $cur_tab_id, $rel_tab_id, $actions);
					// END
    			}
			}
		}
		else
		{
			// vtlib customization: Send more information (from module, related module) to the callee
			$focus_list[$label] = $focus->$function_name($focus->id, $cur_tab_id, $rel_tab_id, $actions);
			// END
		}
	}
	$log->debug("Exiting getRelatedLists method ...");
	return $focus_list;
}

/** This function returns whether related lists is present for this particular module or not
* Param $module - module name
* Param $activity_mode - mode of activity
* Return type true or false
*/


function isPresentRelatedLists($module,$activity_mode='')
{
	global $adb;
	$retval='true';
	$tab_id=getTabid($module);
	// We need to check if there is atleast 1 relation, no need to use count(*)
	$query= "select relation_id from aicrm_relatedlists where tabid=? LIMIT 1";
	$result=$adb->pquery($query, array($tab_id));
	$count=$adb->num_rows($result);
	if($count < 1 || ($module =='Calendar' && $activity_mode=='task'))
	{
		$retval='false';
	}
	return $retval;


}

/** This function returns the detailed block information of a record in a module.
* Param $module - module name
* Param $block - block id
* Param $col_fields - column aicrm_fields array for the module
* Param $tabid - aicrm_tab id
* Return type is an array
*/

function getDetailBlockInformation($module, $result,$col_fields,$tabid,$block_label)
{
	global $log;
	$log->debug("Entering getDetailBlockInformation(".$module.",". $result.",".$col_fields.",".$tabid.",".$block_label.") method ...");
	global $adb;
	global $current_user;
	global $mod_strings;
	$label_data = Array();

	$noofrows = $adb->num_rows($result);
	for($i=0; $i<$noofrows; $i++)
	{
		$fieldtablename = $adb->query_result($result,$i,"tablename");
		$fieldcolname = $adb->query_result($result,$i,"columnname");
		$uitype = $adb->query_result($result,$i,"uitype");
		$fieldname = $adb->query_result($result,$i,"fieldname");
		$fieldid = $adb->query_result($result,$i,"fieldid");
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$maxlength = $adb->query_result($result,$i,"maximumlength");
		$block = $adb->query_result($result,$i,"block");
		$generatedtype = $adb->query_result($result,$i,"generatedtype");
		$tabid = $adb->query_result($result,$i,"tabid");
		$displaytype = $adb->query_result($result,$i,'displaytype');
		$custfld = getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype,$tabid,$module);
		if(is_array($custfld))
		{
			$label_data[$block][] = array($custfld[0]=>array("value"=>$custfld[1],"ui"=>$custfld[2],"options"=>$custfld["options"],"secid"=>$custfld["secid"],"link"=>$custfld["link"],"cursymb"=>$custfld["cursymb"],"salut"=>$custfld["salut"],"notaccess"=>$custfld["notaccess"],"cntimage"=>$custfld["cntimage"],"isadmin"=>$custfld["isadmin"],"tablename"=>$fieldtablename,"fldname"=>$fieldname,"fldid"=>$fieldid,"displaytype"=>$displaytype));
		}
		$i++;
		if($i<$noofrows)
		{
			$fieldtablename = $adb->query_result($result,$i,"tablename");
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$uitype = $adb->query_result($result,$i,"uitype");
			$fieldname = $adb->query_result($result,$i,"fieldname");
			$fieldid = $adb->query_result($result,$i,"fieldid");
			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			$maxlength = $adb->query_result($result,$i,"maximumlength");
			$block = $adb->query_result($result,$i,"block");
			$generatedtype = $adb->query_result($result,$i,"generatedtype");
			$tabid = $adb->query_result($result,$i,"tabid");
			$displaytype = $adb->query_result($result,$i,'displaytype');
			$custfld = getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields,$generatedtype,$tabid,$module);

			if($fieldname == 'branch_code'){
				if($custfld[1] == '') $custfld[1] = $col_fields[$fieldname];
			}
			
			if(is_array($custfld))
			{
				$label_data[$block][] = array($custfld[0]=>array("value"=>$custfld[1],"ui"=>$custfld[2],"options"=>$custfld["options"],"secid"=>$custfld["secid"],"link"=>$custfld["link"],"cursymb"=>$custfld["cursymb"],"salut"=>$custfld["salut"],"notaccess"=>$custfld["notaccess"],"cntimage"=>$custfld["cntimage"],"isadmin"=>$custfld["isadmin"],"tablename"=>$fieldtablename,"fldname"=>$fieldname,"fldid"=>$fieldid,"displaytype"=>$displaytype));
			}
		}
	}

	//Smart Sms - Smart Email
	foreach($label_data as $key => $val){
		foreach($val as $k => $v){
			foreach($v as $k1 => $v1){
				if($k1 == 'ชื่อผู้ส่ง'){
					for($i=0;$i<count($v1['options']);$i++){
						
						if($v1['options'][$i][2] == 'selected'){
							$label_data[$key][$k]['ชื่อผู้ส่ง']['value'] = $v1['options'][$i][0] ;
						}
					}								
				}
				if($k1 == 'ชื่อ Server'){
					for($i=0;$i<count($v1['options']);$i++){
						
						if($v1['options'][$i][2] == 'selected'){
							//print_r($k);
							$label_data[$key][$k]['ชื่อ Server']['value'] = $v1['options'][$i][0] ;
						}
					}								
				}
			}
		}
	}
	//Smart Sms - Smart Email

	foreach($label_data as $headerid=>$value_array)
	{
		$detailview_data = Array();
		for ($i=0,$j=0;$i<count($value_array);$j++)
		{
			$key2 = null;
			$keys=array_keys($value_array[$i]);
			$key1=$keys[0];
			if(is_array($value_array[$i+1]) && ($value_array[$i][$key1][ui]!=19 && $value_array[$i][$key1][ui]!=20))
			{
				$keys=array_keys($value_array[$i+1]);
				$key2=$keys[0];
			}
			// Added to avoid the unique keys
			$use_key1 = $key1;
			if($key1 == $key2) {
				$use_key1 = " " . $key1;
			}

			if($value_array[$i][$key1][ui]!=19 && $value_array[$i][$key1][ui]!=20){
				$detailview_data[$j]=array($use_key1 => $value_array[$i][$key1],$key2 => $value_array[$i+1][$key2]);
				$i+=2;
			}else{
				$detailview_data[$j]=array($use_key1 => $value_array[$i][$key1]);
				$i++;
			}
		}
		$label_data[$headerid] = $detailview_data;
	}
	foreach($block_label as $blockid=>$label)
	{
		if($label == '')
		{
			$returndata[getTranslatedString($curBlock,$module)]=array_merge((array)$returndata[getTranslatedString($curBlock,$module)],(array)$label_data[$blockid]);
		}
		else
		{
			$curBlock = $label;
			if(is_array($label_data[$blockid]))
				$returndata[getTranslatedString($curBlock,$module)]=array_merge((array)$returndata[getTranslatedString($curBlock,$module)],(array)$label_data[$blockid]);
		}
	}
	$log->debug("Exiting getDetailBlockInformation method ...");
	return $returndata;
}

function VT_detailViewNavigation($smarty,$recordNavigationInfo,$currrentRecordId){
	$pageNumber =0;
	foreach ($recordNavigationInfo as $start=>$recordIdList){
		$pageNumber++;
		foreach ($recordIdList as $index=>$recordId) {
			if($recordId === $currrentRecordId){
				if($index ==0 ){
					$smarty->assign('privrecordstart',$start-1);
					$smarty->assign('privrecord',$recordNavigationInfo[$start-1][count($recordNavigationInfo[$start-1])-1]);
				}else{
					$smarty->assign('privrecordstart',$start);
					$smarty->assign('privrecord',$recordIdList[$index-1]);
				}
				if($index == count($recordIdList)-1){
					$smarty->assign('nextrecordstart',$start+1);
					$smarty->assign('nextrecord',$recordNavigationInfo[$start+1][0]);
				}else{

					$smarty->assign('nextrecordstart',$start);
					$smarty->assign('nextrecord',$recordIdList[$index+1]);
				}
			}
		}
	}
}

function getDetailAssociatedProductsOrder($module,$focus)
{
	global $log;
	$log->debug("Entering getDetailAssociatedProductsOrder(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	//Get the taxtype of this entity
	$taxtype = getInventoryTaxType($module,$focus->id);
	$currencytype = getInventoryCurrencyInfo($module, $focus->id);
	
	$output = '';
	$colspan=2;
	$output .= '

	<table width="100%"  border="0" align="center" cellpadding="17" cellspacing="0" class="crmTable" id="proTab">
	   <tr valign="top">
		<td colspan="6" class="dvInnerHeader"><b>'.$app_strings['LBL_ITEM_DETAILS'].'</b></td>
		<td class="dvInnerHeader" align="center" colspan="4">
			<b>Price type : </b>'.$focus->column_fields['pricetype'].'
		</td>
		<td class="dvInnerHeader" align="center" colspan="5"><b>'.
			$app_strings['LBL_CURRENCY'].' : </b>'. getTranslatedCurrencyString($currencytype['currency_name']). ' ('. $currencytype['currency_symbol'] .')
		</td>
		<td class="dvInnerHeader" align="center" colspan="3"><b>'.
			$app_strings['LBL_TAX_MODE'].' : </b>'.$app_strings[$taxtype].'
		</td>
	   </tr>';

	/*$output .= '<tr valign="top">
					<td width="15%" class="lvtCol"><b>รายการสินค้า</b></td>
					<td width="5%" class="lvtCol"><b>Product Type</b></td>
					<td width="3%" class="lvtCol"><b>Km</b></td>
					<td width="3%" class="lvtCol"><b>Zone</b></td>
					<td width="3%" class="lvtCol"><b>ขนาดรถ</b></td>
					<td width="3%" class="lvtCol"><b>หน่วย</b></td>
					<td width="3%" class="lvtCol"><b>จำนวน</b></td>
					<td width="5%" class="lvtCol"><b>ราคา/หน่วย</b></td>
					<td width="5%" class="lvtCol"><b>จำนวนเงิน</b></td>
					<td width="3%" class="lvtCol"><b>Min</b></td>
					<td width="3%" class="lvtCol"><b>DLV_C</b></td>
					<td width="3%" class="lvtCol"><b>DLV_C+VAT</b></td>
					<td width="3%" class="lvtCol"><b>DLV_P+VAT</b></td>
					<td width="3%" class="lvtCol"><b>LP</b></td>
					<td width="3%" class="lvtCol"><b>ส่วนลด</b></td>
					<td width="5%" class="lvtCol"><b>C_Cost</b></td>
					<td width="5%" class="lvtCol"><b>ราคาหลังหักส่วนลด</b></td>
					<td width="5%" class="lvtCol"><b>จำนวนเงินซื้อ</b></td>
			    </tr>';*/

	/*<td class="lvtCol"><b>Product Type</b></td>*/
	$output .= '<tr valign="top">
					<td class="lvtCol"><b>รายการสินค้า</b></td>
					
					<td class="lvtCol"><b>Km</b></td>
					<td class="lvtCol"><b>Zone</b></td>
					<td class="lvtCol"><b>ขนาดรถ</b></td>
					<td class="lvtCol"><b>หน่วย</b></td>
					<td class="lvtCol"><b>จำนวน</b></td>
					<td class="lvtCol"><b>ราคา/หน่วย</b></td>
					<td class="lvtCol"><b>จำนวนเงิน</b></td>
					<td class="lvtCol"><b>Min</b></td>
					<td class="lvtCol"><b>DLV_C</b></td>
					<td class="lvtCol"><b>DLV_C+VAT</b></td>
					<td class="lvtCol"><b>DLV_P+VAT</b></td>
					<td class="lvtCol"><b>LP</b></td>
					<td class="lvtCol"><b>ส่วนลด</b></td>
					<td class="lvtCol"><b>C_Cost</b></td>
					<td class="lvtCol"><b>ราคาหลังหักส่วนลด (ก่อน VAT)</b></td>
					<td class="lvtCol"><b>จำนวน<br>เงิน(ซื้อ)</b></td>
			    </tr>';

	// Add "ORDER BY sequence_no" to retain add order on all inventoryproductrel items
	$query="select aicrm_inventoryproductrelorder.* " .
					" from aicrm_inventoryproductrelorder" .
					" left join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrelorder.productid " .
					" where id=? ORDER BY sequence_no";

	$result=$adb->pquery($query, array($focus->id));
	$num_rows=$adb->num_rows($result);
	
	$sql_quotes = "select * from aicrm_order where orderid=".$focus->id;
	$query_quotes = mysql_query($sql_quotes);
	$rs_quotes = mysql_fetch_array($query_quotes); 

	
	for($i=1;$i<=$num_rows;$i++)
	{
		$sub_prod_query = $adb->pquery("SELECT productid from aicrm_inventorysubproductrel WHERE id=? AND sequence_no=?",array($focus->id,$i));
		$subprodname_str='';
		if($adb->num_rows($sub_prod_query)>0){
			for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
				$sprod_id = $adb->query_result($sub_prod_query,$j,'productid');
				$sprod_name = getProductName($sprod_id);
				$str_sep = "";
				if($j>0) $str_sep = ":";
				$subprodname_str .= $str_sep." - ".$sprod_name;
			}
		}

		$subprodname_str = str_replace(":","<br>",$subprodname_str);
		$productid=$adb->query_result($result,$i-1,'productid');
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
		//For Product Name
		$output .= '<tr valign="top">
				<td class="crmTableRow small lineOnTop" style="width:15%">'.$productname.'</td>';
		/*$output .= '<td class="crmTableRow small lineOnTop" align="center">'.$producttype.'</td>';*/
		$output .= '<td class="crmTableRow small lineOnTop" align="center">'.$km.'</td>';

		if($producttype == 'Product'){
			$output .= '<td class="crmTableRow small lineOnTop" align="right">'.$zone.'</td>';
		}else{
			$output .= '<td class="crmTableRow small lineOnTop"></td>';
		}
		
		if($producttype == 'Product'){
			$output .= '<td class="crmTableRow small lineOnTop" align="right">'.$carsize.'</td>';
		}else{
			$output .= '<td class="crmTableRow small lineOnTop"></td>';
		}

		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$unit.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.$number.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($priceperunit, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($amount, 2, '.', ',').'</td>';
		
		if($producttype == 'Product'){
			$output .= '<td class="crmTableRow small lineOnTop" align="right">'.$min.'</td>';
		}else{
			$output .= '<td class="crmTableRow small lineOnTop"></td>';
		}

		if($producttype == 'Product'){
			$output .= '<td class="crmTableRow small lineOnTop" align="right">'.$dlv_c.'</td>';
		}else{
			$output .= '<td class="crmTableRow small lineOnTop"></td>';
		}

		if($producttype == 'Product'){
			$output .= '<td class="crmTableRow small lineOnTop" align="right">'.$dlv_cvat.'</td>';
		}else{
			$output .= '<td class="crmTableRow small lineOnTop"></td>';
		}

		if($producttype == 'Product'){
			$output .= '<td class="crmTableRow small lineOnTop" align="right">'.$dlv_pvat.'</td>';
		}else{
			$output .= '<td class="crmTableRow small lineOnTop"></td>';
		}

		if($producttype == 'Product'){
			$output .= '<td class="crmTableRow small lineOnTop" align="right">'.$lp.'</td>';
		}else{
			$output .= '<td class="crmTableRow small lineOnTop"></td>';
		}

		if($producttype == 'Product'){
			$output .= '<td class="crmTableRow small lineOnTop" align="right">'.$discount.'</td>';
		}else{
			$output .= '<td class="crmTableRow small lineOnTop"></td>';
		}

		if($producttype == 'Product'){
			$output .= '<td class="crmTableRow small lineOnTop" align="right">'.$c_cost.'</td>';
		}else{
			$output .= '<td class="crmTableRow small lineOnTop"></td>';
		}
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($afterdiscount, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($purchaseamount, 2, '.', ',').'</td>';
		$output .= '</tr>';
	}

	$output .= '<tr valign="top">
			   	 <td colspan="7" class="crmTableRow small lineOnTop" align="right"><b>รวม</b></td>
				 <td colspan="1" id="subTotal1" class="crmTableRow small lineOnTop" align="right">'.number_format($subtotal1, 2, '.', ',').'</td>
				 <td colspan="8" class="crmTableRow small lineOnTop" align="right"><b>รวม</b></td>
				 <td colspan="1" id="subTotal2" class="crmTableRow small lineOnTop" align="right">'.number_format($subtotal2, 2, '.', ',').'</td>
			    </tr>';


	$output .= '<tr valign="top">
			   	<td colspan="7" class="crmTableRow small lineOnTop" align="right"><b>ภาษีมุลค่าเพิ่ม 7%</b></td>
				<td colspan="1" id="Vat1" class="crmTableRow small lineOnTop" align="right">'.number_format($vat1, 2, '.', ',').'</td>
				<td colspan="8" class="crmTableRow small lineOnTop" align="right"><b>ภาษีมุลค่าเพิ่ม 7%</b></td>
				<td colspan="1" id="Vat2" class="crmTableRow small lineOnTop" align="right">'.number_format($vat2, 2, '.', ',').'</td>
			   </tr>';


	$output .= '<tr valign="top">
			   	<td colspan="7" class="crmTableRow small lineOnTop" align="right"><b>รวมทั้งสิ้น</b></td>
				<td colspan="1" id="Total1" class="crmTableRow small lineOnTop" align="right">'.number_format($total1, 2, '.', ',').'</td>
				<td colspan="8" class="crmTableRow small lineOnTop" align="right"><b>รวมทั้งสิ้น</b></td>
				<td colspan="1" id="Total2" class="crmTableRow small lineOnTop" align="right">'.number_format($total2, 2, '.', ',').'</td>
			   </tr>';

	$output .= '</table>';

	$log->debug("Exiting getDetailAssociatedProductsOrder method ...");
	return $output;
}

function getDetailAssociatedSamplerequisition($module,$focus)
{

	global $log;
	$log->debug("Entering getDetailAssociatedSamplerequisition(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	
	$output = '';
	$colspan=2;
	$output .= '

	<table width="100%"  border="0" align="center" cellpadding="8" cellspacing="0" class="crmTable" id="proTab">
	   <tr valign="top">
		<td colspan="8" class="dvInnerHeader"><b>'.$app_strings['LBL_ITEM_DETAILS'].'</b></td>
	   </tr>';

	$output .= '<tr valign="top">
					<td width=5% class="lvtCol" align="center"><b>ลำดับ</td>
					<td width=35% class="lvtCol"><b>รายการสินค้า</b></td>
					<td width="10%" class="lvtCol" align="center"><b>ชนิดผิว</b></td>
					<td width="10%" class="lvtCol" align="center"><b>ขนาด (มม.)</b></td>
					<td width=10% class="lvtCol" align="center"><b>ความหนา (มม.)</b></td>
					<td width=10% class="lvtCol" align="center"><b>หน่วยนับ</b></td>
					<td width=10% class="lvtCol" align="center"><b>จำนวน (แผ่น)</b></td>
					<td width="10%" class="lvtCol" align="center"><b>จำนวนที่คาดว่าจะใช้</b></td>
			    </tr>';

	// Add "ORDER BY sequence_no" to retain add order on all inventoryproductrel items
	$query="select aicrm_inventorysamplerequisition.* ,aicrm_products.productname" .
			" from aicrm_inventorysamplerequisition" .
			" left join aicrm_products on aicrm_products.productid=aicrm_inventorysamplerequisition.productid " .
			" where id=? ORDER BY sequence_no";

	$result=$adb->pquery($query, array($focus->id));
	$num_rows=$adb->num_rows($result);
	
	$sql_sample = "select * from aicrm_samplerequisition where samplerequisitionid=".$focus->id;
	$query_sample = mysql_query($sql_sample);
	$rs_sample = mysql_fetch_array($query_sample); 
	
	$total_amount_of_sample=$rs_sample['total_amount_of_sample'];
	$total_amount_of_purchase=$rs_sample['total_amount_of_purchase'];
	
	for($i=1;$i<=$num_rows;$i++)
	{
		$sub_prod_query = $adb->pquery("SELECT productid from aicrm_inventorysamplerequisition WHERE id=? AND sequence_no=?",array($focus->id,$i));
		$subprodname_str='';
		if($adb->num_rows($sub_prod_query)>0){
			for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
				$sprod_id = $adb->query_result($sub_prod_query,$j,'productid');
				$sprod_name = getProductName($sprod_id);
				$str_sep = "";
				if($j>0) $str_sep = ":";
				$subprodname_str .= $str_sep." - ".$sprod_name;
			}
		}

		$subprodname_str = str_replace(":","<br>",$subprodname_str);
		$productid=$adb->query_result($result,$i-1,'productid');
		$productname=$adb->query_result($result,$i-1,'productname');

		$comment=$adb->query_result($result,$i-1,'comment');
		$subproduct_ids=$adb->query_result($result,$i-1,'subproduct_ids');
		$sr_finish=$adb->query_result($result,$i-1,'sr_finish');
		$sr_size_mm=$adb->query_result($result,$i-1,'sr_size_mm');
		$sr_thickness_mm=$adb->query_result($result,$i-1,'sr_thickness_mm');
		$sr_product_unit=$adb->query_result($result,$i-1,'sr_product_unit');
		$amount_of_sample=$adb->query_result($result,$i-1,'amount_of_sample');
		$amount_of_purchase=$adb->query_result($result,$i-1,'amount_of_purchase');

		//For Product Name
		$output .= '<tr valign="top">
				<td class="crmTableRow small lineOnTop" align="center">'. $i .'</td>
				<td class="crmTableRow small lineOnTop" style="width:15%">'.$productname.'<br>'.$comment.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="center">'.$sr_finish.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="center">'.$sr_size_mm.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="center">'.$sr_thickness_mm.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="center">'.$sr_product_unit.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($amount_of_sample, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($amount_of_purchase, 2, '.', ',').'</td>';
		$output .= '</tr>';
	}

	$output .= '<tr valign="top">
			   	 <td colspan="6" class="crmTableRow small lineOnTop" align="right"><b>Total</b></td>
				 <td colspan="1" id="total_amount_of_sample" class="crmTableRow small lineOnTop" align="right">'.number_format($total_amount_of_sample, 2).'</td>
				 <td colspan="1" id="total_amount_of_purchase" class="crmTableRow small lineOnTop" align="right">'.number_format($total_amount_of_purchase, 2).'</td>
			    </tr>';

	$output .= '</table>';

	$log->debug("Exiting getDetailAssociatedSamplerequisition method ...");
	return $output;
}

function getDetailAssociatedPurchasesorder($module,$focus)
{
	global $log;
	$log->debug("Entering getDetailAssociatedPurchasesorder(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	$taxtype = getInventoryTaxType($module,$focus->id);
	$currencytype = getInventoryCurrencyInfo($module, $focus->id);
  	
  	$output .= '
  	<table width="100%"  border="0" align="center" cellpadding="7" cellspacing="0" class="crmTable table-purchasesorder" id="proTab">
		<thead>
		<tr valign="top">
		  	<td colspan="16" class="dvInnerHeader" width="80%" style="border-top-left-radius: 0px;border-top-right-radius: 0px;"><b>'.$app_strings['LBL_ITEM_DETAILS'].'</b></td>
		  	<td class="dvInnerHeader" align="center" colspan="2" style="border-top-left-radius: 0px;border-top-right-radius: 0px;">
			  
		 	</td>
		  	<td class="dvInnerHeader" align="center" colspan="2" style="border-top-left-radius: 0px;border-top-right-radius: 0px;"><b>'.
				$app_strings['LBL_CURRENCY'].' : </b>'. getTranslatedCurrencyString($currencytype['currency_name']). ' ('. $currencytype['currency_symbol'] .')
		  	</td>
		  	<td class="dvInnerHeader" align="center" colspan="2" style="border-top-left-radius: 0px;border-top-right-radius: 0px;"><b>'.
				$app_strings['LBL_TAX_MODE'].' : </b>'.$app_strings[$taxtype].'
		  	</td>
		  	<td class="dvInnerHeader" align="center" colspan="2" cellpadding="5" style ="display:none">&nbsp</td>		
		</tr>
		</thead>
		
		<tbody>

		<tr valign="top">
			<td width="100px" class="lvtCol" align="center">ลำดับ</td>
		  	<td width="300px" class="lvtCol"><font color="red">*</font>
			  <b>ชื่อสินค้า</b>
		  	</td>';
	
		$output .= '
			<td width="100px" class="lvtCol"><b>ยี่ห้อสินค้า</b></td>
			<td width="100px" class="lvtCol"><b>กลุ่มสินค้า</b></td>
			<td width="100px" class="lvtCol"><b>รหัสสินค้า</b></td>
			<td width="100px" class="lvtCol"><b>Prefix</b></td>
			<td width="100px" class="lvtCol"><b>รหัสสีสินค้า</b></td>
			<td width="100px" class="lvtCol"><b>ชื่่อสีสินค้า</b></td>
			<td width="100px" class="lvtCol"><b>ชนิดผิว</b></td>
			<td width="100px" class="lvtCol"><b>ขนาด (ฟุต)</b></td>
			<td width="100px" class="lvtCol"><b>ความหนา (มม.)</b></td>
			<td width="100px" class="lvtCol"><b>เกรดสินค้า</b></td>
			<td width="100px" class="lvtCol"><b>Film</b></td>
			<td width="100px" class="lvtCol"><b>Backprint</b></td>
			<td width="100px" class="lvtCol"><b>จำนวนที่สั่งซื้อใน P/O</b></td>
			<td width="100px" class="lvtCol"><b>GR 90% or 100%</b></td>
			<td width="100px" class="lvtCol"><b>จำนวนสินค้า GR ที่รับเข้า</b></td>
			<td width="100px" class="lvtCol"><b>จำนวนสินค้าชำรุดที่รับเข้า</b></td>
			<td width="100px" class="lvtCol"><b>จำนวนที่เหลือ</b></td>
			<td width="100px" class="lvtCol"><b>GR Qty.%</b></td>
			<td width="100px" class="lvtCol"><b>สถานะรายการที่สั่งซื้อ</b></td>
			<td width="200px" class="lvtCol"><b>ชื่อโครงการ</b></td>
			<td width="200px" class="lvtCol"><b>ผู้รับผิดชอบโครงการ</b></td>
			<td width="100px" class="lvtCol"><b>Price Type</b></td>
			<td width="100px" class="lvtCol"><b>ราคาซื้อ USD ($)</b></td>
			<td width="100px" class="lvtCol"><b>ราคา/หน่วย</b></td>
			<td width="100px" nowrap class="lvtCol" align="center" colspan="2"><b>Total Amount</b></td>
		</tr>';
	
	$query="select aicrm_products.productname as productname ," .
	" aicrm_products.product_no crmno ," .
  	" case when aicrm_products.productid != '' then 'Products' else 'Services' end as entitytype," .
  	" aicrm_products.*," .
  	" aicrm_inventorypurchasesorderrel.*," .
  	" ifnull(aicrm_projects.projectsid,'') as projectsid," .
  	" ifnull(aicrm_projects.projects_name,'') as projects_name," .
  	" ifnull(aicrm_crmentity.smownerid,'') as smownerid," .
  	" ifnull(concat(aicrm_users.first_name,' ',aicrm_users.last_name),'') as fullname" .
  	" from aicrm_inventorypurchasesorderrel" .
  	" left join aicrm_products on aicrm_products.productid=aicrm_inventorypurchasesorderrel.productid ".
  	" left join aicrm_projects on aicrm_projects.projectsid=aicrm_inventorypurchasesorderrel.projectsid ".
  	" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_projects.projectsid ".
  	" left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid ".
  	" where aicrm_inventorypurchasesorderrel.id=? ORDER BY sequence_no";

	$result = $adb->pquery($query, array($focus->id));
	$num_rows=$adb->num_rows($result);
	$netTotal = '0.00';
	   
	$sql_purchases = "select * from aicrm_purchasesorder where purchasesorderid=".$focus->id;
	$query_purchases = mysql_query($sql_purchases);
	$rs_purchases = mysql_fetch_array($query_purchases);
	$count_qty=0;	  
	for($i=1;$i<=$num_rows;$i++){
		/*$sub_prod_query = $adb->pquery("SELECT productid from aicrm_inventorysubproductrel WHERE id=? AND sequence_no=?",array($focus->id,$i));
		$subprodname_str='';
		if($adb->num_rows($sub_prod_query)>0){
			for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
				$sprod_id = $adb->query_result($sub_prod_query,$j,'productid');
				$sprod_name = getProductName($sprod_id);
				$str_sep = "";
				if($j>0) $str_sep = ":";
				$subprodname_str .= $str_sep." - ".$sprod_name;
			}
		}
		$subprodname_str = str_replace(":","<br>",$subprodname_str);*/

		$productid=$adb->query_result($result,$i-1,'productid');
		$entitytype=$adb->query_result($result,$i-1,'entitytype');
		$productname=$adb->query_result($result,$i-1,'product_name');
		$comment=$adb->query_result($result,$i-1,'comment');

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
		$product_backprint=$adb->query_result($result,$i-1,'product_backprint');
		$po_quantity=$adb->query_result($result,$i-1,'po_quantity');
		$gr_percentage=$adb->query_result($result,$i-1,'gr_percentage');
		$gr_quantity=$adb->query_result($result,$i-1,'gr_quantity');
		$defects_quantity=$adb->query_result($result,$i-1,'defects_quantity');
		$remain_quantity=$adb->query_result($result,$i-1,'remain_quantity');
		$gr_qty_percent=$adb->query_result($result,$i-1,'gr_qty_percent');
		$item_status=$adb->query_result($result,$i-1,'item_status');
		$po_price_type=$adb->query_result($result,$i-1,'po_price_type');
		$price_usd=$adb->query_result($result,$i-1,'price_usd');
		$unit_price=$adb->query_result($result,$i-1,'unit_price');
		$crmno=$adb->query_result($result,$i-1,'crmno');
		
		$discount_percent=$adb->query_result($result,$i-1,'discount_percent');
		$discount_amount=$adb->query_result($result,$i-1,'discount_amount');

		$total = $po_quantity*$unit_price;
	  
		$totalAfterDiscount = $total;
		$productDiscount = '0.00';
		if($discount_percent != 'NULL' && $discount_percent != '')
		{
			$productDiscount = $total*$discount_percent/100;
			$totalAfterDiscount = $total-$productDiscount;
			$discount_info_message = "$discount_percent % of $total = $productDiscount";
		}
		elseif($discount_amount != 'NULL' && $discount_amount != '')
		{
			$productDiscount = $discount_amount;
			$totalAfterDiscount = $total-$productDiscount;
			$discount_info_message = $app_strings['LBL_DIRECT_AMOUNT_DISCOUNT']." = $productDiscount";
		}
		else
		{
			$discount_info_message = $app_strings['LBL_NO_DISCOUNT_FOR_THIS_LINE_ITEM'];
		}
	  	//Product wise Discount calculation - ends

		$netprice = $totalAfterDiscount;
		//Calculate the individual tax if taxtype is individual
		if($taxtype == 'individual')
		{
			$taxtotal = '0.00';
			$tax_info_message = $app_strings['LBL_TOTAL_AFTER_DISCOUNT']." = $totalAfterDiscount \\n";
			$tax_details = getTaxDetailsForProduct($productid,'all');
				for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
				{
					$tax_name = $tax_details[$tax_count]['taxname'];
					$tax_label = $tax_details[$tax_count]['taxlabel'];
					$tax_value = getInventoryProductTaxValue($focus->id, $productid, $tax_name);

					$individual_taxamount = $totalAfterDiscount*$tax_value/100;
					$taxtotal = $taxtotal + $individual_taxamount;
					$tax_info_message .= "$tax_label : $tax_value % = $individual_taxamount \\n";
				}
			$tax_info_message .= "\\n ".$app_strings['LBL_TOTAL_TAX_AMOUNT']." = $taxtotal";
			$netprice = $netprice + $taxtotal;
		}
		//For Product Name
		$output .= '
		<tr valign="top">
			<td class="crmTableRow small lineOnTop" align="center">'. $i .'</td>
			<td class="crmTableRow small lineOnTop" align="left">
				'. $crmno.' : '.$productname.'
				<br>'.$comment.'
			</td>';
		  //Upto this added to display the Product name and comment
		
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_brand.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_group.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_code_crm.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_prefix.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_factory_code.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_design_name.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_finish_name.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_size_ft.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_thinkness.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_grade.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_film.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_backprint.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($po_quantity, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$gr_percentage.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($gr_quantity, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($defects_quantity, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($remain_quantity, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($gr_qty_percent, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$item_status.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$projects_name.'</td>';  
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$fullname.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$po_price_type.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($price_usd, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($unit_price, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($total, 2, '.', ',').'</td>';
		$output .= '</tr>';
		
		$netTotal = $netTotal + $netprice;
		$count_qty += $po_quantity;
  	}
	$output .= '</tbody></table>';
	$netTotal = $focus->column_fields['hdnSubTotal'];
	//Display the total, adjustment, S&H details
	$output .= '<table width="100%" border="0" cellspacing="0" cellpadding="10" class="crmTable">';
	$output .= '<tr>';
	$output .= '<td width="88%" class="crmTableRow small" align="right"><b>Total P/O Qty.</td>';
	$output .= '<td width="12%" class="crmTableRow small" align="right"><b>'.number_format($count_qty, 2, '.', ',').'</b></td>';
	$output .= '</tr>';


	$output .= '<tr>';
	$output .= '<td width="88%" class="crmTableRow small" align="right"><b>'.$app_strings['LBL_TOTAL_PRICE'].'</td>';
	$output .= '<td width="12%" class="crmTableRow small" align="right"><b>'.number_format($netTotal, 2, '.', ',').'</b></td>';
	$output .= '</tr>';

  	//Decide discount
  	$finalDiscount = '0.00';
  	$final_discount_info = '0';
  	//if($focus->column_fields['hdnDiscountPercent'] != '') - previously (before changing to prepared statement) the selected option (either percent or amount) will have value and the other remains empty. So we can find the non selected item by empty check. But now with prepared statement, the non selected option stored as 0
	
	if($focus->column_fields['hdnDiscountPercent'] != '0')
	{
	  	$finalDiscount = ($netTotal*$focus->column_fields['hdnDiscountPercent']/100);
	  	$final_discount_info = $focus->column_fields['hdnDiscountPercent']." % of $netTotal = $finalDiscount";
	}
	elseif($focus->column_fields['hdnDiscountAmount'] != '0')
	{
	  	$finalDiscount = $focus->column_fields['hdnDiscountAmount'];
	  	$final_discount_info = $finalDiscount;
	}
  	//Alert the Final Discount amount even it is zero
  	$final_discount_info = $app_strings['LBL_FINAL_DISCOUNT_AMOUNT']." = $final_discount_info";
  	$final_discount_info = 'onclick="alert(\''.$final_discount_info.'\');"';

  	//show hide discount
	$price_type = $focus->column_fields['pricetype'];
	if($price_type == 'Exclude Vat'){
		$total_after_discount = number_format($netTotal - $finalDiscount, 2, '.', ',');
	}else{
		$total_net = (($netTotal - $finalDiscount)*100) / 107;
		$total_after_discount = $focus->column_fields['hdnSubTotal'] - $focus->column_fields['hdnDiscountAmount'];
	}

	$output .= '<tr>';
	$output .= '<td align="right" class="crmTableRow small lineOnTop">(-)&nbsp;<b><a href="javascript:;" '.$final_discount_info.'>'.$app_strings['LBL_DISCOUNT'].'</a></b></td>';
	$output .= '<td align="right" class="crmTableRow small lineOnTop">'.number_format($rs_purchases['discountTotal_final'], 2, '.', ',').'</td>';
	$output .= '</tr>';

	$output .= '<tr>';
	$output .= '<td align="right" class="crmTableRow small lineOnTop">Total After Discount</td>';
	$output .= '<td align="right" class="crmTableRow small lineOnTop">'. number_format($rs_purchases['total_after_discount'], 2, '.', ',') .'</td>';
	$output .= '</tr>';

  	$grandTotal = ($focus->column_fields['hdnGrandTotal'] != '')?$focus->column_fields['hdnGrandTotal']:'0.00';

	if($taxtype == 'group')
	{
	  $taxtotal = '0.00';

	  $price_type = $focus->column_fields['pricetype'];
	  
	  //$tax_info_message = $app_strings['LBL_TOTAL_AFTER_DISCOUNT']." = ".number_format($final_totalAfterDiscount,2)." \\n";
	  //First we should get all available taxes and then retrieve the corresponding tax values
	  $tax_details = getAllTaxes('available','','edit',$focus->id);
	  //if taxtype is group then the tax should be same for all products in aicrm_inventoryproductrel table
	  
	  for($tax_count=0;$tax_count<count($tax_details);$tax_count++)
	  {
		  $tax_name = $tax_details[$tax_count]['taxname'];
		  $tax_label = $tax_details[$tax_count]['taxlabel'];
		  $tax_value = $adb->query_result($result,0,$tax_name);
		  if($tax_value == '' || $tax_value == 'NULL')
			  $tax_value = '0.00';


		  if($price_type == 'Exclude Vat'){
			  $taxamount = ($netTotal-$finalDiscount)*$tax_value/100;
		  }else{
			  $taxamount = ((($netTotal - $finalDiscount)*100) / (100+$tax_value))*$tax_value/100;
		  }
		  $taxtotal = $taxtotal + $taxamount;
		  $tax_info_message .= "Vat : $tax_value % = ".number_format($taxamount,2);
	  }
		  //show hide tax
		  $output .= '<tr>';
		  $output .= '<td align="right" class="crmTableRow small">(+)&nbsp;<b><a href="javascript:;" onclick="alert(\''.$tax_info_message.'\');">Vat</a></b></td>';
		  $output .= '<td align="right" class="crmTableRow small">'.number_format($rs_purchases['tax_final'], 2, '.', ',').'</td>';
		  $output .= '</tr>';
	}

	$output .= '<tr>';
	$output .= '<td align="right" class="crmTableRow small lineOnTop"><b>'.$app_strings['LBL_GRAND_TOTAL'].'</b></td>';
	$output .= '<td align="right" class="crmTableRow small lineOnTop">'.number_format($grandTotal, 2, '.', ',').'</td>';
	$output .= '</tr>';
	$output .= '</table>';

	//echo $output; exit;
  	$log->debug("Exiting getDetailAssociatedPurchasesorder method ...");
  	return $output;
}

function getDetailAssociatedGoodsreceive($module,$focus)
{

	global $log;
	$log->debug("Entering getDetailAssociatedGoodsreceive(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	
	$output = '';
	$colspan=2;
	$output .= '

	<table width="100%"  border="0" align="center" cellpadding="17" cellspacing="0" class="crmTable table-purchasesorder" id="proTab">
	   <tr valign="top">
		<td colspan="21" class="dvInnerHeader"><b>'.$app_strings['LBL_ITEM_DETAILS'].'</b></td>
	   </tr>';

	$output .= '<tr valign="top">
					<td width="5%" class="lvtCol" align="center"><b>ลำดับ</td>
					<td width="35%" class="lvtCol"><b>หมายเลขใบสั่งซื้อ</b></td>
					<td width="5%" class="lvtCol" align="center"><b>Purchase Order Date</b></td>
					<td width="5%" class="lvtCol" align="center"><b>ชื่อโครงการ</b></td>
					<td width="5%" class="lvtCol" align="center"><b>ผู้รับผิดชอบโครงการ</b></td>
					<td width="5%" class="lvtCol" align="center"><b>รหัสสินค้า</b></td>
					<td width="5%" class="lvtCol" align="center"><b>ชื่อสินค้า</b></td>
					<td width="5%" class="lvtCol" align="center"><b>ลำดับที่สินค้าใน P/O</b></td>
					<td width="5%" class="lvtCol" align="center"><b>จำนวนที่สั่งซื้อใน P/O</b></td>
					<td width="5%" class="lvtCol" align="center"><b>GR 90% or 100%</b></td>
					<td width="5%" class="lvtCol" align="center"><b>สถานะรายการที่สั่งซื้อ</b></td>
					<td width="5%" class="lvtCol" align="center"><b>GR Qty.%</b></td>
					<td width="5%" class="lvtCol" align="center"><b>จำนวนที่เหลือ</b></td>
					<td width="5%" class="lvtCol" align="center"><b>ราคา/หน่วย</b></td>
					<td width="5%" class="lvtCol" align="center"><b>จำนวนสินค้า GR ที่รับเข้าทั้งหมด</b></td>
					<td width="5%" class="lvtCol" align="center"><b>จำนวนสินค้า Defects ที่รับเข้าทั้งหมด</b></td>
					<td width="5%" class="lvtCol" align="center"><b>ราคารวมทั้งหมด</b></td>
					<td width="5%" class="lvtCol" align="center"><b>หมายเหตุสินค้าชำรุด</b></td>
			    </tr>';

	// Add "ORDER BY sequence_no" to retain add order on all inventoryproductrel items
	$query="select aicrm_inventorygoodsreceive.* ,
			aicrm_products.productid,
			aicrm_products.productname,
			aicrm_purchasesorder.purchasesorderid,
			aicrm_purchasesorder.purchasesorder_name,
			aicrm_purchasesorder.purchasesorder_no,
			aicrm_projects.projectsid,
			aicrm_projects.projects_no,
			aicrm_projects.projects_name,
			ifnull(concat(aicrm_users.first_name,' ',aicrm_users.last_name),'') as fullname" .
			" from aicrm_inventorygoodsreceive" .
			" left join aicrm_purchasesorder on aicrm_purchasesorder.purchasesorderid=aicrm_inventorygoodsreceive.purchasesorderid " .
			" left join aicrm_products on aicrm_products.productid=aicrm_inventorygoodsreceive.productid " .
			" left join aicrm_projects on aicrm_projects.projectsid=aicrm_inventorygoodsreceive.projectsid " .
			" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_projects.projectsid ".
  			" left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid ".
			" where aicrm_inventorygoodsreceive.id=? ORDER BY sequence_no";
	
	$result=$adb->pquery($query, array($focus->id));
	$num_rows=$adb->num_rows($result);
	
	/*$sql_sample = "select * from aicrm_samplerequisition where samplerequisitionid=".$focus->id;
	$query_sample = mysql_query($sql_sample);
	$rs_sample = mysql_fetch_array($query_sample); */
	//$total_amount_of_sample=$rs_sample['total_amount_of_sample'];
	//$total_amount_of_purchase=$rs_sample['total_amount_of_purchase'];
	
	for($i=1;$i<=$num_rows;$i++)
	{
		/*$sub_prod_query = $adb->pquery("SELECT productid from aicrm_inventorysamplerequisition WHERE id=? AND sequence_no=?",array($focus->id,$i));
		$subprodname_str='';
		if($adb->num_rows($sub_prod_query)>0){
			for($j=0;$j<$adb->num_rows($sub_prod_query);$j++){
				$sprod_id = $adb->query_result($sub_prod_query,$j,'productid');
				$sprod_name = getProductName($sprod_id);
				$str_sep = "";
				if($j>0) $str_sep = ":";
				$subprodname_str .= $str_sep." - ".$sprod_name;
			}
		}
		$subprodname_str = str_replace(":","<br>",$subprodname_str);*/

		$purchasesorderid=$adb->query_result($result,$i-1,'purchasesorderid');
		$purchasesorder_name=$adb->query_result($result,$i-1,'purchasesorder_name');
		$purchasesorder_no=$adb->query_result($result,$i-1,'purchasesorder_no');
		$purchase_order_date=$adb->query_result($result,$i-1,'purchase_order_date');
		
		$projectsid=$adb->query_result($result,$i-1,'projectsid');
		$projects_code=$adb->query_result($result,$i-1,'projects_no');
		$projects_name=$adb->query_result($result,$i-1,'projects_name');
		$fullname=$adb->query_result($result,$i-1,'fullname');
		$assignto=$adb->query_result($result,$i-1,'assignto');
		$productid=$adb->query_result($result,$i-1,'productid');
		$productname=$adb->query_result($result,$i-1,'productname');
		$comment=$adb->query_result($result,$i-1,'comment');
		
		$product_code_crm=$adb->query_result($result,$i-1,'product_code_crm');
		$po_detail_no=$adb->query_result($result,$i-1,'po_detail_no');
		$po_quantity=$adb->query_result($result,$i-1,'po_quantity');
		$gr_quantity=$adb->query_result($result,$i-1,'gr_quantity');
		$defects_quantity=$adb->query_result($result,$i-1,'defects_quantity');
		$remain_quantity=$adb->query_result($result,$i-1,'remain_quantity');
		$unit_price=$adb->query_result($result,$i-1,'unit_price');
		$amount=$adb->query_result($result,$i-1,'amount');
		$total_defects_quantity=$adb->query_result($result,$i-1,'total_defects_quantity');
		$total_gr_quantity=$adb->query_result($result,$i-1,'total_gr_quantity');
		$total_amount=$adb->query_result($result,$i-1,'total_amount');
		$defects_remark=$adb->query_result($result,$i-1,'defects_remark');

		$gr_percentage=$adb->query_result($result,$i-1,'gr_percentage');
		$item_status=$adb->query_result($result,$i-1,'item_status');
		$gr_qty_percent=$adb->query_result($result,$i-1,'gr_qty_percent');
		//For Product Name
		$output .= '<tr valign="top">
				<td class="crmTableRow small lineOnTop" align="center">'. $i .'</td>
				<td class="crmTableRow small lineOnTop" style="width:15%">'.$purchasesorder_no.'<br>'.$comment.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$purchase_order_date.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$projects_code.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$assignto.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_code_crm.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$productname.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.$po_detail_no.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($po_quantity, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.$gr_percentage.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.$item_status.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($gr_qty_percent, 2, '.', ',').'</td>';
		//$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($gr_quantity, 2, '.', ',').'</td>';
		//$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($defects_quantity, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($remain_quantity, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($unit_price, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($total_gr_quantity, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($total_defects_quantity, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($total_amount, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$defects_remark.'</td>';
		$output .= '</tr>';
	}
	$output .= '</table>';
	
	$log->debug("Exiting getDetailAssociatedGoodsreceive method ...");
	return $output;
}

function getDetailAssociatedPriceList($module,$focus)
{

	global $log;
	$log->debug("Entering getDetailAssociatedPriceList(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	
	$output = '';
	$colspan=2;
	$output .= '

	<table width="100%"  border="0" align="center" cellpadding="9" cellspacing="0" class="crmTable table-purchasesorder" id="proTab">
	   <tr valign="top">
		<td colspan="11" class="dvInnerHeader"><b>'.$app_strings['LBL_ITEM_DETAILS'].'</b></td>
	   </tr>';

	$output .= '<tr valign="top">
			<td width=4% valign="top" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
			<td width=24% class="lvtCol"><font color=\'red\'>* </font><b>รายการสินค้า</b></td>
			<td width=8% class="lvtCol" align="center"><strong>Brand</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>จำนวนแผ่น/กล่อง</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>สถานะของสินค้า</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Showroom</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>List Price</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Normal</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Tier 1</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Tier 2</strong></td>
			<td width=6% class="lvtCol" align="center"><strong>Tier 3</strong></td>
</tr>';

	// Add "ORDER BY sequence_no" to retain add order on all inventoryproductrel items
	$query="select aicrm_inventorypricelist.* ,
			aicrm_products.productid,
			aicrm_products.productname" .
			" from aicrm_inventorypricelist" .
			" left join aicrm_products on aicrm_products.productid=aicrm_inventorypricelist.productid " .
			" where aicrm_inventorypricelist.id=? ORDER BY sequence_no";
	
	$result=$adb->pquery($query, array($focus->id));
	$num_rows=$adb->num_rows($result);
	
	for($i=1;$i<=$num_rows;$i++)
	{		
		$productid=$adb->query_result($result,$i-1,'productid');
		$productname=$adb->query_result($result,$i-1,'productname');
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
		// $selling_price=$adb->query_result($result,$i-1,'selling_price');
		// $selling_price_inc=$adb->query_result($result,$i-1,'selling_price_inc');

		//For Product Name
		$output .= '<tr valign="top">
				<td class="crmTableRow small lineOnTop" align="center">'. $i .'</td>
				<td class="crmTableRow small lineOnTop" style="width:15%">'.$productname.'<br>'.$comment.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_brand.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($product_weight_per_box, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$productstatus.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($pricelist_showroom, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($listprice_project, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($pricelist_nomal, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($pricelist_first_tier, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($pricelist_second_tier, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($pricelist_third_tier, 2, '.', ',').'</td>';
		$output .= '</tr>';
	}
	$output .= '</table>';
	
	$log->debug("Exiting getDetailAssociatedPriceList method ...");
	return $output;
}

function getDetailAssociatedProjects($module,$focus)
{

	global $log;
	$log->debug("Entering getDetailAssociatedProjects(".$module.",".get_class($focus).") method ...");
	global $adb;
	global $mod_strings;
	global $theme;
	global $log;
	global $app_strings,$current_user;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	
	$output = '';
	$colspan=2;
	$output .= '

	<table width="100%"  border="0" align="center" cellpadding="13" cellspacing="0" class="crmTable table-purchasesorder" id="proTab">
	   <tr valign="top">
		<td colspan="13" class="dvInnerHeader"><b>'.$app_strings['LBL_ITEM_DETAILS'].'</b></td>
	   </tr>';

	$output .= '<tr valign="top">
					<td width="5%" class="lvtCol" align="center"><b>ลำดับ</td>
					<td width="15%" class="lvtCol"><b>Product code</b></td>
					<td width=8% align="center" class="lvtCol"><strong>Brand</strong></td>
					<td width=8% align="center" class="lvtCol"><strong>Product Group</strong></td>
					<td width=10% align="center" class="lvtCol"><strong>Dealer Product</strong></td>
					<td width=6% align="center" class="lvtCol"><strong>Create Act.</strong></td>
					<td width=8% align="center" class="lvtCol"><strong>First Delivered Date</strong></td>
					<td width=8% align="center" class="lvtCol"><strong>Last Delivered Date</strong></td>
					<td width=8% align="center" class="lvtCol"><strong>Estimated</strong></td>
					<td width=7% align="center" class="lvtCol"><strong>Plan</strong></td>
					<td width=7% align="center" class="lvtCol"><strong>Delivered</strong></td>
					<td width=8% align="center" class="lvtCol"><strong>On hand</strong></td>
					<td width=8% align="center" class="lvtCol"><strong>Selling Price</strong></td>
			    </tr>';

	// Add "ORDER BY sequence_no" to retain add order on all inventoryproductrel items
	$query="select aicrm_inventoryprojects.* ,
			aicrm_products.productid,
			aicrm_products.productname,
			aicrm_account.accountid,
			aicrm_account.accountname ".
			" from aicrm_inventoryprojects" .
			" left join aicrm_products on aicrm_products.productid=aicrm_inventoryprojects.productid " .
			" left join aicrm_account on aicrm_account.accountid = aicrm_inventoryprojects.accountid ".
			" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_products.productid ".
			" where aicrm_inventoryprojects.id=? ORDER BY sequence_no";

	$result=$adb->pquery($query, array($focus->id));
	$num_rows=$adb->num_rows($result);

	for($i=1;$i<=$num_rows;$i++)
	{
		$productid=$adb->query_result($result,$i-1,'productid');
		$productname=$adb->query_result($result,$i-1,'productname');
		$comment=$adb->query_result($result,$i-1,'comment');
		
		$product_brand=$adb->query_result($result,$i-1,'product_brand');
		$product_group=$adb->query_result($result,$i-1,'product_group');
		$accountid=$adb->query_result($result,$i-1,'accountid');
		$accountname=$adb->query_result($result,$i-1,'accountname');

		$first_delivered_date=$adb->query_result($result,$i-1,'first_delivered_date');
		$s_delivered_date = '';
		if($first_delivered_date != '0000-00-00'){
			$s_delivered_date = date("d-m-Y", strtotime($first_delivered_date));
		}
		$e_delivered_date = '';
		$last_delivered_date=$adb->query_result($result,$i-1,'last_delivered_date');
		if($last_delivered_date != '0000-00-00'){
			$e_delivered_date = date("d-m-Y", strtotime($last_delivered_date));
		}
		
		$plan=$adb->query_result($result,$i-1,'plan');
		$estimated=$adb->query_result($result,$i-1,'estimated');

		$delivered=$adb->query_result($result,$i-1,'delivered');
		$remain_on_hand=$adb->query_result($result,$i-1,'remain_on_hand');
		$listprice=$adb->query_result($result,$i-1,'listprice');

		//For Product Name
		$output .= '<tr valign="top">
				<td class="crmTableRow small lineOnTop" align="center">'. $i .'</td>
				<td class="crmTableRow small lineOnTop" style="width:15%">'.$productname.'<br>'.$comment.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_brand.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$product_group.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$accountname.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left" style="position:absolute"><button type="button">P</button>&nbsp;<button type="button">D</button></td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$s_delivered_date.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$e_delivered_date.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($plan, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($estimated, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($delivered, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($remain_on_hand, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($listprice, 2, '.', ',').'</td>';
		$output .= '</tr>';
	}
	$output .= '</table>';
	
	//Competitor Product Inomation
	$output .= '

	<table width="100%"  border="0" align="center" cellpadding="8" cellspacing="0" class="crmTable table-purchasesorder" id="proTab">
	   <tr valign="top">
		<td colspan="8" class="dvInnerHeader"><b>Competitor Product Inomation</b></td>
	   </tr>';

	$output .= '<br/><br/>';
	$output .= '<tr valign="top">
					<td width="5%" class="lvtCol" align="center"><b>ลำดับ</td>
					<td width="15%" class="lvtCol"><b>Competitor Product Item</b></td>
					<td width=8% align="left" class="lvtCol"><strong>Competitor Brand</strong></td>
					<td width=8% align="left" class="lvtCol"><strong>Competitor Product Group</strong></td>
					<td width=10% align="left" class="lvtCol"><strong>Competitor Product Size</strong></td>
					<td width=8% align="left" class="lvtCol"><strong>Competitor Product Thickness</strong></td>
					<td width=8% align="left" class="lvtCol"><strong>Competitor Estimated unit</strong></td>
					<td width=8% align="left" class="lvtCol"><strong>Competitor Price</strong></td>
			    </tr>';

	$query="select aicrm_inventorycompetitorproduct.* ,
			aicrm_competitorproduct.competitorproductid,
			aicrm_competitorproduct.competitorproduct_name_th".
			" from aicrm_inventorycompetitorproduct" .
			" left join aicrm_competitorproduct on aicrm_competitorproduct.competitorproductid=aicrm_inventorycompetitorproduct.competitorproductid " .
			" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_competitorproduct.competitorproductid ".
			" where aicrm_inventorycompetitorproduct.id=? ORDER BY sequence_no";

	$result=$adb->pquery($query, array($focus->id));
	$num_rows=$adb->num_rows($result);
	
	for($i=1;$i<=$num_rows;$i++)
	{
		$competitorproductid=$adb->query_result($result,$i-1,'competitorproductid');
		$competitorproduct_name_th=$adb->query_result($result,$i-1,'competitorproduct_name_th');
		$competitorcomment=$adb->query_result($result,$i-1,'competitorcomment');
		
		$competitor_brand=$adb->query_result($result,$i-1,'competitor_brand');
		$comprtitor_product_group=$adb->query_result($result,$i-1,'comprtitor_product_group');
		$comprtitor_product_size=$adb->query_result($result,$i-1,'comprtitor_product_size');
		$comprtitor_product_thickness=$adb->query_result($result,$i-1,'comprtitor_product_thickness');
		$comprtitor_estimated_unit=$adb->query_result($result,$i-1,'comprtitor_estimated_unit');
		$competitor_price=$adb->query_result($result,$i-1,'competitor_price');

		//For Product Name
		$output .= '<tr valign="top">
				<td class="crmTableRow small lineOnTop" align="center">'. $i .'</td>
				<td class="crmTableRow small lineOnTop" style="width:15%">'.$competitorproduct_name_th.'<br>'.$competitorcomment.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$competitor_brand.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$comprtitor_product_group.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$comprtitor_product_size.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="left">'.$comprtitor_product_thickness.'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($comprtitor_estimated_unit, 2, '.', ',').'</td>';
		$output .= '<td class="crmTableRow small lineOnTop" align="right">'.number_format($competitor_price, 2, '.', ',').'</td>';
		$output .= '</tr>';
	}

	$output .= '</table>';

	$log->debug("Exiting getDetailAssociatedProjects method ...");
	return $output;
}
?>
