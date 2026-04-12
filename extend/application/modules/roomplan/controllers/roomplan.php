<?php
if ( !defined('BASEPATH') )
exit('No direct script access allowed');
class Roomplan extends MY_Controller
{

  private $description, $title, $keyword, $screen,$modulename;
  public function __construct()
  {
    parent::__construct();
    $meta = $this->config->item('meta');
    $lang = $this->config->item('lang');
    $this->title = $meta["default"]["title"];
    $this->keyword = $meta["default"]["keyword"];
    $this->description = $meta["default"]["description"];
    $this->load->library('curl');
    $this->lang->load('ai',$lang);
    $this->load->config('api');
    $this->url_service= $this->config->item('service');
    $this->url_crm = $this->config->item('path_crm');
    $this->url_path = $this->config->item('path');
	  $this->load->model('roomplan_model');
  }

  public function index()
  {
    $this->template->title("Plans | Smart Inspection"); // 11 words or 70 characters
    $this->template->screen('Plans', $this->screen);
    $this->template->modulename('room_plan', $this->modulename);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_layout('theme');
    $data = array();
    $this->template->build('index', $data, FALSE, TRUE);
  }
  
  public function getroomplan(){

    $post_data = $this->input->post();
    $roomplan_name = @$post_data['roomplan_name'];

    $json_url = $this->url_service."roomplan/list_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Roomplan",
        "crmid" => "",
        "roomplan_name" => $roomplan_name,
        "limit" => "0",
        "offset" => "0",
        'userid' => $this->session->userdata('user.user_id'),
    );
    
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    if($result['Type'] == 'S'){
        $data['Type'] = $result['Type'];
        $data['data'] = $result['data'];
        $data['message'] = $result['Message'];
    }else{
       $data['Type'] = $result['Type'];
       $data['data'] = array();
       $data['message'] = $result['Message'];
    }
    echo json_encode($data);
  }

  public function create(){

    $post_data = $this->input->post();
    $crmid = $post_data['crmid'];
    $action = $post_data['action'];
    $data = array(
      "roomplan_no"=>'',
      'roomplan_name' => @$post_data['modal_roomplan_name'],
      'modifiedby' => $this->session->userdata('user.user_id'),
      'smownerid' => $this->session->userdata('user.user_id'),
    );

    $json_url = $this->url_service."Roomplan/insert_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Roomplan",
        "crmid" => $crmid,
        'action' => $action,
        'userid' => $this->session->userdata('user.user_id'),
        'data' => $data,
    );

    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    if($result['Type'] == 'S'){

        if(!empty($_FILES['imageUpload']['tmp_name'])){
          
          $file_maximumsize = 2097152; // Maximum size is 2MB
          $path = "storage/" . date("Y") . "/" . date("F") . "/week" . date("W") . "";
          $url = $this->url_path.$path;
          $data = [];
          $data['folder'] = $this->url_crm;
          $folder = $this->url_crm;
          $temp_path = $folder . "/" . $path;

          if (!is_dir($temp_path)) {

              $folder2 = $folder . '/storage';
              // Find each subfolder in storage's image
              $storage_folder = explode('storage/', $temp_path);
              $new_folder = explode('/', $storage_folder[1]);

              for ($i = 0; $i <= count($new_folder) - 2; $i++) {
                  //Create subfolder if it not found folder
                  $folder2 = $folder2 . "/" . $new_folder[$i];

                  if (!is_dir($folder2)) {
                      $old = umask(0);
                      mkdir($folder2 . "/", 0777, true);
                      umask($old);
                  }
              }

              $old = umask(0);
              mkdir($temp_path . "/", 0777, true);
              umask($old);
          }

            $tmp_name = $_FILES['imageUpload']['tmp_name'];
            $file_type = $_FILES['imageUpload']['type'];
            $name = basename($_FILES['imageUpload']['name']);

            $this->load->library('db_api');
            $this->load->database();

            $this->db->select_max('id', 'next_id');
            $this->db->from('aicrm_crmentity_seq');
            $sql = $this->db->get();
            $result_seq = $sql->row_array();
            $next_id = $result_seq['next_id'] + 1;
      
            $new_name = $next_id."_".$name;

            if($action == 'edit'){
              if($post_data['image_url'] == ''){
                  if (move_uploaded_file($tmp_name, $temp_path."/".$new_name)){
                    $userid = $this->session->userdata('user.user_id');
                    $smownerid = $this->session->userdata('user.user_id');
                    $sql_update = "UPDATE aicrm_crmentity_seq SET id='".$next_id."'";
                    $this->db->query($sql_update);
                    $sql_insert = "INSERT INTO aicrm_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) VALUES ('".$next_id."', '".$userid."', '".$smownerid."', 'Roomplan Image', '', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
                    $this->db->query($sql_insert);
                    $sql_att = "INSERT INTO aicrm_attachments (attachmentsid, name, type, `path`) VALUES ('" . $next_id . "' , '" . $new_name . "', '" . $file_type . "', '" . $path . "/')";
                    $this->db->query($sql_att);
                    $crmid = $result['data']['Crmid'];
                    $sql_attrel = "INSERT INTO aicrm_seattachmentsrel (crmid , attachmentsid) VALUES ('".$crmid."' , '".$next_id."')";
                    $this->db->query($sql_attrel);
                  }
              }else{
                //Edit Image
                //$new_name = end(explode('/',$post_data['image_url']));
              	$new_name = explode('/',$post_data['image_url']);
              	//alert($new_name); exit;
              	$old_image = $folder."/storage/".$new_name[5]."/".$new_name[6]."/".$new_name[7]."/".$new_name[8];
              	move_uploaded_file($tmp_name, $old_image);
                //move_uploaded_file($tmp_name, $temp_path."/".$new_name);
              }
            
            }else{

              if (move_uploaded_file($tmp_name, $temp_path."/".$new_name)){

                $userid = $this->session->userdata('user.user_id');
                $smownerid = $this->session->userdata('user.user_id');

                $sql_update = "UPDATE aicrm_crmentity_seq SET id='".$next_id."'";
                $this->db->query($sql_update);

                $sql_insert = "INSERT INTO aicrm_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) VALUES ('".$next_id."', '".$userid."', '".$smownerid."', 'Roomplan Image', '', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
                $this->db->query($sql_insert);

                $sql_att = "INSERT INTO aicrm_attachments (attachmentsid, name, type, `path`) VALUES ('" . $next_id . "' , '" . $new_name . "', '" . $file_type . "', '" . $path . "/')";
                $this->db->query($sql_att);

                $crmid = $result['data']['Crmid'];
                $sql_attrel = "INSERT INTO aicrm_seattachmentsrel (crmid , attachmentsid) VALUES ('".$crmid."' , '".$next_id."')";
                $this->db->query($sql_attrel);
              }

            }
             
        }//IF 
   
   }//$result['Type'] = S

    echo json_encode($result);
  
  }

  public function deleted(){

    $data = $this->input->post();
    $crmid = $data['crmid'];
    $json_url = $this->url_service."roomplan/insert_content";
    $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Roomplan",
        "crmid" => $crmid,
        'action' => 'edit',
        'userid' => $this->session->userdata('user.user_id'),
        'data' => array(
            'deleted' => 1,
            'modifiedby' => $this->session->userdata('user.user_id'),
         ),
    );

    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    echo json_encode($result);
  }



}
