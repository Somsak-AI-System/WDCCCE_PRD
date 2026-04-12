<?php
//error_reporting(E_ALL  & ~E_NOTICE  & ~E_WARNING );
//ini_set('display_errors', 1);
include_once("library/xml.php");
include_once("./library/log.php");
class myLibrary{
	public  $_dbconfig;
	public function __construct(){

		$this->_logfile = "./logs/myFunction";
		$this->log = new log();
		$this->log->_logname = $this->_logfile;
	}

	public function set_image($param=array())
	{
		$this->log->write_log("set image =>begin ");
		$this->log->write_log("param  => ".json_encode($param));
		$this->generate = new generate($this->_dbconfig  ,"DB");
		if(empty($param) ) return null;
		try {
			$sql = " insert into aicrm_job_img (jobid,img_type,img_folder,img,import_date,status,position) ";
			$sql .= " values ('".$param["jobid"]."' ";
			$sql .= " ,'" .( ( isset($param["img_type"]) && $param["img_type"]!="" ) ?$param["img_type"]:'01' )."' ";
			$sql .= " ,'" .( ( isset($param["img_folder"]) && $param["img_folder"]!="" ) ?$param["img_folder"]:'sena_pic/job_pic/'.$param["jobid"])."' ";
			$sql .= " ,'" .( ( isset($param["img"]) && $param["img"]!="" ) ?$param["img"]:'')."' ";
			$sql .= " ,'" .( ( isset($param["import_date"]) && $param["import_date"]!="" ) ?$param["import_date"]: date('Y-m-d H:i:s'))."' ";
			$sql .= " ,'" .( ( isset($param["status"]) && $param["status"]!="" ) ?$param["status"]: "Active")."' ";
			$sql .= " ,'" .( ( isset($param["position"]) && $param["position"]!="" ) ?$param["position"]: "0")."') ";
			$this->log->write_log("sql => ".$sql);

			$this->generate->query($sql);

			$a_return["status"] = true;
			$a_return["error"] = "" ;
			$a_return["result"] = "";
		} catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		}
		$this->log->write_log("return  => ".json_encode($a_return));
		return $a_return;
	}

	public function get_block($tabid=null)
	{

		$this->generate = new generate($this->_dbconfig  ,"DB");
		if($tabid =="") return null;
		$sql = "select  blockid,blocklabel,show_title
						 from  aicrm_blocks
						 where  1
						 AND display_status = '1'
						 and tabid ='". $tabid ."' ";
		$sql .= " order by sequence  ";
		$data = $this->generate->process($sql,"all");
		if(!empty($data)){
			return $data;
		}else{
			return null;
		}
	}
	public function get_field($tabid = null,$blockid=null)
	{

		$this->generate = new generate($this->_dbconfig  ,"DB");
		if($tabid =="") return null;
		$sql = "select  columnname,fieldlabel,tablename,fieldid,uitype,typeofdata,block,sequence,quickcreate,displaytype
						 from  aicrm_field
						 where  1
						 AND presence <> '1'
						 and uitype not in(70)
						 and ( quickcreate='2'  /*or (typeofdata='V~O' and presence<>'2' and tabid ='13')*/ )
						 and tabid ='". $tabid ."' ";
		if($blockid!=""){
			$sql .= "  and block='". $blockid ."' ";
		}
		$sql .= " order by block,sequence  ";
		//echo $sql;
		$data = $this->generate->process($sql,"all");
		if(!empty($data)){
			$a_return = array();
			foreach ($data as $k => $v){
				$a_data = array();
				$blockid = $v["block"];
				$a_data = $v;
				$a_return[$blockid][] = $a_data;
			}
			return $a_return;
		}else{
			return null;
		}
	}

	public function get_stored($storedname = null,$a_param=array())
	{

		if($storedname=="" || empty($a_param) ) return null;
		$this->generate = new generate($this->_dbconfig,"DB");
		$param = implode("','",$a_param);
		if($storedname == "questionnaire_pivot"){
			//$sql=" call ".$storedname." ('".$param."');";
			$sql = "select q.accountid,acc.accountname
						,qcf.cf_1375 'คำนำหน้า'
						,qcf.cf_1376 'ชื่อจริง'
						,qcf.cf_1377 'นามสกุล'
						,qcf.cf_1492 'โทรศัพท์'
						,qcf.cf_1790 'ว/ด/ป เกิด'
						,qcf.cf_1791 'อายุ'
						,qcf.cf_1792 'เพศ'
						,qcf.cf_1495 'E-Mail'
						,qcf.branchid 'โครงการที่เยี่ยมชม'
						,qcf.cf_1837 'Questionnaire Date'
						,qcf.cf_1793 'เลขที่'
						,qcf.cf_1794 'หมู่ที่'
						,qcf.cf_2410 'หมู่บ้าน/อาคาร'
						,qcf.cf_2411 'ห้อง'
						,qcf.cf_1795 'ถนน'
						,qcf.cf_1796 'ซอย'
						,qcf.cf_1797 'ภาค'
						,qcf.cf_1798 'จังหวัด'
						,qcf.cf_1799 'อำเภอ / เขต'
						,qcf.cf_1800 'ตำบล / แขวง'
						,qcf.cf_1801 'รหัสไปรษณีย์'
						,qcf.cf_1802 'รายได้ต่อเดือน'
						,qcf.cf_2400 'เลขที่(ที่ทำงาน)'
						,qcf.cf_2401 'หมู่ที่(ที่ทำงาน)'
						,qcf.cf_2412 'หมู่บ้าน/อาคาร(ที่ทำงาน)'
						,qcf.cf_2413 'ห้อง(ที่ทำงาน)'
						,qcf.cf_2402 'ถนน(ที่ทำงาน)'
						,qcf.cf_2403 'ซอย(ที่ทำงาน)'
						,qcf.cf_2404 'ภาค(ที่ทำงาน)'
						,qcf.cf_2405 'จังหวัด(ที่ทำงาน)'
						,qcf.cf_2406 'อำเภอ / เขต(ที่ทำงาน)'
						,qcf.cf_2407 'ตำบล / แขวง(ที่ทำงาน)'
						,qcf.cf_2408 'รหัสไปรษณีย์(ที่ทำงาน)'

						,sum(if(qc.choice_name='ป้ายแยกแฮปปี้แลนด์' ,1,0)) as 'สื่อป้ายโฆษณา-ป้ายแยกแฮปปี้แลนด์' ,sum(if(qc.choice_name='ป้ายถนนรามอินทรา กม.9' ,1,0)) as 'สื่อป้ายโฆษณา-ป้ายถนนรามอินทรา กม.9' ,sum(if(qc.choice_name='ป้ายแยกหทัยราษฎร์' ,1,0)) as 'สื่อป้ายโฆษณา-ป้ายแยกหทัยราษฎร์' ,sum(if(qc.choice_name='ป้ายถนนเลียบคลอง 2' ,1,0)) as 'สื่อป้ายโฆษณา-ป้ายถนนเลียบคลอง 2' ,sum(if(qc.choice_name='ป้ายถนนกาญจนาภิเษก' ,1,0)) as 'สื่อป้ายโฆษณา-ป้ายถนนกาญจนาภิเษก' ,sum(if(qc.choice_name='ป้ายบอกทางบริเวณอื่น' ,1,0)) as 'สื่อป้ายโฆษณา-ป้ายบอกทางบริเวณอื่น' ,sum(if(qc.choice_name='ผ่านหน้าโครงการ' ,1,0)) as 'สื่อป้ายโฆษณา-ผ่านหน้าโครงการ' ,sum(if(qc.choice_name='ป้ายรถแห่' ,1,0)) as 'สื่อป้ายโฆษณา-ป้ายรถแห่' ,sum(if(qc.choice_name='โสด' ,1,0)) as 'สถานภาพ-โสด' ,sum(if(qc.choice_name='สมรส มีบุตร  ' ,1,0)) as 'สถานภาพ-สมรส มีบุตร  ' ,sum(if(qc.choice_name='เจ้าของกิจการประเภท' ,1,0)) as 'อาชีพ-เจ้าของกิจการประเภท' ,sum(if(qc.choice_name='รับจ้างเอกชน' ,1,0)) as 'อาชีพ-รับจ้างเอกชน' ,sum(if(qc.choice_name='อาชีพอิสระ' ,1,0)) as 'อาชีพ-อาชีพอิสระ' ,sum(if(qc.choice_name='รับราชการ/รัฐวิสาหกิจ' ,1,0)) as 'อาชีพ-รับราชการ/รัฐวิสาหกิจ' ,sum(if(qc.choice_name='แพทย์' ,1,0)) as 'อาชีพ-แพทย์' ,sum(if(qc.choice_name='พ่อบ้าน/แม่บ้าน' ,1,0)) as 'อาชีพ-พ่อบ้าน/แม่บ้าน' ,sum(if(qc.choice_name='เกษียณอายุ' ,1,0)) as 'อาชีพ-เกษียณอายุ' ,sum(if(qc.choice_name='อื่นๆ' ,1,0)) as 'อาชีพ-อื่นๆ' ,sum(if(qc.choice_name='120,001 บาท  - 140,000 บาท' ,1,0)) as 'รายได้ครอบครัว/เดือน ก่อนหักค่าใช้จ่ายและภาษี-120,001 บาท  - 140,000 บาท' ,sum(if(qc.choice_name='140,001 บาท - 160,000 บาท' ,1,0)) as 'รายได้ครอบครัว/เดือน ก่อนหักค่าใช้จ่ายและภาษี-140,001 บาท - 160,000 บาท' ,sum(if(qc.choice_name='200,001 บาทขึ้นไป' ,1,0)) as 'รายได้ครอบครัว/เดือน ก่อนหักค่าใช้จ่ายและภาษี-200,001 บาทขึ้นไป' ,sum(if(qc.choice_name='50,001 บาท - 85,000 บาท' ,1,0)) as 'รายได้ครอบครัว/เดือน ก่อนหักค่าใช้จ่ายและภาษี-50,001 บาท - 85,000 บาท' ,sum(if(qc.choice_name='85,001 บาท - 120,000 บาท' ,1,0)) as 'รายได้ครอบครัว/เดือน ก่อนหักค่าใช้จ่ายและภาษี-85,001 บาท - 120,000 บาท' ,sum(if(qc.choice_name='ไม่เกิน 50,000 บาท' ,1,0)) as 'รายได้ครอบครัว/เดือน ก่อนหักค่าใช้จ่ายและภาษี-ไม่เกิน 50,000 บาท' ,sum(if(qc.choice_name='160,001 บาท - 200,000 บาท' ,1,0)) as 'รายได้ครอบครัว/เดือน ก่อนหักค่าใช้จ่ายและภาษี-160,001 บาท - 200,000 บาท' ,sum(if(qc.choice_name='ระบุ' ,1,0)) as 'จำนวนสมาชิกที่อยู่อาศัยด้วยกัน-ระบุ' ,sum(if(qc.choice_name='1 - 3 เดือน' ,1,0)) as 'ระยะเวลาในการตัดสินใจซื้อ-1 - 3 เดือน' ,sum(if(qc.choice_name='4 - 6 เดือน' ,1,0)) as 'ระยะเวลาในการตัดสินใจซื้อ-4 - 6 เดือน' ,sum(if(qc.choice_name='7 - 12 เดือน' ,1,0)) as 'ระยะเวลาในการตัดสินใจซื้อ-7 - 12 เดือน' ,sum(if(qc.choice_name='1 ปี ขึ้นไป' ,1,0)) as 'ระยะเวลาในการตัดสินใจซื้อ-1 ปี ขึ้นไป' ,sum(if(qc.choice_name='5.10 - 6.0 ล้านบาท' ,1,0)) as 'ระดับราคาที่ตั้งงบประมาณไว้ (ล้านบาท)-5.10 - 6.0 ล้านบาท' ,sum(if(qc.choice_name='6.1 - 7.0  ล้านบาท' ,1,0)) as 'ระดับราคาที่ตั้งงบประมาณไว้ (ล้านบาท)-6.1 - 7.0  ล้านบาท' ,sum(if(qc.choice_name='7.10 - 8.0 ล้านบาท' ,1,0)) as 'ระดับราคาที่ตั้งงบประมาณไว้ (ล้านบาท)-7.10 - 8.0 ล้านบาท' ,sum(if(qc.choice_name='8.10 - 9.0  ล้านบาท' ,1,0)) as 'ระดับราคาที่ตั้งงบประมาณไว้ (ล้านบาท)-8.10 - 9.0  ล้านบาท' ,sum(if(qc.choice_name='ต่ำกว่า 5 ล้านบาท' ,1,0)) as 'ระดับราคาที่ตั้งงบประมาณไว้ (ล้านบาท)-ต่ำกว่า 5 ล้านบาท' ,sum(if(qc.choice_name='9.1 - 10 ล้านบาท' ,1,0)) as 'ระดับราคาที่ตั้งงบประมาณไว้ (ล้านบาท)-9.1 - 10 ล้านบาท' ,sum(if(qc.choice_name='10 ล้านบาทขึ้นไป' ,1,0)) as 'ระดับราคาที่ตั้งงบประมาณไว้ (ล้านบาท)-10 ล้านบาทขึ้นไป' ,sum(if(qc.choice_name='บ้านเดี่ยว' ,1,0)) as 'ประเภทที่อยู่อาศัยในปัจจุบัน-บ้านเดี่ยว' ,sum(if(qc.choice_name='ทาวน์เฮ้าส์' ,1,0)) as 'ประเภทที่อยู่อาศัยในปัจจุบัน-ทาวน์เฮ้าส์' ,sum(if(qc.choice_name='อาคารพาณิชย์' ,1,0)) as 'ประเภทที่อยู่อาศัยในปัจจุบัน-อาคารพาณิชย์' ,sum(if(qc.choice_name='คอนโดมิเนียม' ,1,0)) as 'ประเภทที่อยู่อาศัยในปัจจุบัน-คอนโดมิเนียม' ,sum(if(qc.choice_name='อพาร์ทเมนท์ (เช่า)' ,1,0)) as 'ประเภทที่อยู่อาศัยในปัจจุบัน-อพาร์ทเมนท์ (เช่า)' ,sum(if(qc.choice_name='อื่นๆ' ,1,0)) as 'ประเภทที่อยู่อาศัยในปัจจุบัน-อื่นๆ' ,sum(if(qc.choice_name='เช่าอยู่' ,1,0)) as 'ลักษณะการถือครองที่อยู่อาศัยในปัจจุบัน-เช่าอยู่' ,sum(if(qc.choice_name='เป็นเจ้าของเอง/เจ้าของร่วม' ,1,0)) as 'ลักษณะการถือครองที่อยู่อาศัยในปัจจุบัน-เป็นเจ้าของเอง/เจ้าของร่วม' ,sum(if(qc.choice_name='เป็นของพ่อแม่' ,1,0)) as 'ลักษณะการถือครองที่อยู่อาศัยในปัจจุบัน-เป็นของพ่อแม่' ,sum(if(qc.choice_name='เป็นของญาติ พี่น้อง' ,1,0)) as 'ลักษณะการถือครองที่อยู่อาศัยในปัจจุบัน-เป็นของญาติ พี่น้อง' ,sum(if(qc.choice_name='น้อยกว่า 50' ,1,0)) as 'ขนาดที่ดินที่ต้องการ (ตารางวา)-น้อยกว่า 50' ,sum(if(qc.choice_name='51 - 60' ,1,0)) as 'ขนาดที่ดินที่ต้องการ (ตารางวา)-51 - 60' ,sum(if(qc.choice_name='61 - 70' ,1,0)) as 'ขนาดที่ดินที่ต้องการ (ตารางวา)-61 - 70' ,sum(if(qc.choice_name='71 - 80' ,1,0)) as 'ขนาดที่ดินที่ต้องการ (ตารางวา)-71 - 80' ,sum(if(qc.choice_name='81 - 90' ,1,0)) as 'ขนาดที่ดินที่ต้องการ (ตารางวา)-81 - 90' ,sum(if(qc.choice_name='91 - 100' ,1,0)) as 'ขนาดที่ดินที่ต้องการ (ตารางวา)-91 - 100' ,sum(if(qc.choice_name='มากกว่า 100' ,1,0)) as 'ขนาดที่ดินที่ต้องการ (ตารางวา)-มากกว่า 100' ,sum(if(qc.choice_name='ความสะดวกในการเดินทาง' ,1,0)) as 'สาเหตุที่ต้องการซื้อบ้าน / คอนโดมิเนียม-ความสะดวกในการเดินทาง' ,sum(if(qc.choice_name='เปลี่ยนที่อยู่อาศัยให้ใหญ่ขึ้น' ,1,0)) as 'สาเหตุที่ต้องการซื้อบ้าน / คอนโดมิเนียม-เปลี่ยนที่อยู่อาศัยให้ใหญ่ขึ้น' ,sum(if(qc.choice_name='แยกครอบครัว (เป็นส่วนตัว)' ,1,0)) as 'สาเหตุที่ต้องการซื้อบ้าน / คอนโดมิเนียม-แยกครอบครัว (เป็นส่วนตัว)' ,sum(if(qc.choice_name='แต่งงาน' ,1,0)) as 'สาเหตุที่ต้องการซื้อบ้าน / คอนโดมิเนียม-แต่งงาน' ,sum(if(qc.choice_name='ลงทุน/ให้เช่า' ,1,0)) as 'สาเหตุที่ต้องการซื้อบ้าน / คอนโดมิเนียม-ลงทุน/ให้เช่า' ,sum(if(qc.choice_name='เป็นทรัพย์สินเพิ่มเติม' ,1,0)) as 'สาเหตุที่ต้องการซื้อบ้าน / คอนโดมิเนียม-เป็นทรัพย์สินเพิ่มเติม' ,sum(if(qc.choice_name='ซื้อให้บุตรหลาน' ,1,0)) as 'สาเหตุที่ต้องการซื้อบ้าน / คอนโดมิเนียม-ซื้อให้บุตรหลาน' ,sum(if(qc.choice_name='อื่นๆ' ,1,0)) as 'สาเหตุที่ต้องการซื้อบ้าน / คอนโดมิเนียม-อื่นๆ' ,sum(if(qc.choice_name='ใกล้ที่ทำงาน' ,1,0)) as 'เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ -ใกล้ที่ทำงาน' ,sum(if(qc.choice_name='ใกล้บ้านเดิม' ,1,0)) as 'เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ -ใกล้บ้านเดิม' ,sum(if(qc.choice_name='ใกล้โรงเรียนลูก' ,1,0)) as 'เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ -ใกล้โรงเรียนลูก' ,sum(if(qc.choice_name='ทำเลใกล้รถไฟฟ้า' ,1,0)) as 'เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ -ทำเลใกล้รถไฟฟ้า' ,sum(if(qc.choice_name='ราคา' ,1,0)) as 'เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ -ราคา' ,sum(if(qc.choice_name='รูปแบบห้อง' ,1,0)) as 'เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ -รูปแบบห้อง' ,sum(if(qc.choice_name='Facility' ,1,0)) as 'เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ -Facility' ,sum(if(qc.choice_name='ที่่จอดรถ' ,1,0)) as 'เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ -ที่่จอดรถ' ,sum(if(qc.choice_name='โปรโมชั่น' ,1,0)) as 'เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ -โปรโมชั่น' ,sum(if(qc.choice_name='สภาพแวดล้อมในโครงการ' ,1,0)) as 'เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ -สภาพแวดล้อมในโครงการ' ,sum(if(qc.choice_name='ชื่อเสียงผู้ประกอบการ' ,1,0)) as 'เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ -ชื่อเสียงผู้ประกอบการ' ,sum(if(qc.choice_name='อื่นๆ' ,1,0)) as 'เหตุผลที่สนใจเข้าเยี่ยมชมโครงการ -อื่นๆ' ,sum(if(qc.choice_name='ความคุ้มค่าของราคา' ,1,0)) as 'เหตุผลที่ยังไม่ตัดสินใจซื้อ -ความคุ้มค่าของราคา' ,sum(if(qc.choice_name='สิ่งอำนวยความสะดวก' ,1,0)) as 'เหตุผลที่ยังไม่ตัดสินใจซื้อ -สิ่งอำนวยความสะดวก' ,sum(if(qc.choice_name='การเดินทางเข้าถึงโครงการ' ,1,0)) as 'เหตุผลที่ยังไม่ตัดสินใจซื้อ -การเดินทางเข้าถึงโครงการ' ,sum(if(qc.choice_name='รูปแบบพื้นที่ใช้สอย' ,1,0)) as 'เหตุผลที่ยังไม่ตัดสินใจซื้อ -รูปแบบพื้นที่ใช้สอย' ,sum(if(qc.choice_name='สภาพชุมชนภายนอก' ,1,0)) as 'เหตุผลที่ยังไม่ตัดสินใจซื้อ -สภาพชุมชนภายนอก' ,sum(if(qc.choice_name='บรรยากาศในโครงการ' ,1,0)) as 'เหตุผลที่ยังไม่ตัดสินใจซื้อ -บรรยากาศในโครงการ' ,sum(if(qc.choice_name='รอปรึกษากับผู้ร่วมตัดสินใจ' ,1,0)) as 'เหตุผลที่ยังไม่ตัดสินใจซื้อ -รอปรึกษากับผู้ร่วมตัดสินใจ' ,sum(if(qc.choice_name='ร้านค้าในโครงการ' ,1,0)) as 'เหตุผลที่ยังไม่ตัดสินใจซื้อ -ร้านค้าในโครงการ' ,sum(if(qc.choice_name='รอเปรียบเทียบกับโครงการอื่น ระบุ' ,1,0)) as 'เหตุผลที่ยังไม่ตัดสินใจซื้อ -รอเปรียบเทียบกับโครงการอื่น ระบุ' ,sum(if(qc.choice_name='AS' ,1,0)) as 'แบบบ้านในโครงการ Sena Park Grand ที่ท่านสนใจ-AS' ,sum(if(qc.choice_name='OS' ,1,0)) as 'แบบบ้านในโครงการ Sena Park Grand ที่ท่านสนใจ-OS' ,sum(if(qc.choice_name='NS' ,1,0)) as 'แบบบ้านในโครงการ Sena Park Grand ที่ท่านสนใจ-NS' ,sum(if(qc.choice_name='ระบุ' ,1,0)) as 'พนักงานต้อนรับ-ระบุ' ,sum(if(qc.choice_name='ถนนรามอินทรา' ,1,0)) as 'เส้นทางหลักที่ท่านใช้เดินทางมาโครงการนี้-ถนนรามอินทรา' ,sum(if(qc.choice_name='ถนนกาญจนาภิเษก (วงแหวน-ถนนรามอินทรา)' ,1,0)) as 'เส้นทางหลักที่ท่านใช้เดินทางมาโครงการนี้-ถนนกาญจนาภิเษก (วงแหวน-ถนนรามอินทรา)' ,sum(if(qc.choice_name='ถนนนวมินทร์-รามอินทรา' ,1,0)) as 'เส้นทางหลักที่ท่านใช้เดินทางมาโครงการนี้-ถนนนวมินทร์-รามอินทรา' ,sum(if(qc.choice_name='ถนนลาดพร้าว-นวมินทร์-รามอินทรา' ,1,0)) as 'เส้นทางหลักที่ท่านใช้เดินทางมาโครงการนี้-ถนนลาดพร้าว-นวมินทร์-รามอินทรา' ,sum(if(qc.choice_name='ทางด่วนฉลองรัช-คู้บอน' ,1,0)) as 'เส้นทางหลักที่ท่านใช้เดินทางมาโครงการนี้-ทางด่วนฉลองรัช-คู้บอน' ,sum(if(qc.choice_name='ทางด่วนเอกมัยรามอินทรา - ถนนรามอินทรา - คู้บอน' ,1,0)) as 'เส้นทางหลักที่ท่านใช้เดินทางมาโครงการนี้-ทางด่วนเอกมัยรามอินทรา - ถนนรามอินทรา - คู้บอน' ,sum(if(qc.choice_name='ถนนประเสริฐมนูกิจ - ถนนนวมินทร์ - ถนนรามอินทรา' ,1,0)) as 'เส้นทางหลักที่ท่านใช้เดินทางมาโครงการนี้-ถนนประเสริฐมนูกิจ - ถนนนวมินทร์ - ถนนรามอินทรา' ,sum(if(qc.choice_name='ระบุคะแนน' ,1,0)) as 'กรุณาให้คะแนนความพึงพอใจโดยรวมของโครงการนี้-ระบุคะแนน' ,sum(if(qc.choice_name='ระบุยี่ห้อ' ,1,0)) as 'ปัจจุบันท่านใช้โทรศัพท์มือถือ / Smart Phone ยี่ห้อใด-ระบุยี่ห้อ' ,sum(if(qc.choice_name='อยู่คนเดียว' ,1,0)) as 'หากท่านตัดสินใจซื้อโครงการนี้ จะมีผู้ร่วมพักอาศัยกับท่านกี่คน-อยู่คนเดียว' ,sum(if(qc.choice_name='2 คน' ,1,0)) as 'หากท่านตัดสินใจซื้อโครงการนี้ จะมีผู้ร่วมพักอาศัยกับท่านกี่คน-2 คน' ,sum(if(qc.choice_name='3 คน' ,1,0)) as 'หากท่านตัดสินใจซื้อโครงการนี้ จะมีผู้ร่วมพักอาศัยกับท่านกี่คน-3 คน' ,sum(if(qc.choice_name='4 คน' ,1,0)) as 'หากท่านตัดสินใจซื้อโครงการนี้ จะมีผู้ร่วมพักอาศัยกับท่านกี่คน-4 คน' ,sum(if(qc.choice_name='5 คน ขึ้นไป' ,1,0)) as 'หากท่านตัดสินใจซื้อโครงการนี้ จะมีผู้ร่วมพักอาศัยกับท่านกี่คน-5 คน ขึ้นไป' ,sum(if(qc.choice_name='ระบุ' ,1,0)) as 'งานอดิเรก/กิจกรรม ที่ทำในเวลาว่าง-ระบุ' ,sum(if(qc.choice_name='30,001-40,000 บาท' ,1,0)) as 'รายได้ส่วนตัว/เดือน (บาท)-30,001-40,000 บาท' ,sum(if(qc.choice_name='40,001-50,000 บาท' ,1,0)) as 'รายได้ส่วนตัว/เดือน (บาท)-40,001-50,000 บาท' ,sum(if(qc.choice_name='50,001-60,000 บาท' ,1,0)) as 'รายได้ส่วนตัว/เดือน (บาท)-50,001-60,000 บาท' ,sum(if(qc.choice_name='60,001-70,000 บาท' ,1,0)) as 'รายได้ส่วนตัว/เดือน (บาท)-60,001-70,000 บาท' ,sum(if(qc.choice_name='70,001-80,000 บาท' ,1,0)) as 'รายได้ส่วนตัว/เดือน (บาท)-70,001-80,000 บาท' ,sum(if(qc.choice_name='80,000-90,000 บาท' ,1,0)) as 'รายได้ส่วนตัว/เดือน (บาท)-80,000-90,000 บาท' ,sum(if(qc.choice_name='90,001-100,000 บาท' ,1,0)) as 'รายได้ส่วนตัว/เดือน (บาท)-90,001-100,000 บาท' ,sum(if(qc.choice_name='100,001 บาท ขึ้นไป' ,1,0)) as 'รายได้ส่วนตัว/เดือน (บาท)-100,001 บาท ขึ้นไป' ,sum(if(qc.choice_name='ไม่เกิน 30,000 บาท' ,1,0)) as 'รายได้ส่วนตัว/เดือน (บาท)-ไม่เกิน 30,000 บาท' ,sum(if(qc.choice_name='3 ห้องนอน 2 ห้องน้ำ' ,1,0)) as 'จำนวนห้องนอนที่ต้องการ-3 ห้องนอน 2 ห้องน้ำ' ,sum(if(qc.choice_name='3 ห้องนอน 3 ห้องน้ำ' ,1,0)) as 'จำนวนห้องนอนที่ต้องการ-3 ห้องนอน 3 ห้องน้ำ' ,sum(if(qc.choice_name='4 ห้องนอน 3 ห้องน้ำ' ,1,0)) as 'จำนวนห้องนอนที่ต้องการ-4 ห้องนอน 3 ห้องน้ำ' ,sum(if(qc.choice_name='ระบุ' ,1,0)) as 'ห้างสรรพสินค้าที่ท่านไปใช้บริการเป็นประจำ-ระบุ' ,sum(if(qc.choice_name='จดหมาย/ใบปลิว' ,1,0)) as 'งานสื่อสิ่งพิมพ์-จดหมาย/ใบปลิว' ,sum(if(qc.choice_name='หนังสือพิมพ์ ระบุ' ,1,0)) as 'งานสื่อสิ่งพิมพ์-หนังสือพิมพ์ ระบุ' ,sum(if(qc.choice_name='SENA PRIDE MAGAZINE' ,1,0)) as 'งานสื่อสิ่งพิมพ์-SENA PRIDE MAGAZINE' ,sum(if(qc.choice_name='นิตสาร ระบุ' ,1,0)) as 'งานสื่อสิ่งพิมพ์-นิตสาร ระบุ' ,sum(if(qc.choice_name='ฺBooth (ระบุ)' ,1,0)) as 'งาน Event/Booth-ฺBooth (ระบุ)' ,sum(if(qc.choice_name='www.sena.co.th' ,1,0)) as 'Internet-www.sena.co.th' ,sum(if(qc.choice_name='Google' ,1,0)) as 'Internet-Google' ,sum(if(qc.choice_name='Facebook' ,1,0)) as 'Internet-Facebook' ,sum(if(qc.choice_name='www.thinkofliving.com' ,1,0)) as 'Internet-www.thinkofliving.com' ,sum(if(qc.choice_name='E-news' ,1,0)) as 'Internet-E-news' ,sum(if(qc.choice_name='SMS มือถือ' ,1,0)) as 'Internet-SMS มือถือ' ,sum(if(qc.choice_name='แบนเนอร์โฆษณา เว็ปไซต์' ,1,0)) as 'Internet-แบนเนอร์โฆษณา เว็ปไซต์' ,sum(if(qc.choice_name='เพื่อน/ญาติ' ,1,0)) as 'แนะนำ-เพื่อน/ญาติ' ,sum(if(qc.choice_name='ซื้อเพิ่ม (เดิมซื้อโครงการ)' ,1,0)) as 'แนะนำ-ซื้อเพิ่ม (เดิมซื้อโครงการ)'  from aicrm_questionnaires q
						inner join aicrm_questionnairescf qcf on q.questionnaireid = qcf.questionnaireid
						inner join aicrm_questionnairebranch qbr on q.questionnairebranchid = qbr.questionnairebranchid
						inner join aicrm_questionnairesdtl qdtl on q.questionnaireid = qdtl.questionnaireid
						inner join aicrm_account acc on q.accountid = acc.accountid
						right join aicrm_question qu on qdtl.questionid = qu.questionid
						right join aicrm_questionchoice qc1 on qu.questionid = qc1.questionid
						left join aicrm_questionchoice qc on qdtl.choiceid = qc.choiceid
						and qu.questionid = qc.questionid and qc.choiceid = qc1.choiceid
						where 1
						/*and q.questionnaireid = 1054406 */
						and qbr.branchid = 1112554
						group by q.accountid,acc.accountname		";
		}

		//echo $sql;
		$data = $this->generate->process($sql,"all");
		//$this->generate->free_result();
		//$this->generate->closeconnect();
		//$this->generate->next_result();
		if(!empty($data)){
			return $data;
		}else{
			return null;
		}
	}
	/*public function gen_excel($a_data=array(),$title=null)
	{
		//ob_start();
		//echo $title;
		$xls = new Excel($title);
		$xls->top();
		$xls->home();
		foreach ($a_data[0] as $key  => $value)
		{
			$xls->label(iconv('TIS-620','UTF-8',$key));
			$xls->right();
		};
		$xls->down();
		foreach (array_values($a_data) as $lineNumber => $row) {
			$xls->home();
			foreach (array_values($row) as $colNumber => $v) {
			//	if (is_numeric($v)) {
			//		$xls->number($v);
			//	} else {
				//$xls->label($v);
				$xls->label(mb_convert_encoding($v,"UTF-8","auto"));
				//$xls->label($v);
			//	}
				$xls->right();
			}
			$xls->down();
		}
		//$data = ob_get_clean();
		$data =   $xls->send();
		return $data;
	}*/

	public function gen_excel($a_data=array(),$title=null)
	{
		$file=$title.".xls";
		$data = "";
		if(empty($a_data)) return null;
		$data = "<table>";
		$data .= "<tr>";
		foreach ($a_data[0] as $key  => $value)
		{
			$data .= "<td>" . $key ."</td>";
		};
		$data .= "</tr>";
		foreach (array_values($a_data) as $lineNumber => $row) {
			$data .= "<tr>";
			foreach (array_values($row) as $colNumber => $v) {
				if(is_numeric($v)) {
					$data .= '<td style =" mso-number-format: \'\@"\'>' .$v .'</td>';
				}else{
					$data .= "<td>" . (string)$v ."</td>";
				}
			}
			$data .= "</tr>";
		}
		$data .= "</table>";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$file");
		echo $data;
		return $data;
	}

/*	public function  curl_soap($url=null,$a_param=array())
	{
		echo ss;
		if($url=="") return false;
		try {
			echo $url;
		//	$fields_string = json_encode($a_param);
		//	$json_string = $fields_string;
			$xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                              <soap:Body>
                                <GetItemPrice xmlns="http://connecting.website.com/WSDL_Service"> ';
				foreach ($a_param as $k => $v){
					$xml_post_string .= "<".$k.">".$v."</".$k.">";
				}
			  	$xml_post_string .= ' </GetItemPrice >
			                                </soap:Body>
			                                </soap:Envelope>';
			  //	echo $xml_post_string;exit();
			$ch = curl_init(  );
			$headers = array(
					'Content-Type: text/xml; charset="utf-8"',
					'Content-Length: '.strlen($xml_post_string),
					'Accept: text/xml',
					'Cache-Control: no-cache',
					'Pragma: no-cache',
					'SOAPAction: "customerSearch"'
			);

			$options = array(
					CURLOPT_POST => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_TIMEOUT=> 60,
					CURLOPT_HTTPHEADER =>$headers,
					CURLOPT_USERAGENT => $defined_vars['HTTP_USER_AGENT'],
					CURLOPT_POSTFIELDS => $xml_post_string,
					CURLOPT_HTTPAUTH  =>CURLAUTH_BASIC

			);
			curl_setopt_array( $ch, $options );
			$result =  curl_exec($ch); // Getting jSON result string
			$response1 = str_replace("<soap:Body>","",$result);
			$response2 = str_replace("</soap:Body>","",$result);

			// convertingc to XML
			$parser = simplexml_load_string($response2);
			echo $parser;

			if(curl_errno($ch))
			{
				$a_return["status"] = false;
				$a_return["error"] =   curl_error($ch);
				$a_return["result"] = "";
			}else{
				$return = json_decode($result,true);
				if($return==""){
					$a_return["status"] = false;
					$a_return["error"] = $result;
					$a_return["result"] ="";
				}else{
					if($return["Type"]=="E"){
						$a_return["status"] = false;
						$a_return["error"] = $return["Message"];
						$a_return["result"] =$return;
					}else{
						$a_return["status"] = true;
						$a_return["error"] = "";
						$a_return["result"] = $return;
					}
				}
				//echo "<pre>";print_r($result);echo "</pre>";


			}
			curl_close($ch);
		} catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		}

		return $a_return;
	}*/

	public function convert_reponse_to_array($s_data=null,$method=null)
	{
		if($s_data==""||$method=="") return false;
		$result = @XML_unserialize($s_data);
		$m_reponse = $method."Response";
		$a_return = array();
		//echo "<pre>";print_r( $result);echo "</pre>";
		if(empty($result["soap:Envelope"]["soap:Body"][$m_reponse])){
			$a_return["Type"] = "E";
			$a_return["Message"] = "No Data";
			$a_return["result"] = "";
		}else{
			$this->log->write_log("convert data => ".json_encode($result));
			$data = $result["soap:Envelope"]["soap:Body"][$m_reponse];
			$m_result = $method."Result";
			$a_data = json_decode($data[$m_result],true);
			//echo $a_data["Error"];
			if($a_data["Error"]==""){
				$a_return["Type"] = "S";
				$a_return["Message"] = "";
				$a_return["result"] = empty($a_data) ? $data[$m_result]:$a_data;
			}else{
				$a_return["Type"] = "E";
				$a_return["Message"] =$a_data["Error"];
				$a_return["result"] = "";
			}
		}
		//echo "<pre>";print_r($result["soap:Envelope"]["soap:Body"][$m_reponse][$m_result]);echo "</pre>";
		return $a_return;
	}
	public function  curl_soap($url=null,$a_param=array(),$method = "")
	{
		if($url=="" || $method=="") return false;
		try {
			$this->log->write_log("url => ".$url);
			$this->log->write_log("method =>".$method);
			$this->log->write_log("parameter =>".json_encode($a_param));
			$xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
 							<soapenv:Body>
                                <tem:'.$method.'> ';
			foreach ($a_param as $k => $v){
				$xml_post_string .= "<tem:".$k.">".$v."</tem:".$k.">";
			}
			$xml_post_string .= ' </tem:'.$method.' >
			                                </soapenv:Body>
			                                </soapenv:Envelope>';
			                             //   echo $xml_post_string;
			$ch = curl_init(  );
			$headers = array(
					'Content-Type: text/xml; charset="utf-8"',
					'Content-Length: '.strlen($xml_post_string),
					'Accept: text/xml',
					'Cache-Control: no-cache',
					'Pragma: no-cache',
					'SOAPAction: "http://tempuri.org/'.$method.'"'
			);

			$options = array(
					CURLOPT_POST => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_TIMEOUT=> 60,
					CURLOPT_HTTPHEADER =>$headers,
					CURLOPT_USERAGENT => $defined_vars['HTTP_USER_AGENT'],
					CURLOPT_POSTFIELDS => $xml_post_string,
					CURLOPT_HTTPAUTH  =>CURLAUTH_BASIC

			);
			curl_setopt_array( $ch, $options );
			$result =  curl_exec($ch); // Getting jSON result string

			if(curl_errno($ch))
			{
				$a_return["status"] = false;
				$a_return["error"] =   curl_error($ch);
				$a_return["result"] = "";
			}else{
				$return =$this->convert_reponse_to_array($result,$method);
				if(empty($return)){

					$a_return["status"] = false;
					$a_return["error"] = $result["Message"];
					$a_return["result"] ="";
				}else{
					if($return["Type"]=="E"){
						$a_return["status"] = false;
						$a_return["error"] = $return["Message"];
						$a_return["result"] ="";
					}else{
						$a_return["status"] = true;
						$a_return["error"] = "";
						$a_return["result"] = $return["result"];
					}
				}

			}
			curl_close($ch);
		} catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		}
		$this->log->write_log("reponse data => ".json_encode($a_return));

		return $a_return;
	}
}

function date_compare($datebegin,$dateend)
{
	$diff = abs(strtotime($dateend) - strtotime($datebegin));
	$a_data["years"] = floor($diff / (365*60*60*24));
	$a_data["months"] = floor(($diff - $a_data["years"] * 365*60*60*24) / (30*60*60*24));
	$a_data["days"] = floor(($diff - $a_data["years"] * 365*60*60*24 - $a_data["months"] *30*60*60*24)/ (60*60*24));
	$a_data["day"] = floor($diff /  (60*60*24));
	return $a_data;
}
function get_status_color($day)
{
	if($day >0 && $day <= 10 ){
		//blue;
		$a_return["icon"] = "asset/images/icons/bullet_blue.png";
		$a_return["title"] = "1-10 days";
		$a_return["class"] = "info";
	}else if($day >10 && $day <= 20){
		//green;
		$a_return["icon"] = "asset/images/icons/bullet_green.png";
		$a_return["title"] = "11-20 days";
		$a_return["class"] = "success";
	}else if($day >20 && $day <= 30){
		//yellow ;
		$a_return["icon"] = "asset/images/icons/bullet_orange.png";
		$a_return["title"] = "21-30 days";
		$a_return["class"] = "warning";
	}else if($day >30){
		//red ;
		$a_return["icon"] = "asset/images/icons/bullet_red.png";
		$a_return["title"] = ">31 days";
		$a_return["class"] = "danger";
	}else{
		$a_return["icon"] = "asset/images/icons/bullet_blue.png";
		$a_return["title"] = "1-10 days";
		$a_return["class"] = "info";
	}
	$a_return["day"] = $day;
	return $a_return;
}

function get_status_color_km($status)
{
	if($status=="Hot" ){
		//yellow ;
		$a_return["icon"] = "asset/images/icons/bullet_orange.png";
		$a_return["title"] = "Hot";
		$a_return["class"] = "warning";
	}else if($status=="New"){
		$a_return["icon"] = "asset/images/icons/bullet_blue.png";
		$a_return["title"] = "New";
		$a_return["class"] = "info";
	}else{
		$a_return["icon"] = "";
		$a_return["title"] = "";
		$a_return["class"] = "";
	}
	$a_return["day"] = $day;
	return $a_return;
}

function get_status_service_request($datebegin,$dateend){
	$a_return = date_compare($datebegin,$dateend);
	//echo $a_return["day"]." \r ";
	$a_data = get_status_color($a_return["day"]);
	return $a_data;
}


function get_status_service_request_pm( $date_request, $date_work , $closejob){

	$date_end = ($closejob==1)?$date_work:date("Y-m-d");
	$a_return = date_compare($date_request,$dateend);
	//echo $a_return["day"]." \r ";
	$a_data = get_status_color($a_return["day"]);
	return $a_data;
}
	function date_set($date=null ,$format="Y-m-d")
	{
		if ($date=="") return $date;
		$a_date = explode('-',$date);
		if(is_array($a_date) )
		{
			$yyyy_mm_dd = $a_date[2] . '-' . $a_date[1] . '-' . $a_date[0];
			return  date($format, strtotime($yyyy_mm_dd));
		}
		else
		{
			return date($format, strtotime($date));
		}

	}

	function alert()
	{
		$arg_list = func_get_args();
		foreach($arg_list as $k => $v)
		{
			print '<pre class="alert">';
			print_r($v);
			print '</pre>';
		}
	}
?>
