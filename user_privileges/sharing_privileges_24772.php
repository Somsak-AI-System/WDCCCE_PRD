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

$Quotes_share_read_permission=array('ROLE'=>array('H1048'=>array(24742,24916,),'H1012'=>array(),'H1013'=>array(24800,),'H1014'=>array(24802,24803,),'H1015'=>array(24801,24918,),'H1016'=>array(24799,24901,24924,),'H1017'=>array(),'H1023'=>array(24822,24823,24828,24834,24850,24869,24877,24882,24885,24886,24895,),'H1032'=>array(24804,24909,24910,24933,24982,),'H1043'=>array(24954,),'H1042'=>array(24919,24953,24955,24958,24962,24964,),'H1045'=>array(24986,),'H1047'=>array(),'H1046'=>array(24932,24934,24948,24973,24987,25003,),'H1058'=>array(24743,),'H941'=>array(24709,),'H1028'=>array(24729,24902,25001,),'H1055'=>array(24972,),'H1024'=>array(24792,24899,24905,24915,24939,24960,),'H1054'=>array(24978,24979,),'H1056'=>array(),'H1057'=>array(24996,25015,),'H950'=>array(),'H987'=>array(24748,24749,24765,24769,24785,24831,24835,24836,24855,24861,24961,24977,24988,25016,),'H989'=>array(24866,),'H990'=>array(24770,24838,24853,24859,24872,24883,),'H1059'=>array(24718,24745,24757,24760,24762,),'H942'=>array(24862,),'H1033'=>array(24721,),'H995'=>array(24750,24754,24755,24774,24796,24807,24810,24811,24812,24814,24815,24816,24824,24825,24830,24833,24837,24844,24846,24854,24867,24868,24879,24900,24936,24945,24950,24981,25005,25009,),'H1034'=>array(24719,),'H994'=>array(24720,24722,24737,24738,24779,24847,24870,24873,24880,24881,24888,24907,24920,24925,24931,24947,24968,24990,),'H1035'=>array(24908,),'H1026'=>array(),'H1036'=>array(),'H997'=>array(24778,24783,24890,24892,24914,24929,24949,),'H1051'=>array(24963,),'H1052'=>array(24935,24937,24976,25004,),'H952'=>array(24716,24739,),'H996'=>array(24710,24711,24712,24713,24714,24715,24723,24727,24734,24735,24741,24747,24751,24759,24766,24771,24773,24775,24776,24780,24782,24784,24788,24791,24795,24805,24806,24808,24809,24839,24841,24845,24848,24856,24857,24860,24864,24865,24871,24874,24875,24876,24884,24887,24889,24891,24894,24898,24940,24974,24980,24989,24992,),'H1060'=>array(24708,24717,24772,),'H1038'=>array(24724,24726,24913,),'H1053'=>array(),'H1040'=>array(),'H1041'=>array(24922,24966,24991,),'H943'=>array(),'H1002'=>array(19281,),'H1003'=>array(24731,24758,24903,24930,24941,),'H1004'=>array(24733,24767,24787,24983,),'H1006'=>array(24753,),'H1011'=>array(),'H1018'=>array(24763,24817,24818,24819,24820,24821,24826,24827,24829,24832,24840,24842,24843,24849,24852,24858,24878,24893,24896,24897,),'H1019'=>array(),'H1020'=>array(),'H1021'=>array(),'H1022'=>array(),'H1029'=>array(24725,),'H1001'=>array(24730,24732,24740,24756,24761,24942,24952,24956,25002,),'H1009'=>array(24777,24965,),'H1010'=>array(24790,24794,25010,),'H1027'=>array(24912,24928,),'H1030'=>array(24797,),'H1008'=>array(24789,25000,25011,),'H1037'=>array(),'H998'=>array(24768,24781,24943,24959,24975,24985,24995,24998,25014,),'H1031'=>array(24728,24967,),'H1005'=>array(24736,),'H1049'=>array(24938,),'H999'=>array(24744,24793,24984,24994,),'H1039'=>array(),'H1050'=>array(24944,),'H1000'=>array(24746,24764,24786,24957,24971,24993,24999,25006,),'H1007'=>array(24752,),'H954'=>array(),'H944'=>array(24863,25013,),),'GROUP'=>array());

$Quotes_share_write_permission=array('ROLE'=>array('H1048'=>array(24742,24916,),'H1012'=>array(),'H1013'=>array(24800,),'H1014'=>array(24802,24803,),'H1015'=>array(24801,24918,),'H1016'=>array(24799,24901,24924,),'H1017'=>array(),'H1023'=>array(24822,24823,24828,24834,24850,24869,24877,24882,24885,24886,24895,),'H1032'=>array(24804,24909,24910,24933,24982,),'H1043'=>array(24954,),'H1042'=>array(24919,24953,24955,24958,24962,24964,),'H1045'=>array(24986,),'H1047'=>array(),'H1046'=>array(24932,24934,24948,24973,24987,25003,),'H1058'=>array(24743,),'H941'=>array(24709,),'H1028'=>array(24729,24902,25001,),'H1055'=>array(24972,),'H1024'=>array(24792,24899,24905,24915,24939,24960,),'H1054'=>array(24978,24979,),'H1056'=>array(),'H1057'=>array(24996,25015,),'H950'=>array(),'H987'=>array(24748,24749,24765,24769,24785,24831,24835,24836,24855,24861,24961,24977,24988,25016,),'H989'=>array(24866,),'H990'=>array(24770,24838,24853,24859,24872,24883,),'H1059'=>array(24718,24745,24757,24760,24762,),'H942'=>array(24862,),'H1033'=>array(24721,),'H995'=>array(24750,24754,24755,24774,24796,24807,24810,24811,24812,24814,24815,24816,24824,24825,24830,24833,24837,24844,24846,24854,24867,24868,24879,24900,24936,24945,24950,24981,25005,25009,),'H1034'=>array(24719,),'H994'=>array(24720,24722,24737,24738,24779,24847,24870,24873,24880,24881,24888,24907,24920,24925,24931,24947,24968,24990,),'H1035'=>array(24908,),'H1026'=>array(),'H1036'=>array(),'H997'=>array(24778,24783,24890,24892,24914,24929,24949,),'H1051'=>array(24963,),'H1052'=>array(24935,24937,24976,25004,),'H952'=>array(24716,24739,),'H996'=>array(24710,24711,24712,24713,24714,24715,24723,24727,24734,24735,24741,24747,24751,24759,24766,24771,24773,24775,24776,24780,24782,24784,24788,24791,24795,24805,24806,24808,24809,24839,24841,24845,24848,24856,24857,24860,24864,24865,24871,24874,24875,24876,24884,24887,24889,24891,24894,24898,24940,24974,24980,24989,24992,),'H1060'=>array(24708,24717,24772,),'H1038'=>array(24724,24726,24913,),'H1053'=>array(),'H1040'=>array(),'H1041'=>array(24922,24966,24991,),'H943'=>array(),'H1002'=>array(19281,),'H1003'=>array(24731,24758,24903,24930,24941,),'H1004'=>array(24733,24767,24787,24983,),'H1006'=>array(24753,),'H1011'=>array(),'H1018'=>array(24763,24817,24818,24819,24820,24821,24826,24827,24829,24832,24840,24842,24843,24849,24852,24858,24878,24893,24896,24897,),'H1019'=>array(),'H1020'=>array(),'H1021'=>array(),'H1022'=>array(),'H1029'=>array(24725,),'H1001'=>array(24730,24732,24740,24756,24761,24942,24952,24956,25002,),'H1009'=>array(24777,24965,),'H1010'=>array(24790,24794,25010,),'H1027'=>array(24912,24928,),'H1030'=>array(24797,),'H1008'=>array(24789,25000,25011,),'H1037'=>array(),'H998'=>array(24768,24781,24943,24959,24975,24985,24995,24998,25014,),'H1031'=>array(24728,24967,),'H1005'=>array(24736,),'H1049'=>array(24938,),'H999'=>array(24744,24793,24984,24994,),'H1039'=>array(),'H1050'=>array(24944,),'H1000'=>array(24746,24764,24786,24957,24971,24993,24999,25006,),'H1007'=>array(24752,),'H954'=>array(),'H944'=>array(24863,25013,),),'GROUP'=>array());

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

$Projects_share_write_permission=array('ROLE'=>array(),'GROUP'=>array());

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