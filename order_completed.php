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

	$sql_completed_sub_status_order="
	SELECT aicrm_completed_sub_status_order.completed_sub_status_order FROM aicrm_completed_sub_status_order where aicrm_completed_sub_status_order.presence = 1 ORDER BY aicrm_completed_sub_status_order.completed_sub_status_order_id ASC;";
	$a_completed_sub_status_order =$generate->process($sql_completed_sub_status_order,"all");

	$sql="
	SELECT aicrm_order.* FROM aicrm_order where aicrm_order.orderid = '".$crmid."' ";
	$data = $generate->process($sql,"all");
	$completed_sub_status_order = $data[0]['completed_sub_status_order'];
	$completed_remark = $data[0]['completed_remark'];
	
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<div class="mailClient mailClientBg">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	 	<td class="moduleName" width="80%" style="padding-left:10px;">Order Completed</td>
		<td  width=30% nowrap class="componentName" align=right>MOAI</td>
	</tr>
</table>
</div>

<div data-options="region:'center',iconCls:'icon-ok'" style="width: 530px;" title="service"  id="divplan">
			<table width="100%" cellpadding="5" cellspacing="0" border="0"  class="homePageMatrixHdr">
				<tr>
					<td style="padding:0px;" >
					<form id="EditView" method="POST" name="EditView">
                        <input type="hidden" name="myaction" value="" />
                        <input type="hidden" name="crmid" id="crmid" class="txtBox"  style="width:210px" value="<?php echo $_REQUEST["crmid"]?>">
                        
						<table width="100%" cellpadding="5" cellspacing="0">
						
						<tr>
						  <td width="20%" class="dvtCellLabel small">Completed Sub Status<span style="color: red">*</span></td>
						  <td width="30%" class="dvtCellInfo">
						  	<select name="completed_sub_status_order" id="completed_sub_status_order" tabindex="" class="small user-success">

						  		<?php
						  		foreach ($a_completed_sub_status_order as $key => $value) {
						  			$selected = '';
						  			if($value['completed_sub_status_order'] == $completed_sub_status_order ){
						  				$selected = 'selected';
						  			}
						  			echo "<option value='".$value['completed_sub_status_order']."' ".$selected." >".$value['completed_sub_status_order']."</option>";
						  		}
						  		?>
                   			</select>
                   			<b class="completed_sub_status_order" style="color: red;display: none;">Please select</b>
						  	</td>
						</tr>

						<tr>
						  <td width="20%" class="dvtCellLabel small">Completed Remark<span style="color: red">*</span></td>
						  <td width="30%" class="dvtCellInfo">
						  	<textarea name="completed_remark" id="completed_remark" tabindex="" class="detailedViewTextBox user-success" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" rows="2"><?php echo $completed_remark; ?></textarea>
						  	<b class="completed_remark" style="color: red;display: none;">Please select</b>
						  </td>				  	
						</tr>
						
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
		
		/*if(jQuery('#completed_sub_status_order').val() == '' || jQuery('#completed_sub_status_order').val() == '--None--'){
			return false;
		}*/
		var flag = true ;
		jQuery(".completed_sub_status_order").css("display", "none");
		jQuery(".completed_remark").css("display", "none");
		var completed_sub_status_order = jQuery('#completed_sub_status_order').val();
		var completed_remark = jQuery('#completed_remark').val();

		if(completed_sub_status_order == ''){
			var flag = false ;
			jQuery(".completed_sub_status_order").css("display", "block");
		}
		if(completed_remark == ''){
			var flag = false ;
			jQuery(".completed_remark").css("display", "block");
		}
		if(flag == false){return false;}
		
		event.preventDefault();
		jQuery( "#EditView" ).submit();
		
	});

	jQuery('#EditView').form({
		url:'order_status.php?orderstatus=Completed',
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