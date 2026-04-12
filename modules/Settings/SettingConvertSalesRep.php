<?php
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');
require_once('include/utils/UserInfoUtil.php');

global $mod_strings, $adb, $theme, $app_strings, $default_charset;
$theme_path = "themes/" . $theme . "/";
$image_path = $theme_path . "images/";
$smarty = new vtigerCRM_Smarty;
$parenttab = getParentTab();

$smarty->assign("PARENTTAB", $parenttab);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("MOD", $mod_strings);

$sql = "SELECT * FROM aicrm_settings_field WHERE fieldid = 49";
$result = $adb->pquery($sql, '');
$tabData = $adb->query_result_rowdata($result, 0);

$sql = "SELECT id, user_name FROM aicrm_users WHERE email1 like '%@wdc.co.th%'";
$rs = $adb->pquery($sql, '');
$userCount = $adb->num_rows($rs);
$userDatas = [];
for($i=0; $i<$userCount; $i++){
    $userDatas[] = $adb->query_result_rowdata($rs, $i);
}

$sql = "SELECT accountstatusid AS id, accountstatus AS label FROM aicrm_accountstatus";
$rs = $adb->pquery($sql, '');
$accountStatusCount = $adb->num_rows($rs);
$accountStatus = [];
for($i=0; $i<$accountStatusCount; $i++){
    $accountStatus[] = $adb->query_result_rowdata($rs, $i);
}

$sql = "SELECT quotation_statusid AS id, quotation_status AS label FROM aicrm_quotation_status";
$rs = $adb->pquery($sql, '');
$qStatusCount = $adb->num_rows($rs);
$quotationStatus = [];
for($i=0; $i<$qStatusCount; $i++){
    $quotationStatus[] = $adb->query_result_rowdata($rs, $i);
}

$sql = "SELECT projectorder_statusid AS id, projectorder_status AS label FROM aicrm_projectorder_status";
$rs = $adb->pquery($sql, '');
$projectStatusCount = $adb->num_rows($rs);
$projectStatus = [];
for($i=0; $i<$projectStatusCount; $i++){
    $projectStatus[] = $adb->query_result_rowdata($rs, $i);
}

$sql = "SELECT project_opportunityid AS id, project_opportunity AS label FROM aicrm_project_opportunity";
$rs = $adb->pquery($sql, '');
$projectOpportunityCount = $adb->num_rows($rs);
$projectOpportunity = [];
for($i=0; $i<$projectOpportunityCount; $i++){
    $projectOpportunity[] = $adb->query_result_rowdata($rs, $i);
}

$sql = "SELECT project_sizeid AS id, project_size AS label FROM aicrm_project_size";
$rs = $adb->pquery($sql, '');
$projectSizeCount = $adb->num_rows($rs);
$projectSize = [];
for($i=0; $i<$projectSizeCount; $i++){
    $projectSize[] = $adb->query_result_rowdata($rs, $i);
}
?>
<script type="text/javascript" src="asset/js/jquery.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="asset/js/datagrid-filter.js"></script>
<link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css">
<link rel="stylesheet" type="text/css" href="asset/css/tailwind-custom.css?t=<?php echo time(); ?>" />
<link rel="stylesheet" type="text/css" href="asset/css/easyui-custom.css?t=<?php echo time(); ?>" />

<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
    <tbody>
        <tr>
            <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
                <div align=center>
                    <?php $smarty->display("SetMenu.tpl"); ?>

                    <div class='p-2 bg-white'>
                        <div class="flex flex-col gap-2 border-b pb-2">
                            <div class="flex items-center justify-start gap-2">
                                <div>
                                    <img src="themes/images/<?php echo $tabData['iconpath']; ?>" class="h-12 w-12" />
                                </div>
                                <div>
                                    <div class="flex items-center gap-1 font-black">
                                        <span class="text-xl"><?php echo $parenttab; ?></span>
                                        <div class="text-xl px-2"> > </div>
                                        <span class="text-xl"><?php echo $tabData['name']; ?></span>
                                    </div>
                                    <div class="text-xs">
                                        <span><?php echo $tabData['description']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div id="setting-convert-tabs" class="easyui-tabs">
                                <div title="Change Sales Reps">
                                    <?php include('SettingConvertSalesRep/FirstStep.php'); ?>
                                    <?php include('SettingConvertSalesRep/AccountStep.php'); ?>
                                    <?php include('SettingConvertSalesRep/QuoteStep.php'); ?>
                                    <?php include('SettingConvertSalesRep/ProjectStep.php'); ?>
                                    <?php include('SettingConvertSalesRep/FinalStep.php'); ?>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<script>
    
    var userData = JSON.parse('<?php echo json_encode($userDatas, JSON_UNESCAPED_UNICODE); ?>')
    var accountStatus = JSON.parse('<?php echo json_encode($accountStatus, JSON_UNESCAPED_UNICODE); ?>')
    var quotationStatus = JSON.parse('<?php echo json_encode($quotationStatus, JSON_UNESCAPED_UNICODE); ?>')
    var projectStatus = JSON.parse('<?php echo json_encode($projectStatus, JSON_UNESCAPED_UNICODE); ?>')
    var projectOpportunity = JSON.parse('<?php echo json_encode($projectOpportunity, JSON_UNESCAPED_UNICODE); ?>')
    var projectSize = JSON.parse('<?php echo json_encode($projectSize, JSON_UNESCAPED_UNICODE); ?>')
    var oldSaleRep = ''
    var oldSaleRepData = null
    var newSaleRep = ''
    var newSaleRepData = null
    var modules = [6]
    var selectedAccount = []
    var selectedQuotes = []
    var selectedProject = []
    var convertQuotationWithSO = false

    
</script>
<script src="modules/Settings/SettingConvertSalesRep/SettingConvert.js?t=<?php echo time(); ?>"></script>
<script>
    $.runStep1();
</script>