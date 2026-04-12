<?php
//echo "555";
ini_set('max_execution_time', 36000);
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');

class Batch extends MY_Controller
{
  	public function __construct()
  	{
    	parent::__construct();
		parent::__construct();
        $this->load->library('memcached_library');
        $this->load->library('crmentity');
        $this->load->database();
        $this->load->library("common");
        $this->_format = "array";
        $this->load->library('lib_import');
  	}

	public function sendbroadcast(){
			
	  	$this->load->database();
	  	$this->common->_filename= "Auto_Send_Brocast";
	  	$date= date('Y-m-d');
	  	$sql = "select aicrm_voucher.* ,message_customer.* from aicrm_voucher
		inner join aicrm_vouchercf on aicrm_vouchercf.voucherid = aicrm_voucher.voucherid
		inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_voucher.voucherid
		inner join message_customer on message_customer.parentid = aicrm_voucher.accountid
		where aicrm_crmentity.deleted = 0 and aicrm_voucher.startdate = '".$date."' and aicrm_voucher.broadcast = '1' and aicrm_voucher.voucher_status = 'สร้าง' ";
	  	$query = $this->db->query($sql);
	  	// ###### add log format ##################
	  	if(!$query){
	  		$this->common->set_log("1.Query ".$sql,"",$this->db->_error_message());
	  	}else{
	  		$a_data  = $query->result_array();
	  		$this->common->set_log("1.Query ".$sql,"",$a_data);
	  		$result = array();
			$this->load->library('lib_send_voucher');
			$method = 'send_broadcast_voucher';
			$this->lib_send_voucher->send_broadcast_voucher($method , $a_data);
	  	}
	  	return true;
	}

    public function testApi()
    {
        $request_body = file_get_contents('php://input');
		$post = json_decode($request_body, true);

        echo json_encode($post, JSON_UNESCAPED_UNICODE);
    }
		
	public function sendbroadcast_case(){
		$this->load->database();
		$this->common->_filename="Auto_Send_Brocast";
		$date= date('Y-m-d');
		$sql = "";
		$query = $this->db->query($sql);
		// ###### add log format ##################
		if (!$query) {
			$this->common->set_log("1.Query ".$sql,"",$this->db->_error_message());
		}else{
			$a_data = $query->result_array();
			$this->common->set_log("1.Query ".$sql,"",$a_data);
			$result = array();
		$this->load->library('lib_send_case');
		$method = 'send_broadcast_case';
		$this->lib_send_case->send_broadcast_case($method , $a_data);
		}
		return true;
	}

	public function autoGenVoucher(){

        $sql = $this->db->get_where('tbt_generate_promotionvoucher', ['autogen_status'=>'Pending']);
        $temps = $sql->result_array();
        
        $datetime = date('Y-m-d H:i:s');
        $this->load->library('crmentity');

        foreach($temps as $temp){
            $count_record = 0;

            for($i=1; $i<=$temp['voucher_amount']; $i++){

                $voucherno = $this->generateRandomString(15);

                $data = [[
                    'voucher_no' => $voucherno,
                    'voucher_name' => $temp['voucher_name'],
                    'promotionvoucherid' => $temp['promotionvoucherid'],
                    'startdate' => $temp['startdate'],
                    'enddate' => $temp['enddate'],
                    'voucher_usage' => $temp['voucher_usage'],
                    'voucher_type' => $temp['voucher_type'],
                    'value' => $temp['value'],
                    'max_discount_amount' => $temp['max_discount_amount'],
                    'minimum_purchase' => $temp['minimum_purchase'],
                    'generate' => $temp['generate'],
                    'voucher_status' => 'สร้าง',
                    'vouchermessage'=> $temp['vouchermessage'],
                    'voucherremark' => $temp['voucherremark'],
                    'number_of_vouchers'=> 0,
                    'vouchers_of_used'=> 0,
                    'vouchers_of_remaining'=> 0,
                    'smcreatorid' => $temp['created_by'],
                    'smownerid' => $temp['created_by'],
                    'modifiedby' => $temp['created_by']
                ]];
                
                $tabName = ['aicrm_crmentity', 'aicrm_voucher', 'aicrm_vouchercf'];
                $tabIndex = ['aicrm_crmentity' => 'crmid', 'aicrm_voucher' => 'voucherid', 'aicrm_vouchercf' => 'voucherid'];
                list($chk, $crmid, $DocNo) = $this->crmentity->Insert_Update('Voucher', $crmid, 'add', $tabName, $tabIndex, $data);
                if($chk=="0"){
                	$count_record++;
                }
                $this->db->update('aicrm_voucher', ['voucher_no'=>$voucherno], ['voucherid'=>$crmid]);
                // alert($crmid);
            }
            $this->db->update('tbt_generate_promotionvoucher', ['autogen_status'=>'Complete', 'updated_at' => date('Y-m-d H:i:s')],['id'=>$temp['id']]);

            $sql_p = $this->db->get_where('aicrm_promotionvoucher', ['promotionvoucherid'=>$temp['promotionvoucherid']]);
	        $data_p = $sql_p->result_array();
	        $voucher_amount = ($data_p[0]['voucher_amount']+$count_record);
	        $voucher_available = ($data_p[0]['voucher_available']+$count_record);
	        $this->db->update('aicrm_promotionvoucher', ['voucher_amount'=>$voucher_amount, 'voucher_available' => $voucher_available],['promotionvoucherid'=>$temp['promotionvoucherid']]);
        }

        echo 'Auto Generate Voucher Finished';
    }

    public function generateRandomString($length = 10) {
        $characters = date('YmdHis').'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /*public function import_sap_account(){
        $file = array();
        $mydir = 'D:\AppServ\www\interface_sap'; 
        $myfiles = array_diff(scandir($mydir), array('.', '..')); 
        $FileName = "import/account/Import_SAP_Accounts";
        $im_type = "Import_SAP_Accounts";
        
        $this->common->_filename= $im_type;
        foreach ($myfiles as $key => $value) {
            if (stripos($value, "CUSTOMER_") !== false) {
                $file[] = $value; 
            }
        }
        
        //Open File CUSTOMER
        $data_customer = array();
        foreach ($file as $key => $value) {

            if (($open = @fopen('D:\AppServ\www\interface_sap/'.$value, 'r')) !== FALSE) 
            {
                while (($data = fgetcsv($open, 1000, ",")) !== FALSE) 
                { 
                  
                    if($data[0] != 'SOURCE'){

                     $data_customer[] = $data;
                  } 
                
                }
              
                fclose($open);
            }
        }
        
        //alert($data_customer); exit;

        //Move File
        $OriginalFileRoot = "D:\AppServ\www\interface_sap";
        $DestinationRoot = "D:\AppServ\www\interface_sap/backup";
        if(!is_dir($DestinationRoot)){
            mkdir($DestinationRoot,0777,true);
        }
        foreach($file as $OriginalFile){
            $FileExt = pathinfo($OriginalFileRoot."\\".$OriginalFile, PATHINFO_EXTENSION); // Get the file extension
            $Filename = basename($OriginalFile, ".".$FileExt); // Get the filename
            $DestinationFile = $DestinationRoot."\\".$Filename.".".$FileExt; // Create the destination filename 
            rename($OriginalFileRoot."\\".$OriginalFile, $DestinationFile); // rename the file            
        }

        $this->common->set_log_import(date('Y-m-d H:i:s')." Begin =================================================================\r\n",$FileName);
        $this->common->set_log_import(json_encode($data_customer),$FileName);
        $this->common->set_log_import(date('Y-m-d H:i:s')." End ===================================================================\r\n",$FileName);

        $this->lib_import->SET_Accounts_import($data_customer);

        Modules::run('import/IMAccounts');
    }*/

    /*public function import_sap_contact(){
        $file = array();
        $mydir = 'D:\AppServ\www\interface_sap'; 
        $myfiles = array_diff(scandir($mydir), array('.', '..')); 
        $FileName = "import/account/Import_SAP_Accounts";
        $im_type = "Import_SAP_Contacts";
        
        $this->common->_filename= $im_type;
        foreach ($myfiles as $key => $value) {
            if (stripos($value, "CONTACT_") !== false) {
                $file[] = $value; 
            }
        }
        
        //Open File CUSTOMER
        $data_contacts = array();
        foreach ($file as $key => $value) {

            if (($open = @fopen('D:\AppServ\www\interface_sap/'.$value, 'r')) !== FALSE) 
            {
                while (($data = fgetcsv($open, 1000, ",")) !== FALSE) 
                { 
                  if($data[0] != 'CONTACT_STATUS'){
                     $data_contacts[] = $data;
                  } 
                
                }
              
                fclose($open);
            }
        }
        
        //alert($data_contacts); exit;
        
        //Move File
        $OriginalFileRoot = "D:\AppServ\www\interface_sap";
        $DestinationRoot = "D:\AppServ\www\interface_sap/backup";
        if(!is_dir($DestinationRoot)){
            mkdir($DestinationRoot,0777,true);
        }
        foreach($file as $OriginalFile){
            $FileExt = pathinfo($OriginalFileRoot."\\".$OriginalFile, PATHINFO_EXTENSION); // Get the file extension
            $Filename = basename($OriginalFile, ".".$FileExt); // Get the filename
            $DestinationFile = $DestinationRoot."\\".$Filename.".".$FileExt; // Create the destination filename 
            rename($OriginalFileRoot."\\".$OriginalFile, $DestinationFile); // rename the file            
        }

        $this->common->set_log_import(date('Y-m-d H:i:s')." Begin =================================================================\r\n",$FileName);
        $this->common->set_log_import(json_encode($data_contacts),$FileName);
        $this->common->set_log_import(date('Y-m-d H:i:s')." End ===================================================================\r\n",$FileName);

        $this->lib_import->SET_Contacts_import($data_contacts);
        //alert($data07); exit;
       Modules::run('import/IMContacts');
    }*/

    /*public function import_sap_products(){
        $file06 = array();
        $mydir = 'C:\AppServ\www\interface_sap\UMIT'; 
        $myfiles = array_diff(scandir($mydir), array('.', '..'));
        $FileName = "import/product/Import_SAP_Products";
        $im_type = "Import_SAP_Products";
        
        $this->common->_filename= $im_type;

        foreach ($myfiles as $key => $value) {
            if (stripos($value, "_0006_") !== false) {
                $file06[] = $value; 
            }
        }
        //Open File 0006 Products
        $data06 = array();
        foreach ($file06 as $key => $value) {
            $fp = @fopen('C:\AppServ\www\interface_sap/UMIT/'.$value, 'r');
            if ($fp) {
               $array = explode("\n", fread($fp, filesize('C:\AppServ\www\interface_sap/UMIT/'.$value)));
                foreach ($array as $k06 => $v06) {
                    if($k06 != 0){
                        if($v06 != ''){
                           $data06[] =  explode("|", trim($v06));
                        }
                    }
                }
            }
            fclose($fp);
        }
        //alert($data06); exit;
        //Move File
        $OriginalFileRoot = "C:\AppServ\www\interface_sap\UMIT";
        $DestinationRoot = "C:\AppServ\www\interface_sap/backup/UMIT";
        if(!is_dir($DestinationRoot)){
            mkdir($DestinationRoot,0777,true);
        }
        foreach($file06 as $OriginalFile){
            $FileExt = pathinfo($OriginalFileRoot."\\".$OriginalFile, PATHINFO_EXTENSION); // Get the file extension
            $Filename = basename($OriginalFile, ".".$FileExt); // Get the filename
            $DestinationFile = $DestinationRoot."\\".$Filename.".".$FileExt; // Create the destination filename 
            rename($OriginalFileRoot."\\".$OriginalFile, $DestinationFile); // rename the file            
        }

        $this->common->set_log_import(date('Y-m-d H:i:s')." Begin =================================================================\r\n",$FileName);
        $this->common->set_log_import(json_encode($data06),$FileName);
        $this->common->set_log_import(date('Y-m-d H:i:s')." End ===================================================================\r\n",$FileName);

        $this->lib_import->SET_Products_import($data06);
        //alert($data06); exit;

        Modules::run('import/IMProducts');
    }*/

    public function import_sap_account(){
        $RootPath = 'D:\\\\Appserv\\\\www\\\\interface_sap\\\\';
        $BackupPath = $RootPath.'backup';
        $Pro_Files = array_diff(scandir($RootPath),  ['.','..']);
        
        $Commands = [];
        
        foreach($Pro_Files as $File){
            if (stripos($File, "customer_") !== false){
                copy($RootPath.$File, $BackupPath."\\\\".$File);
                $Commands[] = "LOAD DATA INFILE '".$BackupPath."\\\\".$File."' INTO TABLE tbt_import_accounts FIELDS TERMINATED BY '|' IGNORE 1 LINES; update tbt_import_accounts set import_date = now() , action = 'Insert' ,status = 0;";
                unlink($RootPath.$File);
            }
        }

        $Commands = implode('', $Commands);
        $CommandSQL = 'truncate table tbt_import_accounts;';
        $CommandSQL .= $Commands;
       
        $CommandSQL .= 'call p_sap_generate_accounts();';
        $FileName = 'D:\\Appserv\\www\\interface_sap\\SQLfile\\AccLoadFileToDB.sql';
        if(!file_exists($FileName)){
            $fp = fopen($FileName, 'w');
            fclose($fp);
        }else{
            unlink($FileName);
        }

        $fp = fopen($FileName, 'w');
        fwrite($fp, $CommandSQL);
        fclose($fp);

        echo 'Finished';
    }

    public function import_sap_contact(){
        $RootPath = 'D:\\\\Appserv\\\\www\\\\interface_sap\\\\';
        $BackupPath = $RootPath.'backup';
        $Pro_Files = array_diff(scandir($RootPath),  ['.','..']);
        
        $Commands = [];
        
        foreach($Pro_Files as $File){
            if (stripos($File, "contact_") !== false){
                copy($RootPath.$File, $BackupPath."\\\\".$File);
                $Commands[] = "LOAD DATA INFILE '".$BackupPath."\\\\".$File."' INTO TABLE tbt_import_contact FIELDS TERMINATED BY '|' IGNORE 1 LINES; update tbt_import_contact set import_date = now() , action = 'Insert' ,status = 0;";
                unlink($RootPath.$File);
            }
        }

        $Commands = implode('', $Commands);
        $CommandSQL = 'truncate table tbt_import_contact;';
        $CommandSQL .= $Commands;
       
        $CommandSQL .= 'call p_sap_generate_contacts();';
        $FileName = 'D:\\Appserv\\www\\interface_sap\\SQLfile\\ConLoadFileToDB.sql';
        if(!file_exists($FileName)){
            $fp = fopen($FileName, 'w');
            fclose($fp);
        }else{
            unlink($FileName);
        }

        $fp = fopen($FileName, 'w');
        fwrite($fp, $CommandSQL);
        fclose($fp);

        echo 'Finished';
    }

    public function import_sap_products(){
        $RootPath = 'D:\\\\Appserv\\\\www\\\\interface_sap\\\\';
        $BackupPath = $RootPath.'backup';
        $Pro_Files = array_diff(scandir($RootPath),  ['.','..']);
        
        $Commands = [];
        
        foreach($Pro_Files as $File){
            if (stripos($File, "product_") !== false){
                copy($RootPath.$File, $BackupPath."\\\\".$File);
                $Commands[] = "LOAD DATA INFILE '".$BackupPath."\\\\".$File."' INTO TABLE tbt_import_products FIELDS TERMINATED BY '|' IGNORE 1 LINES; update tbt_import_products set import_date = now() , action = 'Insert' ,status = 0;";
                unlink($RootPath.$File);
            }
        }

        $Commands = implode('', $Commands);
        $CommandSQL = 'truncate table tbt_import_products;';
        $CommandSQL .= $Commands;
       
        $CommandSQL .= 'call p_sap_generate_products();';
        $FileName = 'D:\\Appserv\\www\\interface_sap\\SQLfile\\ProLoadFileToDB.sql';
        if(!file_exists($FileName)){
            $fp = fopen($FileName, 'w');
            fclose($fp);
        }else{
            unlink($FileName);
        }

        $fp = fopen($FileName, 'w');
        fwrite($fp, $CommandSQL);
        fclose($fp);

        echo 'Finished';
    }

    public function import_sap_salesinvoice(){
        $RootPath = 'D:\\\\Appserv\\\\www\\\\interface_sap\\\\';
        $BackupPath = $RootPath.'backup';
        $SiFiles = array_diff(scandir($RootPath),  ['.','..']);
        
        $Commands = [];
        foreach($SiFiles as $File){
            if (stripos($File, "salesinvoice_") !== false){
                copy($RootPath.$File, $BackupPath."\\\\".$File);
                /*$Commands[] = "LOAD DATA INFILE '".$BackupPath."\\\\".$File."' INTO TABLE tbt_import_salesinvoice FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\\n' IGNORE 1 ROWS;";*/
                $Commands[] = "LOAD DATA INFILE '".$BackupPath."\\\\".$File."' INTO TABLE tbt_import_salesinvoice FIELDS TERMINATED BY '|' IGNORE 1 LINES;";
                unlink($RootPath.$File);
            }
        }

        $Commands = implode('', $Commands);

        $CommandSQL = 'truncate table tbt_import_salesinvoice;';
        $CommandSQL .= $Commands;
        $CommandSQL .= 'call p_sap_generate_salesinvoice();';
        
        $FileName = 'D:\\Appserv\\www\\interface_sap\\SQLfile\\SiLoadFileToDB.sql';
        if(!file_exists($FileName)){
            $fp = fopen($FileName, 'w');
            fclose($fp);
        }else{
            unlink($FileName);
        }

        $fp = fopen($FileName, 'w');
        fwrite($fp, $CommandSQL);
        fclose($fp);

        echo 'Finished';
    }

    public function import_sap_helpdesk(){
        $RootPath = 'D:\\\\Appserv\\\\www\\\\interface_sap\\\\';
        $BackupPath = $RootPath.'backup';
        $SiFiles = array_diff(scandir($RootPath),  ['.','..']);
        
        $Commands = [];
        foreach($SiFiles as $File){
            if (stripos($File, "case_") !== false){
                copy($RootPath.$File, $BackupPath."\\\\".$File);
                $Commands[] = "LOAD DATA INFILE '".$BackupPath."\\\\".$File."' INTO TABLE tbt_import_helpdesk FIELDS TERMINATED BY '|' IGNORE 1 LINES;";
                unlink($RootPath.$File);
            }
        }

        $Commands = implode('', $Commands);

        $CommandSQL = 'truncate table tbt_import_helpdesk;';
        $CommandSQL .= $Commands;
        $CommandSQL .= 'call p_sap_generate_helpdesk();';
        
        $FileName = 'D:\\Appserv\\www\\interface_sap\\SQLfile\\HelpdeskLoadFileToDB.sql';
        if(!file_exists($FileName)){
            $fp = fopen($FileName, 'w');
            fclose($fp);
        }else{
            unlink($FileName);
        }

        $fp = fopen($FileName, 'w');
        fwrite($fp, $CommandSQL);
        fclose($fp);

        echo 'Finished';
    }

    public function repeat_purchase($a_params=array())
    {
        
        $this->common->_filename= "Repeat_Purchase";
        
        $tab_name = array('aicrm_crmentity','aicrm_smartsms','aicrm_smartsmscf');
        $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_smartsms'=>'smartsmsid','aicrm_smartsmscf'=>'smartsmsid');

        $a_condition["aicrm_smartemail.email_start_date = DATE_ADD(DATE(NOW()), INTERVAL -2 DAY)"] = null;
        $a_condition["aicrm_smartemail.flow"] = 'Repeat Purchase';
        $a_condition["aicrm_crmentity.deleted"] = "0";
        $a_condition["aicrm_smartemail.email_status"] = "Completed";

        $this->db->select("aicrm_smartemail.smartemailid ,aicrm_smartemail.smartemail_no ,aicrm_smartemail.sms_message , aicrm_crmentity.smownerid");
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_smartemail.smartemailid',"inner");
        $this->db->where($a_condition);
        $query = $this->db->get('aicrm_smartemail');
        //echo $this->db->last_query(); exit;
        $d_smartemial = $query->result_array();
        //alert($d_smartemial); exit;
        $this->common->set_log('Get Smartemail---> ',date('Y-m-d'),$d_smartemial);

        if(!empty($d_smartemial)){

            foreach ($d_smartemial as $key => $value) {
                
                $crmid="";
                $doc_no="";
                $action="add";
                $module= "SmartSms";
                $data = array();
                $smartemailid = $value['smartemailid'];
                $userid = 1;
                $sms_sender_name = 4;
                /*Sender SMS*/
                $from_name_email = explode(':', $value['email_from_name']);
                if($from_name_email[0] == 'Lucaris Crystal'){
                    $sms_sender_name = 5;
                }else{
                    $sms_sender_name = 4;
                }

                $data[] =array(
                    'smartsms_no'               => '',
                    'smartsms_name'             => 'Repeat Purchase-'.$value['smartemail_no'],
                    'sms_status'                => 'Schedule',
                    'sms_sender_name'           => $sms_sender_name,
                    'sms_message'               => $value['sms_message'],
                    'sms_start_date'            => date('Y-m-d'),
                    'sms_start_time'            => '11:00',
                    'sms_character'             => $this->get_character($value['sms_message']),
                    'sms_credit'                => $this->get_credit($value['sms_message']),
                    'smartemailid'              => $value['smartemailid'],
                    'smownerid'                 => $value['smownerid'],
                );

                list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$tab_name,$tab_name_index,$data,$userid);
                $this->common->set_log('Inert SmartSMS---> ',$crmid, $DocNo);

                $sql="INSERT INTO aicrm_smartsms_accountsrel (smartsmsid, accountid)
                select '".$crmid."',aicrm_account.accountid 
                from aicrm_smartemail_accountsrel
                inner join aicrm_account on aicrm_account.accountid = aicrm_smartemail_accountsrel.accountid
                inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
                where aicrm_smartemail_accountsrel.smartemailid = '".$smartemailid."' and aicrm_account.repeat_buyers = 0";
                $this->db->query($sql);

                $sql_s = "update aicrm_smartsms 
                       left join aicrm_config_sender_sms on aicrm_config_sender_sms.id = aicrm_smartsms.sms_sender_name
                       set aicrm_smartsms.sms_sender = aicrm_config_sender_sms.sms_sender , aicrm_smartsms.sms_url = aicrm_config_sender_sms.sms_url ,
                       aicrm_smartsms.sms_username = aicrm_config_sender_sms.sms_username , aicrm_smartsms.sms_password = aicrm_config_sender_sms.sms_password 
                       where  aicrm_smartsms.smartsmsid = '".$crmid."' "; 
                $this->db->query($sql_s);   

            }
    
        }
        
        return true;
    }

    public function monthly_promotion($a_params=array())
    {
        $this->common->_filename= "Monthly_Promotion";
        $tab_name = array('aicrm_crmentity','aicrm_smartsms','aicrm_smartsmscf');
        $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_smartsms'=>'smartsmsid','aicrm_smartsmscf'=>'smartsmsid');
        
        $a_condition["aicrm_smartemail.email_start_date = DATE_ADD(DATE(NOW()), INTERVAL -1 DAY)"] = null;
        $a_condition["aicrm_smartemail.flow"] = 'Monthly Promotion or Newsletter';
        $a_condition["aicrm_crmentity.deleted"] = "0";
        $a_condition["aicrm_smartemail.email_status"] = "Completed";

        $this->db->select("aicrm_smartemail.smartemailid ,aicrm_smartemail.smartemail_no ,aicrm_smartemail.sms_message , aicrm_crmentity.smownerid");
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_smartemail.smartemailid',"inner");
        $this->db->where($a_condition);
        $query = $this->db->get('aicrm_smartemail');
        //echo $this->db->last_query(); exit;
        $d_smartemial = $query->result_array();
        //alert($d_smartemial); exit;
        $this->common->set_log('Get Smartemail---> ',date('Y-m-d'),$d_smartemial);

        if(!empty($d_smartemial)){

            foreach ($d_smartemial as $key => $value) {
                
                $crmid="";
                $doc_no="";
                $action="add";
                $module= "SmartSms";
                $data = array();
                $smartemailid = $value['smartemailid'];
                $userid = 1;
                $sms_sender_name = 4;
                /*Sender SMS*/
                $from_name_email = explode(':', $value['email_from_name']);
                if($from_name_email[0] == 'Lucaris Crystal'){
                    $sms_sender_name = 5;
                }else{
                    $sms_sender_name = 4;
                }
                /*Sender SMS*/

                $data[] =array(
                    'smartsms_no'               => '',
                    'smartsms_name'             => 'Monthly Promotion or Newsletter-'.$value['smartemail_no'],
                    'sms_status'                => 'Schedule',
                    'sms_sender_name'           => $sms_sender_name,
                    'sms_message'               => $value['sms_message'],
                    'sms_start_date'            => date('Y-m-d'),
                    'sms_start_time'            => '11:00',
                    'sms_character'             => $this->get_character($value['sms_message']),
                    'sms_credit'                => $this->get_credit($value['sms_message']),
                    'smartemailid'              => $value['smartemailid'],
                    'smownerid'                 => $value['smownerid'],
                );

                list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$tab_name,$tab_name_index,$data,$userid);
                $this->common->set_log('Inert SmartSMS---> ',$crmid, $DocNo);

                $sql="INSERT INTO aicrm_smartsms_accountsrel (smartsmsid, accountid)
                select '".$crmid."',aicrm_account.accountid 
                from aicrm_smartemail_accountsrel
                inner join aicrm_account on aicrm_account.accountid = aicrm_smartemail_accountsrel.accountid
                inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_account.accountid
                where aicrm_smartemail_accountsrel.smartemailid = '".$smartemailid."'";
                $this->db->query($sql);

                $sql_s = "update aicrm_smartsms 
                       left join aicrm_config_sender_sms on aicrm_config_sender_sms.id = aicrm_smartsms.sms_sender_name
                       set aicrm_smartsms.sms_sender = aicrm_config_sender_sms.sms_sender , aicrm_smartsms.sms_url = aicrm_config_sender_sms.sms_url ,
                       aicrm_smartsms.sms_username = aicrm_config_sender_sms.sms_username , aicrm_smartsms.sms_password = aicrm_config_sender_sms.sms_password 
                       where  aicrm_smartsms.smartsmsid = '".$crmid."' "; 
                $this->db->query($sql_s);  
            }
    
        }
        
        return true;
    }

    private function get_character($text=''){
        $character = 0;

        if (!preg_match('/[^A-Za-z0-9]/', $text)){
            $character = mb_strlen($text, 'UTF-8');
        }else{
            $character = mb_strlen($text, 'UTF-8');
        }
        return $character;
    }

    private function get_credit($text=''){
        $character = mb_strlen($text, 'UTF-8');
        if (!preg_match('/[^A-Za-z0-9]/', $text)){
            if($character <= 160 && $character != 0){
                return 1;
            }else if($character == 0){
                return 0;
            }else{
                return ceil($character/157);
            }
        }else{
            if($character <= 70 && $character != 0){
                return 1;
            }else if($character == 0){
                return 0;
            }else{
                return ceil($character/67);
            }
        }
    }

    public function update_counting_gr_date(){
			
        $this->load->database();
        $sql = "UPDATE aicrm_goodsreceive a,
        (
            SELECT
                aicrm_goodsreceive.goodsreceiveid,
                DATEDIFF( NOW(), aicrm_crmentity.createdtime ) AS counting_gr_date 
            FROM
                aicrm_goodsreceive
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_goodsreceive.goodsreceiveid 
                AND aicrm_crmentity.deleted = 0 
            WHERE
            aicrm_goodsreceive.goods_receive_status = 'Intransit' 
            ) b
            
            SET a.counting_gr_date=b.counting_gr_date WHERE a.goodsreceiveid=b.goodsreceiveid";
        $query = $this->db->query($sql);


        $sql_gr = "SELECT
        GROUP_CONCAT(aicrm_goodsreceive.goodsreceiveid order by aicrm_goodsreceive.goodsreceiveid, ', ') goodsreceiveid
        
    FROM
        aicrm_goodsreceive
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_goodsreceive.goodsreceiveid 
        AND aicrm_crmentity.deleted = 0 
    WHERE
        aicrm_goodsreceive.goods_receive_status = 'Intransit'";
        $query_gr = $this->db->query($sql_gr);
        $data_gr = $query_gr->result_array();

        $data_parram = "goodsreceiveid : ".@$data_gr[0]['goodsreceiveid'];
        $file_name="Auto_Update_Counting_GR_Date".date('dmY').".txt";
        $FileName = "log/info/Goodsreceive/".$file_name;
        $FileHandle = fopen($FileName, 'a+') or die("can't open file");
        fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
        fclose($FileHandle);
    }

    public function update_counting_po_date(){
			
        $this->load->database();
        $sql = "UPDATE aicrm_purchasesorder a,
        (
            SELECT
                aicrm_purchasesorder.purchasesorderid,
                DATEDIFF( NOW(), aicrm_crmentity.createdtime ) AS counting_po_date 
            FROM
                aicrm_purchasesorder
                INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_purchasesorder.purchasesorderid 
                AND aicrm_crmentity.deleted = 0 
            WHERE
            aicrm_purchasesorder.po_status != 'Completed'
            ) b
            
            SET a.counting_po_date=b.counting_po_date WHERE a.purchasesorderid=b.purchasesorderid";
        $query = $this->db->query($sql);


        $sql_gr = "SELECT
        GROUP_CONCAT(aicrm_purchasesorder.purchasesorderid order by aicrm_purchasesorder.purchasesorderid, ', ') purchasesorderid
        
    FROM
        aicrm_purchasesorder
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_purchasesorder.purchasesorderid 
        AND aicrm_crmentity.deleted = 0 
    WHERE
        aicrm_purchasesorder.po_status != 'Completed'";
        $query_gr = $this->db->query($sql_gr);
        $data_gr = $query_gr->result_array();

        $data_parram = "purchasesorderid : ".@$data_gr[0]['purchasesorderid'];
        $file_name="Auto_Update_counting_po_date".date('dmY').".txt";
        $FileName = "log/info/Purchasesorder/".$file_name;
        $FileHandle = fopen($FileName, 'a+') or die("can't open file");
        fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
        fclose($FileHandle);
    }

    public function getSeriesSO()
    {
        global $serviceAPI;
        $this->load->library('curl');

        $rs = $this->curl->simple_get($serviceAPI.'SAPDI/GetSeriesSO?datestring='.date('Ymd'), []);
        $rs = json_decode($rs, true);
        
        if(is_array($rs) && !empty($rs)){
            foreach($rs as $row){
                $this->db->update('aicrm_quotation_type', [
                    'series_code' => $row['series'],
                    'series_name' => $row['seriesname'],
                    'series_indicator' => $row['indicator'],
                    'series_remark' => $row['remark']
                ], ['series_prefix' => $row['prefix']]);
            }
        }

        echo 'finished !!!';
    }

    public function projectorderSetFollowUp()
    {
        $this->db->query("CALL projectorder_set_followup()");
    }

    public function projectorderCreateSalesvisit()
    {
        $sql = $this->db->query("SELECT 
            aicrm_projects.projectsid,
            aicrm_projects.projects_no,
            aicrm_projects.projects_name,
            aicrm_projects.project_influencer,
            aicrm_projects.related_sales_person,
            aicrm_crmentity.smownerid
        FROM aicrm_projects
        INNER JOIN aicrm_projectscf ON aicrm_projectscf.projectsid = aicrm_projects.projectsid
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_projects.projectsid
        WHERE aicrm_crmentity.deleted = 0
        AND aicrm_projects.follow_up_project = 1");
        $projects = $sql->result_array();
        // alert($projects); exit();
        $salsesOrderList = [];
        foreach($projects as $project){
            $parentID = '';
            switch($project['project_influencer']) {
                case 'Owner/Developer':
                    $sql = $this->db->get_where('aicrm_inventoryowner', ['id'=>$project['projectsid']]);
                    $inven = $sql->row_array();
                    $parentID = @$inven['accountid'];
                    break;
                case 'Consultant':
                    $sql = $this->db->get_where('aicrm_inventoryconsultant', ['id'=>$project['projectsid']]);
                    $inven = $sql->row_array();
                    $parentID = @$inven['accountid'];
                    break;
                case 'Architecture':
                    $sql = $this->db->get_where('aicrm_inventoryarchitecture', ['id'=>$project['projectsid']]);
                    $inven = $sql->row_array();
                    $parentID = @$inven['accountid'];
                    break;
                case 'Construction':
                    $sql = $this->db->get_where('aicrm_inventoryconstruction', ['id'=>$project['projectsid']]);
                    $inven = $sql->row_array();
                    $parentID = @$inven['accountid'];
                    break;
                case 'Designer':
                    $sql = $this->db->get_where('aicrm_inventorydesigner', ['id'=>$project['projectsid']]);
                    $inven = $sql->row_array();
                    $parentID = @$inven['accountid'];
                    break;
                case 'Contractor':
                    $sql = $this->db->get_where('aicrm_inventorycontractor', ['id'=>$project['projectsid']]);
                    $inven = $sql->row_array();
                    $parentID = @$inven['accountid'];
                    break;
                case 'Sub Contractor':
                    $sql = $this->db->get_where('aicrm_inventorysubcontractor', ['id'=>$project['projectsid']]);
                    $inven = $sql->row_array();
                    $parentID = @$inven['accountid'];
                    break;
            }

            if($project['related_sales_person'] != ''){
                $assigns = explode(' |##| ', $project['related_sales_person']);

                foreach($assigns as $assignTo){
                    $sql = $this->db->query("SELECT id, email1 FROM aicrm_users WHERE CONCAT(first_name, ' ', last_name) = '".$assignTo."'", false);
                    $user = $sql->row_array();
                    
                    $name = 'ประสานงานภายในทีม '.$project['projectsid'].' '.$project['projects_name'].' '.$assignTo;
                    $salsesOrderList[] = [
                        'salesvisit_name' => $name,
                        'eventstatus' => 'Plan',
                        'activitytype' => 'ประสานงานภายในทีม',
                        'date_start' => date('Y-m-d', strtotime('next monday')),
                        'time_start' => '08:00',
                        'due_date' => date('Y-m-d', strtotime('next monday')),
                        'time_end' => '17:00',
                        'description' => $name,
                        'parentid' => $parentID,
                        'email' => $user['email1'],
                        'smownerid' => $user['id']
                    ];
                }
            } else {
                $sql = $this->db->query("SELECT CONCAT(first_name, ' ', last_name) AS 'name', email1 FROM aicrm_users WHERE id = '".$project['smownerid']."'", false);
                $user = $sql->row_array();

                $name = 'ประสานงานภายในทีม '.$project['projectsid'].' '.$project['projects_name'].' '.$user['name'];
                $salsesOrderList[] = [
                    'salesvisit_name' => $name,
                    'eventstatus' => 'Plan',
                    'activitytype' => 'ประสานงานภายในทีม',
                    'date_start' => date('Y-m-d', strtotime('next monday')),
                    'time_start' => '08:00',
                    'due_date' => date('Y-m-d', strtotime('next monday')),
                    'time_end' => '17:00',
                    'description' => $name,
                    'parentid' => $parentID,
                    'email' => $user['email1'],
                    'smownerid' => $project['smownerid']
                ];
            }
        }
        $logId = $this->UUID();
        $this->common->_filename="Auto_Generate_Salesvisit_From_Projectorder";
        $this->common->set_log("Start:(".$logId.")", "", "(".$logId.") : ".json_encode($salsesOrderList, JSON_UNESCAPED_UNICODE));
        $crmIDList = [];
        // alert($salsesOrderList); exit();
        echo 'Start generate sales visit from project order <br>';
        foreach($salsesOrderList as $i => $row){
            $this->load->config('config_module');
            $module = 'Calendar';
            $config = $this->config->item('module');
            $configModule = $config[$module];
            $tab_name = $configModule['tab_name'];
            $tab_name_index = $configModule['tab_name_index'];

            $data = [
                $row
            ];

            list($chk,$crmid,$DocNo,$name,$no)=$this->crmentity->Insert_Update($module, '', 'add', $tab_name, $tab_name_index, $data, '1');
            $crmIDList[] = $crmid;
            $this->db->update('aicrm_activity', ['semodule'=>'Projects'], ['activityid'=>$crmid]);
            // echo $crmid.' | ';
        }
        $this->common->set_log("Finished:(".$logId.")", "" , "(".$logId.") : ".json_encode($crmIDList, JSON_UNESCAPED_UNICODE));
        echo ' | Finished generate sales visit from project order';
    }

    public function generateSalesNoti()
    {
        $dbNoti = $this->load->database('noti', true);
        $connected = $dbNoti->initialize();

        // if($connected){
        //     echo 'connected';
        // } else {
        //     echo 'Disconnected';
        // }
        $projctId = 38;
        $nextMonday = date('Y-m-d', strtotime('next monday'));

        $query = "SELECT aicrm_activity.activityid,
        aicrm_activity.salesvisit_name,
        aicrm_activity.date_start,
        aicrm_activity.time_start,
        aicrm_activity.due_date,
        aicrm_activity.time_end,
        aicrm_crmentity.smownerid
        FROM aicrm_activity
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
        WHERE aicrm_crmentity.deleted = 0 
        AND aicrm_activity.date_start = '".$nextMonday."'
        AND aicrm_activity.semodule = 'Projects'";
        $sql = $this->db->query($query);
        $rs = $sql->result_array();
        // $nextMonday = date('Y-m-d', strtotime('tomorrow'));
        foreach($rs as $row){
            $sql = $dbNoti->query("SELECT * FROM ai_register WHERE projectid='".$projctId."' AND userid='".$row['smownerid']."' AND isActive='Y'");
            $rsRegist = $sql->row_array();
            if(!empty($rsRegist)){
                $registerId = $rsRegist['registerid'];

                $dbNoti->select_max('notificationid');
                $sql = $dbNoti->get('ai_notification');
                $rs = $sql->row_array();
                $maxId = $rs['notificationid'];
                $maxId = $maxId == '' ? 1:$maxId+1;

                $dbNoti->insert('ai_notification', [
                    'notificationid' => $maxId,
                    'projectid' => $projctId,
                    'crmid' => $row['activityid'],
                    'module' => 'Calendar',
                    'send_date' => $nextMonday,
                    'send_time' => '04:00:00',
                    'send_message' => $row['salesvisit_name'],
                    'noti_type' => 1,
                    'adddt' => date('Y-m-d H:i:s'),
                    'addempcd' => 1
                ]);

                $dbNoti->insert('ai_notification_detail', [
                    'notificationid' => $maxId,
                    'registerid' => $registerId,
                    'userid' => $row['smownerid'],
                    'send_flag' => 0,
                    'message' => $row['salesvisit_name']
                ]);

                // echo $dbNoti->last_query();
            }
        }
        echo 'finished';
    }

    public function updateAccountERP()
    {
        $this->load->database();
        $query = "SELECT aicrm_account.*, aicrm_crmentity.smownerid 
        FROM aicrm_account
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
        WHERE 1
            AND aicrm_crmentity.deleted = 0
            AND aicrm_account.passed_inspection != 0 
            AND aicrm_account.send_erp = 0 
        LIMIT 1";
        $sql = $this->db->query($query);
        $accountData = $sql->row_array();

        if(!empty($accountData)){
            $query = "SELECT rolename FROM aicrm_user2role 
            INNER JOIN aicrm_role ON aicrm_role.roleid = aicrm_user2role.roleid
            WHERE userid=".$accountData['smownerid'];
            $sql = $this->db->query($query);
            $role = $sql->row_array();
            $groupCode = explode(':', $role['rolename']);

            $account_payment_term = $accountData['account_payment_term'];
            $groupnum = explode(':', $account_payment_term);

            $query = "SELECT account_no FROM aicrm_account WHERE accountid=".$accountData['parentid'];
            $sql = $this->db->query($query);
            $parent = $sql->row_array();

            if($accountData['accounttype'] == "นิติบุคคล"){
                $cmpprivate = "C";
            }else{
                $cmpprivate = "I";
            }

            $jsonMaster = [
                [
                    'cardcode' => $accountData['account_no'],
                    'cardname' => $accountData['accountname'],
                    'cardfname' => empty($accountData['account_name_en']) ? '':$accountData['account_name_en'],
                    'groupcode' => empty(@$groupCode[0]) ? '':@$groupCode[0],
                    'currency' => 'THB',
                    'fedtaxid' => empty($accountData['idcardno']) ? '':$accountData['idcardno'],
                    'phone_1' => empty($accountData['mobile']) ? '':$accountData['mobile'],
                    'phone_2' => empty($accountData['mobile2']) ? '':$accountData['mobile2'],
                    'email' => $accountData['email1'],
                    'groupnum' => trim(@$groupnum[0]),
                    'vatgroup' => 'AR07',
                    'fathercard' => empty(@$parent['account_no']) ? '':@$parent['account_no'],
                    'remark' => empty($accountData['remark']) ? '':$accountData['remark'],
                    'cmpprivate' => $cmpprivate
                ]
            ];

            $sql = $this->db->query("SELECT * FROM tbm_country_region WHERE name='".$accountData['billingprovince']."'");
            $state1 = $sql->row_array();

            $sql = $this->db->query("SELECT * FROM tbm_country_region WHERE name='".$accountData['province']."'");
            $state2 = $sql->row_array();

            $tumbon1 = 'ตำบล';
            $ampuhr1 = 'อำเภอ';
            if(@$accountData['billingprovince'] == 'กรุงเทพมหานคร'){
                $tumbon1 = 'แขวง';
                $ampuhr1 = 'เขต';
            }

            $tumbon2 = 'ตำบล';
            $ampuhr2 = 'อำเภอ';
            if(@$accountData['province'] == 'กรุงเทพมหานคร'){
                $tumbon2 = 'แขวง';
                $ampuhr2 = 'เขต';
            }

            $address1 = '';
            if($accountData['billingaddressline1'] != '') $address1 .= 'เลขที่ '.$accountData['billingaddressline1'];
            if($accountData['billingaddressline'] != '') $address1 .= ' ห้องเลขที่/ชั้นที่ '.$accountData['billingaddressline'];
            if($accountData['billingvillage'] != '') $address1 .= ' อาคาร/หมู่บ้าน '.$accountData['billingvillage'];
            if($accountData['billingvillageno'] != '') $address1 .= ' หมู่ที่ '.$accountData['billingvillageno'];
            if($accountData['billinglane'] != '') $address1 .= ' ตรอก/ซอย '.$accountData['billinglane'];
            if($accountData['billingstreet'] != '') $address1 .= ' ถนน '.$accountData['billingstreet'];
            if($accountData['billingsubdistrict'] != '') $address1 .= ' '.$tumbon1.' '.$accountData['billingsubdistrict'];
            if($accountData['billingdistrict'] != '') $address1 .= ' '.$ampuhr1.' '.$accountData['billingdistrict'];
            if($accountData['billingprovince'] != '') $address1 .= ' จังหวัด '.$accountData['billingprovince'];
            if($accountData['billingpostalcode'] != '') $address1 .= ' รหัสไปรษณีย์ '.$accountData['billingpostalcode'];
            // if($accountData['billingscountry'] != '') $address1 .= ' Country '.$accountData['billingscountry'];
            if($address1 == '') $address1 = $accountData['billing_address'];

            $address2 = '';
            if($accountData['addressline1'] != '') $address2 .= 'เลขที่ '.$accountData['addressline1'];
            if($accountData['addressline'] != '') $address2 .= ' ห้องเลขที่/ชั้นที่ '.$accountData['addressline'];
            if($accountData['village'] != '') $address2 .= ' อาคาร/หมู่บ้าน '.$accountData['village'];
            if($accountData['villageno'] != '') $address2 .= ' หมู่ที่ '.$accountData['villageno'];
            if($accountData['lane'] != '') $address2 .= ' ตรอก/ซอย '.$accountData['lane'];
            if($accountData['street'] != '') $address2 .= ' ถนน '.$accountData['street'];
            if($accountData['subdistrict'] != '') $address2 .= ' '.$tumbon2.' '.$accountData['subdistrict'];
            if($accountData['district'] != '') $address2 .= ' '.$ampuhr2.' '.$accountData['district'];
            if($accountData['province'] != '') $address2 .= ' จังหวัด '.$accountData['province'];
            if($accountData['postalcode'] != '') $address2 .= ' รหัสไปรษณีย์ '.$accountData['postalcode'];
            // if($accountData['country'] != '') $address2 .= ' Country '.$accountData['country'];
            if($address2 == '') $address2 = $accountData['address'];

            $jsonMaster[0]['billaddress'] = $address1;

            $address1 = $this->subStringLen($address1);
            $address2 = $this->subStringLen($address2);

            $jsonAddress = [
                [
                    'cardcode' => empty($accountData['account_no']) ? '':$accountData['account_no'],
                    'addresstype' => 'B',
                    // 'titlename' => $accountData['prefix1'],
                    'name' => empty($accountData['accountname1']) ? '':$accountData['accountname1'],
                    'address_1' => @$address1[0],
                    'address_2' => @$address1[1],
                    'homeno' => empty($accountData['billingaddressline1']) ? '':$accountData['billingaddressline1'],
                    'floorno' => '',
                    'roomno' => empty($accountData['billingaddressline']) ? '':$accountData['billingaddressline'],
                    'building' => empty($accountData['billingvillage']) ? '':$accountData['billingvillage'],
                    'village' => '',
                    'block' => empty($accountData['billingvillageno']) ? '':$accountData['billingvillageno'],
                    'substreet' => empty($accountData['billinglane']) ? '':$accountData['billinglane'],
                    'street' => empty($accountData['billingstreet']) ? '':$accountData['billingstreet'],
                    'city' => empty($accountData['billingsubdistrict']) ? '':$accountData['billingsubdistrict'],
                    'county' => empty($accountData['billingdistrict']) ? '':$accountData['billingdistrict'],
                    'state' => $state1['code'],
                    'country' => @explode(' ', $accountData['billingscountry'])[0],
                    'fedtaxid' => empty($accountData['idcardno']) ? '':$accountData['idcardno'],
                    'branch' => empty($accountData['branch_code']) ? '':$accountData['branch_code'],
                    'zipcode' => empty($accountData['billingpostalcode']) ? '':$accountData['billingpostalcode'],
                ]
            ];
            global $serviceAPI;
            
            $logID = $this->logUniqID();

            $urlAddress = $serviceAPI.'SAPDI/Update/DIBPAdres?usercode=API&userpass=1234';
            $this->logInfo(['module'=>'Accounts', 'title'=>'BATCH_SAP_UPDATE_ADDRESS_DATA', 'logID'=>$logID, 'type'=>'Request', 'time'=>date('Y-m-d H:i:s'), 'url'=>$urlAddress, 'data'=>json_encode($jsonAddress, JSON_UNESCAPED_UNICODE)]);
            $rsAddress = $this->postApi($urlAddress, $jsonAddress);
            $this->logInfo(['module'=>'Accounts', 'title'=>'BATCH_SAP_UPDATE_ADDRESS_DATA', 'logID'=>$logID, 'type'=>'Response', 'time'=>date('Y-m-d H:i:s'), 'url'=>$urlAddress, 'data'=>$rsAddress]);

            if(@$rsAddress[0]['statussync_sap'] == '0'){ // Send Account Address Success
                $this->db->query("UPDATE aicrm_account SET send_erp=1 WHERE accountid=".$accountData['accountid']);
                alert($accountData['accountid']);
            }
        }        
    }

    public function stampLoginLogs()
    {
        $this->db->query("INSERT INTO ai_check_user_login_stamp_log
            SELECT
                NOW(),
                COUNT(*) AS total,
                SUM(CASE WHEN end_time > NOW() THEN 1 ELSE 0 END) AS active
            FROM ai_check_user_login
            WHERE DATE(start_time) = CURRENT_DATE() AND status = 0
        ");
    }

    public function logUniqID()
    {
        return uniqid().time().uniqid();
    }

    public function logInfo($data){
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

    private function postApi( $url, $param=[] ){
        $fields_string = json_encode($param);
        $json_url = $url;
    
        $json_string = $fields_string;
        $ch = curl_init( $json_url );
        $options = array(
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
            CURLOPT_POSTFIELDS => $json_string,
            CURLOPT_BUFFERSIZE => 1024,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_DNS_USE_GLOBAL_CACHE => false,
            CURLOPT_DNS_CACHE_TIMEOUT => 2
        );
    
        curl_setopt_array( $ch, $options );
        $result =  curl_exec($ch);
        $return = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result), true );
        return $return;
    }

    private function subStringLen($string, $len=100){
        $str_arr = explode(' ', $string);
        $arr = [];
        $count = 0;
        $str = [];
        foreach($str_arr as $i => $txt){
            $count += mb_strlen($txt);
            if($i != (count($str_arr)-1)){
                $count += 1; // spacebar
            }
            if($count > $len) {
                $arr[] = implode(' ', $str);
                $str = [];
                $count = 0;
                // continue;
            } 
        
            $str[] = $txt;
        
            if($i == (count($str_arr)-1)){
                $arr[] = implode(' ', $str);
                $str = [];
                $count = 0;
            }
        }
    
        return $arr;
    }
    
    private function UUID()
    {
        return uniqid().time();
    }

    public function import_attachment_product(){
        session_start();
        global $root_directory;
        $this->load->database();

        $file_path = $root_directory.'upload-images-product-comparision/images/';
        $products = [];

        /* =========================
        STEP 1 : Scan + หา productid
        ========================== */

        if (!is_dir($file_path)) {
            echo json_encode(["Type"=>"E","Message"=>"Directory not found"]);
            return;
        }

        $files = scandir($file_path);

        foreach ($files as $file) {

            if ($file === '.' || $file === '..') continue;

            $source = $file_path.$file;
            if (!is_file($source)) continue;

            // material_code ต้องตรงเป๊ะ (ไม่เอา _ - space)
            $material_code = pathinfo($file, PATHINFO_FILENAME);

            if (!preg_match('/^[0-9A-Za-z]+$/', $material_code)) {
                unlink($source);
                continue;
            }

            $q = $this->db->query("
                SELECT p.productid
                FROM aicrm_products p
                INNER JOIN aicrm_crmentity e ON e.crmid = p.productid
                WHERE e.deleted = 0
                AND p.material_code = ?
                LIMIT 1
            ", [$material_code]);

            if ($q->num_rows() == 0) {
                unlink($source);
                continue;
            }

            $products[] = [
                'productid' => $q->row()->productid,
                'filename'  => $file,
                'source'    => $source
            ];
        }

        if (empty($products)) {
            echo json_encode(["Type"=>"S","Message"=>"No product found"]);
            return;
        }

        /* =========================
        BEGIN TRANSACTION
        ========================== */
        $this->db->trans_begin();

        /* =========================
        STEP 2 : Reserve SEQ
        ========================== */
        $product_count = count($products);
        // echo 'Total products to import images: '.$product_count; exit;

        $row = $this->db->query("SELECT id FROM aicrm_crmentity_seq")->row_array();
        $seq_start = (int)$row['id'];
        $seq_end   = $seq_start + $product_count;
        // echo 'Reserving SEQ from '.$seq_start.' to '.$seq_end; exit;
        $this->db->query(
            "UPDATE aicrm_crmentity_seq SET id = ?",
            [$seq_end]
        );

        /* =========================
        STEP 3 : Remove old images
        ========================== */
        // alert($products); exit;
        foreach ($products as $p) {

            $oldImgs = $this->db->query("
                SELECT a.attachmentsid, a.path, a.name
                FROM aicrm_seattachmentsrel r
                JOIN aicrm_attachments a ON a.attachmentsid = r.attachmentsid
                JOIN aicrm_crmentity e ON e.crmid = a.attachmentsid
                WHERE r.crmid = ?
                AND e.setype = 'Products Image'
                AND e.deleted = 0
            ", [$p['productid']])->result_array();

            foreach ($oldImgs as $img) {

                // ลบไฟล์จริง
                @unlink(
                    $root_directory .
                    $img['path'] .
                    $img['attachmentsid'].'_'.$img['name']
                );

                // ลบ relation
                $this->db->delete('aicrm_seattachmentsrel', [
                    'attachmentsid' => $img['attachmentsid'],
                    'crmid' => $p['productid']
                ]);

                // ลบ attachment
                $this->db->delete('aicrm_attachments', [
                    'attachmentsid' => $img['attachmentsid']
                ]);

                // ลบ crmentity
                // $this->db->delete('aicrm_crmentity', [
                //     'crmid' => $img['attachmentsid']
                // ]);
            }
        }

        /* =========================
        STEP 4–6 : Insert new image
        ========================== */
        $seq = $seq_start;
        $now = date('Y-m-d H:i:s');

        $userid = $_SESSION["authenticated_user_id"];
        if($userid  == ''){
            $userid = 1;
        }
        // echo $userid; exit;
        // alert($products); exit;
        foreach ($products as $p) {

            $seq++;

            // vtiger path
            $filepath = $this->decideFilePath();
            // echo $filepath; exit;

            // ตรวจ mime type ตามไฟล์จริง
            $mime_type = $this->getImageMimeType($p['source']);
            // echo $mime_type; exit;

            // copy file → attachmentsid_filename
            $target = $root_directory.$filepath.$seq.'_'.$p['filename'];

            if (!copy($p['source'], $target)) {
                $this->db->trans_rollback();
                echo json_encode(["Type"=>"E","Message"=>"Copy file failed"]);
                return;
            }

            // relation
            $this->db->insert('aicrm_seattachmentsrel', [
                'crmid' => $p['productid'],
                'attachmentsid' => $seq
            ]);

            // attachments (ไม่ใส่ attachmentsid_ ใน name)
            $this->db->insert('aicrm_attachments', [
                'attachmentsid' => $seq,
                'name' => $p['filename'],
                'description' => '',
                'type' => $mime_type,   // auto ตามภาพ
                'path' => $filepath,
                'subject' => ''
            ]);

            // crmentity
            $this->db->insert('aicrm_crmentity', [
                'crmid' => $seq,
                'smcreatorid' => $userid,
                'smownerid' => $userid,
                'modifiedby' => $userid,
                'setype' => 'Products Image',
                'createdtime' => $now,
                'modifiedtime' => $now,
                'version' => 0,
                'deleted' => 0
            ]);

            // STEP สุดท้าย : ลบไฟล์ต้นทาง
            unlink($p['source']);
        }
        // echo 'All images imported.'; exit;
        /* =========================
        COMMIT / ROLLBACK
        ========================== */
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(["Type"=>"E","Message"=>"Import failed"]);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                "Type" => "S",
                "Message" => "Import Complete",
                "count" => count($products)
            ]);
        }
    }

    function decideFilePath()
    {
        global $root_directory;

        // base directory (absolute)
        $base = rtrim($root_directory, '/\\') . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR;

        $year  = date('Y');
        $month = date('F');
        $day   = date('j');

        if ($day <= 7)       $week = 'week1';
        elseif ($day <= 14)  $week = 'week2';
        elseif ($day <= 21)  $week = 'week3';
        elseif ($day <= 28)  $week = 'week4';
        else                 $week = 'week5';

        $full_path =
            $base .
            $year . DIRECTORY_SEPARATOR .
            $month . DIRECTORY_SEPARATOR .
            $week . DIRECTORY_SEPARATOR;

        // สร้าง directory แบบ recursive
        if (!is_dir($full_path)) {
            if (!mkdir($full_path, 0777, true)) {
                die(json_encode([
                    "Type" => "E",
                    "Message" => "Cannot create directory",
                    "path" => $full_path
                ]));
            }
        }

        // return path สำหรับเก็บใน DB (relative)
        return 'storage/'.$year.'/'.$month.'/'.$week.'/';
    }


    function getImageMimeType($file)
    {
        // 1) ถ้ามี fileinfo ใช้ก่อน
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $type = finfo_file($finfo, $file);
                finfo_close($finfo);
                if ($type !== false) {
                    return $type;
                }
            }
        }

        // 2) fallback จาก extension
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            case 'webp':
                return 'image/webp';
            default:
                return 'application/octet-stream';
        }
    }



    public function import_attachment_product_(){
        global $root_directory;
        $this->load->database();

        $file_path = $root_directory.'upload-images-product-comparision/images/';

        $result_productids = [];

        if (!is_dir($file_path)) {
            echo json_encode([
                "Type" => "E",
                "Message" => "Directory not found",
                "data" => []
            ]);
            return;
        }

        $files = scandir($file_path);

        foreach ($files as $file) {

            if ($file === '.' || $file === '..') continue;

            $full_path = $file_path.$file;

            if (!is_file($full_path)) continue;

            // ชื่อไฟล์ต้องตรงเป๊ะ (ไม่รวม extension)
            $material_code = pathinfo($file, PATHINFO_FILENAME);

            // ถ้ามีอักขระพิเศษ เช่น _ - space → ตัดทิ้งทันที
            if (!preg_match('/^[0-9A-Za-z]+$/', $material_code)) {
                unlink($full_path);
                continue;
            }

            $query = $this->db->query("
                SELECT aicrm_products.productid
                FROM aicrm_products
                INNER JOIN aicrm_productcf 
                    ON aicrm_products.productid = aicrm_productcf.productid
                INNER JOIN aicrm_crmentity 
                    ON aicrm_crmentity.crmid = aicrm_products.productid
                WHERE aicrm_crmentity.deleted = 0
                AND aicrm_products.material_code = ?
                LIMIT 1
            ", [$material_code]);

            if ($query->num_rows() > 0) {
                $row = $query->row_array();
                $result_productids[] = [
                    "productid" => $row['productid'],
                    "material_code" => $material_code,
                    "filename" => $file
                ];
            } else {
                unlink($full_path);
            }
        }

        echo json_encode([
            "Type" => "S",
            "Message" => "Import Complete",
            "data" => $result_productids
        ]);
    }


}