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

require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/utils/utils.php');
global $app_strings, $default_charset;
global $currentModule, $current_user;
global $theme, $adb;
$url_string = '';
$smarty = new vtigerCRM_Smarty;
if (!isset($where)) $where = "";


$parent_tab=getParentTab();
$smarty->assign("CATEGORY",$parent_tab);

$url = '';
$popuptype = '';
$popuptype = vtlib_purify($_REQUEST["popuptype"]);

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("THEME_PATH",$theme_path);
$smarty->assign("MODULE",$currentModule);

$form = vtlib_purify($_REQUEST['form']);
//added to get relatedto field value for todo, while selecting from the popup list, after done the alphabet or basic search.
if(isset($_REQUEST['maintab']) && $_REQUEST['maintab'] != '')
{
        $act_tab = vtlib_purify($_REQUEST['maintab']);
        $url = "&maintab=".$act_tab;
}
$smarty->assign("MAINTAB",$act_tab);
// echo $currentModule; exit;
switch($currentModule)
{
	case 'Claim':
		require_once("modules/$currentModule/Claim.php");
		$focus = new projects();
		$log = LoggerManager::getLogger('projects_list');
		$smarty->assign("SINGLE_MOD",'Claim');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Emails');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','projects_name','true','basic',$popuptype,"","",$url);
		break;
	case 'Branchs':
		require_once("modules/$currentModule/Branchs.php");
		$focus = new projects();
		$log = LoggerManager::getLogger('projects_list');
		$smarty->assign("SINGLE_MOD",'Branchs');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Emails');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','projects_name','true','basic',$popuptype,"","",$url);
		break;
	//ekk add======================================================================================
	case 'Projects':
		require_once("modules/$currentModule/Projects.php");
		$focus = new projects();
		$log = LoggerManager::getLogger('projects_list');
		$smarty->assign("SINGLE_MOD",'Projects');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Emails');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','projects_name','true','basic',$popuptype,"","",$url);
		break;
	
	case 'SmartSms':
		require_once("modules/$currentModule/SmartSms.php");
		$focus = new SmartSms();

		$log = LoggerManager::getLogger('SmartSms_list');
		$smarty->assign("SINGLE_MOD",'SmartSms');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'SmartSms');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','smartsms_name','true','basic',$popuptype,"","",$url);
		break;

    case 'Smartquestionnaire':
        require_once("modules/$currentModule/$currentModule.php");
        $focus = new Smartquestionnaire();
        $log = LoggerManager::getLogger('smartquestionnaire_list');
        if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
        $smarty->assign("SINGLE_MOD",'Smartquestionnaire');
        if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
            $smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
        else
            $smarty->assign("RETURN_MODULE",'Emails');
        $alphabetical = AlphabeticalSearch($currentModule,'Popup','smartquestionnaire_name','true','basic',$popuptype,"","",$url);
        break;

	case 'Job':
		require_once("modules/$currentModule/Job.php");
		$focus = new Job();
		$log = LoggerManager::getLogger('job_list');
		$smarty->assign("SINGLE_MOD",'Job');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Emails');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','job_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Competitor':
		require_once("modules/$currentModule/Competitor.php");
		$focus = new Competitor();
		$log = LoggerManager::getLogger('competitor_list');
		$smarty->assign("SINGLE_MOD",'Competitor');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Competitor');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','competitor_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Serial':
		require_once("modules/$currentModule/Serial.php");
		$focus = new Serial();
		$log = LoggerManager::getLogger('serial_list');
		$smarty->assign("SINGLE_MOD",'Serial');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Serial');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','serial_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Seriallist':
		require_once("modules/$currentModule/Seriallist.php");
		$focus = new Seriallist();
		$log = LoggerManager::getLogger('seriallist_list');
		$smarty->assign("SINGLE_MOD",'Seriallist');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Seriallist');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','seriallist_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Inspection':
		require_once("modules/$currentModule/Inspection.php");
		$focus = new Inspection();
		$log = LoggerManager::getLogger('inspection_list');
		$smarty->assign("SINGLE_MOD",'Inspection');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Inspection');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','inspection_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Inspectiontemplate':
		require_once("modules/$currentModule/Inspectiontemplate.php");
		$focus = new Inspectiontemplate();
		$log = LoggerManager::getLogger('inspectiontemplate_list');
		$smarty->assign("SINGLE_MOD",'Inspectiontemplate');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Inspectiontemplate');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','inspectiontemplate_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Errors':
		require_once("modules/$currentModule/Errors.php");
		$focus = new Errors();
		$log = LoggerManager::getLogger('errors_list');
		$smarty->assign("SINGLE_MOD",'Errors');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Errors');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','errors_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Errorslist':
		require_once("modules/$currentModule/Errorslist.php");
		$focus = new Errorslist();
		$log = LoggerManager::getLogger('errorslist_list');
		$smarty->assign("SINGLE_MOD",'Errorslist');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Errorslist');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','errorslist_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Sparepart':
		require_once("modules/$currentModule/Sparepart.php");
		$focus = new Sparepart();
		$log = LoggerManager::getLogger('sparepart_list');
		$smarty->assign("SINGLE_MOD",'Sparepart');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Sparepart');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','sparepart_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Sparepartlist':
		require_once("modules/$currentModule/Sparepartlist.php");
		$focus = new Sparepartlist();
		$log = LoggerManager::getLogger('sparepartlist_list');
		$smarty->assign("SINGLE_MOD",'Sparepartlist');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Sparepartlist');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','sparepartlist_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Activitys':
		require_once("modules/$currentModule/Activitys.php");
		$focus = new Activitys();
		$log = LoggerManager::getLogger('activitys_list');
		$smarty->assign("SINGLE_MOD",'Activitys');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Activitys');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','activitys_name','true','basic',$popuptype,"","",$url);
		break;

	case 'KnowledgeBase':
		require_once("modules/$currentModule/KnowledgeBase.php");
		$focus = new KnowledgeBase();
		$log = LoggerManager::getLogger('knowledgebase_list');
		$smarty->assign("SINGLE_MOD",'Knowledge Base');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Knowledge Base');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','knowledgebase_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Quotation':
		require_once("modules/$currentModule/Quotation.php");
		$focus = new Quotation();
		$log = LoggerManager::getLogger('quotation_list');
		$smarty->assign("SINGLE_MOD",'Quotation');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Quotation');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','quotation_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Opportunity':
		require_once("modules/$currentModule/Opportunity.php");
		$focus = new Opportunity();
		$log = LoggerManager::getLogger('opportunity_list');
		$smarty->assign("SINGLE_MOD",'Opportunity');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Emails');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','opportunity_name','true','basic',$popuptype,"","",$url);
		break;

	case 'PriceList':
		require_once("modules/$currentModule/PriceList.php");
		$focus = new PriceList();
		$log = LoggerManager::getLogger('pricelist_list');
		$smarty->assign("SINGLE_MOD",'PriceList');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'PriceList');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','pricelist_name','true','basic',$popuptype,"","",$url);
		break;
	
	case 'Smartemail':
		require_once("modules/$currentModule/Smartemail.php");
		$focus = new Smartemail();

		$log = LoggerManager::getLogger('smartemail_list');
		$smarty->assign("SINGLE_MOD",'Smartemail');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Smartemail');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','smartemail_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Contacts':
		require_once("modules/$currentModule/Contacts.php");
		$focus = new Contacts();
		$log = LoggerManager::getLogger('contact_list');
		$smarty->assign("SINGLE_MOD",'Contact');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Emails');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','lastname','true','basic',$popuptype,"","",$url);
		break;

	case 'Campaigns':
		require_once("modules/$currentModule/Campaigns.php");
		$focus = new Campaigns();
		$log = LoggerManager::getLogger('campaign_list');
		$smarty->assign("SINGLE_MOD",'Campaign');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','campaignname','true','basic',$popuptype,"","",$url);
		break;

	case 'Accounts':
		require_once("modules/$currentModule/Accounts.php");
		$focus = new Accounts();
		$log = LoggerManager::getLogger('account_list');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$smarty->assign("SINGLE_MOD",'Account');
		if(isset($_REQUEST['return_field']) && $_REQUEST['return_field'] !=''){
			$smarty->assign("return_field",vtlib_purify($_REQUEST['return_field']));
		}
			
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Emails');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','accountname','true','basic',$popuptype,"","",$url);
		break;

	case 'Leads':
		require_once("modules/$currentModule/Leads.php");
		$focus = new Leads();
		$log = LoggerManager::getLogger('contact_list');
		$smarty->assign("SINGLE_MOD",'Lead');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Emails');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','lastname','true','basic',$popuptype,"","",$url);
		break;

	case 'Potentials':
		require_once("modules/$currentModule/Potentials.php");
		$focus = new Potentials();
		$log = LoggerManager::getLogger('potential_list');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$smarty->assign("SINGLE_MOD",'Opportunity');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','potentialname','true','basic',$popuptype,"","",$url);
		break;

	case 'Quotes':
		require_once("modules/$currentModule/Quotes.php");
		$focus = new Quotes();
		$log = LoggerManager::getLogger('quotes_list');
		$smarty->assign("SINGLE_MOD",'Quote');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','subject','true','basic',$popuptype,"","",$url);
		break;

	case 'Products':
		require_once("modules/$currentModule/$currentModule.php");
		$focus = new $currentModule();
		$smarty->assign("SINGLE_MOD",getTranslatedString('SINGLE_'.$currentModule));
		$smarty->assign("PRICE_TYPE",vtlib_purify($_REQUEST['price_type']));
		if(isset($_REQUEST['curr_row']))
		{
			$curr_row = vtlib_purify($_REQUEST['curr_row']);
			$smarty->assign("CURR_ROW", $curr_row);
			$url_string .="&curr_row=".vtlib_purify($_REQUEST['curr_row']);
		}
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','productname','true','basic',$popuptype,"","",$url);
		break;

	case 'PriceBooks':
		require_once("modules/$currentModule/PriceBooks.php");
		$focus = new PriceBooks();
		$smarty->assign("SINGLE_MOD",'PriceBook');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		if(isset($_REQUEST['fldname']) && $_REQUEST['fldname'] !='')
		{
			$smarty->assign("FIELDNAME",vtlib_purify($_REQUEST['fldname']));
			$url_string .="&fldname=".vtlib_purify($_REQUEST['fldname']);
		}
		if(isset($_REQUEST['productid']) && $_REQUEST['productid'] !='')
		{
			$smarty->assign("PRODUCTID",vtlib_purify($_REQUEST['productid']));
			$url_string .="&productid=".vtlib_purify($_REQUEST['productid']);
		}
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','bookname','true','basic',$popuptype,"","",$url);
		break;

	case 'Users':
        require_once("modules/$currentModule/Users.php");
        $focus = new Users();
        $smarty->assign("SINGLE_MOD",'Users');
        if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
            $smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
            $alphabetical = AlphabeticalSearch($currentModule,'Popup','user_name','true','basic',$popuptype,"","",$url);
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
        break;

	case 'HelpDesk':
		require_once("modules/$currentModule/HelpDesk.php");
		$focus = new HelpDesk();
		$smarty->assign("SINGLE_MOD",'HelpDesk');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','ticket_title','true','basic',$popuptype,"","",$url);
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		break;

	case 'Documents':
		require_once("modules/$currentModule/Documents.php");
		$focus = new Documents();
		$smarty->assign("SINGLE_MOD",'Document');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Emails');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','notes_title','true','basic',$popuptype,"","",$url);
		break;

	case 'Services':
		if(isset($_REQUEST['curr_row']))
		{
			$curr_row = vtlib_purify($_REQUEST['curr_row']);
			$smarty->assign("CURR_ROW", $curr_row);
			$url_string .="&curr_row=".vtlib_purify($_REQUEST['curr_row']);
		}
		break;

	case 'Faq':
		require_once("modules/$currentModule/Faq.php");
		$focus = new Faq();
		$log = LoggerManager::getLogger('faq_list');
		$smarty->assign("SINGLE_MOD",'Faq');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','faqname','true','basic',$popuptype,"","",$url);
		break;

	case 'Plant':
		require_once("modules/$currentModule/Plant.php");
		$focus = new Plant();
		$log = LoggerManager::getLogger('plant_list');
		$smarty->assign("SINGLE_MOD",'Plant');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','plant_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Order':
		require_once("modules/$currentModule/Order.php");
		$focus = new Order();
		$log = LoggerManager::getLogger('order_list');
		$smarty->assign("SINGLE_MOD",'Order');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','order_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Deal':
		require_once("modules/$currentModule/Deal.php");
		$focus = new Deal();
		$log = LoggerManager::getLogger('deal_list');
		$smarty->assign("SINGLE_MOD",'Deal');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Deal');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','deal_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Promotion':
		require_once("modules/$currentModule/Promotion.php");
		$focus = new Promotion();
		$log = LoggerManager::getLogger('promotion_list');
		$smarty->assign("SINGLE_MOD",'Promotion');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Promotion');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','promotion_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Promotionvoucher':
		require_once("modules/$currentModule/Promotionvoucher.php");
		$focus = new Promotionvoucher();
		$log = LoggerManager::getLogger('promotionvoucher_list');
		$smarty->assign("SINGLE_MOD",'Promotion Voucher');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','promotionvoucher_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Competitorproduct':
		require_once("modules/$currentModule/Competitorproduct.php");
		$focus = new Competitorproduct();
		$log = LoggerManager::getLogger('competitorproduct_list');
		$smarty->assign("SINGLE_MOD",'Competitor Product');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','competitorproduct_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Premuimproduct':
		require_once("modules/$currentModule/Premuimproduct.php");
		$focus = new Premuimproduct();
		$log = LoggerManager::getLogger('premuimproduct_list');
		$smarty->assign("SINGLE_MOD",'Premuim Product');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','premuimproduct_name','true','basic',$popuptype,"","",$url);
		break;
	 case 'Service':
		require_once("modules/$currentModule/Service.php");
		$focus = new Service();
		$log = LoggerManager::getLogger('service_list');
		$smarty->assign("SINGLE_MOD",'Service');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Service');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','service_name','true','basic',$popuptype,"","",$url);
		break;
		
	case 'Servicerequest':
		require_once("modules/$currentModule/Servicerequest.php");
		$focus = new Servicerequest();
		$log = LoggerManager::getLogger('servicerequest_list');
		$smarty->assign("SINGLE_MOD",'Service Request');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','servicerequest_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Voucher':
		require_once("modules/$currentModule/Voucher.php");
		$focus = new Voucher();
		$log = LoggerManager::getLogger('voucher_list');
		$smarty->assign("SINGLE_MOD",'Voucher');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Voucher');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','voucher_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Questionnaire':
		require_once("modules/$currentModule/Questionnaire.php");
		$focus = new Questionnaire();
		$log = LoggerManager::getLogger('questionnaire_list');
		$smarty->assign("SINGLE_MOD",'Questionnaire');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Questionnaire');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','questionnaire_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Questionnairetemplate':
		require_once("modules/$currentModule/Questionnairetemplate.php");
		$focus = new Questionnairetemplate();
		$log = LoggerManager::getLogger('questionnairetemplate_list');
		$smarty->assign("SINGLE_MOD",'Questionnairetemplate');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Questionnairetemplate');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','questionnairetemplate_name','true','basic',$popuptype,"","",$url);
		break;
    case 'Questionnaireanswer':
        require_once("modules/$currentModule/$currentModule.php");
        $focus = new Questionnaireanswer();
        $log = LoggerManager::getLogger('questionnaireanswer_list');
        if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
        $smarty->assign("SINGLE_MOD",'Questionnaireanswer');
        if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
            $smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
        else
            $smarty->assign("RETURN_MODULE",'Emails');
        $alphabetical = AlphabeticalSearch($currentModule,'Popup','questionnaireanswer_name','true','basic',$popuptype,"","",$url);
        break;

	case 'Calendar':
		require_once("modules/$currentModule/Activity.php");
		$focus = new Activity();
		$log = LoggerManager::getLogger('activity_list');
		$smarty->assign("SINGLE_MOD",'Activity');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Activity');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','activitytype','true','basic',$popuptype,"","",$url);
		break;

	case 'Announcement':
		require_once("modules/$currentModule/Announcement.php");
		$focus = new Announcement();
		$log = LoggerManager::getLogger('announcement_list');
		$smarty->assign("SINGLE_MOD",'Announcement');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Announcement');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','announcement_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Point':
		require_once("modules/$currentModule/Point.php");
		$focus = new Point();
		$log = LoggerManager::getLogger('point_list');
		$smarty->assign("SINGLE_MOD",'Point');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Point');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','point_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Redemption':
		require_once("modules/$currentModule/Redemption.php");
		$focus = new Redemption();
		$log = LoggerManager::getLogger('redemption_list');
		$smarty->assign("SINGLE_MOD",'Redemption');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Redemption');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','redemption_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Salesorder':
		require_once("modules/$currentModule/Salesorder.php");
		$focus = new Salesorder();
		$log = LoggerManager::getLogger('salesorder_list');
		$smarty->assign("SINGLE_MOD",'Salesorder');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Salesorder');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
			$alphabetical = AlphabeticalSearch($currentModule,'Popup','salesorder_name','true','basic',$popuptype,"","",$url);
		break;

	case 'Tools':
        require_once("modules/$currentModule/Tools.php");
        $focus = new Tools();
        $log = LoggerManager::getLogger('tools_list');
        $smarty->assign("SINGLE_MOD",'Tools');
        if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
            $smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
        else
            $smarty->assign("RETURN_MODULE",'Tools');
        if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
        $alphabetical = AlphabeticalSearch($currentModule,'Popup','tools_name','true','basic',$popuptype,"","",$url);
        break;

    case 'Expense':
        require_once("modules/$currentModule/Expense.php");
        $focus = new Expense();
        $log = LoggerManager::getLogger('expense_list');
        $smarty->assign("SINGLE_MOD",'Expense');
        if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
            $smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
        else
            $smarty->assign("RETURN_MODULE",'Expense');
        if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
        $alphabetical = AlphabeticalSearch($currentModule,'Popup','expense_name','true','basic',$popuptype,"","",$url);
        break;

     case 'Purchasesorder':
        require_once("modules/$currentModule/Purchasesorder.php");
        $focus = new Purchasesorder();
        $log = LoggerManager::getLogger('purchasesorder_list');
        $smarty->assign("SINGLE_MOD",'Purchasesorder');
        if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
            $smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
        else
            $smarty->assign("RETURN_MODULE",'Purchasesorder');
        if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
        $alphabetical = AlphabeticalSearch($currentModule,'Popup','purchasesorder_name','true','basic',$popuptype,"","",$url);
        break;

    case 'Samplerequisition':
        require_once("modules/$currentModule/Samplerequisition.php");
        $focus = new Samplerequisition();
        $log = LoggerManager::getLogger('samplerequisition_list');
        $smarty->assign("SINGLE_MOD",'Samplerequisition');
        if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
            $smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
        else
            $smarty->assign("RETURN_MODULE",'Samplerequisition');
        if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
        $alphabetical = AlphabeticalSearch($currentModule,'Popup','samplerequisition_name','true','basic',$popuptype,"","",$url);
        break;

    case 'Goodsreceive':
        require_once("modules/$currentModule/Goodsreceive.php");
        $focus = new Goodsreceive();
        $log = LoggerManager::getLogger('goodsreceive_list');
        $smarty->assign("SINGLE_MOD",'Goodsreceive');
        if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
            $smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
        else
            $smarty->assign("RETURN_MODULE",'Goodsreceive');
        if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
        $alphabetical = AlphabeticalSearch($currentModule,'Popup','goodsreceive_name','true','basic',$popuptype,"","",$url);
        break;

	case 'Marketingtools':
		require_once("modules/$currentModule/Marketingtools.php");
		$focus = new Marketingtools();
		$log = LoggerManager::getLogger('marketingtools_list');
		$smarty->assign("SINGLE_MOD",'Marketingtools');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		else
			$smarty->assign("RETURN_MODULE",'Marketingtools');
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		$alphabetical = AlphabeticalSearch($currentModule,'Popup','marketingtools_name','true','basic',$popuptype,"","",$url);
		break;	
		
	default:
		require_once("modules/$currentModule/$currentModule.php");
		$focus = new $currentModule();
		$smarty->assign("SINGLE_MOD", $currentModule);
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",vtlib_purify($_REQUEST['return_module']));
		$alphabetical = AlphabeticalSearch($currentModule,'Popup',$focus->def_basicsearch_col,'true','basic',$popuptype,"","",$url);
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		break;
	// END

}
// vtlib customization: Initialize focus to get generic popup
if($_REQUEST['form'] == 'vtlibPopupView') {
	vtlib_setup_modulevars($currentModule, $focus);
}
// END

$smarty->assign("RETURN_ACTION",vtlib_purify($_REQUEST['return_action']));

//echo $currentModule; exit;
//Retreive the list from Database
if($currentModule == 'PriceBooks')
{
	$productid=$_REQUEST['productid'];
	$currency_id=$_REQUEST['currencyid'];
	if($currency_id == null) $currency_id = fetchCurrency($current_user->id);
	$query = 'select aicrm_pricebook.*, aicrm_pricebookproductrel.productid, aicrm_pricebookproductrel.listprice, ' .
					'aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_crmentity.modifiedtime ' .
					'from aicrm_pricebook inner join aicrm_pricebookproductrel on aicrm_pricebookproductrel.pricebookid = aicrm_pricebook.pricebookid ' .
					'inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_pricebook.pricebookid ' .
					'where aicrm_pricebookproductrel.productid='.$adb->sql_escape_string($productid).' and aicrm_crmentity.deleted=0 ' .
							'and aicrm_pricebook.currency_id='.$adb->sql_escape_string($currency_id).' and aicrm_pricebook.active=1';
}
else if($currentModule == 'PriceList'){
	$productid=$_REQUEST['productid'];
	$query="SELECT
		aicrm_pricelists.pricelist_no,
		aicrm_pricelists.pricelist_name,	
		aicrm_pricelists.pricelist_startdate,
		aicrm_pricelists.pricelist_enddate,
		aicrm_pricelists.status_pricelist,
		aicrm_inventoryproductrel.listprice,
		aicrm_crmentity.*
		FROM aicrm_inventoryproductrel
		INNER JOIN aicrm_pricelists ON aicrm_pricelists.pricelistid = aicrm_inventoryproductrel.id
		INNER JOIN aicrm_pricelistscf ON aicrm_pricelistscf.pricelistid = aicrm_pricelists.pricelistid
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid AND aicrm_crmentity.deleted = 0
		WHERE aicrm_pricelists.pricelist_startdate <= '".date('Y-m-d')."' AND  aicrm_pricelists.pricelist_enddate >= '".date('Y-m-d')."' 
		AND aicrm_pricelists.status_pricelist = 'Active' AND aicrm_inventoryproductrel.productid=".$adb->sql_escape_string($productid) ;
}
else if($currentModule == 'Products' && $_REQUEST['return_module']== 'Order')
{
	$relmod_id=$_REQUEST['relmod_id'];
	$query = "select aicrm_products.* ,aicrm_pricelists.* ,aicrm_crmentity.crmid, aicrm_crmentity.smownerid, aicrm_crmentity.modifiedtime from aicrm_pricelists
				inner join aicrm_pricelistscf on aicrm_pricelistscf.pricelistid = aicrm_pricelists.pricelistid
				inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
				inner join ( 
					select aicrm_plant.* from aicrm_plant
				    inner join aicrm_plantcf on aicrm_plantcf.plantid = aicrm_plant.plantid
				    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_plant.plantid
				    where aicrm_crmentity.deleted = 0 
				 ) as aicrm_plant  on aicrm_plant.plantid = aicrm_pricelists.plantid
				 inner join (
					select aicrm_products.* from aicrm_products
				    inner join aicrm_productcf on aicrm_productcf.productid = aicrm_products.productid
				    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
				    where aicrm_crmentity.deleted = 0
				 ) as aicrm_products on aicrm_products.productid = aicrm_pricelists.product_id
				where aicrm_crmentity.deleted = 0 and aicrm_plant.plantid = '".$relmod_id."' ";

}else if($currentModule == 'Products' && ($_REQUEST['return_module'] == 'Quotes' || $_REQUEST['return_module'] == 'Salesorder'))
{
	$relmod_id=$_REQUEST['relmod_id'];
	
	$query = "SELECT CASE WHEN (aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name ELSE aicrm_groups.groupname
    END AS user_name, aicrm_crmentity.crmid,
    aicrm_crmentity.description,
    aicrm_products.*,
    aicrm_productcf.*,
    case when pricelist.id IS NOT NULL THEN pricelist.listprice else aicrm_products.selling_price END AS sellingprice
	FROM aicrm_products
	INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
	INNER JOIN aicrm_productcf ON aicrm_products.productid = aicrm_productcf.productid
	LEFT JOIN  (select aicrm_inventoryproductrel.id , aicrm_inventoryproductrel.productid , aicrm_inventoryproductrel.listprice  from aicrm_inventoryproductrel inner join (SELECT Max(id) as id , productid FROM aicrm_pricelists
	INNER JOIN  aicrm_inventoryproductrel ON aicrm_inventoryproductrel.id = aicrm_pricelists.pricelistid
	INNER join aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid  
	where aicrm_crmentity.deleted = 0  AND aicrm_pricelists.pricelist_startdate <= '".date('Y-m-d')."' AND aicrm_pricelists.pricelist_enddate >= '".date('Y-m-d')."'AND aicrm_pricelists.status_pricelist = 'Active' group by aicrm_inventoryproductrel.productid ) as inventory ON inventory.id = aicrm_inventoryproductrel.id AND inventory.productid = aicrm_inventoryproductrel.productid ) AS pricelist ON pricelist.productid = aicrm_products.productid
	LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
	LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
	LEFT JOIN aicrm_users AS create_by ON aicrm_crmentity.smcreatorid = create_by.id
	LEFT JOIN aicrm_users AS modified_by ON aicrm_crmentity.modifiedby = modified_by.id 
	WHERE aicrm_crmentity.deleted = 0 AND aicrm_products.producttatus != 'Inactive' ";

	if(isset($_REQUEST['recordid']) && $_REQUEST['recordid'] != '')
	{
		$smarty->assign("RECORDID",vtlib_purify($_REQUEST['recordid']));
		$url_string .='&recordid='.vtlib_purify($_REQUEST['recordid']);
        	$where_relquery = getRelCheckquery($currentModule,$_REQUEST['return_module'],$_REQUEST['recordid']);
	}
	if($currentModule == 'Products' && !$_REQUEST['record_id'] && ($popuptype == 'inventory_prod' || $popuptype == 'inventory_prod_po')){
		$where_relquery .=" and aicrm_products.productstatus <> 0 AND (aicrm_products.productid NOT IN (SELECT crmid FROM aicrm_seproductsrel WHERE setype='Products'))";
	}
	elseif($currentModule == 'Products' && $_REQUEST['record_id'] && ($popuptype == 'inventory_prod' || $popuptype == 'inventory_prod_po')){
		$where_relquery .=" and aicrm_products.productstatus <> 0 AND (aicrm_products.productid IN (SELECT crmid FROM aicrm_seproductsrel WHERE setype='Products' AND productid=".$adb->sql_escape_string($_REQUEST['record_id'])."))";
	}
	elseif($currentModule == 'Products' && $_REQUEST['return_module'] != 'Products'){
       		$where_relquery .=" and aicrm_products.productstatus <> 0";
    }

    if($currentModule == 'Products' && (isset($_REQUEST['brand']) && $_REQUEST['brand'] != '' && $_REQUEST['brand'] !='--None--')){
    		$where_relquery .=" and aicrm_products.product_brand = '".$_REQUEST['brand']."' ";    
    }

	if($_REQUEST['return_module'] == 'Products' && $currentModule == 'Products' && $_REQUEST['recordid'])
       	$where_relquery .=" and aicrm_products.productstatus <> 0 AND (aicrm_crmentity.crmid NOT IN (".$adb->sql_escape_string($_REQUEST['recordid']).") AND aicrm_crmentity.crmid NOT IN (SELECT productid FROM aicrm_seproductsrel WHERE setype='Products') AND aicrm_crmentity.crmid NOT IN (SELECT crmid FROM aicrm_seproductsrel WHERE setype='Products' AND productid=".$adb->sql_escape_string($_REQUEST['recordid'])."))";

    $query .= $where_relquery; 


}else if($currentModule == 'Contacts' && $_REQUEST['parent_module'] == 'Accounts' && $_REQUEST['relmod_id'] != ''){
	
	/*$query = "SELECT aicrm_crmentity.*, aicrm_contactdetails.*, CASE WHEN (aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name ELSE aicrm_groups.groupname END AS user_name, aicrm_account.* 
		FROM aicrm_contactdetails 
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid 
		INNER JOIN aicrm_crmentityrel ON (aicrm_crmentityrel.relcrmid = aicrm_crmentity.crmid OR aicrm_crmentityrel.crmid = aicrm_crmentity.crmid) 
		LEFT JOIN aicrm_account ON (aicrm_crmentityrel.relcrmid = aicrm_account.accountid OR aicrm_crmentityrel.crmid = aicrm_account.accountid)  
		LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid 
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
		WHERE aicrm_crmentity.deleted = 0 AND (aicrm_crmentityrel.crmid = '".$_REQUEST['relmod_id']."' OR aicrm_crmentityrel.relcrmid = '".$_REQUEST['relmod_id']."')";*/
	$query = "SELECT aicrm_crmentity.*, aicrm_contactdetails.*, CASE WHEN (aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name ELSE aicrm_groups.groupname END AS user_name
		FROM aicrm_contactdetails 
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid 
		
		LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_contactdetails.accountid
		LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid 
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
		WHERE aicrm_crmentity.deleted = 0 AND aicrm_account.accountid = '".$_REQUEST['relmod_id']."' ";

}else if($currentModule == 'Accounts' && $_REQUEST['parent_module'] == 'Contacts' && $_REQUEST['relmod_id'] != ''){
	
	$query = "SELECT aicrm_crmentity.*, aicrm_account.*, CASE WHEN (aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name ELSE aicrm_groups.groupname END AS user_name, aicrm_accountbillads.*, aicrm_accountshipads.* 
		FROM aicrm_account 
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid 
		INNER JOIN aicrm_crmentityrel ON (aicrm_crmentityrel.relcrmid = aicrm_crmentity.crmid OR aicrm_crmentityrel.crmid = aicrm_crmentity.crmid) 
		LEFT JOIN aicrm_contactdetails ON (aicrm_crmentityrel.relcrmid = aicrm_contactdetails.contactid OR aicrm_crmentityrel.crmid = aicrm_contactdetails.contactid)
		LEFT JOIN aicrm_accountbillads ON aicrm_accountbillads.accountaddressid = aicrm_account.accountid 
		LEFT JOIN aicrm_accountshipads ON aicrm_accountshipads.accountaddressid = aicrm_account.accountid 
		LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid 
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
		WHERE aicrm_crmentity.deleted = 0 AND (aicrm_crmentityrel.crmid = '".$_REQUEST['relmod_id']."' OR aicrm_crmentityrel.relcrmid = '".$_REQUEST['relmod_id']."')";
}else if($currentModule == 'Serial' && $_REQUEST['relmod_id'] != ''){

	$query = "SELECT distinct(aicrm_serial.serialid),aicrm_crmentity.*, aicrm_serial.*, aicrm_serialcf.* 
		FROM aicrm_serial 
		INNER JOIN aicrm_serialcf ON aicrm_serialcf.serialid = aicrm_serial.serialid 
		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_serial.serialid 
		LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_serial.accountid 
		LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_serial.product_id 
		LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id 
		LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
		LEFT JOIN aicrm_users as create_by ON aicrm_crmentity.smcreatorid = create_by.id 
		LEFT JOIN aicrm_users as modified_by ON aicrm_crmentity.modifiedby = modified_by.id 
		WHERE aicrm_crmentity.deleted = 0 AND aicrm_serial.product_id = '".$_REQUEST['relmod_id']."' ";
}
else
{
	if(isset($_REQUEST['recordid']) && $_REQUEST['recordid'] != '')
	{
		$smarty->assign("RECORDID",vtlib_purify($_REQUEST['recordid']));
		$url_string .='&recordid='.vtlib_purify($_REQUEST['recordid']);
        	$where_relquery = getRelCheckquery($currentModule,$_REQUEST['return_module'],$_REQUEST['recordid']);
	}
	if(isset($_REQUEST['relmod_id']) || isset($_REQUEST['fromPotential']))
	{	

		if($_REQUEST['relmod_id'] !='')
		{
			$mod = vtlib_purify($_REQUEST['parent_module']);
			$id = vtlib_purify($_REQUEST['relmod_id']);
		}
		else if($_REQUEST['fromPotential'] != '')
		{
			$mod = "Accounts";
			$id= vtlib_purify($_REQUEST['acc_id']);
		}
		$smarty->assign("mod_var_name", "parent_module");
		$smarty->assign("mod_var_value", $mod);
		$smarty->assign("recid_var_name", "relmod_id");
		$smarty->assign("recid_var_value",$id);
		$where_relquery.= getPopupCheckquery($currentModule,$mod,$id);
	}
	else if(isset($_REQUEST['task_relmod_id']))
	{	
		$smarty->assign("mod_var_name", "task_parent_module");
		$smarty->assign("mod_var_value", vtlib_purify($_REQUEST['task_parent_module']));
		$smarty->assign("recid_var_name", "task_relmod_id");
		$smarty->assign("recid_var_value",vtlib_purify($_REQUEST['task_relmod_id']));
		$where_relquery.= getPopupCheckquery($currentModule,$_REQUEST['task_parent_module'],$_REQUEST['task_relmod_id']);
	}
	
	if($currentModule == 'Products' && !$_REQUEST['record_id'] && ($popuptype == 'inventory_prod' || $popuptype == 'inventory_prod_po')){
		$where_relquery .=" and aicrm_products.productstatus <> 0 AND (aicrm_products.productid NOT IN (SELECT crmid FROM aicrm_seproductsrel WHERE setype='Products'))";
	}
    elseif($currentModule == 'Projects' && ((isset($_REQUEST['contact_id']) && $_REQUEST['contact_id'] != '') || (isset($_REQUEST['account_id']) && $_REQUEST['account_id'] != '' ))){
		if($_REQUEST['account_id'] != '' && $_REQUEST['account_id'] != 0 ){
			$where_relquery .=" and aicrm_projects.accountid = '".$_REQUEST['account_id']."'" ;
		}
		if($_REQUEST['contact_id'] != '' && $_REQUEST['contact_id'] != 0){
			$where_relquery .=" and aicrm_projects.contactid = '".$_REQUEST['contact_id']."'"; 
		}
	}
    elseif($currentModule == 'Projects' && $_REQUEST['module_return'] == 'Quotes' ){
		$where_relquery .=" and aicrm_projects.projectorder_status != 'Cancelled'" ;
	}
	elseif($currentModule == 'Products' && $_REQUEST['record_id'] && ($popuptype == 'inventory_prod' || $popuptype == 'inventory_prod_po')){
		$where_relquery .=" and aicrm_products.productstatus <> 0 AND (aicrm_products.productid IN (SELECT crmid FROM aicrm_seproductsrel WHERE setype='Products' AND productid=".$adb->sql_escape_string($_REQUEST['record_id'])."))";
	}
	elseif($currentModule == 'Products' && $_REQUEST['return_module'] != 'Products'){
       		$where_relquery .=" and aicrm_products.productstatus <> 0";
    }

    if($currentModule == 'Products' && (isset($_REQUEST['brand']) && $_REQUEST['brand'] != '' && $_REQUEST['brand'] !='--None--')){
    		$where_relquery .=" and aicrm_products.product_brand = '".$_REQUEST['brand']."' ";    
    }

	if($_REQUEST['return_module'] == 'Products' && $currentModule == 'Products' && $_REQUEST['recordid'])
       	$where_relquery .=" and aicrm_products.productstatus <> 0 AND (aicrm_crmentity.crmid NOT IN (".$adb->sql_escape_string($_REQUEST['recordid']).") AND aicrm_crmentity.crmid NOT IN (SELECT productid FROM aicrm_seproductsrel WHERE setype='Products') AND aicrm_crmentity.crmid NOT IN (SELECT crmid FROM aicrm_seproductsrel WHERE setype='Products' AND productid=".$adb->sql_escape_string($_REQUEST['recordid'])."))";

	if($currentModule == 'Services' && $popuptype == 'inventory_service') {
		$where_relquery .=" and aicrm_service.discontinued <> 0";
	}

	//Avoiding Current Record to show up in the popups When editing.
	/*if($currentModule == 'Accounts' && $_REQUEST['recordid']!=''){
		$where_relquery .=" and aicrm_account.accountid!=".$adb->sql_escape_string($_REQUEST['recordid']);
		$smarty->assign("RECORDID",vtlib_purify($_REQUEST['recordid']));
	}

	if($currentModule == 'Contacts' && $_REQUEST['recordid']!=''){
		$where_relquery .=" and aicrm_contactdetails.contactid!=".$adb->sql_escape_string($_REQUEST['recordid']);
		$smarty->assign("RECORDID",vtlib_purify($_REQUEST['recordid']));

	}*/

	if($currentModule == 'Users' && $_REQUEST['recordid']!=''){
		$where_relquery .=" and aicrm_users.id!=".$adb->sql_escape_string($_REQUEST['recordid']);
		$smarty->assign("RECORDID",vtlib_purify($_REQUEST['recordid']));
	}
	//echo $where_relquery;
	//add query account show

	$query = getListQuery($currentModule,$where_relquery);
}

if($_REQUEST['relmod_id'] != ''){
	$smarty->assign("relmod_id", $_REQUEST['relmod_id']);
}

//echo $query; exit;
if($currentModule == 'Products' && $_REQUEST['record_id'] && ($popuptype == 'inventory_prod' || $popuptype == 'inventory_prod_po'))
{
	$product_name = getProductName($_REQUEST['record_id']);
	$smarty->assign("PRODUCT_NAME", $product_name);
	$smarty->assign("RECORD_ID", vtlib_purify($_REQUEST['record_id']));
}

$listview_header_search = getSearchListHeaderValues($focus,"$currentModule",$url_string,$sorder,$order_by);

$smarty->assign("SEARCHLISTHEADER", $listview_header_search);
$smarty->assign("ALPHABETICAL", $alphabetical);

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	list($where, $ustring) = split("#@@#",getWhereCondition($currentModule));
	$url_string .="&query=true".$ustring;
}

// if($current_user->is_admin != 'on' and $currentModule == 'Projects'){
// 	$user_login = $current_user->user_name.' [ '.$current_user->first_name.' '.$current_user->last_name.' ]';
// 	$query .= " OR (aicrm_projects.pjorder_employee LIKE '%".$user_login."%' and aicrm_crmentity.deleted = 0 )";
// }

if(isset($where) && $where != '')
{
    $query .= ' and '.$where;
}

//Added to fix the issue #2307

$order_by = (isset($_REQUEST['order_by'])) ? $adb->sql_escape_string($_REQUEST['order_by']) : $focus->default_order_by;
$sorder = (isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '') ? $adb->sql_escape_string($_REQUEST['sorder']) : $focus->default_sort_order;

if(isset($order_by) && $order_by != '')
{
    $query .= ' ORDER BY '.$order_by.' '.$sorder;
}

//echo $query; exit;
//vtlib customization: To override module specific popup query for a given field

$override_query = false;
if(method_exists($focus, 'getQueryByModuleField')) {
	$override_query = $focus->getQueryByModuleField(vtlib_purify($_REQUEST['srcmodule']), vtlib_purify($_REQUEST['forfield']), vtlib_purify($_REQUEST['forrecord']));
	if($override_query) {
		$query = $override_query;
	}
}

//echo $query;
// END
if(PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false) === true){
	if($currentModule == 'Products' && ($_REQUEST['return_module'] == 'Quotes' || $_REQUEST['return_module'] == 'Salesorder')){
		$count_result = $adb->query(mkCountQuery_inventory($query));
	}else{
		$count_result = $adb->query(mkCountQuery($query));
	}
	//$count_result = $adb->query(mkCountQuery($query));
	$noofrows = $adb->query_result($count_result,0,"count");
}else{
	//$noofrows = null;
}
//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '') {
	$start = vtlib_purify($_REQUEST['start']);
	if($start == 'last'){
		if($currentModule == 'Products' && ($_REQUEST['return_module'] == 'Quotes' || $_REQUEST['return_module'] == 'Salesorder')){
			$count_result = $adb->query(mkCountQuery_inventory($query));
		}else{
			$count_result = $adb->query(mkCountQuery($query));
		}
		//$count_result = $adb->query( mkCountQuery($query));
		$noofrows = $adb->query_result($count_result,0,"count");
		if($noofrows > 0){
			$start = ceil($noofrows/$list_max_entries_per_page);
		}
	}
	if(!is_numeric($start)){
		$start = 1;
	}elseif($start < 1){
		$start = 1;
	}
	$start = ceil($start);
} else {
	$start = 1;
}
$limstart=($start-1)*$list_max_entries_per_page;
$query.=" LIMIT $limstart,$list_max_entries_per_page";
//echo $query; exit;
$list_result = $adb->query($query);
//echo "<pre>"; print_r($list_result); echo "</pre>"; exit;
//Retreive the Navigation array
$navigation_array = VT_getSimpleNavigationValues($start, $list_max_entries_per_page,$noofrows);

//Retreive the List View Table Header
$focus->initSortbyField($currentModule);
$focus->list_mode="search";
$focus->popup_type=$popuptype;
$url_string .='&popuptype='.$popuptype;
if(isset($_REQUEST['select']) && $_REQUEST['select'] == 'enable')
	$url_string .='&select=enable';
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != '')
	$url_string .='&return_module='.vtlib_purify($_REQUEST['return_module']);

if($popuptype == 'set_return_emails'){
	$tabid = getTabid($currentModule);
	$mail_arr = getMailFields($tabid);

	if(!empty($mail_arr)){
		$tablename = str_replace("aicrm_","",$mail_arr['tablename']);
		$fieldname = $mail_arr['fieldname'];
		$fieldlabel = $mail_arr['fieldlabel'];
		$focus->search_fields[$fieldlabel] = Array($tablename=>$fieldname);
		$focus->search_fields_name[$fieldlabel] = $fieldname;
	}
}
//echo 555; exit;
$listview_header = getSearchListViewHeader($focus,"$currentModule",$url_string,$sorder,$order_by);
//echo "<pre>"; print_r($listview_header); echo "</pre>"; exit;
$smarty->assign("LISTHEADER", $listview_header);
$smarty->assign("HEADERCOUNT",count($listview_header)+1);

$listview_entries = getSearchListViewEntries($focus,"$currentModule",$list_result,$navigation_array,$form);
//echo "<pre>"; print_r($listview_entries); echo "</pre>"; exit;

$smarty->assign("LISTENTITY", $listview_entries);
$navigationOutput = getTableHeaderSimpleNavigation($navigation_array, $url_string,$currentModule,"Popup");
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);
$smarty->assign("POPUPTYPE", $popuptype);
$smarty->assign("PARENT_MODULE", vtlib_purify($_REQUEST['parent_module']));
$smarty->assign("MODULE_RETURN",vtlib_purify($_REQUEST['module_return']));

//Quotation
$pricetype = @$_REQUEST['pricetype'];
$smarty->assign("pricetype",$pricetype);

if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != ''){
	$smarty->display("PopupContents.tpl");
}else{
	$smarty->display("Popup.tpl");
}

?>