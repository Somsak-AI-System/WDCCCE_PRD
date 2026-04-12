<?php /* Smarty version 2.6.18, created on 2026-04-08 16:51:04
         compiled from ListViewEntries.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'aicrm_imageurl', 'ListViewEntries.tpl', 403, false),array('function', 'html_options', 'ListViewEntries.tpl', 569, false),)), $this); ?>
<?php if ($_REQUEST['ajax'] != ''): ?>
    &#&#&#<?php echo $this->_tpl_vars['ERROR']; ?>
&#&#&#
<?php endif; ?>

<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
<link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css">

<script>
    <?php echo '
    function check_btn1(module, crmid) {
        window.open(\'import_pre.php\', \'Quotes\', \'resizable=1,left=100,top=150,width=1000,height=400,toolbar=no,scrollbars=no,menubar=no,location=no\')
    }
    
    function check_cam(module, crmid) {
        window.open(\'import_cam.php\', \'Quotes\', \'resizable=1,left=100,top=150,width=1000,height=400,toolbar=no,scrollbars=no,menubar=no,location=no\')
    }

    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\\[\\]]/g, "\\\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return \'\';
        return decodeURIComponent(results[2].replace(/\\+/g, " "));
    }

    function per_padding (){
        var qty = document.getElementById("pagesize");
        var val = qty.options[qty.selectedIndex].value;
        
        var url = document.URL;
        var pagesize = getParameterByName(\'pagesize\');

        if(pagesize != null && pagesize !=\'\'){
            url = url.replace("pagesize="+pagesize, ""); 
            var reload_url = url+"pagesize="+val ;
            window.location.href = reload_url; 
        }else{
            var reload_url = url+"&pagesize="+val ;
            window.location.href = reload_url; 
        }
    }
    function modal_competitor(){
        var msg = \'\';
        url = \'plugin/Competitor/competitoranalysis.php\';
        jQuery(\'#dialog_listview\').window({
            title: \'Competitor Analysis\',
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
            title: \'Please waiting\',
            msg: \'Loading ...\',
            text: \'LOADING\'
        });

        jQuery.post(\'index.php\', {
            module: \'Products\',
            action: \'ProductsAjax\',
            ajax: true,
            file: \'GetERPProduct\',
        }, function(rs){
            jQuery.messager.progress(\'close\')
            jQuery.messager.alert(\'Get Product result\', \'Update product completed\', function(r){
                if (r){
                    
                }
            });
            console.log(rs)
        }, \'json\')
    }
'; ?>

</script>

<style>
<?php echo '
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

'; ?>

</style>
<div id="dialog_listview" style="display:none;background-color: #FFF;z-index: 10000000 !important;">Dialog Content.</div>

<script language="JavaScript" type="text/javascript" src="include/js/ListView.js"></script>
<form name="massdelete" method="POST" id="massdelete" onsubmit="VtigerJS_DialogBox.block();">
    <input name='search_url' id="search_url" type='hidden' value='<?php echo $this->_tpl_vars['SEARCH_URL']; ?>
'>
    <input name="idlist" id="idlist" type="hidden">
    <input name="change_owner" type="hidden">
    <input name="change_status" type="hidden">
    <input name="action" type="hidden">
    <input name="where_export" type="hidden" value="<?php  echo to_html($_SESSION['export_where']); ?>">
    <input name="step" type="hidden">
    <input name="allids" type="hidden" id="allids" value="<?php echo $this->_tpl_vars['ALLIDS']; ?>
">
    <input name="selectedboxes" id="selectedboxes" type="hidden" value="<?php echo $this->_tpl_vars['SELECTEDIDS']; ?>
">
    <input name="allselectedboxes" id="allselectedboxes" type="hidden" value="<?php echo $this->_tpl_vars['ALLSELECTEDIDS']; ?>
">
    <input name="current_page_boxes" id="current_page_boxes" type="hidden" value="<?php echo $this->_tpl_vars['CURRENT_PAGE_BOXES']; ?>
">
    <!-- List View Master Holder starts -->
    <table border=0 cellspacing=1 cellpadding=0 width=100% class="lvtBg">
        <tr>
            <td>
                <!-- List View's Buttons and Filters starts -->
                <table border=0 cellspacing=0 cellpadding=2 width=100% class="small">
                    <tr>
                        <!-- Buttons -->
                        <td style="padding-right:20px" nowrap>

                            <?php $_from = $this->_tpl_vars['BUTTONS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['button_check'] => $this->_tpl_vars['button_label']):
?>
                                <?php if ($this->_tpl_vars['button_check'] == 'del'): ?>
    
                                    <button class="crmbutton small delete" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return massDelete('<?php echo $this->_tpl_vars['MODULE']; ?>
')">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;<?php echo $this->_tpl_vars['button_label']; ?>

                                    </button>
                                <?php elseif ($this->_tpl_vars['button_check'] == 'mass_edit'): ?>
                                    <?php if ($this->_tpl_vars['MODULE'] != 'Voucher'): ?>
                                    <button class="crmbutton small btnmassedit" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return mass_edit(this, 'massedit', '<?php echo $this->_tpl_vars['MODULE']; ?>
', '<?php echo $this->_tpl_vars['CATEGORY']; ?>
')">
                                        <img src="themes/softed/images/massedit_o.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;<?php echo $this->_tpl_vars['button_label']; ?>

                                    </button>
                                    <?php endif; ?>
                                <?php elseif ($this->_tpl_vars['button_check'] == 's_mail'): ?>
                                    

                                <?php elseif ($this->_tpl_vars['button_check'] == 's_cmail'): ?>
                                    <button class="crmbutton small btnsendmail" type="submit" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return massMail('<?php echo $this->_tpl_vars['MODULE']; ?>
')">
                                        <img src="themes/softed/images/sendemail_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;<?php echo $this->_tpl_vars['button_label']; ?>

                                    </button>
                                <?php elseif ($this->_tpl_vars['button_check'] == 'mailer_exp'): ?>
                                    
                                <?php elseif ($this->_tpl_vars['button_check'] == 'c_owner' && $this->_tpl_vars['MODULE'] == 'Calendar'): ?>
                                    
                                    <button class="crmbutton small edit" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return change(this,'changeowner')">
                                        <?php echo $this->_tpl_vars['button_label']; ?>

                                    </button>
                                <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?>

                            <?php if ($this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Leads'): ?>
                                
                                 <button class="crmbutton small btnsendmail" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return eMail('<?php echo $this->_tpl_vars['MODULE']; ?>
',this);">
                                    <img src="themes/softed/images/sendemail_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;Send Mail
                                </button>
                                
                                <button class="crmbutton small btnsendmail" type="button" value="Send SMS" onclick="return mass_sms('<?php echo $this->_tpl_vars['MODULE']; ?>
')">
                                    <img src="themes/softed/images/sms_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;Send SMS
                                </button>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['MODULE'] == 'Competitor'): ?>
                                <button class="crmbutton small save" type="button" value="Competitor Analysis" onclick="modal_competitor();">
                                    <img src="themes/softed/images/approval.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;Competitor Analysis
                                </button>
                            <?php endif; ?>

                            <?php if ($this->_tpl_vars['MODULE'] == 'Products'): ?>
                                <button class="crmbutton small save" type="button" value="" onclick="getERPProduct()">
                                    Get Product
                                </button>
                            <?php endif; ?>
                        </td>
                        <td class="small" nowrap>
                            <?php echo $this->_tpl_vars['recordListRange']; ?>

                        </td>
                        <!-- Page Navigation -->
                        <td nowrap width="30%" align="center">
                            <table border=0 cellspacing=0 cellpadding=0 class="small">
                                <tr><?php echo $this->_tpl_vars['NAVIGATION']; ?>
</tr>
                            </table>
                        </td>
                        <td width=40% align="right">
                            <!-- Filters -->
                            <?php if ($this->_tpl_vars['HIDE_CUSTOM_LINKS'] != '1'): ?>
                                <table border=0 cellspacing=0 cellpadding=0 class="small">
                                    <tr>
                                         <!--Padding 20 - 200 -->
                                        <td><?php echo $this->_tpl_vars['APP']['LBL_PAGESIZE']; ?>
</td>
                                        <td style="padding-left:5px;padding-right:5px">
                                           <select id='pagesize' class="small" style="width:50px;border-radius:3px;"  onchange="per_padding()" >
                                            <?php if ($_REQUEST['pagesize'] == '20' || $_REQUEST['pagesize'] == ''): ?>
                                                <option value="20" selected="selected">20</option>
                                            <?php else: ?>
                                                <option value="20">20</option>
                                            <?php endif; ?>
                                            <?php if ($_REQUEST['pagesize'] == '50'): ?>
                                                <option value="50" selected="selected" >50</option>
                                            <?php else: ?>
                                                <option value="50">50</option>
                                            <?php endif; ?>
                                            <?php if ($_REQUEST['pagesize'] == '100'): ?>
                                                <option value="100" selected="selected">100</option>
                                            <?php else: ?>
                                                <option value="100">100</option>
                                            <?php endif; ?>
                                            <?php if ($_REQUEST['pagesize'] == '200'): ?>
                                                <option value="200" selected="selected" >200</option>
                                            <?php else: ?>
                                                <option value="200">200</option>
                                            <?php endif; ?>
                                           </select>
                                        </td>
                                        <!--Padding 20 - 200 -->

                                        <td><?php echo $this->_tpl_vars['APP']['LBL_VIEW']; ?>
</td>
                                        <td style="padding-left:5px;padding-right:5px">
                                            <SELECT name="viewname" id="viewname" class="small" onchange="showDefaultCustomView(this,'<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['CATEGORY']; ?>
')" style="border-radius:3px; width: 100% !important"><?php echo $this->_tpl_vars['CUSTOMVIEW_OPTION']; ?>
</SELECT>
                                        </td>

                                        <?php if ($this->_tpl_vars['ALL'] == 'All'): ?>
                                            <!-- <td>
                                                <a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=CustomView&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LNK_CV_CREATEVIEW']; ?>
</a>
                                                <span class="small">|</span>
                                                <span class="small" disabled><?php echo $this->_tpl_vars['APP']['LNK_CV_EDIT']; ?>
</span>
                                                <span class="small">|</span>
                                                <span class="small" disabled><?php echo $this->_tpl_vars['APP']['LNK_CV_DELETE']; ?>
</span>
                                            </td> -->
                                            <td>
                                                <a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=CustomView&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LNK_CV_CREATEVIEW']; ?>
</a>
                                                <span class="small">|</span>

                                                <?php if ($this->_tpl_vars['is_admin'] == 'on'): ?>
                                                <a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=CustomView&record=<?php echo $this->_tpl_vars['VIEWID']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LNK_CV_EDIT']; ?>
</a>
                                                <?php else: ?>
                                                <span class="small" disabled><?php echo $this->_tpl_vars['APP']['LNK_CV_EDIT']; ?>
</span>
                                                <?php endif; ?>
                                                <span class="small">|</span>                                       
                                                <span class="small" disabled><?php echo $this->_tpl_vars['APP']['LNK_CV_DELETE']; ?>
</span>

                                            </td>
                                        <?php else: ?>
                                            <td>
                                                <a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=CustomView&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LNK_CV_CREATEVIEW']; ?>
</a>
                                                <span class="small">|</span>
                                                <?php if ($this->_tpl_vars['CV_EDIT_PERMIT'] != 'yes'): ?>
                                                    <span class="small" disabled><?php echo $this->_tpl_vars['APP']['LNK_CV_EDIT']; ?>
</span>
                                                <?php else: ?>
                                                    <a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=CustomView&record=<?php echo $this->_tpl_vars['VIEWID']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LNK_CV_EDIT']; ?>
</a>
                                                <?php endif; ?>
                                                <span class="small">|</span>
                                                <?php if ($this->_tpl_vars['CV_DELETE_PERMIT'] != 'yes'): ?>
                                                    <span class="small" disabled><?php echo $this->_tpl_vars['APP']['LNK_CV_DELETE']; ?>
</span>
                                                <?php else: ?>
                                                    <a href="javascript:confirmdelete('index.php?module=CustomView&action=Delete&dmodule=<?php echo $this->_tpl_vars['MODULE']; ?>
&record=<?php echo $this->_tpl_vars['VIEWID']; ?>
&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
')"><?php echo $this->_tpl_vars['APP']['LNK_CV_DELETE']; ?>
</a>
                                                <?php endif; ?>
                                                <?php if ($this->_tpl_vars['CUSTOMVIEW_PERMISSION']['ChangedStatus'] != '' && $this->_tpl_vars['CUSTOMVIEW_PERMISSION']['Label'] != ''): ?>
                                                    <span class="small">|</span>
                                                    <a href="#" id="customstatus_id"
                                                       onClick="ChangeCustomViewStatus(<?php echo $this->_tpl_vars['VIEWID']; ?>
,<?php echo $this->_tpl_vars['CUSTOMVIEW_PERMISSION']['Status']; ?>
,<?php echo $this->_tpl_vars['CUSTOMVIEW_PERMISSION']['ChangedStatus']; ?>
,'<?php echo $this->_tpl_vars['MODULE']; ?>
','<?php echo $this->_tpl_vars['CATEGORY']; ?>
')"><?php echo $this->_tpl_vars['CUSTOMVIEW_PERMISSION']['Label']; ?>
</a>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                </table>
                                <!-- Filters  END-->
                            <?php endif; ?>

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
                        <?php $_from = $this->_tpl_vars['LISTHEADER']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['listviewforeach'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['listviewforeach']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['header']):
        $this->_foreach['listviewforeach']['iteration']++;
?>
                            <td class="lvtCol"><?php echo $this->_tpl_vars['header']; ?>
</td>
                        <?php endforeach; endif; unset($_from); ?>
                        </tr>
                        <!-- Table Contents -->
                        <?php $_from = $this->_tpl_vars['LISTENTITY']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['entity_id'] => $this->_tpl_vars['entity']):
?>
                            <?php if ($this->_tpl_vars['MODULE'] == 'ServiceRequest'): ?>
                                <tr class="<?php echo $this->_tpl_vars['a_status'][$this->_tpl_vars['entity_id']]['class']; ?>
" onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='<?php echo $this->_tpl_vars['a_status'][$this->_tpl_vars['entity_id']]['class']; ?>
'" id="row_<?php echo $this->_tpl_vars['entity_id']; ?>
">
                            <?php elseif ($this->_tpl_vars['MODULE'] == 'KnowledgeBase' && $this->_tpl_vars['a_status'][$this->_tpl_vars['entity_id']]['class'] != ""): ?>
                                <tr class="<?php echo $this->_tpl_vars['a_status'][$this->_tpl_vars['entity_id']]['class']; ?>
" onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='<?php echo $this->_tpl_vars['a_status'][$this->_tpl_vars['entity_id']]['class']; ?>
'" id="row_<?php echo $this->_tpl_vars['entity_id']; ?>
">
                            <?php else: ?>
                                <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'" id="row_<?php echo $this->_tpl_vars['entity_id']; ?>
">
                            <?php endif; ?>

                        <?php if ($this->_tpl_vars['MODULE'] == 'KnowledgeBase' && $this->_tpl_vars['a_status'][$this->_tpl_vars['entity_id']]['class'] != ""): ?>
                            <td width="5%">
                                <input type="checkbox" NAME="selected_id" id="<?php echo $this->_tpl_vars['entity_id']; ?>
" value='<?php echo $this->_tpl_vars['entity_id']; ?>
'
                                   onClick="check_object(this)">
                                <img src="<?php echo $this->_tpl_vars['a_status'][$this->_tpl_vars['entity_id']]['icon']; ?>
" title="<?php echo $this->_tpl_vars['a_status'][$this->_tpl_vars['entity_id']]['title']; ?>
" width="20">
                        <?php elseif ($this->_tpl_vars['MODULE'] == 'Quotes'): ?>
                            <?php if ($this->_tpl_vars['a_status'][$this->_tpl_vars['entity_id']]['quotation_status'] == 'เปิดใบเสนอราคา'): ?>
                                <td width="2%"><input type="checkbox" NAME="selected_id" id="<?php echo $this->_tpl_vars['entity_id']; ?>
" value='<?php echo $this->_tpl_vars['entity_id']; ?>
' onClick="check_object(this)">
                            <?php else: ?>
                                <td width="2%"></td>
                            <?php endif; ?>
                        
                        <?php elseif ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
                                <td width="2%"><input type="checkbox" NAME="selected_id" id="<?php echo $this->_tpl_vars['entity_id']; ?>
" value='<?php echo $this->_tpl_vars['entity_id']; ?>
' onClick="check_object(this)">
                                   
                        <?php elseif ($this->_tpl_vars['MODULE'] == 'Leads'): ?>
                            <?php if ($this->_tpl_vars['a_status'][$this->_tpl_vars['entity_id']]['lead_convert'] == '1'): ?>
                                <td width="2%"></td>
                            <?php else: ?>
                                <td width="2%">
                                <input type="checkbox" NAME="selected_id" id="<?php echo $this->_tpl_vars['entity_id']; ?>
" value='<?php echo $this->_tpl_vars['entity_id']; ?>
' onClick="check_object(this)">
                            <?php endif; ?>

                        <?php elseif ($this->_tpl_vars['MODULE'] == 'Calendar'): ?>
                            <?php if ($this->_tpl_vars['a_status'][$this->_tpl_vars['entity_id']]['flag_send_report'] == '1'): ?>
                                <td width="2%"></td>
                            <?php else: ?>
                                <td width="2%">
                                <input type="checkbox" NAME="selected_id" id="<?php echo $this->_tpl_vars['entity_id']; ?>
" value='<?php echo $this->_tpl_vars['entity_id']; ?>
' onClick="check_object(this)">
                            <?php endif; ?>
                        <?php elseif ($this->_tpl_vars['MODULE'] == 'Projects'): ?>
                            <?php if ($this->_tpl_vars['a_display'][$this->_tpl_vars['entity_id']]['display'] == 'yes'): ?>
                            <td width="2%"><input type="checkbox" NAME="selected_id" id="<?php echo $this->_tpl_vars['entity_id']; ?>
" value='<?php echo $this->_tpl_vars['entity_id']; ?>
' onClick="check_object(this)">
                            <?php else: ?>
                            <td width="2%">
                            <?php endif; ?>
                        <?php else: ?>
                            <td width="2%"><input type="checkbox" NAME="selected_id" id="<?php echo $this->_tpl_vars['entity_id']; ?>
" value='<?php echo $this->_tpl_vars['entity_id']; ?>
' onClick="check_object(this)">
                                
                        <?php endif; ?>
                         </td>
                            
                            <?php $_from = $this->_tpl_vars['entity']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
                                                                <td onmouseover="vtlib_listview.trigger('cell.onmouseover', $(this))"
                                    onmouseout="vtlib_listview.trigger('cell.onmouseout', $(this))"
                                    height="18"><?php echo $this->_tpl_vars['data']; ?>
</td>
                                                            <?php endforeach; endif; unset($_from); ?>

                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td style="background-color:#efefef;height:340px" align="center"
                                    colspan="<?php echo $this->_foreach['listviewforeach']['iteration']+1; ?>
">
                                    <div style="border: 3px solid rgb(201 201 201); background-color: rgb(255, 255, 255); width: 45%; position: relative; z-index: 2;border-radius: 6px;">
                                        <?php $this->assign('vowel_conf', 'LBL_A'); ?>
                                        
                                        <?php if ($this->_tpl_vars['MODULE'] == 'Accounts'): ?>
                                            <?php $this->assign('vowel_conf', 'LBL_AN'); ?>
                                        <?php endif; ?>

                                        <?php $this->assign('MODULE_CREATE', $this->_tpl_vars['SINGLE_MOD']); ?>
                                        
                                        <?php if ($this->_tpl_vars['MODULE'] == 'HelpDesk'): ?>
                                            <?php $this->assign('MODULE_CREATE', 'Ticket'); ?>
                                        <?php endif; ?>

                                        <?php if ($this->_tpl_vars['CHECK']['EditView'] == 'yes' && $this->_tpl_vars['MODULE'] != 'Emails' && $this->_tpl_vars['MODULE'] != 'Webmails' && $this->_tpl_vars['MODULE'] != 'Opportunity'): ?>
                                            <table border="0" cellpadding="5" cellspacing="0" width="98%">
                                                <tr>
                                                    <td rowspan="2" width="25%"><img src="<?php echo aicrm_imageurl('empty.jpg', $this->_tpl_vars['THEME']); ?>
" height="60" width="61"></td>
                                                    <td style="border-bottom: 1px solid rgb(204, 204, 204);" nowrap="nowrap" width="75%"><span class="genHeaderSmall">
                                    				<?php if ($this->_tpl_vars['MODULE_CREATE'] == 'Quotes'): ?>
                                                        <?php echo $this->_tpl_vars['APP']['LBL_NO']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_FOUND']; ?>
 !
                                                    <?php elseif ($this->_tpl_vars['MODULE'] == 'Calendar'): ?>
                                                        <?php echo $this->_tpl_vars['APP']['LBL_NO']; ?>
 <?php echo $this->_tpl_vars['APP']['ACTIVITIES']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_FOUND']; ?>
 !
                                                    <?php else: ?>
                                                                                                                <?php echo $this->_tpl_vars['APP']['LBL_NO']; ?>
 <?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['MODULE_CREATE']; ?>
<?php endif; ?> <?php echo $this->_tpl_vars['APP']['LBL_FOUND']; ?>
 !
                                                    <?php endif; ?>
                                    				</span>
                                                </td>
                                              </tr>
                                              <tr>

                                                    <td class="small" align="left"
                                                        nowrap="nowrap"><?php echo $this->_tpl_vars['APP']['LBL_YOU_CAN_CREATE']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['vowel_conf']]; ?>


                                                        <?php if ($this->_tpl_vars['MODULE_CREATE'] == 'Quotes'): ?>
                                                            <?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['MODULE_CREATE']]; ?>

                                                        <?php else: ?>
                                                                                                                        <?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['MODULE_CREATE']; ?>
<?php endif; ?>
                                                        <?php endif; ?>

                                                        <?php echo $this->_tpl_vars['APP']['LBL_NOW']; ?>
. <?php echo $this->_tpl_vars['APP']['LBL_CLICK_THE_LINK']; ?>
:<br>
                                                        
                                                        <?php if ($this->_tpl_vars['MODULE'] != 'Calendar'): ?>
                                                            &nbsp;&nbsp;-
                                                            <?php if ($this->_tpl_vars['MODULE'] == 'Projects'): ?>
                                                                <a  href='MOAIMB-Webview/Projects/create_web?userid=<?php echo $this->_tpl_vars['CURRENT_USER_ID']; ?>
' class='ls-modal'>
                                                                    <?php echo $this->_tpl_vars['APP']['LBL_CREATE']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['vowel_conf']]; ?>

                                                                    <?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['MODULE_CREATE']; ?>
<?php endif; ?>
                                                                </a><br>
                                                            <?php else: ?>
                                                            <a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=EditView&return_action=DetailView&parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LBL_CREATE']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['vowel_conf']]; ?>

                                                                
                                                                <?php if ($this->_tpl_vars['MODULE_CREATE'] == 'Quotes'): ?>
                                                                    <?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['MODULE_CREATE']]; ?>

                                                                <?php else: ?>
                                                                                                                                        <?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['MODULE_CREATE']; ?>
<?php endif; ?>
                                                                <?php endif; ?>
                                                            </a><br>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            &nbsp;&nbsp;-
                                                            <a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&amp;action=EditView&amp;return_module=Calendar&amp;activity_mode=Events&amp;return_action=DetailView&amp;parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LBL_CREATE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_AN']; ?>
 <?php echo $this->_tpl_vars['APP']['Event']; ?>
</a>
                                                            <br>
                                                            &nbsp;&nbsp;-
                                                            <a href="index.php?module=<?php echo $this->_tpl_vars['MODULE']; ?>
&amp;action=EditView&amp;return_module=Calendar&amp;activity_mode=Task&amp;return_action=DetailView&amp;parenttab=<?php echo $this->_tpl_vars['CATEGORY']; ?>
"><?php echo $this->_tpl_vars['APP']['LBL_CREATE']; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_A']; ?>
 <?php echo $this->_tpl_vars['APP']['Task']; ?>
</a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php else: ?>
                                            <table border="0" cellpadding="5" cellspacing="0" width="98%">
                                                <tr>
                                                    <td rowspan="2" width="25%"><img
                                                                src="<?php echo aicrm_imageurl('denied.gif', $this->_tpl_vars['THEME']); ?>
"></td>
                                                    <td style="border-bottom: 1px solid rgb(204, 204, 204);"
                                                        nowrap="nowrap" width="75%"><span class="genHeaderSmall">
				                                    
                                                    <?php if ($this->_tpl_vars['MODULE_CREATE'] == 'Quotes'): ?>
                                                        <?php echo $this->_tpl_vars['APP']['LBL_NO']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_FOUND']; ?>

                                                        !</span></td>
                                                    <?php else: ?>

                                                                                                            <?php echo $this->_tpl_vars['APP']['LBL_NO']; ?>
 <?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['MODULE_CREATE']; ?>
<?php endif; ?> <?php echo $this->_tpl_vars['APP']['LBL_FOUND']; ?>

                                                        !</span></td>
                                                    <?php endif; ?>
                                                </tr>
                                                <tr>
                                                    <td class="small" align="left"
                                                        nowrap="nowrap"><?php echo $this->_tpl_vars['APP']['LBL_YOU_ARE_NOT_ALLOWED_TO_CREATE']; ?>
 <?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['vowel_conf']]; ?>

                                                        <?php if ($this->_tpl_vars['MODULE_CREATE'] == 'Quotes'): ?>
                                                            <?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['MODULE_CREATE']]; ?>

                                                        <?php else: ?>
                                                                                                                        <?php if ($this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]): ?><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['MODULE_CREATE']]; ?>
<?php else: ?><?php echo $this->_tpl_vars['MODULE_CREATE']; ?>
<?php endif; ?>
                                                        <?php endif; ?>
                                                        <br>
                                                    </td>
                                                </tr>
                                            </table>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; unset($_from); ?>
                    </table>
                </div>

                <table border=0 cellspacing=0 cellpadding=2 width=100%>
                    <tr>
                        <td style="padding-right:20px" nowrap>
                            <?php $_from = $this->_tpl_vars['BUTTONS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['button_check'] => $this->_tpl_vars['button_label']):
?>
                                <?php if ($this->_tpl_vars['button_check'] == 'del'): ?>
                                   
                                    <button class="crmbutton small delete" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return massDelete('<?php echo $this->_tpl_vars['MODULE']; ?>
')">
                                        <img src="themes/softed/images/delete_w.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;<?php echo $this->_tpl_vars['button_label']; ?>

                                    </button>
                                <?php elseif ($this->_tpl_vars['button_check'] == 'mass_edit'): ?>
                                    <?php if ($this->_tpl_vars['MODULE'] != 'Voucher'): ?>
                                    <button class="crmbutton small btnmassedit" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return mass_edit(this, 'massedit', '<?php echo $this->_tpl_vars['MODULE']; ?>
')">
                                        <img src="themes/softed/images/massedit_o.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;<?php echo $this->_tpl_vars['button_label']; ?>

                                    </button>
                                    <?php endif; ?>
                                <?php elseif ($this->_tpl_vars['button_check'] == 's_mail'): ?>
                                    <!-- <input class="crmbutton small edit" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return eMail('<?php echo $this->_tpl_vars['MODULE']; ?>
',this)"/> -->
                                    <!-- <button class="crmbutton small btnsendmail" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return eMail('<?php echo $this->_tpl_vars['MODULE']; ?>
',this)">
                                        <img src="themes/softed/images/sendemail_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;<?php echo $this->_tpl_vars['button_label']; ?>

                                    </button> -->
                                <?php elseif ($this->_tpl_vars['button_check'] == 's_cmail'): ?>
                                    
                                    <button class="crmbutton small btnsendmail" type="submit" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return massMail('<?php echo $this->_tpl_vars['MODULE']; ?>
')">
                                        <img src="themes/softed/images/sendemail_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;<?php echo $this->_tpl_vars['button_label']; ?>

                                    </button>
                                <?php elseif ($this->_tpl_vars['button_check'] == 'mailer_exp'): ?>
                                                                   
                                <?php elseif ($this->_tpl_vars['button_check'] == 'c_owner' && $this->_tpl_vars['MODULE'] == 'Calendar'): ?>

                                    
                                    <button class="crmbutton small edit" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return change(this,'changeowner')">
                                        <?php echo $this->_tpl_vars['button_label']; ?>

                                    </button>
                                <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?>
                            
                            <?php if ($this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Leads'): ?>
                                <button class="crmbutton small btnsendmail" type="button" value="<?php echo $this->_tpl_vars['button_label']; ?>
" onclick="return eMail('<?php echo $this->_tpl_vars['MODULE']; ?>
',this);">
                                    <img src="themes/softed/images/sendemail_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;Send Mail
                                </button>
                                
                                <button class="crmbutton small btnsendmail" type="button" value="Send SMS" onclick="return mass_sms( '<?php echo $this->_tpl_vars['MODULE']; ?>
')">
                                    <img src="themes/softed/images/sms_b.png" border="0" style="width: 15px; vertical-align: middle;">&nbsp;&nbsp;Send SMS
                                </button>
                            <?php endif; ?>       

                        </td>
                        <td class="small" nowrap>
                            <?php echo $this->_tpl_vars['recordListRange']; ?>

                        </td>
                        <td nowrap width="30%" align="center">
                            <table border=0 cellspacing=0 cellpadding=0 class="small">
                                <tr><?php echo $this->_tpl_vars['NAVIGATION']; ?>
</tr>
                            </table>
                        </td>
                        <td align="right" width=40%>
                            <table border=0 cellspacing=0 cellpadding=0 class="small">
                                <tr>
                                    <?php echo $this->_tpl_vars['WORDTEMPLATEOPTIONS']; ?>
<?php echo $this->_tpl_vars['MERGEBUTTON']; ?>

                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</form>
<?php echo $this->_tpl_vars['SELECT_SCRIPT']; ?>


<div id="basicsearchcolumns" style="display:none;"><select name="search_field" id="bas_searchfield" class="txtBox" style="width:150px"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['SEARCHLISTHEADER']), $this);?>
</select>
</div>