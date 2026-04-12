<?
session_start();
include("../config.inc.php");
include_once("../library/dbconfig.php");
include_once("../library/myFunction.php");
include_once("../library/myLibrary_mysqli.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

ini_set('memory_limit', '1024M');

$crmid = $_REQUEST["crmid"];
$table = 'tbt_announcement_log_id_'.$crmid;

$sql = "SELECT * FROM aicrm_announcement where announcementid = '".$crmid."' ";
$data = $myLibrary_mysqli->select($sql);

$sql1 = "SELECT count(id) as sendall FROM aicrm_announcement_usersrel where announcementid = '".$crmid."' ";
$data1 = $myLibrary_mysqli->select($sql1);
$sendall = number_format($data1[0]['sendall'],0);

$sql2 = "SELECT count(id) as wait FROM ".$table." where send = 0 ";
$data2 = $myLibrary_mysqli->select($sql2);
$wait = number_format($data2[0]['wait'],0);

$sql3 = "SELECT count(id) as send FROM ".$table." where send = 1 ";
$data3 = $myLibrary_mysqli->select($sql3);
$send = number_format($data3[0]['send'],0);

$sql4 ="SELECT count(id) as flagread FROM ai_notification where  crmid = '".$crmid."' and module = 'Announcement' and flagread = 1 ";
$data4 = $myLibrary_mysqli->select($sql4);
$read = number_format($data4[0]['flagread'],0);

$sql5 ="SELECT count(id) as accept FROM ai_notification where  crmid = '".$crmid."' and module = 'Announcement' and flagaccept = 1 ";
$data5 = $myLibrary_mysqli->select($sql5);
$accept = number_format($data5[0]['accept'],0);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Report Announcement</title>
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
	var sendall = "<?= $sendall; ?>";
	var wait = "<?= $wait; ?>";
	var send = "<?= $send; ?>";
	var read = "<?= $read; ?>";
	var accept = "<?= $accept; ?>";
	//console.log(sendall);
	var options = {
	  series: [{
	  	name: ['จำนวน'],
	 	data: [sendall,wait,send,read,accept]
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
		colors: ['#0064Fa','#feb018','#2DD22F','#A9A9A9','#FF4560'],//#FF4560
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
		  categories: ['ทั้งหมด','รายการที่รอส่ง','รายการที่ส่งผ่าน','รายการที่อ่านแล้ว','รายการที่รับทราบแล้ว'],
		  labels: {
		    style: {
		      //colors: colors,
		      fontSize: '12px'
		    }
		  }
		}
	};

	var chart = new ApexCharts(document.querySelector("#chartContainer4"), options);
	chart.render();

});

function fnSearch(i){
	var branch_id1=jQuery("#branch_id1").combobox('getValue');
	var branch_id2=jQuery("#branch_id2").combobox('getValue');
	var year=jQuery("#year").val();
	var productgroup=jQuery("#productgroup").combobox('getValue');
	var salename=jQuery("#salename").val();
	var orderbyid=jQuery("#orderbyid").val();
	var graphno=new String(i);
	
	var jqxhr = jQuery.post("getXML1.php",
	{
		branch_id1:branch_id1,
		branch_id2:branch_id2,
		year:year,
		productgroup:productgroup,
		salename:salename,
		orderbyid:orderbyid,
		graphno:graphno
	}
	, function() {
		//alert( "success" );
		/*var win = jQuery.messager.progress({
			title:'กรุณารอสักครู่',
			msg:'กำลังโหลดข้อมูล...'
		});
		setTimeout(function(){
			changeGraph(i);
			jQuery.messager.progress('close');
		},8000)*/
	})
	.done(function() {
		//alert( "second success" );
		var win = jQuery.messager.progress({
			title:'กรุณารอสักครู่',
			msg:'กำลังโหลดข้อมูล...'
		});
		setTimeout(function(){
			changeGraph(i);
			jQuery.messager.progress('close');
		},8000)
	})
	.fail(function() {
		//alert( "error" );
	})
	.always(function() {
		//alert( "finished" );
	});
	// Perform other work here ...
	// Set another completion function for the request above
	jqxhr.always(function() {
	//alert( "second finished" );
	});
	//changeGraph(i);
}
</script>

<!--<body class="easyui-layout" >-->
<body class="header-bg">
<form name="email" method="post" id="email">
  <header>
    <div style="height:850px;">
      <!-- <div class="container_24">
      	<div class="logo"><img src="images/logo_sms.png" alt=""></div>
      </div> -->
    </div>
  </header>
<div class="container_24">
    <div>
        <div class="wrapper">
             <div style="position:absolute; margin-top: -800px; background-color:#FFF; width:1000px; border:solid 5px #ff6300/*#006093*/; -webkit-border-radius:10px; -moz-border-radius:10px; border-radius:10px;">
                <table cellpadding="2" cellspacing="2" width="95%" style="margin-left:14px;"> 
                	<tr>
                		<td>&nbsp;</td>
                	</tr>
                    <tr>
               		  <td>&nbsp;</td>
                	</tr>
                    <tr style="background-color:#e1effc" >
                        <td height="30" colspan="2" align="left" valign="middle" style="font-size:18px;">&nbsp;&nbsp;&nbsp;<strong>ประกาศข่าวสาร</strong></td>
                    </tr>
                    <tr>
                
                        <td align="center">
                        	<div id="chartContainer4" style="border:none;"></div>
                        </td>

                        <!-- <script type="text/javascript">        
						  var myChart4 = new FusionCharts( "Charts/Column2D.swf?noCache=1", "myChartId4", "950", "400", "0", "1" );
						  myChart4.setXMLUrl(escape("XML/data4.xml?noCache"+new Date().valueOf()));
						  myChart4.render("chartContainer4");
						</script> -->
                        
                    </tr>
                </table><br/>
              <table border="1" style="border:0px solid #CCC; font-size:14px;width:950px; line-height:40px; margin-left:14px;">
            <tr style="background-color:#e1effc">
                <td  align="left" style="font-size:18px;">ผลสรุปการส่ง <?=$data[0]['announcement_name'] ?></td>
                <td>&nbsp;</td>
                 <td  align="right" style="font-size:14px;"><strong>จำนวน</strong>&nbsp;</td>
            </tr>

          	<tr style="border-bottom:1px dashed;border-color:#CCC;">
                <td width="85%"><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_001&file_name=ประกาศทั้งหมด')"/>ทั้งหมด</a></td>
                <td align="right" valign="middle"><div style="background-color:#0064Fa; width:15px; height:15px;"></div></td>
                <td style="font-size:20px" align="right"><strong><?=number_format($data1[0]['sendall'],0)?></strong>&nbsp;</td>
            </tr>
            
            <tr style="border-bottom:1px dashed;border-color:#CCC;">
                <td><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_002&file_name=รายการที่รอส่ง')"/>รายการที่รอส่ง</a></td>
                <td align="right" valign="middle"><div style="background-color:#feb018; width:15px; height:15px;"></div></td>
                <td style="font-size:20px" align="right"><strong><?=number_format($data2[0]['wait'],0)?></strong>&nbsp;</td>
            </tr>
            
            <tr style="border-bottom:1px dashed;border-color:#CCC;">
                <td ><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_003&file_name=รายการที่ส่งผ่าน')"/>รายการที่ส่งผ่าน</a></td>
                <td align="right" valign="middle"><div style="background-color:#2DD22F; width:15px; height:15px;"></div></td>
                <td style="font-size:20px" align="right"><strong><?=number_format($data3[0]['send'],0)?></strong>&nbsp;</td>
            </tr>
            
            <tr style="border-bottom:1px dashed;border-color:#CCC;">
                <td><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_004&file_name=รายการที่อ่านแล้ว')"/>รายการที่อ่านแล้ว</a></td>
                <td align="right" valign="middle"><div style="background-color:#A9A9A9; width:15px; height:15px;"></div></td>
                <td style="font-size:20px" align="right"><strong><?=number_format($data4[0]['flagread'],0)?></strong>&nbsp;</td>
            </tr>
            
            <tr style="border-bottom:1px dashed;border-color:#CCC;">
                <td><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_005&file_name=รายการที่รับทราบแล้ว')"/>รายการที่รับทราบแล้ว</a></td>
                <td align="right" valign="middle"><div style="background-color:#FF4560; width:15px; height:15px;"></div></td>
                <td style="font-size:20px" align="right"><strong><?=number_format($data5[0]['accept'],0)?></strong>&nbsp;</td>
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