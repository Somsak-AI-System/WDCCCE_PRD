<?php
class searchall_model extends CI_Model
{
  var $ci;

  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
	$this->load->library("common");
    $this->_limit = "10";
  }


	function searchall($a_params,$a_limit)
  {
    $limit = $a_limit['limit'];
    $offset = $a_limit['offset'];
		
    if($a_params!=''){

        $a_resultmenu = $this->get_searlquery($a_params,$limit,$offset);
        //alert($a_resultmenu); exit;
  			// $querydata = "Select new_table.* ,aicrm_crmentity.setype as module from (
  			// 	select accountname as name ,account_no as no, accountid as id   from aicrm_account
  			// 	UNION  ALL
  			// 	select concat(firstname,'',lastname) as name ,contact_no as no,contactid as id from aicrm_contactdetails
  			// 	UNION ALL
  			// 	select sparepartlist_name as name ,sparepartlist_no as no ,sparepartlistid as id FROM aicrm_sparepartlist
  			// 	UNION ALL
  			// 	select sparepart_name as name ,sparepart_no as no, sparepartid as id FROM aicrm_sparepart
  			// 	UNION ALL
  			// 	select errorslist_name as name ,errorslist_no as no,errorslistid as id FROM aicrm_errorslist
  			// 	UNION all
  			// 	select errors_name as name ,errors_no as no ,errorsid as id FROM aicrm_errors
  			// 	UNION all
  			// 	select job_name as name ,job_no as no ,jobid as id FROM aicrm_jobs
  			// 	UNION all
  			// 	select activitytype as name ,activityid as no ,activityid as id FROM aicrm_activity
        //
  			// 	) as new_table
  			// 	INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = new_table.id
  			// 	where new_table.name LIKE '%".$a_params."%'";
        //   if($limit!=null){
        //     $querydata .= " limit ".$limit."  offset ".$offset." ";
        //   }
        //
  			// $check_menu = $this->db->query($querydata);
        //
        // if(!$check_menu){
        //   $a_return["status"] = false;
        //   $a_return["error"] = $this->db->_error_message();
        //   $a_return["result"] = "";
        // }else{
  			// $a_resultmenu  = $check_menu->result_array() ;

        $a_total = $this->get_total($a_params,$limit,$offset) ;

        $a_data["offset"] = $a_limit["offset"];
        $a_data["limit"] = $a_limit["limit"];
        $a_data["total"] = $a_total["result"];
        $a_data["data"] = $a_resultmenu;

  		  if (!empty($a_resultmenu)) {
          $a_return["status"] = true;
          $a_return["error"] =  "";
          $a_return["result"] = $a_data;
        }else{
          $a_return["status"] = false;
          $a_return["error"] =  "No Data";
          $a_return["result"] = "";
        }

		}else{
			$a_return["status"] = false;
			$a_return["error"] =  "No Data";
			$a_return["result"] = "";
		}

		return $a_return;
	}

  function get_searlquery($a_params,$limit,$offset){
    // UNION ALL
    //   select sparepartlist_name as name ,sparepartlist_no as no ,sparepartlistid as id FROM aicrm_sparepartlist
    //   UNION ALL
    //   select sparepart_name as name ,sparepart_no as no, sparepartid as id FROM aicrm_sparepart
    //   UNION ALL
    //   select errorslist_name as name ,errorslist_no as no,errorslistid as id FROM aicrm_errorslist
    //   UNION all
    //   select errors_name as name ,errors_no as no ,errorsid as id FROM aicrm_errors
    //   UNION all
    //   select job_name as name ,job_no as no ,jobid as id FROM aicrm_jobs

    $querydata = "Select new_table.* ,aicrm_crmentity.setype as module from (
        select accountname as name ,account_no as no, accountid as id   from aicrm_account
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
        where aicrm_crmentity.deleted =0 
        UNION  ALL
        SELECT contactname AS NAME, contact_no AS NO, contactid AS id  FROM aicrm_contactdetails
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_contactdetails.contactid 
        WHERE aicrm_crmentity.deleted = 0 
        UNION ALL
        select concat(firstname,' ',lastname) as name ,lead_no as no,leadid as id from aicrm_leaddetails
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_leaddetails.leadid
        where aicrm_crmentity.deleted =0 
        UNION all
        select sales_visit_name as name ,activityid as no ,activityid as id FROM aicrm_activity
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_activity.activityid
        where aicrm_crmentity.deleted =0 
        UNION all
        select competitor_name AS NAME, competitor_no AS NO, competitorid AS id  FROM aicrm_competitor
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_competitor.competitorid 
        where aicrm_crmentity.deleted =0 
        
        UNION all
        select expense_name AS NAME, expense_no AS NO, expenseid AS id  FROM aicrm_expense
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_expense.expenseid 
        where aicrm_crmentity.deleted =0

        UNION all
        select aicrm_projects.projects_name AS NAME, aicrm_projects.projects_no AS NO, aicrm_projects.projectsid AS id  FROM aicrm_projects
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_projects.projectsid 
        where aicrm_crmentity.deleted =0 

        UNION all
        select aicrm_quotes.quote_name AS NAME, aicrm_quotes.quote_no AS NO, aicrm_quotes.quoteid AS id  FROM aicrm_quotes
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid 
        where aicrm_crmentity.deleted =0

        UNION all
        select aicrm_freetags.tag as 'name', aicrm_freetags.id as 'no',aicrm_freetagged_objects.object_id as 'id' from aicrm_freetags
        inner join aicrm_freetagged_objects on aicrm_freetagged_objects.tag_id = aicrm_freetags.id
      ) as new_table
      INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = new_table.id
      where new_table.name LIKE '%".$a_params."%'  and aicrm_crmentity.deleted=0 ";
      /*select concat(firstname,'',lastname) as name ,contact_no as no,contactid as id from aicrm_contactdetails
      UNION ALL*/
      if($limit!=null){
        $querydata .= " limit ".$limit."  offset ".$offset." ";
      }

    // alert( $querydata);exit;

    $check_menu = $this->db->query($querydata);
    //alert($check_menu); exit;
    if(!$check_menu){
      $a_return["status"] = false;
      $a_return["error"] = $this->db->_error_message();
      $a_return["result"] = "";
    }else{

      $a_resultmenu  = $check_menu->result_array() ;
    }

    return $a_resultmenu;

  }


  function get_total($a_condition=array(),$module="",$like="",$id="",$section="")
 {
   try {
      $limit = 0 ;
      $offset = 0 ;
      $data_All = $this->get_searlquery($a_condition,$limit,$offset);
      //alert($data_All); exit;
     if(!$data_All){
       $a_return["status"] = false;
       $a_return["error"] = $this->db->_error_message();
       $a_return["result"] = "";
     }else{

       $total_All = count($data_All);

       if (!empty($data_All)) {
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
   //alert($a_return); exit;
   return $a_return;
 }


 function searchaddress($a_search,$a_limit)
  {
    $limit = $a_limit['limit'];
    $offset = $a_limit['offset'];

      if($a_search!=''){

      // $a_resultmenu = $this->get_searlquery($a_params,$limit,$offset);
         $queryaddress = "SELECT new_table.* from(
              SELECT CONCAT(tbm_district.district_name,'-',tbm_district.amphur_name,'-',tbm_district.province_name) AS name,
               tbm_district.district_id,tbm_district.district_name,tbm_district.amphur_id,tbm_district.amphur_name,tbm_district.province_id,tbm_district.province_name,tbm_postcode.post_code
              FROM tbm_district
              INNER JOIN tbm_postcode ON tbm_postcode.district_id = tbm_district.district_id 
              WHERE tbm_district.deleted=0 
            )as new_table ";

      if($a_search!=null){
        $queryaddress .= " WHERE new_table.name LIKE '%".$a_search."%' ";
      }

      $queryaddress .= " ORDER BY  new_table.name ASC ";

      if($limit!=null){
        $queryaddress .= " limit ".$limit." offset ".$offset." ";
      }

 // alert($queryaddress); exit;
    $get_address = $this->db->query($queryaddress);
 
    if(!$get_address){
      $a_return["status"] = false;
      $a_return["error"] = $this->db->_error_message();
      $a_return["result"] = "";
    }else{

      $a_resultdata  = $get_address->result_array() ;
    }


    $querytotal = "SELECT COUNT(new_table.name) as total FROM(
              SELECT CONCAT(district_name,'-',amphur_name,'-',province_name) AS name 
              FROM tbm_district
              WHERE deleted=0 
            )AS new_table ";

      // $querytotal = "SELECT COUNT(districtid) AS total
      //         FROM tbm_district
      //         WHERE deleted=0 ";

       if($a_search!=null){
        $querytotal .= " WHERE new_table.name LIKE '%".$a_search."%' ";
      }
        // alert( $querytotal);exit;
      $get_total = $this->db->query($querytotal);
      $a_total = $get_total->row_array();
      // $a_total = $this->get_total($a_params,$limit,$offset) ;

      $a_data["offset"] = $a_limit["offset"];
      $a_data["limit"] = $a_limit["limit"];
      $a_data["total"] = $a_total["total"];
      $a_data["data"] = $a_resultdata;
      if (!empty($a_resultdata)) {
        $a_return["status"] = true;
        $a_return["error"] =  "";
        $a_return["result"] = $a_data;
      }else{
        $a_return["status"] = false;
        $a_return["error"] =  "No Data";
        $a_return["result"] = "";
      }

    }else{
      $a_return["status"] = false;
      $a_return["error"] =  "No Data";
      $a_return["result"] = "";
    }

    return $a_return;
  }

}
