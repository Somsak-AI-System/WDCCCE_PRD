<?
	session_start();
	include("../../config.inc.php");
	include("../../library/dbconfig.php");
	include("../../library/myFunction.php");
	include("../../library/generate_MYSQL.php");
	//include("library/function.php");
	global $generate,$current_user;
	$generate = new generate($dbconfig ,"DB");
	if($_REQUEST["action"]=="edit" and $_REQUEST["cf_2249id"]!="" and $_REQUEST["myaction"]!="save"){
		$cf_2249id=$_REQUEST["cf_2249id"];
		$sql="
		select cf_2249id,cf_2249,aicrm_cf_2249.branch_id,branch_name
		FROM aicrm_cf_2249 
		left join aicrm_branchs on aicrm_branchs.branchid=aicrm_cf_2249.branch_id
		where cf_2249id='".$cf_2249id."'";
		//echo $sql;
		$data = $generate->process($sql,"all");	
		if(count($data)>0){
			$_REQUEST["cf_2249"]=$data[0]['cf_2249'];
			$_REQUEST["branch_id"]=$data[0]['branch_id'];
			$_REQUEST["branch_name"]=$data[0]['branch_name'];
		}
	}
	if($_REQUEST["myaction"]=="save"){
		if($_REQUEST["action"]=="edit"){
			$sql="update aicrm_cf_2249 set 
			branch_id='".$_REQUEST["branch_id"]."'
			where cf_2249id='".$_REQUEST["cf_2249id"]."'
			";
			//echo $sql;exit;
			$generate->query($sql);
			echo "<script type='text/javascript'> alert('Edit Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=list_case_cat&parenttab=Settings');</script>";	
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../themes/softed/style.css">
<title>Case Catgory Setting</title>
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
<form name="warranty" method="post" enctype="multipart/form-data" action="edit_case_cat.php">
<input type="hidden" name="myaction" value="" />
<input type="hidden" name="action" value="<?=$_REQUEST["action"]?>" />
<input type="hidden" name="cf_2249id" value="<?=$_REQUEST["cf_2249id"]?>" />
<table width="100%" border="0" class="crmTable small" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="2" align="left" bgcolor="#999999" class="dvInnerHeader"><strong>Case Catgory</strong><font color="#000000"><strong></strong></font></td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel">Case Catgory</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="cf_2249" type="text" class="detailedViewTextBox" id ="cf_2249" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["cf_2249"]?>" readonly="readonly" style="background-color:#CCC"   /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">Branch Name</td>
          <td align="left" class="dvtCellInfo">
          <?
		  $sql="
		  	select
			aicrm_branchs.branchid
			,aicrm_branchs.branch_name
			FROM aicrm_branchs
			INNER JOIN aicrm_branchscf ON aicrm_branchscf.branchid = aicrm_branchs.branchid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_branchs.branchid
			LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
			LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
			WHERE aicrm_crmentity.deleted = 0
			and aicrm_branchscf.cf_2280='Dummy'
		  ";
		  $data_pic = $generate->process($sql,"all");
		  ?>
            <select name="branch_id" id="branch_id"  class="small" >
            <option value=""></option>
            <?php
            for($k=0;$k<count($data_pic);$k++){
            $a = 0;
            ?>
            <option value="<?=$data_pic[$k]["branchid"]?>" <?php if($data_pic[$k]["branchid"]==$_REQUEST["branch_id"]){ echo "selected";}?>><?=$data_pic[$k]["branch_name"]?></option>
            <?
            }
            ?>
            </select>
          </td>
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