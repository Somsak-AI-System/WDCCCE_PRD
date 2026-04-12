<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

/**
 * ### Class Social ๏ฟฝ๏ฟฝ๏ฟฝ๏ฟฝ๏ฟฝ๏ฟฝับ๏ฟฝึง API ๏ฟฝอง Social ๏ฟฝ๏ฟฝาง ๏ฟฝ
 */
class Services extends REST_Controller
{
    /**
     * crmid ๏ฟฝ๏ฟฝ๏ฟฝ crmid ๏ฟฝ aicrm_crmentity
     */
    private $crmid;

    function __construct()
    {
        parent::__construct();
        $this->load->library('memcached_library');
        //$this->load->library('crmentity');
        $this->load->database();
        $this->load->library("common");
        $this->load->library('email');
        $this->config->load('email');
        //$this->load->model("knowledgebase_model");
        $this->_limit = 100;
        $this->_module = "Services";
        $this->_format = "array";

        $this->_return = array(
            'Type' => "S",
            'Message' => "Insert Complete",
            'cache_time' => date("Y-m-d H:i:s"),
            'data' => array(),
        );

        $this->_data = array(
            "status" => false,
            "message" => "",
            "time" => date("Y-m-d H:i:s"),
            "data" => array("data" => ""),
        );
    }


    private function get_data($a_params = array())
    {
        $this->load->library('lib_mail_template');
        $a_return = array();
        $method = $a_params["method"];
        if (method_exists($this->lib_mail_template, $method)) {
            $a_return = $this->lib_mail_template->{$method}($a_params);
        }
        return $a_return;
    }

    private function forget_password($a_params = array())
    {
        if (empty($a_params["username"])) {
            $a_data["status"] = false;
            $a_data["message"] = "Username is null";
            return array_merge($this->_data, $a_data);
        }
        $this->load->library('lib_mail_template');
        $a_condition["aicrm_contactscf.cf_2131"] = $a_params["username"];
        $a_contact_data = $this->lib_mail_template->get_contact_data($a_condition);
        if (empty($a_contact_data["result"]["data"][0])) {

            $a_data["status"] = false;
            $a_data["message"] = "Cann't  Found Data";
            return array_merge($this->_data, $a_data);
        }
        $a_contact = $a_contact_data["result"]["data"][0];
        $a_return = $this->lib_mail_template->forget_password($a_contact);
        return $a_return;
    }

    //
    private function check_username($a_params = array())
    {
        $method = "forget_password";
        if (empty($a_params["username"])) {
            $a_data["status"] = false;
            $a_data["message"] = "Username is null";
            return array_merge($this->_data, $a_data);
        }
        if (empty($a_params["passcode"])) {
            $a_data["status"] = false;
            $a_data["message"] = "Passcode is null";
            return array_merge($this->_data, $a_data);
        }
        $this->load->library('lib_mail_template');
        $a_condition["aicrm_contactscf.cf_2131"] = $a_params["username"];
        $a_contact_data = $this->lib_mail_template->get_contact_data($a_condition);
        if (empty($a_contact_data["result"]["data"][0])) {

            $a_data["status"] = false;
            $a_data["message"] = "Cann't  Found Data";
            return array_merge($this->_data, $a_data);
        } else {
            $mail = $this->config->item('mail');
            $a_contact = $a_contact_data["result"]["data"][0];
            $passcode = md5($mail[$method]["encode"] . $a_contact["contactid"]);
            if ($a_params["passcode"] == $passcode) {
                $a_return["data"] = $a_contact;
                $a_data["status"] = true;
                $a_data["message"] = "Passcode Match";
                $a_data["data"] = $a_return;
            } else {
                $a_data["status"] = false;
                $a_data["message"] = "Passcode Not Match!!";
            }
        }
        return array_merge($this->_data, $a_data);
    }

    private function reset_password($a_params = array())
    {
        $method = "reset_password";
        if (empty($a_params["username"])) {
            $a_data["status"] = false;
            $a_data["message"] = "Username is null";
            return array_merge($this->_data, $a_data);
        }
        if (empty($a_params["password"])) {
            $a_data["status"] = false;
            $a_data["message"] = "Password is null";
            return array_merge($this->_data, $a_data);
        }
        if (empty($a_params["contactid"])) {
            $a_data["status"] = false;
            $a_data["message"] = "Contactid is null";
            return array_merge($this->_data, $a_data);
        }
        $this->load->library('lib_mail_template');
        $a_condition["aicrm_contactdetails.contactid"] = $a_params["contactid"];
        $a_contact_data = $this->lib_mail_template->get_contact_data($a_condition);
        //alert($a_contact_data);
        if (empty($a_contact_data["result"]["data"][0])) {

            $a_data["status"] = false;
            $a_data["message"] = "Cann't  Found Data";
            return array_merge($this->_data, $a_data);
        } else {
            $data["cf_2131"] = $a_params["username"];
            $data["cf_2132"] = $a_params["password"];
            $data["cf_2588"] = 0;
            $update_status = $this->db->update('aicrm_contactscf', $data, array('aicrm_contactscf.contactid' => $a_params["contactid"]));
            if ($update_status) {
                $a_contact = $a_contact_data["result"]["data"][0];
                $a_data = $this->lib_mail_template->reset_password($a_contact);
            } else {
                $a_data["status"] = false;
                $a_data["message"] = $this->db->_error_message();
            }
        }
        return array_merge($this->_data, $a_data);
    }

    public function check_forget_password_get()
    {
        $a_param = $this->input->get();
        $a_data = $this->check_username($a_param);
        $this->return_data($a_data);
    }

    /*
     * parameter  username
     * parameter passcode
     */

    public function reset_forget_password_get()
    {
        $a_param = $this->input->get();
        $a_data = $this->forget_password($a_param);
        $this->return_data($a_data);
    }

    public function reset_password_get()
    {
        $a_param = $this->input->get();
        $a_data = $this->reset_password($a_param);
        $this->return_data($a_data);
    }

    public function send_mail_get()
    {
        $a_param = $this->input->get();
        $a_data = $this->get_data($a_param);
        $this->return_data($a_data);
    }

    public function send_mail_post()
    {
        $request_body = file_get_contents('php://input');
        $a_param = json_decode($request_body, true);
		$post = $this->input->post();
		if($this->input->post()){
			$a_param = $this->input->post();
		}
        // alert($a_param); exit;
        $a_data = $this->get_data($a_param);
        $this->return_data($a_data);
    }

    public function return_data($a_data)
    {
        if ($a_data) {
            $format = $this->input->get("format", true);
            $a_return["Type"] = ($a_data["status"]) ? "S" : "E";
            $a_return["Message"] = $a_data["message"];
            $a_return["cachetime"] = @$a_data["time"];
            $a_return["data"] = @$a_data["data"]["data"];
            //alert($a_return["data"]);
            if ($format != "json" && $format != "xml") {
                $this->response($a_return, 200); // 200 being the HTTP response code
            } else {
                $this->response($a_return, 200); // 200 being the HTTP response code
            }
        } else {
            $this->response(array('error' => 'Couldn\'t find any Service Request!'), 404);
        }
    }
	
	 public function get_barcodejpg_post()
		{
			$request_body = file_get_contents('php://input');
			$a_param = json_decode($request_body, true);
			$a_data = $this->GenerateBarcodeJPG($a_param);
			$this->return_data($a_data);
		}

	  function GenerateBarcodeJPG($a_param){
		
		require_once('assets/barcode/src/BarcodeGenerator.php');
		require_once('assets/barcode/src/BarcodeGeneratorPNG.php');
		require_once('assets/barcode/src/BarcodeGeneratorSVG.php');
		require_once('assets/barcode/src/BarcodeGeneratorJPG.php');
		require_once('assets/barcode/src/BarcodeGeneratorHTML.php');
		
		$url_new = $this->config->item('url_new');
		
		$prefix = ($a_param["param1"]) ? $a_param["param1"] : "";
		$taxid = ($a_param["param2"]) ? $a_param["param2"] : "";
		$bankcode = ($a_param["param3"]) ? $a_param["param3"] : "";
		$referenceno1 = ($a_param["param4"]) ? $a_param["param4"] : "";
		$referenceno2 = ($a_param["param5"]) ? $a_param["param5"] : "";
		$amount = ($a_param["param6"]) ? $a_param["param6"] : "";
		
		$path = 'assets/barcode/image/verified-files/';
		$filename = $taxid.$bankcode.$referenceno1.$referenceno2.$amount.".jpg";
				
		$fullpathfile = $path.$filename;
		$generatorJPG = new Picqer\Barcode\BarcodeGeneratorJPG();
		$value = $prefix.$taxid.$bankcode.chr(13).chr(10).$referenceno1.chr(13).chr(10).$referenceno2.chr(13).chr(10).$amount;
		file_put_contents($fullpathfile, $generatorJPG->getBarcode($value, $generatorJPG::TYPE_CODE_128));
		if(file_exists($path.'/'.$filename)){
			//true
			 $data['data']['image'] = $url_new."WB_Service_AI/".$path.$filename;
			
			 $a_data["status"] = true;
			 $a_data["message"] = "";
			 $a_data["data"] = $data;
		}else{
			//false
			 $a_data["status"] = false;
			 $a_data["message"] = "No Image";
			 $a_data["data"] = '';
		}
		
		return array_merge($this->_data, $a_data);
	}

	
	
}