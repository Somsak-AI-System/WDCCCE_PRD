<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	require_once("config.inc.php");
	require_once("library/dbconfig.php");
	include_once("library/myLibrary_mysqli.php");
	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;
	$date=date('d-m-Y');
	global $serviceAPI;

	$crmid = $_REQUEST["crmid"];
	$flag = 0;

	require_once('modules/Quotes/Quotes.php');
	require_once('modules/Users/Users.php');
	require_once('include/utils/UserInfoUtil.php');

    $_REQUEST["ajxaction"] = "DETAILVIEW";

	$current_user = new Users();
	$result = $current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id']);
	global $current_user;


	// start update limit time 
	$sql_check_limit_time = "SELECT limit_time FROM aicrm_organizationdetails";
   	$a_check_limit_time = $myLibrary_mysqli->select($sql_check_limit_time);
   	$limit_time = $a_check_limit_time[0]['limit_time'];

	$start_date=date("Y-m-d H:i:s");
    $end_time=date("Y-m-d H:i:s", mktime(date("H")  , date("i")+$limit_time, date("s"), date("m")  , date("d"), date("Y")));

	$update_limit_time = "update ai_check_user_login set start_time='".$start_date."',end_time='".$end_time."', status='0' where user_id='".$_SESSION["user_id"]."' and ipaddress='".$_SESSION["ipaddress"]."' and id='".$_SESSION["login_id"]."'";
	$myLibrary_mysqli->Query($update_limit_time);
	// end update limit time 
	

	$sql_check_data = "SELECT
        aicrm_account.passed_inspection,
		aicrm_quotes.po_number,
		aicrm_quotes.projectsid
    FROM
        aicrm_quotes
        LEFT JOIN aicrm_account ON aicrm_quotes.accountid = aicrm_account.accountid 
    WHERE
        aicrm_quotes.quoteid = '".$crmid."'";
   $a_check_data = $myLibrary_mysqli->select($sql_check_data);
   $passed_inspection = $a_check_data[0]['passed_inspection'];
   $po_number = $a_check_data[0]['po_number'];
   $projectsid = $a_check_data[0]['projectsid'];


	if($passed_inspection == 0){
        $a_reponse["status"] = false;
		$a_reponse["msg"] = $msg. "<center>ลูกค้ายังไม่ผ่านการตรวจสอบ</center>" ;
		$a_reponse["url"] = "index.php";
		echo json_encode($a_reponse);
	}elseif($po_number == ''){
		$a_reponse["status"] = false;
		$a_reponse["msg"] = $msg. "<center>ไม่พบข้อมูล หมายเลข PO No.</center>" ;
		$a_reponse["url"] = "index.php";
		echo json_encode($a_reponse);
	}elseif($projectsid != ''){
		// Check ว่าเลือก Project?
		// Flag => yes คือส่งไปที่ erp แล้วเรียก API Update
		//      => No แล้วเรียก API Insert => success 200 => update flag = Yes => ส่งไป erp ปกติ
	                                 //  => error => alert พบปัญหาการส่งข้อมูลไป erp => หยุดไม่ต้องทำต่อ 

		//  df = Project Open Date
		// dt = 2099-12-31

		// PRD 1121
		// UAT 1122

		$sql_check_data_project = "SELECT
			aicrm_projects.projects_no,
			aicrm_projects.projects_name,
			aicrm_projects.flag_project,
			DATE_FORMAT(DATE_SUB(aicrm_projects.project_open_date, INTERVAL 1 YEAR), '%Y%m%d') AS project_open_date
		FROM
			aicrm_projects
		WHERE
			aicrm_projects.projectsid = '".$projectsid."'";
	    $a_check_data_project = $myLibrary_mysqli->select($sql_check_data_project);
		$projects_no = $a_check_data_project[0]['projects_no'];
		$projects_name = $a_check_data_project[0]['projects_name'];
		$flag_project = $a_check_data_project[0]['flag_project'];
		$project_open_date = $a_check_data_project[0]['project_open_date'];

		$data_project = [];
		if($flag_project == 1){

			// $url = $serviceAPI."SAPDI/Update/DIPRJ?usercode=API&userpass=1234&prjcode=".rawurlencode($projects_no)."&prjname=".rawurlencode($projects_name)."&df=".rawurlencode($project_open_date)."&dt=20991231"; // Update
			$url = $serviceAPI."SAPDI/Update/DIPRJ"; // Update
			// echo $url; exit;

			$data_project[] = [
				"prjcode" => $projects_no,
				"prjname" => $projects_name,
				"datefrom" => $project_open_date,
				"dateto" => "20991231"
			];

			$json_data = json_encode($data_project, JSON_UNESCAPED_UNICODE);
			// cURL to send JSON data

			$data_parram = "Befor Url Update :: ".$url."\r\nProjectsid :: ".$projectsid."\r\n".print_r($json_data, true)."\r\n";
			$file_name="log-Projects-".date('Y-m-d').".php";
			$FileName = "logs/API/".$file_name;
			$FileHandle = fopen($FileName, 'a+') or die("can't open file");
			fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
			fclose($FileHandle);
	
			$ch = curl_init($url);
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

			curl_setopt($ch, CURLOPT_TIMEOUT, 30); // การทำงานทั้งหมดของ cURL ต้องไม่เกิน 30 วินาที
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // เวลาที่รอการเชื่อมต่อเป็น 10 วินาที
			
			$response = curl_exec($ch);
				
			// Check for errors
			if ($response === FALSE || curl_errno($ch)) {
				if (curl_errno($ch) == CURLE_OPERATION_TIMEOUTED) {
					$msg = "Request timed out.";
				} else {
					$msg = "Could not send request: " . curl_error($ch);
				}
				$a_reponse["status"] = false;
				$a_reponse["msg"] = $msg;
				$a_reponse["url"] = "index.php";
				echo json_encode($a_reponse); 
				
				$data_parram = "After Url Update :: ".$url."\r\nResponse :: ".$msg."\r\nProjectsid :: ".$projectsid."\r\n";
				$file_name="log-Projects-".date('Y-m-d').".php";
				$FileName = "logs/API/".$file_name;
				$FileHandle = fopen($FileName, 'a+') or die("can't open file");
				fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
				fclose($FileHandle);

				exit;
			}
			
			$responseArray = json_decode($response, true);	
			// echo $responseArray['statussync_sap']; exit;	
			// Check for errors
			if ($response === FALSE || curl_errno($ch)) {
				die('cURL Error: ' . curl_error($ch));
			}else{
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				if ($httpCode == 200 && @$responseArray['statussync_sap'] == 0) {
					$flag = 1;
					$sql_projects = "UPDATE aicrm_quotes SET flag_projects='1', projects_reference_id='".$responseArray['reference_id']."', erp_response_status='' WHERE quoteid  = '".$crmid."'";
					$myLibrary_mysqli->Query($sql_projects);
				} else {
					$flag = 0;
					$msg = "<center>พบปัญหาการส่งข้อมูล Project ไป ERP</center><br>Error ".$httpCode."<br><br>".$responseArray['remark_sap'];
				}
			}
			
			curl_close($ch);

			$data_parram = "After Url Update :: ".$url."\r\nResponse :: ".print_r($response, true)."\r\nhttpCode :: ".$httpCode."\r\nProjectsid :: ".$projectsid."\r\n";
			$file_name="log-Projects-".date('Y-m-d').".php";
			$FileName = "logs/API/".$file_name;
			$FileHandle = fopen($FileName, 'a+') or die("can't open file");
			fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
			fclose($FileHandle);
			

			// $flag = 1;
		}else{			
			// $url = $serviceAPI."SAPDI/DIPRJ?usercode=API&userpass=1234&prjcode=".rawurlencode($projects_no)."&prjname=".rawurlencode($projects_name)."&df=".rawurlencode($project_open_date)."&dt=20991231"; // Insert

			$url = $serviceAPI."SAPDI/DIPRJ"; // Insert
			// echo $url; exit;

			$data_project[] = [
				"prjcode" => $projects_no,
				"prjname" => $projects_name,
				"datefrom" => $project_open_date,
				"dateto" => "20991231"
			];
			$json_data = json_encode($data_project, JSON_UNESCAPED_UNICODE);
			
			$data_parram = "Befor Url Insert :: ".$url."\r\nProjectsid :: ".$projectsid."\r\n".print_r($json_data, true)."\r\n";
			$file_name="log-Projects-".date('Y-m-d').".php";
			$FileName = "logs/API/".$file_name;
			$FileHandle = fopen($FileName, 'a+') or die("can't open file");
			fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
			fclose($FileHandle);
	
			$ch = curl_init($url);
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

			curl_setopt($ch, CURLOPT_TIMEOUT, 30); // การทำงานทั้งหมดของ cURL ต้องไม่เกิน 30 วินาที
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // เวลาที่รอการเชื่อมต่อเป็น 10 วินาที
			
			$response = curl_exec($ch);
				
			// Check for errors
			if ($response === FALSE || curl_errno($ch)) {
				if (curl_errno($ch) == CURLE_OPERATION_TIMEOUTED) {
					$msg = "Request timed out.";
				} else {
					$msg = "Could not send request: " . curl_error($ch);
				}
				$a_reponse["status"] = false;
				$a_reponse["msg"] = $msg;
				$a_reponse["url"] = "index.php";
				echo json_encode($a_reponse); 
				
				$data_parram = "After Url Insert :: ".$url."\r\nResponse :: ".$msg."\r\nProjectsid :: ".$projectsid."\r\n";
				$file_name="log-Projects-".date('Y-m-d').".php";
				$FileName = "logs/API/".$file_name;
				$FileHandle = fopen($FileName, 'a+') or die("can't open file");
				fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
				fclose($FileHandle);
				exit;
			}
			
			$responseArray = json_decode($response, true);	
			// Check for errors
			if ($response === FALSE || curl_errno($ch)) {
				die('cURL Error: ' . curl_error($ch));
			}else{
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				if ($httpCode == 200) {
					$flag = 1;
					// echo "Response is 200 OK";
					$sql_projects = "UPDATE aicrm_quotes SET flag_projects='1', projects_reference_id='".$responseArray['reference_id']."', erp_response_status='' WHERE quoteid  = '".$crmid."'";
					$myLibrary_mysqli->Query($sql_projects);
				} else {
					$flag = 0;
					$msg = "<center>พบปัญหาการส่งข้อมูล Project ไป ERP</center><br>Error ".$httpCode."<br><br>".$responseArray['Message'];
				}
			}
			
			curl_close($ch);
			
	
			$data_parram = "After Url Insert :: ".$url."\r\nResponse :: ".print_r($response, true)."\r\nhttpCode :: ".$httpCode."\r\nProjectsid :: ".$projectsid."\r\n";
			$file_name="log-Projects-".date('Y-m-d').".php";
			$FileName = "logs/API/".$file_name;
			$FileHandle = fopen($FileName, 'a+') or die("can't open file");
			fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
			fclose($FileHandle);
	
			// print_r($response); exit;
		}

		if($flag == 1){
			$sql_item = "
			SELECT
				aicrm_quotation_type.series_code AS series,
				aicrm_quotation_type.quotation_type,
				CONCAT(aicrm_quotes.quotation_date, 'T00:00:00') AS s_date,
				CONCAT(aicrm_quotes.quotation_enddate, 'T00:00:00') AS e_date,
				DATE_FORMAT(NOW(), '%Y%m%d') AS docdate,
				aicrm_account.account_no AS cardcode,
				aicrm_crmentity.description AS soremark,
				aicrm_quotes.quote_no AS sqno,
				'THB' AS doccur,
				CASE 
					WHEN aicrm_quotes.payment_terms <> '--None--' AND aicrm_quotes.payment_terms != '' 
					THEN aicrm_quotes.payment_terms 
					ELSE '' 
				END AS groupnnum,
				aicrm_account.account_no AS addresscode,
				aicrm_account.account_no AS addresscode2,
				CASE 
					WHEN aicrm_quotes.province <> '' THEN
						CASE 
							WHEN aicrm_quotes.province = 'กรุงเทพมหานคร' THEN
								CONCAT_WS(
									' ',
									NULLIF(CONCAT(aicrm_quotes.village), ''),
									NULLIF(CONCAT('ห้องเลขที่/ชั้นที่ ', NULLIF(aicrm_quotes.room_no, '')), ''),
									NULLIF(CONCAT('เลขที่ ', NULLIF(aicrm_quotes.address_no, '')), ''),
									NULLIF(CONCAT('หมู่ ', NULLIF(aicrm_quotes.village_no, '')), ''),
									NULLIF(CONCAT('ถนน ', NULLIF(aicrm_quotes.street, '')), ''),
									NULLIF(CONCAT('ตรอก/ซอย ', NULLIF(aicrm_quotes.lane, '')), ''),
									NULLIF(CONCAT('แขวง ', NULLIF(aicrm_quotes.sub_district, '')), ''),
									NULLIF(CONCAT('เขต ', NULLIF(aicrm_quotes.district, '')), ''),
									NULLIF(CONCAT(aicrm_quotes.province), ''),
									NULLIF(CONCAT(aicrm_quotes.postal_code), '')
								)
							ELSE
								CONCAT_WS(
									' ',
									NULLIF(CONCAT(aicrm_quotes.village), ''),
									NULLIF(CONCAT('ห้องเลขที่/ชั้นที่ ', NULLIF(aicrm_quotes.room_no, '')), ''),
									NULLIF(CONCAT('เลขที่ ', NULLIF(aicrm_quotes.address_no, '')), ''),
									NULLIF(CONCAT('หมู่ ', NULLIF(aicrm_quotes.village_no, '')), ''),
									NULLIF(CONCAT('ถนน ', NULLIF(aicrm_quotes.street, '')), ''),
									NULLIF(CONCAT('ตรอก/ซอย ', NULLIF(aicrm_quotes.lane, '')), ''),
									NULLIF(CONCAT('ตำบล ', NULLIF(aicrm_quotes.sub_district, '')), ''),
									NULLIF(CONCAT('อำเภอ ', NULLIF(aicrm_quotes.district, '')), ''),
									NULLIF(CONCAT('จังหวัด', NULLIF(aicrm_quotes.province, '')), ''),
									NULLIF(CONCAT(aicrm_quotes.postal_code), '')
								)
						END
					ELSE
						aicrm_quotes.billing_address
				END AS address,
				CASE 
					WHEN aicrm_quotes.province <> '' THEN
						CASE 
							WHEN aicrm_quotes.province = 'กรุงเทพมหานคร' THEN
								CONCAT_WS(
									' ',
									NULLIF(CONCAT(aicrm_quotes.village), ''),
									NULLIF(CONCAT('ห้องเลขที่/ชั้นที่ ', NULLIF(aicrm_quotes.room_no, '')), ''),
									NULLIF(CONCAT('เลขที่ ', NULLIF(aicrm_quotes.address_no, '')), ''),
									NULLIF(CONCAT('หมู่ ', NULLIF(aicrm_quotes.village_no, '')), ''),
									NULLIF(CONCAT('ถนน ', NULLIF(aicrm_quotes.street, '')), ''),
									NULLIF(CONCAT('ตรอก/ซอย ', NULLIF(aicrm_quotes.lane, '')), ''),
									NULLIF(CONCAT('แขวง ', NULLIF(aicrm_quotes.sub_district, '')), ''),
									NULLIF(CONCAT('เขต ', NULLIF(aicrm_quotes.district, '')), ''),
									NULLIF(CONCAT(aicrm_quotes.province), ''),
									NULLIF(CONCAT(aicrm_quotes.postal_code), '')
								)
							ELSE
								CONCAT_WS(
									' ',
									NULLIF(CONCAT(aicrm_quotes.village), ''),
									NULLIF(CONCAT('ห้องเลขที่/ชั้นที่ ', NULLIF(aicrm_quotes.room_no, '')), ''),
									NULLIF(CONCAT('เลขที่ ', NULLIF(aicrm_quotes.address_no, '')), ''),
									NULLIF(CONCAT('หมู่ ', NULLIF(aicrm_quotes.village_no, '')), ''),
									NULLIF(CONCAT('ถนน ', NULLIF(aicrm_quotes.street, '')), ''),
									NULLIF(CONCAT('ตรอก/ซอย ', NULLIF(aicrm_quotes.lane, '')), ''),
									NULLIF(CONCAT('ตำบล ', NULLIF(aicrm_quotes.sub_district, '')), ''),
									NULLIF(CONCAT('อำเภอ ', NULLIF(aicrm_quotes.district, '')), ''),
									NULLIF(CONCAT('จังหวัด', NULLIF(aicrm_quotes.province, '')), ''),
									NULLIF(CONCAT(aicrm_quotes.postal_code), '')
								)
						END
					ELSE
						aicrm_quotes.billing_address
				END AS address2,
				aicrm_quotes.po_number AS numatcard,
				aicrm_users.user_name AS employeecode,
				aicrm_products.material_code AS itemcode,
				aicrm_products.productname AS itemname,
				aicrm_inventoryproductrel.comment AS remark,
				aicrm_inventoryproductrel.box_quantity AS quantity,
				aicrm_inventoryproductrel.sqm_quantity AS volume,
				'NMM' AS whcode,
				
				
				
	-- 			aicrm_inventoryproductrel.selling_price AS price,
				CASE 
					WHEN aicrm_quotes.pricetype = 'Exclude Vat' THEN
						CASE 
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.package_size_sqm_per_box)
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อแผ่น' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.package_size_sheet_per_box)
							ELSE
								aicrm_inventoryproductrel.selling_price
						END
					ELSE
						CASE 
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								((aicrm_inventoryproductrel.selling_price / 1.07) * aicrm_inventoryproductrel.package_size_sqm_per_box)
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อแผ่น' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								((aicrm_inventoryproductrel.selling_price / 1.07) * aicrm_inventoryproductrel.package_size_sheet_per_box)
							ELSE
								(aicrm_inventoryproductrel.selling_price / 1.07)
						END
				END AS price,
				
				
				'AR07' AS vatgroup,
				CASE 
					WHEN aicrm_quotes.pricetype = 'Exclude Vat' THEN
						CASE 
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								ROUND((aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.package_size_sqm_per_box), 2) * aicrm_inventoryproductrel.box_quantity
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อแผ่น' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.box_quantity) * aicrm_inventoryproductrel.package_size_sheet_per_box
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND (aicrm_inventoryproductrel.sales_unit = 'PCS' OR aicrm_inventoryproductrel.sales_unit = 'Pcs' OR aicrm_inventoryproductrel.sales_unit = 'PCS.') THEN
								ROUND((aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.box_quantity), 2) * (aicrm_inventoryproductrel.package_size_sqm_per_box / aicrm_inventoryproductrel.package_size_sheet_per_box)
							ELSE
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.box_quantity)
						END
					ELSE
						CASE 
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								(ROUND((aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.package_size_sqm_per_box), 2) * aicrm_inventoryproductrel.box_quantity) / 1.07
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อแผ่น' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								((aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.box_quantity) * aicrm_inventoryproductrel.package_size_sheet_per_box) / 1.07
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND (aicrm_inventoryproductrel.sales_unit = 'PCS' OR aicrm_inventoryproductrel.sales_unit = 'Pcs' OR aicrm_inventoryproductrel.sales_unit = 'PCS.') THEN
								(ROUND((aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.box_quantity), 2) * (aicrm_inventoryproductrel.package_size_sqm_per_box / aicrm_inventoryproductrel.package_size_sheet_per_box)) / 1.07
							ELSE
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.box_quantity) / 1.07
						END
				END AS linetotal,
				CASE 
					WHEN aicrm_quotes.pricetype = 'Include Vat' THEN
						CASE 
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.package_size_sqm_per_box)
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อแผ่น' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.package_size_sheet_per_box)
							ELSE
								aicrm_inventoryproductrel.selling_price
						END
					ELSE
						CASE 
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								((aicrm_inventoryproductrel.selling_price * 1.07) * aicrm_inventoryproductrel.package_size_sqm_per_box)
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อแผ่น' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								((aicrm_inventoryproductrel.selling_price * 1.07) * aicrm_inventoryproductrel.package_size_sheet_per_box)
							ELSE
								(aicrm_inventoryproductrel.selling_price * 1.07)
						END
				END AS priceafvat,
				aicrm_inventoryproductrel.product_price_type,
				aicrm_inventoryproductrel.pricelist_type,
				aicrm_quotes.total AS doctotal,
	
				CASE WHEN aicrm_quotes.pricetype = 'Exclude Vat' THEN 'E' ELSE 'I' END AS vattype,
				aicrm_projects.projects_no
	
			FROM
				aicrm_quotes
				INNER JOIN aicrm_crmentity ON aicrm_quotes.quoteid = aicrm_crmentity.crmid AND aicrm_crmentity.deleted = 0
				LEFT JOIN aicrm_inventoryproductrel ON aicrm_inventoryproductrel.id = aicrm_quotes.quoteid
				LEFT JOIN aicrm_products ON aicrm_inventoryproductrel.productid = aicrm_products.productid
				LEFT JOIN aicrm_account ON aicrm_quotes.accountid = aicrm_account.accountid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id 
				LEFT JOIN aicrm_quotation_type ON aicrm_quotation_type.quotation_type = aicrm_quotes.quotation_type
				LEFT JOIN aicrm_projects ON aicrm_projects.projectsid = aicrm_quotes.projectsid
			WHERE
				aicrm_quotes.quoteid = '".$crmid."'
			ORDER BY
				aicrm_inventoryproductrel.sequence_no
			;
	
			";
		
			$result = $myLibrary_mysqli->select($sql_item);
		
			if (!empty($result)) {
	
				$data = [];
				foreach ($result as $key => $item){
					$data[] = [
						"series" => (int)$item['series'],
						"docdate" => $item['docdate'],
						"cardcode" => $item['cardcode'],
						"doctotal" => (float)$item['doctotal'],
						"soremark" => str_replace(["'", '"'], "", $item['soremark']),
						"sqno" => $item['sqno'],
						"doccur" => $item['doccur'],
						"groupnum" => (int)$item['groupnnum'],
						"address" => $item['address'],
						"address2" => $item['address2'],
						"numatcard" => $item['numatcard'],
						"employeecode" => $item['employeecode'],
						"itemcode" => $item['itemcode'],
						"itemname" => str_replace(["'", '"'], "", $item['itemname']),
						"remark" => str_replace(["'", '"'], "", $item['remark']),
						"quantity" => (float)$item['quantity'],
						"volume" => (float)$item['volume'],
						"price" => number_format(round((float)$item['price'], 2), 2, '.', ''), // "price" => number_format((float)$item['price'], 4, '.', ''),
						"vatgroup" => $item['vatgroup'],
						"linetotal" => number_format((float)$item['linetotal'], 2, '.', ''),
						"priceafvat" => number_format(round((float)$item['priceafvat'], 2), 2, '.', ''), //"priceafvat" => number_format((float)$item['priceafvat'], 4, '.', ''),
						"pricetier" => $item['product_price_type'],
						"vattype" => $item['vattype'],
						"prjcode" => empty($item['projects_no']) ? '':$item['projects_no']
					];
				}
	
				// Convert data to JSON
				$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
	
				// Output JSON
				// echo $json_data;exit;
	
				// cURL to send JSON data
				global $site_URL_service;
				// $url = $site_URL_service."index.php/special/integrate/saveSaleOrder";
				$url = $serviceAPI."SAPDI/DISO";
				// echo $url; exit;
	
				$data_parram = "Befor Url :: ".$url."\r\n".print_r($json_data, true)."\r\n";
				$file_name="log-Salesorder-".date('Y-m-d').".php";
				$FileName = "logs/API/".$file_name;
				$FileHandle = fopen($FileName, 'a+') or die("can't open file");
				fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
				fclose($FileHandle);
	
				$ch = curl_init($url);
			
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

				curl_setopt($ch, CURLOPT_TIMEOUT, 30); // การทำงานทั้งหมดของ cURL ต้องไม่เกิน 30 วินาที
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // เวลาที่รอการเชื่อมต่อเป็น 10 วินาที
				
				$response = curl_exec($ch);
					
				// Check for errors
				if ($response === FALSE || curl_errno($ch)) {
					if (curl_errno($ch) == CURLE_OPERATION_TIMEOUTED) {
						$msg = "Request timed out.";
					} else {
						$msg = "Could not send request: " . curl_error($ch);
					}
					$a_reponse["status"] = false;
					$a_reponse["msg"] = $msg;
					$a_reponse["url"] = "index.php";
					echo json_encode($a_reponse); 
					
					$data_parram = "After Url :: ".$url."\r\n".print_r($json_data, true)."\r\nResponse :: ".$msg;
					$file_name="log-Salesorder-".date('Y-m-d').".php";
					$FileName = "logs/API/".$file_name;
					$FileHandle = fopen($FileName, 'a+') or die("can't open file");
					fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
					fclose($FileHandle);
					exit;
				}
						
				curl_close($ch);
			
				// Handle the response
				// echo "Response: " . print_r($response, true); exit;
	
				$data_parram = "After Url :: ".$url."\r\n".print_r($json_data, true)."\r\nResponse :: ".print_r($response, true);
				$file_name="log-Salesorder-".date('Y-m-d').".php";
				$FileName = "logs/API/".$file_name;
				$FileHandle = fopen($FileName, 'a+') or die("can't open file");
				fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
				fclose($FileHandle);
	
				// print_r($response);
				// echo $response[0]['statussync_sap']; exit;
				$responseArray = json_decode($response, true);
				// echo $responseArray['statussync_sap']; exit;
	
				if($responseArray['statussync_sap'] == 0){
					$a_reponse["status"] = true;
					$a_reponse["msg"] = $msg. "<center>ส่งข้อมูลไปยัง ERP สำเร็จ</center>" ;
					$a_reponse["url"] = "index.php";
					$a_reponse["a_data"] = $a_data;
	
					// if(!empty($responseArray['sono']) || $responseArray['sono'] != ''){
					// 	$sql = "UPDATE aicrm_quotes SET sono='".$responseArray['sono']."' WHERE quoteid  = '".$crmid."'";
					// 	$myLibrary_mysqli->Query($sql);
					// }
					$sql = "UPDATE aicrm_quotes SET flag_erp_response_status='1', reference_id='".$responseArray['reference_id']."', erp_response_status='' WHERE quoteid  = '".$crmid."'";
					$myLibrary_mysqli->Query($sql);
					
				}else{
					$a_reponse["status"] = true;
					$a_reponse["msg"] = $msg. "<center>Error : ".$responseArray['remark_sap']."</center>" ;
					$a_reponse["url"] = "index.php";
					$a_reponse["a_data"] = $a_data;
				}
				
			}else{
				$a_reponse["status"] = false;
				$a_reponse["msg"] = $msg. "<center>Data null</center>" ;
				$a_reponse["url"] = "index.php";
			}
		}else{
			$a_reponse["status"] = false;
			$a_reponse["msg"] = $msg;
			$a_reponse["url"] = "index.php";
		}
		echo json_encode($a_reponse);
	}else{
			$sql_item = "
			SELECT
				aicrm_quotation_type.series_code AS series,
				aicrm_quotation_type.quotation_type,
				CONCAT(aicrm_quotes.quotation_date, 'T00:00:00') AS s_date,
				CONCAT(aicrm_quotes.quotation_enddate, 'T00:00:00') AS e_date,
				DATE_FORMAT(NOW(), '%Y%m%d') AS docdate,
				aicrm_account.account_no AS cardcode,
				aicrm_crmentity.description AS soremark,
				aicrm_quotes.quote_no AS sqno,
				'THB' AS doccur,
				CASE 
					WHEN aicrm_quotes.payment_terms <> '--None--' AND aicrm_quotes.payment_terms != '' 
					THEN aicrm_quotes.payment_terms 
					ELSE '' 
				END AS groupnnum,
				aicrm_account.account_no AS addresscode,
				aicrm_account.account_no AS addresscode2,
				CASE 
					WHEN aicrm_quotes.province <> '' THEN
						CASE 
							WHEN aicrm_quotes.province = 'กรุงเทพมหานคร' THEN
								CONCAT_WS(
									' ',
									NULLIF(CONCAT(aicrm_quotes.village), ''),
									NULLIF(CONCAT('ห้องเลขที่/ชั้นที่ ', NULLIF(aicrm_quotes.room_no, '')), ''),
									NULLIF(CONCAT('เลขที่ ', NULLIF(aicrm_quotes.address_no, '')), ''),
									NULLIF(CONCAT('หมู่ ', NULLIF(aicrm_quotes.village_no, '')), ''),
									NULLIF(CONCAT('ถนน ', NULLIF(aicrm_quotes.street, '')), ''),
									NULLIF(CONCAT('ตรอก/ซอย ', NULLIF(aicrm_quotes.lane, '')), ''),
									NULLIF(CONCAT('แขวง ', NULLIF(aicrm_quotes.sub_district, '')), ''),
									NULLIF(CONCAT('เขต ', NULLIF(aicrm_quotes.district, '')), ''),
									NULLIF(CONCAT(aicrm_quotes.province), ''),
									NULLIF(CONCAT(aicrm_quotes.postal_code), '')
								)
							ELSE
								CONCAT_WS(
									' ',
									NULLIF(CONCAT(aicrm_quotes.village), ''),
									NULLIF(CONCAT('ห้องเลขที่/ชั้นที่ ', NULLIF(aicrm_quotes.room_no, '')), ''),
									NULLIF(CONCAT('เลขที่ ', NULLIF(aicrm_quotes.address_no, '')), ''),
									NULLIF(CONCAT('หมู่ ', NULLIF(aicrm_quotes.village_no, '')), ''),
									NULLIF(CONCAT('ถนน ', NULLIF(aicrm_quotes.street, '')), ''),
									NULLIF(CONCAT('ตรอก/ซอย ', NULLIF(aicrm_quotes.lane, '')), ''),
									NULLIF(CONCAT('ตำบล ', NULLIF(aicrm_quotes.sub_district, '')), ''),
									NULLIF(CONCAT('อำเภอ ', NULLIF(aicrm_quotes.district, '')), ''),
									NULLIF(CONCAT('จังหวัด', NULLIF(aicrm_quotes.province, '')), ''),
									NULLIF(CONCAT(aicrm_quotes.postal_code), '')
								)
						END
					ELSE
						aicrm_quotes.billing_address
				END AS address,
				CASE 
					WHEN aicrm_quotes.province <> '' THEN
						CASE 
							WHEN aicrm_quotes.province = 'กรุงเทพมหานคร' THEN
								CONCAT_WS(
									' ',
									NULLIF(CONCAT(aicrm_quotes.village), ''),
									NULLIF(CONCAT('ห้องเลขที่/ชั้นที่ ', NULLIF(aicrm_quotes.room_no, '')), ''),
									NULLIF(CONCAT('เลขที่ ', NULLIF(aicrm_quotes.address_no, '')), ''),
									NULLIF(CONCAT('หมู่ ', NULLIF(aicrm_quotes.village_no, '')), ''),
									NULLIF(CONCAT('ถนน ', NULLIF(aicrm_quotes.street, '')), ''),
									NULLIF(CONCAT('ตรอก/ซอย ', NULLIF(aicrm_quotes.lane, '')), ''),
									NULLIF(CONCAT('แขวง ', NULLIF(aicrm_quotes.sub_district, '')), ''),
									NULLIF(CONCAT('เขต ', NULLIF(aicrm_quotes.district, '')), ''),
									NULLIF(CONCAT(aicrm_quotes.province), ''),
									NULLIF(CONCAT(aicrm_quotes.postal_code), '')
								)
							ELSE
								CONCAT_WS(
									' ',
									NULLIF(CONCAT(aicrm_quotes.village), ''),
									NULLIF(CONCAT('ห้องเลขที่/ชั้นที่ ', NULLIF(aicrm_quotes.room_no, '')), ''),
									NULLIF(CONCAT('เลขที่ ', NULLIF(aicrm_quotes.address_no, '')), ''),
									NULLIF(CONCAT('หมู่ ', NULLIF(aicrm_quotes.village_no, '')), ''),
									NULLIF(CONCAT('ถนน ', NULLIF(aicrm_quotes.street, '')), ''),
									NULLIF(CONCAT('ตรอก/ซอย ', NULLIF(aicrm_quotes.lane, '')), ''),
									NULLIF(CONCAT('ตำบล ', NULLIF(aicrm_quotes.sub_district, '')), ''),
									NULLIF(CONCAT('อำเภอ ', NULLIF(aicrm_quotes.district, '')), ''),
									NULLIF(CONCAT('จังหวัด', NULLIF(aicrm_quotes.province, '')), ''),
									NULLIF(CONCAT(aicrm_quotes.postal_code), '')
								)
						END
					ELSE
						aicrm_quotes.billing_address
				END AS address2,
				aicrm_quotes.po_number AS numatcard,
				aicrm_users.user_name AS employeecode,
				aicrm_products.material_code AS itemcode,
				aicrm_products.productname AS itemname,
				aicrm_inventoryproductrel.comment AS remark,
				aicrm_inventoryproductrel.box_quantity AS quantity,
				aicrm_inventoryproductrel.sqm_quantity AS volume,
				'NMM' AS whcode,
				
				
				
	-- 			aicrm_inventoryproductrel.selling_price AS price,
				CASE 
					WHEN aicrm_quotes.pricetype = 'Exclude Vat' THEN
						CASE 
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.package_size_sqm_per_box)
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อแผ่น' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.package_size_sheet_per_box)
							ELSE
								aicrm_inventoryproductrel.selling_price
						END
					ELSE
						CASE 
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								((aicrm_inventoryproductrel.selling_price / 1.07) * aicrm_inventoryproductrel.package_size_sqm_per_box)
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อแผ่น' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								((aicrm_inventoryproductrel.selling_price / 1.07) * aicrm_inventoryproductrel.package_size_sheet_per_box)
							ELSE
								(aicrm_inventoryproductrel.selling_price / 1.07)
						END
				END AS price,
				
				
				'AR07' AS vatgroup,
				CASE 
					WHEN aicrm_quotes.pricetype = 'Exclude Vat' THEN
						CASE 
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								ROUND((aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.package_size_sqm_per_box), 2) * aicrm_inventoryproductrel.box_quantity
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อแผ่น' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.box_quantity) * aicrm_inventoryproductrel.package_size_sheet_per_box
							ELSE
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.box_quantity)
						END
					ELSE
						CASE 
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								(ROUND((aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.package_size_sqm_per_box), 2) * aicrm_inventoryproductrel.box_quantity) / 1.07
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อแผ่น' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								((aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.box_quantity) * aicrm_inventoryproductrel.package_size_sheet_per_box) / 1.07
							ELSE
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.box_quantity) / 1.07
						END
				END AS linetotal,
				CASE 
					WHEN aicrm_quotes.pricetype = 'Include Vat' THEN
						CASE 
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.package_size_sqm_per_box)
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อแผ่น' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								(aicrm_inventoryproductrel.selling_price * aicrm_inventoryproductrel.package_size_sheet_per_box)
							ELSE
								aicrm_inventoryproductrel.selling_price
						END
					ELSE
						CASE 
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อตร.ม.' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								((aicrm_inventoryproductrel.selling_price * 1.07) * aicrm_inventoryproductrel.package_size_sqm_per_box)
							WHEN aicrm_inventoryproductrel.pricelist_type = 'ราคาต่อแผ่น' AND aicrm_inventoryproductrel.sales_unit = 'Box' THEN
								((aicrm_inventoryproductrel.selling_price * 1.07) * aicrm_inventoryproductrel.package_size_sheet_per_box)
							ELSE
								(aicrm_inventoryproductrel.selling_price * 1.07)
						END
				END AS priceafvat,
				aicrm_inventoryproductrel.product_price_type,
				aicrm_inventoryproductrel.pricelist_type,
				aicrm_quotes.total AS doctotal,
	
				CASE WHEN aicrm_quotes.pricetype = 'Exclude Vat' THEN 'E' ELSE 'I' END AS vattype,
				aicrm_projects.projects_no
	
			FROM
				aicrm_quotes
				INNER JOIN aicrm_crmentity ON aicrm_quotes.quoteid = aicrm_crmentity.crmid AND aicrm_crmentity.deleted = 0
				LEFT JOIN aicrm_inventoryproductrel ON aicrm_inventoryproductrel.id = aicrm_quotes.quoteid
				LEFT JOIN aicrm_products ON aicrm_inventoryproductrel.productid = aicrm_products.productid
				LEFT JOIN aicrm_account ON aicrm_quotes.accountid = aicrm_account.accountid
				LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id 
				LEFT JOIN aicrm_quotation_type ON aicrm_quotation_type.quotation_type = aicrm_quotes.quotation_type
				LEFT JOIN aicrm_projects ON aicrm_projects.projectsid = aicrm_quotes.projectsid
			WHERE
				aicrm_quotes.quoteid = '".$crmid."'
			ORDER BY
				aicrm_inventoryproductrel.sequence_no
			;
	
			";
		
			$result = $myLibrary_mysqli->select($sql_item);
		
			if (!empty($result)) {
	
				$data = [];
				foreach ($result as $key => $item){
					$data[] = [
						"series" => (int)$item['series'],
						"docdate" => $item['docdate'],
						"cardcode" => $item['cardcode'],
						"doctotal" => (float)$item['doctotal'],
						"soremark" => str_replace(["'", '"'], "", $item['soremark']),
						"sqno" => $item['sqno'],
						"doccur" => $item['doccur'],
						"groupnum" => (int)$item['groupnnum'],
						"address" => $item['address'],
						"address2" => $item['address2'],
						"numatcard" => $item['numatcard'],
						"employeecode" => $item['employeecode'],
						"itemcode" => $item['itemcode'],
						"itemname" => str_replace(["'", '"'], "", $item['itemname']),
						"remark" => str_replace(["'", '"'], "", $item['remark']),
						"quantity" => (float)$item['quantity'],
						"volume" => (float)$item['volume'],
						"price" => number_format(round((float)$item['price'], 2), 2, '.', ''), // "price" => number_format((float)$item['price'], 4, '.', ''),
						"vatgroup" => $item['vatgroup'],
						"linetotal" => number_format((float)$item['linetotal'], 2, '.', ''),
						"priceafvat" => number_format(round((float)$item['priceafvat'], 2), 2, '.', ''), //"priceafvat" => number_format((float)$item['priceafvat'], 4, '.', ''),
						"pricetier" => $item['product_price_type'],
						"vattype" => $item['vattype'],
						"prjcode" => empty($item['projects_no']) ? '':$item['projects_no']
					];
				}
	
				// Convert data to JSON
				$json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
	
				// Output JSON
				// echo $json_data;exit;
	
				// cURL to send JSON data
				global $site_URL_service;
				// $url = $site_URL_service."index.php/special/integrate/saveSaleOrder";
				$url = $serviceAPI."SAPDI/DISO";
				// echo $url; exit;
	
				$data_parram = "Befor Url :: ".$url."\r\n".print_r($json_data, true)."\r\n";
				$file_name="log-Salesorder-".date('Y-m-d').".php";
				$FileName = "logs/API/".$file_name;
				$FileHandle = fopen($FileName, 'a+') or die("can't open file");
				fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
				fclose($FileHandle);
	
				$ch = curl_init($url);
			
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

				curl_setopt($ch, CURLOPT_TIMEOUT, 30); // การทำงานทั้งหมดของ cURL ต้องไม่เกิน 30 วินาที
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // เวลาที่รอการเชื่อมต่อเป็น 10 วินาที
			
				$response = curl_exec($ch);
				
				// Check for errors
				if ($response === FALSE || curl_errno($ch)) {
					if (curl_errno($ch) == CURLE_OPERATION_TIMEOUTED) {
						$msg = "Request timed out.";
					} else {
						$msg = "Could not send request: " . curl_error($ch);
					}
					$a_reponse["status"] = false;
					$a_reponse["msg"] = $msg;
					$a_reponse["url"] = "index.php";
					echo json_encode($a_reponse); 
					
					$data_parram = "After Url :: ".$url."\r\n".print_r($json_data, true)."\r\nResponse :: ".$msg;
					$file_name="log-Salesorder-".date('Y-m-d').".php";
					$FileName = "logs/API/".$file_name;
					$FileHandle = fopen($FileName, 'a+') or die("can't open file");
					fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
					fclose($FileHandle);
					
					exit;
				}
			
				curl_close($ch);
			
				// Handle the response
				// echo "Response: " . print_r($response, true); exit;
	
				$data_parram = "After Url :: ".$url."\r\n".print_r($json_data, true)."\r\nResponse :: ".print_r($response, true);
				$file_name="log-Salesorder-".date('Y-m-d').".php";
				$FileName = "logs/API/".$file_name;
				$FileHandle = fopen($FileName, 'a+') or die("can't open file");
				fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
				fclose($FileHandle);
	
				// print_r($response);
				// echo $response[0]['statussync_sap']; exit;
				$responseArray = json_decode($response, true);
				// echo $responseArray['statussync_sap']; exit;
	
				if($responseArray['statussync_sap'] == 0){
					$a_reponse["status"] = true;
					$a_reponse["msg"] = $msg. "<center>ส่งข้อมูลไปยัง ERP สำเร็จ</center>" ;
					$a_reponse["url"] = "index.php";
					$a_reponse["a_data"] = $a_data;
	
					// if(!empty($responseArray['sono']) || $responseArray['sono'] != ''){
					// 	$sql = "UPDATE aicrm_quotes SET sono='".$responseArray['sono']."' WHERE quoteid  = '".$crmid."'";
					// 	$myLibrary_mysqli->Query($sql);
					// }
					$sql = "UPDATE aicrm_quotes SET flag_erp_response_status='1', reference_id='".$responseArray['reference_id']."', erp_response_status='' WHERE quoteid  = '".$crmid."'";
					$myLibrary_mysqli->Query($sql);
					
				}else{
					$a_reponse["status"] = true;
					$a_reponse["msg"] = $msg. "<center>Error : ".$responseArray['remark_sap']."</center>" ;
					$a_reponse["url"] = "index.php";
					$a_reponse["a_data"] = $a_data;
				}
				
			}else{
				$a_reponse["status"] = false;
				$a_reponse["msg"] = $msg. "<center>Data null</center>" ;
				$a_reponse["url"] = "index.php";
			}
		echo json_encode($a_reponse);
	}	
?>