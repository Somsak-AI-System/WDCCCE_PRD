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

	$sql_orderstatus="
	SELECT aicrm_lost_reason_order.lost_reason_order FROM aicrm_lost_reason_order where aicrm_lost_reason_order.presence = 1 ORDER BY aicrm_lost_reason_order.lost_reason_order_id ASC;";
	$a_orderstatus =$generate->process($sql_orderstatus,"all");
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<div class="mailClient mailClientBg">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	 	<td class="moduleName" width="80%" style="padding-left:10px;">Order Cancelled</td>
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
						  <td width="20%" class="dvtCellLabel small">Lost Reason<span style="color: red">*</span></td>
						  <td width="30%" class="dvtCellInfo">
						  	<select name="lost_reason_order" id="lost_reason_order" tabindex="" class="small user-success">

						  		<?php
						  		foreach ($a_orderstatus as $key => $value) {
						  			echo "<option value='".$value['lost_reason_order']."''>".$value['lost_reason_order']."</option>";
						  		}
						  		?>
                   			</select>
						 <b class="lost_reason_order" style="color: red;display: none;">Please select</b>
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
		
		/*if(jQuery('#lost_reason_order').val() == '' || jQuery('#lost_reason_order').val() == '--None--'){
			return false;
		}*/
		var flag = true ;
		jQuery(".lost_reason_order").css("display", "none");
		if(jQuery('#lost_reason_order').val() == '' || jQuery('#lost_reason_order').val() == '--None--'){
			var flag = false ;
			jQuery(".lost_reason_order").css("display", "block");
		}
		if(flag == false){return false;}
		
		event.preventDefault();
		jQuery( "#EditView" ).submit();
		
	});

	jQuery('#EditView').form({
		url:'order_status.php?orderstatus=Cancelled',
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