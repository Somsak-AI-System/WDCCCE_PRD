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

ini_set("memory_limit","32M");
global $php_max_execution_time;
set_time_limit($php_max_execution_time);

//This file is used to modify the database from 4.2Patch2 to 5.0 Alpha release

global $conn;
global $migrationlog;
global $query_count, $success_query_count, $failure_query_count;
global $success_query_array, $failure_query_array;

$migrationlog->debug("\n\nDB Changes from 4.2.x to 5.0 GA -------- Starts \n\n");

//Added to put prefix aicrm_ in some of the columns in tables which are used for CV and Reports and field -- 23-06-06
$migrationlog->debug("Going to rename the table names with prefix aicrm_");
include("modules/Migration/rename_tables.php");
$migrationlog->debug("Renaming the table names with prefix aicrm_ has been finished");


$migrationlog->debug("Database Modifications for 4.2 Patch2 ==> 5.0(Alpha) Dev 3 Starts here.");


//These changes have been made in 4.2.3. The following queries have been included who has run the migration from 4.2 Patch2
$wordtemp = $conn->getColumnNames("aicrm_wordtemplates");
if(is_array($wordtemp) && !in_array("templateid",$wordtemp))
{
	$wordtemplate_query1 = "alter table aicrm_wordtemplates DROP PRIMARY KEY";
	Execute($wordtemplate_query1);

	$wordtemplate_query3 = "alter table aicrm_wordtemplates add column templateid integer(19) unsigned auto_increment primary key FIRST";
	Execute($wordtemplate_query3);
}
//upto this added to modify the wordtemplates table which will be in the case of migrate from 4.2 Path2.



/****************** 5.0(Alpha) dev version 1 Database changes -- Starts*********************/


//Added the aicrm_announcement table creation to avoid the error
$ann_query = "CREATE TABLE aicrm_announcement (
	  `creatorid` int(19) NOT NULL,
	    `announcement` text,
	      `title` varchar(255) default NULL,
	        `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
		  PRIMARY KEY  (`creatorid`),
		    KEY `announcement_UK01` (`creatorid`)
	    ) ENGINE=InnoDB";
Execute($ann_query);

//Added Primay Keys for the left out tables
$alter_array1 = Array(
		"alter table aicrm_activity_reminder ADD PRIMARY KEY (activity_id,recurringid)",
		"alter table aicrm_activitygrouprelation ADD PRIMARY KEY (activityid)",
		"alter table aicrm_cvadvfilter ADD PRIMARY KEY (cvid,columnindex)",
		"alter table aicrm_cvcolumnlist ADD PRIMARY KEY (cvid,columnindex)",
		"alter table aicrm_cvstdfilter ADD PRIMARY KEY (cvid)",
		"alter table aicrm_def_org_field ADD PRIMARY KEY (fieldid)",
		"alter table aicrm_leadgrouprelation ADD PRIMARY KEY (leadid)",
		"alter table aicrm_leadgrouprelation drop key leadgrouprelation_IDX0",
		"alter table aicrm_profile2field ADD PRIMARY KEY (profileid,fieldid)",
		"alter table aicrm_profile2standardpermissions ADD PRIMARY KEY (profileid,tabid,Operation)",
		"alter table aicrm_profile2standardpermissions drop index idx_prof2stad",
		"alter table aicrm_profile2utility ADD PRIMARY KEY (profileid,tabid,activityid)",
		"alter table aicrm_profile2utility drop index idx_prof2utility",
		"alter table aicrm_relcriteria ADD PRIMARY KEY (queryid,columnindex)",
		"alter table aicrm_reportdatefilter ADD PRIMARY KEY (datefilterid)",
		"alter table aicrm_reportdatefilter DROP INDEX reportdatefilter_IDX0",
		"alter table aicrm_reportsortcol ADD PRIMARY KEY (sortcolid,reportid)",
		"alter table aicrm_reportsummary ADD PRIMARY KEY (reportsummaryid,summarytype,columnname)",
		"drop table aicrm_role2action",
		"drop table aicrm_role2tab",
		"alter table aicrm_selectcolumn ADD PRIMARY KEY (queryid,columnindex)",
		"alter table aicrm_ticketgrouprelation ADD PRIMARY KEY (ticketid)",
		"alter table aicrm_ticketstracktime ADD PRIMARY KEY (ticket_id)",
		"alter table aicrm_users2group ADD PRIMARY KEY (groupname,userid)",
		"alter table aicrm_users2group DROP INDEX idx_users2group",
		);
foreach($alter_array1 as $query)
{
	Execute($query);
}

//Tables aicrm_profile2globalpermissions, aicrm_actionmapping creation

$create_sql1 ="CREATE TABLE aicrm_profile2globalpermissions (`profileid` int(19) NOT NULL, `globalactionid` int(19) NOT NULL, `globalactionpermission` int(19) default NULL, PRIMARY KEY  (`profileid`,`globalactionid`),  KEY `idx_profile2globalpermissions` (`profileid`,`globalactionid`)) ENGINE=InnoDB";

Execute($create_sql1);

$create_sql2 = "CREATE TABLE aicrm_actionmapping (`actionid` int(19) NOT NULL,	`actionname` varchar(200) NOT NULL, `securitycheck` int(19) default NULL, PRIMARY KEY (`actionid`,`actionname`)) TYPE=InnoDB";
Execute($create_sql2);

//For all Profiles, insert the following entries into aicrm_profile2global permissions table:
$sql = 'select * from aicrm_profile';
$res = $conn->query($sql);
$noofprofiles = $conn->num_rows($res);

for($i=0;$i<$noofprofiles;$i++)
{
	$profile_id = $conn->query_result($res,$i,'profileid');

	$sql1 = "insert into aicrm_profile2globalpermissions values ($profile_id,1,1)";
	$sql2 = "insert into aicrm_profile2globalpermissions values ($profile_id,2,1)";

	Execute($sql1);
	Execute($sql2);
}


//Removing entries for Dashboard and Home module from aicrm_profile2standardpermissions table
$del_query1 = "delete from aicrm_profile2standardpermissions where tabid in(1,3)";
Execute($del_query1);

//For all Profile do the following insert into aicrm_profile2utility table:
$sql = 'select * from aicrm_profile';
$res = $conn->query($sql);
$noofprofiles = $conn->num_rows($res);

/* Commented by Don. Handled below
for($i=0;$i<$noofprofiles;$i++)
{
	$profile_id = $conn->query_result($res,$i,'profileid');

	$sql1 = "insert into aicrm_profile2utility values ($profile_id,4,7,0)";
	$sql2 = "insert into aicrm_profile2utility values ($profile_id,7,9,0)";

	Execute($sql1);
	Execute($sql2);
}
*/

//Insert Values into action mapping table:
$actionmapping_array = Array(
		"insert into aicrm_actionmapping values(0,'Save',0)",
		"insert into aicrm_actionmapping values(1,'EditView',0)",
		"insert into aicrm_actionmapping values(2,'Delete',0)",
		"insert into aicrm_actionmapping values(3,'index',0)",
		"insert into aicrm_actionmapping values(4,'DetailView',0)",
		"insert into aicrm_actionmapping values(5,'Import',0)",
		"insert into aicrm_actionmapping values(6,'Export',0)",
		"insert into aicrm_actionmapping values(8,'Merge',0)",
		"insert into aicrm_actionmapping values(1,'VendorEditView',1)",
		"insert into aicrm_actionmapping values(4,'VendorDetailView',1)",
		"insert into aicrm_actionmapping values(0,'SaveVendor',1)",
		"insert into aicrm_actionmapping values(2,'DeleteVendor',1)",
		"insert into aicrm_actionmapping values(1,'PriceBookEditView',1)",
		"insert into aicrm_actionmapping values(4,'PriceBookDetailView',1)",
		"insert into aicrm_actionmapping values(0,'SavePriceBook',1)",
		"insert into aicrm_actionmapping values(2,'DeletePriceBook',1)",
		"insert into aicrm_actionmapping values(1,'SalesOrderEditView',1)",
		"insert into aicrm_actionmapping values(4,'SalesOrderDetailView',1)",
		"insert into aicrm_actionmapping values(0,'SaveSalesOrder',1)",
		"insert into aicrm_actionmapping values(2,'DeleteSalesOrder',1)",
		"insert into aicrm_actionmapping values(9,'ConvertLead',0)",
		"insert into aicrm_actionmapping values(1,'DetailViewAjax',1)",
		"insert into aicrm_actionmapping values(1,'QuickCreate',1)",
		"insert into aicrm_actionmapping values(4,'TagCloud',1)"
		);
foreach($actionmapping_array as $query)
{
	Execute($query);
}


//Added two columns in aicrm_field table to construct the quickcreate form dynamically
$alter_array2 = Array(
		"ALTER TABLE aicrm_field ADD column quickcreate int(10) after typeofdata",
		"ALTER TABLE aicrm_field ADD column quickcreatesequence int(19) after quickcreate",
		);
foreach($alter_array2 as $query)
{
	Execute($query);
}

$update_array1 = Array(
		"UPDATE aicrm_field SET quickcreate = 1,quickcreatesequence = 0",

		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 2 and fieldlabel = 'Potential Name'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 2 and fieldlabel = 'Account Name'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 2 and fieldlabel = 'Expected Close Date'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 4 WHERE tabid = 2 and fieldlabel = 'Sales Stage'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 5 WHERE tabid = 2 and fieldlabel = 'Amount'",

		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 4 and fieldlabel = 'First Name'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 4 and fieldlabel = 'Last Name'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 4 and fieldlabel = 'Account Name'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 4 WHERE tabid = 4 and fieldlabel = 'Office Phone'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 5 WHERE tabid = 4 and fieldlabel = 'Email'",

		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 6 and fieldlabel = 'Account Name'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 6 and fieldlabel = 'Phone'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 6 and fieldlabel = 'Website'",

		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 7 and fieldlabel = 'First Name'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 7 and fieldlabel = 'Last Name'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 7 and fieldlabel = 'Company'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 4 WHERE tabid = 7 and fieldlabel = 'Phone'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 5 WHERE tabid = 7 and fieldlabel = 'Email'",

		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 8 and fieldlabel = 'Subject'",

		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 9 and fieldlabel = 'Subject'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 9 and fieldlabel = 'Start Date & Time'",

		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 10 and fieldlabel = 'Subject'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 10 and fieldlabel = 'Date & Time Sent'",

		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 13 and fieldlabel = 'Title'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 13 and fieldlabel = 'Description'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 13 and fieldlabel = 'Priority'",

		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 14 and fieldlabel = 'Product Name'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 14 and fieldlabel = 'Product Code'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 14 and fieldlabel = 'Product Category'",

		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 16 and fieldlabel = 'Subject'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 16 and fieldlabel = 'Start Date & Time'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 16 and fieldlabel = 'Activity Type'",
		"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 4 WHERE tabid = 16 and fieldlabel = 'Duration'",
		);
foreach($update_array1 as $query)
{
	Execute($query);
}

//Added for the "Color By User in Calendar " which has been contributed by Cesar
$alter_query1 = "ALTER TABLE aicrm_users ADD cal_color VARCHAR(25) DEFAULT '#E6FAD8' AFTER user_hash";
Execute($alter_query1);

//code contributed by Fredy for color aicrm_priority
$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query1 = "insert into aicrm_field values (16,".$newfieldid.",'priority','activity',1,15,'taskpriority','Priority',1,0,0,100,17,1,1,'V~O',1,'')";
Execute($insert_query1);

//Added on 23-12-2005 which is missed from Fredy's contribution for Color aicrm_priority
populateFieldForSecurity('16',$newfieldid);
$activity_cols = $conn->getColumnNames("aicrm_activity");
if(is_array($activity_cols) && !in_array("priority",$activity_cols))
{
	$activity_alter_query = "alter table aicrm_activity add column priority varchar(150) default NULL";
	Execute($activity_alter_query);
}
//Code contributed by Raju for better emailing 
/*
$insert_array1 = Array(
		"insert into aicrm_field values (10,".$conn->getUniqueID("aicrm_field").",'crmid','seactivityrel',1,'357','parent_id','Related To',1,0,0,100,1,2,1,'I~O',1,'')",
		"insert into aicrm_field values (10,".$conn->getUniqueID("aicrm_field").",'subject','activity',1,'2','subject','Subject',1,0,0,100,1,3,1,'V~M',0,1)",
		"insert into aicrm_field values (10,".$conn->getUniqueID("aicrm_field").",'filename','emails',1,'61','filename','Attachment',1,0,0,100,1,4,1,'V~O',1,'')",
		"insert into aicrm_field values (10,".$conn->getUniqueID("aicrm_field").",'description','emails',1,'19','description','Description',1,0,0,100,1,5,1,'V~O',1,'')",
		);
*/
//commented the above array as that queries are wrong queries -- changed on 23-12-2005
$insert_array1 = array(
			"update aicrm_field set uitype='357' where tabid=10 and fieldname='parent_id' and tablename='aicrm_seactivityrel'",
			"update aicrm_field set sequence=1 where tabid=10 and fieldname in ('parent_id','subject','filename','description')",
			"update aicrm_field set block=2 where tabid=10 and fieldname='parent_id'",
			"update aicrm_field set block=3 where tabid=10 and fieldname='subject'",
			"update aicrm_field set block=4 where tabid=10 and fieldname='filename'",
			"update aicrm_field set block=5 where tabid=10 and fieldname='description'",
		      );
foreach($insert_array1 as $query)
{
	Execute($query);
}

//code contributed by mike to rearrange the home page
$alter_query2 = "alter table aicrm_users add column homeorder varchar(255) default 'ALVT,PLVT,QLTQ,CVLVT,HLT,OLV,GRT,OLTSO,ILTI' after date_format";
Execute($alter_query2);

//Added one column in aicrm_invoice table to include 'Contact Name' aicrm_field in Invoice module
$alter_query3 = "ALTER TABLE aicrm_invoice ADD column contactid int(19) after customerno";
Execute($alter_query3);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query2 = "insert into aicrm_field values (23,".$newfieldid.",'contactid','invoice',1,'57','contact_id','Contact Name',1,0,0,100,4,1,1,'I~O',1,'')";
Execute($insert_query2);
//Added on 23-12-2005 because we must populate aicrm_field entries in aicrm_profile2field and aicrm_def_org_field if we add a aicrm_field in aicrm_field table
populateFieldForSecurity('23',$newfieldid);

//changes made to fix the bug in Address Information block of Accounts and Contacs module
$update_array2 = Array(
		"UPDATE aicrm_field SET fieldlabel='Billing City', sequence=5 WHERE tabid=6 and fieldname='bill_city'",
		"UPDATE aicrm_field SET fieldlabel='Billing State', sequence=7 WHERE tabid=6 and fieldname='bill_state'",
		"UPDATE aicrm_field SET fieldlabel='Billing Code', sequence=9 WHERE tabid=6 and fieldname='bill_code'",
		"UPDATE aicrm_field SET fieldlabel='Billing Country', sequence=11 WHERE tabid=6 and fieldname='bill_country'",

		"UPDATE aicrm_field SET fieldlabel='Shipping City', sequence=6 WHERE tabid=6 and fieldname='ship_city'",
		"UPDATE aicrm_field SET fieldlabel='Shipping State', sequence=8 WHERE tabid=6 and fieldname='ship_state'",
		"UPDATE aicrm_field SET fieldlabel='Shipping Code', sequence=10 WHERE tabid=6 and fieldname='ship_code'",
		"UPDATE aicrm_field SET fieldlabel='Shipping Country', sequence=12 WHERE tabid=6 and fieldname='ship_country'",

		"UPDATE aicrm_field SET fieldlabel='Mailing City', sequence=5 WHERE tabid=4 and fieldname='mailingcity'",
		"UPDATE aicrm_field SET fieldlabel='Mailing State', sequence=7 WHERE tabid=4 and fieldname='mailingstate'",
		"UPDATE aicrm_field SET fieldlabel='Mailing Zip', sequence=9 WHERE tabid=4 and fieldname='mailingzip'",
		"UPDATE aicrm_field SET fieldlabel='Mailing Country', sequence=11 WHERE tabid=4 and fieldname='mailingcountry'",

		"UPDATE aicrm_field SET fieldlabel='Other City', sequence=6 WHERE tabid=4 and fieldname='othercity'",
		"UPDATE aicrm_field SET fieldlabel='Other State', sequence=8 WHERE tabid=4 and fieldname='otherstate'",
		"UPDATE aicrm_field SET fieldlabel='Other Zip', sequence=10 WHERE tabid=4 and fieldname='otherzip'",
		"UPDATE aicrm_field SET fieldlabel='Other Country', sequence=12 WHERE tabid=4 and fieldname='othercountry'",
		);
foreach($update_array2 as $query)
{
	Execute($query);
}


//Added aicrm_field emailoptout in aicrm_account table
$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query3 = "insert into aicrm_field values (6,".$newfieldid.",'emailoptout','account',1,'56','emailoptout','Email Opt Out',1,0,0,100,17,1,1,'C~O',1,'')";
Execute($insert_query3);

//Added on 23-12-2005 because we must populate aicrm_field entries in aicrm_profile2field and aicrm_def_org_field if we add a aicrm_field in aicrm_field table
populateFieldForSecurity('6',$newfieldid);

//Added on 22-12-2005
$alter_query4 = "alter table aicrm_account add column emailoptout varchar(3) default 0";
Execute($alter_query4);

$update_array3 = Array(
		"update aicrm_field set sequence=18 where tabid=6 and fieldname ='assigned_user_id'",
		"update aicrm_field set sequence=19 where tabid=6 and fieldname ='createdtime'",
		"update aicrm_field set sequence=19 where tabid=6 and fieldname ='modifiedtime'",
		);
foreach($update_array3 as $query)
{
	Execute($query);
}


//create table aicrm_moduleowners to assign the module and corresponding owners
$create_query2 = "CREATE TABLE aicrm_moduleowners (
	  `tabid` int(19) NOT NULL default '0',
	    `user_id` varchar(11) NOT NULL,
	      PRIMARY KEY  (`tabid`),
	        KEY `moduleowners_tabid_user_id_idx` (`tabid`,`user_id`)
	) ENGINE=InnoDB";

/*
$create_query2 = "CREATE TABLE aicrm_moduleowners 
(
 `tabid` int(19) NOT NULL default '0',
 `user_id` varchar(11) NOT NULL default '',
 PRIMARY KEY  (`tabid`),
 CONSTRAINT `fk_ModuleOwners` FOREIGN KEY (`tabid`) REFERENCES `aicrm_tab` (`tabid`) ON DELETE CASCADE
) TYPE=InnoDB";
*/
Execute($create_query2);

//Populated the default entries for aicrm_moduleowners which is created newly
$module_array = Array(
		'Potentials',
		'Contacts',
		'Accounts',
		'Leads',
		'Notes',
		'Activities',
		'Emails',
		'HelpDesk',
		'Products',
		'Faq',
		'Vendor',
		'PriceBook',
		'Quotes',
		'Orders',
		'SalesOrder',
		'Invoice',
		'Reports'
		);
foreach($module_array as $mod)
{
	$query = "insert into aicrm_moduleowners values(".$this->localGetTabID($mod).",1)";
	Execute($query);
}


//Changes made to include status aicrm_field in Activity Quickcreate Form
$update_array4 = Array(
		"UPDATE aicrm_field SET quickcreate=0,quickcreatesequence=3 WHERE tabid=16 and fieldname='eventstatus'",
		"UPDATE aicrm_field SET quickcreate=0,quickcreatesequence=4 WHERE tabid=16 and fieldname='activitytype'",
		"UPDATE aicrm_field SET quickcreate=0,quickcreatesequence=5 WHERE tabid=16 and fieldname='duration_hours'",

		"UPDATE aicrm_field SET quickcreate=0,quickcreatesequence=3 WHERE tabid=9 and fieldname='taskstatus'",
		);
foreach($update_array4 as $query)
{
	Execute($query);
}



//Table 'inventory_tandc' added newly to include Inventory Terms &Conditions
$create_query1 = "CREATE TABLE  aicrm_inventory_tandc(id INT(19),type VARCHAR(30) NOT NULL,tandc LONGTEXT default NULL,PRIMARY KEY(id))";
Execute($create_query1);

$insert_query4 = "insert into aicrm_inventory_tandc values('".$conn->getUniqueID('aicrm_inventory_tandc')."','Inventory','  ')";
Execute($insert_query4);

/****************** 5.0(Alpha) dev version 1 Database changes -- Ends*********************/










/****************** 5.0(Alpha) dev version 2 Database changes -- Starts*********************/

$query1 = "ALTER TABLE aicrm_leadaddress change lane lane varchar(250)";
Execute($query1);

$rename_table_array1 = Array(
		"update aicrm_field set tablename='aicrm_customerdetails' where tabid=4 and fieldname in ('portal','support_start_date','support_end_date')",
		"alter table aicrm_PortalInfo drop foreign key fk_PortalInfo",
		"rename table aicrm_PortalInfo to aicrm_portalinfo",
		"alter table aicrm_portalinfo add CONSTRAINT `fk_portalinfo` FOREIGN KEY (`id`) REFERENCES `aicrm_contactdetails` (`contactid`) ON DELETE CASCADE",
		"alter table aicrm_CustomerDetails drop foreign key fk_CustomerDetails",
		"rename table aicrm_CustomerDetails to aicrm_customerdetails",
		"alter table aicrm_customerdetails add CONSTRAINT `fk_customerdetails` FOREIGN KEY (`customerid`) REFERENCES `aicrm_contactdetails` (`contactid`) ON DELETE CASCADE"
		);
foreach($rename_table_array1 as $query)
{
	Execute($query);
}


$query2 = "create table aicrm_ownernotify(crmid int(19),smownerid int(19),flag int(3))";
Execute($query2);


//Form the aicrm_role_map_array as aicrm_roleid=>name mapping array
$sql = "select * from aicrm_role";
$res = $conn->query($sql);
$role_map_array = Array();
for($i=0;$i<$conn->num_rows($res);$i++)
{
	$roleid = $conn->query_result($res,$i,'roleid');
	$name = $conn->query_result($res,$i,'name');
	$role_map_array[$roleid] = $name;
}
$conn->println("List of Roles (roleid => name) ==> ");$conn->println($role_map_array);
//echo '<pre> List of aicrm_roles :';print_r($role_map_array);echo '</pre>';

//Before delete the aicrm_role take a backup array for the table aicrm_user2role
$sql = "select * from aicrm_user2role";
$res = $conn->query($sql);
$user2role_array = array();
for($i=0;$i<$conn->num_rows($res);$i++)
{
	$userid = $conn->query_result($res,$i,'userid');
	$roleid = $conn->query_result($res,$i,'roleid');
	$user2role_array[$userid] = $roleid;
}
$conn->println("Users 2 Roles (userid => roleid) ==> ");$conn->println($user2role_array);
//echo '<pre> List of aicrm_user2role : (userid => aicrm_roleid)';print_r($user2role_array);echo '</pre>';

//Delete the aicrm_role entries
$sql = "truncate aicrm_role";
Execute($sql);


$query3 = "alter table aicrm_user2role drop FOREIGN KEY fk_user2role2";
Execute($query3);

//4,5 th are the Extra added queries
$alter_query_array1 = Array(
		"alter table aicrm_user2role change roleid roleid varchar(255)",
		"alter table aicrm_role2profile change roleid roleid varchar(255)",
		"alter table aicrm_role CHANGE roleid roleid varchar(255)",
		"alter table aicrm_role2profile drop PRIMARY KEY",
		"alter table aicrm_role2profile ADD PRIMARY KEY (roleid,profileid)"
		);
foreach($alter_query_array1 as $query)
{
	Execute($query);
}


$query4 = "ALTER TABLE aicrm_user2role ADD CONSTRAINT fk_user2role2 FOREIGN KEY (roleid) REFERENCES aicrm_role(roleid) ON DELETE CASCADE";
Execute($query4);

$alter_query_array2 = Array(
		"alter table aicrm_role CHANGE name rolename varchar(200)",
		"alter table aicrm_role DROP description",
		"alter table aicrm_role add parentrole varchar(255)",
		"alter table aicrm_role add depth int(19)"
		);
foreach($alter_query_array2 as $query)
{
	Execute($query);
}



$query5 = "insert into aicrm_role values('H1','Organisation','H1',0)";
Execute($query5);

//include("include/utils/UserInfoUtil.php");
//Create aicrm_role based on aicrm_role_map_array values and form the new_role_map_array with old aicrm_roleid and new aicrm_roleid
foreach($role_map_array as $roleid => $rolename)
{
	$parentRole = 'H1';
	if($rolename == 'standard_user')
	{
		$rs = $conn->query("select * from aicrm_role where rolename='administrator'");
		$parentRole = $conn->query_result($rs,0,'roleid');
	}
	$empty_array = array(""=>"");
	$new_role_id = localcreateRole($rolename,$parentRole,$empty_array);
	$new_role_map_array[$roleid] = $new_role_id;
}
$conn->println("Roles (oldroleid => newroleid) ==> ");$conn->println($new_role_map_array);

//Before insert the new entry we should remove the old entries -- added on 06-06-06
$user2role_del = "truncate aicrm_user2role";
Execute($user2role_del);

//First we will insert the old values from aicrm_user2role_array to aicrm_user2role table and then update the new aicrm_role id
foreach($user2role_array as $userid => $roleid)
{
	$sql = "insert into aicrm_user2role (userid, roleid) values(".$userid.",'".$new_role_map_array[$roleid]."')";
	Execute($sql);
}
//Commented the following loop as we have backup the aicrm_user2role and insert the entries with the new rold id using new_role_map_array above
//Update the aicrm_user2role table with new aicrm_roleid
/*
   foreach($new_role_map_array as $old_roleid => $new_roleid)
   {
   $update_user2role = "update aicrm_user2role set aicrm_roleid='".$new_roleid."' where aicrm_roleid=".$old_roleid;
   Execute($update_user2role);
   }
 */
//Update the aicrm_role2profile table with new aicrm_roleid
foreach($new_role_map_array as $old_roleid => $new_roleid)
{
	$update_role2profile = "update aicrm_role2profile set roleid='".$new_roleid."' where roleid=".$old_roleid;
	Execute($update_role2profile);
}



//Group Migration:
//Step 1 :  form and group_map_array as groupname => description from aicrm_groups table
//Step 2 :  form an aicrm_users2group_map_array array as userid => groupname from aicrm_users2group table
//Step 3 :  delete all entries from aicrm_groups table and enter new values from group_map_array
//Step 4 :  drop the table aicrm_users2group and create new table
//Step 5 :  put entries to aicrm_users2group table based on aicrm_users2group_map_array. Here get the groupid from aicrm_groups table based on groupname

//Step 1 : Form the group_map_array as groupname => description
$sql = "select * from aicrm_groups";
$res = $conn->query($sql);
$group_map_array = Array();
for($i=0;$i<$conn->num_rows($res);$i++)
{
	$name = $conn->query_result($res,$i,'name');
	$desc = $conn->query_result($res,$i,'description');
	$group_map_array[$name] = $desc;
}
$conn->println("List of Groups (name => description) ==> ");$conn->println($group_map_array);
//echo '<pre>List of Groups : ';print_r($group_map_array);echo '</pre>';


//Step 2 : form an aicrm_users2group_map_array array as userid => groupname from aicrm_users2group table
$sql = "select * from aicrm_users2group";
$res = $conn->query($sql);
$users2group_map_array = Array();
for($i=0;$i<$conn->num_rows($res);$i++)
{
	$groupname = $conn->query_result($res,$i,'groupname');
	$userid = $conn->query_result($res,$i,'userid');
	$users2group_map_array[$userid] = $groupname;
}
$conn->println("Users 2 Groups (userid => groupname) ==> ");$conn->println($users2group_map_array);
//echo '<pre>List of aicrm_users2group : ';print_r($users2group_map_array);echo '</pre>';

//Step 3 : delete all entries from aicrm_groups table
$sql = "truncate aicrm_groups";
Execute($sql);

$alter_query_array3 = Array(
		"alter table aicrm_users2group drop FOREIGN KEY fk_users2group",
		"alter table aicrm_leadgrouprelation drop FOREIGN KEY fk_leadgrouprelation2",
		"alter table aicrm_activitygrouprelation drop FOREIGN KEY fk_activitygrouprelation2",
		"alter table aicrm_ticketgrouprelation drop FOREIGN KEY fk_ticketgrouprelation2",
		"alter table aicrm_groups drop PRIMARY KEY"
		);
foreach($alter_query_array3 as $query)
{
	Execute($query);
}

//2 nd query is the Extra added query
//Adding columns in group table:
$alter_query_array4 = Array(
		"alter table aicrm_groups add column groupid int(19) FIRST",
		"alter table aicrm_groups change name  groupname varchar(100)",
		"alter table aicrm_groups ADD PRIMARY KEY (groupid)",
		"alter table aicrm_groups add index (groupname)"
		);
foreach($alter_query_array4 as $query)
{
	Execute($query);
}


//Moved the create table queries for aicrm_group2grouprel, aicrm_group2role, aicrm_group2rs from the end of this block
//Added on 06-06-06
$query8 = "CREATE TABLE aicrm_group2grouprel (
	  `groupid` int(19) NOT NULL,
	    `containsgroupid` int(19) NOT NULL,
	      PRIMARY KEY  (`groupid`,`containsgroupid`)
      ) ENGINE=InnoDB";
      /*
$query8 = "CREATE TABLE aicrm_group2grouprel 
(
 `groupid` int(19) NOT NULL default '0',
 `containsgroupid` int(19) NOT NULL default '0',
 PRIMARY KEY (`groupid`,`containsgroupid`),
 CONSTRAINT `fk_group2grouprel1` FOREIGN KEY (`groupid`) REFERENCES `aicrm_groups` (`groupid`) ON DELETE CASCADE
) TYPE=InnoDB";
*/
Execute($query8);

//Added on 06-06-06
$query9 = "CREATE TABLE aicrm_group2role (
	  `groupid` int(19) NOT NULL,
	    `roleid` varchar(255) NOT NULL,
	      PRIMARY KEY  (`groupid`,`roleid`)
      ) ENGINE=InnoDB";
/*
$query9 = "CREATE TABLE aicrm_group2role 
(
 `groupid` int(19) NOT NULL default '0',
 `roleid` varchar(255) NOT NULL default '',
 PRIMARY KEY (`groupid`,`roleid`),
 CONSTRAINT `fk_group2role1` FOREIGN KEY (`groupid`) REFERENCES `aicrm_groups` (`groupid`) ON DELETE CASCADE
) TYPE=InnoDB";
*/
Execute($query9);

//Added on 06-06-06
$query10 = "CREATE TABLE aicrm_group2rs (
	  `groupid` int(19) NOT NULL,
	    `roleandsubid` varchar(255) NOT NULL,
	      PRIMARY KEY  (`groupid`,`roleandsubid`)
      ) ENGINE=InnoDB";
/*
$query10 = "CREATE TABLE aicrm_group2rs 
(
 `groupid` int(19) NOT NULL default '0',
 `roleandsubid` varchar(255) NOT NULL default '',
 PRIMARY KEY (`groupid`,`roleandsubid`),
 CONSTRAINT `fk_group2rs1` FOREIGN KEY (`groupid`) REFERENCES `aicrm_groups` (`groupid`) ON DELETE CASCADE
) TYPE=InnoDB";
*/
Execute($query10);

//Insert all the retrieved old values to the new aicrm_groups table ie., create new aicrm_groups
foreach($group_map_array as $groupname => $description)
{
	$empty_array = array(
			"groups" => array(""=>""),
			"roles" => array(""=>""),
			"rs" => array(""=>""),
			"users" => array(""=>"")
			);
	$groupid = createGroup($groupname,$empty_array,$description);
	$group_name_id_mapping[$groupname] = $groupid;
}
$conn->println("List of Groups Created (groupname => groupid) ==> ");$conn->println($group_name_id_mapping);


//Copy all mappings in a user2grop table in a array;

//Step 4 : Drop and again create users2group
$query6 = "drop table aicrm_users2group";
Execute($query6);

//Added on 06-06-06
$query7 = "CREATE TABLE aicrm_users2group (
	  `groupid` int(19) NOT NULL,
	    `userid` int(19) NOT NULL,
	      PRIMARY KEY  (`groupid`,`userid`),
	        KEY `users2group_groupname_uerid_idx` (`groupid`,`userid`)
	) ENGINE=InnoDB";
/*
$query7 = "CREATE TABLE aicrm_users2group 
(
 `groupid` int(19) NOT NULL default '0',
 `userid` int(19) NOT NULL default '0',
 PRIMARY KEY (`groupid`,`userid`),
 CONSTRAINT `fk_users2group1` FOREIGN KEY (`groupid`) REFERENCES `groups` (`groupid`) ON DELETE CASCADE
) TYPE=InnoDB";
*/
Execute($query7);

//Step 5 : put entries to aicrm_users2group table based on aicrm_users2group_map_array. Here get the groupid from aicrm_groups table based on groupname
foreach($users2group_map_array as $userid => $groupname)
{
	//$groupid = $conn->query_result($conn->query("select * from aicrm_groups where groupname='".$groupname."'"),0,'groupid');
	$sql = "insert into aicrm_users2group (groupid,userid) values(".$group_name_id_mapping[$groupname].",".$userid.")";
	Execute($sql);
}


$alter_query_array5 = Array(
		"alter table aicrm_leadgrouprelation ADD CONSTRAINT fk_leadgrouprelation2 FOREIGN KEY (groupname) REFERENCES aicrm_groups(groupname) ON DELETE CASCADE",
		"ALTER TABLE aicrm_activitygrouprelation ADD CONSTRAINT fk_activitygrouprelation2 FOREIGN KEY (groupname) REFERENCES aicrm_groups(groupname) ON DELETE CASCADE",
		"ALTER TABLE aicrm_ticketgrouprelation ADD CONSTRAINT fk_ticketgrouprelation2 FOREIGN KEY (groupname) REFERENCES aicrm_groups(groupname) ON DELETE CASCADE"
		);
foreach($alter_query_array5 as $query)
{
	Execute($query);
}
//Moved the create table queries for aicrm_group2grouprel, aicrm_group2role, aicrm_group2rs to before creatinf the Group ie., before call the createGroup


/***Added to include decimal places for amount aicrm_field in aicrm_potential table  --by Mangai 15-Nov-2005***/

$query11 = "ALTER TABLE aicrm_potential change amount amount decimal(10,2)";
Execute($query11);

/****************** 5.0(Alpha) dev version 2 Database changes -- Ends*********************/












/****************** 5.0(Alpha) dev version 3 Database changes -- Starts*********************/

//Drop the column company_name from aicrm_vendor table ---- modified by Mickie on 18-11-2005
$altersql1 = "alter table aicrm_vendor drop column company_name";
Execute($altersql1);
$altersql2 = "alter table aicrm_vendor change column name vendorname varchar(100) default NULL";
$conn->query($altersql2);
Execute("update aicrm_field set fieldname='vendorname', columnname='vendorname' where tabid=18 and fieldname='name'");
//TODO (check): Remove this company_name entry from the aicrm_field table if it already exists

//Migration for Default Organisation Share -- Added by Don on 20-11-2005

$query1 = "CREATE TABLE aicrm_org_share_action_mapping (
`share_action_id` int(19) NOT NULL default '0',
	`share_action_name` varchar(200) NOT NULL default '',
PRIMARY KEY  (`share_action_id`,`share_action_name`)
	) TYPE=InnoDB ";
Execute($query1);

$query2 = "CREATE TABLE aicrm_org_share_action2tab (
	`share_action_id` int(19) NOT NULL default '0',
	`tabid` int(19) NOT NULL default '0',
	PRIMARY KEY  (`share_action_id`,`tabid`),
	CONSTRAINT `fk_org_share_action2tab` FOREIGN KEY (`share_action_id`) REFERENCES `aicrm_org_share_action_mapping` (`share_action_id`) ON DELETE CASCADE
	) TYPE=InnoDB";
Execute($query2);


$query3 = "alter table aicrm_def_org_share add column editstatus int(19)";
Execute($query3);

$query4 = "delete from aicrm_def_org_share where tabid in(8,14,15,18,19)";
Execute($query4);



//Inserting values into org share action mapping
$insert_query_array1 = Array(
			"insert into aicrm_org_share_action_mapping values(0,'Public: Read Only')",
			"insert into aicrm_org_share_action_mapping values(1,'Public:Read,Create/Edit')",
			"insert into aicrm_org_share_action_mapping values(2,'Public: Read, Create/Edit, Delete')",
			"insert into aicrm_org_share_action_mapping values(3,'Private')",
			"insert into aicrm_org_share_action_mapping values(4,'Hide Details')",
			"insert into aicrm_org_share_action_mapping values(5,'Hide Details and Add Events')",
			"insert into aicrm_org_share_action_mapping values(6,'Show Details')",
			"insert into aicrm_org_share_action_mapping values(7,'Show Details and Add Events')"
			);
foreach($insert_query_array1 as $query)
{
	Execute($query);
}


//Inserting for all aicrm_tabs
$def_org_tabid=Array(2,4,6,7,9,10,13,16,20,21,22,23,26);
foreach($def_org_tabid as $def_tabid)
{
	$insert_query_array2 = Array(
			"insert into aicrm_org_share_action2tab values(0,".$def_tabid.")",
			"insert into aicrm_org_share_action2tab values(1,".$def_tabid.")",
			"insert into aicrm_org_share_action2tab values(2,".$def_tabid.")",
			"insert into aicrm_org_share_action2tab values(3,".$def_tabid.")"
			);
	foreach($insert_query_array2 as $query)
	{
		Execute($query);
	}
}

$insert_query_array3 = Array(
		"insert into aicrm_org_share_action2tab values(4,17)",
		"insert into aicrm_org_share_action2tab values(5,17)",
		"insert into aicrm_org_share_action2tab values(6,17)",
		"insert into aicrm_org_share_action2tab values(7,17)"
		);
foreach($insert_query_array3 as $query)
{
	Execute($query);
}

$query_array1 = Array(
		"insert into aicrm_def_org_share values(9,17,7,0)",
		"update aicrm_def_org_share set editstatus=0",
		"update aicrm_def_org_share set editstatus=2 where tabid=4",
		"update aicrm_def_org_share set editstatus=1 where tabid=9",
		"update aicrm_def_org_share set editstatus=2 where tabid=16"
		);
foreach($query_array1 as $query)
{
	Execute($query);
}

/****************** 5.0(Alpha) dev version 3 Database changes -- Ends*********************/



$migrationlog->debug("Database Modifications for 5.0(Alpha) Dev 3 ==> 5.0 Alpha starts here.");
//echo "<br><br><b>Database Modifications for 5.0(Alpha) Dev3 ==> 5.0 Alpha starts here.....</b><br>";
$alter_query_array6 = Array(
				"ALTER TABLE aicrm_users ADD column activity_view VARCHAR(25) DEFAULT 'Today' AFTER homeorder",
				"ALTER TABLE aicrm_activity ADD column notime VARCHAR(3) NOT NULL DEFAULT '0' AFTER location"
			   );
foreach($alter_query_array6 as $query)
{
	Execute($query);
}

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (9,".$newfieldid.",'notime','activity',1,56,'notime','No Time',1,0,0,100,20,1,3,'C~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('9',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (16,".$newfieldid.",'notime','activity',1,56,'notime','No Time',1,0,0,100,18,1,1,'C~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('16',$newfieldid);

$alter_query_array7 = Array(
				"alter table aicrm_vendor add column pobox varchar(30) after state",
				"alter table aicrm_leadaddress add column pobox varchar(30) after state",
				"alter table aicrm_accountbillads add column pobox varchar(30) after state",
				"alter table aicrm_accountshipads add column pobox varchar(30) after state",
				"alter table aicrm_contactaddress add column mailingpobox varchar(30) after mailingstate",
				"alter table aicrm_contactaddress add column otherpobox varchar(30) after otherstate",
				"alter table aicrm_quotesbillads add column bill_pobox varchar(30) after bill_street",
				"alter table aicrm_quotesshipads add column ship_pobox varchar(30) after ship_street",
				"alter table aicrm_pobillads add column bill_pobox varchar(30) after bill_street",
				"alter table aicrm_poshipads add column ship_pobox varchar(30) after ship_street",
				"alter table aicrm_sobillads add column bill_pobox varchar(30) after bill_street",
				"alter table aicrm_soshipads add column ship_pobox varchar(30) after ship_street",
				"alter table aicrm_invoicebillads add column bill_pobox varchar(30) after bill_street",
				"alter table aicrm_invoiceshipads add column ship_pobox varchar(30) after ship_street"
			   );
foreach($alter_query_array7 as $query)
{
	Execute($query);
}

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (23,".$newfieldid.",'bill_pobox','invoicebillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('23',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (23,".$newfieldid.",'ship_pobox','invoiceshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('23',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (6,".$newfieldid.",'pobox','accountbillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('6',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (6,".$newfieldid.",'pobox','accountshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('6',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (7,".$newfieldid.",'pobox','leadaddress',1,'1','pobox','Po Box',1,0,0,100,2,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('7',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (4,".$newfieldid.",'mailingpobox','contactaddress',1,'1','mailingpobox','Mailing Po Box',1,0,0,100,3,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('4',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (4,".$newfieldid.",'otherpobox','contactaddress',1,'1','otherpobox','Other Po Box',1,0,0,100,4,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('4',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (18,".$newfieldid.",'pobox','vendor',1,'1','pobox','Po Box',1,0,0,100,2,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('18',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (20,".$newfieldid.",'bill_pobox','quotesbillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('20',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (20,".$newfieldid.",'ship_pobox','quotesshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('20',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (21,".$newfieldid.",'bill_pobox','pobillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('21',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (21,".$newfieldid.",'ship_pobox','poshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('21',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (22,".$newfieldid.",'bill_pobox','sobillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('22',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (22,".$newfieldid.",'ship_pobox','soshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,2,1,'V~O',1,'')";
Execute($insert_query);
populateFieldForSecurity('22',$newfieldid);


$fieldname =array('bill_city','bill_state','bill_code','bill_country','ship_city','ship_state','ship_code','ship_country');
$tablename = array('accountbillads','quotesbillads','pobillads','sobillads','invoicebillads','accountshipads','quotesshipads','poshipads','soshipads','invoiceshipads');
$sequence = array(5,7,9,11,6,8,10,12);
$k = 0;
$n = 0;
for($j = 0;$j < 8;$j++)
{
	if($j == 4)
	$n = $n+5;
	for($i = 0;$i < 5;$i++)
	{
		$query1 = "update aicrm_field set sequence=".$sequence[$j]." where tablename='".$tablename[$n+$i]."' && fieldname='".$fieldname[$j]."'";
		Execute($query1);
	}
}

$fieldname = array('code','city','country','state');
$tablename = 'leadaddress';
$sequence = array(3,4,5,6);
for($i = 0;$i < 4;$i++)
{
	$query2 = "update aicrm_field set sequence=".$sequence[$i]." where tablename='".$tablename."' && fieldname='".$fieldname[$i]."'";
	Execute($query2);
}

$fieldname = array('city','state','postalcode','country');
$tablename = 'vendor';
$sequence = array(3,4,5,6);

for($i = 0;$i < 4;$i++)
{
	$query3 = "update aicrm_field set sequence=".$sequence[$i]." where tablename='".$tablename."' && fieldname='".$fieldname[$i]."'";
	Execute($query3);
}

$fieldname = array('mailingcity','othercity','mailingstate','otherstate','mailingzip','otherzip','mailingcountry','othercountry');
$tablename = 'contactaddress';
$sequence = array(5,6,7,8,9,10,11,12);

for($i = 0;$i < 8;$i++)
{
	$query = "update aicrm_field set sequence=".$sequence[$i]." where tablename='".$tablename."' && fieldname='".$fieldname[$i]."'";
	Execute($query);
}

$query_array1 = Array(
			"update aicrm_field set tablename='aicrm_crmentity' where tabid=10 and fieldname='description'",
			"update aicrm_field set tablename='aicrm_attachments' where tabid=10 and fieldname='filename'",
			"drop table aicrm_emails",

			"alter table aicrm_activity drop column description",
			"update aicrm_field set tablename='aicrm_crmentity' where tabid in (9,16) and fieldname='description'",

			"update aicrm_tab set name='PurchaseOrder',tablabel='PurchaseOrder' where tabid=21",
			"update aicrm_tab set presence=0 where tabid=22 and name='SalesOrder'",

			"delete from aicrm_actionmapping where actionname='SalesOrderDetailView'",
			"delete from aicrm_actionmapping where actionname='SalesOrderEditView'",
			"delete from aicrm_actionmapping where actionname='SaveSalesOrder'",
			"delete from aicrm_actionmapping where actionname='DeleteSalesOrder'",

			//"insert into aicrm_field values (13,".$conn->getUniqueID("aicrm_field").",'filename','aicrm_attachments',1,'61','filename','Attachment',1,0,0,100,12,2,1,'V~O',0,1)",

			"alter table aicrm_troubletickets add column filename varchar(50) default NULL after title"
		     );
foreach($query_array1 as $query)
{
	Execute($query);
}

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (13,".$newfieldid.",'filename','aicrm_attachments',1,'61','filename','Attachment',1,0,0,100,12,2,1,'V~O',0,1)";
Execute($insert_query);
populateFieldForSecurity('13',$newfieldid);


$create_query3 = "create table aicrm_parenttab(parenttabid int(19) not null, parenttab_label varchar(100) not null, sequence int(10) not null, visible int(2) not null default '0', Primary Key(parenttabid))";
Execute($create_query3);
$create_query4 = "create table aicrm_parenttabrel(parenttabid int(3) not null, tabid int(3) not null,sequence int(3) not null)";
Execute($create_query4);

$insert_query_array4 = Array(
				"insert into aicrm_parenttab values(1,'My Home Page',1,0),(2,'Marketing',2,0),(3,'Sales',3,0),(4,'Support',4,0),(5,'Analytics',5,0),(6,'Inventory',6,0), (7,'Tools',7,0),(8,'Settings',8,0)",
				"insert into aicrm_parenttabrel values(1,9,2),(1,17,3),(1,10,4),(1,3,1),(3,7,1),(3,6,2),(3,4,3),(3,2,4),(3,20,5),(3,22,6),(3,23,7),(3,14,8),(3,19,9),(3,8,10),(4,13,1),(4,15,2),(4,6,3),(4,4,4),(4,14,5),(4,8,6),(5,1,1),(5,25,2),(6,14,1), (6,18,2), (6,19,3), (6,21,4), (6,22,5), (6,20,6), (6,23,7), (7,24,1), (7,27,2), (7,8,3), (2,6,2), (2,4,3) "
			    );
foreach($insert_query_array4 as $query)
{
	Execute($query);
}


$create_query5 = "CREATE TABLE aicrm_blocks ( blockid int(19) NOT NULL, tabid int(19) NOT NULL, blocklabel varchar(100) NOT NULL, sequence int(19) NOT NULL, show_title int(2) NOT NULL, visible int(2) NOT NULL DEFAULT 0, create_view int(2) NOT NULL DEFAULT 0, edit_view int(2) NOT NULL DEFAULT 0, detail_view int(2) NOT NULL DEFAULT 0, PRIMARY KEY (blockid))";
Execute($create_query5);

$update_query_array1 = Array(
				"update aicrm_field set block=2 where tabid=2 and block=5",
				"update aicrm_field set block=3 where tabid=2 and block=2",

				//"update aicrm_field set block=4 where tabid=4 and block=1",
				"update aicrm_field set block=5 where tabid=4 and block=5",
				"update aicrm_field set block=6 where tabid=4 and block=4",//Modified on 24-04-06
				"update aicrm_field set block=4 where tabid=4 and block=1",
				"update aicrm_field set block=7 where tabid=4 and block=2",
				"update aicrm_field set block=8 where tabid=4 and block=3",

				"update aicrm_field set block=9 where tabid=6 and block=1",
				"update aicrm_field set block=10 where tabid=6 and block=5",
				"update aicrm_field set block=11 where tabid=6 and block=2",
				"update aicrm_field set block=12 where tabid=6 and block=3",

				"update aicrm_field set block=13 where tabid=7 and block=1",
				"update aicrm_field set block=14 where tabid=7 and block=5",
				"update aicrm_field set block=15 where tabid=7 and block=2",
				"update aicrm_field set block=16 where tabid=7 and block=3",

				"update aicrm_field set block=17 where tabid=8 and block=1",
				"update aicrm_field set block=17 where tabid=8 and block=2",
				"update aicrm_field set block=18 where tabid=8 and block=3",

				"update aicrm_field set block=19 where tabid=9 and block=1",
				"update aicrm_field set block=19 where tabid=9 and block=7",
				"update aicrm_field set block=20 where tabid=9 and block=2",

				"update aicrm_field set block=21 where tabid=10 and block=1",
				"update aicrm_field set block=22 where tabid=10 and block=2",
				"update aicrm_field set block=23 where tabid=10 and block=3",
				"update aicrm_field set block=23 where tabid=10 and block=4",
				"update aicrm_field set block=24 where tabid=10 and block=5",

				"update aicrm_field set block=25 where tabid=13 and block=1",
				"update aicrm_field set block=26 where tabid=13 and block=2",
				"update aicrm_field set block=27 where tabid=13 and block=5",
				"update aicrm_field set block=28 where tabid=13 and block=3",
				"update aicrm_field set block=29 where tabid=13 and block=4",
				"update aicrm_field set block=30 where tabid=13 and block=6",

				"update aicrm_field set block=31 where tabid=14 and block=1",
				"update aicrm_field set block=32 where tabid=14 and block=2",
				"update aicrm_field set block=33 where tabid=14 and block=3",
				"update aicrm_field set block=34 where tabid=14 and block=5",
				"update aicrm_field set block=35 where tabid=14 and block=6",
				"update aicrm_field set block=36 where tabid=14 and block=4",

				"update aicrm_field set block=37 where tabid=15 and block=1",
				"update aicrm_field set block=38 where tabid=15 and block=2",
				"update aicrm_field set block=39 where tabid=15 and block=3",
				"update aicrm_field set block=40 where tabid=15 and block=4",

				"update aicrm_field set block=41 where tabid=16 and block=1",
				"update aicrm_field set block=42 where tabid=16 and block=7",
				"update aicrm_field set block=43 where tabid=16 and block=2",

				"update aicrm_field set block=44 where tabid=18 and block=1",
				"update aicrm_field set block=45 where tabid=18 and block=5",
				"update aicrm_field set block=36 where tabid=18 and block=2",
				"update aicrm_field set block=47 where tabid=18 and block=3",

				"update aicrm_field set block=48 where tabid=19 and block=1",
				"update aicrm_field set block=49 where tabid=19 and block=5",
				"update aicrm_field set block=50 where tabid=19 and block=2",

				"update aicrm_field set block=51 where tabid=20 and block=1",
				"update aicrm_field set block=52 where tabid=20 and block=5",
				"update aicrm_field set block=53 where tabid=20 and block=2",
				"update aicrm_field set block=55 where tabid=20 and block=6",
				"update aicrm_field set block=56 where tabid=20 and block=3",

				"update aicrm_field set block=57 where tabid=21 and block=1",
				"update aicrm_field set block=58 where tabid=21 and block=5",
				"update aicrm_field set block=59 where tabid=21 and block=2",
				"update aicrm_field set block=61 where tabid=21 and block=6",
				"update aicrm_field set block=62 where tabid=21 and block=3",

				"update aicrm_field set block=63 where tabid=22 and block=1",
				"update aicrm_field set block=64 where tabid=22 and block=5",
				"update aicrm_field set block=65 where tabid=22 and block=2",
				"update aicrm_field set block=67 where tabid=22 and block=6",
				"update aicrm_field set block=68 where tabid=22 and block=3",


				"update aicrm_field set block=69 where tabid=23 and block=1",
				"update aicrm_field set block=70 where tabid=23 and block=5",
				"update aicrm_field set block=71 where tabid=23 and block=2",
				"update aicrm_field set block=73 where tabid=23 and block=6",
				"update aicrm_field set block=74 where tabid=23 and block=3",
			    );
foreach($update_query_array1 as $query)
{
	Execute($query);
}

$insert_query_array5 = Array(
				"insert into aicrm_blocks values (1,2,'LBL_OPPORTUNITY_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (2,2,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into aicrm_blocks values (3,2,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0)",
				"insert into aicrm_blocks values (4,4,'LBL_CONTACT_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (5,4,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into aicrm_blocks values (6,4,'LBL_CUSTOMER_PORTAL_INFORMATION',3,0,0,0,0,0)",
				"insert into aicrm_blocks values (7,4,'LBL_ADDRESS_INFORMATION',4,0,0,0,0,0)",
				"insert into aicrm_blocks values (8,4,'LBL_DESCRIPTION_INFORMATION',5,0,0,0,0,0)",
				"insert into aicrm_blocks values (9,6,'LBL_ACCOUNT_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (10,6,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into aicrm_blocks values (11,6,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into aicrm_blocks values (12,6,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)",
				"insert into aicrm_blocks values (13,7,'LBL_LEAD_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (14,7,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into aicrm_blocks values (15,7,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into aicrm_blocks values (16,7,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)",
				"insert into aicrm_blocks values (17,8,'LBL_NOTE_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (18,8,'',2,1,0,0,0,0)",
				"insert into aicrm_blocks values (19,9,'LBL_TASK_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (20,9,'',2,1,0,0,0,0)",
				"insert into aicrm_blocks values (21,10,'LBL_EMAIL_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (22,10,'',2,1,0,0,0,0)",
				"insert into aicrm_blocks values (23,10,'',3,1,0,0,0,0)",
				"insert into aicrm_blocks values (24,10,'',4,1,0,0,0,0)",
				"insert into aicrm_blocks values (25,13,'LBL_TICKET_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (26,13,'',2,1,0,0,0,0)",
				"insert into aicrm_blocks values (27,13,'LBL_CUSTOM_INFORMATION',3,0,0,0,0,0)",
				"insert into aicrm_blocks values (28,13,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)",
				"insert into aicrm_blocks values (29,13,'LBL_TICKET_RESOLUTION',5,0,0,1,0,0)",
				"insert into aicrm_blocks values (30,13,'LBL_COMMENTS',6,0,0,1,0,0)",
				"insert into aicrm_blocks values (31,14,'LBL_PRODUCT_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (32,14,'LBL_PRICING_INFORMATION',2,0,0,0,0,0)",
				"insert into aicrm_blocks values (33,14,'LBL_STOCK_INFORMATION',3,0,0,0,0,0)",
				"insert into aicrm_blocks values (34,14,'LBL_CUSTOM_INFORMATION',4,0,0,0,0,0)",
				"insert into aicrm_blocks values (35,14,'LBL_IMAGE_INFORMATION',5,0,0,0,0,0)",
				"insert into aicrm_blocks values (36,14,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)",
				"insert into aicrm_blocks values (37,15,'LBL_FAQ_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (38,15,'',2,1,0,0,0,0)",
				"insert into aicrm_blocks values (39,15,'',3,1,0,0,0,0)",
				"insert into aicrm_blocks values (40,15,'LBL_COMMENT_INFORMATION',4,0,0,1,0,0)",
				"insert into aicrm_blocks values (41,16,'LBL_EVENT_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (42,16,'',2,1,0,0,0,0)",
				"insert into aicrm_blocks values (43,16,'',3,1,0,0,0,0)",
				"insert into aicrm_blocks values (44,18,'LBL_VENDOR_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (45,18,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into aicrm_blocks values (46,18,'LBL_VENDOR_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into aicrm_blocks values (47,18,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)",
				"insert into aicrm_blocks values (48,19,'LBL_PRICEBOOK_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (49,19,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into aicrm_blocks values (50,19,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0)",
				"insert into aicrm_blocks values (51,20,'LBL_QUOTE_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (52,20,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into aicrm_blocks values (53,20,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into aicrm_blocks values (54,20,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)",
				"insert into aicrm_blocks values (55,20,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)",
				"insert into aicrm_blocks values (56,20,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)",
				"insert into aicrm_blocks values (57,21,'LBL_PO_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (58,21,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into aicrm_blocks values (59,21,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into aicrm_blocks values (60,21,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)",
				"insert into aicrm_blocks values (61,21,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)",
				"insert into aicrm_blocks values (62,21,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)",
				"insert into aicrm_blocks values (63,22,'LBL_SO_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (64,22,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into aicrm_blocks values (65,22,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into aicrm_blocks values (66,22,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)",
				"insert into aicrm_blocks values (67,22,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)",
				"insert into aicrm_blocks values (68,22,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)",
				"insert into aicrm_blocks values (69,23,'LBL_INVOICE_INFORMATION',1,0,0,0,0,0)",
				"insert into aicrm_blocks values (70,23,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)",
				"insert into aicrm_blocks values (71,23,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
				"insert into aicrm_blocks values (72,23,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)",
				"insert into aicrm_blocks values (73,23,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)",
				"insert into aicrm_blocks values (74,23,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)"
			    );
foreach($insert_query_array5 as $query)
{
	Execute($query);
}

$update_query_array2 = Array(
				"update aicrm_tab set name='Vendors', tablabel='Vendors' where tabid=18",
				"update aicrm_tab set name='PriceBooks', tablabel='PriceBooks' where tabid=19",
				"update aicrm_tab set presence=0 where tabid in(18,19)",
				"update aicrm_relatedlists set label='PriceBooks' where tabid=14 and related_tabid=19"
			    );
foreach($update_query_array2 as $query)
{
	Execute($query);
}

$delete_query1 = "delete from aicrm_actionmapping where actionname in ('SavePriceBook','SaveVendor','PriceBookEditView','VendorEditView','DeletePriceBook','DeleteVendor','PriceBookDetailView','VendorDetailView')";
Execute($delete_query1);

$insert_query_array6 = Array(
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'Leads')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'Accounts')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'Contacts')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'Potentials')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'HelpDesk')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'Quotes')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'Activities')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'Emails')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'Invoice')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'Notes')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'PriceBooks')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'Products')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'PurchaseOrder')",
				
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'SalesOrder')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'Vendors')",
			"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$conn->getUniqueID('aicrm_customview').",'All',1,0,'Faq')"
			    );
foreach($insert_query_array6 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='Leads'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array7 = Array(
			"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_leaddetails:lastname:lastname:Leads_Last_Name:V')",
			"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_leaddetails:firstname:firstname:Leads_First_Name:V')",
			"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_leaddetails:company:company:Leads_Company:V')",
			"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_leadaddress:phone:phone:Leads_Phone:V')",
			"insert into aicrm_cvcolumnlist values ($cvid,4,'aicrm_leadsubdetails:website:website:Leads_Website:V')",
			"insert into aicrm_cvcolumnlist values ($cvid,5,'aicrm_leaddetails:email:email:Leads_Email:V')",
			"insert into aicrm_cvcolumnlist values ($cvid,6,'aicrm_crmentity:smownerid:assigned_user_id:Leads_Assigned_To:V')"
			    );
foreach($insert_query_array7 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='Accounts'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array8 = Array(
		"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_account:accountname:accountname:Accounts_Account_Name:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_accountbillads:city:bill_city:Accounts_City:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_account:website:website:Accounts_Website:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_account:phone:phone:Accounts_Phone:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,4,'aicrm_crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V')"
			    );
foreach($insert_query_array8 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='Contacts'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array9 = Array(
		"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_contactdetails:firstname:firstname:Contacts_First_Name:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_contactdetails:lastname:lastname:Contacts_Last_Name:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_contactdetails:title:title:Contacts_Title:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_account:accountname:accountname:Contacts_Account_Name:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,4,'aicrm_contactdetails:email:email:Contacts_Email:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,5,'aicrm_contactdetails:phone:phone:Contacts_Office_Phone:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,6,'aicrm_crmentity:smownerid:assigned_user_id:Contacts_Assigned_To:V')"
			    );
foreach($insert_query_array9 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='Potentials'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array10 = Array(
	"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_potential:potentialname:potentialname:Potentials_Potential_Name:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_account:accountname:accountname:Potentials_Account_Name:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_potential:amount:amount:Potentials_Amount:N')",
	"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_potential:closingdate:closingdate:Potentials_Expected_Close_Date:D')",
	"insert into aicrm_cvcolumnlist values ($cvid,4,'aicrm_crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V')"
			     );
foreach($insert_query_array10 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='HelpDesk'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array11 = Array(
		"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_crmentity:crmid::HelpDesk_Ticket_ID:I')",
		"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_troubletickets:title:ticket_title:HelpDesk_Title:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_troubletickets:parent_id:parent_id:HelpDesk_Related_to:I')",
		"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_troubletickets:status:ticketstatus:HelpDesk_Status:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,4,'aicrm_troubletickets:priority:ticketpriorities:HelpDesk_Priority:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,5,'aicrm_crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V')"
			     );
foreach($insert_query_array11 as $query)
{
	Execute($query);
}


$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='Quotes'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array12 = Array(
		"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_crmentity:crmid::Quotes_Quote_ID:I')",
		"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_quotes:subject:subject:Quotes_Subject:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_quotes:quotestage:quotestage:Quotes_Quote_Stage:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_quotes:potentialid:potential_id:Quotes_Potential_Name:I')",
		"insert into aicrm_cvcolumnlist values ($cvid,4,'aicrm_account:accountname:accountname:Quotes_Account_Name:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,5,'aicrm_quotes:total:hdnGrandTotal:Quotes_Total:I')",
		"insert into aicrm_cvcolumnlist values ($cvid,6,'aicrm_crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V')"
			     );
foreach($insert_query_array12 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='Activities'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array13 = Array(
		"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_activity:status:taskstatus:Activities_Status:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_activity:activitytype:activitytype:Activities_Type:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_activity:subject:subject:Activities_Subject:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_contactdetails:lastname:lastname:Activities_Contact_Name:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,4,'aicrm_seactivityrel:crmid:parent_id:Activities_Related_To:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,5,'aicrm_activity:date_start:date_start:Activities_Start_Date:D')",
		"insert into aicrm_cvcolumnlist values ($cvid,6,'aicrm_activity:due_date:due_date:Activities_End_Date:D')",
		"insert into aicrm_cvcolumnlist values ($cvid,7,'aicrm_crmentity:smownerid:assigned_user_id:Activities_Assigned_To:V')"
			     );
foreach($insert_query_array13 as $query)
{
	Execute($query);
}


$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='Emails'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array14 = Array(
		"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_activity:subject:subject:Emails_Subject:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_seactivityrel:crmid:parent_id:Emails_Related_To:I')",
		"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_activity:date_start:date_start:Emails_Date_Sent:D')",
		"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_crmentity:smownerid:assigned_user_id:Emails_Assigned_To:V')"
			     );
foreach($insert_query_array14 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='Invoice'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array15 = Array(
	"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_crmentity:crmid::Invoice_Invoice_Id:I')",
	"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_invoice:subject:subject:Invoice_Subject:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_invoice:salesorderid:salesorder_id:Invoice_Sales_Order:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_invoice:invoicestatus:invoicestatus:Invoice_Status:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,4,'aicrm_invoice:total:hdnGrandTotal:Invoice_Total:I')",
	"insert into aicrm_cvcolumnlist values ($cvid,5,'aicrm_crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V')"
			     );
foreach($insert_query_array15 as $query)
{
	Execute($query);
}

	     
$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='Notes'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array16 = Array(
		"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_notes:title:title:Notes_Title:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_notes:contact_id:contact_id:Notes_Contact_Name:I')",
		"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_senotesrel:crmid:parent_id:Notes_Related_to:I')",
		"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_notes:filename:filename:Notes_File:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,4,'aicrm_crmentity:modifiedtime:modifiedtime:Notes_Modified_Time:V')"
			     );
foreach($insert_query_array16 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='PriceBooks'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array17 = Array(
		"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_pricebook:bookname:bookname:PriceBooks_Price_Book_Name:V')",
		"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_pricebook:active:active:PriceBooks_Active:V')"
			     );
foreach($insert_query_array17 as $query)
{
	Execute($query);
}


$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='Products'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array18 = Array(
	"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_products:productname:productname:Products_Product_Name:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_products:productcode:productcode:Products_Product_Code:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_products:commissionrate:commissionrate:Products_Commission_Rate:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_products:qty_per_unit:qty_per_unit:Products_Qty/Unit:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,4,'aicrm_products:unit_price:unit_price:Products_Unit_Price:V')"
			     );
foreach($insert_query_array18 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='PurchaseOrder'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array19 = Array(
	"insert into aicrm_cvcolumnlist values($cvid,0,'aicrm_crmentity:crmid::PurchaseOrder_Order_Id:I')",
	"insert into aicrm_cvcolumnlist values($cvid,1,'aicrm_purchaseorder:subject:subject:PurchaseOrder_Subject:V')",
	"insert into aicrm_cvcolumnlist values($cvid,2,'aicrm_purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:I')",
	"insert into aicrm_cvcolumnlist values($cvid,3,'aicrm_purchaseorder:tracking_no:tracking_no:PurchaseOrder_Tracking_Number:V')",
	"insert into aicrm_cvcolumnlist values($cvid,4,'aicrm_crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V')"
			     );
foreach($insert_query_array19 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='SalesOrder'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array20 = Array(
	"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_crmentity:crmid::SalesOrder_Order_Id:I')",
	"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_salesorder:subject:subject:SalesOrder_Subject:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_account:accountname:accountname:SalesOrder_Account_Name:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_quotes:quoteid:quote_id:SalesOrder_Quote_Name:I')",
	"insert into aicrm_cvcolumnlist values ($cvid,4,'aicrm_salesorder:total:hdnGrandTotal:SalesOrder_Total:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,5,'aicrm_crmentity:smownerid:assigned_user_id:SalesOrder_Assigned_To:V')"
			     );
foreach($insert_query_array20 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='Vendors'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array21 = Array(
	"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_vendor:vendorname:vendorname:Vendors_Vendor_Name:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_vendor:phone:phone:Vendors_Phone:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_vendor:email:email:Vendors_Email:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_vendor:category:category:Vendors_Category:V')"
			     );
foreach($insert_query_array21 as $query)
{
	Execute($query);
}

$res=$conn->query("select cvid from aicrm_customview where viewname='All' and entitytype='Faq'");
$cvid = $conn->query_result($res,0,"cvid");

$insert_query_array22 = Array(
	"insert into aicrm_cvcolumnlist values ($cvid,0,'aicrm_faq:id::Faq_FAQ_Id:I')",
	"insert into aicrm_cvcolumnlist values ($cvid,1,'aicrm_faq:question:question:Faq_Question:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,2,'aicrm_faq:category:faqcategories:Faq_Category:V')",
	"insert into aicrm_cvcolumnlist values ($cvid,3,'aicrm_faq:product_id:product_id:Faq_Product_Name:I')",
	"insert into aicrm_cvcolumnlist values ($cvid,4,'aicrm_crmentity:createdtime:createdtime:Faq_Created_Time:D')",
	"insert into aicrm_cvcolumnlist values ($cvid,5,'aicrm_crmentity:modifiedtime:modifiedtime:Faq_Modified_Time:D')"
			     );
foreach($insert_query_array22 as $query)
{
	Execute($query);
}


$update_query_array3 = Array(
				"update aicrm_field set uitype=53 where tabid=2 and columnname='smownerid'",
				"update aicrm_field set uitype=53 where tabid=4 and columnname='smownerid'",
				"update aicrm_field set uitype=53 where tabid=20 and columnname='smownerid'",
				"update aicrm_field set uitype=53 where tabid=22 and columnname='smownerid'",
				"update aicrm_field set uitype=53 where tabid=23 and columnname='smownerid'"
			    );
foreach($update_query_array3 as $query)
{
	Execute($query);
}

//Added on 26-06-06 - we cannot add foreign key in type MyISAM, so we have to change the type to InnoDB
$alter_tables_array = Array("aicrm_groups","aicrm_potential","aicrm_quotes","aicrm_salesorder","aicrm_invoice","aicrm_purchaseorder","aicrm_products","aicrm_account","aicrm_contactdetails","aicrm_vendor","aicrm_users","aicrm_attachments","aicrm_profile");
foreach($alter_tables_array as $tablename)
{
	Execute("alter table $tablename type=InnoDB");
}


$create_query6 = "CREATE TABLE aicrm_accountgrouprelation ( accountid int(19) NOT NULL, groupname varchar(100) default NULL, PRIMARY KEY  (`accountid`)) ENGINE=InnoDB";
Execute($create_query6);

$alter_query_array8 = Array(
				"alter table aicrm_accountgrouprelation ADD CONSTRAINT fk_1_aicrm_accountgrouprelation FOREIGN KEY (accountid) REFERENCES aicrm_account(accountid) ON DELETE CASCADE",
				"alter table aicrm_accountgrouprelation ADD CONSTRAINT fk_2_aicrm_accountgrouprelation FOREIGN KEY (groupname) REFERENCES aicrm_groups(groupname) ON DELETE CASCADE",
				"ALTER TABLE `aicrm_accountgrouprelation` ADD KEY accountgrouprelation_groupname_idx (groupname)",
			   );
foreach($alter_query_array8 as $query)
{
	Execute($query);
}

$create_query7 = "CREATE TABLE aicrm_contactgrouprelation ( contactid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`contactid`))";
Execute($create_query7);

$alter_query_array9 = Array(
				"alter table aicrm_contactgrouprelation ADD CONSTRAINT fk_contactgrouprelation FOREIGN KEY (contactid) REFERENCES aicrm_contactdetails(contactid) ON DELETE CASCADE",
				"alter table aicrm_contactgrouprelation ADD CONSTRAINT fk_contactgrouprelation2 FOREIGN KEY (groupname) REFERENCES aicrm_groups(groupname) ON DELETE CASCADE"
			   );
foreach($alter_query_array9 as $query)
{
	Execute($query);
}


$create_query10 = "CREATE TABLE aicrm_potentialgrouprelation ( potentialid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`potentialid`))";
Execute($create_query10);

$alter_query_array10 = Array(
				"alter table aicrm_potentialgrouprelation ADD CONSTRAINT fk_potentialgrouprelation FOREIGN KEY (potentialid) REFERENCES aicrm_potential(potentialid) ON DELETE CASCADE",
				"alter table aicrm_potentialgrouprelation ADD CONSTRAINT fk_potentialgrouprelation2 FOREIGN KEY (groupname) REFERENCES aicrm_groups(groupname) ON DELETE CASCADE"
			    );
foreach($alter_query_array10 as $query)
{
	Execute($query);
}

$create_query11 = "CREATE TABLE aicrm_quotegrouprelation ( quoteid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`quoteid`) )";
Execute($create_query11);

$alter_query_array11 = Array(
				"alter table aicrm_quotegrouprelation ADD CONSTRAINT fk_quotegrouprelation FOREIGN KEY (quoteid) REFERENCES aicrm_quotes(quoteid) ON DELETE CASCADE",
				"alter table aicrm_quotegrouprelation ADD CONSTRAINT fk_quotegrouprelation2 FOREIGN KEY (groupname) REFERENCES aicrm_groups(groupname) ON DELETE CASCADE"
			    );
foreach($alter_query_array11 as $query)
{
	Execute($query);
}

$create_query12 = "CREATE TABLE aicrm_sogrouprelation ( salesorderid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`salesorderid`) )";
Execute($create_query12);

$alter_query_array12 = Array(
				"alter table aicrm_sogrouprelation ADD CONSTRAINT fk_sogrouprelation FOREIGN KEY (salesorderid) REFERENCES aicrm_salesorder(salesorderid) ON DELETE CASCADE",
				"alter table aicrm_sogrouprelation ADD CONSTRAINT fk_sogrouprelation2 FOREIGN KEY (groupname) REFERENCES aicrm_groups(groupname) ON DELETE CASCADE"
			    );
foreach($alter_query_array12 as $query)
{
	Execute($query);
}

$create_query13 = "CREATE TABLE aicrm_invoicegrouprelation ( invoiceid int(19) NOT NULL default '0',  groupname varchar(100) default NULL,  PRIMARY KEY  (`invoiceid`))";
Execute($create_query13);

$alter_query_array13 = Array(
				"alter table aicrm_invoicegrouprelation ADD CONSTRAINT fk_invoicegrouprelation FOREIGN KEY (invoiceid) REFERENCES aicrm_invoice(invoiceid) ON DELETE CASCADE",
				"alter table aicrm_invoicegrouprelation ADD CONSTRAINT fk_invoicegrouprelation2 FOREIGN KEY (groupname) REFERENCES aicrm_groups(groupname) ON DELETE CASCADE"
			    );
foreach($alter_query_array13 as $query)
{
	Execute($query);
}

$create_query14 = "CREATE TABLE aicrm_pogrouprelation ( purchaseorderid int(19) NOT NULL default '0', groupname varchar(100) default NULL, PRIMARY KEY  (`purchaseorderid`))";
Execute($create_query14);

$alter_query_array14 = Array(
				"alter table aicrm_pogrouprelation ADD CONSTRAINT fk_pogrouprelation FOREIGN KEY (purchaseorderid) REFERENCES aicrm_purchaseorder(purchaseorderid) ON DELETE CASCADE",
				"alter table aicrm_pogrouprelation ADD CONSTRAINT fk_productgrouprelation2 FOREIGN KEY (groupname) REFERENCES aicrm_groups(groupname) ON DELETE CASCADE"
			    );
foreach($alter_query_array14 as $query)
{
	Execute($query);
}

$alter_query1 = "ALTER TABLE aicrm_users ADD column lead_view VARCHAR(25) DEFAULT 'Today' AFTER homeorder";
Execute($alter_query1);

$update_query1 = "update aicrm_users set homeorder = 'ALVT,PLVT,QLTQ,CVLVT,HLT,OLV,GRT,OLTSO,ILTI,MNL'";
Execute($update_query1);

$alter_query2 = "ALTER TABLE aicrm_products change column imagename imagename text";
Execute($alter_query2);

$alter_query3 = "alter table aicrm_systems modify server varchar(50), modify server_username varchar(50), modify server_password varchar(50), add column smtp_auth char(5)";
Execute($alter_query3);

$alter_query_array15 = Array( 
				"alter table aicrm_users add column imagename varchar(250)",
				"alter table aicrm_users add column tagcloud varchar(250)"
			    );
foreach($alter_query_array15 as $query)
{
	Execute($query);
}

$alter_query_array16 = Array(
			"alter table aicrm_systems change column server server varchar(80) default NULL",
			"alter table aicrm_systems change column server_username server_username varchar(80) default NULL"
			    );
foreach($alter_query_array16 as $query)
{
	Execute($query);
}


$create_query15 = "create table aicrm_portal(portalid int(19), portalname varchar(255) NOT NULL, portalurl varchar(255) NOT NULL,sequence int(3) NOT NULL, PRIMARY KEY (portalid))";
Execute($create_query15);

$alter_query = "ALTER TABLE aicrm_field ADD column info_type varchar(20) default NULL after quickcreatesequence";
Execute($alter_query);

//$update_query2 = "UPDATE aicrm_field SET fieldlabel = 'Reference' WHERE tabid = 4 and tablename = 'contactdetails' and fieldname='reference'";
//changed in 24-04-06 because the reference has not been entered into the aicrm_field table. 
$newfieldid = $conn->getUniqueID("aicrm_field");
$update_query2 = "insert into aicrm_field values (4,".$newfieldid.",'reference','contactdetails',1,'56','reference','Reference',1,0,0,10,23,4,1,'C~O',1,null,'ADV')";
Execute($update_query2);
populateFieldForSecurity('4',$newfieldid);

$update_query_array4 = Array(
				"UPDATE aicrm_field SET info_type = 'BAS'",

				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 7 and fieldlabel = 'Website'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 7 and fieldlabel = 'Industry'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 7 and fieldlabel = 'Annual Revenue'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 7 and fieldlabel = 'No Of Employees'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 7 and fieldlabel = 'Yahoo Id'",

				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Ticker Symbol'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Other Phone'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Member Of'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Employees'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Other Email'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Ownership'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Rating'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'industry'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'SIC Code'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Type'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Annual Revenue'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 6 and fieldlabel = 'Email Opt Out'",

				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Home Phone'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Department'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Birthdate'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Email'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Reports To'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Assistant'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Yahoo Id'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Assistant Phone'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Do Not Call'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Email Opt Out'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Reference'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Portal User'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Support Start Date'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Support End Date'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 4 and fieldlabel = 'Contact Image'",

				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Usage Unit'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Qty/Unit'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Qty In Stock'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Reorder Level'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Handler'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Qty In Demand'",
				"UPDATE aicrm_field SET info_type = 'ADV' WHERE tabid = 14 and fieldlabel = 'Product Image'"
			    );
foreach($update_query_array4 as $query)
{
	Execute($query);
}


$create_query16 = "CREATE TABLE aicrm_chat_msg ( `id` bigint(20) NOT NULL auto_increment, `chat_from` bigint(20) NOT NULL default '0', `chat_to` bigint(20) NOT NULL default '0', `born` timestamp NULL default '0000-00-00 00:00:00', `msg` varchar(255) NOT NULL, PRIMARY KEY  (`id`), KEY `chat_to` (`chat_to`), KEY `chat_from` (`chat_from`), KEY `born` (`born`)) ENGINE=InnoDB";
Execute($create_query16);
$create_query17 = "CREATE TABLE aicrm_chat_pchat ( `id` bigint(20) NOT NULL auto_increment, `msg` bigint(20) NOT NULL, PRIMARY KEY  (`id`), UNIQUE KEY `msg` (`msg`)) ENGINE=InnoDB";
Execute($create_query17);

$create_query18 = "CREATE TABLE aicrm_chat_pvchat ( `id` bigint(20) NOT NULL auto_increment, `msg` bigint(20) NOT NULL, PRIMARY KEY  (`id`), UNIQUE KEY `msg` (`msg`)) ENGINE=InnoDB";
Execute($create_query18);

$create_query19 = "CREATE TABLE aicrm_chat_users ( `id` bigint(20) NOT NULL auto_increment, `nick` varchar(50) NOT NULL, `session` varchar(50) NOT NULL, `ip` varchar(20) NOT NULL default '000.000.000.000', `ping` timestamp NULL default '0000-00-00 00:00:00', PRIMARY KEY  (`id`), UNIQUE KEY `session` (`session`), UNIQUE KEY `nick` (`nick`), KEY `ping` (`ping`)) ENGINE=InnoDB";
Execute($create_query19);

$alter_query_array17 = Array(
				"ALTER TABLE `aicrm_chat_msg`  ADD CONSTRAINT `chat_msg_ibfk_1` FOREIGN KEY (`chat_from`) REFERENCES `aicrm_chat_users` (`id`) ON DELETE CASCADE",

				"ALTER TABLE `aicrm_chat_pchat`  ADD CONSTRAINT `chat_pchat_ibfk_1` FOREIGN KEY (`msg`) REFERENCES `aicrm_chat_msg` (`id`) ON DELETE CASCADE",

				"ALTER TABLE `aicrm_chat_pvchat`  ADD CONSTRAINT `chat_pvchat_ibfk_1` FOREIGN KEY (`msg`) REFERENCES `aicrm_chat_msg` (`id`) ON DELETE CASCADE"
			    );
foreach($alter_query_array17 as $query)
{
	Execute($query);
}

$create_query20 = "CREATE TABLE aicrm_freetags ( id int(19) NOT NULL, tag varchar(50) NOT NULL default '', raw_tag varchar(50) NOT NULL default '', PRIMARY KEY  (id)) TYPE=InnoDB";
Execute($create_query20);

$create_query21 = "CREATE TABLE aicrm_freetagged_objects ( tag_id int(20) NOT NULL default '0', tagger_id int(20) NOT NULL default '0', object_id int(20) NOT NULL default '0', tagged_on timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP, module varchar(50) NOT NULL default '', PRIMARY KEY  (`tag_id`,`tagger_id`,`object_id`), KEY `freetagged_objects_tag_id_tagger_id_object_id_idx` (`tag_id`,`tagger_id`,`object_id`)) TYPE=InnoDB";
Execute($create_query21);

$alter_query4 = "alter table aicrm_profile add column description text";
Execute($alter_query4);

$alter_query5 = "alter table aicrm_contactdetails add column imagename varchar(250) after currency";
Execute($alter_query5);

$alter_query = "ALTER TABLE aicrm_contactdetails ADD column reference varchar(3) default NULL after imagename";
Execute($alter_query);

Execute("insert into aicrm_blocks values(75,4,'LBL_IMAGE_INFORMATION',5,0,0,0,0,0)");

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (4,".$newfieldid.",'imagename','contactdetails',1,'69','imagename','Contact Image',1,0,0,100,1,75,1,'V~O',1,null,'ADV')";
Execute($insert_query);
populateFieldForSecurity('4',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (9,".$newfieldid.",'visibility','activity',1,15,'visibility','Visibility',1,0,0,100,17,19,3,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('9',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (16,".$newfieldid.",'visibility','activity',1,15,'visibility','Visibility',1,0,0,100,19,41,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('16',$newfieldid);


$alter_query6 = "ALTER TABLE aicrm_activity ADD COLUMN visibility varchar(50) NOT NULL DEFAULT 'all' after notime";
Execute($alter_query6);

$create_query22 = "CREATE TABLE aicrm_visibility ( `visibilityid` int(19) NOT NULL auto_increment, `visibility` varchar(200) NOT NULL default '', `sortorderid` int(19) NOT NULL default '0', `presence` int(1) NOT NULL default '1', PRIMARY KEY  (`visibilityid`), UNIQUE KEY `Visibility_VLY` (`visibility`)) ENGINE=InnoDB";
Execute($create_query22);


$create_query23 = "CREATE TABLE aicrm_sharedcalendar ( `userid` int(19) NOT NULL default '0',  `sharedid` int(19) NOT NULL default '0', PRIMARY KEY  (`userid`,`sharedid`)) ENGINE=InnoDB";
Execute($create_query23);

$insert_query6 = "INSERT INTO aicrm_tab VALUES(26,'Campaigns',0,23,'Campaigns',null,null,1)";
Execute($insert_query6);
$insert_query7 = "INSERT INTO aicrm_parenttabrel VALUES(2,26,1)";
Execute($insert_query7);

$insert_query8 = "insert into aicrm_blocks values(76,26,'LBL_CAMPAIGN_INFORMATION',1,0,0,0,0,0)";
Execute($insert_query8);
$insert_query8 = "insert into aicrm_blocks values (77,26,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)";
Execute($insert_query8);
$insert_query9 = "insert into aicrm_blocks values(78,26,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0)";
Execute($insert_query9);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'campaignname','campaign',1,'2','campaignname','Campaign Name',1,0,0,100,1,76,1,'V~M',0,1,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'campaigntype','campaign',1,15,'campaigntype','Campaign Type',1,0,0,100,2,76,1,'V~O',0,5,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid,'product_id','campaign',1,59,'product_id','Product',1,0,0,100,3,76,1,'I~O',0,5,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid,'campaignstatus','campaign',1,15,'campaignstatus','Campaign Status',1,0,0,100,4,76,1,'V~O',0,5,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'closingdate','campaign',1,'23','closingdate','Expected Close Date',1,0,0,100,5,76,1,'D~M',0,3,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);


$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'expectedrevenue','campaign',1,'1','expectedrevenue','Expected Revenue',1,0,0,100,6,76,1,'I~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'budgetcost','campaign',1,'1','budgetcost','Budget Cost',1,0,0,100,7,76,1,'I~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'actualcost','campaign',1,'1','actualcost','Actual Cost',1,0,0,100,8,76,1,'I~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'expectedresponse','campaign',1,'15','expectedresponse','Expected Response',1,0,0,100,9,76,1,'V~O',0,4,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,10,76,1,'V~M',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'numsent','campaign',1,'9','numsent','Num Sent',1,0,0,100,11,76,1,'N~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'sponsor','campaign',1,'1','sponsor','Sponsor',1,0,0,100,12,76,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'targetaudience','campaign',1,'1','targetaudience','Target Audience',1,0,0,100,13,76,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'targetsize','campaign',1,'1','targetsize','TargetSize',1,0,0,100,14,76,1,'N~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'expectedresponsecount','campaign',1,'1','expectedresponsecount','Expected Response Count',1,0,0,100,17,76,1,'N~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'expectedsalescount','campaign',1,'1','expectedsalescount','Expected Sales Count',1,0,0,100,15,76,1,'N~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'expectedroi','campaign',1,'1','expectedroi','Expected ROI',1,0,0,100,19,76,1,'N~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'actualresponsecount','campaign',1,'1','actualresponsecount','Actual Response Count',1,0,0,100,18,76,1,'N~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'actualsalescount','campaign',1,'1','actualsalescount','Actual Sales Count',1,0,0,100,16,76,1,'N~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'actualroi','campaign',1,'1','actualroi','Actual ROI',1,0,0,100,20,76,1,'N~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,15,76,2,'T~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,16,76,2,'T~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (26, $newfieldid, 'description','crmentity',1,'19','description','Description',1,0,0,100,1,82,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('26',$newfieldid);

/*
//add all field entries to def_org_field and profile2field tables for Campaigns
$field_res = $conn->query("select fieldid from aicrm_field where tabid=26");
for($i=0;$i<$conn->num_rows($field_res);$i++)
{
	$fieldid = $conn->query_result($field_res,$i,'fieldid');

	populateFieldForSecurity('26',$fieldid);
}
*/

$insert_query_array25 = Array(
	"insert into aicrm_relatedlists values (".$conn->getUniqueID('aicrm_relatedlists').",".getTabid("Campaigns").",".getTabid("Contacts").",'get_contacts',1,'Contacts',0)",
	"insert into aicrm_relatedlists values (".$conn->getUniqueID('aicrm_relatedlists').",".getTabid("Campaigns").",".getTabid("Leads").",'get_leads',2,'Leads',0)"
			     );
foreach($insert_query_array25 as $query)
{
	Execute($query);
}

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (7, $newfieldid, 'campaignid','leaddetails',1,'51','campaignid','Campaign Name',1,0,0,100,6,13,3,'I~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('7',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (4, $newfieldid, 'campaignid','contactdetails',1,'51','campaignid','Campaign Name',1,0,0,100,6,4,3,'I~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('4',$newfieldid);


$create_query24 = "
CREATE TABLE aicrm_campaign (
   `campaignname` varchar(255) default NULL,
   `campaigntype` varchar(255) default NULL,
   `campaignstatus` varchar(255) default NULL,
   `expectedrevenue` int(19) default NULL,
   `budgetcost` int(19) default NULL,
   `actualcost` int(19) default NULL,
   `expectedresponse` varchar(255) default NULL,
   `numsent` decimal(11,0) default NULL,
   `product_id` int(19) default NULL,
   `sponsor` varchar(255) default NULL,
   `targetaudience` varchar(255) default NULL,
   `targetsize` int(19) default NULL,
   `expectedresponsecount` int(19) default NULL,
   `expectedsalescount` int(19) default NULL,
   `expectedroi` int(19) default NULL,
   `actualresponsecount` int(19) default NULL,
   `actualsalescount` int(19) default NULL,
   `actualroi` int(19) default NULL,
   `campaignid` int(19) NOT NULL,
   `closingdate` date default NULL,
    PRIMARY KEY  (`campaignid`),
    KEY `idx_campaignstatus` (`campaignstatus`),
    KEY `idx_campaignname` (`campaignname`),
    KEY `idx_campaignid` (`campaignid`)
) ENGINE=InnoDB
		  ";
Execute($create_query24);


//Added on 06-06-06
$create_query25 = "CREATE TABLE aicrm_campaigncontrel (
	  `campaignid` int(19) NOT NULL default '0',
	    `contactid` int(19) NOT NULL default '0',
	      PRIMARY KEY  (`campaignid`),
	        KEY `campaigncontrel_contractid_idx` (`contactid`)
	) ENGINE=InnoDB";
/*
$create_query25 = "CREATE TABLE aicrm_campaigncontrel (
  `campaignid` int(19) NOT NULL default '0',
  `contactid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`campaignid`),
  KEY `CampaignContRel_IDX1` (`contactid`),
  CONSTRAINT `fk_CampaignContRel2` FOREIGN KEY (`contactid`) REFERENCES `aicrm_contactdetails` (`contactid`) ON DELETE CASCADE,
  CONSTRAINT `fk_CampaignContRel1` FOREIGN KEY (`campaignid`) REFERENCES `aicrm_campaign` (`campaignid`) ON DELETE CASCADE
) ENGINE=InnoDB";
*/
Execute($create_query25);

//Added on 06-06-06
$create_table_query = "CREATE TABLE aicrm_campaigngrouprelation (
	  `campaignid` int(19) NOT NULL,
	    `groupname` varchar(100) default NULL,
	      PRIMARY KEY  (`campaignid`),
	        KEY `campaigngrouprelation_IDX1` (`groupname`)
	) ENGINE=InnoDB";
/*
$create_table_query = "
CREATE TABLE aicrm_campaigngrouprelation (
       `campaignid` int(19) NOT NULL,
       `groupname` varchar(100) default NULL,
	PRIMARY KEY  (`campaignid`),
	KEY `campaigngrouprelation_IDX1` (`groupname`),
	CONSTRAINT `fk_campaigngrouprelation2` FOREIGN KEY (`groupname`) REFERENCES `aicrm_groups` (`groupname`) ON DELETE CASCADE,
	CONSTRAINT `fk_campaigngrouprelation1` FOREIGN KEY (`campaignid`) REFERENCES `aicrm_campaign` (`campaignid`) ON DELETE CASCADE
) ENGINE=InnoDB";
*/
Execute($create_table_query);


//Added on 06-06-06
$create_query26 = "CREATE TABLE aicrm_campaignleadrel (
			`campaignid` int(19) NOT NULL default '0',
			`leadid` int(19) NOT NULL default '0',
			PRIMARY KEY  (`campaignid`),
			KEY `campaignleadrel_leadid_campaignid_idx` (`leadid`,`campaignid`)
		   ) ENGINE=InnoDB";
/*
$create_query26 = "CREATE TABLE aicrm_campaignleadrel (
  `campaignid` int(19) NOT NULL default '0',
  `leadid` int(19) NOT NULL default '0',
  PRIMARY KEY  (`campaignid`),
  KEY `CampaignLeadRel_IDX1` (`leadid`,`campaignid`),
  CONSTRAINT `fk_CampaignLeadRel1234` FOREIGN KEY (`campaignid`) REFERENCES `aicrm_campaign` (`campaignid`) ON DELETE CASCADE,
  CONSTRAINT `fk_CampaignLeadRel2423` FOREIGN KEY (`leadid`) REFERENCES `aicrm_leaddetails` (`leadid`) ON DELETE CASCADE
) ENGINE=InnoDB";
*/
Execute($create_query26);

$create_table_query1 = "CREATE TABLE aicrm_campaignscf (
  `campaignid` int(19) NOT NULL default '0',
   PRIMARY KEY  (`campaignid`),
   CONSTRAINT `fk_CampaignsCF` FOREIGN KEY (`campaignid`) REFERENCES `aicrm_campaign` (`campaignid`) ON DELETE CASCADE
) ENGINE=InnoDB";
Execute($create_table_query1);

$alter_query_array18 = Array(
			"alter table aicrm_potential add column campaignid int(19) default NULL after probability",
			"alter table aicrm_potential drop column campaignsource",
			//"alter table aicrm_notes drop PRIMARY KEY contact_id",
			"alter table aicrm_notes drop PRIMARY KEY , add primary key(notesid)",
			"update aicrm_field set uitype=99 where fieldname='update_log' and tabid=13"
			    );
foreach($alter_query_array18 as $query)
{
	Execute($query);
}


//Added on 09-08-2006
//In the next array we have add constraint for tables purchaseorder, salesorder, quotes and invoice where as the corresponding entity ids should not be 0 they should be NULL. so that this change has been done
Execute("update aicrm_purchaseorder set contactid=NULL where contactid=0");
Execute("update aicrm_salesorder set contactid=NULL where contactid=0");
Execute("update aicrm_quotes set contactid=NULL where contactid=0");
Execute("update aicrm_quotes set potentialid=NULL where potentialid=0");
Execute("update aicrm_invoice set salesorderid=NULL where salesorderid=0");


//echo "<br><br><b>Database Modifications for Indexing and some missded tables starts here.....</b><br>";
//Added queries which are for indexing and the missing tables - Mickie - on 06-04-2006

$create_table_query_array = Array(


"CREATE TABLE aicrm_actualcost (
  `actualcostid` int(19) NOT NULL auto_increment,
  `actualcost` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`actualcostid`),
  UNIQUE KEY `CampaignActCst_UK01` (`actualcost`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_campaignstatus (
  `campaignstatusid` int(19) NOT NULL auto_increment,
  `campaignstatus` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`campaignstatusid`),
  KEY `Campaignstatus_UK01` (`campaignstatus`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_campaigntype (
  `campaigntypeid` int(19) NOT NULL auto_increment,
  `campaigntype` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`campaigntypeid`),
  UNIQUE KEY `Campaigntype_UK01` (`campaigntype`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_datashare_module_rel (
  `shareid` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  `relationtype` varchar(200) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `idx_datashare_module_rel_tabid` (`tabid`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_datashare_grp2grp (
	  `shareid` int(19) NOT NULL,
	    `share_groupid` int(19) default NULL,
	      `to_groupid` int(19) default NULL,
	        `permission` int(19) default NULL,
		  PRIMARY KEY  (`shareid`),
		    KEY `datashare_grp2grp_share_groupid_idx` (`share_groupid`),
		      KEY `datashare_grp2grp_to_groupid_idx` (`to_groupid`)
	      ) ENGINE=InnoDB",

"CREATE TABLE aicrm_datashare_grp2role (
	  `shareid` int(19) NOT NULL,
	    `share_groupid` int(19) default NULL,
	      `to_roleid` varchar(255) default NULL,
	        `permission` int(19) default NULL,
		  PRIMARY KEY  (`shareid`),
		    KEY `idx_datashare_grp2role_share_groupid` (`share_groupid`),
		      KEY `idx_datashare_grp2role_to_roleid` (`to_roleid`)
	      ) ENGINE=InnoDB",

"CREATE TABLE aicrm_datashare_grp2rs (
	  `shareid` int(19) NOT NULL,
	    `share_groupid` int(19) default NULL,
	      `to_roleandsubid` varchar(255) default NULL,
	        `permission` int(19) default NULL,
		  PRIMARY KEY  (`shareid`),
		    KEY `datashare_grp2rs_share_groupid_idx` (`share_groupid`),
		      KEY `datashare_grp2rs_to_roleandsubid_idx` (`to_roleandsubid`)
	      ) ENGINE=InnoDB",

"CREATE TABLE aicrm_datashare_relatedmodule_permission (
  `shareid` int(19) NOT NULL,
  `datashare_relatedmodule_id` int(19) NOT NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`,`datashare_relatedmodule_id`),
  KEY `datashare_relatedmodule_permission_UK1` (`shareid`,`permission`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_datashare_relatedmodules (
	  `datashare_relatedmodule_id` int(19) NOT NULL,
	    `tabid` int(19) default NULL,
	      `relatedto_tabid` int(19) default NULL,
	        PRIMARY KEY  (`datashare_relatedmodule_id`),
		  KEY `datashare_relatedmodules_tabid_idx` (`tabid`),
		    KEY `datashare_relatedmodules_relatedto_tabid_idx` (`relatedto_tabid`)
	    ) ENGINE=InnoDB",

"CREATE TABLE aicrm_datashare_relatedmodules_seq (
  `id` int(11) NOT NULL
) ENGINE=InnoDB",

"CREATE TABLE aicrm_datashare_role2group (
	  `shareid` int(19) NOT NULL,
	    `share_roleid` varchar(255) default NULL,
	      `to_groupid` int(19) default NULL,
	        `permission` int(19) default NULL,
		  PRIMARY KEY  (`shareid`),
		    KEY `idx_datashare_role2group_share_roleid` (`share_roleid`),
		      KEY `idx_datashare_role2group_to_groupid` (`to_groupid`)
	      ) ENGINE=InnoDB",

"CREATE TABLE aicrm_datashare_role2role (
	  `shareid` int(19) NOT NULL,
	    `share_roleid` varchar(255) default NULL,
	      `to_roleid` varchar(255) default NULL,
	        `permission` int(19) default NULL,
		  PRIMARY KEY  (`shareid`),
		    KEY `datashare_role2role_share_roleid_idx` (`share_roleid`),
		      KEY `datashare_role2role_to_roleid_idx` (`to_roleid`)
	      ) ENGINE=InnoDB",

"CREATE TABLE aicrm_datashare_role2rs (
	  `shareid` int(19) NOT NULL,
	    `share_roleid` varchar(255) default NULL,
	      `to_roleandsubid` varchar(255) default NULL,
	        `permission` int(19) default NULL,
		  PRIMARY KEY  (`shareid`),
		    KEY `datashare_role2s_share_roleid_idx` (`share_roleid`),
		      KEY `datashare_role2s_to_roleandsubid_idx` (`to_roleandsubid`)
	      ) ENGINE=InnoDB",

"CREATE TABLE aicrm_datashare_rs2grp (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) default NULL,
  `to_groupid` int(19) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `datashare_rs2grp_share_roleandsubid_idx` (`share_roleandsubid`),
  KEY `datashare_rs2grp_to_groupid_idx` (`to_groupid`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_datashare_rs2role (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) default NULL,
  `to_roleid` varchar(255) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `datashare_rs2role_share_roleandsubid_idx` (`share_roleandsubid`),
  KEY `datashare_rs2role_to_roleid_idx` (`to_roleid`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_datashare_rs2rs (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) default NULL,
  `to_roleandsubid` varchar(255) default NULL,
  `permission` int(19) default NULL,
  PRIMARY KEY  (`shareid`),
  KEY `datashare_rs2rs_share_roleandsubid_idx` (`share_roleandsubid`),
  KEY `idx_datashare_rs2rs_to_roleandsubid_idx` (`to_roleandsubid`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_expectedresponse (
  `expectedresponseid` int(19) NOT NULL auto_increment,
  `expectedresponse` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`expectedresponseid`),
  UNIQUE KEY `CampaignExpRes_UK01` (`expectedresponse`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_expectedrevenue (
  `expectedrevenueid` int(19) NOT NULL auto_increment,
  `expectedrevenue` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL default '0',
  `presence` int(1) NOT NULL default '1',
  PRIMARY KEY  (`expectedrevenueid`),
  UNIQUE KEY `CampaignExpRev_UK01` (`expectedrevenue`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_tmp_read_group_rel_sharing_per (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`relatedtabid`,`sharedgroupid`),
  KEY `tmp_read_group_rel_sharing_per_userid_sharedgroupid_tabid` (`userid`,`sharedgroupid`,`tabid`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_tmp_read_group_sharing_per (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`sharedgroupid`),
  KEY `tmp_read_group_sharing_per_userid_sharedgroupid_idx` (`userid`,`sharedgroupid`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_tmp_read_user_rel_sharing_per (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`relatedtabid`,`shareduserid`),
  KEY `tmp_read_user_rel_sharing_per_userid_shared_reltabid_idx` (`userid`,`shareduserid`,`relatedtabid`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_tmp_read_user_sharing_per (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`shareduserid`),
  KEY `tmp_read_user_sharing_per_userid_shareduserid_idx` (`userid`,`shareduserid`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_tmp_write_group_rel_sharing_per (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`relatedtabid`,`sharedgroupid`),
  KEY `tmp_write_group_rel_sharing_per_userid_sharedgroupid_tabid_idx` (`userid`,`sharedgroupid`,`tabid`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_tmp_write_group_sharing_per (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`sharedgroupid`),
  KEY `tmp_write_group_sharing_per_UK1` (`userid`,`sharedgroupid`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_tmp_write_user_rel_sharing_per (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`relatedtabid`,`shareduserid`),
  KEY `tmp_write_user_rel_sharing_per_userid_sharduserid_tabid_idx` (`userid`,`shareduserid`,`tabid`)
) ENGINE=InnoDB",

"CREATE TABLE aicrm_tmp_write_user_sharing_per (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`tabid`,`shareduserid`),
  KEY `tmp_write_user_sharing_per_userid_shareduserid_idx` (`userid`,`shareduserid`)
) ENGINE=InnoDB",


				 );

foreach($create_table_query_array as $query)
{
	Execute($query);
}

$query_array = Array(

//"ALTER TABLE `aicrm_activity` DROP INDEX `status`",
//"ALTER TABLE `aicrm_contactgrouprelation` DROP INDEX `fk_contactgrouprelation2`",
//"ALTER TABLE `aicrm_customview` DROP INDEX `customview`",
//"ALTER TABLE `aicrm_def_org_field` DROP INDEX `tabid`",
//"ALTER TABLE `aicrm_field` DROP INDEX `tabid`",
//"ALTER TABLE `aicrm_groups` DROP INDEX `groupname`",
//"ALTER TABLE `aicrm_invoicegrouprelation` DROP INDEX `fk_invoicegrouprelation2`",
//"ALTER TABLE `aicrm_pogrouprelation` DROP INDEX `fk_productgrouprelation2`",
//"ALTER TABLE `aicrm_potential` DROP INDEX `potentialid`",
//"ALTER TABLE `aicrm_potentialgrouprelation` DROP INDEX `fk_potentialgrouprelation2`",
//"ALTER TABLE `aicrm_profile2field` DROP INDEX `tabid`",
"ALTER TABLE `aicrm_profile2tab` DROP INDEX `idx_profile2tab`",
"ALTER TABLE `aicrm_profile2tab` ADD KEY `profile2tab_profileid_tabid_idx` (`profileid`, `tabid`)",
//"ALTER TABLE `aicrm_quotegrouprelation` DROP INDEX `fk_quotegrouprelation2`",
"ALTER TABLE `aicrm_reportmodules` DROP INDEX `reportmodules_IDX0`",
"ALTER TABLE `aicrm_reportmodules` MODIFY COLUMN `reportmodulesid` INTEGER(19) NOT NULL",

"ALTER TABLE `aicrm_reportsortcol` DROP INDEX `reportsortcol_IDX0`",
"ALTER TABLE `aicrm_reportsortcol` ADD KEY `fk_1_aicrm_reportsortcol` (`reportid`)",
"ALTER TABLE `aicrm_reportsortcol` MODIFY COLUMN `sortcolid` INTEGER(19) NOT NULL",
"ALTER TABLE `aicrm_reportsortcol` MODIFY COLUMN `reportid` INTEGER(19) NOT NULL",

"ALTER TABLE `aicrm_reportsummary` DROP INDEX `reportsummary_IDX0`",
"ALTER TABLE `aicrm_reportsummary` ADD KEY `reportsummary_reportsummaryid_idx` (`reportsummaryid`)",
"ALTER TABLE `aicrm_reportsummary` MODIFY COLUMN `reportsummaryid` INTEGER(19) NOT NULL",
"ALTER TABLE `aicrm_reportsummary` MODIFY COLUMN `summarytype` INTEGER(19) NOT NULL",

//"ALTER TABLE `aicrm_seattachmentsrel` DROP INDEX `attachmentsid`",
//"ALTER TABLE `aicrm_sogrouprelation` DROP INDEX `fk_sogrouprelation2`",
//"ALTER TABLE `aicrm_tab` DROP INDEX `tabid`",
//"ALTER TABLE `aicrm_troubletickets` DROP INDEX `status`",
"ALTER TABLE `aicrm_activity_reminder` TYPE=InnoDB",
"ALTER TABLE `aicrm_activsubtype` TYPE=InnoDB",
"ALTER TABLE `aicrm_contactgrouprelation` TYPE=InnoDB",
//"ALTER TABLE `aicrm_customerdetails` TYPE=InnoDB",
"ALTER TABLE `aicrm_customview_seq` TYPE=InnoDB",
"ALTER TABLE `aicrm_def_org_field` TYPE=InnoDB",
"ALTER TABLE `aicrm_def_org_share` TYPE=InnoDB",
"ALTER TABLE `aicrm_def_org_share_seq` TYPE=InnoDB",
//"ALTER TABLE `aicrm_defaultcv` TYPE=InnoDB",
"ALTER TABLE `aicrm_durationhrs` TYPE=InnoDB",
"ALTER TABLE `aicrm_durationmins` TYPE=InnoDB",
"ALTER TABLE `aicrm_emailtemplates` TYPE=InnoDB",
//"ALTER TABLE `aicrm_emailtemplates_seq` TYPE=InnoDB",
"ALTER TABLE `aicrm_faqcategories` TYPE=InnoDB",
"ALTER TABLE `aicrm_faqstatus` TYPE=InnoDB",
"ALTER TABLE `aicrm_field_seq` TYPE=InnoDB",
"ALTER TABLE `aicrm_files` TYPE=InnoDB",
"ALTER TABLE `aicrm_group2grouprel` TYPE=InnoDB",
"ALTER TABLE `aicrm_group2role` TYPE=InnoDB",
"ALTER TABLE `aicrm_group2rs` TYPE=InnoDB",
//"DROP TABLE `groups_seq`",
"ALTER TABLE `aicrm_headers` TYPE=InnoDB",
"ALTER TABLE `aicrm_import_maps` TYPE=InnoDB",
"ALTER TABLE `aicrm_inventorynotification_seq` TYPE=InnoDB",
"ALTER TABLE `aicrm_invoicegrouprelation` TYPE=InnoDB",
"ALTER TABLE `aicrm_loginhistory` TYPE=InnoDB",
"ALTER TABLE `aicrm_mail_accounts` TYPE=InnoDB",
"ALTER TABLE `aicrm_notificationscheduler_seq` TYPE=InnoDB",
"ALTER TABLE `aicrm_ownernotify` TYPE=InnoDB",
"ALTER TABLE `aicrm_parenttabrel` TYPE=InnoDB",
"ALTER TABLE `aicrm_pogrouprelation` TYPE=InnoDB",
"ALTER TABLE `aicrm_portal` TYPE=InnoDB",
"ALTER TABLE `aicrm_portalinfo` TYPE=InnoDB",
"ALTER TABLE `aicrm_potentialgrouprelation` TYPE=InnoDB",
"ALTER TABLE `aicrm_profile2field` TYPE=InnoDB",
"ALTER TABLE `aicrm_reportmodules` TYPE=InnoDB",
"ALTER TABLE `aicrm_profile2globalpermissions` TYPE=InnoDB",
"ALTER TABLE `aicrm_profile2standardpermissions` TYPE=InnoDB",
"ALTER TABLE `aicrm_profile2tab` TYPE=InnoDB",
"ALTER TABLE `aicrm_profile2utility` TYPE=InnoDB",
"ALTER TABLE `aicrm_profile_seq` TYPE=InnoDB",
"ALTER TABLE `aicrm_quotegrouprelation` TYPE=InnoDB",
"ALTER TABLE `aicrm_rating` TYPE=InnoDB",
"ALTER TABLE `aicrm_relatedlists` TYPE=InnoDB",
"ALTER TABLE `aicrm_relatedlists_seq` TYPE=InnoDB",
"ALTER TABLE `aicrm_role2profile` TYPE=InnoDB",
"ALTER TABLE `aicrm_role_seq` TYPE=InnoDB",
"ALTER TABLE `aicrm_rss` TYPE=InnoDB",
"ALTER TABLE `aicrm_sales_stage` TYPE=InnoDB",
"ALTER TABLE `aicrm_salutationtype` TYPE=InnoDB",
"ALTER TABLE `aicrm_selectquery_seq` TYPE=InnoDB",
"ALTER TABLE `aicrm_sogrouprelation` TYPE=InnoDB",
"ALTER TABLE `aicrm_systems` TYPE=InnoDB",
"ALTER TABLE `aicrm_taskpriority` TYPE=InnoDB",
"ALTER TABLE `aicrm_taskstatus` TYPE=InnoDB",
"ALTER TABLE `aicrm_ticketcategories` TYPE=InnoDB",
"ALTER TABLE `aicrm_ticketpriorities` TYPE=InnoDB",
"ALTER TABLE `aicrm_ticketseverities` TYPE=InnoDB",
"ALTER TABLE `aicrm_ticketstatus` TYPE=InnoDB",
"ALTER TABLE `aicrm_ticketstracktime` TYPE=InnoDB",
"ALTER TABLE `aicrm_tracker` TYPE=InnoDB",
"ALTER TABLE `aicrm_users2group` TYPE=InnoDB",
"ALTER TABLE `aicrm_users_last_import` TYPE=InnoDB",
"ALTER TABLE `aicrm_users_seq` TYPE=InnoDB",
//"ALTER TABLE `aicrm_wordtemplates` TYPE=InnoDB",

//Create table queries are moved from here to above this array

"ALTER TABLE `aicrm_account` MODIFY COLUMN `website` VARCHAR(100) DEFAULT NULL",
//"ALTER TABLE `aicrm_activity` MODIFY COLUMN `date_start` DATE NOT NULL UNIQUE",
"ALTER TABLE `aicrm_activity` MODIFY COLUMN `sendnotification` VARCHAR(3) NOT NULL DEFAULT '0'",
"ALTER TABLE `aicrm_activity` MODIFY COLUMN `duration_hours` VARCHAR(2) DEFAULT NULL",
"ALTER TABLE `aicrm_activity` MODIFY COLUMN `duration_minutes` VARCHAR(2) DEFAULT NULL",
"ALTER TABLE `aicrm_activity_reminder` MODIFY COLUMN `reminder_time` INTEGER(11) NOT NULL",
"ALTER TABLE `aicrm_activity_reminder` MODIFY COLUMN `reminder_sent` INTEGER(2) NOT NULL",
//"ALTER TABLE `aicrm_blocks` MODIFY COLUMN `tabid` INTEGER(19) NOT NULL UNIQUE",
"ALTER TABLE `aicrm_blocks` MODIFY COLUMN `sequence` INTEGER(10) DEFAULT NULL",
"ALTER TABLE `aicrm_blocks` MODIFY COLUMN `show_title` INTEGER(2) DEFAULT NULL",

//HANDLE HERE - MICKIE - Check the following queries

"ALTER TABLE `aicrm_contactdetails` MODIFY COLUMN `donotcall` VARCHAR(3) DEFAULT NULL",
"ALTER TABLE `aicrm_contactdetails` MODIFY COLUMN `emailoptout` VARCHAR(3) DEFAULT '0'",
"ALTER TABLE `aicrm_contactdetails` MODIFY COLUMN `imagename` VARCHAR(150) DEFAULT NULL",
"ALTER TABLE `aicrm_contactdetails` MODIFY COLUMN `reference` VARCHAR(3) DEFAULT NULL",
//"ALTER TABLE `aicrm_contactgrouprelation` MODIFY COLUMN `contactid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_convertleadmapping` MODIFY COLUMN `leadfid` INTEGER(19) NOT NULL",
//"ALTER TABLE `aicrm_crmentity` MODIFY COLUMN `crmid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_crmentity` MODIFY COLUMN `description` TEXT",
"ALTER TABLE `aicrm_crmentity` MODIFY COLUMN `createdtime` DATETIME NOT NULL",
"ALTER TABLE `aicrm_crmentity` MODIFY COLUMN `modifiedtime` DATETIME NOT NULL",
"ALTER TABLE `aicrm_customaction` MODIFY COLUMN `cvid` INTEGER(19) NOT NULL UNIQUE",
"ALTER TABLE `aicrm_customaction` MODIFY COLUMN `content` TEXT",
//"ALTER TABLE `aicrm_customerdetails` MODIFY COLUMN `customerid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_customerdetails` MODIFY COLUMN `portal` VARCHAR(3) DEFAULT NULL",
//"ALTER TABLE `aicrm_customview` MODIFY COLUMN `cvid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_customview_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
//"ALTER TABLE `aicrm_cvadvfilter` MODIFY COLUMN `cvid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_cvadvfilter` MODIFY COLUMN `columnindex` INTEGER(11) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_cvcolumnlist` MODIFY COLUMN `cvid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_cvcolumnlist` MODIFY COLUMN `columnindex` INTEGER(11) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_cvstdfilter` MODIFY COLUMN `cvid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_dealintimation` MODIFY COLUMN `dealprobability` DECIMAL(3,2) NOT NULL DEFAULT '0.00'",
//"ALTER TABLE `aicrm_def_org_field` MODIFY COLUMN `fieldid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_def_org_share` MODIFY COLUMN `tabid` INTEGER(11) NOT NULL",
//"ALTER TABLE `aicrm_def_org_share` MODIFY COLUMN `permission` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `aicrm_def_org_share_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
//"ALTER TABLE `aicrm_defaultcv` MODIFY COLUMN `tabid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_defaultcv` MODIFY COLUMN `query` TEXT",
"ALTER TABLE `aicrm_emailtemplates` MODIFY COLUMN `description` TEXT",
"ALTER TABLE `aicrm_emailtemplates` MODIFY COLUMN `body` TEXT",
//"ALTER TABLE `aicrm_emailtemplates_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `aicrm_faq` MODIFY COLUMN `question` TEXT",
"ALTER TABLE `aicrm_faq` MODIFY COLUMN `answer` TEXT",
"ALTER TABLE `aicrm_faqcomments` MODIFY COLUMN `comments` TEXT",
"ALTER TABLE `aicrm_faqcomments` MODIFY COLUMN `createdtime` DATETIME NOT NULL",
//"ALTER TABLE `aicrm_field` MODIFY COLUMN `tabid` INTEGER(19) NOT NULL UNIQUE",
"ALTER TABLE `aicrm_field` MODIFY COLUMN `readonly` INTEGER(1) NOT NULL",
"ALTER TABLE `aicrm_field` MODIFY COLUMN `selected` INTEGER(1) NOT NULL",
//"ALTER TABLE `aicrm_field` MODIFY COLUMN `block` INTEGER(19) DEFAULT NULL UNIQUE",
//"ALTER TABLE `aicrm_field` MODIFY COLUMN `displaytype` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `aicrm_field` MODIFY COLUMN `quickcreate` INTEGER(10) NOT NULL DEFAULT '1'",
"ALTER TABLE `aicrm_field_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
//"ALTER TABLE `aicrm_group2grouprel` MODIFY COLUMN `groupid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_group2grouprel` MODIFY COLUMN `containsgroupid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_group2role` MODIFY COLUMN `groupid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_group2rs` MODIFY COLUMN `groupid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_groups` MODIFY COLUMN `groupid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_groups` MODIFY COLUMN `description` TEXT",
"ALTER TABLE `aicrm_import_maps` MODIFY COLUMN `is_published` VARCHAR(3) NOT NULL DEFAULT 'no'",
//"ALTER TABLE `aicrm_inventory_tandc` MODIFY COLUMN `id` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_inventory_tandc` MODIFY COLUMN `tandc` TEXT",
"ALTER TABLE `aicrm_inventory_tandc_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `aicrm_inventorynotification` MODIFY COLUMN `notificationbody` TEXT",
"ALTER TABLE `aicrm_inventorynotification_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
//"ALTER TABLE `aicrm_invoice` MODIFY COLUMN `salesorderid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `aicrm_invoice` MODIFY COLUMN `terms_conditions` TEXT",
//"ALTER TABLE `aicrm_invoicegrouprelation` MODIFY COLUMN `invoiceid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_lar` MODIFY COLUMN `createdon` DATE NOT NULL",
//"ALTER TABLE `aicrm_leaddetails` MODIFY COLUMN `leadid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_leaddetails` MODIFY COLUMN `comments` TEXT",
//"ALTER TABLE `aicrm_leadgrouprelation` MODIFY COLUMN `leadid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_mail_accounts` MODIFY COLUMN `account_id` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_mail_accounts` MODIFY COLUMN `user_id` INTEGER(11) NOT NULL",
"ALTER TABLE `aicrm_mail_accounts` ADD COLUMN `box_refresh` INTEGER(10) DEFAULT NULL",
"ALTER TABLE `aicrm_mail_accounts` ADD COLUMN `mails_per_page` INTEGER(10) DEFAULT NULL",
"ALTER TABLE `aicrm_mail_accounts` ADD COLUMN `ssltype` VARCHAR(50) DEFAULT NULL",
"ALTER TABLE `aicrm_mail_accounts` ADD COLUMN `sslmeth` VARCHAR(50) DEFAULT NULL",
"ALTER TABLE `aicrm_mail_accounts` ADD COLUMN `showbody` VARCHAR(10) DEFAULT NULL",
"ALTER TABLE `aicrm_notes` MODIFY COLUMN `contact_id` INTEGER(19) DEFAULT '0'",
"ALTER TABLE `aicrm_notes` MODIFY COLUMN `notecontent` TEXT",
"ALTER TABLE `aicrm_notificationscheduler` MODIFY COLUMN `notificationbody` TEXT",
"ALTER TABLE `aicrm_notificationscheduler_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `aicrm_opportunitystage` MODIFY COLUMN `probability` DECIMAL(3,2) DEFAULT '0.00'",
//"ALTER TABLE `aicrm_org_share_action2tab` MODIFY COLUMN `share_action_id` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_org_share_action2tab` MODIFY COLUMN `tabid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_org_share_action_mapping` MODIFY COLUMN `share_action_id` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_organizationdetails` MODIFY COLUMN `website` VARCHAR(100) DEFAULT NULL",
"ALTER TABLE `aicrm_organizationdetails` MODIFY COLUMN `logo` TEXT",
"ALTER TABLE `aicrm_ownernotify` MODIFY COLUMN `crmid` INTEGER(19) DEFAULT NULL UNIQUE",
//"ALTER TABLE `aicrm_parenttab` MODIFY COLUMN `parenttabid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_parenttab` MODIFY COLUMN `sequence` INTEGER(10) NOT NULL",
"ALTER TABLE `aicrm_parenttabrel` MODIFY COLUMN `parenttabid` INTEGER(3) NOT NULL",
//"ALTER TABLE `aicrm_parenttabrel` MODIFY COLUMN `tabid` INTEGER(3) NOT NULL UNIQUE",
"ALTER TABLE `aicrm_parenttabrel` MODIFY COLUMN `sequence` INTEGER(3) NOT NULL",
//"ALTER TABLE `aicrm_pogrouprelation` MODIFY COLUMN `purchaseorderid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_portal` MODIFY COLUMN `portalid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_portal` MODIFY COLUMN `portalname` VARCHAR(200) NOT NULL UNIQUE",
"ALTER TABLE `aicrm_portal` MODIFY COLUMN `sequence` INTEGER(3) NOT NULL",
//"ALTER TABLE `aicrm_portalinfo` MODIFY COLUMN `id` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_portalinfo` MODIFY COLUMN `last_login_time` DATETIME NOT NULL",
"ALTER TABLE `aicrm_portalinfo` MODIFY COLUMN `login_time` DATETIME NOT NULL",
"ALTER TABLE `aicrm_portalinfo` MODIFY COLUMN `logout_time` DATETIME NOT NULL",
//"ALTER TABLE `aicrm_potcompetitorrel` MODIFY COLUMN `potentialid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_potcompetitorrel` MODIFY COLUMN `competitorid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_potential` MODIFY COLUMN `amount` DECIMAL(10,2) DEFAULT '0.00'",
"ALTER TABLE `aicrm_potential` MODIFY COLUMN `description` TEXT",
//"ALTER TABLE `aicrm_potentialgrouprelation` MODIFY COLUMN `potentialid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_potstagehistory` MODIFY COLUMN `potentialid` INTEGER(19) NOT NULL",
"ALTER TABLE `aicrm_potstagehistory` MODIFY COLUMN `probability` DECIMAL(7,3) DEFAULT NULL",
"ALTER TABLE `aicrm_potstagehistory` MODIFY COLUMN `lastmodified` DATETIME default NULL",
"ALTER TABLE `aicrm_pricebook` MODIFY COLUMN `description` TEXT",
//"ALTER TABLE `aicrm_pricebookproductrel` MODIFY COLUMN `pricebookid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_pricebookproductrel` MODIFY COLUMN `productid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_productcollaterals` MODIFY COLUMN `productid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_productcollaterals` MODIFY COLUMN `description` TEXT",
//"ALTER TABLE `aicrm_products` MODIFY COLUMN `productid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_products` MODIFY COLUMN `product_description` TEXT",
"ALTER TABLE `aicrm_products` MODIFY COLUMN `commissionrate` DECIMAL(3,3) DEFAULT NULL",
//"ALTER TABLE `aicrm_profile2field` MODIFY COLUMN `profileid` INTEGER(11) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_profile2field` MODIFY COLUMN `fieldid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_profile2globalpermissions` MODIFY COLUMN `profileid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_profile2globalpermissions` MODIFY COLUMN `globalactionid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_profile2standardpermissions` MODIFY COLUMN `profileid` INTEGER(11) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_profile2standardpermissions` MODIFY COLUMN `tabid` INTEGER(10) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_profile2standardpermissions` MODIFY COLUMN `Operation` INTEGER(10) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_profile2utility` MODIFY COLUMN `profileid` INTEGER(11) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_profile2utility` MODIFY COLUMN `tabid` INTEGER(11) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_profile2utility` MODIFY COLUMN `activityid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_profile_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
//"ALTER TABLE `aicrm_purchaseorder` MODIFY COLUMN `quoteid` INTEGER(19) DEFAULT NULL UNIQUE",
//"ALTER TABLE `aicrm_purchaseorder` MODIFY COLUMN `vendorid` INTEGER(19) DEFAULT NULL UNIQUE",
//"ALTER TABLE `aicrm_purchaseorder` MODIFY COLUMN `contactid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `aicrm_purchaseorder` MODIFY COLUMN `terms_conditions` TEXT",
//"ALTER TABLE `aicrm_quotegrouprelation` MODIFY COLUMN `quoteid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_quotes` MODIFY COLUMN `potentialid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `aicrm_quotes` MODIFY COLUMN `quotestage` VARCHAR(200) DEFAULT NULL",
//"ALTER TABLE `aicrm_quotes` MODIFY COLUMN `contactid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `aicrm_quotes` MODIFY COLUMN `terms_conditions` TEXT",
"ALTER TABLE `aicrm_recurringevents` MODIFY COLUMN `activityid` INTEGER(19) NOT NULL",
//"ALTER TABLE `aicrm_relatedlists` MODIFY COLUMN `relation_id` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_relatedlists_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
//"ALTER TABLE `aicrm_relcriteria` MODIFY COLUMN `queryid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_relcriteria` MODIFY COLUMN `columnindex` INTEGER(11) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_report` MODIFY COLUMN `reportid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_report` MODIFY COLUMN `folderid` INTEGER(19) NOT NULL UNIQUE",
//"ALTER TABLE `aicrm_reportdatefilter` MODIFY COLUMN `datefilterid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_role2profile` MODIFY COLUMN `profileid` INTEGER(11) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_role_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
//"ALTER TABLE `aicrm_rss` MODIFY COLUMN `rssid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_salesorder` MODIFY COLUMN `contactid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `aicrm_salesorder` MODIFY COLUMN `vendorid` INTEGER(19) DEFAULT NULL UNIQUE",
"ALTER TABLE `aicrm_salesorder` MODIFY COLUMN `terms_conditions` TEXT",
//"ALTER TABLE `aicrm_seactivityrel` MODIFY COLUMN `crmid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_seactivityrel` MODIFY COLUMN `activityid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_selectcolumn` MODIFY COLUMN `queryid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_selectquery` MODIFY COLUMN `queryid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_selectquery_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
//"ALTER TABLE `aicrm_sharedcalendar` MODIFY COLUMN `userid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_sharedcalendar` MODIFY COLUMN `sharedid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_sogrouprelation` MODIFY COLUMN `salesorderid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_systems` MODIFY COLUMN `id` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_systems` MODIFY COLUMN `server` VARCHAR(30) DEFAULT NULL",
"ALTER TABLE `aicrm_systems` MODIFY COLUMN `server_username` VARCHAR(30) DEFAULT NULL",
"ALTER TABLE `aicrm_systems` MODIFY COLUMN `server_password` VARCHAR(30) DEFAULT NULL",
"ALTER TABLE `aicrm_ticketcomments` MODIFY COLUMN `comments` TEXT",
"ALTER TABLE `aicrm_ticketcomments` MODIFY COLUMN `createdtime` DATETIME NOT NULL",
//"ALTER TABLE `aicrm_ticketgrouprelation` MODIFY COLUMN `ticketid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_troubletickets` MODIFY COLUMN `ticketid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_troubletickets` MODIFY COLUMN `description` TEXT",
"ALTER TABLE `aicrm_troubletickets` MODIFY COLUMN `solution` TEXT",
"ALTER TABLE `aicrm_troubletickets` MODIFY COLUMN `update_log` TEXT",
//"ALTER TABLE `aicrm_user2role` MODIFY COLUMN `userid` INTEGER(11) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_user2role` MODIFY COLUMN `roleid` VARCHAR(255) NOT NULL UNIQUE",
"ALTER TABLE `aicrm_users` MODIFY COLUMN `is_admin` VARCHAR(3) DEFAULT '0'",
"ALTER TABLE `aicrm_users` MODIFY COLUMN `description` TEXT",
"ALTER TABLE `aicrm_users` MODIFY COLUMN `user_preferences` TEXT",
"ALTER TABLE `aicrm_users` MODIFY COLUMN `homeorder` VARCHAR(255) DEFAULT 'ALVT,PLVT,QLTQ,CVLVT,HLT,OLV,GRT,OLTSO,ILTI,MNL'",
"ALTER TABLE `aicrm_users` ADD COLUMN `currency_id` INTEGER(19) NOT NULL DEFAULT '1'",
"ALTER TABLE `aicrm_users` ADD COLUMN `defhomeview` VARCHAR(100) DEFAULT 'home_metrics'",
//"ALTER TABLE `aicrm_users2group` MODIFY COLUMN `groupid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_users2group` MODIFY COLUMN `userid` INTEGER(19) NOT NULL PRIMARY KEY",
"ALTER TABLE `aicrm_users_seq` MODIFY COLUMN `id` INTEGER(11) NOT NULL",
"ALTER TABLE `aicrm_vendor` MODIFY COLUMN `street` TEXT",
"ALTER TABLE `aicrm_vendor` MODIFY COLUMN `description` TEXT",
//"ALTER TABLE `aicrm_wordtemplates` MODIFY COLUMN `templateid` INTEGER(19) NOT NULL PRIMARY KEY",
//"ALTER TABLE `aicrm_wordtemplates` MODIFY COLUMN `description` TEXT",
"ALTER TABLE `aicrm_activity` ADD KEY `status1` (`status`, `eventstatus`)",
"ALTER TABLE `aicrm_attachments` ADD KEY `attachmentsid1` (`attachmentsid`)",
"ALTER TABLE `aicrm_blocks` ADD KEY `block_tabid` (`tabid`)",
"ALTER TABLE `aicrm_carrier` DROP INDEX `carrier_UK0`",
"ALTER TABLE `aicrm_carrier` ADD UNIQUE KEY `carrier_carrier_idx` (`carrier`)",
"ALTER TABLE `aicrm_contactgrouprelation` ADD KEY `contactgrouprelation_IDX1` (`groupname`)",
"ALTER TABLE `aicrm_def_org_field` ADD KEY `tabid4` (`tabid`)",
"ALTER TABLE `aicrm_def_org_share` ADD KEY `fk_def_org_share23` (`permission`)",
"ALTER TABLE `aicrm_field` ADD KEY `tabid2` (`tabid`)",
"ALTER TABLE `aicrm_field` ADD KEY `blockid` (`block`)",
"ALTER TABLE `aicrm_field` ADD KEY `displaytypeid` (`displaytype`)",
"ALTER TABLE `aicrm_group2grouprel` ADD KEY `fk_group2grouprel2` (`containsgroupid`)",
"ALTER TABLE `aicrm_group2role` ADD KEY `fk_group2role2` (`roleid`)",
"ALTER TABLE `aicrm_group2rs` ADD KEY `fk_group2rs2` (`roleandsubid`)",
"ALTER TABLE `aicrm_groups` ADD KEY `idx_groups_123group` (`groupname`)",
"ALTER TABLE `aicrm_invoice` ADD KEY `SoPo_IDX` (`invoiceid`)",
"ALTER TABLE `aicrm_invoice` ADD KEY `fk_Invoice2` (`salesorderid`)",
"ALTER TABLE `aicrm_invoicegrouprelation` ADD KEY `invoicegrouprelation_IDX1` (`groupname`, `invoiceid`)",
"ALTER TABLE `aicrm_leadgrouprelation` ADD KEY `leadgrouprelation_IDX0` (`leadid`)",
"ALTER TABLE `aicrm_moduleowners` ADD KEY `moduleowners_UK11` (`tabid`, `user_id`)",
//"ALTER TABLE `aicrm_org_share_action2tab` ADD KEY `fk_org_share_action2tab12345` (`tabid`)",
"ALTER TABLE `aicrm_ownernotify` ADD KEY `ownernotify_UK1` (`crmid`, `flag`)",
"ALTER TABLE `aicrm_parenttab` ADD KEY `parenttab_UK1` (`parenttabid`, `parenttab_label`, `visible`)",
"ALTER TABLE `aicrm_parenttabrel` ADD KEY `parenttabrelUK01` (`tabid`, `parenttabid`)",
"ALTER TABLE `aicrm_pogrouprelation` ADD KEY `pogrouprelation_IDX1` (`groupname`, `purchaseorderid`)",
"ALTER TABLE `aicrm_portal` ADD KEY `portal_UK01` (`portalname`)",
"ALTER TABLE `aicrm_potential` ADD KEY `potentialid1` (`potentialid`)",
"ALTER TABLE `aicrm_potentialgrouprelation` ADD KEY `potentialgrouprelation_IDX1` (`groupname`)",
"ALTER TABLE `aicrm_potstagehistory` DROP INDEX PotStageHistory_IDX1",
"ALTER TABLE `aicrm_potstagehistory` ADD INDEX `PotStageHistory_IDX1` (`historyid`)",
"ALTER TABLE `aicrm_potstagehistory` ADD KEY `fk_PotStageHistory` (`potentialid`)",
"ALTER TABLE `aicrm_profile2field` ADD KEY `tabid3` (`tabid`, `profileid`)",
//"ALTER TABLE `aicrm_profile2globalpermissions` ADD KEY `idx_profile2globalpermissions` (`profileid`, `globalactionid`)",
"ALTER TABLE `aicrm_profile2standardpermissions` ADD KEY `idx_prof2stad` (`profileid`, `tabid`, `Operation`)",
"ALTER TABLE `aicrm_profile2utility` ADD KEY `idx_prof2utility` (`profileid`, `tabid`, `activityid`)",
"ALTER TABLE `aicrm_purchaseorder` ADD KEY `PO_Vend_IDX` (`vendorid`)",
"ALTER TABLE `aicrm_purchaseorder` ADD KEY `PO_Quote_IDX` (`quoteid`)",
"ALTER TABLE `aicrm_purchaseorder` ADD KEY `PO_Contact_IDX` (`contactid`)",
"ALTER TABLE `aicrm_quotegrouprelation` ADD KEY `quotegrouprelation_IDX1` (`groupname`)",
//"ALTER TABLE `aicrm_quotes` DROP INDEX aicrm_quotestage",
"ALTER TABLE `aicrm_quotes` ADD INDEX `quotestage` (`quoteid`)",
"ALTER TABLE `aicrm_quotes` ADD KEY `potentialid2` (`potentialid`)",
"ALTER TABLE `aicrm_quotes` ADD KEY `contactid` (`contactid`)",
"ALTER TABLE `aicrm_recurringtype` ADD UNIQUE KEY `RecurringEvent_UK0` (`recurringtype`)",
"ALTER TABLE `aicrm_role2profile` ADD KEY `idx_role2profileid1` (`roleid`, `profileid`)",
"ALTER TABLE `aicrm_salesorder` ADD KEY `SoVend_IDX` (`vendorid`)",
"ALTER TABLE `aicrm_salesorder` ADD KEY `SoContact_IDX` (`contactid`)",
"ALTER TABLE `aicrm_seattachmentsrel` ADD KEY `attachmentsid2` (`attachmentsid`, `crmid`)",
"ALTER TABLE `aicrm_selectquery` ADD KEY `selectquery_IDX0` (`queryid`)",
"ALTER TABLE `aicrm_sogrouprelation` ADD KEY `sogrouprelation_IDX1` (`groupname`)",
"ALTER TABLE `aicrm_tab` ADD KEY `tabid1` (`tabid`)",
"ALTER TABLE `aicrm_taxclass` ADD UNIQUE KEY `taxclass_carrier_idx` (`taxclass`)",
"ALTER TABLE `aicrm_troubletickets` ADD KEY `status2` (`status`)",
"ALTER TABLE `aicrm_users2group` ADD KEY `idx_users2group` (`groupid`, `userid`)",
"ALTER TABLE `aicrm_users2group` ADD KEY `fk_users2group2` (`userid`)",
"ALTER TABLE `aicrm_customaction` ADD CONSTRAINT `customaction_FK1` FOREIGN KEY (`cvid`) REFERENCES `aicrm_customview` (`cvid`) ON DELETE CASCADE",
"ALTER TABLE `aicrm_profile2globalpermissions` ADD CONSTRAINT `fk_profile2globalpermissions57` FOREIGN KEY (`profileid`) REFERENCES `aicrm_profile` (`profileid`) ON DELETE CASCADE",
"ALTER TABLE `aicrm_invoice` ADD CONSTRAINT `fk_Invoice2` FOREIGN KEY (`salesorderid`) REFERENCES `aicrm_salesorder` (`salesorderid`) ON DELETE CASCADE",
"ALTER TABLE `aicrm_purchaseorder` ADD CONSTRAINT `fk_PO3` FOREIGN KEY (`contactid`) REFERENCES `aicrm_contactdetails` (`contactid`) ON DELETE CASCADE",
"ALTER TABLE `aicrm_purchaseorder` ADD CONSTRAINT `fk_PO2` FOREIGN KEY (`vendorid`) REFERENCES `aicrm_vendor` (`vendorid`) ON DELETE CASCADE",
"ALTER TABLE `aicrm_purchaseorder` ADD CONSTRAINT `fk_PO2345` FOREIGN KEY (`quoteid`) REFERENCES `aicrm_quotes` (`quoteid`) ON DELETE CASCADE",
"ALTER TABLE `aicrm_quotes` ADD CONSTRAINT `fk_Quotes3` FOREIGN KEY (`contactid`) REFERENCES `aicrm_contactdetails` (`contactid`) ON DELETE CASCADE",
"ALTER TABLE `aicrm_quotes` ADD CONSTRAINT `fk_Quotes2` FOREIGN KEY (`potentialid`) REFERENCES `aicrm_potential` (`potentialid`) ON DELETE CASCADE",
"ALTER TABLE `aicrm_salesorder` ADD CONSTRAINT `fk_SO4` FOREIGN KEY (`contactid`) REFERENCES `aicrm_contactdetails` (`contactid`) ON DELETE CASCADE",
"ALTER TABLE `aicrm_salesorder` ADD CONSTRAINT `fk_SO2` FOREIGN KEY (`vendorid`) REFERENCES `aicrm_vendor` (`vendorid`) ON DELETE CASCADE",
"ALTER TABLE `aicrm_vendorcontactrel` ADD CONSTRAINT `fk_VendorContactRel45` FOREIGN KEY (`vendorid`) REFERENCES `aicrm_vendor` (`vendorid`) ON DELETE CASCADE"
		    );
foreach($query_array as $query)
{
	//Execute($query);
	//These above array queries will not make any problems if failed to execute. whereas the user get confused if it fails. so we are not goiong to display these queries if it is executed successfully or not
	$conn->query($query);
}

//First check whether this table is exist and the proceed
$currency_columns = $conn->getColumnNames("aicrm_currency_info");
if(!is_array($currency_columns))
{
	$currency_query = "CREATE TABLE `aicrm_currency_info` (
	  `id` int(11) NOT NULL auto_increment,
	  `currency_name` varchar(100) default NULL,
	  `currency_code` varchar(100) default NULL,
	  `currency_symbol` varchar(30) default NULL,
	  `conversion_rate` decimal(10,3) default NULL,
	  `currency_status` varchar(25) default NULL,
	  `defaultid` varchar(10) NOT NULL default '0',
	   PRIMARY KEY  (`id`)
	) ENGINE=InnoDB";
	Execute($currency_query);
}
elseif(!in_array("id",$currency_columns))
{
	$currency_query_array = Array(
		"alter table aicrm_currency_info drop primary key",
		"alter table aicrm_currency_info add column id int(11) NOT NULL auto_increment primary key FIRST",
		"alter table aicrm_currency_info add column conversion_rate decimal(10,3) default NULL",
		"alter table aicrm_currency_info add column currency_status varchar(25) default NULL",
		"alter table aicrm_currency_info add column defaultid varchar(10) NOT NULL default '0'",
				     );
	foreach($currency_query_array as $query)
	{
		Execute($query);
	}
}

$migrationlog->debug("Database Modifications for 5.0(Alpha) Dev 3 ==> 5.0 Alpha (5) ends here.");


/************************* The following changes have been made after 5.0 Alpha 5 *************************/
$migrationlog->debug("Database Modifications after 5.0(Alpha 5) starts here.");


//Added on 22-04-06 - to add the Notify Owner aicrm_field in Contacts and Accounts
$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (4, $newfieldid, 'notify_owner','contactdetails',1,56,'notify_owner','Notify Owner',1,0,0,10,24,4,1,'C~O',1,NULL,'ADV')";
Execute($insert_query);
populateFieldForSecurity('4',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (6, $newfieldid, 'notify_owner','account',1,56,'notify_owner','Notify Owner',1,0,0,10,18,9,1,'C~O',1,NULL,'ADV')";
Execute($insert_query);
populateFieldForSecurity('6',$newfieldid);

$notify_owner_array = Array(
	"update aicrm_field set sequence=26 where tabid=4 and fieldname='modifiedtime'",
	"update aicrm_field set sequence=25 where tabid=4 and fieldname='createdtime'",
	
	//"insert into aicrm_field values(4,".$conn->getUniqueID("aicrm_field").",'notify_owner','contactdetails',1,56,'notify_owner','Notify Owner',1,0,0,10,24,4,1,'C~O',1,NULL,'ADV')",
	"alter table aicrm_contactdetails add column notify_owner varchar(3) default 0 after reference",

	"update aicrm_field set sequence=21 where tabid=6 and fieldname='modifiedtime'",
	"update aicrm_field set sequence=20 where tabid=6 and fieldname='createdtime'",
	"update aicrm_field set sequence=19 where tabid=6 and fieldname='assigned_user_id'",
	
	//"insert into aicrm_field values(6,".$conn->getUniqueID("aicrm_field").",'notify_owner','account',1,56,'notify_owner','Notify Owner',1,0,0,10,18,9,1,'C~O',1,NULL,'ADV')",
	"alter table aicrm_account add column notify_owner varchar(3) default 0 after emailoptout"
			   );
foreach($notify_owner_array as $query)
{
	Execute($query);
}

//Added for RSS entries
$newfieldid = $conn->getUniqueID("aicrm_field");
$rss_insert_query = "insert into aicrm_field values (24, $newfieldid, 'rsscategory','rss',1,'15','rsscategory','rsscategory',1,0,0,255,13,null,1,'V~O',1,null,'BAS')";
Execute($rss_insert_query);
populateFieldForSecurity('24',$newfieldid);

//Quick Create Feature added for Vendor & PriceBook
$quickcreate_query = Array(
	"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 18 and fieldname = 'vendorname'",
	"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 18 and fieldname = 'phone'",
	"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 3 WHERE tabid = 18 and fieldname = 'email'",

	"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 1 WHERE tabid = 19 and fieldname = 'bookname'",
	"UPDATE aicrm_field SET quickcreate = 0,quickcreatesequence = 2 WHERE tabid = 19 and fieldname = 'active'"
			  );
foreach($quickcreate_query as $query)
{
	Execute($query);
}


//Added on 24-04-06 to populate aicrm_customview All for Campaign and webmails modules
$cvid1 = $conn->getUniqueID("aicrm_customview");
$cvid2 = $conn->getUniqueID("aicrm_customview");
$customview_query_array = Array(
	"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$cvid1.",'All',1,0,'Campaigns')",
	"insert into aicrm_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid1.",0,'aicrm_campaign:campaignname:campaignname:Campaigns_Campaign_Name:V')",
	"insert into aicrm_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid1.",1,'aicrm_campaign:campaigntype:campaigntype:Campaigns_Campaign_Type:N')",
	"insert into aicrm_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid1.",2,'aicrm_campaign:campaignstatus:campaignstatus:Campaigns_Campaign_Status:N')",
	"insert into aicrm_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid1.",3,'aicrm_campaign:expectedrevenue:expectedrevenue:Campaigns_Expected_Revenue:V')",
	"insert into aicrm_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid1.",4,'aicrm_campaign:closingdate:closingdate:Campaigns_Expected_Close_Date:D')",
	"insert into aicrm_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid1.",5,'aicrm_crmentity:smownerid:assigned_user_id:Campaigns_Assigned_To:V')",


	"insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype) values(".$cvid2.",'All',1,0,'Webmails')",
	"insert into aicrm_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid2.",0,'subject:subject:subject:Subject:V')",
	"insert into aicrm_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid2.",1,'from:fromname:fromname:From:N')",
	"insert into aicrm_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid2.",2,'to:tpname:toname:To:N')",
	"insert into aicrm_cvcolumnlist (cvid,columnindex,columnname) values (".$cvid2.",3,'body:body:body:Body:V')"

			       );
foreach($customview_query_array as $query)
{
	Execute($query);
}


$query_array2 = Array(
				//"INSERT INTO aicrm_parenttabrel VALUES(2,4,2)",
				//"INSERT INTO aicrm_parenttabrel VALUES(2,6,3)",
				"update aicrm_cvcolumnlist set columnname ='aicrm_crmentity:smownerid:assigned_user_id:Emails_Sender:V' where columnname='aicrm_crmentity:smownerid:assigned_user_id:Emails_Assigned_To:V'",
				"update aicrm_field set sequence = 2 where columnname='filename' and tablename = 'aicrm_attachments'",
				"delete from aicrm_cvcolumnlist where columnname = 'aicrm_seactivityrel:crmid:parent_id:Emails_Related_To:I'",
				//"update aicrm_cvcolumnlist set columnindex = 1 where cvid=20 and columnindex=3",
				"update aicrm_field set info_type='ADV' where tabid=18 and columnname in ('street','pobox','city','state','postalcode','country','description')",
				"update aicrm_field set info_type='ADV' where tabid in (20,21,22,23) and columnname in ('description','terms_conditions')",

				"create table aicrm_inventorytaxinfo (taxid int(3) NOT NULL, taxname varchar(50) default NULL, taxlabel varchar(50) default NULL, percentage decimal(7,3) default NULL, deleted int(1) default 0, PRIMARY KEY  (taxid), KEY aicrm_inventorytaxinfo_taxname_idx (taxname))",
				"create table aicrm_producttaxrel ( productid int(11) NOT NULL, taxid int(3) NOT NULL, taxpercentage decimal(7,3) default NULL, KEY aicrm_producttaxrel_productid_idx (productid), KEY aicrm_producttaxrel_taxid_idx (taxid))",
				"alter table aicrm_producttaxrel ADD CONSTRAINT fk_1_aicrm_producttaxrel FOREIGN KEY (productid) REFERENCES aicrm_products(productid) ON DELETE CASCADE",

				"update aicrm_field set uitype=83, tablename='aicrm_producttaxrel' where tabid=14 and fieldname='taxclass'",
				"insert into aicrm_moduleowners values(".$this->localGetTabID('Campaigns').",1)",

				"alter table aicrm_attachments add column path varchar(255) default NULL"
			     );

foreach($query_array2 as $query)
{
	Execute($query);
}

//This code will retrieve all the attachments from db and write it in a file
$attach_query_result = $conn->query("select aicrm_crmentity.createdtime, aicrm_attachments.* from aicrm_attachments inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid");
$noof_attachments = $conn->num_rows($attach_query_result);
for($attach_count = 0;$attach_count < $noof_attachments ;$attach_count++)
{
	$attach_id   = $conn->query_result($attach_query_result,$attach_count,'attachmentsid');
	$attach_name = $conn->query_result($attach_query_result,$attach_count,'name');
	$attach_data = $conn->query_result($attach_query_result,$attach_count,'attachmentcontents');

	$created_time = $conn->query_result($attach_query_result,$attach_count,'createdtime');
	//$filepath = decideFilePath();

	//Added to set the file path where to store the file based on the created time	
	$date = explode(" ",$created_time);
	$date_details = explode("-",$date[0]);

	$year = $date_details[0];
	$month = $date_details[1];
	$day = $date_details[2];
	
	//this is used to convert the month from number to string ie., 03 - March, 04 - April, etc.,
	$month = date("F", mktime(0, 0, 0, $month, $day, $year));

	$week_no = floor(($day-1)/7)+1;//decide the week ie., 1-7 = week1, 8-14=week2, 15-21=week3, etc.,
	$week = "week".$week_no;

	$filepath = 'storage/';
	
	if(!is_dir($filepath.$year))
		mkdir($filepath.$year);
	if(!is_dir($filepath.$year."/".$month))
		mkdir($filepath."$year/$month");
	if(!is_dir($filepath.$year."/".$month."/".$week))
		mkdir($filepath."$year/$month/$week");

	$filepath = $filepath.$year."/".$month."/".$week."/";
	$migrationlog->debug("File Path = $filepath");
	//upto this added to set the file path based on attachment created time

	//In this file name (attachmentid_filename) the file will be stored in the harddisk
	$moved_filename = $attach_id."_".$attach_name;
	//write the contents in the file
	$handle = @fopen($filepath.$moved_filename,'w');
	fputs($handle, base64_decode($attach_data));
	fclose($handle);

	//update the path in the db
	$update_attach = Execute("update aicrm_attachments set path='".$filepath."' where attachmentsid=$attach_id");
}

//Before drop these fields we had read the contents of the file from db and wrote it in a file.
$alter_query_array = Array( 
				"alter table aicrm_attachments drop column attachmentsize",
				"alter table aicrm_attachments drop column attachmentcontents"
			    );
foreach($alter_query_array as $query)
{
	Execute($query);
}


//To populate the comboStrings for Campaigns module which are added newly
require_once('include/ComboStrings.php');
global $combo_strings;

$comboTables = Array('campaigntype','campaignstatus','expectedresponse');
foreach ($comboTables as $tablename)
{
	$values = $combo_strings[$tablename."_dom"];
	$i=0;
	foreach ($values as $val => $cal)
	{
		if($val != '')
		{
			$conn->query("insert into aicrm_".$tablename. " values(null,'".$val."',".$i.",1)");
		}
		else
		{
			$conn->query("insert into aicrm_".$tablename. " values(null,'--None--',".$i.",1)");
		}
		$i++;
	}

}

$update_query3 = "update aicrm_currency_info set conversion_rate=1, currency_status='Active', defaultid='-11' where id=1";
Execute($update_query3);

$update_query4 = "update aicrm_relatedlists set label='Purchase Order' where tabid=18 and name='get_purchase_orders'";
Execute($update_query4);



//Added on 27-05-06

$create_query27 = "CREATE TABLE aicrm_invitees (activityid int(19) NOT NULL, inviteeid int(19) NOT NULL, PRIMARY KEY (activityid,inviteeid))";
Execute($create_query27);

$alter_query_array17 = Array(
				"ALTER TABLE aicrm_users ADD column hour_format varchar(30) default 'am/pm' AFTER date_format",
				"ALTER TABLE aicrm_users ADD column start_hour varchar(30) default '10:00' AFTER hour_format",
				"ALTER TABLE aicrm_users ADD column end_hour varchar(30) default '23:00' AFTER start_hour"
			    );
foreach($alter_query_array17 as $query)
{
	Execute($query);
}

$create_query28 = "CREATE TABLE aicrm_emaildetails (
			emailid int(19) NOT NULL,
			from_email varchar(50) NOT NULL default '',
			to_email text,
			cc_email text,
			bcc_email text,
			assigned_user_email varchar(50) NOT NULL default '',
			idlists varchar(50) NOT NULL default '',
			email_flag varchar(50) NOT NULL default '',
			PRIMARY KEY  (`emailid`)
		  )";
Execute($create_query28);


$obj_array = Array('Leads'=>'aicrm_leaddetails','Contacts'=>'aicrm_contactdetails');
$leadfieldid = $conn->query_result($conn->query("select fieldid from aicrm_field where tabid=7 and fieldname='email'"),0,'fieldid');
$contactfieldid = $conn->query_result($conn->query("select fieldid from aicrm_field where tabid=4 and fieldname='email'"),0,'fieldid');
$fieldid_array = Array("Leads"=>"$leadfieldid","Contacts"=>"$contactfieldid");
$idname_array = Array("Leads"=>"leadid","Contacts"=>"contactid");

$query = 'select * from aicrm_seactivityrel where activityid in (select activityid from aicrm_activity where activitytype="Emails") group by activityid';
$result = $conn->query($query);
$numofrows = $conn->num_rows($result);

for($i=0;$i<$numofrows;$i++)
{
	$toemail = "";
	$idlists = '';

	$emailid = $conn->query_result($result,$i,'activityid');

	$result1 = $conn->query("select * from aicrm_seactivityrel where activityid = $emailid");
	while($row = $conn->fetch_array($result1))
	{
		$result2 = $conn->query("select setype from aicrm_crmentity where crmid=".$row['crmid']);
		$module = $conn->query_result($result2,0,'setype');
		$idlists .= $row['crmid']."@$fieldid_array[$module]|";

		if($module == 'Leads' || $module == 'Contacts')
		{
			$result3 = $conn->query("select lastname, firstname, email from $obj_array[$module] where $idname_array[$module] = ".$row['crmid']);

			$toemail .= $conn->query_result($result3,0,'lastname')." ".$conn->query_result($result3,0,'firstname')."<".$conn->query_result($result3,0,'email').">###";
		}
		else
		{
			//the parent is not a Lead or Contact. so we have avoided the insert query
		}
	}

	//insert this idlists and toemail values in aicrm_emaildetails table
	$sql = "insert into aicrm_emaildetails values ($emailid,'',\"$toemail\",'','','',\"$idlists\",'SAVE')";
	Execute($sql);
}


$update_query5 = "update aicrm_field set quickcreate=1, quickcreatesequence=NULL where tabid in (10,14)";
Execute($update_query5);



//Security aicrm_profile and aicrm_tab table handling by DON starts
$sql_sec="select profileid from  aicrm_profile";
$result_sec=$conn->query($sql_sec);
$num_rows=$conn->num_rows($result_sec);
for($i=0;$i<$num_rows;$i++)
{
	$prof_id=$conn->query_result($result_sec,$i,'profileid');
	$sql1_sec="insert into aicrm_profile2utility values(".$prof_id.",13,8,0)";
	Execute($sql1_sec);

	$sql2_sec="insert into aicrm_profile2utility values(".$prof_id.",7,9,0)";
	Execute($sql2_sec);

	$sql3_sec="insert into aicrm_profile2tab values(".$prof_id.",26,0)";
	Execute($sql3_sec);

	$sql4_sec="insert into aicrm_profile2tab values(".$prof_id.",27,0)";
	Execute($sql4_sec);

	$sql7_sec="insert into aicrm_profile2standardpermissions values(".$prof_id.",26,0,0)";
	Execute($sql7_sec);

	$sql8_sec="insert into aicrm_profile2standardpermissions values(".$prof_id.",26,1,0)";
	Execute($sql8_sec);

	$sql9_sec="insert into aicrm_profile2standardpermissions values(".$prof_id.",26,2,0)";
	Execute($sql9_sec);

	$sql10_sec="insert into aicrm_profile2standardpermissions values(".$prof_id.",26,3,0)";
	Execute($sql10_sec);

	$sql11_sec="insert into aicrm_profile2standardpermissions values(".$prof_id.",26,4,0)";
	Execute($sql11_sec);	

}

//Inserting into aicrm_tab tables
$sec2="INSERT INTO aicrm_tab VALUES (27,'Portal',0,24,'Portal',null,null,1)";
$sec3="INSERT INTO aicrm_tab VALUES (28,'Webmails',0,25,'Webmails',null,null,1)";

//Insert into aicrm_def_org_share tables
$sec4="insert into aicrm_def_org_share values (".$conn->getUniqueID('aicrm_def_org_share').",26,2,0)";	

Execute($sec2);
Execute($sec3);
Execute($sec4);

//Inserting into datashare related modules table

Execute("insert into aicrm_datashare_relatedmodules_seq values(1)");
	
//Lead Related Module
Execute("insert into aicrm_datashare_relatedmodules values (".$conn->getUniqueID('aicrm_datashare_relatedmodules').",7,10)");

//Account Related Module
Execute("insert into aicrm_datashare_relatedmodules values (".$conn->getUniqueID('aicrm_datashare_relatedmodules').",6,2)");
Execute("insert into aicrm_datashare_relatedmodules values (".$conn->getUniqueID('aicrm_datashare_relatedmodules').",6,13)");
Execute("insert into aicrm_datashare_relatedmodules values (".$conn->getUniqueID('aicrm_datashare_relatedmodules').",6,20)");
Execute("insert into aicrm_datashare_relatedmodules values (".$conn->getUniqueID('aicrm_datashare_relatedmodules').",6,22)");
Execute("insert into aicrm_datashare_relatedmodules values (".$conn->getUniqueID('aicrm_datashare_relatedmodules').",6,23)");
Execute("insert into aicrm_datashare_relatedmodules values (".$conn->getUniqueID('aicrm_datashare_relatedmodules').",6,10)");


//Potential Related Module
Execute("insert into aicrm_datashare_relatedmodules values (".$conn->getUniqueID('aicrm_datashare_relatedmodules').",2,20)");
Execute("insert into aicrm_datashare_relatedmodules values (".$conn->getUniqueID('aicrm_datashare_relatedmodules').",2,22)");

//Quote Related Module
Execute("insert into aicrm_datashare_relatedmodules values (".$conn->getUniqueID('aicrm_datashare_relatedmodules').",20,22)");

//SO Related Module
Execute("insert into aicrm_datashare_relatedmodules values (".$conn->getUniqueID('aicrm_datashare_relatedmodules').",22,23)");
	

//By Don Ends

//Added the aicrm_tabel aicrm_mail_accounts which has been added by mmbrich
$alter_query18 = "alter table aicrm_mail_accounts add column int_mailer int(1) default '0'";
Execute($alter_query18);

$update_query_array5 = Array(
	"update aicrm_field set info_type='BAS' where tabid=6 and fieldname in ('tickersymbol','account_id')",
	"update aicrm_relatedlists set label = 'Activity History' where tabid in (4,6,7,20,21,22,23) and label = 'History'",
	"update aicrm_relatedlists set label = 'Products' where tabid=2 and name='get_products' and label='History'",
	"update aicrm_relatedlists set label = 'Activity History' where tabid=2 and name='get_history' and label='History'"
			    );
foreach($update_query_array5 as $query)
{
	Execute($query);
}

$insert_query_array27 = Array(
	"insert into aicrm_relatedlists values(".$conn->getUniqueID('aicrm_relatedlists').",13,0,'get_ticket_history',3,'Ticket History',0)",
	"insert into aicrm_parenttabrel values (2,10,4)",
	"insert into aicrm_parenttabrel values (4,10,7)"
			     );
foreach($insert_query_array27 as $query)
{
	Execute($query);
}


//User fields added in field table
$user_query_array = Array(

"alter table aicrm_users add column confirm_password varchar(50)",
"insert into aicrm_tab values (29,'Users',0,26,'Users',null,null,1)",

"insert into aicrm_blocks values (79,29,'LBL_USERLOGIN_ROLE',1,0,0,0,0,0)",
"insert into aicrm_blocks values (80,29,'LBL_MORE_INFORMATION',2,0,0,0,0,0)",
"insert into aicrm_blocks values (81,29,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)",
			 );
foreach($user_query_array as $query)
{
	Execute($query);
}

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid, 'user_name','aicrm_users',1,'106','user_name','User Name',1,0,0,11,1,79,1,'V~M',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid, 'is_admin','aicrm_users',1,'156','is_admin','Admin',1,0,0,3,2,79,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid, 'user_password','aicrm_users',1,'99','user_password','Password',1,0,0,30,3,79,4,'P~M',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid, 'confirm_password','aicrm_users',1,'99','confirm_password','Confirm Password',1,0,0,30,4,79,4,'P~M',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'first_name','aicrm_users',1,'1','first_name','First Name',1,0,0,30,5,79,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'last_name','aicrm_users',1,'2','last_name','Last Name',1,0,0,30,6,79,1,'V~M',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'roleid','aicrm_user2role',1,'98','roleid','Role',1,0,0,200,7,79,1,'V~M',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'email1','aicrm_users',1,'104','email1','Email',1,0,0,100,9,79,1,'E~M',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'status','aicrm_users',1,'115','status','Status',1,0,0,100,10,79,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'activity_view','aicrm_users',1,'15','activity_view','Default Activity View',1,0,0,100,13,79,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'lead_view','aicrm_users',1,'15','lead_view','Default Lead View',1,0,0,100,12,79,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'currency_id','aicrm_users',1,'116','currency_id','Currency',1,0,0,100,11,79,1,'I~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'title','aicrm_users',1,'1','title','Title',1,0,0,50,1,80,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'phone_work','aicrm_users',1,'1','phone_work','Office Phone',1,0,0,50,2,80,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'department','aicrm_users',1,'1','department','Department',1,0,0,50,3,80,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'phone_mobile','aicrm_users',1,'1','phone_mobile','Mobile',1,0,0,50,4,80,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'reports_to_id','aicrm_users',1,'101','reports_to_id','Reports To',1,0,0,50,5,80,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'phone_other','aicrm_users',1,'1','phone_other','Other Phone',1,0,0,50,5,80,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'email2','aicrm_users',1,'13','email2','Other Email',1,0,0,100,6,80,1,'E~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'phone_fax','aicrm_users',1,'1','phone_fax','Fax',1,0,0,50,7,80,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'yahoo_id','aicrm_users',1,'13','yahoo_id','Yahoo id',1,0,0,100,7,80,1,'E~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'phone_home','aicrm_users',1,'1','phone_home','Home Phone',1,0,0,50,8,80,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'imagename','aicrm_users',1,'105','imagename','User Image',1,0,0,250,9,80,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'date_format','aicrm_users',1,'15','date_format','Date Format',1,0,0,30,10,80,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'tagcloud','aicrm_users',1,'103','tagcloud','Tag Cloud',1,0,0,250,13,80,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'signature','aicrm_users',1,'21','signature','Signature',1,0,0,250,11,80,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'description','aicrm_users',1,'21','description','Notes',1,0,0,250,12,80,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'address_street','aicrm_users',1,'21','address_street','Street Address',1,0,0,250,1,81,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'address_city','aicrm_users',1,'1','address_city','City',1,0,0,100,2,81,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'address_state','aicrm_users',1,'1','address_state','State',1,0,0,100,3,81,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'address_postalcode','aicrm_users',1,'1','address_postalcode','Postal Code',1,0,0,100,4,81,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$insert_query = "insert into aicrm_field values (29, $newfieldid,'address_country','aicrm_users',1,'1','address_country','Country',1,0,0,100,5,81,1,'V~O',1,null,'BAS')";
Execute($insert_query);
populateFieldForSecurity('29',$newfieldid);


$create_query29 = "CREATE TABLE aicrm_status (
			`statusid` int(19) NOT NULL auto_increment,
			`status` varchar(200) NOT NULL,
			`sortorderid` int(19) NOT NULL default '0',
			`presence` int(1) NOT NULL default '1',
			PRIMARY KEY  (`statusid`)
			)";
Execute($create_query29);

Execute("insert into aicrm_status values (1,'Active',0,1)");
Execute("insert into aicrm_status values (2,'Inactive',1,1)");

$create_query30 = "CREATE TABLE aicrm_activity_view (
			`activity_viewid` int(19) NOT NULL auto_increment,
			`activity_view` varchar(200) NOT NULL,
			`sortorderid` int(19) NOT NULL default '0',
			`presence` int(1) NOT NULL default '1',
			PRIMARY KEY  (`activity_viewid`)
			)";
Execute($create_query30);

Execute("insert into aicrm_activity_view values (1,'Today',0,1)");
Execute("insert into aicrm_activity_view values (2,'This Week',1,1)");
Execute("insert into aicrm_activity_view values (3,'This Month',2,1)");
Execute("insert into aicrm_activity_view values (4,'This Year',3,1)");


$create_query31 = "CREATE TABLE aicrm_lead_view (
			`lead_viewid` int(19) NOT NULL auto_increment,
			`lead_view` varchar(200) NOT NULL,
			`sortorderid` int(19) NOT NULL default '0',
			`presence` int(1) NOT NULL default '1',
			PRIMARY KEY  (`lead_viewid`)
			)";
Execute($create_query31);

Execute("insert into aicrm_lead_view values (1,'Today',0,1)");
Execute("insert into aicrm_lead_view values (2,'Last 2 Days',1,1)");
Execute("insert into aicrm_lead_view values (3,'Last Week',2,1)");


$create_query32 = "CREATE TABLE aicrm_date_format (
			`date_formatid` int(19) NOT NULL auto_increment,
			`date_format` varchar(200) NOT NULL,
			`sortorderid` int(19) NOT NULL default '0',
			`presence` int(1) NOT NULL default '1',
			PRIMARY KEY  (`date_formatid`)
			)";
Execute($create_query32);

Execute("insert into aicrm_date_format values (1,'dd-mm-yyyy',0,1)");
Execute("insert into aicrm_date_format values (2,'mm-dd-yyyy',1,1)");
Execute("insert into aicrm_date_format values (3,'yyyy-mm-dd',2,1)");
//end of User fields added in field table

//Activities and Leads Added under Marketing
Execute("insert into aicrm_parenttabrel values (2,7,5)");
Execute("insert into aicrm_parenttabrel values (2,9,6)");
Execute("insert into aicrm_parenttabrel values (4,9,8)");

//Queries to remove the rss categories
Execute("drop table aicrm_rsscategory");
Execute("delete from aicrm_field where tabid=24");

//Added on 23-06-06
$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (29, $newfieldid, 'hour_format','aicrm_users',1,'116','hour_format','Calendar Hour Format',1,0,0,100,13,79,3,'I~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (29, $newfieldid, 'end_hour','aicrm_users',1,'116','end_hour','Day ends at',1,0,0,100,15,79,3,'I~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('29',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (29, $newfieldid, 'start_hour','aicrm_users',1,'116','start_hour','Day starts at',1,0,0,100,14,79,3,'I~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('29',$newfieldid);

Execute("insert into aicrm_relatedlists values (".$conn->getUniqueID('aicrm_relatedlists').",".getTabid("Campaigns").",".getTabid("Potentials").",'get_opportunities',3,'Potentials',0)");
Execute("insert into aicrm_relatedlists values(".$conn->getUniqueID('aicrm_relatedlists').",".getTabid("Campaigns").",9,'get_activities',4,'Activities',0)");

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (2, $newfieldid, 'campaignid','aicrm_potential',1,'58','campaignid','Campaign Source',1,0,0,100,12,1,1,'N~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('2',$newfieldid);

//Added on 28-06-06
//Campaigns module added in Leads and Contacts RelatedList
Execute("insert into aicrm_relatedlists values(".$conn->getUniqueID('aicrm_relatedlists').",".getTabid("Leads").",".getTabid("Campaigns").",'get_campaigns',6,'Campaigns',0)");
Execute("insert into aicrm_relatedlists values(".$conn->getUniqueID('aicrm_relatedlists').",".getTabid("Contacts").",".getTabid("Campaigns").",'get_campaigns',11,'Campaigns',0)");

//Contact Name has been removed from Events Information
Execute("delete from aicrm_field where tabid=16 and fieldname='contact_id'");

//queries to resequence the fields
$fieldname=array('eventstatus','sendnotification','activitytype','location','createdtime','modifiedtime','taskpriority','notime','visibility');
$tablename=array('aicrm_activity','aicrm_activity','aicrm_activity','aicrm_activity','aicrm_crmentity','aicrm_crmentity','aicrm_activity','aicrm_activity','aicrm_activity');

$sequence = array(10,11,12,13,14,15,16,17,18);
for($j = 0;$j < 9;$j++)
{
	Execute("update aicrm_field set sequence=".$sequence[$j]." where tablename='".$tablename[$j]."' && fieldname='".$fieldname[$j]."' and tabid=16");
}

//Campaign has been removed from field table
Execute("delete from aicrm_field where tabid=7 and fieldname='campaignid'");
Execute("delete from aicrm_field where tabid=4 and fieldname='campaignid'");

//Query added to have Calendar under Marketing and Support
$conn->query("insert into aicrm_parenttabrel values (2,17,7)");
$conn->query("insert into aicrm_parenttabrel values (4,17,9)");



//Added on 02-08-2006 ie., 2nd August 2006

//column added for proxy server settings
Execute("alter table aicrm_systems add column server_port int(19) default NULL after server");

//type changed to support decimal places
Execute("alter table aicrm_campaign change expectedrevenue expectedrevenue decimal(11,3)");
Execute("alter table aicrm_campaign change budgetcost budgetcost decimal(11,3)");
Execute("alter table aicrm_campaign change actualcost actualcost decimal(11,3)");
Execute("alter table aicrm_campaign change expectedroi expectedroi decimal(11,3)");
Execute("alter table aicrm_campaign change actualroi actualroi decimal(11,3)");

//homeorder value modified to get graph in homepage
Execute("update aicrm_users set homeorder='ALVT,PLVT,QLTQ,CVLVT,HLT,OLV,GRT,OLTSO,ILTI,MNL,HDB'");

//Removed activities from product related list
Execute("delete from aicrm_relatedlists where tabid = 14 and related_tabid=9");
Execute("insert into aicrm_relatedlists values(".$conn->getUniqueID('aicrm_relatedlists').",".getTabid("HelpDesk").",9,'get_history',4,'Activity History',0)");

//Assigned to field for Events made Optional
Execute("update aicrm_field set typeofdata='V~M' where columnname='smownerid' and tabid=16 and fieldname='assigned_user_id'");

//Query added to have Notes under Marketing and Support --Jeri -- 04-06-06
Execute("insert into aicrm_parenttabrel values (2,8,8)");


//Update Query for quickcreate sequence of Campaign & Ticket -- Added by Ahmed -- 11-07-2006
Execute("update aicrm_field set quickcreatesequence='3' where fieldname='filename' and tabid=13");
Execute("update aicrm_field set quickcreatesequence='4' where fieldname='ticketpriorities' and tabid=13");
Execute("update aicrm_field set quickcreatesequence='3' where fieldname='campaigntype' and tabid=26");
Execute("update aicrm_field set quickcreatesequence='6' where fieldname='campaignstatus' and tabid=26");
Execute("update aicrm_field set quickcreatesequence='2' where fieldname='closingdate' and tabid=26");


//Added for Tax and Inventory - Product details handling

Execute("CREATE TABLE aicrm_inventoryproductrel (id int(19) NOT NULL, productid int(19) NOT NULL, sequence_no int(4) NOT NULL default 1, quantity int(19) default NULL, listprice decimal(11,3) default NULL, discount_percent decimal(7,3) default NULL, discount_amount decimal(11,3) default NULL, comment varchar(100) default NULL, KEY inventoryproductrel_id_idx (id), KEY inventoryproductrel_productid_idx (productid) ) ENGINE=InnoDB");

//Execute("alter table aicrm_inventorytaxinfo add column deleted int(1) default 0");

Execute("CREATE TABLE aicrm_shippingtaxinfo ( taxid int(3) NOT NULL, taxname varchar(50) default NULL, taxlabel varchar(50) default NULL, percentage decimal(7,3) default NULL, deleted int(1) default '0', PRIMARY KEY (taxid), KEY shippingtaxinfo_taxname_idx (taxname) ) ENGINE=InnoDB");

Execute("CREATE TABLE aicrm_inventoryshippingrel (id int(19) NOT NULL, KEY inventoryishippingrel_id_idx (id) ) ENGINE=InnoDB");

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (21, $newfieldid, 'taxtype','aicrm_purchaseorder',1,'15','hdnTaxType','Tax Type',1,0,0,100,14,57,3,'V~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('21',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (21, $newfieldid, 'discount_percent','aicrm_purchaseorder',1,'1','hdnDiscountPercent','Discount Percent',1,0,0,100,14,57,3,'N~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('21',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (21, $newfieldid, 'discount_amount','aicrm_purchaseorder',1,'1','hdnDiscountAmount','Discount Amount',1,0,0,100,14,57,3,'N~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('21',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (21, $newfieldid, 's_h_amount','aicrm_purchaseorder',1,'1','hdnS_H_Amount','S&H Amount',1,0,0,100,14,57,3,'N~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('21',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (22, $newfieldid, 'taxtype','aicrm_salesorder',1,'15','hdnTaxType','Tax Type',1,0,0,100,15,63,3,'V~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('22',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (22, $newfieldid, 'discount_percent','aicrm_salesorder',1,'1','hdnDiscountPercent','Discount Percent',1,0,0,100,15,63,3,'N~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('22',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (22, $newfieldid, 'discount_amount','aicrm_salesorder',1,'1','hdnDiscountAmount','Discount Amount',1,0,0,100,15,63,3,'N~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('22',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (22, $newfieldid, 's_h_amount','aicrm_salesorder',1,'1','hdnS_H_Amount','S&H Amount',1,0,0,100,15,63,3,'N~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('22',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (20, $newfieldid, 'taxtype','aicrm_quotes',1,'15','hdnTaxType','Tax Type',1,0,0,100,14,51,3,'V~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('20',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (20, $newfieldid, 'discount_percent','aicrm_quotes',1,'1','hdnDiscountPercent','Discount Percent',1,0,0,100,14,51,3,'N~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('20',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (20, $newfieldid, 'discount_amount','aicrm_quotes',1,'1','hdnDiscountAmount','Discount Amount',1,0,0,100,14,51,3,'N~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('20',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (20, $newfieldid, 's_h_amount','aicrm_quotes',1,'1','hdnS_H_Amount','S&H Amount',1,0,0,100,14,51,3,'N~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('20',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (23, $newfieldid, 'taxtype','aicrm_invoice',1,'15','hdnTaxType','Tax Type',1,0,0,100,13,69,3,'V~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('23',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (23, $newfieldid, 'discount_percent','aicrm_invoice',1,'1','hdnDiscountPercent','Discount Percent',1,0,0,100,13,69,3,'N~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('23',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (23, $newfieldid, 'discount_amount','aicrm_invoice',1,'1','hdnDiscountAmount','Discount Amount',1,0,0,100,13,69,3,'N~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('23',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (23, $newfieldid, 's_h_amount','aicrm_invoice',1,'1','hdnS_H_Amount','S&H Amount',1,0,0,100,14,57,3,'N~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('23',$newfieldid);


Execute("alter table aicrm_purchaseorder add column taxtype varchar(25) default NULL after subtotal");
Execute("alter table aicrm_purchaseorder add column discount_percent decimal(11,3) default NULL after taxtype");
Execute("alter table aicrm_purchaseorder add column discount_amount decimal(11,3) default NULL after discount_percent");
Execute("alter table aicrm_purchaseorder add column s_h_amount decimal(11,3) default NULL after discount_amount");

Execute("alter table aicrm_salesorder add column taxtype varchar(25) default NULL after subtotal");
Execute("alter table aicrm_salesorder add column discount_percent decimal(11,3) default NULL after taxtype");
Execute("alter table aicrm_salesorder add column discount_amount decimal(11,3) default NULL after discount_percent");
Execute("alter table aicrm_salesorder add column s_h_amount decimal(11,3) default NULL after discount_amount");

Execute("alter table aicrm_quotes add column taxtype varchar(25) default NULL after total");
Execute("alter table aicrm_quotes add column discount_percent decimal(11,3) default NULL after taxtype");
Execute("alter table aicrm_quotes add column discount_amount decimal(11,3) default NULL after discount_percent");
Execute("alter table aicrm_quotes add column s_h_amount decimal(11,3) default NULL after discount_amount");

Execute("alter table aicrm_invoice add column taxtype varchar(25) default NULL after total");
Execute("alter table aicrm_invoice add column discount_percent decimal(11,3) default NULL after taxtype");
Execute("alter table aicrm_invoice add column discount_amount decimal(11,3) default NULL after discount_percent");
Execute("alter table aicrm_invoice add column s_h_amount decimal(11,3) default NULL after discount_amount");


//Update Query to Match Notes List View Header Fieldnames with Custom View combo values
Execute("update aicrm_field set fieldlabel='Title' where fieldlabel='Subject' and tabid=8");
Execute("update aicrm_field set fieldlabel='File' where fieldlabel='Attachment' and tabid=8");


//Update Query to change the UI type of Rating Field in Accounts Module from 1 to 15 to display combo list
Execute("update aicrm_field set uitype=15 where tabid=6 and fieldname='rating'");


//Insert and Update Query for new block addition for Campaigns Module
Execute("update aicrm_blocks set blocklabel='LBL_EXPECTATIONS_AND_ACTUALS' where tabid=26 and blockid=78");
Execute("insert into aicrm_blocks values (82,26,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)");


//Update Query for rearrangements of fields in Campaigns Module's Detail/Edit/Create View
Execute("update aicrm_field set sequence=1 where tabid=26 and columnname='campaignname' and fieldname='campaignname'");
Execute("update aicrm_field set sequence=2 where tabid=26 and columnname='campaignstatus' and fieldname='campaignstatus'");
Execute("update aicrm_field set sequence=3 where tabid=26 and columnname='smownerid' and fieldname='assigned_user_id'");
Execute("update aicrm_field set sequence=4 where tabid=26 and columnname='product_id' and fieldname='product_id'");
Execute("update aicrm_field set sequence=5 where tabid=26 and columnname='campaigntype' and fieldname='campaigntype'");
Execute("update aicrm_field set sequence=6 where tabid=26 and columnname='closingdate' and fieldname='closingdate'");
Execute("update aicrm_field set sequence=7 where tabid=26 and columnname='targetaudience' and fieldname='targetaudience'");
Execute("update aicrm_field set sequence=8 where tabid=26 and columnname='targetsize' and fieldname='targetsize'");
Execute("update aicrm_field set sequence=9 where tabid=26 and columnname='sponsor' and fieldname='sponsor'");
Execute("update aicrm_field set sequence=10 where tabid=26 and columnname='numsent' and fieldname='numsent'");
Execute("update aicrm_field set sequence=11 where tabid=26 and columnname='createdtime' and fieldname='createdtime'");
Execute("update aicrm_field set sequence=12 where tabid=26 and columnname='modifiedtime' and fieldname='modifiedtime'");

Execute("update aicrm_field set sequence=1, block=78 where tabid=26 and columnname='budgetcost' and fieldname='budgetcost'");
Execute("update aicrm_field set sequence=2, block=78 where tabid=26 and columnname='actualcost' and fieldname='actualcost'");
Execute("update aicrm_field set sequence=3, block=78 where tabid=26 and columnname='expectedresponse' and fieldname='expectedresponse'");
Execute("update aicrm_field set sequence=4, block=78 where tabid=26 and columnname='expectedrevenue' and fieldname='expectedrevenue'");
Execute("update aicrm_field set sequence=5, block=78 where tabid=26 and columnname='expectedsalescount' and fieldname='expectedsalescount'");
Execute("update aicrm_field set sequence=6, block=78 where tabid=26 and columnname='actualsalescount' and fieldname='actualsalescount'");
Execute("update aicrm_field set sequence=7, block=78 where tabid=26 and columnname='expectedresponsecount' and fieldname='expectedresponsecount'");
Execute("update aicrm_field set sequence=8, block=78 where tabid=26 and columnname='actualresponsecount' and fieldname='actualresponsecount'");
Execute("update aicrm_field set sequence=9, block=78 where tabid=26 and columnname='expectedroi' and fieldname='expectedroi'");
Execute("update aicrm_field set sequence=10, block=78 where tabid=26 and columnname='actualroi' and fieldname='actualroi'");



//Update query to set the fieldname in user detail/edit/create view
Execute("update aicrm_field set sequence=1 where tabid=29 and columnname='user_name' and fieldname='user_name' and block=79");
Execute("update aicrm_field set sequence=2 where tabid=29 and columnname='is_admin' and fieldname='is_admin' and block=79");
Execute("update aicrm_field set sequence=3 where tabid=29 and columnname='user_password' and fieldname='user_password' and block=79");
Execute("update aicrm_field set sequence=4 where tabid=29 and columnname='email1' and fieldname='email1' and block=79");
Execute("update aicrm_field set sequence=5 where tabid=29 and columnname='confirm_password' and fieldname='confirm_password' and block=79");
Execute("update aicrm_field set sequence=6 where tabid=29 and columnname='status' and fieldname='status' and block=79");
Execute("update aicrm_field set sequence=7 where tabid=29 and columnname='first_name' and fieldname='first_name' and block=79");
Execute("update aicrm_field set sequence=8 where tabid=29 and columnname='currency_id' and fieldname='currency_id' and block=79");
Execute("update aicrm_field set sequence=9 where tabid=29 and columnname='last_name' and fieldname='last_name' and block=79");
Execute("update aicrm_field set sequence=10 where tabid=29 and columnname='lead_view' and fieldname='lead_view' and block=79");
Execute("update aicrm_field set sequence=11 where tabid=29 and columnname='roleid' and fieldname='roleid' and block=79");
Execute("update aicrm_field set sequence=12 where tabid=29 and columnname='activity_view' and fieldname='activity_view' and block=79");
Execute("update aicrm_field set sequence=13 where tabid=29 and columnname='hour_format' and fieldname='hour_format' and block=79");
Execute("update aicrm_field set sequence=14 where tabid=29 and columnname='start_hour' and fieldname='start_hour' and block=79");
Execute("update aicrm_field set sequence=15 where tabid=29 and columnname='end_hour' and fieldname='end_hour' and block=79");

Execute("update aicrm_field set sequence=1 where tabid=29 and columnname='title' and fieldname='title' and block=80");
Execute("update aicrm_field set sequence=2 where tabid=29 and columnname='phone_fax' and fieldname='phone_fax' and block=80");
Execute("update aicrm_field set sequence=3 where tabid=29 and columnname='department' and fieldname='department' and block=80");
Execute("update aicrm_field set sequence=4 where tabid=29 and columnname='email2' and fieldname='email2' and block=80");
Execute("update aicrm_field set sequence=5 where tabid=29 and columnname='phone_work' and fieldname='phone_work' and block=80");
Execute("update aicrm_field set sequence=6 where tabid=29 and columnname='yahoo_id' and fieldname='yahoo_id' and block=80");
Execute("update aicrm_field set sequence=7 where tabid=29 and columnname='phone_mobile' and fieldname='phone_mobile' and block=80");
Execute("update aicrm_field set sequence=8 where tabid=29 and columnname='reports_to_id' and fieldname='reports_to_id' and block=80");
Execute("update aicrm_field set sequence=9 where tabid=29 and columnname='phone_home' and fieldname='phone_home' and block=80");
Execute("update aicrm_field set sequence=10 where tabid=29 and columnname='imagename' and fieldname='imagename' and block=80");
Execute("update aicrm_field set sequence=11 where tabid=29 and columnname='phone_other' and fieldname='phone_other' and block=80");
Execute("update aicrm_field set sequence=12 where tabid=29 and columnname='date_format' and fieldname='date_format' and block=80");
Execute("update aicrm_field set sequence=13 where tabid=29 and columnname='signature' and fieldname='signature' and block=80");
Execute("update aicrm_field set sequence=14 where tabid=29 and columnname='description' and fieldname='description' and block=80");
Execute("update aicrm_field set sequence=15 where tabid=29 and columnname='tagcloud' and fieldname='tagcloud' and block=80");

Execute("update aicrm_field set sequence=1 where tabid=29 and columnname='address_street' and fieldname='address_street' and block=81");
Execute("update aicrm_field set sequence=2 where tabid=29 and columnname='address_country' and fieldname='address_country' and block=81");
Execute("update aicrm_field set sequence=3 where tabid=29 and columnname='address_city' and fieldname='address_city' and block=81");
Execute("update aicrm_field set sequence=4 where tabid=29 and columnname='address_postalcode' and fieldname='address_postalcode' and block=81");
Execute("update aicrm_field set sequence=5 where tabid=29 and columnname='address_state' and fieldname='address_state' and block=81");

//Added for Recurring events
Execute("alter table aicrm_recurringevents add column recurringfreq int(19) default NULL");
Execute("alter table aicrm_recurringevents add column recurringinfo varchar(50) default NULL");


//Update Query for changing the uitype for existing picklist entries making it non-editable
Execute("update aicrm_field set uitype=111 where fieldname in ('sales_stage','ticketstatus','taskstatus','eventstatus','faqstatus','quotestage','postatus','sostatus','invoicestatus')");










//Inventory Tax handlings -- Starts

//Added to populate the default Shipping & Hanlding tax informations
$shvatid = $conn->getUniqueID("aicrm_shippingtaxinfo");
$shsalesid = $conn->getUniqueID("aicrm_shippingtaxinfo");
$shserviceid = $conn->getUniqueID("aicrm_shippingtaxinfo");

$conn->query("insert into aicrm_shippingtaxinfo values($shvatid,'shtax".$shvatid."','VAT','4.50','0')");
$conn->query("insert into aicrm_shippingtaxinfo values($shsalesid,'shtax".$shsalesid."','Sales','10.00','0')");
$conn->query("insert into aicrm_shippingtaxinfo values($shserviceid,'shtax".$shserviceid."','Service','12.50','0')");

//After added these taxes we should add these taxes as columns in aicrm_inventoryshippingrel table
$conn->query("alter table aicrm_inventoryshippingrel add column shtax$shvatid decimal(7,3) default NULL");
$conn->query("alter table aicrm_inventoryshippingrel add column shtax$shsalesid decimal(7,3) default NULL");
$conn->query("alter table aicrm_inventoryshippingrel add column shtax$shserviceid decimal(7,3) default NULL");


//Added to populate the Common tax which will be used to save the existing tax (percentage will be calculated based on the total tax amount retrieved from the entity tables of PO, SO, Quotes and Invoice)
$migratedtaxid = 1;
$migratedtaxid = $conn->getUniqueID("aicrm_inventorytaxinfo");
$migrated_taxname = "tax$migratedtaxid";
$conn->query("insert into aicrm_inventorytaxinfo values($migratedtaxid,'".$migrated_taxname."','Tax','0.00','0')");

//After added these taxes we should add these taxes as columns in aicrm_inventoryproductrel table
$conn->query("alter table aicrm_inventoryproductrel add column $migrated_taxname decimal(7,3) default NULL");

//Now we should create tax for each and every value given in picklist taxclass
$taxres = $conn->query("select * from aicrm_taxclass");
$taxcount = $conn->num_rows($taxres);
for($i=0;$i<$taxcount;$i++)
{
	$taxlabel = $conn->query_result($taxres,$i,'taxclass');

	$newtaxid = $conn->getUniqueID("aicrm_inventorytaxinfo");
	$addtaxres = $conn->query("alter table aicrm_inventoryproductrel add column tax$newtaxid decimal(7,3) default NULL");
	if($addtaxres)
		$conn->query("insert into aicrm_inventorytaxinfo values($newtaxid,'tax".$newtaxid."','".$taxlabel."','0.00','0')");
}
//Finished the add tax process based on the available tax classes

//To save Product - Tax relationship
//get Product - taxclass and add entry in aicrm_producttaxrel for this product - tax relationship
$productres = $conn->query("select productid, taxclass from aicrm_products");
$productcount = $conn->num_rows($productres);
for($i=0;$i<$productcount;$i++)
{
	$productid = $conn->query_result($productres,$i,'productid');
	$taxlabel = $conn->query_result($productres,$i,'taxclass');

	$taxres = $conn->query("select taxid from aicrm_inventorytaxinfo where taxlabel='".addslashes($taxlabel)."'");
	$taxid = $conn->query_result($taxres,0,'taxid');

	$taxquery = "insert into aicrm_producttaxrel values($productid, \"$taxid\", '0.00')";
	//Execute($taxquery);
	$conn->query($taxquery);
}





//Retrieve values from poproductrel, soproductrel, quotesproductrel, invoiceproductrel and store in aicrm_inventoryproductrel

$inventory_tables = Array(
				'aicrm_poproductrel'=>'purchaseorderid',
				'aicrm_soproductrel'=>'salesorderid',
				'aicrm_quotesproductrel'=>'quoteid',
				'aicrm_invoiceproductrel'=>'invoiceid'
			 );

foreach($inventory_tables as $tablename => $idname)
{
	$res = $conn->query("select * from $tablename order by $idname");
	$count = $conn->num_rows($res);

	$id = $oldid = 0;
	$seqno = 0;

	for($i=0;$i<$count;$i++)
	{
		$oldid = $id;
		$id = $conn->query_result($res,$i,$idname);

		//for every new PO/SO/Quotes/Invoice entity we should set the sequence start value as 1
		if($id != $oldid)
			$seqno = 1;

		$productid = $conn->query_result($res,$i,'productid');
		$quantity = $conn->query_result($res,$i,'quantity');
		$listprice = $conn->query_result($res,$i,'listprice');

		$query1 = "insert into aicrm_inventoryproductrel(id,productid,sequence_no,quantity,listprice) values($id, $productid,$seqno, $quantity, $listprice)";
		Execute($query1);
		$seqno++;
	}
}


//Now for each and every PO, SO, Quotes and Invoice we should get the total, discount, tax
$inventory_tables = Array(
				'aicrm_purchaseorder'=>'purchaseorderid',
				'aicrm_salesorder'=>'salesorderid',
				'aicrm_quotes'=>'quoteid',
				'aicrm_invoice'=>'invoiceid'
			 );

foreach($inventory_tables as $tablename => $idname)
{
	$res2 = $conn->query("select * from $tablename order by $idname");
	$entitycount = $conn->num_rows($res2);

	for($i=0;$i<$entitycount;$i++)
	{
		$idval = $conn->query_result($res2,$i,$idname);
		
		//$res3 = $conn->query("select * from $tablename where $idname=$idval");
		$subtotal = $conn->query_result($res2,$i,'subtotal');
		$taxamount = $conn->query_result($res2,$i,'salestax');

		//Now based on the inventory tax total - calculate the percentage
		$taxpercent = '0.00';
		if($taxamount > 0 && $subtotal >0)
		{
			$taxpercent = $taxamount*100/$subtotal;
		}

		//update the taxtype as group
		$query2 = "update $tablename set taxtype='group'";
		Execute($query2);
		
		//update the calculated percentage for the entity ie., PO/SO/Quotes/Invoice
		$query3 = "update aicrm_inventoryproductrel set  $migrated_taxname='".$taxpercent."' where id=$idval";
		Execute($query3);
	}
}


//we have retrieve and saved all the values, so we can delete the unwanted tables
Execute("drop table aicrm_poproductrel");
Execute("drop table aicrm_soproductrel");
Execute("drop table aicrm_quotesproductrel");
Execute("drop table aicrm_invoiceproductrel");

//Inventory Tax handlings -- Ends


//Add Inventory History tracking tables ie.,PO Status, SO Status, Quote Stage and Invoice Status tables
//PO Status
Execute("CREATE TABLE aicrm_postatushistory ( historyid int(19) NOT NULL auto_increment, purchaseorderid int(19) NOT NULL, vendorname varchar(100) default NULL, total decimal(10,0) default NULL, postatus varchar(200) default NULL, lastmodified datetime default NULL, PRIMARY KEY  (historyid), KEY postatushistory_purchaseorderid_idx (purchaseorderid), CONSTRAINT fk_1_aicrm_postatushistory FOREIGN KEY (purchaseorderid) REFERENCES aicrm_purchaseorder (purchaseorderid) ON DELETE CASCADE ) ENGINE=InnoDB");

//SO Status
Execute("CREATE TABLE aicrm_sostatushistory (historyid int(19) NOT NULL auto_increment, salesorderid int(19) NOT NULL, accountname varchar(100) default NULL, total decimal(10,0) default NULL, sostatus varchar(200) default NULL, lastmodified datetime default NULL, PRIMARY KEY  (historyid), KEY sostatushistory_salesorderid_idx (salesorderid), CONSTRAINT fk_1_aicrm_sostatushistory FOREIGN KEY (salesorderid) REFERENCES aicrm_salesorder (salesorderid) ON DELETE CASCADE ) ENGINE=InnoDB");

//Quote Stage
Execute("CREATE TABLE aicrm_quotestagehistory ( historyid int(19) NOT NULL auto_increment, quoteid int(19) NOT NULL, accountname varchar(100) default NULL, total decimal(10,0) default NULL, quotestage varchar(200) default NULL, lastmodified datetime default NULL, PRIMARY KEY  (historyid), KEY quotestagehistory_quoteid_idx (quoteid), CONSTRAINT fk_1_aicrm_quotestagehistory FOREIGN KEY (quoteid) REFERENCES aicrm_quotes (quoteid) ON DELETE CASCADE) ENGINE=InnoDB");

//Invoice Status
Execute("CREATE TABLE aicrm_invoicestatushistory ( historyid int(19) NOT NULL auto_increment, invoiceid int(19) NOT NULL, accountname varchar(100) default NULL, total decimal(10,0) default NULL, invoicestatus varchar(200) default NULL, lastmodified datetime default NULL, PRIMARY KEY  (historyid), KEY invoicestatushistory_invoiceid_idx (invoiceid), CONSTRAINT fk_1_aicrm_invoicestatushistory FOREIGN KEY (invoiceid) REFERENCES aicrm_invoice (invoiceid) ON DELETE CASCADE) ENGINE=InnoDB");


//User image handling
Execute("insert into aicrm_blocks values (83,29,'LBL_USER_IMAGE_INFORMATION',4,0,0,0,0,0)");
Execute("update aicrm_field set block=83 where tabid=29 and fieldname='imagename' and columnname='imagename'");

Execute("update aicrm_field set tablename='aicrm_products' where fieldname='taxclass' && tabid=14");

Execute("update aicrm_field set info_type='BAS' where tabid=4 and fieldname='email' and columnname='email'");

Execute("update aicrm_field set info_type='ADV' where tabid=4 and fieldname='otherphone' and columnname='otherphone'");

Execute("CREATE TABLE aicrm_salesmanattachmentsrel ( smid int(19) NOT NULL default '0', attachmentsid int(19) NOT NULL default '0', PRIMARY KEY (smid, attachmentsid), KEY salesmanattachmentsrel_smid_idx (smid), KEY salesmanattachmentsrel_attachmentsid_idx (attachmentsid), CONSTRAINT fk_1_aicrm_salesmanattachmentsrel FOREIGN KEY (smid) REFERENCES aicrm_users (id), CONSTRAINT fk_2_aicrm_salesmanattachmentsrel FOREIGN KEY (attachmentsid) REFERENCES aicrm_attachments (attachmentsid) ON DELETE CASCADE) ENGINE=InnoDB");


//Changes made for Activity merge with Calendar - Starts
Execute("alter table aicrm_activity add column time_end varchar(50) default NULL after time_start");

Execute("delete from aicrm_tab where tabid=17");

Execute("update aicrm_tab set name='Calendar',tablabel='Calendar' where tabid=9");

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (9, $newfieldid, 'time_end','aicrm_activity', 1,'2','time_end','End Time',1,0,0,100,6,19,3,'T~O',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('9',$newfieldid);

$newfieldid = $conn->getUniqueID("aicrm_field");
$query = "insert into aicrm_field values (16, $newfieldid, 'time_end','aicrm_activity', 1,'2','time_end','End Time',1,0,0,100,6,41,3,'T~M',1,null,'BAS')";
Execute($query);
populateFieldForSecurity('16',$newfieldid);

Execute("delete from aicrm_profile2tab where tabid=17");

Execute("delete from aicrm_org_share_action2tab where tabid=17");

Execute("delete from aicrm_def_org_share where tabid=17");

Execute("delete from aicrm_parenttabrel where tabid=17");
//Changes made for Activity merge with Calendar - Ends

//audit trial table
Execute("create table aicrm_audit_trial(auditid int(19) NOT NULL, userid int(19) default NULL, module varchar(255) default NULL, action varchar(255) default NULL, recordid varchar(20) default NULL, actiondate datetime default NULL, PRIMARY KEY (auditid)) ENGINE=InnoDB");

//Added after 5 rc release
Execute("alter table aicrm_account modify siccode varchar(50)");
Execute("update aicrm_field set typeofdata='V~O' where fieldname='siccode' and columnname='siccode' and tabid=6");

//changes made for CustomView and Reports - Activities changed to Calendar -- Starts
//Added to change the entitytype from Activities to Calendar for customview
Execute("update aicrm_crmentity set setype='Calendar' where setype='Activities'");
Execute("update aicrm_customview set entitytype='Calendar' where entitytype='Activities'");

//Added to change the primarymodule from Activities to Calendar for Reports
Execute("update aicrm_reportmodules set primarymodule='Calendar' where primarymodule='Activities'");
Execute("update  aicrm_reportmodules set primarymodule='PurchaseOrder' where primarymodule='Orders'");
Execute("update  aicrm_reportmodules set secondarymodules='PurchaseOrder' where secondarymodules='Orders'");

//we should change the Activities to Calendar in columnname values in customview and report related tables
$prefix = "aicrm_";
$change_cols_array = Array(
				"cvcolumnlist"=>"columnname",
				"cvstdfilter"=>"columnname",
				"cvadvfilter"=>"columnname",
				"selectcolumn"=>"columnname",
				"relcriteria"=>"columnname",
				"reportsortcol"=>"columnname",
				"reportdatefilter"=>"datecolumnname",
				"reportsummary"=>"columnname",
			  );

//This is to change Activities to Calendar
foreach($change_cols_array as $tablename => $columnname)
{
	$result = $conn->query("select $columnname from $prefix$tablename where $columnname like \"%Activities%\"");

	while($row = $conn->fetch_row($result))
	{
		if($row[$columnname] !='' && $row[$columnname] != 'none')
		{
			Execute("update $prefix$tablename set $columnname=\"".str_replace("Activities","Calendar",$row[$columnname])."\" where $columnname=\"$row[$columnname]\"");
		}
	}
}

//This is to change the Orders to PurchaseOrder
foreach($change_cols_array as $tablename => $columnname)
{
	$result1 = $conn->query("select $columnname from $prefix$tablename where $columnname like \"%Orders%\"");

	while($row1 = $conn->fetch_row($result1))
	{
		if($row1[$columnname] !='' && $row1[$columnname] != 'none')
		{
			Execute("update $prefix$tablename set $columnname=\"".str_replace("Orders","PurchaseOrder",$row1[$columnname])."\" where $columnname=\"$row1[$columnname]\"");
		}
	}
}
//we have to change the table name from activity to crmentity for customview activity description 
Execute('update aicrm_cvcolumnlist set columnname="aicrm_crmentity:description:description:Calendar_Description:V" where columnname="aicrm_activity:description:description:Calendar_Description:V"');
//we have to change the table name (alias) from activity to crmentiryCalendar for reports activity description
Execute('update aicrm_selectcolumn set columnname="aicrm_crmentityCalendar:description:Calendar_Description:description:V" where columnname="aicrm_activity:description:Calendar_Description:description:V"');
//changes made for CustomView and Reports - Activities changed to Calendar -- Ends

Execute("update aicrm_field set uitype = 16 where tabid=2 and uitype=111 and columnname='sales_stage'");

Execute("update aicrm_field set quickcreate=1,quickcreatesequence=null where fieldname='duration_hours' and tabid=16");

Execute("update aicrm_field set quickcreate=0,quickcreatesequence=5 where fieldname='due_date' and tabid=16");

//we have to add id, sortorderid and presence in all existing custom field pick list tables.
$cf_picklist_res = $conn->query("select fieldname from aicrm_field where uitype=15 and fieldname like 'cf_%'");
$noofPicklists = $conn->num_rows($cf_picklist_res);
for($i=0;$i<$noofPicklists;$i++)
{
	$fieldname = $conn->query_result($cf_picklist_res,$i,'fieldname');

	$tablename = "aicrm_".$fieldname;
	$idname = $fieldname."id";

	$alterquery = "alter table $tablename add column $idname int(19) auto_increment PRIMARY KEY FIRST, add column sortorderid int(19) default 0 NOT NULL, add column presence int(1) default 1 NOT NULL";
	Execute($alterquery);
}

$conn->query("alter table aicrm_organizationdetails drop primary key");
Execute("alter table aicrm_organizationdetails change column organizationame  organizationname varchar(60) NOT NULL");
Execute("alter table aicrm_organizationdetails ADD PRIMARY KEY (organizationname)");

//Activity related changes
Execute('update aicrm_field set typeofdata="D~M~OTH~GE~date_start~Start Date & Time", uitype=23 where fieldname="due_date" and tabid=16');
Execute('update aicrm_field set uitype=53 where tabid=16 and fieldname="assigned_user_id"');

Execute('update aicrm_field set typeofdata="D~M~OTH~GE~date_start~Start Date & Time", uitype=23 where fieldname="due_date" and tabid=9');
Execute('update aicrm_field set uitype=53 where tabid=9 and fieldname="assigned_user_id"');

Execute("alter table aicrm_activity change column subject subject varchar(100) NOT NULL");
Execute("alter table aicrm_activity change column activitytype activitytype varchar(50) NOT NULL");
Execute("alter table aicrm_activity change column date_start date_start date NOT NULL");
Execute("alter table aicrm_activity change column time_start time_start varchar(50) default NULL");
//Execute("alter table aicrm_activity change column visibility visibility varchar(50) NOT NULL default 'all'");

Execute("delete from aicrm_field where tabid=14 and fieldname='currency'");

//Product related changes
Execute('update aicrm_field set typeofdata="D~O~OTH~GE~sales_start_date~Sales Start Date" where tabid=14 and fieldname="sales_end_date"');
Execute('update aicrm_field set typeofdata="D~O~OTH~GE~start_date~Start Date" where tabid=14 and fieldname="expiry_date"');

//changes related to Incoming mail server settings
Execute("alter table aicrm_mail_accounts drop column showbody");

//change the Account relatedlist Activity label from Acivities to Activities
Execute("update aicrm_relatedlists set label='Activities' where tabid=6 and relation_id=3");

//change the fieldname from title to notes_title for notes and update in columnlist also
Execute("update aicrm_field set fieldname='notes_title' where tabid=8 and fieldname='title'");
Execute('update aicrm_cvcolumnlist set columnname="aicrm_notes:title:notes_title:Notes_Title:V" where columnname="aicrm_notes:title:title:Notes_Title:V"');

//change the sequence of Billing and Shipping address details for Inventory modules
Execute("update aicrm_field set sequence=5 where tabid in (20,21,22,23) and fieldname='bill_city'");
Execute("update aicrm_field set sequence=6 where tabid in (20,21,22,23) and fieldname='ship_city'");
Execute("update aicrm_field set sequence=7 where tabid in (20,21,22,23) and fieldname='bill_state'");
Execute("update aicrm_field set sequence=8 where tabid in (20,21,22,23) and fieldname='ship_state'");
Execute("update aicrm_field set sequence=9 where tabid in (20,21,22,23) and fieldname='bill_code'");
Execute("update aicrm_field set sequence=10 where tabid in (20,21,22,23) and fieldname='ship_code'");
Execute("update aicrm_field set sequence=11 where tabid in (20,21,22,23) and fieldname='bill_country'");
Execute("update aicrm_field set sequence=12 where tabid in (20,21,22,23) and fieldname='ship_country'");

//for aicrm_campaignleadrel  table
Execute("alter table aicrm_campaignleadrel DROP PRIMARY KEY");
Execute("alter table aicrm_campaignleadrel ADD PRIMARY KEY (campaignid,leadid)");

//for  aicrm_campaigncontrel  table
Execute("alter table aicrm_campaigncontrel DROP PRIMARY KEY");
Execute("alter table aicrm_campaigncontrel ADD PRIMARY KEY (campaignid,contactid)");

//for  aicrm_seactivityrel  table
Execute("alter table aicrm_seactivityrel DROP PRIMARY KEY");
Execute("alter table aicrm_seactivityrel ADD PRIMARY KEY (crmid,activityid)");

//change the block for vendor address details
Execute("update aicrm_field set fieldname='street' where tabid=18 and columnname='street'");
Execute("update aicrm_field set block=46 where tabid=18 and fieldname in ('city','country','pobox','postalcode','state','street')");

//change the calendar sharing access to private
Execute("update aicrm_def_org_share set permission=3 where tabid=9");
//Now sharing access is not available for Emails
Execute("delete from aicrm_def_org_share where tabid=10");

//we have to delete the entry from datashare_relatedmodules (Settings -> Sharing Access -> Add Privileges)
Execute("delete from aicrm_datashare_relatedmodules where tabid=10");
Execute("delete from aicrm_datashare_relatedmodules where relatedto_tabid=10");


//change the share_action_name in aicrm_org_share_action_mapping table for entry Public:Read,Create/Edit 
Execute('update aicrm_org_share_action_mapping set share_action_name="Public: Read, Create/Edit" where share_action_name="Public:Read,Create/Edit"');

//delete the entries from aicrm_profile2standardpermissions table for Emails
Execute("delete from aicrm_profile2standardpermissions where tabid=10");

//delete the tagcloud entry from users
Execute("delete from aicrm_field where tabid=29 and fieldname='tagcloud'");
Execute("alter table aicrm_users drop column tagcloud");

//we have missed to add the Received Shipment in postatus table
$sortorderid = $conn->query_result($conn->query("select max(sortorderid) as id from aicrm_postatus"),0,'id')+1;
Execute("insert into aicrm_postatus values('','Received Shipment',$sortorderid,1)");
Execute("alter table aicrm_attachments add index attachments_description_name_type_attachmentsid_idx (`description`,`type`,`attachmentsid`)");

//Added after 5.0 GA release

//In 4.2.3 we have assigned to group option only for Leads, HelpDesk and Activies and default None can be assigned. Now we will assign the unassigned entities to current user
Execute("update aicrm_crmentity set smownerid=1 where smownerid=0 and setype not in ('Leads','HelpDesk','Calendar')");

//CALCULATE Activity End Time (time_end)
//we have to calculate activity end time (time_end) based on start time (time_start) and duration (duration_hours, duration_minutes)
$sql = "select * from aicrm_activity";
$result = $conn->query($sql);
$num_rows = $conn->num_rows($result);
for($i=0;$i<$num_rows;$i++)
{
	//First we have to retrieve the time_start, duration_hours and duration_minutes and form as a date with time
	$activityid = $conn->query_result($result,$i,'activityid');
	$date_start = $conn->query_result($result,$i,'date_start');
	$time_start = $conn->query_result($result,$i,'time_start');
	$duration_hours = $conn->query_result($result,$i,'duration_hours');
	$duration_minutes = $conn->query_result($result,$i,'duration_minutes');

	if($duration_hours != '' && $duration_minutes != '')
	{
		$date_details = explode("-",$date_start);
		$start_year = $date_details[0];
		$start_month = $date_details[1];
		$start_date = $date_details[2];

		$start_details = explode(":",$time_start);
		$start_hour = $start_details[0];
		$start_minutes = $start_details[1];

		$full_duration = "$duration_hours:$duration_minutes:00";

		$start = date("Y-m-d H:i:s",mktime($start_hour, $start_minutes, 0, $start_month, $start_date, $start_year));
		$end = date("Y-m-d H:i:s",mktime($start_hour+$duration_hours, $start_minutes+$duration_minutes, 0, $start_month, $start_date, $start_year));

		$end_details = explode(" ",$end);
		$due_date = $end_details[0];

		$end_time_details = explode(":",$end_details[1]);
		$time_end = $end_time_details[0].":".$end_time_details[1];

		$update_query = "update aicrm_activity set due_date=\"$due_date\", time_end=\"$time_end\" where activityid=$activityid";

		$conn->query($update_query);
	}
}

//Added after 5.0.1
//we have to delete the entries from customview and report related tables for deleted customfields
include("modules/Migration/deleteCustomFields.php");




//Finally add aicrm_ prefix for all the entries in 'tablename' column in field table - 2nd August 2006
$field_res = $conn->query("select fieldid, tablename from aicrm_field");
for($field_count=0;$field_count<$conn->num_rows($field_res);$field_count++)
{
	//get the tablename
	$tablename = $conn->query_result($field_res,$field_count,'tablename');

	//check whether the table name has the prefix aicrm_
	if(substr($tablename, 0, 7) != 'aicrm_')
	{
		$tablename = "aicrm_$tablename";

		//Now update the tablename
		$fieldid = $conn->query_result($field_res,$field_count,'fieldid');
		Execute("update aicrm_field set tablename=\"$tablename\" where fieldid=$fieldid");
	}
}


$migrationlog->debug("\n\nDB Changes from 4.2.x to 5.0 GA -------- Ends \n\n");
			     

//Added to get the conversion rate and update for all records
?>
<script>
	function ajaxSaveResponse(response)
	{
		//alert(response.responseText);
		alert(alert_arr.CURRENCY_CHANGE_INFO);
	}

	if(!confirm(alert_arr.CURRENCY_CONVERSION_INFO))
	{
		getConversionRate('');
	}

	function getConversionRate(err)
	{
		var crate = prompt(err+"\nPlease enter the conversion rate of your currency");

		if(crate != 0 && crate > 0)
		{
			new Ajax.Request(
			'index.php',
			{queue: {position: 'end', scope: 'command'},
				method: 'post',
				postBody: 'module=Migration&action=updateCurrency&ajax=1&crate='+crate,
				onComplete: function(response)
					{
						//alert("Existing Currency values has been converted to base currency");
					}
			}
			);

			//var ajaxObj = new VtigerAjax(ajaxSaveResponse);
			//url = 'module=Migration&action=updateCurrency&ajax=1&crate='+crate;
			//ajaxObj.process("index.php?",url);
		}
		else
		{
			getConversionRate("Please give valid conversion rate ( > 0)");
		}
	}
</script>
<?php




//Function which is used to execute the query and display the result within tr tag. Also it stores the success and failure queries in a array where we can get this array to find the list of success and failure queries at the end of migraion.
function Execute($query)
{
	global $conn, $query_count, $success_query_count, $failure_query_count, $success_query_array, $failure_query_array;
	global $migrationlog;
	
	$status = $conn->query($query);
	
	$query_count++;
	if(is_object($status))
	{
		echo '
			<tr width="100%">
				<td width="25%" nowrap>'.get_class($status).'</td>
				<td width="5%"><font color="green"> S </font></td>
				<td width="70%">'.$query.'</td>
			</tr>';
		$success_query_array[$success_query_count++] = $query;
		$migrationlog->debug("Query Success ==> $query");
	}
	else
	{
		echo '
			<tr width="100%">
				<td width="25%">'.$status.'</td>
				<td width="5%"><font color="red"><b> F </b></font></td>
				<td width="70%">'.$query.'</td>
			</tr>';
		$failure_query_array[$failure_query_count++] = $query;
		$migrationlog->debug("Query Failed ==> $query \n Error is ==> [".$conn->database->ErrorNo()."]".$conn->database->ErrorMsg());
		//$migrationlog->debug("Error is ==> ".$conn->ErrorMsg());
	}
}

//Added on 23-12-2005 which is used to populate the aicrm_profile2field and aicrm_def_org_field table entries for the field per tab
//if we enter a field in aicrm_field table then we must populate that field in these table for security access
function populateFieldForSecurity($tabid,$fieldid)
{
	global $conn;

	$profileresult = $conn->query("select * from aicrm_profile");
	$countprofiles = $conn->num_rows($profileresult);
	for ($i=0;$i<$countprofiles;$i++)
	{
        	$profileid = $conn->query_result($profileresult,$i,'profileid');
	        $sqlProf2FieldInsert[$i] = 'insert into aicrm_profile2field values ('.$profileid.','.$tabid.','.$fieldid.',0,1)';
        	Execute($sqlProf2FieldInsert[$i]);
	}
	$def_query = "insert into aicrm_def_org_field values (".$tabid.",".$fieldid.",0,1)";
	Execute($def_query);
}

function localcreateRole($roleName,$parentRoleId,$roleProfileArray)
{
	global $migrationlog;
	$migrationlog->debug("Entering localcreateRole(".$roleName.",".$parentRoleId.",".$roleProfileArray.") method ...");

	global $conn;
	$parentRoleDetails = localgetRoleInformation($parentRoleId);
	$parentRoleInfo=$parentRoleDetails[$parentRoleId];
	$roleid_no=$conn->getUniqueId("aicrm_role");
        $roleId='H'.$roleid_no;
        $parentRoleHr=$parentRoleInfo[1];
        $parentRoleDepth=$parentRoleInfo[2];
        $nowParentRoleHr=$parentRoleHr.'::'.$roleId;
        $nowRoleDepth=$parentRoleDepth + 1;

	//Inserting aicrm_role into db
	$query="insert into aicrm_role values('".$roleId."','".$roleName."','".$nowParentRoleHr."',".$nowRoleDepth.")";
	$conn->query($query);

	//Inserting into aicrm_role2profile aicrm_table
	foreach($roleProfileArray as $profileId)
        {
                if($profileId != '')
                {
                        localinsertRole2ProfileRelation($roleId,$profileId);
                }
        }

	$migrationlog->debug("Exiting localcreateRole method ...");
	return $roleId;

}
function localgetRoleInformation($roleid)
{
	global $migrationlog;
	$migrationlog->debug("Entering localgetRoleInformation(".$roleid.") method ...");
	global $conn;
	
	$query = "select * from aicrm_role where roleid='".$roleid."'";
	$result = $conn->query($query);
	$rolename=$conn->query_result($result,0,'rolename');
	$parentrole=$conn->query_result($result,0,'parentrole');
	$roledepth=$conn->query_result($result,0,'depth');
	$parentRoleArr=explode('::',$parentrole);
	$immediateParent=$parentRoleArr[sizeof($parentRoleArr)-2];

	$roleDet=Array();
	$roleDet[]=$rolename;
	$roleDet[]=$parentrole;
	$roleDet[]=$roledepth;
	$roleDet[]=$immediateParent;
	$roleInfo=Array();
	$roleInfo[$roleid]=$roleDet;

	$migrationlog->debug("Exiting localgetRoleInformation method ...");

	return $roleInfo;	
}

function localinsertRole2ProfileRelation($roleId,$profileId)
{
	global $migrationlog;
	$migrationlog->debug("Entering localinsertRole2ProfileRelation(".$roleId.",".$profileId.") method ...");

	global $conn;
	$query="insert into aicrm_role2profile values('".$roleId."',".$profileId.")";
	$conn->query($query);

	$migrationlog->debug("Exiting localinsertRole2ProfileRelation method ...");
}

?>
