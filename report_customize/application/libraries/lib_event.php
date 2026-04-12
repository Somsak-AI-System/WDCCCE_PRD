<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_event
{

  function __construct()
  {
    $this->ci = & get_instance();

    $this->ci->load->config('api');
    $this->ci->load->library('curl');
    $this->ci->load->library('common');
    $this->ci->load->database();
	//$this->_empcd	=  $this->session->userdata('user.username');
  }

  private function get_tabid($module="")
  {
  	if($module=="") return "";
  	$a_condition["aicrm_tab.name"] =  $module;
  	$this->ci->db->select("aicrm_tab.tabid");
  	$this->ci->db->where($a_condition);
  	$query= $this->ci->db->get('aicrm_tab');
  	$a_data = $query->result_array() ;
	return isset($a_data[0]["tabid"]) ?$a_data[0]["tabid"] :"";
  }

  private function get_relatedlist($tabid="")
  {
  	if($tabid=="") return "";
  	$a_condition["aicrm_relatedlists.tabid"] =  $tabid;
  	$a_condition["aicrm_relatedlists.presence"] =  0;
  	$this->ci->db->select("aicrm_relatedlists.*");
  	$this->ci->db->order_by('aicrm_relatedlists.sequence asc');
  	$this->ci->db->where($a_condition);
  	$query= $this->ci->db->get('aicrm_relatedlists');
  	$a_data = $query->result_array() ;
  	return $a_data;
  }

  private function set_format($a_data=array(),$a_default,$module)
  {
		$a_return = array();
		if(!empty($a_data)){
			foreach ($a_data as $key => $value){
				$id = $value["event_id"];
				unset( $value["event_id"]);
				unset( $value["crmid"]);
				$a_return[$id][] = $value;
			}
		}
		foreach ($a_default as  $v){
			if (!array_key_exists($v,$a_return)){
				$a_return[$v] = $this->set_default_data($module);
			}
		}
		return $a_return;
  }

  private function set_default_data($module="")
  {
  	if(empty($module)) return "";
  	$a_return = array();
  	$a_datadf= array();
  	switch ($module) {
  		case "get_participant":
	  			$a_datadf["Participant_No"] = "";$a_datadf["Account_Name"] = "";	$a_datadf["Member_Status"] = "";
	  			$a_datadf["Participant_Name"] = "";		$a_datadf["Participant_SName"] = ""; $a_datadf["Position"] = "";
	  			$a_datadf["Email"] = ""; $a_datadf["Phone"] = ""; 	$a_datadf["Mobile"] = "";
	  			$a_datadf["Ticket_Number"] = ""; 		$a_datadf["Ticket_Prices"] = ""; $a_datadf["Confirm_Status"] = "";
	  			$a_datadf["Payment_Status"] = ""; $a_datadf["Training_Days"] = ""; $a_datadf["Channel_Register"] = "";
  			break;
  		case "get_speaker_list":
	  			$a_datadf["Speaker_Detail_No"] = "";
	  			$a_datadf["Name"] = "";
	  			$a_datadf["Surname_Thai"] = "";
	  			$a_datadf["Position"] = "";
	  			$a_datadf["Email"] = "";
	  			$a_datadf["Mobile"] = "";
  			break;
  		case "get_sponsors_list":
	  			$a_datadf["Sponsor_Detail_No"] = "";
		  		$a_datadf["Coordinator_Position"] = "";
		  		$a_datadf["Sponsor_Name"] = "";
		  		$a_datadf["Coordinator_Email"] = "";
		  		$a_datadf["Coordinator_Mobile"] = "";
		  		$a_datadf["Support_Type"] = "";
		  		$a_datadf["Funding_/_Support Articles"] = "";
  			break;
  		case "get_knowledge_partners_list":
  				$a_datadf["Knowledge_Partner_Detail_No"] = "";
		  		$a_datadf["Position_Thai"] = "";
		  		$a_datadf["Knowledge_Partner_Name"] = "";
		  		$a_datadf["Coordinator_Email"] = "";
		  		$a_datadf["Coordinator_Mobile"] = "";
		  		$a_datadf["Support_Type"] = "";
		  		$a_datadf["Funding_/_Support Articles"] = "";
  			break;
  		case "get_board_list":
  			$a_datadf["Boards_Detail_No"] = "";
  			$a_datadf["Name"] = "";
  			$a_datadf["Surname_Thai"] = "";
  			$a_datadf["Position"] = "";
  			$a_datadf["Email"] = "";
  			$a_datadf["Mobile"] = "";
  			break;
  		case "get_councilor_list":
  			$a_datadf["Councilors_Detail_No"] = "";
  			$a_datadf["Name"] = "";
  			$a_datadf["Surname_Thai"] = "";
  			$a_datadf["Position"] = "";
  			$a_datadf["Email"] = "";
  			$a_datadf["Mobile"] = "";
  			break;
  		case "get_committee_list":
  			$a_datadf["Committees_Detail_No"] = "";
  			$a_datadf["Name"] = "";
  			$a_datadf["Surname_Thai"] = "";
  			$a_datadf["Position"] = "";
  			$a_datadf["Email"] = "";
  			$a_datadf["Mobile"] = "";
  			break;
  		case "get_vendor":
  			$a_datadf["Vendor_No"] = "";
  			$a_datadf["Vendor_Name"] = "";
  			$a_datadf["Address"] = "";
  			$a_datadf["Coordinator_Name"] = "";
  			$a_datadf["Surname"] = "";
  			$a_datadf["Coordinator_Email"] = "";
  			$a_datadf["Mobile"] = "";
  			break;
  	}

  	$a_return[] =  $a_datadf;
  	return $a_return;
  }

  private function get_participant($a_eventid="",$module="")
  {
  		$a_data= array();
	  	$a_condition["aicrm_crmentity.setype"] = "Participant";
		$a_condition["aicrm_crmentity.deleted"] = "0";
		$a_condition["aicrm_crmentity_contact.deleted"] = "0";
		$a_condition["aicrm_crmentity_contact.setype"] = "Contacts";

		$a_condition["aicrm_crmentity_account.deleted"] = "0";
		$a_condition["aicrm_crmentity_account.setype"] = "Accounts";
		if(!empty($a_eventid)){
			$this->ci->db->where_in("aicrm_participant.event_id",$a_eventid);
		}
	  	$this->ci->db->select("aicrm_participant.participant_no as 'Participant_No'
	  			,aicrm_account.accountname as 'Account_Name'
	  			,aicrm_participantcf.cf_3919 as 'Member_Status'
	  			,aicrm_contactdetails.firstname  as 'Participant_Name'
	  			,aicrm_contactdetails.lastname as 'Participant_SName'
	  			,aicrm_participantcf.cf_3906 as 'Position'
	  			,aicrm_participantcf.cf_3907 as 'Email'
	  			,aicrm_participantcf.cf_3908 as 'Phone'
	  			,aicrm_participantcf.cf_3909 as 'Mobile'
	  			,aicrm_participantcf.cf_3910 as 'Ticket_Number'

	  			,aicrm_participantcf.cf_3911 as 'Ticket_Prices'
	  			,aicrm_participantcf.cf_3912 as 'Confirm_Status'
	  			,aicrm_participantcf.cf_3913 as 'Payment_Status'
	  			,aicrm_participantcf.cf_3916 as 'Training_Days'
	  			,aicrm_participantcf.cf_3915 as 'Channel_Register'
	  			");
	 //  $this->ci->db->select("CONCAT(aicrm_contactdetails.firstname,' ', aicrm_contactdetails.lastname) AS Participant_Name",FALSE);
	   $this->ci->db->select("aicrm_crmentity.crmid,aicrm_participant.event_id");
	  	$this->ci->db->join('aicrm_participantcf', 'aicrm_participantcf.participantid = aicrm_participant.participantid', 'left');
	  	$this->ci->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_participant.participantid', 'inner');
	  	$this->ci->db->join('aicrm_account', 'aicrm_participant.account_id = aicrm_account.accountid', 'left');
	  	$this->ci->db->join('aicrm_crmentity as aicrm_crmentity_account', 'aicrm_crmentity_account.crmid = aicrm_account.accountid', 'inner');
	  	$this->ci->db->join('aicrm_contactdetails', 'aicrm_participant.contactid = aicrm_contactdetails.contactid', 'left');
	  	$this->ci->db->join('aicrm_crmentity as aicrm_crmentity_contact ', 'aicrm_crmentity_contact.crmid = aicrm_contactdetails.contactid', 'inner');
	  //	$this->ci->db->order_by('aicrm_participant.sequence asc');
	  	$this->ci->db->where($a_condition);
	  	$query= $this->ci->db->get('aicrm_participant');
	  	//echo $this->ci->db->last_query();
	  	$a_data = $query->result_array() ;

	  	return $a_data;
  }

  private function get_speaker_list($a_eventid="",$module="")
  {
  	$a_data= array();
  	$a_condition["aicrm_crmentity.setype"] = "Speakerdetail";
  	$a_condition["aicrm_crmentity.deleted"] = "0";

  	$a_condition["aicrm_crmentity_ind.deleted"] = "0";
  	$a_condition["aicrm_crmentity_ind.setype"] = "Individuals";
  	if(!empty($a_eventid)){
  		$this->ci->db->where_in("aicrm_speakerdetail.event_id",$a_eventid);
  	}
  	$this->ci->db->select("aicrm_speakerdetail.speakerdetail_no as 'Speaker_Detail_No'
	  			,aicrm_individual.individual_name as 'Name'
  				,aicrm_individualcf.cf_3294 as 'Surname_Thai'
	  			,aicrm_individualcf.cf_3295 as 'Position'
  				,aicrm_individualcf.cf_3311 as 'Email'
  				,aicrm_individualcf.cf_3310 as 'Mobile'
  			");
  	$this->ci->db->select("aicrm_crmentity.crmid,aicrm_speakerdetail.event_id");
  	$this->ci->db->join('aicrm_speakerdetailcf', 'aicrm_speakerdetailcf.speakerdetailid = aicrm_speakerdetail.speakerdetailid', 'left');
  	$this->ci->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_speakerdetail.speakerdetailid', 'inner');

  	$this->ci->db->join('aicrm_individual', 'aicrm_speakerdetail.individualid = aicrm_individual.individualid', 'left');
  	$this->ci->db->join('aicrm_individualcf', 'aicrm_individualcf.individualid = aicrm_individual.individualid', 'left');
  	$this->ci->db->join('aicrm_crmentity as aicrm_crmentity_ind', 'aicrm_crmentity_ind.crmid = aicrm_individual.individualid', 'inner');
  	$this->ci->db->where($a_condition);
  	$query= $this->ci->db->get('aicrm_speakerdetail');
  	//echo $this->ci->db->last_query();
  	$a_data = $query->result_array() ;
  	return $a_data;
  }

  private function get_sponsors_list($a_eventid="",$module="")
  {
  	$a_data= array();
  	$a_condition["aicrm_crmentity.setype"] = "SponsorsDetail";
  	$a_condition["aicrm_crmentity.deleted"] = "0";

  	$a_condition["aicrm_crmentity_spo.deleted"] = "0";
  	$a_condition["aicrm_crmentity_spo.setype"] = "Sponsors";
  	if(!empty($a_eventid)){
  		$this->ci->db->where_in("aicrm_sponsordetail.event_id",$a_eventid);
  	}
  	$this->ci->db->select("aicrm_sponsordetail.sponsordetail_no as 'Sponsor_Detail_No'
	  			,aicrm_sponsorcf.cf_3415 as 'Coordinator_Position'
  				,aicrm_sponsor.sponsor_name as 'Sponsor_Name'
	  			,aicrm_sponsorcf.cf_3426 as 'Coordinator_Email'
  				,aicrm_sponsorcf.cf_3424 as 'Coordinator_Mobile'
  				,aicrm_sponsordetailcf.cf_3339 as 'Support_Type'
  				,aicrm_sponsordetailcf.cf_3340 as 'Funding_/_Support Articles'
  			");
  	$this->ci->db->select("aicrm_crmentity.crmid,aicrm_sponsordetail.event_id");
  	$this->ci->db->join('aicrm_sponsordetailcf', 'aicrm_sponsordetailcf.sponsordetailid = aicrm_sponsordetail.sponsordetailid', 'left');
  	$this->ci->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_sponsordetail.sponsordetailid', 'inner');

  	$this->ci->db->join('aicrm_sponsor', 'aicrm_sponsordetail.sponsorid = aicrm_sponsor.sponsorid', 'left');
  	$this->ci->db->join('aicrm_sponsorcf', 'aicrm_sponsorcf.sponsorid = aicrm_sponsor.sponsorid', 'left');
  	$this->ci->db->join('aicrm_crmentity as aicrm_crmentity_spo', 'aicrm_crmentity_spo.crmid = aicrm_sponsor.sponsorid', 'inner');
  	$this->ci->db->where($a_condition);
  	$query= $this->ci->db->get('aicrm_sponsordetail');
  	//echo $this->ci->db->last_query();
  	$a_data = $query->result_array() ;
  	return $a_data;
  }

  private function get_knowledge_partners_list($a_eventid="",$module="")
  {
  	$a_data= array();
  	$a_condition["aicrm_crmentity.setype"] = "KnowledgePartnersDetail";
  	$a_condition["aicrm_crmentity.deleted"] = "0";

  	$a_condition["aicrm_crmentity_kno.deleted"] = "0";
  	$a_condition["aicrm_crmentity_kno.setype"] = "KnowledgePartners";
  	if(!empty($a_eventid)){
  		$this->ci->db->where_in("aicrm_knowledgepartnerdetail.event_id",$a_eventid);
  	}
  	$this->ci->db->select("aicrm_knowledgepartnerdetail.knowledgepartnerdetail_no as 'Knowledge_Partner_Detail_No'
	  			,aicrm_knowledgepartnercf.cf_3461 as 'Position_Thai'
  				,aicrm_knowledgepartner.knowledgepartner_name as 'Knowledge_Partner_Name'
	  			,aicrm_knowledgepartnercf.cf_3466 as 'Coordinator_Email'
  				,aicrm_knowledgepartnercf.cf_3464 as 'Coordinator_Mobile'
  				,aicrm_knowledgepartnerdetailcf.cf_3348 as 'Support_Type'
  				,aicrm_knowledgepartnerdetailcf.cf_3349 as 'Funding_/_Support Articles'
  			");
  	$this->ci->db->select("aicrm_crmentity.crmid,aicrm_knowledgepartnerdetail.event_id");
  	$this->ci->db->join('aicrm_knowledgepartnerdetailcf', 'aicrm_knowledgepartnerdetailcf.knowledgepartnerdetailid = aicrm_knowledgepartnerdetail.knowledgepartnerdetailid', 'left');
  	$this->ci->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_knowledgepartnerdetail.knowledgepartnerdetailid', 'inner');

  	$this->ci->db->join('aicrm_knowledgepartner', 'aicrm_knowledgepartnerdetail.knowledgepartnerid = aicrm_knowledgepartner.knowledgepartnerid', 'left');
  	$this->ci->db->join('aicrm_knowledgepartnercf', 'aicrm_knowledgepartnercf.knowledgepartnerid = aicrm_knowledgepartner.knowledgepartnerid', 'left');
  	$this->ci->db->join('aicrm_crmentity as aicrm_crmentity_kno', 'aicrm_crmentity_kno.crmid = aicrm_knowledgepartner.knowledgepartnerid', 'inner');
  	$this->ci->db->where($a_condition);
  	$query= $this->ci->db->get('aicrm_knowledgepartnerdetail');
  	//echo $this->ci->db->last_query();
  	$a_data = $query->result_array() ;
  	return $a_data;
  }
  private function get_board_list($a_eventid="",$module="")
  {
  	$a_data= array();
  	$a_condition["aicrm_crmentity.setype"] = "BoardsDetail";
  	$a_condition["aicrm_crmentity.deleted"] = "0";

  	$a_condition["aicrm_crmentity_ind.deleted"] = "0";
  	$a_condition["aicrm_crmentity_ind.setype"] = "Individuals";
  	if(!empty($a_eventid)){
  		$this->ci->db->where_in("aicrm_boarddetail.event_id",$a_eventid);
  	}
  	$this->ci->db->select("aicrm_boarddetail.boarddetail_no as 'Boards_Detail_No'
	  			,aicrm_individual.individual_name as 'Name'
  				,aicrm_individualcf.cf_3294 as 'Surname_Thai'
	  			,aicrm_individualcf.cf_3295 as 'Position'
  				,aicrm_individualcf.cf_3311 as 'Email'
  				,aicrm_individualcf.cf_3310 as 'Mobile'
  			");
  	$this->ci->db->select("aicrm_crmentity.crmid,aicrm_boarddetail.event_id");
  	$this->ci->db->join('aicrm_boarddetailcf', 'aicrm_boarddetailcf.boarddetailid = aicrm_boarddetail.boarddetailid', 'left');
  	$this->ci->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_boarddetail.boarddetailid', 'inner');

  	$this->ci->db->join('aicrm_individual', 'aicrm_individual.individualid = aicrm_boarddetail.individualid', 'left');
  	$this->ci->db->join('aicrm_individualcf', 'aicrm_individualcf.individualid = aicrm_individual.individualid', 'left');
  	$this->ci->db->join('aicrm_crmentity as aicrm_crmentity_ind', 'aicrm_crmentity_ind.crmid = aicrm_individual.individualid', 'inner');
  	$this->ci->db->where($a_condition);
  	$query= $this->ci->db->get('aicrm_boarddetail');
  	//echo $this->ci->db->last_query();
  	$a_data = $query->result_array() ;


  	return $a_data;
  }
  private function get_councilor_list($a_eventid="",$module="")
  {
  	$a_data= array();
  	$a_condition["aicrm_crmentity.setype"] = "CouncilorsDetail";
  	$a_condition["aicrm_crmentity.deleted"] = "0";

  	$a_condition["aicrm_crmentity_ind.deleted"] = "0";
  	$a_condition["aicrm_crmentity_ind.setype"] = "Individuals";
  	if(!empty($a_eventid)){
  		$this->ci->db->where_in("aicrm_councilordetail.event_id",$a_eventid);
  	}
  	$this->ci->db->select("aicrm_councilordetail.councilordetail_no as 'Councilors_Detail_No'
	  			,aicrm_individual.individual_name as 'Name'
  				,aicrm_individualcf.cf_3294 as 'Surname_Thai'
	  			,aicrm_individualcf.cf_3295 as 'Position'
  				,aicrm_individualcf.cf_3311 as 'Email'
  				,aicrm_individualcf.cf_3310 as 'Mobile'
  			");
  	$this->ci->db->select("aicrm_crmentity.crmid,aicrm_councilordetail.event_id");
  	$this->ci->db->join('aicrm_councilordetailcf', 'aicrm_councilordetailcf.councilordetailid = aicrm_councilordetail.councilordetailid', 'left');
  	$this->ci->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_councilordetail.councilordetailid', 'inner');

  	$this->ci->db->join('aicrm_individual', 'aicrm_individual.individualid = aicrm_councilordetail.individualid', 'left');
  	$this->ci->db->join('aicrm_individualcf', 'aicrm_individualcf.individualid = aicrm_individual.individualid', 'left');
  	$this->ci->db->join('aicrm_crmentity as aicrm_crmentity_ind', 'aicrm_crmentity_ind.crmid = aicrm_individual.individualid', 'inner');
  	$this->ci->db->where($a_condition);
  	$query= $this->ci->db->get('aicrm_councilordetail');
  	//echo $this->ci->db->last_query();
  	$a_data = $query->result_array() ;


  	return $a_data;
  }

  private function get_committee_list($a_eventid="",$module="")
  {
  	$a_data= array();
  	$a_condition["aicrm_crmentity.setype"] = "CommitteesDetail";
  	$a_condition["aicrm_crmentity.deleted"] = "0";

  	$a_condition["aicrm_crmentity_ind.deleted"] = "0";
  	$a_condition["aicrm_crmentity_ind.setype"] = "Individuals";
  	if(!empty($a_eventid)){
  		$this->ci->db->where_in("aicrm_committeedetail.event_id",$a_eventid);
  	}
  	$this->ci->db->select("aicrm_committeedetail.committeedetail_no as 'Committees_Detail_No'
	  			,aicrm_individual.individual_name as 'Name'
  				,aicrm_individualcf.cf_3294 as 'Surname_Thai'
	  			,aicrm_individualcf.cf_3295 as 'Position'
  				,aicrm_individualcf.cf_3311 as 'Email'
  				,aicrm_individualcf.cf_3310 as 'Mobile'
  			");
  	$this->ci->db->select("aicrm_crmentity.crmid,aicrm_committeedetail.event_id");
  	$this->ci->db->join('aicrm_committeedetailcf', 'aicrm_committeedetailcf.committeedetailid = aicrm_committeedetail.committeedetailid', 'left');
  	$this->ci->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_committeedetail.committeedetailid', 'inner');

  	$this->ci->db->join('aicrm_individual', 'aicrm_individual.individualid = aicrm_committeedetail.individualid', 'left');
  	$this->ci->db->join('aicrm_individualcf', 'aicrm_individualcf.individualid = aicrm_individual.individualid', 'left');
  	$this->ci->db->join('aicrm_crmentity as aicrm_crmentity_ind', 'aicrm_crmentity_ind.crmid = aicrm_individual.individualid', 'inner');
  	$this->ci->db->where($a_condition);
  	$query= $this->ci->db->get('aicrm_committeedetail');
  	//echo $this->ci->db->last_query();
  	$a_data = $query->result_array() ;


  	return $a_data;
  }

  public function get_vendor($a_eventid="",$module="")
  {
  	$a_data = array();

  	$a_condition["aicrm_crmentity.setype"] = "Vendors";
  	$a_condition["aicrm_crmentity.deleted"] = "0";

  	//$a_condition["aicrm_crmentity_ind.deleted"] = "0";
  	//$a_condition["aicrm_crmentity_ind.setype"] = "Individuals";
  	if(!empty($a_eventid)){
  		$this->ci->db->where_in("aicrm_crmentityrel.crmid",$a_eventid);
  		$this->ci->db->or_where_in("aicrm_crmentityrel.relcrmid",$a_eventid);
  	}

  	$this->ci->db->select("
  			aicrm_vendor.vendor_no	as 'Vendor_No'
  			,aicrm_vendor.vendorname as 'Vendor_Name'
  			,aicrm_vendorcf.cf_4327 as 'Address'
  			,aicrm_vendorcf.cf_3324  as 'Coordinator_Name'
  			,aicrm_vendorcf.cf_3326 as 'Surname'
  			,aicrm_vendorcf.cf_3332  as 'Coordinator_Email'
  			,aicrm_vendorcf.cf_3330 as 'Mobile'
  			");
  	$this->ci->db->select("aicrm_crmentity.crmid ");
  	$this->ci->db->select("case when aicrm_crmentity.crmid=aicrm_crmentityrel.crmid then aicrm_crmentityrel.relcrmid else aicrm_crmentityrel.crmid end as  event_id",false);
  	$this->ci->db->join('aicrm_vendorcf', 'aicrm_vendorcf.vendorid = aicrm_vendor.vendorid', 'left');
  	$this->ci->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_vendor.vendorid', 'inner');
  	$this->ci->db->join('aicrm_crmentityrel', 'aicrm_crmentityrel.crmid = aicrm_crmentity.crmid or aicrm_crmentityrel.relcrmid = aicrm_crmentity.crmid', 'inner');

  	$this->ci->db->where($a_condition);
  	$query= $this->ci->db->get('aicrm_vendor');
  	//echo $this->ci->db->last_query();
  	$a_data = $query->result_array() ;
  	return $a_data;
  }
  public function get_related_list($a_eventid="",$module="",$related_tabid="")
  {
		if($related_tabid=="") return "";
		$data = array();
		$name = "";
		switch ($related_tabid) {
			case "18"://Vendors
				$data = $this->get_vendor($a_eventid,$module);
				$name = "get_vendor";
			break;
		}


		return array($data,$name);
  }

  public function get_event_relate($a_eventid=array(),$module="")
  {
  		$a_response = array();
  		$a_return = array();

		if($module=="" || empty($a_eventid )) return "";
		$tabid = $this->get_tabid($module);
		foreach ($a_eventid as $k => $v){
			$a_responsse[$module][$v]=array();
		}

		if($tabid=="") return $a_responsse;
		$a_data = $this->get_relatedlist($tabid);

		if(empty($a_data)) return $a_responsse;

		foreach ($a_data as $key => $value){
			$name = $value["name"];
			$label = $value["label"];
			$label = str_replace(" ","_",$label);
			$related_tabid =  $value["related_tabid"];
			//alert($value);
			$a_format = array();
			$data=array();
			//echo $name."<br><br>";
				switch ($name) {
					case "get_participant":
						$data = $this->get_participant($a_eventid,$module);
						break;
					case "get_speaker_list":
						$data = $this->get_speaker_list($a_eventid,$module);
						break;
					case "get_sponsors_list":
						$data = $this->get_sponsors_list($a_eventid,$module);
						break;
					case "get_knowledge_partners_list":
						$data = $this->get_knowledge_partners_list($a_eventid,$module);
						break;
					case "get_board_list":
						$data = $this->get_board_list($a_eventid,$module);
						break;
					case "get_councilor_list":
						$data = $this->get_councilor_list($a_eventid,$module);
						break;
					case "get_committee_list":
						$data = $this->get_committee_list($a_eventid,$module);
						break;
					case "get_related_list":
						list($data,$name)	= $this->get_related_list($a_eventid,$module,$related_tabid);
						break;
				}

				$a_format = $this->set_format($data,$a_eventid,$name);
				foreach ($a_eventid as $k => $v){
					$a_return[$v][$label] = (isset($a_format[$v]) && !empty($a_format[$v])) ? $a_format[$v] : array();
				}
			}

		$a_responsse = $a_return;
		return $a_responsse;
  }





}
