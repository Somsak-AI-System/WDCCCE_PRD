<?php
require_once('include/logging.php');
require_once('include/utils/CommonUtils.php');

global $adb, $currentModule, $current_user;
$focus = CRMEntity::getInstance($currentModule);
$mode = '';

$focus->retrieve_entity_info($_REQUEST['record'],"Accounts");
$focus->id = $_REQUEST['record'];

if($focus->column_fields['account_payment_term'] == ''){
	$res = [
		'status' => false,
		'title' => 'ข้อมูลไม่ถูกต้อง',
		'msg' => 'Payment Terms ไม่มีข้อมูล'
	];
	
	echo json_encode($res, JSON_UNESCAPED_UNICODE);
	exit;
}

if($focus->column_fields['accountname1'] == ''){
	$res = [
		'status' => false,
		'title' => 'ข้อมูลไม่ถูกต้อง',
		'msg' => 'ชื่อลูกค้า ใน Address Information (ใบกำกับภาษี) ไม่มีข้อมูล'
	];
	
	echo json_encode($res, JSON_UNESCAPED_UNICODE);
	exit;
}

$rs = $adb->pquery("SELECT rolename FROM aicrm_user2role 
    INNER JOIN aicrm_role ON aicrm_role.roleid = aicrm_user2role.roleid
    WHERE userid=?
", [$focus->column_fields['assigned_user_id']]);
$role = $adb->query_result_rowdata($rs, 0);

$rs = $adb->pquery('SELECT passed_inspection, parentid FROM aicrm_account WHERE accountid=?', [$focus->id]);
$accountData = $adb->query_result_rowdata($rs, 0);

$mode = $accountData['passed_inspection'] == '0' ? 'add':'edit';
$groupCode = explode(':', $role['rolename']);

$account_payment_term = $focus->column_fields['account_payment_term'];
$groupnum = explode(':', $account_payment_term);

$rs = $adb->query("SELECT account_no FROM aicrm_account WHERE accountid=".$accountData['parentid'], '');
$parent = $adb->query_result_rowdata($rs, 0);

/******************** SAP API ********************/
if($focus->column_fields['accounttype'] == "นิติบุคคล"){
	if($focus->column_fields['branch_code'] == ''){
		$res = [
			'status' => false,
			'title' => 'ข้อมูลไม่ถูกต้อง',
			'msg' => 'กรุณาใส่ข้อมูล Branch Code เป็นตัวเลข 5 ตัว'
		];
		
		echo json_encode($res, JSON_UNESCAPED_UNICODE);
		exit;
	}
	$cmpprivate = "C";
}else{
	$cmpprivate = "I";
}
$email1 = trim($focus->column_fields['email1']);
$email2 = trim($focus->column_fields['email2']);

$emails = [];
if (!empty($email1)) {
    $emails[] = $email1;
}
if (!empty($email2)) {
    $emails[] = $email2;
}

$jsonMaster = [
    [
        'cardcode'   => $focus->column_fields['account_no'],
        'cardname'   => $focus->column_fields['accountname'],
        'cardfname'  => empty($focus->column_fields['account_name_en']) ? '' : $focus->column_fields['account_name_en'],
        'groupcode'  => empty(@$groupCode[0]) ? '' : @$groupCode[0],
        'currency'   => 'THB',
        'fedtaxid'   => empty($focus->column_fields['idcardno']) ? '' : $focus->column_fields['idcardno'],
        'phone_1'    => empty($focus->column_fields['mobile']) ? '' : $focus->column_fields['mobile'],
        'phone_2'    => empty($focus->column_fields['mobile2']) ? '' : $focus->column_fields['mobile2'],
        'email'      => implode(',', $emails),
        'groupnum'   => trim(@$groupnum[0]),
        'vatgroup'   => 'AR07',
        'fathercard' => empty(@$parent['account_no']) ? '' : @$parent['account_no'],
        'remark'     => empty($focus->column_fields['remark']) ? '' : $focus->column_fields['remark'],
        'cmpprivate' => $cmpprivate
    ]
];

$rs = $adb->pquery("SELECT * FROM tbm_country_region WHERE name='".$focus->column_fields['billingprovince']."'");
$state1 = $adb->query_result_rowdata($rs, 0);

$rs = $adb->pquery("SELECT * FROM tbm_country_region WHERE name='".$focus->column_fields['province']."'");
$state2 = $adb->query_result_rowdata($rs, 0);

// 'country' => @explode(' ', trim(preg_replace('/\t+/', ' ', $focus->column_fields['billingscountry'])))[0],

$tumbon1 = 'ตำบล';
$ampuhr1 = 'อำเภอ';
if(@$focus->column_fields['billingprovince'] == 'กรุงเทพมหานคร'){
	$tumbon1 = 'แขวง';
	$ampuhr1 = 'เขต';
}

$tumbon2 = 'ตำบล';
$ampuhr2 = 'อำเภอ';
if(@$focus->column_fields['province'] == 'กรุงเทพมหานคร'){
	$tumbon2 = 'แขวง';
	$ampuhr2 = 'เขต';
}

$address1 = '';
if($focus->column_fields['billingaddressline1'] != '') $address1 .= ' '.$focus->column_fields['billingaddressline1'];
if($focus->column_fields['billingaddressline'] != '') $address1 .= '  ห้องเลขที่/ชั้นที่'.$focus->column_fields['billingaddressline'];
if($focus->column_fields['billingvillage'] != '') $address1 .= '  อาคาร/หมู่บ้าน'.$focus->column_fields['billingvillage'];
if($focus->column_fields['billingvillageno'] != '') $address1 .= '  หมู่ที่'.$focus->column_fields['billingvillageno'];
if($focus->column_fields['billinglane'] != '') $address1 .= '  ซอย'.$focus->column_fields['billinglane'];
if($focus->column_fields['billingstreet'] != '') $address1 .= ' ถนน'.$focus->column_fields['billingstreet'];
if($focus->column_fields['billingsubdistrict'] != '') $address1 .= '  '.$tumbon1.$focus->column_fields['billingsubdistrict'];
if($focus->column_fields['billingdistrict'] != '') $address1 .= '  '.$ampuhr1.$focus->column_fields['billingdistrict'];
if($focus->column_fields['billingprovince'] != '') $address1 .= $focus->column_fields['billingprovince'] != 'กรุงเทพมหานคร' ? '  จังหวัด'.$focus->column_fields['billingprovince']:' '.$focus->column_fields['billingprovince'];
if($focus->column_fields['billingpostalcode'] != '') $address1 .= '  '.$focus->column_fields['billingpostalcode'];
// if($focus->column_fields['billingscountry'] != '') $address1 .= ' Country '.$focus->column_fields['billingscountry'];
if($address1 == '') $address1 = $focus->column_fields['billing_address'];

$address2 = '';
if($focus->column_fields['addressline1'] != '') $address2 .= ' '.$focus->column_fields['addressline1'];
if($focus->column_fields['addressline'] != '') $address2 .= '  ห้องเลขที่/ชั้นที่'.$focus->column_fields['addressline'];
if($focus->column_fields['village'] != '') $address2 .= '  อาคาร/หมู่บ้าน'.$focus->column_fields['village'];
if($focus->column_fields['villageno'] != '') $address2 .= ' หมู่ที่'.$focus->column_fields['villageno'];
if($focus->column_fields['lane'] != '') $address2 .= '  ซอย'.$focus->column_fields['lane'];
if($focus->column_fields['street'] != '') $address2 .= '  ถนน'.$focus->column_fields['street'];
if($focus->column_fields['subdistrict'] != '') $address2 .= '  '.$tumbon2.$focus->column_fields['subdistrict'];
if($focus->column_fields['district'] != '') $address2 .= '  '.$ampuhr2.$focus->column_fields['district'];
if($focus->column_fields['province'] != '') $address2 .= $focus->column_fields['province'] != 'กรุงเทพมหานคร' ? '  จังหวัด'.$focus->column_fields['province']:' '.$focus->column_fields['province'];
if($focus->column_fields['postalcode'] != '') $address2 .= '  '.$focus->column_fields['postalcode'];
// if($focus->column_fields['country'] != '') $address2 .= ' Country '.$focus->column_fields['country'];
if($address2 == '') $address2 = $focus->column_fields['address'];

$jsonMaster[0]['billaddress'] = $address1;

$address1 = subStringLen($address1);
$address2 = subStringLen($address2);

$jsonAddress = [
	[
		'cardcode' => empty($focus->column_fields['account_no']) ? '':$focus->column_fields['account_no'],
		'addresstype' => 'B',
		// 'titlename' => $focus->column_fields['prefix1'],
		'name' => empty($focus->column_fields['accountname1']) ? '':$focus->column_fields['accountname1'],
		'address_1' => @$address1[0],
		'address_2' => @$address1[1],
		'homeno' => empty($focus->column_fields['billingaddressline1']) ? '':$focus->column_fields['billingaddressline1'],
		'floorno' => '',
		'roomno' => empty($focus->column_fields['billingaddressline']) ? '':$focus->column_fields['billingaddressline'],
		'building' => empty($focus->column_fields['billingvillage']) ? '':$focus->column_fields['billingvillage'],
		'village' => '',
		'block' => empty($focus->column_fields['billingvillageno']) ? '':$focus->column_fields['billingvillageno'],
		'substreet' => empty($focus->column_fields['billinglane']) ? '':$focus->column_fields['billinglane'],
		'street' => empty($focus->column_fields['billingstreet']) ? '':$focus->column_fields['billingstreet'],
		'city' => empty($focus->column_fields['billingsubdistrict']) ? '':$focus->column_fields['billingsubdistrict'],
		'county' => empty($focus->column_fields['billingdistrict']) ? '':$focus->column_fields['billingdistrict'],
		'state' => $state1['code'],
		'country' => @explode(' ', $focus->column_fields['billingscountry'])[0],
		'fedtaxid' => empty($focus->column_fields['idcardno']) ? '':$focus->column_fields['idcardno'],
		'branch' => empty($focus->column_fields['branch_code']) ? '':$focus->column_fields['branch_code'],
		'zipcode' => empty($focus->column_fields['billingpostalcode']) ? '':$focus->column_fields['billingpostalcode'],
	],
	// [
	// 	'cardcode' => empty($focus->column_fields['account_no']) ? '':$focus->column_fields['account_no'],
	// 	'addresstype' => 'S',
	// 	// 'titlename' => $focus->column_fields['prefix2'] == '' ? $focus->column_fields['prefix1']:$focus->column_fields['prefix2'],
	// 	'name' => $focus->column_fields['accountname2'] == '' ? $focus->column_fields['accountname1']:$focus->column_fields['accountname2'],
	// 	'address_1' => @$address2[0],
	// 	'address_2' => @$address2[1],
	// 	'homeno' => empty($focus->column_fields['addressline1']) ? '':$focus->column_fields['addressline1'],
	// 	'floorno' => '',
	// 	'roomno' => empty($focus->column_fields['addressline']) ? '':$focus->column_fields['addressline'],
	// 	'building' => empty($focus->column_fields['village']) ? '':$focus->column_fields['village'],
	// 	'village' => '',
	// 	'block' => empty($focus->column_fields['villageno']) ? '':$focus->column_fields['villageno'],
	// 	'substreet' => empty($focus->column_fields['lane']) ? '':$focus->column_fields['lane'],
	// 	'street' => empty($focus->column_fields['street']) ? '':$focus->column_fields['street'],
	// 	'city' => empty($focus->column_fields['subdistrict']) ? '':$focus->column_fields['subdistrict'],
	// 	'county' => empty($focus->column_fields['district']) ? '':$focus->column_fields['district'],
	// 	'state' => $state2['code'],
	// 	'country' => @explode(' ', $focus->column_fields['country'])[0],
	// 	'fedtaxid' => empty($focus->column_fields['idcardno']) ? '':$focus->column_fields['idcardno'],
	// 	'branch' => empty($focus->column_fields['branch_code']) ? '':$focus->column_fields['branch_code'],
	// 	'zipcode' => empty($focus->column_fields['postalcode']) ? '':$focus->column_fields['postalcode'],
	// ]
];

$logID = logUniqID();

// echo json_encode($jsonMaster, JSON_UNESCAPED_UNICODE);
// echo json_encode($jsonAddress, JSON_UNESCAPED_UNICODE);
// exit();

$status = false;
$title = 'Send to ERP result';
$msg = '';
if($mode == 'edit'){
	$urlMaster = $serviceAPI.'SAPDI/Update/DIBP?usercode=API&userpass=1234';
	logInfo(['module'=>'Accounts', 'title'=>'SAP_UPDATE_MASTER_DATA', 'logID'=>$logID, 'type'=>'Request', 'time'=>date('Y-m-d H:i:s'), 'url'=>$urlMaster, 'data'=>json_encode($jsonMaster, JSON_UNESCAPED_UNICODE)]);
	$rsMaster = postApi($urlMaster, $jsonMaster);
	logInfo(['module'=>'Accounts', 'title'=>'SAP_UPDATE_MASTER_DATA', 'logID'=>$logID, 'type'=>'Response', 'time'=>date('Y-m-d H:i:s'), 'url'=>$urlMaster, 'data'=>$rsMaster]);

	if(@$rsMaster[0]['statussync_sap'] == '0'){ // Send Account Success
		// Update account status inactivate
		$accountStatus = empty($focus->column_fields['accountstatus']) ? '':$focus->column_fields['accountstatus'];
		if(!empty($focus->column_fields['account_no']) && $accountStatus == 'Inactive'){
			$urlInActive = $serviceAPI.'SAPDI/Inactive/DIBP?usercode=API&userpass=1234&cardcode='.$focus->column_fields['account_no'];
			logInfo(['module'=>'Accounts', 'title'=>'SAP_INACTIVE_ACCOUNT', 'logID'=>$logID, 'type'=>'Request', 'time'=>date('Y-m-d H:i:s'), 'url'=>$urlInActive, 'data'=>$focus->column_fields['account_no']]);
			$rsInActivate = postApi($urlInActive, []);
			logInfo(['module'=>'Accounts', 'title'=>'SAP_INACTIVE_ACCOUNT', 'logID'=>$logID, 'type'=>'Response', 'time'=>date('Y-m-d H:i:s'), 'url'=>$urlInActive, 'data'=>$rsInActivate]);
		}
		// Update account status inactivate

		$urlAddress = $serviceAPI.'SAPDI/Update/DIBPAdres?usercode=API&userpass=1234';
		logInfo(['module'=>'Accounts', 'title'=>'SAP_UPDATE_ADDRESS_DATA', 'logID'=>$logID, 'type'=>'Request', 'time'=>date('Y-m-d H:i:s'), 'url'=>$urlAddress, 'data'=>json_encode($jsonAddress, JSON_UNESCAPED_UNICODE)]);
		$rsAddress = postApi($urlAddress, $jsonAddress);
		logInfo(['module'=>'Accounts', 'title'=>'SAP_UPDATE_ADDRESS_DATA', 'logID'=>$logID, 'type'=>'Response', 'time'=>date('Y-m-d H:i:s'), 'url'=>$urlAddress, 'data'=>$rsAddress]);
	
		if(@$rsAddress[0]['statussync_sap'] == '0'){ // Send Account Address Success
			$adb->pquery("UPDATE aicrm_account SET erp_send_address_b='P1', erp_send_address_s='P1' WHERE accountid=", [$focus->id]);
			
			$status = true;
			$msg = 'Update Account Success';
		} else { // Send Account Address Error
			$msg = @$rsAddress[0]['remark_sap'];
		}
	} else { // Send Account Error
		$msg = @$rsMaster[0]['remark_sap'] != null ? @$rsMaster[0]['remark_sap']:'No response from ERP';
	}
	
} else {
	$urlMaster = $serviceAPI.'SAPDI/DIBP?usercode=API&userpass=1234';
	logInfo(['module'=>'Accounts', 'title'=>'SAP_ADD_MASTER_DATA', 'logID'=>$logID, 'type'=>'Request', 'time'=>date('Y-m-d H:i:s'), 'url'=>$urlMaster, 'data'=>json_encode($jsonMaster, JSON_UNESCAPED_UNICODE)]);
	$rsMaster = postApi($urlMaster, $jsonMaster);
	logInfo(['module'=>'Accounts', 'title'=>'SAP_ADD_MASTER_DATA', 'logID'=>$logID, 'type'=>'Response', 'time'=>date('Y-m-d H:i:s'), 'url'=>$urlMaster, 'data'=>$rsMaster]);

	if(@$rsMaster[0]['statussync_sap'] == '0'){ // Send Account Success
		$adb->pquery("UPDATE aicrm_account SET passed_inspection=1, erp_send_info='P' WHERE accountid=", [$focus->id]);

		$urlAddress = $serviceAPI.'SAPDI/DIBPAdres?usercode=API&userpass=1234';
		logInfo(['module'=>'Accounts', 'title'=>'SAP_ADD_ADDRESS_DATA', 'logID'=>$logID, 'type'=>'Request', 'time'=>date('Y-m-d H:i:s'), 'url'=>$urlAddress, 'data'=>json_encode($jsonAddress, JSON_UNESCAPED_UNICODE)]);
		$rsAddress = postApi($urlAddress, $jsonAddress);
		logInfo(['module'=>'Accounts', 'title'=>'SAP_ADD_ADDRESS_DATA', 'logID'=>$logID, 'type'=>'Response', 'time'=>date('Y-m-d H:i:s'), 'url'=>$urlAddress, 'data'=>$rsAddress]);

		if(@$rsAddress[0]['statussync_sap'] == '0'){ // Send Account Address Success
			$adb->pquery("UPDATE aicrm_account SET erp_send_address_b='P', erp_send_address_s='P' WHERE accountid=", [$focus->id]);

			$status = true;
			$msg = 'Add Account Success';
		} else { // Send Account Address Error
			$msg = @$rsAddress[0]['remark_sap'];
		}
	} else { // Send Account Error
		$msg = @$rsMaster[0]['remark_sap'] != null ? @$rsMaster[0]['remark_sap']:'No response from ERP';
	}
    
}

$res = [
	'status' => $status,
	'title' => $title,
	'msg' => $msg
];

/******************** SAP API ********************/
echo json_encode($res, JSON_UNESCAPED_UNICODE);
// print_r($jsonMaster);

function subStringLen($string, $len=100){
	$str_arr = explode(' ', $string);
	$arr = [];
	$count = 0;
	$str = [];
	foreach($str_arr as $i => $txt){
		$count += mb_strlen($txt);
		if($i != (count($str_arr)-1)){
			$count += 1; // spacebar
		}
		if($count > $len) {
			$arr[] = implode(' ', $str);
			$str = [];
			$count = 0;
			// continue;
		} 
	
		$str[] = $txt;
	
		if($i == (count($str_arr)-1)){
			$arr[] = implode(' ', $str);
			$str = [];
			$count = 0;
		}
	}

	return $arr;
}