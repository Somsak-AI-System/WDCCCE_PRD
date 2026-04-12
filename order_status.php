<?php
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	include("config.inc.php");

	include_once("library/dbconfig.php");
	include_once("library/myFunction.php");
	include_once("library/generate_MYSQL.php");

	global $generate;
	$generate = new generate($dbconfig ,"DB");

	$date=date('d-m-Y');

	/*echo "<pre>"; print_r($_FILES); echo "</pre>"; 
	echo "<pre>"; print_r($_REQUEST); echo "</pre>"; exit;*/

	$crmid = $_REQUEST["crmid"];
	$orderstatus = $_REQUEST["orderstatus"];
	$status = $_REQUEST["status"];
	//$level = $_REQUEST["level"];

	require_once('modules/Order/Order.php');
	require_once('modules/Users/Users.php');
	require_once('include/utils/UserInfoUtil.php');


    $_REQUEST["ajxaction"] = "DETAILVIEW";

	$current_user = new Users();
	$result = $current_user->retrieveCurrentUserInfoFromFile($_SESSION['authenticated_user_id']);
	global $current_user;

	$assignto = '';

	$order_focus = new Order();
	$order_focus->retrieve_entity_info($crmid,"Order");

	if($orderstatus == 'Cancelled'){

		$lost_reason_order = $_REQUEST['lost_reason_order'];
		$order_focus->column_fields['lost_reason_order'] = $lost_reason_order;
		$order_focus->column_fields['order_status_order'] = $orderstatus;

		$text = 'Lost Reason : '.$lost_reason_order;
		$sql = "insert into tbt_orders_log(crmid,orderstatus,adduser,data_update,adddate)
				values('".$crmid."','".$orderstatus."','".$_SESSION['authenticated_user_id']."','".$text."','".date('Y-m-d H:i:s')."');";
	    $generate->query($sql);

	}elseif($orderstatus == 'Completed'){

		$completed_sub_status_order = $_REQUEST['completed_sub_status_order'];
		$order_focus->column_fields['completed_sub_status_order'] = $completed_sub_status_order;
		$completed_remark = $_REQUEST['completed_remark'];
		$order_focus->column_fields['completed_remark'] = $completed_remark;
		$order_focus->column_fields['order_status_order'] = $orderstatus;

		$text = 'Completed Sub Status : '.$completed_sub_status_order.' , Completed Remark : '.$completed_remark;
		$sql = "insert into tbt_orders_log(crmid,orderstatus,adduser,data_update,adddate)
				values('".$crmid."','".$orderstatus."','".$_SESSION['authenticated_user_id']."','".$text."','".date('Y-m-d H:i:s')."');";
	    $generate->query($sql);

	}elseif($status == 'Wait Vendor'){
		$plan_date = $_REQUEST['plan_date'];
		$plan_time = $_REQUEST['plan_time'];
		$order_focus->column_fields['plan_date'] = $plan_date;
		$order_focus->column_fields['plan_time'] = $plan_time;
		$order_focus->column_fields['order_status_order'] = $status;
		//Text
		$text = 'Plan Date : '.date("d-m-Y",strtotime($plan_date)).' , Plan Time : '.$plan_time ;
		$sql = "insert into tbt_orders_log(crmid,orderstatus,adduser,data_update,adddate)
				values('".$crmid."','".$status."','".$_SESSION['authenticated_user_id']."','".$text."','".date('Y-m-d H:i:s')."');";
	    $generate->query($sql);

	}elseif($status == 'Wait Confirm'){
		$mix_easy_site_code = $_REQUEST['mix_easy_site_code'];
		$order_focus->column_fields['mix_easy_site_code'] = $mix_easy_site_code;
		$order_focus->column_fields['order_status_order'] = $status;
		//Text
		$text = 'Mix Easy Site code : '.$mix_easy_site_code ;
		$sql = "insert into tbt_orders_log(crmid,orderstatus,adduser,data_update,adddate)
				values('".$crmid."','".$status."','".$_SESSION['authenticated_user_id']."','".$text."','".date('Y-m-d H:i:s')."');";
	    $generate->query($sql);

	}elseif($status == 'Customer Payment'){
	
		$payment_method = $_REQUEST['payment_method'];
		$receive_money = $_REQUEST['receive_money'];
		$order_focus->column_fields['payment_method'] = $payment_method;
		$order_focus->column_fields['receive_money'] = $receive_money;
		$order_focus->column_fields['order_status_order'] = $status;
		//Text
		$text = 'Payment Method : '.$payment_method.' , Receive money : '.$receive_money ;
		$sql = "insert into tbt_orders_log(crmid,orderstatus,adduser,data_update,adddate)
				values('".$crmid."','".$status."','".$_SESSION['authenticated_user_id']."','".$text."','".date('Y-m-d H:i:s')."');";
	    $generate->query($sql);

	    
	    $cd_po = get_autorun("PO" . substr((date("Y") + 543), -2).date("m"), "Purchase", "4");
	    $cd_in = get_autorun("IN" . substr((date("Y") + 543), -2).date("m"), "Tax", "4");
	    $purchase_no = $cd_po;
        $tax_no = $cd_in;
		$sql_update = " update aicrm_order set purchase_no = '".$purchase_no."' ,tax_no = '" . $tax_no . "' where orderid = '" . $crmid . "' ";
        $generate->query($sql_update);


	}elseif($status == 'Wait Delivery'){

		$site_phone_delivery = $_REQUEST['site_phone_delivery'];
		$plan_date = $_REQUEST['plan_date'];
		$plan_time = $_REQUEST['plan_time'];
		$vendor_site_code = $_REQUEST['vendor_site_code'];
		$vender_plant = $_REQUEST['vender_plant'];
		$order_focus->column_fields['site_phone_delivery'] = $site_phone_delivery;
		$order_focus->column_fields['plan_date'] = $plan_date;
		$order_focus->column_fields['plan_time'] = $plan_time;
		$order_focus->column_fields['vendor_site_code'] = $vendor_site_code;
		$order_focus->column_fields['vender_plant'] = $vender_plant;

		$order_focus->column_fields['order_status_order'] = $status;
		//Text
		$text = 'Site Phone (delivery) : '.$site_phone_delivery.' , Plan date : '.date("d-m-Y",strtotime($plan_date)).' , Plan Time : '.$plan_time.' , Vendor Site code : '. $vendor_site_code.' , Vender plant : '.$vender_plant;
		$sql = "insert into tbt_orders_log(crmid,orderstatus,adduser,data_update,adddate)
				values('".$crmid."','".$status."','".$_SESSION['authenticated_user_id']."','".$text."','".date('Y-m-d H:i:s')."');";
	    $generate->query($sql);

	}elseif($status == 'Start Delivery'){

	   	$order_focus->column_fields['order_status_order'] = $status;
	   	$text = '' ;
		$sql = "insert into tbt_orders_log(crmid,orderstatus,adduser,data_update,adddate)
				values('".$crmid."','".$status."','".$_SESSION['authenticated_user_id']."','".$text."','".date('Y-m-d H:i:s')."');";
	    $generate->query($sql);
	}
	
	
	$order_focus->id = $crmid;
	$order_focus->mode = "edit";
	$order_focus->save("Order");

	$a_reponse["status"] = true;
	$a_reponse["error"] = "" ;
	if($quotationstatus!="Complete"){
		$a_reponse["msg"] = $msg. " Complete" ;
	}
	$a_reponse["result"] = "";
	$a_reponse["url"] = "index.php";
	echo json_encode($a_reponse);



function get_autorun($prefix,$module,$lenght){
	
	global $generate;
	//echo 555; exit;
	$sql="
	SELECT running
	FROM ai_running_doc
	where 1
	and module='".$module."'
	and prefix='".$prefix."' ";
	$res =$generate->process($sql,"all");
	$rows = $generate->num_rows($res);
	if($rows>0){
		//$running = $generate->result($res,0,'running');
		$running = $res[0]['running'];
		$running=$running+1;
		$sql="update ai_running_doc set running='".$running."' where 1 and module='".$module."' and prefix='".$prefix."' ";

		$generate->query($sql, array());
	}else{
		$running=1;
		$sql="insert into ai_running_doc(module,running,prefix)values('".$module."','".$running."','".$prefix."'); ";
		$generate->query($sql, array());
	}

	$cd = $prefix."".str_pad($running,$lenght,"0", STR_PAD_LEFT);
	return $cd;
}


?>