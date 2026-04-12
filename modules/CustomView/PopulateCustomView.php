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
require_once('include/database/PearDatabase.php');

$customviews = Array(Array('viewname'=>'All',
			   'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
			   'cvmodule'=>'Leads','stdfilterid'=>'','advfilterid'=>''),

		     Array('viewname'=>'Hot Leads',
			   'setdefault'=>'0','setmetrics'=>'1','status'=>'3','userid'=>'1',
			   'cvmodule'=>'Leads','stdfilterid'=>'','advfilterid'=>'0'),

		     Array('viewname'=>'This Month Leads',
			   'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
			   'cvmodule'=>'Leads','stdfilterid'=>'0','advfilterid'=>''),
			
		     Array('viewname'=>'All',
               'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
               'cvmodule'=>'Accounts','stdfilterid'=>'','advfilterid'=>''),

		     Array('viewname'=>'Prospect Accounts',
               'setdefault'=>'0','setmetrics'=>'1','status'=>'3','userid'=>'1',
               'cvmodule'=>'Accounts','stdfilterid'=>'','advfilterid'=>'1'),
		     
		     Array('viewname'=>'New This Week',
               'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
               'cvmodule'=>'Accounts','stdfilterid'=>'1','advfilterid'=>''),

		     Array('viewname'=>'All',
               'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
               'cvmodule'=>'Contacts','stdfilterid'=>'','advfilterid'=>''),

		     Array('viewname'=>'Contacts Address',
               'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
               'cvmodule'=>'Contacts','stdfilterid'=>'','advfilterid'=>''),

		     Array('viewname'=>'Todays Birthday',
               'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
               'cvmodule'=>'Contacts','stdfilterid'=>'2','advfilterid'=>''),

		     Array('viewname'=>'All',
               'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
               'cvmodule'=>'Potentials','stdfilterid'=>'','advfilterid'=>''),

		     Array('viewname'=>'Potentials Won',
               'setdefault'=>'0','setmetrics'=>'1','status'=>'3','userid'=>'1',
               'cvmodule'=>'Potentials','stdfilterid'=>'','advfilterid'=>'2'),

		     Array('viewname'=>'Prospecting',
               'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
               'cvmodule'=>'Potentials','stdfilterid'=>'','advfilterid'=>'3'),
 	 	     
		     Array('viewname'=>'All',
               'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
               'cvmodule'=>'HelpDesk','stdfilterid'=>'','advfilterid'=>''),
	
	       Array('viewname'=>'Open Tickets',
               'setdefault'=>'0','setmetrics'=>'1','status'=>'3','userid'=>'1',
               'cvmodule'=>'HelpDesk','stdfilterid'=>'','advfilterid'=>'4'),
       	             
		     Array('viewname'=>'High Prioriy Tickets',
               'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
               'cvmodule'=>'HelpDesk','stdfilterid'=>'','advfilterid'=>'5'),

		     Array('viewname'=>'All',
               'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
               'cvmodule'=>'Quotes','stdfilterid'=>'','advfilterid'=>''),

		     Array('viewname'=>'Open Quotes',
               'setdefault'=>'0','setmetrics'=>'1','status'=>'3','userid'=>'1',
               'cvmodule'=>'Quotes','stdfilterid'=>'','advfilterid'=>'6'),

		     Array('viewname'=>'Rejected Quotes',
               'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
               'cvmodule'=>'Quotes','stdfilterid'=>'','advfilterid'=>'7'),

		    Array('viewname'=>'All',
                'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                'cvmodule'=>'Calendar','stdfilterid'=>'','advfilterid'=>''),
		    
		    Array('viewname'=>'All',
                'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                'cvmodule'=>'Emails','stdfilterid'=>'','advfilterid'=>''),
	
		    Array('viewname'=>'All',
                'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                'cvmodule'=>'Invoice','stdfilterid'=>'','advfilterid'=>''),

		    Array('viewname'=>'All',
                'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                'cvmodule'=>'Documents','stdfilterid'=>'','advfilterid'=>''),
		    
        Array('viewname'=>'All',
                'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                'cvmodule'=>'PriceBooks','stdfilterid'=>'','advfilterid'=>''),	
	
        Array('viewname'=>'All',
                'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                'cvmodule'=>'Products','stdfilterid'=>'','advfilterid'=>''),

        Array('viewname'=>'All',
                'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                'cvmodule'=>'PurchaseOrder','stdfilterid'=>'','advfilterid'=>''),

        Array('viewname'=>'All',
                'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                'cvmodule'=>'SalesOrder','stdfilterid'=>'','advfilterid'=>''),

        Array('viewname'=>'All',
                'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                'cvmodule'=>'Vendors','stdfilterid'=>'','advfilterid'=>''),

		    Array('viewname'=>'All',
                'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                'cvmodule'=>'Faq','stdfilterid'=>'','advfilterid'=>''),

		    Array('viewname'=>'All',
                'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
                'cvmodule'=>'Campaigns','stdfilterid'=>'','advfilterid'=>''),

		    Array('viewname'=>'All',
                'setdefault'=>'1','setmetrics'=>'0','status'=>'0','userid'=>'1',
			          'cvmodule'=>'Webmails','stdfilterid'=>'','advfilterid'=>''),

		    Array('viewname'=>'Drafted FAQ',
                'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                'cvmodule'=>'Faq','stdfilterid'=>'','advfilterid'=>'8'),	
		    
		    Array('viewname'=>'Published FAQ',
                'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
			          'cvmodule'=>'Faq','stdfilterid'=>'','advfilterid'=>'9'),

        Array('viewname'=>'Open Purchase Orders',
                'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                'cvmodule'=>'PurchaseOrder','stdfilterid'=>'','advfilterid'=>'10'),
	    
        Array('viewname'=>'Received Purchase Orders',
                'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                'cvmodule'=>'PurchaseOrder','stdfilterid'=>'','advfilterid'=>'11'),

		    Array('viewname'=>'Open Invoices',
                'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
			          'cvmodule'=>'Invoice','stdfilterid'=>'','advfilterid'=>'12'),

		    Array('viewname'=>'Paid Invoices',
                'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
			          'cvmodule'=>'Invoice','stdfilterid'=>'','advfilterid'=>'13'),

        Array('viewname'=>'Pending Sales Orders',
                'setdefault'=>'0','setmetrics'=>'0','status'=>'3','userid'=>'1',
                'cvmodule'=>'SalesOrder','stdfilterid'=>'','advfilterid'=>'14'),
		    );


$cvcolumns = Array(Array('aicrm_leaddetails:lead_no:lead_no:Leads_Lead_No:V',
						             'aicrm_leaddetails:lastname:lastname:Leads_Last_Name:V',
                         'aicrm_leaddetails:firstname:firstname:Leads_First_Name:V',
                         'aicrm_leaddetails:company:company:Leads_Company:V',
			                   'aicrm_leadaddress:phone:phone:Leads_Phone:V',
                         'aicrm_leadsubdetails:website:website:Leads_Website:V',
                         'aicrm_leaddetails:email:email:Leads_Email:E',
			                   'aicrm_crmentity:smownerid:assigned_user_id:Leads_Assigned_To:V'),

	           Array('aicrm_leaddetails:firstname:firstname:Leads_First_Name:V',
                         'aicrm_leaddetails:lastname:lastname:Leads_Last_Name:V',
                         'aicrm_leaddetails:company:company:Leads_Company:V',
                         'aicrm_leaddetails:leadsource:leadsource:Leads_Lead_Source:V',
                         'aicrm_leadsubdetails:website:website:Leads_Website:V',
                         'aicrm_leaddetails:email:email:Leads_Email:E'),

		         Array('aicrm_leaddetails:firstname:firstname:Leads_First_Name:V',
                         'aicrm_leaddetails:lastname:lastname:Leads_Last_Name:V',
                         'aicrm_leaddetails:company:company:Leads_Company:V',
                         'aicrm_leaddetails:leadsource:leadsource:Leads_Lead_Source:V',
                         'aicrm_leadsubdetails:website:website:Leads_Website:V',
                         'aicrm_leaddetails:email:email:Leads_Email:E'),
	
		  		   Array('aicrm_account:account_no:account_no:Accounts_Account_No:V',
				 		             'aicrm_account:accountname:accountname:Accounts_Account_Name:V',
                         'aicrm_accountbillads:bill_city:bill_city:Accounts_City:V',
                         'aicrm_account:website:website:Accounts_Website:V',
                         'aicrm_account:phone:phone:Accounts_Phone:V',
                         'aicrm_crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),

		          Array('aicrm_account:accountname:accountname:Accounts_Account_Name:V',
                  			 'aicrm_account:phone:phone:Accounts_Phone:V',
                  			 'aicrm_account:website:website:Accounts_Website:V',
                  			 'aicrm_account:rating:rating:Accounts_Rating:V',
                  			 'aicrm_crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),

      		   Array('aicrm_account:accountname:accountname:Accounts_Account_Name:V',
                          'aicrm_account:phone:phone:Accounts_Phone:V',
                          'aicrm_account:website:website:Accounts_Website:V',
                          'aicrm_accountbillads:bill_city:bill_city:Accounts_City:V',
                          'aicrm_crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),

      		   Array('aicrm_contactdetails:contact_no:contact_no:Contacts_Contact_Id:V',
      		   			        'aicrm_contactdetails:firstname:firstname:Contacts_First_Name:V',
                          'aicrm_contactdetails:lastname:lastname:Contacts_Last_Name:V',
                          'aicrm_contactdetails:title:title:Contacts_Title:V',
                          'aicrm_account:accountname:accountname:Contacts_Account_Name:V',
                          'aicrm_contactdetails:email:email:Contacts_Email:E',
                          'aicrm_contactdetails:phone:phone:Contacts_Office_Phone:V',
      			              'aicrm_crmentity:smownerid:assigned_user_id:Contacts_Assigned_To:V'),

      		   Array('aicrm_contactdetails:firstname:firstname:Contacts_First_Name:V',
                          'aicrm_contactdetails:lastname:lastname:Contacts_Last_Name:V',
                          'aicrm_contactaddress:mailingstreet:mailingstreet:Contacts_Mailing_Street:V',
                          'aicrm_contactaddress:mailingcity:mailingcity:Contacts_Mailing_City:V',
                          'aicrm_contactaddress:mailingstate:mailingstate:Contacts_Mailing_State:V',
                        	'aicrm_contactaddress:mailingzip:mailingzip:Contacts_Mailing_Zip:V',
                        	'aicrm_contactaddress:mailingcountry:mailingcountry:Contacts_Mailing_Country:V'),
      		   
      		   Array('aicrm_contactdetails:firstname:firstname:Contacts_First_Name:V',
                          'aicrm_contactdetails:lastname:lastname:Contacts_Last_Name:V',
                          'aicrm_contactdetails:title:title:Contacts_Title:V',
                          'aicrm_account:accountname:accountname:Contacts_Account_Name:V',
                          'aicrm_contactdetails:email:email:Contacts_Email:E',
                      		'aicrm_contactsubdetails:otherphone:otherphone:Contacts_Phone:V',
                      		'aicrm_crmentity:smownerid:assigned_user_id:Contacts_Assigned_To:V'),
		  
      		   Array('aicrm_potential:potential_no:potential_no:Potentials_Potential_No:V',
       	   			         'aicrm_potential:potentialname:potentialname:Potentials_Potential_Name:V',
                         'aicrm_potential:related_to:related_to:Potentials_Related_To:V',
                         'aicrm_potential:sales_stage:sales_stage:Potentials_Sales_Stage:V',
                         'aicrm_potential:leadsource:leadsource:Potentials_Lead_Source:V',
                         'aicrm_potential:closingdate:closingdate:Potentials_Expected_Close_Date:D',
                         'aicrm_crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),

    	       Array('aicrm_potential:potentialname:potentialname:Potentials_Potential_Name:V',
          	             'aicrm_potential:related_to:related_to:Potentials_Related_To:V',
          	             'aicrm_potential:amount:amount:Potentials_Amount:N',
          	             'aicrm_potential:closingdate:closingdate:Potentials_Expected_Close_Date:D',
          	             'aicrm_crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),

      		   Array('aicrm_potential:potentialname:potentialname:Potentials_Potential_Name:V',
                       'aicrm_potential:related_to:related_to:Potentials_Related_To:V',
                       'aicrm_potential:amount:amount:Potentials_Amount:N',
                       'aicrm_potential:leadsource:leadsource:Potentials_Lead_Source:V',
                       'aicrm_potential:closingdate:closingdate:Potentials_Expected_Close_Date:D',
                       'aicrm_crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),

      		   Array('aicrm_troubletickets:ticket_no:ticket_no:HelpDesk_Ticket_No:V',
                			         'aicrm_troubletickets:title:ticket_title:HelpDesk_Title:V',
                               'aicrm_troubletickets:parent_id:parent_id:HelpDesk_Related_To:I',
                               'aicrm_troubletickets:status:ticketstatus:HelpDesk_Status:V',
                               'aicrm_troubletickets:priority:ticketpriorities:HelpDesk_Priority:V',
                               'aicrm_crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),

		   Array('aicrm_troubletickets:title:ticket_title:HelpDesk_Title:V',
                         'aicrm_troubletickets:parent_id:parent_id:HelpDesk_Related_To:I',
                         'aicrm_troubletickets:priority:ticketpriorities:HelpDesk_Priority:V',
                         'aicrm_troubletickets:product_id:product_id:HelpDesk_Product_Name:I',
                         'aicrm_crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),

		   Array('aicrm_troubletickets:title:ticket_title:HelpDesk_Title:V',
                         'aicrm_troubletickets:parent_id:parent_id:HelpDesk_Related_To:I',
                         'aicrm_troubletickets:status:ticketstatus:HelpDesk_Status:V',
                         'aicrm_troubletickets:product_id:product_id:HelpDesk_Product_Name:I',
                         'aicrm_crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),

		   Array('aicrm_quotes:quote_no:quote_no:Quotes_Quote_No:V',
			 'aicrm_quotes:subject:subject:Quotes_Subject:V',
                         'aicrm_quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                         'aicrm_quotes:potentialid:potential_id:Quotes_Potential_Name:I',
                         'aicrm_account:accountname:accountname:Quotes_Account_Name:V',
                         'aicrm_quotes:total:hdnGrandTotal:Quotes_Total:I',
			 'aicrm_crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V'),

		   Array('aicrm_quotes:subject:subject:Quotes_Subject:V',
                         'aicrm_quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                         'aicrm_quotes:potentialid:potential_id:Quotes_Potential_Name:I',
			 'aicrm_account:accountname:accountname:Quotes_Account_Name:V',
                         'aicrm_quotes:validtill:validtill:Quotes_Valid_Till:D',
			 'aicrm_crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V'),

		   Array('aicrm_quotes:subject:subject:Quotes_Subject:V',
                         'aicrm_quotes:potentialid:potential_id:Quotes_Potential_Name:I',
			 'aicrm_account:accountname:accountname:Quotes_Account_Name:V',
                         'aicrm_quotes:validtill:validtill:Quotes_Valid_Till:D',
                         'aicrm_crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V'),
			
		   Array('aicrm_activity:status:taskstatus:Calendar_Status:V',
                         'aicrm_activity:activitytype:activitytype:Calendar_Type:V',
                         'aicrm_activity:subject:subject:Calendar_Subject:V',
                         'aicrm_seactivityrel:crmid:parent_id:Calendar_Related_to:V',
                         'aicrm_activity:date_start:date_start:Calendar_Start_Date:D',
                         'aicrm_activity:due_date:due_date:Calendar_End_Date:D',
                         'aicrm_crmentity:smownerid:assigned_user_id:Calendar_Assigned_To:V'),

		   Array('aicrm_activity:subject:subject:Emails_Subject:V',
       			 'aicrm_emaildetails:to_email:saved_toid:Emails_To:V',
                 	 'aicrm_activity:date_start:date_start:Emails_Date_Sent:D'),
		
		   Array('aicrm_invoice:invoice_no:invoice_no:Invoice_Invoice_No:V',
                         'aicrm_invoice:subject:subject:Invoice_Subject:V',
                         'aicrm_invoice:salesorderid:salesorder_id:Invoice_Sales_Order:I',
                         'aicrm_invoice:invoicestatus:invoicestatus:Invoice_Status:V',
                         'aicrm_invoice:total:hdnGrandTotal:Invoice_Total:I',
                         'aicrm_crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V'),
		
		  Array('aicrm_notes:note_no:note_no:Notes_Note_No:V',
		  				'aicrm_notes:title:notes_title:Notes_Title:V',
                        'aicrm_notes:filename:filename:Notes_File:V',
                        'aicrm_crmentity:modifiedtime:modifiedtime:Notes_Modified_Time:V',
		  				'aicrm_crmentity:smownerid:assigned_user_id:Notes_Assigned_To:V'),
		
		  Array('aicrm_pricebook:pricebook_no:pricebook_no:PriceBooks_PriceBook_No:V',
					  'aicrm_pricebook:bookname:bookname:PriceBooks_Price_Book_Name:V',
                        'aicrm_pricebook:active:active:PriceBooks_Active:V',
                        'aicrm_pricebook:currency_id:currency_id:PriceBooks_Currency:I'),
		  
		  Array('aicrm_products:product_no:product_no:Products_Product_No:V',
		  		'aicrm_products:productname:productname:Products_Product_Name:V',
                        'aicrm_products:productcode:productcode:Products_Part_Number:V',
                        'aicrm_products:commissionrate:commissionrate:Products_Commission_Rate:V',
			'aicrm_products:qtyinstock:qtyinstock:Products_Quantity_In_Stock:V',
                        'aicrm_products:qty_per_unit:qty_per_unit:Products_Qty/Unit:V',
                        'aicrm_products:unit_price:unit_price:Products_Unit_Price:V'),
		  
		  Array('aicrm_purchaseorder:purchaseorder_no:purchaseorder_no:PurchaseOrder_PurchaseOrder_No:V',
                        'aicrm_purchaseorder:subject:subject:PurchaseOrder_Subject:V',
                        'aicrm_purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:I',
                        'aicrm_purchaseorder:tracking_no:tracking_no:PurchaseOrder_Tracking_Number:V',
						'aicrm_purchaseorder:total:hdnGrandTotal:PurchaseOrder_Total:V',
                        'aicrm_crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V'),
		  
	          Array('aicrm_salesorder:salesorder_no:salesorder_no:SalesOrder_SalesOrder_No:V',
                        'aicrm_salesorder:subject:subject:SalesOrder_Subject:V',
                       	'aicrm_account:accountname:accountname:SalesOrder_Account_Name:V',
                        'aicrm_quotes:quoteid:quote_id:SalesOrder_Quote_Name:I',
                        'aicrm_salesorder:total:hdnGrandTotal:SalesOrder_Total:V',
                        'aicrm_crmentity:smownerid:assigned_user_id:SalesOrder_Assigned_To:V'),

	          Array('aicrm_vendor:vendor_no:vendor_no:Vendors_Vendor_No:V',
			  'aicrm_vendor:vendorname:vendorname:Vendors_Vendor_Name:V',
			'aicrm_vendor:phone:phone:Vendors_Phone:V',
			'aicrm_vendor:email:email:Vendors_Email:E',
                        'aicrm_vendor:category:category:Vendors_Category:V'),




		 Array(//'aicrm_faq:id::Faq_FAQ_Id:I',
		 		'aicrm_faq:faq_no:faq_no:Faq_Faq_No:V',
		       'aicrm_faq:question:question:Faq_Question:V',
		       'aicrm_faq:category:faqcategories:Faq_Category:V',
		       'aicrm_faq:product_id:product_id:Faq_Product_Name:I',
		       'aicrm_crmentity:createdtime:createdtime:Faq_Created_Time:D',
                       'aicrm_crmentity:modifiedtime:modifiedtime:Faq_Modified_Time:D'),
		      //this sequence has to be maintained 
		 Array('aicrm_campaign:campaign_no:campaign_no:Campaigns_Campaign_No:V',
		 		'aicrm_campaign:campaignname:campaignname:Campaigns_Campaign_Name:V',
		       'aicrm_campaign:campaigntype:campaigntype:Campaigns_Campaign_Type:N',
		       'aicrm_campaign:campaignstatus:campaignstatus:Campaigns_Campaign_Status:N',
		       'aicrm_campaign:expectedrevenue:expectedrevenue:Campaigns_Expected_Revenue:V',
		       'aicrm_campaign:closingdate:closingdate:Campaigns_Expected_Close_Date:D',
		       'aicrm_crmentity:smownerid:assigned_user_id:Campaigns_Assigned_To:V'),


		 Array('subject:subject:subject:Subject:V',
		       'from:fromname:fromname:From:N',
		       'to:tpname:toname:To:N',
		       'body:body:body:Body:V'),

		 Array ('aicrm_faq:question:question:Faq_Question:V',
		 	'aicrm_faq:status:faqstatus:Faq_Status:V',
			'aicrm_faq:product_id:product_id:Faq_Product_Name:I',
			'aicrm_faq:category:faqcategories:Faq_Category:V',
			'aicrm_crmentity:createdtime:createdtime:Faq_Created_Time:T'),

		 Array( 'aicrm_faq:question:question:Faq_Question:V',
			 'aicrm_faq:answer:faq_answer:Faq_Answer:V',
			 'aicrm_faq:status:faqstatus:Faq_Status:V',
			 'aicrm_faq:product_id:product_id:Faq_Product_Name:I',
			 'aicrm_faq:category:faqcategories:Faq_Category:V',
			 'aicrm_crmentity:createdtime:createdtime:Faq_Created_Time:T'),

		 Array(	 'aicrm_purchaseorder:subject:subject:PurchaseOrder_Subject:V',
			 'aicrm_purchaseorder:postatus:postatus:PurchaseOrder_Status:V',
			 'aicrm_purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:I',
			 'aicrm_crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V',
			 'aicrm_purchaseorder:duedate:duedate:PurchaseOrder_Due_Date:V'),
		 
		 Array ('aicrm_purchaseorder:subject:subject:PurchaseOrder_Subject:V',
			 'aicrm_purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:I',
			 'aicrm_crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V',
			 'aicrm_purchaseorder:postatus:postatus:PurchaseOrder_Status:V',
			 'aicrm_purchaseorder:carrier:carrier:PurchaseOrder_Carrier:V',
			 'aicrm_poshipads:ship_street:ship_street:PurchaseOrder_Shipping_Address:V'),
		
		 Array(  'aicrm_invoice:invoice_no:invoice_no:Invoice_Invoice_No:V',
		 	 'aicrm_invoice:subject:subject:Invoice_Subject:V',
			 'aicrm_account:accountname:accountname:Invoice_Account_Name:V',
			 'aicrm_invoice:salesorderid:salesorder_id:Invoice_Sales_Order:I',
			 'aicrm_invoice:invoicestatus:invoicestatus:Invoice_Status:V',
			 'aicrm_crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V',
			 'aicrm_crmentity:createdtime:createdtime:Invoice_Created_Time:T'),
		 
		 Array(	 'aicrm_invoice:invoice_no:invoice_no:Invoice_Invoice_No:V',
			 'aicrm_invoice:subject:subject:Invoice_Subject:V',
			 'aicrm_account:accountname:accountname:Invoice_Account_Name:V',
			 'aicrm_invoice:salesorderid:salesorder_id:Invoice_Sales_Order:I',
			 'aicrm_invoice:invoicestatus:invoicestatus:Invoice_Status:V',
			 'aicrm_invoiceshipads:ship_street:ship_street:Invoice_Shipping_Address:V',
			 'aicrm_crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V'),

		 Array(	 'aicrm_salesorder:subject:subject:SalesOrder_Subject:V',
			 'aicrm_account:accountname:accountname:SalesOrder_Account_Name:V',
			 'aicrm_salesorder:sostatus:sostatus:SalesOrder_Status:V',
			 'aicrm_crmentity:smownerid:assigned_user_id:SalesOrder_Assigned_To:V',
			 'aicrm_soshipads:ship_street:ship_street:SalesOrder_Shipping_Address:V',
			 'aicrm_salesorder:carrier:carrier:SalesOrder_Carrier:V'),

                  );



$cvstdfilters = Array(Array('columnname'=>'aicrm_crmentity:modifiedtime:modifiedtime:Leads_Modified_Time',
                            'datefilter'=>'thismonth',
                            'startdate'=>'2005-06-01',
                            'enddate'=>'2005-06-30'),

		      Array('columnname'=>'aicrm_crmentity:createdtime:createdtime:Accounts_Created_Time',
                            'datefilter'=>'thisweek',
                            'startdate'=>'2005-06-19',
                            'enddate'=>'2005-06-25'),

		      Array('columnname'=>'aicrm_contactsubdetails:birthday:birthday:Contacts_Birthdate',
                            'datefilter'=>'today',
                            'startdate'=>'2005-06-25',
                            'enddate'=>'2005-06-25')
                     );

$cvadvfilters = Array(
                	Array(
               			 Array('columnname'=>'aicrm_leaddetails:leadstatus:leadstatus:Leads_Lead_Status:V',
		                      'comparator'=>'e',
        		              'value'=>'Hot'
                     			)
                     	 ),
		      		Array(
                          Array('columnname'=>'aicrm_account:account_type:accounttype:Accounts_Type:V',
                                'comparator'=>'e',
                                 'value'=>'Prospect'
                                 )
                           ),
				     Array(
                            Array('columnname'=>'aicrm_potential:sales_stage:sales_stage:Potentials_Sales_Stage:V',
                                  'comparator'=>'e',
                                  'value'=>'Closed Won'
                                 )
                           ),
				     Array(
                            Array('columnname'=>'aicrm_potential:sales_stage:sales_stage:Potentials_Sales_Stage:V',
                                  'comparator'=>'e',
                                  'value'=>'Prospecting'
                                 )
                           ),
				     Array(
                            Array('columnname'=>'aicrm_troubletickets:status:ticketstatus:HelpDesk_Status:V',
                                  'comparator'=>'n',
                                  'value'=>'Closed'
                                 )
                           ),
				     Array(
                            Array('columnname'=>'aicrm_troubletickets:priority:ticketpriorities:HelpDesk_Priority:V',
                                  'comparator'=>'e',
                                  'value'=>'High'
                                 )
                           ),
				     Array(
	                        Array('columnname'=>'aicrm_quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                                  'comparator'=>'n',
                                  'value'=>'Accepted'
                                 ),
						    Array('columnname'=>'aicrm_quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                                  'comparator'=>'n',
                                  'value'=>'Rejected'
                                 )
                           ),
				     Array(
                            Array('columnname'=>'aicrm_quotes:quotestage:quotestage:Quotes_Quote_Stage:V',
                                  'comparator'=>'e',
                                  'value'=>'Rejected'
                                 )
			 ),

			Array(
                          Array('columnname'=>'aicrm_faq:status:faqstatus:Faq_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Draft'
                                 )
			 ),

			Array(
                          Array('columnname'=>'aicrm_faq:status:faqstatus:Faq_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Published'
                                 )
			 ),

			Array(
                          Array('columnname'=>'aicrm_purchaseorder:postatus:postatus:PurchaseOrder_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Created, Approved, Delivered'
                                 )
			 ),

			Array(
                          Array('columnname'=>'aicrm_purchaseorder:postatus:postatus:PurchaseOrder_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Received Shipment'
                                 )
			 ),

			Array(
                          Array('columnname'=>'aicrm_invoice:invoicestatus:invoicestatus:Invoice_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Created, Approved, Sent'
                                 )
			 ),

			Array(
                          Array('columnname'=>'aicrm_invoice:invoicestatus:invoicestatus:Invoice_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Paid'
                                 )
			 ),

			Array(
                          Array('columnname'=>'aicrm_salesorder:sostatus:sostatus:SalesOrder_Status:V',
                                'comparator'=>'e',
                                 'value'=>'Created, Approved'
                                 )
			 )

                     );

foreach($customviews as $key=>$customview)
{
	$queryid = insertCustomView($customview['viewname'],$customview['setdefault'],$customview['setmetrics'],$customview['cvmodule'],$customview['status'],$customview['userid']);
	insertCvColumns($queryid,$cvcolumns[$key]);

	if(isset($cvstdfilters[$customview['stdfilterid']]))
	{
		$i = $customview['stdfilterid'];
		insertCvStdFilter($queryid,$cvstdfilters[$i]['columnname'],$cvstdfilters[$i]['datefilter'],$cvstdfilters[$i]['startdate'],$cvstdfilters[$i]['enddate']);
	}
	if(isset($cvadvfilters[$customview['advfilterid']]))
	{
		insertCvAdvFilter($queryid,$cvadvfilters[$customview['advfilterid']]);
	}
}

	/** to store the details of the customview in aicrm_customview table
	  * @param $viewname :: Type String
	  * @param $setdefault :: Type Integer 
	  * @param $setmetrics :: Type Integer 
	  * @param $cvmodule :: Type String
	  * @returns  $customviewid of the stored custom view :: Type integer
	 */	
function insertCustomView($viewname,$setdefault,$setmetrics,$cvmodule,$status,$userid)
{
	global $adb;

	$genCVid = $adb->getUniqueID("aicrm_customview");

	if($genCVid != "")
	{

		$customviewsql = "insert into aicrm_customview(cvid,viewname,setdefault,setmetrics,entitytype,status,userid) values(?,?,?,?,?,?,?)";
		$customviewparams = array($genCVid, $viewname, $setdefault, $setmetrics, $cvmodule, $status, $userid);
		$customviewresult = $adb->pquery($customviewsql, $customviewparams);
	}
	return $genCVid;
}

	/** to store the custom view columns of the customview in aicrm_cvcolumnlist table
	  * @param $cvid :: Type Integer
	  * @param $columnlist :: Type Array of columnlists
	 */
function insertCvColumns($CVid,$columnslist)
{
	global $adb;
	if($CVid != "")
	{
		for($i=0;$i<count($columnslist);$i++)
		{
			$columnsql = "insert into aicrm_cvcolumnlist (cvid,columnindex,columnname) values(?,?,?)";
			$columnparams = array($CVid, $i, $columnslist[$i]);
			$columnresult = $adb->pquery($columnsql, $columnparams);
		}
	}
}

	/** to store the custom view stdfilter of the customview in aicrm_cvstdfilter table
	  * @param $cvid :: Type Integer
	  * @param $filtercolumn($tablename:$columnname:$fieldname:$fieldlabel) :: Type String
	  * @param $filtercriteria(filter name) :: Type String
	  * @param $startdate :: Type String
	  * @param $enddate :: Type String
	  * returns nothing 
	 */
function insertCvStdFilter($CVid,$filtercolumn,$filtercriteria,$startdate,$enddate)
{
	global $adb;
	if($CVid != "")
	{
		$stdfiltersql = "insert into aicrm_cvstdfilter(cvid,columnname,stdfilter,startdate,enddate) values (?,?,?,?,?)";
		$stdfilterparams = array($CVid, $filtercolumn, $filtercriteria, $startdate, $enddate);
		$stdfilterresult = $adb->pquery($stdfiltersql, $stdfilterparams);
	}
}

	/** to store the custom view advfilter of the customview in aicrm_cvadvfilter table
	  * @param $cvid :: Type Integer
	  * @param $filters :: Type Array('columnname'=>$tablename:$columnname:$fieldname:$fieldlabel,'comparator'=>$comparator,'value'=>$value)
	  * returns nothing 
	 */

function insertCvAdvFilter($CVid,$filters)
{
	global $adb;
	if($CVid != "")
	{
		foreach($filters as $i=>$filter)
		{
			$advfiltersql = "insert into aicrm_cvadvfilter(cvid,columnindex,columnname,comparator,value) values (?,?,?,?,?)";
			$advfilterparams = array($CVid, $i, $filter['columnname'], $filter['comparator'], $filter['value']);
			$advfilterresult = $adb->pquery($advfiltersql, $advfilterparams);
		}
	}
}
?>
