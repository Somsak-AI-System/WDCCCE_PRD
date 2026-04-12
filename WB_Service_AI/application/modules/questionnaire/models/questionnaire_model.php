<?php
class Questionnaire_model extends CI_Model
{
  var $ci;

  /**
   */
  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
    $this->_limit = "0";
  }

   function get_total($a_condition=array())
  {
  	try {
  		if (!empty($a_condition)) {
  			$this->db->where($a_condition);
  		}else{
  			$a_condition["aicrm_crmentity.setype"] = "Smartquestionnaire";
  			$a_condition["aicrm_crmentity.deleted"] = "0";
  		}

  		$this->db->select("count(DISTINCT aicrm_smartquestionnaire.smartquestionnaireid) as total");
        $this->db->join('aicrm_smartquestionnairecf', 'aicrm_smartquestionnairecf.smartquestionnaireid = aicrm_smartquestionnaire.smartquestionnaireid');
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_smartquestionnaire.smartquestionnaireid');
       /* $this->db->join('aicrm_products', 'aicrm_products.productid = aicrm_smartquestionnaire.product_id');
        $this->db->join('aicrm_productcf', 'aicrm_productcf.productid = aicrm_smartquestionnaire.product_id');
        $this->db->join('aicrm_building', 'aicrm_building.buildingid = aicrm_products.buildingid');
        $this->db->join('aicrm_buildingcf', 'aicrm_products.buildingid = aicrm_buildingcf.buildingid');
        $this->db->join('aicrm_branchs', 'aicrm_branchs.branchid = aicrm_buildingcf.cf_1059');*/
  		$query = $this->db->get('aicrm_smartquestionnaire');

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
  	try {
  		$a_condition["aicrm_crmentity.setype"] = "Smartquestionnaire";
  		$a_condition["aicrm_crmentity.deleted"] = "0";

       $this->db->select("aicrm_smartquestionnaire.*, aicrm_smartquestionnairecf.*");
       //$this->db->select("aicrm_productcf.cf_2061 , aicrm_branchs.branch_name , aicrm_building.building_name");
  	   $this->db->join('aicrm_smartquestionnairecf', 'aicrm_smartquestionnairecf.smartquestionnaireid = aicrm_smartquestionnaire.smartquestionnaireid');
  	   $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_smartquestionnaire.smartquestionnaireid');
       /*$this->db->join('aicrm_products', 'aicrm_products.productid = aicrm_smartquestionnaire.product_id');
       $this->db->join('aicrm_productcf', 'aicrm_productcf.productid = aicrm_smartquestionnaire.product_id');
       $this->db->join('aicrm_building', 'aicrm_building.buildingid = aicrm_products.buildingid');
       $this->db->join('aicrm_buildingcf', 'aicrm_products.buildingid = aicrm_buildingcf.buildingid');
       $this->db->join('aicrm_branchs', 'aicrm_branchs.branchid = aicrm_buildingcf.cf_1059');*/

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
  		$query = $this->db->get('aicrm_smartquestionnaire');

  		//echo $this->db->last_query();
		//exit;
  		
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
  
  
   function get_list_template($a_condition=array(),$a_order=array(),$a_limit=array())
	  {
		try {
			$a_condition["aicrm_crmentity.setype"] = "questionnairetemplate";
			$a_condition["aicrm_crmentity.deleted"] = "0";
	
		   $this->db->select("aicrm_questionnairetemplate.*, aicrm_questionnairetemplatecf.*");
		   $this->db->select("aicrm_questionnairetemplate_page.* , aicrm_questionnairetemplate_choice.* , aicrm_questionnairetemplate_choicedetail.* ");
		   $this->db->join('aicrm_questionnairetemplatecf', 'aicrm_questionnairetemplatecf.questionnairetemplateid = aicrm_questionnairetemplate.questionnairetemplateid');
		   $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_questionnairetemplate.questionnairetemplateid');
		   
		   $this->db->join('aicrm_questionnairetemplate_page', 'aicrm_questionnairetemplate_page.questionnairetemplateid = aicrm_questionnairetemplate.questionnairetemplateid');
		   $this->db->join('aicrm_questionnairetemplate_choice', 'aicrm_questionnairetemplate_choice.pageid = aicrm_questionnairetemplate_page.pageid');
		   $this->db->join('aicrm_questionnairetemplate_choicedetail', 'aicrm_questionnairetemplate_choicedetail.choiceid = aicrm_questionnairetemplate_choice.choiceid');

			$a_condition["aicrm_questionnairetemplate.questionnairetemplateid"] =  '1197065' ;

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
			$query = $this->db->get('aicrm_questionnairetemplate');
	
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

	function getTemplateList($a_condition=array(), $a_order=array(), $a_limit=array(), $param=array())
  	{
		try {
			$a_condition["aicrm_crmentity.setype"] = "questionnairetemplate";
			$a_condition["aicrm_crmentity.deleted"] = "0";
	
		   	$this->db->select("aicrm_questionnairetemplate.*, aicrm_questionnairetemplatecf.*");
		   	$this->db->select("aicrm_questionnairetemplate_page.* , aicrm_questionnairetemplate_choice.* , aicrm_questionnairetemplate_choicedetail.* ");
		   	$this->db->join('aicrm_questionnairetemplatecf', 'aicrm_questionnairetemplatecf.questionnairetemplateid = aicrm_questionnairetemplate.questionnairetemplateid');
		   	$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_questionnairetemplate.questionnairetemplateid');
		   
		   	$this->db->join('aicrm_questionnairetemplate_page', 'aicrm_questionnairetemplate_page.questionnairetemplateid = aicrm_questionnairetemplate.questionnairetemplateid');
		   	$this->db->join('aicrm_questionnairetemplate_choice', 'aicrm_questionnairetemplate_choice.pageid = aicrm_questionnairetemplate_page.pageid');
		   	$this->db->join('aicrm_questionnairetemplate_choicedetail', 'aicrm_questionnairetemplate_choicedetail.choiceid = aicrm_questionnairetemplate_choice.choiceid');

			if(isset($param['crmid'])) $a_condition["aicrm_questionnairetemplate.questionnairetemplateid"] =  $param['crmid'] ;

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
			$query = $this->db->get('aicrm_questionnairetemplate');
	
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

	function get_list_user($a_condition=array(),$a_order=array(),$a_limit=array())
	  {
		try {
			$a_condition["aicrm_crmentity.setype"] = "Smartquestionnaire";
			$a_condition["aicrm_crmentity.deleted"] = "0";
	
		   $this->db->select("aicrm_smartquestionnaire_answer.* ");
		   $this->db->join('aicrm_smartquestionnaire', 'aicrm_smartquestionnaire.smartquestionnaireid = aicrm_smartquestionnaire_answer.smartquestionnaireid');
		   $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_smartquestionnaire.smartquestionnaireid');
		   /*$this->db->join('aicrm_smartquestionnaire_page', 'aicrm_smartquestionnaire_page.smartquestionnaireid = aicrm_smartquestionnaire.smartquestionnaireid');
		   $this->db->join('aicrm_smartquestionnaire_choice', 'aicrm_smartquestionnaire_choice.pageid = aicrm_smartquestionnaire_page.pageid');
		   $this->db->join('aicrm_smartquestionnaire_choicedetail', 'aicrm_smartquestionnaire_choicedetail.choiceid = aicrm_smartquestionnaire_choice.choiceid');*/

	
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
			$query = $this->db->get('aicrm_smartquestionnaire_answer');
	
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