<?php
class Indexselect_model extends CI_Model
{
  var $ci;


  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
  }

   function get_total($a_condition=array(),$module,$like,$id,$section)
  {
  	try {

       $data_All =$this->get_query($module,$like,$id,$section);
       $query = $this->db->query($data_All);
       // alert($query);exit;
  		// $this->db->select("count(DISTINCT aicrm_crmentity.crmid) as total");
  		// $this->db->join('aicrm_accountscf', 'aicrm_accountscf.accountid = aicrm_account.accountid',"inner");
  		// $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_account.accountid',"inner");
  		// $this->db->join('aicrm_accountbillads', 'aicrm_accountbillads.accountaddressid = aicrm_account.accountid',"inner");
  		// $this->db->join('aicrm_accountshipads', 'aicrm_accountshipads.accountaddressid = aicrm_account.accountid',"inner");
		  // $this->db->where($a_condition);
		  // $query = $this->db->get('aicrm_crmentity');

		//   echo $this->db->last_query();
  		if(!$query){
  			$a_return["status"] = false;
  			$a_return["error"] = $this->db->_error_message();
  			$a_return["result"] = "";
  		}else{
  			$a_result  = $query->result_array() ;

        $total_All = count($a_result);

  			if (!empty($a_result)) {
  				$a_return["status"] = true;
  				$a_return["error"] =  "";
          $a_return["result"]= $total_All;
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


  function get_list($a_condition=array(),$a_order=array(),$a_limit=array(),$module,$section,$relate_field)
  {



  	$this->load->library('crmentity');

  	try {

				$like = $a_condition['like'];
				$id = $a_condition['crmid'];
        if($module=="Users"){
          	$id = $a_condition['userid'];
        }

				$data = $this->get_query($module,$like,$id,$section,$relate_field);
				$data .= " limit ".$a_limit['limit'];
				$data .= " offset ".$a_limit['offset'];

				$query = $this->db->query($data);

  		if(!$query){
  			$a_return["status"] = false;
  			$a_return["error"] = $this->db->_error_message();
  			$a_return["result"] = "";
  		}else{
				$a_result  = $query->result_array() ;
				foreach($a_result as $key => $val){

					foreach($val as $k => $v){
					if($v==null){
						$v="";
						$val[$k] = $v;
						$val_change = $val[$k];
					}
					$val[$k] = $v;
				}
				$a_result[$key] = $val;
				}


  			$a_total = $this->get_total($a_condition,$module,$like,$id,$section) ;
  			$a_data["offset"] = $a_limit["offset"];
  			$a_data["limit"] = $a_limit["limit"];
        $a_data["total"] = $a_total["result"];
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

	function get_query($module,$like,$id,$section,$relate_field=""){

    $accountid="";
    $contactid="";
    $productid="";
    $serialid="";
    $jobid="";
    $sparepartid="";
    $errorsid="";
    $ticketid="";
    $projectsid="";
    foreach ($relate_field as $key => $value) {

      if($relate_field['accountid']!=""){
        $accountid = $relate_field['accountid'];
      }
      if ($relate_field['contactid']!="") {
        $contactid = $relate_field['contactid'];
      }
      if ($relate_field['product_id']!="") {
        $productid = $relate_field['product_id'];

      }
      if ($relate_field['serialid']!="") {
        $serialid = $relate_field['serialid'];

      }
      if ($relate_field['jobid']!="") {
        $jobid = $relate_field['jobid'];

      }
      if ($relate_field['sparepartid']!="") {
        $sparepartid = $relate_field['sparepartid'];

      }
      if ($relate_field['errorsid']!="") {
        $errorsid = $relate_field['errorsid'];

      }
      if ($relate_field['ticketid']!="") {
        $ticketid = $relate_field['ticketid'];

      }
      if ($relate_field['projectsid']!="") {
        $projectsid = $relate_field['projectsid'];
      }

    }


    if($module=="Folder"){

    }else{
          $list_query=$this->crmentity->Get_Query($module);
    }

		switch($module){
			Case "Accounts":
				if($like!=""){
					$like ="AND `aicrm_account`.`accountname` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_account`.`accountid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

        $query = "
				SELECT `aicrm_account`.`accountid` as id, `aicrm_account`.`accountname` as name, `aicrm_account`.`account_no` as no, `aicrm_account`.`accounttype` as title,`aicrm_account`.*
        ".$list_query." AND `aicrm_crmentity`.`setype` =  'Accounts'  ".$like." ".$id."
				ORDER BY `aicrm_account`.`accountname` asc ";

        // alert($query);exit;


			break;
			Case "Products":
				if($like!=""){
					$like ="AND `aicrm_products`.`productname` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_products`.`productid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

        $query = "
        SELECT `aicrm_products`.`productid` as id, `aicrm_products`.`productname` as name, `aicrm_products`.`product_no` as no,
        `aicrm_products`.`productid`,`aicrm_products`.`productname`,`aicrm_products`.`product_no`,`aicrm_products`.`productcode`,
        `aicrm_productcf`.*,`aicrm_products`.`productid` as product_id
          ".$list_query." AND `aicrm_crmentity`.`setype` =  'Products' ".$like." ".$id."
        ORDER BY `aicrm_products`.`productname` asc";


			break;
			Case "Serial":
				if($like!=""){
					$like ="AND `aicrm_serial`.`serial_name` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_serial`.`serialid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

        if($accountid!=""){
          $accountid = "and aicrm_serial.accountid =".$accountid." ";
        }
        if($productid!=""){
          $productid = "and aicrm_serial.product_id =".$productid." ";
        }

        $query = "
  				SELECT `aicrm_serial`.`serialid` as id, `aicrm_serial`.`serial_name` as name, `aicrm_serial`.`serial_no` as no,`aicrm_account`.`accountname` as title,`aicrm_serial`.*,`aicrm_account`.`accountname`,`aicrm_products`.`productname`
  				  ".$list_query." AND `aicrm_crmentity`.`setype` =  'Serial' ".$like." ".$id." ".$accountid." ".$productid."
  					ORDER BY `aicrm_serial`.`serial_name` asc";


			break;
			Case "Contacts":
				if($like!=""){
					$like ="AND `aicrm_contactdetails`.`firstname` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_contactdetails`.`contactid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

        if($accountid!=""){
          $accountid = "and aicrm_contactdetails.accountid =".$accountid." ";
        }

        $query = "
				SELECT `aicrm_contactdetails`.`contactid` as  id, `aicrm_account`.`accountname` as title, `aicrm_contactdetails`.`contact_no` as no, CONCAT(aicrm_contactdetails.firstname, ' ', aicrm_contactdetails.lastname) AS name,`aicrm_contactdetails`.*, `aicrm_contactscf`.*, CONCAT(aicrm_contactdetails.firstname, ' ', aicrm_contactdetails.lastname) AS contactname, `aicrm_account`.`accountname`, `aicrm_account`.`accountid`
			  ".$list_query." AND `aicrm_crmentity`.`setype` =  'Contacts' ".$like." ".$id." ".$accountid."
				ORDER BY `aicrm_contactdetails`.`firstname` asc";


			break;
			Case "Job":
				if($like!=""){
					$like ="AND `aicrm_jobs`.`job_name` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_jobs`.`jobid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}



        if($accountid!=""){
          $accountid = "and aicrm_jobs.accountid =".$accountid." ";
        }
        if($productid!=""){
          $productid = "and aicrm_jobs.product_id =".$productid." ";
        }
        if($serialid!=""){
          $serialid = "and aicrm_jobs.serialid =".$serialid." ";
        }
        if($ticketid!=""){
          $ticketid = "and aicrm_troubletickets.ticketid =".$ticketid." ";
        }

        $query = "
  				SELECT `aicrm_jobs`.`jobid` as id, `aicrm_jobs`.`job_name` as name, `aicrm_jobs`.`job_no` as no,`aicrm_account`.`accountname` as title,`aicrm_jobs`.*,`aicrm_jobscf`.*,`aicrm_products`.`productname`,`aicrm_serial`.`serial_name`,`aicrm_account`.`accountname`,`aicrm_troubletickets`.`ticket_no`
  				".$list_query." AND `aicrm_crmentity`.`setype` =  'Job' ".$like." ".$id." ".$accountid." ".$productid." ".$serialid."  ".$ticketid."
  				ORDER BY `aicrm_jobs`.`job_name` asc";


			break;
			Case "Sparepart":
				if($like!=""){
					$like ="AND `aicrm_sparepart`.`sparepart_name` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_sparepart`.`sparepartid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

        $query = "
  				SELECT `aicrm_sparepart`.`sparepartid` as id, `aicrm_sparepart`.`sparepart_name` as name, `aicrm_sparepart`.`sparepart_no` as no,`aicrm_sparepart`.*,`aicrm_sparepartcf`.*
  				".$list_query." AND `aicrm_crmentity`.`setype` =  'Sparepart' ".$like." ".$id."
  				ORDER BY `aicrm_sparepart`.`sparepart_name` asc";

			break;
			Case "Errors":
				if($like!=""){
					$like ="AND `aicrm_errors`.`errors_name` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_errors`.`errorsid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

        $query = "
  				SELECT `aicrm_errors`.`errorsid` as  id, `aicrm_errors`.`errors_name` as name, `aicrm_errors`.`errors_no` as no,`aicrm_errors`.*,`aicrm_errorscf`.*
  				".$list_query." AND `aicrm_crmentity`.`setype` =  'Errors' ".$like." ".$id."
  				ORDER BY `aicrm_errors`.`errors_name` asc";

			break;
      Case "HelpDesk":
        if($like!=""){
          $like ="AND `aicrm_troubletickets`.`title` like '".$like."'";
          $id="";
        }else if($id!=""){
          $id = "AND  `aicrm_troubletickets`.`ticketid`= '".$id."'";
          $like ="";
        }else{
          $like ="";
          $id="";
        }

        if($productid!=""){
          $productid = "and aicrm_troubletickets.product_id =".$productid." ";
        }

        if($accountid!=""){
          $accountid = "and aicrm_troubletickets.accountid =".$accountid." ";
        }

        if($contactid!=""){
          $contactid = "and aicrm_troubletickets.contactid =".$contactid." ";
        }

        $query = "
        SELECT `aicrm_troubletickets`.`ticketid` as  id, `aicrm_troubletickets`.`title` as name, `aicrm_troubletickets`.`ticket_no` as no,`aicrm_troubletickets`.*,`aicrm_ticketcf`.*,`aicrm_products`.`productname`,`aicrm_account`.`accountname`,CONCAT(aicrm_contactdetails.firstname, ' ', aicrm_contactdetails.lastname) AS contactname
        	".$list_query." AND `aicrm_crmentity`.`setype` =  'HelpDesk' ".$like." ".$id."  ".$productid."  ".$accountid." ".$contactid."
        ORDER BY `aicrm_troubletickets`.`ticketid` asc";

      break;
			Case "Projects":
				if($like!=""){
					$like ="AND `aicrm_projects`.`projects_name` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_projects`.`projectsid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

        if($accountid!=""){
          $accountid = "and aicrm_projects.accountid =".$accountid." ";
        }
        if($contactid!=""){
          $contactid = "and aicrm_projects.contactid =".$contactid." ";
        }

				$query = "
				SELECT `aicrm_projects`.`projectsid` as id, `aicrm_projects`.`projects_name` as name,`aicrm_projects`.`projects_no` as no,`aicrm_account`.`accountname` as title,`aicrm_projects`.*,`aicrm_projectscf`.*,`aicrm_account`.`accountname`,CONCAT(aicrm_contactdetails.firstname, ' ', aicrm_contactdetails.lastname) AS contactname
				".$list_query." AND `aicrm_crmentity`.`setype` =  'Projects' ".$like." ".$id."  ".$accountid." ".$contactid."
				ORDER BY `aicrm_projects`.`projects_name` asc";
        // alert($query);exit;

			break;
      Case "Campaigns":
				if($like!=""){
					$like ="AND `aicrm_campaign`.`campaignname` like '".$like."'";
					$id="";
				}else if($id!=""){
					$id = "AND  `aicrm_campaign`.`campaignid`= '".$id."'";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

        $query = "
  				SELECT `aicrm_campaign`.`campaignid` as  id, `aicrm_campaign`.`campaignname` as name, `aicrm_campaign`.`campaign_no` as no, `aicrm_campaign`.`campaignstype` as title,`aicrm_campaign`.*,`aicrm_campaignscf`.*
  				".$list_query." AND `aicrm_crmentity`.`setype` =  'Campaigns' ".$like." ".$id."
  				ORDER BY `aicrm_campaign`.`campaignname` asc";
			break;
      Case "Folder":
				if($like!=""){
					$like ="where `aicrm_attachmentsfolder`.`foldername` like '".$like."'";
					$id="";
				}else if($id!=""){
          // $id = "AND  `aicrm_attachmentsfolder`.`folderid`= '".$id."'";
					$id = "";
					$like ="";
				}else{
					$like ="";
					$id="";
				}

        $query = "
				SELECT `aicrm_attachmentsfolder`.`folderid` as id, `aicrm_attachmentsfolder`.`foldername` as name, `aicrm_attachmentsfolder`.`description` ,`aicrm_attachmentsfolder`.*
        from `aicrm_attachmentsfolder`
         ".$like." ".$id."
				ORDER BY `aicrm_attachmentsfolder`.`foldername` asc ";
			break;

      Case "Users":
      if($like!=""){
        $like ="AND `aicrm_users`.`user_name` like '".$like."'";
        $id="";
      }else if($id!=""){
        $id = "AND  `aicrm_users`.`id`= '".$id."'";
        $like ="";
      }else{
        $like ="";
        $id="";
      }

      $query = "
        select id as id
          ,user_name
          , first_name , last_name
          , CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'name'
          , IFNULL(area,'') as area
          , cf_4257 as no
             , case when section	= '--None--' then '' else section end as section
        from aicrm_users
        where
        status='Active'
        and section = '".$section."'
         ".$like." ".$id."
        order by user_name asc";
        // LIMIT 40";

      break;
		default:
		}

	return $query;

	}

}
