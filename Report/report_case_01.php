<?php
include_once("../config.inc.php");
	$config['url'] = $report_viewer_url;

?>
<script>
	//window.location.replace("<?php //echo $config['url'];?>/report-viewer/frameset?__report=rpt12_claim.rptdesign&__showtitle=false&crmid=<?//=$_REQUEST['aicrm']?>");
	window.location.replace("<?php echo $config['url'];?>rpt_case_report.rptdesign&ticketid=<?=$_REQUEST['aicrm']?>");
</script>