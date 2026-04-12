<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lib_api_common
{


  function __construct()
  {
    $this->ci = &get_instance();
    $this->ci->load->database();
    $this->ci->load->library('crmentity');
    $this->ci->load->library('Lib_user_permission');
    $this->ci->load->library('Lib_set_notification');
  }

  public function Get_Tab_ID($module)
  {
    $tabid = "";
    if ($module == "Sales Visit" || $module == "Events" || $module == "SalesVisit"  || $module == "Calendar") {
      $module = "Calendar";
    }

    $sql = " select tabid
    from aicrm_tab
    where 1
    and name='" . $module . "' ";

    $query = $this->ci->db->query($sql);

    if ($query->num_rows() > 0) {
      $data = $query->result_array();
      $tabid = $data[0]["tabid"];
    }
    return $tabid;
  }

  public function Get_field($tabid, $crm_id, $action, $userid = "", $quickcreate = "2")
  {
    $where = "";
    if ($tabid == '16') {
      // if($action=="add"){
      //   $where = " and block in  ( '86', '41','387','389','390','391')";
      // }
      if ($action == "view") {
        // $where = " and block in  ( '86', '41','387','389','390','391') ";
        $where = " and block in  ( '86', '41','387','389','390','391','553','604','671') ";
      } else {
        /**
         * 2022-08-15 [No#122][Issue] [Desciption: Dup. รายการก่อนหน้ามาแล้ว หลัง Save ข้อมูลไม่ขึ้น ""วันที่สิ้นสุด"" ซึ่งวันที่สิ้นสุดจะเป็นวันเดียวกันกับวันที่เริ่ม]
         */
        $where = " and block in  ( '86', '41','387','389','390','391','553','604','671')";
        // $where = " and block in  ( '86', '41','387','389','390','391','553','604') and aicrm_field.columnname NOT in ('due_date') ";
      }
    }

    if ($tabid == "41" && $action == "view") {
      $where = "";
    } elseif ($tabid == "41" && $action == "add") {
      $where = "and block not in ('426','425')";
    } elseif ($tabid == "41" && $action == "edit") {
      $where = "and aicrm_field.columnname NOT in ('customer_signature_time','user_signature_time','image_customer','image_user')";
    }
    if ($tabid == '29') {
      if ($crm_id == "") {

        $sql = " select DISTINCT aicrm_field.columnname, aicrm_field.tablename,aicrm_field.fieldlabel,aicrm_field.fieldname,aicrm_field.uitype,aicrm_field.generatedtype,aicrm_field.typeofdata,aicrm_field.block,aicrm_field.readonly
        from  aicrm_users
        LEFT JOIN aicrm_user2role on aicrm_user2role.userid =aicrm_users.id
        LEFT JOIN aicrm_role2profile on aicrm_role2profile.roleid = aicrm_user2role.roleid
        LEFT JOIN aicrm_profile2tab on aicrm_profile2tab.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_profile2field on aicrm_profile2field.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_profile2field.fieldid
        where 1 and  aicrm_field.presence in (0,2) and aicrm_field.quickcreate in(0,2,3,1) and aicrm_field.columnname NOT in ('user_password','confirm_password') and aicrm_profile2field.visible not in(1)
        and aicrm_field.tabid='" . $tabid . "' and aicrm_field.uitype!= '70'   and aicrm_users.id ='" . $userid . "' ";
      } else {
        $sql = " select DISTINCT aicrm_field.columnname, aicrm_field.tablename,aicrm_field.fieldlabel,aicrm_field.fieldname,aicrm_field.uitype,aicrm_field.generatedtype,aicrm_field.typeofdata,aicrm_field.block,aicrm_field.readonly
        from  aicrm_users
        LEFT JOIN aicrm_user2role on aicrm_user2role.userid =aicrm_users.id
        LEFT JOIN aicrm_role2profile on aicrm_role2profile.roleid = aicrm_user2role.roleid
        LEFT JOIN aicrm_profile2tab on aicrm_profile2tab.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_profile2field on aicrm_profile2field.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_profile2field.fieldid
        where 1 and  aicrm_field.presence in (0,2) and aicrm_field.quickcreate in(0,2,3,1) and aicrm_field.columnname NOT in ('user_password','confirm_password')
        and aicrm_field.tabid='" . $tabid . "' and aicrm_users.id ='" . $userid . "' ";
      }
    /*} else if ($tabid == '50') {
      if ($crm_id == "") {

        $sql = " select DISTINCT aicrm_field.columnname, aicrm_field.tablename,aicrm_field.fieldlabel,aicrm_field.fieldname,aicrm_field.uitype,aicrm_field.generatedtype,aicrm_field.typeofdata,aicrm_field.block,aicrm_field.readonly
        from  aicrm_users
        LEFT JOIN aicrm_user2role on aicrm_user2role.userid =aicrm_users.id
        LEFT JOIN aicrm_role2profile on aicrm_role2profile.roleid = aicrm_user2role.roleid
        LEFT JOIN aicrm_profile2tab on aicrm_profile2tab.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_profile2field on aicrm_profile2field.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_profile2field.fieldid
        where 1 and  aicrm_field.presence in (0,2) and aicrm_field.quickcreate in(0,2,3,1) and aicrm_field.columnname NOT in ('user_password','confirm_password') and aicrm_profile2field.visible not in(1)
        and aicrm_field.tabid='" . $tabid . "' and aicrm_field.uitype!= '70'   and aicrm_users.id ='" . $userid . "' ";
      } else {
        $sql = " select DISTINCT aicrm_field.columnname, aicrm_field.tablename,aicrm_field.fieldlabel,aicrm_field.fieldname,aicrm_field.uitype,aicrm_field.generatedtype,aicrm_field.typeofdata,aicrm_field.block,aicrm_field.readonly
        from  aicrm_users
        LEFT JOIN aicrm_user2role on aicrm_user2role.userid =aicrm_users.id
        LEFT JOIN aicrm_role2profile on aicrm_role2profile.roleid = aicrm_user2role.roleid
        LEFT JOIN aicrm_profile2tab on aicrm_profile2tab.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_profile2field on aicrm_profile2field.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_profile2field.fieldid
        where 1 and  aicrm_field.presence in (0,2) and aicrm_field.quickcreate in(0,2,3,1) and aicrm_field.columnname NOT in ('user_password','confirm_password')
        and aicrm_field.tabid='" . $tabid . "' and aicrm_users.id ='" . $userid . "' ";
      }*/
    } else {

      if ($crm_id == "") {
        $sql = " select  DISTINCT aicrm_field.columnname, aicrm_field.tablename,aicrm_field.fieldlabel,aicrm_field.fieldname,aicrm_field.uitype,aicrm_field.generatedtype,aicrm_field.typeofdata,aicrm_field.block,aicrm_field.readonly,aicrm_field.maximumlength
        from  aicrm_users
        LEFT JOIN aicrm_user2role on aicrm_user2role.userid =aicrm_users.id
        LEFT JOIN aicrm_role2profile on aicrm_role2profile.roleid = aicrm_user2role.roleid
        LEFT JOIN aicrm_profile2tab on aicrm_profile2tab.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_profile2field on aicrm_profile2field.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_profile2field.fieldid
        where 1 and  aicrm_field.presence in (0,2) and aicrm_field.quickcreate in(0,2) and aicrm_field.columnname NOT in ('user_password','confirm_password') and aicrm_profile2field.visible not in(1)
        and aicrm_field.tabid='" . $tabid . "' and aicrm_field.uitype!= '70' and aicrm_users.id ='" . $userid . "'  " . $where . " ";
      } elseif ($action == "duplicate") {
        $sql = " select DISTINCT aicrm_field.columnname, aicrm_field.tablename,aicrm_field.fieldlabel,aicrm_field.fieldname,aicrm_field.uitype,aicrm_field.generatedtype,aicrm_field.typeofdata,aicrm_field.block,aicrm_field.readonly,aicrm_field.maximumlength
        from  aicrm_users
        LEFT JOIN aicrm_user2role on aicrm_user2role.userid =aicrm_users.id
        LEFT JOIN aicrm_role2profile on aicrm_role2profile.roleid = aicrm_user2role.roleid
        LEFT JOIN aicrm_profile2tab on aicrm_profile2tab.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_profile2field on aicrm_profile2field.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_profile2field.fieldid
        where 1 and  aicrm_field.presence in (0,2) and aicrm_field.quickcreate in(0,2) and aicrm_field.columnname NOT in ('user_password','confirm_password') and aicrm_profile2field.visible not in(1)
        and aicrm_field.tabid='" . $tabid . "' and aicrm_field.uitype!= '70' and aicrm_users.id ='" . $userid . "'  " . $where . " ";
      } else {
        $sql = " select DISTINCT aicrm_field.columnname, aicrm_field.tablename,aicrm_field.fieldlabel,aicrm_field.fieldname,aicrm_field.uitype,aicrm_field.generatedtype,aicrm_field.typeofdata,aicrm_field.block,aicrm_field.readonly,aicrm_field.maximumlength
        from  aicrm_users
        LEFT JOIN aicrm_user2role on aicrm_user2role.userid =aicrm_users.id
        LEFT JOIN aicrm_role2profile on aicrm_role2profile.roleid = aicrm_user2role.roleid
        LEFT JOIN aicrm_profile2tab on aicrm_profile2tab.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_profile2field on aicrm_profile2field.profileid = aicrm_role2profile.profileid
        LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_profile2field.fieldid
        where 1 and  aicrm_field.presence in (0,2) and aicrm_field.quickcreate in(0,2,3) and aicrm_profile2field.visible not in(1)
        and aicrm_field.tabid='" . $tabid . "'  and aicrm_users.id ='" . $userid . "'  " . $where . " ";
        //alert( $sql);exit;
      }
      //echo $sql; exit;
    }
    $sql .= " ORDER BY block,sequence,quickcreatesequence ";
    $query = $this->ci->db->query($sql);
    $data_field = $query->result_array();
    
    if ($tabid == '7' && $data_field != "" && $action == 'leadupdate') {

      $data_field = $this->check_leadfield($crm_id, $data_field);
    }

    $a_return = array();
    if (!empty($data_field)) {
      foreach ($data_field as $k => $v) {
        $block_id = $v["block"];
        $a_return[$block_id][] = $v;
      }
    }
    return $a_return;
  }

  public function Get_Block($module, $action, $crm_id = "", $userid, $crm_subid = "", $related_module = "", $templateid = "")
  {
    $action_button = array();
    $where = '';
    $a_return = array();
    $tabid = $this->Get_Tab_ID($module);

    $action_button = array(
      'Create' => "true",
      'Edit' => "true",
      'Duplicate' => "true",
      'Delete' => "flase",
      'View' => "true"
    );
    if ($tabid == "") {
      $a_return["status"] = false;
      $a_return["error"] = "No Module";
      return $a_return;
    }

    if ($module == "Sales Visit" || $module == "Events" || $module == "SalesVisit"  || $module == "Calendar") {
      $module = "Calendar";
      $tabid = "16";
    }

    if ($tabid == '16') {
      if ($crm_id == "" || $action == 'duplicate') {
        $where = " and blockid in  ( '86', '41', '389', '390', '391', '553', '604' , '671') ";
      } else {
        if ($action == "view") {
          $where = " and blockid in  ( '86', '41','387','389','390','391','388' ,'671') ";
        } else {
          $where = " and blockid in  ( '86', '41', '389', '390', '391', '553', '604' , '671') ";
        }
      }
    } elseif ($tabid == '20') {
      //$where = " and blockid in ('49', '54', '289', '558') ";
      $where = " and blockid in ('49','53','54','289','428','429','558','684','685','686') ";
      
    } elseif ($tabid == '6') {
      $where = " and blockid not in ('518') ";
    } elseif ($tabid == '90') {
      $where = " and blockid not in ('509') ";
    } elseif ($tabid == '7') {
      $where = " and blockid not in ('512') ";
    } elseif ($tabid == '93') {
      $where = " and blockid not in ('503') ";
    }

    $sql = " select
    blockid,blocklabel,sequence
    from aicrm_blocks
    where 1 and tabid='" . $tabid . "'";
    if ($where != "") {
      $sql .= $where;
    }
    $sql .= " order by sequence "; 
    $query = $this->ci->db->query($sql);

    if($query->num_rows() > 0) {
      $data_block = $query->result_array();

      $a_block = array();
      $a_field = $this->Get_field($tabid, $crm_id, $action, $userid);
      
      $a_form = array();
      foreach ($data_block as $key => $val) {
        $blockid = $val["blockid"];
        if ($blockid == "41") {
          $blockname = "LBL_EVENT_INFORMATION";
        } else {
          $blockname = $val["blocklabel"];
        }

        if (!empty($a_field[$blockid])) {

          if ($module == "Questionnaire" && $blockname == "Answer Information") {
            $field = $this->questionnaire_template(@$a_field[$blockid], $blockname, $crm_id, $userid, $action, $templateid);
            $a_form[] = $field;
          } else {
            $field = $this->ci->crmentity->Get_field($module, @$a_field[$blockid], $blockname, $crm_id, $userid, $crm_subid, $related_module, $action, $templateid);
            $a_form[] = $field[1];
            $data_title = $field[0];

            if ($module == 'Deal' && $crm_id != "" && $action == "view" && $blockid == '490') {
              $sql = $this->ci->db->query("SELECT 
              tbt_log_quote_saleorder.*
              ,aicrm_users.user_name
              FROM tbt_log_quote_saleorder
              LEFT JOIN aicrm_users ON aicrm_users.id = tbt_log_quote_saleorder.userid
              WHERE tbt_log_quote_saleorder.crmid =" . $crm_id);
              $changeStatusHistory = $sql->result_array();

              $rowData = [];
              foreach ($changeStatusHistory as $item) {
                $rowData[] = [
                  'columnname' => 'history_status',
                  'uitype' => '9902',
                  'readonly' => '1',
                  'type' => 'text',
                  'maximumlength' => '500',
                  'keyboard_type' => 'comments',
                  'status' => $item['status'],
                  'comments' => $item['comments'],
                  'comments_by' => $item['user_name'],
                  'date_time' => $item['date_time']
                ];
              }

              $BlockHistory = [
                'header_name' => 'History Status',
                'form' => $rowData
              ];
              $a_form[] = $BlockHistory;
            }
          }
        } //if

      } //foreach
      // alert($a_form); 
      // exit();

    }
    //exit; 
    if ($module == 'Deal' && $crm_id != "" && $action == "view") {
      $formstage = $this->deal_stage($crm_id);
    }

    if ($module == 'Calendar' && $crm_id != "" && $action == "view") {

      $get_usercomment = $this->usercomment($crm_id);

      if (!empty($get_usercomment)) {
        $data = array(
          'header_name' => 'Comment Information',
          'form' => $get_usercomment
        );
      } else {
        $data = array(
          'header_name' => 'Comment Information',
          'form' => array()
        );
      }

      array_push($a_form, $data);
    }

    if ($crm_id != "") {

      $get_data = "profileActionPermission";
      $action_permission = $this->ci->lib_user_permission->Get_user_privileges($userid, $get_data);
      //alert($action_permission); exit;
      if (!empty($action_permission)) {

        /*** 
         * Log Update
         * [No#128][Issue] [Desciption: ปุ่ม Action Delete ด้านล่างกลายเป็น Icon More ซึ่งต้องเป็น Icon ถังขยะ
         */
        // if ($action_permission['is_admin'] == 1) {
        //   $action_button['Create'] = "true";
        //   $action_button['Edit'] = "true";
        //   $action_button['Duplicate'] = "true";
        //   $action_button['Delete'] = "true";
        //   $action_button['View'] = "true";
         
        //   if($module!='Calendar'){
        //     $action_button['ConvertLead'] = "true";
        //   }
          
        // } else {
          if (!empty($action_permission[$tabid])) {
            foreach ($action_permission[$tabid] as $key => $value) {
              if ($value == 1) {
                $value_action = "flase";
              } else {
                $value_action = "true";
              }
              $sql_get_action = "select actionname from aicrm_actionmapping
                where actionid ='" . $key . "'";
              $query_permission = $this->ci->db->query($sql_get_action);
              $response_permission = $query_permission->result_array();

              foreach ($response_permission as $k => $v) {
                if ($v['actionname'] == 'Save') {
                  $action_button['Create'] = $value_action;
                } elseif ($v['actionname'] == 'EditView') {
                  $action_button['Edit'] = $value_action;
                } elseif ($v['actionname'] == 'DuplicatesHandling') {
                  $action_button['Duplicate'] = $value_action;
                } elseif ($v['actionname'] == 'Delete') {
                  $action_button['Delete'] = $value_action;
                } elseif ($v['actionname'] == 'DetailView') {
                  $action_button['View'] = $value_action;
                } elseif ($v['actionname'] == 'ConvertLead') {
                  $action_button['ConvertLead'] = $value_action;
                }
              }
            }
          }
        // }
      }

      if ($action == "view" && ($module != 'Leads' && $module != 'Calendar')){

        $addmodule_button = array();

        $module_sql = "select related_tabid ,label from aicrm_relatedlists
        LEFT JOIN aicrm_tab on aicrm_tab.tabid = aicrm_relatedlists.related_tabid
        where aicrm_relatedlists.tabid='" . $tabid . "'
        and aicrm_relatedlists.presence='0'
        and aicrm_relatedlists.tabid not in (77,61,0)
        and aicrm_tab.mobile_seq!=0 ";

        $query_module = $this->ci->db->query($module_sql);
        $response_module = $query_module->result_array();

        foreach ($response_module as $key => $value) {
          if ($value['label'] == "Attachments") {
            $value['label'] = "Documents";
          } elseif ($value['label'] == "HelpDesk") {
            $value['label'] = "Case";
          } elseif ($value['label'] == "CustomizeReport") {
            $value['label'] = "Sales Report";
          } elseif ($value['label'] == "Projectorder") {
            $value['label'] = "Project Orders";
          } elseif ($value['label'] == "Errorslist") {
            $value['label'] = "Errors List";
          } elseif ($value['label'] == "Sparepart") {
            $value['label'] = "Spare Part";
          } elseif ($value['label'] == "Sparepartlist") {
            $value['label'] = "Spare Part List";
          } elseif ($value['label'] == "HelpDesk") {
            $value['label'] = "Case";
          } elseif ($value['label'] == "Quotes") {
            $value['label'] = "Quotation";
          }
          /***Log Update
          [No#130][Issue] [Desciption: Action ด้านล่างเยอะเกินไป ปิดเฉพาะที่จำเป็น
          */
          // $addmodule_button[$key] = $value['label'];

        }

      }
      
    }

    if ($module == 'Leads' && $action == "view") {

      $sql_convert = "select leadid,converted from aicrm_leaddetails
      where aicrm_leaddetails.leadid='" . $crm_id . "' ";

      $query_convert = $this->ci->db->query($sql_convert);
      $response_convert = $query_convert->row_array();

      if ($response_convert['converted'] != 1) {
        $action_button['ConvertLead'] = "true";
        // $lead_button['convert'] = "true";
      } else {
        $action_button['ConvertLead'] = "false";
        // $lead_button['convert'] = "false";
        /**
         * Log Update
         * [No#130][Issue] [Desciption: Action ด้านล่างเยอะเกินไป ปิดเฉพาะที่จำเป็น
         * ต้องการ Convert/Edit/Dup/Delete ที่เป็น True นอกนั้นเป็น False ให้หมด
         */
        $action_button['Create'] = "false";
        $action_button['Edit'] = "false";
        $action_button['Duplicate'] = "true";
        $action_button['Delete'] = "false";
        $action_button['View'] = "false";
      }
    }

    /*if($module=='Calendar' && $action=="view"){

      $sql_activity = "select activityid,flag_send_report from aicrm_activity
      where aicrm_activity.activityid='".$crm_id."' ";

      $query_activity = $this->ci->db->query($sql_activity);
      $response_activity= $query_activity->row_array();
      
        if($response_activity['flag_send_report']!=1) {
          $action_button['Edit'] = "true";
          $action_button['Delete'] = "true";
        }else{
          $action_button['Edit'] = "false";
          $action_button['Delete'] = "false";
        }
    }*/

    $customData = [];
    if ($module == 'Deal' && $action == "view") {

      $sql_stage = "select dealid,stage from aicrm_deal
      where aicrm_deal.dealid='" . $crm_id . "' ";

      $query_stage = $this->ci->db->query($sql_stage);
      $response_stage = $query_stage->row_array();

      if ($response_stage['stage'] == "Closed Won" || $response_stage['stage'] == "Closed Lost") {
        $action_button['Edit'] = "false";
        $action_button['Delete'] = "false";
      }

      $user = $this->ci->common->get_role($userid);
      $roleid = $user[0]['roleid'];

      $sql = $this->ci->db->query("SELECT aicrm_wonreason.wonreason 
      FROM aicrm_wonreason 
      INNER JOIN aicrm_role2picklist ON aicrm_wonreason.picklist_valueid = aicrm_role2picklist.picklistvalueid
      WHERE aicrm_wonreason.wonreason <> ''
      AND roleid IN ('" . $roleid . "')
      ORDER BY aicrm_role2picklist.sortid");
      foreach ($sql->result_array() as $item) {
        $wonreason[] = $item['wonreason'];
      }

      $sql = $this->ci->db->query("SELECT aicrm_lostreason.lostreason 
      FROM aicrm_lostreason 
      INNER JOIN aicrm_role2picklist ON aicrm_lostreason.picklist_valueid = aicrm_role2picklist.picklistvalueid
      WHERE aicrm_lostreason.lostreason <> ''
      AND roleid IN ('" . $roleid . "')
      ORDER BY aicrm_role2picklist.sortid");
      foreach ($sql->result_array() as $item) {
        $lostreason[] = $item['lostreason'];
      }

      $customData['wonreason'] = $wonreason;
      $customData['lostreason'] = $lostreason;
    }

    // Get module setting flag
    $sql = $this->ci->db->get_where('aicrm_setting_module_flags', ['module' => $module]);
    $settingFlags = $sql->row_array();

    array_walk_recursive($settingFlags, function (&$item, $key) {
      $item = null === $item ? '0' : $item;
    });

    if (!empty($settingFlags)) {
      unset($settingFlags['module']);
    }

    if ($a_form != "") {
      // ############# My RECENT ###################
      $date = date("Y-m-d h:i:s");

      $select_id = "select id from aicrm_audit_trial_seq";
      $query_seq = $this->ci->db->query($select_id);
      $data = $query_seq->result_array();
      $id_audit = $data[0]['id'];
      $id = $id_audit;
      $id_audit =   $id_audit + 1;

      $sql = "INSERT INTO aicrm_audit_trial(auditid,userid,module,action,recordid,actiondate)
      VALUES('" . $id_audit . "','" . $userid . "','" . $module . "','DetailView','" . $crm_id . "','" . $date . "')";
      $query = $this->ci->db->query($sql);

      $sql_seq = "UPDATE  aicrm_audit_trial_seq  SET id='" .  $id_audit . "' WHERE id='" . $id . "'";
      $query_seq = $this->ci->db->query($sql_seq);
    }

    $user_lang = "EN";
    $user_lang = $this->ci->crmentity->Get_userlang($userid);

    $a_return["status"] = true;
    $a_return["error"] = "";
    $a_return["button"] = $action_button;
    $a_return["setting_flags"] = empty($settingFlags) ? null:$settingFlags;
    $a_return["related_button"] = $addmodule_button;
    // $a_return["lead_status"] = $lead_button['status_button'];
    // $a_return["request_approve"] = $lead_button['request_approve'];
    // $a_return["approved"] = $lead_button['approved'];
    $a_return['customData'] = empty($customData) ? null:$customData;
    $a_return["convert"] = @$lead_button['convert'];
    $a_return["language"] = $user_lang;
    $a_return["title"] = $data_title;

    if ($module == 'Deal' && $crm_id != "" && $action == "view") {
      $a_return["laststage"] = 'Closed';
      $a_return["custom"] = $formstage;
    }

    $a_return["data"] = $a_form;

    return $a_return;
  }

  function deal_stage($crmid)
  {
    $sql_stage = " select aicrm_deal.stage , ifnull(aicrm_deal.expectedclosedate,'') as duedate ,
    ifnull(concat('฿',FORMAT(aicrm_deal.dealamount, 2)),'0') as amount , aicrm_products.productname AS productname
    from aicrm_deal
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_deal.dealid
    left join aicrm_products on aicrm_products.productid = aicrm_deal.product_id
    where 1 and crmid='" . $crmid . "' and aicrm_crmentity.deleted = 0";

    $query_stage = $this->ci->db->query($sql_stage);
    if (!empty($query_stage)) {
      $stagedata = $query_stage->result_array();
      $stagedata = $stagedata[0];
      $defaultstage = $stagedata['stage'];
    } else {
      $stagedata = array();
      $defaultstage = '';
    }

    $sql = " select aicrm_stage.*
    from aicrm_stage
    where  presence='1' ";
    $sql .= " order by sequence asc ";
    $query = $this->ci->db->query($sql);
    $allstage = $query->result_array();
    $form_stage = array();
    $count_stage = 0;

    $duedate = array('columnname' => 'Due Date', 'value' => $stagedata['duedate']);
    $amount = array('columnname' => 'Amount', 'value' => $stagedata['amount']);
    $productname = array('columnname' => 'Product Name', 'value' => $stagedata['productname']);

    $green = "#00e296";
    $yellow = "#feb018";
    $red = "#ff4560";
    $white = "#ffffff";
    $gray = "#b6b6b6";

    foreach ($allstage as $key => $value) {
      if ($stagedata['stage'] == $value['stage']) {
        $count_stage = $key;
      }
    }

    foreach ($allstage as $key => $value) {

      //if($value['stage']!="ปิดแพ้" && $value['stage']!="ปิดชนะ"){
      if ($value['flag_closed'] != 1) {
        //echo $value['stage']; echo "<br>";
        $stage = array('columnname' => 'Deal Stage', 'value' => $value['stage']);

        $form_stage[$key]['header_name'] = $value['stage'];
        $form_stage[$key]['flag_closed'] = $value['flag_closed'];

        if ($count_stage >= $key) {
          $form_stage[$key]['stage_status'] = "true";

          if ($defaultstage != "Closed Lost") {
            if ($value['stage'] == $defaultstage) {
              $form_stage[$key]['color'] = $white;
              $form_stage[$key]['backgroundcolor'] = $yellow;
            } else {
              $form_stage[$key]['color'] = $white;
              $form_stage[$key]['backgroundcolor'] = $green;
            }
          } else {
            $form_stage[$key]['color'] = $gray;
            $form_stage[$key]['backgroundcolor'] = $white;
          }
        } else {
          $form_stage[$key]['stage_status'] = "false";
          $form_stage[$key]['color'] = $gray;
          $form_stage[$key]['backgroundcolor'] = $white;
        }

        $form_stage[$key]['form'][] = $stage;
        $form_stage[$key]['form'][] = $duedate;
        $form_stage[$key]['form'][] = $amount;
        $form_stage[$key]['form'][] = $productname;
      } else {

        $stage = array('columnname' => 'Deal Stage', 'value' => $value['stage']);
        $form_stage[$key]['header_name'] = $value['stage'];
        $form_stage[$key]['flag_closed'] = $value['flag_closed'];

        if ($value['stage'] == $defaultstage) {
          $form_stage[$key]['stage_status'] = "true";

          if ($value['stage'] == "Closed Lost") {
            $form_stage[$key]['color'] = $white;
            $form_stage[$key]['backgroundcolor'] = $red;
          } else {
            $form_stage[$key]['color'] = $white;
            $form_stage[$key]['backgroundcolor'] = $green;
          }
        } else {
          $form_stage[$key]['stage_status'] = "false";
          $form_stage[$key]['color'] = $gray;
          $form_stage[$key]['backgroundcolor'] = $white;
        }

        $form_stage[$key]['form'][] = $stage;
        $form_stage[$key]['form'][] = $duedate;
        $form_stage[$key]['form'][] = $amount;
        $form_stage[$key]['form'][] = $productname;
      }
    }

    return $form_stage;
  }


  function questionnaire_template($a_field = array(), $blockname = "", $crm_id = "", $userid = "", $action = "", $templateid = "")
  {

    $data_template = array();
    $d_awnser = array();

    if ($action == "view" || $action == "edit") {

      $awnser = "SELECT aicrm_questionnaire_choice.choiceid,aicrm_questionnaire_choice.choice_name , 
          aicrm_questionnaire_choice.choice_type , 
          aicrm_questionnaire_choice.hasother ,
          aicrm_questionnaire_choicedetail.choicedetailid ,
          aicrm_questionnaire_choicedetail.choicedetail_name ,
          aicrm_questionnaire_choicedetail.choicedetail_other ,
          aicrm_questionnaire_answer.choicedetail,
          aicrm_questionnaire.questionnairetemplateid
         FROM aicrm_questionnaire_answer
         inner join aicrm_questionnaire_choice on aicrm_questionnaire_choice.choiceid = aicrm_questionnaire_answer.choiceid
         inner join aicrm_questionnaire_choicedetail on aicrm_questionnaire_choicedetail.choicedetailid = aicrm_questionnaire_answer.choicedetailid
         inner join  aicrm_questionnaire on aicrm_questionnaire.questionnaireid = aicrm_questionnaire_answer.questionnaireid
         WHERE aicrm_questionnaire_answer.questionnaireid = '" . $crm_id . "' order by aicrm_questionnaire_choice.choiceid";
      $query = $this->ci->db->query($awnser);
      $a_awnser = $query->result_array();
      //alert($a_awnser);exit;
      //alert($awnser);exit;

      if (!empty($a_awnser)) {
        $d_awnser = array();
        $choiceid = '';
        foreach ($a_awnser as $key => $value) {

          $templateid = $value['questionnairetemplateid'];

          if ($value['choiceid'] == $choiceid) {

            if ($value['choice_type'] == 'checkbox') {
              if ($value['choicedetail_other']  == '1') {
                $d_awnser[$value['choice_name']]['values'][] = $value['choicedetail'];
                // $d_awnser[$value['choice_name']]['values'][]= $value['choicedetail_name'];
                $d_awnser[$value['choice_name']]['check_other'] =  true;
              } else {
                $d_awnser[$value['choice_name']]['values'][] = $value['choicedetail_name'];
              }
            }
            $choiceid = $value['choiceid'];
          } else {

            if ($value['choice_type'] == 'checkbox') {
              $d_awnser[$value['choice_name']] = array();
              if ($value['choicedetail_other']  == '1') {
                $d_awnser[$value['choice_name']]['values'][] = $value['choicedetail'];
                // $d_awnser[$value['choice_name']]['values'][]= $value['choicedetail_name'];
                $d_awnser[$value['choice_name']]['check_other'] =  true;
              } else {
                $d_awnser[$value['choice_name']]['values'][] = $value['choicedetail_name'];
              }
            } elseif ($value['choice_type'] == 'text') {
              $d_awnser[$value['choice_name']] = array();
              $d_awnser[$value['choice_name']]['values'][] = $value['choicedetail'];
              // $d_awnser[$value['choice_name']]['values'][]= $value['choicedetail_name'];
              $d_awnser[$value['choice_name']]['check_other'] =  false;
            } else {

              if ($value['choicedetail_other']  == '1') {
                $d_awnser[$value['choice_name']]['values'][] = $value['choicedetail'];
                // $d_awnser[$value['choice_name']]['values'][]= $value['choicedetail_name'];
                $d_awnser[$value['choice_name']]['check_other'] =  true;
              } else {
                $d_awnser[$value['choice_name']]['values'][] = $value['choicedetail_name'];
                $d_awnser[$value['choice_name']]['check_other'] =  false;
              }
            }

            $choiceid = $value['choiceid'];
          } //if

        } //foreach
      } //if empty

      // alert($d_awnser);
      // exit;

    }
    // else{
    if (empty($templateid)) {

      $sql_template = "SELECT 
          aicrm_questionnaire.questionnairetemplateid
         FROM aicrm_questionnaire
         WHERE aicrm_questionnaire.questionnaireid = '" . $crm_id . "'";
      $query_template = $this->ci->db->query($sql_template);
      if (!empty($query_template)) {
        $a_template = $query_template->result_array();
        $templateid = $a_template[0]['questionnairetemplateid'];
      }
    }

    $a_condition["aicrm_crmentity.setype"] = "questionnairetemplate";
    // $a_condition["aicrm_crmentity.deleted"] = "0";

    $this->ci->db->select("aicrm_questionnairetemplate.*, aicrm_questionnairetemplatecf.*");
    $this->ci->db->select("aicrm_questionnairetemplate_page.* , aicrm_questionnairetemplate_choice.* , aicrm_questionnairetemplate_choicedetail.* ");
    $this->ci->db->join('aicrm_questionnairetemplatecf', 'aicrm_questionnairetemplatecf.questionnairetemplateid = aicrm_questionnairetemplate.questionnairetemplateid');
    $this->ci->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_questionnairetemplate.questionnairetemplateid');

    $this->ci->db->join('aicrm_questionnairetemplate_page', 'aicrm_questionnairetemplate_page.questionnairetemplateid = aicrm_questionnairetemplate.questionnairetemplateid');
    $this->ci->db->join('aicrm_questionnairetemplate_choice', 'aicrm_questionnairetemplate_choice.pageid = aicrm_questionnairetemplate_page.pageid');
    $this->ci->db->join('aicrm_questionnairetemplate_choicedetail', 'aicrm_questionnairetemplate_choicedetail.choiceid = aicrm_questionnairetemplate_choice.choiceid');

    $a_condition["aicrm_questionnairetemplate.questionnairetemplateid"] =  $templateid;

    $this->ci->db->where($a_condition);
    $this->ci->db->order_by("aicrm_questionnairetemplate_page.pageid", "asc");
    $this->ci->db->order_by("aicrm_questionnairetemplate_choice.choiceid", "asc");
    $this->ci->db->order_by("aicrm_questionnairetemplate_choicedetail.choicedetailid", "asc");

    $query = $this->ci->db->get('aicrm_questionnairetemplate');

    // echo $this->ci->db->last_query();
    // exit;

    $a_result  = $query->result_array();

    if (!empty($a_result)) {

      $a_data_temp = $a_result;
      $data_template = array();

      $data_template['templateid'] = $a_data_temp[0]['questionnairetemplateid'];
      $data_template['Questionnairetemplate'] = true;
      $data_template['header_name'] = $blockname;
      $data_template['title'] = $a_data_temp[0]['title_questionnaire'];
      $pageid = '';
      $i = -1;
      $c = 0;
      $k = 0;

      // alert($a_data_temp);exit;
      foreach ($a_data_temp as $key => $val) {

        if ($pageid != $val['pageid']) {
          $c = 0;
          $i++;
          $data_template["form"][$i]['pagename'] = $val['name_page'];
          $data_template["form"][$i]['title'] = $val['title_page'];
          $data_template["form"][$i]['columnname'] = $val['title_page'];
          $data_template["form"][$i]['tablename'] = "aicrm_questionnaire";
          $data_template["form"][$i]['fieldlabel'] = $val['title_page'];
          $data_template["form"][$i]['uitype'] = "00";
          $data_template["form"][$i]['typeofdata'] = null;
          $data_template["form"][$i]['type'] = "page";
          $data_template["form"][$i]['keyboard_type'] = null;
          $data_template["form"][$i]['value_default'] = null;
          $data_template["form"][$i]['value_name'] = null;
          $data_template["form"][$i]['value'] = null;
          $data_template["form"][$i]['check_value'] = null;
          $data_template["form"][$i]['error_message'] = null;
          $data_template["form"][$i]['readonly'] = null;
          $data_template["form"][$i]['maximumlength'] = null;
          $data_template["form"][$i]['format_date'] = null;
          $data_template["form"][$i]['is_array'] = null;
          $data_template["form"][$i]['is_phone'] = null;
          $data_template["form"][$i]['is_account'] = null;
          $data_template["form"][$i]['is_product'] = null;
          $data_template["form"][$i]['is_checkin'] = null;
          $data_template["form"][$i]['is_hidden'] = null;
          $data_template["form"][$i]['module_select'] = null;
          $data_template["form"][$i]['link'] = null;
          $data_template["form"][$i]['no'] = null;
          $data_template["form"][$i]['name'] = null;
          $data_template["form"][$i]['key_valuename_select'] = null;
          $data_template["form"][$i]['relate_field_up'] = [];
          $data_template["form"][$i]['relate_field_down'] = [];

          if ($val['choicedetail_other'] == 1) {
            $data_template["form"][$i]['otherText']  = $val['choicedetail_name']; //choicedetail_name
          }
          $data_template["form"][$i]['elements'][$c]['type'] = $val['choice_type'];
          $data_template["form"][$i]['elements'][$c]['name'] = $val['choice_name'];
          $data_template["form"][$i]['elements'][$c]['title'] = $val['choice_title'];
          $data_template["form"][$i]['elements'][$c]['hasOther'] = (isset($val['hasother']) && $val['hasother'] == 1) ? true : false; //0=false ,1=true
          $data_template["form"][$i]['elements'][$c]['isRequired'] = (isset($val['required']) && $val['required'] == 1) ? true : false; //0=false ,1=true
          $data_template["form"][$i]['elements'][$c]['choiceid'] = $val['choiceid'];
          //$data_template["pages"][$i]['elements'][$c]['choicedetailid'] =$val['choicedetailid'];
          if ($val['choicedetail_other'] == 1) {
            $data_template["form"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
            $data_template["form"][$i]['elements'][$c]['otherTextid'] = $val['choicedetailid'];
          } else if ($val['choice_type'] != 'text') {
            //$k=0;
            // $data_template["form"][$i]['elements'][$c]['choice'][$val['choicedetailid']] = $val['choicedetail_name'];
            $data_template["form"][$i]['elements'][$c]['choice'][] = array("key" => $val['choicedetailid'], "value" => $val['choicedetail_name']);
          } else if ($val['choice_type'] == 'text') {
            $data_template["form"][$i]['elements'][$c]['choicedetailid'] = $val['choicedetailid'];
            $data_template["form"][$i]['elements'][$c]['choice'] = null;
          }

          $pageid = $val['pageid'];
          $choiceid = $val['choiceid'];
          //$i++; 
        } else if ($pageid == $val['pageid']) {

          if ($choiceid != $val['choiceid']) {
            $c++;
            $data_template["form"][$i]['elements'][$c]['type'] = $val['choice_type'];
            $data_template["form"][$i]['elements'][$c]['name'] = $val['choice_name'];
            $data_template["form"][$i]['elements'][$c]['title'] = $val['choice_title'];
            $data_template["form"][$i]['elements'][$c]['hasOther'] = (isset($val['hasother']) && $val['hasother'] == 1) ? true : false; //0=false ,1=true
            $data_template["form"][$i]['elements'][$c]['isRequired'] = (isset($val['required']) && $val['required'] == 1) ? true : false; //0=false ,1=true
            $data_template["form"][$i]['elements'][$c]['choiceid'] = $val['choiceid'];
            //$data_template["pages"][$i]['elements'][$c]['choicedetailid'] =$val['choicedetailid'];

            if ($val['choicedetail_other'] == 1) {
              $data_template["form"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
              $data_template["form"][$i]['elements'][$c]['otherTextid'] = $val['choicedetailid'];
            } else if ($val['choice_type'] != 'text') {
              $k = 0;
              // $data_template["form"][$i]['elements'][$c]['choice'][$val['choicedetailid']] = $val['choicedetail_name'];
              // $data_template["form"][$i]['elements'][$c]['choice'][] =array($val['choicedetailid']=>$val['choicedetail_name']);
              $data_template["form"][$i]['elements'][$c]['choice'][] = array("key" => $val['choicedetailid'], "value" => $val['choicedetail_name']);
            } else if ($val['choice_type'] == 'text') {
              $data_template["form"][$i]['elements'][$c]['choicedetailid'] = $val['choicedetailid'];
              $data_template["form"][$i]['elements'][$c]['choice'] = null;
            }
          } else {
            if ($val['choicedetail_other'] == 1) {
              $data_template["form"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
              $data_template["form"][$i]['elements'][$c]['otherTextid'] = $val['choicedetailid'];
            } else if ($val['choice_type'] != 'text') {
              $k++;
              // $data_template["form"][$i]['elements'][$c]['choice'][$val['choicedetailid']] = $val['choicedetail_name'];
              // $data_template["form"][$i]['elements'][$c]['choice'][] =array($val['choicedetailid']=>$val['choicedetail_name']);
              $data_template["form"][$i]['elements'][$c]['choice'][] = array("key" => $val['choicedetailid'], "value" => $val['choicedetail_name']);
            } else if ($val['choice_type'] == 'text') {
              $data_template["form"][$i]['elements'][$c]['choicedetailid'] = $val['choicedetailid'];
              $data_template["form"][$i]['elements'][$c]['choice'] = null;
            }
          }

          $pageid = $val['pageid'];
          $choiceid = $val['choiceid'];
        }


        if ($action == "add" || $action == "duplicate") {
          $data_template["form"][$i]['elements'][$c]['values'] = null;
        }

        if (empty($data_template["form"][$i]['elements'][$c]['values'])) {
          $data_template["form"][$i]['elements'][$c]['values'] = null;
        }
      } //foreach
      // exit;
      // alert($data_template);exit;
      foreach ($data_template["form"] as $kf => $vf) {
        foreach ($vf['elements'] as $key => $value) {

          if (!empty($d_awnser)) {

            foreach ($d_awnser as $k => $v) {
              if ($value['name'] == $k) {
                if (!empty($v)) {
                  if ($value['type'] == "checkbox") {
                    $data_template["form"][$kf]['elements'][$key]['values'] = $v['values'];
                    if ($v['check_other'] == 1) {
                      $data_template["form"][$kf]['elements'][$key]['otherValue'] = $v['check_other'];
                    } else {
                      $data_template["form"][$kf]['elements'][$key]['otherValue'] = false;
                    }
                  } else {
                    $data_template["form"][$kf]['elements'][$key]['values'] = $v['values'];
                    $data_template["form"][$kf]['elements'][$key]['otherValue'] = $v['check_other'];
                  }
                } else {
                  $data_template["form"][$kf]['elements'][$key]['values'] = null;
                  $data_template["form"][$kf]['elements'][$key]['otherValue'] = $v['check_other'];
                }
              }
            }
          } else {
            $data_template["form"][$kf]['elements'][$key]['values'] = null;
            $data_template["form"][$kf]['elements'][$key]['otherValue'] = false;
          }
        }
      }
      //   alert($data_template);
      // exit;

    }
    // alert($data_template);exit;

    return $data_template;
  }

  public function Get_Relate($module, $crm_id)
  {
    $a_return = array();

    if ($module == "Sales Visit" || $module == "Events" || $module == "SalesVisit"  || $module == "Calendar") {
      $module = "Calendar";
    } elseif ($module == "Projectorders" || $module == "Project Orders" || $module == "Projectorder" || $module == "Project Order") {
      $module = "Projects";
    } elseif ($module == "Spare Part" || $module == "SparePart") {
      $module = "Sparepart";
    } elseif ($module == "Errors List" || $module == "ErrorsList") {
      $module = "Errorslist";
    } elseif ($module == "Spare Part List" || $module == "SparePartList" || $module == "Spare Part List") {
      $module = "Sparepartlist";
    } else if ($module == "Case") {
      $module = "HelpDesk";
    }

    $tabid = $this->Get_Tab_ID($module);

    if ($tabid == "") {
      $a_return["status"] = false;
      $a_return["error"] = "No Module";
      return $a_return;
    }

    $sql = " select *
    from aicrm_relatedlists
    where 1 and tabid='" . $tabid . "' and presence = 0 and presence_mobile = 0";
    $sql .= " order by sequence ";
    
    $query = $this->ci->db->query($sql);
    //Tag
    $a_tag[0]['relation_id'] = 0;
    $a_tag[0]['tabid'] = $tabid;
    $a_tag[0]['related_tabid'] = 0;
    $a_tag[0]['name'] = 'get_tag';
    $a_tag[0]['sequence'] = '1';
    $a_tag[0]['label'] = '1';
    $a_tag[0]['presence'] = '0';
    $a_tag[0]['action'] = 'add';
    $tag = $this->get_tag($module, $crm_id);
    $a_tag[0]['module'] = "Tag";
    $a_tag[0]['total'] = count($tag);
    $a_tag[0]['relateList'] = $tag;
    //Tag
    $a_form = $a_tag;

    if ($query->num_rows() > 0) {
      $data_block = $query->result_array(); //alert($data_block ); exit;
      foreach ($data_block as $key => $val) {

        $label = $val['name'];
        $module_related = $val['label'];
        // alert($label);
        // exit;

        if ($label == 'get_activities') {
          if ($module != "Inspection") {

            $activity = $this->get_activities($module, $crm_id);
            $data_block[$key]['module'] = "Sales Visit";
            //  $data_block[$key]['label'] = "Sales Visit";
            $data_block[$key]['total'] = count($activity);
            $data_block[$key]['relateList'] = $activity;
          }
        } elseif ($label == 'get_salesinvoice') {
          $relateList = $this->get_salesinvoice($module, $crm_id);
          $data_block[$key]['module'] = "SaleInvoice";
          $data_block[$key]['total'] = count($relateList);
          $data_block[$key]['relateList'] =   $relateList;
        } elseif ($label == 'get_attachments') {
          $document = $this->get_attachments($module, $crm_id);
          $data_block[$key]['module'] = "Documents";
          //  $data_block[$key]['lebal'] = "Documents";
          $data_block[$key]['total'] = count($document);
          $data_block[$key]['relateList'] =   $document;
        } elseif ($label == 'get_errorslist') {
          $errorslist = $this->get_errorslist($module, $crm_id);
          $data_block[$key]['module'] = "Errorslist";
          //  $data_block[$key]['label'] = "Errors List";
          $data_block[$key]['total'] = count($errorslist);
          $data_block[$key]['relateList'] =   $errorslist;
        } elseif ($label == 'get_sparepartlist') {
          $sparepaetlist = $this->get_sparepartlist($module, $crm_id);
          $data_block[$key]['module'] =  "Sparepartlist";
          //  $data_block[$key]['label'] =  "Spare Part List";
          $data_block[$key]['total'] = count($sparepaetlist);
          $data_block[$key]['relateList'] =   $sparepaetlist;
        } elseif ($label == 'get_related_list') {
          $serial = $this->get_serial($module, $crm_id);
          $data_block[$key]['module'] =  "Serial";
          //  $data_block[$key]['label'] =  "Serial";
          $data_block[$key]['total'] = count($serial);
          $data_block[$key]['relateList'] =   $serial;
        } elseif ($label == 'get_tickets') {
          $helpdesk = $this->get_tickets($module, $crm_id);
          $data_block[$key]['module'] =  "Case";
          //   $data_block[$key]['label'] =  "Case";
          $data_block[$key]['total'] = count($helpdesk);
          $data_block[$key]['relateList'] =   $helpdesk;
        } elseif ($label == 'get_leads') {
          $leads = $this->get_leads($module, $crm_id);
          $data_block[$key]['module'] =  "Leads";
          // $data_block[$key]['label'] =  "Leads";
          $data_block[$key]['total'] = count($leads);
          $data_block[$key]['relateList'] =   $leads;
        } elseif ($label == 'get_deals') {
          $deal = $this->get_deals($module, $crm_id);
          $data_block[$key]['module'] =  "Deal";
          // $data_block[$key]['label'] =  "Deal";
          $data_block[$key]['total'] = count($deal);
          $data_block[$key]['relateList'] =   $deal;
        } elseif ($label == 'get_voucher') {
          $voucher = $this->get_voucher($module, $crm_id);
          $data_block[$key]['module'] =  "Voucher";
          // $data_block[$key]['label'] =  "Voucher";
          $data_block[$key]['total'] = count($voucher);
          $data_block[$key]['relateList'] =   $voucher;
        } elseif ($label == 'get_contacts') {
          $contacts = $this->get_contacts($module, $crm_id);
          $data_block[$key]['module'] =  "Contacts";
          $data_block[$key]['total'] = count($contacts);
          $data_block[$key]['relateList'] =   $contacts;
        } elseif ($label == 'get_inspection') {

          $inspection = $this->get_inspection($module, $crm_id);

          $data_block[$key]['module'] =  $module_related;
          $data_block[$key]['total'] = count($inspection);
          $data_block[$key]['relateList'] =   $inspection;
        } elseif ($label == 'get_products'){
          $result = $this->get_products($module, $crm_id);

          $data_block[$key]['module'] =  $module_related;
          $data_block[$key]['total'] = count($result);
          $data_block[$key]['relateList'] =   $result;
        } elseif ($label == 'get_competitor'){
          $result = $this->get_competitor($module, $crm_id);

          $data_block[$key]['module'] =  $module_related;
          $data_block[$key]['total'] = count($result);
          $data_block[$key]['relateList'] =   $result;
        } elseif ($label == 'get_expense'){
          $result = $this->get_expense($module, $crm_id);

          $data_block[$key]['module'] =  $module_related;
          $data_block[$key]['total'] = count($result);
          $data_block[$key]['relateList'] =   $result;
        } elseif ($label == 'get_marketingtools') {
          $relateList = $this->get_marketingtools($module, $crm_id);
          $data_block[$key]['module'] = "Marketingtools";
          $data_block[$key]['total'] = count($relateList);
          $data_block[$key]['relateList'] =   $relateList;
        }

        if ($data_block[$key]['relateList'] == null) {
          $data_block[$key]['relateList'] = [];
        }
      }
      // alert($data_block);
      // exit;
      //array_push($a_form, $data_block);
      $a_form = array_merge($a_tag, $data_block);
    }
    $a_return["status"] = true;
    $a_return["error"] = "";
    $a_return["data"] = $a_form;
    return $a_return;
  }

  function get_inspection($module, $crm_id)
  {
    $sql = " select 
              aicrm_inspection.inspectionid as 'id'
              ,aicrm_inspection.inspection_name as 'name'
              ,CONCAT(aicrm_inspection.inspection_no ,': ', aicrm_serial.serial_no ,'|', aicrm_serial.serial_name ) as 'no' 
              ,aicrm_inspection.jobid as moduleid
              ,aicrm_inspection.inspection_status as 'status'
              
    from aicrm_inspection
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid
          inner join aicrm_serial on aicrm_serial.serialid = aicrm_inspection.serialid
          left join aicrm_users on aicrm_users.id = aicrm_crmentity.smownerid
          left join aicrm_groups on aicrm_groups.groupid = aicrm_crmentity.smownerid
    where jobid ='" . $crm_id . "' and aicrm_crmentity.deleted = 0 ";

    $query = $this->ci->db->query($sql);
    $inspectionList = $query->result_array();

    foreach ($inspectionList as $key => $val) {
      foreach ($val as $k => $v) {
        if ($v == null) {
          $v = "";
          $val[$k] = $v;
          $val_change = $val[$k];
        }
        $val[$k] = $v;
      }
      $inspectionList[$key] = $val;
    }
    if (!empty($inspectionList)) {
      $url_form = "http://$_SERVER[HTTP_HOST]/crm/report_customize/Inspection/index?crmid=";
      $url_report = "http://$_SERVER[HTTP_HOST]/crm/report_inspection.php?inspectionid=";

      foreach ($inspectionList as $key => $val) {

        $val['inpect_form'] = $url_form . $val['id'];
        $val['report_inspec'] = $url_report . $val['id'];
        $inspectionList[$key] = $val;
      }
    }
    return $inspectionList;
  }

  function get_salesinvoice($module, $id)
  {
    $query = '';
    switch ($module) {
      case 'Accounts':
        $query = "SELECT
            aicrm_salesinvoice.salesinvoiceid AS id,
            aicrm_salesinvoice.salesinvoice_no AS no,
            aicrm_salesinvoice.salesinvoice_name AS name,
            aicrm_account.accountid AS moduleid
          FROM
            aicrm_salesinvoice
            INNER JOIN aicrm_salesinvoicecf ON aicrm_salesinvoicecf.salesinvoiceid = aicrm_salesinvoice.salesinvoiceid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_salesinvoice.salesinvoiceid
            LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_salesinvoice.accountid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id 
          WHERE
          aicrm_crmentity.deleted = 0 
          AND aicrm_account.accountid = " . $id;
        break;
    }
    if ($query == '') return [];

    $sql = $this->ci->db->query($query);
    $result = $sql->result_array();

    return $result;
  }

  function get_products($module, $id){
    $query = "SELECT 


    aicrm_products.*, aicrm_products.productid as id, aicrm_products.productname as name, aicrm_products.product_no as no,
    aicrm_crmentity.crmid, aicrm_crmentity.smownerid
        FROM aicrm_products
        INNER JOIN aicrm_seproductsrel ON aicrm_products.productid = aicrm_seproductsrel.productid and aicrm_seproductsrel.setype='".$module."'
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
        INNER JOIN aicrm_deal ON aicrm_deal.dealid = aicrm_seproductsrel.crmid
        WHERE aicrm_crmentity.deleted = 0 AND aicrm_deal.dealid =".$id;

    $sql = $this->ci->db->query($query);
    $result = $sql->result_array();
    
    return $result;
  }

  function get_competitor($module, $id){
    $query = "SELECT
      aicrm_users.user_name,
      aicrm_crmentity.*,
      aicrm_competitor.* , aicrm_competitor.competitorid as id, aicrm_competitor.competitor_name as name, aicrm_competitor.competitor_no as no
    FROM
      aicrm_competitor
      LEFT JOIN aicrm_competitorcf ON aicrm_competitorcf.competitorid = aicrm_competitor.competitorid
      INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_competitor.competitorid
      LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
      LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
      LEFT JOIN aicrm_crmentityrel ON aicrm_competitor.competitorid = aicrm_crmentityrel.relcrmid 
      LEFT JOIN aicrm_deal ON aicrm_deal.competitorid = aicrm_competitor.competitorid 
      
      WHERE aicrm_crmentity.deleted = 0 AND aicrm_crmentityrel.crmid = ".$id;

    $sql = $this->ci->db->query($query);
    $result = $sql->result_array();
    
    return $result;
  }

  function get_expense($module, $id){
    $query = "SELECT
      aicrm_users.user_name,
      aicrm_crmentity.*,
      aicrm_expense.* , aicrm_expense.expenseid as id, aicrm_expense.expense_name as name, aicrm_expense.expense_no as no
      FROM
      aicrm_expense
      LEFT JOIN aicrm_expensecf ON aicrm_expensecf.expenseid = aicrm_expense.expenseid
      INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_expense.expenseid
      LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
      LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid";
    
    if($module == 'Accounts'){
      $query .= " WHERE aicrm_crmentity.deleted = 0 AND aicrm_expense.accountid = ".$id;
    }else if($module == 'Contacts'){
      $query .= " WHERE aicrm_crmentity.deleted = 0 AND aicrm_expense.contactid = ".$id;
    }else if($module == 'Contacts'){
      $query .= " WHERE aicrm_crmentity.deleted = 0 AND aicrm_expense.projectsid = ".$id;
    }else{
      $query .= " WHERE aicrm_crmentity.deleted = 0 AND aicrm_crmentity.crmid = ".$id;
    }
    $sql = $this->ci->db->query($query);
    $result = $sql->result_array();
    return $result;
  }

  function get_marketingtools($module, $id){
    $query = "SELECT
    aicrm_marketingtools.*,
    aicrm_marketingtoolscf.*,
    aicrm_crmentity.crmid,
    aicrm_crmentity.smownerid,
    aicrm_activity.salesvisit_name,
    aicrm_crmentity.description,
CASE
        
        WHEN ( aicrm_users.user_name NOT LIKE '' ) THEN
        aicrm_users.user_name ELSE aicrm_groups.groupname 
    END AS user_name 
FROM
    aicrm_marketingtools
    LEFT JOIN aicrm_marketingtoolscf ON aicrm_marketingtoolscf.marketingtoolsid = aicrm_marketingtools.marketingtoolsid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_marketingtools.marketingtoolsid
    LEFT JOIN aicrm_activity ON aicrm_activity.activityid = aicrm_marketingtools.activityid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id 
WHERE
    aicrm_crmentity.deleted = 0 
    AND aicrm_marketingtools.activityid ='".$id."'";
    
   
    $sql = $this->ci->db->query($query);
    $result = $sql->result_array();
    return $result;
  }

  

  function get_activities($module, $crm_id)
  {

    if($module == 'Accounts'){
      //$select = "select aicrm_activity.activityid as 'id' ,aicrm_activity.activitytype as 'name', aicrm_account.accountname as 'accountname' , aicrm_account.accountid as moduleid ";
      $select = "select aicrm_activity.activityid as 'id' ,aicrm_activity.activitytype as 'name', aicrm_account.accountname as 'accountname' , aicrm_account.accountid as moduleid ,aicrm_activity.eventstatus as status , aicrm_activity.date_start as startdate ,aicrm_activity.time_start as starttime ,aicrm_activity.time_end as endtime ";
      $join = 'inner join aicrm_account on aicrm_account.accountid = aicrm_activity.parentid';
      $where = "and aicrm_account.accountid ='".$crm_id."' ";
    }elseif($module == 'Leads'){
      $select = "select aicrm_activity.activityid as 'id' ,aicrm_activity.activitytype as 'name', aicrm_leaddetails.leadname AS 'accountname' , aicrm_leaddetails.leadid AS moduleid ,aicrm_activity.eventstatus as status , aicrm_activity.date_start as startdate ,aicrm_activity.time_start as starttime ,aicrm_activity.time_end as endtime ";
      $join = 'INNER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_activity.parentid';
      $where = "and aicrm_activity.parentid ='".$crm_id."' ";
    } elseif ($module == 'Deal') {
      $select = "select aicrm_activity.activityid as 'id' ,aicrm_activity.activitytype as 'name', aicrm_deal.deal_name as 'deal_name' , aicrm_deal.dealid as moduleid ,aicrm_activity.eventstatus as status , aicrm_activity.date_start as startdate ,aicrm_activity.time_start as starttime ,aicrm_activity.time_end as endtime ";
      $join = 'inner join aicrm_deal on aicrm_deal.dealid = aicrm_activity.dealid';
      $where = "and aicrm_deal.dealid ='" . $crm_id . "' ";
    } elseif ($module == 'Job') {
      $select = "select aicrm_activity.activityid as 'id' ,aicrm_activity.activitytype as 'name', aicrm_account.accountname as 'accountname' , aicrm_account.accountid as moduleid ,aicrm_activity.eventstatus as status , aicrm_activity.date_start as startdate ,aicrm_activity.time_start as starttime ,aicrm_activity.time_end as endtime ";
      $join = 'inner join aicrm_account on aicrm_account.accountid = aicrm_activity.parentid';
      $where = "and aicrm_account.accountid ='" . $crm_id . "' ";

    } elseif ($module == 'Projects') {
      $select = "select aicrm_activity.activityid as 'id' ,aicrm_activity.activitytype as 'name', aicrm_projects.projects_name as 'projects_name' , aicrm_projects.projectsid as moduleid ,aicrm_activity.eventstatus as status , aicrm_activity.date_start as startdate ,aicrm_activity.time_start as starttime ,aicrm_activity.time_end as endtime ";
      $join = 'inner join aicrm_projects on aicrm_projects.projectsid = aicrm_activity.event_id';
      $where = "and aicrm_projects.projectsid ='" . $crm_id . "' ";
    
    } else {
      $select = "select aicrm_activity.activityid as 'id' ,aicrm_activity.activitytype as 'name', aicrm_account.accountname as 'no' , aicrm_jobs.jobid as moduleid ";
      $join = 'inner join aicrm_jobs on aicrm_jobs.jobid = aicrm_activity.event_id';
      $where = " and aicrm_jobs.jobid ='" . $crm_id . "' ";
    }
    $sql = $select . "  
    from aicrm_activity
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_activity.activityid
    inner join aicrm_activitycf on aicrm_activitycf.activityid = aicrm_activity.activityid
    " . $join . " 
    where aicrm_crmentity.deleted = 0 " . $where;
    //left join aicrm_account on aicrm_account.accountid = aicrm_activitycf.accountid
    // echo $sql; exit;
    $query = $this->ci->db->query($sql);
    $activity = $query->result_array();

    foreach ($activity as $key => $val) {
      foreach ($val as $k => $v) {
        if ($v == null) {
          $v = "";
          $val[$k] = $v;
          $val_change = $val[$k];
        }
        $val[$k] = $v;
      }
      $activity[$key] = $val;
    }
    return $activity;
  }



  function get_attachments($module, $crm_id)
  {

    if ($module == 'Accounts') {
      $select = "SELECT  aicrm_notes.notesid as 'id' ,aicrm_notes.title as 'name',aicrm_notes.note_no as 'no',aicrm_account.accountid as 'moduleid' , aicrm_attachments.name as 'filename' ,DATE_FORMAT(aicrm_crmentity.createdtime, '%d/%m/%Y') as datecreate , DATE_FORMAT(aicrm_crmentity.createdtime, '%H:%i:%s') as timecreate, aicrm_attachments.name as 'filename' , aicrm_attachments.path as 'path' ,aicrm_attachments.attachmentsid as 'attachmentsid' ";
      $table = 'aicrm_account';
      $join = 'aicrm_account.accountid';
      $where = "and aicrm_account.accountid ='" . $crm_id . "' ";
    } elseif ($module == 'Contacts') {
      $select = "SELECT  aicrm_notes.notesid as 'id' ,aicrm_notes.title as 'name',aicrm_notes.note_no as 'no',aicrm_contactdetails.contactid as 'moduleid', aicrm_attachments.name as 'filename' ";
      $table = 'aicrm_contactdetails';
      $join = 'aicrm_contactdetails.contactid ';
      $where = " and contactid ='" . $crm_id . "' ";
    } elseif ($module == 'Leads') {
      $select = "SELECT  aicrm_notes.notesid as 'id' ,aicrm_notes.title as 'name',aicrm_notes.note_no as 'no',aicrm_leaddetails.leadid as 'moduleid' , aicrm_attachments.name as 'filename' ,DATE_FORMAT(aicrm_crmentity.createdtime, '%d/%m/%Y') as datecreate , DATE_FORMAT(aicrm_crmentity.createdtime, '%H:%i:%s') as timecreate , aicrm_attachments.name as 'filename' , aicrm_attachments.path as 'path' ,aicrm_attachments.attachmentsid as 'attachmentsid'";
      $table = 'aicrm_leaddetails';
      $join = 'aicrm_leaddetails.leadid';
      $where = "and aicrm_leaddetails.leadid ='" . $crm_id . "' ";
    } elseif ($module == 'Deal') {
      $select = "SELECT  aicrm_notes.notesid as 'id' ,aicrm_notes.title as 'name',aicrm_notes.note_no as 'no',aicrm_deal.dealid as 'moduleid' , aicrm_attachments.name as 'filename' ";
      $table = 'aicrm_deal';
      $join = 'aicrm_deal.dealid';
      $where = "and aicrm_deal.dealid ='" . $crm_id . "' ";
    } elseif ($module == 'SalesVisit' || $module == 'Calendar' || $module == 'Sales Visit' || $module == 'Events') {
      $select = "SELECT  aicrm_notes.notesid as 'id' ,aicrm_notes.title as 'name',aicrm_notes.note_no as 'no',aicrm_activity.activityid as 'moduleid' , aicrm_attachments.name as 'filename' ";
      $table = 'aicrm_activity';
      $join = 'aicrm_activity.activityid';
      $where = "and aicrm_activity.activityid ='" . $crm_id . "' ";
    } elseif ($module == 'Questionnaire') {
      $select = "SELECT  aicrm_notes.notesid as 'id' ,aicrm_notes.title as 'name',aicrm_notes.note_no as 'no',aicrm_questionnaire.questionnaireid as 'moduleid' , aicrm_attachments.name as 'filename' ";
      $table = 'aicrm_questionnaire';
      $join = 'aicrm_questionnaire.questionnaireid';
      $where = "and aicrm_questionnaire.questionnaireid ='" . $crm_id . "' ";
    } elseif ($module == 'HelpDesk') {
      $select = "SELECT  aicrm_notes.notesid as 'id' ,aicrm_notes.title as 'name',aicrm_notes.note_no as 'no',aicrm_troubletickets.ticketid as 'moduleid' , aicrm_attachments.name as 'filename' ";
      $table = 'aicrm_troubletickets';
      $join = 'aicrm_troubletickets.ticketid';
      $where = "and aicrm_troubletickets.ticketid ='" . $crm_id . "' ";
    } elseif ($module == 'Projects') {
      $select = "SELECT  aicrm_notes.notesid as 'id' ,aicrm_notes.title as 'name',aicrm_notes.note_no as 'no',aicrm_projects.projectsid as 'moduleid', aicrm_attachments.name as 'filename' ";
      $table = 'aicrm_projects';
      $join = 'aicrm_projects.projectsid';
      $where = "and aicrm_projects.projectsid ='" . $crm_id . "' ";
    } elseif ($module == 'Competitor') {
      $select = "SELECT  aicrm_notes.notesid as 'id' ,aicrm_notes.title as 'name',aicrm_notes.note_no as 'no',aicrm_competitor.competitorid as 'moduleid', aicrm_attachments.name as 'filename' ";
      $table = 'aicrm_competitor';
      $join = 'aicrm_competitor.competitorid';
      $where = "and aicrm_competitor.competitorid ='" . $crm_id . "' ";
    } elseif ($module == 'Salesinvoice') {
      $select = "SELECT  aicrm_notes.notesid as 'id' ,aicrm_notes.title as 'name',aicrm_notes.note_no as 'no',aicrm_salesinvoice.salesinvoiceid as 'moduleid', aicrm_attachments.name as 'filename' ";
      $table = 'aicrm_salesinvoice';
      $join = 'aicrm_salesinvoice.salesinvoiceid';
      $where = "and aicrm_salesinvoice.salesinvoiceid ='" . $crm_id . "' ";
    } else {
      $select = "SELECT  aicrm_notes.notesid as 'id' ,aicrm_notes.title as 'name',aicrm_notes.note_no as 'no',aicrm_jobs.jobid as 'moduleid', aicrm_attachments.name as 'filename' ";
      $table = 'aicrm_jobs';
      $join = 'aicrm_jobs.jobid ';
      $where = " and jobid ='" . $crm_id . "' ";
    }


    $sql = $select . " 
    FROM " . $table . " 
    INNER JOIN aicrm_senotesrel ON " . $join . " =aicrm_senotesrel.crmid
    INNER JOIN aicrm_notes ON aicrm_notes.notesid = aicrm_senotesrel.notesid
    INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_notes.notesid
    LEFT JOIN aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid = aicrm_crmentity.crmid
    LEFT JOIN aicrm_attachments on aicrm_attachments.attachmentsid = aicrm_seattachmentsrel.attachmentsid
    where aicrm_crmentity.deleted = 0 " . $where;
    // echo $sql; exit;
    // $sql .= " order by sequence ";
    $query = $this->ci->db->query($sql);
    $documentList = $query->result_array();
    foreach ($documentList as $key => $val) {

      foreach ($val as $k => $v) {
        if ($v == null) {
          $v = "";
          $val[$k] = $v;
          $val_change = $val[$k];
        }
        $val[$k] = $v;
      }
      $documentList[$key] = $val;
      // $documentList[$key]['no'] = "";
    }

    return $documentList;
  }

  function get_tag($module, $crm_id)
  {

    $sql = " select aicrm_freetags.id as 'id' ,aicrm_freetags.tag as 'name' , '#a0a0a0' as 'color'
    from aicrm_freetags
    inner join aicrm_freetagged_objects on aicrm_freetagged_objects.tag_id = aicrm_freetags.id
    where aicrm_freetagged_objects.object_id ='" . $crm_id . "' ";

    $query = $this->ci->db->query($sql);
    $errList = $query->result_array();

    foreach ($errList as $key => $val) {
      foreach ($val as $k => $v) {
        if ($v == null) {
          $v = "";
          $val[$k] = $v;
          $val_change = $val[$k];
        }
        $val[$k] = $v;
      }
      $errList[$key] = $val;
    }
    return $errList;
  }

  function get_voucher($module, $crm_id)
  {

    $sql = " select aicrm_voucher.voucherid as 'id' ,aicrm_voucher.voucher_name as 'name'
    , aicrm_voucher.voucher_no as 'no' , aicrm_voucher.vouchermessage as 'detail' , aicrm_voucher.startdate as startdate, aicrm_voucher.enddate as enddate,
    aicrm_voucher.value ,
    DATE_FORMAT(aicrm_crmentity.createdtime, '%d/%m/%Y') as datecreate , DATE_FORMAT(aicrm_crmentity.createdtime, '%H:%i:%s') as timecreate ,aicrm_voucher.voucher_status as status
    from aicrm_voucher
    inner join aicrm_vouchercf on aicrm_vouchercf.voucherid = aicrm_voucher.voucherid
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_voucher.voucherid
    where (aicrm_voucher.accountid ='" . $crm_id . "' || aicrm_voucher.leadid ='" . $crm_id . "') and aicrm_crmentity.deleted = 0";
    //echo $sql; exit;
    $query = $this->ci->db->query($sql);
    $errList = $query->result_array();

    foreach ($errList as $key => $val) {
      foreach ($val as $k => $v) {
        if ($v == null) {
          $v = "";
          $val[$k] = $v;
          $val_change = $val[$k];
        }
        $val[$k] = $v;
      }
      $errList[$key] = $val;
    }
    return $errList;
  }

  function get_contacts($module, $crm_id)
  {

    $sql = " select aicrm_contactdetails.contactid as 'id' ,concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as 'name'
    , aicrm_contactdetails.contact_no as 'no' , aicrm_contactdetails.mobile as 'mobile' , aicrm_contactdetails.email as email,
    DATE_FORMAT(aicrm_crmentity.createdtime, '%d/%m/%Y') as datecreate , DATE_FORMAT(aicrm_crmentity.createdtime, '%H:%i:%s') as timecreate
    from aicrm_contactdetails
    inner join aicrm_contactscf on aicrm_contactscf.contactid = aicrm_contactdetails.contactid
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
    where aicrm_contactdetails.accountid ='" . $crm_id . "' and aicrm_crmentity.deleted = 0";
    //echo $sql; exit;
    $query = $this->ci->db->query($sql);
    $errList = $query->result_array();

    foreach ($errList as $key => $val) {
      foreach ($val as $k => $v) {
        if ($v == null) {
          $v = "";
          $val[$k] = $v;
          $val_change = $val[$k];
        }
        $val[$k] = $v;
      }
      $errList[$key] = $val;
    }
    return $errList;
  }

  function get_deals($module, $crm_id)
  {

    $sql = " select aicrm_deal.dealid as 'id' ,aicrm_deal.deal_name as 'name'
    , aicrm_deal.deal_no as 'no' , aicrm_deal.stage as 'stage' , 
    DATE_FORMAT(aicrm_crmentity.createdtime, '%d/%m/%Y') as datecreate , DATE_FORMAT(aicrm_crmentity.createdtime, '%H:%i:%s') as timecreate
    from aicrm_deal
    inner join aicrm_dealcf on aicrm_dealcf.dealid = aicrm_deal.dealid
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_deal.dealid
    where aicrm_deal.parentid ='" . $crm_id . "' and aicrm_crmentity.deleted = 0";

    // aicrm_deal.ordertype as ordertype,DATE_FORMAT(aicrm_deal.closedate, '%d/%m/%Y') as closedate
    // alert($sql);exit;
    $query = $this->ci->db->query($sql);
    $errList = $query->result_array();

    foreach ($errList as $key => $val) {
      foreach ($val as $k => $v) {
        if ($v == null) {
          $v = "";
          $val[$k] = $v;
          $val_change = $val[$k];
        }
        $val[$k] = $v;
      }
      $errList[$key] = $val;
    }
    return $errList;
  }

  function get_leads($module, $crm_id)
  {

    $sql = " select aicrm_leaddetails.leadid as 'id' ,concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) as 'name'
    , aicrm_leaddetails.lead_no as 'no' , aicrm_leaddetails.mobile as 'mobile' , aicrm_leaddetails.email as email,
    DATE_FORMAT(aicrm_crmentity.createdtime, '%d/%m/%Y') as datecreate , DATE_FORMAT(aicrm_crmentity.createdtime, '%H:%i:%s') as timecreate
    from aicrm_leaddetails
    inner join aicrm_leadscf on aicrm_leadscf.leadid = aicrm_leaddetails.leadid
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
    where aicrm_leaddetails.accountid ='" . $crm_id . "' and aicrm_crmentity.deleted = 0";
    $query = $this->ci->db->query($sql);
    $errList = $query->result_array();

    foreach ($errList as $key => $val) {
      foreach ($val as $k => $v) {
        if ($v == null) {
          $v = "";
          $val[$k] = $v;
          $val_change = $val[$k];
        }
        $val[$k] = $v;
      }
      $errList[$key] = $val;
    }
    return $errList;
  }

  function get_tickets($module,$crm_id){

    $sql=" select aicrm_troubletickets.ticketid as 'id' ,aicrm_troubletickets.title as 'name'
    , aicrm_troubletickets.ticket_no as 'no' , aicrm_troubletickets.case_status as 'status' , aicrm_troubletickets.parentid as moduleid,
    DATE_FORMAT(aicrm_crmentity.createdtime, '%d/%m/%Y') as datecreate , DATE_FORMAT(aicrm_crmentity.createdtime, '%H:%i:%s') as timecreate
    from aicrm_troubletickets
    inner join aicrm_ticketcf on aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_troubletickets.ticketid
    where aicrm_troubletickets.parentid ='".$crm_id."' and aicrm_crmentity.deleted = 0";
    $query = $this->ci->db->query($sql);
    $CaseList = $query->result_array();

    foreach($CaseList as $key => $val){
      foreach($val as $k => $v){
        if($v==null){
          $v="";
          $val[$k] = $v;
          $val_change = $val[$k];
        }
        $val[$k] = $v;
      }
      $CaseList[$key] = $val;
    }
    return $CaseList;

  }

  function get_errorslist($module, $crm_id)
  {


    $sql = " select aicrm_errorslist.errorslistid as 'id' ,aicrm_errorslist.errorslist_name as 'name'
    , aicrm_errorslist.errorslist_no as 'no' , aicrm_errorslist.jobid as moduleid
    from aicrm_errorslist
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_errorslist.errorslistid
    where jobid ='" . $crm_id . "' and aicrm_crmentity.deleted = 0";
    $query = $this->ci->db->query($sql);
    $errList = $query->result_array();

    foreach ($errList as $key => $val) {
      foreach ($val as $k => $v) {
        if ($v == null) {
          $v = "";
          $val[$k] = $v;
          $val_change = $val[$k];
        }
        $val[$k] = $v;
      }
      $errList[$key] = $val;
    }
    return $errList;
  }


  function get_sparepartlist($module, $crm_id)
  {

    $sql = " select sparepartlistid as 'id' , sparepartlist_name as 'name',sparepartlist_no as 'no' , jobid as moduleid
    from aicrm_sparepartlist
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_sparepartlist.sparepartlistid
    where jobid ='" . $crm_id . "' and aicrm_crmentity.deleted = 0 ";

    $query = $this->ci->db->query($sql);
    $sprList = $query->result_array();
    foreach ($sprList as $key => $val) {
      foreach ($val as $k => $v) {
        if ($v == null) {
          $v = "";
          $val[$k] = $v;
          $val_change = $val[$k];
        }
        $val[$k] = $v;
      }
      $sprList[$key] = $val;
    }
    return $sprList;
  }



  public function check_leadstatus($action, $crmid, $userid)
  {
    if ($crmid == "") {

      $sql_leadstatus = "select * from tbt_step_convert where sequen='1' ";
      $query_leadstatus = $this->ci->db->query($sql_leadstatus);
      $response_leadstatus = $query_leadstatus->result_array();
      $response_leadstatus = $response_leadstatus[0];
      $lead_button['status_button']  = array($response_leadstatus['field_source_value']);
    } else {

      $sql_laststatus = "select * from tbt_step_convert where laststep='yes' ";
      $query_laststatus = $this->ci->db->query($sql_laststatus);
      $response_laststatus = $query_laststatus->result_array();
      $laststatus = $response_laststatus[0]['field_source_value'];

      $sql_request = "select * from tbt_approveconvert_log where crmid='" . $crmid . "' ";
      $query_request = $this->ci->db->query($sql_request);
      $response_request = $query_request->result_array();
      $response_request = $response_request[0];


      $sql_leadstatus = "SELECT tbt_step_convert.field_source_value ,tbt_step_convert.destination_value,tbt_step_convert.laststep,
      aicrm_leaddetails.flag_approve,aicrm_crmentity.smownerid,aicrm_leaddetails.converted from aicrm_leaddetails
      INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
      LEFT JOIN tbt_step_convert on tbt_step_convert.field_source_value = aicrm_leaddetails.lead_status
      WHERE  leadid ='" . $crmid . "' ";
      $query_leadstatus = $this->ci->db->query($sql_leadstatus);
      $response_leadstatus = $query_leadstatus->result_array();
      $lead_status = $response_leadstatus[0]['field_source_value'];
      $destination_status = $response_leadstatus[0]['destination_value'];
      $laststep = $response_leadstatus[0]['laststep'];
      $flag_approve = $response_leadstatus[0]['flag_approve'];
      $smownerid = $response_leadstatus[0]['smownerid'];
      $converted = $response_leadstatus[0]['converted'];

      $sql_laststatus = "select * from tbt_step_convert where laststep='yes' ";
      $query_laststatus = $this->ci->db->query($sql_laststatus);
      $response_laststatus = $query_laststatus->result_array();
      $last_status = $response_laststatus[0]['field_source_value'];

      $assign_group = "0";
      if ($smownerid != $userid) {
        // $smownerid = "16471";
        $user_group = $this->ci->lib_set_notification->get_usergroup($smownerid);
        $group = explode(',', $user_group);
        if (!empty($group)) {
          foreach ($group as $key => $value) {
            if ($userid == $value) {
              $assign_group = "1";
            }
          }
        }
      } else {
        $assign_group = "1";
      }

      if (!empty($destination_status)) {
        $lead_button['status_button']  = array($destination_status);
      }

      $user_approve =  $this->get_leadsapprove($userid);

      if ($user_approve == "1") {
        if ($assign_group == "1") {

          if ($destination_status == $last_status) {
            if ($flag_approve == 0 && $response_request == "") {
              $lead_button['status_button']  = array("Request");
              $lead_button['request_approve']  = "true";
            }
          }

          if ($laststep == "yes" && $flag_approve == "0" && $response_request == "") {
            $lead_button['status_button']  = array("Request");
            $lead_button['request_approve']  = "true";
          } elseif ($laststep == "yes" && $flag_approve == "0" && $response_request != "") {
            $lead_button['status_button']  = array("Approve", "Reject");
            $lead_button['approved']  = "true";
          }

          if ($flag_approve == "1") {
            $lead_button['status_button']  = array($laststatus);
            $lead_button['convert']  = "true";
          }
        } else {

          if ($response_request != "" && $flag_approve == "0") {
            $lead_button['status_button']  = array("Approve", "Reject");
            $lead_button['approved']  = "true";
          } else {
            $lead_button['status_button']  = "";
            $lead_button['request_approve']  = "false";
            $lead_button['approved']  = "false";
            $lead_button['convert']  = "false";
          }
        }
      } else {

        if ($assign_group == "1") {

          if ($destination_status == $last_status) {
            if ($flag_approve == 0 && $response_request == "") {
              $lead_button['status_button']  = array("Request");
              $lead_button['request_approve']  = "true";
            }
          }

          if ($laststep == "yes" && $flag_approve == "0" && $response_request == "") {
            $lead_button['status_button']  = array("Request");
            $lead_button['request_approve']  = "true";
          } elseif ($laststep == "yes" && $flag_approve == "0" && $response_request != "") {
            $lead_button['status_button']  = "";
            $lead_button['request_approve']  = "false";
            $lead_button['approved']  = "false";
          }
          if ($flag_approve == "1") {
            $lead_button['status_button']  = array($laststatus);
            $lead_button['convert']  = "true";
          }
        } else {
          $lead_button['status_button']  = "";
          $lead_button['request_approve']  = "false";
          $lead_button['approved']  = "false";
          $lead_button['convert']  = "false";
        }
      }

      if ($converted == 1 || $converted == "1") {
        $lead_button['status_button'] = "";
        $lead_button['request_approve']  = "false";
        $lead_button['approved']  = "false";
        $lead_button['convert']  = "false";
      }
    }

    return $lead_button;
  }


  public function get_leadsapprove($userid = "")
  {

    $sql_approve_lead = "select entity,entity_value from aicrm_approve_lead  ";
    $query = $this->ci->db->query($sql_approve_lead);
    $data_approve =  $query->result_array();
    $entity = $data_approve[0]["entity"];
    $entity_value = $data_approve[0]["entity_value"];

    $data = "0";

    if ($entity == "Role") {
      $sql_user = "SELECT aicrm_user2role.userid from aicrm_users
      LEFT JOIN  aicrm_user2role on  aicrm_user2role.userid = aicrm_users.id
      WHERE roleid ='" . $entity_value . "'";
      $query_user = $this->ci->db->query($sql_user);
      $data_user =  $query_user->result_array();

      foreach ($data_user as $key => $value) {

        if ($userid == $value['userid']) {
          $data = "1";
        }
      }
    } else {

      if ($userid == $entity_value) {
        $data = "1";
      }
    }
    return $data;
  }


  public function check_leadfield($crm_id, $data_field)
  {

    if ($crm_id != "") {

      $sql_laststatus = "select * from tbt_step_convert where laststep='yes' ";
      $query_laststatus = $this->ci->db->query($sql_laststatus);
      $response_laststatus = $query_laststatus->result_array();
      $laststatus = $response_laststatus[0]['field_source_value'];

      $sql_leadstatus = "SELECT * from aicrm_leaddetails
      LEFT JOIN tbt_step_convert on tbt_step_convert.field_source_value = aicrm_leaddetails.lead_status
      WHERE  aicrm_leaddetails.leadid ='" . $crm_id . "' ";

      $query_leadstatus = $this->ci->db->query($sql_leadstatus);
      $response_leadstatus = $query_leadstatus->result_array();
      $field_source_value = $response_leadstatus[0]['field_source_value'];
      $destination_value = $response_leadstatus[0]['destination_value'];
      if ($destination_value != "") {
        if ($destination_value == $laststatus) {
          $sql_leadfield = "SELECT * from tbt_step_convert
          LEFT JOIN tbt_step_convert_validate on tbt_step_convert_validate.stepid = tbt_step_convert.sequen
          WHERE tbt_step_convert.field_source_value ='" . $laststatus . "' ";
        } else {
          $sql_leadfield = "SELECT * from tbt_step_convert
          LEFT JOIN tbt_step_convert_validate on tbt_step_convert_validate.stepid = tbt_step_convert.sequen
          WHERE tbt_step_convert.field_source_value ='" . $destination_value . "' ";
        }

        $query_leadfield = $this->ci->db->query($sql_leadfield);
        $response_leadfield = $query_leadfield->result_array();
        $lead_fieldname = $response_leadfield[0]['fieldname'];
        $lead_fieldname = explode(',', $lead_fieldname);
      }
    } else {
      $sql_leadfield = "SELECT * from tbt_step_convert_validate WHERE  tbt_step_convert_validate.stepid ='1' ";
      $query_leadfield = $this->ci->db->query($sql_leadfield);
      $response_leadfield = $query_leadfield->result_array();
      $lead_fieldname = $response_leadfield[0]['fieldname'];
      $lead_fieldname = explode(',', $lead_fieldname);
    }


    if ($lead_fieldname != "" && $data_field != "") {
      foreach ($data_field as $key => $value) {

        foreach ($lead_fieldname as $k => $v) {

          if ($value['fieldname'] == $v) {
            $value['typeofdata'] = "V~M";
          }
        }

        $data_field[$key] = $value;
      }
    }

    return $data_field;
  }

  function get_serial($module, $crm_id)
  {

    $sql = " select aicrm_serial.serialid as 'id' , aicrm_serial.serial_name as 'name',aicrm_serial.serial_no as 'no' , aicrm_jobs.jobid as moduleid
    from aicrm_serial
    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_serial.serialid
    inner join aicrm_jobs on aicrm_jobs.serialid = aicrm_serial.serialid
    where aicrm_jobs.jobid ='" . $crm_id . "' and aicrm_crmentity.deleted = 0 ";

    // where jobid =119827 ";
    $query = $this->ci->db->query($sql);
    $sprList = $query->result_array();
    foreach ($sprList as $key => $val) {
      foreach ($val as $k => $v) {
        if ($v == null) {
          $v = "";
          $val[$k] = $v;
          $val_change = $val[$k];
        }
        $val[$k] = $v;
      }
      $sprList[$key] = $val;
    }
    return $sprList;
  }


  public function usercomment($crmid = "")
  {

    $a_data = array();

    $sql_comment = "SELECT  aicrm_users.user_name , aicrm_commentplan.comments, aicrm_commentplan.createdtime
    from aicrm_crmentity
    LEFT JOIN aicrm_activity on aicrm_activity.activityid = aicrm_crmentity.crmid
    LEFT JOIN aicrm_commentplan on aicrm_commentplan.activityid  = aicrm_activity.activityid
    LEFT JOIN aicrm_users on aicrm_users.id = aicrm_commentplan.ownerid
    WHERE aicrm_crmentity.crmid='" . $crmid . "'
    ORDER BY aicrm_commentplan.commentplanid DESC  ";

    $query = $this->ci->db->query($sql_comment);
    $commentList = $query->result_array();

    if (!empty($commentList)) {
      foreach ($commentList as $key => $value) {
        if (!empty($value['comments'])) {
          $a_data[$key]['columnname'] = 'comment';
          $a_data[$key]['uitype'] = '9900'; // uitype commentplane
          $a_data[$key]['readonly'] = '1';
          $a_data[$key]['type'] = 'text';
          $a_data[$key]['maximumlength'] = '500';
          $a_data[$key]['keyboard_type'] = 'default';
          $a_data[$key]['comments'] = $value['comments'];
          $a_data[$key]['comments_by'] = "Author: " . $value['user_name'] . " on " . $value['createdtime'];
        }
      }
    }

    $insert_comment = array(
      'columnname' => 'insert_comment',
      'uitype' => '9901', // uitype ช่อง commentplane
      'type' => 'textarea',
      'readonly' => '0',
      'maximumlength' => '500',
      'keyboard_type' => 'default',
      'value' => '',
      'value_name' => ''
    );
    $insert_comment = array('0' => $insert_comment);

    array_splice($a_data, 0, 0, $insert_comment);

    return $a_data;
  }


  public function convert_account($field_value, $userid, $smownerid)
  {
    if ($userid == "") {
      return;
    }

    $select_account = "
    SELECT aicrm_convertleadmapping.accountfid as fieldid ,aicrm_field.columnname , new_lead.leadfid ,new_lead.lead_columnname
    from aicrm_convertleadmapping
    LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_convertleadmapping.accountfid
    LEFT JOIN (
      SELECT aicrm_convertleadmapping.leadfid,aicrm_field.columnname as lead_columnname,aicrm_convertleadmapping.accountfid as fieldid
      from aicrm_convertleadmapping
      LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_convertleadmapping.leadfid
      ) as new_lead on new_lead.fieldid = aicrm_convertleadmapping.accountfid
      WHERE aicrm_convertleadmapping.accountfid!='0'
      ";
    $query_account = $this->ci->db->query($select_account);
    $column_account = $query_account->result(0);
    $colum_acc = array();
    $value_acc = array();

    if (!empty($column_account)) {

      foreach ($column_account as $key => $value) {
        $columnname = $value['lead_columnname'];
        if ($field_value[0][$columnname] != "") {
          $column_account[$key]['value'] = $field_value[0][$columnname];
        } else {
          $column_account[$key]['value'] = "";
        }
      }

      foreach ($column_account as $key => $value) {
        $colum_acc[] = $value['columnname'];
        $value_acc[] = $value['value'];
      }

      if ($smownerid == "") {
        $smownerid = $userid;
      }



      if (count($colum_acc) == count($value_acc)) {

        $crmid = $this->ci->crmentity->Get_Crmid();

        $sql = "insert into aicrm_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime,modifiedby) values
          ('" . $crmid . "','" . $userid . "','" . $smownerid . "','Accounts','',NOW(),NOW(),'" . $userid . "')";
        $this->ci->db->query($sql);

        $colum_acc = implode(", ", $colum_acc);

        $sql_insert_acc = "
          INSERT INTO aicrm_account (accountid," . $colum_acc . ")
          VALUES ('" . $crmid . "', ";

        foreach ($value_acc as $k => $v) {
          $sql_insert_acc .= "'" . $v . "',";
        }

        $sql_insert_acc = mb_substr($sql_insert_acc, 0, -1);
        $sql_insert_acc .= ")";
        $success = $this->ci->db->query($sql_insert_acc);

        $insert_cf = "INSERT INTO aicrm_accountscf (accountid) VALUES ('" . $crmid . "') ";
        $this->ci->db->query($insert_cf);

        $insert_billads = "INSERT INTO aicrm_accountbillads (accountaddressid) VALUES ('" . $crmid . "') ";
        $this->ci->db->query($insert_billads);

        $insert_shipads = "INSERT INTO aicrm_accountshipads (accountaddressid) VALUES ('" . $crmid . "') ";
        $this->ci->db->query($insert_shipads);

        $acc_no = $this->ci->crmentity->autocd_acc();
        $sql = "UPDATE aicrm_account set account_no='" . $acc_no . "' where accountid='" . $crmid . "'";
        if ($this->ci->db->query($sql)) {
          $insert_status = array(
            'status' => 'Success',
            'accountid' => $crmid,
          );
          $a_return["Type"] =  "S";
          $a_return["Message"] =  "Convert Accounts Complete";
          $a_return["data"] = array();
          $data = array(
            'crmid' => $crmid,
            'modifiedtime' => date("Y-m-d H:i:s")
          );

          array_push($a_return["data"], $data);
        } else {
          $insert_status = array(
            'status' => 'Fail',
            'accountid' => $crmid,
          );
          $a_return["Type"] =  "E";
          $a_return["Message"] =  "Convert Fail";
          $a_return["data"] = array();
          $data = array();

          array_push($a_return["data"], $data);
        }

        $response_data = $a_return;

        $log_filename = "Insert_Accounts";
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->ci->common->_filename = $log_filename;
        $this->ci->common->set_log($url, $field_value, $response_data);
      }
    }

    return $insert_status;
  }


  public function convert_contact($field_value, $userid, $accountid, $smownerid)
  {
    if ($userid == "") {
      return;
    }
    $select_contact = "
      SELECT aicrm_convertleadmapping.contactfid as fieldid ,aicrm_field.columnname , new_lead.leadfid ,new_lead.lead_columnname
      from aicrm_convertleadmapping
      LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_convertleadmapping.contactfid
      LEFT JOIN (
        SELECT aicrm_convertleadmapping.leadfid,aicrm_field.columnname as lead_columnname,aicrm_convertleadmapping.contactfid as fieldid
        from aicrm_convertleadmapping
        LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_convertleadmapping.leadfid
        ) as new_lead on new_lead.fieldid = aicrm_convertleadmapping.contactfid
        WHERE aicrm_convertleadmapping.contactfid!='0'
        ";

    $query_contact = $this->ci->db->query($select_contact);
    $column_contact = $query_contact->result(0);

    $colum_con = array();
    $value_con = array();

    if (!empty($column_contact)) {

      foreach ($column_contact as $key => $value) {
        $columnname = $value['lead_columnname'];
        if ($field_value[0][$columnname] != "") {
          $column_contact[$key]['value'] = $field_value[0][$columnname];
        } else {
          $column_contact[$key]['value'] = "";
        }
      }

      foreach ($column_contact as $key => $value) {
        $colum_con[] = $value['columnname'];
        $value_con[] = $value['value'];
      }

      if ($smownerid == "") {
        $smownerid = $userid;
      }

      if (count($colum_con) == count($value_con)) {

        $crmid = $this->ci->crmentity->Get_Crmid();

        $sql = "insert into aicrm_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime,modifiedby) values
            ('" . $crmid . "','" . $userid . "','" . $smownerid . "','Contacts','',NOW(),NOW(),'" . $userid . "')";
        $this->ci->db->query($sql);

        $colum_con = implode(", ", $colum_con);

        $sql_insert_con = "
            INSERT INTO aicrm_contactdetails (contactid,accountid," . $colum_con . ")
            VALUES ('" . $crmid . "','" . $accountid . "' ,";

        foreach ($value_con as $k => $v) {
          $sql_insert_con .= "'" . $v . "',";
        }

        $sql_insert_con = mb_substr($sql_insert_con, 0, -1);
        $sql_insert_con .= ")";
        $success = $this->ci->db->query($sql_insert_con);

        $insert_cf = "INSERT INTO aicrm_contactscf (contactid) VALUES ('" . $crmid . "') ";
        $this->ci->db->query($insert_cf);

        $insert_address = "INSERT INTO aicrm_contactaddress (contactaddressid) VALUES ('" . $crmid . "') ";
        $this->ci->db->query($insert_address);

        $insert_subdetails = "INSERT INTO aicrm_contactsubdetails (contactsubscriptionid) VALUES ('" . $crmid . "') ";
        $this->ci->db->query($insert_subdetails);

        $con_no = $this->ci->crmentity->autocd_con();
        $sql = "UPDATE aicrm_contactdetails set contact_no='" . $con_no . "' where contactid='" . $crmid . "'";
        if ($this->ci->db->query($sql)) {
          $insert_status = array(
            'status' => 'Success',
            'contactid' => $crmid,
          );
          $a_return["Type"] =  "S";
          $a_return["Message"] =  "Convert Contacts Complete";
          $a_return["data"] = array();
          $data = array(
            'crmid' => $crmid,
            'modifiedtime' => date("Y-m-d H:i:s")
          );

          array_push($a_return["data"], $data);
        } else {
          $insert_status = array(
            'status' => 'Fail'
          );
          $a_return["Type"] =  "E";
          $a_return["Message"] =  "Convert Fail";
          $a_return["data"] = array();
          $data = array();

          array_push($a_return["data"], $data);
        }

        $response_data = $a_return;

        $log_filename = "Insert_Contacts";
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->ci->common->_filename = $log_filename;
        $this->ci->common->set_log($url, $field_value, $response_data);
      }
    }

    return $insert_status;
  }



  public function convert_opportunity($field_value, $userid, $accountid, $contactid, $data_from)
  {
    if ($userid == "") {
      return;
    }

    $smownerid = @$data_from['smownerid'];
    $opportunity_name = @$data_from['opportunity_name'];
    $amount = @$data_from['amount'];
    $sales_stage = @$data_from['sales_stage'];
    $expected_close_date = @$data_from['expected_close_date'];

    $select_opportunity = "
        SELECT aicrm_convertleadmapping.potentialfid as fieldid ,aicrm_field.columnname , new_lead.leadfid ,new_lead.lead_columnname
        from aicrm_convertleadmapping
        LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_convertleadmapping.potentialfid
        LEFT JOIN (
          SELECT aicrm_convertleadmapping.leadfid,aicrm_field.columnname as lead_columnname,aicrm_convertleadmapping.potentialfid as fieldid
          from aicrm_convertleadmapping
          LEFT JOIN aicrm_field on aicrm_field.fieldid = aicrm_convertleadmapping.leadfid
          ) as new_lead on new_lead.fieldid = aicrm_convertleadmapping.potentialfid
          WHERE aicrm_convertleadmapping.potentialfid!='0'
          ";


    $query_opportunity = $this->ci->db->query($select_opportunity);
    $column_opportunity = $query_opportunity->result(0);

    $colum_opp = array();
    $value_opp = array();

    if (!empty($column_opportunity)) {

      foreach ($column_opportunity as $key => $value) {
        $columnname = $value['lead_columnname'];
        if ($field_value[0][$columnname] != "") {
          $column_opportunity[$key]['value'] = $field_value[0][$columnname];
        } else {
          $column_opportunity[$key]['value'] = "";
        }
      }

      foreach ($column_opportunity as $key => $value) {
        $colum_opp[] = $value['columnname'];
        $value_opp[] = $value['value'];
      }

      if (count($colum_opp) == count($value_opp)) {

        $crmid = $this->ci->crmentity->Get_Crmid();

        $sql = "insert into aicrm_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime,modifiedby) values
              ('" . $crmid . "','" . $userid . "','" . $smownerid . "','Opportunity','',NOW(),NOW(),'" . $userid . "')";
        $this->ci->db->query($sql);

        $colum_opp = implode(", ", $colum_opp);

        $sql_insert_opp = "
              INSERT INTO aicrm_opportunity (opportunityid,accountid,contactid," . $colum_opp . ",amount,sales_stage,expected_close_date)
              VALUES ('" . $crmid . "','" . $accountid . "','" . $contactid . "', ";

        foreach ($value_opp as $k => $v) {
          $sql_insert_opp .= "'" . $v . "',";
        }

        $sql_insert_opp = mb_substr($sql_insert_opp, 0, -1);
        $sql_insert_opp .= ",'" . $amount . "' ,'" . $sales_stage . "','" . $expected_close_date . "' )";
        $success = $this->ci->db->query($sql_insert_opp);

        if ($opportunity_name != "") {
          $sql_update_opp = "
                UPDATE aicrm_opportunity SET opportunity_name='" . $opportunity_name . "'
                WHERE opportunityid='" . $crmid . "'";
          $success_update = $this->ci->db->query($sql_update_opp);
        }

        $insertcf = "INSERT INTO aicrm_opportunitycf (opportunityid) VALUES ('" . $crmid . "') ";
        $this->ci->db->query($insertcf);

        $opp_no = $this->ci->crmentity->autocd_Opportunity();
        $sql = "UPDATE aicrm_opportunity set opportunity_no='" . $opp_no . "' where opportunityid='" . $crmid . "'";
        if ($this->ci->db->query($sql)) {
          $insert_status = array(
            'status' => 'Success',
            'opportunityid' => $crmid,
          );
          $a_return["Type"] =  "S";
          $a_return["Message"] =  "Convert Opportunity Complete";
          $a_return["data"] = array();
          $data = array(
            'crmid' => $crmid,
            'modifiedtime' => date("Y-m-d H:i:s")
          );

          array_push($a_return["data"], $data);
        } else {
          $insert_status = array(
            'status' => 'Fail'
          );
          $a_return["Type"] =  "E";
          $a_return["Message"] =  "Convert Fail";
          $a_return["data"] = array();
          $data = array();

          array_push($a_return["data"], $data);
        }

        $response_data = $a_return;

        $log_filename = "Insert_Opportunity";
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->ci->common->_filename = $log_filename;
        $this->ci->common->set_log($url, $field_value, $response_data);
      }
    }

    return $insert_status;
  }
}
