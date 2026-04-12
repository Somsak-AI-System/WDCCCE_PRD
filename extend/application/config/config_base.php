<?php
global $site_URL,$root_directory;
$config['meta']['default'] = array(
  'title' => 'MOAI Call Center',
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

$config['export']['history'] = array(
		'path' => "export/report/history/",
		'url' => $site_URL."export/report/history/",
		'prefix' => 'response-history',
		'birt_link' => 'response_history.rptdesign&__format=pdf',
		'birt_link_excel' => 'response_history.rptdesign&__format=xls'
);

$config['export']['rpt_monthly_expenses'] = array(
	'path' => "export/report/rpt_monthly_expenses/",
	'url' => $site_URL."export/report/rpt_monthly_expenses/",
	'prefix' => 'Monthly_Expenses_Report',
	'birt_link' => 'rpt_monthly_expenses.rptdesign&__format=pdf',
	'birt_link_excel' => 'rpt_monthly_expenses.rptdesign&__format=xls'
);

$config['export']['rpt_sales_forecast_summary_report'] = array(
	'path' => "export/report/rpt_sales_forecast_summary_report/",
	'url' => $site_URL."export/report/rpt_sales_forecast_summary_report/",
	'prefix' => 'Sales_Forecast_Summary_Report',
	'birt_link' => 'rpt_sales_forecast_summary_report.rptdesign&__format=pdf',
	'birt_link_excel' => 'rpt_sales_forecast_summary_report.rptdesign&__format=xls'
);

$config['export']['rpt_daily_new_project'] = array(
	'path' => "export/report/rpt_daily_new_project/",
	'url' => $site_URL."export/report/rpt_daily_new_project/",
	'prefix' => 'Daily_New_Project_Report',
	'birt_link' => 'rpt_daily_new_project.rptdesign&__format=pdf',
	'birt_link_excel' => 'rpt_daily_new_project.rptdesign&__format=xls'
);

$config['export']['rpt_sales_team_forecast_report_by_stage'] = array(
	'path' => "export/report/rpt_sales_team_forecast_report_by_stage/",
	'url' => $site_URL."export/report/rpt_sales_team_forecast_report_by_stage/",
	'prefix' => 'Sales_Team_Forecast_Report',
	'birt_link' => 'rpt_sales_team_forecast_report_by_stage.rptdesign&__format=pdf',
	'birt_link_excel' => 'rpt_sales_team_forecast_report_by_stage.rptdesign&__format=xls'
);