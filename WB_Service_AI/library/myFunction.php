<?php
function getphone($module,$cd){
	global $adb;
	global $current_user;
	global $log;
	$sql = " select *  from ai_config_sms  where module = '".$module."' ";
	//echo $sql;
	$res=$adb->pquery($sql, array());
	$module = $adb->query_result($res,0,'module');
	if($module<>''){
		$field_phone = $adb->query_result($res,0,'field_phone');
		$table = $adb->query_result($res,0,'table');
		$field_pk = $adb->query_result($res,0,'field_pk');
		//substr(str_replace(";",",",$cd),strlen($cd)-1);
		$sql = " select ".$field_phone. " from ".$table ." where " .$field_pk ." in ( ".str_replace(";",",",substr($cd,0,-1))." )";
		$res=$adb->pquery($sql, array());
		$res_cnt = $adb->num_rows($res);
		$phone = '';
				if($res_cnt > 0) {
					for($i=0;$i<$res_cnt;$i++) {
						if($i<>0){
							$phone .= ",";
						}
						$phone .= str_replace("-","",$adb->query_result($res,$i,$field_phone));
					}
				}	
		//echo str_replace(";",",",substr($cd,0,-1));
		//echo $sql;
	return $phone;
	}
		
}
function to_utf16($text) {
	$out="";
	$text=mb_convert_encoding($text,'UTF-16','UTF-8');
	for ($i=0;$i<mb_strlen($text,'UTF-16');$i++)
		$out.= bin2hex(mb_substr($text,$i,1,'UTF-16'));
	return $out;
}


	function get_discount($value,$total){
		$temp=split('\+',$value);
		$sum_discount=0;
		for($i=0;$i<count($temp);$i++){
			if(stristr($temp[$i], "%") == true) { 
				$temp1 = split("%",$temp[$i]);
				$sum_discount=$sum_discount+(($total*$temp1[0])/100);
				$total=$total-$sum_discount;
				$sum_discount1=$sum_discount1+$sum_discount;
				$sum_discount=0;
			}else{
				$total=$total-$temp[$i];
				$sum_discount1=$sum_discount1+$temp[$i];
			} 
		}
		return $sum_discount1;
	}
	function begin_tran() {
		@mysql_query("BEGIN");
	}
	function commit_tran(){
		@mysql_query("COMMIT");
	}
	function rollback_tran(){
		@mysql_query("ROLLBACK");
	}
	function error_tran()
	{
		return mysql_error();
	}
	function num2thai($number){
		$t1 = array("ศูนย์", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
		$t2 = array("เอ็ด", "ยี่", "สิบ", "ร้อย", "พัน", "หมื่น", "แสน", "ล้าน");
		$zerobahtshow = 0; // ในกรณีที่มีแต่จำนวนสตางค์ เช่น 0.25 หรือ .75 จะให้แสดงคำว่า ศูนย์บาท หรือไม่ 0 = ไม่แสดง, 1 = แสดง(string) 
		$number;$number = explode(".", $number);
			if(!empty($number[1])){
				if(strlen($number[1]) == 1){
					$number[1] .= "0";
				}elseif(strlen($number[1]) > 2){
					if($number[1]{2} < 5){
						$number[1] = substr($number[1], 0, 2);
					}else{
						$number[1] = $number[1]{0}.($number[1]{1}+1);
					}
				}
			}
			for($i=0; $i<count($number); $i++){
				$countnum[$i] = strlen($number[$i]);
					if($countnum[$i] <= 7){
						$var[$i][] = $number[$i];
					}else{
						$loopround = ceil($countnum[$i]/6);
							for($j=1; $j<=$loopround; $j++){
								if($j == 1){
									$slen = 0;
									$elen = $countnum[$i]-(($loopround-1)*6);
								}else{
									$slen = $countnum[$i]-((($loopround+1)-$j)*6);$elen = 6;
								}
								$var[$i][] = substr($number[$i], $slen, $elen);
							}
					}
						$nstring[$i] = "";
						for($k=0; $k<count($var[$i]); $k++){
							if($k > 0) 
								$nstring[$i] .= $t2[7];
							$val = $var[$i][$k];
							$tnstring = "";
							$countval = strlen($val);
							for($l=7; $l>=2; $l--){
								if($countval >= $l){
									$v = substr($val, -$l, 1);
										if($v > 0){if($l == 2 && $v == 1){
											$tnstring .= $t2[($l)];
										}elseif($l == 2 && $v == 2){
											$tnstring .= $t2[1].$t2[($l)];
										}else{
											$tnstring .= $t1[$v].$t2[($l)];
										}
								}
							}
						}
						if($countval >= 1){
							$v = substr($val, -1, 1);
							if($v > 0){
								if($v == 1 && $countval > 1 && substr($val, -2, 1) > 0){
									$tnstring .= $t2[0];
								}else{
									$tnstring .= $t1[$v];
								}
							}
						}
							$nstring[$i] .= $tnstring;
					}
				}
					$rstring = "";
						if(!empty($nstring[0]) || $zerobahtshow == 1 || empty($nstring[1])){
							if($nstring[0] == "") 
								$nstring[0] = $t1[0];
								$rstring .= $nstring[0]."บาท";
						}
						if(count($number) == 1 || empty($nstring[1])){
							$rstring .= "ถ้วน";
						}else{
							$rstring .= $nstring[1]."สตางค์";
						}
		return $rstring;
		}
	
		function decodedate($strdate,$type){
			if( empty( $strdate ) ||($strdate == "0000-00-00"))	 return "";
				$thaimonth = array('','ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
				$engmonth = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
				$unixTimeStamp = strtotime($strdate);
				$date = date ('j ',$unixTimeStamp);
				$month = intval(date('m',$unixTimeStamp));
				$thyear =  date('Y', $unixTimeStamp) + 543;
				$enyear = date('Y',$unixTimeStamp) ;
				if ($type == 1 )
					$strdate = $date." ".$thaimonth[$month]." ".$thyear;
				else 
					$strdate = $date." ".$engmonth[$month]." ".$enyear;
				return ($strdate);	
		}//end function decodedate
		function encodedate2($strdate){
			if( empty( $strdate ) ||($strdate == "0000-00-00"))	 return "";
			$mydate = split("-",$strdate);
			$toyear =  $mydate[2];
			$todate = ($toyear)."-".$mydate[1]."-".$mydate[0];
			//echo $mydate[0]."<br>";
				return ($todate);	
		}
		function decodedate1($strdate,$type){
			if( empty( $strdate ) ||($strdate == "0000-00-00"))	 return "";
				$thaimonth = array('','มกราคม','กุมภาพันธ์','มีนาคา','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม');
				$engmonth = array('','January','February','March','April','May','June','July','August','September','October','November','December');
				$unixTimeStamp = strtotime($strdate);
				$date = date ('j ',$unixTimeStamp);
				$month = intval(date('m',$unixTimeStamp));
				$thyear =  date('Y', $unixTimeStamp) + 543;
				$enyear = date('Y',$unixTimeStamp) ;
				if ($type == 1 )
					$strdate = $date." ".$thaimonth[$month]." ".$thyear;
				else 
					$strdate = $date." ".$engmonth[$month]." ".$enyear;
				return ($strdate);	
		}//end function decodedate
				function decodedate1234($strdate,$type){
			if( empty( $strdate ) ||($strdate == "0000-00-00"))	 return "";
				$thaimonth = array('','มกราคม','กุมภาพันธ์','มีนาคา','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม');
				$engmonth = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
				$unixTimeStamp = strtotime($strdate);
				$date = date ('j ',$unixTimeStamp);
				$month = intval(date('m',$unixTimeStamp));
				$thyear =  date('Y', $unixTimeStamp) ;
				$enyear = date('Y',$unixTimeStamp) ;
				if ($type == 1 )
					$strdate = "วันที่ ".$date." ".$thaimonth[$month]." ".$thyear;
				else 
					$strdate = $date." ".$engmonth[$month]." ".$enyear;
				return ($strdate);	
		}//end function decodedate
	function decodedate2($strdate,$type){
			if( empty( $strdate ) ||($strdate == "0000-00-00"))	 return "";
				$thaimonth = array('','ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
				$engmonth = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
				$unixTimeStamp = strtotime($strdate);
				$date = date ('j ',$unixTimeStamp);
				$month = intval(date('m',$unixTimeStamp));
				$thyear =  date('Y', $unixTimeStamp) + 543;
				$enyear = date('Y',$unixTimeStamp) ;
				if ($type == 1 )
					$strdate = trim($date)."-".trim($thaimonth[$month])."-".$thyear;
				else 
					$strdate = $date." ".$engmonth[$month]." ".$enyear;
				return ($strdate);	
		}//end function decodedate
			function decodedate22($strdate,$type){
			if( empty( $strdate ) ||($strdate == "0000-00-00"))	 return "";
				$thaimonth = array('','ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
				$engmonth = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
				$unixTimeStamp = strtotime($strdate);
				$mydate = split("-",$strdate);
				$date = date ('j ',$unixTimeStamp);
				$month = intval(date('m',$unixTimeStamp));
				$thyear =  date('Y', $unixTimeStamp) + 543;
				$enyear = date('Y',$unixTimeStamp) ;
				if ($type == 1 )
					$strdate = $date."-".$mydate[1]."-".substr($thyear,2,4);
				else 
					$strdate = $date." ".$engmonth[$month]." ".$enyear;
				return ($strdate);	
		}//end function decodedate

		function decodedate3($strdate,$type){
			if( empty( $strdate ) ||($strdate == "0000-00-00"))	 return "";
				$thaimonth = array('','มกราคม','กุมภาพันธ์','มีนาคา','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม');
				$engmonth = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
				$unixTimeStamp = strtotime($strdate);
				$date = date ('j ',$unixTimeStamp);
				$month = intval(date('m',$unixTimeStamp));
				$thyear =  date('Y', $unixTimeStamp) + 543;
				$enyear = date('Y',$unixTimeStamp) ;
				if ($type == 1 )
					$strdate = "วันที่  ".$date." เดือน  ".$thaimonth[$month]." พ.ศ.  ".$thyear;
				else 
					$strdate = $date." ".$engmonth[$month]." ".$enyear;
				return ($strdate);	
		}//end function decodedate
		function decodedate333($strdate,$type){
			if( empty( $strdate ) ||($strdate == "0000-00-00"))	 return "";
				$thaimonth = array('','มกราคม','กุมภาพันธ์','มีนาคา','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม');
				$engmonth = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
				$unixTimeStamp = strtotime($strdate);
				$date = date ('j ',$unixTimeStamp);
				$month = intval(date('m',$unixTimeStamp));
				$thyear =  date('Y', $unixTimeStamp) ;
				$enyear = date('Y',$unixTimeStamp) ;
				if ($type == 1 )
					$strdate = "วันที่  ".$date." เดือน  ".$thaimonth[$month]." ค.ศ.  ".$thyear;
				else 
					$strdate = $date." ".$engmonth[$month]." ".$enyear;
				return ($strdate);	
		}		
		
				function encodedate($strdate){
			if( empty( $strdate ) ||($strdate == "0000-00-00"))	 return "";
			$mydate = split("-",$strdate);
			$toyear =  $mydate[2]-543;
			$todate = $toyear."-".$mydate[1]."-".$mydate[0];
				return ($todate);	
		}

		function encodedate1($strdate){
			if( empty( $strdate ) ||($strdate == "00-00-0000"))	 return "";
			$mydate = split("-",$strdate);
			$toyear =  $mydate[2];
			$todate = $mydate[0]."-".$mydate[1]."-".$toyear;
				return ($todate);	
		}
		function encodedate12($strdate){
			if( empty( $strdate ) ||($strdate == "00-00-0000"))	 return "";
			$mydate = split("-",$strdate);
			$toyear =  $mydate[2];
			$todate = $toyear."-".$mydate[1]."-".$mydate[0];
				return ($todate);	
		}

		function encodedate_csv($strdate){
			if( empty( $strdate ) ||($strdate == "0000-00-00"))	 return "";
			$mydate = split("-",$strdate);
			$toyear =  $mydate[2];
			$todate = $toyear."-".$mydate[1]."-".$mydate[0];
				return ($todate);	
		}
		function encodedate_slat($strdate){
			if( empty( $strdate ) ||($strdate == "0000-00-00"))	 return "";
			$mydate = split("-",$strdate);
			$toyear =  $mydate[2];
			$todate = $toyear."/".$mydate[1]."/".$mydate[0];
				return ($todate);	
		}

		function calDistance($distance){
			$distance = $distance *1000;
			//echo "q".($distance)."q";
			//echo "s".(floor(($distance)/1000)*1000)."s";
			//echo ceil((($distance) - (floor(($distance)/1000)*1000)));
			//echo "aa".strlen(($distance) - (floor(($distance)/1000)*1000))."aa";
			//$test = str_repeat("0",3-strlen((($distance) - (floor(($distance)/1000)*1000))));
			$distance = floor(($distance)/1000)."+".str_repeat("0",3-strlen(ceil((($distance) - (floor(($distance)/1000)*1000))))).ceil((($distance) - (floor(($distance)/1000)*1000)));
			return $distance;
		}
		function calDistance_KM($distance){
				//echo $distance;
			$distance = $distance *1000;
			$distance = floor(($distance)/1000);
		//echo $distance;
			return $distance;
		}
		function calDistance_M($distance){
			$distance = $distance *1000;
			$distance = str_repeat("0",3-strlen(ceil((($distance) - (floor(($distance)/1000)*1000))))).ceil((($distance) - (floor(($distance)/1000)*1000)));
			//echo $distance;
			return $distance;
		}
		function genDistance($begin,$end){
			for($k=floor($begin);$k<ceil($end);$k++){
				$distance[$k] = $k.".000";
				if(($k+1) < $end){
					$distance[$k] .= "-".($k+1).".000";
				}else{
					$distance[$k] .= "-".$end;
				}
			}
			return $distance[$k];
		}


		function getDirFiles($dirPath){
			if ($handle = opendir($dirPath)) {
				while (false !== ($file = readdir($handle))) 
					if ($file != "." && $file != "..") 
					$filesArr[] = trim($file);            
					closedir($handle);
				}  
     
			return $filesArr;    
		} 


	function displaypathcode($pathcode){
		return substr($pathcode,0,2).".".substr($pathcode,2,4);
	}

	function convertpathcode($pathcode){
		global	$genarate;
			$leftpathcode = substr($pathcode,0,2);
			$sql = "select PROVCODE,REFERNUMBER from province";
			$sql .= " where PROVCODE = '".$leftpathcode."'";
			$data = $genarate->process($sql,"all");
			$k = 0;
			$a = 0; 
			return $data[$k][$a+1].substr($pathcode,3,5);
	}

	function sortorder($sort,$order){
				if($order == "desc"){
					$order = "asc";
				}else{
					$order = "desc";
				}

		return $order;
	}

	function decodeprice($price){
		if($price != ""){
			//echo $price;
			$temp = substr($price,0,-3);
			$data = str_replace(',','',$temp);
			return $data;
		}
	}

	function calc_RCI($YEAR,$PATHCODE,$GROUPCODE){
	global	$genarate;
		$sql_subcode = " SELECT distinct(SUBCODE) From  conditionsroad where PATHCODE='".$PATHCODE."' and YEAR='".$YEAR."' and  GROUPCODE='".$GROUPCODE."' ";
		$data_subcode = $genarate->process($sql_subcode,"all");
		if($data_subcode){
			for($i=0;$i<count($data_subcode);$i++){
				$SUBCODE = $data_subcode[$i][0];
				$sql = " SELECT (
									SELECT if( isnull( sum( AREA ) ) , 0, sum( AREA ) ) percent1
									FROM conditionsroad
									WHERE TYPE IN (
									'01', '02', '04'
									)
									and PATHCODE='".$PATHCODE."' and YEAR='".$YEAR."' and SUBCODE='".$SUBCODE."' 
									and  GROUPCODE='".$GROUPCODE."' 
								), (
								SELECT if( isnull( sum( AREA ) ) , 0, sum( AREA ) ) percent2
								FROM conditionsroad1
								WHERE TYPE IN (
									'03', '05', '06'
								)
								and PATHCODE='".$PATHCODE."' and YEAR='".$YEAR."' and  SUBCODE='".$SUBCODE."' 
								and  GROUPCODE='".$GROUPCODE."' 
							)";
				//echo	$sql;
				$data = $genarate->process($sql,"all");
				
				$sql_road = " Select (ENDKILO1-BEGINKILO1),WIDTH_SURFACE FROM roadinventory where PATHCODE='".$PATHCODE."' and  GROUPCODE='".$GROUPCODE."' ";
				$data_road = $genarate->process($sql_road,"all");
				
				
				if(count($data)>0 && count($data_road)>0){
					$percent1 = $data[0][0];
					$percent2 = $data[0][1];
					$DISTANCE = $data_road[0][1];
					$WIDTH_SURFACE = $data_road[0][2];
						
						$RCI = 0.954 + (0.050*$percent1) + (0.054*$percent2);
						
						$sqlselect = " SELECT PATHCODE form process where PATHCODE='".$PATHCODE."' and  GROUPCODE='".$GROUPCODE."' and YEAR='".$YEAR."' and  SUBCODE='".$SUBCODE."' ";
						$dataselect= $genarate->process($sqlselect,"all");
						if(count($dataselect) > 0 ){
							$sql = " update process set RCI = '".$RCI ."' ";
							$sql .=" where PATHCODE='".$PATHCODE."'  and  GROUPCODE='".$GROUPCODE."' and YEAR='".$YEAR."' and  SUBCODE='".$SUBCODE."' ";
						}else{
							$sql = " INSERT INTO  process ( YEAR,GROUPCODE,PATHCODE,SUBCODE,BEGINKILO,ENDKILO,RCI,SURFACECODE) ";
							$sql .=" where PATHCODE='".$PATHCODE."'  and  GROUPCODE='".$GROUPCODE."' and YEAR='".$YEAR."' and  SUBCODE='".$SUBCODE."' ";
						}	
	//echo	$sql;
	$genarate->query($sql);	
					//	return $RCI;
				}
			}	
		}	
	}
	function calc_RCI3($YEAR,$PATHCODE,$GROUPCODE){
	global	$genarate;
	
		$sql = " select c.TYPE1,c.TYPE2,c.TYPE3,a.DISTANCE,a.WIDTH_SURFACE 
		from conditionsroad3 c left join  roadinventory  a 
		on c.PATHCODE = a.PATHCODE
		where c.PATHCODE='".$PATHCODE."' and c.YEAR='".$YEAR."' and  c.GROUPCODE='".$GROUPCODE."' ";
		//echo	$sql;
		$data = $genarate->process($sql,"all");
		if(count($data)>0){
			$TYPE1 = $data[0][0];
			$TYPE2 = $data[0][1];
			$TYPE3 = $data[0][2];
			$DISTANCE = $data[0][3];
			$WIDTH_SURFACE = $data[0][4];
			
				$percenttype1 =  (($TYPE1 * 0.025) / ($DISTANCE * $WIDTH_SURFACE)) * 100;
				$percenttype =  ($TYPE2 + $TYPE2);
				
				$RCI = 1.037 + (0.041*$percenttype1) + (0.039*$percenttype);
					return $RCI;
		}
	}
	
	
	function calc_RCI2($YEAR,$PATHCODE,$GROUPCODE){
	global	$genarate;
	$sql = "select count(a.PLACECODE) from roadinventorysocial as a, place as b";
	$sql .= " where a.placecode = b.placecode and b.placetype = '1' and PATHCODE='".$_REQUEST["param_pathcode"]."'";
	$data = $genarate->process($sql,"all");
	//echo	$sql;
	$maxvalue = $data[0][0];
	if($maxvalue == 0){
		$value = 0;
	}else if($maxvalue <= 9){
		$value = 5;
	}else if($maxvalue == 10 || $maxvalue <= 15){
		$value = 7;
	}else if($maxvalue > 15){
		$value = 10;	
	}
	// update F5_PLACE in condition table
	$sql = " update condition1 set F5_PLACE = '".$value."' ";
	$sql .=" where PATHCODE='".$_REQUEST["param_pathcode"]."'";
	//echo	$sql;
	$genarate->query($sql);	
	}

	function calcF6_DEPT($PATHCODE){
	global	$genarate;
		$sql ="select max(department) from roadinventory where PATHCODE='".$_REQUEST["param_pathcode"]."' ";
		$data = $genarate->process($sql,"all");
		//echo	$sql;
		$maxvalue = $data[0][0];
// update F6_DEPT in condition table
	$sql = " update condition1 set F6_DEPT = '".$maxvalue."' ";
	$sql .=" where PATHCODE='".$_REQUEST["param_pathcode"]."'";
	//echo	$sql;
	$genarate->query($sql);	
	}


	function calcF7_TYPECAR($PATHCODE){
	global	$genarate;
			// Load Traffic Information
			$a = 0;
			$k = 0;
			$sql = " select CAR1,CAR2,CAR3,CAR4,CAR5,CAR6,CAR7,CAR8,CAR9,CAR10,CAR11,CAR12 from conditiontraffic ";
			$sql .= " where PATHCODE='".$_REQUEST["param_pathcode"]."' and YEAR='".$_REQUEST["param_year"]."' ";
			$data = $genarate->process($sql,"all");
			$Query_Car1=$data[$k][$a];
			$Query_Car2=$data[$k][$a+1];
			$Query_Car3=$data[$k][$a+2];
			$Query_Car4=$data[$k][$a+3];
			$Query_Car5=$data[$k][$a+4];
			$Query_Car6=$data[$k][$a+5];
			$Query_Car7=$data[$k][$a+6];
			$Query_Car8=$data[$k][$a+7];
			$Query_Car9=$data[$k][$a+8];
			$Query_Car10=$data[$k][$a+9];
			$Query_Car11=$data[$k][$a+10];
			$Query_Car12=$data[$k][$a+11];
			// End Load Traffic Information
			$PCU = $Query_Car1*0.25 + $Query_Car2*1 + $Query_Car3*1 + $Query_Car4*1.5 + $Query_Car5*2+$Query_Car6*2.5 + $Query_Car7*2.5 + $Query_Car8*2.5 + $Query_Car9*2.5 + $Query_Car10*2.5 + $Query_Car11*2.5 + $Query_Car12*2.5;

	if($PCU == 0){
		$value = 0;
	}else if($PCU <1000){
		$value = 7;
	}else if($PCU == 1000 || $PCU <=3000){
		$value = 10;
	}else if($PCU == 3001 || $PCU <= 5000){
		$value = 12;
	}else if($PCU > 5000){
		$value = 15;	
	}
	// update F5_PLACE in condition table
	$sql = " update condition1 set F7_TYPECAR = '".$value."' ";
	$sql .=" where PATHCODE='".$_REQUEST["param_pathcode"]."' and YEAR='".$_REQUEST["param_year"]."' ";
	$genarate->query($sql);	
		//echo	$sql;
	}


	function calcF8_TYPECAR($PATHCODE){
	global	$genarate;
			// Load Traffic Information
			$a = 0;
			$k = 0;
			$sql = " select CAR1,CAR2,CAR3,CAR4,CAR5,CAR6,CAR7,CAR8,CAR9,CAR10,CAR11,CAR12 from conditiontraffic ";
			$sql .= " where PATHCODE='".$_REQUEST["param_pathcode"]."' and YEAR='".$_REQUEST["param_year"]."' ";
			$data = $genarate->process($sql,"all");
			$Query_Car1=$data[$k][$a];
			$Query_Car2=$data[$k][$a+1];
			$Query_Car3=$data[$k][$a+2];
			$Query_Car4=$data[$k][$a+3];
			$Query_Car5=$data[$k][$a+4];

			$Query_Car6=$data[$k][$a+5];
			$Query_Car7=$data[$k][$a+6];
			$Query_Car8=$data[$k][$a+7];
			$Query_Car9=$data[$k][$a+8];
			$Query_Car10=$data[$k][$a+9];
			$Query_Car11=$data[$k][$a+10];
			$Query_Car12=$data[$k][$a+11];
			// End Load Traffic Information
			$PCU = $Query_Car6*2.5 + $Query_Car7*2.5 + $Query_Car8*2.5 + $Query_Car9*2.5 + $Query_Car10*2.5 + $Query_Car11*2.5 + $Query_Car12*2.5;
	/*		
	if($PCU == 0){
		$value = 0;
	}else 
	*/

			if($PCU <200){
				$value = 0;
			}else if($PCU ==200 || $PCU <=800){
				$value = 3;
			}else if($PCU ==801 || $PCU <=1400){
				$value = 5;
			}else if($PCU ==1401 || $PCU <=2000){
				$value = 7;
			}else if($PCU >2000){
				$value = 10;	
			}
	// update F8_TYPECAR in condition table
	$sql = " update condition1 set F8_TYPECAR = '".$value."' ";
	$sql .=" where PATHCODE='".$_REQUEST["param_pathcode"]."' and YEAR='".$_REQUEST["param_year"]."' ";
	$genarate->query($sql);	
		//echo	$sql;
	}

	function sumFunction($PATHCODE,$YEAR,$SUBCODE){
	global	$genarate;
		$sql = "select F1_RCI,F2_IRI,F3_USEDAGE,F4_PLACE,F5_PLACE,F6_DEPT,F7_TYPECAR,F8_TYPECAR from condition1";
		$sql .=" where PATHCODE='".$_REQUEST["param_pathcode"]."' and SUBCODE='".$SUBCODE."'and YEAR='".$YEAR."' ";
			$data = $genarate->process($sql,"all");	
			$k = 0;
			$a = 0;
				$F1_RCI = $data[$k][$a];
				$F2_IRI = $data[$k][$a+1];
				$F3_USEDAGE = $data[$k][$a+2];
				$F4_PLACE = $data[$k][$a+3];
				$F5_PLACE = $data[$k][$a+4];
				$F6_DEPT = $data[$k][$a+5];
				$F7_TYPECAR = $data[$k][$a+6];
				$F8_TYPECAR = $data[$k][$a+7];
	
	$SUM = $F1_RCI+$F2_IRI+$F3_USEDAGE+$F4_PLACE+$F5_PLACE+$F6_DEPT+$F7_TYPECAR+$F8_TYPECAR;
			
	// update SUMFUNCTION in condition table
		$sql = " update condition1 set SUMFUNCTION = '".$SUM."' ";
		$sql .=" where PATHCODE='".$_REQUEST["param_pathcode"]."' and YEAR='".$YEAR."' and SUBCODE='".$SUBCODE."'";
		if($genarate->query($sql)){
			//echo	$sql;
		}	
	}
function cal_groupdetail($str,$parent){
	global	$genarate;
	if (count($parent)>0){
		$j=0;
		for($k=0 ;$k<count($parent); $k++){
		//echo " parent k = ".$parent[$k];
			if($parent[$k]!=0){
				$sql = " select GROUPCODE,GROUPREFER from groupmap  where GROUPREFER='".$parent[$k]."' ";
				$datagroup = $genarate->process($sql,"all");
					
				//echo $sql;
				for($i=0 ;$i<count($datagroup); $i++){
					if ($str ==''){
						$str = $datagroup[$i][0];
					}else{
						$str .= ",".$datagroup[$i][0];
					}	
					$refer[$j] .= $datagroup[$i][0];
					$j +=1;
					//echo ' str = ' .$str  ."<br>";
				}//for
			}
		}//for	
			return  cal_groupdetail($str,$refer);
		}else{
				return  $str;
		}
};


?>