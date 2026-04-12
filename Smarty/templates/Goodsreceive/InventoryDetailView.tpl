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
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>

<script type="text/javascript" src="asset/js/jquery.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>

<link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css">

<script type="text/javascript" src="include/js/reflection.js"></script>
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript" src="include/js/dtlviewajax.js"></script>

<link rel="stylesheet" type="text/css" href="asset/css/bootstrap-tagsinput.css">
<script language="JavaScript" type="text/javascript" src="asset/js/bootstrap.min.js"></script>
<script language="JavaScript" type="text/javascript" src="asset/js/bootstrap-tagsinput.min.js"></script>

<div id="convertleaddiv" style="display:block;position:absolute;left:225px;top:150px;"></div>
<span id="crmspanid" style="display:none;position:absolute;"  onmouseover="show('crmspanid');">
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

function setCoOrdinatebutton(elemId){
    oBtnObj = document.getElementById(elemId);
    var tagName = document.getElementById('lstRecordLayout');
    leftpos  = 0;
    toppos = 0;
    aTag = oBtnObj;
    do{
      leftpos  += aTag.offsetLeft;
      toppos += aTag.offsetTop;
    } while(aTag = aTag.offsetParent);
    tagName.style.top= toppos - 300 + 'px';
    tagName.style.left= leftpos - 276 + 'px';
}

function new_comments(id,module){
    var msg = '';
    url = 'plugin/Comment/newcomment.php?crmid='+id+'&module='+module;
    jQuery('#dialog').window({
        title: 'Comments',
        width: 600,
        height: 200,
        closed: false,
        cache: false,
        href: url,
        modal: true,
        minimizable:false,
        maximizable:false,
        collapsible:false,
    });
}

function getListOfRecordsbutton(obj, sModule, iId,sParentTab)
{
        new Ajax.Request(
        'index.php',
        {queue: {position: 'end', scope: 'command'},
            method: 'post',
            postBody: 'module=Users&action=getListOfRecords&ajax=true&CurModule='+sModule+'&CurRecordId='+iId+'&CurParentTab='+sParentTab,
            onComplete: function(response) {
                sResponse = response.responseText;
                //$("lstRecordLayout").innerHTML = sResponse;           
                document.getElementById("lstRecordLayout").innerHTML = sResponse;
                Lay = 'lstRecordLayout';
                var tagName = document.getElementById(Lay);
                var leftSide = findPosX(obj);
                var topSide = findPosY(obj);
                var maxW = tagName.style.width;
                var widthM = maxW.substring(0,maxW.length-2);
                var getVal = parseInt(leftSide) + parseInt(widthM);
                if(getVal  > document.body.clientWidth ){
                    leftSide = parseInt(leftSide) - parseInt(widthM);
                    tagName.style.left = leftSide + 230 + 'px';
                    tagName.style.top = topSide + 20 + 'px';
                }else{
                    tagName.style.left = leftSide + 230 + 'px';
                }
                setCoOrdinatebutton(obj.id);
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
								<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=TimelineList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_TIMELINE}</a></td>
								<td class="dvtTabCache" align="right" style="width:100%">

                                    {if $EDIT_DUPLICATE eq 'permitted' || $is_permmited eq true}
										<button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}">
											<img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
										</button>
									{/if}

									{if $EDIT_DUPLICATE eq 'permitted' || $is_permmited eq true && $MODULE neq 'Documents' && $MODULE neq 'Products'}
                                        
                                   		<button title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}">
                                   			<img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_DUPLICATE_BUTTON_LABEL}
                                   		</button>
                                        
                                    {/if}


									{if $DELETE eq 'permitted' && $MODULE neq 'Products'}	
										<button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">
											<img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_DELETE_BUTTON_LABEL}
										</button>
                                    {/if}


									{if $privrecord neq ''}
			                        	<img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" accessKey="{$APP.LNK_LIST_PREVIOUS}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}&start={$privrecordstart}'" name="privrecord" value="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev.png'|@aicrm_imageurl:$THEME}" style="cursor: pointer;">
			                        {else}
			                        	<img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev.png'|@aicrm_imageurl:$THEME}" style="opacity: 0.5;cursor:not-allowed">
			                        {/if}

			                        {if $privrecord neq '' || $nextrecord neq ''}
			                       		<img align="absmiddle" title="{$APP.LBL_JUMP_BTN}" accessKey="{$APP.LBL_JUMP_BTN}" onclick="var obj = this;var lhref = getListOfRecords(obj, '{$MODULE}',{$ID},'{$CATEGORY}');" name="jumpBtnIdTop" id="jumpBtnIdTop" src="{'rec_jump.png'|@aicrm_imageurl:$THEME}" style="cursor: pointer;">
			                        {/if}

			                        {if $nextrecord neq ''}
			                       		<img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" accessKey="{$APP.LNK_LIST_NEXT}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}&start={$nextrecordstart}'" name="nextrecord" src="{'rec_next.png'|@aicrm_imageurl:$THEME}" style="cursor: pointer;">
			                        {else}
			                       		<img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" src="{'rec_next.png'|@aicrm_imageurl:$THEME}" style="opacity: 0.5;cursor:not-allowed">
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
	   <!-- This is added to display the existing comments -->
        {if $header eq $MOD.LBL_COMMENTS || $header eq $MOD.LBL_COMMENT_INFORMATION || $header eq 'Plan Information'}
                <tr>
                    <td colspan=4 class="dvInnerHeader">
                        <a href="javascript:showHideStatus('comments_div','aidcomments_div','{$IMAGE_PATH}');">
                        <img id="aidcomments_div" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide" /></a>&nbsp;&nbsp;&nbsp;
                        <b>{$MOD.LBL_COMMENTS}</b>
                        <span style="float: right;">
                            <a href="javascript:void(0);" id="newcomments" class="newcomments" onclick="new_comments({$ID},'{$MODULE}')" style="color: #000">
                            <img id="aidcomments_div" src="themes/softed/images/newcomment.png" style="border: 0px solid #000000; width: 24px; height: 21px; margin-top: 2px;" alt="Hide" title="Hide" /><b style="vertical-align: super;">New Comment</b>
                            </a>
                        </span>
                    </td>
                    
                </tr>
                <tr><!-- tblCommentInformation -->
                    <td colspan=4 class="dvtCellInfo">{$COMMENT_BLOCK}</td>
                </tr>
                <tr><td>&nbsp;</td></tr>
        {/if}
	   {if $header neq 'Comments' && $header neq 'Comment Information'}
	         <tr>
	         {strip}
	         <td colspan=4 class="dvInnerHeader">

	         <div style="float:left; font-weight:bold; margin-left: 10px;">
	            <div style="float:left;">
	                <a href="javascript:showHideStatus('tbl{$header|replace:' ':''}','aid{$header|replace:' ':''}','{$IMAGE_PATH}');">

	        {if $BLOCKINITIALSTATUS[$header] eq 1}
	            <img id="aid{$header|replace:' ':''}" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide" />
	        {else}
	            <img id="aid{$header|replace:' ':''}" src="themes/softed/images/slidedown_b2.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Display" title="Display"/>
	        {/if}

	        </a></div><b style="font-family: PromptMedium; font-size: 12px;">&nbsp;&nbsp;&nbsp;{$header}</b></div></td>{/strip}
	        </tr>
	    {/if}
	</table>
	
	{if $header neq 'Comments' && $header neq 'Comment Information'}

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
								<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}>{$keycntimage}</td>
							{elseif $label neq 'Tax Class'}<!-- Avoid to display the label Tax Class -->
								{if $keyid eq '71' || $keyid eq '72'}  <!--CurrencySymbol-->
									<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}>{$label} ({$keycursymb})</td>
								{else}
		                        	{if $keyfldname eq 'cf_4320'}
		                                <td class="dvtCellLabel" align=right width=25%><font size=""><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}>{$label}</font></td>
		                            {else}
		                                <td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}>{$label}</td>
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
		    
		    <!-- {if $header=="Description Information" && $MODULE=="Order" }
		   		{include_php file='modules/Order/log_change_status.php'}
		    {/if} -->
			</table>
			</div> <!-- Line added by SAKTI on 10th Apr, 2008 -->
	{/if}
	
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
										{*include file="Goodsreceive/InventoryActions.tpl"*}

										<br>
										<!-- To display the Tag Clouds -->
										<div>
                                        {if $TAG_CLOUD_DISPLAY eq 'true'}

                                        	<!-- Tag cloud display -->
									        <table border=0 cellspacing=0 cellpadding=0 width=100% class="tagCloud">
									            <tr>
									                <td colspan="2">
									                    <div style="float:left; font-weight:bold;width: 100%;margin-bottom: 10px;">
									                        
									                        <img id="aibtbltag" src="themes/softed/images/tag-fill.png" style="vertical-align: middle;border: 0px solid #000000; width: 15px; height: 15px;" alt="Hide" title="Hide">
									                        <b style="font-family: PromptMedium; font-size: 12px;vertical-align: middle;">Add Tag</b>
									                    </div>
									                </td>
									            </tr>

									            <tr id="tbltag" style="display:block;padding-bottom:15px;" >
									                <td>
									                    <div id="tagdiv" style="display:visible;" class="custom-search">
									                        <form method="POST" action="javascript:void(0);" onsubmit="return tagvalidate();">

									                            <input type="text" class="form-control detailedViewTextBox custom-search-input" data-role="tagsinput" id="txtbox_tagfields" name="textbox_First Name" style="width:250px;margin-left:5px;"/ placeholder="พิมพ์แท็กที่ต้องการแล้วกด Enter ดูสิ!">
									                            <button name="button_tagfileds" type="submit" class="crmbutton small custom-search-botton" />&#10003;</button>
									                            <button name="button_clear" type="button" class="crmbutton small custom-search-botton-x" onclick="ClearTag();" />&#128473;</button>
									                        </form>
									                    </div>
									                </td>
									            </tr>
									            
									            <tr>
									            	<td><b style="font-family: PromptMedium; font-size: 12px;vertical-align: middle;">Tag ( <spam class="tagscount">0</spam> )</b></td>
									            </tr>
									            <tr >
									                <td class="tagCloudDisplay" id="tagCloudDisplay" valign="top" style="padding:1rem 0rem 1rem 0.5rem; line-height: 10px">
									                    <div id="tagfields">{$ALL_TAG}</div>
									                </td>
									            </tr>
									            <tr >
									            	<td>
									            		<span style="color: #ff7488;font-size: 12px;line-height: 3;font-family:PromptMedium, serif; display: none" class="error-tag" id="error-tag"><img src="themes/softed/images/warning-duotone-red.png" style="width: 20px;height: 20px;vertical-align: middle;margin-right: 5px;" >จำนวนแท็กไม่เกิน 10 แท็ก / 1 รายการ</span>
									            	</td>
									            </tr>
									           
									        </table>
									        <!-- End Tag cloud display -->
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
								<td class="dvtUnSelectedCellBottom" align=center nowrap><a href="index.php?action=TimelineList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_TIMELINE}</a></td>
								<td class="dvtTabCacheBottom" align="right" style="width:100%">

                                    {if $EDIT_DUPLICATE eq 'permitted' || $is_permmited eq true}
										<button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}">
											<img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
										</button>
									{/if}

									{if $EDIT_DUPLICATE eq 'permitted' || $is_permmited eq true && $MODULE neq 'Documents' && $MODULE neq 'Products'}
                                   		<button title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}">
                                   			<img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_DUPLICATE_BUTTON_LABEL}
                                   		</button>
                                    {/if}

									{if $DELETE eq 'permitted' && $MODULE neq 'Products' }
							
										<button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">
											<img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_DELETE_BUTTON_LABEL}
										</button>

									{/if}

									{if $privrecord neq ''}
			                        	<img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" accessKey="{$APP.LNK_LIST_PREVIOUS}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}&start={$privrecordstart}'" name="privrecord" value="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev.png'|@aicrm_imageurl:$THEME}" style="cursor: pointer;">
			                        {else}
			                        	<img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev.png'|@aicrm_imageurl:$THEME}" style="opacity: 0.5;cursor:not-allowed">
			                        {/if}

			                        {if $privrecord neq '' || $nextrecord neq ''}
			                       		<img align="absmiddle" title="{$APP.LBL_JUMP_BTN}" accessKey="{$APP.LBL_JUMP_BTN}" onclick="var obj = this;var lhref = getListOfRecordsbutton(obj, '{$MODULE}',{$ID},'{$CATEGORY}');" name="jumpBtnIdBottom" id="jumpBtnIdBottom" src="{'rec_jump.png'|@aicrm_imageurl:$THEME}" style="cursor: pointer;">
			                        {/if}

			                        {if $nextrecord neq ''}
			                        	<img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" accessKey="{$APP.LNK_LIST_NEXT}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}'" name="nextrecord" src="{'rec_next.png'|@aicrm_imageurl:$THEME}" style="cursor: pointer;">
			                        {else}
			                        	<img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" src="{'rec_next.png'|@aicrm_imageurl:$THEME}" style="opacity: 0.5; cursor:not-allowed">
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

                var numItems = jQuery('.tagit').length;

                jQuery(".tagscount").html(numItems);
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


