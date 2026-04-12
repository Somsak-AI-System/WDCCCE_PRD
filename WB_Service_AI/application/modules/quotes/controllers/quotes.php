<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Quotes extends REST_Controller
{


  private $crmid;
  private $tab_name = array('aicrm_crmentity','aicrm_quotes','aicrm_quotescf','aicrm_quotesbillads','aicrm_quotesshipads');
  private $tab_name_index =  array('aicrm_crmentity'=>'crmid','aicrm_quotes'=>'quotationid' ,'aicrm_quotescf'=>'quoteid','aicrm_quotesbillads'=>'quotebilladdressid','aicrm_quotesshipads'=>'quoteshipaddressid');
  
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->model("quotes_model");
		$this->_module = "Quotes";
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
				'QuotesNo' => null
			),
		);
	}
	
	public function insert_content_post(){

	  	$this->common->_filename= "Insert_Quotes";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Quotes ==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert Quotes ==>',$a_request,$response_data);
	  
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
		// $DocNo=isset($a_request['accountid']) ? $a_request['accountid'] : "";
		$DocNo=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
	  
	  		if(count($data[0])>0 and $module=="Quotes"){

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

	public function get_quotes_post(){

		$this->common->_filename= "Detail_Quotes";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$this->common->set_log('Before Detail Quotes ==>',$url,$a_request);

		$response_data = $this->quotes_model->get_quotes($a_request);
		// alert($response_data);exit;

	  	$this->common->set_log('After Detail Quotes ==>',$a_request,$response_data);
	  
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
		$this->common->_filename= "Save_Quotes_Detail";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$a_request =$dataJson;
		
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$this->common->set_log('Before Quotes Detail ==>',$url,$a_request);

		$crmID = $a_request['crmID'];
		$sql = $this->db->get_where('aicrm_quotes', ['quoteid'=>$crmID]);
		$rowData = $sql->row_array();

		$this->db->select('aicrm_inventoryproductrel.*, 
		aicrm_crmentity.setype, 
		aicrm_products.productname, 
		aicrm_products.product_no,
		aicrm_products.selling_price,
		aicrm_products.stockqty	');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid=aicrm_inventoryproductrel.productid', 'inner');
		$this->db->join('aicrm_products', 'aicrm_products.productid=aicrm_inventoryproductrel.productid', 'left');
		//$this->db->join('aicrm_service', 'aicrm_service.serviceid=aicrm_inventoryproductrel.productid', 'left');
		//$this->db->join('aicrm_sparepart', 'aicrm_sparepart.sparepartid=aicrm_inventoryproductrel.productid', 'left');
		//$sql = $this->db->get_where('aicrm_inventoryproductrel', ['aicrm_inventoryproductrel.id'=>$crmID]);
		//$this->db->where('aicrm_inventoryproductrel.id'=>$crmID);
		$this->db->where(array('aicrm_inventoryproductrel.id' => $crmID));
		$this->db->order_by("aicrm_inventoryproductrel.sequence_no", "asc");
		$sql = $this->db->get('aicrm_inventoryproductrel');
		
		$itemList = $sql->result_array();
		
		array_walk_recursive($rowData, function (&$item, $key) {
            $item = null === $item ? '' : $item;
        });

		array_walk_recursive($itemList, function (&$item, $key) {
            $item = null === $item ? '' : $item;
        });

		$returnData = $this->_return;
		$returnData['data']['Crmid'] = $crmID;
		$returnData['data']['QuotesNo'] = $rowData['quote_no'];
		$returnData['data']['rowData'] = $rowData;
		$returnData['data']['itemList'] = $itemList;

		$this->response($returnData, 200);
	}

	public function save_product_list_post()
	{
		$this->common->_filename= "Save_Quotes_Detail";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$a_request =$dataJson;
		
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	  	$this->common->set_log('Before Quotes Detail ==>',$url,$a_request);

		$crmID = $a_request['crmID'];
		$updateData = $a_request['updateData'];
		$itemList = $a_request['itemList'];
		$this->db->update('aicrm_quotes', $updateData, ['quoteid'=>$crmID]);

		$this->db->delete('aicrm_inventoryproductrel', ['id'=>$crmID]);
		foreach($itemList as $item){
			$this->db->insert('aicrm_inventoryproductrel', $item);
		}

		$returnData = $this->_return;
		$returnData['data']['Crmid'] = $crmID;

		$this->response($returnData, 200);
	}

	public function save_product_list_temp_post()
	{
		$this->common->_filename = "Save_Quotes_Detail";
		header('Content-Type:application/json; charset=UTF-8');
		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body, true);
		$a_request = $dataJson;

		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$this->common->set_log('Before Quotes Detail ==>', $url, $a_request);

		$crmID = $a_request['crmID'];
		$updateData = $a_request['updateData'];
		$itemList = $a_request['itemList'];
		$this->db->update('aicrm_quotes', $updateData, ['quoteid' => $crmID]);

		$this->db->delete('aicrm_inventoryproductrel_temp', ['id' => $crmID]);
		foreach ($itemList as $item) {
			$this->db->insert('aicrm_inventoryproductrel_temp', $item);
		}

		$returnData = $this->_return;
		$returnData['data']['Crmid'] = $crmID;

		$this->response($returnData, 200);
	}
	
	public function update_status_post()
	{
		// quota_notapprove เหตุผลการไม่อนุมัติ
		// quota_cancel เหตุผลการยกเลิก
		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body,true);

		$this->common->_filename = "Update_Status_Quotes_Detail";
		header('Content-Type:application/json; charset=UTF-8');
		$a_request = $req;
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->set_log('Before Update Status ==>', $url, $a_request);
		
		$crmID = $req['crmid'];
		$userID = $req['userid'];
		$status = $req['status'];
		$reason = $req['reason'];
		$updateData = [];

		switch($status){
			case 'approve':

				$sql = $this->db->get_where('tbt_quotes_approve', ['crmid'=>$crmID, 'appstatus'=>0]);
				$count = $sql->num_rows();

				if($count > 1){
					$quotesstatus = 'อนุมัติใบเสนอราคา';
					$quotation_status = 'ขออนุมัติใบเสนอราคา';
					$approve_status = 'Request_Approve';
					$approveflag = 0;
				}else{
					$quotesstatus = 'อนุมัติใบเสนอราคา';
					$quotation_status = 'อนุมัติใบเสนอราคา';
					$approve_status = 'Approve';
					$approveflag = 1;
				}

				$sql = "UPDATE tbt_quotes_approve SET appstatus=1, upuser=".$userID.", updatedate='".date('Y-m-d H:i:s')."' WHERE userid=".$userID." AND crmid=".$crmID." AND appstatus<>1 ORDER BY level ASC LIMIT 1";
				$this->db->query($sql);
				
				$updateData = [
					'quotation_status' => $quotation_status
				];
				break;
			case 'reject':
				$quotesstatus = 'ไม่อนุมัติใบเสนอราคา';
				$updateData = [
					'quotation_status' => $quotesstatus,
					'quota_notapprove' => $reason
				];
				$approve_status = 'Cancel_Approve';
				$approveflag = 0;
				break;
			case 'cancel':
				$quotesstatus = 'ยกเลิกใบเสนอราคา';
				$updateData = [
					'quotation_status' => $quotesstatus,
					'quota_cancel' => $reason
				];
				$approve_status = 'Cancel_Quotation';
				$approveflag = 0;
				break;
			case 'change':
				$quotesstatus = 'เปลี่ยนแปลงใบเสนอราคา';
				$updateData = [
					'quotation_status' => $quotesstatus
				];
				$approve_status = 'Revise';
				$approveflag = 0;
				break;
			case 'close':
				$quotesstatus = 'ปิดการขาย';
				$updateData = [
					'quotation_status' => $quotesstatus
				];
				$approve_status = 'Complete';
				$approveflag = 0;
				break;
		}

		if($approveflag == 1){

			$sql = "select quotation_type, accountid, event_id, contactid, project_name from aicrm_quotes where quoteid='".$crmID."'";
			$a_sql = $this->db->query($sql);
			$a_data = $a_sql->row_array();
			
			if($a_data["quotation_type"] == "Special Price"){

				$sql_pricelist = "SELECT aicrm_pricelists.pricelistid  FROM aicrm_pricelists
		                	INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
		                	INNER JOIN aicrm_pricelistscf ON aicrm_pricelists.pricelistid = aicrm_pricelistscf.pricelistid
		               		WHERE
		                    aicrm_crmentity.deleted = 0
		                    AND aicrm_pricelists.pricelist_enddate >= '".date('Y-m-d')."'
		                    AND aicrm_pricelists.accountid = '".$a_data["accountid"]."'
		                    AND aicrm_pricelists.projectsid = '".$a_data["event_id"]."' ORDER BY aicrm_pricelists.pricelistid DESC LIMIT 1";

		        $a_pricelist = $this->db->query($sql_pricelist);
				$data_pricelist = $a_pricelist->row_array();
				
				if(count($data_pricelist) == 0){

					$p_module = "PriceList";
					$p_crmid = "";
					$p_action = "add";
					
					$p_tab_name =  array('aicrm_crmentity','aicrm_pricelists','aicrm_pricelistscf');
					$p_tab_name_index =  array('aicrm_crmentity'=>'crmid','aicrm_pricelists'=>'pricelistid' ,'aicrm_pricelistscf'=>'pricelistid');

					$p_data[0]['status_pricelist'] = "Active";
					$p_data[0]['pricelist_type'] = "Special Price";
					$p_data[0]['quoteid'] = $crmID;
					$p_data[0]['pricelist_startdate'] = date('Y-m-d');
					$p_data[0]['pricelist_enddate'] = date('Y-12-31');
					$p_data[0]['accountid'] = $a_data["accountid"];
					$p_data[0]['contactid'] = $a_data["contactid"];
					$p_data[0]['projectsid'] = $a_data["event_id"];
					$p_data[0]['project_name'] = $a_data["project_name"];
					$p_data[0]['smownerid'] = $userID;

					list($chk,$pcrmid,$DocNo)=$this->crmentity->Insert_Update($p_module,$p_crmid,$p_action,$p_tab_name,$p_tab_name_index,$p_data,$userID);

					$pricelistid = $pcrmid;
					
					$acc_sql = "select accountname from aicrm_account where accountid='".$a_data['accountid']."' limit 1 ";
					$sql = $this->db->query($acc_sql);
					$data_acc = $sql->row_array();					
					
					$pricelist_name = $DocNo." - ".$a_data["project_name"]." - ".$data_acc["accountname"];
					$this->db->update('aicrm_pricelists', ['pricelist_name'=>$pricelist_name], ['pricelistid'=>$pcrmid]);

				}else{
					$pricelistid = $data_pricelist["pricelistid"];
				}

				// Insert Update item in Pricelist
				if($pricelistid != '' && $crmID != ''){
					$sql_quotes = "
					SELECT
						aicrm_quotes.pricetype,
						aicrm_inventoryproductrel.quantity,
						aicrm_quotes.project_name,
					CASE WHEN aicrm_quotes.pricetype = 'Include Vat' THEN
							REPLACE ( FORMAT( aicrm_inventoryproductrel.selling_price /( 1+ ( aicrm_inventoryproductrel.tax1 / 100 )), 2 ), ',', '' ) ELSE aicrm_inventoryproductrel.selling_price 
						END AS selling_price,
					CASE WHEN aicrm_quotes.pricetype = 'Include Vat' THEN
							aicrm_inventoryproductrel.selling_price ELSE
					CASE WHEN aicrm_inventoryproductrel.tax1 != '' THEN
						REPLACE ( FORMAT( aicrm_inventoryproductrel.selling_price *( 1+ ( aicrm_inventoryproductrel.tax1 / 100 )), 2 ), ',', '' ) ELSE REPLACE ( FORMAT(( aicrm_inventoryproductrel.selling_price * 107 )/ 100, 2 ), ',', '' ) 
							END 
							END AS selling_price_inc,
							aicrm_inventoryproductrel.productid,
							aicrm_inventoryproductrel.product_finish,
							aicrm_inventoryproductrel.product_size_mm,
							aicrm_inventoryproductrel.product_thinkness,
							aicrm_inventoryproductrel.product_unit,
							aicrm_inventoryproductrel.product_cost_avg 
						FROM
							aicrm_quotes
							INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
							INNER JOIN aicrm_quotescf ON aicrm_quotes.quoteid = aicrm_quotescf.quoteid
							LEFT JOIN aicrm_inventoryproductrel ON aicrm_inventoryproductrel.id = aicrm_quotes.quoteid 
						WHERE
						aicrm_crmentity.deleted = 0 
						AND aicrm_inventoryproductrel.id = '".$crmID."'
					";
					$sql_q = $this->db->query($sql_quotes);	
					$data_quotes  = $sql_q->result_array() ;
					//$a_data_quotes = $myLibrary_mysqli->select($sql_quotes);

					if(!empty($data_quotes) && count($data_quotes)>0){
						foreach ($data_quotes as $k => $v){
							$sql_inventorypricelist = "SELECT
							aicrm_pricelists.pricelistid,
							aicrm_inventorypricelist.product_finish,
							aicrm_inventorypricelist.product_size_mm,
							aicrm_inventorypricelist.product_thinkness,
							aicrm_inventorypricelist.quotation_qty,
							aicrm_inventorypricelist.product_unit,
							aicrm_inventorypricelist.product_cost_avg,
							aicrm_inventorypricelist.selling_price,
							aicrm_inventorypricelist.selling_price_inc
							FROM
							aicrm_pricelists
							INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
							INNER JOIN aicrm_pricelistscf ON aicrm_pricelists.pricelistid = aicrm_pricelistscf.pricelistid
							LEFT JOIN aicrm_inventorypricelist ON aicrm_inventorypricelist.id = aicrm_pricelists.pricelistid 
							WHERE
							aicrm_crmentity.deleted = 0
							AND aicrm_pricelists.pricelistid = '".$pricelistid."'
							AND aicrm_inventorypricelist.id = '".$pricelistid."'
							AND aicrm_inventorypricelist.productid = '".$v['productid']."'
							";
							$sqlcheck = $this->db->query($sql_inventorypricelist);
							$a_check_inventorypricelist = $sqlcheck->row_array();

							// Update item
							if(count($a_check_inventorypricelist) > 0){
								$sql_update = "UPDATE aicrm_inventorypricelist SET product_finish='".$v['product_finish']."', product_size_mm='".$v['product_size_mm']."', product_thinkness='".$v['product_thinkness']."', quotation_qty='".$v['quantity']."', product_unit='".$v['product_unit']."', product_cost_avg='".$v['product_cost_avg']."' ,selling_price='".$v['selling_price']."', selling_price_inc='".$v['selling_price_inc']."' WHERE id='".$pricelistid."' AND productid='".$v['productid']."'";
								
								$this->db->query($sql_update);

							}else{
								$sql_insert = "INSERT INTO aicrm_inventorypricelist(
									id, productid, product_finish, product_size_mm, product_thinkness, quotation_qty, product_unit, product_cost_avg, selling_price, selling_price_inc 
								) values ('".$pricelistid."','".$v['productid']."','".$v['product_finish']."','".$v['product_size_mm']."','".$v['product_thinkness']."','".$v['quantity']."','".$v['product_unit']."','".$v['product_cost_avg']."','".$v['selling_price']."','".$v['selling_price_inc']."' )";
								$this->db->query($sql_insert);
							}

						}
					}
				}
			}

		}

		// Create Request Approve Log
		$this->db->insert('tbt_quotes_log', [
			'crmid' => $crmID,
			'assignto' => 0,
			'quotesstatus' => $quotesstatus,
			'adduser' => $userID,
			'adddate' => date('Y-m-d H:i:s')
		]);

		$this->db->update('aicrm_quotes', $updateData, ['quoteid'=>$crmID]);
		$this->db->update('aicrm_crmentity', ['modifiedtime'=>date('Y-m-d H:i:s'), 'modifiedby'=>$userID], ['crmid'=>$crmID]);

		$assignto = $this->get_update_assignto($approve_status,$crmID,$approveflag);
		$this->db->update('aicrm_crmentity', ['smownerid'=>$assignto], ['crmid'=>$crmID]);

		//if($assignto !=''){
		$sendmail = $this->send_mail_quotes('approve',$approve_status, $crmID, $assignto);
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
			$this->db->insert('tbt_quotes_approve', [
				'crmid' => $crmID,
				'userid' => $user['userid'],
				'username' => $user['username'],
				'level' => $user['level']
			]);
		}

		// Create Request Approve Log
		$this->db->insert('tbt_quotes_log', [
			'crmid' => $crmID,
			'assignto' => 0,
			'quotesstatus' => 'ขออนุมัติใบเสนอราคา',
			'adduser' => $userID,
			'adddate' => date('Y-m-d H:i:s')
		]);

		$this->db->update('aicrm_quotes', ['quotation_status'=>'ขออนุมัติใบเสนอราคา'], ['quoteid'=>$crmID]);
		$this->db->update('aicrm_crmentity', ['modifiedtime'=>date('Y-m-d H:i:s'), 'modifiedby'=>$userID], ['crmid'=>$crmID]);

		$assignto = $this->get_update_assignto('Request_Approve',$crmID);
		$this->db->update('aicrm_crmentity', ['smownerid'=>$assignto], ['crmid'=>$crmID]);
		
		if($assignto !=''){
			$sendmail = $this->send_mail_quotes('approve','Request_Approve', $crmID, $assignto);
		}
		$returnData = $this->_return;
		$returnData['Message'] = 'Request Approve Success';
		$returnData['data']['Crmid'] = $crmID;

		$this->response($returnData, 200);
	}

	private function get_update_assignto($quotationstatus="",$crmid,$approveflag=0)
	{
		if(($approveflag==1 && $quotationstatus == 'Approve') || $quotationstatus == 'Cancel_Approve'){
			$query = "select smcreatorid as userid
				from aicrm_crmentity
				where crmid='".$crmid."'
				limit 1	";
		}else{
			$query = "select userid
				from tbt_quotes_approve
				where crmid='".$crmid."'
				and appstatus = 0
				order by level,username
				limit 1	";
		}
		$sql = $this->db->query($query);
		$a_data = $sql->row_array();
		return $a_data["userid"];
	}

	public function get_approver_post(){
		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body,true);

		$crmID = $req['crmid'];
		$userID = $req['userid'];

		// Select Approver
		$sql = $this->db->get_where('tbt_quotes_approve', ['crmid'=>$crmID]);
		$approver = $sql->result_array();

		$returnData = $this->_return;
		$returnData['Message'] = 'Success';
		$returnData['data']['Crmid'] = $crmID;
		$returnData['data']['Approvers'] = $approver;

		$this->response($returnData, 200);
	}

	public function get_quote_no_rev_post()
	{

		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body, true);

		$crmID = $req['crmid'];

		$query = "select 
		aicrm_quotes.quote_no,
		aicrm_quotes.rev_no,
		aicrm_quotes.quote_no_rev		
		from aicrm_quotes
		left join aicrm_quotescf on  aicrm_quotes.quoteid=aicrm_quotescf.quoteid
		left join aicrm_crmentity c  on aicrm_quotes.quoteid  = c.crmid 
		where 1 
		and c.deleted <> 1 
		and aicrm_quotes.quoteid='" . $crmID . "'
		group by aicrm_quotes.quoteid";

		$sql = $this->db->query($query);
		$rs = $sql->row_array();

		$data_quote_no = $rs['quote_no'];
		$data_rev_no = $rs['rev_no'];
		$data_quote_no_rev = $rs['quote_no_rev'];

		if ($data_rev_no == "" || $data_rev_no == "0") {
			$data_rev = "1";
		} else {
			$data_rev = $data_rev_no + 1;
		}

		$temp = [
			'data_quote_no' => $data_quote_no,
			'data_rev' => $data_rev
		];

		$returnData = $this->_return;
		$returnData['Message'] = 'Success';
		$returnData['data']['Crmid'] = $crmID;
		$returnData['data']['dataRev'] = $temp;

		$this->response($returnData, 200);
	}
	
	private function getApprover($crmID){
		$query = "SELECT approve_level1, approve_level2, approve_level3, approve_level4 FROM aicrm_quotes WHERE quoteid=".$crmID;
		$sql = $this->db->query($query);
		$rs = $sql->row_array();

		$approver = [];
		if($rs['approve_level1'] != ''){
			$apLv1 = explode(' |##| ', $rs['approve_level1']);
			foreach($apLv1 as $ap){
				$approver[] = [
					'level' => '1',
					'user' => $ap
				];
			}
		}

		if($rs['approve_level2'] != ''){
			$apLv2 = explode(' |##| ', $rs['approve_level2']);
			foreach($apLv2 as $ap){
				$approver[] = [
					'level' => '2',
					'user' => $ap
				];
			}
		}

		if($rs['approve_level3'] != ''){
			$apLv3 = explode(' |##| ', $rs['approve_level3']);
			foreach($apLv3 as $ap){
				$approver[] = [
					'level' => '3',
					'user' => $ap
				];
			}
		}

		if($rs['approve_level4'] != ''){
			$apLv4 = explode(' |##| ', $rs['approve_level4']);
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

	public function get_buyer_post()
	{
		$request_body = file_get_contents('php://input');
		$req = json_decode($request_body, true);

		$quotation_buyer = $req['quotation_buyer'];
		
		$query = "select address,phone,email from aicrm_config_vendorbuyer where type='Buyer' and deleted = 0";
		$query .= " and name = '".$quotation_buyer."' and status = 'Active' ";

		$sql = $this->db->query($query);
		$rs = $sql->result_array();
		
		$returnData = $this->_return;
		$returnData['Message'] = 'Success';
		$returnData['data']['Crmid'] = '';
		$returnData['value'] = @$rs[0];
		$this->response($returnData, 200);
	}

	private function getUserEmailId($name,$val){

		$query = "SELECT email1, email2, yahoo_id ,user_name from aicrm_users WHERE status='Active' AND ";
		$query .= " id = '".$val."' ";
		$sql = $this->db->query($query);
		$data = $sql->result_array();
		return $data[0]['email1'];
	}

  	private function send_mail_quotes($submodule="approve",$quotation_status='',$crmid='',$assignto='')
	{
	
		$this->load->library('email');
		$this->config->load('email');
		$mail = $this->config->item('mail');

		$from = $mail[$submodule]["from"];
		$from_name = $mail[$submodule]["from_name"];

		$a_data = "SELECT * FROM aicrm_quotes WHERE quoteid=".$crmid;
		$sql = $this->db->query($a_data);
		$data = $sql->row_array();

		if($quotation_status=="Request_Approve"){
			$subject = "Request for Quotation Approval - Quote No. [".$data['quote_no']."] เรื่อง : ".$data['quote_name'];
			$message ="Dear User,<br>You have a new quotation. Please take a moment to review and approve in this quote.<br><br>";
		}else{
			$subject = "Quote No. [".$data['quote_no']."] เรื่อง : ".$data['quote_name'];
			
			if($quotation_status=="Create"){
				$message ="Dear User,<br>Your Quotation has been created.<br><br>";
			}elseif($quotation_status=="Approve"){
				$message ="Dear User,<br>Your Quotation has been approved.<br><br>";
			}elseif($quotation_status=="Cancel_Approve"){
				$message ="Dear User,<br>Your Quotation has been rejected.<br><br>";
			}elseif($quotation_status=="Cancel_Quotation"){
				$message ="Dear User,<br>Your Quotation has been cancelled.<br><br>";
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

}
