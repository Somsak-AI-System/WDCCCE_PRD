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
//=================================================================================================================================
//create aicrm_activity_tran_config_monthly_plan
$sql="
	CREATE TABLE `aicrm_activity_tran_config_monthly_plan` (
	  `monthly_id` int(20) NOT NULL COMMENT 'rating_id',
	  `monthly_no` int(20) NOT NULL COMMENT '��͹ 1-12',
	  `monthly_start_date` date NOT NULL,
	  `monthly_end_date` date NOT NULL,
	  `monthly_year` int(20) NOT NULL,
	  `addempcd` varchar(20) default NULL COMMENT '���ʾ�ѡ�ҹ���ѹ�֡',
	  `addpcnm` varchar(50) default NULL COMMENT '��������ͧ���ѹ�֡',
	  `adddt` datetime default NULL COMMENT '�ѹ���ѹ�֡',
	  `updempcd` varchar(20) default NULL COMMENT '���ʾ�ѡ�ҹ����Ѻ��ا',
	  `updpcnm` varchar(50) default NULL COMMENT '��������ͧ����Ѻ��ا',
	  `upddt` datetime default NULL COMMENT '�ѹ����Ѻ��ا',
	  `deleted` varchar(1) NOT NULL default '0',
	  PRIMARY KEY  (`monthly_id`,`monthly_year`),
	  KEY `monthly_id` (`monthly_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='aicrm_activity_tran_config_monthly_plan';
";
if($myLibrary_mysqli->Query($sql)){};
//create aicrm_activity_tran_config_weekly_plan
$sql="
	CREATE TABLE `aicrm_activity_tran_config_weekly_plan` (
	  `weekly_id` int(20) NOT NULL COMMENT 'rating_id',
	  `weekly_no` int(20) NOT NULL COMMENT 'week 1-52',
	  `weekly_start_date` date NOT NULL,
	  `weekly_end_date` date NOT NULL,
	  `weekly_year` int(20) NOT NULL,
	  `addempcd` varchar(20) default NULL COMMENT '���ʾ�ѡ�ҹ���ѹ�֡',
	  `addpcnm` varchar(50) default NULL COMMENT '��������ͧ���ѹ�֡',
	  `adddt` datetime default NULL COMMENT '�ѹ���ѹ�֡',
	  `updempcd` varchar(20) default NULL COMMENT '���ʾ�ѡ�ҹ����Ѻ��ا',
	  `updpcnm` varchar(50) default NULL COMMENT '��������ͧ����Ѻ��ا',
	  `upddt` datetime default NULL COMMENT '�ѹ����Ѻ��ا',
	  `deleted` varchar(1) NOT NULL default '0',
	  PRIMARY KEY  (`weekly_id`,`weekly_year`),
	  KEY `weekly_id` (`weekly_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='aicrm_activity_config_weekly_plan';
";
if($myLibrary_mysqli->Query($sql)){};
//create aicrm_activity_tran_weekly_plan
$sql="
	CREATE TABLE `aicrm_activity_tran_weekly_plan` (
	  `weekly_id` int(20) NOT NULL COMMENT 'rating_id',
	  `weekly_no` int(20) NOT NULL COMMENT 'week 1-52',
	  `weekly_start_date` date NOT NULL,
	  `weekly_end_date` date NOT NULL,
	  `weekly_year` int(20) NOT NULL,
	  `weekly_user_id` int(20) NOT NULL,
	  `weekly_send_date` datetime NOT NULL,
	  `weekly_check_send` varchar(1) NOT NULL default '0' COMMENT '0=�����,1=��',
	  `addempcd` varchar(20) default NULL COMMENT '���ʾ�ѡ�ҹ���ѹ�֡',
	  `addpcnm` varchar(50) default NULL COMMENT '��������ͧ���ѹ�֡',
	  `adddt` datetime default NULL COMMENT '�ѹ���ѹ�֡',
	  `updempcd` varchar(20) default NULL COMMENT '���ʾ�ѡ�ҹ����Ѻ��ا',
	  `updpcnm` varchar(50) default NULL COMMENT '��������ͧ����Ѻ��ا',
	  `upddt` datetime default NULL COMMENT '�ѹ����Ѻ��ا',
	  `deleted` varchar(1) NOT NULL default '0',
	  PRIMARY KEY  (`weekly_id`,`weekly_year`,`weekly_user_id`),
	  KEY `weekly_id` (`weekly_id`),
	  KEY `weekly_user_id` (`weekly_user_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='aicrm_activity_weekly_plan';
";
if($myLibrary_mysqli->Query($sql)){};
//create aicrm_activity_tran_monthly_plan
$sql="
	CREATE TABLE `aicrm_activity_tran_monthly_plan` (
	  `monthly_id` int(20) NOT NULL COMMENT 'rating_id',
	  `monthly_no` int(20) NOT NULL COMMENT '��͹ 1-12',
	  `monthly_start_date` date NOT NULL,
	  `monthly_end_date` date NOT NULL,
	  `monthly_year` int(20) NOT NULL,
	  `monthly_user_id` int(20) NOT NULL,
	  `monthly_send_date` datetime NOT NULL,
	  `monthly_check_send` varchar(1) NOT NULL default '0' COMMENT '0=�����,1=��',
	  `addempcd` varchar(20) default NULL COMMENT '���ʾ�ѡ�ҹ���ѹ�֡',
	  `addpcnm` varchar(50) default NULL COMMENT '��������ͧ���ѹ�֡',
	  `adddt` datetime default NULL COMMENT '�ѹ���ѹ�֡',
	  `updempcd` varchar(20) default NULL COMMENT '���ʾ�ѡ�ҹ����Ѻ��ا',
	  `updpcnm` varchar(50) default NULL COMMENT '��������ͧ����Ѻ��ا',
	  `upddt` datetime default NULL COMMENT '�ѹ����Ѻ��ا',
	  `deleted` varchar(1) NOT NULL default '0',
	  PRIMARY KEY  (`monthly_id`,`monthly_year`,`monthly_user_id`),
	  KEY `monthly_id` (`monthly_id`),
	  KEY `monthly_user_id` (`monthly_user_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='aicrm_activity_monthly_plan';
";
if($myLibrary_mysqli->Query($sql)){};
//create aicrm_activity_tran_daily_report
$sql="
	CREATE TABLE `aicrm_activity_tran_daily_report` (
	  `daily_id` int(20) NOT NULL COMMENT 'rating_id',
	  `daily_no` int(20) NOT NULL,
	  `daily_start_date` date NOT NULL,
	  `daily_end_date` date NOT NULL,
	  `daily_year` int(20) NOT NULL,
	  `daily_user_id` int(20) NOT NULL,
	  `daily_send_date` datetime NOT NULL,
	  `daily_check_send` varchar(1) NOT NULL default '0' COMMENT '0=�����,1=��',
	  `addempcd` varchar(20) default NULL COMMENT '���ʾ�ѡ�ҹ���ѹ�֡',
	  `addpcnm` varchar(50) default NULL COMMENT '��������ͧ���ѹ�֡',
	  `adddt` datetime default NULL COMMENT '�ѹ���ѹ�֡',
	  `updempcd` varchar(20) default NULL COMMENT '���ʾ�ѡ�ҹ����Ѻ��ا',
	  `updpcnm` varchar(50) default NULL COMMENT '��������ͧ����Ѻ��ا',
	  `upddt` datetime default NULL COMMENT '�ѹ����Ѻ��ا',
	  `deleted` varchar(1) NOT NULL default '0',
	  PRIMARY KEY  (`daily_id`,`daily_year`,`daily_user_id`),
	  KEY `daily_id` (`daily_id`),
	  KEY `daily_user_id` (`daily_user_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='aicrm_activity_daily_report';
";
if($myLibrary_mysqli->Query($sql)){};
//create aicrm_activity_tran_monthly_report
$sql="
	CREATE TABLE `aicrm_activity_tran_monthly_report` (
	  `monthly_id` int(20) NOT NULL COMMENT 'rating_id',
	  `monthly_no` int(20) NOT NULL COMMENT '��͹ 1-12',
	  `monthly_start_date` date NOT NULL,
	  `monthly_end_date` date NOT NULL,
	  `monthly_year` int(20) NOT NULL,
	  `monthly_user_id` int(20) NOT NULL,
	  `monthly_send_date` datetime NOT NULL,
	  `monthly_check_send` varchar(1) NOT NULL default '0' COMMENT '0=�����,1=��',
	  `addempcd` varchar(20) default NULL COMMENT '���ʾ�ѡ�ҹ���ѹ�֡',
	  `addpcnm` varchar(50) default NULL COMMENT '��������ͧ���ѹ�֡',
	  `adddt` datetime default NULL COMMENT '�ѹ���ѹ�֡',
	  `updempcd` varchar(20) default NULL COMMENT '���ʾ�ѡ�ҹ����Ѻ��ا',
	  `updpcnm` varchar(50) default NULL COMMENT '��������ͧ����Ѻ��ا',
	  `upddt` datetime default NULL COMMENT '�ѹ����Ѻ��ا',
	  `deleted` varchar(1) NOT NULL default '0',
	  PRIMARY KEY  (`monthly_id`,`monthly_year`,`monthly_user_id`),
	  KEY `monthly_id` (`monthly_id`),
	  KEY `monthly_user_id` (`monthly_user_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='aicrm_activity_tran_monthly_report';
";
if($myLibrary_mysqli->Query($sql)){};
//=================================================================================================================================
$date_chk=date('d-m');
$year_chk=date('Y');
$date_chk="01-01";

$sql="select * from aicrm_users where deleted=0";
$data_user=$myLibrary_mysqli->select($sql);
//gen config weekly plan	
$sql="select * from aicrm_activity_tran_config_weekly_plan where deleted=0 and weekly_year='".$year_chk."'";
$data_chk=$myLibrary_mysqli->select($sql);
if(count($data_chk)>0){
}else{
	for($i=1;$i<=52;$i++){
		$week_array = getStartAndEndDate($i,$year_chk);
		$sql="insert into aicrm_activity_tran_config_weekly_plan
		(`weekly_id`, `weekly_no`, `weekly_start_date`, `weekly_end_date`, `weekly_year`, `addempcd`, `addpcnm`, `adddt`, `updempcd`, `updpcnm`, `upddt`)
		values
		(
		'".$i."','".$i."','".$week_array["week_start"]."','".$week_array["week_end"]."','".$year_chk."','1','localhost',now(),'1','localhost',now()
		);";	
		$myLibrary_mysqli->Query($sql);
	}
}//end gen config weekly plan	

//chk transection Weekly Report
$sql="select * from aicrm_activity_tran_config_weekly_plan where deleted=0 and weekly_year='".$year_chk."'";
$data_chk=$myLibrary_mysqli->select($sql);
if(count($data_chk)>0){
	for($i=1;$i<=count($data_chk);$i++){
		$week_array = getStartAndEndDate($i,$year_chk);
		for($k=0;$k<count($data_user);$k++){
			//gen weekly plan by user
			if($data_user[$k]["plan_type"]=="Weekly"){
				$sql="select * from aicrm_activity_tran_weekly_plan where deleted=0 and weekly_year='".$year_chk."' and weekly_user_id='".$data_user[$k]["id"]."' and weekly_id='".$i."'";
				$data_chk_data=$myLibrary_mysqli->select($sql);
				if(count($data_chk_data)>0){
					
				}else{
					$sql="insert into aicrm_activity_tran_weekly_plan
					(`weekly_id`, `weekly_no`, `weekly_start_date`, `weekly_end_date`, `weekly_year`, `weekly_user_id`, `addempcd`, `addpcnm`, `adddt`, `updempcd`, `updpcnm`, `upddt`)values(
					'".$i."','".$i."','".$week_array["week_start"]."','".$week_array["week_end"]."','".$year_chk."','".$data_user[$k]["id"]."','1','localhost',now(),'1','localhost',now()
					);";	
					$myLibrary_mysqli->Query($sql);
				}
			}else{
				$sql="update aicrm_activity_tran_weekly_plan set  deleted=1 where deleted=0 and weekly_year='".$year_chk."' and weekly_user_id='".$data_user[$k]["id"]."' and weekly_id='".$i."' ";
				$myLibrary_mysqli->Query($sql);
			}
			//gen daily report by user
			if($data_user[$k]["report_type"]=="Daily"){
				$sql="select * from aicrm_activity_tran_daily_report where deleted=0 and daily_year='".$year_chk."' and daily_user_id='".$data_user[$k]["id"]."' and daily_id='".$i."'";
				$data_chk_data=$myLibrary_mysqli->select($sql);
				if(count($data_chk_data)>0){
					
				}else{
					$sql="insert into aicrm_activity_tran_daily_report
					(`daily_id`, `daily_no`, `daily_start_date`, `daily_end_date`, `daily_year`, `daily_user_id`, `addempcd`, `addpcnm`, `adddt`, `updempcd`, `updpcnm`, `upddt`)values(
					'".$i."','".$i."','".$week_array["week_start"]."','".$week_array["week_end"]."','".$year_chk."','".$data_user[$k]["id"]."','1','localhost',now(),'1','localhost',now()
					);";	
					$myLibrary_mysqli->Query($sql);
				}
			}else{
				$sql="update aicrm_activity_tran_daily_report set  deleted=1 where deleted=0 and daily_year='".$year_chk."' and daily_user_id='".$data_user[$k]["id"]."' and daily_id='".$i."' ";
				$myLibrary_mysqli->Query($sql);
				//echo $sql."<br>";
			}
		}
	}
}//chk if

//gen config monthly plan	
$sql="select * from aicrm_activity_tran_config_monthly_plan where deleted=0 and monthly_year='".$year_chk."'";
$data_chk=$myLibrary_mysqli->select($sql);
if(count($data_chk)>0){
}else{
	for($i=1;$i<=12;$i++){
		if($i=="4" or $i=="6" or $i=="9" or $i=="11"){
			$week_start=$year_chk."-".$i."-01";
			$week_end=$year_chk."-".$i."-30";
		}else if($i=="1" or $i=="3" or $i=="5" or $i=="7" or $i=="8" or $i=="10" or $i=="12"){
			$week_start=$year_chk."-".$i."-01";
			$week_end=$year_chk."-".$i."-31";
		}else if($i=="2"){
			if($year_chk%4=="0"){
				$week_start=$year_chk."-".$i."-01";
				$week_end=$year_chk."-".$i."-29";
			}else{
				$week_start=$year_chk."-".$i."-01";
				$week_end=$year_chk."-".$i."-28";
			}
		}
		$sql="insert into aicrm_activity_tran_config_monthly_plan
		(`monthly_id`, `monthly_no`, `monthly_start_date`, `monthly_end_date`, `monthly_year`, `addempcd`, `addpcnm`, `adddt`, `updempcd`, `updpcnm`, `upddt`)values(
		'".$i."','".$i."','".$week_start."','".$week_end."','".$year_chk."','1','localhost',now(),'1','localhost',now()
		);";	
		$myLibrary_mysqli->Query($sql);
	}
}//end gen config monthly plan
//chk transection Monthly Report
$sql="select * from aicrm_activity_tran_config_monthly_plan where deleted=0 and monthly_year='".$year_chk."'";
$data_chk=$myLibrary_mysqli->select($sql);
//echo $sql;
if(count($data_chk)>0){
	for($i=1;$i<=count($data_chk);$i++){
		//echo $i."<br>";
		if($i=="4" or $i=="6" or $i=="9" or $i=="11"){
			$week_start=$year_chk."-".$i."-01";
			$week_end=$year_chk."-".$i."-30";
		}else if($i=="1" or $i=="3" or $i=="5" or $i=="7" or $i=="8" or $i=="10" or $i=="12"){
			$week_start=$year_chk."-".$i."-01";
			$week_end=$year_chk."-".$i."-31";
		}else if($i=="2"){
			if($year_chk%4=="0"){
				$week_start=$year_chk."-".$i."-01";
				$week_end=$year_chk."-".$i."-29";
			}else{
				$week_start=$year_chk."-".$i."-01";
				$week_end=$year_chk."-".$i."-28";
			}
		}
		for($k=0;$k<count($data_user);$k++){
			//gen monthly plan by user
			if($data_user[$k]["plan_type"]=="Monthly"){
				$sql="select * from aicrm_activity_tran_monthly_plan where deleted=0 and monthly_year='".$year_chk."' and monthly_user_id='".$data_user[$k]["id"]."' and monthly_id='".$i."' ";
				$data_chk_data=$myLibrary_mysqli->select($sql);
				if(count($data_chk_data)>0){
					
				}else{
					$sql="insert into aicrm_activity_tran_monthly_plan
					(`monthly_id`, `monthly_no`, `monthly_start_date`, `monthly_end_date`, `monthly_year`, `monthly_user_id`, `addempcd`, `addpcnm`, `adddt`, `updempcd`, `updpcnm`, `upddt`)values(
					'".$i."','".$i."','".$week_start."','".$week_end."','".$year_chk."','".$data_user[$k]["id"]."','1','localhost',now(),'1','localhost',now()
					);";
					$myLibrary_mysqli->Query($sql);
				}
			}else{//if
				$sql="update aicrm_activity_tran_monthly_plan set  deleted=1 where deleted=0 and monthly_year='".$year_chk."' and monthly_user_id='".$data_user[$k]["id"]."' and monthly_id='".$i."' ";
				$myLibrary_mysqli->Query($sql);
				//echo $sql."<br>";
			}
			//gen monthly report by user
			if($data_user[$k]["report_type"]=="Monthly"){
				$sql="select * from aicrm_activity_tran_monthly_report where deleted=0 and monthly_year='".$year_chk."' and monthly_user_id='".$data_user[$k]["id"]."' and monthly_id='".$i."' ";
				$data_chk_data=$myLibrary_mysqli->select($sql);
				if(count($data_chk_data)>0){
					
				}else{
					$sql="insert into aicrm_activity_tran_monthly_report
					(`monthly_id`, `monthly_no`, `monthly_start_date`, `monthly_end_date`, `monthly_year`, `monthly_user_id`, `addempcd`, `addpcnm`, `adddt`, `updempcd`, `updpcnm`, `upddt`)values(
					'".$i."','".$i."','".$week_start."','".$week_end."','".$year_chk."','".$data_user[$k]["id"]."','1','localhost',now(),'1','localhost',now()
					);";
					$myLibrary_mysqli->Query($sql);
				}
				//echo $sql."<br>";
			}else{
				$sql="update aicrm_activity_tran_monthly_report set  deleted=1 where deleted=0 and monthly_year='".$year_chk."' and monthly_user_id='".$data_user[$k]["id"]."' and monthly_id='".$i."' ";
				$myLibrary_mysqli->Query($sql);
			}
		}//for
	}
}
?>