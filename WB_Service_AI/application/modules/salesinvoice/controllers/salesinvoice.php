<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once('src/OAuth2/Autoloader.php');
require APPPATH . '/libraries/REST_Controller.php';
class Salesinvoice extends REST_Controller
{

    private $crmid;
    private $tab_name = array('aicrm_crmentity','aicrm_salesinvoice','aicrm_salesinvoicecf');
    private $tab_name_index =  array('aicrm_crmentity'=>'crmid','aicrm_salesinvoice'=>'salesinvoiceid','aicrm_salesinvoicecf'=>'salesinvoiceid');
    

	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->library('lib_api_common');
		// $this->load->model("salesinvoice_model");
		$this->_module = "Salesinvoice";
		$this->_format = "array";
		$this->_limit = 0;
		$this->_return = array(
			'Type' => "S",
			'Message' => "Insert Complete",
			'cache_time' => date("Y-m-d H:i:s"),
			'total' => "1",
			'offset' => "0",
			'limit' => "1",
			'data' => array(
				'Crmid' => null,
				'SalesinvoiceNo' => null
			),
		);
		$this->_returnreference = array(
			'Type' => "S",
			'Message' => "Insert Complete",
			'cache_time' => date("Y-m-d H:i:s"),
			'total' => "1",
			'offset' => "0",
			'limit' => "1",
		);

		// $dsn  = 'mysql:dbname=' . $this->config->item('oauth_db_database') . ';host=' . $this->config->item('oauth_db_host');
		// $dbusername = $this->config->item('oauth_db_username');
		// $dbpassword = $this->config->item('oauth_db_password');
		// OAuth2\Autoloader::register();
		
		// // $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
		// $storage = new OAuth2\Storage\Pdo(array(
		// 	'dsn' => $dsn,
		// 	'username' => $dbusername,
		// 	'password' => $dbpassword
		// ));
		// // Pass a storage object or array of storage objects to the OAuth2 server class
		// $this->oauth_server = new OAuth2\Server($storage);
		// // Add the "Client Credentials" grant type (it is the simplest of the grant types)
		// $this->oauth_server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
		// // Add the "Authorization Code" grant type (this is where the oauth magic happens)
		// $this->oauth_server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
	}

	public function get_relate_post()
	{ 
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		// $this->response($a_param, 200); exit();
		$this->common->_filename= "Get_Releted";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	$this->common->set_log('Before Get_Releted==>',$url,$a_param);

		$a_data =$this->get_cache($a_param);

		$this->return_dataRelate($a_data);

	}

	public function return_dataRelate($a_data)
	{ 
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["error"];
			$a_return["total"] = 0;
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			$a_return["data"]["jsonrpc"] = "2.0";
			$a_return["data"]["id"] = "";
			$a_return["data"] = !empty($a_data["data"]) ? $a_data["data"] : [] ;

			$this->common->_filename= "Get_Releted";
	  		header('Content-Type:application/json; charset=UTF-8');
	  		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  		$this->common->set_log('After Get_Releted==>',"response",$a_return);

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

	private function get_cache($a_params=array())
	{
		// get related action = 'relate'
		$this->load->library('managecached_library');

		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		$module = @$a_params["module"];
		$action = @$a_params["action"];
		$userid = @$a_params["userid"];
		$crmid= @$a_params["crmid"];
		//$a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($module)));
		$a_data = $this->managecached_library->get_memcache($a_cache);
		
		if ($module=="Calendar" || $module=="Sales Visit" ||$module=="Sale Visit" ||$module=="Events" ||$module=="Visit") {
			$module = "Calendar";
		}elseif ($module=="Projectorders" || $module=="Project Orders" || $module=="Projectorder" || $module=="Project Order") {
			// $module = "Projectorder";
			$module = "Projects";
		}elseif ($module=="Spare Part" || $module=="SparePart" ) {
			$module = "Sparepart";
		}elseif ($module=="Errors List" || $module=="ErrorsList") {
			$module = "Errorslist";
		}elseif ($module=="Spare Part List" || $module=="SparePartList" || $module=="Spare Part List") {
			$module = "Sparepartlist";
		}else if($module=="Case"){
      		$module = "HelpDesk";
    	}else if($module=="SaleInvoice"){
			$module = "Salesinvoice";
	  	}

		if($action == "relate"){
			
			$a_list = $this->lib_api_common->Get_Relate($module,$crmid);
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");

		}else{
				if($a_data===false)
			{

				$a_list = $this->lib_api_common->Get_Block($module,$action,$crmid);
				$a_cache["data"]["time"] = date("Y-m-d H:i:s");
				$this->managecached_library->set_memcache($a_cache,"2400");
			}

		}

		return $a_list;

	}
}