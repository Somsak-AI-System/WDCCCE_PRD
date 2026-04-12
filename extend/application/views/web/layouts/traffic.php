<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $template['title']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Expires" content="0">
 <meta http-equiv="Pragma" content="no-cache">
 <meta http-equiv="Cache-Control" content="no-cache">

<?php echo $template['metadata']; ?>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo site_assets_url('favicon.ico'); ?>" />
 <link href="<?php  echo site_assets_url('css/bootstrap.min.css'); ?>" rel="stylesheet" />     
<link href="<?php  echo site_assets_url('css/queuestyle.css');?>" rel="stylesheet" />
   <link href="<?php   echo site_assets_url('css/font-awesome.min.css'); ?>" rel="stylesheet" />
    <link href="<?php  echo site_assets_url('css/ionicons.min.css');?>" rel="stylesheet" />
 <script src="<?php echo site_assets_url('js/jquery-1.10.2.js'); ?>"></script>
     <link href="<?php  echo site_assets_url('css/morris/morris.css');?>" rel="stylesheet" />

      <link href="<?php  echo site_assets_url('css/bootstrap/easyui.css');?>" rel="stylesheet" />
        <link href="<?php echo site_assets_url('css/icon.css');?>" rel="stylesheet" />
</head>
   <body >
<?php echo $template['body']; ?>


    <script src="<?php  echo site_assets_url('js/jquery-ui-1.10.3.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php  echo site_assets_url('js/bootstrap.js')?>"></script>
     <script type="text/javascript" src="<?php  echo site_assets_url('js/plugins/flot/jquery.flot.min.js')?>"></script>
   <script type="text/javascript" src="<?php  echo site_assets_url('js/plugins/flot/jquery.flot.resize.min.js')?>"></script>
   <script type="text/javascript" src="<?php  echo site_assets_url('js/plugins/flot/jquery.flot.pie.min.js')?>"></script>
  <script type="text/javascript" src="<?php  echo site_assets_url('js/plugins/flot/jquery.flot.pie.min.js')?>"></script>
   <script type="text/javascript" src="<?php echo site_assets_url('js/plugins/canvas/canvasjs.min.js')?>"></script>    
    
    
</body>
</html>