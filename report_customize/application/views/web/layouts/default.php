<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $template['title']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php echo $template['metadata']; ?>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo site_assets_url('favicon.ico'); ?>" />
    <link href="<?php  echo site_assets_url('css/bootstrap.min.css'); ?>" rel="stylesheet" />
    <link href="<?php   echo site_assets_url('css/bootstrap-reset.css'); ?>" rel="stylesheet" />
    <link href="<?php  echo site_assets_url('font-awesome/css/font-awesome.css');?>" rel="stylesheet" />
	<link href="<?php echo site_assets_url('css/ui-lightness/jquery-ui-1.10.4.custom.min.css'); ?>" rel="stylesheet" />    	
    
	<link href="<?php echo site_assets_url('css/table-responsive.css'); ?>" rel="stylesheet" />
	
    <link href="<?php  echo site_assets_url('css/style.css');  ?>" rel="stylesheet" />
	<link href="<?php  echo site_assets_url('css/style-responsive.css');  ?>" rel="stylesheet" />
	
    <link href="<?php echo site_assets_url('css/bootstrap/easyui.css');?>" rel="stylesheet" />
    <link href="<?php echo site_assets_url('css/ui.jqgrid.css'); ?>" rel="stylesheet" />
     <link href="<?php echo site_assets_url('css/ui-red/jquery-ui.css');?>" rel="stylesheet" />
    <link href="<?php  echo site_assets_url('css/icon.css');?>" rel="stylesheet" />
	<link href="<?php  echo site_assets_url('css/ribbon.css');?>" rel="stylesheet" />
     <link href="<?php  echo site_assets_url('css/customize-template.css');  ?>" rel="stylesheet" />
    <link href="<?php  echo site_assets_url('css/custom.css');  ?>" rel="stylesheet" />
   <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
   <script src="<?php echo site_assets_url('js/html5shiv.js'); ?>"></script>
   <script src="<?php echo site_assets_url('js/respond.min.js'); ?>"></script>
    <![endif]-->
      <script src="<?php echo site_assets_url('js/jquery-1.8.3.min.js'); ?>"></script>

</head>
  <body class="full-width">
	  <section id="container" >
	  
		
			<?php echo Modules::run('components/header/index');?>
			 <?php //echo Modules::run('components/header/left_bar');?>
		
		 <section id="main-content">
          <section class="wrapper">
           <div class="row">
           		<div class="col-lg-12">
           			<section class="panel">
           				<?php echo Modules::run('components/header/toolbar');?>
           			</section>
           		</div>
           </div>
           <div class="main">       
           <div class="row">
                  <div class="col-lg-12">
                      <section class="panel">
						<?php echo $template['body']; ?>
			  			
                      </section>
                  </div>
              </div>
           </div>    
		 <!-- page end-->
          </section>
      </section>
	<?php echo Modules::run('components/footer/index');?>
	</section>
   
    <script src="<?php  echo site_assets_url('js/bootstrap.min.js'); ?>"></script>
   	<script class="include" type="text/javascript" src="<?php  echo site_assets_url('js/jquery.dcjqaccordion.2.7.js')?>"></script>
   	<script type="text/javascript" src="<?php  echo site_assets_url('js/hover-dropdown.js')?>"></script>
   	<script src="<?php echo site_assets_url('js/jquery.scrollTo.min.js'); ?>"></script>
   	<script src="<?php echo site_assets_url('js/jquery.nicescroll.js'); ?>" type="text/javascript"></script>
   	<script src="<?php echo site_assets_url('js/jquery-ui-1.10.4.custom.min.js'); ?>" type="text/javascript"></script>
  	<script src="<?php echo site_assets_url('js/jquery.ui.touch-punch.min.js'); ?>"></script>
	<script src="<?php echo site_assets_url('js/respond.min.js'); ?>"></script>
    <script src="<?php echo site_assets_url('js/jquery.easyui.min.js'); ?>"></script>
	<script src="<?php echo site_assets_url('js/jquery.jqGrid.min.js'); ?>"></script>
	<script src="<?php echo site_assets_url('js/grid.locale-en.js'); ?>"></script>
	<script src="<?php echo site_assets_url('js/jquery.contextmenu.js'); ?>"></script>
	<script src="<?php echo site_assets_url('js/jquery.ribbon.js'); ?>"></script>
    <!--common script for all pages-->
    
    <script src="<?php echo site_assets_url('js/common-scripts.js'); ?>"></script>
	<script src="<?php echo site_assets_url('js/my_function.js'); ?>"></script>
</body>
</html>