<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Samplerequisition extends REST_Controller
{

  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_samplerequisition','aicrm_samplerequisitioncf');
  private $tab_name_index =  array('aicrm_crmentity'=>'crmid','aicrm_samplerequisition'=>'samplerequisitionid' ,'aicrm_samplerequisitioncf'=>'samplerequisitionid');
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("samplerequisition_model");
		$this->_module = "Samplerequisition";
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
				'SamplerequisitionNo' => null
			),
		);
	}
	
	public function insert_content_post(){

	  	$this->common->_filename= "Insert_Samplerequisition";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Samplerequisition ==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert Samplerequisition ==>',$a_request,$response_data);
	  
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
	  
	  		if(count($data[0])>0 and $module=="Samplerequisition"){

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

	public function get_samplerequisition_post(){

		$this->common->_filename= "Detail_Samplerequisition";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$this->common->set_log('Before Detail Samplerequisition ==>',$url,$a_request);

		$response_data = $this->samplerequisition_model->get_samplerequisition($a_request);

	  	$this->common->set_log('After Detail Samplerequisition ==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	public function get_product_list_post()
	{
		$this->common->_filename= "Save_Samplerequisition_Detail";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$a_request =$dataJson;
		
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$this->common->set_log('Before Samplerequisition Detail ==>',$url,$a_request);

		$crmID = $a_request['crmID'];
		$sql = $this->db->get_where('aicrm_samplerequisition', ['samplerequisitionid'=>$crmID]);
		$rowData = $sql->row_array();

		$this->db->select('aicrm_inventorysamplerequisition.*, 
		aicrm_crmentity.setype, 
		aicrm_products.productname, 
		aicrm_products.product_no,
		aicrm_products.selling_price,
		aicrm_products.stockqty	');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid=aicrm_inventorysamplerequisition.productid', 'inner');
		$this->db->join('aicrm_products', 'aicrm_products.productid=aicrm_inventorysamplerequisition.productid', 'left');
		$this->db->where(array('aicrm_inventorysamplerequisition.id' => $crmID));
		$this->db->order_by("aicrm_inventorysamplerequisition.sequence_no", "asc");
		$sql = $this->db->get('aicrm_inventorysamplerequisition');

		$itemList = $sql->result_array();
		
		array_walk_recursive($rowData, function (&$item, $key) {
            $item = null === $item ? '' : $item;
        });

		array_walk_recursive($itemList, function (&$item, $key) {
            $item = null === $item ? '' : $item;
        });

		$returnData = $this->_return;
		$returnData['data']['Crmid'] = $crmID;
		$returnData['data']['SamplerequisitionNo'] = $rowData['samplerequisition_no'];
		$returnData['data']['rowData'] = $rowData;
		$returnData['data']['itemList'] = $itemList;
		
		$this->response($returnData, 200);
	}

	public function save_product_list_post()
	{
		$this->common->_filename= "Save_Samplerequisition_Detail";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$a_request =$dataJson;
		
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$this->common->set_log('Before Samplerequisition Detail ==>',$url,$a_request);

		$crmID = $a_request['crmID'];
		$updateData = $a_request['updateData'];
		$itemList = $a_request['itemList'];
		$this->db->update('aicrm_samplerequisition', $updateData, ['samplerequisitionid'=>$crmID]);

		$this->db->delete('aicrm_inventorysamplerequisition', ['id'=>$crmID]);
		foreach($itemList as $item){
			$this->db->insert('aicrm_inventorysamplerequisition', $item);
		}

		$returnData = $this->_return;
		$returnData['data']['Crmid'] = $crmID;

		$this->response($returnData, 200);
	}

	/*public function save_product_list_temp_post()
	{
		$this->common->_filename = "Save_Samplerequisition_Detail";
		header('Content-Type:application/json; charset=UTF-8');
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body, true);
		$a_request = $dataJson;

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$this->common->set_log('Before Samplerequisition Detail ==>', $url, $a_request);

		$crmID = $a_request['crmID'];
		$updateData = $a_request['updateData'];
		$itemList = $a_request['itemList'];
		$this->db->update('aicrm_samplerequisition', $updateData, ['quoteid' => $crmID]);

		$this->db->delete('aicrm_inventoryproductrel_temp', ['id' => $crmID]);
		foreach ($itemList as $item) {
			$this->db->insert('aicrm_inventoryproductrel_temp', $item);
		}

		$returnData = $this->_return;
		$returnData['data']['Crmid'] = $crmID;

		$this->response($returnData, 200);
	}*/
	
	public function update_status_post()
	{
		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body,true);

		$crmID = $req['crmid'];
		$userID = $req['userid'];
		$status = $req['status'];
		$reason = $req['reason'];
		$updateData = [];

		switch($status){
			case 'approve':

				$sql = $this->db->get_where('tbt_samplerequisition_approve', ['crmid'=>$crmID, 'appstatus'=>0]);
				$count = $sql->num_rows();

				if($count > 1){
					$samplerequisitionstatus = 'Request for Approve';
					$approve_status = 'Request_Approve';
					$approveflag = 0;
				}else{
					$samplerequisitionstatus = 'Approved';
					$approve_status = 'Approve';
					$approveflag = 1;
				}

				$sql = "UPDATE tbt_samplerequisition_approve SET appstatus=1, upuser=".$userID.",updatedate='".date('Y-m-d H:i:s')."'  WHERE userid=".$userID." AND crmid=".$crmID." AND appstatus<>1 ORDER BY level ASC LIMIT 1";
				$this->db->query($sql);
				
				$updateData = [
					'samplerequisition_status' => $samplerequisitionstatus
				];
				break;
			case 'reject':
				$samplerequisitionstatus = 'Cancel_Approve';
				$approve_status = 'Cancel_Approve';
				$approveflag = 0;
				$updateData = [
					'samplerequisition_status' => 'Rejected',
					'rejected_reason' => $reason
				];
				$sql = "UPDATE tbt_samplerequisition_approve SET appstatus=2, upuser=".$userID.",updatedate='".date('Y-m-d H:i:s')."' WHERE userid=".$userID." AND crmid=".$crmID." AND appstatus<>1 ORDER BY level ASC LIMIT 1";
				$this->db->query($sql);
				break;
			case 'cancel':
				$samplerequisitionstatus = 'Cancel_Samplerequisition';
				$approve_status = 'Cancel_Samplerequisition';
				$approveflag = 0;
				$updateData = [
					'samplerequisition_status' => 'Cancelled',
					'cancel_reason' => $reason
				];
				break;
			case 'change':
				$samplerequisitionstatus = 'Revised';
				$approve_status = 'Revise';
				$approveflag = 0;
				$updateData = [
					'samplerequisition_status' => 'Revised'
				];
				break;
			case 'close':
				$samplerequisitionstatus = 'Approved';
				$approve_status = 'Complete';
				$approveflag = 0;
				$updateData = [
					'samplerequisition_status' => 'Approved'
				];
				break;
		}

		// Create Request Approve Log
		$this->db->insert('tbt_samplerequisition_log', [
			'crmid' => $crmID,
			'assignto' => 0,
			'samplerequisition_status' => $approve_status,
			'adduser' => $userID,
			'adddate' => date('Y-m-d H:i:s')
		]);

		$this->db->update('aicrm_samplerequisition', $updateData, ['samplerequisitionid'=>$crmID]);
		$this->db->update('aicrm_crmentity', ['modifiedtime'=>date('Y-m-d H:i:s'), 'modifiedby'=>$userID], ['crmid'=>$crmID]);

		$assignto = $this->get_update_assignto($approve_status,$crmID,$approveflag);
		$this->db->update('aicrm_crmentity', ['smownerid'=>$assignto], ['crmid'=>$crmID]);

		//if($assignto !=''){
			$sendmail = $this->send_mail_sample('approve',$approve_status, $crmID, $assignto);
		//}

		$returnData = $this->_return;
		$returnData['Message'] = 'Request Approve Success';
		$returnData['data']['Crmid'] = $crmID;

		$this->response($returnData, 200);
	}

	public function request_approve_post()
	{
		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body,true);

		$crmID = $req['crmid'];
		$userID = $req['userid'];

		// Select Approver
		$approvers = $this->getApprover($crmID);

		// Setting Approver
		foreach($approvers as $user){
			$this->db->insert('tbt_samplerequisition_approve', [
				'crmid' => $crmID,
				'userid' => $user['userid'],
				'username' => $user['username'],
				'level' => $user['level']
			]);
		}

		// Create Request Approve Log
		$this->db->insert('tbt_samplerequisition_log', [
			'crmid' => $crmID,
			'assignto' => 0,
			'samplerequisition_status' => 'Request_Approve',
			'adduser' => $userID,
			'adddate' => date('Y-m-d H:i:s')
		]);

		$this->db->update('aicrm_samplerequisition', ['samplerequisition_status'=>'Request for Approve'], ['samplerequisitionid'=>$crmID]);
		$this->db->update('aicrm_crmentity', ['modifiedtime'=>date('Y-m-d H:i:s'), 'modifiedby'=>$userID], ['crmid'=>$crmID]);

		$assignto = $this->get_update_assignto('Request_Approve',$crmID);
		$this->db->update('aicrm_crmentity', ['smownerid'=>$assignto], ['crmid'=>$crmID]);
		
		if($assignto !=''){
			$sendmail = $this->send_mail_sample('approve','Request_Approve', $crmID, $assignto);
		}

		$returnData = $this->_return;
		$returnData['Message'] = 'Request Approve Success';
		$returnData['data']['Crmid'] = $crmID;

		$this->response($returnData, 200);
	}

	public function get_approver_post(){
		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body,true);

		$crmID = $req['crmid'];
		$userID = $req['userid'];

		// Select Approver
		$sql = $this->db->get_where('tbt_samplerequisition_approve', ['crmid'=>$crmID]);
		$approver = $sql->result_array();

		$returnData = $this->_return;
		$returnData['Message'] = 'Success';
		$returnData['data']['Crmid'] = $crmID;
		$returnData['data']['Approvers'] = $approver;

		$this->response($returnData, 200);
	}

	public function get_samplerequisition_no_rev_post()
	{

		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body, true);

		$crmID = $req['crmid'];

		$query = "select 
		aicrm_samplerequisition.samplerequisition_no,
		aicrm_samplerequisition.revised_no,
		aicrm_samplerequisition.ref_sample_request		
		from aicrm_samplerequisition
		left join aicrm_samplerequisitioncf on  aicrm_samplerequisition.samplerequisitionid=aicrm_samplerequisitioncf.samplerequisitionid
		left join aicrm_crmentity c  on aicrm_samplerequisition.samplerequisitionid  = c.crmid 
		where 1 
		and c.deleted <> 1 
		and aicrm_samplerequisition.samplerequisitionid='" . $crmID . "'
		group by aicrm_samplerequisition.samplerequisitionid";

		$sql = $this->db->query($query);
		$rs = $sql->row_array();

		$data_sample_no = $rs['samplerequisition_no'];
		$data_rev_no = $rs['revised_no'];
		$data_sample_no_rev = $rs['ref_sample_request'];

		if ($data_rev_no == "" || $data_rev_no == "0") {
			$data_rev = "1";
		} else {
			$data_rev = $data_rev_no + 1;
		}

		$temp = [
			'data_sample_no' => $data_sample_no,
			'data_rev' => $data_rev
		];

		$returnData = $this->_return;
		$returnData['Message'] = 'Success';
		$returnData['data']['Crmid'] = $crmID;
		$returnData['data']['dataRev'] = $temp;

		$this->response($returnData, 200);
	}
	
	private function getApprover($crmID){
		$query = "SELECT approver, approver2, approver3, f_approver FROM aicrm_samplerequisition WHERE samplerequisitionid=".$crmID;
		$sql = $this->db->query($query);
		$rs = $sql->row_array();

		$approver = [];
		if($rs['approver'] != ''){
			$apLv1 = explode(' |##| ', $rs['approver']);
			foreach($apLv1 as $ap){
				$approver[] = [
					'level' => '1',
					'user' => $ap
				];
			}
		}

		if($rs['approver2'] != ''){
			$apLv2 = explode(' |##| ', $rs['approver2']);
			foreach($apLv2 as $ap){
				$approver[] = [
					'level' => '2',
					'user' => $ap
				];
			}
		}

		if($rs['approver3'] != ''){
			$apLv3 = explode(' |##| ', $rs['approver3']);
			foreach($apLv3 as $ap){
				$approver[] = [
					'level' => '3',
					'user' => $ap
				];
			}
		}

		if($rs['f_approver'] != ''){
			$apLv4 = explode(' |##| ', $rs['f_approver']);
			foreach($apLv4 as $ap){
				$approver[] = [
					'level' => '4',
					'user' => $ap
				];
			}
		}

		$approvers = [];
		foreach($approver as $user){
			$query = "SELECT id, user_name FROM aicrm_users WHERE CONCAT(first_name, ' ', last_name) = '".$user['user']."' LIMIT 1";
			$sql = $this->db->query($query);
			$rs = $sql->row_array();

			$approvers[] = [
				'userid' => $rs['id'],
				'username' => $rs['user_name'],
				'level' => $user['level']
			];
		}

		return $approvers;
	}

	private function get_update_assignto($sample_status="", $crmid, $approveflag=0)
	{
		if(($approveflag==1 && $sample_status == 'Approve') || $sample_status == 'Cancel_Approve'){
			$query = "select smcreatorid as userid
				from aicrm_crmentity
				where crmid='".$crmid."'
				limit 1	";
		}else{
			$query = "select userid
				from tbt_samplerequisition_approve
				where crmid='".$crmid."'
				and appstatus = 0
				order by level,username
				limit 1	";
		}
		$sql = $this->db->query($query);
		$a_data = $sql->row_array();
		return $a_data["userid"];
	}

	private function send_mail_sample($submodule="approve",$sample_status='',$crmid='',$assignto='')
	{
	
		$this->load->library('email');
		$this->config->load('email');
		$mail = $this->config->item('mail');

		$from = $mail[$submodule]["from"];
		$from_name = $mail[$submodule]["from_name"];

		$a_data = "SELECT * FROM aicrm_samplerequisition WHERE samplerequisitionid=".$crmid;
		$sql = $this->db->query($a_data);
		$data = $sql->row_array();
		
		if($sample_status=="Request_Approve"){
			$subject = "Request for Sample Requisition Approval - Sample Requisition No. [".$data['samplerequisition_no']."] เรื่อง : ".$data['samplerequisition_name'];
			$message ="Dear User,<br>You have a new sample requisition. Please take a moment to review and approve in this sample requisition.<br><br>";
		}else{
			$subject = "Sample Requisition No. [".$data['samplerequisition_no']."] เรื่อง : ".$data['samplerequisition_name'];
			
			if($sample_status=="Create"){
				$message ="Dear User,<br>Your Sample Requisition has been created.<br><br>";
			}elseif($sample_status=="Approve"){
				$message ="Dear User,<br>Your Sample Requisition has been approved.<br><br>";
			}elseif($sample_status=="Cancel_Approve"){
				$message ="Dear User,<br>Your Sample Requisition has been rejected.<br><br>";
			}elseif($sample_status=="Cancel_Samplerequisition"){
				$message ="Dear User,<br>Your Sample Requisition has been cancelled.<br><br>";
			}
		}
		
		$to = $this->getUserEmailId('id',$assignto);		
		
		$this->email->subject($subject);
		$this->email->from( strip_tags($from),strip_tags($from_name));
		$this->email->to($to);

		$this->email->set_newline("\r\n");
		$this->email->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
		
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

	private function getUserEmailId($name,$val){

		$query = "SELECT email1, email2, yahoo_id ,user_name from aicrm_users WHERE status='Active' AND ";
		$query .= " id = '".$val."' ";
		$sql = $this->db->query($query);
		$data = $sql->result_array();
		return $data[0]['email1'];
	}
}
