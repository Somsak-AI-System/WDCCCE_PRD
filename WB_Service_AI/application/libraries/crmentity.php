<?php
if ( !defined('BASEPATH') )
exit('No direct script access allowed');

class crmentity
{
  private $crmid;
  public function __construct(){
    $this->ci = & get_instance();
    // $this->ci->language->english->config("common");
    $this->ci->load->library("common");
    $this->ci->load->library('memcached_library');
    $this->ci->load->library('Lib_user_permission');
    
  }

  public function Get_Doc_No($module,$doc_no){
    if($module!="" and $doc_no!=""){
      $sql_from=$this->Get_Query($module);
      if($module=="Accounts"){
        $sql_select="select aicrm_account.accountid as crmid ";
        $sql=$sql_select;
        $sql.=$sql_from;
        $sql.=" and aicrm_account.account_no='".$doc_no."'";
      }else if($module=="Contacts"){
        $sql_select="select aicrm_contactdetails.contactid as crmid ";
        $sql=$sql_select;
        $sql.=$sql_from;
        $sql.=" and aicrm_contactdetails.contact_no='".$doc_no."'";
      }else if($module=="Dealer"){
        $sql_select="select aicrm_dealers.dealerid as crmid ";
        $sql=$sql_select;
        $sql.=$sql_from;
        $sql.=" and aicrm_dealers.dealer_no='".$doc_no."'";
      }
      $query = $this->ci->db->query($sql);
      if($query->num_rows()>0){
        $data = $query->result_array();
        return $data[0]["crmid"];
      }else{
        return "";
      }
    }else{
      return "";
    }
  }
  /************************************************************************************************************************************************
  START
  PGM Name: ดึงข้อมูลแบบสอบถามที่ผู้กับโครงการ เสนา
  Date:       2015-03-28 10:30
  Code By:    EKK
  ************************************************************************************************************************************************/
  public function get_questionniare_by_branch($branchid=null){
    if($branchid=="") return null;
    $sql = "
    select questionnairebranch.branchid,questionnairebranch.questionnairebranchid,questionnairebranch.questionnairetemplateid
    ,questionnairetemplate.questionnairetemplate_no,questionnairetemplate.questionnairetemplate_name
    ,questionnairetemplate.questionnaire_type

    from aicrm_questionnairebranch as questionnairebranch
    left join aicrm_questionnairetemplate as questionnairetemplate
    on questionnairebranch.questionnairetemplateid = questionnairetemplate.questionnairetemplateid

    /* left join aicrm_crmentityrel
    on questionnairetemplate.questionnairetemplateid=aicrm_crmentityrel.relcrmid */
    left join aicrm_branchs
    on aicrm_branchs.branchid= questionnairebranch.branchid
    /*  left join aicrm_branchscf
    on aicrm_branchscf.branchid=aicrm_branchs.branchid */
    left join aicrm_crmentity
    ON aicrm_crmentity.crmid = aicrm_branchs.branchid
    /*  left join aicrm_questionnairetemplatecf
    on aicrm_questionnairetemplatecf.questionnairetemplateid=questionnairetemplate.questionnairetemplateid  */
    left join aicrm_crmentity as CRMQ
    ON CRMQ.crmid = questionnairetemplate.questionnairetemplateid
    where questionnairebranch.branchid ='".$branchid."'
    and aicrm_crmentity.deleted=0
    and CRMQ.deleted=0
    /*  and aicrm_crmentityrel.module='Branch'
    and aicrm_crmentityrel.relmodule='QuestionnaireTemplate' */ ";
    //echo $sql;
    $query = $this->ci->db->query($sql);
    $data=$query->result_array();
    return $data;
  }
  public function get_choice($questionnairebranchid=null)
  {
    if($questionnairebranchid=="") return null;
    $sql = "
    select questionnairebranchchoice.questionid
    ,questionnairebranchchoice.choiceid
    ,questionnairebranchchoice.choice_weight
    ,questionchoice.choice_name
    ,question_choicetype.choicetype_name
    ,questionchoice.choice_type
    ,questionnairebranchchoice.sortid

    from aicrm_questionnairebranchchoice questionnairebranchchoice
    left join aicrm_questionchoice questionchoice
    on questionnairebranchchoice.choiceid = questionchoice.choiceid
    left join aicrm_question_choicetype question_choicetype
    on questionchoice.choice_type = question_choicetype.choicetypeid
    where questionnairebranchchoice.questionnairebranchid = '".$questionnairebranchid."'   ";
    //echo $sql;
    $sql .= " order by questionnairebranchchoice.sortid ";
    $query = $this->ci->db->query($sql);
    $data=$query->result_array();

    if(!empty($data)){
      $a_return = array();
      foreach ($data as $key => $value){
        $qustionid = $value["questionid"];
        $a_return[$qustionid][] = $value;
      }
      return $a_return;
    }else{
      return null;
    }
  }
  public function get_question($a_param=array())
  {
    if($a_param["questionnairetemplateid"]=="") return null;
    $questionnairetemplateid = $a_param["questionnairetemplateid"];
    $questionnairebranchid = $a_param["questionnairebranchid"];
    $sql = "
    select question.questionid,question.question_name,question.question_help
    ,question_choicetype.choicetype_name
    from aicrm_questionnairetemplatedtl as questionnairetemplatedtl
    left join aicrm_question as question
    on question.questionid = questionnairetemplatedtl.questionid
    left join aicrm_question_choicetype question_choicetype
    on question.choice_type = question_choicetype.choicetypeid
    where 1
    and questionnairetemplatedtl.active=1
    and questionnairetemplatedtl.questionnairetemplateid='".$questionnairetemplateid."' ";
    $sql .= " order by questionnairetemplatedtl.sortid ";
    //echo $sql;
    $query = $this->ci->db->query($sql);
    $data=$query->result_array();
    $a_data_choice = $this->get_choice($questionnairebranchid);
    if(!empty($data)){
      foreach ($data as $key => $value){
        $questionid = $value["questionid"];
        //$a_return[$key]["totalquestion"] = count($value) ;
        $a_return[$key] = $value;
        $a_choice = empty($a_data_choice[$questionid]) ? array():$a_data_choice[$questionid] ;
        if(count($a_choice)<=0){
          unset($a_return[$key]);
        }else{
          $a_return[$key]["totalchoice"] = count($a_choice) ;
          $a_return[$key]["data_choice"] = $a_choice;
        }
      }
      return $a_return;
    }else{
      return null;
    }

  }
  public function Get_Question_Detail($crm_id){
    $a_qustionniare_template = $this->get_questionniare_by_branch($crm_id);
    $data_question = array();
    $data_questionnaire = array();
    if(count($a_qustionniare_template)>0){
      for($k=0;$k<count($a_qustionniare_template);$k++){
        $data_questionnaire[$k]["questionnaire_template"] =  $a_qustionniare_template[$k];
        $a_param["questionnairetemplateid"] = $a_qustionniare_template[$k]["questionnairetemplateid"];
        $a_param["questionnairebranchid"] = $a_qustionniare_template[$k]["questionnairebranchid"];
        $data_question = $this->get_question($a_param);

        $data_questionnaire[$k]["questionnaire_template"]["data_question"]=$data_question;
      }
    }
    $a_return = $data_questionnaire;

    return $a_return;
  }
/************************************************************************************************************************************************
END
************************************************************************************************************************************************/
public function Get_Inventory_Detail($crm_id){
  $sql="
  select
  aicrm_promotion.promotionid
  ,'S' as pro_type
  ,aicrm_inventory_protab3_dtl.productid
  ,aicrm_inventory_protab3_dtl.quantity as qty
  ,aicrm_inventory_protab3_dtl.listprice as listprice
  ,aicrm_inventory_protab3_dtl.uom
  ,aicrm_products.productname
  ,aicrm_products.imagename
  FROM aicrm_promotion
  INNER JOIN aicrm_promotioncf ON aicrm_promotioncf.promotionid = aicrm_promotion.promotionid
  INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_promotion.promotionid
  LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
  LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
  left join aicrm_inventory_protab3_dtl on aicrm_inventory_protab3_dtl.id=aicrm_promotion.promotionid
  LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_inventory_protab3_dtl.productid
  left join aicrm_productcf on aicrm_productcf.productid=aicrm_inventory_protab3_dtl.productid
  WHERE aicrm_crmentity.deleted = 0
  and aicrm_promotion.promotionid='".$crm_id."'
  ";
  //echo $sql;exit;
  $query = $this->ci->db->query($sql);
  return $query;
}

public function Get_Unit_Detail($crm_id,$building_id,$floor_id){
  $tabid=$this->Get_Tab_ID("Products");
  $data_field = $this->Get_Field_Show($tabid,"");
  $field_select="";
  if(count($data_field)>0){
    $field_select=$this->Get_Select_Field($data_field);
  }else{
    $field_select="aicrm_crmentity.crmid";
  }
  $sql="select ".$field_select.",aicrm_products.productid
  FROM aicrm_products
  left join aicrm_productcf on aicrm_productcf.productid=aicrm_products.productid
  left join aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
  left join aicrm_buildingcf on aicrm_buildingcf.buildingid=aicrm_products.buildingid
  left join aicrm_branchs on aicrm_branchs.branchid=aicrm_buildingcf.cf_1059
  WHERE aicrm_crmentity.deleted = 0
  ";
  if($crm_id!=""){
    $sql.=" and aicrm_branchs.branchid='".$crm_id."' ";
  }
  if($building_id!=""){
    $sql.=" and aicrm_products.buildingid='".$building_id."'";
  }
  if($floor_id!=""){
    $sql.=" and aicrm_productcf.cf_2067='".$floor_id."'";
  }
  //$sql.=" limit 1";
  $query = $this->ci->db->query($sql);
  return $query;
}

public function get_edit($module,$crm_id){
  return $this->Get_Block_Detail($module,$crm_id);
}
public function Get_Image($module,$crm_id){
  //$link="http://liveandfit.com/AICRM/";
  $link="http://".$_SERVER['HTTP_HOST']."/sena/";
  //echo $module."<br>";
  if($module == 'Products'){
    $sql = "select
    aicrm_attachments.attachmentsid,
    aicrm_attachments.path,
    aicrm_attachments.attachmentsid,
    aicrm_attachments.name ,
    aicrm_crmentity.setype
    from aicrm_products
    left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_products.productid
    inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
    inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
    where aicrm_crmentity.setype='Products Image'
    and productid='".$crm_id."'";
    //echo $sql."<br>";
  }else if($module == 'Promotion'){
    $sql = "select
    aicrm_attachments.attachmentsid,
    aicrm_attachments.path,
    aicrm_attachments.attachmentsid,
    aicrm_attachments.name ,
    aicrm_crmentity.setype
    from aicrm_promotioncf
    left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_promotioncf.promotionid
    inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
    inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
    where aicrm_crmentity.setype='Promotion Image'
    and promotionid='".$crm_id."'";
  }else if($module == 'Premium'){
    $sql = "select
    aicrm_attachments.attachmentsid,
    aicrm_attachments.path,
    aicrm_attachments.attachmentsid,
    aicrm_attachments.name ,
    aicrm_crmentity.setype
    from aicrm_premiumscf
    left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_premiumscf.premiumid
    inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
    inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
    where aicrm_crmentity.setype='Premium Image'
    and premiumid='".$crm_id."'";
  }else{
    $sql="select aicrm_attachments.*,aicrm_crmentity.setype from aicrm_attachments inner join aicrm_seattachmentsrel on aicrm_seattachmentsrel.attachmentsid = aicrm_attachments.attachmentsid inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid where aicrm_crmentity.setype='Contacts Image' and aicrm_seattachmentsrel.crmid='".$crm_id."'";
  }
  $query = $this->ci->db->query($sql);
  if($query->num_rows()>0){
    $data = $query->result_array();
    $pic=array();
    for($i=0;$i<count($data);$i++){
      $pic[]=$link."/".$data[$i]["path"]."/".$data[$i]["attachmentsid"]."_".$data[$i]["name"];
    }
    return $pic;
  }else{
    return "";
  }
}
public function Get_Tab_ID($module){
  $sql="
  select
  tabid
  from aicrm_tab
  where 1
  and tablabel='".$module."'
  ";

  $query = $this->ci->db->query($sql);
  if($query->num_rows()>0){
    $data = $query->result_array();
    $tabid=$data[0]["tabid"];
  }
  return $tabid;
}
public function Get_Field_Show($tabid,$block_id=""){

  $sql="
  select
  fieldname,columnname,uitype,generatedtype,typeofdata,fieldlabel,tablename
  ,typeofdata
  from aicrm_field
  where 1
  and tabid='".$tabid."'
  and displaytype in (1,3,4)
  and aicrm_field.presence in (0,2)
  or (
    typeofdata='V~M'
    and presence<>'2'
    and tabid ='".$tabid."'
    )
    and columnname<>'smownerid'
    ";
    if($block_id!=""){
      $sql.="and block='".$block_id."'";
    }
    $sql.="
    ORDER BY block,sequence,quickcreatesequence
    ";
    $query = $this->ci->db->query($sql);
    $data_field = $query->result_array();
    return $data_field;
  }
  public function Get_Field_Show_By_Block($tabid,$block_id){
    $where_field="";
    $str="";
    $sql="select filed_name from tbt_field_show_on_mobile where 1 and tabid='".$tabid."' ";
    $query = $this->ci->db->query($sql);
    if($query->num_rows()>0){
      $data_field_name = $query->result_array();
      for($i=0;$i<count($data_field_name);$i++){
        if($str==""){
          $str="'".$data_field_name[$i]['filed_name']."'";
        }else{
          $str.=",'".$data_field_name[$i]['filed_name']."'";
        }
      }
      if($str!=""){
        $where_field=" and columnname in(".$str.")";
      }
    }

    $sql="
    select
    fieldname,columnname,uitype,generatedtype,typeofdata,fieldlabel,tablename
    from aicrm_field
    where 1
    and tabid='".$tabid."'
    and displaytype in (1,3,4)
    and aicrm_field.presence in (0,2)
    and columnname<>'smownerid'
    ".$where_field."
    ";
    if($block_id!=""){
      $sql.="and block='".$block_id."'";
    }
    $sql.="
    ORDER BY block,sequence,quickcreatesequence
    ";
    $query = $this->ci->db->query($sql);
    
    $data_field = $query->result_array();
    return $data_field;
  }
  public function Get_Select_Field($data_field){
    $select_field="";
    for($j=0;$j<count($data_field);$j++){
      $columnname=$data_field[$j]['columnname'];
      $tablename=$data_field[$j]['tablename'];
      if($select_field==""){
        $select_field=$tablename.".".$columnname;
      }else{
        $select_field.=",".$tablename.".".$columnname;
      }

    }
    return $select_field;
  }
  public function Get_Block_Detail($module,$crm_id){
    $tabid=$this->Get_Tab_ID($module);
    $sql="
    select
    blockid,blocklabel,sequence
    from aicrm_blocks
    where 1
    and tabid='".$tabid."'
    order by sequence
    ";
    $query = $this->ci->db->query($sql);

    if($query->num_rows()>0){
      $data_block = $query->result_array();
      $a=0;
      $data_form1=array();
      for($i=0;$i<count($data_block);$i++){
        $data_fs=array();
        $data_f=array();
        $data_field = $this->Get_Field_Show_By_Block($tabid,$data_block[$i]['blockid']);
        if(count($data_field)>0){
          $data_block1=$this->Get_field($module,$data_field,$data_block[$i]['blocklabel'],$crm_id);
          $data_form1[]=$data_block1;
        }
      }
    }
    return $data_form1;
  }

  public function Get_Block_Detail_By_SENA($module,$crm_id){
    $tabid=$this->Get_Tab_ID($module);
    $sql="
    select
    blockid,blocklabel,sequence
    from aicrm_blocks
    where 1
    and tabid='".$tabid."'
    order by sequence
    ";
    $query = $this->ci->db->query($sql);
    if($query->num_rows()>0){
      $data_block = $query->result_array();
      $a=0;
      $data_form1=array();
      for($i=0;$i<count($data_block);$i++){
        $data_fs=array();
        $data_f=array();
        $data_field = $this->Get_Field_Show_By_Block($tabid,$data_block[$i]['blockid']);
        if(count($data_field)>0){
          $data_block1=$this->Get_field($module,$data_field,$data_block[$i]['blocklabel'],$crm_id);
          $data_form1[]=$data_block1;
        }
      }
    }
    return $data_form1;
  }

  public function Get_field($module="",$data_field=array(),$blocklabel="",$crm_id="",$userid="",$crm_subid="",$related_module="",$action="",$templateid=""){

    if($module=="Users"){
      $crm_id = $userid;
    }
    $user_lang = "EN";
    $user_lang =$this->Get_userlang($userid);

    if(empty($user_lang)){
      $user_lang="EN";
    }

    $data_title="";
    $format_date="";
    $is_hidden="false";
    $key_ref_uitype = "";

    for($j=0;$j<count($data_field);$j++){
      $link_status="false";
      $key_valuename="";
      $key_field_up=array();
      $key_field_down=array();
      $columnname=$data_field[$j]['columnname'];
      $fieldlabel=$data_field[$j]['fieldlabel'];
      $fieldname=$data_field[$j]['fieldname'];
      $maximumlength=$data_field[$j]['maximumlength'];

      $minimumlenght=0;
      $invalid_message='';

      if($module=="Users"){
        $maximumlength="100";
      }
      if($fieldlabel=="Visibility"){
        $fieldlabel="Mark Public";
      }
      $tablename=$data_field[$j]['tablename'];
      $uitype=$data_field[$j]['uitype'];
      
      $readonly = isset($data_field[$j]['readonly'])?$data_field[$j]['readonly']:"";
      
      if($crm_id!=""){
        $readonly = isset($data_field[$j]['readonly'])?$data_field[$j]['readonly']:"";
      }else{
        $readonly = isset($data_field[$j]['readonly'])?$data_field[$j]['readonly']:"";
      }

      $typeofdata=$data_field[$j]['typeofdata'];
      $type_of_data  = explode('~',$typeofdata);

      $type=isset($type_of_data[0]) ? $type_of_data[0] : "";
      $typeof_data=isset($type_of_data[1]) ? $type_of_data[1] : "";
      $charset=isset($type_of_data[2]) ? $type_of_data[2] : "";
      $lenght=isset($type_of_data[3]) ? $type_of_data[3] : "";
      
      if($uitype=="15"){
        $type_1="select";
        $is_array="true";
        
        if($data_field[$j]['fieldlabel']=="Priority"){
          $data_pick=$this->Get_PicklistValue("taskpriority",$userid);
          $module_select="";
        }else{
          if($module=="HelpDesk" && $columnname=="status"){
            $columnname2="ticketstatus";
            $data_pick=$this->Get_PicklistValue($columnname2,$userid);
            $module_select="";
          }else if($module=="Quotes" && $columnname=="quotation_buyer"){
            $data_pick=$this->Get_Picklist_Buyer($columnname,$userid);
            $module_select="";
          }else {
            $data_pick=$this->Get_PicklistValue($columnname,$userid);
            $module_select="";
          }
        }
        
      }else if($uitype=="33"){
        $type_1="multiple";
        $data_pick=$this->Get_PicklistValue($columnname,$userid);

        if($module == 'Quotes'){
          $data_pick = $this->getPickListUitype33($columnname, $module);
        }else if($module == 'Projects'){
          $data_pick = $this->getPickList_Alluser($columnname, $module);
        }else if($module == 'Samplerequisition'){
          $data_pick = $this->getPickListUitype_samplere($columnname, $module);
        }else if($module == 'Expense'){
          $data_pick = $this->getPickListUitype_expense($columnname, $module);
        }

        $module_select="";
      }else if($uitype=="56"){
        $type_1="checkbox";
        $data_pick="";
        $module_select="";
        // $data_pick="";
      }else if($uitype=="5"){
        $type_1="date";
        $data_pick="";
        $module_select="";
        $format_date="d MMMM yyyy";
        // $format_date="d MMMM yyyy HH:mm:ss";
        // $format_date="EEEE d MMMM yyyy";
      }else if($uitype=="1"){
        $type_1="varchar(100)";
        $data_pick="";
        $module_select="";
        $format_date="";
      }else if($uitype=="70"){
        $type_1="datetime";
        $data_pick="";
        $module_select="";
        $format_date="d MMMM yyyy HH:mm:ss";
      }else if($uitype=="933"){
        $type_1="time";
        $data_pick="";
        $module_select="";
        $format_date="HH:mm";
      }else if($uitype=="19" || $uitype=="21"){
        $type_1 = "textarea";
        $data_pick="";
        $module_select="";
        //$readonly = "0";
      }else if($uitype=="914" ){
        $type_1 = "select_index";
        $module_select="Project Order";
        //$module_select="Job";
        $key_valuename = "name";
      }else if($uitype=="201" ){
        $type_1 = "select_index";
        $module_select= $related_module == 'Leads' ? $related_module:'Accounts';
        $key_valuename = "name";
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_field_up = $field_up;
        $key_field_down = $field_down;
      }else if($uitype=="73" ||$uitype=="66" || $uitype=="51" || $uitype=="800"){
        $type_1="select_index";
        $module_select = "Accounts";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_field_up = $field_up;
        $key_field_down = $field_down;
        $key_valuename = $valuename;
      }else if($uitype=="947"){
        $type_1="select_index";
        $module_select = "Questionnaire";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_field_up = $field_up;
        $key_field_down = $field_down;
        $key_valuename = $valuename;
      }else if($uitype=="944"){
        $type_1="select_index";
        $module_select = "Calendar";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_field_up = $field_up;
        $key_field_down = $field_down;
        $key_valuename = $valuename;
      }else if($uitype=="57" ){
        $type_1="select_index";
        $module_select = "Contacts";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_field_up = $field_up;
        $key_field_down = $field_down;
        $key_valuename = $valuename;
      }else if($uitype=="906"){
        $type_1="select_index";
        $module_select = "Case";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_field_up = $field_up;
        $key_field_down = $field_down;
        $key_valuename = $valuename;
      }else if($uitype=="58"){
        $type_1="select_index";
        $module_select = "Campaigns";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_field_up = $field_up;
        $key_field_down = $field_down;
        $key_valuename = $valuename;
      }else if($uitype=="903"){
        $type_1="select_index";
        $module_select = "Users";
        $data_pick = $this->get_userlist($userid,$uitype);

        $key_valuename="username";
      }else if($uitype=="53"){
        $type_1="select_index";
        $module_select = "Users";

        $data_pick = $this->get_userlist($userid,$uitype);
        $key_valuename="user_name";
      }else if($uitype=="26"){
        $type_1="select_index";
        $module_select = "Folder";
        $data_pick=[];
        $key_valuename="foldername";
      }else if($uitype=="936"){
        $type_1="select_index";
        $module_select = "Sparepart";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_field_up = $field_up;
        $key_field_down = $field_down;
        $key_valuename = $valuename;
      }else if($uitype=="59"){
        $type_1="select_index";
        $module_select = "Products";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_field_up = $field_up;
        $key_field_down = $field_down;
        $key_valuename = $valuename;
      }else if($uitype=="935"){
        $type_1="select_index";
        $module_select = "Serial";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_field_up = $field_up;
        $key_field_down = $field_down;
        $key_valuename = $valuename;
      }else if($uitype=="938"){
        $type_1="select_index";
        $module_select = "Job";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_valuename = $valuename;
        $key_field_up = $field_up;
        $key_field_down = $field_down;
      }else if($uitype=="937"){
        $type_1="select_index";
        $module_select = "Errors";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_valuename = $valuename;
        $key_field_up = $field_up;
        $key_field_down = $field_down;
      }else if($uitype=="939"){
        $type_1="select_index";
        $module_select = "Case";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_valuename = $valuename;
        $key_field_up = $field_up;
        $key_field_down = $field_down;
      }else if($uitype=="945"){
        $type_1="select_index";
        $module_select = "Deal";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_valuename = $valuename;
        $key_field_up = $field_up;
        $key_field_down = $field_down;
      }else if($uitype=="301"){
        $type_1="select_index";
        $module_select = "Deal";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_valuename = $valuename;
        $key_field_up = $field_up;
        $key_field_down = $field_down;
      }else if($uitype=="302"){
        $type_1="select_index";
        $module_select = "Competitor";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_valuename = $valuename;
        $key_field_up = $field_up;
        $key_field_down = $field_down;
      }else if($uitype=="304"){
        $type_1="select_index";
        $module_select = "Promotion";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_valuename = $valuename;
        $key_field_up = $field_up;
        $key_field_down = $field_down;
      }else if($uitype=="904" ){
        $type_1="select_index";
        $module_select = "Projects";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_field_up = $field_up;
        $key_field_down = $field_down;
        $key_valuename = $valuename;
      }else if($data_field[$j]['columnname']=="date_start" || $data_field[$j]['columnname']=="due_date"){
        $type_1="date";
        $data_pick="";
        $module_select="";
      }else if($data_field[$j]['columnname']=="time_start" || $data_field[$j]['columnname']=="time_end"){
        $type_1="time";
        $data_pick="";
        $module_select="";
      }else if($uitype=="931"){
        $type_1="select_index";
        $module_select = "Contacts";
        $data_pick=[];
        list($field_up,$field_down,$valuename) = $this->Get_related_field($module,$columnname);
        $key_valuename = $valuename;
        $key_field_up = $field_up;
        $key_field_down = $field_down;
      }else if($uitype=="933"){
        $type_1="time";
        $data_pick="";
        $module_select="";
      }else if($data_field[$j]['columnname']=="visibility"){
        $type_1="checkbox";
        $data_pick="";
        $module_select="";
      }else if($columnname=="cf_1246" || $columnname=="cf_1247" || $columnname=="cf_1248" || $columnname=="cf_1249"
        || $columnname=="cf_1425" || $columnname=="cf_1427" || $columnname=="cf_1432" || $columnname=="cf_1430"
        || $columnname=="cf_1488" || $columnname=="cf_1490" || $columnname=="cf_1492" || $columnname=="cf_1494"){
        $type_1="datetime";
        $data_pick="";
        $module_select="";
      }else{
        if(($type=="V" || $type=="E") && $lenght==""){
          $type_1="varchar(100)";
        }else if (($type=="V" || $type=="E") && $lenght!=""){
          $type_1="varchar(".$lenght.")";
          $module_select="";
        }else if ($type=="V" || $typeof_data=="O" || $charset=="LE" || $lenght!=""){//echo "rrr";exit;
          // echo "rrr";exit;
          $type_1="varchar(".$lenght.")";
          $module_select="";
        }else if ($type=="D"){
          $type_1="date";
          $module_select="";
        }else if ($type=="N"){
          $type_1="decimal(10,2)";
          $module_select="";
        }else{
          $type_1="varchar(100)";
          $module_select="";
        }
        $data_pick="";
        $module_select="";

      }
      $value_name = '';

      if($data_field[$j]['columnname']=="visibility"){
        if($crm_id!=""){

          $value =  $this->Get_Value($module,$columnname,$tablename,$crm_id,$uitype);
          if($value=="Private"){
            $value ="0";
          }else{
            $value ="1";
          }
          
        }else{
          $value = "";
        }
      }

      // echo 'Crmentity line 789 '.$uitype.' | ';
      if($crm_subid!="" && $related_module!="") {
          list($data_related)= $this->get_value_relatedid($crm_subid,$related_module,$columnname,$key_valuename);
         
          if($data_related==""){
            $value = "";
            $no = "";
            $name = "";
          }else {
            // alert($data_related);
            $no = $data_related['no'];
            $name = $data_related['name'];
            $value_name = $data_related['name'];
            $value = $data_related['id'];
            // $value = $data_related['name'];
            // alert($data_related);
            // alert($crm_subid);
            // alert($related_module);
            // alert($columnname);
          }

      }elseif($crm_id=="" && $crm_subid==""){
        // || $crm_subid==""
        $value = "";
        $no = "";
        $name = "";
      }elseif($crm_id!=""){
          if($uitype=="73" || $uitype=="51" ){
            list($value,$value_name)= $this->Get_Value_Account($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);

            $is_account = "true";
            if($value !=""){
              $link_status="true";
              $no = $value_name['account_no'];
              $name = $value_name['accountname'];
              $value_name = $value_name['accountname'];
              if($module == 'Calendar' || $module == 'Job'){
                //$readonly = "1";
              }
            }else{
              $value_name="";
            }
          }elseif($uitype=="57"){
            list($value,$value_name)= $this->Get_Value_Contact($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['contact_no'];
              $name = $value_name['contactname'];
              $value_name = $value_name['contactname'];
              if($module == 'Calendar'){
                //$readonly = "1";
              }
            }else{
              $value_name="";
            }
          }elseif($uitype=="944"){
            list($value,$value_name)= $this->Get_Value_Calendar($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['eventstatus'];
              $name = $value_name['activitytype'];
              $value_name = $value_name['activitytype'];
            }else{
              $value_name="";
            }
          }elseif($uitype=="945"){
            list($value,$value_name)= $this->Get_Value_Deal($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['deal_no'];
              $name = $value_name['deal_name'];
              $value_name = $value_name['deal_no'];
            }else{
              $value_name="";
            }
          }elseif($uitype=="301"){
            list($value,$value_name)= $this->Get_Value_Deal($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['deal_no'];
              $name = $value_name['deal_name'];
              $value_name = $value_name['deal_name'] == '' ? $value_name['deal_no']:$value_name['deal_name'];
            }else{
              $value_name="";
            }
          }elseif($uitype=="302"){
            list($value,$value_name)= $this->Get_Value_Competitor($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);

            if($value != "" ){
              $link_status="true";
              $no = $value_name['competitor_no'];
              $name = $value_name['competitor_name'];
              $value_name = $value_name['competitor_name'];
            }else{
              $value_name="";
            }
          }elseif($uitype=="947"){
            list($value,$value_name)= $this->Get_Value_Questionnaire($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['questionnaire_no'];
              $name = $value_name['questionnaire_name'];
              $value_name = $value_name['questionnaire_name'];
            }else{
              $value_name="";
            }
          }elseif($uitype=="59"){
            list($value,$value_name)= $this->Get_Value_Product($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['product_no'];
              $name = $value_name['productname'];
              $value_name = $value_name['productname'];
              $readonly = "0";
            }else{
              $value_name="";
            }
          }elseif($uitype=="58"){
            list($value,$value_name)= $this->Get_Value_Campaign($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['campaign_no'];
              $name = $value_name['campaignname'];
              $value_name = $value_name['campaignname'];
              $readonly = "0";
            }else{
              $value_name="";
            }
          }elseif($uitype=="304"){
            list($value,$value_name)= $this->Get_Value_Promotion($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['promotion_no'];
              $name = $value_name['promotion_name'];
              $value_name = $value_name['promotion_name'];
              $readonly = "0";
            }else{
              $value_name="";
            }
          }elseif($uitype=="931"){
            list($value,$value_name)= $this->Get_Value_Contact($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['contact_no'];
              $name = $value_name['contactname'];
              $value_name = $value_name['contactname'];
              $readonly = "0";
            }else{
              $value_name="";
            }
          }elseif($uitype=="935"){
            list($value,$value_name)= $this->Get_Value_Serial($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['serial_no'];
              $name = $value_name['serial_name'];
              $value_name = $value_name['serial_name'];
              $readonly = "0";
            }else{
              $value_name="";
            }
          }elseif($uitype=="939"){
            list($value,$value_name)= $this->Get_Value_Case($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['ticket_no'];
              $name = $value_name['title'];
              $value_name = $value_name['ticket_no'];
              $readonly = "0";
            }else{
              $value_name="";
            }
          }elseif($uitype=="936"){
            list($value,$value_name)= $this->Get_Value_Sparepart($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['sparepart_no'];
              $name = $value_name['sparepart_name'];
              $value_name = $value_name['sparepart_no'];
              $readonly = "0";
            }else{
              $value_name="";
            }
          }elseif($uitype=="938"){
            list($value,$value_name)= $this->Get_Value_Job($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['job_no'];
              $name = $value_name['job_name'];
              $value_name = $value_name['job_name'];
              $readonly = "0";
            }else{
              $value_name="";
            }
          }elseif($uitype=="937"){
            list($value,$value_name)= $this->Get_Value_Errors($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['errors_no'];
              $name = $value_name['errors_name'];
              $value_name = $value_name['errors_no'];
              $readonly = "0";
            }else{
              $value_name="";
            }
          }elseif($uitype=="26"){
            list($value,$value_name)= $this->Get_Value_Folder($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="false";
              $no = $value_name['note_no'];
              $name = $value_name['title'];
              $value_name = $value_name['foldername'];
              $readonly = "1";
            }else{
              $value_name="";
            }
          }elseif($uitype=="934"){
            list($value,$value_name)= $this->Get_Value_Projects($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['projects_no'];
              $name = $value_name['projects_name'];
              $value_name = $value_name['projects_name'];
              $readonly = "1";
            }else{
              $value_name="";
            }
          }elseif($uitype=="914"){
            list($value,$value_name)= $this->Get_Value_Event($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['no'];
              $name = $value_name['name'];
              if($value_name['module']!=""){
                $module_select = $value_name['module'];
              }
              $value_name = $value_name['name'];

              $readonly = "0";
            }else{
              $value_name="";
            }
          }elseif($uitype=="201"){
            if($module == 'Deal' || $module == 'Quotes'){
              list($value,$value_name)= $this->Get_Value_Parent($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            }else if($module == 'Calendar'){
              list($value,$value_name)= $this->Get_Value_Parent_Calendar($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            }else{
              list($value,$value_name)= $this->Get_Value_Event($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            }
            
            if($value != "" ){
              $link_status="true";
              $no = $value_name['no'];
              $name = $value_name['name'];
              if($value_name['module']!=""){
                $module_select = $value_name['module'];
              }
              $value_name = $value_name['name'];

              $readonly = "0";
            }else{
              $value_name="";
            }
          }elseif($uitype=="903"){
            list($value,$value_name)= $this->Get_Value_assign($module,$columnname,$tablename,$crm_id,$uitype,$userid,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="false";
              $no = "";
              $name = "";
              $readonly = "0";
            }else{
              $value_name="";
            }
          }elseif($uitype=="53"){
            list($value,$value_name)= $this->Get_Value_assign($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="false";
              $no = "";
              $name = "";
              $readonly = "0";
            }else{
              $value_name="";
            }
          }else if($uitype=="998" || $uitype=="999"){
            list($value,$value_name)= $this->Get_Value_Signature($crm_id,$uitype,$crm_subid,$related_module);
            if($value_name!=""){
              $value= $value;
              $value_name= $value_name;
            }else {
              $value_name="";
              $value="";
            }
          }else if($uitype=="116" && $columnname=="currency_id"){
            list($value,$value_name)= $this->Get_Value_Currency($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid);
            if($value_name!=""){
              // $value= $value_name['currency_id'];
              $value= $value_name['currency_name'].":".$value_name['currency_symbol'];
              $value_name= $value_name['currency_name'];
            }else {
              $value_name="";
              $value="";
            }
          }else if($uitype=="98" ){
            list($value,$value_name)= $this->Get_Value_rolename($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid);
            if($value_name!=""){
              $value= $value_name['rolename'];
              $value_name= "";
              $readonly="1";
            }else {
              $value_name="";
              $value="";
            }
          }else if($uitype=="946" ){
              
            list($value,$value_name)= $this->Get_Value_template($module,$templateid,$crm_id);
            if($value_name!=""){
              $value= $value_name['questionnairetemplate_name'];
              $value_name= "";
              $readonly="1";
            }else {
              $value_name="";
              $value="";
              $readonly="1";
            }
           }else if($uitype=="910" ){
              
            list($value,$value_name)= $this->Get_Value_template($module,$templateid,$crm_id);
            if($value_name!=""){
              $value= $value_name['questionnairetemplate_name'];
              $value_name= "";
              $readonly="1";
            }else {
              $value_name="";
              $value="";
              $readonly="1";
            }
          }elseif($uitype=="904"){
            list($value,$value_name)= $this->Get_Value_Projects($module,$columnname,$tablename,$crm_id,$uitype,$crm_subid,$related_module);
            if($value != "" ){
              $link_status="true";
              $no = $value_name['projects_no'];
              $name = $value_name['projects_name'];
              $value_name = $value_name['projects_name'];
              if($module=="Expense"){
                $readonly = "0";
              }else{
                $readonly = "1";
              }
            }else{
              $value_name="";
            }
          }else{
            $value = $this->Get_Value($module,$columnname,$tablename,$crm_id,$uitype);
            $no = "";
            $name = "";
            if($uitype=="19" && $module =='Calendar' && $columnname=="description" && $value != '' ){
              $readonly = "0";
            }

            // if($uitype=="15" && $module =='Calendar' && $columnname=="activitytype"  && $value != ''){
            //   $readonly = "1";
            // }

            if($uitype=="15" && $module =='Job' && $columnname=="jobtype"  && $value != '' ){
              $readonly = "1";
            }


            if(($uitype=="5" || $uitype=="933") && $module == 'Job' && ($columnname=="jobdate_operate" || $columnname=="job_minit" || $columnname=="close_date")  && $value != ''){
              $readonly = "1";
            }

            if(($uitype=="5" || $uitype=="933") && $module =='Calendar' && ($columnname=="time_start" || $columnname=="due_date" || $columnname=="time_end") && $value != ''){
              $readonly = "0";
            }

            /*Bas 2022-03-22*/
            if(($uitype=="71" || $uitype== '7') && $action =='view'){
              $value = number_format($value,2);
            }
          }
      }else{

      }

      if($action == 'add' && $module =='Quotes' && ($data_field[$j]['columnname'] == 'terms_conditions' || $data_field[$j]['columnname'] == 'quote_termcondition')){
        
        $value = $this->get_terms_conditions($data_field[$j]['columnname']);
        
      }

      if($value=="0000-00-00"||$value=="0"){
        $value="";
      }
      if($fieldname=="email1" || $fieldname=="email"){
        $keyboard_type = "email";
        if($value!=""){
          $link_status="true";
        }
      }elseif($fieldname=="phone"  || $fieldname=="mobile" || $fieldname=="telephone" || $fieldname=="tel" || $fieldname=="quota1_phone" || $fieldname=="quota_phonerecieve"){
        $keyboard_type = "phone";
        $minimumlenght = "9";
        if($value!=0){
          $link_status="true";
        }
        /*if($fieldname=="mobile"){
          $maximumlength = "10";
        }*/
      }elseif($fieldname=="mileage_start" || $fieldname=="mileage_end" || $fieldname=="expense_amount"){
        $keyboard_type = "number";
      }elseif($fieldname=="passportno" || $fieldname=="con_idcard"){
        $keyboard_type = "number";
      
      }elseif($fieldname=="lead_postalcode" || $fieldname=="postalcode"){
        $keyboard_type = "phone";

      }elseif($fieldname=="budget" || $fieldname=="dawnpayment" || $fieldname=="numofmonth" || $fieldname=="installments"){
        $keyboard_type = "number";

      }elseif($fieldname=="website" || $fieldname=="cf_3926"){
        $keyboard_type = "url";
        if($value!=""){
          $link_status="true";
        }
      }elseif($fieldname=="filename"){
        $keyboard_type = "default";
        if($value!=""){
          $link_status="true";
        }
      }else {
        $keyboard_type = "default";
      }

      $count_arr=explode(" |##| ",$value);
      if(count($count_arr)>1){

        $value =$count_arr;
      }
      if ($typeof_data == 'M'){
        $check_value="yes";
        $error_message=$fieldlabel." cannot be empty";
        $invalid_message=$fieldlabel." is not correct";
      }else{
        $check_value = $value == '1' ? 'yes':'no';
        $error_message="";
        $invalid_message=$fieldlabel." is not correct";
      }
      $is_phone = "false";
      $is_account = "false";
      $is_checkin = "false";

      if($uitype=="11"){
        $is_phone = "true";
      }
      if($columnname=="accountid"){
        $is_account = "true";
      }

      if($columnname=="location"){
        $is_checkin = "true";
      }

      if($uitype=="15" && $data_pick!="" && $crm_id==""){

        if($module == "Quotes"){

          if($action == "add" && $fieldname=="quotation_status"){
            $readonly = "1";
            $value = "เปิดใบเสนอราคา";
          }

        }else{
          $value = $data_pick[0];

        }
      }

      if($uitype=="33" && $data_pick!="" && $crm_id==""){

        $value = "";
      }

      if($columnname=="jobdate" && $crm_id==""){
        $value = date('Y-m-d');
      }

      if($columnname=="notification_time" && $crm_id==""){
        $value = date('H:i');
      }

      if($module == 'Calendar' && $action == 'add'){
        if($columnname=="date_start" || $columnname=="due_date"){
          // $value = date('Y-m-d');
        }

        if($columnname=="time_start" || $columnname=="time_end"){
          // $value = date('H:i');
        }
        
      }else if($module == 'Calendar' && $action == 'duplicate'){
        if($columnname=="checkoutdate" || $columnname=="location_chkout" || $columnname=="checkindate" || $columnname=="location"){
          $value = "";
        }
      }

      if($module == 'Deal' && $action == 'duplicate'){
        if($columnname=="stage"){
          $value = 'Lead / Open';
        }

        if($columnname=="wonreason"){
          $value = '';
        }
      }

      if($crm_id==""){
        if($uitype=="53"){

          $value = $userid;
          $modul_user = "Users";
          $columnname_user = "id";
          $tablename_user = "aicrm_users";
          list($value,$value_name)= $this->Get_Value_assign($modul_user,$columnname_user,$tablename_user,$crm_id,$uitype,$userid);

        }

      }
      // if($uitype=="938" || $uitype=="914"){
      //   if($module == "Sparepartlist" || $module == "Errorslist" || $module == "Documents" || $module == "Calendar"){
      //     if($crm_id==""){
      //       if($crm_subid!=""){

      //         $value = $crm_subid;
      //         $modul_user = "Job";
      //         $columnname_user = "jobid";
      //         $tablename_user = "aicrm_jobs";
      //         list($value,$value_name)= $this->Get_Value_Job($modul_user,$columnname_user,$tablename_user,$crm_id,$uitype,$crm_subid);
      //         $no = "";
      //         $name = "";
      //         $value_name = $value_name['job_name'];
      //       }
      //     }
      //   }

      // }

      if($uitype=="15" && $columnname=="activitytype" && $crm_id == ''){
        $value = $data_pick[0];
      }


      if($crm_id !=""){

        if($module=="Users"){
          $data_title="";
        }else{

          $select=$this->Get_QuerySelect($module);
          $list=$this->Get_Query($module);

          $sql_select = "select ".$select." ".$list." and aicrm_crmentity.crmid='".$crm_id."'";
          // echo $sql_select; exit;
          if(!empty($this->ci->db->query($sql_select))){
            $query_select = $this->ci->db->query($sql_select);
              $data_title  = $query_select->result_array() ;
          }else{
              $data_title  = "";
          }


          if($module=="Accounts" || $module=="Leads"){

            if($data_title[0]['id']!=""){
              $tag = $this->Get_Tag($data_title[0]['id']);
              $data_title[0]['tag_list'] = $tag;
              
            }else{
              $data_title[0]['tag_list'] = array();
            }

          }

        }
      }

      if($uitype=="27" && $columnname=="filelocationtype" ){
        // $value = 'External';
        $is_hidden = "true";
        $readonly = '1';
      }else {
        $is_hidden = "false";
      }

      if($uitype=="28" && $columnname=="filename" ){
        // $value = 'External';
        $keyboard_type = "url";
      }
      if($uitype == "4"){ // uitype_ no
        $readonly = '1';
      }

      if($uitype=="15" && $columnname=="eventstatus" && ($crm_id == '' || $action =='duplicate')){
        $value = 'Plan';
      }

      // if($uitype=="914"  && $crm_id == ''){
      // }
      if($module=='Calendar' && $action=="edit"){

        $sql_activity = "select activityid,flag_send_report from aicrm_activity
        where aicrm_activity.activityid='".$crm_id."' ";
        $query_activity = $this->ci->db->query($sql_activity);
        $response_activity= $query_activity->row_array();
        
          if(($columnname =='cf_25708' || $columnname =='date_start' || $columnname =='time_start' || $columnname =='time_end' || $columnname =='dealid' || $columnname =='parentid' || $columnname =='contactid' || $columnname =='phone' || $columnname =='email') && $response_activity['flag_send_report']==1 ) {
            $readonly = '1';
          }else{
            $readonly = '0';
          }
      }

      if($module=="Leads" && $columnname =='lead_status'){

        if($action =='edit' || $action =='view'){
          $readonly = '1';
        }else {
          $leadstatus = $this->check_leadstatus($action,$crm_id);

          $sql_laststatus = "select * from tbt_step_convert where laststep='yes' ";
          $query_laststatus = $this->ci->db->query($sql_laststatus);
          $response_laststatus = $query_laststatus->result_array();
          $last = $response_laststatus[0]['field_source_value'];
          // alert($last);
          // alert($leadstatus);exit;
          if($last==$leadstatus){

            $data_pick=array($leadstatus);
            // alert($data_pick);exit;
          }

          $value = $leadstatus;
          $readonly = '1';
          // $
          // alert($leadstatus);exit;
        }

      }

      if($module == 'Calendar' ){
        if($columnname=="scoring" || $columnname=="scoringresult"){
          $readonly="1";
        }
      }

      if($uitype=="70" && $crm_id != ''){
        $readonly="1";
      }

      if($uitype=="106" && $module == 'Users'){
        $readonly="1";
      }

      if($uitype=="156" && $module == 'Users'){
        $readonly="1";
      }

      if($uitype=="914" ){
        //$data_pick=["Job","Case","Project Order"];
        $data_pick=["Project Order","Deal","Case"];
      }

      if($uitype=="201" ){
        $data_pick=["Leads","Accounts"];
      }

      if($uitype=="15" && $columnname=="priority" && $crm_id == ''){
        $value = 'Medium';
      }

      if($uitype=="946" ){
        // alert($templateid);exit;
        list($value,$value_name)= $this->Get_Value_template($module,$templateid,$crm_id);
        $value_name = $value_name['questionnairetemplate_name'];
        $readonly="1";          
      }

      if($uitype=="910" ){
        list($value,$value_name)= $this->Get_Value_template($module,$templateid,$crm_id);
        $value_name = $value_name['questionnairetemplate_name'];
        $readonly="1";          
      }

      if($value == null){
        $value= "";
      }

      if($uitype=="903"){
        if($crm_id==""){
          $value_name = "--None--";
        }
      }

      if($key_field_up==""||$key_field_down==""){
        $key_field_up=array();
        $key_field_down=array();
      }

      if($value_name == null){
        $value_name = "";
      }
      if($no==null) {
        $no="";
      }
      if($name==null) {
        $name="";
      }

      if($uitype=="28" && $value!=""){
        $sql_path = "select DISTINCT aicrm_notes.filename,aicrm_notes.notesid,aicrm_attachments.path,aicrm_notes.filelocationtype
        from aicrm_notes
        inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_notes.notesid
        left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid = aicrm_crmentity.crmid
        left join aicrm_attachments on aicrm_attachments.attachmentsid = aicrm_seattachmentsrel.attachmentsid
        where aicrm_notes.notesid='".$crm_id."' and aicrm_notes.filename='".$value."' ";
        $query = $this->ci->db->query($sql_path);
        $data = $query->result_array();
        $path = $data[0]['path'];
        $filename = $data[0]['filename'];
        $filelocationtype = @$data[0]['filelocationtype'];

        if($filelocationtype=="e" ||$filelocationtype=="E"){
          $value = $filename;
        }else {
          $urlfile = $this->ci->config->item("url_new");
          $url = $urlfile.$path.$filename;
          $value= $url;
        }
      }

      if($uitype == "948"){
        $key_ref_uitype = "949";
      }

      if ($crm_subid != "" && $related_module != "") {
        if ($columnname == "email1" || $columnname == "email2" || $columnname == "mobile2" || $columnname == "phone" || $columnname == "email" || $columnname == "gender" || $columnname == "business_type" || $columnname == "firstname" || $columnname == "lastname" || $columnname == "idcardno" || $columnname == "birthdate" || $columnname == "mobile") {
          $value_data = $this->Get_DefaultValue_relateid($related_module, $columnname, $tablename, $crm_subid, $uitype);
          $value_name = $value_data;
          $value = $value_data;
        }

        $entityData = $this->getCrmEntityData($crm_subid);
        if($uitype == '201'){
          if($entityData['moduleSelect'] == 'Deal'){
            switch($entityData['rsData']['module']){
              case 'Accounts':
              $module_select = $entityData['rsData']['module'];
              $no = $entityData['rsData']['account_no'];
              $name = $entityData['rsData']['accountname'];
              $value_name = $entityData['rsData']['accountname'];
              $value = $entityData['rsData']['accountid'];
              break;
              case 'Contacts':
              $module_select = $entityData['rsData']['module'];
              $no = $entityData['rsData']['contact_no'];
              $name = $entityData['rsData']['contactname'];
              $value_name = $entityData['rsData']['contactname'];
              $value = $entityData['rsData']['contactid'];
              break;
              case 'Leads':
              $module_select = $entityData['rsData']['module'];
              $no = $entityData['rsData']['lead_no'];
              $name = $entityData['rsData']['leadname'];
              $value_name = $entityData['rsData']['leadname'];
              $value = $entityData['rsData']['leadid'];
              break;
            }
              // $no = $data_related['no'];
              // $name = $data_related['name'];
              // $value_name = $data_related['name'];
              // $value = $data_related['id'];
          }
        }else{
          $relateData = $this->get_value_relatedid($entityData['rsData']['id'],$entityData['rsData']['module'],$columnname,$columnname);
          if($entityData['rsData']['module'] == 'Accounts'){
            switch ($columnname) {
              case 'phone':
                $value = $relateData[0]['phone'];
                break;
              case 'email':
                $value = $relateData[0]['email'];
                break;
            }
          }
        }
      }

      if($uitype=="932" || $uitype=="940"){
        // echo $value;
        if($value != ''){
          $userInfo = $this->Get_User_Info($value);
          $value = $userInfo['name'];
        }      
      }

      /*Fig Code Bas 2022-03-22*/
      $ref_concat_value = '';
      if($columnname=='leadname' && $module =='Leads'){
        $ref_concat_value = 'firstname:lastname:leadname';
      }else if($columnname=='accountname' && $module =='Accounts'){
        $ref_concat_value = 'firstname:lastname:accountname';
      // }else if($columnname=='account_name_th' && $module =='Accounts'){
      //   $ref_concat_value = 'firstname:lastname:account_name_th';
      }else if($columnname=='account_name_en' && $module =='Accounts'){
        $ref_concat_value = 'f_name_en:l_name_en:account_name_en';
      }else if($columnname=='contactname' && $module =='Contacts'){
        $ref_concat_value = 'firstname:lastname:contactname';
      }
      /*Fig Code Bas 2022-03-22*/

      if($uitype == '33'){
        if(is_array($value)){
          $value = implode(', ', $value);
        }else{
          $value = str_replace(' |##| ', ', ', $value);
        }
      }

      if($module=="Events"){
        
        $data_f=array(
          'columnname' => $columnname,
          'tablename' => $tablename,
          'fieldlabel' => $fieldlabel,
          'uitype' => $uitype,
          'typeofdata' => $typeofdata,
          'type' => $type_1,
          'keyboard_type' => $keyboard_type,
          'value_default' => $data_pick,
          'value_name' => $value_name,
          'value' => $value,
          'check_value' => $check_value,
          'error_message'=>$error_message,
          'readonly'=>$readonly,
          'maximumlength'=>$maximumlength,
          'minimumlenght'=>$minimumlenght,
          'invalid_message'=>$invalid_message,
          'format_date'=>$format_date,
          'is_array' => "false",
          'is_phone' => $is_phone,
          'is_account' => $is_account,
          'is_product' => "false",
          'is_checkin' => $is_checkin,
          'is_hidden' => $is_hidden,
          'module_select'=>$module_select,
          'link'=>$link_status,
          'no'=>$no,
          'name'=>$name,
          'key_valuename_select'=>$key_valuename,
          'relate_field_up'=>$key_field_up,
          'relate_field_down'=>$key_field_down,
          'ref_uitype'=> $key_ref_uitype,
          'ref_concat_value'=> @$ref_concat_value,
        );

      }else if($module=="Users"){

        $data_f=array(
          'columnname' => $columnname,
          'tablename' => $tablename,
          'fieldlabel' => $fieldlabel,
          'uitype' => $uitype,
          'typeofdata' => $typeofdata,
          'type' => $type_1,
          'keyboard_type' => $keyboard_type,
          'value_default' => $data_pick,
          'module_select'=>$module_select,
          'value_name' => $value_name,
          'value' => $value,
          'check_value' => $check_value,
          'error_message'=>$error_message,
          'readonly'=>$readonly,
          'maximumlength'=>$maximumlength,
          'minimumlenght'=>$minimumlenght,
          'invalid_message'=>$invalid_message,
          'format_date'=>$format_date,
          'is_array' => "false",
          'is_phone' => $is_phone,
          'is_account' => $is_account,
          'is_product' => "false",
          'is_checkin' => $is_checkin,
          'is_hidden' => $is_hidden,
          'module_select'=>$module_select,
          'link'=>$link_status,
          'no'=>$no,
          'name'=>$name,
          'ref_concat_value'=> @$ref_concat_value,
        );

      }else{

        $data_f=array(
          'columnname' => $columnname,
          'tablename' => $tablename,
          'fieldlabel' => $fieldlabel,
          'uitype' => $uitype,
          'typeofdata' => $typeofdata,
          'type' => $type_1,
          'keyboard_type' => $keyboard_type,
          'value_default' => $data_pick,
          'module_select'=>$module_select,
          'value_name' => $value_name,
          'value' => $value,
          'check_value' => $check_value,
          'error_message'=>$error_message,
          'readonly'=>$readonly,
          'maximumlength'=>$maximumlength,
          'minimumlenght'=>$minimumlenght,
          'invalid_message'=>$invalid_message,
          'format_date'=>$format_date,
          'is_array' => "false",
          'is_phone' => $is_phone,
          'is_account' => $is_account,
          'is_product' => "false",
          'is_checkin' => $is_checkin,
          'is_hidden' => $is_hidden,
          'module_select'=>$module_select,
          'link'=>$link_status,
          'no'=>$no,
          'name'=>$name,
          'key_valuename_select'=>$key_valuename,
          'relate_field_up'=>$key_field_up,
          'relate_field_down'=>$key_field_down,
          'ref_uitype'=> $key_ref_uitype,
          'ref_concat_value'=> @$ref_concat_value,
        );

      }

      $data_fs[]=$data_f;
      // alert($data_f);

    }
    // exit;
    //Get Block Name=========================================


    $headder = array();
    $lang_item = array();
    // $language = $this->ci->config->item("lang");
    // $this->_lang = $language[$user_lang];
    // $lang_item = $this->ci->lang->load('ai',$this->_lang);
    $language = $this->ci->config->item("lang");
    $this->_lang = $language[$user_lang];
    $this->ci->lang->load('ai_lang',$this->_lang);
    
    $headder = $this->ci->lang->line($blocklabel);

    if(!empty($headder)){
        $block_name = $headder;
    }else{
        $block_name=$blocklabel;
    }

    $data_block1=array(
      'header_name'=>$block_name,
      'form'=>$data_fs
    );

      return array($data_title,$data_block1);

  }

public function Get_DefaultValue_relateid($module, $columnname, $table_name, $crm_id, $uitype)
{


  $list_query = $this->Get_Query($module);



  switch ($module) {
    case "Leads":
      $table_name = "aicrm_leaddetails";
      if ($columnname == "phone") {
        $columnname = "mobile";
      } elseif ($columnname == "business_type") {
        $columnname = "industry";
      }
      break;
    case "Accounts":
      $table_name = "aicrm_account";
      if ($columnname == "phone") {
        $columnname = "mobile";
      } elseif ($columnname == "business_type") {
        $columnname = "accountindustry";
      } elseif ($columnname == "email") {
        $columnname = "email1";
      }
      break;
  }
  $sql = " select " . $table_name . "." . $columnname;
  $sql .= $list_query . " and aicrm_crmentity.crmid ='" . $crm_id . "'; ";
  // alert($sql);

  $query = $this->ci->db->query($sql);

  if (!empty($query)) {
    $data = $query->result_array();
  } else {
    $data = "";
  }

  if (count($data) > 0) {
    return $data[0][$columnname];
  } else {
    return "";
  }
}

public function Get_Value($module,$columnname,$table_name,$crm_id,$uitype){


  $list_query=$this->Get_Query($module);

  if($module == "Users"){
    $sql=" select ".$table_name.".".$columnname;
    $sql.=$list_query." and aicrm_users.id ='".$crm_id."'; ";
  }else{
    $sql=" select ".$table_name.".".$columnname;
    $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
    // alert($sql);
  }

  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
  }else {
    $data = "";
  }
  
  if($uitype=="33"){

    $instrument = isset($data[0]['instrument']) ? $data[0]['instrument'] : "";
    $job_assay = isset($data[0]['job_assay'])  ? $data[0]['job_assay'] : "";

    if($instrument!="" || $job_assay!=""){
      $instrument = str_replace(" |##| ", ", ", $instrument);
      $job_assay = str_replace(" |##| ", ", ", $job_assay);

      $data[0]['instrument'] = isset($instrument)  ? $instrument : "";
      $data[0]['job_assay'] = isset($job_assay)  ? $job_assay : "";

    }
  }

  if(count($data)>0){
    return $data[0][$columnname];
  }else{
    return "";
  }
}

public  function Get_Tag($crmid=""){

    $tagList = array();

    $sql=" select aicrm_freetags.id as 'id' ,IFNULL(aicrm_freetags.tag , '') as 'name' , '#a0a0a0' as color
    from aicrm_freetags
    inner join aicrm_freetagged_objects on aicrm_freetagged_objects.tag_id = aicrm_freetags.id
    where aicrm_freetagged_objects.object_id ='".$crmid."' ";

    $query = $this->ci->db->query($sql);
    $tagList = $query->result_array();

    return $tagList;

  }

public function Get_Value_Profile($module,$columnname,$table_name,$crm_id,$uitype){
  $list_query=$this->Get_Query($module);
  $sql=" select ".$table_name.".".$columnname;
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
  $query = $this->ci->db->query($sql);
  
  if(!empty($query)){
    $data = $query->result_array();
  }else {
    $data = "";
  }

  if(count($data)>0){
    return $data[0][$columnname];
  }else{
    return "";
  }
}

public function Get_related_field($module,$columnname){

  $related_up = [];
  $related_down = [];
  $selected_columnname ="";

  $tabid = "SELECT tabid from aicrm_tab WHERE name='".$module."' ";
  $query_tabid = $this->ci->db->query($tabid);

  if(!empty($query_tabid)){
    $data = $query_tabid->result_array();
  }else {
    $data = "";
  }


  if(!empty($data)){
    $tabid = $data[0]['tabid'];
  }else {
    return "";
  }

  $list_query = "SELECT id as relatedlistid , columnname_related,selected_columnname,action_type from aicrm_relatedlists_field
  WHERE tabid='".$tabid."' and columnname='".$columnname."' and `status`='active'";
  //echo $list_query;
  $query = $this->ci->db->query($list_query);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data;
    $relatedlistid = $data[0]['relatedlistid'];
  }else {
    $data_a="";
    $data = "";
    $relatedlistid="";
  }

  if($relatedlistid!=""){

  }

  foreach ($data_a as $key => $value) {

    $selected_columnname = $value['selected_columnname'];

    if($value['action_type'] == "Remove" ){
      $related_up[] = $value['columnname_related'];

    }elseif ($value['action_type'] == "Get" ){
      $related_down[] = $value['columnname_related'];
    }

  }
  
  if(count($data)>0){
    return array($related_up,$related_down,$selected_columnname);
  }else{
    return "";
  }

}

public function check_leadstatus($action,$crm_id=""){



  if($action=="add" || $action=="duplicate"){

    $sql_leadstatus = "select * from tbt_step_convert where sequen='1' ";
    $query_leadstatus = $this->ci->db->query($sql_leadstatus);
    $response_leadstatus = $query_leadstatus->result_array();
    if(!empty($response_leadstatus)){
      $data = $response_leadstatus[0]['field_source_value'];
    }


  }elseif($action=="leadupdate"){

    $sql_leadstatus = "SELECT tbt_step_convert.field_source_value ,tbt_step_convert.destination_value,tbt_step_convert.laststep,
    aicrm_leaddetails.flag_approve,aicrm_crmentity.smownerid from aicrm_leaddetails
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
    LEFT JOIN tbt_step_convert on tbt_step_convert.field_source_value = aicrm_leaddetails.lead_status
    WHERE  leadid ='".$crm_id."' ";
    $query_leadstatus = $this->ci->db->query($sql_leadstatus);
    $response_leadstatus = $query_leadstatus->result_array();
    if(!empty($response_leadstatus)){
      $data = $response_leadstatus[0]['destination_value'];
    }

  }else {
    $data="";
  }

   return $data;

}

public function Get_Value_Account($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){
  //echo $module; exit;
  $list_query = $this->Get_Query($module);
  $sql=" select ".$table_name.".".$columnname .", aicrm_account.accountname,aicrm_account.account_no";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
  
  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  } 
  //echo $module;exit;
  if($module=="Accounts" && $columnname=="parentid"){
    $crm_id = $data_a['parentid'];

    $list_query = $this->Get_Query($module);
    $sql=" select ".$table_name.".".$columnname .", aicrm_account.accountname,aicrm_account.account_no";
    $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";

    $query = $this->ci->db->query($sql);

    if(!empty($query)){
      $data = $query->result_array();
      $data_a = $data[0];
    }else {
      $data="";
      $data_a="";
    }


  }

  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_User_Info($userID)
{
  $this->ci->db->select("aicrm_users.*, CONCAT(aicrm_users.first_name,' ',aicrm_users.last_name) AS name", FALSE);
  $sql = $this->ci->db->get_where('aicrm_users', ['id'=>$userID]);
  $result = $sql->row_array();

  return $result;
}

public function Get_Value_Contact($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){
  $list_query = $this->Get_Query($module, $columnname);

  //$sql=" select ".$table_name.".".$columnname .", concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as contactname ,contact_no";
  $sql=" select ".$table_name.".".$columnname ;

  if(in_array($columnname, ['contact_id1', 'contact_id2', 'contact_id3', 'contact_id4' ,'contact_id5' ,'contact_id6' ,'contact_id7' ,'contact_id8' ,'contact_id9' ,'contact_id10' ,'contact_id' ])){
  
    $sql .= ", concat(tb_".$columnname.".firstname,' ',tb_".$columnname.".lastname) as contactname ,tb_".$columnname.".contact_no";
  
  }else{
    $sql .= ", concat(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) as contactname ,aicrm_contactdetails.contact_no";
  }
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
  
  //echo $sql; echo "<br>";
  
  $query = $this->ci->db->query($sql);
  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }

}

public function Get_Value_Calendar($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){
 
  $list_query = $this->Get_Query($module);

  $sql=" select ".$table_name.".".$columnname .", aicrm_activity.activitytype  ,aicrm_activity.eventstatus";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
 // alert($sql);exit;

  $query = $this->ci->db->query($sql);

  if(!empty($query)){
  $data = $query->result_array();
  $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Competitor($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){
 
  $list_query = $this->Get_Query($module);

  $sql=" select ".$table_name.".".$columnname .", aicrm_competitor.competitor_no  ,aicrm_competitor.competitor_name";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
  
  $query = $this->ci->db->query($sql);

  if(!empty($query)){
  $data = $query->result_array();
  $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Deal($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){
 
  $list_query = $this->Get_Query($module);

  $sql=" select ".$table_name.".".$columnname .", aicrm_deal.deal_no  ,aicrm_deal.deal_name";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
 //alert($sql);exit;

  $query = $this->ci->db->query($sql);

  if(!empty($query)){
  $data = $query->result_array();
  $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Questionnaire($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){
 
  $list_query = $this->Get_Query($module);

  $sql=" select ".$table_name.".".$columnname .", aicrm_questionnaire.questionnaire_no  ,aicrm_questionnaire.questionnaire_name";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
 //alert($sql);exit;

  $query = $this->ci->db->query($sql);

  if(!empty($query)){
  $data = $query->result_array();
  $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Projects($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){

  $list_query = $this->Get_Query($module);

  $sql=" select ".$table_name.".".$columnname .", aicrm_projects.projects_name ,aicrm_projects.projects_no";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
 
  $query = $this->ci->db->query($sql);
  
  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }



  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Event($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){
  
  if($module == 'Quotes'){
    $list_query = "
    FROM aicrm_quotes
    LEFT JOIN aicrm_quotescf ON aicrm_quotescf.quoteid = aicrm_quotes.quoteid
    LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
    LEFT OUTER JOIN aicrm_projects ON aicrm_projects.projectsid = aicrm_quotes.event_id
    LEFT OUTER JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_quotes.event_id
    LEFT OUTER JOIN aicrm_troubletickets ON aicrm_troubletickets.ticketid = aicrm_quotes.event_id
    LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_quotes.accountid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
  }else{
    $list_query = "
    FROM aicrm_activity
    LEFT JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
    LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
    LEFT OUTER JOIN aicrm_projects ON aicrm_projects.projectsid = aicrm_activity.event_id
    LEFT OUTER JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_activity.event_id
    LEFT OUTER JOIN aicrm_troubletickets ON aicrm_troubletickets.ticketid = aicrm_activity.event_id
    LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_activity.parentid
    LEFT OUTER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_activity.parentid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
  }
  
  $sql=" select ".$table_name.".".$columnname .", aicrm_jobs.job_name ,aicrm_jobs.job_no,aicrm_projects.projects_name,aicrm_projects.projects_no,aicrm_troubletickets.ticket_no,aicrm_troubletickets.title
    ,aicrm_account.account_no,
    aicrm_account.accountname";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
  //echo $sql; exit;
  $query = $this->ci->db->query($sql);
  
  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  $data_a['event_id'] = @$data[0]['event_id'];
  $data_a['parentid'] = @$data[0]['parentid'];

  if($data[0]['job_name']!=""){
    $data_a['name'] = $data[0]['job_name'];
    $data_a['no'] = $data[0]['job_no'];
    $data_a['module'] = "Job";
  }else if($data[0]['projects_name']!=""){
    $data_a['name'] = $data[0]['projects_name'];
    $data_a['no'] = $data[0]['projects_no'];
    $data_a['module'] = "Projects";
  }else if($data[0]['ticket_no']!=""){
    $data_a['name'] = $data[0]['ticket_no'];
    $data_a['no'] = $data[0]['title'];
    $data_a['module'] = "Case";
  }else if($data[0]['account_no']!=""){
    $data_a['name'] = $data[0]['accountname'];
    $data_a['no'] = $data[0]['account_no'];
    $data_a['module'] = "Accounts";
  }

  /*if($data[0]['lead_no']!=""){
    $data_a['name'] = $data[0]['firstname']." ".$data[0]['lastname'];
    $data_a['no'] = $data[0]['lead_no'];
    $data_a['module'] = "Leads";
  }*/


  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Parent($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){

  switch($module){
    case 'Deal':
      $list_query = "
      FROM aicrm_deal
      LEFT JOIN aicrm_dealcf ON aicrm_dealcf.dealid = aicrm_deal.dealid
      LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_deal.dealid
      LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_deal.parentid
      LEFT OUTER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_deal.parentid
      LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
      LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
      WHERE aicrm_crmentity.deleted = 0 ";
      break;
    case 'Quotes':
      $list_query = " 
      FROM aicrm_quotes
      LEFT JOIN aicrm_quotescf ON aicrm_quotescf.quoteid = aicrm_quotes.quoteid
      LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
      LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_quotes.parentid
      LEFT OUTER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_quotes.parentid
      LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
      LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
      WHERE aicrm_crmentity.deleted = 0 ";
      break;
  } 
  

  $sql=" select ".$table_name.".".$columnname .",aicrm_account.account_no,aicrm_account.accountname,aicrm_leaddetails.lead_no,aicrm_leaddetails.firstname,aicrm_leaddetails.lastname";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";

  $query = $this->ci->db->query($sql);
  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  $data_a['event_id'] = @$data[0]['event_id'];
  $data_a['parentid'] = @$data[0]['parentid'];

  if($data[0]['account_no']!=""){
    $data_a['name'] = $data[0]['accountname'];
    $data_a['no'] = $data[0]['account_no'];
    $data_a['module'] = "Accounts";
  }

  if($data[0]['lead_no']!=""){
    $data_a['name'] = $data[0]['firstname']." ".$data[0]['lastname'];
    $data_a['no'] = $data[0]['lead_no'];
    $data_a['module'] = "Leads";
  }


  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Parent_Calendar($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){

  $list_query = "
  FROM aicrm_activity
  LEFT JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
  LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
  LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_activity.parentid
  LEFT OUTER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_activity.parentid
  LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
  LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
  WHERE aicrm_crmentity.deleted = 0 ";

  $sql=" select ".$table_name.".".$columnname .",aicrm_account.account_no,aicrm_account.accountname,aicrm_leaddetails.lead_no,aicrm_leaddetails.firstname,aicrm_leaddetails.lastname";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
  $query = $this->ci->db->query($sql);
  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  $data_a['event_id'] = @$data[0]['event_id'];
  $data_a['parentid'] = @$data[0]['parentid'];

  if($data[0]['account_no']!=""){
    $data_a['name'] = $data[0]['accountname'];
    $data_a['no'] = $data[0]['account_no'];
    $data_a['module'] = "Accounts";
  }

  if($data[0]['lead_no']!=""){
    $data_a['name'] = $data[0]['firstname']." ".$data[0]['lastname'];
    $data_a['no'] = $data[0]['lead_no'];
    $data_a['module'] = "Leads";
  }


  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}
public function Get_Value_Product($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){

  $list_query = $this->Get_Query($module);

  $sql=" select ".$table_name.".".$columnname .",aicrm_products.productname , aicrm_products.product_no";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Signature($crm_id,$uitype,$crm_subid=""){

  if($crm_subid!=""){
    return "";
  }

  $sql = "select aicrm_attachments.path, aicrm_attachments.attachmentsid, aicrm_attachments.name , aicrm_crmentity.setype,aicrm_jobs.image_user,aicrm_jobs.image_customer
  from aicrm_jobs
  left join aicrm_seattachmentsrel on aicrm_seattachmentsrel.crmid=aicrm_jobs.jobid
  inner join aicrm_attachments on aicrm_attachments.attachmentsid=aicrm_seattachmentsrel.attachmentsid
  inner join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_attachments.attachmentsid
  where aicrm_crmentity.setype='Image ".$uitype."'
  and aicrm_jobs.jobid='".$crm_id."' ";

  $query = $this->ci->db->query($sql);
  
  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  if($data_a!=""){

    $path = $data_a['path'];

    $name = $data_a['name'];
    $value = $data_a['attachmentsid'];
    $id = $data_a['attachmentsid'];
    $urlfile = $this->ci->config->item("url_new");
    $value_url = $urlfile."".$path."".$id."_".$name."";

  }else{
    $value_url="";
    $value="";
  }

  if(count($data)>0){
    return array($value,$value_url);
  }else{
    return "";
  }
}

public function Get_Value_rolename($module,$columnname,$table_name,$crm_id,$uitype,$crm_subid){

  if($crm_subid!=""){
    return "";
  }

  $list_query = $this->Get_Query($module);


  $sql=" select ".$table_name.".".$columnname ." ,aicrm_role.rolename";
  $sql.=$list_query." and aicrm_users.id ='".$crm_id."'; ";
  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_template($module="Questionnaire",$templateid="",$crmid=""){

  if($crmid==""){
      $sql=" select questionnairetemplateid , questionnairetemplate_name
  from aicrm_questionnairetemplate 
  where questionnairetemplateid='".$templateid."' ";

  }else{
      $sql=" select aicrm_questionnairetemplate.questionnairetemplateid , aicrm_questionnairetemplate.questionnairetemplate_name
  from aicrm_questionnaire
  inner join aicrm_questionnairetemplate on aicrm_questionnairetemplate.questionnairetemplateid = aicrm_questionnaire.questionnairetemplateid
  where questionnaireid='".$crmid."' ";

  }

  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }
  if(count($data)>0){
    return array($data[0]['questionnairetemplateid'],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Serial($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){

  $list_query = $this->Get_Query($module);


  $sql=" select ".$table_name.".".$columnname .",aicrm_serial.serial_name,aicrm_serial.serial_no";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }
  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Campaign($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){

  /*if($crm_subid!=""){
    return "";
  }*/

  $list_query = $this->Get_Query($module);


  $sql=" select ".$table_name.".".$columnname .",aicrm_campaign.campaignname,aicrm_campaign.campaign_no";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";

  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Promotion($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){

  /*if($crm_subid!=""){
    return "";
  }*/

  $list_query = $this->Get_Query($module);


  $sql=" select ".$table_name.".".$columnname .",aicrm_promotion.promotion_name,aicrm_promotion.promotion_no";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";

  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}


public function Get_Value_Case($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){

  $list_query = $this->Get_Query($module);


  $sql=" select ".$table_name.".".$columnname .",aicrm_troubletickets.ticket_no,aicrm_troubletickets.title";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";

  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }
  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Job($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){

  $list_query = $this->Get_Query($module);

  $sql=" select ".$table_name.".".$columnname .",aicrm_jobs.job_name,aicrm_jobs.job_no";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
  // echo $sql; exit();
  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Errors($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){

  $list_query = $this->Get_Query($module);


  $sql=" select ".$table_name.".".$columnname .",aicrm_errors.errors_name,aicrm_errors.errors_no";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";

  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }
  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Sparepart($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$crm_subid="",$related_module=""){

  $list_query = $this->Get_Query($module);

  $sql=" select ".$table_name.".".$columnname .",aicrm_sparepart.sparepart_name,aicrm_sparepart.sparepart_no";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";


  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }
  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_Folder($module,$columnname,$table_name,$crm_id,$uitype,$crm_subid){

  if($crm_subid!=""){
    return "";
  }

  $list_query = $this->Get_Query($module);

  $sql=" select ".$table_name.".".$columnname .",aicrm_notes.note_no ,aicrm_notes.title,aicrm_attachmentsfolder.foldername ";
  $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_id."'; ";
  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];

  }else {
    $data="";
    $data_a="";
  }

  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function Get_Value_assign($module="",$columnname="",$table_name="",$crm_id="",$uitype="",$userid="")
{
  $data= "";

  if($crm_id==""){

    $sql  = "select  CONCAT(aicrm_users.first_name, ' ', aicrm_users.last_name,' [',aicrm_users.user_name,']') as user_assign,aicrm_users.id as userid
    FROM aicrm_users
    WHERE aicrm_users.deleted = 0
    and aicrm_users.id ='".$userid."'; ";

    $query = $this->ci->db->query($sql);
    $data = $query->result_array();
    }elseif($uitype=="903") {
    $list_query = $this->Get_Query($module);

    $sql= "  select ".$table_name.".".$columnname ." as userid , ".$table_name.".".$columnname ." as user_assign
    ".$list_query." and aicrm_crmentity.crmid ='".$crm_id."'  ";

    $query = $this->ci->db->query($sql);

    if(!empty($query)){
      $data = $query->result_array();
    }else {
      $data="";
    }

  }else {

    $sql  = " select  CONCAT(aicrm_users.first_name, ' ', aicrm_users.last_name,' [',aicrm_users.user_name,']') as user_assign,aicrm_crmentity.smownerid as userid
    FROM aicrm_users
    inner join aicrm_crmentity on aicrm_users.id = aicrm_crmentity.smownerid
    WHERE aicrm_users.deleted = 0
    and aicrm_crmentity.crmid ='".$crm_id."'; ";

    $query = $this->ci->db->query($sql);

    if(!empty($query)){
      $data = $query->result_array();
    }else {
      $data="";
    }

    if(empty($data)){
      $sql  = " select  aicrm_groups.groupname as user_assign,aicrm_groups.groupid as userid
      FROM aicrm_groups
      inner join aicrm_crmentity on aicrm_groups.groupid = aicrm_crmentity.smownerid
      WHERE aicrm_crmentity.crmid ='".$crm_id."'; ";

      $query = $this->ci->db->query($sql);
      
      if(!empty($query)){
        $data = $query->result_array();
      }else {
        $data="";
      }

    }
  }

  if(count($data)>0){
    return array($data[0]['userid'],$data[0]['user_assign']);
  }else{
    return "";
  }
}

public function Get_Value_Currency($module,$columnname,$table_name,$crm_id,$uitype,$crm_subid){

  if($crm_subid!=""){
    return "";
  }

  $sql="select aicrm_users.currency_id,aicrm_currency_info.currency_name,aicrm_currency_info.currency_symbol
  from aicrm_users
  left join aicrm_currency_info on aicrm_currency_info.id = aicrm_users.currency_id
  where aicrm_users.id='".$crm_id."'";

  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data="";
    $data_a="";
  }

  if(count($data)>0){
    return array($data[0][$columnname],$data_a);
  }else{
    return "";
  }
}

public function getPickListUitype_expense($columnName, $module)
{
  $fieldName = '';
  if($columnName == 'approver'){
    $fieldName = 'approve_expense_level1';
  }else if($columnName == 'approver2'){
    $fieldName = 'approve_expense_level2';
  }else if($columnName == 'approver3'){
    $fieldName = 'approve_expense_level3';
  }else if($columnName == 'f_approver'){
    $fieldName = 'approve_expense_level4';
  }
  $query = "SELECT
      concat( first_name, ' ', last_name ) AS user_name 
    FROM aicrm_users AS a 
    WHERE a.deleted = 0 
      AND a.STATUS = 'Active' 
      AND a.".$fieldName." = '1' 
    ORDER BY a.user_name";
  $sql = $this->ci->db->query($query);
  $rs = $sql->result_array();

  $result = [];
  foreach($rs as $row){
    $result[] = $row['user_name'];
  }

  return $result;
}

public function getPickList_Alluser($columnName, $module)
{
  $query = "SELECT
      CASE WHEN first_name != '' THEN concat( first_name, ' ', last_name ) ELSE user_name END AS user_name 
    FROM aicrm_users AS a 
    WHERE a.deleted = 0 
      AND a.STATUS = 'Active'
    ORDER BY a.user_name";
  $sql = $this->ci->db->query($query);
  $rs = $sql->result_array();

  $result = [];
  foreach($rs as $row){
    $result[] = $row['user_name'];
  }

  return $result;
}

public function getPickListUitype33($columnName, $module)
{
  $query = "SELECT
      concat( first_name, ' ', last_name ) AS user_name 
    FROM aicrm_users AS a 
    WHERE a.deleted = 0 
      AND a.STATUS = 'Active' 
      AND a.".$columnName." = '1' 
    ORDER BY a.user_name";
  $sql = $this->ci->db->query($query);
  $rs = $sql->result_array();

  $result = [];
  foreach($rs as $row){
    $result[] = $row['user_name'];
  }

  return $result;
}

public function getPickListUitype_samplere($columnName, $module)
{
  $fieldName = '';
  if($columnName == 'approver'){
    $fieldName = 'approve_sample_level1';
  }else if($columnName == 'approver2'){
    $fieldName = 'approve_sample_level2';
  }else if($columnName == 'approver3'){
    $fieldName = 'approve_sample_level3';
  }else if($columnName == 'f_approver'){
    $fieldName = 'approve_sample_level4';
  }

  $query = "SELECT
      concat( first_name, ' ', last_name ) AS user_name 
    FROM aicrm_users AS a 
    WHERE a.deleted = 0 
      AND a.STATUS = 'Active' 
      AND a.".$fieldName." = '1' 
    ORDER BY a.user_name";

  $sql = $this->ci->db->query($query);
  $rs = $sql->result_array();

  $result = [];
  foreach($rs as $row){
    $result[] = $row['user_name'];
  }

  return $result;
}

public  function Get_PicklistValue($columnname,$userid){
  $user = $this->ci->common->get_role($userid);
  $roleid = $user[0]['roleid'];
  $sql="
  SELECT DISTINCT ".$columnname." as  ".$columnname."
  FROM aicrm_".$columnname."
  INNER JOIN aicrm_role2picklist ON aicrm_".$columnname.".picklist_valueid = aicrm_role2picklist.picklistvalueid ";
  $sql .=" AND roleid IN ( '".$roleid."' ) ";
  $sql .=" where (".$columnname."<>'') ";

  $sql .= " ORDER BY sortid ";


  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a=array();
  }else {
    $data=array();
    $data_a=array();
  }


  if($columnname=="cf_2559"){
    $data_a[]="--None--";
  }
  if(count($data)>0){
    for($i=0;$i<count($data);$i++){
      $data_a[]=$data[$i][$columnname];
    }

    return $data_a;
  }else{
    return "";
  }

}

public  function Get_Picklist_Buyer($columnname,$userid){
  $user = $this->ci->common->get_role($userid);
  $roleid = $user[0]['roleid'];

  $sql = "select * from aicrm_config_vendorbuyer where type='Buyer' and deleted = 0 and status = 'Active' and (show_in_module like '%Quotation%') ";
  $sql .= " ORDER BY name ";
  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a=array();
  }else {
    $data=array();
    $data_a=array();
  }

  if($columnname=="quotation_buyer"){
    $data_a[]="--None--";
  }

  if(count($data)>0){
    for($i=0;$i<count($data);$i++){
      $data_a[]=$data[$i]['name'];
    }
    
    return $data_a;
  }else{
    return "";
  }
}

public  function Get_userlang($userid){
  $lang="EN";

  $sql="
  SELECT user_language
  FROM aicrm_users
  WHERE id='".$userid."'";

  $query = $this->ci->db->query($sql);

  if(!empty($query)){
    $data = $query->result_array();
    $data_a = $data[0];
  }else {
    $data_a="";
  }


  if($data_a['user_language']!=""){
    $lang = $data_a['user_language'];
  }else {
    $lang="EN";
  }

  return $lang;

}

public function  get_userlist($userid,$uitype){
  $valuaDefault = array();
  if($userid!=""){
    $user = $this->ci->common->get_user2role($userid);

    $r_sql = "select section,is_admin from aicrm_users where id='".$userid."' ";
    $r_query = $this->ci->db->query($r_sql);
    $r_section  = $r_query->result_array() ;
    $section = $r_section[0]['section'];
    $is_admin = $r_section[0]['is_admin'];
    if($is_admin=="on"){
      $sql_user = "select id as id
      ,user_name
      , first_name , last_name
      , CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'name'
      , CONCAT(first_name, ' ', last_name ) as 'username'
      , IFNULL(area,'') as area
      , position as no
      , case when section = '--None--' then '' else section end as section
      from aicrm_users WHERE status='Active' and aicrm_users.id in (".$user.")  ";
    }else{

      if($uitype=="903"){
        $sql_user = "select id as id
        ,user_name
        , first_name , last_name
        , CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'name'
        , CONCAT(first_name, ' ', last_name ) as 'username'
        , IFNULL(area,'') as area
        , position as no
        , case when section = '--None--' then '' else section end as section
        from aicrm_users WHERE status='Active' ";

      }else{
        $sql_user = "select id as id
        ,user_name
        , first_name , last_name
        , CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'name'
        , IFNULL(area,'') as area
        , position as no
        , case when section = '--None--' then '' else section end as section
        from aicrm_users WHERE status='Active' and aicrm_users.id in (".$user.") and aicrm_users.section='".$section."'  ";

      }

    }
    $sql_user .=" ORDER BY first_name ASC ";
    $query_user = $this->ci->db->query($sql_user);
      // alert($sql_user);exit;

      if(!empty($query_user)){
        $result_user = $query_user->result_array() ;
      }else {
        $result_user="";
      }


    if($uitype=="903"){
      $default =  array('id' => "",'name'=> "--None--",'username'=> "");
      array_unshift($result_user,$default);
    }

    $valuaDefault[0]['type'] = "user";
    $valuaDefault[0]['type_value'] = $result_user;
    if($is_admin=="on" && $uitype!="903"){
      $sql_group = "select groupid as id,groupname as name from aicrm_groups ";
      $query_group = $this->ci->db->query($sql_group);
      $result_group  = $query_group->result_array() ;
      $group_data= $result_group;
    }elseif( $uitype!="903"){

      $check_user =  $this->ci->lib_user_permission->Get_user_privileges($userid);
      $check_user = $check_user['current_user_groups'];
      $groupid= implode( ", ", $check_user );

        $sql_group = "select aicrm_groups.groupid as id,aicrm_groups.groupname as name
        FROM aicrm_groups
        where aicrm_groups.groupid in (".$groupid.")";
        $query_group = $this->ci->db->query($sql_group);

        if(!empty($query_group)){
          $result_group  = $query_group->result_array() ;
          $group_data = $result_group;
        }else {
          $group_data = "";
        }


    }

    $valuaDefault[1]['type'] = "group";
    $valuaDefault[1]['type_value'] = $group_data;

  }else {
    $valuaDefault=[];
  }

  return  $valuaDefault;

}


public function get_value_relatedid($crm_subid,$related_module,$columnname,$key_valuename){

  if($columnname=="smownerid" || $columnname=="description"){
        $sql="";
  }else{
    $select = $this->Get_QuerySelect($related_module);
    $list_query = $this->Get_Query($related_module);

    $sql=" select ".$select ."  ";
    $sql.=$list_query." and aicrm_crmentity.crmid ='".$crm_subid."'; ";
  }

  $query = $this->ci->db->query($sql);
 
  if(empty($query)){
    return "";
  }else {
    $data = $query->result_array();
    foreach ($data[0] as $key => $value) {
      if($key==$key_valuename){
        $data_a = $data[0];
      }else {
      }
    }
  }


  if(count($data)>0){
    return array($data_a);
  }else{
    return "";
  }
}

public function Get_Field_Name($columnname,$tabid){
  if($columnname=="crmid"){
    return $columnname;
  }else if($columnname=="premiumid"){
    return "Premiunm ID";
  }else{
    $sql="select fieldlabel from aicrm_field where columnname='".$columnname."' and tabid='".$tabid."' ";
    $query = $this->ci->db->query($sql);

    if($columnname=="event_type"){
      return "Event Type";
    }else if($columnname=="event_name"){
      return "Event Name";
    }else if($columnname=="date_start"){
      return "Event starts Date";
    }else if($columnname=="time_start"){
      return "Event starts Time";
    }else if($columnname=="due_date"){
      return "Event ends Date";
    }else if($columnname=="time_end"){
      return "Event ends Time";
    }else if($columnname=="cf_1225"){
      return "Job Date";
    }else if($columnname=="cf_1355"){
      return "Job Time";
    }else if($columnname=="redemptionid"){
      return "Redemption ID";
    }else{
      if($query->num_rows()>0){
        $data = $query->result_array();
        return $data[0]['fieldlabel'];
      }else{
        return $columnname;
      }
    }
  }
}
public function Get_Query($module, $columnName=''){

  $tabid=$this->Get_TabID($module);

  switch($module){
    Case "Opportunity":
    $query = "
    FROM aicrm_opportunity
    INNER JOIN aicrm_opportunitycf ON aicrm_opportunitycf.opportunityid = aicrm_opportunity.opportunityid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_opportunity.opportunityid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_account on aicrm_account.accountid=aicrm_opportunity.accountid
    LEFT JOIN aicrm_products on aicrm_products.productid=aicrm_opportunity.product_id
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "ServiceRequest":
    $query = "
    FROM aicrm_servicerequests
    INNER JOIN aicrm_servicerequestscf ON aicrm_servicerequestscf.servicerequestid = aicrm_servicerequests.servicerequestid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_servicerequests.servicerequestid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_servicerequests.accountid
    LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_servicerequests.contactid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_vendor ON aicrm_vendor.vendorid = aicrm_servicerequestscf.cf_1069
    LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_servicerequestscf.cf_1059
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Job":
    $query = "
    FROM aicrm_jobs
    INNER JOIN aicrm_jobscf ON aicrm_jobscf.jobid = aicrm_jobs.jobid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_jobs.accountid
    LEFT JOIN aicrm_troubletickets ON aicrm_troubletickets.ticketid = aicrm_jobs.ticketid
    LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_jobs.product_id
    LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_jobs.contactid
    LEFT JOIN aicrm_picklistcolor ON aicrm_picklistcolor.picklist_value = aicrm_jobs.job_status
    LEFT JOIN aicrm_serial ON aicrm_serial.serialid = aicrm_jobs.serialid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Sparepart":
    $query = "
    FROM aicrm_sparepart
    INNER JOIN aicrm_sparepartcf ON aicrm_sparepartcf.sparepartid = aicrm_sparepart.sparepartid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_sparepart.sparepartid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0";
    break;
    case 'Service':
      $query = '
      FROM aicrm_service
      INNER JOIN aicrm_servicecf ON aicrm_servicecf.serviceid = aicrm_service.serviceid
      INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_service.serviceid
      LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
      LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid 
      WHERE
      aicrm_crmentity.deleted = 0';
      break;
    Case "Errors":
    $query = "
    FROM aicrm_errors
    INNER JOIN aicrm_errorscf ON aicrm_errorscf.errorsid = aicrm_errors.errorsid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_errors.errorsid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0";
    // LEFT JOIN aicrm_errorslist ON aicrm_errorslist.errorsid = aicrm_errors.errorsid
    break;
    Case "Sparepartlist":
    $query = "
    FROM aicrm_sparepartlist
    INNER JOIN aicrm_sparepartlistcf ON aicrm_sparepartlistcf.sparepartlistid = aicrm_sparepartlist.sparepartlistid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_sparepartlist.sparepartlistid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_sparepartlist.jobid
    LEFT JOIN aicrm_sparepart ON aicrm_sparepart.sparepartid = aicrm_sparepartlist.sparepartid
    WHERE aicrm_crmentity.deleted = 0";
    break;
    Case "Errorslist":
    $query = "
    FROM aicrm_errorslist
    INNER JOIN aicrm_errorslistcf ON aicrm_errorslistcf.errorslistid = aicrm_errorslist.errorslistid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_errorslist.errorslistid
    LEFT JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_errorslist.jobid
    LEFT JOIN aicrm_errors ON aicrm_errors.errorsid = aicrm_errorslist.errorsid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0";
    // INNER JOIN aicrm_errors ON aicrm_errors.errorsid = aicrm_errorslist.errorsid
    break;
    Case "Branch":
    $query = "
    FROM aicrm_branchs
    INNER JOIN aicrm_branchscf ON aicrm_branchscf.branchid = aicrm_branchs.branchid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_branchs.branchid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0";
    break;
    Case "Building":
    $query = "
    FROM aicrm_building
    INNER JOIN aicrm_buildingcf ON aicrm_buildingcf.buildingid = aicrm_building.buildingid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_building.buildingid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0";
    break;
    Case "Booking":
    $query = "
    FROM aicrm_booking
    INNER JOIN aicrm_bookingcf ON aicrm_bookingcf.bookingid = aicrm_booking.bookingid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_booking.bookingid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0";
    break;
    Case "Transfer":
    $query = "
    FROM aicrm_transfer
    INNER JOIN aicrm_transfercf ON aicrm_transfercf.transferid = aicrm_transfer.transferid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_transfer.transferid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0";
    break;
    Case "PersonalLoan":
    $query = "
    FROM aicrm_personalloans
    INNER JOIN aicrm_personalloanscf ON aicrm_personalloanscf.personalloanid = aicrm_personalloans.personalloanid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_personalloans.personalloanid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0";
    break;
    Case "Premium":
    $query = "
    FROM aicrm_premiums
    INNER JOIN aicrm_premiumscf ON aicrm_premiumscf.premiumid = aicrm_premiums.premiumid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_premiums.premiumid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Promotion":
    $query = "
    FROM aicrm_promotion
    INNER JOIN aicrm_promotioncf ON aicrm_promotioncf.promotionid = aicrm_promotion.promotionid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_promotion.promotionid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    left join aicrm_inventory_protab3 on aicrm_inventory_protab3.id=aicrm_promotion.promotionid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Coupon":
    $query = "
    FROM aicrm_coupons
    INNER JOIN aicrm_couponscf ON aicrm_couponscf.couponid = aicrm_coupons.couponid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_coupons.couponid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    left join aicrm_account on aicrm_account.accountid=aicrm_coupons.accountid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "PriceList":
    $query = "
    FROM aicrm_pricelists
    INNER JOIN aicrm_pricelistscf ON aicrm_pricelistscf.pricelistid = aicrm_pricelists.pricelistid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricelists.pricelistid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Redemption":
    $query = "
    FROM aicrm_redemption
    INNER JOIN aicrm_redemptioncf ON aicrm_redemptioncf.redemptionid = aicrm_redemption.redemptionid
    left join aicrm_account on aicrm_account.accountid=aicrm_redemption.accountid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_redemption.redemptionid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Point":
    $query = "
    FROM aicrm_point
    INNER JOIN aicrm_pointcf ON aicrm_pointcf.pointid = aicrm_point.pointid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_point.pointid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_point.accountid
    LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_point.campaignid
    left join aicrm_products on aicrm_products.productid=aicrm_point.product_id
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "InternalTraining":
    $query = "
    FROM aicrm_internaltrainings
    INNER JOIN aicrm_internaltrainingscf ON aicrm_internaltrainingscf.internaltrainingid = aicrm_internaltrainings.internaltrainingid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_internaltrainings.internaltrainingid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_internaltrainings.accountid
    LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_internaltrainings.contactid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Questionnaire":
    $query = "
    FROM aicrm_questionnaire
    INNER JOIN aicrm_questionnairecf ON aicrm_questionnairecf.questionnaireid = aicrm_questionnaire.questionnaireid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_questionnaire.questionnaireid
    
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_questionnaire.accountid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_activity ON aicrm_activity.activityid = aicrm_questionnaire.activityid
    
    LEFT JOIN (
      select * 
      from aicrm_picklistcolor 
      where aicrm_picklistcolor.tabid='".$tabid."'
      ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_questionnaire.questionnaire_status
    WHERE aicrm_crmentity.deleted = 0  ";
    break;
    Case "Application":
    $query = "
    FROM aicrm_applications
    INNER JOIN aicrm_applicationscf ON aicrm_applicationscf.applicationid = aicrm_applications.applicationid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_applications.applicationid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_applications.accountid
    LEFT JOIN aicrm_branchs ON aicrm_branchs.branchid = aicrm_applicationscf.cf_1059
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "EmailTarget":
    $query = "
    FROM aicrm_emailtargets
    INNER JOIN aicrm_emailtargetscf ON aicrm_emailtargetscf.emailtargetid = aicrm_emailtargets.emailtargetid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_emailtargets.emailtargetid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "EmailTargetList":
    $query = "
    FROM aicrm_emailtargetlists
    INNER JOIN aicrm_emailtargetlistscf ON aicrm_emailtargetlistscf.emailtargetlistid = aicrm_emailtargetlists.emailtargetlistid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_emailtargetlists.emailtargetlistid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "HelpDesk":
    $query = "
    FROM aicrm_troubletickets
    INNER JOIN aicrm_ticketcf ON aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_troubletickets.ticketid
    LEFT JOIN aicrm_ticketcomments ON aicrm_ticketcomments.ticketid = aicrm_troubletickets.ticketid
    LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_troubletickets.contactid
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_troubletickets.accountid
    LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_troubletickets.product_id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN (
      select * 
      from aicrm_picklistcolor 
      where aicrm_picklistcolor.tabid='".$tabid."'
      ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_troubletickets.case_status
    WHERE aicrm_crmentity.deleted = 0  ";

    // $query = "
    // FROM aicrm_troubletickets
    // INNER JOIN aicrm_ticketcf ON aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
    // INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_troubletickets.ticketid
    // LEFT JOIN aicrm_ticketcomments ON aicrm_ticketcomments.ticketid = aicrm_troubletickets.ticketid
    // LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_troubletickets.contactid
    // LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_troubletickets.accountid
    // LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    // LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    // LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_troubletickets.product_id
    // WHERE aicrm_crmentity.deleted = 0 ";
    // LEFT JOIN aicrm_jobs ON aicrm_jobs.ticketid = aicrm_troubletickets.ticketid
    break;
    Case "Accounts":
    $query = "
    FROM aicrm_account
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
    INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
    INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
    INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT JOIN (
      select * 
      from aicrm_picklistcolor 
      where aicrm_picklistcolor.tabid='".$tabid."'
      ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_account.accountstatus
    WHERE aicrm_crmentity.deleted = 0  ";

    //LEFT JOIN aicrm_account aicrm_account2 ON aicrm_account.parentid = aicrm_account2.accountid

    // LEFT JOIN aicrm_activitycf  ON aicrm_activitycf.accountid = aicrm_account.accountid
    // LEFT JOIN aicrm_activity  ON aicrm_activity.activityid = aicrm_activitycf.activityid
    // LEFT JOIN aicrm_troubletickets  ON aicrm_troubletickets.accountid = aicrm_account.accountid
    // LEFT JOIN aicrm_jobs  ON aicrm_jobs.accountid = aicrm_account.accountid
    // -- LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.accountid = aicrm_account.accountid
    // LEFT JOIN aicrm_opportunity  ON aicrm_opportunity.accountid = aicrm_account.accountid
    // LEFT JOIN aicrm_serial  ON aicrm_serial.accountid = aicrm_account.accountid
    // and aicrm_picklistcolor.tabid='".$tabid."'
    break;
    Case "Potentials":
    $query = "
    FROM aicrm_potential
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_potential.potentialid
    INNER JOIN aicrm_potentialscf ON aicrm_potentialscf.potentialid = aicrm_potential.potentialid
    LEFT JOIN aicrm_account ON aicrm_potential.related_to = aicrm_account.accountid
    LEFT JOIN aicrm_contactdetails ON aicrm_potential.related_to = aicrm_contactdetails.contactid
    LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_potential.campaignid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Leads":
    $query = "
    FROM aicrm_leaddetails
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
    LEFT JOIN aicrm_leadsubdetails ON aicrm_leadsubdetails.leadsubscriptionid = aicrm_leaddetails.leadid
    LEFT JOIN aicrm_leadaddress ON aicrm_leadaddress.leadaddressid = aicrm_leadsubdetails.leadsubscriptionid
    INNER JOIN aicrm_leadscf ON aicrm_leaddetails.leadid = aicrm_leadscf.leadid
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_leaddetails.accountid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT JOIN (
      select * 
      from aicrm_picklistcolor 
      where aicrm_picklistcolor.tabid='".$tabid."'
      ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_leaddetails.leadstatus
    WHERE aicrm_crmentity.deleted = 0 
    ";
    // LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_leaddetails.campaignid
    // LEFT JOIN aicrm_promotion ON aicrm_promotion.promotionid = aicrm_leaddetails.promotionid
    break;
    Case "Products":
    $query = "
    FROM aicrm_products
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_products.productid
    INNER JOIN aicrm_productcf ON aicrm_products.productid = aicrm_productcf.productid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    ";
    break;
    Case "Quotation":
    $query = "
    FROM aicrm_quotation
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotation.quotationid
    INNER JOIN aicrm_quotationcf ON aicrm_quotation.quotationid = aicrm_quotationcf.quotationid
    INNER JOIN aicrm_branchscf ON aicrm_branchscf.branchid = aicrm_branchs.branchid
    left join aicrm_branchs on aicrm_branchs.branchid=aicrm_quotationcf.cf_1059
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0
    ";

    break;
    Case "Projects":
    $query = "
    FROM aicrm_projects
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_projects.projectsid
    INNER JOIN aicrm_projectscf ON aicrm_projects.projectsid = aicrm_projectscf.projectsid
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_projects.accountid
    LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_projects.contactid
    LEFT JOIN aicrm_deal ON aicrm_deal.dealid = aicrm_projects.dealid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN (
      select * 
      from aicrm_picklistcolor 
      where aicrm_picklistcolor.tabid='".$tabid."'
      ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_projects.projectorder_status
    WHERE aicrm_crmentity.deleted = 0
    AND aicrm_projects.projectorder_status != 'Job Close : JC' ";

    break;
    Case "Project":
    $query = "
    FROM aicrm_project
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_project.projectid
    INNER JOIN aicrm_projectcf ON aicrm_project.projectid = aicrm_projectcf.projectid
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_projects.accountid
    LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_projects.contactid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0
    ";

    break;
    Case "Projectorder":
    $query = "
    FROM aicrm_projectorder
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_projectorder.projectorderid
    INNER JOIN aicrm_projectordercf ON aicrm_projectordercf.projectorderid = aicrm_projectorder.projectorderid
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_projectorder.accountid
    LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_projectorder.contactid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0
    ";
    break;
    Case "Documents":
    $query = "
    FROM aicrm_notes
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_notes.notesid
    LEFT JOIN aicrm_notescf ON aicrm_notescf.notesid = aicrm_notes.notesid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_attachmentsfolder ON aicrm_notes.folderid = aicrm_attachmentsfolder.folderid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Contacts":
    $query = "
    FROM aicrm_contactdetails
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid
    LEFT JOIN aicrm_contactaddress ON aicrm_contactaddress.contactaddressid = aicrm_contactdetails.contactid
    LEFT JOIN aicrm_contactsubdetails ON aicrm_contactsubdetails.contactsubscriptionid = aicrm_contactdetails.contactid
    INNER JOIN aicrm_contactscf ON aicrm_contactscf.contactid = aicrm_contactdetails.contactid
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_contactdetails.accountid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0
    ";
    // LEFT JOIN aicrm_troubletickets  ON aicrm_troubletickets.contactid = aicrm_contactdetails.contactid
    // LEFT JOIN aicrm_activitycf  ON aicrm_activitycf.contactid = aicrm_contactdetails.contactid
    // LEFT JOIN aicrm_activity  ON aicrm_activity.activityid = aicrm_activitycf.activityid
    break;
    Case "Calendar":
    $query="
    FROM aicrm_activity
    LEFT JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
    LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_activity.parentid
    LEFT OUTER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_activity.parentid
    LEFT OUTER JOIN aicrm_competitor ON aicrm_competitor.competitorid = aicrm_activity.competitorid
    LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_activity.contactid
    LEFT JOIN aicrm_cntactivityrel ON aicrm_cntactivityrel.activityid = aicrm_activity.activityid
    LEFT JOIN aicrm_seactivityrel ON aicrm_seactivityrel.activityid = aicrm_activity.activityid
    LEFT OUTER JOIN aicrm_activity_reminder ON aicrm_activity_reminder.activity_id = aicrm_activity.activityid
    LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_deal ON aicrm_deal.dealid = aicrm_activity.parentid
    LEFT JOIN (
      select * 
      from aicrm_picklistcolor 
      where aicrm_picklistcolor.tabid='".$tabid."'
      ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_activity.eventstatus
    WHERE aicrm_crmentity.deleted = 0 ";

    // LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_activity.contactid
        // LEFT JOIN aicrm_questionnaire ON aicrm_questionnaire.questionnaireid = aicrm_activity.questionnaireid
    break;
    Case "Emails":
    $query = "
    FROM aicrm_activity
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_seactivityrel ON aicrm_seactivityrel.activityid = aicrm_activity.activityid
    LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_seactivityrel.crmid
    LEFT JOIN aicrm_cntactivityrel ON aicrm_cntactivityrel.activityid = aicrm_activity.activityid
    AND aicrm_cntactivityrel.contactid = aicrm_cntactivityrel.contactid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_salesmanactivityrel ON aicrm_salesmanactivityrel.activityid = aicrm_activity.activityid
    LEFT JOIN aicrm_emaildetails ON aicrm_emaildetails.emailid = aicrm_activity.activityid
    WHERE aicrm_activity.activitytype = 'Emails'
    AND aicrm_crmentity.deleted = 0 ";
    break;
    Case "Faq":
    $query = "
    FROM aicrm_faq
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_faq.faqid
    INNER JOIN aicrm_faqcf ON aicrm_faqcf.faqid = aicrm_faq.faqid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT JOIN (
      select * 
      from aicrm_picklistcolor 
      where aicrm_picklistcolor.tabid='".$tabid."'
      ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_faq.faqstatus
    WHERE aicrm_crmentity.deleted = 0";
    break;
    Case "Voucher":
    $query = "
    FROM aicrm_voucher
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_voucher.voucherid
    INNER JOIN aicrm_vouchercf ON aicrm_voucher.voucherid = aicrm_vouchercf.voucherid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN (
      select * 
      from aicrm_picklistcolor 
      where aicrm_picklistcolor.tabid='".$tabid."'
      ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_voucher.voucher_status
    WHERE aicrm_crmentity.deleted = 0  ";
    break;
    Case "Vendors":
    $query = "
    FROM aicrm_vendor
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_vendor.vendorid
    INNER JOIN aicrm_vendorcf ON aicrm_vendor.vendorid = aicrm_vendorcf.vendorid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Events":
    $query = "
    FROM aicrm_activity
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
    INNER JOIN aicrm_activitycf ON aicrm_activitycf.activityid = aicrm_activity.activityid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_activity.parentid
    LEFT OUTER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_activity.parentid
    LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_activity.contactid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "PriceBooks":
    $query = "
    FROM aicrm_pricebook
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_pricebook.pricebookid
    INNER JOIN aicrm_pricebookcf ON aicrm_pricebook.pricebookid = aicrm_pricebookcf.pricebookid
    LEFT JOIN aicrm_currency_info ON aicrm_pricebook.currency_id = aicrm_currency_info.id
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Quotes":
    $query = "
    FROM aicrm_quotes
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
    INNER JOIN aicrm_quotesbillads ON aicrm_quotes.quoteid = aicrm_quotesbillads.quotebilladdressid
    INNER JOIN aicrm_quotesshipads ON aicrm_quotes.quoteid = aicrm_quotesshipads.quoteshipaddressid
    LEFT JOIN aicrm_quotescf ON aicrm_quotes.quoteid = aicrm_quotescf.quoteid
    LEFT JOIN aicrm_currency_info ON aicrm_quotes.currency_id = aicrm_currency_info.id
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_quotes.accountid 
    LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_quotes.contactid ";
    
    if($columnName != ''){
      if(in_array($columnName, ['contact_id1', 'contact_id2', 'contact_id3', 'contact_id4' ,'contact_id5' ,'contact_id6' ,'contact_id7' ,'contact_id8' ,'contact_id9' ,'contact_id10' ,'contact_id' ])){
        $query .= " LEFT JOIN aicrm_contactdetails as tb_".$columnName." ON tb_".$columnName.".contactid = aicrm_quotes.".$columnName." ";
      }
    }

    $query .= "
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "PurchaseOrder":
    $query = "
    FROM aicrm_purchaseorder
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_purchaseorder.purchaseorderid
    LEFT OUTER JOIN aicrm_vendor ON aicrm_purchaseorder.vendorid = aicrm_vendor.vendorid
    LEFT JOIN aicrm_contactdetails ON aicrm_purchaseorder.contactid = aicrm_contactdetails.contactid
    INNER JOIN aicrm_pobillads ON aicrm_purchaseorder.purchaseorderid = aicrm_pobillads.pobilladdressid
    INNER JOIN aicrm_poshipads ON aicrm_purchaseorder.purchaseorderid = aicrm_poshipads.poshipaddressid
    LEFT JOIN aicrm_purchaseordercf ON aicrm_purchaseordercf.purchaseorderid = aicrm_purchaseorder.purchaseorderid
    LEFT JOIN aicrm_currency_info ON aicrm_purchaseorder.currency_id = aicrm_currency_info.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "SalesOrder":
    $query = "
    FROM aicrm_salesorder
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_salesorder.salesorderid
    INNER JOIN aicrm_sobillads ON aicrm_salesorder.salesorderid = aicrm_sobillads.sobilladdressid
    INNER JOIN aicrm_soshipads ON aicrm_salesorder.salesorderid = aicrm_soshipads.soshipaddressid
    LEFT JOIN aicrm_salesordercf ON aicrm_salesordercf.salesorderid = aicrm_salesorder.salesorderid
    LEFT JOIN aicrm_currency_info ON aicrm_salesorder.currency_id = aicrm_currency_info.id
    LEFT OUTER JOIN aicrm_quotes ON aicrm_quotes.quoteid = aicrm_salesorder.quoteid
    LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_salesorder.accountid
    LEFT JOIN aicrm_contactdetails ON aicrm_salesorder.contactid = aicrm_contactdetails.contactid
    LEFT JOIN aicrm_potential ON aicrm_potential.potentialid = aicrm_salesorder.potentialid
    LEFT JOIN aicrm_invoice_recurring_info ON aicrm_invoice_recurring_info.salesorderid = aicrm_salesorder.salesorderid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Invoice":
    $query = "
    FROM aicrm_invoice
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_invoice.invoiceid
    INNER JOIN aicrm_invoicebillads ON aicrm_invoice.invoiceid = aicrm_invoicebillads.invoicebilladdressid
    INNER JOIN aicrm_invoiceshipads ON aicrm_invoice.invoiceid = aicrm_invoiceshipads.invoiceshipaddressid
    LEFT JOIN aicrm_currency_info ON aicrm_invoice.currency_id = aicrm_currency_info.id
    LEFT OUTER JOIN aicrm_salesorder ON aicrm_salesorder.salesorderid = aicrm_invoice.salesorderid
    LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_invoice.accountid
    LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_invoice.contactid
    INNER JOIN aicrm_invoicecf ON aicrm_invoice.invoiceid = aicrm_invoicecf.invoiceid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Campaigns":

    $query = "
    FROM aicrm_campaign
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_campaign.campaignid
    INNER JOIN aicrm_campaignscf ON aicrm_campaign.campaignid = aicrm_campaignscf.campaignid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT JOIN (
      select * 
      from aicrm_picklistcolor 
      where aicrm_picklistcolor.tabid='".$tabid."'
      ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_campaign.campaign_status
    WHERE aicrm_crmentity.deleted = 0  ";
    break;
    Case "Serial":
    $query = "
    FROM aicrm_serial
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_serial.serialid
    INNER JOIN aicrm_serialcf ON aicrm_serialcf.serialid = aicrm_serial.serialid
    LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_serial.accountid
    LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_serial.product_id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0";
    // LEFT JOIN aicrm_jobs ON aicrm_jobs.serialid = aicrm_serial.serialid
    break;
    Case "Users":
    $query = "
    from aicrm_users
    inner join aicrm_user2role on aicrm_user2role.userid=aicrm_users.id
    left join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid
    left JOIN aicrm_users2group on  aicrm_users2group.userid = aicrm_users.id
    left JOIN aicrm_groups on  aicrm_groups.groupid = aicrm_users2group.groupid
    where deleted=0 ";
    break;
    Case "Dealer":
    $query = "
    FROM aicrm_dealers
    INNER JOIN aicrm_dealerscf ON aicrm_dealerscf.dealerid = aicrm_dealers.dealerid
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_dealers.dealerid
    LEFT JOIN aicrm_users ON aicrm_crmentity.smownerid = aicrm_users.id
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Order":
    $query = "
    FROM aicrm_order
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_order.orderid
    INNER JOIN aicrm_ordercf ON aicrm_ordercf.orderid = aicrm_order.orderid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    WHERE aicrm_crmentity.deleted = 0";
    break;
    Case "Deal":
    
    $query = "
    FROM aicrm_deal
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_deal.dealid
    INNER JOIN aicrm_dealcf ON aicrm_dealcf.dealid = aicrm_deal.dealid
    LEFT OUTER JOIN aicrm_account ON aicrm_account.accountid = aicrm_deal.parentid
    LEFT OUTER JOIN aicrm_leaddetails ON aicrm_leaddetails.leadid = aicrm_deal.parentid";

    if($columnName != ''){
      if(in_array($columnName, ['contact_id1', 'contact_id2', 'contact_id3', 'contact_id4'])){
        $query .= " LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_deal.".$columnName." ";
      }
    }

    $query .= " LEFT JOIN aicrm_campaign ON aicrm_campaign.campaignid = aicrm_deal.campaignid
    LEFT JOIN aicrm_promotion ON aicrm_promotion.promotionid = aicrm_deal.promotionid
    -- LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_deal.product_id
    LEFT JOIN aicrm_competitor ON aicrm_competitor.competitorid = aicrm_deal.competitorid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT JOIN (
      select * 
      from aicrm_picklistcolor 
      where aicrm_picklistcolor.tabid='".$tabid."'
      ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_deal.stage
    WHERE aicrm_crmentity.deleted = 0 ";
  // LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_deal.accountid
    break;
    Case "Announcement":
    $query = "
    FROM aicrm_announcement
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_announcement.announcementid
    INNER JOIN aicrm_announcementcf ON aicrm_announcementcf.announcementid = aicrm_announcement.announcementid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT JOIN (
      select * 
      from aicrm_picklistcolor 
      where aicrm_picklistcolor.tabid='".$tabid."'
      ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_announcement.status
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Competitor":
    $query = "
    FROM aicrm_competitor
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_competitor.competitorid
    INNER JOIN aicrm_competitorcf ON aicrm_competitorcf.competitorid = aicrm_competitor.competitorid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT JOIN (
      select * 
      from aicrm_picklistcolor 
      where aicrm_picklistcolor.tabid='".$tabid."'
      ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_competitor.competitor_status
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Marketingtools":
    $query = "
    FROM aicrm_marketingtools
    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_marketingtools.marketingtoolsid
    INNER JOIN aicrm_marketingtoolscf ON aicrm_marketingtoolscf.marketingtoolsid = aicrm_marketingtools.marketingtoolsid
    LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
    LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
    LEFT JOIN (
      select * 
      from aicrm_picklistcolor 
      where aicrm_picklistcolor.tabid='".$tabid."'
      ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_marketingtools.competitor_status
    WHERE aicrm_crmentity.deleted = 0 ";
    break;
    case "Salesinvoice":
      $query = "
      FROM aicrm_salesinvoice
      INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_salesinvoice.salesinvoiceid
      INNER JOIN aicrm_salesinvoicecf ON aicrm_salesinvoicecf.salesinvoiceid = aicrm_salesinvoice.salesinvoiceid
      INNER JOIN aicrm_account ON aicrm_account.accountid = aicrm_salesinvoice.accountid
      LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_salesinvoice.product_id
      LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
      LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
      WHERE aicrm_crmentity.deleted = 0 ";
      break;
    Case "Expense":
      $query = "
      FROM aicrm_expense
      INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_expense.expenseid
      INNER JOIN aicrm_expensecf ON aicrm_expensecf.expenseid = aicrm_expense.expenseid
      LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
      LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
      LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_expense.accountid
      LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_expense.contactid
      LEFT JOIN aicrm_projects ON aicrm_projects.projectsid = aicrm_expense.projectsid
      LEFT JOIN aicrm_activity ON aicrm_activity.activityid = aicrm_expense.activityid
      LEFT JOIN (
        select * 
        from aicrm_picklistcolor 
        where aicrm_picklistcolor.tabid='".$tabid."'
        ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_expense.expenses_type
      WHERE aicrm_crmentity.deleted = 0 ";
    break;
    Case "Competitorproduct":
      $query = "
      FROM aicrm_competitorproduct
      INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_competitorproduct.competitorproductid
      INNER JOIN aicrm_competitorproductcf ON aicrm_competitorproductcf.competitorproductid = aicrm_competitorproduct.competitorproductid
      LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
      LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
      LEFT JOIN (
        select * 
        from aicrm_picklistcolor 
        where aicrm_picklistcolor.tabid='".$tabid."'
        ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_competitorproduct.competitor_product_status
      WHERE aicrm_crmentity.deleted = 0 ";
      break;
    Case "Samplerequisition":
      $query = "
      FROM aicrm_samplerequisition
      INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_samplerequisition.samplerequisitionid
      INNER JOIN aicrm_samplerequisitioncf ON aicrm_samplerequisitioncf.samplerequisitionid = aicrm_samplerequisition.samplerequisitionid
      LEFT JOIN aicrm_account ON aicrm_account.accountid = aicrm_samplerequisition.accountid
      LEFT JOIN aicrm_contactdetails ON aicrm_contactdetails.contactid = aicrm_samplerequisition.contactid
      LEFT JOIN aicrm_projects ON aicrm_projects.projectsid = aicrm_samplerequisition.projectsid
      LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
      LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
      LEFT JOIN (
        select * 
        from aicrm_picklistcolor 
        where aicrm_picklistcolor.tabid='".$tabid."'
        ) aicrm_picklistcolor on aicrm_picklistcolor.picklist_value = aicrm_samplerequisition.samplerequisition_status
      WHERE aicrm_crmentity.deleted = 0 ";
      break;
    default:
  }

  return $query;
}

public function Get_QuerySelect($module){
  switch($module){
    Case "Job":
    $select = "aicrm_jobs.jobid AS id
    , IFNULL(aicrm_jobs.job_no,'') AS no
    , IFNULL(aicrm_jobs.job_name,'') AS name
    , IFNULL(aicrm_jobs.jobid,'') as jobid
    , IFNULL(aicrm_jobs.job_name,'') as job_name
    , aicrm_jobs.job_status as status
    , case when aicrm_picklistcolor.color is NULL then 'E67E22'
      else  aicrm_picklistcolor.color
      END as color
     ";    
    break;
    Case "Accounts":
    $select = "aicrm_account.accountid AS id ,aicrm_account.account_no AS no ,aicrm_account.accountname AS name ,aicrm_account.accountid ,aicrm_account.account_no,aicrm_account.accountname,
    aicrm_account.mobile as phone,
    aicrm_account.email1 as email,
    IFNULL(aicrm_account.accountname , '') as title ,
    aicrm_account.accountstatus AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_account.account_no AS description,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    Case "Leads":
    $select = "aicrm_leaddetails.leadid AS id ,aicrm_leaddetails.lead_no AS no ,CONCAT(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname)  AS name ,aicrm_leaddetails.leadid ,CONCAT(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) as leadname, aicrm_leaddetails.lead_no, aicrm_leaddetails.firstname,aicrm_leaddetails.lastname,
    IFNULL(CONCAT(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname), '') as title ,
    aicrm_leaddetails.leadstatus AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_leaddetails.lead_no AS description,
    aicrm_crmentity.createdtime AS dateAt ";
    break;
    Case "HelpDesk":
    //$select = "aicrm_troubletickets.ticketid AS id ,aicrm_troubletickets.ticket_no AS no ,aicrm_troubletickets.title AS name ,aicrm_troubletickets.ticketid , aicrm_troubletickets.ticket_no, aicrm_troubletickets.title";
    Case "HelpDesk":
    $select = "aicrm_troubletickets.ticketid AS id ,aicrm_troubletickets.ticket_no AS no ,aicrm_troubletickets.title AS name ,aicrm_troubletickets.ticketid ,aicrm_troubletickets.ticket_no,aicrm_troubletickets.title,
    IFNULL(aicrm_troubletickets.title , '') as title ,
    aicrm_troubletickets.case_status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
      aicrm_troubletickets.ticket_no AS description,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    Case "Serial":
    $select = "aicrm_serial.serialid AS id ,aicrm_serial.serial_no AS no ,aicrm_serial.serial_name AS name ,aicrm_serial.serialid,aicrm_serial.serial_no,aicrm_serial.serial_name";
    break;
    Case "Sparepart":
    $select = "aicrm_sparepart.sparepartid AS id ,aicrm_sparepart.sparepart_no AS no ,aicrm_sparepart.sparepart_name AS name  ,aicrm_sparepart.sparepartid,aicrm_sparepart.sparepart_no,aicrm_sparepart.sparepart_name ,
    aicrm_sparepart.sparepart_name as title ,
    aicrm_sparepart.sparepart_no AS description,
    aicrm_sparepart.sparepart_stock_qty AS stock,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    Case "Sparepartlist":
    $select = "aicrm_sparepartlist.sparepartlistid AS id ,aicrm_sparepartlist.sparepartlist_no AS no ,aicrm_sparepartlist.sparepartlist_name AS name ,aicrm_sparepartlist.sparepartlistid, aicrm_sparepartlist.sparepartlist_no, aicrm_sparepartlist.sparepartlist_name ,
     aicrm_sparepartlist.sparepartlist_name as title ,
    aicrm_sparepartlist.sparepartlist_no AS description,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    case 'Service':
      $select = "
      aicrm_service.serviceid AS id,
      aicrm_service.service_no AS no,
      aicrm_service.service_name AS name,
      aicrm_service.serviceid,
      aicrm_service.service_no,
      aicrm_service.service_name,
      aicrm_crmentity.createdtime AS dateAt";
      break;
    Case "Errors":
    $select = "aicrm_errors.errorsid AS id ,aicrm_errors.errors_no AS no ,aicrm_errors.errors_name AS name ,aicrm_errors.errorsid ,aicrm_errors.errors_no,aicrm_errors.errors_name,
    aicrm_errors.errors_name as title ,
    aicrm_errors.errors_no AS description,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    Case "Errorslist":
    $select = "aicrm_errorslist.errorslistid AS id ,aicrm_errorslist.errorslist_no AS no ,aicrm_errorslist.errorslist_name AS name ,aicrm_errorslist.errorslistid ,aicrm_errorslist.errorslist_no,aicrm_errorslist.errorslist_name,
    aicrm_errorslist.errorslist_name  as title ,
    aicrm_errorslist.errorslist_no AS description,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    Case "Opportunity":
    $select = "aicrm_opportunity.opportunityid AS id ,aicrm_opportunity.opportunity_no AS no ,aicrm_opportunity.opportunity_name AS name ,aicrm_opportunity.opportunityid , aicrm_opportunity.opportunity_name ";
    break;
    Case "Quotes":
    $select = "aicrm_quotes.quoteid AS id ,aicrm_quotes.quote_no AS no, aicrm_quotes.quote_name AS name, aicrm_quotes.quote_no, aicrm_quotes.quote_name";
    break;
    Case "Salesinvoice":
    $select = "aicrm_salesinvoice.salesinvoiceid AS id ,aicrm_salesinvoice.salesinvoice_no AS no, aicrm_salesinvoice.salesinvoice_name AS name, aicrm_salesinvoice.salesinvoice_no, aicrm_salesinvoice.salesinvoice_name";
    break;
    Case "Contacts":
    $select = " aicrm_contactdetails.contactid AS id ,aicrm_contactdetails.contact_no AS no  ,
    CONCAT(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) AS name,
    aicrm_contactdetails.contactid,
    aicrm_contactdetails.mobile as contact_mobile,
    aicrm_contactdetails.email as contact_email,
    CONCAT(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) AS contactname ,
    aicrm_contactdetails.contact_no,aicrm_contactdetails.firstname,aicrm_contactdetails.lastname ,
    IFNULL(aicrm_contactdetails.contactname , '') as title,
    aicrm_contactdetails.contact_no AS description,
    aicrm_crmentity.createdtime AS dateAt ";
    break;
    Case "Products":
    $select = " aicrm_products.productid AS id ,aicrm_products.product_no AS no ,aicrm_products.productname AS name ,aicrm_products.productid , aicrm_products.product_no,aicrm_products.productname, aicrm_products.stockqty as stock";
    break;
    Case "Projects":

    $select = "aicrm_projects.projectsid AS id ,aicrm_projects.projects_no AS no ,aicrm_projects.projects_name AS name ,aicrm_projects.projectsid , aicrm_projects.projects_name,
    IFNULL(aicrm_projects.projects_name , '') as title ,
    aicrm_projects.projectorder_status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
      aicrm_projects.projects_no AS description,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    Case "Project":
    $select = " aicrm_project.projectid AS id ,aicrm_project.project_no AS no ,aicrm_project.project_name AS name ,aicrm_project.projectid , aicrm_project.project_name";
    break;
    Case "Projectorder":
    $select = " aicrm_projectorder.projectorderid AS id ,aicrm_projectorder.projectorder_no AS no ,aicrm_projectorder.projectorder_name AS name ,aicrm_projectorder.projectorderid , aicrm_projectorder.projectorder_name";
    break;
    Case "Campaigns":
    $select = " aicrm_campaign.campaignid AS id ,aicrm_campaign.campaign_no AS no ,aicrm_campaign.campaignname AS name ,aicrm_campaign.campaignid ,aicrm_campaign.campaignname,
    IFNULL(aicrm_campaign.campaignname , '') as title ,
    aicrm_campaign.campaign_status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
      aicrm_campaign.campaign_no AS description,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    Case "Voucher":
    $select = " aicrm_voucher.voucherid AS id ,aicrm_voucher.voucher_no AS no ,aicrm_voucher.voucher_name AS name ,aicrm_voucher.voucherid ,aicrm_voucher.voucher_name,
    IFNULL(aicrm_voucher.voucher_name , '') as title ,
    aicrm_voucher.voucher_status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
      aicrm_voucher.voucher_no AS description,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    Case "Promotion":
    $select = " aicrm_promotion.promotionid AS id ,aicrm_promotion.promotion_no AS no ,aicrm_promotion.promotion_name AS name ,aicrm_promotion.promotionid ,aicrm_promotion.promotion_name";
    break;
    Case "Documents":
    $select = " aicrm_notes.notesid AS id ,aicrm_notes.note_no AS no ,aicrm_notes.title AS name ,aicrm_notes.notesid , aicrm_notes.title,aicrm_notes.note_no";
    break;
    Case "Users":
    $select = " aicrm_users.id AS id ,aicrm_users.user_name  AS name";
    break;
    Case "Calendar":
    /**
     * update log
     * 2022-08-15 [No#124][Issue] [Desciption: หน้า Detail หาก Sales Visit รายการนั้นเลือก Lead ด้านบนหัวจะไม่ขึ้นว่า Sales Visit นี้..เป็นของ Lead รายการใด ซึ่งต้องขึ้นด้วย]
     */
    $select="aicrm_activity.activityid AS id,aicrm_activity.activityid,aicrm_activity.activitytype,
    aicrm_activity.date_start,aicrm_activity.due_date,aicrm_activity.time_start,
    aicrm_activity.time_end,aicrm_activity.sendnotification,aicrm_activity.eventstatus,
    aicrm_activity.visibility,aicrm_activity.visibility1,aicrm_account.accountname,aicrm_account.account_no,aicrm_activity.location
    ,aicrm_activity.activitytype as name,aicrm_account.accountname as no ,aicrm_activity.phone as phone, '' as navigate
    ,aicrm_leaddetails.lead_no
    ,aicrm_leaddetails.leadname
    ,aicrm_users.user_name as user_name,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
    else  aicrm_picklistcolor.color
    END as color ";
    break;
    Case "Events":
    $select = "aicrm_activity.activityid,aicrm_activity.activitytype,aicrm_activity.date_start,
    aicrm_activity.due_date,aicrm_activity.time_start,aicrm_activity.time_end,aicrm_activity.sendnotification,
    aicrm_activity.eventstatus,aicrm_activity.visibility,aicrm_activity.visibility1,
    aicrm_account.accountname,aicrm_account.account_no,aicrm_activity.location
    ,aicrm_activity.activitytype as name,aicrm_account.accountname as no ,aicrm_activity.phone as phone, '' as navigate ,
    aicrm_users.user_name as user_name";
    break;
    Case "Order":
    $select = " aicrm_order.orderid AS id ,aicrm_order.order_no AS no ,aicrm_order.order_name AS name ";
    break;
    Case "Deal":
    $select = " aicrm_deal.dealid AS id ,aicrm_deal.deal_no AS no,aicrm_deal.deal_name  AS name ,aicrm_deal.dealid ,aicrm_deal.deal_no ,aicrm_deal.deal_name,
    IFNULL(aicrm_deal.deal_name , '') as title ,
    aicrm_deal.deal_no AS description,
    aicrm_deal.stage AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt ";

    // concat(aicrm_deal.productbrand,' ' ,aicrm_deal.model) AS subtitle ,
    // ,concat('฿',FORMAT(aicrm_deal.budget, 0)) AS bottomTitle
    // IFNULL(aicrm_account.accountname , '') as title ,
    break;
    Case "Questionnaire":
    $select = " aicrm_questionnaire.questionnaireid AS id ,aicrm_questionnaire.questionnaire_no AS no,aicrm_questionnaire.questionnaire_name  AS name ,aicrm_questionnaire.questionnaireid ,aicrm_questionnaire.questionnaire_no ,aicrm_questionnaire.questionnaire_name,
    IFNULL(aicrm_questionnaire.questionnaire_name , '') as title ,
    aicrm_questionnaire.questionnaire_no AS description,
    aicrm_questionnaire.questionnaire_status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt ,
    '' AS subtitle ,
    '' AS bottomTitle";
    break;
    Case "Faq":
    $select = " aicrm_faq.faqid AS id ,aicrm_faq.faq_no AS no,aicrm_faq.faq_name  AS name ,aicrm_faq.faqid ,aicrm_faq.faq_no ,aicrm_faq.faq_name,
    aicrm_faq.faq_name as title ,
    aicrm_faq.faq_no AS description,
    aicrm_faq.faqstatus AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt ";
    break;
    Case "Announcement":
    $select = " aicrm_announcement.announcementid AS id ,aicrm_announcement.announcement_no AS no,aicrm_announcement.announcement_name  AS name ,aicrm_announcement.announcementid ,aicrm_announcement.announcement_no ,aicrm_announcement.announcement_name,
    aicrm_announcement.announcement_name as title ,
    aicrm_announcement.announcement_no AS description,
    aicrm_announcement.status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt ";
    break;
    Case "Competitor":
    $select = " aicrm_competitor.competitorid AS id ,aicrm_competitor.competitor_no AS no,aicrm_competitor.competitor_name  AS name ,aicrm_competitor.competitorid ,aicrm_competitor.competitor_no ,aicrm_competitor.competitor_name,
    IFNULL(aicrm_competitor.competitor_name , '') as title ,
    aicrm_competitor.competitor_no AS description,
    aicrm_competitor.competitor_status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt ";
    break;
    Case "Expense":
    $select = " aicrm_expense.expenseid AS id ,aicrm_expense.expense_no AS no,aicrm_expense.expense_name AS name ,aicrm_expense.expenseid ,aicrm_expense.expense_no ,aicrm_expense.expense_name,
    IFNULL(aicrm_expense.expense_name , '') as title ,
    aicrm_expense.expense_no AS description,
    aicrm_expense.expenses_type AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt ";
    break;
    Case "Samplerequisition":
    $select = " aicrm_samplerequisition.samplerequisitionid AS id ,aicrm_samplerequisition.samplerequisition_no AS no,aicrm_samplerequisition.samplerequisition_name AS name ,aicrm_samplerequisition.samplerequisitionid ,aicrm_samplerequisition.samplerequisition_no ,aicrm_samplerequisition.samplerequisition_name,
    IFNULL(aicrm_samplerequisition.samplerequisition_name , '') as title ,
    aicrm_samplerequisition.samplerequisition_no AS description,
    aicrm_samplerequisition.samplerequisition_status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt ";
    break;
    default:
    
  }

  return $select;
}

public function Get_QuerySelect_Index($module){
  switch($module){
    Case "Job":
    $select = "aicrm_jobs.jobid AS id
    , IFNULL(aicrm_jobs.job_no,'') AS no
    , IFNULL(aicrm_jobs.job_name,'') AS name
    , IFNULL(aicrm_jobs.jobid,'') as jobid
    , IFNULL(aicrm_jobs.job_name,'') as job_name
    , aicrm_jobs.job_status as status
    , 'E67E22' as color
     ";    
    break;
    Case "Accounts":
    $select = "aicrm_account.accountid AS id ,aicrm_account.account_no AS no ,aicrm_account.accountname AS name ,aicrm_account.accountid ,aicrm_account.account_no,aicrm_account.accountname,
    aicrm_account.accountindustry as business_type,
    aicrm_account.firstname,
    aicrm_account.lastname,
    aicrm_account.idcardno,
    aicrm_account.birthdate,
    aicrm_account.mobile,
    aicrm_account.mobile2,
    aicrm_account.gender,
    aicrm_account.email1 as email,
    aicrm_account.email2,
    aicrm_account.accounttype as record1,
    aicrm_account.account_group as record2,
    IFNULL(aicrm_account.accountname , '') as title ,
    aicrm_account.accountstatus AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt,
    concat(aicrm_users.first_name,' ',aicrm_users.last_name) as sale_owner,
    aicrm_account.account_name_th,
    aicrm_account.account_name_en,
    aicrm_account.account_group,
    aicrm_account.accountindustry,
    aicrm_account.account_grade,
    aicrm_account.cd_no,
    aicrm_account.village,
    aicrm_account.addressline,
    aicrm_account.address,
    aicrm_account.villageno,
    aicrm_account.lane,
    aicrm_account.street,
    aicrm_account.subdistrict,
    aicrm_account.district,
    aicrm_account.province,
    aicrm_account.postalcode,
    aicrm_account.payment_terms_type,
    aicrm_account.payment_terms
    ";
    break;
    Case "Leads":
    $select = "aicrm_leaddetails.leadid AS id ,aicrm_leaddetails.lead_no AS no ,CONCAT(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname)  AS name ,aicrm_leaddetails.leadid ,CONCAT(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) as leadname, aicrm_leaddetails.lead_no, aicrm_leaddetails.firstname,aicrm_leaddetails.lastname,
    IFNULL(CONCAT(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname), '') as title ,
    aicrm_leaddetails.firstname,
    aicrm_leaddetails.lastname,
    aicrm_leaddetails.idcardno,
    aicrm_leaddetails.industry as business_type,
    aicrm_leaddetails.birthdate,
    aicrm_leaddetails.gender,
    aicrm_leaddetails.email,
    aicrm_leaddetails.mobile, 
    aicrm_leaddetails.mobile AS phone,
    aicrm_leaddetails.leadstatus AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt ";
    break;
    Case "HelpDesk":
    $select = "aicrm_troubletickets.ticketid AS id ,aicrm_troubletickets.ticket_no AS no ,aicrm_troubletickets.title AS name ,aicrm_troubletickets.ticketid , aicrm_troubletickets.ticket_no, aicrm_troubletickets.title";
    break;
    Case "Serial":
    $select = "aicrm_serial.serialid AS id ,aicrm_serial.serial_no AS no ,aicrm_serial.serial_name AS name ,aicrm_serial.serialid,aicrm_serial.serial_no,aicrm_serial.serial_name";
    break;
    Case "Sparepart":
    $select = "aicrm_sparepart.sparepartid AS id ,aicrm_sparepart.sparepart_no AS no ,aicrm_sparepart.sparepart_name AS name  ,aicrm_sparepart.sparepartid,aicrm_sparepart.sparepart_no,aicrm_sparepart.sparepart_name ,
    aicrm_sparepart.sparepart_name as title ,
    aicrm_sparepart.sparepart_no AS description,
    aicrm_sparepart.sparepart_cost AS price,
    aicrm_sparepart.sparepart_stock_qty AS stock,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    Case "Sparepartlist":
    $select = "aicrm_sparepartlist.sparepartlistid AS id ,aicrm_sparepartlist.sparepartlist_no AS no ,aicrm_sparepartlist.sparepartlist_name AS name ,aicrm_sparepartlist.sparepartlistid, aicrm_sparepartlist.sparepartlist_no, aicrm_sparepartlist.sparepartlist_name ,
     aicrm_sparepartlist.sparepartlist_name as title ,
    aicrm_sparepartlist.sparepartlist_no AS description,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    case "Service":
    $select = "aicrm_service.serviceid AS id, aicrm_service.service_no AS no, aicrm_service.service_name AS name, aicrm_service.serviceid, aicrm_service.service_no, aicrm_service.service_name, aicrm_service.unit_price AS price, 1 AS stock";
    break;
    Case "Errors":
    $select = "aicrm_errors.errorsid AS id ,aicrm_errors.errors_no AS no ,aicrm_errors.errors_name AS name ,aicrm_errors.errorsid ,aicrm_errors.errors_no,aicrm_errors.errors_name,
    aicrm_errors.errors_name as title ,
    aicrm_errors.errors_no AS description,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    Case "Errorslist":
    $select = "aicrm_errorslist.errorslistid AS id ,aicrm_errorslist.errorslist_no AS no ,aicrm_errorslist.errorslist_name AS name ,aicrm_errorslist.errorslistid ,aicrm_errorslist.errorslist_no,aicrm_errorslist.errorslist_name,
    aicrm_errorslist.errorslist_name as title ,
    aicrm_errorslist.errorslist_no AS description,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    Case "Opportunity":
    $select = "aicrm_opportunity.opportunityid AS id ,aicrm_opportunity.opportunity_no AS no ,aicrm_opportunity.opportunity_name AS name ,aicrm_opportunity.opportunityid , aicrm_opportunity.opportunity_name ";
    break;
    Case "Quotes":
    $select = "aicrm_quotes.quoteid AS id ,aicrm_quotes.quote_no AS no, aicrm_quotes.quote_name AS name, aicrm_quotes.quote_no, aicrm_quotes.quote_name";
    break;
    Case "Contacts":
    $select = " aicrm_contactdetails.contactid AS id ,aicrm_contactdetails.contact_no AS no  ,
    CONCAT(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) AS name,
    aicrm_contactdetails.contactid,
    aicrm_contactdetails.contact_no,
    aicrm_contactdetails.contactname,
    aicrm_contactdetails.service_level,
    aicrm_contactdetails.mobile as record1,
    aicrm_contactdetails.email as record2,
    aicrm_contactdetails.mobile as contact_mobile,
    aicrm_contactdetails.email as contact_email,
    CONCAT(aicrm_contactdetails.firstname,' ',aicrm_contactdetails.lastname) AS contactname ,
    aicrm_contactdetails.contact_no,aicrm_contactdetails.firstname,aicrm_contactdetails.lastname ,
    IFNULL(aicrm_account.accountname , '') as title ,
    aicrm_account.accountid,
    aicrm_account.accountname ";
    break;
    Case "Products":
    $select = " aicrm_products.productid AS id ,aicrm_products.product_no AS no ,aicrm_products.productname AS name ,aicrm_products.productid ,aicrm_products.productid as product_id,
      aicrm_products.product_no,
      aicrm_products.productname, 
      aicrm_products.product_brand as record1,
      aicrm_products.product_group as record2,
      aicrm_products.product_brand,
      aicrm_products.product_group,
      aicrm_products.product_code_crm,
      aicrm_products.product_design_name,
      aicrm_products.stockqty AS stock,
      aicrm_products.product_finish,
      aicrm_products.product_size_mm,
      aicrm_products.product_thinkness,
      aicrm_products.product_cost_avg,
      aicrm_products.unit AS uom ,
      aicrm_products.material_code,
      aicrm_products.product_sub_group,
      aicrm_products.product_catalog_code,
      aicrm_products.productdescription AS remark";
    break;
    Case "Projects":
    $select = "aicrm_projects.projectsid AS id ,aicrm_projects.projects_no AS no ,aicrm_projects.projects_name AS name ,aicrm_projects.projectsid , aicrm_projects.projects_name,
    IFNULL(aicrm_projects.projects_name , '') as title ,
    aicrm_projects.projectorder_status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
      aicrm_projects.projects_no AS description,
    aicrm_crmentity.createdtime AS dateAt,
    aicrm_projects.project_s_date,
    aicrm_projects.project_estimate_e_date";
    break;
    Case "Project":
    $select = " aicrm_project.projectid AS id ,aicrm_project.project_no AS no ,aicrm_project.project_name AS name ,aicrm_project.projectid , aicrm_project.project_name";
    break;
    Case "Projectorder":
    $select = " aicrm_projectorder.projectorderid AS id ,aicrm_projectorder.projectorder_no AS no ,aicrm_projectorder.projectorder_name AS name ,aicrm_projectorder.projectorderid , aicrm_projectorder.projectorder_name";
    break;
    Case "Campaigns":
    $select = " aicrm_campaign.campaignid AS id ,aicrm_campaign.campaign_no AS no ,aicrm_campaign.campaignname AS name ,aicrm_campaign.campaignid ,aicrm_campaign.campaignname,
    IFNULL(aicrm_campaign.campaignname , '') as title ,
    aicrm_campaign.campaign_status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
      aicrm_campaign.campaign_no AS description,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    Case "Voucher":
    $select = " aicrm_voucher.voucherid AS id ,aicrm_voucher.voucher_no AS no ,aicrm_voucher.voucher_name AS name ,aicrm_voucher.voucherid ,aicrm_voucher.voucher_name,
    IFNULL(aicrm_voucher.voucher_name , '') as title ,
    aicrm_voucher.voucher_status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
      aicrm_voucher.voucher_no AS description,
    aicrm_crmentity.createdtime AS dateAt
    ";
    break;
    Case "Promotion":
    $select = " aicrm_promotion.promotionid AS id ,aicrm_promotion.promotion_no AS no ,aicrm_promotion.promotion_name AS name ,aicrm_promotion.promotionid ,aicrm_promotion.promotion_name";
    break;
    Case "Documents":
    $select = " aicrm_notes.notesid AS id ,aicrm_notes.note_no AS no ,aicrm_notes.title AS name ,aicrm_notes.notesid , aicrm_notes.title,aicrm_notes.note_no";
    break;
    Case "Users":
    $select = " aicrm_users.id AS id ,aicrm_users.user_name  AS name";
    break;
    Case "Calendar":
    $select="aicrm_activity.activityid,aicrm_activity.activitytype,
    aicrm_activity.date_start,aicrm_activity.due_date,aicrm_activity.time_start,
    aicrm_activity.time_end,aicrm_activity.sendnotification,aicrm_activity.eventstatus,
    aicrm_activity.visibility,aicrm_activity.visibility1,aicrm_account.accountname,aicrm_account.account_no,aicrm_activity.location
    ,aicrm_activity.activitytype as name,aicrm_account.accountname as no ,aicrm_activity.phone as phone, '' as navigate ,
    aicrm_users.user_name as user_name";
    break;
    Case "Events":
    $select = "aicrm_activity.activityid,aicrm_activity.activitytype,aicrm_activity.date_start,
    aicrm_activity.due_date,aicrm_activity.time_start,aicrm_activity.time_end,aicrm_activity.sendnotification,
    aicrm_activity.eventstatus,aicrm_activity.visibility,aicrm_activity.visibility1,
    aicrm_account.accountname,aicrm_account.account_no,aicrm_activity.location
    ,aicrm_activity.activitytype as name,aicrm_account.accountname as no ,aicrm_activity.phone as phone, '' as navigate ,
    aicrm_users.user_name as user_name";
    break;
    Case "Order":
    $select = " aicrm_order.orderid AS id ,aicrm_order.order_no AS no ,aicrm_order.order_name AS name ";
    break;
    Case "Deal":
    $select = " aicrm_deal.dealid AS id ,aicrm_deal.deal_no AS no,aicrm_deal.deal_name  AS name ,aicrm_deal.dealid ,aicrm_deal.deal_no ,aicrm_deal.deal_name,
    CASE WHEN aicrm_account.accountname != '' THEN aicrm_account.accountname
    WHEN aicrm_leaddetails.leadname != '' THEN aicrm_leaddetails.leadname
    ELSE '' END AS title,
    aicrm_deal.type,
    aicrm_deal.stage AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt";
    break;
    Case "Questionnaire":
    $select = " aicrm_questionnaire.questionnaireid AS id ,aicrm_questionnaire.questionnaire_no AS no,aicrm_questionnaire.questionnaire_name  AS name ,aicrm_questionnaire.questionnaireid ,aicrm_questionnaire.questionnaire_no ,aicrm_questionnaire.questionnaire_name,
    IFNULL(aicrm_questionnaire.questionnaire_name , '') as title ,
    aicrm_questionnaire.questionnaire_status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt ,
    concat(aicrm_questionnaire.scoring) AS subtitle ,
    concat(aicrm_questionnaire.point, '  คะแนน') AS bottomTitle";
    break;
    Case "Faq":
    $select = " aicrm_faq.faqid AS id ,aicrm_faq.faq_no AS no,aicrm_faq.faq_name  AS name ,aicrm_faq.faqid ,aicrm_faq.faq_no ,aicrm_faq.faq_name,
    aicrm_faq.faq_name as title ,
    aicrm_faq.faq_no AS description,
    aicrm_faq.faqstatus AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt ";
    break;
    Case "Announcement":
    $select = " aicrm_announcement.announcementid AS id ,aicrm_announcement.announcement_no AS no,aicrm_announcement.announcement_name  AS name ,aicrm_announcement.announcementid ,aicrm_announcement.announcement_no ,aicrm_announcement.announcement_name,
    aicrm_announcement.announcement_name as title ,
    aicrm_announcement.announcement_no AS description,
    aicrm_announcement.status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt ";
    break;
    Case "Competitor":
    $select = " aicrm_competitor.competitorid AS id ,aicrm_competitor.competitor_no AS no,aicrm_competitor.competitor_name  AS name ,aicrm_competitor.competitorid ,aicrm_competitor.competitor_no ,aicrm_competitor.competitor_name,
    IFNULL(aicrm_competitor.competitor_name , '') as title ,
    aicrm_competitor.competitor_status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt ";
    break;
    Case "Marketingtools":
    $select = " aicrm_marketingtools.marketingtoolsid AS id ,aicrm_marketingtools.marketingtools_no AS no,aicrm_marketingtools.marketingtools_name  AS name ,aicrm_marketingtools.marketingtoolsid ,aicrm_marketingtools.marketingtools_no ,aicrm_marketingtools.marketingtools_name,
    IFNULL(aicrm_marketingtools.marketingtools_name , '') as title,
    aicrm_crmentity.createdtime AS dateAt ";
    break;
    Case "Competitorproduct":
    $select = " aicrm_competitorproduct.competitorproductid AS id ,aicrm_competitorproduct.competitorproduct_no AS no,aicrm_competitorproduct.competitorproduct_name_th AS name ,
    aicrm_competitorproduct.competitorproductid ,
    aicrm_competitorproduct.competitorproduct_no ,
    aicrm_competitorproduct.competitorproduct_name_th,
    aicrm_competitorproduct.competitor_product_brand as record1,
    aicrm_competitorproduct.competitor_product_group as record2,
    aicrm_competitorproduct.competitor_product_brand,
    aicrm_competitorproduct.competitor_product_group,
    aicrm_competitorproduct.competitor_product_size,
    aicrm_competitorproduct.competitor_product_thickness,
    aicrm_competitorproduct.selling_price,
    IFNULL(aicrm_competitorproduct.competitorproduct_name_th , '') as title ,
    aicrm_competitorproduct.competitor_product_status AS status ,
    case when aicrm_picklistcolor.color is NULL then '#33DAFF'
      else  aicrm_picklistcolor.color
      END as color,
    aicrm_crmentity.createdtime AS dateAt ";
    break;
    default:
    
  }
  // echo $module; echo $select; exit();
  return $select;
}

public function Get_Field_In_Table($tabid,$tablename){
  $sql="
  SELECT *
  FROM aicrm_field
  /*
  INNER JOIN aicrm_profile2field ON aicrm_profile2field.fieldid = aicrm_field.fieldid
  INNER JOIN aicrm_def_org_field ON aicrm_def_org_field.fieldid = aicrm_field.fieldid
  */
  WHERE 1
  and aicrm_field.tabid = '".$tabid."'
  /*
  AND aicrm_profile2field.visible = 0
  AND aicrm_def_org_field.visible = 0
  */
  and aicrm_field.tablename='".$tablename."'
  and aicrm_field.displaytype in (1,3)
  and aicrm_field.presence in (0,2)
  group by columnname";

  $query = $this->ci->db->query($sql);
  $data_field = $query->result_array();
  return $data_field;
}

public function Mysql_Query($sql){
  $this->ci->db->query($sql);
  return true;
}
public function Get_Crmid(){
  $sql="select (id+1) as id from aicrm_crmentity_seq";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();

  $crmid = $data[0]['id'];

  $sql="update  aicrm_crmentity_seq set id='".$crmid."'";
  $this->ci->db->query($sql);
  return $crmid;
}
public function setModuleSeqNumber($module){
   
  $sql="select cur_id,prefix from aicrm_modentity_num where semodule='".$module."' and active = 1";
  
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();

  $prefix = $data[0]['prefix'];
  $curid = $data[0]['cur_id'];
  $prev_inv_no=$prefix.$curid;
  $strip=strlen($curid)-strlen($curid+1);
  if($strip<0)$strip=0;
  $temp = str_repeat("0",$strip);
  $req_no= $temp.($curid+1);
  $sql="UPDATE aicrm_modentity_num SET cur_id='".$req_no."' where cur_id='".$curid."' and active=1 AND semodule='".$module."'";
  $this->ci->db->query($sql);
  return $prev_inv_no;
}
public function generateQuestionMarks($items_list) {
  print_r($items_list);
  // array_map will call the function specified in the first parameter for every element of the list in second parameter
  if (is_array($items_list)) {
    return implode(",", array_map("_questionify", $items_list));
  } else {
    return implode(",", array_map("_questionify", explode(",", $items_list)));
  }
}
public function Get_TabID($module){
  $sql="select tabid from aicrm_tab where name='".$module."'";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  $tabid = $data[0]['tabid'];
  return $tabid;
}

public function Insert_Update($module,$crmid,$action,$tab_name,$tab_name_index,$data,$userid){

  if($module=="Calendar"){
    $module="Events";
  }

  if ($module=="Marketing tools") {
    $module = "Marketingtools";
  }

  $doc_no="";
  
  if($action=="edit"){
    $this->crmid=$crmid;
  }else{
    $this->crmid=$this->Get_Crmid();
  }
  
  $chk=0;
  
  for($i=0;$i<count($tab_name);$i++){

    $update_colum = array();
    $table_name=$tab_name[$i];

    if($tab_name[$i] == "aicrm_crmentity"){
      $description = "";
      $smcreatorid=$userid;
      $smownerid="";
      $modifiedby=$userid;
      $deleted = "";
      for($kk=0;$kk<count($data[0]);$kk++){
        $data_field=array_keys($data[0]);
        $data_value=array_values($data[0]);
        if($data_field[$kk]=="description"){
          $description=str_replace("'","''",$data_value[$kk]);
        }else if($data_field[$kk]=="smcreatorid"){
          $smcreatorid=str_replace("'","''",$data_value[$kk]);
        }else if($data_field[$kk]=="smownerid"){
          $smownerid=str_replace("'","''",$data_value[$kk]);
        }else if($data_field[$kk]=="modifiedby"){
          $modifiedby=str_replace("'","''",$data_value[$kk]);
        }else if($data_field[$kk]=="deleted"){
          $deleted=str_replace("'","''",$data_value[$kk]);
        }
      }

      if($action=="edit"){
        //Insert Activity Logs (Action Edit)
        $this->insertIntoActivity_Timeline($module,$this->crmid,$action,$userid,$data[0]);

        $sql="
        update aicrm_crmentity set
        modifiedtime=NOW() ";

        if($modifiedby != '' && $modifiedby != '0' ){
          $sql .= " ,modifiedby='".$modifiedby."'";
        }
        if($smownerid != '' && $smownerid != '0'){
          $sql .= " ,smownerid='".$smownerid."'";
        }
        if($modifiedby != '' && $modifiedby != '0'){
          $sql .= " ,modifiedby='".$modifiedby."'";
        }        
        if($description != "" ){
          $sql .= " ,description='".$description."'";
        }
        if($deleted != '' && $deleted != '0'){
          $sql .= " ,deleted ='".$deleted."'";
        }
        $sql .= " where crmid='".$this->crmid."' ";
        if($this->ci->db->query($sql)){}else{$chk=1;}
      
      }else{
        $this->insertIntoActivity_Timeline($module,$this->crmid,$action,$userid,$data[0]);
        $sql = "insert into aicrm_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime,modifiedby) values
        ('".$this->crmid."','".$smcreatorid."','".$smownerid."','".$module."','".$description."',NOW(),NOW(),'".$modifiedby."')";
        if($this->ci->db->query($sql)){}else{$chk=1;}      
      }

    }else{

      if($action=="edit"){
      }else{
        $column_add = array($tab_name_index[$tab_name[$i]]);
        $value_add = array($this->crmid);
      }
      $tabid=$this->Get_TabID($module); //event
      $data_field_chk=$this->Get_Field_In_Table($tabid,$tab_name[$i]);

      $field_check = array(); 

      for($kk=0;$kk<count($data[0]);$kk++){
        $data_field=array_keys($data[0]);
        $data_value=array_values($data[0]);

        for($k=0;$k<count($data_field_chk);$k++){
          $table_name=$data_field_chk[$k]["tablename"];
          $fieldname=$data_field_chk[$k]["fieldname"];
          $fieldid=$data_field_chk[$k]["fieldid"];
          $columname=$data_field_chk[$k]["columnname"];
          $uitype=$data_field_chk[$k]["uitype"];
          $generatedtype=$data_field_chk[$k]["generatedtype"];
          $typeofdata=$data_field_chk[$k]["typeofdata"];
          $typeofdata_array = explode("~",$typeofdata);

          $field_check[$k]['fieldname'] = $fieldname;
          $field_check[$k]['fieldid'] = $fieldid;
          $field_check[$k]['uitype'] = $uitype;

          $datatype = $typeofdata_array[0];
          if($columname==$data_field[$kk]){
            if($action=="edit"){
              if($uitype != 4){
                array_push($update_colum, $columname."= '".str_replace("'","''",$data_value[$kk])."' ");
              }
            }else{
              if($uitype == 4){
                  
                $crm_no=$this->setModuleSeqNumber($module);
                
                $doc_no=$crm_no;
                array_push($column_add, $columname);
                array_push($value_add, "'".str_replace("'","''",$crm_no)."'");
              }else{
                array_push($column_add, $columname);
                array_push($value_add, "'".str_replace("'","''",$data_value[$kk])."'");
         
              }
            }
          }
        }
      }

      if($action=="edit"){
        if(count($update_colum)>0){
         
          $this->insertIntoActivity_Timeline_detail($field_check,$module,$this->timeline_id,$data[0],$table_name,$tab_name_index[$tab_name[$i]]);

          $sql = "update $table_name set ". implode(",",$update_colum) ." where ". $tab_name_index[$tab_name[$i]] ."= '".$this->crmid."'";
          if($this->ci->db->query($sql)){}else{$chk=1;}

          if($module=="Accounts"){
            $sql="
            SELECT account_no
            FROM aicrm_account
            where 1
            and accountid='".$this->crmid."'
            ";
            $query = $this->ci->db->query($sql);
            $data_cf_1580 = $query->result_array();
            $doc_no=$data_cf_1580[0]["account_no"];
          }//Accounts
          elseif ($module=="Products") {
            $sql="
            SELECT product_no
            FROM aicrm_products
            where 1
            and productid='".$this->crmid."'
            ";
            $query = $this->ci->db->query($sql);
            $data_cf_1580 = $query->result_array();
            $doc_no=$data_cf_1580[0]["product_no"];
          }
        }

      }else{
        if($action == "duplicate"){

          foreach($column_add as $key=>$value){
            foreach($value_add as $k=>$v){
              if($value=="location" || $value=="location_chkout" || $value=="imagename"){
                $value_add[$key] = "''";
              }

            }
          }

          $sql = "insert into $table_name(". implode(",",$column_add) .") values(". implode(",",$value_add) .")";

        }else{
          $sql = "insert into $table_name(". implode(",",$column_add) .") values(". implode(",",$value_add) .")";
        }

        if($this->ci->db->query($sql)){}else{$chk=1;}
        if($module=="Accounts"){
          if($table_name=="aicrm_account"){
            $doc_no = @$data[0]['account_no'];
            if($doc_no == ''){
              $doc_no=$this->autocd_acc();
            }
            $sql="UPDATE aicrm_account set account_no='".$doc_no."' where accountid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Contacts"){
          if($table_name=="aicrm_contactdetails"){
            $doc_no = @$data[0]['contact_no'];
            if($doc_no == ''){
              $doc_no=$this->autocd_con();
            }
            
            $sql="UPDATE aicrm_contactdetails set contact_no='".$doc_no."' where contactid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Products"){
          if($table_name=="aicrm_products"){
            $doc_no=$this->autocd_pro();
            $sql="UPDATE aicrm_products set product_no='".$doc_no."' where productid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="PriceList"){
          if($table_name=="aicrm_pricelists"){
            $doc_no=$this->autocd_price();
            $sql="UPDATE aicrm_pricelists set pricelist_no='".$doc_no."' where pricelistid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Serial"){
          if($table_name=="aicrm_serial"){
            $doc_no=$this->autocd_serial();
            $sql="UPDATE aicrm_serial set serial_no='".$doc_no."' where serialid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Sparepart"){
          if($table_name=="aicrm_sparepart"){
            $doc_no=$this->autocd_sparepart();
            $sql="UPDATE aicrm_sparepart set sparepart_no='".$doc_no."' where sparepartid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Errors"){
          if($table_name=="aicrm_errors"){
            $doc_no=$this->autocd_errors();
            $sql="UPDATE aicrm_errors set errors_no='".$doc_no."' where errorsid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Job"){
          if($table_name=="aicrm_jobs"){
            $doc_no=$this->autocd_job();
            $sql="UPDATE aicrm_jobs set job_no='".$doc_no."' where jobid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="HelpDesk"){
          if($table_name=="aicrm_troubletickets"){
            $doc_no=$this->autocd_ticket();
            $sql="UPDATE aicrm_troubletickets set ticket_no='".$doc_no."' where ticketid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Leads"){
          if($table_name=="aicrm_leaddetails"){
            $doc_no=$this->autocd_lead();
            $sql="UPDATE aicrm_leaddetails set lead_no='".$doc_no."' where leadid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Sparepartlist"){
          if($table_name=="aicrm_sparepartlist"){
            $doc_no=$this->autocd_sparepartlist();
            $sql="UPDATE aicrm_sparepartlist set sparepartlist_no='".$doc_no."' where sparepartlistid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Errorslist"){
          if($table_name=="aicrm_errorslist"){
            $doc_no=$this->autocd_Errorslist();
            $sql="UPDATE aicrm_errorslist set errorslist_no='".$doc_no."' where errorslistid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Opportunity"){
          if($table_name=="aicrm_opportunity"){
            $doc_no=$this->autocd_Opportunity();
            $sql="UPDATE aicrm_opportunity set opportunity_no='".$doc_no."' where opportunityid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Deal"){
          if($table_name=="aicrm_deal"){
            $doc_no=$this->autocd_deal();
            $sql="UPDATE aicrm_deal set deal_no='".$doc_no."' where dealid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Questionnaire"){
          if($table_name=="aicrm_questionnaire"){
            $doc_no=$this->autocd_questionnaire();
            $sql="UPDATE aicrm_questionnaire set questionnaire_no='".$doc_no."' where questionnaireid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Project"){
          if($table_name=="aicrm_project"){
            $doc_no=$this->autocd_prj();
            $sql="UPDATE aicrm_project set project_no='".$doc_no."' where projectid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Projects"){

          if($table_name=="aicrm_projects"){
            $doc_no=$this->autocd_projects();
            $sql="UPDATE aicrm_projects set projects_no='".$doc_no."' where projectsid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }
        else if($module=="Documents"){
          if($table_name=="aicrm_notes"){
            $doc_no=$this->autocd_Doc();

            $sql="UPDATE aicrm_notes set note_no='".$doc_no."' where notesid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}

            if($data[0]['jobid']!=""){
              $return_crmid = $data[0]['jobid'];
              $sql_relateDoc = "INSERT INTO aicrm_senotesrel(crmid,notesid) VALUES($return_crmid,$this->crmid)";
              if($this->ci->db->query($sql_relateDoc)){}else{$chk=1;}
            }
          }
        }else if($module=="KnowledgeBase"){
          if($table_name=="aicrm_knowledgebase"){
            // $doc_no=$this->autocd_acc();

            // $sql="UPDATE aicrm_knowledgebase set knowledgebase_no='".$doc_no."' where knowledgebaseid='".$this->crmid."'";
            // if($this->ci->db->query($sql)){}else{$chk=1;}

            if ($data[0]['knowledgebaseid']!="") {
                $return_crmid = $data[0]['knowledgebaseid'];
                $sql_relateDoc = "INSERT INTO aicrm_knowledgebase(crmid,notesid) VALUES($return_crmid,$this->crmid";
                if($this->ci->db->query($sql_relateDoc)){}else{$chk=1;}
            }
          }
        }else if($module=="Faq"){
          if($table_name=="aicrm_faq"){
            // $doc_no=$this->autocd_acc();

            // $sql="UPDATE aicrm_faq set faq_no='".$doc_no."' where faqid='".$this->crmid."'";
            // if($this->ci->db->query($sql)){}else{$chk=1;}

            if ($data[0]['faqid']!="") {
                $return_crmid = $data[0]['faqid'];
                $sql_relateDoc = "INSERT INTO aicrm_faq(crmid,notesid) VALUES($return_crmid,$this->crmid";
                if($this->ci->db->query($sql_relateDoc)){}else{$chk=1;}
            }
          }
        }else if($module=="Order"){
          if($table_name=="aicrm_order"){
             
             $doc_no=$this->autocd_Odr();
             $sql="UPDATE aicrm_order set order_no='".$doc_no."' where orderid='".$this->crmid."'";
             if($this->ci->db->query($sql)){}else{$chk=1;}

             /*$auto = str_replace("QU","",$doc_no);
             $purchase_no = 'PO'.$auto;
             $tax_no = 'IN'.$auto;
             $sql="UPDATE aicrm_order set purchase_no='".$purchase_no."',tax_no='".$tax_no."' where orderid='".$this->crmid."'";
             if($this->ci->db->query($sql)){}else{$chk=1;}*/
             
            /*if ($data[0]['orderid']!="") {
                $return_crmid = $data[0]['orderid'];
                $sql_relateDoc = "INSERT INTO aicrm_order(crmid,notesid) VALUES($return_crmid,$this->crmid";
                if($this->ci->db->query($sql_relateDoc)){}else{$chk=1;}
            }*/
          }
        }else if($module=="Competitor"){

          if($table_name=="aicrm_competitor"){
            $doc_no=$this->autocd_Competitor();
            $sql="UPDATE aicrm_competitor set competitor_no='".$doc_no."' where competitorid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }elseif($module=="Quotes"){

          if($table_name=="aicrm_quotes"){
            $doc_no=$this->autocd_Quotes();
            $sql="UPDATE aicrm_quotes set quote_no='".$doc_no."' where quoteid='".$this->crmid."'";
            //  alert($sql)   ;exit;
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }else if($module=="Questionnaireanswer"){
          if($table_name=="aicrm_questionnaireanswer"){
            $doc_no=$this->autocd_questionnaireanswer();
            $sql="UPDATE aicrm_questionnaireanswer set questionnaireanswer_no='".$doc_no."' where questionnaireanswerid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }elseif($module=="Announcement"){

          if($table_name=="aicrm_announcement"){
            $doc_no=$this->autocd_Announcement();
            $sql="UPDATE aicrm_announcement set announcement_no='".$doc_no."' where announcementid='".$this->crmid."'";
             // alert($sql)   ;exit;
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }elseif($module=="Servicerequest"){
          if($table_name=="aicrm_servicerequest"){
            $doc_no=$this->autocd_servicerequest();
            $sql="UPDATE aicrm_servicerequest set servicerequest_no='".$doc_no."' where servicerequestid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }elseif($module=="Salesinvoice"){
          if($table_name=="aicrm_salesinvoice"){
            $doc_no=$this->autocd_Salesinvoice();
            $sql="UPDATE aicrm_salesinvoice set salesinvoice_no='".$doc_no."' where salesinvoiceid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }elseif($module=="Events"){
            if($table_name=="aicrm_activity"){
                $doc_no=$this->autocd_calendar();
                $sql="UPDATE aicrm_activity set activity_no='".$doc_no."' where activityid='".$this->crmid."'";
                if($this->ci->db->query($sql)){}else{$chk=1;}
            }
        }elseif($module=="Point"){
            if($table_name=="aicrm_point"){
              $doc_no=$this->autocd_point();
              $sql="UPDATE aicrm_point set point_no='".$doc_no."' where pointid='".$this->crmid."'";
              if($this->ci->db->query($sql)){}else{$chk=1;}
            }
        }elseif($module=="Redemption"){
            if($table_name=="aicrm_redemption"){
              $doc_no=$this->autocd_Redemption();
              $sql="UPDATE aicrm_redemption set redemption_no='".$doc_no."' where redemptionid='".$this->crmid."'";
              if($this->ci->db->query($sql)){}else{$chk=1;}
            }
        }elseif($module=="SmartSms"){
            if($table_name=="aicrm_smartsms"){
              $doc_no=$this->autocd_smartsms();
              $sql="UPDATE aicrm_smartsms set smartsms_no='".$doc_no."' where smartsmsid='".$this->crmid."'";
              if($this->ci->db->query($sql)){}else{$chk=1;}
            }
        }elseif($module=="Questionnaireanswer"){
            if($table_name=="aicrm_questionnaireanswer"){
              $doc_no=$this->autocd_questionnaireanswer();
              $sql="UPDATE aicrm_questionnaireanswer set questionnaireanswer_no='".$doc_no."' where questionnaireanswerid='".$this->crmid."'";
              if($this->ci->db->query($sql)){}else{$chk=1;}
            }
        }elseif($module=="Expense"){
            if($table_name=="aicrm_expense"){
              $doc_no=$this->autocd_expense();
              $sql="UPDATE aicrm_expense set expense_no='".$doc_no."' where expenseid='".$this->crmid."'";
              if($this->ci->db->query($sql)){}else{$chk=1;}
            }
        }else if($module=="Samplerequisition"){
          if($table_name=="aicrm_samplerequisition"){
            $doc_no=$this->autocd_samplerequisition();
            $sql="UPDATE aicrm_samplerequisition set samplerequisition_no='".$doc_no."' where samplerequisitionid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }else if($module=="Marketingtools"){
          if($table_name=="aicrm_marketingtools"){
            $doc_no=$this->autocd_marketingtools();
            $sql="UPDATE aicrm_marketingtools set marketingtools_no='".$doc_no."' where marketingtoolsid='".$this->crmid."'";
            if($this->ci->db->query($sql)){}else{$chk=1;}
          }
        }

      }
    }
  }

  if($action=="edit" || $action=="add" || $action=="duplicate"){

    $table_name = $tab_name[1];
    $column_name = $tab_name_index[$table_name];
    
    $no="";
    $name="";
    $data = $this->get_name($module,$this->crmid,$table_name,$column_name);
    
    if($data['name']!=null){
      $name = $data['name'];
    }

    if ($data['no']!=null) {
      $no = $data['no'];
    }

  }
  
  return array($chk,$this->crmid,$doc_no,$name,$no);
}

public function insertIntoActivity_Timeline($module, $fileid = '', $action, $userid, $record=array())
{
    global $adb;
    global $current_user;
    global $log;
    
    $date_var = date('Y-m-d H:i:s');
    $c_userid = $userid;
    $tabid = $this->Get_TabID($module);
    
    if ($module == 'Events') {
      $module = 'Calendar';
    }
    
    if ($action == 'edit') {
      
      $this->ci->db->select_max('id');
      $sql_max = $this->ci->db->get('aicrm_activity_timeline');
      
      if ($sql_max->num_rows() > 0){
        $maxid = $sql_max->result_array();
        $timeline_id = ($maxid[0]['id']+1);
      }else{
        $timeline_id = 1;
      }

      $data_t = array(
          'id' => $timeline_id,
      );
      $this->ci->db->update('aicrm_activity_timeline_seq', $data_t);

      $data = array(
        'id'=>$timeline_id,
        'tabid'=>$tabid,
        'module'=>$module,
        'crmid'=>$fileid,
        'action'=>$action,
        'userid'=>$c_userid,
        'createdtime'=>$date_var,
      );
      $this->ci->db->insert('aicrm_activity_timeline',$data);
      $this->timeline_id = $timeline_id;


      $this->ci->db->select('*');
      $this->ci->db->where('tabid', $tabid);
      $this->ci->db->where('presence != ', 1);
      $columnname = array('smownerid', 'description');
      $this->ci->db->where_in('columnname', $columnname);
      $sql_crmentity = $this->ci->db->get('aicrm_field');
      
      if ($sql_crmentity->num_rows() > 0){
        $colum_crmentity = $sql_crmentity->result_array();
        $fieldname = array();
        foreach ($colum_crmentity as $key => $value) {
          $fieldname[$key]['fieldid'] = $value['fieldid'];
          $fieldname[$key]['columnname'] = $value['columnname'];
        }
      }else{
        $fieldname = array();
      }
      
      //Get Data CRM
      $this->ci->db->select('smownerid,description');
      $this->ci->db->where('crmid', $fileid);
      $sql_data = $this->ci->db->get('aicrm_crmentity');
      $data_crmentity = $sql_data->result_array();
      
      foreach ($fieldname as $key => $value) {
        
        if(!empty($record[$value['columnname']])){
          
          if($data_crmentity[0][$value['columnname']] != $record[$value['columnname']]){
            
            $data_detail = array(
              'activitytimelineid'=>$timeline_id,
              'fieldid'=> $value['fieldid'],
              'sequence'=>'',
              'old_value'=> $data_crmentity[0][$value['columnname']],
              'new_value'=> $record[$value['columnname']]
            );
            
            $this->ci->db->insert('aicrm_activity_timeline_detail',$data_detail);

          }
        }
      }

    } else {

        $this->ci->db->select_max('id');
        $sql_max = $this->ci->db->get('aicrm_activity_timeline');
        
        if ($sql_max->num_rows() > 0){
          $maxid = $sql_max->result_array();
          $timeline_id = ($maxid[0]['id']+1);
        }else{
          $timeline_id = 1;
        }

        $data_t = array(
          'id' => $timeline_id,
        );
        $this->ci->db->update('aicrm_activity_timeline_seq', $data_t);

        $data = array(
          'id'=>$timeline_id,
          'tabid'=>$tabid,
          'module'=>$module,
          'crmid'=>$fileid,
          'action'=>'create',
          'userid'=>$c_userid,
          'createdtime'=>$date_var,
        );
        $this->ci->db->insert('aicrm_activity_timeline',$data);
        $this->timeline_id = $timeline_id;
    }

}

public function insertIntoActivity_Timeline_detail($field_check=array(),$module,$timeline_id,$recode=array(),$table_name,$fieldid){

  $this->ci->db->select('*');
  $this->ci->db->where($fieldid, $this->crmid);
  $sql_table = $this->ci->db->get($table_name);
  
  if ($sql_table->num_rows() > 0){
    $data_table = $sql_table->result_array();
          
    foreach ($field_check as $key => $value) {
      
      if(!empty($recode[$value['fieldname']])){
        
        if($recode[$value['fieldname']] != $data_table[0][$value['fieldname']]){

          $data_detail = array(
            'activitytimelineid'=>$timeline_id,
            'fieldid'=> $value['fieldid'],
            'sequence'=>'',
            'old_value'=> $data_table[0][$value['fieldname']],
            'new_value'=> $recode[$value['fieldname']]
          );
          
          $this->ci->db->insert('aicrm_activity_timeline_detail',$data_detail);

        }
      }
    }
  }
  
}

public function get_name($module,$id,$table_name,$column_name){

  $length = strlen($column_name);
  $string = $column_name;
  $name = substr($string,0,$length-2);
  $sql_name = '';
  $fieldname_no = "";
  if($module == "Accounts"){
    $fieldname = "accountname";
    $fieldname_no = $name."_no";
  }else if($module == "Unit"){
    $fieldname = "unit_name";
    $fieldname_no = $name."_no";
  }else if($module == "Floor"){
      $fieldname = "floor_name";
      $fieldname_no = $name."_no";
  }else if($module == "Contract"){
    $fieldname = "contract_name";
    $fieldname_no = $name."_no";
  }else if($module == "Contacts" ||$module == "Leads"){
    $fieldname = "CONCAT(firstname,' ',lastname)";
    $fieldname_no = $name."_no";
  }else if($module == "Job" || $module == "Sparepartlist" || $module == "Errorslist" || $module == "Serial" || $module == "Errors" || $module == "Sparepart"
  || $module == "Opportunity" || $module == "Competitor" || $module == "Quotation" || $module == "Deal" || $module == "Questionnaire" || $module == "Projects" || $module == "Voucher"
  || $module == "Faq" || $module == "Announcement"){
    $fieldname = $name."_name";
    $fieldname_no = $name."_no"; 
  }else if($module == "Documents" ){
    $fieldname = "title";
    $fieldname_no = "note_no";
  }else if($module == "Sales Visit" || $module=="Calendar" || $module=="Events"){
    $fieldname = $name."type";
    $fieldname_no = $name."_no";
    $module="Calendar";
  }else if($module == "HelpDesk" ){
    $fieldname = "title";
    $fieldname_no = "ticket_no";
  }else if($module =="KnowledgeBase"){
    $fieldname = "knowledgebase_name";
    $fieldname_no = $name."_no";
  } elseif ($module =="Faq") {
    $fieldname = "faq_name";
    $fieldname_no = $name."_no";
  }elseif ($module =="Contacts") {
    $fieldname = "account_name";
    $fieldname_no = $name."_no";
  }elseif ($module =="Order") {
    $fieldname = "order_name";
    $fieldname_no = $name."_no";
  }elseif ($module =="Quotes") {
    $fieldname = "quote_name";
    $fieldname_no = "quote_no";
  }elseif ($module =="Voucher") {
    $fieldname = $name."_name";
    $fieldname_no = "voucher_no";
  }elseif ($module =="Salesinvoice") {
    $fieldname = $name."_name";
    $fieldname_no = "salesinvoice_no";
  }elseif ($module =="Servicerequest") {
    $fieldname = "servicerequest_name";
    $fieldname_no = "servicerequest_no";
  }elseif ($module =="Questionnaireanswer") {
    $fieldname = "questionnaireanswer_name";
    $fieldname_no = "questionnaireanswer_no";
  }elseif ($module =="Point") {
    $fieldname = "point_name";
    $fieldname_no = "point_no";
  }elseif ($module =="Redemption") {
    $fieldname = $name."_name";
    $fieldname_no = "redemption_no";
  }elseif ($module =="SmartSms") {
    $fieldname = $name."_name";
    $fieldname_no = "smartsms_no";
  }elseif ($module =="Questionnaireanswer") {
    $fieldname = $name."_name";
    $fieldname_no = "questionnaireanswer_no";
  }elseif ($module =="Expense") {
    $fieldname = "expense_name";
    $fieldname_no = "expense_no";
  }elseif ($module =="Samplerequisition") {
    $fieldname = "samplerequisition_name";
    $fieldname_no = "samplerequisition_no";
  }elseif ($module == "PriceList"){
    $fieldname = "pricelist_name";
    $fieldname_no = "pricelist_no";
  }elseif ($module == "Marketingtools"){
    $fieldname = "marketingtools_name";
    $fieldname_no = "marketingtools_no";
  }else{
    $fieldname = $name."name";
    $fieldname_no = $name."_no";
  }
  if($module == "Users" || $module == "Profile" ){
    $table_name = "aicrm_users";
    $fieldname = "user_name";
    $column_name = "id";
    $fieldname_no = "";
  }

  if($module == "Users" || $module == "Profile" /*|| $module == "Calendar"*/){
    $sql_name = " select ".$fieldname."
    from ".$table_name."
    where  ".$column_name."='".$id."' ";
  }elseif($module == "Quotes"){
    $sql_name = " select ".$fieldname.",".$fieldname_no."
    from ".$table_name."
    where  quoteid='".$id."' ";
  }else{
    $sql_name = " select ".$fieldname.",".$fieldname_no."
    from ".$table_name."
    where  ".$column_name."='".$id."' ";
  }

  $query1 = $this->ci->db->query($sql_name);

  $result=$query1->result_array();

  $data['name'] = @$result[0][$fieldname];
  $data['no'] =@$result[0][$fieldname_no];

  if($data['name'] == '' ||  $data['name'] == null ){
    $data['name'] = "";
  }
  else if($data['no'] == '' || $data['no'] == null ){
    $data['no'] = "";
  }

  return $data;

}

public function autocd_questionnaireanswer($module="Questionnaireanswer",$num_running="4",$pre="QUE"){

  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd = $prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_expense($module="Expense",$num_running="6",$pre="EXP"){

  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd = $prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_samplerequisition($module="Samplerequisition",$num_running="4",$pre="SR"){

  $yy=substr(date("Y"), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd = $prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_marketingtools($module="Marketingtools",$num_running="4",$pre="MKTT"){

  $yy=substr(date("Y"), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd = $prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_smartsms($module="SmartSms",$num_running="4",$pre="SMS"){

  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd = $prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_Redemption($module="Redemption",$num_running="6",$pre="RDT"){

  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd = $prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_point($module="Point",$num_running="6",$pre="POINT"){

  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd = $prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_acc($module="Accounts",$num_running="6",$pre="ACC"){

  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }
  
  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_con($module="Contacts",$num_running="6",$pre="CON"){
  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }
  
  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_pro($module="Products",$num_running="6",$pre="PRO"){
  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }
  
  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_price($module="PriceList",$num_running="6",$pre="PRI"){
  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;
  
  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();

  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }
  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_serial($module="Serial",$num_running="1",$pre="SEL"){
  $prefix =$pre;
  $sql="
  SELECT cur_id
  FROM aicrm_modentity_num
  where active=1
  and semodule='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['cur_id'];
    $running=$running+1;
  }else{
    $running=1;
  }
  $sql="update aicrm_modentity_num set cur_id='".$running."',prefix='".$prefix."' where active=1 and semodule='".$module."' and prefix='".$prefix."' ";
  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}


public function autocd_projects($module="Projects",$num_running="6",$pre="POD"){
  $yy=substr((date("Y") + 543), -2).date('m');
  $prefix =$pre.$yy;
  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }
  
  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_job($module="Job",$num_running="6",$pre="JOB"){

  $yy = substr((date("Y") + 543), -2).date('m') ;
  $prefix =$pre.$yy;
  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
  }else{
    $running=1;
  }
  $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

/*public function autocd_acc($module="Accounts",$num_running="6",$pre="ACC"){

  $yy=substr((date("Y")+543), -2) ;
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }
  
  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}*/
public function autocd_sparepart($module="Sparepart",$num_running="2",$pre="SPP"){

  $prefix =$pre;
  $sql="
  SELECT cur_id
  FROM aicrm_modentity_num
  where active=1
  and semodule='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();

  if(count($data)>0){
    $running = $data[0]['cur_id'];
    $running=$running+1;
  }else{
    $running=1;
  }
  $sql="update aicrm_modentity_num set cur_id='".$running."',prefix='".$prefix."' where active=1 and semodule='".$module."' and prefix='".$prefix."' ";
  $this->ci->db->query($sql);
  $cd=$prefix."".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_errors($module="Errors",$num_running="1",$pre="ERR"){
  $prefix =$pre;
  $sql="
  SELECT cur_id
  FROM aicrm_modentity_num
  where active=1
  and semodule='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();

  if(count($data)>0){
    $running = $data[0]['cur_id'];
    $running=$running+1;
  }else{
    $running=1;
  }
  $sql="update aicrm_modentity_num set cur_id='".$running."',prefix='".$prefix."' where active=1 and semodule='".$module."' and prefix='".$prefix."' ";
  $this->ci->db->query($sql);
  $cd=$prefix."".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_sparepartlist($module="Sparepartlist",$num_running="3",$pre="SPL"){
  $prefix =$pre;
  $sql="
  SELECT cur_id
  FROM aicrm_modentity_num
  where active=1
  and semodule='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();

  if(count($data)>0){
    $running = $data[0]['cur_id'];
    $running=$running+1;
  }else{
    $running=1;
  }
  $sql="update aicrm_modentity_num set cur_id='".$running."',prefix='".$prefix."' where active=1 and semodule='".$module."' and prefix='".$prefix."'";
  $this->ci->db->query($sql);
  $cd=$prefix."".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_errorslist($module="Errorslist",$num_running="3",$pre="ERL"){
  $prefix =$pre;
  $sql="
  SELECT cur_id
  FROM aicrm_modentity_num
  where active=1
  and semodule='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();

  if(count($data)>0){
    $running = $data[0]['cur_id'];
    $running=$running+1;
  }else{
    $running=1;
  }
  $sql="update aicrm_modentity_num set cur_id='".$running."',prefix='".$prefix."' where active=1 and semodule='".$module."' and prefix='".$prefix."' ";
  $this->ci->db->query($sql);
  $cd=$prefix."".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_deal($module="Deal",$num_running="6",$pre="DEL"){
  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }
  
  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_questionnaire($module="Questionnaire",$num_running="4",$pre="QUE"){
  $yy=substr((date("Y")+543), -2);
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();

  //alert(count($data));exit;
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_prj($module="Project",$num_running="4",$pre="PRO"){
  $yy=substr((date("Y")+543), -2) ;
  $prefix =$pre.$yy;
  $sql="
  SELECT cur_id
  FROM aicrm_modentity_num
  where active=1
  and semodule='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['cur_id'];
    $running=$running+1;
  }else{
    $running=1;
  }
  $sql="update aicrm_modentity_num set cur_id='".$running."',prefix='".$prefix."' where active=1 and semodule='".$module."' and prefix='".$prefix."' ";
  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}


public function autocd_ticket($module="HelpDesk",$num_running="6",$pre="CASE-"){
  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

/*public function autocd_ticket($module="HelpDesk",$num_running="4",$pre="CASE"){
  $yy=substr((date("Y")+543), -2) ;
  $prefix =$pre.$yy."-";
  $sql="
  SELECT cur_id
  FROM aicrm_modentity_num
  where active=1
  and semodule='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['cur_id'];
    $running=$running+1;
  }else{
    $running=1;
  }
  $sql="update aicrm_modentity_num set cur_id='".$running."',prefix='".$prefix."' where active=1 and semodule='".$module."' ";
  $this->ci->db->query($sql);
  $cd=$prefix."".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}*/

public function autocd_lead($module="Leads",$num_running="6",$pre="LED"){

  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }
  
  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_Doc($module="Documents",$num_running="4",$pre="DOC"){
  $prefix =$pre;
  $sql="
  SELECT cur_id
  FROM aicrm_modentity_num
  where active=1
  and semodule='".$module."'
  and prefix='".$prefix."'
  ";
  // alert($sql);exit;

  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['cur_id'];
    $running=$running+1;
  }else{
    $running=1;
  }
  $sql="update aicrm_modentity_num set cur_id='".$running."',prefix='".$prefix."' where active=1 and semodule='".$module."' and prefix='".$prefix."' ";
  $this->ci->db->query($sql);
  $cd=$prefix."".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_Opportunity($module="Opportunity",$num_running="4",$pre="OPP"){
  $yy=substr((date("Y")+543), -2) ;
  $prefix =$pre.$yy."-";
  // $prefix =$pre;
  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();

  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
  }else{
    $running=1;
  }
  $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  $this->ci->db->query($sql);
  $cd=$prefix."".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_Odr($module="Order",$num_running="4",$pre="QU"){

  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd = $prefix."".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_Competitor($module="Competitor",$num_running="6",$pre="COM"){
  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }
  $this->ci->db->query($sql);
  $cd=$prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_Salesinvoice($module="Salesinvoice",$num_running="6",$pre="SIV"){

  $yy=substr((date("Y")+543), -2).date('m');
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd = $prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_Quotes($module="Quotes",$num_running="6",$pre="QUO"){

  $yy=substr((date("Y")+543), -2);
  $prefix =$pre.$yy.date('m');

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."'";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd = $prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_Announcement($module="Announcement",$num_running="4",$pre="ANNT"){

  $yy=substr((date("Y")+543), -2);
  $prefix =$pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd = $prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}


public function autocd_servicerequest($module="Servicerequest",$num_running="6",$pre="SVR"){
  $yy = substr((date("Y") + 543), -2). date('m');
  $prefix = $pre.$yy;

  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  }else{
    $running=1;
    $sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
  }

  $this->ci->db->query($sql);
  $cd = $prefix."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_calendar($module = "Calendar", $num_running = "6", $pre = "VISIT")
{
  $yy = substr((date("Y") + 543), -2). date('m');
  $prefix = $pre . $yy;

  $sql = "SELECT running FROM ai_running_doc where 1 and module='" . $module . "' and prefix='" . $prefix . "' ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  
  if (count($data) > 0) {
      $running = $data[0]['running'];
      $running = $running + 1;
      $sql = "update ai_running_doc set running='" . $running . "',prefix='" . $prefix . "' where 1 and module='" . $module . "' and prefix='".$prefix."' ";
  } else {
      $running = 1;
      $sql = "insert into ai_running_doc(module,running,prefix)values('" . $module . "','" . $running . "','" . $prefix . "'); ";
  }

  $this->ci->db->query($sql);
  $cd = $prefix . "-" . str_pad($running, $num_running, "0", STR_PAD_LEFT);
  return $cd;
}

/*public function autocd_Odr($module="Order",$num_running="1",$pre="ODR"){
  $prefix =$pre;
  $sql="
  SELECT cur_id
  FROM aicrm_modentity_num
  where active=1
  and semodule='".$module."'
  and prefix='".$prefix."'
  ";
  // alert($sql);exit;

  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['cur_id'];
    $running=$running+1;
  }else{
    $running=1;
  }
  $sql="update aicrm_modentity_num set cur_id='".$running."',prefix='".$prefix."' where active=1 and semodule='".$module."' ";
  $this->ci->db->query($sql);
  $cd=$prefix."".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}*/

public function autocd_case($cf_2249){
  if($cf_2249=="Call Center"){
    $prefix="CALL";
  }else if($cf_2249=="Sena We Care"){
    $prefix="SNWE";
  }
  $sql="
  SELECT cur_id
  FROM aicrm_modentity_num
  where active=1
  and semodule='HelpDesk'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['cur_id'];
    $running=$running+1;
    $sql="update aicrm_modentity_num set cur_id='".$running."',prefix='".$prefix."' where active=1 and semodule='HelpDesk' ";
    $this->ci->db->query($sql);
  }
  $yy=date('Y')+543;
  $mm=date('m');
  $dd=date('d');
  $cd=$prefix.$yy.$mm.$dd."-".str_pad($running,5,"0", STR_PAD_LEFT);
  return $cd;
}

public function autocd_runing($module="ServiceRequest",$num_running="4",$pre="SR"){
  $yy=date('y');
  $prefix =$pre.$yy.date('m');
  $dd=date('d');
  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='".$module."'
  and prefix='".$prefix."'
  ";

  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
  }else{
    $running=1;
  }
  $sql="update ai_running_doc set running='".$running."',prefix='".$prefix."' where 1 and module='".$module."' and prefix='".$prefix."' ";
  $this->ci->db->query($sql);

  $cd=$prefix.$dd."-".str_pad($running,$num_running,"0", STR_PAD_LEFT);
  return $cd;
}
public function autocd_so($cf_1603){
  if($cf_1603=="Telesales"){
    $prefix="S";
  }else if($cf_1603=="Event"){
    $prefix="B";
  }else if($cf_1603=="E-commerce"){
    $prefix="E";
  }
  $sql="
  SELECT running
  FROM ai_running_doc
  where 1
  and module='SalesOrder'
  and prefix='".$prefix."'
  ";
  $query = $this->ci->db->query($sql);
  $data = $query->result_array();
  if(count($data)>0){
    $running = $data[0]['running'];
    $running=$running+1;
    $sql="update ai_running_doc set running='".$running."' where 1 and module='SalesOrder' and prefix='".$prefix."' and prefix='".$prefix."'";
    $this->ci->db->query($sql);
  }
  $yy=date('Y')+543;
  $n_year=substr($yy,2,2);
  $dd=date('m');
  $cd=$prefix.$n_year.$dd.str_pad($running,5,"0", STR_PAD_LEFT);
  return $cd;
}
public function insert_inv($data_inv,$net_total,$grand_total,$shipping_value,$discount_amount,$accountid,$doc_no){
  $sql="delete from aicrm_inventoryproductrel where id='".$this->crmid."'";
  $this->ci->db->query($sql);
  for($i=0;$i<count($data_inv);$i++){
    if($data_inv[$i]["pro_type"]=="P" || $data_inv[$i]["pro_type"]=="S"){
      $sql="
      SELECT crmid,setype FROM aicrm_crmentity  where 1 and crmid='".$data_inv[$i]["productid"]."'
      ";

      $query = $this->ci->db->query($sql);
      $data_chk = $query->result_array();
      if(count($data_chk)>0){
        $setype=$data_chk[0]['setype'];
        if($setype=="Promotion"){
          $sql="
          SELECT
          aicrm_inventory_protab3.id as promotion_id
          ,aicrm_inventory_protab3.disc_total
          ,aicrm_inventory_protab3.disc_discount
          ,aicrm_inventory_protab3.disc_net
          ,aicrm_products.productid
          ,concat(cf_1124,'|##|',cf_1641,'|##|',productname) as productname
          ,aicrm_inventory_protab3_dtl.quantity
          ,aicrm_inventory_protab3_dtl.listprice
          ,aicrm_inventory_protab3_dtl.uom
          ,'S' as pro_type

          FROM aicrm_inventory_protab3
          LEFT JOIN aicrm_inventory_protab3_dtl ON aicrm_inventory_protab3_dtl.id = aicrm_inventory_protab3.id
          LEFT JOIN aicrm_products ON aicrm_products.productid = aicrm_inventory_protab3_dtl.productid
          left join aicrm_productcf on aicrm_productcf.productid=aicrm_products.productid
          WHERE 1
          AND aicrm_inventory_protab3.id ='".$data_inv[$i]["productid"]."'
          ";

          $query = $this->ci->db->query($sql);
          $data_dtl = $query->result_array();
          if(count($data_dtl)>0){
            for($k=0;$k<count($data_dtl);$k++){
              $sql="insert into aicrm_inventoryproductrel( id,productid,sequence_no,quantity,listprice,comment,pro_type,uom,promotion_id)
              values('".$this->crmid."','".$data_dtl[$k]["productid"]."','".($k+1)."','".($data_inv[$i]["qty"]*$data_dtl[$k]["quantity"])."','".$data_dtl[$k]["listprice"]."','".$data_dtl[$k]["productname"]."','".$data_dtl[$k]["pro_type"]."','".$data_dtl[$k]["uom"]."','".$data_dtl[$k]["promotion_id"]."')
              ";

              $this->ci->db->query($sql);
            }
            $sql="
            SELECT discount_amount FROM aicrm_salesorder  where 1 and salesorderid='".$this->crmid."'
            ";
            $query = $this->ci->db->query($sql);
            $data_chk = $query->result_array();

            $net_total=($data_inv[$i]["qty"]*$data_dtl[0]["disc_total"]);
            $discount_amount=$data_chk[0]["discount_amount"]+($data_inv[$i]["qty"]*$data_dtl[0]["disc_discount"]);
            $grand_total=$net_total-$discount_amount;
            $shipping_value=$shipping_value;
          }
        }else{
          $sql="
          select
          concat(cf_1124,'|##|',cf_1641,'|##|',productname) as productname
          ,usageunit
          from aicrm_products
          left join aicrm_productcf on aicrm_productcf.productid=aicrm_products.productid
          where 1
          and aicrm_products.productid='".$data_inv[$i]["productid"]."'
          ";
          $query = $this->ci->db->query($sql);
          $data_chk = $query->result_array();
          $sql="insert into aicrm_inventoryproductrel( id,productid,sequence_no,quantity,listprice,comment,pro_type,uom,promotion_id)
          values('".$this->crmid."','".$data_inv[$i]["productid"]."','".($i+1)."','".$data_inv[$i]["qty"]."','".$data_inv[$i]["listprice"]."','".$data_chk[0]["productname"]."','".$data_inv[0]["pro_type"]."','".$data_chk[0]["usageunit"]."','".$data_inv[0]["promotion_id"]."')
          ";
          $this->ci->db->query($sql);
        }
      }
    }//chk if
    else if($data_inv[$i]["pro_type"]=="PM"){//PREMIUM
      $date=date('Y-m-d H:i:s');
      $sql=" select (id+1) as id from aicrm_crmentity_seq ";
      $query = $this->ci->db->query($sql);
      $data_chk = $query->result_array();
      $cid=$data_chk[0]['id'];

      $sql = "insert into  aicrm_crmentity  (crmid,smcreatorid,smownerid,setype,createdtime,modifiedtime,version,presence,deleted,description)  values ('".$cid."','1','1','Redemption','".$date."','".$date."','0','1','0','Redemption By SaleOrder');";
      $this->ci->db->query($sql);
      $sql = "update  aicrm_crmentity_seq set id='".$cid."';";
      $this->ci->db->query($sql);
      $sql=" select prefix,cur_id from aicrm_modentity_num  where num_id='28'";
      $query = $this->ci->db->query($sql);
      $data_chk = $query->result_array();
      $proid=$data_chk[0]['prefix'].$data_chk[0]['cur_id'];

      $sql="
      insert into aicrm_redemption (redemptionid,redemption_no,redemption_name,accountid,premiumid)values('".$cid."','".$proid."','Redemption By SaleOrder','".$accountid."','".$data_inv[$i]["productid"]."')
      ";
      $this->ci->db->query($sql);
      $sql="
      insert into aicrm_redemptioncf (redemptionid,cf_1430,cf_1431,cf_1466,cf_1754,cf_1755,cf_1758)values('".$cid."','".$data_inv[$i]["listprice"]."','1','".date('Y-m-d')."','".$this->crmid."','".$doc_no."',1)
      ";
      $this->ci->db->query($sql);

      $sql = "update  aicrm_modentity_num set cur_id='".($data_chk[0]['cur_id']+1)."' where num_id='28';";
      $this->ci->db->query($sql);
    }
  }//for

  $sql="update aicrm_salesorder set
  subtotal =".$net_total."
  ,discount_amount =".$discount_amount."
  ,total =".$grand_total."
  ,s_h_amount =".$shipping_value."
  where salesorderid =".$this->crmid."
  ";
  $this->ci->db->query($sql);//update so

}

public function savequestion_dtl($a_param=array(),$id="")
{

  if(empty($a_request)) return null;
  if(empty($id)) return null;

}
public function get_building_id($building_code){
  $sql_select="select  aicrm_crmentity.crmid,aicrm_buildingcf.cf_1059,aicrm_building.building_no  ";
  $sql_from=$this->Get_Query("Building");
  $sql_where="
  and cf_2373='".$building_code."'
  ";
  $sql=$sql_select.$sql_from.$sql_where;
  $query = $this->ci->db->query($sql);
  $data_building=$query->result_array();
  if(count($data_building)>0){
    $cf_1059=$data_building[0]["cf_1059"];
    $building_id=$data_building[0]["crmid"];
  }else{
    $cf_1059="";
    $building_id="";
  }
  return array($cf_1059,$building_id);
}
public function get_premium_id($premium_code){
  $sql_select="select  aicrm_crmentity.crmid  ";
  $sql_from=$this->Get_Query("Premium");
  $sql_where="
  and cf_2423='".$premium_code."'
  ";
  $sql=$sql_select.$sql_from.$sql_where;
  $query = $this->ci->db->query($sql);
  $data_building=$query->result_array();
  if(count($data_building)>0){
    $crmid=$data_building[0]["crmid"];
  }else{
    $crmid="";
  }
  return $crmid;
}
public function get_product_id($BRANCHCODE,$SUBBUCODE,$PRODUCTTYPECODE,$PRODUCTCODE){
  $sql_select="select  aicrm_crmentity.crmid  ";
  $sql_from=$this->Get_Query("Products");
  $sql_where="
  WHERE aicrm_crmentity.deleted = 0
  and aicrm_productcf.cf_2399='".trim($BRANCHCODE)."'
  and aicrm_productcf.cf_2058='".trim($SUBBUCODE)."'
  and aicrm_productcf.cf_2393='".trim($PRODUCTTYPECODE)."'
  and aicrm_productcf.cf_2053='".trim($PRODUCTCODE)."'
  ";
  $sql=$sql_select.$sql_from.$sql_where;

  $query = $this->ci->db->query($sql);
  $data_building=$query->result_array();
  if(count($data_building)>0){
    $crmid=$data_building[0]["crmid"];
  }else{
    $crmid="";
  }
  return $crmid;
}
public function get_product_id2($BRANCHCODE,$PRODUCTCODE){
  $sql_select="select  aicrm_crmentity.crmid  ";
  $sql_from=$this->Get_Query("Products");
  $sql_where="
  WHERE aicrm_crmentity.deleted = 0
  and aicrm_productcf.cf_2399='".trim($BRANCHCODE)."'
  and aicrm_productcf.cf_2061 ='".trim($PRODUCTCODE)."'
  ";
  $sql=$sql_select.$sql_from.$sql_where;
  $query = $this->ci->db->query($sql);
  $data_building=$query->result_array();
  if(count($data_building)>0){
    $crmid=$data_building[0]["crmid"];
  }else{
    $crmid="";
  }
  return $crmid;
}
public function get_account_id($account_code){
  $sql_select="select  aicrm_crmentity.crmid ,aicrm_account.accountname ";
  $sql_from=$this->Get_Query("Accounts");
  $sql_where="
  and cf_2091='".$account_code."'
  ";
  $sql=$sql_select.$sql_from.$sql_where;
  $query = $this->ci->db->query($sql);
  $data_building=$query->result_array();
  if(count($data_building)>0){
    $crmid=$data_building[0]["crmid"];
    $accountname=$data_building[0]["accountname"];
  }else{
    $crmid="";
    $accountname="";
  }
  return array($crmid,$accountname);
}
public function get_booking_id($CONTRACTID){
  $sql_select="select  aicrm_crmentity.crmid,aicrm_booking.accountid  ";
  $sql_from=$this->Get_Query("Booking");
  $sql_where="
  and cf_2627='".$CONTRACTID."'
  ";
  $sql=$sql_select.$sql_from.$sql_where;
  $query = $this->ci->db->query($sql);
  $data_building=$query->result_array();
  if(count($data_building)>0){
    $crmid=$data_building[0]["crmid"];
    $accountid=$data_building[0]["accountid"];
  }else{
    $crmid="";
    $accountid="";
  }
  return array($crmid,$accountid);
}
public function get_booking_id_V2($branch_code,$booking_code,$sub_booking_code,$account_code,$product_code){
  $sql_select="select  aicrm_crmentity.crmid,aicrm_booking.accountid  ";
  $sql_from=$this->Get_Query("Booking");
  $sql_where="
  and cf_2445='".$branch_code."'

  and cf_2460='".$booking_code."'
  and cf_2461='".$sub_booking_code."'
  /*and cf_2521='".$account_code."' */
  and cf_2456='".$product_code."'
  ";
  $sql=$sql_select.$sql_from.$sql_where;
  $query = $this->ci->db->query($sql);
  $data_building=$query->result_array();
  if(count($data_building)>0){
    $crmid=$data_building[0]["crmid"];
    $accountid=$data_building[0]["accountid"];
  }else{
    $crmid="";
    $accountid="";
  }
  return array($crmid,$accountid);
}
public function query_sql($sql){
  $this->ci->db->query($sql);
}

public function deleteRelation($table_name,$tab_index,$crmid)
{

  $check_query = "select * from $table_name where ". $tab_index[$table_name] ."='".$crmid."'";
  $query = $this->ci->db->query($check_query);
  $data_check=$query->result_array();
  if(!empty($data_check)){
    $del_query = "DELETE from $table_name where ". $tab_index[$table_name] ."='".$crmid."'";
    $this->ci->db->query($del_query);
  }
}
public function insertRelation($table_name,$fieldrelation,$crmid,$parentid)
{
  $sql = "insert into $table_name ($fieldrelation,activityid) values('".$parentid."','".$crmid."') ";
  $this->ci->db->query($sql);

}

private function getCrmEntityData($crmID)
  {
    $sql = $this->ci->db->query("SELECT * FROM aicrm_crmentity WHERE crmid=".$crmID);
    $result = $sql->row_array();

    $res = [];

    if(!empty($result)){
      $moduleSelect = $result['setype'];
      $rsData = [];
      switch ($result['setype']) {
        case 'Deal':
          $sql = $this->ci->db->get_where('aicrm_deal', ['dealid'=>$crmID]);
          $rs = $sql->row_array();
          $parentID = $rs['parentid'];

          $entityData = $this->getCrmEntityData($parentID);
          if($entityData['moduleSelect'] == 'Accounts'){
            $sql = $this->ci->db->get_where('aicrm_account', ['accountid'=>$parentID]);
            $rsData = $sql->row_array();
            $rsData['module'] = $entityData['moduleSelect'];
            $rsData['id'] = $parentID;
            $value = $rsData['accountid'];
            $name = $rsData['accountname'];
          }else if($entityData['moduleSelect'] == 'Contacts'){
            $sql = $this->ci->db->get_where('aicrm_contactdetails', ['contactid'=>$parentID]);
            $rsData = $sql->row_array();
            $rsData['module'] = $entityData['moduleSelect'];
            $rsData['id'] = $parentID;
            $value = $rsData['contactid'];
            $name = $rsData['contactname'];
          }else if($entityData['moduleSelect'] == 'Leads'){
            $sql = $this->ci->db->get_where('aicrm_leaddetails', ['leadid'=>$parentID]);
            $rsData = $sql->row_array();
            $rsData['module'] = $entityData['moduleSelect'];
            $rsData['id'] = $parentID;
            $value = $rsData['leadid'];
            $name = $rsData['leadname'];
          }
          break;
      }

      $res = [
        'moduleSelect' => $moduleSelect,
        'value' => $value,
        'name' => $name,
        'rsData' => $rsData
      ];
    }

    return $res;
  }

  public function get_terms_conditions($filename=''){

    $list_query = "select * from aicrm_inventory_tandc";
    $query = $this->ci->db->query($list_query);
    $data = $query->result_array();
    if($filename == 'terms_conditions'){
      return $data[0]['tandc'];
    }else if($filename == 'quote_termcondition'){
      return $data[1]['tandc'];
    }else{
      return '';
    }
  }

}