<?
	function Get_Account_Detail($accountid){
		global $generate;
		if($accountid!=""){
			$sql = "
			select 
			aicrm_account.accountid,account_no,accountname,
			case when cf_1145='' then cf_1144 else cf_1145 end as 'cf_1144'
			,cf_1423,cf_1145
			from aicrm_account 
			left join aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
			left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid
			left join aicrm_accountshipads on aicrm_accountshipads.accountaddressid=aicrm_account.accountid
			left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
			where deleted=0
			and aicrm_account.accountid='".$accountid."'
			";
			//echo $sql;
			$data= $generate->process($sql,"all");
			$data_phone=array();
			if(count($data)>0){
				$data1[0]['accountid']=$data[0]['accountid'];
				$data1[0]['accountname']=$data[0]['accountname'];
				$phone=split(",",str_replace("-","",$data[0]['cf_1144']));
				$phone_main="";
				$phone_to=$data[0]['cf_1423'];
				if(count($phone)>1){
					$phone_main=$phone[0];
				}else{
					$phone_main=$phone[0];
				}
				$str_phone=split("##",$phone_main);
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
				/*if($phone_to!="" and $data[0]['cf_1145']==""){
					$data_phone[0]['phone_main']=$phone_main;
					$data_phone[0]['phone_to']=$phone_to;
				}else{
					$data_phone[0]['phone_main']=$phone_main;
					$data_phone[0]['phone_to']="";
				}*/
				$data1[0]['phone']=$data_phone[0];
				if($data1[0]['phone']['phone_main']!=""){
					$data1[0]['is_phone']="true";
				}else{
					$data1[0]['is_phone']="false";
				}
				$data1[0]['form_detail']=Get_Block_Detail("Accounts",'',$data[0]['accountid']);
				return $data1[0];
			}else{
				return "";
			}
		}else{
			return "";
		}
	}
	
	function Get_Product_Detail($productid){
		global $generate;
		if($productid!=""){
			$sql = "
			select
			aicrm_products.productid,
			aicrm_products.product_no,
			aicrm_products.productname,
			aicrm_productcf.cf_1148 as 'model',
			aicrm_products.productcategory as 'pro_cat',
			aicrm_productcf.cf_1147 as 'pro_subcat',
			format(aicrm_products.unit_price,2) as unit_price
			from aicrm_products
			left join aicrm_productcf on aicrm_productcf.productid=aicrm_products.productid
			left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_products.productid
			where deleted=0
			and aicrm_products.productid='".$productid."'
			";
			//echo $sql;
			$data= $generate->process($sql,"all");
			$data_phone=array();
			if(count($data)>0){
				$data1[0]['productid']=$data[0]['productid'];
				$data1[0]['product_no']=$data[0]['product_no'];
				$data1[0]['productname']=$data[0]['productname'];
				//$data1[0]['model']=$data[0]['model'];
				$data1[0]['pro_cat']=$data[0]['pro_cat'];
				$data1[0]['pro_subcat']=$data[0]['pro_subcat'];
				$data1[0]['unit_price']=$data[0]['unit_price'];
				//echo $data[0]['productid'];exit;
				//$data1[0]['form_detail']=Get_Block_Detail("Products",'',$data[0]['productid']);
				return $data1[0];
			}else{
				return "";
			}
		}else{
			return "";
		}
	}
	
	function Get_Case_Detail($module,$ticketid){
		global $generate;
		$sql_from=Get_Query($module);
		//echo $sql_from;exit;
		if($ticketid!=""){
			$sql = "
			select
			aicrm_troubletickets.ticketid,
			aicrm_troubletickets.ticket_no
			";
			$sql.=$sql_from;
			$sql.="
			and aicrm_troubletickets.ticketid='".$ticketid."'
			";
			//echo $sql;exit;
			$data= $generate->process($sql,"all");
			$data_phone=array();
			if(count($data)>0){
				$data1[0]['ticketid']=$data[0]['ticketid'];
				$data1[0]['ticket_no']=$data[0]['ticket_no'];
				//$data1[0]['form_detail']=Get_Block_Detail("HelpDesk",'',$data[0]['ticketid']);
				return $data1[0];
			}else{
				return "";
			}
		}else{
			return "";
		}
	}
	
	function Get_Contact($accountid){
		global $generate;
		$sql = "
		select 
		concat(salutation,' ',firstname,' ',lastname) as con_name,
		case when mobile = '' then 	phone else mobile end as phone,
		email
		from aicrm_contactdetails 
		left join aicrm_contactscf on aicrm_contactscf.contactid=aicrm_contactdetails.contactid
		left join aicrm_contactaddress on aicrm_contactaddress.	contactaddressid=aicrm_contactdetails.contactid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_contactdetails.contactid
		where deleted=0
		and accountid='".$accountid."'
		";
		$data= $generate->process($sql,"all");
		$data1=array();
		
		for($i=0;$i<count($data);$i++){
			$data1[$i]['con_name']=$data[$i]['con_name'];
			$phone=split(",",str_replace("-","",$data[$i]['phone']));
			/*if(count($phone)>1){
				$data1[$i]['phone']=$phone[0];
			}else{
				$data1[$i]['phone']=$phone[0];
			}*/
			$str_phone=split("##",$phone[0]);
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
			$data1[$i]['phone']=$data_phone[0];
			$data1[$i]['email']=$data[$i]['email'];
		}
		return $data1;
	}
?>