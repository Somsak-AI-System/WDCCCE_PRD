<?php
	session_start();
	include("../../config.inc.php");
	include_once("../../library/dbconfig.php");
	include_once("../../library/myFunction.php");
	include_once("../../library/generate_MYSQL.php");
	global $generate;
	$generate = new generate($dbconfig ,"DB");
	$date=date('Y-m-d');
	$smownerid=$_SESSION["authenticated_user_id"];
	

	$status_account_sql = 'select * from aicrm_status_account where presence = 1 order by status_account_id';
	$status_account_select = $generate->process($status_account_sql,"all");

	$accounttype_sql = 'select * from aicrm_accounttype where presence = 1 order by accounttypeid';
	$accounttype_select = $generate->process($accounttype_sql,"all");

	$sql="
	SELECT * ,  CONCAT(first_name,' ', last_name) as name
	FROM aicrm_users
	where deleted = 0 order by name asc " ;
	$a_assign =$generate->process($sql,"all");

	//print_r($accounttype_select); exit;
	/*$crmid=$_REQUEST["crmid"];
	$status=$_REQUEST["status"];*/

	/*$sql="
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
	$payment_method_select =$generate->process($payment_method_sql,"all");*/

?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<div class="mailClient mailClientBg">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	 	<td class="moduleName" width="80%" style="padding-left:10px;"></td>
		<td  width=30% nowrap class="componentName" align=right>MOAI</td>
	</tr>
</table>
</div>

<div data-options="region:'center',iconCls:'icon-ok'" style="width: 530px;" title="service"  id="divplan">
			<table width="100%" cellpadding="5" cellspacing="0" border="0"  class="homePageMatrixHdr">
				<tr>
					<td style="padding:0px;" >
					<form method="POST" name="QEditView" id="QEditView" ENCTYPE="multipart/form-data">
                        <input type="hidden" name="myaction" value="" />
                        <input type="hidden" name="contactid" id="contactid" value="<?php echo $_REQUEST['contact_id']; ?>" />
                                                
						<table width="100%" cellpadding="5" cellspacing="0">
						
						<tr>
						  <td width="20%" class="dvtCellLabel small textleft">Account Name<span style="color: red">*</span></td>
						  <td width="70%" class="dvtCellInfo">
						  	<input id="accountname" name="accountname" style="width:80%;" autocomplete="on" class="detailedViewTextBox user-success" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'">
						  	<b class="accountname" style="color: red;display: none;">Please enter information</b>
						  </td>
						</tr>

						<tr>
						  <td width="20%" class="dvtCellLabel small textleft">Contact Tel.</td>
						  <td width="70%" class="dvtCellInfo">
						  	<input id="q_contact_tel" name="q_contact_tel" style="width:80%;" autocomplete="on" class="detailedViewTextBox user-success" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'">
						  </td>
						</tr>

						<tr>
						  <td width="20%" class="dvtCellLabel small textleft">Contact Person</td>
						  <td width="70%" class="dvtCellInfo">
						  	<input id="q_contact_person" name="q_contact_person" style="width:80%;" autocomplete="on" class="detailedViewTextBox user-success" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'">
						  </td>
						</tr>

						<tr>
						  <td width="20%" class="dvtCellLabel small textleft">Status</td>
						  <td width="70%" class="dvtCellInfo">
						  	<select name="q_status_account" id="q_status_account" tabindex="" class="small user-success" style="width: 80%">
					  		<?php
						  		foreach ($status_account_select as $key => $value) {
						  			echo "<option value='".$value['status_account']."' >".$value['status_account']."</option>";
						  		}
					  		?>
               				</select>
						  </td>
						</tr>

						<tr>
						  <td width="20%" class="dvtCellLabel small textleft">Account Type</td>
						  <td width="70%" class="dvtCellInfo">
						  	<select name="q_accounttype" id="q_accounttype" tabindex="" class="small user-success" style="width: 80%">
					  		<?php
						  		foreach ($accounttype_select as $key => $value) {
						  			echo "<option value='".$value['accounttype']."' >".$value['accounttype']."</option>";
						  		}
					  		?>
               				</select>
						  </td>
						</tr>
						
						<tr>
						  <td width="20%" class="dvtCellLabel small textleft">Address</td>
						  <td width="70%" class="dvtCellInfo">
						  	<textarea name="q_address" id="q_address" tabindex="" class="detailedViewTextBox user-success" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" rows="2"></textarea>
						  </td>				  	
						</tr>

						<tr>
						  <td width="20%" class="dvtCellLabel small textleft">Tax  Address</td>
						  <td width="70%" class="dvtCellInfo">
						  	<textarea name="q_bill_to_address" id="q_bill_to_address" tabindex="" class="detailedViewTextBox user-success" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" rows="2"></textarea>
						  </td>				  	
						</tr>

						<tr>
						  <td width="20%" class="dvtCellLabel small textleft">Mailing  Address</td>
						  <td width="70%" class="dvtCellInfo">
						  	<textarea name="q_mailing_address" id="q_mailing_address" tabindex="" class="detailedViewTextBox user-success" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" rows="2"></textarea>
						  </td>				  	
						</tr>

						<tr>
						  <td width="20%" class="dvtCellLabel small textleft">Corporate registration<br> number (CRN)</td>
						  <td width="70%" class="dvtCellInfo">
						  	<input id="q_corporate_registration_number_crn" name="q_corporate_registration_number_crn" style="width:80%;" autocomplete="on" class="detailedViewTextBox user-success" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'">
						  </td>
						</tr>
						
						<tr>
						  <td width="30%" class="dvtCellLabel small textleft">Assigned To<span style="color: red">*</span></td>
						  <td width="70%" class="dvtCellInfo">
						  	<select name="q_assigned_user_id" id="q_assigned_user_id" tabindex="" class="small user-success" style="width: 80%">
					  		<?php
						  		foreach ($a_assign as $key => $value) {
						  			$selected = '';
						  			if($smownerid == $value['id']){
						  				$selected = 'selected';
						  				//".($value['payment_method'] == $payment_method) ? 'selected' : "";."
						  			}
						  			echo "<option value='".$value['id']."' ".$selected." >".$value['name']."</option>";
						  		}
					  		?>
               				</select>
               				<b class="assigned_user_id" style="color: red;display: none;">Please select</b>
						  </td>
						</tr>

						<tr>
						  <td class="dvtCellLabel">&nbsp;</td>
						  <td class="dvtCellInfo"><input type="button"  id = "q_save" name="q_save" value=" &nbsp;save&nbsp; "  class="crmbutton small q_save"></td>
						  </tr>
						</table>
					</form>
					</td>
				</tr>
			</table>
		</div>
<style type="text/css">
.textleft{
	text-align: left !important;
}
</style>

<script>

jQuery(function(){

	jQuery('.q_save').click(function(event) {
		
		var flag = true ;

		jQuery(".accountname").css("display", "none");
		jQuery(".assigned_user_id").css("display", "none");
		var accountname = jQuery('#accountname').val();
		var assigned_user_id = jQuery('#assigned_user_id').val();

		if(accountname == ''){
			var flag = false ;
			jQuery(".accountname").css("display", "block");
		}
		if(assigned_user_id == ''){
			var flag = false ;
			jQuery(".assigned_user_id").css("display", "block");
		}
		if(flag == false){return false;}
		event.preventDefault();

		jQuery( "#QEditView" ).submit();
		
	});

	jQuery('#QEditView').form({

		url:'saveQuickcreate.php',
		onSubmit:function(){
			
		},
		success:function(data){
			var obj = jQuery.parseJSON(data);
						
			if(obj.status==true){
				
			
				if(typeof(document.EditView.contact_tel) != 'undefined'){
					document.EditView.contact_tel.value = 55555;
				}
				if(typeof(document.EditView.account_name) != 'undefined'){
					document.EditView.account_name.value = jQuery('#accountname').val();
				}
				if(typeof(document.EditView.account_id) != 'undefined'){
					document.EditView.account_id.value = obj.crmid;
				}
				if(typeof(document.EditView.contact_tel) != 'undefined'){
					document.EditView.contact_tel.value = jQuery('#q_contact_tel').val();
				}
				if(typeof(document.EditView.contact_person) != 'undefined'){
					document.EditView.contact_person.value = jQuery('#q_contact_person').val();
				}
				if(typeof(document.EditView.mailing_address) != 'undefined'){
					document.EditView.mailing_address.value = jQuery('#q_mailing_address').val();
				}
				if(typeof(document.EditView.tax_address) != 'undefined'){
					document.EditView.tax_address.value = jQuery('#q_bill_to_address').val();
				}
				if(typeof(document.EditView.corporate_registration_number_crn) != 'undefined'){
					document.EditView.corporate_registration_number_crn.value = jQuery('#q_corporate_registration_number_crn').val();
				}
				if(typeof(document.EditView.address) != 'undefined'){
					document.EditView.address.value = jQuery('#q_address').val();
				}
				
				jQuery('#dialog_create').window('close');

			}else{

				jQuery.messager.alert('Info',obj.msg);

			}
			
		}

	});//form
	
});

</script>