<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
require_once('include/utils/CommonUtils.php');
$category = getParentTab();
global $theme,$app_strings,$mod_strings;
global $CALENDAR_DISPLAY, $WORLD_CLOCK_DISPLAY, $CALCULATOR_DISPLAY, $CHAT_DISPLAY;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

//https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css
//https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js
//https://unpkg.com/popper.js
//https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js
/*<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.2/css/bootstrap.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/popper.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>*/


$html_string = '
<div class="card-btn">
<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>

<tr><td style="height:2px"></td></tr>
<tr>
	<td width="20%" style="padding-left:10px;padding-right:30px" class="moduleName" nowrap>'.$app_strings[$category].' > <a class="hdrLink" href="index.php?action=index&module=Calendar&parenttab=Marketing">Sales Visit</a></td> 
	<td class="sep1" style="width:1px;"></td>
	<td  nowrap width="8%">
		<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td class="sep1" style="width:1px;"></td>
			<td class=small>
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					<!-- <td style="padding-left:10px; padding-right: 10px;" class="submenu">
					 <a href="index.php?module=Calendar&action=EditView&return_action=DetailView&parenttab='.$category.'">
					 	<img src="'.aicrm_imageurl('themes/softed/images/create.png', $theme).'" border=0 style="width: 20px; vertical-align: middle;">
					 	Create
					 </a>
					 </td>
					 <td style="padding-left:10px; color: #2B2B2B; font-size: 12px; font-family: PromptMedium; margin-left: 5px; font-weight: 400;">
					 <img src="'.aicrm_imageurl('themes/softed/images/search(1).png', $theme).'" border=0 style="width: 25px;height: 20px; vertical-align: middle;">Search in
					 </td> -->
		</tr>
		</table>
	</td>
			</tr>
			</table>
	</td>
                <td class="small" width="16%" align="left">

				<table border=0 cellspacing=0 cellpadding=5>

					<tr>'; 
 		 
 		if($CALENDAR_DISPLAY == 'true') 
 		        /*$html_string .= '               <td style="padding-right:0px;padding-left:10px;" class="submenu"><a href="javascript:;" onClick=\'fnvshobj(this,"miniCal");getMiniCal("parenttab='.$category.'");\'><img src="'.$image_path.'calendar.png" alt="'.$app_strings['LBL_CALENDAR_ALT'].'" title="'.$app_strings['LBL_CALENDAR_TITLE'].'" border=0 style="width: 20px;height: 20px; vertical-align: middle;"> Calendar</a></a></td>'; */
 		if($WORLD_CLOCK_DISPLAY == 'true') 
 		        /*$html_string .= '               <td style="padding-right:0px"><a href="javascript:;"><img src="'.$image_path.'btnL3Clock.gif" alt="'.$app_strings['LBL_CLOCK_ALT'].'" title="'.$app_strings['LBL_CLOCK_TITLE'].'" border=0 onClick="fnvshobj(this,\'wclock\');"></a></a></td>'; */
 		if($CALCULATOR_DISPLAY == 'true') 
 		        /*$html_string .= '               <td style="padding-right:0px"><a href="#"><img src="'.$image_path.'btnL3Calc.gif" alt="'.$app_strings['LBL_CALCULATOR_ALT'].'" title="'.$app_strings['LBL_CALCULATOR_TITLE'].'" border=0 onClick="fnvshobj(this,\'calculator_cont\');fetch_calc();"></a></td>'; */
 		if($CHAT_DISPLAY == 'true') 
 		        /*$html_string .= '               <td style="padding-right:10px"><a href="javascript:;" onClick=\'return window.open("index.php?module=Home&action=vtchat","Chat","width=600,height=450,resizable=1,scrollbars=1");\'><img src="'.$image_path.'tbarChat.gif" alt="'.$app_strings['LBL_CHAT_ALT'].'" title="'.$app_strings['LBL_CHAT_TITLE'].'" border=0></a></td>'; */
 		 
 		$html_string .= ' 
					<td style="padding-right:10px"><img src="'.$image_path.'btnL3Tracker.gif" alt="'.$app_strings['LBL_LAST_VIEWED'].'" title="'.$app_strings['LBL_LAST_VIEWED'].'" border=0 onClick="fnvshobj(this,\'tracker\');"></td>
				</tr>
				</table>
	</td>
               <td class="small" align="left" width="10%">

		<table border=0 cellspacing=0 cellpadding=5>
			<tr>
				<td style="padding-right:10px;padding-left:10px;">
				<img src="'.aicrm_imageurl('import_g.png', $theme).'" border="0" style="width:20px;height:20px;vertical-align: middle;">
				<span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">Import</span>
				</td>
				<td style="padding-right:10px">
				<img src="'.aicrm_imageurl('export_g.png', $theme).'" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
				<span style="font-size: 12px; font-family: PromptMedium; margin-left: 5px; color: #A9A9A9; font-weight: 400;">Export</span>
				</td>
			</tr>
		</table>	
	</td>
	<td class="small" align="left">	
	<table border=0 cellspacing=0 cellpadding=5>
	<tr>
	<td style="padding-left:0px;" class="submenu">
		<a href="javascript:;" onmouseout="fninvsh(\'allMenu\');" onclick="fnvshobj(this,\'allMenu\')">
			<img src="'.$image_path.'menu.png" alt="'.$app_strings['LBL_ALL_MENU_ALT'].'" title="'.$app_strings['LBL_ALL_MENU_TITLE'].'" border="0" style="width: 20px; height: 20px; vertical-align: middle;"> '.$app_strings['LBL_ALL_MENU_TITLE'].'
			</a>
	</td>';
	if((isPermitted('Settings','index') == 'yes'))
		$html_string .= '<td style="padding-left:10px;" class="submenu">
		<a href="index.php?module=Settings&action=ModuleManager&module_settings=true&formodule=Calendar&parenttab=Settings">
			<img src="'.$image_path.'tools_b.png" alt="'.$app_strings['LBL_SETTINGS'].'" title="'.$app_strings['LBL_SETTINGS'].'" border="0" style="width: 20px;height: 20px; vertical-align: middle;">
			 '.$app_strings['LBL_SETTINGS'].'
		</a>
		</td>';
	$html_string .= '</tr>
	</table>
	</td>			
	</tr>
	</table></td>
	</tr>
	<tr><td style="height:2px"></td></tr>
	</TABLE>
	</div>


	<table border=0 cellspacing=0 cellpadding=0 width=99% align=center>
	     <tr>
		     <td valign=top><img src="'.aicrm_imageurl('showPanelTopLeft.gif', $theme) .'"></td>

		     	<td class="showPanelBg" valign="top" width=100% style="padding:10px;">
			
			<!-- Calendar Tabs starts -->
			<div class="small" style="padding: 10px;">
			<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
						<table class="small" border="0" cellpadding="3" cellspacing="0" width="100%">
							<tr>
								<td class="dvtTabCache" style="width: 10px;" nowrap="nowrap">&nbsp;</td>';
								//<td class="dvtSelectedCell" align="center" nowrap="nowrap">'.$app_strings["Calendar"].'</td>	
								
							$html_string .='<td class="dvtSelectedCell" align="center" nowrap="nowrap">Sales Visit</td>	
								<td class="dvtUnSelectedCell" align="center" nowrap="nowrap"><a href="index.php?action=ListView&module=Calendar&parenttab='.$category.'">'.$mod_strings["LBL_ALL_EVENTS_TODOS"].'</a></td>
								<td class="dvtTabCache" style="width: 100%;">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="left" valign="top">
						<table class="dvtContentSpace" border="0" cellpadding="3" cellspacing="0" width="100%">
							<tr>
								<td align="left">
								<!-- content cache -->
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td style="padding: 10px;">
	';
	echo $html_string;
?>	
