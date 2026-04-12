<?php
global $site_URL, $public_path;
$config['meta']['default'] = array(
  'title' => 'MOAI',
  'keyword' => ' ',
  'description' => ''
);

// $config['lang'] = 'english';
$config['lang']['EN']	= 'english';
$config['lang']['TH']	= 'thailand';
$config['url_old'] = array('http://localhost:8090/MOAISTD',"http://ocgp.moai-crm.com/",'http://localhost:8090/MOAISTD',"http://ocgp.moai-crm.com/");

$config['url_new']  = $public_path;
$config['url_web']  = $public_path."callcenter/webvoucher";

// $config['url_new']  = "http://192.168.0.24/qbio/";
$config['export']['contact']['path'] = "../export";
$config['export']['contact']['ext'] = ".pdf";


// $config['lang'] = 'english';
$config['lang']['EN']	= 'english';
$config['lang']['TH']	= 'thailand';
$config['export']['path'] = "export/";
$config['export']['string'] = array("Mobile","Telephone","Fax");

$config['export_excel'] = array(
	//'path' => $root_directory."export\\",
	'url' => $site_URL."export/",
	'extension' => 'xls'
);

$config['export']['weeklyplan'] = array(
	'path' => "export/report/weeklyplan/",
	'url' => $site_URL."export/report/weeklyplan/",
	'prefix' => 'weeklyplan',
	'birt_link' => 'rpt_monthly_report.rptdesign&__format=pdf',
	'birt_link_excel' => 'rpt_monthly_report.rptdesign&__format=xls'
);

$config['export']['weeklyreport'] = array(
	'path' => "export/report/weeklyreport/",
	'url' => $site_URL."export/report/weeklyreport/",
	'prefix' => 'weeklyreport',
	'birt_link' => 'rpt_daily_report.rptdesign&__format=pdf',
	'birt_link_excel' => 'rpt_daily_report.rptdesign&__format=xls'
);

$config['export']['monthlyplan'] = array(
	'path' => "export/report/monthlyplan/",
	'url' => $site_URL."export/report/monthlyplan/",
	'prefix' => 'monthlyplan',
	'birt_link' => 'rpt_monthly_report.rptdesign&__format=pdf',
	'birt_link_excel' => 'rpt_monthly_report.rptdesign&__format=xls'
);

$config['export']['jobreport'] = array(
	// 'path' => "export/report/jobreport/",
	'path' => "export/report/job_report/",
	'url' => $site_URL."export/report/job_report/",
	// 'url' => $site_URL."export/report/jobreport/",
	'prefix' => 'jobreport',
	'birt_link' => 'rpt_job_report.rptdesign&__format=pdf',
	'birt_link_excel' => 'rpt_job_report.rptdesign&__format=xls'
);

$config['export']['quotation_report'] = array(
	'path' => "export/report/quotation_report/",
	'url' => $site_URL . "export/report/quotation_report/",
	'prefix' => 'quotation_report',
	'birt_link' => 'rpt_quotation.rptdesign&__format=pdf',
	'birt_link_excel' => 'rpt_quotation.rptdesign&__format=xls'
);

$config['export']['quotation_temp_report'] = array(
	'path' => "export/report/quotation_report/",
	'url' => $site_URL . "export/report/quotation_report/",
	'prefix' => 'quotation_report',
	'birt_link' => 'rpt_quotation_temp.rptdesign&__format=pdf',
	'birt_link_excel' => 'rpt_quotation_temp.rptdesign&__format=xls'
);