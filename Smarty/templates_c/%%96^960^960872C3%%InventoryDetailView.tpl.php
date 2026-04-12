<?php /* Smarty version 2.6.18, created on 2026-04-09 13:08:24
         compiled from Projects/InventoryDetailView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'aicrm_imageurl', 'Projects/InventoryDetailView.tpl', 232, false),array('modifier', 'replace', 'Projects/InventoryDetailView.tpl', 362, false),)), $this); ?>
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
   <a class="link"  align="right" href="javascript:;"><?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON']; ?>
</a>
</span>
<script type="text/javascript">
	jQuery.noConflict();
</script>
<style>
<?php echo '
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
'; ?>

</style>

<script>
function tagvalidate()
{
	if(trim(document.getElementById('txtbox_tagfields').value) != '')
		SaveTag('txtbox_tagfields','<?php echo $this->_tpl_vars['ID']; ?>
','<?php echo $this->_tpl_vars['MODULE']; ?>
');
	else
	{
		alert("<?php echo $this->_tpl_vars['APP']['PLEASE_ENTER_TAG']; ?>
");
		return false;
	}
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
function showHideStatus(sId,anchorImgId,sImagePath)
{
	oObj = eval(document.getElementById(sId));
	if(oObj.style.display == \'block\')
	{
		oObj.style.display = \'none\';
		eval(document.getElementById(anchorImgId)).src =  \'themes/images/slidedown_b21.png\';
		eval(document.getElementById(anchorImgId)).alt = \'Display\';
		eval(document.getElementById(anchorImgId)).title = \'Display\';
	}
	else
	{
		oObj.style.display = \'block\';
		eval(document.getElementById(anchorImgId)).src =  \'themes/softed/images/slidedown_b.png\';
		eval(document.getElementById(anchorImgId)).alt = \'Hide\';
		eval(document.getElementById(anchorImgId)).title = \'Hide\';
	}
}
function setCoOrdinate(elemId)
{
	oBtnObj = document.getElementById(elemId);
	var tagName = document.getElementById(\'lstRecordLayout\');
	leftpos  = 0;
	toppos = 0;
	aTag = oBtnObj;
	do
	{
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
				$("lstRecordLayout").innerHTML = sResponse;
				Lay = \'lstRecordLayout\';
				var tagName = document.getElementById(Lay);
				var leftSide = findPosX(obj);
				var topSide = findPosY(obj);
				var maxW = tagName.style.width;
				var widthM = maxW.substring(0,maxW.length-2);
				var getVal = eval(leftSide) + eval(widthM);
				if(getVal  > document.body.clientWidth ){
					leftSide = eval(leftSide) - eval(widthM);
					tagName.style.left = leftSide + 230 + \'px\';
				}
				else
					tagName.style.left= leftSide + 388 + \'px\';

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
'; ?>

</script>
<div id="dialog" style="display:none;">Dialog Content.</div>
<div id="lstRecordLayout" class="layerPopup" style="display:none;width:325px;height:300px;"></div> <!-- Code added by SAKTI on 16th Jun, 2008 -->

<table width="100%" cellpadding="2" cellspacing="0" border="0">
   <tr>
	<td>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'Buttons_List1.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

		<!-- Contents -->
		<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
		   <tr>
			<td valign=top><img src="<?php echo aicrm_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>
			<td class="showPanelBg" valign=top width=100%>
			<!-- PUBLIC CONTENTS STARTS-->
			   <div class="small" style="padding:20px" >

				<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
				   <tr>
					<td>
			         			         <?php $this->assign('USE_ID_VALUE', $this->_tpl_vars['MOD_SEQ_ID']); ?>
		  			 <?php if ($this->_tpl_vars['USE_ID_VALUE'] == ''): ?> <?php $this->assign('USE_ID_VALUE', $this->_tpl_vars['ID']); ?> <?php endif; ?>

						<span class="lvtHeaderText"><font color="purple">[ <?php echo $this->_tpl_vars['USE_ID_VALUE']; ?>
 ] </font><?php echo $this->_tpl_vars['NAME']; ?>
 -  <?php echo $this->_tpl_vars['MOD'][$this->_tpl_vars['SINGLE_MOD']]; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</span>&nbsp;&nbsp;&nbsp;<span class="small"><?php echo $this->_tpl_vars['UPDATEINFO']; ?>
</span>&nbsp;<span id="vtbusy_info" style="display:none;" valign="bottom"><img src="<?php echo aicrm_imageurl('vtbusy.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></span><span id="vtbusy_info" style="visibility:hidden;" valign="bottom"><img src="<?php echo aicrm_imageurl('vtbusy.gif', $this->_tpl_vars['THEME']); ?>
" border="0"></span>
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

                                    <?php if ($this->_tpl_vars['EDIT_DUPLICATE'] == 'permitted' || $this->_tpl_vars['is_permmited'] == true): ?>
										<button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
">
											<img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

										</button>
									<?php endif; ?>

									<?php if ($this->_tpl_vars['EDIT_DUPLICATE'] == 'permitted' || $this->_tpl_vars['is_permmited'] == true && $this->_tpl_vars['MODULE'] != 'Documents' && $this->_tpl_vars['MODULE'] != 'Products'): ?>
                                        
                                   		<button title="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_KEY']; ?>
" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; submitFormForAction('DetailView','EditView');" type="button" name="Duplicate" value="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_LABEL']; ?>
">
                                   			<img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_LABEL']; ?>

                                   		</button>
                                        
                                    <?php endif; ?>


									<?php if ($this->_tpl_vars['DELETE'] == 'permitted' && $this->_tpl_vars['MODULE'] != 'Products'): ?>	
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
" onclick="var obj = this;var lhref = getListOfRecords(obj, '<?php echo $this->_tpl_vars['MODULE']; ?>
',<?php echo $this->_tpl_vars['ID']; ?>
,'<?php echo $this->_tpl_vars['CATEGORY']; ?>
');" name="jumpBtnIdTop" id="jumpBtnIdTop" src="<?php echo aicrm_imageurl('rec_jump.png', $this->_tpl_vars['THEME']); ?>
" style="cursor: pointer;">
			                        <?php endif; ?>

			                        <?php if ($this->_tpl_vars['nextrecord'] != ''): ?>
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
						<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace" style="border-bottom:0px;">
						   <tr>
							<td align=left style="padding:10px;">
							<!-- content cache -->
								<form action="index.php" method="post" name="DetailView" id="form" onsubmit="VtigerJS_DialogBox.block();">
								<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'DetailViewHidden.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
								<!-- Entity informations display - starts -->
								<table border=0 cellspacing=0 cellpadding=0 width=100%>
			                			   <tr>
									<td style="padding:10px;border-right:1px dashed #CCCCCC;" width="80%">
								<!-- The following table is used to display the buttons -->
								<!-- Button displayed - finished-->
							 <?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => "./include/DetailViewBlockStatus.php", 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>


<!-- Entity information(blocks) display - start -->
<?php $_from = $this->_tpl_vars['BLOCKS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['header'] => $this->_tpl_vars['detail']):
?>
	<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
	   <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align=right>
		</td>
	   </tr>
	   <!-- This is added to display the existing comments -->
        <?php if ($this->_tpl_vars['header'] == $this->_tpl_vars['MOD']['LBL_COMMENTS'] || $this->_tpl_vars['header'] == $this->_tpl_vars['MOD']['LBL_COMMENT_INFORMATION'] || $this->_tpl_vars['header'] == 'Plan Information'): ?>
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
	   <?php if ($this->_tpl_vars['header'] != 'Comments' && $this->_tpl_vars['header'] != 'Comment Information'): ?>
	         <tr>
	         <?php echo '<td colspan=4 class="dvInnerHeader"><div style="float:left; font-weight:bold; margin-left: 10px;"><div style="float:left;"><a href="javascript:showHideStatus(\'tbl'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?><?php echo '\',\'aid'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?><?php echo '\',\''; ?><?php echo $this->_tpl_vars['IMAGE_PATH']; ?><?php echo '\');">'; ?><?php if ($this->_tpl_vars['BLOCKINITIALSTATUS'][$this->_tpl_vars['header']] == 1): ?><?php echo '<img id="aid'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?><?php echo '" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide" />'; ?><?php else: ?><?php echo '<img id="aid'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?><?php echo '" src="themes/softed/images/slidedown_b2.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Display" title="Display"/>'; ?><?php endif; ?><?php echo '</a></div><b style="font-family: PromptMedium; font-size: 12px;">&nbsp;&nbsp;&nbsp;'; ?><?php echo $this->_tpl_vars['header']; ?><?php echo '</b></div></td>'; ?>

	        </tr>
	    <?php endif; ?>
	</table>
	
	<?php if ($this->_tpl_vars['header'] != 'Comments' && $this->_tpl_vars['header'] != 'Comment Information'): ?>

		<?php if ($this->_tpl_vars['BLOCKINITIALSTATUS'][$this->_tpl_vars['header']] == 1): ?>
			<div style="width:auto;display:block;" id="tbl<?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?>
" >
		<?php else: ?>
			<div style="width:auto;display:none;" id="tbl<?php echo ((is_array($_tmp=$this->_tpl_vars['header'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '') : smarty_modifier_replace($_tmp, ' ', '')); ?>
" >
		<?php endif; ?>
			<table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">

		   	<?php $_from = $this->_tpl_vars['detail']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['detail']):
?>
		   		<tr style="height:25px">
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
					<?php $this->assign('keycntimage', $this->_tpl_vars['data']['cntimage']); ?>
					<?php $this->assign('keyadmin', $this->_tpl_vars['data']['isadmin']); ?>
		            <?php $this->assign('fielddatatype', $this->_tpl_vars['data']['fielddatatype']); ?>
		            
						<?php if ($this->_tpl_vars['label'] != ''): ?>
							<?php if ($this->_tpl_vars['keycntimage'] != ''): ?>
								<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value=<?php echo $this->_tpl_vars['keyadmin']; ?>
><?php echo $this->_tpl_vars['keycntimage']; ?>
</td>
							<?php elseif ($this->_tpl_vars['label'] != 'Tax Class'): ?><!-- Avoid to display the label Tax Class -->
								<?php if ($this->_tpl_vars['keyid'] == '71' || $this->_tpl_vars['keyid'] == '72'): ?>  <!--CurrencySymbol-->
									<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value=<?php echo $this->_tpl_vars['keyadmin']; ?>
><?php echo $this->_tpl_vars['label']; ?>
 (<?php echo $this->_tpl_vars['keycursymb']; ?>
)</td>
								<?php else: ?>
		                        	<?php if ($this->_tpl_vars['keyfldname'] == 'cf_4320'): ?>
		                                <td class="dvtCellLabel" align=right width=25%><font size=""><input type="hidden" id="hdtxt_IsAdmin" value=<?php echo $this->_tpl_vars['keyadmin']; ?>
><?php echo $this->_tpl_vars['label']; ?>
</font></td>
		                            <?php else: ?>
		                                <td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value=<?php echo $this->_tpl_vars['keyadmin']; ?>
><?php echo $this->_tpl_vars['label']; ?>
</td>
		                            <?php endif; ?>
								<?php endif; ?>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['EDIT_PERMISSION'] == 'yes' && $this->_tpl_vars['display_type'] != '2'): ?>
																<?php if (! empty ( $this->_tpl_vars['DETAILVIEW_AJAX_EDIT'] )): ?>
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
		    
		    <!-- <?php if ($this->_tpl_vars['header'] == 'Description Information' && $this->_tpl_vars['MODULE'] == 'Order'): ?>
		   		<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'modules/Order/log_change_status.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

		    <?php endif; ?> -->
			</table>
			</div> <!-- Line added by SAKTI on 10th Apr, 2008 -->
	<?php endif; ?>
	
<?php endforeach; endif; unset($_from); ?>
<!-- Entity information(blocks) display - ends -->

									<br>

										<!-- Product Details informations -->
										<?php echo $this->_tpl_vars['ASSOCIATED_PRODUCTS']; ?>

										</form>
									</td>
<!-- The following table is used to display the buttons -->
								<table border=0 cellspacing=0 cellpadding=0 width=100%>
			                			   <tr>
									<td style="padding:10px;border-right:1px dashed #CCCCCC;" width="80%">

		<table border=0 cellspacing=0 cellpadding=0 width=100%>
		  <tr>
			<td style="border-right:1px dashed #CCCCCC;" width="100%">
			<?php if ($this->_tpl_vars['SinglePane_View'] == 'true'): ?>
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'RelatedListNew.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php endif; ?>
		</td></tr></table>
</td></tr></table>
									<!-- Inventory Actions - ends -->
									<td width=22% valign=top style="padding:10px;">
										<!-- right side InventoryActions -->
										<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Projects/InventoryActions.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

										<br>
										<!-- To display the Tag Clouds -->
										<div>
                                        <?php if ($this->_tpl_vars['TAG_CLOUD_DISPLAY'] == 'true'): ?>

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
									                    <div id="tagfields"><?php echo $this->_tpl_vars['ALL_TAG']; ?>
</div>
									                </td>
									            </tr>
									            <tr >
									            	<td>
									            		<span style="color: #ff7488;font-size: 12px;line-height: 3;font-family:PromptMedium, serif; display: none" class="error-tag" id="error-tag"><img src="themes/softed/images/warning-duotone-red.png" style="width: 20px;height: 20px;vertical-align: middle;margin-right: 5px;" >จำนวนแท็กไม่เกิน 10 แท็ก / 1 รายการ</span>
									            	</td>
									            </tr>
									           
									        </table>
									        <!-- End Tag cloud display -->
                                        <?php endif; ?>
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

								<td class="dvtSelectedCellBottom" align=center nowrap><?php echo $this->_tpl_vars['APP'][$this->_tpl_vars['SINGLE_MOD']]; ?>
 <?php echo $this->_tpl_vars['APP']['LBL_INFORMATION']; ?>
</td>
								<!-- <td class="dvtTabCacheBottom" style="width:10px">&nbsp;</td> -->
								<?php if ($this->_tpl_vars['SinglePane_View'] == 'false'): ?>
								<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=CallRelatedList&module=<?php echo $this->_tpl_vars['MODULE']; ?>
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

                                    <?php if ($this->_tpl_vars['EDIT_DUPLICATE'] == 'permitted' || $this->_tpl_vars['is_permmited'] == true): ?>
										<button title="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_KEY']; ?>
" class="crmbutton small btnedit" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='<?php echo $this->_tpl_vars['ID']; ?>
';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; submitFormForAction('DetailView','EditView');" type="button" name="Edit" value="<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>
">
											<img src="themes/softed/images/massedit_w.png" border="0" style="width: 15px;height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_EDIT_BUTTON_LABEL']; ?>

										</button>
									<?php endif; ?>

									<?php if ($this->_tpl_vars['EDIT_DUPLICATE'] == 'permitted' || $this->_tpl_vars['is_permmited'] == true && $this->_tpl_vars['MODULE'] != 'Documents' && $this->_tpl_vars['MODULE'] != 'Products'): ?>
                                   		<button title="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_TITLE']; ?>
" accessKey="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_KEY']; ?>
" class="crmbutton small btnduplicate" onclick="DetailView.return_module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='<?php echo $this->_tpl_vars['MODULE']; ?>
'; submitFormForAction('DetailView','EditView');" type="button" name="Duplicate" value="<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_LABEL']; ?>
">
                                   			<img src="themes/softed/images/duplicate_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;<?php echo $this->_tpl_vars['APP']['LBL_DUPLICATE_BUTTON_LABEL']; ?>

                                   		</button>
                                    <?php endif; ?>

									<?php if ($this->_tpl_vars['DELETE'] == 'permitted' && $this->_tpl_vars['MODULE'] != 'Products'): ?>
							
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
					<!-- PUBLIC CONTENTS STOPS-->
					</td>
					<td align=right valign=top>
						<img src="<?php echo aicrm_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
">
					</td>
				   </tr>
				</table>
			</td>
		   </tr>
		</table>
		<!-- Contents - end -->

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

