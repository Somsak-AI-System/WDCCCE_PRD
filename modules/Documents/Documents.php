<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');
require_once('include/upload_file.php');

// Note is used to store customer information.
class Documents extends CRMEntity {
	
	var $log;
	var $db;
	var $table_name = "aicrm_notes";
	var $table_index= 'notesid';
	var $default_note_name_dom = array('Meeting aicrm_notes', 'Reminder');
	var $table_comment = "aicrm_notescomments";

	var $tab_name = Array('aicrm_crmentity','aicrm_notes');
	var $tab_name_index = Array('aicrm_crmentity'=>'crmid','aicrm_notes'=>'notesid','aicrm_senotesrel'=>'notesid','aicrm_notescomments'=>'notesid');
	
	var $column_fields = Array();

    var $sortby_fields = Array('notes_title','modifiedtime','filename','createdtime','lastname','filedownloadcount','smownerid');		  

	// This is used to retrieve related aicrm_fields from form posts.
	var $additional_column_fields = Array('', '', '', '');

	// This is the list of aicrm_fields that are in the lists.
	var $list_fields = Array(
				'Document No'=>Array('notes'=>'note_no'),
				'Title'=>Array('notes'=>'notes_title'),
				'File'=>Array('notes'=>'filename'),
				'Modified Time'=>Array('crmentity'=>'modifiedtime'),
				'Assigned To' => Array('crmentity'=>'assigned_user_id'),
			);
	var $list_fields_name = Array(
				'Document No'=>'note_no',
				'Title'=>'notes_title',
				'File'=>'filename',
				'Modified Time'=>'modifiedtime',
				'Assigned To'=>'assigned_user_id',
			);	
				     
	var $search_fields = Array(
				'Document No' => Array('notes'=>'note_no'),
				'Title' => Array('notes'=>'notes_title'),
                'File'=>Array('notes'=>'filename'),
				'Modified Time'=>Array('crmentity'=>'modifiedtime'),
				'Assigned To' => Array('crmentity'=>'smownerid'),
			);
	
	var $search_fields_name = Array(
				'Document No' => 'note_no',
				'Title' => 'notes_title',
                'File'=>'filename',
				'Modified Time' => 'modifiedtime',
				'Assigned To' => 'assigned_user_id',
			);		
							     
	var $list_link_field= 'note_no';
	var $old_filename = '';
	//var $groupTable = Array('aicrm_notegrouprelation','notesid');

	var $mandatory_fields = Array('notes_title','createdtime' ,'modifiedtime','filename','filesize','filetype','filedownloadcount','assigned_user_id');
	
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'title';
	var $default_sort_order = 'ASC';
	function Documents() {
		$this->log = LoggerManager::getLogger('notes');
		$this->log->debug("Entering Documents() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Documents');
		$this->log->debug("Exiting Documents method ...");
	}

	function save_module($module)
	{
		global $log,$adb,$upload_badext;
		$insertion_mode = $this->mode;
		if(isset($this->parentid) && $this->parentid != '')
			$relid =  $this->parentid;		
		//inserting into aicrm_senotesrel
		if(isset($relid) && $relid != '')
		{
			$this->insertintonotesrel($relid,$this->id);
		}
		$filetype_fieldname = $this->getFileTypeFieldName();
		$filename_fieldname = $this->getFile_FieldName();
		if($this->column_fields[$filetype_fieldname] == 'I' ){
			if($_FILES[$filename_fieldname]['name'] != ''){
				$errCode=$_FILES[$filename_fieldname]['error'];
					if($errCode == 0){
						foreach($_FILES as $fileindex => $files)
						{
							if($files['name'] != '' && $files['size'] > 0){
								$filename = $_FILES[$filename_fieldname]['name'];
								$filename = from_html(preg_replace('/\s+/', '_', $filename));
								$filetype = $_FILES[$filename_fieldname]['type'];
								$filesize = $_FILES[$filename_fieldname]['size'];
								$filelocationtype = 'I';
								$binFile = preg_replace('/\s+/', '_', $filename);//replace space with _ in filename
								$ext_pos = strrpos($binFile, ".");
								$ext = substr($binFile, $ext_pos + 1);
								if (in_array(strtolower($ext), $upload_badext)) {
									$binFile .= ".txt";
								}
								$filename = ltrim(basename(" ".$binFile)); //allowed filename like UTF-8 characters 
							}
						}
				
					}
			}elseif($this->mode == 'edit') {
				$fileres = $adb->pquery("select filetype, filesize,filename,filedownloadcount,filelocationtype from aicrm_notes where notesid=?", array($this->id));
				if ($adb->num_rows($fileres) > 0) {
					$filename = $adb->query_result($fileres, 0, 'filename');
					$filetype = $adb->query_result($fileres, 0, 'filetype');
					$filesize = $adb->query_result($fileres, 0, 'filesize');
					$filedownloadcount = $adb->query_result($fileres, 0, 'filedownloadcount');
					$filelocationtype = $adb->query_result($fileres, 0, 'filelocationtype');
				}
			}elseif($this->column_fields[$filename_fieldname]) {
				$filename = $this->column_fields[$filename_fieldname];
				$filesize = $this->column_fields['filesize'];
				$filetype = $this->column_fields['filetype'];
				$filelocationtype = $this->column_fields[$filetype_fieldname];
				$filedownloadcount = 0;
			}
		} else{
			$filelocationtype = 'E';
			$filename = $this->column_fields[$filename_fieldname];
			// If filename does not has the protocol prefix, default it to http://
			// Protocol prefix could be like (https://, smb://, file://, \\, smb:\\,...) 
			if(!empty($filename) && !preg_match('/^\w{1,5}:\/\/|^\w{0,3}:?\\\\\\\\/', trim($filename), $match)) {
				$filename = "http://$filename";
			}
			$filetype = '';
			$filesize = 0;
			$filedownloadcount = null;
		}
		$query = "UPDATE aicrm_notes SET filename = ? ,filesize = ?, filetype = ? , filelocationtype = ? , filedownloadcount = ? WHERE notesid = ?";
 		$re=$adb->pquery($query,array($filename,$filesize,$filetype,$filelocationtype,$filedownloadcount,$this->id));
		//Inserting into attachments table
		if($filelocationtype == 'I') {
			$this->insertIntoAttachment($this->id,'Documents');
		}else{
			$query = "delete from aicrm_seattachmentsrel where crmid = ?";
			$qparams = array($this->id);
			$adb->pquery($query, $qparams);
		}	

		$this->insertIntoCommentTable("aicrm_notescomments",'notesid');
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
		$sql = "select * from aicrm_notescomments where notesid=? order by createdtime desc";
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
	function insertIntoAttachment($id,$module)
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
	}

	/**    Function used to get the sort order for Documents listview
	*      @return string  $sorder - first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['NOTES_SORT_ORDER'] if this session value is empty then default sort order will be returned.
	*/
	function getSortOrder()
	{
		global $log;
		$log->debug("Entering getSortOrder() method ...");
		if(isset($_REQUEST['sorder']))
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['NOTES_SORT_ORDER'] != '')?($_SESSION['NOTES_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**     Function used to get the order by value for Documents listview
	*       @return string  $order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['NOTES_ORDER_BY'] if this session value is empty then default order by will be returned.
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
			$order_by = (($_SESSION['NOTES_ORDER_BY'] != '')?($_SESSION['NOTES_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}


	/** Function to export the notes in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Documents Query.
	*/
	function create_export_query($where)
	{
		global $log,$current_user;
		$log->debug("Entering create_export_query(". $where.") method ...");

		include("include/utils/ExportUtils.php");
		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Documents", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		
		$query = "SELECT $fields_list, case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name FROM aicrm_notes
				inner join aicrm_crmentity 
					on aicrm_crmentity.crmid=aicrm_notes.notesid 
				LEFT JOIN aicrm_attachmentsfolder on aicrm_notes.folderid=aicrm_attachmentsfolder.folderid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
				LEFT JOIN aicrm_groups ON aicrm_crmentity.smownerid=aicrm_groups.groupid 
				LEFT JOIN aicrm_users as aicrm_usersModified on aicrm_crmentity.modifiedby = aicrm_usersModified.id
                LEFT JOIN aicrm_users as aicrm_usersCreator on aicrm_crmentity.smcreatorid = aicrm_usersCreator.id
				"
				;
	
				$where_auto=" aicrm_crmentity.deleted=0"; 
				if($where != "")
					$query .= "  WHERE ($where) AND ".$where_auto;
				else
					$query .= "  WHERE ".$where_auto;
					
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		//we should add security check when the user has Private Access
		$tabid = getTabid("Documents");
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tabid] == 3)
		{
			//Added security check to get the permitted records only
			$query = $query." ".getListViewSecurityParameter("Documents");
		}

		
		$log->debug("Exiting create_export_query method ...");
		        return $query;
	}	
	
	function del_create_def_folder($query)
	{
		global $adb;
		$dbQuery = $query." and aicrm_attachmentsfolder.folderid = 0";
		$dbresult = $adb->pquery($dbQuery,array());
		$noofnotes = $adb->num_rows($dbresult);
		if($noofnotes > 0)
		{
            $folderQuery = "select folderid from aicrm_attachmentsfolder";
            $folderresult = $adb->pquery($folderQuery,array());
            $noofdeffolders = $adb->num_rows($folderresult);
            if($noofdeffolders == 0)
            {
			    $insertQuery = "insert into aicrm_attachmentsfolder values (0,'Default','Contains all attachments for which a folder is not set',1,0)";
			    $insertresult = $adb->pquery($insertQuery,array());
            }
		}
		
	}
	
	function insertintonotesrel($relid,$id)
	{
		global $adb;
		$dbQuery = "insert into aicrm_senotesrel values ( ?, ? )";
		$dbresult = $adb->pquery($dbQuery,array($relid,$id));
	}
		
	/*
	 * Function to get the primary query part of a report
	 * @param - $module Primary module name
	 * returns the query string formed on fetching the related data for report for primary module
	 */
	function generateReportsQuery($module){
		$moduletable = $this->table_name;
		$moduleindex = $this->tab_name_index[$moduletable];
		$query = "from $moduletable 
        inner join aicrm_crmentity on aicrm_crmentity.crmid=$moduletable.$moduleindex
        inner join aicrm_attachmentsfolder on aicrm_attachmentsfolder.folderid=$moduletable.folderid
		left join aicrm_groups as aicrm_groups".$module." on aicrm_groups".$module.".groupid = aicrm_crmentity.smownerid
        left join aicrm_users as aicrm_users".$module." on aicrm_users".$module.".id = aicrm_crmentity.smownerid
		left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
        left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid";
        return $query;          
	}
	
	/*
	 * Function to get the secondary query part of a report 
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		// echo $module." / ".$secmodule; exit;
		$query = $this->getRelationQuery($module,$secmodule,"aicrm_notes","notesid");
		$query .=" left join aicrm_crmentity as aicrm_crmentityDocuments on aicrm_crmentityDocuments.crmid=aicrm_notes.notesid and aicrm_crmentityDocuments.deleted=0 
		        left join aicrm_attachmentsfolder on aicrm_attachmentsfolder.folderid=aicrm_notes.folderid
				left join aicrm_groups as aicrm_groupsDocuments on aicrm_groupsDocuments.groupid = aicrm_crmentityDocuments.smownerid
				left join aicrm_users as aicrm_usersDocuments on aicrm_usersDocuments.id = aicrm_crmentityDocuments.smownerid
				left join aicrm_users as aicrm_usersModifiedDocuments on aicrm_crmentity.smcreatorid=aicrm_usersModifiedDocuments.id
                left join aicrm_users as aicrm_usersCreatorDocuments on aicrm_crmentity.smcreatorid=aicrm_usersCreatorDocuments.id
				 ";
		if($module=='Products' && $secmodule =='Documents'){
            //$query .= " LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.accountid = aicrm_products.accountid" aicrm_products ไม่มี accountid;
        }
		if($module=='HelpDesk' && $secmodule =='Documents'){
            $query .= " LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_troubletickets.contactid";
        }
        if($module=="Job" || $module=="Accounts" || $module=='Campaigns' || $module=='PriceList' || $module=='KnowledgeBase' || $module=='Quotes' || $module=='Competitor' || $module=='Errors' || $module=='Errorslist' || $module=='Sparepart' || $module=='Sparepartlist' || ($module=='HelpDesk' && $secmodule =='Documents') || ($module=='Products' && $secmodule =='Documents') || ($module=='Serial' && $secmodule =='Documents') || ($module=='Leads' && $secmodule =='Documents') || ($module=='Deal' && $secmodule =='Documents') || ($module=='Calendar' && $secmodule =='Documents') || ($module=='Questionnaire' && $secmodule =='Documents') || ($module=='Questionnairetemplate' && $secmodule =='Documents') || ($module=='Voucher' && $secmodule =='Documents') || ($module=='Promotion' && $secmodule =='Documents') || ($module=='Competitorproduct' && $secmodule =='Documents') || ($module=='Servicerequest' && $secmodule =='Documents') || ($module=='Questionnaireanswer' && $secmodule =='Documents') || ($module=='Faq' && $secmodule =='Documents') || ($module=='Projects' && $secmodule =='Documents') || $module == 'SmartSms' || $module == 'Smartemail' || $module == 'Smartquestionnaire'){
        }else{
            $query .= " LEFT JOIN aicrm_jobs ON aicrm_jobs.accountid = aicrm_contactdetails.accountid";
        }
        if($module=="Accounts" || $module=='Campaigns' || $module=='PriceList' || $module=='KnowledgeBase' || $module=='Quotes' || $module=='Job' || $module=='Competitor' || $module=='Errors' || $module=='Errorslist' || $module=='Sparepart' || $module=='Sparepartlist' || ($module=='HelpDesk' && $secmodule =='Documents') || ($module=='Products' && $secmodule =='Documents') || ($module=='Serial' && $secmodule =='Documents') || ($module=='Leads' && $secmodule =='Documents') || ($module=='Deal' && $secmodule =='Documents') || ($module=='Calendar' && $secmodule =='Documents') || ($module=='Questionnaire' && $secmodule =='Documents') || ($module=='Questionnairetemplate' && $secmodule =='Documents') || ($module=='Voucher' && $secmodule =='Documents') || ($module=='Promotion' && $secmodule =='Documents') || ($module=='Competitorproduct' && $secmodule =='Documents') || ($module=='Servicerequest' && $secmodule =='Documents') || ($module=='Questionnaireanswer' && $secmodule =='Documents') || ($module=='Faq' && $secmodule =='Documents') || ($module=='Projects' && $secmodule =='Documents') || $module == 'SmartSms' || $module == 'Smartemail' || $module == 'Smartquestionnaire'){

        }else {
            $query .= " LEFT JOIN aicrm_account AS aicrm_accountJob ON aicrm_accountJob.accountid = aicrm_jobs.accountid";
        }

		return $query;
	}

	/*
	 * Function to get the relation tables for related modules 
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array();
		return $rel_tables[$secmodule];
	}
	
	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;		
		/*//Backup Documents Related Records
		$se_q = 'SELECT crmid FROM aicrm_senotesrel WHERE notesid = ?';
		$se_res = $this->db->pquery($se_q, array($id));
		if ($this->db->num_rows($se_res) > 0) {
			for($k=0;$k < $this->db->num_rows($se_res);$k++)
			{
				$se_id = $this->db->query_result($se_res,$k,"crmid");
				$params = array($id, RB_RECORD_DELETED, 'aicrm_senotesrel', 'notesid', 'crmid', $se_id);
				$this->db->pquery('INSERT INTO aicrm_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
			}
		}
		$sql = 'DELETE FROM aicrm_senotesrel WHERE notesid = ?';
		$this->db->pquery($sql, array($id));*/
		
		parent::unlinkDependencies($module, $id);
	}
	
	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		
		$sql = 'DELETE FROM aicrm_senotesrel WHERE notesid = ? AND crmid = ?';
		$this->db->pquery($sql, array($id, $return_id));
			
		$sql = 'DELETE FROM aicrm_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
		$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
		$this->db->pquery($sql, $params);
	}


// Function to get fieldname for uitype 27 assuming that documents have only one file type field

	function getFileTypeFieldName(){
		global $adb,$log;
		$query = 'SELECT fieldname from aicrm_field where tabid = ? and uitype = ?';
		$tabid = getTabid('Documents');
		$filetype_uitype = 27;
		$res = $adb->pquery($query,array($tabid,$filetype_uitype));
		$fieldname = null;
		if(isset($res)){
			$rowCount = $adb->num_rows($res);
			if($rowCount > 0){
				$fieldname = $adb->query_result($res,0,'fieldname');
			}
		}
		return $fieldname;
		
	} 
	
//	Function to get fieldname for uitype 28 assuming that doc has only one file upload type
	
	function getFile_FieldName(){
		global $adb,$log;
		$query = 'SELECT fieldname from aicrm_field where tabid = ? and uitype = ?';
		$tabid = getTabid('Documents');
		$filename_uitype = 28;
		$res = $adb->pquery($query,array($tabid,$filename_uitype));
		$fieldname = null;
		if(isset($res)){
			$rowCount = $adb->num_rows($res);
			if($rowCount > 0){
				$fieldname = $adb->query_result($res,0,'fieldname');
			}
		}
		return $fieldname;
	}
	
	/**
	 * Check the existence of folder by folderid
	 */
	function isFolderPresent($folderid) {
		global $adb;
		$result = $adb->pquery("SELECT folderid FROM aicrm_attachmentsfolder WHERE folderid = ?", array($folderid));
		if(!empty($result) && $adb->num_rows($result) > 0) return true;
		return false;
	}
	
	/**
	 * Customizing the restore procedure.
	 */
	function restore($modulename, $id) {
		parent::restore($modulename, $id);
		
		global $adb;
		$fresult = $adb->pquery("SELECT folderid FROM aicrm_notes WHERE notesid = ?", array($id));
		if(!empty($fresult) && $adb->num_rows($fresult)) {
			$folderid = $adb->query_result($fresult, 0, 'folderid');
			if(!$this->isFolderPresent($folderid)) {
				// Re-link to default folder
				$adb->pquery("UPDATE aicrm_notes set folderid = 1 WHERE notesid = ?", array($id));
			}
		}
	}	
}	
?>