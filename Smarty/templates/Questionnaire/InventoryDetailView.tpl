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
<!-- <link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
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
<script language="JavaScript" type="text/javascript" src="asset/js/bootstrap-tagsinput.min.js"></script> -->

<script type="text/javascript" src="include/js/reflection.js"></script>
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script> 
<script language="JavaScript" type="text/javascript" src="include/js/dtlviewajax.js"></script>
<!-- <script type="text/javascript" src="asset/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="asset/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
<link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css"> -->

<span id="crmspanid" style="display:none;position:absolute;"  onmouseover="show('crmspanid');">
   <a class="link"  align="right" href="javascript:;">{$APP.LBL_EDIT_BUTTON}</a>
</span>
<div id="convertleaddiv" style="display:block;position:absolute;left:225px;top:150px;"></div>

<script type="text/javascript">
     jQuery.noConflict();
</script>

<script>
{literal}
var gVTModule = '{$smarty.request.module|@vtlib_purify}';
function callConvertLeadDiv(id)
{
    new Ajax.Request(
        'index.php',
        {queue: {position: 'end', scope: 'command'},
            method: 'post',
            postBody: 'module=Leads&action=LeadsAjax&file=ConvertLead&record='+id,
            onComplete: function(response) {
                $("convertleaddiv").innerHTML=response.responseText;
                eval($("conv_leadcal").innerHTML);
            }
        }
    );
}

function showHideStatus(sId,anchorImgId,sImagePath)
{
    oObj = eval(document.getElementById(sId));
    if(oObj.style.display == 'block')
    {
        oObj.style.display = 'none';
        eval(document.getElementById(anchorImgId)).src =  'themes/images/slidedown_b2.png';
        eval(document.getElementById(anchorImgId)).alt = 'Display';
        eval(document.getElementById(anchorImgId)).title = 'Display';
    }
    else
    {
        oObj.style.display = 'block';
        // eval(document.getElementById(anchorImgId)).src = 'themes/images/activate.gif';
        eval(document.getElementById(anchorImgId)).src = 'themes/softed/images/slidedown_b.png';
        eval(document.getElementById(anchorImgId)).alt = 'Hide';
        eval(document.getElementById(anchorImgId)).title = 'Hide';
    }
}
<!-- End Of Code modified by SAKTI on 10th Apr, 2008 -->

<!-- Start of code added by SAKTI on 16th Jun, 2008 -->
function setCoOrdinate(elemId){
    oBtnObj = document.getElementById(elemId);
    var tagName = document.getElementById('lstRecordLayout');
    leftpos  = 0;
    toppos = 0;
    aTag = oBtnObj;
    do{
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
<!-- End of code added by SAKTI on 16th Jun, 2008 -->
{/literal}
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

//confirm_send data
function confirm_send(msg,id){
        
        var dlg = jQuery.messager.confirm({
        title: 'Confirm',
        msg: msg,
        buttons:[{
            text: 'OK',
            onClick: function(){
                jQuery.ajax({
                   type: "POST",
                   url: "account_approve.php",
                   cache: false,
                   data: {crmid:id},
                   dataType: "json",
                   success: function(returndate){
                        if(returndate['status'] == true){
                            alert(returndate['msg']);
                            location.reload();
                        }else{
                            alert(returndate['msg']);
                            dlg.dialog('destroy');
                        }
                    }
                 });                            
            }
        },{
            text: 'CANCEL',
            onClick: function(){
                dlg.dialog('destroy')
            }
        }]
    });

}

function confirm_send2(msg,id){
        
        var dlg = jQuery.messager.confirm({
        title: 'Confirm',
        msg: msg,
        buttons:[{
            text: 'OK',
            onClick: function(){
                jQuery.ajax({
                   type: "POST",
                   url: "account_rms.php",
                   cache: false,
                   data: {crmid:id},
                   dataType: "json",
                   success: function(returndata){

                    if(returndata.Type == "S"){
                        //console.log('33');
                        dlg.dialog('destroy');
                        jQuery.messager.alert({
                            title: 'Information',
                            msg:returndata.Message,
                            fn: function(){
                                location.reload();
                            }
                        });
                       
                    }else{
                        
                        dlg.dialog('destroy');
                        jQuery.messager.alert('Message',returndata.Message,'info');
                    

                    }


                      
                    }
                   
                 });                            
            }
        },{
            text: 'CANCEL',
            onClick: function(){
                dlg.dialog('destroy')
            }
        }]
    });
}
{/literal}


//Added to send a file, in Documents module, as an attachment in an email
function sendfile_email()
{ldelim}
    filename = $('dldfilename').value;

    document.DetailView.submit();
    
    OpenCompose(filename,'Documents');
    
{rdelim}

</script>
<div id="dialog" style="display:none;background-color: #FFF">Dialog Content.</div>
<div id="lstRecordLayout" class="layerPopup" style="display:none;width:325px;height:300px;"></div>


<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
	<td>
		{include file='Buttons_List1.tpl'}
<!-- Contents -->
<table border=0 cellspacing=0 cellpadding=0 width=99% align=center>
<tr>
	<td valign=top><img src="{'showPanelTopLeft.gif'|@aicrm_imageurl:$THEME}"></td>
	<td class="showPanelBg" valign=top width=100%>
		<!-- PUBLIC CONTENTS STARTS-->
		<div class="small" style="padding:10px" >
		
		<table border="0" cellpadding="0" cellspacing="0" width="95%">
            <tr style="line-height: 20px;">
                <td style="padding-top: 10px;">
                    {* Module Record numbering, used MOD_SEQ_ID instead of ID *}
                    {assign var="USE_ID_VALUE" value=$MOD_SEQ_ID}
                    {if $USE_ID_VALUE eq ''} {assign var="USE_ID_VALUE" value=$ID} {/if}
                    <span class="dvHeaderText">[ {$USE_ID_VALUE} ] {$NAME} -  {$APP[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</span>&nbsp;&nbsp;&nbsp;<span class="small" style="font-family: PromptMedium; color: #A9A9A9;">{$UPDATEINFO}</span>&nbsp;<span id="vtbusy_info" style="display:none;" valign="bottom"><img src="{'vtbusy.gif'|@aicrm_imageurl:$THEME}" border="0"></span><span id="vtbusy_info" style="visibility:hidden;" valign="bottom"><img src="{'vtbusy.gif'|@aicrm_imageurl:$THEME}" border="0"></span>
                </td>
            </tr>
        </table>
        <hr style="border: 1px solid #F7F7F7;">
        <br>
		
		<!-- Account details tabs -->
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
                       
                        {if $EDIT_DUPLICATE eq 'permitted' }
                        
                        <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}">
                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}                                         
                        </button>

                        <button title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" name="Duplicate" style="width: 95px;">
                            <img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_DUPLICATE_BUTTON_LABEL}
                        </button>

                        {/if}
                        
                         {if $DELETE eq 'permitted'}
                            <button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete btndelete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" name="Delete" style="width: 75px;">
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
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace" style="border-bottom:0;">
				<tr>
					<td align=left>
					<!-- content cache -->

				<table border=0 cellspacing=0 cellpadding=0 width=100%>
                <tr>
					<td style="padding:10px">
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

						

                        {if $header eq $MOD.LBL_COMMENTS || $header eq $MOD.LBL_COMMENT_INFORMATION }
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
						   <tr>{strip}
						     <td colspan=4 class="dvInnerHeader">

							<!-- <div style="float:left;font-weight:bold;">
								<div style="float:left;">
									<a href="javascript:showHideStatus('tbl{$header|replace:' ':''}','aid{$header|replace:' ':''}','{$IMAGE_PATH}');"> -->
							<div style="float:left; font-weight:bold; margin-left: 10px;">
                                <div style="float:left;">
                                    <a href="javascript:showHideStatus('tbl{$header|replace:' ':''}','aid{$header|replace:' ':''}','{$IMAGE_PATH}');">

							{if $BLOCKINITIALSTATUS[$header] eq 1}
								<!-- <img id="aid{$header|replace:' ':''}" src="{'activate.gif'|@aicrm_imageurl:$THEME}" style="border: 0px solid #000000;" alt="Hide" title="Hide"/> -->
								<img id="aid{$header|replace:' ':''}" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide" />
							{else}
							<!-- <img id="aid{$header|replace:' ':''}" src="{'inactivate.gif'|@aicrm_imageurl:$THEME}" style="border: 0px solid #000000;" alt="Display" title="Display"/> -->
							<img id="aid{$header|replace:' ':''}" src="themes/softed/images/slidedown_b2.png" style="border: 0px solid #000000;" alt="Display" title="Display"/>
							{/if}
								
						     	</a></div><b style="font-family: PromptMedium; font-size: 12px;">&nbsp;&nbsp;&nbsp;{$header}</b></div></td>{/strip}
					             </tr>
						{/if}
							</table>
                            
                        {if $header eq "Answer Information" && $MODULE eq "Questionnaire"}
                        	<div style="width: auto; display: block;" id="tblTemplate">
                            <!-- Questionnaire detail -->
                            
                            <input type="hidden" name="data_template" id="data_template" class="data_template" value="{$DATA_TEMPLATE}" >
                            <input type="hidden" name="data_answer" id="data_answer" class="data_answer" value="{$DATA_ANSWER}" >
                            <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
                            <tr>
                                <td colspan=4>
                                   {include_php file='modules/Questionnaire/survey/detail.php'}
                                </td>
                            </tr>
                            </table>
                            </div>
                        {elseif $header=="Comment Information"}

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
		<tr>                                                                                                               <td style="padding:10px">
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
                    <td class="dvtTabCacheBottom" style="width:10px" nowrap>&nbsp;</td>

                    <td class="dvtSelectedCellBottom" align=center nowrap>{$APP[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</td>
                    <!-- <td class="dvtTabCacheBottom" style="width:10px">&nbsp;</td> -->
					{if $SinglePane_View eq 'false'}
					<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a></td>
					{/if}
                    <td class="dvtSelectedCellBottom" align=center nowrap><a href="index.php?action=TimelineList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_TIMELINE}</a></td>
					<td class="dvtTabCacheBottom" align="right" style="width:100%">
					
                        {if $EDIT_DUPLICATE eq 'permitted' && $MODULE neq 'Documents'}
                            <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}">
                                <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}                                         
                            </button>

                            <button title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" name="Duplicate" style="width: 95px;">
                                <img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_DUPLICATE_BUTTON_LABEL}
                            </button>
                       
                        {/if}

                        {if $DELETE eq 'permitted'}
                            <button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete btndelete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" name="Delete" style="width: 75px;">
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
                        <img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" accessKey="{$APP.LNK_LIST_NEXT}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}&start={$nextrecordstart}'" name="nextrecord" src="{'rec_next.png'|@aicrm_imageurl:$THEME}" style="cursor: pointer;">
                        {else}
                        <img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" src="{'rec_next.png'|@aicrm_imageurl:$THEME}" style="opacity: 0.5;cursor:not-allowed">
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
