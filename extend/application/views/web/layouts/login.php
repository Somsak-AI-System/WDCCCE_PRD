<!DOCTYPE html>
<html class="bg-black">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $template['title']; ?></title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<?php echo $template['metadata']; ?>


<link rel="shortcut icon" type="image/x-icon" href="<?php echo site_assets_url('favicon.ico'); ?>" />
    <link href="<?php  echo site_assets_url('css/bootstrap.css'); ?>" rel="stylesheet" />
    <link href="<?php   echo site_assets_url('css/font-awesome.min.css'); ?>" rel="stylesheet" />
    <link href="<?php  echo site_assets_url('css/login.css');?>" rel="stylesheet" />
	<link href="<?php echo site_assets_url('css/bootstrap/easyui.css');?>" rel="stylesheet" />
   
  
  
</head>
  <body class="bg-black">
  <?php echo $template['body']; ?>	



</body>
</html>

<div id="loader" style="display: none;">
  <div class="square-spin">
    <div><img src="<?php echo site_assets_url('images/loader.png'); ?>"  alt=""/></div>
  </div>
</div>


    <script src="<?php echo site_assets_url('js/jquery-1.10.2.js'); ?>"></script> 
    <script src="<?php echo site_assets_url('js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo site_assets_url('js/jquery-ui-1.10.4.custom.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo site_assets_url('js/jquery.easyui.min.js'); ?>"></script>
   
 <style type="text/css">
 	/*#loader {display: none;}*/
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
	  /*opacity: 0.5;*/
	}
	.square-spin > div {
	  -webkit-animation-fill-mode: both;
	  animation-fill-mode: both;
	  width: 50px;
	  height: 50px;
	  -webkit-animation: square-spin 3s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite;
	  /*animation: square-spin 3s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite;*/
	  animation: square-spin 3s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite;
	  text-align:center;
	  padding-top:5px;
	  /*opacity: 0.5;*/
	}

 </style> 
    <!--<script src="<?php //echo site_assets_url('js/2.0.2/jquery.min.js'); ?>"></script> -->  
  
<script type="text/javascript">
$(document).ready(function() {
	
	$( "#signupForm" ).submit(function( event ) {
		
	  if($("#username" ).val() == ''){
	  	//alert("กรุณากรอก User Name" );
		$.messager.alert('Message','กรุณากรอก User Name');
		$("#username" ).focus()
	 	event.preventDefault();
		return false;
	  }
	 
	 if($("#password" ).val() == ''){
	  	//alert("กรุณากรอก Password" );
		$.messager.alert('Message','กรุณากรอก Password');
		$("#password" ).focus()
	 	event.preventDefault();
		return false;
	  }

	});
 
});
  
</script>
