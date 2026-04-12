<?php
	session_start();
	include("config.inc.php");
	include_once("library/dbconfig.php");
	include_once("library/myFunction.php");
	$date=date('Y-m-d');
	$smownerid=$_SESSION["authenticated_user_id"];
	$crmid=$_REQUEST["crmid"];
	$samplerequisition_status = @$_REQUEST["approve_status"];
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<div class=" mailClientBg">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
		 	<td class="moduleName" width="80%" style="padding-left:10px;">Sample Requisition Cancel</td>
			<td  width=30% nowrap class="componentName" align=right>Ai-CRM</td>
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
                <input type="hidden" name="samplerequisitionstatus" id="samplerequisitionstatus" class="txtBox"  style="width:210px" value="<?php echo @$samplerequisition_status ?>">
				<table width="100%" cellpadding="5" cellspacing="0">

				<?php if($samplerequisition_status == 'Cancel_Samplerequisition'){?>
					<tr>
						<td width="20%" class="dvtCellLabel small">เหตุผลการยกเลิก</td>
						<td width="30%" class="dvtCellInfo">
							<textarea id="cancel_reason" class="detailedViewTextBox" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" name="cancel_reason" onblur="this.className='detailedViewTextBox'" cols="90" rows="8" required></textarea>
						</td>
					</tr>
				<?php }else{ ?>
					<tr>
						<td width="20%" class="dvtCellLabel small">เหตุผลการไม่อนุมัติ</td>
						<td width="30%" class="dvtCellInfo">
							<textarea id="rejected_reason" class="detailedViewTextBox" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" name="rejected_reason" onblur="this.className='detailedViewTextBox'" cols="90" rows="8"></textarea>
						</td>
					</tr>
				<?php } ?>
				
					<tr>
						<td class="dvtCellLabel">&nbsp;</td>
						<td class="dvtCellInfo">
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
		url:'samplerequisition_status.php',
		onSubmit:function(){
			if(jQuery(this).form('validate')){
				return true;
			}else{
				return false;
			}
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

jQuery(document).ready(function(){

	jQuery('.panel-tool-close').click(function(event) {
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