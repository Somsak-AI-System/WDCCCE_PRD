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

class Inspection extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_inspection";
	var $table_index= 'inspectionid';

	var $tab_name = Array('aicrm_crmentity','aicrm_inspection','aicrm_inspectioncf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_inspection'=>'inspectionid','aicrm_inspectioncf'=>'inspectionid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_inspectioncf', 'inspectionid');
	var $column_fields = Array();

	var $sortby_fields = Array('inspectionid','inspection_name','smownerid');

	var $list_fields = Array(
		'Inspection No'=>Array('Inspection'=>'inspection_no'),
		'Inspection Name'=>Array('Inspection'=>'inspection_name'),
		'สถานะ/Status'=>Array('Inspection'=>'inspection_status'),
		'หมายเลขซีเรียล/Serial No.(S/N)'=>Array('Inspection'=>'serial_name'),
		'วันที่เริ่มตรวจ'=>Array('Inspection'=>'start_date'),
		'เวลาเริ่มต้น'=>Array('Inspection'=>'start_time'),
		'วันที่สิ้นสุด'=>Array('Inspection'=>'end_date'),
		'เวลาสิ้นสุด'=>Array('Inspection'=>'end_time'),
	);

	var $list_fields_name = Array(
		'Inspection No'=>'inspection_no',
		'Inspection Name'=>'inspection_name',
		'สถานะ/Status' => 'inspection_status',
		'หมายเลขซีเรียล/Serial No.(S/N)	'=> 'serial_name',
		'วันที่เริ่มตรวจ'=> 'start_date',
		'เวลาเริ่มต้น'=> 'start_time',
		'วันที่สิ้นสุด'=> 'end_date',
		'เวลาสิ้นสุด'=> 'end_time',
	);

	var $list_link_field= 'inspection_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'desc';

	var $search_fields = Array(
		'Inspection No'=>Array('aicrm_inspection'=>'inspection_no'),
		'Inspection Name'=>Array('aicrm_inspection'=>'inspection_name'),
	);

	var $search_fields_name = Array(
		'Inspection No'=>'inspection_no',
		'Inspection Name'=>'inspection_name',
	);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','inspectionid');

	function Inspection()
	{
		$this->log =LoggerManager::getLogger('Inspection');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Inspection');
	}

	function save_module()
	{
		global $adb;
		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'InspectionAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave')
		{
			//Based on the total Number of rows we will save the product relationship with this entity
			//saveInventoryProductDetails($this, 'Inspection');
		}
		$this->insertIntoAttachment($this->id,'Inspection');
		
	}

	function insertIntoAttachment($id,$module)
	{
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;

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

			    if($fileindex == 'inspector_signature'){
					$files['flag'] = 'S';
					$files['fileindex'] = $fileindex;
				}elseif($fileindex == 'signature'){
					$files['flag'] = 'S';
					$files['fileindex'] = $fileindex;
				}
				
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}
		
		//Remove the deleted aicrm_attachments from db - Products
		if($module == 'Inspection' && $_REQUEST['del_file_list'] != '')
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
			$sorder = (($_SESSION['INSPECTION_SORT_ORDER'] != '')?($_SESSION['INSPECTION_SORT_ORDER']):($this->default_sort_order));
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
			$order_by = (($_SESSION['INSPECTION_ORDER_BY'] != '')?($_SESSION['INSPECTION_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}
    /**	function used to get the list of unit which are related to the building
     *	@param int $id - building id
     *	@return array - return an array which will be returned from the function GetRelatedList
     */

    function get_unit($id, $cur_tab_id, $rel_tab_id, $actions=false) {

        global $log, $singlepane_view, $currentModule;
        $log->debug("Entering get_unit(".$id.") method ...");
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
        if($actions) {

            if(is_string($actions)) $actions = explode(',', strtoupper($actions));
            if(in_array('SELECT', $actions) ) {
                $button .= "<input title='Select Unit' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='Select Unit'>&nbsp;";
            }
            if(in_array('ADD', $actions) ) {
                $button .= "<input title='Add Unit' class='crmbutton small create'" .
                    " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
                    " value='Add Unit'>&nbsp;";
            }
        }

        $query = "SELECT aicrm_unit.*,aicrm_unitcf.*,aicrm_crmentity.crmid,aicrm_account.*
					FROM aicrm_unit      
					LEFT JOIN aicrm_unitcf on aicrm_unitcf.unitid = aicrm_unit.unitid
					INNER JOIN aicrm_inspection ON aicrm_inspection.inspectionid = aicrm_unit.inspectionid
					INNER JOIN aicrm_account ON aicrm_account.accountid = aicrm_unit.accountid
					INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_unit.unitid
					LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
					LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
					WHERE aicrm_crmentity.deleted=0 AND aicrm_inspection.inspectionid = ".$id;

        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_unit method ...");
        return $return_value;
    }

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_inspection","inspectionid");
//		echo $query ;exit;
		$query .=" left JOIN aicrm_crmentity as aicrm_crmentityinspection on aicrm_crmentityinspection.crmid=aicrm_inspection.inspectionid and aicrm_crmentityinspection.deleted=0
				left JOIN aicrm_inspectioncf on aicrm_inspectioncf.inspectionid = aicrm_crmentityinspection.crmid
				left join aicrm_groups as aicrm_groupsinspection on aicrm_groupsinspection.groupid = aicrm_crmentityinspection.smownerid
				left join aicrm_users as aicrm_usersinspection on aicrm_usersinspection.id = aicrm_crmentityinspection.smownerid
				";
        if($module == "Job" && $secmodule =='Inspection') {
            $query .=" LEFT JOIN aicrm_serial on aicrm_serial.serialid = aicrm_jobs.serialid ";
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
		$sql = getPermittedFieldsQuery("Inspection", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
	       			FROM aicrm_crmentity

				INNER JOIN aicrm_inspection
				ON aicrm_inspection.inspectionid = aicrm_crmentity.crmid
				INNER JOIN aicrm_inspectioncf
				ON aicrm_inspectioncf.inspectionid = aicrm_inspection.inspectionid
				LEFT JOIN aicrm_groups
				ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users
				ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				";
		$query .= setFromQuery("Inspection");
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
			$query = $query." ".getListViewSecurityParameter("Inspection");
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
            "Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_inspection"=>"inspectionid"),
			"Tools" => array("aicrm_crmentityrel"=>array("crmid","relcrmid"),"aicrm_inspection"=>"inspectionid")
        );
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts' ) {
			$this->trash('Inspection',$id);
        } elseif($return_module == 'Building') {
            $relation_query = 'UPDATE aicrm_inspection SET buildingid=0 WHERE inspectionid=?';
            $this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'Potentials') {
			$relation_query = 'UPDATE aicrm_quotes SET potentialid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE aicrm_quotes SET contactid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'InspectionList') {
            $relation_query = 'DELETE FROM aicrm_inspectionlist_inspectionrel WHERE inspectionid =? AND  inspectionlistid=?';
            $this->db->pquery($relation_query, array($id, $return_id));
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}


	function get_resale($id, $cur_tab_id, $rel_tab_id, $actions=false) {
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
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));

			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " ReSale'>&nbsp;";
			}
		}

		$query = "SELECT  aicrm_users.user_name,	aicrm_crmentity.*, aicrm_resale.*, aicrm_resalecf.*,aicrm_account.*,aicrm_products.*
			FROM aicrm_resale
			LEFT JOIN aicrm_resalecf ON aicrm_resalecf.resaleid = aicrm_resale.resaleid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_resale.resaleid
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_resale.accountid
			LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_resale.product_id
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_resale.ref_module = 'Inspection'
			AND aicrm_resale.refid = '".$id."'
			";
		//echo $query."<br>";
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}

	function get_history($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Enteget_historyaccring get_inspection(".$id.") method ...");
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
			/*if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}*/
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " History'>&nbsp;";
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
			AND aicrm_resale.ref_module = 'Inspection'
			AND aicrm_resale.refid = '".$id."'
			";
		//echo $query."<br>";
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;

	}



}

?>
