<?php
include("config.inc.php");
include("library/dbconfig.php");
include_once("library/myLibrary_mysqli.php");

$mysqli = new myLibrary_mysqli();
$mysqli->_dbconfig = $dbconfig;

if(!isset($_REQUEST['tabID']) || $_REQUEST['tabID'] == '') {
    echo 'TabID not found!';
    exit();
}

$query = "SELECT profileid FROM aicrm_profile";
$profiles = $mysqli->select($query);

$query = "SELECT
aicrm_field.fieldid
FROM aicrm_field
INNER JOIN aicrm_blocks ON aicrm_blocks.blockid = aicrm_field.block 
WHERE
aicrm_field.tabid = '".$_REQUEST['tabID']."' 
AND aicrm_field.displaytype IN ( 1, 2, 3, 4 ) 
AND aicrm_field.presence IN ( 0, 2 )
ORDER BY
aicrm_blocks.sequence,
aicrm_field.sequence";
$fields = $mysqli->select($query);

// echo '<pre>';
// print_r($profiles);
// echo '</pre>';

foreach($profiles as $profile){
    $profileID = $profile['profileid'];

    $query = "SELECT * FROM aicrm_profile2tab WHERE profileid = '".$profileID."' AND tabid = '".$_REQUEST['tabID']."'";
    $findProfile2Tab = $mysqli->select($query);
    if(empty($findProfile2Tab)){
        $insertProfile2Tab = "INSERT INTO aicrm_profile2tab (profileid, tabid, permissions) VALUES ('".$profileID."', '".$_REQUEST['tabID']."', 0)";
        $mysqli->Query($insertProfile2Tab);
    }

    for($i = 0; $i <= 5; $i++){
        $query = "SELECT * FROM aicrm_profile2standardpermissions WHERE profileid = '".$profileID."' AND tabid = '".$_REQUEST['tabID']."' AND operation = '".$i."'";
        $findPermission = $mysqli->select($query);

        if(empty($findPermission)){
            $insertPermission = "INSERT INTO aicrm_profile2standardpermissions (profileid, tabid, operation, permissions) VALUES ('".$profileID."', '".$_REQUEST['tabID']."', '".$i."', 0)";
            $mysqli->Query($insertPermission);
        }
    }

    foreach(['5','6','8','10'] as $activityID){
        $query = "SELECT * FROM aicrm_profile2utility WHERE profileid = '".$profileID."' AND tabid = '".$_REQUEST['tabID']."' AND activityid = '".$activityID."'";
        $findUtility = $mysqli->select($query);

        if(empty($findUtility)){
            $insertUtility = "INSERT INTO aicrm_profile2utility (profileid, tabid, activityid, permission) VALUES ('".$profileID."', '".$_REQUEST['tabID']."', '".$activityID."', 0)";
            $mysqli->Query($insertUtility);
        }
    }

    foreach($fields as $field){
        $fieldID = $field['fieldid'];
        $query = "SELECT * FROM aicrm_profile2field 
        WHERE profileid = '".$profileID."' 
        AND tabid = '".$_REQUEST['tabID']."'
        AND fieldid = '".$fieldID."'";
        $findProfile2Field = $mysqli->select($query);
        if(empty($findProfile2Field)){
            $insertProfile2Field = "INSERT INTO aicrm_profile2field (profileid, tabid, fieldid, visible, readonly)
            VALUES ('".$profileID."', '".$_REQUEST['tabID']."', '".$fieldID."', 0, 1)";
            $mysqli->Query($insertProfile2Field);
            // echo $query.'<br>'; 
        }
    }
}

echo 'Update Profile Complate';