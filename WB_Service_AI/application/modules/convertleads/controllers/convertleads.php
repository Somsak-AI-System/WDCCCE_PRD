<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Convertleads extends REST_Controller
{

	private $crmid;
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->library('lib_api_common');
		$this->load->model("Convertleads_model");
		$this->_limit = 100;
		$this->_module = "Leads";
		$this->_format = "array";
		
		$this->_return = array(
			'Type' => "S",
			'Message' => "Insert Complete",
			'cache_time' => date("Y-m-d H:i:s"),
			'data' => array(),
		);
	}


	public function convertleads_post()
	{
		echo 11;exit;
		// $request_body = file_get_contents('php://input');
		// $a_param     = json_decode($request_body,true);
		alert($a_param);exit;

	}

	private function set_param($a_param=array())
	{
		$a_condition = array();
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="" && $a_param["module"] == 'Job') {
			$a_condition["aicrm_jobs.jobid"] =  $a_param["crmid"] ;
		}
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="" && $a_param["module"] == 'Calendar') {
			$a_condition["aicrm_activity.activityid"] =  $a_param["crmid"] ;
		}

		return $a_condition;
	}

	private function set_order($a_orderby=array())
	{
		if(empty($a_orderby)) return false;

		$a_order = array();
		$a_condition = explode( "|",$a_orderby);

		for ($i =0;$i<count($a_condition) ;$i++)
		{
			list($field,$order) = explode(",", $a_condition[$i]);
			$a_order[$i]["field"] = $field;
			$a_order[$i]["order"] = $order;
		}

		return $a_order;
	}
	private function get_cache($a_params=array())
	{
		$this->load->library('managecached_library');
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';

		$action = @$a_params['action'];
		$module = @$a_params['module'];
		$crmid = @$a_params['crmid'];
		$userid = @$a_params['userid'];
		if($action=="convert" && $crmid!=""){

			$fieldid = array(
				'20','4530','4531','4525','4540','4533','4537','4567'
			);
			$fieldid = implode(',',$fieldid);

			$sql = "select DISTINCT columnname,tablename,fieldlabel,fieldname,uitype,generatedtype,typeofdata,block,readonly,maximumlength
			from aicrm_field where fieldid in (".$fieldid.")";
			$query = $this->db->query($sql);
			$data = $query->result_array();
			$blockname = "Convert Lead Information";


			$field = $this->crmentity->Get_field($module,$data,$blockname,$crm_id,$userid,$crm_subid);
			$a_form[] = $field[1];
			$data_title = $field[0];

		}

		$lead_sql ="select company , CONCAT(firstname,' ',lastname) as leadname
		from aicrm_leaddetails where leadid ='".$crmid."'";
		$query_lead = $this->db->query($lead_sql);
		$lead = $query_lead->result_array();
		$leadname = $lead[0]['leadname'];
		$leadcompany = $lead[0]['company'];

		if(!empty($a_form)){
			$form_value = $a_form[0]['form'];
			foreach ($form_value as $key => $value) {

				if($value['columnname']=="contactid"){
					$value['value_name'] = $leadname ;
					$value['value'] = $leadname ;
					$value['readonly'] = "1" ;
				}elseif ($value['columnname']=="accountid") {
					$value['value_name'] = $leadcompany ;
					$value['value'] = $leadcompany ;
					$value['readonly'] = "1" ;
				}elseif ($value['columnname']=="opportunity_name") {
					$value['value_name'] = $leadcompany ;
					$value['value'] = $leadcompany ;
				}elseif ($value['columnname']=="amount") {
					$value['fieldlabel'] = "Opportunity Amount" ;
				}elseif ($value['columnname']=="sales_stage") {
					$value['fieldlabel'] = "Opportunity Sales Stage" ;
				}elseif ($value['columnname']=="expected_close_date") {
					$value['fieldlabel'] = "Opportunity Close Date" ;
				}elseif ($value['columnname']=="smownerid") {
				}
				$form_value[$key] = $value;
			}

			$a_data['status'] = 'S';
			$a_data['message'] = 'Success';
		}else {
			$a_data['status'] = 'E';
			$a_data['message'] = 'No data';
		}

		$a_form[0]['form'] = $form_value;
		$a_data['data'] = $a_form;

		return $a_data;
	}

	private function get_data($a_data=NULL,$module=NULL)
	{
		if(!empty($a_data["result"]["data"]) && $a_data["status"] ){

			if($module == 'Calendar'){
				$crmid = 'activityid';
			}else{
				$crmid = 'jobid';
			}

			foreach ($a_data["result"]["data"] as $key =>$val){
				$id = $val[$crmid];
				$a_activity[] = $id;
			}
			if($module == 'Calendar'){
				$a_conditionin["aicrm_activity.activityid"] = $a_activity;
			}else{
				$a_conditionin["aicrm_jobs.jobid"] = $a_activity;
			}


			$a_image = $this->common->get_a_image($a_conditionin,$module);
			foreach ($a_data["result"]["data"] as $key =>$val){

				$job_id = $val[$crmid];
				$a_return["image"] =( !empty($a_image[$job_id]["image"]))?$a_image[$job_id]["image"] :array();
				$a_response[] = $a_return;
			}
			$a_data["result"]["data"] = $a_response;
		}

		return $a_data;
	}

	public function form_convert_get()
	{
		$a_param =  $this->input->get();
		$a_data =$this->get_cache($a_param);
		$module = $a_param['module'];
		$this->return_data($a_data,$module,$a_param);
	}

	public function form_convert_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$module = $a_param['module'];
		$a_data =$this->get_cache($a_param);
		$this->return_data($a_data,$module,$a_param);
	}

	public function return_data($a_data,$module,$a_param)
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["message"];
			$a_return["data"][]['result'][] = !empty($a_data['data'][0]) ? $a_data['data'][0] : "" ;

			$log_filename = "Form_Convert_Leads";
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->common->_filename= $log_filename;
			$this->common->set_log($url,$a_param,$a_return);

			if ($format!="json" && $format!="xml"  ) {
				$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
			$this->response(array('error' => 'Couldn\'t find any Parcel!'), 404);
		}
	}

	public function convert_lead_post(){

		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
		$a_data = $dataJson;

		$crmid = @$dataJson['crmid'];
		$module = @$dataJson['module'];
		$userid = @$dataJson['userid'];
		$data_form = @$dataJson['data'][0];
		$smownerid = @$dataJson['data'][0]['smownerid'];
		$opportunity_name = @$dataJson['data'][0]['opportunity_name'];
		$amount = @$dataJson['data'][0]['amount'];
		$sales_stage = @$dataJson['data'][0]['sales_stage'];
		$expected_close_date = @$dataJson['data'][0]['expected_close_date'];
		$projects_name = @$dataJson['data'][0]['projects_name'];
		$no = "";
		$name = "";

		if($module=="Case"){
			$module="HelpDesk";
		}elseif ($module=="Spare Part" || $module=="SparePart") {
			$module = "Sparepart";
		}elseif ($module=="Errors List" || $module=="ErrorsList" ) {
			$module = "Errorslist";
		}elseif ($module=="Spare Part List" || $module=="SparePartList") {
			$module = "Sparepartlist";
		}elseif ($module=="Calendar" || $module=="Sales Visit" ||$module=="Sale Visit" ||$module=="Events") {
			$module = "Calendar";
		}elseif ($module=="Projectorders" || $module=="Project Orders" || $module=="Projectorder") {
			$module = "Projectorder";
		}

		$select_name = $this->crmentity->Get_QuerySelect($module);
		$query_name = $this->crmentity->Get_Query($module);

		if($select_name!="" && $query_name!=""){
			$sql_name = "SELECT ".$select_name." ".$query_name."  and crmid='".$crmid."' ";
			$query_name = $this->db->query($sql_name);
			if(!empty($query_name)){
				$lead_name = $query_name->result(0);
				$no = $lead_name[0]['no'];
				$name = $lead_name[0]['name'];
			}
		}

		$sql_leadid = " SELECT aicrm_convertleadmapping.leadfid as fieldid,aicrm_field.columnname  from aicrm_convertleadmapping
		LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_convertleadmapping.leadfid " ;
		$query_lead = $this->db->query($sql_leadid);
		$field_lead = $query_lead->result(0);
		
		$fieldname = array();


		foreach ($field_lead as $key => $value) {
			$fieldname[] = $value['columnname'];
		}

		if(!empty($fieldname)){

			$data_fieldname = implode(",",$fieldname);
			$get_value = " select ".$data_fieldname." ,aicrm_leaddetails.converted
			FROM aicrm_leaddetails
			LEFT JOIN aicrm_leadsubdetails ON aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid
			LEFT JOIN aicrm_leadaddress ON aicrm_leadaddress.leadaddressid = aicrm_leadsubdetails.leadsubscriptionid
			INNER JOIN aicrm_leadscf ON aicrm_leaddetails.leadid = aicrm_leadscf.leadid
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
			WHERE aicrm_crmentity.deleted = 0  and aicrm_crmentity.crmid ='".$crmid."' ";	
			$query_value = $this->db->query($get_value);
			$field_value = $query_value->result(0);
			$converted = $field_value[0]['converted'];
			

		}
	
		if($converted=="0"){

			$accountname = $field_value[0]['company'];
			$contactname = $field_value[0]['firstname']." ".$field_value[0]['lastname'];
			$firstname = $field_value[0]['firstname'];
			$lastname = $field_value[0]['lastname'];
			$opportunityname = $field_value[0]['company'];
			$projectsname = $field_value[0]['company'];

			if($opportunity_name!=""){
				$opportunityname=$opportunity_name;
			}

			if($projects_name!=""){
				$projectsname = $projects_name;
			}


			$sql_acc = "select accountid from aicrm_account
			left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
			where accountname='".$accountname."'  and aicrm_crmentity.deleted='0'" ;
			$query = $this->db->query($sql_acc);
			$result_acc = $query->result(0);
			$accountid_ex = $result_acc[0]['accountid'];

			if(empty($result_acc)){  //insert account
				$insert_account = $this->lib_api_common->convert_account($field_value,$userid,$smownerid);
				$accountid=$insert_account['accountid'];
				$accountid_ex=$accountid;			
			}

			$sql_con = "select contactid from aicrm_contactdetails
			left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
			where firstname='".$firstname."' and lastname = '".$lastname."' and aicrm_crmentity.deleted='0' " ;
			$query = $this->db->query($sql_con);
			$result_con = $query->result(0);
			$contactid_ex = $result_con[0]['contactid'];
			
			if(empty($result_con)){ //inset contact
				$insert_contact = $this->lib_api_common->convert_contact($field_value,$userid,$accountid_ex,$smownerid);
				$contactid=$insert_contact['contactid'];
				$contactid_ex = $contactid;
			}

			$sql_prj = "select projectid from aicrm_project
			left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_project.projectid
			where project_name='".$projectsname."'  and aicrm_crmentity.deleted='0' " ;
			$query = $this->db->query($sql_prj);
			$result_prj = $query->result(0);
			$projectsid_ex = $result_prj[0]['projectid'];
			
			if(empty($result_prj)){ //inset projects
				$insert_projects = $this->lib_api_common->convert_projects($field_value,$userid,$accountid_ex,$smownerid,$data_form);
				$projectsid = $insert_projects['projectid'];
				$projectsid_ex = $projectsid;
			}

			$sql_opp = "select opportunityid from aicrm_opportunity
			left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_opportunity.opportunityid
			where  opportunity_name='".$opportunityname."' and aicrm_crmentity.deleted='0' " ;
			$query = $this->db->query($sql_opp);
			$result_opp = $query->result(0);

			if(empty($result_opp)){ //inset contact
				$insert_opportunity = $this->lib_api_common->convert_opportunity($field_value,$userid,$accountid_ex,$contactid_ex,$data_form);
			}

			if($insert_account['status']=="Success" && $insert_contact['status']=="Success" && $insert_projects['status']=="Success" && $insert_opportunity['status']=="Success")
			{

				$sql="UPDATE aicrm_leaddetails set converted='1' ,accountid='".$accountid_ex."',contactid='".$contactid_ex."' ,projectid='".$projectsid_ex."' ,lead_status='Convertd'
				where leadid='".$crmid."'";
				if($this->db->query($sql)){
					$a_return["Type"] =  "S";
					$a_return["Message"] =  "Convert Complete";
					$a_return["data"]['Crmid'] = $crmid;
					$a_return["data"]['Name'] = $name;
					$a_return["data"]['No'] = $no;

				}else {
					$a_return["Type"] =  "E";
					$a_return["Message"] =  "Convert Fail";
					$a_return["data"] = array();
				}
			}else {
				$a_return["Type"] =  "E";
				$a_return["Message"] =  "This lead already exists.";
				$a_return["data"] = array();
			}

		}else {
			$a_return["Type"] =  "E";
			$a_return["Message"] =  "This lead already exists.";
			$a_return["data"] = array();
			$data = array();
			array_push($a_return["data"] , $data);
		}

		$response_data = array_merge($this->_return,$a_return);

		$log_filename = "Convert_Leads";
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= $log_filename;
		$this->common->set_log($url,$a_data,$response_data);

		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}

	}


	public function form_convertit_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$module = $a_param['module'];
		$crmid = $a_param['crmid'];
		$userid = $a_param['userid'];

		$a_data =  $this->check_fieldconvert($crmid,$userid);

		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["message"];
			$a_return["data"][]['result'][] = !empty($a_data['data']) ? $a_data['data'] : "" ;

			$log_filename = "Form_ConvertIT_Leads";
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->common->_filename= $log_filename;
			$this->common->set_log($url,$a_param,$a_return);

			if ($format!="json" && $format!="xml"  ) {
				$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
			$this->response(array('error' => 'Couldn\'t get Form Convert IT!'), 404);
		}
	}


	public function check_fieldconvert($crmid,$userid){

		$data_return = array();

		$where = "and aicrm_field.fieldname in ('solution_type','solution','solution_description','lead_status','present','present_date','closed_date','project_code')";

		$sql = "select DISTINCT aicrm_field.columnname, aicrm_field.tablename,aicrm_field.fieldlabel,aicrm_field.fieldname,aicrm_field.uitype,aicrm_field.generatedtype,aicrm_field.typeofdata,aicrm_field.block,aicrm_field.readonly,aicrm_field.maximumlength
		from  aicrm_users
		LEFT JOIN aicrm_user2role on aicrm_user2role.userid =aicrm_users.id
		LEFT JOIN aicrm_role2profile on aicrm_role2profile.roleid = aicrm_user2role.roleid
		LEFT JOIN aicrm_profile2tab on aicrm_profile2tab.profileid = aicrm_role2profile.profileid
		LEFT JOIN aicrm_profile2field on aicrm_profile2field.profileid = aicrm_role2profile.profileid
		LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_profile2field.fieldid
		where 1 and  aicrm_field.presence in (0,2) and aicrm_field.quickcreate in(0,2,3) and aicrm_profile2field.visible not in(1)
		and aicrm_field.tabid='7'  and aicrm_users.id ='".$userid."' ".$where."  ORDER BY aicrm_field.fieldid asc ";


		$query = $this->db->query($sql);
		$data_field = $query->result_array();

		$sql_leadfield = "SELECT * from tbt_step_convert_validate
		WHERE approved_status ='Convertd IT' ";

		$query_leadfield = $this->db->query($sql_leadfield);
		$response_leadfield = $query_leadfield->result_array();
		$lead_fieldname = $response_leadfield[0]['fieldname'];
		$lead_fieldname = explode(',', $lead_fieldname);

		if($lead_fieldname!="" && $data_field!=""){
			foreach ($data_field as $key => $value) {

				foreach ($lead_fieldname as $k => $v) {

					if($value['fieldname']==$v){
						$value['typeofdata'] = "V~M";

					}
				}

				$data_field[$key] = $value;

			}

		}


		if(!empty($data_field)){

					$present_date = false ;
					$closed_date = false ;
					$check_value="";

					$blockname = "Convert IT Information";
					$module = "Leads";
					$module = "Leads";
					$field = $this->crmentity->Get_field($module,$data_field,$blockname,$crmid,$userid,$crm_subid="",$related_module="",$action="edit");
					$set_readonly = $field[1]['form'] ;


					foreach ($set_readonly as $key => $value) {
						if($value['columnname'] =="lead_status" ){
							$set_readonly[$key]['readonly'] = "1" ;
						}elseif ($value['columnname'] =="solution_type") {
							$set_readonly[$key]['readonly'] = "1" ;
						}elseif ($value['columnname'] =="solution") {
							$set_readonly[$key]['readonly'] = "1" ;
						}elseif ($value['columnname'] =="solution_description") {
							$set_readonly[$key]['readonly'] = "1" ;
						}

						if($value['columnname']=="present" || $value['columnname']=="present_date" || $value['columnname']=="closed_date" || $value['columnname']=="project_code"){

							list($data_present,$data_set,$data_status) = $this->crmentity->check_valueCheckbox($crmid);

							if($value['columnname']=="present" ){
								$uitype= "959"; 
                           		$set_field="";
                           		$value_present = $data_present;
								$set_field = $data_set;
								   
								   if($data_present=='1'||$data_present==1){
                       
									$present_date = true ;
									$closed_date = false ;
									$check_value = "yes";
									
								  }else{
									$present_date = false ;
									$closed_date = true ;
									$check_value = "no";
								  }

								$set_readonly[$key]['relate_checkbox'] = $set_field;
								$set_readonly[$key]['uitype'] = $uitype;
								$set_readonly[$key]['check_value'] = $check_value;

							}

							if($value['columnname']=="present_date"){

								if($present_date==true){
									$check_value = "yes";
									$set_readonly[$key]['error_message'] = "Present Date cannot be empty";
									$set_readonly[$key]['check_value'] = $check_value;
								  }else{
									$check_value = "no";
									$set_readonly[$key]['check_value'] = $check_value;
								  }

								  $set_readonly[$key]['relate_checkbox'] = array();
	  
							}

							if($value['columnname']=="closed_date"){

								if($closed_date==true){
                        
									$check_value = "yes";
									$set_readonly[$key]['error_message'] = "Closed Date cannot be empty";
									$set_readonly[$key]['check_value'] = $check_value;
									
								}else{
									$check_value = "no";
									$set_readonly[$key]['check_value'] = $check_value;
								}
						  
								$set_readonly[$key]['relate_checkbox'] = array();

							}

							if($value['columnname']=="project_code"){
                            
								$set_readonly[$key]['typeofdata'] = "V~M";
								$set_readonly[$key]['error_message'] = "Project Code cannot be empty";
							}

						}else{
							$set_readonly[$key]['relate_checkbox'] = array();
						}

					}

					$field[1]['form'] = $set_readonly;


					if(!empty($field)){
						$data_return['status'] = "S";
						$data_return['message'] = "get feild Success";
						$data_return['data'] = $field[1];
					}else {
						$data_return['status'] = "E";
						$data_return['message'] = "get feild Fail";
						$data_return['data'] = "";
					}



		}else {
			$data_return['status'] = "E";
			$data_return['message'] = "get feild Fail";
			$data_return['data'] = "";
		}

		return $data_return;

	}


	public function  convert_it_post(){

		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
		$a_data = $dataJson;

		$crmid = @$dataJson['crmid'];
		$module = @$dataJson['module'];
		$userid = @$dataJson['userid'];
		$data = @$dataJson['data'][0];

		$solution_type = @$dataJson['data'][0]['solution_type'];
		$solution = @$dataJson['data'][0]['solution'];
		$solution_description = @$dataJson['data'][0]['solution_description'];
		$lead_status = @$dataJson['data'][0]['lead_status'];
		$present = @$dataJson['data'][0]['present'];
		$present_date = @$dataJson['data'][0]['present_date'];
		$closed_date = @$dataJson['data'][0]['closed_date'];
		$project_code = @$dataJson['data'][0]['project_code'];

		$no = "";
		$name = "";

		$select_name = $this->crmentity->Get_QuerySelect($module);
		$query_name = $this->crmentity->Get_Query($module);

		if($select_name!="" && $query_name!=""){
			$sql_name = "SELECT ".$select_name." ".$query_name."  and crmid='".$crmid."' ";
			$query_name = $this->db->query($sql_name);
			if(!empty($query_name)){
				$lead_name = $query_name->result(0);
				$no = $lead_name[0]['no'];
				$name = $lead_name[0]['name'];
			}
		}


		$update_lead = "UPDATE aicrm_leaddetails SET solution_type='".$solution_type."' , solution='".$solution."' , solution_description='".$solution_description."' ,
		 lead_status='Convertd IT' , present='".$present."' , present_date='".$present_date."' , closed_date='".$closed_date."', project_code='".$project_code."',
		 converted='1'
		  WHERE leadid='".$crmid."'" ;

		if($this->db->query($update_lead)){
			$a_return["Type"] =  "S";
			$a_return["Message"] =  "Convert IT Complete";
			$a_return["data"] = array("Crmid"=>$crmid,"Name"=>$name,"No"=>$no);

		}else {
			$a_return["Type"] =  "E";
			$a_return["Message"] =  "Convert IT Fail";
			$a_return["data"] = array("Crmid"=>$crmid,"Name"=>$name,"No"=>$no);
		}

		$response_data = array_merge($this->_return,$a_return);

		$log_filename = "ConvertIT_Leads";
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->_filename= $log_filename;
		$this->common->set_log($url,$a_data,$response_data);

		if ( $response_data ) {
			$this->response($response_data, 200); // 200 being the HTTP response code
		} else {
			$this->response(array(
				'error' => 'Couldn\'t find Set Content!'
			), 404);
		}

	}





}
