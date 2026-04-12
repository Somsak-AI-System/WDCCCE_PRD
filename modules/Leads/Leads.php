<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of txhe License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('data/SugarBean.php');
require_once('modules/Calendar/Activity.php');
require_once('modules/Campaigns/Campaigns.php');
require_once('modules/Documents/Documents.php');
require_once('modules/Emails/Emails.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');

class Leads extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "aicrm_leaddetails";
	var $table_index= 'leadid';
    var $table_comment = "aicrm_leaddetailscomments";

	var $tab_name = Array('aicrm_crmentity','aicrm_leaddetails','aicrm_leadsubdetails','aicrm_leadaddress','aicrm_leadscf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_leaddetails'=>'leadid','aicrm_leadsubdetails'=>'leadsubscriptionid','aicrm_leadaddress'=>'leadaddressid','aicrm_leadscf'=>'leadid','aicrm_leaddetailscomments'=>'leadid');
	var $entity_table = "aicrm_crmentity";
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_leadscf', 'leadid');

	//construct this from database;
	var $column_fields = Array();
	var $sortby_fields = Array('firstname','lastname','email','phone','company','smownerid','website');
	// This is used to retrieve related aicrm_fields from form posts.
	var $additional_column_fields = Array('smcreatorid', 'smownerid', 'contactid','potentialid' ,'crmid');

	// This is the list of aicrm_fields that are in the lists.
	var $list_fields = Array(
		'หมายเลขผู้มุ่งหวัง'=>Array('leaddetails'=>'lead_no'),
		'ชื่อ'=>Array('leaddetails'=>'firstname'),
		'นามสกุล'=>Array('leaddetails'=>'lastname'),
		'เบอร์มือถือ'=>Array('leaddetails'=>'mobile'),
		'อีเมล'=>Array('leaddetails'=>'email'),
	);
	var $list_fields_name = Array(
		'หมายเลขผู้มุ่งหวัง'=>'lead_no',
		'ชื่อ'=>'firstname',
		'นามสกุล'=>'lastname',
		'เบอร์มือถือ'=>'mobile',
		'อีเมล'=>'email',
	);
	var $list_link_field= 'lead_no';
	var $search_fields = Array(
		'หมายเลขผู้มุ่งหวัง'=>Array('aicrm_leaddetails'=>'lead_no'),
		'ชื่อ'=>Array('aicrm_leaddetails'=>'firstname'),
		'นามสกุล'=>Array('aicrm_leaddetails'=>'lastname'),
		'เบอร์มือถือ'=>Array('aicrm_leadaddress'=>'mobile'),
		'อีเมล'=>Array('aicrm_leaddetails'=>'email'),
	);
	var $search_fields_name = Array(
		'หมายเลขผู้มุ่งหวัง'=>'lead_no',
		'ชื่อ'=>'firstname',
		'นามสกุล'=>'lastname',
		'เบอร์มือถือ'=>'mobile',
		'อีเมล'=>'email',
	);
	var $required_fields =  array();
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id', 'lastname', 'createdtime' ,'modifiedtime');
	//Default Fields for Email Templates -- Pavani
	var $emailTemplate_defaultFields = array('firstname','lastname','leadsource','leadstatus','rating','industry','yahooid','email','annualrevenue','designation','salutation');
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'ASC';
	//var $groupTable = Array('aicrm_leadgrouprelation','leadid');
	function Leads()	{
		$this->log = LoggerManager::getLogger('lead');
		$this->log->debug("Entering Leads() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Leads');
		$this->log->debug("Exiting Lead method ...");
	}
	/** Function to handle module specific operations when saving a entity
	*/
	function save_module($module)
	{
		global $adb;
		$this->insertIntoAttachment($this->id,'Leads');
		$this->insertIntoCommentTable("aicrm_leaddetailscomments",'leadid');
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
		$sql = "select * from aicrm_leaddetailscomments where leadid=? order by createdtime desc";
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
		if($module == 'Leads' && $_REQUEST['del_file_list'] != '')
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
			$sorder = (($_SESSION['LEADS_SORT_ORDER'] != '')?($_SESSION['LEADS_SORT_ORDER']):($this->default_sort_order));

		$log->debug("Exiting getSortOrder method ...");

		return $sorder;
	}

	/**
	 * Function to get order by
	 * return string  $order_by    - fieldname(eg: 'leadname')
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
			$order_by = (($_SESSION['LEADS_ORDER_BY'] != '')?($_SESSION['LEADS_ORDER_BY']):($use_default_order_by));

		$log->debug("Exiting getOrderBy method ...");

		return $order_by;
	}
	// Mike Crowe Mod --------------------------------------------------------

	/** Function to export the lead records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Leads Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Leads", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
		FROM ".$this->entity_table."
		INNER JOIN aicrm_leaddetails ON aicrm_crmentity.crmid=aicrm_leaddetails.leadid
		INNER JOIN aicrm_leadsubdetails ON aicrm_leaddetails.leadid = aicrm_leadsubdetails.leadsubscriptionid
		INNER JOIN aicrm_leadaddress ON aicrm_leaddetails.leadid=aicrm_leadaddress.leadaddressid
		INNER JOIN aicrm_leadscf ON aicrm_leadscf.leadid=aicrm_leaddetails.leadid
		LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_leaddetails.accountid
		LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_leaddetails.contactid
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id and aicrm_users.status='Active'
		LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
		LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id ";
		/*
		LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_leaddetails.campaignid
		LEFT JOIN aicrm_deal ON aicrm_deal.dealid = aicrm_leaddetails.dealid
		LEFT JOIN aicrm_promotion ON aicrm_promotion.promotionid = aicrm_leaddetails.promotionid
		LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_leaddetails.contactid
		*/
		$query .= setFromQuery("Leads");

		$where_auto = " aicrm_crmentity.deleted=0 ";

		if($where != "")
			$query .= " where ($where) AND ".$where_auto;
		else
			$query .= " where ".$where_auto;

		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		//we should add security check when the user has Private Access
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[7] == 3)
		{
			//Added security check to get the permitted records only
			$query = $query." ".getListViewSecurityParameter("Leads");
		}

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}
	
	function get_deals($id, $cur_tab_id, $rel_tab_id, $actions=false) {

		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_deals(".$id.") method ...");
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
				" value='". getTranslatedString('LBL_ADD_NEW'). " Deal'>&nbsp;";
			}
		}

		$query = "SELECT distinct(aicrm_deal.dealid), case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name , aicrm_crmentity.*, aicrm_deal.*, aicrm_dealcf.*
		FROM aicrm_deal
		INNER JOIN aicrm_dealcf ON aicrm_dealcf.dealid = aicrm_deal.dealid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_deal.dealid
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
		LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
		WHERE aicrm_crmentity.deleted = 0 AND aicrm_deal.parentid = '".$id."' ";
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_deals method ...");
		return $return_value;

	}

	function get_smartsms($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
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

		if($actions) {
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

		$query = "SELECT aicrm_leaddetails.*,aicrm_leadscf .*, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_smartsms.*,aicrm_smartsmscf.*,
		case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
		FROM aicrm_smartsms_leadsrel 
		LEFT JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_smartsms_leadsrel.leadid
		LEFT JOIN aicrm_leadscf ON aicrm_leadscf.leadid = aicrm_leaddetails.leadid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms_leadsrel.smartsmsid
		LEFT JOIN aicrm_smartsms ON aicrm_smartsms.smartsmsid = aicrm_smartsms_leadsrel.smartsmsid
		LEFT JOIN aicrm_smartsmscf ON aicrm_smartsmscf.smartsmsid = aicrm_smartsms.smartsmsid
		LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
		WHERE aicrm_crmentity.deleted = 0 AND aicrm_smartsms_leadsrel.leadid = ".$id;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}
	
	function get_smartemail($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
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

		if($actions) {
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

		$query = "SELECT aicrm_leaddetails.*,aicrm_leadscf .*, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_smartemail.*,aicrm_smartemailcf.*,
		case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
		FROM aicrm_smartemail_leadsrel 
		LEFT JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_smartemail_leadsrel.leadid
		LEFT JOIN aicrm_leadscf ON aicrm_leadscf.leadid = aicrm_leaddetails.leadid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartemail_leadsrel.smartemailid
		LEFT JOIN aicrm_smartemail ON aicrm_smartemail.smartemailid = aicrm_smartemail_leadsrel.smartemailid
		LEFT JOIN aicrm_smartemailcf ON aicrm_smartemailcf.smartemailid = aicrm_smartemail.smartemailid
		LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
		WHERE aicrm_crmentity.deleted = 0 AND aicrm_smartemail_leadsrel.leadid = ".$id;
		
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");

		return $return_value;
	}

	function get_smartquestionnaire($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
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


		if($actions) {
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

		$query = "SELECT aicrm_leaddetails.*,aicrm_leadscf .*, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_smartquestionnaire.*,aicrm_smartquestionnairecf.*,
		case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
		FROM aicrm_smartquestionnaire_leadsrel 
		LEFT JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_smartquestionnaire_leadsrel.leadid
		LEFT JOIN aicrm_leadscf ON aicrm_leadscf.leadid = aicrm_leaddetails.leadid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartquestionnaire_leadsrel.smartquestionnaireid
		LEFT JOIN aicrm_smartquestionnaire ON aicrm_smartquestionnaire.smartquestionnaireid = aicrm_smartquestionnaire_leadsrel.smartquestionnaireid
		LEFT JOIN aicrm_smartquestionnairecf ON aicrm_smartquestionnairecf.smartquestionnaireid = aicrm_smartquestionnaire.smartquestionnaireid
		LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
		WHERE aicrm_crmentity.deleted = 0 AND aicrm_smartquestionnaire_leadsrel.leadid = ".$id;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}

	/** Returns a list of the associated Campaigns
	  * @param $id -- campaign id :: Type Integer
	  * @returns list of campaigns in array format
	  */
	function get_campaigns($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_campaigns(".$id.") method ...");
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
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
				" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
				" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name ,
		aicrm_campaign.* ,aicrm_crmentity.crmid, aicrm_crmentity.smownerid,aicrm_crmentity.modifiedtime 
		from aicrm_campaign
		inner join aicrm_campaignleadrel on aicrm_campaignleadrel.campaignid=aicrm_campaign.campaignid
		inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_campaign.campaignid
		left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
		left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid
		where aicrm_campaignleadrel.leadid=".$id." and aicrm_crmentity.deleted=0";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_campaigns method ...");
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
				" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_VISIT', $related_module) ."'>";
			}
		}

		$query = "SELECT aicrm_activity.*, aicrm_activitycf.*,
		aicrm_leaddetails.*,
		aicrm_crmentity.crmid, aicrm_crmentity.smownerid,
		aicrm_crmentity.modifiedtime,
		aicrm_crmentity.*,
		case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
		FROM aicrm_activity
		left join aicrm_activitycf on aicrm_activity.activityid = aicrm_activitycf.activityid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
		LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		INNER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_activity.parentid
		WHERE aicrm_leaddetails.leadid = ".$id."
		AND aicrm_crmentity.deleted = 0 ";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");

		return $return_value;
	}

	/**
	* Function to get lead related Products 
	* @param  integer   $id      - leadid
	* returns related Products record in array format
	*/
	function get_products($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_products(".$id.") method ...");
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
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
				" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
				" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		} 

		$query = "SELECT aicrm_products.*, aicrm_crmentity.crmid, aicrm_crmentity.smownerid 
		FROM aicrm_products 
		INNER JOIN aicrm_seproductsrel ON aicrm_products.productid = aicrm_seproductsrel.productid and aicrm_seproductsrel.setype = 'Leads'
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid 
		INNER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_seproductsrel.crmid  
		WHERE aicrm_crmentity.deleted = 0 AND aicrm_leaddetails.leadid = $id";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;
		
		$log->debug("Exiting get_products method ...");		
		return $return_value;
	}

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

		if($actions) {
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

		$query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
			aicrm_crmentity.*, aicrm_quotes.*, aicrm_quotescf.*
			FROM aicrm_quotes
			inner join aicrm_quotescf on aicrm_quotescf.quoteid = aicrm_quotes.quoteid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
			INNER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_quotes.parentid
			LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_quotes.parentid = ".$id;
		
		//echo $query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}
	/** Function to get the Combo List Values of Leads Field
	 * @param string $list_option
	 * Returns Combo List Options
	*/
	function get_lead_field_options($list_option)
	{
		global $log;
		$log->debug("Entering get_lead_field_options(".$list_option.") method ...");
		$comboFieldArray = getComboArray($this->combofieldNames);
		$log->debug("Exiting get_lead_field_options method ...");
		return $comboFieldArray[$list_option];
	}

	/** Function to get the Columnnames of the Leads Record
	* Used By vtigerCRM Word Plugin
	* Returns the Merge Fields for Word Plugin
	*/
	function getColumnNames_Lead()
	{
		global $log,$current_user;
		$log->debug("Entering getColumnNames_Lead() method ...");
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql1 = "select fieldlabel from aicrm_field where tabid=7 and aicrm_field.presence in (0,2)";
			$params1 = array();
		}else
		{
			$profileList = getCurrentUserProfileList();
			$sql1 = "select aicrm_field.fieldid,fieldlabel from aicrm_field inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid where aicrm_field.tabid=7 and aicrm_field.displaytype in (1,2,3,4) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_field.presence in (0,2)";
			$params1 = array();
			if (count($profileList) > 0) {
				$sql1 .= " and aicrm_profile2field.profileid in (". generateQuestionMarks($profileList) .")  group by fieldid";
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
		$log->debug("Exiting getColumnNames_Lead method ...");
		return $mergeflds;
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

		$rel_table_arr = Array("Activities"=>"aicrm_seactivityrel","Documents"=>"aicrm_senotesrel","Attachments"=>"aicrm_seattachmentsrel",
			"Products"=>"aicrm_seproductsrel","Campaigns"=>"aicrm_campaignleadrel");

		$tbl_field_arr = Array("aicrm_seactivityrel"=>"activityid","aicrm_senotesrel"=>"notesid","aicrm_seattachmentsrel"=>"attachmentsid",
			"aicrm_seproductsrel"=>"productid","aicrm_campaignleadrel"=>"campaignid");

		$entity_tbl_field_arr = Array("aicrm_seactivityrel"=>"crmid","aicrm_senotesrel"=>"crmid","aicrm_seattachmentsrel"=>"crmid",
			"aicrm_seproductsrel"=>"crmid","aicrm_campaignleadrel"=>"leadid");

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
		// echo $module ." && ".  $secmodule; exit;
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_leaddetails","leadid");
		//-- left join aicrm_contactdetails as aicrm_contactdetailsLeads on aicrm_contactdetailsLeads.contactid=aicrm_leaddetails.contactid
		$query .= " 
		left join aicrm_leadscf on aicrm_leadscf.leadid = aicrm_leaddetails.leadid
		left join aicrm_crmentity as aicrm_crmentityLeads on aicrm_crmentityLeads.crmid = aicrm_leaddetails.leadid and aicrm_crmentityLeads.deleted=0
		left join aicrm_leadaddress on aicrm_leaddetails.leadid = aicrm_leadaddress.leadaddressid
		left join aicrm_leadsubdetails on aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid
		left join aicrm_groups as aicrm_groupsLeads on aicrm_groupsLeads.groupid = aicrm_crmentityLeads.smownerid

		LEFT JOIN aicrm_users as aicrm_usersCreatorLeads on aicrm_usersCreatorLeads.id = aicrm_crmentity.smcreatorid
		LEFT JOIN aicrm_users as aicrm_usersModifiedLeads on aicrm_usersModifiedLeads.id = aicrm_crmentity.modifiedby
		left join aicrm_users as aicrm_usersLeads on aicrm_usersLeads.id = aicrm_crmentityLeads.smownerid 
		left join aicrm_account as aicrm_accountLeads on aicrm_accountLeads.accountid=aicrm_leaddetails.accountid
		";

		// if($module == "Accounts" && $secmodule="Leads"){
		// 	$query .= " left join aicrm_account as aicrm_accountLeads on aicrm_accountLeads.accountid=aicrm_leaddetails.accountid";
		// }
		// if($module == "SmartSms" && $secmodule="Leads"){
		// 	$query .= " left join aicrm_account as aicrm_accountLeads on aicrm_accountLeads.accountid=aicrm_leaddetails.accountid";
		// }

		return $query;
	}

	/*
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		// echo $secmodule; exit;
		$rel_tables = array (
			"Calendar" => array("aicrm_seactivityrel"=>array("crmid","activityid"),"aicrm_leaddetails"=>"leadid"),
			"Products" => array("aicrm_seproductsrel"=>array("crmid","productid"),"aicrm_leaddetails"=>"leadid"),
			"Campaigns" => array("aicrm_campaignleadrel"=>array("leadid","campaignid"),"aicrm_leaddetails"=>"leadid"),
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_leaddetails"=>"leadid"),
			"Services" => array("aicrm_crmentityrel"=>array("crmid","relcrmid"),"aicrm_leaddetails"=>"leadid"),
			"Accounts" => array("aicrm_account"=>array("accountid","accountid"),"aicrm_leaddetails"=>"accountid"),
			"Contacts" => array("aicrm_contactdetails"=>array("contactid","contactid"),"aicrm_leaddetails"=>"contactid"),
			"Deal" => array("aicrm_deal"=>array("parentid","dealid"),"aicrm_leaddetails"=>"leadid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'SmartSms') {
			$relation_query = 'DELETE FROM aicrm_smartsms_leadsrel WHERE leadid =? AND  smartsmsid=?';
			$this->db->pquery($relation_query, array($id, $return_id));
		} elseif($return_module == 'Smartemail') {
			$relation_query = 'DELETE FROM aicrm_smartemail_leadsrel WHERE leadid =? AND  smartemailid=?';
			$this->db->pquery($relation_query, array($id, $return_id));
			$relationrel_query = 'DELETE FROM aicrm_crmentityrel WHERE  relcrmid=? AND crmid=?';
			$this->db->pquery($relationrel_query, array($id, $return_id));
		} elseif($return_module == 'Smartquestionnaire') {
			$relation_query = 'DELETE FROM aicrm_smartquestionnaire_leadsrel WHERE leadid =? AND  smartquestionnaireid=?';
			$this->db->pquery($relation_query, array($id, $return_id));
			$relationrel_query = 'DELETE FROM aicrm_crmentityrel WHERE  relcrmid=? AND crmid=?';
			$this->db->pquery($relationrel_query, array($id, $return_id));
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}
	//End
	
}

?>