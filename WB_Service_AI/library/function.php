<?php
	global $generate,$img_path;
	require_once("library/generate_MYSQL.php");
	require_once("library/dbconfig.php");
	require_once("library/myFunction.php");
	require_once("library/f_user.php");
	require_once("library/f_query.php");
	require_once("library/f_search.php");
	require_once("library/f_search_calendar.php");
	require_once("library/f_save_data.php");
	require_once("library/f_get_label.php");
	require_once("library/f_get_data_list.php");
	require_once("library/f_get_pk.php");
	require_once("library/f_update_job.php");
	$generate = new generate($dbconfig,"DB");
	//$img_path="http://crmthai.net/cbci/Signature/";
	
	function Get_Tab_Name($tabid){
		global $generate;
		$sql="select * from aicrm_tab where tabid='".$tabid."' ";
		$data= $generate->process($sql,"all");
		return $data[0]['tablabel'];
	}
	function Get_Field_Name($columnname){
		global $generate;
		if($columnname=="crmid"){
			return $columnname;
		}else{
			$sql="select fieldlabel from aicrm_field where columnname='".$columnname."' ";
			$data= $generate->process($sql,"all");
			if($columnname=="event_type"){
				return "Event Type";
			}else if($columnname=="event_name"){
				return "Event Name";
			}else if($columnname=="date_start"){
				return "Event starts Date";
			}else if($columnname=="time_start"){
				return "Event starts Time";
			}else if($columnname=="due_date"){
				return "Event ends Date";
			}else if($columnname=="time_end"){
				return "Event ends Time";
			}else if($columnname=="cf_1225"){
				return "Job Date";
			}else if($columnname=="cf_1355"){
				return "Job Time";
			}else if($columnname=="cf_1262"){
				return "Job External Status";
			}else{
				return $data[0]['fieldlabel'];
			}
		}
	}
	function Get_Value($module,$columnname,$table_name,$crmid){
		global $generate;
		$PK=get_pk($table_name);
		if($columnname=="cf_1355" || $columnname=="time_start" || $columnname=="time_end"){
			$sql=" select replace(".$columnname.",'.',':')as ".$columnname." from ".$table_name." where ".$PK."='".$crmid."' ";
		}else{
			$sql=" select ".$columnname." from ".$table_name." where ".$PK."='".$crmid."' ";
		}

		$data= $generate->process($sql,"all");

		if(count($data)>0){
			return $data[0][$columnname];
		}else{
			return "";
		}
	}
	
	function Get_PicklistValue($columnname){
		global $generate;
		if($columnname=="cf_1281"){
			$sql = "select user_name as ".$columnname." from aicrm_users  where deleted=0 and cf_1280=1  order by user_name ";
		}else if($columnname=="cf_1352" || $columnname=="cf_1372"){
			$sql = "select user_name as ".$columnname." from aicrm_users  where deleted=0 and cf_1345=1  order by user_name ";
		}else if($columnname=="cf_1202"){
			$sql = "select user_name as ".$columnname." from aicrm_users  where deleted=0 and cf_1375=1  order by user_name ";
		}else if($columnname=="cf_1221" || $columnname=="cf_1223" || $columnname=="cf_1323" || $columnname=="cf_1324" || $columnname=="cf_1325" || $columnname=="cf_1326" || $columnname=="cf_1327" || $columnname=="cf_1225"){
			$sql="select toll_way_name as ".$columnname." from tbm_toll_way where toll_way_status='Active'";
		}else{
			$sql="select ".$columnname." from aicrm_".$columnname." where (".$columnname."<>'' and ".$columnname." <>'--None--') ";
		}
		$data= $generate->process($sql,"all");
		$data1=array();
		if(count($data)>0){
			for($i=0;$i<count($data);$i++){
				$data1[]=$data[$i][$columnname];
			}
			return $data1;
		}else{
			return "";
		}
	}
		
	function Get_Block($module,$tabid,$crmid){
		global $generate;
		$con="";
		if($module=="Job"){
			$module="Call Detail";	
		}
		$sql="
		select 
		tabid
		from aicrm_tab
		where 1
		and tablabel='".$module."'
		";
		//echo $sql;
		$data_tab = $generate->process($sql,"all");
		$tabid=$data_tab[0]['tabid'];
		if($module=="Calendar"){
			$tabid=16;
		}
		if($module=="Calendar"){
			$sql="
			select 
			blockid,blocklabel,sequence
			from aicrm_blocks
			where 1
			and tabid='".$tabid."'
			and blockid in (39,86)
			order by sequence
			";
			$data_block = $generate->process($sql,"all");
			$a=0;
			$data_form1=array();
			for($i=0;$i<count($data_block);$i++){
				$data_fs=array();
				$data_f=array();
				if($data_block[$i]['blockid']=="39"){
					$sql="
					SELECT
					columnname, tablename,fieldlabel,typeofdata,uitype
					FROM aicrm_field
					WHERE 1
					and block=41
					and columnname<>'smownerid'
					and columnname in(
					'subject','date_start','time_start','due_date','time_end','crmid','eventstatus','sendnotification',
					'activitytype','location','visibility','description','priority'
					)
					ORDER BY block,sequence,quickcreatesequence
					";
				}else{
					$sql="
					SELECT
					columnname, tablename,fieldlabel,typeofdata,uitype
					FROM aicrm_field
					WHERE 1
					and block='".$data_block[$i]['blockid']."'
					and columnname<>'smownerid'
					ORDER BY block,sequence,quickcreatesequence
					";
				}
				$data_field = $generate->process($sql,"all");	
				if(count($data_field)>0){
					$data_block1=Get_field_Edit($module,$data_field,$crmid,$data_block[$i]['blocklabel']);
					$data_form1[]=$data_block1;
				}
			}				
		}else{
			$sql="
			select 
			blockid,blocklabel,sequence
			from aicrm_blocks
			where 1
			and tabid='".$tabid."'
			".$con."
			order by sequence
			";
			
					
			$data_block = $generate->process($sql,"all");
			$a=0;
			$data_form1=array();
			for($i=0;$i<count($data_block);$i++){
				$data_fs=array();
				$data_f=array();
				$sql="
				SELECT
				columnname, tablename,fieldlabel,typeofdata,uitype
				FROM aicrm_field
				WHERE quickcreate
				IN ( 0, 2 )
				AND tabid ='".$tabid."'
				AND aicrm_field.presence
				IN ( 0, 2 )
				AND displaytype !=2
				and block='".$data_block[$i]['blockid']."'
				and columnname<>'smownerid'
				ORDER BY block,sequence,quickcreatesequence
				";
				$data_field = $generate->process($sql,"all");	
				if(count($data_field)>0){
					$data_block1=Get_field_Edit($module,$data_field,$crmid,$data_block[$i]['blocklabel']);
					$data_form1[]=$data_block1;
				}
			}			
		}
		return $data_form1;
	}
	function Get_Block_Detail($module,$tabid,$crmid){
		global $generate;
		//echo $module;
		//$Module="";
		//$Tabid=38;
		$con="";
		//echo $crmid;
		if($module=="Job"){
			$module="Call Detail";	
		}
		$sql="
		select 
		tabid
		from aicrm_tab
		where 1
		and tablabel='".$module."'
		";
		if($module=="Products"){
			//echo $sql;
		}
		//echo $sql;exit;
		$data_tab = $generate->process($sql,"all");
		$tabid=$data_tab[0]['tabid'];
		if($module=="Calendar"){
			$tabid=16;
		}
		if($module=="Calendar"){
			$sql="
			select 
			blockid,blocklabel,sequence
			from aicrm_blocks
			where 1
			and tabid='".$tabid."'
			and blockid in (39,86)
			order by sequence
			";
			//echo $sql;
			$data_block = $generate->process($sql,"all");
	
			$a=0;
			$data_form1=array();
			for($i=0;$i<count($data_block);$i++){
				$data_fs=array();
				$data_f=array();
				if($data_block[$i]['blockid']=="39"){
					$sql="
					SELECT
					columnname, tablename,fieldlabel,typeofdata,uitype
					FROM aicrm_field
					WHERE 1
					and block=41
					and columnname<>'smownerid'
					and columnname in(
					'subject','date_start','time_start','due_date','time_end','crmid','eventstatus','sendnotification',
					'activitytype','location','visibility','description','priority'
					)
					ORDER BY block,sequence,quickcreatesequence
					";
				}else{
					$sql="
					SELECT
					columnname, tablename,fieldlabel,typeofdata,uitype
					FROM aicrm_field
					WHERE 1
					and block='".$data_block[$i]['blockid']."'
					and columnname<>'smownerid'
					ORDER BY block,sequence,quickcreatesequence
					";
				}
				
				//echo $sql."<br><br>";exit;
				$data_field = $generate->process($sql,"all");	
				//print_r($data_field);
				if(count($data_field)>0){
					$data_block1=Get_field($module,$data_field,$crmid,$data_block[$i]['blocklabel']);
					$data_form1[]=$data_block1;
				}
			}				
		}else{
			//echo $tabid;exit;
			$sql="
			select 
			blockid,blocklabel,sequence
			from aicrm_blocks
			where 1
			and tabid='".$tabid."'
			".$con."
			order by sequence
			";
			if($module=="Products"){
				//echo $sql;
			}
			$data_block = $generate->process($sql,"all");
	
			$a=0;
			$data_form1=array();
			for($i=0;$i<count($data_block);$i++){
				$data_fs=array();
				$data_f=array();
				$sql="
				SELECT
				columnname, tablename,fieldlabel,typeofdata,uitype
				FROM aicrm_field
				WHERE quickcreate
				IN ( 0, 2 )
				AND tabid ='".$tabid."'
				AND aicrm_field.presence
				IN ( 0, 2 )
				AND displaytype !=2
				and block='".$data_block[$i]['blockid']."'
				and columnname<>'smownerid'
				ORDER BY block,sequence,quickcreatesequence
				";
				if($module=="Products"){
					//echo $sql;
				}
				$data_field = $generate->process($sql,"all");	
				if(count($data_field)>0){
					$data_block1=Get_field($module,$data_field,$crmid,$data_block[$i]['blocklabel']);
					//print_r($data_block1);exit;
					$data_form1[]=$data_block1;
				}
			}	
		}
		if($module=="Accounts"){
			$data_block1=array(
				'header_name'=>"Contacts Information",
				'is_contact'=>"true",
				'form'=>Get_Contact($crmid)
			);
			$data_form1[]=$data_block1;
		}
		return $data_form1;
	}	
	//ในการดึงข้อมูลในหน้า get main และ get all
	function Get_field($module,$data_field,$crmid,$blocklabel){
		global $generate;
		//print_r($data_field);
		for($j=0;$j<count($data_field);$j++){
			//$tabid=$data_field[$j]['tabid'];
			$columnname=$data_field[$j]['columnname'];
			$fieldlabel=$data_field[$j]['fieldlabel'];
			if($fieldlabel=="Visibility"){
				$fieldlabel="Mark Public";
			}
			$tablename=$data_field[$j]['tablename'];
			//$fieldid=$data_field[$j]['fieldid'];
			$uitype=$data_field[$j]['uitype'];
			$typeofdata=$data_field[$j]['typeofdata'];
			$type_of_data  = explode('~',$typeofdata);
			$type=$type_of_data[0];
			$typeofdata=$type_of_data[1];
			$lenght_number=$type_of_data[2];
			$lenght=$type_of_data[3];
			//echo "555";exit;
			//echo $fieldlabel." ".$uitype." ".$data_field[$j]['typeofdata']." ".$type." ".$typeofdata." ".$lenght_number."<br>";
			if($uitype=="15"){
				$type_1="select";
				//$is_array="true";
				if($data_field[$j]['fieldlabel']=="Priority"){
					$data_pick=Get_PicklistValue("taskpriority");
				}else{
					$data_pick=Get_PicklistValue($columnname);
				}
			}else if($uitype=="73" ||$uitype=="66"){
				$type_1="select_account";
				//$is_array="true";
				$data_pick="";
			}else if($uitype=="906"){
				$type_1="select_case";
				//$is_array="true";
				$data_pick="";
			}else if($uitype=="59"){
				$type_1="select_product";
				//$is_array="true";
				$data_pick="";
			}else if($uitype=="33"){
				$type_1="multiple";
				//$is_array="true";
				$data_pick=Get_PicklistValue($columnname);
			}else if($uitype=="56"){
				$type_1="checkbox";
				//$is_array="false";
				$data_pick="";
			}else if($uitype=="19" || $uitype=="21"){
				$type_1="textarea";
				//$is_array="false";
				$data_pick="";
			}else if($columnname=="date_start" || $columnname=="due_date"){
				$type_1="date";
				//$is_array="false";
				$data_pick="";
			}else if($columnname=="time_start" || $columnname=="time_end" || $columnname=="cf_1355"
			|| $columnname=="cf_1213"|| $columnname=="cf_1316" || $columnname=="cf_1464" || $columnname=="cf_1215"
			){
				$type_1="time";
				//$is_array="false";
				$data_pick="";
			}else if($columnname=="cf_1246" || $columnname=="cf_1247" || $columnname=="cf_1248" || $columnname=="cf_1249" 
			|| $columnname=="cf_1425" || $columnname=="cf_1427" || $columnname=="cf_1432" || $columnname=="cf_1430"
			|| $columnname=="cf_1488" || $columnname=="cf_1490" || $columnname=="cf_1492" || $columnname=="cf_1494"
			){
				$type_1="datetime";
				//$is_array="false";
				$data_pick="";
			}else if($columnname=="visibility"){
				$type_1="checkbox";
				//$is_array="false";
				$data_pick="";
			}else{
				if(($type=="V" || $type=="E") && $lenght==""){
					$type_1="varchar(100)";
					//$is_array="false";
				}else if (($type=="N" || $type=="O")){
					$type_1="varchar(100)";
					//$is_array="false";
				}else if (($type=="V" || $type=="E") && $lenght!=""){
					$type_1="varchar(250)";
					//$is_array="false";
				}else if ($type=="D"){
					$type_1="date";
					//$is_array="false";
				}
				$data_pick="";					
			}
			if($columnname=="cf_1315" || $columnname=="cf_1218" || $columnname=="cf_1214" || $columnname=="cf_1222"){
				$type_1="varchar(100)";
			}
			//echo $type_1."<br>";
			if($type_1=="select_account"){
				$value = Get_Account_Detail(Get_Value($module,$columnname,$tablename,$crmid));
			}else if($type_1=="select_product"){
				$value = Get_Product_Detail(Get_Value($module,$columnname,$tablename,$crmid));
			}else if($type_1=="select_case"){
				$value = Get_Case_Detail("HelpDesk",Get_Value($module,$columnname,$tablename,$crmid));
			}else  if($columnname=="visibility"){
				$value = Get_Value($module,$columnname,$tablename,$crmid);
				if($value=="Private"){
					$value ="0";
				}else{
					$value ="1";
				}
			}else{
				$value = Get_Value($module,$columnname,$tablename,$crmid);
			}
			if($type_1=="time"){
			 	$value=str_replace(".",":",$value);
			}
			if($type_1=="select"){
				//$value =iconv("tis-620","utf-8",$value);
			}
			//$value = Get_Value($module,$columnname,$tablename,$crmid);
			$count_arr=explode(" |##| ",$value);
			//print_r($value);
			if(count($count_arr)>1){
				$is_array="true";
				$value =$count_arr;
			}elseif($type_1=="select_account"){
				$chk=Get_Account_Detail(Get_Value($module,$columnname,$tablename,$crmid));
				//echo $dd['accountid'];
				if($chk['accountid']!=""){
					$is_array="true";
				}else{
					$is_array="false";
				}
			}elseif($type_1=="select_case"){
				$chk=Get_Case_Detail("HelpDesk",Get_Value($module,$columnname,$tablename,$crmid));
			}
			//echo "555";
			if ($typeofdata == 'M' || $columnname=="cf_1262" ||  $columnname=="cf_1355"){
				$check_value="yes";
				$error_message=$fieldlabel." cannot be empty";
			}else{
				$check_value="no";
				$error_message="";
			}
			if(($columnname=="cf_1228" || $columnname=="cf_1229" || $columnname=="cf_1397" || $columnname=="cf_1144") and $value!=""){
				$is_phone="true";
				$value=str_replace("-","",$value);
				$str_phone=split("##",$value);
				if(count($str_phone)>0){
					$phone1=$str_phone[0];
					if($str_phone[1]==""){ 
						$phone2="";
					}else{
						$phone2=$str_phone[1];
					}
					$data_phone[0]['phone_main']=$phone1;
					$data_phone[0]['phone_to']=$phone2;
				}else{
					$data_phone[0]['phone_main']=$phone_main;
					$data_phone[0]['phone_to']="";
				}
				$value=$data_phone[0];
			}else if(($columnname=="cf_1228" || $columnname=="cf_1229" || $columnname=="cf_1397" || $columnname=="cf_1144")){
				$is_phone="true";
				$data_phone[0]['phone_main']="";
				$data_phone[0]['phone_to']="";
				$value=$data_phone[0];
			}else{
				$is_phone="false";
			}
			if($columnname=="crmid" || $columnname=="accountid"){
				$is_account="true";
			}else if($columnname=="crmid" || $columnname=="parent_id"){
				$is_account="false";
			}else{
				$is_account="false";
			}
			
			if($columnname=="cf_1401" || $columnname=="cf_1402" || $columnname=="cf_1403" || $columnname=="cf_1404" 
			|| $columnname=="cf_1426" || $columnname=="cf_1428" || $columnname=="cf_1429" || $columnname=="cf_1431"
			|| $columnname=="cf_1489" || $columnname=="cf_1491" || $columnname=="cf_1493" || $columnname=="cf_1495"
			){
				$is_checkin="true";
			}else{
				$is_checkin="false";
			}
			if($value!=""){
			}else{
				$value="";
			}
			if($type_1=="select_account"){
				if($value['accountid']!=""){
					$accountid=$value['accountid'];
					$account_name=$value['accountname'];
					$phone=$value['phone'];
					if(count($phone)>0){
						$is_phone="true";
					}else{
						$is_phone="false";	
					}
					$form_detail=$value['form_detail'];
				}else{
					$accountid="";
					$account_name="";
					$phone="";
					$is_phone="false";	
					$form_detail="";
				}
				$data_f=array(
					//'field_order' => $j,
					'columnname' => $columnname,
					'tablename' => $tablename,
					'fieldlabel' => $fieldlabel,
					'type' => $type_1,
					//'check_value' => $check_value,
					//'error_message'=>$error_message,
					//'value_default' => $data_pick,
					'value' => $account_name,
					'accountid' => $accountid,
					'phone' => $phone,
					'form_detail' => $form_detail,
					//'is_array' => $is_array,
					'is_array' => "false",
					'is_phone' => $is_phone,
					'is_account' => $is_account,
					'is_product' => "false",
					'is_checkin' => $is_checkin
				);					
			}
			else if($type_1=="select_case"){
				if($value['ticketid']!=""){
					$ticketid=$value['ticketid'];
					$ticket_no=$value['ticket_no'];
					$form_detail=$value['form_detail'];
					$is_phone="false";	
				}else{
					$ticketid="";
					$ticket_no="";
					$is_phone="false";	
					$form_detail="";
				}
				$data_f=array(
					//'field_order' => $j,
					'columnname' => $columnname,
					'tablename' => $tablename,
					'fieldlabel' => $fieldlabel,
					'type' => $type_1,
					//'check_value' => $check_value,
					//'error_message'=>$error_message,
					//'value_default' => $data_pick,
					'value' => $ticket_no,
					'ticketid' => $ticketid,
					'form_detail' => $form_detail,
					//'is_array' => $is_array,
					'is_array' => "false",
					'is_phone' => $is_phone,
					'is_account' => $is_account,
					'is_product' => "false",
					'is_checkin' => $is_checkin
				);					
			}
			else if($type_1=="select_product"){
				if($value['productid']!=""){
					$is_array="true";
					$is_array="false";
					$productid=$value['productid'];
					$product_no=$value['product_no'];
					$productname=$value['productname'];
					$model=$value['model'];
					$pro_cat=$value['pro_cat'];
					$pro_subcat=$value['pro_subcat'];
					$unit_price=$value['unit_price'];
					$is_phone="false";	
					
					$data_f1=array(
					  'columnname' => "product_no",
					  'fieldlabel' => "Product No",
					  'type' => "varchar(100)",
					  'value' => $product_no,
					  'is_array' => "false",
					  'is_phone' => "false",
					  'is_account' => "false",
					  'is_product' => "false",
					  'is_checkin'=>"false"
					);
					$data_f2=array(
					  'columnname' => "productname",
					  'fieldlabel' => "Product Name",
					  'type' => "varchar(250)",
					  'value' => $productname,
					  'is_array' => "false",
					  'is_phone' => "false",
					  'is_account' => "false",
					  'is_product' => "false",
					  'is_checkin'=>"false"
					);
					$data_f3=array(
					 'columnname' => "cf_1148",
					  'fieldlabel' => "Product Model",
					  'type' => "varchar(50)",
					  'value' => $model,
					  'is_array' => "false",
					  'is_phone' => "false",
					  'is_account' => "false",
					  'is_product' => "false",
					  'is_checkin'=>"false"
					);
					$data_f4=array(
					 'columnname' => "productcategory",
					  'fieldlabel' => "Product Category",
					  'type' => "varchar(200)",
					  'value' => $pro_cat,
					  'is_array' => "false",
					  'is_phone' => "false",
					  'is_account' => "false",
					  'is_product' => "false",
					  'is_checkin'=>"false"
					);
					$data_f5=array(
					 'columnname' => "cf_1148",
					  'fieldlabel' => "Product Sub-Category",
					  'type' => "varchar(255)",
					  'value' => $pro_subcat,
					  'is_array' => "false",
					  'is_phone' => "false",
					  'is_account' => "false",
					  'is_product' => "false",
					  'is_checkin'=>"false"
					);
					$data_f6=array(
					 'columnname' => "unit_price",
					  'fieldlabel' => "Unit Price",
					  'type' => "varchar(100)",
					  'value' => $unit_price,
					  'is_array' => "false",
					  'is_phone' => "false",
					  'is_account' => "false",
					  'is_product' => "false",
					  'is_checkin'=>"false"
					);
					$data_fs1[]=$data_f1;
					$data_fs1[]=$data_f2;
					//$data_fs[]=$data_f3;
					$data_fs1[]=$data_f4;
					$data_fs1[]=$data_f5;
					$data_fs1[]=$data_f6;
					$data_block11[]=array(
						//'block_order'=>$i,
						'header_name'=>"Product Information",
						'is_contact'=>"false",
						'form'=>$data_fs1
					);
					$form_detail=$data_block11;
				}
				else{
					$is_array="false";
					$productid="";
					$product_no="";
					$productname="";
					$model="";
					$pro_cat="";
					$unit_price="";
					$is_phone="false";	
					$form_detail="";
				}
				$data_f=array(
					//'field_order' => $j,
					'columnname' => $columnname,
					'tablename' => $tablename,
					'fieldlabel' => $fieldlabel,
					'type' => $type_1,
					//'check_value' => $check_value,
					//'error_message'=>$error_message,
					//'value_default' => $data_pick,
					'value' => $productname,
					'productid' => $productid,
					//'unit_price' => $unit_price,
					'form_detail' => $form_detail,
					'is_array' => $is_array,
					'is_phone' => $is_phone,
					'is_account' => $is_account,
					'is_product' => "true",
					'is_checkin' => $is_checkin
				);					
			}
			
			else{
				$data_f=array(
					//'field_order' => $j,
					'columnname' => $columnname,
					'tablename' => $tablename,
					'fieldlabel' => $fieldlabel,
					'type' => $type_1,
					//'check_value' => $check_value,
					//'error_message'=>$error_message,
					//'value_default' => $data_pick,
					'value' => $value,
					'is_array' => $is_array,
					'is_phone' => $is_phone,
					'is_account' => $is_account,
					'is_product' => "false",
					'is_checkin' => $is_checkin
				);				
			}
			$data_fs[]=$data_f;
		}		
		//echo $is_account;exit;
		//Get Block Name=========================================
		$block_name=get_label_name($blocklabel);
		$data_block1=array(
			//'block_order'=>$i,
			'header_name'=>$block_name,
			'is_contact'=>"false",
			'form'=>$data_fs
		);
		return $data_block1;
	}
	//ในการดึงข้อมูลในหน้า edit
	function Get_field_Edit($module,$data_field,$crmid,$blocklabel){
		global $generate;
		for($j=0;$j<count($data_field);$j++){
			$tabid=$data_field[$j]['tabid'];
			$columnname=$data_field[$j]['columnname'];
			$fieldlabel=$data_field[$j]['fieldlabel'];
			if($fieldlabel=="Visibility"){
				$fieldlabel="Mark Public";
			}
			$tablename=$data_field[$j]['tablename'];
			$fieldid=$data_field[$j]['fieldid'];
			$uitype=$data_field[$j]['uitype'];
			$typeofdata=$data_field[$j]['typeofdata'];
			$type_of_data  = explode('~',$typeofdata);
			$type=$type_of_data[0];
			$typeofdata=$type_of_data[1];
			$lenght=$type_of_data[3];
			//echo "555";exit;
			
			if($uitype=="15"){
				$type_1="select";
				$is_array="true";
				if($data_field[$j]['fieldlabel']=="Priority"){
					$data_pick=Get_PicklistValue("taskpriority");
				}else{
					$data_pick=Get_PicklistValue($columnname);
				}
			}else if($uitype=="73" || $uitype=="66"){
				$type_1="select_account";
				//$is_array="true";
				$data_pick="";
			}else if($uitype=="906"){
				$type_1="select_case";
				//$is_array="true";
				$data_pick="";
			}else if($uitype=="59"){
				$type_1="select_product";
				//$is_array="true";
				$data_pick="";
			}else if($uitype=="33"){
				$type_1="multiple";
				//$is_array="true";
				$data_pick=Get_PicklistValue($columnname);
			}else if($uitype=="56"){
				$type_1="checkbox";
				//$is_array="false";
				$data_pick="";
			}else if($uitype=="19" || $uitype=="21"){
				$type_1="textarea";
				//$is_array="false";
				$data_pick="";
			}else if($columnname=="date_start" || $columnname=="due_date"){
				$type_1="date";
				//$is_array="false";
				$data_pick="";
			}else if($columnname=="time_start" || $columnname=="time_end" || $columnname=="cf_1355"
			|| $columnname=="cf_1213"|| $columnname=="cf_1316"|| $columnname=="cf_1464"|| $columnname=="cf_1215"
			){
				$type_1="time";
				//$is_array="false";
				$data_pick="";
			}else if($columnname=="cf_1246" || $columnname=="cf_1247" || $columnname=="cf_1248" || $columnname=="cf_1249" 
			|| $columnname=="cf_1425" || $columnname=="cf_1427" || $columnname=="cf_1432" || $columnname=="cf_1430"
			|| $columnname=="cf_1488" || $columnname=="cf_1490" || $columnname=="cf_1492" || $columnname=="cf_1494"
			){
				$type_1="datetime";
				//$is_array="false";
				$data_pick="";
			}else if($columnname=="visibility"){
				$type_1="checkbox";
				//$is_array="false";
				$data_pick="";
			}else{
				if(($type=="V" || $type=="E") && $lenght==""){
					$type_1="varchar(100)";
					//$is_array="false";
				}else if (($type=="V" || $type=="E") && $lenght!=""){
					$type_1="varchar(250)";
					//$is_array="false";
				}else if ($type=="D"){
					$type_1="date";
					//$is_array="false";
				}		
				$data_pick="";					
			}
			if($columnname=="cf_1315" || $columnname=="cf_1218" || $columnname=="cf_1214" || $columnname=="cf_1222"){
				$type_1="varchar(100)";
			}
			if($type_1=="select_account"){
				//$value = Get_Account_Detail(Get_Value($module,$columnname,$tablename,$crmid));
			}else if($type_1=="select_product"){
				//$value = Get_Product_Detail(Get_Value($module,$columnname,$tablename,$crmid));
			}else if($type_1=="select_case"){
				//$value = Get_Case_Detail("HelpDesk",Get_Value($module,$columnname,$tablename,$crmid));
			}else{
				//$value = Get_Value($module,$columnname,$tablename,$crmid);
			}
			if($type_1=="time"){
			 	$value=str_replace(".",":",$value);
			}
			//$value = Get_Value($module,$columnname,$tablename,$crmid);
			$count_arr=explode(" |##| ",$value);
			//print_r($value);
			if(count($count_arr)>1){
				$is_array="true";
				$value =$count_arr;
			}elseif($type_1=="select_account"){
				$chk=Get_Account_Detail(Get_Value($module,$columnname,$tablename,$crmid));
				//echo $dd['accountid'];
				if($chk['accountid']!=""){
					$is_array="true";
				}else{
					$is_array="false";
				}
			}elseif($type_1=="select_case"){
				$chk=Get_Case_Detail("HelpDesk",Get_Value($module,$columnname,$tablename,$crmid));
				//echo $dd['accountid'];
				if($chk['ticketid']!=""){
					$is_array="true";
				}else{
					$is_array="false";
				}
			}
			
			if ($typeofdata == 'M' || $columnname=="cf_1262" || $columnname=="cf_1355"){
				$check_value="yes";
				$error_message=$fieldlabel." cannot be empty";
			}else{
				$check_value="no";
				$error_message="";
			}
			if(($columnname=="cf_1228" || $columnname=="cf_1229" || $columnname=="cf_1397" || $columnname=="cf_1144") and $value!=""){
				$is_phone="true";
				$value=str_replace("-","",$value);
				$str_phone=split("##",$value);
				if(count($str_phone)>0){
					$phone1=$str_phone[0];
					if($str_phone[1]==""){ 
						$phone2="";
					}else{
						$phone2=$str_phone[1];
					}
					$data_phone[0]['phone_main']=$phone1;
					$data_phone[0]['phone_to']=$phone2;
				}else{
					$data_phone[0]['phone_main']=$phone_main;
					$data_phone[0]['phone_to']="";
				}
				$value=$data_phone[0];
			}else if(($columnname=="cf_1228" || $columnname=="cf_1229" || $columnname=="cf_1397" || $columnname=="cf_1144")){
				$is_phone="true";
				$data_phone[0]['phone_main']="";
				$data_phone[0]['phone_to']="";
				$value=$data_phone[0];
			}else{
				$is_phone="false";
			}
			if($columnname=="crmid" || $columnname=="accountid"){
				$is_account="true";
			}else if($columnname=="crmid" || $columnname=="parent_id"){
				$is_account="true";
			}else{
				$is_account="false";
			}
			
			if($columnname=="product_id"){
				$is_product="true";
			}else{
				$is_product="false";
			}
			
			if($columnname=="cf_1401" || $columnname=="cf_1402" || $columnname=="cf_1403" || $columnname=="cf_1404" 
			|| $columnname=="cf_1426" || $columnname=="cf_1428" || $columnname=="cf_1429" || $columnname=="cf_1431"
			|| $columnname=="cf_1489" || $columnname=="cf_1491" || $columnname=="cf_1493" || $columnname=="cf_1495"
			){
				$is_checkin="true";
			}else{
				$is_checkin="false";
			}
			
			$data_f=array(
				//'field_order' => $j,
				'columnname' => $columnname,
				'tablename' => $tablename,
				'fieldlabel' => $fieldlabel,
				'type' => $type_1,
				'check_value' => $check_value,
				'error_message'=>$error_message,
				'value_default' => $data_pick,
				//'value' => $value,
				'is_array' => $is_array,
				'is_phone' => $is_phone,
				'is_account' => $is_account,
				'is_product' => $is_product,
				'is_checkin'=>$is_checkin
			);
			$data_fs[]=$data_f;
		}		
		//Get Block Name=========================================
		$block_name=get_label_name($blocklabel);
		$data_block1=array(
			//'block_order'=>$i,
			'header_name'=>$block_name,
			'form'=>$data_fs
		);
		return $data_block1;
	}

	
	function autocd_job($table,$colunm,$condition,$prefix,$length,$field_pk){
		global $generate;
		//$cd = date("ymd");
		$condition1 = "a.".$colunm ." like '". $prefix.$cd."%'";	
		$sql = " select if(isnull(max(a.$colunm)),'',SUBSTRING(max(a.$colunm),-".$length.",".$length.")) cd  from $table a left join aicrm_crmentity c  on a.$field_pk  = c.crmid where $condition1 $conditon and c.deleted <> 1 ";
		//echo $sql;
		$res=$generate->process($sql,"all");	
		$data = $res[0]['cd'];
		//echo $data;exit;
		
		if($data>0){
			if($data<>''){
				$data = $data+1;
			}else{
				$data = 1;
			}
		}else{
			$data = 1;
		}
		$num = str_pad($data,$length, "0", STR_PAD_LEFT);
		return $prefix.date('Y')."-".date('m').$num;
	}
	function get_running(){
		global $generate;
	}
	function get_edit($json_id,$user_id,$module,$method,$crmid){
		return Get_Block_Detail($module,'',$crmid);
	}
	function get_module($json_id,$user_id,$module,$method,$crmid){
		global $generate;
		
		if($module=="Job"){
			$module="Call Detail";	
		}
		
		if($module!=""){
			//method get_All=====================================================================================================
			if($method=="get_all"){
				$sql=getListQuery($module,$user_id);
				$data_all = $generate->process($sql,"all");
					
	
				
				$data_v=array();
				$data_v1=array();
				$data_v2=array();
				if(count($data_all)>0){
					for($i=0;$i<count($data_all);$i++){
						$data_field=array_keys($data_all[$i]);
						$data_value=array_values($data_all[$i]);
						//return $data_field;
						//exit;
						$color="#9999FF";
						//$data_v2['item_order']=$i;
						for($j=0;$j<count($data_field);$j++){
							$data_v2[Get_Field_Name($data_field[$j])]=$data_value[$j];
							//$data_v2[$data_field[$j]]=$data_value[$j];
							//echo $data_field[$j]."==>". $data_value[$j]."<br>";
							if($module=="Call Detail"){
								if($data_field[$j]=="cf_1262" and $data_value[$j]=="Planning"){
									$color="#FF0000";
								}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="In Progress"){
									$color="#0099FF";
								}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Wait For Response"){
									$color="#FF9900";
								}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Closed"){
									$color="#00FF00";
								}
							}else if($module=="Calendar"){
								if($data_field[$j]=="event_type" and $data_value[$j]=="Call"){
									$color="#FF0000";
								}else if($data_field[$j]=="event_type" and $data_value[$j]=="Meeting"){
									$color="#0099FF";
								}else if($data_field[$j]=="event_type" and $data_value[$j]=="Present"){
									$color="#FF9900";
								}else if($data_field[$j]=="event_type" and $data_value[$j]=="Visit"){
									$color="#00FF00";
								}
							}
							$data_v2['status_color']=$color;	
							if($data_field[$j]=="crmid"){
								$id=$data_value[$j];
							}	
							/*$data_v=array(
								 'FieldName'=>$data_field[$j],
								 'FieldLabel'=>Get_Field_Name($data_field[$j]),
								 'value'=>$data_value[$j]
							);
							$data_v2[]=$data_v;*/								
						}
						$data_v2['form_detail']=get_edit($json_id,$user_id,$module,"get_edit",$id);
						//$data_v2['form_detail']='';
						$data_v1[]=$data_v2;
						$color="#9999FF";
					}
					//print_r($data_v1);
					//exit;
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>$data_v1
					);
					//print_r($data);
					//exit;
				}else{
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' =>$json_id, //String/Number
						'result' =>array(
							'error_message' => 'Data Not found.'
						)
					);
				}
			}
			//method get_main=====================================================================================================
			elseif($method=="get_main"){
				$sql=getListQuery($module,$user_id,5);
				//$sql.=" and date_start='".date('Y-m-d')."'";
				//echo $sql;exit;
				//$sql.=" order by aicrm_servicerequests.servicerequestid desc";
			
				$data_all = $generate->process($sql,"all");
				
				/*$FileName = "sql.txt";
				$FileHandle = fopen($FileName, 'a+') or die("can't open file");
				fwrite($FileHandle,$module."=>".$method."<br>".json_encode($data_all)."\r\n");
				fclose($FileHandle);
				chmod($FileName , 0777);*/

				
				$data_v=array();
				$data_v1=array();
				$data_v2=array();
				if(count($data_all)>0){
					for($i=0;$i<count($data_all);$i++){
						$data_field=array_keys($data_all[$i]);
						$data_value=array_values($data_all[$i]);
						//print_r($data_field);
						//exit;
						$color="#9999FF";
						//$data_v2['item_order']=$i;
						for($j=0;$j<count($data_field);$j++){
							if($data_value[$j]!=""){
								$data_v2[Get_Field_Name($data_field[$j])]=$data_value[$j];
							}else{
								$data_v2[Get_Field_Name($data_field[$j])]="";
							}
							
							//$data_v2[$data_field[$j]]=$data_value[$j];
							//echo $data_field[$j]."==>". $data_value[$j]."<br>";
							if($module=="Call Detail"){
								if($data_field[$j]=="cf_1262" and ($data_value[$j]=="Incomplete Customer" || $data_value[$j]=="Incomplete Part" || $data_value[$j]=="Incomplete Service" || $data_value[$j]=="Incomplete Other")){
									$color="#FF0000";
								}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="In Progress"){
									$color="#0099FF";
								}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Assistant"){
									$color="#FF9900";
								}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Complete"){
									$color="#00FF00";
								}
							}else if($module=="Calendar"){
								if($data_field[$j]=="event_type" and $data_value[$j]=="Call"){
									$color="#FF0000";
								}else if($data_field[$j]=="event_type" and $data_value[$j]=="Meeting"){
									$color="#0099FF";
								}else if($data_field[$j]=="event_type" and $data_value[$j]=="Present"){
									$color="#FF9900";
								}else if($data_field[$j]=="event_type" and $data_value[$j]=="Visit"){
									$color="#00FF00";
								}
							}
							//$data_v2['cf_1591'] = $data_field[$j]
							$data_v2['status_color']=$color;		
							if($data_field[$j]=="crmid"){
								$id=$data_value[$j];
							}			
							/*$data_v=array(
								 'FieldName'=>$data_field[$j],
								 'FieldLabel'=>Get_Field_Name($data_field[$j]),
								 'value'=>$data_value[$j]
							);
							$data_v2[]=$data_v;*/								
						}
						$data_v2['form_detail']=get_edit($json_id,$user_id,$module,"get_edit",$id);	
						$data_v1[]=$data_v2;
						//$color="#9999FF";
					}
					
					//print_r($data_v1);
					//exit;
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>$data_v1
					);
					//print_r($data);
					//exit;
				}else{
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' =>$json_id, //String/Number
						'result' =>array(
							'error_message' => 'Data Not found.'
						)
					);
				}
			}
			//method get_Edit=====================================================================================================
			else if($method=="get_edit"){

				$data_form=Get_Block($module,'',$crmid);
				
				//exit;
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' => $json_id, //String/Number
					'result' =>$data_form
				);
			}
			//method get_delete=====================================================================================================
			else if($method=="get_delete"){
				if($crmid!=""){
					//$crmid= $data[$i]['crmid'];
					//$date_time= $data[$i]['date_time'];
					//$userid= $data[$i]['user_id'];
					//echo $data[$i]['crmid']."<br>";	
					$sql="update aicrm_crmentity set deleted=1,modifiedby='".$user_id."' 
					,modifiedtime='".date('Y-m-d H:i:s')."'
					where crmid='".$crmid."' ";
					//echo $sql;exit;
					if($generate->query($sql)){
						$msg="Update Complete";
					}else{
						$msg="Cannot Update";
					}
				}	else{
					
				}
			}
			//method get_important=====================================================================================================
			else if($method=="get_important"){
				$sql=getListQuery1($module,$user_id);
				$data_all = $generate->process($sql,"all");
				//echo count($data_all)."<br>";
				$data_job=array();
				if(count($data_all)>0){
					$count=0;
					for($i=0;$i<count($data_all);$i++){
						$lastviewed = $data_all[$i]['viewedtime'];
						$modifiedon = $data_all[$i]['modifiedtime'];
						$smownerid   = $data_all[$i]['smownerid'];
						$smcreatorid = $data_all[$i]['smcreatorid'];
						$modifiedby = $data_all[$i]['modifiedby'];
			
						//echo $lastviewed;exit;
						if($modifiedby == '0' && ($smownerid == $smcreatorid)) {
							/** When module record is created **/
							//$count=$count+1;
							//echo $data_all[$i]['servicerequest_no']."<br>";
						} else if($smownerid == $modifiedby) {
							/** Owner and Modifier as same. **/
							//$count=$count+1;
							//echo $data_all[$i]['servicerequest_no']."<br>";
						} else if($lastviewed && $modifiedon) {
							/** Lastviewed and Modified time is available. */
							//echo __timediff($modifiedon, $lastviewed)."<br>";
							if(__timediff($modifiedon, $lastviewed) > 0){
								//$count=$count+1;	
								//echo $data_all[$i]['servicerequest_no']."<br>";
							}
						} else{
							$count=$count+1;	
							$data_job[]=$data_all[$i]['crmid'];
						}
					}
					//$count=count($data_all)-$count;
					//echo $count;exit;
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>$data_job
					);
					//echo $count;exit;
				}else{
					if($module=="Calendar"){
						$data = array(
							'jsonrpc' => '2.0', //version
							'id' =>5, //String/Number
							'result' =>array(
								'error_message' => 'Data Not found.'
							)
						);
					}else{
						$data = array(
							'jsonrpc' => '2.0', //version
							'id' =>$json_id, //String/Number
							'result' =>array(
								'error_message' => 'Data Not found.'
							)
						);
					}
				}
			}
			//method not found=====================================================================================================
			else{
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' =>$json_id, //String/Number
					'error' =>array(
						'code'=>'-32601',//error code
						'message'=>'Method not found'//error message
					)
				);
			}
		//Module not found=====================================================================================================
		}else{
			$data = array(
				'jsonrpc' => '2.0', //version
				'id' =>$json_id, //String/Number
				'error' =>array(
					'code'=>'-32600',//error code
					'message'=>'Invalid Request'//error message
				)
			);
		}
		/*$FileName = "sql.txt";
					$FileHandle = fopen($FileName, 'a+') or die("can't open file");
					fwrite($FileHandle,"return data"."\r\n");
					fclose($FileHandle);*/
		/*if($module=="Calendar"){
			$data = array(
				'jsonrpc' => '2.0', //version
				'id' =>$json_id, //String/Number
				'result' =>array(
					'error_message' => 'Data Not found.'
				)
			);
		}*/
				/*$FileName = "sql.txt";
				$FileHandle = fopen($FileName, 'a+') or die("can't open file");
				fwrite($FileHandle,$module."=>".$method."<br>".json_encode($data)."\r\n");
				fclose($FileHandle);
				chmod($FileName , 0777);*/
		return $data;
	}
	function getListQuery($module,$user_id='',$limit=''){
		if($module=="Job"){
			$module="Call Detail";	
		}
		$sql_from=Get_Query($module);
		if($module=="Job"){
			//Job
			$sql = "
			SELECT 
			distinct(aicrm_servicerequests.servicerequestid) as crmid,
			servicerequest_no 	,
		    servicerequest_name ,
			replace( cf_1262, '--None--', '' ) AS cf_1262,
			cf_1225,
			replace(cf_1355,'.',':') as cf_1355";
			$sql.=$sql_from;
			$sql.= "and cf_1192='External'
			and cf_1262 !='Closed'
			and cf_1262 !=''
			and aicrm_crmentity.smownerid='".$user_id."'
			";
			if($limit>0){
				$sql.=" order by aicrm_servicerequests.servicerequestid desc";
				$sql.=" limit $limit";
			}
		}else if($module=="Call Detail"){
			//Call Detail
			$sql = "
			select
			aicrm_jobdetails.jobdetailid as crmid
			,jobdetail_no as servicerequest_no
			,ticket_no as servicerequest_name
			,replace( cf_1224, '--None--', '' ) AS cf_1262
			,cf_1211 as cf_1225
			,cf_1591
			,replace(cf_1213,'.',':') as cf_1355";
			$sql.=$sql_from;
			$sql.= "/*and cf_1224 !='Complete'
			and cf_1224 !=''*/
			and aicrm_crmentity.smownerid='".$user_id."'
			and aicrm_troubletickets.ticket_no like 'SV%'
			";
			if($limit>0){
				$sql.=" order by aicrm_jobdetails.jobdetailid desc";
				$sql.=" limit $limit";
			}
			//echo $sql;
		}else if($module=="Calendar"){
			$sql = "
			select
			distinct(aicrm_activity.activityid) as crmid,
			activitytype as 'event_type',
			aicrm_activity.subject as 'event_name',
			aicrm_crmentity.description ,
			date_start,
			replace(time_start,'.',':') as time_start,
			due_date,
			replace(time_end,'.',':') as time_end";
			$sql.=$sql_from;
			$sql.= " and aicrm_crmentity.smownerid='".$user_id."'
			";
			if($limit>0){
				$sql.=" and (date_start='".date('Y-m-d')."' or date_start='".(date('Y')+543)."-".date('m-d')."') limit $limit";
			}
		}else if ($module=="Account"){
			$sql="
			select 
			aicrm_account.accountid,aicrm_account.account_no,aicrm_account.accountname,'' as cf_1144";
			$sql.=$sql_from;
			$sql.= "";
			//echo $sql;exit;
		}else if ($module=="Products"){
			$sql="
			select 
			aicrm_products.productid,
			aicrm_products.product_no,
			aicrm_products.productname,
			aicrm_productcf.cf_1148 as 'model',
			aicrm_products.productcategory as 'pro_cat',
			aicrm_productcf.cf_1147 as 'pro_subcat',
			format(aicrm_products.unit_price,2) as unit_price";
			$sql.=$sql_from;
			$sql.= "";
		}else if ($module=="HelpDesk"){
			$sql="
			select 
			aicrm_troubletickets.ticketid,
			aicrm_troubletickets.ticket_no
			,concat('SN',':',aicrm_models.model_name) as ticket_name
			";
			$sql.=$sql_from;
			$sql.= "";
		}
		//echo $sql;
		return $sql;
	}
	function getListQuery1($module,$user_id=''){//exit;
		$sql_from=Get_Query($module);
		if($module=="Job"){
			$sql = "
			SELECT 
			distinct(aicrm_servicerequests.servicerequestid) as crmid,
			servicerequest_no 	,
		    servicerequest_name ,
			replace( cf_1262, '--None--', '' ) AS cf_1262,
			cf_1225,
			replace(cf_1355,'.',':') as cf_1355,
			viewedtime,modifiedtime,smownerid,smcreatorid,modifiedby";
			$sql.=$sql_from;
			$sql.= "and cf_1192='External'
			and cf_1262 !='Closed'
			and cf_1262 !=''
			and aicrm_crmentity.smownerid='".$user_id."'
			";
		}else if($module=="Call Detail"){
			//Call Detail
			$sql = "
			select
			aicrm_jobdetails.jobdetailid as crmid
			,jobdetail_no as servicerequest_no
			,ticket_no as servicerequest_name
			,replace( cf_1224, '--None--', '' ) AS cf_1262
			,cf_1211 as cf_1225
			,replace(cf_1213,'.',':') as cf_1355
			,viewedtime,modifiedtime,smownerid,smcreatorid,modifiedby";
			$sql.=$sql_from;
			$sql.= "/*and cf_1224 !='Complete'
			and cf_1224 !=''*/
			and aicrm_crmentity.smownerid='".$user_id."'
			and aicrm_troubletickets.ticket_no like 'SV%'
			";
		
		}else if($module=="Calendar"){
			$sql = "
			select
			distinct(aicrm_activity.activityid) as crmid,
			activitytype as 'event_type',
			aicrm_activity.subject as 'event_name',
			aicrm_crmentity.description ,
			date_start,
			replace(time_start,'.',':') as time_start,
			due_date,
			replace(time_end,'.',':') as time_end,
			viewedtime,modifiedtime,smownerid,smcreatorid,modifiedby";
			$sql.=$sql_from;
			$sql.= " and aicrm_crmentity.smownerid='".$user_id."'
			";
		}
		//echo $sql;exit;
		return $sql;
	}
	
	function __timediff($d1, $d2) {
		list($t1_1, $t1_2) = explode(' ', $d1);
		list($t1_y, $t1_m, $t1_d) = explode('-', $t1_1);
		list($t1_h, $t1_i, $t1_s) = explode(':', $t1_2);

		$t1 = mktime($t1_h, $t1_i, $t1_s, $t1_m, $t1_d, $t1_y);

		list($t2_1, $t2_2) = explode(' ', $d2);
		list($t2_y, $t2_m, $t2_d) = explode('-', $t2_1);
		list($t2_h, $t2_i, $t2_s) = explode(':', $t2_2);

		$t2 = mktime($t2_h, $t2_i, $t2_s, $t2_m, $t2_d, $t2_y);

		if( $t1 == $t2 ) return 0;
		return $t2 - $t1;
	}	
	function check_date_calendar($d1, $d2) {
		//echo $d1."<br>";
		//echo $d2."<br>";
		$today1=date('Y-m-d H:i:s');
		list($today_1, $today_2) = explode(' ', $today1);
		list($today_y, $today_m, $today_d) = explode('-', $today_1);
		list($today_h, $today_i, $today_s) = explode(':', $today_2);
		
		$today = mktime(0, 0, 0, $today_m, $today_d, $today_y);
		
		list($t1_1, $t1_2) = explode(' ', $d1);
		list($t1_y, $t1_m, $t1_d) = explode('-', $t1_1);
		list($t1_h, $t1_i, $t1_s) = explode(':', $t1_2);

		$t1 = mktime($t1_h, $t1_i, $t1_s, $t1_m, $t1_d, $t1_y);
		$t1_chk = mktime(0, 0, 0, $t1_m, $t1_d, $t1_y);

		list($t2_1, $t2_2) = explode(' ', $d2);
		list($t2_y, $t2_m, $t2_d) = explode('-', $t2_1);
		list($t2_h, $t2_i, $t2_s) = explode(':', $t2_2);

		$t2 = mktime($t2_h, $t2_i, $t2_s, $t2_m, $t2_d, $t2_y);
		//echo $t1."<br>";
		//echo $t2."<br>";
		//echo $t2 - $t1;
		//echo $t1." ".$t2;
		/*
		if(($today==$t1_chk) || ($t1_chk< $today)){
			$data = array(
				'jsonrpc' => '2.0', //version
				'id' =>$json_id, //String/Number
				'result' =>array(
					'error_message' => 'Start Date & Time should be greater than or equal to Current date & time for Calendar with status as Planned'
				)
			);
			//print_r($data);
			return $data;
		}else if(($t1==$t2)){
			$data = array(
				'jsonrpc' => '2.0', //version
				'id' =>$json_id, //String/Number
				'result' =>array(
					'error_message' => 'End Time Should be greater than Start Time'
				)
			);
			return $data;
		}else if( ($t2<$t1)){
			$data = array(
				'jsonrpc' => '2.0', //version
				'id' =>$json_id, //String/Number
				'result' =>array(
					'error_message' => 'End Time Should be greater than Start Time'
				)
			);
			return $data;
		}else{
			return "1";
		}
		*/
		return "1";
	}
	
?>