<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once("../../config.inc.php");
	require_once("../../library/dbconfig.php");
	include_once("../../library/myLibrary_mysqli.php");

	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;

	$crmid = $_REQUEST["crmid"];
	
	$sql = "select 
	aicrm_promotionvoucher.promotionvoucher_name,
	aicrm_promotionvoucher.promotion_start_date,
	aicrm_promotionvoucher.promotion_end_date,
	aicrm_promotionvoucher.generate
	from aicrm_promotionvoucher
	left join aicrm_campaign on aicrm_campaign.campaignid = aicrm_promotionvoucher.campaignid
	where aicrm_promotionvoucher.promotionvoucherid = '".$crmid."' ";
	$data =$myLibrary_mysqli->select($sql);
	
	$sql_usage = "select * from aicrm_voucher_usage where presence = 1";
	$voucher_usage = $myLibrary_mysqli->select($sql_usage);
	
	$sql_type = "select * from aicrm_voucher_type where presence = 1";
	$voucher_type = $myLibrary_mysqli->select($sql_type);
	
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<div data-options="region:'center',iconCls:'icon-ok'" style="width: 100%;" title="service"  id="divplan">
	<form method="POST" name="EditView" id="EditView" ENCTYPE="multipart/form-data">
	<table width="100%" cellpadding="5" cellspacing="0" border="0"  class="homePageMatrixHdr">
		<tr>
			<td style="padding:0px;" >
				
                <input type="hidden" name="crmid" id="crmid" class="txtBox"  style="width:210px" value="<?php echo $_REQUEST["crmid"]?>">
                <input type="hidden" name="generate" id="generate" value="<?php echo $data[0]["generate"]?>">
                <tr>
				  <td width="40%" class="small">ชื่อบัตรกำนัล <label style="color: red">*</label></td>
				  <td width="60%" class="">
				  	<input type="text" class="detailedViewTextBox user-success" name="voucher_name" id="voucher_name" value="<?= $data[0]['promotionvoucher_name'];?>" style="vertical-align: top;">
				  </td>
				</tr>
				<tr>
				  <td width="40%" class="small">จำนวนบัตรกำนัล <label style="color: red">*</label></td>
				  <td width="60%" class="">
				  	<input type="text" class="detailedViewTextBox user-success text_number_int" name="voucher_amount" id="voucher_amount" value="" maxlength="5" style="vertical-align: top;">
				  </td>
				</tr>
				<tr>
				  <td width="40%" class="small">วันที่เริ่มใช้งานบัตรกำนัล <label style="color: red">*</label></td>
				  <td width="60%" class="">
				  						  	
				  	<input name="startdate" id="startdate" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" readonly value="<?php echo date("d-m-Y", strtotime($data[0]['promotion_start_date'])); ?>" class="user-success">
				  	<img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_startdate" style="vertical-align: middle;">
				  	
				  	<script type="text/javascript">
                    	Calendar.setup ({inputField : "startdate", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_startdate", singleClick : true, step : 1 })
                	</script>             	
				  </td>
				</tr>
				<tr>
				  <td width="40%" class="small">วันที่สิ้นสุดการใช้บัตรกำนัล <label style="color: red">*</label></td>
				  <td width="60%" class="">
				  						  	
				  	<input name="enddate" id="enddate" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" readonly value="<?php echo date("d-m-Y", strtotime($data[0]['promotion_end_date'])); ?>" class="user-success">
				  	<img src="themes/softed/images/btnL3Calendar.gif" id="jscal_trigger_enddate" style="vertical-align: middle;">
				  	
				  	<script type="text/javascript">
                    	Calendar.setup ({inputField : "enddate", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_enddate", singleClick : true, step : 1 })
                	</script>             	
				  </td>
				</tr>
				<tr>
				  <td width="40%" class="small">การใช้งานบัตรกำนัล</td>
				  <td width="60%" class="">
				  	<select id="voucher_usage" name="voucher_usage" class="small user-success" >
				  		<? foreach($voucher_usage as $k_usage => $usage){ ?>
				  			<option value="<? echo $usage['voucher_usage']?>"><? echo $usage['voucher_usage']?></option>
				  		<? } ?>
				  	</select>
				  </td>
				</tr>

				<tr>
				  <td width="40%" class="small">ประเภทส่วนลด</td>
				  <td width="60%" class="">
				  	<select id="voucher_type" name="voucher_type" class="small user-success" >
				  		<? foreach($voucher_type as $k_type => $type){ ?>
				  			<option value="<? echo $type['voucher_type']?>"><? echo $type['voucher_type']?></option>
				  		<? } ?>
				  	</select>
				  </td>
				</tr>
				
				<tr>
				  <td width="40%" class="small">มูลค่าบัตรกำนัล:(฿) <label style="color: red">*</label></td>
				  <td width="60%" class="">
				  	<input type="text" class="detailedViewTextBox user-success text_number_float" name="value" id="value" value="" style="vertical-align: top;">
				  </td>
				</tr>
				<tr>
				  <td width="40%" class="small">มูลค่าส่วนลดสูงสุด:(฿)</td>
				  <td width="60%" class="">
				  	<input type="text" class="detailedViewTextBox user-success text_number_float" name="max_discount_amount" id="max_discount_amount" value="" style="vertical-align: top;">
				  </td>
				</tr>
				<tr>
				  <td width="40%" class="small">ยอดซื้อขั้นต่ำ:(฿)</td>
				  <td width="60%" class="">
				  	<input type="text" class="detailedViewTextBox user-success text_number_float" name="minimum_purchase" id="minimum_purchase" value="" style="vertical-align: top;">
				  </td>
				</tr>
				<tr>
				  <td width="40%" class="small">ข้อความบัตรกำนัล <label style="color: red">*</label></td>
				  <td width="60%" class="">
				  	<textarea type="text" class="detailedViewTextBox" name="vouchermessage" id="vouchermessage"></textarea>
				  </td>
				</tr>
				<tr>
				  <td width="40%" class="small">หมายเหตุของการใช้บัตรกำนัล</td>
				  <td width="60%" class="">
				  	<textarea type="text" class="detailedViewTextBox" name="voucherremark" id="voucherremark"></textarea>
				  </td>
				</tr>
			</td>
		</tr>
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
	</table>
	</form>
</div>

<style type="text/css">
.border_red{
	border: 1px solid red !important;
}
.border_red:focus-visible{
	border: 1px solid red !important;
}
.border_red:focus{
	border: 1px solid red !important;
}
</style>
<script>
	
jQuery(function(){

	jQuery('.save').click(function(event) {
	
		var flag = true ;
		var voucher_name = document.getElementById('voucher_name').value;
		var voucher_amount = document.getElementById('voucher_amount').value;
		var value = document.getElementById('value').value;
		var vouchermessage = document.getElementById('vouchermessage').value;
		jQuery('.border_red').removeClass('border_red');

		if(voucher_name == ''){
			jQuery("#voucher_name").addClass("border_red");
			document.getElementById('voucher_name').focus();
			flag = false ;
			
		}else if(voucher_amount == ''){
			jQuery("#voucher_amount").addClass("border_red");
			document.getElementById('voucher_amount').focus();
			flag = false ;
			
		}else if(value == ''){
			jQuery("#value").addClass("border_red");
			document.getElementById('value').focus();
			flag = false ;
			
		}else if(vouchermessage == ''){
			jQuery("#vouchermessage").addClass("border_red");
			document.getElementById('vouchermessage').focus();
			flag = false ;
		}
		if(flag == false){return false;}
		event.preventDefault();
		
		jQuery("#EditView").submit();
		
	});

	jQuery('#EditView').form({

		url:'plugin/Promotionvoucher/savetemp.php',
		onSubmit:function(){
			
		},
		success:function(data){
		
			var obj = jQuery.parseJSON(data);
			if(obj.status==true){
				var errMsg =  obj.msg + " " +obj.error ;
				jQuery.messager.alert('Info', errMsg, 'info', function(){
					location.reload();
					jQuery('#dialog').window('close');
				});
			}else{
				var errMsg =  "Error: "+ obj.error;
				jQuery.messager.alert('Info', errMsg, 'info', function(){
					location.reload();
				});
			}
		}
	});

	jQuery(".cancel").click(function(){
		jQuery('#dialog').window('close');
	});

	//Float 
	jQuery(".text_number_float").on("keypress keyup blur",function (event) {
		jQuery(this).val(jQuery(this).val().replace(/[^0-9\.]/g,''));
	    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
	        event.preventDefault();
	    }
	});

	//Int
	jQuery(".text_number_int").on("keypress keyup blur",function (event) {
		jQuery(this).val(jQuery(this).val().replace(/[^\d].+/, ""));
		if ((event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
	});

});

</script>