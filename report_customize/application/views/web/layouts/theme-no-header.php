<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $template['title']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
<?php echo $template['metadata']; ?>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo site_assets_url('favicon.ico'); ?>" />
<link href="<?php  echo site_assets_url('css/bootstrap.min.css'); ?>" rel="stylesheet" />
    <link href="<?php   echo site_assets_url('css/font-awesome.min.css'); ?>" rel="stylesheet" />
    <link href="<?php  echo site_assets_url('css/ionicons.min.css');?>" rel="stylesheet" />
    <link href="<?php  echo site_assets_url('css/datatables/dataTables.bootstrap.css');?>" rel="stylesheet" />
  
   <link href="<?php  echo site_assets_url('css/AdminLTE.css');?>" rel="stylesheet" />
  <!-- <link href="<?php  //echo site_assets_url('css/custom_blank.css');?>" rel="stylesheet" />-->
         <script src="<?php echo site_assets_url('js/jquery-1.8.3.min.js'); ?>"></script>
       <script src="<?php echo site_assets_url('js/jquery-1.10.2.js'); ?>"></script>
</head>
   <body class="<? echo $this->session->userdata('user.theme');  ?>">
     
          <section class="content">
						<?php echo $template['body']; ?>
			</section>
   

</body>
</html>
    <script src="<?php  echo site_assets_url('js/jquery-ui-1.10.3.min.js'); ?>"></script>
   	<script type="text/javascript" src="<?php  echo site_assets_url('js/bootstrap.min.js')?>"></script>
    <script src="<?php echo site_assets_url('js/plugins/datatables/jquery.dataTables.js'); ?>"></script>
<script src="<?php echo site_assets_url('js/plugins/datatables/dataTables.bootstrap.js'); ?>"></script> 
 <script src="<?php echo site_assets_url('js/jquery.dataTables.rowGrouping.js'); ?>"></script>   