<?
//require_once ("/home/admin/domains/ai-crm.com/public_html/pcpc/library/genarate.inc.php");
require_once("config.inc.php");
global $dbconfig;
require_once("library/genarate.inc.php");
require_once("library/dbconfig.php");	
global $genarate;

$genarate = new genarate($dbconfig ,"DB");
//echo GetEmail("HelpDesk","Trouble Tickets","76889","ticketid");
function GetEmail($module,$pic_header,$crmid,$fieldid){
 
	$msg.='<html xmlns="http://www.w3.org/1999/xhtml">';	
	$msg.='<head>';
	$msg.='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
	$msg.='<title>Email Alert</title>';
	$msg.='</head>';
	$msg.=style();
	$msg.='<body topmargin="0" leftmargin="0">';
	$msg.='<table width="800" border="0" cellspacing="0" cellpadding="0">';
	$msg.='  <tr>';
	$msg.='    <td background="">'.pheader($pic_header).'</td>';
	$msg.='  </tr>';
	$msg.='  <tr>';
	$msg.='    <td>';
	
	//detail=====================================================================================
	$msg.= GetDetail($module,$crmid,$fieldid);
	//detail=====================================================================================

	$msg.='    </td>';
	$msg.='  </tr>';
	$msg.='  <tr>';
	$msg.='    <td align="center" colspan="2">'.pfooter().'</td>';
	$msg.='  </tr>';
	$msg.='</table>';		  	
	$msg.='</body>';
	$msg.='</html>';
	return $msg;
}
function style(){
	$msg='<style type="text/css">';
	$msg.='.small {';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='color: #000000;';
	$msg.='}';
	$msg.='';
	$msg.='.big {';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 12px;';
	$msg.='line-height: 18px;';
	$msg.='color: #000000;';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.componentName {';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 18px;';
	$msg.='line-height: 18px;';
	$msg.='color: #33338c;';
	$msg.='font-weight:bold;';
	$msg.='border-left:2px dotted #fff;';
	$msg.='padding:10px;';
	$msg.='}';
	$msg.='';
	$msg.='.genHeaderBig{';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 16px;';
	$msg.='line-height: 19px;';
	$msg.='color: #000000;';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.genHeaderSmall{';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 14px;';
	$msg.='line-height: 16px;';
	$msg.='color: #000000;';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.moduleName {';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 16px;';
	$msg.='color: #33338c;';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.hdrNameBg {';
	$msg.='background:#ffffff url(https://moaistd.moai-crm.com/crm/themes/softed/images/header-bg.png) repeat-x;';
	$msg.='';
	$msg.='}';
	$msg.='';
	$msg.='.hdrTabBg {';
	$msg.='background:#ffffff url(https://moaistd.moai-crm.com/crm/themes/softed/images/toolbar-bg.png) bottom repeat-x ;';
	$msg.='background-color:#fff;';
	$msg.='border-top: 1px solid #83bbea;';
	$msg.='}  ';
	$msg.='';
	$msg.='.tabSelected {';
	$msg.='background: #fff url(https://moaistd.moai-crm.com/crm/themes/softed/images/toolbar-bg.png) bottom repeat-x;';
	$msg.='background-color:#fff;';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-weight:bold;';
	$msg.='font-size: 11px;';
	$msg.='padding-left:10px;';
	$msg.='padding-right:10px;';
	$msg.='padding-top:2px;';
	$msg.='padding-bottom:2px;';
	$msg.='border-bottom:0px solid #ffffff;';
	$msg.='}';
	$msg.='';
	$msg.='.tabSelected a{';
	$msg.='color:white;';
	$msg.='text-transform: uppercase;';
	$msg.='text-decoration: underline;';
	$msg.='font-size: 12px;';
	$msg.='}';
	$msg.='';
	$msg.='.tabUnSelected {';
	$msg.='background: #fff url(https://moaistd.moai-crm.com/crm/themes/softed/images/toolbar-bg.png) bottom repeat-x;';
	$msg.='background-color:#fff;';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='padding-left:10px;';
	$msg.='padding-right:10px;';
	$msg.='padding-top:2px;';
	$msg.='padding-bottom:2px;';
	$msg.='border-bottom:0px solid #ffffff;';
	$msg.='}';
	$msg.='';
	$msg.='.tabUnSelected a{';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 12px;';
	$msg.='color:white;';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.tabSeperator {';
	$msg.='background: #fff url(https://moaistd.moai-crm.com/crm/themes/softed/images/toolbar-div.png) bottom no-repeat;';
	$msg.='background-color:#fff;';
	$msg.='width:2px;';
	$msg.='}';
	$msg.='';
	$msg.='.level2text {    ';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='padding-left:15px;';
	$msg.='padding-right:15px;';
	$msg.='padding-top:5px;';
	$msg.='padding-bottom:5px;';
	$msg.='font-weight:bold;';
	$msg.='color:white;    ';
	$msg.='}';
	$msg.='';
	$msg.='.level2text a{';
	$msg.='text-decoration:underline;';
	$msg.='color:#555555;';
	$msg.='}';
	$msg.='';
	$msg.='.level2text a:hover {';
	$msg.='text-decoration:underline;';
	$msg.='color:#555555;';
	$msg.='}';
	$msg.='';
	$msg.='.level2Bg {';
	$msg.='background:#fff url(https://moaistd.moai-crm.com/crm/themes/softed/images/level2Bg.gif) bottom repeat-x;';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='border-top:1px solid #b8b8b8;';
	$msg.='border-bottom:1px solid #bfbfbf;';
	$msg.='color:white;';
	$msg.='}';
	$msg.='';
	$msg.='.level2Bg a{';
	$msg.='color:#33338C;';
	$msg.='}';
	$msg.='';
	$msg.='.level2SelTab{';
	$msg.='font-weight:bold;';
	$msg.='color:#000000;';
	$msg.='padding-left:20px;';
	$msg.='padding-right:20px;';
	$msg.='padding-top:5px;';
	$msg.='padding-bottom:5px;';
	$msg.='}';
	$msg.='';
	$msg.='.level2UnSelTab{';
	$msg.='color:#000000;';
	$msg.='padding-left:20px;';
	$msg.='padding-right:20px;';
	$msg.='padding-top:5px;';
	$msg.='padding-bottom:5px;';
	$msg.='}';
	$msg.='';
	$msg.='.sep1 {';
	$msg.='background:#fff;';
	$msg.='}';
	$msg.='';
	$msg.='.showPanelBg {';
	$msg.='background:url(https://moaistd.moai-crm.com/crm/themes/softed/images/homePageSeperator.gif) no-repeat;';
	$msg.='}';
	$msg.='';
	$msg.='a {';
	$msg.='text-decoration:none;';
	$msg.='color:#0070BA;';
	$msg.='}';
	$msg.='';
	$msg.='a:hover {';
	$msg.='text-decoration:underline;';
	$msg.='}';
	$msg.='';
	$msg.='.hdrLink {';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 16px;';
	$msg.='text-decoration:none;';
	$msg.='color:#0070BA;';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='#showPanelTopLeft{visibility:hidden;}';
	$msg.='#showPanelTopRight{visibility:hidden;}';
	$msg.='#border2pxpopup{ border:2px solid #dddddd;}';
	$msg.='';
	$msg.='.hdrLink:hover {';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 16px;';
	$msg.='text-decoration:underline;';
	$msg.='color:#33338c;';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.searchBox {';
	$msg.='border: 0px solid #0000aa;';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='padding-left:2px;';
	$msg.='width:100px;';
	$msg.='background-color:#ffffef;';
	$msg.='}';
	$msg.='';
	$msg.='.searchBtn {';
	$msg.='border :0px #cccccc solid;';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='font-weight:bold;';
	$msg.='width:30px;';
	$msg.='height:19px;';
	$msg.='background: #9d9d9d url(https://moaistd.moai-crm.com/crm/themes/softed/images/button_serach.gif) bottom repeat-x;';
	$msg.='color:white;';
	$msg.='}';
	$msg.='';
	$msg.='.lvt {';
	$msg.='background-color:#ddf;';
	$msg.='border:0px solid #cce; ';
	$msg.='}';
	$msg.='';
	$msg.='.lvtBg {';
	$msg.='}';
	$msg.='';
	$msg.='.lvtHeaderText {';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 14px;';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.lvtCol {';
	$msg.='border-top:1px solid #fff;';
	$msg.='border-left:1px solid #ddd;';
	$msg.='border-right:0px solid #fff;';
	$msg.='border-bottom:0px solid #fff;';
	$msg.='background: #FFFFFF url(https://moaistd.moai-crm.com/crm/themes/softed/images/level2Bg.gif) repeat-x scroll center bottom;';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.lvtColData {';
	$msg.='background-color:#ffffff;';
	$msg.='}';
	$msg.='';
	$msg.='.lvtColDataHover {';
	$msg.='background-color:#ffffcc;';
	$msg.='}';
	$msg.='';
	$msg.='.dvHeaderText{';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 14px;';
	$msg.='color:#000000;';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.dvInnerHeader{';
	$msg.='	font-family:tahoma;';
	$msg.='	font-size: 11px;';	
	$msg.='border-top:1px solid #dedede;';
	$msg.='border-left:1px solid #dedede;';
	$msg.='border-right:1px solid #dedede;';
	$msg.='border-bottom:1px solid #dedede;';
	$msg.='padding:12px;';
	$msg.='background:  #dddcdd   url(https://moaistd.moai-crm.com/crm/themes/softed/images/inner.gif) bottom repeat-x; ';
	$msg.='color: #000000;';
	$msg.='}';
	$msg.='';
	$msg.='.dvtSelectedCell {';
	$msg.='border-left:1px solid #e0dddd;';
	$msg.='border-top:1px solid #e0dddd;';
	$msg.='border-bottom:0px solid #e0dddd;';
	$msg.='border-right:1px solid #e0dddd;';
	$msg.='padding-left:20px;';
	$msg.='padding-right:20px;';
	$msg.='background:#ffffff url(https://moaistd.moai-crm.com/crm/themes/softed/images/tab.gif) top repeat-x; ';
	$msg.='font-weight:bold;';
	$msg.='color: black;';
	$msg.='line-height:20px;';
	$msg.='}';
	$msg.='';
	$msg.='.dvtUnSelectedCell {';
	$msg.='border-left:1px solid #dedede;';
	$msg.='border-top:1px solid #e0dddd;';
	$msg.='border-right:1px solid #e0dddd;';
	$msg.='border-bottom:1px solid #e0dddd;';
	$msg.='padding-left:20px;';
	$msg.='padding-right:20px;';
	$msg.='background: #FFF ;';
	$msg.='cursor:pointer;';
	$msg.='color: black;';
	$msg.='}';
	$msg.='';
	$msg.='.dvtTabCache {';
	$msg.='border-bottom:1px solid #dedede;';
	$msg.='}';
	$msg.='';
	$msg.='.dvtContentSpace {';
	$msg.='border-left:1px solid #dedede;';
	$msg.='border-right:1px solid #dedede;';
	$msg.='border-bottom:1px solid #dedede;';
	$msg.='padding-left:0px;';
	$msg.='}';
	$msg.='';
	$msg.='.dvtCellLabel, .cellLabel {';
	$msg.='background:#F7F7F7 url(https://moaistd.moai-crm.com/crm/themes/softed/images/testsidebar.jpg) repeat-y scroll right center;';
	$msg.='	font-family:tahoma;';
	$msg.='	font-size: 11px;';	
	$msg.='border-bottom:1px solid #DEDEDE;';
	$msg.='border-left:1px solid #DEDEDE;';
	$msg.='border-right:1px solid #DEDEDE;';
	$msg.='color:#545454;';
	$msg.='padding-left:10px;';
	$msg.='padding-right:10px;';
	$msg.='white-space:nowrap;}';
	$msg.='';
	$msg.='.dvtCellInfo, .cellInfo {';
	$msg.='	font-family:tahoma;';
	$msg.='	font-size: 11px;';	
	$msg.='padding-left:10px;';
	$msg.='padding-right:10px;';
	$msg.='border-bottom:1px solid #dedede;';
	$msg.='border-right:1px solid #dedede;';
	$msg.='border-left:1px solid #dedede;';
	$msg.='}';
	$msg.='';
	$msg.='.rightMailMerge {';
	$msg.='border:1px solid #dedede;';
	$msg.='}';
	$msg.='';
	$msg.='.rightMailMergeHeader {';
	$msg.='border-bottom:1px solid #ffffff;';
	$msg.='padding:5px;';
	$msg.='background-color:#000000;';
	$msg.='background:  url(https://moaistd.moai-crm.com/crm/themes/softed/images/level2Bg.gif) bottom repeat-x;';
	$msg.='color:#555555;';
	$msg.='';
	$msg.='}';
	$msg.='';
	$msg.='.rightMailMergeContent {';
	$msg.='padding:5px;';
	$msg.='background-color:#ffffff;';
	$msg.='}';
	$msg.='';
	$msg.='.detailedViewHeader{';
	$msg.='';
	$msg.='border:1px solid #DDDDDD;';
	$msg.='padding:12px;';
	$msg.='background:  #dddcdd   url(https://moaistd.moai-crm.com/crm/themes/softed/images/inner.gif) bottom repeat-x; ';
	$msg.='color: #000000;';
	$msg.='}';
	$msg.='';
	$msg.='.detailedViewTextBox {';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='color: #000000;';
	$msg.='border:1px solid #bababa;';
	$msg.='padding-left:5px;';
	$msg.='width:90%;';
	$msg.='background-color:#ffffff;';
	$msg.='}';
	$msg.='';
	$msg.='.detailedViewTextBoxOn {';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='color: #000000;';
	$msg.='border:1px solid #bababa;';
	$msg.='padding-left:5px;';
	$msg.='width:90%;';
	$msg.='background-color:#ffffdd;';
	$msg.='}';
	$msg.='.detailedViewTextBoxacc {';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='color: #000000;';
	$msg.='border:1px solid #bababa;';
	$msg.='padding-left:5px;';
	$msg.='width:500px;';
	$msg.='height:35px;';
	$msg.='background-color:#ffffff;';
	$msg.='}';
	$msg.='.detailedViewTextBoxOnacc {';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='color: #000000;';
	$msg.='border:1px solid #bababa;';
	$msg.='padding-left:5px;';
	$msg.='width:500px;';
	$msg.='height:35px;;';
	$msg.='background-color:#ffffdd;';
	$msg.='}';
	$msg.='';
	$msg.='.advSearch {';
	$msg.='padding:10px;';
	$msg.='border-left:1px solid #eaeaea; ';
	$msg.='border-right:1px solid #eaeaea; ';
	$msg.='border-bottom:1px solid #eaeaea; ';
	$msg.='overflow:auto;';
	$msg.='width:600px;';
	$msg.='height:150px;';
	$msg.='background-color:#ffffef;';
	$msg.='';
	$msg.='}';
	$msg.='';
	$msg.='.searchAlph {';
	$msg.='border:1px solid #dadada;';
	$msg.='background-color:#ffffff;';
	$msg.='cursor:pointer;';
	$msg.='width:10px';
	$msg.='';
	$msg.='}';
	$msg.='';
	$msg.='.searchAlphselected {';
	$msg.='border:1px solid #666666;';
	$msg.='background-color:#bbbbbb;';
	$msg.='cursor:pointer;';
	$msg.='width:10px';
	$msg.='}';
	$msg.='';
	$msg.='.textbox {';
	$msg.='border:1px solid #999999;';
	$msg.='background-color:#ffffff;';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='width:120px;';
	$msg.='padding:2px;';
	$msg.='}';
	$msg.='';
	$msg.='.mx {';
	$msg.='}';
	$msg.='';
	$msg.='.calListTable td{';
	$msg.='border-bottom:1px solid #efefef;';
	$msg.='border-left:1px solid #efefef;';
	$msg.='}';
	$msg.='';
	$msg.='.calDIV {';
	$msg.='width:100%;';
	$msg.='border:1px solid #fff;';
	$msg.='';
	$msg.='}';
	$msg.='';
	$msg.='.calDayHour {';
	$msg.='border-top:1px solid #c5d5ff;';
	$msg.='border-left:1px solid #c5d5ff;';
	$msg.='border-bottom:1px solid #c5d5ff;';
	$msg.='border-right:1px solid #c5d5ff;';
	$msg.='background-color:#eae7da;';
	$msg.='}';
	$msg.='';
	$msg.='.calAddEvent {';
	$msg.='position:absolute;';
	$msg.='z-index:10000;';
	$msg.='width:500px;';
	$msg.='left:200px;';
	$msg.='top:150px;';
	$msg.='background-color:#ffffff;';
	$msg.='}';
	$msg.='.addEventInnerBox {';
	$msg.='border:1px dotted #dadada;';
	$msg.='}';
	$msg.='';
	$msg.='.cellNormal {';
	$msg.='border:0px;';
	$msg.='background-color:#ffffff;';
	$msg.='}';
	$msg.='';
	$msg.='.EventToDo{';
	$msg.='';
	$msg.='}';
	$msg.='';
	$msg.='.thumbnail{';
	$msg.='background-color:#ffffff;';
	$msg.='padding: 7px;';
	$msg.='border: 1px solid #ddd;';
	$msg.='float: left;';
	$msg.='margin-right: 10px;';
	$msg.='margin-bottom: 15px;';
	$msg.='}';
	$msg.='';
	$msg.='.padTab{';
	$msg.='padding-top:0px;';
	$msg.='padding-left:10px;';
	$msg.='padding-bottom:0px;';
	$msg.='vertical-align:top;';
	$msg.='';
	$msg.='}';
	$msg.='#user{position:relative;left:0px;top:0px;width:100%;display:block;}';
	$msg.='';
	$msg.='#communication{position:relative;left:0px;top:0px;width:100%;display:block;}';
	$msg.='';
	$msg.='#config{position:relative;left:0px;top:0px;width:100%;display:block;}';
	$msg.='';
	$msg.='#userTab{position:relative;left:0px;top:0px;width:100%;display:none;}';
	$msg.='';
	$msg.='.calAddButton {';
	$msg.='border: 1px solid #ddd;';
	$msg.='background:#ffffff url(https://moaistd.moai-crm.com/crm/themes/softed/images/calAddButton.gif) repeat-x;';
	$msg.='cursor:pointer;';
	$msg.='height:30px;';
	$msg.='line-height:25px;';
	$msg.='}';
	$msg.='';
	$msg.='.calInnerBorder {';
	$msg.='border-left:2px solid #ddd;';
	$msg.='border-right:2px solid #ddd;';
	$msg.='border-bottom:2px solid #ddd;';
	$msg.='}';
	$msg.='';
	$msg.='.calBorder {';
	$msg.='border-left: 1px solid #ddd; ';
	$msg.='border-right: 1px solid #ddd; ';
	$msg.='border-bottom: 1px solid #ddd; ';
	$msg.='background:#fff url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);';
	$msg.='';
	$msg.='}';
	$msg.='.calTopRight {';
	$msg.='border-right: 1px solid #ddd; ';
	$msg.='}';
	$msg.='';
	$msg.='.calUnSel{';
	$msg.='color:#000000;';
	$msg.='font-weight:normal;';
	$msg.='text-align:center;';
	$msg.='width:8%;';
	$msg.='border-right:1px solid #DEDEDE;';
	$msg.='}';
	$msg.='';
	$msg.='a.calMnu{';
	$msg.='font-size:11px;';
	$msg.='color:#0070BA;';
	$msg.='text-decoration:none;';
	$msg.='display:block;';
	$msg.='height:20px;';
	$msg.='padding-left:5px;';
	$msg.='padding-top:3px;';
	$msg.='}';
	$msg.='';
	$msg.='a.calMnu:Hover{';
	$msg.='font-size:11px;';
	$msg.='color:#0070BA;';
	$msg.='text-decoration:underline;';
	$msg.='display:block;';
	$msg.='height:20px;';
	$msg.='padding-left:5px;';
	$msg.='padding-top:3px;';
	$msg.='}';
	$msg.='';
	$msg.='.bgwhite{';
	$msg.='background-color: #FFFFFF;';
	$msg.='}';
	$msg.='.copy{';
	$msg.='font-size:9px;';
	$msg.='font-family: Verdana, Arial, Helvetica, Sans-serif;';
	$msg.='}';
	$msg.='';
	$msg.='#mnuTab{';
	$msg.='position:relative;';
	$msg.='width:100%;';
	$msg.='display:block;';
	$msg.='}';
	$msg.='';
	$msg.='#mnuTab2{';
	$msg.='position:relative;';
	$msg.='overflow:auto;';
	$msg.='width:100%;';
	$msg.='display:none;';
	$msg.='}';
	$msg.='.style1 {color: #FF0000}';
	$msg.='';
	$msg.='.event{';
	$msg.='background-color:#fb802f;';
	$msg.='border:2px solid #dddddd;';
	$msg.='text-align:left;';
	$msg.='width:100%;';
	$msg.='position:relative;';
	$msg.='left:0px;';
	$msg.='top:0px;';
	$msg.='vertical-align:middle;';
	$msg.='padding:1px;';
	$msg.='}';
	$msg.='';
	$msg.='#hrView{';
	$msg.='display:block;';
	$msg.='}';
	$msg.='';
	$msg.='.calendarNav{';
	$msg.='font-size:16px;';
	$msg.='color:#33338C;';
	$msg.='white-space:nowrap;';
	$msg.='text-align:center;';
	$msg.='font-weight:bold;';
	$msg.='padding-left:10px;';
	$msg.='padding-right:10px;';
	$msg.='background:#FFFFFF none repeat scroll 0%;';
	$msg.='}';
	$msg.='';
	$msg.='#addEventDropDown{';
	$msg.='position:absolute;';
	$msg.='display:none;';
	$msg.='width:150px;';
	$msg.='border:1px solid #ddd;';
	$msg.='left:0px;';
	$msg.='top:0px;';
	$msg.='overflow:visible;';
	$msg.='z-index:5000;';
	$msg.='}';
	$msg.='';
	$msg.='.calAction{';
	$msg.='width:175px;';
	$msg.='background-color:#CCCCCC;';
	$msg.='border:1px solid #DDDDDD;';
	$msg.='padding-top:5px;';
	$msg.='position:absolute;';
	$msg.='display:none;';
	$msg.='z-index:2000;';
	$msg.='}';
	$msg.='';
	$msg.='.calSettings{';
	$msg.='position:absolute;';
	$msg.='z-index:20000;';
	$msg.='width:500px;';
	$msg.='left:200px;';
	$msg.='top:150px;';
	$msg.='background-color:#ffffff;';
	$msg.='}';
	$msg.='';
	$msg.='.outer{';
	$msg.='border-bottom:1px solid #CCCCCC;';
	$msg.='border-left:1px solid #CCCCCC;';
	$msg.='border-right:1px solid #CCCCCC;';
	$msg.='}';
	$msg.='';
	$msg.='.calTxt{';
	$msg.='width:50%;';
	$msg.='border:1px solid #CCCCCC;';
	$msg.='font-family:Arial, Helvetica, sans-serif;';
	$msg.='font-size:11px;';
	$msg.='text-align:left;';
	$msg.='padding-left:5px;';
	$msg.='}';
	$msg.='';
	$msg.='#leadLay{';
	$msg.='position:relative;';
	$msg.='width:100%;';
	$msg.='float:left;';
	$msg.='visibility:hidden;';
	$msg.='padding:5px;';
	$msg.='z-index:10000;';
	$msg.='}';
	$msg.='';
	$msg.='.eventDay{';
	$msg.='background-color:#FF9966;';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.currDay{';
	$msg.='background:#5774B0 url(https://moaistd.moai-crm.com/crm/themes/softed/images/toolbar-bg.png) repeat scroll 0%;';
	$msg.='border:1px solid #DEDEDE;';
	$msg.='font-weight:bold;';
	$msg.='text-decoration:underline;';
	$msg.='}';
	$msg.='';
	$msg.='.currDay a{';
	$msg.='color:#FFFFFF;';
	$msg.='font-weight:bold;';
	$msg.='text-decoration:underline;';
	$msg.='}';
	$msg.='';
	$msg.='level2Sel{';
	$msg.='color:#000000;';
	$msg.='font-weight:bold;';
	$msg.='text-decoration:underline;';
	$msg.='}';
	$msg.='';
	$msg.='#DeleteLay{';
	$msg.='font-family:Arial, Helvetica, sans-serif;';
	$msg.='font-size:11px;';
	$msg.='text-align:left;';
	$msg.='width:300px;';
	$msg.='border:3px solid #CCCCCC;';
	$msg.='background-color:#FFFFFF;';
	$msg.='padding:5px;';
	$msg.='}';
	$msg.='';
	$msg.='#CurrencyDeleteLay{';
	$msg.='font-family:Arial, Helvetica, sans-serif;';
	$msg.='font-size:11px;';
	$msg.='text-align:left;';
	$msg.='width:350px;';
	$msg.='}';
	$msg.='.rptCellLabel {';
	$msg.='background-color:#f6f6f6;';
	$msg.='padding-right:10px;';
	$msg.='border-right: 1px solid #DDDDDD;';
	$msg.='border-bottom:1px solid #fff;';
	$msg.='color:#737373;';
	$msg.='font-weight: bold;';
	$msg.='white-space:nowrap;';
	$msg.='}';
	$msg.='';
	$msg.='.rptTable {';
	$msg.='border-left: 1px solid #DDDDDD;';
	$msg.='border-bottom: 1px solid #DDDDDD;';
	$msg.='border-top: 1px solid #DDDDDD;';
	$msg.='}';
	$msg.='';
	$msg.='.rptTitle, .rptHead, .rptData, .rptGrpHead{';
	$msg.='font-family: Verdana, Arial, Helvetica, Sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='text-align:left;';
	$msg.='font-weight: normal;';
	$msg.='height: 20px;';
	$msg.='padding: 4px;';
	$msg.='border-right: 1px solid #DDDDDD;';
	$msg.='border-bottom: 1px solid #DDDDDD;';
	$msg.='background: #DDDDDD;';
	$msg.='}';
	$msg.='';
	$msg.='.rptGrp1Total, .rptGrp2Total, .rptTotal {';
	$msg.='font-family: Verdana, Arial, Helvetica, Sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='text-align:center;';
	$msg.='font-weight: normal;';
	$msg.='background: #FFF;';
	$msg.='height: 20px;';
	$msg.='padding: 4px;';
	$msg.='border-right: 1px solid #DDDDDD;';
	$msg.='border-bottom: 1px solid #DDDDDD;';
	$msg.='}';
	$msg.='.rptGrpHead {';
	$msg.='background: #FFF;';
	$msg.='border-bottom: 1px solid #FFF;';
	$msg.='border-top: 1px solid #DDDDDD;';
	$msg.='font-weight: normal;';
	$msg.='}';
	$msg.='';
	$msg.='.rptData {';
	$msg.='background: #FFF;';
	$msg.='font-weight: normal;';
	$msg.='}';
	$msg.='';
	$msg.='.rptEmptyGrp {';
	$msg.='background: #FFF;';
	$msg.='border-right: 1px solid #DDDDDD;';
	$msg.='}';
	$msg.='';
	$msg.='.statechange {';
	$msg.='position:absolute;';
	$msg.='visibility:hidden;';
	$msg.='left:10px;';
	$msg.='top:20px;';
	$msg.='width:300px;';
	$msg.='border:3px solid #CCCCCC;';
	$msg.='background-color:#FFFFFF;';
	$msg.='}';
	$msg.='';
	$msg.='#PopupLay{';
	$msg.='position:absolute;';
	$msg.='font-family:Arial, Helvetica, sans-serif;';
	$msg.='font-size:11px;';
	$msg.='text-align:left;';
	$msg.='width:500px;';
	$msg.='border:3px solid #CCCCCC;';
	$msg.='background-color:#FFFFFF;';
	$msg.='padding:5px;';
	$msg.='display:none;';
	$msg.='left:100px;';
	$msg.='top:100px;';
	$msg.='}';
	$msg.='';
	$msg.='#folderLay{';
	$msg.='width:175px;';
	$msg.='background-color:#CCCCCC;';
	$msg.='border:1px solid #DDDDDD;';
	$msg.='padding-top:5px;';
	$msg.='position:absolute;';
	$msg.='display:none;';
	$msg.='}';
	$msg.='.qcTransport{';
	$msg.='}';
	$msg.='';
	$msg.='#role_popup{';
	$msg.='position:relative;';
	$msg.='left:0px;';
	$msg.='top:0px;';
	$msg.='width:95%;';
	$msg.='height:300px;';
	$msg.='overflow:auto;';
	$msg.='border:1px solid #999999;';
	$msg.='text-align:left;';
	$msg.='background-color:#FFFFFF;';
	$msg.='}';
	$msg.='';
	$msg.='.unread_email {';
	$msg.='font-weight:bold;';
	$msg.='background-color:#00FF00;';
	$msg.='}';
	$msg.='.delete_email {';
	$msg.='font-weight:bold;';
	$msg.='background-color:#FF5151;';
	$msg.='';
	$msg.='}';
	$msg.='';
	$msg.='.qualify_email {';
	$msg.='font-weight:bold;';
	$msg.='background-color:#CDB5CD;';
	$msg.='}';
	$msg.='.unread_email:hover {';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.tagCloud {';
	$msg.='border:1px solid #ddd;';
	$msg.='}';
	$msg.='';
	$msg.='.tagCloudTopBg {';
	$msg.='background: url(https://moaistd.moai-crm.com/crm/themes/softed/images/tagCloudBg.gif) repeat-x;';
	$msg.='}';
	$msg.='';
	$msg.='.tagCloudDisplay {';
	$msg.='background-color:#fff;';
	$msg.='padding:10px;';
	$msg.='font-family: Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='line-height: 14px;';
	$msg.='color: #000000;';
	$msg.='}';
	$msg.='';
	$msg.='.h2 {';
	$msg.='font-size:18px;';
	$msg.='line-height:20px;';
	$msg.='}';
	$msg.='';
	$msg.='.gray {';
	$msg.='color:gray;';
	$msg.='}';
	$msg.='';
	$msg.='ul {';
	$msg.='list-style:circle;';
	$msg.='line-height:20px;';
	$msg.='padding-left:5px;';
	$msg.='margin-left:20px;';
	$msg.='font-weight:normal;';
	$msg.='}';
	$msg.='';
	$msg.='.tagCloudTopBg {';
	$msg.='background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/tagCloudBg.gif);';
	$msg.='background-repeat: repeat-x;';
	$msg.='}';
	$msg.='';
	$msg.='.tagCloudDisplay {';
	$msg.='background-color:#fff;';
	$msg.='padding:5px;';
	$msg.='}';
	$msg.='';
	$msg.='.heading2 {';
	$msg.='font-family:  Arial, Helvetica, sans-serif;';
	$msg.='font-size: 16px;';
	$msg.='line-height: 16px;';
	$msg.='font-weight:bold;';
	$msg.='color: #000000;';
	$msg.='}';
	$msg.='';
	$msg.='.settingsUI {';
	$msg.='text-align:left;';
	$msg.='background-color:#fff;';
	$msg.='background-image:url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);';
	$msg.='border:2px solid #ddd;';
	$msg.='}';
	$msg.='';
	$msg.='.settingsTabHeader {';
	$msg.='text-align:left;';
	$msg.='font-family:  Arial, Helvetica, sans-serif;';
	$msg.='font-size: 12px;';
	$msg.='line-height:22px;';
	$msg.='font-weight:bold;';
	$msg.='color:#33338c; ';
	$msg.='background-color:#efecec;';
	$msg.='padding-left:10px;';
	$msg.='padding-right:10px;';
	$msg.='border-top: #000000;';
	$msg.='}';
	$msg.='';
	$msg.='.settingsTabList {';
	$msg.='text-align:left;';
	$msg.='font-family:  Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='line-height:20px;';
	$msg.='font-weight:normal;';
	$msg.='color:#000000;';
	$msg.='background-color:#fff;';
	$msg.='padding-left:30px;';
	$msg.='border-top:1px solid #fff;';
	$msg.='border-bottom:1px solid #ddd;';
	$msg.='border-right:1px solid #ddd;';
	$msg.='border-left:1px solid #fff;';
	$msg.='}';
	$msg.='';
	$msg.='.settingsTabSelected {';
	$msg.='text-align:left;';
	$msg.='font-family:  Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='line-height:20px;';
	$msg.='font-weight:bold;';
	$msg.='color:#000000;';
	$msg.='background-color:#ffffff;';
	$msg.='padding-left:30px;';
	$msg.='border-left:3px solid #ddd;';
	$msg.='border-bottom:3px solid #ddd;';
	$msg.='border-top:2px solid #ddd;';
	$msg.='}';
	$msg.='';
	$msg.='.settingsSelectedUI {';
	$msg.='padding-bottom: 5px;';
	$msg.='background-color:#ffffff;  ';
	$msg.='background:url(https://moaistd.moai-crm.com/crm/themes/softed/images/settingsSelUIBg.gif) repeat-x;';
	$msg.='padding:  15px 25px ;';
	$msg.='';
	$msg.='}';
	$msg.='';
	$msg.='.settingsIconDisplay {';
	$msg.='font-family:  Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='line-height:14px;';
	$msg.='padding:10px;';
	$msg.='color:#000000;';
	$msg.='background-color:#ffffff;';
	$msg.='padding-left:10px;';
	$msg.='}';
	$msg.='';
	$msg.='.settingsSelUITopLine{';
	$msg.='border-bottom:2px dotted #999999;';
	$msg.='}';
	$msg.='';
	$msg.='.tableHeading{';
	$msg.='background:#FFFFFF url(https://moaistd.moai-crm.com/crm/themes/softed/images/level2Bg.gif) repeat-x scroll center bottom;';
	$msg.='border:1px solid #DEDEDE;';
	$msg.='}';
	$msg.='';
	$msg.='.colHeader{';
	$msg.='background-color:#fff;';
	$msg.='background-image:url(https://moaistd.moai-crm.com/crm/themes/softed/images/mailSubHeaderBg-grey.gif);';
	$msg.='border-left:1px solid #fff;';
	$msg.='border-top:1px solid #ddd;';
	$msg.='border-right:1px solid #ddd;';
	$msg.='border-bottom:1px solid #ddd;';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.cellLabel {';
	$msg.='background-color:#f5f5ff;';
	$msg.='border-top:1px solid #efefef;';
	$msg.='border-bottom:1px solid #dadada;';
	$msg.='color:#555555;';
	$msg.='}';
	$msg.='';
	$msg.='.cellText {';
	$msg.='color:#333333;';
	$msg.='border-bottom:1px solid #dadada;';
	$msg.='}';
	$msg.='';
	$msg.='.listTable{';
	$msg.='border-left:1px solid #cccccc;';
	$msg.='border-right:1px solid #cccccc;';
	$msg.='border-bottom:1px solid #cccccc;';
	$msg.='}';
	$msg.='';
	$msg.='.listTableRow{';
	$msg.='border-bottom:1px solid #eaeaea;';
	$msg.='border-right:1px solid #eaeaea;';
	$msg.='border-bottom:1px solid #eaeaea;';
	$msg.='border-bottom:1px solid #eaeaea;';
	$msg.='}';
	$msg.='';
	$msg.='.listRow{';
	$msg.='border-bottom:2px solid #eaeaea;';
	$msg.='}';
	$msg.='';
	$msg.='.listTableTopButtons{';
	$msg.='background-color:#efefff;';
	$msg.='background-image:url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);';
	$msg.='}';
	$msg.='';
	$msg.='.crmButton{';
	$msg.='border-left:1px solid #ffffff;';
	$msg.='border-top:1px solid #ffffff;';
	$msg.='border-right:1px solid #555555;';
	$msg.='border-bottom:1px solid #555555;';
	$msg.='}';
	$msg.='';
	$msg.='.create{';
	$msg.='background-color:#5774b0;';
	$msg.='color:#fff;';
	$msg.='font-weight:bold;';
	$msg.='background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/toolbar-bg.png);';
	$msg.='}';
	$msg.='';
	$msg.='.delete {';
	$msg.='background-color:red;';
	$msg.='color:#fff;';
	$msg.='font-weight:bold;';
	$msg.='background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/buttonred.png);  ';
	$msg.='}';
	$msg.='';
	$msg.='.edit {';
	$msg.='background-color:green;';
	$msg.='color:#fff;';
	$msg.='font-weight:bold;';
	$msg.='background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/buttongreen.png);';
	$msg.='}';
	$msg.='';
	$msg.='.save{';
	$msg.='background-color:green;';
	$msg.='color:#fff;';
	$msg.='font-weight:bold;';
	$msg.='background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/buttongreen.png);';
	$msg.='}';
	$msg.='.report{';
	$msg.='background-color:yellow;';
	$msg.='color:#333333;';
	$msg.='font-weight:bold;';
	$msg.='background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/buttonyellow.png);';
	$msg.='}';
	$msg.='.cancel {';
	$msg.='background-color: orange;';
	$msg.='color:#fff;';
	$msg.='font-weight:bold;';
	$msg.='background-image: url(https://moaistd.moai-crm.com/crm/themes/softed/images/buttonorange.png);';
	$msg.='}';
	$msg.='';
	$msg.='.inactive{';
	$msg.='color:#999999;';
	$msg.='}';
	$msg.='';
	$msg.='.active{';
	$msg.='color:#229922;';
	$msg.='}';
	$msg.='';
	$msg.='textarea {';
	$msg.='width:95%;';
	$msg.='height:70px;';
	$msg.='border:1px solid #dadada;';
	$msg.='}';
	$msg.='';
	$msg.='.treeTable1{';
	$msg.='padding:0px;';
	$msg.='}';
	$msg.='';
	$msg.='.cellBottomDotLine {';
	$msg.='border-bottom-width: 2px;';
	$msg.='border-bottom-style: dotted;';
	$msg.='border-bottom-color: #CCCCCC;';
	$msg.='background-color:#ededed;';
	$msg.='}';
	$msg.='';
	$msg.='.crmFormList{';
	$msg.='border:1px solid #cccccc;';
	$msg.='width:90%;';
	$msg.='height:120px;';
	$msg.='}';
	$msg.='';
	$msg.='.cellBottomDotLinePlain {';
	$msg.='border-bottom-width: 2px;';
	$msg.='border-bottom-style: dotted;';
	$msg.='border-bottom-color: #CCCCCC;';
	$msg.='}';
	$msg.='';
	$msg.='.thickBorder {';
	$msg.='border: 2px solid #999999;';
	$msg.='}';
	$msg.='';
	$msg.='.trackerHeading {';
	$msg.='background-color:#efefef;';
	$msg.='}';
	$msg.='';
	$msg.='.trackerListBullet {';
	$msg.='border-right:1px dotted #cccccc;';
	$msg.='background-color:#f9f9f9;';
	$msg.='}';
	$msg.='';
	$msg.='.trackerList {';
	$msg.='border-bottom:1px solid #eeeeee;';
	$msg.='}';
	$msg.='';
	$msg.='.crmTable {';
	$msg.='border:1px solid #dadada;';
	$msg.='}';
	$msg.='';
	$msg.='.crmTableRow {';
	$msg.='border-bottom:1px dotted #dadada;';
	$msg.='border-right:1px dotted #dadada';
	$msg.='}';
	$msg.='';
	$msg.='.lineOnTop {';
	$msg.='border-top:1px solid #999999;';
	$msg.='}';
	$msg.='';
	$msg.='.discountUI{';
	$msg.='border:3px solid #CCCCCC;';
	$msg.='width:250px;';
	$msg.='padding:5px;';
	$msg.='position:absolute;';
	$msg.='background-color:#FFFFFF;';
	$msg.='display:none;';
	$msg.='}';
	$msg.='';
	$msg.='.TaxShow{';
	$msg.='display:inline-table;';
	$msg.='}';
	$msg.='';
	$msg.='.TaxHide{';
	$msg.='display:none;';
	$msg.='}';
	$msg.='';
	$msg.='.emailSelected{';
	$msg.='background-color:#eaeaea;';
	$msg.='color:#000000;';
	$msg.='font-size:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.mailClient{';
	$msg.='border-top:0px solid #ddd;';
	$msg.='border-right:0px solid #ddd;';
	$msg.='border-left:0px solid #ddd;';
	$msg.='border-bottom:0px solid #ddd;';
	$msg.='}';
	$msg.='';
	$msg.='.mailClientBg {';
	$msg.='';
	$msg.='background-color:#ddd;';
	$msg.='background-image:url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif) ;';
	$msg.='border:2px solid #dddddd;';
	$msg.='}';
	$msg.='';
	$msg.='.mailSubHeader {';
	$msg.='background:#FFFFFF url(https://moaistd.moai-crm.com/crm/themes/softed/images/level2Bg.gif) repeat-x scroll center bottom;';
	$msg.='border-top:0px solid #ddd;';
	$msg.='padding:4px;';
	$msg.='}';
	$msg.='';
	$msg.='.mailClientWriteEmailHeader {';
	$msg.='font-family:arial, helvetica, sans-serif;';
	$msg.='font-size:20px;';
	$msg.='line-height:24px;';
	$msg.='font-weight:bold;';
	$msg.='background:#ddd url(https://moaistd.moai-crm.com/crm/themes/softed/images/mailSubHeaderBg.gif) repeat-x;';
	$msg.='padding:10px;';
	$msg.='border-left:1px solid #eaeaea;';
	$msg.='border-top:1px solid #eaeaea;';
	$msg.='border-right:1px solid #939271;';
	$msg.='border-left:1px solid #939271;;';
	$msg.='}';
	$msg.='';
	$msg.='.mailClientCSSButton {';
	$msg.='border-left:1px solid #fff;';
	$msg.='border-top:1px solid #fff;';
	$msg.='border-right:1px solid #333;';
	$msg.='border-bottom:1px solid #333;';
	$msg.='padding:2px;';
	$msg.='background-color:#c3c2b1;';
	$msg.='}';
	$msg.='';
	$msg.='.layerPopup {';
	$msg.='border:2px solid #ddd;';
	$msg.='background:#fffff5 url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif) ;';
	$msg.='position:absolute;';
	$msg.='}';
	$msg.='';
	$msg.='.layerPopupHeading {';
	$msg.='font-family:arial, helvetica, sans-serif;';
	$msg.='font-size:16px;';
	$msg.='line-height:24px;';
	$msg.='font-weight:bold;';
	$msg.='}';
	$msg.='';
	$msg.='.layerHeadingULine {';
	$msg.='border-bottom:2px solid #717351;';
	$msg.='}';
	$msg.='';
	$msg.='.layerPopupTransport {';
	$msg.='background-color:#e2e5ff;';
	$msg.='}';
	$msg.='';
	$msg.='.homePageSeperator {';
	$msg.='border-right:0px dotted #d3d2c1;';
	$msg.='background:#ffffff url(https://moaistd.moai-crm.com/crm/themes/softed/images/homePageSeperator.gif) no-repeat;';
	$msg.='padding-left:15px; padding-top:15px;';
	$msg.='}';
	$msg.='';
	$msg.='.homePageMatrixHdr {';
	$msg.='border-bottom:0px solid #d3d2c1;';
	$msg.='background:#ffffff  ;';
	$msg.='}';
	$msg.='';
	$msg.='.reportsListTable {';
	$msg.='background-color:white;';
	$msg.='border-left:1px solid #ddd;';
	$msg.='border-right:1px solid #ddd;';
	$msg.='border-bottom:1px solid #ddd;';
	$msg.='}';
	$msg.='';
	$msg.='.reportGenerateTable{';
	$msg.='background-image:url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);';
	$msg.='border-left:2px dotted #a5b5ee;';
	$msg.='border-right:2px dotted #a5b5ee;';
	$msg.='border-bottom:2px dotted #a5b5ee;';
	$msg.='}';
	$msg.='';
	$msg.='.reportCreateBottom{';
	$msg.='background-color:#ddf;';
	$msg.='border-bottom:2px solid #737251;';
	$msg.='}';
	$msg.='';
	$msg.='.importLeadUI{';
	$msg.='background-color:white;';
	$msg.='}';
	$msg.='';
	$msg.='a.customMnu{';
	$msg.='padding-left:30px;';
	$msg.='padding-top:5px;';
	$msg.='padding-bottom:5px;';
	$msg.='display:block;';
	$msg.='background-repeat:no-repeat;';
	$msg.='background-position:left;';
	$msg.='width:155px;';
	$msg.='color:#000000;';
	$msg.='text-decoration:none;';
	$msg.='}';
	$msg.='';
	$msg.='a.customMnuSelected{';
	$msg.='padding-left:30px;';
	$msg.='padding-top:5px;';
	$msg.='padding-bottom:5px;';
	$msg.='display:block;';
	$msg.='background-repeat:no-repeat;';
	$msg.='background-position:left;';
	$msg.='width:155px;';
	$msg.='background-color:#0099FF;';
	$msg.='color:#FFFFFF;';
	$msg.='text-decoration:none;';
	$msg.='}';
	$msg.='';
	$msg.='.drop_mnu{';
	$msg.='position:absolute;';
	$msg.='left:0px;';
	$msg.='top:0px;';
	$msg.='z-index:1000000001;';
	$msg.='border-left:1px solid #d3d3d3;';
	$msg.='border-right:1px solid #d3d3d3;';
	$msg.='border-bottom:1px solid #d3d3d3;';
	$msg.='width:150px;';
	$msg.='display:none;';
	$msg.='padding:0px;';
	$msg.='text-align:left;';
	$msg.='overflow-x:hidden;';
	$msg.='overflow-y:hidden;';
	$msg.='background-color:#ffffcc;';
	$msg.='}';
	$msg.='';
	$msg.='a.drop_down{';
	$msg.='width:150px;';
	$msg.='text-align:left;';
	$msg.='font-family:  Arial, Helvetica, sans-serif;';
	$msg.='font-size: 11px;';
	$msg.='line-height:20px;';
	$msg.='font-weight:normal;';
	$msg.='color:#33338c;';
	$msg.='background-color:#fff;';
	$msg.='padding:2px 5px 2px 5px;';
	$msg.='border-top:1px solid #fafafa;';
	$msg.='border-bottom:1px solid #d3d3d3;';
	$msg.='display:block;';
	$msg.='}';
	$msg.='';
	$msg.='a.drop_down:Hover{';
	$msg.='padding:2px 5px 2px 5px;';
	$msg.='width:150px;';
	$msg.='text-align:left;';
	$msg.='color:#0070BA;';
	$msg.='font-weight:normal;';
	$msg.='text-decoration:underline;';
	$msg.='background-color:#ffffcc;';
	$msg.='}';
	$msg.='';
	$msg.='.bgwhite{';
	$msg.='background-color:white;';
	$msg.='}';
	$msg.='';
	$msg.='.searchUIBasic {';
	$msg.='background-image:url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);';
	$msg.='border:2px solid #a5b5ee;';
	$msg.='}';
	$msg.='';
	$msg.='.searchUIAdv1{ ';
	$msg.='background-image:url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);';
	$msg.='border-top:2px solid #a5b5ee;';
	$msg.='border-left:2px solid #a5b5ee;';
	$msg.='border-right:2px solid #a5b5ee;';
	$msg.='}';
	$msg.='';
	$msg.='.searchUIAdv2{ ';
	$msg.='background-image:url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);';
	$msg.='border-left:2px solid #a5b5ee;';
	$msg.='border-right:2px solid #a5b5ee;';
	$msg.='}';
	$msg.='';
	$msg.='.searchUIAdv3{ ';
	$msg.='background-image:url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif);';
	$msg.='border-bottom:2px solid #a5b5ee;';
	$msg.='border-left:2px solid #a5b5ee;';
	$msg.='border-right:2px solid #a5b5ee;';
	$msg.='}';
	$msg.='';
	$msg.='.searchUIName {';
	$msg.='}';
	$msg.='';
	$msg.='.veil{';
	$msg.='background: url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif) ;';
	$msg.='height: 100%;';
	$msg.='width: 100%;';
	$msg.='top: 0px;';
	$msg.='left: 0px;';
	$msg.='overflow: hidden;';
	$msg.='z-index: 10000;';
	$msg.='Filter: Alpha(opacity = 70);';
	$msg.='-moz-opacity: 0.7;';
	$msg.='text-align: center;';
	$msg.='vertical-align: middle;';
	$msg.='position: absolute;';
	$msg.='}';
	$msg.='';
	$msg.='.veil_new{';
	$msg.='background: url(https://moaistd.moai-crm.com/crm/themes/softed/images/layerPopupBg.gif) ;';
	$msg.='height: 100%;';
	$msg.='width: 100%;';
	$msg.='top: 0px;';
	$msg.='left: 0px;';
	$msg.='overflow: hidden;';
	$msg.='z-index: 50000;';
	$msg.='Filter: Alpha(opacity = 70);';
	$msg.='-moz-opacity: 0.7;';
	$msg.='text-align: center;';
	$msg.='vertical-align: middle;';
	$msg.='position: absolute;';
	$msg.='}';
	$msg.='';
	$msg.='.optioncontainer{';
	$msg.='vertical-align: middle;   ';
	$msg.='height: 100%;';
	$msg.='width: 100%;   ';
	$msg.='position: absolute;';
	$msg.='z-index: 90000;   ';
	$msg.='}';
	$msg.='';
	$msg.='.options{';
	$msg.='vertical-align: middle;   ';
	$msg.='margin-left: 25%;';
	$msg.='margin-top: 16%;';
	$msg.='color: #FFFFFF;';
	$msg.='width:650px;';
	$msg.='background-color: Black;';
	$msg.='border: 2px solid #222;';
	$msg.='position: relative;';
	$msg.='text-align: left;';
	$msg.='z-index: 80000;   ';
	$msg.='}';
	$msg.='';
	$msg.='.options h2{';
	$msg.='color: White;';
	$msg.='font-family: Verdana, Arial, Helvetica, sans-serif;';
	$msg.='border-bottom: 1px solid #373D4C;';
	$msg.='margin: 0;';
	$msg.='font-weight: normal;';
	$msg.='}';
	$msg.='';
	$msg.='.mailSelected {';
	$msg.='font-family:  Arial, Helvetica, sans-serif;';
	$msg.='font-weight:bold;';
	$msg.='font-size: 11px;';
	$msg.='padding-left:10px;';
	$msg.='padding-right:10px;';
	$msg.='padding-top:2px;';
	$msg.='padding-bottom:2px;';
	$msg.='}';
	$msg.='';
	$msg.='.mailSelected_select {';
	$msg.='background:#E1DCB3 url(https://moaistd.moai-crm.com/crm/themes/softed/images/tabSelectedBg.gif) repeat-x;';
	$msg.='background-color:#1F5EFF;';
	$msg.='font-family:  Arial, Helvetica, sans-serif;';
	$msg.='font-weight:bold;';
	$msg.='font-size: 11px;';
	$msg.='padding-left:10px;';
	$msg.='padding-right:10px;';
	$msg.='padding-top:2px;';
	$msg.='padding-bottom:2px;';
	$msg.='}';
	$msg.='';
	$msg.='.groupname{';
	$msg.='width:125px;';
	$msg.='}';
	$msg.='';
	$msg.='.winmarkModulesdef{';
	$msg.='background-position:bottom left;';
	$msg.='background-repeat:repeat-x;';
	$msg.='}';
	$msg.='';
	$msg.='.headerrow{';
	$msg.='cursor:move;';
	$msg.='}';
	$msg.='.repBox{';
	$msg.='width:100px;';
	$msg.='border:1px solid #666666;';
	$msg.='font-family:Arial, Helvetica, sans-serif;';
	$msg.='font-size:11px;';
	$msg.='}';
	$msg.='.warning {';
	$msg.='color: #0070BA;';
	$msg.='}';
	$msg.='';
	$msg.='.button_add {';
	$msg.='background:#ffffff url(https://moaistd.moai-crm.com/crm/themes/softed/images/select.gif) no-repeat;';
	$msg.='border: 1px solid #D5D555;';
	$msg.='background-position:center;';
	$msg.='}';
	$msg.='</style>';
	return $msg;
}
function pheader($pic){
	return ('<img src="https://moaistd.moai-crm.com/crm/themes/softed/images/'.$pic.'" width="800" height="75" />');
}
function pfooter(){
	return ('<img src="https://moaistd.moai-crm.com/crm/themes/softed/images/footer.jpg" width="800" height="51" />');	
}
function GetDetail($module,$crmid,$fieldid){

	global $genarate,$site_URL;
	$msg="";
	$sql="select tabid from aicrm_tab where name='".$module."'";
	$data_tab = $genarate->process($sql,"all");
	$tabid=$data_tab[0][0];
	$sql="select blockid,blocklabel from aicrm_blocks where tabid='".$tabid."' and detail_view=0 and visible = 0 order by sequence";
	$data_block = $genarate->process($sql,"all");

	for($i=0; $i<count($data_block); $i++)
	{
		$blockid = $data_block[$i][0];
		$block_name = $data_block[$i][1];

		if($block_name=="LBL_TICKET_INFORMATION"){
			$block_name="Case Information";	
		}else if($block_name=="LBL_ACCOUNT_INFORMATION"){
			$block_name="Account Information";	
		}else if($block_name=="LBL_CUSTOM_INFORMATION"){
			$block_name="Custom Information";	
		}else if($block_name=="LBL_TICKET_RESOLUTION"){
			$block_name="Solution Information";	
		}else if($block_name=="LBL_COMMENTS"){
			$block_name="COMMENTS";	
		}else if($block_name=="LBL_KNOWLEDGE_BASE_INFORMATION"){
			$block_name="Knowledge Detail";	
		}else if($block_name=="LBL_LEAD_INFORMATION"){
			$block_name="Lead Information";
		}else if($block_name=="LBL_DESCRIPTION_INFORMATION"){
			$block_name="Description Information";
		}else if($block_name=="LBL_QUOTE_INFORMATION"){
			$block_name="Quotation Information";
		}else if($block_name=="LBL_ADDRESS_INFORMATION"){
			$block_name="Address Information";
		}else if($block_name=="LBL_CONTACT_INFORMATION"){
            $block_name="CONTACT Information";
        }else if($block_name=="LBL_SERVICEREQUEST_INFORMATION"){
        	$block_name = "Service Request Information";
        }else if($block_name=="LBL_JOB_INFORMATION"){
        	$block_name = "Job Information";
        }
		
		$sql="
			SELECT fieldlabel,tablename,columnname,uitype FROM aicrm_field WHERE aicrm_field.tabid=".$tabid." AND aicrm_field.block='".$blockid."'
			AND aicrm_field.displaytype IN (1,2,4) and aicrm_field.presence in (0,2) ORDER BY block,sequence";
			$data_field = $genarate->process($sql,"all");

		if(count($data_field)>0){
			$msg.='<table width="800" border="0" cellspacing="0" cellpadding="0" class="small">';
			$msg.='<tr>';
			$msg.='<td colspan="2" align="left" class="dvInnerHeader" ><strong>'.$block_name.'</strong></td>';
			$msg.='</tr>';
				for($k=0;$k<count($data_field);$k++){
					$fieldlabel=$data_field[$k][0];
					$tablename=$data_field[$k][1];
					$columnname=$data_field[$k][2];
					$uitype=$data_field[$k][3];
					$msg.='<tr>';
					$msg.='  <td width="30%" class="dvtCellLabel">'.$fieldlabel.'</td>';
					$msg.='  <td width="70%" class="dvtCellInfo" height="20">';
					if($tablename=="aicrm_crmentity"){
						$field="crmid";
					}else{
						$field=$fieldid;
					}
					
					if($tablename=="aicrm_ticketcomments"){
						$sql="
						select aicrm_users.user_name,aicrm_ticketcomments.comments,aicrm_ticketcomments.createdtime
						from aicrm_ticketcomments
						left join aicrm_users on aicrm_users.id=aicrm_ticketcomments.ownerid
						where aicrm_ticketcomments.".$field."=".$crmid;

						$data = $genarate->process($sql,"all");
						for($l=0;$l<count($data);$l++){
							$msg.=$data[$l][1]."<br>";
							$msg.='<font color="#990000">Author : '.$data[$l][0]." on ".$data[$l][2].'<br></font>';
						}
					}else{
                        if($columnname=="smownerid"){
                            $sql="
							select 
							if(isnull(concat(aicrm_users.first_name,' ',aicrm_users.last_name)),aicrm_groups.groupname,concat(aicrm_users.first_name,' ',aicrm_users.last_name)) 
							from ".$tablename."
							LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
							LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
							where ".$tablename.".".$field."=".$crmid;
                        }else if($columnname=="smcreatorid"){
							$sql="
							select 
							if(isnull(concat(aicrm_users.first_name,' ',aicrm_users.last_name)),aicrm_groups.groupname,concat(aicrm_users.first_name,' ',aicrm_users.last_name)) 
							from ".$tablename."
							LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smcreatorid
							LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smcreatorid
							where ".$tablename.".".$field."=".$crmid;

						}else if($columnname=="modifiedby"){
							$sql="
							select 
							if(isnull(concat(aicrm_users.first_name,' ',aicrm_users.last_name)),aicrm_groups.groupname,concat(aicrm_users.first_name,' ',aicrm_users.last_name)) 
							from ".$tablename."
							LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.modifiedby
							LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.modifiedby
							where ".$tablename.".".$field."=".$crmid;

                        }else if($columnname == 'accountid'){
                            $sql="select aicrm_account.accountname from ".$tablename." inner join aicrm_account on aicrm_account.accountid = ".$tablename.".".$columnname." where ".$tablename.".".$field."=".$crmid;
                        }else if($columnname == 'parentid' && $tablename=='aicrm_account'){
                            $sql="select s.accountname from aicrm_account as a 
                                    left join aicrm_account as s on s.accountid = a.parentid
                                    where a.accountid=".$crmid;
                        }else if($columnname == 'contactid'){
                            $sql="select CONCAT(aicrm_contactdetails.firstname, ' ', aicrm_contactdetails.lastname) as contactname from ".$tablename." inner join aicrm_contactdetails on aicrm_contactdetails.contactid = ".$tablename.".".$columnname." where ".$tablename.".".$field."=".$crmid;
                    	}else if($columnname == 'salesorderid'){
                            $sql="select aicrm_salesorder.salesorder_name from ".$tablename." inner join aicrm_salesorder on aicrm_salesorder.salesorderid = ".$tablename.".".$columnname." where ".$tablename.".".$field."=".$crmid;

                        }else if($columnname == 'dealid'){
                            $sql="select aicrm_deal.deal_name from ".$tablename." inner join aicrm_deal on aicrm_deal.dealid = ".$tablename.".".$columnname." where ".$tablename.".".$field."=".$crmid;

                        }else if($columnname == 'campaignid'){
                            $sql="select aicrm_campaign.campaignname from ".$tablename." inner join aicrm_campaign on aicrm_campaign.campaignid = ".$tablename.".".$columnname." where ".$tablename.".".$field."=".$crmid;

                        }else if($columnname == 'promotionid'){
                            $sql="select aicrm_promotion.promotion_name from ".$tablename." inner join aicrm_promotion on aicrm_promotion.promotionid = ".$tablename.".".$columnname." where ".$tablename.".".$field."=".$crmid;
                       
                        }else if($columnname=='product_id'){
                            $sql="select aicrm_products.productname from ".$tablename." inner join aicrm_products on aicrm_products.productid = ".$tablename.".".$columnname." where ".$tablename.".".$field."=".$crmid;
                        
                        }else if($tablename=='aicrm_leadaddress' &&( $columnname=='lead_houseno' || $columnname=='lead_villageno' || $columnname=='lead_village' || $columnname=='lead_lane' || $columnname=='lead_street' || $columnname=='lead_subdistrinct' || $columnname=='lead_district' || $columnname=='lead_province'|| $columnname=='lead_postalcode' )) {
                            $sql = "select ".$tablename.".".$columnname." from ".$tablename." where ".$tablename.".leadaddressid=".$crmid;
						}else if($columnname=='event_id'){
                            $sql="select aicrm_projects.projects_no from ".$tablename." INNER JOIN aicrm_projects ON aicrm_projects.projectsid = ".$tablename.".".$columnname." where ".$tablename.".".$field."=".$crmid;
                        }else{
                            $sql="select ".$tablename.".".$columnname." from ".$tablename." where ".$tablename.".".$field."=".$crmid;
                        }

						$data = $genarate->process($sql,"all");

                        for($l=0;$l<count($data);$l++){
							if($uitype=="5"){
								if($data[$l][0]=="0000-00-00" || $data[$l][0]==""){
									$msg.="";
								}else{
									$msg.=date('d-m-Y',strtotime($data[$l][0]))."<br>";
								}
							}else if($uitype=="70"){
								if($data[$l][0]=="0000-00-00" || $data[$l][0]==""){
									$msg.="";
								}else{
									$msg.=date('d-m-Y',strtotime($data[$l][0]))."<br>";
								}
							}else if($uitype=="7"){
								$msg.=number_format($data[$l][0],2)."<br>";
							}else if($uitype=="4"){
								$msg.="<a href='".$site_URL."index.php?action=DetailView&module=".$module;
								$msg.="&parenttab=Support&record=".$crmid."&viewname=0&start='>".$data[$l][0]."</a>"."<br>";
							}else if($uitype=="15"){
								if($data[$l][0]=="--None--" || $data[$l][0]=="--โปรดเลือก--"){
									$msg.="&nbsp;";
								}else{
									$msg.=$data[$l][0]."<br>";
								}
							}else if($uitype=="56"){
								if($data[$l][0]=="1"){
									$msg.="Yes";
								}else{
									$msg.="No";
								}
							}else{
								$msg.=$data[$l][0]."<br>";
							}

							if($module == 'Quotes' && $columnname == 'quotation_status' ){
                                $quotation_status = $data[$l][0];
                            }
                            if($module == 'Quotes' && $columnname == 'logo' ){
                                $logoflg = $data[$l][0];
                            }
                            if($module == 'Quotes' && $columnname == 'quota_vat_show' ){
                                $vatflg = $data[$l][0];
                            }
                            if($module == 'Quotes' && $columnname == 'quota_form' ){
                                $report = $data[$l][0];
                            }
						}
					}
					$msg.='</tr>';
				}
			$msg.='</table>';	
		}//if
	}


    if($module == 'Quotes'){

        global $dbconfig;
        require_once("/../../library/myFunction.php");
        require_once("/../../library/myLibrary_mysqli.php");
        $myLibrary_mysqli = new myLibrary_mysqli();
        $myLibrary_mysqli->_dbconfig = $dbconfig;

    
  
  		$msg.='<table width="1000" border="0" cellspacing="0" cellpadding="0" class="small">
                    <tr>
                        <td class="dvInnerHeader" align="left" colspan="15"><strong>Item Detail</strong></td>
                    </tr>
                    <tr>
                        <td class="dvtCellLabel" align="center"><strong>รายการสินค้า</strong></td>
                        <td class="dvtCellLabel" align="center"><strong>ชนิดผิว</strong></td>
                        <td class="dvtCellLabel" align="center"><strong>ขนาด (มม.)</strong></td>
                        <td class="dvtCellLabel" align="center"><strong>ความหนา (มม.)</strong></td>
                        <td class="dvtCellLabel" align="center"><strong>แบรนด์สินค้ากรีนแลม</strong></td>
                        <td class="dvtCellLabel" align="center"><strong>ราคากรีนแลม (Exc.VAT)</strong></td>
                        <td class="dvtCellLabel" align="center"><strong>แบรนด์คู่แข่ง</strong></td>
                        <td class="dvtCellLabel" align="center"><strong>ราคาคู่แข่ง (Exc.VAT)</strong></td>
                        <td class="dvtCellLabel" align="center"><strong>แบรนด์คู่แข่งในโครงการ</strong></td>
                        <td class="dvtCellLabel" align="center"><strong>ราคาคู่แข่งในโครงการ (Exc.VAT)</strong></td>
                        <td class="dvtCellLabel" align="center"><strong>จำนวน (หน่วย)</strong></td>
						<td class="dvtCellLabel" align="center"><strong>หน่วยนับ</strong></td>
						<td class="dvtCellLabel" align="center"><strong>รวมต้นทุนจริงเฉลี่ย</strong></td>
						<td class="dvtCellLabel" align="center"><strong>ราคาขาย</strong></td>
						<td class="dvtCellLabel" align="center"><strong>รวม (บาท)</strong></td>
                    </tr>';

        $sql = "SELECT
		aicrm_inventoryproductrel.id,
		aicrm_inventoryproductrel.product_name,
		aicrm_inventoryproductrel.product_finish, -- ชนิดผิว
		aicrm_inventoryproductrel.product_size_mm, -- ขนาด
		aicrm_inventoryproductrel.product_thinkness, -- ความหนา

		aicrm_inventoryproductrel.product_own_brand,
		aicrm_inventoryproductrel.product_own_brand_price,
		aicrm_inventoryproductrel.competitor_brand,
		aicrm_inventoryproductrel.competitor_price,
		aicrm_inventoryproductrel.compet_brand_in_proj,
		aicrm_inventoryproductrel.compet_brand_in_proj_price,
		aicrm_inventoryproductrel.product_unit,
		aicrm_inventoryproductrel.product_cost_avg,

		aicrm_inventoryproductrel.quantity,
		aicrm_inventoryproductrel.selling_price,
		aicrm_inventoryproductrel.tax1,
		aicrm_quotes.discount,
		aicrm_quotes.total_without_vat,
		
		case 
			when aicrm_inventoryproductrel.quantity>0 then 
				case
					when aicrm_inventoryproductrel.selling_price > 0 then REPLACE(FORMAT(aicrm_inventoryproductrel.selling_price*aicrm_inventoryproductrel.quantity, 2 ), ',', '' )
			else ''
		  end
	  else ''
	  end AS total_selling_price,
		CAST(aicrm_quotes.subtotal as CHAR) AS nettotal,
		
	(
		SELECT 
case 
  when aicrm_quotes.pricetype='Include Vat' THEN

	case 
		when aicrm_inventoryproductrel.quantity>0 then 
		case
			when aicrm_inventoryproductrel.selling_price>0 then 
				format(SUM(aicrm_inventoryproductrel.quantity*aicrm_inventoryproductrel.selling_price)-aicrm_quotes.total_without_vat,2)
			else ''
			end
		else ''
	end
	
	ELSE
	
	case 
		when aicrm_inventoryproductrel.quantity>0 then 
		case
			when aicrm_quotes.total_without_vat>0 then 
			 format(aicrm_quotes.total_without_vat*(aicrm_inventoryproductrel.tax1/100),2)
			else ''
			end
		else ''
	end

END  as vat
	
	
FROM 
aicrm_quotes 

LEFT JOIN aicrm_inventoryproductrel ON aicrm_inventoryproductrel.id = aicrm_quotes.quoteid
WHERE aicrm_quotes.quoteid = ".$crmid." 
	) vat,
	
	
	(
		SELECT 
case 
  when aicrm_quotes.pricetype='Include Vat' THEN

	case 
		when aicrm_inventoryproductrel.quantity>0 then 
		case
			when aicrm_inventoryproductrel.selling_price>0 then 
				format(SUM(aicrm_inventoryproductrel.quantity*aicrm_inventoryproductrel.selling_price),2)
			else ''
			end
		else ''
	end
	
	ELSE
	
	case 
		when aicrm_inventoryproductrel.quantity>0 then 
		case
			when aicrm_quotes.total_without_vat>0 then 
			 format(aicrm_quotes.total_without_vat+(aicrm_quotes.total_without_vat*(aicrm_inventoryproductrel.tax1/100)),2)
			else ''
			end
		else ''
	end

END  as vat
	
	
FROM 
aicrm_quotes 

LEFT JOIN aicrm_inventoryproductrel ON aicrm_inventoryproductrel.id = aicrm_quotes.quoteid
WHERE aicrm_quotes.quoteid = ".$crmid."  
	) sum_vat,
	
	 case 
		when aicrm_quotes.currency_id = 2 then 'USD'
		else 'THB'
	end as currency,
	aicrm_quotes.text_currency_th,
	aicrm_quotes.text_currency_en
		
	
		
	
	FROM
		aicrm_quotes
		INNER JOIN aicrm_crmentity ON aicrm_quotes.quoteid = aicrm_crmentity.crmid 
		AND aicrm_crmentity.deleted = 0
		LEFT JOIN aicrm_inventoryproductrel ON aicrm_inventoryproductrel.id = aicrm_quotes.quoteid
	
	WHERE
		aicrm_quotes.quoteid = ".$crmid."  
	ORDER BY
		aicrm_inventoryproductrel.sequence_no;";

        $data = $myLibrary_mysqli->select($sql);

		$quantity = 0;
        for($i=0; $i<count($data); $i++){
			$quantity = $quantity+$data[$i]['quantity'];
            $msg.='<tr>
                        <td class="dvtCellInfo" align="left"><strong>'. $data[$i]['product_name'].'</strong></td>
                        <td class="dvtCellInfo" align="center">'. $data[$i]['product_finish'] .'</td>
                        <td class="dvtCellInfo" align="center">'. $data[$i]['product_size_mm'] .'</td>
                        <td class="dvtCellInfo" align="center">'. $data[$i]['product_thinkness'] .'</td>
                        <td class="dvtCellInfo" align="center">'. $data[$i]['product_own_brand'] .'</td>
                        <td class="dvtCellInfo" align="right">'. number_format($data[$i]['product_own_brand_price'], 2) .'</td>
						<td class="dvtCellInfo" align="center">'. $data[$i]['competitor_brand'] .'</td>
                        <td class="dvtCellInfo" align="right">'. number_format($data[$i]['competitor_price'], 2) .'</td>
                        <td class="dvtCellInfo" align="center">'. $data[$i]['compet_brand_in_proj'] .'</td>
                        <td class="dvtCellInfo" align="right">'. number_format($data[$i]['compet_brand_in_proj_price'], 2) .'</td>
						<td class="dvtCellInfo" align="right">'. number_format($data[$i]['quantity'],2) .'</td>
						<td class="dvtCellInfo" align="center">'. $data[$i]['product_unit'] .'</td>
						<td class="dvtCellInfo" align="right">'. number_format($data[$i]['product_cost_avg'], 2) .'</td>
						<td class="dvtCellInfo" align="right">'. number_format($data[$i]['selling_price'], 2) .'</td>
						<td class="dvtCellInfo" align="right">'. number_format($data[$i]['quantity']*$data[$i]['selling_price'], 2) .'</td>
                    </tr>';
        }

        for($i=0; $i<1; $i++) {

			$msg .= '<tr>
						<td class="dvtCellInfo" colspan="14" align="right"><strong>Total Qty.</strong></td>
						<td class="dvtCellInfo" align="right"><strong>'. number_format($quantity,2) .'</strong></td>
					</tr>';

            $msg .= '<tr>
                        <td class="dvtCellInfo" colspan="14" align="right"><strong>Total Price</strong></td>
                        <td class="dvtCellInfo" align="right"><strong> '. number_format($data[$i]['nettotal'],0) .'</strong></td>
                    </tr>';

            $msg .= '<tr>
                        <td class="dvtCellInfo" colspan="14" align="right"><strong>(-) Discount	</strong></td>
                        <td class="dvtCellInfo" align="right"><strong> 0.00</strong></td>
                    </tr>';

            $msg .= '<tr>
                        <td class="dvtCellInfo" colspan="14" align="right"><strong>Total After Discount</strong></td>
                        <td class="dvtCellInfo" align="right"><strong> '. number_format($data[$i]['nettotal'],0) .'</strong></td>
                    </tr>';
			
			$msg .= '<tr>
                        <td class="dvtCellInfo" colspan="14" align="right"><strong>Total without Vat</strong></td>
                        <td class="dvtCellInfo" align="right"><strong> '. number_format($data[$i]['total_without_vat'],0) .'</strong></td>
                    </tr>';

            $msg .= '<tr>
                        <td class="dvtCellInfo" colspan="14" align="right"><strong>Vat '.number_format($data[$i]['tax1'],0).' %</strong></td>
                        <td class="dvtCellInfo" align="right"><strong> ' . $data[$i]['vat'] . '</strong></td>
                    </tr>';

            $msg .= '<tr>
                        <td class="dvtCellInfo" colspan="14" align="right"><strong>Grand Total</strong></td>
                        <td class="dvtCellInfo" align="right"><strong> ' . $data[$i]['sum_vat'] . '</strong></td>
                    </tr>';
        	$msg .= '</table>';

        }
    }

    if($report == "ใบเสนอราคาแบบไม่มี VAT (TH)"){
    	$name_report = 'rpt_quotation_th';
	}
	elseif($report == "ใบเสนอราคาแบบไม่มี VAT (EN)"){
        $name_report = 'rpt_quotation_en';
    }
    elseif($report == "ใบเสนอราคาแบบมี VAT (TH)"){
        $name_report = 'rpt_quotation_th';
    }
    elseif($report == "ใบเสนอราคาแบบมี VAT (EN)"){
        $name_report = 'rpt_quotation_en';
    }

    if($quotation_status!='ปิดการขาย' && $quotation_status!='อนุมัติใบเสนอราคา'){
        $watermark = 1;
    }else{
        $watermark = 0;
    }
 	
	return $msg;
}

?>