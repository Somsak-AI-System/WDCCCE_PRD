<?
	function get_label_name($blocklabel){
		if($blocklabel=="LBL_USERLOGIN_ROLE"){
			$block_name="User Login";
		}else if($blocklabel=="LBL_SERVICEREQUE_INFORMATION"){
			$block_name="Job Information";
		}else if($blocklabel=="LBL_EVENT_INFORMATION"){
			$block_name="Event Information";
		}else if($blocklabel=="LBL_CUSTOM_INFORMATION"){
			$block_name="Custom Information";
		}else if($blocklabel=="LBL_ACCOUNT_INFORMATION"){
			$block_name="Account Information";
		}else if($blocklabel=="LBL_ADDRESS_INFORMATION"){
			$block_name="Address Information";
		}else if($blocklabel=="LBL_JOBDETAIL_INFORMATION"){
			$block_name="Call Detail Information";
		}else{
			$block_name=$blocklabel;
		}
		return 	$block_name;
	}
?>