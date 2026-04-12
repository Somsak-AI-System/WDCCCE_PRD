<?

session_start();

require_once("../../config.inc.php");
require_once("../../library/dbconfig.php");
require_once("../../library/myFunction.php");
//require_once("../../library/generate.inc.php");
require_once("../../library/generate_MYSQL.php");
require_once("../../library/Library_excel.php");


$generate = new generate($dbconfig ,"DB"); 
 
function Datediff($datefrom,$dateto){
	$startDate = strtotime($datefrom);
	$lastDate = strtotime($dateto);
	$differnce = $startDate - $lastDate;
	$differnce = ($differnce / (60*60*24)); //กรณืที่ต้องการให้ return ค่าเป็นวันนะครับ
	return $differnce;
}	

$report_no=$_REQUEST["report_no"];
$crmid=$_REQUEST["crmid"];
//echo $_SESSION["SQLSTRING"] ;exit;
$param_header="Module,CRMID,Name,Email,Domain,Send-Date";
$sql="select smartemailid from aicrm_smartemail where smartemailid='".$crmid."' limit 1";	
$data1 = $generate->process($sql,"all");




if(count($data1)>0){

$sql="select * from tbt_email_log_smartemailid_".$crmid."";	
$data2 = $generate->process($sql,"all");

$cause = ",
	(CASE 
     WHEN ((DUPLICATE = 1 AND active = 0)) THEN 'emailซ้ำ'
		 WHEN (invalid_email = 1) THEN 'email ไม่ถูก format'
		 WHEN (unsubscribe = 1) THEN 'ไม่ต้องการรับข่าวสาร'
		 WHEN (status != 1) THEN 'ส่งไม่ผ่าน'
		 WHEN (status = 1) THEN 'ส่งผ่าน'
     ELSE '-' 
 END) as Cause";

	$table="tbt_email_log_smartemailid_".$data1[0]['smartemailid'];
	$sql="select from_module as Module,from_id as CRMID ,to_name as Name ,to_email as Email ,domain_name as Domain ,DATE_FORMAT( date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date ".$cause."   from ".$table ." where 1";

}
if($report_no=="p1_001"){  //จำนวนเมล์ที่ส่งออกทั้งหมด
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.="";
}else if($report_no=="p1_002"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.=" and invalid_email=1";
}else if($report_no=="p1_003"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.=" AND report =1 AND active =1";
}else if($report_no=="p1_004"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.=" and domain_name='hotmail' and report=1 and active=1";
}else if($report_no=="p1_005"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.=" and domain_name='yahoo' and report=1 and active=1";
}else if($report_no=="p1_006"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.=" and domain_name='gmail' and report=1 and active=1";
}else if($report_no=="p1_007"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.=" and domain_name='others' and report=1 and active=1";
}else if($report_no=="p1_008"){    //รายการที่มีปัญหา
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.=" AND (
                    (duplicate = 1 AND active = 0)
                    OR invalid_email = 1
                    OR unsubscribe = 1
                    OR to_email IS NULL
                )";
}else if($report_no=="p1_118"){ //รายการที่ทำการส่ง
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql="select from_module as Module,from_id as CRMID ,to_name as Name ,to_email as Email ,domain_name as Domain ,DATE_FORMAT( date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date ".$cause."
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

                  )";

}else if($report_no=="p2_001"){
	$param_header="Module,CRMID,Name,Email,Domain,Click_Date";
	$param_filesname = "Link_no_".($_REQUEST["file_name"]+1)."_".date('d_m_Y').".xls";
	$sql="
	select 
	".$table.".from_module as Module
	,".$table.".from_id  as CRMID
	,".$table.".to_name as Name
	,".$table.".to_email as Email
	,".$table.".domain_name as Domain
	,DATE_FORMAT( ".$table."_click".".dateclick,  '%d-%m-%Y %H:%i:%s' ) as Click_Date
	from ".$table."_click 
	left join ".$table." on ".$table.".id=".$table."_click.uniqueid
	where 1
	and active =1
	and report=1
	and ctnum!=0
	and ctnum='".($_REQUEST["file_name"]+1)."'
	";
	//$sql.=" AND  `duplicate` !=0 AND report =1 AND active =1 GROUP BY  `to_email`";
}else if($report_no=="p2_002"){
	$param_header="Module,CRMID,Name,Email,Domain,Click_Date";
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql="
	select 
	".$table.".from_module as Module
	,".$table.".from_id as CRMID
	,".$table.".to_name as Name
	,".$table.".to_email as Email
	,".$table.".domain_name as Domain 
	,DATE_FORMAT( ".$table."_click".".dateclick,  '%d-%m-%Y %H:%i:%s' ) as Click_Date
	from ".$table."_click 
	left join ".$table." on ".$table.".id=".$table."_click.uniqueid
	where 1
	and active =1
	and report=1
	and ctnum!=0
	";
	//$sql.=" AND  `duplicate` !=0 AND report =1 AND active =1 GROUP BY  `to_email`";
}else if($report_no=="p3_001"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.=" AND report =1 AND active =1";
}else if($report_no=="p3_002"){  //จำนวนเมล์ที่ส่งไม่ผ่านทั้งหมด
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	/*$sql="select from_module as Module,from_id as CRMID ,to_name as Name ,to_email as Email ,domain_name as Domain ,DATE_FORMAT( date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date ".$cause."
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
                  )";*/
     $sql="select from_module as Module,from_id as CRMID ,to_name as Name ,to_email as Email ,domain_name as Domain ,DATE_FORMAT( date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date ".$cause."
                FROM
                  ".$table." WHERE status = 2 and invalid_email = 0
                  ";
}else if($report_no=="p3_333"){   //จำนวนเมล์ส่งผ่านทั้งหมด

	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	/*$sql="select from_module as Module,from_id as CRMID ,to_name as Name ,to_email as Email ,domain_name as Domain ,DATE_FORMAT( date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date ".$cause."
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
                    OR status != 1
                  )";*/
      $sql="select from_module as Module,from_id as CRMID ,to_name as Name ,to_email as Email ,domain_name as Domain ,DATE_FORMAT( date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date ".$cause."
                FROM
                  ".$table." WHERE status = 1
                  ";
}else if($report_no=="p3_003"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.=" AND report =1 AND active =1 and status in(1)";
}else if($report_no=="p3_004"){
	$param_header="Module,CRMID,Name,Email,Domain,Click_Date";
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql="
	select 
	".$table.".from_module as  Module
	,".$table.".from_id as CRMID
	,".$table.".to_name as  Name
	,".$table.".to_email as Email
	,".$table.".domain_name as Domain
	,DATE_FORMAT( ".$table."_open".".dateopen,  '%d-%m-%Y %H:%i:%s' ) as Click_Date
	from ".$table."_open 
	left join ".$table." on ".$table.".id=".$table."_open.uniqueid
	where 1
	and active =1
	and report=1
	";
	//$sql.=" AND  `duplicate` !=0 AND report =1 AND active =1 GROUP BY  `to_email`";
}else if($report_no=="p3_005"){
	$param_header="Module,CRMID,Name,Email,Domain,Click_Date";
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql="
	select 
	".$table.".from_module as Module
	,".$table.".from_id as CRMID
	,".$table.".to_name as Name
	,".$table.".to_email as  Email
	,".$table.".domain_name as Domain
	,DATE_FORMAT( ".$table."_click".".dateclick,  '%d-%m-%Y %H:%i:%s' ) as Click_Date
	from ".$table."_click 
	left join ".$table." on ".$table.".id=".$table."_click.uniqueid
	where 1
	and active =1
	and report=1
	and ctnum!=0
	";
	//$sql.=" AND  `duplicate` !=0 AND report =1 AND active =1 GROUP BY  `to_email`";
}else if($report_no=="p3_006"){
	$param_filesname = $_REQUEST["file_name"]."_".date('d_m_Y').".xls";
	$sql.=" AND report =1 AND unsubscribe >0";
}

// echo $sql; exit;

$data = $generate->process($sql,"all");

	  $myLib = new myExcel();
		if(!empty($data)){
			$title = $param_filesname;
			$a_resonse = $myLib->gen_excel($data,$title);
		}else{
			echo "<script>alert('No Data');</script>";
			echo "<script>window.location.replace('".$site_URL."EDM/webmail/home-1.php?campaing=".$crmid."');</script>";
		}
		
?> 