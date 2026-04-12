<?php
header('Content-Type: text/html; charset=utf-8');
	include("config.inc.php");
	require_once("library/dbconfig.php");
	require_once("library/myLibrary_mysqli.php");
	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;

	$action =$_REQUEST["action"];

	if($action == "getBranch"){		
		$sql = "select aicrm_branch.branchid,aicrm_branch.branch_name,aicrm_branch.branch_name_en
				from aicrm_branch
                inner Join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_branch.branchid
				inner join aicrm_branchcf on aicrm_branch.branchid= aicrm_branchcf.branchid
                where aicrm_crmentity.deleted = 0 and aicrm_branch.branch_name <> '' and  aicrm_branch.branch_status != 'Closed'";
        $sql .=" order by aicrm_branch.branch_name ";
		$data = $myLibrary_mysqli->select($sql);

		$a_return = array();
		if (!empty($data)) {
			foreach ($data as $key => $value){
				$a_value["name"] =    $value["branch_name"];
				$a_value["name_en"] =    $value["branch_name_en"];
				$a_value["id"] =    $value["branchid"];
				$a_value["branchid"] =    $value["branchid"];
				$a_return[] =  $a_value;
			}
		}
		echo json_encode($a_return);
	}
	
	else if($action == "getBuilding"){
		if($_REQUEST["branchid"] != ""){
            $branchid = $_REQUEST["branchid"];
        }
		$sql = "select aicrm_building.* from aicrm_building
				inner join aicrm_crmentity on aicrm_building.buildingid=aicrm_crmentity.crmid 
				and aicrm_crmentity.deleted='0'
				where aicrm_building.branchid='".$branchid."'  order by aicrm_building.building_name
				";
		$data = $myLibrary_mysqli->select($sql);
		$a_return = array();
		if (!empty($data)) {
			foreach ($data as $key => $value){
				$a_value["name"] =    $value["building_name"];
				$a_value["id"] =    $value["buildingid"];
				$a_return[] =  $a_value;
			}
		}
		echo json_encode($a_return);
	}
	else if($action == "getFloor"){
		
		if($_REQUEST["branchid"] != ""){
            $branchid = $_REQUEST["branchid"];
        }
		if($_REQUEST["buildingid"] != ""){
            $buildingid = $_REQUEST["buildingid"];
        }
		$sql = "select distinct aicrm_floor.floorid , aicrm_floor.floor_name as floor_name
				from aicrm_units 
				inner join aicrm_crmentity on aicrm_units.unitsid = aicrm_crmentity.crmid  and aicrm_crmentity.deleted='0'
				inner join aicrm_floor on aicrm_floor.floorid = aicrm_units.floorid
				inner join aicrm_crmentity crm on crm.crmid = aicrm_floor.floorid and crm.deleted = '0'
				where  aicrm_units.branchid='".$branchid ."' and aicrm_units.buildingid='". $buildingid."'
				order by aicrm_floor.floor_name asc ";
		//echo $sql;
		$data = $myLibrary_mysqli->select($sql);
		$a_return = array();

		if (!empty($data)) {
			foreach ($data as $key => $value){
				$a_value["floorid"] =    $value["floorid"];
				$a_value["floor_name"] =    $value["floor_name"];
				$a_return[] = $a_value;
			}
		}
		echo json_encode($a_return);
	}
	else if($action == "getquestionnairetemplate"){
		
		if($_REQUEST["branchid"] != ""){
            $branchid = $_REQUEST["branchid"];
        }
		
		$sql = "select distinct aicrm_questionnairetemplate.questionnairetemplateid , aicrm_questionnairetemplate.questionnairetemplate_no, aicrm_questionnairetemplate.questionnairetemplate_name
				from aicrm_questionnairetemplate 
				inner join aicrm_crmentity on aicrm_questionnairetemplate.questionnairetemplateid = aicrm_crmentity.crmid  and aicrm_crmentity.deleted='0'
				order by aicrm_questionnairetemplate.questionnairetemplateid Desc ";
		$data = $myLibrary_mysqli->select($sql);
		$a_return = array();

		if (!empty($data)) {
			foreach ($data as $key => $value){
				$a_value["questionnairetemplateid"]    =  $value["questionnairetemplateid"];
				$a_value["questionnairetemplate_no"]   =  $value["questionnairetemplate_no"];
				$a_value["questionnairetemplate_name"] =  $value["questionnairetemplate_name"];
				$a_return[] = $a_value;
			}
		}
		echo json_encode($a_return);
	}
	else if($action == "getMedia"){
				
		$sql = "select distinct aicrm_media.mediaid , aicrm_media.media_no, aicrm_media.media_name
				from aicrm_media 
				inner join aicrm_crmentity on aicrm_media.mediaid = aicrm_crmentity.crmid
				where aicrm_crmentity.deleted='0'
				order by aicrm_media.media_name asc";
		//echo $sql;
		$data = $myLibrary_mysqli->select($sql);
		$a_return = array();

		if (!empty($data)) {
			foreach ($data as $key => $value){
				$a_value["mediaid"]    =  $value["mediaid"];
				$a_value["media_no"]   =  $value["media_no"];
				$a_value["media_name"] =  $value["media_name"];
				$a_return[] = $a_value;
			}
		}
		echo json_encode($a_return);
	}
	else if($action == "getUser"){
		
		$sql = "select distinct aicrm_users.id , concat(aicrm_users.first_name,' ',aicrm_users.last_name ) as name
				from aicrm_users 
				where aicrm_users.deleted='0' and aicrm_users.status = 'Active' order by concat(aicrm_users.first_name, aicrm_users.last_name ) asc";
		//echo $sql;
		$data = $myLibrary_mysqli->select($sql);
		$a_return = array();

		if (!empty($data)) {
			foreach ($data as $key => $value){
				$a_value["id"]    =  $value["id"];
				$a_value["name"]   =  $value["name"];
				$a_return[] = $a_value;
			}
		}
		echo json_encode($a_return);
	}

?>
