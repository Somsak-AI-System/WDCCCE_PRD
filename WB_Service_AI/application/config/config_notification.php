<?php
//$config['notification']['url'] = "http://203.151.189.182:10080/DataControl/";
$config['notification']['url_api'] = "https://notification.moai-crm.com/notification/send_uat.php";
$config['notification']['url'] = "https://notification.moai-crm.com/DataControl/";

$config['notification']['projectcode'] = "GLAPCRM";
//$config['notification']['projectcode'] = "GWMC";

//EX send_mode = send | batch | fieldsend
//  || send //auto on save //sendnoti auto onsave now() time + 10 minute
//	|| batch // date=tomorrow and time = setting :: send_time = "" then current_time()
//  || fieldsend // send datetime on field :: value date ="" then tomorrow :: value time ="" then current_time()

$config['notification']['calendar']["send"] = array(
	"send_mode" => "save",
	"send_time" => "",//send mode = batch = sendtime = must key date tomorrow
	"field_crm_date" => "date_start",//calendar_notification_date
	"field_crm_time" => "time_start"//calendar_notification_time
);

$config['notification']['calendar']["queryfunction"] = "get_calendar";
$config['notification']['calendar']["msg"] = "[titlenews]";
$config['notification']['calendar']["startdate"] = "";
$config['notification']['calendar']["enddate"] = "";
$config['notification']['calendar']["field"] = array();

$config['notification']['Job']["send"] = array(
	"send_mode" => "save",
	"send_time" => "",//send mode = batch = sendtime = must key date tomorrow
	"field_crm_date" => "jobdate_operate",//calendar_notification_date
	"field_crm_time" => "start_time"//calendar_notification_time
);

$config['notification']['Job']["queryfunction"] = "get_job";
$config['notification']['Job']["msg"] = "[titlenews]";
$config['notification']['Job']["startdate"] = "";
$config['notification']['Job']["enddate"] = "";
$config['notification']['Job']["field"] = array();


$config['notification']['Leads']["send"] = array(
		"send_mode" => "save",
		"send_time" => "",//send mode = batch = sendtime = must key date tomorrow
		"field_crm_date" => "",//lead_notification_date
		"field_crm_time" => ""//lead_notification_time
		// "field_crm_date" => date("Y-m-d") ,//lead_notification_date
		// "field_crm_time" => date("H:i:s")//lead_notification_time
);

$config['notification']['Leads']["queryfunction"] = "get_leads";
$config['notification']['Leads']["msg"] = "[titlenews]";
$config['notification']['Leads']["startdate"] = "";
$config['notification']['Leads']["enddate"] = "";
$config['notification']['Leads']["field"] = array();

$config['notification']['Accounts']["send"] = array(
		"send_mode" => "save",
		"send_time" => "",
		"field_crm_date" => "",
		"field_crm_time" => ""
);

$config['notification']['Accounts']["queryfunction"] = "get_accounts";
$config['notification']['Accounts']["msg"] = "[titlenews]";
$config['notification']['Accounts']["startdate"] = "";
$config['notification']['Accounts']["enddate"] = "";
$config['notification']['Accounts']["field"] = array();

$config['notification']['Deal']["send"] = array(
		"send_mode" => "save",
		"send_time" => "",
		"field_crm_date" => "",
		"field_crm_time" => ""
);

$config['notification']['Deal']["queryfunction"] = "get_deal";
$config['notification']['Deal']["msg"] = "[titlenews]";
$config['notification']['Deal']["startdate"] = "";
$config['notification']['Deal']["enddate"] = "";
$config['notification']['Deal']["field"] = array();

$config['notification']['Questionnaire']["send"] = array(
		"send_mode" => "save",
		"send_time" => "",
		"field_crm_date" => "",
		"field_crm_time" => ""
);

$config['notification']['Questionnaire']["queryfunction"] = "get_questionnaire";
$config['notification']['Questionnaire']["msg"] = "[titlenews]";
$config['notification']['Questionnaire']["startdate"] = "";
$config['notification']['Questionnaire']["enddate"] = "";
$config['notification']['Questionnaire']["field"] = array();



$config['notification']['news']["send"] = array(
	"send_mode" => "save",
	"send_time" => "",//send mode = batch = sendtime = must key date tomorrow
	"field_crm_date" => "",//km_notification_date
	"field_crm_time" => ""//km_notification_time
);

//Ex send user all || building || only
//all = all user in project
//building = user in buildingid and query get_contact_km
//only = 1 person
$config['notification']['news']["send_user"] = array(
	"user_mode" => "all", //all = all user in project
	"user_query" => "get_contact_km",
	"condition" => "",
	"field_crm_userid" => ""
);
$config['notification']['news']["queryfunction"] = "get_news";
$config['notification']['news']["msg"] = "[titlenews]";
$config['notification']['news']["startdate"] = "";
$config['notification']['news']["enddate"] = "";
$config['notification']['news']["field"] = array(
	"0" => array(
			"columnmap" => "titlenews",
			"columncrm"	=> "knowledgebase_name",
			"function" => ""
	),
);

$config['notification']['new_urgent']["send"] = array(
	"send_mode" => "save",
	"send_time" => "",//send mode = batch = sendtime = must key date tomorrow
	"field_crm_date" => "",//km_notification_date
	"field_crm_time" => ""//km_notification_time
);
$config['notification']['new_urgent']["send_user"] = array(
	"user_mode" => "building", //all = all user in project
	"user_query" => "get_contact_km",
	"condition" => "",
	"field_crm_userid" => ""
);
$config['notification']['new_urgent']["queryfunction"] = "get_news";
$config['notification']['new_urgent']["msg"] = "[titlenews]";
$config['notification']['new_urgent']["startdate"] = "";
$config['notification']['new_urgent']["enddate"] = "";
$config['notification']['new_urgent']["field"] = array(
	"0" => array(
			"columnmap" => "titlenews",
			"columncrm"	=> "knowledgebase_name",
			"function" => ""
	),
);


$config['notification']['debitnote']["send"] = array(
	"send_mode" => "save",
	"send_time" => "",//send mode = batch = sendtime = must key date tomorrow
	"field_crm_date" => "",//km_notification_date
	"field_crm_time" => ""//km_notification_time
);
$config['notification']['debitnote']["send_user"] = array(
	"user_mode" => "only", //all = all user in project
	"user_query" => "",
	"condition" => "",
	"field_crm_userid" => "contactid"
);
$config['notification']['debitnote']["queryfunction"] = "get_debitnote";
$config['notification']['debitnote']["msg"] = "แจ้งค่าใช้จ่ายประจำเดือน";
$config['notification']['debitnote']["startdate"] = "";
$config['notification']['debitnote']["enddate"] = "";
$config['notification']['debitnote']["field"] = array();


$config['notification']['parcel']["send"] = array(
		"send_mode" => "save",
		"send_time" => "",//send mode = batch = sendtime = must key date tomorrow
		"field_crm_date" => "",//km_notification_date
		"field_crm_time" => ""//km_notification_time
);
$config['notification']['parcel']["send_user"] = array(
		"user_mode" => "only", //all = all user in project
		"user_query" => "",
		"condition" => "",
		"field_crm_userid" => "contactid"
);
$config['notification']['parcel']["queryfunction"] = "get_percel";
$config['notification']['parcel']["msg"] = "มีพัสดุส่งถึงคุณ";
$config['notification']['parcel']["startdate"] = "";
$config['notification']['parcel']["enddate"] = "";
$config['notification']['parcel']["field"] = array();



## Service Request
$config['notification']['appointment_cfm']["send"] = array(
		"send_mode" => "save",
		"send_time" => "",//send mode = batch = sendtime = must key date tomorrow
		"field_crm_date" => "",//km_notification_date
		"field_crm_time" => ""//km_notification_time
);
$config['notification']['appointment_cfm']["send_user"] = array(
		"user_mode" => "only", //all = all user in project
		"user_query" => "",
		"condition" => "",
		"field_crm_userid" => "contactid"
);
$config['notification']['appointment_cfm']["queryfunction"] = "get_servicerequest";
$config['notification']['appointment_cfm']["msg"] = "เรียน คุณ [contactname] ทาง Sena we care ยืนยันเข้าซ่อมงาน ในวัน [servicedate]เวลา [servicetime]น.";
$config['notification']['appointment_cfm']["startdate"] = "cf_2322";
$config['notification']['appointment_cfm']["starttime"] = "cf_2323";
$config['notification']['appointment_cfm']["enddate"] = "";
$config['notification']['appointment_cfm']["endtime"] = "";
$config['notification']['appointment_cfm']["field"] = array(
		"0" => array(
				"columnmap" => "contactname",
				"columncrm"	=> "cf_1968",
				"function" => ""
		),
		"1" => array(
				"columnmap" => "servicedate",
				"columncrm"	=> "cf_2322",
				"function" => "get_datethai"
		),
		"2" => array(
				"columnmap" => "servicetime",
				"columncrm"	=> "cf_2323",
				"function" => ""
		),
);

## Service Request assessment_cfm
$config['notification']['assessment_cfm']["send"] = array(
		"send_mode" => "save",
		"send_time" => "",//send mode = batch = sendtime = must key date tomorrow
		"field_crm_date" => "",//km_notification_date
		"field_crm_time" => ""//km_notification_time
);
$config['notification']['assessment_cfm']["send_user"] = array(
		"user_mode" => "only", //all = all user in project
		"user_query" => "",
		"condition" => "",
		"field_crm_userid" => "contactid"
);
$config['notification']['assessment_cfm']["queryfunction"] = "get_servicerequest";
$config['notification']['assessment_cfm']["msg"] = "เรียน คุณ [contactname] ทาง MOAI CRM ยืนยันเข้าดูหน้างาน ในวัน [servicedate]เวลา [servicetime] น.";
$config['notification']['assessment_cfm']["startdate"] = "cf_2322";
$config['notification']['assessment_cfm']["starttime"] = "cf_2323";
$config['notification']['assessment_cfm']["enddate"] = "";
$config['notification']['assessment_cfm']["endtime"] = "";
$config['notification']['assessment_cfm']["field"] = array(
		"0" => array(
				"columnmap" => "contactname",
				"columncrm"	=> "cf_1968",
				"function" => ""
		),
		"1" => array(
				"columnmap" => "servicedate",
				"columncrm"	=> "cf_2322",
				"function" => "get_datethai"
		),
		"2" => array(
				"columnmap" => "servicetime",
				"columncrm"	=> "cf_2323",
				"function" => ""
		),
);

$config['notification']['Accounts']["queryfunction"] = "get_accounts";
$config['notification']['Accounts']["msg"] = "[titlenews]";
$config['notification']['Accounts']["startdate"] = "";
$config['notification']['Accounts']["enddate"] = "";
$config['notification']['Accounts']["field"] = array();

$config['notification']['Leads']["queryfunction"] = "get_leads";
$config['notification']['Leads']["msg"] = "[titlenews]";
$config['notification']['Leads']["startdate"] = "";
$config['notification']['Leads']["enddate"] = "";
$config['notification']['Leads']["field"] = array();

$config['notification']['Deal']["queryfunction"] = "get_deal";
$config['notification']['Deal']["msg"] = "[titlenews]";
$config['notification']['Deal']["startdate"] = "";
$config['notification']['Deal']["enddate"] = "";
$config['notification']['Deal']["field"] = array();

$config['notification']['Questionnaire']["queryfunction"] = "get_questionnaire";
$config['notification']['Questionnaire']["msg"] = "[titlenews]";
$config['notification']['Questionnaire']["startdate"] = "";
$config['notification']['Questionnaire']["enddate"] = "";
$config['notification']['Questionnaire']["field"] = array();