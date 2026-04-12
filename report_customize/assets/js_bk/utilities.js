
var paht = location.protocol + "//" + window.location.host + "/" + location.pathname.split('/')[1] + "/report_customize/components";
//on load
// GetActivitytype();
// GetBussinesstype();
// GetMemberstatus();
// GetPaymenttype();
// GetPaymentstatus();
// GetOrganizationsize();
// GetMembertype();
// GetContacttype();
// GetSponsor();
// GetMemberlevel();
// GetSupporttype();

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function ExportToExcel(filename){

  var dg = $('#dg').datagrid('getData');
  console.log(dg);
  console.log(dg.total);
 if(dg.total > 0)
 {
   $.messager.progress({
        title:'Please waiting',
        msg:'Loading data...',
        text:'LOADING'
    });


    var datetimenow = moment().format('YYYYMMDD_HHMMSS');
     $(".table2excel").table2excel({
        exclude: ".noExl",
        name: filename,
        filename: filename+'_'+datetimenow,
        fileext: ".xls",
        exclude_img: false,
        exclude_links: false,
        exclude_inputs: false
      });

      $.messager.progress('close');
 }
 else
 {
        $.messager.alert('Alert', 'ไม่พบข้อมูลที่จะ Export', 'info');
 }



}

function GetBranch()
{

  $('.branchid').combogrid({
	   panelWidth:550,
	   idField: 'branchid',
	   textField: 'branch_name',
	   mode:'remote',
	   fitColumns:true,  
	   multiple: false,   
	   url: paht+'/GetBranch',

	   columns: [[
	   {field:'ck',checkbox:true},
	   {field:'projects_code',title:'RMS Code',width:120},
	   {field:'branch_name',title:'Name',width:250},
	   {field:'cf_3777',title:'Project Type',width:180},
	
	   ]]
	
  });  

}

function GetActivitytype()
{

  $('.activity_type').combogrid({
   panelWidth:280,
   idField: 'actvitytypecode',
   textField: 'actvitytypename',
   mode:'remote',
   fitColumns:true,  
   multiple: true,   
   url: paht+'/GetActivitytype',

   columns: [[
   {field:'ck',checkbox:true},
   {field:'actvitytypecode',title:'ID',width:120},
   {field:'actvitytypename',title:'Name',width:180},

   ]],
   onChange: function(index,row){

    GetEventname(index);
    GetEventtype(index);
    GetSubeventtype(index);

  }

});  

}


function GetEventname(eventcd)
{

  var param = "";

  if(eventcd != "")
  {
    eventcd.forEach(function(val){
      param == "" ? param += "?activitytype="+val : param += "," + val;
    });
  }

   //console.log(param);

  //get member type
  $('.event_id').combogrid({
    panelWidth:450,
    idField: 'eventid',
    textField: 'eventname',
    mode:'remote',
    multiple: true, 
    fitColumns:true,                     
    url: paht+'/GetEventname'+param,

    columns: [[
    {field:'ck',checkbox:true},
    {field:'activitytype',title:'Type',align:'center', halign:'center',width:'20%'},
    {field:'eventid',title:'ID',align:'center', halign:'center',width:'20%'},
    {field:'eventname',title:'Name',align:'center', halign:'center',width:'60%'},                   
    ]]                                
  });             

}

function GetEventtype(eventcd)
{

  var param = "";

  if(eventcd != "")
  {
    eventcd.forEach(function(val){
      param == "" ? param += "?activitytype="+val : param += "," + val;
    });
  }

   //console.log(param);

  $('.event_type').combogrid({
    panelWidth:450,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: true, 
    fitColumns:true,                     
    url: paht+'/GetEventtype'+param,

    columns: [[
    {field:'ck',checkbox:true},
    {field:'activitytype',title:'Type',align:'center', halign:'center',width:'20%'},
    {field:'eventtypecode',title:'Code',align:'center', halign:'center',width:'20%'},
    {field:'eventtypename',title:'Name',align:'left', halign:'center',width:'60%'},                            
    ]]                                
  });             

}

function GetSubeventtype(eventcd)
{


  var param = "";

  if(eventcd != "")
  {
    eventcd.forEach(function(val){
      param == "" ? param += "?activitytype="+val : param += "," + val;
    });
  }

   //console.log(param);
  $('.sub_event_type').combogrid({
    panelWidth:450,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: true, 
    fitColumns:true,                     
    url: paht+'/GetSubeventtype'+param,

    columns: [[
    {field:'ck',checkbox:true},   
    {field:'activitytype',title:'Type',align:'center', halign:'center',width:'20%'},
    {field:'eventtypecode',title:'Code',align:'center', halign:'center',width:'20%'},
    {field:'eventtypename',title:'Name',align:'left', halign:'center',width:'60%'},                        
    ]]                                
  });             

}


function GetMemberstatus()
{
   //get member type
   $('.member_status').combogrid({
    panelWidth:350,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: true, 
    fitColumns:true,                     
    url: paht+'/GetMemberstatus',

    columns: [[
    {field:'ck',checkbox:true},
    {field:'eventtypecode',title:'Code',width:100},
    {field:'eventtypename',title:'Name',width:250},                            
    ]]                                
  });    
 }

 function Get_Reporttype()
{
   //get Report type
   $('.report_type').combogrid({
    panelWidth:350,
    idField: 'reportcode',
    textField: 'reportname',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/GetReporttype',

    columns: [[
    {field:'reportcode',title:'Code',width:100},
    {field:'reportname',title:'Name',width:250},                            
    ]]                                
  }); 
  // {field:'ck',checkbox:true},   
 }
 
 function Get_Reporttype_send()
{
  $('.report_type_model').combogrid({
    panelWidth:350,
    idField: 'reportcode',
    textField: 'reportname',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/GetReporttype',

    columns: [[
    {field:'reportcode',title:'Code',width:100},
    {field:'reportname',title:'Name',width:250},                            
    ]]                                
  }); 
}

  function Getactivitytype_report()
{
   //get Report type
   $('.objective').combogrid({
    panelWidth:350,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/Getactivitytype',
	
    columns: [[
   /* {field:'eventtypecode',title:'Code',width:100},*/
    {field:'eventtypename',title:'Name',width:350},                            
    ]]                                
  }); 
  // {field:'ck',checkbox:true},   
 }
 
  function Getactivitytype_report_send()
{
   //get Report type
   $('.objective_report').combogrid({
    panelWidth:350,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/Getactivitytype',
	
    columns: [[
   /* {field:'eventtypecode',title:'Code',width:100},*/
    {field:'eventtypename',title:'Name',width:350},                            
    ]]                                
  }); 
  // {field:'ck',checkbox:true},   
 }

 function Get_Department()
{
   //get Report type
   $('.department').combogrid({
    panelWidth:350,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/GetDepartment',

    columns: [[
    {field:'eventtypecode',title:'Code',width:100},
    {field:'eventtypename',title:'Name',width:250},                            
    ]]                                
  }); 
  // {field:'ck',checkbox:true},   
 }

 function Get_Sale()
{
  
   //get Report type
   $('.sales').combogrid({
    panelWidth:350,
    idField: 'id',
    textField: 'user_name',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/GetSale',

    columns: [[
    {field:'id',title:'ID',width:100, hidden:true},
    {field:'user_name',title:'Username',width:250, hidden:true},
    {field:'first_name',title:'Firstname',width:250, hidden:true},
    {field:'last_name',title:'Lastname',width:250, hidden:true},
    {field:'sale_name',title:'Salename',width:250},                            
    ]]                                
  }); 
  // {field:'ck',checkbox:true},   
 }

 function Get_ServiceName()
{
  
   //get Report type
   $('.servicename').combogrid({
    panelWidth:350,
    idField: 'id',
    textField: 'user_name',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/GetServiceName',

    columns: [[
    {field:'id',title:'ID',width:100, hidden:true},
    {field:'section',title:'section',width:100, hidden:true},
    {field:'user_name',title:'Username',width:250, hidden:true},
    {field:'first_name',title:'Firstname',width:250, hidden:true},
    {field:'last_name',title:'Lastname',width:250, hidden:true},
    {field:'sale_name',title:'Salename',width:250},
                                
    ]]                                
  }); 
  // {field:'ck',checkbox:true},   
 }


 function Get_Sale_send()
{
   //get Report type
   $('.sales_report').combogrid({
    panelWidth:250,
    idField: 'id',
    textField: 'user_name',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/GetSale',

    columns: [[
    {field:'id',title:'ID',width:100, hidden:true},
    {field:'user_name',title:'Username',width:250, hidden:true},
    {field:'first_name',title:'Firstname',width:250, hidden:true},
    {field:'last_name',title:'Lastname',width:250, hidden:true},
    {field:'sale_name',title:'Salename',width:250},                            
    ]]                                
  }); 
  // {field:'ck',checkbox:true},   
 }
 
 function Get_section(section)
{
   $('.section').combogrid({
    panelWidth:350,
    idField: 'section',
    textField: 'section',
    mode:'remote',
	value: section,
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/Getsection',
    columns: [[
    	{field:'section',title:'Section',width:250},                            
    ]]                                
  }); 
 }
 
 
function Getsendtofromuser(id){

	var url = paht+'/Get_send_to';
	 var form_data = {
		userid : id
	 };
	
	 $.ajax(url, {
	  type: 'POST',
	  dataType: 'JSON',
	  data: form_data,
		 success: function (data) {
			if (data.send_user_id !== false){
				Get_Sale_sendto(data.send_user_id ,id,data.section);		
			 }else{
			 	Get_Sale_sendto('',id,data.section);
			 }
		 },
		 error: function (data) {
		  $.messager.alert('Retrieve data', data, 'error');
		}
		});
	
}

 function Get_Sale_sendto(user_send_to,id,section)
{
   //get Report type
   $('.send_to').combogrid({
    panelWidth:250,
    idField: 'id',
    textField: 'user_name',
    mode:'remote',
	//value: ['1','19204'],
	value: user_send_to,
    multiple: true,
	queryParams:{
		id:id,
		section:section
	  }, 
    fitColumns:true,                     
    url: paht+'/Get_Sale_section/',

	
    columns: [[
	{field:'ck',checkbox:true},
    {field:'id',title:'ID',width:100, hidden:true},
    {field:'user_name',title:'Username',width:250, hidden:true},
    {field:'first_name',title:'Firstname',width:250, hidden:true},
    {field:'last_name',title:'Lastname',width:250, hidden:true},
    {field:'sale_name',title:'Salename',width:250},                            
    ]]                                
  }); 
  // {field:'ck',checkbox:true},   
 }


 function GetBussinesstype()
 {
  
   //get member type
   $('.business_type').combogrid({
    panelWidth:350,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: true, 
    fitColumns:true,                     
    url: paht+'/GetBussinesstype',

    columns: [[
    {field:'ck',checkbox:true},
    {field:'eventtypecode',title:'Code',width:100},
    {field:'eventtypename',title:'Name',width:250},                            
    ]]                                
  });    
 }


function GetPaymenttype()
 {
  
   $('.payment_type').combogrid({
    panelWidth:350,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: true, 
    fitColumns:true,                     
    url: paht+'/GetPaymenttype',

    columns: [[
    {field:'ck',checkbox:true},
    {field:'eventtypecode',title:'Code',width:100},
    {field:'eventtypename',title:'Name',width:250},                            
    ]]                                
  });    
 }

 function GetPaymentstatus()
 {

   $('.payment_status').combogrid({
    panelWidth:350,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: true, 
    fitColumns:true,                     
    url: paht+'/GetPaymentstatus',

    columns: [[
    {field:'ck',checkbox:true},
    {field:'eventtypecode',title:'Code',width:100},
    {field:'eventtypename',title:'Name',width:250},                            
    ]]                                
  });    
 }

 function GetOrganizationsize()
 {
   $('.organization_size').combogrid({
    panelWidth:350,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: true, 
    fitColumns:true,                     
    url: paht+'/GetOrganizationsize',

    columns: [[
    {field:'ck',checkbox:true},
    {field:'eventtypecode',title:'Code',width:100},
    {field:'eventtypename',title:'Name',width:250},                            
    ]]                                
  });    
 }

function GetMembertype()
 {
  
   //get member type
   $('.member_type').combogrid({
    panelWidth:350,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: true, 
    fitColumns:true,                     
    url: paht+'/GetMembertype',

    columns: [[
    {field:'ck',checkbox:true},
    {field:'eventtypecode',title:'Code',width:100},
    {field:'eventtypename',title:'Name',width:250},                            
    ]]                                
  });    
 }

 function Getmonthly_plan()
 {
   $('.monthly').combogrid({
    panelWidth:400,
    idField: 'monthly_id',
    textField: 'monthly_no',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/Getmonthlyplan',

    columns: [[
    	{field:'monthly_no',title:'Month No',width:90}, 
		{field:'monthly_start_date',title:'Date Start',width:125,formatter:date_set}, 
		{field:'monthly_end_date',title:'Date End',width:125,formatter:date_set},  
		{field:'monthly_year',title:'Year',width:60},
		{field:'monthly_id',title:'Week Id',hidden:true},
                     
    ]]                                
  });    
 }

function Getweeklyplan()
 {
   $('.weekly').combogrid({
    panelWidth:400,
    idField: 'weekly_id',
    textField: 'weekly_no',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/Getweeklyplan',

    columns: [[
   //{field:'ck',checkbox:true},
	{field:'weekly_no',title:'Week No',width:90}, 
	{field:'weekly_start_date',title:'Date Start',width:125, formatter:date_set}, 
	{field:'weekly_end_date',title:'Date End',width:125,formatter:date_set},  
	{field:'weekly_year',title:'Year',width:60},     
	{field:'weekly_id',title:'Week Id',width:100,hidden:true},
                      
    ]] ,
	
  });    
 }


 
 function Get_Daily()
 {
   $('.daily').combogrid({
    panelWidth:400,
    idField: 'weekly_id',
    textField: 'weekly_no',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/Getweeklyplan',

    columns: [[
   //{field:'ck',checkbox:true},
	{field:'weekly_no',title:'Week No',width:90}, 
	{field:'weekly_start_date',title:'Date Start',width:125, formatter:date_set}, 
	{field:'weekly_end_date',title:'Date End',width:125,formatter:date_set},  
	{field:'weekly_year',title:'Year',width:60},     
	{field:'weekly_id',title:'Week Id',width:100,hidden:true},
                      
    ]] ,
	
  });    
 }
 
  function Getweeklyplan_send()
 {
   $('.weekly_report').combogrid({
    panelWidth:400,
    idField: 'weekly_id',
    textField: 'weekly_no',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/Getweeklyplan',

    columns: [[
   //{field:'ck',checkbox:true},
	{field:'weekly_no',title:'Week No',width:90}, 
    {field:'weekly_start_date',title:'Date Start',width:125,formatter:date_set}, 
	{field:'weekly_end_date',title:'Date End',width:125,formatter:date_set}, 
	{field:'weekly_year',title:'Year',width:60},     
	{field:'weekly_id',title:'Week Id',width:100,hidden:true},
                       
    ]] ,
	
  });    
 }
 
 function Getdailyplan_send()
 {
   $('.daily_report').combogrid({
    panelWidth:400,
    idField: 'weekly_id',
    textField: 'weekly_no',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/Getweeklyplan',

    columns: [[
   //{field:'ck',checkbox:true},
	{field:'weekly_no',title:'Week No',width:90}, 
    {field:'weekly_start_date',title:'Date Start',width:125,formatter:date_set}, 
	{field:'weekly_end_date',title:'Date End',width:125,formatter:date_set}, 
	{field:'weekly_year',title:'Year',width:60},     
	{field:'weekly_id',title:'Week Id',width:100,hidden:true},
                       
    ]] ,
	
  });    
 }
 

  function Getmonthlyplan_send()
 {
   $('.monthly_report').combogrid({
    panelWidth:400,
    idField: 'monthly_id',
    textField: 'monthly_no',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/Getmonthlyplan',

    columns: [[
   //{field:'ck',checkbox:true},
   	{field:'monthly_no',title:'Month No',width:50}, 
	{field:'monthly_start_date',title:'Date Start',width:125,formatter:date_set}, 
	{field:'monthly_end_date',title:'Date End',width:125,formatter:date_set}, 
	{field:'monthly_year',title:'Year',width:100},   
    {field:'monthly_id',title:'Week Id',width:100,hidden:true},                         
    ]],
	
  });    
 }
 
function GetContacttype()
 {
  
   //get member type
   $('.contact_type').combogrid({
    panelWidth:350,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: true, 
    fitColumns:true,                     
    url: paht+'/GetContacttype',

    columns: [[
    {field:'ck',checkbox:true},
    {field:'eventtypecode',title:'Code',width:100},
    {field:'eventtypename',title:'Name',width:250},                            
    ]]                                
  });    
 }



 function GetSponsor()
{

  $('.sponsor_name').combogrid({
   panelWidth:280,
   idField: 'actvitytypecode',
   textField: 'actvitytypename',
   mode:'remote',
   fitColumns:true,  
   multiple: true,   
   url: paht+'/GetSponsor',

   columns: [[
   {field:'ck',checkbox:true},
   {field:'actvitytypecode',title:'ID',width:120},
   {field:'actvitytypename',title:'Name',width:180},

   ]],
   onChange: function(index,row){



  }

});  

}


function GetSupporttype()
 {

   $('.support_type').combogrid({
    panelWidth:350,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: true, 
    fitColumns:true,                     
    url: paht+'/GetSupporttype',

    columns: [[
    {field:'ck',checkbox:true},
    {field:'eventtypecode',title:'Code',width:100},
    {field:'eventtypename',title:'Name',width:250},                            
    ]]                                
  });    
 }


function GetMemberlevel()
 {
  
   //get member type
   $('.member_level ').combogrid({
    panelWidth:350,
    idField: 'eventtypecode',
    textField: 'eventtypename',
    mode:'remote',
    multiple: true, 
    fitColumns:true,                     
    url: paht+'/GetMemberlevel',

    columns: [[
    {field:'ck',checkbox:true},
    {field:'eventtypecode',title:'Code',width:100},
    {field:'eventtypename',title:'Name',width:250},                            
    ]]                                
  });    
 }

function Getproject()
{

   $('.porject_id').combogrid({
    panelWidth:350,
    idField: 'branchid',
    textField: 'branch_name',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/GetProject',
	
    columns: [[

	{field:'branchid',title:'branchid',width:70,hidden:true},
    {field:'projects_code',title:'Project Code',width:100},
    {field:'branch_name',title:'Project Name',width:250},                            
    ]]                                
  });    
 }

function Getagreement()
{

   $('.agreement_method').combogrid({
		panelWidth:250,
		idField: 'eventtypecode',
		textField: 'eventtypename',
		mode:'remote',
		multiple: true, 
		fitColumns:true,    
		url: paht+'/Getagreement',
		columns: [[
			{field:'ck',checkbox:true},
			{field:'eventtypename',title:'ALL',width:250},                            
    	]]      	
  	});    
 }

function Getbooking()
{

   $('.reservation_paymentmethod').combobox({
		panelWidth:250,
		url: paht+'/GetBooking',
		valueField: 'eventtypecode',
		textField: 'eventtypename',          
  	});    
}

function Getactivity_name()
{
   $('.activity_name').combogrid({
		panelWidth:250,
		idField: 'eventtypecode',
    	textField: 'eventtypename',
		mode:'remote',
		multiple: true, 
		fitColumns:true,   
		url: paht+'/GetActivity',
		 
	columns: [[
	 	{field:'ck',title:' ',checkbox:true},
		{field:'eventtypecode',title:' ',width:120,hidden:true},    
		{field:'eventtypename',title:'ALL',width:120},                    
	]]         	         
  });    
}

function Get_Account_Source(){
   $('.account_source').combogrid({
		panelWidth:250,
		idField: 'account_source_code',
    	textField: 'account_source_name',
		mode:'remote',
		multiple: true, 
		fitColumns:true,   
		url: paht+'/Get_Account_Source_Pick_list',
		 
	columns: [[
	 	{field:'ck',title:' ',checkbox:true},
		{field:'account_source_code',title:' ',width:120,hidden:true},    
		{field:'account_source_name',title:'ALL',width:120},                    
	]]         	         
  });   	
}

function chk_string_end(val,row){
	if (typeof val === 'undefined' || val == null)  return "";
	if( val.substr(-1) === "," ) {
		return val.substring(0, val.length -1);
	}
	  return val;
}
function formatMoney0d(val,row){

  return accounting.formatMoney(val, "", 0, ",", ".");

}

function formatMoney2d(val,row){

  return accounting.formatMoney(val, "", 2, ",", ".");

}

function formatMoney3d(val,row){

  return accounting.formatMoney(val, "", 3, ",", ".");

}

function formatMoney4d(val,row){

  return accounting.formatMoney(val, "", 4, ",", ".");

}

function date_set(val,row)
{
	if(val!=""){
		//alert(datetime);
		var date = val.split('-');
	
			//var arr = date[0].split('/');
			date = date[2]+ "/"+ date[1] +"/"+date[0];
		
		return date;
	}else{
		return "";
	}
}

 function Get_brandsalesvisit()
 {
    //alert(123124);
     $('.brand').combogrid({
      panelWidth:400,
      idField: 'cf_4356',
      textField: 'cf_4356',
      mode:'remote',
      multiple: false, 
      fitColumns:true,                     
      url: paht+'/Getbrandsalesvisit',

      columns: [[
      //{field:'ck',checkbox:true},
        {field:'cf_4356',title:'Brand',width:100},                       
      ]] ,
      onSelect: function(index,row){
        //console.log("index"+index);
        //console.log("row"+row);
        Get_modelsalesvisit(row);
      }
    });   
 }

 function Get_modelsalesvisit(brand)
 {
    //console.log(brand.cf_4356);
    $('.model').combogrid({
      panelWidth:400,
      idField: 'targetvalues',
      textField: 'targetvalues',
      mode:'remote',
      queryParams:{
        brand:brand.cf_4356
      }, 
      multiple: false, 
      fitColumns:true,                     
      url: paht+'/Getmodelsalesvisit',

      columns: [[
      //{field:'ck',checkbox:true},
        {field:'targetvalues',title:'Model',width:100},                       
      ]] ,
    
    }); 
 }
  // $("#exporttoexcel").click(function(e) {
  //     $(".table2excel").table2excel({
  //       exclude: ".noExl",
  //       name: "Excel Document Name",
  //       filename: "myFileName",
  //       fileext: ".xls",
  //       exclude_img: true,
  //       exclude_links: true,
  //       exclude_inputs: true
  //     });
  //   }); 

