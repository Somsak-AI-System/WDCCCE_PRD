<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

class relatedmodule_model extends CI_Model
{
  var $ci;


  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
	  $this->load->library("common");
	  $this->load->library('crmentity');

    $this->_limit = "10";
  }


  public function Get_Relate($module,$crm_id){
    $a_return = array();

    $tabid=$this->lib_api_common->Get_Tab_ID($module);

    if($tabid==""){
      $a_return["status"] = false;
      $a_return["error"] = "No Module";
      return $a_return;
    }

    if($module=="Sales Visit" || $module=="Events" || $module=="SalesVisit"  || $module=="Calendar" ){
      $module = "Calendar";
    }

    $sql=" select *
    from aicrm_relatedlists_sql
    where 1 and tabid='".$tabid."' and presence = 0";

    $sql .= " order by sequence ";
    $query = $this->db->query($sql);

    if($query->num_rows()>0){
      $data_block = $query->result_array();

      foreach($data_block as $key => $val){

        $sql = $val['sql'];
        $data_related[$key]['relation_id']=$val['relation_id'];
        $data_related[$key]['tabid']=$val['tabid'];
        $data_related[$key]['related_tabid']=$val['related_tabid'];
        $data_related[$key]['module']=$val['label'];
        $data_related[$key]['actions']=$val['actions'];

        $data = $this->get_queryrelated($sql,$crm_id);

        if(!empty($data)){
            $data_related[$key]['total'] = count($data);
            $data_related[$key]['relateList'] = $data;
        }else {
          $data_related[$key]['relateList'] = [];
        }
      }
      $a_form = $data_related;

    }
    $a_return["status"] = true;
    $a_return["error"] = "";
    $a_return["data"] = $a_form;
    return $a_return;
  }


  function get_queryrelated($sql,$crm_id){

    $sql_related= $sql.$crm_id." and aicrm_crmentity.deleted = 0";
    $query = $this->db->query($sql_related);
    $data = $query->result_array();
    if(!empty($data)){
      foreach($data as $key => $val){
        foreach($val as $k => $v){
          if($v==null){
            $v="";
            $val[$k] = $v;
            $val_change = $val[$k];
          }
          $val[$k] = $v;
        }
        $val['moduleid'] = $crm_id;
        $data[$key] = $val;
      }
    }else {
      $data ="";
    }

    return $data;

  }

  function deleted_data($a_request){

    $userid = $a_request['userid'];
    $crmid = $a_request['crmid'];
    $relatedid = $a_request['crm_subid'];
    $module = $a_request['module'];


    if(!empty($crmid)&&!empty($relatedid)){

      if($module=="Serial"){

									$check_inspection = "SELECT aicrm_inspection.inspectionid
									FROM aicrm_inspection
									INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid
									INNER JOIN aicrm_serial on aicrm_serial.serialid = aicrm_inspection.serialid
									WHERE aicrm_crmentity.deleted =0 and aicrm_inspection.serialid = '".$relatedid."'
									and aicrm_inspection.jobid = '".$crmid."' ";

					// if($this->db->query($check_serialname)){
								$querycheck_inspection = $this->db->query($check_inspection);
								$datacheck_inspection = $querycheck_inspection->result_array();
							
								if(!empty($datacheck_inspection)){
										$inspectionid = $datacheck_inspection[0]['inspectionid'];
								}else{
										$inspectionid ="";
								}


								if(!empty($inspectionid)){
													$sql_update = "UPDATE aicrm_crmentity SET deleted=1 WHERE crmid='".$inspectionid."'";
													$this->db->query($sql_update);
								}

									$sql=" DELETE FROM aicrm_crmentityrel WHERE crmid='".$crmid."' AND  relcrmid='".$relatedid."' ";

      }elseif($module=="Tools"){

									$sql=" DELETE FROM aicrm_crmentityrel WHERE crmid='".$crmid."' AND  relcrmid='".$relatedid."' ";

      }else{

         $sql=" UPDATE aicrm_crmentity SET deleted =1 WHERE crmid='".$relatedid."' ";

      }

     
     if($this->db->query($sql)){

      $a_return["status"] = true;
      $a_return["error"] =  "Delete Success";
      $a_return["result"] = "";

    }else{

      $a_return["status"] = false;
      $a_return["error"] =  "Delete fail!";
      $a_return["result"] = "";

    }


  }else{
      $a_return["status"] = false;
      $a_return["error"] =  "No id";
      $a_return["result"] = "";
  }

return $a_return;

  }





}
