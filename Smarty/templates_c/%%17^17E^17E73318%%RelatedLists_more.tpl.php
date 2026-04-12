<?php /* Smarty version 2.6.18, created on 2026-04-08 16:53:26
         compiled from RelatedLists_more.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'aicrm_imageurl', 'RelatedLists_more.tpl', 228, false),)), $this); ?>
<script language="JavaScript" type="text/javascript" src="include/js/ListView.js"></script>

<?php echo '
<script>
function editProductListPrice(id,pbid,price)
{
        $("status").style.display="inline";
        new Ajax.Request(
                \'index.php\',
                {queue: {position: \'end\', scope: \'command\'},
                        method: \'post\',
                        postBody: \'action=ProductsAjax&file=EditListPrice&return_action=CallRelatedList&return_module=PriceBooks&module=Products&record=\'+id+\'&pricebook_id=\'+pbid+\'&listprice=\'+price,
                        onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("editlistprice").innerHTML= response.responseText;
                        }
                }
        );
}

function gotoUpdateListPrice(id,pbid,proid)
{
        $("status").style.display="inline";
        $("roleLay").style.display = "none";
        var listprice=$("list_price").value;
                new Ajax.Request(
                        \'index.php\',
                        {queue: {position: \'end\', scope: \'command\'},
                                method: \'post\',
                                postBody: \'module=Products&action=ProductsAjax&file=UpdateListPrice&ajax=true&return_action=CallRelatedList&return_module=PriceBooks&record=\'+id+\'&pricebook_id=\'+pbid+\'&product_id=\'+proid+\'&list_price=\'+listprice,
                                onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("RLContents").innerHTML= response.responseText;
                                }
                        }
                );
}
'; ?>


function loadCvList(type,id)
{//alert($("account_cv_list").value);
        if($("lead_cv_list").value != 'None')
        {

		$("status").style.display="inline";
        	if(type === 'Leads')
        	{
                        new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=Campaigns&action=CampaignsAjax&file=LoadList&ajax=true&return_action=DetailView&return_id='+id+'&list_type='+type+'&cvid='+$("lead_cv_list").value,
                                onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("RLContents").innerHTML= response.responseText;
                                }
                        }
                	);
        	}
			if(type === 'Accounts')
        	{
                        new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=Campaigns&action=CampaignsAjax&file=LoadList&ajax=true&return_action=DetailView&return_id='+id+'&list_type='+type+'&cvid='+$("account_cv_list").value,
                                onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("RLContents").innerHTML= response.responseText;
                                }
                        }
                	);
        	}
        	if(type === 'Contacts')
        	{
                        new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=Campaigns&action=CampaignsAjax&file=LoadList&ajax=true&return_action=DetailView&return_id='+id+'&list_type='+type+'&cvid='+$("cont_cv_list").value,
                                onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("RLContents").innerHTML= response.responseText;
                                }
                        }
                	);
			}


        }
}

function loadCvList1(type,id)
{
        if($("cont_cv_list").value != 'None' || $("account_cv_list").value != 'None')
        {
		$("status").style.display="inline";
		
		if(type === 'Accounts')
        	{
                        new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=EmailTargetList&action=EmailTargetListAjax&file=LoadList&ajax=true&return_action=DetailView&return_id='+id+'&list_type='+type+'&cvid='+$("account_cv_list").value,
                                onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("RLContents").innerHTML= response.responseText;
                                }
                        }
                	);
        	}

        	if(type === 'Contacts')
        	{
                        new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=EmailTargetList&action=EmailTargetListAjax&file=LoadList&ajax=true&return_action=DetailView&return_id='+id+'&list_type='+type+'&cvid='+$("cont_cv_list").value,
                                onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("RLContents").innerHTML= response.responseText;
                                }
                        }
                	);
		}
        }
}


function loadCvList2(type,id)
{
        if($("account_cv_list").value != 'None')
        {
		$("status").style.display="inline";
		
		if(type === 'Accounts')
        	{
                        new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=Campaigns&action=CampaignsAjax&file=LoadList&ajax=true&return_action=DetailView&return_id='+id+'&list_type='+type+'&cvid='+$("account_cv_list").value,
                                onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("RLContents").innerHTML= response.responseText;
                                }
                        }
                	);
        	}
        }
}

function loadCvList_SMS(type,id)
{
        if($("cont_cv_list").value != 'None' || $("account_cv_list").value != 'None' || $("opp_cv_list").value != 'None' )
        {
		$("status").style.display="inline";
		
                if(type === 'Opportunity')
        	{
                        new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=SmartSms&action=SmartSmsAjax&file=LoadList&ajax=true&return_action=DetailView&return_id='+id+'&list_type='+type+'&cvid='+$("opp_cv_list").value,
                                onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("RLContents").innerHTML= response.responseText;
                                }
                        }
                	);
        	}
		

		if(type === 'Accounts')
        	{
                        new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=SmartSms&action=SmartSmsAjax&file=LoadList&ajax=true&return_action=DetailView&return_id='+id+'&list_type='+type+'&cvid='+$("account_cv_list").value,
                                onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("RLContents").innerHTML= response.responseText;
                                }
                        }
                	);
        	}

        	if(type === 'Contacts')
        	{
                        new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=SmartSms&action=SmartSmsAjax&file=LoadList&ajax=true&return_action=DetailView&return_id='+id+'&list_type='+type+'&cvid='+$("cont_cv_list").value,
                                onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("RLContents").innerHTML= response.responseText;
                                }
                        }
                	);
		}

        }
}
</script>

<!-- Contents -->

<div id="editlistprice" style="position:absolute;width:300px;"></div>
<table border=0 cellspacing=0 cellpadding=0 width=100% align=center>
<tr>
	<td valign=top><img src="<?php echo aicrm_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>
	<td class="showPanelBg" valign=top width=100%>

			<!-- Account details tabs -->
			<table border=0 cellspacing=0 cellpadding=0 width=100% align=center>
			<tr>
				<td valign=top align=left >
		                	<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace" style="border-bottom:0;">
						<tr>
							<td align=left>
							<!-- content cache -->
								<table border=0 cellspacing=0 cellpadding=0 width=100%>
									<tr>
										<td style="padding:10px">
										   <!-- General details -->
												<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'RelatedListsHidden.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
												<input type="hidden" name="return_action" value="DetailView">
												<div id="RLContents">
					                             <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'RelatedListContents.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
           						          </div>
												</form>
										  										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
				</table>

	<!-- PUBLIC CONTENTS STOPS-->
	</td>
	<td align=right valign=top><img src="<?php echo aicrm_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
"></td>
</tr>
</table>

<?php if ($this->_tpl_vars['MODULE'] == 'Leads' || $this->_tpl_vars['MODULE'] == 'Contacts' || $this->_tpl_vars['MODULE'] == 'Accounts' || $this->_tpl_vars['MODULE'] == 'Campaigns'): ?>
<form name="SendMail" onsubmit="VtigerJS_DialogBox.block();"><div id="sendmail_cont" style="z-index:100001;position:absolute;width:300px;"></div></form>
<?php endif; ?>

<script>
function OpenWindow(url)
{
	openPopUp('xAttachFile',this,url,'attachfileWin',380,375,'menubar=no,toolbar=no,location=no,status=no,resizable=no');
}
</script>