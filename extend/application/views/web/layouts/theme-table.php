<!DOCTYPE html>
<html lang="th">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $template['title']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php echo $template['metadata']; ?>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo site_assets_url('favicon.ico'); ?>" />
   <link href="<?php echo site_assets_url('css/bootstrap.css'); ?>" rel="stylesheet" />
    <link href="<?php   echo site_assets_url('css/font-awesome.min.css'); ?>" rel="stylesheet" />
    <link href="<?php  echo site_assets_url('css/ionicons.css');?>" rel="stylesheet" /> 
    <link href="<?php  echo site_assets_url('css/datepicker/datepicker3.css');?>" rel="stylesheet" />
    <link href="<?php  echo site_assets_url('css/daterangepicker/daterangepicker-bs3.css');?>" rel="stylesheet" />
     <link href="<?php  echo site_assets_url('css/datatables/dataTables.bootstrap.css');?>" rel="stylesheet" />
     <link href="<?php  echo site_assets_url('css/timepicker/bootstrap-timepicker.min.css');?>" rel="stylesheet" />       
    <link href="<?php  echo site_assets_url('css/AdminLTE.css');?>" rel="stylesheet" />
     <link href="<?php  echo site_assets_url('css/jqModal.css'); ?>" rel="stylesheet" />
	<script src="<?php echo site_assets_url('js/jquery-1.8.3.min.js'); ?>"></script>   
     <script src="<?php echo site_assets_url('js/jquery-1.10.2.js'); ?>"></script>
   
</head>
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
		 
			<?php echo Modules::run('components/header/index');?>
            <div class="wrapper row-offcanvas row-offcanvas-left">
			 <?php echo Modules::run('components/header/left_bar');?>
		
		 <aside class="right-side">
                    <section class="content-header">
                    <h1>
                     <?php echo $template['screen']; ?>
                       <!-- <small><?php //echo $template['metadata']['description']; ?></small>-->
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active"><?php echo $template['screen']; ?></li>
                    </ol>
                </section>
          <section class="content">
						<?php echo $template['body']; ?>

		</section>
      </aside>
          </div>
      </section>
  
 	
    <script src="<?php  echo site_assets_url('js/jquery-ui-1.10.3.min.js'); ?>"></script>
   
   	<script type="text/javascript" src="<?php  echo site_assets_url('js/bootstrap.min.js')?>"></script>

	<script src="<?php echo site_assets_url('js/raphael-min.js')?>"></script>
   
    <script src="<?php echo site_assets_url('js/plugins/sparkline/jquery.sparkline.min.js'); ?>"></script>
        
     <script src="<?php echo site_assets_url('js/plugins/daterangepicker/daterangepicker.js'); ?>"></script>
      
   <script src="<?php echo site_assets_url('js/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>  
  
    <script src="<?php echo site_assets_url('js/plugins/input-mask/jquery.inputmask.js'); ?>"></script> 
<script src="<?php echo site_assets_url('js/plugins/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>

<script src="<?php echo site_assets_url('js/plugins/input-mask/jquery.inputmask.extensions.js'); ?>"></script>
 <script src="<?php echo site_assets_url('js/plugins/timepicker/bootstrap-timepicker.js'); ?>" type="text/javascript"></script>

    
	<script src="<?php echo site_assets_url('js/AdminLTE/app.js'); ?>"></script>
    

<script src="<?php echo site_assets_url('js/jqModal.js'); ?>" type="text/javascript"></script>

<script src="<?php echo site_assets_url('js/search/popup_search.js'); ?>"></script>
 
<script src="<?php echo site_assets_url('js/plugins/datatables/jquery.dataTables.js'); ?>"></script>

<script src="<?php echo site_assets_url('js/plugins/datatables/dataTables.bootstrap.js'); ?>"></script> 
 <script src="<?php echo site_assets_url('js/jquery.dataTables.rowGrouping.js'); ?>"></script>   

  <script src="<?php echo site_assets_url('js/jquery.PrintArea.js'); ?>"></script>


</body>
</html>