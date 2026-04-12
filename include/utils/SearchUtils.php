<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

require_once('include/database/PearDatabase.php');
require_once('include/database/Postgres8.php');
require_once('include/ComboUtil.php'); //new
require_once('include/utils/CommonUtils.php'); //new

$column_array=array('accountid','contact_id','product_id','campaignid','quoteid','vendorid','potentialid','salesorderid','vendor_id','contactid','handler','inspectiontemplateid');
$table_col_array=array('aicrm_account.accountname','aicrm_contactdetails.firstname,aicrm_contactdetails.lastname','aicrm_products.productname','aicrm_campaign.campaignname','aicrm_quotes.quote_no','aicrm_vendor.vendorname','aicrm_potential.potentialname','aicrm_salesorder.salesorder_no','aicrm_vendor.vendorname','aicrm_contactdetails.firstname,aicrm_contactdetails.lastname','aicrm_users.user_name','aicrm_inspectiontemplate.inspectiontemplate_name');
/*$table_col_array=array('aicrm_account.accountname','aicrm_contactdetails.firstname,aicrm_contactdetails.lastname','aicrm_products.productname','aicrm_campaign.campaignname','aicrm_quotes.subject','aicrm_vendor.vendorname','aicrm_potential.potentialname','aicrm_salesorder.subject','aicrm_vendor.vendorname','aicrm_contactdetails.firstname,aicrm_contactdetails.lastname','aicrm_users.user_name');*/
/**This function is used to get the list view header values in a list view during search
*Param $focus - module object
*Param $module - module name
*Param $sort_qry - sort by value
*Param $sorder - sorting order (asc/desc)
*Param $order_by - order by
*Param $relatedlist - flag to check whether the header is for listvie or related list
*Param $oCv - Custom view object
*Returns the listview header values in an array
*/

function getSearchListHeaderValues($focus, $module,$sort_qry='',$sorder='',$order_by='',$relatedlist='',$oCv='')
{
	global $log;
	$log->debug("Entering getSearchListHeaderValues(".get_class($focus).",". $module.",".$sort_qry.",".$sorder.",".$order_by.",".$relatedlist.",".get_class($oCv).") method ...");
        global $adb;
        global $theme;
        global $app_strings;
        global $mod_strings,$current_user;

        $arrow='';
        $qry = getURLstring($focus);
        $theme_path="themes/".$theme."/";
        $image_path=$theme_path."images/";
        $search_header = Array();

        //Get the aicrm_tabid of the module
        //require_once('include/utils/UserInfoUtil.php')
        $tabid = getTabid($module);
        //added for aicrm_customview 27/5
        if($oCv)
        {
           if(isset($oCv->list_fields))
		   {
            $focus->list_fields = $oCv->list_fields;
           }
        }

	//Added to reduce the no. of queries logging for non-admin aicrm_users -- by Minnie-start
	$field_list = array();
	$j=0;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	//echo "<pre>"; print_r($oCv); echo "</pre>"; exit;   
	foreach($focus->list_fields as $name=>$tableinfo)
	{
		$fieldname = $focus->list_fields_name[$name];
		if($oCv)
		{
			if(isset($oCv->list_fields_name))
			{
				$fieldname = $oCv->list_fields_name[$name];
			}
		}
		if($fieldname == "accountname" && $module !="Accounts")
			$fieldname = "account_id";
		
		if($fieldname == "account_family_id" && $module =="Family")
			$fieldname = "account_id";
		
		if($fieldname == "productname" && $module =="Campaigns" || $module =="Opportunity")
			$fieldname = "product_id";

		if($fieldname == "lastname" && $module !="Leads" && $module !="Contacts")
		{
			$fieldname = "contact_id";
		}
		if($fieldname == 'folderid' && $module == 'Documents'){
			$fieldname = 'foldername';
		}
	
		array_push($field_list, $fieldname);
		$j++;
	}

	//Getting the Entries from Profile2 aicrm_field aicrm_table
	if($is_admin == false)
	{
		$profileList = getCurrentUserProfileList();
		//changed to get aicrm_field.fieldname
		$query  = "SELECT aicrm_profile2field.*,aicrm_field.fieldname FROM aicrm_field INNER JOIN aicrm_profile2field ON aicrm_profile2field.fieldid=aicrm_field.fieldid INNER JOIN aicrm_def_org_field ON aicrm_def_org_field.fieldid=aicrm_field.fieldid WHERE aicrm_field.tabid=? AND aicrm_profile2field.visible=0 AND aicrm_def_org_field.visible=0 AND aicrm_profile2field.profileid IN (". generateQuestionMarks($profileList) .") AND aicrm_field.fieldname IN (". generateQuestionMarks($field_list) .") and aicrm_field.presence in (0,2) GROUP BY aicrm_field.fieldid";
 		if( $adb->dbType == "pgsql")
 		    $query = fixPostgresQuery( $query, $log, 0);
		$result = $adb->pquery($query, array($tabid, $profileList, $field_list));
		$field=Array();
		for($k=0;$k < $adb->num_rows($result);$k++)
		{
			$field[]=$adb->query_result($result,$k,"fieldname");
		}

		//if this field array is empty and the user don't have any one of the admin, view all, edit all permissions then the search picklist options will be empty and we cannot navigate the users list - js error will thrown in function getListViewEntries_js in Smarty\templates\Popup.tpl
		if($module == 'Users' && empty($field))
			$field = Array("last_name","email1");
	}
	 
	// Remove fields which are made inactive
	$focus->filterInactiveFields($module);
		//echo "<pre>"; print_r($focus->list_fields); echo "</pre>"; exit;
	    //modified for aicrm_customview 27/5 - $app_strings change to $mod_strings
        foreach($focus->list_fields as $name=>$tableinfo)
        {
            //added for aicrm_customview 27/5
            if($oCv)
            {
                if(isset($oCv->list_fields_name))
				{
					if( $oCv->list_fields_name[$name] == '')
					$fieldname = 'crmid';
					else
					$fieldname = $oCv->list_fields_name[$name];
                }
                else
                {
					if( $focus->list_fields_name[$name] == '')
						$fieldname = 'crmid';
					else
						$fieldname = $focus->list_fields_name[$name];
                }

			if($fieldname == "lastname" && $module !="Leads" && $module !="Contacts")
				$fieldname = "contact_id";
			if($fieldname == "accountname" && $module !="Accounts")
				$fieldname = "account_id";		
			if($fieldname == "productname" && $module =="Campaigns")
				$fieldname = "product_id";
        	}
			else
        	{
				if( $focus->list_fields_name[$name] == ''){
					if($module=="Contacts"){
						$fieldname = 'accountname';
					}else{
						$fieldname = 'crmid';
					}
				}else{
					$fieldname = $focus->list_fields_name[$name];
				}
				if($fieldname == "lastname" && $module !="Leads" && $module !="Contacts")
	                $fieldname = "contact_id";
			}

			$fld_name=$fieldname;
			
			/*if($fieldname == 'contact_id' && $module !="Contacts"){
				//$name = $focus->list_fields_name[$name];
			}
			elseif($fieldname == 'contact_id' && $module =="Contacts"){
				$name = $mod_strings['Reports To']." - ".$mod_strings['LBL_LIST_LAST_NAME'];
			}*/

			//assign the translated string
			//added to fix #5205
			//Added condition to hide the close column in calendar search header
			if($name != $app_strings['Close'])
				$search_header[$fld_name] = getTranslatedString($name);

		}

		if($module == 'HelpDesk' && $fieldname == 'crmid')
		{
            $fld_name=$fieldname;
            $search_header[$fld_name] = getTranslatedString($name);

        }

	$log->debug("Exiting getSearchListHeaderValues method ...");
    return $search_header;
}

/**This function is used to get the where condition for search listview query along with url_string
*Param $module - module name
*Returns the where conditions and url_string values in string format
*/

function Search($module)
{	
	global $log,$default_charset;
        $log->debug("Entering Search(".$module.") method ...");
	$url_string='';
	if(isset($_REQUEST['search_field']) && $_REQUEST['search_field'] !="") {
		$search_column=vtlib_purify($_REQUEST['search_field']);
	}
	if(isset($_REQUEST['search_text']) && $_REQUEST['search_text']!="") {
		// search other characters like "|, ?, ?" by jagi
		$search_string = $_REQUEST['search_text'];
		$stringConvert = function_exists(iconv) ? @iconv("UTF-8",$default_charset,$search_string) : $search_string;
		$search_string=trim($stringConvert);
	}
	if(isset($_REQUEST['searchtype']) && $_REQUEST['searchtype']!="") {
        $search_type=vtlib_purify($_REQUEST['searchtype']);
    	if($search_type == "BasicSearch") {
            $where=BasicSearch($module,$search_column,$search_string);
    	} else if ($search_type == "AdvanceSearch") {
	    
		} else { //Global Search
	
		}
		$url_string = "&search_field=".$search_column."&search_text=".urlencode($search_string)."&searchtype=BasicSearch";
		if(isset($_REQUEST['type']) && $_REQUEST['type'] != '')
			$url_string .= "&type=".vtlib_purify($_REQUEST['type']);
		$log->debug("Exiting Search method ...");
		return $where."#@@#".$url_string;
	}
}

/**This function is used to get user_id's for a given user_name during search
*Param $table_name - aicrm_tablename
*Param $column_name - columnname
*Param $search_string - searchstring value (username)
*Returns the where conditions for list query in string format
*/

function get_usersid($table_name,$column_name,$search_string)
{

	global $log;
        $log->debug("Entering get_usersid(".$table_name.",".$column_name.",".$search_string.") method ...");
	global $adb;
	$user_qry="select distinct(aicrm_users.id)from aicrm_users inner join aicrm_crmentity on aicrm_crmentity.smownerid=aicrm_users.id where aicrm_users.user_name like '" . formatForSqlLike($search_string) . "'";
	$user_result=$adb->pquery($user_qry, array());
	$noofuser_rows=$adb->num_rows($user_result);
	$x=$noofuser_rows-1;
	if($noofuser_rows!=0)
	{
		$where="(";
		for($i=0;$i<$noofuser_rows;$i++)
		{
			$user_id=$adb->query_result($user_result,$i,'id');
			$where .= "$table_name.$column_name =".$user_id;
			if($i != $x)
			{
				$where .= " or ";
			}
		}
		$where.=" or aicrm_groups.groupname like '". formatForSqlLike($search_string) ."')";
	}
	else
	{
		$where=" aicrm_groups.groupname like '". formatForSqlLike($search_string) ."' ";
	}
	$log->debug("Exiting get_usersid method ...");
	return $where;
}

/**This function is used to get where conditions for a given aicrm_accountid or contactid during search for their respective names
*Param $column_name - columnname
*Param $search_string - searchstring value (username)
*Returns the where conditions for list query in string format
*/


function getValuesforColumns($column_name,$search_string,$criteria='cts')
{
	global $log, $current_user;
	$log->debug("Entering getValuesforColumns(".$column_name.",".$search_string.") method ...");
	global $column_array,$table_col_array;

	if($_REQUEST['type'] == "entchar")
		$criteria = "is";

	for($i=0; $i<count($column_array);$i++)
	{
		if($column_name == $column_array[$i])
		{
			$val=$table_col_array[$i];
			$explode_column=explode(",",$val);
			$x=count($explode_column);
			if($x == 1 )
			{
				$where=getSearch_criteria($criteria,$search_string,$val);
			}
			else
			{
				if($column_name == "contact_id" && $_REQUEST['type'] == "entchar") {
					if (getFieldVisibilityPermission('Contacts', $current_user->id,'firstname') == '0') {
						$where .= "concat(aicrm_contactdetails.lastname, ' ', aicrm_contactdetails.firstname) = '$search_string'";
					} else {
						$where .= "aicrm_contactdetails.lastname = '$search_string'";
					}
				}
				else {
					$where="(";
					for($j=0;$j<count($explode_column);$j++)
					{
						$where .=getSearch_criteria($criteria,$search_string,$explode_column[$j]);
						if($j != $x-1)
						{
							if($criteria == 'dcts' || $criteria == 'isn')
								$where .= " and ";
							else
								$where .= " or ";
						}
					}
					$where.=")";
				}
			}
			break 1;
		}
	}
	$log->debug("Exiting getValuesforColumns method ...");
	return $where;
}

/**This function is used to get where conditions in Basic Search
*Param $module - module name
*Param $search_field - columnname/field name in which the string has be searched
*Param $search_string - searchstring value (username)
*Returns the where conditions for list query in string format
*/

function BasicSearch($module,$search_field,$search_string){

    global $log,$mod_strings,$current_user;
    $log->debug("Entering BasicSearch(".$module.",".$search_field.",".$search_string.") method ...");
    global $adb;
    $search_string = ltrim(rtrim($adb->sql_escape_string($search_string)));
    global $column_array,$table_col_array;

    if($search_field =='crmid'){
        $column_name='crmid';
        $table_name='aicrm_crmentity';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field =='currency_id' && ($module == 'PriceBooks' || $module == 'PurchaseOrder' || $module == 'SalesOrder' || $module == 'Invoice' || $module == 'Quotes')){
        $column_name='currency_name';
        $table_name='aicrm_currency_info';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'folderid' && $module == 'Documents'){
        $column_name='foldername';
        $table_name='aicrm_attachmentsfolder';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'parent_id'){
        $column_name='accountname';
        $table_name='aicrm_account ';
        $where="aicrm_account.accountname like '". formatForSqlLike($search_string) ."' or aicrm_contactdetails.firstname  like '". formatForSqlLike($search_string) ."' or aicrm_contactdetails.lastname  like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'ticketid' && $module != 'HelpDesk'){
        $column_name='title';
        $table_name='aicrm_troubletickets';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";   
    }elseif($search_field == 'serialid' && $module != 'Serial'){
        $column_name='serial_name';
        $table_name='aicrm_serial';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'sparepartid' && $module != 'Sparepart'){
        $column_name='sparepart_no';
        $table_name='aicrm_sparepart';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'account_id' && $module != 'Accounts'){
        $column_name='accountname';
        $table_name='aicrm_account';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'product_id' && $module != 'Products'){
        $column_name='productname';
        $table_name='aicrm_products';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'jobid' && $module != 'Job'){
        $column_name='job_no';
        $table_name='aicrm_jobs';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'errorsid' && $module != 'Errors'){
        $column_name='errors_no';
        $table_name='aicrm_errors';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'productname' && $module != 'Products'){
        $column_name='productname';
        $table_name='aicrm_products';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'plantid' && $module != 'Plant'){
        $column_name='plant_id';
        $table_name='aicrm_plant';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'campaignid' && $module != 'Campaigns'){
        $column_name='campaignname';
        $table_name='aicrm_campaign';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'smcreatorid' && $module != 'Users'){
        $where="Concat(create_by.first_name,' ',create_by.last_name) like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'smcreatorid' && $module != 'Users'){
        $where="Concat(create_by.first_name,' ',create_by.last_name) like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'contact_id' && $module != 'Contacts'){
        $where="Concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'salesorderid' && $module != 'Salesorder'){
        $column_name='salesorder_no';
        $table_name='aicrm_salesorder';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'"; 
    }elseif($search_field == 'dealid' && $module != 'Deal'){
        $column_name='deal_no';
        $table_name='aicrm_deal';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'dealid' && $module != 'Deal'){
        $column_name='deal_no';
        $table_name='aicrm_deal';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'promotionvoucherid' && $module != 'Promotionvoucher'){
        $column_name='promotionvoucher_name';
        $table_name='aicrm_promotionvoucher';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    }elseif($search_field == 'competitorid' && $module != 'Competitor'){
        $column_name='competitor_name';
        $table_name='aicrm_competitor';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";

    }elseif($search_field == 'quoteid' && $module != 'Quotes'){
        $column_name='quote_no';
        $table_name='aicrm_quotes';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
    
    }elseif($search_field == 'promotionid' && $module != 'Promotion'){
        $column_name='promotion_name';
        $table_name='aicrm_promotion';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";

    }elseif($search_field == 'servicerequestid' && $module != 'Servicerequest'){
        $column_name='servicerequest_name';
        $table_name='aicrm_servicerequest';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";

    }elseif($search_field == 'event_id' && $module == 'Calendar'){

        $where="(aicrm_projects.projects_name like '". formatForSqlLike($search_string) ."' or aicrm_jobs.job_no like '". formatForSqlLike($search_string) ."' or aicrm_troubletickets.ticket_no like '". formatForSqlLike($search_string) ."' )";

    }elseif($search_field == 'salesinvoiceid' && $module != 'Salesinvoice'){
        $column_name='salesinvoice_no';
        $table_name='aicrm_salesinvoice';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";

    }elseif($search_field == 'questionnairetemplateid' && $module != 'Questionnairetemplate'){
        $column_name='questionnairetemplate_name';
        $table_name='aicrm_questionnairetemplate';
        $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";

    }elseif($search_field == 'parentid' && ($module == 'Calendar' || $module == 'Deal' || $module == 'Quotes')){

        $where="(aicrm_account.accountname like '". formatForSqlLike($search_string) ."' or concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) like '". formatForSqlLike($search_string) ."')";
	
	}elseif($search_field == 'inspectiontemplateid' && $module != 'Inspectiontemplate'){
			$column_name='inspectiontemplate_name';
			$table_name='aicrm_inspectiontemplate';
			$where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
	}elseif($search_field == 'projectsid' && $module != 'Projects'){
			$column_name='projects_name';
			$table_name='aicrm_projects';
			$where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";      
    }else{
        //Check added for tickets by accounts/contacts in dashboard
        $search_field_first = $search_field;
        if($module=='HelpDesk'){
            if($search_field == 'contactid'){
                $where = "(aicrm_contactdetails.contact_no like '". formatForSqlLike($search_string) ."')";
                return $where;
            }elseif($search_field == 'account_id'){
                $search_field = "parent_id";
            }
        }
        //Check ends

        //Added to search contact name by lastname
        if(($module == "Calendar" || $module == "Documents") && ($search_field == "contact_id")){
            $module = 'Contacts';
            $search_field = 'firstname';
        }
        if($search_field == "accountname" && $module != "Accounts"){
            $search_field = "account_id";
        }
        
        if($search_field == 'productname' && $module == 'Campaigns'){
            $search_field = "product_id";
        }

        if($module == 'Calendar'){
        	$module = 'Events';
        	if($search_field == 'taskstatus'){
            	$search_field = "eventstatus";
       		}
        	//eventstatus
        }

        $qry="select aicrm_field.columnname,tablename from aicrm_tab inner join aicrm_field on aicrm_field.tabid=aicrm_tab.tabid where aicrm_tab.name=? and (fieldname=? or columnname=?)";
        $result = $adb->pquery($qry, array($module, $search_field, $search_field));
        $noofrows = $adb->num_rows($result);

        if($noofrows!=0)
        {
            $column_name=$adb->query_result($result,0,'columnname');

            //Check added for tickets by accounts/contacts in dashboard
            if ($column_name == 'parent_id')
            {
                if ($search_field_first	== 'account_id') $search_field_first = 'accountid';
                if ($search_field_first	== 'contactid') $search_field_first = 'contact_id';
                $column_name = $search_field_first;
            }

            //Check ends
            $table_name=$adb->query_result($result,0,'tablename');
            $uitype=getUItype($module,$column_name);
  
            //Added to support user date format in basic search
            if($uitype == 5 || $uitype == 6 || $uitype == 23 || $uitype == 70)
            {
                list($sdate,$stime) = split(" ",$search_string);
                if($stime !='')
                    $search_string = getDBInsertDateValue($sdate)." ".$stime;
                else
                    $search_string = getDBInsertDateValue($sdate);
            }
            //Added to support user date format in basic search
        
            // Added to fix errors while searching check box type fields(like product active. ie. they store 0 or 1. we search them as yes or no) in basic search.
            if ($uitype == 27)
            {
                if(strtolower($search_string) == 'internal') {
                    $where = "$table_name.$column_name = 'I'";
                }elseif(strtolower($search_string) == 'external') {
                    $where = "$table_name.$column_name = 'E'";
                }else {
                    $where = "$table_name.$column_name = ''";
                }
            }
            if ($uitype == 56)
            {
                if(strtolower($search_string) == 'yes') {
                    $where = "$table_name.$column_name = '1'";
                }elseif(strtolower($search_string) == 'no') {
                    $where = "$table_name.$column_name = '0'";
                }else {
                    $where = "$table_name.$column_name = '-1'";
                }
            }
            elseif($uitype == 51)
            {
                $sql = "select accountid from aicrm_account where  aicrm_account.accountname like '". formatForSqlLike($search_string) ."'";
                $result=$adb->pquery($sql, array());

                if($adb->num_rows($result) > 0){
                    for($k=0;$k < $adb->num_rows($result);$k++)
                    {
                        $a_accountid[]=$adb->query_result($result,$k,"accountid");
                    }
                    $s_accountid = implode("','",$a_accountid);
                    $where="$table_name.$column_name in ('".$s_accountid ."') ";

                }else{
                    $where="$table_name.$column_name in ('0001') ";
                }
            }

            elseif($uitype == 930)
            {
                $sql = "select accountid from aicrm_account where  aicrm_account.accountname like '". formatForSqlLike($search_string) ."'";
                $result=$adb->pquery($sql, array());

                for($k=0;$k < $adb->num_rows($result);$k++)
                {
                    $a_accountid[]=$adb->query_result($result,$k,"accountid");
                }
                $s_accountid = implode("','",$a_accountid);
                if($s_accountid == ''){
                	$where="$table_name.$column_name = '001' ";
                }else{
                	$where="$table_name.$column_name in ('".$s_accountid ."') ";
                }
                //$where="$table_name.$column_name in ('".$s_accountid ."') ";
            }

            elseif($uitype == 931)
            {
                $sql = "select contactid from aicrm_contactdetails where  aicrm_contactdetails.contactname like '". formatForSqlLike($search_string) ."'";
                $result=$adb->pquery($sql, array());

                for($k=0;$k < $adb->num_rows($result);$k++)
                {
                    $a_contactid[]=$adb->query_result($result,$k,"contactid");
                }
                $s_contactid = implode("','",$a_contactid);

                if($s_contactid == ''){
                	$where="$table_name.$column_name = '001' ";
                }else{
                	$where="$table_name.$column_name in ('".$s_contactid ."') ";
                }
            }
            elseif($uitype == 934)
            {
                $sql = "select contactid from aicrm_contactdetails where  aicrm_contactdetails.contactname like '". formatForSqlLike($search_string) ."'";
                $result=$adb->pquery($sql, array());

                for($k=0;$k < $adb->num_rows($result);$k++)
                {
                    $a_contactid[]=$adb->query_result($result,$k,"contactid");
                }
                $s_contactid = implode("','",$a_contactid);

                if($s_contactid == ''){
                	$where="$table_name.$column_name = '001' ";
                }else{
                	$where="$table_name.$column_name in ('".$s_contactid ."') ";
                }
            }
            elseif ($uitype == 15 || $uitype == 16)
            {
                if(is_uitype($uitype, '_picklist_'))
                {
                    // Get all the keys for the for the Picklist value
                    $mod_keys = array_keys($mod_strings, $search_string);
                    if(sizeof($mod_keys) >= 1)
                    {
                        // Iterate on the keys, to get the first key which doesn't start with LBL_      (assuming it is not used in PickList)
                        foreach($mod_keys as $mod_idx=>$mod_key)
                        {
                            $stridx = strpos($mod_key, 'LBL_');
                            // Use strict type comparision, refer strpos for more details
                            if ($stridx !== 0)
                            {
                                $search_string = $mod_key;
                                if(getFieldVisibilityPermission("Calendar", $current_user->id,'taskstatus') == '0' && ($tab_col == "aicrm_activity.status" || $tab_col == "aicrm_activity.eventstatus"))
                                {
                                    $where="(aicrm_activity.status like '". formatForSqlLike($search_string) ."' or aicrm_activity.eventstatus like '". formatForSqlLike($search_string) ."')";
                                }
                                else
                                    $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
                                break;
                            }
                            else //if the mod strings cointains LBL , just return the original search string. Not the key
                                $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
                        }
                    }
                    else
                    {
                        if(getFieldVisibilityPermission("Calendar", $current_user->id,'taskstatus') == '0' && ($table_name == "aicrm_activity" && ($column_name == "status" || $column_name == "eventstatus")))
                        {
                            $where="(aicrm_activity.status like '". formatForSqlLike($search_string) ."' or aicrm_activity.eventstatus like '". formatForSqlLike($search_string) ."')";
                        }
                        else
                            $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
                    }
                }
            }
            elseif($table_name == "aicrm_crmentity" && $column_name == "smownerid")
            {
                $where = get_usersid($table_name,$column_name,$search_string);
            }
            else if(in_array($column_name,$column_array))
            {
                $where = getValuesforColumns($column_name,$search_string);
            }
            else if($_REQUEST['type'] == 'entchar')
            {
                $where="$table_name.$column_name = '". $search_string ."'";
            }
            else
            {
                $where="$table_name.$column_name like '". formatForSqlLike($search_string) ."'";
            }
        }
    }

    if(stristr($where,"like '%%'"))
    {
        $where_cond0=str_replace("like '%%'","like ''",$where);
        $where_cond1=str_replace("like '%%'","is NULL",$where);
        if($module == "Calendar")
            $where = "(".$where_cond0." and ".$where_cond1.")";
        else
            $where = "(".$where_cond0." or ".$where_cond1.")";
    }
    // commented to support searching "%" with the search string.
    if($_REQUEST['type'] == 'alpbt'){
        $where = str_replace_once("%", "", $where);
    }
    //uitype 10 handling
    if($uitype == 10){
        $where = array();
        $sql = "select fieldid from aicrm_field where tabid=? and fieldname=?";
        $result = $adb->pquery($sql, array(getTabid($module), $search_field));

        if($adb->num_rows($result)>0){
            $fieldid = $adb->query_result($result, 0, "fieldid");
            $sql = "select * from aicrm_fieldmodulerel where fieldid=?";
            $result = $adb->pquery($sql, array($fieldid));
            $count = $adb->num_rows($result);
            $searchString = formatForSqlLike($search_string);

            for($i=0;$i<$count;$i++){
                $relModule = $adb->query_result($result, $i, "relmodule");
                $relInfo = getEntityField($relModule);
                $relTable = $relInfo["tablename"];
                $relField = $relInfo["fieldname"];

                if(strpos($relField, 'concat') !== false){
                    $where[] = "$relField like '$searchString'";
                }else{
                    $where[] = "$relTable.$relField like '$searchString'";
                }

            }
            $where = implode(" or ", $where);
        }
        $where = "($where) ";
    }
    //echo $where;
    $log->debug("Exiting BasicSearch method ...");
    return $where;
}

/**This function is used to get where conditions in Advance Search
*Param $module - module name
*Returns the where conditions for list query in string format
*/

function getAdvSearchfields($module)
{
	global $log;
    $log->debug("Entering getAdvSearchfields(".$module.") method ...");
	global $adb;
	global $current_user;
	global $mod_strings,$app_strings;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');

	$tabid = getTabid($module);
        if($tabid==9)
            $tabid="16"; //$tabid="9,16";

	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
	{
		$sql = "select * from aicrm_field ";
		$sql.= " where aicrm_field.tabid in(?) and";
		$sql.= " aicrm_field.displaytype in (1,2,3) and aicrm_field.presence in (0,2)";
		if($tabid == 13 || $tabid == 15)
		{
			$sql.= " and aicrm_field.fieldlabel != 'Add Comment'";
		}
		if($tabid == 14)
		{
			$sql.= " and aicrm_field.fieldlabel != 'Product Image'";
		}
		if($tabid == 9 || $tabid==16)
		{
			$sql.= " and aicrm_field.fieldname not in('notime','duration_minutes','duration_hours')";
		}
		if($tabid == 4)
		{
			$sql.= " and aicrm_field.fieldlabel != 'Contact Image'";
		}
		if($tabid == 13 || $tabid == 10)
		{
			$sql.= " and aicrm_field.fieldlabel != 'Attachment'";
		}
		$sql.= " group by aicrm_field.fieldlabel order by aicrm_field.fieldlabel";
		$params = array($tabid);
	}
	else
	{
		$profileList = getCurrentUserProfileList();
		$sql = "select * from aicrm_field inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid ";
		$sql.= " where aicrm_field.tabid in(?) and";
		$sql.= " aicrm_field.displaytype in (1,2,3) and aicrm_field.presence in (0,2) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0";

		$params = array($tabid);

		if (count($profileList) > 0) {
			$sql.= "  and aicrm_profile2field.profileid in (". generateQuestionMarks($profileList) .")";
			array_push($params, $profileList);
		}

		if($tabid == 13 || $tabid == 15)
		{
			$sql.= " and aicrm_field.fieldlabel != 'Add Comment'";
		}
		if($tabid == 14)
		{
			$sql.= " and aicrm_field.fieldlabel != 'Product Image'";
		}
		if($tabid == 9 || $tabid==16)
		{
			$sql.= " and aicrm_field.fieldname not in('notime','duration_minutes','duration_hours')";
		}
		if($tabid == 4)
		{
			$sql.= " and aicrm_field.fieldlabel != 'Contact Image'";
		}
		if($tabid == 13 || $tabid == 10)
		{
			$sql.= " and aicrm_field.fieldlabel != 'Attachment'";
		}
		$sql.= " group by aicrm_field.fieldlabel order by aicrm_field.fieldlabel";
	}

	$result = $adb->pquery($sql, $params);
	$noofrows = $adb->num_rows($result);
	$block = '';
	$select_flag = '';

	for($i=0; $i<$noofrows; $i++)
	{
		$fieldtablename = $adb->query_result($result,$i,"tablename");
		$fieldcolname = $adb->query_result($result,$i,"columnname");
		$block = $adb->query_result($result,$i,"block");
		$fieldtype = $adb->query_result($result,$i,"typeofdata");
		$fieldtype = explode("~",$fieldtype);
		$fieldtypeofdata = $fieldtype[0];		
		if($fieldcolname == 'productname' && $module != 'Products'){
			$fieldtablename = 'aicrm_products';
			$fieldcolname = 'productname';
			$fieldtypeofdata = "V";
			$OPTION_SET .= "<option value=\'".$fieldtablename.".".$fieldcolname."::::".$fieldtypeofdata."\' ".$select_flag.">".str_replace("'","",$fieldlabel)."</option>";
		}

		if($fieldcolname == 'account_id' || $fieldcolname == 'accountid' || $fieldcolname == 'product_id' || $fieldcolname == 'contact_id' || $fieldcolname == 'contactid' || $fieldcolname == 'potentialid' || $fieldcolname == 'quoteid' || $fieldcolname == 'parentid' || $fieldcolname == "recurringtype" || $fieldcolname == "campaignid" || $fieldcolname == "inventorymanager" ||  $fieldcolname == "handler" ||  $fieldcolname == "currency_id")
			$fieldtypeofdata = "V";
		if($fieldcolname == "productstatus" || $fieldcolname == "active")
			$fieldtypeofdata = "C";
		$fieldlabel = $mod_strings[$adb->query_result($result,$i,"fieldlabel")];

		// Added to display customfield label in search options
		if($fieldlabel == "")
			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");

		if($fieldlabel == "Related To")
		{
			$fieldlabel = "Related to";
		}
		if($fieldlabel == "Start Date & Time")
		{
			$fieldlabel = "Start Date";
			if($module == 'Activities' && $block == 19)
				$module_columnlist['aicrm_activity:time_start::Activities_Start Time:I'] = 'Start Time';
		}
		//Check added to search the lists by Inventory manager
        if($fieldtablename == 'aicrm_notes' && $fieldcolname == 'folderid'){
            $fieldtablename = 'aicrm_attachmentsfolder';
            $fieldcolname = 'foldername';
        }
		if($fieldlabel != 'Related to')
		{
			if ($i==0)
				$select_flag = "selected";

			$mod_fieldlabel = $mod_strings[$fieldlabel];
			if($mod_fieldlabel =="") $mod_fieldlabel = $fieldlabel;

			if($fieldlabel == "Product Code"){
				$OPTION_SET .= "<option value=\'".$fieldtablename.".".$fieldcolname."::::".$fieldtypeofdata."\'".$select_flag.">".$mod_fieldlabel."</option>";
			}
			if($fieldlabel == "Reports To"){
				$OPTION_SET .= "<option value=\'".$fieldtablename.".".$fieldcolname."::::".$fieldtypeofdata."\'".$select_flag.">".$mod_fieldlabel." - ".$mod_strings['LBL_LIST_LAST_NAME']."</option>";
			}
			elseif($fieldcolname == "contactid" || $fieldcolname == "contact_id")
			{
				$OPTION_SET .= "<option value=\'aicrm_contactdetails.contactid::::".$fieldtypeofdata."\' ".$select_flag.">".$mod_fieldlabel."</option>";
			}
			elseif($fieldcolname == "campaignid")
				$OPTION_SET .= "<option value=\'aicrm_campaign.campaignname::::".$fieldtypeofdata."\' ".$select_flag.">".$mod_fieldlabel."</option>";
			else
				$OPTION_SET .= "<option value=\'".$fieldtablename.".".$fieldcolname."::::".$fieldtypeofdata."\' ".$select_flag.">".str_replace("'","",$fieldlabel)."</option>";
		}
	}
	//Added to include Ticket ID in HelpDesk advance search
	if($module == 'HelpDesk')
	{
		$mod_fieldlabel = $mod_strings['Ticket ID'];
        if($mod_fieldlabel =="") $mod_fieldlabel = 'Ticket ID';

		$OPTION_SET .= "<option value=\'aicrm_crmentity.crmid::::".$fieldtypeofdata."\'>".$mod_fieldlabel."</option>";
	}
	//Added to include activity type in activity advance search
	if($module == 'Activities')
	{
		$mod_fieldlabel = $mod_strings['Activity Type'];
        if($mod_fieldlabel =="") $mod_fieldlabel = 'Activity Type';

		$OPTION_SET .= "<option value=\'aicrm_activity.activitytype::::".$fieldtypeofdata."\'>".$mod_fieldlabel."</option>";
	}
	$log->debug("Exiting getAdvSearchfields method ...");
	return $OPTION_SET;
}

/**This function is returns the search criteria options for Advance Search
*takes no parameter
*Returns the criteria option in html format
*/

function getcriteria_options()
{
	global $log,$app_strings;
	$log->debug("Entering getcriteria_options() method ...");
	$CRIT_OPT = "<option value=\'cts\'>".str_replace("'","",$app_strings['contains'])."</option><option value=\'dcts\'>".str_replace("'","",$app_strings['does_not_contains'])."</option><option value=\'is\'>".str_replace("'","",$app_strings['is'])."</option><option value=\'isn\'>".str_replace("'","",$app_strings['is_not'])."</option><option value=\'bwt\'>".str_replace("'","",$app_strings['begins_with'])."</option><option value=\'ewt\'>".str_replace("'","",$app_strings['ends_with'])."</option><option value=\'grt\'>".str_replace("'","",$app_strings['greater_than'])."</option><option value=\'lst\'>".str_replace("'","",$app_strings['less_than'])."</option><option value=\'grteq\'>".str_replace("'","",$app_strings['greater_or_equal'])."</option><option value=\'lsteq\'>".str_replace("'","",$app_strings['less_or_equal'])."</option>";
	$log->debug("Exiting getcriteria_options method ...");
	return $CRIT_OPT;
}

/**This function is returns the where conditions for each search criteria option in Advance Search
*Param $criteria - search criteria option
*Param $searchstring - search string
*Param $searchfield - aicrm_fieldname to be search for
*Returns the search criteria option (where condition) to be added in list query
*/

function getSearch_criteria($criteria,$searchstring,$searchfield,$uitype='')
{	
	global $log,$adb;
	$log->debug("Entering getSearch_criteria(".$criteria.",".$searchstring.",".$searchfield.") method ...");
	$searchstring = ltrim(rtrim($searchstring));
	
	if(($searchfield != "aicrm_troubletickets.update_log") && ($searchfield == "aicrm_crmentity.modifiedtime" || $searchfield == "aicrm_crmentity.createdtime" || stristr($searchfield,'date')))
	{
		list($sdate,$stime) = split(" ",$searchstring);
		if($stime !='')
			$searchstring = getDBInsertDateValue($sdate)." ".$stime;
		else
			$searchstring = getDBInsertDateValue($sdate);
	}
	if($uitype != ''){
		if($uitype == "51"){
			$sql = "SELECT accountid FROM aicrm_account ";
			$taable_name = 'aicrm_account';
			$id = "accountid";
			$field_name = "aicrm_account.accountname ";

		}else if($uitype == "57"){
			$sql = "SELECT contactid FROM aicrm_contactdetails ";
			$taable_name = 'aicrm_contactdetails';
			$id = "contactid";
			$field_name = "concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) ";

		}else if($uitype == "58"){
			$sql = "SELECT campaignid FROM aicrm_campaign ";
			$taable_name = 'aicrm_campaign';
			$id = "campaignid";
			$field_name = "aicrm_campaign.campaignname  ";

		}else if($uitype == "59"){

			$sql = "SELECT productid FROM aicrm_products ";
			$taable_name = 'aicrm_products';
			$id = "productid";
			$field_name = "aicrm_products.productname ";

		}else if($uitype == "301"){
			$sql = "SELECT dealid FROM aicrm_deal ";
			$taable_name = 'aicrm_deal';
			$id = "dealid";
			$field_name = "aicrm_deal.deal_no ";

		}else if($uitype == "302"){
			$sql = "SELECT competitorid FROM aicrm_competitor ";
			$taable_name = 'aicrm_competitor';
			$id = "competitorid";
			$field_name = "aicrm_competitor.competitor_name ";

		}else if($uitype == "303"){
			$sql = "SELECT promotionvoucherid FROM aicrm_promotionvoucher ";
			$taable_name = 'aicrm_promotionvoucher';
			$id = "promotionvoucherid";
			$field_name = "aicrm_promotionvoucher.promotionvoucher_name ";

		}else if($uitype == "304"){
			$sql = "SELECT promotionid FROM aicrm_promotion ";
			$taable_name = 'aicrm_promotion';
			$id = "promotionid";
			$field_name = "aicrm_promotion.promotion_name ";

		}else if($uitype == "305"){
			$sql = "SELECT salesorderid FROM aicrm_salesorder ";
			$taable_name = 'aicrm_salesorder';
			$id = "salesorderid";
			$field_name = "aicrm_salesorder.salesorder_no ";

		}else if($uitype == "307"){
			$sql = "SELECT quoteid FROM aicrm_quotes ";
			$taable_name = 'aicrm_quotes';
			$id = "quoteid";
			$field_name = "aicrm_quotes.quote_no ";

		}else if($uitype == "308"){
			$sql = "SELECT servicerequestid FROM aicrm_servicerequest ";
			$taable_name = 'aicrm_servicerequest';
			$id = "servicerequestid";
			$field_name = "aicrm_servicerequest.servicerequest_name ";

		}else if($uitype == "309"){
			$sql = "SELECT ticketid FROM aicrm_troubletickets ";
			$taable_name = 'aicrm_troubletickets';
			$id = "ticketid";
			$field_name = "aicrm_troubletickets.title ";

		}else if($uitype == "900"){
			$sql = "SELECT salesinvoiceid FROM aicrm_salesinvoice";
			$taable_name = 'aicrm_salesinvoice';
			$id = "salesinvoiceid";
			$field_name = "aicrm_salesinvoice.salesinvoice_no ";

		}else if($uitype == "910"){
			$sql = "SELECT questionnairetemplateid FROM aicrm_questionnairetemplate";
			$taable_name = 'aicrm_questionnairetemplate';
			$id = "questionnairetemplateid";
			$field_name = "aicrm_questionnairetemplate.questionnairetemplate_name ";

		}else if($uitype == "930"){
			$sql = "SELECT accountid FROM aicrm_account";
			$taable_name = 'aicrm_account';
			$id = "accountid";
			$field_name = "aicrm_account.accountname ";

		}else if($uitype == "931"){
			$sql = "SELECT contactid FROM aicrm_contactdetails";
			$taable_name = 'aicrm_contactdetails';
			$id = "contactid";
			$field_name = "aicrm_contactdetails.contactname ";

		}else if($uitype == "934"){
			$sql = "SELECT contactid FROM aicrm_contactdetails";
			$taable_name = 'aicrm_contactdetails';
			$id = "contactid";
			$field_name = "aicrm_contactdetails.contactname ";

		}else if($uitype == "935"){
			$sql = "SELECT serialid FROM aicrm_serial ";
			$taable_name = 'aicrm_serial';
			$id = "serialid";
			$field_name = "aicrm_serial.serial_name ";
		
		}else if($uitype == "936"){
			$sql = "SELECT sparepartid FROM aicrm_sparepart ";
			$taable_name = 'aicrm_sparepart';
			$id = "sparepartid";
			$field_name = "aicrm_sparepart.sparepart_no ";
		
		}else if($uitype == "937"){
			$sql = "SELECT errorsid FROM aicrm_errors ";
			$taable_name = 'aicrm_errors';
			$id = "errorsid";
			$field_name = "aicrm_errors.errors_no ";
		
		}else if($uitype == "938"){
			$sql = "SELECT jobid FROM aicrm_jobs ";
			$taable_name = 'aicrm_jobs';
			$id = "jobid";
			$field_name = "aicrm_jobs.job_no ";

		}else if($uitype == "939"){
			$sql = "SELECT ticketid FROM aicrm_troubletickets ";
			$taable_name = 'aicrm_troubletickets';
			$id = "ticketid";
			$field_name = "aicrm_troubletickets.ticket_no ";

		}else if($uitype == "941"){
			$sql = "SELECT plantid FROM aicrm_plant ";
			$taable_name = 'aicrm_plant';
			$id = "plantid";
			$field_name = "aicrm_plant.plant_id ";
		}
		//echo 5555; exit;	
		switch($criteria)
		{
			case 'cts':
				$where_string = $field_name." like '". formatForSqlLike($searchstring) ."' ";
				if($searchstring == NULL)
				{
						$where_string = "(".$field_name." like '' or (".$field_name." is NULL)";
				}
				break;
	
			case 'dcts':
				if($searchfield == "aicrm_users.user_name" || $searchfield =="aicrm_groups.groupname")
					$where_string = "(".$field_name." not like '". formatForSqlLike($searchstring) ."')";
				else
					$where_string = "(".$field_name." not like '". formatForSqlLike($searchstring) ."' or ".$field_name."  is null)";
				
				if($searchstring == NULL)
					$where_string = "(".$field_name." not like '' or ".$field_name." is not NULL)";
				break;
	
			case 'is':
				$where_string = $field_name." = '".$searchstring."' ";
				if($searchstring == NULL)
				$where_string = "(".$field_name." is NULL or ".$field_name." = '')";
				break;
	
			case 'isn':
				if($searchfield == "aicrm_users.user_name" || $searchfield =="aicrm_groups.groupname")
					$where_string = "(".$field_name." <> '".$searchstring."')";
				else
					$where_string = "(".$field_name." <> '".$searchstring."' or ".$field_name." is null)";
				if($searchstring == NULL)
				$where_string = "(".$field_name." not like '' and ".$field_name." is not NULL)";
				break;
	
			case 'bwt':
				$where_string = $field_name." like '". formatForSqlLike($searchstring, 2) ."' ";
				break;
	
			case 'ewt':
				$where_string = $field_name." like '". formatForSqlLike($searchstring, 1) ."' ";
				break;
	
			case 'grt':
				$where_string = $field_name." > '".$searchstring."' ";
				break;
	
			case 'lst':
				$where_string = $field_name." < '".$searchstring."' ";
				break;
	
			case 'grteq':
				$where_string = $field_name." >= '".$searchstring."' ";
				break;
	
			case 'lsteq':
				$where_string = $field_name." <= '".$searchstring."' ";
				break;
		}
		
		$sql = $sql." Inner Join aicrm_crmentity on aicrm_crmentity.crmid = ".$taable_name.".".$id." where ".$where_string ."and aicrm_crmentity.deleted = 0";
		//echo $sql; exit;
		$result=$adb->pquery($sql, array());
			if($adb->num_rows($result) > 0){
				 for($k=0;$k < $adb->num_rows($result);$k++){
						$a_refid[]=$adb->query_result($result,$k,$id);
				  }
				$s_refid = implode("','",$a_refid);
				$searchstring = $s_refid ;
			}else{
				$searchstring = '001' ;
			}
		$flag = true;	
	}

	if($searchfield == "aicrm_pricebook.currency_id" || $searchfield == "aicrm_quotes.currency_id" || $searchfield == "aicrm_invoice.currency_id"
			|| $searchfield == "aicrm_purchaseorder.currency_id" || $searchfield == "aicrm_salesorder.currency_id")
		$searchfield = "aicrm_currency_info.currency_name";

	if($searchfield == "aicrm_quotes.projectsid"){
		$searchfield = "aicrm_projects.projects_name";
	}
	
	$where_string = '';
	switch($criteria)
	{
		case 'cts':
			
			if($uitype == "51" || $uitype == "57" || $uitype == "58" || $uitype == "59" || $uitype == "301" || $uitype == "302" || $uitype == "303" || $uitype == "304" || $uitype == "305" || $uitype == "307" || $uitype == "308" || $uitype == "309" || $uitype == "900" || $uitype == "910" || $uitype == '930' || $uitype == '931' || $uitype == '934' || $uitype == "935" || $uitype == '936' || $uitype == "937" || $uitype == "938" || $uitype == "939" || $uitype == "941"){
				$where_string = $searchfield." in ('".$searchstring."') ";
			}else{
				$where_string = $searchfield." like '". formatForSqlLike($searchstring) ."' ";
			}
			
			if($searchstring == NULL)
			{
				$where_string = "(".$searchfield." like '' or ".$searchfield." is NULL)";
			}
			break;

		case 'dcts':
			if($searchfield == "aicrm_users.user_name" || $searchfield =="aicrm_groups.groupname"){
				$where_string = "(".$searchfield." not like '". formatForSqlLike($searchstring) ."')";
			}elseif($uitype == "51" || $uitype == "57" || $uitype == "58" || $uitype == "59" || $uitype == "301" || $uitype == "302" || $uitype == "303" || $uitype == "304" || $uitype == "305" || $uitype == "307" || $uitype == "308" || $uitype == "309" || $uitype == "900" || $uitype == "910" || $uitype == '930' || $uitype == '931' || $uitype == '934' || $uitype == "935" || $uitype == '936' || $uitype == "937" || $uitype == "938" || $uitype == "939" || $uitype == "941"){
				$where_string = "(".$searchfield." in ('".$searchstring."'))";
			}else{
				$where_string = "(".$searchfield." not like '". formatForSqlLike($searchstring) ."' or ".$searchfield." is null)";
			}
			if($searchstring == NULL)
			$where_string = "(".$searchfield." not like '' or ".$searchfield." is not NULL)";
			break;

		case 'is':
			
			if($uitype == "51" || $uitype == "57" || $uitype == "58" || $uitype == "59" || $uitype == "301"  || $uitype == "302" || $uitype == "303" || $uitype == "304" || $uitype == "305" || $uitype == "307" || $uitype == "308" || $uitype == "309" || $uitype == "900" || $uitype == "910" || $uitype == '930' || $uitype == '931' || $uitype == '934' || $uitype == "935" || $uitype == '936' ||  $uitype == "937" || $uitype == "938" || $uitype == "939" || $uitype == "941"){
				$where_string = "(".$searchfield." in ('".$searchstring."'))";
			}else{
				$where_string = $searchfield." = '".$searchstring."' ";
			}
			
			
			if($searchstring == NULL){
				$where_string = "(".$searchfield." is NULL or ".$searchfield." = '')";
			}
			break;

		case 'isn':
			if($searchfield == "aicrm_users.user_name" || $searchfield =="aicrm_groups.groupname"){
				$where_string = "(".$searchfield." <> '".$searchstring."')";
			}elseif($uitype == "51" || $uitype == "57" || $uitype == "58" || $uitype == "59" || $uitype == "301" || $uitype == "302" || $uitype == "303" || $uitype == "304" || $uitype == "305" || $uitype == "307" || $uitype == "308" || $uitype == "309" || $uitype == "900" || $uitype == "910" || $uitype == '930' || $uitype == '931' || $uitype == '934' || $uitype == "935" || $uitype == '936' || $uitype == "937" || $uitype == "938" || $uitype == "939" || $uitype == "941"){
				$where_string = "(".$searchfield." in ('".$searchstring."'))";
			}else{
				$where_string = "(".$searchfield." <> '".$searchstring."' or ".$searchfield." is null)";
			}
			if($searchstring == NULL)
			$where_string = "(".$searchfield." not like '' and ".$searchfield." is not NULL)";
			break;

		case 'bwt':
			$where_string = $searchfield." like '". formatForSqlLike($searchstring, 2) ."' ";
			break;

		case 'ewt':
			$where_string = $searchfield." like '". formatForSqlLike($searchstring, 1) ."' ";
			break;

		case 'grt':
			$where_string = $searchfield." > '".$searchstring."' ";
			break;

		case 'lst':
			$where_string = $searchfield." < '".$searchstring."' ";
			break;

		case 'grteq':
			$where_string = $searchfield." >= '".$searchstring."' ";
			break;

		case 'lsteq':
			$where_string = $searchfield." <= '".$searchstring."' ";
			break;
	}
	
	$log->debug("Exiting getSearch_criteria method ...");
	return $where_string;
}

/**This function is returns the where conditions for search
*Param $currentModule - module name
*Returns the where condition to be added in list query in string format
*/

function getWhereCondition($currentModule)
{	
	global $log,$default_charset,$adb;
	global $column_array,$table_col_array,$mod_strings,$current_user;

    $log->debug("Entering getWhereCondition(".$currentModule.") method ...");

	if($_REQUEST['searchtype']=='advance')
	{
		$adv_string='';
		$url_string='';
		if(isset($_REQUEST['search_cnt']))
		$tot_no_criteria = vtlib_purify($_REQUEST['search_cnt']);

		if($_REQUEST['matchtype'] == 'all'){
			$matchtype = "and";
		}else{
			$matchtype = "or";
		}

		for($i=0; $i<$tot_no_criteria; $i++)
		{
			if($i == $tot_no_criteria-1)
			$matchtype= "";

			$table_colname = 'Fields'.$i;
			$search_condition = 'Condition'.$i;
			$search_value = 'Srch_value'.$i;

			list($tab_col_val,$typeofdata) = split("::::",$_REQUEST[$table_colname]);
			$tab_col = str_replace('\'','',stripslashes($tab_col_val));
			$srch_cond = str_replace('\'','',stripslashes($_REQUEST[$search_condition]));
			$srch_val = $_REQUEST[$search_value];
			$srch_val = function_exists(iconv) ? @iconv("UTF-8",$default_charset,$srch_val) : $srch_val;
			$url_string .="&Fields".$i."=".$tab_col."&Condition".$i."=".$srch_cond."&Srch_value".$i."=".urlencode($srch_val);
			$srch_val = $adb->sql_escape_string($srch_val);
			
			list($tab_name,$column_name) = split("[.]",$tab_col);

			if($currentModule == 'Calendar'){
				$currentModule = 'Events';
			}

			$uitype=getUItype($currentModule,$column_name);
			
			//added to allow  search in check box type fields(ex: product active. it will contain 0 or 1) using yes or no instead of 0 or 1
			if ($uitype == 56)
			{
				if(strtolower($srch_val) == 'yes')
                	$adv_string .= " ".getSearch_criteria($srch_cond,"1",$tab_name.'.'.$column_name)." ".$matchtype;
				elseif(strtolower($srch_val) == 'no')
                	$adv_string .= " ".getSearch_criteria($srch_cond,"0",$tab_name.'.'.$column_name)." ".$matchtype;
				else
					$adv_string .= " ".getSearch_criteria($srch_cond,"-1",$tab_name.'.'.$column_name)." ".$matchtype;
			}
			elseif ($uitype == 15 || $uitype == 16)
			{
				if(is_uitype($uitype, '_picklist_')) {
					// Get all the keys for the for the Picklist value
					$mod_keys = array_keys($mod_strings, $srch_val);
					if(sizeof($mod_keys) >= 1)
					{
						// Iterate on the keys, to get the first key which doesn't start with LBL_      (assuming it is not used in PickList)
						foreach($mod_keys as $mod_idx=>$mod_key) {
							$stridx = strpos($mod_key, 'LBL_');
							// Use strict type comparision, refer strpos for more details
							if ($stridx !== 0)
							{
								$srch_val = $mod_key;
								if(getFieldVisibilityPermission("Calendar", $current_user->id,'taskstatus') == '0' && ($tab_col == "aicrm_activity.status" || $tab_col == "aicrm_activity.eventstatus"))
								{
										if($srch_cond == 'dcts' || $srch_cond == 'isn' || $srch_cond == 'is')
											$re_cond = "and";
										else
											$re_cond = "or";
										if($srch_cond == 'is' && $srch_val !='')
											$re_cond = "or";

										$adv_string .= " (".getSearch_criteria($srch_cond,$srch_val,'aicrm_activity.status')." ".$re_cond;
										$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,'aicrm_activity.eventstatus')." )".$matchtype;
								}
								else
									$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,$tab_name.'.'.$column_name)." ".$matchtype;
								break;
							}
							else //if the key contains the LBL, then return the original srch_val.
								$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,$tab_name.'.'.$column_name)." ".$matchtype;

						}

					}
					else
					{
						if(getFieldVisibilityPermission("Calendar", $current_user->id,'taskstatus') == '0' && ($tab_col == "aicrm_activity.status" || $tab_col == "aicrm_activity.eventstatus"))
						{
								if($srch_cond == 'dcts' || $srch_cond == 'isn' || $srch_cond == 'is')
									$re_cond = "and";
								else
									$re_cond = "or";
								if($srch_cond == 'is' && $srch_val !='')
									$re_cond = "or";

								$adv_string .= " (".getSearch_criteria($srch_cond,$srch_val,'aicrm_activity.status')." ".$re_cond;
								$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,'aicrm_activity.eventstatus')." )".$matchtype;
						}
						else
							$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,$tab_col)." ".$matchtype;
					}
				}
			}
			elseif($tab_col == "aicrm_activity.event_id")
			{
				$adv_string .=getSearch_criteria($srch_cond,$srch_val,'aicrm_projects.projects_name')." or ".getSearch_criteria($srch_cond,$srch_val,'aicrm_troubletickets.ticket_no')." or ".getSearch_criteria($srch_cond,$srch_val,'aicrm_jobs.job_no')." ".$matchtype." ";				
			}
			elseif($tab_col == "aicrm_activity.parentid")
			{	
				$adv_string .=getSearch_criteria($srch_cond,$srch_val,'aicrm_account.accountname')." or ".getSearch_criteria($srch_cond,$srch_val,'concat(aicrm_leaddetails.firstname," ",aicrm_leaddetails.lastname)')." ".$matchtype." ";			
			}
			elseif($tab_col == "aicrm_deal.parentid")
			{	
				$adv_string .=getSearch_criteria($srch_cond,$srch_val,'aicrm_account.accountname')." or ".getSearch_criteria($srch_cond,$srch_val,'concat(aicrm_leaddetails.firstname," ",aicrm_leaddetails.lastname)')." ".$matchtype." ";			
			}
			elseif($tab_col == "aicrm_quotes.parentid")
			{	
				$adv_string .=getSearch_criteria($srch_cond,$srch_val,'aicrm_account.accountname')." or ".getSearch_criteria($srch_cond,$srch_val,'concat(aicrm_leaddetails.firstname," ",aicrm_leaddetails.lastname)')." ".$matchtype." ";			
			}
			elseif($tab_col == "aicrm_crmentity.smownerid")
			{
				$adv_string .= " (".getSearch_criteria($srch_cond,$srch_val,'aicrm_users.user_name')." or";
				$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,'aicrm_groups.groupname')." )".$matchtype;
			}
            elseif($tab_col == "aicrm_crmentity.smcreatorid")
            {
                $adv_string="Concat(create_by.first_name,' ',create_by.last_name) like '". formatForSqlLike($srch_val) ."'";
            }
            elseif($tab_col == "aicrm_crmentity.modifiedby")
            {
                $adv_string="Concat(modified_by.first_name,' ',modified_by.last_name) like '". formatForSqlLike($srch_val) ."'";
            }
			elseif($tab_col == "aicrm_cntactivityrel.contactid")
			{
				$adv_string .= " (".getSearch_criteria($srch_cond,$srch_val,'aicrm_contactdetails.firstname')." or";
				$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,'aicrm_contactdetails.lastname')." )".$matchtype;
			}
			elseif($uitype == "57")
			{	
				$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,$tab_col,$uitype)." ".$matchtype;
			}
			elseif($uitype == "58")
			{	
				$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,$tab_col,$uitype)." ".$matchtype;
			}
			elseif(in_array($column_name,$column_array))
            {
                $adv_string .= " ".getValuesforColumns($column_name,$srch_val,$srch_cond)." ".$matchtype;
            }
           
			elseif($uitype == '51')
			{
				$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,$tab_col,$uitype)." ".$matchtype;
			}

			elseif($uitype == '301' || $uitype == '302' || $uitype == '303' || $uitype == "304" || $uitype == "305" || $uitype == "307" || $uitype == "308" || $uitype == "309" || $uitype == "900" || $uitype == "910" || $uitype == '930' || $uitype == '931' || $uitype == '934' || $uitype == '935' || $uitype == '936' || $uitype == '937' || $uitype == '938' || $uitype == '939' || $uitype == '941')
			{
				$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,$tab_col,$uitype)." ".$matchtype;
			}
			
			else
			{
				$adv_string .= " ".getSearch_criteria($srch_cond,$srch_val,$tab_col)." ".$matchtype;
			}
		}
		
		$where="(".$adv_string.")#@@#".$url_string."&searchtype=advance&search_cnt=".$tot_no_criteria."&matchtype=".vtlib_purify($_REQUEST['matchtype']);
	}
	elseif($_REQUEST['type']=='dbrd')
	{
		$where = getdashboardcondition();
	}
	else
	{
 		$where=Search($currentModule);
	}

	$log->debug("Exiting getWhereCondition method ...");
	return $where;

}

/**This function is returns the where conditions for dashboard and shows the records when clicked on dashboard graph
*Takes no parameter, process the values got from the html request object
*Returns the search criteria option (where condition) to be added in list query
*/

function getdashboardcondition()
{
	global $adb;
	$where_clauses = Array();
	$url_string = "";

	if (isset($_REQUEST['leadsource'])) $lead_source = $_REQUEST['leadsource'];
	if (isset($_REQUEST['date_closed'])) $date_closed = $_REQUEST['date_closed'];
	if (isset($_REQUEST['sales_stage'])) $sales_stage = $_REQUEST['sales_stage'];
	if (isset($_REQUEST['closingdate_start'])) $date_closed_start = $_REQUEST['closingdate_start'];
	if (isset($_REQUEST['closingdate_end'])) $date_closed_end = $_REQUEST['closingdate_end'];
	if(isset($_REQUEST['owner'])) $owner = vtlib_purify($_REQUEST['owner']);
	if(isset($_REQUEST['campaignid'])) $campaign = vtlib_purify($_REQUEST['campaignid']);
	if(isset($_REQUEST['quoteid'])) $quote = vtlib_purify($_REQUEST['quoteid']);
	if(isset($_REQUEST['invoiceid'])) $invoice = vtlib_purify($_REQUEST['invoiceid']);
	if(isset($_REQUEST['purchaseorderid'])) $po = vtlib_purify($_REQUEST['purchaseorderid']);

	if(isset($date_closed_start) && $date_closed_start != "" && isset($date_closed_end) && $date_closed_end != "")
	{
		array_push($where_clauses, "aicrm_potential.closingdate >= ".$adb->quote($date_closed_start)." and aicrm_potential.closingdate <= ".$adb->quote($date_closed_end));
		$url_string .= "&closingdate_start=".$date_closed_start."&closingdate_end=".$date_closed_end;
	}

	if(isset($sales_stage) && $sales_stage!=''){
		if($sales_stage=='Other')
		array_push($where_clauses, "(aicrm_potential.sales_stage <> 'Closed Won' and aicrm_potential.sales_stage <> 'Closed Lost')");
		else
		array_push($where_clauses, "aicrm_potential.sales_stage = ".$adb->quote($sales_stage));
		$url_string .= "&sales_stage=".$sales_stage;
	}
	if(isset($lead_source) && $lead_source != "") {
		array_push($where_clauses, "aicrm_potential.leadsource = ".$adb->quote($lead_source));
		$url_string .= "&leadsource=".$lead_source;
	}
	if(isset($date_closed) && $date_closed != "") {
		array_push($where_clauses, $adb->getDBDateString("aicrm_potential.closingdate")." like ".$adb->quote($date_closed.'%')."");
		$url_string .= "&date_closed=".$date_closed;
	}
	if(isset($owner) && $owner != ""){
		$user_qry="select aicrm_users.id from aicrm_users where aicrm_users.user_name = ?";
		$res = $adb->pquery($user_qry, array($owner));
		$uid = $adb->query_result($res,0,'id');
		array_push($where_clauses, "aicrm_crmentity.smownerid = ".$uid);
		$url_string .= "&owner=".$owner;
	}
	if(isset($campaign) && $campaign != "")
	{
		array_push($where_clauses, "aicrm_campaigncontrel.campaignid = ".$campaign);
                $url_string .= "&campaignid=".$campaign;
	}
	if(isset($quote) && $quote != "")
	{
		array_push($where_clauses, "aicrm_inventoryproductrel.id = ".$quote);
		$url_string .= "&quoteid=".$quote;
	}
	if(isset($invoice) && $invoice != "")
	{
		array_push($where_clauses, "aicrm_inventoryproductrel.id = ".$invoice);
		$url_string .= "&invoiceid=".$invoice;
	}
	if(isset($po) && $po != "")
	{
		array_push($where_clauses, "aicrm_inventoryproductrel.id = ".$po);
		$url_string .= "&purchaseorderid=".$po;
	}
	if(isset($_REQUEST['from_homepagedb']) && $_REQUEST['from_homepagedb'] != '') {
		$url_string .= "&from_homepagedb=".vtlib_purify($_REQUEST['from_homepagedb']);
	}
	if(isset($_REQUEST['type']) && $_REQUEST['type'] != '') {
		$url_string .= "&type=".vtlib_purify($_REQUEST['type']);
	}

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}
	return $where."#@@#".$url_string;
}

/**This function is used to replace only the first occurence of a given string
Param $needle - string to be replaced
Param $replace - string to be replaced with
Param $replace - given string
Return type is string
*/
function str_replace_once($needle, $replace, $haystack)
{
	// Looks for the first occurence of $needle in $haystack
	// and replaces it with $replace.
	$pos = strpos($haystack, $needle);
	if ($pos === false) {
		// Nothing found
		return $haystack;
	}
	return substr_replace($haystack, $replace, $pos, strlen($needle));
}

/**
 * Function to get the where condition for a module based on the field table entries
 * @param  string $listquery  -- ListView query for the module
 * @param  string $module     -- module name
 * @param  string $search_val -- entered search string value
 * @return string $where      -- where condition for the module based on field table entries
 */
function getUnifiedWhere($listquery,$module,$search_val,$string_thai=false){
	global $adb, $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');

	$search_val = $adb->sql_escape_string($search_val);
	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0){
		
		if($string_thai == true){
			$query = "SELECT columnname, tablename FROM aicrm_field WHERE tabid = ? and aicrm_field.presence in (0,2) and typeofdata not like '%D~%' and typeofdata not like '%T~%' ";
		}else{
			$query = "SELECT columnname, tablename FROM aicrm_field WHERE tabid = ? and aicrm_field.presence in (0,2) ";
		}

		$qparams = array(getTabid($module));
		
	}else{
		$profileList = getCurrentUserProfileList();
		if($string_thai == true){
			$query = "SELECT columnname, tablename FROM aicrm_field INNER JOIN aicrm_profile2field ON aicrm_profile2field.fieldid = aicrm_field.fieldid INNER JOIN aicrm_def_org_field ON aicrm_def_org_field.fieldid = aicrm_field.fieldid WHERE aicrm_field.tabid = ? AND aicrm_profile2field.visible = 0 AND aicrm_profile2field.profileid IN (". generateQuestionMarks($profileList) . ") AND aicrm_def_org_field.visible = 0 and aicrm_field.presence in (0,2) 
		and aicrm_field.typeofdata not like '%D~%' and aicrm_field.typeofdata not like '%T~%' GROUP BY aicrm_field.fieldid";
		}else{
			$query = "SELECT columnname, tablename FROM aicrm_field INNER JOIN aicrm_profile2field ON aicrm_profile2field.fieldid = aicrm_field.fieldid INNER JOIN aicrm_def_org_field ON aicrm_def_org_field.fieldid = aicrm_field.fieldid WHERE aicrm_field.tabid = ? AND aicrm_profile2field.visible = 0 AND aicrm_profile2field.profileid IN (". generateQuestionMarks($profileList) . ") AND aicrm_def_org_field.visible = 0 and aicrm_field.presence in (0,2) 
		GROUP BY aicrm_field.fieldid";
		}

		$qparams = array(getTabid($module), $profileList);
	}
	$result = $adb->pquery($query, $qparams);
	$noofrows = $adb->num_rows($result);

	$where = '';
	for($i=0;$i<$noofrows;$i++){
		$columnname = $adb->query_result($result,$i,'columnname');
		$tablename = $adb->query_result($result,$i,'tablename');
		
		// Search / Lookup customization
		if($module == 'Contacts' && $columnname == 'accountid') {
			$columnname = "accountname";
			$tablename = "aicrm_account";
		}
		// END

		//Before form the where condition, check whether the table for the field has been added in the listview query
		if(strstr($listquery,$tablename)){
			if($where != ''){
				$where .= " OR ";
			}
			$where .= $tablename.".".$columnname." LIKE '". formatForSqlLike($search_val) ."'";
		}
	}
	return $where;
}

?>