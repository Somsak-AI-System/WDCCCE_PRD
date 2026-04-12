<?php /* Smarty version 2.6.18, created on 2026-04-09 16:31:39
         compiled from UserListView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'aicrm_imageurl', 'UserListView.tpl', 27, false),)), $this); ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>

<script type="text/javascript" src="asset/js/jquery.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="asset/js/datagrid-filter.js"></script>

<link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css">
<link rel="stylesheet" type="text/css" href="asset/css/metro-blue/linkbutton.css">
<link rel="stylesheet" type="text/css" href="asset/css/icon.css">

<br>
<div id="dialog" style="display:none;">Dialog Content.</div>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="<?php echo aicrm_imageurl('showPanelTopLeft.gif', $this->_tpl_vars['THEME']); ?>
"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
<form action="index.php" method="post" name="EditView" id="form" onsubmit="VtigerJS_DialogBox.block();">
<input type='hidden' name='module' value='Users'>
<input type='hidden' name='action' value='EditView'>
<input type='hidden' name='return_action' value='ListView'>
<input type='hidden' name='return_module' value='Users'>
<input type='hidden' name='parenttab' value='Settings'>

        <br>

	<div align=center>
    
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'SetMenu.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				<!-- DISPLAY -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="<?php echo aicrm_imageurl('ico-users.gif', $this->_tpl_vars['THEME']); ?>
" alt="<?php echo $this->_tpl_vars['MOD']['LBL_USERS']; ?>
" width="48" height="48" border=0 title="<?php echo $this->_tpl_vars['MOD']['LBL_USERS']; ?>
"></td>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings"><?php echo $this->_tpl_vars['MOD']['LBL_SETTINGS']; ?>
</a>  > <?php echo $this->_tpl_vars['MOD']['LBL_USERS']; ?>
</b></td>
				</tr>
				<tr>
					<td valign=top class="small"><?php echo $this->_tpl_vars['MOD']['LBL_USER_DESCRIPTION']; ?>
</td>
				</tr>
				</table>
				
				<br>
				<table border=0 cellspacing=0 cellpadding=10 width=100% >
				<tr>
				<td>
					<div id="ListViewContents">
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "UserListViewContents.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					</div>	
				</td>
				</tr>
				</table>
			
			
			
			</td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
		
	</div>

</td>
        <td valign="top"><img src="<?php echo aicrm_imageurl('showPanelTopRight.gif', $this->_tpl_vars['THEME']); ?>
"></td>
   </tr>
</tbody>
</form>
</table>

<div id="tempdiv" style="display:block;position:absolute;left:350px;top:200px;"></div>
<?php echo '
<script>
function getListViewEntries_js(module,url)
{
        $("status").style.display="inline";
        new Ajax.Request(
                \'index.php\',
                {queue: {position: \'end\', scope: \'command\'},
                        method: \'post\',
                        postBody: \'module=Users&action=UsersAjax&file=ListView&ajax=true&\'+url,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                $("ListViewContents").innerHTML= response.responseText;
                        }
                }
        );
}

function newUser(){
	document.getElementById("form").submit();
	//document.form.submit();
}

function deleteUser(userid)
{
	
	msg = \' \';
		url = \'delete_user.php?record=\'+userid;
			$(\'#dialog\').window({
				title: msg,
				width: 380,
				height: 190,
				closed: false,
				cache: false,
				href: url,
				modal: true
			 });
}
/*
function deleteUser(obj,userid)
{
        $("status").style.display="inline";
        new Ajax.Request(
                \'index.php\',
                {queue: {position: \'end\', scope: \'command\'},
                        method: \'post\',
                        postBody: \'action=UsersAjax&file=UserDeleteStep1&return_action=ListView&return_module=Users&module=Users&parenttab=Settings&record=\'+userid,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                $("tempdiv").innerHTML= response.responseText;
				fnvshobj(obj,"tempdiv");
                        }
                }
        );
}*/
/*function transferUser(del_userid)
{
        $("status").style.display="inline";
        $("DeleteLay").style.display="none";
        var trans_userid=$(\'transfer_user_id\').options[$(\'transfer_user_id\').options.selectedIndex].value;
        new Ajax.Request(
                \'index.php\',
                {queue: {position: \'end\', scope: \'command\'},
                        method: \'post\',
                        postBody: \'module=Users&action=UsersAjax&file=DeleteUser&ajax=true&delete_user_id=\'+del_userid+\'&transfer_user_id=\'+trans_userid,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                $("ListViewContents").innerHTML= response.responseText;
                        }
                }
        );

}*/

$(function(){
	
	 var url = "ajax_user.php";
		$.ajax(url, {
			type: \'POST\',
			dataType: \'json\',
			data: \'\',
			success: function (data) {
				FilterDataGrid(data);
		   },
			error: function (msg) {
			   $.messager.progress(\'close\');
			}
		
		});
});

function FilterDataGrid(data){
	
	//User Login
	var user_id =  data.user_login ;
	
	var dg = $(\'#dg\').datagrid({
				filterBtnIconCls:\'icon-filter\',
				contentType: "application/json; charset=utf-8",
				reloadFooter:true,
				pagination: true,
				rownumbers: true,
				remoteSort: false,
				showFooter:true,
				fitColumns:false,
			//	pageList: [10,20,30,40],
				dataType: \'json\',			  
				
				frozenColumns:[[
				{field: \'tool\', title: \'Tools\', align: \'left\', width: \'5%\',disableFilter:true ,
				 formatter: function (value, row, index )
                        {
                            var a =\'<a href="index.php?action=EditView&return_action=ListView&return_module=Users&module=Users&parenttab=Settings&record=\'+row.id+\'"><img src="themes/softed/images/editfield.gif" alt="Edit" title="Edit" border="0"></a>\';
							
							if(user_id != row.id){
								var b =\'<img src="themes/softed/images/delete.gif" onclick="deleteUser(\'+row.id+\')" border="0"  alt="Delete" title="Delete" style="cursor:pointer;"/>\';
							}else{
								var b = \'\';
							}
							var c =\'<a href="index.php?action=EditView&return_action=ListView&return_module=Users&module=Users&parenttab=Settings&record=\'+row.id+\'&isDuplicate=true"><img src="themes/softed/images/settingsActBtnDuplicate.gif" alt="Duplicate" title="Duplicate" border="0"></a>\';
							var text = a+b+c;
							return text;
                        }
				},
				{field: \'user_name\', title: \'User Name\',  align: \'left\', width: \'10%\', sortable:true,
				 formatter: function (value, row, index )
                        {
                            var a =\'<b><a href="index.php?module=Users&action=DetailView&parenttab=Settings&record=\'+row.id+\'"> \'+row.user_name+\' </a></b><br/>\';
							/*var b =\'<a href="index.php?module=Users&action=DetailView&parenttab=Settings&record=\'+row.id+\'">\'+row.first_name+\' \'+row.last_name+\'</a>\';
							var c =\' <a href="index.php?action=RoleDetailView&module=Settings&parenttab=Settings&roleid=\'+row.roleid+\'">(\'+row.rolename+\')</a>\';
							var text = a+b+c;*/
							return a;
                        }
				},
				{field: \'first_name\', title: \'Name\',  align: \'left\', width: \'20%\', sortable:true,
				 formatter: function (value, row, index )
                        {
							var a =\'<a href="index.php?module=Users&action=DetailView&parenttab=Settings&record=\'+row.id+\'">\'+row.first_name+\' \'+row.last_name+\'</a>\';
							return a;
                        }
				},
				]],
				columns: [[ //Un Fix Columns 
				{field: \'rolename\', title: \'Role\',  align: \'left\', width: \'20%\', sortable:true,
				 formatter: function (value, row, index )
                        {
							var a =\' <a href="index.php?action=RoleDetailView&module=Settings&parenttab=Settings&roleid=\'+row.roleid+\'">(\'+row.rolename+\')</a>\';
							return a;
                        }
				},
				{field: \'email1\', title: \'Email\',  align: \'left\', width: \'15%\', sortable:true ,
					formatter: function (value, row, index )
                        {
							var email1 = "\'email1\'";
							var Users = "\'Users\'";
							var record_id = "\'record_id\'";
                            var a =\'<a href="javascript:InternalMailer(\'+row.id+\',460,\'+email1+\',\'+Users+\',\'+record_id+\');">\'+row.email1+\'</a>\';
							return a;
                        }
				},
				{field: \'phone_mobile\', title: \'Mobile\',  align: \'left\', width: \'7%\', sortable:true},
				{field: \'position\', title: \'Position\',  align: \'left\', width: \'7%\', sortable:true},
				{field: \'section\', title: \'Section\',  align: \'left\', width: \'6%\', sortable:true},
				{field: \'is_admin\', title: \'Admin\', align: \'left\', width: \'4%\', sortable:true},
				{field: \'status\', title: \'Status\', align: \'left\', width: \'5%\', sortable:true}
           	 ]]
			});
		
			var dg = $(\'#dg\').datagrid({ 
			   pagination: true,
			   pageSize: 20,
			   pageList: [20,30,50,100]
			   });
		   	dg.datagrid(\'enableFilter\');
  			$(\'#dg\').datagrid(\'loadData\',data.rows);
			//Load Data
	//		dg.datagrid(\'loadData\', data.rows); 
			$("[name=\'tool\']").css("display", "none");	
}

</script>
'; ?>


