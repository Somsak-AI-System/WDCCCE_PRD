<?
	function get_search($json_id,$user_id,$module,$method,$where,$offset,$rows){
/*		echo $json_id."</br>";
		echo $user_id."</br>";
		echo $module."</br>";
		echo $method."</br>";
		echo $where."</br>";
		echo $offset."</br>";
		echo $rows."</br>";*/
		
		global $generate;
		if($module=="Job"){
			
			$module="Call Detail";	
		
		}
		
		if($offset==""){
			$offset=0;
		}
		if($rows==""){
			$rows=10;
		}		
		if($module=="Account"){
			if($method=="get_all"){
				if($where!=""){
					$con=" and (
					account_no like '%".$where."%'
					or accountname like '%".$where."%'
					)";
				}
				//get_total
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;
				$data= $generate->process($sql,"all");
				$count=count($data);
				//print_r($data);
				//echo $count;
				//get_data_limit
				//$sql = getListQuery($module,$user_id,'');
				$sql.=" limit ".$offset.",".$rows."";
				//echo $sql;exit;
				$data= $generate->process($sql,"all");
				$data1=array();
				
				for($i=0;$i<count($data);$i++){
					$data1[$i]['accountid']=$data[$i]['accountid'];
					$data1[$i]['account_no']=$data[$i]['account_no'];
					$data1[$i]['accountname']=$data[$i]['accountname'];
					
					$phone=split(",",str_replace("-","",$data[$i]['cf_1144']));
					
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
					/*if(count($phone)>1){
						$data1[$i]['phone']=$phone[0];
					}else{
						$data1[$i]['phone']=$phone[0];
					}*/
					//$data1[$i]['phone']=str_replace("-","",$data[$i]['cf_1144']);
				}
				if(count($data1)>0){
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data1
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
			}else{
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' =>$json_id, //String/Number
					'error' =>array(
						'code'=>'-32601',//error code
						'message'=>'Method not found'//error message
					)
				);
			}			
		}else if($module=="Products"){
			if($method=="get_all"){
				if($where!=""){
					$con=" and (
					product_no like '%".$where."%'
					or productname like '%".$where."%'
					or productcategory like '%".$where."%'
					or cf_1147 like '%".$where."%'
					or unit_price like '%".$where."%'
					)";
				}
				//get_total
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;
				$data= $generate->process($sql,"all");
				$count=count($data);
				$sql.=" limit ".$offset.",".$rows."";
				$data= $generate->process($sql,"all");
				$data1=array();
				
				for($i=0;$i<count($data);$i++){
					$data1[$i]['productid']=$data[$i]['productid'];
					$data1[$i]['product_no']=$data[$i]['product_no'];
					$data1[$i]['productname']=$data[$i]['productname'];
					//$data1[$i]['model']=$data[$i]['model'];
					$data1[$i]['pro_cat']=$data[$i]['pro_cat'];
					$data1[$i]['pro_subcat']=$data[$i]['pro_subcat'];
					$data1[$i]['unit_price']=$data[$i]['unit_price'];
				}
				if(count($data1)>0){
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data1
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
			}else{
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' =>$json_id, //String/Number
					'error' =>array(
						'code'=>'-32601',//error code
						'message'=>'Method not found'//error message
					)
				);
			}			
		}else if($module=="Job"){
			if($method=="get_all"){
				if($where!=""){
					$con="
					and (
					servicerequest_no like '%".$where."%'
					or servicerequest_name like '%".$where."%'
					or cf_1262 like '%".$where."%'
					or cf_1225 like '%".$where."%'
					or cf_1355 like '%".$where."%'
					)
					";
				}
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;
				//$sql.=" order by aicrm_servicerequests.servicerequestid desc";
				//echo $sql;
				$data= $generate->process($sql,"all");
				$count=count($data);
				
				//$sql = getListQuery($module,$user_id,'');
				//$sql.=" order by aicrm_servicerequests.servicerequestid desc ";
				$sql.=" limit ".$offset.",".$rows."";
				/*$FileName = "sql.txt";
					$FileHandle = fopen($FileName, 'a+') or die("can't open file");
					fwrite($FileHandle,$module."=>".$method."<br>".$sql."\r\n");
					fclose($FileHandle);*/
				//echo $sql; 
				$data_all= $generate->process($sql,"all");
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
							if($data_field[$j]=="cf_1262" and ($data_value[$j]=="Incomplete Customer" || $data_value[$j]=="Incomplete Part" || $data_value[$j]=="Incomplete Service" || $data_value[$j]=="Incomplete Other")){
								$color="#FF0000";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="In Progress"){
								$color="#0099FF";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Assistant"){
								$color="#FF9900";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Complete"){
								$color="#00FF00";
							}
							$data_v2['status_color']=$color;	
							if($data_field[$j]=="crmid"){
								$id=$data_value[$j];
							}	
						}
						//print_r($data_v2);
						$data_v2['form_detail']=get_edit($json_id,$user_id,$module,"get_edit",$id);
						$data_v1[]=$data_v2;
						//$color="#9999FF";
					}
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data_v1
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
			}else if($method=="get_current"){
				if($where!=""){
					$con="
					and (
					servicerequest_no like '%".$where."%'
					or servicerequest_name like '%".$where."%'
					or cf_1262 like '%".$where."%'
					or cf_1225 like '%".$where."%'
					or cf_1355 like '%".$where."%'
					)
					";
				}
				$con.=" 
				and cf_1225='".date('Y-m-d')."'
				";
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;
				//echo $sql;
				$data= $generate->process($sql,"all");
				$count=count($data);
				
				//$sql = getListQuery($module,$user_id,'');
				$sql.=" limit ".$offset.",".$rows."";
				//$sql.=" order by ";
				//echo $sql;
				$data_all= $generate->process($sql,"all");
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
							if($data_field[$j]=="cf_1262" and $data_value[$j]=="Planning"){
								$color="#FF0000";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="In Progress"){
								$color="#0099FF";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Wait For Response"){
								$color="#FF9900";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Closed"){
								$color="#00FF00";
							}
							$data_v2['status_color']=$color;	
							if($data_field[$j]=="crmid"){
								$id=$data_value[$j];
							}	
						}
						$data_v2['form_detail']=get_edit($json_id,$user_id,$module,"get_edit",$id);
						$data_v1[]=$data_v2;
						$color="#9999FF";
					}
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data_v1
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
			}else if($method=="get_late"){
				if($where!=""){
					$con="
					and (
					servicerequest_no like '%".$where."%'
					or servicerequest_name like '%".$where."%'
					or cf_1262 like '%".$where."%'
					or cf_1225 like '%".$where."%'
					or cf_1355 like '%".$where."%'
					)
					";
				}
				$con.=" 
				and cf_1225 < '".date('Y-m-d')."'
				";
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;
				$data= $generate->process($sql,"all");
				$count=count($data);
				
				//$sql = getListQuery($module,$user_id,'');
				$sql.=" limit ".$offset.",".$rows."";
				
				$data_all= $generate->process($sql,"all");
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
							if($data_field[$j]=="cf_1262" and $data_value[$j]=="Planning"){
								$color="#FF0000";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="In Progress"){
								$color="#0099FF";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Wait For Response"){
								$color="#FF9900";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Closed"){
								$color="#00FF00";
							}
							$data_v2['status_color']=$color;	
							if($data_field[$j]=="crmid"){
								$id=$data_value[$j];
							}	
						}
						$data_v2['form_detail']=get_edit($json_id,$user_id,$module,"get_edit",$id);
						$data_v1[]=$data_v2;
						$color="#9999FF";
					}
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data_v1
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
			}else if($method=="get_intelligent"){
				
					
				/*$con=" and cf_1225='".date('Y-m-d')."' 
				and cf_1262 ='Planning'
				";
				$con=' and aicrm_servicerequests.servicerequestid=91861 ';*/
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;
				
				//fix
				
				//echo $sql;
				$data= $generate->process($sql,"all");
				$count=count($data);
				
				//$sql = getListQuery($module,$user_id,'');
				//$sql.=" order by cf_1355";
				$sql.=" limit 1";
				//echo $sql;

				$data_all= $generate->process($sql,"all");
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
							if($data_field[$j]=="cf_1262" and $data_value[$j]=="Planning"){
								$color="#FF0000";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="In Progress"){
								$color="#0099FF";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Wait For Response"){
								$color="#FF9900";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Closed"){
								$color="#00FF00";
							}
							$data_v2['status_color']=$color;	
							if($data_field[$j]=="crmid"){
								$id=$data_value[$j];
							}	
						}
						$data_v2['form_detail']=get_edit($json_id,$user_id,$module,"get_edit",$id);
						$data_v1[]=$data_v2;
						$color="#9999FF";
					}
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data_v1
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
			}else{
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' =>$json_id, //String/Number
					'error' =>array(
						'code'=>'-32601',//error code
						'message'=>'Method not found'//error message
					)
				);
			}	
		}else if($module=="Calendar"){
			if($method=="get_all"){
				if($where!=""){
					$con="
					and (
					activitytype like '%".$where."%'
					or aicrm_activity.subject like '%".$where."%'
					or aicrm_crmentity.description like '%".$where."%'
					or date_start like '%".$where."%'
					or due_date like '%".$where."%'
					)
					";
				}
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;
				//echo $sql;exit;
				$data= $generate->process($sql,"all");
				$count=count($data);
				
				//$sql = getListQuery($module,$user_id,'');
				
				$data_all= $generate->process($sql,"all");
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
							if($module=="Job"){
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
						}
						$data_v2['form_detail']=get_edit($json_id,$user_id,$module,"get_edit",$id);
						$data_v1[]=$data_v2;
						$color="#9999FF";
					}
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data_v1
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
			}else if($method=="get_today"){
				if($where!=""){
					$con="
					and (
					activitytype like '%".$where."%'
					or subject like '%".$where."%'
					or aicrm_crmentity.description like '%".$where."%'
					or date_start like '%".$where."%'
					or due_date like '%".$where."%'
					)
					";
				}
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;
				//echo $sql;exit;
				$data= $generate->process($sql,"all");
				$count=count($data);
				
				//$sql = getListQuery($module,$user_id,'');
				$sql.=" limit ".$offset.",".$rows."";
				//echo $sql;

				$data_all= $generate->process($sql,"all");
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
							if($module=="Job"){
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
						}
						$data_v2['form_detail']=get_edit($json_id,$user_id,$module,"get_edit",$id);
						$data_v1[]=$data_v2;
						$color="#9999FF";
					}
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data_v1
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
			}else if($method=="get_intelligent"){
				$con=" and date_start='".date('Y-m-d')."'  ";
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;
				//echo $sql;exit;
				$data= $generate->process($sql,"all");
				$count=count($data);
				
				//$sql = getListQuery($module,$user_id,'');
				$sql.=" order by due_date";
				$sql.=" limit 1";

				$data_all= $generate->process($sql,"all");
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
							if($data_field[$j]=="cf_1262" and $data_value[$j]=="Planning"){
								$color="#FF0000";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="In Progress"){
								$color="#0099FF";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Wait For Response"){
								$color="#FF9900";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Closed"){
								$color="#00FF00";
							}
							$data_v2['status_color']=$color;	
							if($data_field[$j]=="crmid"){
								$id=$data_value[$j];
							}	
						}
						$data_v2['form_detail']=get_edit($json_id,$user_id,$module,"get_edit",$id);
						$data_v1[]=$data_v2;
						$color="#9999FF";
					}
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data_v1
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
			}else{
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' =>$json_id, //String/Number
					'error' =>array(
						'code'=>'-32601',//error code
						'message'=>'Method not found'//error message
					)
				);
			}
		}else if($module=="Call Detail"){
			
			/*$FileName = "sql.txt";
					$FileHandle = fopen($FileName, 'a+') or die("can't open file");
					fwrite($FileHandle,$module."=>".$method."<br>".$sql."\r\n");
					fclose($FileHandle);*/
			if($method=="get_all"){
				if($where!=""){
					$con="
					and (
					jobdetail_no like '%".$where."%'
					or jobdetail_name like '%".$where."%'
					or ticket_no like '%".$where."%'
					or cf_1224 like '%".$where."%'
					or cf_1211 like '%".$where."%'
					or cf_1213 like '%".$where."%'
					)
					";
				}
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;
				//$sql.=" order by aicrm_servicerequests.servicerequestid desc";
				//echo $sql;
				$data= $generate->process($sql,"all");
				$count=count($data);
				
				//$sql = getListQuery($module,$user_id,'');
				$sql.=" order by aicrm_jobdetails.jobdetailid desc ";
				$sql.=" limit ".$offset.",".$rows."";
				
					
				$data_all= $generate->process($sql,"all");
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
							if($data_field[$j]=="cf_1262" and ($data_value[$j]=="Incomplete Customer" || $data_value[$j]=="Incomplete Part" || $data_value[$j]=="Incomplete Service" || $data_value[$j]=="Incomplete Other")){
								$color="#FF0000";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="In Progress"){
								$color="#0099FF";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Assistant"){
								$color="#FF9900";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Complete"){
								$color="#00FF00";
							}
							$data_v2['status_color']=$color;	
							if($data_field[$j]=="crmid"){
								$id=$data_value[$j];
							}	
						}
						//print_r($data_v2);
						$data_v2['form_detail']=get_edit($json_id,$user_id,$module,"get_edit",$id);
						$data_v1[]=$data_v2;
						//$color="#9999FF";
					}
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data_v1
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
			}else if($method=="get_current"){
				if($where!=""){
					$con="
					and (
					jobdetail_no like '%".$where."%'
					or jobdetail_name like '%".$where."%'
					or ticket_no like '%".$where."%'
					or cf_1224 like '%".$where."%'
					or cf_1211 like '%".$where."%'
					or cf_1213 like '%".$where."%'
					)
					";
				}
				$con.=" 
				and cf_1211='".date('Y-m-d')."'
				";
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;
				//echo $sql;
				$data= $generate->process($sql,"all");
				$count=count($data);
				
				//$sql = getListQuery($module,$user_id,'');
				$sql.=" limit ".$offset.",".$rows."";

				//$sql.=" order by ";
				//echo $sql;
				$data_all= $generate->process($sql,"all");
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
							if($data_field[$j]=="cf_1262" and $data_value[$j]=="Planning"){
								$color="#FF0000";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="In Progress"){
								$color="#0099FF";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Wait For Response"){
								$color="#FF9900";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Closed"){
								$color="#00FF00";
							}
							$data_v2['status_color']=$color;	
							if($data_field[$j]=="crmid"){
								$id=$data_value[$j];
							}	
						}
						$data_v2['form_detail']=get_edit($json_id,$user_id,$module,"get_edit",$id);
						$data_v1[]=$data_v2;
						$color="#9999FF";
					}
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data_v1
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
			}else if($method=="get_late"){
				if($where!=""){
					$con="
					and (
					jobdetail_no like '%".$where."%'
					or jobdetail_name like '%".$where."%'
					or ticket_no like '%".$where."%'
					or cf_1224 like '%".$where."%'
					or cf_1211 like '%".$where."%'
					or cf_1213 like '%".$where."%'
					)
					";
				}
				$con.=" 
				and cf_1211 < '".date('Y-m-d')."'
				";
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;
				$data= $generate->process($sql,"all");
				$count=count($data);
				
				//$sql = getListQuery($module,$user_id,'');
				$sql.=" limit ".$offset.",".$rows."";

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
							if($data_field[$j]=="cf_1262" and $data_value[$j]=="Planning"){
								$color="#FF0000";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="In Progress"){
								$color="#0099FF";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Wait For Response"){
								$color="#FF9900";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Closed"){
								$color="#00FF00";
							}
							$data_v2['status_color']=$color;	
							if($data_field[$j]=="crmid"){
								$id=$data_value[$j];
							}	
						}
						$data_v2['form_detail']=get_edit($json_id,$user_id,$module,"get_edit",$id);
						$data_v1[]=$data_v2;
						$color="#9999FF";
					}
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data_v1
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
			}else if($method=="get_intelligent"){
				
					
				/*$con=" and cf_1225='".date('Y-m-d')."' 
				and cf_1262 ='Planning'
				";
				$con=' and aicrm_servicerequests.servicerequestid=91861 ';*/
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;

				//fix
				
				//echo $sql;
				$data= $generate->process($sql,"all");
				$count=count($data);
				
				//$sql = getListQuery($module,$user_id,'');
				//$sql.=" order by cf_1355";
				$sql.=" limit 1";
				//echo $sql;

				$data_all= $generate->process($sql,"all");
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
							if($data_field[$j]=="cf_1262" and $data_value[$j]=="Planning"){
								$color="#FF0000";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="In Progress"){
								$color="#0099FF";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Wait For Response"){
								$color="#FF9900";
							}else if($data_field[$j]=="cf_1262" and $data_value[$j]=="Closed"){
								$color="#00FF00";
							}
							$data_v2['status_color']=$color;	
							if($data_field[$j]=="crmid"){
								$id=$data_value[$j];
							}	
						}
						$data_v2['form_detail']=get_edit($json_id,$user_id,$module,"get_edit",$id);
						$data_v1[]=$data_v2;
						$color="#9999FF";
					}
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data_v1
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
			}else{
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' =>$json_id, //String/Number
					'error' =>array(
						'code'=>'-32601',//error code
						'message'=>'Method not found'//error message
					)
				);
			}	
		}else if($module=="HelpDesk"){//echo "rrrr";
			if($method=="get_all"){
				if($where!=""){
					$con=" and (
					ticket_no like '%".$where."%'
					or model_name like '%".$where."%'
					or productname like '%".$where."%'
					or cf_1231 like '%".$where."%'
					or cf_1249 like '%".$where."%'
					)";
				}
				//get_total
				$sql = getListQuery($module,$user_id,'');
				$sql.=$con;
				$data= $generate->process($sql,"all");
				$count=count($data);

				$sql.=" limit ".$offset.",".$rows."";
				//echo $sql;exit;
				$data= $generate->process($sql,"all");
				$data1=array();
				
				for($i=0;$i<count($data);$i++){
					$data_phone[0]['phone_main']="";
					$data_phone[0]['phone_to']="";
					$data1[$i]['ticketid']=$data[$i]['ticketid'];
					$data1[$i]['phone']=$data_phone[0];
					$data1[$i]['ticket_no']=$data[$i]['ticket_name'];
					$data1[$i]['ticketname']=$data[$i]['ticket_no'];
				}
				if(count($data1)>0){
					$data = array(
						'jsonrpc' => '2.0', //version
						'id' => $json_id, //String/Number
						'result' =>array(
							'total'=>$count,
							'data'=>$data1
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
			}else{
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' =>$json_id, //String/Number
					'error' =>array(
						'code'=>'-32601',//error code
						'message'=>'Method not found'//error message
					)
				);
			}			
		}
		return $data;
	}
?>