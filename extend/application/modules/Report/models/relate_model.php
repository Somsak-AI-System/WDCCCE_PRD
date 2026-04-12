<?php
class relate_model extends CI_Model
{
  var $ci;

 
  function __construct()
  {
    parent::__construct();
	$this->ci = & get_instance();
	$this->load->library("common");
	$this->load->library('crmentity');

    $this->_limit = "10";
  }


	function check_relate_list($a_param){
		
		if($a_param==null){
			$a_return["status"] = false;
  			$a_return["error"] =  "Fail";
  			$a_return["result"] = "";
			return $a_return;
		}
		// alert($a_param); exit;
		$crmid = $a_param['crmid'];		
		$module = $a_param['module'];
		$relate_module = $a_param['relate_module'];
		$relate_crmid = $a_param['relate_crmid'];
		$userid = $a_param['userid'];
		$doctype = "";

		if($module=="Job" && $relate_module=="Serial"){			
			$check_doctype = "SELECT doctype
			FROM aicrm_jobs
			WHERE jobid = '".$crmid."' ";
				
			$querycheck_doctype = $this->db->query($check_doctype);
			$datacheck_doctype = $querycheck_doctype->result_array();
			$doctype = @$datacheck_doctype[0]['doctype'];

			$check_serialno = "SELECT aicrm_serial.* 
			FROM aicrm_serial
			INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_serial.serialid
			WHERE aicrm_crmentity.deleted =0 and aicrm_serial.serial_no = '".$relate_crmid."' ";
				
			// if($this->db->query($check_serialname)){
			$querycheck_serialno = $this->db->query($check_serialno);
			$datacheck_serialno = $querycheck_serialno->result_array();

			$check_serialname = "SELECT aicrm_serial.* 
			FROM aicrm_serial
			INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_serial.serialid
			WHERE aicrm_crmentity.deleted =0 and aicrm_serial.serial_name = '".$relate_crmid."' ";
				
			// if($this->db->query($check_serialname)){
			$querycheck_serialname = $this->db->query($check_serialname);
			$datacheck_serialname = $querycheck_serialname->result_array();


			$check_serialid = "SELECT aicrm_serial.* 
			FROM aicrm_serial
			INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_serial.serialid
			WHERE aicrm_crmentity.deleted =0 and aicrm_serial.serialid = '".$relate_crmid."' ";
				
			// if($this->db->query($check_serialname)){
				$querycheck_serialid = $this->db->query($check_serialid);
				$datacheck_serialid = $querycheck_serialid->result_array();
			
			
		
				if($datacheck_serialno!="" || $datacheck_serialname!="" || $datacheck_serialid!=""){
								
						if(!empty($datacheck_serialno)){
							$productid = $datacheck_serialno[0]['product_id'];
							$serialid = $datacheck_serialno[0]['serialid'];
							$serial_name = $datacheck_serialno[0]['serial_name'];
						
						}elseif(!empty($datacheck_serialname)){
							$productid = $datacheck_serialname[0]['product_id'];
							$serialid = $datacheck_serialname[0]['serialid'];
							$serial_name = $datacheck_serialno[0]['serial_name'];
						}elseif(!empty($datacheck_serialid)){
							$productid = $datacheck_serialid[0]['product_id'];
							$serialid = $datacheck_serialid[0]['serialid'];
							$serial_name = $datacheck_serialno[0]['serial_name'];
						}
			
			
						
				if($serialid!=""){
					
					$checkserial_related = "SELECT aicrm_crmentityrel.* FROM `aicrm_crmentityrel`
					INNER JOIN aicrm_jobs on aicrm_jobs.jobid = aicrm_crmentityrel.crmid
					INNER JOIN aicrm_serial on aicrm_serial.serialid = aicrm_crmentityrel.relcrmid
					WHERE aicrm_crmentityrel.crmid='".$crmid."' and aicrm_crmentityrel.relcrmid ='".$serialid."' " ;
					
					$querycheck_related = $this->db->query($checkserial_related);
					$datacheck_related = $querycheck_related->result_array();
					
					if(!empty($datacheck_related)){
						
								$a_return["status"] = false;
						$a_return["error"] =  "This Serial number already exists";
								$a_return["result"] = "";
						
				
							// return $a_return;
					}else{
						
						if($relate_module==""){
							$relate_module= "Serial";
						}
						
						$insert_relted = "insert into aicrm_crmentityrel(crmid,module,relcrmid,relmodule) 
						values('".$crmid."','".$module."','".$serialid."','".$relate_module."')";
						
					//	alert(11);
							
						if($this->db->query($insert_relted)){
							
							//alert(22);
								
							//alert($productid);

							if($productid != ""){
															
								// $check_inspecttem = "SELECT aicrm_products.* ,aicrm_inspectiontemplate.*
								// FROM aicrm_products
								// INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_products.productid
								// LEFT JOIN aicrm_inspectiontemplate on aicrm_inspectiontemplate.inspectiontemplateid = aicrm_products.inspectiontemplateid
								// WHERE aicrm_crmentity.deleted =0 and aicrm_products.productid = '".$productid."' ";
								
								$check_inspecttem = "select aicrm_documenttemplate.inspectiontemplateid,aicrm_inspectiontemplate.inspectiontemplate_name ,aicrm_products.productname from aicrm_products 
								INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
								INNER join aicrm_documenttemplate ON aicrm_documenttemplate.product_id = aicrm_products.productid 
								LEFT JOIN aicrm_inspectiontemplate ON aicrm_inspectiontemplate.inspectiontemplateid = aicrm_documenttemplate.inspectiontemplateid  
								WHERE aicrm_products.productid IN ('".$productid."') and aicrm_crmentity.deleted = 0 AND aicrm_inspectiontemplate.doctype = '".$doctype."' ORDER by aicrm_inspectiontemplate.inspectiontemplateid DESC ";
								
								//alert($check_inspecttem);

								$querycheck_inspecttem = $this->db->query($check_inspecttem);
								$datacheck_inspecttem = $querycheck_inspecttem->result_array();
								//alert($datacheck_inspecttem);
								$inspectiontemplateid = $datacheck_inspecttem[0]['inspectiontemplateid']; 
								$inspectiontemplatename = $datacheck_inspecttem[0]['inspectiontemplate_name']; 
								$productname = $datacheck_inspecttem[0]['productname']; 
															
								$check_assignto = "SELECT aicrm_crmentity.smownerid,aicrm_jobs.jobtype
								FROM aicrm_crmentity
								inner join aicrm_jobs on aicrm_jobs.jobid =aicrm_crmentity.crmid 
								WHERE aicrm_crmentity.deleted =0 and aicrm_crmentity.crmid = '".$crmid."' ";
								
								$querycheck_assignto= $this->db->query($check_assignto);
								$datacheck_assignto = $querycheck_assignto->result_array();

								$smownerid = $datacheck_assignto[0]['smownerid'];
								$jobtype = $datacheck_assignto[0]['jobtype'];
								
								//alert($smownerid);
								//Get Crmid
								$sql_next_id = "SELECT max(id)+1 as next_id FROM aicrm_crmentity_seq";
								$query_next_id = $this->db->query($sql_next_id);
								$result_next_id = $query_next_id->result(0);
								$next_id = $result_next_id[0]['next_id'];
								
								//alert($next_id);
								

								$sql_insert_inspect1 = "INSERT INTO aicrm_crmentity (crmid,smcreatorid,smownerid,modifiedby,setype,description,createdtime,modifiedtime) VALUES ('".$next_id."', '".$userid."', '".$smownerid."','".$userid."', 'Inspection', '', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
								
								$sql_update = "UPDATE aicrm_crmentity_seq SET id='".$next_id."'";
								
								$inspection_no = $this->crmentity->autocd_Inspection();
								
							//	alert($inspection_no);
							$inspection_name = $inspectiontemplatename.' / '.$productname;
							//alert($inspection_name);
								$sql_insert_inspect2 = "INSERT INTO aicrm_inspection (jobid ,inspectionid, inspection_no, serialid,inspectiontemplateid,inspection_status,inspection_name,inspec_report_type) 
								VALUES ('".$crmid."' , '".$next_id."' , '".$inspection_no."'  ,'".$serialid."' ,'".$inspectiontemplateid."' ,'Open','".$inspection_name."','".$jobtype."')";
								
								//alert($sql_insert_inspect2);exit;
								
								$sql_insert_inspect3 = "INSERT INTO aicrm_inspectioncf (inspectionid) VALUES ('".$next_id."')";

								$this->db->query($sql_insert_inspect1);
								$this->db->query($sql_update);
								$this->db->query($sql_insert_inspect2);
								$this->db->query($sql_insert_inspect3);
								
								$checkproducts_tools = "SELECT aicrm_crmentityrel.* FROM aicrm_crmentityrel
								INNER JOIN aicrm_products on aicrm_products.productid = aicrm_crmentityrel.crmid
								WHERE aicrm_crmentityrel.crmid='".$productid."' and  aicrm_crmentityrel.relmodule='Tools' " ;
								
								$queryproducts_tools = $this->db->query($checkproducts_tools);
								$dataproducts_tools = $queryproducts_tools->result_array();
								
								if(!empty($dataproducts_tools)){

										foreach ($dataproducts_tools as $key => $value) {

											$insert_crmentityrel_inspect = "INSERT INTO aicrm_crmentityrel (crmid ,module, relcrmid,relmodule) 
										VALUES ('".$next_id."' , 'Inspection' , '".$value['relcrmid']."'  ,'Tools' )";

										$this->db->query($insert_crmentityrel_inspect);

										}

									}
								
								
							}
								$a_return["status"] = true;
								$a_return["error"] =  "Add serial Success";
								$a_return["result"] = "";
							// return $a_return;

							
						}else{
							
							$a_return["status"] = false;
							$a_return["error"] =  "No Data";
							$a_return["result"] = "";
							// return $a_return;
							
						}
						
					}
					
					
				}
				
				
			}else{
				
							$a_return["status"] = false;
					$a_return["error"] =  "No Data";
					$a_return["result"] = "";
				
						// return $a_return;
			}
		}else if($relate_module=="Documents" && $crmid != '' && $relate_crmid != ''){
			$insert_crmentityrel = "INSERT INTO aicrm_senotesrel(crmid,notesid) 
			VALUES ('".$crmid."' , '".$relate_crmid."')";
			// echo $insert_crmentityrel; exit;
			if($this->db->query($insert_crmentityrel)){
				$a_return["status"] = true;
				$a_return["error"] =  "Add Documents Success";
				$a_return["result"] = "";
			}else{
				$a_return["status"] = false;
				$a_return["error"] =  "No Data";
				$a_return["result"] = "";
			}
		}else{
			$a_return["status"] = false;
			$a_return["error"] =  "No Data";
			$a_return["result"] = "";
		}
		return $a_return;
		
	}
	

}