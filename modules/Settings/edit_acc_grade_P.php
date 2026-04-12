<?
	session_start();
	include("../../config.inc.php");
	include("../../library/dbconfig.php");
	include("../../library/myFunction.php");
	include("../../library/generate_MYSQL.php");
	//include("library/function.php");
	global $generate,$current_user;
	$generate = new generate($dbconfig ,"DB");
	if($_REQUEST["action"]=="edit" and $_REQUEST["cf_1599id"]!="" and $_REQUEST["myaction"]!="save"){
		$cf_1599id=$_REQUEST["cf_1599id"];
		$sql="select * from aicrm_cf_1599 where cf_1599id='".$cf_1599id."'";
		//echo $sql;
		$data = $generate->process($sql,"all");	
		if(count($data)>0){
			$_REQUEST["cf_1599"]=$data[0]['cf_1599'];
			$_REQUEST["chk_st"]=$data[0]['chk_st'];
			$_REQUEST["chk_en"]=$data[0]['chk_en'];
			$_REQUEST["chk_count_from"]=$data[0]['chk_count_from'];
			$_REQUEST["chk_count_to"]=$data[0]['chk_count_to'];
		}
	}
	if($_REQUEST["myaction"]=="save"){
		if($_REQUEST["action"]=="edit"){
			$sql="update aicrm_cf_1599 set 
			chk_st='".$_REQUEST["chk_st"]."'
			,chk_en='".$_REQUEST["chk_en"]."'
			,chk_count_from='".$_REQUEST["chk_count_from"]."'
			,chk_count_to='".$_REQUEST["chk_count_to"]."'
			where cf_1599id='".$_REQUEST["cf_1599id"]."'
			";
			//echo $sql;exit;
			$generate->query($sql);
			echo "<script type='text/javascript'> alert('Edit Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=list_acc_grade_P&parenttab=Settings');</script>";	
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=tis-620" />
<link rel="stylesheet" type="text/css" href="../../themes/softed/style.css">
<title>Toll Way Setting</title>
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
<form name="warranty" method="post" enctype="multipart/form-data" action="edit_acc_grade_P.php">
<input type="hidden" name="myaction" value="" />
<input type="hidden" name="action" value="<?=$_REQUEST["action"]?>" />
<input type="hidden" name="cf_1599id" value="<?=$_REQUEST["cf_1599id"]?>" />
<table width="100%" border="0" class="crmTable small" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="2" align="left" bgcolor="#999999" class="dvInnerHeader"><strong>Account Grade Per Period</strong><font color="#000000"><strong></strong></font></td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel">Account Grade</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="cf_1599" type="text" class="detailedViewTextBox" id ="cf_1599" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["cf_1599"]?>" readonly="readonly" style="background-color:#CCC"   /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">Summary From</td>
          <td align="left" class="dvtCellInfo"><input name="chk_st" type="text" class="detailedViewTextBox" id ="chk_st" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["chk_st"]?>" onchange="check_number('chk_st');"   /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">Summary To</td>
          <td align="left" class="dvtCellInfo"><input name="chk_en" type="text" class="detailedViewTextBox" id ="chk_en" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["chk_en"]?>" onchange="check_number('chk_en');"   /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">Count From</td>
          <td align="left" class="dvtCellInfo"><input name="chk_count_from" type="text" class="detailedViewTextBox" id ="chk_count_from" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["chk_count_from"]?>" onchange="check_number('chk_count_from');"   /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">Count To</td>
          <td align="left" class="dvtCellInfo"><input name="chk_count_to" type="text" class="detailedViewTextBox" id ="chk_count_to" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["chk_count_to"]?>" onchange="check_number('chk_count_to');"   /></td>
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