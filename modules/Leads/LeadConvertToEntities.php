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
require_once('modules/Leads/Leads.php');
require_once('include/ComboUtil.php');

// echo '<pre>';
// print_r($_REQUEST);
// echo '</pre>';
// exit();

//Getting the Parameters from the ConvertLead Form
$id = vtlib_purify($_REQUEST["record"]);

$module = vtlib_purify($_REQUEST["module"]);
//$createpotential = $_REQUEST["createpotential"];
//$potential_name = $_REQUEST["potential_name"];
//$deal_name = @$_REQUEST["deal_name"];


//$close_date = getDBInsertDateValue($_REQUEST["closedate"]);
$current_user_id = $_REQUEST["current_user_id"];
$assigned_to = $_REQUEST["assigntype"];

if($assigned_to == "U"){
	$assigned_user_id = $_REQUEST["assigned_user_id"];
}else{
	$assigned_user_id = $_REQUEST["assigned_group_id"];
}

$accountname = $_REQUEST['account_name'];
$idcardno = $_REQUEST['idcardno'];
$createcontact = $_REQUEST["createcontact"];
$contact_name = $_REQUEST["contact_name"];
//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;
//$potential_amount = $_REQUEST['potential_amount'];
//$potential_sales_stage = $_REQUEST['potential_sales_stage'];

global $log,$current_user;
require('user_privileges/user_privileges_'.$current_user->id.'.php');

if($assigned_to == "U"){
	$log->debug("id = $id \n assigned_user_id = $assigned_user_id \n createpotential = $createpotential \n close date = $close_date \n current user id = $current_user_id \n accountname = $accountname \n module = $module");
}else{
	$log->debug("id = $id \n assigned_user_id = $assigned_group_id \n createpotential = $createpotential \n close date = $close_date \n current user id = $current_user_id \n accountname = $accountname \n module = $module");
}

$rate_symbol=getCurrencySymbolandCRate($user_info['currency_id']);
$rate = $rate_symbol['rate'];
if($potential_amount != ''){
	$potential_amount = convertToDollar($potential_amount,$rate);
}

//Retrieve info from all the aicrm_tables related to leads
$focus = new Leads();
$focus->retrieve_entity_info($id,"Leads");

//get all the lead related columns 
$row = $focus->column_fields;

$date_entered = $adb->formatDate(date('Y-m-d H:i:s'), true);
$date_modified = $adb->formatDate(date('Y-m-d H:i:s'), true);

/** Function for getting the custom values from leads and saving to aicrm_account/contact/potential custom aicrm_fields.
 *  @param string $type - Field Type (eg: text, list)
 *  @param integer $type_id - Field Type ID 
*/

function getInsertValues($type,$type_id)
{
	global $id,$adb,$log;

	$log->debug("Entering getInsertValues(".$type.",".$type_id.") method ...");
	$sql_convert_lead="select * from aicrm_convertleadmapping ";
	$convert_result = $adb->pquery($sql_convert_lead, array());
	$noofrows = $adb->num_rows($convert_result);

	$value_cf_array = Array();
	for($i=0;$i<$noofrows;$i++)
	{
		$flag="false";
	 	$log->info("In aicrm_convertleadmapping function");
		$lead_id=$adb->query_result($convert_result,$i,"leadfid");  
		//Getting the relatd customfields for Accounts/Contact/potential from aicrm_convertleadmapping aicrm_table	
		$account_id_val=$adb->query_result($convert_result,$i,"accountfid");
		//$deal_id_val =$adb->query_result($convert_result,$i,"dealfid");
		$contact_id_val=$adb->query_result($convert_result,$i,"contactfid");
		//$potential_id_val=$adb->query_result($convert_result,$i,"potentialfid");
		
		$sql_leads_column="select aicrm_field.uitype,aicrm_field.fieldid,aicrm_field.columnname,aicrm_field.tablename from aicrm_field,aicrm_tab where aicrm_field.tabid=aicrm_tab.tabid and generatedtype=2 and aicrm_tab.name='Leads' and fieldid=? and aicrm_field.presence in (0,2)"; //getting the columnname for the customfield of the lead
		 $log->debug("Lead's custom aicrm_field coumn name is ".$sql_leads_column);

		$lead_column_result = $adb->pquery($sql_leads_column, array($lead_id));

		$leads_no_rows = $adb->num_rows($lead_column_result);
		if($leads_no_rows>0)
		{
			$lead_column_name=$adb->query_result($lead_column_result,0,"columnname");
			$tablename=$adb->query_result($lead_column_result,0,"tablename");
			//echo $lead_column_name; exit;
			$lead_uitype=$adb->query_result($lead_column_result,0,"uitype");
			if($tablename=="aicrm_leadaddress"){
				$sql_leads_val="select $lead_column_name from ".$tablename." where leadaddressid=?"; //custom aicrm_field value for lead	
			}else{
				$sql_leads_val="select $lead_column_name from ".$tablename." where leadid=?"; //custom aicrm_field value for lead	
			}
			
			$lead_val_result = $adb->pquery($sql_leads_val,array($id));
			
			$lead_value=$adb->query_result($lead_val_result,0,$lead_column_name);
			$log->debug("Lead's custom aicrm_field value is ".$lead_value);
		}	
		//Query for getting the column name for Accounts/Contacts/Potentials if custom aicrm_field for lead is mappped
		$sql_type="select aicrm_field.fieldid,aicrm_field.uitype,aicrm_field.columnname from aicrm_field,aicrm_tab where aicrm_field.tabid=aicrm_tab.tabid and generatedtype=2 and aicrm_field.presence in (0,2) and aicrm_tab.name="; 

		$params = array();
		if($type=="Accounts")
		{
			if($account_id_val!="" && $account_id_val!=0)	
			{
				$flag="true";
				$log->info("Getting the  Accounts custom aicrm_field column name  ");
				$sql_type.="'Accounts' and fieldid=?";
				array_push($params, $account_id_val);
			}
		}
		else if($type == "Contacts")
		{	
			if($contact_id_val!="" && $contact_id_val!=0)	
			{
				$flag="true";
				$log->info("Getting the  Contacts custom aicrm_field column name  ");
				$sql_type.="'Contacts' and fieldid=?";
				array_push($params, $contact_id_val);
			}
		}
		else if($type == "Potentials")
		{
			if($potential_id_val!="" && $potential_id_val!=0)
            {
				$flag="true";
                		$log->info("Getting the  Potentials custom aicrm_field column name  ");
				$sql_type.="'Potentials' and fieldid=?";
				array_push($params, $potential_id_val);				
            }
		}
		else if($type == "Deal")
		{
			if($deal_id_val!="" && $deal_id_val!=0)
            {
				$flag="true";
                $log->info("Getting the  Deals custom aicrm_field column name  ");
				$sql_type.="'Deal' and fieldid=?";
				array_push($params, $deal_id_val);		
            }
		}
		
		if($flag=="true")
		{ 
			$type_result=$adb->pquery($sql_type, $params);
			//To construct the cf array
            $colname = $adb->query_result($type_result,0,"columnname");
			
			$type_insert_column[] = $colname;
			$type_uitype =$adb->query_result($type_result,0,"uitype") ;

			//To construct the cf array
			$ins_val = $adb->query_result($lead_val_result,0,$lead_column_name);
			
			//This array is used to store the tablename as the key and the value for that table in the custom field of the uitype only for 15 and 33(Multiselect cf)...
			if($lead_uitype == 33 || $lead_uitype == 15) {
                $lead_val_arr[$colname] = $lead_column_name;
                $value_cf_array[$colname]=$ins_val;	
			}
			
			$insert_value[] = $ins_val;
		}
	}
	if(count($value_cf_array) > 0)
	{
		if(count($type_insert_column) > 0)
		{
			foreach($value_cf_array as $key => $value)
			{
				$tableName = $key;
				$tableVal = $value;
				if($tableVal != '')
				{
					$tab_val = explode ("|##|",$value);
					if(count($tab_val)>0)
					{
						for($k=0;$k<count($tab_val);$k++)
						{
							$val =$tab_val[$k];
							$sql = "select $tableName from aicrm_$tableName";
							$numRow = $adb->num_rows($adb->query($sql));
							$count=0;
							for($n=0;$n < $numRow;$n++)
							{
								$exist_val = $adb->query_result($adb->query($sql),$n,$tableName);
								if(trim($exist_val) == trim($val))
								{
									$count++;
								}
							}
							if($count == 0)
							{
								$cfId=$adb->getUniqueID("aicrm_$tableName");
								$unique_picklist_value = getUniquePicklistID();

								$qry="insert into aicrm_$tableName values(?,?,?,?)";
								$adb->pquery($qry, array($cfId,trim($val),1,$unique_picklist_value));
								//added to fix ticket#4492
								$picklistId_qry = "select picklistid from aicrm_picklist where name=?";
								$picklistId_res = $adb->pquery($picklistId_qry,array($tableName));
								$picklist_Id = $adb->query_result($picklistId_res,0,'picklistid');
								//While adding a new value into the picklist table, the roleid's which has permission for the lead cf will only given permission for contacts,potentials and account module while converting. -- refer ticket #4885 ---
								$role_qry = "select roleid from aicrm_role2picklist where picklistvalueid in (select picklist_valueid from aicrm_".$lead_val_arr[$tableName]."  where ".$lead_val_arr[$tableName]."='".trim($val)."')";
								$numOFRole=$adb->num_rows($adb->query($role_qry));
								for($l=0;$l<$numOFRole;$l++)
								{
									$role_id = $adb->query_result($adb->query($role_qry),$l,'roleid');
									$sort_qry = "select max(sortid)+1 as sortid from aicrm_role2picklist where picklistid=? and roleid=?";
									$sort_qry_res = $adb->pquery($sort_qry,array($picklist_Id,$role_id));
									$sort_id = $adb->query_result($sort_qry_res,0,'sortid');
									$role_picklist = "insert into aicrm_role2picklist values (?,?,?,?)";
									$adb->pquery($role_picklist,array($role_id,$unique_picklist_value,$picklist_Id,$sort_id));
								}
								//end
							}
						}
					}

				}
			}
		}
	}
	//EXIT;
	$log->debug("columns to be inserted are ".$type_insert_column);
    $log->debug("columns to be inserted are ".$insert_value);
	$values = array ('columns'=>$type_insert_column,'values'=>$insert_value);
	$log->debug("Exiting getInsertValues method ...");
	return $values;	
}
//function Ends

/**	Function used to get the lead related Notes and Attachments with other entities Account, Contact and Potential
 *	@param integer $id - leadid
 *	@param integer $accountid -  related entity id (accountid)
 */
function getRelatedNotesAttachments($id,$related_id)
{
	global $adb,$log,$id;
	$log->debug("Entering getRelatedNotesAttachments(".$id.",".$related_id.") method ...");
	
	$sql_lead_notes	="select * from aicrm_senotesrel where crmid=?";
	$lead_notes_result = $adb->pquery($sql_lead_notes, array($id));
	$noofrows = $adb->num_rows($lead_notes_result);

	for($i=0; $i<$noofrows;$i++ )
	{

		$lead_related_note_id=$adb->query_result($lead_notes_result,$i,"notesid");
		$log->debug("Lead related note id ".$lead_related_note_id);

		$sql_insert_notes="insert into aicrm_senotesrel(crmid,notesid) values (?,?)";
		$adb->pquery($sql_insert_notes, array($related_id, $lead_related_note_id));
	}

	$sql_lead_attachment="select * from aicrm_seattachmentsrel where crmid=?";
    $lead_attachment_result = $adb->pquery($sql_lead_attachment, array($id));
    $noofrows = $adb->num_rows($lead_attachment_result);

    for($i=0;$i<$noofrows;$i++)
    {
        $lead_related_attachment_id=$adb->query_result($lead_attachment_result,$i,"attachmentsid");
 		$log->debug("Lead related attachment id ".$lead_related_attachment_id);

        $sql_insert_attachment="insert into aicrm_seattachmentsrel(crmid,attachmentsid) values (?,?)";                        
        $adb->pquery($sql_insert_attachment, array($related_id, $lead_related_attachment_id));
    }
	$log->debug("Exiting getRelatedNotesAttachments method ...");
	
}

/**	Function used to get the lead related activities with other entities Account and Contact 
 *	@param integer $accountid - related entity id
 *	@param integer $contact_id -  related entity id 
 */
function getRelatedActivities($accountid,$contact_id)
{
	global $adb,$log,$id;	
	$log->debug("Entering getRelatedActivities(".$accountid.",".$contact_id.") method ...");
	$sql_lead_activity="select * from aicrm_seactivityrel where crmid=?";
	$lead_activity_result = $adb->pquery($sql_lead_activity, array($id));
    $noofrows = $adb->num_rows($lead_activity_result);
    for($i=0;$i<$noofrows;$i++)
    {
        $lead_related_activity_id=$adb->query_result($lead_activity_result,$i,"activityid");
		$log->debug("Lead related aicrm_activity id ".$lead_related_activity_id);

		$sql_type_email="select setype from aicrm_crmentity where crmid=?";
		$type_email_result = $adb->pquery($sql_type_email, array($lead_related_activity_id));
        $type=$adb->query_result($type_email_result,0,"setype");
		$log->debug("type of aicrm_activity id ".$type);

	    $sql_delete_lead_activity="delete from aicrm_seactivityrel where crmid=?";
	    $adb->pquery($sql_delete_lead_activity, array($id));

		if($type != "Emails")
		{
            $sql_insert_account_activity="insert into aicrm_seactivityrel(crmid,activityid) values (?,?)";
	        $adb->pquery($sql_insert_account_activity, array($accountid, $lead_related_activity_id));

			$sql_insert_contact_activity="insert into aicrm_cntactivityrel(contactid,activityid) values (?,?)";
            $adb->pquery($sql_insert_contact_activity, array($contact_id, $lead_related_activity_id));
		}
		else
		{
			 $sql_insert_contact_activity="insert into aicrm_seactivityrel(crmid,activityid) values (?,?)";                                                                                     
			 $adb->pquery($sql_insert_contact_activity, array($contact_id, $lead_related_activity_id));
		}
    }
	$log->debug("Exiting getRelatedActivities method ...");

}

/**	Function used to save the lead related products with other entities Account, Contact and Potential
 *	$leadid - leadid
 *	$relatedid - related entity id (accountid/contactid/potentialid)
 *	$setype - related module(Accounts/Contacts/Potentials)
 */
function saveLeadRelatedProducts($leadid, $relatedid, $setype)
{
	global $adb, $log;
	$log->debug("Entering into function saveLeadRelatedProducts($leadid, $relatedid)");

	$product_result = $adb->pquery("select * from aicrm_seproductsrel where crmid=?", array($leadid));
	$noofproducts = $adb->num_rows($product_result);
	for($i = 0; $i < $noofproducts; $i++)
	{
		$productid = $adb->query_result($product_result,$i,'productid');
		$adb->pquery("insert into aicrm_seproductsrel values(?,?,?)", array($relatedid, $productid, $setype));
	}

	$log->debug("Exit from function saveLeadRelatedProducts.");
}

/**     Function used to save the lead related Campaigns with Contact
 *      $leadid - leadid
 *      $relatedid - related entity id (contactid)
 */
function saveLeadRelatedCampaigns($leadid, $relatedid)
{
	global $adb, $log;
	$log->debug("Entering into function saveLeadRelatedCampaigns($leadid, $relatedid)");

	$campaign_result = $adb->pquery("select * from aicrm_campaignleadrel where leadid=?", array($leadid));
	$noofcampaigns = $adb->num_rows($campaign_result);
	for($i = 0; $i < $noofcampaigns; $i++)
	{
		$campaignid = $adb->query_result($campaign_result,$i,'campaignid');

		$adb->pquery("insert into aicrm_campaigncontrel (campaignid, contactid) values(?,?)", array($campaignid, $relatedid));
	}
	$log->debug("Exit from function saveLeadRelatedCampaigns.");
}

function getRelatedvisit($leadid,$accountid)
{
	global $adb,$log,$id;	
	$log->debug("Entering getRelatedvisit(".$leadid.",".$accountid.") method ...");
	$sql_lead_visit="update aicrm_activity set parentid =? where parentid=?";
	$adb->pquery($sql_lead_visit, array($accountid,$leadid));
	$log->debug("Exiting getRelatedvisit method ...");
}

function getRelateddeal($leadid,$accountid)
{
	global $adb,$log,$id;	
	$log->debug("Entering getRelateddeal(".$leadid.",".$accountid.") method ...");
	$sql_lead_deal="update aicrm_deal set parentid =? where parentid=?";
	$adb->pquery($sql_lead_deal, array($accountid,$leadid));
	$log->debug("Exiting getRelateddeal method ...");
}

function getRelatedquotation($leadid,$accountid)
{
	global $adb,$log,$id;	
	$log->debug("Entering getRelatedquotation(".$leadid.",".$accountid.") method ...");
	$sql_lead_quotes="update aicrm_quotes set parentid =? where parentid=?";
	$adb->pquery($sql_lead_quotes, array($accountid,$leadid));
	$log->debug("Exiting getRelatedquotation method ...");
}

$crmid ='';
$contact_id = '';

if(vtlib_isModuleActive('Accounts') && isPermitted("Accounts","EditView") =='yes'){
	/*Code integrated to avoid duplicate Account creation during ConvertLead Operation  START-- by Bharathi*/
	$acc_query = "select aicrm_account.accountid from aicrm_account left join aicrm_crmentity on aicrm_account.accountid = aicrm_crmentity.crmid where aicrm_crmentity.deleted=0 and aicrm_account.accountname = ?";
	$acc_res = $adb->pquery($acc_query, array($accountname));
	$acc_rows = $adb->num_rows($acc_res);
	
	if($acc_rows != 0){
		$crmid = $adb->query_result($acc_res,0,"accountid");
		//Retrieve the lead related products and relate them with this new account
		getRelatedNotesAttachments($id,$crmid); 
		getRelatedvisit($id,$crmid);
		getRelateddeal($id,$crmid);
		saveLeadRelatedProducts($id, $crmid, "Accounts");
		getRelatedquotation($id,$crmid);
		//saveLeadRelations($id, $crmid, "Accounts");
	}else if($accountname == ''){
		$crmid='';
	}else{
		$crmid = $adb->getUniqueID("aicrm_crmentity");
		//Saving Account - starts
		$sql_crmentity = "insert into aicrm_crmentity(crmid,smcreatorid,smownerid,setype,presence,createdtime,modifiedtime,deleted,description) values(?,?,?,?,?,?,?,?,?)";
		$sql_params = array($crmid, $current_user_id, $assigned_user_id, 'Accounts', 1, $date_entered, $date_modified, 0, $row['description']);
		$adb->pquery($sql_crmentity, $sql_params);
		
		//Module Sequence Numbering
		require_once('modules/Accounts/Accounts.php');
		$acc_no_focus = new Accounts();
		//$account_no = $acc_no_focus->setModuleSeqNumber("increment",'Accounts');
		
		include_once("include/myFunction.php");
	    $account_no = get_autorun("CLA", "Accounts", "6");
		$account_no = str_replace('-', '', $account_no);
		//echo $account_no;exit;
		// END

		/* Modified by Minnie to fix the convertlead issue -- START*/
		if(isset($row["annualrevenue"]) && !empty($row["annualrevenue"])){ 
			$annualrevenue = $row["annualrevenue"];
		}else{ 
			$annualrevenue = 'null';
		}
		
		if(isset($row["noofemployees"]) && !empty($row["noofemployees"])){ 
			$employees = $row["noofemployees"];
		}else{ 
			$employees = 'null';
		}
		
		//Getting the custom aicrm_field values from leads and inserting into Accounts if the aicrm_field is mapped - Jaguar
		$col_val= getInsertValues("Accounts",$crmid);
		
		$insert_columns = $col_val['columns'];
		$insert_columns[] = "accountid";
		$insert_values = $col_val['values'];
		$insert_values[] = $crmid;
			
		for($aa=0;$aa<count($insert_columns);$aa++){
			if(substr($insert_columns[$aa],0,3)=="cf_"){
			}else{
				$insert_columns_ok[]=$insert_columns[$aa];
				$insert_values_ok[]=$insert_values[$aa];
			}
		}
		$insert_columns_ok[]="account_no";		
		$insert_values_ok[]=$account_no;
		$insert_columns_ok[]="accountname";		
		$insert_values_ok[]=@$accountname;
		$insert_columns_ok[]="account_name_th";
		$insert_values_ok[]=@$accountname;
		// $insert_columns_ok[]="idcardno";		
		// $insert_values_ok[]=@$idcardno;

		$insert_columns_ok[]="nametitle";
		$insert_values_ok[]=$focus->column_fields['salutationtype'];
		// $insert_columns_ok[]="account_group";
		// $insert_values_ok[]=$focus->column_fields['lead_group'];
		$insert_columns_ok[]="accountstatus";
		$insert_values_ok[]="Active";
		// $insert_columns_ok[]="birthdate";
		// $insert_values_ok[]=$focus->column_fields['birthdate'];
		// $insert_columns_ok[]="gender";
		// $insert_values_ok[]=$focus->column_fields['gender'];
		// $insert_columns_ok[]="mobile";
		// $insert_values_ok[]=$focus->column_fields['mobile'];
		// $insert_columns_ok[]="email1";
		// $insert_values_ok[]=$focus->column_fields['email'];
		// $insert_columns_ok[]="line_id";
		// $insert_values_ok[]=$focus->column_fields['line_id'];
		// $insert_columns_ok[]="facebook_name";
		// $insert_values_ok[]=$focus->column_fields['facebook_name'];
		// $insert_columns_ok[]="accountsource";
		// $insert_values_ok[]=$focus->column_fields['leadsource'];
		// $insert_columns_ok[]="other_account_source";
		// $insert_values_ok[]=$focus->column_fields['lead_source'];
		// $insert_columns_ok[]="accountinterest";
		// $insert_values_ok[]=$focus->column_fields['interest'];
		$insert_columns_ok[]="register_date";
		$insert_values_ok[]= date('Y-m-d');

		$insert_columns_ok[]="accountname1";
		$insert_values_ok[]=@$accountname;
		$insert_columns_ok[]="billingvillage";
		$insert_values_ok[]=$focus->column_fields['lead_village'];
		$insert_columns_ok[]="billingaddressline";
		$insert_values_ok[]=$focus->column_fields['addressline'];
		$insert_columns_ok[]="billingaddressline1";
		$insert_values_ok[]=$focus->column_fields['lead_houseno'];
		$insert_columns_ok[]="billingvillageno";
		$insert_values_ok[]=$focus->column_fields['lead_villageno'];
		$insert_columns_ok[]="billinglane";
		$insert_values_ok[]=$focus->column_fields['lead_lane'];
		$insert_columns_ok[]="billingstreet";
		$insert_values_ok[]=$focus->column_fields['lead_street'];
		$insert_columns_ok[]="billingsubdistrict";
		$insert_values_ok[]=$focus->column_fields['lead_subdistrinct'];
		$insert_columns_ok[]="billingdistrict";
		$insert_values_ok[]=$focus->column_fields['lead_district'];
		$insert_columns_ok[]="billingprovince";
		$insert_values_ok[]=$focus->column_fields['lead_province'];
		$insert_columns_ok[]="billingpostalcode";
		$insert_values_ok[]=$focus->column_fields['lead_postalcode'];
		$insert_columns_ok[]="billing_address";
		$insert_values_ok[]=$focus->column_fields['lead_aboard'];

		$insert_columns_ok[]="accountname2";
		$insert_values_ok[]=@$accountname;
		// $insert_columns_ok[]="village";
		// $insert_values_ok[]=$focus->column_fields['lead_village'];
		// $insert_columns_ok[]="addressline";
		// $insert_values_ok[]=$focus->column_fields['addressline'];
		// $insert_columns_ok[]="addressline1";
		// $insert_values_ok[]=$focus->column_fields['lead_houseno'];
		// $insert_columns_ok[]="villageno";
		// $insert_values_ok[]=$focus->column_fields['lead_villageno'];
		// $insert_columns_ok[]="lane";
		// $insert_values_ok[]=$focus->column_fields['lead_lane'];
		// $insert_columns_ok[]="street";
		// $insert_values_ok[]=$focus->column_fields['lead_street'];
		// $insert_columns_ok[]="subdistrict";
		// $insert_values_ok[]=$focus->column_fields['lead_subdistrinct'];
		// $insert_columns_ok[]="district";
		// $insert_values_ok[]=$focus->column_fields['lead_district'];
		// $insert_columns_ok[]="province";
		// $insert_values_ok[]=$focus->column_fields['lead_province'];
		// $insert_columns_ok[]="postalcode";
		// $insert_values_ok[]=$focus->column_fields['lead_postalcode'];
		$insert_columns_ok[]="address";
		$insert_values_ok[]=$focus->column_fields['lead_aboard'];

		$insert_val_str_ok = generateQuestionMarks($insert_values_ok);
		$sql_insert_account = "INSERT INTO aicrm_account (". implode(",",$insert_columns_ok) .") VALUES (".$insert_val_str_ok.")";
		// echo $sql_insert_account;
		// echo implode('","', $insert_values_ok);
		// exit();
		// echo "<pre>"; print_r($insert_columns_ok); echo "</pre>";
		// echo "<pre>"; print_r($insert_values_ok); echo "</pre>";
		// echo $sql_insert_account; 
		// echo implode("','", $insert_values_ok);
		// exit;
		$adb->pquery($sql_insert_account, $insert_values_ok);
		//Add By AI 
		$sql_insert_lead2acc = "INSERT INTO aicrm_convert_lead2acc (accountid,leadid,createdate) VALUES (?,?,?)";
		/* Modified by Minnie -- END*/
		$lead2acc_params = array($crmid, $id,$date_entered );
		$adb->pquery($sql_insert_lead2acc, $lead2acc_params);

		$sql_insert_accountbillads = "INSERT INTO aicrm_accountbillads (accountaddressid) VALUES (?)";
		$billads_params = array($crmid);
		$adb->pquery($sql_insert_accountbillads, $billads_params);
	
		$sql_insert_accountshipads = "INSERT INTO aicrm_accountshipads (accountaddressid) VALUES (?)";
		$shipads_params = array($crmid);
		$adb->pquery($sql_insert_accountshipads, $shipads_params);
			
		$insert_columns_ok=array();
		$insert_values_ok=array();
		for($aa=0;$aa<count($insert_columns);$aa++){
			if(substr($insert_columns[$aa],0,3)=="cf_"){
				$insert_columns_ok[]=$insert_columns[$aa];
				$insert_values_ok[]=$insert_values[$aa];
			}
		}
		$insert_columns_ok[] = "accountid";
		$insert_values_ok[] = $crmid;
		
		$insert_val_str = generateQuestionMarks($insert_values_ok);
		
		$sql_insert_accountcustomfield = "INSERT INTO aicrm_accountscf (". implode(",",$insert_columns_ok) .") VALUES (".$insert_val_str.")";
		$adb->pquery($sql_insert_accountcustomfield, $insert_values_ok);
		//Saving Account - ends

		getRelatedNotesAttachments($id,$crmid); 
		getRelatedvisit($id,$crmid);
		getRelateddeal($id,$crmid);
		saveLeadRelatedProducts($id, $crmid, "Accounts");
		getRelatedquotation($id,$crmid);

		//getRelatedNotesAttachments($id,$crmid); 
		//Retrieve the lead related products and relate them with this new account
		//saveLeadRelatedProducts($id, $crmid, "Accounts");
		//saveLeadRelations($id, $crmid, "Accounts");
	}
}

if((!isset($createcontact) || $createcontact == "on") && (!empty($contact_name)) && isPermitted("Contacts","EditView") =='yes' && vtlib_isModuleActive('Contacts')){
	$date_entered = $adb->formatDate(date('Y-m-d H:i:s'), true);
	$date_modified = $adb->formatDate(date('Y-m-d H:i:s'), true);
	
	//Saving Contact - starts
	$crmcontactid = $adb->getUniqueID("aicrm_crmentity");
	
	$sql_crmentity1 = "insert into aicrm_crmentity(crmid,smcreatorid,smownerid,setype,presence,deleted,description,createdtime,modifiedtime) values(?,?,?,?,?,?,?,?,?)";
	$sql_params = array($crmcontactid, $current_user_id, $assigned_user_id, 'Contacts', 0, 0, $row['description'], $date_entered, $date_modified);
	$adb->pquery($sql_crmentity1, $sql_params);
	
	$contact_id = $crmcontactid;
	$log->debug("contact id is ".$contact_id);
	
	// Module Sequence Numbering
	require_once('modules/Contacts/Contacts.php');
	$cont_no_focus = new Contacts();
	include_once("include/myFunction.php");
	$contact_no = get_autorun("CON".substr((date("Y")+543), -2).date('m'),"Contacts","6");
	// END

	$col_val= getInsertValues("Contacts",$contact_id);

	$insert_columns = $col_val['columns'];
	$insert_columns[] = "contactid";
	$insert_values = $col_val['values'];
	$insert_values[] = $contact_id;
			
	for($aa=0;$aa<count($insert_columns);$aa++){
		if(substr($insert_columns[$aa],0,3)=="cf_"){
		}else{
			$insert_columns_ok[]=$insert_columns[$aa];
			$insert_values_ok[]=$insert_values[$aa];
		}
	}
	$insert_columns_ok[]="contact_no";		
	$insert_values_ok[]=$contact_no;
	$insert_columns_ok[]="contactname";		
	$insert_values_ok[]=@$contact_name;

	$insert_val_str_ok = generateQuestionMarks($insert_values_ok);
	$sql_insert_contact = "INSERT INTO aicrm_contactdetails (". implode(",",$insert_columns_ok) .") VALUES (".$insert_val_str_ok.")";
	$adb->pquery($sql_insert_contact, $insert_values_ok);
	
	$sql_insert_contactbillads = "INSERT INTO aicrm_contactsubdetails (contactsubscriptionid) VALUES (?)";
	$billads_params = array($contact_id);
	$adb->pquery($sql_insert_contactbillads, $billads_params);

	$sql_insert_contacthipads = "INSERT INTO aicrm_contactaddress (contactaddressid) VALUES (?)";
	$shipads_params = array($contact_id);
	$adb->pquery($sql_insert_contacthipads, $shipads_params);

	$sql_insert_contacthipads = "INSERT INTO aicrm_customerdetails (customerid) VALUES (?)";
	$shipads_params = array($contact_id);
	$adb->pquery($sql_insert_contacthipads, $shipads_params);
		
	$insert_columns_ok=array();
	$insert_values_ok=array();
	for($aa=0;$aa<count($insert_columns);$aa++){
		if(substr($insert_columns[$aa],0,3)=="cf_"){
			$insert_columns_ok[]=$insert_columns[$aa];
			$insert_values_ok[]=$insert_values[$aa];
		}
	}
	$insert_columns_ok[] = "contactid";
	$insert_values_ok[] = $contact_id;
	
	$insert_val_str = generateQuestionMarks($insert_values_ok);
	
	$sql_insert_contactcustomfield = "INSERT INTO aicrm_contactscf (". implode(",",$insert_columns_ok) .") VALUES (".$insert_val_str.")";
	$adb->pquery($sql_insert_contactcustomfield, $insert_values_ok);

	$sql_update_contact = "update aicrm_contactdetails set accountid='".$crmid."' where contactid='".$contact_id."'";
	$adb->pquery($sql_update_contact, array());
	//Saving Contact - ends
	
	$sql_update_acc = "update aicrm_account set accountindustry = '--None--' , firstname = '".$accountname."' , lastname = '' where accountid='".$crmid."'";
	$adb->pquery($sql_update_acc, array());

	//getRelatedActivities($crmid,$contact_id); //To convert relates Activites  and Email -Jaguar
	//getRelatedNotesAttachments($id,$contact_id); 
	
	//Retrieve the lead related products and relate them with this new contact
	//saveLeadRelatedProducts($id, $contact_id, "Contacts");
	//saveLeadRelations($id, $contact_id, "Contacts");
	
	//Retrieve the lead related Campaigns and relate them with this new contact --Minnie
	//saveLeadRelatedCampaigns($id, $contact_id);
}

/*if(vtlib_isModuleActive('Deal') && isPermitted("Deal","EditView") =='yes'){

	$log->info("createdeal is not set");

	$date_entered = $adb->formatDate(date('Y-m-d H:i:s'), true);
	$date_modified = $adb->formatDate(date('Y-m-d H:i:s'), true);
  
	$dealid = $adb->getUniqueID("aicrm_crmentity");
  	
	$sql_crmentity = "insert into aicrm_crmentity(crmid,smcreatorid,smownerid,setype,presence,deleted,createdtime,modifiedtime,description) values(?,?,?,?,?,?,?,?,?)";
	$sql_params = array($dealid, $current_user_id, $assigned_user_id, 'Deal', 0, 0, $date_entered, $date_modified, $row['description']);
	$adb->pquery($sql_crmentity, $sql_params);  	
  	
	// Module Sequence Numbering
	require_once('modules/Deal/Deal.php');
	$deal_no_focus = new Deal();
	include_once("include/myFunction.php");
    $deal_no = get_autorun("DEL".substr((date("Y")+543), -2),"Deal","4");
	// END	
	
	$col_val= getInsertValues("Deal",$dealid);

	$deal_columns = $col_val['columns'];
	$deal_columns[] = "dealid";
	$deal_values = $col_val['values'];
	$deal_values[] = $dealid;
	
	for($aa=0;$aa<count($deal_columns);$aa++){
		if(substr($deal_columns[$aa],0,3)=="cf_"){
		}else{
			$deal_columns_ok[]=$deal_columns[$aa];
			$deal_values_ok[]=$deal_values[$aa];
		}
	}
	$deal_columns_ok[] = "deal_no";
	$deal_values_ok[] = $deal_no;
	$deal_columns_ok[] = "accountid";
	$deal_values_ok[] = @$crmid;

	$insert_val_str_ok = generateQuestionMarks($deal_values_ok);
	$sql_insert_deal = "INSERT INTO aicrm_deal (". implode(",",$deal_columns_ok) .") VALUES (".$insert_val_str_ok.")";
	$adb->pquery($sql_insert_deal, $deal_values_ok);

	$deal_columns_ok=array();
	$deal_values_ok=array();
	for($aa=0;$aa<count($deal_columns);$aa++){
		if(substr($deal_columns[$aa],0,3)=="cf_"){
			$deal_columns_ok[]=$deal_columns[$aa];
			$deal_values_ok[]=$deal_values[$aa];
		}
	}

	$deal_columns_ok[] = "dealid";
	$deal_values_ok[] = $dealid;
	$insert_val_str = generateQuestionMarks($deal_values_ok);
	
	$sql_insert_dealcustomfield = "INSERT INTO aicrm_dealcf (". implode(",",$deal_columns_ok) .") VALUES (".$insert_val_str.")";
	$adb->pquery($sql_insert_dealcustomfield, $deal_values_ok);

	getRelatedNotesAttachments($id,$dealid); 
}*/

$sql_insert_account = "update aicrm_leaddetails set accountid='".$crmid."',contactid='".$contact_id."' where leadid='".$id."'";
$adb->pquery($sql_insert_account, array());

/*$socialid = @$focus->column_fields['socialid'];
if($socialid != ''){
	$s_update = "Update message_customer set module = 'Accounts' , parentid = '".$crmid."' where customerno = '".$socialid."' ";
	$adb->pquery($s_update, array());
}*/

//Deleting from the aicrm_tracker
$sql_delete_tracker= "DELETE from aicrm_tracker where item_id=?";
$adb->pquery($sql_delete_tracker, array($id));
$category = getParentTab();
//Updating the deleted status
if($crmid != ''){
	$sql_update_converted = "UPDATE aicrm_leaddetails SET converted = 1 ,convert_lead = 1 , convert_date = '".date('Y-m-d H:i:s')."' where leadid=?";
	$adb->pquery($sql_update_converted, array($id)); 
}

if($crmid!=''){
	header("Location: index.php?action=DetailView&module=Accounts&record=$crmid&parenttab=$category");
/*}elseif($dealid != '') {
	header("Location: index.php?action=DetailView&module=Deal&record=$dealid&parenttab=$category");*/
}elseif($crmcontactid != '') {
	header("Location: index.php?action=DetailView&module=Contacts&record=$crmcontactid&parenttab=$category");
}else{
	echo "<link rel='stylesheet' type='text/css' href='themes/$theme/style.css'>";	
	echo "<table border='0' cellpadding='5' cellspacing='0' width='100%' height='450px'><tr><td align='center'>";
	echo "<div style='border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 55%; position: relative; z-index: 10000000;'>

		<table border='0' cellpadding='5' cellspacing='0' width='98%'>
		<tbody><tr>
		<td rowspan='2' width='11%'><img src='". aicrm_imageurl('denied.gif', $theme) . "' ></td>
		<td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'><span class='genHeaderSmall'>$currentModule $app_strings[CANNOT_CONVERT]</span></td>
		</tr>
		<tr>
		<td class='small' align='right' nowrap='nowrap'>			   	
		<a href='javascript:window.history.back();'>$app_strings[LBL_GO_BACK]</a><br>								   						     </td>
		</tr>
		</tbody></table> 
		</div>";
	echo "</td></tr></table>";
}

/**	Function used to save the lead related services with other entities Account, Contact and Potential
 *	$leadid - leadid
 *	$relatedid - related entity id (accountid/contactid/potentialid)
 *	$setype - related module(Accounts/Contacts/Potentials)
*/

function saveLeadRelations($leadid, $relatedid, $setype)
{
	global $adb, $log;
	$log->debug("Entering into function saveLeadRelatedProducts($leadid, $relatedid)");

	$result = $adb->pquery("select * from aicrm_crmentityrel where crmid=?", array($leadid));
	$noofproducts = $adb->num_rows($result);
	for($i = 0; $i < $noofproducts; $i++)
	{
		$recordid = $adb->query_result($result,$i,'relcrmid');
		$recordmodule = $adb->query_result($result,$i,'relmodule');
		$adb->pquery("insert into aicrm_crmentityrel values(?,?,?,?)", array($relatedid, $setype, $recordid, $recordmodule));
	}
	$result = $adb->pquery("select * from aicrm_crmentityrel where relcrmid=?", array($leadid));
	$noofproducts = $adb->num_rows($result);
	for($i = 0; $i < $noofproducts; $i++)
	{
		$recordid = $adb->query_result($result,$i,'crmid');
		$recordmodule = $adb->query_result($result,$i,'module');
		$adb->pquery("insert into aicrm_crmentityrel values(?,?,?,?)", array($relatedid, $setype, $recordid, $recordmodule));
	}

	$log->debug("Exit from function saveLeadRelatedProducts.");
}

?>