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
 * $Header$
 * Description:  TODO: To be written.
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('modules/Contacts/Contacts.php');
require_once('modules/Import/UsersLastImport.php');
require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php');

// Contact is used to store customer information.
class ImportContact extends Contacts {
	// these are aicrm_fields that may be set on import
	// but are to be processed and incorporated
	// into aicrm_fields of the parent class
	var $db;
	var $full_name;
	var $primary_address_street_2;
	var $primary_address_street_3;
	var $alt_address_street_2;
	var $alt_address_street_3;

    // This is the list of the functions to run when importing
    var $special_functions =  array(
		//"get_names_from_full_name"
		//"add_create_account",
		"map_reports_to",
		"modseq_number",
	);

	// Module Sequence Numbering	
	function modseq_number() {
		$this->column_fields['contact_no'] = '';
	}
	// END

	/**	function used to create or map with existing account if the contact has mapped with an account during import
	 */
	function add_create_account(){
		global $adb;
		// global is defined in UsersLastImport.php
		global $imported_ids;
        global $current_user;

		$acc_name = $this->column_fields['account_id'];
		$adb->println("contact add_create acc=".$acc_name);

		if ((! isset($acc_name) || $acc_name == '') )
		{
			return; 
		}
        $arr = array();
		// check if it already exists
        $focus = new Accounts();
		$query = '';
		// if user is defining the aicrm_account id to be associated with this contact..
		//Modified to remove the spaces at first and last in aicrm_account name -- after 4.2 patch 2
		$acc_name = trim($acc_name);

		//Modified the query to get the available account only ie., which is not deleted
		$query = "select aicrm_crmentity.deleted, aicrm_account.* from aicrm_account, aicrm_crmentity WHERE accountname=? and aicrm_crmentity.crmid =aicrm_account.accountid and aicrm_crmentity.deleted=0";
		$result = $adb->pquery($query, array($acc_name));

        $row = $this->db->fetchByAssoc($result, -1, false);

		$adb->println("fetched account");
		$adb->println($row);

		// we found a row with that id
        if (isset($row['accountid']) && $row['accountid'] != -1)
        {
			$focus->id = $row['accountid'];
			$adb->println("Account row exists - using same id=".$focus->id);
        }

		// if we didnt find the aicrm_account, so create it
		if (! isset($focus->id) || $focus->id == '')
		{
			$adb->println("Createing new aicrm_account");
			$focus->column_fields['accountname'] = $acc_name;
			$focus->column_fields['assigned_user_id'] = $current_user->id;
			$focus->column_fields['modified_user_id'] = $current_user->id;

			//$focus->saveentity("Accounts");
			$focus->save("Accounts");
			$acc_id = $focus->id;

			$adb->println("New Account created id=".$focus->id);

			// avoid duplicate mappings:
			if (! isset( $imported_ids[$acc_id]) )
			{
				$adb->println("inserting aicrm_users last import for aicrm_accounts");
				// save the new aicrm_account as a aicrm_users_last_import
				$last_import = new UsersLastImport();
				$last_import->assigned_user_id = $current_user->id;
				$last_import->bean_type = "Accounts";
				$last_import->bean_id = $focus->id;
				$last_import->save();
				$imported_ids[$acc_id] = 1;
			}
		}

		$adb->println("prev contact accid=".$this->column_fields["account_id"]);
		// now just link the aicrm_account
		$this->column_fields["account_id"] = $focus->id;
		$adb->println("curr contact accid=".$this->column_fields["account_id"]);

    }

	/**function used to map with existing Reports To(Contact) if the contact is map with reports to during import*/
	function map_reports_to()
	{
		global $adb;

		$contact_name = $this->column_fields['contact_id'];
		$adb->println("Entering map_reports_to contact_id=".$contact_name);

		if ((! isset($contact_name) || $contact_name == '') )
		{
			$adb->println("Exit map_reports_to. Contact Name(Reports To) not set for this entity.");
			return; 
		}

		$contact_name = trim($contact_name);

		//Query to get the available Contact (Reports To) which is not deleted
		$query = "select contactid from aicrm_contactdetails inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid WHERE concat(aicrm_contactdetails.lastname,' ',aicrm_contactdetails.firstname) = ? and aicrm_crmentity.deleted=0";
		$contact_id = $adb->query_result($adb->pquery($query, array($contact_name)),0,'contactid');

		if($contact_id == '' || !isset($contact_id))
			$contact_id = 0;

		$this->column_fields['contact_id'] = $contact_id;

		$adb->println("Exit map_reports_to. Fetched Contact (Reports To) for '".$contact_name."' and the contactid = $contact_id");
    }

	var $importable_fields = Array();
		
	/** Constructor which will set the importable_fields as $this->importable_fields[$key]=1 in this object where key is the fieldname in the field table
	 */
	function ImportContact() {
		parent::Contacts();
		$this->log = LoggerManager::getLogger('import_contact');
		$this->db = PearDatabase::getInstance();
		$this->db->println("IMP ImportContact");
		$this->initImportableFields("Contacts");
		$this->db->println($this->importable_fields);
	}

}



?>
