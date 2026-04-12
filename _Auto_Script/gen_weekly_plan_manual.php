<?
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
include("C:/AppServ/www/glapcrmuat/config.inc.php");
include_once("C:/AppServ/www/glapcrmuat/library/dbconfig.php");
include_once("C:/AppServ/www/glapcrmuat/library/myFunction.php");
include_once("C:/AppServ/www/glapcrmuat/library/myLibrary_mysqli.php");
include_once("C:/AppServ/www/glapcrmuat/phpmailer/class.phpmailer.php");
$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

function getStartAndEndDate($week, $year) {
  $dto = new DateTime();
  $dto->setISODate($year, $week);
  $ret['week_start'] = $dto->format('Y-m-d');
  $dto->modify('+6 days');
  $ret['week_end'] = $dto->format('Y-m-d');
  return $ret;
}

$week_no = '53';
$year_chk= date('Y', strtotime('+1 year'));

$week_array = getStartAndEndDate($week_no,$year_chk);

$sql = "SELECT * FROM aicrm_activity_tran_config_weekly_plan WHERE weekly_id='". $week_no."' AND weekly_year='". $year_chk."'";
$count_chk=$myLibrary_mysqli->select($sql);

if(count($count_chk) == 0){
	$sql = "insert into aicrm_activity_tran_config_weekly_plan
	(`weekly_id`, `weekly_no`, `weekly_start_date`, `weekly_end_date`, `weekly_year`, `addempcd`, `addpcnm`, `adddt`, `updempcd`, `updpcnm`, `upddt`)
	values
	(
	'".$week_no."','".$week_no."','".$week_array["week_start"]."','".$week_array["week_end"]."','".$year_chk."','1','localhost',now(),'1','localhost',now()
	);";
	$myLibrary_mysqli->Query($sql);
}

$sql="select * from aicrm_users where deleted=0";
$data_user=$myLibrary_mysqli->select($sql);
//echo "<pre>"; print_r($data_user); echo "</pre>"; exit;
//chk transection Weekly Report
$sql="SELECT * FROM aicrm_activity_tran_config_weekly_plan WHERE deleted=0 AND weekly_id='".$week_no."' AND weekly_year='".$year_chk."'";
$data_chk=$myLibrary_mysqli->select($sql);
//echo count($data_chk);
if(count($data_chk) > 0){
	$week_array = getStartAndEndDate($week_no,$year_chk);
	//print_r( $week_array); exit;
	for($k=0;$k<count($data_user);$k++){
		//gen weekly plan by user
		if($data_user[$k]["plan_type"]=="Weekly"){
			$sql="select * from aicrm_activity_tran_weekly_plan where deleted=0 and weekly_year='".$year_chk."' and weekly_user_id='".$data_user[$k]["id"]."' and weekly_id='".$week_no."'";
			//echo $sql ; exit;
			$data_chk_data=$myLibrary_mysqli->select($sql);
			if(count($data_chk_data)>0){
				
			}else{
				$sql="insert into aicrm_activity_tran_weekly_plan
				(`weekly_id`, `weekly_no`, `weekly_start_date`, `weekly_end_date`, `weekly_year`, `weekly_user_id`, `addempcd`, `addpcnm`, `adddt`, `updempcd`, `updpcnm`, `upddt`)values(
				'".$week_no."','".$week_no."','".$week_array["week_start"]."','".$week_array["week_end"]."','".$year_chk."','".$data_user[$k]["id"]."','1','localhost',now(),'1','localhost',now()
				);";
				$myLibrary_mysqli->Query($sql);
			}
		}else{
			$sql="update aicrm_activity_tran_weekly_plan set  deleted=1 where deleted=0 and weekly_year='".$year_chk."' and weekly_user_id='".$data_user[$k]["id"]."' and weekly_id='".$week_no."' ";
			$myLibrary_mysqli->Query($sql);
		}
		//gen daily report by user
		if($data_user[$k]["report_type"]=="Daily"){
			$sql="select * from aicrm_activity_tran_daily_report where deleted=0 and daily_year='".$year_chk."' and daily_user_id='".$data_user[$k]["id"]."' and daily_id='".$week_no."'";
			$data_chk_data=$myLibrary_mysqli->select($sql);
			if(count($data_chk_data)>0){
				
			}else{
				$sql="insert into aicrm_activity_tran_daily_report
				(`daily_id`, `daily_no`, `daily_start_date`, `daily_end_date`, `daily_year`, `daily_user_id`, `addempcd`, `addpcnm`, `adddt`, `updempcd`, `updpcnm`, `upddt`)values(
				'".$week_no."','".$week_no."','".$week_array["week_start"]."','".$week_array["week_end"]."','".$year_chk."','".$data_user[$k]["id"]."','1','localhost',now(),'1','localhost',now()
				);";
				$myLibrary_mysqli->Query($sql);
			}
		}else{
			$sql="update aicrm_activity_tran_daily_report set  deleted=1 where deleted=0 and daily_year='".$year_chk."' and daily_user_id='".$data_user[$k]["id"]."' and daily_id='".$week_no."' ";
			$myLibrary_mysqli->Query($sql);
		}
	}
}//chk if
