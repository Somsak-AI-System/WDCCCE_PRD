<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/
global $current_user;
require_once('include/utils/ListViewUtils.php');
require_once('modules/CustomView/CustomView.php');
require_once('include/DatabaseUtil.php');
require_once('include/utils/CommonUtils.php');
require('user_privileges/user_privileges_'.$current_user->id.'.php');

class Homestuff{
	var $userid;
	var $dashdetails=array();
	
	/**
	 * this is the constructor for the class
	 */	
	function Homestuff(){
		
	}
	
	/**
	 * this function adds a new widget information to the database
	 */
	function addStuff(){
		global $adb;
		global $current_user;
		global $current_language;
		$dashbd_strings = return_module_language($current_language, "Dashboard"); 

		$stuffid=$adb->getUniqueId('aicrm_homestuff');

		$queryseq="select max(stuffsequence)+1 as seq from aicrm_homestuff";
		$sequence=$adb->query_result($adb->pquery($queryseq, array()),0,'seq');
		//echo $sequence;
		if($this->defaulttitle != ""){
			$this->stufftitle = $this->defaulttitle;
		}
		$query="insert into aicrm_homestuff(stuffid, stuffsequence, stufftype, userid, visible, stufftitle) values(?, ?, ?, ?, ?, ?)";
		//echo $query;
		$params= array($stuffid,$sequence,$this->stufftype,$current_user->id,0,$this->stufftitle); 
		//print_r($params); exit;
		$result=$adb->pquery($query, $params);
		if(!$result){
			return false;
		}
		
		if($this->stufftype=="Module"){
			$fieldarray=explode(",",$this->fieldvalue);
			$querymod="insert into aicrm_homemodule(stuffid, modulename, maxentries, customviewid, setype) values(?, ?, ?, ?, ?)";
			$params = array($stuffid,$this->selmodule,$this->maxentries,$this->selFiltername,$this->selmodule);
			$result=$adb->pquery($querymod, $params);
			if(!$result){
				return false;
			}
			
			for($q=0;$q<sizeof($fieldarray);$q++){
				$queryfld="insert into aicrm_homemoduleflds values(? ,?);";
				$params = array($stuffid,$fieldarray[$q]);
				$result=$adb->pquery($queryfld, $params);
			}
			
			if(!$result){
				return false;
			}
		}else if($this->stufftype=="RSS"){
			$queryrss="insert into aicrm_homerss values(?,?,?)";
			$params = array($stuffid,$this->txtRss,$this->maxentries);
			$resultrss=$adb->pquery($queryrss, $params);
			if(!$resultrss){
				return false;
			}		
		}else if($this->stufftype=="DashBoard"){
			$querydb="insert into aicrm_homedashbd values(?,?,?)";
			$params = array($stuffid,$this->seldashbd,$this->seldashtype);
			$resultdb=$adb->pquery($querydb, $params);
			if(!$resultdb){
				return false;
			}		
		}else if($this->stufftype=="Default"){
			$querydef="insert into aicrm_homedefault values(?, ?)";
			$params = array($stuffid,$this->defaultvalue);
	       	$resultdef=$adb->pquery($querydef, $params);
			if(!$resultdef){
				return false;
			}		
		}else if($this->stufftype=='Notebook'){
			$userid = $current_user->id;
			$query="insert into aicrm_notebook_contents values(?,?,?)";
			$params= array($userid,$stuffid,'');
	       	$result=$adb->pquery($query, $params);
			if(!$result){
				return false;
			}
		}else if($this->stufftype=='URL'){
			$userid = $current_user->id;
			$query="insert into aicrm_homewidget_url values(?, ?)";
	       	$result=$adb->pquery($query, array($stuffid, $this->txtURL));
			if(!$result){
				return false;
			}
		}
	 	return "loadAddedDiv($stuffid,'".$this->stufftype."')";
	}
	
	/**
	 * this function returns the information about a widget in an array
	 * @return array(stuffid=>"id", stufftype=>"type", stufftitle=>"title")
	 */
	function getHomePageFrame(){
		global $adb;
		global $current_user;
		$querystuff ="select aicrm_homestuff.stuffid,stufftype,stufftitle,setype from aicrm_homestuff 
						left join aicrm_homedefault on aicrm_homedefault.stuffid=aicrm_homestuff.stuffid
						where visible=0 and userid=? order by stuffsequence desc";
		$resultstuff=$adb->pquery($querystuff, array($current_user->id));
		for($i=0;$i<$adb->num_rows($resultstuff);$i++){
			$modulename = $adb->query_result($resultstuff,$i,'setype');
			$stuffid = $adb->query_result($resultstuff,$i,'stuffid');
			$stufftype=$adb->query_result($resultstuff,$i,'stufftype');
			if(!empty($modulename) && $modulename!='NULL'){
				if(!vtlib_isModuleActive($modulename)){
					continue;
				}
			}elseif($stufftype == 'Module'){
				//check for setype in aicrm_homemodule table and hide if module is de-activated
				$sql = "select setype from aicrm_homemodule where stuffid=?";
				$result_setype = $adb->pquery($sql, array($stuffid));
				if($adb->num_rows($result_setype)>0){
					$module_name = $adb->query_result($result_setype, 0, "setype");
				}
				if(!empty($module_name) && $module_name!='NULL'){
					if(!vtlib_isModuleActive($module_name)){
						continue;
					}
				}
			}elseif($stufftype == 'DashBoard'){
				if(!vtlib_isModuleActive('Dashboard')){
					continue;
				}
			}elseif(!empty($stufftype) && $stufftype=='RSS'){
				if(!vtlib_isModuleActive($stufftype)){
					continue;
				}
			}
			
			$nontrans_stufftitle = $adb->query_result($resultstuff,$i,'stufftitle');
			$trans_stufftitle = getTranslatedString($nontrans_stufftitle);
			$stufftitle=decode_html($trans_stufftitle);
			if(strlen($stufftitle)>100){
				$stuff_title=substr($stufftitle,0,97)."...";
			}else{
				$stuff_title = $stufftitle;
			}
			
			if($stufftype == 'Default' && $nontrans_stufftitle != 'Home Page Dashboard' && $nontrans_stufftitle != 'Tag Cloud'){
				if($modulename != 'NULL'){
					if(isPermitted($modulename,'index') == "yes"){
						$count_entries = $this->getDefaultDetails($stuffid,'calculateCnt');
						if($count_entries > 0){
							$homeval[]=Array('Stuffid'=>$stuffid,'Stufftype'=>$stufftype,'Stufftitle'=>$stuff_title);
						}
					}
				}else{
					$count_entries = $this->getDefaultDetails($stuffid,'calculateCnt');
					if($count_entries > 0){
						$homeval[]=Array('Stuffid'=>$stuffid,'Stufftype'=>$stufftype,'Stufftitle'=>$stuff_title);
					}
				}
			}else if($stufftype == 'Tag Cloud'){
				$freetag = new freetag();
				if($freetag->get_tag_cloud_html("",$current_user->id) != ''){
					$homeval[]=Array('Stuffid'=>$stuffid,'Stufftype'=>$stufftype,'Stufftitle'=>$stuff_title);
				}
			}else if($modulename != 'NULL'){
				if(isPermitted($modulename,'index') == "yes"){
					$homeval[]=Array('Stuffid'=>$stuffid,'Stufftype'=>$stufftype,'Stufftitle'=>$stuff_title);
				}
			}else{
				$homeval[]=Array('Stuffid'=>$stuffid,'Stufftype'=>$stufftype,'Stufftitle'=>$stuff_title);
			}
		}
		$homeframe=$homeval;
		return $homeframe;
	}
	
	/**
	 * this function returns information about the given widget in an array format
	 * @return array(stuffid=>"id", stufftype=>"type", stufftitle=>"title")
	 */
	function getSelectedStuff($sid,$stuffType){
		global $adb;
		global $current_user;
		$querystuff="select stufftitle from aicrm_homestuff where visible=0 and stuffid=?";	
		$resultstuff=$adb->pquery($querystuff, array($sid));
		$homeval=Array('Stuffid'=>$sid,'Stufftype'=>$stuffType,'Stufftitle'=>$adb->query_result($resultstuff,0,'stufftitle'));
		return $homeval;
	}
	
	/**
	 * this function only returns the widget contents for a given widget
	 */
	function getHomePageStuff($sid,$stuffType){
		global $adb;
		global $current_user;
		$header=Array();
		if($stuffType=="Module"){
			$details=$this->getModuleFilters($sid);
		}else if($stuffType=="RSS"){
			$details=$this->getRssDetails($sid);
		}else if($stuffType=="DashBoard" && vtlib_isModuleActive("Dashboard")){
			$details=$this->getDashDetails($sid);
		}else if($stuffType=="Default"){
			$details=$this->getDefaultDetails($sid,'');
		}
		return $details;
	}
	
	/**
	 * this function returns the widget information for an module type widget
	 */
	private function getModuleFilters($sid){
		global $adb,$current_user;
		$querycvid="select aicrm_homemoduleflds.fieldname,aicrm_homemodule.* from aicrm_homemoduleflds
					left join aicrm_homemodule on aicrm_homemodule.stuffid=aicrm_homemoduleflds.stuffid
					where aicrm_homemoduleflds.stuffid=?";
		$resultcvid=$adb->pquery($querycvid, array($sid));
		$modname=$adb->query_result($resultcvid,0,"modulename");
		$cvid=$adb->query_result($resultcvid,0,"customviewid");
		$maxval=$adb->query_result($resultcvid,0,"maxentries");
		$column_count = $adb->num_rows($resultcvid);
		$cvid_check_query = $adb->pquery("SELECT * FROM aicrm_customview WHERE cvid = ?",array($cvid));
		if(isPermitted($modname,'index') == "yes"){	
			if($adb->num_rows($cvid_check_query)>0){
				$focus = CRMEntity::getInstance($modname);
					
				$oCustomView = new CustomView($modname);
				$listquery = getListQuery($modname);
				if(trim($listquery) == ''){
					$listquery = $focus->getListQuery($modname);
				}
				$query = $oCustomView->getModifiedCvListQuery($cvid,$listquery,$modname);
				$count_result = $adb->query(mkCountQuery($query));
				$noofrows = $adb->query_result($count_result,0,"count");
				$navigation_array = getNavigationValues(1, $noofrows, $maxval);
				
				//To get the current language file
				global $current_language,$app_strings;
				$fieldmod_strings = return_module_language($current_language, $modname);
				
				if($modname == 'Calendar'){
					$query .= "AND aicrm_activity.activitytype NOT IN ('Emails')";
				}
				
				if( $adb->dbType == "pgsql"){
					$list_result = $adb->query($query. " OFFSET 0 LIMIT ".$maxval);
				}else{
					$list_result = $adb->query($query. " LIMIT 0,".$maxval);
				}
				
				for($l=0;$l < $column_count;$l++){
					$fieldinfo = $adb->query_result($resultcvid,$l,"fieldname");
					list($tabname,$colname,$fldname,$fieldmodlabel) = explode(":",$fieldinfo);
					
					$fieldheader=explode("_",$fieldmodlabel,2);
					$fldlabel=$fieldheader[1];
					$pos=strpos($fldlabel,"_");
					if($pos==true){
						$fldlabel=str_replace("_"," ",$fldlabel);
					}
					$field_label = isset($app_strings[$fldlabel])?$app_strings[$fldlabel]:(isset($fieldmod_strings[$fldlabel])?$fieldmod_strings[$fldlabel]:$fldlabel);
					$cv_presence = $adb->pquery("SELECT * from aicrm_cvcolumnlist WHERE cvid = ? and columnname LIKE '%".$fldname."%'", array($cvid));
					if($is_admin == false){
						$fld_permission = getFieldVisibilityPermission($modname,$current_user->id,$fldname);
					}
					if($fld_permission == 0 && $adb->num_rows($cv_presence)){ 
						$field_query = $adb->pquery("SELECT fieldlabel FROM aicrm_field WHERE fieldname = ? AND tablename = ? and aicrm_field.presence in (0,2)", array($fldname,$tabname));
						$field_label = $adb->query_result($field_query,0,'fieldlabel');
						$header[] = $field_label;
					}
					$fieldcolumns[$fldlabel] = Array($tabname=>$colname);
				}
				$listview_entries = getListViewEntries($focus,$modname,$list_result,$navigation_array,"","","EditView","Delete",$oCustomView,'HomePage',$fieldcolumns);
				$return_value =Array('ModuleName'=>$modname,'cvid'=>$cvid,'Maxentries'=>$maxval,'Header'=>$header,'Entries'=>$listview_entries);
				if(sizeof($header)!=0){
		       		return $return_value;
				}else{
		       		echo "Fields not found in Selected Filter";
				}
			}
			else{
				echo "<font color='red'>Filter You have Selected is Not Found</font>";
			}
 		}
		else{
			echo "<font color='red'>Permission Denied</font>";
		}
	}
	
	/**
	 * this function gets the detailed information about a rss widget
	 */
	private function getRssDetails($rid){
		global $mod_strings;
		if(isPermitted('Rss','index') == "yes"){
			require_once('modules/Rss/Rss.php');
			global $adb;
			$qry="select * from aicrm_homerss where stuffid=?";
			$res=$adb->pquery($qry, array($rid));
			$url=$adb->query_result($res,0,"url");
			$maxval=$adb->query_result($res,0,"maxentries");
			$oRss = new vtigerRSS();
			if($oRss->setRSSUrl($url)){
				$rss_html = $oRss->getListViewHomeRSSHtml($maxval);
			}else{
				$rss_html = "<strong>".$mod_strings['LBL_ERROR_MSG']."</strong>";
			}
			$return_value=Array('Maxentries'=>$maxval,'Entries'=>$rss_html);
		}else{
			echo "<font color='red'>Not Accessible</font>";
		}
		return $return_value;	
	}
	
	/**
	 * this function gets the detailed information of the dashboard widget
	 */
	function getDashDetails($did,$chart=''){
		global $adb;
		$qry="select * from aicrm_homedashbd where stuffid=?";
		$result=$adb->pquery($qry, array($did));
		$type=$adb->query_result($result,0,"dashbdname");
		$charttype=$adb->query_result($result,0,"dashbdtype");
		$dash=Array('DashType'=>$type,'Chart'=>$charttype);
		$this->dashdetails[$did]=$dash;
		$from_page='HomePage';
		if($chart==''){
			return $this->getdisplayChart($type,$charttype,$from_page);
		}else{
			return $dash;
		}
		
	}
	
	/**
	 * this function returns detailed information of the homepage big dashboard
	 */
	private function getdisplayChart($type,$Chart_Type,$from_page){
		require_once('modules/Dashboard/homestuff.php');
		$return_dash=dashboardDisplayCall($type,$Chart_Type,$from_page);
		return $return_dash;
	}
	
	private function getDefaultDetails($dfid,$calCnt){
		global $adb;
		$qry="select * from aicrm_homedefault where stuffid=?";
		$result=$adb->pquery($qry, array($dfid));
		$maxval=$adb->query_result($result,0,"maxentries");
		$hometype=$adb->query_result($result,0,"hometype");
		
		if($hometype=="ALVT" && vtlib_isModuleActive("Accounts")){
			include_once("modules/Accounts/ListViewTop.php");	
			$home_values = getTopAccounts($maxval,$calCnt);
		}elseif($hometype=="PLVT" && vtlib_isModuleActive("Potentials")){
			if(isPermitted('Potentials','index') == "yes"){
				 include_once("modules/Potentials/ListViewTop.php");
				 $home_values=getTopPotentials($maxval,$calCnt);
			}	
		}elseif($hometype=="QLTQ" && vtlib_isModuleActive("Quotes")){
			if(isPermitted('Quotes','index') == "yes"){
				require_once('modules/Quotes/ListTopQuotes.php');
				$home_values=getTopQuotes($maxval,$calCnt);
			}	
		}elseif($hometype=="HLT" && vtlib_isModuleActive("HelpDesk")){
			if(isPermitted('HelpDesk','index') == "yes"){
				require_once('modules/HelpDesk/ListTickets.php');
				$home_values=getMyTickets($maxval,$calCnt);
			}	
		}elseif($hometype=="GRT"){
			$home_values = getGroupTaskLists($maxval,$calCnt);	
		}elseif($hometype=="OLTSO" && vtlib_isModuleActive("SalesOrder")){
			if(isPermitted('SalesOrder','index') == "yes"){
				require_once('modules/SalesOrder/ListTopSalesOrder.php');
				$home_values=getTopSalesOrder($maxval,$calCnt);
			}	
		}elseif($hometype=="ILTI" && vtlib_isModuleActive("Invoice")){
			if(isPermitted('Invoice','index') == "yes"){
				require_once('modules/Invoice/ListTopInvoice.php');
				$home_values=getTopInvoice($maxval,$calCnt);
			}	
		}elseif($hometype=="MNL" && vtlib_isModuleActive("Leads")){
			if(isPermitted('Leads','index') == "yes"){
				 include_once("modules/Leads/ListViewTop.php");
				 $home_values=getNewLeads($maxval,$calCnt);
			}	
		}elseif($hometype=="OLTPO" && vtlib_isModuleActive("PurchaseOrder")){
			if(isPermitted('PurchaseOrder','index') == "yes"){
				require_once('modules/PurchaseOrder/ListTopPurchaseOrder.php');
				$home_values=getTopPurchaseOrder($maxval,$calCnt);
			}	
		}elseif($hometype=="LTFAQ" && vtlib_isModuleActive("FAQ")){
			if(isPermitted('Faq','index') == "yes"){
				require_once('modules/Faq/ListFaq.php');
				$home_values=getMyFaq($maxval,$calCnt);
			}	
		}elseif($hometype=="CVLVT"){
			include_once("modules/CustomView/ListViewTop.php");
			$home_values = getKeyMetrics($maxval,$calCnt);
		}elseif($hometype == 'UA' && vtlib_isModuleActive("Calendar")){
			require_once "modules/Home/HomeUtils.php";
			$home_values = homepage_getUpcomingActivities($maxval, $calCnt);
		}elseif($hometype == 'PA' && vtlib_isModuleActive("Calendar")){
			require_once "modules/Home/HomeUtils.php";
			$home_values = homepage_getPendingActivities($maxval, $calCnt);
		}
		
		if($calCnt == 'calculateCnt'){
			return $home_values;
		}
		$return_value = Array();
		if(count($home_values) > 0){
			$return_value=Array('Maxentries'=>$maxval,'Details'=>$home_values);
		}
		return $return_value;
	}
	
	/**
	 * this function returns the notebook contents from the database
	 * @param integer $notebookid - the notebookid
	 * @return - contents of the notebook for a user
	 */
 	function getNotebookContents($notebookid){
		global $adb, $current_user;
		
		$sql = "select * from aicrm_notebook_contents where notebookid=? and userid=?";
		$result = $adb->pquery($sql, array($notebookid,$current_user->id));
		
		$contents = "";
		if($adb->num_rows($result)>0){
			$contents = $adb->query_result($result,0,"contents");
		}
		return $contents;
	}
	
	/**
	 * this function returns the URL for a given widget id from the database
	 * @param integer $widgetid - the notebookid
	 * @return $url - the url for the widget
	 */
	function getWidgetURL($widgetid){
		global $adb, $current_user;
		
		$sql = "select * from aicrm_homewidget_url where widgetid=?";
		$result = $adb->pquery($sql, array($widgetid));
		
		$url = "";
		if($adb->num_rows($result)>0){
			$url = $adb->query_result($result,0,"url");
		}
		return $url;
	}
}

/**
 * this function returns the tasks allocated to different groups
 */
function getGroupTaskLists($maxval,$calCnt){
	//get all the group relation tasks
	global $current_user;
	global $adb;
	global $log;
	global $app_strings;
	$userid= $current_user->id;
	$groupids = explode(",", fetchUserGroupids($userid));
	
	//Check for permission before constructing the query.
	if(vtlib_isModuleActive("Leads") && count($groupids) > 0 && (isPermitted('Leads','index') == "yes"  || isPermitted('Calendar','index') == "yes" || isPermitted('HelpDesk','index') == "yes" || isPermitted('Potentials','index') == "yes"  || isPermitted('Accounts','index') == "yes" || isPermitted('Contacts','index') =='yes' || isPermitted('Campaigns','index') =='yes'  || isPermitted('SalesOrder','index') =='yes' || isPermitted('Invoice','index') =='yes' || isPermitted('PurchaseOrder','index') == 'yes')){
		$query = '';
		$params = array();
		if(isPermitted('Leads','index') == "yes"){
			$query = "select aicrm_leaddetails.leadid as id,aicrm_leaddetails.lastname as name,aicrm_groups.groupname as groupname, 'Leads     ' as Type from aicrm_leaddetails inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid inner join aicrm_groups on aicrm_crmentity.smownerid=aicrm_groups.groupid where  aicrm_crmentity.deleted=0";
			if (count($groupids) > 0){
				$query .= " and aicrm_groups.groupid in (". generateQuestionMarks($groupids). ")";
				array_push($params, $groupids);
			}
		}
		
		if(vtlib_isModuleActive("Calendar") && isPermitted('Calendar','index') == "yes"){
			if($query !=''){
				$query .= " union all ";
			}
			//Get the activities assigned to group
			$query .= "select aicrm_activity.activityid as id,aicrm_activity.subject as name,aicrm_groups.groupname as groupname,'Activities' as Type from aicrm_activity inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_activity.activityid inner join aicrm_groups on aicrm_crmentity.smownerid=aicrm_groups.groupid where  aicrm_crmentity.deleted=0 and ((aicrm_activity.eventstatus !='held'and (aicrm_activity.status is null or aicrm_activity.status ='')) or (aicrm_activity.status !='completed' and (aicrm_activity.eventstatus is null or aicrm_activity.eventstatus='')))";
			if (count($groupids) > 0) {
				$query .= " and aicrm_groups.groupid in (". generateQuestionMarks($groupids). ")";
				array_push($params, $groupids);
			}
		}
		
		if(vtlib_isModuleActive("HelpDesk") && isPermitted('HelpDesk','index') == "yes"){
			if($query !=''){
				$query .= " union all ";
			}
			//Get the tickets assigned to group (status not Closed -- hardcoded value)
			$query .= "select aicrm_troubletickets.ticketid,aicrm_troubletickets.title as name,aicrm_groups.groupname,'Tickets   ' as Type from aicrm_troubletickets inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_troubletickets.ticketid inner join aicrm_groups on aicrm_crmentity.smownerid=aicrm_groups.groupid where aicrm_crmentity.deleted=0 and aicrm_troubletickets.status != 'Closed'";
			if (count($groupids) > 0) {
				$query .= " and aicrm_groups.groupid in (". generateQuestionMarks($groupids). ")";
				array_push($params, $groupids);
			}
		}
		
		if(vtlib_isModuleActive("Potentials") && isPermitted('Potentials','index') == "yes"){
			if($query != ''){
				$query .=" union all ";
			}	
			//Get the potentials assigned to group(sales stage not Closed Lost or Closed Won-- hardcoded value)
			$query .= "select aicrm_potential.potentialid,aicrm_potential.potentialname as name,aicrm_groups.groupname as groupname,'Potentials ' as Type from aicrm_potential  inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_potential.potentialid inner join aicrm_groups on aicrm_crmentity.smownerid = aicrm_groups.groupid where aicrm_crmentity.deleted=0  and ((aicrm_potential.sales_stage !='Closed Lost') or (aicrm_potential.sales_stage != 'Closed Won'))";
			if (count($groupids) > 0){
				$query .= " and aicrm_groups.groupid in (". generateQuestionMarks($groupids). ")";
				array_push($params, $groupids);
			}
		}
		
		if(vtlib_isModuleActive("Accounts") && isPermitted('Accounts','index') == "yes"){
			if($query != ''){
				$query .=" union all ";
			}
			//Get the Accounts assigned to group 
			$query .= "select aicrm_account.accountid as id,aicrm_account.accountname as name,aicrm_groups.groupname as groupname, 'Accounts ' as Type from aicrm_account inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid inner join aicrm_groups on aicrm_crmentity.crmid=aicrm_groups.groupid where aicrm_crmentity.deleted=0 and aicrm_groups.groupid in(". generateQuestionMarks($groupids). ")"; 
			array_push($params, $groupids);
		}
		
		if(vtlib_isModuleActive("Contacts") && isPermitted('Contacts','index') =='yes'){
			if($query != ''){
            	$query .=" union all ";
			}
            //Get the Contacts assigned to group
			$query .= "select aicrm_contactdetails.contactid as id, aicrm_contactdetails.lastname as name ,aicrm_groups.groupname as groupname, 'Contacts ' as Type from aicrm_contactdetails inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid inner join aicrm_groups on aicrm_crmentity.smownerid = aicrm_groups.groupid where aicrm_crmentity.deleted=0";
			if (count($groupids) > 0) {
				$query .= " and aicrm_groups.groupid in (". generateQuestionMarks($groupids). ")";
				array_push($params, $groupids);
			}
		}
		
		if(vtlib_isModuleActive("Campaigns") && isPermitted('Campaigns','index') =='yes'){
			if($query != ''){
				$query .=" union all ";
			}
			//Get the Campaigns assigned to group(Campaign status not Complete -- hardcoded value)
			$query .= "select aicrm_campaign.campaignid as id, aicrm_campaign.campaignname as name, aicrm_groups.groupname as groupname,'Campaigns ' as Type from aicrm_campaign inner join  aicrm_crmentity on aicrm_crmentity.crmid = aicrm_campaign.campaignid inner join aicrm_groups on aicrm_crmentity.smownerid = aicrm_groups.groupid where aicrm_crmentity.deleted=0  and (aicrm_campaign.campaignstatus != 'Complete')";
			if (count($groupids) > 0) {
				$query .= " and aicrm_groups.groupid in (". generateQuestionMarks($groupids). ")";
				array_push($params, $groupids);
			}
		}
		
		if(vtlib_isModuleActive("Quotes") && isPermitted('Quotes','index') == 'yes'){
			if($query != ''){
				$query .=" union all ";
			}
			//Get the Quotes assigned to group(Quotes stage not Rejected -- hardcoded value)
			$query .="select aicrm_quotes.quoteid as id,aicrm_quotes.subject as name, aicrm_groups.groupname as groupname ,'Quotes 'as Type from aicrm_quotes inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_quotes.quoteid inner join aicrm_groups on aicrm_crmentity.smownerid = aicrm_groups.groupid where aicrm_crmentity.deleted=0  and (aicrm_quotes.quotestage != 'Rejected')";
			if (count($groupids) > 0) {
				$query .= " and aicrm_groups.groupid in (". generateQuestionMarks($groupids). ")";
				array_push($params, $groupids);
			}
		}
		
		if(vtlib_isModuleActive("SalesOrder") && isPermitted('SalesOrder','index') =='yes'){
			if($query != ''){
				$query .=" union all ";
			}
            //Get the Sales Order assigned to group
            $query .="select aicrm_salesorder.salesorderid as id, aicrm_salesorder.subject as name,aicrm_groups.groupname as groupname,'SalesOrder ' as Type from aicrm_salesorder inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_salesorder.salesorderid inner join aicrm_groups on aicrm_crmentity.smownerid = aicrm_groups.groupid where aicrm_crmentity.deleted=0 and aicrm_groups.groupid in  (". generateQuestionMarks($groupids). ")";
			array_push($params, $groupids);
		}	
		
		if(vtlib_isModuleActive("Invoice") && isPermitted('Invoice','index') =='yes'){
			if($query != ''){
				$query .=" union all ";
			}
			//Get the Sales Order assigned to group(Invoice status not Paid -- hardcoded value)
			$query .="select aicrm_invoice.invoiceid as Id , aicrm_invoice.subject as Name, aicrm_groups.groupname as groupname,'Invoice ' as Type from aicrm_invoice inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_invoice.invoiceid inner join aicrm_groups on aicrm_crmentity.smownerid = aicrm_groups.groupid where aicrm_crmentity.deleted=0 and(aicrm_invoice.invoicestatus != 'Paid') and aicrm_groups.groupid in  (". generateQuestionMarks($groupids). ")";
			array_push($params, $groupids);
		}
		
		if(vtlib_isModuleActive("PurchaseOrder") && isPermitted('PurchaseOrder','index') == 'yes'){
			if($query != ''){
				$query .=" union all ";
			}
			//Get the Purchase Order assigned to group
			$query .="select aicrm_purchaseorder.purchaseorderid as id,aicrm_purchaseorder.subject as name,aicrm_groups.groupname as groupname, 'PurchaseOrder ' as Type from aicrm_purchaseorder inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_purchaseorder.purchaseorderid inner join  aicrm_groups on aicrm_crmentity.smownerid =aicrm_groups.groupid where aicrm_crmentity.deleted=0";
			if (count($groupids) > 0) {
				$query .= " and aicrm_groups.groupid in (". generateQuestionMarks($groupids). ")";
				array_push($params, $groupids);
			}
		}
		
		if(vtlib_isModuleActive("Documents") && isPermitted('Documents','index') == 'yes'){
			if($query != ''){
				$query .=" union all ";
			}
			//Get the Purchase Order assigned to group
			$query .="select aicrm_notes.notesid as id,aicrm_notes.title as name,aicrm_groups.groupname as groupname, 'Documents' as Type from aicrm_notes inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_notes.notesid inner join  aicrm_groups on aicrm_crmentity.smownerid =aicrm_groups.groupid where aicrm_crmentity.deleted=0";
			if (count($groupids) > 0) {
				$query .= " and aicrm_groups.groupid in (". generateQuestionMarks($groupids). ")";
				array_push($params, $groupids);
			}
		}
		
		$query .= " LIMIT 0, $maxval";
		$log->info("Here is the where clause for the list view: $query");
		$result = $adb->pquery($query, $params) or die("Couldn't get the group listing");

		$title=array();
		$title[]='myGroupAllocation.gif';
		$title[]=$app_strings['LBL_GROUP_ALLOCATION_TITLE'];
		$title[]='home_mygrp';
		$header=array();
		$header[]=$app_strings['LBL_ENTITY_NAME'];
		$header[]=$app_strings['LBL_GROUP_NAME'];
		$header[]=$app_strings['LBL_ENTITY_TYPE'];

		if(count($groupids) > 0){
			$i=1;
			while($row = $adb->fetch_array($result)){
				$value=array();	
				$row["type"]=trim($row["type"]);
				if($row["type"] == "Tickets"){
					$list = '<a href=index.php?module=HelpDesk';
					$list .= '&action=DetailView&record='.$row["id"].'>'.$row["name"].'</a>';
				}elseif($row["type"] == "Activities"){
					$row["type"] = 'Calendar';
					$acti_type = getActivityType($row["id"]);
					$list = '<a href=index.php?module='.$row["type"];
					if($acti_type == 'Task'){
						$list .= '&activity_mode=Task';
					}elseif($acti_type == 'Call' || $acti_type == 'Meeting'){
						$list .= '&activity_mode=Events';
					}
					$list .= '&action=DetailView&record='.$row["id"].'>'.$row["name"].'</a>';
				}else{
					$list = '<a href=index.php?module='.$row["type"];
					$list .= '&action=DetailView&record='.$row["id"].'>'.$row["name"].'</a>';
				}

				$value[]=$list;	
				$value[]= $row["groupname"];
				$value[]= $row["type"];
				$entries[$row["id"]]=$value;	
				$i++;
			}
		}

		$values=Array('Title'=>$title,'Header'=>$header,'Entries'=>$entries);
		if(count($entries)>0){	
			return $values;
		}
	}
}

?>
