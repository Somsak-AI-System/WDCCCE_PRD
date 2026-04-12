<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $template['title']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<head>
<?php echo $template['metadata']; ?>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo site_assets_url('img/DASClientIcon.ico'); ?>" />
   <link href="<?php  echo site_assets_url('css/bootstrap.css'); ?>" rel="stylesheet" />
    <link href="<?php   echo site_assets_url('css/font-awesome.min.css'); ?>" rel="stylesheet" />
    <link href="<?php  echo site_assets_url('css/ionicons.min.css');?>" rel="stylesheet" />
    <link href="<?php  echo site_assets_url('css/datepicker/datepicker3.css');?>" rel="stylesheet" />
	<link href="<?php echo site_assets_url('css/icon.css');?>" rel="stylesheet" />
     <link href="<?php  echo site_assets_url('css/daterangepicker/daterangepicker-bs3.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/timepicker/bootstrap-timepicker.css');?>" rel="stylesheet" />
 <link href="<?php  echo site_assets_url('css/toggle-switch.css');?>" rel="stylesheet" />
    <link href="<?php  echo site_assets_url('css/morris/morris.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/colpick.css');?>" rel="stylesheet" />
 <link href="<?php  echo site_assets_url('css/bootstrap/easyui.css');?>" rel="stylesheet" />
  <link href="<?php  echo site_assets_url('css/AdminLTE.css');?>" rel="stylesheet" />
  <script src="<?php echo site_assets_url('js/jquery-1.8.3.min.js'); ?>"></script>
   <script src="<?php echo site_assets_url('js/jquery-1.10.1.min.js'); ?>"></script>

      <script src="<?php echo site_assets_url('js/jquery.easyui.min.js'); ?>"></script>
      <script src="<?php echo site_assets_url('js/datagrid-groupview.js'); ?>"></script>
      <script src="<?php echo site_assets_url('js/modernizr.js'); ?>"></script>
<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>      
     <link href="<?php  echo site_assets_url('css/jqModal.css'); ?>" rel="stylesheet" />
<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>
      
      
<style>
.no-js #loader { display: none;  }
.js #loader { display: block; position: absolute; left: 100px; top: 0; }

</style>

</head>
  <script>
$(window).load(function() {
		// Animate loader off screen
		$(".se-pre-con").fadeOut("slow");
	});
	
		function formatDMY(val,row){
    if (!val || val=='')
	  return '';
 	  var d = new Date(val);
      var str = (val.split(' '));
	  var str_date =str[0];
	  var ss = (str_date.split('-'));
         var y = parseInt(ss[0],10);
         var m = parseInt(ss[1],10);
         var d = parseInt(ss[2],10);
		 var date_ddmmyy = d+"/"+m+"/"+y;
		 
     return date_ddmmyy;

	}

</script>

 
 <body class="<? echo $this->session->userdata('user.theme');  ?>">
 
 <div id="SearchWindow" class="jqmWindow" style="height:450px; width:700px; background:#FFF;box-shadow: 2px  2px  5px  #151515;">
        <div id="jqmTitle">
            <button class="jqmClose">
               <i class="fa fa-times-circle"></i>
            </button>
            <span id="jqmTitleText">เลือกรายการโดยการดับเบิ้ลคลิกค่ะ</span>
            <hr class="truck">
        </div>
        <iframe id="jqmContent"  src="" style="overflow:hidden;height:90%;width:99%; background:#E9F0F8;" >
        </iframe>
        
   </div>

<div class="se-pre-con"></div>

			<?php echo Modules::run('components/header/index');?>
            <div class="wrapper row-offcanvas row-offcanvas-left">
			 <?php echo Modules::run('components/header/left_bar');?>
		
		 <aside class="right-side">
                    <section class="content-header">
           
                       <?php echo $template['modulename']; ?> <i class="fa  fa-caret-right"></i> <?php echo $template['screen']; ?>
                    
                </section>
          <section class="content">
						<?php echo $template['body']; ?>

		</section>
      </aside>
          </div>
      </section>

 
	<!--<script type="text/javascript" src="<?php  echo site_assets_url('js/jquery.min.js'); ?>" ></script>-->
   <script src="<?php  echo site_assets_url('js/jquery-ui-1.10.3.min.js'); ?>"></script>
   	<script type="text/javascript" src="<?php  echo site_assets_url('js/bootstrap.min.js')?>"></script>
     <script src="<?php echo site_assets_url('js/plugins/jqueryKnob/jquery.knob.js'); ?>"></script>
   <script src="<?php echo site_assets_url('js/plugins/daterangepicker/daterangepicker.js'); ?>"></script>
   <script src="<?php echo site_assets_url('js/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>      
     <script src="<?php echo site_assets_url('js/plugins/timepicker/bootstrap-timepicker.js'); ?>"></script>     
 
   <script src="<?php echo site_assets_url('js/plugins/iCheck/icheck.min.js'); ?>"></script>
 <script src="<?php echo site_assets_url('js/jquery.easyui.min.js'); ?>"></script>
   <script src="<?php echo site_assets_url('js/plugins/jqgrid/js/grid.locale-en.js'); ?>"></script>
	 <script src="<?php echo site_assets_url('js/plugins/jqgrid/js/jquery.jqGrid.js'); ?>"></script> 
    <script src="<?php echo site_assets_url('js/colpick.js'); ?>"></script> 
     <script src="<?php echo site_assets_url('js/raphael-min.js'); ?>"></script>
 <script type="text/javascript" src="<?php  echo site_assets_url('js/plugins/morris/morris.min.js')?>"></script>
 	<script src="<?php echo site_assets_url('js/piechart'); ?>"></script>
    <script type="text/javascript" src="<?php echo site_assets_url('js/datagrid-filter.js')?>"></script> 
        <script type="text/javascript" src="<?php echo site_assets_url('js/plugins/flot/jquery.flot.min.js')?>"></script> 
 <script type="text/javascript" src="<?php echo site_assets_url('js/plugins/flot/jquery.flot.pie.min.js')?>"></script> 
     
<script src="<?php echo site_assets_url('js/AdminLTE/app.js'); ?>"></script>
<script src="<?php //echo site_assets_url('js/AdminLTE/dashboard.js'); ?>"></script>
<script src="<?php  //echo site_assets_url('js/AdminLTE/demo.js'); ?>"></script>
<script src="<?php  echo site_assets_url('js/custom_function.js'); ?>"></script>

<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>

<script src="<?php echo site_assets_url('js/search/popup_search.js'); ?>"></script>

<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>

</body>
</html>