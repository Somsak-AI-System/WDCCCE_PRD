<?php
global $site_URL,$root_directory;
$config['meta']['default'] = array(
  'title' => 'MJDP',
  'keyword' => ' ',
  'description' => ''
);

$config['lang'] = 'english';
$config['export']['path'] = "export/";
$config['export']['string'] = array("Mobile","Telephone","Fax");

$config['export_excel'] = array(
		'path' => $root_directory."export\\",
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