
var paht = location.protocol + "//" + window.location.host + "/" + location.pathname.split('/')[1] + "/components";
var host = location.protocol + "//" + window.location.host + "/" + location.pathname.split("/")[1] + "/extend/";
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

function GetProject_active()
{

   $('.porject_id').combogrid({
    panelWidth:350,
    idField: 'branchid',
    textField: 'branch_name',
    mode:'remote',
    multiple: false, 
    fitColumns:true,                     
    url: paht+'/GetProject_active',
	
    columns: [[
	{field:'branchid',title:'branchid',width:70,hidden:true},
    {field:'projects_code',title:'Project Code',width:100},
    {field:'branch_name',title:'Project Name',width:250},                            
    ]],
	onChange: function(index,row){
    	GetBuilding(index);
  	}                          
  });    
 }
 
 function GetBuilding(branchid)
{
  var param = "";
  if(branchid != "")
  {
      param == "" ? param += "?branchid="+branchid : param += "," + val;
  }

   $('.buildingid').combogrid({
		panelWidth:260,
		idField: 'buildingid',
		textField: 'building_name',
		mode:'remote',
		multiple: true, 
		fitColumns:true,    
		url: paht+'/Getbuilding'+param,
		columns: [[
			{field:'ck',checkbox:true},
			{field:'buildingid',title:'ALL',width:250 ,hidden:true},
			{field:'building_name',title:'ALL',width:250},                            
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



function Getmonthly_plan() {
  $(".monthly").combogrid({
    panelWidth: 400,
    idField: "monthly_id",
    textField: "monthly_no",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/Getmonthlyplan",

    columns: [
      [
        { field: "monthly_no", title: "Month No", width: 90 },
        {
          field: "monthly_start_date",
          title: "Date Start",
          width: 125,
          formatter: date_set,
        },
        {
          field: "monthly_end_date",
          title: "Date End",
          width: 125,
          formatter: date_set,
        },
        { field: "monthly_year", title: "Year", width: 60 },
        { field: "monthly_id", title: "Week Id", hidden: true },
      ],
    ],
  });
}

function Getweeklyplan() {
  $(".weekly").combogrid({
    panelWidth: 400,
    idField: "weekly_id",
    textField: "weekly_no",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/Getweeklyplan",

    columns: [
      [
        { field: "weekly_no", title: "Week No", width: 90 },
        {
          field: "weekly_start_date",
          title: "Date Start",
          width: 125,
          formatter: date_set,
        },
        {
          field: "weekly_end_date",
          title: "Date End",
          width: 125,
          formatter: date_set,
        },
        { field: "weekly_year", title: "Year", width: 60 },
        { field: "weekly_id", title: "Week Id", width: 100, hidden: true },
      ],
    ],
  });
}

function Get_Daily() {
  $(".daily").combogrid({
    panelWidth: 400,
    idField: "weekly_id",
    textField: "weekly_no",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/Getweeklyplan",

    columns: [
      [
        { field: "weekly_no", title: "Week No", width: 90 },
        {
          field: "weekly_start_date",
          title: "Date Start",
          width: 125,
          formatter: date_set,
        },
        {
          field: "weekly_end_date",
          title: "Date End",
          width: 125,
          formatter: date_set,
        },
        { field: "weekly_year", title: "Year", width: 60 },
        { field: "weekly_id", title: "Week Id", width: 100, hidden: true },
      ],
    ],
  });
}

function Getweeklyplan_send() {
  $(".weekly_report").combogrid({
    panelWidth: 400,
    idField: "weekly_id",
    textField: "weekly_no",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/Getweeklyplan",

    columns: [
      [
        { field: "weekly_no", title: "Week No", width: 90 },
        {
          field: "weekly_start_date",
          title: "Date Start",
          width: 125,
          formatter: date_set,
        },
        {
          field: "weekly_end_date",
          title: "Date End",
          width: 125,
          formatter: date_set,
        },
        { field: "weekly_year", title: "Year", width: 60 },
        { field: "weekly_id", title: "Week Id", width: 100, hidden: true },
      ],
    ],
  });
}

function Getweeklyplan_sendadmin() {
  $(".weekly_report_admin").combogrid({
    panelWidth: 400,
    idField: "weekly_id",
    textField: "weekly_no",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/Getweeklyplan",

    columns: [
      [
        { field: "weekly_no", title: "Week No", width: 90 },
        {
          field: "weekly_start_date",
          title: "Date Start",
          width: 125,
          formatter: date_set,
        },
        {
          field: "weekly_end_date",
          title: "Date End",
          width: 125,
          formatter: date_set,
        },
        { field: "weekly_year", title: "Year", width: 60 },
        { field: "weekly_id", title: "Week Id", width: 100, hidden: true },
      ],
    ],
  });
}

function Getdailyplan_send() {
  $(".daily_report").combogrid({
    panelWidth: 400,
    idField: "weekly_id",
    textField: "weekly_no",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/Getweeklyplan",

    columns: [
      [
        { field: "weekly_no", title: "Week No", width: 90 },
        {
          field: "weekly_start_date",
          title: "Date Start",
          width: 125,
          formatter: date_set,
        },
        {
          field: "weekly_end_date",
          title: "Date End",
          width: 125,
          formatter: date_set,
        },
        { field: "weekly_year", title: "Year", width: 60 },
        { field: "weekly_id", title: "Week Id", width: 100, hidden: true },
      ],
    ],
  });
}

function Getdailyplan_sendadmin() {
  $(".daily_report_admin").combogrid({
    panelWidth: 400,
    idField: "weekly_id",
    textField: "weekly_no",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/Getweeklyplan",

    columns: [
      [
        { field: "weekly_no", title: "Week No", width: 90 },
        {
          field: "weekly_start_date",
          title: "Date Start",
          width: 125,
          formatter: date_set,
        },
        {
          field: "weekly_end_date",
          title: "Date End",
          width: 125,
          formatter: date_set,
        },
        { field: "weekly_year", title: "Year", width: 60 },
        { field: "weekly_id", title: "Week Id", width: 100, hidden: true },
      ],
    ],
  });
}

function Getmonthlyplan_send() {
  $(".monthly_report").combogrid({
    panelWidth: 400,
    idField: "monthly_id",
    textField: "monthly_no",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/Getmonthlyplan",

    columns: [
      [
        { field: "monthly_no", title: "Month No", width: 50 },
        {
          field: "monthly_start_date",
          title: "Date Start",
          width: 125,
          formatter: date_set,
        },
        {
          field: "monthly_end_date",
          title: "Date End",
          width: 125,
          formatter: date_set,
        },
        { field: "monthly_year", title: "Year", width: 100 },
        { field: "monthly_id", title: "Week Id", width: 100, hidden: true },
      ],
    ],
  });
}

function Getmonthlyplan_sendadmin() {
  $(".monthly_report_admin").combogrid({
    panelWidth: 400,
    idField: "monthly_id",
    textField: "monthly_no",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/Getmonthlyplan",

    columns: [
      [
        { field: "monthly_no", title: "Month No", width: 50 },
        {
          field: "monthly_start_date",
          title: "Date Start",
          width: 125,
          formatter: date_set,
        },
        {
          field: "monthly_end_date",
          title: "Date End",
          width: 125,
          formatter: date_set,
        },
        { field: "monthly_year", title: "Year", width: 100 },
        { field: "monthly_id", title: "Week Id", width: 100, hidden: true },
      ],
    ],
  });
}

function Get_Reporttype() {
  //get Report type
  $(".report_type").combogrid({
    panelWidth: 350,
    idField: "reportcode",
    textField: "reportname",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/GetReporttype",

    columns: [
      [
        { field: "reportcode", title: "Code", width: 100 },
        { field: "reportname", title: "Name", width: 250 },
      ],
    ],
  });
}

function Get_Reporttype_send() {
  $(".report_type_model").combogrid({
    panelWidth: 350,
    idField: "reportcode",
    textField: "reportname",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/GetReporttype",

    columns: [
      [
        { field: "reportcode", title: "Code", width: 100 },
        { field: "reportname", title: "Name", width: 250 },
      ],
    ],
  });
}

function Getactivitytype_report() {
  //get Report type
  $(".objective").combogrid({
    panelWidth: 350,
    idField: "eventtypecode",
    textField: "eventtypename",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/Getactivitytype",

    columns: [[{ field: "eventtypename", title: "Name", width: 350 }]],
  });
}

function Getactivitytype_report_send() {
  //get Report type
  $(".objective_report").combogrid({
    panelWidth: 350,
    idField: "eventtypecode",
    textField: "eventtypename",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/Getactivitytype",

    columns: [[{ field: "eventtypename", title: "Name", width: 350 }]],
  });
}

function Get_Department() {
  //get Report type
  $(".department").combogrid({
    panelWidth: 350,
    idField: "eventtypecode",
    textField: "eventtypename",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/GetDepartment",

    columns: [
      [
        { field: "eventtypecode", title: "Code", width: 100 },
        { field: "eventtypename", title: "Name", width: 250 },
      ],
    ],
  });
}

function Get_Sale() {
  //get Report type
  $(".sales").combogrid({
    panelWidth: 350,
    idField: "id",
    textField: "sale_name",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/GetSale",

    columns: [
      [
        { field: "id", title: "ID", width: 100, hidden: true },
        { field: "user_name", title: "Username", width: 250, hidden: true },
        { field: "first_name", title: "Firstname", width: 250, hidden: true },
        { field: "last_name", title: "Lastname", width: 250, hidden: true },
        { field: "sale_name", title: "Salename", width: 250 },
      ],
    ],
  });
}

function Get_Sales() {
  //get Report type
  $(".sales").combogrid({
    panelWidth: 350,
    idField: "id",
    textField: "sale_name",
    mode: "remote",
    multiple: true,
    fitColumns: true,
    url: paht + "/GetSale",

    columns: [
      [
        { field: "ck", checkbox: true },
        { field: "sale_name", title: "Select All", width: 250 },
        { field: "first_name", title: "Firstname", width: 250, hidden: true },
        { field: "last_name", title: "Lastname", width: 250, hidden: true }
      ],
    ]
  });
}

function Get_Sales_team() {
  //get Report type
  $(".sales_team").combogrid({
    panelWidth: 350,
    idField: "id",
    textField: "rolename",
    mode: "remote",
    multiple: true,
    fitColumns: true,
    url: paht + "/GetSaleTeam",

    columns: [
      [
        { field: "ck", checkbox: true },
        { field: "rolename", title: "Select All", width: 250 },
      ],
    ]
  });
}

function Get_Stage() {
  $(".stage").combogrid({
    panelWidth: 350,
    idField: "id",
    textField: "stage",
    mode: "remote",
    multiple: true,
    fitColumns: true,
    url: paht + "/GetStageDeal",

    columns: [
      [
        { field: "ck", checkbox: true },
        { field: "stage", title: "Select All", width: 250 },
      ],
    ]
  });
}

function Get_Status() {
  $(".status").combogrid({
    panelWidth: 350,
    idField: "id",
    textField: "status",
    mode: "remote",
    multiple: true,
    fitColumns: true,
    url: paht + "/GetStatusDeal",

    columns: [
      [
        { field: "ck", checkbox: true },
        { field: "status", title: "Select All", width: 250 },
      ],
    ]
  });
}

function Get_Sale_send() {
  //get Report type
  $(".sales_report").combogrid({
    panelWidth: 250,
    idField: "id",
    textField: "user_name",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/GetSale",

    columns: [
      [
        { field: "id", title: "ID", width: 100, hidden: true },
        { field: "user_name", title: "Username", width: 250, hidden: true },
        { field: "first_name", title: "Firstname", width: 250, hidden: true },
        { field: "last_name", title: "Lastname", width: 250, hidden: true },
        { field: "sale_name", title: "Salename", width: 250 },
      ],
    ],
  });
}

function Get_Sale_send_new() {
  //get Report type
  $(".sales_report").combogrid({
    panelWidth: 250,
    idField: "id",
    textField: "user_name",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/GetSale",

    columns: [
      [
        { field: "id", title: "ID", width: 100, hidden: true },
        { field: "user_name", title: "Username", width: 250, hidden: true },
        { field: "first_name", title: "Firstname", width: 250, hidden: true },
        { field: "last_name", title: "Lastname", width: 250, hidden: true },
        { field: "sale_name", title: "Salename", width: 250 },
      ],
    ],
  });
}

function Get_section(section) {
  $(".section").combogrid({
    panelWidth: 350,
    idField: "section",
    textField: "section",
    mode: "remote",
    value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/Getsection",
    columns: [[{ field: "section", title: "Section", width: 250 }]],
  });
}

function Getsendtofromuser(id) {
  var url = paht + "/Get_send_to";
  var form_data = {
    userid: id,
  };
  $.ajax(url, {
    type: "POST",
    dataType: "JSON",
    data: form_data,
    success: function (data) {
      if (data.send_user_id !== false && data.send_user_id !== null) {
        Get_Sale_sendto(data.send_user_id, id, data.section);
      } else {
        Get_Sale_sendto("", id, data.section);
      }
    },
    error: function (data) {
      $.messager.alert("Retrieve data", data, "error");
    },
  });
}

function Get_Sale_sendto(user_send_to, id, section) {
  //get Report type
  $(".send_to").combogrid({
    panelWidth: 250,
    idField: "id",
    textField: "user_name",
    mode: "remote",
    value: user_send_to,
    multiple: true,
    queryParams: {
      id: id,
      section: section,
    },
    fitColumns: true,
    url: paht + "/Get_Sale_section/",
    columns: [
      [
        { field: "ck", checkbox: true },
        { field: "id", title: "ID", width: 100, hidden: true },
        { field: "user_name", title: "Username", width: 250, hidden: true },
        { field: "first_name", title: "Firstname", width: 250, hidden: true },
        { field: "last_name", title: "Lastname", width: 250, hidden: true },
        { field: "sale_name", title: "Salename", width: 250 },
      ],
    ],
  });
}

function Getsendtofromuser_sendto(id) {
  var url = paht + "/Get_send_to";
  var form_data = {
    userid: id,
  };
  $.ajax(url, {
    type: "POST",
    dataType: "JSON",
    data: form_data,
    success: function (data) {
      if (data.send_user_id !== false && data.send_user_id !== null) {
        Get_Sale_send(data.send_user_id, id, data.section);
      } else {
        Get_Sale_send("", id, data.section);
      }
    },
    error: function (data) {
      $.messager.alert("Retrieve data", data, "error");
    },
  });
}

function Get_Sale_send(user_send_to, id, section) {
  //get Report type
  $(".send_to, .send_cc").combogrid({
    panelWidth: 250,
    idField: "id",
    textField: "user_name",
    mode: "remote",
    value: user_send_to,
    multiple: true,
    queryParams: {
      id: id,
      section: section,
    },
    fitColumns: true,
    url: paht + "/Get_Sale_section/",
    columns: [
      [
        { field: "ck", checkbox: true },
        { field: "id", title: "ID", width: 100, hidden: true },
        { field: "user_name", title: "Username", width: 250, hidden: true },
        { field: "first_name", title: "Firstname", width: 250, hidden: true },
        { field: "last_name", title: "Lastname", width: 250, hidden: true },
        { field: "sale_name", title: "Select All", width: 250 },
      ],
    ],
  });
}

function Getmonthly_Report() {
  $(".monthly").combogrid({
    panelWidth: 400,
    idField: "monthly_id",
    textField: "monthly_no",
    mode: "remote",
    multiple: false,
    fitColumns: true,
    url: paht + "/GetmonthlyReport",

    columns: [
      [
        { field: "monthly_no", title: "Month No", width: 120 },
        {
          field: "monthly_start_date",
          title: "Date Start",
          width: 125,
          formatter: date_set,
        },
        {
          field: "monthly_end_date",
          title: "Date End",
          width: 125,
          formatter: date_set,
        },
        { field: "monthly_year", title: "Year", width: 60 },
        { field: "monthly_id", title: "Week Id", hidden: true },
      ],
    ],
  });
}

function getQuestionnaireTemplate() {
  
  var url = host + "Questionnaire/listQuestionnaireTemplate";

  $("#questionnairetemplateid").combogrid({
    idField: 'questionnairetemplateid',
    textField: 'questionnairetemplate_name',
    multiple: false,
    url: url,
    columns: [
      [
        // { field: "ck", checkbox: true },
        { field: "questionnairetemplate_name", title: "แบบสอบถาม"},
      ]
    ]
  });
}

function GetProjectType() {
  $(".project_type").combogrid({
    panelWidth: 350,
    idField: "project_type",
    textField: "project_type",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetProjectType",
    columns: [[{ field: "project_type", title: "Project Type", width: 250 }]],
  });
}

function GetProjectSize() {
  $(".project_size").combogrid({
    panelWidth: 350,
    idField: "project_size",
    textField: "project_size",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetProjectSize",
    columns: [[{ field: "project_size", title: "Project Size", width: 250 }]],
  });
}

function GetProjectOpportunity() {
  $(".project_opportunity").combogrid({
    panelWidth: 350,
    idField: "project_opportunity",
    textField: "project_opportunity",
    mode: "remote",
    // value: section,
    multiple: true,
    fitColumns: true,
    url: paht + "/GetProjectOpportunity",
    columns: [[{ field: "ck", checkbox: true },{ field: "project_opportunity", title: "Select All", width: 250 }]],
  });
}

function GetProjectOrderStatus() {
  $(".projectorder_status").combogrid({
    panelWidth: 350,
    idField: "projectorder_status",
    textField: "projectorder_status",
    mode: "remote",
    // value: section,
    multiple: true,
    fitColumns: true,
    url: paht + "/GetProjectOrderStatus",
    columns: [
      [
        { field: "ck", checkbox: true },
        { field: "projectorder_status", title: "Select All", width: 250 }
      ]
    ],
    // onLoadSuccess: function(data) {
    //   var grid = $(this).combogrid('grid'); // Get the grid object
    //   var rows = grid.datagrid('getRows'); // Get all the rows in the grid
    //   for (var i = 0; i < rows.length; i++) {
    //       if (rows[i].projectorder_status != "Cancelled : CL" && rows[i].projectorder_status != "--None--" && rows[i].projectorder_status != "Job Close : JC") {
    //           grid.datagrid('selectRow', i); // Select the row if it doesn't have a value of 1000
    //       }
    //   }
    // }
  });
}

function GetSaleRap(user_send_to, id, section) {
  $(".sale_rap, .sale").combogrid({
    panelWidth: 350,
    idField: "id",
    textField: "sale_name",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetSaleRap",
    columns: [[{ field: "sale_name", title: "List", width: 250 }]],
  });
}

function GetQuotationType() {
  $(".quotation_type").combogrid({
    panelWidth: 350,
    idField: "quotation_type",
    textField: "quotation_type",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetQuotationType",
    columns: [[{ field: "quotation_type", title: "Quotation Type", width: 250 }]],
  });
}

function GetQuotationStatus() {
  $(".quotation_status").combogrid({
    panelWidth: 350,
    idField: "quotation_status",
    textField: "quotation_status",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetQuotationStatus",
    columns: [[{ field: "quotation_status", title: "Quotation Status", width: 250 }]],
  });
}


function GetProductBrand() {
  $(".product_brand").combogrid({
    panelWidth: 350,
    idField: "product_brand",
    textField: "product_brand",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetProductBrand",
    columns: [[{ field: "product_brand", title: "Product Brand", width: 250 }]],
  });
}

function GetProductBrandMultiPicklist() {
  $(".product_brand").combogrid({
    panelWidth: 350,
    idField: "product_brand",
    textField: "product_brand",
    mode: "remote",
    // value: section,
    multiple: true,
    fitColumns: true,
    url: paht + "/GetProductBrand",
    columns: [
      [
        { field: "ck", checkbox: true },
        { field: "product_brand", title: "Select All", width: 250 }
      ]
    ],
  });
}

function GetFinishNameMultiPicklist() {
  $(".product_finish").combogrid({
    panelWidth: 350,
    idField: "product_finish",
    textField: "product_finish",
    mode: "remote",
    // value: section,
    multiple: true,
    fitColumns: true,
    url: paht + "/GetFinishName",
    columns: [
      [
        { field: "ck", checkbox: true },
        { field: "product_finish", title: "Select All", width: 250 }
      ]
    ],
  });
}

function GetFinishNameMultiPicklist() {
  $(".product_finish").combogrid({
    panelWidth: 350,
    idField: "product_finish",
    textField: "product_finish",
    mode: "remote",
    // value: section,
    multiple: true,
    fitColumns: true,
    url: paht + "/GetFinishName",
    columns: [
      [
        { field: "ck", checkbox: true },
        { field: "product_finish", title: "Select All", width: 250 }
      ]
    ],
  });
}

function GetSizeFTMultiPicklist() {
  $(".product_size_ft").combogrid({
    panelWidth: 350,
    idField: "product_size_ft",
    textField: "product_size_ft",
    mode: "remote",
    // value: section,
    multiple: true,
    fitColumns: true,
    url: paht + "/GetSizeFT",
    columns: [
      [
        { field: "ck", checkbox: true },
        { field: "product_size_ft", title: "Select All", width: 250 }
      ]
    ],
  });
}

function GetThicknessMultiPicklist() {
  $(".product_thinkness").combogrid({
    panelWidth: 350,
    idField: "product_thinkness",
    textField: "product_thinkness",
    mode: "remote",
    // value: section,
    multiple: true,
    fitColumns: true,
    url: paht + "/GetThickness",
    columns: [
      [
        { field: "ck", checkbox: true },
        { field: "product_thinkness", title: "Select All", width: 250 }
      ]
    ],
  });
}

function GetGrade() {
  $(".product_grade").combogrid({
    panelWidth: 350,
    idField: "product_grade",
    textField: "product_grade",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetGrade",
    columns: [[{ field: "product_grade", title: "Product Grade", width: 250 }]],
  });
}

function GetGradeMultiPicklist() {
  $(".product_grade").combogrid({
    panelWidth: 350,
    idField: "product_grade",
    textField: "product_grade",
    mode: "remote",
    // value: section,
    multiple: true,
    fitColumns: true,
    url: paht + "/GetGrade",
    columns: [[{ field: "ck", checkbox: true },{ field: "product_grade", title: "Select All", width: 250 }]],
  });
}

function GetProductStatus() {
  $(".product_status").combogrid({
    panelWidth: 350,
    idField: "producttatus",
    textField: "producttatus",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetProductStatus",
    columns: [[{ field: "producttatus", title: "Product Status", width: 250 }]],
  });
}

function GetShippingMethod() {
  $(".shipping_method").combogrid({
    panelWidth: 350,
    idField: "shipping_method",
    textField: "shipping_method",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetShippingMethod",
    columns: [[{ field: "shipping_method", title: "Shipping Method", width: 250 }]],
  });
}

function GetItemStatus() {
  $(".item_status").combogrid({
    panelWidth: 350,
    idField: "item_status",
    textField: "item_status",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetItemStatus",
    columns: [[{ field: "item_status", title: "Item Status", width: 250 }]],
  });
}

function GetSafetyStock() {
  $(".safety_stock").combogrid({
    panelWidth: 350,
    idField: "safety_stock",
    textField: "safety_stock",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetSafetyStock",
    columns: [[{ field: "safety_stock", title: "Safety Stock", width: 250 }]],
  });
}

function GetOrderBySafety() {
  $(".order_by_safety").combogrid({
    panelWidth: 350,
    idField: "order_by_safety",
    textField: "order_by_safety",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetOrderBySafety",
    columns: [[{ field: "order_by_safety", title: "Order By Safety", width: 250 }]],
  });
}

function GetSafetyMonth() {
  $(".safety_month").combogrid({
    panelWidth: 350,
    idField: "safety_month",
    textField: "safety_month",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetSafetyMonth",
    columns: [[{ field: "safety_month", title: "Safety Month", width: 250 }]],
  });
}

function GetFinalbyOrder2mth() {
  $(".final_by_order_2_mth").combogrid({
    panelWidth: 350,
    idField: "final_by_order_2_mth",
    textField: "final_by_order_2_mth",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetFinalbyOrder2mth",
    columns: [[{ field: "final_by_order_2_mth", title: "Final by Order for 2 mth", width: 250 }]],
  });
}

function GetPurchasingfor2mth() {
  $(".purchasing_2_mth").combogrid({
    panelWidth: 350,
    idField: "purchasing_2_mth",
    textField: "purchasing_2_mth",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetPurchasingfor2mth",
    columns: [[{ field: "purchasing_2_mth", title: "Purchasing for 2 mth", width: 250 }]],
  });
}

function GetFinalbyOrder3mth() {
  $(".final_by_order_3_mth").combogrid({
    panelWidth: 350,
    idField: "final_by_order_3_mth",
    textField: "final_by_order_3_mth",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetFinalbyOrder3mth",
    columns: [[{ field: "final_by_order_3_mth", title: "Final by Order for 3 mth", width: 250 }]],
  });
}

function GetPurchasingfor3mth() {
  $(".purchasing_3_mth").combogrid({
    panelWidth: 350,
    idField: "purchasing_3_mth",
    textField: "purchasing_3_mth",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetPurchasingfor3mth",
    columns: [[{ field: "purchasing_3_mth", title: "Purchasing for 3 mth", width: 250 }]],
  });
}


function GetFinalbyOrder4mth() {
  $(".final_by_order_4_mth").combogrid({
    panelWidth: 350,
    idField: "final_by_order_4_mth",
    textField: "final_by_order_4_mth",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetFinalbyOrder4mth",
    columns: [[{ field: "final_by_order_4_mth", title: "Final by Order for 4 mth", width: 250 }]],
  });
}

function GetPurchasingfor4mth() {
  $(".purchasing_4_mth").combogrid({
    panelWidth: 350,
    idField: "purchasing_4_mth",
    textField: "purchasing_4_mth",
    mode: "remote",
    // value: section,
    multiple: false,
    fitColumns: true,
    url: paht + "/GetPurchasingfor4mth",
    columns: [[{ field: "purchasing_4_mth", title: "Purchasing for 4 mth", width: 250 }]],
  });
}

