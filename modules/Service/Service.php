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

class Service extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_service";
	var $table_index= 'serviceid';
	var $table_comment = "aicrm_servicecomments";
	var $tab_name = Array('aicrm_crmentity','aicrm_service','aicrm_servicecf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_service'=>'serviceid','aicrm_servicecf'=>'serviceid','aicrm_servicecomments'=>'serviceid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_servicecf', 'serviceid');
	var $column_fields = Array();

	var $sortby_fields = Array('serviceid','service_name');

	var $list_fields = Array(
					'Service No'=>Array('aicrm_service'=>'service_no'),
					'Service Name'=>Array('aicrm_service'=>'service_name')
					//'Assigned To' => Array('crmentity'=>'smownerid')
				);

	var $list_fields_name = Array(
					'Service No'=>'service_no',
					'Service Name'=>'service_name'
					//'Assigned To'=>'assigned_user_id'
				     );	  			

	var $list_link_field= 'service_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'desc';

	//var $groupTable = Array('aicrm_campaigngrouprelation','projects_id');

	var $search_fields = Array(
			'Service No'=>Array('aicrm_service'=>'service_no'),
			'Service Name'=>Array('aicrm_service'=>'service_name')

			);

	var $search_fields_name = Array(
			'Service No'=>'service_no',
			'Service Name'=>'service_name'
	);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','serviceid');
	
	function Service() 
	{
		$this->log =LoggerManager::getLogger('Service');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Service');
	}
	function save_module()
	{
		global $adb;
		$this->insertIntoCommentTable("aicrm_servicecomments",'serviceid');
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
		$sql = "select * from aicrm_servicecomments where serviceid=? order by createdtime desc";
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
			$sorder = (($_SESSION['SERVICE_SORT_ORDER'] != '')?($_SESSION['SERVICE_SORT_ORDER']):($this->default_sort_order));
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
			$order_by = (($_SESSION['SERVICE_ORDER_BY'] != '')?($_SESSION['SERVICE_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}	


	/**	function used to get the list of activities which are related to the Quotes
	 *	@param int $id - quote id
	 *	@return array - return an array which will be returned from the function GetRelatedList
	 */
   //  function get_servicelist($id, $cur_tab_id, $rel_tab_id, $actions=false) {
   //      global $log, $singlepane_view, $currentModule, $current_user;
   //      $log->debug("Entering get_servicelist(" . $id . ") method ...");
   //      $this_module = $currentModule;

   //      $related_module = vtlib_getModuleNameById($rel_tab_id);
   //      require_once("modules/$related_module/$related_module.php");
   //      $other = new $related_module();
   //      vtlib_setup_modulevars($related_module, $other);
   //      $singular_modname = vtlib_toSingular($related_module);

   //      $parenttab = getParentTab();

   //      if ($singlepane_view == 'true')
   //          $returnset = '&return_module=' . $this_module . '&return_action=DetailView&return_id=' . $id;
   //      else
   //          $returnset = '&return_module=' . $this_module . '&return_action=CallRelatedList&return_id=' . $id;

   //      $button = '';

   //      if ($actions) {
   //          if (is_string($actions)) $actions = explode(',', strtoupper($actions));
   //          if (in_array('SELECT', $actions) && isPermitted($related_module, 4, '') == 'yes') {
   //              $button .= "<input title='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "'>&nbsp;";
   //          }
   //          if (in_array('ADD', $actions) && isPermitted($related_module, 1, '') == 'yes') {
   //              $button .= "<input title='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "' class='crmbutton small create'" .
   //                  " onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
   //                  " value='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "'>&nbsp;";
   //          }
   //      }

   //      $query = "SELECT  aicrm_users.user_name,aicrm_crmentity.*, aicrm_service.* ,aicrm_servicelist.* ,aicrm_servicelistcf.*
			// FROM aicrm_servicelist
			// LEFT JOIN aicrm_servicelistcf ON aicrm_servicelistcf.servicelistid = aicrm_servicelist.servicelistid
			// INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_servicelist.servicelistid
			// LEFT JOIN aicrm_service on aicrm_service.serviceid = aicrm_servicelist.serviceid
			// LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			// WHERE aicrm_crmentity.deleted = 0 AND aicrm_servicelist.serviceid = '".$id."' ";

   //      $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

   //      if($return_value == null) $return_value = Array();
   //      $return_value['CUSTOM_BUTTON'] = $button;

   //      $log->debug("Exiting get_servicelist method ...");
   //      return $return_value;
   //  }

	/*
	 * Function to get the secondary query part of a report 
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_service","serviceid");
		//echo $query ;exit;
		$query .=" left join aicrm_crmentity as aicrm_crmentityEmailtarget on aicrm_crmentityEmailtarget.crmid=aicrm_service.serviceid and aicrm_crmentityEmailtarget.deleted=0 
				left join aicrm_servicecf on aicrm_servicecf.serviceid = aicrm_crmentityEmailtarget.crmid 
				left join aicrm_groups as aicrm_groupsEmailtarget on aicrm_groupsEmailtarget.groupid = aicrm_crmentityEmailtarget.smownerid
				left join aicrm_users as aicrm_usersEmailtarget on aicrm_usersEmailtarget.id = aicrm_crmentityEmailtarget.smownerid
				left join aicrm_users as aicrm_usersModifiedService on aicrm_crmentity.modifiedby=aicrm_usersModifiedService.id
                left join aicrm_users as aicrm_usersCreatorService on aicrm_crmentity.smcreatorid=aicrm_usersModifiedService.id";
		return $query;
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
        $query = "SELECT
            aicrm_crmentity.*, aicrm_quotes.*, aicrm_quotescf.*,
        aicrm_inventoryproductrel.id, aicrm_inventoryproductrel.productid, aicrm_inventoryproductrel.product_name
        FROM
            aicrm_quotes
            INNER JOIN aicrm_quotescf ON aicrm_quotescf.quoteid = aicrm_quotes.quoteid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
            LEFT JOIN aicrm_inventoryproductrel ON aicrm_quotes.quoteid = aicrm_inventoryproductrel.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id 
        WHERE
            aicrm_crmentity.deleted = 0 
            AND aicrm_inventoryproductrel.productid = ".$id;
			// echo $query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}

	function create_export_query($where)
	{
		global $log;
		global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Service", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$query = "SELECT $fields_list,case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name 
	       			FROM aicrm_crmentity
				INNER JOIN aicrm_service ON aicrm_service.serviceid = aicrm_crmentity.crmid
				INNER JOIN aicrm_servicecf ON aicrm_servicecf.serviceid = aicrm_service.serviceid
				
				LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid and aicrm_users.status = 'Active'
				LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
				LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
				";
		//LEFT JOIN aicrm_serial	ON aicrm_serial.serialid = aicrm_service.serialid
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
			$query = $query." ".getListViewSecurityParameter("Service");
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
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_service"=>"serviceid"),
            // "Servicelist" => array("aicrm_servicelist"=>array("serviceid","servicelistid"),"aicrm_service"=>"serviceid"),
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
