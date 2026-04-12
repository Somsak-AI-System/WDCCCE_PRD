<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Salesreport extends REST_Controller
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
		$this->load->model("salesreport_model");
		$this->_format = "array";
		$this->_return = array(
				'Type' => "S",
				'Message' => "Send email Complete",
				'cache_time' => date("Y-m-d H:i:s"),
		);
	}

	public function send_email_post(){
		//header('Content-Type:application/json; charset=UTF-8');
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
    	$a_data = $dataJson;

    	//$submodule ="weeklyplan";
    	$date = date('Y-m-d H:i:s');
		$userid = $a_data["userid"];
		$action = @$a_data["action"];
    	//if($a_data["report_type"]=="weeklyplan"){
		//$a_parameter['report_type'] = @$a_data["report_type"];
		//}

		$a_parameter['userid'] = @$a_data["userid"];
		// $a_parameter['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-',$a_data["date_start"])));
		$a_parameter['date_start'] = date('Y-m-d', strtotime(str_replace('-', '/',$a_data["date_start"])));
		$date_start_replace = str_replace('-', '/',$a_parameter["date_start"]);
		$a_parameter['due_date'] =  date('Y-m-d', strtotime(str_replace('-', '/',$a_data["due_date"])));
		$due_date_replace = str_replace('-', '/',$a_parameter["due_date"]);

		// $a_parameter['area'] = @$a_data["area"];
		// $a_parameter['accountid'] = @$a_data["accountid"];
		// $a_parameter['department'] = @$a_data["department"];
	    // $a_parameter['objective'] = @$a_data["objective"];
	    // $a_parameter['send_to'] =  implode(",", $a_data["send_to"]["id"]);
		// alert($a_parameter['date_start']);
		// alert($a_parameter['due_date']);exit;

		$a_parameter['accountid'] = "";
		//$a_parameter['description'] = @$a_data["description"];
		// $a_parameter['username'] = @$a_data["username"];
		$a_parameter['objective'] = "";
		$a_parameter['year'] = @$a_data["year"];
		// $a_parameter['report_plan'] = @$a_data["report_plan"];
		// $a_parameter['report_type'] = @$a_data["report_type"];
		// $a_parameter['submodule'] = @$submodule;
		// $a_parameter['submodule'] = @$a_data["submodule"];
		$a_parameter['description'] = "";
		$submodule = @$a_data["submodule"];
		$a_parameter['report_type'] = @$a_data["report_type"];

		if ($a_parameter['report_type']=='Weekly Plan') {
			$submodule = 'weeklyreport';
		}elseif ($a_parameter['report_type']=='Monthly Plan') {
			$submodule ='monthlyplan';
		}
		// alert($a_parameter);exit;

		// if($submodule=="weeklyreport" &&	$a_parameter['report_type']==""){
		// 		$a_parameter['report_type'] = "weeklyreport";
		// }

		$date_start = $date_start_replace;
		$due_date = $due_date_replace;

	    foreach ( $a_data["send_to"] as $key => $value) {
	      if ($key==0){
	        $a_parameter['send_to'] .= $value;
	      }else {
	        $a_parameter['send_to'] .= ",".$value;
	      }
	    }


	    $sql = "Select aicrm_users.first_name, aicrm_users.last_name,aicrm_users.email1 as email, aicrm_users.area, aicrm_users.position from aicrm_users where id='".$userid."'";
	    $query = $this->db->query($sql);
	    $result = $query->result_array();
	    $name = $result[0]['first_name']." ".$result[0]['last_name'];
		$a_parameter['username'] = $name;
		$a_parameter['email'] = $result[0]['email'];
		$a_parameter['area'] = $result[0]['area'];
		$a_parameter['department'] = $result[0]['position'];


	    $insert_tbm = $this->salesreport_model->insert_update_send_report($a_parameter);

	    $string_param = serialize($a_parameter);

		//data log
		$data_log["userid"] = @$a_data["userid"];
		// $data_log["reporttype"] = $submodule=="weeklyreport" ? "weeklyreport" :@$a_data["report_type"];
		$data_log["sendtime"] = $date;
		$data_log["adduser"] = $a_data["userid"];
		$data_log["adddate"] = $date;
		$data_log["parameter"] = $string_param;
		$data_log["sendto"] =$a_parameter["send_to"] ;

   	 	if($submodule=="weeklyreport"){

			if(empty($a_data) || $a_parameter['userid']==""  || $insert_tbm == false )
			{
				$a_reponse["status"] = false;
				$a_reponse["error"] = "ไม่มีข้อมูล";
				$a_reponse["msg"] = "ลองอีกครั้ง" ;
				$a_reponse["result"] = "";
				$data_log["status"] = '2';
				$data_log["msg"] = 'No Data';
				$this->db->insert('tbt_salesvisit_log', $data_log);
		        $a_return["Type"] ="E";
		        $a_return["Message"] ="ไม่มีข้อมูล";
		        array_merge($this->_return,$a_return);
      		}

		}else{

			if(empty($a_data) || $a_parameter['userid']=="" || $a_parameter['report_type']=="" || $insert_tbm == false )
			{
				$a_reponse["status"] = false;
				$a_reponse["error"] = "ไม่มีข้อมูล";
				$a_reponse["msg"] = "ลองอีกครั้ง" ;
				$a_reponse["result"] = "";
				$data_log["status"] = '2';
				$data_log["msg"] = 'No Data';
				$this->db->insert('tbt_salesvisit_log', $data_log);

		        $result_data['type'] = "E";
		        $result_data['error'] = "ไม่มีข้อมูล";
		        $response_data = $this->get_return($result_data,$action);
        	}

		}

		//#genpdf file
		$a_gen = $this->genpdf($submodule,$a_parameter);

		$path = $a_gen["pdf"]["path"];
		$filename = $a_gen["pdf"]["filename"];
		$url_file = $a_gen["pdf"]["url_file"];

		$path_excel = $a_gen["excel"]["path"];
		$filename_excel = $a_gen["excel"]["filename"];
		$url_file_excel = $a_gen["excel"]["url_file"];
		if($filename==""){
	      $result_data['type'] = "E";
	      $result_data['error'] = "ไม่มีข้อมูล pdf";
	      $response_data = $this->get_return($result_data,$action);
    	}


    	$data_log["path"] = $path;
		$data_log["filename"] = $filename;
		$data_log["parameter_birt"] = $url_file;
		// alert($a_parameter);exit;

    	#send mail
		list($status,$msg,$mailmsg) = $this->sendmail($submodule,$a_parameter,$data_log,$path.$filename,$path_excel.$filename_excel,$name,$date_start,$due_date);
		$data_log["status"] = "1";
		$data_log["msg"] = $msg;
		$data_log["mailstatus"] = $status;
		$data_log["mailmsg"] = $mailmsg;

	    $result_data['type'] = "S";
	    $result_data['error'] = "";
	    $result_data['message'] = $msg;
	    $result_data['status'] = $status;
	    $response_data = $this->get_return($result_data,$action);

		$this->db->insert('tbt_salesvisit_log', $data_log);
		if($data_log['reporttype']=="Weekly Plan"){

			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->common->_filename= "Sendmail_weeklyplan";
			$this->common->set_log($url,$a_parameter,$response_data);

		}elseif ($data_log['reporttype']=="Monthly Plan") {

			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->common->_filename= "Sendmail_monthlyplan";
			$this->common->set_log($url,$a_parameter,$response_data);

		}elseif ($data_log['reporttype']=="weeklyreport") {

			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->common->_filename= "Sendmail_weeklyreport";
			$this->common->set_log($url,$a_parameter,$response_data);

		}

		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
					'error' => 'Couldn\'t find Set Content!'
			), 404);
		}
	}

  public  function sendmail($submodule="weeklyplan",$param=array(),$data_log=array(),$filename="",$filename_excel="",$name="",$date_start="",$due_date="")
	{
		global $report_viewer_url, $root_directory;
		$a_data = $this->input->post();
		$this->load->library('email');
		$this->config->load('email');
		$mail = $this->config->item('mail');

		$from = $mail[$submodule]["from"];
		$from_name = $mail[$submodule]["from_name"];

		$subject = $mail[$submodule]["subject"];
		$subject .= " ของ ".$name;
		$subject .= " ช่วงวันที่ ".$date_start;
		$subject .= " ถึง วันที่ ".$due_date;
		
		if(isset($param['username']) && $param['username'] !="" ){
			$from_name = $param['username'];
		}


		if(!empty($param["send_to"])){
			$sql ="SELECT GROUP_CONCAT(email1 )as email1
					FROM aicrm_users
					where  FIND_IN_SET (id, '".$param["send_to"]."');";
			$a_data = $this->db->query($sql);
			$result = $a_data->result_array();

			//alert($result);exit;

			if(!empty($result)){
				$to = $result[0]["email1"];
			}else{
				$to = $mail[$submodule]["to"];
			}

		}else{
			$to = $mail[$submodule]["to"];
		}
		//echo $to; exit;
		
		$this->email->from( strip_tags($from),strip_tags($from_name));
		$this->email->to($to);

		// cc mail
		$cc_mail=$this->common->get_user_email($data_log["userid"]);
		// $cc_mail=$this->get_user_email($datsa_log["userid"]);
		$this->email->cc($cc_mail);
		//cc mail

		$this->email->subject(strip_tags($subject));
		if (file_exists($root_directory.$filename)) {
			$this->email->attach($root_directory.$filename);
		}

		if (file_exists($root_directory.$filename_excel)) {
			$this->email->attach($root_directory.$filename_excel);
		}

		$message = "เรื่อง : ".htmlspecialchars($subject,ENT_QUOTES)."\r\n<br>";
		$message .= "ข้อมูล ช่วงวันที่ ".date("d/m/Y ",strtotime($param["date_start"]));
		if($param["due_date"]!=""){
			$message .= " ถึง วันที่ ".date("d/m/Y ",strtotime($param["due_date"]));
		}
		$message .="\r\n<br>";
		$message .= "ข้อมูล ของ ".$param['username']."\r\n<br>";
		$message .= "ข้อมูล วันที่ ".date("d/m/Y เวลา  H:i:s",strtotime($data_log["sendtime"]))."\r\n";

		$this->email->set_newline("\r\n");
		$this->email->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

  		$this->email->message($message);
  		// alert($this->email); exit;
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


  public function genpdf($submodule="weeklyplan",$a_param=array())
	{
		ini_set('memory_limit', '256M');
    $userid = $a_param['userid'];

		global $report_viewer_url_service, $root_directory;
		$config_export = $this->config->item('export');
// echo $submodule; exit;
		$birt_link = $config_export[$submodule]["birt_link"];
		$prefix = $config_export[$submodule]["prefix"];
		$path = $config_export[$submodule]['path'];

		$param = rawurldecode(http_build_query($a_param));

		$url_file=$report_viewer_url_service.$birt_link."&".$param;
		// echo $url_file; exit;
		// $url_file=$report_viewer_url_service.$birt_link;
				// alert($url_file);exit;
		$filename = $prefix."_".$userid."_".date('Y-m-d_his').".pdf";
		$pathfile = $root_directory.$path.$filename;
		// echo $pathfile; exit;
		// alert($filename);
		// 	alert($pathfile);exit;


		$ch = curl_init($url_file);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //ssl
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //ssl
		$data = curl_exec($ch);

		curl_close($ch);

		file_put_contents($pathfile, $data);


		//export excel
		$birt_link_excel = $config_export[$submodule]["birt_link_excel"];
		$url_file_excel=$report_viewer_url_service.$birt_link_excel."&".$param;

		$filename_excel = $prefix."_".$userid."_".date('Y-m-d_his').".xls";
		$pathfile_excel = $root_directory.$path.$filename_excel;

		$ch = curl_init($url_file_excel);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //ssl
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //ssl

		$data = curl_exec($ch);
		curl_close($ch);

		file_put_contents($pathfile_excel, $data);
		//export excel
		$a_return["pdf"]["path"] = $path;
		$a_return["pdf"]["filename"] = $filename;
		$a_return["pdf"]["url_file"] = $url_file;

		$a_return["excel"]["path"] = $path;
		$a_return["excel"]["filename"] = $filename_excel;
		$a_return["excel"]["url_file"] = $url_file_excel;

		return $a_return;
	}



	public function get_return($result_data="",$action=""){
				 if($result_data['type']=="S"){
           $a_return["Type"] = "S";
           $a_return["Message"] = $result_data['message'];
           $a_return["Status"] = $result_data['status'];
           $a_return["error"] = "";
					 $a_return["result"] = "";
				 }else{
           $a_return["Type"] ="E";
           $a_return["Message"] ="ลองอีกครั้ง";
           $a_return["Status"] = "2";
           $a_return["error"] = $result_data['error'];

				 }
				 return array_merge($this->_return,$a_return);
	}

    public function Getweeklyplan_post()
  	{
  		// $request_body = file_get_contents('php://input');
  		// $dataJson     = json_decode($request_body,true);
  	   $year =date('Y');
  	   $year1 =date('Y')-1;
  	   $year2 =date('Y')+1;
  		$sql = "SELECT `weekly_id` , `weekly_no` , `weekly_start_date` , `weekly_end_date` , `weekly_year`
  					FROM aicrm_activity_tran_config_weekly_plan
  					WHERE `weekly_year` in ('".$year."','".$year1."','".$year2."') order by weekly_year Desc ,weekly_id ASC";
				// alert($sql);exit;
  		$query = $this->db->query($sql);
  		$a_response =  $query->result_array();
  		if($a_response!=null){

  			$response_data['Type']='S';
  			$response_data['Message']='Success';
  			$response_data['cache_time']=date("Y-m-d H:i:s");
  			$response_data['total']=count($a_response);
  			$response_data['data']=$a_response;

  		}else{
  			$response_data['Type']='E';
  			$response_data['Message']='No Data';
  			$response_data['cache_time']=date("Y-m-d H:i:s");
  			$response_data['data']="";

  		}

  		// alert($a_response);exit;
  		if ( $response_data ) {
  			$this->response($response_data, 200); // 200 being the HTTP response code
  		} else {
  			$this->response(array(
  					'error' => 'Couldn\'t find Set Content!'
  			), 404);
  		}

  	}


    public function Getmonthlyplan_post()
  {
    // $request_body = file_get_contents('php://input');
    // $dataJson
    $year =date('Y');
    $year1 =date('Y')-1;
    $year2 =date('Y')+1;

     $sql = "SELECT `monthly_id` , `monthly_no` , `monthly_start_date` , `monthly_end_date` , `monthly_year`
           FROM aicrm_activity_tran_config_monthly_plan
           WHERE `monthly_year` in ('".$year."','".$year1."','".$year2."') order by monthly_year Desc  ,monthly_id  ASC";

     $query = $this->db->query($sql);
     $a_response =  $query->result_array();

    if($a_response!=null){

      $response_data['Type']='S';
      $response_data['Message']='Success';
      $response_data['cache_time']=date("Y-m-d H:i:s");
      $response_data['total']=count($a_response);
      $response_data['data']=$a_response;

    }else{
      $response_data['Type']='E';
      $response_data['Message']='No Data';
      $response_data['cache_time']=date("Y-m-d H:i:s");
      $response_data['data']="";

    }

    if ( $response_data ) {
      $this->response($response_data, 200); // 200 being the HTTP response code
    } else {
      $this->response(array(
          'error' => 'Couldn\'t find Set Content!'
      ), 404);
    }

  }


  public function userlist_post(){

  		//header('Content-Type:application/json; charset=UTF-8');
  		$request_body = file_get_contents('php://input');
  		$dataJson     = json_decode($request_body,true);

  		$a_request =$dataJson;
  		$userid = $a_request['userid'];
  		$action = $a_request['action'];

  		if($userid!=null){
  			$sql = "Select aicrm_users.user_name ,aicrm_users.is_admin ,aicrm_user2role.roleid,section,aicrm_role.rolename,aicrm_role.parentrole
  			from aicrm_users
  			inner join aicrm_user2role on aicrm_user2role.userid=aicrm_users.id
  			inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid
  			where aicrm_users.id = '".$userid."'";
  			$query = $this->db->query($sql);
  			$a_return  = $query->result_array() ;

  			$is_admin = $a_return[0]['is_admin'];
  			$roleid = $a_return[0]['roleid'];
  			$section = $a_return[0]['section'];
  			$rolename = $a_return[0]['rolename'];
  			$parentrole = $a_return[0]['parentrole'];

  			if($is_admin=="on"){

  				// $sql_user = "select id as id,user_name as user_name, first_name , last_name
  				// , CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name'
  				// , IFNULL(area,'') as area
  				// , case when section	= '--None--' then '' else section end as section
  				// from aicrm_users
  				// where aicrm_users.cf_4344 = '1'  and deleted = '0' and section = '--None--' and status='Active'
  				// order by user_name ASC";
          // alert($sql_user);exit;
					$sql_user = "select id as id
						,user_name as user_name
						, first_name , last_name
						, CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name'
						, IFNULL(area,'') as area
							 , case when section	= '--None--' then '' else section end as section
					from aicrm_users
					where
					status='Active'
						order by user_name";
  				$query = $this->db->query($sql_user);
  				$a_data  = $query->result_array() ;

  			}else{

  				// $sql_user = "select id as id,user_name as user_name, first_name , last_name
  				// , CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name'
  				// , IFNULL(area,'') as area
  				// , case when section	= '--None--' then '' else section end as section
  				// from aicrm_users
  				// where aicrm_users.cf_4344 = '1'  and deleted = '0' and section = '--None--' and status='Active'
  				// order by user_name";
					$sql_user = "select id as id
						,user_name as user_name
						, first_name , last_name
						, CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name'
						, IFNULL(area,'') as area
					     , case when section	= '--None--' then '' else section end as section
					from aicrm_users
					where
					status='Active'
					and section = '".$section."'
				    order by user_name";
  				$query = $this->db->query($sql_user);
  				$a_data  = $query->result_array() ;

  			}

  		}

      if($a_data!=null){

        $response_data['Type']='S';
        $response_data['Message']='Success';
        $response_data['cache_time']=date("Y-m-d H:i:s");
        $response_data['total']=count($a_data);
        $response_data['data']=$a_data;

      }else{
        $response_data['Type']='E';
        $response_data['Message']='No Data';
        $response_data['cache_time']=date("Y-m-d H:i:s");
        $response_data['data']="";

      }

      if ( $response_data ) {
        $this->response($response_data, 200); // 200 being the HTTP response code
      } else {
        $this->response(array(
            'error' => 'Couldn\'t find Set Content!'
        ), 404);
      }

  }


}
