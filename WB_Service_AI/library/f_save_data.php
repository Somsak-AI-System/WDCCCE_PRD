<?
	function update_data($json_id,$user_id,$module,$method,$data,$crmid,$action){
		global $generate;
		if($module == 'Job'){
			$module = 'Call Detail';	
		}
		
		if($method=="update_important"){
			if(count($data)>0){
				$data_msg=array();
				for($i=0;$i<count($data);$i++){
					$crmid= $data[$i]['crmid'];
					$date_time= $data[$i]['date_time'];
					$userid= $data[$i]['user_id'];
					//echo $data[$i]['crmid']."<br>";	
					$sql="update aicrm_crmentity set viewedtime='".$date_time."' where crmid='".$crmid."' ";
					//echo $sql;
					if($generate->query($sql)){
						$msg="Update Complete";
					}else{
						$msg="Cannot Update";
					}
					$data1=array(
						'crmid'=>$crmid,
						'message'=>$msg
					);
					$data_msg[]=$data1;
				}
				$data2 = array(
					'jsonrpc' => '2.0', //version
					'id' =>$json_id, //String/Number
					'result' =>$data_msg
				);
				return $data2;
			}
		}else if($method=="update_checkpoint"){
			if(count($data)>0){
				//print_r($data);
				for($i=0;$i<count($data);$i++){
					$tablename= $data[$i]['tablename'];
					$columnname= $data[$i]['columnname'];
					$value= $data[$i]['value'];
					//echo $columnname."<br>";	
					if($columnname=="cf_1246" || $columnname=="cf_1247" || $columnname=="cf_1248" || $columnname=="cf_1249" 
					|| $columnname=="cf_1425" || $columnname=="cf_1427" || $columnname=="cf_1430" || $columnname=="cf_1432"
					|| $columnname=="cf_1488" || $columnname=="cf_1490" || $columnname=="cf_1492" || $columnname=="cf_1494"
					){
						//echo $columnname."<br>";	
						$value=date('Y-m-d H:i:s');
						$data1=array(
							'crmid'=>$crmid,
							'tablename'=>$tablename,
							'columnname'=>$columnname,
							'value'=>$value
						);
					}
					if($module=="Call Detail"){
						$sql="update aicrm_jobdetailscf set ".$columnname."='".$value."' where jobdetailid='".$crmid."' ";
					}else{
						$sql="update ".$tablename." set ".$columnname."='".$value."' where ".get_pk($tablename)."='".$crmid."' ";
					}
					/*$FileName = "sql.txt";
					$FileHandle = fopen($FileName, 'a+') or die("can't open file");
					fwrite($FileHandle,$sql."\r\n");
					fclose($FileHandle);*/
					if($generate->query($sql)){
						if($columnname=="cf_1488"){
							$sql="update aicrm_jobdetailscf set cf_1211='".date('Y-m-d',strtotime($value))."',cf_1213='".date('H:i',strtotime($value))."' where jobdetailid='".$crmid."' ";
							$generate->query($sql);
						}else if($columnname=="cf_1492"){
							$sql="update aicrm_jobdetailscf set cf_1339='".date('Y-m-d',strtotime($value))."',cf_1316='".date('H:i',strtotime($value))."' where jobdetailid='".$crmid."' ";
							$generate->query($sql);
							/*
							update_Travel_Back_Date($module,$crmid);
							update_sla($crmid);
							update_Respons($crmid);
							update_ot($crmid);
							*/
						}else if($columnname=="cf_1494"){
							$sql="update aicrm_jobdetailscf set cf_1328='".date('Y-m-d',strtotime($value))."',cf_1217='".date('H:i',strtotime($value))."' where jobdetailid='".$crmid."' ";
							$generate->query($sql);
						}else if($columnname=="cf_1490"){
							$sql="update aicrm_jobdetailscf set cf_1215='".date('H:i',strtotime($value))."' where jobdetailid='".$crmid."' ";
							$generate->query($sql);
						}
						$data2 = array(
							'jsonrpc' => '2.0', //version
							'id' =>$json_id, //String/Number
							'result' =>$data1
						);
						$sql="update aicrm_crmentity modifiedby='".$user_id."' ,modifiedtime='".date('Y-m-d H:i:s')."' where crmid='".$crmid."' ";
						$generate->query($sql);
						$msg="Update Complete";
					}else{
						$data2 = array(
							'jsonrpc' => '2.0', //version
							'id' =>$json_id, //String/Number
							'result' =>array(
								'message' => 'Cannot Update'
							)
						);
						//$msg="Cannot Update";
					}
				}
				return $data2;
			}
		}else if($method=="add_edit"){
			
			$column2=array();
			$value2=array();
			$column3=array();
			$value3=array();
			$column4=array();
			$value4=array();
			$chk=0;
			$date=date('Y-m-d H:i:s');
			if(count($data)>0){
				if($module == 'Job'){
					$table1="aicrm_crmentity";
					$table2="aicrm_servicerequests";
					$table3="aicrm_servicerequestscf";
					
					if($action=="add"){
						$sql=" select (id+1) as id from aicrm_crmentity_seq ";
						$id_seq = $generate->process($sql,"all");	
						$cid=$id_seq[0]['id'];
						
						for($i=0;$i<count($data);$i++){
							if($data[$i]['columnname']=="description"){
								if($data[$i]['value']!=""){
									$description=$data[$i]['value'];
								}else{
									$description="";
								}
							}
						}
						//aicrm_crmentity
						$sql = "insert into  aicrm_crmentity  (crmid,smcreatorid,smownerid,setype,createdtime,modifiedtime,version,presence,deleted,description)  values ('".$cid."','".$user_id."','".$user_id."','Job','".$date."','".$date."','0','1','0','".$description."');";
						
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						
						//aicrm_crmentity_seq
						$sql = "update  aicrm_crmentity_seq set id='".$cid."';";
						//echo $sql."<br>";
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}	
						
						$sql_num=" select prefix,cur_id from aicrm_modentity_num  where num_id='18'";
						$result_num = $generate->process($sql_num,"all");	
						$proid=$result_num[0]['prefix'].$result_num[0]['cur_id'];
						
						array_push($column2, "servicerequestid");
						array_push($value2, "'".$cid."'");	
						for($i=0;$i<count($data);$i++){
							if($table2==$data[$i]['tablename']){
								if($data[$i]['columnname']=="servicerequest_no"){
									array_push($column2, $data[$i]['columnname']);
									array_push($value2, "'".$proid."'");
								}else{
									array_push($column2, $data[$i]['columnname']);
									array_push($value2, "'".$data[$i]['value']."'");
								}
							}
						}
						//aicrm_servicerequests
						$sql = "insert into $table2(". implode(",",$column2) .") values(". implode(",",$value2) .");";
						//echo $sql."<br>";
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						
						$sql = "update  aicrm_modentity_num set cur_id='".($result_num[0]['cur_id']+1)."' where num_id='18';";
						//echo $sql."<br>";
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}	
						
						array_push($column3, "servicerequestid");
						array_push($value3, "'".$cid."'");	
						for($i=0;$i<count($data);$i++){
							if($table3==$data[$i]['tablename']){
								array_push($column3, $data[$i]['columnname']);
								array_push($value3, "'".$data[$i]['value']."'");
							}
						}
						//aicrm_servicerequestscf
						$sql = "insert into $table3(". implode(",",$column3) .") values(". implode(",",$value3) .");";
						//echo $sql."<br>";
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						if($chk==0){
							$sql="select  table_name_auto,field_pk ,field_auto ,table_name_prefix,field_prefix ,length  from ai_config_autorun  where table_name_auto='aicrm_servicerequests';";
							$datat_up = $generate->process($sql,"all");	
							
							if(count($datat_up)>0){
								$table =$datat_up[0]['table_name_auto'];
								$field_pk =$datat_up[0]['field_pk'];
								$field_auto =$datat_up[0]['field_auto'];
								$table_name_prefix =$datat_up[0]['table_name_prefix'];
								$field_prefix =$datat_up[0]['field_prefix'];
								$length =$datat_up[0]['length'];
							}
					
							$sql="select cf_1192 from ".$table3 ." where servicerequestid='".$cid."';";
							$datat_up = $generate->process($sql,"all");	
							$cf_1192=$datat_up[0]['cf_1192'];
							if($cf_1192=="Internal"){
								$prefix ="JOI";
								$status="Open";
							}elseif($cf_1192=="External"){
								$prefix ="JOE";
								$status="--None--";
							}elseif($cf_1192=="Claim"){
								$prefix ="CL";
								$status="Open";
							}
							$cd = autocd_job($table,$field_auto,$condition,$prefix,$length,$field_pk);
							$sql = " update  ".$table." set ".$field_auto." = '".$cd."' where ".$field_pk." = '".$cid."' ";
							$generate->query($sql);
						}
						if($chk==1){
							$data = array(
								'jsonrpc' => '2.0', //version
								'id' =>$json_id, //String/Number
								'result' =>array(
									'message' => 'Can not Save'
								)
							);
						}else{
							$data = array(
								'jsonrpc' => '2.0', //version
								'id' =>$json_id, //String/Number
								'result' =>array(
									//'crmid' => $cid,
									'message' => 'Save Complete'
								)
							);
						}
					}else{//edit
						//aicrm_crmentity
						$sql="update $table1 set ";
						$con="";
						for($i=0;$i<count($data);$i++){
							if($table1==$data[$i]['tablename']){
								if($con==""){
									$con=$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}else{
									$con.=",".$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}
							}
						}
						if($con==""){
							$sql.=$con." smownerid='".$user_id."',
							modifiedtime='".$date."'
							where crmid='".$crmid."'
						";
						}else{
							$sql.=$con." , smownerid='".$user_id."',
							modifiedtime='".$date."'
							where crmid='".$crmid."'
						";
						}
						
						//echo $sql."<br>";exit;
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						
						//aicrm_servicerequests
						$sql="update $table2 set ";
						$con="";
						for($i=0;$i<count($data);$i++){
							if($table2==$data[$i]['tablename']){
								if($con==""){
									$con=$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}else{
									$con.=",".$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}
							}
						}
						$sql.=$con." where servicerequestid='".$crmid."';";
						//echo $sql."<br>";
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						
						//aicrm_servicerequestscf
						$sql="update $table3 set ";
						$con="";
						for($i=0;$i<count($data);$i++){
							if($table3==$data[$i]['tablename']){
								if($con==""){
									$con=$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}else{
									$con.=",".$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}
							}
						}
						$sql.=$con." where servicerequestid='".$crmid."';";
						//echo $sql."<br>";
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						if($chk==1){
							$data = array(
								'jsonrpc' => '2.0', //version
								'id' =>$json_id, //String/Number
								'result' =>array(
									'message' => 'Can not Update'
								)
							);
						}else{
							$data = array(
								'jsonrpc' => '2.0', //version
								'id' =>$json_id, //String/Number
								'result' =>array(
									'message' => 'Update Complete'
								)
							);
						}
					}
				}//end save Job	
				else if($module == 'Call Detail'){
					
					$table1="aicrm_crmentity";
					$table2="aicrm_jobdetails";
					$table3="aicrm_jobdetailscf";
					
					if($action=="add"){
						$sql=" select (id+1) as id from aicrm_crmentity_seq ";
						$id_seq = $generate->process($sql,"all");	
						$cid=$id_seq[0]['id'];
						$crmid=$cid;
						for($i=0;$i<count($data);$i++){
							if($data[$i]['columnname']=="description"){
								if($data[$i]['value']!=""){
									$description=$data[$i]['value'];
								}else{
									$description="";
								}
							}
						}
						//aicrm_crmentity
						$sql = "insert into  aicrm_crmentity  (crmid,smcreatorid,smownerid,setype,createdtime,modifiedtime,version,presence,deleted,description)  values ('".$cid."','".$user_id."','".$user_id."','JobDetail','".$date."','".$date."','0','1','0','".$description."');";
						
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						//aicrm_crmentity_seq
						$sql = "update  aicrm_crmentity_seq set id='".$cid."';";
						//echo $sql."<br>";
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}	
						
						$sql_num=" select prefix,cur_id from aicrm_modentity_num  where num_id='27'";
						$result_num = $generate->process($sql_num,"all");	
						$proid=$result_num[0]['prefix'].$result_num[0]['cur_id'];
						
						array_push($column2, "jobdetailid");
						array_push($value2, "'".$cid."'");	
						for($i=0;$i<count($data);$i++){
							if($table2==$data[$i]['tablename']){
								if($data[$i]['columnname']=="jobdetail_no"){
									array_push($column2, $data[$i]['columnname']);
									array_push($value2, "'".$proid."'");
								}else{
									if($data[$i]['value']=="null" || $data[$i]['value']==""){
										array_push($column2, $data[$i]['columnname']);
										array_push($value2, "NULL");
									}else{
										array_push($column2, $data[$i]['columnname']);
										array_push($value2, "'".$data[$i]['value']."'");
									}
								}
							}
						}
						//aicrm_servicerequests
						$sql = "insert into $table2(". implode(",",$column2) .") values(". implode(",",$value2) .");";
						//echo $sql."<br>";
						/**/
						
						
						if(!$generate->query($sql)){
							$chk=1;
						}
						
						$sql = "update  aicrm_modentity_num set cur_id='".($result_num[0]['cur_id']+1)."' where num_id='27';";
						//echo $sql."<br>";
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}	
						
						array_push($column3, "jobdetailid");
						array_push($value3, "'".$cid."'");	
						for($i=0;$i<count($data);$i++){
							if($table3==$data[$i]['tablename']){
								if($data[$i]['value']=="null" || $data[$i]['value']==""){
									array_push($column3, $data[$i]['columnname']);
									array_push($value3, "NULL");
								}else{
									array_push($column3, $data[$i]['columnname']);
									array_push($value3, "'".$data[$i]['value']."'");
								}
								/*$FileName = "sql.txt";
								$FileHandle = fopen($FileName, 'a+') or die("can't open file");
								fwrite($FileHandle,$data[$i]['columnname']."=>|".$data[$i]['value']."|\r\n");
								fclose($FileHandle);*/
							}
						}
						//aicrm_servicerequestscf
						$sql = "insert into $table3(". implode(",",$column3) .") values(". implode(",",$value3) .");";
						//echo $sql."<br>";
						
						$FileName = "sql11.txt";
						$FileHandle = fopen($FileName, 'a+') or die("can't open file");
						fwrite($FileHandle,$sql."\r\n");
						fclose($FileHandle);
						
						if(!$generate->query($sql)){
							$chk=1;
						}
						if($chk==0){
							
						}
						if($chk==1){
							$data = array(
								'jsonrpc' => '2.0', //version
								'id' =>$json_id, //String/Number
								'result' =>array(
									'message' => 'Can not Save'
								)
							);
						}else{
							$data = array(
								'jsonrpc' => '2.0', //version
								'id' =>$json_id, //String/Number
								'result' =>array(
									//'crmid' => $cid,
									'message' => 'Save Complete'
								)
							);
						}
					}else{//edit
						//aicrm_crmentity
						$sql="update $table1 set ";
						$con="";
						for($i=0;$i<count($data);$i++){
							if($table1==$data[$i]['tablename']){
								if($con==""){
									$con=$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}else{
									$con.=",".$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}
							}
						}
						if($con==""){
							$sql.=$con." smownerid='".$user_id."',
							modifiedtime='".$date."'
							where crmid='".$crmid."'
						";
						}else{
							$sql.=$con." , smownerid='".$user_id."',
							modifiedtime='".$date."'
							where crmid='".$crmid."'
						";
						}
					
						if(!$generate->query($sql)){
							$chk=1;
						}
						
						//aicrm_servicerequests
						$sql="update $table2 set ";
						$con="";
						for($i=0;$i<count($data);$i++){
							if($table2==$data[$i]['tablename']){
								if($con==""){
									$con=$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}else{
									$con.=",".$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}
							}
						}
						$sql.=$con." where jobdetailid='".$crmid."';";
						
						if($con!=""){
							if(!$generate->query($sql)){
								$chk=1;
							}
						}

						//aicrm_servicerequestscf
						$sql="update $table3 set ";
						$con="";
						for($i=0;$i<count($data);$i++){
							if($table3==$data[$i]['tablename']){
								if($data[$i]['value']=="null" || $data[$i]['value']==""){
									if($con==""){
										$con=$data[$i]['columnname']."= NULL ";
									}else{
										$con.=",".$data[$i]['columnname']."= NULL ";
									}
								}else{
									if($con==""){
										$con=$data[$i]['columnname']."= '".$data[$i]['value']."' ";
									}else{
										$con.=",".$data[$i]['columnname']."= '".$data[$i]['value']."' ";
									}
								}
							}
						}
						$sql.=$con." where jobdetailid='".$crmid."';";
						
						if($con!=""){
							if(!$generate->query($sql)){
								$chk=1;
							}
						}
						if($chk==1){
							$data = array(
								'jsonrpc' => '2.0', //version
								'id' =>$json_id, //String/Number
								'result' =>array(
									'message' => 'Can not Update'
								)
							);
						}else{
							$data = array(
								'jsonrpc' => '2.0', //version
								'id' =>$json_id, //String/Number
								'result' =>array(
									'message' => 'Update Complete'
								)
							);
						}
					}
					//Update All Data===============================================================================================================
					//Update Travel Back Date
					update_Travel_Back_Date($module,$crmid);
					update_sla($crmid);
					update_Respons($crmid);
					$FileName = "sql.txt";
					$FileHandle = fopen($FileName, 'a+') or die("can't open file");
					fwrite($FileHandle,"start".date('d-m-Y H:i:s')."\r\n");
					fclose($FileHandle);
					update_ot($crmid);
					$FileName = "sql.txt";
					$FileHandle = fopen($FileName, 'a+') or die("can't open file");
					fwrite($FileHandle,"stop".date('d-m-Y H:i:s')."\r\n");
					fclose($FileHandle);
					/*$FileName = "sql.txt";
					$FileHandle = fopen($FileName, 'a+') or die("can't open file");
					fwrite($FileHandle,$sql_from."\r\n");
					fclose($FileHandle);*/
					//Update All Data===============================================================================================================
				}//end save Call Detail
				else if($module == 'Calendar'){
					//validate value for calendar
					$date_start="";
					$due_date="";
					$time_start="";
					$time_end="";
					$planned="";
					for($i=0;$i<count($data);$i++){
						if($data[$i]['columnname']=="eventstatus" and $data[$i]['value']=="Planned"){
							$planned=$data[$i]['value'];
							for($j=0;$j<count($data);$j++){
								if($data[$j]['columnname']=="date_start"){
									$date_start=$data[$j]['value'];
								}else if($data[$j]['columnname']=="due_date"){
									$due_date=$data[$j]['value'];
								}else if($data[$j]['columnname']=="time_start"){
									$time_start=$data[$j]['value'];
								}else if($data[$j]['columnname']=="time_end"){
									$time_end=$data[$j]['value'];
								}
							}
						}
					}
					if($planned!=""){
						$date_start=$date_start." ".$time_start;
						$due_date=$due_date." ".$time_end;
						//echo $date_start."<br>";
						//echo $due_date."<br>";
						$data1=check_date_calendar($date_start,$due_date);
						if(count($data1)>0 and $data1!="1"){
							return $data1;	
						}
					}
					
					$table1="aicrm_crmentity";
					$table2="aicrm_activity";
					$table3="aicrm_activitycf";
					$table4="aicrm_seactivityrel";					
					if($action=="add"){
						$sql=" select (id+1) as id from aicrm_crmentity_seq ";
						$id_seq = $generate->process($sql,"all");	
						$cid=$id_seq[0]['id'];
						for($i=0;$i<count($data);$i++){
							if($data[$i]['columnname']=="description"){
								if($data[$i]['value']!=""){
									$description=$data[$i]['value'];
								}else{
									$description="";
								}
							}
						}
						//aicrm_crmentity
						$sql = "insert into  aicrm_crmentity  (crmid,smcreatorid,smownerid,setype,createdtime,modifiedtime,version,presence,deleted,description) values ('".$cid."','".$user_id."','".$user_id."','Calendar','".$date."','".$date."','0','1','0','".$description."');";
						//echo $sql."<br>";exit;
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						
						//aicrm_crmentity_seq
						$sql = "update  aicrm_crmentity_seq set id='".$cid."';";
						//echo $sql."<br>";
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						
						//aicrm_activity
						array_push($column2, "activityid");
						array_push($value2, "'".$cid."'");	


						for($i=0;$i<count($data);$i++){
							if($table2==$data[$i]['tablename']){
								if(trim($data[$i]['columnname'])=="visibility"){
									if($data[$i]['value']=="1"){
										array_push($column2, $data[$i]['columnname']);
										array_push($value2, "'Public'");
									}else{
										array_push($column2, $data[$i]['columnname']);
										array_push($value2, "'Private'");
									}
								}else{
									array_push($column2, $data[$i]['columnname']);
									array_push($value2, "'".$data[$i]['value']."'");
								}
							}
						}
						$sql = "insert into $table2(". implode(",",$column2) .") values(". implode(",",$value2) .");";
						//echo $sql."<br>";
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						
						//aicrm_activitycf
						array_push($column3, "activityid");
						array_push($value3, "'".$cid."'");	
						for($i=0;$i<count($data);$i++){
							if($table3==$data[$i]['tablename']){
								array_push($column3, $data[$i]['columnname']);
								array_push($value3, "'".$data[$i]['value']."'");
							}
						}
						$sql = "insert into $table3(". implode(",",$column3) .") values(". implode(",",$value3) .");";
						//echo $sql."<br>";
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						
						//aicrm_seactivityrel 
						array_push($column4, "activityid");
						array_push($value4, "'".$cid."'");
						$chk_tab4=0;	
						for($i=0;$i<count($data);$i++){
							if($table4==$data[$i]['tablename']){
								array_push($column4, $data[$i]['columnname']);
								array_push($value4, "'".$data[$i]['value']."'");
								if($data[$i]['value']=="" || $data[$i]['value']=="0"){
									$chk_tab4=1;
								}
							}
						}
						if($chk_tab4=="0"){
							$sql = "insert into $table4(". implode(",",$column4) .") values(". implode(",",$value4) .");";
							//echo $sql."<br>";exit;
							/**/
							if(!$generate->query($sql)){
								$chk=1;
							}
						}
						if($chk==1){
							$data = array(
								'jsonrpc' => '2.0', //version
								'id' =>$json_id, //String/Number
								'result' =>array(
									'message' => 'Can not Save'
								)
							);
						}else{
							$data = array(
								'jsonrpc' => '2.0', //version
								'id' =>$json_id, //String/Number
								'result' =>array(
									//'crmid' => $cid,
									'message' => 'Save Complete'
								)
							);
						}
					}else{
						//aicrm_crmentity
						//aicrm_crmentity
						$sql="update $table1 set ";
						$con="";
						for($i=0;$i<count($data);$i++){
							if($table1==$data[$i]['tablename']){
								if($con==""){
									$con=$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}else{
									$con.=",".$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}
							}
						}
						if($con==""){
							$sql.=$con." smownerid='".$user_id."',
							modifiedtime='".$date."'
							where crmid='".$crmid."'
						";
						}else{
							$sql.=$con." , smownerid='".$user_id."',
							modifiedtime='".$date."'
							where crmid='".$crmid."'
						";
						}
						//echo $sql."<br>";
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						
						//aicrm_activity
						$sql="update $table2 set ";
						$con="";
						for($i=0;$i<count($data);$i++){
							if($table2==$data[$i]['tablename']){
								if(trim($data[$i]['columnname'])=="visibility"){
									if($data[$i]['value']=="1"){
										array_push($column2, $data[$i]['columnname']);
										array_push($value2, "'Public'");
									}else{
										array_push($column2, $data[$i]['columnname']);
										array_push($value2, "'Private'");
									}
								}else{
									array_push($column2, $data[$i]['columnname']);
									array_push($value2, "'".$data[$i]['value']."'");
								}
							}
							if($table2==$data[$i]['tablename']){
								if($con==""){
									if(trim($data[$i]['columnname'])=="visibility"){
										if($data[$i]['value']=="1"){
											$con=$data[$i]['columnname']."= 'Public' ";
										}else{
											$con=$data[$i]['columnname']."= 'Private' ";
										}
									}else{
										$con=$data[$i]['columnname']."= '".$data[$i]['value']."' ";
									}
								}else{
									if(trim($data[$i]['columnname'])=="visibility"){
										if($data[$i]['value']=="1"){
											$con.=",".$data[$i]['columnname']."= 'Public' ";
										}else{
											$con.=",".$data[$i]['columnname']."= 'Private' ";
										}
									}else{
										$con.=",".$data[$i]['columnname']."= '".$data[$i]['value']."' ";
									}
								}
							}
						}
						$sql.=$con." where activityid='".$crmid."';";
						//echo $sql."<br>";
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						
						//aicrm_activitycf
						$sql="update $table3 set ";
						$con="";
						$con1="";
						for($i=0;$i<count($data);$i++){
							if($table3==$data[$i]['tablename']){
								if($con==""){
									$con=$data[$i]['columnname']."= '".$data[$i]['value']."' ";
									$con1=$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}else{
									$con.=",".$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}
							}
						}
						$sql.=$con." where activityid='".$crmid."';";
						//echo $sql."<br>";exit;
						/**/
						if(!$generate->query($sql)){
							$chk=1;
						}
						
						//aicrm_seactivityrel
						$sql="update $table4 set ";
						$con="";
						for($i=0;$i<count($data);$i++){
							if($table4==$data[$i]['tablename']){
								if($con==""){
									$con=$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}else{
									$con.=",".$data[$i]['columnname']."= '".$data[$i]['value']."' ";
								}
							}
						}
						$sql.=$con." where activityid='".$crmid."';";
						//echo $con;
						$sql_chk="select * from $table4 where 1 and activityid='".$crmid."'";
						//echo $sql_chk;
						$data_chk = $generate->process($sql_chk,"all");	
						if(count($data_chk)>0){
							//echo $sql."<br>";exit;
							/**/
							if(!$generate->query($sql)){
								$chk=1;
							}
						}else{
							array_push($column4, "activityid");
							array_push($value4, "'".$crmid."'");
							$chk_tab4=0;	
							for($i=0;$i<count($data);$i++){
								if($table4==$data[$i]['tablename']){
									array_push($column4, $data[$i]['columnname']);
									array_push($value4, "'".$data[$i]['value']."'");
									if($data[$i]['value']=="" || $data[$i]['value']=="0"){
										$chk_tab4=1;
									}
								}
							}
							if($chk_tab4=="0"){
								$sql = "insert into $table4(". implode(",",$column4) .") values(". implode(",",$value4) .");";
								//echo $sql."<br>";exit;
								/**/
								if(!$generate->query($sql)){
									$chk=1;
								}
							}
						}
						
						if($chk==1){
							$data = array(
								'jsonrpc' => '2.0', //version
								'id' =>$json_id, //String/Number
								'result' =>array(
									'message' => 'Can not Update'
								)
							);
						}else{
							$data = array(
								'jsonrpc' => '2.0', //version
								'id' =>$json_id, //String/Number
								'result' =>array(
									'message' => 'Update Complete'
								)
							);
						}	
					}
				}
			}else{
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' =>$json_id, //String/Number
					'result' =>array(
						'error_message' => 'Data Not found.'
					)
				);
			}
			return $data;	
		}
	}
?>