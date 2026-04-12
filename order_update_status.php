<?php
	session_start();
	include("config.inc.php");
	include_once("library/dbconfig.php");
	include_once("library/myFunction.php");
	include_once("library/generate_MYSQL.php");
	global $generate;
	$generate = new generate($dbconfig ,"DB");
	$date=date('Y-m-d');
	$smownerid=$_SESSION["authenticated_user_id"];
	
	$crmid=$_REQUEST["crmid"];
	$status=$_REQUEST["status"];

	$sql="
	SELECT aicrm_order.* FROM aicrm_order where aicrm_order.orderid = '".$crmid."' ";
	$data =$generate->process($sql,"all");

	$mix_easy_site_code = $data[0]['mix_easy_site_code'];
	$payment_method = $data[0]['payment_method'];
	$receive_money = $data[0]['receive_money'];
	$site_phone_delivery = $data[0]['site_phone_delivery'];
	$plan_date = $data[0]['plan_date'];
	$plan_time = $data[0]['plan_time'];
	$vendor_site_code = $data[0]['vendor_site_code'];
	$vender_plant = $data[0]['vender_plant'];
	
	$payment_method_sql = 'select * from aicrm_payment_method where presence = 1 order by payment_method_id';
	$payment_method_select =$generate->process($payment_method_sql,"all");


?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<div class="mailClient mailClientBg">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	 	<td class="moduleName" width="80%" style="padding-left:10px;">Update Status</td>
		<td  width=30% nowrap class="componentName" align=right>MOAI</td>
	</tr>
</table>
</div>

<div data-options="region:'center',iconCls:'icon-ok'" style="width: 530px;" title="service"  id="divplan">
			<table width="100%" cellpadding="5" cellspacing="0" border="0"  class="homePageMatrixHdr">
				<tr>
					<td style="padding:0px;" >
					<form method="POST" name="EditView" id="EditView" ENCTYPE="multipart/form-data">
                        <input type="hidden" name="myaction" value="" />
                        <input type="hidden" name="status" id="orderstatus" value="<?php echo $_REQUEST["status"];?>" />
                        <input type="hidden" name="crmid" id="crmid" class="txtBox"  style="width:210px" value="<?php echo $_REQUEST["crmid"]?>">
                        
						<table width="100%" cellpadding="5" cellspacing="0">
						
						<?php if($status == 'Wait Vendor'){ ?>
							<tr>
							  <td width="20%" class="dvtCellLabel small">Plan date<span style="color: red">*</span></td>
							  <td width="30%" class="dvtCellInfo">
							  	<input name="plan_date" tabindex="" id="plan_date" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="<?php echo date("d-m-Y",strtotime($plan_date)); ?>" class="user-success">
                				<img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_plan_date" style="vertical-align: middle;">
                				<b class="plan_date" style="color: red;display: none;">Please select</b>
							  </td>
							  	<script type="text/javascript" id="massedit_calendar_plan_date">
	                            Calendar.setup ({inputField : "plan_date", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_plan_date", singleClick : true, step : 1})
	                        	</script>
							</tr>

							<tr>
							  <td width="20%" class="dvtCellLabel small">Plan Time<span style="color: red">*</span></td>
							  <td width="30%" class="dvtCellInfo">
							  	<input id="plan_time" name="plan_time" style="width:80px;" value="<?php echo $plan_time;?>" autocomplete="on" class="easyui-timespinner" data-options="showSeconds:false,editable:true">
							  	<b class="plan_time" style="color: red;display: none;">Please select</b>
							  </td>
							</tr>
						
						<?php }else if($status == 'Wait Confirm'){ ?>
							<tr>
							  <td width="20%" class="dvtCellLabel small">Mix Easy Site code<span style="color: red">*</span></td>
							  <td width="30%" class="dvtCellInfo">
							  	<input type="text" name="mix_easy_site_code" id="mix_easy_site_code" value="<?php echo $mix_easy_site_code ;?>">
							  	<b class="mix_easy_site_code" style="color: red;display: none;">Please enter information</b>
							  </td>
							</tr>
						
						<?php }else if($status == 'Customer Payment'){ ?>
							<tr>
							  <td width="20%" class="dvtCellLabel small">Attach file<span style="color: red">*</span></td>
							  <td width="30%" class="dvtCellInfo">
							  	<input type="file" name="file_0" id="file_0" value="">
							  	<b class="file_0" style="color: red;display: none;">Please Attach file</b>
							  </td>
							</tr>
							<tr>
							  <td width="20%" class="dvtCellLabel small">Payment Method<span style="color: red">*</span></td>
							  <td width="30%" class="dvtCellInfo">
							  	<select name="payment_method" id="payment_method" tabindex="" class="small user-success">

						  		<?php
							  		foreach ($payment_method_select as $key => $value) {
							  			$selected = '';
							  			if($payment_method == $value['payment_method']){
							  				$selected = 'selected';
							  				//".($value['payment_method'] == $payment_method) ? 'selected' : "";."
							  			}
							  			echo "<option value='".$value['payment_method']."' ".$selected."  >".$value['payment_method']."</option>";
							  		}
						  		?>
                   				</select>
							  	<b class="payment_method" style="color: red;display: none;">Please select</b>
							  </td>
							</tr>
							
							<tr>
							  <td width="20%" class="dvtCellLabel small">Receive money<span style="color: red">*</span></td>
							  <td width="30%" class="dvtCellInfo">
							  	<input type="text" name="receive_money" id="receive_money" value="<?php echo $receive_money ;?>">
							  	<b class="receive_money" style="color: red;display: none;">Please enter information</b>
							  </td>
							</tr>

						<?php }else if($status == 'Wait Delivery'){ ?>
							<tr>
							  <td width="20%" class="dvtCellLabel small">Site Phone (delivery)<span style="color: red">*</span></td>
							  <td width="30%" class="dvtCellInfo">
							  	<input type="text" name="site_phone_delivery" id="site_phone_delivery" value="<?php echo $site_phone_delivery ;?>" style="wid;width: 80%;">
							  	<b class="site_phone_delivery" style="color: red;display: none;">Please enter information</b>
							  </td>
							</tr>
							<tr>
							  <td width="20%" class="dvtCellLabel small">Plan date<span style="color: red">*</span></td>
							  <td width="30%" class="dvtCellInfo">
							  	<input name="plan_date" tabindex="" id="plan_date" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="<?php echo date("d-m-Y",strtotime($plan_date)); ?>" class="user-success">
                				<img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_plan_date" style="vertical-align: middle;">
                				<b class="plan_date" style="color: red;display: none;">Please select</b>
							  </td>
							  	<script type="text/javascript" id="massedit_calendar_plan_date">
	                            Calendar.setup ({inputField : "plan_date", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_plan_date", singleClick : true, step : 1})
	                        	</script>
	                        	
							</tr>

							<tr>
							  <td width="20%" class="dvtCellLabel small">Plan Time<span style="color: red">*</span></td>
							  <td width="30%" class="dvtCellInfo">
							  	<input id="plan_time" name="plan_time" style="width:80px;" value="<?php echo $plan_time;?>" autocomplete="on" class="easyui-timespinner" data-options="showSeconds:false,editable:true">
							  	<b class="plan_time" style="color: red;display: none;">Please select</b>
							  </td>
							  
							</tr>

							<tr>
							  <td width="20%" class="dvtCellLabel small">Vendor Site code<span style="color: red">*</span></td>
							  <td width="30%" class="dvtCellInfo">
							  	<input type="text" name="vendor_site_code" id="vendor_site_code" value="<?php echo $vendor_site_code ;?>" style="wid;width: 80%;">
							  	<b class="vendor_site_code" style="color: red;display: none;">Please enter information</b>
							  </td>
							</tr>

							<tr>
							  <td width="20%" class="dvtCellLabel small">Attach file<span style="color: red">*</span></td>
							  <td width="30%" class="dvtCellInfo">
							  	<input type="file" name="image_vendor" id="image_vendor" value="">
							  	<b class="image_vendor" style="color: red;display: none;">Please Attach file</b>
							  </td>
							</tr>

							<tr>
							  <td width="20%" class="dvtCellLabel small">Vender plant<span style="color: red">*</span></td>
							  <td width="30%" class="dvtCellInfo">
							  	<input type="text" name="vender_plant" id="vender_plant" value="<?php echo $vender_plant ;?>" style="wid;width: 80%;">
							  	<b class="vender_plant" style="color: red;display: none;">Please enter information</b>
							  </td>
							</tr>

						<?php }?>

						<!-- <tr>
						  <td width="20%" class="dvtCellLabel small">Lost Reason<span style="color: red">*</span></td>
						  <td width="30%" class="dvtCellInfo">
						  	<select name="lost_reason_order" id="lost_reason_order" tabindex="" class="small user-success">

						  		<?php
						  		/*foreach ($a_orderstatus as $key => $value) {
						  			echo "<option value='".$value['lost_reason_order']."''>".$value['lost_reason_order']."</option>";
						  		}*/
						  		?>
                   			</select>
						  	
						</tr> -->

						<tr>
						  <td class="dvtCellLabel">&nbsp;</td>
						  <td class="dvtCellInfo"><input type="button"  id = "save" name="save" value=" &nbsp;save&nbsp; "  class="crmbutton small save"></td>
						  </tr>
						</table>
						</form>
					</td>
				</tr>
							</table>
		</div>

<script>

jQuery(function(){
	
	jQuery('.save').click(function(event) {
		
		var statusorder = jQuery('#orderstatus').val();
		var flag = true ;
		if(statusorder=='Wait Vendor'){
			jQuery(".plan_date").css("display", "none");
			jQuery(".plan_time").css("display", "none");
			var plan_date = jQuery('#plan_date').val();
			var plan_time = jQuery('#plan_time').val();

			if(plan_date == ''){
				var flag = false ;
				jQuery(".plan_date").css("display", "block");
			}
			if(plan_time == ''){
				var flag = false ;
				jQuery(".plan_time").css("display", "block");
			}
			if(flag == false){return false;}
			event.preventDefault();
		
		}else if(statusorder=='Wait Confirm'){
			jQuery(".mix_easy_site_code").css("display", "none");
			var mix_easy_site_code = jQuery('#mix_easy_site_code').val();
			if(mix_easy_site_code == ''){
				var flag = false ;
				jQuery(".mix_easy_site_code").css("display", "block");
			}
			if(flag == false){return false;}
			event.preventDefault();

		}else if(statusorder=='Customer Payment'){
			jQuery(".payment_method").css("display", "none");
			jQuery(".receive_money").css("display", "none");
			jQuery(".file_0").css("display", "none");
			
			var payment_method = jQuery('#payment_method').val();
			var receive_money = jQuery('#receive_money').val();
			var file_0 = jQuery('#file_0').val();

			if(payment_method == ''){
				var flag = false ;
				jQuery(".payment_method").css("display", "block");
			}
			if(receive_money == ''){
				var flag = false ;
				jQuery(".receive_money").css("display", "block");
			}
			if(file_0 == ''){
				var flag = false ;
				jQuery(".file_0").css("display", "block");
			}
			if(flag == false){return false;}
			event.preventDefault();
		
		}else if(statusorder=='Wait Delivery'){
			jQuery(".site_phone_delivery").css("display", "none");
			jQuery(".plan_date").css("display", "none");
			jQuery(".plan_time").css("display", "none");
			jQuery(".vendor_site_code").css("display", "none");
			jQuery(".image_vendor").css("display", "none");
			jQuery(".vender_plant").css("display", "none");
			
			var site_phone_delivery = jQuery('#site_phone_delivery').val();
			var plan_date = jQuery('#plan_date').val();
			var plan_time = jQuery('#plan_time').val();
			var vendor_site_code = jQuery('#vendor_site_code').val();
			var image_vendor = jQuery('#image_vendor').val();
			var vender_plant = jQuery('#vender_plant').val();

			if(image_vendor == ''){
				var flag = false ;
				jQuery(".image_vendor").css("display", "block");
			}
			if(site_phone_delivery == ''){
				var flag = false ;
				jQuery(".site_phone_delivery").css("display", "block");
			}
			if(plan_date == ''){
				var flag = false ;
				jQuery(".plan_date").css("display", "block");
			}
			if(plan_time == ''){
				var flag = false ;
				jQuery(".plan_time").css("display", "block");
			}
			if(vendor_site_code == ''){
				var flag = false ;
				jQuery(".vendor_site_code").css("display", "block");
			}
			if(vender_plant == ''){
				var flag = false ;
				jQuery(".vender_plant").css("display", "block");
			}
			if(flag == false){return false;}
			event.preventDefault();

		}
		//console.log('submit');
		jQuery( "#EditView" ).submit();
		
	});

	jQuery('#EditView').form({

		url:'order_status.php',
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
	
});

</script>