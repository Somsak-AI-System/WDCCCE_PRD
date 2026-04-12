<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Indexinsert extends REST_Controller
{
	private $crmid;
  	function __construct()
  	{
    	parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
   	 	$this->load->database();
		$this->load->library("common");
		$this->load->library('lib_set_notification');
		$this->load->model("indexinsert_model");
		$this->_limit = 10;
		$this->_module = "Calendar";
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
					'DocNo' => null
			),
		);
  	}

	public function insert_content_post(){

  		$request_body = file_get_contents('php://input');
  		$dataJson     = json_decode($request_body,true);

  		$response_data = null;
		$a_request = $dataJson;
		
		$module = $a_request['module'];
		if($a_request['module'] == 'Sales Visit'){
			$module = 'Calendar';
		} else if($a_request['module'] == 'Case'){
			$module = 'HelpDesk';
		}

		if ($module=="Projectorders" || $module=="Project Orders" || $module=="Projectorder" || $module=="Project Order") {
			$module = "Projects";
		}

		$action = $a_request['action'];
		$action_delete = @$a_request['data'][0]['deleted'];

		if($action=="add"){
			$log_filename = "Insert_".$module;

		}elseif ($action=="edit") {
			$log_filename = "Edit_".$module;

		}elseif ($action=="duplicate") {
			$log_filename = "Duplicate_".$module;
		
		}elseif ($action=="revise") {
			$log_filename = "Revise_".$module;
		}

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  		$this->common->_filename= $log_filename;
  		$this->common->set_log($url,$dataJson,"");
  		
		$response_data = $this->get_insert_data($a_request);

		if($module == "Job"){

			$instrument = isset($a_request['data'][0]['instrument']) ? $a_request['data'][0]['instrument'] : "";
			$job_assay = isset($a_request['data'][0]['job_assay'])  ? $a_request['data'][0]['job_assay'] : "";

			if($instrument!="" || $job_assay!=""){
				$instrument = str_replace(",", " |##|", $instrument);
				$job_assay = str_replace(",", " |##|", $job_assay);

				$a_request['data'][0]['instrument'] = isset($instrument)  ? $instrument : "";
				$a_request['data'][0]['job_assay'] = isset($job_assay)  ? $job_assay : "";
			}
		}

		if($action=="add"){
			$log_filename = "Insert_".$module;

		}elseif ($action=="edit") {
			$log_filename = "Edit_".$module;

		}elseif ($action=="duplicate") {
			$log_filename = "Duplicate_".$module;
		
		}elseif ($action=="revise") {
			$log_filename = "Revise_".$module;
		}

		if ($action=="edit" && $action_delete=="1" ) {
			$log_filename = "Delete_".$module;
		}

	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$this->common->_filename= $log_filename;
	  	$this->common->set_log($url,$a_request,$response_data);
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
  	}

	function get_returnlocation($crmid="",$module=""){

	  	if($module==""){

	  		$sql ="select *
				FROM aicrm_questionnaire
		    	INNER JOIN aicrm_questionnairecf ON aicrm_questionnairecf.questionnaireid = aicrm_questionnaire.questionnaireid
		    	INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_questionnaire.questionnaireid
				WHERE aicrm_crmentity.deleted = 0
				AND crmid = ".$crmid ."
				AND aicrm_questionnaire.location ='' ";
	  	}else{
	  		$sql ="select *
	  			FROM aicrm_activity
				LEFT JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
				LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
				LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_activity.event_id
				WHERE aicrm_crmentity.deleted = 0
				AND crmid = ".$crmid ."
				AND aicrm_activity.location ='' ";
		}
	  	$query = $this->db->query($sql);
		$data=$query->result_array();

		if(count($data)>0){
			return "E";
		}else{
			return "S";
		}
  	}

	private function get_insert_data($a_request){

	  	$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
		$userid=isset($a_request['userid']) ? $a_request['userid'] : "";
		$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
		$ischeckin = isset($a_request['ischeckin']) ? $a_request['ischeckin'] : "";
		
		if($module=="Case"){
			$module="HelpDesk";
		}elseif ($module=="Spare Part" || $module=="SparePart" ) {
			$module = "Sparepart";
		}elseif ($module=="Errors List" || $module=="ErrorsList") {
			$module = "Errorslist";
		}elseif ($module=="Spare Part List" || $module=="SparePartList") {
			$module = "Sparepartlist";
		}elseif ($module=="Sales Visit" || $module=="Events") {
	 		$module = "Calendar";
		}elseif($module=="Quotation") {
			$module="Quotes";
	 	}elseif ($module=="Projectorders" || $module=="Project Orders" || $module=="Projectorder" || $module=="Project Order") {
			$module = "Projects";
		}

		if($ischeckin=="location"){
			$data[0]["checkindate"]=date('Y-m-d H:i:s'); //DateTime
		}else if($ischeckin=="location_chkout"){
			$chk_return = $this->get_returnlocation($a_request['crmid'],$module);

	        if($chk_return=="E"){
				$a_return  =  array(
					'Type' => 'E',
					'Message' => 'Please check in before check out.',
				);
				return array_merge($this->_return,$a_return);
			}else{
				$data[0]["checkoutdate"]=date('Y-m-d H:i:s'); //DateTime
				$data[0]["eventstatus"] = "Complete";
			}
		}

		$this->load->config('config_module');
	 	
		$config = $this->config->item('module');
		$configModule = $config[$module];
		$tab_name = $configModule['tab_name'];
		$tab_name_index = $configModule['tab_name_index'];
		
		if($module=="Sales Visit" || $module=="Events" || $module=="SalesVisit"  || $module=="Calendar" ){
			$configModule = $config['Calendar'];
			$tab_name = $configModule['tab_name'];
			$tab_name_index = $configModule['tab_name_index'];
	  		$module = "Events";
	  		$smownerid =  isset($a_request['data'][0]['smownerid']) && $a_request['data'][0]['smownerid']!="" ? $a_request['data'][0]['smownerid'] : "0";
			$data[0]["smownerid"] = $smownerid;
			$data[0]['productcategory'] = str_replace(', ', ' |##| ', $data[0]["productcategory"]);
		}else if ($module == "Leads"){
			$data[0]["leadname"] = @$a_request['data'][0]['firstname'].' '.@$a_request['data'][0]['lastname'];
			$data[0]["products_category"] = str_replace(', ', ' |##| ', $data[0]["products_category"]);
			$data[0]["interested_brand"] = str_replace(', ', ' |##| ', $data[0]["interested_brand"]);
		}else if ($module == "Contacts"){
			$data[0]["contactname"] = @$a_request['data'][0]['firstname'].' '.@$a_request['data'][0]['lastname'];
		}else if ($module == "Accounts"){
			$data[0]["accountname"] = @$a_request['data'][0]['firstname'].' '.@$a_request['data'][0]['lastname'];
		}else if($module == "Job"){
			$instrument = isset($a_request['data'][0]['instrument']) ? $a_request['data'][0]['instrument'] : "";
			$job_assay = isset($a_request['data'][0]['job_assay'])  ? $a_request['data'][0]['job_assay'] : "";
			if($instrument!="" || $job_assay!=""){
				$instrument = str_replace(",", " |##|", $instrument);
				$job_assay = str_replace(",", " |##|", $job_assay);
				$data[0]['instrument'] = isset($instrument)  ? $instrument : "";
				$data[0]['job_assay'] = isset($job_assay)  ? $job_assay : "";
			}
			$smownerid =  isset($a_request['data'][0]['smownerid']) && $a_request['data'][0]['smownerid']!="" ? $a_request['data'][0]['smownerid'] : "0";
		  	$serialid =  isset($a_request['data'][0]['serialid']) && $a_request['data'][0]['serialid']!="" ? $a_request['data'][0]['serialid'] : "0";
		  	$product_id =  isset($a_request['data'][0]['product_id']) && $a_request['data'][0]['product_id']!="" ? $a_request['data'][0]['product_id'] : "0";
		  	$ticketid =  isset($a_request['data'][0]['ticketid']) && $a_request['data'][0]['ticketid']!="" ? $a_request['data'][0]['ticketid'] : "0";
			$data[0]["smownerid"] = $smownerid;
			$data[0]["serialid"] = $serialid;
			$data[0]["product_id"] = $product_id;
			$data[0]["ticketid"] = $ticketid;
		}else if ($module == "Deal") {
			$dealamount = "";
			$deal_amount_ocean =  isset($a_request['data'][0]['deal_amount_ocean']) && $a_request['data'][0]['deal_amount_ocean'] != "" ? $a_request['data'][0]['deal_amount_ocean'] : "0";
			$deal_amount_posh =  isset($a_request['data'][0]['deal_amount_posh']) && $a_request['data'][0]['deal_amount_posh'] != "" ? $a_request['data'][0]['deal_amount_posh'] : "0";
			$deal_amount_lucaris =  isset($a_request['data'][0]['deal_amount_lucaris']) && $a_request['data'][0]['deal_amount_lucaris'] != "" ? $a_request['data'][0]['deal_amount_lucaris'] : "0";
			$deal_amount_other =  isset($a_request['data'][0]['deal_amount_other']) && $a_request['data'][0]['deal_amount_other'] != "" ? $a_request['data'][0]['deal_amount_other'] : "0";

			$dealamount = [
				'deal_amount_ocean' => $deal_amount_ocean,
				'deal_amount_posh' => $deal_amount_posh,
				'deal_amount_lucaris' => $deal_amount_lucaris,
				'deal_amount_other' => $deal_amount_other
			];

			$dealamount = array_sum($dealamount);
			$data[0]["dealamount"] = $dealamount;
			$data[0]["brandname"] = str_replace(', ', ' |##| ', $data[0]["brandname"]);
			$data[0]["secondary_reason"] = str_replace(', ', ' |##| ', $data[0]["secondary_reason"]);
		}else if($module == 'Competitor'){
			$data[0]["cf_500643"] = str_replace(', ', ' |##| ', $data[0]["cf_500643"]);
			$data[0]["cf_500644"] = str_replace(', ', ' |##| ', $data[0]["cf_500644"]);
		}else{
			$smownerid =  isset($a_request['data'][0]['smownerid']) && $a_request['data'][0]['smownerid']!="" ? $a_request['data'][0]['smownerid'] : "0";
			$data[0]["smownerid"] = $smownerid;
		}
		
	  	if(count($data[0])>0 and $module!=""){

			if($action=="edit"){
				if($module=="Job"){
					$sql_last = "select aicrm_jobs.*,aicrm_jobscf.* ,aicrm_crmentity.*  from aicrm_jobs
						inner join aicrm_jobscf on aicrm_jobscf.jobid = aicrm_jobs.jobid
						inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_jobs.jobid
						where aicrm_crmentity.deleted=0 and  aicrm_jobs.jobid='".$crmid."'";
					$query_new = $this->db->query($sql_last);
					$a_data_new =  $query_new->result_array() ;
				}elseif ($module=="Calendar" || $module=="Events") {
					$sql_last = "select aicrm_activity.*,aicrm_activitycf.*,aicrm_crmentity.*  from aicrm_activity
						inner join aicrm_activitycf on aicrm_activitycf.activityid = aicrm_activity.activityid
						inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_activity.activityid
						where aicrm_crmentity.deleted=0 and  aicrm_activity.activityid='".$crmid."'";
					$query_new = $this->db->query($sql_last);
					$a_data_new =  $query_new->result_array() ;
				}elseif ($module=="Accounts") {
					$sql_last = "select aicrm_account.*,aicrm_crmentity.*  from aicrm_account
						inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
						where aicrm_crmentity.deleted=0 and  aicrm_account.accountid='".$crmid."'";
					$query_new = $this->db->query($sql_last);
					$a_data_new =  $query_new->result_array() ;
				}elseif ($module=="Leads") {
					$sql_last = "select aicrm_leaddetails.*,aicrm_crmentity.*  from aicrm_leaddetails
						inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
						where aicrm_crmentity.deleted=0 and  aicrm_leaddetails.leadid='".$crmid."'";
					$query_new = $this->db->query($sql_last);
					$a_data_new =  $query_new->result_array() ;
				}elseif ($module=="Deal") {
					$sql_last = "select aicrm_deal.*,aicrm_crmentity.*  from aicrm_deal
						inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_deal.dealid
						where aicrm_crmentity.deleted=0 and  aicrm_deal.dealid='".$crmid."'";
					$query_new = $this->db->query($sql_last);
					$a_data_new =  $query_new->result_array() ;
				}elseif ($module=="Questionnaire") {
					$sql_last = "select aicrm_questionnaire.*,aicrm_crmentity.*  from aicrm_questionnaire
						inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_questionnaire.questionnaireid
						where aicrm_crmentity.deleted=0 and  aicrm_questionnaire.questionnaireid='".$crmid."'";
					$query_new = $this->db->query($sql_last);
					$a_data_new =  $query_new->result_array() ;
				}
			}

			if($action=="add" || $action=="duplicate"){
				
				if($module=="Leads"){
					$lead_mobile = isset($a_request['data'][0]['mobile']) && $a_request['data'][0]['mobile']!="" ? $a_request['data'][0]['mobile'] : "";

					if($lead_mobile!="" && $lead_mobile!="0000000000"){
						
						$sql_mobile = "select aicrm_leaddetails.leadid,aicrm_leaddetails.mobile  from aicrm_leaddetails
						inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
						where aicrm_crmentity.deleted=0 and  aicrm_leaddetails.mobile='".$lead_mobile."'";
						$query_mobile = $this->db->query($sql_mobile);
							if(!empty($query_mobile)){
								$a_data_mobile =  $query_mobile->result_array() ;
								if(!empty($a_data_mobile)){
								$data_mobile = $a_data_mobile[0]['mobile'];

								$a_return  =  array(
			  						'Type' => 'E',
			  						'Message' => 'Mobile number already exists.'
			  					);

			  					return array_merge($this->_return,$a_return);
			  				}
						}
						
					}
				}elseif($module=="Accounts"){
					$acc_mobile = isset($a_request['data'][0]['mobile']) && $a_request['data'][0]['mobile']!="" ? $a_request['data'][0]['mobile'] : "";

					if($acc_mobile!="" && $acc_mobile!="0000000000"){
						
						$sql_mobile = "select aicrm_account.accountid,aicrm_account.mobile  from aicrm_account
						inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
						where aicrm_crmentity.deleted=0 and  aicrm_account.mobile='".$acc_mobile."'";
						$query_mobile = $this->db->query($sql_mobile);
							if(!empty($query_mobile)){
								$a_data_mobile =  $query_mobile->result_array() ;
								if(!empty($a_data_mobile)){
								$data_mobile = $a_data_mobile[0]['mobile'];

								$a_return  =  array(
			  						'Type' => 'E',
			  						'Message' => 'Mobile number already exists.'
			  					);
			  					return array_merge($this->_return,$a_return);
			  				}
						}
					}

					$acc_name = isset($a_request['data'][0]['accountname']) && $a_request['data'][0]['accountname']!="" ? $a_request['data'][0]['accountname'] : "";

					if($acc_name!=""){
						
						$sql_name = "select aicrm_account.accountid,aicrm_account.accountname,aicrm_account.firstname,aicrm_account.lastname  from aicrm_account
						inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
						where aicrm_crmentity.deleted=0 and  aicrm_account.accountname='".$acc_name."'";
						$query_name = $this->db->query($sql_name);
						
						if(!empty($query_name)){
							$a_data_name =  $query_name->result_array() ;
							if(!empty($a_data_name)){
								$data_name = $a_data_name[0]['accountname'];

								$a_return  =  array(
				  					'Type' => 'E',
				  					'Message' => 'Account name already exists.'
				  				);

				  				return array_merge($this->_return,$a_return);
				  			}
						}
						
					}
				}elseif(($module=="Calendar" || $module=="Events") && $action=="add"){
						$date_start = @$a_request['data'][0]['date_start'];
						if($date_start!=""){
							$data[0]['due_date'] = $date_start;
						}
				}
			}

				/*GLAM*/
				if($module=="Projects"){
					
					$date1 = date('Y-m-d');
					$date2 = $a_request['data'][0]['project_s_date'];
					$ts1 = strtotime($date1);
					$ts2 = strtotime($date2);
					$year1 = date('Y', $ts1);
					$year2 = date('Y', $ts2);
					$month1 = date('m', $ts1);
					$month2 = date('m', $ts2);
					$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
					//echo $diff;
					$flagmonth = false;
					if($diff <= 6){
						$flagmonth = true;
					}
					
					$flagopportunity = false;
					if($data[0]['project_opportunity'] == '50% (forecast)' || $data[0]['project_opportunity'] == '100% (forecast)' || $data[0]['project_opportunity'] == '100% (Complete)'){
						$flagopportunity = true;
					}

					$flagsize = false;
					if($data[0]['project_size'] == 'L: 200-499 sh.' || $data[0]['project_size'] == 'XL: 500-999 sh.' || $data[0]['project_size'] == '2XL: 1000-9999 sh.' || $data[0]['project_size'] == '3XL : >=10,000'){
						$flagsize = true;
					}
					if($flagmonth == true && $flagopportunity == true ){
						$data[0]['follow_up_project'] = 1;
					}else{
						$data[0]['follow_up_project'] = 0;
					}
				}
				/*GLAM*/
				if($module=="Quotes" && $action == 'revise'){
					$reviseid = $crmid;
				}

				list($chk,$crmid,$DocNo,$name,$no)=$this->crmentity->Insert_Update($module,$crmid,$action,$tab_name,$tab_name_index,$data,$userid);

				if($module=="Projects"){
					if($action=="add" || $action=="duplicate"){
						foreach($data[0]['owner'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventoryowner', $value);
						}
						foreach($data[0]['consultant'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventoryconsultant', $value);
						}
						foreach($data[0]['architecture'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventoryarchitecture', $value);
						}
						foreach($data[0]['construction'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventoryconstruction', $value);
						}
						foreach($data[0]['designer'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventorydesigner', $value);
						}
						foreach($data[0]['contractor'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventorycontractor', $value);
						}
						foreach($data[0]['subcontractor'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventorysubcontractor', $value);
						}

						foreach($data[0]['product'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventoryprojects', $value);
						}
						foreach($data[0]['compeitor'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventorycompetitorproduct', $value);
						}
					}else if($action=="edit"){
						
						$this->db->delete('aicrm_inventoryowner', ['id'=>$crmid]);
						$this->db->delete('aicrm_inventoryconsultant', ['id'=>$crmid]);
						$this->db->delete('aicrm_inventoryarchitecture', ['id'=>$crmid]);
						$this->db->delete('aicrm_inventoryconstruction', ['id'=>$crmid]);
						$this->db->delete('aicrm_inventorydesigner', ['id'=>$crmid]);
						$this->db->delete('aicrm_inventorycontractor', ['id'=>$crmid]);
						$this->db->delete('aicrm_inventorysubcontractor', ['id'=>$crmid]);
						$this->db->delete('aicrm_inventoryprojects', ['id'=>$crmid]);
						$this->db->delete('aicrm_inventorycompetitorproduct', ['id'=>$crmid]);

						foreach($data[0]['owner'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventoryowner', $value);
						}
						foreach($data[0]['consultant'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventoryconsultant', $value);
						}
						foreach($data[0]['architecture'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventoryarchitecture', $value);
						}
						foreach($data[0]['construction'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventoryconstruction', $value);
						}
						foreach($data[0]['designer'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventorydesigner', $value);
						}
						foreach($data[0]['contractor'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventorycontractor', $value);
						}
						foreach($data[0]['subcontractor'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventorysubcontractor', $value);
						}

						foreach($data[0]['product'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventoryprojects', $value);
						}
						foreach($data[0]['compeitor'] as $value){
							$value['id'] = $crmid;
							$this->db->insert('aicrm_inventorycompetitorproduct', $value);
						}
					}
					
				}
				
				if($module=="Questionnaire"){

					$data_answer=isset($a_request['data_answer']) ? $a_request['data_answer'] : "";
					$formate_answer = $this->set_format_answer($data_answer,@$crmid,@$userid);

					if($action=="edit"){
						$sql_delete =  "DELETE FROM aicrm_questionnaire_answer WHERE questionnaireid='".$crmid."' ;";
						$query_delete = $this->db->query($sql_delete);
					}

					if(count($formate_answer) > 0 && $module == "Questionnaire"){
					//Set String Insert
				
						$sql = 'INSERT INTO aicrm_questionnaire_answer (questionnaireid,relcrmid ,relmodule ,choiceid ,choicedetailid ,choicedetail,createdate) VALUES ';

						foreach($formate_answer as $key => $val){
							
							if($val['type'] == 'text'){
								$sql .= " ".@$comma." ( '".$val['questionnaireid']."', '".$val['userid']."' , '', '".$val['choiceid']."', '".$val['choicedetailid']."', '".$val['choice']."' , '".date('Y-m-d H:i:s')."' ) ";
							}else{
								$choice_aw = isset($val['choicedetail_value']) ? $val['choicedetail_value'] :$val['choice'];

								$sql .= " ".@$comma." ( '".$val['questionnaireid']."', '".$val['userid']."' , '', '".$val['choiceid']."', '".$val['choicedetailid']."', '".$choice_aw."' , '".date('Y-m-d H:i:s')."' ) ";
							}
							
							$comma = ',';
						}
					
						$query = $this->db->query($sql);
						
						//Calculator Point
						$update_point = "UPDATE aicrm_questionnaire
							INNER JOIN (
								SELECT
								sum(aicrm_questionnaire_choicedetail.choicedetail_value) as scroll , aicrm_questionnaire_answer.questionnaireid
								FROM aicrm_questionnaire_answer 
								inner join aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choicedetailid = aicrm_questionnaire_answer.choicedetailid
								where aicrm_questionnaire_answer.questionnaireid = '".$crmid."'
								group by aicrm_questionnaire_answer.questionnaireid
							) answer ON answer.questionnaireid = aicrm_questionnaire.questionnaireid 
							Set aicrm_questionnaire.point = answer.scroll
							WHERE aicrm_questionnaire.questionnaireid = '".$crmid."'; ";
						$this->db->query($update_point);
						//Calculator Point
						
					//Data Insert
					$sql_update = " UPDATE aicrm_smartquestionnairerel SET date_answer = '".date('Y-m-d H:i:s')."' WHERE smartquestionnaireid = '".$crmid."' and relcrmid = '".$userid."' and  servicerequestid = '".$servicerequestid."' ";
					$this->db->query($sql_update);

					//alert($query); exit;
						if(!$query){
							$a_return  =  array(
								'Type' => 'E',
								'Message' => 'Unable to complete transaction',
							);
						}
					}

					if($a_request['data'][0]['activityid']!=""){
						$activityid_update = $a_request['data'][0]['activityid'];

						$update_visit = "UPDATE aicrm_activity
							SET aicrm_activity.questionnaireid= '".$crmid."'
							WHERE aicrm_activity.activityid = '".$activityid_update."'; ";
						$this->db->query($update_visit);

					}
					
				}
				
				if($chk=="0"){
					
					if($module=="Quotes" && $action == 'revise'){
						$updateData = [
							'quotation_status' => 'เปลี่ยนแปลงใบเสนอราคา'
						];
						$this->db->update('aicrm_quotes', $updateData, ['quoteid'=>$reviseid]);
					}

					if($module=="Job" || $module=="Calendar"|| $module=="Events" || $module=="Accounts" || $module=="Leads" || $module=="Deal" || $module=="Questionnaire"){
						
						$this->lib_set_notification->set_notification($a_data_new,$crmid,$smownerid,$module,$action,$userid);
		  				$a_return["Message"] = ($action=="add")?"Insert Complete" :"Update Complete";
		  				$a_return["data"] =array(
	  						'Crmid' => $crmid,
								'Name' => $name,
							 'No' => $no,
						);
					}else{
		  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
		  				$a_return["data"] =array(
	  						'Crmid' => $crmid,
							'Name' => $name,
							'No' => $no,
						);
					}
					
	  			}else{
	  				$a_return  =  array(
	  						'Type' => 'E',
	  						'Message' => 'Unable to complete transaction',
	  				);
	  			}
	  	}else{
	  		$a_return  =  array(
	  				'Type' => 'E',
	  				'Message' =>  'Invalid Request!',
	  		);
		}
	  	
	  	return array_merge($this->_return,$a_return);
  
  	}


	public function set_notification($a_data = array(),$crmid="",$smownerid="",$module="",$action="",$userid="")
	{

		if($crmid=="") return "";
		if($smownerid=="") return "";
		$a_data_new = $a_data[0];

		$this->load->config('config_notification');
		$config = $this->config->item('notification');

		if($module=="Events" || $module=="Calendar" || $module=="Sales visit" ){
			$method = 'calendar';
			$a_condition["aicrm_activity.activityid"] = $crmid;
		}elseif ($module=="Job") {
			$method = 'Job';
			$a_condition["aicrm_jobs.jobid"] = $crmid;
		}



		$a_result = $this->indexinsert_model->get_notification($a_condition,$module);
		$a_activity = @$a_result["result"]["data"][0];
		$queryfunction = @$config[$method]["queryfunction"];

		if (method_exists($this, $queryfunction))
		{
			// alert($queryfunction);exit;

			$a_data_return_noti = $this->{$queryfunction}($crmid,$config[$method],$action,$a_data_new);

			if($a_data_return_noti["status"]===false || empty($a_data_return_noti["result"])){
				return array_merge($this->_return,$a_data_return_noti);
				exit();
			}
			$a_noti = $a_data_return_noti["result"];
		}else{
			$a_data["status"] = false;
			$a_data["message"] = "Method Query is null";
			return array_merge($this->_return,$a_data);
		}

		//Get Time Send Notification
		$a_send = $this->get_send($config[$method]["send"],$a_noti);
		if($a_send["status"]===false || empty($a_send["result"])){
			return array_merge($this->_return,$a_send);
			exit();
		}

		$a_send_data = $a_send["result"];
		$notificationid = ($a_noti["notificationid"]==0 || $a_noti["notificationid"]=="") ?"": $a_noti["notificationid"];
		$msg = $this->get_message($a_noti,$module);


		$field_startdate = $config[$method]["startdate"];
		$field_starttime = @$config[$method]["starttime"];
		$startdate_date = @$a_noti[$field_startdate];
		$startdate_time = @$a_noti[$field_starttime];


		$startdate = $startdate_date .( $startdate_time != "" ? " ".$startdate_time:"");
		// Get field End date
		$field_enddate = $config[$method]["enddate"];
		$field_endtime = @$config[$method]["endtime"];
		$enddate_date = @$a_noti[$field_enddate];
		$enddate_time = @$a_noti[$field_endtime];

		$enddate = $enddate_date . ($enddate_time != "" ? " ".$enddate_time:"");

		if($startdate!="" && $enddate=="" ){
			$enddate =  date("Y-m-d H:i:s", strtotime(($startdate) . "+1 hour"));
		}

		// alert($crmid);
		// alert($smownerid);exit;
		$data_usergroup = $this->get_usergroup($smownerid);
		if(!empty($data_usergroup)){
			$smownerid =$data_usergroup;
		}


		if($module=="Events" || $module=="Sales Visit" ){
			$module="Calendar";
		}
		$a_param["Value1"] = $notificationid;
		$a_param["Value2"] = $crmid;

		$a_param["Value3"] = $module;
		$a_param["Value4"] = "";//send_total
		$a_param["Value5"] = "";//send_success;
		$a_param["Value6"] = "";//send_unsuccess*/
		$a_param["Value7"] = date('Y-m-d');
		//$a_param["Value8"] = date('H:i','+1 minutes');//send_time
		$a_param["Value8"] = date('H:i', strtotime('+1 minutes'));//send_time ,
		// if($module=="Calendar"){
		// 	$a_param["Value8"] = date('H:i', strtotime($a_activity["time_start"].'+1 minutes'));//send_time
		// }elseif ($module=="Job") {
		// 	$a_param["Value8"] = date('H:i', strtotime($a_activity["start_time"].'+1 minutes'));//send_time
		// }

		$a_param["Value9"] = $msg;//send_message
		$a_param["Value10"] = "1";//noti_type
		$a_param["Value11"] = "";//result_filename
		$a_param["Value12"] = "";//result_status
		$a_param["Value13"] = "";//result_errorcode
		$a_param["Value14"] = "";//result_errormsg
		$a_param["Value15"] = $smownerid;//userid
		$a_param["Value16"] = $userid;//empcd
		$a_param["Value17"] = "";//noti_status
		$a_param["Value18"] = $config["projectcode"];//project_code
		$a_param["Value19"] = $startdate;//
		$a_param["Value20"] = $enddate;//
		$a_param["Value21"] = $a_noti[$config[$method]["send"]['field_crm_date']];// Start Date CRM
		$a_param["Value22"] = $a_noti[$config[$method]["send"]['field_crm_time']];// Start Time CRM

		$method = "SetNotificationPersonal";
		$url = $config["url"].$method;
		$this->load->library('curl');
		$this->common->_filename= "Insert_Notification";
		$this->common->set_log($url."_Begin",$a_param,array());

		// alert($s_result); exit;

		//Send Noti To .Net
		$s_result = $this->curl->simple_post($url, $a_param,array(),"json");

		// alert($s_result); exit;
		$a_response = json_decode($s_result,true);
		$notificationid = $a_response["Value3"];

		$a_update["crmid"] = $crmid;
		$a_update["notificationid"] = $notificationid;
		$sql = $this->db->insert_string('aicrm_crmentity_notification', $a_update) . " ON DUPLICATE KEY UPDATE notificationid='".$notificationid."'";
		$this->db->query($sql);

		if($module=="Calendar" || $module=="Events"){
			$this->common->set_log($url." update data ",$this->db->last_query(),"");
			$this->common->set_log($url."_End",$a_param,$s_result);
			$this->common->_filename= "Insert_Calendar";

		}
		if($module=="Job"){
			$this->common->set_log($url." update data ",$this->db->last_query(),"");
			$this->common->set_log($url."_End",$a_param,$s_result);
			$this->common->_filename= "Insert_Job";
		}

		return $s_result;
	}

	public function get_message( $data=array() ,$module){
		// alert($data);exit;
		if($module=="Events"  || $module=="Calendar"){
			$message = 'วันที่ : '.date("d-m-Y", strtotime($data['date_start'])).'\nเวลา : '.$data['time_start'].'\nObj : '.$data['activitytype'].'\nCustomer Name : '.$data['accountname'];
		}elseif ($module=="Job") {
			$message = 'วันที่ : '.date("d-m-Y", strtotime($data['jobdate'])).'\nเวลา : '.$data['notification_time'].'\nJob Name : '.$data['job_name'].'\nCustomer Name : '.$data['accountname'];
		}
			return $message;
	}

	public function get_calendar($crmid="",$a_cofig=array(),$action="",$a_data_new="")
	{

		$activitytype = $a_data_new['activitytype'];
		$smownerid = $a_data_new['smownerid'];
		$date_start = $a_data_new['date_start'];
		$time_start= $a_data_new['time_start'];
		$due_date= $a_data_new['due_date'];
		$time_end= $a_data_new['time_end'];
		$eventstatus= $a_data_new['eventstatus'];
		$account_id= $a_data_new['accountid'];

		$sql = " select aicrm_activity.*,aicrm_activitycf.*,aicrm_crmentity.*, aicrm_crmentity_notification.notificationid ,aicrm_account.accountname
				from aicrm_activity
				inner join aicrm_activitycf on aicrm_activitycf.activityid = aicrm_activity.activityid
				inner join aicrm_account on aicrm_activitycf.accountid = aicrm_account.accountid
				inner join aicrm_crmentity on aicrm_activity.activityid = aicrm_crmentity.crmid
				left join aicrm_crmentity_notification on aicrm_crmentity.crmid =  aicrm_crmentity_notification.crmid";

		$sql .= " where aicrm_crmentity.deleted = 0	";
		if (isset($crmid) && $crmid!="") {
			$sql .= " and aicrm_crmentity.crmid = '".$crmid."'";
		}

		$query = $this->db->query($sql);
		if(!$query){
			$a_return["status"] = false;
			$a_return["message"] = $this->ci->db->_error_message();
			$a_return["result"] = "";
		}else{

		$a_data =  $query->result_array() ;
		$a_data = $a_data[0];

		// foreach ($a_data as $key => $value) {
		if($action=="edit" && ($activitytype!=$a_data['activitytype'] || $smownerid!=$a_data['smownerid'] || $date_start!=$a_data['date_start'] || $time_start!=$a_data['time_start']
			|| $due_date!=$a_data['due_date'] || $time_end!=$a_data['time_end'] || $eventstatus!=$a_data['eventstatus'] || $account_id!=$a_data['accountid'])){
				$a_data['notificationid']="";
				$a_data = $a_data;

		}elseif ($action=="edit" && $activitytype==$a_data['activitytype'] && $smownerid==$a_data['smownerid'] && $date_start==$a_data['date_start'] && $time_start==$a_data['time_start']
			&& $due_date==$a_data['due_date'] && $time_end==$a_data['time_end'] && $eventstatus==$a_data['eventstatus'] && $account_id==$a_data['accountid']) {
				$a_data = $a_data;
		}

	  	if (!empty($a_data)) {
			$a_return["status"] = true;
			$a_return["message"] =  "";
			$a_return["result"] = $a_data;
		}else{
			$a_return["status"] = false;
			$a_return["message"] =  "No Data";
			$a_return["result"] = "";
			}
		}
		
		return $a_return;

	}

	public function get_job($crmid="",$a_cofig=array(),$action="",$a_data_new="")
	{

		$smownerid = $a_data_new['smownerid'];

		$sql = " select aicrm_jobs.*,aicrm_jobscf.*,aicrm_crmentity.*, aicrm_crmentity_notification.notificationid ,aicrm_account.accountname
			from aicrm_jobs
			inner join aicrm_jobscf on aicrm_jobscf.jobid = aicrm_jobs.jobid
			left join aicrm_account on aicrm_account.accountid = aicrm_jobs.accountid
			inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_jobs.jobid
			left join aicrm_crmentity_notification on aicrm_crmentity_notification.crmid =  aicrm_crmentity.crmid";

			$sql .= " where aicrm_crmentity.deleted = 0	";
			if (isset($crmid) && $crmid!="") {
				$sql .= " and aicrm_crmentity.crmid = '".$crmid."'";
			}
			// alert($sql);exit;
			$query = $this->db->query($sql);

			if(!$query){
				$a_return["status"] = false;
				$a_return["message"] = $this->ci->db->_error_message();
				$a_return["result"] = "";
			}else{

			$a_data =  $query->result_array() ;
			$a_data = $a_data[0];
			// alert($a_data);exit;
			// foreach ($a_data as $key => $value) {
			if($action=="edit" && $smownerid!=$a_data['smownerid']){
					$a_data['notificationid']="";
					$a_data = $a_data;
					// echo 111;
					// alert($a_data);exit;
			}elseif ($action=="edit" && $smownerid==$a_data['smownerid']) {
					$a_data = $a_data;
			}

			if (!empty($a_data)) {
					$a_return["status"] = true;
					$a_return["message"] =  "";
					$a_return["result"] = $a_data;
				}else{
					$a_return["status"] = false;
					$a_return["message"] =  "No Data";
					$a_return["result"] = "";
				}
			}
		
		return $a_return;
	}

	private function get_send($a_config=array(),$a_data=array())
	  	{
  		if(empty($a_config)){
  			$a_return["status"] = false;
  			$a_return["message"] = "Send Time is null";
  			return array_merge($this->_return,$a_return);
  		}


  		$send_mode = $a_config["send_mode"];
  		$send_time = @$a_config["send_time"];
  		$field_crm_date = @$a_config["field_crm_date"];
  		$field_crm_time = @$a_config["field_crm_time"];

  		if($send_mode=="save")
  		{
  			$a_response["senddate"] = date("Y-m-d");
  			$a_response["sendtime"] = date("H:i", strtotime("+10 minutes"));
  		}
  		else if($send_mode=="batch")
  		{
  			$a_response["senddate"] = date("Y-m-d", strtotime("+1 day"));
  			if($send_time!=""){
  				$a_response["sendtime"] = str_replace(".", ":", $send_time);
  			}else{
  				$a_response["sendtime"] = date("H:i");
  			}
  		}
  		else if($send_mode=="fieldsend")
  		{
  			$s_senddate = @$a_data[$field_crm_date];
  			if($s_senddate!=""){
  				$a_response["senddate"] = $s_senddate;
  			}else{
  				$a_response["senddate"] = date("Y-m-d", strtotime("+1 day"));
  			}

  			$s_sendtime = @$a_data[$field_crm_time];
  			if($s_sendtime!=""){
  				$a_response["sendtime"] = str_replace(".", ":", $s_sendtime);
  			}else{
  				$a_response["sendtime"] = date("H:i");
  			}
  		}
  		$a_return["status"] = true;
  		$a_return["message"] =  "";
  		$a_return["result"] = $a_response;
  		return $a_return;

  	}

	private function get_usergroup($smownerid=""){

		$sql_userlist = "select  aicrm_users.id  as userid
        FROM aicrm_groups
        inner join aicrm_crmentity on aicrm_groups.groupid = aicrm_crmentity.smownerid
			inner JOIN aicrm_users2group on aicrm_users2group.groupid =  aicrm_groups.groupid
			LEFT JOIN aicrm_users on aicrm_users.id =  aicrm_users2group.userid
        WHERE aicrm_groups.groupid = '".$smownerid."' and aicrm_users.deleted=0 group by aicrm_users2group.userid";

			$query = $this->db->query($sql_userlist);
			$a_data =  $query->result_array() ;

			if(empty($a_data)){
				// $data[] = array("userid"=>$smownerid);
				$data = $smownerid;
			}else {
				$data = $a_data;
				foreach ($data as $key => $value) {
					// alert($value);exit;
					$data_value = implode( ",", $value );
					$data .= $data_value.",";
					$test =  rtrim($data,",");
					$data = $test;
					// alert($key);
				}

			}
		return $data;
	}

	public function update_winlose_post()
	{
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);

		$crmid = @$dataJson['crmid'];
		$userid = @$dataJson['userid'];
		$stage = @$dataJson['stage']; // win/lose
		$reason = @$dataJson['reason'];
		$remark = @$dataJson['remark'];
		$moreDetails = @$dataJson['moredetails'];
		$competitorid = @$dataJson['competitorid'];
		$newcompetitor = @$dataJson['newcompetitor'];

		$response_data = array(
			'Type' => "E",
			'Message' => "Update Failed",
			'cache_time' => date("Y-m-d H:i:s"),
			'total' => "1",
			'offset' => "0",
			'limit' => "1",
			'data' => array(
					'crmid' => $crmid,
					'stage' => ""
			)
		);

		switch($stage){
			case 'win':
				$stage = 'Closed Won';
				$this->db->update('aicrm_deal', [
					'stage' => $stage,
					'wonreason' => $reason,
					'remark' => $remark,
					// 'competitorid' => $competitorid,
					'newcompetitor' => $newcompetitor,
					//'moredetails' => $moreDetails
				],[
					'dealid' => $crmid
				]);
				// echo $this->db->last_query(); exit();
				break;
			case 'lose':
				$stage = 'Closed Lost';
				$this->db->update('aicrm_deal', [
					'stage' => $stage,
					'wonreason' => $reason,
					'remark' => $remark,
					// 'competitorid' => $competitorid,
					'newcompetitor' => $newcompetitor,
					//'moredetails' => $moreDetails
				],[
					'dealid' => $crmid
				]);
				break;
			default: 
				$stage = '';
				break;
		}

		if($stage != ''){
			$this->db->insert('tbt_log_quote_saleorder', [
				'modules' => 'Deal',
				'crmid' => $crmid,
				'userid' => $userid,
				'status' => $stage,
				'comments' => '',
				'date_time' => date('Y-m-d H:i:s'),
				'form' => ''
			]);

			$response_data['Type'] = 'S';
			$response_data['Message'] = 'Update Complete';
			$response_data['data']['stage'] = $stage;
		}

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$this->common->_filename= "Update_Stage";
		$this->common->set_log($url,$dataJson,$response_data);
		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
					'error' => 'Couldn\'t find Set Content!'
			), 404);
		}
	}

	public function update_stage_post(){

	  	$request_body = file_get_contents('php://input');
	  	$dataJson     = json_decode($request_body,true);
	  	$crmid = @$dataJson['crmid'];
	  	$userid = @$dataJson['userid'];
	  	$stage = @$dataJson['stage'];
		$comments = @$dataJson['comments'];

	  	$sql = "update aicrm_deal set stage='".$stage."', comments='".$comments."' where  dealid='".$crmid."' ";
  		//alert($sql);exit;

     	if($this->db->query($sql)){

			$this->db->insert('tbt_log_quote_saleorder', [
				'modules' => 'Deal',
				'crmid' => $crmid,
				'userid' => $userid,
				'status' => $stage,
				'comments' => $comments,
				'date_time' => date('Y-m-d H:i:s'),
				'form' => ''
			]);
			// echo $this->db->last_query(); exit();

	     	$response_data = array(
				'Type' => "S",
				'Message' => "Update Complete",
				'cache_time' => date("Y-m-d H:i:s"),
				'total' => "1",
				'offset' => "0",
				'limit' => "1",
				'data' => array(
						'crmid' => $crmid,
						'stage' => $stage
				)
			);

     	}else{

	     	$response_data = array(
				'Type' => "E",
				'Message' => "Update Fail",
				'cache_time' => date("Y-m-d H:i:s"),
				'total' => "1",
				'offset' => "0",
				'limit' => "1",
				'data' => array(
						'crmid' => $crmid,
						'stage' => ""
				)
			);
     	}

	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$this->common->_filename= "Update_Stage";
	  	$this->common->set_log($url,$dataJson,$response_data);
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
  	}

  	public function set_format_answer($a_param=array(),$crmid=NULL,$userid=NULL)
  	{
   		//Set Answer
	   	if(count($a_param)>=0){		
			foreach ($a_param as $key => $value){
				
				$templateid = $value['templateid'];
				
				
				if($value['type'] == 'dropdown'){
					if($value["choice"] != false){
						$a_response["questionnaireid"] = (isset($crmid) && $crmid!="") ?$crmid:"0";
						$a_response["userid"] = (isset($userid) && $userid!="") ? $userid:"0" ;
						$a_response["choiceid"] = (isset($value["choiceid"]) && $value["choiceid"]!="") ? $value["choiceid"]:"0" ;
						$a_response["type"] = (isset($value["type"]) && $value["type"]!="") ? $value["type"]:"" ;
						foreach($value["choice"] as $k => $v){
							$data = explode("|##|", $v); 
							$a_response["choicedetailid"] = (isset($data[0]) && $data[0]!="") ? $data[0]:"0";
							if(isset($data[2]) && $data[2] == 'get_other'){
								$a_response["choice"] = (isset($value["otherText"]) && $value["otherText"] !="" ) ? $value["otherText"] : "" ;
							}else{
								$a_response["choice"] = (isset($data[1]) && $data[1] !="" ) ? $data[1]: "" ;
							}
							$a_return[$value["choiceid"]][$a_response["choicedetailid"]] = $a_response;
						}

						if($value["otherText"]!=""){
							$a_return[$value["choiceid"]][$a_response["choicedetailid"]]["choice"] = (isset($value["otherText"]) && $value["otherText"] !="" ) ? $value["otherText"] : "" ;
						}
					}
				}else if($value['type'] == 'radiogroup'){
					if($value["choice"] != false){
						$a_response["questionnaireid"] = (isset($crmid) && $crmid!="") ?$crmid:"0" ;
						$a_response["userid"] = (isset($userid) && $userid!="") ? $userid:"0" ;
						$a_response["choiceid"] = (isset($value["choiceid"]) && $value["choiceid"]!="") ? $value["choiceid"]:"0" ;
						$a_response["type"] = (isset($value["type"]) && $value["type"]!="") ? $value["type"]:"" ;
						
						foreach($value["choice"] as $k => $v){
							$data = explode("|##|", $v); 
							$a_response["choicedetailid"] = (isset($data[0]) && $data[0]!="") ? $data[0]:"0";
							if(isset($data[2]) && $data[2] == 'get_other'){
							// if($value["otherText"]!=""){
								$a_response["choice"] = (isset($value["otherText"]) && $value["otherText"] !="" ) ? $value["otherText"] : "" ;
							}else{
								$a_response["choice"] = (isset($data[1]) && $data[1] !="" ) ? $data[1]: "" ;
							}
							$a_return[$value["choiceid"]][$a_response["choicedetailid"]] = $a_response;
						}

						if($value["otherText"]!=""){
							$a_return[$value["choiceid"]][$a_response["choicedetailid"]]["choice"] = (isset($value["otherText"]) && $value["otherText"] !="" ) ? $value["otherText"] : "" ;
						}
					}
				}else if($value['type'] == 'checkbox'){

					if($value["choice"] != false){
						$a_response["questionnaireid"] = (isset($crmid) && $crmid!="") ?$crmid:"0" ;
						$a_response["userid"] = (isset($userid) && $userid!="") ? $userid:"0" ;
						$a_response["choiceid"] = (isset($value["choiceid"]) && $value["choiceid"]!="") ? $value["choiceid"]:"0" ;
						$a_response["type"] = (isset($value["type"]) && $value["type"]!="") ? $value["type"]:"" ;
						$temp = $a_response ;
						foreach($value["choice"] as $k => $v){
							$data = explode("|##|", $v);	
							$temp["choicedetailid"] = (isset($data[0]) && $data[0]!="") ? $data[0]:"0";
							// alert($value);exit;
							if(isset($data[2]) && $data[2] == 'get_other'){
							// 	alert($value["otherText"]);
								

							// 	$temp["choice"] = (isset($value["otherText"]) && $value["otherText"] !="" ) ? $value["otherText"] : "" ;
							// if($value["otherText"]!=""){
								$temp["choice"] = (isset($value["otherText"]) && $value["otherText"] !="" ) ? $value["otherText"] : "" ;
							}else{
								
								$temp["choice"] = (isset($data[1]) && $data[1] !="" ) ? $data[1]: "" ;
							}
								
							$a_return[$value["choiceid"]][$temp["choicedetailid"]] = $temp;
						}

						if($value["otherText"]!=""){
							$a_return[$value["choiceid"]][$temp["choicedetailid"]]["choice"] = (isset($value["otherText"]) && $value["otherText"] !="" ) ? $value["otherText"] : "" ;
						}
						// alert($a_return[$value["choiceid"]][$temp["choicedetailid"]]);
					}
					//alert($a_return); exit;
				}else{//Text
				  if($value["choice"] != false){
				  	
					$a_response["questionnaireid"] = (isset($crmid) && $crmid!="") ?$crmid:"0" ;
					$a_response["userid"] = (isset($userid) && $userid!="") ? $userid:"0" ;
					$a_response["choiceid"] = (isset($value["choiceid"]) && $value["choiceid"]!="") ? $value["choiceid"]:"0" ;
					$a_response["type"] = (isset($value["type"]) && $value["type"]!="") ? $value["type"]:"" ;
					$a_response["choicedetailid"] = (isset($value["choicedetailid"]) && $value["choicedetailid"]!="") ? $value["choicedetailid"]:"0" ;
					$a_response["choice"] = (isset($value["choice"][0]) && $value["choice"][0]!="") ? $value["choice"][0]:"" ;
					$a_return[$value["choiceid"]][$a_response["choicedetailid"]] = $a_response;
				  }
				  
				}
			}// foreach question
		}// if count
	   	//Set Answer
		// alert($a_response); 
		// alert($a_return); 
		// exit;

	   	//Get Questionnaire Template
	  	$sql_tem = 'SELECT 
	    aicrm_questionnairetemplate_page.*,
	    aicrm_questionnairetemplate_choice.*,
	    aicrm_questionnairetemplate_choicedetail.*
		FROM aicrm_questionnairetemplate
		LEFT JOIN aicrm_questionnairetemplate_page ON aicrm_questionnairetemplate_page.questionnairetemplateid = aicrm_questionnairetemplate.questionnairetemplateid
		LEFT JOIN aicrm_questionnairetemplate_choice ON aicrm_questionnairetemplate_choice.pageid = aicrm_questionnairetemplate_page.pageid
		LEFT JOIN aicrm_questionnairetemplate_choicedetail ON aicrm_questionnairetemplate_choicedetail.choiceid = aicrm_questionnairetemplate_choice.choiceid
		WHERE aicrm_questionnairetemplate.questionnairetemplateid = "'.$templateid.'" order by aicrm_questionnairetemplate_page.pageid asc ,aicrm_questionnairetemplate_choice.choiceid asc , aicrm_questionnairetemplate_choicedetail.choicedetailid asc';/*ORDER BY aicrm_questionnairetemplate_choice.choiceid asc*/
		$query = $this->db->query($sql_tem);
		$data_tem = $query->result_array();
		//alert($data_tem);exit;
		//Set Format 
		$fomat_template = $this->set_format($data_tem);
		//alert($fomat_template); exit;

		//Insert New Questionnaire Template
		$title_questionnaire = @$fomat_template['title'];
		foreach($fomat_template['pages'] as $key => $val){
			
			//inset tabe aicrm_questionnaire_page
			$sql = "insert into aicrm_questionnaire_page (questionnaireid,title_questionnaire,title_page,name_page,sequence_page) ";
			$sql .= " VALUES ('".$crmid."','".$title_questionnaire."','".$val['title']."','".$val['name']."','".($key +1)."'); ";
		
			$this->db->query($sql);
			$pageid = $this->db->insert_id();
			//echo $pageid; exit;
			foreach($val['elements'] as $k => $v){
			//inset tabe aicrm_questionnaire_choice
				$hasOther = (isset($v['hasOther']) && $v['hasOther'] == true) ? 1 : 0;
				$isRequired =(isset($v['isRequired']) && $v['isRequired'] == true) ? 1 : 0;

				$sql_choice = "insert into aicrm_questionnaire_choice (questionnaireid,choice_type,pageid,choice_title,choice_name,sequence,hasother,required) ";
				$sql_choice .= " VALUES ('".$crmid."','".$v['type']."','".$pageid."','".@$v['title']."','".@$v['name']."','".($k+1)."','".$hasOther."','".$isRequired."'); ";
			
				$this->db->query($sql_choice);
				$choiceid = $this->db->insert_id();
				$data['pages'][$key]['elements'][$k]['choiceid'] = $choiceid;
				
				/*if(isset($a_return[$v['choiceid']][$v['choicedetailid']])){
					$a_return[$v['choiceid']][$v['choicedetailid']]['choiceid'] = $choiceid;
				}*/
				
				if($v['type'] == 'text'){
					//inset tabe aicrm_questionnaire_choicedetail (Type Text)
					$sql_choicedetail = "insert into aicrm_questionnaire_choicedetail (questionnaireid,choiceid,choicedetail_name,sequence_detail,choicedetail_other) ";
					$sql_choicedetail .= " VALUES ('".$crmid."','".$choiceid."','','1','0'); ";
					$this->db->query($sql_choicedetail);
				
					$choicedetailid = $this->db->insert_id();
					
					if(isset($a_return[$v['choiceid']][$v['text']])){
						$a_return[$v['choiceid']][$v['text']]['choiceid'] = $choiceid;
						$a_return[$v['choiceid']][$v['text']]['choicedetailid'] = $choicedetailid;
						$a_return[$v['choiceid']][$v['text']]['choicedetail_value'] = '';
						
					}

					$kc++;
				}else{
					$kc = 1 ;
					foreach($v['choices'] as $kchoice => $choice){

						$score="";

						foreach ($v['score'] as $kscore => $vscore) {

							if($choice==$kscore){
								$score = $vscore;
							}
						}

						if(is_array($choice)){
							$value = $choice['value'];
							$text = $choice['text'] ;
						}else{
							$value = $choice ;
							$text = $choice ;
						}
						//inset tabe aicrm_questionnaire_choicedetail
						$sql_choicedetail = "insert into aicrm_questionnaire_choicedetail (questionnaireid,choiceid,choicedetail_value,choicedetail_name,sequence_detail,choicedetail_other) ";
						$sql_choicedetail .= " VALUES ('".$crmid."','".$choiceid."','".$score."','".$value."','".($kchoice+1)."','0'); ";
					
						$this->db->query($sql_choicedetail);
						$choicedetailid = $this->db->insert_id();

						if(isset($a_return[$v['choiceid']][$v[$choice]])){
							$a_return[$v['choiceid']][$v[$choice]]['choiceid'] = $choiceid;
							$a_return[$v['choiceid']][$v[$choice]]['choicedetailid'] = $choicedetailid;
							$a_return[$v['choiceid']][$v[$choice]]['choicedetail_value'] = $score;
						}

						$kc++;
					}

					if(isset($v['otherText']) && $v['otherText'] != ''){

						$sql_choicedetail = "insert into aicrm_questionnaire_choicedetail (questionnaireid,choiceid,choicedetail_name,sequence_detail,choicedetail_other) ";
						$sql_choicedetail .= " VALUES ('".$crmid."','".$choiceid."','".$v['otherText']."','".$kc."','1'); ";	
					
						$this->db->query($sql_choicedetail);

						$choicedetailid = $this->db->insert_id();
						//alert($a_return[$v['choiceid']]); exit;
						if(isset($a_return[$v['choiceid']][$v[$v['otherText']]])){
							$a_return[$v['choiceid']][$v[$v['otherText']]]['choiceid'] = $choiceid;
							$a_return[$v['choiceid']][$v[$v['otherText']]]['choicedetailid'] = $choicedetailid;
							/*$a_return[$v['choiceid']][$v[$v['otherText']]]['choicedetail_value'] = $v['otherText'];*/
						}

					}
				}//else

			}//foreach elements
		}//foreach pages
		//Insert New Questionnaire Template
		//alert($a_return); exit;
		//Set format return
		$data = array();
		foreach ($a_return as $key => $value) {
			foreach ($value as $k => $v) {
				array_push($data,$v);
			}
		}
		//alert($data); exit;
		return @$data;
   	}

    private function set_format($a_data=array())
	{
		$data_template = array();
		$data_template['title'] = $a_data[0]['title_questionnaire'];
		$pageid = '';
		$i=-1;$c=0;
		foreach($a_data as $key => $val){
			if($pageid != $val['pageid']){
				$c=0;
				$i++;	
				$data_template["pages"][$i]['name'] = $val['name_page'];
				$data_template["pages"][$i]['title'] = $val['title_page'];
				if($val['choicedetail_other'] == 1){
					$data_template["pages"][$i]['otherText']  = $val['choicedetail_name'] ; //choicedetail_name
				}
				$data_template["pages"][$i]['elements'][$c]['choiceid'] = $val['choiceid'];
				$data_template["pages"][$i]['elements'][$c]['type'] =$val['choice_type'];
				$data_template["pages"][$i]['elements'][$c]['name'] =$val['choice_name'];
				$data_template["pages"][$i]['elements'][$c]['title'] =$val['choice_title'];
				$data_template["pages"][$i]['elements'][$c]['hasOther'] =(isset($val['hasother']) && $val['hasother'] == 1) ? true : false;//0=false ,1=true
				$data_template["pages"][$i]['elements'][$c]['isRequired'] =(isset($val['required']) && $val['required'] == 1) ? true : false;//0=false ,1=true
				if($val['choicedetail_other'] == 1){
					$data_template["pages"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
				}else if($val['choice_type'] != 'text'){
					$k=0;
					$data_template["pages"][$i]['elements'][$c]['choices'][$k] = $val['choicedetail_name'];
					$data_template["pages"][$i]['elements'][$c]['score'][$val['choicedetail_name']] = $val['choicedetail_value'];
				} 
				$choicedetail_name = (isset($val['choicedetail_name']) && $val['choicedetail_name'] == '') ? 'text' : $val['choicedetail_name'] ;
				$data_template["pages"][$i]['elements'][$c][$choicedetail_name ] =  $val['choicedetailid'];
				
				$pageid = $val['pageid'];
				$choiceid = $val['choiceid'];
				//$i++;	
			}else if($pageid == $val['pageid']){
					
					if($choiceid != $val['choiceid']){
						$c++;
						$data_template["pages"][$i]['elements'][$c]['choiceid'] =$val['choiceid'];
						$data_template["pages"][$i]['elements'][$c]['type'] =$val['choice_type'];
						$data_template["pages"][$i]['elements'][$c]['name'] =$val['choice_name'];
						$data_template["pages"][$i]['elements'][$c]['title'] =$val['choice_title'];
						$data_template["pages"][$i]['elements'][$c]['hasOther'] =(isset($val['hasother']) && $val['hasother'] == 1) ? true : false;//0=false ,1=true
						$data_template["pages"][$i]['elements'][$c]['isRequired'] =(isset($val['required']) && $val['required'] == 1) ? true : false;//0=false ,1=true
						if($val['choicedetail_other'] == 1){
							$data_template["pages"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
						}else if($val['choice_type'] != 'text'){
							$k=0;
							$data_template["pages"][$i]['elements'][$c]['choices'][$k] = $val['choicedetail_name'];
							$data_template["pages"][$i]['elements'][$c]['score'][$val['choicedetail_name']] = $val['choicedetail_value'];
						}
					}else{
						if($val['choicedetail_other'] == 1){
							$data_template["pages"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
						}else if($val['choice_type'] != 'text'){
							$k++;
							$data_template["pages"][$i]['elements'][$c]['choices'][$k] = $val['choicedetail_name'];
							$data_template["pages"][$i]['elements'][$c]['score'][$val['choicedetail_name']] = $val['choicedetail_value'];
						}
					}
					
					$choicedetail_name = (isset($val['choicedetail_name']) && $val['choicedetail_name'] == '') ? 'text' : $val['choicedetail_name'] ;
					$data_template["pages"][$i]['elements'][$c][$choicedetail_name] =  $val['choicedetailid'];

					$pageid = $val['pageid'];
					$choiceid = $val['choiceid'];
			}
		}//foreach
		//alert($data_template);exit;
		return $data_template;
	}

}
