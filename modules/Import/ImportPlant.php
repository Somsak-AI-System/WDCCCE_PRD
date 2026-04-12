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
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contacts.php');
require_once('modules/Potentials/Potentials.php');
require_once('modules/Documents/Documents.php');
require_once('modules/Emails/Emails.php');
require_once('modules/Accounts/Accounts.php');
require_once('include/ComboUtil.php');
require_once('modules/Plant/Plant.php');

// Account is used to store aicrm_plant information.
class ImportPlant extends Plant {
	var $db;
	var $required_fields =  array("plant_name"=>1);
	
	// This is the list of the functions to run when importing
	var $special_functions =  array(
						"map_member_of","modseq_number",
				       );
	var $importable_fields = Array();

		/** Constructor which will set the importable_fields as $this->importable_fields[$key]=1 in this object where key is the fieldname in the field table
		 */
	function ImportPlant() {
		parent::Plant();
		$this->log = LoggerManager::getLogger('import_plant');
		$this->db = PearDatabase::getInstance();
		$this->db->println("IMP ImportPlant");
		$this->initImportableFields("Plant");
		
		$this->db->println($this->importable_fields);
	}

	/**     function used to map with existing Mamber Of(Plant) if the errors is map with an member of during import
         */
	function map_member_of()
	{
		
    }

	// Module Sequence Numbering	
	function modseq_number() {
		$this->column_fields['plant_no'] = '';	
	}
	// END

}



?>
