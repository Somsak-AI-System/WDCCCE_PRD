<?php
/** Send response to alive check request. **/
if($_REQUEST['isalive']) { echo 'true'; die; }
?>
<html>
	<head>
		<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">

		<title>vtoffline</title>
		<script type='text/javascript' src='vtwsclib/third-party/js/jquery.js'></script>
		<script type='text/javascript' src='vtwsclib/third-party/js/md5.js'></script>
		<script type='text/javascript' src='vtwsclib/Vtiger/WSClient.js'></script>

		<script type='text/javascript' src='vtwsclib/third-party/js/gears_init.js'></script>
		<script type='text/javascript' src='vtwsclib/Vtiger/Gears.js'></script>

		<script type='text/javascript' src='js/jquery-jtemplates.js'></script>
	
		<script type='text/javascript' src='js/vtoffline.lang.js'></script>
		<script type='text/javascript' src='js/vtoffline.js'></script>

		<link rel='stylesheet' href='vtoffline.css'/>
		
	</head>


	<body id='body'>

	<script>
	if (!window.google || !google.gears) {
		location.href = "http://gears.google.com/?action=install&message=Install Gears to work Offline" +
			"&name=" + app_strings.LBL_APP_TITLE +
		   "&return=" + self.location.href;
	}
	</script>

	<script type='text/javascript'>
	window.onload = vtoffline_init;
	function vtoffline_init() { 
		if(typeof(vtoffline) != 'undefined') vtoffline.IndexPage.Init(); 
		else window.setTimeout(vtoffline_init, 100);
	}
	</script>		

	</body>

</html>


