<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

require_once("include/database/PearDatabase.php");
$conn = PearDatabase::getInstance();

$ajax_val = $_REQUEST['ajax'];

if($ajax_val == 1)
{
	$crate = $_REQUEST['crate'];
	$conn->println('conversion rate = '.$crate);
	
	$query = "UPDATE aicrm_currency_info SET conversion_rate=? WHERE id=1";
	$result = $conn->pquery($query, array($crate));

	//array should be id || aicrm_fieldname => aicrm_tablename
	$modules_array = Array(
				"accountid||annualrevenue"	=>	"aicrm_account",
				
				"leadid||annualrevenue"		=>	"aicrm_leaddetails",

				"potentialid||amount"		=>	"aicrm_potential",

				"productid||unit_price"		=>	"aicrm_products",

				"salesorderid||salestax"	=>	"aicrm_salesorder",
				"salesorderid||adjustment"	=>	"aicrm_salesorder",
				"salesorderid||total"		=>	"aicrm_salesorder",
				"salesorderid||subtotal"	=>	"aicrm_salesorder",

				"purchaseorderid||salestax"	=>	"aicrm_purchaseorder",
				"purchaseorderid||adjustment"	=>	"aicrm_purchaseorder",
				"purchaseorderid||total"	=>	"aicrm_purchaseorder",
				"purchaseorderid||subtotal"	=>	"aicrm_purchaseorder",

				"quoteid||tax"			=>	"aicrm_quotes",
				"quoteid||adjustment"		=>	"aicrm_quotes",
				"quoteid||total"		=>	"aicrm_quotes",
				"quoteid||subtotal"		=>	"aicrm_quotes",

				"invoiceid||salestax"		=>	"aicrm_invoice",
				"invoiceid||adjustment"		=>	"aicrm_invoice",
				"invoiceid||total"		=>	"aicrm_invoice",
				"invoiceid||subtotal"		=>	"aicrm_invoice",
			      );

	foreach($modules_array as $fielddetails => $table)
	{
		$temp = explode("||",$fielddetails);
		$id_name = $temp[0];
		$fieldname = $temp[1];

		$res = $conn->query("select $id_name, $fieldname from $table");
		$record_count = $conn->num_rows($res);
		
		for($i=0;$i<$record_count;$i++)
		{
			$recordid = $conn->query_result($res,$i,$id_name);
			$old_value = $conn->query_result($res,$i,$fieldname);

			//calculate the new value
			$new_value = $old_value/$crate;//convertToDollar($old_value,$crate);
			$conn->println("old value = $old_value && new value = $new_value");

			$update_query = "update $table set $fieldname='".$new_value."' where $id_name=$recordid";
			$update_result = $conn->query($update_query);
		}
	}
}

?>