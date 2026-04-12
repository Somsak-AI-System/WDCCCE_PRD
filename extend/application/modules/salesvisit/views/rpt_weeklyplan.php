<?php global $site_URL;  ?>
<form id="searchForm" class="easyui-form" method="POST" name="searchForm">
<div class="row">
  <div class="col-lg-12">
    <!-- <div  id="layout" class="easyui-layout" style="width:100%;height:680px"> -->
   	<div id="layout" class="easyui-layout" style="width:100%;height:760px">
      <div data-options="region:'north',split:true" title="Filter"  style="width:100%; height:280px;">

        <div style="clear:both; height:8px; "></div>

        <div class="col-xs-2" style="text-align:right;">
          <label for="menu">Sales</label>
        </div>
        <div class="col-xs-4">
          <input class="easyui-textbox sales" id="sales" name="sales"  style="width:100%; height:25px;"  >
        </div>

		<input type="hidden"  id="area" name="area"  style="width:100%; height:25px;"  >
		<input type="hidden"  id="department" name="department"  style="width:100%; height:25px;"  >
        <div class="col-xs-2" style="text-align:right">
          <label for="menu">Account Name</label>
        </div>
        <div class="col-xs-4">
          <input name="account_id" id="account_id" type="hidden">  
          <input id="account_name" class="easyui-textbox account_name" style="width:'100%';height:80px" >
        </div>

		 <div style="clear:both; height:8px; "></div>     

     	<div class="col-xs-2" style="text-align:right;">
        <label for="menu">Daily Report</label>
        </div> 
        <div class="col-xs-4">
        <input class="easyui-textbox daily" id="daily" name="daily"  style="width:100%; height:25px;"  />
        </div>
            
        <div class="col-xs-2" style="text-align:right">
        <label for="menu">Objective</label>
        </div>
        <div class="col-xs-4">
          <input class="easyui-textbox objective easyui-validatebox" data-options="prompt:'ALL Objective'" id="objective" name="objective"  style="width:100%; height:25px;" />
        </div>

		<div style="clear:both; height:8px; "></div>
        <div class="col-xs-2" style="text-align:right;">
            <label for="menu">Sales Team</label>
        </div>
        <div class="col-xs-4">
            <input class="easyui-textbox sales_team easyui-validatebox" data-options="required:true" id="sales_team" name="sales_team" style="width:100%; height:25px;">
        </div>
       

        <div style="clear:both; height:8px; "></div>   

        <div class="col-xs-2" style="text-align:right;">
           <label for="menu">From</label>
        </div>
        <div class="col-xs-4">
          <input type="text" class="easyui-datebox" id="date_from" name="date_from" value="<? echo $date_from ?>"  style="height:25pt; width:60%;" />
        </div>

        <div class="col-xs-2" style="text-align:right">
          <label for="menu">To</label>
        </div>
        <div class="col-xs-4">
          <input name="member_id" id="member_id" type="hidden">  
          <input type="text" class="easyui-datebox" id="date_to" name="date_to" value="<? echo $date_to ?>"  style="height:25pt; width:60%;" />
        </div>

        <div style="clear:both; height:8px; "></div>  

        <div class="col-xs-6" style="text-align:center">
          <a href="#" class="easyui-linkbutton" onclick="Filter()" ><i class="fa fa-search"></i> Search</a>   
        </div>

		<?php /*
        <div class="col-xs-6" style="text-align:center">
          <a href="#" class="easyui-linkbutton" onclick="Send_report()" ><i class="fa fa-envelope"></i> Send Report</a> 
        </div>
      
		<?php if(!empty($role_user)){?>
            <div style="clear:both; height:8px; "></div> 
            
            <div class="col-xs-6" style="text-align:center"></div>
            <div class="col-xs-6" style="text-align:center">
              <a href="#" class="easyui-linkbutton" onclick="Send_daily_manual()" ><i class="fa fa-envelope"></i>Send Daily Report Manual</a>   
            </div>
        <?php } ?>
		*/?>
        
        </div>
        
      	<div data-options="region:'center',title:'',iconCls:'icon-ok'" id="report-result">
      	<iframe  id="birt-result" style="padding:0 0 0 0;min-height:500px;"  frameborder="0"  width="100%" height="auto" >
		
		</iframe>
      </div>
    </div> 
    <div id="chart1div" ></div>
  </div>
</div>
</form>


<!--Open Send Report -->
<div id="dialog"  title="Send Report"  style="width:850px;height:600px; margin-top:10px;">	         
        <form id="sendForm" class="easyui-form" method="POST" name="sendForm" >      
    	   <div style="clear:both; height:8px; "></div>
               <div class="col-xs-2" style="text-align:right;">
                  <label for="menu">Sales</label>
                </div>
                <div class="col-xs-4">
                  <input class="easyui-textbox sales_report easyui-validatebox" data-options="required:true" id="sales_report" name="sales_report"  style="width:250px; height:25px;"  >
                </div>
               
                <div class="col-xs-2" style="text-align:right;">
                <label for="menu">Daily Report</label>
                </div> 
    
                <div class="col-xs-4">
                <input class="easyui-textbox daily_report" id="daily_report" data-options="required:true" name="daily_report"  style="width:250px; height:25px; display:none"  >
                </div>              
                
                
        		<div style="clear:both; height:8px; "></div>
                 
                <div class="col-xs-2" style="text-align:right;">
                   <label for="menu">From</label>
                </div>
                <div class="col-xs-4">
                  <input type="text" class="easyui-datebox" id="date_from_report" name="date_from_report" value="<? echo $date_from ?>"  style="height:25pt; width:150px;background-color:#999"  readonly="readonly"  />
                </div>
        
                <div class="col-xs-2" style="text-align:right">
                  <label for="menu">To</label>
                </div>
                <div class="col-xs-4">
                  <input name="member_id" id="member_id" type="hidden">  
                  <input type="text" class="easyui-datebox" id="date_to_report" name="date_to_report" value="<? echo $date_to ?>"  style="height:25pt; width:150px;background-color:#999"" readonly="readonly" />
                </div>
        
                <div style="clear:both; height:8px; "></div>  
        		<div class="col-xs-2" style="text-align:right;">
                  <label for="menu">Send To</label>
                </div>
                <div class="col-xs-4">
                  <input class="easyui-textbox send_to easyui-validatebox" data-options="required:true" id="send_to_user" name="send_to_user"  style="width:200px; height:25px;" />
                </div>
                
                <div style="clear:both; height:8px; "></div>
                
                <div class="col-xs-12" style="text-align:center">
                  <a href="#" class="easyui-linkbutton" onclick="Send(this)" onkeydown="if(event.keyCode==13) return false;"><i class="fa fa-envelope"></i> Send</a>
                  <a href="#" class="easyui-linkbutton" onclick="cancel_report()" ><i class="fa fa-cancel"></i> Cancel</a>   
                </div>
        	
        </form>

</div>
<!--Close Send Report -->

<!--Open Daliy Report Muanual-->
<div id="dialog_daily"  title="Send Report"  style="width:850px; height:100px;">	
         
        <form id="sendForm_daily" class="easyui-form" method="POST" name="sendForm_daily" >      
     		<div style="clear:both; height:8px; "></div>
                                            
                <div class="col-xs-2" style="text-align:right;">
                  <label for="menu">Weekly Plan</label>
                </div> 
                <div class="col-xs-4">
                  <input class="easyui-textbox daily_report" id="daily_report_admin" data-options="required:true" name="daily_report_admin"  style="width:250px; height:25px; display:none" >
                </div>
                
                 <div class="col-xs-2" style="text-align:right;">
                  <label for="menu">Section</label>
                </div> 
                <div class="col-xs-4">
                  <input class="easyui-textbox section" id="section_daily" data-options="required:true" name="section_daily"  style="width:250px; height:25px; display:none"  >
                </div>
                
                <div style="clear:both; height:8px; "></div>
                                 
                <div class="col-xs-2" style="text-align:right;">
                   <label for="menu">From</label>
                </div>
                <div class="col-xs-4">
                  <input type="text" class="easyui-datebox" id="date_from_report_admin" name="date_from_report_admin" value="<? echo $date_from ?>"  style="height:25pt; width:150px;background-color:#999"  readonly="readonly"  />
                </div>
        
                <div class="col-xs-2" style="text-align:right">
                  <label for="menu">To</label>
                </div>
                <div class="col-xs-4">
                  <input name="member_id" id="member_id" type="hidden">  
                  <input type="text" class="easyui-datebox" id="date_to_report_admin" name="date_to_report_admin" value="<? echo $date_to ?>"  style="height:25pt; width:150px;background-color:#999"" readonly="readonly" />
                </div>
                
                <div style="clear:both; height:8px; "></div>
                
                <div class="col-xs-12" style="text-align:center">
                  <a href="#" class="easyui-linkbutton" onclick="Send_daily(this)" onkeydown="if(event.keyCode==13) return false;"><i class="fa fa-envelope"></i> Send</a>
                  <a href="#" class="easyui-linkbutton" onclick="cancel_report_daily()" ><i class="fa fa-cancel"></i> Cancel</a>   
                </div>
        </form>
</div>
<!--Close Daliy Report Muanual-->

<script>
$(document ).ready(function()  {
  /* on load ref file assets/jf/utilities.js */
  $("#dialog").css("display","none");
  $("#dialog_daily").css("display","none");
  Get_Sale_send();
  Get_Department();
  Get_Sales();
  Get_Sales_team();
  Get_Sale_send_new();
  $('.row').resize(function () {
    $('#dg').datagrid('resize');
    $('#layout').layout('resize');
  });

  $('#sales').combogrid({
		'value': '<?php echo USERID?>'
	});
	
	$('#sales_report').combogrid({
		'value': '<?php echo USERID?>',
	});
	
	Get_Daily();
    Getactivitytype_report();
	Get_section('<?php echo $section;?>');
	
	$('#daily').combogrid({
		onSelect: function(index,row){
			var weekly_start_date = row.weekly_start_date;
			var weekly_end_date = row.weekly_end_date;
			var w_start_date = weekly_start_date.split('-');
			var start_date = w_start_date[2]+'/'+w_start_date[1]+'/'+w_start_date[0] ;
			var w_end_date = weekly_end_date.split('-');
			var end_date = w_end_date[2]+'/'+w_end_date[1]+'/'+w_end_date[0] ;

			$('#date_from').datebox('setValue', start_date);	
			$('#date_to').datebox('setValue', end_date);	
		}
	});
	
	$('#daily_report').combogrid({
		onSelect: function(index,row){
			var weekly_start_date = row.weekly_start_date;
			var weekly_end_date = row.weekly_end_date;
		
			var w_start_date = weekly_start_date.split('-');
			var start_date = w_start_date[2]+'/'+w_start_date[1]+'/'+w_start_date[0] ;
			
			var w_end_date = weekly_end_date.split('-');
			var end_date = w_end_date[2]+'/'+w_end_date[1]+'/'+w_end_date[0] ;
		
			$('#date_from_report').datebox('setValue', start_date);	
			$('#date_to_report').datebox('setValue', end_date);	
		}
	});
	
	$('#daily_report_admin').combogrid({
		onSelect: function(index,row){
			var weekly_start_date = row.weekly_start_date;
			var weekly_end_date = row.weekly_end_date;
		
			var w_start_date = weekly_start_date.split('-');
			var start_date = w_start_date[2]+'/'+w_start_date[1]+'/'+w_start_date[0] ;
			
			var w_end_date = weekly_end_date.split('-');
			var end_date = w_end_date[2]+'/'+w_end_date[1]+'/'+w_end_date[0] ;
		
			$('#date_from_report_admin').datebox('setValue', start_date);	
			$('#date_to_report_admin').datebox('setValue', end_date);	
		}
	});
	
	$('#sales_report').combogrid({
		onSelect: function(index,row){
			var userid = row.id;
			 Getsendtofromuser(userid);
		}
	});
	
	Getsendtofromuser('<?php echo USERID?>');
	
	Getdailyplan_send();
	
});

function checkvalid()
{
	if($("#sales").combogrid('getValues') == ''){
		 $('#sales').next().find('input').focus();
		 return false;	
	 }else{
		return true;
	 }
}

function Filter() {

	var form_data = $('#searchForm').serialize();
	
	var returnvalid = checkvalid();

	if(returnvalid===false){

		return false;
	}

	// var userid =  $("#sales").combogrid('getValues');
	var userid =  $("#sales").combogrid('getValues');
	userid.join(',');

	var salesGrid = $('#sales').combogrid('grid');	// get datagrid object
	var salesRow = salesGrid.datagrid('getSelected');	// get the selected row

	var username = salesRow.first_name + ' ' + salesRow.last_name;

	var area = salesRow.area ;
	var department = salesRow.section;
	var accountid =  $("#account_id").val();
	var description = $("#objective").combogrid('getValues');     
	var date_start = $("#date_from").datebox('getValue');
	var due_date= $("#date_to").datebox('getValue');
	var datestartSplit = date_start.split('/');
	var sendDateStart = datestartSplit[2] + '-' + datestartSplit[1] + '-' + datestartSplit[0];	
	var datestopSplit = due_date.split('/');
	var sendDateStop = datestopSplit[2] + '-' + datestopSplit[1] + '-' + datestopSplit[0];
	var roleid =  $("#sales_team").combogrid('getValues');
            roleid.join(',');
			
	var url = '<?php echo REPORT_VIEWER_URL;?>rpt_daily_report.rptdesign&__showtitle=false&userid='+userid
	+'&area='+area+'&accountid='+accountid+'&department='+department+'&description='+description
	+'&date_start='+sendDateStart+'&due_date='+sendDateStop+'&username='+username+'&roleid='+roleid;
	LoadURL(  url,"birt-result" );

}

function cancel_report(){
	$('#dialog').dialog('close')
}

function cancel_report_daily(){
	$('#dialog_daily').dialog('close')
}

function Send_report(){
		$('#dialog').window({
		    width: 900,
		    height: 250,
			position: 'center',
    		resizable: false,
		    closed: false,
		    cache: false,
		    modal: true,
		});	
		$("#dialog").css("display","block");
}

function Send_daily_manual(){
	var msg = 'Send Report';
		$('#dialog_daily').window({
		    title: msg,
		    width: 900,
		    height: 300,
			position: 'center',
    		resizable: false,
		    closed: false,
		    cache: false,
		    modal: true,
		});	
		$("#dialog_daily").css("display","block");
}

function checkval_send()
{
	if($("#sales_report").combogrid('getValue') == ''){
		 $('#sales_report').next().find('input').focus();
		 return false;
	 }else if($("#daily_report").combogrid('getValue') == '' ){
		 $('#daily_report').next().find('input').focus();
		 return false;
	 }else if($("#send_to_user").combogrid('getValues') == ''){
		 $('#send_to_user').next().find('input').focus();
		 return false;
	 }else{
		return true;
	 }
}

function Send(send){
    $(send).prop("onclick", null).off("click");
	$.messager.progress();
    var form_data = $('#sendForm').serialize();
	
	var returnvalid = checkval_send();

	if(returnvalid===false){
		$.messager.progress('close');
		$(send).click(function(){
              Send(send);
        });
		return false;
	}
	
	var url = '<?php echo site_url("index.php/salesvisit/sendmail_report")?>';

	var salesGrid = $('#sales_report').combogrid('grid');	// get datagrid object
	var salesRow = salesGrid.datagrid('getSelected');	// get the selected row
	var username = salesRow.first_name + ' ' + salesRow.last_name;

	var area = salesRow.area ;
	var department = salesRow.section;	
	    
	var plan = '';
	plan = $("#daily_report").combogrid('getValue');
	
	var daily_report= $('#daily_report').combogrid('grid');	// get datagrid object
	var daily = daily_report.datagrid('getSelected');	// get the selected row
	var year = daily.weekly_year;
	
	 var form_data = {
		userid :  $("#sales_report").combogrid('getValue'),
		area : area,
		year : year,
		department : department,      
		report_plan : plan,
		date_start : $("#date_from_report").datebox('getValue'),
		due_date : $("#date_to_report").datebox('getValue'),
		objective : '',
		submodule : 'weeklyreport',
		send_to: $("#send_to_user").combogrid('getValues'),
		username : username
	 };
		
	 $.ajax(url, {
	  type: 'POST',
	  dataType: 'json',
	  data: form_data,
	  success: function (data) {
		  var obj = data;
	      $.messager.progress('close');	
		  if (obj.status==true){
			var errMsg =  obj.msg + " " +obj.error ;
		  }else{
			  if(obj.error!=''){
				  var errMsg =  obj.msg +" error: "+ obj.error;
			  }else{
				  var errMsg =  obj.msg +" "+ obj.error;
			  }		
		  }
		  $.messager.alert('Info', errMsg, 'info', function(){
			if(obj.status==true){
					window.close();
			}
			else{
					console.log(obj);
			}
		  });
          $(send).click(function(){
              Send(send);
          });
	 },
	 error: function (data) {
	  $.messager.progress('close');
	  $.messager.alert('Retrieve data', data, 'error');
	}
	});
}

function Send_daily(send){

	if($("#daily_report_admin").combogrid('getValue') == ''){
		 $('#daily_report_admin').next().find('input').focus();
		 return false;
	}

    $(send).prop("onclick", null).off("click");
	$.messager.progress();
	var url = '<?php echo $site_URL."_Auto_Script/send_email_Weely_Plan_Weekly_Report.php" ?>';

	var date_from = $("#date_from_report_admin").combogrid('getValue');
	var datestartSplit = date_from.split('/');
	var sendDateStart = datestartSplit[2] + '-' + datestartSplit[1] + '-' + datestartSplit[0];	
	var section = $("#section_daily").combogrid('getValue');
	
	var form_data = {
		runtype:'2',
		section:section,
		date_time:sendDateStart
	 };
	 
	 $.ajax(url, {
	  type: 'POST',
	  dataType: 'json',
	  data: form_data,
	  success: function (data) {
		  var obj = data;
	      $.messager.progress('close');	
		  if (obj.status==true){
			var errMsg =  obj.msg + " " +obj.error ;
		  }else{
			  if(obj.error!=''){
				  var errMsg =  obj.msg +" error: "+ obj.error;
			  }else{
				  var errMsg =  obj.msg +" "+ obj.error;
			  }		
		  }
		  $.messager.alert('Info', errMsg, 'info', function(){
			if(obj.status==true){
				window.close();
			}
			else{
				console.log(obj);
			}
		  });
          
          $(send).click(function(){
              Send_daily(send);
          });

	 },
	 error: function (data) {
	  $.messager.progress('close');
	  $.messager.alert('Retrieve data', data, 'error');
	}
	});
	
}
</script>