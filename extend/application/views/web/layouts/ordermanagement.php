<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Order Management</title>
<!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<link rel="stylesheet" href="<?php  echo site_assets_url('css/node_modules/morrisjs/morris.css');?>" >
	<link rel="stylesheet" href="<?php  echo site_assets_url('css/node_modules/toast-master/css/jquery.toast.css');?>" >

	<link rel="stylesheet" href="<?php  echo site_assets_url('css/dist/css/style.min.css');?>" >

	<link href="<?php echo site_assets_url('icons/themify-icons/themify-icons.css');?>" rel="stylesheet" />
	<link href="<?php echo site_assets_url('icons/simple-line-icons/css/simple-line-icons.css');?>" rel="stylesheet" />

	<link href="<?php echo site_assets_url('font-awesome/css/font-awesome.min.css');?>" rel="stylesheet" />

	<link href="<?php echo site_assets_url('css/node_modules/select2/dist/css/select2.min.css');?>" rel="stylesheet" type="text/css" />

	<link href="<?php echo site_assets_url('css/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css');?>" rel="stylesheet" />

	<!-- Date picker plugins css -->
    <link href="<?php echo site_assets_url('css/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css');?>" rel="stylesheet" type="text/css" />

    <link href="<?php echo site_assets_url('css/node_modules/daterangepicker/daterangepicker.css');?>" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?php echo site_assets_url('css/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css');?>">

	<script src="<?php echo site_assets_url('css/node_modules/jquery/jquery-3.2.1.min.js');?>"></script>
	<!-- Bootstrap popper Core JavaScript -->
	<script src="<?php echo site_assets_url('css/node_modules/popper/popper.min.js');?>"></script>
	<script src="<?php echo site_assets_url('css/node_modules/bootstrap/js/bootstrap.min.js');?>"></script>
	<!-- slimscrollbar scrollbar JavaScript -->
	<script src="<?php echo site_assets_url('css/dist/js/perfect-scrollbar.jquery.min.js');?>"></script>

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

    <!-- Editable -->
	<script src="<?php echo site_assets_url('css/node_modules/jquery-datatables-editable/jquery.dataTables.js');?>"></script>
   
</head>

<body class="skin-default-dark fixed-layout">
	<div id="main-wrapper">

		<?php echo Modules::run('components/header/index');?>
		
		<?php echo Modules::run('components/header/left_bar');?>

		<?php echo $template['body']; ?>
	
	</div>
</body>

<style type="text/css">

	/*.skin-default-dark {
		background: #DBDBDB;
	}*/

</style>