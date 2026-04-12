<?php // callback.php
defined('BASEPATH') or exit('No direct script access allowed');
require_once('src/OAuth2/Autoloader.php');
require APPPATH . '/libraries/REST_Controller.php';

class  Facebookmessage extends REST_Controller
{

	function __construct()
  	{
	    parent::__construct();
	    $this->load->library('memcached_library');
		$this->load->library('crmentity');
	    $this->load->database();
		$this->load->library("common");
		$this->load->model("facebookmessage_model");
		$this->load->library('lib_socket');
		$this->_format = "array";
		$this->_return = array(
				'Type' => "S",
				'Message' => "Insert Complete",
				'cache_time' => date("Y-m-d H:i:s"),
				'total' => "1",
				'offset' => "0",
				'limit' => "1",
				'data' => array(
						
				),
		);
		$dsn  = 'mysql:dbname=' . $this->config->item('oauth_db_database') . ';host=' . $this->config->item('oauth_db_host');
		$dbusername = $this->config->item('oauth_db_username');
		$dbpassword = $this->config->item('oauth_db_password');
		OAuth2\Autoloader::register();

		// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
		$storage = new OAuth2\Storage\Pdo(array(
			'dsn' => $dsn,
			'username' => $dbusername,
			'password' => $dbpassword
		));

		// Pass a storage object or array of storage objects to the OAuth2 server class
		$this->oauth_server = new OAuth2\Server($storage);
		// Add the "Client Credentials" grant type (it is the simplest of the grant types)
		$this->oauth_server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
		// Add the "Authorization Code" grant type (this is where the oauth magic happens)
		$this->oauth_server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

	}

	public function getfbmessage_post(){

		/*$pages_access_token = "EAAB20aPU3ckBAGCKuTzHriFB4kcS5nZAMaJSJrpssjfGRriNMBPQFCLVDEeW7cPiDhV4of9M4sZC1ZBvVVhJmUGQHoKuFgg4M6QnTjo9IZA8KscnTGVRJd24pIZC1tmZCrQZBa0GbHfnxrlrYolyjcir4z7NFLfsrIzeISizbGhTXoaes6xqBJ7";*/

		// Get POST body content
		$content = file_get_contents('php://input');
		// $dataJson =  $this->input->get();
		// Parse JSON
		// alert($content);exit;
		$data = json_decode($content, true,512, JSON_BIGINT_AS_STRING);
		
		$events = @$data['events'];
		$messaging = @$events['entry'][0]['messaging'][0];

		$recipient_id = @$events['entry'][0]['messaging'][0]['recipient']['id']; // pages id
		$sender_id = @$events['entry'][0]['messaging'][0]['sender']['id'];

		$timestamp = @$events['entry'][0]['messaging'][0]['recipient']['timestamp'];
		$mid = @$events['entry'][0]['messaging'][0]['message']['mid'];
		//$text = @$events['entry'][0]['messaging'][0]['message']['text'];
		if(!empty($events['entry'][0]['messaging'][0]['message']['text'])){
			$text = @$events['entry'][0]['messaging'][0]['message']['text'];
		}else{
			$text = @$events['entry'][0]['messaging'][0]['postback']['payload'];
		}
		$attachments = @$events['entry'][0]['messaging'][0]['message']['attachments'];

		$userid ="";
		$messages = "";
		$messages_type = "";
		$messages_id ="";
		$destination ="";
		$stickerid ="";
		$data_message = array();

		$displayName ="";
		$pictureUrl ="";
		$birthday ="";
		$gender ="";
		// Validate parsed JSON data

		if (!is_null($events)) {
			
			$destination = $recipient_id; // id ผู้รับหรือ pages id
			$userid = $sender_id;
			$messages = $text;
			if(!empty($attachments)){

				foreach ($attachments as $key => $value) {
					$messages_type = $value['type'];
					$data_message[$key]['messages_type'] = $messages_type;

					if($value['type'] =="image" || $value['type'] =="video" || $value['type'] =="audio" || $value['type'] =="file"){	

						$url_data = @$value['payload']['url'];

						$url_data = str_replace( '\/', '/', $url_data );
						$ch = curl_init($url_data);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						// curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
						curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
						$result_data = curl_exec($ch);
						$err = curl_error($ch);
						curl_close($ch);

							if($messages_type =="image"){

								$stickerid = @$value['payload']['sticker_id'];

								$folderPath = "upload_facebook/image/";
								$file = $folderPath.uniqid().'.png';
								file_put_contents("../".$file, $result_data);
								$filepath = "https://".$_SERVER['HTTP_HOST']."/".$file;
								$messages = $filepath;
								$data_message[$key]['messages']= $messages;
								// alert($messages);exit;

							}elseif($messages_type =="video"){

								$folderPath = "upload_facebook/video/";
								$file = $folderPath.uniqid().'.mp4';
								file_put_contents("../".$file, $result_data);
								$filepath = "https://".$_SERVER['HTTP_HOST']."/".$file;
								$messages = $filepath;
								$data_message[$key]['messages']= $messages;
								// alert($messages);exit;
								
							 }elseif ($messages_type =="audio"){

								$folderPath = "upload_facebook/audio/";
								$file = $folderPath.uniqid().'.mp3';
								file_put_contents("../".$file, $result_data);
								$filepath = "https://".$_SERVER['HTTP_HOST']."/".$file;
								$messages = $filepath;
								$data_message[$key]['messages']= $messages;
								// alert($messages);exit;
								
							}elseif($messages_type =="file"){

								$fileName = $event['message']['fileName'];
								$folderPath = "upload_facebook/file/";
								$file = $folderPath.uniqid()."_".$fileName;
								file_put_contents("../".$file, $result_data);
								$filepath = "https://".$_SERVER['HTTP_HOST']."/".$file;
								$messages = $filepath;
								$data_message[$key]['messages']= $messages;
								// alert($messages);exit;
							}
					}
				}

				if(!empty($text)){

					$messages_type = "text"; //fb ไม่ระบุ type มาให้
					$messages = $text;

					$d_mess = array('messages_type' => $messages_type,'messages'=> $messages);
					$data_message[] = $d_mess;

				}

			}else{
				$messages_type = "text"; //fb ไม่ระบุ type มาให้
				$data_message[0]['messages_type']= $messages_type;
				$data_message[0]['messages']= $messages;
			}

			//echo $userid; exit;
			$sql_admin = "Select * from aicrm_social_config where channelid = '".$userid."' ";
			
			$query_admin = $this->db->query($sql_admin);
			$data_admin = $query_admin->result_array();
			


			if(!empty($data_admin)){
				$senderid = $sender_id;
				$userid = $recipient_id;
				$channelid = @$data_admin[0]['id'];
			}else if(!is_null($userid)){

				$channelid = 0 ;
				$channel_name = '';
				$sql_admin = "Select * from aicrm_social_config where channelid = '".$recipient_id."' "; 
				$query_admin = $this->db->query($sql_admin);
				$data_page = $query_admin->result_array();
				$channelid = @$data_page[0]['id'];
				$channel_name = @$data_page[0]['name'];
				$pages_access_token = @$data_page[0]['access_token'];
				// Make a GET to Facebook user Profile
				/*$url = "https://graph.facebook.com/".$userid."?fields=first_name,last_name,languages,birthday,gender,profile_pic&access_token=".$pages_access_token;*/
				$url = "https://graph.facebook.com/".$userid."?fields=first_name,last_name,languages,birthday,picture&access_token=".$pages_access_token;
				$headers = array('Content-Type: application/json');
				$ch = curl_init($url);

				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$result = curl_exec($ch);

				curl_close($ch);
				$result = json_decode($result,true);

				if(!empty($result)){

					$name = str_replace("'","",$result['first_name'])." ".str_replace("'","",$result['last_name']);
					$displayName = str_replace("'","",$result['first_name'])." ".str_replace("'","",$result['last_name']);
					$firstname = $result['first_name'];
					$lastname = $result['last_name'];
					$pictureUrl = @$result['picture']['data']['url'];
					$birthday = @$result['birthday'];
					$gender = @$result['gender'];
					
				}else{
					$url = "https://graph.facebook.com/".$userid."?fields=first_name,last_name,languages,birthday,profile_pic&access_token=".$pages_access_token;
					$headers = array('Content-Type: application/json');
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					$result = curl_exec($ch);

					curl_close($ch);
					$result = json_decode($result,true);

					$name = str_replace("'","",$result['first_name'])." ".str_replace("'","",$result['last_name']);
					$displayName = str_replace("'","",$result['first_name'])." ".str_replace("'","",$result['last_name']);
					$firstname = $result['first_name'];
					$lastname = $result['last_name'];
					$pictureUrl = $result['profile_pic'];
					$birthday = @$result['birthday'];
					$gender = @$result['gender'];

				}

			}
			
		}
		
		if(!empty($data_admin)){

			$sql_user = "SELECT * FROM message_customer where customerno = '".$userid."' AND channelid = '".$channelid."' ";
			$query_user = $this->db->query($sql_user);
			$data_user = $query_user->result_array();
			
			if(!empty($data_user)){

				$lastmessage = "SELECT * FROM message_chathistory where customerid = '".$data_user[0]['customerid']."' and customerno = '".$userid."' and chatactionname = 'Agent' and message = '".$messages."' and NOW() between (DATE_ADD(cast( messagetime as datetime ),  INTERVAL -30 second)) and (DATE_ADD(cast( messagetime as datetime ),  INTERVAL 30 second) ) order by chatid desc limit 1";
				$query_lastmessage = $this->db->query($lastmessage);
				$data_lastmessage = $query_lastmessage->result_array();

			 	if(empty($data_lastmessage)){
			 		
					$customerid = $data_user[0]['customerid'];
					$customerno = $data_user[0]['customerno'];
					$channel = $data_user[0]['channel'];
					$displayName = $data_user[0]['socialname'];

					foreach ($data_message as $key => $value) {

						$sql_chat = "SELECT chatno from message_chathistory WHERE customerid='".$customerid."' order by chatno desc limit 0,1";
						$query_chat = $this->db->query($sql_chat);
						$data_chat = $query_chat->result_array();
						$chatno = $data_chat[0]['chatno'];
						$nextchatno = $chatno+1;
						$data_message[$key]['chatno']=$nextchatno;

						$data_chat = array(  
				            'customerid ' => $customerid,  
				            'chatno'  => $nextchatno,  
				            'customerno'   => $customerno,  
				            'chatactionname' => 'Agent',
				            'message' => $value['messages'],
				            'messagetime'=> date('Y-m-d H:i:s'),
				            'isagent'=> '2',
				            'channel'=> $channel,
				            'messageaction'=>'agent',
				            'flag_read'=>'0',
				            'userid'=> '1',
				            'channelid'=> $channelid,
				            'message_type'=>$value['messages_type'],
				        );
				        //alert($data_chat); exit;
						$this->db->insert('message_chathistory',$data_chat);

						$this->lib_socket->emit_socket_detail($customerid,$displayName);
					
					}
					
					$response_data['Type']='S';
					$response_data['Message']='Success';
					$response_data['Time']=date('Y-m-d H:i:s');
			 	
			 	}else{

			 		$response_data['Type']='E';
					$response_data['Message']='Fail';
					$response_data['Time']=date('Y-m-d H:i:s');

			 	}
				
			}else{
				$response_data['Type']='E';
				$response_data['Message']='Fail';
				$response_data['Time']=date('Y-m-d H:i:s');
				
			}
			
			$this->response($response_data, 200);

		}else if(!is_null($userid)){
			$channelid = 0 ;
			$channel_name = '';
			$sql_admin = "Select * from aicrm_social_config where channelid = '".$recipient_id."' "; 
			$query_admin = $this->db->query($sql_admin);
			$data_page = $query_admin->result_array();
			$channelid = @$data_page[0]['id'];
			$channel_name = @$data_page[0]['name'];

			$sql_getcustomer = "SELECT * from message_customer WHERE customerno='".$userid."' AND channelid = '".$channelid."' ";
			$query_getcustomer = $this->db->query($sql_getcustomer);
			$data_getcustomer = $query_getcustomer->result_array();
			//alert($data_getcustomer); exit; 
			if(!empty($data_getcustomer)){
				$sql_customer = " UPDATE message_customer SET startchatting = '".date('Y-m-d H:i:s')."' ,custfirstname='".$displayName."'   , channel = 'facebook', socialname='".$displayName."' , pictureurl='".$pictureUrl."' ,modifiedtime = '".date('Y-m-d H:i:s')."',deleted=0 WHERE customerno = '".$userid."' AND channelid = '".$channelid."' ";
				//echo $sql_customer; exit;
			}else{
				//Insert Lead
				$tab_name_lead = array('aicrm_crmentity','aicrm_leaddetails','aicrm_leadsubdetails','aicrm_leadaddress','aicrm_leadscf');
				$tab_name_index_lead = array('aicrm_crmentity'=>'crmid','aicrm_leaddetails'=>'leadid','aicrm_leadsubdetails'=>'leadsubscriptionid','aicrm_leadaddress'=>'leadaddressid','aicrm_leadscf'=>'leadid');
				$module_leads = "Leads";
				$crmid = "";
				$action = 'add';
				$data[0]['lead_no'] = "";
				$data[0]['firstname'] = 'Facebook - '.$displayName;
				$data[0]['lastname'] = '';
				$data[0]['social_channel'] = 'Facebook';
				$data[0]['social_id'] = $userid;
				$data[0]['social_name'] = $displayName;
				//$data[0]['newsocialname'] = $displayName;
				$data[0]['first_contact_date'] = date('Y-m-d');
				//$data[0]['social_status'] = 'New';
				$data[0]['smownerid'] = '19330';
				$data[0]['chat_status'] = 'อินบ็อกซ์';
				$data[0]['line_oa_facebook_fan_page_name'] = $channel_name;
				$data[0]['leadsource'] = 'Facebook';
				$data[0]['leaddate'] = date('Y-m-d');
				$data[0]['email_consent'] = 1;
				$data[0]['sms_consent'] = 1;
				$data[0]['main_contact_channel'] = 'Facebook';

				list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module_leads,$crmid,$action,$tab_name_lead,$tab_name_index_lead,$data,'2');

				$sql_customer = "INSERT INTO message_customer (customerno, custfirstname, custsubject, custstartlogin, startchatting,  skillbaseid,channel,socialname,pictureurl,parentid,module,createdate,modifiedtime,deleted,channelid)
				VALUES ('".$userid."','".$displayName."',1,'".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."' ,'1','facebook','".$displayName."','".$pictureUrl."','".@$crmid."','Leads','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."',0 ,'".$channelid."') ";
			}

			if($this->db->query($sql_customer)){

				$sql_chat = "SELECT chatno from message_chathistory 
				WHERE customerno='".$userid."' AND channelid = '".$channelid."' order by chatno desc limit 0,1";
				$query_chat = $this->db->query($sql_chat);
				$data_chat = $query_chat->result_array();

				$sql_customer = "SELECT customerid,parentid, module,contactid from message_customer WHERE customerno= '".$userid."' AND channelid = '".$channelid."' ";
				$query_customer = $this->db->query($sql_customer);
				$data_customer = $query_customer->result_array();
				$customerid = @$data_customer[0]['customerid'];
				$contactid = @$data_customer[0]['contactid'];

				$parentid = @$data_customer[0]['parentid'];
				$module = @$data_customer[0]['module'];

				if($action == 'add'){
					$sql_sla = "INSERT INTO aicrm_sla (crmid,module,customerid,chat_status,updatedate,userid,sla_group) VALUES ( '".$crmid."','Leads','".$customerid."','อินบ็อกซ์','".date('Y-m-d H:i:s')."','1','1');";
					$this->db->query($sql_sla);
				}else{

					if($module == 'Accounts'){
						
						/*$sql_acc = "SELECT chat_status FROM aicrm_account where accountid = '".$parentid."' ";
						$data_crm = $this->db->query($sql_acc);
						$d_customer = $data_crm->result_array();
						$chat_status = @$d_customer[0]['chat_status'];*/

						$sql_con = "SELECT chat_status FROM aicrm_contactdetails where contactid = '".$contactid."' ";
						$data_crm = $this->db->query($sql_con);
						$d_customer = $data_crm->result_array();
						$chat_status = @$d_customer[0]['chat_status'];

						if($chat_status == 'เสร็จสิ้น'){
							
							/*$tab_name_acc = array('aicrm_crmentity','aicrm_account','aicrm_accountbillads','aicrm_accountshipads','aicrm_accountscf');
	  						$tab_name_index_acc = array('aicrm_crmentity'=>'crmid','aicrm_account'=>'accountid','aicrm_accountbillads'=>'accountaddressid','aicrm_accountshipads'=>'accountaddressid','aicrm_accountscf'=>'accountid');*/

	  						$tab_name_contacts = array('aicrm_crmentity','aicrm_contactdetails','aicrm_contactaddress','aicrm_contactsubdetails','aicrm_contactscf','aicrm_customerdetails');
							$tab_name_index_contacts = array('aicrm_crmentity'=>'crmid','aicrm_contactdetails'=>'contactid','aicrm_contactaddress'=>'contactaddressid','aicrm_contactsubdetails'=>'contactsubscriptionid','aicrm_contactscf'=>'contactid','aicrm_customerdetails'=>'customerid');

							$module_contacts = "Contacts";
							$contactid = $contactid;
							$action = 'edit';
			  				$data[0]['chat_status'] = 'อินบ็อกซ์';
							$this->crmentity->Insert_Update($module_contacts,$contactid,$action,$tab_name_contacts,$tab_name_index_contacts,$data,'1');
						
						}
					}else{

						$sql_lead = "SELECT chat_status FROM aicrm_leaddetails where leadid = '".$parentid."' ";
						
						$data_crm = $this->db->query($sql_lead);
						$d_customer = $data_crm->result_array();
						$chat_status = @$d_customer[0]['chat_status'];

						if($chat_status == 'เสร็จสิ้น'){
							$tab_name_lead = array('aicrm_crmentity','aicrm_leaddetails','aicrm_leadsubdetails','aicrm_leadaddress','aicrm_leadscf');
							$tab_name_index_lead = array('aicrm_crmentity'=>'crmid','aicrm_leaddetails'=>'leadid','aicrm_leadsubdetails'=>'leadsubscriptionid','aicrm_leadaddress'=>'leadaddressid','aicrm_leadscf'=>'leadid');
							$module_leads = "Leads";
							$parentid = $parentid;
							$action = 'edit';
			  				$data[0]['chat_status'] = 'อินบ็อกซ์';
							$this->crmentity->Insert_Update($module_leads,$parentid,$action,$tab_name_lead,$tab_name_index_lead,$data,'1');
						}
					}
					
					if($chat_status == 'เสร็จสิ้น'){
						$sql_max = "select max(sla_group)+1 as sla_group from aicrm_sla where customerid ='".$customerid."';";
						$query_max = $this->db->query($sql_max);
						$data_sla = $query_max->result_array();
						$sla_group = @$data_sla[0]['sla_group'];

						if($module == 'Accounts'){
							$sql_sla = "INSERT INTO aicrm_sla (crmid,module,customerid,chat_status,updatedate,userid,sla_group) VALUES ( '".$contactid."','Contacts','".$customerid."','อินบ็อกซ์','".date('Y-m-d H:i:s')."','1' ,'".$sla_group."');";
						}else{
							$sql_sla = "INSERT INTO aicrm_sla (crmid,module,customerid,chat_status,updatedate,userid,sla_group) VALUES ( '".$parentid."','".$module."','".$customerid."','อินบ็อกซ์','".date('Y-m-d H:i:s')."','1' ,'".$sla_group."');";
						}
						$this->db->query($sql_sla);
					}
				}

				if(!empty($data_chat)){

					foreach ($data_message as $key => $value) {
						if(empty($value['chatno'])){
							$chatno = $data_chat[0]['chatno'];
							$nextchatno = $chatno+1;
						}else{
							$nextchatno = $value['chatno'];
						}

						$sql_chat = "INSERT INTO message_chathistory (customerid ,chatno,customerno, chatactionname, message, messagetime, isagent, channel,messageaction,message_type,sticker_id,channelid)
						VALUES ('".$customerid."','".$nextchatno."','".$userid."','".$displayName."', '". $value['messages']."','".date('Y-m-d H:i:s')."','2' ,'facebook','customer','".$value['messages_type']."' ,'".$stickerid."' ,'".$channelid."') ";
						$this->db->query($sql_chat);
					}

				}else{

					foreach ($data_message as $key => $value) {
						
					$sql_chat = "INSERT INTO message_chathistory (customerid ,chatno,customerno, chatactionname, message, messagetime, isagent, channel,messageaction,message_type,sticker_id,channelid)
					VALUES ('".$customerid."','1','".$userid."','".$displayName."', '".$value['messages']."','".date('Y-m-d H:i:s')."','2' ,'facebook','customer','".$value['messages_type']."' ,'".$stickerid."','".$channelid."') ";
					$this->db->query($sql_chat);

					}

				}
				$this->lib_socket->emit_socket_detail($customerid,$displayName);

				$response_data['Type']='S';
				$response_data['Message']='Success';
				$response_data['Time']=date('Y-m-d H:i:s');
		
			}else{

				$response_data['Type']='E';
				$response_data['Message']='Fail';
				$response_data['Time']=date('Y-m-d H:i:s');

			}

			$this->response($response_data, 200);
		}

	}

	/*public function emit_socket($customerid = '',$displayName=''){

		if($customerid == '' ){
			return false;
		}
		
		$data = $this->facebookmessage_model->get_list($customerid); //alert($data['result']['data']); 
		$data_message=$this->facebookmessage_model->get_list_detail($customerid); //alert($data_message['result']['data']); exit;

		$msg = array(
			"newchat" => $data['result']['data'],
			"newmessage" => $data_message['result']['data'],
			"displayName" =>$displayName
		);
		
		$port = $this->config->item('socketIOPort');

		require_once '/SocketIO.php';
		//$client = new SocketIO('localhost',$port);
		$client = new SocketIO('moaioc.moai-crm.com',$port);
		$client->setQueryParams([
		    'token' => 'edihsudshuz',
		    'id' => '8781',
		    'cid' => '345',
		    'cmp' => 2340
		]);

		$success = $client->emit('joinRoom_user', ['agentid' => $displayName,'customerid' => $customerid ,'msg'=>$msg]);

		return true;
	}*/

	public function sendfbmessage_post(){

		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
		$a_data = $dataJson;

		$this->common->_filename= "Facebook_Sendmessage";
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->common->set_log('Post==>',$a_data,array());

		/*if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
			$a_data['status'] = false;
	        $a_data['error'] = 'Access Token not found';
	        $a_data['time'] = date("Y-m-d H:i:s");
	        $a_data["data"]["data"] = '';
	        $a_data["data"]['total'] = 0;
	        $a_data['offset'] = 0;
	        $a_data['limit'] = 0;
	        $this->return_data_token($a_data);
		}*/

		$channel = @$a_data["channel"];
		$customerid = @$a_data['customerid'];
		$userid = @$a_data['userid'];

		$data = @$a_data["data"];

		if($channel=="facebook"){
			$sendmessage = $this->SendMessageFacebook($data,$channel,$customerid,$userid);
			if($sendmessage==true){
				
				$this->lib_socket->emit_socket_detail($customerid,$userid);

				$response_data['Type']='S';
				$response_data['Message']='Facebook Send Success';
				$response_data['cachetime']=date('Y-m-d H:i:s');

			}else{

				$response_data['Type']='E';
				$response_data['Message']='Facebook send Fail';
				$response_data['cachetime']=date('Y-m-d H:i:s');
			}

		}else{

			$response_data['Type']='E';
			$response_data['Message']='No channel send Fail';
			$response_data['cachetime']=date('Y-m-d H:i:s');
		}

		$this->common->set_log('Response==>',$a_data,$response_data);

		$this->response($response_data, 200);

	}

	public function SendMessageFacebook($data="",$channel="",$customerid="",$userid="")
	{

		if($data==""){
			return false;
		}

		/*$pages_access_token = 'EAAB20aPU3ckBAIEgg2ttReWpNGKOUd75ArozgQpBkPMUKornmJZCCySDy2yRhy7eFXAn9qV84DAKOtF1GtJdGZBYlaJe8Ow9irp9US06TMM0FnYfexS0vbaf1ZCBMvujcPOT1F49RdxyZAocYNRZCOPzZC7wdLsuplFzzJTZAS4mZAnwad07XQVK';*/
		//$pages_access_token = 'EAAB20aPU3ckBAGCKuTzHriFB4kcS5nZAMaJSJrpssjfGRriNMBPQFCLVDEeW7cPiDhV4of9M4sZC1ZBvVVhJmUGQHoKuFgg4M6QnTjo9IZA8KscnTGVRJd24pIZC1tmZCrQZBa0GbHfnxrlrYolyjcir4z7NFLfsrIzeISizbGhTXoaes6xqBJ7';

		$pages_access_token = '';

		$this->db->select("aicrm_social_config.*");
		$this->db->join('aicrm_social_config', 'aicrm_social_config.id = message_customer.channelid',"inner");
		$this->db->where('message_customer.customerid',$customerid);
		$query_socail = $this->db->get('message_customer');
		$data_socail = $query_socail->result_array();
		$pages_access_token = @$data_socail[0]['access_token'];
		$channelid = @$data_socail[0]['id'];

		//alert($pages_access_token); exit;
		$message_type = @$data['message_type'];
		// $type = "message";
		$userId = @$data['socialid'];
		
		// $replyToken = "24ca704a5d5d4ff98934020282c5e564";
		//$text = @$data['message'];
		$text = trim(@$data['message']);

		$recipient = ['id' => $userId ];

		if($message_type=="image" || $message_type=="video" || $message_type=="audio" || $message_type=="file"){

			$payload = [
	             "url"=> trim(@$data['message']),
	             "is_reusable"=> "true"
	        ];

			$attachment = [
				"type" => @$message_type,
				"payload" => $payload
			];

			$messages = [
			'attachment' => $attachment 
			];

			// ### format ###
			// "message": {
	  		// "attachment":{
	        //	 "type":"image",
	  	    //   "payload":{
	  	    //    	 "url":"https://moaioc.moai-crm.com/upload/2021/January/60125fc11e50e_116281457.jpg",
	  	    //       "is_reusable":"true"
	  	    //   	}
	     	//	  }
	  		//	}

		}elseif($message_type=="text"){

			$messages = [
				'text' => trim(@$data['message'])
			];

		}

		if($userId != "" ){

			$sql_chat = "SELECT chatno from message_chathistory WHERE customerid='".$customerid."' order by chatno desc limit 0,1";
			
			$query_chat = $this->db->query($sql_chat);
			$data_chat = $query_chat->result_array();
			$chatno = $data_chat[0]['chatno'];
			$nextchatno = $chatno+1;
			
			$data_chat = array(  
	            'customerid ' => $customerid,  
	            'chatno'  => $nextchatno,  
	            'customerno'   => $userId,  
	            'chatactionname' => 'Agent',
	            'message' => $text,
	            'messagetime'=> date('Y-m-d H:i:s'),
	            'isagent'=> '2',
	            'channel'=> $channel,
	            'messageaction'=>'agent',
	            'flag_read'=>'1',
	            'userid'=>$userid,
	            'channelid'=> $channelid,
	            'message_type'=>$message_type,
	        );
	        //alert($data_chat); exit;
			$this->db->insert('message_chathistory',$data_chat);
			
			$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$pages_access_token;

			$data = [
				'recipient' => $recipient,
				'message' => $messages,
				'message_type'=>'MESSAGE_TAG',
				'tag'=> 'HUMAN_AGENT',
			];
			$post = json_encode($data);
			 // alert($url);
	        // alert($post); exit;
			$headers = array('Content-Type: application/json');
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$result = curl_exec($ch);
			curl_close($ch);
			$result = json_decode($result,true);
			//alert($result); exit;

			return true;
		}else{
			return false;
		}

	}

	public function return_data_token($a_data)
	{
	    if($a_data)
	    {
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"T";
			$a_return["Message"] = $a_data["error"];
			$a_return["total"] = @$a_data["data"]["total"];
			$a_return["offset"] = $a_data["offset"];
			$a_return["limit"] = $a_data["limit"];
			$a_return["cachetime"] = $a_data["time"];
			$a_return["data"] = @$a_data["data"]["data"];
			if ($format!="json" && $format!="xml"){
				$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
				$this->response($a_return, 200); // 200 being the HTTP response code
			}
	    }
	    else
	    {
	      	$this->response(array('error' => 'Couldn\'t find any Building!'), 404);
	    }
	}

}