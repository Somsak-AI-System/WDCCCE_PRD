<?php
/* Class for models */
require_once("../config.inc.php");
require_once("upload_model.php");

class uploadControllers {

	public function test_show(){
		/* Use class is input_jobsModels */
		$uploadModels = new uploadModels();

		$uploadModels->test_show();
	}

	public function branch_data($branch_id){
		/* Use class is input_jobsModels */
		$uploadModels = new uploadModels();

		$branch_data = $uploadModels->branch_data($branch_id , $list_name);

		return $branch_data[0];
	}

	public function building_data($branch_id , $list_name){
		/* Use class is input_jobsModels */
		$uploadModels = new uploadModels();

		$building_data = $uploadModels->building_data($branch_id , $list_name);

		return $building_data;
	}

	public function floor_data($buildingid , $list_name){
		/* Use class is input_jobsModels */
		$uploadModels = new uploadModels();

		$floor_data = $uploadModels->floor_data($buildingid , $list_name);

		return $floor_data;
	}

	public function product_data($buildingid , $list_name){
		/* Use class is input_jobsModels */
		$uploadModels = new uploadModels();

		$product_data = $uploadModels->product_data($buildingid , $list_name);

		return $product_data;
	}

	public function display_upload_image($element , $img){
    
		switch ($element) {
	    	case 'thumbnail':
					(isset($img) && $img !="")? $style = "display:block": $style = "display:none";
			break;
	    	
	    	case 'button':
					(!isset($img) || $img =="")? $style = "display:block": $style = "display:none";
			break;
	    }

	    return $style;
	}

	public function option_building_data($branch_id){
		
		$building_data = $this->building_data($branch_id , "");

		$select;
		$select .= "<select id='building_id' class='btn btn-default btn-sm' style='width:180px'>";
		$select .= "<option value=''>-------- เลือก Building ------</option>";

		if($building_data != null){

			foreach ($building_data as $data) {

				if($data['Building'] == "") $name = "ไม่มีชื่อ"; else $name = $data['Building'];
				$select .=  "<option value='".$data['buildingid']."'>".$name."</option>";
			}
		}

		$select .= "</select>";
		echo $select;
	}

	public function list_name_building_data($building_data){
		$select;
		$select .= "<select id='list_name' multiple='multiple'>";

		if($building_data != null){

			foreach ($building_data as $data) {

				if($data['Building'] == "") $name = "ไม่มีชื่อ"; else $name = $data['Building'];
				$select .=  "<option value='".$data['buildingid']."'>".$name."</option>";
			}
		}

		$select .= "</select>";
		echo $select;
	}

	public function list_name_floor_data($floor_data){
		$select;
		$select .= "<select id='list_name' multiple='multiple'>";

		if($floor_data != null){

			foreach ($floor_data as $data) {

				if($data['floor'] == "") $name = "ไม่มีชื่อ"; else $name = $data['floor'];
				$select .=  "<option value='".$data['floor']."'>".$name."</option>";
			}
		}

		$select .= "</select>";
		echo $select;	
	}

	public function list_name_product_data($product_data){
		$select;
		$select .= "<select id='list_name' multiple='multiple'>";

		if($product_data != null){

			foreach ($product_data as $data) {

				if($data['roomtype'] == "") $name = "ไม่มีชื่อ"; else $name = $data['roomtype'];
				$select .=  "<option value='".$data['roomtype']."'>".$name."</option>";
			}
		}

		$select .= "</select>";
		echo $select;
	}
	
}


if ($_POST['mode'] == "upload_img"){

	function moveFiles($temp_path , $file_temp , $file_name, $jobid){
		$cleanName 		= preg_replace(array("/\s+/", "/[^-\.\w]+/"), array("_", ""),  trim($file_name)); 
		$new_file_name	= "1.jpg";
		$full_path		= $temp_path.$new_file_name;

		/* check to existing folder */
		if (!is_dir($temp_path)) {
		   	@mkdir($temp_path);         
		}
		move_uploaded_file($file_temp , $full_path);
		return $new_file_name;
	}
	
	$file_maximumsize = 1048576 * 3; // Maximum size is 3MB

	$crmid 		= $_POST['crmid'];
	$snowid  	= $_POST['snowid'];
	$module    	= $_POST['module'];
	$index    	= $_POST['index'];
	$position   = '' ;
	$file_name 	= $_FILES['image']['name'];
	$file_temp 	= $_FILES['image']['tmp_name'];
	$file_type 	= $_FILES['image']['type'];
	$file_error = $_FILES['image']['error'];
	$file_size 	= $_FILES['image']['size'];

	$temp_path 	= "";
	
	if($file_name != ""){

		if($file_error == 0){

			if($file_size <= $file_maximumsize){

				if($file_type == "image/gif" || $file_type == "image/jpeg" || $file_type == "image/pjpeg" || $file_type == "image/png" || $file_type == "image/x-png" || $file_type == "image/bmp"){

					/* Move file from file temp to my temp folder */
					$new_file_name = moveFiles($temp_path , $file_temp , $file_name , $crmid);				
					
					//$url = $_SERVER['HTTP_HOST']."/moai/WB_Service_AI/image/upload_image";
					$url = $site_URL_service."image/upload_image";
					//echo $url; exit;
					$image = '1.jpg';
					$type = pathinfo($image, PATHINFO_EXTENSION);
					$data = file_get_contents($image);
					//$dataUri = 'data:image/' . $type . ';base64,' . base64_encode($data);
					$dataUri = base64_encode($data);
					
					$data1=array(
						'crmid' => $crmid,
						'module' => $module,
						'smownerid' => $snowid ,
					);
					
					$data1['image']['0'] = $dataUri;
					
						$data_save[]=$data1;

						$fields = array(
						'AI-API-KEY'=>"1234",
						'module'=> $module,
						'data'=> $data_save,
						);
					
						$fields_string = json_encode($fields);
						$json_url = $url;
						$json_string = $fields_string;
						
						$ch = curl_init( $json_url );
						// Configuring curl options
						$options = array(
							CURLOPT_POST => false,
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
							CURLOPT_POSTFIELDS => $json_string,
							CURLOPT_SSL_VERIFYPEER => false,
							CURLOPT_SSL_VERIFYHOST => false,
						);
						 
						// Setting curl options
						curl_setopt_array( $ch, $options );
					
						$result =  curl_exec($ch); // Getting jSON result string

					$error = false;

				}else $error = "error_file_type";

			}else $error = "error_file_size";

		}else $error = "error_file_error";

	}else $error = "error_file_name";
	
	echo "
	<script>
		window.parent.afterUploaded('".$crmid."' , '".$error."' , '".$src_file_name."' , '".$index."' , '".$temp_id."');
	</script>
	";
}


?>