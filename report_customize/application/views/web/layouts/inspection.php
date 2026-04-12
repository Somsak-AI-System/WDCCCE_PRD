<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $template['title']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<?php echo $template['metadata']; ?>
      <link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_assets_url('form_inspection/bootstrap.min.css'); ?>">
      <link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_assets_url('form_inspection/font-awesome.min.css'); ?>">
      <link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_assets_url('form_inspection/smartadmin-production-plugins.min.css'); ?>">
      <link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_assets_url('form_inspection/smartadmin-production.min.css'); ?>">
      <link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_assets_url('form_inspection/nhealth-skins.css'); ?>">
      <link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_assets_url('form_inspection/smartadmin-rtl.min.css'); ?>">
      <link rel="stylesheet" type="text/css" media="screen" href="<?php echo site_assets_url('form_inspection/your_style.css'); ?>">

      <link rel="stylesheet" href="<?php  echo site_assets_url('sweetalert/sweetalert2.min.css');?>">
      <script src="<?php echo site_assets_url('sweetalert/sweetalert2.all.min.js');?>"></script>

      <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
      <script>
         if (!window.jQuery) {
          document.write('<script src="<?php echo site_assets_url('form_inspection/jquery-2.1.1.min.js'); ?>" ><\/script>');
         }
      </script>
      <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
      <script>
         if (!window.jQuery.ui) {
          document.write('<script src="<?php echo site_assets_url('form_inspection/jquery-ui-1.10.3.min.js'); ?>"><\/script>');
         }
      </script>
</head>
   
   <body class="nhealth-skin" >


		<?php echo $template['body']; ?>
    

  </body>

</html>

      <script src="<?php echo site_assets_url('form_inspection/js/app.config.js'); ?>"></script>
      <script src="<?php echo site_assets_url('form_inspection/jquery.ui.touch-punch.min.js'); ?>"></script> 
      <script src="<?php echo site_assets_url('form_inspection/bootstrap.min.js'); ?>"></script>
      <script src="<?php echo site_assets_url('form_inspection/SmartNotification.min.js'); ?>"></script>
      <script src="<?php echo site_assets_url('form_inspection/jarvis.widget.min.js'); ?>"></script>
      <script src="<?php echo site_assets_url('form_inspection/jquery.easy-pie-chart.min.js'); ?>"></script>
      <script src="<?php echo site_assets_url('form_inspection/jquery.sparkline.min.js'); ?>"></script>     
      <script src="<?php echo site_assets_url('form_inspection/jquery.maskedinput.min.js'); ?>"></script>
      <script src="<?php echo site_assets_url('form_inspection/bootstrap-slider.min.js'); ?>"></script>
      <script src="<?php echo site_assets_url('form_inspection/jquery.mb.browser.min.js'); ?>"></script>
      <script src="<?php echo site_assets_url('form_inspection/fastclick.min.js'); ?>"></script>
      <script src="<?php echo site_assets_url('form_inspection/app.min.js'); ?>"></script>

      <script type="text/javascript">
      jQuery(document).ready(function() {
        $(window).scroll(function(){
            if ($(window).scrollTop() >= 300) {
                $('nav').addClass('fixed-header');
                $('nav div').addClass('visible-title');
            }
            else {
                $('nav').removeClass('fixed-header');
                $('nav div').removeClass('visible-title');
            }
        });
      });
      </script>
      <script src="<?php echo site_assets_url('form_inspection/jquery-form.min.js'); ?>"></script>
      <script src="<?php echo site_assets_url('form_inspection/form.js'); ?>"></script>

<script type="text/javascript">


