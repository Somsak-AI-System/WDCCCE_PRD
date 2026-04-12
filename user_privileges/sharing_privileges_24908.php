<?php


//This is the sharing access privilege file
$defaultOrgSharingPermission=array('4'=>'1','6'=>'1','7'=>'2','9'=>'3','13'=>'2','16'=>'3','26'=>'3','8'=>'2','52'=>'2','31'=>'2','57'=>'2','50'=>'0','53'=>'2','20'=>'3','41'=>'3','38'=>'2','56'=>'2','61'=>'2','62'=>'2','63'=>'2','65'=>'2','14'=>'2','30'=>'2','24'=>'2','21'=>'2','51'=>'2','44'=>'2','43'=>'2','42'=>'2','11'=>'3','23'=>'2','55'=>'2','54'=>'2','45'=>'2','39'=>'2','40'=>'2','46'=>'2','47'=>'2','48'=>'2','49'=>'2','58'=>'2','59'=>'2','66'=>'2','68'=>'2','69'=>'2','70'=>'2','72'=>'2','73'=>'2');

$related_module_share=array(2=>array(6,),13=>array(6,),20=>array(6,2,),22=>array(6,2,20,),23=>array(6,22,),);

$Leads_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Leads_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Leads_Emails_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Leads_Emails_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Contacts_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Contacts_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_Potentials_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_Potentials_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_HelpDesk_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_HelpDesk_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_Emails_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_Emails_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_Quotes_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_Quotes_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_SalesOrder_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_SalesOrder_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_Invoice_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Accounts_Invoice_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Potentials_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Potentials_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Potentials_Quotes_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Potentials_Quotes_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Potentials_SalesOrder_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Potentials_SalesOrder_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$HelpDesk_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$HelpDesk_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Emails_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Emails_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Campaigns_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Campaigns_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Quotes_share_read_permission=array('ROLE'=>array('H1026'=>array(),),'GROUP'=>array());

$Quotes_share_write_permission=array('ROLE'=>array('H1026'=>array(),),'GROUP'=>array());

$Quotes_SalesOrder_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Quotes_SalesOrder_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$PurchaseOrder_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$PurchaseOrder_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$SalesOrder_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$SalesOrder_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$SalesOrder_Invoice_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$SalesOrder_Invoice_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Invoice_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Invoice_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Documents_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Documents_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Calendar_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Calendar_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Products_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Products_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Users_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Users_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$SalesReport_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$SalesReport_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$PriceList_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$PriceList_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Projects_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Projects_share_write_permission=array('ROLE'=>array('H942'=>array(24862,),'H1033'=>array(24721,),'H995'=>array(24750,24754,24755,24774,24796,24807,24810,24811,24812,24814,24815,24816,24824,24825,24830,24833,24837,24844,24846,24854,24867,24868,24879,24900,24936,24945,24950,24981,25005,25009,),'H1034'=>array(24719,),'H994'=>array(24720,24722,24737,24738,24779,24847,24870,24873,24880,24881,24888,24907,24920,24925,24931,24947,24968,24990,),'H1035'=>array(24908,),'H1026'=>array(),'H1036'=>array(),'H997'=>array(24778,24783,24890,24892,24914,24929,24949,),'H1051'=>array(24963,),'H1052'=>array(24935,24937,24976,25004,),'H952'=>array(24716,24739,),'H996'=>array(24710,24711,24712,24713,24714,24715,24723,24727,24734,24735,24741,24747,24751,24759,24766,24771,24773,24775,24776,24780,24782,24784,24788,24791,24795,24805,24806,24808,24809,24839,24841,24845,24848,24856,24857,24860,24864,24865,24871,24874,24875,24876,24884,24887,24889,24891,24894,24898,24940,24974,24980,24989,24992,),),'GROUP'=>array());

$Competitor_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Competitor_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Deal_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Deal_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Announcement_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Announcement_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Competitorproduct_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Competitorproduct_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

$Claim_share_read_permission=array('ROLE'=>array(),'GROUP'=>array());

$Claim_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

?>