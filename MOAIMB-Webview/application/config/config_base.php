<?php
$config['meta']['default'] = array(
  'title' => 'GELE',
  'keyword' => ' ',
  'description' => ''
);

$config['lang'] = 'english';
$config['limit'] = '10';


$config['log']['status'] = true;
$config['log']['config'] = array(
	"get" => array(
		"param" => true,
		"response" => false,
	),
	"save" => array(
		"param" => true,
		"response" => false,
	),
	"delete" => array(
			"param" => true,
			"response" => false,
	),
	"field" => array(
		"param" => false,
		"response" => false,
	),
	"allocate" => array(
			"param" => true,
			"response" => false,
	),
	"release" => array(
			"param" => true,
			"response" => false,
	),
	"stored" => array(
			"param" => true,
			"response" => false,
	),
	"check" => array(
			"param" => true,
			"response" => true,
	),
	"test" => array(
			"param" => true,
			"response" => true,
	),
	"create" => array(
			"param" => true,
			"response" => true,
	),
	"so" => array(
			"param" => true,
			"response" => true,
	),
	"cancel" => array(
			"param" => true,
			"response" => true,
	),
	"update" => array(
			"param" => true,
			"response" => true,
	),
	"re" => array(
			"param" => true,
			"response" => true,
	),
	"import" => array(
			"param" => true,
			"response" => true,
	),
	"wip" => array(
			"param" => true,
			"response" => true,
	),
	"export" => array(
			"param" => true,
			"response" => true,
	)
);
$config['export']['path'] = "export/";
$config['export']['string'] = array("Item_Code","itemcd","Routing_Code","Car_Number","Date","opdt","Last_Update_Date","Effect_Date"
	,"Order_No","Operation_No","Item Code","Order_NO","Component_Code"
);
$config['export_report']['report'] = array(
		'path' => "D:\AppServ\www\PATPPC\export\defect",
		'url' => "http://172.16.64.185:8090/PATPPC/export/defect/",
		'extension' => 'xls',
);
$config['export_excel2007'] = array(
		'path' => "D:\AppServ\www\PATPPC\export\data",
		'url' => "http://172.16.64.185:8090/PATPPC/export/data/",
		'extension' => 'xlsx',
		'master_service' => 'Export_Masters',
);
$config['export_excel2003'] = array(
		'path' => "D:\AppServ\www\PATPPC\export\data",
		'url' => "http://172.16.64.185:8090/PATPPC/export/data/",
		'extension' => 'xls',
);
$config['exportwip']['so'] = array(
		'path' => "D:\AppServ\www\PATPPC\pdf",
		'path_web' => 'pdf',
		'url' => "http://172.16.64.185:8090/PATPPC/pdf/",
		'prefix' => 'wip_process_',
		'extension' => 'xls'
		);
$config['exportwip']['Forming'] = array(
		'path' => "D:\AppServ\www\PATPPC\pdf",
		'path_web' => 'pdf',
		'url' => "http://172.16.64.185:8090/PATPPC/pdf/",
		'prefix' => 'follow_wip_forming_',
		'extension' => 'xls',
		'path_program' => 'WebService\ProductionService\WipFollow\FollowWip.exe',
);

$config['exportwip']['Casting'] = array(
		'path' => "D:\AppServ\www\PATPPC\pdf",
		'path_web' => 'pdf',
		'url' => "http://172.16.64.185:8090/PATPPC/pdf/",
		'prefix' => 'follow_wip_casting_',
		'extension' => 'xls',
		'path_program' => 'WebService\ProductionService\WipFollow\FollowWip.exe',
);

$config['run']['wipbalance'] = array(
		'path' => 'WebService\ProductionService\WipBalance\WipBalance.exe',
);