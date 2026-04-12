<?php

require_once("../library/dbconfig_phase2_upload.php");

class uploadModels {

	public function branch_data($branch_id){
		
		/* Class for connection with database */
		$db = new dq();

		/* STEP 1/4 for-loop of branch images */
		//$sql1 = "SELECT * FROM aicrm_product_img WHERE PDCODE ='".$branch_id."'";
		$sql1 = "SELECT * FROM aicrm_product_img img RIGHT JOIN aicrm_branchs b ON img.PDCODE = b.branchid WHERE b.branchid ='".$branch_id."'";
		
		$branch_data = $db->fetch($sql1);

		return $branch_data;
	}

	public function building_data($branch_id , $list_name){
		
		/* Class for connection with database */
		$db = new dq();

		if($list_name != "") $condition = " AND buildingid IN (".$list_name.")";

		/* STEP 2/4 for-loop of building images */
		//$sql2 = "select buildingid ,cf_2420 as Building from aicrm_crmentity a inner join aicrm_buildingcf b on  a.crmid=b.buildingid where a.deleted=0 and cf_1059='".$branch_id."'";
		$sql2 = "SELECT buildingid ,cf_2420 AS Building ,img.* FROM aicrm_crmentity a INNER JOIN aicrm_buildingcf b ON a.crmid=b.buildingid LEFT JOIN aicrm_product_img img ON b.buildingid = img.PDCODE AND img.refid = '".$branch_id."' WHERE a.deleted=0 AND cf_1059 = '".$branch_id."'".$condition;
		//echo $sql2;
		$building_data = $db->fetch($sql2);

		return $building_data;
	}

	public function floor_data($buildingid , $list_name){
		
		/* Class for connection with database */
		$db = new dq();

		if($list_name != "") $condition = " AND b.cf_2067 IN (".$list_name.")";

		/* STEP 3/4 for-loop of floor images */
	    //$sql3 = "select distinct b.cf_2067 as floor from aicrm_crmentity c inner join aicrm_products a on c.crmid=a.productid inner join aicrm_productcf b on a.productid=b.productid where c.deleted=0 and a.buildingid='".$building['buildingid']."'";
	    $sql3 = "select distinct b.cf_2067 as floor, img.* from aicrm_crmentity c inner join aicrm_products a on c.crmid=a.productid inner join aicrm_productcf b on a.productid=b.productid LEFT JOIN aicrm_product_img img ON b.cf_2067 = img.PDCODE AND img.refid = '".$buildingid."' where c.deleted=0 and a.buildingid='".$buildingid."' ".$condition." ORDER BY abs(b.cf_2067) ASC";
	    //echo $sql3;
	    $floor_data = $db->fetch($sql3);

	    return $floor_data;
	}

	public function product_data($buildingid , $list_name){
		
		/* Class for connection with database */
		$db = new dq();

		if($list_name != "") $condition = " AND b.cf_2072 IN (".$list_name.")";

		/* STEP 4/4 for-loop of product(room type) images */
	    //$sql4 = "select distinct b.cf_2072 as roomtype, cf_2070 as area from aicrm_crmentity c inner join aicrm_products a on c.crmid=a.productid inner join aicrm_productcf b on a.productid=b.productid where c.deleted=0 and a.buildingid='".$building['buildingid']."'";
	    $sql4 = "select distinct b.cf_2072 as roomtype, img.* from aicrm_crmentity c inner join aicrm_products a on c.crmid=a.productid inner join aicrm_productcf b on a.productid=b.productid LEFT JOIN aicrm_product_img img ON b.cf_2072 = img.PDCODE AND img.refid = '".$buildingid."' where c.deleted=0 and a.buildingid='".$buildingid."'".$condition." ORDER BY b.cf_2072 ASC";
	    $product_data = $db->fetch($sql4);

	    return $product_data;
	}

	public function select_file_db($PDCODE , $refid , $img_type , $img_path){
		/* Class for connection with database */
		$db = new dq();

		/*
		echo "<br/><span style='color:#f00'>PDCODE : ".$PDCODE."</span>";
		echo "<br/><span style='color:#f00'>refid : ".$refid."</span>";
		echo "<br/><span style='color:#f00'>img_type : ".$img_type."</span>";
		echo "<br/><span style='color:#f00'>img_folder : ".$img_folder."</span>";
		echo "<br/><span style='color:#f00'>img_path : ".$img_path."</span>";
		echo "<br/><span style='color:#f00'>new_file_name : ".$new_file_name."</span>";
		*/
        $sql = "SELECT ".$img_path." FROM aicrm_product_img WHERE PDCODE = '".$PDCODE."' AND refid = '".$refid."'";
		
		//echo "<br/><br/><span style='color:#0f0'>sql : ".$sql."</span>";

		$select_file_db = $db->fetch($sql);

		return $select_file_db[0];
	}

	public function update_file_db($PDCODE , $refid , $img_type , $img_folder , $img_path , $new_file_name , $count){
		/* Class for connection with database */
		$db = new dq();

		switch($img_type){
            case "branch":  
            				$type = "01";
            				break;
            case "building":
            				$type = "02";
            				break;
            case "floor":
            				$type = "03";
            				break;
            case "product": 
            				$type = "04";
            				break;
        }
		
		if($count > 0){
			/* If it has been image */
			$sql = "UPDATE aicrm_product_img SET ".$img_path." = '".$new_file_name."' , img_type = '".$type."' , img_folder = '".$img_folder."' WHERE PDCODE = '".$PDCODE."' AND refid = '".$refid."'";
		}else{
			/* not recording */
			$sql = "INSERT INTO aicrm_product_img (PDCODE, refid, img_type, img_folder, ".$img_path.") VALUES ('".$PDCODE."', '".$refid."', '".$type."', '".$img_folder."', '".$new_file_name."')";
		}

		echo "<br/><br/><span style='color:#0f0'>sql : ".$sql."</span>";

		$db->query($sql);	
	}

	public function insert_job_image($jobid , $img_folder , $img , $position){

		/* Class for connection with database */
		$db = new dq();

		$query = "INSERT INTO aicrm_job_img (jobid , img_type , img_folder , img , position) VALUES ('".$jobid."' , '02' , '".$img_folder."' , '".$img."' , '".$position."')";

		$data = $db->query($query);
	}

	public function test_show(){

		/* Class for connection with database */
		$db = new dq();

		//--------------------------------------------------------------------------------
		$branch_id = $_GET['crmid'];
		echo "<br/>branch : ".$branch_id;

		/* STEP 1/4 for-loop of branch images */
		$sql1 = "SELECT * FROM aicrm_product_img WHERE PDCODE ='".$branch_id."'";
		echo $sql1;
		$branch_data = $db->fetch($sql1);

		foreach($branch_data as $branch){
		    echo "<br/>branch_img (1) : ".$branch['img1_path'];
		    echo "<br/>branch_img (2) : ".$branch['img2_path'];
		    echo "<br/>branch_img (3) : ".$branch['img3_path'];
		    echo "<br/>branch_img (4) : ".$branch['img4_path'];
		    echo "<br/>branch_img (5) : ".$branch['img5_path'];
		}

		/* STEP 2/4 for-loop of building images */
		//$sql2 = "select buildingid ,cf_2420 as Building from aicrm_crmentity a inner join aicrm_buildingcf b on  a.crmid=b.buildingid where a.deleted=0 and cf_1059='".$branch_id."'";
		$sql2 = "SELECT buildingid ,cf_2420 AS Building ,img.* FROM aicrm_crmentity a INNER JOIN aicrm_buildingcf b ON a.crmid=b.buildingid LEFT JOIN aicrm_product_img img ON b.buildingid = img.PDCODE AND img.refid = '".$branch_id."' WHERE a.deleted=0 AND cf_1059 = '".$branch_id."'";

		$building_data = $db->fetch($sql2);

		foreach($building_data as $building){

		    echo "<br/><b>-->building_data : </b>".$building['buildingid']." ** ".$building['Building']." ==>".$building['img1_path'];

		    /* STEP 3/4 for-loop of floor images */
		    //$sql3 = "select distinct b.cf_2067 as floor from aicrm_crmentity c inner join aicrm_products a on c.crmid=a.productid inner join aicrm_productcf b on a.productid=b.productid where c.deleted=0 and a.buildingid='".$building['buildingid']."'";
		    $sql3 = "select distinct b.cf_2067 as floor, img.* from aicrm_crmentity c inner join aicrm_products a on c.crmid=a.productid inner join aicrm_productcf b on a.productid=b.productid LEFT JOIN aicrm_product_img img ON b.cf_2067 = img.PDCODE AND img.refid = '".$building['buildingid']."' where c.deleted=0 and a.buildingid='".$building['buildingid']."'";
		    $floor_data = $db->fetch($sql3);

		    if($floor_data != null){
			    foreach($floor_data as $floor){
			        echo "<br/>&nbsp;&nbsp; -floor_data : ".$floor['floor']." ==>".$floor['img1_path'];
			    }
			}

		    /* STEP 4/4 for-loop of product(room type) images */
		    //$sql4 = "select distinct b.cf_2072 as roomtype, cf_2070 as area from aicrm_crmentity c inner join aicrm_products a on c.crmid=a.productid inner join aicrm_productcf b on a.productid=b.productid where c.deleted=0 and a.buildingid='".$building['buildingid']."'";
		    $sql4 = "select distinct b.cf_2072 as roomtype, cf_2070 as area, img.* from aicrm_crmentity c inner join aicrm_products a on c.crmid=a.productid inner join aicrm_productcf b on a.productid=b.productid LEFT JOIN aicrm_product_img img ON b.cf_2072 = img.PDCODE AND img.refid = '".$building['buildingid']."' where c.deleted=0 and a.buildingid='".$building['buildingid']."'";
		    $product_data = $db->fetch($sql4);
		    
		    if($product_data != null){
			    foreach($product_data as $product){
			        echo "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *room type : ".$product['roomtype']." ==>".$product['img1_path']." , ".$product['img2_path']." , ".$product['img3_path']." , ".$product['img4_path']." , ".$product['img5_path'];
			    }
			}
		}
		//--------------------------------------------------------------------------------
	}
}
?>