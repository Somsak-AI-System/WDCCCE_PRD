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
		$sql="select * from aicrm_monthly_plan where id='".$id."'";
		//echo $sql;
		$data = $generate->process($sql,"all");	
		if(count($data)>0){
			$_REQUEST["section"]=$data[0]['section'];
			$_REQUEST["date_send"]=$data[0]['date_send'];
			$_REQUEST["time_send"]=$data[0]['time_send'];
			$_REQUEST["send_to"]=$data[0]['send_to'];
		}
	}
	if($_REQUEST["myaction"]=="save"){
				
		if($_REQUEST["date_send"]==""){
			echo "<script>alert('กรุณาเลือก Date Send');</script>";	
		}else if($_REQUEST["time_send"]==""){
			echo "<script>alert('กรุณาเลือก Time Send');</script>";	
		}else if($_REQUEST["send_to"]==""){
			echo "<script>alert('กรุณาเลือก Send To');</script>";	
		}else{
					$send_to = implode(",", $_REQUEST["send_to"]);	
					$sql="update aicrm_monthly_plan set 
					date_send='".$_REQUEST["date_send"]."'
					,time_send='".$_REQUEST["time_send"]."'
					,send_to='".$send_to."'
					,last_update='".date('Y-m-d H:i:s')."'
					,user_update='".$_SESSION["authenticated_user_id"]."'
					where id='".$_REQUEST["id"]."'
					";
					$generate->query($sql);
				
					echo "<script type='text/javascript'> alert('Edit Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=".$_REQUEST["action_type"]."&parenttab=Settings');</script>";	
																																															 
			}
		}	
 
 $day_date=array();
  for($i = 1;$i <= 31; $i++ ){
 	if($i <= 9){
		$day_date[$i] = $i;
	}else{
		$day_date[$i] = $i;
	}
 }
 $day_date[32] = 99;
 
 $time_send = array();
 for($i = 0;$i < 24; $i++ ){
 	if($i <= 9){
		$time_send[$i] = '0'.$i.":00";
	}else{
		$time_send[$i] = $i.":00";
	}
 }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=tis-620" />
<link rel="stylesheet" type="text/css" href="../../themes/softed/style.css">
<title>Monthly Plan Config</title>

<script type="text/javascript" src="../../asset/js/jquery-1.12.4.js"></script>
<script type="text/javascript" src="../../asset/js/jquery.easyui.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../asset/css/metro-gray/easyui.css">

</head>
<script> 
$(document ).ready(function()  {
	Get_Sale_sendto();	  
});

function Get_Sale_sendto(){
	$('.send_to').combogrid({
			panelWidth:250,
			idField: 'id',
			textField: 'user_name',
			mode:'remote',
			value: '<?= $_REQUEST["send_to"] ?>',
			multiple: true,
			queryParams:{
				section:'<?= $_REQUEST["section"] ?>'
			  }, 
			fitColumns:true,                     
			url: 'get_user.php',
			columns: [[
			{field:'ck',checkbox:true},
			{field:'id',title:'ID',width:100, hidden:true},
			{field:'user_name',title:'Username',width:250, hidden:true},
			{field:'first_name',title:'Firstname',width:250, hidden:true},
			{field:'last_name',title:'Lastname',width:250, hidden:true},
			{field:'sale_name',title:'Salename',width:250},                            
			]]                                
	}); 
}
			
</script>

<body topmargin="0" leftmargin="0">
<form name="warranty" method="post" enctype="multipart/form-data" action="monthly_config.php">
<input type="hidden" name="myaction" value="" />
<input type="hidden" name="action" value="<?=$_REQUEST["action"]?>" />
<input type="hidden" name="id" value="<?=$_REQUEST["id"]?>" />
<input type="hidden" name="action_type" value="monthlyplantemplates" />
<table width="100%" border="0" class="crmTable small" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="2" align="left" bgcolor="#999999" class="dvInnerHeader"><strong>Monthly Plan Config</strong><font color="#000000"><strong></strong></font></td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel"><font color="#FF0000"></font>Section</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="section" type="text" class="detailedViewTextBox" id ="section" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["section"]?>" style="background-color:#999" readonly="readonly" /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Date Send</td>
          <td align="left" class="dvtCellInfo">
          <select name="date_send" id="date_send" class="small" style="width:100px;">
         
          	<?php foreach($day_date as $key =>$val){
            	echo "<option value='".$val."' ";
						if($val == $_REQUEST["date_send"]){
							echo "selected";
						}
				//echo	"/>".($val == '99') ? 'สิ้นเดือน' : $val."</option>";
				echo	"/>";
				if($val == 99){
					echo iconv('tis-620','utf-8',"สิ้นเดือน");
				}else{
					echo $val;
				}
				echo "</option>";
             } ?>             
          </select>
          </td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Time Send</td>
          <td align="left" class="dvtCellInfo">
          <select name="time_send" id="time_send" class="small" style="width:100px;">
          	<?php foreach($time_send as $key =>$val){
            	echo "<option value='".$val."' ";
						if($_REQUEST["time_send"] == $val){
							echo "selected";
						}
				echo	"/>".$val."</option>";
             } ?>   
          </select>
          <!--<input name="time_send" type="text" class="detailedViewTextBox" id ="time_send" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?//=$_REQUEST["time_send"]?>"  />-->
          </td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel"><font color="#FF0000">*</font>Send To</td>
          <td align="left" class="dvtCellInfo"><label for="status"></label>
          <input class="easyui-textbox send_to easyui-validatebox" id="send_to" name="send_to[]"  style="width:300px;" data-options="multiple:true"/>
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