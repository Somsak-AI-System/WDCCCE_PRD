<?
require_once("config.inc.php");
require_once("library/dbconfig.php");
require_once("library/generate_MYSQL.php");
require_once("phpmailer/class.phpmailer.php");

$generate = new generate($dbconfig ,"DB");
global $generate,$path;
$crmid=$_REQUEST["crmid"];
$module=$_REQUEST["module"];
$related_module=$_REQUEST["related_module"];
$msg_ok="Clear Data Complete";
$msg_no="Can not Clear Data";

if($module=="Campaigns"){
	if($related_module=="Leads"){
		$sql="delete from aicrm_campaignleadrel where campaignid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}
}else if($module=="Announcement"){
	
	if($related_module=="Users"){
		$sql1="delete from aicrm_crmentityrel where crmid='".$crmid."' and relmodule = 'Users' " ;
		$generate->query($sql1);

		$sql="delete from aicrm_announcement_usersrel where announcementid='".$crmid."'";
		
		if($generate->query($sql)){
			
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=My Home Page');</script>";
		}
	}

}else if($module=="EmailTargetList"){
	if($related_module=="Leads"){
		$sql="delete from aicrm_emailtargetlist_leadsrel where emailtargetlistid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Accounts"){
		$sql="delete from aicrm_emailtargetlist_accountsrel where emailtargetlistid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Opportunity"){
		$sql="delete from aicrm_emailtargetlist_opportunityrel where emailtargetlistid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Contacts"){
		$sql="delete from aicrm_emailtargetlist_contactsrel where emailtargetlistid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="EmailTarget"){
		$sql="delete from aicrm_emailtargetlist_emailtargetrel where emailtargetlistid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Questionnaire"){
		$sql="delete from aicrm_emailtargetlist_questionnairerel where emailtargetlistid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}
}else if($module=="SmartSms"){
	if($related_module=="Leads"){
		$sql="delete from aicrm_smartsms_leadsrel where smartsmsid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Accounts"){
		$sql="delete from aicrm_smartsms_accountsrel where smartsmsid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Opportunity"){
		$sql="delete from aicrm_smartsms_opportunityrel where smartsmsid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Contacts"){
		$sql="delete from aicrm_smartsms_contactsrel where smartsmsid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="EmailTarget"){
		$sql="delete from aicrm_smartsms_emailtargetrel where smartsmsid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Questionnaire"){
		$sql="delete from aicrm_smartsms_questionnairerel where smartsmsid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}

}else if($module=="Smartemail"){
	if($related_module=="Leads"){
		$sql1="delete from aicrm_smartemail_leadsrel where smartemailid='".$crmid."'";
		$generate->query($sql1);
		$sql="delete from aicrm_crmentityrel where crmid='".$crmid."' and relmodule = 'Leads' " ;
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Accounts"){
		$sql1="delete from aicrm_crmentityrel where crmid='".$crmid."' and relmodule = 'Accounts' " ;
		$generate->query($sql1);
		$sql="delete from aicrm_smartemail_accountsrel where smartemailid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Users"){
		$sql1="delete from aicrm_crmentityrel where crmid='".$crmid."' and relmodule = 'Users' " ;
		$generate->query($sql1);
		$sql="delete from aicrm_smartemail_usersrel where smartemailid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Opportunity"){
		$sql="delete from aicrm_smartemail_opportunityrel where smartemailid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Contacts"){
		$sql="delete from aicrm_smartemail_contactsrel where smartemailid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="EmailTarget"){
		$sql="delete from aicrm_smartemail_emailtargetrel where smartemailid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Questionnaire"){
		$sql="delete from aicrm_smartemail_questionnairerel where smartemailid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}//

}else if($module=="PriceList"){
	
    if($related_module=="Accounts"){
		$sql1="delete from aicrm_pricelist_accountsrel where pricelistid='".$crmid."'";
		
		$sql2 = "delete FROM aicrm_crmentityrel WHERE  crmid = '".$crmid."' ";
		$generate->query($sql2);
		
		if($generate->query($sql1)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
}

//Module Promotion
}else if($module=="Promotion"){
	if($related_module=="Products"){
		$sql="delete from aicrm_productsprorel where promotionid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}else if($related_module=="Premium"){
		$sql="delete from aicrm_premiumsprorel where promotionid='".$crmid."'";
		if($generate->query($sql)){
			echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
		}
	}	
	
	
}else if($module=="Smartquestionnaire"){
    if($related_module=="Accounts"){
        $sql="delete from aicrm_smartquestionnaire_accountsrel where smartquestionnaireid='".$crmid."'";
        if($generate->query($sql)){
            echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
        }
    }else if($related_module=="Leads"){
        $sql="delete from aicrm_smartquestionnaire_leadsrel where smartquestionnaireid='".$crmid."'";
        if($generate->query($sql)){
            echo "<script type='text/javascript'>alert('".$msg_ok."');window.close();  window.opener.parent.location.replace('index.php?action=CallRelatedList&module=".$module."&record=".$crmid."&parenttab=Marketing');</script>";
        }
    }
}
?>