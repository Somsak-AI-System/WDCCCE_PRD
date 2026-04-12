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

/**Function to get the top 5 Accounts order by Amount in Descending Order
 *return array $values - array with the title, header and entries like  Array('Title'=>$title,'Header'=>$listview_header,'Entries'=>$listview_entries) where as listview_header and listview_entries are arrays of header and entity values which are returned from function getListViewHeader and getListViewEntries
*/
function getTopAccounts($maxval,$calCnt)
{
	$log = LoggerManager::getLogger('top accounts_list');
	$log->debug("Entering getTopAccounts() method ...");
	require_once("data/Tracker.php");
	require_once('modules/Potentials/Potentials.php');
	require_once('include/logging.php');
	require_once('include/ListView/ListView.php');
	global $app_strings;
	global $adb;
	global $current_language;
	global $current_user;
	$current_module_strings = return_module_language($current_language, "Accounts");

    require('user_privileges/user_privileges_'.$current_user->id.'.php');
    require('user_privileges/sharing_privileges_'.$current_user->id.'.php');

	$list_query = "select aicrm_account.accountid, aicrm_account.accountname, aicrm_account.tickersymbol, sum(aicrm_potential.amount) as amount from aicrm_potential inner join aicrm_crmentity on (aicrm_potential.potentialid=aicrm_crmentity.crmid) left join aicrm_account on (aicrm_potential.related_to=aicrm_account.accountid) left join aicrm_groups on (aicrm_groups.groupid = aicrm_crmentity.smownerid) where aicrm_crmentity.deleted=0 AND aicrm_crmentity.smownerid='".$current_user->id."' and aicrm_potential.sales_stage not in ('Closed Won', 'Closed Lost','".$app_strings['LBL_CLOSE_WON']."','".$app_strings['LBL_CLOSE_LOST']."')";
	if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[6] == 3)
	{
		$sec_parameter=getListViewSecurityParameter('Accounts');
		$list_query .= $sec_parameter;

	}
	$list_query .= " group by aicrm_account.accountid, aicrm_account.accountname, aicrm_account.tickersymbol order by amount desc";

	$list_query .= " LIMIT 0," . $adb->sql_escape_string($maxval);
	
	if($calCnt == 'calculateCnt') {
		$list_result_rows = $adb->query(mkCountQuery($list_query));
		return $adb->query_result($list_result_rows, 0, 'count');
	}
	
	$list_result=$adb->query($list_query);
	$open_accounts_list = array();
	$noofrows = $adb->num_rows($list_result);
	
	if ($noofrows) {
		for($i=0;$i<$noofrows;$i++) 
		{
			$open_accounts_list[] = Array('accountid' => $adb->query_result($list_result,$i,'accountid'),
					'accountname' => $adb->query_result($list_result,$i,'accountname'),
					'amount' => $adb->query_result($list_result,$i,'amount'),
					'tickersymbol' => $adb->query_result($list_result,$i,'tickersymbol'),
					);								 
		}
	}
	
	$title=array();
	$title[]='myTopAccounts.gif';
	$title[]=$current_module_strings['LBL_TOP_ACCOUNTS'];
	$title[]='home_myaccount';
	
	$header=array();
	$header[]=$current_module_strings['LBL_LIST_ACCOUNT_NAME'];
	$currencyid=fetchCurrency($current_user->id);
	$rate_symbol = getCurrencySymbolandCRate($currencyid);
	$rate = $rate_symbol['rate'];
	$curr_symbol = $rate_symbol['symbol'];
        $header[]=$current_module_strings['LBL_LIST_AMOUNT'].'('.$curr_symbol.')';
	
	$entries=array();
	foreach($open_accounts_list as $account)
	{
		$value=array();
		$account_fields = array(
				'ACCOUNT_ID' => $account['accountid'],
				'ACCOUNT_NAME' => $account['accountname'],
				'AMOUNT' => ($account['amount']),
				);

		$Top_Accounts = (strlen($account['accountname']) > 20) ? (substr($account['accountname'],0,20).'...') : $account['accountname'];
		$value[]='<a href="index.php?action=DetailView&module=Accounts&record='.$account['accountid'].'">'.$Top_Accounts.'</a>';
		$value[]=convertFromDollar($account['amount'],$rate);
		$entries[$account['accountid']]=$value;	
	}
	$values=Array('ModuleName'=>'Accounts','Title'=>$title,'Header'=>$header,'Entries'=>$entries);
	$log->debug("Exiting getTopAccounts method ...");
	if (($display_empty_home_blocks && count($entries) == 0 ) || (count($entries)>0))
		return $values;
}
?>
