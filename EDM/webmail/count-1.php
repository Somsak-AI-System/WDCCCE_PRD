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
                </table><br/>
                 <table border="1" style="border:0px solid #CCC; font-size:14px;width:950px; line-height:40px; margin-left:14px;">

                <?
                $sql=" SELECT  * FROM tbt_report_tab_1 WHERE 1 and campaign_id='".$crmid."' ";
                $data=$generate->process($sql,"all");

                $sql7="select smartemailid from aicrm_smartemail where smartemailid='".$crmid."' limit 1";
                $data7 = $generate->process($sql7,"all");

                $table="tbt_email_log_smartemailid_".$data7[0]['smartemailid'];
                $sql8="select from_module as Module,from_id as CRMID ,to_name as Name ,to_email as Email ,domain_name as Domain ,DATE_FORMAT( date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date  from ".$table ." where 1 AND status =2";
                $data8=$generate->process($sql8,"all");
                $kk = count($data8);


                $sql="select a.email
from
(
    select 
    b.email as email
    from aicrm_smartemail_leadsrel a
    left join aicrm_leaddetails  b on a.leadid=b.leadid
    left join aicrm_leadscf c on b.leadid=c.leadid
    left join aicrm_crmentity d on d.crmid=b.leadid
    where 1
    and d.deleted=0
   and a.smartemailid='".$crmid."'
    union all
    select 
    email1  as email
    from aicrm_smartemail_accountsrel a
    left join aicrm_account  b on a.accountid=b.accountid
    left join aicrm_accountscf c on b.accountid=c.accountid
    left join aicrm_crmentity d on d.crmid=b.accountid
    where 1
    and d.deleted=0
    and a.smartemailid='".$crmid."'
    union all
    select 
    b.email as email
    from aicrm_smartemail_opportunityrel a
    left join aicrm_opportunity  b on a.opportunityid=b.opportunityid
    left join aicrm_opportunitycf c on b.opportunityid=c.opportunityid
    left join aicrm_crmentity d on d.crmid=b.opportunityid
    where 1
    and d.deleted=0
    and a.smartemailid='".$crmid."'
    union all
    select 
    b.email as email
    from aicrm_smartemail_contactsrel a
    left join aicrm_contactdetails b on a.contactid=b.contactid
    left join aicrm_contactscf c on b.contactid=c.contactid
    left join aicrm_crmentity d on d.crmid=b.contactid
    where 1
    and d.deleted=0
    and a.smartemailid='".$crmid."'  
) as a";  //จำนวนเมล์ที่ส่งออกทั้งหมด
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
                   $sql="select a.email
from
(
    select 
    b.email as email
    from aicrm_smartemail_leadsrel a
    left join aicrm_leaddetails  b on a.leadid=b.leadid
    left join aicrm_leadscf c on b.leadid=c.leadid
    left join aicrm_crmentity d on d.crmid=b.leadid
    where 1
    and d.deleted=0
    and b.email<>''
    and emailstatus in('','Active')
   and b.email LIKE '%@%'
   and a.smartemailid='".$crmid."'
    group by b.email
    union
    select 
    email1  as email
    from aicrm_smartemail_accountsrel a
    left join aicrm_account  b on a.accountid=b.accountid
    left join aicrm_accountscf c on b.accountid=c.accountid
    left join aicrm_crmentity d on d.crmid=b.accountid
    where 1
    and d.deleted=0
    and email1<>''
    and emailstatus in('','Active')
    and email1 LIKE '%@%'
    and a.smartemailid='".$crmid."'
    group by email1
    union
    select 
    b.email as email
    from aicrm_smartemail_opportunityrel a
    left join aicrm_opportunity  b on a.opportunityid=b.opportunityid
    left join aicrm_opportunitycf c on b.opportunityid=c.opportunityid
    left join aicrm_crmentity d on d.crmid=b.opportunityid
    where 1
    and d.deleted=0
    and b.email<>''
    and emailstatus='Active'
    and b.email LIKE '%@%'
    and a.smartemailid='".$crmid."'
    group by b.email 
    union
    select 
    b.email as email
    from aicrm_smartemail_contactsrel a
    left join aicrm_contactdetails b on a.contactid=b.contactid
    left join aicrm_contactscf c on b.contactid=c.contactid
    left join aicrm_crmentity d on d.crmid=b.contactid
    where 1
    and d.deleted=0
    and b.email<>''
    and emailstatus='Active'
    and b.email LIKE '%@%'
    and a.smartemailid='".$crmid."'
    group by b.email    
) as a 
group by email";
                $data_pass=$generate->process($sql,"all");
                $count_data_pass = count($data_pass);   //จำนวนเมล์ที่ส่งผ่านทั้งหมด



               
                ?>
                     <tr style="background-color:#e1effc">

                    <td  align="left" style="font-size:18px;">&nbsp;&nbsp;&nbsp;<strong>ผลสรุปการส่ง</strong></td>
                    <td>&nbsp;</td>
                    <td  align="right" style="font-size:14px;"><strong>จำนวน</strong>&nbsp;</td>
                </tr>



                               <tr style="border-bottom:1px dashed;border-color:#CCC;">
                                    <td width="85%"><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p1_001&file_name=จำนวนอีเมล์ทั้งหมด')"/>รายชื่อที่ส่ง EMS ทั้งหมด</a></td>
                                    <td  align="right" valign="middle"><div style="background-color:#00BFFF; width:15px; height:15px;"></div></td>
                                    <td style="font-size:20px" align="right"> <strong><?=number_format($count_data_all)?></strong></td>
                                </tr>

                                <tr style="border-bottom:1px dashed;border-color:#CCC;">
                                  <td><a href="#" onclick="JavaScript: void window.location.replace('mysql2xls.php?crmid=<?=$crmid?>&report_no=p3_333&file_name=จำนวนเมล์ที่ส่งผ่านทั้งหมด')"/>จำนวน EMS ที่ส่งผ่าน</a></td>
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

          </div>
      </div>
   </div>
</div>

</form>
</body>
</html>
