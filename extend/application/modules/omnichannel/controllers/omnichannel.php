<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class Omnichannel extends MY_Controller
{
	
  public function __construct()
  {
    parent::__construct();
    $meta = $this->config->item('meta');
    $this->title = $meta["default"]["title"];
    $this->keyword = $meta["default"]["keyword"];
    $this->description = $meta["default"]["description"];
    $this->load->library('curl');
    $this->module = 'OmniChannel';
    $this->load->config('api');
    $this->url_service = $this->config->item('service');
  }

  public function index()
  {
    
    $this->template->title('OmniChannel',$this->title); 
    // $this->template->screen('OmniChannel',$this->screen); 
    // $this->template->modulename('omnichannel', $this->modulename);
    // $this->template->set_metadata('description', "OmniChannel"); // 70 words (350 characters)
    // $this->template->set_metadata('keywords', $this->keyword);
    // $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));

    $data['assigned'] = $this->get_assigned();

    $this->template->set_layout('omnichannel');
    // $this->template->build('index');
  	$this->template->build('index',$data);
  }

  public function get_assigned()
  { 
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("omnichannel_model");
    $data = $this->omnichannel_model->get_assigned();
    
    return $data ;
  }

  public function getLeadDetail() {
    $post_data = $this->input->post();

    $crmid = @$post_data['crmid'];
    $json_url = $this->url_service."leads/get_lead";
    
    $fields = array(
      "AI-API-KEY" => "1234",
      "crmid" => $crmid,
      "module" => "Leads",
      "userid" => USERID
    );
    /*echo $json_url;
    echo json_encode($fields);exit;*/
    $data = array();
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    if($result['status'] == 'true'){
        $data['status'] = $result['status'];
        $data['result'] = $result['result'];
        $data['error'] = $result['error'];
    }else{
       $data['status'] = $result['status'];
       $data['result'] = array();
       $data['error'] = $result['error'];
    }
    echo json_encode($data);

  }

  public function create_lead() {

    $post_data = $this->input->post();

    $data[] = array(
      "lead_no" => '',
      "status" => @$post_data['status'],
      "organization" => @$post_data['organization'],
      "nametitle" => @$post_data['nametitle'],
      "fname" => @$post_data['fname'],
      "lname" => @$post_data['lname'],
      "nname" => @$post_data['nname'],
      "gender" => @$post_data['gender'],
      "branch" => @$post_data['branch'],
      "lineid" => @$post_data['lineid'],
      "facebook" => @$post_data['facebook'],
      "adsid" => @$post_data['adsid'],
      "mobile" => @$post_data['mobile'],
      "email" => @$post_data['email'],
      "campaignid" => @$post_data['campaignid'],
      "promotion" => @$post_data['promotion'],
      "smownerid" => @$post_data['smownerid'],
      "model" => @$post_data['model'],
      "start" => @$post_data['start'],
      "wheel" => @$post_data['wheel'],
      "brake" => @$post_data['brake'],
      "year" => @$post_data['year'],
      "color" => @$post_data['color'],
      "village" => @$post_data['village'],
      "addressline" => @$post_data['addressline'],
      "villageno" => @$post_data['villageno'],
      "lane" => @$post_data['lane'],
      "street" => @$post_data['street'],
      "subdistrict" => @$post_data['subdistrict'],
      "district" => @$post_data['district'],
      "province" => @$post_data['province'],
      "postalcode" => @$post_data['postalcode'],
      "remark" => @$post_data['remark'],
    );

    $json_url = $this->url_service."leads/insert_content";
    $fields = array(
      'AI-API-KEY'=>'1234',
      "module" => "Leads",
      "action" => "add",
      "crmid" => "",
      "userid" => USERID,
      "leadid" => "",
      "data" => $data,
    );

    // echo $json_url;
    // echo json_encode($fields);exit;

    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
   
    echo json_encode($result); 

  }

  public function update_lead() { 

    $post_data = $this->input->post();

    $crmid = @$post_data['crmid'];
    $leadid = @$post_data['leadid'];

    $data[] = array(
      "leadstatus" => @$post_data['leadstatus'],
      "deal_name" => @$post_data['deal_name'],
      "salutation" => @$post_data['salutationtype'],
      "firstname" => @$post_data['firstname'],
      "lastname" => @$post_data['lastname'],
      "nickname" => @$post_data['nickname'],
      "gender" => @$post_data['gender'],
      "branch" => @$post_data['branch'],
      "lineid" => @$post_data['lineid'],
      "facebookid" => @$post_data['facebookid'],
      "adsid" => @$post_data['adsid'],
      "mobile" => @$post_data['mobile'],
      "email" => @$post_data['email'],
      "campaignid" => @$post_data['campaignid'],
      "promotionid" => @$post_data['promotionid'],
      "smownerid" => @$post_data['smownerid'],
      "model" => @$post_data['model'],
      "start" => @$post_data['start'],
      "wheel" => @$post_data['wheel'],
      "brake" => @$post_data['brake'],
      "year" => @$post_data['year'],
      "color" => @$post_data['color'],
      "lead_village" => @$post_data['lead_village'],
      "addressline" => @$post_data['addressline'],
      "lead_houseno" => @$post_data['lead_houseno'],
      "lead_villageno" => @$post_data['lead_villageno'],
      "lead_lane" => @$post_data['lead_lane'],
      "lead_street" => @$post_data['lead_street'],
      "lead_subdistrinct" => @$post_data['lead_subdistrinct'],
      "lead_district" => @$post_data['lead_district'],
      "lead_province" => @$post_data['lead_province'],
      "lead_postalcode" => @$post_data['lead_postalcode'],
      "description" => @$post_data['description'],
    );

    $json_url = $this->url_service."leads/insert_content";
    $fields = array(
      'AI-API-KEY'=>'1234',
      "module" => "Leads",
      "action" => "edit",
      "crmid" => $crmid,
      "userid" => USERID,
      "leadid" => $leadid,
      "data" => $data,
    );

    //echo $json_url;
    //echo json_encode($fields);exit;

    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
   
    echo json_encode($result); 

  }

  public function create_deal() {

    $post_data = $this->input->post();

    $data[] = array(
      "deal_no" => '',
      "ordertype" => @$post_data['ordertype'],
      "stage" => @$post_data['stage'],
      "type" => @$post_data['type'],
      "scoring" => @$post_data['scoring'],
      "campaign_name" => @$post_data['campaign_name'],
      "promotion_name" => @$post_data['promotion_name'],
      "smownerid" => @$post_data['smownerid'],
      "accountid" => @$post_data['accountid'],
      "account_name" => @$post_data['account_name'],
      "nname" => @$post_data['nname'],
      "branch" => @$post_data['branch'],
      "mobile" => @$post_data['mobile'],
      "model" => @$post_data['model'],
      "start" => @$post_data['start'],
      "wheel" => @$post_data['wheel'],
      "brake" => @$post_data['brake'],
      "yearmodel" => @$post_data['yearmodel'],
      "color" => @$post_data['color'],
      "village" => @$post_data['village'],
      "addressline" => @$post_data['addressline'],
      "addressline1" => @$post_data['addressline1'],
      "villageno" => @$post_data['villageno'],
      "lane" => @$post_data['lane'],
      "street" => @$post_data['street'],
      "subdistrict" => @$post_data['subdistrict'],
      "district" => @$post_data['district'],
      "province" => @$post_data['province'],
      "postalcode" => @$post_data['postalcode'],
      "description" => @$post_data['description'],
    );

    $json_url = $this->url_service."deal/insert_content";
    $fields = array(
      'AI-API-KEY'=>'1234',
      "module" => "Deal",
      "action" => "add",
      "crmid" => "",
      "userid" => USERID,
      "leadid" => "",
      "data" => $data,
    );

    /*echo $json_url;
    echo json_encode($fields);exit;*/

    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
   
    echo json_encode($result); 

  }

  public function getdeal() {

    $post_data = $this->input->post();
    $crmid = @$post_data['crmid']; 

    $json_url = $this->url_service."deal/list_content";

    $fields = array(
      "AI-API-KEY" => "1234",
      "crmid" => $crmid,
      "module" => "Deal",
      "userid" => USERID
    );

    /*echo $json_url;
    echo json_encode($fields);exit;*/

    $data = array();
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
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

  public function update_deal() {

    $post_data = $this->input->post();
    $crmid = @$post_data['crmid'];

    $data[] = array(
      "ordertype" => @$post_data['ordertype'],
      "stage" => @$post_data['stage'],
      "type" => @$post_data['type'],
      "scoring" => @$post_data['scoring'],
      "campaign_name" => @$post_data['campaign_name'],
      "promotion_name" => @$post_data['promotion_name'],
      "smownerid" => @$post_data['smownerid'],
      "accountid" => @$post_data['accountid'],
      "account_name" => @$post_data['account_name'],
      "nname" => @$post_data['nname'],
      "branch" => @$post_data['branch'],
      "mobile" => @$post_data['mobile'],
      "model" => @$post_data['model'],
      "start" => @$post_data['start'],
      "wheel" => @$post_data['wheel'],
      "brake" => @$post_data['brake'],
      "yearmodel" => @$post_data['yearmodel'],
      "color" => @$post_data['color'],
      "village" => @$post_data['village'],
      "addressline" => @$post_data['addressline'],
      "addressline1" => @$post_data['addressline1'],
      "villageno" => @$post_data['villageno'],
      "lane" => @$post_data['lane'],
      "street" => @$post_data['street'],
      "subdistrict" => @$post_data['subdistrict'],
      "district" => @$post_data['district'],
      "province" => @$post_data['province'],
      "postalcode" => @$post_data['postalcode'],
      "description" => @$post_data['description'],
    );

    $json_url = $this->url_service."deal/insert_content";
    $fields = array(
      'AI-API-KEY'=>'1234',
      "module" => "Deal",
      "action" => "edit",
      "crmid" => $crmid,
      "userid" => USERID,
      "leadid" => "",
      "data" => $data,
    );

    // echo $json_url;
    // echo json_encode($fields);exit;

    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
   
    echo json_encode($result); 

  }

  public function getaccount() {
      $post_data = $this->input->post();
      $crmid = @$post_data['crmid'];
      $json_url = $this->url_service."accounts/get_account";

      $fields = array(
        "AI-API-KEY" => "1234",
        "crmid" => $crmid,
        "module" => "Accounts",
        "userid" => USERID
      );
      /*echo $json_url;
      echo json_encode($fields);exit;*/

      $data = array();
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      
      if($result['status'] == 'true'){
          $data['status'] = $result['status'];
          $data['result'] = $result['result'];
          $data['error'] = $result['error'];
          $data['case'] = $result['case'];
          $data['deal'] = $result['deal'];
      }else{
         $data['status'] = $result['status'];
         $data['result'] = array();
         $data['error'] = $result['error'];
         $data['case'] = 0;
          $data['deal'] = 0;
      }
      echo json_encode($data);

  }

  public function update_account() {
    $post_data = $this->input->post();
    // $crmid = @$post_data['crmid'];
    $crmid = @$post_data['crmid'];

    $birthdate = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['birthdate'])));
    $register_date = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['register_date'])));

    $corporation =(@$post_data['corporation']=='on') ? 1 : 0;
    $blacklist =(@$post_data['blacklist']=='on') ? 1 : 0;
    $data[] = array(
      //"account_no" => '',
      //"accountname" => @$post_data['accountname'],
      "corporation" =>  $corporation,//@$post_data['corporation'],
      "nametitle" => @$post_data['nametitle'],
      "firstname" => @$post_data['firstname'],
      "lastname" => @$post_data['lastname'],
      "nickname" => @$post_data['nickname'],
      "idcardno" => @$post_data['idcardno'],
      "ac_type" => @$post_data['ac_type'],
      "branch" => @$post_data['branch'],
      "career" => @$post_data['career'],
      "birthdate" => $birthdate,
      "mobile" => @$post_data['mobile'],
      "email1" => @$post_data['email1'],
      "fax" => @$post_data['fax'],
      "phone" => @$post_data['phone'],
      "taxid" => @$post_data['taxid'],
      "register_date" => $register_date,
      "erpaccountid" => @$post_data['erpaccountid'],
      "smownerid" => @$post_data['smownerid'],
      "village" => @$post_data['village'],
      "addressline" => @$post_data['addressline'],
      "addressline1" =>@$post_data['addressline1'],
      "villageno" => @$post_data['villageno'],
      "lane" => @$post_data['lane'],
      "street" => @$post_data['street'],
      "subdistrict" => @$post_data['subdistrict'],
      "district" => @$post_data['district'],
      "province" => @$post_data['province'],
      "postalcode" => @$post_data['postalcode'],
      "blacklist" => $blacklist, //@$post_data['blacklist'],
      "description" => @$post_data['description'],
      "remark" => @$post_data['remark'],
    );

    $json_url = $this->url_service."accounts/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Accounts",
      'action' => "edit",
      "crmid" => $crmid,
      "userid" => USERID,
      'data' => $data,
    );

    //echo json_encode($fields);exit;
    //alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function getContacts() {
      $post_data = $this->input->post();
      $crmid = @$post_data['crmid'];

      $json_url = $this->url_service."contacts/get_contacts";

      $fields = array(
        "AI-API-KEY" => "1234",
        "crmid" => $crmid,
        "module" => "Contacts",
        "userid" => USERID
      );
      //echo $json_url;
      // echo json_encode($fields);exit;

      $data = array();
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      
      if($result['status'] == 'true'){
          $data['status'] = $result['status'];
          $data['result'] = $result['result'];
          $data['error'] = $result['error'];
      }else{
         $data['status'] = $result['status'];
         $data['result'] = array();
         $data['error'] = $result['error'];
      }
      echo json_encode($data);
  }

  public function create_contacts() {
    $post_data = $this->input->post();
    // $crmid = @$post_data['crmid'];

    $data[] = array(
      "contact_no" => "",
      "firstname" => @$post_data['firstname'],
      "line_id" => @$post_data['line_id'],
      "mobile" => @$post_data['mobile'],
      "phone_work" => @$post_data['phone_work'],
      "email" => @$post_data['email'],
      "fax" => @$post_data['fax'],
      "alley" => @$post_data['alley'],
      "con_villageno" => @$post_data['con_villageno'],
      "con_village" => @$post_data['con_village'],
      "con_lane" => @$post_data['con_lane'],
      "con_street" => @$post_data['con_street'],
      "subdistrict" => @$post_data['subdistrict'],
      "district" => @$post_data['district'],
      "province" => @$post_data['province'],
      "postalcode" => @$post_data['postalcode'],
      "remark" => @$post_data['remark'],
    );

    $json_url = $this->url_service."contacts/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Contacts",
      'action' => "add",
      "crmid" => "",
      "userid" => USERID,
      'data' => $data,
    );

    
    // echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function update_contact() {
    $post_data = $this->input->post();
    $crmid = @$post_data['crmid'];

    $data[] = array(
      "contact_no" => "",
      "firstname" => @$post_data['firstname'],
      "line_id" => @$post_data['line_id'],
      "mobile" => @$post_data['mobile'],
      "phone_work" => @$post_data['phone_work'],
      "email" => @$post_data['email'],
      "fax" => @$post_data['fax'],
      "alley" => @$post_data['alley'],
      "con_villageno" => @$post_data['con_villageno'],
      "con_village" => @$post_data['con_village'],
      "con_lane" => @$post_data['con_lane'],
      "con_street" => @$post_data['con_street'],
      "subdistrict" => @$post_data['subdistrict'],
      "district" => @$post_data['district'],
      "province" => @$post_data['province'],
      "postalcode" => @$post_data['postalcode'],
      "remark" => @$post_data['remark'],
    );

    $json_url = $this->url_service."contacts/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Contacts",
      'action' => "edit",
      "crmid" => $crmid,
      "userid" => USERID,
      'data' => $data,
    );

    
    // echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);

  }

  public function getsalevisit() {
      $post_data = $this->input->post();
      $crmid = @$post_data['crmid'];

      $json_url = $this->url_service."calendar/get_visit";

      $fields = array(
        "AI-API-KEY" => "1234",
        "crmid" => $crmid,
        "module" => "Calendar",
        "userid" => USERID
      );
      /*echo $json_url;
      echo json_encode($fields);exit;*/

      $data = array();
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      
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

  public function create_salevisit() {
    $post_data = $this->input->post();

    $date_start = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['date_start'])));
    $due_date = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['due_date'])));

    $data[] = array(
      "activityid" => "",
      "event_id" => @$post_data['event_id'],
      "activitytype" => @$post_data['activitytype'],
      "smownerid" => @$post_data['smownerid'],
      "date_start" => $date_start,
      "time_start" => @$post_data['time_start'],
      "due_date" => $due_date,
      "time_end" => @$post_data['time_end'],
      "eventstatus" => @$post_data['eventstatus'],
      "branch" => @$post_data['branch'],
      "officer" => @$post_data['officer'],
      "accountid" => @$post_data['accountid'],
      "account_name" => @$post_data['account_name'],
      "phone" => @$post_data['phone'],
      "idcardno" => @$post_data['idcardno'],
      "mobile" => @$post_data['mobile'],
      "questionnaire_name" => @$post_data['questionnaire_name'],
      "scoring" => @$post_data['scoring'],
      "scoringresult" => @$post_data['scoringresult'],
      "googlelink" => @$post_data['googlelink'],
      "description" => @$post_data['description'],
      "commentplan" => @$post_data['commentplan'],
      "sale_remark" => @$post_data['sale_remark'],
      "sale_report" => @$post_data['sale_report'],
    );

    $json_url = $this->url_service."calendar/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Calendar",
      'action' => "add",
      "crmid" => "",
      "userid" => USERID,
      'data' => $data,
    );

    //echo $json_url;
    //echo json_encode($fields);exit;
    //alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);

  }

  public function update_salevisit() {

    $post_data = $this->input->post();

    $crmid = @$post_data['crmid'];
    
    $date_start = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['date_start'])));
    $due_date = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['due_date'])));

    $data[] = array(
      "event_id" => @$post_data['event_id'],
      "activitytype" => @$post_data['activitytype'],
      "smownerid" => @$post_data['smownerid'],
      "date_start" => $date_start,
      "time_start" => @$post_data['time_start'],
      "due_date" => $due_date,
      "time_end" => @$post_data['time_end'],
      "eventstatus" => @$post_data['eventstatus'],
      "branch" => @$post_data['branch'],
      "officer" => @$post_data['officer'],
      "account_name" => @$post_data['account_name'],
      "phone" => @$post_data['phone'],
      "idcardno" => @$post_data['idcardno'],
      "mobile" => @$post_data['mobile'],
      "questionnaire_name" => @$post_data['questionnaire_name'],
      "scoring" => @$post_data['scoring'],
      "scoringresult" => @$post_data['scoringresult'],
      "googlelink" => @$post_data['googlelink'],
      "description" => @$post_data['description'],
      "commentplan" => @$post_data['commentplan'],
      "sale_remark" => @$post_data['sale_remark'],
    );

    $json_url = $this->url_service."calendar/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Calendar",
      'action' => "edit",
      "crmid" => $crmid,
      "userid" => USERID,
      'data' => $data,
    );

    
    // echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);

  }

  public function getcase() {
      $post_data = $this->input->post();
      $crmid = @$post_data['crmid'];

      $json_url = $this->url_service."helpdesk/get_helpdesk";

      $fields = array(
        "AI-API-KEY" => "1234",
        "crmid" => $crmid,
        "module" => "HelpDesk",
        "userid" => USERID
      );
      /*echo $json_url;
      echo json_encode($fields);exit;*/

      $data = array();
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      
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

  public function create_case() {
    $post_data = $this->input->post();

    $case_date = date("Y-m-d", strtotime(str_replace('/', '-', @$post_data['case_date'])));
    $duedate = date("Y-m-d", strtotime(str_replace('/', '-', @$post_data['duedate'])));

    $data[] = array(
      "ticket_no" => "",
      "case_status" => @$post_data['case_status'],
      "title"=>@$post_data['ticket_title'],
      "priority_case" => @$post_data['priority_case'],
      "case_date" => $case_date,
      "case_time" => @$post_data['case_time'],
      "accountid" => @$post_data['account_id'],
      "account_name" => @$post_data['account_name'],
      "tel" => @$post_data['tel'],
      "line_id" => @$post_data['line_id'],
      "facebook" => @$post_data['facebook'],
      "email" => @$post_data['email'],
      "address" => @$post_data['address'],
      "subdistrict" => @$post_data['subdistrict'],
      "district" => @$post_data['district'],
      "province" => @$post_data['province'],
      "postalcode" => @$post_data['postalcode'],
      "erpaccountid" => @$post_data['erpaccountid'],
      "contract_number" => @$post_data['contract_number'],
      "duedate" => @$duedate,
      "payment" => @$post_data['payment'],
      "installment_payment" => @$post_data['installment_payment'],
      "total" => @$post_data['total'],
      "cashier" => @$post_data['cashier'],
      "branch" => @$post_data['branch'],
      "case_performance"=>@$post_data['case_performance'],
      "smownerid"=>@$post_data['smownerid'],
      "description"=>@$post_data['description']
    );

    $json_url = $this->url_service."helpdesk/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "HelpDesk",
      'action' => "add",
      "crmid" => "",
      "userid" => USERID,
      'data' => $data,
    );

    /*echo $json_url;
    echo json_encode($fields);exit;*/
    //alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function update_case() {
    $post_data = $this->input->post();

    $crmid = @$post_data['crmid'];
    
    $case_date = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['case_date'])));
    $date_completed = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['date_completed'])));

    $data[] = array(
      "ticket_no" => "",
      "case_status" => @$post_data['case_status'],
      "task_name" => @$post_data['task_name'],
      "priority_case" => @$post_data['priority_case'],
      "case_date" => $case_date,
      "date_completed" => $date_completed,
      "account_name" => @$post_data['account_name'],
      "contact_name" => @$post_data['contact_name'],
      "tel" => @$post_data['tel'],
      "email"=> @$post_data['email'],
    );

    $json_url = $this->url_service."helpdesk/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "HelpDesk",
      'action' => "edit",
      "crmid" => $crmid,
      "userid" => USERID,
      'data' => $data,
    );

    
    // echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function getjob() {
      $post_data = $this->input->post();
      $crmid = @$post_data['crmid'];

      $json_url = $this->url_service."job/get_job";

      $fields = array(
        "AI-API-KEY" => "1234",
        "crmid" => $crmid,
        "module" => "Job",
        "userid" => USERID
      );
      //echo $json_url;
      // echo json_encode($fields);exit;

      $data = array();
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      
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

  public function create_job() {
    $post_data = $this->input->post();

    $jobdate = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['jobdate'])));
    $jobdate_operate = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['jobdate_operate'])));
    $close_date = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['close_date'])));

    $data[] = array(
      "job_no" => "",
      "job_name" => @$post_data['job_name'],
      "job_status" => @$post_data['job_status'],
      "jobdate" => $jobdate,
      "notification_time" => @$post_data['notification_time'],
      "jobdate_operate" => $jobdate_operate,
      "ticket_no" => @$post_data['ticket_no'],
      "close_date" => $close_date,
      "account_name" => @$post_data['account_name'],
      "contact_name" => @$post_data['contact_name'],
      "contact_phone" => @$post_data['contact_phone'],
      "contact_email" => @$post_data['contact_email'],
      "job_number_house" => @$post_data['job_number_house'],
      "con_villageno" => @$post_data['con_villageno'],
      "con_village" => @$post_data['con_village'],
      "job_alley" => @$post_data['job_alley'],
      "job_road" => @$post_data['job_road'],
    );

    $json_url = $this->url_service."job/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Job",
      'action' => "add",
      "crmid" => "",
      "userid" => USERID,
      'data' => $data,
    );

    
    // echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function update_job() {

    $post_data = $this->input->post();

    $crmid = @$post_data['crmid'];

    $jobdate = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['jobdate'])));
    $jobdate_operate = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['jobdate_operate'])));
    $close_date = date("Y-m-d", strtotime(str_replace('/', '-', $post_data['close_date'])));

    $data[] = array(
      "job_no" => "",
      "job_name" => @$post_data['job_name'],
      "job_status" => @$post_data['job_status'],
      "jobdate" => $jobdate,
      "notification_time" => @$post_data['notification_time'],
      "jobdate_operate" => $jobdate_operate,
      "ticket_no" => @$post_data['ticket_no'],
      "close_date" => $close_date,
      "account_name" => @$post_data['account_name'],
      "contact_name" => @$post_data['contact_name'],
      "contact_phone" => @$post_data['contact_phone'],
      "contact_email" => @$post_data['contact_email'],
      "job_number_house" => @$post_data['job_number_house'],
      "con_villageno" => @$post_data['con_villageno'],
      "con_village" => @$post_data['con_village'],
      "job_alley" => @$post_data['job_alley'],
      "job_road" => @$post_data['job_road'],
    );

    $json_url = $this->url_service."job/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Job",
      'action' => "edit",
      "crmid" => $crmid,
      "userid" => USERID,
      'data' => $data,
    );

    
    // echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);

  }

  public function getDocuments() {

      $post_data = $this->input->post();
      
      $crmid = @$post_data['crmid'];

      $json_url = $this->url_service."documents/get_documents";

      $fields = array(
        "AI-API-KEY" => "1234",
        "crmid" => $crmid,
        "module" => "Documents",
        "userid" => USERID
      );
      //echo $json_url;
      //echo json_encode($fields);exit;

      $data = array();
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      
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

  public function create_Documents() {
    $post_data = $this->input->post();
    $crmid = @$post_data['crmid'];
    $module = @$post_data['module'];
    $data[] = array(
      //"note_no" => "",
      "notecontent" => @$post_data['notecontent'],
      "title" => @$post_data['title'],
      "filename" => @$post_data['filename'],
      "smownerid" => @$post_data['smownerid'],
    );

    $json_url = $this->url_service."documents/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Documents",
      'action' => "add",
      "crmid" => "",
      "related_id" => $crmid,
      //"related_module" => $module,
      "userid" => USERID,
      'data' => $data,
    );

    
    //echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function update_Documents() {

    $post_data = $this->input->post();

    $crmid = @$post_data['crmid'];

    $data[] = array(
      "note_no" => "",
      "title" => @$post_data['title'],
      "description" => @$post_data['description'],
    );

    $json_url = $this->url_service."documents/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Documents",
      'action' => "edit",
      "crmid" => $crmid,
      "userid" => USERID,
      'data' => $data,
    );

    
    //echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);

  }

  public function getSerial() {

      $post_data = $this->input->post();
      $crmid = @$post_data['crmid'];

      $json_url = $this->url_service."serial/get_serial";

      $fields = array(
        "AI-API-KEY" => "1234",
        "crmid" => $crmid,
        "module" => "Serial",
        "userid" => USERID
      );
      //echo $json_url;
      // echo json_encode($fields);exit;

      $data = array();
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      
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

  public function create_Serial() {

    $post_data = $this->input->post();

    $data[] = array(
      "serial_no" => "",
      "serial_name" => @$post_data['serial_name'],
      "productname" => @$post_data['productname'],
      "remark" => @$post_data['remark'],
    );

    $json_url = $this->url_service."serial/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Serial",
      'action' => "add",
      "crmid" => "",
      "userid" => USERID,
      'data' => $data,
    );

    
    // echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);

  }

  public function update_Serial() {

    $post_data = $this->input->post();

    $crmid = @$post_data['crmid'];

    $data[] = array(
      "serial_no" => "",
      "serial_name" => @$post_data['serial_name'],
      "productname" => @$post_data['productname'],
      "remark" => @$post_data['remark'],
    );

    $json_url = $this->url_service."serial/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Serial",
      'action' => "edit",
      "crmid" => $crmid,
      "userid" => USERID,
      'data' => $data,
    );

    
    // echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function getCompetitor() {

      $post_data = $this->input->post();
      $crmid = @$post_data['crmid'];

      $json_url = $this->url_service."competitor/get_competitor";

      $fields = array(
        "AI-API-KEY" => "1234",
        "crmid" => $crmid,
        "module" => "Competitor",
        "userid" => USERID
      );
      //echo $json_url;
      // echo json_encode($fields);exit;

      $data = array();
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      
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

  public function create_Competitor() {

    $post_data = $this->input->post();

    $data[] = array(
      "competitor_no" => "",
      "competitor_name" => @$post_data['competitor_name'],
      "competitor_productname" => @$post_data['competitor_productname'],
      "qty" => @$post_data['qty'],
      "price" => @$post_data['price'],
      "compe_sales" => @$post_data['compe_sales'],
      "remark" => @$post_data['remark'],
    );

    $json_url = $this->url_service."competitor/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Competitor",
      'action' => "add",
      "crmid" => "",
      "userid" => USERID,
      'data' => $data,
    );

    
    // echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);

  }

  public function update_Competitor() {

    $post_data = $this->input->post();

    $crmid = @$post_data['crmid'];

    $data[] = array(
      "competitor_no" => "",
      "competitor_name" => @$post_data['competitor_name'],
      "competitor_productname" => @$post_data['competitor_productname'],
      "qty" => @$post_data['qty'],
      "price" => @$post_data['price'],
      "compe_sales" => @$post_data['compe_sales'],
      "remark" => @$post_data['remark'],
    );

    $json_url = $this->url_service."competitor/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Competitor",
      'action' => "edit",
      "crmid" => $crmid,
      "userid" => USERID,
      'data' => $data,
    );

    
    // echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);
  }

  public function getProducts() {

      $post_data = $this->input->post();
      $crmid = @$post_data['crmid'];

      $json_url = $this->url_service."products/get_products";

      $fields = array(
        "AI-API-KEY" => "1234",
        "crmid" => $crmid,
        "module" => "Products",
        "userid" => USERID
      );
      //echo $json_url;
      // echo json_encode($fields);exit;

      $data = array();
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      
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

  public function getrelate() {

      $post_data = $this->input->post();
      $crmid = @$post_data['crmid'];
      $module = @$post_data['module'];

      $json_url = $this->url_service."job/get_relate";

      $fields = array(
        "AI-API-KEY" => "1234",
        "action" => "relate",
        "crmid" => $crmid,
        "crm_subid" => "",
        "limit" => 0,
        "module" => $module,
        "offset" => 0,
        "related_module" => "",
        "userid" => USERID
      );
      //echo $json_url;
      //echo json_encode($fields);exit;

      $data = array();
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      
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

  public function create_tag() {

    $post_data = $this->input->post();

    $relation_id = @$post_data['relation_id'];
    $name = @$post_data['name'];
    $relation_module = @$post_data['relation_module'];

    $json_url = $this->url_service."tag/insert_content";
    
    $ex_name = explode(',', $name);

    //alert($ex_name); exit;
    
    foreach ($ex_name as $key => $value) {
      $fields = array(
        'AI-API-KEY'=>"1234",
        'module' => "Tag",
        'action' => "add",
        "relation_id" => $relation_id,
        "name" => $value,
        "userid" => USERID,
        "relation_module" => $relation_module,
      );
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      
      $data[] = $result['data'];
    }
    $result['data'] = $data;
    //alert($result); exit;
    // echo json_encode($fields);exit;
    // alert($fields); exit;
    //$response = $this->curl->simple_post($json_url,$fields,array(),"json");
    //$result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    echo json_encode($result);
  }

  public function delete_tag(){

    $post_data = $this->input->post();

    $relation_id = @$post_data['relation_id'];
    $id = @$post_data['id'];
    $relation_module = @$post_data['relation_module'];

    $json_url = $this->url_service."tag/delete_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Tag",
      "userid" => USERID,
      'action' => "delete",
      "id" => $id,
      "relation_id" => $relation_id,
      "action_type" => "only",
      "relation_module" => $relation_module,
    );

    
    // echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);

  }

  public function getvoucher(){

      $post_data = $this->input->post();
      $crmid = @$post_data['crmid'];

      $json_url = $this->url_service."voucher/list_content";

      $fields = array(
        "AI-API-KEY" => "1234",
        "crmid" => $crmid,
        "module" => "Voucher",
        "userid" => USERID
      );
      //echo $json_url;
      //echo json_encode($fields);exit;

      $data = array();
      $response = $this->curl->simple_post($json_url,$fields,array(),"json");
      $result = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
      
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

  public function convert_lead() {

    $post_data = $this->input->post();

    // $relation_id = @$post_data['relation_id'];
    $crmid = @$post_data['crmid'];
    $accountname = @$post_data['accountname'];

    $json_url = $this->url_service."convertlead/insert_content";
    $fields = array(
      'AI-API-KEY'=>"1234",
      'module' => "Leads",
      'action' => "convert",
      "userid" => USERID,
      "crmid" => $crmid,
      "accountname" => $accountname,
    );
    //echo json_encode($fields);exit;
    // alert($fields); exit;
    $response = $this->curl->simple_post($json_url,$fields,array(),"json");
    $result =json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true );
    
    echo json_encode($result);

  }
  
}