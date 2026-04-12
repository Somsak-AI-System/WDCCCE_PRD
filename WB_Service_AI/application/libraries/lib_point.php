<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
if(! defined('BASEPATH')) exit('No direct script access allowed');

class lib_point
{

	function __construct()
	{
		$this->ci = & get_instance();
		$this->ci->load->library("common");
		$this->ci->load->library('crmentity');
		$this->ci->load->database();
		$this->ci->load->library('memcached_library');
		$this->_return = array(
			'status' => false,
			'error' => "",
			'result' => "",
		);
	}

	public function get_adjust($data=array())
	{
		$this->ci->db->reconnect();
		$action = @$data['action'];
		$brand = @$data['brand'];
		$channel = @$data['channel'];
		$point = @$data['point'];
		$accountid = @$data['accountid'];
		$pointid = @$data['pointid'];
		$redemp_type = @$data['type'];
		
		$this->ci->db->select('aicrm_account.accountid ,aicrm_account.point_total,aicrm_account.point_used,aicrm_account.point_remaining,aicrm_account.sap_no');
		$this->ci->db->join('aicrm_accountscf', 'aicrm_accountscf.accountid = aicrm_account.accountid');
		$this->ci->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_account.accountid');
		$this->ci->db->where(array(
			'aicrm_crmentity.deleted' => 0,
			'aicrm_account.accountid' => $accountid
		));
		
		$query = $this->ci->db->get('aicrm_account');
		$data_acc = $query->result_array();

		$query->next_result(); 
		$query->free_result();

		$point_total=@$data_acc[0]['point_total'];//คะแนนสะสมทั้งหมด (แต้ม)
		$point_used=@$data_acc[0]['point_used'];//คะแนนที่ใช้ไป (แต้ม)
		$point_remaining=@$data_acc[0]['point_remaining'];//คะแนนสะสมคงเหลือ (แต้ม)
		$sap_no = @$data_acc[0]['sap_no'];//หมายเลขลูกค้า SAP
		
		if($action == 'add'){
			$dateadd = date('Y-m-d');
			$dateexpired = date('Y-12-31', strtotime($dateadd . "+1 year"));

			$sql_add = "INSERT INTO aicrm_transaction_point(accountid, pointid, type, dateadd, dateexpired, channel, brand, points, balance) VALUES ('".$accountid."','".$pointid."','Add Point','".$dateadd."','".$dateexpired."','".$channel."','".$brand."','".$point."','".$point."');";
			$this->ci->db->query($sql_add);

			$point_total= ($data_acc[0]['point_total']+$point);//คะแนนสะสมทั้งหมด
			$point_remaining= ($data_acc[0]['point_remaining']+$point);//คะแนนสะสมคงเหลือ

			//$u_acc ="update aicrm_account set point_remaining = '".$point_remaining."', point_total= '".$point_total."' where accountid = '".$accountid."' ";
			$u_acc ="update aicrm_account set point_remaining = '".$point_remaining."', point_total= '".$point_total."' where sap_no = '".$sap_no."' ";
			$this->ci->db->query($u_acc);

		}else{

			$sql_point = "call get_transaction_point('".$accountid."','".$point."')";
			$query_point = $this->ci->db->query($sql_point);
			$data_point=$query_point->result_array();
			$this->ci->db->reconnect();
			
			foreach ($data_point as $key => $value) {
				
				$insert ="INSERT INTO aicrm_transaction_userpoint(accountid, pointid, redemp_type, date, brand, cut_point, balance, balance_stock, cut_point_stock, transactionpointid) VALUES ('".$accountid."','".$pointid."','".$redemp_type."','".date('Y-m-d')."','".$brand."','".$point."','".$value['remaining']."','".$value['last_balance']."','".$value['usepoint']."','".$value['transactionid']."');";
				$this->ci->db->query($insert);
				
				$update = "UPDATE aicrm_transaction_point SET balance = '".$value['last_balance']."' WHERE id = '".$value['transactionid']."' ";
				$this->ci->db->query($update);
			}

			$point_used= ($data_acc[0]['point_used']+$point);//คะแนนที่ใช้ไป
			$point_remaining= ($data_acc[0]['point_remaining']-$point);//คะแนนสะสมคงเหลือ
			
			//$u_acc ="update aicrm_account set point_used = '".$point_used."', point_remaining= '".$point_remaining."' where accountid = '".$accountid."' ";
			$u_acc ="update aicrm_account set point_used = '".$point_used."', point_remaining= '".$point_remaining."' where sap_no = '".$sap_no."' ";
			$this->ci->db->query($u_acc);
		}
		
		return $point_remaining;
		
	}
	
	
}
