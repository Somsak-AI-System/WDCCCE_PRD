<?
	function Get_Query($module){
		switch($module){
			Case "ServiceRequest":
				$query = "
				FROM aicrm_servicerequests
				INNER JOIN aicrm_servicerequestscf ON aicrm_servicerequestscf.servicerequestid = aicrm_servicerequests.servicerequestid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_servicerequests.servicerequestid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_servicerequests.accountid
				LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_servicerequests.contactid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_vendor ON aicrm_vendor.vendorid = aicrm_servicerequestscf.cf_1069
				LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_servicerequestscf.cf_1059
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Branch":
				$query = "
				FROM aicrm_branchs
				INNER JOIN aicrm_branchscf ON aicrm_branchscf.branchid = aicrm_branchs.branchid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_branchs.branchid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Premium":
			$query = "
				FROM aicrm_premiums
				INNER JOIN aicrm_premiumscf ON aicrm_premiumscf.premiumid = aicrm_premiums.premiumid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_premiums.premiumid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Promotion":
				$query = "
				FROM aicrm_promotion
				INNER JOIN aicrm_promotioncf ON aicrm_promotioncf.promotionid = aicrm_promotion.promotionid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_promotion.promotionid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Coupon":
				$query = "
				FROM aicrm_coupons
				INNER JOIN aicrm_couponscf ON aicrm_couponscf.couponid = aicrm_coupons.couponid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_coupons.couponid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				left join aicrm_account on aicrm_account.accountid=aicrm_coupons.accountid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "PriceList":
				$query = "
				FROM aicrm_pricelists
				INNER JOIN aicrm_pricelistscf ON aicrm_pricelistscf.pricelistid = aicrm_pricelists.pricelistid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Redemption":
				$query = "
				FROM aicrm_redemption
				INNER JOIN aicrm_redemptioncf ON aicrm_redemptioncf.redemptionid = aicrm_redemption.redemptionid
				left join aicrm_account on aicrm_account.accountid=aicrm_redemption.accountid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_redemption.redemptionid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Point":
				$query = "
				FROM aicrm_point
				INNER JOIN aicrm_pointcf ON aicrm_pointcf.pointid = aicrm_point.pointid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_point.pointid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_point.accountid
				LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_point.campaignid
				left join aicrm_products on aicrm_products.productid=aicrm_point.product_id
				WHERE aicrm_crmentity.deleted = 0 ";
			break;	
			Case "InternalTraining":
				$query = "
				FROM aicrm_internaltrainings
				INNER JOIN aicrm_internaltrainingscf ON aicrm_internaltrainingscf.internaltrainingid = aicrm_internaltrainings.internaltrainingid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_internaltrainings.internaltrainingid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_internaltrainings.accountid
				LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_internaltrainings.contactid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Questionnaire":
				$query = "
				FROM aicrm_questionnaires
				INNER JOIN aicrm_questionnairescf ON aicrm_questionnairescf.questionnaireid = aicrm_questionnaires.questionnaireid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_questionnaires.questionnaireid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Application":
				$query = "
				FROM aicrm_applications
				INNER JOIN aicrm_applicationscf ON aicrm_applicationscf.applicationid = aicrm_applications.applicationid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_applications.applicationid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_applications.accountid
				LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_applicationscf.cf_1059
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "EmailTarget":
				$query = "
				FROM aicrm_emailtargets
				INNER JOIN aicrm_emailtargetscf ON aicrm_emailtargetscf.emailtargetid = aicrm_emailtargets.emailtargetid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_emailtargets.emailtargetid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "EmailTargetList":
				$query = "
				FROM aicrm_emailtargetlists
				INNER JOIN aicrm_emailtargetlistscf ON aicrm_emailtargetlistscf.emailtargetlistid = aicrm_emailtargetlists.emailtargetlistid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_emailtargetlists.emailtargetlistid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "HelpDesk":
				$query = "
				FROM aicrm_troubletickets
				INNER JOIN aicrm_ticketcf ON aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_troubletickets.ticketid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_contactdetails ON aicrm_troubletickets.parent_id = aicrm_contactdetails.contactid
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_troubletickets.parent_id
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_products  ON aicrm_products.productid = aicrm_troubletickets.product_id 
				left join aicrm_servicecontracts on aicrm_servicecontracts.servicecontractsid=aicrm_troubletickets.servicecontractsid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Account":
				$query = "
				FROM aicrm_account
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
				INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
				INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
				INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_account aicrm_account2 ON aicrm_account.parentid = aicrm_account2.accountid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Potentials":
				$query = "
				FROM aicrm_potential
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_potential.potentialid
				INNER JOIN aicrm_potentialscf ON aicrm_potentialscf.potentialid = aicrm_potential.potentialid
				LEFT JOIN aicrm_account ON aicrm_potential.related_to = aicrm_account.accountid
				LEFT JOIN aicrm_contactdetails ON aicrm_potential.related_to = aicrm_contactdetails.contactid
				LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_potential.campaignid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Leads":
				$query = "
				FROM aicrm_leaddetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
				INNER JOIN aicrm_leadsubdetails ON aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid
				INNER JOIN aicrm_leadaddress ON aicrm_leadaddress.leadaddressid = aicrm_leadsubdetails.leadsubscriptionid
				INNER JOIN aicrm_leadscf ON aicrm_leaddetails.leadid = aicrm_leadscf.leadid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0
				AND aicrm_leaddetails.converted = 0 ";			
			break;
			Case "Products":
				$query = "
				FROM aicrm_products
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
				INNER JOIN aicrm_productcf ON aicrm_products.productid = aicrm_productcf.productid
				LEFT JOIN aicrm_vendor ON aicrm_vendor.vendorid = aicrm_products.vendor_id
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_products.handler";
			break;
			Case "Documents":
				$query = "
				FROM aicrm_notes
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_notes.notesid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_attachmentsfolder ON aicrm_notes.folderid = aicrm_attachmentsfolder.folderid 
				WHERE aicrm_crmentity.deleted = 0 ";	
			break;
			Case "Contacts":
				$query = "
				FROM aicrm_contactdetails
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
				INNER JOIN aicrm_contactaddress ON aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid
				INNER JOIN aicrm_contactsubdetails ON aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid
				INNER JOIN aicrm_contactscf ON aicrm_contactscf.contactid = aicrm_contactdetails.contactid
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_contactdetails.accountid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0
				
				";
			break;
			Case "Calendar":
				$query="
				FROM aicrm_activity
				LEFT JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
				LEFT JOIN aicrm_cntactivityrel ON aicrm_cntactivityrel.activityid = aicrm_activity.activityid
				LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_cntactivityrel.contactid
				LEFT JOIN aicrm_seactivityrel ON aicrm_seactivityrel.activityid = aicrm_activity.activityid
				LEFT OUTER JOIN aicrm_activity_reminder ON aicrm_activity_reminder.activity_id = aicrm_activity.activityid
				LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_contactdetails.accountid
				LEFT OUTER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_seactivityrel.crmid
				LEFT OUTER JOIN aicrm_account aicrm_account2 ON aicrm_account2.accountid = aicrm_seactivityrel.crmid
				LEFT OUTER JOIN aicrm_potential ON aicrm_potential.potentialid = aicrm_seactivityrel.crmid
				LEFT OUTER JOIN aicrm_troubletickets ON aicrm_troubletickets.ticketid = aicrm_seactivityrel.crmid
				LEFT OUTER JOIN aicrm_salesorder ON aicrm_salesorder.salesorderid = aicrm_seactivityrel.crmid	
				LEFT OUTER JOIN aicrm_purchaseorder ON aicrm_purchaseorder.purchaseorderid = aicrm_seactivityrel.crmid	
				LEFT OUTER JOIN aicrm_quotes ON aicrm_quotes.quoteid = aicrm_seactivityrel.crmid
				LEFT OUTER JOIN aicrm_invoice ON aicrm_invoice.invoiceid = aicrm_seactivityrel.crmid
				LEFT OUTER JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_seactivityrel.crmid
				WHERE aicrm_crmentity.deleted = 0
				";
			break;
			Case "Emails":
				$query = "
				FROM aicrm_activity
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_seactivityrel ON aicrm_seactivityrel.activityid = aicrm_activity.activityid
				LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_seactivityrel.crmid
				LEFT JOIN aicrm_cntactivityrel ON aicrm_cntactivityrel.activityid = aicrm_activity.activityid
				AND aicrm_cntactivityrel.contactid = aicrm_cntactivityrel.contactid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_salesmanactivityrel ON aicrm_salesmanactivityrel.activityid = aicrm_activity.activityid
				LEFT JOIN aicrm_emaildetails ON aicrm_emaildetails.emailid = aicrm_activity.activityid
				WHERE aicrm_activity.activitytype = 'Emails'
				AND aicrm_crmentity.deleted = 0 ";
			break;
			Case "Faq":
				$query = "
				FROM aicrm_faq
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_faq.id
				LEFT JOIN aicrm_products ON aicrm_faq.product_id = aicrm_products.productid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Vendors":
				$query = "
				FROM aicrm_vendor
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_vendor.vendorid
				INNER JOIN aicrm_vendorcf ON aicrm_vendor.vendorid = aicrm_vendorcf.vendorid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "PriceBooks":
				$query = "
				FROM aicrm_pricebook
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricebook.pricebookid
				INNER JOIN aicrm_pricebookcf ON aicrm_pricebook.pricebookid = aicrm_pricebookcf.pricebookid
				LEFT JOIN aicrm_currency_info ON aicrm_pricebook.currency_id = aicrm_currency_info.id
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Quotes":
				$query = "
				FROM aicrm_quotes
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
				INNER JOIN aicrm_quotesbillads ON aicrm_quotes.quoteid = aicrm_quotesbillads.quotebilladdressid
				INNER JOIN aicrm_quotesshipads ON aicrm_quotes.quoteid = aicrm_quotesshipads.quoteshipaddressid
				LEFT JOIN aicrm_quotescf ON aicrm_quotes.quoteid = aicrm_quotescf.quoteid
				LEFT JOIN aicrm_currency_info ON aicrm_quotes.currency_id = aicrm_currency_info.id
				LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_quotes.accountid
				LEFT OUTER JOIN aicrm_potential ON aicrm_potential.potentialid = aicrm_quotes.potentialid
				LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_quotes.contactid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users as aicrm_usersQuotes ON aicrm_usersQuotes.id = aicrm_quotes.inventorymanager
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "PurchaseOrder":
				$query = "
				FROM aicrm_purchaseorder
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_purchaseorder.purchaseorderid
				LEFT OUTER JOIN aicrm_vendor ON aicrm_purchaseorder.vendorid = aicrm_vendor.vendorid
				LEFT JOIN aicrm_contactdetails ON aicrm_purchaseorder.contactid = aicrm_contactdetails.contactid	
				INNER JOIN aicrm_pobillads ON aicrm_purchaseorder.purchaseorderid = aicrm_pobillads.pobilladdressid
				INNER JOIN aicrm_poshipads ON aicrm_purchaseorder.purchaseorderid = aicrm_poshipads.poshipaddressid
				LEFT JOIN aicrm_purchaseordercf ON aicrm_purchaseordercf.purchaseorderid = aicrm_purchaseorder.purchaseorderid
				LEFT JOIN aicrm_currency_info ON aicrm_purchaseorder.currency_id = aicrm_currency_info.id
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "SalesOrder":
				$query = "
				FROM aicrm_salesorder
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_salesorder.salesorderid
				INNER JOIN aicrm_sobillads ON aicrm_salesorder.salesorderid = aicrm_sobillads.sobilladdressid
				INNER JOIN aicrm_soshipads ON aicrm_salesorder.salesorderid = aicrm_soshipads.soshipaddressid
				LEFT JOIN aicrm_salesordercf ON aicrm_salesordercf.salesorderid = aicrm_salesorder.salesorderid
				LEFT JOIN aicrm_currency_info ON aicrm_salesorder.currency_id = aicrm_currency_info.id
				LEFT OUTER JOIN aicrm_quotes ON aicrm_quotes.quoteid = aicrm_salesorder.quoteid
				LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_salesorder.accountid
				LEFT JOIN aicrm_contactdetails ON aicrm_salesorder.contactid = aicrm_contactdetails.contactid	
				LEFT JOIN aicrm_potential ON aicrm_potential.potentialid = aicrm_salesorder.potentialid
				LEFT JOIN aicrm_invoice_recurring_info ON aicrm_invoice_recurring_info.salesorderid = aicrm_salesorder.salesorderid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
				break;
			Case "Invoice":
				$query = "
				FROM aicrm_invoice
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_invoice.invoiceid
				INNER JOIN aicrm_invoicebillads ON aicrm_invoice.invoiceid = aicrm_invoicebillads.invoicebilladdressid
				INNER JOIN aicrm_invoiceshipads ON aicrm_invoice.invoiceid = aicrm_invoiceshipads.invoiceshipaddressid
				LEFT JOIN aicrm_currency_info ON aicrm_invoice.currency_id = aicrm_currency_info.id
				LEFT OUTER JOIN aicrm_salesorder ON aicrm_salesorder.salesorderid = aicrm_invoice.salesorderid
				LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_invoice.accountid
				LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_invoice.contactid
				INNER JOIN aicrm_invoicecf ON aicrm_invoice.invoiceid = aicrm_invoicecf.invoiceid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Campaigns":
				$query = "
				FROM aicrm_campaign
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_campaign.campaignid
				INNER JOIN aicrm_campaignscf ON aicrm_campaign.campaignid = aicrm_campaignscf.campaignid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_campaign.product_id
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Users":
				$query = "
				from aicrm_users
				inner join aicrm_user2role on aicrm_user2role.userid=aicrm_users.id 
				LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_users.cf_1059
				where deleted=0 ";
			break;
			Case "Dealer":
				$query = "
				FROM aicrm_dealers
				INNER JOIN aicrm_dealerscf ON aicrm_dealerscf.dealerid = aicrm_dealers.dealerid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_dealers.dealerid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Call Detail":
				$query = "
				from aicrm_jobdetails
				left join aicrm_jobdetailscf on aicrm_jobdetailscf.jobdetailid=aicrm_jobdetails.jobdetailid
				left join aicrm_troubletickets on aicrm_troubletickets.ticketid=aicrm_jobdetails.parent_id
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobdetails.jobdetailid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				WHERE aicrm_crmentity.deleted = 0 ";
			break;
			Case "Job":
				$query = "
				FROM aicrm_servicerequests
				INNER JOIN aicrm_servicerequestscf ON aicrm_servicerequestscf.servicerequestid = aicrm_servicerequests.servicerequestid
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_servicerequests.servicerequestid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_servicerequests.accountid
				LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
				LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_servicerequestscf.product_id
				WHERE aicrm_crmentity.deleted = 0  ";
			break;
			default:
			}
		return $query;			
	}
?>