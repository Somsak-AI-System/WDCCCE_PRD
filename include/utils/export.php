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

require_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Accounts/Accounts.php');
require_once('modules/Smartemail/Smartemail.php');
require_once('modules/Projects/Projects.php');
require_once('modules/Branchs/Branchs.php');
require_once('modules/Contacts/Contacts.php');
require_once('modules/Leads/Leads.php');
require_once('modules/Emails/Emails.php');
require_once('modules/Calendar/Activity.php');
require_once('modules/Documents/Documents.php');
require_once('modules/Potentials/Potentials.php');
require_once('modules/Users/Users.php');
require_once('modules/Products/Products.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('include/utils/UserInfoUtil.php');
require_once('modules/CustomView/CustomView.php');
require_once('modules/PickList/PickListUtils.php');
require_once('modules/SmartSms/SmartSms.php');
require_once('modules/Activitys/Activitys.php');
require_once('modules/Opportunity/Opportunity.php');
require_once('modules/KnowledgeBase/KnowledgeBase.php');
require_once('modules/Quotation/Quotation.php');
require_once('modules/PriceList/PriceList.php');
require_once('modules/Job/Job.php');
require_once('modules/Competitor/Competitor.php');
require_once('modules/Serial/Serial.php');
require_once('modules/Errors/Errors.php');
require_once('modules/Errorslist/Errorslist.php');
require_once('modules/Sparepart/Sparepart.php');
require_once('modules/Sparepartlist/Sparepartlist.php');
require_once('modules/Campaigns/Campaigns.php');
require_once('modules/Faq/Faq.php');
require_once('modules/Plant/Plant.php');
require_once('modules/Order/Order.php');
require_once('modules/Deal/Deal.php');
require_once('modules/Promotion/Promotion.php');
require_once('modules/Voucher/Voucher.php');
require_once('modules/Questionnaire/Questionnaire.php');
require_once('modules/Questionnairetemplate/Questionnairetemplate.php');
require_once('modules/Announcement/Announcement.php');
require_once('modules/Smartquestionnaire/Smartquestionnaire.php');
require_once('modules/Questionnaireanswer/Questionnaireanswer.php');
require_once('modules/Promotionvoucher/Promotionvoucher.php');
require_once('modules/Competitorproduct/Competitorproduct.php');
require_once('modules/Premuimproduct/Premuimproduct.php');
require_once('modules/Service/Service.php');
require_once('modules/Servicerequest/Servicerequest.php');
require_once('modules/Redemption/Redemption.php');
require_once('modules/Point/Point.php');
require_once('modules/Salesorder/Salesorder.php');
require_once('modules/Seriallist/Seriallist.php');
require_once('modules/Inspection/Inspection.php');
require_once('modules/Salesinvoice/Salesinvoice.php');
require_once('modules/Inspectiontemplate/Inspectiontemplate.php');
require_once('modules/Tools/Tools.php');
require_once('modules/Expense/Expense.php');
require_once('modules/Purchasesorder/Purchasesorder.php');
require_once('modules/Samplerequisition/Samplerequisition.php');
require_once('modules/Goodsreceive/Goodsreceive.php');
require_once('modules/Marketingtools/Marketingtools.php');
require_once('modules/Claim/Claim.php');

// Set the current language and the language strings, if not already set.
setCurrentLanguage();

global $allow_exports,$app_strings;

session_start();

$current_user = new Users();

if(isset($_SESSION['authenticated_user_id']))
{
	$result = $current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id'],"Users");
	if($result == null)
	{
		session_destroy();
		header("Location: index.php?action=Login&module=Users");
		exit;
	}

}

//Security Check
if(isPermitted($_REQUEST['module'],"Export") == "no")
{
	$allow_exports="none";
}

if ($allow_exports=='none' || ( $allow_exports=='admin' && ! is_admin($current_user) ) )
{

?>
	<script type='text/javascript'>
		alert("<?php echo $app_strings['NOT_PERMITTED_TO_EXPORT']?>");
		window.location="index.php?module=<?php echo vtlib_purify($_REQUEST['module']) ?>&action=index";
	</script>
	<?php exit; ?>
<?php
}

/**Function convert line breaks to space in description during export
 * Pram $str - text
 * retrun type string
*/
function br2nl_vt($str)
{
	global $log;
	$log->debug("Entering br2nl_vt(".$str.") method ...");
	$str = preg_replace("/(\r\n)/", " ", $str);
	$log->debug("Exiting br2nl_vt method ...");
	return $str;
}

/**
 * This function exports all the data for a given module
 * Param $type - module name
 * Return type text
 */
function export($type){
    global $allow_exports,$app_strings;
    global $log,$list_max_entries_per_page;
    $log->debug("Entering export(".$type.") method ...");
    global $adb;

    $focus = 0;
    $content = '';

    if ($type != ""){
		// vtlib customization: Hook to dynamically include required module file.
		// Refer to the logic in setting $currentModule in index.php
		$focus = CRMEntity::getInstance($type);
    }
    $log = LoggerManager::getLogger('export_'.$type);
    $db = PearDatabase::getInstance();

	$oCustomView = new CustomView("$type");
	$viewid = $oCustomView->getViewId("$type");
	$sorder = $focus->getSortOrder();
	$order_by = $focus->getOrderBy();

    $search_type = $_REQUEST['search_type'];
    $export_data = $_REQUEST['export_data'];

	if(isset($_SESSION['export_where']) && $_SESSION['export_where']!='' && $search_type == 'includesearch'){
		$where =$_SESSION['export_where'];
	}

	$query = $focus->create_export_query($where);
	$stdfiltersql = $oCustomView->getCVStdFilterSQL($viewid);
	$advfiltersql = $oCustomView->getCVAdvFilterSQL($viewid);
	if(isset($stdfiltersql) && $stdfiltersql != ''){
		$query .= ' and '.$stdfiltersql;
	}
	if(isset($advfiltersql) && $advfiltersql != '') {
		$query .= ' and '.$advfiltersql;
	}
	$params = array();

	if(($search_type == 'withoutsearch' || $search_type == 'includesearch') && $export_data == 'selecteddata'){
		$idstring = explode(";", $_REQUEST['idstring']);
		if($type == 'Accounts' && count($idstring) > 0) {
			$query .= ' and aicrm_account.accountid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		} elseif($type == 'Contacts' && count($idstring) > 0) {
			$query .= ' and aicrm_contactdetails.contactid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		} elseif($type == 'Potentials' && count($idstring) > 0) {
			$query .= ' and aicrm_potential.potentialid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		} elseif($type == 'Leads' && count($idstring) > 0) {
			$query .= ' and aicrm_leaddetails.leadid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		} elseif($type == 'Products' && count($idstring) > 0) {
			$query .= ' and aicrm_products.productid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		} elseif($type == 'Documents' && count($idstring) > 0) {
			$query .= ' and aicrm_notes.notesid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		} elseif($type == 'HelpDesk' && count($idstring) > 0) {
			$query .= ' and aicrm_troubletickets.ticketid in ('. generateQuestionMarks($idstring) .')';
			array_push($params, $idstring);
		} else if(count($idstring) > 0) {
			// vtlib customization: Hook to make the export feature available for custom modules.
			$query .= " and $focus->table_name.$focus->table_index in (" . generateQuestionMarks($idstring) . ')';
			array_push($params, $idstring);
			// END
		}
	}

	if(isset($order_by) && $order_by != ''){
		if($order_by == 'smownerid'){
			$query .= ' ORDER BY user_name '.$sorder;
		}elseif($order_by == 'lastname' && $type == 'Documents'){
			$query .= ' ORDER BY aicrm_contactdetails.lastname  '. $sorder;
		}elseif($order_by == 'crmid' && $type == 'HelpDesk'){
			$query .= ' ORDER BY aicrm_troubletickets.ticketid  '. $sorder;
		}else{
			$tablename = getTableNameForField($type,$order_by);
			$tablename = (($tablename != '')?($tablename."."):'');
			if( $adb->dbType == "pgsql"){
				$query .= ' GROUP BY '.$tablename.$order_by;
			}
			$query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
		}
	}
	//echo $_SESSION['nav_start']; exit;
	if(isset($_SESSION['nav_start']) && $_SESSION['nav_start']!='' && $export_data == 'currentpage'){
		$start_rec = ($_SESSION['nav_start']-1);
		$limit_start_rec = ($start_rec == 0) ? 0 : ($start_rec * $list_max_entries_per_page);
		$query .= ' LIMIT '.$limit_start_rec.','.$list_max_entries_per_page;
	}
	//echo $query; exit;
    $result = $adb->pquery($query, $params, true, "Error exporting $type: "."<BR>$query");
    $fields_array = $adb->getFieldsArray($result);
    $fields_array = array_diff($fields_array,array("user_name"));

	$__processor = new ExportUtils($type, $fields_array);

	// Translated the field names based on the language used.
	$translated_fields_array = array();
	for($i=0; $i<count($fields_array); $i++) {
		$translated_fields_array[$i] = getTranslatedString($fields_array[$i],$type);
	}
	$header = implode("\",\"",array_values($translated_fields_array));
	$header = "\"" .$header;
	$header .= "\"\r\n";

	/** Output header information */
	// echo iconv("UTF-8", "TIS-620", $header);
	echo $header;

	$column_list = implode(",",array_values($fields_array));

    while($val = $adb->fetchByAssoc($result, -1, false)){
		$new_arr = array();
		$val = $__processor->sanitizeValues($val);
		foreach ($val as $key => $value){
			//$value =mb_convert_encoding($value, "TIS-620", "UTF-8");// Add by AI
			if($type == 'Documents' && $key == 'description'){
				$value = strip_tags($value);
				$value = str_replace('&nbsp;','',$value);
				array_push($new_arr,$value);
			}else if($type == 'Accounts' && $key == $app_strings['LBL_Application_1']){
				if($value != ''){
					$sql1 = "select branchs_name from aicrm_branchs where branchsid=?";
					$result1 = $adb->pquery($sql1, array($value));
					$branchs_name=$adb->query_result($result1,0,"branchs_name");
					$value=$branchs_name;
				}else{
					$value = '';
				}
				array_push($new_arr,$value);
			}elseif($key != "user_name"){
				// Let us provide the module to transform the value before we save it to CSV file
				$value = $focus->transform_export_value($key, $value);
				array_push($new_arr, preg_replace("/\"/","\"\"",$value));
			}
		}
		$line = implode("\",\"",$new_arr);
		$line = "\"" .$line;
		$line .= "\"\r\n";
		/** Output each row information */
		//$value =mb_convert_encoding($value, "TIS-620", "UTF-8");// Add by AI
		//echo $line;
		// echo iconv( 'UTF-8' ,'TIS-620',$line);
		echo $line;
	}
	$log->debug("Exiting export method ...");
	return true;
}

/** Send the output header and invoke function for contents output */
header("Content-Disposition:attachment;filename={$_REQUEST['module']}.csv");
header("Content-Type:text/csv;charset=UTF-8");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header("Cache-Control: post-check=0, pre-check=0", false );

echo "\xEF\xBB\xBF";

export(vtlib_purify($_REQUEST['module']));

exit;

/**
 * this class will provide utility functions to process the export data.
 * this is to make sure that the data is sanitized before sending for export
 */
class ExportUtils{
	var $fieldsArr = array();
	var $picklistValues = array();

	function ExportUtils($module, $fields_array){
		self::__init($module, $fields_array);
	}


	function __init($module, $fields_array){
		$infoArr = self::getInformationArray($module);

		//attach extra fields related information to the fields_array; this will be useful for processing the export data
		foreach($infoArr as $fieldname=>$fieldinfo){
			if(in_array($fieldinfo["fieldlabel"], $fields_array)){
				$this->fieldsArr[$fieldname] = $fieldinfo;
			}
		}
	}

	function startsWith($haystack, $needle)
	{
		return $needle === "" || strpos($haystack, $needle) === 0;
	}
	/**
	 * this function takes in an array of values for an user and sanitizes it for export
	 * @param array $arr - the array of values
	 */
	function sanitizeValues($arr){
		global $current_user, $adb;
		$roleid = fetchUserRole($current_user->id);

		foreach($arr as $fieldlabel=>&$value){
			$fieldInfo = $this->fieldsArr[$fieldlabel];

			$uitype = $fieldInfo['uitype'];
			$fieldname = $fieldInfo['fieldname'];

			if(is_numeric($value) && $this->startsWith($value,"0") && $uitype!="7" ) $value ="=".'"'.$value.'"';


			if($uitype == 15 || $uitype == 16 || $uitype == 33){
				//picklists
				if(empty($this->picklistValues[$fieldname])){
					$this->picklistValues[$fieldname] = getAssignedPicklistValues($fieldname, $roleid, $adb);
				}
				$value = trim($value);
				if(!empty($this->picklistValues[$fieldname]) && !in_array($value, $this->picklistValues[$fieldname]) && !empty($value)){
					$value = getTranslatedString("LBL_NOT_ACCESSIBLE");
				}
			}elseif($uitype == 10){
				//have to handle uitype 10
				$value = trim($value);
				if(!empty($value)) {
					$parent_module = getSalesEntityType($value);
					$displayValueArray = getEntityName($parent_module, $value);
					if(!empty($displayValueArray)){
						foreach($displayValueArray as $k=>$v){
							$displayValue = $v;
						}
					}
					if(!empty($parent_module) && !empty($displayValue)){
						$value = $parent_module."::::".$displayValue;
					}else{
						$value = "";
					}
				} else {
					$value = '';
				}
			}
		}
		return $arr;
	}

	/**
	 * this function takes in a module name and returns the field information for it
	 */
	function getInformationArray($module){
		require_once 'include/utils/utils.php';
		global $adb;
		$tabid = getTabid($module);

		$result = $adb->pquery("select * from aicrm_field where tabid=?", array($tabid));
		$count = $adb->num_rows($result);
		$arr = array();
		$data = array();

		for($i=0;$i<$count;$i++){
			$arr['uitype'] = $adb->query_result($result, $i, "uitype");
			$arr['fieldname'] = $adb->query_result($result, $i, "fieldname");
			$arr['columnname'] = $adb->query_result($result, $i, "columnname");
			$arr['tablename'] = $adb->query_result($result, $i, "tablename");
			$arr['fieldlabel'] = $adb->query_result($result, $i, "fieldlabel");
			$fieldlabel = strtolower($arr['fieldlabel']);
			$data[$fieldlabel] = $arr;
		}
		return $data;
	}
}
?>