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
 * $Header: /advent/projects/wesat/aicrm_crm/sugarcrm/modules/Contacts/Contacts.php,v 1.70 2005/04/27 11:21:49 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('data/SugarBean.php');
require_once('include/utils/utils.php');
require_once('modules/Potentials/Potentials.php');
require_once('modules/Calendar/Activity.php');
require_once('modules/Campaigns/Campaigns.php');
require_once('modules/Documents/Documents.php');
require_once('modules/Emails/Emails.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('user_privileges/default_module_view.php');

// Contact is used to store customer information.
class Contacts extends CRMEntity {
	var $log;
	var $db;
	var $table_comment = "aicrm_contactdetailscomments";

	var $table_name = "aicrm_contactdetails";
	var $table_index= 'contactid';
	var $tab_name = Array('aicrm_crmentity','aicrm_contactdetails','aicrm_contactaddress','aicrm_contactsubdetails','aicrm_contactscf','aicrm_customerdetails');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_contactdetails'=>'contactid','aicrm_contactaddress'=>'contactaddressid','aicrm_contactsubdetails'=>'contactsubscriptionid','aicrm_contactscf'=>'contactid','aicrm_customerdetails'=>'customerid','aicrm_contactdetailscomments'=>'contactid');
	/**
	* Mandatory table for supporting custom fields.
	*/
	var $customFieldTable = Array('aicrm_contactscf', 'contactid');

	var $column_fields = Array();

	var $sortby_fields = Array('lastname','firstname','title','email','phone','smownerid','accountname');

	var $list_link_field= 'firstname';

	// This is the list of aicrm_fields that are in the lists.
	var $list_fields = Array(
		'หมายเลขผู้ติดต่อ' => Array('contactdetails'=>'contact_no'),
		'ชื่อ' => Array('contactdetails'=>'firstname'),
		'นามสกุล' => Array('contactdetails'=>'lastname'),
		'เบอร์ติดต่อ' => Array('contactdetails'=>'mobile'),
		'อีเมล'=>Array('contactdetails'=>'email'),
		/*'แผนก'=>Array('contactdetails'=>'department'),
		'สถานะผู้ติดต่อ'=>Array('contactdetails'=>'contactstatus'),
		'ตำแหน่ง'=>Array('contactdetails'=>'position'),*/
	);

	var $range_fields = Array(
		'first_name',
		'last_name',
		'account_id',
		'id',
	);
	
	var $list_fields_name = Array(
		'หมายเลขผู้ติดต่อ' =>'contact_no',
		'ชื่อ' =>'firstname',
		'นามสกุล' =>'lastname',
		'เบอร์ติดต่อ' =>'mobile',
		'อีเมล' =>'email',
		/*'แผนก' =>'department',
		'สถานะผู้ติดต่อ' =>'contactstatus',
		'ตำแหน่ง' =>'position',*/
	);

	var $search_fields = Array(
		'หมายเลขผู้ติดต่อ' => Array('contactdetails'=>'contact_no'),
		'ชื่อ' => Array('contactdetails'=>'firstname'),
		'นามสกุล' => Array('contactdetails'=>'lastname'),
		'เบอร์ติดต่อ' => Array('contactdetails'=>'mobile'),
		'อีเมล'=>Array('contactdetails'=>'email')
	);

	var $search_fields_name = Array(
		'หมายเลขผู้ติดต่อ' =>'contact_no',
		'ชื่อ' => 'firstname',
		'นามสกุล' => 'lastname',
		'เบอร์ติดต่อ'=>'mobile',
		'อีเมล'=>'email'
	);

	// This is the list of aicrm_fields that are required
	//var $required_fields =  array("lastname"=>1);
	var $required_fields =  array();
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','lastname','createdtime' ,'modifiedtime','picture');

	//Default Fields for Email Templates -- Pavani
	var $emailTemplate_defaultFields = array('firstname','lastname','salutation','title','email','department','phone','mobile','support_start_date','support_end_date');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';

	function Contacts() {
		$this->log = LoggerManager::getLogger('contact');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Contacts');
	}
	
    // Mike Crowe Mod --------------------------------------------------------Default ordering for us
	/**
	* Function to get sort order
	* return string  $sorder    - sortorder string either 'ASC' or 'DESC'
	*/
	function getSortOrder()
	{
		global $log;
		$log->debug("Entering getSortOrder() method ...");
		if(isset($_REQUEST['sorder']))
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['CONTACTS_SORT_ORDER'] != '')?($_SESSION['CONTACTS_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder method ...");
		return $sorder;
	}
	/**
	* Function to get order by
	* return string  $order_by    - fieldname(eg: 'Contactname')
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
			$order_by = (($_SESSION['CONTACTS_ORDER_BY'] != '')?($_SESSION['CONTACTS_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}
	// Mike Crowe Mod --------------------------------------------------------
	/** Function to get the number of Contacts assigned to a particular User.
	*  @param varchar $user name - Assigned to User
	*  Returns the count of contacts assigned to user.
	*/
	function getCount($user_name)
	{
		global $log;
		$log->debug("Entering getCount(".$user_name.") method ...");
		$query = "select count(*) from aicrm_contactdetails  inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid inner join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid where user_name=? and aicrm_crmentity.deleted=0";
		$result = $this->db->pquery($query,array($user_name),true,"Error retrieving contacts count");
		$rows_found =  $this->db->getRowCount($result);
		$row = $this->db->fetchByAssoc($result, 0);

		$log->debug("Exiting getCount method ...");
		return $row["count(*)"];
	}

	// This function doesn't seem to be used anywhere. Need to check and remove it.
	/** Function to get the Contact Details assigned to a given User ID which has a valid Email Address.
	* @param varchar $user_name - User Name (eg. Admin)
	* @param varchar $email_address - Email Addr of each contact record.
	* Returns the query.
	*/
	function get_contacts1($user_name,$email_address)
	{
		global $log;
		$log->debug("Entering get_contacts1(".$user_name.",".$email_address.") method ...");
		$query = "select aicrm_users.user_name, aicrm_contactdetails.lastname last_name,aicrm_contactdetails.firstname first_name,aicrm_contactdetails.contactid as id, aicrm_contactdetails.salutation as salutation, aicrm_contactdetails.email as email1,aicrm_contactdetails.title as title,aicrm_contactdetails.mobile as phone_mobile,aicrm_account.accountname as account_name,aicrm_account.accountid as account_id   from aicrm_contactdetails inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid inner join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid  left join aicrm_account on aicrm_account.accountid=aicrm_contactdetails.accountid left join aicrm_contactaddress on aicrm_contactaddress.contactaddressid=aicrm_contactdetails.contactid  where user_name='" .$user_name ."' and aicrm_crmentity.deleted=0  and aicrm_contactdetails.email like '". formatForSqlLike($email_address) ."' limit 50";

		$log->debug("Exiting get_contacts1 method ...");
		return $this->process_list_query1($query);
	}

	// This function doesn't seem to be used anywhere. Need to check and remove it.
	/** Function to get the Contact Details assigned to a particular User based on the starting count and the number of subsequent records.
	*  @param varchar $user_name - Assigned User
	*  @param integer $from_index - Initial record number to be displayed
	*  @param integer $offset - Count of the subsequent records to be displayed.
	*  Returns Query.
	*/
	function get_contacts($user_name,$from_index,$offset)
	{
		global $log;
		$log->debug("Entering get_contacts(".$user_name.",".$from_index.",".$offset.") method ...");
		$query = "select aicrm_users.user_name,aicrm_groups.groupname,aicrm_contactdetails.department department, aicrm_contactdetails.phone office_phone, aicrm_contactdetails.fax fax, aicrm_contactsubdetails.assistant assistant_name, aicrm_contactsubdetails.otherphone other_phone, aicrm_contactsubdetails.homephone home_phone,aicrm_contactsubdetails.birthday birthdate, aicrm_contactdetails.lastname last_name,aicrm_contactdetails.firstname first_name,aicrm_contactdetails.contactid as id, aicrm_contactdetails.salutation as salutation, aicrm_contactdetails.email as email1,aicrm_contactdetails.title as title,aicrm_contactdetails.mobile as phone_mobile,aicrm_account.accountname as account_name,aicrm_account.accountid as account_id, aicrm_contactaddress.mailingcity as primary_address_city,aicrm_contactaddress.mailingstreet as primary_address_street, aicrm_contactaddress.mailingcountry as primary_address_country,aicrm_contactaddress.mailingstate as primary_address_state, aicrm_contactaddress.mailingzip as primary_address_postalcode,   aicrm_contactaddress.othercity as alt_address_city,aicrm_contactaddress.otherstreet as alt_address_street, aicrm_contactaddress.othercountry as alt_address_country,aicrm_contactaddress.otherstate as alt_address_state, aicrm_contactaddress.otherzip as alt_address_postalcode  from aicrm_contactdetails inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid inner join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid left join aicrm_account on aicrm_account.accountid=aicrm_contactdetails.accountid left join aicrm_contactaddress on aicrm_contactaddress.contactaddressid=aicrm_contactdetails.contactid left join aicrm_contactsubdetails on aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid left join aicrm_users on aicrm_crmentity.smownerid=aicrm_users.id where user_name='" .$user_name ."' and aicrm_crmentity.deleted=0 limit " .$from_index ."," .$offset;

		$log->debug("Exiting get_contacts method ...");
		return $this->process_list_query1($query);
	}

    /** Function to process list query for a given query
    *  @param $query
    *  Returns the results of query in array format
    */
    function process_list_query1($query)
    {
    	global $log;
    	$log->debug("Entering process_list_query1(".$query.") method ...");

    	$result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
    	$list = Array();
    	$rows_found =  $this->db->getRowCount($result);
    	if($rows_found != 0)
    	{
    		$contact = Array();
    		for($index = 0 , $row = $this->db->fetchByAssoc($result, $index); $row && $index <$rows_found;$index++, $row = $this->db->fetchByAssoc($result, $index))

    		{
    			foreach($this->range_fields as $columnName)
    			{
    				if (isset($row[$columnName])) {

    					$contact[$columnName] = $row[$columnName];
    				}
    				else
    				{
    					$contact[$columnName] = "";
    				}
    			}
			// TODO OPTIMIZE THE QUERY ACCOUNT NAME AND ID are set separetly for every aicrm_contactdetails and hence
			// aicrm_account query goes for ecery single aicrm_account row
    			$list[] = $contact;
    		}
    	}

    	$response = Array();
    	$response['list'] = $list;
    	$response['row_count'] = $rows_found;
    	$response['next_offset'] = $next_offset;
    	$response['previous_offset'] = $previous_offset;


    	$log->debug("Exiting process_list_query1 method ...");
    	return $response;
    }


    /** Function to process list query for Plugin with Security Parameters for a given query
    *  @param $query
    *  Returns the results of query in array format
    */
    function plugin_process_list_query($query)
    {
    	global $log,$adb,$current_user;
    	$log->debug("Entering process_list_query1(".$query.") method ...");
    	$permitted_field_lists = Array();
    	require('user_privileges/user_privileges_'.$current_user->id.'.php');
    	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
    	{
    		$sql1 = "select columnname from aicrm_field where tabid=4 and block <> 75 and aicrm_field.presence in (0,2)";
    		$params1 = array();
    	}else
    	{
    		$profileList = getCurrentUserProfileList();
    		$sql1 = "select columnname from aicrm_field inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid where aicrm_field.tabid=4 and aicrm_field.block <> 6 and aicrm_field.block <> 75 and aicrm_field.displaytype in (1,2,4,3) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_field.presence in (0,2)";
    		$params1 = array();
    		if (count($profileList) > 0) {
    			$sql1 .= " and aicrm_profile2field.profileid in (". generateQuestionMarks($profileList) .")";
    			array_push($params1, $profileList);
    		}
    	}
    	$result1 = $this->db->pquery($sql1, $params1);
    	for($i=0;$i < $adb->num_rows($result1);$i++)
    	{
    		$permitted_field_lists[] = $adb->query_result($result1,$i,'columnname');
    	}

    	$result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
    	$list = Array();
    	$rows_found =  $this->db->getRowCount($result);
    	if($rows_found != 0)
    	{
    		for($index = 0 , $row = $this->db->fetchByAssoc($result, $index); $row && $index <$rows_found;$index++, $row = $this->db->fetchByAssoc($result, $index))
    		{
    			$contact = Array();

    			$contact[lastname] = in_array("lastname",$permitted_field_lists) ? $row[lastname] : "";
    			$contact[firstname] = in_array("firstname",$permitted_field_lists)? $row[firstname] : "";
    			$contact[email] = in_array("email",$permitted_field_lists) ? $row[email] : "";

    			if(in_array("accountid",$permitted_field_lists))
    			{
    				$contact[accountname] = $row[accountname];
    				$contact[account_id] = $row[accountid];
    			}else
    			{
    				$contact[accountname] = "";
    				$contact[account_id] = "";
    			}

    			$contact[contactid] =  $row[contactid];
    			$list[] = $contact;
    		}
    	}

    	$response = Array();
    	$response['list'] = $list;
    	$response['row_count'] = $rows_found;
    	$response['next_offset'] = $next_offset;
    	$response['previous_offset'] = $previous_offset;
    	$log->debug("Exiting process_list_query1 method ...");
    	return $response;
    }


	/** Returns a list of the associated opportunities
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_opportunities($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_opportunities(".$id.") method ...");
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
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
				" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_action.value=\"updateRelations\"' type='submit' name='button'" .
				" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query ='select case when (aicrm_users.user_name not like "") then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
		aicrm_contactdetails.accountid, aicrm_contactdetails.contactid , aicrm_potential.potentialid, aicrm_potential.potentialname,
		aicrm_potential.potentialtype, aicrm_potential.sales_stage, aicrm_potential.amount, aicrm_potential.closingdate,
		aicrm_potential.related_to, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_account.accountname
		from aicrm_contactdetails
		left join aicrm_contpotentialrel on aicrm_contpotentialrel.contactid=aicrm_contactdetails.contactid
		left join aicrm_potential on (aicrm_potential.potentialid = aicrm_contpotentialrel.potentialid or aicrm_potential.related_to=aicrm_contactdetails.contactid)
		inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_potential.potentialid
		left join aicrm_account on aicrm_account.accountid=aicrm_contactdetails.accountid
		left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
		left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid
		where aicrm_contactdetails.contactid ='.$id.'
		and (aicrm_contactdetails.accountid = aicrm_potential.related_to or aicrm_contactdetails.contactid=aicrm_potential.related_to)
		and aicrm_crmentity.deleted=0';

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_opportunities method ...");
		return $return_value;
	}

	function get_order($id, $cur_tab_id, $rel_tab_id, $actions = false)
	{
		global $log, $singlepane_view, $currentModule, $current_user;
		$log->debug("Entering get_order(" . $id . ") method ...");
		$this_module = $currentModule;

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if ($singlepane_view == 'true')
			$returnset = '&return_module=' . $this_module . '&return_action=DetailView&return_id=' . $id;
		else
			$returnset = '&return_module=' . $this_module . '&return_action=CallRelatedList&return_id=' . $id;

		$button = '';

		if ($actions) {
			if (is_string($actions)) $actions = explode(',', strtoupper($actions));
			if (in_array('SELECT', $actions) && isPermitted($related_module, 4, '') == 'yes') {
				$button .= "<input title='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "'>&nbsp;";
			}
			if (in_array('ADD', $actions) && isPermitted($related_module, 1, '') == 'yes') {
				$button .= "<input title='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "' class='crmbutton small create'" .
				" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
				" value='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "'>&nbsp;";
			}
		}

		$query = "SELECT aicrm_order.*, aicrm_ordercf.*, aicrm_account.accountname,
		aicrm_crmentity.*,
		CASE
		WHEN ( aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name
		ELSE aicrm_groups.groupname END AS user_name,
		aicrm_contactdetails.contactid,
		aicrm_contactdetails.contact_no,
		aicrm_contactdetails.firstname,
		aicrm_contactdetails.lastname
		FROM aicrm_order
		LEFT JOIN aicrm_ordercf ON aicrm_ordercf.orderid = aicrm_order.orderid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_order.orderid
		LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_order.accountid
		LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_order.contactid
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
		WHERE aicrm_crmentity.deleted = 0 AND aicrm_contactdetails.contactid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);
		if ($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_order method ...");
		return $return_value;
	}
	/** Returns a list of the associated tasks
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
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
				" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Events\";' type='submit' name='button'" .
				" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_EVENT', $related_module) ."'>";
			}
		}

		$query = "SELECT aicrm_activity.*, aicrm_activitycf.*,
		aicrm_crmentity.crmid, aicrm_crmentity.smownerid,
		aicrm_crmentity.modifiedtime,
		aicrm_crmentity.*,
		case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
		aicrm_recurringevents.recurringtype
		FROM aicrm_activity
		left join aicrm_activitycf on aicrm_activity.activityid = aicrm_activitycf.activityid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
		LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
		LEFT OUTER JOIN aicrm_recurringevents ON aicrm_recurringevents.activityid = aicrm_activity.activityid
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		
		LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_activity.contactid

		WHERE aicrm_contactdetails.contactid = ".$id."
		AND aicrm_crmentity.deleted = 0
		-- AND ((aicrm_activity.activitytype='Task' and aicrm_activity.status not in ('Completed','Deferred'))
		-- OR (aicrm_activity.activitytype not in ('Emails','Task') and  aicrm_activity.eventstatus not in ('','Held'))) ";
		
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}
	/**
	* Function to get Contact related Task & Event which have activity type Held, Completed or Deferred.
	* @param  integer   $id      - contactid
	* returns related Task or Event record in array format
	*/
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$query = "SELECT aicrm_activity.activityid, aicrm_activity.subject, aicrm_activity.status, aicrm_activity.eventstatus,aicrm_activity.activitytype, aicrm_activity.date_start, aicrm_activity.due_date,aicrm_activity.time_start,aicrm_activity.time_end,aicrm_contactdetails.contactid, aicrm_contactdetails.firstname,aicrm_contactdetails.lastname, aicrm_crmentity.modifiedtime,aicrm_crmentity.createdtime, aicrm_crmentity.description, case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
		from aicrm_activity
		inner join aicrm_cntactivityrel on aicrm_cntactivityrel.activityid= aicrm_activity.activityid
		inner join aicrm_contactdetails on aicrm_contactdetails.contactid= aicrm_cntactivityrel.contactid
		inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid
		left join aicrm_seactivityrel on aicrm_seactivityrel.activityid=aicrm_activity.activityid
		left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
		left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid
		where (aicrm_activity.activitytype = 'Meeting' or aicrm_activity.activitytype='Call' or aicrm_activity.activitytype='Task')
		and (aicrm_activity.status = 'Completed' or aicrm_activity.status = 'Deferred' or (aicrm_activity.eventstatus = 'Held' and aicrm_activity.eventstatus != ''))
		and aicrm_cntactivityrel.contactid=".$id."
		and aicrm_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php
		$log->debug("Entering get_history method ...");
		return getHistory('Contacts',$query,$id);
	}
	/**
	* Function to get Contact related Tickets.
	* @param  integer   $id      - contactid
	* returns related Ticket records in array format
	*/
	function get_tickets($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_tickets(".$id.") method ...");
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

		//if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'parent_id') == '0') {
		if(is_string($actions)) $actions = explode(',', strtoupper($actions));
		if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
			$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
		}
		if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
			$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
			" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
			" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
		}
		//}
		//echo $button;
		$query = "select case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
		aicrm_crmentity.crmid, aicrm_troubletickets.title, aicrm_contactdetails.contactid,
		aicrm_contactdetails.firstname, aicrm_contactdetails.lastname, aicrm_troubletickets.status,
		aicrm_crmentity.smownerid, aicrm_troubletickets.ticket_no ,aicrm_troubletickets.ticket_type ,aicrm_troubletickets.ticket_important, aicrm_troubletickets.case_open_date
		from aicrm_troubletickets 
		inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_troubletickets.ticketid
		left join aicrm_contactdetails on aicrm_contactdetails.contactid= aicrm_troubletickets.contactid
		left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid
		left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
		where aicrm_crmentity.deleted=0 and aicrm_contactdetails.contactid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_tickets method ...");
		return $return_value;
	}

  	/**
  	* Function to get Contact related Quotes
  	* @param  integer   $id  - contactid
  	* returns related Quotes record in array format
  	*/
	function get_quotes($id, $cur_tab_id, $rel_tab_id, $actions=false) {
	  	global $log, $singlepane_view,$currentModule,$current_user;
	  	$log->debug("Entering get_quotes(".$id.") method ...");
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

	  	if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'contact_id') == '0') {
	  		if(is_string($actions)) $actions = explode(',', strtoupper($actions));
	  		if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
	  			$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
	  		}
	  		if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
	  			$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
	  			" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
	  			" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
	  		}
	  	}

	  	$query = "select case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,aicrm_crmentity.*, 
	  	aicrm_quotes.*, aicrm_quotescf.*,aicrm_potential.potentialname,aicrm_contactdetails.lastname,aicrm_account.accountname 
	  	from aicrm_quotes 
	  	inner join aicrm_quotescf on aicrm_quotescf.quoteid = aicrm_quotes.quoteid
	  	inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_quotes.quoteid
	  	left outer join aicrm_contactdetails on aicrm_contactdetails.contactid=aicrm_quotes.contactid 
	  	left outer join aicrm_potential on aicrm_potential.potentialid=aicrm_quotes.potentialid 
	  	left join aicrm_account on aicrm_account.accountid = aicrm_quotes.accountid 
	  	left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid 
	  	left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid 
	  	where aicrm_crmentity.deleted=0 and aicrm_contactdetails.contactid=".$id;

	  	$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

	  	if($return_value == null) $return_value = Array();
	  	$return_value['CUSTOM_BUTTON'] = $button;

	  	$log->debug("Exiting get_quotes method ...");
	  	return $return_value;
	}

	function get_leads($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
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

        if($actions) {
            if(is_string($actions)) $actions = explode(',', strtoupper($actions));
           
            if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                    " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                    " value='". getTranslatedString('LBL_ADD_NEW'). " Competitor Analysis'>&nbsp;";
            }
        }

        $query = "SELECT aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_leaddetails.*, aicrm_leadscf.*
        			FROM aicrm_leaddetails
        			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
        			INNER JOIN aicrm_leadsubdetails   ON aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid
        			INNER JOIN aicrm_leadaddress ON aicrm_leadaddress.leadaddressid = aicrm_leadsubdetails.leadsubscriptionid
        			INNER JOIN aicrm_leadscf ON aicrm_leaddetails.leadid = aicrm_leadscf.leadid
                    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
        			LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
        			LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                    LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
        			WHERE aicrm_crmentity.deleted = 0
					AND aicrm_leaddetails.contactid = '".$id."' ";
			
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_leads method ...");
        return $return_value;

    }

	function get_projects($id, $cur_tab_id, $rel_tab_id, $actions=false)
	{
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_projects(".$id.") method ...");
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
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = "
			SELECT aicrm_projects.*,aicrm_projectscf.*,aicrm_crmentity.crmid,aicrm_account.*,aicrm_contactdetails.*, aicrm_users.*
			FROM  aicrm_projects
			LEFT JOIN  aicrm_projectscf ON aicrm_projectscf.projectsid = aicrm_projects.projectsid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid =aicrm_projects.projectsid
			LEFT JOIN aicrm_account ON aicrm_account.accountid =  aicrm_projects.accountid
			LEFT OUTER JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_projects.contactid
			LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_projects.contactid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_projects method ...");
		return $return_value;
	}

	function get_samplerequisition($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_samplerequisition(".$id.") method ...");
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
           
            if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                    " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                    " value='". getTranslatedString('LBL_ADD_NEW'). " Competitor Analysis'>&nbsp;";
            }
        }

        $query = "SELECT  aicrm_users.user_name,
			aicrm_crmentity.*,
			aicrm_samplerequisition.*,
			aicrm_samplerequisitioncf.*
			FROM aicrm_samplerequisition
			INNER JOIN aicrm_samplerequisitioncf ON aicrm_samplerequisitioncf.samplerequisitionid = aicrm_samplerequisition.samplerequisitionid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_samplerequisition.samplerequisitionid
			INNER JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_samplerequisition.contactid
			INNER JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_samplerequisition.contactid = '".$id."'
			";
        //LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_competitor.product_id
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_samplerequisition method ...");
        return $return_value;

    }

    function get_expense($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_expense(".$id.") method ...");
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
           
            if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
                $button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
                    " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                    " value='". getTranslatedString('LBL_ADD_NEW'). " Competitor Analysis'>&nbsp;";
            }
        }

        $query = "SELECT  aicrm_users.user_name,aicrm_account.accountname,
			aicrm_crmentity.*,
			aicrm_expense.*,
			aicrm_expensecf.*,
			aicrm_account.*
			FROM aicrm_expense
			LEFT JOIN aicrm_expensecf ON aicrm_expensecf.expenseid = aicrm_expense.expenseid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_expense.expenseid
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_expense.account_id
			LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_expense.contactid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_expense.contactid = '".$id."' ";
        //LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_competitor.product_id
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_expense method ...");
        return $return_value;

    }

	/** Returns a list of the associated emails
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function get_emails($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_emails(".$id.") method ...");
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

		$button .= '<input type="hidden" name="email_directing_module"><input type="hidden" name="record">';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."' accessyKey='F' class='crmbutton small create' onclick='fnvshobj(this,\"sendmail_cont\");sendmail(\"$this_module\",$id);' type='button' name='button' value='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."'></td>";
			}
		}

		$query = "select case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name," .
		" aicrm_activity.activityid, aicrm_activity.subject, aicrm_activity.activitytype, aicrm_crmentity.modifiedtime," .
		" aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_activity.date_start, aicrm_seactivityrel.crmid as parent_id " .
		" from aicrm_activity, aicrm_seactivityrel, aicrm_contactdetails, aicrm_users, aicrm_crmentity" .
		" left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid" .
		" where aicrm_seactivityrel.activityid = aicrm_activity.activityid" .
		" and aicrm_contactdetails.contactid = aicrm_seactivityrel.crmid and aicrm_users.id=aicrm_crmentity.smownerid" .
		" and aicrm_crmentity.crmid = aicrm_activity.activityid  and aicrm_contactdetails.contactid = ".$id." and" .
		" aicrm_activity.activitytype='Emails' and aicrm_crmentity.deleted = 0";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_emails method ...");
		return $return_value;
	}

	/** Function to export the contact records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Contacts Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Contacts", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
		FROM aicrm_contactdetails
		INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
		INNER JOIN aicrm_contactaddress on aicrm_contactaddress.contactaddressid=aicrm_contactdetails.contactid
		INNER JOIN aicrm_contactsubdetails on aicrm_contactsubdetails.contactsubscriptionid=aicrm_contactdetails.contactid
		INNER join aicrm_contactscf on aicrm_contactscf.contactid=aicrm_contactdetails.contactid
		LEFT JOIN aicrm_account on aicrm_contactdetails.accountid=aicrm_account.accountid
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id and aicrm_users.status='Active'
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
		LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
		";
		//LEFT join aicrm_customerdetails on aicrm_customerdetails.customerid=aicrm_contactdetails.contactid
		//LEFT JOIN aicrm_account on aicrm_contactdetails.accountid=aicrm_account.accountid
		//LEFT JOIN aicrm_contactdetails aicrm_contactdetails2 ON aicrm_contactdetails2.contactid = aicrm_contactdetails.reportsto
		$query .= setFromQuery("Contacts");
		$where_auto = " aicrm_crmentity.deleted = 0 ";

		if($where != "")
			$query .= "  WHERE ($where) AND ".$where_auto;
		else
			$query .= "  WHERE ".$where_auto;

		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		//we should add security check when the user has Private Access
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[4] == 3)
		{
			//Added security check to get the permitted records only
			$query = $query." ".getListViewSecurityParameter("Contacts");
		}
		$log->info("Export Query Constructed Successfully");
		$log->debug("Exiting create_export_query method ...");
		return $query;
	}


	/** Function to get the Columnnames of the Contacts
	* Used By vtigerCRM Word Plugin
	* Returns the Merge Fields for Word Plugin
	*/
	function getColumnNames()
	{
		global $log, $current_user;
		$log->debug("Entering getColumnNames() method ...");
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql1 = "select fieldlabel from aicrm_field where tabid=4 and block <> 75 and aicrm_field.presence in (0,2)";
			$params1 = array();
		}else
		{
			$profileList = getCurrentUserProfileList();
			$sql1 = "select aicrm_field.fieldid,fieldlabel from aicrm_field inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid where aicrm_field.tabid=4 and aicrm_field.block <> 75 and aicrm_field.displaytype in (1,2,4,3) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_field.presence in (0,2)";
			$params1 = array();
			if (count($profileList) > 0) {
				$sql1 .= " and aicrm_profile2field.profileid in (". generateQuestionMarks($profileList) .") group by fieldid";
				array_push($params1, $profileList);
			}
		}
		$result = $this->db->pquery($sql1, $params1);
		$numRows = $this->db->num_rows($result);
		for($i=0; $i < $numRows;$i++)
		{
			$custom_fields[$i] = $this->db->query_result($result,$i,"fieldlabel");
			$custom_fields[$i] = ereg_replace(" ","",$custom_fields[$i]);
			$custom_fields[$i] = strtoupper($custom_fields[$i]);
		}
		$mergeflds = $custom_fields;
		$log->debug("Exiting getColumnNames method ...");
		return $mergeflds;
	}
	//End
	/** Function to get the Contacts assigned to a user with a valid email address.
	* @param varchar $username - User Name
	* @param varchar $emailaddress - Email Addr for each contact.
	* Used By vtigerCRM Outlook Plugin
	* Returns the Query
	*/
	function get_searchbyemailid($username,$emailaddress)
	{
		global $log;
		global $current_user;
		require_once("modules/Users/Users.php");
		$seed_user=new Users();
		$user_id=$seed_user->retrieve_user_id($username);
		$current_user=$seed_user;
		$current_user->retrieve_entity_info($user_id, 'Users');
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		$log->debug("Entering get_searchbyemailid(".$username.",".$emailaddress.") method ...");
		$query = "select aicrm_contactdetails.lastname,aicrm_contactdetails.firstname,
		aicrm_contactdetails.contactid, aicrm_contactdetails.salutation,
		aicrm_contactdetails.email,aicrm_contactdetails.title,
		aicrm_contactdetails.mobile,aicrm_account.accountname,
		aicrm_account.accountid as accountid  from aicrm_contactdetails
		inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
		inner join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid
		left join aicrm_account on aicrm_account.accountid=aicrm_contactdetails.accountid
		left join aicrm_contactaddress on aicrm_contactaddress.contactaddressid=aicrm_contactdetails.contactid
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		where aicrm_crmentity.deleted=0";
		if(trim($emailaddress) != '')
			$query .= " and ((aicrm_contactdetails.email like '". formatForSqlLike($emailaddress) ."') or aicrm_contactdetails.lastname REGEXP REPLACE('".$emailaddress."',' ','|') or aicrm_contactdetails.firstname REGEXP REPLACE('".$emailaddress."',' ','|'))  and aicrm_contactdetails.email != ''";
		else
			$query .= " and (aicrm_contactdetails.email like '". formatForSqlLike($emailaddress) ."' and aicrm_contactdetails.email != '')";

		$tab_id = getTabid("Contacts");
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter("Contacts");
			$query .= $sec_parameter;

		}
		$log->debug("Exiting get_searchbyemailid method ...");
		return $this->plugin_process_list_query($query);
	}

	/** Function to get the Contacts associated with the particular User Name.
	*  @param varchar $user_name - User Name
	*  Returns query
	*/

	function get_contactsforol($user_name)
	{
		global $log,$adb;
		global $current_user;
		require_once("modules/Users/Users.php");
		$seed_user=new Users();
		$user_id=$seed_user->retrieve_user_id($user_name);
		$current_user=$seed_user;
		$current_user->retrieve_entity_info($user_id, 'Users');
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');

		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql1 = "select tablename,columnname from aicrm_field where tabid=4 and aicrm_field.presence in (0,2)";
			$params1 = array();
		}else
		{
			$profileList = getCurrentUserProfileList();
			$sql1 = "select tablename,columnname from aicrm_field inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid where aicrm_field.tabid=4 and aicrm_field.displaytype in (1,2,4,3) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_field.presence in (0,2)";
			$params1 = array();
			if (count($profileList) > 0) {
				$sql1 .= " and aicrm_profile2field.profileid in (". generateQuestionMarks($profileList) .")";
				array_push($params1, $profileList);
			}
		}
		$result1 = $adb->pquery($sql1, $params1);
		for($i=0;$i < $adb->num_rows($result1);$i++)
		{
			$permitted_lists[] = $adb->query_result($result1,$i,'tablename');
			$permitted_lists[] = $adb->query_result($result1,$i,'columnname');
			if($adb->query_result($result1,$i,'columnname') == "accountid")
			{
				$permitted_lists[] = 'aicrm_account';
				$permitted_lists[] = 'accountname';
			}
		}
		$permitted_lists = array_chunk($permitted_lists,2);
		$column_table_lists = array();
		for($i=0;$i < count($permitted_lists);$i++)
		{
			$column_table_lists[] = implode(".",$permitted_lists[$i]);
		}

		$log->debug("Entering get_contactsforol(".$user_name.") method ...");
		$query = "select aicrm_contactdetails.contactid as id, ".implode(',',$column_table_lists)." from aicrm_contactdetails
		inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
		inner join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid
		left join aicrm_customerdetails on aicrm_customerdetails.customerid=aicrm_contactdetails.contactid
		left join aicrm_account on aicrm_account.accountid=aicrm_contactdetails.accountid
		left join aicrm_contactaddress on aicrm_contactaddress.contactaddressid=aicrm_contactdetails.contactid
		left join aicrm_contactsubdetails on aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		where aicrm_crmentity.deleted=0 and aicrm_users.user_name='".$user_name."'";
		$log->debug("Exiting get_contactsforol method ...");
		return $query;
	}


	/** Function to handle module specific operations when saving a entity
	*/
	function save_module($module)
	{
		$this->insertIntoAttachment($this->id,$module);
		$this->insertIntoCommentTable("aicrm_contactdetailscomments",'contactid');
	}

	function insertIntoCommentTable($table_name, $module)
	{
		global $log;
		$log->info("in insertIntoCommentTable  ".$table_name."    module is  ".$module);
		global $adb;
		global $current_user;

		$current_time = $adb->formatDate(date('YmdHis'), true);
		if($this->column_fields['assigned_user_id'] != ''){
			$ownertype = 'user';
		}	
		else
		{
			$ownertype = 'customer';
		}

		if($this->column_fields['comments'] != ''){			
			$comment = $this->column_fields['comments'];
		}
		else
		{
			$comment = $_REQUEST['comments'];
		}	
		
		if($comment!=""){
			$sql = "insert into ".$table_name." values(?,?,?,?,?,?)";
			$params = array('', $this->id, from_html($comment), $current_user->id, $ownertype, $current_time);
			$adb->pquery($sql, $params);
		}
	}

	function getCommentInformation($crmid)
	{
		global $log;
		$log->debug("Entering getCommentInformation(".$crmid.") method ...");
		global $adb;
		global $mod_strings, $default_charset;
		$sql = "select * from aicrm_contactdetailscomments where contactid=? order by createdtime desc";
		$result = $adb->pquery($sql, array($crmid));
		$noofrows = $adb->num_rows($result);

		//In ajax save we should not add this div
		if($_REQUEST['action'] != 'ServiceRequestAjax')
		{
			$list .= '<div id="comments_div" style="overflow: auto;height:200px;width:100%;display:block;">';
			$enddiv = '</div>';
		}
		for($i=0;$i<$noofrows;$i++)
		{
			if($adb->query_result($result,$i,'comments') != '')
			{
				//this div is to display the comment
				$comment = $adb->query_result($result,$i,'comments');
				// Asha: Fix for ticket #4478 . Need to escape html tags during ajax save.
				if($_REQUEST['action'] == 'ServiceRequestAjax') {
					$comment = htmlentities($comment, ENT_QUOTES, $default_charset);
				}
				$list .= '<div valign="top" style="width:99%;padding-top:10px;" class="dataField">';
				$list .= make_clickable(nl2br($comment));

				$list .= '</div>';

				//this div is to display the author and time
				$list .= '<div valign="top" style="width:99%;border-bottom:1px dotted #CCCCCC;padding-bottom:5px;" class="dataLabel"><font color=darkred>';
				$list .= $mod_strings['LBL_AUTHOR'].' : ';

				if($adb->query_result($result,$i,'ownertype') == 'user')
					$list .= getUserName($adb->query_result($result,$i,'ownerid'));
				elseif($adb->query_result($result,$i,'ownertype') == 'customer') {
					$contactid = $adb->query_result($result,$i,'ownerid');
					$list .= getContactName($contactid);
				}
				$list .= ' on '.date('d-m-Y H:i:s',strtotime($adb->query_result($result,$i,'createdtime'))).' &nbsp;';

				$list .= '</font></div>';
			}
		}

		$list .= $enddiv;

		$log->debug("Exiting getCommentInformation method ...");
		return $list;
	}	

	/**
	 *      This function is used to add the aicrm_attachments. This will call the function uploadAndSaveFile which will upload the attachment into the server and save that attachment information in the database.
	 *      @param int $id  - entity id to which the aicrm_files to be uploaded
	 *      @param string $module  - the current module name
	*/
	function insertIntoAttachment($id,$module){
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;

		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				if($_REQUEST[$fileindex.'_hidden'] != '')
					$files['original_name'] = vtlib_purify($_REQUEST[$fileindex.'_hidden']);
				else
					$files['original_name'] = stripslashes($files['name']);
				$files['original_name'] = str_replace('"','',$files['original_name']);

				if($fileindex == 'image_vendor'){
					$files['flag'] = 'S';
					$files['fileindex'] = $fileindex;
				}

				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		//Remove the deleted aicrm_attachments from db - Products
		if($module == 'Contacts' && $_REQUEST['del_file_list'] != '')
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

	/**
	 * Move the related records of the specified list of id's to the given record.
	 * @param String This module name
	 * @param Array List of Entity Id's from which related records need to be transfered
	 * @param Integer Id of the the Record to which the related records are to be moved
	 */
	function transferRelatedRecords($module, $transferEntityIds, $entityId) {
		global $adb,$log;
		$log->debug("Entering function transferRelatedRecords ($module, $transferEntityIds, $entityId)");

		$rel_table_arr = Array("Potentials"=>"aicrm_contpotentialrel","Activities"=>"aicrm_cntactivityrel","Emails"=>"aicrm_seactivityrel",
			"HelpDesk"=>"aicrm_troubletickets","Quotes"=>"aicrm_quotes","PurchaseOrder"=>"aicrm_purchaseorder",
			"SalesOrder"=>"aicrm_salesorder","Products"=>"aicrm_seproductsrel","Documents"=>"aicrm_senotesrel",
			"Attachments"=>"aicrm_seattachmentsrel","Campaigns"=>"aicrm_campaigncontrel","Calendar"=>"aicrm_activitycf");

		$tbl_field_arr = Array("aicrm_contpotentialrel"=>"potentialid","aicrm_cntactivityrel"=>"activityid","aicrm_seactivityrel"=>"activityid",
			"aicrm_troubletickets"=>"ticketid","aicrm_quotes"=>"quoteid","aicrm_purchaseorder"=>"purchaseorderid",
			"aicrm_salesorder"=>"salesorderid","aicrm_seproductsrel"=>"productid","aicrm_senotesrel"=>"notesid",
			"aicrm_seattachmentsrel"=>"attachmentsid","aicrm_campaigncontrel"=>"campaignid","aicrm_activitycf"=>"activityid");

		$entity_tbl_field_arr = Array("aicrm_contpotentialrel"=>"contactid","aicrm_cntactivityrel"=>"contactid","aicrm_seactivityrel"=>"crmid",
			"aicrm_troubletickets"=>"contactid","aicrm_quotes"=>"contactid","aicrm_purchaseorder"=>"contactid",
			"aicrm_salesorder"=>"contactid","aicrm_seproductsrel"=>"crmid","aicrm_senotesrel"=>"crmid",
			"aicrm_seattachmentsrel"=>"crmid","aicrm_campaigncontrel"=>"contactid","aicrm_activitycf"=>"contactid");

		foreach($transferEntityIds as $transferId) {
			foreach($rel_table_arr as $rel_module=>$rel_table) {
				$id_field = $tbl_field_arr[$rel_table];
				$entity_id_field = $entity_tbl_field_arr[$rel_table];
				// IN clause to avoid duplicate entries
				$sel_result =  $adb->pquery("select $id_field from $rel_table where $entity_id_field=? " .
					" and $id_field not in (select $id_field from $rel_table where $entity_id_field=?)",
					array($transferId,$entityId));
				$res_cnt = $adb->num_rows($sel_result);
				if($res_cnt > 0) {
					for($i=0;$i<$res_cnt;$i++) {
						$id_field_value = $adb->query_result($sel_result,$i,$id_field);
						$adb->pquery("update $rel_table set $entity_id_field=? where $entity_id_field=? and $id_field=?",
							array($entityId,$transferId,$id_field_value));
					}
				}
			}
		}
		$log->debug("Exiting transferRelatedRecords...");
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		// echo $module." | ".$secmodule; exit;
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_contactdetails","contactid");

		$query .= " 
		left join aicrm_crmentity as aicrm_crmentityContacts on aicrm_crmentityContacts.crmid = aicrm_contactdetails.contactid  and aicrm_crmentityContacts.deleted=0
		left join aicrm_contactaddress on aicrm_contactdetails.contactid = aicrm_contactaddress.contactaddressid
		left join aicrm_customerdetails on aicrm_customerdetails.customerid = aicrm_contactdetails.contactid
		left join aicrm_contactsubdetails on aicrm_contactdetails.contactid = aicrm_contactsubdetails.contactsubscriptionid
		left join aicrm_contactscf on aicrm_contactdetails.contactid = aicrm_contactscf.contactid
		left join aicrm_groups as aicrm_groupsContacts on aicrm_groupsContacts.groupid = aicrm_crmentityContacts.smownerid
		
		LEFT JOIN aicrm_users AS aicrm_usersCreatorContacts ON aicrm_usersCreatorContacts.id = aicrm_crmentityContacts.smcreatorid
		LEFT JOIN aicrm_users AS aicrm_usersModifiedContacts ON aicrm_usersModifiedContacts.id = aicrm_crmentityContacts.modifiedby
		LEFT JOIN aicrm_users AS aicrm_usersContacts ON aicrm_usersContacts.id = aicrm_crmentityContacts.smownerid 
		";

		// if($module == "Accounts" && $secmodule == "Contacts"){
		// 	$query .= " -- left join aicrm_account as aicrm_accountContacts on aicrm_accountContacts.accountid = aicrm_contactdetails.accountid";
		// }

		return $query;
	}

	/*
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){

		$rel_tables = array (
			"Calendar" => array("aicrm_activitycf"=>array("contactid","activityid"),"aicrm_contactdetails"=>"contactid"),
			"HelpDesk" => array("aicrm_troubletickets"=>array("contactid","ticketid"),"aicrm_contactdetails"=>"contactid"),
			"Quotes" => array("aicrm_quotes"=>array("quoteid","contactid"),"aicrm_contactdetails"=>"contactid"),
			"Projects" => array("aicrm_projects"=>array("contactid","projectsid"),"aicrm_contactdetails"=>"contactid"),
			"Products" => array("aicrm_seproductsrel"=>array("crmid","productid"),"aicrm_contactdetails"=>"contactid"),
			"Campaigns" => array("aicrm_campaigncontrel"=>array("contactid","campaignid"),"aicrm_contactdetails"=>"contactid"),
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_contactdetails"=>"contactid"),
			"Accounts" => array("aicrm_contactdetails"=>array("contactid","accountid")),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;
		//Deleting Contact related Potentials.
		$pot_q = 'SELECT aicrm_crmentity.crmid FROM aicrm_crmentity
		INNER JOIN aicrm_potential ON aicrm_crmentity.crmid=aicrm_potential.potentialid
		LEFT JOIN aicrm_account ON aicrm_account.accountid=aicrm_potential.related_to
		WHERE aicrm_crmentity.deleted=0 AND aicrm_potential.related_to=?';
		$pot_res = $this->db->pquery($pot_q, array($id));
		$pot_ids_list = array();
		for($k=0;$k < $this->db->num_rows($pot_res);$k++)
		{
			$pot_id = $this->db->query_result($pot_res,$k,"crmid");
			$pot_ids_list[] = $pot_id;
			$sql = 'UPDATE aicrm_crmentity SET deleted = 1 WHERE crmid = ?';
			$this->db->pquery($sql, array($pot_id));
		}
		//Backup deleted Contact related Potentials.
		$params = array($id, RB_RECORD_UPDATED, 'aicrm_crmentity', 'deleted', 'crmid', implode(",", $pot_ids_list));
		$this->db->pquery('INSERT INTO aicrm_relatedlists_rb VALUES(?,?,?,?,?,?)', $params);

		//Backup Contact-Trouble Tickets Relation
		$tkt_q = 'SELECT ticketid FROM aicrm_troubletickets WHERE parent_id=?';
		$tkt_res = $this->db->pquery($tkt_q, array($id));
		if ($this->db->num_rows($tkt_res) > 0) {
			$tkt_ids_list = array();
			for($k=0;$k < $this->db->num_rows($tkt_res);$k++)
			{
				$tkt_ids_list[] = $this->db->query_result($tkt_res,$k,"ticketid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'aicrm_troubletickets', 'parent_id', 'ticketid', implode(",", $tkt_ids_list));
			$this->db->pquery('INSERT INTO aicrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//removing the relationship of contacts with Trouble Tickets
		$this->db->pquery('UPDATE aicrm_troubletickets SET parent_id=0 WHERE parent_id=?', array($id));

		//Backup Contact-PurchaseOrder Relation
		$po_q = 'SELECT purchaseorderid FROM aicrm_purchaseorder WHERE contactid=?';
		$po_res = $this->db->pquery($po_q, array($id));
		if ($this->db->num_rows($po_res) > 0) {
			$po_ids_list = array();
			for($k=0;$k < $this->db->num_rows($po_res);$k++)
			{
				$po_ids_list[] = $this->db->query_result($po_res,$k,"purchaseorderid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'aicrm_purchaseorder', 'contactid', 'purchaseorderid', implode(",", $po_ids_list));
			$this->db->pquery('INSERT INTO aicrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//removing the relationship of contacts with PurchaseOrder
		$this->db->pquery('UPDATE aicrm_purchaseorder SET contactid=0 WHERE contactid=?', array($id));

		//Backup Contact-SalesOrder Relation
		$so_q = 'SELECT salesorderid FROM aicrm_salesorder WHERE contactid=?';
		$so_res = $this->db->pquery($so_q, array($id));
		if ($this->db->num_rows($so_res) > 0) {
			$so_ids_list = array();
			for($k=0;$k < $this->db->num_rows($so_res);$k++)
			{
				$so_ids_list[] = $this->db->query_result($so_res,$k,"salesorderid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'aicrm_salesorder', 'contactid', 'salesorderid', implode(",", $so_ids_list));
			$this->db->pquery('INSERT INTO aicrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//removing the relationship of contacts with SalesOrder
		$this->db->pquery('UPDATE aicrm_salesorder SET contactid=0 WHERE contactid=?', array($id));
		//Backup Contact-Quotes Relation
		$quo_q = 'SELECT quoteid FROM aicrm_quotes WHERE contactid=?';
		$quo_res = $this->db->pquery($quo_q, array($id));
		if ($this->db->num_rows($quo_res) > 0) {
			$quo_ids_list = array();
			for($k=0;$k < $this->db->num_rows($quo_res);$k++)
			{
				$quo_ids_list[] = $this->db->query_result($quo_res,$k,"quoteid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'aicrm_quotes', 'contactid', 'quoteid', implode(",", $quo_ids_list));
			$this->db->pquery('INSERT INTO aicrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//removing the relationship of contacts with Quotes
		$this->db->pquery('UPDATE aicrm_quotes SET contactid=0 WHERE contactid=?', array($id));
		//remove the portal info the contact
		$this->db->pquery('DELETE FROM aicrm_portalinfo WHERE id = ?', array($id));
		$this->db->pquery('UPDATE aicrm_customerdetails SET portal=0,support_start_date=NULL,support_end_date=NULl WHERE customerid=?', array($id));
		parent::unlinkDependencies($module, $id);
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		// echo $return_module; exit;
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Potentials') {
			$sql = 'DELETE FROM aicrm_contpotentialrel WHERE contactid=? AND potentialid=?';
			$this->db->pquery($sql, array($id, $return_id));
		}elseif($return_module == 'Campaigns') {
			$sql = 'DELETE FROM aicrm_campaigncontrel WHERE contactid=? AND campaignid=?';
			$this->db->pquery($sql, array($id, $return_id));
		}elseif($return_module == 'Products') {
			$sql = 'DELETE FROM aicrm_seproductsrel WHERE crmid=? AND productid=?';
			$this->db->pquery($sql, array($id, $return_id));
		}elseif($return_module == 'SmartSms') {
			$relation_query = 'DELETE FROM aicrm_smartsms_contactsrel WHERE contactid =? AND  smartsmsid=?';
			$this->db->pquery($relation_query, array($id, $return_id));
		}elseif($return_module == 'Smartquestionnaire') {
			$relation_query = 'DELETE FROM aicrm_smartquestionnaire_contactsrel WHERE contactid =? AND  smartquestionnaireid=?';
			$this->db->pquery($relation_query, array($id, $return_id));
		}elseif($return_module == 'Smartemail') {
			$relation_query = 'DELETE FROM aicrm_smartemail_contactsrel WHERE contactid =? AND  smartemailid=?';
			$this->db->pquery($relation_query, array($id, $return_id));
		}else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}


}

?>