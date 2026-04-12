<?php
class libQuotation{
	public  $_dbconfig;
	public $_page;

	public function __construct($dbconfig){
		global  $list_max_entries_per_page;
		$this->_dbconfig = $dbconfig;
		$this->generate = new generate($this->_dbconfig  ,"DB");
		$this->list_max_entries_per_page =$list_max_entries_per_page;

	}

	public function set_transaction_print($productid=null,$saleid=null,$status=true,$msg=null)
	{
	//	var_dump($status);
		if($productid=="" || $saleid=="")	return false;
		$status = ($status===true)?1:0;
		$query = "insert into aicrm_product_print (productid,saleid,status,msg)";
		$query .= " values ('".$productid."','".$saleid."',".$status.",'".str_replace("'","''",$msg)."') ";
		//echo $query;
		$this->generate->query($query);
	}
	public function get_salename($useid=null)
	{
		if($useid=="")	return false;
		$query = "select aicrm_users.first_name as first_name
					,aicrm_users.last_name as last_name
						from aicrm_users
						where 1 ";
		if($useid!=""){
			$query .= " and aicrm_users.id = '".$useid."' ";
		}
		$data = $this->generate->process($query,"1");

		if(!empty($data)){
			return $data;
		}else{
			return null;
		}
	}
	public function get_branch($branchid=null)
	{
		if($branchid=="")	return false;

		$query = "
					select branchs.branch_name as branch_name
						,branchs.ipaddress as ipaddress
						,branchs.printername as printername


					from aicrm_branchs branchs
					left join aicrm_branchscf branchscf
						on branchs.branchid =branchscf.branchid
					left join aicrm_crmentity crmbranch
						on crmbranch.crmid = branchs.branchid
					where 1 and crmbranch.deleted=0  ";
		if($branchid!=""){
			$query .= " and branchs.branchid = '".$branchid."' ";
		}
		//echo $query;

		$data = $this->generate->process($query,"1");

		if(!empty($data)){
			
			return $data;
		}else{
			return null;
		}
	}
	public function get_product($productid=null)
	{
		if($productid=="")	return false;

		$query = "
					select products.branchcode as projectcode
						,products.sbucode as sbucode
						,products.producttypecode as producttypecode
						,products.productcode as productcode
												
						,aicrm_building.buildingid as buildingid
						,aicrm_building.branchid


					from aicrm_products products
					left join aicrm_productcf productcf
						on products.productid =productcf.productid
					left join aicrm_crmentity crmproduct
						on crmproduct.crmid = products.productid
					left join aicrm_building
						on products.buildingid = aicrm_building.buildingid
					
					where 1 and crmproduct.deleted=0  ";
		if($productid!=""){
			$query .= " and products.productid = '".$productid."' ";
		}
		
		$data = $this->generate->process($query,"1");

		if(!empty($data)){
			$a_data = array();
			foreach ($data as $key=>$val){
				$a_data[$key] = $val;
				$a_data[$key]["unitno"] =   $val["unitno"];
				$a_data[$key]["typeno"] =  $val["typeno"];
				$a_data[$key]["space"] =   $val["space"];
				$a_data[$key]["unitspace"] =  $val["unitspace"];
			}
			return $a_data;
		}else{
			return null;
		}
	}

	public function get_conditionpayment($a_product=array())
	{
		if(empty($a_product))	return false;
		$key = 0;
		$val= array();
		$data_product = $a_product[0];
		$price = isset($data_product["price"]) &&  $data_product["price"]!="" ?  $data_product["price"]:"0";
		$a_data[$key] = $val;
		$a_data[$key]["deposit"] = "0";
		$a_data[$key]["contract_funds"] =   "0";
		$a_data[$key]["down_payment"] =   "0";
		$a_data[$key]["installment_month"] =   "1";
		$a_data[$key]["transfer_payment"] =  $price - $a_data[$key]["deposit"] ;

		$transfer_payment =$a_data[$key]["transfer_payment"];

		$a_data[$key]["load_rate_text1"] =   "อัตราดอกเบี้ย 6.50% 20 ปี ผ่อนธนาคารงวดละ" ;
		$a_data[$key]["load_rate_text2"] = "อัตราดอกเบี้ย 6.50% 25 ปี ผ่อนธนาคารงวดละ" ;
		$a_data[$key]["load_rate_text3"] =   "อัตราดอกเบี้ย 6.50% 30 ปี ผ่อนธนาคารงวดละ";

		$a_data[$key]["load_rate1"] = $this->calculate_rate("6.50",$transfer_payment,"20") ;
		$a_data[$key]["load_rate2"] = $this->calculate_rate("6.50",$transfer_payment,"25");
		$a_data[$key]["load_rate3"] = $this->calculate_rate("6.50",$transfer_payment,"30") ;

		return $a_data;

	}

	public function get_condition($branchid=null)
	{
		if($branchid=="")	return false;
		$key = 0;
		$val= array();

		$a_data[$key] = $val;
		$condition_text = "1. ทำสัญญาภายใน 7 วันนับจากวันจอง \r";
		$condition_text .="2. ค่าใช้จ่ายในการขอวงเงินกู้กับสถาบันการเงิน และค่าจดจำนอง \r";
		$condition_text .="3. ค่าใช้จ่าย ค่าอากรแสตมป์และค่าธรรมเนียมในการโอนกรรมสิทธิ์ ผู้จะซื้อและผู้จะขายชำระฝ่ายละครึ่ง \r";
		$condition_text .="4. ค่าธรรมเนียมการขอและค่าประกันมิเตอร์ไฟฟ้า 15 Am ผู้ซื้อจะเป็นผู้ชำระ \r";
		$condition_text .="5. ค่าบำรุงรักษาส่วนกลาง ตร.ม. ละ 35 บาท ต่อเดิอน ชำระล่วงหน้า 1 ปี โดยผู้จะซื้อเป็นผู้ชำระ ณ วันโอนกรรมสิทธิ์ \r";
		$condition_text .="6. ค่าดูแลรักษามิเตอร์น้ำ 30 บาท ต่อเดือน จัดเก็บล่วงหน้า 1 ปี ในวันโอนกรรมสิทธิ์ ส่วนปีต่อไปขึ้นกับมติที่ประชุมใหญ่ \r";
		$condition_text .="7. ค่าเงินกองทุนส่วนกลาง ตร.ม. ละ 350 บาท (ชำระครั้งเดียว) โดยผู้จะซื้อ จะเป็นผู้ชำระ ณ วันโอนกรรมสิทธิ์ \r";
		$condition_text .="8. ค่าเบี้ยประกัน 13 บาท ต่อ ตร.ม. จัดเก็บล่วงหน้า 1 ปี ณ วันที่โอนกรรมสิทธิื ส่วนปีต่อไปขึ้นกับมติที่ประชุมใหญ่";
		$a_data[$key]["condition_text"] = $condition_text;


		return $a_data;

	}

	public function get_promotion($a_premium=array())
	{
		if(empty($a_premium))	return false;
		$i=0;
		$premium1="";
		$premium2="";
		foreach ($a_premium as $key=>$value){
			if($i%2==0){
				$premium1 .= $value["PREMIUMNO"]." ".$value["PREMIUMNAME"]. "\r";
			}else{
				$premium2 .= $value["PREMIUMNO"]." ".$value["PREMIUMNAME"]. "\r";
			}
			$i++;
		}
		return array($premium1,$premium2);
	}

	public function calculate_rate($rate="6.5",$price=0,$year="20")
	{
		$month  = $year*12;
		$i = $rate/1200;
		$v = pow(1/(1+($i)),$month);
		$r = $i/(1-$v) * 	$price;
		return $r;
	}
}

?>
