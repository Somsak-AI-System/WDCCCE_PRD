<?
	function get_search_calendar($json_id,$user_id,$module,$method,$where,$search_date,$offset,$rows){//echo "555";
		global $generate;
		if($offset==""){
			$offset=0;
		}
		if($rows==""){
			$rows=10;
		}		
		if($module=="Calendar"){
			if($method=="get_today"){
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
				//(date_start='".date('Y-m-d')."' or date_start='".(date('Y')+543)."-".date('-m-d')."')
				$con.=" and (date_start='".date('Y-m-d')."' or date_start='".(date('Y')+543)."-".date('m-d')."') ";
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
			}else if($method=="get_day"){
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
				$con.=" and (date_start='".date('Y-m-d',strtotime($search_date))."' or date_start='".(date('Y')+543)."-".date('m-d',strtotime($search_date))."')";
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
			}else if($method=="get_month_new"){
				$M=date('m',strtotime($search_date));
				$Y=date('Y',strtotime($search_date));
				//echo $M." ".$Y;
				$num = cal_days_in_month(CAL_GREGORIAN, $M, $Y); // 31
				//echo "There was $num days in August $Y";
				
				
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
				$data1=array();
				$data_day=array();
				for($i=0;$i<$num;$i++){
					$D=$i+1;
					$search_date=$D."-".$M."-".$Y;
					$con=" and left(date_start,10)='".date('Y-m-d',strtotime($search_date))."' ";
					$sql = getListQuery($module,$user_id,'');
					$sql.=$con;
					//echo $sql."<br>";
					$data= $generate->process($sql,"all");
					$count=count($data);
					//echo $count."<br>";
					$data_day = array(
						'date' => $D, //version
						'total' =>$count //String/Number
					);
					$data1[]=$data_day;
				}
				$data = array(
					'jsonrpc' => '2.0', //version
					'id' => $json_id, //String/Number
					'result' =>$data1
				);
			}else if($method=="get_month"){
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
				$con.=" and MONTH( date_start )='".date('m',strtotime($search_date))."' ";
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