<?php

class libGeneral{


	public function __construct(){


	}

	public function  get_server_name()
	{
		return  gethostbyaddr($_SERVER['REMOTE_ADDR']);;
	}

	public function  curl($url=null,$a_param=array(),$format="json")
	{
		//echo ss;
		$log = new log();
		$log->_logname = "logs/curl";
		//echo $url;

		if($url=="") return false;
		try {
			if($format=="json"){
				$fields_string = json_encode($a_param);
			}else{
				$fields_string = $a_param;
			}

			$log->write_log("url =>".$url);
			$log->write_log("parameter =>".$fields_string);
			$json_url = $url;
			$json_string = $fields_string;
			$ch = curl_init( $json_url );
			
			$options = array(
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
				CURLOPT_POSTFIELDS => $json_string
			);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt_array( $ch, $options );
			$result =  curl_exec($ch); // Getting jSON result string
			//echo $result;
			$log->write_log("result =>".$result);
			if(curl_errno($ch))
			{
				$a_return["status"] = false;
				$a_return["error"] =   curl_error($ch);
				$a_return["result"] = "";
			}else{
				$return = json_decode($result,true);
				if($return==""){
					$a_return["status"] = false;
					$a_return["error"] = $result;
					$a_return["result"] ="";
				}else{
					if($return["Status"]=="E"){
						$a_return["status"] = false;
						$a_return["error"] = $return["Message"];
						$a_return["result"] =$return;
					}else{
						$a_return["status"] = true;
						$a_return["error"] = "";
						$a_return["result"] = $return;
					}
				}
				//echo "<pre>";print_r($result);echo "</pre>";


			}
			curl_close($ch);
		} catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		}

		return $a_return;
	}
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


function bahtEng($thb) {
	list($thb, $ths) = explode('.', $thb);
	$ths = substr($ths.'00', 0, 2);
	$thb = engFormat(intval($thb)).' Baht';
	if (intval($ths) > 0) {
		$thb .= ' and '.engFormat(intval($ths)).' Satang';
	}
	return $thb;
}
function engFormat($number) {
	list($thb, $ths) = explode('.', $thb);
	$ths = substr($ths.'00', 0, 2);
	$max_size = pow(10, 18);
	if (!$number)
		return "zero";
		if (is_int($number) && $number < abs($max_size)) {
			switch ($number) {
				case $number < 0:
					$prefix = "negative";
					$suffix = engFormat(-1 * $number);
					$string = $prefix." ".$suffix;
					break;
				case 1:
					$string = "one";
					break;
				case 2:
					$string = "two";
					break;
				case 3:
					$string = "three";
					break;
				case 4:
					$string = "four";
					break;
				case 5:
					$string = "five";
					break;
				case 6:
					$string = "six";
					break;
				case 7:
					$string = "seven";
					break;
				case 8:
					$string = "eight";
					break;
				case 9:
					$string = "nine";
					break;
				case 10:
					$string = "ten";
					break;
				case 11:
					$string = "eleven";
					break;
				case 12:
					$string = "twelve";
					break;
				case 13:
					$string = "thirteen";
					break;
				case 15:
					$string = "fifteen";
					break;
				case $number < 20:
					$string = engFormat($number % 10);
					if ($number == 18) {
						$suffix = "een";
					} else {
						$suffix = "teen";
					}
					$string .= $suffix;
					break;
				case 20:
					$string = "twenty";
					break;
				case 30:
					$string = "thirty";
					break;
				case 40:
					$string = "forty";
					break;
				case 50:
					$string = "fifty";
					break;
				case 60:
					$string = "sixty";
					break;
				case 70:
					$string = "seventy";
					break;
				case 80:
					$string = "eighty";
					break;
				case 90:
					$string = "ninety";
					break;
				case $number < 100:
					$prefix = engFormat($number - $number % 10);
					$suffix = engFormat($number % 10);
					$string = $prefix."-".$suffix;
					break;
				case $number < pow(10, 3):
					$prefix = engFormat(intval(floor($number / pow(10, 2))))." hundred";
					if ($number % pow(10, 2))
						$suffix = " and ".engFormat($number % pow(10, 2));
						$string = $prefix.$suffix;
						break;
				case $number < pow(10, 6):
					$prefix = engFormat(intval(floor($number / pow(10, 3))))." thousand";
					if ($number % pow(10, 3))
						$suffix = engFormat($number % pow(10, 3));
						$string = $prefix." ".$suffix;
						break;
				case $number < pow(10, 9):
					$prefix = engFormat(intval(floor($number / pow(10, 6))))." million";
					if ($number % pow(10, 6))
						$suffix = engFormat($number % pow(10, 6));
						$string = $prefix." ".$suffix;
						break;
				case $number < pow(10, 12):
					$prefix = engFormat(intval(floor($number / pow(10, 9))))." billion";
					if ($number % pow(10, 9))
						$suffix = engFormat($number % pow(10, 9));
						$string = $prefix." ".$suffix;
						break;
				case $number < pow(10, 15):
					$prefix = engFormat(intval(floor($number / pow(10, 12))))." trillion";
					if ($number % pow(10, 12))
						$suffix = engFormat($number % pow(10, 12));
						$string = $prefix." ".$suffix;
						break;
				case $number < pow(10, 18):
					$prefix = engFormat(intval(floor($number / pow(10, 15))))." quadrillion";
					if ($number % pow(10, 15))
						$suffix = engFormat($number % pow(10, 15));
						$string = $prefix." ".$suffix;
						break;
			}
		}
		return $string;
}
?>
