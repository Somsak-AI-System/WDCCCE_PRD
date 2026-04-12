<?php
include_once("../config.inc.php");
	$config['url'] = $report_viewer_url;


if($_REQUEST['type'] == 'service'){
	$report = $config['url'].'rpt_job_report.rptdesign&jobid='.$_REQUEST['jobid'].'&__format=pdf';
}else if ($_REQUEST['type'] == 'serial'){
	$report = $config['url'].'rpt_issue_productserial.design&jobid='.$_REQUEST['jobid'];
}else if ($_REQUEST['type'] == 'sparepart'){
	$report = $config['url'].'rpt_issue_sparepart.design&jobid='.$_REQUEST['jobid'];
}else if ($_REQUEST['type'] == 'Requisition'){
    $report = $config['url'].'rpt_requisition.rptdesign&jobid='.$_REQUEST['jobid'].'&__format=pdf';
}else if ($_REQUEST['type'] == 'CASH'){
    $report = $config['url'].'rpt_cash_cover.rptdesign&dealid='.$_REQUEST['dealid'].'&__format=pdf';
}else if ($_REQUEST['type'] == 'CONTRACT'){
    $report = $config['url'].'rpt_contract_cover.rptdesign&dealid='.$_REQUEST['dealid'].'&__format=pdf';
}



?>
<script>
	window.location.replace("<?php echo $report ?>");
</script>