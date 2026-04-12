<?php
ini_set("memory_limit", "-1");

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Import extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('memcached_library');
		$this->load->library('crmentity');
		$this->load->database();
		$this->load->library("common");
		$this->load->library('excel');
	}

	public function account()
	{
		$tab_name = array('aicrm_crmentity', 'aicrm_account', 'aicrm_accountbillads', 'aicrm_accountshipads', 'aicrm_accountscf');
		$tab_name_index = array('aicrm_crmentity' => 'crmid', 'aicrm_account' => 'accountid', 'aicrm_accountbillads' => 'accountaddressid', 'aicrm_accountshipads' => 'accountaddressid', 'aicrm_accountscf' => 'accountid');

		$reader = PHPExcel_IOFactory::createReader('Excel2007');
		$excel = $reader->load('../documents/import/import_account.xlsx');
		$worksheet = [];
		$columns = [];
		$data = [];
		foreach ($excel->getWorksheetIterator() as $index => $worksheet) {
			$worksheets = $worksheet->toArray();
			if ($index == 0) $worksheet = $worksheets;
		}

		if (!is_array($worksheet)) {
			echo json_encode(['status' => 'error', 'data' => []]);
			exit();
		}

		$errorData = [];
		foreach ($worksheet as $i => $row) {
			if ($i > 0) {
				$this->db->select('id')->from('aicrm_users')->where('user_name', $row[17]);
				$sql = $this->db->get();
				$user = $sql->row_array();

				$rowData = [
					'account_no' => $row[1],
					'accountstatus' =>  $row[2],
					'accounttype' =>  $row[3],
					'accountindustry' =>  $row[4],
					'nametitle' =>  $row[5],
					'accountname' =>  $row[6] . ' ' . $row[7],
					'account_name_th' =>  $row[6] . ' ' . $row[7],
					'account_name_en' => $row[8] . ' ' . $row[9],
					'firstname' =>  $row[6],
					'lastname' => $row[7],
					'f_name_en' =>  $row[8],
					'l_name_en' => $row[9],
					'account_group' => $row[10],
					'account_grade' => $row[11],
					'idcardno' => $row[12],
					'mobile' => $row[13],
					'mobile2' => $row[14],
					'email1' => $row[15],
					'register_date' => $this->convertDate2DB($row[16]),
					'smownerid' => $user['id'] == '' ? 1 : $user['id'],
					'billing_address' => $row[18],
					'smcreatorid' => 1
				];

				// $data[] = $rowData;

				// list($chk, $resultID, $DocNo) = $this->crmentity->Insert_Update('Accounts', '', 'add', $tab_name, $tab_name_index, [$rowData], 1);

				// $data[] = [
				// 	'DocNo' => $DocNo,
				// 	'resultID' => $resultID,
				// 	'smownerid' => $user['id'] == '' ? 1 : $user['id']
				// ];
			}
		}

		alert($data);
	}

	public function contact()
	{
		$tab_name = array('aicrm_crmentity', 'aicrm_contactdetails', 'aicrm_contactaddress', 'aicrm_contactsubdetails', 'aicrm_contactscf', 'aicrm_customerdetails');
		$tab_name_index = array('aicrm_crmentity' => 'crmid', 'aicrm_contactdetails' => 'contactid', 'aicrm_contactaddress' => 'contactaddressid', 'aicrm_contactsubdetails' => 'contactsubscriptionid', 'aicrm_contactscf' => 'contactid', 'aicrm_customerdetails' => 'customerid');

		$reader = PHPExcel_IOFactory::createReader('Excel2007');
		$excel = $reader->load('../documents/import/import_contact.xlsx');
		$worksheet = [];
		$columns = [];
		$data = [];
		foreach ($excel->getWorksheetIterator() as $index => $worksheet) {
			$worksheets = $worksheet->toArray();
			if ($index == 0) $worksheet = $worksheets;
		}

		if (!is_array($worksheet)) {
			echo json_encode(['status' => 'error', 'data' => []]);
			exit();
		}

		$errorData = [];
		foreach ($worksheet as $i => $row) {
			if ($i > 0) {
				$this->db->select('accountid')->from('aicrm_account')->where('account_no', $row[12]);
				$sql = $this->db->get();
				$account = $sql->row_array();

				$this->db->select('id')->from('aicrm_users')->where('user_name', $row[13]);
				$sql = $this->db->get();
				$user = $sql->row_array();

				$rowData = [
					'contact_no' => $row[1],
					'contactstatus' =>  $row[2],
					'contactmain' =>  $row[3] == 'yes' ? '1' : '0',
					'contactname' =>  $row[4] . ' ' . $row[5],
					'firstname' =>  $row[4],
					'lastname' =>  $row[5],
					'position' => $row[6],
					'department' =>  $row[7],
					'gender' => $row[8],
					'mobile' => $row[9],
					'email' => $row[10],
					'register_date' => $this->convertDate2DB($row[11]),
					'accountid' => $account['accountid'],
					'smownerid' => $user['id'] == '' ? 1 : $user['id'],
					'smcreatorid' => 1
				];

				// list($chk, $resultID, $DocNo) = $this->crmentity->Insert_Update('Contacts', '', 'add', $tab_name, $tab_name_index, [$rowData], 1);

				// $data[] = [
				// 	'DocNo' => $DocNo,
				// 	'resultID' => $resultID,
				// 	'smownerid' => $user['id'] == '' ? 1 : $user['id']
				// ];
			}
		}

		alert($data);
	}

	public function user()
	{
		$tab_name = array('aicrm_users');
		$tab_name_index = array('aicrm_users' => 'id');

		$reader = PHPExcel_IOFactory::createReader('Excel2007');
		$excel = $reader->load('../documents/import/import_user.xlsx');
		$worksheet = [];
		$columns = [];
		$data = [];
		foreach ($excel->getWorksheetIterator() as $index => $worksheet) {
			$worksheets = $worksheet->toArray();
			if ($index == 0) $worksheet = $worksheets;
		}

		if (!is_array($worksheet)) {
			echo json_encode(['status' => 'error', 'data' => []]);
			exit();
		}

		$errorData = [];
		foreach ($worksheet as $i => $row) {
			if ($i > 0) {
				$salt = substr($row[1], 0, 2);
				$salt = '$1$' . $salt . '$';
				$password = crypt('aicrm', $salt);

				$rowData = [
					'user_name' => $row[1],
					'user_password' => $password,
					'email1' => $row[1],
					'first_name_th' => @explode(' ', $row[2])[0],
					'last_name_th' => @explode(' ', $row[2])[1],
					'first_name' => @explode(' ', $row[3])[0],
					'last_name' => @explode(' ', $row[3])[1],
					'status' => 'Active',
					'smownerid' => 1,
					'smcreatorid' => 1
				];

				// list($chk, $resultID, $DocNo) = $this->crmentity->Insert_Update('Users', '', 'add', $tab_name, $tab_name_index, [$rowData], 1);
				// if ($resultID != '') {
				// 	$this->db->update('aicrm_users', ['user_password' => $password], ['id' => $resultID]);
				// }

				// $roleName = '';
				// if ($row[6] != '' && $row[7] != '') {
				// 	$roleName = $row[6] . ':' . $row[7];
				// } else if ($row[4] != '' && $row[5] != '') {
				// 	$roleName = $row[4] . ':' . $row[5];
				// } else if ($row[8] != '' && $row[9] != '') {
				// 	$roleName = $row[9] . ':' . $row[9];
				// }
				// $rowData['userid'] = $resultID;
				// $rowData['roleName'] = $roleName;

				// if ($roleName != '') {
				// 	$sql = $this->db->get_where('aicrm_role', ['rolename' => $roleName]);
				// 	$role = $sql->row_array();
				// 	$rowData['roleid'] = $role['roleid'];
				// 	if (!empty($role)) {
				// 		$this->db->insert('aicrm_user2role', [
				// 			'userid' => $resultID,
				// 			'roleid' => $role['roleid']
				// 		]);
				// 	}
				// }

				// $data[] = [
				// 	'DocNo' => $DocNo,
				// 	'resultID' => $resultID
				// ];
			}
		}

		alert($data);
	}

	public function product()
	{
		$tab_name = array('aicrm_crmentity', 'aicrm_products', 'aicrm_productcf');
		$tab_name_index = array('aicrm_crmentity' => 'crmid', 'aicrm_products' => 'productid', 'aicrm_productcf' => 'productid');

		$reader = PHPExcel_IOFactory::createReader('Excel2007');
		$excel = $reader->load('../documents/import/update_product_sale_unit.xlsx');
		$worksheet = [];
		$columns = [];
		$data = [];
		foreach ($excel->getWorksheetIterator() as $index => $worksheet) {
			$worksheets = $worksheet->toArray();
			if ($index == 0) $worksheet = $worksheets;
		}

		if (!is_array($worksheet)) {
			echo json_encode(['status' => 'error', 'data' => []]);
			exit();
		}

		$errorData = [];
		foreach ($worksheet as $i => $row) {
			// if ($i > 0) {
				$materialCode = str_replace('.00', '', $row[0]);
				$sql = $this->db->get_where('aicrm_products', [
					'material_code' => $materialCode
				]);
				$product = $sql->row_array();

				$rowData = [
					// 'productid' => $product['productid'],
					// 'product_no' => $row[1],
					// 'productname' => $row[2],
					// 'productdescription' => $row[3],
					// 'productstatus' => $row[4] == 'yes' ? '1':'0',
					'material_code' => $materialCode,
					'sales_unit' => $row[1],
					// 'product_group' => $row[6],
					// 'product_sub_group' => $row[7],
					// 'product_brand' => $row[8],
					// 'product_catalog_code' => $row[9],
					// 'product_factory_code' => $row[10],
					// 'product_design_no' => $row[11],
					// 'product_design_name' => $row[12],
					// 'product_width_mm' => $row[13],
					// 'product_size_ft' => $row[14],
					// 'product_size_mm' => $row[15],
					// 'product_length_ft' => $row[16],
					// 'product_length_mm' => $row[17],
					// 'product_film' => $row[18],
					// 'product_width_ft' => $row[19],
					// 'product_colorrange' => $row[20],
					// 'product_thinkness' => $row[21],
					// 'product_grade' => $row[22],
					// 'unit' => $row[23],
					// 'product_backprint' => $row[24],
					// 'producttype' => $row[25],
					// 'producttatus' => $row[26],
					// 'package_size_sheet_per_box' => $row[33],
					// 'package_size_sqm_per_box' => $row[34],
					// 'package_size_box_per_palate' => $row[34],
					// 'product_weight_per_box' => $row[36],
					// 'package_size_box_per_container' => $row[37],
					'smownerid' => 1,
					'smcreatorid' => 1
				];
				
				$crmID = @$product['productid'];
				$action = @$product['productid'] == '' ? 'add':'edit';
				list($chk, $resultID, $DocNo) = $this->crmentity->Insert_Update('Products', $crmID, $action, $tab_name, $tab_name_index, [$rowData], 1);

				$rowData['DocNo'] = $DocNo;
				$rowData['resultID'] = $resultID;
				
				$data[] = $rowData;
			// }
		}

		alert($data);
	}

	public function productSAP()
	{
		global $site_URL, $serviceAPI;
		$tab_name = array('aicrm_crmentity', 'aicrm_products', 'aicrm_productcf');
		$tab_name_index = array('aicrm_crmentity' => 'crmid', 'aicrm_products' => 'productid', 'aicrm_productcf' => 'productid');
		
		$filterDate = date('Ymd', strtotime('-3 day', strtotime(date('Y-m-d'))));
		$url = $serviceAPI.'/SAPDI/GetItem?datestring='.$filterDate;
		$logID = $this->logUniqID();
		$this->logInfo(['module'=>'Products', 'title'=>'SAP_UPDATE_PRODUCT', 'logID'=>$logID, 'type'=>'Request', 'time'=>date('Y-m-d H:i:s'), 'url'=>$url, 'data'=>json_encode([], JSON_UNESCAPED_UNICODE)]);
		$rs = $this->getApi($url, [], 'get');
		$this->logInfo(['module'=>'Products', 'title'=>'SAP_UPDATE_PRODUCT', 'logID'=>$logID, 'type'=>'Response', 'time'=>date('Y-m-d H:i:s'), 'url'=>$url, 'data'=>json_encode($rs, JSON_UNESCAPED_UNICODE)]);

		$result = [];
		foreach($rs as $i => $item){
			$query = "SELECT aicrm_products.productid FROM aicrm_products 
				INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
				WHERE aicrm_crmentity.deleted = 0 && aicrm_products.material_code=".$item['itemcode'];
			$sql = $this->db->query($query);
			$findProduct = $sql->row_array();

			$crmID = @$findProduct['productid'];
			
			// Check Active field and set product status
			$productStatus = (isset($item['Active']) && strtolower(trim($item['Active'])) == 'active') ? 'Active' : 'Inactive';
			
			$rowData = [
				'productname' => $item['itemname'],
				'material_code' => $item['itemcode'],
				'product_group' => $item['itemgroupname'], 
				'product_brand' => $item['categoriesname'], 
				'product_catalog_code' => $item['subcategoriesname'], 
				'product_sub_group' => $item['subgroupname1'], 
				'package_size_sqm_per_box' => $item['volumn'], 
				'package_size_sheet_per_box' => $item['pieceinbox'], 
				'package_size_box_per_palate' => $item['boxperpallet'], 
				'palate_weight' => $item['weightperpallet'], 
				'remark' => $item['remark'], 
				'unit' => $item['salesuom'], 
				'sales_unit' => $item['salesuom'], 
				'product_weight_per_box' => $item['weight'],
				'producttatus' => $productStatus,
				'smownerid' => 1,
				'smcreatorid' => 1
			];

			$action = $crmID == '' ? 'add':'edit';
			$result[] = $this->crmentity->Insert_Update('Products', $crmID, $action, $tab_name, $tab_name_index, [$rowData], 1);
		}
		alert($result);
	}

	function logInfo($data){

		global $root_directory;
		$module = $data['module'];
		$dateTime = date('d_m_Y');
		$title = str_replace(' ', '', $data['title']);
		$fileName = $module.'_'.$title.'_'.$dateTime.'.txt';
	
		$logData = $data;
	
		$FileName = $root_directory."/logs/".$fileName;
	
		$FileHandle = fopen($FileName, 'a+') or die("can't open file");
		fwrite($FileHandle, date('Y-m-d H:i:s')." == ".print_r($logData, true)."================================================================"."\r\n");
	
		fclose($FileHandle);
	
	}
	
	function logUniqID()
	{
		return uniqid().time().uniqid();
	}

	private function getApi( $url, $param=[] ){
		$fields_string = json_encode($param);
		$json_url = $url;
	
		$json_string = $fields_string;
		$ch = curl_init( $json_url );
		$options = array(
			CURLOPT_FRESH_CONNECT => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false
		);
	
		curl_setopt_array( $ch, $options );
		$result =  curl_exec($ch);
		$return = json_decode($result, true );
		return $return;
	}

	private function convertDate2DB($date = '')
	{
		if ($date != '') {
			$date = explode('/', $date);
			$date = date('Y-m-d', strtotime($date[2] . '-' . $date[0] . '-' . $date[1]));
		}

		return $date;
	}

	public function update_project_and_quotation_from_erp()
	{
		/****** projects ******/
		$query_projects = "
		SELECT
			aicrm_quotes.quoteid,
			aicrm_quotes.quote_no,
			aicrm_projects.projectsid,
			aicrm_projects.projects_no,
			aicrm_projects.projects_name,
			aicrm_quotes.reference_id,
			aicrm_quotes.projects_reference_id
		FROM
			`aicrm_quotes`
			LEFT JOIN aicrm_projects ON aicrm_quotes.projectsid = aicrm_projects.projectsid 
		WHERE
			aicrm_quotes.flag_projects = 1
			AND aicrm_quotes.projects_reference_id != ''
		";

		$sql_projects = $this->db->query($query_projects);
		$projects = $sql_projects->result_array();
		// alert($projects); exit;

		foreach ($projects as $project) {
			
			$url_diprj = "http://202.44.218.12:1121/SAPDI/GetResponse";
			$data_project = [
				"ID" => $project['projects_reference_id']
			];

			$queryString = http_build_query($data_project);
			$url_diprj = $url_diprj . '?' . $queryString;

			
			$data_parram = "Befor Url GetResponse :: ".$url_diprj." ,Quotes ID :: ".$project['quoteid']." ,Projects ID :: ".$project['projectsid'];
			$file_name="log-Projects-GetResponse-".date('Y-m-d').".php";
			$FileName = "C:/AppServ/www/WDCCCE/logs/API/".$file_name;
			$FileHandle = fopen($FileName, 'a+') or die("can't open file");
			fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
			fclose($FileHandle);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url_diprj);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			$responseArray = json_decode($response, true);

			// alert($responseArray); exit;

			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if ($response === FALSE) {
				die('cURL Error: ' . curl_error($ch));
			}else{
				// echo $responseArray[0]['RESPONSE']; exit;
				if(($responseArray[0]['REFERENCE'] == NULL || $responseArray[0]['REFERENCE'] == "") && ($responseArray[0]['RESPONSE'] == NULL || $responseArray[0]['RESPONSE'] == "")){
					
					$query_quotes = "UPDATE aicrm_quotes SET flag_projects='1', erp_response_status='".$responseArray[0]['RESPONSE']."'  WHERE quoteid='".$project['quoteid']."'";
					$this->db->query($query_quotes);

				}elseif(($responseArray[0]['REFERENCE'] == NULL || $responseArray[0]['REFERENCE'] == "") && $responseArray[0]['RESPONSE'] != ''){

					$query_quotes = "UPDATE aicrm_quotes SET flag_projects='0', erp_response_status='".$responseArray[0]['RESPONSE']."'  WHERE quoteid='".$project['quoteid']."'";
					$this->db->query($query_quotes);

				}elseif($responseArray[0]['REFERENCE'] != '' && $responseArray[0]['RESPONSE'] != ''){

					$query_quotes = "UPDATE aicrm_quotes SET flag_projects='0', erp_response_status='".$responseArray[0]['RESPONSE']."'  WHERE quoteid='".$project['quoteid']."'";
					$this->db->query($query_quotes);

					$query_projects = "UPDATE aicrm_projects SET flag_project='1' WHERE projectsid='".$project['projectsid']."'";
					$this->db->query($query_projects);
				}
			}

			$data_parram = "After Url GetResponse :: ".$url_diprj." ,Response :: ".print_r($response, true)." ,httpCode :: ".$httpCode." ,Quotes ID :: ".$project['quoteid']." ,Projects ID :: ".$project['projectsid']."\r\n";
			$file_name="log-Projects-GetResponse-".date('Y-m-d').".php";
			$FileName = "C:/AppServ/www/WDCCCE/logs/API/".$file_name;
			$FileHandle = fopen($FileName, 'a+') or die("can't open file");
			fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
			fclose($FileHandle);

			curl_close($ch);
		}
		/****** projects ******/

		/****** quotes ******/
		$query_quotes = "
		SELECT
			aicrm_quotes.quoteid,
			aicrm_quotes.quote_no,
			aicrm_quotes.reference_id
		FROM
			`aicrm_quotes`
		WHERE
			aicrm_quotes.flag_erp_response_status = 1
			AND aicrm_quotes.reference_id != ''
		";

		$sql_quotes = $this->db->query($query_quotes);
		$quotes = $sql_quotes->result_array();
		// alert($quote); exit;

		foreach ($quotes as $quote) {
			
			$url_quotes = "http://202.44.218.12:1121/SAPDI/GetResponse";
			$data_quotes = [
				"ID" => $quote['reference_id']
			];

			$queryString = http_build_query($data_quotes);
			$url_quotes = $url_quotes . '?' . $queryString;

			
			$data_parram = "Befor Url GetResponse :: ".$url_quotes." ,Quotes ID :: ".$quote['quoteid'];
			$file_name="log-Quotes-GetResponse-".date('Y-m-d').".php";
			$FileName = "C:/AppServ/www/WDCCCE/logs/API/".$file_name;
			$FileHandle = fopen($FileName, 'a+') or die("can't open file");
			fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
			fclose($FileHandle);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url_quotes);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			$responseArray = json_decode($response, true);

			// alert($responseArray); exit;

			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if ($response === FALSE) {
				die('cURL Error: ' . curl_error($ch));
			}else{
				// echo $responseArray[0]['RESPONSE']; exit;
				if(($responseArray[0]['REFERENCE'] == NULL || $responseArray[0]['REFERENCE'] == "") && ($responseArray[0]['RESPONSE'] == NULL || $responseArray[0]['RESPONSE'] == "")){
					
					$query_quotes = "UPDATE aicrm_quotes SET flag_erp_response_status='1', erp_response_status='".$responseArray[0]['RESPONSE']."'  WHERE quoteid='".$quote['quoteid']."'";
					$this->db->query($query_quotes);

				}elseif(($responseArray[0]['REFERENCE'] == NULL || $responseArray[0]['REFERENCE'] == "") && $responseArray[0]['RESPONSE'] != ''){

					$query_quotes = "UPDATE aicrm_quotes SET flag_erp_response_status='0', erp_response_status='".$responseArray[0]['RESPONSE']."'  WHERE quoteid='".$quote['quoteid']."'";
					$this->db->query($query_quotes);

				}elseif($responseArray[0]['REFERENCE'] != '' && $responseArray[0]['RESPONSE'] != ''){

					$query_quotes = "UPDATE aicrm_quotes SET flag_erp_response_status='0', erp_response_status='".$responseArray[0]['RESPONSE']."' , sono='".$responseArray[0]['REFERENCE']."' WHERE quoteid='".$quote['quoteid']."'";
					$this->db->query($query_quotes);

				}
			}

			$data_parram = "After Url GetResponse :: ".$url_quotes." ,Response :: ".print_r($response, true)." ,httpCode :: ".$httpCode." ,Quotes ID :: ".$project['quoteid']."\r\n";
			$file_name="log-Quotes-GetResponse-".date('Y-m-d').".php";
			$FileName = "C:/AppServ/www/WDCCCE/logs/API/".$file_name;
			$FileHandle = fopen($FileName, 'a+') or die("can't open file");
			fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
			fclose($FileHandle);

			curl_close($ch);
		}
        /****** quotes ******/
	}
}
