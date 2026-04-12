<?php
ini_set('max_execution_time', 36000);
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class batch extends MY_Controller
{

  public function __construct()
  {
    parent::__construct();
	//$this->load->library('memcached_library');
	//$this->load->library('crmentity');
    //$this->load->database();
	//$this->load->library("common");
	//$this->_format = "array";
  }

  public function import_product(){
	$this->load->database();
	global $path_pgm;
	$url = $path_pgm."/WB_Service_AI/products/insert_products";
  	$this->db2 = $this->load->database('dbsqlserver', TRUE);

  	//$this->load->database();
  	$sql="
		select 
		ITPK,
		ITCode COLLATE thai_CI_AS as ITCode,
		ITProdType COLLATE thai_CI_AS as ITProdType,
		ITSRP,
		ITUOM COLLATE thai_CI_AS as ITUOM,
		ITShortDesc1 COLLATE thai_CI_AS as ITShortDesc1,
		ITRevision,
		Expired COLLATE thai_CI_AS as Expired,
		ITShortDesc2 COLLATE thai_CI_AS as ITShortDesc2,
		ITLongDesc1 COLLATE thai_CI_AS as ITLongDesc1,
		ITLongDesc2 COLLATE thai_CI_AS as ITLongDesc2,
		ITDateOfCreation,
		LastUpdate,
		ITBarCode COLLATE thai_CI_AS as ITBarCode,
		ITECNApprovedBy COLLATE thai_CI_AS as ITECNApprovedBy,
		ICS_Field1 COLLATE thai_CI_AS as ICS_Field1,
		ICS_Field2 COLLATE thai_CI_AS as ICS_Field2,
		ICS_Field3 COLLATE thai_CI_AS as ICS_Field3,
		ICS_Field4 COLLATE thai_CI_AS as ICS_Field4,
		ICS_Field5 COLLATE thai_CI_AS as ICS_Field5,
		ICS_Field6 COLLATE thai_CI_AS as ICS_Field6,
		ICS_Field7 COLLATE thai_CI_AS as ICS_Field7,
		ICS_Field8 COLLATE thai_CI_AS as ICS_Field8,
		ICS_Field9 COLLATE thai_CI_AS as ICS_Field9,
		ICS_Field10 COLLATE thai_CI_AS as ICS_Field10
		from v_item
		where CONVERT(VARCHAR(10),LastUpdate, 120) = '".date('Y-m-d')."'
	";
		
	$sql="
		select  
		ITPK,
		ITCode COLLATE thai_CI_AS as ITCode,
		ITProdType COLLATE thai_CI_AS as ITProdType,
		ITSRP,
		ITUOM COLLATE thai_CI_AS as ITUOM,
		ITShortDesc1 COLLATE thai_CI_AS as ITShortDesc1,
		ITRevision,
		Expired COLLATE thai_CI_AS as Expired,
		ITShortDesc2 COLLATE thai_CI_AS as ITShortDesc2,
		ITLongDesc1 COLLATE thai_CI_AS as ITLongDesc1,
		ITLongDesc2 COLLATE thai_CI_AS as ITLongDesc2,
		ITDateOfCreation,
		LastUpdate,
		ITBarCode COLLATE thai_CI_AS as ITBarCode,
		ITECNApprovedBy COLLATE thai_CI_AS as ITECNApprovedBy,
		ICS_Field1 COLLATE thai_CI_AS as ICS_Field1,
		ICS_Field2 COLLATE thai_CI_AS as ICS_Field2,
		ICS_Field3 COLLATE thai_CI_AS as ICS_Field3,
		ICS_Field4 COLLATE thai_CI_AS as ICS_Field4,
		ICS_Field5 COLLATE thai_CI_AS as ICS_Field5,
		ICS_Field6 COLLATE thai_CI_AS as ICS_Field6,
		ICS_Field7 COLLATE thai_CI_AS as ICS_Field7,
		ICS_Field8 COLLATE thai_CI_AS as ICS_Field8,
		ICS_Field9 COLLATE thai_CI_AS as ICS_Field9,
		ICS_Field10 COLLATE thai_CI_AS as ICS_Field10
		from v_item
	";
	//echo $sql;exit;
  	$query = $this->db2->query($sql);
  	//alert($query);
  	if(!$query){
  		echo  $this->db2->_error_message();
  	}else{
  		$data  = $query->result_array() ;
  	}
	//echo iconv("tis-620","utf-8",trim($data[0]["ITShortDesc2"]));exit;
	//echo $data[0]["ITShortDesc2"];exit;
	/*$FileName = "sql.txt";
	$FileHandle = fopen($FileName, 'a+') or die("can't open file");
	fwrite($FileHandle,$a_data[0]["LECode"]."\r\n");
	fclose($FileHandle);*/
  	//alert($data);exit;
	//echo count($a_data);
	if(count($data)>0){
		for($i=0;$i<count($data);$i++){
			$data_save=array();
			$sql="
			select
			vtiger_products.productid
			FROM vtiger_products
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid
			INNER JOIN vtiger_productcf ON vtiger_products.productid = vtiger_productcf.productid
			LEFT JOIN vtiger_vendor ON vtiger_vendor.vendorid = vtiger_products.vendor_id
			LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_products.handler
			where 1
			and vtiger_crmentity.deleted=0
			and vtiger_productcf.cf_1795='".iconv("tis-620","utf-8",trim($data[$i]["ITCode"]))."'
			and vtiger_productcf.cf_1124='".iconv("tis-620","utf-8",trim($data[$i]["ITPK"]))."'
			";
			//echo $sql."<br>";exit;
			$query = $this->db->query($sql);
			$data_pro=$query->result_array();
			//alert($data_pro);
			$date=date('Y-m-d h:i:s');
			$crmid="";
			$action="";
			if(count($data_pro)>0){
				$crmid=$data_pro[0]["productid"];
				$action="edit";
			}else{
				$crmid="";
				$action="add";
			}
			$data1=array(
				'product_no'=>"",
				'createdtime'=>date('Y-m-d H:i:s',strtotime(iconv("tis-620","utf-8",trim($data[$i]["ITDateOfCreation"])))),
				'modifiedtime'=>date('Y-m-d H:i:s',strtotime(iconv("tis-620","utf-8",trim($data[$i]["LastUpdate"])))),
				'productname'=>iconv("tis-620","utf-8",trim($data[$i]["ITShortDesc1"]))." ".iconv("tis-620","utf-8",trim($data[$i]["ITShortDesc2"])),
				'unit_price'=>iconv("tis-620","utf-8",trim($data[$i]["ITSRP"])),
				'usageunit'=>iconv("tis-620","utf-8",trim($data[$i]["ITUOM"])),
				'cf_1124'=>iconv("tis-620","utf-8",trim($data[$i]["ITPK"])),
				'cf_1795'=>iconv("tis-620","utf-8",trim($data[$i]["ITCode"])),
				'cf_1826'=>iconv("tis-620","utf-8",trim($data[$i]["ITProdType"])),
				'cf_1808'=>iconv("tis-620","utf-8",trim($data[$i]["ITShortDesc1"])),
				'cf_1809'=>iconv("tis-620","utf-8",trim($data[$i]["ITRevision"])),
				'cf_1810'=>iconv("tis-620","utf-8",trim($data[$i]["Expired"])),
				'cf_1811'=>iconv("tis-620","utf-8",trim($data[$i]["ITShortDesc2"])),
				'cf_1812'=>iconv("tis-620","utf-8",trim($data[$i]["ITLongDesc1"])),
				'cf_1813'=>iconv("tis-620","utf-8",trim($data[$i]["ITLongDesc2"])),
				'cf_1814'=>iconv("tis-620","utf-8",trim($data[$i]["ITBarCode"])),
				'cf_1815'=>iconv("tis-620","utf-8",trim($data[$i]["ITECNApprovedBy"])),
				'cf_1816'=>iconv("tis-620","utf-8",trim($data[$i]["ICS_Field1"])),
				'cf_1817'=>iconv("tis-620","utf-8",trim($data[$i]["ICS_Field2"])),
				'cf_1818'=>iconv("tis-620","utf-8",trim($data[$i]["ICS_Field3"])),
				'cf_1819'=>iconv("tis-620","utf-8",trim($data[$i]["ICS_Field4"])),
				'cf_1820'=>iconv("tis-620","utf-8",trim($data[$i]["ICS_Field5"])),
				'cf_1821'=>iconv("tis-620","utf-8",trim($data[$i]["ICS_Field6"])),
				'cf_1822'=>iconv("tis-620","utf-8",trim($data[$i]["ICS_Field7"])),
				'cf_1823'=>iconv("tis-620","utf-8",trim($data[$i]["ICS_Field8"])),
				'cf_1824'=>iconv("tis-620","utf-8",trim($data[$i]["ICS_Field9"])),
				'cf_1825'=>iconv("tis-620","utf-8",trim($data[$i]["ICS_Field10"])),
				'cf_1832'=>iconv("tis-620","utf-8",trim($data[$i]["ITSRP"])),
			);
			$data_save[]=$data1;
			
			/*Single*/
			$fields = array(
					'AI-API-KEY'=>"1234",
					'module' => "Products", //module
					'action' => $action, //module
					'crmid' => $crmid, //module
					'data' => $data_save,
					'url' => $url,
			);
			/*echo "<pre>";
			print_r($fields);
			echo "</pre>";
			exit;*/
			//url-ify the data for the POST
			$fields_string = json_encode($fields);
			//echo $fields_string;exit;
			
			// jSON URL which should be requested
			$json_url = $url;
				 
			// jSON String for request
			$json_string = $fields_string;
			 
			// Initializing curl
			$ch = curl_init( $json_url );
			 
			// Configuring curl options
			$options = array(
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
				CURLOPT_POSTFIELDS => $json_string
			);
			 
			// Setting curl options
			curl_setopt_array( $ch, $options );
			// Getting results
			$result =  curl_exec($ch); // Getting jSON result string
			//echo "<pre>";
			$data_result=@json_decode($result,true);
			//print_r($data_result);
			//echo "</pre>";
			//exit;
			//update  In-Active All=No
			$sql="
			update vtiger_products
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid
			INNER JOIN vtiger_productcf ON vtiger_products.productid = vtiger_productcf.productid
			set discontinued=0
			where 1
			and vtiger_crmentity.deleted=0
			and vtiger_productcf.cf_1795='".iconv("tis-620","utf-8",trim($data[$i]["ITCode"]))."' 
			";
			//echo $sql;exit;
			if($this->db->query($sql)){}
			
			//หา product ที่มี ITRevision สูงสุด แล้ว update เป็น Active
			$sql="
			select
			vtiger_products.productid
			FROM vtiger_products
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid
			INNER JOIN vtiger_productcf ON vtiger_products.productid = vtiger_productcf.productid
			LEFT JOIN vtiger_vendor ON vtiger_vendor.vendorid = vtiger_products.vendor_id
			LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_products.handler
			where 1
			and vtiger_crmentity.deleted=0
			and vtiger_productcf.cf_1795='".iconv("tis-620","utf-8",trim($data[$i]["ITCode"]))."'
			order by vtiger_productcf.cf_1809 desc
			limit 1
			";
			//echo $sql."<br>";
			//echo $sql;exit;
			$query = $this->db->query($sql);
			$data_pro_max=$query->result_array();
			if(count($data_pro_max)>0){
				$sql="
				update vtiger_products
				INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid
				INNER JOIN vtiger_productcf ON vtiger_products.productid = vtiger_productcf.productid
				set discontinued=1
				where 1
				and vtiger_crmentity.deleted=0
				and vtiger_products.productid='".iconv("tis-620","utf-8",trim($data_pro_max[0]["productid"]))."' 
				";
				//echo $sql;exit;
				if($this->db->query($sql)){}
			}
		}//for
	}	
  }//end import product

  public function import_saleorder(){
	$this->load->database();
	global $path_pgm;
	$url = $path_pgm."/WB_Service_AI/salesorder/insert_salesorder";
  	$this->db2 = $this->load->database('dbsqlserver', TRUE);

  	//$this->load->database();
  	$sql="
	select 
	PINO COLLATE thai_CI_AS as PINO,
	PINOShop COLLATE thai_CI_AS as PINOShop,
	PIType COLLATE thai_CI_AS as PIType,
	PISettled COLLATE thai_CI_AS as PISettled,
	PIRefDoc COLLATE thai_CI_AS as PIRefDoc,
	PIShop COLLATE thai_CI_AS as PIShop,
	PIIssueDate,
	PIIssueTime COLLATE thai_CI_AS as PIIssueTime,
	PISalesPerson COLLATE thai_CI_AS as PISalesPerson,
	PIRemarks COLLATE thai_CI_AS as PIRemarks,
	PIAmount,
	PIQty,
	LastUpdate,
	LastUpdatedBy COLLATE thai_CI_AS as LastUpdatedBy,
	PIMember COLLATE thai_CI_AS as PIMember,
	PIGstRate,
	PIGstAmount,
	PIFGAmount,
	PIPurGoldAmount,
	PIRecGoldAmount,
	PIExchangeAmount,
	PDPK,
	PDPINo COLLATE thai_CI_AS as PDPINo,
	PDSeq,
	PDItemCode COLLATE thai_CI_AS as PDItemCode,
	PDQty,
	PDUnitPrice,
	PDDiscPercent,
	PDAmount,
	PDDiscAmt,
	PDACost,
	PDBCost	
	from v_invoicedtl 
	where CONVERT(VARCHAR(10),LastUpdate, 120) = '".date('Y-m-d')."'
	";
		
	$sql="
	select 
	PINO COLLATE thai_CI_AS as PINO,
	PINOShop COLLATE thai_CI_AS as PINOShop,
	PIType COLLATE thai_CI_AS as PIType,
	PISettled COLLATE thai_CI_AS as PISettled,
	PIRefDoc COLLATE thai_CI_AS as PIRefDoc,
	PIShop COLLATE thai_CI_AS as PIShop,
	PIIssueDate,
	PIIssueTime COLLATE thai_CI_AS as PIIssueTime,
	PISalesPerson COLLATE thai_CI_AS as PISalesPerson,
	PIRemarks COLLATE thai_CI_AS as PIRemarks,
	PIAmount,
	PIQty,
	LastUpdate,
	LastUpdatedBy COLLATE thai_CI_AS as LastUpdatedBy,
	PIMember COLLATE thai_CI_AS as PIMember,
	PIGstRate,
	PIGstAmount,
	PIFGAmount,
	PIPurGoldAmount,
	PIRecGoldAmount,
	PIExchangeAmount,
	PDPK,
	PDPINo COLLATE thai_CI_AS as PDPINo,
	PDSeq,
	PDItemCode COLLATE thai_CI_AS as PDItemCode,
	PDQty,
	PDUnitPrice,
	PDDiscPercent,
	PDAmount,
	PDDiscAmt,
	PDACost,
	PDBCost	
	from v_invoicedtl 
	";
	//echo $sql;exit;
  	$query = $this->db2->query($sql);
  	//alert($query);
  	if(!$query){
  		echo  $this->db2->_error_message();
  	}else{
  		$data  = $query->result_array() ;
  	}
	//echo iconv("tis-620","utf-8",trim($data[0]["ITShortDesc2"]));exit;
	//echo $data[0]["ITShortDesc2"];exit;
	/*$FileName = "sql.txt";
	$FileHandle = fopen($FileName, 'a+') or die("can't open file");
	fwrite($FileHandle,$a_data[0]["LECode"]."\r\n");
	fclose($FileHandle);*/
  	//alert($data);exit;
	//echo count($a_data);
	if(count($data)>0){
		for($i=0;$i<count($data);$i++){
			$sql="insert into tbt_import_so(`PINO`, `PINOShop`, `PIType`, `PISettled`, `PIRefDoc`, `PIShop`, `PIIssueDate`, `PIIssueTime`, `PISalesPerson`, `PIRemarks`, `PIAmount`, `LastUpdate`, `PIMember`, `PDItemCode`, `PDQty`, `PDUnitPrice`, `PDDiscPercent`, `PDAmount`, `PDDiscAmt`, `Import_Date`)values(
			'".iconv("tis-620","utf-8",trim($data[$i]['PINO']))."','".iconv("tis-620","utf-8",trim($data[$i]['PINOShop']))."','".iconv("tis-620","utf-8",trim($data[$i]['PIType']))."','".iconv("tis-620","utf-8",trim($data[$i]['PISettled']))."','".iconv("tis-620","utf-8",trim($data[$i]['PIRefDoc']))."'
			,'".iconv("tis-620","utf-8",trim($data[$i]['PIShop']))."','".date('Y-m-d',strtotime(iconv("tis-620","utf-8",trim($data[$i]['PIIssueDate']))))."','".iconv("tis-620","utf-8",trim($data[$i]['PIIssueTime']))."','".iconv("tis-620","utf-8",trim($data[$i]['PISalesPerson']))."','".iconv("tis-620","utf-8",trim($data[$i]['PIRemarks']))."'
			,'".iconv("tis-620","utf-8",trim($data[$i]['PIAmount']))."','".date('Y-m-d H:i:s',strtotime(iconv("tis-620","utf-8",trim($data[$i]["LastUpdate"]))))."','".iconv("tis-620","utf-8",trim($data[$i]['PIMember']))."','".iconv("tis-620","utf-8",trim($data[$i]['PDItemCode']))."','".iconv("tis-620","utf-8",trim($data[$i]['PDQty']))."'
			,'".iconv("tis-620","utf-8",trim($data[$i]['PDUnitPrice']))."','".iconv("tis-620","utf-8",trim($data[$i]['PDDiscPercent']))."','".iconv("tis-620","utf-8",trim($data[$i]['PDAmount']))."','".iconv("tis-620","utf-8",trim($data[$i]['PDDiscAmt']))."','".date('Y-m-d')."'
			)";
			//echo $sql."<br>";
			if($this->db->query($sql)){}
		}
		$sql="select * from tbt_import_so where 1 and Import_Date='".date('Y-m-d')."' and status=0 group by PINO";
		$query = $this->db->query($sql);
		$data=$query->result_array();
		
		for($i=0;$i<count($data);$i++){
			$data_save=array();
			$sql="
			select
			vtiger_salesorder.salesorderid
			FROM vtiger_salesorder
			LEFT JOIN vtiger_salesordercf ON vtiger_salesordercf.salesorderid = vtiger_salesorder.salesorderid
			LEFT JOIN vtiger_sobillads ON vtiger_sobillads.sobilladdressid = vtiger_salesorder.salesorderid
			LEFT JOIN vtiger_soshipads ON vtiger_soshipads.soshipaddressid = vtiger_salesorder.salesorderid
			LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_salesorder.salesorderid
			left join vtiger_account  on vtiger_account.accountid=vtiger_salesorder.accountid
			left join vtiger_accountscf on vtiger_accountscf.accountid=vtiger_account.accountid
			left join vtiger_accountbillads on vtiger_accountbillads.accountaddressid=vtiger_account.accountid
			left join vtiger_accountshipads on vtiger_accountshipads.accountaddressid=vtiger_account.accountid
			WHERE 1
			AND vtiger_crmentity.deleted =0
			AND vtiger_salesorder.salesorder_no='".iconv("tis-620","utf-8",trim($data[$i]["PINO"]))."'
			";
			//echo $sql."<br>";exit;
			$query = $this->db->query($sql);
			$data_pro=$query->result_array();
			//alert($data_pro);
			$date=date('Y-m-d h:i:s');
			$crmid="";
			$action="";
			if(count($data_pro)>0){
				$crmid=$data_pro[0]["salesorderid"];
				$action="edit";
			}else{
				$crmid="";
				$action="add";
			}
			$data_detail=array();
			
			$sql="select * from tbt_import_so where 1 and Import_Date='".date('Y-m-d')."' and status=0 and PINO='".iconv("tis-620","utf-8",trim($data[$i]["PINO"]))."'";
			$query = $this->db->query($sql);
			$data_inv=$query->result_array();

			$sql="update tbt_import_so set status=1 where 1 and Import_Date='".date('Y-m-d')."' and status=0 and PINO='".iconv("tis-620","utf-8",trim($data[$i]["PINO"]))."'";
			if($this->db->query($sql)){}
			
			$sql="
			select
			vtiger_account.accountid
			,vtiger_accountscf.cf_1580
			FROM vtiger_account
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_account.accountid
			INNER JOIN vtiger_accountbillads ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid
			INNER JOIN vtiger_accountshipads ON vtiger_account.accountid = vtiger_accountshipads.accountaddressid
			INNER JOIN vtiger_accountscf ON vtiger_account.accountid = vtiger_accountscf.accountid
			LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_account vtiger_account2 ON vtiger_account.parentid = vtiger_account2.accountid
			WHERE vtiger_crmentity.deleted = 0
			and vtiger_accountscf.cf_1580='".iconv("tis-620","utf-8",trim($data[$i]["PIMember"]))."'
			";
			//echo $sql."<br>";
			$query = $this->db->query($sql);
			$data_acc=$query->result_array();
			
			$accountid="";
			$cf_1580="";
			$chk_mamber="0";
			$cf_1319="Request Approval";	
			
			if(count($data_acc)>0){
				$accountid=$data_acc[0]["accountid"];
				$cf_1580=$data_acc[0]["cf_1580"];
				$chk_mamber="1";
				$cf_1319="Approved";
				//echo $sql."<br>";
			}else{
				$accountid="1016668";
				$cf_1580=iconv("tis-620","utf-8",trim($data[$i]["PIMember"]));
				$chk_mamber="0";
				$cf_1319="Request Approval";
			}		
			if(count($data_inv)>0){
				for($k=0;$k<count($data_inv);$k++){
					$productid="";
					$sql="
					select
					vtiger_products.productid
					FROM vtiger_products
					INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid
					INNER JOIN vtiger_productcf ON vtiger_products.productid = vtiger_productcf.productid
					LEFT JOIN vtiger_vendor ON vtiger_vendor.vendorid = vtiger_products.vendor_id
					LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_products.handler
					where 1
					and vtiger_crmentity.deleted=0
					and vtiger_productcf.cf_1795='".iconv("tis-620","utf-8",trim($data_inv[$k]["PDItemCode"]))."'
					and discontinued=1
					";
					$query = $this->db->query($sql);
					$data_pro=$query->result_array();
					//$data_pro =$generate->process($sql,"all");
					if(count($data_pro)>0){
						$productid=$data_pro[0]["productid"];
					}else{
						$productid="1016669";
					}
					$data_detail[]=array(
						'productid'=>$productid,
						'qty'=>$data_inv[$k]["PDQty"],
						'listprice'=>$data_inv[$k]["PDUnitPrice"],
						'total'=>$data_inv[$k]["PDAmount"],
						'discount_percent'=>$data_inv[$k]["PDDiscPercent"],
						'discount_amount'=>$data_inv[$k]["PDDiscAmt"],
						'comment'=>$data_inv[$k]["PDItemCode"],
					);
				}
			}
			$data1=array(
				'salesorder_no'=>iconv("tis-620","utf-8",trim($data[$i]["PINO"])),
				'createdtime'=>date('Y-m-d H:i:s',strtotime(iconv("tis-620","utf-8",trim($data[$i]["LastUpdate"])))),
				'modifiedtime'=>date('Y-m-d H:i:s',strtotime(iconv("tis-620","utf-8",trim($data[$i]["LastUpdate"])))),
				'subject'=>iconv("tis-620","utf-8",trim($data[$i]["PINO"])),

				'cf_1854'=>iconv("tis-620","utf-8",trim($data[$i]["PINOShop"])),
				'cf_1855'=>iconv("tis-620","utf-8",trim($data[$i]["PIType"])),
				'cf_1860'=>iconv("tis-620","utf-8",trim($data[$i]["PISettled"])),
				'cf_1856'=>iconv("tis-620","utf-8",trim($data[$i]["PIRefDoc"])),
				'cf_1857'=>iconv("tis-620","utf-8",trim($data[$i]["PIShop"])),
				'cf_1318'=>iconv("tis-620","utf-8",trim($data[$i]["PIIssueDate"])),
				'cf_1858'=>iconv("tis-620","utf-8",trim($data[$i]["PIIssueTime"])),
				'cf_1859'=>iconv("tis-620","utf-8",trim($data[$i]["PISalesPerson"])),
				'cf_1634'=>iconv("tis-620","utf-8",trim($data[$i]["PIRemarks"])),
				'total'=>iconv("tis-620","utf-8",trim($data[$i]["PIAmount"])),
				'subtotal'=>iconv("tis-620","utf-8",trim($data[$i]["PIAmount"])),
				'accountid'=>$accountid,
				'cf_1319'=>$cf_1319,
				'cf_1498'=>$cf_1580,
				'chk_mamber'=>$chk_mamber,
				'detail'=>$data_detail,
			);
			$data_save[]=$data1;
			
			/*Single*/
			$fields = array(
					'AI-API-KEY'=>"1234",
					'module' => "SalesOrder", //module
					'action' => $action, //module
					'crmid' => $crmid, //module
					'data' => $data_save,
					'url' => $url,
			);
			/*echo "<pre>";
			print_r($fields);
			echo "</pre>";
			exit;*/
			//url-ify the data for the POST
			$fields_string = json_encode($fields);
			//echo $fields_string;exit;
			
			// jSON URL which should be requested
			$json_url = $url;
				 
			// jSON String for request
			$json_string = $fields_string;
			 
			// Initializing curl
			$ch = curl_init( $json_url );
			 
			// Configuring curl options
			$options = array(
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
				CURLOPT_POSTFIELDS => $json_string
			);
			 
			// Setting curl options
			curl_setopt_array( $ch, $options );
			// Getting results
			$result =  curl_exec($ch); // Getting jSON result string
			//echo "<pre>";
			$data_result=@json_decode($result,true);
			
			$sql_update="update tbt_import_so set salesorderid='".iconv("tis-620","utf-8",trim($data_result["data"]["Crmid"]))."',Import_Status='".$data_result["Type"]."' where 1 and Import_Date='".date('Y-m-d')."' and status=1 and PINO='".iconv("tis-620","utf-8",trim($data_result["data"]["SalesOrderNO"]))."'";
			if($data_result["data"]["Crmid"]!=""){
				$sql="
				select
				vtiger_salesorder.salesorderid
				FROM vtiger_salesorder
				LEFT JOIN vtiger_salesordercf ON vtiger_salesordercf.salesorderid = vtiger_salesorder.salesorderid
				LEFT JOIN vtiger_sobillads ON vtiger_sobillads.sobilladdressid = vtiger_salesorder.salesorderid
				LEFT JOIN vtiger_soshipads ON vtiger_soshipads.soshipaddressid = vtiger_salesorder.salesorderid
				LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_salesorder.salesorderid
				left join vtiger_account  on vtiger_account.accountid=vtiger_salesorder.accountid
				left join vtiger_accountscf on vtiger_accountscf.accountid=vtiger_account.accountid
				left join vtiger_accountbillads on vtiger_accountbillads.accountaddressid=vtiger_account.accountid
				left join vtiger_accountshipads on vtiger_accountshipads.accountaddressid=vtiger_account.accountid
				WHERE 1
				AND vtiger_crmentity.deleted =0
				AND vtiger_salesorder.salesorderid='".iconv("tis-620","utf-8",trim($data_result["data"]["Crmid"]))."'
				";
				//echo $sql."<br>";exit;
				$query = $this->db->query($sql);
				$data_pro=$query->result_array();
				if(count($data_pro)>0){
					//if($data_pro[0]["accountid"]!="1016668"){
						//echo "444<br>";
						//$point->_saleorder = $data_result["data"]["Crmid"];
						//$a_data = $point->set_point_transaction();
					//}
				}
			}
			//echo $sql."<br>";
			if($this->db->query($sql_update)){}
		}//for
	}	
  }//end import saleorder
  
  public function import_account(){
	$this->load->database();
	global $path_pgm;
	$url = $path_pgm."/WB_Service_AI/accounts/insert_accounts";
  	$this->db2 = $this->load->database('dbsqlserver', TRUE);

  	//$this->load->database();
  	$sql="
	select 
	PMCode COLLATE thai_CI_AS as PMCode,
	PMRef COLLATE thai_CI_AS as PMRef,
	PMName COLLATE thai_CI_AS as PMName,
	PMTel COLLATE thai_CI_AS as PMTel,
	PMAddress1 COLLATE thai_CI_AS as PMAddress1,
	PMAddress2 COLLATE thai_CI_AS as PMAddress2,
	PMAddress3 COLLATE thai_CI_AS as PMAddress3,
	PMAddress4 COLLATE thai_CI_AS as PMAddress4,
	PMEmail COLLATE thai_CI_AS as PMEmail,
	PMGender COLLATE thai_CI_AS as PMGender,
	PMAge,
	PMIDNo COLLATE thai_CI_AS as PMIDNo,
	PMRemarks COLLATE thai_CI_AS as PMRemarks,
	Expired COLLATE thai_CI_AS as Expired,
	LastUpdate,
	LastUpdatedBy COLLATE thai_CI_AS as LastUpdatedBy,
	ActionLog COLLATE thai_CI_AS as ActionLog,
	PMJoinDate,
	PMJoinShop COLLATE thai_CI_AS as PMJoinShop,
	PMBirthMonth,
	PMBirthDate,
	PMType COLLATE thai_CI_AS as PMType,
	PMNameChi COLLATE thai_CI_AS as PMNameChi,
	PMTEL2 COLLATE thai_CI_AS as PMTEL2,
	PMLevel COLLATE thai_CI_AS as PMLevel,
	PMReligion COLLATE thai_CI_AS as PMReligion,
	PMPostalCode COLLATE thai_CI_AS as PMPostalCode,
	PMBirthYear,
	PMMaritalStatus COLLATE thai_CI_AS as PMMaritalStatus,
	PMExpiryDate,
	PMOccupation COLLATE thai_CI_AS as PMOccupation,
	PMRace COLLATE thai_CI_AS as PMRace,
	PMTel3 COLLATE thai_CI_AS as PMTel3,
	ServedBy COLLATE thai_CI_AS as ServedBy,
	Nationality COLLATE thai_CI_AS as Nationality
	from v_POSMembership 
	where CONVERT(VARCHAR(10),LastUpdate, 120) = '".date('Y-m-d')."'
	and PMName!=''
	";
		
	$sql="
	select  top 2
	PMCode COLLATE thai_CI_AS as PMCode,
	PMRef COLLATE thai_CI_AS as PMRef,
	PMName COLLATE thai_CI_AS as PMName,
	PMTel COLLATE thai_CI_AS as PMTel,
	PMAddress1 COLLATE thai_CI_AS as PMAddress1,
	PMAddress2 COLLATE thai_CI_AS as PMAddress2,
	PMAddress3 COLLATE thai_CI_AS as PMAddress3,
	PMAddress4 COLLATE thai_CI_AS as PMAddress4,
	PMEmail COLLATE thai_CI_AS as PMEmail,
	PMGender COLLATE thai_CI_AS as PMGender,
	PMAge,
	PMIDNo COLLATE thai_CI_AS as PMIDNo,
	PMRemarks COLLATE thai_CI_AS as PMRemarks,
	Expired COLLATE thai_CI_AS as Expired,
	LastUpdate,
	LastUpdatedBy COLLATE thai_CI_AS as LastUpdatedBy,
	ActionLog COLLATE thai_CI_AS as ActionLog,
	PMJoinDate,
	PMJoinShop COLLATE thai_CI_AS as PMJoinShop,
	PMBirthMonth,
	PMBirthDate,
	PMType COLLATE thai_CI_AS as PMType,
	PMNameChi COLLATE thai_CI_AS as PMNameChi,
	PMTEL2 COLLATE thai_CI_AS as PMTEL2,
	PMLevel COLLATE thai_CI_AS as PMLevel,
	PMReligion COLLATE thai_CI_AS as PMReligion,
	PMPostalCode COLLATE thai_CI_AS as PMPostalCode,
	PMBirthYear,
	PMMaritalStatus COLLATE thai_CI_AS as PMMaritalStatus,
	PMExpiryDate,
	PMOccupation COLLATE thai_CI_AS as PMOccupation,
	PMRace COLLATE thai_CI_AS as PMRace,
	PMTel3 COLLATE thai_CI_AS as PMTel3,
	ServedBy COLLATE thai_CI_AS as ServedBy,
	Nationality COLLATE thai_CI_AS as Nationality
	from v_POSMembership 
	where PMName!=''
	";
	//echo $sql;exit;
  	$query = $this->db2->query($sql);
  	//alert($query);
  	if(!$query){
  		echo  $this->db2->_error_message();
  	}else{
  		$data  = $query->result_array() ;
  	}
	//echo iconv("tis-620","utf-8",trim($data[0]["ITShortDesc2"]));exit;
	//echo $data[0]["ITShortDesc2"];exit;
	/*$FileName = "sql.txt";
	$FileHandle = fopen($FileName, 'a+') or die("can't open file");
	fwrite($FileHandle,$a_data[0]["LECode"]."\r\n");
	fclose($FileHandle);*/
  	alert($data);exit;
	//echo count($a_data);
	if(count($data)>0){
		for($i=0;$i<count($data);$i++){
			$data_save=array();
			$data_save=array();
			$sql="
			select
			vtiger_account.accountid
			FROM vtiger_account
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_account.accountid
			INNER JOIN vtiger_accountbillads ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid
			INNER JOIN vtiger_accountshipads ON vtiger_account.accountid = vtiger_accountshipads.accountaddressid
			INNER JOIN vtiger_accountscf ON vtiger_account.accountid = vtiger_accountscf.accountid
			LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_account vtiger_account2 ON vtiger_account.parentid = vtiger_account2.accountid
			WHERE vtiger_crmentity.deleted = 0
			and vtiger_accountscf.cf_1580='".iconv("tis-620","utf-8",trim($data[$i]["PMCode"]))."'
			";
			//echo $sql."<br>";exit;
			$query = $this->db->query($sql);
			$data_pro=$query->result_array();
			//alert($data_pro);exit;
			$date=date('Y-m-d h:i:s');
			$crmid="";
			$action="";
			if(count($data_pro)>0){
				$crmid=$data_pro[0]["accountid"];
				$action="edit";
			}else{
				$crmid="";
				$action="add";
			}
			$cf_1441="";
			if(iconv("tis-620","utf-8",trim($data[$i]['PMBirthYear']))!=""){
				$cf_1441=iconv("tis-620","utf-8",trim($data[$i]['PMBirthYear']));
			}else{
				$cf_1441="";
			}
			$cf_1441=$cf_1441+543;
			
			//echo iconv("tis-620","utf-8",trim($data[$i]['PMBirthYear']))."<br>";
			$cf_1440=str_replace(".0000","",iconv("tis-620","utf-8",trim($data[$i]['PMBirthMonth'])));
			$cf_1439=str_replace(".0000","",iconv("tis-620","utf-8",trim($data[$i]['PMBirthDate'])));
			//exit;
			$data1=array(
				'account_no'=>"",
				'createdtime'=>date('Y-m-d H:i:s',strtotime(iconv("tis-620","utf-8",trim($data[$i]["LastUpdate"])))),
				'modifiedtime'=>date('Y-m-d H:i:s',strtotime(iconv("tis-620","utf-8",trim($data[$i]["LastUpdate"])))),
				'accountname'=>iconv("tis-620","utf-8",trim($data[$i]["PMName"])),
				'description'=>iconv("tis-620","utf-8",trim($data[$i]["PMRemarks"])),
				'cf_1580'=>iconv("tis-620","utf-8",trim($data[$i]['PMCode'])),
				'cf_1827'=>iconv("tis-620","utf-8",trim($data[$i]['PMRef'])),
				'cf_1828'=>iconv("tis-620","utf-8",trim($data[$i]['PMTel'])),
				'cf_1847'=>iconv("tis-620","utf-8",trim($data[$i]['PMAddress1'])),
				'cf_1848'=>iconv("tis-620","utf-8",trim($data[$i]['PMAddress2'])),
				'cf_1849'=>iconv("tis-620","utf-8",trim($data[$i]['PMAddress3'])),
				'cf_1850'=>iconv("tis-620","utf-8",trim($data[$i]['PMAddress4'])),
				'cf_1829'=>iconv("tis-620","utf-8",trim($data[$i]['PMEmail'])),
				'cf_1830'=>iconv("tis-620","utf-8",trim($data[$i]['PMGender'])),
				'cf_1831'=>iconv("tis-620","utf-8",trim($data[$i]['PMAge'])),
				'cf_952'=>iconv("tis-620","utf-8",trim($data[$i]['PMIDNo'])),
				'cf_1793'=>iconv("tis-620","utf-8",trim($data[$i]['Expired']))=="F"?'0':'1',
				'cf_1833'=>iconv("tis-620","utf-8",trim($data[$i]['LastUpdatedBy'])),
				'cf_1846'=>iconv("tis-620","utf-8",trim($data[$i]['ActionLog'])),
				'cf_965'=>date('Y-m-d H:i:s',strtotime(iconv("tis-620","utf-8",trim($data[$i]['PMJoinDate'])))),
				'cf_1834'=>iconv("tis-620","utf-8",trim($data[$i]['PMJoinShop'])),
				'cf_1440'=>$cf_1440,
				'cf_1439'=>$cf_1439,
				'cf_1852'=>iconv("tis-620","utf-8",trim($data[$i]['PMType'])),
				'cf_1853'=>iconv("tis-620","utf-8",trim($data[$i]['PMNameChi'])),
				'cf_1836'=>iconv("tis-620","utf-8",trim($data[$i]['PMTEL2'])),
				'cf_1837'=>iconv("tis-620","utf-8",trim($data[$i]['PMLevel'])),
				'cf_1838'=>iconv("tis-620","utf-8",trim($data[$i]['PMReligion'])),
				'cf_1851'=>iconv("tis-620","utf-8",trim($data[$i]['PMPostalCode'])),
				'cf_1441'=>$cf_1441,
				'cf_1839'=>iconv("tis-620","utf-8",trim($data[$i]['PMMaritalStatus'])),
				'cf_1840'=>iconv("tis-620","utf-8",trim($data[$i]['PMExpiryDate'])),
				'cf_1841'=>iconv("tis-620","utf-8",trim($data[$i]['PMOccupation'])),
				'cf_1842'=>iconv("tis-620","utf-8",trim($data[$i]['PMRace'])),
				'cf_1843'=>iconv("tis-620","utf-8",trim($data[$i]['PMTel3'])),
				'cf_1844'=>iconv("tis-620","utf-8",trim($data[$i]['ServedBy'])),
				'cf_1845'=>iconv("tis-620","utf-8",trim($data[$i]['Nationality'])),
			);
			/*echo "<pre>";
			print_r($data1);
			echo "</pre>";
			exit;*/
			$data_save[]=$data1;
			
			/*Single*/
			$fields = array(
					'AI-API-KEY'=>"1234",
					'module' => "Accounts", //module
					'action' => $action, //module
					'crmid' => $crmid, //module
					'data' => $data_save,
					'url' => $url,
			);
			/*echo "<pre>";
			print_r($fields);
			echo "</pre>";
			exit;*/
			//url-ify the data for the POST
			$fields_string = json_encode($fields);
			//echo $fields_string;exit;
			
			// jSON URL which should be requested
			$json_url = $url;
				 
			// jSON String for request
			$json_string = $fields_string;
			 
			// Initializing curl
			$ch = curl_init( $json_url );
			 
			// Configuring curl options
			$options = array(
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
				CURLOPT_POSTFIELDS => $json_string
			);
			 
			// Setting curl options
			curl_setopt_array( $ch, $options );
			// Getting results
			$result =  curl_exec($ch); // Getting jSON result string
			//echo "<pre>";
			$data_result=@json_decode($result,true);
			//print_r($data_result);
			//echo "555".$data_result["data"]["memberID"];
			//echo "</pre>";
		}//for
	}	
  }//end import account
    
  public function test_connect_mysql()
  {
  	$this->load->database();
  	$sql = " select * from tbm_province ";
  	$query = $this->db->query($sql);
  	//alert($query);
  	if(!$query){
  		echo  $this->db->_error_message();
  	}else{
  		$a_data  = $query->result_array() ;
  	}
  	alert($a_data);
  }
}