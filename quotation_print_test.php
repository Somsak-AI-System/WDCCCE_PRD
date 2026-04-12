<?php
session_start();
header('Content-Type: text/html; charset=tis-620');
include("config.inc.php");
include("library/dbconfig.php");
require_once("library/generate_MYSQL.php");
require_once("library/Quotation.php");
require_once("library/general.php");
include_once("library/log.php");
include_once("library/myLibrary.php");
global $generate,$current_user;

$General = new libGeneral();
$Log = new log();
$Log->_logname ="logs/quotation_print";

	$url = "http://192.168.0.1:8080";
	//$s_param =  implode("|:|",$a_param);
	$s_param = "เอสวิลล์ รังสิต-ลำลูกกา คลอง 4|:|80/194|:|ลิลลี่|:|38.4 ตารางวา|:|0.00|:|0.00|:|0.00|:|0.00|:|0.00|:|0.00|:|0|:|0.00|:|0.00|:|0.00|:|0.00|:|0.00|:|อัตราดอกเบี้ย 6.5 % 20 ปี ผ่อนธนาคารงวดละ|:|อัตราดอกเบี้ย 6.5 % 25 ปี ผ่อนธนาคารงวดละ|:|อัตราดอกเบี้ย 6.5 % 30 ปี ผ่อนธนาคารงวดละ|:|ทำสัญญาภายใน 7 วันหลังจากวันจอง
ขนาดทีดินเพิ่ม-ลด(บ้านคิดตารางวาละ 25,000 บาทโฮมออฟพิศคิดตารางวาละ28,000 บาท)
ค่าประกันและค่าใช้จ่ายในการขอมิเตอร์น้ำประปา 6 หุนไฟฟ้า 15 Am ผู้ซื้อเป็นผู้ชำระ
ค่าใช้จ่ายค่าธรรมเนียมการโอนกรรมสิทธิ์ ค่าอากร ผู้ซื้อและผู้ขายเป็นผู้ขำระเท่ากันทั้งสองฝ่าย
ค่าใช้จ่ายในการขอวงเงินกู้กับสถาบันการเงิน และค่าจดจำนอง1%ผู้จะซื้อเป็นผู้ชำระ
กรณีเปลี่ยนแปลงผู้ซื้อก่อนโอนกรรมสิทธิ์ มีค่าเปลี่ยนสัญญาครั้งละ 10,000 บาท
ชำระค่าดูแลสาธารณูปโภคตอนโอนกรรมสิทธิ์ล่วงหน้า3 ปี (บ้านในอัตรา 20 บาท ต่อตารางวาโฮมออฟพิศ 300 บาท ต่อเดือน)
ระยะเวลาและการผ่อนชำระอาจมีการเปลี่ยนแปลงขึ้นลงตามอัตรดอกเบี้ยของสถาบันการเงิน
บริษัทฯขอสงวนสทธิ์ในการเปลี่ยนแปลงราคาและเงือนไขโดยไม่ต้องแจ้งให้ทราบล่วงหน้า
(หมายเหตุ)กรณีบ้านสร้างเสร็จก่อนผ่อนดาวน์ครบลูกค้าต้องโอนกรรมสิทธิ์ทันที
|:||:||:|บริษัท กรุงเทพเคหะกรุ๊ป จำกัด|:|889 อาคารไทยซีซีทาวเวอร์ ชั้น 24 ห้อง242 ถ. สาทรใต้ ยานนาวา สาทร กรุงเทพมหานคร
โทร 02-5414642 แฟกซ์ |:|Eakkawatna P|:|J194|:|d:/print_q\prequotation_1069440_16490_20150728_142940.pdf";

		$a_curl = $General->curl($url,$s_param,"array");
		$Log->write_log("url =>".$url);
		$Log->write_log("parameter =>".json_encode($a_param));
		$Log->write_log("sparameter =>".$s_param);
		$Log->write_log("response =>".json_encode($a_curl));
		if($a_curl["status"]==true){
			$a_reponse["msg"] ="Print Complete";
			$a_reponse["error"] = "";
			$a_reponse["status"] = true;
			$a_reponse["result"] = "";


		}else{
			$a_reponse["msg"] ="Try agian";
			$a_reponse["error"] =$a_curl["error"] ;
			$a_reponse["status"] = false;
			$a_reponse["result"] = "";
		}
		$a_reponse["url"] = "index.php";
		//$Quotation->set_transaction_print($productid,$userid,$a_reponse["status"],$a_reponse["error"]);
		echo json_encode($a_reponse);


		//echo $param;exit;


?>