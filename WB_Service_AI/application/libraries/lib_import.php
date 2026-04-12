<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_import
{
	function __construct()
	  {
	    $this->ci = & get_instance();
	    $this->ci->load->library("common");
	    $this->ci->load->library('crmentity');
	    $this->ci->load->database();
	    $this->_import =  $this->ci->config->item('import');
	   	$this->_return = array(
			'status' => false,
			'error' => "",
			'result' => "",
		);
	}

	/*** Maew Add new data from  RMS ****/
	public function get_active_project()
	{
		$sql ="select projects_code  from aicrm_branchs a inner join aicrm_crmentity b on a.branchid=b.crmid
					where  b.deleted='0' and a.projectsstatus='Active' and  ifempty(a.projects_code,'')<>''  ";
		$query = $this->ci->db->query($sql);
		$a_data=$query->result_array();
		$branchs ='';

		foreach ($a_data as $key => $value)
		{
			$branchs[]=$value['projects_code'];
		}
		return $branchs;
	}

	public function SET_Accounts_import($a_data=array())
	{
		if(empty($a_data)) return null;

		$SQLTRUNCATE = "TRUNCATE tbt_import_accounts;";
		$this->ci->db->query($SQLTRUNCATE);
		//echo 666; exit;
		foreach ($a_data as $key => $value)
		{	
			$acc_source 		=trim($value['0']);  
			$sap_no				=trim($value['1']);  
			$account_status		=trim($value['2']);  
			$account_name		=trim($value['3']);  
			$taxid_no			=trim($value['4']);
			$mobile				=trim($value['5']);
			$email1				=trim($value['6']);  
			$address			=trim($value['7']);  
			$district			=trim($value['8']); 
			$province			=trim($value['9']); 
			$postal_code		=trim($value['10']); 
			$tel_number			=trim($value['11']); 
			$address_billing	=trim($value['12']); 
			$billingdistrict	=trim($value['13']); 
			$billingprovince	=trim($value['14']); 
			$billingpostalcode	=trim($value['15']); 
			$country    		=trim($value['16']); 
			$sales_office    	=trim($value['17']); 
			$salesperson		=trim($value['18']);
			
			$sale_code = explode(':',trim($value['18']));
			//$sales_organization_name=(trim($value['0']) == '2000') ? 'TTC' : 'UMI';

			$SQL="INSERT INTO tbt_import_accounts(
			acc_source				, sap_no			, account_status		, account_name			, taxid_no    
			, mobile				, email1			, address				, district				, province				
			, postal_code			, tel_number		, address_billing		, billingdistrict		, billingprovince
			, billingpostalcode		, country			, sales_office			, salesperson			, import_date
			, sale_code
			)	

			VALUES( '".$acc_source."'			,'".$sap_no."'				,'".$account_status."'			,'".$account_name."'			,'".$taxid_no."'    
			,'".$mobile."'						,'".$email1."'				,'".$address."'					,'".$district."'				,'".$province."'				
			,'".$postal_code."'					,'".$tel_number."'			,'".$address_billing."'			,'".$billingdistrict."'			,'".$billingprovince."'
			,'".$billingpostalcode."'			,'".$country."'				,'".$sales_office."'			,'".$salesperson."'				,'".date('Y-m-d H:i:s')."'
			,'".@$sale_code[0]."'
			)";	
			
			$this->ci->db->query($SQL);
		
		}//foreach

		$import_date = date('Y-m-d');
		$sql_update = "
			Update tbt_import_accounts
			INNER JOIN aicrm_account ON aicrm_account.sap_no = tbt_import_accounts.sap_no
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
			INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
			INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
			INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
			SET tbt_import_accounts.action = 'Update',
			tbt_import_accounts.accountno = aicrm_account.account_no,
			tbt_import_accounts.accountid = aicrm_account.accountid,
			tbt_import_accounts.crmid = aicrm_account.accountid
			WHERE aicrm_crmentity.deleted = 0 and tbt_import_accounts.status = 0 and left(tbt_import_accounts.import_date,10)= '".$import_date."';";
		$this->ci->db->query($sql_update);
		
		$sql_smownerid = "update tbt_import_accounts
			INNER JOIN aicrm_users on aicrm_users.salesperson_no = tbt_import_accounts.sale_code and aicrm_users.salesperson_no != ''
			set tbt_import_accounts.smownerid = aicrm_users.id
			where left(tbt_import_accounts.import_date,10)= '".$import_date."' and tbt_import_accounts.status = 0 and aicrm_users.deleted = 0 ;";
		$this->ci->db->query($sql_smownerid);

		$sql_salesoffice = "Update tbt_import_accounts
			LEFT JOIN aicrm_account on aicrm_account.accountid = tbt_import_accounts.accountid
			set tbt_import_accounts.a_sales_office =
			case 
				WHEN tbt_import_accounts.accountid = '0' then tbt_import_accounts.sales_office 
				WHEN aicrm_account.sales_office LIKE concat('%', tbt_import_accounts.sales_office, '%') THEN aicrm_account.sales_office
				WHEN aicrm_account.sales_office = '' then tbt_import_accounts.sales_office 
				WHEN aicrm_account.sales_office != '' then concat(aicrm_account.sales_office,' |##| ',tbt_import_accounts.sales_office ) 
			    else '' end
			where tbt_import_accounts.status = 0 and left(tbt_import_accounts.import_date,10)= '".$import_date."' ;";
		$this->ci->db->query($sql_salesoffice);
		//exit;
	}

	public function SET_Contacts_import($a_data=array())
	{
		if(empty($a_data)) return null;

		$SQLTRUNCATE = "TRUNCATE tbt_import_contact;";
		$this->ci->db->query($SQLTRUNCATE);
		foreach ($a_data as $key => $value)
		{	
			$contact_status 		=trim($value['0']);  
			$contact_code_sap				=trim($value['1']);  
			$firstname		=trim($value['2']);  
			$lastname		=trim($value['3']);  
			$position			=trim($value['4']);
			$department				=trim($value['5']);
			$mobile				=trim($value['6']);  
			$email			=trim($value['7']);  
			$sap_customer_code			=trim($value['8']);
			
			$sale_code = explode(':',trim($value['18']));
			//$sales_organization_name=(trim($value['0']) == '2000') ? 'TTC' : 'UMI';

			$SQL="INSERT INTO tbt_import_contact(
			contact_status				, contact_code_sap		, firstname			, lastname				, position    
			, department				, mobile				, email				, sap_customer_code		, import_date
			)	

			VALUES( '".$contact_status."'			,'".$contact_code_sap."'	,'".$firstname."'			,'".$lastname."'				,'".$position."'    
			,'".$department."'						,'".$mobile."'				,'".$email."'				,'".$sap_customer_code."'		,'".date('Y-m-d H:i:s')."'
			)";	
			
			$this->ci->db->query($SQL);
		
		}//foreach
		$import_date = date('Y-m-d');

		$sql_acc = "Update tbt_import_contact 
			INNER JOIN aicrm_account ON aicrm_account.sap_no = tbt_import_contact.sap_customer_code
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
			SET tbt_import_contact.accountid = aicrm_account.accountid
			WHERE aicrm_crmentity.deleted = 0 and tbt_import_contact.status = 0 and left(tbt_import_contact.import_date,10)= '".$import_date."';";
		$this->ci->db->query($sql_acc);
		
		$sql_update = "
			Update tbt_import_contact
			INNER JOIN aicrm_account ON aicrm_account.sap_no = tbt_import_contact.sap_customer_code
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
			INNER JOIN aicrm_contactdetails ON aicrm_contactdetails.contact_code_sap = tbt_import_contact.contact_code_sap
			INNER JOIN aicrm_crmentity crmcon ON crmcon.crmid = aicrm_contactdetails.contactid
			SET tbt_import_contact.action = 'Update',
			tbt_import_contact.contactno = aicrm_contactdetails.contact_no,
			tbt_import_contact.contactid = aicrm_contactdetails.contactid,
			tbt_import_contact.crmid = aicrm_contactdetails.contactid
			WHERE aicrm_crmentity.deleted = 0 and crmcon.deleted = 0 and tbt_import_contact.status = 0 and left(tbt_import_contact.import_date,10)= '".$import_date."';";
			
		$this->ci->db->query($sql_update);
		
	}

	public function SET_Products_import($a_data=array())
	{
		if(empty($a_data)) return null;
		
		foreach ($a_data as $key => $value)
		{	
			$material 						=trim($value['0']);  
			$base_unit_of_measure			=trim($value['1']);  
			$um_coversion					=trim($value['2']); 
			$description_en					=str_replace("'","''",trim($value['3']));  
			$description_th					=str_replace("'","''",trim($value['4']));  
			$product_status					=trim($value['5']);  
			$desc_status					=trim($value['6']);  
			$valuation_class				=trim($value['7']); 
			$valuation_class_description	=trim($value['8']); 
			$material_group					=trim($value['9']); 
			$mat_group						=trim($value['10']); 
			$plant							=trim($value['11']); 
			$sales_org						=trim($value['12']); 
			$channel						=trim($value['13']); 
			$mat_price_grp					=trim($value['14']); 
			$mat_price_grp_desc    			=trim($value['15']); 
			$mat_gp1    					=trim($value['16']); 
			$mat_gp1_desciption				=trim($value['17']); 
			$mat_gp2						=trim($value['18']); 
			$mat_gp2_desciption				=trim($value['19']); 
			$mat_gp3						=trim($value['20']); 
			$mat_gp3_desciption				=trim($value['21']); 
			$mat_gp4						=trim($value['22']); 
			$mat_gp4_desciption				=trim($value['23']); 
			$mat_gp5						=trim($value['24']); 
			$mat_gp5_desciption				=trim($value['25']); 
			$country						=trim($value['26']); 
			$country_of_origin				=trim($value['27']); 
			$list_price						=str_replace(' ', '', trim($value['28']));
			$M2CTN							=str_replace(' ', '', trim($value['29']));
			$M2SH							=str_replace(' ', '', trim($value['30']));
			$SHCTN							=str_replace(' ', '', trim($value['31']));

			$SQL="INSERT INTO tbt_import_products (
			 material				, base_unit_of_measure		, um_coversion			, description_en				, description_th    
			 , product_status		, desc_status				, valuation_class		, valuation_class_description	, material_group				
			 , mat_group			, plant						, sales_org				, channel						, mat_price_grp
			 , mat_price_grp_desc	, mat_gp1					, mat_gp1_desciption	, mat_gp2						, mat_gp2_desciption
			 , mat_gp3				, mat_gp3_desciption		, mat_gp4				, mat_gp4_desciption			, mat_gp5
			 , mat_gp5_desciption	, country 					, country_of_origin		, list_price					, import_date
			 , m2ctn				, m2sh 						, shctn			
			 )

			VALUES( '".$material."'				,'".$base_unit_of_measure."'		,'".$um_coversion."'			,'".$description_en."'			,'".$description_th."'    
			 ,'".$product_status."'				,'".$desc_status."'					,'".$valuation_class."'			,'".$valuation_class."'			,'".$material_group."'
			 ,'".$mat_group."'					,'".$plant."'						,'".$sales_org."'				,'".$channel."'					,'".$mat_price_grp."'
			 ,'".$mat_price_grp_desc."'			,'".$mat_gp1."'						,'".$mat_gp1_desciption."'		,'".$mat_gp2."'					,'".$mat_gp2_desciption."'
			 ,'".$mat_gp3."'					,'".$mat_gp3_desciption."'			,'".$mat_gp4."'					,'".$mat_gp4_desciption."'		,'".$mat_gp5."'
			 ,'".$mat_gp5_desciption."'			,'".$country."' 					,'".$country_of_origin."'		,'".$list_price."'				,'".date('Y-m-d H:i:s')."' 
			 ,'".$M2CTN."'						,'".$M2SH."' 						,'".$SHCTN."')";
			$this->ci->db->query($SQL);

		}//foreach
		$import_date = date('Y-m-d');
		$sql_update ="Update tbt_import_products
			INNER JOIN aicrm_products ON aicrm_products.material = tbt_import_products.material 
			-- AND aicrm_products.sales_org = tbt_import_products.sales_org
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
			INNER JOIN aicrm_productcf ON aicrm_products.productid = aicrm_productcf.productid
			SET tbt_import_products.action = 'Update',
			tbt_import_products.productno = aicrm_products.product_no,
			tbt_import_products.producid = aicrm_products.productid,
			tbt_import_products.crmid = aicrm_products.productid
			WHERE aicrm_crmentity.deleted = 0 and tbt_import_products.status = 0 and left(tbt_import_products.import_date,10)= '".$import_date."';";
		$this->ci->db->query($sql_update);

		//exit;
	}

	public function SET_SalesInvoice_import($a_data=array())
	{
		if(empty($a_data)) return null;
		foreach ($a_data as $key => $value)
		{	
			$company_code 						=trim($value['0']);  
			$sales_organization					=trim($value['1']);  
			$distribution_channel				=trim($value['2']);  
			$company_name						=trim($value['3']);  
			$sales_org							=trim($value['4']);  
			$sales_grp							=trim($value['5']);  
			$s_group_name						=trim($value['6']);  
			$billing_document					=trim($value['7']); 
			$item								=trim($value['8']); 
			$reference							=trim($value['9']); 
			$date1 								=str_replace('.', '-', trim($value['10']));
			$billing_date						=date('Y-m-d', strtotime($date1));
			//$billing_date						=trim($value['10']);
			$billing_type						=trim($value['11']); 
			$returns							=trim($value['12']); 
			$cancelled							=trim($value['13']); 
			$sales_district						=trim($value['14']); 
			$posting_status    					=trim($value['15']);
			$overall_status    					=trim($value['16']); 
			$payment_terms    					=trim($value['17']); 
			$customer_reference					=trim($value['18']); 
			$payer								=trim($value['19']); 
			$payer_name							=trim($value['20']); 
			$sold_to_party						=trim($value['21']); 
			$sold_to_name						=trim($value['22']); 
			$ship_to							=trim($value['23']); 
			$ship_to_name						=trim($value['24']); 
			$document_currency					=trim($value['25']); 
			$profitab_segmt_no					=trim($value['26']); 
			$billed_quantity					=str_replace(' ', '', trim($value['27']));
			$sales_unit							=trim($value['28']); 
			$numerator							=str_replace(' ', '', trim($value['29']));
			$denominator						=str_replace(' ', '', trim($value['30']));
			$billing_qty_in_sku					=str_replace(' ', '', trim($value['31']));
			$base_unit_of_measure				=trim($value['32']);
			$m2_qty								=str_replace(' ', '', trim($value['33']));
			$list_price							=str_replace(' ', '', trim($value['34']));
			$credit_price						=str_replace(' ', '', trim($value['35']));
			$net_price							=str_replace(' ', '', trim($value['36']));
			$net_value							=str_replace(' ', '', trim($value['37']));
			$tax_amount							=str_replace(' ', '', trim($value['38']));
			$total_amount						=str_replace(' ', '', trim($value['39']));
			$cost_unit							=str_replace(' ', '', trim($value['40']));
			$cost_sales_qty						=str_replace(' ', '', trim($value['41']));
			$exchange_rate						=str_replace(' ', '', trim($value['42']));
			$reference_document					=trim($value['43']);
			$sales_document						=trim($value['44']);
			$sales_document_item				=trim($value['45']);
			$order_type							=trim($value['46']);
			$material							=trim($value['47']);
			$material_description				=trim($value['48']);
			$batch								=trim($value['49']);
			$plant								=trim($value['50']);
			$plant_name							=trim($value['51']);
			$storage_location					=trim($value['52']);
			$customer_group						=trim($value['53']);
			$cg_description						=trim($value['54']);
			$customer_group_1					=trim($value['55']);
			$material_group						=trim($value['56']);
			$material_group_1_name				=trim($value['57']);
			$val_class							=trim($value['58']);
			$mat_status							=trim($value['59']);
			$material_group_1					=trim($value['60']);
			$mg1_description					=trim($value['61']);
			$material_group_2					=trim($value['62']);
			$mg2_description					=trim($value['63']);
			$material_group_3					=trim($value['64']);
			$mg3_description					=trim($value['65']);
			$material_group_4					=trim($value['66']);
			$mg4_description					=trim($value['67']);
			$material_group_5					=trim($value['68']);
			$mg5_description					=trim($value['69']);
			$bonus								=trim($value['70']);
			$mat_document						=trim($value['71']);
			$inv_account						=trim($value['72']);
			$cogs_account						=trim($value['73']);
			$ar_account							=trim($value['74']);
			$sales_account						=trim($value['75']);
			$order_reason						=trim($value['76']);
			$order_reason_description			=trim($value['77']);
			$original_invoice					=trim($value['78']);
			$date2								=str_replace('.', '-', trim($value['79']));
			$original_date						=date('Y-m-d', strtotime($date2));
			//$original_date						=trim($value['79']);
			$create_by							=trim($value['80']);
			$country_of_origin					=trim($value['81']);
			$origin_name						=trim($value['82']);

			$SQL="INSERT INTO tbt_import_salesinvoice (
				company_code,			sales_organization,			distribution_channel,			company_name,				sales_org,		
				sales_grp,				s_group_name,				billing_document,				item,						reference,
				billing_date,			billing_type,				returns,						cancelled,					sales_district,
				posting_status,			overall_status,				payment_terms,					customer_reference,			payer,
				payer_name,				sold_to_party,				sold_to_name,					ship_to,					ship_to_name,
				document_currency,		profitab_segmt_no,			billed_quantity,				sales_unit,					numerator,
				denominator,			billing_qty_in_sku,			base_unit_of_measure,			m2_qty,						list_price,
				credit_price,			net_price,					net_value,						tax_amount,					total_amount,
				cost_unit,				cost_sales_qty,				exchange_rate,					reference_document,			sales_document,
				sales_document_item, 	order_type,					material,						material_description, 		batch,
				plant,					plant_name,					storage_location,				customer_group,				cg_description,
				customer_group_1,		material_group,				material_group_1_name,			val_class,					mat_status,
				material_group_1,		mg1_description,			material_group_2,				mg2_description,			material_group_3,
				mg3_description,		material_group_4,			mg4_description,				material_group_5,			mg5_description,
				bonus,					mat_document,				inv_account,					cogs_account,				ar_account,
				sales_account,			order_reason,				order_reason_description,		original_invoice,			original_date,
				create_by,				country_of_origin,			origin_name,					import_date			
				 )
			
			VALUES (
				'".$company_code."',			'".$sales_organization."',			'".$distribution_channel."',			'".$company_name."',				'".$sales_org."',		
				'".$sales_grp."',				'".$s_group_name."',				'".$billing_document."',				'".$item."',						'".$reference."',
				'".$billing_date."',			'".$billing_type."',				'".$returns."',							'".$cancelled."',					'".$sales_district."',
				'".$posting_status."',			'".$overall_status."',				'".$payment_terms."',					'".$customer_reference."',			'".$payer."',
				'".$payer_name."',				'".$sold_to_party."',				'".$sold_to_name."',					'".$ship_to."',						'".$ship_to_name."',
				'".$document_currency."',		'".$profitab_segmt_no."',			'".$billed_quantity."',					'".$sales_unit."',					'".$numerator."',
				'".$denominator."',				'".$billing_qty_in_sku."',			'".$base_unit_of_measure."',			'".$m2_qty."',						'".$list_price."',
				'".$credit_price."',			'".$net_price."',					'".$net_value."',						'".$tax_amount."',					'".$total_amount."',
				'".$cost_unit."',				'".$cost_sales_qty."',				'".$exchange_rate."',					'".$reference_document."',			'".$sales_document."',
				'".$sales_document_item."', 	'".$order_type."',					'".$material."',						'".$material_description."', 		'".$batch."',
				'".$plant."',					'".$plant_name."',					'".$storage_location."',				'".$customer_group."',				'".$cg_description."',
				'".$customer_group_1."',		'".$material_group."',				'".$material_group_1_name."',			'".$val_class."',					'".$mat_status."',
				'".$material_group_1."',		'".$mg1_description."',				'".$material_group_2."',				'".$mg2_description."',				'".$material_group_3."',
				'".$mg3_description."',			'".$material_group_4."',			'".$mg4_description."',					'".$material_group_5."',			'".$mg5_description."',
				'".$bonus."',					'".$mat_document."',				'".$inv_account."',						'".$cogs_account."',				'".$ar_account."',
				'".$sales_account."',			'".$order_reason."',				'".$order_reason_description."',		'".$original_invoice."',			'".$original_date."',
				'".$create_by."',				'".$country_of_origin."',			'".$origin_name."',						'".date('Y-m-d H:i:s')."' )";
			$this->ci->db->query($SQL);
		}//foreach

		$import_date = date('Y-m-d');
		$sql_update_p ="Update tbt_import_salesinvoice
			INNER JOIN aicrm_products ON aicrm_products.material = tbt_import_salesinvoice.material
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
			INNER JOIN aicrm_productcf ON aicrm_products.productid = aicrm_productcf.productid
			SET	tbt_import_salesinvoice.producid = aicrm_products.productid
			WHERE aicrm_crmentity.deleted = 0 and tbt_import_salesinvoice.status = 0 and left(tbt_import_salesinvoice.import_date,10)= '".$import_date."';";
		$this->ci->db->query($sql_update_p);


		$sql_update_a ="Update tbt_import_salesinvoice
			INNER JOIN aicrm_account ON aicrm_account.sap_code = tbt_import_salesinvoice.sold_to_party
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
			INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
			INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
			INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
			SET	tbt_import_salesinvoice.accountid = aicrm_account.accountid
			WHERE aicrm_crmentity.deleted = 0 and tbt_import_salesinvoice.status = 0 and left(tbt_import_salesinvoice.import_date,10)= '".$import_date."';";
		$this->ci->db->query($sql_update_a);
	}

	public function get_Accounts_temp($import_date)
	{
		$import_date = ($import_date=="") ? date('Y-m-d') : $import_date;
		$sql = " select * from tbt_import_accounts where left(import_date,10)='".$import_date."' and status = 0 ";
		$query = $this->ci->db->query($sql);
		$data=$query->result_array();

		if(!$query){
			$a_return["status"] = false;
			$a_return["error"] =  $this->ci->db->_error_message();
			$a_return["result"] = "";
		}else{
			$a_return["status"] = true;
			$a_return["error"] =  "";
			$a_return["result"] = $query->result_array() ;
		}
		return $a_return["result"];
	}

	public function get_Contacts_temp($import_date)
	{
		$import_date = ($import_date=="") ? date('Y-m-d') : $import_date;
		$sql = " select * from tbt_import_contact where left(import_date,10)='".$import_date."' and status = 0 ";
		$query = $this->ci->db->query($sql);
		$data=$query->result_array();

		if(!$query){
			$a_return["status"] = false;
			$a_return["error"] =  $this->ci->db->_error_message();
			$a_return["result"] = "";
		}else{
			$a_return["status"] = true;
			$a_return["error"] =  "";
			$a_return["result"] = $query->result_array() ;
		}
		return $a_return["result"];
	}

	public function get_Products_temp($import_date)
	{
		$import_date = ($import_date=="") ? date('Y-m-d') : $import_date;
		$sql = " select * from tbt_import_products where left(import_date,10)='".$import_date."' and status = 0 ";
		$query = $this->ci->db->query($sql);
		$data=$query->result_array();

		if(!$query){
			$a_return["status"] = false;
			$a_return["error"] =  $this->ci->db->_error_message();
			$a_return["result"] = "";
		}else{
			$a_return["status"] = true;
			$a_return["error"] =  "";
			$a_return["result"] = $query->result_array() ;
		}
		return $a_return["result"];
	}
	
	public function get_SalesInvoice_temp($import_date)
	{
		$import_date = ($import_date=="") ? date('Y-m-d') : $import_date;
		$sql = " select * from tbt_import_salesinvoice where left(import_date,10)='".$import_date."' and status = 0 ";
		$query = $this->ci->db->query($sql);
		$data=$query->result_array();

		if(!$query){
			$a_return["status"] = false;
			$a_return["error"] =  $this->ci->db->_error_message();
			$a_return["result"] = "";
		}else{
			$a_return["status"] = true;
			$a_return["error"] =  "";
			$a_return["result"] = $query->result_array() ;
		}
		return $a_return["result"];
	}
	
}
