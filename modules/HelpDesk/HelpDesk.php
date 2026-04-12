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
require_once('user_privileges/default_module_view.php');

class HelpDesk extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "aicrm_troubletickets";
	var $table_index= 'ticketid';
	var $tab_name = Array('aicrm_crmentity','aicrm_troubletickets','aicrm_ticketcf');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_troubletickets'=>'ticketid','aicrm_ticketcf'=>'ticketid','aicrm_ticketcomments'=>'ticketid');
	var $customFieldTable = Array('aicrm_ticketcf', 'ticketid');
	var $column_fields = Array();
	//Pavani: Assign value to entity_table
    var $entity_table = "aicrm_crmentity";

	var $sortby_fields = Array('ticket_no','title','status','priority','crmid','firstname','smownerid','accountname','lastname','productname','product_id');

	var $list_fields = Array(
		'หมายเลขเคส'=>Array('aicrm_troubletickets'=>'ticket_no'),
		'ชื่อเรื่องร้องเรียน'=>Array('aicrm_troubletickets'=>'ticket_title'),
		'ผู้รับผิดชอบงานต่อ'=>Array('aicrm_crmentity'=>'smownerid'),
	);

	var $list_fields_name = Array(
		'หมายเลขเคส'=>'ticket_no',
		'ชื่อเรื่องร้องเรียน' => 'ticket_title',
		'ผู้รับผิดชอบงานต่อ'=>'assigned_user_id',
	);
	
	var $search_fields = Array(
		'หมายเลขเคส' =>Array('aicrm_troubletickets'=>'ticket_no'),
		'ชื่อเรื่องร้องเรียน' => Array('aicrm_troubletickets'=>'ticket_title'),
		'ผู้รับผิดชอบงานต่อ'=>Array('aicrm_crmentity'=>'smownerid'),
	);

	var $search_fields_name = Array(
		'หมายเลขเคส' => 'ticket_no',
		'ชื่อเรื่องร้องเรียน'=>'ticket_title',
		'ผู้รับผิดชอบงานต่อ'=>'assigned_user_id',
	);

	var $list_link_field= 'ticket_no';
	var $range_fields = Array(
        'ticketid',
        'ticket_no',
		'ticket_title',
    	'firstname',
        'lastname',
    	'parent_id',
    	'productid',
    	'productname',
    	'priority',
    	'severity',
        'status',
    	'category',
		'description',
		'solution',
		'modifiedtime',
		'createdtime'
	);
	
	//Specify Required fields
    var $required_fields =  array();

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to aicrm_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id', 'createdtime', 'modifiedtime', 'ticket_title', 'update_log');

     //Added these variables which are used as default order by and sortorder in ListView
     var $default_order_by = 'ticket_no';
     var $default_sort_order = 'DESC';

	//var $groupTable = Array('aicrm_ticketgrouprelation','ticketid');

	/**	Constructor which will set the column_fields in this object
	 */
	function HelpDesk()
	{
		$this->log =LoggerManager::getLogger('helpdesk');
		$this->log->debug("Entering HelpDesk() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('HelpDesk');
		$this->log->debug("Exiting HelpDesk method ...");
	}


	function save_module($module)
	{
		//Inserting into Ticket Comment Table
		$this->insertIntoCommentTable("aicrm_ticketcomments",'ticketid');
		//Inserting into aicrm_attachments
		//$this->insertIntoAttachment($this->id,'HelpDesk');
		$return_action = $_REQUEST['return_action'];
		$for_module = $_REQUEST['return_module'];
		$for_crmid  = $_REQUEST['return_id'];
		if ($return_action && $for_module && $for_crmid) {
			if ($for_module != 'Accounts' && $for_module != 'Contacts' && $for_module != 'Products') {
				parent::save_related_module($for_module, $for_crmid, $module, $this->id);
			}
		}

		$this->insertIntoAttachment($this->id,'HelpDesk');
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
		$sql = "select * from aicrm_ticketcomments where ticketid=? order by createdtime desc";
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
	 *This function is used to add the aicrm_attachments. This will call the function uploadAndSaveFile which will upload the attachment into the server and save that attachment information in the database.
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

				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}
		
		//Remove the deleted aicrm_attachments from db - Products
		if($module == 'HelpDesk' && $_REQUEST['del_file_list'] != '')
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
	/*function insertIntoAttachment($id,$module)
	{
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;

		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				$files['original_name'] = vtlib_purify($_REQUEST[$fileindex.'_hidden']);
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}*/


	/**	Function used to get the sort order for HelpDesk listview
	 *	@return string	$sorder	- first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['HELPDESK_SORT_ORDER'] if this session value is empty then default sort order will be returned.
	 */
	function getSortOrder()
	{
		global $log;
                $log->debug("Entering getSortOrder() method ...");
		if(isset($_REQUEST['sorder']))
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['HELPDESK_SORT_ORDER'] != '')?($_SESSION['HELPDESK_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**	Function used to get the order by value for HelpDesk listview
	 *	@return string	$order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['HELPDESK_ORDER_BY'] if this session value is empty then default order by will be returned.
	 */
	function getOrderBy()
	{
		global $log;
                $log->debug("Entering getOrderBy() method ...");

		$use_default_order_by = '';
		if(PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}

		if (isset($_REQUEST['order_by'])){
			$order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
		
		}else{
			$order_by = (($_SESSION['HELPDESK_ORDER_BY'] != '')?($_SESSION['HELPDESK_ORDER_BY']):($use_default_order_by));
		}


		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}

	/**     Function to get the Ticket History information as in array format
	 *	@param int $ticketid - ticket id
	 *	@return array - return an array with title and the ticket history informations in the following format
		array(
			header=>array('0'=>'title'),
			entries=>array('0'=>'info1','1'=>'info2',etc.,)
		     )
	 */

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
	              CASE
                  WHEN ( aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name
                  ELSE aicrm_groups.groupname END AS user_name FROM aicrm_jobs
                  LEFT JOIN aicrm_jobscf ON aicrm_jobs.jobid = aicrm_jobscf.jobid
                  INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid
                  LEFT JOIN aicrm_troubletickets ON aicrm_troubletickets.ticketid = aicrm_jobs.ticketid
                  LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                  LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
                  WHERE aicrm_crmentity.deleted = 0 AND aicrm_jobs.ticketid = " . $id;
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);
        if ($return_value == null) $return_value = Array();
        $return_value['CUSTOM_BUTTON'] = $button;

        $log->debug("Exiting get_contacts method ...");
        return $return_value;
    }

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
				$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString($singular_modname, $related_module) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Task\";' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname, $related_module) ."'>&nbsp;";
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
			LEFT JOIN aicrm_troubletickets ON aicrm_troubletickets.ticketid = aicrm_activity.event_id
			WHERE aicrm_troubletickets.ticketid = ".$id."
			AND aicrm_crmentity.deleted = 0
			 ";
	
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
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
			AND aicrm_questionnaireanswer.event_id = ".$id;
        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

        $log->debug("Exiting get_point method ...");
        return $return_value;
    }
    
	function get_ticket_history($ticketid)
	{
		global $log, $adb;
		$log->debug("Entering into get_ticket_history($ticketid) method ...");

		$query="select title,update_log from aicrm_troubletickets where ticketid=?";
		$result=$adb->pquery($query, array($ticketid));
		$update_log = $adb->query_result($result,0,"update_log");

		$splitval = split('--//--',trim($update_log,'--//--'));

		$header[] = $adb->query_result($result,0,"title");

		$return_value = Array('header'=>$header,'entries'=>$splitval);

		$log->debug("Exiting from get_ticket_history($ticketid) method ...");

		return $return_value;
	}

	/**	Function to get the ticket comments as a array
	 *	@param  int   $ticketid - ticketid
	 *	@return array $output - array(
						[$i][comments]    => comments
						[$i][owner]       => name of the user or customer who made the comment
						[$i][createdtime] => the comment created time
					     )
				where $i = 0,1,..n which are all made for the ticket
	**/
	function get_ticket_comments_list($ticketid)
	{
		global $log;
		$log->debug("Entering get_ticket_comments_list(".$ticketid.") method ...");
		 $sql = "select * from aicrm_ticketcomments where ticketid=? order by createdtime DESC";
		 $result = $this->db->pquery($sql, array($ticketid));
		 $noofrows = $this->db->num_rows($result);
		 for($i=0;$i<$noofrows;$i++)
		 {
			 $ownerid = $this->db->query_result($result,$i,"ownerid");
			 $ownertype = $this->db->query_result($result,$i,"ownertype");
			 if($ownertype == 'user')
				 $name = getUserName($ownerid);
			 elseif($ownertype == 'customer')
			 {
				 $sql1 = 'select * from aicrm_portalinfo where id=?';
				 $name = $this->db->query_result($this->db->pquery($sql1, array($ownerid)),0,'user_name');
			 }

			 $output[$i]['comments'] = nl2br($this->db->query_result($result,$i,"comments"));
			 $output[$i]['owner'] = $name;
			 $output[$i]['createdtime'] = $this->db->query_result($result,$i,"createdtime");
		 }
		$log->debug("Exiting get_ticket_comments_list method ...");
		 return $output;
	 }

	/**	Function to process the list query and return the result with number of rows
	 *	@param  string $query - query
	 *	@return array  $response - array(	list           => array(
											$i => array(key => val)
									       ),
							row_count      => '',
							next_offset    => '',
							previous_offset	=>''
						)
		where $i=0,1,..n & key = ticketid, title, firstname, ..etc(range_fields) & val = value of the key from db retrieved row
	**/
	function process_list_query($query)
	{
		global $log;
		$log->debug("Entering process_list_query(".$query.") method ...");

   		$result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
		$list = Array();
	        $rows_found =  $this->db->getRowCount($result);
        	if($rows_found != 0)
	        {
			$ticket = Array();
			for($index = 0 , $row = $this->db->fetchByAssoc($result, $index); $row && $index <$rows_found;$index++, $row = $this->db->fetchByAssoc($result, $index))
			{
		                foreach($this->range_fields as $columnName)
                		{
		                	if (isset($row[$columnName]))
					{
			                	$ticket[$columnName] = $row[$columnName];
                    			}
		                       	else
				        {
		                        	$ticket[$columnName] = "";
			                }
	     			}
    		                $list[] = $ticket;
                	}
        	}

		$response = Array();
	        $response['list'] = $list;
        	$response['row_count'] = $rows_found;
	        $response['next_offset'] = $next_offset;
        	$response['previous_offset'] = $previous_offset;

		$log->debug("Exiting process_list_query method ...");
	        return $response;
	}

	/**	Function to get the HelpDesk field labels in caps letters without space
	 *	@return array $mergeflds - array(	key => val	)    where   key=0,1,2..n & val = ASSIGNEDTO,RELATEDTO, .,etc
	**/
	function getColumnNames_Hd()
	{
		global $log,$current_user;
		$log->debug("Entering getColumnNames_Hd() method ...");
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql1 = "select fieldlabel from aicrm_field where tabid=13 and block <> 30 and aicrm_field.uitype <> '61' and aicrm_field.presence in (0,2)";
			$params1 = array();
		}else
		{
			$profileList = getCurrentUserProfileList();
			$sql1 = "select aicrm_field.fieldid,fieldlabel from aicrm_field inner join aicrm_profile2field on aicrm_profile2field.fieldid=aicrm_field.fieldid inner join aicrm_def_org_field on aicrm_def_org_field.fieldid=aicrm_field.fieldid where aicrm_field.tabid=13 and aicrm_field.block <> 30 and aicrm_field.uitype <> '61' and aicrm_field.displaytype in (1,2,3,4) and aicrm_profile2field.visible=0 and aicrm_def_org_field.visible=0 and aicrm_field.presence in (0,2)";
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
		$log->debug("Exiting getColumnNames_Hd method ...");
		return $mergeflds;
	}

	/**     Function to get the list of comments for the given ticket id
	 *      @param  int  $ticketid - Ticket id
	 *      @return list $list - return the list of comments and comment informations as a html output where as these comments and comments informations will be formed in div tag.
	**/
	

	/**     Function to get the Customer Name who has made comment to the ticket from the customer portal
	 *      @param  int    $id   - Ticket id
	 *      @return string $customername - The contact name
	**/
	function getCustomerName($id)
	{
		global $log;
		$log->debug("Entering getCustomerName(".$id.") method ...");
        	global $adb;
	        $sql = "select * from aicrm_portalinfo inner join aicrm_troubletickets on aicrm_troubletickets.parent_id = aicrm_portalinfo.id where aicrm_troubletickets.ticketid=?";
        	$result = $adb->pquery($sql, array($id));
	        $customername = $adb->query_result($result,0,'user_name');
		$log->debug("Exiting getCustomerName method ...");
        	return $customername;
	}
	//Pavani: Function to create, export query for helpdesk module
        /** Function to export the ticket records in CSV Format
        * @param reference variable - where condition is passed when the query is executed
        * Returns Export Tickets Query.
        */
	function create_export_query($where)
	{
	        global $log;
	        global $current_user;
	        $log->debug("Entering create_export_query(".$where.") method ...");

	        include("include/utils/ExportUtils.php");

	        //To get the Permitted fields query and the permitted fields list
	        $sql = getPermittedFieldsQuery("HelpDesk", "detail_view");
	        $fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list, case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name FROM $this->entity_table
					INNER JOIN aicrm_troubletickets ON aicrm_troubletickets.ticketid =aicrm_crmentity.crmid
					LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_troubletickets.accountid
					LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_troubletickets.contactid
					LEFT JOIN aicrm_ticketcf ON aicrm_ticketcf.ticketid=aicrm_troubletickets.ticketid
					LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
					LEFT JOIN aicrm_users ON aicrm_users.id=aicrm_crmentity.smownerid and aicrm_users.status='Active'					
					LEFT JOIN aicrm_products ON aicrm_products.productid=aicrm_troubletickets.product_id 
					LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
	       			LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
					";
	       
	       $query .= setFromQuery("HelpDesk");
		   $where_auto="   aicrm_crmentity.deleted = 0 ";

			if($where != "")
				$query .= "  WHERE ($where) AND ".$where_auto;
			else
				$query .= "  WHERE ".$where_auto;
			require('user_privileges/user_privileges_'.$current_user->id.'.php');
	        require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	        //we should add security check when the user has Private Access
	        if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[13] == 3)
	        {
	                //Added security check to get the permitted records only
	                $query = $query." ".getListViewSecurityParameter("HelpDesk");
	        }


	        $log->debug("Exiting create_export_query method ...");
	        return $query;
	}


	/**	Function used to get the Activity History
	 *	@param	int	$id - ticket id to which we want to display the activity history
	 *	@return  array	- return an array which will be returned from the function getHistory
	 */
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$query = "SELECT aicrm_activity.activityid, aicrm_activity.subject, aicrm_activity.status, aicrm_activity.eventstatus, aicrm_activity.date_start, aicrm_activity.due_date,aicrm_activity.time_start,aicrm_activity.time_end,aicrm_activity.activitytype, aicrm_troubletickets.ticketid, aicrm_troubletickets.title, aicrm_crmentity.modifiedtime,aicrm_crmentity.createdtime, aicrm_crmentity.description,
case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
				from aicrm_activity
				inner join aicrm_seactivityrel on aicrm_seactivityrel.activityid= aicrm_activity.activityid
				inner join aicrm_troubletickets on aicrm_troubletickets.ticketid = aicrm_seactivityrel.crmid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_activity.activityid
                                left join aicrm_groups on aicrm_groups.groupid=aicrm_crmentity.smownerid
				left join aicrm_users on aicrm_users.id=aicrm_crmentity.smownerid
				where (aicrm_activity.activitytype = 'Meeting' or aicrm_activity.activitytype='Call' or aicrm_activity.activitytype='Task')
				and (aicrm_activity.status = 'Completed' or aicrm_activity.status = 'Deferred' or (aicrm_activity.eventstatus = 'Held' and aicrm_activity.eventstatus != ''))
				and aicrm_seactivityrel.crmid=".$id."
                                and aicrm_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php
		$log->debug("Entering get_history method ...");
		return getHistory('HelpDesk',$query,$id);
	}

	/** Function to get the update ticket history for the specified ticketid
	  * @param $id -- $ticketid:: Type Integer
	 */
	function constructUpdateLog($focus, $mode, $assigned_group_name, $assigntype)
	{
		global $adb;
		global $current_user;

		if($mode != 'edit')//this will be updated when we create new ticket
		{
			$updatelog = "Case created. Assigned to ";

			if(!empty($assigned_group_name) && $assigntype == 'T')
			{
				$updatelog .= " group ".(is_array($assigned_group_name)? $assigned_group_name[0] : $assigned_group_name);
			}
			elseif($focus->column_fields['assigned_user_id'] != '')
			{
				$updatelog .= " user ".getUserName($focus->column_fields['assigned_user_id']);
			}
			else
			{
				$updatelog .= " user ".getUserName($current_user->id);
			}

			$fldvalue = date("l dS F Y h:i:s A").' by '.$current_user->user_name;
			$updatelog .= " -- ".$fldvalue."--//--";
		}
		else
		{
			$ticketid = $focus->id;

			//First retrieve the existing information
			$tktresult = $adb->pquery("select * from aicrm_troubletickets where ticketid=?", array($ticketid));
			$crmresult = $adb->pquery("select * from aicrm_crmentity where crmid=?", array($ticketid));

			$updatelog = decode_html($adb->query_result($tktresult,0,"update_log"));

			$old_owner_id = $adb->query_result($crmresult,0,"smownerid");
			$old_status = $adb->query_result($tktresult,0,"status");
			$old_priority = $adb->query_result($tktresult,0,"priority");
			$old_severity = $adb->query_result($tktresult,0,"severity");
			$old_category = $adb->query_result($tktresult,0,"category");

			//Assigned to change log
			if($focus->column_fields['assigned_user_id'] != $old_owner_id)
			{
				$owner_name = getOwnerName($focus->column_fields['assigned_user_id']);
				if($assigntype == 'T')
					$updatelog .= ' Transferred to group '.$owner_name.'\.';
				else
					$updatelog .= ' Transferred to user '.decode_html($owner_name).'\.'; // Need to decode UTF characters which are migrated from versions < 5.0.4.
			}
			//Status change log
			if($old_status != $focus->column_fields['ticketstatus'] && $focus->column_fields['ticketstatus'] != '')
			{
				$updatelog .= ' Status Changed to '.$focus->column_fields['ticketstatus'].'\.';
			}
			//Priority change log
			if($old_priority != $focus->column_fields['ticketpriorities'] && $focus->column_fields['ticketpriorities'] != '')
			{
				$updatelog .= ' Priority Changed to '.$focus->column_fields['ticketpriorities'].'\.';
			}
			//Severity change log
			if($old_severity != $focus->column_fields['ticketseverities'] && $focus->column_fields['ticketseverities'] != '')
			{
				$updatelog .= ' Severity Changed to '.$focus->column_fields['ticketseverities'].'\.';
			}
			//Category change log
			if($old_category != $focus->column_fields['ticketcategories'] && $focus->column_fields['ticketcategories'] != '')
			{
				$updatelog .= ' Category Changed to '.$focus->column_fields['ticketcategories'].'\.';
			}

			$updatelog .= ' -- '.date("l dS F Y h:i:s A").' by '.$current_user->user_name.'--//--';
		}
		return $updatelog;
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

		$rel_table_arr = Array("Activities"=>"aicrm_seactivityrel","Attachments"=>"aicrm_seattachmentsrel","Documents"=>"aicrm_senotesrel");

		$tbl_field_arr = Array("aicrm_seactivityrel"=>"activityid","aicrm_seattachmentsrel"=>"attachmentsid","aicrm_senotesrel"=>"notesid");

		$entity_tbl_field_arr = Array("aicrm_seactivityrel"=>"crmid","aicrm_seattachmentsrel"=>"crmid","aicrm_senotesrel"=>"crmid");

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
		//echo $module." && ".$secmodule; exit;
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_troubletickets","ticketid");

		$query .=" left join aicrm_crmentity as aicrm_crmentityHelpDesk on aicrm_crmentityHelpDesk.crmid=aicrm_troubletickets.ticketid and aicrm_crmentityHelpDesk.deleted=0
				left join aicrm_ticketcf on aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
				left join aicrm_crmentity as aicrm_crmentityRelHelpDesk on aicrm_crmentityRelHelpDesk.crmid = aicrm_troubletickets.ticketid
				left join aicrm_account as aicrm_accountRelHelpDesk on aicrm_accountRelHelpDesk.accountid=aicrm_crmentityRelHelpDesk.crmid
				left join aicrm_contactdetails as aicrm_contactdetailsRelHelpDesk on aicrm_contactdetailsRelHelpDesk.contactid= aicrm_crmentityRelHelpDesk.crmid
				left join aicrm_products as aicrm_productsRel on aicrm_productsRel.productid = aicrm_troubletickets.product_id
				left join aicrm_groups as aicrm_groupsHelpDesk on aicrm_groupsHelpDesk.groupid = aicrm_crmentityHelpDesk.smownerid
                LEFT JOIN aicrm_account AS aicrm_accountHelpDesk ON aicrm_accountHelpDesk.accountid = aicrm_troubletickets.accountid


                left join aicrm_users as aicrm_usersHelpDesk on aicrm_usersHelpDesk.id = aicrm_crmentityHelpDesk.smownerid
                left join aicrm_users as aicrm_usersCreatorHelpDesk on aicrm_usersCreatorHelpDesk.id = aicrm_crmentityHelpDesk.smcreatorid
                left join aicrm_users as aicrm_usersModifiedHelpDesk on aicrm_usersModifiedHelpDesk.id = aicrm_crmentityHelpDesk.modifiedby
                ";
		if(($module=='Contacts' && $secmodule=='HelpDesk')) {
            $query .= " LEFT JOIN aicrm_contactdetails AS aicrm_contactdetailsHelpDesk on aicrm_contactdetailsHelpDesk.accountid = aicrm_troubletickets.accountid";
        }

        if(($module=='Accounts' && $secmodule=='HelpDesk')) {
            $query .= " LEFT JOIN aicrm_contactdetails AS aicrm_contactdetailsHelpDesk ON aicrm_troubletickets.contactid = aicrm_contactdetailsHelpDesk.contactid";
        }
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
			"Calendar" => array("aicrm_seactivityrel"=>array("crmid","activityid"),"aicrm_troubletickets"=>"ticketid"),
			"Documents" => array("aicrm_senotesrel"=>array("crmid","notesid"),"aicrm_troubletickets"=>"ticketid"),
			"Products" => array("aicrm_troubletickets"=>array("product_id","ticketid"),"aicrm_troubletickets"=>"ticketid"),
            "Job" => array("aicrm_jobs"=>array("ticketid","jobid"),"aicrm_troubletickets"=>"ticketid"),

		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

        if($return_module == 'Contacts') {
            $relation_query = 'UPDATE aicrm_troubletickets SET contactid=0 WHERE ticketid=?';
            $this->db->pquery($relation_query, array($id));
        } elseif($return_module == 'Accounts') {
			$sql = 'UPDATE aicrm_troubletickets SET parent_id=0 WHERE ticketid=?';
			$this->db->pquery($sql, array($id));
			$se_sql= 'DELETE FROM aicrm_seticketsrel WHERE ticketid=?';
			$this->db->pquery($se_sql, array($id));
		} elseif($return_module == 'Products') {
			$sql = 'UPDATE aicrm_troubletickets SET product_id=0 WHERE ticketid=?';
			$this->db->pquery($sql, array($id));
		} else {
			$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
			$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
			$this->db->pquery($sql, $params);
		}
	}

}
?>