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
require_once('include/utils/utils.php');
require_once('modules/Contacts/Contacts.php');
require_once('modules/Leads/Leads.php');
require_once('user_privileges/default_module_view.php');

class Campaigns extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "aicrm_campaign";
	var $table_index= 'campaignid';
	var $table_comment = "aicrm_campaigncomments";

	var $tab_name = Array('aicrm_crmentity','aicrm_campaign','aicrm_campaignscf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_campaign'=>'campaignid','aicrm_campaignscf'=>'campaignid','aicrm_campaigncomments'=>'campaignid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_campaignscf', 'campaignid');
	var $column_fields = Array();

	var $sortby_fields = Array('campaignname','smownerid','campaignstype','productname','expectedrevenue','closingdate','campaignstatus','expectedresponse','targetaudience','expectedcost');

	var $list_fields = Array(
		'หมายเลขแคมเปญ'=>Array('aicrm_campaign'=>'campaign_no'),
		'ชื่อแคมเปญ'=>Array('aicrm_campaign'=>'campaignname'),
		'วันที่เริ่มแคมเปญ'=>Array('aicrm_campaign'=>'startdate'),
		'วันที่สิ้นสุดแคมแปญ'=>Array('aicrm_campaign'=>'enddate')
	);

	var $list_fields_name = Array(
		'หมายเลขแคมเปญ'=>'campaign_no',
		'ชื่อแคมเปญ'=>'campaignname',
		'วันที่เริ่มแคมเปญ'=>'startdate',
		'วันที่สิ้นสุดแคมแปญ'=>'enddate',
    );	  			

	var $list_link_field= 'campaignname';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';

	//var $groupTable = Array('aicrm_campaigngrouprelation','campaignid');

	var $search_fields = Array(
		'หมายเลขแคมเปญ'=>Array('aicrm_campaign'=>'campaign_no'),
		'ชื่อแคมเปญ'=>Array('aicrm_campaign'=>'campaignname'),
		'วันที่เริ่มแคมเปญ'=>Array('aicrm_campaign'=>'startdate'),
		'วันที่สิ้นสุดแคมแปญ'=>Array('aicrm_campaign'=>'enddate'),
	);

	var $search_fields_name = Array(
		'หมายเลขแคมเปญ'=>'campaign_no',
		'ชื่อแคมเปญ'=>'campaignname',
		'วันที่เริ่มแคมเปญ'=>'startdate',
		'วันที่สิ้นสุดแคมแปญ'=>'enddate',
	);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('campaignname','createdtime' ,'modifiedtime','assigned_user_id');
	
	function Campaigns() 
	{
		$this->log =LoggerManager::getLogger('campaign');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Campaigns');
	}

	/** Function to handle module specific operations when saving a entity 
	*/
	function save_module($module)
	{
		global $adb;
		$this->insertIntoCommentTable("aicrm_campaigncomments",'campaignid');
		$this->insertIntoAttachment($this->id,'Campaigns');
		save_promotion_tab($this, 'Campaigns');	
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
		$sql = "select * from aicrm_campaigncomments where campaignid=? order by createdtime desc";
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
		if($module == 'Campaigns' && $_REQUEST['del_file_list'] != '')
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
			$sorder = (($_SESSION['CAMPAIGN_SORT_ORDER'] != '')?($_SESSION['CAMPAIGN_SORT_ORDER']):($this->default_sort_order));

		$log->debug("Exiting getSortOrder method ...");
		return $sorder;
	}

	/**
	 * Function to get order by
	 * return string  $order_by    - fieldname(eg: 'campaignname')
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
			$order_by = (($_SESSION['CAMPAIGN_ORDER_BY'] != '')?($_SESSION['CAMPAIGN_ORDER_BY']):($use_default_order_by));

		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}
	// Mike Crowe Mod --------------------------------------------------------

	
	function get_promotion($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view, $currentModule;
        $log->debug("Entering get_promotion(".$id.") method ...");
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
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=900,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}
		$query = "SELECT aicrm_promotion.*,aicrm_promotioncf.*, aicrm_crmentity.crmid,
					CASE when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, 
					aicrm_crmentity.smownerid FROM aicrm_promotion  
					INNER JOIN aicrm_promotioncf ON aicrm_promotioncf.promotionid=aicrm_promotion.promotionid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
					LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
					LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
					WHERE aicrm_crmentity.deleted=0 AND aicrm_promotion.campaignid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;
		
		$log->debug("Exiting get_promotion method ...");		
		return $return_value;

	}

	/**
	 * Function to get Campaign related Leads
	 * @param  integer   $id      - campaignid
	 * returns related Leads record in array format
	 */
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
		$lhtml = "<select id='lead_cv_list' class='small'><option value='None'>-- ".getTranslatedString('Select One')." --</option>";
		$oCustomView = new CustomView($related_module);
		$viewid = $oCustomView->getViewId($related_module);
		$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid, false);
		$lhtml .= $customviewcombo_html;
		$lhtml .= "</select>";
		/* To get Leads CustomView -END */
		
		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=900,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}
		$query = "SELECT aicrm_leaddetails.*,aicrm_leadscf.*, aicrm_crmentity.crmid,
					CASE when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, 
					aicrm_crmentity.smownerid FROM aicrm_leaddetails      
					INNER JOIN aicrm_campaignleadrel ON aicrm_campaignleadrel.leadid=aicrm_leaddetails.leadid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
					INNER JOIN aicrm_leadsubdetails  ON aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid 			
					INNER JOIN aicrm_leadaddress ON aicrm_leadaddress.leadaddressid = aicrm_leadsubdetails.leadsubscriptionid
					LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
					LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
					LEFT JOIN aicrm_leadscf ON aicrm_leadscf.leadid=aicrm_leaddetails.leadid
					WHERE aicrm_crmentity.deleted=0 AND aicrm_campaignleadrel.campaignid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;
		
		$log->debug("Exiting get_leads method ...");		
		return $return_value;
	}
		
	/*
	 * Function to get the secondary query part of a report 
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		// aicrm_campaignPromotion
		// echo $module." && ".$secmodule; exit;
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_campaign","campaignid");
		$query .=" left join aicrm_crmentity as aicrm_crmentityCampaigns on aicrm_crmentityCampaigns.crmid=aicrm_campaign.campaignid and aicrm_crmentityCampaigns.deleted=0 
				left join aicrm_campaignscf on aicrm_campaignscf.campaignid = aicrm_crmentityCampaigns.crmid 
				left join aicrm_groups as aicrm_groupsCampaigns on aicrm_groupsCampaigns.groupid = aicrm_crmentityCampaigns.smownerid
				left join aicrm_users as aicrm_usersCampaigns on aicrm_usersCampaigns.id = aicrm_crmentityCampaigns.smownerid
				left join aicrm_users as aicrm_usersModifiedCampaigns on aicrm_crmentity.smcreatorid=aicrm_usersModifiedCampaigns.id
                left join aicrm_users as aicrm_usersCreatorCampaigns on aicrm_crmentity.smcreatorid=aicrm_usersCreatorCampaigns.id
				";

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
			"Contacts" => array("aicrm_campaigncontrel"=>array("campaignid","contactid"),"aicrm_campaign"=>"campaignid"),
			"Leads" => array("aicrm_campaignleadrel"=>array("campaignid","leadid"),"aicrm_campaign"=>"campaignid"),
			"Accounts" => array("aicrm_campaign_account"=>array("campaignid","accountid"),"aicrm_campaign"=>"campaignid"),
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_campaign"=>"campaignid"),
            "Calendar" => array("aicrm_seactivityrel"=>array("crmid","activityid"),"aicrm_campaign"=>"campaignid"),
            "Promotion" => array("aicrm_promotion"=>array("campaignid","promotionid"),"aicrm_campaign"=>"campaignid"),
		);
		return $rel_tables[$secmodule];
	}

    function create_export_query($where)
    {
        global $log;
        global $current_user;
        $log->debug("Entering create_export_query(".$where.") method ...");

        include("include/utils/ExportUtils.php");

        //To get the Permitted fields query and the permitted fields list
        $sql = getPermittedFieldsQuery("Campaigns", "detail_view");
        $fields_list = getFieldsListFromQuery($sql);
        $query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
	       			FROM aicrm_crmentity
				INNER JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_crmentity.crmid
				INNER JOIN aicrm_campaignscf ON aicrm_campaignscf.campaignid = aicrm_campaign.campaignid
				LEFT JOIN aicrm_promotion ON aicrm_promotion.promotionid = aicrm_campaign.promotionid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
				LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
				";
        $query .= setFromQuery("Campaigns");
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
            $query = $query." ".getListViewSecurityParameter("Campaigns");
        }
        $log->debug("Exiting create_export_query method ...");
        return $query;
    }

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		
		/*if($return_module == 'Leads') {
			$sql = 'DELETE FROM aicrm_campaignleadrel WHERE campaignid=? AND leadid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Contacts') {
			$sql = 'DELETE FROM aicrm_campaigncontrel WHERE campaignid=? AND contactid=?';
			$this->db->pquery($sql, array($id, $return_id));
		}elseif($return_module == 'Accounts') {
			$sql = 'DELETE FROM aicrm_campaign_account WHERE campaignid=? AND accountid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} else {*/
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		/*}*/
	}

}
?>