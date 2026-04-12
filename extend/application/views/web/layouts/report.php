<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $template['title']; ?></title>
<!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo site_assets_url('favicon.ico'); ?>" />
	<link rel="stylesheet" href="<?php  echo site_assets_url('css/dist/css/style.min.css');?>" >
	<script src="<?php echo site_assets_url('css/node_modules/jquery/jquery-3.2.1.min.js');?>"></script>
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
	<!-- Bootstrap popper Core JavaScript -->
	<script src="<?php echo site_assets_url('css/node_modules/popper/popper.min.js');?>"></script>
	<script src="<?php echo site_assets_url('css/node_modules/bootstrap/js/bootstrap.min.js');?>"></script>
	<!-- slimscrollbar scrollbar JavaScript -->
	<!--Wave Effects -->
	<script src="<?php echo site_assets_url('css/dist/js/waves.js');?>"></script>
	<!--Menu sidebar -->
	<script src="<?php echo site_assets_url('css/dist/js/sidebarmenu.js');?>"></script>
	<!--Custom JavaScript -->
	<script src="<?php echo site_assets_url('css/dist/js/custom.min.js');?>"></script>
	<!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--morris JavaScript -->
	<script src="<?php echo site_assets_url('css/node_modules/raphael/raphael.min.js');?>"></script>
	<script src="<?php echo site_assets_url('css/node_modules/morrisjs/morris.min.js');?>"></script>
	<script src="<?php echo site_assets_url('css/node_modules/jquery-sparkline/jquery.sparkline.min.js');?>"></script>

	<!--c3 JavaScript -->
	<script src="<?php echo site_assets_url('css/node_modules/d3/d3.min.js');?>"></script>
	<script src="<?php echo site_assets_url('css/node_modules/c3-master/c3.min.js');?>"></script>

	<!-- Popup message jquery -->
	<script src="<?php echo site_assets_url('css/node_modules/toast-master/js/jquery.toast.js');?>"></script>

	<!-- Date Picker Plugin JavaScript -->
    <script src="<?php echo site_assets_url('css/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js');?>"></script> 

    <script src="<?php echo site_assets_url('css/node_modules/select2/dist/js/select2.full.min.js');?>" type="text/javascript"></script>

    <script src="<?php echo site_assets_url('css/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js');?>" type="text/javascript"></script>

    <script src="<?php echo site_assets_url('css/node_modules/daterangepicker/daterangepicker.js');?>"></script>

    <!--Kendo-->
    <script src="<?php echo site_url('assets/kendoui/js/jszip.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/kendoui/js/kendo.all.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/js/kendo-ui-custom.js'); ?>"></script>

    <script src="<?php echo site_url('assets/js/main.js'); ?>"></script>
    <script src="<?php echo site_url('assets/js/main-script.js'); ?>"></script>

    <script src="<?php echo site_url('assets/js/jquery-clockpicker.min.js'); ?>"></script>

    <link rel="stylesheet" href="<?php  echo site_assets_url('sweetalert/sweetalert2.min.css');?>">
    <script src="<?php echo site_assets_url('sweetalert/sweetalert2.all.min.js');?>"></script>
    <script src="<?php echo site_assets_url('js/moment.min.js'); ?>" ></script>
    <script src="<?php echo site_url('assets/css/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script>

    <script src="<?php echo site_url('assets/js/bootstrap-datetimepicker-0.0.11/js/bootstrap-datetimepicker.min.js'); ?>"></script>

    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="<?php echo site_assets_url('css/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js');?>"></script>
    <link rel="stylesheet" href="<?php echo site_assets_url('css/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css');?>">

    <link rel="stylesheet" href="<?php echo site_assets_url('css/dist/css/pages/timeline-vertical-horizontal.css');?>">

</head>

<body class="skin-default-dark fixed-layout">
	<div id="main-wrapper">

		<?php echo Modules::run('components/header/index');?>
				
		<?php echo $template['body']; ?>
	
	</div>



</body>
</html>
<style type="text/css">

	

	html, body {
		/*font-size: 11px;*/
	}

	body {
        background-color: #fff;
        /*overflow: hidden;*/
        height: 100%;
        width: 100%;
        /*overflow: hidden;*/
    }

    #main-wrapper{
    	background-color: #fff;
    }

    .page-wrapper {
    	background-color: #fff;
        min-height: 1000px;
    }

</style>

<script>
	var site_url = function(url){
		url = url!=undefined ? url:'';
		return '<?php echo site_url(); ?>'+url;
	}
</script>