<?php
global $adb, $site_URL, $current_user;
$currentModule = 'Users';

require_once ('PHPExcel/PHPExcel.php');
require_once("modules/$currentModule/$currentModule.php");
require_once('include/utils/UserInfoUtil.php');
require_once('modules/Users/CreateUserPrivilegeFile.php');


$reader = PHPExcel_IOFactory::createReader('Excel2007');
$excel = $reader->load('documents/import/import_user.xlsx');
$worksheet = [];
$columns = [];
$data = [];
foreach ($excel->getWorksheetIterator() as $index => $worksheet) {
    $worksheets = $worksheet->toArray();
    if ($index == 0) $worksheet = $worksheets;
}

if (!is_array($worksheet)) {
    echo json_encode(['status' => 'error', 'data' => []]);
    exit();
}

$password = 'aicrm';
$errorData = [];
foreach ($worksheet as $i => $row) {
    if ($i > 0) {
        
        $focus = new $currentModule();

        $roleName = '';
        if ($row[6] != '' && $row[7] != '') {
        	$roleName = $row[6] . ':' . $row[7];
        } else if ($row[4] != '' && $row[5] != '') {
        	$roleName = $row[4] . ':' . $row[5];
        } else if ($row[8] != '' && $row[9] != '') {
        	$roleName = $row[9] . ':' . $row[9];
        }

        $roleID = '';
        if ($roleName != '') {
            $sql = $adb->pquery("SELECT * FROM aicrm_role WHERE rolename=?", [$roleName]);
            $role = $adb->query_result_rowdata($sql, 0);
            $roleID = $role['roleid'];
        }

        if($roleID == '') $roleID = 'H939';

        // $focus->column_fields['user_name'] = $row[1];
        // $focus->column_fields['user_password'] = $password;
        // $focus->column_fields['confirm_password'] = $password;
        // $focus->column_fields['email1'] = $row[1];
        // $focus->column_fields['first_name_th'] = @explode(' ', $row[2])[0];
        // $focus->column_fields['last_name_th'] = @explode(' ', $row[2])[1];
        // $focus->column_fields['first_name'] =  @explode(' ', $row[3])[0];
        // $focus->column_fields['last_name'] = @explode(' ', $row[3])[1];
        // $focus->column_fields['status'] = 'Active';
        // $focus->column_fields['section'] = 'SYSTEM';
        // $focus->column_fields['user_role'] = $roleID;

        // $focus->saveentity($currentModule);
        // $userID = $focus->id;

        // $focus->saveHomeStuffOrder($focus->id);
        // $focus->resetReminderInterval('None');

        // $user_hash = strtolower(md5($password));
        // $adb->pquery("UPDATE aicrm_users SET user_hash=? WHERE id=?", [$user_hash, $focus->id]);

        // updateUser2RoleMapping($roleID, $focus->id);
        // createUserPrivilegesfile($focus->id);
        // createUserSharingPrivilegesfile($focus->id);

        // echo '<pre>';
        // echo $roleName;
        // echo $roleID;
        // print_r($row);
        // echo '</pre>';
    }
}

echo json_encode(['status' => 'success', 'data' => []]);
