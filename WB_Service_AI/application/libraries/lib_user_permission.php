<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_user_permission
{


  function __construct()
  {
    $this->ci = & get_instance();
    $this->ci->load->database();
  }

  public function Get_user_privileges($userid,$get_data=""){
    $data = array();
    if($userid==""){
      return ;
    }
    require APPPATH."../../../../user_privileges/user_privileges_".$userid.".php";

      $data['current_user_roles'] = @$current_user_roles;// role ของ user
      $data['is_admin'] = @$is_admin; // role ที่อยู่เหนือกว่า userนี้
      $data['current_user_parent_role_seq'] = @$current_user_parent_role_seq; // role ที่อยู่เหนือกว่า userนี้
      $data['current_user_profiles'] = @$current_user_profiles ; // profileid ของ user
      $data['profileTabsPermission'] = @$profileTabsPermission ;// สิทธิ์การใช้งาน tab module ของ profileนี้
      $data['profileActionPermission'] = @$profileActionPermission ; // สิทธิ์การมองเห็น action ของแต่ละ tab
      $data['current_user_groups'] = @$current_user_groups ;// groupid ของ userนี้
      $data['parent_roles'] = @$parent_roles ;// role ที่อยู่ภายใต้ userนี้
      $data['user_info'] = @$user_info ; // ข้อมูลเกี่ยวกับ user นี้

    return $data;

  }


  public function Get_sharing_privileges($userid,$modulelist="",$related_module="",$get_data=""){

    if($userid==""){
      return ;
    }

    // $get_data = "defaultOrgSharingPermission";

    require APPPATH."../../../../user_privileges/sharing_privileges_".$userid.".php";
    $data['SharingPermission'] = $defaultOrgSharingPermission;
    $data['related_module'] = $related_module_share;

    if(!empty($modulelist)){

        $module = $modulelist;

        if($module=="Sales Visit" || $module=="Events"){
          $module="Calendar";
        }elseif($module=="Case"){
          $module="HelpDesk";
        }elseif ($module=="Spare Part" || $module=="SparePart") {
          $module = "Sparepart";
        }elseif ($module=="Errors List" || $module=="ErrorsList" ) {
          $module = "Errorslist";
        }elseif ($module=="Spare Part List" || $module=="SparePartList") {
          $module = "Sparepartlist";
        }elseif($module=="Quotation"){
          $module="Quotes";
        }

        switch($module){
          Case "Opportunity":
          $data[$module]['read']  = $Opportunity_share_read_permission;
          $data[$module]['write'] = $Opportunity_share_write_permission;
          break;
          Case "Job":
          $data[$module]['read']  = $Job_share_read_permission;
          $data[$module]['write'] = $Job_share_write_permission;
          break;
          Case "Sparepart":
          $data[$module]['read']  = $Sparepart_share_read_permission;
          $data[$module]['write'] = $Sparepart_share_write_permission;
          break;
          Case "Errors":
          $data[$module]['read']  = $Errors_share_read_permission;
          $data[$module]['write'] = $Errors_share_write_permission;
          break;
          Case "Sparepartlist":
          $data[$module]['read']  = $Sparepartlist_share_read_permission;
          $data[$module]['write'] = $Sparepartlist_share_write_permission;
          break;
          Case "Errorslist":
          $data[$module]['read']  = $Errorslist_share_read_permission;
          $data[$module]['write'] = $Errorslist_share_write_permission;
          break;
          Case "HelpDesk":
          $data[$module]['read']  = $HelpDesk_share_read_permission;
          $data[$module]['write'] = $HelpDesk_share_write_permission;
          break;
          Case "Accounts":
          $data[$module]['read']  = $Accounts_share_read_permission;
          $data[$module]['write'] = $Accounts_share_write_permission;
          break;
          Case "Leads":
          $data[$module]['read']  = $Leads_share_read_permission;
          $data[$module]['write'] = $Leads_share_write_permission;
          break;
          Case "Products":
          $data[$module]['read']  = $Products_share_read_permission;
          $data[$module]['write'] = $Products_share_write_permission;
          break;
          Case "Quotation":
          $data[$module]['read']  = $Quotation_share_read_permission;
          $data[$module]['write'] = $Quotation_share_write_permission;
          break;
          Case "Projects":
          $data[$module]['read']  = $Projects_share_read_permission;
          $data[$module]['write'] = $Projects_share_write_permission;
          break;
          Case "Project":
          $data[$module]['read']  = $Project_share_read_permission;
          $data[$module]['write'] = $Project_share_write_permission;
          break;
          Case "Documents":
          $data[$module]['read']  = $Documents_share_read_permission;
          $data[$module]['write'] = $Documents_share_write_permission;
          break;
          Case "Contacts":
          $data[$module]['read']  = $Contacts_share_read_permission;
          $data[$module]['write'] = $Contacts_share_write_permission;
          break;
          Case "Calendar":
          $data[$module]['read']  = $Calendar_share_read_permission;
          $data[$module]['write'] = $Calendar_share_write_permission;
          break;
          Case "Events":
          $data[$module]['read']  = $Events_share_read_permission;
          $data[$module]['write'] = $Events_share_write_permission;
          break;
          Case "Quotes":
          $data[$module]['read']  = $Quotes_share_read_permission;
          $data[$module]['write'] = $Quotes_share_write_permission;
          break;
          Case "Serial":
          $data[$module]['read']  = $Serial_share_read_permission;
          $data[$module]['write'] = $Serial_share_write_permission;
          break;
          default:
        }
      }

    return $data;

  }

  public function module_sharing($module,$userid){

    if($userid==""){
      return ;
    }

    require APPPATH."../../../../user_privileges/sharing_privileges_10883.php";

    switch($module){
      Case "Opportunity":
      break;
      Case "Job":
      break;
      Case "Sparepart":
      break;
      Case "Errors":
      break;
      Case "Sparepartlist":
      break;
      Case "Errorslist":
      break;
      Case "HelpDesk":
      break;
      Case "Accounts":
      $data = $Accounts_share_read_permission;
      break;
      Case "Leads":
      break;
      Case "Products":
      break;
      Case "Quotation":
      break;
      Case "Projects":
      break;
      Case "Documents":
      break;
      Case "Contacts":
      break;
      Case "Calendar":
      break;
      Case "Events":
      break;
      Case "Quotes":
      break;
      Case "Serial":
      break;
      default:
    }

    return $module;
  }



}
