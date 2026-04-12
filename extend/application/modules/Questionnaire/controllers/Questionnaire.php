<?php

class Questionnaire extends MY_Controller {

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
		$this->_module = "REM";
		$this->lang->load('ai', $this->_lang);
	}

	/*--------------------------------   Start View  --------------------------------------*/
	function index() {

		$this->template->title('Export Questionnaire ', $this->title);
		$this->template->screen('Export Questionnaire ', $this->screen);
		$this->template->modulename('Questionnaire', $this->modulename);
		$data['date_from'] =  date('d/m/Y', strtotime("-7 days"));		
		$data['date_to'] =  date('d/m/Y', strtotime("0 days"));	
		/*$this->load->database();
		$this->load->model("questionnaire_model");
		$section = $this->questionnaire_model->get_section($_SESSION['authenticated_user_id']);
		$data['section'] = $section[0]['section'];
		
		$role_user = $this->questionnaire_model->get_role_amin($_SESSION['authenticated_user_id']);
		$data['role_user']  = $role_user;*/
		$this->template->set_metadata('keywords', $this->keyword);
		$this->template->set_layout('theme-questionnaire');
		$this->template->build('index', $data);
	}
	/*--------------------------------   END View  --------------------------------------*/

	public function listQuestionnaireTemplate(){
		$this->load->database();
		$a_response = [];
		$this->db->select('aicrm_questionnairetemplate.questionnairetemplateid,aicrm_questionnairetemplate.questionnairetemplate_name')->from('aicrm_questionnairetemplate');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_questionnairetemplate.questionnairetemplateid');
		$this->db->where([
			'aicrm_crmentity.deleted' => 0
		]);
		$sql = $this->db->get();
		$result =  $sql->result_array();
		
		echo json_encode($result);
	}

	public function export(){

		$a_param = $this->input->post();
		//$branchid = @$a_param['branchid'];
		$questionnairetemplateid = $a_param['questionnairetemplateid'];
		$date_start = date("Y-m-d",strtotime(str_replace('/', '-',$a_param['date_start'])));
		$due_date = date("Y-m-d",strtotime(str_replace('/', '-',$a_param['due_date'])));
		
		$this->load->database();
		$sql ="call p_questionnaire_export('".$questionnairetemplateid."','','".$date_start."','".$due_date."');";
		//echo $sql; exit;
		$a_data = $this->db->query($sql);
		$result = $a_data->result_array();

		if(!empty($result)){
			$this->load->library("excel");
			$title = "Questionnaire_".date("Ymd_His");
		    $this->excel->setActiveSheetIndex(0);
		    $url = $this->excel->stream_csv(($title.'.csv'), $result);
		    //echo $url; exit;
			$a_reponse["status"] = true;
			$a_reponse["msg"] = "";
			$a_reponse["error"] = "";
			$a_reponse["url"] = $url;
		}else{
			$a_reponse["status"] = false;
			$a_reponse["msg"] = "";
			$a_reponse["error"] = "ไม่พบข้อมูล";
			$a_reponse["url"] = "";
		}

		echo json_encode($a_reponse);
	}

}

?>