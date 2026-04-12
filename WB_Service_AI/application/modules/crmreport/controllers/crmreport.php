<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Crmreport extends REST_Controller
{

	private $crmid;
	private $tab_name = array('aicrm_users');
	private $tab_name_index = array('aicrm_users' => 'id');
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->config('config_module');
		$this->load->library('lib_api_common');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("crmreport_model");
		$this->_format = "array";
		$this->_return = array(
			'Type' => "S",
			'Message' => "Send email Complete",
			'cache_time' => date("Y-m-d H:i:s"),
		);
	}

	public function send_crmreport_post()
	{
		//header('Content-Type:application/json; charset=UTF-8');
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body, true);
		$a_data = $dataJson;

		$date = date('Y-m-d H:i:s');
		$userid = @$a_data["userid"];
		$crmid = @$a_data["crmid"];
		$module = @$a_data["module"];
		$submodule = @$a_data["submodule"];
		$today = date('d-m-Y');

		$crm_data['data'] = $this->get_data($crmid, $module);

		// alert($crm_data);exit;

		//data log
		$data_log["userid"] = @$a_data["userid"];
		$data_log["sendtime"] = $date;
		$data_log["crmid"] = @$a_data["crmid"];

		// alert($data_log);exit;

		#genpdf file
		if ($submodule == "jobreport") {
			$a_parameter['jobid'] = $crmid;
		} elseif ($submodule == "quotation_report") {
			$a_parameter['quoteid'] = $crmid;
		} elseif ($submodule == "inspectionreport") {
			$a_parameter['crmid'] = $crmid;
		}
		$a_parameter['crm_no'] = $crm_data['data']['crm_no'];

		// alert($a_parameter);exit;
		$a_gen = $this->genpdf($submodule, $a_parameter);

		// alert($a_gen);exit;
		$path = $a_gen["pdf"]["path"];
		$filename = $a_gen["pdf"]["filename"];
		$url_file = $a_gen["pdf"]["url_file"];
		$path_file = $a_gen["pdf"]["path_file"];

		$urlfile = $this->config->item("url_new");
		$url = $urlfile . $path . $filename;

		if ($filename == "") {
			$result_data['type'] = "E";
			$result_data['error'] = "ไม่มีข้อมูล pdf";
			$response_data = $this->get_return($result_data, $action);
		}

		$data_log["path"] = $path;
		$data_log["filename"] = $filename;
		$data_log["parameter_birt"] = $url_file;

		$data_log["status"] = "1";
		$data_log["msg"] = $msg;
		$data_log["mailstatus"] = $status;
		$data_log["mailmsg"] = $mailmsg;

		$crm_data['type'] = "S";
		$crm_data['message'] = "success";
		$crm_data['status'] = "true";
		$crm_data['root_directory'] = $a_gen["pdf"]["root_directory"];
		$crm_data['path'] = $a_gen["pdf"]["path"];
		$crm_data['path_file'] = $url;
		$crm_data['subject'] =  $crm_data['data']['crm_no'] . "_Report_" . $today;;

		$response_data = $this->get_return($crm_data, $action);

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename = "report_CRM";
		$this->common->set_log($url, $crm_data, $response_data);

		if ($response_data) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}
	}


	public function genpdf($submodule = "", $a_param = array())
	{
		$crmid = $a_param['crmid'];
		$crm_no = $a_param['crm_no'];

		$today = date('d-m-Y');
		// alert($submodule);
		// exit;
		global $report_viewer_url_service, $root_directory, $site_URL;

		$config_export = $this->config->item('export');

		$birt_link = $config_export[$submodule]["birt_link"];
		$prefix = $config_export[$submodule]["prefix"];
		$path = $config_export[$submodule]['path'];

		// $param = rawurldecode(http_build_query($a_param));
		$param = rawurldecode(http_build_query($a_param));
		// alert($param);exit;

		if ($submodule == "inspectionreport") {
			// $url_file = 'http://localhost/MOAISTD/report_inspection.php?inspectionid=' . $crmid;
			$url_file = $site_URL."report_inspection.php?inspectionid=" . $crmid;
		} else {
			$url_file = $report_viewer_url_service . $birt_link . "&" . $param;
		}
		// alert($url_file);exit;

		$filename = $crm_no . "_" . $crmid . "_" . date('Y-m-d_his') . ".pdf";
		$pathfile = $root_directory . $path . $filename;
		//echo $url_file; exit;
		$ch = curl_init($url_file);
		// $ch = curl_init("https://moaistd.moai-crm.com:8443/birt-viewer/frameset?__showtitle=false&__report=moai/rpt_job_demo_report.rptdesign&jobid=314&__format=pdf");
		// $ch = curl_init("http://aisystem.moai-crm.com:8080/birt-viewer/frameset?__showtitle=false&__report=moaistd/rpt_quotation_th_temp.rptdesign&quoteid=86&__format=pdf");

		$options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        );

		curl_setopt_array($ch, $options);

		$data = curl_exec($ch);
		curl_close($ch);

		file_put_contents($pathfile, $data);

		$a_return["pdf"]["root_directory"] = $root_directory;
		$a_return["pdf"]["path"] = $path;
		$a_return["pdf"]["filename"] = $filename;
		$a_return["pdf"]["url_file"] = $url_file;
		$a_return["pdf"]["path_file"] = $pathfile;


		return $a_return;
	}


	public function get_return($result_data = "", $action = "")
	{
		if ($result_data['type'] == "S") {
			$a_return["Type"] = "S";
			$a_return["Message"] = $result_data['message'];
			$a_return["Status"] = $result_data['status'];
			$a_return["error"] = "";
			$a_return["root_directory"] = $result_data['root_directory'];
			$a_return["path"] = $result_data['path'];
			$a_return["path_file"] = !empty($result_data['path_file']) ? $result_data['path_file'] : "";
			$a_return["Subject"] = $result_data['subject'];
		} else {
			$a_return["Type"] = "E";
			$a_return["Message"] = "ลองอีกครั้ง";
			$a_return["Status"] = "2";
			$a_return["error"] = $result_data['error'];
		}
		return array_merge($this->_return, $a_return);
	}


	public function get_data($crmid = "", $module = "")
	{
		// alert($module);
		if ($module == 'Job') {
			$sql = "select jobid as crmid, job_no as crm_no, job_name as crm_name  from aicrm_jobs where jobid='" . $crmid . "'";
		} elseif ($module == "Quotes") {
			$sql = "select quoteid as crmid, quote_no as crm_no, quote_name as crm_name from aicrm_quotes where quoteid='" . $crmid . "'";
		} elseif ($module == "Inspection") {
			$sql = "select inspectionid as crmid, inspection_no as crm_no, inspection_name as crm_name from aicrm_inspection where inspectionid='" . $crmid . "'";
		}
		$data = $this->db->query($sql);
		$result = $data->result(0);

		// alert($result);
		// exit;
		$result = $result[0];
		return $result;
	}

	public function sendmail_crmreport_post()
	{
		//header('Content-Type:application/json; charset=UTF-8');
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body, true);
		$a_data = $dataJson;

		$userid = @$a_data["userid"];
		$crmid = @$a_data["crmid"];
		$module = 'Quotes';
		$sendto_email = @$a_data["email"];
		$pathfile = @$a_data["pathfile"];
		$submodule = "quotation_report";
		$date = date('Y-m-d H:i:s');
		$subject = @$a_data["subject"];
		$filename = @$a_data["filename"];
		$emailto = @$a_data["emailto"];
		$emailcc = @$a_data["emailcc"];

		$a_parameter['userid'] = @$a_data["userid"];
		$a_parameter['send_to'] = $sendto_email;

		$sql = "Select aicrm_users.email1 ,aicrm_users.first_name , aicrm_users.last_name  from aicrm_users where id='" . $userid . "'";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		if (!empty($result)) {
			$from_email = $result[0]['email1'];
			$first_name = $result[0]['first_name'];
			$last_name = $result[0]['last_name'];
			$a_parameter['from_email'] = $from_email;
			$a_parameter['from_name'] = $first_name . " " . $last_name;
		}

		$url = $this->config->item('url_new');
		$mailpath = $this->config->item('export');

		$path = $mailpath[$submodule]['path'];
		$prefix = $mailpath[$submodule]['prefix'];
		$birt_link = $mailpath[$submodule]['birt_link'];

		//$filename_path = explode('/', $pathfile);
		//$filename = end($filename_path);

		$q_data['data'] = $this->get_data($crmid, $module);
		
		$string_param = serialize($a_parameter);

		//data log
		$data_log["userid"] = @$a_data["userid"];
		$data_log["username"] = @$a_parameter['from_name'];
		$data_log["sendto"] = @$a_data["email"];
		// $data_log["userid"] = @$a_data["userid"];
		$data_log["sendtime"] = $date;
		$data_log["jobid"] = @$a_data["crmid"];
		$data_log["adduser"] =  @$a_data["userid"];
		$data_log["adddate"] = $date;
		$data_log["parameter"] = $string_param;
		//$data_log["sendto"] = $a_parameter["send_to"];
		$data_log["send_to"] = $emailto;
		$data_log["send_cc"] = $emailcc;
		$data_log["path"] = $path;
		$data_log["filename"] = $filename;

		//#genpdf file
		$a_parameter['jobid'] = $crmid;
		$a_parameter["path"] = $path;
		$a_parameter["filename"] = $filename;
		$a_parameter["url"] = $url;
		$name = $q_data['data']['crm_name'];
		
		$date_start = $q_data['data']['quotation_date'];
		//$due_date = $q_data['data']['quotation_enddate'];

		list($status, $msg, $mailmsg) = $this->sendmail($submodule, $a_parameter, $data_log, $path, $email, $name, $date_start ,$subject);

		$data_log["status"] = "1";
		$data_log["msg"] = $msg;
		$data_log["mailstatus"] = $status;
		$data_log["mailmsg"] = $mailmsg;

		$q_data['type'] = "S";
		$q_data['message'] = "success";
		$q_data['status'] = "true";
		$response_data = $this->get_return($q_data, $action);

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename = "sendmail_Quotes";
		$this->common->set_log($url, $q_data, $response_data);

		if ($response_data) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}
	}

	public  function sendmail($submodule = "quotation_report", $param = array(), $data_log = array(), $path = "", $email = "", $username = "", $date_start = "" ,$subject="")
	{
		
		global $report_viewer_url, $root_directory;
		$a_data = $this->input->post();
		$this->load->library('email');
		$this->config->load('email');
		
		$mail = $config_export = $this->config->item('mail');
		$subject = $subject;
		
		/*if (!empty($param["from_email"])) {
			$from = $param["from_email"];
		} else {*/
			$from = $mail[$submodule]["from"];
		/*}*/

		//$from_name = $param['from_name'];
		$from_name = $mail[$submodule]["from_name"];
		//$to = $param['send_to'];
		$to = $data_log['send_to'];

		$this->email->from(strip_tags($from), strip_tags($from_name));
		$this->email->to($to);

		// cc mail
		//$cc_mail = $this->common->get_user_email($data_log["userid"]);
		$cc_mail = @$data_log['send_cc'];
		// $cc_mail=$this->get_user_email($datsa_log["userid"]);
		$this->email->cc($cc_mail);
		//cc mail

		$filename = $data_log['filename'];
		$path = $data_log['path'];
		//echo $root_directory . $path . $filename; exit;
		$this->email->subject(strip_tags($subject));
		if (file_exists($root_directory . $path . $filename)) {
			$this->email->attach($root_directory . $path . $filename);
		}

		$message = "เรื่อง : " . htmlspecialchars($subject, ENT_QUOTES) . "\r\n<br>";
		$message .= "ข้อมูล Quotation ของ " . $data_log['username'] . "\r\n<br>";
		$message .= "ข้อมูล วันที่ " . date("d/m/Y เวลา  H:i:s", strtotime($data_log["sendtime"])) . "\r\n";

		$this->email->message($message);

		if (!$this->email->send()) {
			$status = false;
			$msg = "Can't send e-mail,Please try again";
			$mailmsg = $this->email->print_debugger();
		} else {
			$status = true;
			$msg = "Send E-mail Complete";
			$mailmsg = $this->email->print_debugger();
		}
		
		return array($status, $msg, $mailmsg);
	}
}
