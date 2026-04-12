<?
	session_start();
	include("../../config.inc.php");
	include("../../library/dbconfig.php");
	include("../../library/myFunction.php");
	include("../../library/generate_MYSQL.php");
	//include("library/function.php");
	global $generate,$current_user;
	$generate = new generate($dbconfig ,"DB");
	if($_REQUEST["action"]=="edit" and $_REQUEST["id"]!="" and $_REQUEST["myaction"]!="save"){
		$id=$_REQUEST["id"];
		$sql="select * from aicrm_config_sender_sms where id='".$id."'";
		//echo $sql;
		$data = $generate->process($sql,"all");	
		if(count($data)>0){
			$_REQUEST["sms_sender"]=$data[0]['sms_sender'];
			$_REQUEST["sms_status"]=$data[0]['sms_status'];
			$_REQUEST["sms_url"]=$data[0]['sms_url'];
			$_REQUEST["sms_username"]=$data[0]['sms_username'];
			$_REQUEST["sms_password"]=$data[0]['sms_password'];
		}
	}
	if($_REQUEST["myaction"]=="save"){
		if($_REQUEST["sms_sender"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Sender Name');</script>";	
		}else if($_REQUEST["sms_status"]=="" || $_REQUEST["sms_status"]=="--None--"){
			echo "<script>alert('กรุณาเลือกข้อมูล Status');</script>";	
		}else if($_REQUEST["sms_url"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Url');</script>";	
		}else if($_REQUEST["sms_username"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล User Name');</script>";
		}else if($_REQUEST["sms_password"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Password');</script>";	
		}else{
			if($_REQUEST["action"]=="edit"){
				$sql="update aicrm_config_sender_sms set 
				sms_sender='".$_REQUEST["sms_sender"]."'
				,sms_status='".$_REQUEST["sms_status"]."'
				,sms_url='".$_REQUEST["sms_url"]."'
				,sms_username='".$_REQUEST["sms_username"]."'
				,sms_password='".$_REQUEST["sms_password"]."'
				where id='".$_REQUEST["id"]."'
				";
				//echo $sql;exit;
				$generate->query($sql);
				echo "<script type='text/javascript'> alert('Edit Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=".$_REQUEST["action_type"]."&parenttab=Settings');</script>";	

			}else{
				$mail_type="";
				if($_REQUEST["action_type"]=="list_account_send_email"){
					$mail_type="account";
				}else if($_REQUEST["action_type"]=="list_bounce_email"){
					$mail_type="bounce";
				}else if($_REQUEST["action_type"]=="list_reply_email"){
					$mail_type="reply";
				}else if($_REQUEST["action_type"]=="list_server_email"){
					$mail_type="edm";
				}
				$sql="insert into aicrm_config_sender_sms(`sms_sender`, `sms_status`,  `sms_url`, `sms_username`, `sms_password`)
				values('".$_REQUEST["sms_sender"]."','".$_REQUEST["sms_status"]."','".$_REQUEST["sms_url"]."','".$_REQUEST["sms_username"]."','".$_REQUEST["sms_password"]."') ";
				//echo $sql;exit;
				$generate->query($sql);
				echo "<script type='text/javascript'> alert('Add Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=".$_REQUEST["action_type"]."&parenttab=Settings');</script>";	
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../themes/softed/style.css">
<title>Edit SMS Config</title>
</head>
<script> 
function check_number(field) {
	//alert(field);
	var len, digit , chk;
	if(document.getElementById(field).value == " "){
		alert("Pleate Input Number Only!..");
		len=0;
	}else{
		len = document.getElementById(field).value.length;
	}
	for(var i=0 ; i<len ; i++){
		digit = document.getElementById(field).value.charAt(i)
		//alert(digit);
		if((digit >="0" && digit <="9") || digit=="." ){
		}else{
			chk='1';
		}
	}
	if(chk=="1"){
		alert("Pleate Input Number Only!..");
		document.getElementById(field).value="0";
	}	
}
function checkEmail(field) { 
	var emailFilter=/^.+@.+\..{2,3}$/;
	var str=document.getElementById(field).value;
	if (!(emailFilter.test(str))) { 
		alert ("Please enter a valid E-mail");
		document.getElementById(field).value="";
		return false;
	}
	return true;
}				
</script>

<body topmargin="0" leftmargin="0">
<form name="warranty" method="post" enctype="multipart/form-data" action="edit_sender_sms.php">
<input type="hidden" name="myaction" value="" />
<input type="hidden" name="action" value="<?=$_REQUEST["action"]?>" />
<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>" />
<input type="hidden" name="action_type" value="<?=$_REQUEST["action_type"]?>" />
<table width="100%" border="0" class="crmTable small" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="2" align="left" bgcolor="#999999" class="dvInnerHeader"><strong>SMS Config</strong><font color="#000000"><strong></strong></font></td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Sender Name</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="sms_sender" type="text" class="detailedViewTextBox" id ="sms_sender" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["sms_sender"]?>" /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Status</td>
          <td align="left" class="dvtCellInfo"><label for="status"></label>
            <select name="sms_status" id="sms_status" class="small">
              <option value="">-- None --</option>
              <option value="Active" <? if($_REQUEST["sms_status"]=="Active"){ echo "selected";}?> >Active</option>
              <option value="InActive"<? if($_REQUEST["sms_status"]=="InActive"){ echo "selected";}?> >InActive</option>
            </select></td>
        </tr>
         <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Url</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="sms_url" type="text" class="detailedViewTextBox" id ="sms_url" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["sms_url"]?>" /></td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>User Name</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="sms_username" type="text" class="detailedViewTextBox" id ="sms_username" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["sms_username"]?>" /></td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Password</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="sms_password" type="password" class="detailedViewTextBox" id ="sms_password" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["sms_password"]?>" /></td>
        </tr>
        <tr>
          <td colspan="2" align="center" class="lvtCol"><input title="Save" accesskey="S" class="crmbutton small save" onclick="this.form.myaction.value='save'; " type="submit" name="button" value="Save" style="width:80px;cursor: pointer;" />
            
            &nbsp;
            <input title="Cancel" accesskey="C" class="crmbutton small cancel" onclick="window.close();" type="submit" name="button3" value="Cancel" style="width:80px;cursor: pointer;" />
            </td>
        </tr>
      </table>
</form>      
</body>
</html>