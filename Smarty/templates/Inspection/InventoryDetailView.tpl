{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*********************************************************************************/
-->*}
<script language="JavaScript" type="text/javascript" src="include/js/dtlviewajax.js"></script>
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>

<style>
{literal}
	.ui-resizable{
		position:absolute;
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
		eval(document.getElementById(anchorImgId)).src =  'themes/images/inactivate.gif';
		eval(document.getElementById(anchorImgId)).alt = 'Display';
		eval(document.getElementById(anchorImgId)).title = 'Display';
	}
	else
	{
		oObj.style.display = 'block';
		eval(document.getElementById(anchorImgId)).src =  'themes/images/activate.gif';
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
				//$("lstRecordLayout").innerHTML = sResponse;
				document.getElementById("lstRecordLayout").innerHTML=sResponse;
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

<span id="crmspanid" style="display:none;position:absolute;"  onmouseover="show('crmspanid');">
   <a class="link"  align="right" href="javascript:;">{$APP.LBL_EDIT_BUTTON}</a>
</span>
<div id="convertleaddiv" style="display:block;position:absolute;left:225px;top:150px;"></div>

<div id="lstRecordLayout" class="layerPopup" style="display:none;width:325px;height:300px;"></div>
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
		<div class="small" style="padding:10px" >

		<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
			<tr><td>
		  		{* Module Record numbering, used MOD_SEQ_ID instead of ID *}
		  		{assign var="USE_ID_VALUE" value=$MOD_SEQ_ID}
		  		{if $USE_ID_VALUE eq ''} {assign var="USE_ID_VALUE" value=$ID} {/if}
		 		<span class="dvHeaderText">[ {$USE_ID_VALUE} ] {$NAME} -  {$APP[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</span>&nbsp;&nbsp;&nbsp;<span class="small">{$UPDATEINFO}</span>&nbsp;<span id="vtbusy_info" style="display:none;" valign="bottom"><img src="{'vtbusy.gif'|@aicrm_imageurl:$THEME}" border="0"></span><span id="vtbusy_info" style="visibility:hidden;" valign="bottom"><img src="{'vtbusy.gif'|@aicrm_imageurl:$THEME}" border="0"></span>
		 	</td></tr>
		 </table>
		<br>

		<!-- Account details tabs -->
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
				<tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>

					<td class="dvtSelectedCell" align=center nowrap>{$APP[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</td>
					
					{if $SinglePane_View eq 'false'}
					<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a></td>
					{/if}
					<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=TimelineList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_TIMELINE}</a></td>
					<td class="dvtTabCache" align="right" style="width:100%">
                        	{php}
                            	global $campaignid,$viewlog;
                          	{/php}

                            {*{if $EDIT_DUPLICATE eq 'permitted'}*}
                            	{*<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small edit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}&nbsp;">&nbsp;*}
                            {*{/if}*}

                            {*{if $EDIT_DUPLICATE eq 'permitted' }*}
                            	{*<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small create" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}">&nbsp;*}
                            {*{/if}*}
                            {if $DELETE eq 'permitted'}
                            	<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">&nbsp;
                            {/if}


						{if $privrecord neq ''}
						<img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" accessKey="{$APP.LNK_LIST_PREVIOUS}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}&start={$privrecordstart}'" name="privrecord" value="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev.gif'|@aicrm_imageurl:$THEME}">&nbsp;
						{else}
						<img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev_disabled.gif'|@aicrm_imageurl:$THEME}">
						{/if}
						{if $privrecord neq '' || $nextrecord neq ''}
						<img align="absmiddle" title="{$APP.LBL_JUMP_BTN}" accessKey="{$APP.LBL_JUMP_BTN}" onclick="var obj = this;var lhref = getListOfRecords(obj, '{$MODULE}',{$ID},'{$CATEGORY}');" name="jumpBtnIdTop" id="jumpBtnIdTop" src="{'rec_jump.gif'|@aicrm_imageurl:$THEME}">&nbsp;
						{/if}
						{if $nextrecord neq ''}
						<img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" accessKey="{$APP.LNK_LIST_NEXT}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}&start={$nextrecordstart}'" name="nextrecord" src="{'rec_next.gif'|@aicrm_imageurl:$THEME}">&nbsp;
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
				 <table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace" style="border-bottom:0;">
				<tr>

					<td align=left>
					<!-- content cache -->


				<table border=0 cellspacing=0 cellpadding=0 width=100%>
                <tr>
					<td style="padding:5px">
					<!-- Command Buttons -->
				  	<table border=0 cellspacing=0 cellpadding=0 width=100%>
							 <!-- NOTE: We should avoid form-inside-form condition, which could happen when
								Singlepane view is enabled. -->
							 <form action="index.php" method="post" name="DetailView" id="form">
							{include file='DetailViewHidden.tpl'}

							  <!-- Start of File Include by SAKTI on 10th Apr, 2008 -->
							 {include_php file="./include/DetailViewBlockStatus.php"}
							 <!-- Start of File Include by SAKTI on 10th Apr, 2008 -->

							{foreach key=header item=detail from=$BLOCKS}

							<!-- Detailed View Code starts here-->
							<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
							<tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                             <td align=right>
                             </td>
                             </tr>




						{if $header neq 'Comments'}
						   <tr>{strip}
						     <td colspan=4 class="dvInnerHeader">

							<div style="float:left;font-weight:bold;"><div style="float:left;"><a href="javascript:showHideStatus('tbl{$header|replace:' ':''}','aid{$header|replace:' ':''}','{$IMAGE_PATH}');">
							{if $BLOCKINITIALSTATUS[$header] eq 1}
								<img id="aid{$header|replace:' ':''}" src="{'activate.gif'|@aicrm_imageurl:$THEME}" style="border: 0px solid #000000;" alt="Hide" title="Hide"/>
							{else}
							<img id="aid{$header|replace:' ':''}" src="{'inactivate.gif'|@aicrm_imageurl:$THEME}" style="border: 0px solid #000000;" alt="Display" title="Display"/>
							{/if}
								</a></div><b>&nbsp;
						        	{$header}
	  			     			</b></div>
						     </td>{/strip}
					             </tr>
						{/if}
							</table>

                        {if $header eq "Template" && $MODULE eq "Inspection"}
                            <!-- Questionnaire detail -->
                            <input type="hidden" name="data_template" id="data_template" class="data_template" value="{$DATA_TEMPLATE}" >
                            <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
                            <tr>
                                <td colspan=4>
                                   {include_php file='/plugin/inspection/answer.php'}
                                </td>
                            </tr>
                            </table>
                        {else}

						{if $header neq 'Comments'}
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
							   {assign var=keyaccess value=$data.notaccess}
							   {assign var=keycntimage value=$data.cntimage}
							   {assign var=keyadmin value=$data.isadmin}
							   {assign var=display_type value=$data.displaytype}
                           {if $label ne ''}
	                        {if $keycntimage ne ''}
				<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$keycntimage}</td>
				{elseif $keyid eq '71' || $keyid eq '72'}<!-- Currency symbol -->
					<td class="dvtCellLabel" align=right width=25%>{$label}<input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input> ({$keycursymb})</td>
				{else}
					<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$label}</td>
				{/if}

				{if $EDIT_PERMISSION eq 'yes' && $display_type neq '2'}
					{* Performance Optimization Control *}
					{if !empty($DETAILVIEW_AJAX_EDIT) }
                            {include file="DetailViewFields.tpl"}
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
						     </table>

							 </div>
		{/if}
	{/if}{* END block questionnaire detail*}
                     	                      </td>
					   </tr>
		<tr>
		 <td style="padding:10px">
			{/foreach}


                    {*-- End of Blocks--*}

			</td>
                </tr>

		<!-- Inventory - Product Details informations -->
		   <tr>
			{$ASSOCIATED_PRODUCTS}
		   </tr>
		<!-- Promotion - Product Details informations -->
		   <tr>
           		<td align="center">
				{$PROMOTION_TAB}
                </td>
		   </tr>
			</form>
			<!-- End the form related to detail view -->

			{if $SinglePane_View eq 'true' && $IS_REL_LIST eq 'true'}
				{include file= 'RelatedListNew.tpl'}
			{/if}
		</table>

		</td>
		<td width=22% valign=top style="border-left:1px dashed #cccccc;padding:13px">
            <!-- {if $MODULE eq 'Inspection'}
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <tr>
                        <td align="left">
                            <span class="genHeaderSmall">เอกสาร</span><br />
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding-left:10px;">
                            <a class="webMnu" href="#"
                               onclick="JavaScript: void window.open('Report/report_job.php?inspectionid={$ID}&type=inspection_and_preventive','Job','resizable=yes,left=20,top=10,width=990,height=800,toolbar=no,scrollbars=yes,menubar=no,location=no');">
                                <img src="{'actionGeneratePDF.gif'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle"
                                     border="0"/>รายงาน Inspection & Preventive Maintenance
                            </a>
                        </td>
                    </tr>
                </table>
            {/if}  -->


			{* vtlib customization: Custom links on the Detail view basic links *}
			{if $CUSTOM_LINKS && $CUSTOM_LINKS.DETAILVIEWBASIC}
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
				{foreach item=CUSTOMLINK from=$CUSTOM_LINKS.DETAILVIEWBASIC}
				<tr>
					<td align="left" style="padding-left:10px;">
						{assign var="customlink_href" value=$CUSTOMLINK->linkurl}
						{assign var="customlink_label" value=$CUSTOMLINK->linklabel}
						{if $customlink_label eq ''}
							{assign var="customlink_label" value=$customlink_href}
						{else}
							{* Pickup the translated label provided by the module *}
							{assign var="customlink_label" value=$customlink_label|@getTranslatedString:$CUSTOMLINK->module()}
						{/if}
						{if $CUSTOMLINK->linkicon}
						<a class="webMnu" href="{$customlink_href}"><img hspace=5 align="absmiddle" border=0 src="{$CUSTOMLINK->linkicon}"></a>
						{/if}
						<a class="webMnu" href="{$customlink_href}">{$customlink_label}</a>
					</td>
				</tr>
				{/foreach}
				</table>
			{/if}

			{* vtlib customization: Custom links on the Detail view *}
			{if $CUSTOM_LINKS}
				<br>
				{if isset($CUSTOM_LINKS.DETAILVIEW)}
					{assign var="CUSTOM_LINKS" value=$CUSTOM_LINKS.DETAILVIEW}
				{/if}
				{if !empty($CUSTOM_LINKS)}
					<table width="100%" border="0" cellpadding="5" cellspacing="0">
						<tr><td align="left" class="dvtUnSelectedCell dvtCellLabel">
							<a href="javascript:;" onmouseover="fnvshobj(this,'vtlib_customLinksLay');" onclick="fnvshobj(this,'vtlib_customLinksLay');"><b>{$APP.LBL_MORE} {$APP.LBL_ACTIONS} &#187;</b></a>
						</td></tr>
					</table>
					<br>
					<div style="display: none; left: 193px; top: 106px;width:155px; position:absolute;" id="vtlib_customLinksLay"
						onmouseout="fninvsh('vtlib_customLinksLay')" onmouseover="fnvshNrm('vtlib_customLinksLay')">
						<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr><td style="border-bottom: 1px solid rgb(204, 204, 204); padding: 5px;"><b>{$APP.LBL_MORE} {$APP.LBL_ACTIONS} &#187;</b></td></tr>
						<tr>
							<td>
								{foreach item=CUSTOMLINK from=$CUSTOM_LINKS}
									{assign var="customlink_href" value=$CUSTOMLINK->linkurl}
									{assign var="customlink_label" value=$CUSTOMLINK->linklabel}
									{if $customlink_label eq ''}
										{assign var="customlink_label" value=$customlink_href}
									{else}
										{* Pickup the translated label provided by the module *}
										{assign var="customlink_label" value=$customlink_label|@getTranslatedString:$CUSTOMLINK->module()}
									{/if}
									<a href="{$customlink_href}" class="drop_down">{$customlink_label}</a>
								{/foreach}
							</td>
						</tr>
						</table>
					</div>
				{/if}
			{/if}
		{* END *}
                <!-- Action links END -->




			<!-- Mail Merge-->
				<br>
				{if $MERGEBUTTON eq 'permitted'}
				<form action="index.php" method="post" name="TemplateMerge" id="form">
				<input type="hidden" name="module" value="{$MODULE}">
				<input type="hidden" name="parenttab" value="{$CATEGORY}">
				<input type="hidden" name="record" value="{$ID}">
				<input type="hidden" name="action">
  				<table border=0 cellspacing=0 cellpadding=0 width=100% class="rightMailMerge">
      				<tr>
      					   <td class="rightMailMergeHeader"><b>{$WORDTEMPLATEOPTIONS}</b></td>
      				</tr>
      				<tr style="height:25px">
					<td class="rightMailMergeContent">
						{if $TEMPLATECOUNT neq 0}
						<select name="mergefile">{foreach key=templid item=tempflname from=$TOPTIONS}<option value="{$templid}">{$tempflname}</option>{/foreach}</select>
                         <input class="crmbutton small create" value="{$APP.LBL_MERGE_BUTTON_LABEL}" onclick="this.form.action.value='Merge';" type="submit"></input>
						{else}
						<a href=index.php?module=Settings&action=upload&tempModule={$MODULE}&parenttab=Settings>{$APP.LBL_CREATE_MERGE_TEMPLATE}</a>
						{/if}
					</td>
      				</tr>
  				</table>
				</form>
				{/if}
			</td>
		</tr>
		</table>




		</div>
		<!-- PUBLIC CONTENTS STOPS-->
	</td>
</tr>
	<tr>
		<td>
			<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
				<tr>
					<td class="dvtTabCacheBottom" style="width:10px" nowrap>&nbsp;</td>

					<td class="dvtSelectedCellBottom" align=center nowrap>{$APP[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</td>
					
					{if $SinglePane_View eq 'false'}
					<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a></td>
					{/if}
					<td class="dvtUnSelectedCellBottom" align=center nowrap><a href="index.php?action=TimelineList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_TIMELINE}</a></td>
					<td class="dvtTabCacheBottom" align="right" style="width:100%">

                            {*{if $EDIT_DUPLICATE eq 'permitted' }*}
                            	{*<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small edit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}&nbsp;">&nbsp;*}
                            {*{/if}*}
                            {*{if $EDIT_DUPLICATE eq 'permitted' && $MODULE neq 'Documents' && $MODULE neq 'Questionnaire'}*}
                           		{*<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small create" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}">&nbsp;*}
                            {*{/if}*}
                            {if $DELETE eq 'permitted' }
                            	<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">&nbsp;
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
<!-- added for validation -->

</td>

	<td align=right valign=top><img src="{'showPanelTopRight.gif'|@aicrm_imageurl:$THEME}"></td>
</tr></table>
