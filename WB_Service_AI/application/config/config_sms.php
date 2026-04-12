<?php
$config['sms']['method'] = array("service_request","service_close" ,"inspection_appointment","inspection_appointment_second","transfer_appointment","reserved","contract_notification","service_otp","service_chang_password","sms_paymentgateway_TH","sms_paymentgateway_EN","register_otp");
$config['sms']['service_request']["sms_settingid"] = "1";

//$config['sms']['service_request']["msg"] = "Major Development ได้รับเรื่องแจ้งซ่อมของ [projectname]-[productcode] แล้วค่ะ";
$config['sms']['service_request']["msg"] = "Thank you for create Service No.[serviceno] Project : [projectname] : House No. [houseno] Major Development Contact Center 1266";
$config['sms']['service_request']["module"] = "ServiceRequest";
$config['sms']['service_request']["contactname"] = "request_name";
$config['sms']['service_request']["contacttel"] = "request_user_phone";
$config['sms']['service_request']["field"] = array(
	"0" => array(
		"columnmap" => "serviceno",
		"columncrm"	=> "servicerequest_no",
		"function" => ""				
	),
	"1" => array(
		"columnmap" => "projectname",
		"columncrm"	=> "cf_3550",
		"function" => ""
		),
	"2" => array(
		"columnmap" => "houseno",
		"columncrm"	=> "productaddress",
		"function" => ""
	),
);

$config['sms']['service_close']["sms_settingid"] = "1";
$config['sms']['service_close']["msg"] = "Service No.[serviceno] status has been completed. Thank you for using online service Major Development Contact Center 1266";
$config['sms']['service_close']["module"] = "ServiceRequest";
$config['sms']['service_close']["contactname"] = "request_name";
$config['sms']['service_close']["contacttel"] = "request_user_phone";
$config['sms']['service_close']["field"] = array(
	"0" => array(
			"columnmap" => "serviceno",
			"columncrm"	=> "servicerequest_no",
			"function" => ""
	),
);

$config['sms']['inspection_appointment']["sms_settingid"] = "1";
//$config['sms']['inspection_appointment']["msg"] = "นัดตรวจรับ โครงการ : [projectname] ยูนิต : [productcode] วันที่ [date] [time]";

$config['sms']['inspection_appointment']["msg"]= "[projectname] : We are delight to inform you that your unit no. [productcode] is now completed and ready to the inspection on [date] from time [time]";
$config['sms']['inspection_appointment']["module"] = "Inspection";
$config['sms']['inspection_appointment']["contactname"] = "inspect_contact";
$config['sms']['inspection_appointment']["contacttel"] = "inspect_contactphone";
$config['sms']['inspection_appointment']["field"] = array(
		"0" => array(
				"columnmap" => "projectname",
				"columncrm"	=> "cf_3550",
				"function" => ""
		),
		"1" => array(
				"columnmap" => "productcode",
				"columncrm"	=> "productcode",
				"function" => ""
		),
		"2" => array(
				"columnmap" => "date",
				"columncrm"	=> "inspection_date",
				"function" => "get_fulldate"
		),
		"3" => array(
				"columnmap" => "time",
				"columncrm"	=> "inspection_time",
				"function" => "get_fullTime"
		),
);

$config['sms']['inspection_appointment_second']["sms_settingid"] = "1";
//$config['sms']['inspection_appointment']["msg"] = "นัดตรวจรับ โครงการ : [projectname] ยูนิต : [productcode] วันที่ [date] [time]";
$config['sms']['inspection_appointment_second']["msg"]= "[projectname] : We are ready to proceed your unit no. [productcode] on the next inspection on [date] from time [time] Thank you.";
$config['sms']['inspection_appointment_second']["module"] = "Inspection";
$config['sms']['inspection_appointment_second']["contactname"] = "inspect_contact";
$config['sms']['inspection_appointment_second']["contacttel"] = "inspect_contactphone";
$config['sms']['inspection_appointment_second']["field"] = array(
		"0" => array(
				"columnmap" => "projectname",
				"columncrm"	=> "cf_3550",
				"function" => ""
		),
		"1" => array(
				"columnmap" => "productcode",
				"columncrm"	=> "productcode",
				"function" => ""
		),
		"2" => array(
				"columnmap" => "date",
				"columncrm"	=> "inspection_date",
				"function" => "get_fulldate"
		),
		"3" => array(
				"columnmap" => "time",
				"columncrm"	=> "inspection_time",
				"function" => "get_fullTime"
		),
);

$config['sms']['transfer_appointment']["sms_settingid"] = "1";
//$config['sms']['transfer_appointment']["msg"] = "นัดตรวจรับ โครงการ : [projectname] ยูนิต : [productcode] วันที่ [date] [time]";

$config['sms']['transfer_appointment']["msg"] = "[projectname] : Unit No. [productcode] Confirm ownership transfer  on date. [date] Thank you.";
$config['sms']['transfer_appointment']["module"] = "Transfer";
$config['sms']['transfer_appointment']["contactname"] = "accountname";
$config['sms']['transfer_appointment']["contacttel"] = "account_telephone";
$config['sms']['transfer_appointment']["field"] = array(
		"0" => array(
				"columnmap" => "projectname",
				"columncrm"	=> "cf_3550",
				"function" => ""
		),
		"1" => array(
				"columnmap" => "productcode",
				"columncrm"	=> "productcode",
				"function" => ""
		),
		"2" => array(
				"columnmap" => "date",
				"columncrm"	=> "unittransfer_date",
				"function" => "get_fulldate"
		),
		"3" => array(
				"columnmap" => "time",
				"columncrm"	=> "unittransfer_time",
				"function" => "get_fullTime"
		),
);

$config['sms']['reserved']["sms_settingid"] = "1";
$config['sms']['reserved']["msg"] = "Thank you for reservation [projectname] by [companyname]. Unit no. [productcode],[floorno] Floor, as your residences";
$config['sms']['reserved']["module"] = "Reservation";
$config['sms']['reserved']["contactname"] = "accountname";
$config['sms']['reserved']["contacttel"] = "mobile";
$config['sms']['reserved']["field"] = array(
		"0" => array(
				"columnmap" => "projectname",
				"columncrm"	=> "cf_3550",
				"function" => ""
		),
		"1" => array(
				"columnmap" => "productcode",
				"columncrm"	=> "productcode",
				"function" => ""
		),
		"2" => array(
				"columnmap" => "floorno",
				"columncrm"	=> "floorno",
				"function" => ""
		),
		"3" => array(
				"columnmap" => "companyname",
				"columncrm"	=> "cf_3901",
				"function" => ""
		)
	
);
$config['sms']['contract_notification']["sms_settingid"] = "1";
$config['sms']['contract_notification']["msg"] = "Reservation : [reservationno]. [projectname] Unit no. [productcode],[floorno] Floor is due to sign the agreement within [contractdate_appointment]";
$config['sms']['contract_notification']["module"] = "Reservation";
$config['sms']['contract_notification']["contactname"] = "accountname";
$config['sms']['contract_notification']["contacttel"] = "mobile";
$config['sms']['contract_notification']["field"] = array(
		"0" => array(
				"columnmap" => "projectname",
				"columncrm"	=> "cf_3550",
				"function" => ""
		),
		"1" => array(
				"columnmap" => "productcode",
				"columncrm"	=> "productcode",
				"function" => ""
		),
		"2" => array(
				"columnmap" => "floorno",
				"columncrm"	=> "floorno",
				"function" => ""
		),
		"3" => array(
				"columnmap" => "companyname",
				"columncrm"	=> "cf_3901",
				"function" => ""
		)
		,
		"4" => array(
				"columnmap" => "reservationno",
				"columncrm"	=> "reservationno",
				"function" => ""
		)
		,
		"5" => array(
				"columnmap" => "contractdate_appointment",
				"columncrm"	=> "contractdate_appointment",
				"function" => "get_fulldate"
		)
	
);

// $config['sms']['service_otp']["sms_settingid"] = "10";
// $config['sms']['service_otp']["msg"] = "คุณกำลังใช้บริการ MJD CONNECT  รหัสOTP คือ [otp_number] ";
// $config['sms']['service_otp']["module"] = "Contacts";
// $config['sms']['service_otp']["contactname"] = "firstname";
// $config['sms']['service_otp']["contacttel"] = "mobile";
// $config['sms']['service_otp']["field"] = array(
// 		"0" => array(
// 				"columnmap" => "otp_number",
// 				"columncrm"	=> "otp_number",
// 				"function" => ""
// 		)
// );

$config['sms']['service_otp']["sms_settingid"] = "1";
$config['sms']['service_otp']["msg"] = "รหัสOTP คือ [otp_number] ";
$config['sms']['service_otp']["module"] = "Accounts";
/*$config['sms']['service_otp']["accountname"] = "cf_955";
$config['sms']['service_otp']["accounttel"] = "cf_957";*/
$config['sms']['service_otp']["field"] = array(
		"0" => array(
				"columnmap" => "otp_number",
				"columncrm"	=> "otp_number",
				"function" => ""
		)
);

$config['sms']['register_otp']["sms_settingid"] = "1";
$config['sms']['register_otp']["msg"] = "รหัสOTP คือ [otp_number] ";
$config['sms']['register_otp']["module"] = "Accounts";
$config['sms']['register_otp']["field"] = array(
		"0" => array(
				"columnmap" => "otp_number",
				"columncrm"	=> "otp_number",
				"function" => ""
		)
);

$config['sms']['service_chang_password']["sms_settingid"] = "1";
$config['sms']['service_chang_password']["msg"] = "คุณกำลังใช้บริการ MJD CONNECT USERNAME: [username]  PASSWORD: [password] ";
$config['sms']['service_chang_password']["module"] = "Contacts";
$config['sms']['service_chang_password']["contactname"] = "firstname";
$config['sms']['service_chang_password']["contacttel"] = "mobile";
$config['sms']['service_chang_password']["field"] = array(
		"0" => array(
				"columnmap" => "username",
				"columncrm"	=> "username",
				"function" => ""
		),"1" => array(
				"columnmap" => "password",
				"columncrm"	=> "password",
				"function" => ""
		),
);


$config['sms']['sms_paymentgateway_TH']["sms_settingid"] = "1"; //10
$config['sms']['sms_paymentgateway_TH']["msg"] = "ท่านได้ชำระค่างวดดาวน์([UNITNO]) จำนวน [AMOUNT] บาท เรียบร้อยแล้ว บริษัทฯจะจัดส่งใบเสร็จรับเงิน ส่งไปยังที่อยู่ของท่าน ขอบคุณที่ใช้บริการ";
$config['sms']['sms_paymentgateway_TH']["module"] = "Payment";
$config['sms']['sms_paymentgateway_TH']["firstname"] = "firstname";
$config['sms']['sms_paymentgateway_TH']["lastname"] = "lastname";
$config['sms']['sms_paymentgateway_TH']["accountmobile"] = "mobile";
$config['sms']['sms_paymentgateway_TH']["field"] = array(
		"0" => array(
				"columnmap" => "UNITNO",
				"columncrm"	=> "productcode",
				"function" => ""
		),"1" => array(
				"columnmap" => "AMOUNT",
				"columncrm"	=> "amount",
				"function" => ""
		),
);

$config['sms']['sms_paymentgateway_EN']["sms_settingid"] = "1"; //10
$config['sms']['sms_paymentgateway_EN']["msg"] = "You have made payment of [AMOUNT] Baht for Installment Unit No. [UNITNO]. The receipt will be sent to your mailing address soon. Thank you.";
$config['sms']['sms_paymentgateway_EN']["module"] = "Payment";
$config['sms']['sms_paymentgateway_EN']["firstname"] = "firstname";
$config['sms']['sms_paymentgateway_EN']["lastname"] = "lastname";
$config['sms']['sms_paymentgateway_EN']["accountmobile"] = "mobile";
$config['sms']['sms_paymentgateway_EN']["field"] = array(
		"0" => array(
				"columnmap" => "UNITNO",
				"columncrm"	=> "productcode",
				"function" => ""
		),"1" => array(
				"columnmap" => "AMOUNT",
				"columncrm"	=> "amount",
				"function" => ""
		),
);

