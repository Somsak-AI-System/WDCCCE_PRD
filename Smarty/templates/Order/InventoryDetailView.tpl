{*<!--

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
<script language="JavaScript" type="text/javascript" src="include/js/dtlviewajax.js"></script>
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<!-- <script type="text/javascript" src="asset/js/jquery-1.9.1.min.js"></script> -->
<script type="text/javascript" src="asset/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>

<div id="convertleaddiv" style="display:block;position:absolute;left:225px;top:150px;"></div>
<span id="crmspanid" style="display:none;position:absolute;"  onmouseover="show('crmspanid');">
<link rel="stylesheet" type="text/css" media="all" href="asset/css/icon.css">
<link rel="stylesheet" type="text/css" media="all" href="asset/css/metro-blue/easyui.css">
<link rel="stylesheet" type="text/css" media="screen" href="asset/css/custom.css"  >
   <a class="link"  align="right" href="javascript:;">{$APP.LBL_EDIT_BUTTON}</a>
</span>
<script type="text/javascript">
	jQuery.noConflict();
</script>
<style>
{literal}
	.ui-resizable{
		position:absolute;
	}
	.wait{

		background-color: #07BDEB;
	    color: #fff;
	    border-radius: 5px;
	    padding: 7px;
	    font-weight: 400;
	    border: 1px solid #06ADD7;
	    padding: 5px 10px;
	    cursor: pointer;
	    margin: 2px;
	    font-size: 11px;
	    font-family: PromptMedium;
	}
{/literal}
</style>
<script language="javascript">
{literal}
jQuery( document ).ready(function() {

	jQuery("#loadPage").hide();
	jQuery( ".print_quotation" ).on( "click", function() {
		var id = jQuery(this).attr('data-id');
		var userid = jQuery(this).attr('data-userid');
		jQuery("#loadPage").show();
		console.log(id);
		 jQuery.ajax({
						type: "POST",
						encoding:"UTF-8",
						url: "quotation_print.php",
						dataType: "html",
						async : false,
						data:  {"id": id,"userid": userid},
						success: function (data)
						{
							jQuery("#loadPage").hide();
							var obj = jQuery.parseJSON(data);
							if(obj.status==true){
								//var errMsg =  obj.msg + " " +obj.error ;
								window.open(obj.url);
							}else{
								var errMsg =  obj.msg  + " " +obj.error ;

								//jQuery.messager.alert('Info',errMsg,'info');
								alert(errMsg);
							}

							/*jQuery.messager.alert('Info', errMsg);*/
							window.close();
						}//success
					});//ajax;
	});
});

function confirm_approve(approve_status,id,level){
	var msg = '';
	if(approve_status == 'Approve'){
		msg = 'ต้องการอนุมัติรายการ';
		url = 'quotation_status.php';
	}else if ( approve_status =='Request_Approve')	{
		msg = 'ต้องการขออนุมัติรายการ';
		url = 'quotation_status.php';
	}else if ( approve_status =='Complete')	{
		msg = 'ต้องการปิดการขาย';
		url = 'quotation_status.php';
	}else{
		msg = 'ต้องการไม่อนุมัติรายการ';
		url = 'quotation_cancel.php?crmid='+id+'&level='+level;
	}

	var dlg = jQuery.messager.confirm({
		title: 'Confirm',
		msg: msg,
		buttons:[{
			text: 'Yes',
			onClick: function(){

				jQuery('.'+approve_status).attr('disabled','disabled');
				jQuery('.'+approve_status).css({
					'background-color' : '#cccccc',
					'border': '1px solid #999999',
					'color': '#666666'});
				if(approve_status == 'Cancel_Approve'){
					dlg.dialog("close");
					jQuery('#dialog').window({
					    title: 'Cancel Approve',
					    width: 550,
					    height: 350,
					    closed: false,
					    cache: false,
					    href: url,
					    modal: true
					});
				}else{
					
					jQuery.messager.progress({
						title:'Please wait...',
						text:'PROCESSING'
					});

					dlg.dialog('destroy');
					jQuery.ajax({
					   type: "POST",
					   url: url,
					   cache: false,
					   data: {crmid:id,quotationstatus:approve_status,level:level},
					   dataType: "json",
					   success: function(returndate){
						if(returndate['status'] == true){
							jQuery.messager.progress('close');	
							jQuery.messager.alert({
								title: 'Info',
								msg: returndate['msg'],
								fn: function(){
									location.reload();
								}
							});

						}else{
							jQuery.messager.progress('close');
							jQuery.messager.alert('Info',returndate['msg'],'info');
						}//if
					   }//success
					 });//ajax

				}

			}//onclick
		},{
			text: 'No',
			onClick: function(){
			dlg.dialog('destroy');
			}
		}]
	});
}//function
{/literal}
</script>
<script>
function tagvalidate()
{ldelim}
	if(trim(document.getElementById('txtbox_tagfields').value) != '')
		SaveTag('txtbox_tagfields','{$ID}','{$MODULE}');
	else
	{ldelim}
		alert("{$APP.PLEASE_ENTER_TAG}");
		return false;
	{rdelim}
{rdelim}
function DeleteTag(id,recordid)
{ldelim}
	$("vtbusy_info").style.display="inline";
	Effect.Fade('tag_'+id);
	new Ajax.Request(
		'index.php',
                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                        method: 'post',
                        postBody: "file=TagCloud&module={$MODULE}&action={$MODULE}Ajax&ajxaction=DELETETAG&recordid="+recordid+"&tagid=" +id,
                        onComplete: function(response) {ldelim}
						getTagCloud();
						$("vtbusy_info").style.display="none";
                        {rdelim}
                {rdelim}
        );
{rdelim}
{literal}
function showHideStatus(sId,anchorImgId,sImagePath)
{
	oObj = eval(document.getElementById(sId));
	if(oObj.style.display == 'block')
	{
		oObj.style.display = 'none';
		eval(document.getElementById(anchorImgId)).src =  'themes/images/slidedown_b21.png';
		eval(document.getElementById(anchorImgId)).alt = 'Display';
		eval(document.getElementById(anchorImgId)).title = 'Display';
	}
	else
	{
		oObj.style.display = 'block';
		eval(document.getElementById(anchorImgId)).src =  'themes/softed/images/slidedown_b.png';
		eval(document.getElementById(anchorImgId)).alt = 'Hide';
		eval(document.getElementById(anchorImgId)).title = 'Hide';
	}
}
function setCoOrdinate(elemId)
{
	oBtnObj = document.getElementById(elemId);
	var tagName = document.getElementById('lstRecordLayout');
	leftpos  = 0;
	toppos = 0;
	aTag = oBtnObj;
	do
	{
	  leftpos  += aTag.offsetLeft;
	  toppos += aTag.offsetTop;
	} while(aTag = aTag.offsetParent);

	tagName.style.top= toppos + 20 + 'px';
	tagName.style.left= leftpos - 276 + 'px';
}

function getListOfRecords(obj, sModule, iId,sParentTab)
{
		new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=Users&action=getListOfRecords&ajax=true&CurModule='+sModule+'&CurRecordId='+iId+'&CurParentTab='+sParentTab,
			onComplete: function(response) {
				sResponse = response.responseText;
				$("lstRecordLayout").innerHTML = sResponse;
				Lay = 'lstRecordLayout';
				var tagName = document.getElementById(Lay);
				var leftSide = findPosX(obj);
				var topSide = findPosY(obj);
				var maxW = tagName.style.width;
				var widthM = maxW.substring(0,maxW.length-2);
				var getVal = eval(leftSide) + eval(widthM);
				if(getVal  > document.body.clientWidth ){
					leftSide = eval(leftSide) - eval(widthM);
					tagName.style.left = leftSide + 230 + 'px';
				}
				else
					tagName.style.left= leftSide + 388 + 'px';

				setCoOrdinate(obj.id);

				tagName.style.display = 'block';
				tagName.style.visibility = "visible";
			}
		}
	);
}
{/literal}
</script>
<div id="dialog" style="display:none;">Dialog Content.</div>
<div id="lstRecordLayout" class="layerPopup" style="display:none;width:325px;height:300px;"></div> <!-- Code added by SAKTI on 16th Jun, 2008 -->

<table width="100%" cellpadding="2" cellspacing="0" border="0">
   <tr>
	<td>
		{include file='Buttons_List1.tpl'}

		<!-- Contents -->
		<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
		   <tr>
			<td valign=top><img src="{'showPanelTopLeft.gif'|@aicrm_imageurl:$THEME}"></td>
			<td class="showPanelBg" valign=top width=100%>
			<!-- PUBLIC CONTENTS STARTS-->
			   <div class="small" style="padding:20px" >

				<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
				   <tr>
					<td>
			         {* Module Record numbering, used MOD_SEQ_ID instead of ID *}
			         {assign var="USE_ID_VALUE" value=$MOD_SEQ_ID}
		  			 {if $USE_ID_VALUE eq ''} {assign var="USE_ID_VALUE" value=$ID} {/if}

						<span class="lvtHeaderText"><font color="purple">[ {$USE_ID_VALUE} ] </font>{$NAME} -  {$MOD[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</span>&nbsp;&nbsp;&nbsp;<span class="small">{$UPDATEINFO}</span>&nbsp;<span id="vtbusy_info" style="display:none;" valign="bottom"><img src="{'vtbusy.gif'|@aicrm_imageurl:$THEME}" border="0"></span><span id="vtbusy_info" style="visibility:hidden;" valign="bottom"><img src="{'vtbusy.gif'|@aicrm_imageurl:$THEME}" border="0"></span>
					</td>
				   </tr>
				</table>
				<br>

				<!-- Entity and More information tabs -->
				<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
				   <tr>
					<td>
   						<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
						   <tr>
								<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
								<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>

								<td class="dvtSelectedCell" align=center nowrap>{$APP[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</td>
								<!-- <td class="dvtTabCache" style="width:10px">&nbsp;</td> -->
								{if $SinglePane_View eq 'false'}
								<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a></td>
								{/if}
								<td class="dvtTabCache" align="right" style="width:100%">

									

                                    {if $EDIT_DUPLICATE eq 'permitted' || $is_permmited eq true}

                                    	{if $order_status eq 'Open'}
                                    		<input class="crmbutton small wait" type="button" value="Wait Vendor" onclick="update_status('Wait Vendor','{$ID}')"  />&nbsp;
                                    	{/if}

                                    	{if $order_status eq 'Wait Vendor'}
                                    		<input class="crmbutton small wait" type="button" value="Wait Confirm" onclick="update_status('Wait Confirm','{$ID}')"  />&nbsp;
                                    	{/if}

                                    	{if $order_status eq 'Wait Confirm'}
                                    		<input class="crmbutton small wait" type="button" value="Customer Payment" onclick="update_status('Customer Payment','{$ID}')"  />&nbsp;
                                    	{/if}

                                    	{if $order_status eq 'Customer Payment'}
                                    		<input class="crmbutton small wait" type="button" value="Wait Delivery" onclick="update_status('Wait Delivery','{$ID}')"  />&nbsp;
                                    	{/if}

                                    	{if $order_status eq 'Wait Delivery'}
                                    		<input class="crmbutton small wait" type="button" value="Start Delivery" onclick="update_status('Start Delivery','{$ID}')"  />&nbsp;
                                    	{/if}
                                    	
                                    	{if $order_status eq 'Start Delivery'}
                                    		<input class="crmbutton small save" type="button" value="Completed" onclick="update_status('Completed','{$ID}')"  />&nbsp;
                                    	{/if}

                                    	{if $order_status neq 'Completed' && $order_status neq 'Cancelled'}
								
											<input class="crmbutton small cancel" type="button" value="Cancelled" onclick="update_status('Cancelled','{$ID}')"  />&nbsp;
											<!-- <button title="Completed" accessKey="" class="crmbutton small edit save" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}">
												<img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Completed
											</button>

											<button title="Cancelled" accessKey="" class="crmbutton small edit cancel" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}">
												<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px;height: 17px; vertical-align: middle;">&nbsp;Cancelled
											</button> -->

											<button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}">
												<img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
											</button>
						
										{else}

										{/if}
                                		

									{/if}

									{if $EDIT_DUPLICATE eq 'permitted' || $is_permmited eq true && $MODULE neq 'Documents' && $MODULE neq 'Products'}
                                        {if $MODULE eq 'Order'}
                                        	{if $flagassign eq true && $quotation_status eq 'เปิดใบเสนอราคา' }
				                        		<input class="crmbutton small edit Request_Approve" type="button" value="Request Approve" onclick="confirm_approve('Request_Approve','{$ID}','');"  />&nbsp;
				                      		{/if}

				                      		{if $quotation_status eq 'ขออนุมัติใบเสนอราคา' && $approveflg eq true }
                                            	{if $levelflg eq '4'}
				                        		<input class="crmbutton small edit Approve" type="button" value="Final Approve" onclick="confirm_approve('Approve','{$ID}','{$levelflg}')"  />&nbsp;
				                        		<input class="crmbutton small cancel Cancel_Approve" type="button" value="Cancel Final Approve" onclick="confirm_approve('Cancel_Approve','{$ID}','{$levelflg}')"  />&nbsp;
                                                {else}
				                        		<input class="crmbutton small edit Approve" type="button" value="Approve Level {$levelflg}" onclick="confirm_approve('Approve','{$ID}','{$levelflg}')"  />&nbsp;
				                        		<input class="crmbutton small cancel Cancel_Approve" type="button" value="Cancel Approve Level {$levelflg}" onclick="confirm_approve('Cancel_Approve','{$ID}','{$levelflg}')"  />&nbsp;
                                                {/if}
				                      		{/if}
				                      		
											{*top $flagassign eq true &&*}
                                            {if $quotation_status eq 'อนุมัติใบเสนอราคา' ||  $quotation_status eq 'ไม่อนุมัติใบเสนอราคา' || ($is_permmited eq true && $quotation_status eq 'อนุมัติใบเสนอราคา' ||  $quotation_status eq 'ไม่อนุมัติใบเสนอราคา')}
                                        		<input title="Revise" accessKey="Revise" class="crmbutton small create" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isRevise.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Revise" value="Revise">&nbsp;
                                        	{/if}
											{*$flagassign eq true &&*}
											{if ( $quotation_status eq 'อนุมัติใบเสนอราคา')}
                                        		<input class="crmbutton small edit Complete" type="button" value="Complete" onclick="confirm_approve('Complete','{$ID}','');"   />&nbsp;
                                        	{/if}

                                        	{*{if $quotation_status eq 'เปิดใบเสนอราคา' || $quotation_status eq 'เปลี่ยนแปลงใบเสนอราคา' || $quotation_status eq 'อนุมัติใบเสนอราคา' || $quotation_status eq 'ปิดการขาย'  }*}
                                            {*{if $IS_ADMIN eq 'on' || $quotation_status eq 'อนุมัติใบเสนอราคา'}*}
                                        		
                                        		<button title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}">
                                        			<img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_DUPLICATE_BUTTON_LABEL}
                                        		</button>
                                       		{*{/if}*}
                                       	
                                       	{else}
                                       		
                                       		<button title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}">
                                       			<img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_DUPLICATE_BUTTON_LABEL}
                                       		</button>
                                        {/if}
                                    {/if}


									{if $DELETE eq 'permitted' && $MODULE neq 'Products'}
										{if $MODULE eq 'Order'}
											{if $flagassign eq true && $quotation_status eq 'เปิดใบเสนอราคา' }
												
												<button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete btndelete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">
													<img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_DELETE_BUTTON_LABEL}
												</button>
                                    		{/if}
                                    	{else}
                                    		<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">&nbsp;
                                    	{/if}
                                    {elseif $DELETE eq 'permitted' && $MODULE eq 'Products'}
                                        {if $productcode==""}
											
											<button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">
												<img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_DELETE_BUTTON_LABEL}
											</button>
                                        {/if}

                                    {/if}

									{if $privrecord neq ''}
									<img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" accessKey="{$APP.LNK_LIST_PREVIOUS}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}'" name="privrecord" value="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev.gif'|@aicrm_imageurl:$THEME}">&nbsp;
									{else}
									<img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev_disabled.gif'|@aicrm_imageurl:$THEME}">
									{/if}
									{if $privrecord neq '' || $nextrecord neq ''}
									<img align="absmiddle" title="{$APP.LBL_JUMP_BTN}" accessKey="{$APP.LBL_JUMP_BTN}" onclick="var obj = this;var lhref = getListOfRecords(obj, '{$MODULE}',{$ID},'{$CATEGORY}');" name="jumpBtnIdTop" id="jumpBtnIdTop" src="{'rec_jump.gif'|@aicrm_imageurl:$THEME}">&nbsp;
									{/if}
									{if $nextrecord neq ''}
									<img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" accessKey="{$APP.LNK_LIST_NEXT}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}'" name="nextrecord" src="{'rec_next.gif'|@aicrm_imageurl:$THEME}">&nbsp;
									{else}
									<img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" src="{'rec_next_disabled.gif'|@aicrm_imageurl:$THEME}">&nbsp;
									{/if}
								</td>
							</tr>
						</table>
					</td>
				   </tr>
				   <tr>
					<td valign=top align=left >
						<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace" style="border-bottom:0px;">
						   <tr>
							<td align=left style="padding:10px;">
							<!-- content cache -->
								<form action="index.php" method="post" name="DetailView" id="form" onsubmit="VtigerJS_DialogBox.block();">
								{include file='DetailViewHidden.tpl'}
								<!-- Entity informations display - starts -->
								<table border=0 cellspacing=0 cellpadding=0 width=100%>
			                			   <tr>
									<td style="padding:10px;border-right:1px dashed #CCCCCC;" width="80%">
<!-- The following table is used to display the buttons -->
<!-- Button displayed - finished-->
							 {include_php file="./include/DetailViewBlockStatus.php"}

<!-- Entity information(blocks) display - start -->
{foreach key=header item=detail from=$BLOCKS}
	<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
	   <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align=right>
		</td>
	   </tr>
	   <tr>
		{strip}
		<td colspan=4 class="dvInnerHeader" >

							<div style="float:left;font-weight:bold;"><div style="float:left;"><a href="javascript:showHideStatus('tbl{$header|replace:' ':''}','aid{$header|replace:' ':''}','{$IMAGE_PATH}');">
							{if $BLOCKINITIALSTATUS[$header] eq 1}
								<img id="aid{$header|replace:' ':''}" src="{'themes/softed/images/slidedown_b.png'|@aicrm_imageurl:$THEME}" style="border: 0px solid #000000; width: 13px; height: 10px; vertical-align: middle;" alt="Hide" title="Hide"/>
							{else}
								<img id="aid{$header|replace:' ':''}" src="{'themes/images/slidedown_b21.png'|@aicrm_imageurl:$THEME}" style="border: 0px solid #000000;" alt="Display" title="Display"/>
							{/if}
								<b style="font-family: PromptMedium; font-size: 12px; font-weight: 600; color: #2B2B2B;">&nbsp;&nbsp;{$header}</b>
								</a></div></div>
		</td>
		{/strip}
	   </tr>
							</table>
							{if $BLOCKINITIALSTATUS[$header] eq 1}
							<div style="width:auto;display:block;" id="tbl{$header|replace:' ':''}" >
							{else}
							<div style="width:auto;display:none;" id="tbl{$header|replace:' ':''}" >
							{/if}
							<table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">

	   {foreach item=detail from=$detail}
	   <tr style="height:25px">
		{foreach key=label item=data from=$detail}
			{assign var=keyid value=$data.ui}
			{assign var=keyval value=$data.value}
			{assign var=keytblname value=$data.tablename}
			{assign var=keyfldname value=$data.fldname}
			{assign var=keyfldid value=$data.fldid}
			{assign var=keyoptions value=$data.options}
			{assign var=keysecid value=$data.secid}
			{assign var=keyseclink value=$data.link}
			{assign var=keycursymb value=$data.cursymb}
			{assign var=keysalut value=$data.salut}
			{assign var=keycntimage value=$data.cntimage}
			{assign var=keyadmin value=$data.isadmin}
            {assign var=fielddatatype value=$data.fielddatatype}
            
				{if $label ne ''}
					{if $keycntimage ne ''}
						<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$keycntimage}</td>
					{elseif $label neq 'Tax Class'}<!-- Avoid to display the label Tax Class -->
						{if $keyid eq '71' || $keyid eq '72'}  <!--CurrencySymbol-->
							<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$label} ({$keycursymb})</td>
						{else}
                        	{if $keyfldname eq 'cf_4320'}
                                <td class="dvtCellLabel" align=right width=25%><font size=""><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$label}</font></td>
                            {else}
                                <td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$label}</td>
                            {/if}
						{/if}
					{/if}
					{if $EDIT_PERMISSION eq 'yes' && $display_type neq '2'}
						{* Performance Optimization Control *}
						{if !empty($DETAILVIEW_AJAX_EDIT) }
							{include file="DetailViewUI.tpl"}
						{else}
							{include file="DetailViewFields.tpl"}
						{/if}
						{* END *}
					{else}
						{include file="DetailViewFields.tpl"}
					{/if}
				{/if}
		{/foreach}
	   </tr>
	   {/foreach}
	    
	    {if $header=="Description Information" && $MODULE=="Order" }
	   		{include_php file='modules/Order/log_change_status.php'}
	    {/if}

	</table>
	
	</div> <!-- Line added by SAKTI on 10th Apr, 2008 -->

{/foreach}
{*-- End of Blocks--*}
<!-- Entity information(blocks) display - ends -->

									<br>

										<!-- Product Details informations -->
										{$ASSOCIATED_PRODUCTS}
										</form>
									</td>
<!-- The following table is used to display the buttons -->
								<table border=0 cellspacing=0 cellpadding=0 width=100%>
			                			   <tr>
									<td style="padding:10px;border-right:1px dashed #CCCCCC;" width="80%">

		<table border=0 cellspacing=0 cellpadding=0 width=100%>
		  <tr>
			<td style="border-right:1px dashed #CCCCCC;" width="100%">
			{if $SinglePane_View eq 'true'}
				{include file= 'RelatedListNew.tpl'}
			{/if}
		</td></tr></table>
</td></tr></table>
									<!-- Inventory Actions - ends -->
									<td width=22% valign=top style="padding:10px;">
										<!-- right side InventoryActions -->
										{include file="Order/InventoryActions.tpl"}

										<br>
										<!-- To display the Tag Clouds -->
										<div>
                                        {if $MODULE neq 'Products' && $MODULE neq 'Order'}

                                              {include file="TagCloudDisplay.tpl"}

                                         {/if}
										</div>
									</td>
								   </tr>

								</table>
							</td>
						   </tr>
						    <tr>
					<td>
   						<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
						   <tr>
								<td class="dvtTabCacheBottom" style="width:10px" nowrap>&nbsp;</td>

								<td class="dvtSelectedCellBottom" align=center nowrap>{$APP[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</td>
								<!-- <td class="dvtTabCacheBottom" style="width:10px">&nbsp;</td> -->
								{if $SinglePane_View eq 'false'}
								<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a></td>
								{/if}
								<td class="dvtTabCacheBottom" align="right" style="width:100%">

                                  
                                    {if $EDIT_DUPLICATE eq 'permitted' || $is_permmited eq true}

                                    	{if $order_status eq 'Open'}
                                    		<input class="crmbutton small wait" type="button" value="Wait Vendor" onclick="update_status('Wait Vendor','{$ID}')"  />&nbsp;
                                    	{/if}

                                    	{if $order_status eq 'Wait Vendor'}
                                    		<input class="crmbutton small wait" type="button" value="Wait Confirm" onclick="update_status('Wait Confirm','{$ID}')"  />&nbsp;
                                    	{/if}

                                    	{if $order_status eq 'Wait Confirm'}
                                    		<input class="crmbutton small wait" type="button" value="Customer Payment" onclick="update_status('Customer Payment','{$ID}')"  />&nbsp;
                                    	{/if}

                                    	{if $order_status eq 'Customer Payment'}
                                    		<input class="crmbutton small wait" type="button" value="Wait Delivery" onclick="update_status('Wait Delivery','{$ID}')"  />&nbsp;
                                    	{/if}

                                    	{if $order_status eq 'Wait Delivery'}
                                    		<input class="crmbutton small wait" type="button" value="Start Delivery" onclick="update_status('Start Delivery','{$ID}')"  />&nbsp;
                                    	{/if}

                                    	{if $order_status eq 'Start Delivery'}
                                    		<input class="crmbutton small save" type="button" value="Completed" onclick="update_status('Completed','{$ID}')"  />&nbsp;
                                    	{/if}

                                    	{if $order_status neq 'Completed' && $order_status neq 'Cancelled'}
										
											<input class="crmbutton small cancel" type="button" value="Cancelled" onclick="update_status('Cancelled','{$ID}')"  />&nbsp;
											
											<button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}">
												<img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
											</button>
										
										{else}

										{/if}

									{/if}
									{if $EDIT_DUPLICATE eq 'permitted' || $is_permmited eq true && $MODULE neq 'Documents' && $MODULE neq 'Products'}
                                        {if $MODULE eq 'Order'}
                                        	{if $flagassign eq true && $quotation_status eq 'เปิดใบเสนอราคา' }
				                        		<input class="crmbutton small edit Request_Approve" type="button" value="Request Approve" onclick="confirm_approve('Request_Approve','{$ID}','');"  />&nbsp;
				                      		{/if}
				                      		{if $quotation_status eq 'ขออนุมัติใบเสนอราคา' && $approveflg eq true }
                                            	{if $levelflg eq '4'}
				                        		<input class="crmbutton small edit Approve" type="button" value="Final Approve" onclick="confirm_approve('Approve','{$ID}','{$levelflg}')"  />&nbsp;
				                        		<input class="crmbutton small cancel Cancel_Approve" type="button" value="Cancel Final Approve" onclick="confirm_approve('Cancel_Approve','{$ID}','{$levelflg}')"  />&nbsp;
                                                {else}
				                        		<input class="crmbutton small edit Approve" type="button" value="Approve Level {$levelflg}" onclick="confirm_approve('Approve','{$ID}','{$levelflg}')"  />&nbsp;
				                        		<input class="crmbutton small cancel Cancel_Approve" type="button" value="Cancel Approve Level {$levelflg}" onclick="confirm_approve('Cancel_Approve','{$ID}','{$levelflg}')"  />&nbsp;
                                                {/if}
				                      		{/if}
											{*buttom $flagassign eq true && *}
				                      		{if $quotation_status eq 'อนุมัติใบเสนอราคา' ||  $quotation_status eq 'ไม่อนุมัติใบเสนอราคา' || ($is_permmited eq true && $quotation_status eq 'อนุมัติใบเสนอราคา' ||  $quotation_status eq 'ไม่อนุมัติใบเสนอราคา')}
                                        		<input title="Revise" accessKey="Revise" class="crmbutton small create" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isRevise.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Revise" value="Revise">&nbsp;
                                        	{/if}
											{*$flagassign eq true &&*}
											{if ( $quotation_status eq 'อนุมัติใบเสนอราคา')}
                                        		<input class="crmbutton small edit Complete" type="button" value="Complete" onclick="confirm_approve('Complete','{$ID}','');"   />&nbsp;
                                        	{/if}
                                        	{*{if $quotation_status eq 'เปิดใบเสนอราคา' || $quotation_status eq 'เปลี่ยนแปลงใบเสนอราคา' || $quotation_status eq 'อนุมัติใบเสนอราคา' || $quotation_status eq 'ปิดการขาย'  }*}
                                            {*{if $IS_ADMIN eq 'on' || $quotation_status eq 'ขออนุมัติใบเสนอราคา'}*}
                                        		
                                        		<button title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}">
                                        			<img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_DUPLICATE_BUTTON_LABEL}
                                        		</button>
                                       		{*{/if}*}
                                       	{else}
                                       		
                                       		<button title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}">
                                       			<img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_DUPLICATE_BUTTON_LABEL}
                                       		</button>
                                        {/if}
                                    {/if}

									{if $DELETE eq 'permitted' && $MODULE neq 'Products' }
										{if $MODULE eq 'Order'}
											{if $flagassign eq true && $quotation_status eq 'เปิดใบเสนอราคา' }
												
												<button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete btndelete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">
													<img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_DELETE_BUTTON_LABEL}
												</button>
                                    		{/if}
                                    	{else}
											<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">&nbsp;
										{/if}
									 {elseif $DELETE eq 'permitted' && $MODULE eq 'Products'}
                                        {if $productcode==""}
											
											<button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">
												<img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_DELETE_BUTTON_LABEL}
											</button>
                                        {/if}
									{/if}

									{if $privrecord neq ''}
									<img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" accessKey="{$APP.LNK_LIST_PREVIOUS}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}'" name="privrecord" value="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev.gif'|@aicrm_imageurl:$THEME}">&nbsp;
									{else}
									<img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev_disabled.gif'|@aicrm_imageurl:$THEME}">
									{/if}
									{if $privrecord neq '' || $nextrecord neq ''}
									<img align="absmiddle" title="{$APP.LBL_JUMP_BTN}" accessKey="{$APP.LBL_JUMP_BTN}" onclick="var obj = this;var lhref = getListOfRecords(obj, '{$MODULE}',{$ID},'{$CATEGORY}');" name="jumpBtnIdBottom" id="jumpBtnIdBottom" src="{'rec_jump.gif'|@aicrm_imageurl:$THEME}">&nbsp;
									{/if}
									{if $nextrecord neq ''}
									<img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" accessKey="{$APP.LNK_LIST_NEXT}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}'" name="nextrecord" src="{'rec_next.gif'|@aicrm_imageurl:$THEME}">&nbsp;
									{else}
									<img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" src="{'rec_next_disabled.gif'|@aicrm_imageurl:$THEME}">&nbsp;
									{/if}
								</td>
							</tr>
						</table>
					</td>
				   </tr>
						</table>
					<!-- PUBLIC CONTENTS STOPS-->
					</td>
					<td align=right valign=top>
						<img src="{'showPanelTopRight.gif'|@aicrm_imageurl:$THEME}">
					</td>
				   </tr>
				</table>
			</td>
		   </tr>
		</table>
		<!-- Contents - end -->

<script>
function getTagCloud()
{ldelim}
new Ajax.Request(
        'index.php',
        {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
        method: 'post',
        postBody: 'module={$MODULE}&action={$MODULE}Ajax&file=TagCloud&ajxaction=GETTAGCLOUD&recordid={$ID}',
        onComplete: function(response) {ldelim}
                                $("tagfields").innerHTML=response.responseText;
                                $("txtbox_tagfields").value ='';
                        {rdelim}
        {rdelim}
);
{rdelim}
getTagCloud();
</script>

<script language="javascript">
	var fielduitype = new Array({$VALIDATION_DATA_UITYPE})
	var fieldname = new Array({$VALIDATION_DATA_FIELDNAME});
	var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL});
	var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE});
</script>

<div id="loadPage">
	<img alt="" src="asset/images/ajax-loader.gif">
	<span>Loading ...</span>
</div>

<script>
{literal}
function update_status(status,id){
	var msg = '';
	if(status == 'Wait Vendor'){	
		msg = "ต้องการเปลี่ยนสถานะเป็น 'Wait Vendor' ? ";
		url = 'order_update_status.php?crmid='+id+'&status='+status;

	}else if ( status =='Wait Confirm')	{
		msg = "ต้องการเปลี่ยนสถานะเป็น 'Wait Confirm' ? ";
		url = 'order_update_status.php?crmid='+id+'&status='+status;
		//url = 'order_status.php';
	}else if ( status =='Customer Payment')	{
		msg = "ต้องการเปลี่ยนสถานะเป็น 'Customer Payment' ? ";
		url = 'order_update_status.php?crmid='+id+'&status='+status;
		//url = 'order_status.php';
	}else if ( status =='Wait Delivery')	{
		msg = "ต้องการเปลี่ยนสถานะเป็น 'Wait Delivery' ? ";
		url = 'order_update_status.php?crmid='+id+'&status='+status;
		//url = 'order_status.php';
	}else if ( status =='Start Delivery')	{
		msg = "ต้องการเปลี่ยนสถานะเป็น 'Start Delivery' ? ";
		url = 'order_status.php?crmid='+id+'&status='+status;
		//url = 'order_status.php';
	}else if ( status =='Completed')	{
		msg = "ต้องการเปลี่ยนสถานะเป็น 'Completed' ? ";
		url = 'order_completed.php?crmid='+id;
	}else{
		msg = 'ต้องการยกเลิกรายการ';
		url = 'order_cancel.php?crmid='+id;
	}

	if(status == 'Start Delivery'){
		var dlg = jQuery.messager.confirm({
			title: 'Confirm',
			msg: msg,
			buttons:[{
				text: 'Yes',
				onClick: function(){
					jQuery.messager.progress({
						title:'Please wait...',
						text:'PROCESSING'
					});

					dlg.dialog('destroy');
					jQuery.ajax({
					   type: "POST",
					   url: url,
					   cache: false,
					   data: {crmid:id,orderstatus:status},
					   dataType: "json",
					   success: function(returndate){
						if(returndate['status'] == true){
							jQuery.messager.progress('close');	
							jQuery.messager.alert({
								title: 'Info',
								msg: returndate['msg'],
								fn: function(){
									location.reload();
								}
							});

						}else{
							jQuery.messager.progress('close');
							jQuery.messager.alert('Info',returndate['msg'],'info');
						}//if
					   }//success
					 });//ajax
				}//onclick
			},{
				text: 'No',
				onClick: function(){
				dlg.dialog('destroy');
				}
			}]
		});
	}else{
		jQuery('#dialog').window({
		    title: status,
		    width: 550,
		    height: 350,
		    closed: false,
		    cache: false,
		    href: url,
		    modal: true
		});
	}	
	/*var dlg = jQuery.messager.confirm({
		title: 'Confirm',
		msg: msg,
		buttons:[{
			text: 'Yes',
			onClick: function(){

				if(status == 'Start Delivery'){
					jQuery.messager.progress({
						title:'Please wait...',
						text:'PROCESSING'
					});

					dlg.dialog('destroy');
					jQuery.ajax({
					   type: "POST",
					   url: url,
					   cache: false,
					   data: {crmid:id,orderstatus:status},
					   dataType: "json",
					   success: function(returndate){
						if(returndate['status'] == true){
							jQuery.messager.progress('close');	
							jQuery.messager.alert({
								title: 'Info',
								msg: returndate['msg'],
								fn: function(){
									location.reload();
								}
							});

						}else{
							jQuery.messager.progress('close');
							jQuery.messager.alert('Info',returndate['msg'],'info');
						}//if
					   }//success
					 });//ajax
				}else{
					dlg.dialog("close");
					jQuery('#dialog').window({
					    title: status,
					    width: 550,
					    height: 350,
					    closed: false,
					    cache: false,
					    href: url,
					    modal: true
					});
				}

			}//onclick
		},{
			text: 'No',
			onClick: function(){
			dlg.dialog('destroy');
			}
		}]
	});*/
}//function
{/literal}
</script>