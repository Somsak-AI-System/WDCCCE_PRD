<?
header('Content-Type: text/html; charset=utf-8');

require_once ('include/utils/CommonUtils.php');
require_once ('PHPExcel/PHPExcel.php');
global $adb, $currentModule, $current_user, $site_URL;

$columns = [
    'pricelist_name',
    'pricelist_type',
    'item_no',
    'material_code',
    'showroom',
    'listprice',
    'normal',
    'tier1',
    'tier2',
    'tier3',
];

if(isset($_FILES['importFile'])){
    $reader = PHPExcel_IOFactory::createReader('Excel2007');
    $file = isset($_FILES["importFile"]['tmp_name']) ? $_FILES["importFile"]['tmp_name'] : '';
    $excel = $reader->load($file);

    foreach ($excel->getWorksheetIterator() as $index => $worksheet) {
        $worksheets = $worksheet->toArray();
        if($index == 0) $worksheet = $worksheets;
    }

    $sheetData = [];
    $errorData = [];
    $pricelistid = '';
    foreach($worksheet as $i => $row){
        if($i > 0) {
            $rowData = [];
            $error = [];
            
            $pricelist_name = '';
            foreach($row as $index => $value){
                $value = trim($value);
                $current_encoding = mb_detect_encoding($value, 'auto');

                if($index == 0) $pricelist_name = iconv($current_encoding, 'UTF-8', $value);

                if($columns[$index] != '') $rowData[$columns[$index]] = iconv($current_encoding, 'UTF-8', $value);

                if($columns[$index] == 'pricelist_name' && $rowData[$columns[$index]] == '') $error[] = 'ชื่อรายการราคา ไม่มีข้อมูล';
                if($columns[$index] == 'pricelist_type' && $rowData[$columns[$index]] == '') $error[] = 'รูปแบบรายการราคา ไม่มีข้อมูล';
                if($columns[$index] == 'material_code' && $rowData[$columns[$index]] == '') $error[] = 'รหัสสินค้า ไม่มีข้อมูล';
                if($columns[$index] == 'showroom' && $rowData[$columns[$index]] == '') $error[] = 'Showroom ไม่มีข้อมูล';
                if($columns[$index] == 'listprice' && $rowData[$columns[$index]] == '') $error[] = 'List Price ไม่มีข้อมูล';
                if($columns[$index] == 'normal' && $rowData[$columns[$index]] == '') $error[] = 'Normal ไม่มีข้อมูล';
                if($columns[$index] == 'tier1' && $rowData[$columns[$index]] == '') $error[] = 'Tier 1 ไม่มีข้อมูล';
                if($columns[$index] == 'tier2' && $rowData[$columns[$index]] == '') $error[] = 'Tier 2 ไม่มีข้อมูล';
                if($columns[$index] == 'tier3' && $rowData[$columns[$index]] == '') $error[] = 'Tier 3 ไม่มีข้อมูล';
            }

            // $rowData['sellprice_include_vat'] = 0;
            // if($rowData['sellprice'] != ''){
            //     $rowData['sellprice_include_vat'] = $rowData['sellprice'] + (($rowData['sellprice'] * 7) / 100);
            // }

            if($i == 1){
                $query = "SELECT aicrm_pricelists.pricelistid FROM aicrm_pricelists
                    INNER JOIN aicrm_pricelistscf ON aicrm_pricelistscf.pricelistid = aicrm_pricelists.pricelistid
                    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
                    WHERE aicrm_crmentity.deleted = 0
                    AND aicrm_pricelists.pricelist_name = '".$pricelist_name."'";
                $rs = $adb->pquery($query, '');
                $checkItem = $adb->query_result_rowdata($rs, 0);
                // print_r($checkItem);
                $pricelistid = $checkItem['pricelistid'];
                $rowData['pricelistid'] = $pricelistid;
            } else {
                $rowData['pricelistid'] = $pricelistid;
            }

            if($rowData['material_code'] != ''){
                $query = "SELECT aicrm_products.productid,
                    aicrm_products.productname,
                    aicrm_products.material_code,
                    aicrm_products.product_brand,
                    aicrm_products.product_weight_per_box,
                    aicrm_products.producttatus,
                    aicrm_products.productdescription
                FROM aicrm_products
                INNER JOIN aicrm_productcf ON aicrm_productcf.productid = aicrm_products.productid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
                WHERE aicrm_crmentity.deleted = 0
                AND aicrm_products.material_code = '".$rowData['material_code']."'";
                $rs = $adb->pquery($query, '');
                $checkProduct = $adb->query_result_rowdata($rs, 0);
                if($checkProduct == ''){
                    $error[] = 'ไม่มีข้อมูลสินค้าในระบบ';
                } 
                $rowData['productid'] = $checkProduct['productid'];
                $rowData['productname'] = $checkProduct['productname'];
                $rowData['product_brand'] = $checkProduct['product_brand'];
                $rowData['product_weight_per_box'] = $checkProduct['product_weight_per_box'];
                $rowData['producttatus'] = $checkProduct['producttatus'];
                $rowData['productdescription'] = $checkProduct['productdescription'];
            }

            $rowData['error'] = implode(', ', $error);

            $sheetData[] = $rowData;
        }        
    }

    echo json_encode($sheetData, JSON_UNESCAPED_UNICODE);
}

if(isset($_POST['type']) && $_POST['type'] == 'import'){
    $dataList = $_POST['data'];
    $totalItems = $_POST['newItems'] + $_POST['updateItems'] + $_POST['errorItems'];

    $query = "INSERT INTO tbt_import_pricelist (filename, total_items, new_items, update_items, error_items, import_date, userid)
    VALUES ('".$_POST['filename']."', '".$totalItems."', '".$_POST['newItems']."', '".$_POST['updateItems']."', '".$_POST['errorItems']."', NOW(), '".$current_user->id."')";
    $adb->pquery($query, '');

    $importID = $adb->getLastInsertID();

    $reslist = [];
    $crmid = $dataList[0]['pricelistid'];
    foreach($dataList as $row => $data){

        // insert log
        $importType = '';
        if($data['error'] != ''){
            $importType = 'error';
        } else {
            $importType = 'new';
        }

        $pricelist_status = 'Active';

        $query = "INSERT INTO tbt_import_pricelist_items (
            import_id, 
            import_type, 
            pricelistid,
            pricelist_name,
            pricelist_status,
            pricelist_type,
            item_no,
            productid,
            productname,
            material_code,
            showroom,
            listprice,
            normal,
            tier1,
            tier2,
            tier3,
            error
        ) VALUES (
            '".$importID."', '".$importType."', 
            '".$data['pricelistid']."', 
            '".$data['pricelist_name']."', 
            '".$pricelist_status."', 
            '".$data['pricelist_type']."',  
            '".$data['item_no']."', 
            '".$data['productid']."', 
            '".$data['productname']."', 
            '".$data['material_code']."', 
            '".$data['showroom']."', 
            '".$data['listprice']."', 
            '".$data['normal']."', 
            '".$data['tier1']."',
            '".$data['tier2']."',
            '".$data['tier3']."',
            '".$data['error']."'
        )";
        $adb->pquery($query, '');
        // insert log

        if($data['error'] != '') continue; 

        if($row == 0){
            $act = $crmid == '' ? 'add':'edit';
            checkFileAccess("modules/$currentModule/$currentModule.php");
            require_once("modules/$currentModule/$currentModule.php");

            $focus = new $currentModule();
            $focus->mode = $act;
            $focus->id  = $crmid;

            $focus->column_fields['pricelist_name'] = $data['pricelist_name'];
            $focus->column_fields['status_pricelist'] = $pricelist_status;
            $focus->column_fields['pricelist_type'] = $data['pricelist_type'];
            $focus->column_fields['pricelist_startdate'] = date('Y-m-d');
            $focus->column_fields['pricelist_enddate'] = date('Y-m-d', strtotime('+ 3 year', strtotime(date('Y-m-d'))));
            $focus->column_fields['assigned_user_id'] = $current_user->id;
            $focus->save($currentModule);
            $crmid = $focus->id;
            $reslist[] = $focus->id;

            $adb->pquery("delete from aicrm_inventorypricelist where id=?", array($focus->id));
        }
        
        $query ="INSERT INTO aicrm_inventorypricelist(
            id, 
            productid, 
            product_name, 
            material_code,
            sequence_no,
            product_brand,
            product_weight_per_box,
            productstatus,
            pricelist_showroom,
            listprice_project,
            pricelist_nomal,
            pricelist_first_tier,
            pricelist_second_tier,
            pricelist_third_tier,
            selling_price,
            selling_price_inc
        ) VALUES (
            '".$crmid."',
            '".$data['productid']."', 
            '".$data['productname']."',
            '".$data['material_code']."',
            '".$data['item_no']."',
            '".$data['product_brand']."',
            '".$data['product_weight_per_box']."',
            '".$data['producttatus']."',
            '".$data['showroom']."', 
            '".$data['listprice']."', 
            '".$data['normal']."', 
            '".$data['tier1']."',
            '".$data['tier2']."',
            '".$data['tier3']."',
            '".$data['sellprice']."',
            '".$data['sellprice_include_vat']."'
        )";
        $adb->pquery($query,"");
    }
    echo json_encode($reslist);
}
