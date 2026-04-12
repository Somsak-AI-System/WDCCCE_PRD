<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Promotion extends REST_Controller
{

  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_promotion','aicrm_promotioncf');
  private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_promotion'=>'promotionid','aicrm_promotioncf'=>'promotionid');
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
	    $this->load->database();
		$this->load->library("common");
		$this->_module = 'Promotion';
		$this->load->model("promotion_model");
		$this->_return = array(
				'Type' => "S",
				'Message' => "Insert Complete",
				'cache_time' => date("Y-m-d H:i:s"),
				'total' => "1",
				'offset' => "0",
				'limit' => "1",
				'data' => array(
						'Crmid' => null,
						'PromotionNo' => null
				),
		);
	}
	
	//###################### set param ################################
	private function set_param($a_param=array())
	{
		$a_condition = array();
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
			$a_condition["aicrm_promotion.promotionid"] =  $a_param["crmid"] ;
		}

		if (isset($a_param["accountid"]) && $a_param["accountid"]!="") {
			$a_condition["aicrm_promotion.accountid"] = $a_param["accountid"];
		}
		if (isset($a_param["contactid"]) && $a_param["contactid"]!="") {
			$a_condition["aicrm_promotion.contactid"] = $a_param["contactid"];
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

	private function get_cache($a_params=array())
	{
		$this->load->library('managecached_library');
		$this->load->model("promotion_model");
	
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		$a_condition = array();
		$a_condition = $this->set_param($a_params);
		
		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];
		$order= @$a_params["orderby"];
		$a_order = $this->set_order($order);
	
		$a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;
	
		$a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
		$a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
		$a_build["limit"] = http_build_query($a_limit);
	
		$a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));
		
		$a_data = $this->managecached_library->get_memcache($a_cache);
	
		if($a_data===false)
		{
			$a_list=$this->promotion_model->get_list($a_condition,$a_order,$a_limit);
			//alert($a_list); exit();
			$a_data = $this->get_data($a_list);
			$a_data["data"] = $a_data["result"];
			$a_data["limit"] = $a_limit["limit"]  ;
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_data["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
	
		return $a_data;
	}
	
	private function get_data($a_data)
	{
		if($a_data['result'] != ''){			
		}

		return $a_data;
	}

	public function list_content_get()
	{
		$a_param =  $this->input->get();
		$a_data =$this->get_cache($a_param);

		$this->return_data($a_data);
	}
	
	public function list_content_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data =$this->get_cache($a_param);
		$this->return_data($a_data);
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

	public function insert_content_post(){
	  	$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);
	  	
	  	$response_data = null;
	  	$a_request =$dataJson; 
		$response_data = $this->get_insert_data($a_request);
	  	
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->_filename= "Insert_Promotion";

	  	$this->common->set_log($url,$a_request,$response_data);
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function get_insert_data($a_request){

	  	$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";

	  		if(count($data[0])>0 and $module== "Promotion"){
	  			
	  			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data);
	  			
	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  					'Crmid' => $crmid,
	  					'DocNo' => $DocNo,
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

	public function get_voucher_post(){

		$this->common->_filename= "Get_Voucher";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->set_log('Before Get Voucher==>',$url,$a_request);
		$response_data = $this->get_dataVoucher($a_request);	
	  	$this->common->set_log('After Get Voucher==>',$a_request,$response_data);

		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
					'error' => 'Couldn\'t find Set Content!'
			), 404);
		}

	}

	private function get_dataVoucher($a_request){

		$response_data = null;
		$accountid = isset($a_request['crmid']) ? $a_request['crmid'] : "";
		$keyword = isset($a_request['keyword']) ? $a_request['keyword'] : "";
		
		if (isset($keyword) && $keyword!="") {

			$select = "SELECT aicrm_promotion.promotionid,
				aicrm_promotion.promotion_vkeyword,
				aicrm_promotion.promotion_vwording,
				aicrm_promotion.enddate

			FROM aicrm_promotion
			INNER JOIN aicrm_promotioncf ON aicrm_promotioncf.promotionid = aicrm_promotion.promotionid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_promotion.promotionid 
		WHERE
			aicrm_crmentity.deleted = 0 
			AND aicrm_promotion.promotion_vkeyword <> ''
			AND aicrm_promotion.promotion_vkeyword = '".$keyword."' ";
			
			$query = $this->db->query($select);
			
			$a_data =  $query->result_array();
			$promotionid = $a_data[0]['promotionid'];
			$wording = $a_data[0]['promotion_vwording'];
			$enddate = $a_data[0]['enddate'];

			if(count($a_data) > 0){

				//Check promotion expiry date
				$expire = strtotime($enddate);
				$today = strtotime("today midnight");

				if($today > $expire){
					$a_return  =  array(
						'Type' => 'E',
						'Message' =>  'กิจกรรมนี้หมดอายุแล้ว',
						'data' => ''
					);
				}

				//Check exceeding the right
				$checkVoucher = "SELECT *
                FROM aicrm_voucher
                INNER JOIN aicrm_vouchercf ON aicrm_vouchercf.voucherid = aicrm_voucher.voucherid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_voucher.voucherid
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_voucher.accountid
                WHERE 
				aicrm_crmentity.deleted = 0 
				AND aicrm_voucher.voucher_status <> 'สร้าง' 
				AND aicrm_voucher.promotionid ='".$promotionid."'
				/*AND aicrm_account.accountid = '".$accountid."'*/
				";
				$queryCheckVoucher = $this->db->query($checkVoucher);
				$dataCheckVoucher =  $queryCheckVoucher->result_array();

				if(count($dataCheckVoucher) >0){
						$a_return  =  array(
							'Type' => 'E',
							'Message' =>  'คุณใช้สิทธิเกินที่กำหนด',
							'data' => ''
						);
				}

				//get voucher
				$selectVoucher = "SELECT voucher_no
                FROM aicrm_voucher
                INNER JOIN aicrm_vouchercf ON aicrm_vouchercf.voucherid = aicrm_voucher.voucherid
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_voucher.voucherid
				LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_voucher.accountid
                WHERE 
				aicrm_crmentity.deleted = 0 
				AND aicrm_voucher.voucher_status = 'สร้าง' 
				AND aicrm_voucher.promotionid ='".$promotionid."'
				ORDER BY aicrm_crmentity.crmid ASC limit 1
				";
				$queryVoucher = $this->db->query($selectVoucher);
				$dataVoucher =  $queryVoucher->result_array();
				
				$link = "https://gwmcuat.moai-crm.com/qrcode.php?gen=".$dataVoucher[0]['voucher_no'];

				$a_return  =  array(
					'Type' => 'S',
					'Message' =>  $wording,
					'data' => $link
				);

			}else{

				$a_return  =  array(
					'Type' => 'S',
					'Message' =>  '',
					'data' => ''
				);
			}

		}else{

			$a_return  =  array(
				'Type' => 'E',
				'Message' =>  'Invalid Request!'
			);

		}

		return array_merge($this->_return,$a_return);
  	}

}