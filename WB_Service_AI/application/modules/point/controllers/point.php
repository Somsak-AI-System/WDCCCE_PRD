<?php
//header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');
require_once('src/OAuth2/Autoloader.php');
require APPPATH . '/libraries/REST_Controller.php';

class Point extends REST_Controller
{
  /**
   * crmid คือ crmid ใน aicrm_crmentity
   */
  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_point','aicrm_pointcf');
  private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_point'=>'pointid','aicrm_pointcf'=>'pointid');
 
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("point_model");
		$this->_module = "Point";
		$this->_format = "array";
		$this->_limit = 0;
		$this->_return = array(
			'Type' => "S",
			'Message' => "Insert Complete",
			'total' => "1",
			'offset' => "0",
			'limit' => "1",
			'cache_time' => date("Y-m-d H:i:s"),
			// 'data' => array(
			// 	'Crmid' => null,
			// 	'HelpdeskNo' => null
			// ),
		);
		
		$dsn  = 'mysql:dbname=' . $this->config->item('oauth_db_database') . ';host=' . $this->config->item('oauth_db_host');
		$dbusername = $this->config->item('oauth_db_username');
		$dbpassword = $this->config->item('oauth_db_password');
		OAuth2\Autoloader::register();

		// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
		$storage = new OAuth2\Storage\Pdo(array(
			'dsn' => $dsn,
			'username' => $dbusername,
			'password' => $dbpassword
		));
		// Pass a storage object or array of storage objects to the OAuth2 server class
		$this->oauth_server = new OAuth2\Server($storage);
		// Add the "Client Credentials" grant type (it is the simplest of the grant types)
		$this->oauth_server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
		// Add the "Authorization Code" grant type (this is where the oauth magic happens)
		$this->oauth_server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

	}

	public function redeem_post(){
		$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= "Insert_Redeem"; 
		$this->common->set_log($url,$a_request,array());
		$response_data = $this->get_redeem_data($a_request);
	  	$this->common->_filename= "Insert_Redeem";
	  	$this->common->set_log($url,$a_request,$response_data);
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function get_redeem_data($a_request){

	  	if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
			$a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;
            $this->return_data_token($a_data);
		}
			  	
	  	$response_data = null;
	  	$accountid = isset($a_request['accountid']) ? $a_request['accountid'] : "";
	  	$category = isset($a_request['category']) ? $a_request['category'] : "";
	  	$point = isset($a_request['point']) ? $a_request['point'] : "";
	  	$amount = isset($a_request['amount']) ? $a_request['amount'] : "";
	  	$rewardname = isset($a_request['rewardname']) ? $a_request['rewardname'] : "";
	  	$crmid = isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$type = isset($a_request['type']) ? $a_request['type'] : "";
	  	$userid = isset($a_request['userid']) ? $a_request['userid'] : "1";
	  	$action = isset($a_request['action']) ? $a_request['action'] : "add";
	  	
	  	$this->db->select('aicrm_account.accountid ,aicrm_account.point_total,aicrm_account.point_used,aicrm_account.point_remaining');
		$this->db->join('aicrm_accountscf', 'aicrm_accountscf.accountid = aicrm_account.accountid');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_account.accountid');
		$this->db->where(array(
			'aicrm_crmentity.deleted' => 0,
			'aicrm_account.accountid' => $accountid
		));
		$query = $this->db->get('aicrm_account');
		$data_acc = $query->result_array();

        if($point > $data_acc[0]['point_remaining']){
        	$a_return  =  array(
				'Type' => 'E',
				'Message' => 'คะแนนคงเหลือไม่พอ',
			);
			return array_merge($this->_return,$a_return);
        }
       	//alert($data_acc); exit;
        //Set Format Data Redemption
        $data[0]['redemption_no'] = '';
        $data[0]['redemption_name'] = 'Redeem '.$rewardname;//Redemption Name
        $data[0]['redemption_source'] = 'Loyalty Website';//ช่องทางการแลกของรางวัล
        $data[0]['accountid'] = $accountid;//Account Name
        $data[0]['quantity'] = $amount;
        $data[0]['point_used'] = $point;
        $data[0]['redeem_date'] = date('Y-m-d');
        $data[0]['category'] = $category;
        $data[0]['redemption_category'] = $type;
        $data[0]['smownerid'] = '1';//assingto
        $data[0]['smcreatorid'] = '0';
	  	//Set Format Data
	  	if(count($data[0])>0 ){	

  			$module = 'Redemption';
  			$tab_name_redemption = array('aicrm_crmentity','aicrm_redemption','aicrm_redemptioncf');
			$tab_name_index_redemption = array('aicrm_crmentity'=>'crmid','aicrm_redemption'=>'redemptionid','aicrm_redemptioncf'=>'redemptionid');	
  			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$tab_name_redemption,$tab_name_index_redemption,$data,$userid);
  			
  			if($chk=="0"){
  				$redemptionid = $crmid;//Redemption id
  				//Create Point//
  				$module_point = 'Point';
  				$tab_name_point = array('aicrm_crmentity','aicrm_point','aicrm_pointcf');
				$tab_name_index_point = array('aicrm_crmentity'=>'crmid','aicrm_point'=>'pointid','aicrm_pointcf'=>'pointid');	
				$data_point = array();
				$data_point[0]['point_no'] = '';
				$data_point[0]['point_name'] = 'Redeem '.$rewardname;//Point Name
				$data_point[0]['smownerid'] = '1';
				$data_point[0]['smcreatorid'] = '1';
				$data_point[0]['point_source'] = '';//ช่องทางการได้คะแนน
				$data_point[0]['points'] = "";//คะแนน
				$data_point[0]['total_point'] = "";//คะแนนทั้งหมด
				$data_point[0]['used_point'] = "";//คะแนนที่ใช้ไป
				$data_point[0]['remain_point'] = "";//คะแนนคงเหลือ
				$data_point[0]['sourcestatus'] = 'Use';//สถานะคะแนน
				$data_point[0]['accountid'] = $accountid;
				$data_point[0]['redemptionid'] = $crmid;//Redemption id

				$data_point[0]['points'] = $point;
				$data_point[0]['total_point'] = $data_acc[0]['point_total'];//คะแนนทั้งหมด
				//$data_point[0]['used_point'] = $data_premium[0]['cf_1371'];//คะแนนที่ใช้ไป
				$remainingpoints = ($data_acc[0]['point_remaining']-$point);
				$data_point[0]['remain_point'] = $remainingpoints;//คะแนนคงเหลือ
		
				list($chk_point,$crmid_point,$DocNo_point)=$this->crmentity->Insert_Update($module_point,'','add',$tab_name_point,$tab_name_index_point,$data_point,$userid);
				//Create Point//
				
				//คำนวณ ลด Point ตาม FIFO && Update Point ที่ Account
				$this->load->library('lib_point');
				$a_request['brand']= '';
				$a_request['channel']= '';
				$a_request['action'] = 'use';
				$a_request['point'] = $point;
				$a_request['pointid'] =$crmid_point;
	        	$point_balance = $this->lib_point->get_adjust($a_request);
				
  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Successful";
  				$a_return["data"] =array(
  					'point' => $point_balance
  				);

  			}else{
  				$a_return  =  array(
  					'Type' => 'E',
  					'Message' => 'Unable to complete transaction',
  				);
  			}
	  	}else{
	  		$a_return  =  array(
	  				'Type' => 'E',
	  				'Message' =>  'Invalid Request!',
	  		);
	  	}
	  	return array_merge($this->_return,$a_return);
	}

	public function create_salesorder_post(){
		$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson; 
		$response_data = $this->insert_salesorder_data($a_request);
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "Insert_Salesorder_CRM";
	  	$this->common->set_log($url,$a_request,$response_data);
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function insert_salesorder_data($a_request){
		//alert($a_request); exit;
	  	$response_data = null;
	  	$accountid = isset($a_request['accountid']) ? $a_request['accountid'] : "";
	  	$premiumid = isset($a_request['premiumid']) ? $a_request['premiumid'] : "";
	  	$redemptionid = isset($a_request['redemptionid']) ? $a_request['redemptionid'] : "";
	  	$pointid = isset($a_request['pointid']) ? $a_request['pointid'] : "";
  	
	  	$crmid = isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$userid = isset($a_request['userid']) ? $a_request['userid'] : "1";
	  	$action = isset($a_request['action']) ? $a_request['action'] : "add";

	  	$sql_point = "select aicrm_point.pointid , aicrm_pointcf.cf_1414, aicrm_pointcf.cf_1487 
	  	from aicrm_point 
	  	inner join aicrm_pointcf on aicrm_pointcf.pointid = aicrm_point.pointid 
	  	inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_point.pointid
	  	where aicrm_crmentity.deleted = 0 and aicrm_point.pointid = '".$pointid."' ";
	  	$query_point = $this->db->query($sql_point) ;
        $data_point = $query_point->result_array();
        //alert($data_point); exit;
        
        $sql_premium = "select aicrm_premiums.premiumid , aicrm_premiums.premium_name, aicrm_premiumscf.cf_1371, aicrm_premiums.category_premiums from aicrm_premiums 
	  	inner join aicrm_premiumscf on aicrm_premiumscf.premiumid = aicrm_premiums.premiumid 
	  	inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_premiums.premiumid
	  	where aicrm_crmentity.deleted = 0 and aicrm_premiums.premiumid = '".$premiumid."' ";
	  	$query = $this->db->query($sql_premium) ;
        $data_premium = $query->result_array();
        //alert($data_premium); exit;
        
        $sql_acc = "select aicrm_account.accountid , aicrm_accountscf.cf_1484 ,aicrm_accountscf.cf_1485 ,aicrm_account.accountname ,aicrm_accountscf.cf_1580 ,aicrm_accountscf.cf_957 ,aicrm_accountscf.cf_952,aicrm_accountscf.cf_2062 ,aicrm_accountscf.cf_1582 ,aicrm_accountscf.cf_1336 ,aicrm_accountscf.cf_1555 ,aicrm_accountscf.cf_1554 ,aicrm_accountscf.cf_2063 ,aicrm_accountscf.cf_1595 ,aicrm_accountscf.cf_1596 ,aicrm_accountscf.cf_1594 ,aicrm_accountscf.cf_1593 ,aicrm_accountscf.cf_1602 ,aicrm_accountbillads.bill_street ,aicrm_account.address_en ,aicrm_accountbillads.bill_code ,aicrm_account.sp_address ,aicrm_account.sp_address_en
        from aicrm_account 
	  	inner join aicrm_accountscf on aicrm_accountscf.accountid = aicrm_account.accountid 
	  	inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
	  	inner join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid 
	  	where aicrm_crmentity.deleted = 0 and aicrm_account.accountid = '".$accountid."' ";
	  	$query_acc = $this->db->query($sql_acc) ;
        $data_acc = $query_acc->result_array();
        
		$sales_channel = 'Loyalty';
		if($redemptionid!= ''){
			$sales_channel = 'Loyalty - Lucky Draw';
		}
	  	//Set Format Data
	  	if($premiumid != '' && $accountid != '' && $redemptionid != ''){	

  			$module_saleorder = 'SalesOrder';
			$tab_name_saleorder = array('aicrm_crmentity','aicrm_salesorder','aicrm_salesordercf','aicrm_sobillads','aicrm_soshipads');
			$tab_name_index_saleorder = array('aicrm_crmentity'=>'crmid','aicrm_salesorder'=>'salesorderid','aicrm_salesordercf'=>'salesorderid','aicrm_sobillads'=>'sobilladdressid','aicrm_soshipads'=>'soshipaddressid');
			$data_saleorder = array();
			$data_saleorder[0]['salesorder_no'] = '';//SalesOrder No
			$data_saleorder[0]['cf_1603'] = $sales_channel;//ช่องทางการขาย
			$data_saleorder[0]['cf_1318'] = date('Y-m-d');//วันที่สั่งซื้อ
			$data_saleorder[0]['accountid'] = @$accountid;//ชื่อลูกค้า
			$data_saleorder[0]['cf_1319'] = 'Open';//สถานะ
			$data_saleorder[0]['cf_1498'] = @$data_acc[0]['cf_1580'];//หมายเลขสมาชิก
			//CASE65-0093
			$data_saleorder[0]['cf_1805'] = $data_acc[0]['cf_1485'];//คะแนนเริ่มต้น
			// $data_saleorder[0]['cf_1808'] = $data_point[0]['cf_1487'];//คะแนนแลกของรางวัล
			//CASE65-0093
			$data_saleorder[0]['cf_1809'] = $data_acc[0]['cf_957'];//เบอร์มือถือ1

			//Payment Detail
			$data_saleorder[0]['cf_1614'] = 'Redemption';//Payment Type1
			$data_saleorder[0]['cf_1619'] = 'Redemption';//Payment Type2
			
			//Address Information
			$data_saleorder[0]['tax_no'] = @$data_acc[0]['cf_952'];//เลขผู้เสียภาษี
			$data_saleorder[0]['tax_name'] = @$data_acc[0]['accountname'];//ชื่อออกใบกำกับภาษี
			$data_saleorder[0]['address'] = @$data_acc[0]['bill_street'];//ที่อยู่
			$data_saleorder[0]['address_en'] = @$data_acc[0]['address_en'];//ที่อยู่ (EN)
			$data_saleorder[0]['country'] = @$data_acc[0]['cf_2062'];//ประเทศ
			$data_saleorder[0]['cf_1703'] = @$data_acc[0]['cf_1582'];//ภาค
			$data_saleorder[0]['cf_1702'] = @$data_acc[0]['cf_1336'];//จังหวัด
			$data_saleorder[0]['cf_673'] = @$data_acc[0]['cf_1555'];//อำเภอ/เขต
			$data_saleorder[0]['cf_667'] = @$data_acc[0]['cf_1554'];//ตำบล/แขวง
			$data_saleorder[0]['bill_code'] = @$data_acc[0]['bill_code'];//รหัสไปรษณีย์
			//Shipping Information
			$data_saleorder[0]['fullname'] = @$data_acc[0]['accountname'];//ชื่อ-นามสกุลที่จัดส่ง
			$data_saleorder[0]['sp_address'] = @$data_acc[0]['sp_address'];//ที่อยู่ (จัดส่ง)
			$data_saleorder[0]['sp_address_en'] = @$data_acc[0]['sp_address_en'];//ที่อยู่จัดส่ง (EN)
			$data_saleorder[0]['sp_country'] = @$data_acc[0]['cf_2063'];//ประเทศ (จัดส่ง)
			$data_saleorder[0]['cf_1707'] = @$data_acc[0]['cf_1595'];//ภาค (จัดส่ง)
			$data_saleorder[0]['cf_1709'] = @$data_acc[0]['cf_1596'];//จังหวัด (จัดส่ง)
			$data_saleorder[0]['cf_1711'] = @$data_acc[0]['cf_1594'];//อำเภอ / เขต (จัดส่ง)
			$data_saleorder[0]['cf_1713'] = @$data_acc[0]['cf_1593'];//ตำบล / แขวง (จัดส่ง)
			$data_saleorder[0]['cf_1715'] = @$data_acc[0]['cf_1602'];//รหัสไปรษณีย์ (จัดส่ง)
			$data_saleorder[0]['sp_phone'] = @$data_acc[0]['cf_957'];//เบอร์มือถือ (จัดส่ง)
			
			$data_saleorder[0]['smownerid'] = '1';
			$data_saleorder[0]['smcreatorid'] = '1';
			//alert($data_saleorder); exit;

			list($chk_saleorder,$crmid_saleorder,$DocNo_saleorder)=$this->crmentity->Insert_Update($module_saleorder,'','add',$tab_name_saleorder,$tab_name_index_saleorder,$data_saleorder,$userid);
  			
  			if($chk_saleorder=="0"){
					//productrel Sale Order
					$sql_productrel = "SELECT id , productid , quantity,listprice,uom FROM aicrm_inventoryproductrel WHERE id = '".$premiumid."' ";
					$query_rel = $this->db->query($sql_productrel);
				    $data_product = $query_rel->result_array();
					$sequence_no = 1;
					$nettotal=0;

				    foreach ($data_product as $key => $value) {

						$productListprice = $value['listprice'];
						$qPriceList = "SELECT 
							aicrm_pricelists.pricelistid, 
							aicrm_pricelistscf.cf_1696,
							aicrm_inventoryproductrel.productid,
							aicrm_inventoryproductrel.quantity,
							aicrm_inventoryproductrel.listprice,
							aicrm_inventoryproductrel.valid_from,
							aicrm_inventoryproductrel.valid_to
						FROM aicrm_pricelists
						INNER JOIN aicrm_pricelistscf ON aicrm_pricelistscf.pricelistid = aicrm_pricelists.pricelistid
						INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
						INNER JOIN aicrm_inventoryproductrel ON aicrm_inventoryproductrel.id = aicrm_pricelists.pricelistid
						WHERE aicrm_crmentity.deleted = 0
						AND aicrm_pricelistscf.cf_1696 = 'Loyalty'
						AND aicrm_inventoryproductrel.productid = ".$value['productid']."
						AND aicrm_inventoryproductrel.valid_from <= NOW()
						AND aicrm_inventoryproductrel.valid_to >= NOW()
						ORDER BY aicrm_crmentity.crmid";
						$sql = $this->db->query($qPriceList);
						$rsPriceList = $sql->result_array();

						foreach($rsPriceList as $priceList){
							// if(date('Y-m-d') >= $priceList['valid_from'] && date('Y-m-d') <= $priceList['valid_to']){
								$productListprice = $priceList['listprice'];
							// }
						}

				    	$net = 0;
				    	$sql="INSERT INTO aicrm_inventoryproductrel(id, productid, sequence_no, quantity, listprice, pro_type, uom) VALUES ('".$crmid_saleorder."','".$value['productid']."','".$sequence_no."','".$value['quantity']."','".$productListprice."','P','".$value['uom']."')";
				    	$this->db->query($sql);
				    	//echo $sql;
				    	$net = ($value['quantity']*$productListprice);
				    	$nettotal= $nettotal+$net;
				    	$sequence_no++;

				    	//Inset Tmp
				    	$tmp = "INSERT INTO tbt_transation_redemption(productid, redemptionid, redemptionno, salesorderid, qty, listprice) VALUES ('".$value['productid']."','".$redemptionid."','".$DocNo."','".$crmid_saleorder."','".$value['quantity']."','".$productListprice."')";
				    	$this->db->query($tmp);
				    	//Inset Tmp

				    	/*update stock product*/
				    	//cf_2105 =>Qty. Pending Stock
				    	//$sql_ajust = "UPDATE aicrm_productcf SET cf_2105 = cf_2105+(".$value['quantity'].") where productid = '".$value['productid']."' ";
				    	//$this->db->query($sql_ajust);
				    	/*update stock product*/

				    }
				    //updat nettotal sale order
				    $update_saleorder = "Update aicrm_salesorder SET total = '".$nettotal."' , subtotal = '".$nettotal."' WHERE salesorderid = '".$crmid_saleorder."' ";
				    $this->db->query($update_saleorder);
				    //updat nettotal sale order

					//productrel Sale Order			
				

  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Successful";
  				$a_return["data"] =array(
  					'Crmid' => $crmid_saleorder,
  					'DocNo' => $DocNo_saleorder,
  				);

  			}else{
  				$a_return  =  array(
  					'Type' => 'E',
  					'Message' => 'Unable to complete transaction',
  				);
  			}
	  	}else{
	  		$a_return  =  array(
	  				'Type' => 'E',
	  				'Message' =>  'Invalid Request!',
	  		);
	  	}

	  	return array_merge($this->_return,$a_return);
	}

	public function return_data($a_data)
	{
		if($a_data)
		{	
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["error"];
			$a_return["total"] = isset($a_data["data"]["total"]) ? $a_data["data"]["total"]:0;
			$a_return["offset"] = isset($a_data["offset"])?$a_data["offset"]:0;
			$a_return["limit"] = isset($a_data["limit"])?$a_data["limit"]:0;
			$a_return["cachetime"] = $a_data["time"];
			// $a_return["data"] = !empty($a_data["data"]["data"]) ? $a_data["data"]["data"] : "" ;
			if ($format!="json" && $format!="xml"  ) {
				$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
			$this->response(array('error' => 'Couldn\'t find any Service Request!'), 404);
		}
	}

	public function list_history_point_post() {
		$this->common->_filename= "Get_History_Point";

		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
        $a_request = $dataJson;
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->common->set_log('Get History Point==>',$url,$a_request);

        $a_data = $this->get_list_history_point($a_request);
        //alert($a_data); exit;
        $this->return_data_list_history_point($a_data);
	}

	private function get_list_history_point($a_params=array())
	{ 
		if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
			$a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;
            $this->return_data_token($a_data);
		}
        $this->load->library('managecached_library');
        $a_cache = array();
        $a_cache["_ctag"] = $this->_module . '/';
        $a_cache["_ckname"] = $this->_module . '/get_user';
        $a_condition = array();

        $a_condition = $this->set_param($a_params);

        $limit = @$a_params["limit"];
        $offset = @$a_params["offset"];
        $order = @$a_params["orderby"];
        $a_order = $this->set_order($order);

        $a_limit["limit"] = ($limit == "") ? $this->_limit : $limit; 
        $a_limit["offset"] = ($offset == "") ? 0 : $offset;
        $a_data = $this->managecached_library->get_memcache($a_cache);

        $accountid = isset($a_params['accountid']) ? $a_params['accountid'] : "";
        $filter = isset($a_params['filter']) ? $a_params['filter'] : "";
		
        if ($a_data === false) {

        	//$a_list = $this->point_model->get_list_redemption($a_condition,$a_order,$a_limit,$a_params,$filter);
			
			$a_list = $this->point_model->get_list_point($accountid,$a_order,$a_limit,$a_params,$filter);
			
			//$a_data = $this->get_data_history_point($a_list,$a_list1);
			
			$a_data["status"] = $a_list["status"];
			$a_data["error"] = $a_list["error"]  ;
			$a_data["data"] = $a_list["result"];
			$a_data["limit"] = $a_limit["limit"]  ;
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_list["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");	
        }
        //alert($a_data); exit;
        return $a_data;
	}
	private function get_data_history_point($a_data,$a_data1) {
		//alert($a_data); exit;
		$data = array();
		$data['premium'] = array();
		if($a_data['result'] != ''){//echo 4; exit;
			
			$tmp = array();
			$premium = array();
			$datapremium = array();
			foreach($a_data['result']['data'] as $key => $val ){
				$datapremium['pointdatetime'] = $val['cf_1466'];
				$datapremium['premiumid'] = $val['premiumid'];
				//$datapremium['premiumname'] = $val['premium_name'];
				$datapremium['premiumname'] = mb_convert_encoding($val['premium_name'], 'UTF-8', 'tis-620');

				$datapremium['premiumdescription'] = $val['cf_1245'];
				$datapremium['premiumpoint'] = $val['cf_1371'];
								
				$module = 'Premium';
	        	$a_conditionin["aicrm_premiums.premiumid"] = $val['premiumid'];
	        	$a_image = $this->common->get_a_image($a_conditionin, $module);
	        	if(!empty($a_image)){
	        		$id = $val["premiumid"]; 
	        		
	        		$datapremium['image'] = $a_image[$val['premiumid']]['image'];
	        	}else{
	        		$datapremium['image'] = '';
	        	}

				$data['premium'][] = $datapremium;
			}

			/*foreach($tmp as $k => $v){
					$data['premium'][] =  ['pointdate'=> $k , 'data'=> $v];
			}*/
			//echo 5555; exit;
			//$a_data['result']['data'] = $data;
		}
		$data['point'] = array();
		if($a_data1['result'] != ''){
			//cf_1483 วันหมดอายุคะแนน
			//cf_1411 Point Date
			//cf_1414 คะแนนทั้งหมด
			//cf_1488 คะแนนคงเหลือ
			$datapoint = array();
			foreach($a_data1['result']['data'] as $key => $val ){
				
				$datapoint['point_name'] = $val['point_name'];
				$datapoint['pointdate'] = $val['cf_1411'];
				$datapoint['point'] = $val['cf_1488'];

				$data['point'][] = $datapoint;
			}
			//$a_data['result']['data'] = $data;
		}
		//$a_data['result']['data'] = $data;
		$a_data['result']['data'] = $data;
		return $a_data;
	}
	
	public function return_data_list_history_point($a_data) {
		if($a_data)
		{	
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["error"];
			$a_return["total"] = isset($a_data["data"]["total"]) ? $a_data["data"]["total"]:0;
			$a_return["offset"] = isset($a_data["offset"])?$a_data["offset"]:0;
			$a_return["limit"] = isset($a_data["limit"])?$a_data["limit"]:0;
			$a_return["cachetime"] = $a_data["time"];
			$a_return["data"] = !empty($a_data["data"]["data"]) ? $a_data["data"]["data"] : "" ;
			if ($format!="json" && $format!="xml"  ) {
				
				$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
			$this->response(array('error' => 'Couldn\'t find any Service Request!'), 404);
		}
	}

	public function list_history_activity_post() {
		$this->common->_filename= "get_list_history_point";

		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
        $a_request = $dataJson;
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->common->set_log('Get Social==>',$url,$a_request);

        $a_data = $this->get_list_history_activity($a_request);
        
        $this->return_data_list_history_activity($a_data);
	}

	private function get_list_history_activity($a_params=array())
	{ 
		if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
			$a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;
            $this->return_data_token($a_data);
		}
        $this->load->library('managecached_library');
        $a_cache = array();
        $a_cache["_ctag"] = $this->_module . '/';
        $a_cache["_ckname"] = $this->_module . '/get_user';
        $a_condition = array();

        $a_condition = $this->set_param($a_params);

        $limit = @$a_params["limit"];
        $offset = @$a_params["offset"];
        $order = @$a_params["orderby"];
        $a_order = $this->set_order($order);

        $a_limit["limit"] = ($limit == "") ? $this->_limit : $limit; 
        $a_limit["offset"] = ($offset == "") ? 0 : $offset;
        $a_data = $this->managecached_library->get_memcache($a_cache);

        $accountid = isset($a_params['accountid']) ? $a_params['accountid'] : "";
		$filter = isset($a_params['filter']) ? $a_params['filter'] : "";
        if ($a_data === false) {

        	$a_list=$this->point_model->get_list_redemactivity($a_condition,$a_order,$a_limit,$a_params,$filter);
			$a_data = $this->get_data_list_history_activity($a_list);

			$a_data["status"] = $a_list["status"];
			$a_data["error"] = $a_list["error"]  ;
			$a_data["data"] = $a_data["result"];
			$a_data["limit"] = $a_limit["limit"]  ;
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_list["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");	
        }
        
        return $a_data;
	}
	private function get_data_list_history_activity($a_data)
	{
		if($a_data['result'] != ''){

			//$data['redemptiondate'] = array();
			$data = array();
			$tmp = array();
			$datapremium = array();

			foreach($a_data['result']['data'] as $key => $val ){
				$datapremium['redemptiondate'] = $val['cf_1466'];
				$datapremium['premiumid'] = $val['premiumid'];
				$datapremium['premiumname'] = $val['premium_name'];
				$datapremium['premiumdescription'] = $val['cf_1245'];

				$datapremium['point'] = $val['cf_1430'];

				$module = 'Premium';
	        	$a_conditionin["aicrm_premiums.premiumid"] = $val['premiumid'];
	        	$a_image = $this->common->get_a_image($a_conditionin, $module);
	        	if(!empty($a_image)){
	        		$id = $val["premiumid"]; 
	        		
	        		$datapremium['image'] = $a_image[$val['premiumid']]['image'];
	        	}else{
	        		$datapremium['image'] = '';
	        	}
	        	//alert($val); exit;
				$data[] = $datapremium;
				
			}

			/*foreach($tmp as $k => $v){
				$data['redemptiondate'][] =  ['date'=> $k , 'data'=> $v];
			}*/
			// alert($data); exit;
			$a_data['result']['data'] = $data;
		}
		return $a_data;
	}

	public function return_data_list_history_activity($a_data) {
		if($a_data)
		{	
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] = ($a_data["error"])?"Error":"Successful";;
			$a_return["total"] = isset($a_data["data"]["total"]) ? $a_data["data"]["total"]:0;
			$a_return["offset"] = isset($a_data["offset"])?$a_data["offset"]:0;
			$a_return["limit"] = isset($a_data["limit"])?$a_data["limit"]:0;
			$a_return["cachetime"] = $a_data["time"];
			$a_return["data"] = !empty($a_data["data"]["data"]) ? $a_data["data"]["data"] : "" ;
			if ($format!="json" && $format!="xml"  ) {
				$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
			$this->response(array('error' => 'Couldn\'t find any Service Request!'), 404);
		}
	}

	public function return_data_token($a_data)
	{
		if($a_data)
		{
		  $format =  $this->input->get("format",true);
		  $a_return["Type"] = ($a_data["status"])?"S":"T";
		  $a_return["Message"] = $a_data["error"];
		  $a_return["total"] = @$a_data["data"]["total"];
		  $a_return["offset"] = $a_data["offset"];
		  $a_return["limit"] = $a_data["limit"];
		  $a_return["cachetime"] = $a_data["time"];
		  $a_return["data"] = @$a_data["data"]["data"];
		  if ($format!="json" && $format!="xml"){
		    $this->response($a_return, 200); // 200 being the HTTP response code
		  }else{
		    $this->response($a_return, 200); // 200 being the HTTP response code
		  }
		}
		else
		{
		  $this->response(array('error' => 'Couldn\'t find any Building!'), 404);
		}
	}

	private function set_param($a_param=array())
	{
		$a_condition = array();
	
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
			$a_condition["aicrm_crmentity.crmid"] =  $a_param["crmid"] ;
		}
		if (isset($a_param["type"]) && $a_param["type"]!="") {
			$a_condition["aicrm_point.type"] =  $a_param["type"] ;
		}
		if (isset($a_param["accountid"]) && $a_param["accountid"]!="") {
			$a_condition["aicrm_redemption.accountid"] =  $a_param["accountid"] ;
		}
		if (isset($a_param["premiumid"]) && $a_param["premiumid"]!="") {
			$a_condition["aicrm_point.premiumid"] =  $a_param["premiumid"] ;
		}



		return $a_condition;
	}

	private function set_order($a_orderby=array())
	{
		if(empty($a_orderby)) return false;
	
		$a_order = array();
		$a_condition = explode( "|",$a_orderby);
	
		for ($i =0;$i<count($a_condition) ;$i++)
		{
			list($field,$order) = explode(",", $a_condition[$i]);
			$a_order[$i]["field"] = $field;
			$a_order[$i]["order"] = $order;
		}
	
		return $a_order;
	}

	public function add_code_post(){
	  	$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);
	  	
	  	$response_data = null;
	  	$a_request =$dataJson; 
		$response_data = $this->get_insert_code($a_request);
	  	
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "Insert_Point_Code";

	  	$this->common->set_log($url,$a_request,$response_data);
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function get_insert_code($a_request){

		if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
			$a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;
            $this->return_data_token($a_data);
		}

	  	$response_data = null;
	  	$accountid = isset($a_request['accountid']) ? $a_request['accountid'] : "";
	  	$module = isset($a_request['module']) ? $a_request['module'] : "";
	  	$action = isset($a_request['action']) ? $a_request['action'] : "";
	  	$crmid= isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$userid =isset($a_request['userid']) ? $a_request['userid'] : "1";
	 	$data = isset($a_request['data']) ? $a_request['data'] : "";

	  	if($module=="Point"){
			
			$sql_acc = "SELECT aicrm_account.accountid ,aicrm_account.accountname ,aicrm_account.point_total , aicrm_account.point_remaining FROM aicrm_account 
	  					inner join aicrm_accountscf on aicrm_accountscf.accountid = aicrm_account.accountid 
	  					inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
	  					where aicrm_crmentity.deleted = 0 and aicrm_account.accountid = '".$accountid."' "; 
		  	$query_acc = $this->db->query($sql_acc) ;
	        $data_acc = $query_acc->result_array();

	        $acc_name = $data_acc[0]['accountname'];
	        $point_total = $data_acc[0]['point_total'];//คะแนนสะสมทั้งหมด (แต้ม)
	        $point_remaining = $data_acc[0]['point_remaining'];//คะแนนสะสมคงเหลือ (แต้ม)

	  		foreach ($data as $key => $value) {
	  			$data_point = array();
	  			//New total point
	  			$point_total = ($point_total+$value['point']);
				$point_remaining = ($point_remaining+$value['point']);

	  			$data_point[0]['point_no'] = '';//Point No
				$data_point[0]['point_name'] = 'Add code '.$value['code'];//Point Name
				$data_point[0]['point_source'] = 'Voucher';
				$data_point[0]['points'] = $value['point'];
				$data_point[0]['total_point'] = $point_total;
				$data_point[0]['remain_point'] = $point_remaining;
				$data_point[0]['used_point'] = 0;
				$data_point[0]['accountid'] = $accountid;//Accountid
				$data_point[0]['sourcestatus'] = "Add";
				$data_point[0]['serial_no'] = $value['code'];
				$data_point[0]['smownerid'] = '1';//Assigned To
				$data_point[0]['smcreatorid'] = '1';//Assigned To

				$data_point[0]['point_start_date'] = date('Y-m-d');
				$data_point[0]['point_expired_date'] = date('Y-12-31', strtotime('+1 year'));

				list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data_point,$userid);
				
				if($chk=="0"){
					$a_data=array();
					$a_data['action'] = 'add';
					$a_data['brand'] = '';
					$a_data['channel'] = 'Add Code Loyalty';
					$a_data['point'] = $value['point'];
					$a_data['accountid'] = $accountid;
					$a_data['type'] = '';
					$a_data['pointid'] = $crmid;
					$this->load->library('lib_point');
    				$this->lib_point->get_adjust($a_data);
				}
	  		}

	  		if($chk=="0"){
  				$a_return["Message"] = ($action=="add")?"Successful" : "Successful";

  				$a_return["data"] =array(
  					'point' => $point_remaining,
  				);

  			}else{
  				$a_return  =  array(
  					'Type' => 'E',
  					'Message' => 'Unable to complete transaction',
  					'data' => array(
                        'point' => ''
                    )
  				);
  			}

	  		
	  	}else{
	  		$a_return  =  array(
	  				'Type' => 'E',
	  				'Message' =>  'Invalid Request!',
	  		);
	  	}

	  	return array_merge($this->_return,$a_return);
	}

	public function addjust_post(){
		$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);
	  	
	  	$response_data = null;
	  	$a_request =$dataJson; 
		$response_data = $this->addjust_data($a_request);
	  	
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "Insert_Addjust";

	  	$this->common->set_log($url,$a_request,$response_data);
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function addjust_data($a_request){
	  	$response_data = null;
	  	$accountid = isset($a_request['accountid']) ? $a_request['accountid'] : "";
	  	$brand = isset($a_request['brand']) ? $a_request['brand'] : "";
	  	$channel = isset($a_request['channel']) ? $a_request['channel'] : "";
	  	$point = isset($a_request['point']) ? $a_request['point'] : "";
	  	$type = isset($a_request['type']) ? $a_request['type'] : "";
	  	$userid = isset($a_request['userid']) ? $a_request['userid'] : "1";
	  	$action = isset($a_request['action']) ? $a_request['action'] : "add";
	  	$crmid= isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	
	  	if($accountid != ''){
	  		$sql_acc = "SELECT aicrm_account.accountid ,aicrm_account.accountname ,aicrm_account.point_total , aicrm_account.point_remaining FROM aicrm_account 
				inner join aicrm_accountscf on aicrm_accountscf.accountid = aicrm_account.accountid 
				inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
				where aicrm_crmentity.deleted = 0 and aicrm_account.accountid = '".$accountid."' "; 
			$query_acc = $this->db->query($sql_acc) ;
			$data_acc = $query_acc->result_array();
			$acc_name = $data_acc[0]['accountname'];
			
			if($action == 'add'){
				$point_total= ($data_acc[0]['point_total']+$point);//คะแนนสะสมทั้งหมด
				$point_used= $data_acc[0]['point_used'];
				$point_remaining= ($data_acc[0]['point_remaining']+$point);//คะแนนสะสมคงเหลือ
			}else{
				$point_total=$data_acc[0]['point_total'];
				$point_used= ($data_acc[0]['point_used']+$point);//คะแนนที่ใช้ไป
				$point_remaining= ($data_acc[0]['point_remaining']-$point);//คะแนนสะสมคงเหลือ
			}
			
			$data_point = array();
			//New total point

			$data_point[0]['point_no'] = '';//Point No
			$data_point[0]['point_name'] = 'Adjust Point';//Point Name
			$data_point[0]['point_source'] = '--None--';
			$data_point[0]['points'] = $point;
			$data_point[0]['total_point'] = $point_total;
			$data_point[0]['remain_point'] = $point_remaining;
			
			if($action == 'add'){
				$data_point[0]['used_point'] = 0;
			}
			
			$data_point[0]['accountid'] = $accountid;//Accountid
			
			if($action == 'use'){
				$data_point[0]['sourcestatus'] = "Use";
			}else if($action == 'delete'){
				$data_point[0]['sourcestatus'] = "Delete";
			}else{
				$data_point[0]['sourcestatus'] = "Add";
				$data_point[0]['point_expired_date'] = date('Y-12-31', strtotime('+1 year'));
			}
			
			$data_point[0]['serial_no'] = '';
			$data_point[0]['smownerid'] = '1';//Assigned To
			$data_point[0]['smcreatorid'] = '1';//Assigned To
			$data_point[0]['point_start_date'] = date('Y-m-d');
			
			$module="Point";
			$action_crm='add';

			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action_crm,$this->tab_name,$this->tab_name_index,$data_point,$userid);

			if($chk=="0"){
				$a_data=array();
				$a_data['action'] = $action;
				$a_data['brand'] = $brand;
				$a_data['channel'] = $channel;
				$a_data['point'] = $point;
				$a_data['accountid'] = $accountid;
				$a_data['type'] = $type;
				$a_data['pointid'] = $crmid;
				$this->load->library('lib_point');
				$data = $this->lib_point->get_adjust($a_data);
				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Successful";
				$a_return["data"] =array(
					'point' => $data
				);
			}
	  	
	  	}else{
	  		$a_return  =  array(
	  				'Type' => 'E',
	  				'Message' =>  'Invalid Request!',
	  		);
	  	}

	  	return array_merge($this->_return,$a_return);
	}

	
}