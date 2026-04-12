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
?>
<html>
<body>
<script>
if (document.layers)
{
	document.write("This feature requires IE 5.5 or higher for Windows on Microsoft Windows 2000, Windows NT4 SP6, Windows XP.");
	document.write("<br><br>Click <a href='#' onclick='window.history.back();'>here</a> to return to the previous page");
}	
else if (document.layers || (!document.all && document.getElementById))
{
	document.write("This feature requires IE 5.5 or higher for Windows on Microsoft Windows 2000, Windows NT4 SP6, Windows XP.");
	document.write("<br><br>Click <a href='#' onclick='window.history.back();'>here</a> to return to the previous page");	
}
else if(document.all)
{
	document.write("<br><br>Click <a href='#' onclick='window.history.back();'>here</a> to return to the previous page");
	document.write("<OBJECT Name='vtigerCRM' codebase='modules/Settings/vtigerCRM.CAB#version=1,5,0,0' id='objMMPage' classid='clsid:0FC436C2-2E62-46EF-A3FB-E68E94705126' width=0 height=0></object>");
}
</script>
<?php
require_once('include/database/PearDatabase.php');
require_once('config.php');

global $default_charset;

// Fix For: http://trac.vtiger.com/cgi-bin/trac.cgi/ticket/2107
$randomfilename = "vt_" . str_replace(array("."," "), "", microtime());

$templateid = $_REQUEST['mergefile'];
if($templateid == "")
{
	die("Select Mail Merge Template");
}
//get the particular file from db and store it in the local hard disk.
//store the path to the location where the file is stored and pass it  as parameter to the method 
$sql = "select filename,data,filesize from aicrm_wordtemplates where templateid=?";

$result = $adb->pquery($sql, array($templateid));
$temparray = $adb->fetch_array($result);

$fileContent = $temparray['data'];
$filename=html_entity_decode($temparray['filename'], ENT_QUOTES, $default_charset);

// Fix For: http://trac.vtiger.com/cgi-bin/trac.cgi/ticket/2107
$filename= $randomfilename . "_word.doc";

$filesize=$temparray['filesize'];
$wordtemplatedownloadpath =$root_directory ."/test/wordtemplatedownload/";


$handle = fopen($wordtemplatedownloadpath .$filename,"wb");
fwrite($handle,base64_decode($fileContent),$filesize);
fclose($handle);

//for mass merge
$mass_merge = $_REQUEST['allselectedboxes'];
$single_record = $_REQUEST['record'];

if($mass_merge != "")
{	
	$mass_merge = explode(";",$mass_merge);
	//array_pop($mass_merge);
	$temp_mass_merge = $mass_merge;
	if(array_pop($temp_mass_merge)=="")
		array_pop($mass_merge);
	//$mass_merge = implode(",",$mass_merge);
}else if($single_record != "")
{
	$mass_merge = $single_record;	
}else
{
	die("Record Id is not found");
}

//<<<<<<<<<<<<<<<<header for csv and select columns for query>>>>>>>>>>>>>>>>>>>>>>>>

global $current_user;
require('user_privileges/user_privileges_'.$current_user->id.'.php');
if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0 || $module == "Users" || $module == "Emails")
{
	$query1="select tablename,columnname,fieldlabel from aicrm_field where tabid=7 and aicrm_field.presence in (0,2) order by tablename";
	$params1 = array();
}
else
{
	$profileList = getCurrentUserProfileList();
	$query1="select aicrm_field.tablename,aicrm_field.columnname,aicrm_field.fieldlabel from aicrm_field INNER JOIN aicrm_profile2field ON aicrm_profile2field.fieldid=aicrm_field.fieldid INNER JOIN aicrm_def_org_field ON aicrm_def_org_field.fieldid=aicrm_field.fieldid where aicrm_field.tabid in (7) AND aicrm_profile2field.visible=0 AND aicrm_def_org_field.visible=0 AND aicrm_profile2field.profileid IN (". generateQuestionMarks($profileList) .") and aicrm_field.presence in (0,2) GROUP BY aicrm_field.fieldid order by aicrm_field.tablename";
	$params1 = array($profileList);
	//Postgres 8 fixes
	if( $adb->dbType == "pgsql")
		$query1 = fixPostgresQuery( $query1, $log, 0);
}

$result = $adb->pquery($query1, $params1);
$y=$adb->num_rows($result);
	
for ($x=0; $x<$y; $x++)
{ 
  $tablename = $adb->query_result($result,$x,"tablename");
  $columnname = $adb->query_result($result,$x,"columnname");
	$querycolumns[$x] = $tablename.".".$columnname;
  if($columnname == "smownerid")
  {
    $querycolumns[$x] = "case when (aicrm_users.user_name not like '') then concat(aicrm_users.last_name,' ',aicrm_users.first_name) else aicrm_groups.groupname end as username,aicrm_users.first_name,aicrm_users.last_name,aicrm_users.user_name,aicrm_users.yahoo_id,aicrm_users.title,aicrm_users.phone_work,aicrm_users.department,aicrm_users.phone_mobile,aicrm_users.phone_other,aicrm_users.phone_fax,aicrm_users.email1,aicrm_users.phone_home,aicrm_users.email2,aicrm_users.address_street,aicrm_users.address_city,aicrm_users.address_state,aicrm_users.address_postalcode,aicrm_users.address_country";
  }
  $field_label[$x] = "LEAD_".strtoupper(str_replace(" ","",$adb->query_result($result,$x,"fieldlabel")));
	if($columnname == "smownerid")
  		{
  			$field_label[$x] = $field_label[$x].",USER_FIRSTNAME,USER_LASTNAME,USER_USERNAME,USER_YAHOOID,USER_TITLE,USER_OFFICEPHONE,USER_DEPARTMENT,USER_MOBILE,USER_OTHERPHONE,USER_FAX,USER_EMAIL,USER_HOMEPHONE,USER_OTHEREMAIL,USER_PRIMARYADDRESS,USER_CITY,USER_STATE,USER_POSTALCODE,USER_COUNTRY";
  		}
}
$csvheader = implode(",",$field_label);
//<<<<<<<<<<<<<<<<End>>>>>>>>>>>>>>>>>>>>>>>>
	
if(count($querycolumns) > 0)
{
	$selectcolumns = implode($querycolumns,",");

$query = "select ".$selectcolumns." from aicrm_leadmanage 
  inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leadmanage.leadid 
  inner join aicrm_leadmanagesubdetail on aicrm_leadmanagesubdetail.leadsubscriptionid=aicrm_leadmanage.leadid 
  inner join aicrm_leadmanageaddress on aicrm_leadmanageaddress.leadaddressid=aicrm_leadmanagesubdetail.leadsubscriptionid 
  inner join aicrm_leadmanagecf on aicrm_leadmanage.leadid = aicrm_leadmanagecf.leadid 
  LEFT JOIN aicrm_groups
  	ON aicrm_groups.groupid = aicrm_crmentity.smownerid
  left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid
  where aicrm_crmentity.deleted=0 and aicrm_leadmanage.leadid in (". generateQuestionMarks($mass_merge) .")";
		
$result = $adb->pquery($query, array($mass_merge));
$avail_pick_arr = getAccessPickListValues('Leads');	
while($columnValues = $adb->fetch_array($result))
{
  $y=$adb->num_fields($result);
  for($x=0; $x<$y; $x++)
  {
	  $value = $columnValues[$x];
	 foreach($columnValues as $key=>$val)
	 {
		if($val == $value && $value != '')
		{
		  if(array_key_exists($key,$avail_pick_arr))
		  {
			if(!in_array($val,$avail_pick_arr[$key]))
			{
				$value = "Not Accessible";
			}
		  }
		}
	 }
  	//<<<<<<<<<<<<<<< For Blank Fields >>>>>>>>>>>>>>>>>>>>>>>>>>>>
  	if(trim($value) == "--None--" || trim($value) == "--none--")
  	{
  		$value = "";
  	}
	//<<<<<<<<<<<<<<< End >>>>>>>>>>>>>>>>>>>>>>>>>>>>
		$actual_values[$x] = $value;
		$actual_values[$x] = str_replace('"'," ",$actual_values[$x]);
		//if value contains any line feed or carriage return replace the value with ".value."
		if (preg_match ("/(\r\n)/", $actual_values[$x])) 
		{
			$actual_values[$x] = '"'.$actual_values[$x].'"';
		}
		$actual_values[$x] = decode_html(str_replace(","," ",$actual_values[$x]));
  }
	$mergevalue[] = implode($actual_values,",");  	
}
$csvdata = implode($mergevalue,"###");
}else
{
	die("No aicrm_fields to do Merge");
}	
// Fix for: http://trac.vtiger.com/cgi-bin/trac.cgi/ticket/2107
$datafilename = $randomfilename . "_data.csv";

$handle = fopen($wordtemplatedownloadpath.$datafilename,"wb");
fwrite($handle,$csvheader."\r\n");
fwrite($handle,str_replace("###","\r\n",$csvdata));
fclose($handle);

?>

<script>
if (window.ActiveXObject){
	try 
	{
  		ovtigerVM = eval("new ActiveXObject('vtigerCRM.ActiveX');");
  		if(ovtigerVM)
  		{
        	var filename = "<?php echo $filename?>";
        	if(filename != "")
        	{
        		if(objMMPage.bDLTempDoc("<?php echo $site_URL?>/test/wordtemplatedownload/<?php echo $filename?>","MMTemplate.doc"))
        		{
        			try
        			{ 
        				if(objMMPage.Init())
        				{
        					objMMPage.vLTemplateDoc();
							objMMPage.bBulkHDSrc("<?php echo $site_URL;?>/test/wordtemplatedownload/<?php echo $datafilename ?>");
        					objMMPage.vBulkOpenDoc();
        					objMMPage.UnInit()
        					window.history.back();
        				}		
        			}catch(errorObject)
        			{	
        				document.write("Error while processing mail merge operation");
        			}
        		}else
        		{
        			document.write("Cannot get template document");
        		}
        	}
  		}
		}
	catch(e) {
		document.write("Requires to download ActiveX Control from vtigerCRM. Please, ensure that you have administration privilage");
	}
}
</script>
</body>
</html>
