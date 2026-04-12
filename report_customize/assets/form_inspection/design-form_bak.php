<!DOCTYPE html>
<html lang="en-us" >
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title> ตั้งค่าแบบฟอร์มข้อมูล : E-Certificate</title>
		<meta name="description" content="">
		<meta name="author" content="">

		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="http://203.107.236.212/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="http://203.107.236.212/css/font-awesome.min.css">

		<!-- SmartAdmin Styles : Caution! DO NOT change the order -->
		<link rel="stylesheet" type="text/css" media="screen" href="http://203.107.236.212/css/smartadmin-production-plugins.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="http://203.107.236.212/css/smartadmin-production.min.css">
		<!--<link rel="stylesheet" type="text/css" media="screen" href="http://203.107.236.212/css/smartadmin-skins.min.css">-->
        <link rel="stylesheet" type="text/css" media="screen" href="http://203.107.236.212/css/nhealth-skins.css">
		
        
		<!-- SmartAdmin RTL Support is under construction-->
		<link rel="stylesheet" type="text/css" media="screen" href="http://203.107.236.212/css/smartadmin-rtl.min.css">

		<!-- We recommend you use "your_style.css" to override SmartAdmin
		     specific styles this will also ensure you retrain your customization with each SmartAdmin update.
		<link rel="stylesheet" type="text/css" media="screen" href="http://203.107.236.212/css/your_style.css"> -->

		<link rel="stylesheet" type="text/css" media="screen" href="http://203.107.236.212/css/your_style.css">

		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<!--<link rel="stylesheet" type="text/css" media="screen" href="http://203.107.236.212/css/demo.min.css">-->

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="http://203.107.236.212/img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="http://203.107.236.212/img/favicon/favicon.ico" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

		<!-- Specifying a Webpage Icon for Web Clip
			 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
		<!--<link rel="apple-touch-icon" href="http://203.107.236.212/img/splash/sptouch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="http://203.107.236.212/img/splash/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="http://203.107.236.212/img/splash/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="http://203.107.236.212/img/splash/touch-icon-ipad-retina.png">-->

		<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
		<!--<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">-->

		<!-- Startup image for web apps -->
		<link rel="apple-touch-startup-image" href="http://203.107.236.212/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
		<link rel="apple-touch-startup-image" href="http://203.107.236.212/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
		<link rel="apple-touch-startup-image" href="http://203.107.236.212/img/splash/iphone.png" media="screen and (max-device-width: 320px)">
		        
		<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="http://203.107.236.212/js/libs/jquery-2.1.1.min.js"><\/script>');
			}
		</script>

		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="http://203.107.236.212/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>
                
	</head>
	<body class="nhealth-skin" >

		<!-- POSSIBLE CLASSES: minified, fixed-ribbon, fixed-header, fixed-width
			 You can also add different skin classes such as "smart-skin-1", "smart-skin-2" etc...-->
						<!-- HEADER -->
				<header id="header">
					<div id="logo-group">
						<!-- PLACE YOUR LOGO HERE -->
						<!--<span id="logo"> <img src="http://203.107.236.212/img/logo.png" alt="E-Certificate"> </span>-->
                        <span id="logo"><img src="http://203.107.236.212/img/nlogo3.png" /></span>
						<!-- END LOGO PLACEHOLDER -->                   
					</div>
					                                        
					<!-- projects dropdown -->
					<div class="project-context hidden-xs">
						<h1 style="font-size:150%; color:#EEEEEE; text-shadow:none;">
                                                	DASHBOARD
                                                </h1>
					</div>
					<!-- end projects dropdown -->

					<!-- pulled right: nav area -->
					<div class="pull-right">
						
                        <!-- logout button -->
						<div id="logout" class="btn-header transparent pull-right">
							<span> <a href="http://203.107.236.212/?page=logout" title="ออกจากระบบ"><i class="fa fa-sign-out"></i></a> </span>
						</div>
						<!-- end logout button -->
                        
						<!-- collapse menu button -->
						<div id="hide-menu" class="btn-header pull-right">
							<span> <a href="javascript:void(0);" title="Collapse Menu" data-action="toggleMenu"><i class="fa fa-reorder"></i></a> </span>
						</div>
						<!-- end collapse menu -->

						<!-- fullscreen button -->
						<div id="fullscreen" class="btn-header transparent pull-right">
							<span> <a href="javascript:void(0);" title="Full Screen" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i></a> </span>
						</div>
						<!-- end fullscreen button -->
                        
                       	                        
                                    
					</div>
					<!-- end pulled right: nav area -->

				</header>
				<!-- END HEADER -->
				
                                
				<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<aside id="left-panel">

			<!-- User info -->
			<div class="login-info">
				<span> <!-- User image size is adjusted inside CSS, it should stay as is --> 
					
					<a href="http://203.107.236.212/?page=profile&id=501" id="show-shortcut">
						<span id="show-pic-box"><img src="http://203.107.236.212/img/avatars/user.png"></span>
						<span>
							AI						</span>
					</a> 
					
				</span>
			</div>
			<!-- end user info -->

			<!-- NAVIGATION : This navigation is also responsive

			To make this navigation dynamic please make sure to link the node
			(the reference to the nav > ul) after page load. Or the navigation
			will not initialize.
			-->
			<nav>
				<!-- NOTE: Notice the gaps after each icon usage <i></i>..
				Please note that these links work a bit different than
				traditional hre="" links. See documentation for details.
				-->
				<ul><li><a
				href="http://203.107.236.212/?page=center"
				
				title="กลับเมนูหลัก"
				>
					<i class="fa fa-lg fa-fw fa-mail-reply"></i>
					<span class="menu-item-parent">กลับเมนูหลัก </span>
					
				</a></li><li><a
				href="#"
				
				title="Admin"
				>
					<i class="fa fa-lg fa-fw fa-gear"></i>
					<span class="menu-item-parent">Admin </span>
					
				</a><ul><li><a
				href="http://203.107.236.212/?page=admin-hospitals"
				
				title="โรงพยาบาล/บริษัท"
				>
					
					โรงพยาบาล/บริษัท 
					
				</a></li><li class="active"><a
				href="http://203.107.236.212/?page=admin-forms"
				
				title="ฟอร์มข้อมูล"
				>
					
					ฟอร์มข้อมูล 
					
				</a></li><li><a
				href="http://203.107.236.212/?page=admin-parameters"
				
				title="พารามิเตอร์"
				>
					
					พารามิเตอร์ 
					
				</a></li><li><a
				href="http://203.107.236.212/?page=admin-users"
				
				title="ผู้ใช้งาน"
				>
					
					ผู้ใช้งาน 
					
				</a></li><li><a
				href="http://203.107.236.212/?page=admin-user-groups"
				
				title="กลุ่มผู้ใช้งาน"
				>
					
					กลุ่มผู้ใช้งาน 
					
				</a></li><li><a
				href="http://203.107.236.212/?page=admin-standards"
				
				title="เครื่องมาตรฐาน"
				>
					
					เครื่องมาตรฐาน 
					
				</a></li><li><a
				href="http://203.107.236.212/?page=admin-institutions"
				
				title="สถาบันสอบเทียบ"
				>
					
					สถาบันสอบเทียบ 
					
				</a></li><li><a
				href="http://203.107.236.212/?page=admin-eqp-status"
				
				title="รายการสถานะเครื่องมือ"
				>
					
					รายการสถานะเครื่องมือ 
					
				</a></li><li><a
				href="http://203.107.236.212/?page=admin-missings"
				
				title="รายการไม่สามารถดำเนินการ"
				>
					
					รายการไม่สามารถดำเนินการ 
					
				</a></li><li><a
				href="http://203.107.236.212/?page=admin-pm-list"
				
				title="รายการ PM"
				>
					
					รายการ PM 
					
				</a></li><li><a
				href="http://203.107.236.212/?page=admin-knowledge"
				
				title="เอกสารคู่มือ"
				>
					
					เอกสารคู่มือ 
					
				</a></li></ul></li></ul>
			</nav>
			<span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
			            
		</aside>
		<!-- END NAVIGATION -->
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
		<!-- RIBBON -->
	<div id="ribbon">
		
        <!--
		<span class="ribbon-button-alignment"> 
			<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh" rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true"><i class="fa fa-refresh"></i></span> 
		</span>
		-->
        
		<!-- breadcrumb -->
		<ol class="breadcrumb">
			<li><a href="http://203.107.236.212">Home</a></li><li><a href="http://203.107.236.212">Admin</a></li><li><a href="http://203.107.236.212?page=admin-forms">ฟอร์มข้อมูล</a></li><li>ตั้งค่าแบบฟอร์มข้อมูล</li>		</ol>
		<!-- end breadcrumb -->

		<!-- You can also add more buttons to the
		ribbon for further usability

		Example below:

		<span class="ribbon-button-alignment pull-right">
		<span id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa-grid"></i> Change Grid</span>
		<span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa-plus"></i> Add</span>
		<span id="search" class="btn btn-ribbon" data-title="search"><i class="fa-search"></i> <span class="hidden-mobile">Search</span></span>
		</span> -->
		        
	</div>
	<!-- END RIBBON -->
	<!-- MAIN CONTENT -->
	<div id="content">
		
      <section id="widget-grid" class="">
		
			<!-- START ROW -->
		<div class="row">
		
				<!-- NEW COL START -->
				<article class="col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">
						<!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
		
						data-widget-colorbutton="false"
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true"
						data-widget-sortable="false"
		
						-->
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>DATA RECORD FORM: <strong id="show-form-name">Untitled Form</strong> <span id="last-update-form" class="last-update-form"></span></h2>
							
                            <div id="close-box" class="widget-toolbar">
                            	<button class="btn btn-xs btn-danger">ยกเลิก</button>
                        	</div>
                            
                            <div class="widget-toolbar">
								<!-- add: non-hidden - to disable auto hide -->
								
								<div class="btn-group">
									<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
										ตั้งค่า <i class="fa fa-caret-down"></i>
									</button>
									<ul class="dropdown-menu pull-right js-status-update">
										<li>
											<a href="javascript:addTable();"><i class="fa fa-plus-square txt-color-green"></i> เพิ่มตารางข้อมูล CAL</a>
										</li>
                                        <li>
											<a href="javascript:addTablePM();"><i class="fa fa-plus-square txt-color-green"></i> เพิ่มตารางข้อมูล PM</a>
										</li>
                                        <li>
											<a href="javascript:addTableSTD();"><i class="fa fa-plus-square txt-color-green"></i> เพิ่มตารางข้อมูลเครื่อง Standard</a>
										</li>
                                       <!-- <li>
											<a href="javascript:addTableMissing();"><i class="fa fa-plus-square txt-color-green"></i> Add Missing Table</a>
										</li>-->
                                        <li>
											<a href="javascript:addTableNote();"><i class="fa fa-plus-square txt-color-green"></i> เพิ่มตารางหมายเหตุ</a>
										</li>
                                        <li>
											<a href="javascript:addCustomTable();"><i class="fa fa-plus-square txt-color-green"></i> เพิ่มตารางข้อมูลเฉพาะ</a>
										</li>
                                        <li class="save-form divider"></li>
                                        <li class="save-form">
											<a href="javascript:SaveForm();"><i class="fa fa-save txt-color-green"></i> Save</a>
										</li>
                                        <li class="save-form">
											<a href="javascript:SaveAsForm();" id="save-form-as-btn"><i class="fa fa-copy txt-color-green"></i> Save As...</a>
										</li>
									</ul>
								</div>
                                
							</div>
                           
						</header>
		
						<!-- widget div-->
						<div>
				
							<!-- widget content -->
							<div class="widget-body no-padding" style="min-height:50px;">
		
								<form id="design-form" class="smart-form" method="post" enctype="multipart/form-data" action="save.php">
                                	<input type="hidden" name="save_type" value="form" />
                                    <input type="hidden" id="save-form-name" name="form_name" value="" />
                                    <input type="hidden" id="save-form-type" name="form_type" value="" />
                                    <input type="hidden" id="save-form-id" name="form_id" value="" />
                                	<input type="hidden" id="CountRowsID" value="1" />
									
                                    <fieldset id="control-number">
                                        <div class="row">
                                            <section class="col col-2">
                                                <label class="label" style="padding-top:6px;">สถานะ:</label>
                                            </section>
                                            <section class="col col-4">
                                                <label class="input">
                                                    <select name="form_status" id="form_status" style="width:100%;" class="select2">
														<option value="1">ใช้งาน</option><option value="2">ไม่ใช้งาน</option><option value="3">ลบ</option>                                                    </select>
                                                </label>
                                            </section>
                                        </div>
                                        
                                        <div class="row">
                                            <section class="col col-2">
                                                <label class="label" style="padding-top:6px;">ชื่อเอกสาร Certificate:</label>
                                            </section>
                                            <section class="col col-10">
                                                <label class="input">
                                                    <input name="control_number[form_cert_name]" id="form_cert_name" type="text" value="" maxlength="50">
                                                </label>
                                            </section>
                                  		</div>
                                        <div class="row">
                                            <section class="col col-2">
                                                <label class="label" style="padding-top:6px;">ชื่อเอกสาร Certificate/2:</label>
                                            </section>
                                            <section class="col col-10">
                                                <label class="input">
                                                    <input name="control_number[form_cert_name2]" id="form_cert_name2" type="text" value="" maxlength="50">
                                                </label>
                                            </section>
                                  		</div>
                                        
                                        <div class="row">
                                            <section class="col col-2">
                                                <label class="label" style="padding-top:6px;">เลขที่เอกสาร ฟอร์ม:</label>
                                            </section>
                                            <section class="col col-4">
                                                <label class="input">
                                                    <input name="control_number[form_number]" id="form_number" type="text" value="" maxlength="50">
                                                </label>
                                            </section>
                                            <section class="col col-2">
                                                <label class="label" style="padding-top:6px;">Issued Date:</label>
                                            </section>
                                            <section class="col col-4">
                                                <label class="input">
                                                    <input name="control_number[form_issued_date]" id="form_issued_date" type="text" value="" maxlength="20">
                                                </label>
                                            </section>
                                  		</div>
                                        
                                        <div class="row">
                                            <section class="col col-2">
                                                <label class="label" style="padding-top:6px;">เลขที่เอกสาร Certificate:</label>
                                            </section>
                                            <section class="col col-4">
                                                <label class="input">
                                                    <input name="control_number[form_certificate_number]" id="form_certificate_number" type="text" value="" maxlength="50">
                                                </label>
                                            </section>
                                            <section class="col col-2">
                                                <label class="label" style="padding-top:6px;">Issued Date:</label>
                                            </section>
                                            <section class="col col-4">
                                                <label class="input">
                                                    <input name="control_number[form_certificate_issued_date]" id="form_certificate_issued_date" type="text" value="" maxlength="20">
                                                </label>
                                            </section>
                                        </div>
                                    </fieldset>
                                    
                                    <div id="parameters-sortable">
																		</div>
                                                                   
                                    <div id="table-parameter" style="padding:10px;">
                                    	<a href="javascript:addTableSTD();" class="btn btn-primary btn-lg" style="width:120px;">Standard</a>
                                        <a href="javascript:addTable();" class="btn btn-primary btn-lg" style="width:120px;">CAL</a>
                                        <a href="javascript:addTablePM();" class="btn btn-primary btn-lg" style="width:120px;">PM</a>
                                        <!--<a href="javascript:addTableMissing();" class="btn btn-primary btn-lg">Missing Table</a>-->
                                        <a href="javascript:addTableNote();" class="btn btn-primary btn-lg" style="width:120px;">Note</a>
                                    </div>
                                    
								</form>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
		
				</article>
				<!-- END COL -->
		
			</div>
		
			<!-- END ROW -->
		
			<!-- START ROW --><!-- END ROW -->
		
		  <!-- NEW ROW --><!-- END ROW-->
		
		</section>
        
	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<div id="dialog_save_form_as" title="Save Form As">
    <form id="save-as-form-box">
        <div class="row">
            <div class="col-md-3"><label style="padding-top:6px;">Save As</label></div>
            <div class="col-md-9">
                <input id="input-form-name" type="text" class="form-control" placeholder="Untitled Form" required />
            </div>
        </div>
        <div class="row" style="margin-top:10px;">
        	<div class="col-md-3"><label style="padding-top:6px;">Save Type</label></div>
            <div class="col-md-9">
				<select name="select_form_type" id="select-form-type" class="form-control" style="width:100%;" required>
					<option value="CAL">CAL</option>
					<option value="PM">PM</option>
				</select> <i></i> 
            </div>
        </div>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="SourceOfUncertainty" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
                <form id="source-of-uncertainty-form">
                	<input type="hidden" id="source-of-uncertainty-tbid" value="" />
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="myModalLabel">การคำนวนค่า Uncertainty</h4>
					</div>
					<div class="modal-body" style="padding:5px;">
                    	<div class="row" style="margin-bottom:10px; margin-top:10px;">
                        	<div class="col-md-4 smart-form">
                            	<label class="radio">รายงานผล</label>
                            </div>
                            <div class="col-md-4 smart-form">
                            	<label class="radio" id="set_report_uncertainty_box">
                                	<input name="set_report_type" id="set_report_uncertainty" value="uncertainty" type="radio"><i></i> รายงานค่า Uncertainty
                            	</label>
                            </div>
                            <div class="col-md-4 smart-form">
                            	<label class="radio" id="set_report_average_box">
                                	<input name="set_report_type" id="set_report_average" value="average" type="radio"><i></i> รายงานค่า Average
                            	</label>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom:20px; margin-top:10px;">
                        	<div class="col-md-4 smart-form">
                            	<label class="radio">เพิ่มเติม</label>
                            </div>
                            <div class="col-md-8 smart-form">
                            	<label class="checkbox"><input name="adds[phototherapy]" id="adds_phototherapy" class="report_adds" value="1" type="checkbox"><i></i> PHOTOTHERAPY</label>
                            </div>
                        </div>
                        
                        <strong>Source of Uncertainty</strong>
						<table class="table-bordered" style="margin-bottom:0px;">
                        	<tbody>
                                <tr>
                                    <th style="text-align:center; width:6%;">Use</th>
                                    <th style="text-align:center; width:10%;">Symbol</th>
                                    <th style="text-align:center; width:30%;">Source</th>
                                    <th style="text-align:center; width:15%;">&plusmn; Value</th>
                                    <th style="text-align:center; width:13%;">Divisor</th>
                                    <th style="text-align:center; width:13%;">Ci</th>
                                    <th style="text-align:center; width:13%;">Vi or Veff<br /><span style="font-size:70%; font-weight:normal;">0 = Infinity</span></th>
                                </tr>
                                                        		<tr>
                                	<td style="text-align:center"><input class="checked-source-active" symbol="u1" type="checkbox" name="source_active[u1]" id="source_active_u1" style="margin:0px;" checked="checked" /></td>
                                    <td style="text-align:center">x1<input type="hidden" class="uncer_source_symbol" value="u1" /></td>
                                                                        <td style="font-size:85%; padding-left:2px; padding-right:2px;">Repeatability of indication<input name="source_name[u1]" id="source_name_u1" type="hidden" /></td>
                                                                        <td><input name="source_value[u1]" id="source_value_u1" class="form-control" type="text" style="text-align:center" disabled="disabled" number="1"></td>
                                    <td><input name="source_divisor[u1]" id="source_divisor_u1" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_ci[u1]" id="source_ci_u1" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_veff[u1]" id="source_veff_u1" class="form-control" type="text" style="text-align:center" number="1"></td>
                                </tr>
                                                        		<tr>
                                	<td style="text-align:center"><input class="checked-source-active" symbol="u2" type="checkbox" name="source_active[u2]" id="source_active_u2" style="margin:0px;" checked="checked" /></td>
                                    <td style="text-align:center">x2<input type="hidden" class="uncer_source_symbol" value="u2" /></td>
                                                                        <td style="font-size:85%; padding-left:2px; padding-right:2px;">Resolution of UUC<input name="source_name[u2]" id="source_name_u2" type="hidden" /></td>
                                                                        <td><input name="source_value[u2]" id="source_value_u2" class="form-control" type="text" style="text-align:center" disabled="disabled" number="1"></td>
                                    <td><input name="source_divisor[u2]" id="source_divisor_u2" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_ci[u2]" id="source_ci_u2" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_veff[u2]" id="source_veff_u2" class="form-control" type="text" style="text-align:center" number="1"></td>
                                </tr>
                                                        		<tr>
                                	<td style="text-align:center"><input class="checked-source-active" symbol="u3" type="checkbox" name="source_active[u3]" id="source_active_u3" style="margin:0px;" checked="checked" /></td>
                                    <td style="text-align:center">x3<input type="hidden" class="uncer_source_symbol" value="u3" /></td>
                                                                        <td style="font-size:85%; padding-left:2px; padding-right:2px;">Resolution of Standard<input name="source_name[u3]" id="source_name_u3" type="hidden" /></td>
                                                                        <td><input name="source_value[u3]" id="source_value_u3" class="form-control" type="text" style="text-align:center" disabled="disabled" number="1"></td>
                                    <td><input name="source_divisor[u3]" id="source_divisor_u3" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_ci[u3]" id="source_ci_u3" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_veff[u3]" id="source_veff_u3" class="form-control" type="text" style="text-align:center" number="1"></td>
                                </tr>
                                                        		<tr>
                                	<td style="text-align:center"><input class="checked-source-active" symbol="u4" type="checkbox" name="source_active[u4]" id="source_active_u4" style="margin:0px;" checked="checked" /></td>
                                    <td style="text-align:center">x4<input type="hidden" class="uncer_source_symbol" value="u4" /></td>
                                                                        <td style="font-size:85%; padding-left:2px; padding-right:2px;">Accuracy of Standard (% of reading)<input name="source_name[u4]" id="source_name_u4" type="hidden" /></td>
                                                                        <td><input name="source_value[u4]" id="source_value_u4" class="form-control" type="text" style="text-align:center" disabled="disabled" number="1"></td>
                                    <td><input name="source_divisor[u4]" id="source_divisor_u4" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_ci[u4]" id="source_ci_u4" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_veff[u4]" id="source_veff_u4" class="form-control" type="text" style="text-align:center" number="1"></td>
                                </tr>
                                                        		<tr>
                                	<td style="text-align:center"><input class="checked-source-active" symbol="u5" type="checkbox" name="source_active[u5]" id="source_active_u5" style="margin:0px;" checked="checked" /></td>
                                    <td style="text-align:center">x5<input type="hidden" class="uncer_source_symbol" value="u5" /></td>
                                                                        <td style="font-size:85%; padding-left:2px; padding-right:2px;">Calibration of Standard<input name="source_name[u5]" id="source_name_u5" type="hidden" /></td>
                                                                        <td><input name="source_value[u5]" id="source_value_u5" class="form-control" type="text" style="text-align:center" disabled="disabled" number="1"></td>
                                    <td><input name="source_divisor[u5]" id="source_divisor_u5" class="form-control" type="text" style="text-align:center" disabled="disabled" number="1"></td>
                                    <td><input name="source_ci[u5]" id="source_ci_u5" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_veff[u5]" id="source_veff_u5" class="form-control" type="text" style="text-align:center" number="1"></td>
                                </tr>
                                                        		<tr>
                                	<td style="text-align:center"><input class="checked-source-active" symbol="u6" type="checkbox" name="source_active[u6]" id="source_active_u6" style="margin:0px;" checked="checked" /></td>
                                    <td style="text-align:center">x6<input type="hidden" class="uncer_source_symbol" value="u6" /></td>
                                                                        <td style="font-size:85%; padding-left:2px; padding-right:2px;">Accuracy of Standard (± Value)<input name="source_name[u6]" id="source_name_u6" type="hidden" /></td>
                                                                        <td><input name="source_value[u6]" id="source_value_u6" class="form-control" type="text" style="text-align:center" disabled="disabled" number="1"></td>
                                    <td><input name="source_divisor[u6]" id="source_divisor_u6" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_ci[u6]" id="source_ci_u6" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_veff[u6]" id="source_veff_u6" class="form-control" type="text" style="text-align:center" number="1"></td>
                                </tr>
                                                        		<tr>
                                	<td style="text-align:center"><input class="checked-source-active" symbol="u7" type="checkbox" name="source_active[u7]" id="source_active_u7" style="margin:0px;" checked="checked" /></td>
                                    <td style="text-align:center">x7<input type="hidden" class="uncer_source_symbol" value="u7" /></td>
                                                                        <td style="font-size:85%; padding-left:2px; padding-right:2px;">Drift of Standard<input name="source_name[u7]" id="source_name_u7" type="hidden" /></td>
                                                                        <td><input name="source_value[u7]" id="source_value_u7" class="form-control" type="text" style="text-align:center" disabled="disabled" number="1"></td>
                                    <td><input name="source_divisor[u7]" id="source_divisor_u7" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_ci[u7]" id="source_ci_u7" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_veff[u7]" id="source_veff_u7" class="form-control" type="text" style="text-align:center" number="1"></td>
                                </tr>
                                                        		<tr>
                                	<td style="text-align:center"><input class="checked-source-active" symbol="u8" type="checkbox" name="source_active[u8]" id="source_active_u8" style="margin:0px;" checked="checked" /></td>
                                    <td style="text-align:center">x8<input type="hidden" class="uncer_source_symbol" value="u8" /></td>
                                                                        <td><input name="source_name[u8]" id="source_name_u8" class="form-control" type="text" style="font-size:85%; padding-left:2px; padding-right:2px;"></td>
                                                                        <td><input name="source_value[u8]" id="source_value_u8" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_divisor[u8]" id="source_divisor_u8" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_ci[u8]" id="source_ci_u8" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_veff[u8]" id="source_veff_u8" class="form-control" type="text" style="text-align:center" number="1"></td>
                                </tr>
                                                        		<tr>
                                	<td style="text-align:center"><input class="checked-source-active" symbol="u9" type="checkbox" name="source_active[u9]" id="source_active_u9" style="margin:0px;" checked="checked" /></td>
                                    <td style="text-align:center">x9<input type="hidden" class="uncer_source_symbol" value="u9" /></td>
                                                                        <td><input name="source_name[u9]" id="source_name_u9" class="form-control" type="text" style="font-size:85%; padding-left:2px; padding-right:2px;"></td>
                                                                        <td><input name="source_value[u9]" id="source_value_u9" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_divisor[u9]" id="source_divisor_u9" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_ci[u9]" id="source_ci_u9" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_veff[u9]" id="source_veff_u9" class="form-control" type="text" style="text-align:center" number="1"></td>
                                </tr>
                                                        		<tr>
                                	<td style="text-align:center"><input class="checked-source-active" symbol="u10" type="checkbox" name="source_active[u10]" id="source_active_u10" style="margin:0px;" checked="checked" /></td>
                                    <td style="text-align:center">x10<input type="hidden" class="uncer_source_symbol" value="u10" /></td>
                                                                        <td><input name="source_name[u10]" id="source_name_u10" class="form-control" type="text" style="font-size:85%; padding-left:2px; padding-right:2px;"></td>
                                                                        <td><input name="source_value[u10]" id="source_value_u10" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_divisor[u10]" id="source_divisor_u10" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_ci[u10]" id="source_ci_u10" class="form-control" type="text" style="text-align:center" number="1"></td>
                                    <td><input name="source_veff[u10]" id="source_veff_u10" class="form-control" type="text" style="text-align:center" number="1"></td>
                                </tr>
                                                                
                            </tbody>
                        </table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							ยกเลิก
						</button>
						<button id="save-source-of-uncertainty" type="button" class="btn btn-primary">
							ตกลง
						</button>
					</div>
                    <div class="modal-body">
                    	<ul>
                        	<li><strong>Symbol</strong> สัญลักษณ์ของแหล่งความไม่แน่นอนย่อย</li>
                            <li><strong>Source</strong> รายละเอียดของแหล่งความไม่แน่นอน</li>
                            <li><strong>Value</strong> ขนาดของแหล่งความไม่แน่นอนที่ประมาณได้</li>
                            <li><strong>Divisor</strong> ขนาดของตัวหารเพื่อที่จะทำให้ได้ Standard Uncertainty ของแต่ละตัว
                            	<ul>
                                	<li>√2 = 1.414</li>
                                    <li>√3 = 1.732</li>
                                    <li>2√3 = 3.464</li>
                                    <li>√6 = 2.449</li>
                                </ul>
                            </li>
                            <li><strong>Ci</strong> ค่า Sensitivity Coefficient</li>
                            <li><strong>Vi or Veff</strong> ค่า Degree of Freedom ของแต่ละตัว</li>
                        </ul>
                    </div>
                </form>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Modal -->
	<div class="modal fade" id="AddPmListFormBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
                   <form id="add-pm-list-form" method="post">
                       <input type="hidden" name="save_type" value="add_pm_list" />
                       <input type="hidden" name="table_id" id="add_pm_list_table_id" value="" />
                       <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                               &times;
                           </button>
                           <h4 class="modal-title">เพิ่มรายการ</h4>
                       </div>
                       <div class="modal-body">
                           <div class="row">
                               <div class="col-md-12">
                                   <textarea name="pm_list_names" id="pm_list_names" class="form-control" rows="10" required></textarea>
                               </div>
                           </div>
                       </div>
                       <div class="modal-footer">
                           <button type="button" class="btn btn-default" data-dismiss="modal">
                               ยกเลิก
                           </button>
                           <button id="add-pm-list" type="button" class="btn btn-primary">
                               เพิ่ม
                           </button>
                       </div>
                   </form>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- PAGE FOOTER -->
                        
<!-- PAGE FOOTER -->
<div class="page-footer">
	<div class="row">
		<div class="col-xs-12 col-sm-6">
			<span class="txt-color-white">E-Certificate © 2014-2020</span>
		</div>
				<div class="col-xs-6 col-sm-6 text-right hidden-xs">
			<div class="txt-color-white inline-block">
				<div class="btn-group dropup">
					<button class="btn btn-xs dropdown-toggle bg-color-blue txt-color-white" data-toggle="dropdown">
						User Online 7 <span class="caret"></span>
					</button>
					<ul class="dropdown-menu pull-right text-left">
						<li><a href="http://203.107.236.212/?page=profile&id=48"><i class="fa fa-circle txt-color-green"></i> YOTHIN  TERMPHOL</a></li><li><a href="http://203.107.236.212/?page=profile&id=50"><i class="fa fa-circle txt-color-green"></i> NATTAWIT KOFUG</a></li><li><a href="http://203.107.236.212/?page=profile&id=177"><i class="fa fa-circle txt-color-green"></i> NETI  BANJONG</a></li><li><a href="http://203.107.236.212/?page=profile&id=244"><i class="fa fa-circle txt-color-green"></i> นายภูวสิน ยิวคิม</a></li><li><a href="http://203.107.236.212/?page=profile&id=300"><i class="fa fa-circle txt-color-green"></i> VIPHADA NGOYLA</a></li><li><a href="http://203.107.236.212/?page=profile&id=499"><i class="fa fa-circle txt-color-green"></i> PIMLAPHUT YADDUSSADEE</a></li><li><a href="http://203.107.236.212/?page=profile&id=501"><i class="fa fa-circle txt-color-green"></i> AI</a></li>					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END PAGE FOOTER --><!-- END PAGE FOOTER -->

		<!--================================================== -->

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<!-- <script data-pace-options='{ "restartOnRequestAfter": true }' src="http://203.107.236.212/js/plugin/pace/pace.min.js"></script> -->

		<!-- These scripts will be located in Header So we can add scripts inside body (used in class.datatables.php) -->
		<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local 
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="http://203.107.236.212/js/libs/jquery-2.0.2.min.js"><\/script>');
			}
		</script>

		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="http://203.107.236.212/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script> -->

		<!-- IMPORTANT: APP CONFIG -->
		<script src="js/app.config.js"></script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
		<script src="http://203.107.236.212/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 

		<!-- BOOTSTRAP JS -->
		<script src="http://203.107.236.212/js/bootstrap/bootstrap.min.js"></script>

		<!-- CUSTOM NOTIFICATION -->
		<script src="http://203.107.236.212/js/notification/SmartNotification.min.js"></script>

		<!-- JARVIS WIDGETS -->
		<script src="http://203.107.236.212/js/smartwidgets/jarvis.widget.min.js"></script>

		<!-- EASY PIE CHARTS -->
		<script src="http://203.107.236.212/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

		<!-- SPARKLINES -->
		<script src="http://203.107.236.212/js/plugin/sparkline/jquery.sparkline.min.js"></script>

		<!-- JQUERY VALIDATE -->
		<script src="http://203.107.236.212/js/plugin/jquery-validate/jquery.validate.min.js"></script>

		<!-- JQUERY MASKED INPUT -->
		<script src="http://203.107.236.212/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

		<!-- JQUERY SELECT2 INPUT -->
		<script src="http://203.107.236.212/js/plugin/select2/select2.min.js"></script>

		<!-- JQUERY UI + Bootstrap Slider -->
		<script src="http://203.107.236.212/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

		<!-- browser msie issue fix -->
		<script src="http://203.107.236.212/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

		<!-- FastClick: For mobile devices -->
		<script src="http://203.107.236.212/js/plugin/fastclick/fastclick.min.js"></script>

		<!--[if IE 8]>
			<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
		<![endif]-->

		<!-- Demo purpose only -->
		<!--<script src="http://203.107.236.212/js/demo.min.js"></script>-->

		<!-- MAIN APP JS FILE -->
		<script src="http://203.107.236.212/js/app.min.js"></script>		

		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="http://203.107.236.212/js/speech/voicecommand.min.js"></script>	

		<!-- SmartChat UI : plugin -->
		<script src="http://203.107.236.212/js/smart-chat-ui/smart.chat.ui.min.js"></script>
		<script src="http://203.107.236.212/js/smart-chat-ui/smart.chat.manager.min.js"></script>

		<script type="text/javascript">
			// DO NOT REMOVE : GLOBAL FUNCTIONS!
			$(document).ready(function() {
				pageSetUp();
			})
		</script>
<!-- PAGE RELATED PLUGIN(S) -->
<script src="http://203.107.236.212/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="http://203.107.236.212/js/form.js"></script>

<script>

	$(document).ready(function() {
		// PAGE RELATED SCRIPTS
		
				
		$('#close-box').click(function(){ $(location).attr('href', 'http://203.107.236.212/?page=admin-forms'); });
		
		$('#save-source-of-uncertainty').click(function(){ SubmitSource(); });
		
		$('#add-pm-list').click(function(){ AddPmList(); });
		
		$('.checked-source-active').change(function(){ CheckSourceRequiredTag(this); });
		
		$('.input-upper-case').keyup(function(){
			this.value = this.value.toUpperCase();
		});
		
		$('.input-upper-first').keyup(function(){
			this.value = this.value.toLowerCase().replace(/\b[a-z]/g, function(letter) {
				return letter.toUpperCase();
			});
		});
		
		$('.set-tole-amount').keyup(function(){
			set_tolerance_amount($(this).attr('tbid'));
		});
		
		CheckHasTable();
				
		// PERFORM DATE AND DUE DATE
		$('#performdate').datepicker({
			dateFormat : 'yy-mm-dd',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			//defaultDate: '2015-01-15',
			onSelect : function(selectedDate) {
				$('#duedate').datepicker('option', 'minDate', selectedDate);
			}
		});
		
		$('#duedate').datepicker({
			dateFormat : 'yy-mm-dd',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			//defaultDate: '2015-01-15',
			onSelect : function(selectedDate) {
				$('#performdate').datepicker('option', 'maxDate', selectedDate);
			}
		});
		
		// Turn Off Autocomplete
		$(document).on('focus', ':input', function(){
			$(this).attr('autocomplete', 'off');
		});
				
		$('#dialog_save_form_as').dialog({
			autoOpen : false,
			width : 400,
			resizable : false,
			modal : true,
			title : "Save Form As",
			buttons : [{
				html : "<i class='fa fa-save'></i>&nbsp; Save",
				"class" : "btn btn-success",
				click : function() {
					
					if(SaveAsFormValidate()){
						var InputFormName = $("#input-form-name").val();
						$("#save-form-name").val(InputFormName);
						
						var InputFormType = $("#select-form-type").val();
						$("#save-form-type").val(InputFormType);
						
						SavingForm();
					}

				}
			}, {
				html : "<i class='fa fa-times'></i>&nbsp; Cancel",
				"class" : "btn btn-default",
				click : function() {
					$(this).dialog("close");
				}
			}]
		});
		
		PMListAutocomplete();
	})

function PMListAutocomplete(){
	var PMLists = ["\"Base Supports","\"Circuit Breaker/Fuse","\"Controls/Switches","\"Door operation","\"Indicators/Displays","\"Labeling","\"Portable Power Supply (transport incubators only)","\"Safety valve","\"Shutoff valves","\"Test cycle","(Capnometers) ±0.4 vol% (±3 mm Hg) or ±10%","(thermistor thermometers) (Temp) ±0.3°C (0.5°F)","(thermistor thermometers) (Temp) ±0.5°C (0.8°F)",", Temp, Capnometers, tcpO2/CO2)","5","AC Plug","AC Plug/Receptacles","Accessories","Accessosies","Accuracy  ±2 vol% or 5%, whichever is greater","Accuracy of Speed Setting±10%","Accuracy on Volunteer (NIBP) ±10 mm Hg of nurse\'s measurement","Adjust","Air Leakage  ≤15 mm Hg/min","Air Leakage (NIBP) ≤15 mm Hg/min","Air-in-Line Detection 50 to 100 μL","Air-Temperature Alarms ≤39°C or mfr spec","Alarm Accuracy (IBP) ±2%","Alarm Accuracy ≤0.5°C","Alarm Delay ≤10 sec","Alarms","Alarms  ±2% oxygen at 21%, ±5% oxygen at 50%","Alarms (Capnometers)","Alarms (ECG)","Alarms (IBP)","Alarms (NIBP)","Alarms (SpO2)","Alarms (tcpO2/CO2)","Alarms (Temp)","Alarms/Interlocks","Alignment Features for Disposable Sets","Amplifier Cable Testing and Temperature Testing (Option).","Amplifier Gain Testing.","Amplitude Accuracy ±10%","APL Valve~1 to ≥30 cm H2O","Apnea Alarm Delay Time ±20%","Atrial-Ventricular Delay ±10% mfr spec","Audible Signals","Auditory Stim  Testing  (Option).","Automated Mode Analysis and Defibrillator Output per mfr/hosp policy","Automatic Controller Switching ±0.5°C or mfr spec","Backup System Data.","Ball Valve","Base Supports","Baseline Spectral Irradiance ≥4 μW/cm2/nm or mfr spec *(≥176 μW/cm2)","Bassinet/Mattress","Battery","Battery Test Feature","Battery/Charger","Bellows or Piston","Blankets","Bleed Valve","Blower","Bolts and nuts","Bolus Accuracy ≤5%","Brake","Breathing System (including filters)","Breathing System ≥30 cm H2O, 30 sec","Cable","Cables","Cables (ECG)","Cables (SpO2, IBP, Temp, Capnometers, tcpO2/CO2)","Calibration/Self-Test","Carbon Dioxide Absorber","Carbon Dioxide Concentration Accuracy","Carbon Dioxide Concentration Accuracy  ±0.4 vol% (±3 mm Hg) or within ±10%, whichever is greater, of the delivered concentration","Carbon Dioxide Display Accuracy(tcpO2/CO2) ±5 mm Hg or 10%","casters","Casters/Brakes","Casters/Wheels","Casters/Wheels/Brakes","Chassis Grounding Resistance≤0.5 Ω","Chassis Leakage Current  ≤500 µA","Chassis Leakage Current (<300 µA)","Chassis Leakage Current (For Installed Equipment) ≤10mA","Chassis Leakage Current (≤300 µA)","Chassis Leakage Current ≤ 500 µA","Chassis Leakage Current ≤300 µA","Chassis Leakage Current ≤500 μA","Chassis Leakage Current≤3,500 µA","Chassis Leakage Current≤500 µA","Chassis/Housin","Chassis/Housing","Chassis/Housing/Bassinet","Chassis/Housing/Frame","Check Frequency","Check output power","Check that all connector are at the correct place and the they are properly fastened.","Check that main switch is properly mounted.","Check that the fuses have the ratings specified.","Check that the LED display","Check the cable electrode","Check the electrical wiring for safety","Check tuning device","Check-Valve Leakage  ≤0.1 L/min","Circuit Breaker/Fuse","Clean","Clean the exterior and accessories","Coincidence Circuit","Compressor","Concentration Check±0.3% vapor or ±10% of the selected value, whichever is greater","Connectivity","Connectors","Continuity >10 Ω / Contact quality 5 Ω -150 Ω","Control Valve","Controls","Controls ±10%","Controls ±10% of control settings","controls/Knobs","Controls/Switches","Cuff Pressure Indicator Accuracy ±5% at 200 and 450 mm Hg","Cuffs","Cycle Time  mfr spec","Data Recording","Data Transfer to Data Management System","Demand-Mode Sensitivity 1-2 mV or mfr spec","Demand-Mode Sensitivity ≤0.5 mV or mfr spec","Direct Current Leakage ≤5 mV","Directional Valves","Dispersive Cable Continuity Monitor","Dispersive Electrode Contact Quality Monitor Resistance","Dispersive Electrode Contact Quality Monitor Resistance Continuity >10 Ω / Contact quality 5 Ω -150 Ω","Dispersive Electrode Grounding Resistance>20 MΩ for","Dispersive Electrode Grounding Resistance>20 MΩ for ground-referenced units","Dispersive Electrodes","Display output show","Display timer test Maximum  30  minute","Door operation","Dual-Bladder","ECG Features per Procedure 493","EEH Electrode","Elapsed-Time Meter/Timer ±2 min after 15 min","Electrode-to-Ground Leakage Current  ≤100 µA grounded;  ≤500µA ungrounded","Electrodes","Electrodes/Transducers","Elevation ±1 %","Emergency Stop","Empty Container","Empty Syringe","Energy after 50 Sec (Manual Mode) ≥85%","equipment","Exhaled Volume Monitor±15% test lung value; ±15% minute volume display","Fail-Safe Oxygen Valves","Fan","Fan/Compressor","Fan/Compressor or Turbine","Fasteners","Fiberoptic Cable Connector","Fiberoptic Pads/Cables","Filters","Filters (Capnometers)","Filters/Heat-Reflecting Mirrors","Fittings/Connectors","Fittings/Connectors (ECG, SpO2, IBP","Fittings/Connectors (NIBP)","Fittings/Connectors and Preventing Misconnection","Flow Accuracy ±0.5 L/min *or ±10%","Flow Accuracy ±10%","Flow Accuracy ±5%","Flow Accuracy±5%","Flow with Loss of an Input   High flow ≥30 L/min; low flow ≥15 L/min","Flow-Stop Mechanism(s)","Flow/Output","Flowmeters","Flowmeters ±10%","Fluid Flow Unit alarms; mfr flow spec, if indicated","Fluid Levels","Fluid Temperature 37°C to 42°C at highest flow setting used","Fluid Temperature Display ±1°C","Footswitch","Free-flow Prevention Mechanism(s)","Frequency Response 0.67 to 100 Hz in the auto report mode","Front End Testing.","Gain ±10%","Gas concentration alarms","Gas cylinders (and gauges and regulators)","Gas Cylinders, Gauges, and Regulators (for transport ventilators)","Gas Cylinders, Gauges, and Regulators (for transport ventilators)\"","Gas Cylinders, Gauges, and Regulators(for transport ventilators)","Gauge/Column","Ground Resistance","Ground Resistance ≤0.5 Ω","ground-referenced units","Grounding Resistance  ≤ 0.5 Ω","Grounding Resistance  ≤0.5 Ω","Grounding Resistance  ≤0.5Ω","Grounding Resistance (<0.5 Ω)","Grounding Resistance (≤0.5 Ω )","Grounding Resistance ≤0.5 Ω","Grounding Resistance ≤0.5 Ω.","Grounding Resistance ≤0.5Ω","Grounding Resistance≤0.5 Ω","Grounding Resistance≤0.5Ω","Halogenated Agent Concentration Accuracy(Capnometers) ±0.25 vol%","Heart Rate Alarm","Heart Rate ±10%","Heart Rate ±5%","Heater","Heater(s)","Heating Element","High-Pressure Leaks negligible pressure drop >30 sec","High-Temperature Alarm  43°C (110°F) solutions / 54°C (130°F) blankets","High-Temperature Alarms  ±1°C","High-Temperature Protection 53°C ±3°C (127°F ±5°F)","High-Temperature Protection ≤43°C","High-Temperature Protection ≤43°C or manufacturer specification","Hood Air Temperature ±1°C","Hood Thermometer","Humidifier","Humidifiers","Indicators/Display","Indicators/Displays","Indicators/Displays (Capnometers)","Indicators/Displays (ECG)","Indicators/Displays (NIBP, IBP, tcpO2/CO2)","Indicators/Displays (SpO2)","Indicators/Displays (Temp)","Inflow","Inflow ferocity >100 fpm","Inflow Velocity > 100 fpm","Infusion Complete","Input Pressure Alarms  Mfr Spec","Integral Output Tester","Interlead Leakage Current (Isolated Lead)  ≤10 μA grounded; ≤50 μA open ground","Interlead Leakage Current (Isolated Lead) (ECG) ≤10 μA grounded; ≤50 μA open ground","Interlead Leakage Current (Isolated Lead) ≤10 μA (grounded); ≤50 μA (ungrounded)","Interlead Leakage Current (Isolated Lead) ≤10 µA grounded;≤50 µA open ground","Interlocks","Intermediate Pressure Leaks no leakage","Intermittent Cycling 20 sec","Intermittent Operation","Internal Paddle Energy Limit ≤50 J","Intrauterine Pressure (IUP) Transducer ±2 mm","Investigate and Save New Hardware Configuration.","Isolate Box  Vac","IV Pole Mount","Keyboard/Switch Testing.","Knob","Labeling","Labeling   Accessories","Lead -to-Ground Leakage Current  ≤100µA grounded; ≤500µA ungrounded","Lead Input Isolation  ≤50 μA","Lead Input Isolation (ECG) ≤50 μA","Lead Input Isolation ≤50 μA (grounded)","Lead Input Isolation ≤50 µA grounded","Lead Off Detection","Lead-to-Ground Leakage Current  ≤100 µA grounded; ≤500 µA ungrounded","Lead-to-Ground Leakage Current (Isolated Lead)  ≤10 µA grounded; ≤50 µA open ground","Lead-to-Ground Leakage Current (Isolated Lead) ≤10 μA (grounded); ≤50 μA (ungrounded)","Lead-to-Ground Leakage Current (Isolated Lead) ≤10 µA grounded; ≤50 µA open ground","Lead-to-Ground Leakage Current (Isolated Lead)(ECG) ≤10 μA grounded; ≤50 μA open  round","Leak Check","Leak Check (Capnometers)","Leaks   ≤6 mL","Line Cord","LKDSJFSDJFLSDKFJLSKDSJ","Lockout Interval","Low Temperature Protection ±1°C of mfr spec","Low-Flow Bleeds","Low-Pressure Leaks<30 mL/min at 30 cm H2O","Low-Temperature Alarms  ±1°C","Lubricate","Manual Mode Defibrillator Output ±4 J low; ±15% high","Manual operation","Manuals","Mattress","Maximum Cuff Pressure ≤550 mm Hg or mfr spec","Maximum Flow   ≥30 L/min at 60% O2","Maximum Free Flow  20 L/min","Maximum Free Flow ≥20 L/min","Maximum Free Flow ≥85 L/min","Maximum Load (for bedside and ceiling- mounted adult/pediatric units)Max rated load for 5 min","Maximum Pressure (NIBP) ≤330 mm Hg","Maximum Pressure ±1 psi mfr spec","Maximum Pressure ±1 psi of manufacturer specification","Maximum Pressure ±1 psi of mfr spec","Maximum Vacuum  >40 mm Hg","Maximum Vacuum > 200 mm Hg","Maximum Vacuum Low-volume/thoracic >40 to 120 mm Hg; surgical/tracheal ≥400 mm Hg.","Maximum Vacuum Low/High: >40/>100 mm Hg","Maximum Vacuum ≥700 mm Hg","Minimum Oxygen Flow and Percentage 100–250 mL/min or mfr\'s specs","Misloaded Set/Syringe/Vial","Misloaded Syringe","Modes and Settings±10% or mfr spec","Monitored Parameters and Alarms displays, ±10% simulated values ; alarms, ±10%  settings","Monitored Parameters and Alarms displays, ±10% simulated values ; alarms, ±10% settings","Monitored Parameters and Alarms displays,±10% simulated values ; alarms, ±10% settings","Monitors and Alarms±10% or mfr spec","Motor","Motor Back","Motor Hi-Lo","Motor Knee","Motor Trendelenburg","Motor/Fan","Motor/Pump","Motor/Pump/Fan/Compressor","Motor/Rotor/Pump","Mount","Mount/Fasteners","Mounts","Mounts/Fasteners","Multiple Discharge Output Energy and Charge Time ±15%,≤10 sec","Network/Wireless Interfaces","Nitrous Oxide Concentration Accuracy (Capnometers) ±5 vol% or ±10%","Nurse Call (verify only if this function is used)","Occlusion","Occlusion alarm","Open Door/Misloaded Infusion Set","Open Electrode Indicator 1,000 to 2,000 Ω","Operational Modes","Operational Sound","or manufacturer specification","Other alarms","Outlet Pressure  mfr spec or 5-7 psig","Output Temperature  ±1°C","Overflow Protection","Oxygen Concentration  ±3%","Oxygen Concentration  ≥93%","Oxygen Concentration Accuracy  ±2 vol% or 5% of the expectedvalue, whichever is greater","Oxygen Concentration Accuracy (Capnometers)","Oxygen Display Accuracy (tcpO2/CO2) ±5 mm Hg or 10%","Oxygen Flush Valve 35–75 L/min; O2 flowmeter drop <1 L/min at 2 L/min; return to 2 L/min <2 sec","Oxygen Tank","Pacemaker Demand-Mode Activation/Inhibition","Pacing Amplitude ±10%","Pacing Pulse Width ±10%","Pacing Rate ±5%","Paddles/Electrodes","Paper Speed  ±2%","Paper Speed ±2%","Paper Transport","Patient Probe Leakage Current (SpO2,Temp) ≤100 μA, ≤500 μA under single fault conditions","Patient Probe Leakage Current (tcpO2/CO2)≤100 μA, ≤500 μA under single fault conditions","Patient Temperature Display and Probe ±1°C","Pediatric Mode Output Energy ≤50 J","PEEP Valve system pressures <1 cm H2O and ±1.5 cm H2O","Plug","Portable Power Supply (transport incubators only) ≤10% voltage decrease","Power Continuity","Power Sources/Internal Battery Charger","Pressure Accuracy (IBP) ±2%  *or mfr spec","Pressure Accuracy ±3 mm Hg","Pressure Display ±10%","Pressure Drop  ≤5 cm H2O for bubble-type humidifiers; less for other units","Pressure Leakage ≤15 mmHg/min","Pressure Modes (IBP)","Pressure Regulator","Pressure Stability 400 ±10 mm Hg >15 min","Pressure Verification  ± 10%","Pressure-Relief Mechanism","Print Quality","Probes","Pulse Width 0.5 to 2.0 msec, typical; ±10% mfr spec, user adjustable","Pulse/Heart Rate ±5 %","Pump","Pump (Capnometers)","QRS Sensitivity ≥0.15 mV","Rate Accuracy ±5%","Rate Alarm (ECG)","Rate Alarm Accuracy ≤20 bpm","Rate Alarm ±5% or 5 bpm at 40 and 120 bpm","Rate Calibration (ECG) ±5%","Rate Calibration ±5% or 5 bpm at 60 bpm and 120 bpm","Rate of Vacuum Rise  30 mm Hg in 4 sec","Rate of Vacuum Rise 150 mm Hg in 5 sec","Rate of Vacuum Rise 30 mm Hg in 30 sec","Ratemeter Accuracy ±5%","Receptacles","Recorder","Recorder (ECG)","Recorder / Printer","Refractory Period mfr spec","Remote control Handheld","Remote control Nurse","Replace","Respiration Rate ±1 breath/min","Response Time  Mfr spec/≤20 sec","RF Output Current/Power ±5 W or ±20%","RF Output Isolation≤150 mA or≤4.5 W","Run Keypoint Test Software.","Safety Thermostat ≤40°C","Safety valve","Sampling Flow Accuracy (Capnometers) mfr spec","Scavenging System Max suction -0.5–0 cm H2O; ≤10 L/min O2, near ambient; APL occluded <10 cm H2O","Self-Test","Self-Test Verification","Sensitivity ≤0.3 Ω at max varies w/setting, no breaths at 0 bpm","Sensor/transducer","Sensors/Sampling Lines","Sensors/Sampling Lines (Capnometers)","Shutoff valves","Side rails","Site Glass, O-Rings, Keyed Filler Mechanism","Skin-Temperature Alarms ±0.5°C","Sleep Senser","Slow exhaust valve  20-40 min","Speed ±5 %","Static Pressure Accuracy (NIBP) ±3 mm Hg*OscillometricPressure Accuracy (NIBP) ±8 mm Hg","Static Pressure Accuracy ±3 mmHg or *Oscillometric Pressure ±8 mmHg","Stimulator Testing.","Stimulator Voltage or Current ±10% or mfr spec","Strain Reliefs","Synchronized Cardioverter","Synchronized Cardioverter Operation ≤60 msec after R-wave","Temperature Accuracy *±1°C","Temperature Accuracy *±1°C Humidity Accuracy *±10%RH","Temperature Accuracy and Control  ±1.0 °C warm up; ± 0.5°C steady state","Temperature Accuracy and Control  ±1°C (2°F)","Temperature Accuracy ±0.3°C","Temperature Accuracy ±0.5°C","Temperature Accuracy ±1°C (2°F) displayed value","Temperature Accuracy, Predictive Mode","Temperature Accuracy, Predictive Mode ±0.3°C","Temperature Accuracy, Steady-State Mode","Temperature Accuracy, Steady-State Mode ±0.3°C","Temperature Accuracy±3°C","Temperature Alarms (Temp) ±0.6°C (1.0°F)","Temperature Alarms ±0.6°C","Temperature Control (tcpO2/CO2) ±0.1°C","Temperature Controller Performance  ±1.0°C warm up; ± 0.5°C steady state","Temperature Display Accuracy (tcpO2/CO2) ±0.3°C","Temperature Sensor (water bath units)","Terminals","Test cycle","Test safety swich","Time/Date Settings","Timer 1 min ±10 sec","Timer Accuracy±10%","Touch Current","Touch Current  ≤500 µA","Touch Current  ≤500 µA [ungrounded] patient-care","Touch Current  ≤500 µA [ungrounded] patient-care equipment","Touch Current  ≤500µA (ungrounded) patient-care Equipment","Touch Current ≤500 µA (ungrounded) patient-care equipment","Touch Current ≤500µA","Traction Force ±10%","Transducers (non-oscillometric units)","Transducers/Electrodes","Transducers/Temperature Sensor","Tubes/Hoses","Tubes/Hoses/Bulb","Tubes/Hoses/Bulbs (NIBP)","Ultrasound Power ±20%","Update Anti-Virus","User Calibration","User Calibration (IBP)","User Calibration (NIBP, Capnometers, tcpO2/CO2)","User Calibration (Temp)","User Calibration/Self Test","User Calibration/Self-Test","Vacuum Gauge Accuracy  ±10%","Vacuum Gauge Accuracy ±10%","Vacuum Gauge Accuracy ±5% FSO(Full Scale output)","Vacuum leak test  ≤1 mm Hg/min","Vacuum/Pressure Gauge Accuracy  ±10%","Vaporizer Back-Pressure Check Valve","Verify Operation of Computer,Keyboard,Monitor and Printer.","Visual Stim Testing  (Option).","Waveform Analysis mfr spec","Weight (Mass) Accuracy for Electronic Scales ±1 %","Weight (Mass) Accuracy for Mechanical Scales ±1 %","Zero Calibration/Electronic Scales Display reads zero","Zero Calibration/Mechanical Scales","Zero Pressure Setting","กล้องบันทึกภาพการเคลื่อนไหว (Camara Record)","การเชื่อมต่อระบบ Server Record","ชุดต่อสายสัญญาณ (Head Box)","ตัวกระตุ้นด้วยเเสง (Electrical Photic)","ตัวขยายสัญญาณ (AMPLIFIER)","ตัวควบคุมหลัก (MAIN CONTROL SYSTEM)","ตัววัดออกซิเจนในเลือด (Pluse Oximeter)","สายต่อต่าง ๆ ในระบบ (ALL CABLE AMP. UNITE)","โปรแกรมตรวจการนอนหลับ (Ultrasom EEG)","โปรแกรมตรวจคลื่นไฟฟ้าสมอง (Aliance EEG)","โปรแกรมรายงานผลการตรวจ (Report generator)","≤10% voltage decrease \"","≤2 vol% or ≤5% expected value",];
	$(".pm-list-autocomplete").autocomplete({
	  source: PMLists,
	  minLength: 1
	});
}

function PMListSortable(TbID){
	$( "#"+TbID+"-sortable" ).sortable();
    //$( "#"+TbID+"-sortable" ).disableSelection();
}

function ShowHideTable(TbID){
	
	if( $("#"+TbID).css("display") == "none" ){
		$('#'+TbID).css({display:'block'});
		$('#'+TbID+'-collapse').html('<i class="fa fa-minus"></i>');
	}else{
		$('#'+TbID).css({display:'none'});
		$('#'+TbID+'-collapse').html('<i class="fa fa-plus"></i>');
	}

}

$( "#parameters-sortable" ).sortable({ cursor: "move" });
//$( "#parameters-sortable" ).disableSelection();
</script>
