<?
class components extends MX_Controller {
 	public $a_event,$deflaut_module;
	public function __construct()
  	{

   		parent::__construct();
		$this->load->helper('form');
		$this->load->database();
  	}

	private function get_role($userid = "")
  	{
  		if($userid==""){
  			return array();
  		}
  		$a_return = array();
  		$a_condition["aicrm_user2role.userid"] = $userid;
  		$this->db->join('aicrm_users', 'aicrm_user2role.userid = aicrm_users.id', 'left');
  		$this->db->where($a_condition);
  		$query = $this->db->get('aicrm_user2role');
  		$a_return  = $query->result_array() ;
  		return $a_return;
  	}
  	private function get_parent_role($roleid = "")
  	{
  		if($roleid==""){
  			return array();
  		}
  		$a_return = array();
  		$a_condition["roleid"] = $roleid;
  		$this->db->where($a_condition);
  		$query = $this->db->get('aicrm_role');
  		$a_return  = $query->result_array() ;
  		
  		return $a_return;
  	}
	public function GetSale()
	{
		
		$a_response= array();
		$userid =  $_SESSION['user_id'];
		//Get Roleid, is Admin
		$a_role = $this->get_role($userid);
					
		$section = @$a_role[0]['section']; //á¼¹¡
		$roleid = @$a_role[0]['roleid'];
		$is_admin = @$a_role[0]['is_admin'];
		
		//Get 'parentrole' of $roleid		
		$a_role = $this->get_parent_role($roleid);	
		$parentrole = $a_role[0]['parentrole'];
		$rolename = $a_role[0]['rolename'];
		
		$check = false ;
		
		if($is_admin == "on"){
			//'admin' authorization
			$sql = "SELECT id as id
						,user_name as user_name 
						, first_name 
						, last_name
						, CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name' 
					    , IFNULL(area,'') as area
					    , case when section	= '--None--' then '' else section end as section 
					FROM aicrm_users users 
					WHERE deleted = 0 
					AND users.status='Active'
					ORDER BY user_name ASC";
		}else{
			
			if (stripos($rolename, "Admin") !== false) {
				$check = true;
			}
			
			if($check == true){
			
					$sql = "select id as id
								,user_name as user_name 
								, first_name , last_name
								, CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name'
								, IFNULL(area,'') as area
								 , case when section	= '--None--' then '' else section end as section 
							from aicrm_users
							where aicrm_users.track_report = '1'  and deleted = '0'
							and status='Active'
					  	    order by user_name";
				}else{
					$sql = "select id as id
									,user_name as user_name 
									, first_name , last_name
									, CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name'
									, IFNULL(area,'') as area
									 , case when section	= '--None--' then '' else section end as section 
								from aicrm_users
								where id=".$userid." and aicrm_users.track_report = '1'
								and status='Active'
							union
								select aicrm_user2role.userid as id
										,aicrm_users.user_name as user_name 
										, aicrm_users.first_name  , aicrm_users.last_name
										, CONCAT(aicrm_users.first_name, ' ', aicrm_users.last_name,' [',aicrm_users.user_name,']') as 'sale_name'
										, IFNULL(aicrm_users.area,'') as area
										, case when aicrm_users.section	= '--None--' then '' else aicrm_users.section end as section
								from aicrm_user2role
								inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid
								inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid
								where (aicrm_role.parentrole like '".$parentrole."::%' OR aicrm_role.roleid = '".$roleid."')
									and status='Active' and deleted = '0' and aicrm_users.track_report = '1'
									order by user_name";
				}
		}
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		echo json_encode($a_response);
	}

	public function GetSaleTeam(){
		$a_response= array();
		$sql = "SELECT roleid AS id,rolename FROM aicrm_role WHERE roleid NOT IN ('H1', 'H2', 'H939' ,'H944') ORDER BY parentrole ASC";
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		echo json_encode($a_response);
	}

	public function GetStageDeal(){
		$a_response= array();
		$sql = "SELECT stageid AS id , CONCAT(stage, ' ',`percentage`) AS `stage`  FROM `aicrm_stage` WHERE presence = 1 AND (`percentage` IS NOT NULL OR `percentage` != '') ";
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		echo json_encode($a_response);
	}

	public function GetStatusDeal(){
		$a_response= array();
		$sql = "SELECT stageid AS id , stage AS `status` FROM `aicrm_stage` WHERE presence = 1";
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		echo json_encode($a_response);
	}



	public function GetServiceName()
	{
		$a_response= array();
		$userid =  $_SESSION['user_id'];
		//Get Roleid, is Admin
		$a_role = $this->get_role($userid);		
		$roleid = @$a_role[0]['roleid'];
		$is_admin = @$a_role[0]['is_admin'];
		
		//Get 'parentrole' of $roleid		
		$a_role = $this->get_parent_role($roleid);		;
		$parentrole = $a_role[0]['parentrole'];
		
		if($is_admin == "on"){
			//'admin' authorization
			$sql = "SELECT id as id
						,user_name as user_name 
						, first_name 
						, last_name
						, CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name' 
					    , IFNULL(area,'') as area
					    , case when section	= '--None--' then '' else section end as section 
					FROM aicrm_users users 
					WHERE deleted = 0 
					ORDER BY user_name ASC";
		}else{
			$sql = "select id as id
						,user_name as user_name 
						, first_name , last_name
						, CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name'
						, IFNULL(area,'') as area
					     , case when section	= '--None--' then '' else section end as section 
					from aicrm_users
					where id=".$userid."
					and status='Active'
				union
					select aicrm_user2role.userid as id
							,aicrm_users.user_name as user_name 
							, aicrm_users.first_name  , aicrm_users.last_name
							, CONCAT(aicrm_users.first_name, ' ', aicrm_users.last_name,' [',aicrm_users.user_name,']') as 'sale_name'
							, IFNULL(aicrm_users.area,'') as area
					   		, case when aicrm_users.section	= '--None--' then '' else aicrm_users.section end as section
					from aicrm_user2role
					inner join aicrm_users on aicrm_users.id=aicrm_user2role.userid
					inner join aicrm_role on aicrm_role.roleid=aicrm_user2role.roleid
					where aicrm_role.parentrole like '".$parentrole."::%'
						and status='Active' and deleted = '0'
						order by user_name";
		}
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		//echo $this->db->last_query();
		echo json_encode($a_response);
	}
	public function Getsection()
	{
		$a_response= array();
		$sql = "SELECT * FROM aicrm_section WHERE section <> '--None--' ORDER BY `sectionid` ASC ";
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		echo json_encode($a_response);
	}
	public function Getweeklyplan()
	{
	   $year =date('Y');
	   $year1 =date('Y')-1;
	   $year2 =date('Y')+1;
		$sql = "SELECT `weekly_id` , `weekly_no` , `weekly_start_date` , `weekly_end_date` , `weekly_year`
					FROM aicrm_activity_tran_config_weekly_plan
					WHERE `weekly_year` in ('".$year."','".$year1."','".$year2."') order by weekly_year Desc ,weekly_id ASC";
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		//echo $this->db->last_query();
		echo json_encode($a_response);	
	}
	public function Getbrandsalesvisit()
	{

		$sql = "SELECT cf_4356id,cf_4356 
				FROM aicrm_cf_4356
				where cf_4356 <> '--None--'
				order by cf_4356  ASC ";
					
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		//echo $this->db->last_query();
		//alert($a_response);exit;
		echo json_encode($a_response);	
	}
	public function Getmodelsalesvisit()
	{
		$brand = $this->input->get_post('brand');

		$sql = "SELECT targetvalues
				FROM aicrm_picklist_dependency
				where sourcevalue <> '--None--' and sourcevalue = '".$brand."'
				order by id ASC ";
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		$target =str_replace("[","",$a_response[0]['targetvalues']);
		$target =str_replace("]","",$target);
		$value = explode(",", $target);

		$i=0 ;
		foreach($value as $key => $val){
			$data = str_replace('"--None--"','',$val);
			if($data != ''){
				$a_data[$i]['targetvalues'] = str_replace('"','',$val);
				$i++;
			}
		}
		echo json_encode($a_data);	
	}
	public function Getmonthlyplan()
	{
		
	   $year =date('Y');
	   $year1 =date('Y')-1;
	   $year2 =date('Y')+1;		
		
		$sql = "SELECT `monthly_id` , `monthly_no` , `monthly_start_date` , `monthly_end_date` , `monthly_year`
					FROM aicrm_activity_tran_config_monthly_plan
					WHERE `monthly_year` in ('".$year."','".$year1."','".$year2."') order by monthly_year Desc  ,monthly_id  ASC";
					
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		//echo $this->db->last_query();
		echo json_encode($a_response);	
	}
	public function GetReporttype()
	{
		$data = array(
			"0" => array(
				"reportcode" => "Weekly Plan",
				"reportname" => "Weekly Plan",
			),
			"1" => array(
				"reportcode" => "Monthly Plan",
				"reportname" => "Monthly Plan",
			)
		);
		 echo json_encode($data);
	}
	public function GetDepartment()
	{
		$a_data= array();
		$columnname = "section";
		$a_data = $this->get_picklistvalue($columnname);
		echo json_encode($a_data);
	}	
	public function Getactivitytype()
	{
		$a_data= array();
		$columnname = "activitytype";
		$a_data = $this->get_picklistvalue($columnname);
		echo json_encode($a_data); //exit;
	}
	public function Get_send_to(){
		
		$a_response= array();
		$userid = $this->input->get_post('userid');

		$sql = "SELECT aicrm_activity_tran_send_mail_to.send_user_id , aicrm_users.section
				FROM aicrm_users 
				Left Join aicrm_activity_tran_send_mail_to on aicrm_activity_tran_send_mail_to.user_id = aicrm_users.id
				WHERE aicrm_users.id = '".$userid."' and aicrm_users.deleted = 0";
		
				
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();			
		
		if (empty($a_response)) {
			$a_response[0]['send_user_id'] = false ;
		}
		
		echo json_encode(@$a_response[0]);
	}
	public function GetAccount()
	{
		$a_response= array();
		$s_accountname = $this->input->get_post('accountname');
		$s_page = $this->input->get_post('page');
		$s_rows = $this->input->get_post('rows');

		$s_page = ($s_page=="") ? "1" : $s_page ;
		$s_rows = ($s_rows=="") ? "10" : $s_rows ;

		$offset =($s_page-1)*$s_rows;

		$a_condition["aicrm_crmentity.setype"] = "Accounts";
		$a_condition["aicrm_crmentity.deleted"] = "0";

		if($s_accountname!=""){
			$this->db->like('accountname', $s_accountname);//before,after,both
			$this->db->or_like('account_no', $s_accountname);
		}

		$this->db->select("count(DISTINCT aicrm_account.accountid) as total");
		$this->db->join('aicrm_accountscf', 'aicrm_accountscf.accountid = aicrm_account.accountid', 'left');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_account.accountid', 'inner');
		$this->db->where($a_condition);
		$query = $this->db->get('aicrm_account');
		$a_result  = $query->result_array() ;
		$a_response["total"]  = (isset($a_result[0]["total"]) && $a_result[0]["total"] !="") ? $a_result[0]["total"] : 0;


		$this->db->select("aicrm_account.*,aicrm_accountscf.*");
		$this->db->join('aicrm_accountscf', 'aicrm_accountscf.accountid = aicrm_account.accountid', 'left');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_account.accountid', 'inner');
		if($s_accountname!=""){
			$this->db->like('accountname', $s_accountname);//before,after,both
			$this->db->or_like('account_no', $s_accountname);
		}
		$this->db->where($a_condition);
		$this->db->limit($s_rows,$offset);
		$query_data = $this->db->get('aicrm_account');

		$a_data = $query_data->result_array() ;
		$a_response["rows"]  =$a_data;
		//alert($a_response);
		echo json_encode($a_response);
	}

	public function ProjectsName()
	{
		$a_response= array();
		$s_projects_name = $this->input->get_post('projects_name');
		$s_page = $this->input->get_post('page');
		$s_rows = $this->input->get_post('rows');

		$s_page = ($s_page=="") ? "1" : $s_page ;
		$s_rows = ($s_rows=="") ? "10" : $s_rows ;

		$offset =($s_page-1)*$s_rows;

		$a_condition["aicrm_crmentity.setype"] = "Projects";
		$a_condition["aicrm_crmentity.deleted"] = "0";

		if($s_projects_name!=""){
			$this->db->like('projects_name', $s_projects_name);//before,after,both
			$this->db->or_like('projects_no', $s_projects_name);
		}

		$this->db->select("count(DISTINCT aicrm_projects.projectsid) as total");
		$this->db->join('aicrm_projectscf', 'aicrm_projectscf.projectsid = aicrm_projects.projectsid', 'left');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_projects.projectsid', 'inner');
		$this->db->where($a_condition);
		$query = $this->db->get('aicrm_projects');
		$a_result  = $query->result_array() ;
		$a_response["total"]  = (isset($a_result[0]["total"]) && $a_result[0]["total"] !="") ? $a_result[0]["total"] : 0;


		$this->db->select("aicrm_projects.*,aicrm_projectscf.*");
		$this->db->join('aicrm_projectscf', 'aicrm_projectscf.projectsid = aicrm_projects.projectsid', 'left');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_projects.projectsid', 'inner');
		if($s_projects_name!=""){
			$this->db->like('projects_name', $s_projects_name);//before,after,both
			$this->db->or_like('projects_no', $s_projects_name);
		}
		$this->db->where($a_condition);
		$this->db->limit($s_rows,$offset);
		$query_data = $this->db->get('aicrm_projects');

		$a_data = $query_data->result_array() ;
		$a_response["rows"]  =$a_data;
		//alert($a_response);
		echo json_encode($a_response);
	}

	public function ProjectsNo()
	{
		$a_response= array();
		$s_projects_no = $this->input->get_post('projects_no');
		$s_page = $this->input->get_post('page');
		$s_rows = $this->input->get_post('rows');

		$s_page = ($s_page=="") ? "1" : $s_page ;
		$s_rows = ($s_rows=="") ? "10" : $s_rows ;

		$offset =($s_page-1)*$s_rows;

		$a_condition["aicrm_crmentity.setype"] = "Projects";
		$a_condition["aicrm_crmentity.deleted"] = "0";

		if($s_projects_no!=""){
			$this->db->like('projects_no', $s_projects_no);//before,after,both
			$this->db->or_like('projects_name', $s_projects_no);
		}

		$this->db->select("count(DISTINCT aicrm_projects.projectsid) as total");
		$this->db->join('aicrm_projectscf', 'aicrm_projectscf.projectsid = aicrm_projects.projectsid', 'left');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_projects.projectsid', 'inner');
		$this->db->where($a_condition);
		$query = $this->db->get('aicrm_projects');
		$a_result  = $query->result_array() ;
		$a_response["total"]  = (isset($a_result[0]["total"]) && $a_result[0]["total"] !="") ? $a_result[0]["total"] : 0;


		$this->db->select("aicrm_projects.*,aicrm_projectscf.*");
		$this->db->join('aicrm_projectscf', 'aicrm_projectscf.projectsid = aicrm_projects.projectsid', 'left');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_projects.projectsid', 'inner');
		if($s_projects_no!=""){
			$this->db->like('projects_no', $s_projects_no);//before,after,both
			$this->db->or_like('projects_name', $s_projects_no);
		}
		$this->db->where($a_condition);
		$this->db->limit($s_rows,$offset);
		$query_data = $this->db->get('aicrm_projects');

		$a_data = $query_data->result_array() ;
		$a_response["rows"]  =$a_data;
		//alert($a_response);
		echo json_encode($a_response);
	}

	public function QuoteNo()
	{
		$a_response= array();
		$s_quote_no = $this->input->get_post('quote_no');
		$s_page = $this->input->get_post('page');
		$s_rows = $this->input->get_post('rows');

		$s_page = ($s_page=="") ? "1" : $s_page ;
		$s_rows = ($s_rows=="") ? "10" : $s_rows ;

		$offset =($s_page-1)*$s_rows;

		$a_condition["aicrm_crmentity.setype"] = "Quotes";
		$a_condition["aicrm_crmentity.deleted"] = "0";

		if($s_quote_no!=""){
			$this->db->like('quote_no', $s_quote_no);//before,after,both
			$this->db->or_like('projects_name', $s_quote_no);
		}

		$this->db->select("count(DISTINCT aicrm_quotes.quoteid) as total");
		$this->db->join('aicrm_quotescf', 'aicrm_quotescf.quoteid = aicrm_quotes.quoteid', 'left');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_quotes.quoteid', 'inner');
		$this->db->where($a_condition);
		$query = $this->db->get('aicrm_quotes');
		$a_result  = $query->result_array() ;
		$a_response["total"]  = (isset($a_result[0]["total"]) && $a_result[0]["total"] !="") ? $a_result[0]["total"] : 0;


		$this->db->select("aicrm_quotes.*,aicrm_quotescf.*");
		$this->db->join('aicrm_quotescf', 'aicrm_quotescf.quoteid = aicrm_quotes.quoteid', 'left');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_quotes.quoteid', 'inner');
		if($s_quote_no!=""){
			$this->db->like('quote_no', $s_quote_no);//before,after,both
			$this->db->or_like('projects_name', $s_quote_no);
		}
		$this->db->where($a_condition);
		$this->db->limit($s_rows,$offset);
		$query_data = $this->db->get('aicrm_quotes');

		$a_data = $query_data->result_array() ;
		$a_response["rows"]  =$a_data;
		//alert($a_response);
		echo json_encode($a_response);
	}

	public function get_picklistvalue($columnname=null)
	{
		if(empty($columnname)) return null;
		$sql = " select DISTINCT ".$columnname." as eventtypecode, ".$columnname." as eventtypename
				FROM aicrm_".$columnname."
				INNER JOIN aicrm_role2picklist ON aicrm_".$columnname.".picklist_valueid = aicrm_role2picklist.picklistvalueid
				AND roleid
				IN ( 	'H2' 	)
				where (".$columnname."<>'' and ".$columnname." <>'--None--')
				ORDER BY sortid ";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		return $a_data;
	}	
	public function getRole($userid){
		$sql = "SELECT roleid FROM aicrm_user2role WHERE  userid= '".$userid."'";
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();	
        return $a_response[0]['roleid'];
    }
    public function getParentRole($roleid){
		$sql = "SELECT parentrole FROM aicrm_role WHERE  roleid= '".$roleid."'";
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();	
        return $a_response[0]['parentrole'];
    }
	public function getRoleFromParentRole($parentrole){
		$sql = "SELECT roleid FROM aicrm_role WHERE  parentrole = '".$parentrole."'";
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();	
        return $a_response[0]['roleid'];
    }
	public function Get_Sale_section()
	{
		$userid = $this->input->get_post('id');
		$section = $this->input->get_post('section');
		
		$a_response= array();
		//Get Roleid, is Admin
		$a_role = $this->get_role($userid);		
		$roleid = @$a_role[0]['roleid'];
		$is_admin = @$a_role[0]['is_admin'];
		
		if($is_admin == "on"){
			//'admin' authorization
			$sql = "SELECT id as id
						,user_name as user_name 
						, first_name 
						, last_name
						, CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name' 
					    , IFNULL(area,'') as area
					    , case when section	= '--None--' then '' else section end as section 
					FROM aicrm_users users 
					WHERE deleted = 0 
					ORDER BY user_name ASC";
		}else{
			$role = $this->getRole($_SESSION['user_id']);
			$parentrole = $this->getParentRole($role);
			$roleid = str_replace("::".$role,"",$parentrole);
			$roleid = $this->getRoleFromParentRole($roleid);
			if($roleid=="H2"){
					$sql = "SELECT id as id
					,user_name as user_name 
					, first_name 
					, last_name
					, CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name' 
					, IFNULL(area,'') as area
					, case when section	= '--None--' then '' else section end as section 
				FROM aicrm_users users 
				WHERE deleted = 0 
				ORDER BY user_name ASC";
			}else{
				$sql = "SELECT aicrm_users.id as id
						,aicrm_users.user_name as user_name 
						, aicrm_users.first_name 
						, aicrm_users.last_name
						, CONCAT(aicrm_users.first_name, ' ', aicrm_users.last_name,' [',aicrm_users.user_name,']') as 'sale_name' 
					    , IFNULL(aicrm_users.area,'') as area
					    , case when aicrm_users.section	= '--None--' then '' else aicrm_users.section end as section 
					FROM aicrm_users
					LEFT JOIN `aicrm_user2role` ON `aicrm_users`.`id` = `aicrm_user2role`.`userid`
					WHERE deleted = 0 
					AND `aicrm_user2role`.`roleid` = '".$roleid."'
					ORDER BY aicrm_users.user_name ASC";
			}
			// $sql = "select id as id
			// 			,user_name as user_name 
			// 			, first_name , last_name
			// 			, CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name'
			// 			, IFNULL(area,'') as area
			// 		     , case when section	= '--None--' then '' else section end as section 
			// 		from aicrm_users
			// 		where 
			// 		status='Active'
			// 		and section = '".$section."'
			// 	    order by user_name";
		}
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		//echo $this->db->last_query();
		echo json_encode($a_response);	
	}

	public function GetmonthlyReport()
	{
		
	   $year =date('Y');
	   $year1 =date('Y')-1;
	   $year2 =date('Y')+1;		
		
		$sql = "SELECT `monthly_id` , DATE_FORMAT(monthly_start_date,'%M %Y') monthly_no, `monthly_start_date` , `monthly_end_date` , `monthly_year`
					FROM aicrm_activity_tran_config_monthly_plan
					WHERE `monthly_year` in ('".$year."','".$year1."','".$year2."') order by monthly_year Desc  ,monthly_id  ASC";
					
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		// echo $this->db->last_query();
		echo json_encode($a_response);	
	}

	public function GetProjectType()
	{
		$sql = "SELECT project_typeid, project_type FROM aicrm_project_type WHERE presence=1 ORDER BY project_type ";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}
	
	public function GetProjectSize()
	{
		$sql = "SELECT project_sizeid, project_size FROM aicrm_project_size WHERE presence=1 ORDER BY project_size ";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function GetProjectOpportunity()
	{
		$sql = "SELECT project_opportunityid, project_opportunity FROM aicrm_project_opportunity WHERE presence=1 ORDER BY project_opportunity ";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function GetProjectOrderStatus()
	{
		$sql = "SELECT projectorder_statusid, projectorder_status FROM aicrm_projectorder_status WHERE presence=1 ORDER BY projectorder_status ";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function GetSaleRap()
	{
		$sql = "SELECT aicrm_users.id AS id, CONCAT( aicrm_users.first_name, ' ', aicrm_users.last_name, ' [', aicrm_users.user_name, ']' ) AS 'sale_name' FROM aicrm_users WHERE deleted = 0 and `status` = 'Active' ORDER BY aicrm_users.user_name ASC";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function GetQuotationStatus()
	{
		$sql = "SELECT quotation_statusid, quotation_status FROM aicrm_quotation_status WHERE presence=1 and quotation_status != '--None--' ORDER BY quotation_status ";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function GetQuotationType()
	{
		$sql = "SELECT quotation_typeid, quotation_type FROM aicrm_quotation_type WHERE presence=1 and quotation_type != '--None--' ORDER BY quotation_type ";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function GetProductBrand()
	{
		$sql = "SELECT product_brandid, product_brand FROM aicrm_product_brand WHERE presence=1 and product_brand != '--None--' ORDER BY product_brand ";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function GetFinishName()
	{
		$sql = "SELECT product_finishid, product_finish FROM aicrm_product_finish WHERE presence=1 and product_finish != '--None--' ORDER BY product_finish ";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function GetSizeFT()
	{
		$sql = "SELECT product_size_ftid, product_size_ft FROM aicrm_product_size_ft WHERE presence=1 and product_size_ft != '--None--' ORDER BY product_size_ft ";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function GetThickness()
	{
		$sql = "SELECT product_thinknessid, product_thinkness FROM aicrm_product_thinkness WHERE presence=1 and product_thinkness != '--None--' ORDER BY product_thinkness ";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function GetGrade()
	{
		$sql = "SELECT product_gradeid, product_grade FROM aicrm_product_grade WHERE presence=1 and product_grade !='--None--' and product_grade !='' ORDER BY product_grade";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function  GetProductStatus()
	{
		$sql = "SELECT producttatusid, producttatus FROM aicrm_producttatus WHERE presence=1 and producttatus !='' ORDER BY producttatus";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function  GetShippingMethod()
	{
		$sql = "SELECT shipping_methodid, shipping_method FROM aicrm_shipping_method WHERE presence=1 and shipping_method !='' ORDER BY shipping_method";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function  GetItemStatus()
	{
		$sql = "SELECT 'Pending' AS item_status UNION ALL SELECT 'Intransit' AS item_status UNION ALL SELECT 'Completed' AS item_status";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function  GetSafetyStock()
	{
		$sql = "SELECT '>= 0' AS safety_stock UNION ALL SELECT '< 0' AS safety_stock";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}
	
	public function  GetOrderBySafety()
	{
		$sql = "SELECT '>= 0' AS order_by_safety UNION ALL SELECT '< 0' AS order_by_safety";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function  GetSafetyMonth()
	{
		$sql = "SELECT '4' AS safety_month UNION ALL SELECT '5' AS safety_month";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function  GetFinalbyOrder2mth()
	{
		$sql = "SELECT '>= 0' AS final_by_order_2_mth UNION ALL SELECT '< 0' AS final_by_order_2_mth";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function  GetPurchasingfor2mth()
	{
		$sql = "SELECT 'Yes' AS purchasing_2_mth UNION ALL SELECT 'No' AS purchasing_2_mth";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function  GetFinalbyOrder3mth()
	{
		$sql = "SELECT '>= 0' AS final_by_order_3_mth UNION ALL SELECT '< 0' AS final_by_order_3_mth";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function  GetPurchasingfor3mth()
	{
		$sql = "SELECT 'Yes' AS purchasing_3_mth UNION ALL SELECT 'No' AS purchasing_3_mth";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function  GetFinalbyOrder4mth()
	{
		$sql = "SELECT '>= 0' AS final_by_order_4_mth UNION ALL SELECT '< 0' AS final_by_order_4_mth";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}

	public function  GetPurchasingfor4mth()
	{
		$sql = "SELECT 'Yes' AS purchasing_4_mth UNION ALL SELECT 'No' AS purchasing_4_mth";
		$query = $this->db->query($sql);
		$a_data =  $query->result_array();
		echo json_encode($a_data);
	}


	

		
}

?>