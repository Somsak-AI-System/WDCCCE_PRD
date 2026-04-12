<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $template['title']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
<?php echo $template['metadata']; ?>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo site_assets_url('favicon.ico'); ?>" />
  <link href="<?php  echo site_assets_url('css/bootstrap.css'); ?>" rel="stylesheet" />
  <link href="<?php   echo site_assets_url('css/font-awesome.min.css'); ?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/ionicons.min.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/datepicker/datepicker3.css');?>" rel="stylesheet" />
  <link href="<?php echo site_assets_url('css/icon.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/daterangepicker/daterangepicker-bs3.css');?>" rel="stylesheet" />


  <link href="<?php  echo site_assets_url('css/timepicker/bootstrap-timepicker.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/toggle-switch.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/colpick.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/bootstrap/easyui.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/AdminLTE.css');?>" rel="stylesheet" />

  <script src="<?php echo site_assets_url('js/jquery-1.10.1.min.js'); ?>"></script>
 
  <script src="<?php echo site_assets_url('js/jquery-ui-1.10.4.custom.js'); ?>"></script>
  <script src="<?php echo site_assets_url('js/jquery-ui-1.10.4.custom.min.js'); ?>"></script>
  <script src="<?php echo site_assets_url('js/jquery.easyui.min.js'); ?>"></script>
  <script src="<?php echo site_assets_url('js/datagrid-groupview.js'); ?>"></script>
  <script src="<?php echo site_assets_url('js/modernizr.js'); ?>"></script>
  
  <script src="<?php echo site_assets_url('js/utilities.js'); ?>"></script>
  

<style>
.no-js #loader { display: none;  }
.js #loader { display: block; position: absolute; left: 100px; top: 0; }
</style>

</head>
  <script>
$(window).load(function() {


  $('#dg_account').datagrid('resize');
 // $('#dg_sponsor').datagrid('resize');

    SetFormatDate();
    PopupAccountName();
    //PopupSponsorName();

    // Animate loader off screen
    $(".se-pre-con").fadeOut("slow");





  });



function GetSponsorList(val){
     console.log(val);
    $('#dg_sponsor').datagrid('load',{
    sponsor_name: val 
  });
}

function GetSelectSponsor(val)
{
  var rows = $('#dg_sponsor').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? rows[i]['accountname'] : ","+rows[i]['accountname'];
  }

  $('#sponsor_name').textbox('setValue',val_name);
  $('#sponsor_id').val(val_id);
  $('#dlg_sponsor_name').dialog('close');
}

function PopupSponsorName(){
  $('#sponsor_name').textbox({
    multiline:true,
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign:'right',
    //buttonText:'Search',
    // iconCls:'icon-man',
    icons: [{
      iconCls:'icon-add',
      handler: function(e){

        $('#dlg_sponsor_name').dialog('open');
      }
    },{
      iconCls:'icon-remove',
      handler: function(e){
        $('#sponsor_id').val('');
        $(e.data.target).textbox('clear');
      }
    }]
  });

}










function GetAccountList(val = '')
{
  $('#dg_account').datagrid('load',{
    accountname: val 
  });
}

function GetSelectAccount(val)
{
  var rows = $('#dg_account').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? (parseInt(i)+1)+"."+ rows[i]['accountname'] : "\r\n"+(parseInt(i)+1)+"." + rows[i]['accountname'];
  }

  $('#account_name').textbox('setValue',val_name);
  $('#account_id').val(val_id);
  $('#dlg_account_name').dialog('close');
}



function PopupAccountName(){
  $('#account_name').textbox({
    multiline:true,
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign:'right',
    //buttonText:'Search',
    // iconCls:'icon-man',
    icons: [{
      iconCls:'icon-add',
      handler: function(e){

        $('#dlg_account_name').dialog('open');
      }
    },{
      iconCls:'icon-remove',
      handler: function(e){
        $('#account_id').val('');
        $(e.data.target).textbox('clear');
      }
    }]
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

function SetFormatDate(){
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


function LoadURL(URL,divID) {
	var http = false;

	 if(navigator.appName == "Microsoft Internet Explorer") {
	   http = new ActiveXObject("Microsoft.XMLHTTP");

	 }

	else {
     http = new XMLHttpRequest();
   }

    http.abort();

      http.open("GET", URL, true);
      http.onreadystatechange=function() {
        if(http.readyState == 4) {
          document.getElementById(divID).src = URL;
	        }

      }
      http.send(null);
    }

</script>

 
<body class="<? echo $this->session->userdata('user.theme');  ?>">
<div class="se-pre-con"></div>

  <div class="wrapper row-offcanvas row-offcanvas-left">
      <section class="content">
      <?php echo $template['body']; ?>

      </section>
    </div>
</section>

 
   
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
  <script src="<?php echo site_assets_url('js/piechart.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo site_assets_url('js/datagrid-filter.js')?>"></script> 
  <script type="text/javascript" src="<?php echo site_assets_url('js/plugins/flot/jquery.flot.min.js')?>"></script> 
  <script type="text/javascript" src="<?php echo site_assets_url('js/plugins/flot/jquery.flot.pie.min.js')?>"></script> 
  <script type="text/javascript" src="<?php echo site_assets_url('js/plugins/canvas/canvasjs.min.js')?>"></script>      
  <script src="<?php echo site_assets_url('js/AdminLTE/app.js'); ?>"></script>
  <script src="<?php  echo site_assets_url('js/custom_function.js'); ?>"></script>




  <script src="<?php  echo site_assets_url('Charts/FusionCharts.js'); ?>"></script>
  <!--<script src="<?php echo site_assets_url('js/utilities.js'); ?>"></script>-->
  
  <script src="<?php echo site_assets_url('js/datagrid-scrollview.js'); ?>"></script>

   <script src="<?php echo site_assets_url('js/datagrid-detailview.js'); ?>"></script>
	  <script src="<?php echo site_assets_url('js/jquery.table2excel.js'); ?>"></script>
	
	<script src="<?php echo site_assets_url('js/accounting.js'); ?>"></script>

    <div id="dlg_account_name" class="easyui-dialog" title="List Account Name" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar',
    buttons: '#dlg-buttons'
    ">


    <table id="dg_account" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>        
      </tr>
    </thead>

    <thead>
      <th field="account_no" width="200px"  data-options="halign:'center',align:'left'" >Account No</th>
      <th field="accountname" width="350px" data-options="halign:'center',align:'left'" >Account Name</th>
      <th field="cf_3619" width="120px" data-options="halign:'center'" hidden="true">Tax Id</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectAccount()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_account_name" class="easyui-searchbox" data-options="prompt:'Account Name',searcher:GetAccountList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>

</body>
</html>