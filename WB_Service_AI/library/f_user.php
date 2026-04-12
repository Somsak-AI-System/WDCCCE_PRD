<?
		function Get_Company(){
		$FileName = "library/company.txt";
		$FileHandle = fopen($FileName, 'r+') or die("can't open file");
		$company = file($FileName);
		$data_com=array();
		$data_com1=array();
		if(count($company)>0){
			for($i=0;$i<count($company);$i++){
				if(trim($company[$i])!=""){
					$data_company=str_replace('"','',split('","',trim($company[$i])));
					for($k=0;$k<count($data_company);$k++){
						$str=split("=",trim($data_company[$k]));
						//$data_com[$str[0]]=$str[1];
						$data_com=array(
							'Company_name'=>$str[0],
							'Company_path'=>$str[1]
						);
						$data_com1[]=$data_com;
					}
				}
			}
			$data=array(
				'jsonrpc' => '2.0', //version
				'id' => $data_user[0]['id'], //String/Number
				'result' =>array(
					//'error_message' =>'' ,
					'company'=>$data_com1
				)
			);			
		}else{
			$data = array(
				'jsonrpc' => '2.0', //version
				'id' =>NULL, //String/Number
				'error' =>array(
					'code'=>'-32600',//error code
					'message'=>'Invalid Data'//error message
				)
			);	
		}
		//print_r($data);
		//exit;
		return $data ;
	}
	
	function Get_User($json_id,$username,$password){
		//echo 555;
		global $generate,$img_path;
		$con=" AND blockid in (77)";
		
		if($username!="" and $password!=""){
			$salt = substr($username, 0, 2);
			$salt = '$1$' . $salt . '$';
			$encrypted_password = crypt($password, $salt);	
			//echo $encrypted_password;exit;
			//$encrypted_password ='$1$ad$hsl2KFybNRnbXBa.b.WWv.';
			$sql="
			select
			id,user_name,first_name,last_name,email1,imagename
			from aicrm_users
			where  1
			and user_name='".$username."'
			and user_password ='".$encrypted_password."'
			";	
			//echo $sql; exit;
			$data_user = $generate->process($sql,"all");
			//print_r($data_user );
			//exit;
			if(count($data_user)>0){
				$Module="Users";
				$Tabid=29;
				$data_form=Get_Block($Module,$Tabid,$con);
				if(count($data_form)>1){
					$data_F=$data_form;
				}else{
					$data_F=$data_form[0];
				}
				$data_permission=Get_Permission($data_user[0]['id']);
				if($data_user[0]['imagename']!=""){
					$new_x=96;
					$new_y=96;
					$file=$img_path.$data_user[0]['imagename'];
					$quality=50;
					
	 
					$im = file_get_contents($file);
					$imdata = base64_encode($im);
				}else{
					$imdata ="";
				}
				

				$data = array(
					'jsonrpc' => '2.0', //version
					'id' => $json_id, //String/Number
					'result' =>array(
						//'error_message' =>'' ,
						'user_id' => $data_user[0]['id'],
						'user_name' => $data_user[0]['user_name'],
						'first_name' => $data_user[0]['first_name'],
						'last_name' => $data_user[0]['last_name'],
						'email' => $data_user[0]['email1'],
						'image' => $imdata,
						'module_name'=>$data_permission,
						//'Form'=>$data_F
					)
				);
			}else{
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' =>$json_id, //String/Number
					'result' => array(
						'error_message'=>'Login Fail.'
					)
				);	
			}
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
		return $data;
	}
	function Get_User_ios($json_id,$username,$password){
		global $generate,$img_path;
		$con=" AND blockid in (77)";
		if($username!="" and $password!=""){
			$salt = substr($username, 0, 2);
			$salt = '$1$' . $salt . '$';
			$encrypted_password = crypt($password, $salt);	
			//echo $encrypted_password;exit;
			$sql="
			select
			id,user_name,first_name,last_name,email1,imagename
			from aicrm_users
			where  1
			and user_name='".$username."'
			and user_password ='".$encrypted_password."'
			";	
			//echo $sql;exit;
			$data_user = $generate->process($sql,"all");
			//print_r($data_user );
			//exit;
			if(count($data_user)>0){
				$Module="Users";
				$Tabid=29;
				$data_form=Get_Block($Module,$Tabid,$con);
				if(count($data_form)>1){
					$data_F=$data_form;
				}else{
					$data_F=$data_form[0];
				}
				$data_permission=Get_Permission_ios($data_user[0]['id']);
				if($data_user[0]['imagename']!=""){
					$new_x=96;
					$new_y=96;
					$file=$img_path.$data_user[0]['imagename'];
					$quality=50;
					
	 
					$im = file_get_contents($file);
					$imdata = base64_encode($im);
				}else{
					$imdata ="";
				}
				

				$data = array(
					'jsonrpc' => '2.0', //version
					'id' => $json_id, //String/Number
					'result' =>array(
						//'error_message' =>'' ,
						'user_id' => $data_user[0]['id'],
						'user_name' => $data_user[0]['user_name'],
						'first_name' => $data_user[0]['first_name'],
						'last_name' => $data_user[0]['last_name'],
						'email' => $data_user[0]['email1'],
						'image' => $imdata,
						'module_name'=>$data_permission,
						//'Form'=>$data_F
					)
				);
			}else{
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' =>$json_id, //String/Number
					'result' => array(
						'error_message'=>'Login Fail.'
					)
				);	
			}
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
		return $data;
	}
	
	function Get_Permission($userid){
		global $generate;
		$sql="
		select
		aicrm_role2profile.profileid
		from aicrm_user2role 
		left join aicrm_role2profile on aicrm_role2profile.roleid=aicrm_user2role.roleid 
		where 1
		and aicrm_user2role.userid='".$userid."'
		";
		//echo $sql;
		$data= $generate->process($sql,"all");
		$profileid=$data[0]['profileid'];
		
		$sql="
		select  
		permissions ,
		tabid
		from aicrm_profile2tab
		where 1
		/*and tabid in(9,38)*/
		and tabid in(9,47)
		and profileid='".$profileid."'
		";
		$data_tab= $generate->process($sql,"all");
		$data_action=array();
		$data_action1=array();
		for($i=0;$i<count($data_tab);$i++){
			$sql="
			select
			permissions
			from aicrm_profile2standardpermissions
			where 1
			and profileid='".$profileid."'
			and tabid='".$data_tab[$i]['tabid']."'
			and operation in(1,2,4)
			";
			//echo $sql;exit;
			$data_at= $generate->process($sql,"all");
			
			$AddEdit=$data_at[0]['permissions'];
			$Delete=$data_at[1]['permissions'];
			$View=$data_at[2]['permissions'];
			
			$ModuleAccess=$data_tab[$i]['permissions'];
			
			$data_action=array(
				'add_edit'=>$AddEdit,
				'delete'=>$Delete
			);
			$data_action1[Get_Tab_Name($data_tab[$i]['tabid'])]=$data_action;				
		}
		return $data_action1;
	}
	function Get_Permission_ios($userid){
		//echo "555";
		include("../config.inc.php");
		//require_once("library/generate_MYSQL.php");
		global $generate;
		//echo "<pre>";
		//print_r($permission_mobile);
		//echo "</pre>";
		
		//exit;
		$sql="
		select
		aicrm_role2profile.profileid
		from aicrm_user2role 
		left join aicrm_role2profile on aicrm_role2profile.roleid=aicrm_user2role.roleid 
		where 1
		and aicrm_user2role.userid='".$userid."'
		";
		//echo $sql;
		$data= $generate->process($sql,"all");
		$profileid=$data[0]['profileid'];
		
		$tab_id="";
		for($i=0;$i<count($permission_mobile);$i++){
			if($tab_id==""){
				$tab_id=$permission_mobile[$i]['mobile_id'];
			}else{
				$tab_id.=",".$permission_mobile[$i]['mobile_id'];	
			}
		}
		$sql="
		select  
		permissions ,
		tabid
		from aicrm_profile2tab
		where 1
		and tabid in(".$tab_id.")
		and profileid='".$profileid."'
		";
		$data_tab= $generate->process($sql,"all");
		$data_action=array();
		$data_action1=array();
		for($i=0;$i<count($data_tab);$i++){
			$sql="
			select
			permissions
			from aicrm_profile2standardpermissions
			where 1
			and profileid='".$profileid."'
			and tabid='".$data_tab[$i]['tabid']."'
			and operation in(1,2,4)
			";
			//echo $sql;
			$data_at= $generate->process($sql,"all");
			
			$AddEdit=$data_at[0]['permissions'];
			$Delete=$data_at[1]['permissions'];
			$View=$data_at[2]['permissions'];
			
			$ModuleAccess=$data_tab[$i]['permissions'];
			
			$data_action=array(
				'module_name'=>$permission_mobile[$i]['mobile_name'],
				'ios_flg'=>$permission_mobile[$i]['ios_flg'],
				'android_flg'=>$permission_mobile[$i]['android_flg'],
				'add_edit'=>$AddEdit,
				'view'=>$View,
				'delete'=>$Delete
			);
			$data_action1[Get_Tab_Name($data_tab[$i]['tabid'])]=$data_action;
		}
		//echo "<pre>";
		//print_r($data_action1);
		//echo "</pre>";
		//exit;
		return $data_action1;
	}		
?>