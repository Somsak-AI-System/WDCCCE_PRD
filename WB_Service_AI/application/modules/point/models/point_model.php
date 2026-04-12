<?php
class Point_model extends CI_Model
{
  var $ci;
  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
  }

  function get_total($a_condition=array())
  {
    try {
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }else{
        $a_condition["aicrm_crmentity.setype"] = "Point";
        $a_condition["aicrm_crmentity.deleted"] = "0";
      }

      $this->db->select("count(DISTINCT aicrm_point.pointid) as total");
      $this->db->join('aicrm_pointcf', 'aicrm_pointcf.pointid = aicrm_point.pointid',"inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_point.pointid',"inner");
      $query = $this->db->get('aicrm_point');
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_result[0];
        }else{
          $a_return["status"] = false;
          $a_return["error"] =  "No Data";
          $a_return["result"] = "";
        }
      }
    }catch (Exception $e) {
      $a_return["status"] = false;
      $a_return["error"] =  $e->getMessage();
      $a_return["result"] = "";
    }
    return $a_return;
  }

  function get_list($a_condition=array(),$a_order=array(),$a_limit=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "Point";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->join('aicrm_pointcf', 'aicrm_pointcf.pointid = aicrm_point.pointid',"inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_point.pointid',"inner");
      $this->db->join('aicrm_users', 'aicrm_users.id = aicrm_crmentity.smownerid',"left");
      
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      if (!empty($a_order)) {
        for($i=0;$i<count($a_order);$i++){
          $this->db->order_by($a_order[$i]["field"], $a_order[$i]["order"]);
        }
      }
      if (empty($a_limit)) {
        $a_limit["limit"] = $this->_limit;
        $a_limit["offset"] = 0;
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }else if($a_limit["limit"]==0){

      }else{
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }
      $query = $this->db->get('aicrm_point');

      /*echo $this->db->last_query();
      exit;*/
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total($a_condition) ;
        $a_data["offset"] = $a_limit["offset"];
        $a_data["limit"] = $a_limit["limit"];
        $a_data["total"] = ($a_total["status"]) ? $a_total["result"]["total"] : 0;
        $a_data["data"] = $a_result;
        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_data;
        }else{
          $a_return["status"] = false;
          $a_return["error"] =  "No Data";
          $a_return["result"] = "";
        }
      }
      }catch (Exception $e) {
        $a_return["status"] = false;
        $a_return["error"] =  $e->getMessage();
        $a_return["result"] = "";
      }
      return $a_return;
  }

  function get_list_redemption($a_condition=array(),$a_order=array(),$a_limit=array(),$filter=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "Redemption";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
     
      $this->db->join('aicrm_redemptioncf', 'aicrm_redemptioncf.redemptionid = aicrm_redemption.redemptionid',"inner");
      $this->db->join('aicrm_premiums', 'aicrm_premiums.premiumid = aicrm_redemption.premiumid',"inner");
      $this->db->join('aicrm_premiumscf', 'aicrm_premiumscf.premiumid = aicrm_premiums.premiumid',"inner");
      $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_redemption.accountid',"inner");
      $this->db->join('aicrm_accountscf', 'aicrm_accountscf.accountid = aicrm_account.accountid',"inner");
      
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_redemption.redemptionid',"inner");
      $this->db->join('aicrm_users', 'aicrm_users.id = aicrm_crmentity.smownerid',"left");
      
      $a_condition['aicrm_premiums.category_premiums != '] = 'Lucky Draw';

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      
      if(isset($filter["filter"]) && $filter["filter"]!="") {
        //echo $filter["filter"]; exit;
        $filter = explode('/',$filter["filter"]);
        
        $this->db->where("MONTH(aicrm_crmentity.createdtime) = '".$filter[0]."' ", NULL, FALSE);  
        $this->db->where("YEAR(aicrm_crmentity.createdtime) = '".$filter[1]."' ", NULL, FALSE);  
        /*if($filter["filter"] == 1){//5 รายการล่าสุด
          $a_limit["limit"] = 5;
        }else if($filter["filter"] == 2){//15 วันที่ผ่านมา
          $this->db->where("aicrm_crmentity.createdtime > now() - INTERVAL 15 day", NULL, FALSE);  
        }else if($filter["filter"] == 3){//30 วันที่ผ่านมา
          $this->db->where("aicrm_crmentity.createdtime > now() - INTERVAL 30 day", NULL, FALSE);  
        }else if($filter["filter"] == 4){//6 เดือนที่ผ่านมา
          $this->db->where("aicrm_crmentity.createdtime > DATE_SUB(now(), INTERVAL 6 MONTH)", NULL, FALSE);
        }else if($filter["filter"] == 5){//รายการปี ปัจจุบัน
          $this->db->where("YEAR(aicrm_crmentity.createdtime) = ".date('Y'), NULL, FALSE);
        }else if($filter["filter"] == 6){//รายการทั้งหมด

        }*/

      }

      //echo $a_limit["limit"];
      $this->db->group_by('aicrm_redemption.redemptionid'); 

      if (!empty($a_order)) {
        for($i=0;$i<count($a_order);$i++){
          $this->db->order_by($a_order[$i]["field"], $a_order[$i]["order"]);
        }
      }
      if (empty($a_limit)) {
        $a_limit["limit"] = $this->_limit;
        $a_limit["offset"] = 0;
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }else if($a_limit["limit"]==0){

      }else{
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }
      $query = $this->db->get('aicrm_redemption');

      //echo $this->db->last_query(); 
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total($a_condition) ;
        $a_data["offset"] = $a_limit["offset"];
        $a_data["limit"] = $a_limit["limit"];
        $a_data["total"] = ($a_total["status"]) ? $a_total["result"]["total"] : 0;
        $a_data["data"] = $a_result;
        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_data;
        }else{
          $a_return["status"] = false;
          $a_return["error"] =  "No Data";
          $a_return["result"] = "";
        }
      }
      }catch (Exception $e) {
        $a_return["status"] = false;
        $a_return["error"] =  $e->getMessage();
        $a_return["result"] = "";
      }
      return $a_return;
  }

  function get_list_point($accountid='',$a_order=array(),$a_limit=array(),$filter=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "Point";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->select(" aicrm_point.* ,aicrm_account.accountname ");
      $this->db->join('aicrm_pointcf', 'aicrm_pointcf.pointid = aicrm_point.pointid',"inner");
      $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_point.accountid',"inner");
      $this->db->join('aicrm_accountscf', 'aicrm_accountscf.accountid = aicrm_account.accountid',"inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_point.pointid',"inner");
      $this->db->join('aicrm_users', 'aicrm_users.id = aicrm_crmentity.smownerid',"left");
      
      $a_condition['aicrm_point.sourcestatus'] = 'Add';
      $a_condition['aicrm_point.accountid']  = $accountid;

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      
      if (isset($filter["filter"]) && $filter["filter"]!="") {
        $filter = explode('/',$filter["filter"]);
        
        $this->db->where("MONTH(aicrm_crmentity.createdtime) = '".$filter[0]."' ", NULL, FALSE);  
        $this->db->where("YEAR(aicrm_crmentity.createdtime) = '".$filter[1]."' ", NULL, FALSE); 
        /*if($filter["filter"] == 1){//5 รายการล่าสุด
          $a_limit["limit"] = 5;
        }else if($filter["filter"] == 2){//15 วันที่ผ่านมา
          $this->db->where("aicrm_crmentity.createdtime > now() - INTERVAL 15 day", NULL, FALSE);  
        }else if($filter["filter"] == 3){//30 วันที่ผ่านมา
          $this->db->where("aicrm_crmentity.createdtime > now() - INTERVAL 30 day", NULL, FALSE);  
        }else if($filter["filter"] == 4){//6 เดือนที่ผ่านมา
          $this->db->where("aicrm_crmentity.createdtime > DATE_SUB(now(), INTERVAL 6 MONTH)", NULL, FALSE);
        }else if($filter["filter"] == 5){//รายการปี ปัจจุบัน
          $this->db->where("YEAR(aicrm_crmentity.createdtime) = ".date('Y'), NULL, FALSE);
        }else if($filter["filter"] == 6){//รายการทั้งหมด

        }*/
      }
      $this->db->group_by('aicrm_point.pointid'); 

      if (!empty($a_order)) {
        for($i=0;$i<count($a_order);$i++){
          $this->db->order_by($a_order[$i]["field"], $a_order[$i]["order"]);
        }
      }
      if (empty($a_limit)) {
        $a_limit["limit"] = $this->_limit;
        $a_limit["offset"] = 0;
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }else if($a_limit["limit"]==0){

      }else{
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }
      $query = $this->db->get('aicrm_point');

      //echo $this->db->last_query(); exit;
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;
        //alert($a_result); exit;
        $a_total = $this->get_total($a_condition) ;
        $a_data["offset"] = $a_limit["offset"];
        $a_data["limit"] = $a_limit["limit"];
        $a_data["total"] = ($a_total["status"]) ? $a_total["result"]["total"] : 0;
        $a_data["data"] = $a_result;
        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_data;
        }else{
          $a_return["status"] = false;
          $a_return["error"] =  "No Data";
          $a_return["result"] = "";
        }
      }
      }catch (Exception $e) {
        $a_return["status"] = false;
        $a_return["error"] =  $e->getMessage();
        $a_return["result"] = "";
      }
      return $a_return;
  }

  function get_list_redemactivity($a_condition=array(),$a_order=array(),$a_limit=array(),$filter=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "Redemption";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      // $this->db->select("aicrm_premiums.premiumid, aicrm_premiums.premium_name, aicrm_premiumscf.cf_1245,aicrm_premiumscf.cf_1371,aicrm_accountscf.cf_1219,aicrm_account.expired_date,aicrm_accountscf.cf_1486,aicrm_redemptioncf.cf_1466",false );
      $this->db->join('aicrm_redemptioncf', 'aicrm_redemptioncf.redemptionid = aicrm_redemption.redemptionid',"inner");
      $this->db->join('aicrm_premiums', 'aicrm_premiums.premiumid = aicrm_redemption.premiumid',"inner");
      $this->db->join('aicrm_premiumscf', 'aicrm_premiumscf.premiumid = aicrm_premiums.premiumid',"inner");
      $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_redemption.accountid',"inner");
      $this->db->join('aicrm_accountscf', 'aicrm_accountscf.accountid = aicrm_account.accountid',"inner");
      //$this->db->join('aicrm_point', 'aicrm_point.parent_id = aicrm_redemption.accountid',"inner");
      //$this->db->join('aicrm_pointcf', 'aicrm_pointcf.pointid = aicrm_point.pointid',"inner");
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_redemption.redemptionid',"inner");
      $this->db->join('aicrm_users', 'aicrm_users.id = aicrm_crmentity.smownerid',"left");
      
      $a_condition['aicrm_premiums.category_premiums = '] = 'Lucky Draw';

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      
      if (isset($filter["filter"]) && $filter["filter"]!="") {
        $filter = explode('/',$filter["filter"]);
        
        $this->db->where("MONTH(aicrm_crmentity.createdtime) = '".$filter[0]."' ", NULL, FALSE);  
        $this->db->where("YEAR(aicrm_crmentity.createdtime) = '".$filter[1]."' ", NULL, FALSE); 
        /*if($filter["filter"] == 1){//5 รายการล่าสุด
          $a_limit["limit"] = 5;
        }else if($filter["filter"] == 2){//15 วันที่ผ่านมา
          $this->db->where("aicrm_crmentity.createdtime > now() - INTERVAL 15 day", NULL, FALSE);  
        }else if($filter["filter"] == 3){//30 วันที่ผ่านมา
          $this->db->where("aicrm_crmentity.createdtime > now() - INTERVAL 30 day", NULL, FALSE);  
        }else if($filter["filter"] == 4){//6 เดือนที่ผ่านมา
          $this->db->where("aicrm_crmentity.createdtime > DATE_SUB(now(), INTERVAL 6 MONTH)", NULL, FALSE);
        }else if($filter["filter"] == 5){//รายการปี ปัจจุบัน
          $this->db->where("YEAR(aicrm_crmentity.createdtime) = ".date('Y'), NULL, FALSE);
        }else if($filter["filter"] == 6){//รายการทั้งหมด

        }*/
      }
      
      $this->db->group_by('aicrm_redemption.redemptionid'); 

      if (!empty($a_order)) {
        for($i=0;$i<count($a_order);$i++){
          $this->db->order_by($a_order[$i]["field"], $a_order[$i]["order"]);
        }
      }
      if (empty($a_limit)) {
        $a_limit["limit"] = $this->_limit;
        $a_limit["offset"] = 0;
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }else if($a_limit["limit"]==0){

      }else{
        $this->db->limit($a_limit["limit"],$a_limit["offset"]);
      }
      $query = $this->db->get('aicrm_redemption');

      /*echo $this->db->last_query();
      exit;*/
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total($a_condition) ;
        $a_data["offset"] = $a_limit["offset"];
        $a_data["limit"] = $a_limit["limit"];
        $a_data["total"] = ($a_total["status"]) ? $a_total["result"]["total"] : 0;
        $a_data["data"] = $a_result;
        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_data;
        }else{
          $a_return["status"] = false;
          $a_return["error"] =  "No Data";
          $a_return["result"] = "";
        }
      }
      }catch (Exception $e) {
        $a_return["status"] = false;
        $a_return["error"] =  $e->getMessage();
        $a_return["result"] = "";
      }
      return $a_return;
  }

}