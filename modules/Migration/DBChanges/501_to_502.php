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


//5.0.2 database changes - added on 27-10-06
//we have to use the current object (stored in PatchApply.php) to execute the queries
$adb = $_SESSION['adodb_current_object'];
$conn = $_SESSION['adodb_current_object'];

$migrationlog->debug("\n\nDB Changes from 5.0.1 to 5.0.2 -------- Starts \n\n");

//Query added to show Manufacturer field in Products module
ExecuteQuery("update aicrm_field set displaytype=1,block=31 where tabid=14 and block=1");
ExecuteQuery("update aicrm_field set block=23,displaytype=1 where block=1 and displaytype=23 and tabid=10");
ExecuteQuery("update aicrm_field set block=22,displaytype=1 where block=1 and displaytype=22 and tabid=10");

//Added to rearange the attachment in HelpDesk
ExecuteQuery(" update aicrm_field set block=25,sequence=12 where tabid=13 and fieldname='filename'");

//Query added to as entityname,its tablename,its primarykey are saved in a table
ExecuteQuery(" CREATE TABLE `aicrm_entityname` (
	`tabid` int(19) NOT NULL default '0',
	`modulename` varchar(50) NOT NULL,
	`tablename` varchar(50) NOT NULL,
	`fieldname` varchar(150) NOT NULL,
	`entityidfield` varchar(150) NOT NULL,
	PRIMARY KEY (`tabid`),
	KEY `entityname_tabid_idx` (`tabid`)
)");

//Data Populated for the existing modules
ExecuteQuery("insert into aicrm_entityname values(7,'Leads','aicrm_leaddetails','lastname,firstname','leadid')");
ExecuteQuery("insert into aicrm_entityname values(6,'Accounts','aicrm_account','accountname','accountid')");
ExecuteQuery("insert into aicrm_entityname values(4,'Contacts','aicrm_contactdetails','lastname,firstname','contactid')");
ExecuteQuery("insert into aicrm_entityname values(2,'Potentials','aicrm_potential','potentialname','potentialid')");
ExecuteQuery("insert into aicrm_entityname values(8,'Notes','aicrm_notes','title','notesid')");
ExecuteQuery("insert into aicrm_entityname values(13,'HelpDesk','aicrm_troubletickets','title','ticketid')");
ExecuteQuery("insert into aicrm_entityname values(9,'Calendar','aicrm_activity','subject','activityid')");
ExecuteQuery("insert into aicrm_entityname values(10,'Emails','aicrm_activity','subject','activityid')");
ExecuteQuery("insert into aicrm_entityname values(14,'Products','aicrm_products','productname','productid')");
ExecuteQuery("insert into aicrm_entityname values(29,'Users','aicrm_users','last_name,first_name','id')");
ExecuteQuery("insert into aicrm_entityname values(23,'Invoice','aicrm_invoice','subject','invoiceid')");
ExecuteQuery("insert into aicrm_entityname values(20,'Quotes','aicrm_quotes','subject','quoteid')");
ExecuteQuery("insert into aicrm_entityname values(21,'PurchaseOrder','aicrm_purchaseorder','subject','purchaseorderid')");
ExecuteQuery("insert into aicrm_entityname values(22,'SalesOrder','aicrm_salesorder','subject','salesorderid')");
ExecuteQuery("insert into aicrm_entityname values(18,'Vendors','aicrm_vendor','vendorname','vendorid')");
ExecuteQuery("insert into aicrm_entityname values(19,'PriceBooks','aicrm_pricebook','bookname','pricebookid')");
ExecuteQuery("insert into aicrm_entityname values(26,'Campaigns','aicrm_campaign','campaignname','campaignid')");
ExecuteQuery("insert into aicrm_entityname values(15,'Faq','aicrm_faq','question','id')");

//added quantity in stock in product default listview - All
$res = $adb->query("select aicrm_cvcolumnlist.cvid from aicrm_cvcolumnlist inner join aicrm_customview on aicrm_cvcolumnlist.cvid=aicrm_customview.cvid where entitytype='Products' and viewname='All'");
if($adb->num_rows != 0)
{
	$cvid = $adb->query_result($res,0,'cvid');
	$adb->query("insert into aicrm_cvcolumnlist values($cvid,5,'aicrm_products:qtyinstock:qtyinstock:Products_Quantity_In_Stock:V')");
}


//echo "<br><font color='red'>&nbsp; 5.0/5.0.1 ==> 5.0.2 Database changes has been done.</font><br>";

$migrationlog->debug("\n\nDB Changes from 5.0.1 to 5.0.2 -------- Ends \n\n");

?>
