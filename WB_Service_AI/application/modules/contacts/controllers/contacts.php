<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Contacts extends REST_Controller
{

  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_contactdetails','aicrm_contactaddress','aicrm_contactsubdetails','aicrm_contactscf','aicrm_customerdetails');
  private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_contactdetails'=>'contactid','aicrm_contactaddress'=>'contactaddressid','aicrm_contactsubdetails'=>'contactsubscriptionid','aicrm_contactscf'=>'contactid','aicrm_customerdetails'=>'customerid');
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
				$this->load->model("contacts_model");
		$this->_module = "Contacts";
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
				'ContacsNo' => null
			),
		);
	}
	
	//###################### set param ################################
	private function set_param($a_param=array())
	{
		$a_condition = array();

			
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
			$a_condition["aicrm_contactdetails.contactid"] =  $a_param["crmid"] ;
		}

		if (isset($a_param["firstname"]) && $a_param["firstname"]!="") {
			//$a_condition["aicrm_contactdetails.firstname like "] =  "%".$a_param["firstname"]."%" ;
			$a_condition["concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname ) like "] =  "%".$a_param["firstname"]."%" ;
			
		}

		if (isset($a_param["contactno"]) && $a_param["contactno"]!="") {
			$a_condition["aicrm_contactdetails.contact_no like "] =  "%".$a_param["contactno"]."%" ;
		}

		if (isset($a_param["mobile"]) && $a_param["mobile"]!="") {
			$a_condition["aicrm_contactdetails.mobile like"] = "%".$a_param["mobile"]."%";
		}

		if (isset($a_param["line_id"]) && $a_param["line_id"]!="") {
			$a_condition["aicrm_contactdetails.line_id like"] = "%".$a_param["line_id"]."%";
		}

		if (isset($a_param["facebook"]) && $a_param["facebook"]!="") {
			$a_condition["aicrm_contactdetails.facebook like"] = "%".$a_param["facebook"]."%";
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
		$this->load->model("Contacts_model");
	
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
			$a_list=$this->Contacts_model->get_list($a_condition,$a_order,$a_limit);
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
		/*if($a_data['result'] != ''){
			foreach($a_data['result']['data'] as $key => $val ){
				$sub_index = iconv_substr($val['accountname'],0,1,"UTF-8");
				$a_data['result']['data'][$key]['index_name'] = $sub_index;
			}
		}*/
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

		$this->common->_filename= "Insert_Contacts";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Contacts ==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert Contacts ==>',$a_request,$response_data);
	  
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
		// $DocNo=isset($a_request['contactid']) ? $a_request['contactid'] : "";
		$DocNo=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";

	  		if(count($data[0])>0 and $module=="Contacts"){
	  			
	  			 $a_condition["aicrm_contactdetails.firstname"] = trim($data[0]['firstname']);
	  			 $a_condition["aicrm_contactdetails.lastname"] = trim($data[0]['lastname']);
			      // $a_condition["aicrm_crmentity.smownerid"] = $userid;
			      $a_condition["aicrm_crmentity.deleted"] = "0";

			      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_contactdetails.contactid',"inner");

			       if (!empty($a_condition)) {
	     				$this->db->where($a_condition);
	     			}

 				$query = $this->db->get('aicrm_contactdetails');

 				// echo $this->db->last_query(); exit;
 				
			      if($action=="add"){
					$dataContact=$query->result_array();
					// alert($dataContact);exit;
					if(!empty($dataContact)){
						return array(
							'Type' => 'E',
							'Message' => 'มี contact นี้อยู่ในระบบแล้ว กรุณากดปุ่มตรวจสอบข้อมูลลูกค้า',
							'cache_time' => date("Y-m-d H:i:s"),
							'total' => "0",
							'offset' => "0",
							'limit' =>"1",
							'data'=>array(
						));
					}
				}

					// alert($dataContact);exit;

					list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);

	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				

	  				//Add Account
	  				/*if($action=="add"){
	  					$module_acc = "Accounts";
	  					$acccrmid = '';
	  					$tab_name_acc = array('aicrm_crmentity','aicrm_account','aicrm_accountbillads','aicrm_accountshipads','aicrm_accountscf');
						$tab_name_index_acc = array('aicrm_crmentity'=>'crmid','aicrm_account'=>'accountid','aicrm_accountbillads'=>'accountaddressid','aicrm_accountshipads'=>'accountaddressid','aicrm_accountscf'=>'accountid');

						$data_acc[0]['company_name'] = $data[0]['firstname'].' '.$data[0]['lastname'];
						$data_acc[0]['contact_person'] = $data[0]['firstname'].' '.$data[0]['lastname'];
						$data_acc[0]['contact_tel'] = $data[0]['mobile'];
						$data_acc[0]['email1'] = $data[0]['email'];
						$data_acc[0]['line_id'] = $data[0]['line_id'];
						$data_acc[0]['facebook'] = $data[0]['facebook'];

						$data_acc[0]['status_account'] = "Active";
						$data_acc[0]['register_date'] = date('Y-m-d');
						$data_acc[0]['mobile'] = $data[0]['mobile'];
						$data_acc[0]['accountname'] = $data[0]['firstname'].' '.$data[0]['lastname'];
						
						list($chk_acc,$crmid_acc,$DocNo_acc)=$this->crmentity->Insert_Update($module_acc,$acccrmid,$action,$tab_name_acc,$tab_name_index_acc,$data_acc);
						
						if($chk_acc=="0"){

							$crmentityrel = "INSERT INTO aicrm_crmentityrel(crmid, module, relcrmid, relmodule) VALUES('".$crmid."','Contacts','".$crmid_acc."','Accounts')";
							$this->db->query($crmentityrel);
							
							$a_return["data"] =array(
	  							'Crmid' => $crmid,
	  							'DocNo' => $DocNo,
	  							'CrmidAcc' => $crmid_acc,
	  							'companyname'=> $data_acc[0]['company_name'],
	  							'contact_person'=> $data_acc[0]['contact_person'],
	  							'contact_tel'=> $data_acc[0]['contact_tel'],
	  						);
						}else{
							$a_return["data"] =array(
	  							'Crmid' => $crmid,
	  							'DocNo' => $DocNo,
	  							'CrmidAcc' => '',
	  							'companyname'=> '',
	  							'contact_person'=> '',
	  							'contact_tel'=> '',
	  						);
						}




	  				}else{
	  					$a_return["data"] =array(
	  						'Crmid' => $crmid,
	  						'DocNo' => $DocNo,
	  						'CrmidAcc' => '',
							'companyname'=> ''
	  					);

	  				}*/

	  				$a_return["data"] =array(
						'Crmid' => $crmid,
						'ContactNo' => $DocNo
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


	public function get_contacts_post(){

		$this->common->_filename= "Detail_Contacts";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$this->common->set_log('Before Detail Contacts==>',$url,$a_request);

		$response_data = $this->contacts_model->get_contact($a_request);
		// alert($response_data);exit;

	  	$this->common->set_log('After Detail Contacts==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

}