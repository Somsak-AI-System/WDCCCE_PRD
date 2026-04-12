<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $template['title']; ?></title>
<!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<?php echo $template['metadata']; ?>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo site_assets_url('favicon.ico'); ?>" />
  	<link rel="stylesheet" href="<?php  echo site_assets_url('css/bootstrap.min.css');?>" >
    <link href="<?php echo site_assets_url('font-awesome/css/font-awesome.min.css');?>" rel="stylesheet" />
    <link href="<?php echo site_assets_url('css/ionicons.min.css'); ?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?php  echo site_assets_url('js/datatables/dataTables.bootstrap.css');?>" >
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/AdminLTE.min.css');?>" >
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/skins/_all-skins.min.css');?>" >    

</head>

<body class="hold-transition skin-yellow sidebar-mini">
 
<div class="wrapper">
			
 			<?php echo Modules::run('components/header/index');?>
			
   
           	<?php echo Modules::run('components/header/left_bar');?>
       		     
             
			<?php echo $template['body']; ?>


	  		<?php echo Modules::run('components/footer/index');?>
	
  <div class="control-sidebar-bg"></div>
</div>

	 <script src="<?php echo site_assets_url('js/jQuery/jquery-2.2.3.min.js'); ?>" ></script>
    <script src="<?php echo site_assets_url('js/bootstrap.min.js'); ?>" ></script>
   	<script src="<?php echo site_assets_url('js/datatables/jquery.dataTables.min.js'); ?>" ></script>
    <script src="<?php echo site_assets_url('js/datatables/dataTables.bootstrap.min.js'); ?>" ></script>
    <script src="<?php echo site_assets_url('js/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
  	<script src="<?php echo site_assets_url('js/fastclick/fastclick.js'); ?>"></script>
	<script src="<?php echo site_assets_url('js/app.min.js'); ?>" ></script>
    <script src="<?php echo site_assets_url('js/demo.js'); ?>"></script>    
    <script>
	  $(function () {
		//$("#datagrid").DataTable();
		$('#datagrid').DataTable({
		  "lengthMenu": [[20, 50, 100, -1], [20, 50, 100, "All"]],
		  "paging": true,
		  "lengthChange": true,
		  "searching": true,
		  "displayStart": 20,
		  "ordering": true,
		  "info": true,
		  "autoWidth": true
		});
	  });
	</script>
</body>
</html>