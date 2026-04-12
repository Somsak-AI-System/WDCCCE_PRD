<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Order extends REST_Controller
{

  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_order','aicrm_ordercf','aicrm_account','aicrm_contactdetails','aicrm_plant');
  private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_order'=>'orderid','aicrm_ordercf'=>'orderid','aicrm_account'=>'accountid','aicrm_contactdetails'=>'contactid','aicrm_plant'=>'plantid');
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->_module = "Order";
		$this->load->model("order_model");
		$this->_format = "array";
		$this->_return = array(
			'Type' => "S",
			'Message' => "Insert Complete",
			'cache_time' => date("Y-m-d H:i:s"),
			'total' => "1",
			'offset' => "0",
			'limit' => "1",
			'data' => array(
				'Crmid' => null,
				'DocNo' => null
			),
		);
	}
	
	//###################### set param ################################
	private function set_param($a_param=array())
	{
		$a_condition = array();

			
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
			$a_condition["aicrm_order.orderid"] =  $a_param["crmid"] ;
		}

		if (isset($a_param["accountid"]) && $a_param["accountid"]!="") {
			$a_condition["aicrm_order.accountid"] = $a_param["accountid"];
		}

		if (isset($a_param["contactid"]) && $a_param["contactid"]!="") {
			$a_condition["aicrm_order.contactid"] = $a_param["contactid"];
		}

		if (isset($a_param["contact_no"]) && $a_param["contact_no"]!="") {
			$a_condition["aicrm_order.contact_no like"] = "%".$a_param["contact_no"]."%";
		}

		if (isset($a_param["telephone"]) && $a_param["telephone"]!="") {
			$a_condition["aicrm_order.telephone like"] = "%".$a_param["telephone"]."%";
		}

		/*if (isset($a_param["plan_date"]) && $a_param["plan_date"]!="") {
			$a_condition["aicrm_order.plan_date like"] = "%".$a_param["plan_date"]."%";
		}*/

		if (isset($a_param["line_id"]) && $a_param["line_id"]!="") {
			$a_condition["aicrm_contactdetails.line_id like"] = "%".$a_param["line_id"]."%";
		}

		if (isset($a_param["accountname"]) && $a_param["accountname"]!="") {
			$a_condition["aicrm_account.accountname like"] = "%".$a_param["accountname"]."%";
		}

		if (isset($a_param["project_address"]) && $a_param["project_address"]!="") {
			$a_condition["aicrm_order.project_address like"] = "%".$a_param["project_address"]."%";
		}

		if (isset($a_param["conract_name"]) && $a_param["conract_name"]!="") {
			$a_condition["concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname ) like "] =  "%".$a_param["conract_name"]."%" ;
		}

		if (isset($a_param["delivery_location"]) && $a_param["delivery_location"]!="") {
			$a_condition["aicrm_order.delivery_location like"] = "%".$a_param["delivery_location"]."%";
		}
		
		if (isset($a_param["date_from"]) && isset($a_param["date_to"]) && $a_param["date_from"]!="" && $a_param["date_to"]!="" ) {
			
			$order_date = "aicrm_order.order_date";
			$date_from = $a_param['date_from'];
			$date_to = $a_param['date_to'];
			$this->db->where("$order_date  BETWEEN '$date_from' AND '$date_to'");
		
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
		$this->load->model("order_model");
	
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
			$a_list=$this->order_model->get_list($a_condition,$a_order,$a_limit);
			// alert($a_list); exit();
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
			foreach($a_data['result']['data'] as $key => $val ){
				//alert($val['orderid']); exit;
				$sql = "select aicrm_inventoryproductrelorder.* " .
					" from aicrm_inventoryproductrelorder" .
					" left join aicrm_products on aicrm_products.productid=aicrm_inventoryproductrelorder.productid " .
					" where id= '".$val['orderid']."' ORDER BY sequence_no";
				$data_sql = $this->db->query($sql);
				$data_detail=$data_sql->result_array();

				//alert($data_detail);exit;
				$a_data['result']['data'][$key]['detail'] = $data_detail;
				/*$sub_index = iconv_substr($val['accountname'],0,1,"UTF-8");
				$a_data['result']['data'][$key]['index_name'] = $sub_index;*/
			}
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
		// echo "hi"; exit();
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
	  	$this->common->_filename= "Insert_Order";

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
	  	//alert($data);exit;

	  		if(count($data[0])>0 and $module=="Order"){
	  			
	  			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data);

	  			if($action=="add"){
	  				$prod_seq=1;
	  				for($i=1; $i<=4; $i++)
					{	
						if($i==1){
							$prod_id = @$data[0]['ProductId'];
						}else{
							$prod_id = '';
						}
						
					    $productname = @$data[0]['productName'.$i];
					    $producttype = '';
					    if($i==1 || $i==2){
					    	$producttype = @$data[0]['producttype'.$i];	
					    }
					    $km = '';
					    if($i==1){
					    	$km = @$data[0]['km'];	
					    }
					    $zone = @$data[0]['zone'];
					    $carsize = @$data[0]['carsize'];
					    $unit = @$data[0]['unit'.$i];
					    $number = @$data[0]['number'.$i];
					    $priceperunit = str_replace(',', '', @$data[0]['priceperunit'.$i]);
					    $amount = str_replace(',', '', @$data[0]['amount'.$i]);
					    $min = @$data[0]['min'];
					    $dlv_c = @$data[0]['dlv_c'];
					    $dlv_cvat = @$data[0]['dlv_cvat'];
					    $dlv_pvat = @$data[0]['dlv_pvat'];
					    $lp = @$data[0]['lp'];
					    $discount = @$data[0]['discount'];
					    $c_cost = str_replace(',', '', @$data[0]['c_cost']);
					    $afterdiscount = str_replace(',', '', @$data[0]['afterdiscount'.$i]);
					    $purchaseamount = str_replace(',', '', @$data[0]['purchaseamount'.$i]);

					    $subtotal1 = @$data[0]['subTotal1'];
					    $vat1 = @$data[0]['Vat1'];
					    $total1 = @$data[0]['Total1'];
					    $subtotal2 = @$data[0]['subTotal2'];
					    $vat2 = @$data[0]['Vat2'];
					    $total2 = @$data[0]['Total2'];
					    $profit = @$data[0]['profit'];

	  					$query ="insert into aicrm_inventoryproductrelorder(
	  					id,			productid,		sequence_no,		productname,		producttype,		km,zone,		carsize,		unit,
	  					number,		priceperunit,	amount,				min,				dlv_c,				dlv_cvat,		dlv_pvat,		lp,
	  					discount,	c_cost,			afterdiscount,		purchaseamount,		subtotal1,			vat1,			total1,			subtotal2,
	  					vat2,		total2,			profit) VALUES
	  					('".$crmid."','".$prod_id."','".$prod_seq."','".$productname."','".$producttype."','".$km."','".$zone."','".$carsize."','".$unit."','".$number."','".$priceperunit."','".$amount."','".$min."','".$dlv_c."','".$dlv_cvat."','".$dlv_pvat."','".$lp."','".$discount."','".$c_cost."','".$afterdiscount."','".$purchaseamount."','".$subtotal1."','".$vat1."','".$total1."','".$subtotal2."','".$vat2."','".$total2."','".$profit."'
	  					)";
	  					//echo $quer; exit;
	  					$this->db->query($query);

	  					$prod_seq++;
	  				}

	  			}else{
	  				$sql = "delete from aicrm_inventoryproductrelorder where id= '".$crmid."' ";
	  				$this->db->query($sql);

	  				$prod_seq=1;
	  				for($i=1; $i<=4; $i++)
					{	
						//$prod_id = @$data[0]['ProductId'.$i];
					    if($i==1){
							$prod_id = @$data[0]['ProductId'];
						}else{
							$prod_id = '';
						}
					    $productname = @$data[0]['productName'.$i];
					    $producttype = '';
					    if($i==1 || $i==2){
					    	$producttype = @$data[0]['producttype'.$i];	
					    }
					    $km = '';
					    if($i==1){
					    	$km = @$data[0]['km'];	
					    }
					    $zone = @$data[0]['zone'];
					    $carsize = @$data[0]['carsize'];
					    $unit = @$data[0]['unit'.$i];
					    $number = @$data[0]['number'.$i];
					    $priceperunit = str_replace(',', '', @$data[0]['priceperunit'.$i]);
					    $amount = str_replace(',', '', @$data[0]['amount'.$i]);
					    $min = @$data[0]['min'];
					    $dlv_c = @$data[0]['dlv_c'];
					    $dlv_cvat = @$data[0]['dlv_cvat'];
					    $dlv_pvat = @$data[0]['dlv_pvat'];
					    $lp = @$data[0]['lp'];
					    $discount = @$data[0]['discount'];
					    $c_cost = str_replace(',', '', @$data[0]['c_cost']);
					    $afterdiscount = str_replace(',', '', @$data[0]['afterdiscount'.$i]);
					    $purchaseamount = str_replace(',', '', @$data[0]['purchaseamount'.$i]);

					    $subtotal1 = @$data[0]['subTotal1'];
					    $vat1 = @$data[0]['Vat1'];
					    $total1 = @$data[0]['Total1'];
					    $subtotal2 = @$data[0]['subTotal2'];
					    $vat2 = @$data[0]['Vat2'];
					    $total2 = @$data[0]['Total2'];
					    $profit = @$data[0]['profit'];

	  					$query ="insert into aicrm_inventoryproductrelorder(
	  					id,			productid,		sequence_no,		productname,		producttype,		km,zone,		carsize,		unit,
	  					number,		priceperunit,	amount,				min,				dlv_c,				dlv_cvat,		dlv_pvat,		lp,
	  					discount,	c_cost,			afterdiscount,		purchaseamount,		subtotal1,			vat1,			total1,			subtotal2,
	  					vat2,		total2,			profit) VALUES
	  					('".$crmid."','".$prod_id."','".$prod_seq."','".$productname."','".$producttype."','".$km."','".$zone."','".$carsize."','".$unit."','".$number."','".$priceperunit."','".$amount."','".$min."','".$dlv_c."','".$dlv_cvat."','".$dlv_pvat."','".$lp."','".$discount."','".$c_cost."','".$afterdiscount."','".$purchaseamount."','".$subtotal1."','".$vat1."','".$total1."','".$subtotal2."','".$vat2."','".$total2."','".$profit."'
	  					)";

	  					$this->db->query($query);
	  					$prod_seq++;
	  				}


	  			}

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


	public function get_order_post(){

		$this->common->_filename= "Detail_Order";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Detail Order==>',$url,$a_request);

		$response_data = $this->order_model->get_order($a_request);
		// alert($response_data);exit;

	  	$this->common->set_log('After Detail Order==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

}