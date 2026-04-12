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

<span id="crmspanid" style="display:none;position:absolute;" onmouseover="show('crmspanid');">
   <a class="link"  align="right" href="javascript:;">{$APP.LBL_EDIT_BUTTON}</a>
</span>
<div id="convertleaddiv" style="display:block;position:absolute;left:600px;top:200px;"></div>
<script type="text/javascript">
     jQuery.noConflict();
</script>
{if $MODULE eq 'Products'}
<script>
    {literal}
    function genarate_serial(action, id) {
        var msg = '';
        model_width = '650';
        model_height = '520';

        msg = 'Create Serial';
        url = 'get_genarate_serial.php?crmid=' + id + '&action='+action;
       

        jQuery('#dialog').window({
            title: msg,
            width: model_width,
            height: model_height,
            closed: false,
            cache: false,
            href: url,
            modal: true
        });


    }//function

    {/literal}
</script>
{/if}
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

function openSettingEmailDialog(crmID, Module, tbtEmailLog){
    
    /*if(tbtEmailLog === 'no'){
        alert('ยังไม่มีรายการ Account ใน moreinfo');
        return false;
    }*/

    /*jQuery.post('emailSettingDialog.php', {crmID:crmID, Module:Module}, function(html){
        jQuery('#dialog').window({
            title: 'ตั้งเวลาส่ง',
            width:500,
            height:250,
            modal:true,
            maximizable: false,
            minimizable: false
        }).html(html);
    })*/

    var msg = '';
    url = 'plugin/Smartquestionnaire/setup_datetime.php?crmid='+crmID;
    jQuery('#dialog').window({
        title: 'ตั้งเวลาส่ง',
        width: 560,
        height: 180,
        closed: false,
        cache: false,
        href: url,
        modal: true,
        minimizable:false,
        maximizable:false,
        collapsible:false,
    });

}

function saveSettingEmail(crmID, Module){
    var type = jQuery(`input[name="type"]:checked`).val();
    if(type === '') return false;
    var date = '';
    var time = '';
    if(type === '2'){
        date = jQuery('#date').val();
        time = jQuery('#time').val();

        if(date === '' || time === ''){
            alert('กรุณาระบบ วัน เวลา');
            return false;
        }
    }

    jQuery.post('emailSettingSave.php', {Module:Module, crmID:crmID, type:type, date:date, time:time}, function(rs){
        
        jQuery('#settingemail-dialog').window('close');

        if(type === '1'){
            switch(Module){
                case 'Surveysmartemail':
                    window.open('sendemail_Surveysmartemail_setup.php?crmid='+crmID,'Application','resizable=0,left=200,top=50,width=630,height=390,toolbar=no,scrollbars=no,menubar=no,location=no')
                    break;
                case 'Smartquestionnaire':
                    window.open('sendemail_SmartquestionnaireCampaigns_setup.php?crmid='+crmID,'Application','resizable=0,left=200,top=50,width=630,height=390,toolbar=no,scrollbars=no,menubar=no,location=no')
                    break;
            }
        }else if(type === '2'){
            window.location.reload();
        }
    })
}

function exportDataSmartQuestionnaire(crmID, questionnairetemplateid)
{
     jQuery.messager.progress({
         title: 'Please waiting',
         msg: 'Loading ...',
         text: 'LOADING'
     });

    jQuery.post('smartQuestionnaireExport.php', {crmID:crmID, questionnairetemplateid:questionnairetemplateid}, function(rs){
        jQuery("body").append(`<iframe src='${rs.filePath}' style='display: none;' ></iframe>`);
        // console.log(rs)
        jQuery.messager.progress('close');
    },'json')
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
    if(trim(document.getElementById('txtbox_tagfields').value) != ''){ldelim}
        //jQuery("tagCloudDisplay").style.border='0px';
        //jQuery("error-tag").style.display='none';
        jQuery('.tagCloudDisplay').css('border','0px');
        jQuery('.error-tag').css('display','none');

        SaveTag('txtbox_tagfields','{$ID}','{$MODULE}');

        jQuery('.bootstrap-tagsinput').css('border-color','#ccc');
    	/*jQuery( ".tag" ).remove();
    	jQuery('#txtbox_tagfields').tagsinput('removeAll');*/
    {rdelim}
    else
    {ldelim}
        jQuery('.bootstrap-tagsinput').css('border-color','red');
        /*alert("{$APP.PLEASE_ENTER_TAG}");*/
        return false;
    {rdelim}
{rdelim}
function ClearTag(){ldelim}
	jQuery('#txtbox_tagfields').tagsinput('removeAll');
{rdelim}
function keyvalue(){ldelim}
	console.log(123);
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

function setup_announcement(id){
    var msg = '';
    url = 'plugin/Announcement/setup_datetime.php?crmid='+id;
    jQuery('#dialog').window({
        title: 'ตั้งเวลาส่ง',
        width: 560,
        height: 180,
        closed: false,
        cache: false,
        href: url,
        modal: true,
        minimizable:false,
        maximizable:false,
        collapsible:false,
    });
    
}

function setup_sms(id){
    var msg = '';
    url = 'plugin/Smartsms/setup_datetime.php?crmid='+id;
    jQuery('#dialog').window({
        title: 'ตั้งเวลาส่ง',
        width: 560,
        height: 180,
        closed: false,
        cache: false,
        href: url,
        modal: true,
        minimizable:false,
        maximizable:false,
        collapsible:false,
    });
    
}

function setup_email(id){
    var msg = '';
    url = 'plugin/Smartemail/setup_datetime.php?crmid='+id;
    jQuery('#dialog').window({
        title: 'ตั้งเวลาส่ง',
        width: 560,
        height: 180,
        closed: false,
        cache: false,
        href: url,
        modal: true,
        minimizable:false,
        maximizable:false,
        collapsible:false,
    });
    
}

function adjust_stock(id){
    var msg = '';
    url = 'plugin/Premuimproduct/adjust_stock.php?crmid='+id;
    jQuery('#dialog').window({
        title: 'เพิ่ม/ลด สินค้า',
        width: 560,
        height: 180,
        closed: false,
        cache: false,
        href: url,
        modal: true,
        minimizable:false,
        maximizable:false,
        collapsible:false,
    });
    
}

function openVoucherDialog(promotionID, userID){
    var msg = '';
    url = 'plugin/Promotionvoucher/formCreate.php?crmid='+promotionID;
    jQuery('#dialog').window({
        title: 'Generate Voucher',
        width: 500,
        height: 650,
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

function sendAccountERP(crmID){
    jQuery.messager.progress({
        title: 'Please waiting',
        msg: 'Loading ...',
        text: 'LOADING'
    });

    jQuery.post('index.php', {
        module: 'Accounts',
        action: 'AccountsAjax',
        ajax: true,
        file: 'SendERP',
        record: crmID
    }, function(rs){
        jQuery.messager.progress('close')
        var title = rs.title;
        var message = rs.msg
        jQuery.messager.alert(title, message, function(r){
            if (r){
                
            }
        });
        console.log(rs)
    }, 'json')
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
{php}
    global $converted;
{/php}
<div id="lstRecordLayout" class="layerPopup" style="display:none;width:325px;height:300px;"></div>

{if $MODULE eq 'Accounts' || $MODULE eq 'Contacts' || $MODULE neq 'Leads'}
        {if $MODULE eq 'Accounts'}
            {assign var=address1 value='$MOD.LBL_BILLING_ADDRESS'}
            {assign var=address2 value='$MOD.LBL_SHIPPING_ADDRESS'}
        {/if}
        {if $MODULE eq 'Contacts'}
            {assign var=address1 value='$MOD.LBL_PRIMARY_ADDRESS'}
            {assign var=address2 value='$MOD.LBL_ALTERNATE_ADDRESS'}
        {/if}
        <div id="locateMap" onMouseOut="fninvsh('locateMap')" onMouseOver="fnvshNrm('locateMap')">
                <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                                <td>
                                {if $MODULE eq 'Accounts'}
                                        <!--<a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Main' );" class="calMnu">{$MOD.LBL_BILLING_ADDRESS}</a>
                                        <a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Other' );" class="calMnu">{$MOD.LBL_SHIPPING_ADDRESS}</a>-->
                               {/if}

                               {if $MODULE eq 'Contacts'}
                                <!--<a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Main' );" class="calMnu">{$MOD.LBL_PRIMARY_ADDRESS}</a>
                                        <a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Other' );" class="calMnu">{$MOD.LBL_ALTERNATE_ADDRESS}</a>-->
                               {/if}

                                         </td>
                        </tr>
                </table>
        </div>
{/if}

<div id="dialog" style="display:none;background-color: #FFF">Dialog Content.</div>
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
                            {php}
                                global $campaignid,$viewlog;
                            {/php}

                        {if $MODULE eq 'Accounts'}
                            {if $approved_status neq 'อนุมัติ'}

                                {if $is_admin eq 1 || $roleid eq 'H2' || $roleid eq 'H738'}
                                    <input class="crmbutton small edit" type="button" value="Approved" onclick="confirm_send('Approved',{$ID});" />&nbsp;
                                {/if}
                            {/if}

                            <input class="crmbutton small save" type="button" value="Send to ERP" onclick="sendAccountERP({$ID})" />&nbsp;
                        {/if}

                        {if $MODULE eq 'Claim'}
                            <input class="crmbutton small edit" type="button" value="Send to ERP" onclick="" />&nbsp;
                        {/if}

                        {if $MODULE eq 'HelpDesk' }

                            {if $flagassign eq true && $ticketstatus neq '--None--' && $ticketstatus neq 'ยกเลิก' && $ticketstatus neq 'ปิดงาน'}
                                <input class="crmbutton small edit" type="button" value="ส่งต่อ" onclick="JavaScript: void window.open('case_transfer.php?casestatus=ส่งต่อ&crmid={$ID}','Case','resizable=yes,left=350,top=50,width=500,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $flagassign eq true && ($ticketstatus eq 'เปิด' || $ticketstatus eq 'ระหว่างดำเนินการ' ) }
                                <input class="crmbutton small edit" type="button" value="รับงาน" onclick="JavaScript: void window.open('case_status.php?casestatus=รับงาน&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=460,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {*if $flagassign eq true && ($tickettype neq 'ร้องเรียน' && $tickettype neq 'ร้องขอ') && ($ticketstatus eq 'Wait For Response' || $ticketstatus eq 'Acknowledge Job' ) }
                                <input class="crmbutton small edit" type="button" value="เช็คอิน" onclick="JavaScript: void window.open('case_status.php?casestatus=เช็คอิน&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if*}

                            {if $flagassign eq true && ( $ticketstatus eq 'Acknowledge Job' || $ticketstatus eq 'เช็คอิน')}
                                <input class="crmbutton small edit" type="button" value="อยู่ระหว่างดำเนินการ" onclick="JavaScript: void window.open('case_status.php?casestatus=อยู่ระหว่างดำเนินการ&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $tickettype neq 'ร้องขอ' && $flagassign eq true && $ticketstatus neq '--None--' && $ticketstatus neq 'ยกเลิก' && $ticketstatus neq 'ปิดงาน' }
                                <input class="crmbutton small edit" type="button" value="ปิดงาน" onclick="JavaScript: void window.open('case_status.php?casestatus=ปิดงาน&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $tickettype eq 'ร้องขอ' && $flagassign eq true && $ticketstatus neq '--None--' && $ticketstatus neq 'ยกเลิก' && $ticketstatus neq 'ปิดงาน' &&  $ticketstatus neq 'อนุมัติเรื่องร้องขอ' &&  $ticketstatus neq 'ไม่อนุมัติเรื่องร้องขอ'}
                                
                                <input class="crmbutton small edit" type="button" value="อนุมัติเรื่องร้องขอ" onclick="JavaScript: void window.open('case_status.php?casestatus=อนุมัติเรื่องร้องขอ&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                                
                                <input class="crmbutton small edit" type="button" value="ไม่อนุมัติเรื่องร้องขอ" onclick="JavaScript: void window.open('case_status.php?casestatus=ไม่อนุมัติเรื่องร้องขอ&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')" />&nbsp;
                            {/if}

                            {if $flagassign eq true && $ticketstatus neq '--None--' && $ticketstatus neq 'ยกเลิก' && $ticketstatus neq 'ปิดงาน'   &&  $ticketstatus neq 'อนุมัติเรื่องร้องขอ' &&  $ticketstatus neq 'ไม่อนุมัติเรื่องร้องขอ'}
                                <input class="crmbutton small edit" type="button" value="ยกเลิก" onclick="JavaScript: void window.open('case_status.php?casestatus=ยกเลิก&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                        {/if}

                        {if $MODULE eq 'Servicerequest' }

                            {if $flagassign eq true && $service_request_status neq '--None--' && $service_request_status neq 'ปิดงาน' && $service_request_status neq 'ยกเลิก'}
                                <input class="crmbutton small edit" type="button" value="ส่งต่อ" onclick="JavaScript: void window.open('servicerequest_transfer.php?service_request_status=ส่งต่อ&crmid={$ID}','Case','resizable=yes,left=350,top=50,width=500,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $flagassign eq true && ($service_request_status eq 'เปิดงาน') }
                                <input class="crmbutton small edit" type="button" value="รับงาน" onclick="JavaScript: void window.open('servicerequest_status.php?service_request_status=อยู่ระหว่างดำเนินการ&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=460,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {*if $flagassign eq true && $service_request_status neq '--None--' && $service_request_status neq 'ปิดงาน' && $service_request_status neq 'ยกเลิก' }
                                <input class="crmbutton small edit" type="button" value="ปิดงาน" onclick="JavaScript: void window.open('servicerequest_status.php?service_request_status=ปิดงาน&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if*}

                            {if $flagassign eq true && $service_request_status neq '--None--' && $service_request_status neq 'ปิดงาน' && $service_request_status neq 'ยกเลิก'}
                                <input class="crmbutton small edit" type="button" value="ยกเลิก" onclick="JavaScript: void window.open('servicerequest_status.php?service_request_status=ยกเลิก&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                        {/if}

                        {if $MODULE eq 'Job' }

                            {if $flagassign eq true && $job_status neq '--None--' && $job_status neq 'ปิดงาน' && $job_status neq 'ยกเลิกงาน'}
                                <input class="crmbutton small edit" type="button" value="ส่งต่อ" onclick="JavaScript: void window.open('job_transfer.php?job_status=ส่งต่อ&crmid={$ID}','Case','resizable=yes,left=350,top=50,width=500,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $flagassign eq true && ($job_status eq 'เปิดงาน') }
                                <input class="crmbutton small edit" type="button" value="รับงาน" onclick="JavaScript: void window.open('job_status.php?job_status=อยู่ระหว่างดำเนินงาน&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=460,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $flagassign eq true && $job_status neq '--None--' && $job_status neq 'ปิดงาน' && $job_status neq 'ยกเลิกงาน' }
                                <input class="crmbutton small edit" type="button" value="ปิดงาน" onclick="JavaScript: void window.open('job_status.php?job_status=ปิดงาน&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $flagassign eq true && $job_status neq '--None--' && $job_status neq 'ปิดงาน' && $job_status neq 'ยกเลิกงาน'}
                                <input class="crmbutton small edit" type="button" value="ยกเลิก" onclick="JavaScript: void window.open('job_status.php?job_status=ยกเลิก&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                        {/if}
                        
                        {if $MODULE eq 'Announcement'}

                            {if $announcement_status eq 'Prepare'}
                                {if $count_user neq '0'}
                                <button title="Setup" accesskey="E" class="crmbutton small success" onclick="setup_announcement({$ID})" type="button" name="setup_announcement" id="setup_announcement" style="width: auto;">
                                    <img src="themes/softed/images/gear-bold.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Setup
                                </button>
                                {/if}
                            {/if}
                            <button title="View" accesskey="E" class="crmbutton small edit" onclick="JavaScript: void window.open('Announcement/report.php?crmid={php} echo $_REQUEST['record'];{/php}','Application','resizable=0,left=200,top=50,width=1100,height=860,toolbar=no,scrollbars=yes,menubar=no,location=no')" type="button" name="viewannouncement" style="width: auto;">
                                <img src="themes/softed/images/bar-chart.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Report
                            </button>
                           
                        {/if}

                        {if $MODULE eq 'SmartSms'}
                            {if $sms_status eq 'Prepare'}
                                {if $count_sms neq '0'}
                                    <button title="Setup SMS" accesskey="E" class="crmbutton small success" onclick="setup_sms({$ID})" type="button" name="setup_sms" id="setup_sms" style="width: auto;">
                                        <img src="themes/softed/images/gear-bold.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Setup SMS
                                    </button>
                                {/if}
                            {/if}

                            {if $sms_status eq 'Complete' && $setup_sms eq "1" && $send_sms eq "1" && $resend neq "0"}
                                <input class="crmbutton btnsendmail" type="button" value="Resend" onclick="JavaScript: void window.open('update_resend.php?crmid={php} echo $_REQUEST['record'];{/php}','Application','resizable=0,left=200,top=50,width=630,height=390,toolbar=no,scrollbars=no,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            <button title="Report" accesskey="E" class="crmbutton small edit" onclick="JavaScript: void window.open('SMS/view_send_sms.php?crmid={php} echo $_REQUEST['record'];{/php}','Application','resizable=0,left=200,top=50,width=1100,height=860,toolbar=no,scrollbars=yes,menubar=no,location=no')" type="button" name="viewsms" style="width: auto;">
                                <img src="themes/softed/images/bar-chart.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Report
                            </button>
                            
                        {/if}

                        {if $MODULE eq 'Smartemail'} 
                            {if $email_status eq 'Prepare'}
                                {if $count_email neq '0'}
                                    <button title="Setup Email" accesskey="E" class="crmbutton small success" onclick="setup_email({$ID})" type="button" name="setup_email" id="setup_email" style="width: auto;">
                                        <img src="themes/softed/images/gear-bold.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Setup Email
                                    </button>
                                {/if}
                            {/if}

                                <button title="Report" accesskey="E" class="crmbutton small edit" onclick="JavaScript: void window.open('EDM/webmail/home-1.php?crmid={php} echo $_REQUEST['record'];{/php}','Application','resizable=0,left=200,top=50,width=1100,height=860,toolbar=no,scrollbars=yes,menubar=no,location=no')" type="button" name="viewemail" style="width: auto;">
                                    <img src="themes/softed/images/bar-chart.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Report
                                </button>
                        {/if}

                        {if $MODULE eq 'Smartquestionnaire'}
                            {php}
                                global $campaignid,$prepare_status,$send_email,$email_status,$count_email,$smartquestionnaire_status;
                            {/php}

                            {php} if($prepare_status=="0" && $count_email != "0"){ {/php}
                                
                                {if $smartquestionnaire_status eq 'Prepare'}
                                    <button title="Setup Email" accesskey="E" class="crmbutton small success" onclick="openSettingEmailDialog('{$ID}', '{$MODULE}', '{$tbtEmailLog}')" type="button" name="prepare_status" id="prepare_status" style="width: auto;">
                                        <img src="themes/softed/images/gear-bold.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Setup Email
                                    </button>
                                {/if}
                            {php} } {/php}
                            {if $questionnairetemplateid neq ''}
								<button title="Export" class="crmbutton small edit" type="button" onclick="exportDataSmartQuestionnaire('{$ID}', '{$questionnairetemplateid}')">
                                	<img src="themes/softed/images/export_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Export
                            	</button>&nbsp;
								
								<!-- <button class="crmbutton btncountview" type="button" onclick="exportDataSmartQuestionnaire('{$ID}', '{$questionnairetemplateid}')">Export</button>&nbsp; -->
							{/if}
                            <button title="Preview" class="crmbutton small edit" type="button" onclick="javascript:void window.open('survey/home/questionnaire_template_view/{$questionnairetemplateid}/0','Preview Questionnairetemplate','resizable=1,left=200,top=50,width=1100,height=800,toolbar=no,scrollbars=yes,menubar=no,location=no')">
                                <img src="themes/softed/images/file-search.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Preview
                            </button>&nbsp;
                            <button title="Report" accesskey="E" class="crmbutton small edit" onclick="JavaScript: void window.open('EDM/webmail/view_smart_questionnaire_email.php?campaing={php} echo $_REQUEST['record'];{/php}','Application','resizable=1,left=200,top=50,width=1100,height=600,toolbar=no,scrollbars=yes,menubar=no,location=no')" type="button" name="viewemail" style="width: auto;">
                                <img src="themes/softed/images/bar-chart.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Report
                            </button>
                              
                        {/if}
                        
                        {if $MODULE eq 'Promotionvoucher'}
                            <button title="Generate Voucher" accesskey="E" class="crmbutton small success" onclick="openVoucherDialog('{$ID}', '{$USERID}')" type="button" name="generate" id="generate" style="width: auto;">
                                <img src="themes/softed/images/gear-bold.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Generate Voucher
                            </button>
                        {/if}

                        {if $MODULE eq 'Premuimproduct'}
                            <button title="Adjust Stock" accesskey="E" class="crmbutton small success" onclick="adjust_stock({$ID})" type="button" name="adjust_stock" id="adjust_stock" style="width: auto;">
                                <img src="themes/softed/images/gear-bold.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Adjust Stock
                            </button>            
                        {/if}
                    	
                        {*{if $MODULE eq 'Products'}
                            <button title="ดูรูป"class="crmbutton small save" onclick="window.open('https://umisky.umi-tiles.com/{$material}','_blank');" type="button" name="Openimage" style="width: 70px;">
                                <img src="themes/softed/images/picture_w.png" border="0" style="width: 17px; height: 17px; vertical-align: middle;">&nbsp;&nbsp;ดูรูป
                            </button>
                        {/if}*}

                        {if $EDIT_DUPLICATE eq 'permitted'}
                        	<!-- DUPLICATE To Premuim Product -->
                        	{*{if $MODULE eq 'Products'}
                        		<button title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small save" onclick="window.location.href ='index.php?module=Premuimproduct&action=EditView&return_action=DetailView&parenttab=Inventory&return_module={$MODULE}&productid={$ID}'" type="button" name="Duplicate" style="width: 170px;">
                                    <img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;Duplicate To Premuim
                                </button>
                        	{/if}*}
                        	<!-- DUPLICATE To Premuim Product -->

                            {*{if $MODULE eq 'Products'}
                        		<button title="Create Serial" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small edit" onclick="genarate_serial('CreateSerial','{$ID}');" type="button" name="Create Serial">
                                    <img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;Create Serial
                                </button>
                        	{/if}*}

                            {if $MODULE eq 'HelpDesk'}
                                {if $flagassign eq true && $ticketstatus neq 'ยกเลิก' && $ticketstatus neq 'ปิดงาน' &&  $ticketstatus neq 'อนุมัติเรื่องร้องขอ' &&  $ticketstatus neq 'ไม่อนุมัติเรื่องร้องขอ'}
                                    <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}">
                                        <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                    </button>
                                {/if}
                            {elseif $MODULE eq 'Servicerequest'}
                                {if $flagassign eq true && $service_request_status neq 'ปิดงาน' && $service_request_status neq 'ยกเลิก'}
                                    <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}" >
                                        <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                    </button>
                                {/if}
                            {elseif $MODULE eq 'Job'}
                                {if $flagassign eq true && $job_status neq 'ปิดงาน' && $job_status neq 'ยกเลิกงาน'}
                                    <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}" >
                                        <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                    </button>
                                {/if}
                            
                            {else}
                                {php}
                                    if($converted=="1"){
                                {/php}
                                {php}
                                    }else{
                                {/php}
                                    
                                    {if $MODULE eq 'Announcement'}
                                        {if $announcement_status eq 'Prepare'}
                                         <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                         </button>
                                        {/if}
                                    {elseif $MODULE eq 'Smartquestionnaire'}
                                        {if $smartquestionnaire_status eq 'Prepare'}
                                         <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                         </button>
                                        {/if}
                                    {elseif $MODULE eq 'SmartSms'}
                                        {if $sms_status eq 'Prepare'}
                                         <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                         </button>
                                        {/if}
                                    {elseif $MODULE eq 'Smartemail'}
                                        {if $email_status eq 'Prepare'}
                                         <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                         </button>
                                        {/if}
                                    {elseif $MODULE eq 'Voucher'}
                                        <!-- Not Edit All user -->
                                    {else}
                                         <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                         </button>
                                    {/if}
                                {php}
                                    }
                                {/php}
                            {/if}
                        {/if}

                        {if $EDIT_DUPLICATE eq 'permitted' && $MODULE neq 'Documents' && $MODULE neq 'Opportunity' && $MODULE neq 'Voucher'}
                            {php}
                                if($converted=="1"){
                            {/php}
                            {php}
                                }else{
                            {/php}
                                
                                <button title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" name="Duplicate" style="width: 95px;">
                                    <img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_DUPLICATE_BUTTON_LABEL}
                                </button>
                            {php}
                            }
                            {/php}
                        {/if}

                        {if $DELETE eq 'permitted'}

                            {if $MODULE eq 'HelpDesk'}
                                {if $flagassign eq true && $ticketstatus neq 'ยกเลิก' && $ticketstatus neq 'ปิดงาน' &&  $ticketstatus neq 'อนุมัติเรื่องร้องขอ' &&  $ticketstatus neq 'ไม่อนุมัติเรื่องร้องขอ'}
                                    
                                    <button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete btndelete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" name="Delete">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_DELETE_BUTTON_LABEL}
                                    </button>
                                {/if}
                            {elseif $MODULE eq 'Servicerequest'}
                                {if $flagassign eq true && $service_request_status neq 'ปิดงาน' && $service_request_status neq 'ยกเลิก'}
                                    
                                    <button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_DELETE_BUTTON_LABEL}
                                    </button>
                                {/if}
                            {elseif $MODULE eq 'Job'}
                                {if $flagassign eq true && $job_status neq 'ปิดงาน' && $job_status neq 'ยกเลิกงาน'}
                                    <button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_DELETE_BUTTON_LABEL}
                                    </button>
                                {/if}
                            
                            {else}
                                {php}
                                    if($converted=="1"){
                                {/php}
                                {php}
                                    }else{
                                        if($accountcode==""){
                                        {/php}
                                           
                                            <button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete btndelete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" name="Delete" style="width: 75px;">
                                                <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_DELETE_BUTTON_LABEL}
                                            </button>
                                        {php}
                                        }
                                    }
                                {/php}
                            {/if}

                        {/if}


                        {if $privrecord neq ''}
                        <!-- <img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" accessKey="{$APP.LNK_LIST_PREVIOUS}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}&start={$privrecordstart}'" name="privrecord" value="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev.gif'|@aicrm_imageurl:$THEME}">&nbsp; -->

                        <img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" accessKey="{$APP.LNK_LIST_PREVIOUS}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}&start={$privrecordstart}'" name="privrecord" value="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev.png'|@aicrm_imageurl:$THEME}" style="cursor: pointer;">

                        {else}
                        <img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev.png'|@aicrm_imageurl:$THEME}" style="opacity: 0.5;cursor:not-allowed">
                        <!-- <img align="absmiddle" title="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev_disabled.gif'|@aicrm_imageurl:$THEME}"> -->
                        {/if}

                        {if $privrecord neq '' || $nextrecord neq ''}
                        <!-- <img align="absmiddle" title="{$APP.LBL_JUMP_BTN}" accessKey="{$APP.LBL_JUMP_BTN}" onclick="var obj = this;var lhref = getListOfRecords(obj, '{$MODULE}',{$ID},'{$CATEGORY}');" name="jumpBtnIdTop" id="jumpBtnIdTop" src="{'rec_jump.gif'|@aicrm_imageurl:$THEME}">&nbsp; -->
                        <img align="absmiddle" title="{$APP.LBL_JUMP_BTN}" accessKey="{$APP.LBL_JUMP_BTN}" onclick="var obj = this;var lhref = getListOfRecords(obj, '{$MODULE}',{$ID},'{$CATEGORY}');" name="jumpBtnIdTop" id="jumpBtnIdTop" src="{'rec_jump.png'|@aicrm_imageurl:$THEME}" style="cursor: pointer;">

                        {/if}

                        {if $nextrecord neq ''}
                        <!-- <img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" accessKey="{$APP.LNK_LIST_NEXT}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}&start={$nextrecordstart}'" name="nextrecord" src="{'rec_next.gif'|@aicrm_imageurl:$THEME}">&nbsp; -->
                        <img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" accessKey="{$APP.LNK_LIST_NEXT}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}&start={$nextrecordstart}'" name="nextrecord" src="{'rec_next.png'|@aicrm_imageurl:$THEME}" style="cursor: pointer;">
                        {else}
                        <!-- <img align="absmiddle" title="{$APP.LNK_LIST_NEXT}" src="{'rec_next_disabled.gif'|@aicrm_imageurl:$THEME}">&nbsp; -->
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
                    <td style="padding:10px;">
                    <!-- Command Buttons -->
                    <table border=0 cellspacing=0 cellpadding=0 width=100%>
                            <!-- NOTE: We should avoid form-inside-form condition, which could happen when
                                Singlepane view is enabled. -->
                            <form action="index.php" method="post" name="DetailView" id="form">
                            {include file='DetailViewHidden.tpl'}

                              <!-- Start of File Include by SAKTI on 10th Apr, 2008 -->
                            {include_php file="./include/DetailViewBlockStatus.php"}
                             <!-- Start of File Include by SAKTI on 10th Apr, 2008 -->

                            {if $MODULE eq 'HelpDesk'}
                                <input type="hidden" name="case_type_detailview" id ="case_type_detailview" value ="{$tickettype}">
                            {/if}

                            {if $MODULE eq 'Deal'}
                                <tr><td>{include_php file='plugin/Deal/Viewstage.php'}</td></tr>
                            {/if}
                            
                            {foreach key=header item=detail from=$BLOCKS}
                            <!-- Detailed View Code starts here-->
                            <table border=0 cellspacing=0 cellpadding=0 width=100% class="small">

                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>

                                    <td align=right>
                                    {if $header eq $MOD.LBL_ADDRESS_INFORMATION && ($MODULE eq 'Accounts' || $MODULE eq 'Contacts' || $MODULE neq 'Leads') }

                                        {if $MODULE eq 'Leads'}
                                         <!--<input name="mapbutton" value="{$APP.LBL_LOCATE_MAP}" class="crmbutton small create" type="button" onClick="searchMapLocation( 'Main' )" title="{$APP.LBL_LOCATE_MAP}">-->
                                        {else}
                                         <!--<input name="mapbutton" value="{$APP.LBL_LOCATE_MAP}" class="crmbutton small create" type="button" onClick="fnvshobj(this,'locateMap');" onMouseOut="fninvsh('locateMap');" title="{$APP.LBL_LOCATE_MAP}">-->
                                        {/if}

                                    {/if}
                                    </td>
                                </tr>
                                
                                <!-- This is added to display the existing comments -->
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

                                {if $header eq 'History Status'}
                                    <tr>
                                        <td colspan=4 class="dvInnerHeader">
                                            <a href="javascript:showHideStatus('history-div','history-div-img','{$IMAGE_PATH}');">
                                                <img id="history-div-img" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide" /></a>&nbsp;&nbsp;&nbsp;
                                            <b>{$header}</b>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td colspan=4 class="dvtCellInfo" style="border-bottom:none">{$HISTORY_STATUS_LIST}</td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                {/if}

                                {if $header neq 'Comments' && $header neq 'Comment Information'}
                                    
                                    {if $MODULE eq 'HelpDesk'}
                                        {if $header eq 'รายละเอียดเรื่องร้องขอ' }
                                             <tr class='case-tr-request'>
                                        {elseif $header eq 'รายละเอียดเรื่องแจ้งซ่อม' || $header eq 'ผลการตรวจสอบหรือแก้ไข รายการแจ้งซ่อม'}
                                             <tr class='case-tr-service'>
                                        {elseif $header eq 'รายละเอียดเรื่องร้องเรียน'}
                                             <tr class='case-tr-complain'>
                                        {else}
                                            <tr>
                                        {/if}
                                    {else}
                                        <tr>
                                    {/if}
                                     {strip}
                                     <td colspan=4 class="dvInnerHeader">

                                     <div style="float:left; font-weight:bold; margin-left: 10px;">
                                        <div style="float:left;">
                                            <a href="javascript:showHideStatus('tbl{$header|replace:' ':''}','aid{$header|replace:' ':''}','{$IMAGE_PATH}');">

                                    {if $BLOCKINITIALSTATUS[$header] eq 1}
                                        <!-- <img id="aid{$header|replace:' ':''}" src="{'activate.gif'|@aicrm_imageurl:$THEME}" style="border: 0px solid #000000;" alt="Hide" title="Hide"/> -->
                                        <img id="aid{$header|replace:' ':''}" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide" />
                                    {else}
                                    <!-- <img id="aid{$header|replace:' ':''}" src="{'inactivate.gif'|@aicrm_imageurl:$THEME}" style="border: 0px solid #000000;" alt="Display" title="Display"/> -->
                                    <img id="aid{$header|replace:' ':''}" src="themes/softed/images/slidedown_b2.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Display" title="Display"/>
                                    {/if}

                                    </a></div><b style="font-family: PromptMedium; font-size: 12px;">&nbsp;&nbsp;&nbsp;{$header}</b></div></td>{/strip}
                                    </tr>
                                {/if}

                            </table>
                            
                            {if $header=="Image Infomation" && $MODULE=="Job" }
                                <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
                                <tr>
                                    <td colspan=4>
                                        {include_php file='modules/Job/ViewImage.php'}
                                    </td>
                                </tr>
                                </table>
                            {elseif $header=="Image Information" && $MODULE=="Calendar" }
                                <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
                                <tr>
                                    <td colspan=4>
                                        {include_php file='modules/Calendar/ViewImage.php'}
                                    </td>
                                </tr>
                                </table>
                            {elseif $header=="Products Image Infomation" && $MODULE=="Job" }
                                <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
                                <tr>
                                    <td colspan=4>
                                        {include_php file='modules/Job/ViewImage.php'}
                                    </td>
                                </tr>
                                </table>
                            {elseif $header=="Receipt Image Information" && $MODULE=="Job" }
                                <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
                                <tr>
                                    <td colspan=4>
                                        {include_php file='modules/Job/ViewImagereceipt.php'}
                                    </td>
                                </tr>
                                </table>
                            {elseif $header=="Picture Information" && $MODULE=="HelpDesk" }
                                <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
                                <tr>
                                    <td colspan=4>
                                        {include_php file='modules/HelpDesk/ViewImage.php'}
                                    </td>
                                </tr>
                                </table>
                            {elseif $header=="Comment Information"}

                            {else}

                                {if $header neq 'Comments'}
                                    {if $BLOCKINITIALSTATUS[$header] eq 1}
                                        <div style="width:auto;display:block; border: 1px solid #EDEDED; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;" id="tbl{$header|replace:' ':''}" >
                                    {else}
                                        <div style="width:auto;display:none;" id="tbl{$header|replace:' ':''}" >
                                    {/if}

                                    <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">

                                    {foreach item=detail from=$detail}
                                        {if $MODULE eq 'HelpDesk'}
                                            {if $header eq 'รายละเอียดเรื่องร้องขอ' }
                                                <tr class='case-tr-request'  style="height:25px">
                                            {elseif $header eq 'รายละเอียดเรื่องแจ้งซ่อม' || $header eq 'ผลการตรวจสอบหรือแก้ไข รายการแจ้งซ่อม'}
                                                <tr class='case-tr-service'  style="height:25px">
                                            {elseif $header eq 'รายละเอียดเรื่องร้องเรียน'}
                                                <tr class='case-tr-complain'  style="height:25px">
                                            {else}
                                                <tr style="height:25px">
                                            {/if}
                                        {else}
                                            <tr style="height:25px">
                                        {/if}

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
                                                {elseif $keyid eq '948'}
                                                
                                                {else}
                                                    <td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$label}</td>
                                                {/if}

                                                {if $EDIT_PERMISSION eq 'yes' && $display_type neq '2'}

                                                    {* Performance Optimization Control *}
                                                    {if !empty($DETAILVIEW_AJAX_EDIT) }
                                                        {php}
                                                          if(($cf_2282=="0" or $cf_2282=="") and $converted!="1"){
                                                        {/php}
                                                            {include file="DetailViewUI.tpl"}
                                                        {php}
                                                          }else if($converted=="1"){
                                                        {/php}
                                                            {include file="DetailViewFields.tpl"}
                                                        {php}
                                                         }else{
                                                         echo "dddd";
                                                        {/php}
                                                            {include file="DetailViewFields.tpl"}
                                                        {php}
                                                         }
                                                        {/php}
                                                    {else}
                                                        {if $keyfldname eq 'comments'}
                                                            {include file="DetailViewUI.tpl"}
                                                        {else}
                                                            {include file="DetailViewFields.tpl"}
                                                        {/if}
                                                    {/if}

                                                    {* END *}

                                                {else}
                                                        {include file="DetailViewFields.tpl"}

                                                {/if}

                                            {/if}
                                        {/foreach}
                                        </tr>
                                    {/foreach}

                                    {if $header=="Remark" && $MODULE=="HelpDesk" }
                                        {include_php file='modules/HelpDesk/log_change_status.php'}
                                    {elseif $header=="Remark" && $MODULE=="Servicerequest"}
                                        {include_php file='modules/Servicerequest/log_change_status.php'}
                                    {elseif $header=="Remark" && $MODULE=="Job"}
                                        {include_php file='modules/Job/log_change_status.php'}
                                    {/if}

                                    </table>
                                </div>
                                {/if}
                            {/if}  {* END block questionnaire detail*}
                            </td>
                           </tr>
                        <tr>
                        <td style="padding:10px">
                        
                        {/foreach}

                {*-- End of Blocks--*}

                </td>
            </tr>

            {if $MODULE eq 'Claim'}
            <tr>
                <td colspan="4">
                    {include_php file="./modules/Claim/ProductDetail/product_list_view.php"}
                </td>
            </tr>
            {/if}

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

            {if $MODULE eq 'Job'}
                <tr>
                    <td style="padding:10px">
                        <table class="small" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr><td class="dvInnerHeader"> <div style="float:left;font-weight:bold;"><b>Error/Spare Part Description</b></div> </td><tr>
                            <tr><td align="center">{include file= 'RelatedLists_more.tpl'}</td> </tr>
                        </table>
                     </td>
                </tr>

            {/if}
            {if $MODULE eq 'Deal'}
                <tr>
                    <td style="padding:10px">
                        <table class="small" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr><td align="center">{include file= 'RelatedLists_more.tpl'}</td> </tr>
                        </table>
                     </td>
                </tr>

            {/if}
            {if $MODULE eq 'HelpDesk'}
                <tr>
                    <td style="padding:10px">
                        <table class="small" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr><td align="center">{include file= 'RelatedLists_more.tpl'}</td> </tr>
                        </table>
                     </td>
                </tr>

            {/if}

            {if $MODULE eq 'Servicerequest'}
                <tr>
                    <td style="padding:10px">
                        <table class="small" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr><td align="center">{include file= 'RelatedLists_more.tpl'}</td> </tr>
                        </table>
                     </td>
                </tr>

            {/if}

            <!-- End the form related to detail view -->

            {if $SinglePane_View eq 'true' && $IS_REL_LIST eq 'true'}
                {include file= 'RelatedListNew.tpl'}
            {/if}
        </table>

        </td>
        <td width=22% valign=top style="border-left:1px dashed #cccccc;padding:13px">

            <!-- right side relevant info -->
            <!-- Action links for Event & Todo START-by Minnie -->
            {if $MODULE eq 'Accounts' || $MODULE eq 'Contacts' || $MODULE eq 'Potentials' || $MODULE eq 'HelpDesk' || $MODULE eq 'Leads' || $MODULE eq 'Quotes' || ($MODULE eq 'Documents'  && ($ADMIN eq 'yes' || $FILE_STATUS eq '1') && $FILE_EXIST eq 'yes')}
            <table width="100%" border="0" cellpadding="5" cellspacing="0">
                {*{if $MODULE eq 'Accounts'}
                    <tr><td align="left" class="genHeaderSmall">{$APP.LBL_REPORT}</td></tr>

                    <td align="left" style="padding-left:10px;">
                        <a class="webMnu" href="{$Report_PDF}rpt_change_password_account.rptdesign&accountid={$ID}&__format=pdf" target="_blank" style="font-family: PromptMedium; font-weight: 400; color: #2B2B2B; font-size: 11px;">
                        <img src="{'themes/softed/images/pdf.png'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0" style="width: 28px; margin-right: 10px;"/>แบบฟอร์มการเปิดบัญชีลูกค้ารายใหม่
                        </a>
                    </td>
                {/if}*}
                
                {if $MODULE eq 'HelpDesk'}

                    {*}<tr><td align="left" class="genHeaderSmall">{$APP.LBL_REPORT}</td></tr>

                    <td align="left" style="padding-left:10px;">
                        <a class="webMnu" href="{$Report_PDF}rpt_customer_complain.rptdesign&ticketid={$ID}&__format=pdf" target="_blank" style="font-family: PromptMedium; font-weight: 400; color: #2B2B2B; font-size: 11px;">
                        <img src="{'themes/softed/images/pdf.png'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0" style="width: 28px; margin-right: 10px;"/>Customer Complain Form
                        </a>
                    </td>{*}
                    
                {elseif $TODO_PERMISSION eq 'true' || $EVENT_PERMISSION eq 'true' || $CONTACT_PERMISSION eq 'true'|| $MODULE eq 'Contacts'|| $MODULE eq 'Leads' || $MODULE eq 'Accounts' || ($MODULE eq 'Documents') }
                    
                    {if $MODULE neq 'Leads'}
                        <tr><td align="left" class="genHeaderSmall">{$APP.LBL_ACTIONS}</td></tr>
                    {/if}

                    {if $MODULE eq 'Contacts'}
                        {assign var=subst value="contact_id"}
                        {assign var=acc value="&account_id=$accountid"}
                    {elseif $MODULE eq 'Accounts'}
                        {assign var=subst value="account_id"}
                    {else}
                        {assign var=subst value="parent_id"}
                        {assign var=acc value=""}
                    {/if}

                    {if $MODULE eq 'Contacts' || $MODULE eq 'Accounts'}
                        {if $SENDMAILBUTTON eq 'permitted'}
                            <tr>
                                <td align="left" style="padding-left:10px;">
                                <input type="hidden" name="pri_email" value="{$EMAIL1}"/>
                                <input type="hidden" name="sec_email" value="{$EMAIL2}"/>
                                <img src="themes/softed/images/mail.png" hspace="5" align="absmiddle"  border="0" style="width: 20px;" />
                                <a href="javascript:void(0);" class="webMnu" onclick="if(LTrim('{$EMAIL1}') !='' || LTrim('{$EMAIL2}') !=''){ldelim}fnvshobj(this,'sendmail_cont');sendmail('{$MODULE}',{$ID}){rdelim}else{ldelim}OpenCompose('','create'){rdelim}">{$APP.LBL_SENDMAIL_BUTTON_LABEL}</a>
                                </td>
                            </tr>
                        {/if}
                    {/if}

                    {if $MODULE eq 'Contacts' || $EVENT_PERMISSION eq 'true' && $MODULE neq 'Leads' }
                        <tr>
                            <td align="left" style="padding-left:10px;">
                                <img src="themes/softed/images/holiday_b.png" hspace="5" align="absmiddle"  border="0" style="width: 20px;" />
                                <a href="index.php?module=Calendar&action=EditView&return_module={$MODULE}&return_action=DetailView&activity_mode=Events&return_id={$ID}&{$subst}={$ID}{$acc}&parenttab={$CATEGORY}" class="webMnu">{$APP.LBL_ADD_NEW} {$APP.Event}</a>
                            </td>
                        </tr>
                    {/if}


                    {*if $TODO_PERMISSION eq 'true' && ($MODULE eq 'Accounts' || $MODULE eq 'Quotes')}
                        <tr>
                            <td align="left" style="padding-left:10px;">
                                <a href="index.php?module=Calendar&action=EditView&return_module={$MODULE}&return_action=DetailView&activity_mode=Task&return_id={$ID}&{$subst}={$ID}{$acc}&parenttab={$CATEGORY}" class="webMnu">
                                    <img src="{'AddToDo.gif'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/>
                                </a>
                                <a href="index.php?module=Calendar&action=EditView&return_module={$MODULE}&return_action=DetailView&activity_mode=Task&return_id={$ID}&{$subst}={$ID}{$acc}&parenttab={$CATEGORY}" class="webMnu">{$APP.LBL_ADD_NEW} {$APP.Todo}</a>
                            </td>
                        </tr>
                    {/if*}

                    {if $MODULE neq 'Contacts' && $CONTACT_PERMISSION eq 'true' }
                        <tr>
                            <td align="left" style="padding-left:10px;">
                                <a href="index.php?module=Calendar&action=EditView&return_module={$MODULE}&return_action=DetailView&activity_mode=Task&return_id={$ID}&{$subst}={$ID}{$acc}&parenttab={$CATEGORY}" class="webMnu">
                                    <img src="{'AddToDo.gif'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/>
                                </a>
                                <a href="index.php?module=Calendar&action=EditView&return_module={$MODULE}&return_action=DetailView&activity_mode=Task&return_id={$ID}&{$subst}={$ID}{$acc}&parenttab={$CATEGORY}" class="webMnu">{$APP.LBL_ADD_NEW} {$APP.Todo}</a>
                            </td>
                        </tr>
                    {/if}

                    {if $MODULE eq 'Leads'}
                        {if $CONVERTLEAD eq 'permitted'}
                            {php}
                                if($converted=="1"){
                            {/php}
                            {php}
                                }else{
                            {/php}
                            <tr><td align="left" class="genHeaderSmall">{$APP.LBL_ACTIONS}</td></tr>
                            <tr>
                                <td align="left" style="padding-left:5px;">
                                   <!--  <a href="javascript:void(0);" class="webMnu" onclick="callConvertLeadDiv('{$ID}');"><img src="{'Leads.gif'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle"  border="0"/></a> -->
                                    <a href="javascript:void(0);" class="webMnu" onclick="callConvertLeadDiv('{$ID}');"><img src="{'repeat.svg'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle"  border="0" style="width: 25px; height: 22px;" /></a>
                                    <a href="javascript:void(0);" class="webMnu" onclick="callConvertLeadDiv('{$ID}');">{$APP.LBL_CONVERT_BUTTON_LABEL}</a>
                                </td>
                            </tr>
                            {php}
                                }
                            {/php}
                        {/if}
                    {/if}

                    <!-- Start: Actions for Documents Module -->
                    {if $MODULE eq 'Documents'}
                        <tr><td align="left" style="padding-left:10px;">
                        {if $DLD_TYPE eq 'I' && $FILE_STATUS eq '1'}
                            <br><a href="index.php?module=uploads&action=downloadfile&fileid={$FILEID}&entityid={$NOTESID}"  onclick="javascript:dldCntIncrease({$NOTESID});" class="webMnu"><img src="{'fbDownload.gif'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" title="{$APP.LNK_DOWNLOAD}" border="0"/></a>
                            <a href="index.php?module=uploads&action=downloadfile&fileid={$FILEID}&entityid={$NOTESID}" onclick="javascript:dldCntIncrease({$NOTESID});">{$MOD.LBL_DOWNLOAD_FILE}</a>
                        {elseif $DLD_TYPE eq 'E' && $FILE_STATUS eq '1'}
                            <br><a target="_blank" href="{$DLD_PATH}" onclick="javascript:dldCntIncrease({$NOTESID});"><img src="{'fbDownload.gif'|@aicrm_imageurl:$THEME}"" align="absmiddle" title="{$APP.LNK_DOWNLOAD}" border="0"></a>
                            <a target="_blank" href="{$DLD_PATH}" onclick="javascript:dldCntIncrease({$NOTESID});">{$MOD.LBL_DOWNLOAD_FILE}</a>
                        {/if}
                        </td></tr>
                        {if $CHECK_INTEGRITY_PERMISSION eq 'yes'}
                            <tr><td align="left" style="padding-left:10px;">
                            <br><a href="javascript:;" onClick="checkFileIntegrityDetailView({$NOTESID});"><img id="CheckIntegrity_img_id" src="{'yes.gif'|@aicrm_imageurl:$THEME}" alt="Check integrity of this file" title="Check integrity of this file" hspace="5" align="absmiddle" border="0"/></a>
                            <a href="javascript:;" onClick="checkFileIntegrityDetailView({$NOTESID});">{$MOD.LBL_CHECK_INTEGRITY}</a>&nbsp;
                            <input type="hidden" id="dldfilename" name="dldfilename" value="{$FILEID}_{$FILENAME}">
                            <span id="vtbusy_integrity_info" style="display:none;">
                                <img src="{'vtbusy.gif'|@aicrm_imageurl:$THEME}" border="0"></span>
                            <span id="integrity_result" style="display:none"></span>
                            </td></tr>
                        {/if}
                        <tr><td align="left" style="padding-left:10px;">
                        {if $DLD_TYPE eq 'I'}
                            <input type="hidden" id="dldfilename" name="dldfilename" value="{$FILEID}_{$FILENAME}">
                            <br><a href="javascript: document.DetailView.return_module.value='Documents'; document.DetailView.return_action.value='DetailView'; document.DetailView.module.value='Documents'; document.DetailView.action.value='EmailFile'; document.DetailView.record.value={$NOTESID}; document.DetailView.return_id.value={$NOTESID}; sendfile_email();" class="webMnu"><img src="{'attachment.gif'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/></a>
                            <a href="javascript: document.DetailView.return_module.value='Documents'; document.DetailView.return_action.value='DetailView'; document.DetailView.module.value='Documents'; document.DetailView.action.value='EmailFile'; document.DetailView.record.value={$NOTESID}; document.DetailView.return_id.value={$NOTESID}; sendfile_email();">{$MOD.LBL_EMAIL_FILE}</a>
                        {/if}
                        </td></tr>
                        <tr><td>&nbsp;</td></tr>
                    {/if}

                {/if}

                <!-- End: Actions for Documents Module -->
            </table>
                {* vtlib customization: Avoid line break if custom links are present *}
                {if !isset($CUSTOM_LINKS) || empty($CUSTOM_LINKS)}
                <br>
                {/if}
            {/if}

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

                                <a class="webMnu" href="{$customlink_href}"><img hspace=5 align="absmiddle" border=0 src="{$CUSTOMLINK->linkicon}" style="width: 20px;"></a>

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

        {* //if module neq accounts *}
        {* END *}
                <!-- Action links END -->
    {if $MODULE eq 'HelpDesk'}
        <!-- <table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
        <td align="left">
        <span class="genHeaderSmall">เอกสาร</span><br />
        </td>
        </tr>
        <tr>
        <td align="left" style="padding-left:10px;">
        <a class="webMnu" href="#" onclick="JavaScript: void window.open('Report/report_case_01.php?aicrm={$ID}','HelpDesk','resizable=yes,left=20,top=10,width=990,height=800,toolbar=no,scrollbars=yes,menubar=no,location=no');">
            <img src="{'actionGeneratePDF.gif'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/>แบบฟอร์มใบแจ้งซ่อมแผนก PCD
        </a>
        </td>
        </tr>
        <tr>
        <td>&nbsp;</td>
        </tr>
        </table> -->
    {/if}

    {*if $MODULE eq 'Job'}
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
        <td align="left">
        <span class="genHeaderSmall">เอกสาร</span><br />
        </td>
        </tr>
        <tr>
	        <td align="left" style="padding-left:10px;">
                <a class="webMnu" href="#" onclick="JavaScript: void window.open('Report/report_job.php?jobid={$ID}&type=service','Job','resizable=yes,left=20,top=10,width=990,height=800,toolbar=no,scrollbars=yes,menubar=no,location=no');" style="font-family: PromptMedium; font-weight: 400; color: #2B2B2B; font-size: 11px;">
                <img src="{'themes/softed/images/pdf.png'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0" style="width: 30px; margin-right: 10px;"/>รายงาน Technical & Service Report
                </a>
	        </td>
    	</tr>
        <tr>
	        <td align="left" style="padding-left:10px;">
	            <a class="webMnu" href="{$Report_URL}rpt_job_report.rptdesign&jobid={$ID}&__format=doc">
                    <img src="{'themes/softed/images/doc.png'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0" style="width: 30px; margin-right: 10px;"/></a>
	            <a class="webMnu" href="{$Report_URL}rpt_job_report.rptdesign&jobid={$ID}&__format=doc") style="font-family: PromptMedium; font-weight: 400; color: #2B2B2B; font-size: 11px;">รายงาน Technical & Service Report</a>
	        </td>
        </tr>

        <tr>
            <td align="left" style="padding-left:10px;">
                <a class="webMnu" href="#" onclick="JavaScript: void window.open('Report/report_job.php?jobid={$ID}&type=Requisition','Job','resizable=yes,left=20,top=10,width=990,height=800,toolbar=no,scrollbars=yes,menubar=no,location=no');" style="font-family: PromptMedium; font-weight: 400; color: #2B2B2B; font-size: 11px;">
                    <img src="{'themes/softed/images/pdf.png'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0" style="width: 30px; margin-right: 10px;"/>รายงาน Support Requisition Form
                </a>
            </td>
        </tr>
        <tr>
            <td align="left" style="padding-left:10px;">
                <a class="webMnu" href="{$Report_URL}rpt_requisition.rptdesign&jobid={$ID}&__format=doc"><img src="{'themes/softed/images/doc.png'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0" style="width: 30px; margin-right: 10px;"/></a>
                <a class="webMnu" style="font-family: PromptMedium; font-weight: 400; color: #2B2B2B; font-size: 11px;" href="{$Report_URL}rpt_requisition.rptdesign&jobid={$ID}&__format=doc")>รายงาน Support Requisition Form</a>
            </td>
        </tr>
        </table>
    {/if*}

    {if $MODULE eq 'Deal'}
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
            <tr><td align="left" class="genHeaderSmall">{$APP.LBL_ACTIONS}</td></tr>

            <tr>
                <td align="left" class="genHeaderSmall submenu">
                        <a href="index.php?module=Quotes&action=EditView&return_module={$MODULE}&return_action=DetailView&activity_mode=Events&return_id={$ID}&parenttab={$CATEGORY}" style="text-decoration: none;">
                        <img src="themes/softed/images/create.png" hspace="5" align="absmiddle"  border="0" style="width: 20px;" />
                        Create Quotation</a>
                </td>
            </tr>
            <tr>
                <td align="left" class="genHeaderSmall submenu">
                        <a href='MOAIMB-Webview/Projects/create_web?userid={$USERID}&return_module={$MODULE}&dealId={$DEALID}&dealNo={$DEALNO}' class='ls-modal' style="text-decoration: none;">
                            <img src="themes/softed/images/create.png" hspace="5" align="absmiddle"  border="0" style="width: 20px;" />
                            Create Project Orders
                        </a>
                </td>
            </tr>
        </table>
        <br>
    {/if}

    {if $MODULE eq 'Contacts'}
       {*
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
        <td align="left">
        <span class="genHeaderSmall">เอกสาร</span><br />
        </td>
        </tr>
        <tr>
        <td align="left" style="padding-left:10px;">
        <a class="webMnu" href="#" onclick="JavaScript: void window.open('Report/report_gencontact.php?aicrm={$ID}','Contact','resizable=yes,left=20,top=10,width=990,height=800,toolbar=no,scrollbars=yes,menubar=no,location=no');"><img src="{'actionGeneratePDF.gif'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/>เอกสารแจ้ง Username</a>
        </td>
        </tr>
        <tr>
        <td>&nbsp;</td>
        </tr>
        </table>
        *}
    {/if}

    <!--Open History Activit -->
    <!-- <table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
            <td align="left">
                <span class="genHeaderSmall">History</span><br />
            </td>
        </tr>
        <tr>
            <td align="left" style="padding-left:10px;">
                <a class="webMnu" href="#" onclick="JavaScript: void window.open('Report/report_job.php?dealid={$ID}&type=CASH','Job','resizable=yes,left=20,top=10,width=990,height=800,toolbar=no,scrollbars=yes,menubar=no,location=no');">
                    <img src="{'themes/softed/images/timeline.png'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0" style="width: 20px; margin-right: 10px;"/>Activit Timeline
                </a>
            </td>
        </tr>
    </table><br /> -->
    <!--Closed History Activit -->

    {*if $MODULE neq 'Accounts' && $MODULE neq 'Contacts' && $MODULE neq 'HelpDesk'*}

    {if $TAG_CLOUD_DISPLAY eq 'true'}
        <!-- Tag cloud display -->
        <table border=0 cellspacing=0 cellpadding=0 width=100% class="tagCloud">

            {if $MODULE eq 'Claim'}
                <tr><td align="left" class="genHeaderSmall"  style="padding-top:10px;">{*{$APP.LBL_REPORT}*}Quick Action</td></tr>
                <tr>
                    <td align="left" style="padding:10px 10px 20px;">
                        <a class="webMnu" href="{$Report_URL}rpt_claim.rptdesign&claimid={$ID}&__format=pdf" target="_blank" style="font-family: PromptMedium; font-weight: 400; color: #2B2B2B; font-size: 11px;">
                            <img src="{'themes/softed/images/pdf.png'|@aicrm_imageurl:$THEME}" hspace="5" align="absmiddle" border="0" style="width: 28px; margin-right: 10px;"/>Claim
                        </a>
                    </td>
                </tr>
            {/if}
            
            <tr>
                <!-- <td colspan="2" class="dvInnerHeader"> -->
                <td colspan="2">
                    <div style="float:left; font-weight:bold;width: 100%;margin-bottom: 10px;">
                        
                        <img id="aibtbltag" src="themes/softed/images/tag-fill.png" style="vertical-align: middle;border: 0px solid #000000; width: 15px; height: 15px;" alt="Hide" title="Hide">
                        <b style="font-family: PromptMedium; font-size: 12px;vertical-align: middle;">Add Tag</b>
                    </div>
                </td>
            </tr>

            <tr id="tbltag" style="display:block;padding-bottom:15px;" ><!-- style="display:block;" -->
                <td>
                    <div id="tagdiv" style="display:visible;" class="custom-search">
                        <form method="POST" action="javascript:void(0);" onsubmit="return tagvalidate();">

                            <input type="text" class="form-control detailedViewTextBox custom-search-input" data-role="tagsinput" id="txtbox_tagfields" name="textbox_First Name" style="width:250px;margin-left:5px;"/ placeholder="พิมพ์แท็กที่ต้องการแล้วกด Enter ดูสิ!">

                            <button name="button_tagfileds" type="submit" class="crmbutton small custom-search-botton" />&#10003;</button>
                            <button name="button_clear" type="button" class="crmbutton small custom-search-botton-x" onclick="ClearTag();" />&#128473;</button>
                            
                        </form>
                    </div>
                </td>

                <!-- <td class="tagCloudDisplay" valign="top" style="padding:1rem 0rem 1rem 0.5rem; line-height: 10px">
                    <div id="tagfields">{$ALL_TAG}</div>
                </td> -->
            </tr>
            <!-- <tr>
            	<td>
            		<span style="color: #ff7488;font-size: 12px;line-height: 3;font-family:PromptMedium, serif; display: block;" class="error-tag" id="error-tag">
            	Please enter a tag</span>
            	</td>
            </tr> -->
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
            <!-- <tr >
                <td class="tagCloudDisplay" valign=top> <span id="tagfields">{$ALL_TAG}</span></td>
            </tr> -->

        </table>
        <!-- End Tag cloud display -->
    {/if}

            <!-- Mail Merge-->
                <br>
                <!-- {if $MERGEBUTTON eq 'permitted' }
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
                {/if} -->

    {*/if*}
    {* //if module neq accounts *}

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
                    <td class="dvtUnSelectedCellBottom" align=center nowrap><a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a></td>
                    {/if}
                    <td class="dvtUnSelectedCellBottom" align=center nowrap><a href="index.php?action=TimelineList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_TIMELINE}</a></td>
                    <td class="dvtTabCacheBottom" align="right" style="width:100%">

                        {if $MODULE eq 'Accounts'}
                            {if $approved_status neq 'อนุมัติ'}
                                {if $is_admin eq 1 || $roleid eq 'H2' || $roleid eq 'H738'}
                                    <input class="crmbutton small edit" type="button" value="Approved" onclick="confirm_send('Approved',{$ID});" />&nbsp;
                                {/if}
                            {/if}

                        {/if}

                        <!--Send Data to RMS (Bas 2016-08-18)-->
                        {if $MODULE eq 'HelpDesk' }
                            {if $flagassign eq true && $ticketstatus neq '--None--' && $ticketstatus neq 'ยกเลิก' && $ticketstatus neq 'ปิดงาน' &&  $ticketstatus neq 'อนุมัติเรื่องร้องขอ' &&  $ticketstatus neq 'ไม่อนุมัติเรื่องร้องขอ'}
                                <input class="crmbutton small edit" type="button" value="ส่งต่อ" onclick="JavaScript: void window.open('case_transfer.php?casestatus=ส่งต่อ&crmid={$ID}','Case','resizable=yes,left=350,top=50,width=500,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $flagassign eq true && ($ticketstatus eq 'เปิด' || $ticketstatus eq 'ระหว่างดำเนินการ' ) }
                                <input class="crmbutton small edit" type="button" value="รับงาน" onclick="JavaScript: void window.open('case_status.php?casestatus=รับงาน&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $flagassign eq true && ($tickettype neq 'ร้องเรียน' && $tickettype neq 'ร้องขอ') && ($ticketstatus eq 'Wait For Response' || $ticketstatus eq 'Acknowledge Job' ) }
                                <input class="crmbutton small edit" type="button" value="เช็คอิน" onclick="JavaScript: void window.open('case_status.php?casestatus=เช็คอิน&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $flagassign eq true && ( $ticketstatus eq 'Acknowledge Job' || $ticketstatus eq 'เช็คอิน')}
                                <input class="crmbutton small edit" type="button" value="อยู่ระหว่างดำเนินการ" onclick="JavaScript: void window.open('case_status.php?casestatus=อยู่ระหว่างดำเนินการ&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $tickettype neq 'ร้องขอ' && $flagassign eq true && $ticketstatus neq '--None--' && $ticketstatus neq 'ยกเลิก' && $ticketstatus neq 'ปิดงาน' }
                                <input class="crmbutton small edit" type="button" value="ปิดงาน" onclick="JavaScript: void window.open('case_status.php?casestatus=ปิดงาน&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $tickettype eq 'ร้องขอ' && $flagassign eq true && $ticketstatus neq '--None--' && $ticketstatus neq 'ยกเลิก' && $ticketstatus neq 'ปิดงาน' &&  $ticketstatus neq 'อนุมัติเรื่องร้องขอ' &&  $ticketstatus neq 'ไม่อนุมัติเรื่องร้องขอ'}
                                <input class="crmbutton small edit" type="button" value="อนุมัติเรื่องร้องขอ" onclick="JavaScript: void window.open('case_status.php?casestatus=อนุมัติเรื่องร้องขอ&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                                <input class="crmbutton small edit" type="button" value="ไม่อนุมัติเรื่องร้องขอ" onclick="JavaScript: void window.open('case_status.php?casestatus=ไม่อนุมัติเรื่องร้องขอ&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $flagassign eq true && $ticketstatus neq '--None--' && $ticketstatus neq 'ยกเลิก' && $ticketstatus neq 'ปิดงาน'   &&  $ticketstatus neq 'อนุมัติเรื่องร้องขอ' &&  $ticketstatus neq 'ไม่อนุมัติเรื่องร้องขอ'}
                                <input class="crmbutton small edit" type="button" value="ยกเลิก" onclick="JavaScript: void window.open('case_status.php?casestatus=ยกเลิก&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}
                        {/if}

                        {if $MODULE eq 'Servicerequest' }

                            {if $flagassign eq true && $service_request_status neq '--None--' && $service_request_status neq 'ปิดงาน' && $service_request_status neq 'ยกเลิก'}
                                <input class="crmbutton small edit" type="button" value="ส่งต่อ" onclick="JavaScript: void window.open('servicerequest_transfer.php?service_request_status=ส่งต่อ&crmid={$ID}','Case','resizable=yes,left=350,top=50,width=500,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $flagassign eq true && ($service_request_status eq 'เปิดงาน') }
                                <input class="crmbutton small edit" type="button" value="รับงาน" onclick="JavaScript: void window.open('servicerequest_status.php?service_request_status=อยู่ระหว่างดำเนินการ&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=460,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {*if $flagassign eq true && $service_request_status neq '--None--' && $service_request_status neq 'ปิดงาน' && $service_request_status neq 'ยกเลิก' }
                                <input class="crmbutton small edit" type="button" value="ปิดงาน" onclick="JavaScript: void window.open('servicerequest_status.php?service_request_status=ปิดงาน&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if*}
                            
                            {if $flagassign eq true && $service_request_status neq '--None--' && $service_request_status neq 'ปิดงาน' && $service_request_status neq 'ยกเลิก'}
                                <input class="crmbutton small edit" type="button" value="ยกเลิก" onclick="JavaScript: void window.open('servicerequest_status.php?service_request_status=ยกเลิก&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                        {/if}

                        {if $MODULE eq 'Job' }

                            {if $flagassign eq true && $job_status neq '--None--' && $job_status neq 'ปิดงาน' && $job_status neq 'ยกเลิกงาน'}
                                <input class="crmbutton small edit" type="button" value="ส่งต่อ" onclick="JavaScript: void window.open('job_transfer.php?job_status=ส่งต่อ&crmid={$ID}','Case','resizable=yes,left=350,top=50,width=500,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $flagassign eq true && ($job_status eq 'เปิดงาน') }
                                <input class="crmbutton small edit" type="button" value="รับงาน" onclick="JavaScript: void window.open('job_status.php?job_status=อยู่ระหว่างดำเนินงาน&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=460,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $flagassign eq true && $job_status neq '--None--' && $job_status neq 'ปิดงาน' && $job_status neq 'ยกเลิกงาน' }
                                <input class="crmbutton small edit" type="button" value="ปิดงาน" onclick="JavaScript: void window.open('job_status.php?job_status=ปิดงาน&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                            {if $flagassign eq true && $job_status neq '--None--' && $job_status neq 'ปิดงาน' && $job_status neq 'ยกเลิกงาน'}
                                <input class="crmbutton small edit" type="button" value="ยกเลิก" onclick="JavaScript: void window.open('job_status.php?job_status=ยกเลิก&crmid={$ID}','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            {/if}

                        {/if}

                        {if $EDIT_DUPLICATE eq 'permitted' }
                            {if $MODULE eq 'HelpDesk'}
                                {if $flagassign eq true && $ticketstatus neq 'ยกเลิก' && $ticketstatus neq 'ปิดงาน' &&  $ticketstatus neq 'อนุมัติเรื่องร้องขอ' &&  $ticketstatus neq 'ไม่อนุมัติเรื่องร้องขอ'}
                                    <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}" >
                                        <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                    </button>
                                {/if}
                            {elseif $MODULE eq 'Servicerequest'}
                                {if $flagassign eq true && $service_request_status neq 'ปิดงาน' && $service_request_status neq 'ยกเลิก'}
                                    <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}" >
                                        <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                    </button>
                                {/if}
                            {elseif $MODULE eq 'Job'}
                                {if $flagassign eq true && $job_status neq 'ปิดงาน' && $job_status neq 'ยกเลิกงาน'}
                                    <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}" >
                                        <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                    </button>
                                {/if}
                           
                            {else}
                                {php}
                                    if($converted=="1"){
                                {/php}
                                {php}
                                    }else{
                                {/php}
                                    {if $MODULE eq 'Announcement'}
                                         {if $announcement_status eq 'Prepare'}
                                            <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                            </button>
                                         {/if}
                                    {elseif $MODULE eq 'Smartquestionnaire'}
                                        {if $smartquestionnaire_status eq 'Prepare'}
                                         <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                         </button>
                                        {/if}
                                    {elseif $MODULE eq 'SmartSms'}
                                        {if $sms_status eq 'Prepare'}
                                         <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                         </button>
                                        {/if}
                                    {elseif $MODULE eq 'Smartemail'}
                                        {if $email_status eq 'Prepare'}
                                         <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                         </button>
                                        {/if}
                                    {elseif $MODULE eq 'Voucher'}
                                        <!-- Not Edit All User -->
                                    {else}
                                        <button title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small btnedit" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}
                                        </button>
                                    {/if}
                                {php}
                                    }
                                {/php}
                            {/if}
                        {/if}

                        {if $EDIT_DUPLICATE eq 'permitted' && $MODULE neq 'Documents' && $MODULE neq 'Voucher' }
                            {php}
                                if($converted=="1"){
                            {/php}
                            {php}
                                }else{
                            {/php}
                                
                                <button title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" name="Duplicate" style="width: 95px;">
                                        <img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_DUPLICATE_BUTTON_LABEL}
                                    </button>
                                
                            {php}
                                }
                            {/php}
                        {/if}

                        {if $DELETE eq 'permitted'}
                            {if $MODULE eq 'HelpDesk'}
                                {if $flagassign eq true && $ticketstatus neq 'ยกเลิก' && $ticketstatus neq 'ปิดงาน' &&  $ticketstatus neq 'อนุมัติเรื่องร้องขอ' &&  $ticketstatus neq 'ไม่อนุมัติเรื่องร้องขอ'}
                                    
                                    <button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_DELETE_BUTTON_LABEL}
                                    </button>
                                {/if}
                            {elseif $MODULE eq 'Servicerequest'}
                                {if $flagassign eq true && $service_request_status neq 'ปิดงาน' && $service_request_status neq 'ยกเลิก'}
                                    
                                    <button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_DELETE_BUTTON_LABEL}
                                    </button>
                                {/if}
                            {elseif $MODULE eq 'Job'}
                                {if $flagassign eq true && $job_status neq 'ปิดงาน' && $job_status neq 'ยกเลิกงาน'}
                                    <button title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_DELETE_BUTTON_LABEL}
                                    </button>
                                {/if}
                           
                            {else}
                                {php}
                                    if($converted=="1"){
                                {/php}
                                {php}
                                    }else{
                                        if($accountcode==""){
                                {/php}
            
                                    <button title="Delete [Alt+D]" accesskey="D" class="crmbutton small delete btndelete" onclick="DetailView.return_module.value='Accounts'; DetailView.return_action.value='index';  var confirmMsg = 'Deleting this account will remove its related Potentials &amp; Quotes. Are you sure you want to delete this account?' ; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" name="Delete" style="width: 75px;">
                                            <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;Delete
                                    </button>

                                {php}
                                        }
                                    }
                                {/php}
                            {/if}
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

{if $MODULE eq 'Deal'}
<link rel="stylesheet" type="text/css" href="asset/css/bootstrap-modal.min.css">
<script language="JavaScript" type="text/javascript" src="asset/js/bootstrap-modal.min.js"></script>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width: 90% !important;">
	    <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="display: inline;">Project Order</h4>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="float: right;">X</button>
            </div>
            <div class="modal-body">
                <iframe src="" style="zoom:0.60" height="1200" width="100%" frameborder="0"></iframe>
            </div>
	    </div>
	</div>
</div>

<div id="myModalUploag" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width: 90% !important;">
	    <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" style="display: inline;">Project Order</h4>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="float: right;">X</button>
            </div>
            <div class="modal-body">
                <iframe src="" style="zoom:0.60" height="700" width="100%" frameborder="0"></iframe>
            </div>
	    </div>
	</div>
</div>

<script>
{literal}

jQuery('.ls-modal').on('click', function(e){
  jQuery('iframe').attr("src", ""); 
  e.preventDefault();
  
  jQuery('#myModal').modal('show').find('.modal-body');
  jQuery('iframe').attr("src", jQuery(this).attr('href')); 
});

function closeModal()
{
    jQuery('#myModal').modal('hide')
}

{/literal}
</script>
{/if}

{if $MODULE eq 'Products'}
<script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script>
<script language="JavaScript" type="text/javascript">Carousel();</script>
{/if}

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
<!-- added for validation -->
<script language="javascript">
    var fielduitype = new Array({$VALIDATION_DATA_UITYPE})
    var fieldname = new Array({$VALIDATION_DATA_FIELDNAME});
    var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL});
    var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE});

</script>
  </td>
  <td align=right valign=top><img src="{'showPanelTopRight.gif'|@aicrm_imageurl:$THEME}"></td>
 </tr>
</table>

{if $MODULE neq 'Leads' or $MODULE eq 'Contacts' or $MODULE eq 'Accounts' or $MODULE eq 'Campaigns'}
    <form name="SendMail"><div id="sendmail_cont" style="z-index:100001;position:absolute;"></div></form>
{/if}