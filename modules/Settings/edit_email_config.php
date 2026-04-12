<?
	session_start();
	include("../../config.inc.php");
	include("../../library/dbconfig.php");
	include("../../library/myFunction.php");
	include("../../library/generate_MYSQL.php");
	//include("library/function.php");
	global $generate,$current_user;
	$generate = new generate($dbconfig ,"DB");
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
	if($_REQUEST["action"]=="edit" and $_REQUEST["id"]!="" and $_REQUEST["myaction"]!="save"){
		$id=$_REQUEST["id"];
		$sql="select * from aicrm_config_sendemail where id='".$id."'";
		//echo $sql;
		$data = $generate->process($sql,"all");	
		if(count($data)>0){
			$_REQUEST["email_server"]=$data[0]['email_server'];
			$_REQUEST["email_from_name"]=$data[0]['email_from_name'];
			$_REQUEST["email_port"]=$data[0]['email_port'];
			$_REQUEST["email_from_name"]=$data[0]['email_from_name'];
			$_REQUEST["email_user"]=$data[0]['email_user'];
			$_REQUEST["email_pass"]=$data[0]['email_pass'];
			$_REQUEST["email_status"]=$data[0]['email_status'];
		}
	}
	if($_REQUEST["myaction"]=="save"){
		if($_REQUEST["email_server"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Email Server');</script>";	
		}else if($_REQUEST["email_port"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Port');</script>";	
		}else if($_REQUEST["email_from_name"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Email Name');</script>";	
		}else if($_REQUEST["email_user"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Email');</script>";	
		}else if($_REQUEST["email_pass"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Password');</script>";	
		}else if($_REQUEST["email_status"]==""){
			echo "<script>alert('กรุณาเลือกข้อมูล Status');</script>";	
		}else{
			if($mail_type=="bounce" || $mail_type=="edm"){
				if($_REQUEST["action"]=="edit"){
					$sql="select * from aicrm_config_sendemail where 1 and deleted=0 and email_user !='".$_REQUEST["email_user"]."'
					and  email_type ='".$mail_type."'
					and email_status='Active'
					";
					//echo $sql;exit;
					$data = $generate->process($sql,"all");	
					if(count($data)>0 and $_REQUEST["email_status"]=="Active"){
						echo "<script type='text/javascript'> alert('Email information with the status active in the system.');</script>";	
					}else{
						$sql="update aicrm_config_sendemail set 
						email_port='".$_REQUEST["email_port"]."'
						,email_server='".$_REQUEST["email_server"]."'
						,email_from_name='".$_REQUEST["email_from_name"]."'
						,email_user='".$_REQUEST["email_user"]."'
						,email_pass='".$_REQUEST["email_pass"]."'
						,email_status='".$_REQUEST["email_status"]."'
						where id='".$_REQUEST["id"]."'
						";
						//echo $sql;exit;
						$generate->query($sql);
						echo "<script type='text/javascript'> alert('Edit Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=".$_REQUEST["action_type"]."&parenttab=Settings');</script>";	
					}
	
				}else{
					$sql="select * from aicrm_config_sendemail where 1 and deleted=0 and email_user !='".$_REQUEST["email_user"]."'
					and  email_type ='".$mail_type."'
					and email_status='Active'
					";
					//echo $sql;exit;
					$data = $generate->process($sql,"all");	
					if(count($data)>0 and $_REQUEST["email_status"]=="Active"){
						echo "<script type='text/javascript'> alert('Email information with the status active in the system.');</script>";	
					}else{
						$sql="insert into aicrm_config_sendemail(`email_from_name`, `email_user`, `email_pass`, `email_server`, `email_port`, `email_type`, `email_status`)
						values('".$_REQUEST["email_from_name"]."','".$_REQUEST["email_user"]."','".$_REQUEST["email_pass"]."','".$_REQUEST["email_server"]."','".$_REQUEST["email_port"]."','".$mail_type."','".$_REQUEST["email_status"]."') ";
						//echo $sql;exit;
						$generate->query($sql);
						echo "<script type='text/javascript'> alert('Add Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=".$_REQUEST["action_type"]."&parenttab=Settings');</script>";	
					}
				}
			}else{
				if($_REQUEST["action"]=="edit"){
					$sql="update aicrm_config_sendemail set 
					email_port='".$_REQUEST["email_port"]."'
					,email_server='".$_REQUEST["email_server"]."'
					,email_from_name='".$_REQUEST["email_from_name"]."'
					,email_user='".$_REQUEST["email_user"]."'
					,email_pass='".$_REQUEST["email_pass"]."'
					,email_status='".$_REQUEST["email_status"]."'
					where id='".$_REQUEST["id"]."'
					";
					//echo $sql;exit;
					$generate->query($sql);
					echo "<script type='text/javascript'> alert('Edit Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=".$_REQUEST["action_type"]."&parenttab=Settings');</script>";	
				}else{
					$sql="insert into aicrm_config_sendemail(`email_from_name`, `email_user`, `email_pass`, `email_server`, `email_port`, `email_type`, `email_status`)
						values('".$_REQUEST["email_from_name"]."','".$_REQUEST["email_user"]."','".$_REQUEST["email_pass"]."','".$_REQUEST["email_server"]."','".$_REQUEST["email_port"]."','".$mail_type."','".$_REQUEST["email_status"]."') ";
						//echo $sql;exit;
						$generate->query($sql);
						echo "<script type='text/javascript'> alert('Add Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=".$_REQUEST["action_type"]."&parenttab=Settings');</script>";	
				}
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=tis-620" />
<link rel="stylesheet" type="text/css" href="../../themes/softed/style.css">
<title>Edit Email Config</title>
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
<form name="warranty" method="post" enctype="multipart/form-data" action="edit_email_config.php">
<input type="hidden" name="myaction" value="" />
<input type="hidden" name="action" value="<?=$_REQUEST["action"]?>" />
<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>" />
<input type="hidden" name="action_type" value="<?=$_REQUEST["action_type"]?>" />
<table width="100%" border="0" class="crmTable small" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="2" align="left" bgcolor="#999999" class="dvInnerHeader"><strong>Email Config</strong><font color="#000000"><strong></strong></font></td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Mail Server</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="email_server" type="text" class="detailedViewTextBox" id ="email_server" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["email_server"]?>" /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Port</td>
          <td align="left" class="dvtCellInfo"><input name="email_port" type="text" class="detailedViewTextBox" id ="email_port" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["email_port"]?>"  /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Email Name</td>
          <td align="left" class="dvtCellInfo"><input name="email_from_name" type="text" class="detailedViewTextBox" id ="email_from_name" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["email_from_name"]?>"  /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Email</td>
          <td align="left" class="dvtCellInfo"><input name="email_user" type="text" class="detailedViewTextBox" id ="email_user" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox';checkEmail('email_user')" value="<?=$_REQUEST["email_user"]?>"   /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Password</td>
          <td align="left" class="dvtCellInfo"><input name="email_pass" type="password" class="detailedViewTextBox" id ="email_pass" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["email_pass"]?>"  /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Status</td>
          <td align="left" class="dvtCellInfo"><label for="status"></label>
            <select name="email_status" id="email_status" class="small">
              <option value="">-- None --</option>
              <option value="Active" <? if($_REQUEST["email_status"]=="Active"){ echo "selected";}?> >Active</option>
              <option value="InActive"<? if($_REQUEST["email_status"]=="InActive"){ echo "selected";}?> >InActive</option>
            </select></td>
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