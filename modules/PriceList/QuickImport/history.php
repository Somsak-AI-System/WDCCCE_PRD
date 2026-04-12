<?
header('Content-Type: text/html; charset=utf-8');

require_once ('include/utils/CommonUtils.php');
require_once ('PHPExcel/PHPExcel.php');
global $adb, $currentModule, $current_user, $site_URL;

$post = $_POST;

if(isset($post['rows'])){
    $perpage = $post['rows'];
    $start = $post['page'] == 1 ? 0 : $perpage * ($post['page']-1);

    $query = "SELECT tbt_import_pricelist.*, DATE_FORMAT(import_date, '%d/%m/%Y %H:%i:%s') AS import_date, CONCAT(aicrm_users.first_name, ' ', aicrm_users.last_name) AS importor FROM tbt_import_pricelist 
    INNER JOIN aicrm_users ON aicrm_users.id = tbt_import_pricelist.userid";
    $rs = $adb->pquery($query, '');
    $total = $adb->num_rows($rs);

    $query .= " LIMIT ".$start.",".$perpage;
    // echo $query;
    $rs = $adb->pquery($query, '');
    $count = $adb->num_rows($rs);
    $data = [];
    for($i=0; $i<$count; $i++){
        $data[] = $adb->query_result_rowdata($rs, $i);
    }

    echo json_encode(['total'=>$total, 'rows'=>$data], JSON_UNESCAPED_UNICODE);
}

if(isset($post['export_type'])){
    $exportType = $post['export_type'];
    $importId = $post['id'];

    $query = "SELECT * FROM tbt_import_pricelist_items WHERE import_id = ".$importId;
    $rs = $adb->pquery($query, '');
    $count = $adb->num_rows($rs);

    $data = [];
    for($i=0; $i<$count; $i++){
        $row = $adb->query_result_rowdata($rs, $i);

        switch($exportType){
            case 'all':
                $data[] = $row;
                break;
            case 'new':
                if($row['import_type'] == 'new') $data[] = $row;
                break;
            case 'update':
                if($row['import_type'] == 'update') $data[] = $row;
                break;
            case 'error':
                if($row['import_type'] == 'error') $data[] = $row;
                break;
        }
    }

    $fileName = exportExcel($data);
    echo $fileName;
}

if(isset($post['check_import'])){
    $exportType = $post['check_import'];
    $importData = $post['data'];

    $data = [];
    foreach($importData as $row){
        switch($exportType){
            case 'new':
                if($row['error'] == '' && $row['pricelistid'] == '') $data[] = $row;
                break;
            case 'update':
                if($row['error'] == '' && $row['pricelistid'] != '') $data[] = $row;
                break;
            case 'error':
                if($row['error'] != '') $data[] = $row;
                break;
        }
    }
    $fileName = exportExcel($data);
    echo $fileName;
}

function exportExcel($data){
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ชื่อรายการราคา')
        ->setCellValue('B1', 'รูปแบบรายการราคา')
        ->setCellValue('C1', 'No')
        ->setCellValue('D1', 'รหัสสินค้า')
        ->setCellValue('E1', 'Showroom')
        ->setCellValue('F1', 'List Price')
        ->setCellValue('G1', 'Normal')
        ->setCellValue('H1', 'Tier 1')
        ->setCellValue('I1', 'Tier 2')
        ->setCellValue('J1', 'Tier 3')
        ->setCellValue('K1', 'Error Message');

    foreach($data as $i => $row){
        $rowNo = $i + 2;
        $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$rowNo, $row['pricelist_name'])
            ->setCellValue('B'.$rowNo, $row['pricelist_type'])
            ->setCellValue('C'.$rowNo, $row['item_no'])
            ->setCellValueExplicit('D'.$rowNo, $row['material_code'], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValue('E'.$rowNo, $row['showroom'])
            ->setCellValue('F'.$rowNo, $row['listprice'])
            ->setCellValue('G'.$rowNo, $row['normal'])
            ->setCellValue('H'.$rowNo, $row['tier1'])
            ->setCellValue('I'.$rowNo, $row['tier2'])
            ->setCellValue('J'.$rowNo, $row['tier3'])
            ->setCellValue('K'.$rowNo, @$row['error']);
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $fileName = "export/import_pricelist_history.xlsx";
    $objWriter->save($fileName);

    return $fileName;
}