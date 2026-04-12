<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_truckcancel
{

  function __construct()
  {
    $this->ci = & get_instance();

    $this->ci->load->config('api');
    $this->ci->load->library('curl');
    $this->ci->load->library('common');
	//$this->_empcd	=  $this->session->userdata('user.username');
  }

  private function get_flag($queueno=null,$queuedtlno=null)
  {
  	$sql = " select  ci_das,ci_sap,ci_ap,wi_das,wi_sap,wi_ap,wo_das,wo_sap,wo_ap,go_das,go_sap,go_ap
  			  from  tbt_lgst_queuedtl_saveflag where  queuedtlno = '".$queuedtlno."'  and queueno = '".$queueno."'  ";
  	$a_data_queue= $this->ci->db_api->getresult($sql );
	//return $sql ;
	return $a_data_queue;
  }

  public function set_flag($a_param=array(),$type='')
  {
  	if(empty($a_param)) return false;
	if($type=="O")
	{
		$flgTbl="tbt_lgst_queuedtl_saveflag";		
	}
	else
	{
		$flgTbl="tbt_lgst_queuedtl_mat_saveflag";
		
	}
	
  	$sql = " update  ".$flgTbl."  set   ";
  	$sql .= $this->gen_query($a_param);
	$sql .= "  where   queuedtlno = '".$a_param["queuedtlno"]."'  ";
	//$sql .= "  and queueno = '".$a_param["queueno"]."'   ";

  	$data = $this->ci->db_api->query($sql);
  	$this->ci->common->set_log("update cancel_flg ".$sql,$a_param,$data);
  }
  
  
    public function set_reasoncd($a_param=array())
  {
  	if(empty($a_param)) return false;
	
	if($a_param['type']=="O")
	{
		$tbl="tbt_lgst_queuedtl";		
	}
	else
	{
		$tbl="tbt_lgst_queuedtl_mat";
		
	}
	

	
  	$sql = " update  ".$tbl."  set cancelreasoncd ='".$a_param["reasoncd"]."' ";
	$sql .= ",cancelreasontext='".$a_param["reasontext"]."'";
	$sql .= "  where   queuedtlno = '".$a_param["queuedtlno"]."'  ";
	//$sql .= "  and queueno = '".$a_param["queueno"]."'   ";

  	$data = $this->ci->db_api->query($sql);
  	$this->ci->common->set_log("update cancel_flg ".$sql,$a_param,$data);
  }

	public function gen_query($a_param=array())
	{
		$query= "";
			foreach($a_param as $key => $value) {    	
			$query = $key."="."'".$value."'";
           }
	  return $query;
		
	}
  public function get_ws_flg($a_param=array())
  {
	  $queueno 		= $a_param['queueno'];
	  $queuedtlno 	= $a_param['queuedtlno'];
	  $pre_do 			= $a_param['pre_do'];
	  $stage 			= $a_param['stage'];
	  $type 				= $a_param['type'];
	  $sql =" exec p_get_saveflag  $queueno,$queuedtlno ,'$pre_do',  '$type' , '$stage'  ";	  
	  $data_flg = $this->ci->db_api->getresult($sql );	  
	 return    $data_flg;
	 }
	 	 
  public function get_param_data($a_param=array())
  {
	  $queueno 		= $a_param['queueno'];
	  $queuedtlno 	= $a_param['queuedtlno'];
	  $pre_do 			= $a_param['pre_do'];
	  $stage 			= $a_param['stage'];
	  $type 				= $a_param['type'];
	  $Ttable ="";
	  $DTable ="";
	  $DoTable ="";
	  $SoTable ="";
		if($type=="O")
		{
		  $Ttable 		= "tbt_lgst_queue";
	 	  $DTable 		= "tbt_lgst_queuedtl";
	 	  $DoTable 	= "tbt_sale_do";
	  	  $SoTable 	= "tbt_sale_so";
		
	   }
			
	if($type=="I")
	{
		  $Ttable 		= "tbt_lgst_queue_mat";
	 	  $DTable 		= "tbt_lgst_queuedtl_mat";
	 	  $DoTable 	= "tbt_sale_do_mat";
	  	  $SoTable 	= "tbt_sale_so_mat";
		
		
		}
			$sql = " select 
					dtl.shipno , dtl.ldt_no as pre_do 
					,LEFT(dtl.gi_datetime,8) as gi_date
					,RIGHT(dtl.gi_datetime,6) as gi_time
					,LEFT(dtl.ci_datetime,8) as ci_date
					,RIGHT(dtl.ci_datetime,6) as ci_time
					,LEFT(dtl.wi_datetime,8) as wi_date
					,RIGHT(dtl.wi_datetime,6) as wi_time
					,LEFT(dtl.li_datetime,8) as li_date
					,RIGHT(dtl.li_datetime,6) as li_time
					,LEFT(dtl.lo_datetime,8) as lo_date
					,RIGHT(dtl.lo_datetime,6) as lo_time
					,LEFT(dtl.wo_datetime,8) as wo_date
					,RIGHT(dtl.wo_datetime,6) as wo_time
					,LEFT(dtl.go_datetime,8) as go_date
					,RIGHT(dtl.go_datetime,6) as go_time
					,dtl.sealno
					,dtl.weightInQty
					,dtl.weightOutQty
					from  ".$Ttable." 	 q 
					inner join ".$DTable." dtl on q.queueno=dtl.queueno
					inner join ".$DoTable ." do on dtl.dono=do.dono and  dtl.dodtlno=do.dodtlno and isnull(dtl.ldt_no,'')=isnull(do.ldt_no ,'')
					inner join ". $SoTable." so on so.sono=do.sono and  so.sodtlno=do.sodtlno
					where q.queueno =  $queueno  and  dtl.queuedtlno=$queuedtlno  ";
	 $param_data = $this->ci->db_api->getresult($sql );	  
	 return   $param_data ;
	 }	 
	
	
	
	
	
	 public function cancel_gatein($a_params=array()){
	  $empcd= $a_params['empcd'];
	  $queueno=  $a_params['queueno'];

		$this->ci->common->_filename= "cancel_gatein";
					 	$sql = "execute p_das_cancelgatein  '$queueno', '".$a_params['type']."' , '$empcd' ";
  						$data = $this->ci->db_api->getresult($sql);
					//	return 	$data ;
						$this->ci->common->set_log("update call  das  ".$sql,"",$data);
  						if( !$data or $data[0]['isError'] =="true"){
						  		return $a_return_ap["Cannot cancel Gatein"];
							}
						
		return "Cancel Gate In Successfully";	
		
	  }
	  
	  public function cancel_LabReject($a_params=array())
  {	
  	$this->ci->common->_filename= "cancel_reject";
  	$queueno = $a_params["queueno"];
  	$queuedtlno = $a_params["queuedtlno"];
  	$update_status["queueno"] = $queueno;
	$update_status["queuedtlno"] = $queuedtlno;
	if($a_params["type"]=="O"){
		$sql_flg = " select  lab_wo_sap ,	lab_wi_ap 	,lab_ci_ap,lab_gi_das from tbt_lgst_queuedtl_saveflag 
						where queueno=".$queueno ." and  queuedtlno =".$queuedtlno."   ";
		$data_flg  = $this->ci->db_api->getresult($sql_flg);
	}
	else
	{
		$sql_flg = " select  lab_wo_sap ,	lab_wi_ap 	,lab_ci_ap,lab_gi_das from tbt_lgst_queuedtl_mat_saveflag 
						where queueno=".$queueno ." and  queuedtlno =".$queuedtlno."   ";
		$data_flg  = $this->ci->db_api->getresult($sql_flg);
		}
		$this->set_reasoncd($a_params);		
		
	if($data_flg[0]['lab_wo_sap']=='Y'){
		if(	$a_params["type"]=="O" ){
			
						$method = "SAPWeightOut";
						$param = array(
							"Value1" =>$queueno, 
							"Value2" =>$queuedtlno,  // no need to send queuedtlno
							"Value3" =>'15',
							"Value4" =>$a_params["type"],
							"ChannelCode" => "WEB",
							"UserName" => $a_params['empcd']
						);
					$a_return_sap = $this->call_sap($method,$param);
					$update_status["lab_wo_sap"] = $a_return_sap["status"]?"N":"Y";
					$this->set_flag($update_status,$a_params["type"]);
					if( !$a_return_sap["status"]){			
					return $a_return_sap["msg"];
				    } // End SAP process
					
			}else
			{
						$method = "SAPCancelWeightIn";
						$param = array(
							"Value1" =>$queueno, 
							"Value2" =>$queuedtlno,  // no need to send queuedtlno
							"Value3" =>'10',
							"Value4" =>$a_params["type"],
							"ChannelCode" => "WEB",
							"UserName" => $a_params['empcd']
						);
					$a_return_sap = $this->call_sap($method,$param);
					$update_status["lab_wo_sap"] = $a_return_sap["status"]?"N":"Y";
					$this->set_flag($update_status,$a_params["type"]);
					if( !$a_return_sap["status"]){			
					return $a_return_sap["msg"];
				    } // End SAP process
			
			
			}
		}
	
	if($data_flg[0]['lab_wi_ap']=='Y'){
		
			if(	$a_params["type"]=="O" ){
							$method = "ISMARTAPSendTruckStatusCancelWeightIn";
							$ap_params= $this->get_param_data($a_params);		
						//	return 	$ap_params;			
							$param = array(
									"ChannelCode" =>"WEB",
									"UserName" =>$a_params["empcd"], 
									"PCName" =>"WEB", // Shipment
									"Value1" =>$ap_params["0"]["shipno"],// Shipment
									"Value2" =>$ap_params["0"]["pre_do"],
									"Value3" =>$ap_params["0"]["wi_date"],
									"Value4" =>$ap_params["0"]["wi_time"],
									"Value5" =>$ap_params["0"]["weightInQty"],
							);
							$a_return_ap = $this->call_ap($method,$param);
							$update_status["wi_ap"] = $a_return_ap["status"]?"N":"Y";
							$this->set_flag($update_status,$a_params["type"]);
							if( !$a_return_ap["status"]){
						  		return $a_return_ap["msg"];
							}
			}
	}
					
		/*if($data_flg[0]['lab_ci_ap']=='Y'){				
							$method = "ISMARTAPSendTruckStatusCheckIn";
							$ap_params= $this->get_param_data($a_params);							
							$param = array(
									"ChannelCode" =>"WEB",
									"UserName" =>$a_params["empcd"], 
									"PCName" =>"WEB", // Shipment
									"Value1" =>$ap_params["0"]["shipno"],// Shipment
									"Value2" =>$ap_params["0"]["gi_date"],
									"Value3" =>$ap_params["0"]["gi_time"],
									"Value4" =>$ap_params["0"]["ci_date"],
									"Value5" =>$ap_params["0"]["ci_time"],
							);
							$a_return_ap = $this->call_ap($method,$param);
							$update_status["lab_ci_ap"] = $a_return_ap["status"]?"N":"Y";
							$this->set_flag($update_status,$a_params["type"]);
							if( !$a_return_ap["status"]){
						  			return $a_return_ap["msg"];
							}
		}*/

			if($data_flg[0]['lab_gi_das']=='Y'){	
					 	$sql = "execute p_das_lab_reject  ".$queueno.",".$queuedtlno.",  '".$a_params['type']."','".$a_params["reasoncd"]."','".$a_params["reasontext"] ."','".$a_params["empcd"]."'";
  						$data = $this->ci->db_api->query($sql);
					//	return $data;
  						$this->ci->common->set_log("update call  das  ".$sql,"",$data);
  						$update_status["lab_gi_das"] = ($data) ?"N":"Y";
						$this->set_flag($update_status,$a_params["type"]);
						
							if( !$data){
						  		return "Lab Reject Failed" ;
							}
				
			   }
					
						return "Lab Reject Successfully";
		
					
	// All process successfully
	  	
	
  }   
  public function cancel_checkin($a_params=array())
  {	
  	$this->ci->common->_filename= "cancel_checkin";
  	$queueno = $a_params["queueno"];
  	$queuedtlno = $a_params["queuedtlno"];
  	$update_status["queueno"] = $queueno;
	$update_status["queuedtlno"] = $queuedtlno;
  	$a_data_queue = $this->get_ws_flg($a_params);
//	return $a_data_queue ;
	if(empty($a_data_queue) || count($a_data_queue) <= 0){
  		return false;
  	}
		if($a_data_queue[0]['sap_flg'] =="Y")
				{
						$method = "SAPCancelCheckIn";
						$param = array(
							"Value1" =>$a_params["queueno"], 
							"Value2" =>0,  // no need to send queuedtlno
							"Value3" =>$a_params['stage'],
							"Value4" =>$a_params['type'],
							"ChannelCode" => "WEB",
							"UserName" => $a_params['empcd']
						);
					$a_return_sap = $this->call_sap($method,$param);
					$update_status["ci_sap"] = $a_return_sap["status"]?"N":"Y";
					$this->set_flag($update_status,$a_params['type']);
					if( !$a_return_sap["status"]){
				
					return $a_return_sap["msg"];
				
				    } // End SAP process
				
				}
				
				if($a_data_queue[0]['ap_flg'] =="Y")
				{
							$method = "ISMARTAPSendTruckStatusCancelCheckIn";
							$ap_params= $this->get_param_data($a_params);							
							$param = array(
									"ChannelCode" =>"WEB",
									"UserName" =>$a_params["empcd"], 
									"PCName" =>"WEB", // Shipment
									"Value1" =>$ap_params["0"]["shipno"],// Shipment
									"Value2" =>$ap_params["0"]["gi_date"],
									"Value3" =>$ap_params["0"]["gi_time"],
									"Value4" =>$ap_params["0"]["ci_date"],
									"Value5" =>$ap_params["0"]["ci_time"],
							);
							$a_return_ap = $this->call_ap($method,$param);
							$update_status["ci_ap"] = $a_return_ap["status"]?"N":"Y";
							$this->set_flag($update_status,$a_params['type']);
							if( !$a_return_ap["status"]){
						  		return $a_return_ap["msg"];
							}
				} // End  AP process
				
			if($a_data_queue[0]['das_flg'] =="Y")
				{
					 	$sql = "execute p_das_cancelcheckin_multi  '$queueno',  '".$a_params['type']."' ";
  						$data = $this->ci->db_api->query($sql);
					//	return $data;
  						$this->ci->common->set_log("update call  das  ".$sql,"",$data);
  						$update_status["ci_das"] = ($data) ?"N":"Y";
						$this->set_flag($update_status,$a_params['type']);
						if(!$data)
						{
							return "Cancel CheckIn failed";
							}
						
				}
				

	// All process successfully
	  	return "Cancel CheckIn Successfully";
	
  }
  
   public function cancel_weightin2($a_params=array())
  { 

  	$this->ci->common->_filename= "cancel_weightin";
  	$queueno = $a_params["queueno"];
  	$queuedtlno = $a_params["queuedtlno"];
  	$update_status["queueno"] = $queueno;
	$update_status["queuedtlno"] = $queuedtlno;

  	$a_data_queue = $this->get_ws_flg($a_params);
//	return  	$a_data_queue ;
	if(empty($a_data_queue) || count($a_data_queue) <= 0){
  		return false;
  	}		
	
		if($a_data_queue[0]['sap_flg'] =="Y")
				{
						$method = "SAPCancelWeightIn";
						$param = array(
							"Value1" =>$a_params["queueno"], 
							"Value2" =>$a_params["queuedtlno"],  // no need to send queuedtlno
							"Value3" =>$a_params['stage'],
							"Value4" =>$a_params['type'],
							"ChannelCode" => "WEB",
							"UserName" => $a_params['empcd']
						);
					$a_return_sap = $this->call_sap($method,$param);
					$update_status["wi_sap"] = $a_return_sap["status"]?"N":"Y";
					$this->set_flag($update_status,$a_params['type']);
					if( !$a_return_sap["status"]){
					return $a_return_sap["msg"];				
				    } // End SAP process
				
				}
				
				if($a_data_queue[0]['ap_flg'] =="Y")
				{
							$method = "ISMARTAPSendTruckStatusCancelWeightIn";
							$ap_params= $this->get_param_data($a_params);		
						//	return 	$ap_params;			
							$param = array(
									"ChannelCode" =>"WEB",
									"UserName" =>$a_params["empcd"], 
									"PCName" =>"WEB", // Shipment
									"Value1" =>$ap_params["0"]["shipno"],// Shipment
									"Value2" =>$ap_params["0"]["pre_do"],
									"Value3" =>$ap_params["0"]["wi_date"],
									"Value4" =>$ap_params["0"]["wi_time"],
									"Value5" =>$ap_params["0"]["weightInQty"],
							);
							$a_return_ap = $this->call_ap($method,$param);
							$update_status["wi_ap"] = $a_return_ap["status"]?"N":"Y";
							$this->set_flag($update_status,$a_params['type']);
							if( !$a_return_ap["status"]){
						  		return $a_return_ap["msg"];
							}
				} // End  AP process
	
			if($a_data_queue[0]['das_flg'] =="Y")
				{
					 	$sql = "execute p_das_cancelweightin  '$queueno',  '$queuedtlno', '".$a_params['type']."' ";
  						$data = $this->ci->db_api->query($sql);
						$this->ci->common->set_log("update call  das  ".$sql,"",$data);
  						$update_status["wi_das"] = ($data) ?"N":"Y";
						$this->set_flag($update_status,$a_params['type']);
							if( !$data){
								$update_status["wi_das"] = ($data) ?"Y":"N";
						  		return $a_return_ap["Cannot cancel in DAS"];
							}
						
				}
		//	$update_status["wi_das"]="Y";
		//	$update_status["wi_ap"]="Y";
	//		$update_status["wi_sap"]="Y";
	//		$this->set_flag($update_status,$a_params['type']);
	// All process successfully
	  	return "Cancel Weight In Successfully";
    
  }
   public function cancel_weightout2($a_params=array())
  { 

  	$this->ci->common->_filename= "cancel_weightout";
  	$queueno = $a_params["queueno"];
  	$queuedtlno = $a_params["queuedtlno"];
  	$update_status["queueno"] = $queueno;
	$update_status["queuedtlno"] = $queuedtlno;

  	$a_data_queue = $this->get_ws_flg($a_params);
//	return  	$a_data_queue ;
	if(empty($a_data_queue) || count($a_data_queue) <= 0){
  		return false;
  	}
		
	//	"Value3" =>$a_params['stage'],
		if($a_data_queue[0]['sap_flg'] =="Y")
				{
					$method = "SAPCancelWeightOut";
				//			$method = "SAPWeightOut";
						$param = array(
							"Value1" =>$a_params["queueno"], 
							"Value2" =>$a_params["queuedtlno"],  // no need to send queuedtlno
							"Value3" =>'16',
							"Value4" =>$a_params['type'],
							"ChannelCode" => "WEB",
							"UserName" => $a_params['empcd']
						);
					$a_return_sap = $this->call_sap($method,$param);
					$update_status["wo_sap"] = $a_return_sap["status"]?"N":"Y";
					$this->set_flag($update_status,$a_params['type']);
					if( !$a_return_sap["status"]){
					return $a_return_sap["msg"];				
				    } // End SAP process
				
				}
				
				if($a_data_queue[0]['ap_flg'] =="Y")
				{
							$method = "ISMARTAPSendTruckStatusCancelWeightOut";
							$ap_params= $this->get_param_data($a_params);		
						//	return 	$ap_params;			


							$param = array(
									"UserName" =>$a_params["empcd"], 
									"ChannelCode" =>"WEB", // Shipment
									"PCName" =>"WEB", // Shipment
									"Value1" =>$ap_params["0"]["shipno"],// Shipment
									"Value2" =>$ap_params["0"]["pre_do"],
									"Value3" =>$ap_params["0"]["li_date"],
									"Value4" =>$ap_params["0"]["li_time"],
									"Value5" =>$ap_params["0"]["lo_date"],
									"Value6" =>$ap_params["0"]["lo_time"],
									"Value7" =>$ap_params["0"]["wo_date"],
									"Value8" =>$ap_params["0"]["wo_time"],
									"Value9" =>$ap_params["0"]["weightOutQty"],
									"Value10" =>$ap_params["0"]["sealno"],
							);
							$a_return_ap = $this->call_ap($method,$param);
							$update_status["wo_ap"] = $a_return_ap["status"]?"N":"Y";
							$this->set_flag($update_status,$a_params['type']);
							if( !$a_return_ap["status"]){
						  		return $a_return_ap["msg"];
							}
				} // End  AP process
	
			if($a_data_queue[0]['das_flg'] =="Y")
				{
					 	$sql = "execute p_das_cancelweightout  '$queueno',  '$queuedtlno', '".$a_params['type']."' ";
  						$data = $this->ci->db_api->query($sql);
						$this->ci->common->set_log("update call  das  ".$sql,"",$data);
  						$update_status["wo_das"] = ($data) ?"N":"Y";
						$this->set_flag($update_status,$a_params['type']);
							if( !$data){
						  		return $a_return_ap["Cannot cancel in DAS"];
							}
						
				}
	
	// All process successfully
	
			$update_status["wo_das"]="Y";
				$this->set_flag($update_status,$a_params['type']);
			$update_status["wo_ap"]="Y";
				$this->set_flag($update_status,$a_params['type']);
			$update_status["wo_sap"]="Y";
			$this->set_flag($update_status,$a_params['type']);
			
	  	return "Cancel Weight Out Successfully";
    
  }
 
  public function cancel_gateout2($a_params=array())
  { 

  	$this->ci->common->_filename= "cancel_gateout";
  	$queueno = $a_params["queueno"];
  	$queuedtlno = $a_params["queuedtlno"];
  	$update_status["queueno"] = $queueno;
	$update_status["queuedtlno"] = $queuedtlno;

  	$a_data_queue = $this->get_ws_flg($a_params);
//	return  	$a_data_queue ;
	if(empty($a_data_queue) || count($a_data_queue) <= 0){
  		return false;
  	}
		
		if($a_data_queue[0]['sap_flg'] =="Y")
				{
						$method = "SAPCancelGateOut";
						$param = array(
							"Value1" =>$a_params["queueno"], 
							"Value2" =>$a_params["queuedtlno"],  // no need to send queuedtlno
							"Value3" =>$a_params['stage'],
							"Value4" =>$a_params['type'],
							"ChannelCode" => "WEB",
							"UserName" => $a_params['empcd']
						);
					$a_return_sap = $this->call_sap($method,$param);
					$update_status["go_sap"] = $a_return_sap["status"]?"N":"Y";
					$this->set_flag($update_status,$a_params['type']);
					if( !$a_return_sap["status"]){
					   return $a_return_sap["msg"];				
				    } // End SAP process
				
				}
				
				if($a_data_queue[0]['ap_flg'] =="Y")
				{
							$method = "ISMARTAPSendTruckStatusCancelGateOut";
							$ap_params= $this->get_param_data($a_params);		
			
						   $param = array(
									"UserName" =>$a_params["empcd"], 
									"ChannelCode" =>"WEB", // Shipment
									"PCName" =>"WEB", // Shipment
									"Value1" =>$ap_params["0"]["shipno"],// Shipment
									"Value2" =>$ap_params["0"]["go_date"],
									"Value3" =>$ap_params["0"]["go_time"],
									
							);
							$a_return_ap = $this->call_ap($method,$param);
							$update_status["go_ap"] = $a_return_ap["status"]?"N":"Y";
							$this->set_flag($update_status,$a_params['type']);
							if( !$a_return_ap["status"]){
						  		return $a_return_ap["msg"];
							}
				} // End  AP process
	
			if($a_data_queue[0]['das_flg'] =="Y")
				{
					 	$sql = "execute p_das_cancelgateout  '$queueno',  '$queuedtlno', '".$a_params['type']."' ";
  						$data = $this->ci->db_api->query($sql);
						$this->ci->common->set_log("update call  das  ".$sql,"",$data);
  						$update_status["go_das"] = ($data) ?"N":"Y";
						$this->set_flag($update_status,$a_params['type']);
							if( !$data){
									$update_status["go_das"] = ($data) ?"Y":"Y";
						  		return $a_return_ap["Cannot cancel in DAS"];
							}
						
				}
	
	// All process successfully
			$update_status["go_das"]="Y";
			$this->set_flag($update_status,$a_params['type']);
			$update_status["go_ap"]="Y";
			$this->set_flag($update_status,$a_params['type']);
			$update_status["go_sap"]="Y";	
			$this->set_flag($update_status,$a_params['type']);
			
			
			
	  	return "Cancel Gate Out Successfully";
    
  }





  public function cancel_weightin($a_params=array())
  {
	
	//  return 555;
  	$this->ci->common->_filename= "cancel_weightin";
  	$queueno = $a_params["queueno"];
  	$queuedtlno = $a_params["queuedtlno"];
  	//$update_status["queueno"] = $queueno;
	
  	$update_status["queuedtlno"] = $queuedtlno;
	
	//maew add comment coz not insert yet
/*
  	$a_data_queue = $this->get_flag($queueno,$queuedtlno);
	  return 	$a_data_queue ;
  	if(empty($a_data_queue) || count($a_data_queue) <= 0){
  		return false;
  	}
*/
  	#############call sap####################
  	/*if($a_data_queue["0"]["wi_sap"]=="Y" ){
  		$a_return_sap["status"] = true;
  	}else{*/
  		$method = "SAPCancelWeightIn";
	  	$param = array(
	  			"Value1" =>$a_params["queueno"], // queue
	  			"Value2" =>$a_params["queuedtlno"],//queuedtl
	  			"Value3" =>"10",//stage
	  			"Value4" =>"O",// O=Outbound, I=inbound
	  			"ChannelCode" => "WEB", // 'WEB'
	  			"UserName" => 'AI'
	  	);
  		$a_return_sap = $this->call_sap($method,$param);
 /* 	} */

  	$update_status["wi_sap"] = $a_return_sap["status"]?"N":"Y";

  	if( !$a_return_sap["status"]){
  		return $a_return_sap["msg"];
  		exit();
  	}

	###############call ap ################
// maew add comment coz not insert yet
  //	if(($a_data_queue["0"]["wi_ap"]=="Y"  || $a_data_queue["0"]["wi_ap"]=="") &&  $a_return_sap["status"]){
	  if( $a_return_sap["status"]){
  		$sql = " select dtl.shipno
				,dtl.ldt_no as pre_do
				,LEFT(wi_datetime,8) as wi_date
				,right(wi_datetime,6) as wi_time
				,weightInQty
				 from tbt_lgst_queue q
				inner join tbt_lgst_queuedtl dtl on q.queueno=dtl.queueno
				where q.queueno='".$queueno."' and dtl.queuedtlno='".$queuedtlno."' " ;
	  	$a_data_dtl= $this->ci->db_api->getresult($sql );

	  	$method = "ISMARTAPSendTruckStatusCancelWeightIn";
	  	$param = array(
	  			"Value1" =>$a_data_dtl["0"]["shipno"], // Shipment
	  			"Value2" =>$a_data_dtl["0"]["pre_do"],//Pre Do
	  			"Value3" =>$a_data_dtl["0"]["wi_date"],//Weight In Date
	  			"Value4" =>$a_data_dtl["0"]["wi_time"],// Weight In Time
	  			"Value5" =>$a_data_dtl["0"]["weightInQty"],//Weight in QTY
	  			"ChannelCode" => "WEB", // 'WEB'
	  			"UserName" =>'AI'
	  	);
	  	$a_return_ap = $this->call_ap($method,$param);
  	}else{
  		$a_return_ap["status"] = true;
  	}

  	$update_status["wi_ap"] = $a_return_ap["status"]?"N":"Y";

  	if( !$a_return_ap["status"]){
  //		$this->set_flag($update_status);
  		return $a_return_ap["msg"];
  		exit();
  	}

  	############### call das ################
  	if($a_return_sap["status"] &&  $a_return_ap["status"]){
  		$sql = "execute p_das_cancelweightin  '$queueno',  '$queuedtlno' ";
  		$data = $this->ci->db_api->query($sql);
  		$this->ci->common->set_log("update call  das  ".$sql,"",$data);
  		$update_status["wi_das"] = ($data) ?"N":"Y";
  	}

  //	$this->set_flag($update_status);

  	return "Cancel Weight In Successfully";
  }


  public function cancel_weightout($a_params=array())
  {
  	$this->ci->common->_filename= "cancel_weightout";
  	$queueno = $a_params["queueno"];
  	$queuedtlno = $a_params["queuedtlno"];
  	$update_status["queueno"] = $queueno;
  	$update_status["queuedtlno"] = $queuedtlno;

// Maew Comment flag  coz not insert  yet
/*  	$a_data_queue = $this->get_flag($queueno,$queuedtlno);
  	if(empty($a_data_queue) || count($a_data_queue) <= 0){
  		return false;
  	}*/

  	#############call sap####################
 /* 	if($a_data_queue["0"]["wo_sap"]=="Y" ){
  		$a_return_sap["status"] = true;
  	}else{*/
  		$method = "SAPCancelWeightOut";
	  	$param = array(
	  			"Value1" =>$a_params["queueno"], // queue
	  			"Value2" =>$a_params["queuedtlno"],//queuedtl
	  			"Value3" =>"16",//stage
	  			"Value4" =>"O",// O=Outbound, I=inbound
	  			"ChannelCode" => "WEB", // 'WEB'
	  			"UserName" => 'AI'
	  	);
  		$a_return_sap = $this->call_sap($method,$param);
  /*	} */

  	$update_status["wo_sap"] = $a_return_sap["status"]?"N":"Y";

  	if( !$a_return_sap["status"]){
  		echo json_encode($a_return_sap["msg"]);
  		exit();
  	}

	###############call ap ################

  // 	if(($a_data_queue["0"]["wo_ap"]=="Y"  || $a_data_queue["0"]["wo_ap"]=="") &&  $a_return_sap["status"]){ // maew comment flag coz not intsert yet
	 	if(  $a_return_sap["status"]){
  		$sql = "select dtl.shipno
						,dtl.ldt_no as pre_do
						,LEFT(li_datetime,8) as li_date
						,right(li_datetime,6) as li_time
						,LEFT(lo_datetime,8) as lo_date
						,right(lo_datetime,6) as lo_time
						,LEFT(wo_datetime,8) as wo_date
						,right(wo_datetime,6) as wo_time
						,weightOutQty
						,sealno
						 from tbt_lgst_queue q
						inner join tbt_lgst_queuedtl dtl on q.queueno=dtl.queueno
						where q.queueno='".$queueno."' and dtl.queuedtlno='".$queuedtlno."' " ;
	  	$a_data_dtl= $this->ci->db_api->getresult($sql );

	  	$method = "ISMARTAPSendTruckStatusCancelWeightOut";
	  	$param = array(
	  			"Value1" =>$a_data_dtl["0"]["shipno"], // Shipment
	  			"Value2" =>$a_data_dtl["0"]["pre_do"],//Pre Do
	  			"Value3" =>$a_data_dtl["0"]["li_date"],//Load In Date
	  			"Value4" =>$a_data_dtl["0"]["li_time"],// Load In Time
	  			"Value5" =>$a_data_dtl["0"]["lo_date"],//Load Out Date
	  			"Value6" =>$a_data_dtl["0"]["lo_time"],//Load Out Time
	  			"Value7" =>$a_data_dtl["0"]["wo_date"],// Weight Out Date
	  			"Value8" =>$a_data_dtl["0"]["wo_time"],//Weight Out Time
	  			"Value9" =>$a_data_dtl["0"]["weightOutQty"],// Weight Out Qty
	  			"Value10" =>$a_data_dtl["0"]["sealno"],//seal
	  			"ChannelCode" => "WEB", // 'WEB'
	  			"UserName" => "AI"
	  	);
	  	$a_return_ap = $this->call_ap($method,$param);
  	}else{
  		$a_return_ap["status"] = true;
  	}

  	$update_status["wo_ap"] = $a_return_ap["status"]?"N":"Y";

  	if( !$a_return_ap["status"]){
  	//	$this->set_flag($update_status); // maew comment coz not insert yet
  		return $a_return_ap["msg"];
  	//	exit();
  	}

  	############### call das ################
  	if($a_return_sap["status"] &&  $a_return_ap["status"]){
  		$sql = "execute p_das_cancelweightout  '$queueno' ,'$queuedtlno' ";
  		$data = $this->ci->db_api->query($sql);
  		$this->ci->common->set_log("update call  das  ".$sql,"",$data);
  		$update_status["wo_das"] = ($data) ?"N":"Y";
  	}

 // 	$this->set_flag($update_status);  // maew comment coz not insert yet
  	return "Cancel WeightOut Successfully";
  }

  public function cancel_gateout($a_params=array())
  {
	
	$this->ci->common->_filename= "cancel_gateout";
  	$queueno = $a_params["queueno"];
  	$queuedtlno = $a_params["queuedtlno"];
  	//$update_status["queueno"] = $queueno;
	//	return 	$queueno;
  	$update_status["queuedtlno"] = $queuedtlno;

  	$a_data_queue = $this->get_flag($queueno,$queuedtlno);
//	return 	$a_data_queue;
  	if(empty($a_data_queue) || count($a_data_queue) <= 0){
  		return false;
  	}

  	#############call sap####################
	// maew add comment coz not insert yet
	
 /* 	if($a_data_queue["0"]["go_sap"]=="N" ){
  			$a_return_sap["status"] = true;
  	}else{ */ 
  	$method = "SAPCancelGateOut";
	//return   	$method;
	  	$param = array(
  			"Value1" =>$a_params["queueno"], // queue
	  		"Value2" =>0,//queuedtl
	  		"Value3" =>"18",//stage
  			"Value4" =>"O",// O=Outbound, I=inbound
  			"ChannelCode" => "WEB", // 'WEB'
  			"UserName" => "AI"
	  	);
  	$a_return_sap = $this->call_sap($method,$param);
 // 	} 	// maew add comment coz not insert yet
//return  	 $a_return_sap["status"];
  	$update_status["go_sap"] = $a_return_sap["status"]?"N":"Y";

  	if(! $a_return_sap["status"]){
  	return $a_return_sap["msg"];
  	//exit();
  	}

  	###############call ap ################
 
  //	if(($a_data_queue["0"]["go_ap"]=="Y"  || $a_data_queue["0"]["go_ap"]=="") &&  $a_return_sap["status"]){ // maew add comment coz not insert yet
	  if( $a_return_sap["status"]){
  		$sql = "select  dtl.shipno,LEFT(go_datetime,8) as go_date,right(go_datetime,6) as go_time
						from tbt_lgst_queue q
						inner join tbt_lgst_queuedtl dtl on q.queueno=dtl.queueno
						where q.queueno='".$queueno."' /* and dtl.queuedtlno='".$queuedtlno."'*/ " ;
	  	$a_data_dtl= $this->ci->db_api->getresult($sql );
  		  	$method = "ISMARTAPSendTruckStatusCancelGateOut";
  		  	$param = array(
	  			"Value1" =>$a_data_dtl["0"]["shipno"], // Shipment
  		  		"Value2" =>$a_data_dtl["0"]["go_date"],//Gate Out Date
  		  		"Value3" =>$a_data_dtl["0"]["go_time"],//Gate Out Time
  		  		"ChannelCode" => "WEB", // 'WEB'
	  			"UserName" => "AI"
  		  	);
	  	$a_return_ap = $this->call_ap($method,$param);
	//	return $a_return_ap;
  	}else{
  		$a_return_ap["status"] = true;
  	}
  		$update_status["go_ap"] = $a_return_ap["status"]?"N":"Y";

  		if( !$a_return_ap["status"]){
  			//	$this->set_flag($update_status);
  				return $a_return_ap["msg"];
  			//	exit();
			
  		}

  				############### call das ################
  	if($a_return_sap["status"] &&  $a_return_ap["status"]){
  				$sql = "execute p_das_cancelgateout  '$queueno' ,'$queuedtlno' ";
  				$data = $this->ci->db_api->query($sql);
  				$this->ci->common->set_log("update call  das  ".$sql,"",$data);
  				$update_status["wo_das"] = ($data) ?"N":"Y";
		//$this->set_flag($update_status);
  	    return "Cancel GateOut Successfully";
  		}
	//$this->set_flag($update_status);
	return "Cancel GateOut Successfully";
  }

 public function call_das($method="",$a_param=array())
 {
	 	$queueno = $a_param['queueno'];
	 	$queuedtlno = $a_param['queuedtlno'];
		$type  =$a_param['type'];
		if($method ="cancel_weightin")
		{
			$sql = "execute p_das_cancelweightin  '$queueno',  '$queuedtlno'  , '$type' ";  		
			$data = $this->ci->db_api->query($sql);
			$update_status["wi_das"] = ($data) ?"N":"Y";
  		}	
		if($method ="cancel_weightout")
		{
			$sql = "execute p_das_cancelweightout  '$queueno',  '$queuedtlno' ";  	
			$data = $this->ci->db_api->query($sql);
			$update_status["wo_das"] = ($data) ?"N":"Y";
 	 	}	
		if($method ="cancel_gateout")
		{
			$sql = "execute p_das_cancelgateout  '$queueno',  '$queuedtlno' ";
			$data = $this->ci->db_api->query($sql);
			$update_status["go_das"] = ($data) ?"N":"Y";
  		}	
		$this->ci->common->set_log("update call  das  ".$sql,"",$data);
  	
		return $update_status;
	
	}
	

  public function call_sap($method="",$a_param=array())
  {
  	if($method=="") return false;
  	$configsap =  $this->ci->config->item('sap');
  	$url = $configsap["url"]. $method;

  	/*tak*/

  	/* $a_response["IsError"] =false;
  	$a_response["Message"] ="";
  	$a_response["ResultList"][0] = array(
  			"InspectionLot" => "",
  			"MessageType" => "S",
  			"Message" => "test sap",
  	);*/

 $response = $this->ci->curl->simple_post($url,$a_param,array(),"json");
  $a_response = json_decode($response,true);
 //$a_response = json_decode('{"IsError":false,"Message":"Successful","Status":null,"Value1":"Y","Value10":null,"Value2":"","Value3":null,"Value4":null,"Value5":null,"Value6":null,"Value7":null,"Value8":null,"Value9":null}',true);

  $this->ci->common->set_log("update  call sap ".$url,$a_param,$a_response);

/*	if (strpos($a_response["Message"], 'Error') !== FALSE){
    	$a_return["status"] = false;
  		 $a_return["msg"] ="SAP : ". $a_response["Message"];
     }
	else if(isset($a_response["IsError"]) && !$a_response["IsError"]  )
  	{
  		if(empty($a_response["Message"])){
  			$a_return["status"] = false;
  			$a_return["msg"] = "No Response From SAP";
  		//	return $a_return;
  		}

  	//	$result = $a_response["Message"][0];
		
//		return $result ;
  		if(!empty($result)){
			if($result["IsError"]){
				$a_return["status"] = false;
  				$a_return["msg"] = "SAP : ".$result["Message"];
			}else
			{
				$a_return["status"] = true;
  				$a_return["msg"] = "SAP : ".$result["Message"];
				}
  		
		/*
			$msg_type = $result["Message"];
  			if($msg_type=="S"){
  				$a_return["status"] = true;
  				$a_return["msg"] = "";
  			}else{
  				$a_return["status"] = false;
  				$a_return["msg"] = "SAP : ".$result["Message"];
  			}
  		}
  	}
  	else
  	{
  		$a_return["status"] = false;
  		$a_return["msg"] = $result["Message"];
  	}
	
	*/
	
//	return $a_response["IsError"];

	 if(isset($a_response["IsError"]) )
  	{
		if($a_response["IsError"]){
			$a_return["status"] = false;
  			$a_return["msg"] =$a_response["Message"];
			return $a_return;
		}
	
  		$result = $a_response["ResultList"][0];
		if(!empty($result)){
					$msg_type = $result["MessageType"];
					if($msg_type=="S"){
						$a_return["status"] = true;
						$a_return["msg"] = "";
					}else{
		
							$a_return["status"] = false;
							$a_return["msg"] = "SAP : ".$result["Message"];
					}
		}else if(!$a_response["IsError"])
		{
					$a_return["status"] = true;
  					$a_return["msg"] = "";
			
			}
 
  	
  	}
  	else
  	{
  		$a_return["status"] = false;
  		$a_return["msg"] = $a_response["Message"];
  	}
	
	
	
  	return $a_return;
  }
  
   public function call_ap($method="",$a_param=array())
  {
  	if($method=="") return false;

  	if(empty($a_param)){
  		$this->ci->common->set_log("update call_ap ","","response:no data");
  		return false;
  	}
  	$configap =  $this->ci->config->item('ap');
  	$url = $configap["url"]. $method;

  	$response = $this->ci->curl->simple_post($url,$a_param,array(),"json");
  	$a_response = json_decode($response,true);
  	$this->ci->common->set_log("update call ap ".$url,$a_param,$a_response);
	if (strpos($a_response["Message"], 'Error') !== FALSE){
    	$a_return["status"] = false;
  		 $a_return["msg"] ="Auto Plant : ". $a_response["Message"];
     }
	
  else	if(isset($a_response["IsError"]) && !$a_response["IsError"])
  	{
  		$a_return["status"] = true;
  		$a_return["msg"] = "";
  	}else{
  		$a_return["status"] = false;
  		$a_return["msg"] = "Auto Plant : ".$a_response["Message"];
  	}
  	return $a_return;
  }
  



 public function cancel_shipment($a_params=array())
  { 	
	 $this->ci->common->_filename= "cancel_shipment";
	 $queueno = $a_params["queueno"];
  	 $queuedtlno = $a_params["queuedtlno"];
  	 $update_status["queueno"] = $queueno;
	 $update_status["queuedtlno"] = $queuedtlno;
     $empcd= $a_params['empcd'];

		$sql = "execute p_das_cancelcheckin_multi  '$queueno',  '".$a_params['type']."' ";
  		$data = $this->ci->db_api->query($sql);
			//	return $data;
  		$this->ci->common->set_log("update call  das  ".$sql,"",$data);
  		$update_status["ci_das"] = ($data) ?"N":"Y";
			$this->set_flag($update_status,$a_params['type']);
			if( !$data){
					return $a_return_ap["Cannot cancel in DAS"];
			}
		
	// All process successfully
	  	return "Cancel Shipment Successfully";
  
	  
	  }
  



  
}
