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
{if $smarty.request.ajax neq ''}
    &#&#&#{$ERROR}&#&#&#
{/if}

<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
<link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css">

<script>
    {literal}
    function check_btn1(module, crmid) {
        window.open('import_pre.php', 'Quotes', 'resizable=1,left=100,top=150,width=1000,height=400,toolbar=no,scrollbars=no,menubar=no,location=no')
    }
    
    function check_cam(module, crmid) {
        window.open('import_cam.php', 'Quotes', 'resizable=1,left=100,top=150,width=1000,height=400,toolbar=no,scrollbars=no,menubar=no,location=no')
    }

    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    function per_padding (){
        var qty = document.getElementById("pagesize");
        var val = qty.options[qty.selectedIndex].value;
        
        var url = document.URL;
        var pagesize = getParameterByName('pagesize');

        if(pagesize != null && pagesize !=''){
            url = url.replace("pagesize="+pagesize, ""); 
            var reload_url = url+"pagesize="+val ;
            window.location.href = reload_url; 
        }else{
            var reload_url = url+"&pagesize="+val ;
            window.location.href = reload_url; 
        }
    }
    function modal_competitor(){
        var msg = '';
        url = 'plugin/Competitor/competitoranalysis.php';
        jQuery('#dialog_listview').window({
            title: 'Competitor Analysis',
            width: 900,
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

    function getERPProduct()
    {
        jQuery.messager.progress({
            title: 'Please waiting',
            msg: 'Loading ...',
            text: 'LOADING'
        });

        jQuery.post('index.php', {
            module: 'Products',
            action: 'ProductsAjax',
            ajax: true,
            file: 'GetERPProduct',
        }, function(rs){
            jQuery.messager.progress('close')
            jQuery.messager.alert('Get Product result', 'Update product completed', function(r){
                if (r){
                    
                }
            });
            console.log(rs)
        }, 'json')
    }
{/literal}
</script>

<style>
{literal}
    #ListViewContents table.small tbody TR.danger {
        border-top: 1px solid #fff;
        border-left: 1px solid #ddd;
        border-right: 0px solid #fff;
        border-bottom: 0px solid #fff;
        background: #F2DEDE !important;
    }

    #ListViewContents table.small tbody TR.success {
        border-top: 1px solid #fff;
        border-left: 1px solid #ddd;
        border-right: 0px solid #fff;
        border-bottom: 0px solid #fff;
        background: #DFF0D8 !important;
    }

    #ListViewContents table.small tbody TR.info {
        border-top: 1px solid #fff;
        border-left: 1px solid #ddd;
        border-right: 0px solid #fff;
        border-bottom: 0px solid #fff;
        background: #D9EDF7 !important;
    }

    #ListViewContents table.small tbody TR.warning {
        border-top: 1px solid #fff;
        border-left: 1px solid #ddd;
        border-right: 0px solid #fff;
        border-bottom: 0px solid #fff;
        background: #FCF8E3 !important;
    }

{/literal}
</style>
<div id="dialog_listview" style="display:none;background-color: #FFF;z-index: 10000000 !important;">Dialog Content.</div>

<script language="JavaScript" type="text/javascript" src="include/js/ListView.js"></script>
<form name="massdelete" method="POST" id="massdelete" onsubmit="VtigerJS_DialogBox.block();">
    <input name='search_url' id="search_url" type='hidden' value='{$SEARCH_URL}'>
    <input name="idlist" id="idlist" type="hidden">
    <input name="change_owner" type="hidden">
    <input name="change_status" type="hidden">
    <input name="action" type="hidden">
    <input name="where_export" type="hidden" value="{php} echo to_html($_SESSION['export_where']);{/php}">
    <input name="step" type="hidden">
    <input name="allids" type="hidden" id="allids" value="{$ALLIDS}">
    <input name="selectedboxes" id="selectedboxes" type="hidden" value="{$SELECTEDIDS}">
    <input name="allselectedboxes" id="allselectedboxes" type="hidden" value="{$ALLSELECTEDIDS}">
    <input name="current_page_boxes" id="current_page_boxes" type="hidden" value="{$CURRENT_PAGE_BOXES}">
    <!-- List View Master Holder starts -->
    <table border=0 cellspacing=1 cellpadding=0 width=100% class="lvtBg">
        <tr>
            <td>
                <!-- List View's Buttons and Filters starts -->
                <table border=0 cellspacing=0 cellpadding=2 width=100% class="small">
                    <tr>
                        <!-- Buttons -->
                        <td style="padding-right:20px" nowrap>

                            {foreach key=button_check item=button_label from=$BUTTONS}
                                {if $button_check eq 'del'}
    
                                    <button class="crmbutton small delete" type="button" value="{$button_label}" onclick="return massDelete('{$MODULE}')">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;{$button_label}
                                    </button>
                                {elseif $button_check eq 'mass_edit'}
                                    {if $MODULE neq 'Voucher'}
                                    <button class="crmbutton small btnmassedit" type="button" value="{$button_label}" onclick="return mass_edit(this, 'massedit', '{$MODULE}', '{$CATEGORY}')">
                                        <img src="themes/softed/images/massedit_o.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;{$button_label}
                                    </button>
                                    {/if}
                                {elseif $button_check eq 's_mail'}
                                    

                                {elseif $button_check eq 's_cmail'}
                                    <button class="crmbutton small btnsendmail" type="submit" value="{$button_label}" onclick="return massMail('{$MODULE}')">
                                        <img src="themes/softed/images/sendemail_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;{$button_label}
                                    </button>
                                {elseif $button_check eq 'mailer_exp'}
                                    {* Mass Edit handles Change Owner for other module except Calendar *}

                                {elseif $button_check eq 'c_owner' && $MODULE eq 'Calendar'}
                                    
                                    <button class="crmbutton small edit" type="button" value="{$button_label}" onclick="return change(this,'changeowner')">
                                        {$button_label}
                                    </button>
                                {/if}
                            {/foreach}

                            {if  $MODULE eq 'Contacts' || $MODULE eq 'Accounts' || $MODULE eq 'Leads'}
                                
                                 <button class="crmbutton small btnsendmail" type="button" value="{$button_label}" onclick="return eMail('{$MODULE}',this);">
                                    <img src="themes/softed/images/sendemail_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;Send Mail
                                </button>
                                
                                <button class="crmbutton small btnsendmail" type="button" value="Send SMS" onclick="return mass_sms('{$MODULE}')">
                                    <img src="themes/softed/images/sms_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;Send SMS
                                </button>
                            {/if}
                            {if  $MODULE eq 'Competitor'}
                                <button class="crmbutton small save" type="button" value="Competitor Analysis" onclick="modal_competitor();">
                                    <img src="themes/softed/images/approval.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;Competitor Analysis
                                </button>
                            {/if}

                            {if $MODULE eq 'Products'}
                                <button class="crmbutton small save" type="button" value="" onclick="getERPProduct()">
                                    Get Product
                                </button>
                            {/if}
                        </td>
                        <td class="small" nowrap>
                            {$recordListRange}
                        </td>
                        <!-- Page Navigation -->
                        <td nowrap width="30%" align="center">
                            <table border=0 cellspacing=0 cellpadding=0 class="small">
                                <tr>{$NAVIGATION}</tr>
                            </table>
                        </td>
                        <td width=40% align="right">
                            <!-- Filters -->
                            {if $HIDE_CUSTOM_LINKS neq '1'}
                                <table border=0 cellspacing=0 cellpadding=0 class="small">
                                    <tr>
                                         <!--Padding 20 - 200 -->
                                        <td>{$APP.LBL_PAGESIZE}</td>
                                        <td style="padding-left:5px;padding-right:5px">
                                           <select id='pagesize' class="small" style="width:50px;border-radius:3px;"  onchange="per_padding()" >
                                            {if $smarty.request.pagesize eq '20' || $smarty.request.pagesize eq ''}
                                                <option value="20" selected="selected">20</option>
                                            {else}
                                                <option value="20">20</option>
                                            {/if}
                                            {if $smarty.request.pagesize eq '50' }
                                                <option value="50" selected="selected" >50</option>
                                            {else}
                                                <option value="50">50</option>
                                            {/if}
                                            {if $smarty.request.pagesize eq '100' }
                                                <option value="100" selected="selected">100</option>
                                            {else}
                                                <option value="100">100</option>
                                            {/if}
                                            {if $smarty.request.pagesize eq '200' }
                                                <option value="200" selected="selected" >200</option>
                                            {else}
                                                <option value="200">200</option>
                                            {/if}
                                           </select>
                                        </td>
                                        <!--Padding 20 - 200 -->

                                        <td>{$APP.LBL_VIEW}</td>
                                        <td style="padding-left:5px;padding-right:5px">
                                            <SELECT name="viewname" id="viewname" class="small" onchange="showDefaultCustomView(this,'{$MODULE}','{$CATEGORY}')" style="border-radius:3px; width: 100% !important">{$CUSTOMVIEW_OPTION}</SELECT>
                                        </td>

                                        {if $ALL eq 'All'}
                                            <!-- <td>
                                                <a href="index.php?module={$MODULE}&action=CustomView&parenttab={$CATEGORY}">{$APP.LNK_CV_CREATEVIEW}</a>
                                                <span class="small">|</span>
                                                <span class="small" disabled>{$APP.LNK_CV_EDIT}</span>
                                                <span class="small">|</span>
                                                <span class="small" disabled>{$APP.LNK_CV_DELETE}</span>
                                            </td> -->
                                            <td>
                                                <a href="index.php?module={$MODULE}&action=CustomView&parenttab={$CATEGORY}">{$APP.LNK_CV_CREATEVIEW}</a>
                                                <span class="small">|</span>

                                                {if $is_admin eq 'on'}
                                                <a href="index.php?module={$MODULE}&action=CustomView&record={$VIEWID}&parenttab={$CATEGORY}">{$APP.LNK_CV_EDIT}</a>
                                                {else}
                                                <span class="small" disabled>{$APP.LNK_CV_EDIT}</span>
                                                {/if}
                                                <span class="small">|</span>                                       
                                                <span class="small" disabled>{$APP.LNK_CV_DELETE}</span>

                                            </td>
                                        {else}
                                            <td>
                                                <a href="index.php?module={$MODULE}&action=CustomView&parenttab={$CATEGORY}">{$APP.LNK_CV_CREATEVIEW}</a>
                                                <span class="small">|</span>
                                                {if $CV_EDIT_PERMIT neq 'yes'}
                                                    <span class="small" disabled>{$APP.LNK_CV_EDIT}</span>
                                                {else}
                                                    <a href="index.php?module={$MODULE}&action=CustomView&record={$VIEWID}&parenttab={$CATEGORY}">{$APP.LNK_CV_EDIT}</a>
                                                {/if}
                                                <span class="small">|</span>
                                                {if $CV_DELETE_PERMIT neq 'yes'}
                                                    <span class="small" disabled>{$APP.LNK_CV_DELETE}</span>
                                                {else}
                                                    <a href="javascript:confirmdelete('index.php?module=CustomView&action=Delete&dmodule={$MODULE}&record={$VIEWID}&parenttab={$CATEGORY}')">{$APP.LNK_CV_DELETE}</a>
                                                {/if}
                                                {if $CUSTOMVIEW_PERMISSION.ChangedStatus neq '' && $CUSTOMVIEW_PERMISSION.Label neq ''}
                                                    <span class="small">|</span>
                                                    <a href="#" id="customstatus_id"
                                                       onClick="ChangeCustomViewStatus({$VIEWID},{$CUSTOMVIEW_PERMISSION.Status},{$CUSTOMVIEW_PERMISSION.ChangedStatus},'{$MODULE}','{$CATEGORY}')">{$CUSTOMVIEW_PERMISSION.Label}</a>
                                                {/if}
                                            </td>
                                        {/if}
                                    </tr>
                                </table>
                                <!-- Filters  END-->
                            {/if}

                        </td>
                    </tr>
                </table>
                <!-- List View's Buttons and Filters ends -->

                <div>
                    <table border=0 cellspacing=1 cellpadding=3 width=100% class="lvt small">
                        <!-- Table Headers -->
                        <tr>
                            <td class="lvtCol">
                                <input type="checkbox" name="selectall" onClick=toggleSelect_ListView(this.checked,"selected_id")>
                            </td>
                        {foreach name="listviewforeach" item=header from=$LISTHEADER}
                            <td class="lvtCol">{$header}</td>
                        {/foreach}
                        </tr>
                        <!-- Table Contents -->
                        {foreach item=entity key=entity_id from=$LISTENTITY}
                            {if $MODULE eq 'ServiceRequest'}
                                <tr class="{$a_status.$entity_id.class}" onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='{$a_status.$entity_id.class}'" id="row_{$entity_id}">
                            {elseif $MODULE eq 'KnowledgeBase' && $a_status.$entity_id.class != "" }
                                <tr class="{$a_status.$entity_id.class}" onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='{$a_status.$entity_id.class}'" id="row_{$entity_id}">
                            {else}
                                <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'" id="row_{$entity_id}">
                            {/if}

                        {if $MODULE eq 'KnowledgeBase' && $a_status.$entity_id.class != "" }
                            <td width="5%">
                                <input type="checkbox" NAME="selected_id" id="{$entity_id}" value='{$entity_id}'
                                   onClick="check_object(this)">
                                <img src="{$a_status.$entity_id.icon}" title="{$a_status.$entity_id.title}" width="20">
                        {elseif $MODULE eq 'Quotes'}
                            {if $a_status.$entity_id.quotation_status eq 'เปิดใบเสนอราคา'}
                                <td width="2%"><input type="checkbox" NAME="selected_id" id="{$entity_id}" value='{$entity_id}' onClick="check_object(this)">
                            {else}
                                <td width="2%"></td>
                            {/if}
                        
                        {elseif $MODULE eq 'Accounts'}
                                <td width="2%"><input type="checkbox" NAME="selected_id" id="{$entity_id}" value='{$entity_id}' onClick="check_object(this)">
                                   
                        {elseif $MODULE eq 'Leads' }
                            {if  $a_status.$entity_id.lead_convert eq '1'}
                                <td width="2%"></td>
                            {else}
                                <td width="2%">
                                <input type="checkbox" NAME="selected_id" id="{$entity_id}" value='{$entity_id}' onClick="check_object(this)">
                            {/if}

                        {elseif $MODULE eq 'Calendar' }
                            {if  $a_status.$entity_id.flag_send_report eq '1'}
                                <td width="2%"></td>
                            {else}
                                <td width="2%">
                                <input type="checkbox" NAME="selected_id" id="{$entity_id}" value='{$entity_id}' onClick="check_object(this)">
                            {/if}
                        {elseif $MODULE eq 'Projects' }
                            {if $a_display.$entity_id.display eq 'yes'}
                            <td width="2%"><input type="checkbox" NAME="selected_id" id="{$entity_id}" value='{$entity_id}' onClick="check_object(this)">
                            {else}
                            <td width="2%">
                            {/if}
                        {else}
                            <td width="2%"><input type="checkbox" NAME="selected_id" id="{$entity_id}" value='{$entity_id}' onClick="check_object(this)">
                                
                        {/if}
                         </td>
                            
                            {foreach item=data from=$entity}
                                {* vtlib customization: Trigger events on listview cell *}
                                <td onmouseover="vtlib_listview.trigger('cell.onmouseover', $(this))"
                                    onmouseout="vtlib_listview.trigger('cell.onmouseout', $(this))"
                                    height="18">{$data}</td>
                                {* END *}
                            {/foreach}

                            </tr>
                            {foreachelse}
                            <tr>
                                <td style="background-color:#efefef;height:340px" align="center"
                                    colspan="{$smarty.foreach.listviewforeach.iteration+1}">
                                    <div style="border: 3px solid rgb(201 201 201); background-color: rgb(255, 255, 255); width: 45%; position: relative; z-index: 2;border-radius: 6px;">
                                        {assign var=vowel_conf value='LBL_A'}
                                        
                                        {if $MODULE eq 'Accounts'}
                                            {assign var=vowel_conf value='LBL_AN'}
                                        {/if}

                                        {assign var=MODULE_CREATE value=$SINGLE_MOD}
                                        
                                        {if $MODULE eq 'HelpDesk'}
                                            {assign var=MODULE_CREATE value='Ticket'}
                                        {/if}

                                        {if $CHECK.EditView eq 'yes' && $MODULE neq 'Emails' && $MODULE neq 'Webmails' && $MODULE neq 'Opportunity' }
                                            <table border="0" cellpadding="5" cellspacing="0" width="98%">
                                                <tr>
                                                    <td rowspan="2" width="25%"><img src="{'empty.jpg'|@aicrm_imageurl:$THEME}" height="60" width="61"></td>
                                                    <td style="border-bottom: 1px solid rgb(204, 204, 204);" nowrap="nowrap" width="75%"><span class="genHeaderSmall">
                                    				{if $MODULE_CREATE eq 'Quotes'}
                                                        {$APP.LBL_NO} {$APP.$MODULE_CREATE} {$APP.LBL_FOUND} !
                                                    {elseif $MODULE eq 'Calendar'}
                                                        {$APP.LBL_NO} {$APP.ACTIVITIES} {$APP.LBL_FOUND} !
                                                    {else}
                                                        {* vtlib customization: Use translation string only if available *}
                                                        {$APP.LBL_NO} {if $APP.$MODULE_CREATE}{$APP.$MODULE_CREATE}{else}{$MODULE_CREATE}{/if} {$APP.LBL_FOUND} !
                                                    {/if}
                                    				</span>
                                                </td>
                                              </tr>
                                              <tr>

                                                    <td class="small" align="left"
                                                        nowrap="nowrap">{$APP.LBL_YOU_CAN_CREATE} {$APP.$vowel_conf}

                                                        {if $MODULE_CREATE eq 'Quotes'}
                                                            {$MOD.$MODULE_CREATE}
                                                        {else}
                                                            {* vtlib customization: Use translation string only if available *}
                                                            {if $APP.$MODULE_CREATE}{$APP.$MODULE_CREATE}{else}{$MODULE_CREATE}{/if}
                                                        {/if}

                                                        {$APP.LBL_NOW}. {$APP.LBL_CLICK_THE_LINK}:<br>
                                                        
                                                        {if $MODULE neq 'Calendar'}
                                                            &nbsp;&nbsp;-
                                                            {if $MODULE eq 'Projects'}
                                                                <a  href='MOAIMB-Webview/Projects/create_web?userid={$CURRENT_USER_ID}' class='ls-modal'>
                                                                    {$APP.LBL_CREATE} {$APP.$vowel_conf}
                                                                    {if $APP.$MODULE_CREATE}{$APP.$MODULE_CREATE}{else}{$MODULE_CREATE}{/if}
                                                                </a><br>
                                                            {else}
                                                            <a href="index.php?module={$MODULE}&action=EditView&return_action=DetailView&parenttab={$CATEGORY}">{$APP.LBL_CREATE} {$APP.$vowel_conf}
                                                                
                                                                {if $MODULE_CREATE eq 'Quotes'}
                                                                    {$MOD.$MODULE_CREATE}
                                                                {else}
                                                                    {* vtlib customization: Use translation string only if available *}
                                                                    {if $APP.$MODULE_CREATE}{$APP.$MODULE_CREATE}{else}{$MODULE_CREATE}{/if}
                                                                {/if}
                                                            </a><br>
                                                            {/if}
                                                        {else}
                                                            &nbsp;&nbsp;-
                                                            <a href="index.php?module={$MODULE}&amp;action=EditView&amp;return_module=Calendar&amp;activity_mode=Events&amp;return_action=DetailView&amp;parenttab={$CATEGORY}">{$APP.LBL_CREATE} {$APP.LBL_AN} {$APP.Event}</a>
                                                            <br>
                                                            &nbsp;&nbsp;-
                                                            <a href="index.php?module={$MODULE}&amp;action=EditView&amp;return_module=Calendar&amp;activity_mode=Task&amp;return_action=DetailView&amp;parenttab={$CATEGORY}">{$APP.LBL_CREATE} {$APP.LBL_A} {$APP.Task}</a>
                                                        {/if}
                                                    </td>
                                                </tr>
                                            </table>
                                        {else}
                                            <table border="0" cellpadding="5" cellspacing="0" width="98%">
                                                <tr>
                                                    <td rowspan="2" width="25%"><img
                                                                src="{'denied.gif'|@aicrm_imageurl:$THEME}"></td>
                                                    <td style="border-bottom: 1px solid rgb(204, 204, 204);"
                                                        nowrap="nowrap" width="75%"><span class="genHeaderSmall">
				                                    
                                                    {if $MODULE_CREATE eq 'Quotes'}
                                                        {$APP.LBL_NO} {$APP.$MODULE_CREATE} {$APP.LBL_FOUND}
                                                        !</span></td>
                                                    {else}

                                                    {* vtlib customization: Use translation string only if available *}
                                                        {$APP.LBL_NO} {if $APP.$MODULE_CREATE}{$APP.$MODULE_CREATE}{else}{$MODULE_CREATE}{/if} {$APP.LBL_FOUND}
                                                        !</span></td>
                                                    {/if}
                                                </tr>
                                                <tr>
                                                    <td class="small" align="left"
                                                        nowrap="nowrap">{$APP.LBL_YOU_ARE_NOT_ALLOWED_TO_CREATE} {$APP.$vowel_conf}
                                                        {if $MODULE_CREATE eq 'Quotes'}
                                                            {$MOD.$MODULE_CREATE}
                                                        {else}
                                                            {* vtlib customization: Use translation string only if available *}
                                                            {if $APP.$MODULE_CREATE}{$APP.$MODULE_CREATE}{else}{$MODULE_CREATE}{/if}
                                                        {/if}
                                                        <br>
                                                    </td>
                                                </tr>
                                            </table>
                                        {/if}
                                    </div>
                                </td>
                            </tr>
                        {/foreach}
                    </table>
                </div>

                <table border=0 cellspacing=0 cellpadding=2 width=100%>
                    <tr>
                        <td style="padding-right:20px" nowrap>
                            {foreach key=button_check item=button_label from=$BUTTONS}
                                {if $button_check eq 'del'}
                                   
                                    <button class="crmbutton small delete" type="button" value="{$button_label}" onclick="return massDelete('{$MODULE}')">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;{$button_label}
                                    </button>
                                {elseif $button_check eq 'mass_edit'}
                                    {if $MODULE neq 'Voucher'}
                                    <button class="crmbutton small btnmassedit" type="button" value="{$button_label}" onclick="return mass_edit(this, 'massedit', '{$MODULE}')">
                                        <img src="themes/softed/images/massedit_o.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;{$button_label}
                                    </button>
                                    {/if}
                                {elseif $button_check eq 's_mail'}
                                    <!-- <input class="crmbutton small edit" type="button" value="{$button_label}" onclick="return eMail('{$MODULE}',this)"/> -->
                                    <!-- <button class="crmbutton small btnsendmail" type="button" value="{$button_label}" onclick="return eMail('{$MODULE}',this)">
                                        <img src="themes/softed/images/sendemail_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;{$button_label}
                                    </button> -->
                                {elseif $button_check eq 's_cmail'}
                                    
                                    <button class="crmbutton small btnsendmail" type="submit" value="{$button_label}" onclick="return massMail('{$MODULE}')">
                                        <img src="themes/softed/images/sendemail_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;{$button_label}
                                    </button>
                                {elseif $button_check eq 'mailer_exp'}
                                    {* Mass Edit handles Change Owner for other module except Calendar *}
                               
                                {elseif $button_check eq 'c_owner' && $MODULE eq 'Calendar'}

                                    
                                    <button class="crmbutton small edit" type="button" value="{$button_label}" onclick="return change(this,'changeowner')">
                                        {$button_label}
                                    </button>
                                {/if}
                            {/foreach}
                            
                            {if  $MODULE eq 'Contacts' || $MODULE eq 'Accounts' || $MODULE eq 'Leads'}
                                <button class="crmbutton small btnsendmail" type="button" value="{$button_label}" onclick="return eMail('{$MODULE}',this);">
                                    <img src="themes/softed/images/sendemail_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;Send Mail
                                </button>
                                
                                <button class="crmbutton small btnsendmail" type="button" value="Send SMS" onclick="return mass_sms( '{$MODULE}')">
                                    <img src="themes/softed/images/sms_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;Send SMS
                                </button>
                            {/if}       

                        </td>
                        <td class="small" nowrap>
                            {$recordListRange}
                        </td>
                        <td nowrap width="30%" align="center">
                            <table border=0 cellspacing=0 cellpadding=0 class="small">
                                <tr>{$NAVIGATION}</tr>
                            </table>
                        </td>
                        <td align="right" width=40%>
                            <table border=0 cellspacing=0 cellpadding=0 class="small">
                                <tr>
                                    {$WORDTEMPLATEOPTIONS}{$MERGEBUTTON}
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</form>
{$SELECT_SCRIPT}

<div id="basicsearchcolumns" style="display:none;"><select name="search_field" id="bas_searchfield" class="txtBox" style="width:150px">{html_options  options=$SEARCHLISTHEADER}</select>
</div>
