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
		$sql="select * from aicrm_config_rpt2 where id='".$id."'";
		//echo $sql;
		$data = $generate->process($sql,"all");	
		if(count($data)>0){
			$_REQUEST["target_year"]=$data[0]['target_year'];
			$_REQUEST["call_in"]=$data[0]['call_in'];
			$_REQUEST["call_out"]=$data[0]['call_out'];
			$_REQUEST["walk"]=$data[0]['walk'];
			$_REQUEST["gross_booking"]=$data[0]['gross_booking'];
			$_REQUEST["booking_amount"]=$data[0]['booking_amount'];
			$_REQUEST["canceled"]=$data[0]['canceled'];
			$_REQUEST["canceled_amount"]=$data[0]['canceled_value'];
			$_REQUEST["net_booking"]=$data[0]['net_booking'];
			$_REQUEST["net_booking_amount"]=$data[0]['net_booking_amount'];
			$_REQUEST["resell"]=$data[0]['resell'];
		}
	}
	
	if($_REQUEST["myaction"]=="save"){
		if($_REQUEST["call_in"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Call In');</script>";	
		}else if($_REQUEST["call_out"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Call Out');</script>";	
		}else if($_REQUEST["walk"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Walk');</script>";	
		}else if($_REQUEST["gross_booking"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Gross Booking');</script>";
		}else if($_REQUEST["booking_amount"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Gross Booking Value (MB)');</script>";	
		}else if($_REQUEST["canceled"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Canceled');</script>";	
		}else if($_REQUEST["canceled_amount"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Canceled Value (MB)');</script>";	
		}else if($_REQUEST["net_booking"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Net Booking');</script>";	
		}else if($_REQUEST["net_booking_amount"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Gross Booking Value (MB)');</script>";	
		}else if($_REQUEST["resell"]==""){
			echo "<script>alert('กรุณาใส่ข้อมูล Resell');</script>";	
		}else{
			if($_REQUEST["action"]=="edit"){
				$sql="update aicrm_config_rpt2 set 
				target_year='".$_REQUEST["year"]."'
				,call_in='".$_REQUEST["call_in"]."'
				,call_out='".$_REQUEST["call_out"]."'
				,walk='".$_REQUEST["walk"]."'
				,gross_booking='".$_REQUEST["gross_booking"]."'
				,booking_amount='".$_REQUEST["booking_amount"]."'
				,net_booking='".$_REQUEST["net_booking"]."'
				,net_booking_amount='".$_REQUEST["net_booking_amount"]."'
				,modify_date='".date('Y-m-d H:i:s')."'
				,canceled='".$_REQUEST["canceled"]."'
				,canceled_value='".$_REQUEST["canceled_amount"]."'
				,resell='".$_REQUEST["resell"]."'
				where id='".$_REQUEST["id"]."'
				";
				//echo $sql;exit;
				$generate->query($sql);
				echo "<script type='text/javascript'> alert('Edit Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=".$_REQUEST["action_type"]."&parenttab=Settings');</script>";	

			}else{
				$sql="insert into aicrm_config_rpt2(`target_year`, `call_in`,  `call_out`, `walk`, `gross_booking`, `booking_amount`, `canceled` , `canceled_value` ,`net_booking`, `net_booking_amount`, `resell`,`deleted`, `create_date`, `modify_date`)
				values('".$_REQUEST["year"]."','".$_REQUEST["call_in"]."','".$_REQUEST["call_out"]."','".$_REQUEST["walk"]."','".$_REQUEST["gross_booking"]."' ,'".$_REQUEST["booking_amount"]."','".$_REQUEST["canceled"]."','".$_REQUEST["canceled_amount"]."','".$_REQUEST["net_booking"]."'
				,'".$_REQUEST["net_booking_amount"]."','".$_REQUEST["resell"]."', '0','".date('Y-m-d H:i:s')."' ,'".date('Y-m-d H:i:s')."') ";
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
<title>Config Targeted</title>
</head>
<script> 

function check_number(field) {
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
<form name="warranty" method="post" enctype="multipart/form-data" action="edit_config_targeted.php">
<input type="hidden" name="myaction" value="" />
<input type="hidden" name="action" value="<?=$_REQUEST["action"]?>" />
<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>" />
<input type="hidden" name="action_type" value="<?=$_REQUEST["action_type"]?>" />
<table width="100%" border="0" class="crmTable small" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="2" align="left" bgcolor="#999999" class="dvInnerHeader"><strong>Config Targeted</strong><font color="#000000"><strong></strong></font></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Year</td>
          <td align="left" class="dvtCellInfo"><label for="status"></label>
           <select name="year" id="year" class="small" style="width:30%">
              <?php $begin_year = 2016; 
					foreach (range($begin_year,date('Y')+5) as $x) {
						if(isset($_REQUEST["target_year"]) || $_REQUEST["target_year"] != ''){
							echo  '<option value="'.$x.'"'.($x == $_REQUEST["target_year"] ? ' selected="selected"' :  '' ).'>'.$x.'</option>';
						}else{
							echo  '<option value="'.$x.'"'.($x == date("Y") ? ' selected="selected"' :  '' ).'>'.$x.'</option>';
						}
					}		  
			  ?>
            </select>
           </td>
          <!--<input name="year" type="text" class="detailedViewTextBox" id ="year" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?//=$_REQUEST["year"]?>" /></td>-->
        </tr>
              
         <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Call in</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="call_in" type="text" class="detailedViewTextBox" id ="call_in" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["call_in"]?>" onchange="check_number('call_in');" /></td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Call out Register</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="call_out" type="text" class="detailedViewTextBox" id ="call_out" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["call_out"]?>" onchange="check_number('call_out');"/></td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Walk</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="walk" type="text" class="detailedViewTextBox" id ="walk" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["walk"]?>" onchange="check_number('walk');"/></td>
        </tr>
        
        <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Gross Booking</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="gross_booking" type="text" class="detailedViewTextBox" id ="gross_booking" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["gross_booking"]?>" onchange="check_number('gross_booking');"/></td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Gross Booking Value (MB)</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="booking_amount" type="text" class="detailedViewTextBox" id ="booking_amount" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["booking_amount"]?>" onchange="check_number('booking_amount');"/></td>
        </tr>
        
        <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Canceled</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="canceled" type="text" class="detailedViewTextBox" id ="canceled" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["canceled"]?>" onchange="check_number('canceled');"/></td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Canceled Value (MB)</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="canceled_amount" type="text" class="detailedViewTextBox" id ="canceled_amount" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["canceled_amount"]?>" onchange="check_number('canceled_amount');"/></td>
        </tr>
        
        <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Net Booking</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="net_booking" type="text" class="detailedViewTextBox" id ="net_booking" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["net_booking"]?>" onchange="check_number('net_booking');"/></td>
        </tr>
         <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Net Booking Value (MB)</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="net_booking_amount" type="text" class="detailedViewTextBox" id ="net_booking_amount" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["net_booking_amount"]?>" onchange="check_number('net_booking_amount');"/></td>
        </tr>
        
         <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Resell</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="resell" type="text" class="detailedViewTextBox" id ="resell" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["resell"]?>" onchange="check_number('resell');"/></td>
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