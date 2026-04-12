<?php
require_once('include/database/PearDatabase.php');
require_once('modules/Settings/SettingConvertSalesRep/xlsxwriter.class.php');
include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/generate_MYSQL.php");
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
global $adb, $current_user, $site_URL, $site_URL_service, $generate;
// $generate = new generate($dbconfig ,"DB");
// $myLibrary_mysqli = new myLibrary_mysqli();
// $myLibrary_mysqli->_dbconfig = $dbconfig;

// require_once ("library/myLibrary_mysqli.php");
// $myLibrary_mysqli = new myLibrary_mysqli();
// $myLibrary_mysqli->_dbconfig = $dbconfig;

// global $adb, $current_user, $site_URL, $site_URL_service;
$type = $_REQUEST['type'];

$resultData = [];
$smownerID = @$_REQUEST['data']['oldSaleRep'];
switch($type){
    case 'search-account':
        $accountNo = @$_REQUEST['data']['accountNo'];
        $accountNameTH = @$_REQUEST['data']['accountNameTH'];
        $accountNameEN = @$_REQUEST['data']['accountNameEN'];
        $accountStatus = @$_REQUEST['data']['accountStatus'];
        $where[] = "aicrm_crmentity.deleted = 0 AND aicrm_crmentity.smownerid='".$smownerID."'";
        $like = [];
        if($accountNo != '') $where[] = "aicrm_account.account_no LIKE '%".$accountNo."%'";
        if($accountNameTH != '') $where[] = "aicrm_account.account_name_th LIKE '%".$accountNameTH."%'";
        if($accountNameEN != '') $where[] = "aicrm_account.account_name_en LIKE '%".$accountNameEN."%'";
        if($accountStatus != '') $where[] = "aicrm_account.accountstatus = '".$accountStatus."'";

        $where = implode(' AND ', $where);
        $like = implode(' OR ', $like);
        if($like != ''){
            $like = " AND (".$like.")";
        }
        $sql = "SELECT aicrm_account.accountid as id, 
            CONCAT(aicrm_account.account_no, ' ', aicrm_account.accountname) AS label
        FROM aicrm_account
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid 
        WHERE ".$where.$like;
        // echo $sql; exit;
        $rs = $adb->pquery($sql, '');
        $count = $adb->num_rows($rs);
        $resultData = [];
        for($i=0; $i<$count; $i++){
            $resultData[] = $adb->query_result_rowdata($rs, $i);
        }
        break;
    case 'search-quotes':
        $quote_no = @$_REQUEST['data']['quote_no'];
        $quote_name = @$_REQUEST['data']['quote_name'];
        $quotation_status = @$_REQUEST['data']['quotation_status'];
        $quotation_date_from = @$_REQUEST['data']['quotation_date_from'];
        $quotation_date_to = @$_REQUEST['data']['quotation_date_to'];
        $convertQuotationWithSO = @$_REQUEST['data']['convertQuotationWithSO'];
        $where[] = "aicrm_crmentity.deleted = 0 AND aicrm_crmentity.smownerid='".$smownerID."'";
        $like = [];
        if($quote_no != '') $where[] = "aicrm_quotes.quote_no LIKE '%".$quote_no."%'";
        if($quote_name != '') $where[] = "aicrm_quotes.quote_name LIKE '%".$quote_name."%'";
        if($quotation_status != '') $where[] = "aicrm_quotes.quotation_status = '".$quotation_status."'";
        if($quotation_date_from != '' && $quotation_date_from != '') $where[] = "aicrm_quotes.quotation_date >=  '".$quotation_date_from."' AND aicrm_quotes.quotation_date <= '".$quotation_date_to."'";
        // ถ้าไม่เลือก "Convert quotation with SO" ให้ย้ายเฉพาะใบเสนอราคาที่ยังไม่ส่ง ERP (ไม่มีเลข SO)
        // ค่าจาก POST เป็น string "true"/"false" หรือ boolean
        $withSO = ($convertQuotationWithSO === true || $convertQuotationWithSO === 'true' || $convertQuotationWithSO === '1');
        if (!$withSO) {
            $where[] = "(aicrm_quotes.sono IS NULL OR aicrm_quotes.sono = '')";
        }

        $where = implode(' AND ', $where);
        $like = implode(' OR ', $like);
        if($like != ''){
            $like = " AND (".$like.")";
        }
        $sql = "SELECT aicrm_quotes.quoteid as id, 
            CONCAT(aicrm_quotes.quote_no, ' ', aicrm_quotes.quote_name) AS label
        FROM aicrm_quotes
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid 
        WHERE ".$where.$like;
        // echo $sql; exit;
        $rs = $adb->pquery($sql, '');
        $count = $adb->num_rows($rs);
        $resultData = [];
        for($i=0; $i<$count; $i++){
            $resultData[] = $adb->query_result_rowdata($rs, $i);
        }
        break;
    case 'search-project':
        $projectNo = @$_REQUEST['data']['projectNo'];
        $projectName = @$_REQUEST['data']['projectName'];
        $projectStatus = @$_REQUEST['data']['projectStatus'];
        $projectOpportunity = @$_REQUEST['data']['projectOpportunity'];
        $projectSize = @$_REQUEST['data']['projectSize'];
        $where[] = "aicrm_crmentity.deleted = 0 AND aicrm_crmentity.smownerid='".$smownerID."'";
        $like = [];
        if($projectNo != '') $where[] = "aicrm_projects.projects_no LIKE '%".$projectNo."%'";
        if($projectName != '') $where[] = "aicrm_projects.projects_name LIKE '%".$projectName."%'";
        if($projectStatus != '') $where[] = "aicrm_projects.projectorder_status = '".$projectStatus."'";
        if($projectOpportunity != '') $where[] = "aicrm_projects.project_opportunity = '".$projectOpportunity."'";
        if($projectSize != '') $where[] = "aicrm_projects.project_size = '".$projectSize."'";

        $where = implode(' AND ', $where);
        $like = implode(' OR ', $like);
        if($like != ''){
            $like = " AND (".$like.")";
        }
        $sql = "SELECT aicrm_projects.projectsid as id, 
            CONCAT(aicrm_projects.projects_no, ' ', aicrm_projects.projects_name) AS label
        FROM aicrm_projects
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_projects.projectsid 
        WHERE ".$where.$like;
        $rs = $adb->pquery($sql, '');
        $count = $adb->num_rows($rs);
        $resultData = [];
        for($i=0; $i<$count; $i++){
            $resultData[] = $adb->query_result_rowdata($rs, $i);
        }
        break;
    case 'save':
        $newSmownerID = @$_REQUEST['data']['newSaleRep'];
        $modules = @$_REQUEST['data']['modules'];
        $accountData = isset($_REQUEST['data']['selectedAccount']) ? $_REQUEST['data']['selectedAccount']:[];
        $projectData = isset($_REQUEST['data']['selectedProject']) ? $_REQUEST['data']['selectedProject']:[];
        $quoteData = isset($_REQUEST['data']['selectedQuotes']) ? $_REQUEST['data']['selectedQuotes']:[];
        $resApi = [];
        foreach($modules as $module){
            $adb->pquery("INSERT INTO aicrm_sale_rep_convert (old_sale_rep, new_sale_rep, module, convert_date, remark, creator_id) VALUES (?,?,?,?,?,?)", [$smownerID, $newSmownerID, $module, date('Y-m-d H:i:s'), '', $current_user->id]);
            $convertID = $adb->getLastInsertID();
            if($module == '6'){
                foreach($accountData as $row){
                    $adb->pquery("INSERT INTO aicrm_sale_rep_convert_detail VALUES (?,?)", [$convertID, $row['id']]);
                    $adb->pquery("UPDATE aicrm_crmentity SET smownerid=? WHERE crmid=?", [$newSmownerID, $row['id']]);

                    // Update contact smwonerid
                    $adb->pquery("UPDATE aicrm_crmentity,
                    (
                    SELECT aicrm_crmentity.crmid, aicrm_crmentity.smownerid
                    FROM aicrm_crmentity
                    INNER JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_crmentity.crmid
                    WHERE aicrm_crmentity.deleted = 0
                    AND aicrm_contactdetails.accountid = '".$row['id']."'
                    ) AS crmentity
                    SET aicrm_crmentity.smownerid = '".$newSmownerID."'
                    WHERE aicrm_crmentity.crmid = crmentity.crmid", '');

                    $res = post_Api($site_URL_service.'special/account/updateAccountProject', ['accountID'=>$row['id'], 'oldSale'=>$smownerID, 'newSale'=>$newSmownerID]);
                    $resApi[] = $res;
                }
            }

            if($module == '20'){
                foreach($quoteData as $row){
                    $adb->pquery("INSERT INTO aicrm_sale_rep_convert_detail VALUES (?,?)", [$convertID, $row['id']]);
                    $adb->pquery("UPDATE aicrm_crmentity SET smownerid=? WHERE crmid=?", [$newSmownerID, $row['id']]);
                }
            }

            if($module == '50'){
                foreach($projectData as $row){
                    $adb->pquery("INSERT INTO aicrm_sale_rep_convert_detail VALUES (?,?)", [$convertID, $row['id']]);
                    $adb->pquery("UPDATE aicrm_crmentity SET smownerid=? WHERE crmid=?", [$newSmownerID, $row['id']]);
                    updateProjectRelatePerson($row['id'], $smownerID, $newSmownerID);
                }
            }
        }

        $resultData = ['status' => 'Success', 'msg' => '', 'resApi' => $resApi];
        break;
    case 'search-log':
        $req = $_REQUEST['data'];
        $query = "SELECT
            tb_detail.crmid,
            DATE_FORMAT(tb_main.convert_date, '%d-%m-%Y %H:%i:%s') AS convert_date,
            CASE WHEN tb_main.module = 6 THEN 'Account'
                WHEN tb_main.module = 50 THEN 'Project Order'
                ELSE '' END AS module,
            CASE WHEN account.account_no != '' THEN account.account_no ELSE project.projects_no END AS no,
            CASE WHEN account.accountname != '' THEN account.accountname ELSE project.projects_name END AS name,
            CONCAT(oldSale.user_name) AS old_sales_rep,
            CONCAT(newSale.user_name) AS new_sales_rep,
            CONCAT(modifiedBy.user_name) AS modified_by
        FROM aicrm_sale_rep_convert AS tb_main
        INNER JOIN aicrm_sale_rep_convert_detail AS tb_detail ON tb_detail.convert_id = tb_main.id
        LEFT JOIN aicrm_account AS account ON account.accountid = tb_detail.crmid
        LEFT JOIN aicrm_projects AS project ON project.projectsid = tb_detail.crmid
        INNER JOIN aicrm_users AS oldSale ON oldSale.id = tb_main.old_sale_rep
        INNER JOIN aicrm_users AS newSale ON newSale.id = tb_main.new_sale_rep
        INNER JOIN aicrm_users AS modifiedBy ON modifiedBy.id = tb_main.creator_id
        WHERE 1";

        if(@$req['logOldSale'] != '') $query .= " AND tb_main.old_sale_rep = ".$req['logOldSale'];
        if(@$req['logNewSale'] != '') $query .= " AND tb_main.new_sale_rep = ".$req['logNewSale'];
        if(!empty(@$req['logModules'])) $query .= " AND tb_main.module IN (".implode(',', $req['logModules']).")";
        if(@$req['logStartDate'] != '') {
            $logStartDate = date('Y-m-d', strtotime($req['logStartDate']));
            $query .= " AND DATE(tb_main.convert_date) >= '".$logStartDate."'";
        }
        if(@$req['logEndDate'] != ''){
            $logEndDate = date('Y-m-d', strtotime($req['logEndDate']));
            $query .= " AND DATE(tb_main.convert_date) <= '".$logEndDate."'";
        }

        $rs = $adb->pquery($query, '');
        $count = $adb->num_rows($rs);
        $resultData = [];
        for($i=0; $i<$count; $i++){
            $resultData[] = $adb->query_result_rowdata($rs, $i);
        }
        break;
    case 'export-log':
        $req = $_REQUEST['data'];
        $query = "SELECT
            tb_detail.crmid,
            DATE_FORMAT(tb_main.convert_date, '%d-%m-%Y %H:%i:%s') AS convert_date,
            CASE WHEN tb_main.module = 6 THEN 'Account'
                WHEN tb_main.module = 50 THEN 'Project Order'
                ELSE '' END AS module,
            CASE WHEN account.account_no != '' THEN account.account_no ELSE project.projects_no END AS no,
            CASE WHEN account.accountname != '' THEN account.accountname ELSE project.projects_name END AS name,
            CONCAT(oldSale.user_name) AS old_sales_rep,
            CONCAT(newSale.user_name) AS new_sales_rep,
            CONCAT(modifiedBy.user_name) AS modified_by
        FROM aicrm_sale_rep_convert AS tb_main
        INNER JOIN aicrm_sale_rep_convert_detail AS tb_detail ON tb_detail.convert_id = tb_main.id
        LEFT JOIN aicrm_account AS account ON account.accountid = tb_detail.crmid
        LEFT JOIN aicrm_projects AS project ON project.projectsid = tb_detail.crmid
        INNER JOIN aicrm_users AS oldSale ON oldSale.id = tb_main.old_sale_rep
        INNER JOIN aicrm_users AS newSale ON newSale.id = tb_main.new_sale_rep
        INNER JOIN aicrm_users AS modifiedBy ON modifiedBy.id = tb_main.creator_id
        WHERE 1";

        if(@$req['logOldSale'] != '') $query .= " AND tb_main.old_sale_rep = ".$req['logOldSale'];
        if(@$req['logNewSale'] != '') $query .= " AND tb_main.new_sale_rep = ".$req['logNewSale'];
        if(!empty(@$req['logModules'])) $query .= " AND tb_main.module IN (".implode(',', $req['logModules']).")";
        if(@$req['logStartDate'] != '') {
            $logStartDate = date('Y-m-d', strtotime($req['logStartDate']));
            $query .= " AND DATE(tb_main.convert_date) >= '".$logStartDate."'";
        }
        if(@$req['logEndDate'] != ''){
            $logEndDate = date('Y-m-d', strtotime($req['logEndDate']));
            $query .= " AND DATE(tb_main.convert_date) <= '".$logEndDate."'";
        }

        $rs = $adb->pquery($query, '');
        $count = $adb->num_rows($rs);
        $result = [];
        for($i=0; $i<$count; $i++){
            $result[] = $adb->query_result_rowdata($rs, $i);
        }

        $data = [
            ['Date Time', 'Module', 'Record No.', 'Record Name', 'Old Sales Rep.', 'New Sales Rep.', 'Modified By']
        ];

        foreach($result as $row){
            $data[] = [
                $row['convert_date'],
                $row['module'],
                $row['no'],
                $row['name'],
                $row['old_sales_rep'],
                $row['new_sales_rep'],
                $row['modified_by'],
            ];
        }
        $fileName = 'export/convert_sales_rep_log.xlsx';
        $writer = new XLSXWriter();
        $writer->writeSheet($data);
        $writer->writeToFile($fileName);
        $resultData = ['status' => 'Success', 'path' => $site_URL.$fileName];
        break;
}

function updateProjectRelatePerson($projectID, $oldSale, $newSale)
{
    global $adb;
    $sql = "SELECT user_name FROM aicrm_users WHERE id=?";
    $rs = $adb->pquery($sql, [$oldSale]);
    $oldSaleData = $adb->query_result_rowdata($rs, 0);
    $oldSaleName = $oldSaleData['user_name'];

    $sql = "SELECT user_name FROM aicrm_users WHERE id=?";
    $rs = $adb->pquery($sql, [$newSale]);
    $newSaleData = $adb->query_result_rowdata($rs, 0);
    $newSaleName = $newSaleData['user_name'];

    $rs = $adb->pquery("UPDATE aicrm_projects SET related_sales_person=REPLACE(related_sales_person, '".$oldSaleName."', '".$newSaleName."') WHERE projectsid=?", [$projectID]);
    return $rs;
}

function updateAccountProject($accountID, $oldSale, $newSale)
{
    global $adb;

    $sql = "SELECT account_no FROM aicrm_account WHERE accountid=?";
    $rs = $adb->pquery($sql, [$accountID]);
    $accountData = $adb->query_result_rowdata($rs, 0);
    $accountNo = $accountData['account_no'];

    $sql = "SELECT user_name FROM aicrm_users WHERE id=?";
    $rs = $adb->pquery($sql, [$oldSale]);
    $oldSaleData = $adb->query_result_rowdata($rs, 0);
    $oldSaleName = $oldSaleData['user_name'];

    $sql = "SELECT user_name FROM aicrm_users WHERE id=?";
    $rs = $adb->pquery($sql, [$newSale]);
    $newSaleData = $adb->query_result_rowdata($rs, 0);
    $newSaleName = $newSaleData['user_name'];

    $sql = "SELECT 
        tmp2.projectid,
        GROUP_CONCAT(tmp2.field SEPARATOR ', ') AS field
    FROM (
        SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_owner_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventoryowner WHERE owner_no = '".$accountNo."'
        UNION ALL
        SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_consultant_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventoryconsultant WHERE consultant_no = '".$accountNo."'
        UNION ALL
        SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_architecture_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventoryarchitecture WHERE architecture_no = '".$accountNo."'
        UNION ALL
        SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_construction_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventoryconstruction WHERE construction_no = '".$accountNo."'
        UNION ALL
        SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_designer_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventorydesigner WHERE designer_no = '".$accountNo."'
        UNION ALL
        SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_contractor_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventorycontractor WHERE contractor_no = '".$accountNo."'
        UNION ALL
        SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_sub_contractor_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventorysubcontractor WHERE sub_contractor_no = '".$accountNo."'
    ) AS tmp2
    GROUP BY tmp2.projectid";
    $rs = $adb->pquery($sql, '');
    $count = $adb->num_rows($rs);
    $returnList = [];
    for($i=0; $i<$count; $i++){
        $rowData = $adb->query_result_rowdata($rs, $i);
        $returnList[] = $rowData;
    }

    return $returnList;
}

function post_Api( $url, $param=[] ){
	$param['AI-API-KEY'] = '1234';
	$fields_string = json_encode($param);
	$json_url = $url;

	$json_string = $fields_string;
	$ch = curl_init( $json_url );
	$options = array(
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
		CURLOPT_POSTFIELDS => $json_string,
		CURLOPT_BUFFERSIZE => 1024,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_DNS_USE_GLOBAL_CACHE => false,
		CURLOPT_DNS_CACHE_TIMEOUT => 2
	);

	curl_setopt_array( $ch, $options );
	$result =  curl_exec($ch);
	$return = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result), true );
	return $return;
}

echo json_encode($resultData, JSON_UNESCAPED_UNICODE); exit;