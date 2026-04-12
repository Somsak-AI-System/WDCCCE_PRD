<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Jobreport extends REST_Controller
{

	private $crmid;
	private $tab_name = array('aicrm_users');
	private $tab_name_index = array('aicrm_users'=>'id');
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->config('config_module');
		$this->load->library('lib_api_common');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("jobreport_model");
		$this->_format = "array";
		$this->_return = array(
			'Type' => "S",
			'Message' => "Send email Complete",
			'cache_time' => date("Y-m-d H:i:s"),
		);
	}

	public function send_jobreport_post(){
		//header('Content-Type:application/json; charset=UTF-8');
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
		$a_data = $dataJson;
		// alert($a_data);exit;

		$date = date('Y-m-d H:i:s');
		$userid = @$a_data["userid"];
		$crmid = @$a_data["crmid"];
		$module = @$a_data["module"];

		$job_data['data'] = $this->get_data($crmid,$module);
		// alert($job_data);exit;

		//data log
		$data_log["userid"] = @$a_data["userid"];
		$data_log["sendtime"] = $date;
		$data_log["jobid"] = @$a_data["crmid"];

		// alert($job_data);exit;

		#genpdf file
		$a_parameter['jobid'] = $crmid;
		$submodule="jobreport";
		$a_gen = $this->genpdf($submodule,$a_parameter);

		$path = $a_gen["pdf"]["path"];
		$filename = $a_gen["pdf"]["filename"];
		$url_file = $a_gen["pdf"]["url_file"];
		$path_file = $a_gen["pdf"]["path_file"];

		$urlfile = $this->config->item("url_new");
		$url = $urlfile.$path.$filename;

		if($filename==""){
			$result_data['type'] = "E";
			$result_data['error'] = "ไม่มีข้อมูล pdf";
			$response_data = $this->get_return($result_data,$action);

		}

		$data_log["path"] = $path;
		$data_log["filename"] = $filename;
		$data_log["parameter_birt"] = $url_file;

		$data_log["status"] = "1";
		$data_log["msg"] = $msg;
		$data_log["mailstatus"] = $status;
		$data_log["mailmsg"] = $mailmsg;

		$job_data['type']="S";
		$job_data['message']="success";
		$job_data['status']="true";
		$job_data['path_file']=$url;
		$response_data = $this->get_return($job_data,$action);

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= "report_Job";
		$this->common->set_log($url,$job_data,$response_data);
		//
		// }

		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}
	}


	public function genpdf($submodule="jobreport",$a_param=array())
	{
		$crmid = $a_param['crmid'];

		global $report_viewer_url_service, $root_directory;

		$config_export = $this->config->item('export');

		$birt_link = $config_export[$submodule]["birt_link"];
		$prefix = $config_export[$submodule]["prefix"];
		$path = $config_export[$submodule]['path'];

		// $param = rawurldecode(http_build_query($a_param));
		$param = rawurldecode(http_build_query($a_param));
		// alert($param);exit;

		$url_file=$report_viewer_url_service.$birt_link."&".$param;
		// $url_file=$report_viewer_url_service.$birt_link;

		$filename = $prefix."_".$crmid."_".date('Y-m-d_his').".pdf";
		$pathfile = $root_directory.$path.$filename;

		$ch = curl_init($url_file);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$data = curl_exec($ch);
		curl_close($ch);

		file_put_contents($pathfile, $data);


		// $ch = curl_init($url_file_excel);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// $data = curl_exec($ch);
		// curl_close($ch);
		//
		// file_put_contents($pathfile_excel, $data);
		//export excel
		$a_return["pdf"]["path"] = $path;
		$a_return["pdf"]["filename"] = $filename;
		$a_return["pdf"]["url_file"] = $url_file;
		$a_return["pdf"]["path_file"] = $pathfile;


		return $a_return;
	}


	public function get_return($result_data="",$action=""){
		if($result_data['type']=="S"){
			$a_return["Type"] = "S";
			$a_return["Message"] = $result_data['message'];
			$a_return["Status"] = $result_data['status'];
			$a_return["error"] = "";
			$a_return["path_file"] = !empty($result_data['path_file']) ? $result_data['path_file'] : "" ;
		}else{
			$a_return["Type"] ="E";
			$a_return["Message"] ="ลองอีกครั้ง";
			$a_return["Status"] = "2";
			$a_return["error"] = $result_data['error'];

		}
		return array_merge($this->_return,$a_return);
	}


	public function get_data($crmid="",$module=""){


		$sql="select * from aicrm_jobs where jobid='".$crmid."'";
		$data = $this->db->query($sql);
		$result = $data->result(0);

		$a_conditionin["aicrm_jobs.jobid"] = $crmid;

		$a_image = $this->common->get_a_image($a_conditionin,$module);

		if($a_image[$crmid]['image']!=""){
			$result[0]['image'] = $a_image[$crmid]['image'];
		}else {
			$result[0]['image']=[];
		}


		if($result[0]['image_customer']!=""){
			$uitype='998';
			$sql_customer = "select aicrm_attachments.path, aicrm_attachments.attachmentsid, aicrm_attachments.name , aicrm_crmentity.setype,aicrm_jobs.image_user,aicrm_jobs.image_customer
			from aicrm_jobs
			left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_jobs.jobid
			inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
			where aicrm_crmentity.setype='Image ".$uitype."'
			and aicrm_jobs.jobid='".$crmid."' ";

			$query = $this->db->query($sql_customer);
			$data_image = $query->result(0);
			// $image_user=$data_image[0];
			$path = $data_image[0]['path'];
			$name = $data_image[0]['name'];
			$value = $data_image[0]['attachmentsid'];

			$urlfile = $this->config->item("url_new");
			$image_user =$urlfile."".$path."".$name."";
			$result[0]['image_customer']=$image_customer;
			// $image_customer ="http://aisystem.dyndns.biz:8090/moai/".$path."".$name."";
			// $result[0]['image_customer']=$image_customer;



			// alert($image_user);
		}else {
			$uitype='';
		}

		if ($result[0]['image_user']!="") {
			$uitype='999';
			$sql_user = "select aicrm_attachments.path, aicrm_attachments.attachmentsid, aicrm_attachments.name , aicrm_crmentity.setype,aicrm_jobs.image_user,aicrm_jobs.image_customer
			from aicrm_jobs
			left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_jobs.jobid
			inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
			inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
			where aicrm_crmentity.setype='Image ".$uitype."'
			and aicrm_jobs.jobid='".$crmid."' ";
			$query = $this->db->query($sql_user);
			$data_image = $query->result(0);
			$image_user=$data_image[0];
			// alert($image_user);exit;
			$path = $data_image[0]['path'];
			$name = $data_image[0]['name'];
			$value = $data_image[0]['attachmentsid'];
			// alert($value);exit;


			$urlfile = $this->config->item("url_new");

			$image_user =$urlfile.$path.$name;
			$result[0]['image_user']=$image_user;
			// alert($image_customer);


		}else {
			$uitype='';
		}

		// alert($result);exit;
		$result = $result[0];
		return $result;
	}


	public function sendmail_jobreport_post(){
		//header('Content-Type:application/json; charset=UTF-8');
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
		$a_data = $dataJson;

		$userid = @$a_data["userid"];
		$crmid = @$a_data["crmid"];
		$module = @$a_data["module"];
		$sendto_email = @$a_data["email"];
		$pathfile = @$a_data["pathfile"];
		$submodule="jobreport";
		$date = date('Y-m-d H:i:s');



		$a_parameter['userid'] = @$a_data["userid"];
		$a_parameter['send_to'] = $sendto_email;

		$sql = "Select aicrm_users.email1 ,aicrm_users.first_name , aicrm_users.last_name  from aicrm_users where id='".$userid."'";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		if(!empty($result)){
			$from_email = $result[0]['email1'];
			$first_name = $result[0]['first_name'];
			$last_name = $result[0]['last_name'];
			$a_parameter['from_email'] = $from_email;
			$a_parameter['from_name'] = $first_name." ".$last_name;
		}

		$url = $this->config->item('url_new');
		$mailpath = $this->config->item('export');

		$path = $mailpath[$submodule]['path'];
		$prefix = $mailpath[$submodule]['prefix'];
		$birt_link = $mailpath[$submodule]['birt_link'];

		$filename_path = explode( '/', $pathfile );
		$filename = end($filename_path);

		$job_data['data'] = $this->get_data($crmid,$module);

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
		$data_log["sendto"] =$a_parameter["send_to"] ;
		$data_log["path"] = $path ;
		$data_log["filename"] =$filename ;

		//#genpdf file
		$a_parameter['jobid'] = $crmid;
		$a_parameter["path"] = $path ;
		$a_parameter["filename"] =$filename ;
		$a_parameter["url"] =$url ;
		$name = $job_data['data']['job_name'];
		$date_start = $job_data['data']['jobdate'];
		$due_date = $job_data['data']['close_date'];

		list($status,$msg,$mailmsg) = $this->sendmail($submodule,$a_parameter,$data_log,$path,$email,$name,$date_start,$due_date);

		$data_log["status"] = "1";
		$data_log["msg"] = $msg;
		$data_log["mailstatus"] = $status;
		$data_log["mailmsg"] = $mailmsg;

		$job_data['type']="S";
		$job_data['message']="success";
		$job_data['status']="true";
		$response_data = $this->get_return($job_data,$action);

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= "sendmail_Job";
		$this->common->set_log($url,$job_data,$response_data);

		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}
	}



	public  function sendmail($submodule="jobreport",$param=array(),$data_log=array(),$path="",$email="",$username="",$date_start="",$due_date="")
	{
		global $report_viewer_url, $root_directory;
		$a_data = $this->input->post();
		$this->load->library('email');
		$this->config->load('email');

		$mail = $config_export = $this->config->item('mail');

		$subject = $mail[$submodule]["subject"];
		$subject .= " ของ ".$data_log['username'];
		$subject .= " วันที่สร้าง job ".$date_start;
		$subject .= " วันที่ปิด Case ".$due_date;  // MOAICRM :: Job Report ของ  วันที่แจ้ง 2019-05-29 วันที่ปิด Case 000

		if(!empty($param["from_email"])){
			$from = $param["from_email"];
		}else{
			$from = $mail[$submodule]["from"];
		}

		$from_name = $param['from_name'];
		$to = $param['send_to'];

		$this->email->from( strip_tags($from),strip_tags($from_name));
		$this->email->to($to);

		// cc mail
		$cc_mail=$this->common->get_user_email($data_log["userid"]);
		// $cc_mail=$this->get_user_email($datsa_log["userid"]);
		$this->email->cc($cc_mail);
		//cc mail

		$filename = $data_log['filename'];
		$path = $data_log['path'];

		$this->email->subject(strip_tags($subject));
		if (file_exists($root_directory.$path.$filename)) {
			$this->email->attach($root_directory.$path.$filename);
		}

		$message = "เรื่อง : ".htmlspecialchars($subject,ENT_QUOTES)."\r\n<br>";
		$message .= "ข้อมูล job ของ ".$data_log['username']."\r\n<br>";
		$message .= "ข้อมูล วันที่ ".date("d/m/Y เวลา  H:i:s",strtotime($data_log["sendtime"]))."\r\n";

		$this->email->message($message);
		if ( ! $this->email->send()){
			$status = false;
			$msg = "Can't send e-mail,Please try again";
			$mailmsg=$this->email->print_debugger();
		}else{
			$status = true;
			$msg = "Send E-mail Complete";
			$mailmsg=$this->email->print_debugger();
		}

		return array($status,$msg,$mailmsg);
	}


}
