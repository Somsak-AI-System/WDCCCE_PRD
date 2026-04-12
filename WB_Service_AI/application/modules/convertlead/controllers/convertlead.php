<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Convertlead extends REST_Controller
{
  
	private $crmid;
  function __construct()
  {
    parent::__construct();
    $this->load->library('memcached_library');
	$this->load->library('crmentity');
    $this->load->database();
	$this->load->library("common");
	$this->load->model("convertlead_model");
	$this->_limit = 100;
	$this->_module = "Leads";
	$this->_format = "array";

	$this->_return = array(
			'Type' => "S",
			'Message' => "Insert Complete",
			'cache_time' => date("Y-m-d H:i:s"),
			'data' => array(),
	);

	$this->_data= array(
			"status" => false,
			"message" =>"",
			"time" => date("Y-m-d H:i:s"),
			"data" => array("data" => ""),
	);
  }


  	public function check_username_get()
	{
		$a_param =  $this->input->get();
		$a_data=$this->register_model->check_username($a_param);

		if(!isset($a_data['number_username'])){
			$a_data["status"] = false;
			$a_data["message"] = "Cann't Found Data";

		}else{

			if($a_data['number_username'] > 0){
				/* Username is exist already */
				$a_data["status"] = false;
				$a_data["message"] = "Number of username : ".$a_data['number_username']." number";
			}else{
				/* Username can used */
				$a_data["status"] = true;
				$a_data["message"] = "Can used username : ".$a_param['username'];
			}
			
		}

		$this->return_data($a_data);
	}

	public function check_username_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);

		$a_data=$this->register_model->check_username($a_param['condition']);

		if(!isset($a_data['number_username'])){
			$a_data["status"] = false;
			$a_data["message"] = "Cann't Found Data";

		}else{

			if($a_data['number_username'] > 0){
				/* Username is exist already */
				$a_data["status"] = false;
				$a_data["message"] = "Number of username : ".$a_data['number_username']." number";
			}else{
				/* Username can used */
				$a_data["status"] = true;
				$a_data["message"] = "Can used username : ".$a_param['condition']['username'];
			}
		}

		$this->return_data($a_data);
	}

	private function check_username($a_params=array())
	{
		
		if(empty($a_params["username"])){
			$a_data["status"] = false;
			$a_data["message"] = "Username is null";
			return array_merge($this->_data,$a_data);
		}
	}

	public function return_data($a_data)
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["message"];
			$a_return["cachetime"] = @$a_data["time"];
			//$a_return["data"] = @$a_data["data"]["data"];
		
			if ($format!="json" && $format!="xml"  ) {
					$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
				$this->response(array('error' => 'Couldn\'t find any Register!'), 404);
		}
	}

	public function insert_content_post(){

		$this->common->_filename= "Insert_Leads";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Leads==>',$url,$a_request);
		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Insert Leads==>',$a_request,$response_data);
	  
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
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
		$accountname = isset($a_request['data'][0]['accountname']) ? $a_request['data'][0]['accountname'] : "";
		$mobile_convert = isset($a_request['mobile']) ? $a_request['mobile'] : "";
		$newcontact = isset($a_request['data'][0]['newcontact']) ? $a_request['data'][0]['newcontact'] : 'false';
		$contactname = isset($a_request['data'][0]['contactname']) ? $a_request['data'][0]['contactname'] : "";
		$smownerid = isset($a_request['data'][0]['smownerid']) ? $a_request['data'][0]['smownerid'] : "";

		if($smownerid==""){
			$smownerid = $userid;
		}

	  	$date_entered = date('Y-m-d H:i:s');
		$date_modified = date('Y-m-d H:i:s');

	  	if($action == 'convert' and $module=="Leads"){
	  			// alert($a_request); exit;
	  			//list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data,$userid);
				
  				//Accounts
				$acc_query = "select aicrm_account.accountid from aicrm_account inner join aicrm_crmentity on aicrm_account.accountid = aicrm_crmentity.crmid where aicrm_crmentity.deleted=0 and aicrm_account.accountname = ?";

				$acc_res = $this->db->query($acc_query, array($accountname));
				$acc_rows = $acc_res->result_array();
				
				// alert($acc_rows); exit;
				
				if(!empty($acc_rows)){
					//echo 66666; exit;
					$accountid = $acc_rows[0]['accountid'];

					$this->getRelatedNotesAttachments($crmid,$accountid);
					$this->getRelateddeal($crmid,$accountid);
					$this->getRelatedActivity($crmid,$accountid);
					$this->getRelatedproducts($crmid,$accountid);
					$this->getRelatedHelpDesk($crmid,$accountid);
					$this->getRelatedVoucher($crmid,$accountid);
					$this->getRelatedtag($crmid,$accountid,$userid);
				}else{
					
					$accountid = $this->getUniqueID("aicrm_crmentity");
					//Saving Account - starts
					$sql_crmentity = "insert into aicrm_crmentity(crmid,smcreatorid,smownerid,setype,presence,createdtime,modifiedtime,deleted) values(?,?,?,?,?,?,?,?)";
					$sql_params = array($accountid, $userid, $smownerid, 'Accounts', 1, $date_entered, $date_modified, 0);
					$this->db->query($sql_crmentity, $sql_params);

				    $account_no = $this->autocd_acc();
					//echo $account_no; exit;	
					//Getting the custom aicrm_field values from leads and inserting into Accounts if the aicrm_field is mapped - Jaguar
					$col_val= $this->getInsertValues("Accounts",$accountid,$crmid);

					// alert(json_encode($col_val)); exit;

					$insert_columns = $col_val['columns'];
					$insert_columns[] = "accountid";
					$insert_values = $col_val['values'];
					$insert_values[] = $accountid;
							
					for($aa=0;$aa<count($insert_columns);$aa++){
						if(substr($insert_columns[$aa],0,3)=="cf_"){
						}else{
							$insert_columns_ok[]=$insert_columns[$aa];
							$insert_values_ok[]=$insert_values[$aa];
						}
					}
					$insert_columns_ok[]="account_no";		
					$insert_values_ok[]=$account_no;
					$insert_columns_ok[]="accountname";		
					$insert_values_ok[]=@$accountname;
					$insert_columns_ok[]="register_date";		
					$insert_values_ok[]=date('Y-m-d');
					// alert(json_encode($insert_columns_ok)); 
					// alert(json_encode($insert_values_ok)); exit;
					
					$insert_val_str_ok = $this->generateQuestionMarks($insert_values_ok);
					//echo $insert_val_str_ok; exit;
					
					$sql_insert_account = "INSERT INTO aicrm_account (". implode(",",$insert_columns_ok) .") VALUES (".$insert_val_str_ok.")";
					//echo $sql_insert_account; exit;
					$this->db->query($sql_insert_account, $insert_values_ok);
					

					$sql_insert_lead2acc = "INSERT INTO aicrm_convert_lead2acc (accountid,leadid,createdate) VALUES (?,?,?)";
					// Modified by Minnie -- END
					$lead2acc_params = array($accountid, $crmid,$date_entered );
					$this->db->query($sql_insert_lead2acc, $lead2acc_params);

					$sql_insert_accountbillads = "INSERT INTO aicrm_accountbillads (accountaddressid) VALUES (?)";
					$billads_params = array($accountid);
					$this->db->query($sql_insert_accountbillads, $billads_params);
					
					$sql_insert_accountshipads = "INSERT INTO aicrm_accountshipads (accountaddressid) VALUES (?)";
					$shipads_params = array($accountid);
					$this->db->query($sql_insert_accountshipads, $shipads_params);
						
					$insert_columns_ok=array();
					$insert_values_ok=array();
					for($aa=0;$aa<count($insert_columns);$aa++){
						if(substr($insert_columns[$aa],0,3)=="cf_"){
							$insert_columns_ok[]=$insert_columns[$aa];
							$insert_values_ok[]=$insert_values[$aa];
						}
					}
					$insert_columns_ok[] = "accountid";
					$insert_values_ok[] = $accountid;
					
					$insert_val_str = $this->generateQuestionMarks($insert_values_ok);
					
					$sql_insert_accountcustomfield = "INSERT INTO aicrm_accountscf (". implode(",",$insert_columns_ok) .") VALUES (".$insert_val_str.")";
					$this->db->query($sql_insert_accountcustomfield, $insert_values_ok);
					//Saving Account - ends

					$this->getRelatedNotesAttachments($crmid,$accountid); 
					$this->getRelateddeal($crmid,$accountid);
					$this->getRelatedActivity($crmid,$accountid);
					$this->getRelatedproducts($crmid,$accountid);
					$this->getRelatedHelpDesk($crmid,$accountid);
					$this->getRelatedVoucher($crmid,$accountid);
					$this->getRelatedtag($crmid,$accountid,$userid);
					
				}//Accounts
				//echo $accountid; exit;

 			//Contacts
			if($newcontact=='true'){

				$date_entered = date('Y-m-d H:i:s');
				$date_modified = date('Y-m-d H:i:s');
			  
				$contactid = $this->getUniqueID("aicrm_crmentity");
			  	
				$sql_crmentity = "insert into aicrm_crmentity(crmid,smcreatorid,smownerid,setype,presence,deleted,createdtime,modifiedtime,description) values(?,?,?,?,?,?,?,?,?)";
				$sql_params = array($contactid, $userid, $smownerid, 'Contacts', 0, 0, $date_entered, $date_modified, $row['description']);
				$this->db->query($sql_crmentity, $sql_params);  	
			  	
			    $contact_no = $this->autocd_Contacts();

				// END					
				
				$col_val= $this->getInsertValues("Contacts",$contactid,$crmid);

				$contact_columns = $col_val['columns'];
				$contact_columns[] = "contactid";
				$contact_values = $col_val['values'];
				$contact_values[] = $contactid;
				
				for($aa=0;$aa<count($contact_columns);$aa++){
					if(substr($contact_columns[$aa],0,3)=="cf_"){
					}else{
						$contact_columns_ok[]=$contact_columns[$aa];
						$contact_values_ok[]=$contact_values[$aa];
					}
				}
				$contact_columns_ok[] = "contact_no";
				$contact_values_ok[] = $contact_no;
				$contact_columns_ok[] = "accountid";
				$contact_values_ok[] = @$accountid;
				$contact_columns_ok[] = "contactname";
				$contact_values_ok[] = @$contactname;

				$insert_val_str_ok = $this->generateQuestionMarks($contact_values_ok);
				// $sql_insert_deal = "INSERT INTO aicrm_deal (". implode(",",$deal_columns_ok) .") VALUES (".$insert_val_str_ok.")";
				$sql_insert_contact = "INSERT INTO aicrm_contactdetails (". implode(",",$contact_columns_ok) .") VALUES (".$insert_val_str_ok.")";
				$this->db->query($sql_insert_contact, $contact_values_ok);
// echo $this->db->last_query(); exit();
				$contact_columns_ok=array();
				$contact_values_ok=array();
				for($aa=0;$aa<count($contact_columns);$aa++){
					if(substr($contact_columns[$aa],0,3)=="cf_"){
						$contact_columns_ok[]=$contact_columns[$aa];
						$contact_values_ok[]=$contact_values[$aa];
					}
				}

				$contact_columns_ok[] = "contactid";
				$contact_values_ok[] = $contactid;
				$insert_val_str = $this->generateQuestionMarks($contact_values_ok);
				
				$sql_insert_contactcustomfield = "INSERT INTO aicrm_contactscf (". implode(",",$contact_columns_ok) .") VALUES (".$insert_val_str.")";
				$this->db->query($sql_insert_contactcustomfield, $contact_values_ok);
				

				$this->getRelatedNotesAttachments($crmid,$contactid); 
				//Deal
			} //Contacts

				$sql_insert_account = "update aicrm_leaddetails set accountid='".$accountid."' where leadid='".$crmid."' ";
				// ,dealid='".$dealid."'
				$this->db->query($sql_insert_account, array());
				
				//echo 5555; exit;
				//$socialid = @$focus->column_fields['socialid'];
				if($crmid != '' && $accountid != ''){
					$s_update = "Update message_customer set module = 'Accounts' , parentid = '".$accountid."' where parentid = '".$crmid."' ";
					$this->db->query($s_update, array());
				}

				if($accountid != ''){
					// || $dealid != ''

					$sql_update_converted = "UPDATE aicrm_leaddetails SET convert_lead = 1, converted = 1, convert_date = '".date('Y-m-d h:i:s')."' where leadid=?";
					// ,convert_flag = 1
					$this->db->query($sql_update_converted, array($crmid)); 
					$chk="0";
					// alert($this->db->last_query());
				}

				if($mobile_convert!=""){
					// $a_branch = $a_request['branch'];
					$a_birthdate = $a_request['birthdate'];
					$a_updatebranch = "Update aicrm_account set  birthdate = '".$a_birthdate."' where accountid = '".$accountid."' ";
					// branch = '".$a_branch."' ,
					$this->db->query($a_updatebranch, array());
					// alert($a_updatebranch);

				}


	  			if($chk=="0"){
	  				$a_return["Message"] = ($action=="convert")?"Convert Complete" : "Update Complete";
	  				
	  				$a_list = $this->convertlead_model->get_list_account($accountid);
					$a_data = $a_list['result']['data'];

					if($mobile_convert!=""){
						$a_data = array(
							"crmid"=>$crmid,
							"no"=>'',
							"name"=>''
						);
					  $a_return["data"] = $a_data;
					}else{
						$a_return["data"] = $a_data;
					}
	  				

	  			}else{
	  				$a_return  =  array(
	  						'Type' => 'E',
	  						'Message' => 'Unable to complete transaction',
	  				);
	  			}
	  	}else{//echo "ddd";
	  		$a_return  =  array(
	  				'Type' => 'E',
	  				'Message' =>  'Invalid Request!',
	  		);
	  	}
	  	return array_merge($this->_return,$a_return);
  	}

  	private function getUniqueID($table=NULL){
  		
  		$sql = 'select id from aicrm_crmentity_seq';
  		$seq_num = $this->db->query($sql);
  		$num = $seq_num->result_array();
 
  		$seqid = $num[0]['id'] + 1;

  		$s_update = 'update aicrm_crmentity_seq set id = "'.$seqid.'" ';
  		$this->db->query($s_update);
  		
  		return $seqid;
   	}

  	private function getInsertValues($type,$type_id,$crmid)
	{
		//global $id,$adb,$log;
		//$log->debug("Entering getInsertValues(".$type.",".$type_id.") method ...");
		$sql_convert_lead="select * from aicrm_convertleadmapping ";
		$convert_result = $this->db->query($sql_convert_lead);

		//$noofrows = $convert_result->num_rows();
		$noofrows = $convert_result->result_array();

		$value_cf_array = Array();

		// alert($noofrows); exit;
		foreach ($noofrows as $key => $value) {
			//echo 1;
			$flag="false";
			$lead_id=$value['leadfid']; //$adb->query_result($convert_result,$i,"leadfid");  
			$account_id_val=$value['accountfid']; //$adb->query_result($convert_result,$i,"accountfid");
			// $deal_id_val= $value['dealfid']; //$adb->query_result($convert_result,$i,"dealfid");
			$contact_id_val= $value['contactfid']; //$adb->query_result($convert_result,$i,"contactfid");

			$sql_leads_column="select aicrm_field.uitype,aicrm_field.fieldid,aicrm_field.columnname,aicrm_field.tablename from aicrm_field,aicrm_tab where aicrm_field.tabid=aicrm_tab.tabid and generatedtype=2 and aicrm_tab.name='Leads' and fieldid=? and aicrm_field.presence in (0,2)";
			$lead_column_result = $this->db->query($sql_leads_column, array($lead_id));

			//$leads_no_rows = $lead_column_result->num_rows();
			$leads_no_rows = $lead_column_result->result_array();
			// alert($this->db->last_query());

		 
			if(!empty($leads_no_rows))
			{	
				$lead_column_name = $leads_no_rows['0']['columnname'];
				$tablename = $leads_no_rows['0']['tablename'];
				$lead_uitype = $leads_no_rows['0']['uitype'];
				//echo $lead_uitype; echo "<br>";
				if($tablename=="aicrm_leadaddress"){
					$sql_leads_val="select $lead_column_name from ".$tablename." where leadaddressid=?"; //custom aicrm_field value for lead	
				}else{
					$sql_leads_val="select $lead_column_name from ".$tablename." where leadid=?"; //custom aicrm_field value for lead	
				}
				
				$lead_val_result = $this->db->query($sql_leads_val,array($crmid));
				$a_lead_val_result = $lead_val_result->result_array();
				//$lead_value= $this->db->query($lead_val_result,0,$lead_column_name);
				// alert($a_lead_val_result);
			}

			$sql_type="select aicrm_field.fieldid,aicrm_field.uitype,aicrm_field.columnname from aicrm_field,aicrm_tab where aicrm_field.tabid=aicrm_tab.tabid and generatedtype=2 and aicrm_field.presence in (0,2) and aicrm_tab.name="; 

			$params = array();

			if($type=="Accounts")
			{
				if($account_id_val!="" && $account_id_val!=0)	
				{
					$flag="true";
					$sql_type.="'Accounts' and fieldid=?";
					array_push($params, $account_id_val);
				}
			}
			else if($type == "Contacts")
			{
				if($contact_id_val!="" && $contact_id_val!=0)
	            {
					$flag="true";
					$sql_type.="'Contacts' and fieldid=?";
					array_push($params, $contact_id_val);	

	            }
			}
			// else if($type == "Deal")
			// {
			// 	if($deal_id_val!="" && $deal_id_val!=0)
	  //           {
			// 		$flag="true";
			// 		$sql_type.="'Deal' and fieldid=?";
			// 		array_push($params, $deal_id_val);		
	  //           }
			// }



			
			if($flag=="true")
			{ 
				$type_result=$this->db->query($sql_type, $params);
				//To construct the cf array
	            $colname = $type_result->result_array();
				$type_insert_column[] = $colname['0']['columnname'];
				
				$columnname = $colname['0']['columnname'];

				$type_uitype = $colname['0']['uitype'];
				//echo $type_uitype; echo "<br>";
				//To construct the cf array

				$ins_val = $a_lead_val_result[0][$lead_column_name];//$this->db->query($lead_val_result,0,$lead_column_name);
				
				//This array is used to store the tablename as the key and the value for that table in the custom field of the uitype only for 15 and 33(Multiselect cf)...
				if($lead_uitype == 33 || $lead_uitype == 15) {
	                $lead_val_arr[$columnname] = $lead_column_name;
	                $value_cf_array[$columnname]=$ins_val;	
				}
				
				$insert_value[] = $ins_val;
			}


		}

		$values = array ('columns'=>$type_insert_column,'values'=>$insert_value);
		// alert($values);exit;
		return $values;	
	}

	private function getRelatedNotesAttachments($id,$related_id)
	{
		$sql_lead_notes	="select * from aicrm_senotesrel where crmid=?";
		$lead_notes_result = $this->db->query($sql_lead_notes, array($id));
		$noofrows = $lead_notes_result->result_array();
		
		foreach ($noofrows as $key => $value) {
			$sql_insert_notes="insert into aicrm_senotesrel(crmid,notesid) values (?,?)";
			$this->db->query($sql_insert_notes, array($related_id, $value['notesid']));
		}

	    $sql_lead_attachment="select * from aicrm_seattachmentsrel where crmid=?";
		$lead_attachment_result = $this->db->query($sql_lead_attachment, array($id));
		$noofrows = $lead_attachment_result->result_array();

		foreach ($noofrows as $key => $value) {
			$sql_insert_attachment="insert into aicrm_seattachmentsrel(crmid,attachmentsid) values (?,?)";                        
	        $this->db->query($sql_insert_attachment, array($related_id, $value['attachmentsid']));
		}
	}

	private function getRelatedvisit($id,$accountid,$contactid)
	{
		$sql_update_deal="update aicrm_activity set parentid = ?,contactid = ? where parentid = ?";                     
	    $this->db->query($sql_update_deal, array($accountid,$contactid,$id));
	}

	private function getRelateddeal($id,$related_id)
	{
		$sql_update_deal="update aicrm_deal set parentid = ? where parentid = ?";                        
	    $this->db->query($sql_update_deal, array($related_id, $id));
	}

	private function getRelatedActivity($id,$related_id)
	{
		$sql_update_activity="update aicrm_activity set parentid = ? where parentid = ?";                        
	    $this->db->query($sql_update_activity, array($related_id, $id));
	}

	private function getRelatedproducts($id,$related_id)
	{
		$sql_seproductsrel	="select * from aicrm_seproductsrel where crmid=?";
		$lead_productsrel_result = $this->db->query($sql_seproductsrel, array($id));
		$noofrows = $lead_productsrel_result->result_array();

		foreach ($noofrows as $key => $value) {
			$sql_insert_productsrel="insert into aicrm_seproductsrel(crmid,productid,setype) values (?,?,?)";
			$this->db->query($sql_insert_productsrel, array($related_id,$value['productid'],'Accounts'));
		}
	}

	private function getRelatedHelpDesk($id,$related_id)
	{
		$sql_update_case = "update aicrm_troubletickets set parentid = ? where parentid = ?";                        
	    $this->db->query($sql_update_case, array($related_id, $id));
	}
	
	private function getRelatedVoucher($id,$related_id){
		$sql_update_voucher = "update aicrm_voucher set accountid = ? where leadid = ?";                        
	    $this->db->query($sql_update_voucher, array($related_id, $id));
	}

	private function getRelatedtag($id,$related_id,$userid){
	    $sql_sevoucher	="select * from aicrm_freetagged_objects where object_id=?";
		$lead_voucher_result = $this->db->query($sql_sevoucher, array($id));
		$noofrows = $lead_voucher_result->result_array();

		foreach ($noofrows as $key => $value) {
			$sql_insert_voucehrrel="insert into aicrm_freetagged_objects(tag_id,tagger_id,object_id,tagged_on,module) values (?,?,?,?,?)";
			$this->db->query($sql_insert_voucehrrel, array($value['tag_id'],$userid,$related_id,date("Y-m-d H:i:s"),'Accounts'));
		}
	}

	private function autocd_acc($module="Accounts",$num_running="6",$pre="ACC"){

	  $yy=substr((date("Y")+543), -2) ;
	  $prefix =$pre.$yy.date('m');

	  $sql="
	  SELECT running
	  FROM ai_running_doc
	  where 1
	  and module='".$module."'
	  and prefix='".$prefix."'
	  ";
	  $query = $this->db->query($sql);
	  $data = $query->result_array();
	  if(count($data)>0){
	    $running = $data[0]['running'];
	    $running=$running+1;
	    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' ";
	  }else{
	    $running=1;
	    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
	  }

	  $this->db->query($sql);
	  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
	  return $cd;
	}
	
	private function autocd_Deal($module="Deal",$num_running="4",$pre="DEL"){

	  $yy=substr((date("Y")+543), -2);
	  $prefix =$pre.$yy;

	  $sql="
	  SELECT running
	  FROM ai_running_doc
	  where 1
	  and module='".$module."'
	  and prefix='".$prefix."'
	  ";
	  $query = $this->db->query($sql);
	  $data = $query->result_array();
	  
	  if(count($data)>0){
	    $running = $data[0]['running'];
	    $running=$running+1;
	    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' ";
	  }else{
	    $running=1;
	    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
	  }

	  $this->db->query($sql);
	  $cd = $prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
	  return $cd;
	}


	private function autocd_Contacts($module="Contacts",$num_running="4",$pre="CON"){

	  $yy=substr((date("Y")+543), -2);
	  $prefix =$pre.$yy;

	  $sql="
	  SELECT running
	  FROM ai_running_doc
	  where 1
	  and module='".$module."'
	  and prefix='".$prefix."'
	  ";
	  $query = $this->db->query($sql);
	  $data = $query->result_array();
	  
	  if(count($data)>0){
	    $running = $data[0]['running'];
	    $running=$running+1;
	    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' ";
	  }else{
	    $running=1;
	    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
	  }

	  $this->db->query($sql);
	  $cd = $prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
	  return $cd;
	}


	private function generateQuestionMarks($items_list) {

		foreach ($items_list as $key => $val) {
			if($key == 0){
				$value = '?';
			}else{
				$value .= ',?';
			}
		}
		
		return $value;
	}

	public function convert_lead_post(){

		$this->common->_filename= "Convert_Leads";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;

	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Convert Leads==>',$url,$a_request);

	  	$crmid= @$a_request['crmid'];
	  	if($crmid!=""){
	  	
		  	$sql= "select firstname ,lastname  ,birthdate  from aicrm_leaddetails
		  	where aicrm_leaddetails.leadid='".$crmid."' ";
		  	// ,branch
		// alert($sql);exit;
		  	$query = $this->db->query($sql);
		  	$data = $query->row_array();

		  	// alert($data);exit;
		  	
		  	if(!empty($data)){
		  		// $a_request['accountname']=$data['firstname']." ".$data['lastname'];
		  		// $a_request['accountname']=$a_request['data']['accountname'];
		  		// $a_request['branch']=@$data['branch'];
		  		$a_request['birthdate']=$data['birthdate'];
		  		$a_request['mobile']="true";
		  	}
		}

		$response_data = $this->get_insert_data($a_request);	
	  	$this->common->set_log('After Convert Leads==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}


	public function form_convert_get()
	{
		$a_param =  $this->input->get();
		$a_data =$this->get_cache($a_param);
		$module = $a_param['module'];
		$this->return_form($a_data,$module,$a_param);
	}

	public function form_convert_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$module = $a_param['module'];
		$a_data =$this->get_cache($a_param);


		$this->return_form($a_data,$module,$a_param);
	}

	private function get_cache($a_params=array())
	{
		$this->load->library('managecached_library');
		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';

		$action = @$a_params['action'];
		$module = @$a_params['module'];
		$crmid = @$a_params['crmid'];
		$userid = @$a_params['userid'];
		if($action=="convert" && $crmid!=""){

			$fieldid = array(
				'11022',
			);
			// '11248','12414',
			$fieldid = implode(',',$fieldid);

			$sql = "select DISTINCT columnname,tablename,fieldlabel,fieldname,uitype,generatedtype,typeofdata,block,readonly,maximumlength
			from aicrm_field where fieldid in (".$fieldid.") ORDER BY uitype desc";
			$query = $this->db->query($sql);
			$data = $query->result_array();
			$blockname = "Convert Lead Information";
			// alert($data);exit;
			$field = $this->crmentity->Get_field($module,$data,$blockname,$crmid,$userid,"");
			unset($field[0]);
			$a_form[] = $field[1];

		}
		// alert($a_form);exit;

		if(!empty($a_form)){
			$a_form[0]['form'][0]['check_value'] = "no";
			$a_form[0]['form'][0]['error_message'] = "";
			unset($a_form[0]['form'][0]['tablename']);
			unset($a_form[0]['form'][0]['format_date']);
			unset($a_form[0]['form'][0]['format_date']);
			unset($a_form[0]['form'][0]['format_date']);
			unset($a_form[0]['form'][0]['format_date']);
			unset($a_form[0]['form'][0]['is_array']);
			unset($a_form[0]['form'][0]['is_phone']);
			unset($a_form[0]['form'][0]['is_account']);
			unset($a_form[0]['form'][0]['is_product']);
			unset($a_form[0]['form'][0]['is_checkin']);
			unset($a_form[0]['form'][0]['link']);
			unset($a_form[0]['form'][0]['no']);
			unset($a_form[0]['form'][0]['name']);
			unset($a_form[0]['form'][0]['relate_field_up']);
			unset($a_form[0]['form'][0]['relate_field_down']);
			unset($a_form[0]['form'][0]['ref_uitype']);

			$sql = $this->db->get_where('aicrm_leaddetails', ['leadid'=>$crmid]);
			$rs = $sql->row_array();

			$acc_field  = array("columnname"=>"accountname",
				"fieldlabel"=>"ชื่อบัญชีลูกค้า",
				"uitype"=>"1",
				"typeofdata"=>"V~M",
				"type"=>"varchar(100)",
				"keyboard_type"=>"default",
				"value_default"=>"",
				"value_name"=>"",

				"value"=>$rs['company']==null ? '':$rs['company'],
				"check_value"=>"yes",
				"error_message"=>"ชื่อบัญชีลูกค้า cannot be empty",
				"readonly"=>"0",
				"maximumlength"=>"100",
				);
			$new_field  = array("columnname"=>"newcontact",
				"fieldlabel"=>"สร้างผู้ติดต่อใหม่",
				"uitype"=>"56",
				"typeofdata"=>"V~M",
				"type"=>"checkbox",
				"keyboard_type"=>"default",
				"value_default"=>"false",
				"value_name"=>"",
				"value"=>"",
				"check_value"=>"no",
				"error_message"=>"",
				"readonly"=>"0",
				"maximumlength"=>"0",
				);
			$con_field  = array("columnname"=>"contactname",
				"fieldlabel"=>"ชื่อผู้ติดต่อ",
				"uitype"=>"1",
				"typeofdata"=>"V~M",
				"type"=>"varchar(100)",
				"keyboard_type"=>"default",
				"value_default"=>"",
				"value_name"=>"",

				"value"=>$rs['leadname']==null ? '':$rs['leadname'],
				"check_value"=>"no",
				"error_message"=>"",
				"readonly"=>$rs['leadname']==null ? '0':'1',
				"maximumlength"=>"100",
				);

		
			$form_value[0] = $acc_field;
			$form_value[1] = $new_field;
			$form_value[2] = $con_field;
			$form_value[3] = $a_form[0]['form'][0];
			
			$a_data['status'] = 'S';
			$a_data['message'] = 'Success';
		}else {
			$a_data['status'] = 'E';
			$a_data['message'] = 'No data';
		}

		$a_form[0]['form'] = $form_value;
		$a_data['data'] = $a_form;

		return $a_data;
	}

	public function return_form($a_data,$module,$a_param)
	{
		if($a_data)
		{
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["message"];
			$a_return["data"][]['result'][] = !empty($a_data['data'][0]) ? $a_data['data'][0] : "" ;

			$log_filename = "Form_Convert_Leads";
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$this->common->_filename= $log_filename;
			$this->common->set_log($url,$a_param,$a_return);

			if ($format!="json" && $format!="xml"  ) {
				$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
		}
		else
		{
			$this->response(array('error' => 'Couldn\'t find any Parcel!'), 404);
		}
	}

	public function insert_content_oc_post(){

		$this->common->_filename= "Insert_Leads";
	  	header('Content-Type:application/json; charset=UTF-8');
	  	$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
	  	$response_data = null;
	  	$a_request =$dataJson;
	  	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	  	
	  	$this->common->set_log('Before Insert Leads==>',$url,$a_request);
		$response_data = $this->get_insert_dataoc($a_request);	
	  	$this->common->set_log('After Insert Leads==>',$a_request,$response_data);
	  
	  	if ( $response_data ) {
	  		$this->response($response_data, 200); // 200 being the HTTP response code
	  	} else {
	  		$this->response(array(
	  				'error' => 'Couldn\'t find Set Content!'
	  		), 404);
	  	}
	}

	private function get_insert_dataoc($a_request){
	  	
	  	$response_data = null;
	  	$module=isset($a_request['module']) ? $a_request['module'] : "";
	  	$crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
	  	$action=isset($a_request['action']) ? $a_request['action'] : "";
	  	$data=isset($a_request['data']) ? $a_request['data'] : "";
	  	$userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
		$accountid = isset($a_request['accountid']) ? $a_request['accountid'] : "0";
		$accountname = isset($a_request['accountname']) ? $a_request['accountname'] : "";
		$contactname = isset($a_request['contactname']) ? $a_request['contactname'] : "";
		$smownerid = isset($a_request['smownerid']) ? $a_request['smownerid'] : "19330";

		if($smownerid==""){
			//$smownerid = $userid;
			$smownerid = '19330';
		}

	  	$date_entered = date('Y-m-d H:i:s');
		$date_modified = date('Y-m-d H:i:s');

	  	if($action == 'convert' and $module=="Leads"){
			//Accounts
			if($accountid == 0){
				$acc_query = "select aicrm_account.accountid from aicrm_account inner join aicrm_crmentity on aicrm_account.accountid = aicrm_crmentity.crmid where aicrm_crmentity.deleted=0 and aicrm_account.accountname = ?";

				$acc_res = $this->db->query($acc_query, array($accountname));
				$acc_rows = $acc_res->result_array();
								
				if(!empty($acc_rows)){
					$accountid = $acc_rows[0]['accountid'];
					$this->getRelatedNotesAttachments($crmid,$accountid);
					$this->getRelateddeal($crmid,$accountid);
					$this->getRelatedproducts($crmid,$accountid);
					$this->getRelatedHelpDesk($crmid,$accountid);
					$this->getRelatedVoucher($crmid,$accountid);
					$this->getRelatedtag($crmid,$accountid,$userid);

				}else{
					$accountid = $this->getUniqueID("aicrm_crmentity");
					//Saving Account - starts
					$sql_crmentity = "insert into aicrm_crmentity(crmid,smcreatorid,smownerid,setype,presence,createdtime,modifiedtime,deleted) values(?,?,?,?,?,?,?,?)";
					$sql_params = array($accountid, $userid, $smownerid, 'Accounts', 1, $date_entered, $date_modified, 0);
					$this->db->query($sql_crmentity, $sql_params);
					
				    $account_no = $this->autocd_acc();
					//Getting the custom aicrm_field values from leads and inserting into Accounts if the aicrm_field is mapped - Jaguar
					$col_val= $this->getInsertValues("Accounts",$accountid,$crmid);
					
					$insert_columns = $col_val['columns'];
					$insert_columns[] = "accountid";
					$insert_values = $col_val['values'];
					$insert_values[] = $accountid;
							
					for($aa=0;$aa<count($insert_columns);$aa++){
						if(substr($insert_columns[$aa],0,3)=="cf_"){
						}else{
							$insert_columns_ok[]=$insert_columns[$aa];
							$insert_values_ok[]=$insert_values[$aa];
						}
					}
					$insert_columns_ok[]="account_no";		
					$insert_values_ok[]=$account_no;
					$insert_columns_ok[]="accountname";		
					$insert_values_ok[]=@$accountname;
					$insert_columns_ok[]="register_date";		
					$insert_values_ok[]=date('Y-m-d');
					// alert(json_encode($insert_columns_ok)); 
					// alert(json_encode($insert_values_ok)); exit;
					
					$insert_val_str_ok = $this->generateQuestionMarks($insert_values_ok);
					//echo $insert_val_str_ok; exit;
					
					$sql_insert_account = "INSERT INTO aicrm_account (". implode(",",$insert_columns_ok) .") VALUES (".$insert_val_str_ok.")";
					//echo $sql_insert_account; exit;
					$this->db->query($sql_insert_account, $insert_values_ok);
					

					$sql_insert_lead2acc = "INSERT INTO aicrm_convert_lead2acc (accountid,leadid,createdate) VALUES (?,?,?)";
					// Modified by Minnie -- END
					$lead2acc_params = array($accountid, $crmid,$date_entered );
					$this->db->query($sql_insert_lead2acc, $lead2acc_params);

					$sql_insert_accountbillads = "INSERT INTO aicrm_accountbillads (accountaddressid) VALUES (?)";
					$billads_params = array($accountid);
					$this->db->query($sql_insert_accountbillads, $billads_params);
					
					$sql_insert_accountshipads = "INSERT INTO aicrm_accountshipads (accountaddressid) VALUES (?)";
					$shipads_params = array($accountid);
					$this->db->query($sql_insert_accountshipads, $shipads_params);
						
					$insert_columns_ok=array();
					$insert_values_ok=array();
					for($aa=0;$aa<count($insert_columns);$aa++){
						if(substr($insert_columns[$aa],0,3)=="cf_"){
							$insert_columns_ok[]=$insert_columns[$aa];
							$insert_values_ok[]=$insert_values[$aa];
						}
					}
					$insert_columns_ok[] = "accountid";
					$insert_values_ok[] = $accountid;
					
					$insert_val_str = $this->generateQuestionMarks($insert_values_ok);
					
					$sql_insert_accountcustomfield = "INSERT INTO aicrm_accountscf (". implode(",",$insert_columns_ok) .") VALUES (".$insert_val_str.")";
					$this->db->query($sql_insert_accountcustomfield, $insert_values_ok);
					//Saving Account - ends
					$this->getRelatedNotesAttachments($crmid,$accountid);
					$this->getRelateddeal($crmid,$accountid);
					$this->getRelatedproducts($crmid,$accountid);
					$this->getRelatedHelpDesk($crmid,$accountid);
					$this->getRelatedVoucher($crmid,$accountid);
					$this->getRelatedtag($crmid,$accountid,$userid);
				}
			}else{
				$this->getRelatedNotesAttachments($crmid,$accountid);
				$this->getRelateddeal($crmid,$accountid);
				$this->getRelatedproducts($crmid,$accountid);
				$this->getRelatedHelpDesk($crmid,$accountid);
				$this->getRelatedVoucher($crmid,$accountid);
				$this->getRelatedtag($crmid,$accountid,$userid);
			}
			//Accounts

 			//Contacts
			$date_entered = date('Y-m-d H:i:s');
			$date_modified = date('Y-m-d H:i:s');
		  	
			$contactid = $this->getUniqueID("aicrm_crmentity");
		  	
			$sql_crmentity = "insert into aicrm_crmentity(crmid,smcreatorid,smownerid,setype,presence,deleted,createdtime,modifiedtime,description) values(?,?,?,?,?,?,?,?,?)";
			$sql_params = array($contactid, $userid, $smownerid, 'Contacts', 0, 0, $date_entered, $date_modified, $row['description']);
			$this->db->query($sql_crmentity, $sql_params);  	
		  	
		    $contact_no = $this->autocd_Contacts();
			// END
			$col_val= $this->getInsertValues("Contacts",$contactid,$crmid);

			$contact_columns = $col_val['columns'];
			$contact_columns[] = "contactid";
			$contact_values = $col_val['values'];
			$contact_values[] = $contactid;
			
			for($aa=0;$aa<count($contact_columns);$aa++){
				if(substr($contact_columns[$aa],0,3)=="cf_"){
				}else{
					$contact_columns_ok[]=$contact_columns[$aa];
					$contact_values_ok[]=$contact_values[$aa];
				}
			}

			$contact_columns_ok[] = "contact_no";
			$contact_values_ok[] = $contact_no;
			$contact_columns_ok[] = "accountid";
			$contact_values_ok[] = @$accountid;
			/*$contact_columns_ok[] = "firstname";
			$contact_values_ok[] = @$contactname;*/
			$contact_columns_ok[] = "contactname";
			$contact_values_ok[] = @$contactname;

			$insert_val_str_ok = $this->generateQuestionMarks($contact_values_ok);
			// $sql_insert_deal = "INSERT INTO aicrm_deal (". implode(",",$deal_columns_ok) .") VALUES (".$insert_val_str_ok.")";
			$sql_insert_contact = "INSERT INTO aicrm_contactdetails (". implode(",",$contact_columns_ok) .") VALUES (".$insert_val_str_ok.")";
			$this->db->query($sql_insert_contact, $contact_values_ok);

			$contact_columns_ok=array();
			$contact_values_ok=array();
			for($aa=0;$aa<count($contact_columns);$aa++){
				if(substr($contact_columns[$aa],0,3)=="cf_"){
					$contact_columns_ok[]=$contact_columns[$aa];
					$contact_values_ok[]=$contact_values[$aa];
				}
			}

			$contact_columns_ok[] = "contactid";
			$contact_values_ok[] = $contactid;
			$insert_val_str = $this->generateQuestionMarks($contact_values_ok);
			
			$sql_insert_contactcustomfield = "INSERT INTO aicrm_contactscf (". implode(",",$contact_columns_ok) .") VALUES (".$insert_val_str.")";
			$this->db->query($sql_insert_contactcustomfield, $contact_values_ok);
			$this->getRelatedNotesAttachments($crmid,$contactid);
			//Contacts

			//Related Visit Account Contact
			$this->getRelatedvisit($crmid,$accountid,$contactid);
			//Related Visit Account Contact

			$sql_insert_account = "update aicrm_leaddetails set accountid='".$accountid."' ,contactid = '".$contactid."' where leadid='".$crmid."' ";
			$this->db->query($sql_insert_account, array());
			
			if($crmid != '' && $accountid != ''){
				$s_update = "Update message_customer set module = 'Accounts' , parentid = '".$accountid."' , contactid = '".@$contactid."' where parentid = '".$crmid."' ";
				$this->db->query($s_update, array());

				$s_updatesla = "Update aicrm_sla set module = 'Contacts' , crmid = '".@$contactid."' where crmid = '".$crmid."' and module = 'Leads' ";
				$this->db->query($s_updatesla, array());
			}

			if($accountid != ''){
				/*$sql_update_converted = "UPDATE aicrm_leaddetails SET converted = 1 , leadstatus='แปลง' ,convert_date = '".date('Y-m-d h:i:s')."' where leadid=?";*/

				$sql_update_converted = "UPDATE aicrm_leaddetails SET convert_lead = 1, converted = 1 ,convert_date = '".date('Y-m-d h:i:s')."' where leadid=?";
				$this->db->query($sql_update_converted, array($crmid)); 
				$chk="0";
			}

  			if($chk=="0"){
  				$a_return["Message"] = ($action=="convert")?"Convert Complete" : "Update Complete";
  				
  				$a_list = $this->convertlead_model->get_list_account($accountid);
				$a_data = $a_list['result']['data'];

				if($mobile_convert!=""){
					$a_data = array(
						"crmid"=>$crmid,
						"no"=>'',
						"name"=>''
					);
				  $a_return["data"] = $a_data;
				}else{
					$a_return["data"] = $a_data;
				}
  				
  			}else{
  				$a_return  =  array(
  						'Type' => 'E',
  						'Message' => 'Unable to complete transaction',
  				);
  			}
	  	}else{//echo "ddd";
	  		$a_return  =  array(
	  				'Type' => 'E',
	  				'Message' =>  'Invalid Request!',
	  		);
	  	}
	  	return array_merge($this->_return,$a_return);
  	}
	
}