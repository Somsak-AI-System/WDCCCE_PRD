<?
	function update_Travel_Back_Date($module,$crmid){
		global $generate;
		$sql_from=Get_Query($module);
		$sql="select * ";
		$sql.=$sql_from;
		$sql.=" and aicrm_crmentity.crmid='".$crmid."'";
		
		$data_c = $generate->process($sql,"all");	
		if(count($data_c)>0){
			$cf_1339=	$data_c[0]["cf_1339"];
			$cf_1316=	$data_c[0]["cf_1316"];
			$cf_1328=	$data_c[0]["cf_1328"];
			$cf_1217=	$data_c[0]["cf_1217"];
			$con="";
			if($cf_1328=="" and $cf_1339!=""){
				$con=" cf_1328='".$cf_1339."'";	
			}
			if($cf_1217=="" and $cf_1316!=""){
				if($con!=""){
					$con.=" ,cf_1217='".$cf_1316."'";	
				}else{
					$con=" cf_1217='".$cf_1316."'";	
				}
			}
			$sql="update aicrm_jobdetailscf set ".$con ." where jobdetailid='".$crmid."'";
			/*$FileName = "sql.txt";
			$FileHandle = fopen($FileName, 'a+') or die("can't open file");
			fwrite($FileHandle,$sql."\r\n");
			fclose($FileHandle);*/
			if(!$generate->query($sql)){
				$chk=1;
			}
		}
	}
	
	function update_sla($crmid){
		global $generate;
		//update=====================================
		$sql=" select parent_id from aicrm_jobdetails where jobdetailid='".$crmid."'";
		$data = $generate->process($sql,"all");
		$ticketid=$data[0]['parent_id'];
		$sql="
		SELECT aicrm_troubletickets.ticketid,cf_1234,cf_1235,cf_1336,cf_1337,aicrm_usersJOB1.id as tick_user
		FROM aicrm_troubletickets 
		INNER JOIN aicrm_crmentity  ON aicrm_troubletickets.ticketid = aicrm_crmentity.crmid
		INNER JOIN aicrm_ticketcf  ON aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
		left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentity.smownerid = aicrm_usersJOB1.id
		WHERE aicrm_crmentity.deleted =0
		and aicrm_crmentity.crmid='".$ticketid."'
		";
		//echo $sql;exit;
		$data = $generate->process($sql,"all");
		if(count($data)>0){
			for($ii=0;$ii<=count($data);$ii++){
				$case_date=$data[$ii]['cf_1336']." ".$data[$ii]['cf_1337'];
				$case_date1=$data[$ii]['cf_1337'];
				$ticketid=$data[$ii]['ticketid'];
				$tick_user=$data[$ii]['tick_user'];
				//หาวันที่เริ่ม
				$sql="
				select
				cf_1211 as start_date 
				from aicrm_jobdetails 
				left join aicrm_jobdetailscf on aicrm_jobdetailscf.jobdetailid=aicrm_jobdetails.jobdetailid
				left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_jobdetails.jobdetailid
				left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
				where 1
				and aicrm_crmentityJOB1.deleted =0
				and aicrm_jobdetails.parent_id='".$ticketid."'
				order by cf_1211 ASC  limit 1
				";
				//echo $sql."<br>";
				$data_start = $generate->process($sql,"all");
				if(count($data_start)>0){
					$start_date=$data_start[0]['start_date'];
				}else{
					$start_date="";
				}
				//หาเวลาที่เริ่ม
				$sql="
				select
				cf_1213 as start_time
				
				from aicrm_jobdetails 
				left join aicrm_jobdetailscf on aicrm_jobdetailscf.jobdetailid=aicrm_jobdetails.jobdetailid
				left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_jobdetails.jobdetailid
				left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
				where 1
				and aicrm_crmentityJOB1.deleted =0
				and aicrm_jobdetails.parent_id='".$ticketid."'
				order by cf_1213 asc   limit 1
				";
				$data_start = $generate->process($sql,"all");
				if(count($data_start)>0){
					$start_time=$data_start[0]['start_time'];
				}else{
					$start_time="";
				}
				//หาวันที่สิ้นสุด
				$sql="
				select
				cf_1328 as endt_date,
				cf_1339 as travel_date
				from aicrm_jobdetails 
				left join aicrm_jobdetailscf on aicrm_jobdetailscf.jobdetailid=aicrm_jobdetails.jobdetailid
				left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_jobdetails.jobdetailid
				left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
				where 1
				and aicrm_crmentityJOB1.deleted =0
				and aicrm_jobdetails.parent_id='".$ticketid."'
				order by cf_1328 DESC   limit 1
				";
				//echo $sql;
				$data_start = $generate->process($sql,"all");
				if(count($data_start)>0){
					$endt_date=$data_start[0]['endt_date'];
					$travel_date=$data_start[0]['travel_date'];
				}else{
					$endt_date="";
					$travel_date="";
				}
				//หาเวลาที่สิ้นสุด
				$sql="
				select
				cf_1217 as endt_time,
				cf_1316 as travel_time
				from aicrm_jobdetails 
				left join aicrm_jobdetailscf on aicrm_jobdetailscf.jobdetailid=aicrm_jobdetails.jobdetailid
				left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_jobdetails.jobdetailid
				left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
				where 1
				and aicrm_crmentityJOB1.deleted =0
				and aicrm_jobdetails.parent_id='".$ticketid."'
				order by cf_1217 desc    limit 1
				";
				//echo $sql;
				$data_start = $generate->process($sql,"all");
				if(count($data_start)>0){
					$endt_time=$data_start[0]['endt_time'];
					$travel_time=$data_start[0]['travel_time'];
				}else{
					$endt_time="";
					$travel_time="";
				}
				$start_date1=$start_date." ".$start_time;
				$endt_date1=$endt_date." ".$endt_time;
				$travel_time1=$travel_date." ".$travel_time;
				//echo $start_date.",".$endt_date."<br>";
				if($start_date=="" || $endt_date==""){
					$Arrive=0;
				}else{
					$t1= strtotime($start_date1);
					$t2= strtotime($endt_date1);
					$Arrive=($t2 - $t1)/60;
				}
				$Arrive1=($Arrive%60);
				$Arrive2=($Arrive-($Arrive%60))/60;
				$Arrive=$Arrive2.":".$Arrive1;

				$sql="update aicrm_ticketcf set cf_1264='".$start_date1."', cf_1266='".$travel_time1."', cf_1265='".$Arrive."' where ticketid='".$ticketid."' ";
				$generate->query($sql);		
	//new SLA 28-5-2014=====================================
				//หาวันที่สิ้นสุด
				$sql="
				select
				cf_1328 as travel_date,
				cf_1339 as endt_date
				from aicrm_jobdetails 
				left join aicrm_jobdetailscf on aicrm_jobdetailscf.jobdetailid=aicrm_jobdetails.jobdetailid
				left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_jobdetails.jobdetailid
				left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
				where 1
				and aicrm_crmentityJOB1.deleted =0
				and aicrm_jobdetails.parent_id='".$ticketid."'
				and cf_1212 in(10,12,13,16)
				order by aicrm_jobdetailscf.cf_1211 DESC   limit 1
				";
				//echo $sql;exit;
				$data_start = $generate->process($sql,"all");
				if(count($data_start)>0){
					$endt_date_N=$data_start[0]['endt_date'];
					$travel_date_N=$data_start[0]['travel_date'];
				}else{
					$endt_date_N="";
					$travel_date_N="";
				}
				//หาเวลาที่สิ้นสุด
				$sql="
				select cf_1328,cf_1217,
				cf_1217 as travel_time,
				cf_1316 as endt_time
				from aicrm_jobdetails 
				left join aicrm_jobdetailscf on aicrm_jobdetailscf.jobdetailid=aicrm_jobdetails.jobdetailid
				left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_jobdetails.jobdetailid
				left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
				where 1
				and aicrm_crmentityJOB1.deleted =0
				and aicrm_jobdetails.parent_id='".$ticketid."'
				and cf_1212 in(10,12,13,16)
				ORDER BY cf_1328 DESC , cf_1316 DESC    limit 1
				";
				//echo $sql;exit;
				$data_start = $generate->process($sql,"all");
				if(count($data_start)>0){
					$endt_time_N=$data_start[0]['endt_time'];
					$travel_time_N=$data_start[0]['travel_time'];
				}else{
					$endt_time_N="";
					$travel_time_N="";
				}
				$start_date1_N=$start_date." ".$start_time;
				$endt_date1_N=$endt_date_N." ".$endt_time_N;
				$travel_time1_N=$travel_date_N." ".$travel_time_N;
				
				$t1= strtotime($case_date);
				$t2= strtotime($endt_date1_N);		
				$SLA=($t2 - $t1)/60;
	
				//หา  Appointment date
				$sql="
				select
				aicrm_jobdetails.jobdetailid
				,cf_1463 as appointment_date 
				,cf_1464 as appointment_time
				,cf_1339 as travel_date
				,cf_1316 as travel_time
				from aicrm_jobdetails 
				left join aicrm_jobdetailscf on aicrm_jobdetailscf.jobdetailid=aicrm_jobdetails.jobdetailid
				left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_jobdetails.jobdetailid
				left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
				where 1
				and aicrm_crmentityJOB1.deleted =0
				and aicrm_jobdetailscf.cf_1463!=''
				and aicrm_jobdetails.parent_id='".$ticketid."'
				and cf_1212 in(10,12,13)
	
				order by aicrm_jobdetails.jobdetailid
				";
				//echo $sql;exit;
				$data_start = $generate->process($sql,"all");
				if(count($data_start)>0){
					$SLA_N=0;
					for($i=0;$i<count($data_start);$i++){
						$st=$data_start[$i]['appointment_date']." ".$data_start[$i]['appointment_time'];
						$en=$data_start[$i]['travel_date']." ".$data_start[$i]['travel_time'];
						$t1= strtotime($en);
						$t2= strtotime($st);
						$SLA_N=$SLA_N+($t2 - $t1)/60;
					}
				}
				$SLA=$SLA-$SLA_N;
				$SLA1=($SLA%60);
				$SLA2=($SLA-($SLA%60))/60;
				if($SLA1>9){
					$SLA=$SLA2.":".$SLA1;	
				}else{
					$SLA=$SLA2.":0".$SLA1;	
				}
				if($SLA<0){
					$SLA=0;	
				}
				$sql="update aicrm_ticketcf set cf_1267='".$SLA."' where ticketid='".$ticketid."' ";
				$generate->query($sql);		
	//new SLA 28-5-2014=====================================		
			}
		}//end update case
	}
	
	function update_ot($crmid){
		global $generate;
		//update=====================================
		$sql=" select parent_id from aicrm_jobdetails where jobdetailid='".$crmid."'";
		
		$data = $generate->process($sql,"all");
		$ticketid=$data[0]['parent_id'];
		//update OT======================================================
		$sql="
		select
		cf_1211 as start_date ,cf_1328 as end_date,cf_1217 as endt_time,cf_1213 as start_time,cf_1224
		,cf_1218,cf_1222
		,cf_1221,cf_1223,cf_1225,cf_1323,cf_1324,cf_1325,cf_1326,cf_1327
		from aicrm_jobdetails 
		left join aicrm_jobdetailscf on aicrm_jobdetailscf.jobdetailid=aicrm_jobdetails.jobdetailid
		left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_jobdetails.jobdetailid
		left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
		where 1
		and aicrm_crmentityJOB1.deleted =0
		and aicrm_jobdetails.jobdetailid='".$crmid."'
		order by cf_1211 ASC  limit 1
		";
		$data_start = $generate->process($sql,"all");
		if(count($data_start)>0){
			$st_date=$data_start[0]['st_date'];
			$en_date=$data_start[0]['end_date'];
			$st_datetime=$data_start[0]['start_date']." ".$data_start[0]['start_time'];
			$ed_datetime=$data_start[0]['end_date']." ".$data_start[0]['endt_time'];
			$start_time=$data_start[0]['start_time'];
			$endt_time=$data_start[0]['endt_time'];
			$job_detail_status=$data_start[0]['cf_1224'];
			
			$Expense=$data_start[0]['cf_1218'];
			$TotalExpense=$data_start[0]['cf_1222'];
			
			$Tollway1=$data_start[0]['cf_1221'];
			$Tollway2=$data_start[0]['cf_1223'];
			$Tollway3=$data_start[0]['cf_1225'];
			$Tollway4=$data_start[0]['cf_1323'];
			$Tollway5=$data_start[0]['cf_1324'];
			$Tollway6=$data_start[0]['cf_1325'];
			$Tollway7=$data_start[0]['cf_1326'];
			$Tollway8=$data_start[0]['cf_1327'];
			
		}
		
		$t1_0830=strtotime($st_date." "."8:30:00");
		$t1_1730=strtotime($st_date." "."17:30:00");
		$t1_2400=strtotime($st_date." "."23:59:59");
		$t2_0000=strtotime($en_date." "."00:00:00");
		//echo $t1_0830," ".$t1_1730." ".$t1_2400;
		$t1= strtotime($st_datetime);
		$t2= strtotime($ed_datetime);
		$M=($t2 - $t1)/60;
		$t_chk=strtotime($en_date." ".$endt_time);
		$OT1=0;
		$OT2=0;
		$OT3=0;
		$OT4=0;
		//echo $st_datetime." ".$ed_datetime." ".$M."<br>";exit;
		
		for($i=0;$i<$M;$i++){
			if($i=="0"){
				$t_000000=strtotime(date('Y-m-d',strtotime($st_datetime))." "."00:00:00");
				$t_083000=strtotime(date('Y-m-d',strtotime($st_datetime))." "."08:30:00");
				$t_173000=strtotime(date('Y-m-d',strtotime($st_datetime))." "."17:30:00");
				$t_235959=strtotime(date('Y-m-d',strtotime($st_datetime))." "."23:59:59");
				$t_235959=$t_235959+1;
			
				$chk_h=check_holiday($st_datetime);
				$t2= strtotime($st_datetime);
				//echo $chk_h."==>".$st_datetime;exit;
				if($chk_h==0){//check วันหยุด
					if($t_chk>=$t2){
						if(($t2>=$t_000000) and ($t2<$t_083000)){
							//echo $date_time."<br>";
							$OT2=$OT2+1;
						}else if(($t2>=$t_083000) and ($t2<$t_173000)){
							//echo $date_time."<br>";
							$OT1=$OT1+1;
						}else if(($t2>=$t_173000) and ($t2<$t_235959)){
							//echo $date_time." ".$t2."<br>";
							$OT2=$OT2+1;
						}
					}
				}else{
					if($t_chk>=$t2){
						if(($t2>=$t_000000) and ($t2<$t_083000)){
							//echo $date_time."<br>";
							$OT4=$OT4+1;
						}else if(($t2>=$t_083000) and ($t2<$t_173000)){
							//echo $date_time."<br>";
							$OT3=$OT3+1;
						}else if(($t2>=$t_173000) and ($t2<$t_235959)){
							//echo $date_time."<br>";
							$OT4=$OT4+1;
						}
					}else{
						//echo $date_time."<br>";
					}
				}
				$date_time=(date("Y-m-d H:i:s",mktime(date('H',strtotime($st_datetime)),date('i',strtotime($st_datetime))+1,00,date('m',strtotime($st_datetime)),date('d',strtotime($st_datetime)),date('Y',strtotime($st_datetime)))));
				
			}else{
				$t_000000=strtotime(date('Y-m-d',strtotime($date_time))." "."00:00:00");
				$t_083000=strtotime(date('Y-m-d',strtotime($date_time))." "."08:30:00");
				$t_173000=strtotime(date('Y-m-d',strtotime($date_time))." "."17:30:00");
				$t_235959=strtotime(date('Y-m-d',strtotime($date_time))." "."23:59:59");
				$t_235959=$t_235959+1;			
				$chk_h=check_holiday($date_time);
				$t2= strtotime($date_time);
				if($chk_h==0){//check วันหยุด
					if($t_chk>=$t2){
						if(($t2>=$t_000000) and ($t2<$t_083000)){
							//echo $date_time."<br>";
							$OT2=$OT2+1;
						}else if(($t2>=$t_083000) and ($t2<$t_173000)){
							//echo $date_time."<br>";
							$OT1=$OT1+1;
						}else if(($t2>=$t_173000) and ($t2<$t_235959)){
							//echo $date_time." ".$t2."<br>";
							$OT2=$OT2+1;
						}
					}
				}else{
					if($t_chk>=$t2){
						if(($t2>=$t_000000) and ($t2<$t_083000)){
							//echo $date_time."<br>";
							$OT4=$OT4+1;
						}else if(($t2>=$t_083000) and ($t2<$t_173000)){
							//echo $date_time."<br>";
							$OT3=$OT3+1;
						}else if(($t2>=$t_173000) and ($t2<$t_235959)){
							//echo $date_time."<br>";
							$OT4=$OT4+1;
						}
					}
				}
				$date_time=(date("Y-m-d H:i:s",mktime(date('H',strtotime($date_time)),date('i',strtotime($date_time))+1,00,date('m',strtotime($date_time)),date('d',strtotime($date_time)),date('Y',strtotime($date_time)))));
			}
		}
		
		//echo $OT1." ".$OT2." ".$OT3." ".$OT4;
		$OT11=($OT1%60);
		$OT22=($OT1-($OT1%60))/60;
		$OT=$OT22.":".$OT11;
		if($OT11>9){
			if($OT22>8){
				$OT1=($OT22).":".$OT11;
			}else{
				$OT1=$OT22.":".$OT11;
			}
		}else if($OT11<=9){
			if($OT22>8){
				$OT1=($OT22).":0".$OT11;
			}else{
				$OT1=$OT22.":0".$OT11;
			}
		}else{
			if($OT22>8){
				$OT1=($OT22);
			}else{
				$OT1=$OT22;
			}
		}
		//echo $OT3;
		$OT11=($OT3%60);
		$OT22=($OT3-($OT3%60))/60;
		$OT=$OT22.":".$OT11;
		
		if($OT11>9){
			if($OT22>8){
				$OT3=($OT22).":".$OT11;
			}else{
				$OT3=$OT22.":".$OT11;
			}
			
		}else if($OT11<=9){
			if($OT22>8){
				$OT3=($OT22).":0".$OT11;
			}else{
				$OT3=$OT22.":0".$OT11;
			}
		}else{
			if($OT22>8){
				$OT3=($OT22);
			}else{
				$OT3=$OT22;
			}
		}
		//echo $OT3;
		$OT11=($OT2%60);
		$OT22=($OT2-($OT2%60))/60;
		$OT=$OT22.":".$OT11;
		if($OT11>9){
			$OT2=$OT22.":".$OT11;
		}else if($OT11<=9){
			$OT2=$OT22.":0".$OT11;
		}else{
			$OT2=$OT22;
		}
			
		$OT11=($OT4%60);
		$OT22=($OT4-($OT4%60))/60;
		$OT=$OT22.":".$OT11;
		if($OT11>9){
			$OT4=$OT22.":".$OT11;
		}else if($OT11<=9){
			$OT4=$OT22.":0".$OT11;
		}else{
			$OT4=$OT22;
		}
		//echo $OT1." ".$OT2." ".$OT3." ".$OT4;
		//exit;
	
	//ส่วน update===========================================================================================================================
		/*$Expense=$adb->query_result($data_start,0,'cf_1218');
		$TotalExpense=$adb->query_result($data_start,0,'cf_1222');*/
		
		$Tollway1_b=get_Tollway($Tollway1);
		$Tollway2_b=get_Tollway($Tollway2);
		$Tollway3_b=get_Tollway($Tollway3);
		$Tollway4_b=get_Tollway($Tollway4);
		$Tollway5_b=get_Tollway($Tollway5);
		$Tollway6_b=get_Tollway($Tollway6);
		$Tollway7_b=get_Tollway($Tollway7);
		$Tollway8_b=get_Tollway($Tollway8);
		
		$TotalExpense=$Expense+$Tollway1_b+$Tollway2_b+$Tollway3_b+$Tollway4_b+$Tollway5_b+$Tollway6_b+$Tollway7_b+$Tollway8_b;
		
		$sql="update aicrm_jobdetailscf set cf_1258='".$OT1."', cf_1259='".$OT2."', cf_1260='".$OT3."', cf_1261='".$OT4."' 
		,cf_1222='".$TotalExpense."'
		where jobdetailid='".$crmid."' ";
		//echo $sql;exit;
		/*$FileName = "sql.txt";
					$FileHandle = fopen($FileName, 'a+') or die("can't open file");
					fwrite($FileHandle,$sql."\r\n");
					fclose($FileHandle);*/
		$generate->query($sql);		
		
		//update call status=============
		$call_status="";
		//echo $job_detail_status;exit;
		if($job_detail_status=="Complete"){
			$call_status="2 - Completed";
		}else if($job_detail_status="Incomplete Customer" || $job_detail_status="Incomplete Part" || $job_detail_status="Incomplete Service" || $job_detail_status="Incomplete Other"){
			$call_status="3 - Incompleted";
		}
		$sql="
		select smownerid from aicrm_crmentity where crmid='".$crmid."'
		";
		$data_cc = $generate->process($sql,"all");
		if(count($data_cc)>0){
			$job_user_id=$data_cc[0]['smownerid'];
		}
		if($tick_user==$job_user_id){
			$sql="update aicrm_troubletickets set status='".$call_status."' where ticketid='".$ticketid."'";
			$generate->query($sql);		
		}
	}
	
	function update_Respons($crmid){
		global $generate;
		//update=====================================
		$sql=" select parent_id from aicrm_jobdetails where jobdetailid='".$crmid."'";
		$data = $generate->process($sql,"all");
		$ticketid=$data[0]['parent_id'];		
		
		$sql="
		SELECT aicrm_troubletickets.ticketid,cf_1234,cf_1235,cf_1336,cf_1337,aicrm_usersJOB1.id as tick_user
		FROM aicrm_troubletickets 
		INNER JOIN aicrm_crmentity  ON aicrm_troubletickets.ticketid = aicrm_crmentity.crmid
		INNER JOIN aicrm_ticketcf  ON aicrm_ticketcf.ticketid = aicrm_troubletickets.ticketid
		left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentity.smownerid = aicrm_usersJOB1.id
		WHERE aicrm_crmentity.deleted =0
		and aicrm_crmentity.crmid='".$ticketid."'
		";

		//echo $sql;exit;
		$data = $generate->process($sql,"all");
		$case_date=$data[0]['cf_1336']." ".$data[0]['cf_1337'];
				
		//คำนวณ Respons Time 
		$sql="
		select
		aicrm_jobdetails.jobdetailid
		,cf_1211 as start_date
		,cf_1213 as start_time
		,cf_1215 as arrived_time
		
		from aicrm_jobdetails 
		left join aicrm_jobdetailscf on aicrm_jobdetailscf.jobdetailid=aicrm_jobdetails.jobdetailid
		left JOIN aicrm_crmentity as aicrm_crmentityJOB1  ON aicrm_crmentityJOB1.crmid = aicrm_jobdetails.jobdetailid
		left JOIN aicrm_users as aicrm_usersJOB1  ON aicrm_crmentityJOB1.smownerid = aicrm_usersJOB1.id
		where 1
		and aicrm_crmentityJOB1.deleted =0
		and aicrm_jobdetails.parent_id='".$ticketid."'
		and cf_1215!=''
		/*and cf_1212 in(10,13)*/
		
		GROUP BY cf_1211   
		order by aicrm_jobdetails.jobdetailid asc
		limit 1
		";
		//echo $sql;exit;
		$data_start = $generate->process($sql,"all");
		$t1=0;
		$t2=0;		
		if(count($data_start)>0){
			$start_date=$data_start[0]['start_date'];
			$start_time=$data_start[0]['start_time'];
			$arrived_time=$data_start[0]['arrived_time'];

			$t1= strtotime($case_date);
			$t2= strtotime($start_date." ".$arrived_time);
			//echo $case_date." ".$start_date." ".$arrived_time;
		}
			/*$FileName = "sql.txt";
			$FileHandle = fopen($FileName, 'a+') or die("can't open file");
			fwrite($FileHandle,$case_date." ".$start_date." ".$arrived_time."\r\n");
			fclose($FileHandle);*/
		if($t2>$t1){
			$SLA=($t2 - $t1)/60;
			$SLA_chk=($t2 - $t1)/60;
			$SLA1=($SLA%60);
			$SLA2=($SLA-($SLA%60))/60;
			if($SLA1>9){
				$SLA=$SLA2.":".$SLA1;	
			}else{
				$SLA=$SLA2.":0".$SLA1;	
			}
		
			$sql="update aicrm_ticketcf set cf_1485='".$SLA."' where ticketid='".$ticketid."'";
			$generate->query($sql);	
		}else{
			$sql="update aicrm_ticketcf set cf_1485=0 where ticketid='".$ticketid."'";
			$generate->query($sql);	
		}
		//update relation====================================================
		$sql="
		selelct 
		*
		from aicrm_crmentityrel
		where 1
		and crmid='".$ticketid."'
		and relcrmid='".$focus->id."'
		";
		$data_start = $generate->process($sql,"all");
		if(count($data_start)>0){
		}else{
			$sql="insert into aicrm_crmentityrel (crmid,module,relcrmid,relmodule)values(
			'".$ticketid."'
			,'HelpDesk'
			,'".$crmid."'
			,'JobDetail'
			)
			";	
			$generate->query($sql);	
		}
		//echo  $SLA;exit;
	//ส่วน update===========================================================================================================================	
	}
	
	function Get_H($st_date,$en_date){
		//echo $st_date." ".$en_date;
		$t1= strtotime($st_date);
		$t2= strtotime($en_date);
		$c_date=($t2-$t1)/86400;
		//echo $t1." ".$t2;exit;
		return $c_date;
		//echo ($t2-$t1)/86400;
	}
	function get_Tollway($Tollway_name){
		global $generate;
		$sql="select toll_way_free from tbm_toll_way  where toll_way_name='".$Tollway_name."'";
		//echo $sql;
		$data = $generate->process($sql,"all");	
		//print_r($data_start);
		//$rows1 = $adb->num_rows($data_start);
		if(count($data)>0){
			$toll_way=$data[0]['toll_way_free'];
		}else{
			$toll_way=0;	
		}
		return $toll_way;
	}
	function check_holiday($st_date){
		global $generate;
		$sql="select holiday_date from tbm_holiday where holiday_date='".$st_date."' and holiday_status='Active' ";
		$data = $generate->process($sql,"all");	
		//print_r($data_start);
		//$rows1 = $adb->num_rows($data_start);
		if(count($data)>0){
			$chk_h=1;
		}else{
			$chk_h=0;	
		}
		//$thai_day_arr=array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");  
		//$thai_date_return=$thai_day_arr[date("l",$st_date)]; 
		if(date('l',strtotime($st_date))=="Sunday" || date('l',strtotime($st_date))=="Saturday"){
			$chk_h=1;
		}
		return $chk_h;
	}
?>