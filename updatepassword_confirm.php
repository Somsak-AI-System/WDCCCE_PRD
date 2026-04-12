<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once("config.inc.php");

require_once('modules/Users/Users.php');
require_once('include/utils/UserInfoUtil.php');

global $adb;

$focus = new Users();
$focus->retrieve_entity_info($_REQUEST['record'],'Users');
$focus->id = $_REQUEST['record'];

$changePass = $focus->change_password($_REQUEST['user_password'], $_REQUEST['password']);

$res = ['status' => false];
if($changePass) {
    $adb->pquery("UPDATE aicrm_users SET first_login=1 WHERE id=".$_REQUEST['record']);
    $res = ['status' => true];
}

echo json_encode($res, JSON_UNESCAPED_UNICODE);
?>