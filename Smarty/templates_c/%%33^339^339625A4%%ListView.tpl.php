<?php /* Smarty version 2.6.18, created on 2026-04-08 17:43:10
         compiled from ListView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'aicrm_imageurl', 'ListView.tpl', 219, false),array('function', 'html_options', 'ListView.tpl', 255, false),)), $this); ?>
<script language="JavaScript" type="text/javascript" src="include/js/ListView.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/search.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/Merge.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/dtlviewajax.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/FieldDependencies.js"></script>

<script language="JavaScript" type="text/javascript" src="asset/js/jquery-1.12.4.js"></script>
<!-- <script language="JavaScript" type="text/javascript" src="asset/js/jquery-ui-1.10.4.custom.min.js"></script>
<script language="JavaScript" type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
<link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css"> -->


<script type="text/javascript">
	jQuery.noConflict();
</script>
<script language="javascript" type="text/javascript">
var typeofdata = new Array();
typeofdata['E'] = ['is','isn','bwt','ewt','cts','dcts'];
typeofdata['V'] = ['is','isn','bwt','ewt','cts','dcts'];
typeofdata['N'] = ['is','isn','lst','grt','lsteq','grteq'];
typeofdata['NN'] = ['is','isn','lst','grt','lsteq','grteq'];
typeofdata['T'] = ['is','isn','lst','grt','lsteq','grteq'];
typeofdata['I'] = ['is','isn','lst','grt','lsteq','grteq'];
typeofdata['C'] = ['is','isn'];
typeofdata['DT'] = ['is','isn','lst','grt','lsteq','grteq'];
typeofdata['D'] = ['is','isn','lst','grt','lsteq','grteq'];
var fLabels = new Array();
fLabels['is'] = "<?php echo $this->_tpl_vars['APP']['is']; ?>
";
fLabels['isn'] = "<?php echo $this->_tpl_vars['APP']['is_not']; ?>
";
fLabels['bwt'] = "<?php echo $this->_tpl_vars['APP']['begins_with']; ?>
";
fLabels['ewt'] = "<?php echo $this->_tpl_vars['APP']['ends_with']; ?>
";
fLabels['cts'] = "<?php echo $this->_tpl_vars['APP']['contains']; ?>
";
fLabels['dcts'] = "<?php echo $this->_tpl_vars['APP']['does_not_contains']; ?>
";
fLabels['lst'] = "<?php echo $this->_tpl_vars['APP']['less_than']; ?>
";
fLabels['grt'] = "<?php echo $this->_tpl_vars['APP']['greater_than']; ?>
";
fLabels['lsteq'] = "<?php echo $this->_tpl_vars['APP']['less_or_equal']; ?>
";
fLabels['grteq'] = "<?php echo $this->_tpl_vars['APP']['greater_or_equal']; ?>
";
var noneLabel;
<?php echo '
function trimfValues(value)
{
    var string_array;
    string_array = value.split(":");
    return string_array[4];
}

function updatefOptions(sel, opSelName) {
    var selObj = document.getElementById(opSelName);
    var fieldtype = null ;

    var currOption = selObj.options[selObj.selectedIndex];
    var currField = sel.options[sel.selectedIndex];
    
    var fld = currField.value.split(":");
    var tod = fld[4];

    if(currField.value != null && currField.value.length != 0)
    {
	fieldtype = trimfValues(currField.value);
	fieldtype = fieldtype.replace(/\\\\\'/g,\'\');
	ops = typeofdata[fieldtype];
	var off = 0;
	if(ops != null)
	{

		var nMaxVal = selObj.length;
		for(nLoop = 0; nLoop < nMaxVal; nLoop++)
		{
			selObj.remove(0);
		}
	
		for (var i = 0; i < ops.length; i++)
		{
			var label = fLabels[ops[i]];
			if (label == null) continue;
			var option = new Option (fLabels[ops[i]], ops[i]);
			selObj.options[i] = option;
			if (currOption != null && currOption.value == option.value)
			{
				option.selected = true;
			}
		}
	}
    }else
    {
	var nMaxVal = selObj.length;
	for(nLoop = 0; nLoop < nMaxVal; nLoop++)
	{
		selObj.remove(0);
	}
	selObj.options[0] = new Option (\'None\', \'\');
	if (currField.value == \'\') {
		selObj.options[0].selected = true;
	}
    }

}
'; ?>

</script>
<script language="JavaScript" type="text/javascript" src="modules/<?php echo $this->_tpl_vars['MODULE']; ?>
/<?php echo $this->_tpl_vars['MODULE']; ?>
.js"></script>
<script language="javascript">
function checkgroup()
{
  if($("group_checkbox").checked)
  {
  	document.change_ownerform_name.lead_group_owner.style.display = "block";
  	document.change_ownerform_name.lead_owner.style.display = "none";
  }
  else
  {
  	document.change_ownerform_name.lead_owner.style.display = "block";
  	document.change_ownerform_name.lead_group_owner.style.display = "none";
  }    
}

function callSearch(searchtype)
{
	for(i=1;i<=26;i++)
    	{
        	var data_td_id = 'alpha_'+ eval(i);
        	getObj(data_td_id).className = 'searchAlph';
    	}
    	gPopupAlphaSearchUrl = '';
	search_fld_val= $('bas_searchfield').options[$('bas_searchfield').selectedIndex].value;
	search_txt_val= encodeURIComponent(document.basicSearch.search_text.value);
        var urlstring = '';
        if(searchtype == 'Basic')
        {
        		var p_tab = document.getElementsByName("parenttab");
                urlstring = 'search_field='+search_fld_val+'&searchtype=BasicSearch&search_text='+search_txt_val+'&';
                urlstring = urlstring + 'parenttab='+p_tab[0].value+ '&';
        }
        else if(searchtype == 'Advanced')
        {
                var no_rows = document.basicSearch.search_cnt.value;
                for(jj = 0 ; jj < no_rows; jj++)
                {
                        var sfld_name = getObj("Fields"+jj);
                        var scndn_name= getObj("Condition"+jj);
                        var srchvalue_name = getObj("Srch_value"+jj);
                        var p_tab = document.getElementsByName("parenttab");
                        urlstring = urlstring+'Fields'+jj+'='+sfld_name[sfld_name.selectedIndex].value+'&';
                        urlstring = urlstring+'Condition'+jj+'='+scndn_name[scndn_name.selectedIndex].value+'&';
			urlstring = urlstring+'Srch_value'+jj+'='+encodeURIComponent(srchvalue_name.value)+'&';
                        urlstring = urlstring + 'parenttab='+p_tab[0].value+ '&';
                }
                for (i=0;i<getObj("matchtype").length;i++){
                        if (getObj("matchtype")[i].checked==true)
                                urlstring += 'matchtype='+getObj("matchtype")[i].value+'&';
                }
                urlstring += 'search_cnt='+no_rows+'&';
                urlstring += 'searchtype=advance&'
        }
	$("status").style.display="inline";
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody:urlstring +'query=true&file=index&module=<?php echo $this->_tpl_vars['MODULE']; ?>
&action=<?php echo $this->_tpl_vars['MODULE']; ?>
Ajax&ajax=true&search=true',
			onComplete: function(response) {
								$("status").style.display="none";
                                result = response.responseText.split('&#&#&#');
                                $("ListViewContents").innerHTML= result[2];
                                if(result[1] != '')
									alert(result[1]);
								$('basicsearchcolumns').innerHTML = '';
			}
	       }
        );
	return false
}

function alphabetic(module,url,dataid)
{
        for(i=1;i<=26;i++)
        {
                var data_td_id = 'alpha_'+ eval(i);
                getObj(data_td_id).className = 'searchAlph';

        }
        getObj(dataid).className = 'searchAlphselected';
	$("status").style.display="inline";
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module='+module+'&action='+module+'Ajax&file=index&ajax=true&search=true&'+url,
			onComplete: function(response) {
				$("status").style.display="none";
				result = response.responseText.split('&#&#&#');
				$("ListViewContents").innerHTML= result[2];
				if(result[1] != '')
			                alert(result[1]);
				$('basicsearchcolumns').innerHTML = '';
			}
		}
	);
}
</script>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Buttons_List.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                                <div id="searchingUI" style="display:none;">
                                        <table border=0 cellspacing=0 cellpadding=0 width=100%>
                                        <tr>
                                                <td align=center>
                                                <img src="<?php echo aicrm_imageurl('searching.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_SEARCHING']; ?>
"  title="<?php echo $this->_tpl_vars['APP']['LBL_SEARCHING']; ?>
">
                                                </td>
                                        </tr>
                                        </table>

                                </div>
                        </td>
                </tr>
                </table>
        </td>
</tr>
</table>

<table border=0 cellspacing=0 cellpadding=0 width=99% align=center style="box-shadow: 1px 0px 10px #e5e5e5;">
     <tr>
      <td valign=top>
      	<!-- <img src="<?php echo aicrm_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"> -->
      </td>

	<td class="showPanelBg" valign="top" width=100% style="padding:10px;">
	 <!-- SIMPLE SEARCH -->
<div id="searchAcc" style="z-index:1;display:none;position:relative;">
<form name="basicSearch" method="post" action="index.php" onSubmit="return callSearch('Basic');">
<table width="100%" cellpadding="5" cellspacing="0"  class="searchUIBasic small" align="center" border=0>
	<tr>
		<td class="searchUIName small" nowrap align="left">
		<span class="moduleName"><?php echo $this->_tpl_vars['APP']['LBL_SEARCH']; ?>
</span><br>
		<span class="small"><a href="#" onClick="fnhide('searchAcc');show('advSearch');updatefOptions(document.getElementById('Fields0'), 'Condition0');document.basicSearch.searchtype.value='advance';"><?php echo $this->_tpl_vars['APP']['LBL_GO_TO']; ?>
 <?php echo $this->_tpl_vars['APP']['LNK_ADVANCED_SEARCH']; ?>
</a></span>
		</td>
		<td class="small" nowrap align=right><b><?php echo $this->_tpl_vars['APP']['LBL_SEARCH_FOR']; ?>
</b></td>
		<td class="small"><input type="text"  class="txtBox" name="search_text"></td>
		<td class="small" nowrap><b><?php echo $this->_tpl_vars['APP']['LBL_IN']; ?>
</b>&nbsp;</td>
		<td class="small" nowrap>
			<div id="basicsearchcolumns_real">
			<select name="search_field" id="bas_searchfield" class="txtBox" >
			 <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['SEARCHLISTHEADER']), $this);?>

			</select>
			</div>
            
            <input type="hidden" name="searchtype" value="BasicSearch">
            <input type="hidden" name="module" value="<?php echo $this->_tpl_vars['MODULE']; ?>
">
            <input type="hidden" name="parenttab" value="<?php echo $this->_tpl_vars['CATEGORY']; ?>
">
			<input type="hidden" name="action" value="index">
            <input type="hidden" name="query" value="true">
			<input type="hidden" name="search_cnt">
		</td>

		<td class="small" nowrap width=40% >
			<input name="submit" type="button" class="crmbutton small create" onClick="callSearch('Basic');" value=" <?php echo $this->_tpl_vars['APP']['LBL_SEARCH_NOW_BUTTON']; ?>
 ">&nbsp;  
		</td>
		<td class="small" valign="top" onMouseOver="this.style.cursor='pointer';" onclick="moveMe('searchAcc');searchshowhide('searchAcc','advSearch')">[x]</td>
	</tr>

	<tr>
		<td colspan="7" align="center" class="small">
			<table border=0 cellspacing=0 cellpadding=0 width=100%>
				<tr>
                    <?php echo $this->_tpl_vars['ALPHABETICAL']; ?>

                </tr>
            </table>
		</td>
	</tr>
</table>
</form>
</div>
<!-- ADVANCED SEARCH -->
<div id="advSearch" style="display:none;">
<form name="advSearch" method="post" action="index.php" onSubmit="totalnoofrows();return callSearch('Advanced');">
		<table  cellspacing=0 cellpadding=5 width=100% class="searchUIAdv1 small" align="center" border=0>
			<tr>
				<td class="searchUIName small" nowrap align="left"><span class="moduleName"><?php echo $this->_tpl_vars['APP']['LBL_SEARCH']; ?>
</span><br><span class="small"><a href="#" onClick="show('searchAcc');fnhide('advSearch')"><?php echo $this->_tpl_vars['APP']['LBL_GO_TO']; ?>
 <?php echo $this->_tpl_vars['APP']['LNK_BASIC_SEARCH']; ?>
</a></span></td>
				<td nowrap class="small"><b><input name="matchtype" type="radio" value="all">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_ADV_SEARCH_MSG_ALL']; ?>
</b></td>
				<td nowrap width=60% class="small" ><b><input name="matchtype" type="radio" value="any" checked>&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_ADV_SEARCH_MSG_ANY']; ?>
</b></td>
				<td class="small" valign="top" onMouseOver="this.style.cursor='pointer';" onclick="moveMe('searchAcc');searchshowhide('searchAcc','advSearch')">[x]</td>
			</tr>
		</table>

		<table cellpadding="2" cellspacing="0" width="100%" align="center" class="searchUIAdv2 small" border=0>
			<tr>
				<td align="center" class="small" width=90%>
				<div id="fixed" style="position:relative;width:95%;height:80px;padding:0px; overflow:auto;border:1px solid #CCCCCC;background-color:#ffffff" class="small">
					<table border=0 width=95%>
					<tr>
						<td align=left>
							<table width="100%"  border="0" cellpadding="2" cellspacing="0" id="adSrc" align="left">
								<tr>
									<td width="31%"><select name="Fields0" id="Fields0" class="detailedViewTextBox txtBox" onchange="updatefOptions(this, 'Condition0')"><?php echo $this->_tpl_vars['FIELDNAMES']; ?>
</select>
									</td>
									<td width="32%"><select name="Condition0" id="Condition0" class="detailedViewTextBox txtBox"><?php echo $this->_tpl_vars['CRITERIA']; ?>
</select>
									</td>
									<td width="32%"><input type="text" name="Srch_value0" class="detailedViewTextBox txtBox"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				</div>	
				</td>
			</tr>
		</table>
			
		<table border=0 cellspacing=0 cellpadding=5 width=100% class="searchUIAdv3 small" align="center">
		<tr>
			<td align=left width=40%>
				<input type="button" name="more" value=" <?php echo $this->_tpl_vars['APP']['LBL_MORE']; ?>
 " onClick="fnAddSrch('<?php echo $this->_tpl_vars['FIELDNAMES']; ?>
','<?php echo $this->_tpl_vars['CRITERIA']; ?>
')" class="crmbuttom small edit" >
				<input name="button" type="button" value=" <?php echo $this->_tpl_vars['APP']['LBL_FEWER_BUTTON']; ?>
 " onclick="delRow()" class="crmbuttom small edit" >
			</td>
			<td align=left class="small"><input type="button" class="crmbutton small create" value=" <?php echo $this->_tpl_vars['APP']['LBL_SEARCH_NOW_BUTTON']; ?>
 " onClick="totalnoofrows();callSearch('Advanced');">
			</td>
		</tr>
	</table>
</form>
</div>		

<div id="mergeDup" style="z-index:1;display:none;position:relative;">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "MergeColumns.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>	 
	   <!-- PUBLIC CONTENTS STARTS-->
<div id="ListViewContents" class="small" style="width:100%;">
	<?php if ($this->_tpl_vars['MODULE'] != 'Documents'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "ListViewEntries.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else: ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "DocumentsListViewEntries.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
</div>

     </td>
        <td valign=top>
        	<!-- <img src="<?php echo aicrm_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
"> -->
        </td>
   </tr>
</table>

<!-- MassEdit Feature -->
<div id="massedit" class="layerPopup" style="display:none;width:80%;">
<table width="100%" border="0" cellpadding="3" cellspacing="0" class="layerHeadingULine">
	<tr>
		<td class="layerPopupHeading" align="left" width="60%"><?php echo $this->_tpl_vars['APP']['LBL_MASSEDIT_FORM_HEADER']; ?>
</td>
		<td>&nbsp;</td>
		<td align="right" width="40%"><img onClick="fninvsh('massedit');" title="<?php echo $this->_tpl_vars['APP']['LBL_CLOSE']; ?>
" alt="<?php echo $this->_tpl_vars['APP']['LBL_CLOSE']; ?>
" style="cursor:pointer;" src="<?php echo aicrm_imageurl('close.gif', $this->_tpl_vars['THEME']); ?>
" align="absmiddle" border="0"></td>
	</tr>
</table>
<div id="massedit_form_div"></div>

</div>
<!-- END -->
<?php if ($this->_tpl_vars['MODULE'] == 'Leads' || $this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] == 'Accounts'): ?>
<form name="SendMail"> <div id="sendmail_cont" style="z-index:100001;position:absolute;color:#f91818;top:125px;"></div> </form>
<?php endif; ?>

<?php if ($this->_tpl_vars['MODULE'] == 'Products' || $this->_tpl_vars['MODULE'] == 'Projects'): ?>
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
					<h4 class="modal-title" style="display: inline;">Upload รูปสินค้า 
					<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="return confirm_import_attachment_product('Import ข้อมูลรูปสินค้า (Product)','');"> Import ข้อมูลรูปสินค้า (Product)</button></h4>
					<button type="button" class="btn btn-default" data-dismiss="modal" style="float: right;">X</button>
				</div>
				<div class="modal-body">
					<iframe src="" style="zoom:1" height="700" width="100%" frameborder="0"></iframe>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>


<script>
<?php echo '

jQuery(\'.ls-modal\').on(\'click\', function(e){
  jQuery(\'iframe\').attr("src", ""); 
  e.preventDefault();
  
  jQuery(\'#myModal\').modal(\'show\').find(\'.modal-body\');
  jQuery(\'iframe\').attr("src", jQuery(this).attr(\'href\')); 
});

function modal_projects(link,event){
	jQuery(\'iframe\').attr("src", ""); 
  	event.preventDefault();
	jQuery(\'#myModal\').modal(\'show\').find(\'.modal-body\');
	jQuery(\'iframe\').attr("src", link); 
}

function modal_upload_images(link, event){
    event.preventDefault();

    const $modal = jQuery(\'#myModalUploag\');
    const $iframe = $modal.find(\'iframe\');

    // clear iframe ก่อน
    $iframe.attr(\'src\', \'\');

    // เปิด modal
    $modal.modal(\'show\');

    // รอ modal เปิดเสร็จ
    $modal.one(\'shown.bs.modal\', function () {
        $iframe.attr(\'src\', link);
    });
}

function confirm_import_attachment_product(msg, id) {
    var dlg = jQuery.messager.confirm({
        title: \'Confirm\',
        msg: msg,
        buttons: [{
            text: \'OK\',
            onClick: function () {
                // Show loading indicator **หลังจากกด OK**
                var $loading = jQuery(\'<div id="import-loading" style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(255,255,255,0.7);z-index:99999;text-align:center;"><div style="position:relative;top:40vh;font-size:22px;color:#333;">กำลังดำเนินการ...<br></div></div>\');
                jQuery(\'body\').append($loading);

				//dlg.dialog(\'destroy\');

                jQuery.ajax({
                    type: "POST",
                    url: "import_attachment_product.php",
                    cache: false,
                    data: { crmid: id },
                    dataType: "json",
                    success: function (returndata) {
                        jQuery(\'#import-loading\').remove();
                        if (returndata.Type == "S") {
                            dlg.dialog(\'destroy\');
                            jQuery.messager.alert({
                                title: \'Information\',
                                msg: returndata.Message,
                                fn: function () {
                                    modal_upload_images(\'upload-images-product-comparision/upload-files.php\', event)
                                }
                            });
                        } else {
                            dlg.dialog(\'destroy\');
                            jQuery.messager.alert(\'Message\', returndata.Message, \'info\');
                        }
                    },
                    error: function () {
                        jQuery(\'#import-loading\').remove();
                        dlg.dialog(\'destroy\');
                        jQuery.messager.alert(\'Error\', \'เกิดข้อผิดพลาดในการเชื่อมต่อ\', \'error\');
                    }
                });
            }
        }, {
            text: \'CANCEL\',
            onClick: function () {
                dlg.dialog(\'destroy\');
            }
        }]
    });
}

function ajaxChangeStatus(statusname)
{
	$("status").style.display="inline";
	var viewid = document.getElementById(\'viewname\').options[document.getElementById(\'viewname\').options.selectedIndex].value;
	var idstring = document.getElementById(\'idlist\').value;
	var searchurl= document.getElementById(\'search_url\').value;
	var tplstart=\'&\';
	if(gstart!=\'\')
	{
		tplstart=tplstart+gstart;
	}
	if(statusname == \'status\')
	{
		fninvsh(\'changestatus\');
		var url=\'&leadval=\'+document.getElementById(\'lead_status\').options[document.getElementById(\'lead_status\').options.selectedIndex].value;
		var urlstring ="module=Users&action=updateLeadDBStatus&return_module=Leads"+tplstart+url+"&viewname="+viewid+"&idlist="+idstring+searchurl;
	}
	else if(statusname == \'owner\')
	{
		if($("user_checkbox").checked)
		{
		    fninvsh(\'changeowner\');
		    var url=\'&owner_id=\'+document.getElementById(\'lead_owner\').options[document.getElementById(\'lead_owner\').options.selectedIndex].value;
		    '; ?>

		        var urlstring ="module=Users&action=updateLeadDBStatus&return_module=<?php echo $this->_tpl_vars['MODULE']; ?>
"+tplstart+url+"&viewname="+viewid+"&idlist="+idstring+searchurl;
		    <?php echo '
		} else {
			fninvsh(\'changeowner\');
			var url=\'&owner_id=\'+document.getElementById(\'lead_group_owner\').options[document.getElementById(\'lead_group_owner\').options.selectedIndex].value;
	      	'; ?>

		        var urlstring ="module=Users&action=updateLeadDBStatus&return_module=<?php echo $this->_tpl_vars['MODULE']; ?>
"+tplstart+url+"&viewname="+viewid+"&idlist="+idstring+searchurl;
        	<?php echo '
    	}
	}
	new Ajax.Request(
                \'index.php\',
                {queue: {position: \'end\', scope: \'command\'},
                        method: \'post\',
                        postBody: urlstring,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                result = response.responseText.split(\'&#&#&#\');
                                $("ListViewContents").innerHTML= result[2];
                                if(result[1] != \'\')
                                        alert(result[1]);
				$(\'basicsearchcolumns\').innerHTML = \'\';
                        }
                }
        );
	
}
</script>
'; ?>


<?php if ($this->_tpl_vars['MODULE'] == 'Contacts'): ?>
<?php echo '
<script>
function modifyimage(imagename)
{
	imgArea = getObj(\'dynloadarea\');
        if(!imgArea)
        {
                imgArea = document.createElement("div");
                imgArea.id = \'dynloadarea\';
                imgArea.setAttribute("style","z-index:100000001;");
                imgArea.style.position = \'absolute\';
                imgArea.innerHTML = \'<img width="260" height="200" src="\'+imagename+\'" class="thumbnail">\';
		document.body.appendChild(imgArea);
        }
	PositionDialogToCenter(imgArea.id);
}

function PositionDialogToCenter(ID)
{
       var vpx,vpy;
       if (self.innerHeight) // Mozilla, FF, Safari and Opera
       {
            vpx = self.innerWidth;
            vpy = self.innerHeight;
       }
       else if (document.documentElement && document.documentElement.clientHeight) //IE
       {
            vpx = document.documentElement.clientWidth;
            vpy = document.documentElement.clientHeight;
       }
       else if (document.body) // IE
       {
            vpx = document.body.clientWidth;
            vpy = document.body.clientHeight;
       }

       //Calculate the length from top, left
       dialogTop = (vpy/2 - 280/2) + document.documentElement.scrollTop;
       dialogLeft = (vpx/2 - 280/2);

       //Position the Dialog to center
       $(ID).style.top = dialogTop+"px";
       $(ID).style.left = dialogLeft+"px";
       $(ID).style.display="block";
}

function removeDiv(ID){
    var node2Rmv = getObj(ID);
    if(node2Rmv){node2Rmv.parentNode.removeChild(node2Rmv);}
}

</script>
'; ?>

<?php endif; ?>

