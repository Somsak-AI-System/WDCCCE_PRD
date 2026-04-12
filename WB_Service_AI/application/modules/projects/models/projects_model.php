<?php
class Projects_model extends CI_Model
{
  var $ci;
  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
  }
 
  function get_projects($a_request=array())
  {

    $a_return["status"] = false;
    $a_return["error"] =  "No Data";
    $a_return["result"] = "";

    $crmid = isset($a_request['crmid']) ? $a_request['crmid'] : "";
    $userid = isset($a_request['userid']) ? $a_request['userid'] : "";
    $module = isset($a_request['module']) ? $a_request['module'] : "";

    if(empty($crmid)){
       return $a_return;
    }
    
    try {

      $a_condition["aicrm_crmentity.crmid"] = $crmid;
      $a_condition["aicrm_crmentity.setype"] = $module;
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_projects.projectsid',"inner");
      $this->db->join('aicrm_projectsbillads', 'aicrm_projectsbillads.quotebilladdressid = aicrm_projects.projectsid',"inner");
      $this->db->join('aicrm_projectsshipads', 'aicrm_projectsshipads.projectshipaddressid = aicrm_projects.projectsid',"inner");
      $this->db->join('aicrm_projectscf', 'aicrm_projectscf.projectsid = aicrm_projects.projectsid',"inner");

      $this->db->join('aicrm_currency_info', 'aicrm_currency_info.id = aicrm_projects.currency_id',"left");
      $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_projects.accountid',"left");
      $this->db->join('aicrm_potential', 'aicrm_potential.potentialid = aicrm_projects.potentialid',"left");
      $this->db->join('aicrm_contactdetails', 'aicrm_contactdetails.contactid = aicrm_projects.contactid',"left");

      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }
      
      $query = $this->db->get('aicrm_projects');

      // echo $this->db->last_query(); exit;

      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;        
        if (!empty($a_result)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_result;
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

  function get_total_activity($a_condition=array())
  {
    try {
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }else{
        //$a_condition["aicrm_crmentity.setype"] = "Calendar";
        $a_condition["aicrm_crmentity.deleted"] = "0";
      }

      $this->db->select("count(DISTINCT aicrm_activity.activityid) as total");
      $this->db->join('aicrm_activitycf', 'aicrm_activitycf.activityid = aicrm_activity.activityid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_activity.activityid' ,'inner');
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $query = $this->db->get('aicrm_activity');
      /*echo $this->db->last_query();
      exit;*/
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

  function get_list_activity($a_condition=array(),$a_order=array(),$a_limit=array())
  {
    $this->load->library('crmentity');
    try {
      //$a_condition["aicrm_crmentity.setype"] = "Calendar";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->select("aicrm_activity.* ,CASE WHEN (aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name
          ELSE aicrm_groups.groupname
        END AS user_name, CASE WHEN ifnull(aicrm_account.accountid,'') != '' THEN aicrm_account.accountname
          WHEN ifnull(aicrm_leaddetails.leadid,'') != '' THEN aicrm_leaddetails.leadname
          ELSE ''
        END AS customer_name ",false);

      $this->db->join('aicrm_activitycf', 'aicrm_activitycf.activityid = aicrm_activity.activityid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_activity.activityid' ,'inner');
      $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_activity.parentid','left');
      $this->db->join('aicrm_leaddetails', 'aicrm_leaddetails.leadid = aicrm_activity.parentid','left');
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      
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
      $query = $this->db->get('aicrm_activity');

      /*echo $this->db->last_query();
      exit;*/
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total_activity($a_condition) ;
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

  function get_total_quotation($a_condition=array())
  {
    try {
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }else{
        $a_condition["aicrm_crmentity.setype"] = "Quotes";
        $a_condition["aicrm_crmentity.deleted"] = "0";
      }

      $this->db->select("count(DISTINCT aicrm_quotes.quoteid) as total");
      $this->db->join('aicrm_quotescf', 'aicrm_quotescf.quoteid = aicrm_quotes.quoteid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_quotes.quoteid' ,'inner');
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $query = $this->db->get('aicrm_quotes');
      /*echo $this->db->last_query();
      exit;*/
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

  function get_list_quotation($a_condition=array(),$a_order=array(),$a_limit=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "Quotes";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->select("aicrm_quotes.* ,ifnull(aicrm_account.accountname,'') as accountname ,CASE WHEN (aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name
          ELSE aicrm_groups.groupname
        END AS user_name,",false);

      $this->db->join('aicrm_quotescf', 'aicrm_quotescf.quoteid = aicrm_quotes.quoteid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_quotes.quoteid' ,'inner');
      $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_quotes.accountid','left');
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      
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
      $query = $this->db->get('aicrm_quotes');

      // echo $this->db->last_query();
      // exit;
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total_quotation($a_condition) ;
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

  function get_total_documents($a_condition=array())
  {
    try {
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }else{
        $a_condition["aicrm_crmentity.setype"] = "Documents";
        $a_condition["aicrm_crmentity.deleted"] = "0";
      }

      $this->db->select("count(DISTINCT aicrm_notes.notesid) as total");
      $this->db->join('aicrm_senotesrel', 'aicrm_senotesrel.notesid = aicrm_notes.notesid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_notes.notesid AND aicrm_crmentity.deleted = 0' ,'inner');
      $this->db->join('aicrm_crmentity as crm2', 'crm2.crmid = aicrm_senotesrel.crmid','inner');
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      $this->db->join('aicrm_seattachmentsrel', 'aicrm_seattachmentsrel.crmid = aicrm_notes.notesid','left');
      $this->db->join('aicrm_attachments', 'aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid','left');
      $query = $this->db->get('aicrm_notes');
      /*echo $this->db->last_query();
      exit;*/
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

  function get_list_documents($a_condition=array(),$a_order=array(),$a_limit=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "Documents";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->select("
        CASE WHEN (aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name
          ELSE aicrm_groups.groupname
        END AS user_name,
        aicrm_seattachmentsrel.attachmentsid attachmentsid,
        aicrm_notes.notesid crmid,
        aicrm_notes.* , ifnull(aicrm_attachmentsfolder.foldername,'') as  foldername ,
        ifnull(aicrm_notes.filename,'') as filename",false);

      $this->db->join('aicrm_senotesrel', 'aicrm_senotesrel.notesid = aicrm_notes.notesid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_notes.notesid AND aicrm_crmentity.deleted = 0' ,'inner');
      $this->db->join('aicrm_crmentity as crm2', 'crm2.crmid = aicrm_senotesrel.crmid','inner');
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_attachmentsfolder', 'aicrm_attachmentsfolder.folderid = aicrm_notes.folderid','left');
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      $this->db->join('aicrm_seattachmentsrel', 'aicrm_seattachmentsrel.crmid = aicrm_notes.notesid','left');
      $this->db->join('aicrm_attachments', 'aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid','left');
      
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
      $query = $this->db->get('aicrm_notes');

      /*echo $this->db->last_query();
      exit;*/
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total_documents($a_condition) ;
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
  
  function get_total_pricelist($a_condition=array())
  {
    try {
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }else{
        $a_condition["aicrm_crmentity.setype"] = "Documents";
        $a_condition["aicrm_crmentity.deleted"] = "0";
      }

      $this->db->select("count(DISTINCT aicrm_pricelists.pricelistid) as total");
      $this->db->join('aicrm_pricelistscf', 'aicrm_pricelistscf.pricelistid = aicrm_pricelists.pricelistid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_pricelists.pricelistid' ,'inner');
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      $query = $this->db->get('aicrm_pricelists');
      /*echo $this->db->last_query();
      exit;*/
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

  function get_list_pricelist($a_condition=array(),$a_order=array(),$a_limit=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "PriceList";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->select("aicrm_pricelists.*,CASE WHEN (aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name
          ELSE aicrm_groups.groupname
        END AS user_name , ifnull(aicrm_account.accountname,'') as accountname ,ifnull(aicrm_quotes.quote_no,'') as quote_no",false);

      $this->db->join('aicrm_pricelistscf', 'aicrm_pricelistscf.pricelistid = aicrm_pricelists.pricelistid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_pricelists.pricelistid' ,'inner');

      $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_pricelists.accountid' ,'left');
      $this->db->join('aicrm_quotes', 'aicrm_quotes.quoteid = aicrm_pricelists.quoteid' ,'left');

      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      
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
      $query = $this->db->get('aicrm_pricelists');

      /*echo $this->db->last_query();
      exit;*/
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total_pricelist($a_condition) ;
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

  function get_total_samplerequisition($a_condition=array())
  {
    try {
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }else{
        $a_condition["aicrm_crmentity.setype"] = "Samplerequisition";
        $a_condition["aicrm_crmentity.deleted"] = "0";
      }

      $this->db->select("count(DISTINCT aicrm_samplerequisition.samplerequisitionid) as total");
      $this->db->join('aicrm_samplerequisitioncf', 'aicrm_samplerequisitioncf.samplerequisitionid = aicrm_samplerequisition.samplerequisitionid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_samplerequisition.samplerequisitionid' ,'inner');
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      $query = $this->db->get('aicrm_samplerequisition');
      /*echo $this->db->last_query();
      exit;*/
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

  function get_list_samplerequisition($a_condition=array(),$a_order=array(),$a_limit=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "Samplerequisition";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->select("aicrm_samplerequisition.* ,CASE WHEN (aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name
          ELSE aicrm_groups.groupname
        END AS user_name , ifnull(aicrm_account.accountname,'') as accountname",false);

      $this->db->join('aicrm_samplerequisitioncf', 'aicrm_samplerequisitioncf.samplerequisitionid = aicrm_samplerequisition.samplerequisitionid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_samplerequisition.samplerequisitionid' ,'inner');
      $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_samplerequisition.accountid',"left");
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      
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
      $query = $this->db->get('aicrm_samplerequisition');

      /*echo $this->db->last_query();
      exit;*/
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total_samplerequisition($a_condition) ;
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

  function get_total_expense($a_condition=array())
  {
    try {
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }else{
        $a_condition["aicrm_crmentity.setype"] = "Expense";
        $a_condition["aicrm_crmentity.deleted"] = "0";
      }

      $this->db->select("count(DISTINCT aicrm_expense.expenseid) as total");
      $this->db->join('aicrm_expensecf', 'aicrm_expensecf.expenseid = aicrm_expense.expenseid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_expense.expenseid' ,'inner');
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      $query = $this->db->get('aicrm_expense');
      /*echo $this->db->last_query();
      exit;*/
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

  function get_list_expenses($a_condition=array(),$a_order=array(),$a_limit=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "Expense";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->select("aicrm_expense.* ,CASE WHEN (aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name
          ELSE aicrm_groups.groupname
        END AS user_name ",false);

      $this->db->join('aicrm_expensecf', 'aicrm_expensecf.expenseid = aicrm_expense.expenseid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_expense.expenseid' ,'inner');
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      
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
      $query = $this->db->get('aicrm_expense');

      /*echo $this->db->last_query();
      exit;*/
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total_expense($a_condition) ;
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

  function get_total_questionnaire($a_condition=array())
  {
    try {
      if (!empty($a_condition)) {
        $this->db->where($a_condition);
      }else{
        $a_condition["aicrm_crmentity.setype"] = "Questionnaire";
        $a_condition["aicrm_crmentity.deleted"] = "0";
      }

      $this->db->select("count(DISTINCT aicrm_questionnaire.questionnaireid) as total");
      $this->db->join('aicrm_questionnairecf', 'aicrm_questionnairecf.questionnaireid = aicrm_questionnaire.questionnaireid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_questionnaire.questionnaireid' ,'inner');
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      $query = $this->db->get('aicrm_questionnaire');
      /*echo $this->db->last_query();
      exit;*/
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

  function get_list_questionnaire($a_condition=array(),$a_order=array(),$a_limit=array())
  {
    $this->load->library('crmentity');
    try {
      $a_condition["aicrm_crmentity.setype"] = "Questionnaire";
      $a_condition["aicrm_crmentity.deleted"] = "0";
      
      $this->db->select("aicrm_questionnaire.* ,CASE WHEN (aicrm_users.user_name NOT LIKE '') THEN aicrm_users.user_name
          ELSE aicrm_groups.groupname
        END AS user_name ,ifnull(aicrm_account.accountname,'') AS accountname ",false);

      $this->db->join('aicrm_questionnairecf', 'aicrm_questionnairecf.questionnaireid = aicrm_questionnaire.questionnaireid','inner');
      $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_questionnaire.questionnaireid' ,'inner');
      $this->db->join('aicrm_account', 'aicrm_account.accountid = aicrm_questionnaire.accountid','left');
      $this->db->join('aicrm_groups', 'aicrm_groups.groupid = aicrm_crmentity.smownerid',"left");
      $this->db->join('aicrm_users', 'aicrm_crmentity.smownerid = aicrm_users.id','left');
      
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
      $query = $this->db->get('aicrm_questionnaire');

      /*echo $this->db->last_query();
      exit;*/
    
      if(!$query){
        $a_return["status"] = false;
        $a_return["error"] = $this->db->_error_message();
        $a_return["result"] = "";
      }else{
        $a_result  = $query->result_array() ;

        $a_total = $this->get_total_questionnaire($a_condition) ;
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