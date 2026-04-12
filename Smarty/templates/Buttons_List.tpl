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
<script type="text/javascript" src="modules/{$MODULE}/{$MODULE}.js"></script>
<!-- <div class="card">
    <div class="card-body">
        test
    </div>
</div> -->

<div class="card-btn">
    <TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
        <tr>
            <td style="height:2px"></td>
        </tr>
        <tr>
            {assign var="action" value="ListView"}

            {if $MODULE eq 'Calendar'}
                {assign var="MODULELABEL" value='Sales Visit'}
            {else}
                {assign var="MODULELABEL" value=$MODULE|@getTranslatedString:$MODULE}
            {/if}
            <td style="padding-left:15px !important;padding-right:50px" class="moduleName" nowrap>{$APP.$CATEGORY} > <a class="hdrLink" href="index.php?action={$action}&module={$MODULE}&parenttab={$CATEGORY}">{$MODULELABEL}</a></td>
            <td width=50% nowrap>

                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="sep1" style="width:1px;"></td>
                        <td class=small>
                            <!-- Add and Search -->
                            <table border=0 cellspacing=0 cellpadding=0>
                                <tr>
                                    <td>
                                        <table border=0 cellspacing=0 cellpadding=2>
                                            <tr>
                                                {if $CHECK.EditView eq 'yes' && $MODULE neq 'Emails' && $MODULE neq 'Webmails' && $MODULE neq 'Inspection'}
                                                    
                                                    {if $MODULE eq 'Calendar'}
                                                        <td style="padding-right:10px;padding-left:5px;" class="submenu">
                                                            <a href="index.php?module={$MODULE}&action=EditView&return_action=DetailView&parenttab={$CATEGORY}">
                                                                <img src="themes/softed/images/create.png" border= "0" style="width: 20px; height: 20px; vertical-align: middle;">
                                                                {$APP.LBL_CREATE_BUTTON_LABEL}
                                                        </td>
                                                    
                                                    {elseif $MODULE eq 'Opportunity' || $MODULE eq 'Voucher'} 
                                                        <td style="padding-right:0px;padding-left:5px;">
                                                            <img src="themes/softed/images/create_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                            <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
                                                                {$APP.LBL_CREATE_BUTTON_LABEL}
                                                            </span>
                                                        </td>
                                                    {elseif $MODULE eq 'Projects'}
                                                        <td class="submenu" style="padding-right: 10px; padding-left: 20px;">
                                                            <a  href='MOAIMB-Webview/Projects/create_web?userid={$CURRENT_USER_ID}' class='ls-modal'>
                                                                <img src="themes/softed/images/create.png" border=0 style="width: 20px; height: 20px; vertical-align: middle;">
                                                                {$APP.LBL_CREATE_BUTTON_LABEL}
                                                            </a>
                                                        </td>
                                                    {else}
                                                        <td class="submenu" style="padding-right: 10px; padding-left: 20px;">
                                                            <a href="index.php?module={$MODULE}&action=EditView&return_action=DetailView&parenttab={$CATEGORY}">
                                                                <img src="themes/softed/images/create.png" border=0 style="width: 20px; height: 20px; vertical-align: middle;">
                                                                {$APP.LBL_CREATE_BUTTON_LABEL}
                                                            </a>
                                                        </td>
                                                    {/if}

                                                {else}
                                                    <td style="padding-right:5px;padding-left:5px;">
                                                        <!-- <img src="{'btnL3Add-Faded.gif'|@aicrm_imageurl:$THEME}" border=0> -->
                                                        <img src="themes/softed/images/create_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                        <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
                                                            {$APP.LBL_CREATE_BUTTON_LABEL}
                                                        </span>
                                                    </td>
                                                {/if}

                                                {if $CHECK.index eq 'yes' && $MODULE neq 'Emails' && $MODULE neq 'Webmails'}
                                                    <td class="submenu" style="padding-right: 5px">
                                                        <a href="javascript:;" onClick="moveMe('searchAcc');searchshowhide('searchAcc','advSearch');mergehide('mergeDup')">
                                                            <img src="themes/softed/images/search(1).png" border=0 style="width: 25px;height: 20px; vertical-align: middle;">
                                                            {$APP.LBL_SEARCH_TITLE}
                                                        </a>
                                                    </td>
                                                {else}
                                                    <td style="padding-right:5px">
                                                        <!-- <img src="{'btnL3Search-Faded.gif'|@aicrm_imageurl:$THEME}" border=0> -->
                                                        <img src="themes/softed/images/search_g.png" border="0" style="width:25px;height:20px;vertical-align: middle;">
                                                        <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9;">
                                                            {$APP.LBL_SEARCH_TITLE}
                                                        </span>
                                                    </td>
                                                {/if}

                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width:10px;">&nbsp;</td>
                        <td class="small">
                            <!-- Calendar Clock Calculator and Chat -->
                            <table border=0 cellspacing=0 cellpadding=5>
                                <tr>
                                    {if $CALENDAR_DISPLAY eq 'true'}
                                        {if $CATEGORY eq 'Settings' || $CATEGORY eq 'Tools' || $CATEGORY eq 'Analytics'}
                                            {if $CHECK.Calendar eq 'yes'}
                                                
                                                <td class="submenu" style="padding-right:0px;padding-left:5px;">
                                                    <a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal("parenttab=My Home Page");'>
                                                        <img src="themes/softed/images/calendar.png" border=0 style="width: 20px;height: 20px; vertical-align: middle;">
                                                        
                                                        {$APP.LBL_CALENDAR_TITLE}
                                                    </a>
                                                </td>
                                            {else}
                                                <td style="padding-right:0px;padding-left:5px;">
                                                    <!-- <img src="{'btnL3Calendar-Faded.gif'|@aicrm_imageurl:$THEME}"> -->
                                                    <img src="themes/softed/images/calendar_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                    <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
                                                        {$APP.LBL_CALENDAR_TITLE}
                                                    </span>
                                                </td>
                                            {/if}
                                        {else}
                                            {if $CHECK.Calendar eq 'yes'}
                                                <!--ปุ่ม CALENDAR-->
                                                <td class="submenu" style="padding-right:0px;padding-left:5px;">
                                                    
                                                    <a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal("parenttab={$CATEGORY}");'>
                                                       <img src="themes/softed/images/calendar.png" border=0 style="width: 20px;height: 20px; vertical-align: middle;">
                                                        {$APP.LBL_CALENDAR_TITLE}
                                                    </a>
                                                </td>
                                            {else}
                                                <td style="padding-right:0px;padding-left:5px;">
                                                    <!-- <img src="{'btnL3Calendar-Faded.gif'|@aicrm_imageurl:$THEME}"> -->
                                                    <img src="themes/softed/images/calendar_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                    <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
                                                        {$APP.LBL_CALENDAR_TITLE}
                                                    </span>
                                                </td>
                                            {/if}
                                        {/if}
                                    {/if}
                                    {if $WORLD_CLOCK_DISPLAY eq 'true'}
                                        <!--ปุ่ม CLOCK-->
                                        <!-- <td style="padding-right:0px"><a href="javascript:;"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="{$APP.LBL_CLOCK_ALT}" title="{$APP.LBL_CLOCK_TITLE}" border=0 onClick="fnvshobj(this,'wclock');"></a></a></td> -->
                                    {/if}
                                    {if $CALCULATOR_DISPLAY eq 'true'}
                                        <!--ปุ่ม CALCULATOR-->
                                        <!--<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="{$APP.LBL_CALCULATOR_ALT}" title="{$APP.LBL_CALCULATOR_TITLE}" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();"></a></td>-->
                                    {/if}
                                    {if $CHAT_DISPLAY eq 'true'}
                                        <!--ปุ่ม CHAT-->
                                        <!-- <td style="padding-right:5px"><a href="javascript:;" onClick='return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");'><img src="{$IMAGE_PATH}tbarChat.gif" alt="{$APP.LBL_CHAT_ALT}" title="{$APP.LBL_CHAT_TITLE}" border=0></a> -->
                                    {/if}
                                    </td>
                                    <!--ปุ่ม LAST_VIEWED-->
                                    <!--<td style="padding-right:5px"><img src="{$IMAGE_PATH}btnL3Tracker.gif" alt="{$APP.LBL_LAST_VIEWED}" title="{$APP.LBL_LAST_VIEWED}" border=0 onClick="fnvshobj(this,'tracker');">
                                </td>-->
                                </tr>
                            </table>
                        </td>
                        <td style="width:10px;">&nbsp;</td>
                        <td class="small">
                            <!-- Import / Export -->
                            <table border=0 cellspacing=0 cellpadding=5>
                                <tr>

                                    {* vtlib customization: Hook to enable import/export button for custom modules. Added CUSTOM_MODULE *}

                                    {*if $MODULE eq 'HelpDesk' || $MODULE eq 'Contacts' || $MODULE eq 'Accounts' || $MODULE eq 'Potentials'  || $MODULE eq 'Documents' || $MODULE eq 'Leads' || $MODULE eq 'PriceList' || $CUSTOM_MODULE eq 'true' || $MODULE eq 'Quotes' || $MODULE eq 'Campaigns' || $MODULE eq 'Products' || $MODULE eq 'Competitor' || $MODULE eq 'Errors' || $MODULE eq 'Errorslist' || $MODULE eq 'Job' || $MODULE eq 'Serial' || $MODULE eq 'Sparepart' || $MODULE eq 'Sparepartlist' || $MODULE eq 'Plant'*}

                                        {if $CHECK.Import eq 'yes' && ($MODULE neq 'Documents' && $MODULE neq 'Activitys' && $MODULE neq 'Quotes' && $MODULE neq 'Errorslist' && $MODULE neq 'Job' && $MODULE neq 'Sparepartlist' && $MODULE neq 'Questionnairetemplate' && $MODULE neq 'Questionnaire' && $MODULE neq 'Calendar' && $MODULE neq 'Projects' && $MODULE neq 'Voucher' && $MODULE neq 'Servicerequest')}
                                            {if $MODULE eq 'PriceList'}
                                            <td class="submenu sub_listview">
                                                <a href="index.php?module={$MODULE}&action=QuickImport&step=1&return_module={$MODULE}&return_action=index&parenttab={$CATEGORY}" style="display: inline-flex;">
                                                    <img src="themes/softed/images/import.png" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
                                                    {$APP.LBL_IMPORT}
                                                </a>
                                            </td>
                                            {else}
                                            <td class="submenu" style="padding-right:0px;padding-left:5px;">
                                                <a href="index.php?module={$MODULE}&action=Import&step=1&return_module={$MODULE}&return_action=index&parenttab={$CATEGORY}">
                                                    <img src="themes/softed/images/import.png" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
                                                    {$APP.LBL_IMPORT}
                                                </a>
                                            </td>
                                            {/if}
                                        {else}
                                            {if  $MODULE eq 'Activitys' || $MODULE eq 'Quotes' || $MODULE eq 'Errorslist' || $MODULE eq 'Job' || $MODULE eq 'Sparepartlist'}
                                                <td style="padding-right:0px;padding-left:5px;">
                                                    <!-- <img src="{$IMAGE_PATH}tbarImport-Faded.gif" border="0"> -->
                                                    <img src="themes/softed/images/import_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                    <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">{$APP.LBL_IMPORT}</span>
                                                </td>
                                            {else}
                                                <td style="padding-right:0px;padding-left:5px;">
                                                    <!-- <img src="{'tbarImport-Faded.gif'|@aicrm_imageurl:$THEME}" border="0"> -->
                                                    <img src="themes/softed/images/import_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                    <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">{$APP.LBL_IMPORT}</span>
                                                </td>
                                            {/if}

                                        {/if}
                                        
                                        {if $CHECK.Export eq 'yes' && ($MODULE neq 'Questionnaire' && $MODULE neq 'Projects')}

                                            {if $MODULE eq 'Quotes'}
                                                <td class="submenu" style="padding-left: 10px; padding-right:5px">
                                                    <img src="themes/softed/images/export_g.png" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
                                                    <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">{$APP.LBL_EXPORT}</span>
                                                </td>
                                            {else}
                                                <td class="submenu" style="padding-left: 10px; padding-right:5px">
                                                    <a name='export_link' href="javascript:void(0)" onclick="return selectedRecords('{$MODULE}','{$CATEGORY}')">
                                                        <img src="themes/softed/images/export.png" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
                                                        {$APP.LBL_EXPORT}
                                                    </a>
                                                </td>
                                            {/if}
                                            
                                            {if $MODULE eq 'Goodsreceive' || $MODULE eq 'Purchasesorder'}
                                                <td class="submenu" style="padding-left: 10px; padding-right:5px">
                                                    <a name='export_link' href="javascript:void(0)" onclick="return selectedRecordsLine('{$MODULE}','{$CATEGORY}')">
                                                        <img src="themes/softed/images/export.png" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
                                                        Export Line
                                                    </a>
                                                </td>
                                            {/if}
                                        {else}
                                            <td style="padding-right:5px">
                                                <!-- <img src="{'tbarExport-Faded.gif'|@aicrm_imageurl:$THEME}" border="0"> -->
                                                <img src="themes/softed/images/export_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
                                                    {$APP.LBL_EXPORT}
                                                </span>
                                            </td>
                                        {/if}

                                    {*else*}
                                        

                                    {*/if*}

                                    {if $MODULE eq 'Accounts' || $MODULE eq 'Contacts' || $MODULE eq 'Products'}
                                    
                                        {if $CHECK.DuplicatesHandling eq 'yes'}
                                            <td class="submenu" style="padding-right:5px">
                                                
                                                <a href="javascript:;" onClick="moveMe('mergeDup');mergeshowhide('mergeDup');searchhide('searchAcc','advSearch');">
                                                    <img src="themes/softed/images/merge_b.png" border="0" style="width: 20px; height: 20px; vertical-align: middle;">
                                                    
                                                    {$APP.LBL_FIND_DUPLICATES}
                                                </a>
                                            </td>
                                        {/if}
                                    {else}
                                        <!--FindDuplicates-->
                                        {*<td style="padding-right:5px">
                                            <!-- <img src="{'FindDuplicates-Faded.gif'|@aicrm_imageurl:$THEME}" border="0"> -->
                                            <img src="themes/softed/images/merge_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                            <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9;">
                                                {$APP.LBL_FIND_DUPLICATES}
                                            </span>
                                        </td>*}
                                    {/if}
                                </tr>
                            </table>
                        <td style="width:10px;">&nbsp;</td>
                        <td class="small">
                            <!-- All Menu -->
                            <table border=0 cellspacing=0 cellpadding=5>
                                <tr>
                                    <td class="submenu" style="padding-left:5px;">
                                        
                                        <a href="javascript:;" onmouseout="fninvsh('allMenu');" onClick="fnvshobj(this,'allMenu')">
                                            <img src="themes/softed/images/menu.png" border="0" style="width: 20px; height: 20px; vertical-align: middle;">
                                            {$APP.LBL_ALL_MENU_TITLE}
                                        </a>
                                    </td>

                                    {if $CHECK.moduleSettings eq 'yes'}
                                        <td class="submenu" style="padding-left:5px;">
                                            <a href='index.php?module=Settings&action=ModuleManager&module_settings=true&formodule={$MODULE}&parenttab=Settings'><img src="themes/softed/images/tools_b.png" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
                                                
                                                {$APP.LBL_SETTINGS}
                                            </a>
                                        </td>
                                    {/if}


                                    {if $MODULE eq 'Products'}
                                        <td class="submenu" style="padding-left:5px;">
                                            <button class="crmbutton small btnworkingday" type="button" class="ls-modal-link" onclick="modal_upload_images('upload-images-product-comparision/upload-files.php',event)">
                                                &nbspUpload Images
                                            </button>
                                        </td>
                                    {/if}
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <div class="skin-greensena main-search">

                    <form name="UnifiedSearch" method="post" action="index.php" style="margin:0px" onSubmit="VtigerJS_DialogBox.block();">
                        <a href='javascript:void(0);' onClick="UnifiedSearch_SelectModuleForm(this);">
                          <img src="themes/softed/images/searchmodule.png" align='left' border=0 style="margin-top:3px; width: 25px; padding-right: 5px;">
                      </a>&nbsp;
                      <input type="hidden" name="action" value="UnifiedSearch" style="margin:0px">
                      <input type="hidden" name="module" value="Home" style="margin:0px">
                      <input type="hidden" name="parenttab" value="{$CATEGORY}" style="margin:0px">
                      <input type="hidden" name="search_onlyin" value="--USESELECTED--" style="margin:0px">
                      <input type="text" class="tftextinput" name="query_string" value="{$QUERY_STRING}" onFocus="this.value=''"  style="width:150px; margin-bottom: 5px;" placeholder="Searching">
                      <button type="submit"  class="tfbutton"  value="{$APP.LBL_FIND_BUTTON}" alt="{$APP.LBL_FIND}" title="{$APP.LBL_FIND}">
                        <i><img src="themes/softed/images/search.png" border=0 style="width: 10px"></i> &nbsp;{$APP.LBL_FIND_BUTTON}
                    </button>
                  </form>

              </div>
            </td>
        </tr>
        <tr>
            <td style="height:2px"></td>
        </tr>
    </TABLE>
</div>

