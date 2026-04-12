<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once('src/OAuth2/Autoloader.php');
require APPPATH . '/libraries/REST_Controller.php';

class Upload extends REST_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->library('memcached_library');
    $this->load->library('crmentity');
    $this->load->database();
    $this->load->library("common");
    // $this->load->model("image_model");
    $this->_limit = 10;
    $this->_module = "Documents";
    $this->_format = "array";
    $this->_return = array(
      'Type' => "S",
      'Message' => "upload Complete",
      'cache_time' => date("Y-m-d H:i:s"),
      'data' => array(
        'Crmid' => null,
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


    public function upload_file_post(){

      $this->load->library('upload');
      global $root_directory;

      $a_param =  $this->input->post();

      $module = "Documents Attachment";
      $userid = $a_param['userid'];
      $crmid = $a_param['crmid'];

      $a_param['file']=$_FILES;

      $max_size = $this->config->item("filesize");

      if($_FILES['file']['size']<=$max_size){
        $path     = "storage/".date("Y")."/".date("F")."/week".date("W")."";
        $url = "http://".$_SERVER['HTTP_HOST']."/moai/".$path."/";

        $folder = $root_directory;
        $temp_path  = $folder."/".$path;

        if (!is_dir($temp_path)) {

          $folder2 = $folder.'/storage';
          // Find each subfolder in storage's image
          $storage_folder = explode('storage/' , $temp_path);
          $new_folder = explode('/' , $storage_folder[1]);

          for($i=0; $i<=count($new_folder)-2; $i++){

            //Create subfolder if it not found folder
            $folder2 = $folder2."/".$new_folder[$i];

            if (!is_dir($folder2)) {
              $old = umask(0);
              mkdir($folder2 ."/" ,0777,true);
              umask($old);
            }
          }

          $old = umask(0);
          mkdir($temp_path ."/" ,0777,true);
          umask($old);
        }//if check folder

        $photo = $_FILES['file']['name'];
        $file_type = $_FILES['file']['type'];
        $type = explode('.',$photo);
        $image_name = $photo;
        // $type = explode('.',$photo);
        // $image_name = $photo;
        $smownerid="";
        $file_size = $_FILES['file']['size'];

        $config['file_name'] = $image_name;

        $config['upload_path']=$temp_path;

        $config['allowed_types']='gif|jpg|png|pdf|zip|xlsx|docx|jpeg|txt|ppt|pptx|doc|xls|msword';
        $config['max_size']=3145728;
        $config['max_width']=10000;
        $config['max_height']=10000;
        $config['overwrite']=TRUE;
        $config['max_filename']=25;

        $config['source_image']=$temp_path."/".$image_name;
        // $config['new_image']='assets/uploads/thumbs/'.$photo;
        $config['maintain_ratio']=TRUE;
        $config['width']=150;
        $config['height']=150;

        $tis620 = iconv("UTF-8", "windows-874", $_FILES['file']['name'] );
        $_FILES['file']['name'] = $tis620;
        $config['file_name']= $tis620;
        $config['source_image']=$temp_path."/".$tis620;

        $this->upload->initialize($config);

        if(!$this->upload->do_upload('file')){
          $error = $this->upload->display_errors();
          $this->response(array(
            'error' => $error
          ), 404);
        }else {
          $sql_next_id = "SELECT max(id)+1 as next_id FROM aicrm_crmentity_seq";
          $query_next_id = $this->db->query($sql_next_id);
          $result_next_id = $query_next_id->result(0);
          $next_id = $result_next_id[0]['next_id'];

          $sql_smownerid = "SELECT aicrm_crmentity.smownerid,aicrm_notes.title as name,aicrm_notes.note_no as no
          FROM aicrm_crmentity
          INNER JOIN aicrm_notes on aicrm_notes.notesid = aicrm_crmentity.crmid
          WHERE aicrm_crmentity.crmid='".$crmid."' ";
          $query_smownerid = $this->db->query($sql_smownerid);
          $result_smownerid = $query_smownerid->result(0);
          $smownerid = $result_smownerid[0]['smownerid'];
          $name = $result_smownerid[0]['name'];
          $no = $result_smownerid[0]['no'];

          if($smownerid==""){
            $smownerid=$userid;
          }


          $sql_insert_new1 = "INSERT INTO aicrm_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) VALUES ('".$next_id."', '".$userid."', '".$smownerid."', '".$module."', '', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')";
          $sql_insert_new2 = "INSERT INTO aicrm_seattachmentsrel (crmid , attachmentsid) VALUES ('".$crmid."' , '".$next_id."')";
          $sql_update = "UPDATE aicrm_crmentity_seq SET id='".$next_id."'";

          $this->db->query($sql_insert_new1);
          $this->db->query($sql_insert_new2);
          $this->db->query($sql_update);

          $sql2 = "INSERT INTO aicrm_attachments (attachmentsid, name, type, `path`) VALUES ('".$next_id."' , '".$image_name."', '".$file_type."', '".$path."/')";
          $this->db->query($sql2);

          $sql_update_Documents = "UPDATE aicrm_notes SET filename='".$image_name."' , filetype='".$file_type."' , filesize='".$file_size."' ,filelocationtype='I'  WHERE notesid='".$crmid."' ";
          $this->db->query($sql_update_Documents);

          $this->load->helper('file');
          $this->load->library('image_lib');
          // $config['image_library']='gd2';
          $config['source_image']=$temp_path."/".$image_name;
          $config['maintain_ratio']=TRUE;
          $config['width']=150;
          $config['height']=150;

          $this->image_lib->clear();
          $this->image_lib->initialize($config);

          if(!$this->image_lib->resize()){
            $error=$this->image_lib->display_errors();

          }

          $data = array(
            'crmid' => $crmid,
            'name' => $name,
            'no' => $no,
            'modifiedby' => $userid,
            'modifiedtime' => date("Y-m-d H:i:s"),
          );


          $a_return['data']=$data;


          $response_data = array_merge($this->_return,$a_return);

          $log_filename = "Upload_file_Documents";
          $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
          // $this->common->_filename= "Insert_Calendar";
          $this->common->_filename= $log_filename;
          $this->common->set_log($url,$a_param,$response_data);


          $this->response($response_data, 200);

        }
      }else{
        $this->response(array(
        'error' => 'file size maximum !'
        ), 404);

      }

  }


    public function attachfile_post(){

      if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
      $a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;

            $this->return_data_token($a_data);
      }

      $this->load->library('upload');
      global $root_directory;

      $a_param =  $this->input->post();

      // $module = "Documents Attachment";
      $userid = $a_param['userid'];
      $channel = $a_param['channel'];
      // $crmid = $a_param['crmid'];

      $a_param['file']=$_FILES;
      $file_type = split("/", $_FILES['file']['type']);

      // $max_size = $this->config->item("filesize");
      $max_size = 15728640; // max set from web crm 15

      // alert($file_type);
      // alert($a_param['file']);exit;

      //Maximum file size for send to Line https://developers.line.biz/en/news/2020/05/12/messaging-api-update-may-2020/
      if($file_type[0]=="image"){
        /* File format = JPEG, PNG  ,Max image size = No limit  ,Max file size  = 10MB  */
        $max_size = 10485760;

      }elseif ($file_type[0]=="video" || $file_type[0]=="audio") {
        /* Max image size = No limit  ,Max file size  = 200MB  */
        $max_size = 209715200;
        
      }else{
        /* Attachment Max file size  = 4MB  */
        $max_size = 4194304;

      }

      if($_FILES['file']['name'] != ''){
        //alert($_FILES['file']);exit;
        if($_FILES['file']['size']<=$max_size){
          $path     = "upload/".date("Y")."/".date("F");
          $url = "https://".$_SERVER['HTTP_HOST']."/".$path."/";

          $folder = $root_directory;
          $temp_path  = $folder."".$path;

          if (!is_dir($temp_path)) {

            $folder2 = $folder.'/upload';
            // Find each subfolder in storage's image
            $storage_folder = explode('upload/' , $temp_path);
            $new_folder = explode('/' , $storage_folder[1]);

            for($i=0; $i<=count($new_folder)-2; $i++){

              //Create subfolder if it not found folder
              $folder2 = $folder2."/".$new_folder[$i];

              if (!is_dir($folder2)) {
                $old = umask(0);
                mkdir($folder2 ."/" ,0777,true);
                umask($old);
              }
            }

            $old = umask(0);
            mkdir($temp_path ."/" ,0777,true);
            umask($old);
          }//if check folder

          $photo = $_FILES['file']['name'];
          $file_type = $_FILES['file']['type'];
          $type = explode('.',$photo);
          $image_name = uniqid()."_".$photo;
          $file_size = $_FILES['file']['size'];
          $ext = $type[1];
          // alert($type);exit;
          //echo $image_name; exit;
          $image_name = str_replace(" ","_",$image_name);
          $image_name = preg_replace("/[^a-z0-9ก-เ?-?\_\-\.]/i", '', $image_name);
          //echo $image_name; exit;
          $config['file_name'] = $image_name;
          $config['file_type'] = $file_type;
          $config['file_path'] = $temp_path;
          $config['full_path'] = $temp_path."/".$image_name;
          $config['file_size'] = $file_size;
          $config['file_ext']  = ".".$ext;

          $config['upload_path']=$temp_path;

          $config['allowed_types']='*';
          // $config['allowed_types']='gif|jpg|png|pdf|zip|xlsx|docx|jpeg|txt|ppt|pptx|doc|xls|msword';
          $config['max_size']=$max_size;
          $config['max_width']=10000;
          $config['max_height']=10000;
          $config['overwrite']=TRUE;
          $config['max_filename']=200;

          $config['source_image']=$temp_path."/".$image_name;
          // $config['new_image']='assets/uploads/thumbs/'.$photo;
          $config['maintain_ratio']=TRUE;
          //$config['width']=150;
          //$config['height']=150;

          //$tis620 = iconv("UTF-8", "windows-874", $image_name);
          //$tis620 = iconv("windows-874", "TIS-620", $tis620);
          $tis620 = $image_name;
          //$tis620 = iconv("UTF-8", "tis620", $image_name);
          //echo $tis620; exit;
          $_FILES['file']['name'] = $tis620;
          $config['file_name']= $tis620;
          $config['source_image']=$temp_path."/".$tis620;

          $this->upload->initialize($config);

          //alert($this->upload->initialize($config));exit;
          //alert($this->upload->do_upload('file'));exit;

          if(!$this->upload->do_upload('file')){
            $error = $this->upload->display_errors();
             // alert("Err");
            $this->response(array(
              'error' => $error
            ), 404);
          }else {
            // alert("S");
            $this->load->helper('file');
            $this->load->library('image_lib');
            // $config['image_library']='gd2';
            $config['source_image']=$temp_path."/".$image_name;
            $config['maintain_ratio']=TRUE;
            //$config['width']=150;
            //$config['height']=150;

            $this->image_lib->clear();
            $this->image_lib->initialize($config);

            if(!$this->image_lib->resize()){
              $error=$this->image_lib->display_errors();

            }
            $data = array(
              //'crmid' => $crmid,
              'url' => $url.$tis620,
              //'no' => $no,
              'modifiedby' => $userid,
              'modifiedtime' => date("Y-m-d H:i:s"),
            );
            $a_return['data']=$data;
            $response_data = array_merge($this->_return,$a_return);

            $log_filename = "Upload_file_Documents";
            $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            // $this->common->_filename= "Insert_Calendar";
            $this->common->_filename= $log_filename;
            $this->common->set_log($url,$a_param,$response_data);
            $this->response($response_data, 200);

          }
        }else{
          $response_data['Type']='E';
          $response_data['Message']='file size maximum';
          $response_data['total']='0';
          $response_data['offset']='0';
          $response_data['limit']='0';
          $response_data['cachetime']=date('Y-m-d H:i:s');
          $response_data['data']='';
          $this->response($response_data, 200);
          /*$this->response(array(
            'error' => 'file size maximum !'
          ), 404);*/

        }

      }else{
        
        $response_data['Type']='E';
        $response_data['Message']='Upload Fail';
        $response_data['total']='0';
        $response_data['offset']='0';
        $response_data['limit']='0';
        $response_data['cachetime']=date('Y-m-d H:i:s');
        $response_data['data']='';
        $this->response($response_data, 200);
        /*
        $this->response(array(
          'error' => 'file size maximum !'
        ), 404);*/
      
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
