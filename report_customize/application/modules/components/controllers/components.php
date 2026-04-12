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
		//echo $_SESSION['user_id'];
		$a_response= array();
		$userid =  $_SESSION['user_id'];
		//Get Roleid, is Admin
		$a_role = $this->get_role($userid);
					
		$section = @$a_role[0]['section']; //Ἱ�
		$roleid = @$a_role[0]['roleid'];
		$is_admin = @$a_role[0]['is_admin'];
		
		//Get 'parentrole' of $roleid		
		$a_role = $this->get_parent_role($roleid);	
		$parentrole = $a_role[0]['parentrole'];
		$rolename = $a_role[0]['rolename'];
		
		$check = false ;
		//echo "<pre>"; print_r($parentrole); echo "</pre>"; exit;
		//$is_admin = 'off';
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
							where track_report = '1'  and deleted = '0' and section = '".$section."'
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
								where id=".$userid." and track_report = '1'
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
									and status='Active' and deleted = '0' and track_report = '1'
									order by user_name";
				}
		}
		//echo $sql; exit;
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		//echo $this->db->last_query(); exit;
		echo json_encode($a_response);
	}

	public function GetServiceName()
	{
		//echo $_SESSION['user_id'];
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
	   /*$year =date('Y');
	   $year1 =date('Y')-1;
	   $year2 =date('Y')+1;*/
	   
		$sql = "SELECT
			`weekly_id`,
			`weekly_no`,
			`weekly_start_date`,
			`weekly_end_date`,
			`weekly_year` 
		FROM
			aicrm_activity_tran_config_weekly_plan 
		WHERE
			weekly_start_date >= DATE_ADD(curdate(), INTERVAL -6 MONTH)
			AND weekly_end_date <= DATE_ADD(curdate(), INTERVAL 6 MONTH)
		ORDER BY
			weekly_year ASC,
			weekly_id ASC";
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		// echo $this->db->last_query();
		
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
		//echo $sql; exit;		
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
		
		/*$sql = "SELECT `monthly_id` , `monthly_no` , `monthly_start_date` , `monthly_end_date` , `monthly_year`
					FROM aicrm_activity_tran_config_monthly_plan
					WHERE `monthly_year` in ('".$year."','".$year1."','".$year2."') order by monthly_year Desc  ,monthly_id  ASC";*/
		$sql = "SELECT
			`monthly_id` , `monthly_no` , `monthly_start_date` , `monthly_end_date` , `monthly_year` 
		FROM
			aicrm_activity_tran_config_monthly_plan 
		WHERE
			monthly_start_date >= DATE_ADD(curdate(), INTERVAL -7 MONTH)
			AND monthly_start_date <= DATE_ADD(curdate(), INTERVAL 6 MONTH)
		ORDER BY
			monthly_year ASC,
			monthly_id ASC";
					
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
						
			/*$sql = "SELECT send_user_id
					FROM aicrm_activity_tran_send_mail_to
					WHERE user_id = '".$userid."' and deleted = 0";*/
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
				$this->db->like('accountname', $s_accountname, 'both');//before,after,both
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
				$this->db->like('accountname', $s_accountname, 'both');//before,after,both
			}
			$this->db->where($a_condition);
			$this->db->limit($s_rows,$offset);
			$query_data = $this->db->get('aicrm_account');
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
		
		
		
	public function Get_Sale_section()
	{
		//$id = $this->input->get_post('id');
		$userid = $this->input->get_post('id');
		$section = $this->input->get_post('section');
		//$section = $this->input->get_post('section');
		
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
			$sql = "select id as id
						,user_name as user_name 
						, first_name , last_name
						, CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name'
						, IFNULL(area,'') as area
					     , case when section	= '--None--' then '' else section end as section 
					from aicrm_users
					where 
					status='Active'
					and section = '".$section."'
				    order by user_name";
		}
		$query = $this->db->query($sql);
		$a_response =  $query->result_array();
		//echo $this->db->last_query();
		echo json_encode($a_response);
		
	}

		
	}
	
	
	

?>