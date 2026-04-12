<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Admin extends REST_Controller
{    
	private $crmid;
    private $tab_name = array('aicrm_users','aicrm_attachments','aicrm_user2role','aicrm_asteriskextensions');
    private $tab_name_index = array('aicrm_users'=>'id','aicrm_attachments'=>'attachmentsid','aicrm_user2role'=>'userid','aicrm_asteriskextensions'=>'userid');
  
  function __construct()
  {
    parent::__construct();
    $this->load->library('memcached_library');
	$this->load->library('crmentity');
    $this->load->database();
	$this->load->library("common");
	$this->load->model("admin_model");
	$this->_limit = 10;
	$this->_module = "Admin";
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
					'AdminNo' => null
			),
	);
  }

	private function set_param($a_param=array())
	{
		$a_condition = array();
		if (isset($a_param["user"]) && $a_param["user"]!="") {
			$salt = substr($a_param["user"], 0, 2);
			$salt = '$1$' . $salt . '$';
			$encrypted_password = crypt($a_param["pass"], $salt);
			
			$a_condition["aicrm_users.user_name"] =  $a_param["user"] ;
			$a_condition["aicrm_users.user_password"] =  $encrypted_password ;
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

		$a_list=$this->admin_model->get_list($a_condition,$a_order,$a_limit);


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
			if(!empty($a_data["result"]["data"]) && $a_data["status"] ){


				foreach ($a_data["result"]["data"] as $key =>$val){
					$parcelid = $val["id"];
					$a_return = $val;

					$a_response[] = $a_return;
				}
				$a_data["result"]["data"] = $a_response;
			}

			return $a_data;
		}
/**
 *  ## List Content :: Get All Content
 *    | Field                        | Description                                                                                                                                                                |
 *    | ------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------- |
 *    | Description            | Get All Content on Parcel                                                                                                                                     |
 *    | URL                          | http://localhost/sena/WB_Service_AI/users/admin/list_content?AI-API-KEY=1234&module=Admin  |
 *    | Method Type        | Get :: Post                                                                                                                                                                   |
 *
 *
 *  ## Request Parameter
 *  | Name              | Type                 | Description                                                                  					 |  Value  	  | Default Value        | Mandatory	 |
 *  | ------------------- | -------------------   | -------------------------------------------------------------------------------- | -----------	  |-------------------------- |------------------	 |
 *  | format              | String                 | Expected response format                    					                 | json/xml	  | json                          | No          	     |
 *  | offset                | INT                     | Start index result set                                   					             |           	      | 0                                | No       	         |
 *  | limit                   | INT                     | Number of item in result  0 = unlimit      						             |            	      | 10                              | No       	         |
 *  | AI-API-KEY    | INT                     | Secret key                                                     						             |           	      |                                    | Yes     	         |
 *  | orderby           | String                 | Order by ascending or descending  									 |          	      |                      			   | No      	         |
 *  | crmid		         | String                 | parcelid																							 |          	      |                      			   | No      	         |
 *
 *  ## Return Result
 *  | Name              | Type                 | Description                                                                  					 |  Value  	 																 |
 *  | ------------------- | -------------------   | -------------------------------------------------------------------------------- | ----------------------------------------------------------	 |
 *  | Type              	 | String                 | Return status							                    					                 | S/Success :: E/Error								       	     |
 *  | Message       | String                 | Error Message				                                  					             |     																      	         |
 *  | Total                | INT                     | Result  Total Data								    						             |   																      	         |
 *  | offset				 | INT                     | Start index result set                                   						             |           	   													     	         |
 *  | limit			         | INT		               | Number of item in result  0 = unlimit 	  									 |  																	   	         |
 *	 | data		         | Array		               | Result Data 									 |  																	   	         |
 *
 * ## Example Parameter
 * URL :: http://localhost/sena/WB_Service_AI/Parcel/list_content?AI-API-KEY=1234&module=Admin&user=user&pass=password
 *
 *  ## Example Return Success
 *  ~~~~~~~~~~~~~{.py}
 *   [Type] => S
 *   [Message] => 
 *   [total] => 1
 *   [offset] => 0
 *   [limit] => 1000
 *   [cachetime] => 2015-12-20 17:16:54
 *   [data] => Array
 *       (
 *            [0] => Array
 *               (
 *                   [id] => 11050
 *                   [user_name] => tor
 *                   [user_password] => $1$to$TptnxyHvjzL6Bg83tc/SS0
 *                   [user_hash] => 4d3bf5592690f5e2af0e6e7c4615af5a
 *                   [cal_color] => #E6FAD8
 *                   [first_name] => Sanina
 *                   [last_name] => Admin
 *                   [reports_to_id] => 
 *                   [is_admin] => on
 *                   [currency_id] => 1
 *                   [description] => 
 *                   [date_entered] => 2015-09-14 18:56:38
 *                   [date_modified] => 0000-00-00 00:00:00
 *                   [modified_user_id] => 
 *                   [title] => 
 *                   [department] => 
 *                   [phone_home] => 
 *                   [phone_mobile] => 
 *                   [phone_work] => 
 *                   [phone_other] => 
 *                   [phone_fax] => 
 *                   [email1] => tanate@aisyst.com
 *                   [email2] => 
 *                   [yahoo_id] => 
 *                   [status] => Active
 *                   [signature] => 
 *                   [address_street] => 
 *                   [address_city] => 
 *                   [address_state] => 
 *                   [address_country] => 
 *                   [address_postalcode] => 
 *                   [user_preferences] => 
 *                   [tz] => 
 *                   [holidays] => 
 *                   [namedays] => 
 *                   [workdays] => 
 *                   [weekstart] => 
 *                   [date_format] => dd-mm-yyyy
 *                   [hour_format] => am/pm
 *                   [start_hour] => 08:00
 *                   [end_hour] => 
 *                   [activity_view] => This Week
 *                   [lead_view] => Today
 *                   [imagename] => 
 *                   [deleted] => 0
 *                   [confirm_password] => $1$to$TptnxyHvjzL6Bg83tc/SS0
 *                   [internal_mailer] => 1
 *                   [reminder_interval] => 1 Minute
 *                   [reminder_next_time] => 2015-12-16 15:57
 *                   [crypt_type] => MD5
 *                   [accesskey] => s2AoKM1a875dKnYx
 *                   [cf_1059] => 77581
 *                   [cf_1739] => 0
 *                   [cf_2034] => The Niche ตากสินกส.100%
 *                   [pm_flag] => 
 *                   [wecare_flag] => 
 *                   [userid] => 11050
 *                   [roleid] => H2
 *               )
 *
 *       )
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
			$a_return["data"] = !empty($a_data["data"]["data"]) ? $a_data["data"]["data"] : "" ;
			if ($format!="json" && $format!="xml"  ) {
					$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
				$this->response(array('error' => 'Couldn\'t find any Parcel!'), 404);
		}
	}
}