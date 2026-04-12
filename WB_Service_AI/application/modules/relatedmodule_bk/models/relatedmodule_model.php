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





}
