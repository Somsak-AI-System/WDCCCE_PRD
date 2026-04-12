<?
require_once("config.inc.php");
require_once("library/dbconfig.php");
require_once("library/generate_MYSQL.php");
require_once("phpmailer/class.phpmailer.php");
$generate = new generate($dbconfig ,"DB");
global $generate,$path,$client1;
### FUNCTION SEND MAIL ------------------------------------------------------------------------------------------------
function scriptdd_sendmail($to_name,$to_email,$from_name,$email_user_send,$email_pass_send,$subject,$body_html,$from_email) {
	$cc = 'tanate@aisyst.com';
	$mail= new PHPMailer();
	$mail-> From     = $from_email;
	$mail-> FromName = $from_name;
	$mail-> AddAddress($to_email,$to_name);
	$mail-> AddCC($cc,$cc);
	$mail-> Subject	= $subject;
	$mail-> Body		= $body_html;
	$mail-> IsHTML (true);
	$mail->IsSMTP();
	$mail->Host = 'mail.aisyst.com';
	$mail->Port = 25;
	//$mail->Host = 'mail.miyabigrill.com';
	//$mail->Port = 25;
	$mail->SMTPAuth		= true;
	$mail->Username = $email_user_send;
	$mail->Password = $email_pass_send;
	$mail->Send();
	$mail->ClearAddresses();
}
### FUNCTION SEND MAIL -----------------------------------------------------------------------------------------------
$path_1="D:/AppServ/www/SABN/";
$path_2="export_csv/";
$path_3="backup/";
$path=$path_1.$path_2;
$data_msg=array();
$data_msg_r=array();

	$file_type_chk="member";
	$FName="member.CSV";
	//echo $FName."<br>";exit;
	$FileName = $path.$FName;
	if(file_exists($FileName)) {
		//echo $FName."<br>";exit;
		$FileHandle = fopen($FileName, 'r+') or die("can't open file");
		$data_import = file($FileName);
		//print_r($data_import);
		fclose($FileHandle);
		$data_return=Add_Member($data_import,$FName,$FileName,"","",$s_date,$file_type_chk);
	//move file =======================================================================================================================
		$source = $path_1.$path_2.$FName;
		$destination =$path_1.$path_2.$path_3.$FName;
		if($data_return[1]!="4"){
			if(rename($source, $destination)) {
				echo "The file was moved successfully.", "\n";
			} else {
				echo "The specified file could not be moved. Please try again.", "\n";
			}		
		}
	}

function Add_Member($data_import,$file_name,$FileName,$file_type_O,$acc_type,$s_date,$file_type_chk){
	global $generate,$path,$client1;
	//echo $branch."<br>";
	$data_im_AC_Y=array();	
	$data_im_AC_Y_R=array();
	$data_im_AC_N=array();	
	$data_im_AC_N_R=array();
	
	$data_im_SO_Y=array();	
	$data_im_SO_Y_R=array();
	$data_im_SO_N=array();	
	$data_im_SO_N_R=array();
	
	$data_im_PO_Y=array();	
	$data_im_PO_Y_R=array();
	$data_im_PO_N=array();	
	$data_im_PO_N_R=array();
	$date=date('Y-m-d H:i:s');
	$total_record=0;
	
	//echo strtolower(substr($data_import[0],0,37));exit;
	if(count($data_import)>0 and strtolower(substr($data_import[0],0,37))=="mb_id|:|mb_idcard|:|mb_prename|:|mb_f"){
		$total_record=count($data_import)-1;
		//echo $total_record;
		for($k=1;$k<count($data_import);$k++){
			$data=explode("|:|",$data_import[$k]);
			if(count($data)>0){
				$MB_ID=iconv("UTF-8","TIS-620",str_replace("'","''",$data['0']));
				$MB_PASS="";
				$MB_REGIS_DT="";
				$MB_IDCARD=iconv("UTF-8","TIS-620",str_replace("'","''",$data['1']));
				$MB_PRENAME=iconv("UTF-8","TIS-620",str_replace("'","''",$data['2']));
				if($MB_PRENAME==iconv("utf-8","tis-620","นาง")){
					$MB_PRENAME_N=iconv("utf-8","tis-620","นาง / Mrs.");
				}else if($MB_PRENAME==iconv("utf-8","tis-620","นางสาว")){
					$MB_PRENAME_N=iconv("utf-8","tis-620","นางสาว / Ms.");
				}else if($MB_PRENAME==iconv("utf-8","tis-620","นาย")){
					$MB_PRENAME_N=iconv("utf-8","tis-620","นาย / Mr.");
				}else{
					$MB_PRENAME_N=iconv("utf-8","tis-620","คุณ");
				}
				$MB_FIRSTNAME=iconv("UTF-8","TIS-620",str_replace("'","''",$data['3']));
				$MB_LASTNAME=iconv("UTF-8","TIS-620",str_replace("'","''",$data['4']));
				$MB_GENDER=iconv("UTF-8","TIS-620",str_replace("'","''",$data['5']));
				if($MB_GENDER=="F"){
					$MB_GENDER_N=iconv("utf-8","tis-620","หญิง");
				}else{
					$MB_GENDER_N=iconv("utf-8","tis-620","ชาย");
				}
				$MB_BIRTHDATE=iconv("UTF-8","TIS-620",str_replace("'","''",$data['6']));
				$MB_BIRTHMONTH=iconv("UTF-8","TIS-620",str_replace("'","''",$data['7']));
				$MB_BIRTHYEAR=iconv("UTF-8","TIS-620",str_replace("'","''",$data['8']));
				$MB_BIRTHYEAR=$MB_BIRTHYEAR+543;
				$MB_MAIL=iconv("UTF-8","TIS-620",str_replace("'","''",$data['9']));
				$MB_NUMBER_ADDRESS=iconv("UTF-8","TIS-620",str_replace("'","''",$data['10']));
				$MB_BUILDING=iconv("UTF-8","TIS-620",str_replace("'","''",$data['11']));
				$MB_ROOM=iconv("UTF-8","TIS-620",str_replace("'","''",$data['12']));
				$MB_FLOOR=iconv("UTF-8","TIS-620",str_replace("'","''",$data['13']));
				$MB_CLUSTER=iconv("UTF-8","TIS-620",str_replace("'","''",$data['14']));
				$MB_VILLAGE=iconv("UTF-8","TIS-620",str_replace("'","''",$data['15']));
				$MB_SOI=iconv("UTF-8","TIS-620",str_replace("'","''",$data['16']));
				$MB_ROAD=iconv("UTF-8","TIS-620",str_replace("'","''",$data['17']));
				$MB_PLACE=iconv("UTF-8","TIS-620",str_replace("'","''",$data['18']));
				
				$MB_DISTRICT=iconv("UTF-8","TIS-620",str_replace("'","''",$data['19']));
				$MB_PROVINCE=iconv("UTF-8","TIS-620",str_replace("'","''",$data['20']));
				$MB_ACC_TYPE="Sabina Online";
				$MB_POSTCODE=iconv("UTF-8","TIS-620",str_replace("'","''",$data['21']));
				$MB_PHONE=iconv("UTF-8","TIS-620",str_replace("'","''",$data['22']));
				$MB_MOBILE=iconv("UTF-8","TIS-620",str_replace("'","''",$data['23']));
				$MB_MOBILE2="";
				$MB_BRANCH="";
				$MB_CUP="";
				$MB_DEP_STORE=iconv("UTF-8","TIS-620",str_replace("'","''",$data['24']));
				$MB_CUP_SIZE=iconv("UTF-8","TIS-620",str_replace("'","''",$data['25']));
				$MB_PRICE=iconv("UTF-8","TIS-620",str_replace("'","''",$data['26']));
				$UpdateDate=iconv("UTF-8","TIS-620",str_replace("'","''",$data['27']));

				$str=explode(" ",$UpdateDate);
				$str1=explode("/",$str[0]);
				//$cf_1318_n=($str1[2]-543)."-".$str1[1]."-".$str1[0];
				$UpdateDate_N=($str1[2])."-".$str1[1]."-".$str1[0]." ".$str[1];
				//echo $UpdateDate_N;exit;
				$sql="insert into tbp_member(
				MB_ID,file_name,MB_PASS,MB_REGIS_DT,MB_PRENAME,MB_PRENAME_N,MB_FIRSTNAME,MB_LASTNAME,MB_IDCARD,MB_BIRTHDATE,MB_NUMBER_ADDRESS,MB_BUILDING
				,MB_ROOM,MB_FLOOR,MB_CLUSTER,MB_VILLAGE,MB_SOI,MB_ROAD,MB_PLACE,MB_DISTRICT,MB_PROVINCE,MB_POSTCODE,MB_MOBILE,MB_MOBILE2
				,MB_PHONE,MB_MAIL,MB_ACC_TYPE,MB_DEP_STORE,MB_BRANCH,MB_CUP,MB_CUP_SIZE,MB_PRICE,MB_BIRTHMONTH,MB_BIRTHYEAR,MB_GENDER,UpdateDate,MB_GENDER_N,UpdateDate_N
				)values(
				'".$MB_ID."','".$file_name."','".$MB_PASS."','".$MB_REGIS_DT."','".$MB_PRENAME."','".$MB_PRENAME_N."','".$MB_FIRSTNAME."','".$MB_LASTNAME."','".$MB_IDCARD."','".$MB_BIRTHDATE."','".$MB_NUMBER_ADDRESS."','".$MB_BUILDING
				."','".$MB_ROOM."','".$MB_FLOOR."','".$MB_CLUSTER."','".$MB_VILLAGE."','".$MB_SOI."','".$MB_ROAD."','".$MB_PLACE."','".$MB_DISTRICT."','".$MB_PROVINCE."','".$MB_POSTCODE."','".$MB_MOBILE."','".$MB_MOBILE2
				."','".$MB_PHONE."','".$MB_MAIL."','".$MB_ACC_TYPE."','".$MB_DEP_STORE."','".$MB_BRANCH."','".$MB_CUP."','".$MB_CUP_SIZE."','".$MB_PRICE."','".$MB_BIRTHMONTH."','".$MB_BIRTHYEAR."','".$MB_GENDER."','".$UpdateDate."','".$MB_GENDER_N."','".$UpdateDate_N."'
				)";
				//echo $sql;exit;
//check log import tmp============================================================================================================================					
				if($generate->query($sql)){
					
				}
			}
		}//for end import
		
//check log import tmp============================================================================================================================	=				
//end import====================================================================================================================================
		$sql="SELECT id FROM tbp_member WHERE 1 and status=0  and file_name='".$file_name."' ";	
		$data_chk =$generate->process($sql,"all");
		//echo count($data_chk)."<br>";exit;
		for($i=0;$i<count($data_chk);$i++){
			$sql="SELECT * FROM tbp_member WHERE 1 and status=0  and file_name='".$file_name."' and id='".$data_chk[$i]['id']."'";	
			$data1 =$generate->process($sql,"all");
			//print_r();
			$ID=$data1[0]['id'];
			$MB_ID=$data1[0]['MB_ID'];
			$MB_IDCARD=$data1[0]['MB_IDCARD'];
			$MB_PASSH="sabina1234";
			$MB_PRENAME=$data1[0]['MB_PRENAME'];
			$MB_PRENAME_N=$data1[0]['MB_PRENAME_N'];
			$MB_FIRSTNAME=$data1[0]['MB_FIRSTNAME'];
			$MB_LASTNAME=$data1[0]['MB_LASTNAME'];
			$MB_GENDER_N=$data1[0]['MB_GENDER_N'];
			$MB_BIRTHDATE=$data1[0]['MB_BIRTHDATE'];
			$MB_BIRTHMONTH=$data1[0]['MB_BIRTHMONTH'];
			$MB_BIRTHYEAR=$data1[0]['MB_BIRTHYEAR'];
			$MB_MAIL=$data1[0]['MB_MAIL'];
			$MB_NUMBER_ADDRESS=$data1[0]['MB_NUMBER_ADDRESS'];
			$MB_BUILDING=$data1[0]['MB_BUILDING'];
			$MB_ROOM=$data1[0]['MB_ROOM'];
			$MB_FLOOR=$data1[0]['MB_FLOOR'];
			$MB_CLUSTER=$data1[0]['MB_CLUSTER'];
			$MB_VILLAGE=$data1[0]['MB_VILLAGE'];
			$MB_SOI=$data1[0]['MB_SOI'];
			$MB_ROAD=$data1[0]['MB_ROAD'];
			$MB_PLACE=$data1[0]['MB_PLACE'];
			$MB_DISTRICT=$data1[0]['MB_DISTRICT'];
			$MB_PROVINCE=$data1[0]['MB_PROVINCE'];
			$MB_POSTCODE=$data1[0]['MB_POSTCODE'];
			$MB_PHONE=$data1[0]['MB_PHONE'];
			$MB_MOBILE=$data1[0]['MB_MOBILE'];
			$MB_DEP_STORE=$data1[0]['MB_DEP_STORE'];
			$MB_CUP_SIZE=$data1[0]['MB_CUP_SIZE'];
			$MB_PRICE=$data1[0]['MB_PRICE'];
			$UpdateDate_N=$data1[0]['UpdateDate_N'];

			//import Account=====================================================================================
			$sql=" 
			select 
			aicrm_account.accountid
			,bill_street
			,cf_1348
			,cf_1331
			,cf_1333
			,cf_1340
			,cf_1337
			,cf_1336
			,bill_code
			,cf_1215,cf_1216,cf_1217,cf_1218,cf_1219,cf_1221,cf_1222,cf_1224,cf_1225,cf_1226,cf_1227,cf_1228,cf_1309,cf_1310,cf_1313,cf_1314,cf_1315,cf_1316,cf_965
			from aicrm_account 
			left join aicrm_accountscf on aicrm_accountscf.accountid=aicrm_account.accountid
			left join aicrm_accountbillads on aicrm_accountbillads.accountaddressid=aicrm_account.accountid
			left join aicrm_accountshipads on aicrm_accountshipads.accountaddressid=aicrm_account.accountid
			left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
			where 1
			and aicrm_crmentity.deleted=0 
			and cf_961='".$MB_ID."' 
			";
			//echo $sql;exit;
			$data_acc =$generate->process($sql,"all");
			//print_r($data_p );//exit;
			if(count($data_acc)>0){
				$data_im_AC_N_R=array(
					'module' => "Accounts", //version
					'member_no' => $MB_ID, //version
					'member_name' => $MB_FIRSTNAME." ".$MB_LASTNAME,
					'branch'=>$MB_DEP_STORE,
					'msg' => "Update data."
				);	
				$data_im_AC_N[]=$data_im_AC_N_R;
				$accountid=$data_acc[0]['accountid'];
				$sql="update aicrm_account set accountname='".$MB_FIRSTNAME." ".$MB_LASTNAME."'
				,email1='".$MB_MAIL."' where accountid='".$accountid."'
				";
				$generate->query($sql);
				$sql="update aicrm_accountscf set
				cf_961='".$MB_ID."'
						
				,cf_1214='".$MB_PRENAME_N."'
				,cf_955='".$MB_FIRSTNAME."'
				,cf_768='".$MB_LASTNAME."'
				,cf_952='".$MB_IDCARD."'
				,cf_1439='".$MB_BIRTHDATE."'
				,cf_1359='".$MB_NUMBER_ADDRESS."'
				,cf_1360='".$MB_BUILDING."'
				,cf_1361='".$MB_ROOM."'
				,cf_1362='".$MB_FLOOR."'
				,cf_1348='".$MB_CLUSTER."'
				,cf_1363='".$MB_VILLAGE."'
				,cf_1331='".$MB_SOI."'
				,cf_1333='".$MB_ROAD."'
				,cf_1340='".$MB_PLACE."'
				,cf_1337='".$MB_DISTRICT."'
				,cf_1336='".$MB_PROVINCE."'
				,cf_957='".$MB_MOBILE."'
				,cf_1312='".$MB_MOBILE2."'
				,cf_956='".$MB_PHONE."'
				,cf_1308='".$MB_ACC_TYPE."'						
				,cf_1434='".$MB_CUP_SIZE."'
				,cf_1440='".$MB_BIRTHMONTH."'
				,cf_1441='".$MB_BIRTHYEAR."'
				,cf_1401='".$MB_GENDER_N."'
				where accountid='".$accountid."'
				";
				$generate->query($sql);
				$sql="update aicrm_accountbillads set bill_code='".$MB_POSTCODE."' where accountaddressid='".$accountid."'";
				$generate->query($sql);
				$address=$data_acc[0]['bill_street'];
				$moo=$data_acc[0]['cf_1348'];
				$soi=$data_acc[0]['cf_1331'];
				$road=$data_acc[0]['cf_1333'];
				$tambon=$data_acc[0]['cf_1340'];
				$amphur=$data_acc[0]['cf_1337'];
				$province=$data_acc[0]['cf_1336'];
				$postcode=$data_acc[0]['bill_code'];
				$cf_1215=$data_acc[0]['cf_1215'];
				$cf_1216=$data_acc[0]['cf_1216'];
				$cf_1217=$data_acc[0]['cf_1217'];
				$cf_1218=$data_acc[0]['cf_1218'];
				$cf_1219=$data_acc[0]['cf_1219'];
				$cf_1221=$data_acc[0]['cf_1221'];
				$cf_1222=$data_acc[0]['cf_1222'];
				$cf_1224=$data_acc[0]['cf_1224'];
				$cf_1225=$data_acc[0]['cf_1225'];
				$cf_1226=$data_acc[0]['cf_1226'];
				$cf_1227=$data_acc[0]['cf_1227'];
				$cf_1228=$data_acc[0]['cf_1228'];
				$cf_1309=$data_acc[0]['cf_1309'];
				$cf_1310=$data_acc[0]['cf_1310'];
				$cf_1313=$data_acc[0]['cf_1313'];
				$cf_1314=$data_acc[0]['cf_1314'];
				$cf_1315=$data_acc[0]['cf_1315'];
				$cf_1316=$data_acc[0]['cf_1316'];
				$start_member=$data_acc[0]['cf_965'];
				$sql="update tbp_member set status=1,import_flg='Y',accountid='".$accountid."'  where id='".$ID."'";
				$generate->query($sql);
			}else{
				$sql=" select (id+1)as id from aicrm_crmentity_seq ";
				$id_seq = $generate->process($sql,"all");	
				$cid=$id_seq[0]['id'];
				$accountid=$cid;
				$address="";
				$moo="";
				$soi="";
				$road="";
				$tambon="";
				$amphur="";
				$province="";
				$postcode="";
				$cf_1218=0;
				$cf_1219=0;
				$cf_1221=0;
				$cf_1222="";
				$cf_1224="";
				$cf_1225=0;
				$cf_1226=0;
				$cf_1227=0;
				$cf_1228=0;
				$cf_1309=0;
				$cf_1310='';
				$cf_1313=0;
				$cf_1314=0;
				$cf_1315=0;
				$cf_1316=0;
				$start_member=$UpdateDate_N;
				$sql1 = "insert into  aicrm_crmentity  (crmid,smcreatorid,smownerid,setype,createdtime,modifiedtime,version,presence,deleted) 
				values ('".$cid."','1','1','Accounts','".$UpdateDate_N."','".$UpdateDate_N."','0','1','0')";
				//echo $sql1."<br>";
				$generate->query($sql1);
				
				//aicrm_crmentity_seq
				$sql2 = "update  aicrm_crmentity_seq set id='".$cid."'";
				//echo $sql2; 
				$generate->query($sql2);				
				
				$sql_num=" select prefix,cur_id from aicrm_modentity_num  where num_id='2'";
				$result_num = $generate->process($sql_num,"all");	
				$proid=$result_num[0]['prefix'].$result_num[0]['cur_id'];
				
				//aicrm_account
				$sql3 = "insert into  aicrm_account  (accountid,account_no,accountname,email1)values('".$cid."','".$proid."','".$MB_FIRSTNAME." ".$MB_LASTNAME."','".$MB_MAIL."')";
				//echo $sql3."<br>";
				$generate->query($sql3);
				
				//aicrm_accountscf
				$sql4 = "insert into  aicrm_accountscf  (accountid,cf_961,cf_1213,cf_965,cf_1214,cf_955,cf_768,cf_952,cf_1439,cf_1359,
						cf_1360,cf_1361,cf_1362,cf_1348,cf_1363,cf_1331,cf_1333,cf_1340,
						cf_1337,cf_1336,cf_957,cf_1312,cf_956,cf_1308,cf_1215,cf_1216,
						cf_1217,cf_1434,cf_1218,cf_1440,cf_1441,cf_1401
				,cf_1432)
				values('".$cid."','".$MB_ID."','".$MB_PASSH."','".$UpdateDate_N."','".$MB_PRENAME_N."','".$MB_FIRSTNAME."','".$MB_LASTNAME."','".$MB_IDCARD."'
,'".$MB_BIRTHDATE."','".$MB_NUMBER_ADDRESS."','".$MB_BUILDING."','".$MB_ROOM."','".$MB_FLOOR."','".$MB_CLUSTER."'
,'".$MB_VILLAGE."','".$MB_SOI."','".$MB_ROAD."','".$MB_PLACE."','".$MB_DISTRICT."','".$MB_PROVINCE."','".$MB_MOBILE."'
,'".$MB_MOBILE2."','".$MB_PHONE."','".$MB_ACC_TYPE."','".$MB_DEP_STORE."','".$MB_BRANCH."','".$MB_CUP."','".$MB_CUP_SIZE."'
,'".$MB_PRICE."','".$MB_BIRTHMONTH."','".$MB_BIRTHYEAR."','".$MB_GENDER_N."'
				,'".$UpdateDate_N."')";
				//echo $sql4."<br>";
				$generate->query($sql4);
		
				//aicrm_accountbillads
				$sql5 = "insert into  aicrm_accountbillads  (accountaddressid,bill_code)values('".$cid."','".$MB_POSTCODE."')";
				//echo $sql5;
				$generate->query($sql5);
				
				//aicrm_accountshipads
				$sql6= "insert into  aicrm_accountshipads  (accountaddressid )values('".$cid."')";
				//echo $sql6."<br>";
				//exit;
				$generate->query($sql6);	
				
				$sql7 = "update  aicrm_modentity_num set cur_id='".($result_num[0]['cur_id']+1)."' where num_id='2'";
				$generate->query($sql7);	
				
				$sql="update tbp_member set status=1,import_flg='Y',accountid='".$accountid."' where id='".$ID."'";
				$generate->query($sql);	
			}
		}//for
		$msg="";
		$flg="3";
		return array($msg,$flg,$total_record,$data_im_AC_Y,$data_im_AC_N,$data_im_SO_Y,$data_im_SO_N,$data_im_PO_Y,$data_im_PO_N);			
	}
}
?>