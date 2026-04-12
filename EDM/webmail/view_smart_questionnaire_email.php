<?
session_start();
require_once("../../config.inc.php");
require_once("../../library/dbconfig.php");
require_once("../../library/generate_MYSQL.php");
require_once('../../include/tcpdf/tcpdf.php');
require_once("../../library/myFunction.php");
require_once("library/get_xml.php");
/*
error_reporting(E_ALL);
ini_set("display_errors", 1);
*/
global $generate;
$generate = new generate($dbconfig ,"DB");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Email Marketing Report</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/reset.css" type="text/css" media="all">
  <link rel="stylesheet" href="css/grid.css" type="text/css" media="all">
  <link rel="stylesheet" href="css/style.css" type="text/css" media="all">
  <link rel="stylesheet" type="text/css" href="themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="themes/icon.css">
<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="js/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="js/cufon-yui.js"></script>
  <script type="text/javascript" src="js/Bell_Gothic_Std_500.font.js"></script>
  <script type="text/javascript" src="js/Bell_Gothic_Std_700.font.js"></script>
  <script type="text/javascript" src="js/cufon-replace.js"></script>
  <!-- <SCRIPT type="text/javascript" LANGUAGE="Javascript" SRC="Charts/FusionCharts.js"></SCRIPT> -->
  <SCRIPT type="text/javascript" LANGUAGE="Javascript" SRC="Charts_html5/fusioncharts.js"></SCRIPT>
  <SCRIPT type="text/javascript" LANGUAGE="Javascript" SRC="Charts_html5/fusioncharts.charts.js"></SCRIPT>
  <SCRIPT type="text/javascript" LANGUAGE="Javascript" SRC="Charts_html5/themes/fusioncharts.theme.zune.js"></SCRIPT>
  <!--[if lt IE 7]>
  	<script type="text/javascript" src="http://info.template-help.com/files/ie6_warning/ie6_script_other.js"></script>
  <![endif]-->
  <!--[if lt IE 9]>
  	<script type="text/javascript" src="js/html5.js"></script>
  <![endif]-->
</head>
<script>
	function Search(){
		$("#campaing").val($("#campaingid").combobox('getValue'));
		document.email.submit();
	}
</script>
<style>
tbt_1{
	border:1px solid #FFF;
}
tbt_1 thead {
	background-color:#e1effc;
}
</style>
<?
	$crmid=$_REQUEST["campaing"];
	$sql="
	select
	*
	from tbt_report_tab_1
	where 1
	and campaign_id='".$crmid."' 
	";
	$data=$generate->process($sql,"all");  

?>
<body class="header-bg">
<form name="email" method="post" id="email">

  <!-- header -->
  <header>
     <div style="height:1000px;">
      <div class="container_24">
      	<div class="logo"><img src="images/logo.png" alt=""></div>
        <nav>
          <ul>
          	<li><a href="view_smart_questionnaire_email.php?campaing=<?=$crmid?>" class="current">Overview</a></li>
            <li><a href="home-2.php?campaing=<?=$crmid?>">Click Link</a></li>
            <li><a href="questionnaire-home-3.php?campaing=<?=$crmid?>">Summary</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </header>
 <!-- content -->
<!--<section id="content" >-->
<div class="container_24">
    <!--<div class="row">-->
    <div>
        <div class="wrapper" >
            <div style="position:absolute;">
             <div style="position:absolute; margin-top: -850px; background-color:#FFF; width:1000px; border:solid 5px #006093; -webkit-border-radius:10px; -moz-border-radius:10px; border-radius:10px;">
            	<table cellpadding="2" cellspacing="2" width="95%">               
                 <tr>
                	<td>&nbsp;</td>
                </tr>
                
                <tr>
                	<td>&nbsp;</td>
                </tr>
                <?
                $sql=" SELECT  * FROM tbt_report_tab_1 WHERE 1 and campaign_id='".$crmid."' ";
                $data=$generate->process($sql,"all");

                $sql7="select smartquestionnaireid from aicrm_smartquestionnaire where smartquestionnaireid='".$crmid."' limit 1";
                $data7 = $generate->process($sql7,"all");

                $table="tbt_email_log_smartquestionnaireid_".$data7[0]['smartquestionnaireid'];
                $sql8="select from_module as Module,from_id as CRMID ,to_name as Name ,to_email as Email ,domain_name as Domain ,DATE_FORMAT( date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date  from ".$table ." where 1 AND status =2";
                $data8=$generate->process($sql8,"all");
                $kk = count($data8);


                $sql="SELECT * from ".$table."";  //จำนวนเมล์ที่ส่งออกทั้งหมด
                $data_all=$generate->process($sql,"all");
                $count_data_all = count($data_all);

                 $sql="SELECT * FROM ".$table."
                WHERE
                    
                      (duplicate = 1 AND active = 0)
                      OR invalid_email = 1
                      OR unsubscribe = 1
                      OR to_email IS NULL                    
                "; 
                $data_problem=$generate->process($sql,"all");
                $count_data_problem = count($data_problem);   //จำนวนเมล์ที่มีปัญหา

               // print_r($sql);exit();



                
                $sql="SELECT * FROM ".$table." a
                WHERE
                  NOT a.id IN (
                  SELECT
                    b.id
                  FROM
                    ".$table." b
                  WHERE
                    (
                      (b.duplicate = 1 AND b.active = 0)
                      OR b.invalid_email = 1
                      OR b.unsubscribe = 1
                      OR b.to_email IS NULL
                    )

                  )"; 
                $data_send=$generate->process($sql,"all");
                $count_data_send = count($data_send);   //รายการที่ทำการส่ง

               // print_r($sql);



                /*$sql="SELECT
                  *
                FROM
                  ".$table." a
                WHERE
                  NOT a.id IN (
                  SELECT
                    b.id
                  FROM
                    ".$table." b
                  WHERE
                    (
                      (b.duplicate = 1 AND b.active = 0)
                      OR b.invalid_email = 1
                      OR b.unsubscribe = 1
                      OR b.to_email IS NULL
                    )
                    OR b.status != 2
                  )
                "; */
                $sql="SELECT
                  *
                FROM
                  ".$table." 
                WHERE
                 status = 2 and invalid_email = 0
                  
                "; 
                $data_no_pass=$generate->process($sql,"all");
                $count_data_no_pass = count($data_no_pass);   //จำนวนเมล์ที่ส่งไม่ผ่านทั้งหมด


                //print_r($sql);




                /* $sql="SELECT
                  *
                FROM
                  ".$table." a
                WHERE
                  NOT a.id IN (
                  SELECT
                    b.id
                  FROM
                    ".$table." b
                  WHERE
                    (
                      (b.duplicate = 1 AND b.active = 0)
                      OR b.invalid_email = 1
                      OR b.unsubscribe = 1
                      OR b.to_email IS NULL
                    )
                    OR b.status != 1
                  )"; */
                   $sql="SELECT * FROM ".$table." WHERE status = 1 ";
                $data_pass=$generate->process($sql,"all");
                $count_data_pass = count($data_pass);   //จำนวนเมล์ที่ส่งผ่านทั้งหมด



               
                ?>
                <tr style="background-color:#e1effc; line-height:30px;" >
                    <td colspan="2" align="left" valign="middle" style="font-size:18px;">&nbsp;&nbsp;&nbsp;<strong>Overview</strong></td>
                </tr>
                <tr style="border-bottom:1px dashed;border-color:#CCC;line-height:30px;">
                  <td height="25" align="left" valign="middle" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-Newsletter : </td>
                  <td valign="middle" ><?=$data[0]['campaign_name']?></td>
              </tr>
              	<? 
				  if($data[0]['start_date']!="" and $data[0]['start_date']!="0000-00-00 00:00:00"){
				?>
                <tr style="border-bottom:1px dashed;border-color:#CCC;line-height:30px;">
                  <td height="25" align="left" valign="middle" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันที่เริ่มส่ง : </td>
                  <td valign="middle" ><?=date('d-m-Y H:i:s',strtotime($data[0]['start_date']));?></td>
              </tr>
               <?
				  }
			   ?>
               <? 
				  if($data[0]['end_date']!="" and $data[0]['end_date']!="0000-00-00 00:00:00"){
				?>
                <tr style="border-bottom:1px dashed;border-color:#CCC;line-height:30px;">
                  <td height="25" align="left" valign="middle" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันส่งเสร็จ : </td>
                  <td valign="middle" ><?=date('d-m-Y H:i:s',strtotime($data[0]['end_date']));?></td>
              </tr>
               <?
				  }
			   ?>
               <? 
				  if($data[0]['stop_date']!="" and $data[0]['stop_date']!="0000-00-00 00:00:00"){
				?>
                <tr style="border-bottom:1px dashed;border-color:#CCC;line-height:30px;">
                  <td height="25" align="left" valign="middle" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันเก็บสถิติล่าสุด : </td>
                  <td valign="middle" ><?=date('d-m-Y H:i:s',strtotime($data[0]['stop_date']));?></td>
              </tr>
               <?
				  }
			   ?>
           </table>
                <br/>
                <table cellpadding="2" cellspacing="2" width="98%" style="line-height:30px; border:1px solid #CCC; margin:10px">
                    <tr>
                        <td >
                        	<? 
							getData1($crmid, 'smartquestionnaire');
							?>
                            <div id="chartContainer1">FusionCharts will load here!</div>          
                          <script type="text/javascript"><!--         
                              var myChart1 = new FusionCharts( "Charts/Column3D.swf","myChartId1", "400", "300", "0", "1" );
                              myChart1.setXMLUrl("XML/data1.xml");
                              myChart1.render("chartContainer1");
                            // -->     
                            </script>      
                        </td>
                        <td >
                            <table border="1" style="border:0px solid #CCC; font-size:14px;width:500px; line-height:75px;margin:30px 0 0 0;">
                            
                            
                               <tr style="border-bottom:1px dashed;border-color:#CCC;">
                                    <td width="70%"><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_001&file_name=จำนวนอีเมล์ทั้งหมด')"/>จำนวนอีเมล์ทั้งหมด</a></td>
                                    <td  align="right" valign="middle"><div style="background-color:#00BFFF; width:15px; height:15px;"></div></td>
                                    <td style="font-size:20px" align="right"> <strong><?=number_format($count_data_all)?></strong></td>
                                </tr>
                                <tr style="border-bottom:1px dashed;border-color:#CCC;">
                                    <td><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_008&file_name=จำนวนอีเมล์ที่มีเงื่อนไข')"/>จำนวนอีเมล์ที่มีเงื่อนไข</a></td>
                                     <td align="right" valign="middle"><div style="background-color:#838f98; width:15px; height:15px;"></div></td>
                                    <td style="font-size:20px" align="right"> <strong><?=number_format($count_data_problem)?></strong>
                                    </td>
                                </tr>

                                <tr style="border-bottom:1px dashed;border-color:#CCC;">
                                    <td><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_118&file_name=รายการที่ทำการส่ง')"/>รายการที่ทำการส่ง</a></td>
                                     <td align="right" valign="middle"><div style="background-color:black; width:15px; height:15px;"></div></td>
                                    <td style="font-size:20px" align="right"> <strong><?php echo number_format($count_data_send); ?></strong>
                                    </td>
                                </tr>
                                 <tr style="border-bottom:1px dashed;border-color:#CCC;">
                                  <td><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p3_002&file_name=จำนวนเมล์ที่ส่งไม่ผ่านทั้งหมด')"/>จำนวนเมล์ที่ส่งไม่ผ่านทั้งหมด</a></td>
                                   <td align="right" valign="middle"><div style="background-color:#FF0000; width:15px; height:15px;"></div></td>
                                  <td style="font-size:20px" align="right"><strong><?php echo number_format($count_data_no_pass); ?></strong></td>
                                </tr>
                                <tr style="border-bottom:1px dashed;border-color:#CCC;">
                                  <td><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p3_333&file_name=จำนวนเมล์ที่ส่งผ่านทั้งหมด')"/>จำนวนเมล์ที่ส่งผ่านทั้งหมด</a></td>
                                     <td align="right" valign="middle"><div style="background-color:#32CD32; width:15px; height:15px;"></div></td>
                                  <td style="font-size:20px" align="right"> <strong><?php echo number_format($count_data_pass); ?></strong></td>
                                </tr>
                             
                            </table>
                        </td>
                    </tr>
                </table>
                <br/>
             <?
				getData2($crmid);
				?>
                 <table cellpadding="2" cellspacing="2" width="98%" style="line-height:30px; border:1px solid #CCC; margin:10px">
                    <tr>
                        <td >
                            <div id="chartContainer2">FusionCharts will load here!</div>          
                          <script type="text/javascript"><!--         
                              var myChart2 = new FusionCharts( "Charts/Pie3D.swf","myChartId2", "400", "350", "0", "1" );
                              myChart2.setXMLUrl("XML/data2.xml");
                              myChart2.render("chartContainer2");
                            // -->     
                            </script>      
                        </td>
                        <td >
                            <table border="1" style="border:0px solid #CCC; font-size:14px;width:500px; line-height:70px;">
                            	<!--<tr style="background-color:#e1effc">
                                    <td colspan="2" align="left" style="font-size:18px; line-height:30px;">&nbsp;&nbsp;&nbsp;รายละเอียด</td>
                                </tr>-->
                               <tr style="border-bottom:1px dashed;border-color:#CCC;">
                                    <td width="70%"><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_004&file_name=hotmail')"/>hotmail</a></td>
                                     <td align="right" valign="middle"><div style="background-color:#0000FF; width:15px; height:15px;"></div></td>
                                    <td  style="font-size:20px" align="right"><strong><?=number_format($data[0]['email_hotmail'],0)?></strong></td>
                                </tr>
                                  <tr style="border-bottom:1px dashed;border-color:#CCC;">
                                    <td><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_005&file_name=yahoo')"/>yahoo</a></td>
                                     <td align="right" valign="middle"><div style="background-color:#A020F0; width:15px; height:15px;"></div></td>
                                    <td style="font-size:20px" align="right"><strong><?=number_format($data[0]['email_yahoo'],0)?></strong></td>
                                </tr>
                                 <tr style="border-bottom:1px dashed;border-color:#CCC;">
                                    <td><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_006&file_name=gmail')"/>gmail</a></td>
                                     <td align="right" valign="middle"><div style="background-color:#FFA500; width:15px; height:15px;"></div></td>
                                    <td style="font-size:20px" align="right"><strong><?=number_format($data[0]['email_gmail'],0)?></strong></td>
                                </tr>
                                <tr style="border-bottom:1px dashed;border-color:#CCC;">
                                    <td><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_007&file_name=other')"/>other</a></td>
                                     <td align="right" valign="middle"><div style="background-color:#98FB98; width:15px; height:15px;"></div></td>
                                    <td style="font-size:20px" align="right"><strong><?=number_format($data[0]['email_others'],0)?></strong></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table><br/>
          </div>
      </div>
   </div>
</div>

</form>
</body>
</html>
