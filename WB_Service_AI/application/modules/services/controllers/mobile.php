<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**
 *  # Class Placearea
 *   ## Webservice Module Placearea สถานที่ใกล้เคียง
*/
class Mobile extends REST_Controller
{
	private $crmid;

  function __construct()
  {
    parent::__construct();
    $this->load->library('memcached_library');
	$this->load->library('crmentity');
    $this->load->database();
	$this->load->library("common");
	$this->_limit = 10;
	$this->_module = "Services";
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
					'PlaceareaNo' => null
			),
	);
  }

  private function set_param($a_param=array())
  {
  	$a_condition = array();
  	if (isset($a_param["contactid"]) && $a_param["contactid"]!="") {
  		$a_condition["contactid"] =  $a_param["contactid"] ;
  	}

  	if (isset($a_param["buildingid"]) && $a_param["buildingid"]!="") {
  		$a_condition["buildingid"] =  $a_param["buildingid"] ;
  	}



  	return $a_condition;
  }

	private function get_cache($a_params=array())
	{
		$this->load->library('managecached_library');


		$a_cache = array();
    	$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';

		$a_condition = array();
		$a_condition = $this->set_param($a_params);

		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];
		$order= @$a_params["orderby"];
		$a_order = $this->common->set_order($order);


		$a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;


		$a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
		$a_build["limit"] = http_build_query($a_limit);

		$a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));

		$a_data = $this->managecached_library->get_memcache($a_cache);


		if($a_data===false)
		{
			$this->load->library('lib_mobile');

			$new=$this->lib_mobile->get_new("","ข่าวประชาสัมพันธ์ - ทั่วไป",$a_order,$a_limit);
			$new_urgent = $this->lib_mobile->get_new(@$a_condition["buildingid"],"ประกาศสำคัญเร่งด่วน",$a_order,$a_limit);
			$parcel=$this->lib_mobile->get_parcel(@$a_condition["contactid"],$a_order,$a_limit);
			$debitnote=$this->lib_mobile->get_debitnote(@$a_condition["contactid"],$a_order,$a_limit);
			$emergency=$this->lib_mobile->get_emergency(@$a_condition["buildingid"],$a_order,$a_limit);

		$a_list["result"]["new"] = isset($new["result"][0]) ? $new["result"][0] : array("total" => "0");
		$a_list["result"]["new_urgent"] =  isset($new_urgent["result"][0]) ? $new_urgent["result"][0] :array("total" => "0");
		$a_list["result"]["parcel"] =  isset($parcel["result"][0]) ? $parcel["result"][0] :array("total" => "0");
		$a_list["result"]["debitnote"] =  isset($debitnote["result"][0]) ? $debitnote["result"][0] :array("total" => "0");
		$a_list["result"]["emergency"] =  isset($emergency["result"][0]) ? $emergency["result"][0] :array("total" => "0");


		if( $new["status"] && $new_urgent["status"] && $parcel["status"] && $debitnote["status"] && $emergency["status"]){
			$a_data["status"] = true;
			$a_data["error"] = "";
		}else{
			$a_data["status"] =false;
			$a_data["error"] =  $new["error"]." ". $new_urgent["error"]." ". $parcel["error"]." " .$debitnote["error"]." " .$emergency["error"];
		}

		$a_data["data"] = $a_list["result"];
		$a_data["limit"] = $a_limit["limit"]  ;
		$a_data["offset"] = $a_limit["offset"]  ;
		$a_data["time"] = date("Y-m-d H:i:s");


		$a_cache["data"] = $a_data["data"];
		$a_cache["data"]["time"] = date("Y-m-d H:i:s");
    		$this->managecached_library->set_memcache($a_cache,"2400");
		}

	    return $a_data;
		}

/**
 *  ## List Content :: Get All Content
 *    | Field                        | Description                                                                                                                                                                |
 *    | ------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
 *    | Description            | Get All Content on Service                                                                                                                                     |
 *    | URL                          | http://localhost/sena/WB_Service_AI/Services/Mobile/list_content?AI-API-KEY=1234&module=Service  |
 *    | Method Type        | Get :: Post                                                                                                                                                                   |
 *
 *
 *  ## Request Parameter
 *  | Name              | Type                 | Description                                                                  					 |  Value  											  | Default Value        | Mandatory	 |
 *  | ------------------- | -------------------   | -------------------------------------------------------------------------------- | --------------------------------------------|-------------------------- |------------------	 |
 *  | format              | String                 | Expected response format                    					                 | json/xml	 										  | json                          | No          	     |
 *  | offset                | Int      	          	    | Start index result set                                  					        	     |           											      | 0                                | No       	         |
 *  | limit                   | Int    	 	                | Number of item in result  0 = unlimit      						             |            											      | 10                              | No       	         |
 *  | AI-API-KEY    | Int     	                | Secret key                                                     						         |      										     	      |                                    | Yes     	         |
 *  | orderby           | String                 | Order by ascending or descending  									 |          											      |                      			   | No      	         |
 *  | contactid       | Int			                | contactid																						 | 											         	      |                      			   | No      	         |
 *  | buildingid      | Int			                |buildingid																						 | 															   |                      			   | No      	         |
 *
 *
 *  ## Return Result
 *  | Name              | Type                 | Description                                                                  					 |  Value  	 																 |
 *  | ------------------- | -------------------   | -------------------------------------------------------------------------------- | ----------------------------------------------------------	 |
 *  | Type              	 | String                 | Return status							                    					                 | S/Success :: E/Error								       	     |
 *  | Message       | String                 | Error Message				                                  					             |     																      	         |
 *  | Total                | INT                     | Result  Total Data								    						             |   																      	         |
 *  | offset				 | INT                     | Start index result set                                   						             |           	   													     	         |
 *  | limit			         | INT		               | Number of item in result  0 = unlimit 	  									 |  																	   	         |
 *	 | data		         | Arra		               | Result Data 																					 |  																	   	         |
 *
 * ## Example Parameter
 * URL :: http://localhost/sena/WB_Service_AI/Services/Mobile/list_content?AI-API-KEY=1234&module=Service&offset:0&limit=0
 *
 *  ## Example Return Success
 *  ~~~~~~~~~~~~~{.py}
 * {
 * 	Type: "S",
 * 	Message: "",
 * 	total: "1",
 * 	offset: "0",
 * 	limit: "0",
 * 	cachetime: "2015-12-21 17:35:02",
 * 	data: [
 * 			{
 * 				placeareaid: "1810966",
 * 				placearea_no: "PLA1",
 * 				placearea_name: "test",
 * 				accountid: "1111512",
 * 				buildingid: "1809240",
 * 				crmid: "1810966",
 * 				smcreatorid: "11057",
 * 				smownerid: "11057",
 * 				modifiedby: "0",
 * 				setype: "Placearea",
 * 				description: "",
 * 				createdtime: "2015-12-21 17:18:51",
 * 				modifiedtime: "2015-12-21 17:18:51",
 * 				viewedtime: "2015-12-21 17:18:52",
 * 				status: null,
 * 				version: "0",
 * 				presence: "1",
 * 				deleted: "0"
 * 			}
 * 		]
 * }
 *~~~~~~~~~~~~~
 */

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
		//$a_param =  $this->input->post();
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
			$a_return["total"] = isset($a_data["data"]["total"]) ? $a_data["data"]["total"] : 0;
			$a_return["offset"] = isset($a_data["offset"])?$a_data["offset"]:0;
			$a_return["limit"] = isset($a_data["limit"])?$a_data["limit"]:0;
			$a_return["cachetime"] = $a_data["time"];
			$a_return["data"] = !empty($a_data["data"]) ? $a_data["data"] : "" ;
		//alert($a_return["data"]);
			if ($format!="json" && $format!="xml"  ) {
					$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
				$this->response(array('error' => 'Couldn\'t find any Data!'), 404);
		}
	}
	
	public function get_version($a_params=array())
	{
		//alert($a_params);exit;
		$this->load->model("services_model");
		$a_list = $this->services_model->get_version($a_params);
		//alert($a_list);exit();
		$a_data = $a_list;
		//alert($a_data);exit;
		//$a_data["data"] = @$a_list["result"]["data"][0];
		$isdetect0 = @$a_data["result"]["data"][0]["isdetect"];
		$isdetect1 = @$a_data["result"]["data"][1]["isdetect"];
		//alert($isdetect1);exit();
		if(isset($isdetect0) && $isdetect0=="1"){
			if(isset($a_params["type"]) && $a_params["type"] == "ios"){
				$mobile_type0 = @$a_data["result"]["data"][0]["mobile_type"];
				$mobile_version0 = @$a_data["result"]["data"][0]["mobile_version"];
				$mobile_msg0 = @$a_data["result"]["data"][0]["msg"];
				$mobile_isdetect0 = @$a_data["result"]["data"][0]["isdetect"];
				if($mobile_type0 == $a_params["type"] && $mobile_version0 != $a_params["version"]){
					//alert($a_params["type"]);exit();
					$a_data["status"] = false;
					$a_data["error"] = "Your Application Not Match iOS Version";
					$a_data["data"]["mobile_version"] = $mobile_version0;
					$a_data["data"]["mobile_type"] = "ios";
					$a_data["data"]["msg"] = $mobile_msg0;
					$a_data["data"]["msg"] = $mobile_isdetect0;
					$a_data["data"]["isdetect"] = "1";
					$a_data["data"]["check"] = "2";//not match
					$a_data["data"]["chk_msg"] = "Do Not Match Version";//not match
				}else{
					$a_data["data"]["mobile_version"] = $mobile_version0;
					$a_data["data"]["mobile_type"] = "ios";
					$a_data["data"]["msg"] = $mobile_msg0;
					$a_data["data"]["isdetect"] = $mobile_isdetect0;
					$a_data["data"]["check"] = "1";//match
					$a_data["data"]["chk_msg"] = "Match Version";// match
				}
			}
		}else{
			$a_data["status"] = true;
			$a_data["data"]["check"] = "1";//match
			$a_data["data"]["chk_msg"] = "Match Version";// match
		}

		if(isset($isdetect1) && $isdetect1=="1"){
			if(isset($a_params["type"]) && $a_params["type"] == "android"){
				$mobile_type1 = @$a_data["result"]["data"][1]["mobile_type"];
				$mobile_version1 = @$a_data["result"]["data"][1]["mobile_version"];
				$mobile_msg1 = @$a_data["result"]["data"][1]["msg"];
				$mobile_isdetect1 = @$a_data["result"]["data"][1]["isdetect"];
				if($mobile_type1 == $a_params["type"] && $mobile_version1 != $a_params["version"]){
					//alert($mobile_type1);exit();
					$a_data["status"] = false;
					$a_data["error"] = "Your Application Not Match Android Version";
					$a_data["data"]["mobile_version"] = $mobile_version1;
					$a_data["data"]["mobile_type"] = "android";
					$a_data["data"]["msg"] = $mobile_msg1;
					$a_data["data"]["isdetect"] = $mobile_isdetect1;
					$a_data["data"]["isdetect"] = "1";
					$a_data["data"]["check"] = "2";//not match
					$a_data["data"]["chk_msg"] = "Do Not Match Version";//not match
				}else{
					$a_data["data"]["mobile_version"] = $mobile_version1;
					$a_data["data"]["mobile_type"] = "android";
					$a_data["data"]["msg"] = $mobile_msg1;
					$a_data["data"]["isdetect"] = $mobile_isdetect1;
					$a_data["data"]["check"] = "1";//match
					$a_data["data"]["chk_msg"] = "Match Version";// match
				}
			}
		}else{
			$a_data["status"] = true;
			$a_data["data"]["check"] = "1";//match
			$a_data["data"]["chk_msg"] = "Match Version";// match
		}
		
		
		//$a_data["data"]["total"] = count($a_list["result"]["data"]);
		//$a_data["limit"] = $a_limit["limit"]  ;
		//$a_data["offset"] = $a_limit["offset"]  ;
		$a_data["time"] = date("Y-m-d H:i:s");
		//alert($a_data);
		return $a_data;
	}
	public function get_version_get()
	{
		$a_param =  $this->input->get();
		$a_data =$this->get_version($a_param);
		$this->return_data($a_data);
	}
	
	public function get_version_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		//$a_param =  $this->input->post();
		$a_data =$this->get_version($a_param);
		$this->return_data($a_data);
	}
}