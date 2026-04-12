<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class Common
{
  public $_tab = array();

  function __construct()
  {
    $this->CI = & get_instance();
    $this->CI->load->library('logging');
    $this->_format = "array";//array|json
    $this->_filename = "info";

  }

  public function set_order($a_orderby=array())
  {
    if(empty($a_orderby)) return false;

    $a_order = array();
    $a_condition = explode( "|",$a_orderby);
    for ($i =0;$i<count($a_condition) ;$i++)
    {
      list($field,$order) = explode(",", $a_condition[$i]);
      $a_order[$i]["field"] = $field;
      $a_order[$i]["order"] = $order;
    }

    return $a_order;
  }

  public function set_log_import($message,$logname)
  {
    // Get time of request
    if( ($time = $_SERVER['REQUEST_TIME']) == '') {
      $time = time();
    }

    $time = date("Y-m-d H:i:s");

    // Get IP address
    if( ($remote_addr = $_SERVER['REMOTE_ADDR']) == '') {
      $remote_addr = "REMOTE_ADDR_UNKNOWN";
    }

    // Get requested script
    if( ($request_uri = $_SERVER['REQUEST_URI']) == '') {
      $request_uri = "REQUEST_URI_UNKNOWN";
    }

    // Format the date and time
    //$date = date("Y-m-d H:i:s", $time);

    $date =  date("Y-m-d H:i:s");

    // Append to the log file
    $strFileName = "log/".$logname."_".date("Ymd").".txt";
    //$objFopen = fopen($strFileName, 'a');

    if($fd = fopen($strFileName, "a")) {
      $result = fputcsv($fd, array($date, $remote_addr, $request_uri, $message));
      fclose($fd);

      if($result > 0)
      return array("status" => true);
      else
      return array("status" => false, "message" => 'Unable to write to '.$strFileName.'!');
    }
    else {
      return array("status" => false, "message" => 'Unable to open log '.$strFileName.'!');
    }
  }

  public function set_log($url=null,$param=null,$response=null)
  {
    //if($url=="") return false;
    //print_r($response);
    $this->write_log($url,$param,$response);
  }

  public function set_log_error($url=null,$param=null,$response=null)
  {
    if($this->_filename == "info" || $this->_filename ==""){
      $this->_filename = "error";
    }
    $logger = $this->CI->logging->get_logger($this->_filename );
    //echo $response;
    $logger->error("URL:: ".$url);
    $logger->error("Parameter ::".json_encode($param));
    if($this->_format =="array"){
      $logger->error("Response:: ".json_encode($response));
    }else{//json
      $logger->error("Response:: ".$response);
    }
  }

  private function write_log($url=null,$param=null,$response=null)
  {
    $logger =$this->CI->logging->get_logger($this->_filename);


    $logger->info("URL:: ".$url);
    $logger->info("Parameter ::".json_encode($param));
    if($this->_format =="array"){
      $logger->info("Response:: ".json_encode($response));
    }else{//json
      $logger->info("Response:: ".$response);
    }

  }

  public function get_a_image($a_conditionin=array(),$module="",$pkfield="")
  {
    // alert($module);
    // alert($a_conditionin);exit;
    $this->CI->load->model("attachments/attachments_model");
    $url_new= $this->CI->config->item('url_new');
    $a_order = array();
    $a_condition = array();
    $a_limit["limit"] = 0;
    $a_limit["offset"] = 0;

    $a_att = $this->CI->attachments_model->get_field($module);
    $a_data=$this->CI->attachments_model->get_list($a_condition,$a_order,$a_limit,$a_conditionin);
    // alert($a_att);exit();
    // alert($a_data);exit();
    $a_response = array();
    if(!empty($a_data["result"]["data"]) && $a_data["status"] ){
      $field = $a_att["_field"];
      // alert($a_att);exit;
      foreach ($a_data["result"]["data"] as $key =>$val){
        $a_return = array();
        $contentid = $val[$field];
        //alert($contentid);exit;
        //alert($val);
        if($module == 'Calendar' || $module == 'Job'){
          $a_return[$val["attachmentsid"]] =$url_new.$val["path"].$val["name"];
        }elseif($module == 'Questionnaire'){
          $a_return[$val["attachmentsid"]] =$url_new.$val["path"].$val["attachmentsid"]."_".$val["name"];
        }elseif($module == 'HelpDesk'){
          $a_return[$val["attachmentsid"]] =$url_new.$val["path"].$val["name"];
        }else{
          $a_return =$url_new.$val["path"].$val["attachmentsid"]."_".$val["name"];
        }
        $a_response[$contentid]["image"][] = $a_return;
      }
    }
    //alert($a_response);exit;
    return $a_response;
  }


  public function get_productname($productid="")
  {
    if($productid=="")	return "";
    $this->CI->load->database();
    $a_condition["aicrm_crmentity.deleted"] = "0";
    $a_condition["aicrm_products.productid"] = $productid;
    if (!empty($a_condition)) {
      $this->CI->db->where($a_condition);
    }
    $this->CI->db->select("aicrm_products.productid
    ,aicrm_products.productname");
    $this->CI->db->join('aicrm_productcf', 'aicrm_productcf.productid = aicrm_products.productid','inner');
    $this->CI->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_products.productid','inner');
    $query = $this->CI->db->get('aicrm_products');
    $a_result  = $query->result_array() ;
    return @$a_result[0]["productname"] ;
  }

  public function get_branchname($branchid="")
  {
    if($branchid=="")	return "";
    $this->CI->load->database();
    $a_condition["aicrm_crmentity.deleted"] = "0";
    $a_condition["aicrm_branchs.branchid"] = $branchid;
    if (!empty($a_condition)) {
      $this->CI->db->where($a_condition);
    }
    $this->CI->db->select("aicrm_branchs.branchid
    ,aicrm_branchs.branch_name");
    $this->CI->db->join('aicrm_branchscf', 'aicrm_branchscf.branchid = aicrm_branchs.branchid','inner');
    $this->CI->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_branchs.branchid','inner');
    $query = $this->CI->db->get('aicrm_branchs');
    $a_result  = $query->result_array() ;
    return @$a_result[0]["branch_name"] ;
  }

  public function get_mobile_tabid($userid="")
  {
    if($userid=="")	return "";
    $this->CI->load->database();
    $a_condition["aicrm_users.id"] = $userid;
    $a_condition["aicrm_tab.mobile_seq != "] = 0;
    if (!empty($a_condition)) {
      $this->CI->db->where($a_condition);
    }
    $this->CI->db->select("aicrm_tab.tabid, aicrm_tab.tablabel,aicrm_tab.mobile_seq,aicrm_tab.presence ");
    $this->CI->db->join('aicrm_user2role', 'aicrm_users.id = aicrm_user2role.userid','inner');
    $this->CI->db->join('aicrm_role2profile', 'aicrm_role2profile.roleid = aicrm_user2role.roleid','inner');
    $this->CI->db->join('aicrm_profile2tab', 'aicrm_profile2tab.profileid = aicrm_role2profile.profileid','inner');
    $this->CI->db->join('aicrm_tab', 'aicrm_tab.tabid = aicrm_profile2tab.tabid','inner');
    $query = $this->CI->db->get('aicrm_users');
    // alert($this->CI->db->last_query());exit;
    $a_result  = $query->result_array() ;
    return @$a_result ;
  }


  public function get_role($userid = "")
  {
    if($userid==""){
      return array();
    }
    $a_return = array();
    $a_condition["aicrm_user2role.userid"] = $userid;
    $this->CI->db->join('aicrm_users', 'aicrm_user2role.userid = aicrm_users.id', 'left');
    $this->CI->db->where($a_condition);
    $query = $this->CI->db->get('aicrm_user2role');
    $a_return  = $query->result_array() ;
    return $a_return;
  }

  public function get_group($userid="")
  {
     if($userid==""){
      return array();
    }
    $a_return = array();
    $a_condition["aicrm_users2group.userid"] = $userid;
    // $this->CI->db->select('aicrm_groups.groupid ,aicrm_groups.groupname,aicrm_users.user_name ');
    $this->CI->db->select('aicrm_groups.groupid ');
    $this->CI->db->join('aicrm_users', 'aicrm_users2group.userid = aicrm_users.id', 'left');
    $this->CI->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_users2group.groupid', 'left');
    $this->CI->db->where($a_condition);
    $query = $this->CI->db->get('aicrm_users2group');
    $a_return  = $query->result_array() ;
	//alert($this->CI->db->last_query());exit;
    return $a_return;
  }

  public function get_parent_role($roleid = "")
  {
    if($roleid==""){
      return array();
    }
    $a_return = array();
    $a_condition["roleid"] = $roleid;
    $this->CI->db->where($a_condition);
    $query = $this->CI->db->get('aicrm_role');
    $a_return  = $query->result_array() ;

    return $a_return;
  }

  public function get_user2role($userid="",$module="")
  {

    $a_response= array();
    //$userid =  $_SESSION['user_id'];
    //Get Roleid, is Admin
    $a_role = $this->get_role($userid);
    $roleid = @$a_role[0]['roleid'];
    $is_admin = @$a_role[0]['is_admin'];

    //Get 'parentrole' of $roleid
    $a_role = $this->get_parent_role($roleid);
    $parentrole = $a_role[0]['parentrole'];

    if($module!="Events" || $module!="Calendar" || $module!="Sales visit"){

      $a_group = $this->get_group($userid);
      // $groupid = @$a_group[0]['groupid'];
        foreach ($a_group as $key => $value) {
          $groupid[] = $value['groupid'];
        }
        $groupid = implode(",",$groupid);
  
      }

    if($is_admin == "on"){
      //'admin' authorization
      $sql = "SELECT id as id
      ,user_name as user_name
      , first_name
      , last_name
      , CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name'
      , IFNULL(area,'') as area
      , case when section	= '--None--' then '' else section end as section
      FROM aicrm_users users
      WHERE deleted = 0
      UNION
      SELECT DISTINCT groupid as id
      ,	groupname as user_name
      , groupname as first_name
      , groupname as last_name
      , CONCAT(description) as 'sale_name'
      , '' as area
      , 'admin' as section
      FROM aicrm_groups
      ORDER BY id ASC ";
    }else{
      $sql = "select id as id
      ,user_name as user_name
      , first_name , last_name
      , CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name'
      , IFNULL(area,'') as area
      , case when section	= '--None--' then '' else section end as section
      from aicrm_users
      where id= ".$userid."
      and status='Active'
      union
      select aicrm_user2role.userid as id
      ,aicrm_users.user_name as user_name
      , aicrm_users.first_name  , aicrm_users.last_name
      , CONCAT(aicrm_users.first_name, ' ', aicrm_users.last_name,' [',aicrm_users.user_name,']') as 'sale_name'
      , IFNULL(aicrm_users.area,'') as area
      , case when aicrm_users.section	= '--None--' then '' else aicrm_users.section end as section
      from aicrm_user2role
      inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid
      inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid
      where aicrm_role.parentrole like '".$parentrole."::%'
     and status='Active' and deleted = '0' ";

      if(!empty($groupid)){
        $sql .= "
        union
        select aicrm_users2group.groupid as id
        ,aicrm_groups.groupname as user_name
        , aicrm_users.first_name  , aicrm_users.last_name
        , CONCAT(aicrm_users.first_name, ' ', aicrm_users.last_name,' [',aicrm_users.user_name,']') as 'sale_name'
        , IFNULL(aicrm_users.area,'') as area
        , case when aicrm_users.section	= '--None--' then '' else aicrm_users.section end as section
        from aicrm_users2group
        inner join aicrm_users on aicrm_users.id=aicrm_users2group.userid
        inner join aicrm_groups on aicrm_groups.groupid=aicrm_users2group.groupid
        where aicrm_users.id in (".$userid.")
        ";
            // where aicrm_groups.groupid in (".$groupid.")
      }

      $sql .= "and status='Active' and deleted = '0'
      order by user_name ";
    }
     $query = $this->CI->db->query($sql);
     $a_response =  $query->result_array();

    $id= '';
    foreach($a_response as $key => $val){

      if($key == 0){
        $id = "'".$val['id']."'" ;
      }else{
        $id .= ",'".$val['id']."'" ;
      }

    }

    return $id;

  }


  public function get_user_email($user_id = "")
  {
    if($user_id==""){
      return array();
    }
    $a_return = array();
    $a_condition["id"] = $user_id;
    $this->CI->db->where($a_condition);
    $query = $this->CI->db->get('aicrm_users');
    $a_return  = $query->result_array() ;
    return @$a_return[0]["email1"] ;
  }

  public function get_user_name($user_id = "")
  {
    if($user_id==""){
      return array();
    }
    $a_return = array();
    $a_condition["id"] = $user_id;
    $this->CI->db->where($a_condition);
    $query = $this->CI->db->get('aicrm_users');
    $a_return  = $query->result_array() ;
    return @$a_return[0]["user_name"] ;
  }

}
