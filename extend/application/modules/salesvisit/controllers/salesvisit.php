<?php

class Salesvisit extends MY_Controller {

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

    public function selectdata(){
        $this->load->database();
        $post = $this->input->post();
        $id = $post['id'];
		$idString = implode(",", $id);
        $sql = "SELECT * FROM aicrm_users WHERE id IN ($idString)";
        $a_data = $this->db->query($sql);
        $result = $a_data->row_array();
        echo json_encode($result);
    }

	/*--------------------------------   Start View  --------------------------------------*/

	function rpt_weeklyplan_monthlyplan() {

		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		$data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		$data['date_to'] =  date('d/m/Y', strtotime("0 days"));	

		$this->load->database();
		$this->load->model("salesvisit_model");
		$section = $this->salesvisit_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		 
		$role_user = $this->salesvisit_model->get_role_amin($_SESSION['authenticated_user_id']);
		$data['role_user']  = $role_user;
		 
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_weeklyplan_monthlyplan', $data);
		
	}

	function rpt_weeklyplan() {
	
		$this->template->title('Corporate Member Ordinary ', $this->title);
		$this->template->screen('Corporate Member Ordinary ', $this->screen);
		$this->template->modulename('Report', $this->modulename);
		$data['date_from'] =  date('d/m/Y', strtotime("0 days"));		
		$data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		
		$this->load->database();
		$this->load->model("salesvisit_model");
		$section = $this->salesvisit_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		 $role_user = $this->salesvisit_model->get_role_amin($_SESSION['authenticated_user_id']);
		 $data['role_user']  = $role_user;
		
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('no-menu');
		$this->template->build('rpt_weeklyplan', $data);
		
	}
	/*--------------------------------   END View  --------------------------------------*/

	private function get_parameter($a_data=array(),$submodule="")
	{
		//parameter
		if($submodule=="weeklyplan"){
			$a_parameter['report_type'] = @$a_data["report_type"];
		}

		$a_parameter['userid'] = @$a_data["userid"];		
		$a_parameter['date_start'] = date('Y-m-d', strtotime(str_replace('/', '-',$a_data["date_start"])));
		$a_parameter['due_date'] =  date('Y-m-d', strtotime(str_replace('/', '-',$a_data["due_date"])));
		$a_parameter['area'] = @$a_data["area"];
		$a_parameter['accountid'] = @$a_data["accountid"];
		$a_parameter['department'] = @$a_data["department"];
		$a_parameter['username'] = @$a_data["username"];
		$a_parameter['objective'] = @$a_data["objective"];
		$a_parameter['year'] = @$a_data["year"];
		$a_parameter['report_plan'] = @$a_data["report_plan"];
		$a_parameter['report_type'] = @$a_data["report_type"];
		$a_parameter['submodule'] = @$submodule;
		$a_parameter['send_to'] =  implode(",", $a_data["send_to"]);
		$a_parameter['description'] = @$a_data["objective"];

		return $a_parameter;

	}
	public function sendmail_report()
	{
		global $report_viewer_url, $root_directory, $site_URL;
		$this->load->database();
		$a_data = $this->input->post();
		$date = date('Y-m-d H:i:s');
		$submodule = $a_data["submodule"];

		//format parameter
		$a_param = $this->get_parameter($a_data,$submodule);
		
		$this->load->database();
		$this->load->model("salesvisit_model");
		$insert_tbm = $this->salesvisit_model->insert_update_send_report($a_param);

		$string_param = serialize($a_param);
		//data log
		$data_log["userid"] = @$a_data["userid"];
		$data_log["reporttype"] = $submodule=="weeklyreport" ? "weeklyreport" :@$a_data["report_type"];
		$data_log["sendtime"] = $date;
		$data_log["adduser"] = USERID;
		$data_log["adddate"] = $date;
		$data_log["parameter"] = $string_param;
		$data_log["sendto"] =$a_param["send_to"] ;

		$this->load->database();
		$this->load->model("salesvisit_model");
		$insert_tbm = $this->salesvisit_model->insert_update_send_report($a_param);

		if($submodule=="weeklyreport"){

			if(empty($a_data) || $a_param['userid']==""  || $insert_tbm == false )
			{
				$a_reponse["status"] = false;
				$a_reponse["error"] = "ไม่มีข้อมูล";
				$a_reponse["msg"] = "ลองอีกครั้ง" ;
				$a_reponse["result"] = "";
				$data_log["status"] = '2';
				$data_log["msg"] = 'No Data';
				$this->db->insert('tbt_salesvisit_log', $data_log);
				//echo $this->db->last_query();
				echo json_encode($a_reponse);
				exit();
			}
		}else{

			if(empty($a_data) || $a_param['userid']=="" || $a_param['report_type']=="" || $insert_tbm == false )
			{
				$a_reponse["status"] = false;
				$a_reponse["error"] = "ไม่มีข้อมูล";
				$a_reponse["msg"] = "ลองอีกครั้ง" ;
				$a_reponse["result"] = "";
				$data_log["status"] = '2';
				$data_log["msg"] = 'No Data';
				$this->db->insert('tbt_salesvisit_log', $data_log);
				//echo $this->db->last_query();
				echo json_encode($a_reponse);
				exit();
			}
		}

		//#genpdf file
		$a_gen = $this->genpdf_new($submodule,$a_param);
		// alert($a_gen); exit;
		$path = $a_gen["pdf"]["path"];
		$filename = $a_gen["pdf"]["filename"];
		$url_file = $a_gen["pdf"]["url_file"];

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

		#send mail
		list($status,$msg,$mailmsg) = $this->sendmail($submodule,$a_param,$data_log,$path.$filename,$path_excel.$filename_excel);
		$data_log["status"] = "1";
		$data_log["msg"] = $msg;
		$data_log["mailstatus"] = $status;
		$data_log["mailmsg"] = $mailmsg;

		$a_reponse["status"] = $status;
		$a_reponse["error"] = "";
		$a_reponse["msg"] = $msg ;
		$a_reponse["result"] = "";

		$this->db->insert('tbt_salesvisit_log', $data_log);
		echo json_encode($a_reponse);
		exit();
	}
	
	public  function sendmail($submodule="weeklyplan",$param=array(),$data_log=array(),$filename="",$filename_excel="")
	{
		global $report_viewer_url, $root_directory;
		$a_data = $this->input->post();
		$this->load->library('email');
		$this->config->load('email');	
		$mail = $config_export = $this->config->item('mail'); 
		$from = $mail[$submodule]["from"];
		$from_name = $mail[$submodule]["from_name"];
		$subject = $mail[$submodule]["subject"];

			$sql ="SELECT email1 ,CONCAT(first_name, ' ', last_name )as first_name
					FROM aicrm_users  
					where  FIND_IN_SET (id, '".$param["userid"]."');";
			$a_data = $this->db->query($sql);
			$result = $a_data->result_array();

            if($submodule == 'weeklyplan'){
                $subject = "Ai-CRM :: Weekly Plan ของ " .$result[0]["first_name"]." ช่วงวันที่ " .date("d/m/Y ",strtotime($param["date_start"]))." ถึง วันที่ ".date("d/m/Y ",strtotime($param["due_date"]));
            }
            else{
                $subject = "Ai-CRM :: Daily Report ของ " .$result[0]["first_name"]." ช่วงวันที่ " .date("d/m/Y ",strtotime($param["date_start"]))." ถึง วันที่ ".date("d/m/Y ",strtotime($param["due_date"]));
            }

            if(!empty($param["send_to"])){
                $sql ="SELECT GROUP_CONCAT(email1 )as email1  ,GROUP_CONCAT(first_name, ' ', last_name )as first_name
					FROM aicrm_users  
					where  FIND_IN_SET (id, '".$param["send_to"]."');";

                $a_data = $this->db->query($sql);
                $result = $a_data->result_array();
          
                if(!empty($result)){
				$to = $result[0]["email1"];
			}else{
				$to = $mail[$submodule]["to"];
			}
		}else{
			$to = $mail[$submodule]["to"];
		}

		//$to='support_moaicrm@qbiosci.com';

        $sql ="SELECT email1 ,CONCAT(first_name, ' ', last_name )as first_name
					FROM aicrm_users  
					where  FIND_IN_SET (id, '".$data_log["adduser"]."');";

        $a_data = $this->db->query($sql);
        $result = $a_data->result_array();

        $from = "noreply@aisyst.com";
        $from_name = $result[0]["first_name"];

        //$from = 'suchada.w@biogenic.co.th';
        //$from_name = 'Moai Send Email'; 

		$this->email->from( strip_tags($from),strip_tags($from_name));
		$this->email->to($to);
		
		//cc mail
		$cc_mail=$this->common->get_user_email($data_log["userid"]);

		$cc_mail = '';
		
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
		
	
		$this->email->message($message);

		if (! $this->email->send()){
			echo $this->email->print_debugger(); exit;
			$status = false;
			$msg = "Can't send e-mail,Please try again";
			$mailmsg=$this->email->print_debugger();
		}else{
			//echo $this->email->print_debugger(); exit;
			$status = true;
			$msg = "Send E-mail Completed";
			$mailmsg=$this->email->print_debugger();
		}
		return array($status,$msg,$mailmsg);
	}

	public function genpdf($submodule="weeklyplan",$a_param=array())
	{
		global $report_viewer_url_service, $root_directory;
		$config_export = $this->config->item('export');

		$birt_link = $config_export[$submodule]["birt_link"];
		$prefix = $config_export[$submodule]["prefix"];
		$path = $config_export[$submodule]['path'];

		$param = rawurldecode(http_build_query($a_param));

		$url_file=$report_viewer_url_service.$birt_link."&".$param;

		$filename = $prefix."_".USERID."_".date('Y-m-d_his').".pdf";
		$pathfile = $root_directory.$path.$filename;
		
		$ch = curl_init($url_file);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$data = curl_exec($ch);
		
		curl_close($ch);
		
		file_put_contents($pathfile, $data);
		
		
		//export excel 
		$birt_link_excel = $config_export[$submodule]["birt_link_excel"];
		$url_file_excel=$report_viewer_url_service.$birt_link_excel."&".$param;

		$filename_excel = $prefix."_".USERID."_".date('Y-m-d_his').".xls";
		$pathfile_excel = $root_directory.$path.$filename_excel;	
		
		$ch = curl_init($url_file_excel);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		
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

	public function genpdf_new($submodule,$a_param=array())
	{
		global $report_viewer_url_service, $root_directory;
		$config_export = $this->config->item('export');
		$birt_link = $config_export[$submodule]["birt_link"];
		$prefix = $config_export[$submodule]["prefix"];
		$path = $config_export[$submodule]['path'];
		// alert($a_param); exit;
		$param = rawurldecode(http_build_query($a_param));

		$url_file=$report_viewer_url_service.$birt_link."&".$param;
		// echo $url_file; exit;

		$filename = $prefix."_".USERID."_".date('Y-m-d_his').".pdf";
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

		$filename_excel = $prefix."_".USERID."_".date('Y-m-d_his').".xls";
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




}
?>