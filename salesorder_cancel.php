<?php
	session_start();
	include("config.inc.php");
	include_once("library/dbconfig.php");
	include_once("library/myFunction.php");
	/*include_once("library/generate_MYSQL.php");
	global $generate;
	$generate = new generate($dbconfig ,"DB");*/
	$date=date('Y-m-d');
	$smownerid=$_SESSION["authenticated_user_id"];
	$crmid=$_REQUEST["crmid"];
	$approve_status = @$_REQUEST["approve_status"];
?>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<div class=" mailClientBg"><!-- mailClient -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	 	<td class="moduleName" width="80%" style="padding-left:10px;">Sales Order</td>
		<td  width=30% nowrap class="componentName" align=right>MOAI-CRM</td>
	</tr>
</table>
</div>

<div data-options="region:'center',iconCls:'icon-ok'" style="width: 530px;" title="service"  id="divplan">
	<table width="100%" cellpadding="5" cellspacing="0" border="0"  class="homePageMatrixHdr">
		<tr>
			<td style="padding:0px;" >
			<form id="QuoEditView" method="POST" name="QuoEditView">
                <input type="hidden" name="myaction" value="" />
                <input type="hidden" name="crmid" id="crmid" class="txtBox"  style="width:210px" value="<?php echo $_REQUEST["crmid"]?>">
                <input type="hidden" name="level" id="level" class="txtBox"  style="width:210px" value="<?php echo @$_REQUEST["level"]?>">
                <input type="hidden" name="salesorderstatus" id="salesorderstatus" class="txtBox"  style="width:210px" value="<?php echo @$approve_status?>">
				<table width="100%" cellpadding="5" cellspacing="0">

				<?php if($approve_status == 'Cancel_Salesorder'){?>
					<tr>
					  <td width="20%" class="dvtCellLabel small">เหตุผลการยกเลิกใบขาย</td>
					  <td width="30%" class="dvtCellInfo"><textarea id="salesorder_cancel" name="" class="detailedViewTextBox" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" name="salesorder_cancel" onblur="this.className='detailedViewTextBox'" cols="90" rows="8" required></textarea></td>
					</tr>
				<?php }else{ ?>
					<tr>
					  <td width="20%" class="dvtCellLabel small">เหตุผลการไม่อนุมัติใบขาย</td>
					  <td width="30%" class="dvtCellInfo"><textarea id="salesorder_notapprove" name="" class="detailedViewTextBox" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" name="salesorder_notapprove" onblur="this.className='detailedViewTextBox'" cols="90" rows="8"></textarea></td>
					</tr>
				<?php } ?>
				
				<tr>
				  <td class="dvtCellLabel">&nbsp;</td>
				  <td class="dvtCellInfo">
				  	<!-- <input type="button" id ="save" name="save" value=" &nbsp;save&nbsp; "  class="crmbutton small save"> -->
				  	<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" id="save">
						<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 15px; vertical-align: middle;">&nbsp;Save
					</button>
				  </td>
				  </tr>
				</table>
				</form>
			</td>
		</tr>
	</table>
</div>

<script>
jQuery(function(){
	jQuery('#QuoEditView').form({
		url:'salesorder_status.php',
		onSubmit:function(){
			//jQuery("#loadPage").show();
			if(jQuery(this).form('validate')){
				return true;
			}else{
				//jQuery("#loadPage").hide();
				return false;
			}
		},
		success:function(data){
			//$("#loadPage").hide();
			var obj = jQuery.parseJSON(data);
			//console.log(obj);
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
jQuery(document).ready(function(){

	//panel-tool-close
	jQuery('.panel-tool-close').click(function(event) {
		//event.preventDefault();
		jQuery('.Cancel_Approve').css({
					'background-color' : 'orange',
					'border': '1px solid #FF9224',
					'color': '#fff'});
		jQuery('.Cancel_Approve').removeAttr('disabled');
	});

	jQuery('.save').click(function(event) {
		event.preventDefault();
		jQuery( "#QuoEditView" ).submit();
		
	});
});

</script>