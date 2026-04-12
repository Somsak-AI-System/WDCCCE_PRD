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
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/AdminLTE.min.css');?>" >
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/skins/_all-skins.min.css');?>" >
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/iCheck/flat/blue.css');?>" >
    <!-- <link rel="stylesheet" href="<?php  //echo site_assets_url('css/morris/morris.css');?>" > -->
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/jvectormap/jquery-jvectormap-1.2.2.css');?>">
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/datepicker/datepicker3.css');?>">
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/daterangepicker/daterangepicker.css');?>" >
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css');?>">
    <link rel="stylesheet" href="<?php  echo site_assets_url('select2/select2.css');?>">
    <link rel="stylesheet" href="<?php  echo site_assets_url('css/timepicker/bootstrap-timepicker.min.css');?>">
    <link rel="stylesheet" href="<?php  echo site_assets_url('sweetalert/sweetalert2.min.css');?>">
    <script src="<?php echo site_assets_url('sweetalert/sweetalert2.all.min.js');?>"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="<?php echo site_assets_url('js/jquery-3.3.1.js'); ?>" ></script>

   

</head>



<body class="hold-transition skin-yellow sidebar-mini fixed">

<button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-chevron-up"></i></button>

<div class="wrapper">
      
      <?php echo Modules::run('components/header/index');?>
      <?php echo Modules::run('components/header/left_bar');?>
      <?php echo $template['body']; ?>
      <?php echo Modules::run('components/footer/index');?>
  
  <div class="control-sidebar-bg"></div>
</div>  
    <!-- <script src="<?php echo site_assets_url('js/jQuery/jquery-2.2.3.min.js'); ?>" ></script> -->
    <!-- <script src="<?php echo site_assets_url('js/jquery-ui.min.js'); ?>"></script> -->
    <script>
      //$.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="<?php echo site_assets_url('js/bootstrap.min.js'); ?>" ></script>
    <script src="<?php echo site_assets_url('js/raphael-min.js'); ?>"></script>
    <script src="<?php echo site_assets_url('js/raphael-min.js'); ?>"></script>
    
    <!-- <script src="<?php //echo site_assets_url('js/morris/morris.min.js'); ?>" ></script> -->
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
    <!-- <script src="<?php //echo site_assets_url('js/pages/dashboard.js'); ?>" ></script> -->
    <script src="<?php echo site_assets_url('js/demo.js'); ?>"></script>
    <script src="<?php echo site_assets_url('css/timepicker/bootstrap-timepicker.min.js'); ?>"></script>  
    <script src="<?php echo site_assets_url('select2/select2.full.min.js'); ?>"></script>
    
<div id="loader">
  <div class="square-spin">
    <div><img src="<?php echo site_assets_url('images/loader.png'); ?>"  alt=""/></div>
  </div>
</div>

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

.modal-confirm {    
  color: #636363;
  width: 400px;
}
.modal-confirm .modal-content {
  padding: 20px;
  border-radius: 5px;
  border: none;
      text-align: center;
  font-size: 14px;
}
.modal-confirm .modal-header {
  border-bottom: none;   
  position: relative;
}
.modal-confirm h4 {
  text-align: center;
  font-size: 26px;
  margin: 30px 0 -10px;
}
.modal-confirm .close {
      position: absolute;
  top: -5px;
  right: -2px;
}
.modal-confirm .modal-body {
  color: #999;
}
.modal-confirm .modal-footer {
  border: none;
  text-align: center;   
  border-radius: 5px;
  font-size: 13px;
  padding: 10px 15px 25px;
}
.modal-confirm .modal-footer a {
  color: #999;
}   
.modal-confirm .icon-box {
  width: 80px;
  height: 80px;
  margin: 0 auto;
  border-radius: 50%;
  z-index: 9;
  text-align: center;
  border: 3px solid #f15e5e;
}
.modal-confirm .icon-box i {
  color: #f15e5e;
  font-size: 46px;
  display: inline-block;
  margin-top: 13px;
}
.modal-confirm .btn {
  color: #fff;
  border-radius: 4px;
  background: #60c7c1;
  text-decoration: none;
  transition: all 0.4s;
  line-height: normal;
  min-width: 120px;
  border: none;
  min-height: 40px;
  border-radius: 3px;
  margin: 0 5px;
  outline: none !important;
}
.modal-confirm .btn-info {
  background: #c1c1c1;
}
.modal-confirm .btn-info:hover, .modal-confirm .btn-info:focus {
    background: #a8a8a8;
}
.modal-confirm .btn-danger {
    background: #f15e5e;
}
.modal-confirm .btn-danger:hover, .modal-confirm .btn-danger:focus {
    background: #ee3535;
}
</style>


<script type="text/javascript">
$(function () {
  //Initialize Select2 Elements
  //$('#loader').fadeOut();
  $(".select2").select2();
  $('#datepicker').datepicker({
      autoclose: true
  });
  
  $('#daterange-btn').daterangepicker(
      {
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
      },
      function (start, end) {
        /*$('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));*/
        $('#daterange-btn span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));

      }
  );
 $(".timepicker").timepicker({
      showInputs: false,
      showMeridian: false,    
      //format: 'HH:mm'
  });

});

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}   
</script>

</body>
</html>