<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Users/Login.php,v 1.6 2005/01/08 13:15:03 jack Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$theme_path="themes/".$theme."/";
$image_path="include/images/";

global $app_language;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Users');

 define("IN_LOGIN", true);

include_once('vtlib/Vtiger/Language.php');
// Retrieve username and password from the session if possible.
if(isset($_SESSION["login_user_name"]))
{

	if (isset($_REQUEST['default_user_name']))
		$login_user_name = vtlib_purify($_REQUEST['default_user_name']);
	else
		$login_user_name = vtlib_purify($_SESSION['login_user_name']);
}
else
{
	if (isset($_REQUEST['default_user_name']))
	{
		$login_user_name = vtlib_purify($_REQUEST['default_user_name']);
	}
	elseif (isset($_REQUEST['ck_login_id_vtiger'])) {
		$login_user_name = get_assigned_user_name($_REQUEST['ck_login_id_vtiger']);
	}
	else
	{
		$login_user_name = $default_user_name;
	}
	$_session['login_user_name'] = $login_user_name;
}

$current_module_strings['VLD_ERROR'] = base64_decode('UGxlYXNlIHJlcGxhY2UgdGhlIFN1Z2FyQ1JNIGxvZ29zLg==');

// Retrieve username and password from the session if possible.
if(isset($_SESSION["login_password"]))
{
	$login_password = $_SESSION['login_password'];
}
else
{
	$login_password = $default_password;
	$_session['login_password'] = $login_password;
}

if(isset($_SESSION["login_error"]))
{
	$login_error = $_SESSION['login_error'];
}
if(isset($_SESSION["login_error1"]))
{
	$login_error = $_SESSION['login_error1'];
}
if(isset($_REQUEST["login_mode"]))
{
	$login_mode = $_REQUEST["login_mode"];
}
if($_REQUEST["action"]=="Timeout")
{
	$login_error = $mod_strings['ERR_USER_LOGIN1'];
}
//echo $_REQUEST["action"];
?>
<!--Added to display the footer in the login page by Dina-->
<style type="text/css">@import url("themes/<?php echo $theme; ?>/style.css");</style>

<style type="text/css">@import url("themes/<?php echo $theme; ?>/font-awesome/css/font-awesome.min.css");</style>
<style type="text/css">@import url("themes/<?php echo $theme; ?>/font-awesome/style.css");</style>

<style type="text/css">@import url("themes/<?php echo $theme; ?>/font-awesome/style.css");</style>

<style type="text/css">@import url("asset/css/login/style.min.css");</style>
<style type="text/css">@import url("asset/css/login/floating-label.css");</style>
<style>
body {
  font: 13px/20px 'Lucida Grande', Tahoma, Verdana, sans-serif;
  color: #404040;
/*background:#60AEEE ;*/

/*
background-image: -ms-radial-gradient(center, ellipse farthest-side, #75B848 0%, #5E943A 100%);
background-image: -moz-radial-gradient(center, ellipse farthest-side, #75B848 0%, #5E943A 100%);
background-image: -o-radial-gradient(center, ellipse farthest-side, #75B848 0%, #5E943A 100%);
background-image: -webkit-gradient(radial, center center, 0, center center, 486, color-stop(0, #75B848), color-stop(1, #5E943A));
background-image: -webkit-radial-gradient(center, ellipse farthest-side, #75B848 0%, #5E943A 100%);
background-image: radial-gradient(ellipse farthest-side at center, #75B848 0%, #5E943A 100%);
*/
background-image: -ms-linear-gradient(top left, #f27032 0%, #f27032 40%);
background-image: -moz-linear-gradient(top left, #f27032 0%, #f27032 40%);
background-image: -o-linear-gradient(top left, #f27032 0%, #f27032 40%);
background-image: -webkit-gradient(linear, left top, right bottom, color-stop(0, #f27032), color-stop(0.4, #f27032));
background-image: -webkit-linear-gradient(top left, #f27032 0%, #f27032 40%);
background-image: linear-gradient(180deg, #ffffff, #a9a9a9);
/* Note: This gradient may render differently in browsers that don't support the unprefixed gradient syntax */

/*

background-image: -ms-linear-gradient(top left, #5EDBBC 0%, #1F8A6E 50%);
background-image: -moz-linear-gradient(top left, #5EDBBC 0%, #1F8A6E 50%);
background-image: -o-linear-gradient(top left, #5EDBBC 0%, #1F8A6E 50%);
background-image: -webkit-gradient(linear, left top, right bottom, color-stop(0, #5EDBBC), color-stop(0.5, #1F8A6E));
background-image: -webkit-linear-gradient(top left, #5EDBBC 0%, #1F8A6E 50%);
background-image: linear-gradient(to bottom right, #5EDBBC 0%, #1F8A6E 50%);
*/
}
</style>
<script type="text/javascript" language="JavaScript">
<!-- Begin -->
function set_focus() {
	if (document.DetailView.user_name.value != '') {
		document.DetailView.user_password.focus();
		document.DetailView.user_password.select();
	}
	else document.DetailView.user_name.focus();
}
<!-- End -->
</script>

<!-- <div class="page-wrapper" style="top: 20%;">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body" style="padding: 0px;">
				<div class="col-12">
					<div class="row">
						<div class="col-6" style="padding-left: 0px; padding-right: 0px;">
							<div class="banner" style="width: 100%; height: 100%;">
								<img src="themes/softed/images/Photo2.png" style=" border-radius: 5px;"/>
							</div>
						</div>
						<div class="col-6" style="margin-top: 10px;">
							<div class="d-flex m-b-30 no-block">
								<div class="ml-auto">
									<div class="input-group">
										<span class="input-group-btn input-group-prepend">
											<button class="btn btn-secondary btn-outline active">EN</button>
										</span>
										<span class="input-group-btn input-group-prepend">
											<button class="btn btn-secondary btn-outline">TH</button>
										</span>
									</div>
								</div>
							</div>
							<form class="floating-labels m-t-40" action="index.php" method="post" name="DetailView" id="form">
								<?php
								if (isset($_REQUEST['ck_login_language_vtiger'])) {
									$display_language = $_REQUEST['ck_login_language_vtiger'];
								}
								else {
									$display_language = $default_language;
								}

								if (isset($_REQUEST['ck_login_theme_vtiger'])) {
									$display_theme = $_REQUEST['ck_login_theme_vtiger'];
								}
								else {
									$display_theme = $default_theme;
								}
								?>

								<div class="control-group">
									<label class="control-label visible-ie8 visible-ie9">เลือกภาษา</label>
									<div class="controls">
										<select class="flat_select_login" name='login_language' tabindex="4">
											<?php echo get_select_options_with_id(get_languages(), $display_language) ?>
										</select>
									</div>
								</div>
								<div class="col-sm-12" style="top: 65px;">
									<div class="col-sm-10">
										<input type="hidden" name="module" value="Users">
										<input type="hidden" name="action" value="Authenticate">
										<input type="hidden" name="return_module" value="Users">
										<input type="hidden" name="return_action" value="Login">
										<input type="hidden" name="login_mode" value="">
										<div class="form-group m-b-10">
											<input type="text" class="form-control" style="min-height: 39px;" autocomplete="off" name="user_name" id="user_name" value="<?php echo $login_user_name ?>" tabindex="1" placeholder="<?php echo $current_module_strings['LBL_USER_NAME'] ?>" />
											<span class="bar"></span>
											<label for="input1">Username</label>
										</div>
										<div class="form-group m-b-10">
											<div class="input-icon left">
											<i id="eye" class="icon-eye-open" style="margin-left: 270px;" onclick="changeviewpass()"></i>
											<input type="password" class="form-control" style="min-height: 39px;" type="password" name="user_password" id="user_password" value="<?php echo $login_password ?>" tabindex="2" placeholder="<?php echo $current_module_strings['LBL_PASSWORD'] ?>" />
											</div>
											<span class="bar"></span>
											<label for="input2">Password</label>
										</div>
										<div class="form-group" style="margin-bottom: 5px;">
										<div class="checkbox checkbox-success">
											<input type="checkbox" name="checkbox1" id="checkbox1">
											<label for="checkbox1" style="font-size: 11px; font-family:PromptMedium; color: #A9A9A9; font-weight: 400;">Remember me</label>
											<label for="checkbox1"><span></span>Remember me</label>
										</div>
										<div class="checkbox">
											<input type="checkbox" id="checkbox" name="" value="">
											<label for="checkbox" style="font-size: 11px; font-family:PromptMedium; color: #A9A9A9; font-weight: 400;"><span>Remember me</span></label>
										</div>
									</div>
									<div class="col-sm-10" style="display: contents;">
										<button type="submit" class="btn btn-success" style="width: 100%;">Sign in</button title="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" alt="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" accesskey="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>"  type="submit" name="Login" value="Sign In"  tabindex="5">
									</div>

									<div class="col-sm-10" style="display: contents;">
										
										<button type="submit" class="btn btn-success" style="width: 100%;margin-top: 5px;" onclick="this.form.login_mode.value='call_center';" value="Call Center">Call Center</button>
										
									</div>
										<div style="font-size:11px;">

											<?php
											if( isset($_SESSION['validation'])){
												//echo "55";
												?>
												<font color="Red"> <?php echo $current_module_strings['VLD_ERROR']; ?> </font>

												<?php
											}
											else if(isset($login_error) && $login_error != "")
											{
												?>
												<font color="Brown">
													<?php echo $login_error ?> </font></b>

													<?php
												}

												?>
											</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> -->

<div class="page-wrapper" style="top: 20%;">
	<div class="container-fluid">
		<div class="card">
			<div class="card-body" style="padding: 0px;">
				<div class="col-12">
					<div class="row">
						<div class="col-6" style="padding-left: 0px; padding-right: 0px;">
							<div class="banner" style="width: 100%; height: 100%;">
								<img src="themes/softed/images/img_login2.png" style=" border-radius: 5px;"/>
							</div>
						</div>
						<div class="col-6" style="margin-top: 10px;">
							<div class="d-flex m-b-30 no-block">
								<div class="ml-auto">
									<div class="input-group">
										<span class="input-group-btn input-group-prepend">
											<button class="btn btn-secondary btn-outline active">EN</button>
										</span>
										<span class="input-group-btn input-group-prepend">
											<button class="btn btn-secondary btn-outline">TH</button>
										</span>
									</div>
								</div>
							</div>
							<form class="floating-labels m-t-40" action="index.php" method="post" name="DetailView" id="form">
								<?php
								if (isset($_REQUEST['ck_login_language_vtiger'])) {
									$display_language = $_REQUEST['ck_login_language_vtiger'];
								}
								else {
									$display_language = $default_language;
								}

								if (isset($_REQUEST['ck_login_theme_vtiger'])) {
									$display_theme = $_REQUEST['ck_login_theme_vtiger'];
								}
								else {
									$display_theme = $default_theme;
								}
								?>

								<!-- <div class="control-group">
									<label class="control-label visible-ie8 visible-ie9">เลือกภาษา</label>
									<div class="controls">
										<select class="flat_select_login" name='login_language' tabindex="4">
											<?php echo get_select_options_with_id(get_languages(), $display_language) ?>
										</select>
									</div>
								</div> -->
								<div class="col-sm-12" style="top: 65px;">
									<div class="col-sm-10">
										<input type="hidden" name="module" value="Users">
										<input type="hidden" name="action" value="Authenticate">
										<input type="hidden" name="return_module" value="Users">
										<input type="hidden" name="return_action" value="Login">
										<input type="hidden" name="login_mode" value="" >
										<div class="form-group m-b-10">
											<input type="text" class="form-control" style="min-height: 39px;" autocomplete="off" name="user_name" id="user_name" value="<?php echo $login_user_name ?>" tabindex="1" placeholder="<?php echo $current_module_strings['LBL_USER_NAME'] ?>" />
											<!-- <span class="bar"></span> -->
											<!-- <label for="input1">Username</label> -->
										</div>
										<div class="form-group m-b-10">
											<div class="input-icon left">
											<i id="eye" class="icon-eye-open" style="margin-left: 270px;" onclick="changeviewpass()"></i>
											<input type="password" class="form-control" style="min-height: 39px;" type="password" name="user_password" id="user_password" value="<?php echo $login_password ?>" tabindex="2" placeholder="<?php echo $current_module_strings['LBL_PASSWORD'] ?>" />
											</div>
											<!-- <span class="bar"></span> -->
											<!-- <label for="input2">Password</label> -->
										</div>
										<div class="form-group" style="margin-bottom: 5px;">
										<!-- <div class="checkbox checkbox-success">
											<input type="checkbox" name="checkbox1" id="checkbox1">
											<label for="checkbox1" style="font-size: 11px; font-family:PromptMedium; color: #A9A9A9; font-weight: 400;">Remember me</label>
											<label for="checkbox1"><span></span>Remember me</label>
										</div> -->
										<div class="checkbox">
											<input type="checkbox" id="checkbox" name="" value="">
											<label for="checkbox" style="font-size: 11px; font-family:PromptMedium; color: #A9A9A9; font-weight: 400;"><span>Remember me</span></label>
										</div>
									</div>
									<div class="col-sm-10" style="display: contents;">
										<button type="submit" class="btn btn-success" style="width: 100%;">Sign in</button title="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" alt="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" accesskey="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>"  type="submit" name="Login" value="Sign In"  tabindex="5">
									</div>

									
										<div style="font-size:11px;">

											<?php
											if( isset($_SESSION['validation'])){
												//echo "55";
												?>
												<font color="Red"> <?php echo $current_module_strings['VLD_ERROR']; ?> </font>

												<?php
											}
											else if(isset($login_error) && $login_error != "")
											{
												?>
												<font color="Brown">
													<?php echo $login_error ?> </font></b>

													<?php
												}

												?>
											</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	table {
	    border-collapse: inherit !important;
	}
	@media (min-width: 1024px){
		.footer, .page-wrapper {
	    	margin-left: auto !important;
		}
	}
	.input-icon.left input {
    	padding-left: 10px !important;
	}
	.btn-success:not(:disabled):not(.disabled).active, .btn-success:not(:disabled):not(.disabled):active, .show>.btn-success.dropdown-toggle {
	    color: #fff !important;
	    background-color: #068af0 !important;
	    border-color: #068af0 !important;
	}
	.btn-success.focus, .btn-success:focus {
    	/*box-shadow: 0 0 0 0.2rem rgb(231 153 104) !important;*/
    	box-shadow: 0 0 0 0.2rem rgb(0 119 217 / 50%)
	}
	@font-face {
      font-family: PromptMedium;
      src: url(assets/fonts/Prompt-Medium.ttf);
    }

	.page-wrapper {
		background: none;
	}

	.container-fluid {
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.card {
		width: 869px;
		height: 485px;
		border-radius: 5px;
		box-shadow: 0px 0px 5px 0px rgba(0, 0, 0, 0.1);
	}

	.input-group>.input-group-prepend>.btn {
		border-radius: 5px;
		font-family: PromptMedium;
		color: #A9A9A9;
		background-color: #EDEDED;
		border: 0px;
	}

	.btn-secondary:not(:disabled):not(.disabled).active {
		color: #068af0;
		background-color: #ffffff;
	}

	.floating-labels .form-control {
		background-color: #EDEDED;
		font-family: PromptMedium;
		border-radius: 5px;
		border: 0px;
		font-weight: 400px;
		/*padding-bottom: 0px;*/
		padding-left: 10px;
		/*padding-top: 15px;*/
	}

	.floating-labels label {
		color: #A9A9A9;
		left: 10px;
		font-size: 16px;
	}

	.floating-labels .form-control:focus {
		background-color: #ffffff;
		border: 1px solid #068af0;
	}

	.floating-labels .focused label {
		top: 2px;
		font-size: 11px;
		color: #a9a9a9;
	}


	.btn-success {
		background-color: #068af0;
		border-color: #068af0;
	}

	.btn-success:hover {
		background-color: #068af0;
		border-color: #068af0;
	}

	/*input[type="checkbox"] {
		display:none;
	}
	input[type="checkbox"] + label span {
		display:inline-block;
		width:19px;
		height:19px;
		margin:-2px 10px 0 0;
		vertical-align:middle;
		background:url(check_radio_sheet.png) left top no-repeat;
		background-color: #EDEDED;
		cursor:pointer;
	}

	input[type="checkbox"]:checked + label span {
		background-color: #fff;
	}*/


	.container {
    margin-top: 50px;
    margin-left: 20px;
    margin-right: 20px;
	}
	.checkbox {
		width: 100%;
		margin: 15px auto;
		position: relative;
		display: block;
	}

	.checkbox input[type="checkbox"] {
		width: auto;
		opacity: 0.00000001;
		position: absolute;
		left: 0;
		margin-left: -20px;
	}
	.checkbox label {
		position: relative;
	}
	.checkbox label:before {
		content: '';
		position: absolute;
		left: 0;
		top: 0;
		margin: 4px;
		width: 22px;
		height: 22px;
		transition: transform 0.28s ease;
		border-radius: 2px;
		/*border: 2px solid #000;*/
		background-color: #EDEDED;
	}
	.checkbox label:after {
		content: '';
		display: block;
		width: 10px;
		height: 5px;
		border-bottom: 2px solid #ffffff;
		border-left: 2px solid #ffffff;
		-webkit-transform: rotate(-45deg) scale(0);
		transform: rotate(-45deg) scale(0);
		transition: transform ease 0.25s;
		will-change: transform;
		position: absolute;
		top: 12px;
		left: 10px;
	}
	.checkbox input[type="checkbox"]:checked ~ label::before {
		color: #ffffff;
		background-color: #068af0;
	}

	.checkbox input[type="checkbox"]:checked ~ label::after {
		-webkit-transform: rotate(-45deg) scale(1);
		transform: rotate(-45deg) scale(1);
	}

	.checkbox label {
		min-height: 34px;
		display: block;
		padding-left: 40px;
		margin-bottom: 0;
		font-weight: normal;
		cursor: pointer;
		vertical-align: sub;
	}
	.checkbox label span {
		position: absolute;
		top: 50%;
		-webkit-transform: translateY(-50%);
		transform: translateY(-50%);
	}
	.checkbox input[type="checkbox"]:focus + label::before {
		outline: 0;
	}

</style>

<script type="text/javascript">
function changeviewpass(){
	let el = document.getElementById('eye');
	if (el.className === 'icon-eye-open'){
	  el.className = 'icon-eye-close';
	  document.getElementById('user_password').type = 'text';
	} else {
	  el.className = 'icon-eye-open';
	  document.getElementById('user_password').type = 'password'; 
	}
}
</script>
