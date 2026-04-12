<?php
	session_start();
	include("config.inc.php");
	include_once("library/dbconfig.php");
	include_once("library/myFunction.php");
	include_once("library/generate_MYSQL.php");
	$crmid=$_REQUEST["crmid"];

?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<div data-options="region:'center',iconCls:'icon-ok'" style="width: 100%;" title="service"  id="divplan">
	<form method="POST" name="EditView" id="EditView" ENCTYPE="multipart/form-data">
	<table width="100%" cellpadding="5" cellspacing="0" border="0"  class="homePageMatrixHdr">
		<tr>
			<td style="padding:0px;" >
				
	                <input type="hidden" name="crmid" id="crmid" class="txtBox"  style="width:210px" value="<?php echo $_REQUEST["crmid"]?>">
	                <tr>
					  <td width="10%" class="small">ตั้งเวลาส่ง</td>
					  <td width="30%" class="">
					  	<input type="radio" name="chk" id="chk1" checked value="1" style="vertical-align: top;">ทันที
					  	<!-- <b class="project_address" style="color: red;display: none;">Please enter information</b> -->
					  </td>
					</tr>
					<tr>
					  <td width="10%" class="small"></td>
					  <td width="30%" class="">
					  	<input type="radio" name="chk" id="chk2" value="2" style="vertical-align: top;">ตั้งวันที่ทำรายการ
					  	
					  	<input name="senddate" id="senddate" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" readonly value="<?php echo date("d-m-Y"); ?>" class="user-success">
					  	<img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_date" style="vertical-align: middle;">
					  	
					  	<script type="text/javascript">
                        	Calendar.setup ({inputField : "senddate", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_date", singleClick : true, step : 1 })
                    	</script>
                    	
                    	<input id="sendtime" name="sendtime" style="width:80px;" value="<?php echo date("H:i",strtotime(date("H:i")." +30 minutes"));?>" autocomplete="on" class="easyui-timespinner" data-options="showSeconds:false,editable:true"><!-- strtotime("+30 minutes") -->
                    	
                    	<br><b class="senddate" style="color: red;display: none;">Please select date</b>
	                    
	                    <b class="sendtime" style="color: red;display: none;">Please select time</b>
                    	
					  </td>
					  
					</tr>
					
					<style type="text/css">
						#senddate{
							border-radius: 3px;
						    color: #2b2f33;
						    background-color: #FFF;
						    padding: 2px;
						    line-height: 20px;
						}
					</style>
					
				

			</td>
			<tr>
			  
			  <td class=""></td>
			  <td class="">
			  	<button title="Save" class="crmbutton small save"type="button" name="button" value="  Save  " style="width:70px"> 
        			<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 15px; vertical-align: middle;">&nbsp;Save
        		</button>
        		<button title="Cancel" class="crmbutton small cancel" type="button" name="button" value="Cancel">
            		<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px;height: 17px; vertical-align: middle;">  Cancel
            	</button>
			  </td>
			</tr>
		</tr>
	</table>
	</form>
</div>

<script>
	
jQuery(function(){

	jQuery('.save').click(function(event) {
	
		var flag = true ;
		var radios = document.getElementsByName('chk');

		for (var i = 0, length = radios.length; i < length; i++) {
		  if (radios[i].checked) {
		    radioValue = radios[i].value;
		  }
		}
		jQuery(".senddate").css("display", "none");
		jQuery(".sendtime").css("display", "none");
		if(radioValue == 1){
			

		}else if(radioValue == 2){
			var datesend = document.getElementById('senddate').value;
			var sendtime = document.getElementById('sendtime').value;
			
			if(sendtime == '' ){
				var flag = false ;
				jQuery(".sendtime").css("display", "block");
			}

		}else{
			location.reload();
			jQuery('#dialog').window('close');
		}

		if(flag == false){return false;}
		event.preventDefault();
		
		//console.log('submit');
		jQuery("#EditView").submit();
		
	});

	jQuery('#EditView').form({

		url:'plugin/Announcement/setup.php',
		onSubmit:function(){
			
		},
		success:function(data){
		
			var obj = jQuery.parseJSON(data);
			if(obj.status==true){
				var errMsg =  obj.msg + " " +obj.error ;
			}else{
				var errMsg =  obj.msg +" error: "+ obj.error;
			}
			jQuery.messager.alert('Info', errMsg, 'info', function(){
			if(obj.status==true){
				if(obj.url != '')
				{
					location.reload();
					jQuery('#dialog').window('close');	
				}
			}
			else{
					console.log(obj);
			}
			});
		}
	});

	jQuery(".cancel").click(function(){
		//location.reload();
		jQuery('#dialog').window('close');

	});

});

</script>