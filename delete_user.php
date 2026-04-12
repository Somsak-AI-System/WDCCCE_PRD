<?php

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

require_once('include/utils/utils.php');

global $mod_strings, $app_strings;
global $theme;
$theme_path="themes/".$theme."/";

$delete_user_id = $_REQUEST['record'];
$delete_user_name = getUserName($delete_user_id);


$output='';
$output ='<div id="DeleteLay" class="layerPopup" style="width:350px;">
<form name="newProfileForm" action="index.php" onsubmit="VtigerJS_DialogBox.block();">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action" value="DeleteUser">
<input type="hidden" name="delete_user_id" value="'.$delete_user_id.'">	
<table border=0 cellspacing=0 cellpadding=5 width=100% class=layerHeadingULine>
<tr>
	<td class=layerPopupHeading " align="left">DELETE USER</td>
	<td align="right" class="small"></td>
</tr>
</table>
<table border=0 cellspacing=0 cellpadding=5 width=95% align=center> 
<tr>	
	<td class="small">
	<table border=0 celspacing=0 cellpadding=5 width=100% align=center bgcolor=white>
	<tr>
	
		<td width="25%" class="cellLabel small"><b>User to be Deleted</b></td>
		<td width="25%" class="cellText small"><b>'.$delete_user_name.'</b></td>
	</tr>
	<tr>
		<td align="left" class="cellLabel small" nowrap><b>Transfer Ownership to User</b></td>
		<td align="left" class="cellText small">';
           
		$output.='<select class="select" name="transfer_user_id" id="transfer_user_id">';
	     
		global $adb;	
         	$sql = "select * from aicrm_users";
	        $result = $adb->pquery($sql, array());
         	$temprow = $adb->fetch_array($result);
         	do
         	{
         		$user_name=$temprow["user_name"];
			$user_id=$temprow["id"];
		    	if($delete_user_id 	!= $user_id)
		    	{	 
            			$output.='<option value="'.$user_id.'">'.$user_name.'</option>';
		    	}	
         	}while($temprow = $adb->fetch_array($result));

		$output.='</td>
	</tr>
	
	</table>
	</td>
</tr>
</table>
<table border=0 cellspacing=0 cellpadding=5 width=100% class="layerPopupTransport">
<tr>
	<td align=center class="small"><input type="button" onclick="transferUser('.$delete_user_id.')" name="Delete" value="Save" class="small">
	</td>
</tr>
</table>
</form></div>';

$output.="<script>
function transferUser(del_userid)
{
	
      var paht = location.protocol + '//' + window.location.host + '/' + location.pathname.split('/')[1] + '/'+ location.pathname.split('/')[2];
	  var trans_userid = $('#transfer_user_id').val();
	  
	 //alert(paht+'?module=Users&action=UsersAjax&file=DeleteUser&ajax=true&delete_user_id='+del_userid+'&transfer_user_id='+trans_userid);
	 //window.close();
	
	 jQuery.messager.progress({
                	title:'Please wait...',
					text:'PROCESSING'
	  });

	  //window.open(paht+'?module=Administration&action=index&parenttab=Settings'); 
	  //location.reload();
	  
	 jQuery.ajax({
			 type: 'GET',
			 url: paht+'?module=Users&action=UsersAjax&file=DeleteUser&ajax=true&delete_user_id='+del_userid+'&transfer_user_id='+trans_userid,
			 cache: false,
			 dataType: 'json',
			success: function(returndate){
				if(returndate.status == 'true'){
					//jQuery.messager.alert('Info', 'Delete User Success');
					//jQuery.messager.progress('close');
					//location.reload();
					
					jQuery.messager.progress('close');
					
					jQuery.messager.alert({
						title: 'Information',
						msg: 'Delete User Success',
						fn: function(){
							location.reload();
						}
					});
				}else{
					jQuery.messager.alert('Info', 'Try Again Delete User');
					jQuery.messager.progress('close');	
				}
			}//success
		});//ajax
	  
	  
	  
}
</script>";

echo $output;
?>
