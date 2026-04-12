<?
	session_start();
	include("../../config.inc.php");
	include("../../library/dbconfig.php");
	include("../../library/myFunction.php");
	include("../../library/generate_MYSQL.php");
	
	global $generate,$current_user;
	$generate = new generate($dbconfig ,"DB");
	if($_REQUEST["action"]=="delete" and $_REQUEST["id"]!=""){
		$sql="update aicrm_config_vendorbuyer set 
		deleted=1
		where id='".$_REQUEST["id"]."'
		";
		$generate->query($sql);
		echo "<script type='text/javascript'> alert('Delete Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=".$_REQUEST["action_type"]."&parenttab=Settings');</script>";	
	
	}

?>
