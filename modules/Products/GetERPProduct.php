<?php
require_once('include/logging.php');
require_once('include/utils/CommonUtils.php');

global $adb, $currentModule, $current_user, $serviceAPI;

$filterDate = date('Ymd', strtotime('-3 day', strtotime(date('Y-m-d'))));
$url = $serviceAPI.'/SAPDI/GetItem?datestring='.$filterDate;
// echo $url; exit;
$logID = logUniqID();
logInfo(['module'=>'Products', 'title'=>'SAP_UPDATE_PRODUCT', 'logID'=>$logID, 'type'=>'Request', 'time'=>date('Y-m-d H:i:s'), 'url'=>$url, 'data'=>json_encode([], JSON_UNESCAPED_UNICODE)]);
$rs = getApi($url, [], 'get');
logInfo(['module'=>'Products', 'title'=>'SAP_UPDATE_PRODUCT', 'logID'=>$logID, 'type'=>'Response', 'time'=>date('Y-m-d H:i:s'), 'url'=>$url, 'data'=>json_encode($rs, JSON_UNESCAPED_UNICODE)]);

foreach($rs as $i => $item){
    $sql = $adb->pquery("SELECT productid FROM aicrm_products WHERE material_code='".$item['itemcode']."'");
    $findProduct = $adb->query_result_rowdata($sql, 0);
    
    if($findProduct['productid'] == ''){
        $focus = new $currentModule();
        $focus->mode = 'add';
    }else{
        $focus = new $currentModule();
        $focus->mode = 'edit';
    }
     
    $focus->id  = $findProduct['productid'];
    $focus->column_fields['productname'] = $item['itemname'];
    $focus->column_fields['material_code'] = $item['itemcode'];
    $focus->column_fields['product_group'] = $item['itemgroupname'];
    $focus->column_fields['product_brand'] = $item['categoriesname'];
    $focus->column_fields['product_catalog_code'] = $item['subcategoriesname'];
    $focus->column_fields['product_sub_group'] = $item['subgroupname1'];
    $focus->column_fields['package_size_sqm_per_box'] = $item['volumn'];
    $focus->column_fields['package_size_sheet_per_box'] = $item['pieceinbox'];
    $focus->column_fields['package_size_box_per_palate'] = $item['boxperpallet'];
    $focus->column_fields['palate_weight'] = $item['weightperpallet'];
    $focus->column_fields['remark'] = $item['remark'];
    $focus->column_fields['unit'] = $item['salesuom'];
    $focus->column_fields['sales_unit'] = $item['salesuom'];
    $focus->column_fields['product_weight_per_box'] = $item['weight'];

    $focus->save($currentModule);
    $crmid = $focus->id;
}

echo json_encode(['status'=>'success']);