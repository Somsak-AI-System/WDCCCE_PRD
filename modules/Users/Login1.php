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
 * $Header: /advent/projects/wesat/aicrm_crm/sugarcrm/modules/Users/Login.php,v 1.6 2005/01/08 13:15:03 jack Exp $
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

if(isset($_REQUEST["login_mode"]))
{
	$login_mode = $_REQUEST["login_mode"];
}
?>
<!--Added to display the footer in the login page by Dina-->
<style type="text/css">@import url("themes/<?php echo $theme; ?>/style.css");</style>
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

<br><br>

<table width="540" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td width="78"><img src="themes/images/bt1.jpg" name="Image4" width="78" height="78" border="0"></td>
    <td rowspan="4"><table width="448" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="330" background="themes/images/bg-login.jpg">
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
				<form action="index.php" method="post" name="DetailView" id="form">
				<input type="hidden" name="module" value="Users">
				<input type="hidden" name="action" value="Authenticate">
				<input type="hidden" name="return_module" value="Users">
				<input type="hidden" name="return_action" value="Login">
                <input type="hidden" name="login_mode" value="">
          <TABLE width="90%" border=0 align="center" cellPadding=0 cellSpacing=0>
            <TBODY>
              <TR>
                <TD height="40">&nbsp;</TD>
              </TR>
              <TR>
                <TD class="small"><!-- form elements -->
                    <BR>
                    <TABLE cellSpacing=0 cellPadding=5 width="100%" border=0>
                      <TBODY>
                        <TR>
                          <TD class="small" align=right width="36%"><span class="style8"><?php echo $current_module_strings['LBL_USER_NAME'] ?> : </span></TD>
                          <TD class="small" align=left width="64%"><input class="small" type="text" name="user_name" value="<?php echo $login_user_name ?>" tabindex="1"></TD>
                        </TR>
                        <TR>
                          <TD class="small" align=right width="36%"><span class="style8"> <?php echo $current_module_strings['LBL_PASSWORD'] ?> : </span></TD>
                          <TD class="small" align=left width="64%"><input class="small" type="password" size='20' name="user_password" value="<?php echo $login_password ?>" tabindex="2"></TD>
                        </TR>
                        <!--<TR>
                          <TD width="36%" align=right class="small"><span class="style8"> <?php echo $current_module_strings['LBL_THEME'] ?> : </span></TD>
                          <TD class="small" align=left width="64%"><select class="small" name='login_theme' style="width:70%" tabindex="3">
                            <?php echo get_select_options_with_id(get_themes(), $display_theme) ?>
                          </select></TD>
                        </TR> -->
                        <TR>
                          <TD class="small" align=right width="36%"><span class="style8"> <?php echo $current_module_strings['LBL_LANGUAGE'] ?> : </span></TD>
                          <TD class="small" align=left width="64%"><select class="small" name='login_language' style="width:70%" tabindex="4">
                            <?php echo get_select_options_with_id(get_languages(), $display_language) ?>
                          </select></TD>
                        </TR>
                           <?php
							if( isset($_SESSION['validation'])){
							?>
                        <TR>
                          <TD align="right" class="small" style8><font color="Red">
						  <?php echo $current_module_strings['VLD_ERROR']; ?> </font></TD>
                          <TD class="small">&nbsp;</TD>
                        </TR>
							<?php
							}
							else if(isset($login_error) && $login_error != "")
							{
							?>                        <TR>
                          <TD align="right" class="small" style8><b class="small"><font color="Brown">
						  <?php echo $login_error ?></font></b> </TD>
                          <TD class="small">&nbsp;</TD>
                        </TR>
							<?php
							}
							?>                        <TR>
                          <TD class="small" style8>&nbsp;</TD>
                          <TD class="small">
<input title="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" alt="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" accesskey="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" src="themes/images/BT-singin.gif" type="image" name="Login" value="  <?php echo $current_module_strings['LBL_LOGIN_BUTTON_LABEL'] ?>  "  tabindex="5">  

<input title="Login Call Center" alt="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" accesskey="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" src="themes/images/call_center.gif" type="image" name="Login" value="  <?php echo $current_module_strings['LBL_LOGIN_BUTTON_LABEL'] ?>  "  tabindex="5" onClick="this.form.login_mode.value='call_center';"> 
           
                        
        				</TD>
                        </TR>
                      </TBODY>
                    </TABLE>
                  <BR>
                    <BR></TD>
              </TR>
            </TBODY>
          </TABLE>
        </FORM></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><img src="themes/images/bt2.jpg" name="Image5" width="78" height="78" border="0"></td>
  </tr>
  <tr>
    <td><img src="themes/images/bt3.jpg" name="Image6" width="78" height="78" border="0"></td>
  </tr>
  <tr>
    <td><img src="themes/images/bt4.jpg" name="Image7" width="78" height="78" border="0"></td>
  </tr>
</table>