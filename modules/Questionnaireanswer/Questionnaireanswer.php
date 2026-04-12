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

class Questionnaireanswer extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_questionnaireanswer";
	var $table_index= 'questionnaireanswerid';
	var $table_comment = "aicrm_questionnaireanswercomments";

	var $tab_name = Array('aicrm_crmentity','aicrm_questionnaireanswer','aicrm_questionnaireanswercf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_questionnaireanswer'=>'questionnaireanswerid','aicrm_questionnaireanswercf'=>'questionnaireanswerid','aicrm_questionnaireanswercomments'=>'questionnaireanswerid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_questionnaireanswercf', 'questionnaireanswerid');
	var $column_fields = Array();

	var $sortby_fields = Array('questionnaireanswerid','questionnaireanswer_name','smownerid','firstname','lastname');

	var $list_fields = Array(
		'หมายเลขแบบสอบถาม'=>Array('Questionnaireanswer'=>'questionnaireanswer_no'),
		'ชื่อแบบสอบถาม'=>Array('account'=>'questionnaireanswer_name'),
		'Assigned To'=>Array('Questionnaireanswer'=>'assigned_user_id'),
	);

	var $list_fields_name = Array(
		'หมายเลขแบบสอบถาม'=>'questionnaireanswer_no',
		'ชื่อแบบสอบถาม'=>'questionnaireanswer_name',
		'Assigned To'=>'assigned_user_id',
	);

	var $list_link_field= 'questionnaireanswer_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'desc';

	//var $groupTable = Array('aicrm_campaigngrouprelation','projects_id');
	var $search_fields = Array(
		'หมายเลขแบบสอบถาม'=>Array('aicrm_questionnaireanswer'=>'questionnaireanswer_no'),
		'ชื่อแบบสอบถาม'=>Array('aicrm_questionnaireanswer'=>'questionnaireanswer_name'),
	);

	var $search_fields_name = Array(
		'หมายเลขแบบสอบถาม'=>'questionnaireanswer_no',
		'ชื่อแบบสอบถาม'=>'questionnaireanswer_name',
	);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','questionnaireanswerid');
	
	function Questionnaireanswer() 
	{
		$this->log =LoggerManager::getLogger('Questionnaireanswer');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Questionnaireanswer');
	}
	function save_module()
	{
		global $adb;
		$this->insertIntoAttachment($this->id,'Questionnaireanswer');
		$this->insertIntoCommentTable("aicrm_questionnaireanswercomments",'questionnaireanswerid');
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
		$sql = "select * from aicrm_questionnaireanswercomments where questionnaireanswerid=? order by createdtime desc";
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
		if($module == 'Questionnaireanswer' && $_REQUEST['del_file_list'] != '')
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
	
	function getSortOrder()
	{
		global $log;
		$log->debug("Entering getSortOrder() method ...");	
		if(isset($_REQUEST['sorder'])) 
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['QUESTIONNAIREANSWER_SORT_ORDER'] != '')?($_SESSION['QUESTIONNAIREANSWER_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
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
			$order_by = (($_SESSION['QUESTIONNAIREANSWER_ORDER_BY'] != '')?($_SESSION['QUESTIONNAIREANSWER_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
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
		aicrm_account.*,
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
		LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_activitycf.accountid


		WHERE aicrm_account.accountid = ".$id."
		AND aicrm_crmentity.deleted = 0
		";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");

		return $return_value;
	}
	/*
	 * Function to get the secondary query part of a report 
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		//echo $module." && ".$secmodule; exit;
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_questionnaireanswer","questionnaireanswerid");
		// $query .=" left join aicrm_crmentity as aicrm_crmentityQuestionnaireanswer on aicrm_crmentityQuestionnaireanswer.crmid=aicrm_questionnaireanswer.questionnaireanswerid and aicrm_crmentityQuestionnaireanswer.deleted=0 
		// 		left join aicrm_questionnaireanswercf on aicrm_questionnaireanswercf.questionnaireanswerid = aicrm_crmentityQuestionnaireanswer.crmid 
		// 		left join aicrm_groups as aicrm_groupsQuestionnaireanswer on aicrm_groupsQuestionnaireanswer.groupid = aicrm_crmentityQuestionnaireanswer.smownerid
		// 		 LEFT JOIN aicrm_users as aicrm_usersCreatorQuestionnaireanswer ON aicrm_crmentity.smcreatorid = aicrm_usersCreatorQuestionnaireanswer.id
  //           LEFT JOIN aicrm_users as aicrm_usersQuestionnaireanswer ON aicrm_usersQuestionnaireanswer.id = aicrm_crmentity.smownerid
  //           LEFT JOIN aicrm_users as aicrm_usersModifiedQuestionnaireanswer ON aicrm_crmentity.modifiedby = aicrm_usersModifiedQuestionnaireanswer.id
  //           LEFT JOIN aicrm_account AS aicrm_accountQuestionnaireanswer ON aicrm_accountQuestionnaireanswer.accountid = aicrm_questionnaireanswer.accountid
  //           left join aicrm_branch as aicrm_branchQuestionnaireanswer on aicrm_branchQuestionnaireanswer.branchid = aicrm_questionnaireanswer.branchid
  //           left join aicrm_questionnaireanswertemplate as aicrm_questionnaireanswertemplateQuestionnaireanswer on aicrm_questionnaireanswertemplateQuestionnaireanswer.questionnaireanswertemplateid = aicrm_questionnaireanswer.questionnaireanswertemplateid
  //           left join aicrm_units as aicrm_unitsQuestionnaireanswer on aicrm_unitsQuestionnaireanswer.unitsid = aicrm_questionnaireanswer.unitsid";
		$query .=" 
		left join aicrm_crmentity as aicrm_crmentityQuestionnaireanswer on aicrm_crmentityQuestionnaireanswer.crmid=aicrm_questionnaireanswer.questionnaireanswerid and aicrm_crmentityQuestionnaireanswer.deleted=0 
		left join aicrm_questionnairetemplate on aicrm_questionnairetemplate.questionnairetemplateid = aicrm_questionnaireanswer.questionnairetemplateid
		left join aicrm_questionnaireanswercf on aicrm_questionnaireanswercf.questionnaireanswerid = aicrm_crmentityQuestionnaireanswer.crmid 
		left join aicrm_groups as aicrm_groupsQuestionnaireanswer on aicrm_groupsQuestionnaireanswer.groupid = aicrm_crmentityQuestionnaireanswer.smownerid


		left join aicrm_users as aicrm_usersQuestionnaireanswer on aicrm_usersQuestionnaireanswer.id = aicrm_crmentityQuestionnaireanswer.smownerid
		LEFT JOIN aicrm_users AS aicrm_usersCreatorQuestionnaireanswer ON aicrm_usersCreatorQuestionnaireanswer.id = aicrm_crmentityQuestionnaireanswer.smcreatorid
		LEFT JOIN aicrm_users AS aicrm_usersModifiedQuestionnaireanswer ON aicrm_usersModifiedQuestionnaireanswer.id = aicrm_crmentityQuestionnaireanswer.modifiedby

		";

		if($module == "Accounts" && $secmodule == "Questionnaireanswer"){
			$query .=" LEFT JOIN aicrm_account AS aicrm_accountQuestionnaireanswer ON aicrm_accountQuestionnaireanswer.accountid = aicrm_questionnaireanswer.accountid ";
		}

		// echo $query; exit;

		return $query;
	}

	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Questionnaireanswer", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name 
		FROM aicrm_crmentity
		INNER JOIN aicrm_questionnaireanswer ON aicrm_questionnaireanswer.questionnaireanswerid = aicrm_crmentity.crmid
		INNER JOIN aicrm_questionnaireanswercf ON aicrm_questionnaireanswercf.questionnaireanswerid = aicrm_questionnaireanswer.questionnaireanswerid
		LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
		LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
		LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
		";
		$where_auto = " aicrm_crmentity.deleted = 0 ";

		if($where != "")
			$query .= " WHERE ($where) AND ".$where_auto;
		else
			$query .= " WHERE ".$where_auto;

		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		//we should add security check when the user has Private Access
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[6] == 3)
		{
			//Added security check to get the permitted records only
			$query = $query." ".getListViewSecurityParameter("Questionnaireanswer");
		}
		$log->debug("Exiting create_export_query method ...");
		return $query;
	}
	/*
	 * Function to get the relation tables for related modules 
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (	
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_questionnaireanswer"=>"questionnaireanswerid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		
		
		$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
		$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
		$this->db->pquery($sql, $params);
		
	}

}

?>
