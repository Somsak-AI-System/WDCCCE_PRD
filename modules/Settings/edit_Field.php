<?
	session_start();
	include("../../config.inc.php");
	include("../../library/dbconfig.php");
	include("../../library/myFunction.php");
	include("../../library/generate_MYSQL.php");
	//include("library/function.php");
	global $generate,$current_user;
	$generate = new generate($dbconfig ,"DB");
	if($_REQUEST["action"]=="edit" and $_REQUEST["field_id"]!="" and $_REQUEST["myaction"]!="save"){
		$field_id=$_REQUEST["field_id"];
		$sql="select * from aicrm_field where fieldid='".$field_id."'";
		//echo $sql;
		$data = $generate->process($sql,"all");	
		if(count($data)>0){
			$_REQUEST["tbx_fieldlabel"]=$data[0]['fieldlabel'];
			$_REQUEST["tbx_tabid"]=$data[0]['tabid'];
		}
	}
	if($_REQUEST["myaction"]=="save"){
		if($_REQUEST["action"]=="edit"){
			$field_id=$_REQUEST["field_id"];
			$tabid=$_REQUEST["tbx_tabid"];
			$sql="select * from aicrm_field 
			where fieldid != '".$field_id."'
			and  tabid='".$tabid."'
			and fieldlabel='".$_REQUEST["tbx_fieldlabel"]."'
			";
			//echo $sql;exit;
			$data = $generate->process($sql,"all");	
			if(count($data)>0){
				echo "<script type='text/javascript'> alert('Label already exists. Please specify a different Label');</script>";	
			}else{
				$sql="update aicrm_field set 
				fieldlabel='".$_REQUEST["tbx_fieldlabel"]."'
				where fieldid='".$field_id."'
				and  tabid='".$tabid."'
				";
				//echo $sql;exit;
				$generate->query($sql);
				echo "<script type='text/javascript'> alert('Edit Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=list_Field&parenttab=Settings');</script>";	
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../themes/softed/style.css">
<title>Edit Label</title>
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
</script>

<body topmargin="0" leftmargin="0">
<form name="warranty" method="post" enctype="multipart/form-data" action="edit_Field.php">
<input type="hidden" name="myaction" value="" />
<input type="hidden" name="action" value="<?=$_REQUEST["action"]?>" />
<input type="hidden" name="field_id" value="<?=$_REQUEST["field_id"]?>" />
<input type="hidden" name="tbx_tabid" value="<?=$_REQUEST["tbx_tabid"]?>" />
<table width="100%" border="0" class="crmTable small" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="2" align="left" bgcolor="#999999" class="dvInnerHeader"><strong>Modules Layout Editor</strong></td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel">Label Name</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="tbx_fieldlabel" type="text" class="detailedViewTextBox" id ="tbx_fieldlabel" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["tbx_fieldlabel"]?>"    /></td>
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