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
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

$imported_ids = array();


// Contact is used to store customer information.
class UsersLastImport extends SugarBean
{
    var $log;
    var $db;

    // Stored aicrm_fields
    var $id;
    var $assigned_user_id;
    var $bean_type;
    var $bean_id;

    var $table_name = "aicrm_users_last_import";
    var $object_name = "UsersLastImport";
    var $column_fields = Array(
        "id"
    , "assigned_user_id"
    , "bean_type"
    , "bean_id"
    , "deleted"
    );

    var $new_schema = true;

    var $additional_column_fields = Array();

    var $list_fields = Array();
    var $list_fields_name = Array();
    var $list_link_field;

    /**    Constructor
     */
    function UsersLastImport()
    {
        $this->log = LoggerManager::getLogger('UsersLastImport');
        $this->db = PearDatabase::getInstance();
    }

    /**    function used to delete the old entries for this user
     * @param int $user_id - user id to whom's last imported records to delete
     * @return void
     */
    function mark_deleted_by_user_id($user_id)
    {
        $query = "DELETE FROM $this->table_name  where assigned_user_id=?";
        $this->db->pquery($query, array($user_id), true, "Error deleting last imported records: ");
    }

    /**    function used to get the list query of the imported records
     * @param reference &$order_by - reference of the variable order_by to add with the query
     * @param reference &$where - where condition to add with the query
     * @return string $query - return the list query to get the imported records list
    */
    function create_list_query(&$order_by, &$where)
    {
        global $current_user;
        $query = '';
        
        $this->db->println("create list bean_type = " . $this->bean_type . " where = " . $where);

        if ($this->bean_type == 'Contacts') {
            $query = "SELECT distinct crmid,
			aicrm_account.accountname as accountname,
			aicrm_contactdetails.contactid,
			aicrm_contactdetails.accountid,				
			aicrm_contactdetails.*,
			aicrm_users.id as assigned_user_id,
				smownerid,
                                case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name
				FROM aicrm_contactdetails
				left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_contactdetails.contactid
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid  
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id 
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_account  ON aicrm_account.accountid=aicrm_contactdetails.accountid 
				WHERE aicrm_users_last_import.assigned_user_id= '{$current_user->id}'  
				AND aicrm_users_last_import.bean_type='Contacts' 
				AND aicrm_users_last_import.deleted=0  AND aicrm_crmentity.deleted=0";
				
        } else if ($this->bean_type == 'Accounts') {
            $query = "SELECT distinct aicrm_account.*, case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
				crmid, smownerid 
				FROM aicrm_account
				inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
				inner join aicrm_accountbillads on aicrm_crmentity.crmid=aicrm_accountbillads.accountaddressid
				left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid
			    left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
			    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE aicrm_users_last_import.assigned_user_id = '{$current_user->id}'
				AND aicrm_users_last_import.bean_type='Accounts'
				AND aicrm_users_last_import.deleted=0
				AND aicrm_crmentity.deleted=0";

        } else if ($this->bean_type == 'Potentials') {
            $query = "SELECT distinct aicrm_account.accountid accountid, aicrm_account.accountname accountname,
				case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, aicrm_crmentity.crmid, smownerid,
				aicrm_potential.* 
				FROM aicrm_potential 
				inner join  aicrm_crmentity
					on aicrm_crmentity.crmid=aicrm_potential.potentialid 
				left join aicrm_account
					on aicrm_account.accountid=aicrm_potential.related_to 
				left join aicrm_users
					ON aicrm_crmentity.smownerid=aicrm_users.id 
				LEFT JOIN aicrm_groups 
					ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				left join aicrm_users_last_import
					on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid 
				where aicrm_users_last_import.assigned_user_id='{$current_user->id}'
					AND aicrm_users_last_import.bean_type='Potentials'
					AND aicrm_users_last_import.bean_id=aicrm_crmentity.crmid
					AND aicrm_users_last_import.deleted=0
					AND aicrm_crmentity.deleted=0";

        } else if ($this->bean_type == 'Application') {
            $query = "SELECT distinct aicrm_applications.*, aicrm_crmentity.crmid,aicrm_applicationscf.*,
						case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
						smownerid 
						FROM aicrm_applications 
						left join aicrm_applicationscf on aicrm_applicationscf.applicationid=aicrm_applications.applicationid 
						inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_applications.applicationid 
						left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid			       	
						left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
						LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
						WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
						AND aicrm_users_last_import.bean_type='Application'
						AND aicrm_users_last_import.deleted=0
						AND aicrm_crmentity.deleted=0";
        } else if ($this->bean_type == 'Leads') {
            $query = "SELECT distinct aicrm_leaddetails.*, aicrm_leadscf.*, aicrm_crmentity.crmid, aicrm_leadaddress.*, aicrm_leadsubdetails.*,aicrm_leadsubdetails.website,
						case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
						smownerid 
						FROM aicrm_leaddetails 
						inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid 
						inner join aicrm_leadaddress on aicrm_crmentity.crmid=aicrm_leadaddress.leadaddressid 
						inner join aicrm_leadsubdetails on aicrm_crmentity.crmid=aicrm_leadsubdetails.leadsubscriptionid 
						inner join aicrm_leadscf on aicrm_leadscf.leadid=aicrm_leaddetails.leadid 
						left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid			       	
						left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
						LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
						WHERE 
						aicrm_users_last_import.assigned_user_id=
							'{$current_user->id}'
						AND aicrm_users_last_import.bean_type='Leads'
						AND aicrm_users_last_import.deleted=0
						AND aicrm_crmentity.deleted=0";
        } //Pavani: Query to retrieve trouble tickets, vendors data from database
        else if ($this->bean_type == 'HelpDesk') {
            $query = "SELECT distinct aicrm_troubletickets.*, aicrm_crmentity.crmid,
                                case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
                                smownerid
                                FROM aicrm_troubletickets
                                inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_troubletickets.ticketid
                                left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid
                                left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
                                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                                WHERE
                                aicrm_users_last_import.assigned_user_id=
                                        '{$current_user->id}'
                                AND aicrm_users_last_import.bean_type='HelpDesk'
                                AND aicrm_users_last_import.deleted=0
                                AND aicrm_crmentity.deleted=0";
                 
        } else if ($this->bean_type == 'Vendors') {
            $query = "SELECT distinct aicrm_vendor.*, aicrm_crmentity.crmid,
                                case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
                                smownerid
                                FROM aicrm_vendor
                                inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_vendor.vendorid
                                left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid
                                left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
                                LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
                                WHERE
                                aicrm_users_last_import.assigned_user_id=
                                        '{$current_user->id}'
                                AND aicrm_users_last_import.bean_type='Vendors'
                                AND aicrm_users_last_import.deleted=0
                                AND aicrm_crmentity.deleted=0";
        } //pavani...end
        else if ($this->bean_type == 'Products') {
            $query = "SELECT aicrm_crmentity.crmid, aicrm_products.*, aicrm_productcf.*
				FROM aicrm_products
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
				INNER JOIN aicrm_productcf ON aicrm_products.productid = aicrm_productcf.productid
				LEFT JOIN aicrm_users_last_import  ON aicrm_users_last_import.bean_id=aicrm_crmentity.crmid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				WHERE 
				aicrm_users_last_import.assigned_user_id= '{$current_user->id}'
				AND aicrm_users_last_import.bean_type='Products'
				AND aicrm_users_last_import.deleted=0
				AND aicrm_crmentity.deleted = 0";

        } else if ($this->bean_type == 'EmailTarget') {
            $query = "SELECT distinct aicrm_emailtargets.*, aicrm_crmentity.crmid,aicrm_emailtargetscf.*,
						case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
						smownerid 
						FROM aicrm_emailtargets 
						left join aicrm_emailtargetscf on aicrm_emailtargetscf.emailtargetid=aicrm_emailtargets.emailtargetid 
						inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_emailtargets.emailtargetid 
						left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid			       	
						left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
						LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
						WHERE 
						aicrm_users_last_import.assigned_user_id=
							'{$current_user->id}'
						AND aicrm_users_last_import.bean_type='EmailTarget'
						AND aicrm_users_last_import.deleted=0
						AND aicrm_crmentity.deleted=0";
        } else if ($this->bean_type == 'Branch') {
            $query = "SELECT distinct aicrm_branchs.*, aicrm_crmentity.crmid,aicrm_branchscf.*,
						case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
						smownerid 
						FROM aicrm_branchs 
						left join aicrm_branchscf on aicrm_branchscf.branchid=aicrm_branchs.branchid 
						inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_branchs.branchid 
						left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid			       	
						left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
						LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
						WHERE 
						aicrm_users_last_import.assigned_user_id=
							'{$current_user->id}'
						AND aicrm_users_last_import.bean_type='Branch'
						AND aicrm_users_last_import.deleted=0
						AND aicrm_crmentity.deleted=0";
        } else if ($this->bean_type == 'Building') {
            $query = "SELECT aicrm_crmentity.crmid, aicrm_building.*, aicrm_buildingcf.*, aicrm_account.*, aicrm_branchs.*
				FROM aicrm_building
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_building.buildingid
				INNER JOIN aicrm_buildingcf ON aicrm_building.buildingid = aicrm_buildingcf.buildingid
				LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_building.branchid
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_building.accountid
				LEFT JOIN aicrm_users_last_import ON aicrm_users_last_import.bean_id=aicrm_crmentity.crmid
				WHERE 
				aicrm_users_last_import.assigned_user_id= '{$current_user->id}'
				AND aicrm_users_last_import.bean_type='Building'
				AND aicrm_users_last_import.deleted=0
				AND aicrm_crmentity.deleted = 0";
            //echo $query;
        } else if ($this->bean_type == 'Installment') {
            $query = "SELECT aicrm_crmentity.crmid, aicrm_installment.*, aicrm_installmentcf.*, aicrm_account.*, aicrm_branchs.*, aicrm_products.*, aicrm_building.*
				FROM aicrm_installment
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_installment.installmentid
				INNER JOIN aicrm_installmentcf ON aicrm_installment.installmentid = aicrm_installmentcf.installmentid
				
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_installment.accountid
				LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_installment.branchid
				LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_installment.product_id
				LEFT JOIN aicrm_building ON aicrm_building.buildingid = aicrm_installment.buildingid
				LEFT JOIN aicrm_users_last_import ON aicrm_users_last_import.bean_id=aicrm_crmentity.crmid
								
				WHERE 
				aicrm_users_last_import.assigned_user_id= '{$current_user->id}'
				AND aicrm_users_last_import.bean_type='Installment'
				AND aicrm_users_last_import.deleted=0
				AND aicrm_crmentity.deleted = 0";

        } else if ($this->bean_type == 'Inspection') {
            $query = "SELECT aicrm_crmentity.crmid, aicrm_inspection.*, aicrm_inspectioncf.*, aicrm_account.*, aicrm_branchs.*, aicrm_products.*, aicrm_building.*
				FROM aicrm_inspection
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_inspection.inspectionid
				INNER JOIN aicrm_inspectioncf ON aicrm_inspectioncf.inspectionid = aicrm_inspection.inspectionid
				
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_inspection.accountid
				LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_inspection.branchid
				LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_inspection.product_id
				LEFT JOIN aicrm_building ON aicrm_building.buildingid = aicrm_inspection.buildingid
				LEFT JOIN aicrm_users_last_import ON aicrm_users_last_import.bean_id=aicrm_crmentity.crmid
								
				WHERE 
				aicrm_users_last_import.assigned_user_id= '{$current_user->id}'
				AND aicrm_users_last_import.bean_type='Inspection'
				AND aicrm_users_last_import.deleted=0
				AND aicrm_crmentity.deleted = 0";

        } else if ($this->bean_type == 'Transfer') {
            $query = "SELECT aicrm_crmentity.crmid, aicrm_transfer.*, aicrm_transfercf.*, aicrm_account.*, aicrm_branchs.*, aicrm_products.*, aicrm_building.*
				FROM aicrm_transfer
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_transfer.transferid
				INNER JOIN aicrm_transfercf ON aicrm_transfercf.transferid = aicrm_transfer.transferid
				
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_transfer.accountid
				LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_transfer.branchid
				LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_transfer.product_id
				LEFT JOIN aicrm_building ON aicrm_building.buildingid = aicrm_transfer.buildingid
				LEFT JOIN aicrm_users_last_import ON aicrm_users_last_import.bean_id=aicrm_crmentity.crmid
								
				WHERE 
				aicrm_users_last_import.assigned_user_id= '{$current_user->id}'
				AND aicrm_users_last_import.bean_type='Transfer'
				AND aicrm_users_last_import.deleted=0
				AND aicrm_crmentity.deleted = 0";

        } else if ($this->bean_type == 'PersonalLoan') {
            $query = "SELECT aicrm_crmentity.crmid, aicrm_personalloans.*,aicrm_personalloanscf.*
				FROM aicrm_personalloans
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_personalloans.personalloanid
				INNER JOIN aicrm_personalloanscf ON aicrm_personalloans.personalloanid = aicrm_personalloanscf.personalloanid
				
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_personalloans.accountid
				LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_personalloans.branchid
				LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_personalloans.product_id
				LEFT JOIN aicrm_building ON aicrm_building.buildingid = aicrm_personalloans.buildingid
				LEFT JOIN aicrm_booking ON aicrm_booking.bookingid = aicrm_personalloans.bookingid
				LEFT JOIN aicrm_users_last_import ON aicrm_users_last_import.bean_id=aicrm_crmentity.crmid
								
				WHERE 
				aicrm_users_last_import.assigned_user_id= '{$current_user->id}'
				AND aicrm_users_last_import.bean_type='PersonalLoan'
				AND aicrm_users_last_import.deleted=0
				AND aicrm_crmentity.deleted = 0";

        } else if ($this->bean_type == 'Premium') {
            $query = "SELECT distinct aicrm_premiums.*, aicrm_crmentity.crmid,aicrm_premiumscf.*,
						case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
						smownerid 
						FROM aicrm_premiums 
						left join aicrm_premiumscf on aicrm_premiumscf.premiumid=aicrm_premiums.premiumid 
						inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_premiums.premiumid 
						left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid			       	
						left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
						LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
						WHERE 
						aicrm_users_last_import.assigned_user_id=
							'{$current_user->id}'
						AND aicrm_users_last_import.bean_type='Premium'
						AND aicrm_users_last_import.deleted=0
						AND aicrm_crmentity.deleted=0";
        } else if ($this->bean_type == 'Coupon') {
            $query = "SELECT distinct aicrm_coupons.*, aicrm_crmentity.crmid,aicrm_couponscf.*,
						case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
						smownerid 
						FROM aicrm_coupons 
						left join aicrm_couponscf on aicrm_couponscf.couponid=aicrm_coupons.couponid 
						inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_coupons.couponid 
						left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid			       	
						left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
						LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
						WHERE 
						aicrm_users_last_import.assigned_user_id=
							'{$current_user->id}'
						AND aicrm_users_last_import.bean_type='Coupon'
						AND aicrm_users_last_import.deleted=0
						AND aicrm_crmentity.deleted=0";
        } else if ($this->bean_type == 'PriceList') {
            $query = "SELECT distinct aicrm_pricelists.*, aicrm_crmentity.crmid,aicrm_pricelistscf.*,
						case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
						smownerid 
						FROM aicrm_pricelists 
						left join aicrm_pricelistscf on aicrm_pricelistscf.pricelistid=aicrm_pricelists.pricelistid 
						inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_pricelists.pricelistid 
						left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid			       	
						left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
						LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
						WHERE 
						aicrm_users_last_import.assigned_user_id=
							'{$current_user->id}'
						AND aicrm_users_last_import.bean_type='PriceList'
						AND aicrm_users_last_import.deleted=0
						AND aicrm_crmentity.deleted=0";
        } else if ($this->bean_type == 'ServiceRequest') {
            $query = "SELECT distinct aicrm_servicerequestss.*, aicrm_crmentity.crmid,aicrm_servicerequestsscf.*,
						case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
						smownerid 
						FROM aicrm_servicerequestss 
						left join aicrm_servicerequestsscf on aicrm_servicerequestsscf.servicerequestsid=aicrm_servicerequestss.servicerequestsid 
						inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_servicerequestss.servicerequestsid 
						left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid			       	
						left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
						LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
						WHERE 
						aicrm_users_last_import.assigned_user_id=
							'{$current_user->id}'
						AND aicrm_users_last_import.bean_type='ServiceRequest'
						AND aicrm_users_last_import.deleted=0
						AND aicrm_crmentity.deleted=0";

        } else if ($this->bean_type == 'Resale') {
            $query = "SELECT distinct aicrm_resale.*, aicrm_crmentity.crmid,aicrm_resalecf.*, aicrm_account.*, aicrm_products.*, 
						case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name,
						smownerid 
						FROM aicrm_resale 
						left join aicrm_resalecf on aicrm_resalecf.resaleid=aicrm_resale.resaleid 
						inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_resale.resaleid 
						left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid			       	
						left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
						LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
						LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_resale.accountid
						LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_resale.product_id
						WHERE 
						aicrm_users_last_import.assigned_user_id=
							'{$current_user->id}'
						AND aicrm_users_last_import.bean_type='Resale'
						AND aicrm_users_last_import.deleted=0
						AND aicrm_crmentity.deleted=0";

        } else if ($this->bean_type == 'Agreement') {
            $query = "SELECT aicrm_crmentity.crmid, aicrm_agreement.*, aicrm_agreementcf.* , aicrm_account.*, aicrm_branchs.*, aicrm_products.*, aicrm_building.*
				FROM aicrm_agreement
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_agreement.agreementid
				INNER JOIN aicrm_agreementcf ON aicrm_agreement.agreementid = aicrm_agreementcf.agreementid
				
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_agreement.accountid
				LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_agreement.branchid
				LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_agreement.product_id
				LEFT JOIN aicrm_building ON aicrm_building.buildingid = aicrm_agreement.buildingid
				LEFT JOIN aicrm_users_last_import ON aicrm_users_last_import.bean_id=aicrm_crmentity.crmid
								
				WHERE 
				aicrm_users_last_import.assigned_user_id= '{$current_user->id}'
				AND aicrm_users_last_import.bean_type='Agreement'
				AND aicrm_users_last_import.deleted=0
				AND aicrm_crmentity.deleted = 0";
            //echo $query;

        } else if ($this->bean_type == 'Booking') {
            $query = "SELECT aicrm_crmentity.crmid, aicrm_booking.*, aicrm_bookingcf.* , aicrm_account.*, aicrm_branchs.*, aicrm_products.*, aicrm_building.*
				FROM aicrm_booking
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_booking.bookingid
				INNER JOIN aicrm_bookingcf ON aicrm_booking.bookingid = aicrm_bookingcf.bookingid
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_booking.accountid
				LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_booking.branchid
				LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_booking.product_id
				LEFT JOIN aicrm_building ON aicrm_building.buildingid = aicrm_booking.buildingid
				LEFT JOIN aicrm_users_last_import ON aicrm_users_last_import.bean_id=aicrm_crmentity.crmid
				
				WHERE 
				aicrm_users_last_import.assigned_user_id= '{$current_user->id}'
				AND aicrm_users_last_import.bean_type='Booking'
				AND aicrm_users_last_import.deleted=0
				AND aicrm_crmentity.deleted = 0";
            //echo $query;

        } else if ($this->bean_type == 'Competitor') {
            $query = "SELECT distinct aicrm_competitor.*, aicrm_crmentity.crmid,aicrm_competitorcf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_competitor 
            left join aicrm_competitorcf on aicrm_competitorcf.competitorid=aicrm_competitor.competitorid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_competitor.competitorid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='Competitor'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";
            //echo $query; exit;

        } else if ($this->bean_type == 'Errors') {
            $query = "SELECT distinct aicrm_errors.*, aicrm_crmentity.crmid,aicrm_errorscf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_errors 
            left join aicrm_errorscf on aicrm_errorscf.errorsid=aicrm_errors.errorsid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_errors.errorsid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='Errors'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";
            //echo $query; exit;

        } else if ($this->bean_type == 'Serial') {
            $query = "SELECT distinct aicrm_serial.*, aicrm_crmentity.crmid,aicrm_serialcf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_serial 
            left join aicrm_serialcf on aicrm_serialcf.serialid=aicrm_serial.serialid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_serial.serialid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='Errors'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";
            //echo $query; exit;

        }else if ($this->bean_type == 'Sparepart') {
            $query = "SELECT distinct aicrm_sparepart.*, aicrm_crmentity.crmid, aicrm_sparepartcf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_sparepart 
            left join aicrm_sparepartcf on aicrm_sparepartcf.sparepartid=aicrm_sparepart.sparepartid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_sparepart.sparepartid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='Sparepart'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";

        }else if($this->bean_type == 'Campaigns'){
			$query = "SELECT aicrm_crmentity.crmid, aicrm_campaign.*, aicrm_campaignscf.*
				FROM aicrm_campaign
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_campaign.campaignid
				INNER JOIN aicrm_campaignscf ON aicrm_campaign.campaignid = aicrm_campaignscf.campaignid
				LEFT JOIN aicrm_users_last_import ON aicrm_users_last_import.bean_id=aicrm_crmentity.crmid
				left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            	LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE 
				aicrm_users_last_import.assigned_user_id= '{$current_user->id}'
				AND aicrm_users_last_import.bean_type='Campaigns'
				AND aicrm_users_last_import.deleted=0
				AND aicrm_crmentity.deleted = 0";
			//echo $query; exit;

		} else if ($this->bean_type == 'Plant') {
            $query = "SELECT distinct aicrm_plant.*, aicrm_crmentity.crmid,aicrm_plantcf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_plant
            left join aicrm_plantcf on aicrm_plantcf.plantid=aicrm_plant.plantid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_plant.plantid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='Plant'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";
            
            //echo $query; exit;
        } else if ($this->bean_type == 'Deal') {
            $query = "SELECT distinct aicrm_deal.*, aicrm_crmentity.crmid,aicrm_dealcf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_deal
            left join aicrm_dealcf on aicrm_dealcf.dealid=aicrm_deal.dealid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_deal.dealid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='Deal'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";

        } else if ($this->bean_type == 'Promotion') {
            $query = "SELECT distinct aicrm_promotion.*, aicrm_crmentity.crmid,aicrm_promotioncf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_promotion
            left join aicrm_promotioncf on aicrm_promotioncf.promotionid=aicrm_promotion.promotionid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_promotion.promotionid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='Promotion'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";

        } else if ($this->bean_type == 'Promotionvoucher') {
            $query = "SELECT distinct aicrm_promotionvoucher.*, aicrm_crmentity.crmid,aicrm_promotionvouchercf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_promotionvoucher
            left join aicrm_promotionvouchercf on aicrm_promotionvouchercf.promotionvoucherid=aicrm_promotionvoucher.promotionvoucherid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_promotionvoucher.promotionvoucherid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='Promotionvoucher'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";

        } else if ($this->bean_type == 'Voucher') {
            $query = "SELECT distinct aicrm_voucher.*, aicrm_crmentity.crmid,aicrm_vouchercf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_voucher
            left join aicrm_vouchercf on aicrm_vouchercf.voucherid=aicrm_voucher.voucherid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_voucher.voucherid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='Voucher'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";

        } else if ($this->bean_type == 'Competitorproduct') {
            $query = "SELECT distinct aicrm_competitorproduct.*, aicrm_crmentity.crmid, aicrm_competitorproductcf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_competitorproduct
            left join aicrm_competitorproductcf on aicrm_competitorproductcf.competitorproductid=aicrm_competitorproduct.competitorproductid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_competitorproduct.competitorproductid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='Competitorproduct'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";

        } else if ($this->bean_type == 'Premuimproduct') {
            $query = "SELECT distinct aicrm_premuimproduct.*, aicrm_crmentity.crmid, aicrm_premuimproductcf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_premuimproduct
            left join aicrm_premuimproductcf on aicrm_premuimproductcf.premuimproductid=aicrm_premuimproduct.premuimproductid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_premuimproduct.premuimproductid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='Premuimproduct'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";

        } else if ($this->bean_type == 'Service') {
            $query = "SELECT distinct aicrm_service.*, aicrm_crmentity.crmid, aicrm_servicecf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_service
            left join aicrm_servicecf on aicrm_servicecf.serviceid=aicrm_service.serviceid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_service.serviceid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='Service'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";

        } else if ($this->bean_type == 'Faq') {
            $query = "SELECT distinct aicrm_faq.*, aicrm_crmentity.crmid, aicrm_faqcf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_faq
            left join aicrm_faqcf on aicrm_faqcf.faqid=aicrm_faq.faqid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_faq.faqid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='Faq'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";

        } else if ($this->bean_type == 'KnowledgeBase') {
            $query = "SELECT distinct aicrm_knowledgebase.*, aicrm_crmentity.crmid, aicrm_knowledgebasecf.*,
            case when (aicrm_users.user_name not like '') then aicrm_users.user_name else aicrm_groups.groupname end as user_name, smownerid 
            FROM aicrm_knowledgebase
            left join aicrm_knowledgebasecf on aicrm_knowledgebasecf.knowledgebaseid=aicrm_knowledgebase.knowledgebaseid 
            inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_knowledgebase.knowledgebaseid 
            left join aicrm_users_last_import on aicrm_users_last_import.bean_id=aicrm_crmentity.crmid	       	
            left join aicrm_users ON aicrm_crmentity.smownerid=aicrm_users.id
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            WHERE aicrm_users_last_import.assigned_user_id='{$current_user->id}'
            AND aicrm_users_last_import.bean_type='KnowledgeBase'
            AND aicrm_users_last_import.deleted=0
            AND aicrm_crmentity.deleted=0";

        }else {
            require_once("modules/$this->bean_type/$this->bean_type.php");
            $bean_focus = new $this->bean_type();
            $query = $bean_focus->create_import_query($this->bean_type);
        }

		// END
		//echo $query."<br>";
        return $query;

    }

    /*
    function list_view_parse_additional_sections(&$list_form)
    {
        if ($this->bean_type == "Contacts")
        {
                    if( isset($this->yahoo_id) && $this->yahoo_id != '')
            {
                            $list_form->parse("main.row.yahoo_id");
            }
                    else
            {
                            $list_form->parse("main.row.no_yahoo_id");
            }
        }
                return $list_form;

        }
    */

    /**    function used to delete (update deleted=1 in crmentity table) the last imported records of the current user
     * @param int $user_id - user id, whose last imported records want to be deleted
     * @return int $count - return the number of total deleted records (contacts, accounts, opportunities, leads and products)
     */
    function undo($user_id)
    {
        $count = 0;

        $count += $this->undo_contacts($user_id);
        $count += $this->undo_accounts($user_id);
        $count += $this->undo_opportunities($user_id);
        $count += $this->undo_leads($user_id);
        $count += $this->undo_products($user_id);
        $count += $this->undo_HelpDesk($user_id);
        $count += $this->undo_Vendors($user_id);
        $count += $this->undo_Sparepart($user_id);
        $count += $this->undo_Competitor($user_id);
        $count += $this->undo_Campaigns($user_id);
        $count += $this->undo_Plant($user_id);
        $count += $this->undo_Deal($user_id);
        $count += $this->undo_Promotion($user_id);
        $count += $this->undo_Voucher($user_id);
        $count += $this->undo_Competitorproduct($user_id);
        $count += $this->undo_Premuimproduct($user_id);
        $count += $this->undo_Promotionvoucher($user_id);
        $count += $this->undo_PriceList($user_id);
        $count += $this->undo_Service($user_id);
        $count += $this->undo_Faq($user_id);
        $count += $this->undo_KnowledgeBase($user_id);
        return $count;
    }

    /**    function used to delete (update deleted=1 in crmentity table) the last imported contacts of the current user
     * @param int $user_id - user id, whose last imported contacts want to be deleted
     * @return int $count - return the number of deleted contacts
     */
    function undo_contacts($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Contacts' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: " . mysql_error());

            $count++;

        }
        return $count;
    }

    function undo_Sparepart($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Sparepart' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: " . mysql_error());

            $count++;

        }
        return $count;
    }

    function undo_Competitor($user_id)
    {
    	$count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Competitor' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: " . mysql_error());
            $count++;
        }
        return $count;

    }

    function undo_Competitorproduct($user_id)
    {
    	$count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Competitorproduct' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: " . mysql_error());
            $count++;
        }
        return $count;

    }

    function undo_Premuimproduct($user_id)
    {
    	$count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Premuimproduct' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: " . mysql_error());
            $count++;
        }
        return $count;

    }

    function undo_Service($user_id)
    {
    	$count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Service' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: " . mysql_error());
            $count++;
        }
        return $count;

    }

    function undo_Faq($user_id)
    {
    	$count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Faq' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: " . mysql_error());
            $count++;
        }
        return $count;

    }
    
    function undo_KnowledgeBase($user_id)
    {
    	$count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='KnowledgeBase' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: " . mysql_error());
            $count++;
        }
        return $count;

    }
    
    /**    function used to delete (update deleted=1 in crmentity table) the last imported leads of the current user
     * @param int $user_id - user id, whose last imported leads want to be deleted
     * @return int $count - return the number of deleted leads
     */
    function undo_leads($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Leads' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: " . mysql_error());

            $count++;

        }
        return $count;
    }

	//Pavani: Function to cancel latest import of trouble tickets and vendors of the particular user

    /**     function used to delete (update deleted=1 in crmentity table) the last imported tickets of the current user
     * @param int $user_id - user id, whose last imported tickets want to be deleted
     * @return int $count - return the number of deleted tickets
     */
    function undo_HelpDesk($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id='$user_id' AND bean_type='HelpDesk' AND deleted=0";

        $this->log->info($query1);

        $result1 = $this->db->query($query1) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid='{$row1['bean_id']}'";

            $this->log->info($query2);

            $result2 = $this->db->query($query2) or die("Error undoing last import: " . mysql_error());

            $count++;

        }
        return $count;
    }

    /**     function used to delete (update deleted=1 in crmentity table) the last imported vendors of the current user
     * @param int $user_id - user id, whose last imported vendors want to be deleted
     * @return int $count - return the number of deleted vendors
     */
    function undo_Vendors($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id='$user_id' AND bean_type='Vendors' AND deleted=0";

        $this->log->info($query1);

        $result1 = $this->db->query($query1) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid='{$row1['bean_id']}'";

            $this->log->info($query2);

            $result2 = $this->db->query($query2) or die("Error undoing last import: " . mysql_error());

            $count++;

        }
        return $count;
    }

    /**    function used to delete (update deleted=1 in crmentity table) the last imported accounts of the current user
     * @param int $user_id - user id, whose last imported accounts want to be deleted
     * @return int $count - return the number of deleted accounts
     */
    function undo_accounts($user_id)
    {
        // this should just be a loop foreach module type
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Accounts' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: " . mysql_error());

            $count++;

        }
        return $count;
    }

    /**    function used to delete (update deleted=1 in crmentity table) the last imported potentials of the current user
     * @param int $user_id - user id, whose last imported potentials want to be deleted
     * @return int $count - return the number of deleted potentials
     */
    function undo_opportunities($user_id)
    {
        // this should just be a loop foreach module type
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Potentials' AND deleted=0";

        $this->log->info($query1);

        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: " . mysql_error());

            $count++;

        }
        return $count;
    }

    /**    function used to delete (update deleted=1 in crmentity table) the last imported products of the current user
     * @param int $user_id - user id, whose last imported products want to be deleted
     * @return int $count - return the number of deleted products
     */
    function undo_products($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Products' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: " . mysql_error());

        while ($row1 = $this->db->fetchByAssoc($result1)) {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: " . mysql_error());

            $count++;
        }
        return $count;
    }
    
    function undo_Errors($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Errors' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: ".mysql_error());

        while ( $row1 = $this->db->fetchByAssoc($result1))
        {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: ".mysql_error());
            $count++;
        }
        return $count;
    }
    function undo_Serial($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Serial' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: ".mysql_error());

        while ( $row1 = $this->db->fetchByAssoc($result1))
        {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: ".mysql_error());
            $count++;
        }
        return $count;
    }
    

    function undo_Campaigns($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Campaigns' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: ".mysql_error());

        while ( $row1 = $this->db->fetchByAssoc($result1))
        {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: ".mysql_error());
            $count++;
        }
        return $count;
    }

    function undo_Plant($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Plant' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: ".mysql_error());

        while ( $row1 = $this->db->fetchByAssoc($result1))
        {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: ".mysql_error());
            $count++;
        }
        return $count;
    }

    function undo_Deal($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Deal' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: ".mysql_error());

        while ( $row1 = $this->db->fetchByAssoc($result1))
        {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: ".mysql_error());
            $count++;
        }
        return $count;
    }

    function undo_Promotion($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Promotion' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: ".mysql_error());

        while ( $row1 = $this->db->fetchByAssoc($result1))
        {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: ".mysql_error());
            $count++;
        }
        return $count;
    }

    function undo_Promotionvoucher($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Promotionvoucher' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: ".mysql_error());

        while ( $row1 = $this->db->fetchByAssoc($result1))
        {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: ".mysql_error());
            $count++;
        }
        return $count;
    }
    
    function undo_PriceList($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='PriceList' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: ".mysql_error());

        while ( $row1 = $this->db->fetchByAssoc($result1))
        {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: ".mysql_error());
            $count++;
        }
        return $count;
    }

    function undo_Voucher($user_id)
    {
        $count = 0;
        $query1 = "select bean_id from aicrm_users_last_import where assigned_user_id=? AND bean_type='Voucher' AND deleted=0";
        $this->log->info($query1);
        $result1 = $this->db->pquery($query1, array($user_id)) or die("Error getting last import for undo: ".mysql_error());

        while ( $row1 = $this->db->fetchByAssoc($result1))
        {
            $query2 = "update aicrm_crmentity set deleted=1 where crmid=?";
            $this->log->info($query2);
            $result2 = $this->db->pquery($query2, array($row1['bean_id'])) or die("Error undoing last import: ".mysql_error());
            $count++;
        }
        return $count;
    }
    

}


?>
