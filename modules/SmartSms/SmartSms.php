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

class SmartSms extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_smartsms";
	var $table_index= 'smartsmsid';

	var $tab_name = Array('aicrm_crmentity','aicrm_smartsms','aicrm_smartsmscf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_smartsms'=>'smartsmsid','aicrm_smartsmscf'=>'smartsmsid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_smartsmscf', 'smartsmsid');
	var $column_fields = Array();

	var $sortby_fields = Array('smartsmsid','smartsms_name','smownerid');

	var $list_fields = Array(
					'Smart SMS No'=>Array('SmartSms'=>'smartsms_no'),
					'Smart SMS Name'=>Array('SmartSms'=>'smartsms_name'),
					'Date Sent'=>Array('SmartSms'=>'sms_start_date'),
					'Smart SMS Status'=>Array('SmartSms'=>'sms_status'),
					//'Assigned To' => Array('crmentity'=>'smownerid')
				);

	var $list_fields_name = Array(
					'Smart SMS No'=>'smartsms_no',
					'Smart SMS Name'=>'smartsms_name',
					'Date Sent'=>'sms_start_date',
					'Smart SMS Status'=>'sms_status',
					//'Assigned To'=>'assigned_user_id'
				     );	  			

	var $list_link_field= 'smartsms_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';

	//var $groupTable = Array('aicrm_campaigngrouprelation','projects_id');

	var $search_fields = Array(
			'Smart SMS No'=>Array('aicrm_smartsms'=>'smartsms_no'),
			'Smart SMS Name'=>Array('aicrm_smartsms'=>'smartsms_name'),
			);

	var $search_fields_name = Array(
			'Smart SMS No'=>'smartsms_no',
			'Smart SMS Name'=>'smartsms_name',
			);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','smartsmsid');
	
	function SmartSms()
	{
		$this->log =LoggerManager::getLogger('SmartSms');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('SmartSms');
	}
	function save_module()
	{
		// echo "555"; exit;
		global $adb;
		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'SmartSmsAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave')
		{
			//Based on the total Number of rows we will save the product relationship with this entity
			saveInventoryProductDetails($this, 'SmartSms');
			// echo "555"; exit;
		}
		//echo $_REQUEST['sms_sender_name'] ; exit;
		if(isset($_REQUEST['sms_sender_name']) &&  $_REQUEST['sms_sender_name'] != ''){
			// echo "555"; exit;
		
			$sql = "update aicrm_smartsms 
					   left join aicrm_config_sender_sms on aicrm_config_sender_sms.id = aicrm_smartsms.sms_sender_name
					   set aicrm_smartsms.sms_sender = aicrm_config_sender_sms.sms_sender , aicrm_smartsms.sms_url = aicrm_config_sender_sms.sms_url ,
					   aicrm_smartsms.sms_username = aicrm_config_sender_sms.sms_username , aicrm_smartsms.sms_password = aicrm_config_sender_sms.sms_password 
					   where  aicrm_smartsms.smartsmsid = '".$this->id."' ";	
			$adb->pquery($sql, array());	
		 }
		
		if(isset($_REQUEST['sms_status']) &&  $_REQUEST['sms_status'] == 'Active'){
			// echo "555"; exit;
			$sql = " update aicrm_smartsms
						set setup_sms = '0'
						,send_sms = '0'
						where smartsmsid = '".$this->id."' ";
			
			$adb->pquery($sql, array());
						
		}
		// Update the currency id and the conversion rate for the quotes
		//$update_query = "update aicrm_quotes set currency_id=?, conversion_rate=? where quoteid=?";
//		$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id); 
//		$adb->pquery($update_query, $update_params);
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
					INNER JOIN aicrm_smartsms_emailtargetrel ON aicrm_smartsms_emailtargetrel.emailtargetid=aicrm_emailtargets.emailtargetid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_emailtargets.emailtargetid
					LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
					LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
					WHERE aicrm_crmentity.deleted=0 AND aicrm_smartsms_emailtargetrel.smartsmsid = ".$id;
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
					INNER JOIN aicrm_smartsms_contactsrel ON aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
					LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
					LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
					LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_contactdetails.accountid
					WHERE aicrm_crmentity.deleted=0 AND aicrm_smartsms_contactsrel.smartsmsid = ".$id;
		//echo 		$query;
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
					INNER JOIN aicrm_smartsms_usersrel ON aicrm_smartsms_usersrel.id=aicrm_users.id
					left join aicrm_user2role on aicrm_user2role.userid=aicrm_users.id
					left join aicrm_role on aicrm_role.roleid 	=aicrm_user2role.roleid
					WHERE aicrm_smartsms_usersrel.smartsmsid = ".$id;
		// echo $query;
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
		$button .= $lhtml."<input title='Load List' class='crmbutton small edit' value='Load List' type='button' name='button' onclick='loadCvList_SMS(\"$related_module\",\"$id\")'>";
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
					INNER JOIN aicrm_smartsms_leadsrel ON aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
					LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
					LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
					WHERE aicrm_crmentity.deleted=0 AND aicrm_smartsms_leadsrel.smartsmsid = ".$id;
		// echo 		$query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
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
					INNER JOIN aicrm_smartsms_opportunityrel ON aicrm_smartsms_opportunityrel.opportunityid=aicrm_opportunity.opportunityid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_opportunity.opportunityid
					LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
					LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
					LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_opportunity.accountid
					LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_opportunity.branchid
					WHERE aicrm_crmentity.deleted=0 AND aicrm_smartsms_opportunityrel.smartsmsid = ".$id;
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
					INNER JOIN aicrm_smartsms_questionnairerel ON aicrm_smartsms_questionnairerel.questionnaireid=aicrm_questionnaires.questionnaireid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_questionnaires.questionnaireid
					LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
					LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
					WHERE aicrm_crmentity.deleted=0 AND aicrm_smartsms_questionnairerel.smartsmsid = ".$id;
		// echo $query;
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

                $button .= "<input title='Advance Select' class='crmbutton small create' type='button' onclick=\"return window . open('AdvanceSearch.php?&currentModule=$currentModule&module=$related_module&recordid=$id', '', 'width=640,height=602,resizable=1,scrollbars=0');\" value='Advance Select'>&nbsp;";
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
		$button .= $lhtml."<input title='Load List' class='crmbutton small edit' value='Load List' type='button' name='button' onclick='loadCvList_SMS(\"$related_module\",\"$id\")'>";
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
					INNER JOIN aicrm_smartsms_accountsrel ON aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
					LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
					LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
					WHERE aicrm_crmentity.deleted=0 AND aicrm_smartsms_accountsrel.smartsmsid = ".$id;
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
					" onclick=\"window.open('sms_marketing.php?smartsmsid=".$id."&userid=".$_SESSION["user_id"]."','test','resizable=no,left=200,top=150,width=600,height=320,toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no')\" type='button' name='button'" .
					" value='Add SMS Marketing'>&nbsp;";
			}
		}
		$query = "SELECT *
					FROM aicrm_campaign_sms_marketing  
					WHERE aicrm_campaign_sms_marketing.deleted=0 AND aicrm_campaign_sms_marketing.smartsmsid = ".$id." 
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
	// 	$query = $this->getRelationQuery($module,$secmodule,"aicrm_smartsms","smartsmsid");
	// 	$query .=" left join aicrm_crmentity as aicrm_crmentitySmartSmss on aicrm_crmentitySmartSmss.crmid=aicrm_smartsms.smartsmsid and aicrm_crmentitySmartSmss.deleted=0 
	// 			left join aicrm_smartsmscf on aicrm_smartsmscf.smartsmsid = aicrm_crmentitySmartSmss.crmid 
	// 			left join aicrm_groups as aicrm_groupsSmartSmss on aicrm_groupsSmartSmss.groupid = aicrm_crmentitySmartSmss.smownerid
	// 			left join aicrm_users as aicrm_usersSmartSmss on aicrm_usersSmartSmss.id = aicrm_crmentitySmartSmss.smownerid"; 
	// 	//echo $query;exit;
	// 	return $query;
	// }	
	function generateReportsSecQuery($module,$secmodule){
		//echo $module ."|".  $secmodule; exit;
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_smartsms","smartsmsid");
		$query .=" left join aicrm_crmentity as aicrm_crmentitySmartSms on aicrm_crmentitySmartSms.crmid=aicrm_smartsms.smartsmsid and aicrm_crmentitySmartSms.deleted=0 
				left join aicrm_smartsmscf on aicrm_smartsmscf.smartsmsid = aicrm_crmentitySmartSms.crmid 
				left join aicrm_groups as aicrm_groupsSmartSms on aicrm_groupsSmartSms.groupid = aicrm_crmentitySmartSms.smownerid

				left join aicrm_users as aicrm_usersSmartSms on aicrm_usersSmartSms.id = aicrm_crmentitySmartSms.smownerid
				LEFT JOIN aicrm_users AS aicrm_usersCreatorSmartSms ON aicrm_usersCreatorSmartSms.id = aicrm_crmentity.smcreatorid
				LEFT JOIN aicrm_users as aicrm_usersModifiedSmartSms on aicrm_usersModifiedSmartSms.id = aicrm_crmentity.modifiedby";
		// echo $query;exit;
		return $query;
	}
	
	/*
	 * Function to get the relation tables for related modules 
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (
			//"SalesOrder" =>array("aicrm_salesorder"=>array("quoteid","salesorderid"),"aicrm_smartsms"=>"quoteid"),
			//"Calendar" =>array("aicrm_seactivityrel"=>array("crmid","activityid"),"aicrm_smartsms"=>"smartsmsid"),
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_smartsms"=>"smartsmsid"),
			"Accounts" => array("aicrm_smartsms_accountsrel"=>array("smartsmsid","accountid"),"aicrm_smartsms"=>"smartsmsid"),
			"Contacts" => array("aicrm_smartsms_contactsrel"=>array("smartsmsid","contactid"),"aicrm_smartsms"=>"smartsmsid"),
			"Leads" => array("aicrm_smartsms_leadsrel"=>array("smartsmsid","leadid"),"aicrm_smartsms"=>"smartsmsid"),
			"Users" => array("aicrm_smartsms_usersrel"=>array("smartsmsid","id"),"aicrm_smartsms"=>"smartsmsid"),
			"Opportunity" => array("aicrm_smartsms_opportunityrel"=>array("smartsmsid","opportunityid"),"aicrm_smartsms"=>"smartsmsid"),
			"Questionnaire" => array("aicrm_smartsms_questionnairerel"=>array("smartsmsid","questionnaireid"),"aicrm_smartsms"=>"smartsmsid"),
			//"Contacts" => array("aicrm_quotes"=>array("aicrm_smartsms","contactid")),
			//"Potentials" => array("aicrm_quotes"=>array("quoteid","potentialid")),
		);
		return $rel_tables[$secmodule];
	}
	
	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {//echo "ffff";
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		
		if($return_module == 'Accounts' ) {
			$this->trash('SmartSms',$id);
		} elseif($return_module == 'Potentials') {
			$relation_query = 'UPDATE aicrm_quotes SET potentialid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE aicrm_quotes SET contactid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'Campaigns') {
			$relation_query = 'DELETE FROM aicrm_campaignmaillistrel WHERE smartsmsid=? AND campaignid=?';
			$this->db->pquery($relation_query, array($id, $return_id));
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

}

?>
