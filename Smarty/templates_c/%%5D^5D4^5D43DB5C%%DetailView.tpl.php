<?php /* Smarty version 2.6.18, created on 2026-04-08 16:51:12
         compiled from DetailView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'aicrm_imageurl', 'DetailView.tpl', 605, false),array('modifier', 'replace', 'DetailView.tpl', 1105, false),array('modifier', 'getTranslatedString', 'DetailView.tpl', 1513, false),)), $this); ?>
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
   <a class="link"  align="right" href="javascript:;"><?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON']; ?>
</a>
</span>
<div id="convertleaddiv" style="display:block;position:absolute;left:600px;top:200px;"></div>
<script type="text/javascript">
     jQuery.noConflict();
</script>
<?php if ($this->_tpl_vars['MODULE'] == 'Products'): ?>
<script>
    <?php echo '
    function genarate_serial(action, id) {
        var msg = \'\';
        model_width = \'650\';
        model_height = \'520\';

        msg = \'Create Serial\';
        url = \'get_genarate_serial.php?crmid=\' + id + \'&action=\'+action;
       

        jQuery(\'#dialog\').window({
            title: msg,
            width: model_width,
            height: model_height,
            closed: false,
            cache: false,
            href: url,
            modal: true
        });


    }//function

    '; ?>

</script>
<?php endif; ?>
<script>
<?php echo '
var gVTModule = \'{$smarty.request.module|@vtlib_purify}\';
function callConvertLeadDiv(id)
{	
    new Ajax.Request(
        \'index.php\',
        {queue: {position: \'end\', scope: \'command\'},
            method: \'post\',
            postBody: \'module=Leads&action=LeadsAjax&file=ConvertLead&record=\'+id,
            onComplete: function(response) {
                $("convertleaddiv").innerHTML=response.responseText;
                eval($("conv_leadcal").innerHTML);
            }
        }
    );
}

function openSettingEmailDialog(crmID, Module, tbtEmailLog){
    
    /*if(tbtEmailLog === \'no\'){
        alert(\'ยังไม่มีรายการ Account ใน moreinfo\');
        return false;
    }*/

    /*jQuery.post(\'emailSettingDialog.php\', {crmID:crmID, Module:Module}, function(html){
        jQuery(\'#dialog\').window({
            title: \'ตั้งเวลาส่ง\',
            width:500,
            height:250,
            modal:true,
            maximizable: false,
            minimizable: false
        }).html(html);
    })*/

    var msg = \'\';
    url = \'plugin/Smartquestionnaire/setup_datetime.php?crmid=\'+crmID;
    jQuery(\'#dialog\').window({
        title: \'ตั้งเวลาส่ง\',
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
    if(type === \'\') return false;
    var date = \'\';
    var time = \'\';
    if(type === \'2\'){
        date = jQuery(\'#date\').val();
        time = jQuery(\'#time\').val();

        if(date === \'\' || time === \'\'){
            alert(\'กรุณาระบบ วัน เวลา\');
            return false;
        }
    }

    jQuery.post(\'emailSettingSave.php\', {Module:Module, crmID:crmID, type:type, date:date, time:time}, function(rs){
        
        jQuery(\'#settingemail-dialog\').window(\'close\');

        if(type === \'1\'){
            switch(Module){
                case \'Surveysmartemail\':
                    window.open(\'sendemail_Surveysmartemail_setup.php?crmid=\'+crmID,\'Application\',\'resizable=0,left=200,top=50,width=630,height=390,toolbar=no,scrollbars=no,menubar=no,location=no\')
                    break;
                case \'Smartquestionnaire\':
                    window.open(\'sendemail_SmartquestionnaireCampaigns_setup.php?crmid=\'+crmID,\'Application\',\'resizable=0,left=200,top=50,width=630,height=390,toolbar=no,scrollbars=no,menubar=no,location=no\')
                    break;
            }
        }else if(type === \'2\'){
            window.location.reload();
        }
    })
}

function exportDataSmartQuestionnaire(crmID, questionnairetemplateid)
{
     jQuery.messager.progress({
         title: \'Please waiting\',
         msg: \'Loading ...\',
         text: \'LOADING\'
     });

    jQuery.post(\'smartQuestionnaireExport.php\', {crmID:crmID, questionnairetemplateid:questionnairetemplateid}, function(rs){
        jQuery("body").append(`<iframe src=\'${rs.filePath}\' style=\'display: none;\' ></iframe>`);
        // console.log(rs)
        jQuery.messager.progress(\'close\');
    },\'json\')
}
function showHideStatus(sId,anchorImgId,sImagePath)
{
    oObj = eval(document.getElementById(sId));
    if(oObj.style.display == \'block\')
    {
        oObj.style.display = \'none\';
        eval(document.getElementById(anchorImgId)).src =  \'themes/images/slidedown_b2.png\';
        eval(document.getElementById(anchorImgId)).alt = \'Display\';
        eval(document.getElementById(anchorImgId)).title = \'Display\';
    }
    else
    {
        oObj.style.display = \'block\';
        // eval(document.getElementById(anchorImgId)).src = \'themes/images/activate.gif\';
        eval(document.getElementById(anchorImgId)).src = \'themes/softed/images/slidedown_b.png\';
        eval(document.getElementById(anchorImgId)).alt = \'Hide\';
        eval(document.getElementById(anchorImgId)).title = \'Hide\';
    }
}
<!-- End Of Code modified by SAKTI on 10th Apr, 2008 -->

<!-- Start of code added by SAKTI on 16th Jun, 2008 -->
function setCoOrdinate(elemId){
    oBtnObj = document.getElementById(elemId);
    var tagName = document.getElementById(\'lstRecordLayout\');
    leftpos  = 0;
    toppos = 0;
    aTag = oBtnObj;
    do{
      leftpos  += aTag.offsetLeft;
      toppos += aTag.offsetTop;
    } while(aTag = aTag.offsetParent);
    tagName.style.top= toppos + 20 + \'px\';
    tagName.style.left= leftpos - 276 + \'px\';
}

function getListOfRecords(obj, sModule, iId,sParentTab)
{       
        new Ajax.Request(
        \'index.php\',
        {queue: {position: \'end\', scope: \'command\'},
            method: \'post\',
            postBody: \'module=Users&action=getListOfRecords&ajax=true&CurModule=\'+sModule+\'&CurRecordId=\'+iId+\'&CurParentTab=\'+sParentTab,
            onComplete: function(response) {
                sResponse = response.responseText;
                //$("lstRecordLayout").innerHTML = sResponse;
                document.getElementById("lstRecordLayout").innerHTML = sResponse;
                Lay = \'lstRecordLayout\';
                var tagName = document.getElementById(Lay);
                var leftSide = findPosX(obj);
                var topSide = findPosY(obj);
                var maxW = tagName.style.width;
                var widthM = maxW.substring(0,maxW.length-2);
                var getVal = parseInt(leftSide) + parseInt(widthM);
                if(getVal  > document.body.clientWidth ){
                    leftSide = parseInt(leftSide) - parseInt(widthM);
                    tagName.style.left = leftSide + 230 + \'px\';
                    tagName.style.top = topSide + 20 + \'px\';
                }else{
                    tagName.style.left = leftSide + 230 + \'px\';
                }
                setCoOrdinate(obj.id);
                tagName.style.display = \'block\';
                tagName.style.visibility = "visible";
            }
        }
    );
}

function setCoOrdinatebutton(elemId){
    oBtnObj = document.getElementById(elemId);
    var tagName = document.getElementById(\'lstRecordLayout\');
    leftpos  = 0;
    toppos = 0;
    aTag = oBtnObj;
    do{
      leftpos  += aTag.offsetLeft;
      toppos += aTag.offsetTop;
    } while(aTag = aTag.offsetParent);
    tagName.style.top= toppos - 300 + \'px\';
    tagName.style.left= leftpos - 276 + \'px\';
}

function getListOfRecordsbutton(obj, sModule, iId,sParentTab)
{
        new Ajax.Request(
        \'index.php\',
        {queue: {position: \'end\', scope: \'command\'},
            method: \'post\',
            postBody: \'module=Users&action=getListOfRecords&ajax=true&CurModule=\'+sModule+\'&CurRecordId=\'+iId+\'&CurParentTab=\'+sParentTab,
            onComplete: function(response) {
                sResponse = response.responseText;
                //$("lstRecordLayout").innerHTML = sResponse;
               
                document.getElementById("lstRecordLayout").innerHTML = sResponse;

                Lay = \'lstRecordLayout\';
                var tagName = document.getElementById(Lay);
                var leftSide = findPosX(obj);
                var topSide = findPosY(obj);
                var maxW = tagName.style.width;
                var widthM = maxW.substring(0,maxW.length-2);
                var getVal = parseInt(leftSide) + parseInt(widthM);
                if(getVal  > document.body.clientWidth ){
                    leftSide = parseInt(leftSide) - parseInt(widthM);
                    tagName.style.left = leftSide + 230 + \'px\';
                    tagName.style.top = topSide + 20 + \'px\';
                }else{
                    tagName.style.left = leftSide + 230 + \'px\';
                }
                setCoOrdinatebutton(obj.id);
                tagName.style.display = \'block\';
                tagName.style.visibility = "visible";
            }
        }
    );
}
<!-- End of code added by SAKTI on 16th Jun, 2008 -->
'; ?>

function tagvalidate()
{
    if(trim(document.getElementById('txtbox_tagfields').value) != ''){
        //jQuery("tagCloudDisplay").style.border='0px';
        //jQuery("error-tag").style.display='none';
        jQuery('.tagCloudDisplay').css('border','0px');
        jQuery('.error-tag').css('display','none');

        SaveTag('txtbox_tagfields','<?php echo $this->_tpl_vars['ID']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
');

        jQuery('.bootstrap-tagsinput').css('border-color','#ccc');
    	/*jQuery( ".tag" ).remove();
    	jQuery('#txtbox_tagfields').tagsinput('removeAll');*/
    }
    else
    {
        jQuery('.bootstrap-tagsinput').css('border-color','red');
        /*alert("<?php echo $this->_tpl_vars['APP']['PLEASE_ENTER_TAG']; ?>
");*/
        return false;
    }
}
function ClearTag(){
	jQuery('#txtbox_tagfields').tagsinput('removeAll');
}
function keyvalue(){
	console.log(123);
}
function DeleteTag(id,recordid)
{
    $("vtbusy_info").style.display="inline";
    Effect.Fade('tag_'+id);
    new Ajax.Request(
        'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: "file=TagCloud&module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=<?php echo $this->_tpl_vars['MODULE']; ?>
Ajax&ajxaction=DELETETAG&recordid="+recordid+"&tagid=" +id,
                        onComplete: function(response) {
                        getTagCloud();
                        $("vtbusy_info").style.display="none";
                        }
                }
        );
}

<?php echo '

function new_comments(id,module){
    var msg = \'\';
    url = \'plugin/Comment/newcomment.php?crmid=\'+id+\'&module=\'+module;
    jQuery(\'#dialog\').window({
        title: \'Comments\',
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
    var msg = \'\';
    url = \'plugin/Announcement/setup_datetime.php?crmid=\'+id;
    jQuery(\'#dialog\').window({
        title: \'ตั้งเวลาส่ง\',
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
    var msg = \'\';
    url = \'plugin/Smartsms/setup_datetime.php?crmid=\'+id;
    jQuery(\'#dialog\').window({
        title: \'ตั้งเวลาส่ง\',
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
    var msg = \'\';
    url = \'plugin/Smartemail/setup_datetime.php?crmid=\'+id;
    jQuery(\'#dialog\').window({
        title: \'ตั้งเวลาส่ง\',
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
    var msg = \'\';
    url = \'plugin/Premuimproduct/adjust_stock.php?crmid=\'+id;
    jQuery(\'#dialog\').window({
        title: \'เพิ่ม/ลด สินค้า\',
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
    var msg = \'\';
    url = \'plugin/Promotionvoucher/formCreate.php?crmid=\'+promotionID;
    jQuery(\'#dialog\').window({
        title: \'Generate Voucher\',
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
        title: \'Confirm\',
        msg: msg,
        buttons:[{
            text: \'OK\',
            onClick: function(){
                jQuery.ajax({
                   type: "POST",
                   url: "account_approve.php",
                   cache: false,
                   data: {crmid:id},
                   dataType: "json",
                   success: function(returndate){
                        if(returndate[\'status\'] == true){
                            alert(returndate[\'msg\']);
                            location.reload();
                        }else{
                            alert(returndate[\'msg\']);
                            dlg.dialog(\'destroy\');
                        }
                    }
                 });
            }
        },{
            text: \'CANCEL\',
            onClick: function(){
                dlg.dialog(\'destroy\')
            }
        }]
    });

}

function confirm_send2(msg,id){

        var dlg = jQuery.messager.confirm({
        title: \'Confirm\',
        msg: msg,
        buttons:[{
            text: \'OK\',
            onClick: function(){
                jQuery.ajax({
                   type: "POST",
                   url: "account_rms.php",
                   cache: false,
                   data: {crmid:id},
                   dataType: "json",
                   success: function(returndata){

                    if(returndata.Type == "S"){
                        //console.log(\'33\');
                        dlg.dialog(\'destroy\');
                        jQuery.messager.alert({
                            title: \'Information\',
                            msg:returndata.Message,
                            fn: function(){
                                location.reload();
                            }
                        });

                    }else{

                        dlg.dialog(\'destroy\');
                        jQuery.messager.alert(\'Message\',returndata.Message,\'info\');


                    }



                    }

                 });
            }
        },{
            text: \'CANCEL\',
            onClick: function(){
                dlg.dialog(\'destroy\')
            }
        }]
    });
}

function sendAccountERP(crmID){
    jQuery.messager.progress({
        title: \'Please waiting\',
        msg: \'Loading ...\',
        text: \'LOADING\'
    });

    jQuery.post(\'index.php\', {
        module: \'Accounts\',
        action: \'AccountsAjax\',
        ajax: true,
        file: \'SendERP\',
        record: crmID
    }, function(rs){
        jQuery.messager.progress(\'close\')
        var title = rs.title;
        var message = rs.msg
        jQuery.messager.alert(title, message, function(r){
            if (r){
                
            }
        });
        console.log(rs)
    }, \'json\')
}
'; ?>



//Added to send a file, in Documents module, as an attachment in an email
function sendfile_email()
{
    filename = $('dldfilename').value;

    document.DetailView.submit();

    OpenCompose(filename,'Documents');

}

</script>
<?php 
    global $converted;
 ?>
<div id="lstRecordLayout" class="layerPopup" style="display:none;width:325px;height:300px;"></div>

<?php if ($this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] != 'Leads'): ?>
        <?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
            <?php $this->assign('address1', '$MOD.LBL_BILLING_ADDRESS'); ?>
            <?php $this->assign('address2', '$MOD.LBL_SHIPPING_ADDRESS'); ?>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['MODULE'] == 'Contacts'): ?>
            <?php $this->assign('address1', '$MOD.LBL_PRIMARY_ADDRESS'); ?>
            <?php $this->assign('address2', '$MOD.LBL_ALTERNATE_ADDRESS'); ?>
        <?php endif; ?>
        <div id="locateMap" onMouseOut="fninvsh('locateMap')" onMouseOver="fnvshNrm('locateMap')">
                <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                                <td>
                                <?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
                                        <!--<a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Main' );" class="calMnu"><?php echo $this->_tpl_vars['MOD']['LBL_BILLING_ADDRESS']; ?>
</a>
                                        <a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Other' );" class="calMnu"><?php echo $this->_tpl_vars['MOD']['LBL_SHIPPING_ADDRESS']; ?>
</a>-->
                               <?php endif; ?>

                               <?php if ($this->_tpl_vars['MODULE'] == 'Contacts'): ?>
                                <!--<a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Main' );" class="calMnu"><?php echo $this->_tpl_vars['MOD']['LBL_PRIMARY_ADDRESS']; ?>
</a>
                                        <a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Other' );" class="calMnu"><?php echo $this->_tpl_vars['MOD']['LBL_ALTERNATE_ADDRESS']; ?>
</a>-->
                               <?php endif; ?>

                                         </td>
                        </tr>
                </table>
        </div>
<?php endif; ?>

<div id="dialog" style="display:none;background-color: #FFF">Dialog Content.</div>
<table width="100%" cellpadding="2" cellspacing="0" border="0">
<tr>
    <td>

        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Buttons_List1.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- Contents -->
<table border=0 cellspacing=0 cellpadding=0 width=99% align=center>
<tr>
    <td valign=top><img src="<?php echo aicrm_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>
    <td class="showPanelBg" valign=top width=100%>
        <!-- PUBLIC CONTENTS STARTS-->
        <div class="small" style="padding:10px" >

        <table border="0" cellpadding="0" cellspacing="0" width="95%">
            <tr style="line-height: 20px;">
                <td style="padding-top: 10px;">
                                        <?php $this->assign('USE_ID_VALUE', $this->_tpl_vars['MOD_SEQ_ID']); ?>
                    <?php if ($this->_tpl_vars['USE_ID_VALUE'] == ''): ?> <?php $this->assign('USE_ID_VALUE', $this->_tpl_vars['ID']); ?> <?php endif; ?>
                    <span class="dvHeaderText">[ <?php echo $this->_tpl_vars['USE_ID_VALUE']; ?>
 ] <?php echo $this->_tpl_vars['NAME']; ?>
 -  <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['SINGLE_MOD']]; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</span>&nbsp;&nbsp;&nbsp;<span class="small" style="font-family: PromptMedium; color: #A9A9A9;"><?php echo $this->_tpl_vars['UPDATEINFO']; ?>
</span>&nbsp;<span id="vtbusy_info" style="display:none;" valign="bottom"><img src="<?php echo aicrm_imageurl('vtbusy.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></span><span id="vtbusy_info" style="visibility:hidden;" valign="bottom"><img src="<?php echo aicrm_imageurl('vtbusy.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></span>
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

                    <td class="dvtSelectedCell" align=center nowrap><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['SINGLE_MOD']]; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</td>

                    <!-- <td class="dvtTabCache" style="width:10px">&nbsp;</td> -->
                    <?php if ($this->_tpl_vars['SinglePane_View'] == 'false'): ?>
                        <td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=CallRelatedList&module=<?php echo $this->_tpl_vars['MODULE']; ?>
&record=<?php echo $this->_tpl_vars['ID']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</a></td>
                    <?php endif; ?>
                    <td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=TimelineList&module=<?php echo $this->_tpl_vars['MODULE']; ?>
&record=<?php echo $this->_tpl_vars['ID']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LBL_TIMELINE']; ?>
</a></td>
                    <td class="dvtTabCache" align="right" style="width:100%">
                            <?php 
                                global $campaignid,$viewlog;
                             ?>

                        <?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
                            <?php if ($this->_tpl_vars['approved_status'] != 'อนุมัติ'): ?>

                                <?php if ($this->_tpl_vars['is_admin'] == 1 || $this->_tpl_vars['roleid'] == 'H2' || $this->_tpl_vars['roleid'] == 'H738'): ?>
                                    <input class="crmbutton small edit" type="button" value="Approved" onclick="confirm_send('Approved',<?php echo $this->_tpl_vars['ID']; ?>
);" />&nbsp;
                                <?php endif; ?>
                            <?php endif; ?>

                            <input class="crmbutton small save" type="button" value="Send to ERP" onclick="sendAccountERP(<?php echo $this->_tpl_vars['ID']; ?>
)" />&nbsp;
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['MODULE'] == 'Claim'): ?>
                            <input class="crmbutton small edit" type="button" value="Send to ERP" onclick="" />&nbsp;
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['ticketstatus'] != '--None--' && $this->_tpl_vars['ticketstatus'] != 'ยกเลิก' && $this->_tpl_vars['ticketstatus'] != 'ปิดงาน'): ?>
                                <input class="crmbutton small edit" type="button" value="ส่งต่อ" onclick="JavaScript: void window.open('case_transfer.php?casestatus=ส่งต่อ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=350,top=50,width=500,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && ( $this->_tpl_vars['ticketstatus'] == 'เปิด' || $this->_tpl_vars['ticketstatus'] == 'ระหว่างดำเนินการ' )): ?>
                                <input class="crmbutton small edit" type="button" value="รับงาน" onclick="JavaScript: void window.open('case_status.php?casestatus=รับงาน&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=460,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            
                            <?php if ($this->_tpl_vars['flagassign'] == true && ( $this->_tpl_vars['ticketstatus'] == 'Acknowledge Job' || $this->_tpl_vars['ticketstatus'] == 'เช็คอิน' )): ?>
                                <input class="crmbutton small edit" type="button" value="อยู่ระหว่างดำเนินการ" onclick="JavaScript: void window.open('case_status.php?casestatus=อยู่ระหว่างดำเนินการ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['tickettype'] != 'ร้องขอ' && $this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['ticketstatus'] != '--None--' && $this->_tpl_vars['ticketstatus'] != 'ยกเลิก' && $this->_tpl_vars['ticketstatus'] != 'ปิดงาน'): ?>
                                <input class="crmbutton small edit" type="button" value="ปิดงาน" onclick="JavaScript: void window.open('case_status.php?casestatus=ปิดงาน&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['tickettype'] == 'ร้องขอ' && $this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['ticketstatus'] != '--None--' && $this->_tpl_vars['ticketstatus'] != 'ยกเลิก' && $this->_tpl_vars['ticketstatus'] != 'ปิดงาน' && $this->_tpl_vars['ticketstatus'] != 'อนุมัติเรื่องร้องขอ' && $this->_tpl_vars['ticketstatus'] != 'ไม่อนุมัติเรื่องร้องขอ'): ?>
                                
                                <input class="crmbutton small edit" type="button" value="อนุมัติเรื่องร้องขอ" onclick="JavaScript: void window.open('case_status.php?casestatus=อนุมัติเรื่องร้องขอ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                                
                                <input class="crmbutton small edit" type="button" value="ไม่อนุมัติเรื่องร้องขอ" onclick="JavaScript: void window.open('case_status.php?casestatus=ไม่อนุมัติเรื่องร้องขอ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')" />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['ticketstatus'] != '--None--' && $this->_tpl_vars['ticketstatus'] != 'ยกเลิก' && $this->_tpl_vars['ticketstatus'] != 'ปิดงาน' && $this->_tpl_vars['ticketstatus'] != 'อนุมัติเรื่องร้องขอ' && $this->_tpl_vars['ticketstatus'] != 'ไม่อนุมัติเรื่องร้องขอ'): ?>
                                <input class="crmbutton small edit" type="button" value="ยกเลิก" onclick="JavaScript: void window.open('case_status.php?casestatus=ยกเลิก&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['MODULE'] == 'Servicerequest'): ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['service_request_status'] != '--None--' && $this->_tpl_vars['service_request_status'] != 'ปิดงาน' && $this->_tpl_vars['service_request_status'] != 'ยกเลิก'): ?>
                                <input class="crmbutton small edit" type="button" value="ส่งต่อ" onclick="JavaScript: void window.open('servicerequest_transfer.php?service_request_status=ส่งต่อ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=350,top=50,width=500,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && ( $this->_tpl_vars['service_request_status'] == 'เปิดงาน' )): ?>
                                <input class="crmbutton small edit" type="button" value="รับงาน" onclick="JavaScript: void window.open('servicerequest_status.php?service_request_status=อยู่ระหว่างดำเนินการ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=460,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            
                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['service_request_status'] != '--None--' && $this->_tpl_vars['service_request_status'] != 'ปิดงาน' && $this->_tpl_vars['service_request_status'] != 'ยกเลิก'): ?>
                                <input class="crmbutton small edit" type="button" value="ยกเลิก" onclick="JavaScript: void window.open('servicerequest_status.php?service_request_status=ยกเลิก&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['MODULE'] == 'Job'): ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['job_status'] != '--None--' && $this->_tpl_vars['job_status'] != 'ปิดงาน' && $this->_tpl_vars['job_status'] != 'ยกเลิกงาน'): ?>
                                <input class="crmbutton small edit" type="button" value="ส่งต่อ" onclick="JavaScript: void window.open('job_transfer.php?job_status=ส่งต่อ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=350,top=50,width=500,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && ( $this->_tpl_vars['job_status'] == 'เปิดงาน' )): ?>
                                <input class="crmbutton small edit" type="button" value="รับงาน" onclick="JavaScript: void window.open('job_status.php?job_status=อยู่ระหว่างดำเนินงาน&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=460,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['job_status'] != '--None--' && $this->_tpl_vars['job_status'] != 'ปิดงาน' && $this->_tpl_vars['job_status'] != 'ยกเลิกงาน'): ?>
                                <input class="crmbutton small edit" type="button" value="ปิดงาน" onclick="JavaScript: void window.open('job_status.php?job_status=ปิดงาน&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['job_status'] != '--None--' && $this->_tpl_vars['job_status'] != 'ปิดงาน' && $this->_tpl_vars['job_status'] != 'ยกเลิกงาน'): ?>
                                <input class="crmbutton small edit" type="button" value="ยกเลิก" onclick="JavaScript: void window.open('job_status.php?job_status=ยกเลิก&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                        <?php endif; ?>
                        
                        <?php if ($this->_tpl_vars['MODULE'] == 'Announcement'): ?>

                            <?php if ($this->_tpl_vars['announcement_status'] == 'Prepare'): ?>
                                <?php if ($this->_tpl_vars['count_user'] != '0'): ?>
                                <button title="Setup" accesskey="E" class="crmbutton small success" onclick="setup_announcement(<?php echo $this->_tpl_vars['ID']; ?>
)" type="button" name="setup_announcement" id="setup_announcement" style="width: auto;">
                                    <img src="themes/softed/images/gear-bold.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Setup
                                </button>
                                <?php endif; ?>
                            <?php endif; ?>
                            <button title="View" accesskey="E" class="crmbutton small edit" onclick="JavaScript: void window.open('Announcement/report.php?crmid=<?php  echo $_REQUEST['record']; ?>','Application','resizable=0,left=200,top=50,width=1100,height=860,toolbar=no,scrollbars=yes,menubar=no,location=no')" type="button" name="viewannouncement" style="width: auto;">
                                <img src="themes/softed/images/bar-chart.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Report
                            </button>
                           
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['MODULE'] == 'SmartSms'): ?>
                            <?php if ($this->_tpl_vars['sms_status'] == 'Prepare'): ?>
                                <?php if ($this->_tpl_vars['count_sms'] != '0'): ?>
                                    <button title="Setup SMS" accesskey="E" class="crmbutton small success" onclick="setup_sms(<?php echo $this->_tpl_vars['ID']; ?>
)" type="button" name="setup_sms" id="setup_sms" style="width: auto;">
                                        <img src="themes/softed/images/gear-bold.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Setup SMS
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['sms_status'] == 'Complete' && $this->_tpl_vars['setup_sms'] == '1' && $this->_tpl_vars['send_sms'] == '1' && $this->_tpl_vars['resend'] != '0'): ?>
                                <input class="crmbutton btnsendmail" type="button" value="Resend" onclick="JavaScript: void window.open('update_resend.php?crmid=<?php  echo $_REQUEST['record']; ?>','Application','resizable=0,left=200,top=50,width=630,height=390,toolbar=no,scrollbars=no,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <button title="Report" accesskey="E" class="crmbutton small edit" onclick="JavaScript: void window.open('SMS/view_send_sms.php?crmid=<?php  echo $_REQUEST['record']; ?>','Application','resizable=0,left=200,top=50,width=1100,height=860,toolbar=no,scrollbars=yes,menubar=no,location=no')" type="button" name="viewsms" style="width: auto;">
                                <img src="themes/softed/images/bar-chart.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Report
                            </button>
                            
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['MODULE'] == 'Smartemail'): ?> 
                            <?php if ($this->_tpl_vars['email_status'] == 'Prepare'): ?>
                                <?php if ($this->_tpl_vars['count_email'] != '0'): ?>
                                    <button title="Setup Email" accesskey="E" class="crmbutton small success" onclick="setup_email(<?php echo $this->_tpl_vars['ID']; ?>
)" type="button" name="setup_email" id="setup_email" style="width: auto;">
                                        <img src="themes/softed/images/gear-bold.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Setup Email
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>

                                <button title="Report" accesskey="E" class="crmbutton small edit" onclick="JavaScript: void window.open('EDM/webmail/home-1.php?crmid=<?php  echo $_REQUEST['record']; ?>','Application','resizable=0,left=200,top=50,width=1100,height=860,toolbar=no,scrollbars=yes,menubar=no,location=no')" type="button" name="viewemail" style="width: auto;">
                                    <img src="themes/softed/images/bar-chart.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Report
                                </button>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['MODULE'] == 'Smartquestionnaire'): ?>
                            <?php 
                                global $campaignid,$prepare_status,$send_email,$email_status,$count_email,$smartquestionnaire_status;
                             ?>

                            <?php  if($prepare_status=="0" && $count_email != "0"){  ?>
                                
                                <?php if ($this->_tpl_vars['smartquestionnaire_status'] == 'Prepare'): ?>
                                    <button title="Setup Email" accesskey="E" class="crmbutton small success" onclick="openSettingEmailDialog('<?php echo $this->_tpl_vars['ID']; ?>
', '<?php echo $this->_tpl_vars['MODULE']; ?>
', '<?php echo $this->_tpl_vars['tbtEmailLog']; ?>
')" type="button" name="prepare_status" id="prepare_status" style="width: auto;">
                                        <img src="themes/softed/images/gear-bold.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Setup Email
                                    </button>
                                <?php endif; ?>
                            <?php  }  ?>
                            <?php if ($this->_tpl_vars['questionnairetemplateid'] != ''): ?>
								<button title="Export" class="crmbutton small edit" type="button" onclick="exportDataSmartQuestionnaire('<?php echo $this->_tpl_vars['ID']; ?>
', '<?php echo $this->_tpl_vars['questionnairetemplateid']; ?>
')">
                                	<img src="themes/softed/images/export_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Export
                            	</button>&nbsp;
								
								<!-- <button class="crmbutton btncountview" type="button" onclick="exportDataSmartQuestionnaire('<?php echo $this->_tpl_vars['ID']; ?>
', '<?php echo $this->_tpl_vars['questionnairetemplateid']; ?>
')">Export</button>&nbsp; -->
							<?php endif; ?>
                            <button title="Preview" class="crmbutton small edit" type="button" onclick="javascript:void window.open('survey/home/questionnaire_template_view/<?php echo $this->_tpl_vars['questionnairetemplateid']; ?>
/0','Preview Questionnairetemplate','resizable=1,left=200,top=50,width=1100,height=800,toolbar=no,scrollbars=yes,menubar=no,location=no')">
                                <img src="themes/softed/images/file-search.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Preview
                            </button>&nbsp;
                            <button title="Report" accesskey="E" class="crmbutton small edit" onclick="JavaScript: void window.open('EDM/webmail/view_smart_questionnaire_email.php?campaing=<?php  echo $_REQUEST['record']; ?>','Application','resizable=1,left=200,top=50,width=1100,height=600,toolbar=no,scrollbars=yes,menubar=no,location=no')" type="button" name="viewemail" style="width: auto;">
                                <img src="themes/softed/images/bar-chart.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Report
                            </button>
                              
                        <?php endif; ?>
                        
                        <?php if ($this->_tpl_vars['MODULE'] == 'Promotionvoucher'): ?>
                            <button title="Generate Voucher" accesskey="E" class="crmbutton small success" onclick="openVoucherDialog('<?php echo $this->_tpl_vars['ID']; ?>
', '<?php echo $this->_tpl_vars['USERID']; ?>
')" type="button" name="generate" id="generate" style="width: auto;">
                                <img src="themes/softed/images/gear-bold.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Generate Voucher
                            </button>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['MODULE'] == 'Premuimproduct'): ?>
                            <button title="Adjust Stock" accesskey="E" class="crmbutton small success" onclick="adjust_stock(<?php echo $this->_tpl_vars['ID']; ?>
)" type="button" name="adjust_stock" id="adjust_stock" style="width: auto;">
                                <img src="themes/softed/images/gear-bold.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;Adjust Stock
                            </button>            
                        <?php endif; ?>
                    	
                        
                        <?php if ($this->_tpl_vars['EDIT_DUPLICATE'] == 'permitted'): ?>
                        	<!-- DUPLICATE To Premuim Product -->
                        	                        	<!-- DUPLICATE To Premuim Product -->

                            
                            <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
                                <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['ticketstatus'] != 'ยกเลิก' && $this->_tpl_vars['ticketstatus'] != 'ปิดงาน' && $this->_tpl_vars['ticketstatus'] != 'อนุมัติเรื่องร้องขอ' && $this->_tpl_vars['ticketstatus'] != 'ไม่อนุมัติเรื่องร้องขอ'): ?>
                                    <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
">
                                        <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                    </button>
                                <?php endif; ?>
                            <?php elseif ($this->_tpl_vars['MODULE'] == 'Servicerequest'): ?>
                                <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['service_request_status'] != 'ปิดงาน' && $this->_tpl_vars['service_request_status'] != 'ยกเลิก'): ?>
                                    <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
" >
                                        <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                    </button>
                                <?php endif; ?>
                            <?php elseif ($this->_tpl_vars['MODULE'] == 'Job'): ?>
                                <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['job_status'] != 'ปิดงาน' && $this->_tpl_vars['job_status'] != 'ยกเลิกงาน'): ?>
                                    <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
" >
                                        <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                    </button>
                                <?php endif; ?>
                            
                            <?php else: ?>
                                <?php 
                                    if($converted=="1"){
                                 ?>
                                <?php 
                                    }else{
                                 ?>
                                    
                                    <?php if ($this->_tpl_vars['MODULE'] == 'Announcement'): ?>
                                        <?php if ($this->_tpl_vars['announcement_status'] == 'Prepare'): ?>
                                         <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                         </button>
                                        <?php endif; ?>
                                    <?php elseif ($this->_tpl_vars['MODULE'] == 'Smartquestionnaire'): ?>
                                        <?php if ($this->_tpl_vars['smartquestionnaire_status'] == 'Prepare'): ?>
                                         <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                         </button>
                                        <?php endif; ?>
                                    <?php elseif ($this->_tpl_vars['MODULE'] == 'SmartSms'): ?>
                                        <?php if ($this->_tpl_vars['sms_status'] == 'Prepare'): ?>
                                         <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                         </button>
                                        <?php endif; ?>
                                    <?php elseif ($this->_tpl_vars['MODULE'] == 'Smartemail'): ?>
                                        <?php if ($this->_tpl_vars['email_status'] == 'Prepare'): ?>
                                         <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                         </button>
                                        <?php endif; ?>
                                    <?php elseif ($this->_tpl_vars['MODULE'] == 'Voucher'): ?>
                                        <!-- Not Edit All user -->
                                    <?php else: ?>
                                         <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                         </button>
                                    <?php endif; ?>
                                <?php 
                                    }
                                 ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['EDIT_DUPLICATE'] == 'permitted' && $this->_tpl_vars['MODULE'] != 'Documents' && $this->_tpl_vars['MODULE'] != 'Opportunity' && $this->_tpl_vars['MODULE'] != 'Voucher'): ?>
                            <?php 
                                if($converted=="1"){
                             ?>
                            <?php 
                                }else{
                             ?>
                                
                                <button title="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_KEY']; ?>
" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; submitFormForAction('DetailView','EditView');" name="Duplicate" style="width: 95px;">
                                    <img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_LABEL']; ?>

                                </button>
                            <?php 
                            }
                             ?>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['DELETE'] == 'permitted'): ?>

                            <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
                                <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['ticketstatus'] != 'ยกเลิก' && $this->_tpl_vars['ticketstatus'] != 'ปิดงาน' && $this->_tpl_vars['ticketstatus'] != 'อนุมัติเรื่องร้องขอ' && $this->_tpl_vars['ticketstatus'] != 'ไม่อนุมัติเรื่องร้องขอ'): ?>
                                    
                                    <button title="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_KEY']; ?>
" class="crmbutton small delete btndelete" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='index'; <?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_ACCOUNT_DELETE_CONFIRMATION']; ?>
' <?php else: ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_DELETE_CONFIRMATION']; ?>
' <?php endif; ?>; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" name="Delete">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>

                                    </button>
                                <?php endif; ?>
                            <?php elseif ($this->_tpl_vars['MODULE'] == 'Servicerequest'): ?>
                                <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['service_request_status'] != 'ปิดงาน' && $this->_tpl_vars['service_request_status'] != 'ยกเลิก'): ?>
                                    
                                    <button title="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_KEY']; ?>
" class="crmbutton small delete" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='index'; <?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_ACCOUNT_DELETE_CONFIRMATION']; ?>
' <?php else: ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_DELETE_CONFIRMATION']; ?>
' <?php endif; ?>; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>
">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>

                                    </button>
                                <?php endif; ?>
                            <?php elseif ($this->_tpl_vars['MODULE'] == 'Job'): ?>
                                <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['job_status'] != 'ปิดงาน' && $this->_tpl_vars['job_status'] != 'ยกเลิกงาน'): ?>
                                    <button title="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_KEY']; ?>
" class="crmbutton small delete" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='index'; <?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_ACCOUNT_DELETE_CONFIRMATION']; ?>
' <?php else: ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_DELETE_CONFIRMATION']; ?>
' <?php endif; ?>; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>
">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>

                                    </button>
                                <?php endif; ?>
                            
                            <?php else: ?>
                                <?php 
                                    if($converted=="1"){
                                 ?>
                                <?php 
                                    }else{
                                        if($accountcode==""){
                                         ?>
                                           
                                            <button title="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_KEY']; ?>
" class="crmbutton small delete btndelete" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='index'; <?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_ACCOUNT_DELETE_CONFIRMATION']; ?>
' <?php else: ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_DELETE_CONFIRMATION']; ?>
' <?php endif; ?>; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" name="Delete" style="width: 75px;">
                                                <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>

                                            </button>
                                        <?php 
                                        }
                                    }
                                 ?>
                            <?php endif; ?>

                        <?php endif; ?>


                        <?php if ($this->_tpl_vars['privrecord'] != ''): ?>
                        <!-- <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" onclick="location.href='index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&viewtype=<?php echo $this->_tpl_vars['VIEWTYPE']; ?>
&action=DetailView&record=<?php echo $this->_tpl_vars['privrecord']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
&start=<?php echo $this->_tpl_vars['privrecordstart']; ?>
'" name="privrecord" value="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" src="<?php echo aicrm_imageurl('rec_prev.gif', $this->_tpl_vars['THEME']); ?>
">&nbsp; -->

                        <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" onclick="location.href='index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&viewtype=<?php echo $this->_tpl_vars['VIEWTYPE']; ?>
&action=DetailView&record=<?php echo $this->_tpl_vars['privrecord']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
&start=<?php echo $this->_tpl_vars['privrecordstart']; ?>
'" name="privrecord" value="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" src="<?php echo aicrm_imageurl('rec_prev.png', $this->_tpl_vars['THEME']); ?>
" style="cursor: pointer;">

                        <?php else: ?>
                        <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" src="<?php echo aicrm_imageurl('rec_prev.png', $this->_tpl_vars['THEME']); ?>
" style="opacity: 0.5;cursor:not-allowed">
                        <!-- <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" src="<?php echo aicrm_imageurl('rec_prev_disabled.gif', $this->_tpl_vars['THEME']); ?>
"> -->
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['privrecord'] != '' || $this->_tpl_vars['nextrecord'] != ''): ?>
                        <!-- <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LBL_JUMP_BTN']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_JUMP_BTN']; ?>
" onclick="var obj = this;var lhref = getListOfRecords(obj, '<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['ID']; ?>
,'<?php echo $this->_tpl_vars['CATEGORY']; ?>
');" name="jumpBtnIdTop" id="jumpBtnIdTop" src="<?php echo aicrm_imageurl('rec_jump.gif', $this->_tpl_vars['THEME']); ?>
">&nbsp; -->
                        <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LBL_JUMP_BTN']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_JUMP_BTN']; ?>
" onclick="var obj = this;var lhref = getListOfRecords(obj, '<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['ID']; ?>
,'<?php echo $this->_tpl_vars['CATEGORY']; ?>
');" name="jumpBtnIdTop" id="jumpBtnIdTop" src="<?php echo aicrm_imageurl('rec_jump.png', $this->_tpl_vars['THEME']); ?>
" style="cursor: pointer;">

                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['nextrecord'] != ''): ?>
                        <!-- <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" onclick="location.href='index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&viewtype=<?php echo $this->_tpl_vars['VIEWTYPE']; ?>
&action=DetailView&record=<?php echo $this->_tpl_vars['nextrecord']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
&start=<?php echo $this->_tpl_vars['nextrecordstart']; ?>
'" name="nextrecord" src="<?php echo aicrm_imageurl('rec_next.gif', $this->_tpl_vars['THEME']); ?>
">&nbsp; -->
                        <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" onclick="location.href='index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&viewtype=<?php echo $this->_tpl_vars['VIEWTYPE']; ?>
&action=DetailView&record=<?php echo $this->_tpl_vars['nextrecord']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
&start=<?php echo $this->_tpl_vars['nextrecordstart']; ?>
'" name="nextrecord" src="<?php echo aicrm_imageurl('rec_next.png', $this->_tpl_vars['THEME']); ?>
" style="cursor: pointer;">
                        <?php else: ?>
                        <!-- <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" src="<?php echo aicrm_imageurl('rec_next_disabled.gif', $this->_tpl_vars['THEME']); ?>
">&nbsp; -->
                        <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" src="<?php echo aicrm_imageurl('rec_next.png', $this->_tpl_vars['THEME']); ?>
" style="opacity: 0.5;cursor:not-allowed">
                        <?php endif; ?>

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
                            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'DetailViewHidden.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                              <!-- Start of File Include by SAKTI on 10th Apr, 2008 -->
                            <?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => "./include/DetailViewBlockStatus.php", 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

                             <!-- Start of File Include by SAKTI on 10th Apr, 2008 -->

                            <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
                                <input type="hidden" name="case_type_detailview" id ="case_type_detailview" value ="<?php echo $this->_tpl_vars['tickettype']; ?>
">
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['MODULE'] == 'Deal'): ?>
                                <tr><td><?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'plugin/Deal/Viewstage.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>
</td></tr>
                            <?php endif; ?>
                            
                            <?php $_from = $this->_tpl_vars['BLOCKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['detail']):
?>
                            <!-- Detailed View Code starts here-->
                            <table border=0 cellspacing=0 cellpadding=0 width=100% class="small">

                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>

                                    <td align=right>
                                    <?php if ($this->_tpl_vars['header'] == $this->_tpl_vars['MOD']['LBL_ADDRESS_INFORMATION'] && ( $this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] != 'Leads' )): ?>

                                        <?php if ($this->_tpl_vars['MODULE'] == 'Leads'): ?>
                                         <!--<input name="mapbutton" value="<?php echo $this->_tpl_vars['APP']['LBL_LOCATE_MAP']; ?>
" class="crmbutton small create" type="button" onClick="searchMapLocation( 'Main' )" title="<?php echo $this->_tpl_vars['APP']['LBL_LOCATE_MAP']; ?>
">-->
                                        <?php else: ?>
                                         <!--<input name="mapbutton" value="<?php echo $this->_tpl_vars['APP']['LBL_LOCATE_MAP']; ?>
" class="crmbutton small create" type="button" onClick="fnvshobj(this,'locateMap');" onMouseOut="fninvsh('locateMap');" title="<?php echo $this->_tpl_vars['APP']['LBL_LOCATE_MAP']; ?>
">-->
                                        <?php endif; ?>

                                    <?php endif; ?>
                                    </td>
                                </tr>
                                
                                <!-- This is added to display the existing comments -->
                                <?php if ($this->_tpl_vars['header'] == $this->_tpl_vars['MOD']['LBL_COMMENTS'] || $this->_tpl_vars['header'] == $this->_tpl_vars['MOD']['LBL_COMMENT_INFORMATION']): ?>
                                    <tr>
                                        <td colspan=4 class="dvInnerHeader">
                                            <a href="javascript:showHideStatus('comments_div','aidcomments_div','<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
');">
                                            <img id="aidcomments_div" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide" /></a>&nbsp;&nbsp;&nbsp;
                                            <b><?php echo $this->_tpl_vars['MOD']['LBL_COMMENTS']; ?>
</b>
                                            <span style="float: right;">
                                                <a href="javascript:void(0);" id="newcomments" class="newcomments" onclick="new_comments(<?php echo $this->_tpl_vars['ID']; ?>
,'<?php echo $this->_tpl_vars['MODULE']; ?>
')" style="color: #000">
                                                <img id="aidcomments_div" src="themes/softed/images/newcomment.png" style="border: 0px solid #000000; width: 24px; height: 21px; margin-top: 2px;" alt="Hide" title="Hide" /><b style="vertical-align: super;">New Comment</b>
                                                </a>
                                            </span>
                                        </td>
                                        
                                    </tr>
                                    <tr><!-- tblCommentInformation -->
                                        <td colspan=4 class="dvtCellInfo"><?php echo $this->_tpl_vars['COMMENT_BLOCK']; ?>
</td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                <?php endif; ?>

                                <?php if ($this->_tpl_vars['header'] == 'History Status'): ?>
                                    <tr>
                                        <td colspan=4 class="dvInnerHeader">
                                            <a href="javascript:showHideStatus('history-div','history-div-img','<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
');">
                                                <img id="history-div-img" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide" /></a>&nbsp;&nbsp;&nbsp;
                                            <b><?php echo $this->_tpl_vars['header']; ?>
</b>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td colspan=4 class="dvtCellInfo" style="border-bottom:none"><?php echo $this->_tpl_vars['HISTORY_STATUS_LIST']; ?>
</td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                <?php endif; ?>

                                <?php if ($this->_tpl_vars['header'] != 'Comments' && $this->_tpl_vars['header'] != 'Comment Information'): ?>
                                    
                                    <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
                                        <?php if ($this->_tpl_vars['header'] == 'รายละเอียดเรื่องร้องขอ'): ?>
                                             <tr class='case-tr-request'>
                                        <?php elseif ($this->_tpl_vars['header'] == 'รายละเอียดเรื่องแจ้งซ่อม' || $this->_tpl_vars['header'] == 'ผลการตรวจสอบหรือแก้ไข รายการแจ้งซ่อม'): ?>
                                             <tr class='case-tr-service'>
                                        <?php elseif ($this->_tpl_vars['header'] == 'รายละเอียดเรื่องร้องเรียน'): ?>
                                             <tr class='case-tr-complain'>
                                        <?php else: ?>
                                            <tr>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <tr>
                                    <?php endif; ?>
                                     <?php echo '<td colspan=4 class="dvInnerHeader"><div style="float:left; font-weight:bold; margin-left: 10px;"><div style="float:left;"><a href="javascript:showHideStatus(\'tbl'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?><?php echo '\',\'aid'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?><?php echo '\',\''; ?><?php echo $this->_tpl_vars['IMAGE_PATH']; ?><?php echo '\');">'; ?><?php if ($this->_tpl_vars['BLOCKINITIALSTATUS'][$this->_tpl_vars['header']] == 1): ?><?php echo '<!-- <img id="aid'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?><?php echo '" src="'; ?><?php echo aicrm_imageurl('activate.gif', $this->_tpl_vars['THEME']); ?><?php echo '" style="border: 0px solid #000000;" alt="Hide" title="Hide"/> --><img id="aid'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?><?php echo '" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide" />'; ?><?php else: ?><?php echo '<!-- <img id="aid'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?><?php echo '" src="'; ?><?php echo aicrm_imageurl('inactivate.gif', $this->_tpl_vars['THEME']); ?><?php echo '" style="border: 0px solid #000000;" alt="Display" title="Display"/> --><img id="aid'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?><?php echo '" src="themes/softed/images/slidedown_b2.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Display" title="Display"/>'; ?><?php endif; ?><?php echo '</a></div><b style="font-family: PromptMedium; font-size: 12px;">&nbsp;&nbsp;&nbsp;'; ?><?php echo $this->_tpl_vars['header']; ?><?php echo '</b></div></td>'; ?>

                                    </tr>
                                <?php endif; ?>

                            </table>
                            
                            <?php if ($this->_tpl_vars['header'] == 'Image Infomation' && $this->_tpl_vars['MODULE'] == 'Job'): ?>
                                <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
                                <tr>
                                    <td colspan=4>
                                        <?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'modules/Job/ViewImage.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

                                    </td>
                                </tr>
                                </table>
                            <?php elseif ($this->_tpl_vars['header'] == 'Image Information' && $this->_tpl_vars['MODULE'] == 'Calendar'): ?>
                                <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
                                <tr>
                                    <td colspan=4>
                                        <?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'modules/Calendar/ViewImage.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

                                    </td>
                                </tr>
                                </table>
                            <?php elseif ($this->_tpl_vars['header'] == 'Products Image Infomation' && $this->_tpl_vars['MODULE'] == 'Job'): ?>
                                <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
                                <tr>
                                    <td colspan=4>
                                        <?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'modules/Job/ViewImage.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

                                    </td>
                                </tr>
                                </table>
                            <?php elseif ($this->_tpl_vars['header'] == 'Receipt Image Information' && $this->_tpl_vars['MODULE'] == 'Job'): ?>
                                <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
                                <tr>
                                    <td colspan=4>
                                        <?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'modules/Job/ViewImagereceipt.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

                                    </td>
                                </tr>
                                </table>
                            <?php elseif ($this->_tpl_vars['header'] == 'Picture Information' && $this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
                                <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
                                <tr>
                                    <td colspan=4>
                                        <?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'modules/HelpDesk/ViewImage.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

                                    </td>
                                </tr>
                                </table>
                            <?php elseif ($this->_tpl_vars['header'] == 'Comment Information'): ?>

                            <?php else: ?>

                                <?php if ($this->_tpl_vars['header'] != 'Comments'): ?>
                                    <?php if ($this->_tpl_vars['BLOCKINITIALSTATUS'][$this->_tpl_vars['header']] == 1): ?>
                                        <div style="width:auto;display:block; border: 1px solid #EDEDED; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;" id="tbl<?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?>
" >
                                    <?php else: ?>
                                        <div style="width:auto;display:none;" id="tbl<?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?>
" >
                                    <?php endif; ?>

                                    <table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">

                                    <?php $_from = $this->_tpl_vars['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['detail']):
?>
                                        <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
                                            <?php if ($this->_tpl_vars['header'] == 'รายละเอียดเรื่องร้องขอ'): ?>
                                                <tr class='case-tr-request'  style="height:25px">
                                            <?php elseif ($this->_tpl_vars['header'] == 'รายละเอียดเรื่องแจ้งซ่อม' || $this->_tpl_vars['header'] == 'ผลการตรวจสอบหรือแก้ไข รายการแจ้งซ่อม'): ?>
                                                <tr class='case-tr-service'  style="height:25px">
                                            <?php elseif ($this->_tpl_vars['header'] == 'รายละเอียดเรื่องร้องเรียน'): ?>
                                                <tr class='case-tr-complain'  style="height:25px">
                                            <?php else: ?>
                                                <tr style="height:25px">
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <tr style="height:25px">
                                        <?php endif; ?>

                                        <?php $_from = $this->_tpl_vars['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['label'] => $this->_tpl_vars['data']):
?>
                                           <?php $this->assign('keyid', $this->_tpl_vars['data']['ui']); ?>
                                           <?php $this->assign('keyval', $this->_tpl_vars['data']['value']); ?>
                                           <?php $this->assign('keytblname', $this->_tpl_vars['data']['tablename']); ?>
                                           <?php $this->assign('keyfldname', $this->_tpl_vars['data']['fldname']); ?>
                                           <?php $this->assign('keyfldid', $this->_tpl_vars['data']['fldid']); ?>
                                           <?php $this->assign('keyoptions', $this->_tpl_vars['data']['options']); ?>
                                           <?php $this->assign('keysecid', $this->_tpl_vars['data']['secid']); ?>
                                           <?php $this->assign('keyseclink', $this->_tpl_vars['data']['link']); ?>
                                           <?php $this->assign('keycursymb', $this->_tpl_vars['data']['cursymb']); ?>
                                           <?php $this->assign('keysalut', $this->_tpl_vars['data']['salut']); ?>
                                           <?php $this->assign('keyaccess', $this->_tpl_vars['data']['notaccess']); ?>
                                           <?php $this->assign('keycntimage', $this->_tpl_vars['data']['cntimage']); ?>
                                           <?php $this->assign('keyadmin', $this->_tpl_vars['data']['isadmin']); ?>
                                           <?php $this->assign('display_type', $this->_tpl_vars['data']['displaytype']); ?>

                                            <?php if ($this->_tpl_vars['label'] != ''): ?>
                                                <?php if ($this->_tpl_vars['keycntimage'] != ''): ?>
                                                    <td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value=<?php echo $this->_tpl_vars['keyadmin']; ?>
></input><?php echo $this->_tpl_vars['keycntimage']; ?>
</td>
                                                <?php elseif ($this->_tpl_vars['keyid'] == '71' || $this->_tpl_vars['keyid'] == '72'): ?><!-- Currency symbol -->
                                                    <td class="dvtCellLabel" align=right width=25%><?php echo $this->_tpl_vars['label']; ?>
<input type="hidden" id="hdtxt_IsAdmin" value=<?php echo $this->_tpl_vars['keyadmin']; ?>
></input> (<?php echo $this->_tpl_vars['keycursymb']; ?>
)</td>
                                                <?php elseif ($this->_tpl_vars['keyid'] == '948'): ?>
                                                
                                                <?php else: ?>
                                                    <td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value=<?php echo $this->_tpl_vars['keyadmin']; ?>
></input><?php echo $this->_tpl_vars['label']; ?>
</td>
                                                <?php endif; ?>

                                                <?php if ($this->_tpl_vars['EDIT_PERMISSION'] == 'yes' && $this->_tpl_vars['display_type'] != '2'): ?>

                                                                                                        <?php if (! empty ( $this->_tpl_vars['DETAILVIEW_AJAX_EDIT'] )): ?>
                                                        <?php 
                                                          if(($cf_2282=="0" or $cf_2282=="") and $converted!="1"){
                                                         ?>
                                                            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "DetailViewUI.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                                        <?php 
                                                          }else if($converted=="1"){
                                                         ?>
                                                            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "DetailViewFields.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                                        <?php 
                                                         }else{
                                                         echo "dddd";
                                                         ?>
                                                            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "DetailViewFields.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                                        <?php 
                                                         }
                                                         ?>
                                                    <?php else: ?>
                                                        <?php if ($this->_tpl_vars['keyfldname'] == 'comments'): ?>
                                                            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "DetailViewUI.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                                        <?php else: ?>
                                                            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "DetailViewFields.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>

                                                    
                                                <?php else: ?>
                                                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "DetailViewFields.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                                                <?php endif; ?>

                                            <?php endif; ?>
                                        <?php endforeach; endif; unset($_from); ?>
                                        </tr>
                                    <?php endforeach; endif; unset($_from); ?>

                                    <?php if ($this->_tpl_vars['header'] == 'Remark' && $this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
                                        <?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'modules/HelpDesk/log_change_status.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

                                    <?php elseif ($this->_tpl_vars['header'] == 'Remark' && $this->_tpl_vars['MODULE'] == 'Servicerequest'): ?>
                                        <?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'modules/Servicerequest/log_change_status.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

                                    <?php elseif ($this->_tpl_vars['header'] == 'Remark' && $this->_tpl_vars['MODULE'] == 'Job'): ?>
                                        <?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'modules/Job/log_change_status.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

                                    <?php endif; ?>

                                    </table>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>                              </td>
                           </tr>
                        <tr>
                        <td style="padding:10px">
                        
                        <?php endforeach; endif; unset($_from); ?>

                
                </td>
            </tr>

            <?php if ($this->_tpl_vars['MODULE'] == 'Claim'): ?>
            <tr>
                <td colspan="4">
                    <?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => "./modules/Claim/ProductDetail/product_list_view.php", 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

                </td>
            </tr>
            <?php endif; ?>

            <!-- Inventory - Product Details informations -->
            <tr>
                <?php echo $this->_tpl_vars['ASSOCIATED_PRODUCTS']; ?>

            </tr>
            <!-- Promotion - Product Details informations -->
            <tr>
                <td align="center">
                    <?php echo $this->_tpl_vars['PROMOTION_TAB']; ?>

                </td>
            </tr>
            </form>

            <?php if ($this->_tpl_vars['MODULE'] == 'Job'): ?>
                <tr>
                    <td style="padding:10px">
                        <table class="small" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr><td class="dvInnerHeader"> <div style="float:left;font-weight:bold;"><b>Error/Spare Part Description</b></div> </td><tr>
                            <tr><td align="center"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'RelatedLists_more.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td> </tr>
                        </table>
                     </td>
                </tr>

            <?php endif; ?>
            <?php if ($this->_tpl_vars['MODULE'] == 'Deal'): ?>
                <tr>
                    <td style="padding:10px">
                        <table class="small" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr><td align="center"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'RelatedLists_more.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td> </tr>
                        </table>
                     </td>
                </tr>

            <?php endif; ?>
            <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
                <tr>
                    <td style="padding:10px">
                        <table class="small" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr><td align="center"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'RelatedLists_more.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td> </tr>
                        </table>
                     </td>
                </tr>

            <?php endif; ?>

            <?php if ($this->_tpl_vars['MODULE'] == 'Servicerequest'): ?>
                <tr>
                    <td style="padding:10px">
                        <table class="small" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr><td align="center"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'RelatedLists_more.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td> </tr>
                        </table>
                     </td>
                </tr>

            <?php endif; ?>

            <!-- End the form related to detail view -->

            <?php if ($this->_tpl_vars['SinglePane_View'] == 'true' && $this->_tpl_vars['IS_REL_LIST'] == 'true'): ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'RelatedListNew.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php endif; ?>
        </table>

        </td>
        <td width=22% valign=top style="border-left:1px dashed #cccccc;padding:13px">

            <!-- right side relevant info -->
            <!-- Action links for Event & Todo START-by Minnie -->
            <?php if ($this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] == 'Potentials' || $this->_tpl_vars['MODULE'] == 'HelpDesk' || $this->_tpl_vars['MODULE'] == 'Leads' || $this->_tpl_vars['MODULE'] == 'Quotes' || ( $this->_tpl_vars['MODULE'] == 'Documents' && ( $this->_tpl_vars['ADMIN'] == 'yes' || $this->_tpl_vars['FILE_STATUS'] == '1' ) && $this->_tpl_vars['FILE_EXIST'] == 'yes' )): ?>
            <table width="100%" border="0" cellpadding="5" cellspacing="0">
                                
                <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>

                                        
                <?php elseif ($this->_tpl_vars['TODO_PERMISSION'] == 'true' || $this->_tpl_vars['EVENT_PERMISSION'] == 'true' || $this->_tpl_vars['CONTACT_PERMISSION'] == 'true' || $this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] == 'Leads' || $this->_tpl_vars['MODULE'] == 'Accounts' || ( $this->_tpl_vars['MODULE'] == 'Documents' )): ?>
                    
                    <?php if ($this->_tpl_vars['MODULE'] != 'Leads'): ?>
                        <tr><td align="left" class="genHeaderSmall"><?php echo $this->_tpl_vars['APP']['LBL_ACTIONS']; ?>
</td></tr>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['MODULE'] == 'Contacts'): ?>
                        <?php $this->assign('subst', 'contact_id'); ?>
                        <?php $this->assign('acc', "&account_id=".($this->_tpl_vars['accountid'])); ?>
                    <?php elseif ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
                        <?php $this->assign('subst', 'account_id'); ?>
                    <?php else: ?>
                        <?php $this->assign('subst', 'parent_id'); ?>
                        <?php $this->assign('acc', ""); ?>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] == 'Accounts'): ?>
                        <?php if ($this->_tpl_vars['SENDMAILBUTTON'] == 'permitted'): ?>
                            <tr>
                                <td align="left" style="padding-left:10px;">
                                <input type="hidden" name="pri_email" value="<?php echo $this->_tpl_vars['EMAIL1']; ?>
"/>
                                <input type="hidden" name="sec_email" value="<?php echo $this->_tpl_vars['EMAIL2']; ?>
"/>
                                <img src="themes/softed/images/mail.png" hspace="5" align="absmiddle"  border="0" style="width: 20px;" />
                                <a href="javascript:void(0);" class="webMnu" onclick="if(LTrim('<?php echo $this->_tpl_vars['EMAIL1']; ?>
') !='' || LTrim('<?php echo $this->_tpl_vars['EMAIL2']; ?>
') !=''){fnvshobj(this,'sendmail_cont');sendmail('<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['ID']; ?>
)}else{OpenCompose('','create')}"><?php echo $this->_tpl_vars['APP']['LBL_SENDMAIL_BUTTON_LABEL']; ?>
</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['EVENT_PERMISSION'] == 'true' && $this->_tpl_vars['MODULE'] != 'Leads'): ?>
                        <tr>
                            <td align="left" style="padding-left:10px;">
                                <img src="themes/softed/images/holiday_b.png" hspace="5" align="absmiddle"  border="0" style="width: 20px;" />
                                <a href="index.php?module=Calendar&action=EditView&return_module=<?php echo $this->_tpl_vars['MODULE']; ?>
&return_action=DetailView&activity_mode=Events&return_id=<?php echo $this->_tpl_vars['ID']; ?>
&<?php echo $this->_tpl_vars['subst']; ?>
=<?php echo $this->_tpl_vars['ID']; ?>
<?php echo $this->_tpl_vars['acc']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
" class="webMnu"><?php echo $this->_tpl_vars['APP']['LBL_ADD_NEW']; ?>
 <?php echo $this->_tpl_vars['APP']['Event']; ?>
</a>
                            </td>
                        </tr>
                    <?php endif; ?>


                    
                    <?php if ($this->_tpl_vars['MODULE'] != 'Contacts' && $this->_tpl_vars['CONTACT_PERMISSION'] == 'true'): ?>
                        <tr>
                            <td align="left" style="padding-left:10px;">
                                <a href="index.php?module=Calendar&action=EditView&return_module=<?php echo $this->_tpl_vars['MODULE']; ?>
&return_action=DetailView&activity_mode=Task&return_id=<?php echo $this->_tpl_vars['ID']; ?>
&<?php echo $this->_tpl_vars['subst']; ?>
=<?php echo $this->_tpl_vars['ID']; ?>
<?php echo $this->_tpl_vars['acc']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
" class="webMnu">
                                    <img src="<?php echo aicrm_imageurl('AddToDo.gif', $this->_tpl_vars['THEME']); ?>
" hspace="5" align="absmiddle" border="0"/>
                                </a>
                                <a href="index.php?module=Calendar&action=EditView&return_module=<?php echo $this->_tpl_vars['MODULE']; ?>
&return_action=DetailView&activity_mode=Task&return_id=<?php echo $this->_tpl_vars['ID']; ?>
&<?php echo $this->_tpl_vars['subst']; ?>
=<?php echo $this->_tpl_vars['ID']; ?>
<?php echo $this->_tpl_vars['acc']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
" class="webMnu"><?php echo $this->_tpl_vars['APP']['LBL_ADD_NEW']; ?>
 <?php echo $this->_tpl_vars['APP']['Todo']; ?>
</a>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['MODULE'] == 'Leads'): ?>
                        <?php if ($this->_tpl_vars['CONVERTLEAD'] == 'permitted'): ?>
                            <?php 
                                if($converted=="1"){
                             ?>
                            <?php 
                                }else{
                             ?>
                            <tr><td align="left" class="genHeaderSmall"><?php echo $this->_tpl_vars['APP']['LBL_ACTIONS']; ?>
</td></tr>
                            <tr>
                                <td align="left" style="padding-left:5px;">
                                   <!--  <a href="javascript:void(0);" class="webMnu" onclick="callConvertLeadDiv('<?php echo $this->_tpl_vars['ID']; ?>
');"><img src="<?php echo aicrm_imageurl('Leads.gif', $this->_tpl_vars['THEME']); ?>
" hspace="5" align="absmiddle"  border="0"/></a> -->
                                    <a href="javascript:void(0);" class="webMnu" onclick="callConvertLeadDiv('<?php echo $this->_tpl_vars['ID']; ?>
');"><img src="<?php echo aicrm_imageurl('repeat.svg', $this->_tpl_vars['THEME']); ?>
" hspace="5" align="absmiddle"  border="0" style="width: 25px; height: 22px;" /></a>
                                    <a href="javascript:void(0);" class="webMnu" onclick="callConvertLeadDiv('<?php echo $this->_tpl_vars['ID']; ?>
');"><?php echo $this->_tpl_vars['APP']['LBL_CONVERT_BUTTON_LABEL']; ?>
</a>
                                </td>
                            </tr>
                            <?php 
                                }
                             ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Start: Actions for Documents Module -->
                    <?php if ($this->_tpl_vars['MODULE'] == 'Documents'): ?>
                        <tr><td align="left" style="padding-left:10px;">
                        <?php if ($this->_tpl_vars['DLD_TYPE'] == 'I' && $this->_tpl_vars['FILE_STATUS'] == '1'): ?>
                            <br><a href="index.php?module=uploads&action=downloadfile&fileid=<?php echo $this->_tpl_vars['FILEID']; ?>
&entityid=<?php echo $this->_tpl_vars['NOTESID']; ?>
"  onclick="javascript:dldCntIncrease(<?php echo $this->_tpl_vars['NOTESID']; ?>
);" class="webMnu"><img src="<?php echo aicrm_imageurl('fbDownload.gif', $this->_tpl_vars['THEME']); ?>
" hspace="5" align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_DOWNLOAD']; ?>
" border="0"/></a>
                            <a href="index.php?module=uploads&action=downloadfile&fileid=<?php echo $this->_tpl_vars['FILEID']; ?>
&entityid=<?php echo $this->_tpl_vars['NOTESID']; ?>
" onclick="javascript:dldCntIncrease(<?php echo $this->_tpl_vars['NOTESID']; ?>
);"><?php echo $this->_tpl_vars['MOD']['LBL_DOWNLOAD_FILE']; ?>
</a>
                        <?php elseif ($this->_tpl_vars['DLD_TYPE'] == 'E' && $this->_tpl_vars['FILE_STATUS'] == '1'): ?>
                            <br><a target="_blank" href="<?php echo $this->_tpl_vars['DLD_PATH']; ?>
" onclick="javascript:dldCntIncrease(<?php echo $this->_tpl_vars['NOTESID']; ?>
);"><img src="<?php echo aicrm_imageurl('fbDownload.gif', $this->_tpl_vars['THEME']); ?>
"" align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_DOWNLOAD']; ?>
" border="0"></a>
                            <a target="_blank" href="<?php echo $this->_tpl_vars['DLD_PATH']; ?>
" onclick="javascript:dldCntIncrease(<?php echo $this->_tpl_vars['NOTESID']; ?>
);"><?php echo $this->_tpl_vars['MOD']['LBL_DOWNLOAD_FILE']; ?>
</a>
                        <?php endif; ?>
                        </td></tr>
                        <?php if ($this->_tpl_vars['CHECK_INTEGRITY_PERMISSION'] == 'yes'): ?>
                            <tr><td align="left" style="padding-left:10px;">
                            <br><a href="javascript:;" onClick="checkFileIntegrityDetailView(<?php echo $this->_tpl_vars['NOTESID']; ?>
);"><img id="CheckIntegrity_img_id" src="<?php echo aicrm_imageurl('yes.gif', $this->_tpl_vars['THEME']); ?>
" alt="Check integrity of this file" title="Check integrity of this file" hspace="5" align="absmiddle" border="0"/></a>
                            <a href="javascript:;" onClick="checkFileIntegrityDetailView(<?php echo $this->_tpl_vars['NOTESID']; ?>
);"><?php echo $this->_tpl_vars['MOD']['LBL_CHECK_INTEGRITY']; ?>
</a>&nbsp;
                            <input type="hidden" id="dldfilename" name="dldfilename" value="<?php echo $this->_tpl_vars['FILEID']; ?>
_<?php echo $this->_tpl_vars['FILENAME']; ?>
">
                            <span id="vtbusy_integrity_info" style="display:none;">
                                <img src="<?php echo aicrm_imageurl('vtbusy.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></span>
                            <span id="integrity_result" style="display:none"></span>
                            </td></tr>
                        <?php endif; ?>
                        <tr><td align="left" style="padding-left:10px;">
                        <?php if ($this->_tpl_vars['DLD_TYPE'] == 'I'): ?>
                            <input type="hidden" id="dldfilename" name="dldfilename" value="<?php echo $this->_tpl_vars['FILEID']; ?>
_<?php echo $this->_tpl_vars['FILENAME']; ?>
">
                            <br><a href="javascript: document.DetailView.return_module.value='Documents'; document.DetailView.return_action.value='DetailView'; document.DetailView.module.value='Documents'; document.DetailView.action.value='EmailFile'; document.DetailView.record.value=<?php echo $this->_tpl_vars['NOTESID']; ?>
; document.DetailView.return_id.value=<?php echo $this->_tpl_vars['NOTESID']; ?>
; sendfile_email();" class="webMnu"><img src="<?php echo aicrm_imageurl('attachment.gif', $this->_tpl_vars['THEME']); ?>
" hspace="5" align="absmiddle" border="0"/></a>
                            <a href="javascript: document.DetailView.return_module.value='Documents'; document.DetailView.return_action.value='DetailView'; document.DetailView.module.value='Documents'; document.DetailView.action.value='EmailFile'; document.DetailView.record.value=<?php echo $this->_tpl_vars['NOTESID']; ?>
; document.DetailView.return_id.value=<?php echo $this->_tpl_vars['NOTESID']; ?>
; sendfile_email();"><?php echo $this->_tpl_vars['MOD']['LBL_EMAIL_FILE']; ?>
</a>
                        <?php endif; ?>
                        </td></tr>
                        <tr><td>&nbsp;</td></tr>
                    <?php endif; ?>

                <?php endif; ?>

                <!-- End: Actions for Documents Module -->
            </table>
                                <?php if (! isset ( $this->_tpl_vars['CUSTOM_LINKS'] ) || empty ( $this->_tpl_vars['CUSTOM_LINKS'] )): ?>
                <br>
                <?php endif; ?>
            <?php endif; ?>

                            <?php if ($this->_tpl_vars['CUSTOM_LINKS'] && $this->_tpl_vars['CUSTOM_LINKS']['DETAILVIEWBASIC']): ?>
                    <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <?php $_from = $this->_tpl_vars['CUSTOM_LINKS']['DETAILVIEWBASIC']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CUSTOMLINK']):
?>
                    <tr>
                        <td align="left" style="padding-left:10px;">
                            <?php $this->assign('customlink_href', $this->_tpl_vars['CUSTOMLINK']->linkurl); ?>
                            <?php $this->assign('customlink_label', $this->_tpl_vars['CUSTOMLINK']->linklabel); ?>
                            <?php if ($this->_tpl_vars['customlink_label'] == ''): ?>
                                <?php $this->assign('customlink_label', $this->_tpl_vars['customlink_href']); ?>
                            <?php else: ?>
                                                                <?php $this->assign('customlink_label', getTranslatedString($this->_tpl_vars['customlink_label'], $this->_tpl_vars['CUSTOMLINK']->module())); ?>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['CUSTOMLINK']->linkicon): ?>

                                <a class="webMnu" href="<?php echo $this->_tpl_vars['customlink_href']; ?>
"><img hspace=5 align="absmiddle" border=0 src="<?php echo $this->_tpl_vars['CUSTOMLINK']->linkicon; ?>
" style="width: 20px;"></a>

                            <?php endif; ?>
                                <a class="webMnu" href="<?php echo $this->_tpl_vars['customlink_href']; ?>
"><?php echo $this->_tpl_vars['customlink_label']; ?>
</a>
                        </td>
                    </tr>
                    <?php endforeach; endif; unset($_from); ?>
                    </table>
                <?php endif; ?>
            
            <?php if ($this->_tpl_vars['CUSTOM_LINKS']): ?>
                <br>
                <?php if (isset ( $this->_tpl_vars['CUSTOM_LINKS']['DETAILVIEW'] )): ?>
                    <?php $this->assign('CUSTOM_LINKS', $this->_tpl_vars['CUSTOM_LINKS']['DETAILVIEW']); ?>
                <?php endif; ?>

                <?php if (! empty ( $this->_tpl_vars['CUSTOM_LINKS'] )): ?>
                    <table width="100%" border="0" cellpadding="5" cellspacing="0">
                        <tr><td align="left" class="dvtUnSelectedCell dvtCellLabel">
                            <a href="javascript:;" onmouseover="fnvshobj(this,'vtlib_customLinksLay');" onclick="fnvshobj(this,'vtlib_customLinksLay');"><b><?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_ACTIONS']; ?>
 &#187;</b></a>
                        </td></tr>

                    </table>
                    <br>
                    <div style="display: none; left: 193px; top: 106px;width:155px; position:absolute;" id="vtlib_customLinksLay"
                        onmouseout="fninvsh('vtlib_customLinksLay')" onmouseover="fnvshNrm('vtlib_customLinksLay')">
                        <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr><td style="border-bottom: 1px solid rgb(204, 204, 204); padding: 5px;"><b><?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_ACTIONS']; ?>
 &#187;</b></td></tr>
                        <tr>
                            <td>
                                <?php $_from = $this->_tpl_vars['CUSTOM_LINKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CUSTOMLINK']):
?>
                                    <?php $this->assign('customlink_href', $this->_tpl_vars['CUSTOMLINK']->linkurl); ?>
                                    <?php $this->assign('customlink_label', $this->_tpl_vars['CUSTOMLINK']->linklabel); ?>
                                    <?php if ($this->_tpl_vars['customlink_label'] == ''): ?>
                                        <?php $this->assign('customlink_label', $this->_tpl_vars['customlink_href']); ?>
                                    <?php else: ?>
                                                                                <?php $this->assign('customlink_label', getTranslatedString($this->_tpl_vars['customlink_label'], $this->_tpl_vars['CUSTOMLINK']->module())); ?>
                                    <?php endif; ?>
                                    <a href="<?php echo $this->_tpl_vars['customlink_href']; ?>
" class="drop_down"><?php echo $this->_tpl_vars['customlink_label']; ?>
</a>
                                <?php endforeach; endif; unset($_from); ?>
                            </td>
                        </tr>
                        </table>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

                                <!-- Action links END -->
    <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
        <!-- <table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
        <td align="left">
        <span class="genHeaderSmall">เอกสาร</span><br />
        </td>
        </tr>
        <tr>
        <td align="left" style="padding-left:10px;">
        <a class="webMnu" href="#" onclick="JavaScript: void window.open('Report/report_case_01.php?aicrm=<?php echo $this->_tpl_vars['ID']; ?>
','HelpDesk','resizable=yes,left=20,top=10,width=990,height=800,toolbar=no,scrollbars=yes,menubar=no,location=no');">
            <img src="<?php echo aicrm_imageurl('actionGeneratePDF.gif', $this->_tpl_vars['THEME']); ?>
" hspace="5" align="absmiddle" border="0"/>แบบฟอร์มใบแจ้งซ่อมแผนก PCD
        </a>
        </td>
        </tr>
        <tr>
        <td>&nbsp;</td>
        </tr>
        </table> -->
    <?php endif; ?>

    
    <?php if ($this->_tpl_vars['MODULE'] == 'Deal'): ?>
        <table width="100%" border="0" cellpadding="5" cellspacing="0">
            <tr><td align="left" class="genHeaderSmall"><?php echo $this->_tpl_vars['APP']['LBL_ACTIONS']; ?>
</td></tr>

            <tr>
                <td align="left" class="genHeaderSmall submenu">
                        <a href="index.php?module=Quotes&action=EditView&return_module=<?php echo $this->_tpl_vars['MODULE']; ?>
&return_action=DetailView&activity_mode=Events&return_id=<?php echo $this->_tpl_vars['ID']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
" style="text-decoration: none;">
                        <img src="themes/softed/images/create.png" hspace="5" align="absmiddle"  border="0" style="width: 20px;" />
                        Create Quotation</a>
                </td>
            </tr>
            <tr>
                <td align="left" class="genHeaderSmall submenu">
                        <a href='MOAIMB-Webview/Projects/create_web?userid=<?php echo $this->_tpl_vars['USERID']; ?>
&return_module=<?php echo $this->_tpl_vars['MODULE']; ?>
&dealId=<?php echo $this->_tpl_vars['DEALID']; ?>
&dealNo=<?php echo $this->_tpl_vars['DEALNO']; ?>
' class='ls-modal' style="text-decoration: none;">
                            <img src="themes/softed/images/create.png" hspace="5" align="absmiddle"  border="0" style="width: 20px;" />
                            Create Project Orders
                        </a>
                </td>
            </tr>
        </table>
        <br>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['MODULE'] == 'Contacts'): ?>
           <?php endif; ?>

    <!--Open History Activit -->
    <!-- <table width="100%" border="0" cellpadding="5" cellspacing="0">
        <tr>
            <td align="left">
                <span class="genHeaderSmall">History</span><br />
            </td>
        </tr>
        <tr>
            <td align="left" style="padding-left:10px;">
                <a class="webMnu" href="#" onclick="JavaScript: void window.open('Report/report_job.php?dealid=<?php echo $this->_tpl_vars['ID']; ?>
&type=CASH','Job','resizable=yes,left=20,top=10,width=990,height=800,toolbar=no,scrollbars=yes,menubar=no,location=no');">
                    <img src="<?php echo aicrm_imageurl('themes/softed/images/timeline.png', $this->_tpl_vars['THEME']); ?>
" hspace="5" align="absmiddle" border="0" style="width: 20px; margin-right: 10px;"/>Activit Timeline
                </a>
            </td>
        </tr>
    </table><br /> -->
    <!--Closed History Activit -->

    
    <?php if ($this->_tpl_vars['TAG_CLOUD_DISPLAY'] == 'true'): ?>
        <!-- Tag cloud display -->
        <table border=0 cellspacing=0 cellpadding=0 width=100% class="tagCloud">

            <?php if ($this->_tpl_vars['MODULE'] == 'Claim'): ?>
                <tr><td align="left" class="genHeaderSmall"  style="padding-top:10px;">Quick Action</td></tr>
                <tr>
                    <td align="left" style="padding:10px 10px 20px;">
                        <a class="webMnu" href="<?php echo $this->_tpl_vars['Report_URL']; ?>
rpt_claim.rptdesign&claimid=<?php echo $this->_tpl_vars['ID']; ?>
&__format=pdf" target="_blank" style="font-family: PromptMedium; font-weight: 400; color: #2B2B2B; font-size: 11px;">
                            <img src="<?php echo aicrm_imageurl('themes/softed/images/pdf.png', $this->_tpl_vars['THEME']); ?>
" hspace="5" align="absmiddle" border="0" style="width: 28px; margin-right: 10px;"/>Claim
                        </a>
                    </td>
                </tr>
            <?php endif; ?>
            
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
                    <div id="tagfields"><?php echo $this->_tpl_vars['ALL_TAG']; ?>
</div>
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
                    <div id="tagfields"><?php echo $this->_tpl_vars['ALL_TAG']; ?>
</div>
                </td>
            </tr>
            <tr >
            	<td>
            		<span style="color: #ff7488;font-size: 12px;line-height: 3;font-family:PromptMedium, serif; display: none" class="error-tag" id="error-tag"><img src="themes/softed/images/warning-duotone-red.png" style="width: 20px;height: 20px;vertical-align: middle;margin-right: 5px;" >จำนวนแท็กไม่เกิน 10 แท็ก / 1 รายการ</span>
            	</td>
            </tr>
            <!-- <tr >
                <td class="tagCloudDisplay" valign=top> <span id="tagfields"><?php echo $this->_tpl_vars['ALL_TAG']; ?>
</span></td>
            </tr> -->

        </table>
        <!-- End Tag cloud display -->
    <?php endif; ?>

            <!-- Mail Merge-->
                <br>
                <!-- <?php if ($this->_tpl_vars['MERGEBUTTON'] == 'permitted'): ?>
                <form action="index.php" method="post" name="TemplateMerge" id="form">
                <input type="hidden" name="module" value="<?php echo $this->_tpl_vars['MODULE']; ?>
">
                <input type="hidden" name="parenttab" value="<?php echo $this->_tpl_vars['CATEGORY']; ?>
">
                <input type="hidden" name="record" value="<?php echo $this->_tpl_vars['ID']; ?>
">
                <input type="hidden" name="action">
                <table border=0 cellspacing=0 cellpadding=0 width=100% class="rightMailMerge">
                    <tr>
                           <td class="rightMailMergeHeader"><b><?php echo $this->_tpl_vars['WORDTEMPLATEOPTIONS']; ?>
</b></td>
                    </tr>
                    <tr style="height:25px">
                    <td class="rightMailMergeContent">
                        <?php if ($this->_tpl_vars['TEMPLATECOUNT'] != 0): ?>
                        <select name="mergefile"><?php $_from = $this->_tpl_vars['TOPTIONS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['templid'] => $this->_tpl_vars['tempflname']):
?><option value="<?php echo $this->_tpl_vars['templid']; ?>
"><?php echo $this->_tpl_vars['tempflname']; ?>
</option><?php endforeach; endif; unset($_from); ?></select>
                         <input class="crmbutton small create" value="<?php echo $this->_tpl_vars['APP']['LBL_MERGE_BUTTON_LABEL']; ?>
" onclick="this.form.action.value='Merge';" type="submit"></input>
                        <?php else: ?>
                        <a href=index.php?module=Settings&action=upload&tempModule=<?php echo $this->_tpl_vars['MODULE']; ?>
&parenttab=Settings><?php echo $this->_tpl_vars['APP']['LBL_CREATE_MERGE_TEMPLATE']; ?>
</a>
                        <?php endif; ?>
                    </td>
                    </tr>
                </table>
                </form>
                <?php endif; ?> -->

        
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

                    <td class="dvtSelectedCellBottom" align=center nowrap><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['SINGLE_MOD']]; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</td>
                    <!-- <td class="dvtTabCacheBottom" style="width:10px">&nbsp;</td> -->
                    <?php if ($this->_tpl_vars['SinglePane_View'] == 'false'): ?>
                    <td class="dvtUnSelectedCellBottom" align=center nowrap><a href="index.php?action=CallRelatedList&module=<?php echo $this->_tpl_vars['MODULE']; ?>
&record=<?php echo $this->_tpl_vars['ID']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</a></td>
                    <?php endif; ?>
                    <td class="dvtUnSelectedCellBottom" align=center nowrap><a href="index.php?action=TimelineList&module=<?php echo $this->_tpl_vars['MODULE']; ?>
&record=<?php echo $this->_tpl_vars['ID']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LBL_TIMELINE']; ?>
</a></td>
                    <td class="dvtTabCacheBottom" align="right" style="width:100%">

                        <?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
                            <?php if ($this->_tpl_vars['approved_status'] != 'อนุมัติ'): ?>
                                <?php if ($this->_tpl_vars['is_admin'] == 1 || $this->_tpl_vars['roleid'] == 'H2' || $this->_tpl_vars['roleid'] == 'H738'): ?>
                                    <input class="crmbutton small edit" type="button" value="Approved" onclick="confirm_send('Approved',<?php echo $this->_tpl_vars['ID']; ?>
);" />&nbsp;
                                <?php endif; ?>
                            <?php endif; ?>

                        <?php endif; ?>

                        <!--Send Data to RMS (Bas 2016-08-18)-->
                        <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['ticketstatus'] != '--None--' && $this->_tpl_vars['ticketstatus'] != 'ยกเลิก' && $this->_tpl_vars['ticketstatus'] != 'ปิดงาน' && $this->_tpl_vars['ticketstatus'] != 'อนุมัติเรื่องร้องขอ' && $this->_tpl_vars['ticketstatus'] != 'ไม่อนุมัติเรื่องร้องขอ'): ?>
                                <input class="crmbutton small edit" type="button" value="ส่งต่อ" onclick="JavaScript: void window.open('case_transfer.php?casestatus=ส่งต่อ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=350,top=50,width=500,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && ( $this->_tpl_vars['ticketstatus'] == 'เปิด' || $this->_tpl_vars['ticketstatus'] == 'ระหว่างดำเนินการ' )): ?>
                                <input class="crmbutton small edit" type="button" value="รับงาน" onclick="JavaScript: void window.open('case_status.php?casestatus=รับงาน&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && ( $this->_tpl_vars['tickettype'] != 'ร้องเรียน' && $this->_tpl_vars['tickettype'] != 'ร้องขอ' ) && ( $this->_tpl_vars['ticketstatus'] == 'Wait For Response' || $this->_tpl_vars['ticketstatus'] == 'Acknowledge Job' )): ?>
                                <input class="crmbutton small edit" type="button" value="เช็คอิน" onclick="JavaScript: void window.open('case_status.php?casestatus=เช็คอิน&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && ( $this->_tpl_vars['ticketstatus'] == 'Acknowledge Job' || $this->_tpl_vars['ticketstatus'] == 'เช็คอิน' )): ?>
                                <input class="crmbutton small edit" type="button" value="อยู่ระหว่างดำเนินการ" onclick="JavaScript: void window.open('case_status.php?casestatus=อยู่ระหว่างดำเนินการ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['tickettype'] != 'ร้องขอ' && $this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['ticketstatus'] != '--None--' && $this->_tpl_vars['ticketstatus'] != 'ยกเลิก' && $this->_tpl_vars['ticketstatus'] != 'ปิดงาน'): ?>
                                <input class="crmbutton small edit" type="button" value="ปิดงาน" onclick="JavaScript: void window.open('case_status.php?casestatus=ปิดงาน&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['tickettype'] == 'ร้องขอ' && $this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['ticketstatus'] != '--None--' && $this->_tpl_vars['ticketstatus'] != 'ยกเลิก' && $this->_tpl_vars['ticketstatus'] != 'ปิดงาน' && $this->_tpl_vars['ticketstatus'] != 'อนุมัติเรื่องร้องขอ' && $this->_tpl_vars['ticketstatus'] != 'ไม่อนุมัติเรื่องร้องขอ'): ?>
                                <input class="crmbutton small edit" type="button" value="อนุมัติเรื่องร้องขอ" onclick="JavaScript: void window.open('case_status.php?casestatus=อนุมัติเรื่องร้องขอ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                                <input class="crmbutton small edit" type="button" value="ไม่อนุมัติเรื่องร้องขอ" onclick="JavaScript: void window.open('case_status.php?casestatus=ไม่อนุมัติเรื่องร้องขอ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['ticketstatus'] != '--None--' && $this->_tpl_vars['ticketstatus'] != 'ยกเลิก' && $this->_tpl_vars['ticketstatus'] != 'ปิดงาน' && $this->_tpl_vars['ticketstatus'] != 'อนุมัติเรื่องร้องขอ' && $this->_tpl_vars['ticketstatus'] != 'ไม่อนุมัติเรื่องร้องขอ'): ?>
                                <input class="crmbutton small edit" type="button" value="ยกเลิก" onclick="JavaScript: void window.open('case_status.php?casestatus=ยกเลิก&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['MODULE'] == 'Servicerequest'): ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['service_request_status'] != '--None--' && $this->_tpl_vars['service_request_status'] != 'ปิดงาน' && $this->_tpl_vars['service_request_status'] != 'ยกเลิก'): ?>
                                <input class="crmbutton small edit" type="button" value="ส่งต่อ" onclick="JavaScript: void window.open('servicerequest_transfer.php?service_request_status=ส่งต่อ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=350,top=50,width=500,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && ( $this->_tpl_vars['service_request_status'] == 'เปิดงาน' )): ?>
                                <input class="crmbutton small edit" type="button" value="รับงาน" onclick="JavaScript: void window.open('servicerequest_status.php?service_request_status=อยู่ระหว่างดำเนินการ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=460,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                                                        
                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['service_request_status'] != '--None--' && $this->_tpl_vars['service_request_status'] != 'ปิดงาน' && $this->_tpl_vars['service_request_status'] != 'ยกเลิก'): ?>
                                <input class="crmbutton small edit" type="button" value="ยกเลิก" onclick="JavaScript: void window.open('servicerequest_status.php?service_request_status=ยกเลิก&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['MODULE'] == 'Job'): ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['job_status'] != '--None--' && $this->_tpl_vars['job_status'] != 'ปิดงาน' && $this->_tpl_vars['job_status'] != 'ยกเลิกงาน'): ?>
                                <input class="crmbutton small edit" type="button" value="ส่งต่อ" onclick="JavaScript: void window.open('job_transfer.php?job_status=ส่งต่อ&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=350,top=50,width=500,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && ( $this->_tpl_vars['job_status'] == 'เปิดงาน' )): ?>
                                <input class="crmbutton small edit" type="button" value="รับงาน" onclick="JavaScript: void window.open('job_status.php?job_status=อยู่ระหว่างดำเนินงาน&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=460,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['job_status'] != '--None--' && $this->_tpl_vars['job_status'] != 'ปิดงาน' && $this->_tpl_vars['job_status'] != 'ยกเลิกงาน'): ?>
                                <input class="crmbutton small edit" type="button" value="ปิดงาน" onclick="JavaScript: void window.open('job_status.php?job_status=ปิดงาน&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['job_status'] != '--None--' && $this->_tpl_vars['job_status'] != 'ปิดงาน' && $this->_tpl_vars['job_status'] != 'ยกเลิกงาน'): ?>
                                <input class="crmbutton small edit" type="button" value="ยกเลิก" onclick="JavaScript: void window.open('job_status.php?job_status=ยกเลิก&crmid=<?php echo $this->_tpl_vars['ID']; ?>
','Case','resizable=yes,left=300,top=150,width=450,height=250,toolbar=no,scrollbars=yes,menubar=no,location=no')"  />&nbsp;
                            <?php endif; ?>

                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['EDIT_DUPLICATE'] == 'permitted'): ?>
                            <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
                                <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['ticketstatus'] != 'ยกเลิก' && $this->_tpl_vars['ticketstatus'] != 'ปิดงาน' && $this->_tpl_vars['ticketstatus'] != 'อนุมัติเรื่องร้องขอ' && $this->_tpl_vars['ticketstatus'] != 'ไม่อนุมัติเรื่องร้องขอ'): ?>
                                    <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
" >
                                        <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                    </button>
                                <?php endif; ?>
                            <?php elseif ($this->_tpl_vars['MODULE'] == 'Servicerequest'): ?>
                                <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['service_request_status'] != 'ปิดงาน' && $this->_tpl_vars['service_request_status'] != 'ยกเลิก'): ?>
                                    <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
" >
                                        <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                    </button>
                                <?php endif; ?>
                            <?php elseif ($this->_tpl_vars['MODULE'] == 'Job'): ?>
                                <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['job_status'] != 'ปิดงาน' && $this->_tpl_vars['job_status'] != 'ยกเลิกงาน'): ?>
                                    <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
" >
                                        <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                    </button>
                                <?php endif; ?>
                           
                            <?php else: ?>
                                <?php 
                                    if($converted=="1"){
                                 ?>
                                <?php 
                                    }else{
                                 ?>
                                    <?php if ($this->_tpl_vars['MODULE'] == 'Announcement'): ?>
                                         <?php if ($this->_tpl_vars['announcement_status'] == 'Prepare'): ?>
                                            <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                            </button>
                                         <?php endif; ?>
                                    <?php elseif ($this->_tpl_vars['MODULE'] == 'Smartquestionnaire'): ?>
                                        <?php if ($this->_tpl_vars['smartquestionnaire_status'] == 'Prepare'): ?>
                                         <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                         </button>
                                        <?php endif; ?>
                                    <?php elseif ($this->_tpl_vars['MODULE'] == 'SmartSms'): ?>
                                        <?php if ($this->_tpl_vars['sms_status'] == 'Prepare'): ?>
                                         <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                         </button>
                                        <?php endif; ?>
                                    <?php elseif ($this->_tpl_vars['MODULE'] == 'Smartemail'): ?>
                                        <?php if ($this->_tpl_vars['email_status'] == 'Prepare'): ?>
                                         <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                         </button>
                                        <?php endif; ?>
                                    <?php elseif ($this->_tpl_vars['MODULE'] == 'Voucher'): ?>
                                        <!-- Not Edit All User -->
                                    <?php else: ?>
                                        <button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
';submitFormForAction('DetailView','EditView');" type="button" name="Edit" style="width: 65px;">
                                            <img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

                                        </button>
                                    <?php endif; ?>
                                <?php 
                                    }
                                 ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['EDIT_DUPLICATE'] == 'permitted' && $this->_tpl_vars['MODULE'] != 'Documents' && $this->_tpl_vars['MODULE'] != 'Voucher'): ?>
                            <?php 
                                if($converted=="1"){
                             ?>
                            <?php 
                                }else{
                             ?>
                                
                                <button title="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_KEY']; ?>
" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; submitFormForAction('DetailView','EditView');" name="Duplicate" style="width: 95px;">
                                        <img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_LABEL']; ?>

                                    </button>
                                
                            <?php 
                                }
                             ?>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['DELETE'] == 'permitted'): ?>
                            <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
                                <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['ticketstatus'] != 'ยกเลิก' && $this->_tpl_vars['ticketstatus'] != 'ปิดงาน' && $this->_tpl_vars['ticketstatus'] != 'อนุมัติเรื่องร้องขอ' && $this->_tpl_vars['ticketstatus'] != 'ไม่อนุมัติเรื่องร้องขอ'): ?>
                                    
                                    <button title="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_KEY']; ?>
" class="crmbutton small delete" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='index'; <?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_ACCOUNT_DELETE_CONFIRMATION']; ?>
' <?php else: ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_DELETE_CONFIRMATION']; ?>
' <?php endif; ?>; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>
">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>

                                    </button>
                                <?php endif; ?>
                            <?php elseif ($this->_tpl_vars['MODULE'] == 'Servicerequest'): ?>
                                <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['service_request_status'] != 'ปิดงาน' && $this->_tpl_vars['service_request_status'] != 'ยกเลิก'): ?>
                                    
                                    <button title="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_KEY']; ?>
" class="crmbutton small delete" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='index'; <?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_ACCOUNT_DELETE_CONFIRMATION']; ?>
' <?php else: ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_DELETE_CONFIRMATION']; ?>
' <?php endif; ?>; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>
">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>

                                    </button>
                                <?php endif; ?>
                            <?php elseif ($this->_tpl_vars['MODULE'] == 'Job'): ?>
                                <?php if ($this->_tpl_vars['flagassign'] == true && $this->_tpl_vars['job_status'] != 'ปิดงาน' && $this->_tpl_vars['job_status'] != 'ยกเลิกงาน'): ?>
                                    <button title="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_KEY']; ?>
" class="crmbutton small delete" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='index'; <?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_ACCOUNT_DELETE_CONFIRMATION']; ?>
' <?php else: ?> var confirmMsg = '<?php echo $this->_tpl_vars['APP']['NTC_DELETE_CONFIRMATION']; ?>
' <?php endif; ?>; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>
">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_DELETE_BUTTON_LABEL']; ?>

                                    </button>
                                <?php endif; ?>
                           
                            <?php else: ?>
                                <?php 
                                    if($converted=="1"){
                                 ?>
                                <?php 
                                    }else{
                                        if($accountcode==""){
                                 ?>
            
                                    <button title="Delete [Alt+D]" accesskey="D" class="crmbutton small delete btndelete" onclick="DetailView.return_module.value='Accounts'; DetailView.return_action.value='index';  var confirmMsg = 'Deleting this account will remove its related Potentials &amp; Quotes. Are you sure you want to delete this account?' ; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" name="Delete" style="width: 75px;">
                                            <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;Delete
                                    </button>

                                <?php 
                                        }
                                    }
                                 ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['privrecord'] != ''): ?>
                       
                            <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" onclick="location.href='index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&viewtype=<?php echo $this->_tpl_vars['VIEWTYPE']; ?>
&action=DetailView&record=<?php echo $this->_tpl_vars['privrecord']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
&start=<?php echo $this->_tpl_vars['privrecordstart']; ?>
'" name="privrecord" value="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" src="<?php echo aicrm_imageurl('rec_prev.png', $this->_tpl_vars['THEME']); ?>
" style="cursor: pointer;">

                        <?php else: ?>
                            <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_PREVIOUS']; ?>
" src="<?php echo aicrm_imageurl('rec_prev.png', $this->_tpl_vars['THEME']); ?>
" style="opacity: 0.5;cursor:not-allowed">
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['privrecord'] != '' || $this->_tpl_vars['nextrecord'] != ''): ?>
                        
                            <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LBL_JUMP_BTN']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_JUMP_BTN']; ?>
" onclick="var obj = this;var lhref = getListOfRecordsbutton(obj, '<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['ID']; ?>
,'<?php echo $this->_tpl_vars['CATEGORY']; ?>
');" name="jumpBtnIdBottom" id="jumpBtnIdBottom" src="<?php echo aicrm_imageurl('rec_jump.png', $this->_tpl_vars['THEME']); ?>
" style="cursor: pointer;">
                        <?php endif; ?>

                        <?php if ($this->_tpl_vars['nextrecord'] != ''): ?>
                            <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" onclick="location.href='index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&viewtype=<?php echo $this->_tpl_vars['VIEWTYPE']; ?>
&action=DetailView&record=<?php echo $this->_tpl_vars['nextrecord']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
'" name="nextrecord" src="<?php echo aicrm_imageurl('rec_next.png', $this->_tpl_vars['THEME']); ?>
" style="cursor: pointer;">
                        <?php else: ?>

                            <img align="absmiddle" title="<?php echo $this->_tpl_vars['APP']['LNK_LIST_NEXT']; ?>
" src="<?php echo aicrm_imageurl('rec_next.png', $this->_tpl_vars['THEME']); ?>
" style="opacity: 0.5; cursor:not-allowed">
                        
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<?php if ($this->_tpl_vars['MODULE'] == 'Deal'): ?>
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
<?php echo '

jQuery(\'.ls-modal\').on(\'click\', function(e){
  jQuery(\'iframe\').attr("src", ""); 
  e.preventDefault();
  
  jQuery(\'#myModal\').modal(\'show\').find(\'.modal-body\');
  jQuery(\'iframe\').attr("src", jQuery(this).attr(\'href\')); 
});

function closeModal()
{
    jQuery(\'#myModal\').modal(\'hide\')
}

'; ?>

</script>
<?php endif; ?>

<?php if ($this->_tpl_vars['MODULE'] == 'Products'): ?>
<script language="JavaScript" type="text/javascript" src="modules/Products/Productsslide.js"></script>
<script language="JavaScript" type="text/javascript">Carousel();</script>
<?php endif; ?>

<script>
function getTagCloud()
{
new Ajax.Request(
        'index.php',
        {queue: {position: 'end', scope: 'command'},
        method: 'post',
        postBody: 'module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=<?php echo $this->_tpl_vars['MODULE']; ?>
Ajax&file=TagCloud&ajxaction=GETTAGCLOUD&recordid=<?php echo $this->_tpl_vars['ID']; ?>
',
        onComplete: function(response) {
                $("tagfields").innerHTML=response.responseText;

                var numItems = jQuery('.tagit').length;

                jQuery(".tagscount").html(numItems);
            }
        }
);
}
getTagCloud();
</script>
<!-- added for validation -->
<script language="javascript">
    var fielduitype = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_UITYPE']; ?>
)
    var fieldname = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDNAME']; ?>
);
    var fieldlabel = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDLABEL']; ?>
);
    var fielddatatype = new Array(<?php echo $this->_tpl_vars['VALIDATION_DATA_FIELDDATATYPE']; ?>
);

</script>
  </td>
  <td align=right valign=top><img src="<?php echo aicrm_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
"></td>
 </tr>
</table>

<?php if ($this->_tpl_vars['MODULE'] != 'Leads' || $this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Campaigns'): ?>
    <form name="SendMail"><div id="sendmail_cont" style="z-index:100001;position:absolute;"></div></form>
<?php endif; ?>