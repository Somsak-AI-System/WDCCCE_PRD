<?php // callback.php
//header("Content-Type: text/html; charset=utf-8");
defined('BASEPATH') or exit('No direct script access allowed');
require_once('src/OAuth2/Autoloader.php');
require APPPATH . '/libraries/REST_Controller.php';

class  Linemessage extends REST_Controller
{

	function __construct()
	  {
	    parent::__construct();
	    $this->load->library('memcached_library');
		$this->load->library('crmentity');
	    $this->load->database();
		$this->load->library("common");
		$this->load->model("linemessage_model");
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

	public function getmessage_post(){

		// $access_token = 'rV062cOQi/wf14o/R+SNgpHb3DQWawt5j4gLz8HTbAWcMTw1rwUew6Qol1yNxbhBDO7VqhnLphe0eax87yCSJPKJ3vPCNjPmpqF4Ptfi2Yx2xFZEwamkTbahQIgfJBCmoW8fA4IuuQSVx/eZabxyuFGUYhWQfeY8sLGRXgo3xvw=';
		//$access_token = 'ub5SR9vzRhqbh6Gvf5vaZ7RlZoBfcjvvi7+CW2SfC0zh6RJL35FpCEyGCHd0ww3qDO7VqhnLphe0eax87yCSJPKJ3vPCNjPmpqF4Ptfi2Yw+s6TMw/DAW9uiFkeOu+AMqbzy0lf1oLRvJ1OkqFQTr1GUYhWQfeY8sLGRXgo3xvw=';

		// Get POST body content
		$content = file_get_contents('php://input');
		// $dataJson =  $this->input->get();
		// Parse JSON
		//$data = json_decode($content, true);
		$data = json_decode($content, true);
		//alert($data);exit;
		$events = $data['events'];

		$event_type ="";
		$replyToken ="";
		$userid ="";
		$user_type ="";
		$mode ="";
		$messages ="";
		$messages_type ="";
		$messages_id ="";
		$destination ="";
		$userid =  "";
		$destination ="";
		$stickerid = "";
		$packageid = "";

		//$stickerid =  "";
		//$packageid =  "";
		$filename =  "";
		$filesize =  "";

		$displayName ="";
		$pictureUrl ="";
		$language ="";
		$destination = "";
		// Validate parsed JSON data
		if (!is_null($events['events'])) {
			/*$access_token = 'ub5SR9vzRhqbh6Gvf5vaZ7RlZoBfcjvvi7+CW2SfC0zh6RJL35FpCEyGCHd0ww3qDO7VqhnLphe0eax87yCSJPKJ3vPCNjPmpqF4Ptfi2Yw+s6TMw/DAW9uiFkeOu+AMqbzy0lf1oLRvJ1OkqFQTr1GUYhWQfeY8sLGRXgo3xvw=';*/
			// Loop through each event
			foreach ($events['events'] as $event) {

				// Get text sent
				$event_type = $event['type'];
				$replyToken = $event['replyToken'];
				$userid = $event['source']['userId'];
				$user_type = $event['source']['type'];
				$mode = $event['mode'];
				$messages = $event['message']['text'];
				$messages_type = $event['message']['type'];
				$messages_id = $event['message']['id'];
				$destination = $events['destination'];	

				$this->db->select("*");
				$this->db->where('destination',$destination);
				$query_socail = $this->db->get('aicrm_social_config');
				$data_socail = $query_socail->result_array();
				
				$channelid = @$data_socail[0]['id'];
				$channel_name = @$data_socail[0]['name'];
				$access_token = @$data_socail[0]['access_token'];

				if($messages_type =="sticker"){
					//$stickerid = $event['message']['stickerId'];	
					//$packageid = $event['message']['packageId'];
					$stickerid = $event['message']['stickerId'];
					$packageid = $event['message']['packageId'];

					$messages = "https://stickershop.line-scdn.net/stickershop/v1/sticker/".$stickerid."/android/sticker.png";
					
				}else if($messages_type =="location"){
					$address = $event['message']['address'];
					$latitude = $event['message']['latitude'];
					$longitude = $event['message']['longitude'];
					//$enter = "";
					$map = "https://www.google.com/maps?q=".$latitude.",".$longitude;
					//$map = "https://www.google.co.th/maps/place/".$latitude.",".$longitude."./@".$latitude.",".$longitude.",19z/";
					$messages = $event['message']['address']." ".$map;


				}elseif($messages_type =="image" || $messages_type =="video" || $messages_type =="audio" || $messages_type =="file"){	

					$url_img = "https://api-data.line.me/v2/bot/message/".$messages_id."/content";
					// $post = json_encode($data);
					$headers = array('Content-Type: application/json', 'Authorization: Bearer '.$access_token);
					$ch = curl_init($url_img);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					// curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					$result = curl_exec($ch);
					$err = curl_error($ch);
					curl_close($ch);

					if($err){

					}else{

						if($messages_type =="image"){

							$folderPath = "upload_line/image/";
							$file = $folderPath.uniqid().'.png';
							file_put_contents("../".$file, $result);
							$filepath = "https://".$_SERVER['HTTP_HOST']."/".$file;
							$messages = $filepath;
							// alert($messages);exit;

						}elseif($messages_type =="video"){
							$folderPath = "upload_line/video/";
							$file = $folderPath.uniqid().'.mp4';
							file_put_contents("../".$file, $result);
							$filepath = "https://".$_SERVER['HTTP_HOST']."/".$file;
							$messages = $filepath;
							// alert($messages);exit;

						}elseif($messages_type =="audio"){
							$folderPath = "upload_line/audio/";
							$file = $folderPath.uniqid().'.mp3';
							file_put_contents("../".$file, $result);
							// $filenew =  $folderPath.uniqid().'.m4a';
							// file_put_contents("../".$filenew , $result);
							$filepath = "https://".$_SERVER['HTTP_HOST']."/".$file;
							$messages = $filepath;
							// alert($messages);exit;
							//alert($file);exit;

						}elseif($messages_type =="file"){
							$fileName = $event['message']['fileName'];
							$folderPath = "upload_line/file/";
							$file = $folderPath.uniqid()."_".$fileName;
							file_put_contents("../".$file, $result);
							$filepath = "https://".$_SERVER['HTTP_HOST']."/".$file;
							$messages = $filepath;
							// alert($messages);exit;
							//alert($file);exit;

						}

						// $result = json_decode($result,true);
					}
				}

					// alert($messages_id);
					//exit;		
				if(!is_null($userid)){

					// Make a POST Request to Line user Profile 
					// $userid =  "U0835aa50283c11096e0b2ecc0d2167d9";
					// $userid =  "Ufdf5971167fc74eea8ba828420a57e5c";
					
					$url = "https://api.line.me/v2/bot/profile/".$userid;
					
					// // $post = json_encode($data);
					$headers = array('Content-Type: application/json', 'Authorization: Bearer '.$access_token);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					// curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					$result = curl_exec($ch);
					curl_close($ch);
					$result = json_decode($result,true);
					// alert($result);exit;
					if(!empty($result)){
						//$name = $result['displayName'];
						//$displayName = $result['displayName'];
						$name = str_replace("'","",$result['displayName']);
						$displayName = str_replace("'","",$result['displayName']);
						$pictureUrl = $result['pictureUrl'];
						$language = $result['language'];
						// convert อักขระ
						// $displayName = addslashes($displayName);
						// replace อักขระ
						// $displayName = str_replace ("'","\'",$name);
						//$displayName = str_replace("'","",$displayName);
					}
				}
			}

			//$destination
			if(!is_null($userid)){

				$sql_getcustomer = "SELECT * from message_customer WHERE customerno='".$userid."' AND channelid = '".$channelid."' ";
				$query_getcustomer = $this->db->query($sql_getcustomer);
				$data_getcustomer = $query_getcustomer->result_array();

				if(!empty($data_getcustomer)){

					$sql_customer = " UPDATE message_customer SET startchatting = '".date('Y-m-d H:i:s')."' ,custfirstname='".$displayName."'   , channel = 'line', socialname='".$displayName."' , pictureurl='".$pictureUrl."' ,modifiedtime = '".date('Y-m-d H:i:s')."' , deleted = 0 WHERE customerno = '".$userid."' AND channelid = '".$channelid."' ";				
				}else{
					//Insert Lead
					$tab_name_lead = array('aicrm_crmentity','aicrm_leaddetails','aicrm_leadsubdetails','aicrm_leadaddress','aicrm_leadscf');
					$tab_name_index_lead = array('aicrm_crmentity'=>'crmid','aicrm_leaddetails'=>'leadid','aicrm_leadsubdetails'=>'leadsubscriptionid','aicrm_leadaddress'=>'leadaddressid','aicrm_leadscf'=>'leadid');
					$module_leads = "Leads";
					$crmid = "";
					$action = 'add';
					
					$data[0]['lead_no'] = "";
					$data[0]['firstname'] = "Line - ".$displayName;
					$data[0]['lastname'] = '';
					$data[0]['social_channel'] = 'Line';
					$data[0]['social_id'] = $userid;
					$data[0]['social_name'] = $displayName;
					//$data[0]['newsocialname'] = $displayName;
					$data[0]['first_contact_date'] = date('Y-m-d');
					//$data[0]['social_status'] = 'New';
					$data[0]['smownerid'] = '19330';
					$data[0]['chat_status'] = 'อินบ็อกซ์';
					$data[0]['line_oa_facebook_fan_page_name'] = $channel_name;
					$data[0]['leadsource'] = 'Line';
					$data[0]['leaddate'] = date('Y-m-d');
					$data[0]['email_consent'] = 1;
					$data[0]['sms_consent'] = 1;
					$data[0]['main_contact_channel'] = 'Line';

					list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module_leads,$crmid,$action,$tab_name_lead,$tab_name_index_lead,$data,'1');
					
					$sql_customer = "INSERT INTO message_customer (customerno, custfirstname, custsubject, custstartlogin, startchatting,  skillbaseid,channel,socialname,pictureurl,parentid,module,createdate,modifiedtime,deleted,channelid)
					VALUES ('".$userid."','".$displayName."',1,'".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."' ,'1','line','".$displayName."','".$pictureUrl."','".@$crmid."','Leads','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."' ,0 ,'".$channelid."') ";
				}

				// alert($sql_customer);
				// alert($this->db->query($sql_customer));exit;
				if($this->db->query($sql_customer)){

					$sql_chat = "SELECT chatno from message_chathistory 
					WHERE customerno='".$userid."' AND channelid = '".$channelid."' order by chatno desc limit 0,1";
					$query_chat = $this->db->query($sql_chat);
					$data_chat = $query_chat->result_array();

					$sql_customer = "SELECT customerid, parentid, module,contactid from message_customer WHERE customerno= '".$userid."' AND channelid = '".$channelid."' ";
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
		  						$tab_name_index_acc = array('aicrm_crmentity'=>'crmid','aicrm_account'=>'accountid','aicrm_accountbillads'=>'accountaddressid','aicrm_accountshipads'=>'accountaddressid','aicrm_accountscf'=>'accountid');

								$module_account = "Accounts";
								$parentid = $parentid;
								$action = 'edit';
				  				$data[0]['chat_status'] = 'อินบ็อกซ์';
								$this->crmentity->Insert_Update($module_account,$parentid,$action,$tab_name_acc,$tab_name_index_acc,$data,'1');*/

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

						//$d_customer = $data_crm->result_array();
						//$chat_status = @$d_customer[0]['chat_status'];
						
						if($chat_status == 'เสร็จสิ้น'){

							$sql_max = "select max(sla_group)+1 as sla_group from aicrm_sla where customerid ='".$customerid."';";
							$query_max = $this->db->query($sql_max);
							$data_sla = $query_max->result_array();
							$sla_group = @$data_sla[0]['sla_group'];

							/*$sql_sla = "INSERT INTO aicrm_sla (crmid,module,customerid,chat_status,updatedate,userid,sla_group) VALUES ( '".$parentid."','".$module."','".$customerid."','อินบ็อกซ์','".date('Y-m-d H:i:s')."','1' ,'".$sla_group."');";*/
							if($module == 'Accounts'){
								$sql_sla = "INSERT INTO aicrm_sla (crmid,module,customerid,chat_status,updatedate,userid,sla_group) VALUES ( '".$contactid."','Contacts','".$customerid."','อินบ็อกซ์','".date('Y-m-d H:i:s')."','1' ,'".$sla_group."');";
							}else{
								$sql_sla = "INSERT INTO aicrm_sla (crmid,module,customerid,chat_status,updatedate,userid,sla_group) VALUES ( '".$parentid."','".$module."','".$customerid."','อินบ็อกซ์','".date('Y-m-d H:i:s')."','1' ,'".$sla_group."');";
							}
							$this->db->query($sql_sla);
						}

					}

					// alert($data_chat);exit;
					if(!empty($data_chat)){
						$chatno = $data_chat[0]['chatno'];
						$nextchatno = $chatno+1;
						$sql_chat = "INSERT INTO message_chathistory (customerid ,chatno,customerno, chatactionname, message, messagetime, isagent, channel,messageaction , message_id,message_type,sticker_id,package_id)
						VALUES ('".$customerid."','".$nextchatno."','".$userid."','".$displayName."', '".$messages."','".date('Y-m-d H:i:s')."','2' ,'line','customer' , '".$messages_id."','".$messages_type."' ,'".$stickerid."','".$packageid."') ";
						//echo $sql_chat;exit;
						$this->db->query($sql_chat);

					}else{
						$sql_chat = "INSERT INTO message_chathistory (customerid ,chatno,customerno, chatactionname, message, messagetime, isagent, channel,messageaction , message_id,message_type,sticker_id,package_id)
						VALUES ('".$customerid."','1','".$userid."','".$displayName."', '".$messages."','".date('Y-m-d H:i:s')."','2' ,'line','customer' , '".$messages_id."','".$messages_type."' ,'".$stickerid."' ,'".$packageid."') ";
						$this->db->query($sql_chat);

					}
					/*Send message to socket.io*/		
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

	}

	/*public function emit_socket($customerid = '',$displayName=''){

		if($customerid == '' ){
			return false;
		}
		
		$data = $this->linemessage_model->get_list($customerid); //alert($data['result']['data']); exit;
		$data_message=$this->linemessage_model->get_list_detail($customerid);//alert($data_message['result']['data']); exit;

		$msg = array(
			"newchat" => $data['result']['data'],
			"newmessage" => $data_message['result']['data'],
			"displayName" =>$displayName
		);
		
		$port = $this->config->item('socketIOPort');

		require_once '/SocketIO.php';
		//$client = new SocketIO('localhost',$port);
		$client = new SocketIO('moaioc.moai-crm.com',$port);
		//$client = new SocketIO('localhost',$port);
		$client->setQueryParams([
		    'token' => 'edihsudshuz',
		    'id' => '8780',
		    'cid' => '344',
		    'cmp' => 2339
		]);
		
		$success = $client->emit('joinRoom_user', ['agentid' => $displayName,'customerid' => $customerid ,'msg'=>$msg]);
		if(!$success)
		{
		    var_dump($client->getErrors());
		}
		else{
		    var_dump("Success");
		}
		return true;
	}*/

	public function sendmessage_post(){

		

		$request_body = file_get_contents('php://input');
		$dataJson     = json_decode($request_body,true);
		$a_data = $dataJson;

		$this->common->_filename= "Line_Sendmessage";
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
		$customerid = @$a_data["customerid"];
		$userid = @$a_data['userid'];

		$data = @$a_data["data"];

		if($channel=="line"){
			$sendmessage = $this->SendMessageLine($data,$channel,$customerid,$userid);
			if($sendmessage==true){

				/*Send message to socket.io*/
				$this->lib_socket->emit_socket_detail($customerid,$userid);

				$response_data['Type']='S';
				$response_data['Message']='Line Send Success';
				$response_data['cachetime']=date('Y-m-d H:i:s');

			}else{

				$response_data['Type']='E';
				$response_data['Message']='Line send Fail';
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


	public function SendMessageLine($data="",$channel="",$customerid="",$userid="")
	{
		if($data==""){
			return false;
		}
		$access_token = '';

		$this->db->select("aicrm_social_config.*");
		$this->db->join('aicrm_social_config', 'aicrm_social_config.id = message_customer.channelid',"inner");
		$this->db->where('message_customer.customerid',$customerid);
		$query_socail = $this->db->get('message_customer');
		$data_socail = $query_socail->result_array();

		$access_token = @$data_socail[0]['access_token'];
		/*select aicrm_social_config.* from message_customer 
		INNER JOIN aicrm_social_config on aicrm_social_config.id = message_customer.channelid
		WHERE message_customer.customerid = 5;*/
		//alert($access_token); exit;
		// $access_token = 'rV062cOQi/wf14o/R+SNgpHb3DQWawt5j4gLz8HTbAWcMTw1rwUew6Qol1yNxbhBDO7VqhnLphe0eax87yCSJPKJ3vPCNjPmpqF4Ptfi2Yx2xFZEwamkTbahQIgfJBCmoW8fA4IuuQSVx/eZabxyuFGUYhWQfeY8sLGRXgo3xvw=';
		//$access_token = 'ub5SR9vzRhqbh6Gvf5vaZ7RlZoBfcjvvi7+CW2SfC0zh6RJL35FpCEyGCHd0ww3qDO7VqhnLphe0eax87yCSJPKJ3vPCNjPmpqF4Ptfi2Yw+s6TMw/DAW9uiFkeOu+AMqbzy0lf1oLRvJ1OkqFQTr1GUYhWQfeY8sLGRXgo3xvw=';
		

		$message_type = @$data['message_type'];
		// $type = "message";
		$userId = @$data['socialid'];
		
		// $replyToken = "24ca704a5d5d4ff98934020282c5e564";
		$text = @$data['message'];

		if($message_type=="text"){

			$messages = [
			'type' => $message_type, //important
			'text' => @$data['message']  //important
			];

		}elseif($message_type=="sticker"){

			$messages = [
			'type' => $message_type, 
			'packageId' => @$data['packageId'], // เลขชุดของสติ๊กเกอร์
			'stickerId' => @$data['stickerId']  // เลขรหัสของสติ๊กเกอร์
			];
			//https://stickershop.line-scdn.net/stickershop/v1/sticker/13/android/sticker.png
			$text = 'https://stickershop.line-scdn.net/stickershop/v1/sticker/'.@$data['stickerId'].'/android/sticker.png';
			// ดู packageId และ stickerId ทั้งหมดที่ LINE เตรียมไว้ https://developers.line.biz/media/messaging-api/sticker_list.pdf
		}elseif($message_type=="image"){

			$messages = [
			'type' => $message_type, 
			'originalContentUrl' => @$data['message'], // URL ของรูปภาพที่จะแสดงเมื่อผู้ใช้คลิกรูปพรีวิว ทั้งหมดจะต้องเป็น HTTPS เท่านั้น
			'previewImageUrl' => @$data['message']  // URL ของรูปภาพพรีวิว ทั้งหมดจะต้องเป็น HTTPS เท่านั้น
			];
			/*$messages = [
			'type' => $message_type, 
			'originalContentUrl' => @$data['originalContentUrl'], // URL ของรูปภาพที่จะแสดงเมื่อผู้ใช้คลิกรูปพรีวิว ทั้งหมดจะต้องเป็น HTTPS เท่านั้น
			'previewImageUrl' => @$data['previewImageUrl']  // URL ของรูปภาพพรีวิว ทั้งหมดจะต้องเป็น HTTPS เท่านั้น
			];*/
			
		}elseif($message_type=="video"){

			$messages = [
			'type' => $message_type, 
			'originalContentUrl' => @$data['originalContentUrl'], // URL ของวิดีโอที่มีนามสกุลเป็น MP4
			'previewImageUrl' => @$data['previewImageUrl'],  // URL ของรูปภาพพรีวิว
			'trackingId' => @$data['trackingId']  
			];

			/* trackingId: ID ที่ใช้สำหรับWebhook event ประเภท videoPlayComplete โดยสามารถกำหนด (a-z, A-Z, 0-9) และสัญลักษณ์ (-.=,+*()%$&;:@{}!?<>[]) ได้ไม่เกิน 100 ตัวอักษร */
			
		}elseif($message_type=="audio"){

			$messages = [
			'type' => $message_type, 
			'originalContentUrl' => @$data['originalContentUrl'], // URL ของไฟล์เสียง เช่น mp3 และ m4a โดยมีความยาวไม่เกิน 1 นาที และขนาดไม่เกิน 10MB
			'duration' => @$data['duration']  // ความยาวของไฟล์เสียง หน่วยเป็น milliseconds
			];
			
		}elseif($message_type=="file"){

			$ms = [
				"type" => "button",
				"style" => "link",
				"action" => [
					"type" => "uri",
					"label" => "ไฟล์แนบ (download)",
	                "uri" => @$data['message']
				]
			];
			/*$ms = [
				"type" => "button",
				"style" => "link",
				"action" => [
					"type" => "uri",
					"label" => "ไฟล์แนบ (download)",
	                "uri" => @$data['originalContentUrl']
				]
			];*/

			//vertical,horizontal
			$contents = [
				"type"=> "bubble",
				"body"=> [
					"type"=> "box",
					"layout"=> "vertical",
					"spacing"=> "md",
					"contents"=> [
							0 => $ms
						]
					]
			];


			$messages = [
			'type' => 'flex',
			"altText"=> "new message",
			'contents' => $contents  
			];
			
		}
		// alert($customerid);
		// alert($userid);
		// alert($message_type);
		// alert($messages);exit;


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
	            'message_type'=>$message_type,
	        );
	        //alert($data_chat); exit;
			$this->db->insert('message_chathistory',$data_chat);
			
			$url = 'https://api.line.me/v2/bot/message/push';
			$data = [
				'to' => $userId,
				'messages' => [$messages],
			];

			//alert(json_encode($data));exit;

			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
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
			//$result = json_decode($result,true);
			//alert($result);exit;
			 

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