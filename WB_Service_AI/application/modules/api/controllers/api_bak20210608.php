<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**
 * ### Class Social ������Ѻ�֧ API �ͧ Social ��ҧ �
 */
class Api extends REST_Controller
{
  /**
   * crmid ��� crmid � aicrm_crmentity
   */
	private $crmid;
  function __construct()
  {
    parent::__construct();
    $this->load->library('memcached_library');
	$this->load->library('crmentity');
	$this->load->library('lib_api_common');
	$this->load->library('lib_set_notification');
    $this->load->database();
	$this->load->library("common");
	$this->_format = "array";
	$this->_limit = 100;
	$this->_module = "";
  }


  	private function get_cache($a_params=array())
	{
		$this->load->library('managecached_library');


		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		$module = @$a_params["module"];
		$action = @$a_params["action"];
		$crmid= @$a_params["crmid"];
		$userid= @$a_params["userid"];
		$optimize = @$a_params['optimize'];
		$crm_subid = @$a_params['crm_subid'];
		$related_module="";
		$related_module = @$a_params['related_module'];
		$templateid = @$a_params['templateid'];

		if($module=="Case"){
		 	$module="HelpDesk";
		}elseif ($module=="Spare Part" || $module=="SparePart" ) {
			$module = "Sparepart";
		}elseif ($module=="Errors List" || $module=="ErrorsList") {
			$module = "Errorslist";
		}elseif ($module=="Spare Part List" || $module=="SparePartLis" || $module=="Spare Part List") {
			$module = "Sparepartlist";
		}elseif ($module=="Projectorders" || $module=="Project Orders" || $module=="Projectorder") {
			$module = "Projectorder";
		}elseif($module=="Quotation"){
			$module="Quotes";
		}

		if($related_module=="Case"){
			$related_module="HelpDesk";
		}elseif ($related_module=="Spare Part" || $related_module=="SparePart" ) {
			$related_module = "Sparepart";
		}elseif ($related_module=="Errors List" || $related_module=="ErrorsList") {
			$related_module = "Errorslist";
		}elseif ($related_module=="Spare Part List" || $related_module=="SparePartLis" || $related_module=="Spare Part List") {
			$related_module = "Sparepartlist";
		}elseif ($related_module=="Projectorders" || $related_module=="Project Orders" || $related_module=="Projectorder") {
			$related_module = "Projectorder";
		}elseif($related_module=="Quotation"){
			$related_module="Quotes";
		}



		$a_data = $this->managecached_library->get_memcache($a_cache);

		if($a_data===false)
		{

			$a_list = $this->lib_api_common->Get_Block($module,$action,$crmid,$userid,$crm_subid,$related_module,$templateid);
			//alert($a_list);

			$a_data = $this->get_data($a_list);
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
		return $a_data;
	}

	private function get_data($a_data,$optimize='')
	{
		return $a_data;
	}

  	public function return_data($a_data,$action,$module,$a_param)
	{

		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["error"];
			$a_return["total"] = 0;
			$a_return["offset"] = 0;
			$a_return["limit"] = 0;
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			$a_return["data"][0]["jsonrpc"] = "2.0";
			$a_return["data"][0]["id"] = "";
			$a_return["data"][0]["language"] = !empty($a_data["language"]) ? $a_data["language"] : "" ;
			$a_return["data"][0]["action_button"] = !empty($a_data["button"]) ? $a_data["button"] : "" ;
			$a_return["data"][0]["related_button"] = !empty($a_data["related_button"]) ? $a_data["related_button"] : "" ;
			$a_return["data"][0]["leadstatus_button"] = !empty($a_data["lead_status"]) ? $a_data["lead_status"] : "" ;
			$a_return["data"][0]["request_approve"] = !empty($a_data["request_approve"]) ? $a_data["request_approve"] : "false" ;
			$a_return["data"][0]["approved"] = !empty($a_data["approved"]) ? $a_data["approved"] : "false" ;
			$a_return["data"][0]["convert"] = !empty($a_data["convert"]) ? $a_data["convert"] : "false" ;
			$a_return["data"][0]["no"] = !empty($a_data["title"][0]['no']) ? $a_data["title"][0]['no'] : "" ;
			$a_return["data"][0]["name"] = !empty($a_data["title"][0]['name']) ? $a_data["title"][0]['name'] : "" ;

		if($module == "Calendar" || $module == "Sales Visit" || $module == "Schedule" || $module == "Events"){
			$a_return["data"][0]["title"] = !empty($a_data["title"][0]['name']) ? $a_data["title"][0]['name'] : "" ;
			$a_return["data"][0]["status"] = !empty($a_data["title"][0]['eventstatus']) ? $a_data["title"][0]['eventstatus'] : "" ;
			$a_return["data"][0]["description"] = !empty($a_data["title"][0]['account_no']) ? $a_data["title"][0]['account_no'] : "" ;
			$a_return["data"][0]["dateAt"] = !empty($a_data["title"][0]['date_start']) ? $a_data["title"][0]['date_start'] : "" ;
			$a_return["data"][0]["subtitle"] = !empty($a_data["title"][0]['accountname']) ? $a_data["title"][0]['accountname'] : "" ;

			$a_return["data"][0]["dateAt"] = date("d/m/Y", $a_return["data"][0]["dateAt"]); 

		}else{
			$a_return["data"][0]["title"] = !empty($a_data["title"][0]['title']) ? $a_data["title"][0]['title'] : "" ;
			$a_return["data"][0]["status"] = !empty($a_data["title"][0]['status']) ? $a_data["title"][0]['status'] : "" ;
			$a_return["data"][0]["color"] = !empty($a_data["title"][0]['color']) ? $a_data["title"][0]['color'] : "" ;
			$a_return["data"][0]["description"] = !empty($a_data["title"][0]['description']) ? $a_data["title"][0]['description'] : "" ;
			$a_return["data"][0]["dateAt"] = !empty($a_data["title"][0]['dateAt']) ? $a_data["title"][0]['dateAt'] : "" ;

			$a_return["data"][0]["dateAt"] = date("d/m/Y", strtotime($a_return["data"][0]["dateAt"]));
		}

		if($module=="Accounts" || $module=="Leads"){
			$a_return["data"][0]["tag_list"] = !empty($a_data["title"][0]['tag_list']) ? $a_data["title"][0]['tag_list'] : null ;
		}

		if($module=="Deal" || $module=="Questionnaire"){
			$a_return["data"][0]["subtitle"] = !empty($a_data["title"][0]['subtitle']) ? $a_data["title"][0]['subtitle'] : "" ;
			$a_return["data"][0]["bottomTitle"] = !empty($a_data["title"][0]['bottomTitle']) ? $a_data["title"][0]['bottomTitle'] : "" ;
		}

		if($module=="Deal" && $action=="view"){
			
			$a_return["data"][0]["laststage"] = !empty($a_data["laststage"]) ? $a_data["laststage"] : "" ;
			$a_return["data"][0]["custom"] = !empty($a_data["custom"]) ? $a_data["custom"] : "" ;
		}

			$a_return["data"][0]["result"] = !empty($a_data["data"]) ? $a_data["data"] : "" ;

			if($action=="view"){
				$log_filename = "Detail_".$module;
				$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		  		$this->common->_filename= $log_filename;
		  		$this->common->set_log($url,$a_param,$a_return);
			}
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

  public function list_content_get()
	{

		$a_param =  $this->input->get();
		$a_data =$this->get_cache($a_param);
		$action = $a_param['action'];
		$module = $a_param['module'];

		$this->return_data($a_data,$a_data,$action,$module);
	}

	public function list_content_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$action = $a_param['action'];

		$module = ($a_param['module'] == 'Sales Visit') ? 'Calendar' : $a_param['module'] ;
		if($module=="Case"){
			$module="HelpDesk";
		}elseif ($module=="Spare Part" || $module=="SparePart" ) {
			$module = "Sparepart";
		}elseif ($module=="Errors List" || $module=="ErrorsList") {
			$module = "Errorslist";
		}elseif ($module=="Spare Part List" || $module=="SparePartLis" || $module=="Spare Part List") {
			$module = "Sparepartlist";
		}elseif ($module=="Projectorders" || $module=="Project Orders" || $module=="Projectorder") {
			$module = "Projectorder";
		}elseif($module=="Quotation"){
			$module="Quotes";
		}

		$a_data =$this->get_cache($a_param);
		$this->return_data($a_data,$action,$module,$a_param);
	}


	public function comment_plan_post(){

		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);

		$module =$dataJson['module'];
		$userid =$dataJson['userid'];
		$crmid =$dataJson['crmid'];
		$comment =$dataJson['comment'];
		$action = "commentplan";

		if($module!="" && $userid!="" && $crmid!="" && $comment!=""){

			$sql="insert into aicrm_commentplan (activityid,comments,ownerid,ownertype,createdtime) values('".$crmid."' ,'".$comment."' ,'".$userid."' ,'user' ,NOW() )" ;
			if($this->db->query($sql)){

				$sql_data = "select aicrm_activity.*,aicrm_crmentity.smownerid
				from aicrm_activity
				inner join aicrm_crmentity on aicrm_activity.activityid = aicrm_crmentity.crmid
				where aicrm_crmentity.crmid='".$crmid."'";

				$this->lib_set_notification->set_notification($a_data_new,$crmid,$smownerid,$module,$action,$userid);

				$a_return["Type"] = "S";
				$a_return["Message"] =  "Insert Complete";
				$a_return["data"] = array();
				$data = array(
					'crmid' => $crmid,
					'modifiedtime' => date("Y-m-d H:i:s")
				);

				array_push($a_return["data"] , $data);

			}else{

				$a_return["Type"] = "E";
				$a_return["Message"] =  "Insert Fail!!";
				$a_return["data"] = array();
				$data = array(
					'crmid' => $crmid,
					'modifiedtime' => date("Y-m-d H:i:s")
				);

				array_push($a_return["data"] , $data);

			}
		}

		$log_filename = "Comment_".$module;
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= $log_filename;
		$this->common->set_log($url,$dataJson,$a_return);

		if ( $a_return ) {
			$this->response($a_return, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}
	}


	public function get_template_post(){

	  	$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);
	  	$module = @$dataJson['module'];
	  	$userid = @$dataJson['userid'];

	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			
		$this->common->_filename= "Get_Template";
		$this->common->set_log($url."_Begin",$dataJson,array());

		if($module!="Questionnaire"){
			$a_return["status"] = "E";
			$a_return["error"] =  "No Template";
			$a_return["result"] = "";
			$this->response($a_return, 200);
		}

		try{

			$a_condition["aicrm_crmentity.setype"] = "questionnairetemplate";
			$a_condition["aicrm_crmentity.deleted"] = "0";
	
		   $this->db->select("aicrm_questionnairetemplate.questionnairetemplateid as templateid, aicrm_questionnairetemplate.questionnairetemplate_name as name,aicrm_questionnairetemplate.scoring");
		   $this->db->join('aicrm_questionnairetemplatecf', 'aicrm_questionnairetemplatecf.questionnairetemplateid = aicrm_questionnairetemplate.questionnairetemplateid');
		   $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_questionnairetemplate.questionnairetemplateid');
		   
			$a_condition["aicrm_questionnairetemplate.createmobile"] =  '1' ;


			if (!empty($a_condition)) {
				$this->db->where($a_condition);
			}

			$query = $this->db->get('aicrm_questionnairetemplate');
	
			// echo $this->db->last_query();
			// exit;
			
			if(!$query){
				$a_return["status"] = "E";
				$a_return["error"] = $this->db->_error_message();
				$a_return["result"] = "";
			}else{
				$a_result  = $query->result_array() ;
				//alert($a_result);exit;

				if(!empty($a_result)){

					$a_return["status"] = "S";
					$a_return["error"] =  "";
					$a_return["result"] = $a_result;
				
				}else{
					$a_return["status"] = "E";
					$a_return["error"] =  "No Template";
					$a_return["result"] = "";
				}
			}
		
		}catch (Exception $e) {
				$a_return["status"] = "E";
				$a_return["error"] =  $e->getMessage();
				$a_return["result"] = "";
			
		}
	
		$this->common->_filename= "Get_Template";
		$this->common->set_log($url."_End",$a_param,$a_return);
		

		if ( $a_return ){
			$this->response($a_return, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}

	}

	//  public function get_score_get()
	// {

	// 	$a_param =  $this->input->get();
	// 	$a_data =$this->get_cache($a_param);
	// 	$action = $a_param['action'];
	// 	$module = $a_param['module'];

	// 	// $this->return_data($a_data,$a_data,$action,$module);
	// }


	public function get_score_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$module = @$a_param['module'];
		$lang = @$a_param['lang'];

		$url_img = "https://moaioc.moai-crm.com/storage/img_score/";
		// alert($a_param);exit;
		if($lang=="EN"){
			$score1 = array('title' => 'Total score',
			'subtitle'=>'75-100',
			'description'=>'ผ่านเกณฑ์ (อนุมัติโดยสาขา)',
			'image'=> $url_img.'score1.png' );
			$score2 = array('title' => 'Total score',
			'subtitle'=>'71-74',
			'description'=>'ไม่ผ่านเกณฑ์ (อนุมัติโดยผู้จัดการเขต)',
			'image'=>$url_img.'score2.png' );
			$score3 = array('title' => 'Total score',
			'subtitle'=>'61-70',
			'description'=>'ไม่ผ่านเกณฑ์ (อนุมัติโดยสินเชื่อส่วนกลาง)',
			'image'=>$url_img.'score3.png' );
			$score4 = array('title' => 'Total score',
			'subtitle'=>'51-60',
			'description'=>'ขอเอกสารเพิ่มหรือปรับเงื่อนไข',
			'image'=>$url_img.'score4.png' );
			$score5 = array('title' => 'Total score',
			'subtitle'=>'less than 50',
			'description'=>'ไม่อนุมัติทุกกรณี',
			'image'=>$url_img.'score5.png' );
		}else{
			$score1 = array('title' => 'คะแนนรวม',
			'subtitle'=>'75-100 คะแนน',
			'description'=>'ผ่านเกณฑ์ (อนุมัติโดยสาขา)',
			'image'=>$url_img.'score1.png' );
			$score2 = array('title' => 'คะแนนรวม',
			'subtitle'=>'71-74 คะแนน',
			'description'=>'ไม่ผ่านเกณฑ์ (อนุมัติโดยผู้จัดการเขต)',
			'image'=>$url_img.'score2.png' );
			$score3 = array('title' => 'คะแนนรวม',
			'subtitle'=>'61-70 คะแนน',
			'description'=>'ไม่ผ่านเกณฑ์ (อนุมัติโดยสินเชื่อส่วนกลาง)',
			'image'=>$url_img.'score3.png' );
			$score4 = array('title' => 'คะแนนรวม',
			'subtitle'=>'51-60คะแนน',
			'description'=>'ขอเอกสารเพิ่มหรือปรับเงื่อนไข',
			'image'=>$url_img.'score4.png' );
			$score5 = array('title' => 'คะแนนรวม',
			'subtitle'=>'ต่ำกว่า 50 คะแนน',
			'description'=>'ไม่อนุมัติทุกกรณี',
			'image'=>$url_img.'score5.png' );
		}

		$score = array('0' => $score1,'1'=>$score2 ,'2'=>$score3,'3'=>$score4,'4'=>$score5 );

		$url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$response  = array(
			'Type' => "S",
			'Message' => "Get Score Complete",
			'cache_time' => date("Y-m-d H:i:s"),
			'result' => $score
		);
			
		$this->common->_filename= "Get_Score";
		$this->common->set_log($url,$a_param,$response);

		$this->response($response, 200);
		
	}


	
}
