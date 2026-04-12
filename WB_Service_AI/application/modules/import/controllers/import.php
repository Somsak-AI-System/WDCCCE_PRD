<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Import extends MY_Controller
{
	private $crmid;
	function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->_format = "array";
		$this->_limit = 20;
		$this->load->load->config('config_module');
		$this->load->library('lib_import');
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

	public function IMAccounts($date="")
	{
		$date = ($date=="") ? date('Y-m-d') : $date;

		$config =  $this->config->item('module');
		$this->tab_name = $config["Accounts"]["tab_name"];
		$this->tab_name_index = $config["Accounts"]["tab_name_index"];
		$this->common->_filename= "Import_SAP_Accounts";
		$date = ($date == "") ? date('Y-m-d') : $date;
		$userid = "1";

		$a_data = $this->lib_import->get_Accounts_temp(@$date);
		
		foreach ($a_data as $key => $value)
		{
			$data = array();
			$chk = "1";
			$id = $value['id'];
			$userid = 1;
			$smownerid = 19374;

			if($value['crmid'] !=  0 ){
				$crmid = $value["crmid"];
				$doc_no = $value["accountno"];
				$action = "edit";
			}else{
				$crmid="";
				$doc_no="";
				$action="add";
			}
			
			$data[] =array(
				'account_no'				=> @$doc_no,
				'acc_source'				=> $value['acc_source'],
				'sap_no'					=> $value['sap_no'],
				'account_status'			=> $value['account_status'],
				'accountname'				=> $value['account_name'],
				'idcardno'					=> $value['taxid_no'],
				'email1'					=> $value['email1'],
				'address'					=> $value['address'],
				'district'					=> $value['district'],
				'province'					=> $value['province'],
				'postalcode'				=> $value['postal_code'],
				'mobile'					=> $value['tel_number'],
				'address_billing'			=> $value['address_billing'],
				'billingdistrict'			=> $value['billingdistrict'],
				'billingprovince'			=> $value['billingprovince'],
				'billingpostalcode'			=> $value['billingpostalcode'],
				//'country'					=> $value['country'],
				'sales_office'				=> $value['a_sales_office'],
				'country'					=> $value['country'],
				'salesperson'				=> $value['salesperson'],
			);
			
			if($action != 'edit'){
				$data[0]['smownerid']= $value['smownerid'];
			}

			$module = "Accounts";
			
			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);

			if($chk=="0"){
				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
			}else{
				$a_return  =  array(
					'Type' => 'E',
					'Message' => 'Unable to complete transaction',
				);
			}
			
			$a_return["data"] =array(
				'Crmid' => $crmid,
				'DocNo' => $DocNo
			);
			
			$response_data = array_merge($this->_return,$a_return);
			$this->common->set_log("Import Accounts",$data,$response_data);
		
			if($response_data["data"]["DocNo"]!=""){
				$doc_no=$response_data["data"]["DocNo"];
			}

			$sql="update tbt_import_accounts set
			status=1
			,update_type		=	'".$response_data["Type"]."'
			,update_message		=	'".@$response_data["Message"]."'
			,accountid			=	'".@$response_data["data"]["Crmid"]."'
			,accountno			=	'".$doc_no."'
			where id='".$id."'
			";
			$this->db->query($sql);
		}
	}

	public function IMContacts($date="")
	{
		$date = ($date=="") ? date('Y-m-d') : $date;

		$config =  $this->config->item('module');
		$this->tab_name = $config["Contacts"]["tab_name"];
		$this->tab_name_index = $config["Contacts"]["tab_name_index"];
		$this->common->_filename= "Import_SAP_Contacts";
		$date = ($date == "") ? date('Y-m-d') : $date;
		$userid = "1";
		$a_data = $this->lib_import->get_Contacts_temp(@$date);
		
		foreach ($a_data as $key => $value)
		{
			$data = array();
			$chk = "1";
			$id = $value['id'];
			$userid = 1;
			$smownerid = 19374;

			if($value['crmid'] !=  0 ){
				$crmid = $value["crmid"];
				$doc_no = $value["contactno"];
				$action = "edit";
			}else{
				$crmid="";
				$doc_no="";
				$action="add";
			}
			
			$data[] =array(
				'contact_no'			=> @$doc_no,
				'contactstatus'			=> $value['contact_status'],
				'contact_code_sap'		=> $value['contact_code_sap'],
				'contactname'			=> $value['firstname'].' '.$value['lastname'],
				'firstname'				=> $value['firstname'],
				'lastname'				=> $value['lastname'],
				'position'				=> $value['position'],
				'department'			=> $value['department'],
				'mobile'				=> $value['mobile'],
				'email'					=> $value['email'],
				'accountid'				=> $value['accountid'],
				'sap_customer_code'		=> $value['sap_customer_code'],
			);
			
			if($action != 'edit'){
				$data[0]['smownerid']= $value['smownerid'];
			}

			$module = "Contacts";
			
			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);

			if($chk=="0"){
				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
			}else{
				$a_return  =  array(
					'Type' => 'E',
					'Message' => 'Unable to complete transaction',
				);
			}
			
			$a_return["data"] =array(
				'Crmid' => $crmid,
				'DocNo' => $DocNo
			);
			
			$response_data = array_merge($this->_return,$a_return);
			$this->common->set_log("Import Contacts",$data,$response_data);
		
			if($response_data["data"]["DocNo"]!=""){
				$doc_no=$response_data["data"]["DocNo"];
			}

			$sql="update tbt_import_contact set
			status=1
			,update_type		=	'".$response_data["Type"]."'
			,update_message		=	'".@$response_data["Message"]."'
			,contactid			=	'".@$response_data["data"]["Crmid"]."'
			,contactno			=	'".$doc_no."'
			where id='".$id."'
			";
			$this->db->query($sql);
		}
	}
	
	public function IMProducts($date=""){
		$date = ($date=="") ? date('Y-m-d') : $date;
		
		$config =  $this->config->item('module');
		$this->tab_name = $config["Products"]["tab_name"];
		$this->tab_name_index = $config["Products"]["tab_name_index"];
		$this->common->_filename= "Import_SAP_Products";
		$date = ($date == "") ? date('Y-m-d') : $date;
		$userid = "1";

		$a_data = $this->lib_import->get_Products_temp(@$date);
		foreach ($a_data as $key => $value)
		{
			$data = array();
			$chk = "1";
			$id = $value['id'];
			$userid = 1;
			$smownerid = 19374;

			if($value['crmid'] !=  0 ){
				$crmid = $value["crmid"];
				$doc_no = $value["productno"];
				$action = "edit";
			}else{
				$crmid="";
				$doc_no="";
				$action="add";	
			}

			$data[] =array(
				'product_no'					=>@$doc_no,
				'productname'					=> $value['description_th'],
				'productname_en'				=> $value['description_en'],
				'product_status'				=> $value['desc_status'],//สถานะของสินค้า
				'productcategory'				=> $value['mat_gp1_desciption'],
				'productsubcategory'			=> $value['mat_gp2_desciption'],
				'price_per_box'					=> $value['list_price'],
				'material'						=> $value['material'],
				'base_unit_of_measure'			=> $value['base_unit_of_measure'],
				'um_coversion_m2_pcs'			=> $value['um_coversion'],
				'description_en'				=> $value['description_en'],
				'description_th'				=> $value['description_th'],
				'status'						=> $value['product_status'],
				'desc_status'					=> $value['desc_status'],
				'valuation_class'				=> $value['valuation_class'],
				'valuation_class_description'	=> $value['valuation_class_description'],
				'material_group'				=> $value['material_group'],
				'mat_group'						=> $value['mat_group'],
				'plant'							=> $value['plant'],
				'sales_org'						=> $value['sales_org'],
				'channel'						=> $value['channel'],
				'mat_price_grp'					=> $value['mat_price_grp'],
				'mat_price_grp_desc'			=> $value['mat_price_grp_desc'],
				'mat_gp1'						=> $value['mat_gp1'],
				'mat_gp1_desciption'			=> $value['mat_gp1_desciption'],
				'mat_gp2'						=> $value['mat_gp2'],
				'mat_gp2_desciption'			=> $value['mat_gp2_desciption'],
				'mat_gp3'						=> $value['mat_gp3'],
				'mat_gp3_desciption'			=> $value['mat_gp3_desciption'],
				'mat_gp4'						=> $value['mat_gp4'],
				'mat_gp4_desciption'			=> $value['mat_gp4_desciption'],
				'mat_gp5'						=> $value['mat_gp5'],
				'mat_gp5_desciption'			=> $value['mat_gp5_desciption'],
				'country'						=> $value['country'],
				'country_of_origin'				=> $value['country_of_origin'],
				'list_price'					=> $value['list_price'],
				'unit'							=> $value['base_unit_of_measure'] == 'PCS' ? 'PCS' : "CTN",
				'squaremeters_per_carton'		=> $value['m2ctn'],
				//'um_coversion_m2_pcs'			=> $value['m2sh'],
				'quantity_sheet'				=> $value['shctn'],
			);

			if($action != 'edit'){
			 	$data[0]['smownerid'] = $smownerid;
			}

			$module = "Products";
			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
			if($chk=="0"){
				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
			}else{
				$a_return  =  array(
					'Type' => 'E',
					'Message' => 'Unable to complete transaction',
				);
			}
			
			$a_return["data"] =array(
				'Crmid' => $crmid,
				'DocNo' => $DocNo
			);
		
			$response_data = array_merge($this->_return,$a_return);
			$this->common->set_log("Import Products",$data,$response_data);
		
			if($response_data["data"]["DocNo"]!=""){
				$doc_no=$response_data["data"]["DocNo"];
			}

			$sql="update tbt_import_products set
			status=1
			,update_type		=	'".$response_data["Type"]."'
			,update_message		=	'".@$response_data["Message"]."'
			,producid			=	'".@$response_data["data"]["Crmid"]."'
			,productno			=	'".$doc_no."'
			where id='".$id."'
			";

			$this->db->query($sql);
		}	
	}

	public function IMSalesInvoice($date=""){

		$date = ($date=="") ? date('Y-m-d') : $date;
		$config =  $this->config->item('module');
		$this->tab_name = $config["Salesinvoice"]["tab_name"];
		$this->tab_name_index = $config["Salesinvoice"]["tab_name_index"];
		$this->common->_filename= "Import_SAP_SalesInvoice";
		$date = ($date == "") ? date('Y-m-d') : $date;
		$userid = "1";
		$a_data = $this->lib_import->get_SalesInvoice_temp(@$date);
		//alert($a_data); exit;
		foreach ($a_data as $key => $value)
		{
			$data = array();
			$chk = "1";
			$id = $value['id'];
			$userid = 1;
			$smownerid = 1;

			if($value['crmid'] !=  0 ){
				$crmid = $value["crmid"];
				$doc_no = $value["salesinvoiceno"];
				$action = "edit";
			}else{
				$crmid="";
				$doc_no="";
				$action="add";
			}

			$data[] =array(
				'salesinvoice_no'				=>@$doc_no,
				'salesinvoice_name'				=> $value['company_name'].' / '.$value['material'],
				'sap_code'						=> $value['company_code'],
				'sales_organization'			=> $value['sales_organization'],
				'distribution_channel'			=> $value['distribution_channel'],
				'company_name'					=> $value['company_name'],
				'sales_org'						=> $value['sales_org'],
				'slaes_grp'						=> $value['sales_grp'],
				's_group_name'					=> $value['s_group_name'],
				'billing_document'				=> $value['billing_document'],
				'item'							=> $value['item'],
				'reference'						=> $value['reference'],
				'billing_date'					=> $value['billing_date'],
				'billing_type'					=> $value['billing_type'],
				'returns'						=> $value['returns'],
				'cancelled'						=> $value['cancelled'],
				'sales_district'				=> $value['sales_district'],
				'posting_status'				=> $value['posting_status'],
				'overall_status'				=> $value['overall_status'],
				'payment_terms'					=> $value['payment_terms'],
				'customer_reference'			=> $value['customer_reference'],
				'payer'							=> $value['payer'],
				'sold_to_party'					=> $value['sold_to_party'],
				'sold_to_name'					=> $value['sold_to_name'],
				'ship_to'						=> $value['ship_to'],
				'ship_to_name'					=> $value['ship_to_name'],
				'document_currency'				=> $value['document_currency'],
				'profitab_segmt_no'				=> $value['profitab_segmt_no'],
				'billed_quantity'				=> $value['billed_quantity'],
				'sales_unit'					=> $value['sales_unit'],
				'numerator'						=> $value['numerator'],
				'denominator'					=> $value['denominator'],
				'billing_qty_in_sku'			=> $value['billing_qty_in_sku'],
				'base_unit_of_measure'			=> $value['base_unit_of_measure'],
				'm2_qty'						=> $value['m2_qty'],
				'list_price'					=> $value['list_price'],
				'credit_price'					=> $value['credit_price'],
				'net_price'						=> $value['net_price'],
				'net_value'						=> $value['net_value'],
				'tax_amount'					=> $value['tax_amount'],
				'total_amount'					=> $value['total_amount'],
				'cost_unit'						=> $value['cost_unit'],
				'cost_sales_qty'				=> $value['cost_sales_qty'],
				'exchange_rate'					=> $value['exchange_rate'],
				'reference_document'			=> $value['reference_document'],
				'sales_document'				=> $value['sales_document'],
				'sales_document_item'			=> $value['sales_document_item'],
				'order_type'					=> $value['order_type'],
				'material'						=> $value['material'],
				'material_description'			=> $value['material_description'],
				'batch'							=> $value['batch'],
				'plant'							=> $value['plant'],
				'plant_name'					=> $value['plant_name'],
				'storage_location'				=> $value['storage_location'],
				'customer_group'				=> $value['customer_group'],
				'cg_description'				=> $value['cg_description'],
				'customer_group_1'				=> $value['customer_group_1'],
				'material_group'				=> $value['material_group'],
				'material_group_description'	=> $value['material_group_1_name'],
				'val_class'						=> $value['val_class'],
				'mat_status'					=> $value['mat_status'],
				'material_group_1'				=> $value['material_group_1'],
				'mg1_description'				=> $value['mg1_description'],
				'material_group_2'				=> $value['material_group_2'],
				'mg2_description'				=> $value['mg2_description'],
				'material_group_3'				=> $value['material_group_3'],
				'mat_gp3_desciption'			=> $value['mg3_description'],
				'material_group_4'				=> $value['material_group_4'],
				'mg4_description'				=> $value['mg4_description'],
				'material_group_5'				=> $value['material_group_5'],
				'mg5_description'				=> $value['mg5_description'],
				'bonus'							=> $value['bonus'],
				'mat_document'					=> $value['mat_document'],
				'inv_account'					=> $value['inv_account'],
				'cogs_account'					=> $value['cogs_account'],
				'ar_account'					=> $value['ar_account'],
				'sales_account'					=> $value['sales_account'],
				'order_reason'					=> $value['order_reason'],
				'order_reason_description'		=> $value['order_reason_description'],
				'original_invoice'				=> $value['original_invoice'],
				'original_date'					=> $value['original_date'],
				'create_by'						=> $value['create_by'],
				'country_of_origin'				=> $value['country_of_origin'],
				'origin_name'					=> $value['origin_name'],
				'product_id'					=> $value['producid'],
				'accountid'						=> $value['accountid'],
			);
			
			if($action != 'edit'){
			 	$data[0]['smownerid'] = $smownerid;
			}

			$module = "Salesinvoice";
			list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
			if($chk=="0"){
				$a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
			}else{
				$a_return  =  array(
					'Type' => 'E',
					'Message' => 'Unable to complete transaction',
				);
			}
		
			$a_return["data"] =array(
				'Crmid' => $crmid,
				'DocNo' => $DocNo
			);
			
			$response_data = array_merge($this->_return,$a_return);
			
			$this->common->set_log("Import Salesinvoice",$data,$response_data);
			
			if($response_data["data"]["DocNo"]!=""){
				$doc_no=$response_data["data"]["DocNo"];
			}

			$sql="update tbt_import_salesinvoice set status = 1
			,update_type		=	'".$response_data["Type"]."'
			,update_message		=	'".@$response_data["Message"]."'
			,salesinvoiceid		=	'".@$response_data["data"]["Crmid"]."'
			,salesinvoiceno		=	'".$doc_no."'
			where id= '".$id."' ";

			$this->db->query($sql);

		}
	}

}