<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once("../../config.inc.php");
	require_once("../../library/dbconfig.php");
	include_once("../../library/myLibrary_mysqli.php");

	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;

	$crmid = $_REQUEST["crmid"];	
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
				  <td width="40%" class="small">ประเภท</td>
				  <td width="60%" class="">
				  	<select id="action" name="action" class="small user-success" >
				  		<option value="add">เพิ่มสินค้า</option>
				  		<option value="del">ลดสินค้า</option>
				  	</select>
				  </td>
				</tr>

				<tr>
				  <td width="40%" class="small">จำนวนสินค้า <label style="color: red">*</label></td>
				  <td width="60%" class="">
				  	<input type="text" class="detailedViewTextBox user-success text_number_int" name="quantity" id="quantity" value="" maxlength="7" style="vertical-align: top;">
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
		var quantity = document.getElementById('quantity').value;
		jQuery('.border_red').removeClass('border_red');

		if(quantity == ''){
			jQuery("#quantity").addClass("border_red");
			document.getElementById('quantity').focus();
			flag = false ;	
		}
		if(flag == false){return false;}
		event.preventDefault();
		
		jQuery("#EditView").submit();
		
	});

	jQuery('#EditView').form({

		url:'plugin/Premuimproduct/setup.php',
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
				jQuery.messager.alert('Error', errMsg, 'error');
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