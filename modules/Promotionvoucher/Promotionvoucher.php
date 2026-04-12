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

class Promotionvoucher extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_promotionvoucher";
	var $table_index= 'promotionvoucherid';
	var $_images_path= 'upload/promotionvoucher/';
	var $table_comment = "aicrm_promotionvouchercomments";
	
	var $tab_name = Array('aicrm_crmentity','aicrm_promotionvoucher','aicrm_promotionvouchercf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_promotionvoucher'=>'promotionvoucherid','aicrm_promotionvouchercf'=>'promotionvoucherid','aicrm_promotionvouchercomments'=>'promotionvoucherid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_promotionvouchercf', 'promotionvoucherid');
	var $column_fields = Array();

	var $sortby_fields = Array('promotionvoucherid','promotionvoucher_name','smownerid');

	var $list_fields = Array(
		'เลขที่โปรโมชั่นบัตรกำนัล'=>Array('Promotionvoucher'=>'promotionvoucher_no'),
		'ชื่อโปรโมชั่นบัตรกำนัล'=>Array('Promotionvoucher'=>'promotionvoucher_name'),
		'ผู้รับผิดชอบ' => Array('crmentity'=>'smownerid')
	);

	var $list_fields_name = Array(
		'เลขที่โปรโมชั่นบัตรกำนัล'=>'promotionvoucher_no',
		'ชื่อโปรโมชั่นบัตรกำนัล'=>'promotionvoucher_name',
		'ผู้รับผิดชอบ'=>'assigned_user_id'
     );

	var $list_link_field= 'promotionvoucher_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';

	//var $groupTable = Array('aicrm_campaigngrouprelation','projects_id');

	var $search_fields = Array(
		'เลขที่โปรโมชั่นบัตรกำนัล'=>Array('aicrm_promotionvoucher'=>'promotionvoucher_no'),
		'ชื่อโปรโมชั่นบัตรกำนัล'=>Array('aicrm_promotionvoucher'=>'promotionvoucher_name'),
		'ผู้รับผิดชอบ' => Array('crmentity'=>'smownerid')
	);

	var $search_fields_name = Array(
		'เลขที่โปรโมชั่นบัตรกำนัล'=>'promotionvoucher_no',
		'ชื่อโปรโมชั่นบัตรกำนัล'=>'promotionvoucher_name',
		'ผู้รับผิดชอบ'=>'assigned_user_id'
	);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','promotionvoucherid');

	function Promotionvoucher()
	{
		$this->log =LoggerManager::getLogger('Promotionvoucher');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Promotionvoucher');
	}
	function save_module()
	{
		global $adb;
		$this->insertIntoCommentTable("aicrm_promotionvouchercomments",'promotionvoucherid');
		$this->insertIntoAttachment($this->id,'Promotionvoucher');
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
		$sql = "select * from aicrm_promotionvouchercomments where promotionvoucherid=? order by createdtime desc";
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
			$sorder = (($_SESSION['PROMOTION_SORT_ORDER'] != '')?($_SESSION['PROMOTION_SORT_ORDER']):($this->default_sort_order));
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
			$order_by = (($_SESSION['PROMOTION_ORDER_BY'] != '')?($_SESSION['PROMOTION_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		//echo $module;echo'<br>'; echo $secmodule;exit;
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_promotionvoucher","promotionvoucherid");
		$query .=" left join aicrm_crmentity as aicrm_crmentityPromotionvoucher on aicrm_crmentityPromotionvoucher.crmid=aicrm_promotionvoucher.promotionvoucherid and aicrm_crmentityPromotionvoucher.deleted=0
				left join aicrm_promotionvouchercf on aicrm_promotionvouchercf.promotionvoucherid = aicrm_crmentityPromotionvoucher.crmid
				left join aicrm_campaign as aicrm_campaignPromotionvoucher on aicrm_campaignPromotionvoucher.campaignid = aicrm_promotionvoucher.campaignid
				left join aicrm_groups as aicrm_groupsPromotionvoucher on aicrm_groupsPromotionvoucher.groupid = aicrm_crmentityPromotionvoucher.smownerid
				left join aicrm_users as aicrm_usersPromotionvoucher on aicrm_usersPromotionvoucher.id = aicrm_crmentityPromotionvoucher.smownerid
				left join aicrm_users as aicrm_usersModifiedPromotionvoucher on aicrm_crmentity.smcreatorid=aicrm_usersModifiedPromotionvoucher.id
                left join aicrm_users as aicrm_usersCreatorPromotionvoucher on aicrm_crmentity.smcreatorid=aicrm_usersCreatorPromotionvoucher.id
				";
		return $query;
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
                    " value='". getTranslatedString('LBL_ADD_NEW'). " Voucher'>&nbsp;";
            }
        }

        $query = "SELECT distinct(aicrm_voucher.voucherid), 
				case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name , 
				aicrm_crmentity.*, 
				aicrm_voucher.*, 
				aicrm_vouchercf.*
                FROM aicrm_voucher
                INNER JOIN aicrm_vouchercf ON aicrm_vouchercf.voucherid = aicrm_voucher.voucherid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_voucher.voucherid
                LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id
                LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id
                WHERE aicrm_crmentity.deleted = 0 AND aicrm_voucher.promotionvoucherid = '".$id."' ";
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_voucher method ...");
        return $return_value;

    }

	function create_export_query($where)
	{
		global $log;
		global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Promotionvoucher", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
	       			FROM aicrm_crmentity
				INNER JOIN aicrm_promotionvoucher ON aicrm_promotionvoucher.promotionvoucherid = aicrm_crmentity.crmid
				INNER JOIN aicrm_promotionvouchercf ON aicrm_promotionvouchercf.promotionvoucherid = aicrm_promotionvoucher.promotionvoucherid
				LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_promotionvoucher.campaignid
				LEFT JOIN aicrm_promotionvouchercomments ON aicrm_promotionvouchercomments.promotionvoucherid = aicrm_promotionvoucher.promotionvoucherid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
				LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
				";
		$query .= setFromQuery("Promotionvoucher");
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
			$query = $query." ".getListViewSecurityParameter("Promotionvoucher");
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
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_promotionvoucher"=>"promotionvoucherid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts' ) {
			$this->trash('Promotionvoucher',$id);
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
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
		if($module == 'Promotionvoucher' && $_REQUEST['del_file_list'] != '')
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

}

?>
