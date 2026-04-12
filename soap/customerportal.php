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

require_once("config.php");
require_once('include/logging.php');
require_once('include/nusoap/nusoap.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('modules/Emails/mail.php');
require_once('modules/HelpDesk/language/en_us.lang.php');
require_once('include/utils/CommonUtils.php');
require_once('include/utils/VtlibUtils.php');

/** Configure language for server response translation */
global $default_language, $current_language;
if(!isset($current_language)) $current_language = $default_language;

$log = &LoggerManager::getLogger('customerportal');

error_reporting(0);

$NAMESPACE = 'http://www.vtiger.com/products/crm';
$server = new soap_server;

$server->configureWSDL('customerportal');

$server->wsdl->addComplexType(
	'common_array',
	'complexType',
	'array',
	'',
	array(
		'fieldname' => array('name'=>'fieldname','type'=>'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'common_array1',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(
		array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:common_array[]')
	),
	'tns:common_array'
);

$server->wsdl->addComplexType(
	'add_contact_detail_array',
    'complexType',
    'array',
    '',
	array(
    	'salutation' => array('name'=>'salutation','type'=>'xsd:string'),
        'firstname' => array('name'=>'firstname','type'=>'xsd:string'),
        'phone' => array('name'=>'phone','type'=>'xsd:string'),
        'lastname' => array('name'=>'lastname','type'=>'xsd:string'),
        'mobile' => array('name'=>'mobile','type'=>'xsd:string'),
		'accountid' => array('name'=>'accountid','type'=>'xsd:string'),
        'leadsource' => array('name'=>'leadsource','type'=>'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'field_details_array',
	'complexType',
    'array',
    '',
	array(
    	'fieldlabel' => array('name'=>'fieldlabel','type'=>'xsd:string'),
        'fieldvalue' => array('name'=>'fieldvalue','type'=>'xsd:string'),
	)
);
$server->wsdl->addComplexType(
	'field_datalist_array',
    'complexType',
    'array',
    '',
	array(
    	'fielddata' => array('name'=>'fielddata','type'=>'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'product_list_array',
	'complexType',
	'array',
	'',
	array(
		'productid' => array('name'=>'productid','type'=>'xsd:string'),
		'productname' => array('name'=>'productname','type'=>'xsd:string'),
		'productcode' => array('name'=>'productcode','type'=>'xsd:string'),
		'commissionrate' => array('name'=>'commissionrate','type'=>'xsd:string'),
		'qtyinstock' => array('name'=>'qtyinstock','type'=>'xsd:string'),
		'qty_per_unit' => array('name'=>'qty_per_unit','type'=>'xsd:string'),
		'unit_price' => array('name'=>'unit_price','type'=>'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'get_ticket_attachments_array',
    'complexType',
    'array',
    '',
	array(
    	'files' => array(
			'fileid'=>'xsd:string','type'=>'tns:xsd:string',
			'filename'=>'xsd:string','type'=>'tns:xsd:string',
			'filesize'=>'xsd:string','type'=>'tns:xsd:string',
			'filetype'=>'xsd:string','type'=>'tns:xsd:string',
			'filecontents'=>'xsd:string','type'=>'tns:xsd:string'
		),
	)
);


$server->register(
	'authenticate_user',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'change_password',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'create_ticket',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

//for a particular contact ticket list
$server->register(
	'get_tickets_list',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'get_ticket_comments',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'get_combo_values',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'get_KBase_details',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array1'),
	$NAMESPACE);

$server->register(
	'save_faq_comment',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'update_ticket_comment',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
        'close_current_ticket',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'xsd:string'),
	$NAMESPACE);

$server->register(
	'update_login_details',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'xsd:string'),
	$NAMESPACE);

$server->register(
	'send_mail_for_password',
	array('email'=>'xsd:string'),
	array('return'=>'xsd:string'),
	$NAMESPACE);

$server->register(
        'get_ticket_creator',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'xsd:string'),
	$NAMESPACE);

$server->register(
	'get_picklists',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'get_ticket_attachments',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'get_filecontent',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'add_ticket_attachment',
	array('fieldname'=>'tns:common_array'),
	array('return'=>'tns:common_array'),
	$NAMESPACE);

$server->register(
	'get_cf_field_details',
	array('id'=>'xsd:string','contactid'=>'xsd:string','sessionid'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
        'get_check_account_id',
	array('id'=>'xsd:string'),
	array('return'=>'xsd:string'),
	$NAMESPACE);

		//to get details of quotes,invoices and documents
$server->register(
	'get_details',
	array('id'=>'xsd:string','block'=>'xsd:string','contactid'=>'xsd:string','sessionid'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

		//to get the products list for the entire account of a contact
$server->register(
	'get_product_list_values',
	array('id'=>'xsd:string','block'=>'xsd:string','sessionid'=>'xsd:string','only_mine'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
	'get_list_values',
	array('id'=>'xsd:string','block'=>'xsd:string','sessionid'=>'xsd:string','only_mine'=>'xsd:string'),
	array('return'=>'tns:field_datalist_array'),
	$NAMESPACE);

$server->register(
	'get_product_urllist',
	array('customerid'=>'xsd:string','productid'=>'xsd:string','block'=>'xsd:string'),
	array('return'=>'tns:field_datalist_array'),
	$NAMESPACE);

$server->register(
	'get_pdf',
	array('id'=>'xsd:string','block'=>'xsd:string','contactid'=>'xsd:string','sessionid'=>'xsd:string'),
	array('return'=>'tns:field_datalist_array'),
	$NAMESPACE);

$server->register(
	'get_filecontent_detail',
	array('id'=>'xsd:string','folderid'=>'xsd:string','block'=>'xsd:string','contactid'=>'xsd:string','sessionid'=>'xsd:string'),
	array('return'=>'tns:get_ticket_attachments_array'),
	$NAMESPACE);

$server->register(
	'get_invoice_detail',
	array('id'=>'xsd:string','block'=>'xsd:string','contactid'=>'xsd:string','sessionid'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
	'get_modules',
	array(),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
	'show_all',
	array('module'=>'xsd:string'),
	array('return'=>'xsd:string'),
	$NAMESPACE);

$server->register(
	'get_documents',
	array('id'=>'xsd:string','module'=>'xsd:string','customerid'=>'xsd:string','sessionid'=> 'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

$server->register(
	'updateCount',
	array('id'=>'xsd:string'),
	array('return'=>'xsd:string'),
	$NAMESPACE);

//to get the Services list for the entire account of a contact
$server->register(
	'get_service_list_values',
	array('id'=>'xsd:string','module'=>'xsd:string','sessionid'=>'xsd:string','only_mine'=>'xsd:string'),
	array('return'=>'tns:field_details_array'),
	$NAMESPACE);

/**
 * Helper class to provide functionality like caching etc...
 */
class Vtiger_Soap_CustomerPortal {

	/** Preference value caching */
	static $_prefs_cache = array();
	static function lookupPrefValue($key) {
		if(self::$_prefs_cache[$key]) {
			return self::$_prefs_cache[$key];
		}
		return false;
	}
	static function updatePrefValue($key, $value) {
		self::$_prefs_cache[$key] = $value;
	}

	/** Sessionid caching for re-use */
	static $_sessionid = array();
	static function lookupSessionId($key) {
		if(isset(self::$_sessionid[$key])) {
			return self::$_sessionid[$key];
		}
		return false;
	}
	static function updateSessionId($key, $value) {
		self::$_sessionid[$key] = $value;
	}

	/** Store available module information */
	static $_modules = false;
	static function lookupAllowedModules() {
		return self::$_modules;
	}
	static function updateAllowedModules($modules) {
		self::$_modules = $modules;
	}

}

/**	function used to get the list of ticket comments
 * @param array $input_array - array which contains the following parameters 
 * int $id - customer id
 * string $sessionid - session id
 * int $ticketid - ticket id
 * @return array $response - ticket comments and details as a array with elements comments, owner and createdtime which will be returned from the function get_ticket_comments_list
*/
function get_ticket_comments($input_array)
{
	global $adb,$log;
	$adb->println("Entering customer portal function get_ticket_comments");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$ticketid = (int) $input_array['ticketid'];

	if(!validateSession($id,$sessionid))
		return null;
	
	$userid = getPortalUserid();

	if(getFieldVisibilityPermission('HelpDesk', $userid, 'comments') == '1'){
		return null;
	}
	
	$seed_ticket = new HelpDesk();
	$response = $seed_ticket->get_ticket_comments_list($ticketid);
	return $response;
}

/**	function used to get the combo values ie., picklist values of the HelpDesk module and also the list of products
 *	@param array $input_array - array which contains the following parameters
 =>	int $id - customer id
	string $sessionid - session id
	*	return array $output - array which contains the product id, product name, ticketpriorities, ticketseverities, ticketcategories and module owners list
	*/
function get_combo_values($input_array)
{
	global $adb,$log;
	$adb->println("Entering customer portal function get_combo_values");
	$adb->println($input_array);
	
	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];

	if(!validateSession($id,$sessionid))
		return null;

	$output = Array();
	$sql = "select  productid, productname from aicrm_products inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_products.productid where aicrm_crmentity.deleted=0";
	$result = $adb->pquery($sql, array());
	$noofrows = $adb->num_rows($result);
	for($i=0;$i<$noofrows;$i++)
	{
		$check = checkModuleActive('Products');
		if($check == false){
			$output['productid']['productid']="#MODULE INACTIVE#";
			$output['productname']['productname']="#MODULE INACTIVE#";
			break;
		}
		$output['productid']['productid'][$i] = $adb->query_result($result,$i,"productid");
		$output['productname']['productname'][$i] = decode_html($adb->query_result($result,$i,"productname"));
	}
	
	$userid = getPortalUserid();
	
	//We are going to display the picklist entries associated with admin user (role is H2)
	$roleres = $adb->pquery("SELECT roleid from aicrm_user2role where userid = ?",array($userid));
	$RowCount = $adb->num_rows($roleres);
	if($RowCount > 0){
		$admin_role = $adb->query_result($roleres,0,'roleid');
	}
	$result1 = $adb->pquery("select aicrm_ticketpriorities.ticketpriorities from aicrm_ticketpriorities inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_ticketpriorities.picklist_valueid and aicrm_role2picklist.roleid='$admin_role'", array());
	for($i=0;$i<$adb->num_rows($result1);$i++)
	{
		$output['ticketpriorities']['ticketpriorities'][$i] = $adb->query_result($result1,$i,"ticketpriorities");
	}

	$result2 = $adb->pquery("select aicrm_ticketseverities.ticketseverities from aicrm_ticketseverities inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_ticketseverities.picklist_valueid and aicrm_role2picklist.roleid='$admin_role'", array());
	for($i=0;$i<$adb->num_rows($result2);$i++)
	{
		$output['ticketseverities']['ticketseverities'][$i] = $adb->query_result($result2,$i,"ticketseverities");
	}

	$result3 = $adb->pquery("select aicrm_ticketcategories.ticketcategories from aicrm_ticketcategories inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_ticketcategories.picklist_valueid and aicrm_role2picklist.roleid='$admin_role'", array());
	for($i=0;$i<$adb->num_rows($result3);$i++)
	{
		$output['ticketcategories']['ticketcategories'][$i] = $adb->query_result($result3,$i,"ticketcategories");
	}

	// Gather service contract information
	if(!vtlib_isModuleActive('ServiceContracts')) {
		$output['serviceid']['serviceid']="#MODULE INACTIVE#";
		$output['servicename']['servicename']="#MODULE INACTIVE#";
	} else {
		$servicequery = "SELECT aicrm_servicecontracts.servicecontractsid,aicrm_servicecontracts.subject from aicrm_servicecontracts
inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_servicecontracts.servicecontractsid and aicrm_crmentity.deleted = 0";
		$serviceResult = $adb->pquery($servicequery,array());

		for($i=0;$i < $adb->num_rows($serviceResult);$i++){
			$serviceid = $adb->query_result($serviceResult,$i,'servicecontractsid');
			$output['serviceid']['serviceid'][$i] = $serviceid;
			$output['servicename']['servicename'][$i] = $adb->query_result($serviceResult,$i,'subject');
		}
	}

	return $output;

}

/**	function to get the Knowledge base details
 *	@param array $input_array - array which contains the following parameters
 =>	int $id - customer id
	string $sessionid - session id
	*	return array $result - array which contains the faqcategory, all product ids , product names and all faq details
	*/
function get_KBase_details($input_array)
{
	global $adb,$log;
	$adb->println("Entering customer portal function get_KBase_details");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];

	if(!validateSession($id,$sessionid))
		return null;

	$userid = getPortalUserid();
	$result['faqcategory'] = array();
	$result['product'] = array();
	$result['faq'] = array();

	//We are going to display the picklist entries associated with admin user (role is H2)
	$roleres = $adb->pquery("SELECT roleid from aicrm_user2role where userid = ?",array($userid));
	$RowCount = $adb->num_rows($roleres);
	if($RowCount > 0){
		$admin_role = $adb->query_result($roleres,0,'roleid');
	}
	$category_query = "select aicrm_faqcategories.faqcategories from aicrm_faqcategories inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_faqcategories.picklist_valueid and aicrm_role2picklist.roleid='$admin_role'";
	$category_result = $adb->pquery($category_query, array());
	$category_noofrows = $adb->num_rows($category_result);
	for($j=0;$j<$category_noofrows;$j++)
	{
		$faqcategory = $adb->query_result($category_result,$j,'faqcategories');
		$result['faqcategory'][$j] = $faqcategory;
	}

	$check = checkModuleActive('Products');

	if($check == true) {
		$product_query = "select productid, productname from aicrm_products inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_products.productid where aicrm_crmentity.deleted=0";
		$product_result = $adb->pquery($product_query, array());
		$product_noofrows = $adb->num_rows($product_result);
		for($i=0;$i<$product_noofrows;$i++)
		{
			$productid = $adb->query_result($product_result,$i,'productid');
			$productname = $adb->query_result($product_result,$i,'productname');
			$result['product'][$i]['productid'] = $productid;
			$result['product'][$i]['productname'] = $productname;
		}
	}
	$faq_query = "select aicrm_faq.*, aicrm_crmentity.createdtime, aicrm_crmentity.modifiedtime from aicrm_faq " .
		"inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_faq.id " .
		"where aicrm_crmentity.deleted=0 and aicrm_faq.status='Published' order by aicrm_crmentity.modifiedtime DESC";
	$faq_result = $adb->pquery($faq_query, array());
	$faq_noofrows = $adb->num_rows($faq_result);
	for($k=0;$k<$faq_noofrows;$k++)
	{
		$faqid = $adb->query_result($faq_result,$k,'id');
		$moduleid = $adb->query_result($faq_result,$k,'faq_no');
		$result['faq'][$k]['faqno'] = $moduleid;
		$result['faq'][$k]['id'] = $faqid;
		if($check == true) {
			$result['faq'][$k]['product_id']  = $adb->query_result($faq_result,$k,'product_id');
		}
		$result['faq'][$k]['question'] =  nl2br($adb->query_result($faq_result,$k,'question'));
		$result['faq'][$k]['answer'] = nl2br($adb->query_result($faq_result,$k,'answer'));
		$result['faq'][$k]['category'] = $adb->query_result($faq_result,$k,'category');
		$result['faq'][$k]['faqcreatedtime'] = $adb->query_result($faq_result,$k,'createdtime');
		$result['faq'][$k]['faqmodifiedtime'] = $adb->query_result($faq_result,$k,'modifiedtime');

		$faq_comment_query = "select * from aicrm_faqcomments where faqid=? order by createdtime DESC";
		$faq_comment_result = $adb->pquery($faq_comment_query, array($faqid));
		$faq_comment_noofrows = $adb->num_rows($faq_comment_result);
		for($l=0;$l<$faq_comment_noofrows;$l++)
		{
			$faqcomments = nl2br($adb->query_result($faq_comment_result,$l,'comments'));
			$faqcreatedtime = $adb->query_result($faq_comment_result,$l,'createdtime');
			if($faqcomments != '')
			{
				$result['faq'][$k]['comments'][$l] = $faqcomments;
				$result['faq'][$k]['createdtime'][$l] = $faqcreatedtime;
			}
		}
	}
	$adb->println($result);
	return $result;
}

/**	function to save the faq comment
 *	@param array $input_array - array which contains the following values
 => 	int $id - Customer ie., Contact id
	int $sessionid - session id
	int $faqid - faq id
	string $comment - comment to be added with the FAQ
	*	return array $result - This function will call get_KBase_details and return that array
	*/
function save_faq_comment($input_array)
{
	global $adb;
	$adb->println("Entering customer portal function save_faq_comment");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$faqid = (int) $input_array['faqid'];
	$comment = $input_array['comment'];

	if(!validateSession($id,$sessionid))
		return null;

	$createdtime = $adb->formatDate(date('YmdHis'),true);
	if(trim($comment) != '')
	{
		$faq_query = "insert into aicrm_faqcomments values(?,?,?,?)";
		$adb->pquery($faq_query, array('', $faqid, $comment, $createdtime));
	}

	$params = Array('id'=>"$id", 'sessionid'=>"$sessionid");
	$result = get_KBase_details($input_array);

	return $result;
}

/** function to get a list of tickets and to search tickets
 * @param array $input_array - array which contains the following values
 => 	int $id - Customer ie., Contact id
	int $only_mine - if true it will display only tickets related to contact
	otherwise displays tickets related to account it belongs and all the contacts that are under the same account
	int $where - used for searching tickets
	string $match - used for matching tickets
	*	return array $result - This function will call get_KBase_details and return that array
	*/


function get_tickets_list($input_array) {

	require_once('modules/HelpDesk/HelpDesk.php');
	require_once('include/utils/UserInfoUtil.php');

	global $adb,$log;
	global $current_user;
	require_once('modules/Users/Users.php');
	$log->debug("Entering customer portal function get_ticket_list");
	
	$user = new Users();
	$userid = getPortalUserid();
	
	$show_all = show_all('HelpDesk');
	$current_user = $user->retrieveCurrentUserInfoFromFile($userid);
	
	$id = $input_array['id'];
	$only_mine = $input_array['onlymine'];
	$where = $input_array['where']; //addslashes is already added with where condition fields in portal itself
	$match = $input_array['match'];
	$sessionid = $input_array['sessionid'];

	if(!validateSession($id,$sessionid))
		return null;

	// Prepare where conditions based on search query
	$join_type = '';
	$where_conditions = '';
	if(trim($where) != '') {
		if($match == 'all' || $match == '') {
			$join_type = " AND ";
		} elseif($match == 'any') {
			$join_type = " OR ";
		}
		$where = explode("&&&",$where);
		$where_conditions = implode($join_type, $where);
	}

	$entity_ids_list = array();
	if($only_mine == 'true' || $show_all == 'false')
	{
		array_push($entity_ids_list,$id);
	}
	else
	{
		$contactquery = "SELECT contactid, accountid FROM aicrm_contactdetails " .
			" INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid" .
			" AND aicrm_crmentity.deleted = 0 " .
			" WHERE (accountid = (SELECT accountid FROM aicrm_contactdetails WHERE contactid = ?)  AND accountid != 0) OR contactid = ?";
		$contactres = $adb->pquery($contactquery, array($id,$id));
		$no_of_cont = $adb->num_rows($contactres);
		for($i=0;$i<$no_of_cont;$i++)
		{
			$cont_id = $adb->query_result($contactres,$i,'contactid');
			$acc_id = $adb->query_result($contactres,$i,'accountid');
			if(!in_array($cont_id, $entity_ids_list))
				$entity_ids_list[] = $cont_id;
			if(!in_array($acc_id, $entity_ids_list) && $acc_id != '0')
				$entity_ids_list[] = $acc_id;
		}
	}

	$focus = new HelpDesk();
	$focus->filterInactiveFields('HelpDesk');
	foreach ($focus->list_fields as $fieldlabel => $values){
		foreach($values as $table => $fieldname){
			$fields_list[$fieldlabel] = $fieldname;
		}
	}
	$query = "SELECT aicrm_troubletickets.*, aicrm_crmentity.smownerid,aicrm_crmentity.createdtime, aicrm_crmentity.modifiedtime, '' AS setype
		FROM aicrm_troubletickets 
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_troubletickets.ticketid AND aicrm_crmentity.deleted = 0
		WHERE aicrm_troubletickets.parent_id IN (". generateQuestionMarks($entity_ids_list) .")";
	// Add conditions if there are any search parameters
	if ($join_type != '' && $where_conditions != '') {
		$query .= " AND (".$where_conditions.")";
	}
	$params = array($entity_ids_list);

	
	$TicketsfieldVisibilityByColumn = array();
	foreach($fields_list as $fieldlabel=> $fieldname) {
		$TicketsfieldVisibilityByColumn[$fieldname] = 
			getColumnVisibilityPermission($current_user->id,$fieldname,'HelpDesk');
	}
	
	$res = $adb->pquery($query,$params);
	$noofdata = $adb->num_rows($res);
	for( $j= 0;$j < $noofdata; $j++)
	{
		$i=0;
		foreach($fields_list as $fieldlabel => $fieldname) {
			$fieldper = $TicketsfieldVisibilityByColumn[$fieldname]; //in troubletickets the list_fields has columns so we call this API
			if($fieldper == '1'){
				continue;
			}
			$output[0]['head'][0][$i]['fielddata'] = $fieldlabel;
			$fieldvalue = $adb->query_result($res,$j,$fieldname);
			$ticketid = $adb->query_result($res,$j,'ticketid');
			if($fieldname == 'title'){
				$fieldvalue = '<a href="index.php?module=HelpDesk&action=index&fun=detail&ticketid='.$ticketid.'">'.$fieldvalue.'</a>';
			}
			if($fieldname == 'parent_id') {
				$crmid = $fieldvalue;
				$module = getSalesEntityType($crmid);
				if ($crmid != '' && $module != '') {
					$fieldvalues = getEntityName($module, array($crmid));
					if($module == 'Contacts')
					$fieldvalue = '<a href="index.php?module=Contacts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
					elseif($module == 'Accounts')
					$fieldvalue = '<a href="index.php?module=Accounts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
				} else {
					$fieldvalue = '';
				}
			}
			if($fieldname == 'smownerid'){
				$fieldvalue = getOwnerName($fieldvalue);
			}
			$output[1]['data'][$j][$i]['fielddata'] = $fieldvalue;
			$i++;
		}
	}
	$log->debug("Exiting customer portal function get_ticket_list");
	return $output;
}

/**	function used to create ticket which has been created from customer portal
 *	@param array $input_array - array which contains the following values
 => 	int $id - customer id
	int $sessionid - session id
	string $title - title of the ticket
	string $description - description of the ticket
	string $priority - priority of the ticket
	string $severity - severity of the ticket
	string $category - category of the ticket
	string $user_name - customer name
	int $parent_id - parent id ie., customer id as this customer is the parent for this ticket
	int $product_id - product id for the ticket
	string $module - module name where as based on this module we will get the module owner and assign this ticket to that corresponding user
	*	return array - currently created ticket array, if this is not created then all tickets list will be returned
	*/
function create_ticket($input_array)
{
	global $adb,$log;
	$adb->println("Inside customer portal function create_ticket");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$title = $input_array['title'];
	$description = $input_array['description'];
	$priority = $input_array['priority'];
	$severity = $input_array['severity'];
	$category = $input_array['category'];
	$user_name = $input_array['user_name'];
	$parent_id = (int) $input_array['parent_id'];
	$product_id = (int) $input_array['product_id'];
	$module = $input_array['module'];
	//$assigned_to = $input_array['assigned_to'];
	$servicecontractid = $input_array['serviceid'];

	if(!validateSession($id,$sessionid))
		return null;

	$ticket = new HelpDesk();

	$ticket->column_fields[ticket_title] = $title;
	$ticket->column_fields[description]=$description;
	$ticket->column_fields[ticketpriorities]=$priority;
	$ticket->column_fields[ticketseverities]=$severity;
	$ticket->column_fields[ticketcategories]=$category;
	$ticket->column_fields[ticketstatus]='Open';

	$ticket->column_fields[parent_id]=$parent_id;
	$ticket->column_fields[product_id]=$product_id;

	$userid = getPortalUserid();
		
	$ticket->column_fields['assigned_user_id']=$userid;

	$ticket->save("HelpDesk");

	$subject = "[From Portal] " .$ticket->column_fields['ticket_no']." [ Ticket ID : $ticket->id ] ".$title;
	$contents = ' Ticket No : '.$ticket->column_fields['ticket_no']. '<br> Ticket ID : '.$ticket->id.'<br> Ticket Title : '.$title.'<br><br>'.$description;

	//get the contact email id who creates the ticket from portal and use this email as from email id in email
	$result = $adb->pquery("select email from aicrm_contactdetails where contactid=?", array($parent_id));
	$contact_email = $adb->query_result($result,0,'email');
	$from_email = $contact_email;

	//send mail to assigned to user
	$to_email = getUserEmailId('id',$userid);
	$adb->println("Send mail to the user who is the owner of the module about the portal ticket");
	$mail_status = send_mail('HelpDesk',$to_email,'',$from_email,$subject,$contents);

	//send mail to the customer(contact who creates the ticket from portal)
	$adb->println("Send mail to the customer(contact) who creates the portal ticket");
	$mail_status = send_mail('Contacts',$contact_email,'',$from_email,$subject,$contents);

	$ticketresult = $adb->pquery("select aicrm_troubletickets.ticketid from aicrm_troubletickets
		inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_troubletickets.ticketid inner join aicrm_ticketcf on aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid 
		where aicrm_crmentity.deleted=0 and aicrm_troubletickets.ticketid = ?", array($ticket->id));
	if($adb->num_rows($ticketresult) == 1)
	{
		$record_save = 1;
		$record_array[0]['new_ticket']['ticketid'] = $adb->query_result($ticketresult,0,'ticketid');
	}
	if($servicecontractid != ''){
		$res = $adb->pquery("insert into aicrm_crmentityrel values(?,?,?,?)",
		array($servicecontractid, 'ServiceContracts', $ticket->id, 'HelpDesk'));
	}
	if($record_save == 1)
	{
		$adb->println("Ticket from Portal is saved with id => ".$ticket->id);
		return $record_array;
	}
	else
	{
		$adb->println("There may be error in saving the ticket.");
		return null;
	}
}

/**	function used to update the ticket comment which is added from the customer portal
 *	@param array $input_array - array which contains the following values
 => 	int $id - customer id
	int $sessionid - session id
	int $ticketid - ticket id
	int $ownerid - customer ie., contact id who has added this ticket comment
	string $comments - comment which is added from the customer portal
	*	return void
	*/
function update_ticket_comment($input_array)
{
	global $adb,$mod_strings;
	$adb->println("Inside customer portal function update_ticket_comment");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$ticketid = (int) $input_array['ticketid'];
	$ownerid = (int) $input_array['ownerid'];
	$comments = $input_array['comments'];

	if(!validateSession($id,$sessionid))
		return null;

	$servercreatedtime = $adb->formatDate(date('YmdHis'), true);
	if(trim($comments) != '')
	{
		$sql = "insert into aicrm_ticketcomments values(?,?,?,?,?,?)";
		$params1 = array('', $ticketid, $comments, $ownerid, 'customer', $servercreatedtime);
		$adb->pquery($sql, $params1);

		$updatequery = "update aicrm_crmentity set modifiedtime=? where crmid=?";
		$updateparams = array($servercreatedtime, $ticketid);
		$adb->pquery($updatequery, $updateparams);

		//To get the username and user email id, user means assigned to user of the ticket
		$result = $adb->pquery("select user_name, email1 from aicrm_users inner join aicrm_crmentity on aicrm_users.id=aicrm_crmentity.smownerid where aicrm_crmentity.crmid=?", array($ticketid));
		$owner = $adb->query_result($result,0,'user_name');
		$to_email = $adb->query_result($result,0,'email1');

		//To get the contact name
		$result1 = $adb->pquery("select lastname, firstname, email from aicrm_contactdetails where contactid=?", array($ownerid));
		$customername = $adb->query_result($result1,0,'firstname').' '.$adb->query_result($result1,0,'lastname');
		$customername = decode_html($customername);//Fix to display the original UTF-8 characters in sendername instead of ascii characters
		$from_email = $adb->query_result($result1,0,'email');

		//send mail to the assigned to user when customer add comment
		$subject = $mod_strings['LBL_RESPONDTO_TICKETID']."##". $ticketid."##". $mod_strings['LBL_CUSTOMER_PORTAL'];
		$contents = $mod_strings['Dear']." ".$owner.","."<br><br>"
		.$mod_strings['LBL_CUSTOMER_COMMENTS']."<br><br>

		<b>".nl2br($comments)."</b><br><br>"

		.$mod_strings['LBL_RESPOND']."<br><br>"

		.$mod_strings['LBL_REGARDS']."<br>"
		.$mod_strings['LBL_SUPPORT_ADMIN'];

		$mailstatus = send_mail('HelpDesk',$to_email,$customername,$from_email,$subject,$contents);
	}
}

/**	function used to close the ticket
 *	@param array $input_array - array which contains the following values
 => 	int $id - customer id
	int $sessionid - session id
	int $ticketid - ticket id
	*	return string - success or failure message will be returned based on the ticket close update query
	*/
function close_current_ticket($input_array)
{
	global $adb,$mod_strings,$log,$current_user;
	require_once('modules/HelpDesk/HelpDesk.php');
	$adb->println("Inside customer portal function close_current_ticket");
	$adb->println($input_array);

	//foreach($input_array as $fieldname => $fieldvalue)$input_array[$fieldname] = mysql_real_escape_string($fieldvalue);
	$userid = getPortalUserid();
	
	$current_user->id = $userid;
	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$ticketid = (int) $input_array['ticketid'];

	if(!validateSession($id,$sessionid))
		return null;
		
	$focus = new HelpDesk();
	$focus->id = $ticketid;
	$focus->retrieve_entity_info($focus->id,'HelpDesk');
	$focus->mode = 'edit';
	$focus->column_fields['ticketstatus'] ='Closed';
	$focus->save("HelpDesk");
	return "closed";
}

/**	function used to authenticate whether the customer has access or not
 *	@param string $username - customer name for the customer portal
 *	@param string $password - password for the customer portal
 *	@param string $login - true or false. If true means function has been called for login process and we have to clear the session if any, false means not called during login and we should not unset the previous sessions
 *	return array $list - returns array with all the customer details
 */
function authenticate_user($username,$password,$version,$login = 'true')
{
	global $adb,$log;
	$adb->println("Inside customer portal function authenticate_user($username, $password, $login).");
	include('vtigerversion.php');
	if(version_compare($version,$aicrm_current_version,'==') == 0){
		$list[0] = "NOT COMPATIBLE";
  		return $list;
	}
	$username = $adb->sql_escape_string($username);
	$password = $adb->sql_escape_string($password);

	$current_date = date("Y-m-d");
	$sql = "select id, user_name, user_password,last_login_time, support_start_date, support_end_date from aicrm_portalinfo inner join aicrm_customerdetails on aicrm_portalinfo.id=aicrm_customerdetails.customerid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_portalinfo.id where aicrm_crmentity.deleted=0 and user_name=? and user_password = ? and isactive=1 and aicrm_customerdetails.portal=1 and aicrm_customerdetails.support_end_date >= ?";
	$result = $adb->pquery($sql, array($username, $password, $current_date));
	$err[0]['err1'] = "MORE_THAN_ONE_USER";
	$err[1]['err1'] = "INVALID_USERNAME_OR_PASSWORD";

	$num_rows = $adb->num_rows($result);

	if($num_rows > 1)		return $err[0];//More than one user
	elseif($num_rows <= 0)		return $err[1];//No user

	$customerid = $adb->query_result($result,0,'id');

	$list[0]['id'] = $customerid;
	$list[0]['user_name'] = $adb->query_result($result,0,'user_name');
	$list[0]['user_password'] = $adb->query_result($result,0,'user_password');
	$list[0]['last_login_time'] = $adb->query_result($result,0,'last_login_time');
	$list[0]['support_start_date'] = $adb->query_result($result,0,'support_start_date');
	$list[0]['support_end_date'] = $adb->query_result($result,0,'support_end_date');

	//During login process we will pass the value true. Other times (change password) we will pass false
	if($login != 'false')
	{
		$sessionid = makeRandomPassword();

		unsetServerSessionId($customerid);

		$sql="insert into aicrm_soapservice values(?,?,?)";
		$result = $adb->pquery($sql, array($customerid,'customer' ,$sessionid));

		$list[0]['sessionid'] = $sessionid;
	}

	return $list;
}

/**	function used to change the password for the customer portal
 *	@param array $input_array - array which contains the following values
 => 	int $id - customer id
	int $sessionid - session id
	string $username - customer name
	string $password - new password to change
	*	return array $list - returns array with all the customer details
	*/
function change_password($input_array)
{
	global $adb,$log;
	$log->debug("Entering customer portal function change_password");
	$adb->println($input_array);

	$id = (int) $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$username = $input_array['username'];
	$password = $input_array['password'];
	$version = $input_array['version'];

	if(!validateSession($id,$sessionid))
		return null;

	$list = authenticate_user($username,$password,$version ,'false');
	if(!empty($list[0]['id'])){
		return array('MORE_THAN_ONE_USER');
	}
	$sql = "update aicrm_portalinfo set user_password=? where id=? and user_name=?";
	$result = $adb->pquery($sql, array($password, $id, $username));

	$log->debug("Exiting customer portal function change_password");
	return $list;
}

/**	function used to update the login details for the customer
 *	@param array $input_array - array which contains the following values
 => 	int $id - customer id
	int $sessionid - session id
	string $flag - login/logout, based on this flag, login or logout time will be updated for the customer
	*	return string $list - empty value
	*/
function update_login_details($input_array)
{
	global $adb,$log;
	$log->debug("Entering customer portal function update_login_details");
	$adb->println("INPUT ARRAY for the function update_login_details");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$flag = $input_array['flag'];

	if(!validateSession($id,$sessionid))
		return null;

	$current_time = $adb->formatDate(date('YmdHis'), true);

	if($flag == 'login')
	{
		$sql = "update aicrm_portalinfo set login_time=? where id=?";
		$result = $adb->pquery($sql, array($current_time, $id));
	}
	elseif($flag == 'logout')
	{
		$sql = "update aicrm_portalinfo set logout_time=?, last_login_time=login_time where id=?";
		$result = $adb->pquery($sql, array($current_time, $id));
	}
	$log->debug("Exiting customer portal function update_login_details");
}

/**	function used to send mail to the customer when he forgot the password and want to retrieve the password
 *	@param string $mailid - email address of the customer
 *	return message about the mail sending whether entered mail id is correct or not or is there any problem in mail sending
 */
function send_mail_for_password($mailid)
{
	global $adb,$mod_strings,$log;
	$log->debug("Entering customer portal function send_mail_for_password");
	$adb->println("Inside the function send_mail_for_password($mailid).");

	$sql = "select * from aicrm_portalinfo  where user_name = ? ";
	$res = $adb->pquery($sql, array($mailid));
	$user_name = $adb->query_result($res,0,'user_name');
	$password = $adb->query_result($res,0,'user_password');
	$isactive = $adb->query_result($res,0,'isactive');

	$fromquery = "select aicrm_users.user_name, aicrm_users.email1 from aicrm_users inner join aicrm_crmentity on aicrm_users.id = aicrm_crmentity.smownerid inner join aicrm_contactdetails on aicrm_contactdetails.contactid=aicrm_crmentity.crmid where aicrm_contactdetails.email =?";
	$from_res = $adb->pquery($fromquery, array($mailid));
	$initialfrom = $adb->query_result($from_res,0,'user_name');
	$from = $adb->query_result($from_res,0,'email1');

	$contents = $mod_strings['LBL_LOGIN_DETAILS'];
	$contents .= "<br><br>".$mod_strings['LBL_USERNAME']." ".$user_name;
	$contents .= "<br>".$mod_strings['LBL_PASSWORD']." ".$password;

	$mail = new PHPMailer();

	$mail->Subject = $mod_strings['LBL_SUBJECT_PORTAL_LOGIN_DETAILS'];
	$mail->Body    = $contents;
	$mail->IsSMTP();

	$mailserverresult = $adb->pquery("select * from aicrm_systems where server_type=?", array('email'));
	$mail_server = $adb->query_result($mailserverresult,0,'server');
	$mail_server_username = $adb->query_result($mailserverresult,0,'server_username');
	$mail_server_password = $adb->query_result($mailserverresult,0,'server_password');
	$smtp_auth = $adb->query_result($mailserverresult,0,'smtp_auth');

	$mail->Host = $mail_server;
	if($smtp_auth == 'true')
	$mail->SMTPAuth = 'true';
	$mail->Username = $mail_server_username;
	$mail->Password = $mail_server_password;
	$mail->From = $from;
	$mail->FromName = $initialfrom;

	$mail->AddAddress($user_name);
	$mail->AddReplyTo($current_user->name);
	$mail->WordWrap = 50;

	$mail->IsHTML(true);

	$mail->AltBody = $mod_strings['LBL_ALTBODY'];
	if($mailid == '')
	{
		$ret_msg = "false@@@<b>".$mod_strings['LBL_GIVE_MAILID']."</b>";
	}
	elseif($user_name == '' && $password == '')
	{
		$ret_msg = "false@@@<b>".$mod_strings['LBL_CHECK_MAILID']."</b>";
	}
	elseif($isactive == 0)
	{
		$ret_msg = "false@@@<b>".$mod_strings['LBL_LOGIN_REVOKED']."</b>";
	}
	elseif(!$mail->Send())
	{
		$ret_msg = "false@@@<b>".$mod_strings['LBL_MAIL_COULDNOT_SENT']."</b>";
	}
	else
	{
		$ret_msg = "true@@@<b>".$mod_strings['LBL_MAIL_SENT']."</b>";
	}

	$adb->println("Exit from send_mail_for_password. $ret_msg");
	$log->debug("Exiting customer portal function send_mail_for_password");
	return $ret_msg;
}

/**	function used to get the ticket creater
 *	@param array $input_array - array which contains the following values
 =>	int $id - customer ie., contact id
	int $sessionid - session id
	int $ticketid - ticket id
	*	return int $creator - ticket created user id will be returned ie., smcreatorid from crmentity table
	*/
function get_ticket_creator($input_array)
{
	global $adb,$log;
	$log->debug("Entering customer portal function get_ticket_creator");
	$adb->println("INPUT ARRAY for the function get_ticket_creator");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$ticketid = (int) $input_array['ticketid'];

	if(!validateSession($id,$sessionid))
		return null;

	$res = $adb->pquery("select smcreatorid from aicrm_crmentity where crmid=?", array($ticketid));
	$creator = $adb->query_result($res,0,'smcreatorid');
	$log->debug("Exiting customer portal function get_ticket_creator");
	return $creator;
}

/**	function used to get the picklist values
 *	@param array $input_array - array which contains the following values
 =>	int $id - customer ie., contact id
	int $sessionid - session id
	string $picklist_name - picklist name you want to retrieve from database
	*	return array $picklist_array - all values of the corresponding picklist will be returned as a array
	*/
function get_picklists($input_array)
{
	global $adb, $log;
	$log->debug("Entering customer portal function get_picklists");
	$adb->println("INPUT ARRAY for the function get_picklists");
	$adb->println($input_array);

	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$picklist_name = $adb->sql_escape_string($input_array['picklist_name']);

	if(!validateSession($id,$sessionid))
	return null;

	$picklist_array = Array();

	$admin_role = 'H2';
	$userid = getPortalUserid();
	$roleres = $adb->pquery("SELECT roleid from aicrm_user2role where userid = ?", array($userid));
	$RowCount = $adb->num_rows($roleres);
	if($RowCount > 0){
		$admin_role = $adb->query_result($roleres,0,'roleid');
	}

	$res = $adb->pquery("select aicrm_". $picklist_name.".* from aicrm_". $picklist_name." inner join aicrm_role2picklist on aicrm_role2picklist.picklistvalueid = aicrm_". $picklist_name.".picklist_valueid and aicrm_role2picklist.roleid='$admin_role'", array());
	for($i=0;$i<$adb->num_rows($res);$i++)
	{
		$picklist_val = $adb->query_result($res,$i,$picklist_name);
		$picklist_array[$i] = $picklist_val;
	}

	$adb->println($picklist_array);
	$log->debug("Exiting customer portal function get_picklists($picklist_name)");
	return $picklist_array;
}

/**	function to get the attachments of a ticket
 *	@param array $input_array - array which contains the following values
 =>	int $id - customer ie., contact id
	int $sessionid - session id
	int $ticketid - ticket id
	*	return array $output - This will return all the file details related to the ticket
	*/
function get_ticket_attachments($input_array)
{
	global $adb,$log;
	$log->debug("Entering customer portal function get_ticket_attachments");
	$adb->println("INPUT ARRAY for the function get_ticket_attachments");
	$adb->println($input_array);

	$check = checkModuleActive('Documents');
	if($check == false){
		return array("#MODULE INACTIVE#");
	}
	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$ticketid = $input_array['ticketid'];

	$isPermitted = check_permission($id,'HelpDesk',$ticketid);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}


	if(!validateSession($id,$sessionid))
	return null;

	$query = "select aicrm_troubletickets.ticketid, aicrm_attachments.*,aicrm_notes.filename,aicrm_notes.filelocationtype from aicrm_troubletickets " .
		"left join aicrm_senotesrel on aicrm_senotesrel.crmid=aicrm_troubletickets.ticketid " .
		"left join aicrm_notes on aicrm_notes.notesid=aicrm_senotesrel.notesid " .
		"inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_notes.notesid " .
		"left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_notes.notesid " .
		"left join aicrm_attachments on aicrm_attachments.attachmentsid = aicrm_seattachmentsrel.attachmentsid " .
		"and aicrm_crmentity.deleted = 0 where aicrm_troubletickets.ticketid =?";

	$res = $adb->pquery($query, array($ticketid));
	$noofrows = $adb->num_rows($res);
	for($i=0;$i<$noofrows;$i++)
	{
		$filename = $adb->query_result($res,$i,'filename');
		$filepath = $adb->query_result($res,$i,'path');

		$fileid = $adb->query_result($res,$i,'attachmentsid');
		$filesize = filesize($filepath.$fileid."_".$filename);
		$filetype = $adb->query_result($res,$i,'type');
		$filelocationtype = $adb->query_result($res,$i,'filelocationtype');
		//Now we will not pass the file content to CP, when the customer click on the link we will retrieve
		//$filecontents = base64_encode(file_get_contents($filepath.$fileid."_".$filename));//fread(fopen($filepath.$filename, "r"), $filesize));

		$output[$i]['fileid'] = $fileid;
		$output[$i]['filename'] = $filename;
		$output[$i]['filetype'] = $filetype;
		$output[$i]['filesize'] = $filesize;
		$output[$i]['filelocationtype'] = $filelocationtype;
	}
	$log->debug("Exiting customer portal function get_ticket_attachments");
	return $output;
}

/**	function used to get the contents of a file
 *	@param array $input_array - array which contains the following values
 =>	int $id - customer ie., contact id
	int $sessionid - session id
	int $fileid - id of the file to which we want contents
	string $filename - name of the file to which we want contents
	*	return $filecontents array with single file contents like [fileid] => filecontent
	*/
function get_filecontent($input_array)
{
	global $adb,$log;
	$log->debug("Entering customer portal function get_filecontent");
	$adb->println("INPUT ARRAY for the function get_filecontent");
	$adb->println($input_array);
	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$fileid = $input_array['fileid'];
	$filename = $input_array['filename'];
	$ticketid = $input_array['ticketid'];
	if(!validateSession($id,$sessionid))
	return null;

	$query = 'SELECT aicrm_attachments.path FROM aicrm_attachments
	INNER JOIN aicrm_seattachmentsrel ON aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid 
	INNER JOIN aicrm_notes ON aicrm_notes.notesid = aicrm_seattachmentsrel.crmid 
	INNER JOIN aicrm_senotesrel ON aicrm_senotesrel.notesid = aicrm_notes.notesid 
	INNER JOIN aicrm_troubletickets ON aicrm_troubletickets.ticketid = aicrm_senotesrel.crmid 
	WHERE aicrm_troubletickets.ticketid = ? AND aicrm_attachments.name = ? AND aicrm_attachments.attachmentsid = ?';
	$res = $adb->pquery($query, array($ticketid, $filename,$fileid));
	if($adb->num_rows($res)>0)
	{
		$filenamewithpath = $adb->query_result($res,0,'path').$fileid."_".$filename;
		$filecontents[$fileid] = base64_encode(file_get_contents($filenamewithpath));
	}
	$log->debug("Exiting customer portal function get_filecontent ");
	return $filecontents;
}

/**	function to add attachment for a ticket ie., the passed contents will be write in a file and the details will be stored in database
 *	@param array $input_array - array which contains the following values
 =>	int $id - customer ie., contact id
	int $sessionid - session id
	int $ticketid - ticket id
	string $filename - file name to be attached with the ticket
	string $filetype - file type
	int $filesize - file size
	string $filecontents - file contents as base64 encoded format
	*	return void
	*/
function add_ticket_attachment($input_array)
{
	global $adb,$log;
	global $root_directory, $upload_badext;
	$log->debug("Entering customer portal function add_ticket_attachment");
	$adb->println("INPUT ARRAY for the function add_ticket_attachment");
	$adb->println($input_array);
	$id = $input_array['id'];
	$sessionid = $input_array['sessionid'];
	$ticketid = $input_array['ticketid'];
	$filename = $input_array['filename'];
	$filetype = $input_array['filetype'];
	$filesize = $input_array['filesize'];
	$filecontents = $input_array['filecontents'];

	if(!validateSession($id,$sessionid))
	return null;

	//decide the file path where we should upload the file in the server
	$upload_filepath = decideFilePath();

	$attachmentid = $adb->getUniqueID("aicrm_crmentity");

	//fix for space in file name
	$filename = preg_replace('/\s+/', '_', $filename);
	$ext_pos = strrpos($filename, ".");
	$ext = substr($filename, $ext_pos + 1);

	if (in_array(strtolower($ext), $upload_badext)){
		$filename .= ".txt";
	}
	$new_filename = $attachmentid.'_'.$filename;

	$data = base64_decode($filecontents);
	$description = 'CustomerPortal Attachment';

	//write a file with the passed content
	$handle = @fopen($upload_filepath.$new_filename,'w');
	fputs($handle, $data);
	fclose($handle);

	//Now store this file information in db and relate with the ticket
	$date_var = $adb->formatDate(date('Y-m-d H:i:s'), true);

	$crmquery = "insert into aicrm_crmentity (crmid,setype,description,createdtime) values(?,?,?,?)";
	$crmresult = $adb->pquery($crmquery, array($attachmentid, 'HelpDesk Attachment', $description, $date_var));

	$attachmentquery = "insert into aicrm_attachments(attachmentsid,name,description,type,path) values(?,?,?,?,?)";
	$attachmentreulst = $adb->pquery($attachmentquery, array($attachmentid, $filename, $description, $filetype, $upload_filepath));

	$relatedquery = "insert into aicrm_seattachmentsrel values(?,?)";
	$relatedresult = $adb->pquery($relatedquery, array($ticketid, $attachmentid));

	$user_id = getPortalUserid();

	require_once('modules/Documents/Documents.php');
	$focus = new Documents();
	$focus->column_fields['notes_title'] = $filename;
	$focus->column_fields['filename'] = $filename;
	$focus->column_fields['filetype'] = $filetype;
	$focus->column_fields['filesize'] = $filesize;
	$focus->column_fields['filelocationtype'] = 'I';
	$focus->column_fields['filedownloadcount']= 0;
	$focus->column_fields['filestatus'] = 1;
	$focus->column_fields['assigned_user_id'] = $user_id;
	$focus->column_fields['folderid'] = 1;
	$focus->parent_id = $ticketid;
	$focus->save('Documents');

	$related_doc = 'insert into aicrm_seattachmentsrel values (?,?)';
	$res = $adb->pquery($related_doc,array($focus->id,$attachmentid));

	$tic_doc = 'insert into aicrm_senotesrel values(?,?)';
	$res = $adb->pquery($tic_doc,array($ticketid,$focus->id));
	$log->debug("Exiting customer portal function add_ticket_attachment");
}

/**	Function used to validate the session
 *	@param int $id - contact id to which we want the session id
 *	@param string $sessionid - session id which will be passed from customerportal
 *	return true/false - return true if valid session otherwise return false
 **/
function validateSession($id, $sessionid)
{
	global $adb;
	$adb->println("Inside function validateSession($id, $sessionid)");

	$server_sessionid = getServerSessionId($id);

	$adb->println("Checking Server session id and customer input session id ==> $server_sessionid == $sessionid");

	if($server_sessionid == $sessionid)
	{
		$adb->println("Session id match. Authenticated to do the current operation.");
		return true;
	}
	else
	{
		$adb->println("Session id does not match. Not authenticated to do the current operation.");
		return false;
	}
}


/**	Function used to get the session id which was set during login time
 *	@param int $id - contact id to which we want the session id
 *	return string $sessionid - return the session id for the customer which is a random alphanumeric character string
 **/
function getServerSessionId($id)
{
	global $adb;
	$adb->println("Inside the function getServerSessionId($id)");

	//To avoid SQL injection we are type casting as well as bound the id variable. In each and every function we will call this function
	$id = (int) $id;

	$sessionid = Vtiger_Soap_CustomerPortal::lookupSessionId($id);
	if($sessionid === false) {
		$query = "select * from aicrm_soapservice where type='customer' and id=?";
		$sessionid = $adb->query_result($adb->pquery($query, array($id)),0,'sessionid');
		Vtiger_Soap_CustomerPortal::updateSessionId($id, $sessionid);
	}
	return $sessionid;
}

/**	Function used to unset the server session id for the customer
 *	@param int $id - contact id to which customer we want to unset the session id
 **/
function unsetServerSessionId($id)
{
	global $adb,$log;
	$log->debug("Entering customer portal function unsetServerSessionId");
	$adb->println("Inside the function unsetServerSessionId");

	$id = (int) $id;
	Vtiger_Soap_CustomerPortal::updateSessionId($id, false);

	$adb->pquery("delete from aicrm_soapservice where type='customer' and id=?", array($id));
	$log->debug("Exiting customer portal function unsetServerSessionId");
	return;
}


/**	function used to get the Account name
 *	@param int $id - Account id
 *	return string $message - Account name returned
 */
function get_account_name($accountid)
{
	global $adb,$log;
	$log->debug("Entering customer portal function get_account_name");
	$res = $adb->pquery("select accountname from aicrm_account where accountid=?", array($accountid));
	$accountname=$adb->query_result($res,0,'accountname');
	$log->debug("Exiting customer portal function get_account_name");
	return $accountname;
}

/** function used to get the Contact name
 *  @param int $id -Contact id
 * return string $message -Contact name returned
 */
function get_contact_name($contactid)
{
	global $adb,$log;
	$log->debug("Entering customer portal function get_contact_name");
	$contact_name = '';
	if($contactid != '')
	{
		$sql = "select firstname,lastname from aicrm_contactdetails where contactid=?";
		$result = $adb->pquery($sql, array($contactid));
		$firstname = $adb->query_result($result,0,"firstname");
		$lastname = $adb->query_result($result,0,"lastname");
		$contact_name = $firstname." ".$lastname;
		return $contact_name;
	}
	$log->debug("Exiting customer portal function get_contact_name");
	return false;
}

/**     function used to get the Account id
 **      @param int $id - Contact id
 **      return string $message - Account id returned
 **/

function get_check_account_id($id)
{
	global $adb,$log;
	$log->debug("Entering customer portal function get_check_account_id");
	$res = $adb->pquery("select accountid from aicrm_contactdetails where contactid=?", array($id));
	$accountid=$adb->query_result($res,0,'accountid');
	$log->debug("Entering customer portal function get_check_account_id");
	return $accountid;
}


/**	function used to get the vendor name
 *	@param int $id - vendor id
 *	return string $name - Vendor name returned
 */

function get_vendor_name($vendorid)
{
	global $adb,$log;
	$log->debug("Entering customer portal function get_vendor_name");
	$res = $adb->pquery("select vendorname from aicrm_vendor where vendorid=?", array($vendorid));
	$name=$adb->query_result($res,0,'vendorname');
	$log->debug("Exiting customer portal function get_vendor_name");
	return $name;
}


/**	function used to get the Quotes/Invoice List
 *	@param int $id - id -Contactid
 *	return string $output - Quotes/Invoice list Array
 */

function get_list_values($id,$module,$sessionid,$only_mine='true')
{
	require_once('modules/'.$module.'/'.$module.'.php');
	require_once('include/utils/UserInfoUtil.php');
	require_once('modules/Users/Users.php');
	global $adb,$log,$current_user;
	$log->debug("Entering customer portal function get_list_values");
	$check = checkModuleActive($module);
	if($check == false){
		return array("#MODULE INACTIVE#");
	}
	$user = new Users();
	$userid = getPortalUserid();
	$current_user = $user->retrieveCurrentUserInfoFromFile($userid);
	$focus = new $module();
	$focus->filterInactiveFields($module);
	foreach ($focus->list_fields as $fieldlabel => $values){
		foreach($values as $table => $fieldname){
			$fields_list[$fieldlabel] = $fieldname;
		}
	}

	if(!validateSession($id,$sessionid))
	return null;

	$entity_ids_list = array();
	$show_all=show_all($module);
	if($only_mine == 'true' || $show_all == 'false')
	{
		array_push($entity_ids_list,$id);
	}
	else
	{
		$contactquery = "SELECT contactid, accountid FROM aicrm_contactdetails " .
			" INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid" .
			" AND aicrm_crmentity.deleted = 0 " .
			" WHERE (accountid = (SELECT accountid FROM aicrm_contactdetails WHERE contactid = ?)  AND accountid != 0) OR contactid = ?";
		$contactres = $adb->pquery($contactquery, array($id,$id));
		$no_of_cont = $adb->num_rows($contactres);
		for($i=0;$i<$no_of_cont;$i++)
		{
			$cont_id = $adb->query_result($contactres,$i,'contactid');
			$acc_id = $adb->query_result($contactres,$i,'accountid');
			if(!in_array($cont_id, $entity_ids_list))
			$entity_ids_list[] = $cont_id;
			if(!in_array($acc_id, $entity_ids_list) && $acc_id != '0')
			$entity_ids_list[] = $acc_id;
		}
	}
	if($module == 'Quotes')
	{
		$query = "select distinct aicrm_quotes.*,aicrm_crmentity.smownerid,
		case when aicrm_quotes.contactid is not null then aicrm_quotes.contactid else aicrm_quotes.accountid end as entityid,
		case when aicrm_quotes.contactid is not null then 'Contacts' else 'Accounts' end as setype,
		aicrm_potential.potentialname,aicrm_account.accountid 
		from aicrm_quotes left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_quotes.quoteid 
		LEFT OUTER JOIN aicrm_account
		ON aicrm_account.accountid = aicrm_quotes.accountid
		LEFT OUTER JOIN aicrm_potential
		ON aicrm_potential.potentialid = aicrm_quotes.potentialid 
		where aicrm_crmentity.deleted=0 and (aicrm_quotes.accountid in  (". generateQuestionMarks($entity_ids_list) .") or contactid in (". generateQuestionMarks($entity_ids_list) ."))";		
		$params = array($entity_ids_list,$entity_ids_list);
		$fields_list['Related To'] = 'entityid';

	}
	else if($module == 'Invoice')
	{
		$query ="select distinct aicrm_invoice.*,aicrm_crmentity.smownerid,
		case when aicrm_invoice.contactid !=0 then aicrm_invoice.contactid else aicrm_invoice.accountid end as entityid,
		case when aicrm_invoice.contactid !=0 then 'Contacts' else 'Accounts' end as setype
		from aicrm_invoice 
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_invoice.invoiceid 
		where aicrm_crmentity.deleted=0 and (accountid in (". generateQuestionMarks($entity_ids_list) .") or contactid in  (". generateQuestionMarks($entity_ids_list) ."))";
		$params = array($entity_ids_list,$entity_ids_list);
		$fields_list['Related To'] = 'entityid';
	}
	else if ($module == 'Documents')
	{
		$query ="select aicrm_notes.*, aicrm_crmentity.*, aicrm_senotesrel.crmid as entityid, '' as setype,aicrm_attachmentsfolder.foldername from aicrm_notes " .
		"inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_notes.notesid " .
		"left join aicrm_senotesrel on aicrm_senotesrel.notesid=aicrm_notes.notesid " .
		"LEFT JOIN aicrm_attachmentsfolder ON aicrm_attachmentsfolder.folderid = aicrm_notes.folderid " .
		"where aicrm_crmentity.deleted = 0 and  aicrm_senotesrel.crmid in (".generateQuestionMarks($entity_ids_list).")"; 
		$params = array($entity_ids_list);
		$fields_list['Related To'] = 'entityid';
	}else if ($module == 'Contacts'){
		$query = "select aicrm_contactdetails.*,aicrm_crmentity.smownerid from aicrm_contactdetails
		 inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
		 where aicrm_crmentity.deleted = 0 and contactid IN (".generateQuestionMarks($entity_ids_list).")";	
		$params = array($entity_ids_list);
	}
	$res = $adb->pquery($query,$params);
	$noofdata = $adb->num_rows($res);
	
	
	$columnVisibilityByFieldnameInfo = array();
	if($noofdata) {
		foreach($fields_list as $fieldlabel =>$fieldname ) {
			$columnVisibilityByFieldnameInfo[$fieldname] = getColumnVisibilityPermission($current_user->id,$fieldname,$module);
		}
	}
	
	
	for( $j= 0;$j < $noofdata; $j++)
	{
		$i=0;
		foreach($fields_list as $fieldlabel =>$fieldname ) {
			$fieldper = $columnVisibilityByFieldnameInfo[$fieldname];
			if($fieldper == '1' && $fieldname != 'entityid'){
				continue;
			}
			$fieldlabel = getTranslatedString($fieldlabel,$module);
				
			$output[0][$module]['head'][0][$i]['fielddata'] = $fieldlabel;
			$fieldvalue = $adb->query_result($res,$j,$fieldname);

			if($module == 'Quotes')
			{
				if($fieldname =='subject'){
					$fieldid = $adb->query_result($res,$j,'quoteid');
					$filename = $fieldid.'_Quotes.pdf';
					$fieldvalue = '<a href="index.php?&module=Quotes&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
				}
				if($fieldname == 'total'){
					$sym = getCurrencySymbol($res,$j,'currency_id');
					$fieldvalue = $sym.$fieldvalue;
				}
			}
			if($module == 'Invoice')
			{
				if($fieldname =='subject'){
					$fieldid = $adb->query_result($res,$j,'invoiceid');
					$filename = $fieldid.'_Invoice.pdf';
					$fieldvalue = '<a href="index.php?&module=Invoice&action=index&status=true&id='.$fieldid.'">'.$fieldvalue.'</a>';
				}
				if($fieldname == 'total'){
					$sym = getCurrencySymbol($res,$j,'currency_id');
					$fieldvalue = $sym.$fieldvalue;
				}
			}
			if($module == 'Documents')
			{
				if($fieldname == 'title'){
					$fieldid = $adb->query_result($res,$j,'notesid');
					$fieldvalue = '<a href="index.php?&module=Documents&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
				}
				if( $fieldname == 'filename'){
					$fieldid = $adb->query_result($res,$j,'notesid');
					$filename = $fieldvalue;
					$folderid = $adb->query_result($res,$j,'folderid');
					$filename = $adb->query_result($res,$j,'filename');
					$fileactive = $adb->query_result($res,$j,'filestatus');
					$filetype = $adb->query_result($res,$j,'filelocationtype');

					if($fileactive == 1){
						if($filetype == 'I'){
							$fieldvalue = '<a href="index.php?&downloadfile=true&folderid='.$folderid.'&filename='.$filename.'&module=Documents&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
						}
						elseif($filetype == 'E'){
							$fieldvalue = '<a target="_blank" href="'.$filename.'" onclick = "updateCount('.$fieldid.');">'.$filename.'</a>';
						}
					}else{
						$fieldvalue = $filename;
					}
				}
				if($fieldname == 'folderid'){
					$fieldvalue = $adb->query_result($res,$j,'foldername');
				}
			}
			if($module == 'Invoice' && $fieldname == 'salesorderid')
			{
				if($fieldvalue != '')
				$fieldvalue = get_salesorder_name($fieldvalue);
			}
				
			if($module == 'Services'){
				if($fieldname == 'servicename'){
					$fieldid = $adb->query_result($res,$j,'serviceid');
					$fieldvalue = '<a href="index.php?module=Services&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
				}
				if($fieldname == 'discontinued'){
					if($fieldvalue == 1){
						$fieldvalue = 'Yes';
					}else{
						$fieldvalue = 'No';
					}
				}
				if($fieldname == 'unit_price'){
					$sym = getCurrencySymbol($res,$j,'currency_id');
					$fieldvalue = $sym.$fieldvalue;
				}

			}
			if($module == 'Contacts'){
				if($fieldname == 'lastname' || $fieldname == 'firstname'){
					$fieldid = $adb->query_result($res,$j,'contactid');
					$fieldvalue ='<a href="index.php?module=Contacts&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
				}
			}
			if($fieldname == 'entityid' || $fieldname == 'contactid' || $fieldname == 'accountid' || $fieldname == 'potentialid' ) {
				$crmid = $fieldvalue;
				$modulename = getSalesEntityType($crmid);
				if ($crmid != '' && $modulename != '') {
					$fieldvalues = getEntityName($modulename, array($crmid));
					if($modulename == 'Contacts')
					$fieldvalue = '<a href="index.php?module=Contacts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
					elseif($modulename == 'Accounts')
					$fieldvalue = '<a href="index.php?module=Accounts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
					elseif($modulename == 'Potentials'){
						$fieldvalue = $adb->query_result($res,$j,'potentialname');
					}
				} else {
					$fieldvalue = '';
				}
			}
			if($fieldname == 'smownerid'){
				$fieldvalue = getOwnerName($fieldvalue);
			}
			$output[1][$module]['data'][$j][$i]['fielddata'] = $fieldvalue;
			$i++;
		}
	}
	$log->debug("Exiting customer portal function get_list_values");
	return $output;

}


/**	function used to get the contents of a file
 *	@param int $id - customer ie., id
 *	return $filecontents array with single file contents like [fileid] => filecontent
 */
function get_filecontent_detail($id,$folderid,$module,$customerid,$sessionid)
{
	global $adb,$log;
	global $site_URL;
	$log->debug("Entering customer portal function get_filecontent_detail ");
	$isPermitted = check_permission($customerid,$module,$id);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}

	if(!validateSession($customerid,$sessionid))
	return null;

	if($module == 'Documents')
	{
		$query="SELECT filetype FROM aicrm_notes WHERE notesid =?";
		$res = $adb->pquery($query, array($id));
		$filetype = $adb->query_result($res, 0, "filetype");
		updateDownloadCount($id);

		$fileidQuery = 'select attachmentsid from aicrm_seattachmentsrel where crmid = ?';
		$fileres = $adb->pquery($fileidQuery,array($id));
		$fileid = $adb->query_result($fileres,0,'attachmentsid');

		$filepathQuery = 'select path,name from aicrm_attachments where attachmentsid = ?';
		$fileres = $adb->pquery($filepathQuery,array($fileid));
		$filepath = $adb->query_result($fileres,0,'path');
		$filename = $adb->query_result($fileres,0,'name');
		$filename= decode_html($filename);

		$saved_filename =  $fileid."_".$filename;
		$filenamewithpath = $filepath.$saved_filename;
		$filesize = filesize($filenamewithpath );
	}
	else
	{
		$query ='select aicrm_attachments.*,aicrm_seattachmentsrel.* from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.attachmentsid=aicrm_attachments.attachmentsid where aicrm_seattachmentsrel.crmid =?';

		$res = $adb->pquery($query, array($id));

		$filename = $adb->query_result($res,0,'name');
		$filename = decode_html($filename);
		$filepath = $adb->query_result($res,0,'path');
		$fileid = $adb->query_result($res,0,'attachmentsid');
		$filesize = filesize($filepath.$fileid."_".$filename);
		$filetype = $adb->query_result($res,0,'type');
		$filenamewithpath=$filepath.$fileid.'_'.$filename;

	}
	$output[0]['fileid'] = $fileid;
	$output[0]['filename'] = $filename;
	$output[0]['filetype'] = $filetype;
	$output[0]['filesize'] = $filesize;
	$output[0]['filecontents']=base64_encode(file_get_contents($filenamewithpath));
	$log->debug("Exiting customer portal function get_filecontent_detail ");
	return $output;
}

/** Function that the client actually calls when a file is downloaded
 *
 */
function updateCount($id){
	global $adb,$log;
	$log->debug("Entering customer portal function updateCount");
	$result = updateDownloadCount($id);
	$log->debug("Entering customer portal function updateCount");
	return $result;

}

/**
 * Function to update the download count of a file
 */
function updateDownloadCount($id){
	global $adb,$log;
	$log->debug("Entering customer portal function updateDownloadCount");
	$updateDownloadCount = "UPDATE aicrm_notes SET filedownloadcount = filedownloadcount+1 WHERE notesid = ?";
	$countres = $adb->pquery($updateDownloadCount,array($id));
	$log->debug("Entering customer portal function updateDownloadCount");
	return true;
}

/**	function used to get the Quotes/Invoice pdf
 *	@param int $id - id -id
 *	return string $output - pd link value
 */

function get_pdf($id,$block,$customerid,$sessionid)
{
	global $adb;
	global $current_user,$log,$default_language;
	global $currentModule,$mod_strings,$app_strings,$app_list_strings;
	$log->debug("Entering customer portal function get_pdf");
	$isPermitted = check_permission($customerid,$block,$id);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}

	if(!validateSession($customerid,$sessionid))
	return null;

	require_once("modules/Users/Users.php");
	require_once("config.inc.php");
	$seed_user=new Users();
	$user_id=$seed_user->retrieve_user_id('admin');
	
	$current_user=$seed_user;
	$current_user->retrieveCurrentUserInfoFromFile($user_id);
	
	$currentModule = $block;
	$current_language = $default_language;
	$app_strings = return_application_language($current_language);
	$app_list_strings = return_app_list_strings_language($current_language);
	$mod_strings = return_module_language($current_language, $currentModule);

	$_REQUEST['record']= $id;
	$_REQUEST['savemode']= 'file';
	$filenamewithpath='test/product/'.$id.'_'.$block.'.pdf';
	if (file_exists($filenamewithpath) && (filesize($filenamewithpath) != 0))
	unlink($filenamewithpath);

	checkFileAccess("modules/$block/CreatePDF.php");
	include("modules/$block/CreatePDF.php");

	if (file_exists($filenamewithpath) && (filesize($filenamewithpath) != 0))
	{
		//we have to pass the file content
		$filecontents[] = base64_encode(file_get_contents($filenamewithpath));
		unlink($filenamewithpath);
		// TODO: Delete the file to avoid public access.
	}
	else
	{
		$filecontents = "failure";
	}
	$log->debug("Exiting customer portal function get_pdf");
	return $filecontents;
}

/**	function used to get the salesorder name
 *	@param int $id -  id
 *	return string $name - Salesorder name returned
 */

function get_salesorder_name($id)
{
	global $adb,$log;
	$log->debug("Entering customer portal function get_salesorder_name");
	$res = $adb->pquery(" select subject from aicrm_salesorder where salesorderid=?", array($id));
	$name=$adb->query_result($res,0,'subject');
	$log->debug("Exiting customer portal function get_salesorder_name");
	return $name;
}

function get_invoice_detail($id,$module,$customerid,$sessionid)
{
	require_once('include/utils/UserInfoUtil.php');
	require_once('modules/Users/Users.php');
	require_once('include/utils/utils.php');

	global $adb,$site_URL,$log,$current_user;
	$log->debug("Entering customer portal function get_invoice_details $id - $module - $customerid - $sessionid");
	$user = new Users();
	$userid = getPortalUserid();
	$current_user = $user->retrieveCurrentUserInfoFromFile($userid);

	$isPermitted = check_permission($customerid,$module,$id);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}

	if(!validateSession($customerid,$sessionid))
	return null;

	$fieldquery = "SELECT fieldname, columnname, fieldlabel,block,uitype FROM aicrm_field WHERE tabid = ? AND displaytype in (1,2,4) ORDER BY block,sequence";
	$fieldres = $adb->pquery($fieldquery,array(getTabid($module)));
	$nooffields = $adb->num_rows($fieldres);
	$query = "select aicrm_invoice.*,aicrm_crmentity.* ,aicrm_invoicebillads.*,aicrm_invoiceshipads.*,
		aicrm_invoicecf.* from aicrm_invoice 
		inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_invoice.invoiceid 
		LEFT JOIN aicrm_invoicebillads ON aicrm_invoice.invoiceid = aicrm_invoicebillads.invoicebilladdressid
		LEFT JOIN aicrm_invoiceshipads ON aicrm_invoice.invoiceid = aicrm_invoiceshipads.invoiceshipaddressid
		INNER JOIN aicrm_invoicecf ON aicrm_invoice.invoiceid = aicrm_invoicecf.invoiceid
		where aicrm_invoice.invoiceid=?";
	$res = $adb->pquery($query, array($id));

	for($i=0;$i<$nooffields;$i++)
	{
		$fieldname = $adb->query_result($fieldres,$i,'columnname');
		$fieldlabel = getTranslatedString($adb->query_result($fieldres,$i,'fieldlabel'));

		$blockid = $adb->query_result($fieldres,$i,'block');
		$blocknameQuery = "select blocklabel from aicrm_blocks where blockid = ?";
		$blockPquery = $adb->pquery($blocknameQuery,array($blockid));
		$blocklabel = $adb->query_result($blockPquery,0,'blocklabel');

		$fieldper = getFieldVisibilityPermission($module,$current_user->id,$fieldname);
		if($fieldper == '1'){
			continue;
		}

		$fieldvalue = $adb->query_result($res,0,$fieldname);
		if($fieldname == 'subject' && $fieldvalue !='')
		{
			$fieldid = $adb->query_result($res,0,'invoiceid');
			//$fieldlabel = "(Download PDF)  ".$fieldlabel;
			$fieldvalue = '<a href="index.php?downloadfile=true&module=Invoice&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
		}
		if( $fieldname == 'salesorderid' || $fieldname == 'contactid' || $fieldname == 'accountid' || $fieldname == 'potentialid')
		{
			$crmid = $fieldvalue;
			$Entitymodule = getSalesEntityType($crmid);
			if ($crmid != '' && $Entitymodule != '') {
				$fieldvalues = getEntityName($Entitymodule, array($crmid));
				if($Entitymodule == 'Contacts')
				$fieldvalue = '<a href="index.php?module=Contacts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
				elseif($Entitymodule == 'Accounts')
				$fieldvalue = '<a href="index.php?module=Accounts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
				else
				$fieldvalue = $fieldvalues[$crmid];
			} else {
				$fieldvalue = '';
			}
		}
		if($fieldname == 'total'){
			$sym = getCurrencySymbol($res,0,'currency_id');
			$fieldvalue = $sym.$fieldvalue;
		}
		if($fieldname == 'smownerid'){
			$fieldvalue = getOwnerName($fieldvalue);
		}
		$output[0][$module][$i]['fieldlabel'] = $fieldlabel;
		$output[0][$module][$i]['fieldvalue'] = $fieldvalue;
		$output[0][$module][$i]['blockname'] = getTranslatedString($blocklabel,$module);
	}
	$log->debug("Entering customer portal function get_invoice_detail ..");
	return $output;
}

/* Function to get contactid's and account's product details'
 *
 */
function get_product_list_values($id,$modulename,$sessionid,$only_mine='true')
{
	require_once('modules/Products/Products.php');
	require_once('include/utils/UserInfoUtil.php');
	require_once('modules/Users/Users.php');
	global $current_user,$adb,$log;
	$log->debug("Entering customer portal function get_product_list_values ..");
	$check = checkModuleActive($modulename);
	if($check == false){
		return array("#MODULE INACTIVE#");
	}
	$user = new Users();
	$userid = getPortalUserid();
	$current_user = $user->retrieveCurrentUserInfoFromFile($userid);
	$entity_ids_list = array();
	$show_all=show_all($modulename);

	if(!validateSession($id,$sessionid))
	return null;

	if($only_mine == 'true' || $show_all == 'false')
	{
		array_push($entity_ids_list,$id);
	}
	else
	{
		$contactquery = "SELECT contactid, accountid FROM aicrm_contactdetails " .
		" INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid" .
		" AND aicrm_crmentity.deleted = 0 " .
		" WHERE (accountid = (SELECT accountid FROM aicrm_contactdetails WHERE contactid = ?)  AND accountid != 0) OR contactid = ?";
		$contactres = $adb->pquery($contactquery, array($id,$id));
		$no_of_cont = $adb->num_rows($contactres);
		for($i=0;$i<$no_of_cont;$i++)
		{
			$cont_id = $adb->query_result($contactres,$i,'contactid');
			$acc_id = $adb->query_result($contactres,$i,'accountid');
			if(!in_array($cont_id, $entity_ids_list))
			$entity_ids_list[] = $cont_id;
			if(!in_array($acc_id, $entity_ids_list) && $acc_id != '0')
			$entity_ids_list[] = $acc_id;
		}
	}

	$focus = new Products();
	$focus->filterInactiveFields('Products');
	foreach ($focus->list_fields as $fieldlabel => $values){
		foreach($values as $table => $fieldname){
			$fields_list[$fieldlabel] = $fieldname;
		}
	}
	$fields_list['Related To'] = 'entityid';
	$query = array();
	$params = array();

	$query[] = "SELECT aicrm_products.*,aicrm_seproductsrel.crmid as entityid, aicrm_seproductsrel.setype FROM aicrm_products
		INNER JOIN aicrm_crmentity on aicrm_products.productid = aicrm_crmentity.crmid 
		LEFT JOIN aicrm_seproductsrel on aicrm_seproductsrel.productid = aicrm_products.productid  					
		WHERE aicrm_seproductsrel.crmid in (". generateQuestionMarks($entity_ids_list).") and aicrm_crmentity.deleted = 0 ";
	$params[] = array($entity_ids_list);
		
	$checkQuotes = checkModuleActive('Quotes');
	if($checkQuotes == true){
		$query[] = "select distinct aicrm_products.*,
			case when aicrm_quotes.contactid is not null then aicrm_quotes.contactid else aicrm_quotes.accountid end as entityid,
			case when aicrm_quotes.contactid is not null then 'Contacts' else 'Accounts' end as setype
			from aicrm_quotes INNER join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_quotes.quoteid 
			left join aicrm_inventoryproductrel on aicrm_inventoryproductrel.id=aicrm_quotes.quoteid 
			left join aicrm_products on aicrm_products.productid = aicrm_inventoryproductrel.productid 
			where aicrm_inventoryproductrel.productid = aicrm_products.productid AND aicrm_crmentity.deleted=0 and (accountid in  (". generateQuestionMarks($entity_ids_list) .") or contactid in (". generateQuestionMarks($entity_ids_list) ."))";		
		$params[] = array($entity_ids_list,$entity_ids_list);
	}
	$checkInvoices = checkModuleActive('Invoice');
	if($checkInvoices == true){
		$query[] = "select distinct aicrm_products.*,
			case when aicrm_invoice.contactid !=0 then aicrm_invoice.contactid else aicrm_invoice.accountid end as entityid,
			case when aicrm_invoice.contactid !=0 then 'Contacts' else 'Accounts' end as setype
			from aicrm_invoice 
			INNER join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_invoice.invoiceid 
			left join aicrm_inventoryproductrel on aicrm_inventoryproductrel.id=aicrm_invoice.invoiceid
			left join aicrm_products on aicrm_products.productid = aicrm_inventoryproductrel.productid 
			where aicrm_inventoryproductrel.productid = aicrm_products.productid AND aicrm_crmentity.deleted=0 and (accountid in (". generateQuestionMarks($entity_ids_list) .") or contactid in  (". generateQuestionMarks($entity_ids_list) ."))";
		$params[] = array($entity_ids_list,$entity_ids_list);
	}
	for($k=0;$k<count($query);$k++)
	{
		$res[$k] = $adb->pquery($query[$k],$params[$k]);
		$noofdata[$k] = $adb->num_rows($res[$k]);
		if($noofdata[$k] == 0)
		$output[$k][$modulename]['data'] = '';
		for( $j= 0;$j < $noofdata[$k]; $j++)
		{
			$i=0;
			foreach($fields_list as $fieldlabel=> $fieldname) {
				$fieldper = getFieldVisibilityPermission('Products',$current_user->id,$fieldname);
				if($fieldper == '1' && $fieldname != 'entityid'){
					continue;
				}
				$output[$k][$modulename]['head'][0][$i]['fielddata'] = $fieldlabel;
				$fieldvalue = $adb->query_result($res[$k],$j,$fieldname);
				$fieldid = $adb->query_result($res[$k],$j,'productid');
					
				if($fieldname == 'entityid') {
					$crmid = $fieldvalue;
					$module = $adb->query_result($res[$k],$j,'setype');
					if ($crmid != '' && $module != '') {
						$fieldvalues = getEntityName($module, array($crmid));
						if($module == 'Contacts')
						$fieldvalue = '<a href="index.php?module=Contacts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
						elseif($module == 'Accounts')
						$fieldvalue = '<a href="index.php?module=Accounts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
					} else {
						$fieldvalue = '';
					}
				}

				if($fieldname == 'productname')
				$fieldvalue = '<a href="index.php?module=Products&action=index&productid='.$fieldid.'">'.$fieldvalue.'</a>';
					
				if($fieldname == 'unit_price'){
					$sym = getCurrencySymbol($res[$k],$j,'currency_id');
					$fieldvalue = $sym.$fieldvalue;
				}
				$output[$k][$modulename]['data'][$j][$i]['fielddata'] = $fieldvalue;
				$i++;
			}
		}
	}
	$log->debug("Exiting function get_product_list_values.....");
	return $output;
}

/*function used to get details of tickets,quotes,documents,Products,Contacts,Accounts
 *	@param int $id - id of quotes or invoice or notes
 *	return string $message - Account informations will be returned from :Accountdetails table
 */
function get_details($id,$module,$customerid,$sessionid)
{
	global $adb,$log,$current_language,$default_language,$current_user;
	require_once('include/utils/utils.php');
	require_once('include/utils/UserInfoUtil.php');
	require_once('modules/Users/Users.php');
	$log->debug("Entering customer portal function get_details ..");

	$user = new Users();
	$userid = getPortalUserid();
	$current_user = $user->retrieveCurrentUserInfoFromFile($userid);

	$current_language = $default_language;
	$isPermitted = check_permission($customerid,$module,$id);
	if($isPermitted == false) {
		return array("#NOT AUTHORIZED#");
	}

	if(!validateSession($customerid,$sessionid))
	return null;

	if($module == 'Quotes'){
		$query =  "SELECT
			aicrm_quotes.*,aicrm_crmentity.*,aicrm_quotesbillads.*,aicrm_quotesshipads.*,  
			aicrm_quotescf.* FROM aicrm_quotes 
			INNER JOIN aicrm_crmentity " .
				"ON aicrm_crmentity.crmid = aicrm_quotes.quoteid 
			INNER JOIN aicrm_quotesbillads
				ON aicrm_quotes.quoteid = aicrm_quotesbillads.quotebilladdressid
			INNER JOIN aicrm_quotesshipads
				ON aicrm_quotes.quoteid = aicrm_quotesshipads.quoteshipaddressid
			LEFT JOIN aicrm_quotescf
				ON aicrm_quotes.quoteid = aicrm_quotescf.quoteid 
			WHERE aicrm_quotes.quoteid=(". generateQuestionMarks($id) .") AND aicrm_crmentity.deleted = 0";

	}
	else if($module == 'Documents'){
		$query =  "SELECT
			aicrm_notes.*,aicrm_crmentity.*,aicrm_attachmentsfolder.foldername    
			FROM aicrm_notes
			INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_notes.notesid 
			LEFT JOIN aicrm_attachmentsfolder 
				ON aicrm_notes.folderid = aicrm_attachmentsfolder.folderid
			where aicrm_notes.notesid=(". generateQuestionMarks($id) .") AND aicrm_crmentity.deleted=0";
	}
	else if($module == 'HelpDesk'){
		$query ="SELECT
			aicrm_troubletickets.*,aicrm_crmentity.smownerid,aicrm_crmentity.createdtime,aicrm_crmentity.modifiedtime, 
			aicrm_ticketcf.*,aicrm_crmentity.description  FROM aicrm_troubletickets
			INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_troubletickets.ticketid 
			INNER JOIN aicrm_ticketcf
				ON aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
			WHERE (aicrm_troubletickets.ticketid=(". generateQuestionMarks($id) .") AND aicrm_crmentity.deleted = 0)";
	}
	else if($module == 'Services'){
		$query ="SELECT aicrm_service.*,aicrm_crmentity.*,aicrm_servicecf.*  FROM aicrm_service
			INNER JOIN aicrm_crmentity 
				ON aicrm_crmentity.crmid = aicrm_service.serviceid AND aicrm_crmentity.deleted = 0
			LEFT JOIN aicrm_servicecf 
				ON aicrm_service.serviceid = aicrm_servicecf.serviceid 	
			WHERE aicrm_service.serviceid= (". generateQuestionMarks($id) .")";
	}
	else if($module == 'Contacts'){
		$query = "SELECT aicrm_contactdetails.*,aicrm_contactaddress.*,aicrm_contactsubdetails.*,aicrm_contactscf.*" .
			" ,aicrm_crmentity.*,aicrm_customerdetails.*   
		 	FROM aicrm_contactdetails 
			INNER JOIN aicrm_crmentity
				ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid 
			INNER JOIN aicrm_contactaddress
				ON aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid
			INNER JOIN aicrm_contactsubdetails
				ON aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid
			INNER JOIN aicrm_contactscf
				ON aicrm_contactscf.contactid = aicrm_contactdetails.contactid
			LEFT JOIN aicrm_customerdetails
				ON aicrm_customerdetails.customerid = aicrm_contactdetails.contactid 
			WHERE aicrm_contactdetails.contactid = (". generateQuestionMarks($id) .") AND aicrm_crmentity.deleted = 0";
	}
	else if($module == 'Accounts'){
		$query = "SELECT aicrm_account.*,aicrm_accountbillads.*,aicrm_accountshipads.*,aicrm_accountscf.*,
			aicrm_crmentity.* FROM aicrm_account
			INNER JOIN aicrm_crmentity
				ON aicrm_crmentity.crmid = aicrm_account.accountid
			INNER JOIN aicrm_accountbillads
				ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
			INNER JOIN aicrm_accountshipads
				ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
			INNER JOIN aicrm_accountscf
				ON aicrm_account.accountid = aicrm_accountscf.accountid" .
		" WHERE aicrm_account.accountid = (". generateQuestionMarks($id) .") AND aicrm_crmentity.deleted = 0";
	}
	else if ($module == 'Products'){
		$query = "SELECT aicrm_products.*,aicrm_productcf.*,aicrm_crmentity.* " .
		"FROM aicrm_products " .
		"INNER JOIN aicrm_crmentity " .
			"ON aicrm_crmentity.crmid = aicrm_products.productid " .
		"LEFT JOIN aicrm_productcf " .
			"ON aicrm_productcf.productid = aicrm_products.productid " .
		"LEFT JOIN aicrm_vendor 
			ON aicrm_vendor.vendorid = aicrm_products.vendor_id 
		LEFT JOIN aicrm_users 
			ON aicrm_users.id = aicrm_products.handler " .
		"WHERE aicrm_products.productid = (". generateQuestionMarks($id) .") AND aicrm_crmentity.deleted = 0";
	}
	$params = array($id);
	
	$fieldquery = "SELECT fieldname,columnname,fieldlabel,blocklabel,uitype FROM aicrm_field 
	INNER JOIN  aicrm_blocks on aicrm_blocks.blockid=aicrm_field.block WHERE aicrm_field.tabid = ? AND displaytype in (1,2,4) 
	ORDER BY aicrm_field.block,aicrm_field.sequence";
	
	$fieldres = $adb->pquery($fieldquery,array(getTabid($module)));
	$nooffields = $adb->num_rows($fieldres);
	$res = $adb->pquery($query,$params);
	
	// Dummy instance to make sure column fields are initialized for futher processing
	$focus = CRMEntity::getInstance($module);
	
	for($i=0;$i<$nooffields;$i++)
	{
		$columnname = $adb->query_result($fieldres,$i,'columnname');
		$fieldname = $adb->query_result($fieldres,$i,'fieldname');
		$fieldid = $adb->query_result($fieldres,$i,'fieldid');
		$blockid = $adb->query_result($fieldres,$i,'block');
		$uitype = $adb->query_result($fieldres,$i,'uitype');

		$blocklabel = $adb->query_result($fieldres,$i,'blocklabel');
		$blockname = getTranslatedString($blocklabel,$module);
		if($blocklabel == 'LBL_COMMENTS' || $blocklabel == 'LBL_IMAGE_INFORMATION'){ // the comments block of tickets is hardcoded in customer portal,get_ticket_comments is used for it
			continue;
		}
		if($uitype == 83){ //for taxclass in products and services
			continue;
		}
		$fieldper = getFieldVisibilityPermission($module,$current_user->id,$fieldname);
		if($fieldper == '1'){
			continue;
		}

		$fieldlabel = getTranslatedString($adb->query_result($fieldres,$i,'fieldlabel'));
		$fieldvalue = $adb->query_result($res,0,$columnname);

		$output[0][$module][$i]['fieldlabel'] = $fieldlabel ;
		$output[0][$module][$i]['blockname'] = $blockname;

		if($columnname == 'parent_id' || $columnname == 'contactid' || $columnname == 'accountid' || $columnname == 'potentialid' || $fieldname == 'account_id' || $fieldname == 'contact_id')
		{
			$crmid = $fieldvalue;
			$modulename = getSalesEntityType($crmid);
			if ($crmid != '' && $modulename != '') {
				$fieldvalues = getEntityName($modulename, array($crmid));
				if($modulename == 'Contacts')
				$fieldvalue = '<a href="index.php?module=Contacts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
				elseif($modulename == 'Accounts')
				$fieldvalue = '<a href="index.php?module=Accounts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
				else
				$fieldvalue = $fieldvalues[$crmid];
			} else {
				$fieldvalue = '';
			}
		}

		if($module=='Quotes')
		{
			if($fieldname == 'subject' && $fieldvalue !=''){
				$fieldid = $adb->query_result($res,0,'quoteid');
				$fieldvalue = '<a href="index.php?downloadfile=true&module=Quotes&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
			}
			if($fieldname == 'total'){
				$sym = getCurrencySymbol($res,0,'currency_id');
				$fieldvalue = $sym.$fieldvalue;
			}
		}
		if($module == 'Documents')
		{
			$fieldid = $adb->query_result($res,0,'notesid');
			$filename = $fieldvalue;
			$folderid = $adb->query_result($res,0,'folderid');
			$filestatus = $adb->query_result($res,0,'filestatus');
			$filetype = $adb->query_result($res,0,'filelocationtype');
			if($fieldname == 'filename'){
				if($filestatus == 1){
					if($filetype == 'I'){
						$fieldvalue = '<a href="index.php?downloadfile=true&folderid='.$folderid.'&filename='.$filename.'&module=Documents&action=index&id='.$fieldid.'" >'.$fieldvalue.'</a>';
					}
					elseif($filetype == 'E'){
						$fieldvalue = '<a target="_blank" href="'.$filename.'" onclick = "updateCount('.$fieldid.');">'.$filename.'</a>';
					}
				}
			}
			if($fieldname == 'folderid'){
				$fieldvalue = $adb->query_result($res,0,'foldername');
			}
			if($fieldname == 'filesize'){
				if($filetype == 'I'){
					$fieldvalue = $fieldvalue .' B';
				}
				elseif($filetype == 'E'){
					$fieldvalue = '--';
				}
			}
			if($fieldname == 'filelocationtype'){
				if($fieldvalue == 'I'){
					$fieldvalue = getTranslatedString('LBL_INTERNAL',$module);
				}elseif($fieldvalue == 'E'){
					$fieldvalue = getTranslatedString('LBL_EXTERNAL',$module);
				}else{
					$fieldvalue = '---';
				}
			}
		}
		if($columnname == 'product_id') {
			$fieldvalues = getEntityName('Products', array($fieldvalue));
			$fieldvalue = '<a href="index.php?module=Products&action=index&productid='.$fieldvalue.'">'.$fieldvalues[$fieldvalue].'</a>';
		}
		if($module == 'Products'){
			if($fieldname == 'vendor_id'){
				$fieldvalue = get_vendor_name($fieldvalue);
			}
		}
		if($fieldname == 'assigned_user_id' || $fieldname == 'assigned_user_id1'){
			$fieldvalue = getOwnerName($fieldvalue);
		}
		if($uitype == 56){
			if($fieldvalue == 1){
				$fieldvalue = 'Yes';
			}else{
				$fieldvalue = 'No';
			}
		}
		if($module == 'HelpDesk' && $fieldname == 'ticketstatus'){
			$parentid = $adb->query_result($res,0,'parent_id');
			$status = $adb->query_result($res,0,'status');
			if($customerid != $parentid ){ //allow only the owner to delete the ticket
				$fieldvalue = '';
			}else{
				$fieldvalue = $status;
			}
		}
		if($fieldname == 'unit_price'){
			$sym = getCurrencySymbol($res,0,'currency_id');
			$fieldvalue = $sym.$fieldvalue;
		}
		$output[0][$module][$i]['fieldvalue'] = $fieldvalue;
	}

	if($module == 'HelpDesk'){
		$ticketid = $adb->query_result($res,0,'ticketid');
		$sc_info = getRelatedServiceContracts($ticketid);
		if (!empty($sc_info)) {
			$modulename = 'ServiceContracts';
			$blocklable = getTranslatedString('LBL_SERVICE_CONTRACT_INFORMATION',$modulename);
			$j=$i;
			for($k=0;$k<count($sc_info);$k++){
				foreach ($sc_info[$k] as $label => $value) {
					$output[0][$module][$j]['fieldlabel']= getTranslatedString($label,$modulename);
					$output[0][$module][$j]['fieldvalue']= $value;
					$output[0][$module][$j]['blockname'] = $blocklable;
					$j++;
				}
			}
		}
	}
	$log->debug("Existing customer portal function get_details ..");
	return $output;
}
/* Function to check the permission if the customer can see the recorde details
 * @params $customerid :: INT contact's Id
 * 			$module :: String modulename
 * 			$entityid :: INT Records Id
 */
function check_permission($customerid, $module, $entityid) {
	global $adb,$log;
	$log->debug("Entering customer portal function check_permission ..");
	$show_all= show_all($module);
	$allowed_contacts_and_accounts = array();
	$check = checkModuleActive($module);
	if($check == false){
		return false;
	}

	if($show_all == 'false')
	$allowed_contacts_and_accounts[] = $customerid;
	else {
			
		$contactquery = "SELECT contactid, accountid FROM aicrm_contactdetails " .
					" INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid" .
					" AND aicrm_crmentity.deleted = 0 " .
					" WHERE (accountid = (SELECT accountid FROM aicrm_contactdetails WHERE contactid = ?) AND accountid != 0) OR contactid = ?";
		$contactres = $adb->pquery($contactquery, array($customerid,$customerid));
		$no_of_cont = $adb->num_rows($contactres);
		for($i=0;$i<$no_of_cont;$i++){
			$cont_id = $adb->query_result($contactres,$i,'contactid');
			$acc_id = $adb->query_result($contactres,$i,'accountid');
			if(!in_array($cont_id, $allowed_contacts_and_accounts))
			$allowed_contacts_and_accounts[] = $cont_id;
			if(!in_array($acc_id, $allowed_contacts_and_accounts) && $acc_id != '0')
			$allowed_contacts_and_accounts[] = $acc_id;
		}
	}
	if(in_array($entityid, $allowed_contacts_and_accounts)) { //for contact's,if they are present in the allowed list then send true
		return true;
	}
	$faqquery = "select id from aicrm_faq";
	$faqids = $adb->pquery($faqquery,array());
	$no_of_faq = $adb->num_rows($faqids);
	for($i=0;$i<$no_of_faq;$i++){
		$faq_id[] = $adb->query_result($faqids,$i,'id');
	}
	switch($module) {
		case 'Products'	: 	$query = "SELECT aicrm_seproductsrel.productid FROM aicrm_seproductsrel
								INNER JOIN aicrm_crmentity 
								ON aicrm_seproductsrel.productid=aicrm_crmentity.crmid 					
								WHERE aicrm_seproductsrel.crmid IN (". generateQuestionMarks($allowed_contacts_and_accounts).")
									AND aicrm_crmentity.deleted=0
									AND aicrm_seproductsrel.productid = ?";
		$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $entityid));
		if ($adb->num_rows($res) > 0) {
			return true;
		}
		$query = "SELECT aicrm_inventoryproductrel.productid, aicrm_inventoryproductrel.id
								FROM aicrm_inventoryproductrel   
								INNER JOIN aicrm_crmentity 
								ON aicrm_inventoryproductrel.productid=aicrm_crmentity.crmid 					
								LEFT JOIN aicrm_quotes
								ON aicrm_inventoryproductrel.id = aicrm_quotes.quoteid 													
								WHERE aicrm_crmentity.deleted=0 
									AND (aicrm_quotes.contactid IN (". generateQuestionMarks($allowed_contacts_and_accounts).") or aicrm_quotes.accountid IN (".generateQuestionMarks($allowed_contacts_and_accounts)."))
									AND aicrm_inventoryproductrel.productid = ?";
		$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $allowed_contacts_and_accounts, $entityid));
		if ($adb->num_rows($res) > 0) {
			return true;
		}
		$query = "SELECT aicrm_inventoryproductrel.productid, aicrm_inventoryproductrel.id
								FROM aicrm_inventoryproductrel   
								INNER JOIN aicrm_crmentity 
								ON aicrm_inventoryproductrel.productid=aicrm_crmentity.crmid 					
								LEFT JOIN aicrm_invoice
								ON aicrm_inventoryproductrel.id = aicrm_invoice.invoiceid 													
								WHERE aicrm_crmentity.deleted=0 
									AND (aicrm_invoice.contactid IN (". generateQuestionMarks($allowed_contacts_and_accounts).") or aicrm_invoice.accountid IN (".generateQuestionMarks($allowed_contacts_and_accounts)."))
									AND aicrm_inventoryproductrel.productid = ?";
		$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $allowed_contacts_and_accounts, $entityid));
		if ($adb->num_rows($res) > 0) {
			return true;
		}
		break;

		case 'Quotes'	:	$query = "SELECT aicrm_quotes.quoteid
								FROM aicrm_quotes   
								INNER JOIN aicrm_crmentity 
								ON aicrm_quotes.quoteid=aicrm_crmentity.crmid  													
								WHERE aicrm_crmentity.deleted=0 
									AND (aicrm_quotes.contactid IN (". generateQuestionMarks($allowed_contacts_and_accounts).") or aicrm_quotes.accountid IN (".generateQuestionMarks($allowed_contacts_and_accounts)."))
									AND aicrm_quotes.quoteid = ?";
		$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $allowed_contacts_and_accounts, $entityid));
		if ($adb->num_rows($res) > 0) {
			return true;
		}
		break;

		case 'Invoice'	:	$query = "SELECT aicrm_invoice.invoiceid
								FROM aicrm_invoice   
								INNER JOIN aicrm_crmentity 
								ON aicrm_invoice.invoiceid=aicrm_crmentity.crmid  													
								WHERE aicrm_crmentity.deleted=0 
									AND (aicrm_invoice.contactid IN (". generateQuestionMarks($allowed_contacts_and_accounts).") or aicrm_invoice.accountid IN (".generateQuestionMarks($allowed_contacts_and_accounts)."))
									AND aicrm_invoice.invoiceid = ?";
		$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $allowed_contacts_and_accounts, $entityid));
		if ($adb->num_rows($res) > 0) {
			return true;
		}
		break;

		case 'Documents'	: 	$query = "SELECT aicrm_senotesrel.notesid FROM aicrm_senotesrel
									INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_senotesrel.notesid AND aicrm_crmentity.deleted = 0
									WHERE aicrm_senotesrel.crmid IN (". generateQuestionMarks($allowed_contacts_and_accounts) .")
									AND aicrm_senotesrel.notesid = ?";
		$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $entityid));
		if ($adb->num_rows($res) > 0) {
			return true;
		}
		$query = "SELECT aicrm_senotesrel.notesid FROM aicrm_senotesrel
									INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_senotesrel.notesid AND aicrm_crmentity.deleted = 0
									WHERE aicrm_senotesrel.crmid IN (". generateQuestionMarks($faq_id) .")
									AND aicrm_senotesrel.notesid = ?";
		$res = $adb->pquery($query, array($faq_id,$entityid));
		if ($adb->num_rows($res) > 0) {
			return true;
		}
		break;

		case 'HelpDesk'	:	$query = "SELECT aicrm_troubletickets.ticketid FROM aicrm_troubletickets
									INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_troubletickets.ticketid AND aicrm_crmentity.deleted = 0
									WHERE aicrm_troubletickets.parent_id IN (". generateQuestionMarks($allowed_contacts_and_accounts) .")
									AND aicrm_troubletickets.ticketid = ?";
		$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $entityid));
		if ($adb->num_rows($res) > 0) {
			return true;
		}
		break;
			
		case 'Services'	:	$query = "SELECT aicrm_service.serviceid FROM aicrm_service
									INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_service.serviceid AND aicrm_crmentity.deleted = 0 
									LEFT JOIN aicrm_crmentityrel ON (aicrm_crmentityrel.relcrmid=aicrm_service.serviceid OR aicrm_crmentityrel.crmid=aicrm_service.serviceid)  
									WHERE (aicrm_crmentityrel.crmid IN (". generateQuestionMarks($allowed_contacts_and_accounts) .")  OR " .
		 							"(aicrm_crmentityrel.relcrmid IN (".generateQuestionMarks($allowed_contacts_and_accounts).") AND aicrm_crmentityrel.module = 'Services')) 
									AND aicrm_service.serviceid = ?";			
		$res = $adb->pquery($query, array($allowed_contacts_and_accounts,$allowed_contacts_and_accounts, $entityid));
		if ($adb->num_rows($res) > 0) {
			return true;
		}

		$query = "SELECT aicrm_inventoryproductrel.productid, aicrm_inventoryproductrel.id
									FROM aicrm_inventoryproductrel   
									INNER JOIN aicrm_crmentity 
									ON aicrm_inventoryproductrel.productid=aicrm_crmentity.crmid 					
									LEFT JOIN aicrm_quotes
									ON aicrm_inventoryproductrel.id = aicrm_quotes.quoteid 													
									WHERE aicrm_crmentity.deleted=0 
									AND (aicrm_quotes.contactid IN (". generateQuestionMarks($allowed_contacts_and_accounts).") or aicrm_quotes.accountid IN (".generateQuestionMarks($allowed_contacts_and_accounts)."))
									AND aicrm_inventoryproductrel.productid = ?";
		$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $allowed_contacts_and_accounts, $entityid));
		if ($adb->num_rows($res) > 0) {
			return true;
		}

		$query = "SELECT aicrm_inventoryproductrel.productid, aicrm_inventoryproductrel.id
								FROM aicrm_inventoryproductrel   
								INNER JOIN aicrm_crmentity 
								ON aicrm_inventoryproductrel.productid=aicrm_crmentity.crmid 					
								LEFT JOIN aicrm_invoice
								ON aicrm_inventoryproductrel.id = aicrm_invoice.invoiceid 													
								WHERE aicrm_crmentity.deleted=0 
									AND (aicrm_invoice.contactid IN (". generateQuestionMarks($allowed_contacts_and_accounts).") or aicrm_invoice.accountid IN (".generateQuestionMarks($allowed_contacts_and_accounts)."))
									AND aicrm_inventoryproductrel.productid = ?";
		$res = $adb->pquery($query, array($allowed_contacts_and_accounts, $allowed_contacts_and_accounts, $entityid));
		if ($adb->num_rows($res) > 0) {
			return true;
		}
		break;

		case 'Accounts' : 	$query = "SELECT aicrm_account.accountid FROM aicrm_account " .
									"INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid " .
									"INNER JOIN aicrm_contactdetails ON aicrm_contactdetails.accountid = aicrm_account.accountid " .
									"WHERE aicrm_crmentity.deleted = 0 and aicrm_contactdetails.contactid = ? and aicrm_contactdetails.accountid = ?";
		$res = $adb->pquery($query,array($customerid,$entityid));
		if ($adb->num_rows($res) > 0) {
			return true;
		}
		break;

	}
	return false;
	$log->debug("Exiting customerportal function check_permission ..");
}

/* Function to get related Documents for faq
 *  @params $id :: INT parent's Id
 * 			$module :: String modulename
 * 			$customerid :: INT contact's Id'
 */
function get_documents($id,$module,$customerid,$sessionid)
{
	global $adb,$log;
	$log->debug("Entering customer portal function get_documents ..");
	$check = checkModuleActive($module);
	if($check == false){
		return array("#MODULE INACTIVE#");
	}
	$fields_list = array(
	'title' => 'Title',
	'filename' => 'FileName',
	'createdtime' => 'Created Time');

	if(!validateSession($customerid,$sessionid))
	return null;

	$query ="select aicrm_notes.title,'Documents' ActivityType, aicrm_notes.filename,
		crm2.createdtime,aicrm_notes.notesid,aicrm_notes.folderid,
		aicrm_notes.notecontent description, aicrm_users.user_name
		from aicrm_notes
		LEFT join aicrm_senotesrel on aicrm_senotesrel.notesid= aicrm_notes.notesid
		INNER join aicrm_crmentity on aicrm_crmentity.crmid= aicrm_senotesrel.crmid
		LEFT join aicrm_crmentity crm2 on crm2.crmid=aicrm_notes.notesid and crm2.deleted=0
		LEFT JOIN aicrm_groups
		ON aicrm_groups.groupid = aicrm_crmentity.smownerid			
		LEFT join aicrm_users on crm2.smownerid= aicrm_users.id
		where aicrm_crmentity.crmid=?";
	$res = $adb->pquery($query,array($id));
	$noofdata = $adb->num_rows($res);
	for( $j= 0;$j < $noofdata; $j++)
	{
		$i=0;
		foreach($fields_list as $fieldname => $fieldlabel) {
			$output[0][$module]['head'][0][$i]['fielddata'] = $fieldlabel; //$adb->query_result($fieldres,$i,'fieldlabel');
			$fieldvalue = $adb->query_result($res,$j,$fieldname);
			if($fieldname =='title') {
				$fieldid = $adb->query_result($res,$j,'notesid');
				$filename = $fieldvalue;
				$fieldvalue = '<a href="index.php?&module=Documents&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
			}
			if($fieldname == 'filename'){
				$fieldid = $adb->query_result($res,$j,'notesid');
				$filename = $fieldvalue;
				$folderid = $adb->query_result($res,$j,'folderid');
				$filetype = $adb->query_result($res,$j,'filelocationtype');
				if($filetype == 'I'){
					$fieldvalue = '<a href="index.php?&downloadfile=true&folderid='.$folderid.'&filename='.$filename.'&module=Documents&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
				}else{
					$fieldvalue = '<a target="_blank" href="'.$filename.'">'.$filename.'</a>';
				}
			}
			$output[1][$module]['data'][$j][$i]['fielddata'] = $fieldvalue;
			$i++;
		}
	}
	$log->debug("Exiting customerportal function  get_faq_document ..");
	return $output;
}


/* Function to get contactid's and account's product details'
 *
 */
function get_service_list_values($id,$modulename,$sessionid,$only_mine='true')
{
	require_once('modules/Services/Services.php');
	require_once('include/utils/UserInfoUtil.php');
	require_once('modules/Users/Users.php');
	global $current_user,$adb,$log;
	$log->debug("Entering customer portal Function get_service_list_values");
	$check = checkModuleActive($modulename);
	if($check == false){
		return array("#MODULE INACTIVE#");
	}
	$user = new Users();
	$userid = getPortalUserid();
	$current_user = $user->retrieveCurrentUserInfoFromFile($userid);
	$entity_ids_list = array();
	$show_all=show_all($modulename);

	if(!validateSession($id,$sessionid))
	return null;

	if($only_mine == 'true' || $show_all == 'false')
	{
		array_push($entity_ids_list,$id);
	}
	else
	{
		$contactquery = "SELECT contactid, accountid FROM aicrm_contactdetails " .
		" INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid" .
		" AND aicrm_crmentity.deleted = 0 " .
		" WHERE (accountid = (SELECT accountid FROM aicrm_contactdetails WHERE contactid = ?)  AND accountid != 0) OR contactid = ?";
		$contactres = $adb->pquery($contactquery, array($id,$id));
		$no_of_cont = $adb->num_rows($contactres);
		for($i=0;$i<$no_of_cont;$i++)
		{
			$cont_id = $adb->query_result($contactres,$i,'contactid');
			$acc_id = $adb->query_result($contactres,$i,'accountid');
			if(!in_array($cont_id, $entity_ids_list))
			$entity_ids_list[] = $cont_id;
			if(!in_array($acc_id, $entity_ids_list) && $acc_id != '0')
			$entity_ids_list[] = $acc_id;
		}
	}

	$focus = new Services();
	$focus->filterInactiveFields('Services');
	foreach ($focus->list_fields as $fieldlabel => $values){
		foreach($values as $table => $fieldname){
			$fields_list[$fieldlabel] = $fieldname;
		}
	}
	$fields_list['Related To'] = 'entityid';
	$query = array();
	$params = array();

	$query[] = "select aicrm_service.*," .
		"case when aicrm_crmentityrel.crmid != aicrm_service.serviceid then aicrm_crmentityrel.crmid else aicrm_crmentityrel.relcrmid end as entityid, " .
		 "'' as setype from aicrm_service " .
		 "inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_service.serviceid " .
		 "left join aicrm_crmentityrel on (aicrm_crmentityrel.relcrmid=aicrm_service.serviceid or aicrm_crmentityrel.crmid=aicrm_service.serviceid) " .
		 "where aicrm_crmentity.deleted = 0 and " .
		 "( aicrm_crmentityrel.crmid in (".generateQuestionMarks($entity_ids_list).") OR " .
		 "(aicrm_crmentityrel.relcrmid in (".generateQuestionMarks($entity_ids_list).") AND aicrm_crmentityrel.module = 'Services')" .
		 ")";
		
	$params[] = array($entity_ids_list, $entity_ids_list);
		
	$checkQuotes = checkModuleActive('Quotes');
	if($checkQuotes == true){
		$query[] = "select distinct aicrm_service.*,
			case when aicrm_quotes.contactid is not null then aicrm_quotes.contactid else aicrm_quotes.accountid end as entityid,
			case when aicrm_quotes.contactid is not null then 'Contacts' else 'Accounts' end as setype
			from aicrm_quotes INNER join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_quotes.quoteid 
			left join aicrm_inventoryproductrel on aicrm_inventoryproductrel.id=aicrm_quotes.quoteid 
			left join aicrm_service on aicrm_service.serviceid = aicrm_inventoryproductrel.productid 
			where aicrm_inventoryproductrel.productid = aicrm_service.serviceid AND aicrm_crmentity.deleted=0 and (accountid in  (". generateQuestionMarks($entity_ids_list) .") or contactid in (". generateQuestionMarks($entity_ids_list) ."))";		
		$params[] = array($entity_ids_list,$entity_ids_list);
	}
	$checkInvoices = checkModuleActive('Invoice');
	if($checkInvoices == true){
		$query[] = "select distinct aicrm_service.*,
			case when aicrm_invoice.contactid !=0 then aicrm_invoice.contactid else aicrm_invoice.accountid end as entityid,
			case when aicrm_invoice.contactid !=0 then 'Contacts' else 'Accounts' end as setype
			from aicrm_invoice 
			INNER join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_invoice.invoiceid 
			left join aicrm_inventoryproductrel on aicrm_inventoryproductrel.id=aicrm_invoice.invoiceid
			left join aicrm_service on aicrm_service.serviceid = aicrm_inventoryproductrel.productid 
			where aicrm_inventoryproductrel.productid = aicrm_service.serviceid AND aicrm_crmentity.deleted=0 and (accountid in (". generateQuestionMarks($entity_ids_list) .") or contactid in  (". generateQuestionMarks($entity_ids_list) ."))";
		$params[] = array($entity_ids_list,$entity_ids_list);
	}
	
	$ServicesfieldVisibilityPermissions = array();
	foreach($fields_list as $fieldlabel=> $fieldname) {
		$ServicesfieldVisibilityPermissions[$fieldname] = 
			getFieldVisibilityPermission('Services',$current_user->id,$fieldname);
	}
	
	for($k=0;$k<count($query);$k++)
	{
		$res[$k] = $adb->pquery($query[$k],$params[$k]);
		$noofdata[$k] = $adb->num_rows($res[$k]);
		if($noofdata[$k] == 0) {
			$output[$k][$modulename]['data'] = '';
		}
		for( $j= 0;$j < $noofdata[$k]; $j++)
		{
			$i=0;
			foreach($fields_list as $fieldlabel=> $fieldname) {
				$fieldper = $ServicesfieldVisibilityPermissions[$fieldname];
				if($fieldper == '1' && $fieldname != 'entityid'){
					continue;
				}
				$output[$k][$modulename]['head'][0][$i]['fielddata'] = $fieldlabel;
				$fieldvalue = $adb->query_result($res[$k],$j,$fieldname);
				$fieldid = $adb->query_result($res[$k],$j,'serviceid');
					
				if($fieldname == 'entityid') {
					$crmid = $fieldvalue;
					$module = $adb->query_result($res[$k],$j,'setype');
					if($module == ''){
						$module = $adb->query_result($adb->pquery("SELECT setype FROM aicrm_crmentity WHERE crmid = ?", array($crmid)),0,'setype');
					}
					if ($crmid != '' && $module != '') {
						$fieldvalues = getEntityName($module, array($crmid));
						if($module == 'Contacts')
						$fieldvalue = '<a href="index.php?module=Contacts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
						elseif($module == 'Accounts')
						$fieldvalue = '<a href="index.php?module=Accounts&action=index&id='.$crmid.'">'.$fieldvalues[$crmid].'</a>';
					} else {
						$fieldvalue = '';
					}
				}

				if($fieldname == 'servicename')
				$fieldvalue = '<a href="index.php?module=Services&action=index&id='.$fieldid.'">'.$fieldvalue.'</a>';
					
				if($fieldname == 'unit_price'){
					$sym = getCurrencySymbol($res[$k],$j,'currency_id');
					$fieldvalue = $sym.$fieldvalue;
				}
				$output[$k][$modulename]['data'][$j][$i]['fielddata'] = $fieldvalue;
				$i++;
			}
		}
	}
	$log->debug("Exiting customerportal function get_product_list_values.....");
	return $output;
}


/* Function to get the list of modules allowed for customer portal
 */
function get_modules()
{
	global $adb,$log;
	$log->debug("Entering customer portal Function get_modules");

	// Check if information is available in cache?
	$modules = Vtiger_Soap_CustomerPortal::lookupAllowedModules();
	if($modules === false) {
		$modules = array();

		$query = $adb->pquery("SELECT aicrm_customerportal_tabs.* FROM aicrm_customerportal_tabs
			INNER JOIN aicrm_tab ON aicrm_tab.tabid = aicrm_customerportal_tabs.tabid 
			WHERE aicrm_tab.presence = 0 AND aicrm_customerportal_tabs.visible = 1", array());
		$norows = $adb->num_rows($query);
		if($norows) {
			while($resultrow = $adb->fetch_array($query)) {
				$modules[(int)$resultrow['sequence']] = getTabModuleName($resultrow['tabid']); 
			}
			ksort($modules); // Order via SQL might cost us, so handling it ourselves in this case
		}
		Vtiger_Soap_CustomerPortal::updateAllowedModules($modules);
	}
	$log->debug("Exiting customerportal function get_modules");
	return $modules;
}

/* Function to check if the module has the permission to show the related contact's and Account's information
 */
function show_all($module){

	global $adb,$log;
	$log->debug("Entering customer portal Function show_all");
	$tabid = getTabid($module);
	if($module=='Tickets'){
		$tabid = getTabid('HelpDesk');
	}
	$query = $adb->pquery("SELECT prefvalue from aicrm_customerportal_prefs where tabid = ?", array($tabid));
	$norows = $adb->num_rows($query);
	if($norows > 0){
		if($adb->query_result($query,0,'prefvalue') == 1){
			return 'true';
		}else {
			return 'false';
		}
	}else {
		return 'false';
	}
	$log->debug("Exiting customerportal function show_all");
}

/* Function to get ServiceContracts information in the tickets module if the ticket is related to ServiceContracts
 */
function getRelatedServiceContracts($crmid){
	global $adb,$log;
	$log->debug("Entering customer portal function getRelatedServiceContracts");
	$module = 'ServiceContracts';
	$sc_info = array();
	if(vtlib_isModuleActive($module) !== true){
		return $sc_info;
	}
	$query = "SELECT * FROM aicrm_servicecontracts " .
	"INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_servicecontracts.servicecontractsid AND aicrm_crmentity.deleted = 0 " .
	"LEFT JOIN aicrm_crmentityrel ON aicrm_crmentityrel.crmid = aicrm_servicecontracts.servicecontractsid " .
	"WHERE (aicrm_crmentityrel.relcrmid = ? and aicrm_crmentityrel.module= 'ServiceContracts')";

	$res = $adb->pquery($query,array($crmid));
	$rows = $adb->num_rows($res);
	for($i=0;$i<$rows;$i++){
		$sc_info[$i]['Subject'] = $adb->query_result($res,$i,'subject');
		$sc_info[$i]['Used Units'] = $adb->query_result($res,$i,'used_units');
		$sc_info[$i]['Total Units'] = $adb->query_result($res,$i,'total_units');
		$sc_info[$i]['Available Units'] = $adb->query_result($res,$i,'total_units')- $adb->query_result($res,$i,'used_units');
	}
	return $sc_info;
	$log->debug("Exiting customerportal function getRelatedServiceContracts");
}


function getPortalUserid() {
	global $adb,$log;
	$log->debug("Entering customer portal function getPortalUserid");

	// Look the value from cache first
	$userid = Vtiger_Soap_CustomerPortal::lookupPrefValue('userid');
	if($userid === false) {
		$res = $adb->pquery("SELECT prefvalue FROM aicrm_customerportal_prefs WHERE prefkey = 'userid' AND tabid = 0", array());
		$norows = $adb->num_rows($res);
		if($norows > 0) {
			$userid = $adb->query_result($res,0,'prefvalue');
			// Update the cache information now.
			Vtiger_Soap_CustomerPortal::updatePrefValue('userid', $userid);
		}
	}
	return $userid;
	$log->debug("Exiting customerportal function getPortalUserid");
}

function checkModuleActive($module){
	global $adb,$log;
	
	$isactive = false;
	$modules = get_modules(true);
	
	foreach($modules as $key => $value){
		if(strcmp($module,$value) == 0){
			$isactive = true;
			break;
		}
	}
	return $isactive;
}

/**
 *  Function that gives the Currency Symbol
 * @params $result $adb object - resultset
 * $column String column name
 * Return $value - Currency Symbol
 */
function getCurrencySymbol($result,$i,$column){
	global $adb;
	$currencyid = $adb->query_result($result,$i,$column);
	$curr = getCurrencySymbolandCRate($currencyid);
	$value = "(".$curr['symbol'].")";
	return $value;

}
/* Begin the HTTP listener service and exit. */
if (!isset($HTTP_RAW_POST_DATA)){
	$HTTP_RAW_POST_DATA = file_get_contents('php://input');
}
$server->service($HTTP_RAW_POST_DATA);

exit();

?>
