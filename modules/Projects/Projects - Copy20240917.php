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

class Projects extends CRMEntity {
// Account is used to store aicrm_account information.
	var $log;
	var $db;
	var $table_name = "aicrm_projects";
	var $table_index= 'projectsid';
	var $table_comment = "aicrm_projectscomments";

	var $tab_name = Array('aicrm_crmentity','aicrm_projects','aicrm_projectscf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_projects'=>'projectsid','aicrm_projectscf'=>'projectsid','aicrm_projectscomments'=>'projectsid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('aicrm_projectscf', 'projectsid');
	var $column_fields = Array();

	var $sortby_fields = Array('projectsid','projects_name','smownerid','projects_no','accountid','accountname','lastname');

	var $list_fields = Array(
		'หมายเลขโครงการ'=>Array('projects'=>'projects_no'),
		'ชื่อโครงการ'=>Array('projects'=>'projects_name'),
		'Project Size'=>Array('projects'=>'project_size'),
		'วันที่คาดว่าจะเริ่มโครงการ'=>Array('projects'=>'project_s_date'),
		'วันที่คาดว่าจะจบโครงการ'=>Array('projects'=>'project_estimate_e_date'),
		'สถานะโครงการ'=>Array('projects'=>'projectorder_status')
	);

	var $list_fields_name = Array(
		'หมายเลขโครงการ' =>'projects_no',
		'ชื่อโครงการ' =>'projects_name',
		'Project Size' =>'project_size',
		'วันที่คาดว่าจะเริ่มโครงการ' => 'project_s_date',
		'วันที่คาดว่าจะจบโครงการ' => 'project_estimate_e_date',
		'สถานะโครงการ' => 'projectorder_status'
	);				

	var $list_link_field= 'projects_name';
	//var $list_link_field= 'projects_no';
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'aicrm_crmentity.crmid';
	var $default_sort_order = 'DESC';

	//var $groupTable = Array('aicrm_campaigngrouprelation','projectsid');

	var $search_fields = Array(
		'หมายเลขโครงการ'=>Array('aicrm_projects'=>'projects_no'),
		'ชื่อโครงการ'=>Array('aicrm_projects'=>'projects_name'),
  'Project Size'=>Array('projects'=>'project_size'),
		'วันที่คาดว่าจะเริ่มโครงการ'=>Array('projects'=>'project_s_date'),
		'วันที่คาดว่าจะจบโครงการ'=>Array('projects'=>'project_estimate_e_date'),
		'สถานะโครงการ'=>Array('projects'=>'projectorder_status')
	);

	var $search_fields_name = Array(
		'หมายเลขโครงการ'=>'projects_no',
		'ชื่อโครงการ'=>'projects_name',
   'Project Size' =>'project_size',
		'วันที่คาดว่าจะเริ่มโครงการ' => 'project_s_date',
		'วันที่คาดว่าจะจบโครงการ' => 'project_estimate_e_date',
		'สถานะโครงการ' => 'projectorder_status'
	);
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','createdtime' ,'modifiedtime','projects_name');
	
	function Projects() 
	{
		$this->log =LoggerManager::getLogger('Projects');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Projects');
	}

	function save_module()
	{
		global $adb;
		$sql = "insert into tbt_project_log(crmid,assignto,projectstatus,adduser,adddate)
			values('".$this->id."','".$this->column_fields['assigned_user_id']."','".$this->column_fields["projectorder_status"]."','".$_SESSION['authenticated_user_id']."','".date('Y-m-d H:i:s')."');";
		$adb->pquery($sql,"");
		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'ProjectsAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave')
		{
			//Based on the total Number of rows we will save the product relationship with this entity
			saveInventoryProductDetails_Projects($this, 'Projects');
		}

		$this->insertIntoCommentTable("aicrm_projectscomments",'projectsid');
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
		$sql = "select * from aicrm_projectscomments where projectsid=? order by createdtime desc";
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
			$sorder = (($_SESSION['PROJECTS_SORT_ORDER'] != '')?($_SESSION['PROJECTS_SORT_ORDER']):($this->default_sort_order));
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
		
		if (isset($_REQUEST['order_by'])) {
			$order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
		}else{
			$order_by = (($_SESSION['PROJECTS_ORDER_BY'] != '')?($_SESSION['PROJECTS_ORDER_BY']):($use_default_order_by));
		}
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
		
		$query = "SELECT aicrm_activity.*, aicrm_activitycf.*, aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_crmentity.description,
			case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
			FROM aicrm_activity 
			INNER JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
			INNER JOIN aicrm_projects ON aicrm_projects.projectsid = aicrm_activity.event_id
			INNER JOIN aicrm_projectscf ON aicrm_projectscf.projectsid = aicrm_projects.projectsid		
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
			LEFT JOIN aicrm_groups	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0 AND aicrm_activity.event_id = ".$id;
		//echo $query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
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
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_quotes.parentid
			
			LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_quotes.event_id = ".$id;
		//, aicrm_contactdetails.*
		//LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_quotes.contactid

		//echo $query;
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
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
			AND aicrm_pricelists.projectsid = '".$id."'
			";
        //LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_competitor.product_id
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_priceList method ...");
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
			LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_samplerequisition.accountid
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			LEFT JOIN aicrm_groups ON aicrm_groups.groupid=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_samplerequisition.projectsid = '".$id."'
			";
			//echo $query;
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
			LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0
			AND aicrm_expense.projectsid = '".$id."' ";
        //LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_competitor.product_id
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        if($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_expense method ...");
        return $return_value;

    }

    function get_questionnaire($id, $cur_tab_id, $rel_tab_id, $actions=false) {
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
			AND aicrm_questionnaireanswer.event_id = ".$id;
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        $log->debug("Exiting get_point method ...");
        return $return_value;
    }

	/*
	 * Function to get the secondary query part of a report 
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		// echo $module." && ".$secmodule; exit;
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_projects","projectsid");
		$query .=" left join aicrm_crmentity as aicrm_crmentityprojects on aicrm_crmentityprojects.crmid=aicrm_projects.projectsid and aicrm_crmentityprojects.deleted=0 
				left join aicrm_projectscf on aicrm_projectscf.projectsid = aicrm_crmentityprojects.crmid 
				left join aicrm_groups as aicrm_groupsprojects on aicrm_groupsprojects.groupid = aicrm_crmentityprojects.smownerid
				left join aicrm_users as aicrm_usersprojects on aicrm_usersprojects.id = aicrm_crmentityprojects.smownerid
				LEFT JOIN aicrm_users as aicrm_usersModifiedProjects on aicrm_crmentity.modifiedby = aicrm_usersModifiedProjects.id
            	LEFT JOIN aicrm_users as aicrm_usersCreatorProjects on aicrm_crmentity.smcreatorid = aicrm_usersCreatorProjects.id
				LEFT JOIN aicrm_inventoryproductrel as projectProductrel on aicrm_projects.projectsid = projectProductrel.id and projectProductrel.productid>0 
				left join aicrm_account as aicrm_accountProjects on aicrm_accountProjects.accountid = aicrm_projects.accountid
				left join aicrm_contactdetails as aicrm_contactdetailsProjects on aicrm_contactdetailsProjects.contactid= aicrm_projects.contactid
			";
		if($module != 'Products' && $secmodule == 'Projects'){
			$query .= " LEFT JOIN aicrm_products as projectProduct on projectProductrel.productid=projectProduct.productid ";
            $query .= " LEFT JOIN aicrm_productcf as projectProductcf on projectProductcf.productid=projectProduct.productid ";
		}
		//echo $query; exit;
		return $query;
	}

	/*
	 * Function to get the relation tables for related modules 
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		//echo 555; exit;
		$rel_tables = array (
            "Calendar" =>array("aicrm_activity"=>array("event_id","activityid"),"aicrm_projects"=>"projectsid"),
            "Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_projects"=>"projectsid"),
			/*"Accounts" => array("aicrm_projects"=>array("projectsid","accountid"),"aicrm_projects"=>"projectsid"),
			"Contacts" => array("aicrm_projects"=>array("projectsid","contactid"),"aicrm_projects"=>"projectsid"),*/
		);
		return $rel_tables[$secmodule];
	}
	
	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		
		if($return_module == 'Accounts' ) {
			$this->trash('Projects',$id);
		} elseif($return_module == 'Potentials') {
			$relation_query = 'UPDATE aicrm_quotes SET potentialid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE aicrm_quotes SET contactid=0 WHERE quoteid=?';
			$this->db->pquery($relation_query, array($id));
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

}

?>
