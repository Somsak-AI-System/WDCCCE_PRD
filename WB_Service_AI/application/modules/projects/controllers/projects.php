<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Projects extends REST_Controller
{
  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_projects','aicrm_projectscf');
  private $tab_name_index =  array('aicrm_crmentity'=>'crmid','aicrm_projects'=>'projectsid' ,'aicrm_projectscf'=>'projectsid');
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("projects_model");
		$this->_module = "Projects";
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
				'ProjectsNo' => null
			),
		);
	}
	
	public function insert_content_post(){

	  	$this->common->_filename= "Insert_Projects";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Projects ==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert Projects ==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function get_insert_data($a_request){

	  	$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
		$DocNo=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  	
	  		if(count($data[0])>0 and $module=="Projects"){

					list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
		
	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  						'Crmid' => $crmid,
	  						'DocumentsNo' => $DocNo,

	  				);
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

	public function get_projects_post(){

		$this->common->_filename= "Detail_Projects";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$this->common->set_log('Before Detail Projects ==>',$url,$a_request);

		$response_data = $this->projects_model->get_projects($a_request);

	  	$this->common->set_log('After Detail Projects ==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	public function get_detail_list_post()
	{	
		$this->common->_filename= "Get_Projects_Detail";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$a_request =$dataJson;
		
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$this->common->set_log('Before Projects Detail ==>',$url,$a_request);

		$crmID = $a_request['crmid'];
		$sql = $this->db->get_where('aicrm_projects', ['projectsid'=>$crmID]);
		$rowData = $sql->row_array();
		
		/*Owner*/
		$this->db->select('aicrm_inventoryowner.*, 
		ifnull(aicrm_account.accountname,"") as accountname,
		ifnull(aicrm_contactdetails.contactname,"") as contactname' , false);
		$this->db->join('aicrm_account', 'aicrm_account.accountid=aicrm_inventoryowner.accountid','inner');
		$this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid=aicrm_inventoryowner.contactid', 'left');
		$this->db->where(array('aicrm_inventoryowner.id' => $crmID));
		$this->db->order_by("aicrm_inventoryowner.sequence_no", "asc");
		$sql = $this->db->get('aicrm_inventoryowner');
		$itemList['owner'] = $sql->result_array();
		
		/*Consultant*/
		$this->db->select('aicrm_inventoryconsultant.*, 
		ifnull(aicrm_account.accountname,"") as accountname,
		ifnull(aicrm_contactdetails.contactname,"") as contactname' , false);
		$this->db->join('aicrm_account', 'aicrm_account.accountid=aicrm_inventoryconsultant.accountid','inner');
		$this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid=aicrm_inventoryconsultant.contactid', 'left');
		$this->db->where(array('aicrm_inventoryconsultant.id' => $crmID));
		$this->db->order_by("aicrm_inventoryconsultant.sequence_no", "asc");
		$sql = $this->db->get('aicrm_inventoryconsultant');
		$itemList['consultant'] = $sql->result_array();

		/*Architecture*/
		$this->db->select('aicrm_inventoryarchitecture.*, 
		ifnull(aicrm_account.accountname,"") as accountname,
		ifnull(aicrm_contactdetails.contactname,"") as contactname' , false);
		$this->db->join('aicrm_account', 'aicrm_account.accountid=aicrm_inventoryarchitecture.accountid','inner');
		$this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid=aicrm_inventoryarchitecture.contactid', 'left');
		$this->db->where(array('aicrm_inventoryarchitecture.id' => $crmID));
		$this->db->order_by("aicrm_inventoryarchitecture.sequence_no", "asc");
		$sql = $this->db->get('aicrm_inventoryarchitecture');
		$itemList['architecture'] = $sql->result_array();

		/*Construction*/
		$this->db->select('aicrm_inventoryconstruction.*, 
		ifnull(aicrm_account.accountname,"") as accountname,
		ifnull(aicrm_contactdetails.contactname,"") as contactname' , false);
		$this->db->join('aicrm_account', 'aicrm_account.accountid=aicrm_inventoryconstruction.accountid','inner');
		$this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid=aicrm_inventoryconstruction.contactid', 'left');
		$this->db->where(array('aicrm_inventoryconstruction.id' => $crmID));
		$this->db->order_by("aicrm_inventoryconstruction.sequence_no", "asc");
		$sql = $this->db->get('aicrm_inventoryconstruction');
		$itemList['construction'] = $sql->result_array();

		/*Designer*/
		$this->db->select('aicrm_inventorydesigner.*, 
		ifnull(aicrm_account.accountname,"") as accountname,
		ifnull(aicrm_contactdetails.contactname,"") as contactname' , false);
		$this->db->join('aicrm_account', 'aicrm_account.accountid=aicrm_inventorydesigner.accountid','inner');
		$this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid=aicrm_inventorydesigner.contactid', 'left');
		$this->db->where(array('aicrm_inventorydesigner.id' => $crmID));
		$this->db->order_by("aicrm_inventorydesigner.sequence_no", "asc");
		$sql = $this->db->get('aicrm_inventorydesigner');
		$itemList['designer'] = $sql->result_array();

		/*Contractor*/
		$this->db->select('aicrm_inventorycontractor.*, 
		ifnull(aicrm_account.accountname,"") as accountname,
		ifnull(aicrm_contactdetails.contactname,"") as contactname' , false);
		$this->db->join('aicrm_account', 'aicrm_account.accountid=aicrm_inventorycontractor.accountid','inner');
		$this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid=aicrm_inventorycontractor.contactid', 'left');
		$this->db->where(array('aicrm_inventorycontractor.id' => $crmID));
		$this->db->order_by("aicrm_inventorycontractor.sequence_no", "asc");
		$sql = $this->db->get('aicrm_inventorycontractor');
		$itemList['contractor'] = $sql->result_array();

		/*Subcontractor*/
		$this->db->select('aicrm_inventorysubcontractor.*, 
		ifnull(aicrm_account.accountname,"") as accountname,
		ifnull(aicrm_contactdetails.contactname,"") as contactname' , false);
		$this->db->join('aicrm_account', 'aicrm_account.accountid=aicrm_inventorysubcontractor.accountid','inner');
		$this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid=aicrm_inventorysubcontractor.contactid', 'left');
		$this->db->where(array('aicrm_inventorysubcontractor.id' => $crmID));
		$this->db->order_by("aicrm_inventorysubcontractor.sequence_no", "asc");
		$sql = $this->db->get('aicrm_inventorysubcontractor');
		$itemList['subcontractor'] = $sql->result_array();

		/*Products*/
		$this->db->select('aicrm_inventoryprojects.*, 
		ifnull(aicrm_account.accountname,"") as accountname , 
		ifnull(aicrm_products.productname,"") as productname' , false);
		$this->db->join('aicrm_products', 'aicrm_products.productid=aicrm_inventoryprojects.productid','inner');
		$this->db->join('aicrm_account', 'aicrm_account.accountid=aicrm_inventoryprojects.accountid', 'left');
		$this->db->where(array('aicrm_inventoryprojects.id' => $crmID));
		$this->db->order_by("aicrm_inventoryprojects.sequence_no", "asc");
		$sql = $this->db->get('aicrm_inventoryprojects');
		$itemList['products'] = $sql->result_array();

		/*Competitor*/
		$this->db->select('aicrm_inventorycompetitorproduct.*, 
		ifnull(aicrm_competitorproduct.competitorproduct_name_th,"") as competitorproduct_name_th', false);
		$this->db->join('aicrm_competitorproduct', 'aicrm_competitorproduct.competitorproductid=aicrm_inventorycompetitorproduct.competitorproductid','inner');
		$this->db->where(array('aicrm_inventorycompetitorproduct.id' => $crmID));
		$this->db->order_by("aicrm_inventorycompetitorproduct.sequence_no", "asc");
		$sql = $this->db->get('aicrm_inventorycompetitorproduct');
		$itemList['competitor'] = $sql->result_array();

		$returnData = $this->_return;
		$returnData['data']['Crmid'] = $crmID;
		$returnData['data']['ProjectsNo'] = $rowData['projects_no'];
		$returnData['data']['rowData'] = $rowData;
		$returnData['data']['itemList'] = $itemList;

		$this->response($returnData, 200);
	}

	public function checkDup_post()
	{
		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body,true);
		$action = $req['action'];

		$this->db->select('COUNT(*) AS count');
		$this->db->from('aicrm_projects');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_projects.projectsid', 'inner');
		$this->db->where([
			'aicrm_crmentity.deleted' => 0,
			'aicrm_projects.projects_name' => $req['projects_name'],
			'aicrm_projects.project_location' => $req['project_location'],
		]);

		if($action == 'edit'){
			$this->db->where(['aicrm_projects.projectsid !=' => $req['crmid']]);
		}

		$sql = $this->db->get(); // echo $this->db->last_query(); exit();
		$res = $sql->row_array();

		$this->response(['count' => $res['count']], 200);
	}

	public function update_status_post()
	{
		$this->common->_filename= "Insert_Projects";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Projects ==>',$url,$a_request);
		$response_data = $this->get_update_data($a_request);	
	  	$this->common->set_log('After Insert Projects ==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function get_update_data($a_request){

	  	$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
		$DocNo=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  
	  		if(count($data[0])>0 and $module=="Projects"){

					list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
		
	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
	  				$a_return["data"] =array(
	  						'Crmid' => $crmid,
	  						'DocumentsNo' => $DocNo,

	  				);
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

	public function addcomment_post()
	{
		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body,true);

		$crmID = $req['crmid'];
		$userID = $req['userid'];
		$projectorder_status = $req['projectorder_status'];
		$message = $req['message'];
		$updateData = [];

		$this->db->insert('aicrm_projectscomments', [
			'projectsid' => $crmID,
			'comments' => $message,
			'projectorder_status' => $projectorder_status,
			'ownerid' => $userID,
			'ownertype' => 'user',
			'createdtime' => date('Y-m-d H:i:s')
		]);

		$insert_id = $this->db->insert_id();

		$query = "select aicrm_projectscomments.comments, aicrm_projectscomments.projectorder_status, aicrm_projectscomments.ownerid, 
					date_format(aicrm_projectscomments.createdtime, '%d-%m-%Y %T' ) as createdtime,
					concat(aicrm_users.first_name,' ',aicrm_users.last_name) as username
					from aicrm_projectscomments
					inner join aicrm_users on aicrm_users.id = aicrm_projectscomments.ownerid
					where aicrm_projectscomments.commentid = '".$insert_id."'";
		$sql = $this->db->query($query);
		$rs_comment = $sql->row_array();

		$returnData = $this->_return;
		$returnData['Message'] = 'Add Comments Success';
		$returnData['data']['Crmid'] = $crmID;
		$returnData['comment'] = $rs_comment;

		$this->response($returnData, 200);
	}

	public function getComments_post()
	{

		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body, true);

		$crmID = $req['crmid'];

		$query = "select aicrm_projectscomments.comments, aicrm_projectscomments.projectorder_status, aicrm_projectscomments.ownerid, 
			date_format(aicrm_projectscomments.createdtime, '%d-%m-%Y %T' ) as createdtime,
			concat(aicrm_users.first_name,' ',aicrm_users.last_name) as username
			from aicrm_projectscomments
			inner join aicrm_users on aicrm_users.id = aicrm_projectscomments.ownerid
			where aicrm_projectscomments.projectsid = '".$crmID."' order by aicrm_projectscomments.commentid desc; ";

		$sql = $this->db->query($query);
		$rs = $sql->result_array();

		$returnData = $this->_return;
		$returnData['Message'] = 'Success';
		$returnData['data']['Crmid'] = $crmID;
		$returnData['comment'] = $rs;
		$this->response($returnData, 200);
	}

	public function getStatus_post()
	{
		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body, true);

		$crmID = $req['crmid'];

		$query = "select projectorder_status
			from aicrm_projects
			inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_projects.projectsid
			where aicrm_projects.projectsid = '".$crmID."' AND aicrm_crmentity.deleted = 0;";
		$sql = $this->db->query($query);
		$rs = $sql->result_array();
		
		$qs = "select projectorder_status from aicrm_projectorder_status where presence =1 and projectorder_status != '--None--';";
		$sqls = $this->db->query($qs);
		$r_status = $sqls->result_array();
		$returnData = $this->_return;
		$returnData['Message'] = 'Success';
		$returnData['data']['Crmid'] = $crmID;
		$returnData['value'] = @$rs[0]['projectorder_status'];
		$returnData['status'] = $r_status;
		$this->response($returnData, 200);
	}

	public function getTimeline_post()
	{

		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body, true);

		$crmID = $req['crmid'];
		/*TimeLine*/
		$query = "select aicrm_activity_timeline.id,
			aicrm_activity_timeline.crmid,
			aicrm_activity_timeline.action,
			aicrm_activity_timeline.userid,
			aicrm_activity_timeline.createdtime,
			DATE_FORMAT(aicrm_activity_timeline.createdtime,'%d %M, %Y at %T %p') as format_date ,
		    DATE_FORMAT(aicrm_activity_timeline.createdtime,'%d %b, %Y') as date_create ,
		    DATE_FORMAT(aicrm_activity_timeline.createdtime,'%H:%i %p') as time_create ,
			aicrm_activity_timeline_detail.id,
			aicrm_activity_timeline_detail.fieldid,
			aicrm_activity_timeline_detail.old_value,
			aicrm_activity_timeline_detail.new_value,
			aicrm_field.fieldlabel,
			aicrm_field.uitype, 
			aicrm_users.user_name,
			CASE WHEN ifnull(aicrm_attachments.path,'') != '' THEN concat(aicrm_attachments.path,aicrm_attachments.attachmentsid,'_',aicrm_attachments.name)  ELSE '' END AS profile_image
		from aicrm_activity_timeline 
		LEFT JOIN aicrm_activity_timeline_detail on aicrm_activity_timeline_detail.activitytimelineid = aicrm_activity_timeline.id
		LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_activity_timeline_detail.fieldid
		INNER JOIN aicrm_users on aicrm_users.id = aicrm_activity_timeline.userid
		LEFT JOIN aicrm_salesmanattachmentsrel ON aicrm_salesmanattachmentsrel.smid = aicrm_activity_timeline.userid
		LEFT JOIN aicrm_attachments ON aicrm_attachments.attachmentsid = aicrm_salesmanattachmentsrel.attachmentsid
		where aicrm_activity_timeline.crmid = '".$crmID."'
		ORDER BY aicrm_activity_timeline.id DESC,aicrm_activity_timeline_detail.id DESC; ";
		$sql = $this->db->query($query);
		$data_timeline = $sql->result_array();
		/*TimeLine*/

		/*Users*/
		$q_user = "select
			aicrm_activity_timeline.userid,
			aicrm_users.user_name
			from aicrm_activity_timeline 
			INNER JOIN aicrm_users on aicrm_users.id = aicrm_activity_timeline.userid
			where aicrm_activity_timeline.crmid = '".$crmID."'
			GROUP BY aicrm_activity_timeline.userid 
			ORDER BY aicrm_users.user_name ASC;";
		$sql_user = $this->db->query($q_user);
		$data_user = $sql_user->result_array();
		/*Users*/

		/*Field*/
		$q_field = "select
			aicrm_activity_timeline_detail.fieldid,
			aicrm_field.fieldlabel
			FROM aicrm_activity_timeline 
			INNER JOIN aicrm_activity_timeline_detail on aicrm_activity_timeline_detail.activitytimelineid = aicrm_activity_timeline.id
			INNER JOIN aicrm_field on aicrm_field.fieldid = aicrm_activity_timeline_detail.fieldid
			WHERE aicrm_activity_timeline.crmid = '".$crmID."'
			GROUP BY aicrm_activity_timeline_detail.fieldid
			ORDER BY aicrm_field.fieldlabel ASC;";
		$sql_field = $this->db->query($q_field);
		$data_field = $sql_field->result_array();
		/*Field*/

		$returnData = $this->_return;
		$returnData['Message'] = 'Success';
		$returnData['data']['Crmid'] = $crmID;
		$returnData['timeline'] = $data_timeline;
		$returnData['users'] = $data_user;
		$returnData['field'] = $data_field;
		$this->response($returnData, 200);
	}

	public function getProductPlan_post()
	{
		$request_body = file_get_contents('php://input');
		$dataJson = json_decode($request_body, true);
	  	$response_data = null;
	  	$a_request = $dataJson;

		$crmid = $a_request['crmid'];
		$productid = $a_request['productid'];

		$query = "SELECT aicrm_projects.projects_no , aicrm_projects.projects_name , aicrm_products.product_no , aicrm_products.productname , aicrm_inventoryprojects.estimated 
			FROM aicrm_inventoryprojects
			inner join aicrm_projects on aicrm_projects.projectsid = aicrm_inventoryprojects.id
			inner join aicrm_products on aicrm_products.productid = aicrm_inventoryprojects.productid
			WHERE aicrm_inventoryprojects.id = '".$crmid."' AND aicrm_inventoryprojects.productid = '".$productid."';";
		$sql = $this->db->query($query);
		$data = $sql->result_array();

		$query_plan = "select
			lineitem_id,
			DATE_FORMAT(plan_date,'%d/%m/%Y') as date_plan,
			plan_qty as qty
			from aicrm_inventoryproductplan
			where productid = '".$productid."' and id = '".$crmid."'
			and deleted = 0
			order by lineitem_id desc;";
		$sql_plan = $this->db->query($query_plan);
		$data_plan = $sql_plan->result_array();
		

		$response_data = $this->_return;
		$response_data['Message'] = 'Success';
		$response_data['data']['Crmid'] = $crmID;
		$response_data['data'] = $data;
		$response_data['plan'] = $data_plan;

		$this->response($response_data, 200);
	}

	public function insert_ProductPlan_post()
	{
		$request_body = file_get_contents('php://input');
		$dataJson = json_decode($request_body, true);
	  	$response_data = null;
	  	$a_request = $dataJson;
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "add";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  	
		$projectid = $data[0]['projectid'];
		$productid = $data[0]['productid'];
		$product_plan_date = $data[0]['product_plan_date'];
		$product_qty = $data[0]['product_qty'];
		$lineitem = $data[0]['lineitem'];
		$Planlineitem_id = $data[0]['Planlineitem_id'];
		$updateData = [];

		if($action=='edit'){
			$data = array(
			    'plan_date' => $product_plan_date,
			    'plan_qty' => $product_qty,
			    'modifiedby' => $userid,
			    'modifiedtime' => date('Y-m-d H:i:s')
			);
		  	$this->db->where('lineitem_id', $Planlineitem_id);
			$this->db->update('aicrm_inventoryproductplan', $data);

			$insert_id = $Planlineitem_id;
		}else{
			$this->db->insert('aicrm_inventoryproductplan', [
				'id' => $projectid,
				'productid' => $productid,
				'plan_date' => $product_plan_date,
				'plan_qty' => $product_qty,
				'userid' => $userid,
				'createdtime' => date('Y-m-d H:i:s')
			]);
			$insert_id = $this->db->insert_id();
		}
		

		$query_plan = "select
			lineitem_id,
			DATE_FORMAT(plan_date,'%d/%m/%Y') as date_plan,
			plan_qty as qty
			from aicrm_inventoryproductplan
			where lineitem_id = '".$insert_id."'
			order by lineitem_id desc;";
		$sql_plan = $this->db->query($query_plan);
		$rs_plan = $sql_plan->row_array();

		$q = "update aicrm_inventoryprojects
		inner join (select
		id,
		productid,
		sum(plan_qty) as plan_qty
		from aicrm_inventoryproductplan
		where productid = '".$productid."' and id = '".$projectid."'
		and deleted = 0
		group by productid,id) as productplan on productplan.productid = aicrm_inventoryprojects.productid and productplan.id = aicrm_inventoryprojects.id
		set aicrm_inventoryprojects.plan = productplan.plan_qty , aicrm_inventoryprojects.remain_on_hand = (productplan.plan_qty-aicrm_inventoryprojects.delivered)
		where aicrm_inventoryprojects.lineitem_id = '".$lineitem."';";
		$this->db->query($q);

		$returnData = $this->_return;
		$returnData['Message'] = 'Add Success';
		$returnData['data']['Crmid'] = $projectid;
		$returnData['plan'] = $rs_plan;

		$this->response($returnData, 200);
	}

	public function delProductPlan_post()
	{
		$request_body = file_get_contents('php://input');
		$dataJson = json_decode($request_body, true);
	  	$response_data = null;
	  	$a_request = $dataJson;
	  	
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  	$search_module=isset($a_request['search_module']) ? $a_request['search_module'] : "Projects";
	  	$productid=isset($a_request['productid']) ? $a_request['productid'] : "";
	  	$lineitem_id=isset($a_request['lineitem_id']) ? $a_request['lineitem_id'] : "";
	  	$lineitem=isset($a_request['lineitem_id']) ? $a_request['lineitem'] : "";
	  	$acion=isset($a_request['acion']) ? $a_request['acion'] : "del";

	  	$data = array(
		    'modifiedby' => $userid,
		    'modifiedtime' => date('Y-m-d H:i:s'),
		    'deleted' => 1
		);
	  	$this->db->where('lineitem_id', $lineitem_id);
		$this->db->update('aicrm_inventoryproductplan', $data);
		

		$query_check = "select
			id,
			productid,
			sum(plan_qty) as plan_qty
			from aicrm_inventoryproductplan
			where productid = '".$productid."' and id = '".$crmid."'
			and deleted = 0
			group by productid,id";
		$sql_check = $this->db->query($query_check);
		$rs_check = $sql_check->row_array();

		if(empty($rs_check)){
			$qty = 0;
		}else{
			$qty = $rs_check['plan_qty'];
		}
		
		$q = "update aicrm_inventoryprojects
		set aicrm_inventoryprojects.plan = ".$qty." , aicrm_inventoryprojects.remain_on_hand = (".$qty."-aicrm_inventoryprojects.delivered)
		where aicrm_inventoryprojects.lineitem_id = '".$lineitem."' and aicrm_inventoryprojects.productid = '".$productid."' and aicrm_inventoryprojects.id = '".$crmid."';";

		/*$q = "update aicrm_inventoryprojects
		inner join (select
		id,
		productid,
		sum(plan_qty) as plan_qty
		from aicrm_inventoryproductplan
		where productid = '".$productid."' and id = '".$crmid."'
		and deleted = 0
		group by productid,id) as productplan on productplan.productid = aicrm_inventoryprojects.productid and productplan.id = aicrm_inventoryprojects.id
		set aicrm_inventoryprojects.plan = productplan.plan_qty , aicrm_inventoryprojects.remain_on_hand = (productplan.plan_qty-aicrm_inventoryprojects.delivered)
		where aicrm_inventoryprojects.lineitem_id = '".$lineitem."';";*/
		$this->db->query($q);

		$query_plan = "select
			sum(plan_qty) as qty
			from aicrm_inventoryproductplan
			where productid = '".$productid."' and id = '".$crmid."'
			and deleted = 0
			group by productid,id;";
		$sql_qty = $this->db->query($query_plan);
		$rs_qty = $sql_qty->row_array();

		$returnData = $this->_return;
		$returnData['Message'] = 'Deleted Success';
		$returnData['data']['Crmid'] = $crmid;
		$returnData['qty'] = $rs_qty['qty'];

		$this->response($returnData, 200);
	}

	public function getProductDelivered_post()
	{
		$request_body = file_get_contents('php://input');
		$dataJson = json_decode($request_body, true);
	  	$response_data = null;
	  	$a_request = $dataJson;

		$crmid = $a_request['crmid'];
		$productid = $a_request['productid'];

		$query = "SELECT aicrm_projects.projects_no , aicrm_projects.projects_name , aicrm_products.product_no , aicrm_products.productname , aicrm_inventoryprojects.plan
		, aicrm_inventoryprojects.delivered 
			FROM aicrm_inventoryprojects
			inner join aicrm_projects on aicrm_projects.projectsid = aicrm_inventoryprojects.id
			inner join aicrm_products on aicrm_products.productid = aicrm_inventoryprojects.productid
			WHERE aicrm_inventoryprojects.id = '".$crmid."' AND aicrm_inventoryprojects.productid = '".$productid."';";
		$sql = $this->db->query($query);
		$data = $sql->result_array();
		//alert($data); exit;
		
		$query_deliver = "select
			aicrm_inventoryproductdeliver.lineitem_id,
			DATE_FORMAT(aicrm_inventoryproductdeliver.deliver_date,'%d/%m/%Y') as deliver_date,
			aicrm_inventoryproductdeliver.deliver_qty as qty,
			aicrm_account.accountid,
			aicrm_account.accountname
			from aicrm_inventoryproductdeliver
			inner join aicrm_account on aicrm_account.accountid = aicrm_inventoryproductdeliver.accountid
			where aicrm_inventoryproductdeliver.productid = '".$productid."' and aicrm_inventoryproductdeliver.id = '".$crmid."'
			and aicrm_inventoryproductdeliver.deleted = 0
			order by aicrm_inventoryproductdeliver.lineitem_id desc;";
		$sql_deliver = $this->db->query($query_deliver);
		$data_deliver = $sql_deliver->result_array();

		//alert($data_deliver); exit;
		$response_data = $this->_return;
		$response_data['Message'] = 'Success';
		$response_data['data']['Crmid'] = $crmID;
		$response_data['data'] = $data;
		$response_data['deliver'] = $data_deliver;

		$this->response($response_data, 200);
	}

	public function insert_ProductDelivered_post()
	{
		$request_body = file_get_contents('php://input');
		$dataJson = json_decode($request_body, true);
	  	$response_data = null;
	  	$a_request = $dataJson;
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "add";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  	
		$projectid = $data[0]['projectid'];
		$productid = $data[0]['productid'];
		$accountid = $data[0]['accountid'];
		$product_delivered_date = $data[0]['product_delivered_date'];
		$product_qty = $data[0]['product_delivered_qty'];
		$lineitem = $data[0]['lineitem'];
		$deliveredlineitem_id = $data[0]['deliveredlineitem_id'];

		$this->db->where('id', $data[0]['projectid']);
		$this->db->where('productid', $productid);
		$this->db->where('deleted', 0);
		$query = $this->db->get('aicrm_inventoryproductdeliver');
		$a_result  = $query->result_array();
		
		if (!empty($a_result)) {
			$this->db->select('first_delivered_date,last_delivered_date', false );
			$this->db->where('id', $projectid);
		  	$this->db->where('lineitem_id', $lineitem);
			$s_query = $this->db->get('aicrm_inventoryprojects');
			$s_result  = $s_query->result_array();

			$first_delivered_date = date('Y-m-d', strtotime($s_result[0]['first_delivered_date']));
			$last_delivered_date = date('Y-m-d', strtotime($s_result[0]['last_delivered_date']));
			$delivered_date = date('Y-m-d', strtotime($product_delivered_date));
			
			if ($delivered_date < $first_delivered_date){
			    $in_data = array(
			    	'first_delivered_date' => $product_delivered_date,
				);
			  	$this->db->where('id', $projectid);
			  	$this->db->where('lineitem_id', $lineitem);
				$this->db->update('aicrm_inventoryprojects', $in_data);
			}

			if ($delivered_date > $last_delivered_date){
			    $in_data = array(
			    	'last_delivered_date' => $product_delivered_date,
				);
			  	$this->db->where('id', $projectid);
			  	$this->db->where('lineitem_id', $lineitem);
				$this->db->update('aicrm_inventoryprojects', $in_data);
			}
		}else{
			$in_data = array(
		    	'first_delivered_date' => $product_delivered_date,
		    	'last_delivered_date' => $product_delivered_date,
			);
		  	$this->db->where('id', $projectid);
		  	$this->db->where('lineitem_id', $lineitem);
			$this->db->update('aicrm_inventoryprojects', $in_data);
		}
		
		if($action=='edit'){
	        $data = array(
	            'accountid' => $accountid,
				'deliver_date' => $product_delivered_date,
				'deliver_qty' => $product_qty,
	            'modifiedby' => $userid,
	            'modifiedtime' => date('Y-m-d H:i:s')
	        );
	        $this->db->where('lineitem_id', $deliveredlineitem_id);
	        $this->db->update('aicrm_inventoryproductdeliver', $data);
	        $insert_id = $deliveredlineitem_id;
	    }else{
	        $this->db->insert('aicrm_inventoryproductdeliver', [
				'id' => $projectid,
				'productid' => $productid,
				'accountid' => $accountid,
				'deliver_date' => $product_delivered_date,
				'deliver_qty' => $product_qty,
				'userid' => $userid,
				'createdtime' => date('Y-m-d H:i:s')
			]);
			$insert_id = $this->db->insert_id();
	    }

		$q = "update aicrm_inventoryprojects
			inner join (select
			id,
			productid,
			sum(deliver_qty) as qty
			from aicrm_inventoryproductdeliver
			where productid = '".$productid."' and id = '".$projectid."'
			and deleted = 0
			group by productid,id) as productdeliver on productdeliver.productid = aicrm_inventoryprojects.productid and productdeliver.id = aicrm_inventoryprojects.id
			set aicrm_inventoryprojects.delivered = productdeliver.qty , aicrm_inventoryprojects.remain_on_hand = (aicrm_inventoryprojects.plan-productdeliver.qty)
			where aicrm_inventoryprojects.lineitem_id = '".$lineitem."';";
		$this->db->query($q);

		$query_deliver = "select
			aicrm_inventoryproductdeliver.lineitem_id,
			DATE_FORMAT(aicrm_inventoryproductdeliver.deliver_date,'%d/%m/%Y') as deliver_date,
			aicrm_inventoryproductdeliver.deliver_qty as qty,
			aicrm_account.accountid,
			aicrm_account.accountname
			from aicrm_inventoryproductdeliver
			inner join aicrm_account on aicrm_account.accountid = aicrm_inventoryproductdeliver.accountid
			where aicrm_inventoryproductdeliver.lineitem_id = '".$insert_id."'
			and aicrm_inventoryproductdeliver.deleted = 0
			order by aicrm_inventoryproductdeliver.lineitem_id desc;";
		$sql_deliver = $this->db->query($query_deliver);
		$rs_deliver = $sql_deliver->row_array();

		/**/
		$this->db->select('first_delivered_date,last_delivered_date', false );
		$this->db->where('id', $projectid);
	  	$this->db->where('lineitem_id', $lineitem);
		$s_query = $this->db->get('aicrm_inventoryprojects');
		$s_result  = $s_query->result_array();

		$returnData = $this->_return;
		$returnData['Message'] = 'Add Success';
		$returnData['data']['Crmid'] = $projectid;
		$returnData['deliver'] = $rs_deliver;
		$returnData['delivered_date'] = $s_result;

		$this->response($returnData, 200);
	}

	public function delProductDelivered_post()
	{
		$request_body = file_get_contents('php://input');
		$dataJson = json_decode($request_body, true);
	  	$response_data = null;
	  	$a_request = $dataJson;
	  	
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  	$search_module=isset($a_request['search_module']) ? $a_request['search_module'] : "Projects";
	  	$productid=isset($a_request['productid']) ? $a_request['productid'] : "";
	  	$lineitem_id=isset($a_request['lineitem_id']) ? $a_request['lineitem_id'] : "";
	  	$lineitem=isset($a_request['lineitem']) ? $a_request['lineitem'] : "";
	  	$acion=isset($a_request['acion']) ? $a_request['acion'] : "del";

	  	$data = array(
		    'modifiedby' => $userid,
		    'modifiedtime' => date('Y-m-d H:i:s'),
		    'deleted' => 1
		);
	  	$this->db->where('lineitem_id', $lineitem_id);
		$this->db->update('aicrm_inventoryproductdeliver', $data);
		
		$query_check = "select
			id,
			productid,
			sum(deliver_qty) as qty
			from aicrm_inventoryproductdeliver
			where productid = '".$productid."' and id = '".$crmid."'
			and deleted = 0
			group by productid,id";
		$sql_check = $this->db->query($query_check);
		$rs_check = $sql_check->row_array();

		if(empty($rs_check)){
			$qty = 0;
		}else{
			$qty = $rs_check['qty'];
		}
		
		$q = "update aicrm_inventoryprojects
				set aicrm_inventoryprojects.delivered = ".$qty." , aicrm_inventoryprojects.remain_on_hand = (aicrm_inventoryprojects.plan-".$qty.")
				where aicrm_inventoryprojects.lineitem_id = '".$lineitem."' and aicrm_inventoryprojects.productid = '".$productid."' and aicrm_inventoryprojects.id = '".$crmid."';";
			
		/*$q = "update aicrm_inventoryprojects
			inner join (select
			id,
			productid,
			sum(deliver_qty) as qty
			from aicrm_inventoryproductdeliver
			where productid = '".$productid."' and id = '".$crmid."'
			and deleted = 0
			group by productid,id) as productdeliver on productdeliver.productid = aicrm_inventoryprojects.productid and productdeliver.id = aicrm_inventoryprojects.id
			set aicrm_inventoryprojects.delivered = productdeliver.qty , aicrm_inventoryprojects.remain_on_hand = (aicrm_inventoryprojects.plan-productdeliver.qty)
			where aicrm_inventoryprojects.lineitem_id = '".$lineitem."';";*/
		$this->db->query($q);

		$query_plan = "select
			sum(deliver_qty) as qty
			from aicrm_inventoryproductdeliver
			where productid = '".$productid."' and id = '".$crmid."'
			and deleted = 0
			group by productid,id;";
		$sql_qty = $this->db->query($query_plan);
		$rs_qty = $sql_qty->row_array();

		$returnData = $this->_return;
		$returnData['Message'] = 'Deleted Success';
		$returnData['data']['Crmid'] = $crmid;
		$returnData['qty'] = $rs_qty['qty'];

		$returnData['clear'] = false;
		/*Clear Delivered*/
		$query_d = "select
			count(*) as row
			from aicrm_inventoryproductdeliver
			where productid = '".$productid."' and id = '".$crmid."'
			and deleted = 0
			group by productid,id;";
		$sql_d = $this->db->query($query_d);
		$rs_d = $sql_d->row_array();

		if(empty($rs_d)){
			$data = array(
			    'first_delivered_date' => '0000-00-00',
			    'last_delivered_date' => '0000-00-00'
			);
		  	$this->db->where('id', $crmid);
		  	$this->db->where('productid', $productid);
			$this->db->update('aicrm_inventoryprojects', $data);
			$returnData['clear'] = true;
			$returnData['delivered_date'] = $data;
		}else{
			$q_getdate = "SELECT MIN(deliver_date) as first_date , MAX(deliver_date) as last_date
				FROM aicrm_inventoryproductdeliver
				WHERE productid = '".$productid."' AND id = '".$crmid."'AND deleted = 0;";
			$sql_getdate = $this->db->query($q_getdate);
			$rs_date = $sql_getdate->row_array();
			$data = array(
			    'first_delivered_date' => $rs_date['first_date'],
			    'last_delivered_date' => $rs_date['last_date']
			);
		  	$this->db->where('id', $crmid);
		  	$this->db->where('productid', $productid);
			$this->db->update('aicrm_inventoryprojects', $data);
			$returnData['clear'] = true;
			$returnData['delivered_date'] = $data;
		}
		/*Clear Delivered*/
		$this->response($returnData, 200);
	}

	private function set_param($a_param=array())
	{
		$a_condition = array();
	
		if (isset($a_param["event_id"]) && $a_param["event_id"]!="" && $a_param["search_module"]=="Calendar") {
			$a_condition["aicrm_activity.event_id"] =  $a_param["event_id"] ;
		}
		if (isset($a_param["event_id"]) && $a_param["event_id"]!="" && $a_param["search_module"]=="Quotation") {
			$a_condition["aicrm_quotes.event_id"] =  $a_param["event_id"] ;
		}
		if(isset($a_param["projectsid"]) && $a_param["projectsid"]!='' && $a_param["search_module"]=="Quotation"){
			$a_condition["aicrm_quotes.projectsid"] =  $a_param["projectsid"] ;
		}
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="" && $a_param["search_module"]=="Documents") {
			$a_condition["crm2.crmid"] =  $a_param["crmid"] ;
		}
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="" && $a_param["search_module"]=="PriceList") {
			$a_condition["aicrm_pricelists.projectsid"] =  $a_param["crmid"] ;
		}
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="" && $a_param["search_module"]=="Samplerequisition") {
			$a_condition["aicrm_samplerequisition.projectsid"] =  $a_param["crmid"] ;
		}
		if (isset($a_param["crmid"]) && $a_param["crmid"]!="" && $a_param["search_module"]=="Expense") {
			$a_condition["aicrm_expense.projectsid"] =  $a_param["crmid"] ;
		}
		if (isset($a_param["event_id"]) && $a_param["event_id"]!="" && $a_param["search_module"]=="Questionnaire") {
			$a_condition["aicrm_questionnaire.event_id"] =  $a_param["event_id"] ;
		}

		return $a_condition;
	}

	public function return_data($a_data)
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["error"];
			$a_return["total"] = isset($a_data["data"]["total"]) ? $a_data["data"]["total"]:0;
			$a_return["offset"] = isset($a_data["offset"])?$a_data["offset"]:0;
			$a_return["limit"] = isset($a_data["limit"])?$a_data["limit"]:0;
			$a_return["cachetime"] = $a_data["time"];
			$a_return["data"] = !empty($a_data["data"]["data"]) ? $a_data["data"]["data"] : "" ;
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

	private function get_data($a_data)
	{
		if($a_data['result'] != ''){
			
		}
		return $a_data;
	}

	public function getRelated_Activity_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data = $this->get_Activity($a_param);
		
		$this->return_data($a_data);
	}
	
	private function get_Activity($a_params=array())
	{	
		$this->load->library('managecached_library');
		$this->load->model("projects_model");
		
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		
		$a_condition = array();
		$a_condition = $this->set_param($a_params);

		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];
		$order= @$a_params["orderby"];
		$a_order = $this->set_order($order);
		
		$a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;
	
		$a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
		$a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
		$a_build["limit"] = http_build_query($a_limit);
		
		$a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));
	
		$a_data = $this->managecached_library->get_memcache($a_cache);
		
		if($a_data===false)
		{
			$a_list=$this->projects_model->get_list_activity($a_condition,$a_order,$a_limit);

			$a_data = $this->get_data($a_list);
			$a_data["data"] = $a_data["result"];
			$a_data["limit"] = $a_limit["limit"]  ;
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_data["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
	
		return $a_data;
	}

	public function getRelated_Quotation_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data = $this->get_Quotation($a_param);
		
		$this->return_data($a_data);
	}
	
	private function get_Quotation($a_params=array())
	{	
		$this->load->library('managecached_library');
		$this->load->model("projects_model");
		
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		
		$a_condition = array();
		$a_condition = $this->set_param($a_params);

		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];
		$order= @$a_params["orderby"];
		$a_order = $this->set_order($order);
		
		$a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;
	
		$a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
		$a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
		$a_build["limit"] = http_build_query($a_limit);
		
		$a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));
	
		$a_data = $this->managecached_library->get_memcache($a_cache);
		
		if($a_data===false)
		{
			$a_list=$this->projects_model->get_list_quotation($a_condition,$a_order,$a_limit);

			$a_data = $this->get_data($a_list);
			$a_data["data"] = $a_data["result"];
			$a_data["limit"] = $a_limit["limit"]  ;
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_data["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
	
		return $a_data;
	}

	public function getRelated_Documents_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data = $this->get_Documents($a_param);
		
		$this->return_data($a_data);
	}
	
	private function get_Documents($a_params=array())
	{	
		$this->load->library('managecached_library');
		$this->load->model("projects_model");
		
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		
		$a_condition = array();
		$a_condition = $this->set_param($a_params);

		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];
		$order= @$a_params["orderby"];
		$a_order = $this->set_order($order);
		
		$a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;
	
		$a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
		$a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
		$a_build["limit"] = http_build_query($a_limit);
		
		$a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));
	
		$a_data = $this->managecached_library->get_memcache($a_cache);
		
		if($a_data===false)
		{
			$a_list=$this->projects_model->get_list_documents($a_condition,$a_order,$a_limit);

			$a_data = $this->get_data($a_list);
			$a_data["data"] = $a_data["result"];
			$a_data["limit"] = $a_limit["limit"]  ;
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_data["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
	
		return $a_data;
	}

	public function getRelated_Pricelist_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data = $this->get_Pricelist($a_param);
		
		$this->return_data($a_data);
	}
	
	private function get_Pricelist($a_params=array())
	{	
		$this->load->library('managecached_library');
		$this->load->model("projects_model");
		
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		
		$a_condition = array();
		$a_condition = $this->set_param($a_params);

		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];
		$order= @$a_params["orderby"];
		$a_order = $this->set_order($order);
		
		$a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;
	
		$a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
		$a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
		$a_build["limit"] = http_build_query($a_limit);
		
		$a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));
	
		$a_data = $this->managecached_library->get_memcache($a_cache);
		
		if($a_data===false)
		{
			$a_list=$this->projects_model->get_list_pricelist($a_condition,$a_order,$a_limit);

			$a_data = $this->get_data($a_list);
			$a_data["data"] = $a_data["result"];
			$a_data["limit"] = $a_limit["limit"]  ;
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_data["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
	
		return $a_data;
	}
	
	public function getRelated_Samplerequisition_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data = $this->get_Samplerequisition($a_param);
		
		$this->return_data($a_data);
	}
	
	private function get_Samplerequisition($a_params=array())
	{	
		$this->load->library('managecached_library');
		$this->load->model("projects_model");
		
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		
		$a_condition = array();
		$a_condition = $this->set_param($a_params);

		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];
		$order= @$a_params["orderby"];
		$a_order = $this->set_order($order);
		
		$a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;
	
		$a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
		$a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
		$a_build["limit"] = http_build_query($a_limit);
		
		$a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));
	
		$a_data = $this->managecached_library->get_memcache($a_cache);
		
		if($a_data===false)
		{
			$a_list=$this->projects_model->get_list_samplerequisition($a_condition,$a_order,$a_limit);

			$a_data = $this->get_data($a_list);
			$a_data["data"] = $a_data["result"];
			$a_data["limit"] = $a_limit["limit"]  ;
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_data["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
	
		return $a_data;
	}

	public function getRelated_Expenses_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data = $this->get_Expenses($a_param);
		
		$this->return_data($a_data);
	}
	
	private function get_Expenses($a_params=array())
	{	
		$this->load->library('managecached_library');
		$this->load->model("projects_model");
		
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		
		$a_condition = array();
		$a_condition = $this->set_param($a_params);

		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];
		$order= @$a_params["orderby"];
		$a_order = $this->set_order($order);
		
		$a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;
	
		$a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
		$a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
		$a_build["limit"] = http_build_query($a_limit);
		
		$a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));
	
		$a_data = $this->managecached_library->get_memcache($a_cache);
		
		if($a_data===false)
		{
			$a_list=$this->projects_model->get_list_expenses($a_condition,$a_order,$a_limit);

			$a_data = $this->get_data($a_list);
			$a_data["data"] = $a_data["result"];
			$a_data["limit"] = $a_limit["limit"]  ;
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_data["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
	
		return $a_data;
	}

	public function getRelated_Questionnaire_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$a_data = $this->get_Questionnaire($a_param);
		
		$this->return_data($a_data);
	}
	
	private function get_Questionnaire($a_params=array())
	{	
		$this->load->library('managecached_library');
		$this->load->model("projects_model");
		
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		
		$a_condition = array();
		$a_condition = $this->set_param($a_params);

		$limit = @$a_params["limit"];
		$offset = @$a_params["offset"];
		$order= @$a_params["orderby"];
		$a_order = $this->set_order($order);
		
		$a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
		$a_limit["offset"] = ($offset == "") ? 0 :$offset;
	
		$a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
		$a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
		$a_build["limit"] = http_build_query($a_limit);
		
		$a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));
	
		$a_data = $this->managecached_library->get_memcache($a_cache);
		
		if($a_data===false)
		{
			$a_list=$this->projects_model->get_list_questionnaire($a_condition,$a_order,$a_limit);

			$a_data = $this->get_data($a_list);
			$a_data["data"] = $a_data["result"];
			$a_data["limit"] = $a_limit["limit"]  ;
			$a_data["offset"] = $a_limit["offset"]  ;
			$a_data["time"] = date("Y-m-d H:i:s");
			$a_cache["data"] = $a_data["result"];
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
	
		return $a_data;
	}

}
