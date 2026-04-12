<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $template['title']; ?></title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<?php echo $template['metadata']; ?>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo site_assets_url('favicon.ico'); ?>" />
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/bootstrap.min.css');?>" >
    <link href="<?php echo site_assets_url('font-awesome/css/font-awesome.min.css');?>" rel="stylesheet" />
    <link href="<?php echo site_assets_url('css/ionicons.min.css'); ?>" rel="stylesheet" />
  	<link rel="stylesheet" href="<?php  echo site_assets_url('css/AdminLTE.min.css');?>" >
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/skins/_all-skins.min.css');?>" >
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/iCheck/flat/blue.css');?>" >
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/jvectormap/jquery-jvectormap-1.2.2.css');?>">
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/datepicker/datepicker3.css');?>">
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/daterangepicker/daterangepicker.css');?>" >
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css');?>">
    <link rel="stylesheet" href="<?php  echo site_assets_url('select2/select2.css');?>">
    <link rel="stylesheet" href="<?php  echo site_assets_url('sweetalert/sweetalert.css');?>">
    <script src="<?php echo site_assets_url('sweetalert/sweetalert2.all.min.js');?>"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="<?php echo site_assets_url('js/jquery-3.3.1.js'); ?>" ></script>

</head>

<body class="hold-transition skin-yellow sidebar-mini fixed">
 		<div class="wrapper">
      <?php echo Modules::run('components/header/index');?>
      <?php echo Modules::run('components/header/left_bar');?>
      <?php echo $template['body']; ?>
      <?php echo Modules::run('components/footer/index');?>
    </div>
    <!-- <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script> -->
    <script src="<?php echo site_assets_url('js/bootstrap.min.js'); ?>" ></script>
    <script src="<?php echo site_assets_url('js/raphael-min.js'); ?>"></script>
    <script src="<?php echo site_assets_url('js/sparkline/jquery.sparkline.min.js'); ?>" ></script>
    <script src="<?php echo site_assets_url('js/jvectormap/jquery-jvectormap-1.2.2.min.js'); ?>" ></script>
    <script src="<?php echo site_assets_url('js/jvectormap/jquery-jvectormap-world-mill-en.js'); ?>" ></script>
    <script src="<?php echo site_assets_url('js/knob/jquery.knob.js'); ?>" ></script>
    <script src="<?php echo site_assets_url('js/moment.min.js'); ?>" ></script>
    <script src="<?php echo site_assets_url('js/daterangepicker/daterangepicker.js'); ?>"></script>
    <script src="<?php echo site_assets_url('js/datepicker/bootstrap-datepicker.js'); ?>"></script>
    <script src="<?php echo site_assets_url('js/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>
    <script src="<?php echo site_assets_url('js/slimScroll/jquery.slimscroll.min.js'); ?>"></script>
    <script src="<?php echo site_assets_url('js/fastclick/fastclick.js'); ?>"></script>
    <script src="<?php echo site_assets_url('js/app.min.js'); ?>" ></script>
    <script src="<?php echo site_assets_url('js/demo.js'); ?>"></script>
    <script src="<?php echo site_assets_url('select2/select2.full.min.js'); ?>"></script>

</body>
</html>

<div id="loader">
  <div class="square-spin">
    <div><img src="<?php echo site_assets_url('images/loader.png'); ?>"  alt=""/></div>
  </div>
</div>

<button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-chevron-up"></i></button>

<style>
#loader { display: none;  }
#loader { display: block; position: absolute; left: 100px; top: 0; }
#loader {
  position: fixed;
  background: #eaeaea;
  background-color:rgba(0, 0, 0, 0.5);
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999999999;
}
@-webkit-keyframes square-spin {
    0%{
        -webkit-transform: rotate(0deg);
    }
    25% {
        -webkit-transform: rotate(20deg);
    }
    50% {
        -webkit-transform: rotate(0deg);
    }
    75% {
        -webkit-transform: rotate(-20deg);
    }
    100% {
        -webkit-transform: rotate(0deg);
    }    
}
@keyframes square-spin {
    0% {
        -webkit-transform: rotate(0deg);
    }
    25% {
        -webkit-transform: rotate(20deg);
    }
    50% {
        -webkit-transform: rotate(0deg);
    }
    75% {
        -webkit-transform: rotate(-20deg);
    }
    100% {
        -webkit-transform: rotate(0deg);
    }    
}
.square-spin {
  position: absolute;
  top: 50%;
  left: 50%;
  margin-left: -50px;
  margin-top: -40px;
}
.square-spin > div {
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
  width: 50px;
  height: 50px;
  -webkit-animation: square-spin 3s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite;
  animation: square-spin 3s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite;
  text-align:center;
  padding-top:5px;
}
</style>


<style>
#myBtn {
  display: none;
  position: fixed;
  bottom: 55px;
  right: 30px;
  z-index: 99;
  font-size: 14px;
  border: none;
  outline: none;
  background-color: #969494;
  color: white;
  cursor: pointer;
  padding: 10px;
  border-radius: 4px;
}

#myBtn:hover {
  background-color: #555;
}
</style>

<style>
  .skin-yellow .wrapper, .skin-yellow .main-sidebar, .skin-yellow .left-side{
    background-color: #ecf0f5;
  }
</style>

<script type="text/javascript">
window.onscroll = function() {scrollFunction()};
function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }
}
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}    
</script>