<?
function images1($IMGPATH1,$nameImage,$filePath,$wconfig,$hconfig) { //การเก็บ image
global $genarate;
	if ($_FILES['IMGPATH1']["name"] <> ""){
		if ($error ==0 ){
			$size = $_FILES["IMGPATH1"]["size"];
			$type = $_FILES["IMGPATH1"]["type"];
			$tmp_name = $_FILES["IMGPATH1"]["tmp_name"];
			$fname = $_FILES["IMGPATH1"]["name"];
				list($usec, $sec) = explode(" ", microtime());
				list($sec2,$milli)	= explode(".", $usec);					
			$nameImage = $nameImage.$milli;
			$name1 = $nameImage.substr($fname,strripos($fname , "."));
			//$nmext = explode(".",$fname);
			//$name1 = $nameImage.".".$nmext[1];
	
			$uploadfile = $filePath."/" .basename($name1);
			$thumbfile = $filePath. "/thumb/" .$name1;
			
			if((!ereg("^image/jpeg",$type))and(!ereg("^image/gif",$type))and(!ereg("^image/x-png",$type))and(!ereg("^image/pjpeg",$type))){
				echo "alert(\" ERROR : upload ไม่ได้ Typeไม่ถูกต้อง  \")";
				//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ Typeไม่ถูกต้อง</h3></div>";
				die;
			}

		if($size > $genarate->image_maxsize){
			echo "alert(' ERROR : upload ไม่ได้ขนาด file ใหญ่เกิน $genarate->image_maxsize  ')";
			//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ขนาด file ใหญ่เกิน $limit</h3></div>";
			die;
		}
		list($width,$heigth) = getimagesize($tmp_name);
		if($width>800){
			echo "alert(' ERROR : upload ไม่ได้ขนาดความกว้าง file ใหญ่เกิน 800 pixel  ')";
			//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ขนาดความกว้าง file ใหญ่เกิน 800 pixel</h3></div>";
			die;
		}
		move_uploaded_file($tmp_name,"$uploadfile");//การย้ายไฟล์
		chmod("$uploadfile", 0777);
//----------------------------------------------------------------//
			$quality = 90;
			$border = 2; 
			$percent = $hconfig / $heigth;
			$wpercent = $width * $percent;
			$hpercent = $heigth * $percent;
				if($wpercent>$wconfig){
					$percent = $wconfig / $width;
					$wpercent = $width * $percent;
					$hpercent = $heigth * $percent;
				}

			$im = imageCreateTrueColor($wpercent+$border,$hpercent+$border);
			$borderColor1 = "200, 200, 200"; //RGB : Gray
			$borderColorArr = explode( ",", $borderColor1 );
			$borderColor = imagecolorallocate( $im, $borderColorArr[0], $borderColorArr[1], $borderColorArr[2] );
				imagefilledRectangle( $im, 0,0, $wpercent+$border, $hpercent+$border, $borderColor );
				imagefilledRectangle( $im, 1,1, $wpercent, $hpercent, $bgColor );
			
				if(ereg("^image/jpeg",$type)){
					$im1 = imageCreateFromjpeg($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);
				}elseif(ereg("^image/pjpeg",$type)){
					$im1 = imageCreateFromjpeg($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);	
				}elseif(ereg("^image/gif",$type)){
					$im1 = imageCreateFromgif($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);
				}elseif (ereg("^image/x-png",$type)){
					$im1 = imageCreateFrompng($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagepng($im,$thumbfile,$quality);
				}
			imagedestroy($im);
			imagedestroy($im1);
		}
			return $name1;
	//----------------------------------------------------------------//
	}//endif userflie=" "*/

}

function images2($IMGPATH2,$nameImage,$filePath,$wconfig,$hconfig) { //การเก็บ image
global $genarate;
	if ($_FILES['IMGPATH2']["name"] <> ""){
		if ($error ==0 ){
			$size = $_FILES["IMGPATH2"]["size"];
			$type = $_FILES["IMGPATH2"]["type"];
			$tmp_name = $_FILES["IMGPATH2"]["tmp_name"];
			$fname = $_FILES["IMGPATH2"]["name"];
				list($usec, $sec) = explode(" ", microtime());
				list($sec2,$milli)	= explode(".", $usec);					
			$nameImage = $nameImage.$milli;
			$name1 = $nameImage.substr($fname,strripos($fname , "."));
			//$nmext = explode(".",$fname);
			//$name1 = $nameImage.".".$nmext[1];
	
			$uploadfile = $filePath."/" .basename($name1);
			$thumbfile = $filePath. "/thumb/" .$name1;
			
			if((!ereg("^image/jpeg",$type))and(!ereg("^image/gif",$type))and(!ereg("^image/x-png",$type))and(!ereg("^image/pjpeg",$type))){
				echo "alert(\" ERROR : upload ไม่ได้ Typeไม่ถูกต้อง  \")";
				//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ Typeไม่ถูกต้อง</h3></div>";
				die;
			}

		if($size > $genarate->image_maxsize){
			echo "alert(' ERROR : upload ไม่ได้ขนาด file ใหญ่เกิน $genarate->image_maxsize  ')";
			//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ขนาด file ใหญ่เกิน $limit</h3></div>";
			die;
		}
		list($width,$heigth) = getimagesize($tmp_name);
		if($width>800){
			echo "alert(' ERROR : upload ไม่ได้ขนาดความกว้าง file ใหญ่เกิน 800 pixel  ')";
			//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ขนาดความกว้าง file ใหญ่เกิน 800 pixel</h3></div>";
			die;
		}
		move_uploaded_file($tmp_name,"$uploadfile");//การย้ายไฟล์
		chmod("$uploadfile", 0777);
//----------------------------------------------------------------//
			$quality = 90;
			$border = 2; 
			$percent = $hconfig / $heigth;
			$wpercent = $width * $percent;
			$hpercent = $heigth * $percent;
				if($wpercent>$wconfig){
					$percent = $wconfig / $width;
					$wpercent = $width * $percent;
					$hpercent = $heigth * $percent;
				}

			$im = imageCreateTrueColor($wpercent+$border,$hpercent+$border);
			$borderColor1 = "200, 200, 200"; //RGB : Gray
			$borderColorArr = explode( ",", $borderColor1 );
			$borderColor = imagecolorallocate( $im, $borderColorArr[0], $borderColorArr[1], $borderColorArr[2] );
				imagefilledRectangle( $im, 0,0, $wpercent+$border, $hpercent+$border, $borderColor );
				imagefilledRectangle( $im, 1,1, $wpercent, $hpercent, $bgColor );
			
				if(ereg("^image/jpeg",$type)){
					$im1 = imageCreateFromjpeg($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);
				}elseif(ereg("^image/pjpeg",$type)){
					$im1 = imageCreateFromjpeg($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);	
				}elseif(ereg("^image/gif",$type)){
					$im1 = imageCreateFromgif($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);
				}elseif (ereg("^image/x-png",$type)){
					$im1 = imageCreateFrompng($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagepng($im,$thumbfile,$quality);
				}
			imagedestroy($im);
			imagedestroy($im1);
		}
			return $name1;
	//----------------------------------------------------------------//
	}//endif userflie=" "*/

}
function images3($IMGPATH3,$nameImage,$filePath,$wconfig,$hconfig) { //การเก็บ image
global $genarate;
	if ($_FILES['IMGPATH3']["name"] <> ""){
		if ($error ==0 ){
			$size = $_FILES["IMGPATH3"]["size"];
			$type = $_FILES["IMGPATH3"]["type"];
			$tmp_name = $_FILES["IMGPATH3"]["tmp_name"];
			$fname = $_FILES["IMGPATH3"]["name"];
				list($usec, $sec) = explode(" ", microtime());
				list($sec2,$milli)	= explode(".", $usec);					
			$nameImage = $nameImage.$milli;
			$name1 = $nameImage.substr($fname,strripos($fname , "."));
			//$nmext = explode(".",$fname);
			//$name1 = $nameImage.".".$nmext[1];
	
			$uploadfile = $filePath."/" .basename($name1);
			$thumbfile = $filePath. "/thumb/" .$name1;
			
			if((!ereg("^image/jpeg",$type))and(!ereg("^image/gif",$type))and(!ereg("^image/x-png",$type))and(!ereg("^image/pjpeg",$type))){
				echo "alert(\" ERROR : upload ไม่ได้ Typeไม่ถูกต้อง  \")";
				//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ Typeไม่ถูกต้อง</h3></div>";
				die;
			}

		if($size > $genarate->image_maxsize){
			echo "alert(' ERROR : upload ไม่ได้ขนาด file ใหญ่เกิน $genarate->image_maxsize  ')";
			//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ขนาด file ใหญ่เกิน $limit</h3></div>";
			die;
		}
		list($width,$heigth) = getimagesize($tmp_name);
		if($width>800){
			echo "alert(' ERROR : upload ไม่ได้ขนาดความกว้าง file ใหญ่เกิน 800 pixel  ')";
			//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ขนาดความกว้าง file ใหญ่เกิน 800 pixel</h3></div>";
			die;
		}
		move_uploaded_file($tmp_name,"$uploadfile");//การย้ายไฟล์
		chmod("$uploadfile", 0777);
//----------------------------------------------------------------//
			$quality = 90;
			$border = 2; 
			$percent = $hconfig / $heigth;
			$wpercent = $width * $percent;
			$hpercent = $heigth * $percent;
				if($wpercent>$wconfig){
					$percent = $wconfig / $width;
					$wpercent = $width * $percent;
					$hpercent = $heigth * $percent;
				}

			$im = imageCreateTrueColor($wpercent+$border,$hpercent+$border);
			$borderColor1 = "200, 200, 200"; //RGB : Gray
			$borderColorArr = explode( ",", $borderColor1 );
			$borderColor = imagecolorallocate( $im, $borderColorArr[0], $borderColorArr[1], $borderColorArr[2] );
				imagefilledRectangle( $im, 0,0, $wpercent+$border, $hpercent+$border, $borderColor );
				imagefilledRectangle( $im, 1,1, $wpercent, $hpercent, $bgColor );
			
				if(ereg("^image/jpeg",$type)){
					$im1 = imageCreateFromjpeg($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);
				}elseif(ereg("^image/pjpeg",$type)){
					$im1 = imageCreateFromjpeg($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);	
				}elseif(ereg("^image/gif",$type)){
					$im1 = imageCreateFromgif($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);
				}elseif (ereg("^image/x-png",$type)){
					$im1 = imageCreateFrompng($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagepng($im,$thumbfile,$quality);
				}
			imagedestroy($im);
			imagedestroy($im1);
		}
			return $name1;
	//----------------------------------------------------------------//
	}//endif userflie=" "*/

}
function imagesarray($IMGPATH,$nameImage,$filePath,$wconfig,$hconfig,$i) { //การเก็บ image
global $genarate;
	if ($_FILES['IMGPATH']["name"][$i] <> ""){
		if ($error ==0 ){
			$size = $_FILES["IMGPATH"]["size"][$i];
			$type = $_FILES["IMGPATH"]["type"][$i];
			$tmp_name = $_FILES["IMGPATH"]["tmp_name"][$i];
			$fname = $_FILES["IMGPATH"]["name"][$i];

			/*$nmext = explode(".",$fname);
			$name1 = $nameImage.".".$nmext[1];*/
			
			list($usec, $sec) = explode(" ", microtime());
			list($sec2,$milli)	= explode(".", $usec);					
			$nameImage = $nameImage.$milli;
			$name1 = $nameImage.substr($fname,strripos($fname , "."));
			
			$uploadfile = $filePath."/" .basename($name1);
			$thumbfile = $filePath. "/thumb/" .$name1;
			
			if((!ereg("^image/jpeg",$type))and(!ereg("^image/gif",$type))and(!ereg("^image/x-png",$type))and(!ereg("^image/pjpeg",$type))){
				echo "alert(\" ERROR : upload ไม่ได้ Typeไม่ถูกต้อง  \")";
				//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ Typeไม่ถูกต้อง</h3></div>";
				die;
			}

		if($size > $genarate->image_maxsize){
			echo "alert(\" ERROR : upload ไม่ได้ขนาด file ใหญ่เกิน $genarate->image_maxsize  \")";
			//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ขนาด file ใหญ่เกิน $limit</h3></div>";
			die;
		}
		list($width,$heigth) = getimagesize($tmp_name);
		if($width>800){
			echo "alert(\" ERROR : upload ไม่ได้ขนาดความกว้าง file ใหญ่เกิน 800 pixel  \")";
			//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ขนาดความกว้าง file ใหญ่เกิน 800 pixel</h3></div>";
			die;
		}
		move_uploaded_file($tmp_name,"$uploadfile");//การย้ายไฟล์
		chmod("$uploadfile", 0777);
//----------------------------------------------------------------//
			$quality = 90;
			$border = 2; 
			$percent = $hconfig / $heigth;
			$wpercent = $width * $percent;
			$hpercent = $heigth * $percent;
				if($wpercent>$wconfig){
					$percent = $wconfig / $width;
					$wpercent = $width * $percent;
					$hpercent = $heigth * $percent;
				}

			$im = imageCreateTrueColor($wpercent+$border,$hpercent+$border);
			$borderColor1 = "200, 200, 200"; //RGB : Gray
			$borderColorArr = explode( ",", $borderColor1 );
			$borderColor = imagecolorallocate( $im, $borderColorArr[0], $borderColorArr[1], $borderColorArr[2] );
				imagefilledRectangle( $im, 0,0, $wpercent+$border, $hpercent+$border, $borderColor );
				imagefilledRectangle( $im, 1,1, $wpercent, $hpercent, $bgColor );
			
				if(ereg("^image/jpeg",$type)){
					$im1 = imageCreateFromjpeg($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);
				}elseif(ereg("^image/pjpeg",$type)){
					$im1 = imageCreateFromjpeg($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);	
				}elseif(ereg("^image/gif",$type)){
					$im1 = imageCreateFromgif($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);
				}elseif (ereg("^image/x-png",$type)){
					$im1 = imageCreateFrompng($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagepng($im,$thumbfile,$quality);
				}
			imagedestroy($im);
			imagedestroy($im1);
		}
			return $name1;
	//----------------------------------------------------------------//
	}//endif userflie=" "*/

}
function imagesarray1($IMGPATH1,$nameImage,$filePath,$wconfig,$hconfig,$i) { //การเก็บ image
global $genarate;
	if ($_FILES['IMGPATH1']["name"][$i] <> ""){
		if ($error ==0 ){
			$size = $_FILES["IMGPATH1"]["size"][$i];
			$type = $_FILES["IMGPATH1"]["type"][$i];
			$tmp_name = $_FILES["IMGPATH1"]["tmp_name"][$i];
			$fname = $_FILES["IMGPATH1"]["name"][$i];

			/*$nmext = explode(".",$fname);
			$name1 = $nameImage.".".$nmext[1];*/
			list($usec, $sec) = explode(" ", microtime());
			list($sec2,$milli)	= explode(".", $usec);					
			$nameImage = $nameImage.$milli;
			$name1 = $nameImage.substr($fname,strripos($fname , "."));
	
			$uploadfile = $filePath."/" .basename($name1);
			$thumbfile = $filePath. "/thumb/" .$name1;
			
			if((!ereg("^image/jpeg",$type))and(!ereg("^image/gif",$type))and(!ereg("^image/x-png",$type))and(!ereg("^image/pjpeg",$type))){
				echo "alert(\" ERROR : upload ไม่ได้ Typeไม่ถูกต้อง  \")";
				//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ Typeไม่ถูกต้อง</h3></div>";
				die;
			}

		if($size > $genarate->image_maxsize){
			echo "alert(\" ERROR : upload ไม่ได้ขนาด file ใหญ่เกิน $genarate->image_maxsize  \")";
			//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ขนาด file ใหญ่เกิน $limit</h3></div>";
			die;
		}
		list($width,$heigth) = getimagesize($tmp_name);
		if($width>800){
			echo "alert(\" ERROR : upload ไม่ได้ขนาดความกว้าง file ใหญ่เกิน 800 pixel  \")";
			//echo "<br><div align =center> <h3>ERROR : upload ไม่ได้ขนาดความกว้าง file ใหญ่เกิน 800 pixel</h3></div>";
			die;
		}
		move_uploaded_file($tmp_name,"$uploadfile");//การย้ายไฟล์
		chmod("$uploadfile", 0777);
//----------------------------------------------------------------//
			$quality = 90;
			$border = 2; 
			$percent = $hconfig / $heigth;
			$wpercent = $width * $percent;
			$hpercent = $heigth * $percent;
				if($wpercent>$wconfig){
					$percent = $wconfig / $width;
					$wpercent = $width * $percent;
					$hpercent = $heigth * $percent;
				}

			$im = imageCreateTrueColor($wpercent+$border,$hpercent+$border);
			$borderColor1 = "200, 200, 200"; //RGB : Gray
			$borderColorArr = explode( ",", $borderColor1 );
			$borderColor = imagecolorallocate( $im, $borderColorArr[0], $borderColorArr[1], $borderColorArr[2] );
				imagefilledRectangle( $im, 0,0, $wpercent+$border, $hpercent+$border, $borderColor );
				imagefilledRectangle( $im, 1,1, $wpercent, $hpercent, $bgColor );
			
				if(ereg("^image/jpeg",$type)){
					$im1 = imageCreateFromjpeg($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);
				}elseif(ereg("^image/pjpeg",$type)){
					$im1 = imageCreateFromjpeg($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);	
				}elseif(ereg("^image/gif",$type)){
					$im1 = imageCreateFromgif($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagejpeg($im,$thumbfile,$quality);
				}elseif (ereg("^image/x-png",$type)){
					$im1 = imageCreateFrompng($uploadfile);
					imageCopyResampled($im,$im1,1,1,0,0,$wpercent,$hpercent,$width,$heigth);
					imagepng($im,$thumbfile,$quality);
				}
			imagedestroy($im);
			imagedestroy($im1);
		}
			return $name1;
	//----------------------------------------------------------------//
	}//endif userflie=" "*/

}
?>