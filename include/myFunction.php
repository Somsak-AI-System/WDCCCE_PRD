<?php
function get_autorun($prefix,$module,$lenght){
	global $adb;
	global $current_user;
	global $log;

	$sql="
	SELECT running
	FROM ai_running_doc
	where 1
	and module='".$module."'
	and prefix='".$prefix."' 	";

	$res=$adb->pquery($sql, array());
	$rows = $adb->num_rows($res);

	if($rows>0){
		$running = $adb->query_result($res,0,'running');
		$running=$running+1;
		$sql="update ai_running_doc set running='".$running."' where 1 and module='".$module."' and prefix='".$prefix."' ";

		$adb->pquery($sql, array());
	}else{
		$running=1;
		$sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";

		$adb->pquery($sql, array());
	}

	$cd=$prefix."-".str_pad($running,$lenght,"0", STR_PAD_LEFT);
	return $cd;
}

function generateRandomString($length = 10) {
    $characters = date('YmdHis').'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function get_autorun_order($prefix,$module,$lenght){
	global $adb;
	global $current_user;
	global $log;

	$sql="
	SELECT running
	FROM ai_running_doc
	where 1
	and module='".$module."'
	and prefix='".$prefix."' 	";

	$res=$adb->pquery($sql, array());
	$rows = $adb->num_rows($res);

	if($rows>0){
		$running = $adb->query_result($res,0,'running');
		$running=$running+1;
		$sql="update ai_running_doc set running='".$running."' where 1 and module='".$module."' and prefix='".$prefix."' ";

		$adb->pquery($sql, array());
	}else{
		$running=1;
		$sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";

		$adb->pquery($sql, array());
	}

	$cd = $prefix."".str_pad($running,$lenght,"0", STR_PAD_LEFT);
	return $cd;
}

function get_autorun_quotes($prefix,$month,$day,$module,$lenght){
	global $adb;
	global $current_user;
	global $log;

	$sql="
	SELECT running
	FROM ai_running_doc
	where 1
	and module='".$module."'
	and prefix='".$prefix."' 	";


	$res=$adb->pquery($sql, array());
	$rows = $adb->num_rows($res);

	if($rows>0){
		$running = $adb->query_result($res,0,'running');
		$running=$running+1;
		$sql="update ai_running_doc set running='".$running."' where 1 and module='".$module."' and prefix='".$prefix."' ";

		$adb->pquery($sql, array());
	}else{
		$running=1;
		$sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";

		$adb->pquery($sql, array());
	}

	$cd=$prefix.$month.$day."-".str_pad($running,$lenght,"0", STR_PAD_LEFT);
	return $cd;
}
?>