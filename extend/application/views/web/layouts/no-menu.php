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
  
  <script src="<?php echo site_assets_url('js/utilities.js?v=' . date('ymdhis')); ?>"></script>
  

<style>
.no-js #loader { display: none;  }
.js #loader { display: block; position: absolute; left: 100px; top: 0; }
</style>

</head>
  <script>
$(window).load(function() {


  $('#dg_account').datagrid('resize');
 // $('#dg_sponsor').datagrid('resize');
    //Step1
    SetFormatDate();
    PopupAccountName();
    PopupdealerName();
    PopupdealerName2();
    PopupSubdealerName();
    PopupownerName();
    PopupconsultantName();
    PopuparchitectName();
    PopupconstructionName();
    PopupinteriorName();
    PopupmaincontName();
    PopupsubcontractorName();
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
    val_name += val_name == '' ? rows[i]['accountname'] : "\r\n"+ rows[i]['accountname'];
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
//Step2
/********************************/
function GetdealerList(val = '')
{
  $('#dg_dealer').datagrid('load',{
    accountname: val 
  });
}
function GetSelectDealer(val)
{
  var rows = $('#dg_dealer').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? rows[i]['accountname'] : "\r\n"+ rows[i]['accountname'];
  }

  $('#dealer_name').textbox('setValue',val_name);
  $('#dealer_id').val(val_id);
  $('#dlg_dealer').dialog('close');
}

function PopupdealerName(){
  $('#dealer_name').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_dealer').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#dealer_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function GetdealerList2(val = '')
{
  $('#dg_dealer2').datagrid('load',{
    accountname: val 
  });
}
function GetSelectDealer2(val)
{
  var rows = $('#dg_dealer2').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? rows[i]['accountname'] : "\r\n"+ rows[i]['accountname'];
  }

  $('#dealer_name2').textbox('setValue',val_name);
  $('#dealer_id2').val(val_id);
  $('#dlg_dealer2').dialog('close');
}

function PopupdealerName2(){
  $('#dealer_name2').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_dealer2').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#dealer_id2').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function GetsubdealerList(val = '')
{
  $('#dg_sub_dealer').datagrid('load',{
    accountname: val 
  });
}
function GetSelectSubDealer(val)
{
  var rows = $('#dg_sub_dealer').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? rows[i]['accountname'] : "\r\n"+ rows[i]['accountname'];
  }

  $('#sup_dealer_name').textbox('setValue',val_name);
  $('#sup_dealer_id').val(val_id);
  $('#dlg_sup_dealer').dialog('close');
}

function PopupSubdealerName(){
  $('#sup_dealer_name').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_sup_dealer').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#sup_dealer_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function GetownerList(val = '')
{
  $('#dg_owner').datagrid('load',{
    accountname: val 
  });
}
function GetSelectOwner(val)
{
  var rows = $('#dg_owner').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? rows[i]['accountname'] : "\r\n"+ rows[i]['accountname'];
  }

  $('#owner_name').textbox('setValue',val_name);
  $('#owner_id').val(val_id);
  $('#dlg_owner').dialog('close');
}

function PopupownerName(){
  $('#owner_name').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_owner').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#owner_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function GetdesignerList(val = '')
{
  $('#dg_designer').datagrid('load',{
    accountname: val 
  });
}
function GetSelectDesigner(val)
{
  var rows = $('#dg_designer').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? rows[i]['accountname'] : "\r\n"+ rows[i]['accountname'];
  }

  $('#designer_name').textbox('setValue',val_name);
  $('#designer_id').val(val_id);
  $('#dlg_designer').dialog('close');
}

function PopupdesignerName(){
  $('#designer_name').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_designer').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#designer_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/
/********************************/
function GetprojectsNameList(val = '')
{
  $('#dg_projects_name').datagrid('load',{
    projects_name: val 
  });
}
function GetSelectProjectsName(val)
{
  var rows = $('#dg_projects_name').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['projectsid'] : ","+rows[i]['projectsid'];
    val_name += val_name == '' ? rows[i]['projects_name'] : "\r\n"+ rows[i]['projects_name'];
  }

  $('#projects_name').textbox('setValue',val_name);
  $('#projectsid').val(val_id);
  $('#dlg_projects_name').dialog('close');
}

function PopupProjectsName(){
  $('#projects_name').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_projects_name').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#projectsid').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function Getprojects_noList(val = '')
{
  $('#dg_projects_no').datagrid('load',{
    projects_no: val 
  });
}
function GetSelectProjectsNo(val)
{
  var rows = $('#dg_projects_no').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['projectsid'] : ","+rows[i]['projectsid'];
    val_name += val_name == '' ? rows[i]['projects_no'] : "\r\n"+ rows[i]['projects_no'];
  }

  $('#projects_no').textbox('setValue',val_name);
  $('#projects_no_id').val(val_id);
  $('#dlg_projects_no').dialog('close');
}

function PopupProjectsNo(){
  $('#projects_no').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_projects_no').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#projects_no_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function GetconsultantList(val = '')
{
  $('#dg_consultant').datagrid('load',{
    accountname: val 
  });
}
function GetSelectConsultant(val)
{
  var rows = $('#dg_consultant').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? rows[i]['accountname'] : "\r\n"+ rows[i]['accountname'];
  }

  $('#consultant_name').textbox('setValue',val_name);
  $('#consultant_id').val(val_id);
  $('#dlg_consultant').dialog('close');
}

function PopupconsultantName(){
  $('#consultant_name').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_consultant').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#consultant_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function GetarchitectList(val = '')
{
  $('#dg_architect').datagrid('load',{
    accountname: val 
  });
}
function GetSelectArchitect(val)
{
  var rows = $('#dg_architect').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? rows[i]['accountname'] : "\r\n"+ rows[i]['accountname'];
  }

  $('#architect_name').textbox('setValue',val_name);
  $('#architect_id').val(val_id);
  $('#dlg_architect').dialog('close');
}

function PopuparchitectName(){
  $('#architect_name').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_architect').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#architect_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function GetconstructionList(val = '')
{
  $('#dg_construction').datagrid('load',{
    accountname: val 
  });
}
function GetSelectConstruction(val)
{
  var rows = $('#dg_construction').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? rows[i]['accountname'] : "\r\n"+ rows[i]['accountname'];
  }

  $('#construction_name').textbox('setValue',val_name);
  $('#construction_id').val(val_id);
  $('#dlg_construction').dialog('close');
}

function PopupconstructionName(){
  $('#construction_name').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_construction').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#construction_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function GetinteriorList(val = '')
{
  $('#dg_interior').datagrid('load',{
    accountname: val 
  });
}
function GetSelectInterior(val)
{
  var rows = $('#dg_interior').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? rows[i]['accountname'] : "\r\n"+ rows[i]['accountname'];
  }

  $('#interior_name').textbox('setValue',val_name);
  $('#interior_id').val(val_id);
  $('#dlg_interior').dialog('close');
}

function PopupinteriorName(){
  $('#interior_name').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_interior').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#interior_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function GetmaincontList(val = '')
{
  $('#dg_maincont').datagrid('load',{
    accountname: val 
  });
}
function GetSelectMainCont(val)
{
  var rows = $('#dg_maincont').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? rows[i]['accountname'] : "\r\n"+ rows[i]['accountname'];
  }

  $('#maincont_name').textbox('setValue',val_name);
  $('#maincont_id').val(val_id);
  $('#dlg_maincont').dialog('close');
}

function PopupmaincontName(){
  $('#maincont_name').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_maincont').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#maincont_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function GetcontractorList(val = '')
{
  $('#dg_contractor').datagrid('load',{
    accountname: val 
  });
}
function GetSelectContractor(val)
{
  var rows = $('#dg_contractor').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? rows[i]['accountname'] : "\r\n"+ rows[i]['accountname'];
  }

  $('#contractor_name').textbox('setValue',val_name);
  $('#contractor_id').val(val_id);
  $('#dlg_contractor').dialog('close');
}

function PopupcontractorName(){
  $('#contractor_name').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_contractor').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#contractor_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function GetsubcontractorList(val = '')
{
  $('#dg_subcontractor').datagrid('load',{
    accountname: val 
  });
}
function GetSelectSubcontractor(val)
{
  var rows = $('#dg_subcontractor').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['accountid'] : ","+rows[i]['accountid'];
    val_name += val_name == '' ? rows[i]['accountname'] : "\r\n"+ rows[i]['accountname'];
  }

  $('#subcontractor_name').textbox('setValue',val_name);
  $('#subcontractor_id').val(val_id);
  $('#dlg_subcontractor').dialog('close');
}

function PopupsubcontractorName(){
  $('#subcontractor_name').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_subcontractor').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#subcontractor_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function Getquote_no_fromList(val = '')
{
  $('#dg_quote_no_from').datagrid('load',{
    quote_no_from: val 
  });
}
function GetSelectQuoteNoFrom(val)
{
  var rows = $('#dg_quote_no_from').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['quoteid'] : ","+rows[i]['quoteid'];
    val_name += val_name == '' ? rows[i]['quote_no'] : "\r\n"+ rows[i]['quote'];
  }

  $('#quote_no_from').textbox('setValue',val_name);
  $('#quote_no_from_id').val(val_name);
  $('#dlg_quote_no_from').dialog('close');
}

function PopupQuoteNoFrom(){
  $('#quote_no_from').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_quote_no_from').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#quote_no_from_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/

/********************************/
function Getquote_no_toList(val = '')
{
  $('#dg_quote_no_to').datagrid('load',{
    quote_no_to: val 
  });
}
function GetSelectQuoteNoTo(val)
{
  var rows = $('#dg_quote_no_to').datagrid('getChecked');
  var val_id = '';
  var val_name = '';
  for (i in rows) {
    val_id += val_id == '' ? rows[i]['quoteid'] : ","+rows[i]['quoteid'];
    val_name += val_name == '' ? rows[i]['quote_no'] : "\r\n"+ rows[i]['quote_no'];
  }

  $('#quote_no_to').textbox('setValue',val_name);
  $('#quote_no_to_id').val(val_name);
  $('#dlg_quote_no_to').dialog('close');
}

function PopupQuoteNoTo(){
  $('#quote_no_to').textbox({
    multiline: false, // Ensure multiline is set to false for a single-line textbox
    prompt: '',
    width: '100%',
    iconWidth: 22,
    iconAlign: 'right',
    icons: [{
        iconCls: 'icon-add',
        handler: function(e) {
            $('#dlg_quote_no_to').dialog('open');
        }
    }, {
        iconCls: 'icon-remove',
        handler: function(e) {
            $('#quote_no_to_id').val('');
            $(e.data.target).textbox('clear');
        }
    }]
});


}
/********************************/
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
  <script src="<?php //echo site_assets_url('js/AdminLTE/dashboard.js'); ?>"></script>
  <script src="<?php  //echo site_assets_url('js/AdminLTE/demo.js'); ?>"></script>
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
    url="<?php echo site_url("index.php/components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>
       <!-- <th field="cf_2118" width="auto" data-options="halign:'center',align:'left'" >Account Status</th>-->
        

      </tr>
    </thead>

    <thead>
      <!--<th field="cf_2090" width="120px" data-options="halign:'center',align:'left'" >Account Type</th>-->
      <th field="account_no" width="200px"  data-options="halign:'center',align:'left'" >Account No</th>
      <th field="accountname" width="350px" data-options="halign:'center',align:'left'" >Account Name</th>
      <!-- <th field="cf_3619" width="120px" data-options="halign:'center'" hidden="true">Tax Id</th> -->

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




<!-- Step3 -->
<!------------------dealer------------------->
<div id="dlg_dealer" class="easyui-dialog" title="Dealer" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-dealer',
    buttons: '#dlg-buttons-dealer'
    ">


    <table id="dg_dealer" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>
      </tr>
    </thead>

    <thead>
      <th field="accountname" width="400px"  data-options="halign:'center',align:'left'" >Dealer Name</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-dealer" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectDealer()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_dealer_name" class="easyui-searchbox" data-options="prompt:'Issue Type',searcher:GetdealerList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------dealer----------------->

<!------------------dealer2------------------->
<div id="dlg_dealer2" class="easyui-dialog" title="Dealer" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-dealer2',
    buttons: '#dlg-buttons-dealer'
    ">


    <table id="dg_dealer2" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>
      </tr>
    </thead>

    <thead>
      <th field="accountname" width="400px"  data-options="halign:'center',align:'left'" >Dealer Name</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-dealer2" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectDealer2()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_dealer_name2" class="easyui-searchbox" data-options="prompt:'Issue Type',searcher:GetdealerList2" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------dealer2----------------->

<!------------------Sub Dealer------------------->
<div id="dlg_sup_dealer" class="easyui-dialog" title="Sub Dealer" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-sub-dealer',
    buttons: '#dlg-buttons-sub-dealer'
    ">


    <table id="dg_sub_dealer" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>
      </tr>
    </thead>

    <thead>
      <th field="accountname" width="400px"  data-options="halign:'center',align:'left'" >Sub Dealer Name</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-sub-dealer" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectSubDealer()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_sup_dealer_sales_rap" class="easyui-searchbox" data-options="prompt:'Issue Type',searcher:GetsubdealerList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------Sub Dealer----------------->

<!------------------owner------------------->
<div id="dlg_owner" class="easyui-dialog" title="Owner" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-owner',
    buttons: '#dlg-buttons-owner'
    ">


    <table id="dg_owner" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>
      </tr>
    </thead>

    <thead>
      <th field="accountname" width="400px"  data-options="halign:'center',align:'left'" >Owner Name</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-owner" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectOwner()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_owner_name" class="easyui-searchbox" data-options="prompt:'Owner Name',searcher:GetownerList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------owner----------------->

<!------------------designer------------------->
<div id="dlg_designer" class="easyui-dialog" title="Designer" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-designer',
    buttons: '#dlg-buttons-designer'
    ">


    <table id="dg_designer" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>
      </tr>
    </thead>

    <thead>
      <th field="accountname" width="400px"  data-options="halign:'center',align:'left'" >Designer Name</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-designer" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectDesigner()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_designer_name" class="easyui-searchbox" data-options="prompt:'Designer Name',searcher:GetdesignerList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------designer----------------->

<!------------------projects_name------------------->
<div id="dlg_projects_name" class="easyui-dialog" title="Project Number" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-projects_name',
    buttons: '#dlg-buttons-projects_name'
    ">


    <table id="dg_projects_name" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/ProjectsName")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="projectsid"  data-options="halign:'center',align:'center' " hidden="true" >projectsid</th>
      </tr>
    </thead>

    <thead>
      <th field="projects_name" width="400px"  data-options="halign:'center',align:'left'" >Project Name</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-projects_name" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectProjectsName()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_projects_name" class="easyui-searchbox" data-options="prompt:'Project Name',searcher:GetprojectsNameList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------projects_name----------------->

<!------------------projects_no------------------->
<div id="dlg_projects_no" class="easyui-dialog" title="Project Number" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-projects_no',
    buttons: '#dlg-buttons-projects_no'
    ">


    <table id="dg_projects_no" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/ProjectsNo")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="projectsid"  data-options="halign:'center',align:'center' " hidden="true" >projectsid</th>
      </tr>
    </thead>

    <thead>
      <th field="projects_no" width="400px"  data-options="halign:'center',align:'left'" >Project Number</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-projects_no" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectProjectsNo()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_projects_no" class="easyui-searchbox" data-options="prompt:'Project Number',searcher:Getprojects_noList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------projects_no----------------->

<!------------------consultant------------------->
<div id="dlg_consultant" class="easyui-dialog" title="Consultant" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-consultant',
    buttons: '#dlg-buttons-consultant'
    ">


    <table id="dg_consultant" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>
      </tr>
    </thead>

    <thead>
      <th field="accountname" width="400px"  data-options="halign:'center',align:'left'" >Consultant Name</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-consultant" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectConsultant()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_consultant_name" class="easyui-searchbox" data-options="prompt:'Consultant Name',searcher:GetconsultantList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------consultant----------------->

<!------------------architect------------------->
<div id="dlg_architect" class="easyui-dialog" title="Architect" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-architect',
    buttons: '#dlg-buttons-architect'
    ">


    <table id="dg_architect" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>
      </tr>
    </thead>

    <thead>
      <th field="accountname" width="400px"  data-options="halign:'center',align:'left'" >Architect Name</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-architect" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectArchitect()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_architect_name" class="easyui-searchbox" data-options="prompt:'Architect Name',searcher:GetarchitectList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------architect----------------->

<!------------------construction------------------->
<div id="dlg_construction" class="easyui-dialog" title="Construction" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-construction',
    buttons: '#dlg-buttons-construction'
    ">


    <table id="dg_construction" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>
      </tr>
    </thead>

    <thead>
      <th field="accountname" width="400px"  data-options="halign:'center',align:'left'" >Construction Name</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-construction" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectConstruction()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_construction_name" class="easyui-searchbox" data-options="prompt:'Construction Name',searcher:GetconstructionList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------construction----------------->

<!------------------interior------------------->
<div id="dlg_interior" class="easyui-dialog" title="Interior" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-interior',
    buttons: '#dlg-buttons-interior'
    ">


    <table id="dg_interior" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>
      </tr>
    </thead>

    <thead>
      <th field="accountname" width="400px"  data-options="halign:'center',align:'left'" >Interior Name</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-interior" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectInterior()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_interior_name" class="easyui-searchbox" data-options="prompt:'Interior Name',searcher:GetinteriorList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------interior----------------->

<!------------------maincont------------------->
<div id="dlg_maincont" class="easyui-dialog" title="MainCont" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-maincont',
    buttons: '#dlg-buttons-maincont'
    ">


    <table id="dg_maincont" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>
      </tr>
    </thead>

    <thead>
      <th field="accountname" width="400px"  data-options="halign:'center',align:'left'" >MainCont Name</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-maincont" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectMainCont()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_maincont_name" class="easyui-searchbox" data-options="prompt:'MainCont Name',searcher:GetmaincontList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------maincont----------------->

<!------------------contractor------------------->
<div id="dlg_contractor" class="easyui-dialog" title="Contractor" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-contractor',
    buttons: '#dlg-buttons-contractor'
    ">


    <table id="dg_contractor" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>
      </tr>
    </thead>

    <thead>
      <th field="accountname" width="400px"  data-options="halign:'center',align:'left'" >Contractor Name</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-contractor" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectContractor()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_contractor_name" class="easyui-searchbox" data-options="prompt:'Contractor Name',searcher:GetcontractorList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------contractor----------------->

<!------------------subcontractor------------------->
<div id="dlg_subcontractor" class="easyui-dialog" title="Subcontractor" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-subcontractor',
    buttons: '#dlg-buttons-subcontractor'
    ">


    <table id="dg_subcontractor" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/GetAccount")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="accountid"  data-options="halign:'center',align:'center' " hidden="true" >accountid</th>
      </tr>
    </thead>

    <thead>
      <th field="accountname" width="400px"  data-options="halign:'center',align:'left'" >Subcontractor Name</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-subcontractor" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectSubcontractor()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_subcontractor_name" class="easyui-searchbox" data-options="prompt:'Subcontractor Name',searcher:GetsubcontractorList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------subcontractor----------------->

<!------------------quote_no_from------------------->
<div id="dlg_quote_no_from" class="easyui-dialog" title="Quote Number" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-quote_no_from',
    buttons: '#dlg-buttons-quote_no_from'
    ">


    <table id="dg_quote_no_from" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/QuoteNo")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="quoteid"  data-options="halign:'center',align:'center' " hidden="true" >quoteid</th>
      </tr>
    </thead>

    <thead>
      <th field="quote_no" width="400px"  data-options="halign:'center',align:'left'" >Quote Number</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-quote_no_from" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectQuoteNoFrom()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_quote_no_from" class="easyui-searchbox" data-options="prompt:'Quote Number',searcher:Getquote_no_fromList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------quote_no_from----------------->
<!------------------quote_no_to------------------->
<div id="dlg_quote_no_to" class="easyui-dialog" title="Quote Number" style="width:550px;height:450px;padding:10px"
    data-options="
    modal:true,
    closed: true,
    iconCls: 'icon-search',
    toolbar: '#dlg-toolbar-quote_no_to',
    buttons: '#dlg-buttons-quote_no_to'
    ">


    <table id="dg_quote_no_to" class="easyui-datagrid" style="width:100%;height:100%"
    url="<?php echo site_url("index.php/components/QuoteNo")?>"
    data-options="view:scrollview,rownumbers:true,singleSelect:false,fitColumns:false,
    autoRowHeight:true,pageSize:10">




    <thead data-options="frozen:true">
      <tr>
        <th data-options="field:'ck',checkbox:true"></th>
        <th field="quoteid"  data-options="halign:'center',align:'center' " hidden="true" >quoteid</th>
      </tr>
    </thead>

    <thead>
      <th field="quote_no" width="400px"  data-options="halign:'center',align:'left'" >Quote Number</th>

    </thead>

  </table>

</div>
<div id="dlg-toolbar-quote_no_to" style="padding-bottom: 5px; padding-top: 5px; padding-left: 10px;">
  <table cellpadding="0" cellspacing="0" style="width:100%">
    <tr>
      <td style="padding-left:0px; width:20%;text-align:center">
        <a href="javascript:GetSelectQuoteNoTo()" class="easyui-linkbutton" data-options="iconCls:'icon-ok',plain:true" style="width:50px;">OK</a>
      </td>
      <td style="width:80%">

       <input id="sb_quote_no_to" class="easyui-searchbox" data-options="prompt:'Quote Number',searcher:Getquote_no_toList" style="width:200px"></input>

     </td>
   </tr>
 </table>
</div>
<!--------------------quote_no_to----------------->
</body>
</html>