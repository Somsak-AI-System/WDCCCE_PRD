<?php
	include_once("library/generate_MYSQL.php");
	include_once("library/dbconfig.php");
	include_once("library/myFunction.php");
	global $generate;
	$generate = new generate($dbconfig ,"db_voiz");
	function Get_User($user_name,$pass_word){
		global $generate;
		$con=" AND blockid in (77)";
		if($user_name!="" and $pass_word!=""){
			$sql="
			select
			id,user_name,first_name,last_name,email1
			from aicrm_users
			where  1
			and user_name='".$user_name."'
			and user_hash='".md5($pass_word)."'
			";	
			//echo $sql;
			$data_user = $generate->process($sql,"all");
			if(count($data_user)>0){
				$Module="Users";
				$Tabid=29;
				$data_form=Get_Block($Module,$Tabid,$con);
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' => $data_user[0]['id'], //String/Number
					'error' =>array(
						'code'=>'',//error code
						'message'=>''//error message
					), //version
					'result' => array(
						'TabId'=>'29',
						'ModuleName'=>'Users',
						'Form' =>array(
							'Block'=>$data_form,
							'Data'=>$data_user
						)
					)
				);
			}else{
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' =>NULL, //String/Number
					'error' =>array(
						'code'=>'-32600',//error code
						'message'=>'Invalid Request'//error message
					), //version
					'result' => array(
						'TabId'=>'29',
						'ModuleName'=>'Users',
						'Form' =>array(
							'Block'=>'',
							'Data'=>''
						)
					)
				);	
			}
		}else{
			$data = array(
				'jsonrpc' => '2.0', //version
				'id' =>NULL, //String/Number
				'error' =>array(
					'code'=>'-32600',//error code
					'message'=>'Invalid Request'//error message
				), //version
				'result' => array(
					'TabId'=>'29',
					'ModuleName'=>'Users',
					'Form' =>array(
						'Block'=>'',
						'Data'=>''
					)
				)
			);
		}
		return $data;
	}
	function Get_Block($Module,$Tabid,$con){
		global $generate;
		$sql="
		select 
		blockid,blocklabel,sequence
		from aicrm_blocks
		where 1
		and tabid='".$Tabid."'
		".$con."
		order by sequence
		";
		$data_block = $generate->process($sql,"all");

		$data_fs=array();
		$data_f=array();
		$data_form1=array();
		$a=0;
		//echo count($data_block);exit;
		for($i=0;$i<count($data_block);$i++){
			$sql="
			SELECT 
			tabid, fieldid, columnname, tablename, uitype, fieldlabel, readonly, presence, typeofdata, block, sequence
			FROM aicrm_field
			WHERE 1
			AND presence <> '1'
			AND tabid ='".$Tabid."'
			and quickcreate='2'
			or (typeofdata LIKE '%~M' and presence<>'2' and tabid ='".$Tabid."')
			and block='".$data_block[$i]['blockid']."'
			ORDER BY block, sequence
			";
			//echo $sql."<br><br>";exit;
			$data_field = $generate->process($sql,"all");		
			//echo count($data_field);exit;
			for($j=0;$j<count($data_field);$j++){
				$tabid=$data_field[$j]['tabid'];
				$columnname=$data_field[$j]['columnname'];
				$fieldlabel=$data_field[$j]['fieldlabel'];
				$tablename=$data_field[$j]['tablename'];
				$fieldid=$data_field[$j]['fieldid'];
				$uitype=$data_field[$j]['uitype'];
				$typeofdata=$data_field[$j]['typeofdata'];
				$type_of_data  = explode('~',$typeofdata);
				$type=$type_of_data[0];
				$typeofdata=$type_of_data[1];
				$lenght=$type_of_data[3];
				
				if(($type=="V" || $type=="E") && $lenght==""){
					$type_1="varchar(100)";
				}else if (($type=="V" || $type=="E") && $lenght!=""){
					$type_1="varchar(".$lenght.")";
				}else if ($type=="D"){
					$type_1="date";
				}
				if ($typeofdata == 'M'){
					$check_value="yes";
				}else{
					$check_value="no";
				}
				$data_f=array(
					'fieldid' => $fieldid,
					'columnname' => $columnname,
					'tablename' => $tablename,
					'uitype' => $uitype,
					'fieldlabel' => $fieldlabel,
					'type' => $type_1,
					'check_value' => $check_value,
					'value' => ''
				);
				$data_fs[]=$data_f;
			}	
			//Get Block Name=========================================
			if($data_block[$i]['blocklabel']=="LBL_USERLOGIN_ROLE"){
				$block_name="User Login";
			}else{
				$block_name=$data_block[$i]['blocklabel'];
			}
			$data_block1=array(
				'BlockName'=>$block_name,
				'Fields'=>$data_fs
			);	
			$data_form1[]=$data_block1;
		}
		//print_r($data_form1);
		//exit;
		return $data_form1;
	}
?>