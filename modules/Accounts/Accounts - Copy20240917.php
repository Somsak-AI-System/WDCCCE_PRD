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
 * $Header: /advent/projects/wesat/aicrm_crm/sugarcrm/modules/Accounts/Accounts.php,v 1.53 2005/04/28 08:06:45 rank Exp $
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contacts.php');
require_once('modules/Potentials/Potentials.php');
require_once('modules/Calendar/Activity.php');
require_once('modules/Documents/Documents.php');
require_once('modules/Emails/Emails.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');

// Account is used to store aicrm_account information.
class Accounts extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "aicrm_account";
	var $table_index= 'accountid';
	var $table_comment = "aicrm_accountcomments";
	var $tab_name = Array('aicrm_crmentity','aicrm_account','aicrm_accountbillads','aicrm_accountshipads','aicrm_accountscf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_account'=>'accountid','aicrm_accountbillads'=>'accountaddressid','aicrm_accountshipads'=>'accountaddressid','aicrm_accountscf'=>'accountid','aicrm_accountcomments'=>'accountid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_accountscf', 'accountid');
	var $entity_table = "aicrm_crmentity";

	var $column_fields = Array();

	var $sortby_fields = Array('account_no','accountname','parentid');

	//var $groupTable = Array('aicrm_accountgrouprelation','accountid');
	// This is the list of aicrm_fields that are in the lists.
	var $list_fields = Array(
		'หมายเลขลูกค้า'=>Array('aicrm_account'=>'account_no'),
		'ชื่อลูกค้า'=>Array('aicrm_account'=>'accountname'),
		'เบอร์มือถือ'=>Array('aicrm_account'=>'mobile'),
		'อีเมล'=>Array('aicrm_account'=>'email1'),
	);

	var $list_fields_name = Array(
		'หมายเลขลูกค้า'=>'account_no',
		'ชื่อลูกค้า'=>'accountname',
		'เบอร์มือถือ'=>'mobile',
		'อีเมล'=>'email1',
	);

	var $list_link_field= 'accountname';

	var $search_fields = Array(
		'หมายเลขลูกค้า'=>Array('aicrm_account'=>'account_no'),
		'ชื่อลูกค้า'=>Array('aicrm_account'=>'accountname'),
		'เบอร์มือถือ'=>Array('aicrm_account'=>'mobile'),
		'อีเมล'=>Array('aicrm_account'=>'email1'),
	);

	var $search_fields_name = Array(
		'หมายเลขลูกค้า'=>'account_no',
		'ชื่อลูกค้า'=>'accountname',
		'เบอร์มือถือ'=>'mobile',
		'อีเมล'=>'email1',
	);
	// This is the list of aicrm_fields that are required
	var $required_fields =  array();

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id', 'createdtime', 'modifiedtime', 'accountname');

	//Default Fields for Email Templates -- Pavani
	var $emailTemplate_defaultFields = array('accountname','account_type','industry','annualrevenue','phone','email1','rating','website','fax');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'accountname';
	var $default_sort_order = 'ASC';

	function Accounts() {
		$this->log =LoggerManager::getLogger('account');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Accounts');
	}

	/** Function to handle module specific operations when saving a entity
	*/
	function save_module($module) {
		global $adb, $log;
		$this->insertIntoAttachment($this->id,$module);
		$this->insertIntoCommentTable("aicrm_accountcomments",'accountid');
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
		$sql = "select * from aicrm_accountcomments where accountid=? order by createdtime desc";
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
		if($module == 'Accounts' && $_REQUEST['del_file_list'] != '')
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
			$sorder = (($_SESSION['ACCOUNTS_SORT_ORDER'] != '')?($_SESSION['ACCOUNTS_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}
	/**
	 * Function to get order by
	 * return string  $order_by    - fieldname(eg: 'accountname')
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
			$order_by = (($_SESSION['ACCOUNTS_ORDER_BY'] != '')?($_SESSION['ACCOUNTS_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}
	// Mike Crowe Mod --------------------------------------------------------
	function get_contacts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
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

		$query = "SELECT aicrm_contactdetails.*,
			aicrm_contactscf .*,
			aicrm_contactsubdetails.*,
			aicrm_crmentity.crmid,
            aicrm_crmentity.smownerid,
			aicrm_account.accountname,
			case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
			FROM aicrm_contactdetails
			LEFT JOIN aicrm_contactscf ON aicrm_contactscf.contactid = aicrm_contactdetails.contactid
			LEFT JOIN aicrm_contactsubdetails ON aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_contactdetails.accountid
			LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0 AND aicrm_contactdetails.accountid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);
		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}
	
	function get_salesorder($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view, $currentModule;
        $log->debug("Entering get_salesorder(".$id.") method ...");
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
		$query = "SELECT aicrm_salesorder.*,aicrm_salesordercf.*, aicrm_crmentity.crmid,
					CASE when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, 
					aicrm_crmentity.smownerid FROM aicrm_salesorder  
					INNER JOIN aicrm_promotioncf ON aicrm_promotioncf.salesorderid=aicrm_salesorder.salesorderid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_salesorder.salesorderid
					LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
					LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
					WHERE aicrm_crmentity.deleted=0 AND aicrm_salesorder.accountid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;
		
		$log->debug("Exiting get_salesorder method ...");		
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

		$query = "SELECT aicrm_account.*,aicrm_accountscf.*, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_smartsms.*,aicrm_smartsmscf.*,
			case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
			FROM aicrm_smartsms_accountsrel 
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_smartsms_accountsrel.accountid
			LEFT JOIN aicrm_accountscf ON aicrm_accountscf.accountid = aicrm_account.accountid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms_accountsrel.smartsmsid
			LEFT JOIN aicrm_smartsms ON aicrm_smartsms.smartsmsid = aicrm_smartsms_accountsrel.smartsmsid
			LEFT JOIN aicrm_smartsmscf ON aicrm_smartsmscf.smartsmsid = aicrm_smartsms.smartsmsid
			LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0 AND aicrm_smartsms_accountsrel.accountid = ".$id;

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

		$query = "SELECT aicrm_account.*,aicrm_accountscf .*, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_smartemail.*,aicrm_smartemailcf.*,
			case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
			FROM aicrm_smartemail_accountsrel 
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_smartemail_accountsrel.accountid
			LEFT JOIN aicrm_accountscf ON aicrm_accountscf.accountid = aicrm_account.accountid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartemail_accountsrel.smartemailid
			LEFT JOIN aicrm_smartemail ON aicrm_smartemail.smartemailid = aicrm_smartemail_accountsrel.smartemailid
			LEFT JOIN aicrm_smartemailcf ON aicrm_smartemailcf.smartemailid = aicrm_smartemail.smartemailid
			LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0 AND aicrm_smartemail_accountsrel.accountid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
			$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}

	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	 */
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

		$query = "SELECT
			aicrm_projects.*,
			aicrm_projectscf.*,
			aicrm_crmentity.crmid,
			aicrm_account.*,
			aicrm_contactdetails.*,
			aicrm_users.* 
		FROM
			aicrm_projects
			LEFT JOIN aicrm_projectscf ON aicrm_projectscf.projectsid = aicrm_projects.projectsid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_projects.projectsid
			LEFT JOIN aicrm_account ON ( 
				aicrm_account.accountid = aicrm_projects.account_id1 OR
				aicrm_account.accountid = aicrm_projects.account_id2 OR
				aicrm_account.accountid = aicrm_projects.account_id3 OR
				aicrm_account.accountid = aicrm_projects.account_id4 OR
				aicrm_account.accountid = aicrm_projects.account_id5 OR
				aicrm_account.accountid = aicrm_projects.account_id6 OR
				aicrm_account.accountid = aicrm_projects.account_id7 OR
				aicrm_account.accountid = aicrm_projects.account_id8 OR
				aicrm_account.accountid = aicrm_projects.account_id9 OR
				aicrm_account.accountid = aicrm_projects.account_id10 OR
				aicrm_account.accountid = aicrm_projects.account_id11 OR
				aicrm_account.accountid = aicrm_projects.account_id12 OR
				aicrm_account.accountid = aicrm_projects.account_id13 OR
				aicrm_account.accountid = aicrm_projects.account_id14 OR
				aicrm_account.accountid = aicrm_projects.account_id15 OR
				aicrm_account.accountid = aicrm_projects.account_id16 OR
				aicrm_account.accountid = aicrm_projects.account_id17 OR
				aicrm_account.accountid = aicrm_projects.account_id18 OR
				aicrm_account.accountid = aicrm_projects.account_id19 OR
				aicrm_account.accountid = aicrm_projects.account_id20 OR
				aicrm_account.accountid = aicrm_projects.account_id21 OR
				aicrm_account.accountid = aicrm_projects.account_id22 OR
				aicrm_account.accountid = aicrm_projects.account_id23 OR
				aicrm_account.accountid = aicrm_projects.account_id24 OR
				aicrm_account.accountid = aicrm_projects.account_id25 OR
				aicrm_account.accountid = aicrm_projects.account_id26
			)
			LEFT OUTER JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_projects.contactid
			LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id 
		WHERE
			aicrm_crmentity.deleted = 0 
			AND (
				aicrm_projects.account_id1 = '".$id."' OR
				aicrm_projects.account_id2 = '".$id."' OR
				aicrm_projects.account_id3 = '".$id."' OR
				aicrm_projects.account_id4 = '".$id."' OR
				aicrm_projects.account_id5 = '".$id."' OR
				aicrm_projects.account_id6 = '".$id."' OR
				aicrm_projects.account_id7 = '".$id."' OR
				aicrm_projects.account_id8 = '".$id."' OR
				aicrm_projects.account_id9 = '".$id."' OR
				aicrm_projects.account_id10 = '".$id."' OR
				aicrm_projects.account_id11 = '".$id."' OR
				aicrm_projects.account_id12 = '".$id."' OR
				aicrm_projects.account_id13 = '".$id."' OR
				aicrm_projects.account_id14 = '".$id."' OR
				aicrm_projects.account_id15 = '".$id."' OR
				aicrm_projects.account_id16 = '".$id."' OR
				aicrm_projects.account_id17 = '".$id."' OR
				aicrm_projects.account_id18 = '".$id."' OR
				aicrm_projects.account_id19 = '".$id."' OR
				aicrm_projects.account_id20 = '".$id."' OR
				aicrm_projects.account_id21 = '".$id."' OR
				aicrm_projects.account_id22 = '".$id."' OR
				aicrm_projects.account_id23 = '".$id."' OR
				aicrm_projects.account_id24 = '".$id."' OR
				aicrm_projects.account_id25 = '".$id."' OR
				aicrm_projects.account_id26 = '".$id."'
			)";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_projects method ...");
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
			INNER JOIN aicrm_account ON aicrm_account.accountid = aicrm_activity.parentid
			WHERE aicrm_account.accountid = ".$id."
			AND aicrm_crmentity.deleted = 0 ";
	
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");

		return $return_value;
	}

	/**
	 * Function to get Account related Task & Event which have activity type Held, Completed or Deferred.
 	 * @param  integer   $id      - accountid
 	 * returns related Task or Event record in array format
 	 */
	function get_history($id)
	{
		global $log;
        $log->debug("Entering get_history(".$id.") method ...");
		$query = "SELECT aicrm_activity.activityid, aicrm_activity.subject,
			aicrm_activity.status, aicrm_activity.eventstatus,
			aicrm_activity.activitytype, aicrm_activity.date_start, aicrm_activity.due_date,
			aicrm_activity.time_start, aicrm_activity.time_end,
			aicrm_crmentity.modifiedtime, aicrm_crmentity.createdtime,
			aicrm_crmentity.description,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
			FROM aicrm_activity
			INNER JOIN aicrm_seactivityrel ON aicrm_seactivityrel.activityid = aicrm_activity.activityid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
			LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE (aicrm_activity.activitytype = 'Meeting'
				OR aicrm_activity.activitytype = 'Call'
				OR aicrm_activity.activitytype = 'Task')
			AND (aicrm_activity.status = 'Completed'
				OR aicrm_activity.status = 'Deferred'
				OR (aicrm_activity.eventstatus = 'Held'
					AND aicrm_activity.eventstatus != ''))
			AND aicrm_seactivityrel.crmid = ".$id."
			AND aicrm_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php
		$log->debug("Exiting get_history method ...");
		return getHistory('Accounts',$query,$id);
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

		$query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
			aicrm_activity.activityid, aicrm_activity.subject,
			aicrm_activity.activitytype, aicrm_crmentity.modifiedtime,
			aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_activity.date_start, aicrm_seactivityrel.crmid as parent_id
			FROM aicrm_activity, aicrm_seactivityrel, aicrm_account, aicrm_users, aicrm_crmentity
			LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
			WHERE aicrm_seactivityrel.activityid = aicrm_activity.activityid
				AND aicrm_account.accountid = aicrm_seactivityrel.crmid
				AND aicrm_users.id=aicrm_crmentity.smownerid
				AND aicrm_crmentity.crmid = aicrm_activity.activityid
				AND aicrm_account.accountid = ".$id."
				AND aicrm_activity.activitytype='Emails'
				AND aicrm_crmentity.deleted = 0";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_emails method ...");
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
			aicrm_crmentity.*, aicrm_quotes.*, aicrm_quotescf.*, aicrm_account.accountname
			FROM aicrm_quotes
			inner join aicrm_quotescf on aicrm_quotescf.quoteid = aicrm_quotes.quoteid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_quotes.accountid
			
			LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_account.accountid = ".$id;
		//, aicrm_contactdetails.*
		//LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_quotes.contactid

		// echo $query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
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
                  WHERE aicrm_crmentity.deleted = 0 AND aicrm_order.accountid = " . $id;
        
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);
        if ($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_order method ...");
        return $return_value;
    }

	function get_job_list($id, $cur_tab_id, $rel_tab_id, $actions = false)
    {
        global $log, $singlepane_view, $currentModule, $current_user;
        $log->debug("Entering get_job_list(" . $id . ") method ...");
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

        $query = "SELECT aicrm_jobs.*, aicrm_jobscf.*, aicrm_crmentity.crmid,
	              aicrm_crmentity.smownerid,
	              aicrm_account.accountname,
	              CASE
                  WHEN ( aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name
                  ELSE aicrm_groups.groupname END AS user_name FROM aicrm_jobs
                  LEFT JOIN aicrm_jobscf ON aicrm_jobs.jobid = aicrm_jobscf.jobid
                  INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid
                  LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_jobs.accountid
                  LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                  LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                  WHERE aicrm_crmentity.deleted = 0 AND aicrm_jobs.accountid = " . $id;
        
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);
        if ($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_contacts method ...");
        return $return_value;
    }

    function get_servicerequest($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_servicerequest(".$id.") method ...");
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
                    " value='". getTranslatedString('LBL_ADD_NEW')." " . getTranslatedString($singular_modname) . "'>&nbsp;";
            }
        }

        $query = "SELECT  aicrm_users.user_name,
			aicrm_crmentity.*,
			aicrm_servicerequest.*,
			aicrm_servicerequestcf.*,
			aicrm_account.*
			FROM aicrm_servicerequest
			LEFT JOIN aicrm_servicerequestcf ON aicrm_servicerequestcf.servicerequestid = aicrm_servicerequest.servicerequestid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_servicerequest.servicerequestid
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_servicerequest.accountid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_servicerequest.accountid = '".$id."'
			";
        //echo $query."<br>";
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_servicerequest method ...");
        return $return_value;

    }

    function get_helpdesk($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_helpdesk(".$id.") method ...");
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
                    " value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) . "'>&nbsp;";
            }
        }

        $query = "SELECT  aicrm_users.user_name,
			aicrm_crmentity.*,
			aicrm_troubletickets.*,
			aicrm_ticketcf.*,
			aicrm_account.*
			FROM aicrm_troubletickets
			LEFT JOIN aicrm_ticketcf ON aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_troubletickets.ticketid
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_troubletickets.accountid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_troubletickets.accountid = '".$id."'
			";
        //echo $query."<br>";
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_helpdesk method ...");
        return $return_value;

    }

    function get_serial($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_serial(".$id.") method ...");
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
                    " value='". getTranslatedString('LBL_ADD_NEW'). " Serial'>&nbsp;";
            }
        }

        $query = "SELECT  aicrm_users.user_name,aicrm_products.productname,
			aicrm_crmentity.*,
			aicrm_serial.*,
			aicrm_serialcf.*,
			aicrm_account.*
			FROM aicrm_serial
			LEFT JOIN aicrm_serialcf ON aicrm_serialcf.serialid = aicrm_serial.serialid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_serial.serialid
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_serial.accountid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_serial.product_id
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_serial.accountid = '".$id."'
			";
        //echo $query."<br>";
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_serial method ...");
        return $return_value;

    }

    function get_competitor($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_competitor(".$id.") method ...");
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
			aicrm_competitor.*,
			aicrm_competitorcf.*,
			aicrm_account.*
			FROM aicrm_competitor
			LEFT JOIN aicrm_competitorcf ON aicrm_competitorcf.competitorid = aicrm_competitor.competitorid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_competitor.competitorid
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_competitor.account_id
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_competitor.account_id = '".$id."'
			";
        //LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_competitor.product_id
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_competitor method ...");
        return $return_value;

    }

	function get_historyacc($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_agreement(".$id.") method ...");
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
					" value='". getTranslatedString('LBL_ADD_NEW'). "History'>&nbsp;";
			}
		}
		$query = "SELECT  aicrm_users.user_name,	aicrm_crmentity.*, aicrm_resale.*, aicrm_resalecf.*,aicrm_account.*,aicrm_products.*
			FROM aicrm_resale
			LEFT JOIN aicrm_resalecf ON aicrm_resalecf.resaleid = aicrm_resale.resaleid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_resale.resaleid
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_resale.prevaccid
			LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_resale.product_id
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_resale.prevaccid = '".$id."'
			";
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;

	}

	/**
	* Function to get Account related Tickets
	* @param  integer   $id      - accountid
	* returns related Ticket record in array format
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

			if(is_string($actions)){
				$actions = explode(',', strtoupper($actions));
			}
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}

			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {//echo "555";
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}

        $query = "SELECT case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
        	aicrm_troubletickets.*,
			aicrm_ticketcf.*,aicrm_account.*,
			CONCAT(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as contactname
			FROM aicrm_troubletickets
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_troubletickets.ticketid
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_troubletickets.accountid
			LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid=aicrm_troubletickets.contactid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			INNER JOIN aicrm_ticketcf ON aicrm_ticketcf.ticketid  = aicrm_troubletickets.ticketid
			WHERE  aicrm_crmentity.deleted = 0 AND aicrm_troubletickets.accountid = ".$id ;
		
		//Appending the security parameter
		global $current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		$tab_id=getTabid('Contacts');
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter('Contacts');
			$query .= ' '.$sec_parameter;

		}

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_tickets method ...");
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
                    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_leaddetails.accountid
                    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
        			LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
        			LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                    LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
        			WHERE aicrm_crmentity.deleted = 0
					AND aicrm_leaddetails.accountid = '".$id."'
			";
			
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_leads method ...");
        return $return_value;

    }

    function get_questionnaireanswer($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_point(".$id.") method ...");
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

        $query = "
			SELECT aicrm_questionnaireanswer.*,aicrm_questionnaireanswercf.*,aicrm_crmentity.crmid,aicrm_account.*,
			case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
			FROM  aicrm_questionnaireanswer
			LEFT JOIN  aicrm_questionnaireanswercf ON aicrm_questionnaireanswercf.questionnaireanswerid = aicrm_questionnaireanswer.questionnaireanswerid 
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid =aicrm_questionnaireanswer.questionnaireanswerid 
			LEFT JOIN aicrm_account ON aicrm_account.accountid =  aicrm_questionnaireanswer.accountid
			LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_questionnaireanswer.accountid = ".$id;
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        $log->debug("Exiting get_point method ...");
        return $return_value;
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

    /**
	* Function to get Account related Products 
	* @param  integer   $id      - accountid
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
			INNER JOIN aicrm_seproductsrel ON aicrm_products.productid = aicrm_seproductsrel.productid and aicrm_seproductsrel.setype='Accounts'
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
			INNER JOIN aicrm_account ON aicrm_account.accountid = aicrm_seproductsrel.crmid
			WHERE aicrm_crmentity.deleted = 0 AND aicrm_account.accountid = $id";
					
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset); 
		
		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;
		
		$log->debug("Exiting get_products method ...");		
		return $return_value;
	}

    function get_voucher($id, $cur_tab_id, $rel_tab_id, $actions=false) {
    	
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_voucher(".$id.") method ...");
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

        $query = "SELECT distinct(aicrm_voucher.voucherid), case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name , aicrm_crmentity.*, aicrm_voucher.*, aicrm_vouchercf.*
                FROM aicrm_voucher
                INNER JOIN aicrm_vouchercf ON aicrm_vouchercf.voucherid = aicrm_voucher.voucherid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_voucher.voucherid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 AND aicrm_voucher.accountid = '".$id."' ";
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_voucher method ...");
        return $return_value;

    }

    function get_salesinvoice($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_salesinvoice(".$id.") method ...");
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
			aicrm_crmentity.*, aicrm_salesinvoice.*, aicrm_salesinvoicecf.*
			FROM aicrm_salesinvoice
			inner join aicrm_salesinvoicecf on aicrm_salesinvoicecf.salesinvoiceid = aicrm_salesinvoice.salesinvoiceid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_salesinvoice.salesinvoiceid
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_salesinvoice.accountid
			LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_account.accountid = ".$id;
		
		//echo $query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_salesinvoice method ...");
		return $return_value;
	}

	function get_priceList($id, $cur_tab_id, $rel_tab_id, $actions=false) {
        global $log, $singlepane_view,$currentModule,$current_user;
        $log->debug("Entering get_priceList(".$id.") method ...");
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
			aicrm_pricelists.*,
			aicrm_pricelistscf.*,
			aicrm_account.*
			FROM aicrm_pricelists
			LEFT JOIN aicrm_pricelistscf ON aicrm_pricelistscf.pricelistid = aicrm_pricelists.pricelistid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_pricelists.account_id
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_pricelists.account_id = '".$id."'
			";
        //LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_competitor.product_id
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_priceList method ...");
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
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_expense.accountid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_expense.accountid = '".$id."' ";
        //LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_competitor.product_id
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_expense method ...");
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

        $query = "SELECT  aicrm_users.user_name,aicrm_account.accountname,
			aicrm_crmentity.*,
			aicrm_samplerequisition.*,
			aicrm_samplerequisitioncf.*,
			aicrm_account.*
			FROM aicrm_samplerequisition
			INNER JOIN aicrm_samplerequisitioncf ON aicrm_samplerequisitioncf.samplerequisitionid = aicrm_samplerequisition.samplerequisitionid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_samplerequisition.samplerequisitionid
			INNER JOIN aicrm_account ON aicrm_account.accountid = aicrm_samplerequisition.account_id
			INNER JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_samplerequisition.account_id = '".$id."'
			";
        //LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_competitor.product_id
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_samplerequisition method ...");
        return $return_value;

    }
	/** Function to export the account records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Accounts Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Accounts", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
	       			FROM ".$this->entity_table."
				INNER JOIN aicrm_account ON aicrm_account.accountid = aicrm_crmentity.crmid
				INNER JOIN aicrm_accountbillads ON aicrm_accountbillads.accountaddressid = aicrm_account.accountid
				INNER JOIN aicrm_accountshipads ON aicrm_accountshipads.accountaddressid = aicrm_account.accountid
				INNER JOIN aicrm_accountscf ON aicrm_accountscf.accountid = aicrm_account.accountid
				LEFT JOIN aicrm_account as aicrm_account2 ON aicrm_account2.accountid = aicrm_account.accountid
	            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
				LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
				";//aicrm_account2 is added to get the Member of account
		$query .= setFromQuery("Accounts");

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
			$query = $query." ".getListViewSecurityParameter("Accounts");
		}

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}

	/** Function to get the Columnnames of the Account Record
	* Used By vtigerCRM Word Plugin
	* Returns the Merge Fields for Word Plugin
	*/
	function getColumnNames_Acnt()
	{
		global $log,$current_user;
		$log->debug("Entering getColumnNames_Acnt() method ...");
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql1 = "SELECT fieldlabel FROM aicrm_field WHERE tabid = 6 and aicrm_field.presence in (0,2)";
			$params1 = array();
		}else
		{
			$profileList = getCurrentUserProfileList();
			$sql1 = "select aicrm_field.fieldid,fieldlabel from aicrm_field INNER JOIN aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid where aicrm_field.tabid=6 and aicrm_field.displaytype in (1,2,4) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_field.presence in (0,2)";
			$params1 = array();
			if (count($profileList) > 0) {
				$sql1 .= " and aicrm_profile2field.profileid in (". generateQuestionMarks($profileList) .")  group by fieldid";
			    array_push($params1,  $profileList);
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
		$log->debug("Exiting getColumnNames_Acnt method ...");
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

		$rel_table_arr = Array("Contacts"=>"aicrm_contactdetails","Potentials"=>"aicrm_potential","Quotes"=>"aicrm_quotes",
					"SalesOrder"=>"aicrm_salesorder","Invoice"=>"aicrm_invoice","Activities"=>"aicrm_activitycf",
					"Documents"=>"aicrm_senotesrel","Attachments"=>"aicrm_seattachmentsrel","HelpDesk"=>"aicrm_troubletickets",
					"Products"=>"aicrm_seproductsrel","Projects"=>"aicrm_projects","Calendar"=>"aicrm_activitycf","Job"=>"aicrm_jobs","Serial"=>"aicrm_serial");

		$tbl_field_arr = Array("aicrm_contactdetails"=>"contactid","aicrm_potential"=>"potentialid","aicrm_quotes"=>"quoteid",
					"aicrm_salesorder"=>"salesorderid","aicrm_invoice"=>"invoiceid","aicrm_activitycf"=>"activityid",
					"aicrm_senotesrel"=>"notesid","aicrm_seattachmentsrel"=>"attachmentsid","aicrm_troubletickets"=>"ticketid",
					"aicrm_seproductsrel"=>"productid","aicrm_projects"=>"projectsid","aicrm_activitycf"=>"activityid","aicrm_jobs"=>"jobid","aicrm_jobs"=>"jobid","aicrm_serial"=>"serialid");


		$entity_tbl_field_arr = Array("aicrm_contactdetails"=>"accountid","aicrm_potential"=>"related_to","aicrm_quotes"=>"accountid",
					"aicrm_salesorder"=>"accountid","aicrm_invoice"=>"accountid","aicrm_activitycf"=>"accountid",
					"aicrm_senotesrel"=>"crmid","aicrm_seattachmentsrel"=>"crmid","aicrm_troubletickets"=>"accountid",
					"aicrm_seproductsrel"=>"crmid","aicrm_projects"=>"accountid","aicrm_activitycf"=>"accountid","aicrm_jobs"=>"accountid","aicrm_serial"=>"accountid");

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
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
	    //echo $secmodule; exit;
		$rel_tables =  array (
			"Contacts" => array("aicrm_contactdetails"=>array("accountid","contactid"),"aicrm_account"=>"accountid"),
			"Potentials" => array("aicrm_potential"=>array("related_to","potentialid"),"aicrm_account"=>"accountid"),
			"Quotes" => array("aicrm_quotes"=>array("accountid","quoteid"),"aicrm_account"=>"accountid"),
			"Projects" => array("aicrm_projects"=>array("accountid","projectsid"),"aicrm_account"=>"accountid"),
			"Calendar" => array("aicrm_activity"=>array("parentid","activityid"),"aicrm_account"=>"accountid"),
            "HelpDesk" => array("aicrm_troubletickets"=>array("accountid","ticketid"),"aicrm_account"=>"accountid"),
			"Products" => array("aicrm_seproductsrel"=>array("crmid","productid"),"aicrm_account"=>"accountid"),
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_account"=>"accountid"),
            "Job" => array("aicrm_jobs"=>array("accountid","jobid"),"aicrm_account"=>"accountid"),
            "Serial" => array("aicrm_serial"=>array("accountid","serialid"),"aicrm_account"=>"accountid"),
            "Competitor" => array("aicrm_competitor"=>array("account_id","competitorid"),"aicrm_account"=>"accountid"),
            "Leads" => array("aicrm_leaddetails"=>array("accountid","leadid"),"aicrm_account"=>"accountid"),
            "Deal" => array("aicrm_deal"=>array("parentid","dealid"),"aicrm_account"=>"accountid"),
            "Questionnaireanswer" => array("aicrm_questionnaireanswer"=>array("accountid","questionnaireanswerid"),"aicrm_account"=>"accountid"),
		);
		return $rel_tables[$secmodule];
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		// echo $module." && ".$secmodule; exit;
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_account","accountid");
		$query .= " left join aicrm_crmentity as aicrm_crmentityAccounts on aicrm_crmentityAccounts.crmid=aicrm_account.accountid and aicrm_crmentityAccounts.deleted=0
			left join aicrm_accountbillads on aicrm_account.accountid=aicrm_accountbillads.accountaddressid
			left join aicrm_accountshipads on aicrm_account.accountid=aicrm_accountshipads.accountaddressid
			left join aicrm_accountscf on aicrm_account.accountid = aicrm_accountscf.accountid
			left join aicrm_account as aicrm_accountAccounts on aicrm_accountAccounts.accountid = aicrm_account.parentid
			left join aicrm_groups as aicrm_groupsAccounts on aicrm_groupsAccounts.groupid = aicrm_crmentityAccounts.smownerid

			
			left join aicrm_users as aicrm_usersCreatorAccounts on aicrm_usersCreatorAccounts.id = aicrm_crmentityAccounts.smcreatorid
			left join aicrm_users as aicrm_usersModifiedAccounts on aicrm_usersModifiedAccounts.id = aicrm_crmentityAccounts.modifiedby
			left join aicrm_users as aicrm_usersAccounts on aicrm_usersAccounts.id = aicrm_crmentityAccounts.smownerid
            
			";
		return $query;
	}

	/**
	* Function to get Account hierarchy of the given Account
	* @param  integer   $id      - accountid
	* returns Account hierarchy in array format
	*/
	function getAccountHierarchy($id) {
		global $log, $adb, $current_user;
        $log->debug("Entering getAccountHierarchy(".$id.") method ...");
		require('user_privileges/user_privileges_'.$current_user->id.'.php');

		$tabname = getParentTab();
		$listview_header = Array();
		$listview_entries = array();

		foreach ($this->list_fields_name as $fieldname=>$colname) {
			if(getFieldVisibilityPermission('Accounts', $current_user->id, $colname) == '0') {
				$listview_header[] = getTranslatedString($fieldname);
			}
		}

		$accounts_list = Array();

		// Get the accounts hierarchy from the top most account in the hierarch of the current account, including the current account
		$encountered_accounts = array($id);
		$accounts_list = $this->__getParentAccounts($id, $accounts_list, $encountered_accounts);

		// Get the accounts hierarchy (list of child accounts) based on the current account
		$accounts_list = $this->__getChildAccounts($id, $accounts_list, $accounts_list[$id]['depth']);

		// Create array of all the accounts in the hierarchy
		foreach($accounts_list as $account_id => $account_info) {
			$account_info_data = array();

			$hasRecordViewAccess = (is_admin($current_user)) || (isPermitted('Accounts', 'DetailView', $account_id) == 'yes');

			foreach ($this->list_fields_name as $fieldname=>$colname) {
				// Permission to view account is restricted, avoid showing field values (except account name)
				if(!$hasRecordViewAccess && $colname != 'accountname') {
					$account_info_data[] = '';
				} else if(getFieldVisibilityPermission('Accounts', $current_user->id, $colname) == '0') {
					$data = $account_info[$colname];
					if ($colname == 'accountname') {
						if ($account_id != $id) {
							if($hasRecordViewAccess) {
								$data = '<a href="index.php?module=Accounts&action=DetailView&record='.$account_id.'&parenttab='.$tabname.'">'.$data.'</a>';
							} else {
								$data = '<i>'.$data.'</i>';
							}
						} else {
							$data = '<b>'.$data.'</b>';
						}
						// - to show the hierarchy of the Accounts
						$account_depth = str_repeat(" .. ", $account_info['depth'] * 2);
						$data = $account_depth . $data;
					} else if ($colname == 'website') {
						$data = '<a href="http://'. $data .'" target="_blank">'.$data.'</a>';
					}
					$account_info_data[] = $data;
				}
			}
			$listview_entries[$account_id] = $account_info_data;
		}

		$account_hierarchy = array('header'=>$listview_header,'entries'=>$listview_entries);
        $log->debug("Exiting getAccountHierarchy method ...");
		return $account_hierarchy;
	}

	/**
	* Function to Recursively get all the upper accounts of a given Account
	* @param  integer   $id      		- accountid
	* @param  array   $parent_accounts   - Array of all the parent accounts
	* returns All the parent accounts of the given accountid in array format
	*/
	function __getParentAccounts($id, &$parent_accounts, &$encountered_accounts) {
		global $log, $adb;
        $log->debug("Entering __getParentAccounts(".$id.",".$parent_accounts.") method ...");

		$query = "SELECT parentid FROM aicrm_account " .
				" INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid" .
				" WHERE aicrm_crmentity.deleted = 0 and aicrm_account.accountid = ?";
		$params = array($id);

		$res = $adb->pquery($query, $params);

		if ($adb->num_rows($res) > 0 &&
			$adb->query_result($res, 0, 'parentid') != '' && $adb->query_result($res, 0, 'parentid') != 0 &&
			!in_array($adb->query_result($res, 0, 'parentid'),$encountered_accounts)) {

			$parentid = $adb->query_result($res, 0, 'parentid');
			$encountered_accounts[] = $parentid;
			$this->__getParentAccounts($parentid,$parent_accounts,$encountered_accounts);
		}

		$query = "SELECT aicrm_account.*, aicrm_accountbillads.*," .
				" CASE when (aicrm_users.user_name not like '') THEN aicrm_users.user_name ELSE aicrm_groups.groupname END as user_name " .
				" FROM aicrm_account" .
				" INNER JOIN aicrm_crmentity " .
				" ON aicrm_crmentity.crmid = aicrm_account.accountid" .
				" INNER JOIN aicrm_accountbillads" .
				" ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid " .
				" LEFT JOIN aicrm_groups" .
				" ON aicrm_groups.groupid = aicrm_crmentity.smownerid" .
				" LEFT JOIN aicrm_users" .
				" ON aicrm_users.id = aicrm_crmentity.smownerid" .
				" WHERE aicrm_crmentity.deleted = 0 and aicrm_account.accountid = ?";

		$params = array($id);
		$res = $adb->pquery($query, $params);

		$parent_account_info = array();
		$depth = 0;
		$immediate_parentid = $adb->query_result($res, 0, 'parentid');
		if (isset($parent_accounts[$immediate_parentid])) {
			$depth = $parent_accounts[$immediate_parentid]['depth'] + 1;
		}
		$parent_account_info['depth'] = $depth;
		foreach($this->list_fields_name as $fieldname=>$columnname) {
			if ($columnname == 'assigned_user_id') {
				$parent_account_info[$columnname] = $adb->query_result($res, 0, 'user_name');
			} else {
				$parent_account_info[$columnname] = $adb->query_result($res, 0, $columnname);
			}
		}
		$parent_accounts[$id] = $parent_account_info;
        $log->debug("Exiting __getParentAccounts method ...");
		return $parent_accounts;
	}

	/**
	* Function to Recursively get all the child accounts of a given Account
	* @param  integer   $id      		- accountid
	* @param  array   $child_accounts   - Array of all the child accounts
	* @param  integer   $depth          - Depth at which the particular account has to be placed in the hierarchy
	* returns All the child accounts of the given accountid in array format
	*/
	function __getChildAccounts($id, &$child_accounts, $depth) {
		global $log, $adb;
        $log->debug("Entering __getChildAccounts(".$id.",".$child_accounts.",".$depth.") method ...");

		$query = "SELECT aicrm_account.*, aicrm_accountbillads.*," .
				" CASE when (aicrm_users.user_name not like '') THEN aicrm_users.user_name ELSE aicrm_groups.groupname END as user_name " .
				" FROM aicrm_account" .
				" INNER JOIN aicrm_crmentity " .
				" ON aicrm_crmentity.crmid = aicrm_account.accountid" .
				" INNER JOIN aicrm_accountbillads" .
				" ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid " .
				" LEFT JOIN aicrm_groups" .
				" ON aicrm_groups.groupid = aicrm_crmentity.smownerid" .
				" LEFT JOIN aicrm_users" .
				" ON aicrm_users.id = aicrm_crmentity.smownerid" .
				" WHERE aicrm_crmentity.deleted = 0 and parentid = ?";
		$params = array($id);

		$res = $adb->pquery($query, $params);

		$num_rows = $adb->num_rows($res);

		if ($num_rows > 0) {
			$depth = $depth + 1;
			for($i=0;$i<$num_rows;$i++) {
				$child_acc_id = $adb->query_result($res, $i, 'accountid');
				if(array_key_exists($child_acc_id,$child_accounts)) {
					continue;
				}
				$child_account_info = array();
				$child_account_info['depth'] = $depth;
				foreach($this->list_fields_name as $fieldname=>$columnname) {
					if ($columnname == 'assigned_user_id') {
						$child_account_info[$columnname] = $adb->query_result($res, $i, 'user_name');
					} else {
						$child_account_info[$columnname] = $adb->query_result($res, $i, $columnname);
					}
				}
				$child_accounts[$child_acc_id] = $child_account_info;
				$this->__getChildAccounts($child_acc_id, $child_accounts, $depth);
			}
		}
        $log->debug("Exiting __getChildAccounts method ...");
		return $child_accounts;
	}

	// Function to unlink the dependent records of the given record by id
	function unlinkDependencies($module, $id) {
		global $log;

		//Deleting Account related Potentials.
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
		//Backup deleted Account related Potentials.
		$params = array($id, RB_RECORD_UPDATED, 'aicrm_crmentity', 'deleted', 'crmid', implode(",", $pot_ids_list));
		$this->db->pquery('INSERT INTO aicrm_relatedlists_rb VALUES(?,?,?,?,?,?)', $params);

		//Deleting Account related Quotes.
		$quo_q = 'SELECT aicrm_crmentity.crmid FROM aicrm_crmentity
			INNER JOIN aicrm_quotes ON aicrm_crmentity.crmid=aicrm_quotes.quoteid
			INNER JOIN aicrm_account ON aicrm_account.accountid=aicrm_quotes.accountid
			WHERE aicrm_crmentity.deleted=0 AND aicrm_quotes.accountid=?';
		$quo_res = $this->db->pquery($quo_q, array($id));
		$quo_ids_list = array();
		for($k=0;$k < $this->db->num_rows($quo_res);$k++)
		{
			$quo_id = $this->db->query_result($quo_res,$k,"crmid");
			$quo_ids_list[] = $quo_id;
			$sql = 'UPDATE aicrm_crmentity SET deleted = 1 WHERE crmid = ?';
			$this->db->pquery($sql, array($quo_id));
		}
		//Backup deleted Account related Quotes.
		$params = array($id, RB_RECORD_UPDATED, 'aicrm_crmentity', 'deleted', 'crmid', implode(",", $quo_ids_list));
		$this->db->pquery('INSERT INTO aicrm_relatedlists_rb VALUES(?,?,?,?,?,?)', $params);

		//Backup Contact-Account Relation
		$con_q = 'SELECT contactid FROM aicrm_contactdetails WHERE accountid = ?';
		$con_res = $this->db->pquery($con_q, array($id));
		if ($this->db->num_rows($con_res) > 0) {
			$con_ids_list = array();
			for($k=0;$k < $this->db->num_rows($con_res);$k++)
			{
				$con_ids_list[] = $this->db->query_result($con_res,$k,"contactid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'aicrm_contactdetails', 'accountid', 'contactid', implode(",", $con_ids_list));
			$this->db->pquery('INSERT INTO aicrm_relatedlists_rb VALUES(?,?,?,?,?,?)', $params);
		}
		//Deleting Contact-Account Relation.
		$con_q = 'UPDATE aicrm_contactdetails SET accountid = 0 WHERE accountid = ?';
		$this->db->pquery($con_q, array($id));

		//Backup Trouble Tickets-Account Relation
		$tkt_q = 'SELECT ticketid FROM aicrm_troubletickets WHERE parent_id = ?';
		$tkt_res = $this->db->pquery($tkt_q, array($id));
		if ($this->db->num_rows($tkt_res) > 0) {
			$tkt_ids_list = array();
			for($k=0;$k < $this->db->num_rows($tkt_res);$k++)
			{
				$tkt_ids_list[] = $this->db->query_result($tkt_res,$k,"ticketid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'aicrm_troubletickets', 'parent_id', 'ticketid', implode(",", $tkt_ids_list));
			$this->db->pquery('INSERT INTO aicrm_relatedlists_rb VALUES(?,?,?,?,?,?)', $params);
		}
		//Deleting Trouble Tickets-Account Relation.
		$tt_q = 'UPDATE aicrm_troubletickets SET parent_id = 0 WHERE parent_id = ?';
		$this->db->pquery($tt_q, array($id));

		parent::unlinkDependencies($module, $id);
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Products') {
			$sql = 'DELETE FROM aicrm_seproductsrel WHERE crmid=? AND productid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'SmartSms') {
			$relation_query = 'DELETE FROM aicrm_smartsms_accountsrel WHERE accountid =? AND  smartsmsid=?';
			$this->db->pquery($relation_query, array($id, $return_id));
		} elseif($return_module == 'Smartemail') {
			$relation_query = 'DELETE FROM aicrm_smartemail_accountsrel WHERE accountid =? AND  smartemailid=?';
			$this->db->pquery($relation_query, array($id, $return_id));
			$relationrel_query = 'DELETE FROM aicrm_crmentityrel WHERE  relcrmid=? AND crmid=?';
			$this->db->pquery($relationrel_query, array($id, $return_id));
		} elseif($return_module == 'PriceList') {
			$relation_query = 'DELETE FROM aicrm_pricelist_accountsrel WHERE accountid =? AND  pricelistid=?';
			$this->db->pquery($relation_query, array($id, $return_id));
			$relationrel_query = 'DELETE FROM aicrm_crmentityrel WHERE  relcrmid=? AND crmid=?';
			$this->db->pquery($relationrel_query, array($id, $return_id));
        } elseif($return_module == 'HelpDesk') {
            $sql = 'DELETE FROM aicrm_seticketsrel WHERE ticketid = ? AND crmid = ?';
            $this->db->pquery($sql, array($return_id, $id));
        } elseif($return_module == 'Serial') {
            $relation_query = 'DELETE FROM aicrm_campaign_account WHERE accountid=? AND campaignid=?';
            $this->db->pquery($relation_query, array($id, $return_id));
        } else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}
}

?>
