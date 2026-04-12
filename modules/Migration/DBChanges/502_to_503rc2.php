<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

//5.0.2 to 5.0.3 RC2 database changes - added on 05-01-07
//we have to use the current object (stored in PatchApply.php) to execute the queries
$adb = $_SESSION['adodb_current_object'];
$conn = $_SESSION['adodb_current_object'];

$migrationlog->debug("\n\nDB Changes from 5.0.2 to 5.0.3 RC2 -------- Starts \n\n");

$query_array = Array(
			"alter table aicrm_entityname add column entityidcolumn varchar(150)",

			"update aicrm_entityname set entityidcolumn='leadid' where tabid=7",
			"update aicrm_entityname set entityidcolumn='account_id' where tabid=6",
			"update aicrm_entityname set entityidcolumn='contact_id' where tabid=4",
			"update aicrm_entityname set entityidcolumn='potential_id' where tabid=2",
			"update aicrm_entityname set entityidcolumn='notesid' where tabid=8",
			"update aicrm_entityname set entityidcolumn='ticketid' where tabid=13",
			"update aicrm_entityname set entityidcolumn='activityid' where tabid=9",
			"update aicrm_entityname set entityidcolumn='activityid' where tabid=10",
			"update aicrm_entityname set entityidcolumn='product_id' where tabid=14",
			"update aicrm_entityname set entityidcolumn='id' where tabid=29",
			"update aicrm_entityname set entityidcolumn='invoiceid' where tabid=23",
			"update aicrm_entityname set entityidcolumn='quote_id' where tabid=20",
			"update aicrm_entityname set entityidcolumn='purchaseorderid' where tabid=21",
			"update aicrm_entityname set entityidcolumn='salesorder_id' where tabid=22",
			"update aicrm_entityname set entityidcolumn='vendor_id' where tabid=18",
			"update aicrm_entityname set entityidcolumn='pricebookid' where tabid=19",
			"update aicrm_entityname set entityidcolumn='campaignid' where tabid=26",
			"update aicrm_entityname set entityidcolumn='id' where tabid=15",
			"alter table aicrm_entityname MODIFY entityidcolumn varchar(150) NOT NULL",
			
			"update aicrm_field set fieldlabel='Part Number' where tabid=14 and fieldname='productcode'",


			"alter table aicrm_tab change customized customized integer(19)",
			"alter table aicrm_tab add column ownedby integer(19)",
			"ALTER TABLE aicrm_blocks ADD CONSTRAINT fk_1_aicrm_blocks FOREIGN KEY (tabid) REFERENCES aicrm_tab(tabid) ON DELETE CASCADE",
			"alter table aicrm_crmentity modify setype varchar(25)",

			"ALTER TABLE aicrm_customview ADD  INDEX customview_entitytype_idx  (entitytype)",
			"ALTER TABLE aicrm_customview ADD CONSTRAINT fk_1_aicrm_customview FOREIGN KEY (entitytype) REFERENCES aicrm_tab (name) ON DELETE CASCADE",

			"alter table aicrm_parenttabrel change parenttabid parenttabid integer(19)",
			"alter table aicrm_parenttabrel change tabid tabid integer(19)",

			"ALTER TABLE aicrm_parenttabrel ADD CONSTRAINT fk_1_aicrm_parenttabrel FOREIGN KEY (tabid) REFERENCES aicrm_tab(tabid) ON DELETE CASCADE",

			"ALTER TABLE aicrm_parenttabrel ADD CONSTRAINT fk_2_aicrm_parenttabrel FOREIGN KEY (parenttabid) REFERENCES aicrm_parenttab(parenttabid) ON DELETE CASCADE",

			"ALTER TABLE aicrm_entityname ADD CONSTRAINT fk_1_aicrm_entityname FOREIGN KEY (tabid) REFERENCES aicrm_tab(tabid) ON DELETE CASCADE",

			"alter table aicrm_parenttab engine=InnoDB",

			"update aicrm_tab set customized=0",
			"update aicrm_tab set ownedby=1",
			"update aicrm_tab set ownedby=0 where tabid in (2,4,6,7,9,13,16,20,21,22,23,26)",
			   
		    );

foreach($query_array as $query)
{
	ExecuteQuery($query);
}


ExecuteQuery("ALTER TABLE aicrm_users MODIFY user_password varchar(32)");

//Changes related to Product - Lead/Account/Contact/Potential relationship - Mickie - 13-01-2007
ExecuteQuery("delete from aicrm_field where tabid=14 and fieldname in ('parent_id','contact_id')");

//Before drop the contactid from products, we have to save this product - contact relationship in seproductsrel table
//ExecuteQuery("insert into aicrm_seproductsrel (select contactid, productid from aicrm_products where contactid is not NULL)");
//In above query, if there is any duplicate entry then execution stopped. So we will insert undeleted products one by one
$product_contact_res = $adb->query("select contactid, productid from aicrm_products inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_products.productid where aicrm_crmentity.deleted=0 and contactid != 0 and contactid is NOT NULL");
for($i=0;$i<$adb->num_rows($product_contact_res);$i++)
{
	$crmid = $adb->query_result($product_contact_res,$i,'contactid');
	$productid = $adb->query_result($product_contact_res,$i,'productid');

	$adb->query("insert into aicrm_seproductsrel values ($crmid , $productid)");
}
ExecuteQuery("alter table aicrm_products drop column contactid");


ExecuteQuery("insert into aicrm_relatedlists values(".$adb->getUniqueID('aicrm_relatedlists').",14,7,'get_leads',9,'Leads',0)");
ExecuteQuery("insert into aicrm_relatedlists values(".$adb->getUniqueID('aicrm_relatedlists').",14,6,'get_accounts',10,'Accounts',0)");
ExecuteQuery("insert into aicrm_relatedlists values(".$adb->getUniqueID('aicrm_relatedlists').",14,4,'get_contacts',11,'Contacts',0)");
ExecuteQuery("insert into aicrm_relatedlists values(".$adb->getUniqueID('aicrm_relatedlists').",14,2,'get_opportunities',12,'Potentials',0)");

ExecuteQuery("alter table aicrm_seproductsrel add column setype varchar(100)");
//we have to update setype for all existing entries which will be NULL before execute the following query
ExecuteQuery("update  aicrm_seproductsrel,aicrm_crmentity set aicrm_seproductsrel.setype=aicrm_crmentity.setype  where aicrm_crmentity.crmid=aicrm_seproductsrel.crmid");


ExecuteQuery("CREATE TABLE aicrm_version (id int(11) NOT NULL auto_increment, old_version varchar(30) default NULL, current_version varchar(30) default NULL, PRIMARY KEY  (id) ) ENGINE=InnoDB DEFAULT CHARSET=latin1");

ExecuteQuery("delete from aicrm_selectcolumn WHERE columnname LIKE '%aicrm_crmentityRelProducts%'");
//echo "<br><font color='red'>&nbsp; 5.0.2 ==> 5.0.3 Database changes has been done.</font><br>";

$migrationlog->debug("\n\nDB Changes from 5.0.2 to 5.0.3 RC2 -------- Ends \n\n");

?>