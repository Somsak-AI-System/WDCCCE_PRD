<?php  	
	session_start();
	include("../../config.inc.php");
	include("../../library/dbconfig.php");
	include("../../library/myFunction.php");
	include("../../library/generate_MYSQL.php");
	//include("library/function.php");
	global $generate,$current_user;
	$generate = new generate($dbconfig ,"DB");
		
	$a_response= array();
		$sql = "select id as id
					,user_name as user_name 
					, first_name , last_name
					, CONCAT(first_name, ' ', last_name,' [',user_name,']') as 'sale_name'
					, IFNULL(area,'') as area
				    , case when section = '--None--' then '' else section end as section 
					from aicrm_users
					where 
					status='Active'
					and section = '". $_REQUEST['section']."'
					or is_admin = 'on'
				    order by user_name";
		
		$data = $generate->process($sql,"all");	
		//$a_response =  $query->result_array();
		echo json_encode($data);
	
	
	
?>