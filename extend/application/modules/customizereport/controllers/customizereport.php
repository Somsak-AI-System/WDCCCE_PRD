<?php

class Customizereport extends MY_Controller {

	private $description, $title, $keyword, $screen, $modulename;

	public function __construct() {

		parent::__construct();
		$meta = $this->config->item('meta');
		$this->_lang = $this->config->item('lang');
		$this->title = $meta["default"]["title"];
		$this->keyword = $meta["default"]["keyword"];
		$this->description = $meta["default"]["description"];
		$this->load->library('curl');
		$this->load->library("common");
		$this->lang->load('ai', $this->_lang);
		$this->load->library('logging');
		$this->load->library('encrypt');
		$this->_pcnm = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$this->_empcd = $this->session->userdata('user.username');
		$this->_module = "Cement Management";
		$this->lang->load('ai', $this->_lang);
	}

    // public function selectdata(){
    //     $this->load->database();
    //     $post = $this->input->post();
    //     $id = $post['id'];
    //     $sql = "SELECT * FROM aicrm_users WHERE id=".$id;
    //     $a_data = $this->db->query($sql);
    //     $result = $a_data->row_array();
    //     echo json_encode($result);
    // }

	/*--------------------------------   Start View  --------------------------------------*/
	function rpt_monthly_expenses() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		$data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		$data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("customizereport_model");
		$section = $this->customizereport_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->customizereport_model->get_role_amin($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_monthly_expenses', $data);
		
	}

	function rpt_project_on_hand() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		$data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		$data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("customizereport_model");
		$section = $this->customizereport_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->customizereport_model->get_role_amin($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_project_on_hand', $data);
		
	}

	function rpt_sum_report_of_project_line() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		$data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		$data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("customizereport_model");
		$section = $this->customizereport_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->customizereport_model->get_role_amin($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_sum_report_of_project_line', $data);
		
	}

	function rpt_sales_team_forecast_report_by_period() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		// $data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		// $data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("customizereport_model");
		$section = $this->customizereport_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->customizereport_model->get_role_amin($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;

		 $data['sale_name'] = $data['role_user'][0]['first_name'].' '.$data['role_user'][0]['last_name'];
		 $data['sale_team'] = $data['role_user'][0]['cf_501095'].' '.$data['role_user'][0]['cf_501096'].' '.$data['role_user'][0]['cf_501097'];
		//  alert($data); exit;
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_sales_team_forecast_report_by_period', $data);
		
	}

	function rpt_sales_team_forecast_report_by_stage() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		$data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		$data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("customizereport_model");
		$section = $this->customizereport_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->customizereport_model->get_role_amin($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_sales_team_forecast_report_by_stage', $data);
		
	}

	function rpt_sales_forecast_detail() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		// $data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		// $data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("customizereport_model");
		$section = $this->customizereport_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->customizereport_model->get_role_amin($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_sales_forecast_detail', $data);
		
	}

	function rpt_sales_visiting_project() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		// $data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		// $data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("customizereport_model");
		$section = $this->customizereport_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->customizereport_model->get_role_amin($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_sales_visiting_project', $data);
		
	}

	function rpt_sales_forecast_summary_report() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		// $data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		// $data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("customizereport_model");
		$section = $this->customizereport_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->customizereport_model->get_role_amin($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_sales_forecast_summary_report', $data);
		
	}

	function rpt_daily_new_project() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		$data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		$data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("customizereport_model");
		$section = $this->customizereport_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->customizereport_model->get_role_amin($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_daily_new_project', $data);
		
	}

	function rpt_planning() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		$data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		$data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("customizereport_model");
		$section = $this->customizereport_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->customizereport_model->get_role_amin($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_planning', $data);
		
	}

	function rpt_project_listing() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		$data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		$data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("customizereport_model");
		$section = $this->customizereport_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->customizereport_model->get_role_amin($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_project_listing', $data);
		
	}

	function rpt_quote_listing() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		$data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		$data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("customizereport_model");
		$section = $this->customizereport_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->customizereport_model->get_role_amin($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_quote_listing', $data);
		
	}

	function rpt_pipeline_detail() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		$data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		$data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("customizereport_model");
		$section = $this->customizereport_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->customizereport_model->get_role($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;
		 $data['userid']  = $_SESSION['authenticated_user_id'];
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		// alert($data); exit;
		$this->template->build('rpt_pipeline_detail', $data);
		
	}
	/*--------------------------------   END View  --------------------------------------*/

	public function sendmail_rpt_report()
	{
		global $report_viewer_url, $root_directory, $site_URL;
		$this->load->database();
		$a_data = $this->input->post();
		// alert($a_data); exit;

		$date = date('Y-m-d H:i:s');
		$submodule = $a_data["submodule"];
		// echo json_encode($a_data); exit; 
		//format parameter
		$a_param = $this->get_parameter_report($a_data,$submodule);
		// alert($a_param); exit;
		$data_log["adduser"] = USERID;
	
		if($submodule=="rpt_monthly_expenses"){

			if(empty($a_data) || $a_param['send_to']=="")
			{
				$a_reponse["status"] = false;
				$a_reponse["error"] = "ไม่มีข้อมูล";
				$a_reponse["msg"] = "ลองอีกครั้ง" ;
				$a_reponse["result"] = "";
				$data_log["status"] = '2';
				$data_log["msg"] = 'No Data';
				// $this->db->insert('tbt_salesvisit_log', $data_log);
				//echo $this->db->last_query();
				echo json_encode($a_reponse);
				exit();
			}
		}else if($submodule=="rpt_sales_forecast_summary_report"){
			
			if(empty($a_data) || $a_param['send_to']=="")
			{
				$a_reponse["status"] = false;
				$a_reponse["error"] = "ไม่มีข้อมูล";
				$a_reponse["msg"] = "ลองอีกครั้ง" ;
				$a_reponse["result"] = "";
				$data_log["status"] = '2';
				$data_log["msg"] = 'No Data';
				// $this->db->insert('tbt_salesvisit_log', $data_log);
				//echo $this->db->last_query();
				echo json_encode($a_reponse);
				exit();
			}
		}else if($submodule=="rpt_daily_new_project"){
			
			if(empty($a_data) || $a_param['send_to']=="")
			{
				$a_reponse["status"] = false;
				$a_reponse["error"] = "ไม่มีข้อมูล";
				$a_reponse["msg"] = "ลองอีกครั้ง" ;
				$a_reponse["result"] = "";
				$data_log["status"] = '2';
				$data_log["msg"] = 'No Data';
				// $this->db->insert('tbt_salesvisit_log', $data_log);
				//echo $this->db->last_query();
				echo json_encode($a_reponse);
				exit();
			}
		}else if($submodule=="rpt_sales_team_forecast_report_by_stage"){
			
			if(empty($a_data) || $a_param['send_to']=="")
			{
				$a_reponse["status"] = false;
				$a_reponse["error"] = "ไม่มีข้อมูล";
				$a_reponse["msg"] = "ลองอีกครั้ง" ;
				$a_reponse["result"] = "";
				$data_log["status"] = '2';
				$data_log["msg"] = 'No Data';
				// $this->db->insert('tbt_salesvisit_log', $data_log);
				//echo $this->db->last_query();
				echo json_encode($a_reponse);
				exit();
			}
		}else{

			if(empty($a_data) || $a_param['send_to']=="")
			{
				$a_reponse["status"] = false;
				$a_reponse["error"] = "ไม่มีข้อมูล";
				$a_reponse["msg"] = "ลองอีกครั้ง" ;
				$a_reponse["result"] = "";
				$data_log["status"] = '2';
				$data_log["msg"] = 'No Data';
				// $this->db->insert('tbt_salesvisit_log', $data_log);
				//echo $this->db->last_query();
				echo json_encode($a_reponse);
				exit();
			}
		}

		//#genpdf file
		$a_gen = $this->genpdf_new($submodule,$a_param);
		$path = $a_gen["pdf"]["path"];
		$filename = $a_gen["pdf"]["filename"];
		$url_file = $a_gen["pdf"]["url_file"];


		// echo $path; exit;

		$path_excel = $a_gen["excel"]["path"];
		$filename_excel = $a_gen["excel"]["filename"];
		$url_file_excel = $a_gen["excel"]["url_file"];

		if($filename==""){
			$a_reponse["status"] = false;
			$a_reponse["error"] = "ไม่มีข้อมูล pdf";
			$a_reponse["msg"] = "ลองอีกครั้ง" ;
			$a_reponse["result"] = "";
			$data_log["status"] = '2';
			$data_log["msg"] = 'No Data pdf';
			echo json_encode($a_reponse);
			exit();
		}

		$data_log["path"] = $path;
		$data_log["filename"] = $filename;
		$data_log["parameter_birt"] = $url_file;

		$arr = explode(",", $a_param['send_to']);
		$emailTo = array();
		foreach($arr as $key => $val){
			$emailTo[] = $this->getUserEmailId('id',$val);
		}

		$arr2 = explode(",", $a_param['send_cc']);
		$emailCC = array();
		foreach($arr2 as $key => $val){
			$emailCC[] = $this->getUserEmailId('id',$val);
		}

		// if($a_param['accountid'] != ''){
		// 	$sql ="SELECT
		// 		aicrm_contactdetails.email
				
		// 	FROM
		// 		aicrm_contactdetails
		// 		LEFT JOIN aicrm_contactscf ON aicrm_contactscf.contactid = aicrm_contactdetails.contactid
		// 		INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
		// 	WHERE
		// 		aicrm_crmentity.deleted = 0 
		// 		AND aicrm_contactdetails.accountid = '".$a_param['accountid']."' AND aicrm_contactdetails.contactstatus='Active' GROUP BY aicrm_contactdetails.email";
		// 	$a_data = $this->db->query($sql);
		// 	$result = $a_data->result_array();
		// 	// alert($result); exit;
		// 	foreach($result as $key => $val){
		// 		$emailTo[] = $val['email'];
		// 	}
		// }
		
		// print_r($emailTo); exit;


		$path_attaches = array();
		$path_attaches[0] = $root_directory.$path.$filename;


		if($submodule == "rpt_monthly_expenses"){
			$param = [
				'AI-API-KEY' => "1234",
				'from' => $this->getUserFullname(USERID),
				'method' => "send_mail_rpt_monthly_expenses",
				'emailTo' => $emailTo,
				'emailCC' => $emailCC,
				'path_attaches' => $path_attaches,
				'date_start' => $this->convertDateToView($a_param['date_start']),
				'date_end' => $this->convertDateToView($a_param['date_end']),
				'date_now' => $this->convertDateToView(date("Y-m-d")),
				'time_now' => date("H:i:s"),
				'monthly' => $a_param['monthly']
			];
		}elseif($submodule == "rpt_sales_forecast_summary_report"){
			$param = [
				'AI-API-KEY' => "1234",
				'from' => $this->getUserFullname(USERID),
				'method' => "send_mail_rpt_sales_forecast_summary_report",
				'emailTo' => $emailTo,
				'emailCC' => $emailCC,
				'path_attaches' => $path_attaches,
				'date_start' => $this->convertDateToView($a_param['date_start']),
				'date_end' => $this->convertDateToView($a_param['date_end']),
				'date_now' => $this->convertDateToView(date("Y-m-d")),
				'sale_rap' => $a_param['sale_rap'],
				'sale_rap_name' => $a_param['sale_rap_name'],
				'time_now' => date("H:i:s"),
				'monthly' => $a_param['monthly']
			];
		}elseif($submodule == "rpt_daily_new_project"){
			$param = [
				'AI-API-KEY' => "1234",
				'from' => $this->getUserFullname(USERID),
				'method' => "send_mail_rpt_daily_new_project",
				'emailTo' => $emailTo,
				'emailCC' => $emailCC,
				'path_attaches' => $path_attaches,
				'date' => $this->convertDateToView($a_param['date']),
				'date_now' => $this->convertDateToView(date("Y-m-d")),
				'time_now' => date("H:i:s")
			];
		}elseif($submodule == "rpt_sales_team_forecast_report_by_stage"){
			$param = [
				'AI-API-KEY' => "1234",
				'from' => $this->getUserFullname(USERID),
				'method' => "send_mail_rpt_sales_team_forecast_report_by_stage",
				'emailTo' => $emailTo,
				'emailCC' => $emailCC,
				'path_attaches' => $path_attaches,
				'date_from' => $this->convertDateToView($a_param['date_from']),
				'date_to' => $this->convertDateToView($a_param['date_to']),
				'date_now' => $this->convertDateToView(date("Y-m-d")),
				'time_now' => date("H:i:s")
			];
		}
		

		
		// echo json_encode($param); exit;
	
		$url = $site_URL."WB_Service_AI/index.php/services/send_mail";
		// $url = "http://localhost/WDCCCE/WB_Service_AI/index.php/services/send_mail";

		// return (['url' => $url, 'param' => $param]);

		$return_deta = array();
		$return_deta['url'] = $url;
		$return_deta['param'] = $param;
		echo json_encode($return_deta);
	}
	/*********************************************************************************************** */
	private function get_parameter_report($a_data=array(),$submodule="")
	{
		//parameter
		if($submodule == "rpt_monthly_expenses"){
			$a_parameter['date_end'] = @$a_data["date_end"];
			$a_parameter['date_start'] = @$a_data["date_start"];
			$a_parameter['send_to'] =  implode(",", @$a_data["send_to"]);
			$a_parameter['send_cc'] =  @$a_data["send_cc"] != '' ? implode(",", @$a_data["send_cc"]) : '';
			$a_parameter['submodule'] = @$submodule;
			$a_parameter['monthly'] = @$a_data["monthly"];
		}elseif($submodule == "rpt_sales_forecast_summary_report"){
			$a_parameter['date_end'] = @$a_data["date_end"];
			$a_parameter['date_start'] = @$a_data["date_start"];
			$a_parameter['sale_rap'] = @$a_data["sale_rap"];
			$a_parameter['sale_rap_name'] = @$a_data["sale_rap_name"];
			$a_parameter['send_to'] =  implode(",", @$a_data["send_to"]);
			$a_parameter['send_to_name'] = @$a_data["send_to_name"];
			$a_parameter['send_cc'] =  @$a_data["send_cc"] != '' ? implode(",", @$a_data["send_cc"]) : '';
			$a_parameter['send_cc_name'] = @$a_data["send_cc_name"];
			$a_parameter['submodule'] = @$submodule;
			$a_parameter['monthly'] = @$a_data["monthly"];
		}elseif($submodule == "rpt_daily_new_project"){
			$a_parameter['date'] = @$a_data["date"];
			$a_parameter['send_to'] =  implode(",", @$a_data["send_to"]);
			$a_parameter['send_cc'] =  @$a_data["send_cc"] != '' ? implode(",", @$a_data["send_cc"]) : '';
			$a_parameter['submodule'] = @$submodule;
		}elseif($submodule == "rpt_sales_team_forecast_report_by_stage"){
			$a_parameter['date_from'] = @$a_data["date_from"];
			$a_parameter['date_to'] = @$a_data["date_to"];
			$a_parameter['send_to'] =  implode(",", @$a_data["send_to"]);
			$a_parameter['send_cc'] =  @$a_data["send_cc"] != '' ? implode(",", @$a_data["send_cc"]) : '';
			$a_parameter['submodule'] = @$submodule;
		}else{
			$a_parameter['date'] = @$a_data["date"];
			$a_parameter['date_end'] = @$a_data["date_end"];
			$a_parameter['date_start'] = @$a_data["date_start"];
			$a_parameter['send_to'] =  implode(",", @$a_data["send_to"]);
			$a_parameter['send_cc'] =  @$a_data["send_cc"] != '' ? implode(",", @$a_data["send_cc"]) : '';
			$a_parameter['submodule'] = @$submodule;
			$a_parameter['monthly'] = @$a_data["monthly"];
		}
		
		return $a_parameter;
	}
   /*********************************************************************************************** */
   public function genpdf_new($submodule,$a_param=array())
	{
		global $report_viewer_url_service, $root_directory;
		$config_export = $this->config->item('export');
		$birt_link = $config_export[$submodule]["birt_link"];
		$prefix = $config_export[$submodule]["prefix"];
		$path = $config_export[$submodule]['path'];
		// alert($a_param); exit;
		// $param = rawurldecode(http_build_query($a_param));
		$param = http_build_query($a_param);

		$url_file=$report_viewer_url_service.$birt_link."&".$param;
		// echo $url_file; exit;

		$filename = $prefix."_".date('Ymd_his').".pdf";
		$pathfile = $root_directory.$path.$filename;
		
		$ch = curl_init($url_file);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				
		$data = curl_exec($ch);
		
		curl_close($ch);
		
		file_put_contents($pathfile, $data);
		
		
		//export excel 
		$birt_link_excel = $config_export[$submodule]["birt_link_excel"];
		$url_file_excel=$report_viewer_url_service.$birt_link_excel."&".$param;

		$filename_excel = $prefix."_".date('Ymd_his').".xls";
		$pathfile_excel = $root_directory.$path.$filename_excel;	
		
		$ch = curl_init($url_file_excel);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);	
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
	/*********************************************************************************************** */
	function getUserEmailId($name,$val)
	{
		$this->load->database();
		if($val != '')
		{
			$sql = "SELECT email1, email2, yahoo_id from aicrm_users WHERE status='Active' AND ".$name." = '".$val."'";
			$a_data = $this->db->query($sql);
			$email_data = $a_data->row_array();
			$email = $email_data["email1"];
			// echo $email; exit;
			if($email == '')
			{
				$email = $email_data["email2"];
				if($email == '')
				{
					$email = $email_data["yahoo_id"];
				}
			}
			return $email;
		}
		else
		{
			return '';
		}
	}
	function getUserFullname($id)
	{
		$this->load->database();
		if($id != '')
		{
			$sql = "SELECT CONCAT(first_name_th, ' ', last_name_th, ' (', user_name,')' ) as fullname from aicrm_users WHERE id= '".$id."'";
			$a_data = $this->db->query($sql);
			$name_data = $a_data->row_array();
			$fullname = $name_data["fullname"];
			
			return $fullname;
		}
		else
		{
			return '';
		}
	}
	public function convertDateToView($date){
        if($date != '' && $date != "0000-00-00"){
            $date = date('d/m/Y' , strtotime($date));
        }else{
            $date = "";
        }
        return $date;
    }

    function getworkingday()
	{
		$this->load->database();
		$request_body = file_get_contents('php://input');
        $a_param = json_decode($request_body, true);
		
		$post = $this->input->post();
		if($this->input->post()){
			$a_param = $this->input->post();
		}

		$month = $a_param['month'];
		$year = $a_param['year'];
		// alert($month);
		if($month != '' && $year != '')
		{
			$sql = "SELECT ifnull(".$month.",0) as total FROM tbm_workingday WHERE YEAR = '".$year."'";
			// echo $sql; exit;
			$a_data = $this->db->query($sql);
			$name_data = $a_data->row_array();
			$total = $name_data["total"];
			echo $total;
		}
		else
		{
			echo '';
		}
	}
	/*********************************************************************************************** */
	// private function get_parameter($a_data=array(),$submodule="")
	// {
	// 	//parameter
	// 	if($submodule=="weeklyplan"){
	// 		$a_parameter['report_type'] = @$a_data["report_type"];
	// 	}

	// 	$a_parameter['userid'] = @$a_data["userid"];		
	// 	$a_parameter['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-',$a_data["date_start"])));
	// 	$a_parameter['due_date'] =  date('Y-m-d', strtotime(str_replace('/', '-',$a_data["due_date"])));
	// 	$a_parameter['area'] = @$a_data["area"];
	// 	$a_parameter['accountid'] = @$a_data["accountid"];
	// 	$a_parameter['department'] = @$a_data["department"];
	// 	$a_parameter['username'] = @$a_data["username"];
	// 	$a_parameter['objective'] = @$a_data["objective"];
	// 	$a_parameter['year'] = @$a_data["year"];
	// 	$a_parameter['report_plan'] = @$a_data["report_plan"];
	// 	$a_parameter['report_type'] = @$a_data["report_type"];
	// 	$a_parameter['submodule'] = @$submodule;
	// 	$a_parameter['send_to'] =  implode(",", $a_data["send_to"]);
	// 	$a_parameter['description'] = @$a_data["objective"];

	// 	return $a_parameter;

	// }
	// public function sendmail_report()
	// {
	// 	$this->load->database();
	// 	$a_data = $this->input->post();
	// 	$date = date('Y-m-d H:i:s');
	// 	$submodule = $a_data["submodule"];

	// 	//format parameter
	// 	$a_param = $this->get_parameter($a_data,$submodule);
		
	// 	$this->load->database();
	// 	$this->load->model("salesvisit_model");
	// 	$insert_tbm = $this->salesvisit_model->insert_update_send_report($a_param);

	// 	$string_param = serialize($a_param);
	// 	//data log
	// 	$data_log["userid"] = @$a_data["userid"];
	// 	$data_log["reporttype"] = $submodule=="weeklyreport" ? "weeklyreport" :@$a_data["report_type"];
	// 	$data_log["sendtime"] = $date;
	// 	$data_log["adduser"] = USERID;
	// 	$data_log["adddate"] = $date;
	// 	$data_log["parameter"] = $string_param;
	// 	$data_log["sendto"] =$a_param["send_to"] ;

	// 	$this->load->database();
	// 	$this->load->model("salesvisit_model");
	// 	$insert_tbm = $this->salesvisit_model->insert_update_send_report($a_param);

	// 	if($submodule=="weeklyreport"){

	// 		if(empty($a_data) || $a_param['userid']==""  || $insert_tbm == false )
	// 		{
	// 			$a_reponse["status"] = false;
	// 			$a_reponse["error"] = "ไม่มีข้อมูล";
	// 			$a_reponse["msg"] = "ลองอีกครั้ง" ;
	// 			$a_reponse["result"] = "";
	// 			$data_log["status"] = '2';
	// 			$data_log["msg"] = 'No Data';
	// 			$this->db->insert('tbt_salesvisit_log', $data_log);
	// 			//echo $this->db->last_query();
	// 			echo json_encode($a_reponse);
	// 			exit();
	// 		}
	// 	}else{

	// 		if(empty($a_data) || $a_param['userid']=="" || $a_param['report_type']=="" || $insert_tbm == false )
	// 		{
	// 			$a_reponse["status"] = false;
	// 			$a_reponse["error"] = "ไม่มีข้อมูล";
	// 			$a_reponse["msg"] = "ลองอีกครั้ง" ;
	// 			$a_reponse["result"] = "";
	// 			$data_log["status"] = '2';
	// 			$data_log["msg"] = 'No Data';
	// 			$this->db->insert('tbt_salesvisit_log', $data_log);
	// 			//echo $this->db->last_query();
	// 			echo json_encode($a_reponse);
	// 			exit();
	// 		}
	// 	}

	// 	//#genpdf file
	// 	$a_gen = $this->genpdf($submodule,$a_param);
		
	// 	$path = $a_gen["pdf"]["path"];
	// 	$filename = $a_gen["pdf"]["filename"];
	// 	$url_file = $a_gen["pdf"]["url_file"];

	// 	$path_excel = $a_gen["excel"]["path"];
	// 	$filename_excel = $a_gen["excel"]["filename"];
	// 	$url_file_excel = $a_gen["excel"]["url_file"];

	// 	if($filename==""){
	// 		$a_reponse["status"] = false;
	// 		$a_reponse["error"] = "ไม่มีข้อมูล pdf";
	// 		$a_reponse["msg"] = "ลองอีกครั้ง" ;
	// 		$a_reponse["result"] = "";
	// 		$data_log["status"] = '2';
	// 		$data_log["msg"] = 'No Data pdf';
	// 		echo json_encode($a_reponse);
	// 		exit();
	// 	}

	// 	$data_log["path"] = $path;
	// 	$data_log["filename"] = $filename;
	// 	$data_log["parameter_birt"] = $url_file;

	// 	#send mail
	// 	list($status,$msg,$mailmsg) = $this->sendmail($submodule,$a_param,$data_log,$path.$filename,$path_excel.$filename_excel);
	// 	$data_log["status"] = "1";
	// 	$data_log["msg"] = $msg;
	// 	$data_log["mailstatus"] = $status;
	// 	$data_log["mailmsg"] = $mailmsg;

	// 	$a_reponse["status"] = $status;
	// 	$a_reponse["error"] = "";
	// 	$a_reponse["msg"] = $msg ;
	// 	$a_reponse["result"] = "";

	// 	$this->db->insert('tbt_salesvisit_log', $data_log);
	// 	echo json_encode($a_reponse);
	// 	exit();
	// }
	
	// public  function sendmail($submodule="weeklyplan",$param=array(),$data_log=array(),$filename="",$filename_excel="")
	// {
	// 	global $report_viewer_url, $root_directory;
	// 	$a_data = $this->input->post();
	// 	$this->load->library('email');
	// 	$this->config->load('email');	
	// 	$mail = $config_export = $this->config->item('mail'); 
	// 	$from = $mail[$submodule]["from"];
	// 	$from_name = $mail[$submodule]["from_name"];
	// 	$subject = $mail[$submodule]["subject"];

	// 		$sql ="SELECT email1 ,CONCAT(first_name, ' ', last_name )as first_name
	// 				FROM aicrm_users  
	// 				where  FIND_IN_SET (id, '".$param["userid"]."');";
	// 		$a_data = $this->db->query($sql);
	// 		$result = $a_data->result_array();

    //         if($submodule == 'weeklyplan'){
    //             $subject = "MOAI :: Weekly Plan ของ " .$result[0]["first_name"]." ช่วงวันที่ " .date("d/m/Y ",strtotime($param["date_start"]))." ถึง วันที่ ".date("d/m/Y ",strtotime($param["due_date"]));
    //         }
    //         else{
    //             $subject = "MOAI :: Daily Report ของ " .$result[0]["first_name"]." ช่วงวันที่ " .date("d/m/Y ",strtotime($param["date_start"]))." ถึง วันที่ ".date("d/m/Y ",strtotime($param["due_date"]));
    //         }

    //         if(!empty($param["send_to"])){
    //             $sql ="SELECT GROUP_CONCAT(email1 )as email1  ,GROUP_CONCAT(first_name, ' ', last_name )as first_name
	// 				FROM aicrm_users  
	// 				where  FIND_IN_SET (id, '".$param["send_to"]."');";

    //             $a_data = $this->db->query($sql);
    //             $result = $a_data->result_array();
          
    //             if(!empty($result)){
	// 			$to = $result[0]["email1"];
	// 		}else{
	// 			$to = $mail[$submodule]["to"];
	// 		}
	// 	}else{
	// 		$to = $mail[$submodule]["to"];
	// 	}

	// 	//$to='support_moaicrm@qbiosci.com';

    //     $sql ="SELECT email1 ,CONCAT(first_name, ' ', last_name )as first_name
	// 				FROM aicrm_users  
	// 				where  FIND_IN_SET (id, '".$data_log["adduser"]."');";

    //     $a_data = $this->db->query($sql);
    //     $result = $a_data->result_array();

    //     $from = $result[0]["email1"];
    //     $from_name = $result[0]["first_name"];

    //     //$from = 'suchada.w@biogenic.co.th';
    //     //$from_name = 'Moai Send Email'; 

	// 	$this->email->from( strip_tags($from),strip_tags($from_name));
	// 	$this->email->to($to);
		
	// 	//cc mail
	// 	$cc_mail=$this->common->get_user_email($data_log["userid"]);

	// 	$cc_mail = 'support_moaicrm@qbiosci.com';
		
	// 	$this->email->cc($cc_mail); 
	// 	//cc mail
		
	// 	$this->email->subject(strip_tags($subject));
	// 	if (file_exists($root_directory.$filename)) {
	// 		$this->email->attach($root_directory.$filename);
	// 	}
		
	// 	if (file_exists($root_directory.$filename_excel)) {
	// 		$this->email->attach($root_directory.$filename_excel);
	// 	}
		
				
	// 	$message = "เรื่อง : ".htmlspecialchars($subject,ENT_QUOTES)."\r\n<br>";
	// 	$message .= "ข้อมูล ช่วงวันที่ ".date("d/m/Y ",strtotime($param["date_start"]));
	// 	if($param["due_date"]!=""){
	// 		$message .= " ถึง วันที่ ".date("d/m/Y ",strtotime($param["due_date"]));
	// 	}
	// 	$message .="\r\n<br>";
	// 	$message .= "ข้อมูล ของ ".$param['username']."\r\n<br>";
	// 	$message .= "ข้อมูล วันที่ ".date("d/m/Y เวลา  H:i:s",strtotime($data_log["sendtime"]))."\r\n";
		
	
	// 	$this->email->message($message);

	// 	if (! $this->email->send()){
	// 		//echo $this->email->print_debugger(); exit;
	// 		$status = false;
	// 		$msg = "Can't send e-mail,Please try again";
	// 		$mailmsg=$this->email->print_debugger();
	// 	}else{
	// 		//echo $this->email->print_debugger(); exit;
	// 		$status = true;
	// 		$msg = "Send E-mail Completed";
	// 		$mailmsg=$this->email->print_debugger();
	// 	}
	// 	return array($status,$msg,$mailmsg);
	// }

	// public function genpdf($submodule="weeklyplan",$a_param=array())
	// {
	// 	global $report_viewer_url_service, $root_directory;
	// 	$config_export = $this->config->item('export');

	// 	$birt_link = $config_export[$submodule]["birt_link"];
	// 	$prefix = $config_export[$submodule]["prefix"];
	// 	$path = $config_export[$submodule]['path'];

	// 	$param = rawurldecode(http_build_query($a_param));

	// 	$url_file=$report_viewer_url_service.$birt_link."&".$param;

	// 	$filename = $prefix."_".USERID."_".date('Y-m-d_his').".pdf";
	// 	$pathfile = $root_directory.$path.$filename;
		
	// 	$ch = curl_init($url_file);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
	// 	$data = curl_exec($ch);
		
	// 	curl_close($ch);
		
	// 	file_put_contents($pathfile, $data);
		
		
	// 	//export excel 
	// 	$birt_link_excel = $config_export[$submodule]["birt_link_excel"];
	// 	$url_file_excel=$report_viewer_url_service.$birt_link_excel."&".$param;

	// 	$filename_excel = $prefix."_".USERID."_".date('Y-m-d_his').".xls";
	// 	$pathfile_excel = $root_directory.$path.$filename_excel;	
		
	// 	$ch = curl_init($url_file_excel);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		
	// 	$data = curl_exec($ch);		
	// 	curl_close($ch);
		
	// 	file_put_contents($pathfile_excel, $data);
	// 	//export excel
	// 	$a_return["pdf"]["path"] = $path;
	// 	$a_return["pdf"]["filename"] = $filename;
	// 	$a_return["pdf"]["url_file"] = $url_file;
		
	// 	$a_return["excel"]["path"] = $path;
	// 	$a_return["excel"]["filename"] = $filename_excel;
	// 	$a_return["excel"]["url_file"] = $url_file_excel;
	// 	return $a_return;
	// }



}
?>