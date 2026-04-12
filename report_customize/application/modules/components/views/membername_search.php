


<div id="dlg_member_name" class="easyui-dialog" title="List Member Name" style="width:450px;height:450px;padding:10px"
data-options="
iconCls: 'icon-search',
toolbar: '#dlg-toolbar',
buttons: '#dlg-buttons'
">
<table id="dg_member" class="easyui-datagrid" style="width:100%;height:100%"
url="http://localhost:8080/tmao/report_customize/example/getMember"
data-options="fitColumns:true,singleSelect:false, pagination:true, rownumbers:true,
onDblClickRow: function(index,field,value){

console.log(field.accountname);
//  GetSelectMember(field.accountname);

},
onSelect : function(index,row){
//console.log(row.accountname);

// GetSelectMember(field.accountname);

}
">


<!-- url="http://localhost:8080/tmao/report_customize/example/getMember" -->
<thead>
  <tr>
    <th data-options="field:'ck',checkbox:true"></th>
    <th field="accountid"  data-options="halign:'center' " hidden="true" >accountid</th>
    <th field="cf_2118" width="auto" data-options="halign:'center'" >cf_2118</th>
    <th field="cf_2090" width="auto" data-options="halign:'center'" >cf_2090</th>
    <th field="cf_3619" width="auto" data-options="halign:'center'" >cf_3619</th>

    <th field="account_no" width:"auto"  data-options="halign:'center'" >Account No</th>
    <th field="accountname" width="auto" data-options="halign:'center'" >Account Name</th>

  </tr>
</thead>

</table>

</div>

<div id="dlg-toolbar" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:2px">
        <a href="javascript:GetSelectMember()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:5%">OK</a>
        <input id="sb_account_name" class="easyui-searchbox" data-options="prompt:'Account Name'" style="width:15%"></input>        
      </td>

    </tr>
  </table>
</div>



<script>
$(document ).ready(function()  {



 });



function GetSelectMember(val)
{

  var rows = $('#dg_member').datagrid('getChecked');

  var acc_id = '';
  var acc_name = '';
  for (i in rows) {
    acc_id += acc_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    acc_name += acc_name == '' ? rows[i]['accountname'] : ","+rows[i]['accountname'];
  }


  $('#member_name').textbox('setValue',acc_name);
  $('#member_id').val(acc_id);

  $('#dlg_member_name').dialog('close');
}

function PopupMember()
{
  $('#sb_account_name').searchbox('clear');

 // GetMemberList();

 $('#dlg_member_name').dialog('open');

}

function GetMemberList(accountnm = '')
{

  $('#dg_member').datagrid('load',{
    accountname: accountnm 
  });


}

  </script>