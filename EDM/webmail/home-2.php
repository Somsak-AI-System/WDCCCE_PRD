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
	//echo $_REQUEST["campaing"];

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
  <!--<SCRIPT type="text/javascript" LANGUAGE="Javascript" SRC="Charts/FusionCharts.js"></SCRIPT>-->

    <SCRIPT type="text/javascript" LANGUAGE="Javascript" SRC="Charts_html5/fusioncharts.js"></SCRIPT>
    <SCRIPT type="text/javascript" LANGUAGE="Javascript" SRC="Charts_html5/fusioncharts.charts.js"></SCRIPT>
    <SCRIPT type="text/javascript" LANGUAGE="Javascript" SRC="Charts_html5/themes/fusioncharts.theme.zune.js"></SCRIPT>
  <!--[if lt IE 7]>
  	<script type="text/javascript" src="http://info.template-help.com/files/ie6_warning/ie6_script_other.js"></script>
  <![endif]-->
  <!--[if lt IE 9]>
  	<script type="text/javascript" src="js/html5.js"></script>
  <![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<style>
tbt_1{
	border:1px solid #FFF;
}
tbt_1 thead {
	background-color:#e1effc;
}

</style>
<script>
	function Search(){
		$("#campaing").val($("#campaingid").combobox('getValue'));
		document.email.submit();
	}
</script>
<?
	$crmid=$_REQUEST["campaing"];
	$sql=" SELECT  * FROM tbt_report_tab_1 WHERE 1 and campaign_id='".$crmid."' ";
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
          	<li><a href="home-1.php?campaing=<?=$crmid?>" >Overview</a></li>
            <li><a href="home-2.php?campaing=<?=$crmid?>" class="current">Click Link</a></li>
            <li><a href="home-3.php?campaing=<?=$crmid?>">Summary</a></li>
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
            <div style="position:absolute;  margin-top: -850px; background-color:#FFF; width:1000px; border:solid 5px #006093; -webkit-border-radius:10px; -moz-border-radius:10px; border-radius:10px;">
                <table cellpadding="2" cellspacing="2" width="95%" style="margin-left:14px;">
                	<tr>
                		<td>&nbsp;</td>
                	</tr>
                	<!--<tr>
                    	<td align="right" colspan="2">
                        	 <input name="campaing" id="campaing" value="<?=$_REQUEST["campaing"]?>" type="hidden"/>
                        	E-Newsletter :
                        	<?
							//$sql="select * from  tbm_province order by convert(province_name using tis620)
							$sql="
							select
							*
							from tbt_report_tab_1
							where 1
							and status='Active'
							";
							$data=$generate->process($sql,"all");
								?>
                            <select  id="campaingid"  style="width:300px;" class="easyui-combobox" name="campaingid">
                            <? for($i=0;$i<count($data);$i++){?>
                            <option value="<?=$data[$i]['campaign_id']?>" <? if($crmid==$data[$i]['campaign_id']){echo "selected";}?>><?=$data[$i]['campaign_name']?></option>
                            <? } ?>
                            </select>
                           <a href="javascript:void(0)" class="icon-search" onClick="Search();" style="text-decoration:none;">&nbsp;&nbsp;&nbsp;&nbsp;</a>
                        </td>
                    </tr>-->
                    <tr>
               		  <td>&nbsp;</td>
                	</tr>
                <tr style="background-color:#e1effc" >
                        <td height="30" colspan="2" align="left" valign="middle" style="font-size:18px;">&nbsp;&nbsp;&nbsp;<strong>Click Link</strong></td>
                  </tr>
                    <tr>
                   	 <td >
                     		<?
							getData3($crmid);
							?>
                            <div id="chartContainer1">FusionCharts will load here!</div>
                            <script type="text/javascript">
                              var myChart1 = new FusionCharts( "Charts/Pie3D.swf","myChartId1", "400", "350", "0", "1" );
                              myChart1.setXMLUrl("XML/data4.xml");
                              myChart1.render("chartContainer1");



                            </script>
                      </td>
                        <?
						$total=0;
						$sql="
						SELECT 
						*
						FROM tbt_report_tab_2 WHERE 1
						and campaign_id='".$crmid."'
						and status='Active'
						order by link_id
						";
						$data=$generate->process($sql,"all");
						for($h=0;$h<count($data);$h++){
							$total=$total+$data[$h]['total_click'];
						}
						?>
                        <td style="padding:60px 0 0 30px;">
                            <table border="1" style="border:0px solid #CCC; font-size:12px;width:500px; line-height:60px;">
                                <!--<tr style="background-color:#e1effc">
                                    <td colspan="2" align="left" style="font-size:18px;">&nbsp;<strong>Link ที่มีผู้อ่านอีเมล์คลิ๊กทั้งหมด</strong></td>
                                </tr>-->
                                <?
								for($i=0;$i<count($data);$i++){
									//echo $data[$i]['total_click']." ".$total."<br>";
									$total_click=$data[$i]['total_click'];
									if($total_click<=0){
										$total_click=0;
									}
									if($total<=0){
										$total_p=1;
									}else{
										$total_p=$total;
									}
								?>
                                 <tr style="border-bottom:1px dashed;border-color:#CCC;">
                                    <td  align="left"><strong style="font-size:22px"><?=number_format(($total_click/$total_p)*100,2)."%"?></strong>&nbsp;-&nbsp;<em><?=$data[$i]['link_name']?></em></td>
                                </tr>
								<?
								}
								?>
                            </table>
                        </td>
                    </tr>
                </table><br/>
                 <table border="1" style="border:0px solid #CCC; font-size:14px;width:950px; line-height:50px; margin-left:14px;">
                    <tr style="background-color:#e1effc">
                        <td align="left" style="font-size:18px;">&nbsp;<strong>Link ที่มีผู้อ่านอีเมล์คลิ๊กทั้งหมด</strong></td>
                        <td  align="right" style="font-size:14px;"><strong>จำนวนคลิ๊ก</strong>&nbsp;</td>
                    </tr>
                    <?
                    for($i=0;$i<count($data);$i++){
                    ?>
                     	<tr style="border-bottom:1px dashed;border-color:#CCC;">
                         <td width="50%"><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p2_001&file_name=<?=$i?>')"/><?=$data[$i]['link_name']?></a></td>
                        <td style="font-size:20px" align="right"><strong><?=number_format($data[$i]['total_click'],0)?></strong>&nbsp;</td>
                    </tr>
                    <?
                    }
                    ?>
                   <tr style="border-bottom:1px dashed;border-color:#CCC;">
                    	<td style="font-size:18px;"><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p2_002&file_name=click_all')"/><strong>รวม</strong></a></td>
                        <td style="font-size:20px;"  align="right"><strong><?=number_format($total,0)?></strong>&nbsp;</td>
                    </tr>
                </table><br/>
           </div>
      </div>
   </div>
</div>

</form>
</body>
</html>
