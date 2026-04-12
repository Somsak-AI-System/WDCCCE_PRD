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
 * $Header$
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('data/SugarBean.php');
require_once('include/utils/utils.php');
require_once('include/RelatedListView.php');
require_once('user_privileges/default_module_view.php');

class Smartquestionnaire extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_smartquestionnaire";
	var $table_index= 'smartquestionnaireid';

	var $tab_name = Array('aicrm_crmentity','aicrm_smartquestionnaire','aicrm_smartquestionnairecf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_smartquestionnaire'=>'smartquestionnaireid','aicrm_smartquestionnairecf'=>'smartquestionnaireid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_smartquestionnairecf', 'smartquestionnaireid');
	var $column_fields = Array();

	var $sortby_fields = Array('smartquestionnaireid','smartquestionnaire_name','smownerid');

	var $list_fields = Array(
		'Smart Questionnaire No'=>Array('Smartquestionnaire'=>'smartquestionnaire_no'),
		'Smart Questionnaire Name'=>Array('Smartquestionnaire'=>'smartquestionnaire_name'),
		'Date Sent'=>Array('Smartquestionnaire'=>'send_date'),
		'Smart Questionnaire Status'=>Array('Smartquestionnaire'=>'email_status'),
					//'Assigned To' => Array('crmentity'=>'smownerid')
	);

	var $list_fields_name = Array(
		'Smart Questionnaire No'=>'smartquestionnaire_no',
		'Smart Questionnaire Name'=>'smartquestionnaire_name',
		'Date Sent'=>'send_date',
		'Smart Questionnaire Status'=>'email_status',
					//'Assigned To'=>'assigned_user_id'
	);	  			

	var $list_link_field= 'smartquestionnaire_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';

	//var $groupTable = Array('aicrm_campaigngrouprelation','projects_id');

	var $search_fields = Array(
		'Smart Questionnaire No'=>Array('aicrm_smartquestionnaire'=>'smartquestionnaire_no'),
		'Smart Questionnaire Name'=>Array('aicrm_smartquestionnaire'=>'smartquestionnaire_name'),
	);

	var $search_fields_name = Array(
		'Smart Questionnaire No'=>'smartquestionnaire_no',
		'Smart Questionnaire Name'=>'smartquestionnaire_name',
	);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','smartquestionnaireid');
	
	function Smartquestionnaire() 
	{
		$this->log =LoggerManager::getLogger('Smartquestionnaire');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Smartquestionnaire');
	}
	function save_module()
	{
		global $adb;
		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'SmartquestionnaireAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave')
		{
			//Based on the total Number of rows we will save the product relationship with this entity
			saveInventoryProductDetails($this, 'Smartquestionnaire');
		}
		$this->insertIntoAttachment($this->id,'Smartquestionnaire');
		if(isset($_REQUEST['sms_sender_name']) &&  $_REQUEST['sms_sender_name'] != ''){
			
			$sql = "update aicrm_smartquestionnaire 
			left join aicrm_config_sender_sms on aicrm_config_sender_sms.id = aicrm_smartquestionnaire.sms_sender_name
			set aicrm_smartquestionnaire.sms_sender = aicrm_config_sender_sms.sms_sender , aicrm_smartquestionnaire.sms_url = aicrm_config_sender_sms.sms_url ,
			aicrm_smartquestionnaire.sms_username = aicrm_config_sender_sms.sms_username , aicrm_smartquestionnaire.sms_password = aicrm_config_sender_sms.sms_password 
			where  aicrm_smartquestionnaire.smartquestionnaireid = '".$this->id."' ";	
			$adb->pquery($sql, array());	
		}
		
		if(isset($_REQUEST['sms_status']) &&  $_REQUEST['sms_status'] == 'Active'){
			
			$sql = " update aicrm_smartquestionnaire
			set setup_sms = '0'
			,send_sms = '0'
			where smartquestionnaireid = '".$this->id."' ";
			
			$adb->pquery($sql, array());
			
		}
		// Update the currency id and the conversion rate for the quotes
		//$update_query = "update aicrm_quotes set currency_id=?, conversion_rate=? where quoteid=?";
//		$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id); 
//		$adb->pquery($update_query, $update_params);
	}	

	function insertIntoAttachment($id,$module){
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;
		// echo "<pre>";print_r($_FILES); echo "</pre>"; exit;
		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				if($_REQUEST[$fileindex.'_hidden'] != '')
					$files['original_name'] = vtlib_purify($_REQUEST[$fileindex.'_hidden']);
				else
					$files['original_name'] = stripslashes($files['name']);
				$files['original_name'] = str_replace('"','',$files['original_name']);

				if (strpos($fileindex, 'image_header') !== false) {
					$files['flag'] = 'S';
					$files['setype'] = 'image_header';
					$files['fileindex'] = 'image_header';

					$query= "select aicrm_seattachmentsrel.attachmentsid from aicrm_seattachmentsrel inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_seattachmentsrel.attachmentsid where aicrm_crmentity.setype='".$fileindex."' and aicrm_seattachmentsrel.crmid=?";
					$result = $adb->pquery($query, array($id));
					$attachmentsid = $adb->query_result($result, 0, "attachmentsid");

					$rel_delquery='delete from aicrm_seattachmentsrel where crmid=?  and attachmentsid=?';
					$adb->pquery($rel_delquery, array($id, $attachmentsid));
					
					$crm_delquery="delete from aicrm_crmentity where crmid=?";
					$adb->pquery($crm_delquery, array($attachmentsid));

				}else if (strpos($fileindex, 'image_footer') !== false) {
					$files['flag'] = 'S';
					$files['setype'] = 'image_footer';
					$files['fileindex'] = 'image_footer';

					$query= "select aicrm_seattachmentsrel.attachmentsid from aicrm_seattachmentsrel inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_seattachmentsrel.attachmentsid where aicrm_crmentity.setype='".$fileindex."' and aicrm_seattachmentsrel.crmid=?";
					$result = $adb->pquery($query, array($id));
					$attachmentsid = $adb->query_result($result, 0, "attachmentsid");

					$rel_delquery='delete from aicrm_seattachmentsrel where crmid=?  and attachmentsid=?';
					$adb->pquery($rel_delquery, array($id, $attachmentsid));
					
					$crm_delquery="delete from aicrm_crmentity where crmid=?";
					$adb->pquery($crm_delquery, array($attachmentsid));
				}
				// echo "<pre>";print_r($files); echo "</pre>";
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}
		// exit;

		//Remove the deleted aicrm_attachments from db - Products
		if($module == 'Smartquestionnaire' && $_REQUEST['del_file_list'] != '')
		{
			$del_file_list = explode("###",trim($_REQUEST['del_file_list'],"###"));
			foreach($del_file_list as $del_file_name)
			{
				$attach_res = $adb->pquery("select aicrm_attachments.attachmentsid from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid where crmid=? and name=?", array($id,$del_file_name));
				$attachments_id = $adb->query_result($attach_res,0,'attachmentsid');

				$del_res1 = $adb->pquery("delete from aicrm_attachments where attachmentsid=?", array($attachments_id));
				$del_res2 = $adb->pquery("delete from aicrm_seattachmentsrel where attachmentsid=?", array($attachments_id));
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}
	
	/**	Function used to get the sort order for Quote listview
	 *	@return string	$sorder	- first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['QUOTES_SORT_ORDER'] if this session value is empty then default sort order will be returned. 
	 */
	function getSortOrder()
	{
		global $log;
		$log->debug("Entering getSortOrder() method ...");	
		if(isset($_REQUEST['sorder'])) 
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['EMAILTARGETLIST_SORT_ORDER'] != '')?($_SESSION['EMAILTARGETLIST_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		//echo "555".$sorder;exit;
		return $sorder;
	}

	/**	Function used to get the order by value for Quotes listview
	 *	@return string	$order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['QUOTES_ORDER_BY'] if this session value is empty then default order by will be returned. 
	 */
	function getOrderBy()
	{
		global $log;
		$log->debug("Entering getOrderBy() method ...");
		
		$use_default_order_by = '';		
		if(PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}
		
		if (isset($_REQUEST['order_by'])) 
			$order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
		else
			$order_by = (($_SESSION['EMAILTARGETLIST_ORDER_BY'] != '')?($_SESSION['EMAILTARGETLIST_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		
		return $order_by;
	}	

	/**	function used to get the list of sales orders which are related to the Quotes
	 *	@param int $id - quote id
	 *	@return array - return an array which will be returned from the function GetRelatedList
	 */
//	function get_salesorder($id)
//	{
//		global $log,$singlepane_view;
//		$log->debug("Entering get_salesorder(".$id.") method ...");
//		require_once('modules/SalesOrder/SalesOrder.php');
//	        $focus = new SalesOrder();
// 
//		$button = '';
//
//		if($singlepane_view == 'true')
//			$returnset = '&return_module=Quotes&return_action=DetailView&return_id='.$id;
//		else
//			$returnset = '&return_module=Quotes&return_action=CallRelatedList&return_id='.$id;
//
//		$query = "select aicrm_crmentity.*, aicrm_salesorder.*, aicrm_quotes.subject as quotename, aicrm_account.accountname,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name 
//		from aicrm_salesorder
//		inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_salesorder.salesorderid
//		left outer join aicrm_quotes on aicrm_quotes.quoteid=aicrm_salesorder.quoteid 
//		left outer join aicrm_account on aicrm_account.accountid=aicrm_salesorder.accountid  
//		left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
//		left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid
//		where aicrm_crmentity.deleted=0 and aicrm_salesorder.quoteid = ".$id;
//		$log->debug("Exiting get_salesorder method ...");
//		return GetRelatedList('Quotes','SalesOrder',$focus,$query,$button,$returnset);
//	}

	/**	function used to get the list of activities which are related to the Quotes
	 *	@param int $id - quote id
	 *	@return array - return an array which will be returned from the function GetRelatedList
	 */
	function get_activities($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_activities(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/Activity.php");
		$other = new Activity();
		vtlib_setup_modulevars($related_module, $other);		
		$singular_modname = vtlib_toSingular($related_module);
		
		$parenttab = getParentTab();
		
		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;
		
		$button = '';
		
		$button .= '<input type="hidden" name="activity_mode">';
		
		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString('LBL_TODO', $related_module) ."' class='crmbutton small create'" .
				" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Task\";' type='submit' name='button'" .
				" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_TODO', $related_module) ."'>&nbsp;";
			}
		}

		$query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, aicrm_contactdetails.contactid, aicrm_contactdetails.lastname, aicrm_contactdetails.firstname, aicrm_activity.*,aicrm_seactivityrel.*,aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_crmentity.modifiedtime,aicrm_recurringevents.recurringtype from aicrm_activity inner join aicrm_seactivityrel on aicrm_seactivityrel.activityid=aicrm_activity.activityid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid left join aicrm_cntactivityrel on aicrm_cntactivityrel.activityid= aicrm_activity.activityid left join aicrm_contactdetails on aicrm_contactdetails.contactid = aicrm_cntactivityrel.contactid left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid left outer join aicrm_recurringevents on aicrm_recurringevents.activityid=aicrm_activity.activityid left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid where aicrm_seactivityrel.crmid=".$id." and aicrm_crmentity.deleted=0 and activitytype='Task' and (aicrm_activity.status is not NULL and aicrm_activity.status != 'Completed') and (aicrm_activity.status is not NULL and aicrm_activity.status != 'Deferred')";
		
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;
		
		$log->debug("Exiting get_activities method ...");		
		return $return_value;
	}

	function get_emailtargets($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view, $currentModule;
		$log->debug("Entering get_emailtargets(".$id.") method ...");
		$this_module = $currentModule;
		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		vtlib_setup_modulevars($related_module, $other);		
		$singular_modname = vtlib_toSingular($related_module);
		
		$parenttab = getParentTab();
		
		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;
		
		$button = '';
		/* To get Leads CustomView -START */
		require_once('modules/CustomView/CustomView.php');
		$lhtml = "<select id='target_cv_list' class='small'><option value='None'>-- ".getTranslatedString('Select One')." --</option>";
		$oCustomView = new CustomView($related_module);
		$viewid = $oCustomView->getViewId($related_module);
		$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid, false);
		$lhtml .= $customviewcombo_html;
		$lhtml .= "</select>";
		/* To get Leads CustomView -END */
		//echo $id;
		$button .= $lhtml."<input title='Load List' class='crmbutton small edit' value='Load List' type='button' name='button' onclick='loadCvList_SMS(\"$related_module\",\"$id\")'>";
		$button .= '&nbsp;&nbsp;&nbsp;&nbsp';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) ) {
				$button .= "<input title='Select EmailTarget' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='Select EmailTarget'>&nbsp;";
			}
			if(in_array('ADD', $actions) ) {
				$button .= "<input title='Add EmailTarget' class='crmbutton small create'" .
				" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
				" value='Add EmailTarget'>&nbsp;";
			}
		}
		$button .="<input class='crmbutton small delete' type='button' value='Clear Data' onclick=\"JavaScript: void window.open('clear_list_data.php?crmid=".$id."&module=".$_REQUEST["module"]."&related_module=".$related_module."','Application','resizable=0,left=200,top=50,width=500,height=200,toolbar=no,scrollbars=no,menubar=no,location=no')\"  />";	
		$query = "SELECT aicrm_emailtargets.*,aicrm_emailtargetscf.*, aicrm_crmentity.crmid, 
		CASE when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, 
		aicrm_crmentity.smownerid 
		FROM aicrm_emailtargets      
		LEFT JOIN aicrm_emailtargetscf on aicrm_emailtargetscf.emailtargetid=aicrm_emailtargets.emailtargetid
		INNER JOIN aicrm_smartquestionnaire_emailtargetrel ON aicrm_smartquestionnaire_emailtargetrel.emailtargetid=aicrm_emailtargets.emailtargetid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_emailtargets.emailtargetid
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
		WHERE aicrm_crmentity.deleted=0 AND aicrm_smartquestionnaire_emailtargetrel.smartquestionnaireid = ".$id;
		//echo 		$query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
		if($return_value == null) $return_value = Array();	
		$return_value['CUSTOM_BUTTON'] = $button;	
		$log->debug("Exiting get_emailtargets method ...");	
		return $return_value;	
	}
	
	function get_contacts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view, $currentModule;
		$log->debug("Entering get_contacts(".$id.") method ...");
		$this_module = $currentModule;
		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		vtlib_setup_modulevars($related_module, $other);		
		$singular_modname = vtlib_toSingular($related_module);
		
		$parenttab = getParentTab();
		
		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;
		
		$button = '';
		/* To get Leads CustomView -START */
		require_once('modules/CustomView/CustomView.php');
		$lhtml = "<select id='cont_cv_list' class='small loadlist'><option value='None'>-- ".getTranslatedString('Select One')." --</option>";
		$oCustomView = new CustomView($related_module);
		$viewid = $oCustomView->getViewId($related_module);
		$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid, false);
		$lhtml .= $customviewcombo_html;
		$lhtml .= "</select>";
		/* To get Leads CustomView -END */
		//echo $id;
		//echo "|". $lhtml."|";
		//$button="";
		$button .= $lhtml."<input title='Load List' class='crmbutton small edit' value='Load List' type='button' name='button' onclick='loadCvList_SMS(\"$related_module\",\"$id\")'>";
		$button .= '&nbsp;&nbsp;&nbsp;&nbsp';
		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) ) {
				$button .= "<input title='Select Contacts' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='Select Contacts'>&nbsp;";
			}
			if(in_array('ADD', $actions) ) {
				$button .= "<input title='Add Contacts' class='crmbutton small create'" .
				" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
				" value='Add Contacts'>&nbsp;";
			}
		}
		$button .="<input class='crmbutton small delete' type='button' value='Clear Data' onclick=\"JavaScript: void window.open('clear_list_data.php?crmid=".$id."&module=".$_REQUEST["module"]."&related_module=".$related_module."','Application','resizable=0,left=200,top=50,width=500,height=200,toolbar=no,scrollbars=no,menubar=no,location=no')\"  />";	
		$query = "SELECT aicrm_contactdetails.*,aicrm_contactscf.*, aicrm_crmentity.crmid, 
		CASE when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, 
		aicrm_crmentity.smownerid ,aicrm_account.accountname as accountname
		FROM aicrm_contactdetails      
		LEFT JOIN aicrm_contactscf on aicrm_contactscf.contactid=aicrm_contactdetails.contactid
		INNER JOIN aicrm_smartquestionnaire_contactsrel ON aicrm_smartquestionnaire_contactsrel.contactid=aicrm_contactdetails.contactid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
		LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_contactdetails.accountid
		WHERE aicrm_crmentity.deleted=0 AND aicrm_smartquestionnaire_contactsrel.smartquestionnaireid = ".$id;
		// echo $query; exit();
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
		if($return_value == null) $return_value = Array();	
		$return_value['CUSTOM_BUTTON'] = $button;	
		
		$log->debug("Exiting get_contacts method ...");	
		return $return_value;	
	}
	
	function get_users($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view, $currentModule;
		$log->debug("Entering get_users(".$id.") method ...");
		$this_module = $currentModule;
		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		vtlib_setup_modulevars($related_module, $other);		
		$singular_modname = vtlib_toSingular($related_module);
		
		$parenttab = getParentTab();
		
		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;
		
		$button = '';

		if($actions) {

			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) ) {
				$button .= "<input title='Select Users' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='Select Users'>&nbsp;";
			}
			if(in_array('ADD', $actions) ) {
				$button .= "<input title='Add Users' class='crmbutton small create'" .
				" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
				" value='Add Users'>&nbsp;";
			}
		}			
		$query = "SELECT aicrm_users.*, aicrm_role.*
		FROM aicrm_users      
		INNER JOIN aicrm_smartquestionnaire_usersrel ON aicrm_smartquestionnaire_usersrel.id=aicrm_users.id
		left join aicrm_user2role on aicrm_user2role.userid=aicrm_users.id
		left join aicrm_role on aicrm_role.roleid 	=aicrm_user2role.roleid
		WHERE aicrm_smartquestionnaire_usersrel.smartquestionnaireid = ".$id;
		//echo 		$query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
		if($return_value == null) $return_value = Array();	
		$return_value['CUSTOM_BUTTON'] = $button;	

		
		$log->debug("Exiting get_users method ...");	
		return $return_value;	
	}
	
	function get_leads($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view, $currentModule;
		$log->debug("Entering get_leads(".$id.") method ...");
		$this_module = $currentModule;
		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		vtlib_setup_modulevars($related_module, $other);		
		$singular_modname = vtlib_toSingular($related_module);
		
		$parenttab = getParentTab();
		
		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;
		
		$button = '';
		/* To get Leads CustomView -START */
		require_once('modules/CustomView/CustomView.php');
		$lhtml = "<select id='lead_cv_list' class='small loadlist'><option value='None'>-- ".getTranslatedString('Select One')." --</option>";
		$oCustomView = new CustomView($related_module);
		$viewid = $oCustomView->getViewId($related_module);
		$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid, false);
		$lhtml .= $customviewcombo_html;
		$lhtml .= "</select>";
		/* To get Leads CustomView -END */
		//echo $id;
		$button .= $lhtml."<input title='Load List' class='crmbutton small edit' value='Load List' type='button' name='button' onclick='loadCvList_Smartquestionnaire(\"$related_module\",\"$id\")'>";
		$button .= '&nbsp;&nbsp;&nbsp;&nbsp';

		if($actions) {

			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) ) {
				$button .= "<input title='Select Leads' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='Select Leads'>&nbsp;";
			}
			if(in_array('ADD', $actions) ) {
				$button .= "<input title='Add Leads' class='crmbutton small create'" .
				" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
				" value='Add Leads'>&nbsp;";
			}
		}		
		$button .="<input class='crmbutton small delete' type='button' value='Clear Data' onclick=\"JavaScript: void window.open('clear_list_data.php?crmid=".$id."&module=".$_REQUEST["module"]."&related_module=".$related_module."','Application','resizable=0,left=450,top=100,width=500,height=200,toolbar=no,scrollbars=no,menubar=no,location=no')\"  />";	
		$query = "SELECT aicrm_leaddetails.*,aicrm_leadscf.*, aicrm_crmentity.crmid, aicrm_leadaddress.*,
		CASE when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, 
		aicrm_crmentity.smownerid 
		FROM aicrm_leaddetails      
		LEFT JOIN aicrm_leadscf on aicrm_leadscf.leadid=aicrm_leaddetails.leadid
		LEFT JOIN aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
		INNER JOIN aicrm_smartquestionnaire_leadsrel ON aicrm_smartquestionnaire_leadsrel.leadid=aicrm_leaddetails.leadid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
		WHERE aicrm_crmentity.deleted=0 AND aicrm_smartquestionnaire_leadsrel.smartquestionnaireid = ".$id;
		// echo 		$query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		// print_r($return_value);
		if($return_value == null) $return_value = Array();	
		$return_value['CUSTOM_BUTTON'] = $button;	

		$log->debug("Exiting get_accounts method ...");	
		return $return_value;	
	}
	
	function get_opportunity($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view, $currentModule;
		$log->debug("Entering get_opportunity(".$id.") method ...");
		$this_module = $currentModule;
		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		vtlib_setup_modulevars($related_module, $other);		
		$singular_modname = vtlib_toSingular($related_module);
		
		$parenttab = getParentTab();
		
		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;
		
		$button = '';
		/* To get Leads CustomView -START */
		require_once('modules/CustomView/CustomView.php');
		$lhtml = "<select id='opp_cv_list' class='small'><option value='None'>-- ".getTranslatedString('Select One')." --</option>";
		$oCustomView = new CustomView($related_module);
		$viewid = $oCustomView->getViewId($related_module);
		$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid, false);
		$lhtml .= $customviewcombo_html;
		$lhtml .= "</select>";
		/* To get Leads CustomView -END */
		//echo $id;
		$button .= $lhtml."<input title='Load List' class='crmbutton small edit' value='Load List' type='button' name='button' onclick='loadCvList_SMS(\"$related_module\",\"$id\")'>";
		$button .= '&nbsp;&nbsp;&nbsp;&nbsp';

		if($actions) {

			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) ) {
				$button .= "<input title='Select Opportunity' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='Select Opportunity'>&nbsp;";
			}
			if(in_array('ADD', $actions) ) {
				$button .= "<input title='Add Opportunity' class='crmbutton small create'" .
				" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
				" value='Add Opportunity'>&nbsp;";
			}
		}		
		$button .="<input class='crmbutton small delete' type='button' value='Clear Data' onclick=\"JavaScript: void window.open('clear_list_data.php?crmid=".$id."&module=".$_REQUEST["module"]."&related_module=".$related_module."','Application','resizable=0,left=450,top=100,width=500,height=200,toolbar=no,scrollbars=no,menubar=no,location=no')\"  />";	
		$query = "SELECT aicrm_opportunity.*,aicrm_opportunitycf.*, aicrm_crmentity.crmid, aicrm_account.*, aicrm_branchs.*,
		CASE when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, 
		aicrm_crmentity.smownerid 
		FROM aicrm_opportunity      
		LEFT JOIN aicrm_opportunitycf on aicrm_opportunitycf.opportunityid=aicrm_opportunity.opportunityid
		INNER JOIN aicrm_smartquestionnaire_opportunityrel ON aicrm_smartquestionnaire_opportunityrel.opportunityid=aicrm_opportunity.opportunityid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_opportunity.opportunityid
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
		LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_opportunity.accountid
		LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_opportunity.branchid
		WHERE aicrm_crmentity.deleted=0 AND aicrm_smartquestionnaire_opportunityrel.smartquestionnaireid = ".$id;
		//echo $query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
		if($return_value == null) $return_value = Array();	
		$return_value['CUSTOM_BUTTON'] = $button;	

		$log->debug("Exiting get_opportunity method ...");	
		return $return_value;	
	}
	
	function get_questionnaire($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view, $currentModule;
		$log->debug("Entering get_questionnaire(".$id.") method ...");
		$this_module = $currentModule;
		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		vtlib_setup_modulevars($related_module, $other);		
		$singular_modname = vtlib_toSingular($related_module);
		
		$parenttab = getParentTab();
		
		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;
		
		$button = '';
		/* To get Leads CustomView -START */
		require_once('modules/CustomView/CustomView.php');
		$lhtml = "<select id='ques_cv_list' class='small'><option value='None'>-- ".getTranslatedString('Select One')." --</option>";
		$oCustomView = new CustomView($related_module);
		$viewid = $oCustomView->getViewId($related_module);
		$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid, false);
		$lhtml .= $customviewcombo_html;
		$lhtml .= "</select>";
		/* To get Leads CustomView -END */
		//echo $id;
		$button .= $lhtml."<input title='Load List' class='crmbutton small edit' value='Load List' type='button' name='button' onclick='loadCvList_SMS(\"$related_module\",\"$id\")'>";
		$button .= '&nbsp;&nbsp;&nbsp;&nbsp';

		if($actions) {

			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) ) {
				$button .= "<input title='Select Questionnaire' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='Select Questionnaire'>&nbsp;";
			}
			if(in_array('ADD', $actions) ) {
				$button .= "<input title='Add Questionnaire' class='crmbutton small create'" .
				" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
				" value='Add Questionnaire'>&nbsp;";
			}
		}		
		$button .="<input class='crmbutton small delete' type='button' value='Clear Data' onclick=\"JavaScript: void window.open('clear_list_data.php?crmid=".$id."&module=".$_REQUEST["module"]."&related_module=".$related_module."','Application','resizable=0,left=200,top=50,width=500,height=200,toolbar=no,scrollbars=no,menubar=no,location=no')\"  />";	
		$query = "SELECT aicrm_questionnaires.*,aicrm_questionnairescf.*, aicrm_crmentity.crmid, 
		CASE when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, 
		aicrm_crmentity.smownerid 
		FROM aicrm_questionnaires      
		LEFT JOIN aicrm_questionnairescf on aicrm_questionnairescf.questionnaireid=aicrm_questionnaires.questionnaireid
		INNER JOIN aicrm_smartquestionnaire_questionnairerel ON aicrm_smartquestionnaire_questionnairerel.questionnaireid=aicrm_questionnaires.questionnaireid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_questionnaires.questionnaireid
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
		WHERE aicrm_crmentity.deleted=0 AND aicrm_smartquestionnaire_questionnairerel.smartquestionnaireid = ".$id;
		//echo 		$query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
		if($return_value == null) $return_value = Array();	
		$return_value['CUSTOM_BUTTON'] = $button;	

		$log->debug("Exiting get_questionnaire method ...");	
		return $return_value;	
	}
	
	function get_accounts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view, $currentModule;
		$log->debug("Entering get_accounts(".$id.") method ...");
		$this_module = $currentModule;
		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		vtlib_setup_modulevars($related_module, $other);		
		$singular_modname = vtlib_toSingular($related_module);
		
		$parenttab = getParentTab();
		
		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;
		
		$button = '';
		/* To get Leads CustomView -START */
		require_once('modules/CustomView/CustomView.php');
		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('ADVANCE_SELECT', $actions) ) {
//                $button .= "<input title='Advance Select' class='crmbutton small create' type='button' onclick=\"advanceSelect('$related_module','$id');\" value='Advance Select'>&nbsp;";
				$button .= "<input title='Advance Select' class='crmbutton small create' type='button' onclick=\"return window . open('AdvanceSearch.php?&currentModule=$currentModule&module=$related_module&recordid=$id', '', 'width=850,height=602,resizable=1,scrollbars=0');\" value='Advance Select'>&nbsp;";
			}
		}
		
		$lhtml = "<select id='account_cv_list' class='small loadlist'><option value='None'>-- ".getTranslatedString('Select One')." --</option>";
		$oCustomView = new CustomView($related_module);
		$viewid = $oCustomView->getViewId($related_module);
		$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid, false);
		$lhtml .= $customviewcombo_html;
		$lhtml .= "</select>";
		/* To get Leads CustomView -END */
		//echo $id;
		$button .= $lhtml."<input title='Load List' class='crmbutton small edit' value='Load List' type='button' name='button' onclick='loadCvList_Smartquestionnaire (\"$related_module\",\"$id\")'>";
		$button .= '&nbsp;&nbsp;&nbsp;&nbsp';

		if($actions) {

			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) ) {
				$button .= "<input title='Select Accounts' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=1,scrollbars=0');\" value='Select Accounts'>&nbsp;";
			}
			if(in_array('ADD', $actions) ) {
				$button .= "<input title='Add Accounts' class='crmbutton small create'" .
				" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
				" value='Add Accounts'>&nbsp;";
			}
		}		
		$button .="<input class='crmbutton small delete' type='button' value='Clear Data' onclick=\"JavaScript: void window.open('clear_list_data.php?crmid=".$id."&module=".$_REQUEST["module"]."&related_module=".$related_module."','Application','resizable=0,left=450,top=100,width=500,height=200,toolbar=no,scrollbars=no,menubar=no,location=no')\"  />";			
		$query = "SELECT aicrm_account.*,aicrm_accountscf.*, aicrm_crmentity.crmid, aicrm_accountbillads.*,
		CASE when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, 
		aicrm_crmentity.smownerid 
		FROM aicrm_account      
		LEFT JOIN aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
		left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid
		INNER JOIN aicrm_smartquestionnaire_accountsrel ON aicrm_smartquestionnaire_accountsrel.accountid=aicrm_account.accountid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
		WHERE aicrm_crmentity.deleted=0 AND aicrm_smartquestionnaire_accountsrel.smartquestionnaireid = ".$id;
		//echo $query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
		if($return_value == null) $return_value = Array();	
		$return_value['CUSTOM_BUTTON'] = $button;	
		
		$log->debug("Exiting get_accounts method ...");	
		return $return_value;	
	}	
	
	function get_sms_marketing($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view, $currentModule;
		$log->debug("Entering get_sms_marketing(".$id.") method ...");
		$this_module = $currentModule;
		$related_module = 'SMSMarketing';
		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;
		
		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('ADD', $actions) ) {
				$button .= "<input title='Add SMS Marketing' class='crmbutton small create'" .
				" onclick=\"window.open('sms_marketing.php?smartquestionnaireid=".$id."&userid=".$_SESSION["user_id"]."','test','resizable=no,left=200,top=150,width=600,height=320,toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no')\" type='button' name='button'" .
				" value='Add SMS Marketing'>&nbsp;";
			}
		}
		$query = "SELECT *
		FROM aicrm_campaign_sms_marketing  
		WHERE aicrm_campaign_sms_marketing.deleted=0 AND aicrm_campaign_sms_marketing.smartquestionnaireid = ".$id." 
		order by id desc
		";
		//echo 		$query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;
		$log->debug("Exiting get_sms_marketing method ...");	
		return $return_value;	
	}	
	// function generateReportsSecQuery($module,$secmodule){
	// 	$query = $this->getRelationQuery($module,$secmodule,"aicrm_smartquestionnaire","smartquestionnaireid");
	// 	$query .=" left join aicrm_crmentity as aicrm_crmentitySmartquestionnaires on aicrm_crmentitySmartquestionnaires.crmid=aicrm_smartquestionnaire.smartquestionnaireid and aicrm_crmentitySmartquestionnaires.deleted=0 
	// 			left join aicrm_smartquestionnairecf on aicrm_smartquestionnairecf.smartquestionnaireid = aicrm_crmentitySmartquestionnaires.crmid 
	// 			left join aicrm_groups as aicrm_groupsSmartquestionnaires on aicrm_groupsSmartquestionnaires.groupid = aicrm_crmentitySmartquestionnaires.smownerid
	// 			left join aicrm_users as aicrm_usersSmartquestionnaires on aicrm_usersSmartquestionnaires.id = aicrm_crmentitySmartquestionnaires.smownerid"; 
	// 	//echo $query;exit;
	// 	return $query;
	// }

	function generateReportsSecQuery($module,$secmodule){
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_smartquestionnaire","smartquestionnaireid");
		$query .=" left join aicrm_crmentity as aicrm_crmentitySmartquestionnaire on aicrm_crmentitySmartquestionnaire.crmid=aicrm_smartquestionnaire.smartquestionnaireid and aicrm_crmentitySmartquestionnaire.deleted=0 
		left join aicrm_questionnairetemplate on aicrm_questionnairetemplate.questionnairetemplateid = aicrm_smartquestionnaire.questionnairetemplateid
		left join aicrm_smartquestionnairecf on aicrm_smartquestionnairecf.smartquestionnaireid = aicrm_crmentitySmartquestionnaire.crmid 
		left join aicrm_groups as aicrm_groupsSmartquestionnaire on aicrm_groupsSmartquestionnaire.groupid = aicrm_crmentitySmartquestionnaire.smownerid
		left join aicrm_users as aicrm_usersSmartquestionnaire on aicrm_usersSmartquestionnaire.id = aicrm_crmentitySmartquestionnaire.smownerid
		LEFT JOIN aicrm_users as aicrm_usersModifiedSmartquestionnaire on aicrm_crmentity.modifiedby = aicrm_usersModifiedSmartquestionnaire.id
		LEFT JOIN aicrm_users AS aicrm_usersCreatorSmartquestionnaire ON aicrm_crmentity.smcreatorid = aicrm_usersCreatorSmartquestionnaire.id
		"; 
		//echo $query;exit;
		return $query;
	}
	
	/*
	 * Function to get the relation tables for related modules 
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){//echo "7787";
	$rel_tables = array (
			//"SalesOrder" =>array("aicrm_salesorder"=>array("quoteid","salesorderid"),"aicrm_smartquestionnaire"=>"quoteid"),
			//"Calendar" =>array("aicrm_seactivityrel"=>array("crmid","activityid"),"aicrm_smartquestionnaire"=>"smartquestionnaireid"),
		"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_smartquestionnaire"=>"smartquestionnaireid"),
		"Accounts" => array("aicrm_smartquestionnaire_accountsrel"=>array("smartquestionnaireid","accountid"),"aicrm_smartquestionnaire"=>"smartquestionnaireid"),
		"Contacts" => array("aicrm_smartquestionnaire_contactsrel"=>array("smartquestionnaireid","contactid"),"aicrm_smartquestionnaire"=>"smartquestionnaireid"),
		"Leads" => array("aicrm_smartquestionnaire_leadsrel"=>array("smartquestionnaireid","leadid"),"aicrm_smartquestionnaire"=>"smartquestionnaireid"),
		"Users" => array("aicrm_smartquestionnaire_usersrel"=>array("smartquestionnaireid","id"),"aicrm_smartquestionnaire"=>"smartquestionnaireid"),
		"Opportunity" => array("aicrm_smartquestionnaire_opportunityrel"=>array("smartquestionnaireid","opportunityid"),"aicrm_smartquestionnaire"=>"smartquestionnaireid"),
		"Questionnaire" => array("aicrm_smartquestionnaire_questionnairerel"=>array("smartquestionnaireid","questionnaireid"),"aicrm_smartquestionnaire"=>"smartquestionnaireid"),
			//"Contacts" => array("aicrm_quotes"=>array("aicrm_smartquestionnaire","contactid")),
			//"Potentials" => array("aicrm_quotes"=>array("quoteid","potentialid")),
	);
	return $rel_tables[$secmodule];
}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {//echo "ffff";
	// $return_module; exit; 
	global $log;
	if(empty($return_module) || empty($return_id)) return;
	
	if($return_module == 'Accounts' ) {
		$this->trash('Smartquestionnaire',$id);
	} elseif($return_module == 'Potentials') {
		$relation_query = 'UPDATE aicrm_quotes SET potentialid=0 WHERE quoteid=?';
		$this->db->pquery($relation_query, array($id));
	} elseif($return_module == 'Contacts') {
		$relation_query = 'UPDATE aicrm_quotes SET contactid=0 WHERE quoteid=?';
		$this->db->pquery($relation_query, array($id));
	} elseif($return_module == 'Campaigns') {
		$relation_query = 'DELETE FROM aicrm_campaignmaillistrel WHERE smartquestionnaireid=? AND campaignid=?';
		$this->db->pquery($relation_query, array($id, $return_id));
	} else {
		$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
		$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
		$this->db->pquery($sql, $params);
	}
}

}

?>
