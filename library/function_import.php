<?
//Function==================================================================================
function Replace_text($value){
	return str_replace("'","''",$value);
}
function Get_Branch($branchid){
	global $genarate;
	$sql =" select aicrm_branchscf.cf_1060 ";
	$sql.=" from  aicrm_branchscf";
	$sql.=" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_branchscf.branchid";
	$sql.=" where aicrm_crmentity.deleted=0";
	$sql.=" and aicrm_branchscf.cf_1060='".$branchid."'";
	$data = $genarate->process($sql,"all");
	if(count($data)>0){
		return "1";
	}else{
		return "0";
	}
}
function Get_BranchId($branchid){
	global $genarate;
	$sql =" select aicrm_branchscf.branchid";
	$sql.=" from  aicrm_branchscf";
	$sql.=" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_branchscf.branchid";
	$sql.=" where aicrm_crmentity.deleted=0";
	$sql.=" and aicrm_branchscf.cf_1060='".$branchid."'";
	$data = $genarate->process($sql,"all");
	if(count($data)>0){
		return $data[0][0];
	}else{
		return "0";
	}
}
function Get_BranchName($branchid){
	global $genarate;
	$sql =" select aicrm_branchs.branch_name";
	$sql.=" from  aicrm_branchs";
	$sql.=" left join aicrm_branchscf on aicrm_branchs.branchid=aicrm_branchscf.branchid";
	$sql.=" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_branchscf.branchid";
	$sql.=" where aicrm_crmentity.deleted=0";
	$sql.=" and aicrm_branchscf.cf_1060='".$branchid."'";
	$data = $genarate->process($sql,"all");
	if(count($data)>0){
		return $data[0][0];
	}else{
		return "0";
	}
}
function Get_Member($member_code){
	global $genarate;	
	$sql =" select aicrm_applications.applicationid ";
	$sql.=" from  aicrm_applications";
	$sql.=" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_applications.applicationid";
	$sql.=" where aicrm_crmentity.deleted=0";
	$sql.=" and aicrm_applications.application_name='".$member_code."'";
	$data = $genarate->process($sql,"all");
	if(count($data)>0){
		return "1";
	}else{
		return "0";
	}
}
function Add_Branch($branchid,$branch_name){
	global $genarate;	
	$sql=" select (id+1) from aicrm_crmentity_seq ";
	$id_seq = $genarate->process($sql,"all");	
	$cid=$id_seq[0][0];
	$date=date('Y-m-d H:i:s');
	
	//aicrm_crmentity
	$sql1 = "insert into  aicrm_crmentity  (crmid,smcreatorid,smownerid,setype,createdtime,modifiedtime,version,presence,deleted) 
	values ('".$cid."','10809','10809','Branch','".$date."','".$date."','0','1','0')";
	//echo $sql1."<br>";
	$genarate->query($sql1);	
	
	//aicrm_crmentity_seq
	$sql2 = "update  aicrm_crmentity_seq set id='".$cid."'";
	//echo $sql2."<br>";
	$genarate->query($sql2);				
	
	//aicrm_branchs
	$sql_num=" select prefix,cur_id from aicrm_modentity_num  where num_id='25'";
	$result_num = $genarate->process($sql_num,"all");	
	$proid=$result_num[0][0].$result_num[0][1];
	
	$sql3 = "
	insert into  aicrm_branchs  (branchid,branch_no,branch_name) 
	values('".$cid."','".$proid."','".$branch_name."')";
	//echo $sql3."<br>";
	$genarate->query($sql3);
	
	$sql4 = "update  aicrm_modentity_num set cur_id='".($result_num[0][1]+1)."' where num_id='25'";
	//echo $sql4."<br>";
	$genarate->query($sql4);

	$sql5 = "insert into  aicrm_branchscf (branchid,cf_1060) 
	values('".$cid."','".$branchid."')";
	//echo $sql5."<br>";exit;
	$genarate->query($sql5);
}
function Get_MemberId($member_code){
	global $genarate;	
	$sql =" select aicrm_applications.applicationid ";
	$sql.=" from  aicrm_applications";
	$sql.=" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_applications.applicationid";
	$sql.=" where aicrm_crmentity.deleted=0";
	$sql.=" and aicrm_applications.application_name='".$member_code."'";
	$data = $genarate->process($sql,"all");
	if(count($data)>0){
		return $data[0][0];
	}else{
		return "0";
	}
}
function Add_Member($member_code,$branchid,$branch_name,$application_date,$application_time,$importdate){
	global $genarate;	
	$sql=" select (id+1) from aicrm_crmentity_seq ";
	$id_seq = $genarate->process($sql,"all");	
	$cid=$id_seq[0][0];
	$date=date('Y-m-d H:i:s');
	
	$sql="select id from aicrm_users where user_name='PTS".$branchid."'";
	$data_user = $genarate->process($sql,"all");
	$userid=$data_user[0][0];
	if($userid==""){
		$userid="10809";
	}
	
	//aicrm_crmentity
	$sql1 = "insert into  aicrm_crmentity  (crmid,smcreatorid,smownerid,setype,createdtime,modifiedtime,version,presence,deleted) 
	values ('".$cid."','".$userid."','".$userid."','Application','".$application_date." ".$application_time."','".$application_date." ".$application_time."','0','1','0')";
	//echo $sql1."<br>";
	if($genarate->query($sql1)){
		//aicrm_crmentity_seq
		$sql2 = "update  aicrm_crmentity_seq set id='".$cid."'";
		//echo $sql2."<br>";
		if($genarate->query($sql2)){
			//aicrm_branchs
			$sql_num=" select prefix,cur_id from aicrm_modentity_num  where num_id='24'";
			$result_num = $genarate->process($sql_num,"all");	
			$proid=$result_num[0][0].$result_num[0][1];
			
			$sql3 = "
			insert into  aicrm_applications  (applicationid,application_no,application_name) 
			values('".$cid."','".$proid."','".$member_code."')";
			//echo $sql3."<br>";
			if($genarate->query($sql3)){
				$sql4 = "update  aicrm_modentity_num set cur_id='".($result_num[0][1]+1)."' where num_id='24'";
				//echo $sql4."<br>";
				if($genarate->query($sql4)){
					$branchid=Get_BranchId($branchid);
					$sql5 = "insert into  aicrm_applicationscf (applicationid,cf_962,branchid,cf_1118,cf_1120) 
					values('".$cid."','".$application_date."','".$branchid."','".$application_time."','".$importdate."')";
					//echo $sql5."<br>";exit;
					$genarate->query($sql5);						
				}
			}
		}
	}
}
function Get_Premium($premiumid){
	global $genarate;
	$sql =" select aicrm_premiumscf.cf_1125 ";
	$sql.=" from  aicrm_premiumscf";
	$sql.=" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_premiumscf.premiumid";
	$sql.=" where aicrm_crmentity.deleted=0";
	$sql.=" and aicrm_premiumscf.cf_1125='".$premiumid."'";
	$data = $genarate->process($sql,"all");
	if(count($data)>0){
		return "1";
	}else{
		return "0";
	}
}
function Get_PremiumId($premiumid){
	global $genarate;
	$sql =" select aicrm_premiumscf.premiumid";
	$sql.=" from  aicrm_premiumscf";
	$sql.=" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_premiumscf.premiumid";
	$sql.=" where aicrm_crmentity.deleted=0";
	$sql.=" and aicrm_premiumscf.cf_1125='".$premiumid."'";
	$data = $genarate->process($sql,"all");
	if(count($data)>0){
		return $data[0][0];
	}else{
		return "0";
	}
}
function Get_PremiumName($premiumid){
	global $genarate;
	$sql =" select aicrm_premiums.premium_name";
	$sql.=" from  aicrm_premiums";
	$sql.=" left join aicrm_premiumscf on aicrm_premiums.premiumid=aicrm_premiumscf.premiumid";
	$sql.=" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_premiumscf.premiumid";
	$sql.=" where aicrm_crmentity.deleted=0";
	$sql.=" and aicrm_premiumscf.cf_1125='".$premiumid."'";
	$data = $genarate->process($sql,"all");
	if(count($data)>0){
		return $data[0][0];
	}else{
		return "0";
	}
}
function Add_Premium($premiumid,$premium_name){
	global $genarate;	
	$sql=" select (id+1) from aicrm_crmentity_seq ";
	$id_seq = $genarate->process($sql,"all");	
	$cid=$id_seq[0][0];
	$date=date('Y-m-d H:i:s');
	
	//aicrm_crmentity
	$sql1 = "insert into  aicrm_crmentity  (crmid,smcreatorid,smownerid,setype,createdtime,modifiedtime,version,presence,deleted) 
	values ('".$cid."','1','1','Premium','".$date."','".$date."','0','1','0')";
	//echo $sql1."<br>";
	$genarate->query($sql1);
	
	//aicrm_crmentity_seq
	$sql2 = "update  aicrm_crmentity_seq set id='".$cid."'";
	//echo $sql2."<br>";
	$genarate->query($sql2);		
	
	//aicrm_branchs
	$sql_num=" select prefix,cur_id from aicrm_modentity_num  where num_id='26'";
	$result_num = $genarate->process($sql_num,"all");	
	$proid=$result_num[0][0].$result_num[0][1];
	
	$sql3 = "
	insert into  aicrm_premiums  (premiumid,premium_no,premium_name) 
	values('".$cid."','".$proid."','".$premium_name."')";
	//echo $sql3."<br>";
	$genarate->query($sql3);	
	
	$sql4 = "update  aicrm_modentity_num set cur_id='".($result_num[0][1]+1)."' where num_id='26'";
	//echo $sql4."<br>";
	$genarate->query($sql4);	

	$sql5 = "insert into  aicrm_premiumscf (premiumid,cf_1125) 
	values('".$cid."','".$premiumid."')";
	//echo $sql5."<br>";exit;
	$genarate->query($sql5);	
}
function Get_Product($productid){
	global $genarate;
	$sql =" select aicrm_productcf.cf_1124 ";
	$sql.=" from  aicrm_productcf";
	$sql.=" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_productcf.productid";
	$sql.=" where aicrm_crmentity.deleted=0";
	$sql.=" and aicrm_productcf.cf_1124='".$productid."'";
	$data = $genarate->process($sql,"all");
	if(count($data)>0){
		return "1";
	}else{
		return "0";
	}
}
function Get_ProductId($productid){
	global $genarate;
	$sql =" select aicrm_productcf.productid ";
	$sql.=" from  aicrm_productcf";
	$sql.=" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_productcf.productid";
	$sql.=" where aicrm_crmentity.deleted=0";
	$sql.=" and aicrm_productcf.cf_1124='".$productid."'";
	$data = $genarate->process($sql,"all");
	if(count($data)>0){
		return $data[0][0];
	}else{
		return "0";
	}
}
function Get_ProductName($productid){
	global $genarate;
	$sql =" select aicrm_products.productname";
	$sql.=" from  aicrm_products";
	$sql.=" left join aicrm_productcf on aicrm_products.productid=aicrm_productcf.productid";
	$sql.=" left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_products.productid";
	$sql.=" where aicrm_crmentity.deleted=0";
	$sql.=" and aicrm_productcf.cf_1124='".$productid."'";
	$data = $genarate->process($sql,"all");
	if(count($data)>0){
		return $data[0][0];
	}else{
		return "0";
	}
}
function Add_Product($productid,$productname){
	global $genarate;	
	$sql=" select (id+1) from aicrm_crmentity_seq ";
	$id_seq = $genarate->process($sql,"all");	
	$cid=$id_seq[0][0];
	$date=date('Y-m-d H:i:s');
	
	//aicrm_crmentity
	$sql1 = "insert into  aicrm_crmentity  (crmid,smcreatorid,smownerid,setype,createdtime,modifiedtime,version,presence,deleted) 
	values ('".$cid."','1','1','Products','".$date."','".$date."','0','1','0')";
	//echo $sql1."<br>";
	$genarate->query($sql1);
	
	//aicrm_crmentity_seq
	$sql2 = "update  aicrm_crmentity_seq set id='".$cid."'";
	//echo $sql2."<br>";
	$genarate->query($sql2);		
	
	//aicrm_branchs
	$sql_num=" select prefix,cur_id from aicrm_modentity_num  where num_id='11'";
	$result_num = $genarate->process($sql_num,"all");	
	$proid=$result_num[0][0].$result_num[0][1];
	
	$sql3 = "
	insert into  aicrm_products  (productid,product_no,productname) 
	values('".$cid."','".$proid."','".$productname."')";
	//echo $sql3."<br>";
	$genarate->query($sql3);	
	
	$sql4 = "update  aicrm_modentity_num set cur_id='".($result_num[0][1]+1)."' where num_id='11'";
	//echo $sql4."<br>";
	$genarate->query($sql4);	

	$sql5 = "insert into  aicrm_productcf (productid,cf_1124) 
	values('".$cid."','".$productid."')";
	//echo $sql5."<br>";exit;
	$genarate->query($sql5);	
}

function Insert_Log($member_code,$branchid,$branch_name,$application_date,$application_time,$importdate,$msg){
	global $genarate;	
	$sql="insert into tbt_member_log(member_code,branchid,branch_name,application_date,application_time,importdate,msg)values(";
	$sql.=" '".$member_code."','".$branchid."','".$branch_name."','".$application_date."','".$application_time."','".$importdate."','".$msg."')";
	if($genarate->query($sql)){
	}
}
function Insert_Log_Tran($idd,$id,$sequence_no,$trandate,$trantime,$applicationcard,$point_total,$point,$point_remain,$productid,$product_name,
$branchid,$branch_name,$status,$tranupdate,$tranimport,$flag,$source,$applicationid,$accountid,$campaignid,$msg){
	global $genarate;	
	$sql=" select ";
	$sql.=" CASE WHEN if( isnull(max(sequence_no)) ,'',max(sequence_no)) = '' THEN '0' ELSE max(sequence_no) END as sequence_no";
	$sql.=" from tbt_transaction_log where applicationcard='".$applicationcard."'";
	$data_tran = $genarate->process($sql,"all");
	if(count($data_tran)>0){
		$sequence_no=$data_tran[0][0]+1;
	}else{
		$sequence_no=1;
	}
	$sql="insert into tbt_transaction_log(";
	$sql.="id,sequence_no,trandate,trantime,applicationcard,point_total,point,point_remain,productid,product_name,";
	$sql.="branchid,branch_name,status,tranupdate,tranimport,flag,source,applicationid,accountid,campaignid,msg,idd)";
	$sql.="values(";
	$sql.="'".$id."','".$sequence_no."','".$trandate."','".$trantime."','".$applicationcard."','".$point_total."','".$point."','".$point_remain."','".$productid."','".$product_name."',";
	$sql.="'".$branchid."','".$branch_name."','".$status."','".$tranupdate."','".$tranimport."','".$flag."','".$source."','".$applicationid."','".$accountid."','".$campaignid."','".$msg."','".$idd."'";
	$sql.=")";
	if($genarate->query($sql)){
	}
}
function Execute($member_code,$branchid,$branch_name,$application_date,$application_time,$importdate,$sql){
	global $genarate;	
	begin_tran(); 
	$genarate->query($sql);
	if(error_tran()){
		$msg=str_replace("'","''",error_tran());
		rollback_tran();
		Insert_Log($member_code,$branchid,$branch_name,$application_date,$application_time,$importdate,$msg);
	}else{
		commit_tran();
	}
}
function Execute_Tran($idd,$id,$sequence_no,$trandate,$trantime,$applicationcard,$point_total,$point,$point_remain,$productid,$product_name,
$branchid,$branch_name,$status,$tranupdate,$tranimport,$flag,$source,$applicationid,$accountid,$campaignid){
	global $genarate;	
	begin_tran(); 
	$sql="insert into tbt_transaction(";
	$sql.="id,sequence_no,trandate,trantime,applicationcard,point_total,point,point_remain,productid,product_name,";
	$sql.="branchid,branch_name,status,tranupdate,tranimport,flag,source,applicationid,accountid,campaignid,idd)";
	$sql.="values(";
	$sql.="'".$id."','".$sequence_no."','".$trandate."','".$trantime."','".$applicationcard."','".$point_total."','".$point."','".$point_remain."','".$productid."','".$product_name."',";
	$sql.="'".$branchid."','".$branch_name."','".$status."','".$tranupdate."','".$tranimport."','".$flag."','".$source."','".$applicationid."','".$accountid."','".$campaignid."','".$idd."'";
	$sql.=")";
	$genarate->query($sql);
	if(error_tran()){
		$msg=str_replace("'","''",error_tran());
		rollback_tran();
		Insert_Log_Tran($idd,$id,$sequence_no,$trandate,$trantime,$applicationcard,$point_total,$point,$point_remain,$productid,$product_name,
$branchid,$branch_name,$status,$tranupdate,$tranimport,$flag,$source,$applicationid,$accountid,$campaignid,$msg);
	}else{
		commit_tran();
		Insert_Log_Tran($idd,$id,$sequence_no,$trandate,$trantime,$applicationcard,$point_total,$point,$point_remain,$productid,$product_name,
$branchid,$branch_name,$status,$tranupdate,$tranimport,$flag,$source,$applicationid,$accountid,$campaignid,"Import Complete");	
		$sql="delete from tbt_transaction_tmp where idd='".$idd."'";
		if($genarate->query($sql,"all")){
		}	
	}
}
//Function==================================================================================
?>