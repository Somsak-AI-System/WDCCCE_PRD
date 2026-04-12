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

class Job extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_jobs";
	var $table_index= 'jobid';
	var $table_comment = "aicrm_jobscomments";
	var $tab_name = Array('aicrm_crmentity','aicrm_jobs','aicrm_jobscf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_jobs'=>'jobid','aicrm_jobscf'=>'jobid','aicrm_jobscomments'=>'jobid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_jobscf', 'jobid');
	var $column_fields = Array();
	var $sortby_fields = Array('jobid','job_name','smownerid','accountname','lastname');

	var $list_fields = Array(
		'Job No'=>Array('Job'=>'job_no'),
		'วันที่แจ้ง'=>Array('aicrm_jobs'=>'open_date'),
		'รูปแบบใบงาน'=>Array('aicrm_jobs'=>'job_type'),
		'วันที่ปิด'=>Array('aicrm_jobs'=>'close_date'),
		'สถานะใบงาน'=>Array('aicrm_jobs'=>'job_status')
	);

	var $list_fields_name = Array(
		'Job No'=>'job_no',
		'วันที่แจ้ง'=>'open_date',
		'ประเภทงานซ่อม'=>'jobtype',
		'วันที่ปิด'=>'close_date',
		'สถานะใบงาน'=>'job_status',
	);

	var $list_link_field= 'job_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'desc';

	var $search_fields = Array(
		'Job No'=>Array('aicrm_jobs'=>'job_no'),
		'วันที่แจ้ง'=>Array('aicrm_jobs'=>'open_date'),
		'ประเภทงานซ่อม'=>Array('aicrm_jobs'=>'jobtype'),
		'วันที่ปิด'=>Array('aicrm_jobs'=>'close_date'),
		'สถานะใบงาน'=>Array('aicrm_jobs'=>'job_status')
	);

	var $search_fields_name = Array(
		'Job No'=>'job_no',
		'วันที่แจ้ง'=>'open_date',
		'ประเภทงานซ่อม'=>'jobtype',
		'วันที่ปิด'=>'close_date',
		'สถานะใบงาน'=>'job_status',
	);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','jobid');

	function Job()
	{
		$this->log =LoggerManager::getLogger('Job');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Job');
	}
	
	function save_module()
	{
		global $adb;		
		$this->insertIntoAttachment($this->id,'Job');
		$this->insertIntoCommentTable("aicrm_jobscomments",'jobid');

		if($this->mode == '') {//Action Add
			if(vtlib_isModuleActive('Serial')) {//Check Module
				if(isset($this->column_fields["serialid"]) && $this->column_fields["serialid"] !='' && $this->column_fields["serialid"] != '0' && $this->column_fields['serial_location']){
					$sql = "Update aicrm_serial set location = '".$this->column_fields["serial_location"]."' where  serialid = '".$this->column_fields["serialid"]."' ";
					$adb->pquery($sql,'');
				}
			}
		}

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
		$sql = "select * from aicrm_jobscomments where jobid=? order by createdtime desc";
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
	
	function insertIntoAttachment($id,$module)
	{
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;
		//echo "<pre>"; print_r($_FILES); echo "</pre>"; exit;
		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
			      if($_REQUEST[$fileindex.'_hidden'] != ''){
				      $files['original_name'] = vtlib_purify($_REQUEST[$fileindex.'_hidden']);
			      }else{
				      $files['original_name'] = stripslashes($files['name']);
				  }

			      $files['original_name'] = str_replace('"','',$files['original_name']);

			    if($fileindex == 'image_customer'){
					$files['flag'] = 'S';
					$files['fileindex'] = $fileindex;
				}elseif($fileindex == 'image_user'){
					$files['flag'] = 'S';
					$files['fileindex'] = $fileindex;
				//}elseif("imagename" == str_contains($fileindex, 'imagename')){
				}elseif(strpos($fileindex, 'imagename') !== FALSE) {
					$files['flag'] = 'S';
					$files['fileindex'] ="imagename";
				//}elseif("imagereceipt" == str_contains($fileindex, 'imagereceipt')){
				}elseif(strpos($fileindex, 'imagereceipt') !== FALSE) {
					$files['flag'] = 'S';
					$files['fileindex'] = "imagereceipt";
				}
				
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}
		//Remove the deleted aicrm_attachments from db - Products
		if($module == 'Job' && $_REQUEST['del_file_list'] != '')
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
		if($module == 'Job' && $_REQUEST['imagename_hidden'] != '')
		{
			$del_file_list = explode("###",trim($_REQUEST['imagename_hidden'],"###"));
			foreach($del_file_list as $del_file_name)
			{
				$attach_res = $adb->pquery("select aicrm_attachments.attachmentsid from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid where crmid=? and name=?", array($id,$del_file_name));
				$attachments_id = $adb->query_result($attach_res,0,'attachmentsid');

				$del_res1 = $adb->pquery("delete from aicrm_attachments where attachmentsid=?", array($attachments_id));
				$del_res2 = $adb->pquery("delete from aicrm_seattachmentsrel where attachmentsid=?", array($attachments_id));
			}
		}
		if($module == 'Job' && $_REQUEST['imagereceipt_hidden'] != '')
		{
			$del_file_list = explode("###",trim($_REQUEST['imagereceipt_hidden'],"###"));
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
			$sorder = (($_SESSION['JOB_SORT_ORDER'] != '')?($_SESSION['JOB_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}


	function get_errorslist($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_loan(".$id.") method ...");
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
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;

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

		$query = "SELECT  aicrm_users.user_name,aicrm_crmentity.*, aicrm_errors.*, aicrm_jobs.*  ,aicrm_errorslist.* ,aicrm_errorslistcf.*
			FROM aicrm_errorslist
			LEFT JOIN aicrm_errorslistcf ON aicrm_errorslistcf.errorslistid = aicrm_errorslist.errorslistid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_errorslist.errorslistid
			LEFT JOIN aicrm_errors on aicrm_errors.errorsid = aicrm_errorslist.errorsid
			LEFT JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_errorslist.jobid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0 AND aicrm_errorslist.jobid = '".$id."'
			";
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}

	function get_inspection($id, $cur_tab_id, $rel_tab_id, $actions = false)
	{
		global $log, $singlepane_view, $currentModule, $current_user;
		$log->debug("Entering get_loan(" . $id . ") method ...");
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
		$returnset = '&return_module=' . $this_module . '&return_action=DetailView&return_id=' . $id;

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

		$query = "SELECT  aicrm_users.user_name,aicrm_crmentity.*, aicrm_inspection.*,aicrm_inspectioncf.*,aicrm_serial.*
		 FROM aicrm_inspection
		 INNER JOIN aicrm_inspectioncf ON aicrm_inspectioncf.inspectionid = aicrm_inspection.inspectionid
		 INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_inspection.inspectionid
		 LEFT JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_inspection.jobid
		 LEFT JOIN aicrm_serial ON aicrm_serial.serialid = aicrm_inspection.serialid
		 LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
		 LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
		 WHERE aicrm_crmentity.deleted = 0 AND aicrm_inspection.jobid = '" . $id . "'
		 ";
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);
		//echo $query;
		if ($return_value == null) $return_value = array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}

	function get_sparepartlist($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_loan(".$id.") method ...");
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
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;

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

		$query = "SELECT  aicrm_users.user_name,aicrm_crmentity.*, aicrm_sparepart.*, aicrm_jobs.* ,aicrm_sparepartlist.* ,aicrm_sparepartlistcf.*
			FROM aicrm_sparepartlist
			LEFT JOIN aicrm_sparepartlistcf ON aicrm_sparepartlistcf.sparepartlistid = aicrm_sparepartlist.sparepartlistid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_sparepartlist.sparepartlistid
			LEFT JOIN aicrm_sparepart on aicrm_sparepart.sparepartid = aicrm_sparepartlist.sparepartid
			LEFT JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_sparepartlist.jobid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0 AND aicrm_sparepartlist.jobid = '".$id."' ";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
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
			$order_by = (($_SESSION['JOB_ORDER_BY'] != '')?($_SESSION['JOB_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}

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

		$query = "SELECT aicrm_activity.*, aicrm_activitycf.*, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_crmentity.description, aicrm_jobs.*,aicrm_jobscf.*,
			case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
			FROM aicrm_activity 
			INNER JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
			INNER JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_activity.event_id
			INNER JOIN aicrm_jobscf ON aicrm_jobscf.jobid = aicrm_jobs.jobid		
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
			LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0 AND aicrm_activity.event_id = ".$id;
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
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_jobs","jobid");
		$query .=" left join aicrm_crmentity as aicrm_crmentityJob on aicrm_crmentityJob.crmid=aicrm_jobs.jobid and aicrm_crmentityJob.deleted=0
				left join aicrm_jobscf on aicrm_jobscf.jobid = aicrm_crmentityJob.crmid
				left join aicrm_groups as aicrm_groupsJob on aicrm_groupsJob.groupid = aicrm_crmentityJob.smownerid
				left join aicrm_users as aicrm_usersJob on aicrm_usersJob.id = aicrm_crmentityJob.smownerid
				left join aicrm_users as aicrm_usersModifiedJob on aicrm_crmentity.smcreatorid=aicrm_usersModifiedJob.id
                left join aicrm_users as aicrm_usersCreatorJob on aicrm_crmentity.smcreatorid=aicrm_usersCreatorJob.id";

        if($module!='Accounts'&&$secmodule!='Job') {
            $query .= " LEFT JOIN aicrm_account AS aicrm_accountSerial ON aicrm_accountSerial.accountid = aicrm_jobs.accountid";
        }else{
            $query .= " LEFT JOIN aicrm_account AS aicrm_accountJob ON aicrm_accountJob.accountid = aicrm_jobs.accountid";
		}
		return $query;
	}

	function create_export_query($where)
	{
		global $log;
		global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Job", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
	       		FROM aicrm_crmentity
				INNER JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_crmentity.crmid
				INNER JOIN aicrm_jobscf ON aicrm_jobscf.jobid = aicrm_jobs.jobid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_jobs.accountid
				LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_jobs.contactid
				LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_jobs.product_id
				LEFT JOIN aicrm_troubletickets ON aicrm_troubletickets.ticketid = aicrm_jobs.ticketid 
				LEFT JOIN aicrm_servicerequest ON aicrm_servicerequest.servicerequestid = aicrm_jobs.servicerequestid
				LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
                LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
				";
		$query .= setFromQuery("Job");
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
			$query = $query." ".getListViewSecurityParameter("Job");
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
			"Calendar" =>array("aicrm_activity"=>array("event_id","activityid"),"aicrm_jobs"=>"jobid"),
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_jobs"=>"jobid"),
			"Contacts" => array("aicrm_quotes"=>array("quoteid","contactid")),
            "Errorslist" => array("aicrm_errorslist"=>array("jobid","errorslistid"),"aicrm_jobs"=>"jobid"),
			"Sparepartlist" => array("aicrm_sparepartlist"=>array("jobid","sparepartlistid"),"aicrm_jobs"=>"jobid"),
            "Serial" => array("aicrm_serial"=>array("serialid","serialid"),"aicrm_jobs"=>"jobid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		// echo $return_id; exit;

		if(empty($return_module) || empty($return_id)) return;
		//echo 1234; exit;
		if($return_module == 'Accounts' ) {
			$this->trash('Job',$id);
		} elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE aicrm_quotes SET contactid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
        } elseif($return_module == 'Products') {
            $relation_query = 'UPDATE aicrm_crmentity SET deleted = 1 WHERE crmid =?';
            $this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'HelpDesk') {
            $relation_query = 'UPDATE aicrm_jobs SET ticketid=0 WHERE jobid=?';
            $this->db->pquery($relation_query, array($id));
		}elseif($return_module == 'Serial') {
            $relation_query = 'UPDATE aicrm_jobs SET serialid=0 WHERE jobid=?';
            $this->db->pquery($relation_query, array($id));
		}elseif($return_module == 'Errors') {
            $relation_query = 'UPDATE aicrm_jobs SET errorsid=0 WHERE jobid=?';
            $this->db->pquery($relation_query, array($id));
		}else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

}

?>
