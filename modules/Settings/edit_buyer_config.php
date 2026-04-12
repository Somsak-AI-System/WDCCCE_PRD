<?
	session_start();
	include("../../config.inc.php");
	include("../../library/dbconfig.php");
	include("../../library/myFunction.php");
	include("../../library/generate_MYSQL.php");
	$vowels = array("/",":","#","*","+"," ");

	global $generate,$current_user;
	$generate = new generate($dbconfig ,"DB");
	$mail_type="";
	
	if($_REQUEST["action"]=="edit" and $_REQUEST["id"]!="" and $_REQUEST["myaction"]!="save"){
		$id=$_REQUEST["id"];
		$sql="select * from aicrm_config_vendorbuyer where id='".$id."'";
		//echo $sql;
		$data = $generate->process($sql,"all");	
		if(count($data)>0){
			$_REQUEST["images"]=$data[0]['images'];
			$_REQUEST["name"]=$data[0]['name'];
			$_REQUEST["type"]=$data[0]['type'];
			$_REQUEST["prefix_po"]=$data[0]['prefix_po'];
			$_REQUEST["status"]=$data[0]['status'];
			$_REQUEST["address"]=$data[0]['address'];
			$_REQUEST["phone"]=$data[0]['phone'];
			$_REQUEST["email"]=$data[0]['email'];
			$_REQUEST["remark"]=$data[0]['remark'];
			$_REQUEST["show_in_module"]=$data[0]['show_in_module'];
		}
	}
	if($_REQUEST["myaction"]=="save"){
		if($_REQUEST["name"]==""){
			echo "<script>alert('');</script>";	
		}else if($_REQUEST["address"]==""){
			echo "<script>alert('');</script>";	
		}else if($_REQUEST["phone"]==""){
			echo "<script>alert('');</script>";	
		}else if($_REQUEST["email"]==""){
			echo "<script>alert('');</script>";	
		}else{
			
			if($_REQUEST["action"]=="edit"){

				$sql_images = '';
				if(isset($_FILES['images']['name'])){
					if(isset($_FILES['images']['name']) && $_FILES['images']['name'] != ''){
						$name = date("Ymdhis")."_".str_replace($vowels, "-",$_FILES['images']['name']); // Rename  
						$target_dir = "../../Image/image_vender_buyer/";
						$target_file = $target_dir . $name;
						$extension = strtolower(pathinfo($target_dir.$_FILES['images']['name'],PATHINFO_EXTENSION));
						$extensions_arr = array("png","jpg","jpeg");
						if( in_array($extension,$extensions_arr) ){
							if(copy($_FILES['images']['tmp_name'],$target_file)){
								$sql_images = ", images = '$name'";
							}
				
						}else{
							echo "<script type='text/javascript'> alert('นามสกุลไฟล์ไม่ถูกต้อง ต้องเป็นไฟล์นามสกุล .png .jpg .jpge เท่านั้น');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=".$_REQUEST["action_type"]."&parenttab=Settings');</script>";
						}
					}
				} 

				$sql="update aicrm_config_vendorbuyer set 
				name='".$_REQUEST["name"]."'
				,type='".$_REQUEST["type"]."'
				,prefix_po='".$_REQUEST["prefix_po"]."'
				,status='".$_REQUEST["status"]."'
				,address='".$_REQUEST["address"]."'
				,phone='".$_REQUEST["phone"]."'
				,email='".$_REQUEST["email"]."'
				,remark='".$_REQUEST["remark"]."'
				,show_in_module='".implode(", ",$_REQUEST["show_in_module"])."'
				$sql_images
				where id='".$_REQUEST["id"]."'
				";

				$generate->query($sql);
				echo "<script type='text/javascript'> alert('Edit Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=".$_REQUEST["action_type"]."&parenttab=Settings');</script>";	
			}else{

				$images = '';
				if(isset($_FILES['images']['name'])){
					$maxsize = 52428800; // 50MB
					if(isset($_FILES['images']['name']) && $_FILES['images']['name'] != ''){
						$name = $_FILES['images']['name']; // Rename
						$target_dir = "../../Image/image_vender_buyer/";
						$target_file = $target_dir . $name;
						$extension = strtolower(pathinfo($target_dir.$_FILES['images']['name'],PATHINFO_EXTENSION));
						$extensions_arr = array("png","jpg","jpeg");
						if( in_array($extension,$extensions_arr) ){
							if(copy($_FILES['images']['tmp_name'],$target_file)){
								$images = $name;
							}
				
						}else{
							echo "<script type='text/javascript'> alert('นามสกุลไฟล์ไม่ถูกต้อง ต้องเป็นไฟล์นามสกุล .png .jpg .jpge เท่านั้น');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=".$_REQUEST["action_type"]."&parenttab=Settings');</script>";
						}
					}
				} 

				$sql="insert into aicrm_config_vendorbuyer(`name`, `type`, `prefix_po`, `status`, `address`, `phone`, `email`, `remark`, images, show_in_module)
					values('".$_REQUEST["name"]."','".$_REQUEST["type"]."','".$_REQUEST["prefix_po"]."','".$_REQUEST["status"]."','".$_REQUEST["address"]."','".$_REQUEST["phone"]."','".$_REQUEST["email"]."','".$_REQUEST["remark"]."','".$images."','".implode(", ",$_REQUEST["show_in_module"])."') ";

					$generate->query($sql);
					echo "<script type='text/javascript'> alert('Add Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=".$_REQUEST["action_type"]."&parenttab=Settings');</script>";	
			}
			
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=tis-620" />
<link REL="SHORTCUT ICON" HREF="../../themes/AICRM.ico">
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
<form name="warranty" method="post" enctype="multipart/form-data" action="edit_buyer_config.php">
<input type="hidden" name="myaction" value="" />
<input type="hidden" name="action" value="<?=$_REQUEST["action"]?>" />
<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>" />
<input type="hidden" name="action_type" value="<?=$_REQUEST["action_type"]?>" />
<table width="100%" border="0" class="crmTable small" cellpadding="5" cellspacing="0">
		<tr>
          <td colspan="2" align="left" style="background-color:#BFBFBF" class="dvInnerHeader"><strong>Buyer Config</strong><font color="#000000"><strong></strong></font></td>
        </tr>
		<tr>
          <td colspan="2" align="left" style="background-color:#D9D9D9" class="dvInnerHeader"><strong>Buyer Information</strong><font color="#000000"><strong></strong></font></td>
        </tr>
		<tr>
          <td width="40%" align="left" class="dvtCellLabel">Header Images on Report <font color="#FF0000">*</font></td>
          <td width="60%" align="left" class="dvtCellInfo">
		    <?php if($_REQUEST["images"] != ''){?>
				<img src="../../Image/image_vender_buyer/<?php echo str_replace($vowels, "-",$_REQUEST["images"]);?>?v=<?php echo filemtime("../../Image/image_vender_buyer/".str_replace($vowels, "-",$_REQUEST["images"]));?>" height="100"><br><br>
				<input type="file" class="form-control" name="images" id="images"> <font color="#FF0000">*ขนาดที่เหมาะสม 930 x 80 Pixel</font>
			<?php }else{?>	
				 <input type="file" class="form-control" name="images" id="images"> <font color="#FF0000">*ขนาดที่เหมาะสม 930 x 80 Pixel</font>
			<?php }?>
			</td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel">Buyer Name <font color="#FF0000">*</font></td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="name" type="text" class="detailedViewTextBox" id ="name" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["name"]?>" /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">Type <font color="#FF0000">*</font></td>
          <td align="left" class="dvtCellInfo"><input name="type" type="text" class="detailedViewTextBox" id ="type" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="Buyer" readonly style="background-color: #CCC" /></td>
        </tr>
		
        <tr>
          <td align="left" class="dvtCellLabel">Status <font color="#FF0000">*</font></td>
          <td align="left" class="dvtCellInfo"><label for="status"></label>
            <select name="status" id="status" class="small">
              <option value="Active" <? if($_REQUEST["status"]=="Active"){ echo "selected";}?> >Active</option>
              <option value="Inactive"<? if($_REQUEST["status"]=="Inactive"){ echo "selected";}?> >Inactive</option>
            </select></td>
        </tr>

         <tr>
          <td align="left" class="dvtCellLabel">Address <font color="#FF0000">*</font></td>
          <td align="left" class="dvtCellInfo"><label for="address"></label>
            <textarea id="address" name="address" cols="5"><?=$_REQUEST["address"]?></textarea>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">Phone <font color="#FF0000">*</font></td>
          <td align="left" class="dvtCellInfo"><label for="address"></label>
            <input name="phone" type="text" class="detailedViewTextBox" id ="phone" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["phone"]?>" />
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">Email <font color="#FF0000">*</font></td>
          <td align="left" class="dvtCellInfo"><label for="email"></label>
            <input name="email" type="text" class="detailedViewTextBox" id ="email" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["email"]?>" />
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">Remark</td>
          <td align="left" class="dvtCellInfo"><label for="remark"></label>
            <textarea id="remark" name="remark" cols="5"><?=$_REQUEST["remark"]?></textarea>
        </tr>

		<tr>
          <td colspan="2" align="left" style="background-color:#D9D9D9" class="dvInnerHeader"><strong>Custom Field Information</strong><font color="#000000"><strong></strong></font></td>
        </tr>
		<tr>
          <td align="left" class="dvtCellLabel">Show or Hide this Picklist in Module</td>
          <td align="left" class="dvtCellInfo"><label for="payment_method"></label>
			<select id="show_in_module" name="show_in_module[]" multiple class="small">
				<?php
				$selectedModules = array();
				$selectedModulesString = $_REQUEST["show_in_module"];
				$selectedModules = explode(", ", $selectedModulesString);

				$options = array("Quotation", "Purchases Order", "Goods Receive");

				foreach ($options as $option) {
					$selected = in_array($option, $selectedModules) ? 'selected' : '';
					echo "<option value='$option' $selected>$option</option>";
				}
				?>
			</select>
          </td>
        </tr>
		<tr>
          <td align="left" class="dvtCellLabel">P/O Prefix <font color="#FF0000">*</font></td>
          <td align="left" class="dvtCellInfo"><label for="prefix_po"></label>
            <select name="prefix_po" id="prefix_po" class="small">
			  <option value="">--None--</option>	
              <option value="PO" <? if($_REQUEST["prefix_po"]=="PO"){ echo "selected";}?> >PO</option>
              <option value="NM"<? if($_REQUEST["prefix_po"]=="NM"){ echo "selected";}?> >NM</option>
			  <option value="OT"<? if($_REQUEST["prefix_po"]=="OT"){ echo "selected";}?> >OT</option>
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