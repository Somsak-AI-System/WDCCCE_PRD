<?php /* Smarty version 2.6.18, created on 2026-04-08 17:45:00
         compiled from Buttons_List.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'getTranslatedString', 'Buttons_List.tpl', 30, false),array('modifier', 'aicrm_imageurl', 'Buttons_List.tpl', 79, false),)), $this); ?>
<script type="text/javascript" src="modules/<?php echo $this->_tpl_vars['MODULE']; ?>
/<?php echo $this->_tpl_vars['MODULE']; ?>
.js"></script>
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
            <?php $this->assign('action', 'ListView'); ?>

            <?php if ($this->_tpl_vars['MODULE'] == 'Calendar'): ?>
                <?php $this->assign('MODULELABEL', 'Sales Visit'); ?>
            <?php else: ?>
                <?php $this->assign('MODULELABEL', getTranslatedString($this->_tpl_vars['MODULE'], $this->_tpl_vars['MODULE'])); ?>
            <?php endif; ?>
            <td style="padding-left:15px !important;padding-right:50px" class="moduleName" nowrap><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['CATEGORY']]; ?>
 > <a class="hdrLink" href="index.php?action=<?php echo $this->_tpl_vars['action']; ?>
&module=<?php echo $this->_tpl_vars['MODULE']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['MODULELABEL']; ?>
</a></td>
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
                                                <?php if ($this->_tpl_vars['CHECK']['EditView'] == 'yes' && $this->_tpl_vars['MODULE'] != 'Emails' && $this->_tpl_vars['MODULE'] != 'Webmails' && $this->_tpl_vars['MODULE'] != 'Inspection'): ?>
                                                    
                                                    <?php if ($this->_tpl_vars['MODULE'] == 'Calendar'): ?>
                                                        <td style="padding-right:10px;padding-left:5px;" class="submenu">
                                                            <a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=EditView&return_action=DetailView&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
">
                                                                <img src="themes/softed/images/create.png" border= "0" style="width: 20px; height: 20px; vertical-align: middle;">
                                                                <?php echo $this->_tpl_vars['APP']['LBL_CREATE_BUTTON_LABEL']; ?>

                                                        </td>
                                                    
                                                    <?php elseif ($this->_tpl_vars['MODULE'] == 'Opportunity' || $this->_tpl_vars['MODULE'] == 'Voucher'): ?> 
                                                        <td style="padding-right:0px;padding-left:5px;">
                                                            <img src="themes/softed/images/create_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                            <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
                                                                <?php echo $this->_tpl_vars['APP']['LBL_CREATE_BUTTON_LABEL']; ?>

                                                            </span>
                                                        </td>
                                                    <?php elseif ($this->_tpl_vars['MODULE'] == 'Projects'): ?>
                                                        <td class="submenu" style="padding-right: 10px; padding-left: 20px;">
                                                            <a  href='MOAIMB-Webview/Projects/create_web?userid=<?php echo $this->_tpl_vars['CURRENT_USER_ID']; ?>
' class='ls-modal'>
                                                                <img src="themes/softed/images/create.png" border=0 style="width: 20px; height: 20px; vertical-align: middle;">
                                                                <?php echo $this->_tpl_vars['APP']['LBL_CREATE_BUTTON_LABEL']; ?>

                                                            </a>
                                                        </td>
                                                    <?php else: ?>
                                                        <td class="submenu" style="padding-right: 10px; padding-left: 20px;">
                                                            <a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=EditView&return_action=DetailView&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
">
                                                                <img src="themes/softed/images/create.png" border=0 style="width: 20px; height: 20px; vertical-align: middle;">
                                                                <?php echo $this->_tpl_vars['APP']['LBL_CREATE_BUTTON_LABEL']; ?>

                                                            </a>
                                                        </td>
                                                    <?php endif; ?>

                                                <?php else: ?>
                                                    <td style="padding-right:5px;padding-left:5px;">
                                                        <!-- <img src="<?php echo aicrm_imageurl('btnL3Add-Faded.gif', $this->_tpl_vars['THEME']); ?>
" border=0> -->
                                                        <img src="themes/softed/images/create_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                        <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
                                                            <?php echo $this->_tpl_vars['APP']['LBL_CREATE_BUTTON_LABEL']; ?>

                                                        </span>
                                                    </td>
                                                <?php endif; ?>

                                                <?php if ($this->_tpl_vars['CHECK']['index'] == 'yes' && $this->_tpl_vars['MODULE'] != 'Emails' && $this->_tpl_vars['MODULE'] != 'Webmails'): ?>
                                                    <td class="submenu" style="padding-right: 5px">
                                                        <a href="javascript:;" onClick="moveMe('searchAcc');searchshowhide('searchAcc','advSearch');mergehide('mergeDup')">
                                                            <img src="themes/softed/images/search(1).png" border=0 style="width: 25px;height: 20px; vertical-align: middle;">
                                                            <?php echo $this->_tpl_vars['APP']['LBL_SEARCH_TITLE']; ?>

                                                        </a>
                                                    </td>
                                                <?php else: ?>
                                                    <td style="padding-right:5px">
                                                        <!-- <img src="<?php echo aicrm_imageurl('btnL3Search-Faded.gif', $this->_tpl_vars['THEME']); ?>
" border=0> -->
                                                        <img src="themes/softed/images/search_g.png" border="0" style="width:25px;height:20px;vertical-align: middle;">
                                                        <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9;">
                                                            <?php echo $this->_tpl_vars['APP']['LBL_SEARCH_TITLE']; ?>

                                                        </span>
                                                    </td>
                                                <?php endif; ?>

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
                                    <?php if ($this->_tpl_vars['CALENDAR_DISPLAY'] == 'true'): ?>
                                        <?php if ($this->_tpl_vars['CATEGORY'] == 'Settings' || $this->_tpl_vars['CATEGORY'] == 'Tools' || $this->_tpl_vars['CATEGORY'] == 'Analytics'): ?>
                                            <?php if ($this->_tpl_vars['CHECK']['Calendar'] == 'yes'): ?>
                                                
                                                <td class="submenu" style="padding-right:0px;padding-left:5px;">
                                                    <a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal("parenttab=My Home Page");'>
                                                        <img src="themes/softed/images/calendar.png" border=0 style="width: 20px;height: 20px; vertical-align: middle;">
                                                        
                                                        <?php echo $this->_tpl_vars['APP']['LBL_CALENDAR_TITLE']; ?>

                                                    </a>
                                                </td>
                                            <?php else: ?>
                                                <td style="padding-right:0px;padding-left:5px;">
                                                    <!-- <img src="<?php echo aicrm_imageurl('btnL3Calendar-Faded.gif', $this->_tpl_vars['THEME']); ?>
"> -->
                                                    <img src="themes/softed/images/calendar_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                    <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
                                                        <?php echo $this->_tpl_vars['APP']['LBL_CALENDAR_TITLE']; ?>

                                                    </span>
                                                </td>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if ($this->_tpl_vars['CHECK']['Calendar'] == 'yes'): ?>
                                                <!--ปุ่ม CALENDAR-->
                                                <td class="submenu" style="padding-right:0px;padding-left:5px;">
                                                    
                                                    <a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal("parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
");'>
                                                       <img src="themes/softed/images/calendar.png" border=0 style="width: 20px;height: 20px; vertical-align: middle;">
                                                        <?php echo $this->_tpl_vars['APP']['LBL_CALENDAR_TITLE']; ?>

                                                    </a>
                                                </td>
                                            <?php else: ?>
                                                <td style="padding-right:0px;padding-left:5px;">
                                                    <!-- <img src="<?php echo aicrm_imageurl('btnL3Calendar-Faded.gif', $this->_tpl_vars['THEME']); ?>
"> -->
                                                    <img src="themes/softed/images/calendar_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                    <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
                                                        <?php echo $this->_tpl_vars['APP']['LBL_CALENDAR_TITLE']; ?>

                                                    </span>
                                                </td>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if ($this->_tpl_vars['WORLD_CLOCK_DISPLAY'] == 'true'): ?>
                                        <!--ปุ่ม CLOCK-->
                                        <!-- <td style="padding-right:0px"><a href="javascript:;"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Clock.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLOCK_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CLOCK_TITLE']; ?>
" border=0 onClick="fnvshobj(this,'wclock');"></a></a></td> -->
                                    <?php endif; ?>
                                    <?php if ($this->_tpl_vars['CALCULATOR_DISPLAY'] == 'true'): ?>
                                        <!--ปุ่ม CALCULATOR-->
                                        <!--<td style="padding-right:0px"><a href="#"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Calc.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_CALCULATOR_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CALCULATOR_TITLE']; ?>
" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();"></a></td>-->
                                    <?php endif; ?>
                                    <?php if ($this->_tpl_vars['CHAT_DISPLAY'] == 'true'): ?>
                                        <!--ปุ่ม CHAT-->
                                        <!-- <td style="padding-right:5px"><a href="javascript:;" onClick='return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");'><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
tbarChat.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_CHAT_ALT']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_CHAT_TITLE']; ?>
" border=0></a> -->
                                    <?php endif; ?>
                                    </td>
                                    <!--ปุ่ม LAST_VIEWED-->
                                    <!--<td style="padding-right:5px"><img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
btnL3Tracker.gif" alt="<?php echo $this->_tpl_vars['APP']['LBL_LAST_VIEWED']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_LAST_VIEWED']; ?>
" border=0 onClick="fnvshobj(this,'tracker');">
                                </td>-->
                                </tr>
                            </table>
                        </td>
                        <td style="width:10px;">&nbsp;</td>
                        <td class="small">
                            <!-- Import / Export -->
                            <table border=0 cellspacing=0 cellpadding=5>
                                <tr>

                                    
                                    
                                        <?php if ($this->_tpl_vars['CHECK']['Import'] == 'yes' && ( $this->_tpl_vars['MODULE'] != 'Documents' && $this->_tpl_vars['MODULE'] != 'Activitys' && $this->_tpl_vars['MODULE'] != 'Quotes' && $this->_tpl_vars['MODULE'] != 'Errorslist' && $this->_tpl_vars['MODULE'] != 'Job' && $this->_tpl_vars['MODULE'] != 'Sparepartlist' && $this->_tpl_vars['MODULE'] != 'Questionnairetemplate' && $this->_tpl_vars['MODULE'] != 'Questionnaire' && $this->_tpl_vars['MODULE'] != 'Calendar' && $this->_tpl_vars['MODULE'] != 'Projects' && $this->_tpl_vars['MODULE'] != 'Voucher' && $this->_tpl_vars['MODULE'] != 'Servicerequest' )): ?>
                                            <?php if ($this->_tpl_vars['MODULE'] == 'PriceList'): ?>
                                            <td class="submenu sub_listview">
                                                <a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=QuickImport&step=1&return_module=<?php echo $this->_tpl_vars['MODULE']; ?>
&return_action=index&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
" style="display: inline-flex;">
                                                    <img src="themes/softed/images/import.png" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
                                                    <?php echo $this->_tpl_vars['APP']['LBL_IMPORT']; ?>

                                                </a>
                                            </td>
                                            <?php else: ?>
                                            <td class="submenu" style="padding-right:0px;padding-left:5px;">
                                                <a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=Import&step=1&return_module=<?php echo $this->_tpl_vars['MODULE']; ?>
&return_action=index&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
">
                                                    <img src="themes/softed/images/import.png" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
                                                    <?php echo $this->_tpl_vars['APP']['LBL_IMPORT']; ?>

                                                </a>
                                            </td>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if ($this->_tpl_vars['MODULE'] == 'Activitys' || $this->_tpl_vars['MODULE'] == 'Quotes' || $this->_tpl_vars['MODULE'] == 'Errorslist' || $this->_tpl_vars['MODULE'] == 'Job' || $this->_tpl_vars['MODULE'] == 'Sparepartlist'): ?>
                                                <td style="padding-right:0px;padding-left:5px;">
                                                    <!-- <img src="<?php echo $this->_tpl_vars['IMAGE_PATH']; ?>
tbarImport-Faded.gif" border="0"> -->
                                                    <img src="themes/softed/images/import_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                    <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;"><?php echo $this->_tpl_vars['APP']['LBL_IMPORT']; ?>
</span>
                                                </td>
                                            <?php else: ?>
                                                <td style="padding-right:0px;padding-left:5px;">
                                                    <!-- <img src="<?php echo aicrm_imageurl('tbarImport-Faded.gif', $this->_tpl_vars['THEME']); ?>
" border="0"> -->
                                                    <img src="themes/softed/images/import_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                    <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;"><?php echo $this->_tpl_vars['APP']['LBL_IMPORT']; ?>
</span>
                                                </td>
                                            <?php endif; ?>

                                        <?php endif; ?>
                                        
                                        <?php if ($this->_tpl_vars['CHECK']['Export'] == 'yes' && ( $this->_tpl_vars['MODULE'] != 'Questionnaire' && $this->_tpl_vars['MODULE'] != 'Projects' )): ?>

                                            <?php if ($this->_tpl_vars['MODULE'] == 'Quotes'): ?>
                                                <td class="submenu" style="padding-left: 10px; padding-right:5px">
                                                    <img src="themes/softed/images/export_g.png" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
                                                    <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;"><?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>
</span>
                                                </td>
                                            <?php else: ?>
                                                <td class="submenu" style="padding-left: 10px; padding-right:5px">
                                                    <a name='export_link' href="javascript:void(0)" onclick="return selectedRecords('<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['CATEGORY']; ?>
')">
                                                        <img src="themes/softed/images/export.png" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
                                                        <?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>

                                                    </a>
                                                </td>
                                            <?php endif; ?>
                                            
                                            <?php if ($this->_tpl_vars['MODULE'] == 'Goodsreceive' || $this->_tpl_vars['MODULE'] == 'Purchasesorder'): ?>
                                                <td class="submenu" style="padding-left: 10px; padding-right:5px">
                                                    <a name='export_link' href="javascript:void(0)" onclick="return selectedRecordsLine('<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['CATEGORY']; ?>
')">
                                                        <img src="themes/softed/images/export.png" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
                                                        Export Line
                                                    </a>
                                                </td>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <td style="padding-right:5px">
                                                <!-- <img src="<?php echo aicrm_imageurl('tbarExport-Faded.gif', $this->_tpl_vars['THEME']); ?>
" border="0"> -->
                                                <img src="themes/softed/images/export_g.png" border="0" style="width:20px;height:20px;vertical-align: middle;">
                                                <span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">
                                                    <?php echo $this->_tpl_vars['APP']['LBL_EXPORT']; ?>

                                                </span>
                                            </td>
                                        <?php endif; ?>

                                                                            

                                    
                                    <?php if ($this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] == 'Products'): ?>
                                    
                                        <?php if ($this->_tpl_vars['CHECK']['DuplicatesHandling'] == 'yes'): ?>
                                            <td class="submenu" style="padding-right:5px">
                                                
                                                <a href="javascript:;" onClick="moveMe('mergeDup');mergeshowhide('mergeDup');searchhide('searchAcc','advSearch');">
                                                    <img src="themes/softed/images/merge_b.png" border="0" style="width: 20px; height: 20px; vertical-align: middle;">
                                                    
                                                    <?php echo $this->_tpl_vars['APP']['LBL_FIND_DUPLICATES']; ?>

                                                </a>
                                            </td>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <!--FindDuplicates-->
                                                                            <?php endif; ?>
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
                                            <?php echo $this->_tpl_vars['APP']['LBL_ALL_MENU_TITLE']; ?>

                                        </a>
                                    </td>

                                    <?php if ($this->_tpl_vars['CHECK']['moduleSettings'] == 'yes'): ?>
                                        <td class="submenu" style="padding-left:5px;">
                                            <a href='index.php?module=Settings&action=ModuleManager&module_settings=true&formodule=<?php echo $this->_tpl_vars['MODULE']; ?>
&parenttab=Settings'><img src="themes/softed/images/tools_b.png" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
                                                
                                                <?php echo $this->_tpl_vars['APP']['LBL_SETTINGS']; ?>

                                            </a>
                                        </td>
                                    <?php endif; ?>


                                    <?php if ($this->_tpl_vars['MODULE'] == 'Products'): ?>
                                        <td class="submenu" style="padding-left:5px;">
                                            <button class="crmbutton small btnworkingday" type="button" class="ls-modal-link" onclick="modal_upload_images('upload-images-product-comparision/upload-files.php',event)">
                                                &nbspUpload Images
                                            </button>
                                        </td>
                                    <?php endif; ?>
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
                      <input type="hidden" name="parenttab" value="<?php echo $this->_tpl_vars['CATEGORY']; ?>
" style="margin:0px">
                      <input type="hidden" name="search_onlyin" value="--USESELECTED--" style="margin:0px">
                      <input type="text" class="tftextinput" name="query_string" value="<?php echo $this->_tpl_vars['QUERY_STRING']; ?>
" onFocus="this.value=''"  style="width:150px; margin-bottom: 5px;" placeholder="Searching">
                      <button type="submit"  class="tfbutton"  value="<?php echo $this->_tpl_vars['APP']['LBL_FIND_BUTTON']; ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_FIND']; ?>
" title="<?php echo $this->_tpl_vars['APP']['LBL_FIND']; ?>
">
                        <i><img src="themes/softed/images/search.png" border=0 style="width: 10px"></i> &nbsp;<?php echo $this->_tpl_vars['APP']['LBL_FIND_BUTTON']; ?>

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
