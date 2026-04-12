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
 * $Header:
 * Description:  Main file for the Home module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $adb;
global $currentModule;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
require_once('include/logging.php');
include_once("config.inc.php");

$log = LoggerManager::getLogger('customizereport');

?>
<link rel="stylesheet" type="text/css" media="all" href="asset/css/metro-blue/easyui.css">
<link rel="stylesheet" type="text/css" media="screen" href="asset/css/custom.css"  >
<script type="text/javascript" src="asset/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="asset/js/jquery-ui-1.10.4.custom.min.js"></script>
<link rel="stylesheet" href="asset/css/smoothness/jquery-ui.css">
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>

<style>
.display-report{
	float:left;
	position:relative;
	width:100%;
	padding:0em;
}
fieldset{
	width:98%;
	border:1px solid #ddd;


	margin:0.5em 0;
}
legend{ padding: 2px 15px;}
div#report-result{
	width:99%;
	border:1px solid #ddd;

	-khtml-border-radius:5px;
	-moz-border-radius:5px;

}

div#dashChart{
	min-height:350px;

}
.search-div{
	float: left;
  position: relative;
  width: 100%;
  padding-right: 2px 10px;
}
.search-label{
	float: left;
 text-align: right;
 padding:2px 5px;
 /*padding-top:2px;*/
 line-height:30px;

}
.search-input{
	float:left;
	position:relative;
	margin-bottom:5px;

}
label { clear:right !important; line-height:25px; padding:0 3px; }
.clear{ clear:both;}
.col1{ width:10%; }
.col2{ width:15%; }
.col3{ width:20%; }
.col4{ width:25%; }
.col5{ width:30%; }
.col6{ width:40%; }
.divcenter{
	text-align:center;
}
.calendar{ max-width:250px;}
.easyui-layout{ border-color:#CCC;}
.panel-header{ background:#EFEFEF;}
.panel-title{ color:#33338C}
.layout-expand{ background:#EEE;}
.tree-title a{color:#33338C}
</style>

<div style="padding:10px 10px ; width:98%; height:100%; ">

  <div id="cc" class="easyui-layout" style="width:100%;height:850px;">

   <div data-options="region:'west',split:true" title="Report List" style="width:250px; height:100%">
    <ul class="easyui-tree">
      <li>
        <span>Sales Visit Report</span>
        <ul>
         <li><a href="#" onclick="GetReportList('weeklyplan_monthlyplan','Weekly Plan + Monthly Plan','extend/index.php/salesvisit/salesvisit/rpt_weeklyplan_monthlyplan');return false;" >1.Weekly Plan + Monthly Plan</a></li>
         <li><a href="#" onclick="GetReportList('weeklyplan','Daily Report','extend/index.php/salesvisit/salesvisit/rpt_weeklyplan'); return false;">2.Daily Report</a></li>

    </ul>


  </li>
</ul>



</div>
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/scriptaculous.js"></script>
<div data-options="region:'center',title:'Report'" style="padding: 5px 10px 0 5px">
  <div class="display-report">
 	<iframe  id="report_view" style="padding:0 0 0 0;min-height: 100%;"  allowfullscreen="" frameborder="0" height="800px" width="100%" height="auto" >

 	</iframe>
  </div>

</div>


</div>
</div>
</div>

<script type="application/javascript">
function LoadURL(URL,divID) {

  document.getElementById(divID).src = URL;
}

function GetReportList(reportid,reportname,url)
{

  var panelcenter = jQuery('#cc').layout('panel', 'center');
  panelcenter.panel({'title':reportname});
  LoadURL(url,"report_view" );
}

</script>

