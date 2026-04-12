<?
session_start();
include("../config.inc.php");
include_once("../library/dbconfig.php");
include_once("../library/myFunction.php");
include_once("../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

ini_set('memory_limit', '1024M');

$crmid=$_REQUEST["crmid"];

$sql="select aicrm_smartsms.* from aicrm_smartsms 
		 Inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
		 where aicrm_smartsms.smartsmsid='".$crmid."' and aicrm_crmentity.deleted=0";
$data = $myLibrary_mysqli->select($sql);

$sqlall=" select * , aicrm_smartsms_leadsrel.leadid as ref_id
	FROM aicrm_smartsms
	LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_smartsms_leadsrel ON aicrm_smartsms_leadsrel.smartsmsid = aicrm_smartsms.smartsmsid
	WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
	AND aicrm_crmentity.deleted =0 and aicrm_smartsms_leadsrel.leadid is not NULL
	union all 

	SELECT *,aicrm_smartsms_opportunityrel.opportunityid as ref_id
	FROM aicrm_smartsms
	LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_smartsms_opportunityrel ON aicrm_smartsms_opportunityrel.smartsmsid = aicrm_smartsms.smartsmsid
	WHERE aicrm_smartsms.smartsmsid =  '".$crmid."'
	AND aicrm_crmentity.deleted =0 and aicrm_smartsms_opportunityrel.opportunityid is not NULL
	union all 

	SELECT *,aicrm_smartsms_accountsrel.accountid as ref_id
	FROM aicrm_smartsms
	LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_smartsms_accountsrel on aicrm_smartsms_accountsrel.smartsmsid = aicrm_smartsms.smartsmsid
	WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
	AND aicrm_crmentity.deleted =0 and aicrm_smartsms_accountsrel.accountid is not NULL
	union all

	SELECT *,aicrm_smartsms_contactsrel.contactid as ref_id
	FROM aicrm_smartsms
	LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
	LEFT JOIN aicrm_smartsms_contactsrel on aicrm_smartsms_contactsrel.smartsmsid = aicrm_smartsms.smartsmsid
	WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
	AND aicrm_crmentity.deleted =0 and aicrm_smartsms_contactsrel.contactid is not NULL
	";
$data1 = $myLibrary_mysqli->select($sqlall);
$c_data_sms6 = count($data1);

$sqldup="
		select count(duprecord) duprecord
		from
			(
			select Phone,count(*)-1 duprecord
			from
			(
				select concat( \"\" ,aicrm_leaddetails.mobile) as Phone
				FROM aicrm_smartsms
				LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
				LEFT JOIN aicrm_smartsms_leadsrel ON aicrm_smartsms_leadsrel.smartsmsid = aicrm_smartsms.smartsmsid
				LEFT JOIN aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
				LEFT JOIN aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
				WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
				AND aicrm_crmentity.deleted =0 AND aicrm_smartsms_leadsrel.leadid is not NULL
				union all
				
				select concat( \"\" ,aicrm_account.mobile) as Phone
				FROM aicrm_smartsms
				LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
				LEFT JOIN aicrm_smartsms_accountsrel on aicrm_smartsms_accountsrel.smartsmsid = aicrm_smartsms.smartsmsid
				left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
				WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
				AND aicrm_crmentity.deleted =0 and aicrm_smartsms_accountsrel.accountid is not NULL
				union all 
				
				select concat( \"\" ,aicrm_contactdetails.mobile) as Phone
				FROM aicrm_smartsms
				LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartsms.smartsmsid
				LEFT JOIN aicrm_smartsms_contactsrel on aicrm_smartsms_contactsrel.smartsmsid = aicrm_smartsms.smartsmsid
				LEFT JOIN aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
				LEFT JOIN aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
				WHERE aicrm_smartsms.smartsmsid = '".$crmid."'
				AND aicrm_crmentity.deleted =0 and aicrm_smartsms_contactsrel.contactid is not NULL
				
			) alldup
			where Phone <> ''
			group by Phone
			having count(*) > 1
		) alldup ";

$data2 = $myLibrary_mysqli->select($sqldup);
$c_data_sms7 = $data2[0]['duprecord'];


$sqlunsubscribe=" select 
		aicrm_account.mobile as mobile
		from aicrm_smartsms_accountsrel
		left join aicrm_account  on aicrm_smartsms_accountsrel.accountid=aicrm_account.accountid
		left join aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
		left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid
		left join aicrm_accountshipads on aicrm_accountshipads.accountaddressid=aicrm_account.accountid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
		where 1
		and aicrm_crmentity.deleted=0
		and (aicrm_account.smsstatus = 'InActive' )
		and aicrm_smartsms_accountsrel.smartsmsid= '".$crmid."'
		and mobile<>''
		group by mobile
		union
		select 
		aicrm_leaddetails.mobile as mobile
		from aicrm_smartsms_leadsrel
		left join aicrm_leaddetails  on aicrm_smartsms_leadsrel.leadid=aicrm_leaddetails.leadid
		left join aicrm_leadscf on aicrm_leaddetails.leadid=aicrm_leadscf.leadid
		left join aicrm_leadaddress on aicrm_leadaddress.leadaddressid=aicrm_leaddetails.leadid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_leaddetails.leadid
		where 1
		and aicrm_crmentity.deleted=0
		and (aicrm_leaddetails.smsstatus  = 'InActive' )
		and aicrm_smartsms_leadsrel.smartsmsid='".$crmid."'
		and mobile<>''
		group by mobile
		union
	    select 
	    mobile
	    from aicrm_smartsms_contactsrel
	    left join aicrm_contactdetails on aicrm_smartsms_contactsrel.contactid=aicrm_contactdetails.contactid
	    left join aicrm_contactscf on aicrm_contactdetails.contactid=aicrm_contactscf.contactid
	    left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
	    where 1
	    and aicrm_crmentity.deleted=0
		and (aicrm_contactdetails.smsstatus  = 'InActive' )
	    and aicrm_smartsms_contactsrel.smartsmsid='".$crmid."'
	    and mobile<>''
		group by mobile 

	";
$data3 = $myLibrary_mysqli->select($sqlunsubscribe);
$c_data_sms8 = count($data3);


$FileName = "XML/data4.xml";
$FileHandle = fopen($FileName, 'w') or die("can't open file");
$c_data_sms1=0;
$c_data_sms2=0;
$c_data_sms3=0;
$c_data_sms4=0;
$c_data_sms5=0;

if(count($data)>0){
	

	foreach ($data as $key => $value) {
		$new_table="tbt_sms_log_smartsmsid_".$data[$i]["smartsmsid"];
		$sql="select * from ".$new_table." where smartsmsid='".$crmid."'";
		$data_sms1 = $myLibrary_mysqli->select($sql);
		if(count($data_sms1)>0){
			$c_data_sms1=$c_data_sms1+count($data_sms1);
		}

		$sql="select * from ".$new_table." where smartsmsid='".$crmid."' and invalid_sms=1";//sms มีปัญหา
		$data_sms2 = $myLibrary_mysqli->select($sql);
		if(count($data_sms2)>0){
			$c_data_sms2=$c_data_sms2+count($data_sms2);
		}

		$sql="select * from ".$new_table." where smartsmsid='".$crmid."' and status=0 and invalid_sms=0 and date_start <= '1900-01-01'";//sms รอส่ง
		$data_sms3 = $myLibrary_mysqli->select($sql);
		if(count($data_sms3)>0){
			$c_data_sms3=$c_data_sms3+count($data_sms3);
		}

		$sql="select * from ".$new_table." where smartsmsid='".$crmid."' and ( status=1  or  date_start >= '1900-01-01')";//sms ส่งผ่าน
		$data_sms4 = $myLibrary_mysqli->select($sql);
		if(count($data_sms4)>0){
			$c_data_sms4=$c_data_sms4+count($data_sms4);
		}

		$sql="select * from ".$new_table." where smartsmsid='".$crmid."' and status=2";//sms ส่งไม่ผ่าน
		$data_sms5 = $myLibrary_mysqli->select($sql);
		if(count($data_sms5)>0){
			$c_data_sms5=$c_data_sms5+count($data_sms5);
		}

	}

}//if


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>View Send SMS</title>
</head>
<link rel="stylesheet" type="text/css" href="themes/default/easyui.css">
<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
<link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
<link rel="stylesheet" href="css/grid.css" type="text/css" media="all">
<link rel="stylesheet" type="text/css" href="themes/icon.css">
<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="js/jquery.easyui.min.js"></script>

<script type="text/javascript" src="js/apexcharts.js"></script>

<script>
	$.noConflict();
</script>

<script>
jQuery(document).ready(function(){
	var c_data_sms6 = "<?= $c_data_sms6; ?>";
	var c_data_sms7 = "<?= $c_data_sms7; ?>";
	var c_data_sms8 = "<?= $c_data_sms8; ?>";
	var c_data_sms2 = "<?= $c_data_sms2; ?>";
	var c_data_sms3 = "<?= $c_data_sms3; ?>";
	var c_data_sms4 = "<?= $c_data_sms4; ?>";
	var c_data_sms5 = "<?= $c_data_sms5; ?>";
	//console.log(sendall);
	var options = {
	  series: [{
	  	name: ['จำนวน'],
	 	data: [c_data_sms6,c_data_sms7,c_data_sms8,c_data_sms2,c_data_sms3,c_data_sms4,c_data_sms5]
		}],
	  chart: {
	  	height: 350,
	  	type: 'bar',
	  	events: {
	    	click: function(chart, w, e) {
	    },
	    toolbar: {
          show: false
        },
	  }
		},
		colors: ['#b4daf8','#838f98','#CC0000','#f6c221','#91bd0e','#ff9551','#0c9393'],//#FF4560
		plotOptions: {
		  bar: {
		    columnWidth: '45%',
		    distributed: true,
		  }
		},
		dataLabels: {
		  enabled: false
		},
		legend: {
		  show: false
		},
		title: {
	      text: ''
	    },
	    tooltip: {
		    x: {
		      show: true
		    },
		    y: {
		      formatter: function(value){
		      	return value.toString();
		       }
		    }
		  },
		xaxis: {
		  categories: ['ทั้งหมด','SMS ซ้ำ',['รายการที่ไม่ต้องการ', 'รับข่าวสาร'],'รายการที่มีปัญหา','รายการที่รอส่ง','รายการที่ส่งผ่าน','รายการที่ส่งไม่ผ่าน'],
		  labels: {
		    style: {
		      fontSize: '12px'
		    }
		  }
		}
	};

	var chart = new ApexCharts(document.querySelector("#chartContainer4"), options);
	chart.render();

});

</script>

<!--<body class="easyui-layout" >-->
<body class="header-bg">
<form name="email" method="post" id="email">
<header>
	<div style="height:850px;"> </div>
</header>
<div class="container_24">
    <div>
        <div class="wrapper">
             <div style="position:absolute; margin-top: -800px; background-color:#FFF; width:1000px; border:solid 5px #ff6300; -webkit-border-radius:10px; -moz-border-radius:10px; border-radius:10px;">
                <table cellpadding="2" cellspacing="2" width="95%" style="margin-left:14px;"> 
                	<tr>
                		<td>&nbsp;</td>
                	</tr>
                    <tr>
               		  <td>&nbsp;</td>
                	</tr>
                    <tr style="background-color:#e1effc" >
                        <td height="30" colspan="2" align="left" valign="middle" style="font-size:18px;">&nbsp;&nbsp;&nbsp;<strong>Summary</strong></td>
                    </tr>
                    <tr>
                
                        <td align="center">
                        	<div id="chartContainer4" style="border:none;"></div>
                        </td>
                        
                    </tr>
                </table>
                <br/>
	            <table border="1" style="border:0px solid #CCC; font-size:14px;width:950px; line-height:40px; margin-left:14px;">
		            <tr style="background-color:#e1effc">
		                <td  align="left" style="font-size:18px;">ผลสรุปการส่ง <?=$data[0]['smartsms_name'] ?></td>
		                <td>&nbsp;</td>
		                 <td  align="right" style="font-size:14px;"><strong>จำนวน</strong>&nbsp;</td>
		            </tr>
		            <tr style="border-bottom:1px dashed;border-color:#CCC;">
		                <td width="85%"><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_001&file_name=SMS_ทั้งหมด')"/>SMS ทั้งหมด</a></td>
		                <td align="right" valign="middle"><div style="background-color:#b4daf8; width:15px; height:15px;"></div></td>
		                <td style="font-size:20px" align="right"><strong><?=number_format($c_data_sms6,0)?></strong>&nbsp;</td>
		            </tr>
		            <tr style="border-bottom:1px dashed;border-color:#CCC;">
		                <td width="85%"><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_006&file_name=SMS_ซ้ำ')"/>SMS ซ้ำ</a></td>
		                <td align="right" valign="middle"><div style="background-color:#838f98; width:15px; height:15px;"></div></td>
		                <td style="font-size:20px" align="right"><strong><?=number_format($c_data_sms7,0)?></strong>&nbsp;</td>
		            </tr>
		            <tr style="border-bottom:1px dashed;border-color:#CCC;">
		                <td width="85%"><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_008&file_name=รายการทีไม่ต้องการรับข่าวสาร')"/>รายการทีไม่ต้องการรับข่าวสาร</a></td>
		                <td align="right" valign="middle"><div style="background-color:#CC0000; width:15px; height:15px;"></div></td>
		                <td style="font-size:20px" align="right"><strong><?=number_format($c_data_sms8,0)?></strong>&nbsp;</td>
		            </tr>
		              <tr style="border-bottom:1px dashed;border-color:#CCC;">
		                <td ><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_002&file_name=รายการที่มีปัญหา')"/>รายการที่มีปัญหา</a></td>
		                <td align="right" valign="middle"><div style="background-color:#f6c221; width:15px; height:15px;"></div></td>
		                <td style="font-size:20px" align="right"><strong><?=number_format($c_data_sms2,0)?></strong>&nbsp;</td>
		            </tr>
		             <tr style="border-bottom:1px dashed;border-color:#CCC;">
		                <td><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_003&file_name=รายการที่รอส่ง')"/>รายการที่รอส่ง</a></td>
		                <td align="right" valign="middle"><div style="background-color:#91bd0e; width:15px; height:15px;"></div></td>
		                <td style="font-size:20px" align="right"><strong><?=number_format($c_data_sms3,0)?></strong>&nbsp;</td>
		            </tr>
		              <tr style="border-bottom:1px dashed;border-color:#CCC;">
		                <td ><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_004&file_name=รายการที่ส่งผ่าน')"/>รายการที่ส่งผ่าน</a></td>
		                <td align="right" valign="middle"><div style="background-color:#ff9551; width:15px; height:15px;"></div></td>
		                <td style="font-size:20px" align="right"><strong><?=number_format($c_data_sms4,0)?></strong>&nbsp;</td>
		            </tr>
		             <tr style="border-bottom:1px dashed;border-color:#CCC;">
		                <td><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_005&file_name=รายการที่ส่งไม่ผ่าน')"/>รายการที่ส่งไม่ผ่าน</a></td>
		                <td align="right" valign="middle"><div style="background-color:#0c9393; width:15px; height:15px;"></div></td>
		                <td style="font-size:20px" align="right"><strong><?=number_format($c_data_sms5,0)?></strong>&nbsp;</td>
		            </tr>
	       		</table>
       			<br/>
           </div>
      </div>
   </div>
</div>

</form> 
 <!-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->       
        
</body>
</html>