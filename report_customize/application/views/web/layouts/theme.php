<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $template['title']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
  <?php echo $template['metadata']; ?>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo site_assets_url('img/DASClientIcon.ico'); ?>" />
  <link href="<?php  echo site_assets_url('css/bootstrap.css'); ?>" rel="stylesheet" />
  <link href="<?php   echo site_assets_url('css/font-awesome.min.css'); ?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/ionicons.min.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/datepicker/datepicker3.css');?>" rel="stylesheet" />
  <link href="<?php echo site_assets_url('css/icon.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/daterangepicker/daterangepicker-bs3.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/timepicker/bootstrap-timepicker.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/toggle-switch.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/morris/morris.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/colpick.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/bootstrap/easyui.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/AdminLTE.css');?>" rel="stylesheet" />
  <script src="<?php echo site_assets_url('js/jquery-1.8.3.min.js'); ?>"></script>
  <script src="<?php echo site_assets_url('js/jquery-1.10.1.min.js'); ?>"></script>

  <script src="<?php echo site_assets_url('js/jquery.easyui.min.js'); ?>"></script>
  <script src="<?php echo site_assets_url('js/datagrid-groupview.js'); ?>"></script>
  <script src="<?php echo site_assets_url('js/modernizr.js'); ?>"></script>



  <style>
  .no-js #loader { display: none;  }
  .js #loader { display: block; position: absolute; left: 100px; top: 0; }

  </style>

</head>
<body>




</body>



<script>
$(window).load(function() {

  $('#dg_member').datagrid('resize');

  // $('#dg_member').datagrid();

		// Animate loader off screen
		$(".se-pre-con").fadeOut("slow");


    SetFormatDate();
    PopupMemberName();


    // GetMemberList();


  });



function GetMemberList(accountnm = '')
{
  //console.log(accountnm);

  $('#dg_member').datagrid('load',{
    accountname: accountnm 
  });

}

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




function PopupMemberName(){

  $('#member_name').textbox({
    multiline:true,
    prompt: '',
    width: '100%',
    iconWidth: 22,
    icons: [{
      iconCls:'icon-add',
      handler: function(e){

        $('#dlg_member_name').dialog('open');

        //$(e.data.target).textbox('setValue', '');

      }
    },{
      iconCls:'icon-remove',
      handler: function(e){

        $('#member_id').val('');
        $(e.data.target).textbox('clear');
      }
    }]
    //buttonText:'Search',
    //iconCls:'icon-man',
    //iconAlign:'left'
  });


}

function formatDMY(val,row){
  if (!val || val=='')
   return '';
 var d = new Date(val);
 var str = (val.split(' '));
 var str_date =str[0];
 var ss = (str_date.split('-'));
 var y = parseInt(ss[0],10);
 var m = parseInt(ss[1],10);
 var d = parseInt(ss[2],10);
 var date_ddmmyy = d+"/"+m+"/"+y;
 
 return date_ddmmyy;

}

function formatDMYHHMM(val,row){
  if (!val || val=='')
   return '';
 var d = new Date(val);
 var str = (val.split(' '));
 var str_date =str[0];
 var str_time =str[1];
 var ss = (str_date.split('-'));
 var tt =  (str_time.split(':'));
 
 var y = parseInt(ss[0],10);
 var m = parseInt(ss[1],10);
 var d = parseInt(ss[2],10);
 var date_ddmmyy = d+"/"+m+"/"+y+' '+tt[0]+':'+tt[1];
 
 return date_ddmmyy;

}

function SetFormatDate()
{
      //set datebox ddmmyyyy
      $('.easyui-datebox').datebox({
        formatter: function(date) {
          var y = date.getFullYear();
          var m = date.getMonth() + 1;
          var d = date.getDate();
                //return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
                return (d < 10 ? ('0' + d) : d) + '/' + (m < 10 ? ('0' + m) : m) + '/' + y;
              },
              parser: function(s) {

                if (!s)
                  return new Date();
                var ss = s.split('/');
                var y = parseInt(ss[2], 10);
                var m = parseInt(ss[1], 10);
                var d = parseInt(ss[0], 10);
                if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
                    //return new Date(d, m - 1, y)
                    return new Date(y, m - 1, d);
                  } else {
                    return new Date();
                  }
                }

              });
    }

    

    </script>

    
    <body class="<? echo $this->session->userdata('user.theme');  ?>">
      <div class="se-pre-con"></div>

      <?php echo Modules::run('components/header/index');?>
      <div class="wrapper row-offcanvas row-offcanvas-left">
        <?php echo Modules::run('components/header/left_bar');?>
        
        <aside class="right-side">
          <section class="content-header">

           <?php echo $template['modulename']; ?> <i class="fa  fa-caret-right"></i> <?php echo $template['screen']; ?>
           
         </section>
         <section class="content">
          <?php echo $template['body']; ?>

        </section>
      </aside>
    </div>
  </section>

  
  <!--<script type="text/javascript" src="<?php  echo site_assets_url('js/jquery.min.js'); ?>" ></script>-->
  <script src="<?php  echo site_assets_url('js/jquery-ui-1.10.3.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php  echo site_assets_url('js/bootstrap.min.js')?>"></script>
  <script src="<?php echo site_assets_url('js/plugins/jqueryKnob/jquery.knob.js'); ?>"></script>
  <script src="<?php echo site_assets_url('js/plugins/daterangepicker/daterangepicker.js'); ?>"></script>
  <script src="<?php echo site_assets_url('js/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>      
  <script src="<?php echo site_assets_url('js/plugins/timepicker/bootstrap-timepicker.js'); ?>"></script>     
  
  <script src="<?php echo site_assets_url('js/plugins/iCheck/icheck.min.js'); ?>"></script>
  <script src="<?php echo site_assets_url('js/jquery.easyui.min.js'); ?>"></script>
  <script src="<?php echo site_assets_url('js/plugins/jqgrid/js/grid.locale-en.js'); ?>"></script>
  <script src="<?php echo site_assets_url('js/plugins/jqgrid/js/jquery.jqGrid.js'); ?>"></script> 
  <script src="<?php echo site_assets_url('js/colpick.js'); ?>"></script> 
  <script src="<?php echo site_assets_url('js/raphael-min.js'); ?>"></script>
  <script type="text/javascript" src="<?php  echo site_assets_url('js/plugins/morris/morris.min.js')?>"></script>
  <script src="<?php echo site_assets_url('js/piechart'); ?>"></script>
  <script type="text/javascript" src="<?php echo site_assets_url('js/datagrid-filter.js')?>"></script> 
  <script type="text/javascript" src="<?php echo site_assets_url('js/plugins/flot/jquery.flot.min.js')?>"></script> 
  <script type="text/javascript" src="<?php echo site_assets_url('js/plugins/flot/jquery.flot.pie.min.js')?>"></script> 
  <script type="text/javascript" src="<?php echo site_assets_url('js/plugins/canvas/canvasjs.min.js')?>"></script>      
  <script src="<?php echo site_assets_url('js/AdminLTE/app.js'); ?>"></script>
  <script src="<?php //echo site_assets_url('js/AdminLTE/dashboard.js'); ?>"></script>
  <script src="<?php  //echo site_assets_url('js/AdminLTE/demo.js'); ?>"></script>
  <script src="<?php  echo site_assets_url('js/custom_function.js'); ?>"></script>


  <script src="<?php  echo site_assets_url('Charts/FusionCharts.js'); ?>"></script>


  <script src="<?php echo site_assets_url('js/utilities.js?v=1'); ?>"></script>
  <script src="<?php echo site_assets_url('js/datagrid-scrollview.js'); ?>"></script>


  <div id="dlg_member_name" class="easyui-dialog" title="List Member Name" style="width:550px;height:450px;padding:10px"
  data-options="
  modal:true,
  closed: true,
  iconCls: 'icon-search',
  toolbar: '#dlg-toolbar',
  buttons: '#dlg-buttons'
  ">


  <table id="dg_member" class="easyui-datagrid" style="width:100%;height:100%"
  url="<?php echo site_url("components/GetMember")?>"
  data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
  autoRowHeight:true,pageSize:10">


<!--   <table id="dg_member" class="easyui-datagrid" style="width:100%;height:100%"
  url="<?php echo site_url("components/GetMember")?>"
  data-options="fitColumns:true,singleSelect:false, pagination:true, rownumbers:true,
  onDblClickRow: function(index,field,value){

  console.log(field.accountname);
  //  GetSelectMember(field.accountname);

},
onSelect : function(index,row){
//console.log(row.accountname);

// GetSelectMember(field.accountname);

}
"> -->


<thead data-options="frozen:true">
  <tr>
    <th data-options="field:'ck',checkbox:true"></th>
    <th field="accountid"  data-options="halign:'center' " hidden="true" >accountid</th>
    <th field="cf_2118" width="auto" data-options="halign:'center'" >Member Type</th>
    

  </tr>
</thead>

<thead>
  <th field="cf_2090" width="120px" data-options="halign:'center'" >Member Status</th>
  <th field="cf_3619" width="120px" data-options="halign:'center'">Tax Id</th>
  <th field="account_no" width:"200px"  data-options="halign:'center'" >Account No</th>
  <th field="accountname" width="350px" data-options="halign:'center'" >Account Name</th>

</thead>

</table>

</div>

<div id="dlg-toolbar" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectMember()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">
        <input id="sb_account_name" class="easyui-searchbox" data-options="prompt:'Account Name', searchbox:function(value){GetMemberList(value);}" style="width:200px;"></input>        
      </td>
    </tr>
  </table>
</div> 

</body>
</html>