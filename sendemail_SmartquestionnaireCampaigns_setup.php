<?
require_once ("config.inc.php");
//include("C:/AppServ/www/mjdp/config.inc.php");
global $path,$url_path;

ini_set('memory_limit', '4024M');

$path=$root_directory;//"C:/AppServ/www/mjdp";
$url_path=$site_URL;//"http://".$_SERVER['HTTP_HOST']."/mjdp";
//		echo $url_path;exit;
require_once ($path."library/dbconfig.php");
require_once ($path."library/genarate.inc.php");
require_once ($path."library/myFunction.php");
require_once ($path."lib/swift_required.php");
require_once ($path."phpmailer/class.phpmailer.php");

//$genarate = new genarate($dbconfig ,"DB");
$genarate = new genarate($dbconfig ,"DB");

//get email====================================================================================
$date=date('Y-m-d H:i:'."01");
$date=date('Y-m-d H:i:s',mktime(date('H'), date('i')+10, 1, date('m'), date('d'), date('Y')));
function getInbetweenStrings($start, $end, $str){
    $matches = array();
    $regex = "/\\". $start ."([a-zA-Z0-9_.]*)\\". $end ."/";
    preg_match_all($regex, $str, $matches);
    return $matches[1];
}
//Select แบบมี crmid ส่งเข้ามา เช็ค active
$systax = '$';
$crmid = @$_REQUEST["crmid"];
if($crmid!=""){
    $sql="
		select aicrm_smartquestionnaire. *
		from aicrm_smartquestionnaire
		left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_smartquestionnaire.smartquestionnaireid
		where aicrm_smartquestionnaire.smartquestionnaireid='".$crmid."'
		and aicrm_smartquestionnaire.email_status='Prepare'
		and aicrm_crmentity.deleted =0
		and aicrm_smartquestionnaire.email_setup=0
		";
    //Select แบบไม่มี crmid ส่งเข้ามา เช็ค active (Run Schedule)
}else{
    $sql="
		  SELECT aicrm_smartquestionnaire.* 
		  FROM aicrm_smartquestionnaire
	      LEFT JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_smartquestionnaire.smartquestionnaireid 
          WHERE aicrm_smartquestionnaire.email_status='Prepare'
          AND aicrm_crmentity.deleted = 0 
          AND aicrm_smartquestionnaire.email_setup = 0 
          AND NOW( ) BETWEEN ( DATE_ADD( cast( concat( `email_start_date`, ' ', `email_start_time` ) AS datetime ), INTERVAL - 5 MINUTE ) ) 
          AND ( DATE_ADD( cast( concat( `email_start_date`, ' ', `email_start_time` ) AS datetime ), INTERVAL 5 MINUTE ) )";
}

$campaign = $genarate->process($sql,"all");
//echo '<pre>'; print_r($sql); echo '</pre>';exit;

for($i=0;$i<count($campaign);$i++){
    //Set ตัวแฟร
    $new_table="tbt_email_log_smartquestionnaireid_".$campaign[$i][0];
    $date_start=$campaign[$i][5]." ".$campaign[$i][6];
    $campaignid=$campaign[$i][0];
    $campaign_name=$campaign[$i][2];

//		print_r($campaign[$i][14]);exit();


    //Create New Table  --message text NOT NUll,
    $sql="
		CREATE TABLE ".$new_table." (
		id int(11) NOT NULL auto_increment,
		campaignid int(19) NOT NULL,
		email_marketingid int(19) NOT NULL,
		emailtargetlistid int(19) NOT NULL,
		from_module varchar(250) NOT NULL,
		from_id int(19) NOT NULL,
		to_name varchar(250) NOT NULL,
		to_email varchar(250) NOT NULL,
		to_email_old varchar(250) NOT NULL,
		message TEXT  NOT NUll,
		domain_name varchar(250) NOT NULL,
		date_start datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'เวลาเริ่มส่ง',
		status int(1) default '0' COMMENT '(3,4)=ยังไม่ได้ส่ง,(1)=ส่งแล้ว,(0)=เมล์นี้มีปัญหา,(2)=ส่งแล้วมีปัญหา',
		mistype_email int(20) default '0' ,
		invalid_email int(20) default '0' ,
		active int(20) default '0' COMMENT 'ใช้งานหรือไม่ใช้งาน',
		report int(20) default '0' COMMENT 'ออกรายงานหรือไม่ออก',
		duplicate int(20) default '0' COMMENT 'อีเมล์ซ้ำหรือไม่',
		unsubscribe int(20) default '0' COMMENT 'งดรับเมล์',
		unsubscribe_date datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'วันยกเลิกข่าวสาร',
		check_send int(20) default '0' ,
		group_send int(20) default '0' ,
		PRIMARY KEY  (id,campaignid),
		KEY id (id),
		KEY campaignid (campaignid),
		KEY email_marketingid (email_marketingid),
		KEY emailtargetlistid (emailtargetlistid)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		";

    //Check ถ้ามี Table อยู่แล้ว  TRUNCATE TABLE
    if($genarate->query($sql,"all")){
    }else{
        $sql="TRUNCATE TABLE ".$new_table."";
        $genarate->query($sql,"all");
    }
    //Check Path Folder
    $folder_name=date('Ymd',strtotime($campaign[$i][5]))."_".$campaign[$i][0];
    if (!file_exists($path."/EDM/".$folder_name)){
        if (!mkdir($path."/EDM/".$folder_name, 0777, true)){}
    }

    //Set ตัวแปร Body
    $email_body = $campaign[$i][4];
    //Check ตัวแปรที่ส่งแบบ Presonal ($-------$) ครอบหน้าหลัง
    $str_arr = getInbetweenStrings($systax, $systax, $campaign[$i][4]);

    $replace_arr = array();

    /*--/////////////////เพิ่ม data table แบ่ง group-personal //////////////////////////////--*/

    $massage=$campaign[$i][7];
    if(strstr($massage,$systax))
    {
        //personal
        $sql="update aicrm_smartquestionnaire set email_type = 'personal' where smartquestionnaireid=".$crmid."";
        $genarate->query($sql,"all");
    }else{
        //group
        $sql="update aicrm_smartquestionnaire set email_type = 'group' where smartquestionnaireid=".$crmid."";
        $genarate->query($sql,"all");
    }

    /*--////////////////////////////////////////////////////////////////////////////////////--*/
    //Check Array ที่เอาไป Convet มี ,ไม่มี
    if(empty($str_arr))
    {
        /*$sql="
        insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message,unsubscribe)
        select
        '".$campaign[$i][0]."',
        '0',
        '0',
        b.contactid as id,
        b.firstname as name,
        TRIM(REPLACE(b.email,'\n','')) as email,
        'Contacts' as module,
        '0'
        ,'".$email_body."'
        ,CASE WHEN b.emailstatus = 'InActive' THEN 1 ELSE 0 END
        from aicrm_smartemail_contactsrel a
        left join aicrm_contactdetails b on a.contactid=b.contactid
        left join aicrm_contactscf c on b.contactid=c.contactid
        left join aicrm_crmentity d on d.crmid=b.contactid
        where 1
        and d.deleted=0
        and a.smartemailid='".$campaign[$i][0]."'

        ";
        echo $sql;exit;
        //group by b.email
        $genarate->query($sql,"all");*/

        //Insert Opportunity======================================
        /*$sql="
        insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message,unsubscribe)
        select
        '".$campaign[$i][0]."',
        '0',
        '0',
        b.opportunityid as id,
        b.opportunity_name as name,
        TRIM(REPLACE(b.email,'\n','')) as email,
        'Opportunity' as module,
        '0'
        ,'".$email_body."'
        ,CASE WHEN b.emailstatus = 'InActive' THEN 1 ELSE 0 END
        from aicrm_smartemail_opportunityrel a
        left join aicrm_opportunity  b on a.opportunityid=b.opportunityid
        left join aicrm_opportunitycf c on b.opportunityid=c.opportunityid
        left join aicrm_crmentity d on d.crmid=b.opportunityid
        where 1
        and d.deleted=0
        and a.smartemailid='".$campaign[$i][0]."'
        ";
        //b.email as email,
        //group by b.email
        $genarate->query($sql,"all");*/

        //Insert Lead======================================
        $sql="
			insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message,unsubscribe)
			select 
			'".$campaign[$i][0]."',
			'0',
			'0',
			b.leadid as id,
			concat(b.firstname,' ',b.lastname) as name,
			TRIM(REPLACE(b.email,'\n','')) as email,
			'Leads' as module,
			'0'
			,'".$email_body."'
			,CASE WHEN b.emailstatus = 'InActive' THEN 1 ELSE 0 END
			from aicrm_smartquestionnaire_leadsrel a
			left join aicrm_leaddetails  b on a.leadid=b.leadid
			left join aicrm_leadscf c on b.leadid=c.leadid
			left join aicrm_crmentity d on d.crmid=b.leadid
			where 1
			and d.deleted=0
			and a.smartquestionnaireid='".$campaign[$i][0]."'
			";
//			echo $sql;exit;
        //b.email as email,
        //group by b.email
        $genarate->query($sql,"all");
        //Insert Account======================================
        $sql="
			select 
			b.accountid as id,
			b.accountname as name,
			TRIM(REPLACE(email1,'\n','')) as email,
			'Accounts' as module
			,CASE WHEN b.emailstatus = 'InActive' THEN 1 ELSE 0 END 
			from aicrm_smartquestionnaire_accountsrel a
			left join aicrm_account  b on a.accountid=b.accountid
			left join aicrm_accountscf c on b.accountid=c.accountid
			left join aicrm_crmentity d on d.crmid=b.accountid
			where 1
			and d.deleted=0
			and a.smartquestionnaireid='".$campaign[$i][0]."'
			";

        $questionnaiertemplateid = $campaign[$i][3];
        $dataacc = $genarate->process($sql,"all");
        for($k=0;$k<count($dataacc);$k++){
            $email=split(",",$dataacc[$k][2]);
            for($w=0;$w<count($email);$w++){
                if($email[$w] !=""){
                    $message = '<a href="'.$url_path.'/survey/home/questionnaire_answer/'.$questionnaiertemplateid.'/'.$dataacc[$k][0].'/'.$campaignid.'">คลิกเพื่อทำการตอบแบบสอบถาม</a><br>'.$email_body;
                    $sql="
						insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message,unsubscribe)
						values('".$campaign[$i][0]."','0','0','".$dataacc[$k][0]."','".$dataacc[$k][1]."','".trim(str_replace("\n", '', $email[$w]))."','".$dataacc[$k][3]."','0','".$message."','".$dataacc[$k][4]."')";
                    $genarate->query($sql,"all");
                }else{
                    $message = '<a href="'.$url_path.'/survey/home/questionnaire_answer/'.$questionnaiertemplateid.'/'.$dataacc[$k][0].'/'.$campaignid.'">คลิกเพื่อทำการตอบแบบสอบถาม</a><br>'.$email_body;
                    $sql="
						insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message,unsubscribe)
						values('".$campaign[$i][0]."','0','0','".$dataacc[$k][0]."','".$dataacc[$k][1]."','".trim(str_replace("\n", '', $email[$w]))."','".$dataacc[$k][3]."','0','".$message."','".$dataacc[$k][4]."')";
                    $genarate->query($sql,"all");
                }//if
            }
        }

        //Insert Users======================================
        // $sql="
        // select
        //     aicrm_users.id,
        //     concat( aicrm_users.first_name, ' ', aicrm_users.last_name ) AS NAME,
        //     TRIM(REPLACE(email1,'\n','')) AS email,
        //     /*TRIM(REPLACE(email2,'\n','')) AS email2,*/
        //     'Users' AS module,
        //     CASE WHEN aicrm_users.status = 'InActive' THEN 1 ELSE 0 END
        // from aicrm_users
        // INNER JOIN aicrm_smartquestionnaire_usersrel ON aicrm_smartquestionnaire_usersrel.id = aicrm_users.id
        // LEFT JOIN aicrm_user2role ON aicrm_user2role.userid = aicrm_users.id
        // LEFT JOIN aicrm_role ON aicrm_role.roleid = aicrm_user2role.roleid
        // where 1
        // and aicrm_smartquestionnaire_usersrel.smartquestionnaireid='".$campaign[$i][0]."'
        // ";

        // $datausers = $genarate->process($sql,"all");

        // for($k=0;$k<count($datausers);$k++){
        //     $email=split(",",$datausers[$k][2]);
        //     for($w=0;$w<count($email);$w++){


        //         if($email[$w] !=""){
        //             $sql="
        // 			insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message,unsubscribe)
        // 			values('".$campaign[$i][0]."','0','0','".$datausers[$k][0]."','".$datausers[$k][1]."','".trim(str_replace("\n", '', $email[$w]))."','".$datausers[$k][3]."','0','".$email_body."','".$datausers[$k][4]."')";
        //             $genarate->query($sql,"all");
        //         }else{
        //             $sql="
        // 			insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message,unsubscribe)
        // 			values('".$campaign[$i][0]."','0','0','".$datausers[$k][0]."','".$datausers[$k][1]."','".trim(str_replace("\n", '', $email[$w]))."','".$datausers[$k][3]."','0','".$email_body."','".$datausers[$k][4]."')";
        //             $genarate->query($sql,"all");
        //         }
        //     }
        // }

    }//if
    else{ // Got map field in email content

        foreach($str_arr as $arr){

            $ex = explode('.', $arr);

            if(in_array($ex[0], array('aicrm_account','aicrm_accountscf','aicrm_crmentity'))){
                $sql = 'select aicrm_account.accountid, '. $arr .'
									from aicrm_smartquestionnaire_accountsrel
									left join aicrm_account on aicrm_smartquestionnaire_accountsrel.accountid=aicrm_account.accountid
									left join aicrm_accountscf on aicrm_account.accountid=aicrm_accountscf.accountid
									left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_account.accountid
									where 1 and aicrm_crmentity.deleted=0
									and aicrm_smartquestionnaire_accountsrel.smartquestionnaireid='.$campaign[$i][0];

                $res = $genarate->process($sql, "all");
                foreach($res as $rs){
                    $replace_arr[$rs[0]]['module'] = 'Accounts';
                    $replace_arr[$rs[0]][$systax.$arr.$systax] = $rs[1];
                }
            }else if(in_array($ex[0], array('aicrm_contactdetails','aicrm_crmentity','aicrm_contactaddress','aicrm_contactscf'))){
                $sql = 'select aicrm_contactdetails.contactid, '. $arr .'
									from aicrm_smartemail_contactsrel
									inner join aicrm_contactdetails on aicrm_smartemail_contactsrel.contactid = aicrm_contactdetails.contactid
									left join aicrm_contactaddress on  aicrm_contactdetails.contactid=aicrm_contactaddress.contactaddressid 
									left join aicrm_contactscf on aicrm_contactdetails.contactid = aicrm_contactscf.contactid
									left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_contactdetails.contactid
									where 1 and aicrm_crmentity.deleted=0 and aicrm_contactdetails.email<>"" and aicrm_smartemail_contactsrel.smartemailid='.$campaign[$i][0];

                $res = $genarate->process($sql, "all");
                foreach($res as $rs){
                    $replace_arr[$rs[0]]['module'] = 'Contacts';
                    $replace_arr[$rs[0]][$systax.$arr.$systax] = $rs[1];
                }
            }else if(in_array($ex[0], array('aicrm_leaddetails','aicrm_leadscf','aicrm_crmentity'))){
                $sql = 'select aicrm_leaddetails.leadid, '. $arr .'
									from aicrm_smartquestionnaire_leadsrel
									left join aicrm_leaddetails on aicrm_smartquestionnaire_leadsrel.leadid = aicrm_leaddetails.leadid
									left join aicrm_leadscf on aicrm_leaddetails.leadid = aicrm_leadscf.leadid
									left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
									where 1
									and aicrm_crmentity.deleted=0
									and aicrm_smartquestionnaire_leadsrel.smartquestionnaireid='.$campaign[$i][0];

                $res = $genarate->process($sql, "all");
                foreach($res as $rs){
                    $replace_arr[$rs[0]]['module'] = 'Leads';
                    $replace_arr[$rs[0]][$systax.$arr.$systax] = $rs[1];
                }
            }else if(in_array($ex[0], array('aicrm_opportunity','aicrm_opportunitycf','aicrm_crmentity'))){
                $sql = 'select aicrm_opportunity.opportunityid, '. $arr .'
								from aicrm_smartemail_opportunityrel
								left join aicrm_opportunity on aicrm_smartemail_opportunityrel.opportunityid = aicrm_opportunity.opportunityid
								left join aicrm_opportunitycf on aicrm_opportunity.opportunityid = aicrm_opportunitycf.opportunityid
								left join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_opportunity.opportunityid
								where 1
								and aicrm_crmentity.deleted=0
								and aicrm_smartemail_opportunityrel.smartemailid='. $campaign[$i][0];

                $res = $genarate->process($sql, "all");
                foreach($res as $rs){
                    $replace_arr[$rs[0]]['module'] = 'Opportunity';
                    $replace_arr[$rs[0]][$systax.$arr.$systax] = $rs[1];
                }
            }
        }






        foreach($replace_arr as $id => $a){
            //echo $id.' => '. $a['module'];
            $find       = array_keys($a);
            $replace    = array_values($a);
            $new_string = str_ireplace($find, $replace, $campaign[$i][4]);

            $body = $new_string;

            switch($a['module']){
                case'Users':
                    $sql_user="
								select '". $id ."' as id,
								b.accountname,
								TRIM(REPLACE(b.email1,'\n','')) as email1,
								'Accounts' as module
								,CASE WHEN b.emailstatus = 'InActive' THEN 1 ELSE 0 END 
								from aicrm_users
								INNER JOIN aicrm_smartquestionnaire_usersrel ON aicrm_smartquestionnaire_usersrel.id = aicrm_users.id
								LEFT JOIN aicrm_user2role ON aicrm_user2role.userid = aicrm_users.id
								LEFT JOIN aicrm_role ON aicrm_role.roleid = aicrm_user2role.roleid
								where 1
								and aicrm_smartquestionnaire_usersrel.smartquestionnaireid='". $campaign[$i][0] ."'
								and aicrm_users.id='". $id ."' ";

                    $datausers = $genarate->process($sql_user, "all");

                    for($k=0;$k<count($datausers);$k++){
                        $email=split(",",$dataacc[$k][2]);
                        for($w=0;$w<count($email);$w++){
                            if($email[$w]!=""){
                                $sql="
											insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message,unsubscribe)
											values('".$campaign[$i][0]."','0','0','".$datausers[$k][0]."','".$datausers[$k][1]."','".trim(str_replace("\n", '', $email[$w]))."','".$datausers[$k][3]."','0','".$new_string."','".$datausers[$k][4]."')";
                                // echo $sql;
                                $genarate->query($sql,"all");
                            }/*else{
											$sql="
											insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message,unsubscribe)
											values('".$campaign[$i][0]."','0','0','".$datausers[$k][0]."','".$datausers[$k][1]."','".trim(str_replace("\n", '', $email[$w]))."','".$datausers[$k][3]."','4','".$new_string."','".$datausers[$k][4]."')";
											$genarate->query($sql,"all");
										}//if		*/
                        }
                    }
                    break;
                case'Accounts':
                    $sql_acc="
								select '". $id ."' as id,
								b.accountname,
								TRIM(REPLACE(b.email1,'\n','')) as email1,
								'Accounts' as module
								,CASE WHEN b.emailstatus = 'InActive' THEN 1 ELSE 0 END 
								from aicrm_smartquestionnaire_accountsrel a
								left join aicrm_account b on a.accountid=b.accountid
								left join aicrm_accountscf c on b.accountid=c.accountid
								left join aicrm_crmentity d on d.crmid=b.accountid
								where 1
								and d.deleted=0
								and a.smartquestionnaireid='". $campaign[$i][0] ."'
								and a.accountid='". $id ."' ";
                    //and b.emailstatus in('','Active')
                    //and b.email1 <> ''

                    $questionnaiertemplateid = $campaign[$i][3];
                    $dataacc = $genarate->process($sql_acc, "all");
                    for($k=0;$k<count($dataacc);$k++){
                        $email=split(",",$dataacc[$k][2]);
                        for($w=0;$w<count($email);$w++){
                            if($email[$w]!=""){
                                $message = '<a href="'.$url_path.'/survey/home/questionnaire_answer/'.$questionnaiertemplateid.'/'.$dataacc[$k][0].'/'.$campaignid.'">คลิกเพื่อทำการตอบแบบสอบถาม</a><br>'.$new_string;
                                $sql="
											insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message,unsubscribe)
											values('".$campaign[$i][0]."','0','0','".$dataacc[$k][0]."','".$dataacc[$k][1]."','".trim(str_replace("\n", '', $email[$w]))."','".$dataacc[$k][3]."','0','".$message."','".$dataacc[$k][4]."')";
                                // echo $sql;
                                $genarate->query($sql,"all");
                            }/*else{
											$sql="
											insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,from_module,status,message,unsubscribe)
											values('".$campaign[$i][0]."','0','0','".$dataacc[$k][0]."','".$dataacc[$k][1]."','".trim(str_replace("\n", '', $email[$w]))."','".$dataacc[$k][3]."','4','".$new_string."','".$dataacc[$k][4]."')";
											$genarate->query($sql,"all");
										}//if		*/
                        }
                    }
                    break;
                case'Contacts':
                    $sql="insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,message,from_module,status,unsubscribe)
								select
								'".$campaign[$i][0]."',
								'0', '0',
								'". $id ."',
								b.firstname as name,
								TRIM(REPLACE(b.email,'\n','')) as email,
								ifempty('". $new_string ."','".$email_body."'),
								'Contacts' as module,
								'0'
								,CASE WHEN b.emailstatus = 'InActive' THEN 1 ELSE 0 END
								from aicrm_smartemail_contactsrel a
								left join aicrm_contactdetails b on a.contactid=b.contactid
								left join aicrm_contactscf c on b.contactid=c.contactid
								left join aicrm_crmentity d on d.crmid=b.contactid
								where 1
								and d.deleted=0
								and a.smartemailid='".$campaign[$i][0]."'
								and a.contactid='". $id ."' ";
                    //and b.email<>''
                    $genarate->query($sql,"all");
                    break;
                case'Leads':
                    $sql="insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,message,from_module,status,unsubscribe)
								select
								'".$campaign[$i][0]."',
								'0', '0',
								'". $id ."',
								concat(b.firstname,' ',b.lastname) as name,
								TRIM(REPLACE(b.email,'\n','')) as email,
								ifempty('". $new_string ."','".$email_body."'),
								'Leads' as module,
								'0'
								,CASE WHEN b.emailstatus = 'InActive' THEN 1 ELSE 0 END
								from aicrm_smartquestionnaire_leadsrel a
								left join aicrm_leaddetails  b on a.leadid=b.leadid
								left join aicrm_leadscf c on b.leadid=c.leadid
								left join aicrm_crmentity d on d.crmid=b.leadid
								where 1
								and d.deleted=0
								and a.smartquestionnaireid='".$campaign[$i][0]."'
								and a.leadid='". $id ."' ";
                    //and emailstatus in('','Active')
                    //and b.email<>''
                    $genarate->query($sql,"all");
                    break;
                case'Opportunity':
                    $sql = "insert into ".$new_table."(campaignid,email_marketingid,emailtargetlistid,from_id,to_name,to_email,message,from_module,status,unsubscribe)
								select
								'".$campaign[$i][0]."',
								'0', '0',
								'". $id ."',
								b.opportunity_name as name,
								TRIM(REPLACE(b.email,'\n','')) as email,
								ifempty('". $new_string ."','".$email_body."'),
								'Opportunity' as module,
								'0'
								,CASE WHEN b.emailstatus = 'InActive' THEN 1 ELSE 0 END
								from aicrm_smartemail_opportunityrel a
								left join aicrm_opportunity  b on a.opportunityid=b.opportunityid
								left join aicrm_opportunitycf c on b.opportunityid=c.opportunityid
								left join aicrm_crmentity d on d.crmid=b.opportunityid
								where 1
								and d.deleted=0
								and a.smartemailid='".$campaign[$i][0]."'
								and a.opportunityid='". $id ."' ";
                    //and emailstatus='Active'
                    //and b.email<>''
                    $genarate->query($sql,"all");
                    break;
            }
        }

    }//End if no map field

    //Select New Table ที่เอา Message ยัดเข้าไป


    //Select New Table ที่เอา Message ยัดเข้าไป
    $new_sql = "select * from ".$new_table;
    $record_smartemail = $genarate->process($new_sql,"all");
//	echo "<pre>"; print_r($record_smartemail);echo "</pre>"; exit;

    foreach($record_smartemail as $key => $val){
        $id=$val[0];
        $campaignid=$val[1];
        $email_marketingid=$val[2];
        $emailtargetlistid=$val[3];
        $from_module=$val[4];
        $from_id=$val[5];
        $to_name=$val[6];
        $body=$val[9];



        $folder_name=date('Ymd',strtotime($date_start))."_".$campaignid;
        $url_click_view="";
        $url_click_view=$url_path.'/EDM/update_click_edm.php?param=id1332'.$id.'1332campaignid1332'.$campaignid.'1332marketid1332'.$email_marketingid.'1332module1332'.$from_module.'1332crmid1332'.$from_id;
        $url_click_view.='1332link133211332click_type1332CLICKTYPE1332url1332'.$url_path.'EDM/'.$folder_name.'/'.$folder_name.'.php?id='.$id.'*campaignid='.$campaignid;
        $url_click_view.='*marketid='.$email_marketingid.'*module='.$from_module.'*crmid='.$from_id;
        //echo $url_click;exit;
        $url_click_link="";
        $url_click_link=$url_path.'/EDM/update_click_edm.php?param=id1332'.$id.'1332campaignid1332'.$campaignid.'1332marketid1332'.$email_marketingid.'1332module1332'.$from_module.'1332crmid1332'.$from_id;
        $url_click_link.='1332link133221332click_type1332CLICKTYPE1332url1332'.str_replace("&","*",$url_click);

        $url_un_sub="";
        $url_un_sub=$url_path.'/EDM/update_click_edm.php?param=id1332'.$id.'1332campaignid1332'.$campaignid.'1332marketid1332'.$email_marketingid.'1332module1332'.$from_module.'1332crmid1332'.$from_id;
        $url_un_sub.='1332link1332161332click_type1332CLICKTYPE1332url1332'.$url_path.'/EDM/unsub.php?id='.$id.'*campaignid='.$campaignid.'*marketid='.$email_marketingid.'*module='.$from_module.'*crmid='.$from_id;


        /*--/////////////////-เปลี่ยนภาษา TH และ EN-//////////////////////////////--*/

        if($campaign[$i][14]=="TH"){
            $language1 = "หากท่านไม่สามารถอ่านอีเมล์ฉบับนี้ได้ กรุณา ";
            $language2 = "คลิกที่นี่";
            $language3 = "หากท่านไม่ต้องการรับข่าวสารจากทางเราอีก";
            $language4 = "กรุณายกเลิกการรับข่าวสารที่นี่";
            $language5 = "";
        }else{
            $language1 = "If you can not read this email please.";
            $language2 = "Click here.";
            $language3 = "If you prefer not to receive email. Please ";
            $language4 = "Click Here";
            $language5 = "to unsubscribe";
            //If you prefer not to receive email. Please Click Here to unsubscribe
        }
        /*--////////////////////////////////////////////////////////////////////--*/

        $body_html="";
        //$body_html.=$send_email[$p][8];
        $body_html='
				<table align="center" class="mceItemTable" id="Table_01" border="0" cellspacing="0" cellpadding="0" width="800">
					<tbody>
						<tr>
							<td height="40" align="right">
							<div style="text-align: center; color: rgb(106, 106, 106); line-height: 20px; font-family: tahoma; font-size: 12px;" mce_style="font-size: 12px; line-height: 20px; font-family: tahoma; text-align: right; color:#6a6a6a;">'.$language1.'<a style=" color:#fd8103; font-family: tahoma; font-size: 12px;" target="_blank" href='.str_replace('CLICKTYPE','edm',$url_click_view).'>'.$language2.'</a> </div>
							</td>
						</tr>
						<tr>
						
						  <td style="font-family: tahoma; font-size: 12px; " mce_style="font-size:12px;font-family: tahoma;">
				';

        //print_r($body_html);exit();


        //$body_html.=$body;

//replace link======================================================================================
        $link_all = explode("href=", $body);
        $link_array=array();
        $link_array_chk=array();
        for($kkk=0;$kkk<count($link_all);$kkk++){
            $link_true="";
            $aPanel=$link_all[$kkk];
            $link_str= explode('" ',$aPanel);
            for($kk=0;$kk<count($link_str);$kk++){
                $link_name=str_replace('"',"",$link_str[$kk]);
                if(substr($link_name,0,4)=="http"){
                    $link_true=$link_name;
                    $link_true = explode(">", $link_true);
                    $link_array[]=$link_true[0];
                }
            }
        }
        $link_array_chk1 = array_unique($link_array);
        $link_array_chk = array_values($link_array_chk1);

        //$genarate->query($sql);
        $link_no=2;
        $link_rename="";
        $link_chk="";
        $body1= $body;
        $link_replace=array();
        rsort($link_array_chk);
        for($kkk=0;$kkk<count($link_array_chk);$kkk++){
            //$body1="";
            $link_rename="";
            $link_chk="";
            $link_chk=$link_array_chk[$kkk];
            $link_rename=$url_path."/EDM/update_click_edm.php?param=id1332IDD1332campaignid1332CAMPAIGNNN1332marketid1332MARKETTT1332module1332MODUES1332crmid1332CRMMM1332link1332".$link_no."1332click_type1332CLICKTYPE1332url1332".str_replace("&","||||",$link_chk);
            $link_replace[]=$link_rename;
            //$body1=str_replace($link_chk,"xxxxx".$link_no."xxxxx",$body1);
            $body1=str_replace('href="'.$link_chk.'"','href="xxxxx'.$link_no.'xxxxx"',$body1);
            $link_no=$link_no+1;
        }
        for($kkk=0;$kkk<count($link_replace);$kkk++){
            $lnk="xxxxx".($kkk+2)."xxxxx";
            $body1=str_replace($lnk,$link_replace[$kkk],$body1);
        }
//replace link======================================================================================
        $body_html .=$body1;
        $body_html=str_replace('IDD',$id,$body_html);
        $body_html=str_replace('CAMPAIGNNN',$campaignid,$body_html);
        $body_html=str_replace('MARKETTT',$email_marketingid,$body_html);
        $body_html=str_replace('MODUES',$from_module,$body_html);
        $body_html=str_replace('CRMMM',$from_id,$body_html);
        $body_html=str_replace('CLICKTYPE','edm',$body_html);

        /*$body_html.='</td>
                </tr>
                <tr>
                    <td height="50" align="center" style="font-size: 0px;" mce_style="font-size:0px;">
                        <div style="color: rgb(102, 102, 102); line-height: 20px; font-family: tahoma; font-size: 12px;" mce_style="font-size: 12px; line-height: 20px; font-family: tahoma; color:#666666;">
                        หากท่านไม่ต้องการรับข่าวสารจากทางเราอีก <a style="color:#fd8103;" target="_blank" href='.str_replace('CLICKTYPE','edm',$url_un_sub).'>กรุณายกเลิกการรับข่าวสารที่นี่</a><br mce_bogus="1" /></div>
                    </td>
                </tr>
            </tbody>
        </table>
    ';	*/
        $body_html.='</td>
						</tr>
						<tr>
							<td height="50" align="center" style="font-size: 0px;" mce_style="font-size:0px;">
								<div style="color: rgb(102, 102, 102); line-height: 20px; font-family: tahoma; font-size: 12px;" mce_style="font-size: 12px; line-height: 20px; font-family: tahoma; color:#666666;">
								'.$language3.' <a style="color:#fd8103;" target="_blank" href='.str_replace('CLICKTYPE','edm',$url_un_sub).'>'.$language4.'</a> '.$language5.'<br mce_bogus="1" /></div>
							</td>
						</tr>
					</tbody>
				</table>			
			';

        /*$url_load_open='<img src=".$url_path."/EDM/update_open.php?id=".$id."&campaignid=".$campaignid."';
        $url_load_open.='&email_marketingid=".$email_marketingid."&emailtargetlistid=".$emailtargetlistid."&table=".$new_table."';
        $url_load_open.='&from_module=".$from_module."&from_id=".$from_id."&email=".trim($to_email)."';
        $url_load_open.=' " alt="" width="1" height="1">';	*/
        $url_load_open="<img src=".$url_path."/EDM/update_open.php?id=".$id."&campaignid=".$campaignid."";
        $url_load_open.="&email_marketingid=".$email_marketingid."&emailtargetlistid=".$emailtargetlistid."&table=".$new_table."";
        $url_load_open.="&from_module=".$from_module."&from_id=".$from_id."&email=".trim($to_email)." ";
        $url_load_open.=" width=1 height=1>";

        $body_html=$body_html.$unsub.$url_load_open;
        //Set Email Body

        //Update message เก็บ code html
        // $sql_update="update tbt_email_log_smartquestionnaireid_".$val[1]." set message = '".$body_html."' where id='".$id."' ";
        // $genarate->query($sql_update,"all");
        //echo $sql_update; exit;
    }



    $subject = $campaign[$i][2];
    if($new_string=="")
    {
        $body=$email_body;
    }else
    {
        $body=$new_string;
    }

    $body_html='
		<!--DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"-->
		<html>
		<head>
		<title>'.$subject.'</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		</head>
		<script>
		var XMLHttpArray = [
				function() {return new XMLHttpRequest()},
				function() {return new ActiveXObject("Msxml2.XMLHTTP")},
				function() {return new ActiveXObject("Msxml2.XMLHTTP")},
				function() {return new ActiveXObject("Microsoft.XMLHTTP")}
		];
		function createXMLHTTPObject(){
				var xmlhttp = false;
				for(var i=0; i<XMLHttpArray.length; i++){
						try{
								xmlhttp = XMLHttpArray[i]();
						}catch(e){
								continue;
						}
						break;
				}
				return xmlhttp;
		}////
		
		function doQuery(click_link) {
			var req = createXMLHTTPObject();
			//alert(click_link);
			<?
				if (isset($_GET["id"])){
					$varid = $_GET["id"];
				}else{
					$varid = "0";
				}
				if (isset($_GET["campaignid"])){
					$varcampaignid = $_GET["campaignid"];
				}else{
					$varcampaignid = "0";
				}
				if (isset($_GET["marketid"])){
					$varmarketid = $_GET["marketid"];
				}else{
					$varmarketid = "0";
				}				
			?>
			var id="";
			id = <?=$varid;?>;
			var campaignid="";
			campaignid = <?=$varcampaignid;?>;
			var marketid="";
			marketid = <?=$varmarketid;?>;
			var strURL = "'.$url_path.'/EDM/update_click_html.php?id="+ id+"&click_link="+click_link+"&campaignid="+campaignid+"&marketid="+marketid;
			//alert(strURL);
				if (req){
					req.onreadystatechange = function(){
						if (req.readyState == 4) { //data is retrieved from server
							if (req.status == 200) { // which reprents ok status                    
								//alert(5555);
							}else{ 
								//alert("There was a problem while using XMLHTTP:\n");
							}
						}            
					}        
					req.open("GET", strURL, true); //open url using get method
					req.send(null);//send the results
				}
			}
		</script>
		
		<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">		
			<table align="center" class="mceItemTable" id="Table_01" border="0" cellspacing="0" cellpadding="0" width="800" >
				<tbody>
					<tr>
						  <td  >
				';

//replace link======================================================================================
    //echo $body; exit;
    $link_all = explode("href=", $body);

    $link_array=array();
    $link_array_chk=array();
    rsort($link_all);
    for($kkk=0;$kkk<count($link_all);$kkk++){
        $link_true="";
        $aPanel=$link_all[$kkk];
        //echo $aPanel;exit;
        $link_str= explode('" ',$aPanel);
        //echo "<pre>";
        //print_r($link_str);
        //echo "</pre>";
        //exit;
        for($kk=0;$kk<count($link_str);$kk++){
            $link_name=str_replace('"',"",$link_str[$kk]);
            if(substr($link_name,0,4)=="http"){
                $link_true=$link_name;
                $link_true = explode(">", $link_true);
                $link_array[]=$link_true[0];
            }
        }
    }
    $chk=0;
    $link_array_chk1 = array_unique($link_array);
    $link_array_chk = array_values($link_array_chk1);

    //insert link
    $sql="delete from tbt_report_tab_2 where campaign_id='".$campaignid."' and link_id in(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16)  ";
    $genarate->query($sql);

    $sql="insert into tbt_report_tab_2 (campaign_id,link_id,link_name,total_click,status)values('".$campaignid."','1','หากท่านไม่สามารถอ่านอีเมล์ฉบับนี้ได้ กรุณา คลิกที่นี่','0','Active');";
    $genarate->query($sql);
    $sql="insert into tbt_report_tab_2 (campaign_id,link_id,link_name,total_click,status)values('".$campaignid."','16','หากท่านไม่ต้องการรับข่าวสารจากทางเราอีก กรุณายกเลิกการรับข่าวสารที่นี่','0','Active');";
    $genarate->query($sql);
    $link_no=2;

    for($kkk=0;$kkk<count($link_array_chk);$kkk++){
        $sql="insert into tbt_report_tab_2 (campaign_id,link_id,link_name,total_click,status)values('".$campaignid."','".$link_no."','".$link_array_chk[$kkk]."','0','Active');";
        $genarate->query($sql);
        //echo $sql."<br>";
        $link_rename=$url_path."/EDM/update_click_edm.php?param=id1332IDD1332campaignid1332CAMPAIGNNN1332marketid1332MARKETTT1332module1332MODUES1332crmid1332CRMMM1332link1332".$link_no."1332click_type1332CLICKTYPE1332url1332";
        $body=str_replace($link_array_chk[$kkk],$link_rename.str_replace("&","||||",$link_array_chk[$kkk]),$body);
        $link_no=$link_no+1;
    }

//replace link======================================================================================
    $body_html .=	$body;

    $body_html=str_replace('IDD','<?=$_REQUEST["id"]?>',$body_html);
    $body_html=str_replace('CAMPAIGNNN','<?=$_REQUEST["campaignid"]?>',$body_html);
    $body_html=str_replace('MARKETTT','<?=$_REQUEST["marketid"]?>',$body_html);
    $body_html=str_replace('MODUES','<?=$_REQUEST["module"]?>',$body_html);
    $body_html=str_replace('CRMMM','<?=$_REQUEST["crmid"]?>',$body_html);
    $body_html=str_replace('CLICKTYPE','html',$body_html);


    $body_html.='</td>
					</tr>
					<tr>
						<td height="50" align="center" style="font-size: 0px;" mce_style="font-size:0px;">
							<div style="color: rgb(102, 102, 102); line-height: 20px; font-family: tahoma; font-size: 12px;" mce_style="font-size: 12px; line-height: 20px; font-family: tahoma; color:#666666;">
							'.$language3.' <a style="color:#fd8103;" target="_blank" href="'.$url_path.'/EDM/unsub.php?id=<?=$_GET["id"]?>&campaignid=<?=$_GET["campaignid"]?>&marketid=<?=$_GET["marketid"]?>&module=<?=$_GET["module"]?>&crmid=<?=$_GET["crmid"]?>"  onClick="doQuery(16);">'.$language4.'</a> '.$language5.'<br mce_bogus="1" /></div>
						</td>
					</tr>
				</tbody>
			</table>	
			</body>
		</html>			
		';
    //auto เขียนไฟล์ edm_html
//		$FileName = $path."/EDM/".$folder_name."/".$folder_name.".html";
//		//echo $FileName;
//		$FileHandle = fopen($FileName, 'a+') or die("can't open file");
//		fwrite($FileHandle,''."\r\n");
//		fclose($FileHandle);
//		$FileName = $path."/EDM/".$folder_name."/".$folder_name.".php";
//		$FileHandle = fopen($FileName, 'a+') or die("can't open file");
//		fwrite($FileHandle,''."\r\n");
//		fclose($FileHandle);
//
//		$FileName = $path."/EDM/".$folder_name."/".$folder_name.".html";
//		$FileHandle = fopen($FileName, 'w') or die("can't open file");
//		fwrite($FileHandle,$body_html."\r\n");
//		fclose($FileHandle);
//		$FileName = $path."/EDM/".$folder_name."/".$folder_name.".php";
//		$FileHandle = fopen($FileName, 'w') or die("can't open file");
//		fwrite($FileHandle,$body_html."\r\n");
//		fclose($FileHandle);

//จัดการข้อมูล email ซ้ำ===========================================================================			
    //set hotmail,gmail,yahoo status=3=================================================
    $sql="update ".$new_table." set domain_name='hotmail' where 1 and `to_email` like'%hotmail%';";
    $genarate->query($sql,"all");
    $sql="update ".$new_table." set domain_name='gmail' where 1 and `to_email` like'%gmail%';";
    $genarate->query($sql,"all");
    $sql="update ".$new_table." set domain_name='yahoo' where 1 and `to_email` like'%yahoo%';";
    $genarate->query($sql,"all");
    $sql="update ".$new_table." set domain_name='others' where 1 and domain_name not in ('hotmail','gmail','yahoo') ;";
    $genarate->query($sql,"all");

    $sql="update ".$new_table." set active=1,report=1 ,group_send=1 where 1 ;";
    $genarate->query($sql,"all");

    $sql="
		update ".$new_table." as a
		inner join (
		select 
		to_email 
		from ".$new_table."
		where 1 GROUP BY to_email HAVING count( * ) >1 
		)as b
		on a.to_email = b.to_email 
		set a.active=0,a.report=0 ,a.duplicate=1,a.status=2;
		";
    //echo $sql."<br>"; exit;
    $genarate->query($sql,"all");
    $sql="
		select min(id)as id
		from ".$new_table."
		where 1 
		and duplicate = 1
		GROUP BY to_email
		HAVING count( * ) >1
		";
    //echo $sql."<br>";exit;
    $data_d = $genarate->process($sql,"all");
    for($k=0;$k<count($data_d);$k++){
        $sql="update ".$new_table." set active=1,report=1 ,status = 0 ,duplicate = 0 where 1 and id='".$data_d[$k][0]."';";
        $genarate->query($sql,"all");
    }

    //แก้ตัวอักษรแปลกๆๆ
    $sql="
		select 
		id,
		to_email 
		from ".$new_table."
		where 1 
		and active=1
		and report=1
		";
    $data_d = $genarate->process($sql,"all");
    for($k=0;$k<count($data_d);$k++){
        $to_email=$data_d[$k][1];
        $to_email_old=$data_d[$k][1];
        $to_email=str_replace("$","",$to_email);
        $to_email=str_replace("'","",$to_email);
        $to_email=str_replace(",","",$to_email);
        $to_email=str_replace("ี","",$to_email);
        $to_email=str_replace("ุ","",$to_email);
        $to_email=str_replace("ู","",$to_email);

        if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$", $to_email)){
            //echo $to_email."<br>";
        }else{
            $sql="update ".$new_table." set active=1,report=1,mistype_email=1,to_email_old='".$to_email_old."',to_email='".$to_email."' where 1 and id='".$data_d[$k][0]."';";
            $genarate->query($sql,"all");
        }
    }
    //exit;
    //เช็ค format ก่อนส่ง
    $sql="
		select 
		id,
		to_email 
		from ".$new_table."
		where 1 
		and active=1
		and report=1
		";
    $data_d = $genarate->process($sql,"all");
    for($k=0;$k<count($data_d);$k++){
        $to_email=$data_d[$k][1];
        /*if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo("$email is a valid email address");
        } else {
            echo("$email is not a valid email address");
        }*/
        //if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $to_email)){
        if (filter_var($to_email, FILTER_VALIDATE_EMAIL)) {

        }else{
            $sql="update ".$new_table." set active=0,report=0,invalid_email=1,status=2 where 1 and id='".$data_d[$k][0]."';";
            $genarate->query($sql,"all");
        }
    }
    $sql="update ".$new_table." set active=0,report=0,invalid_email=1,status=2 where 1 and right(to_email,1)='.';";
    $genarate->query($sql,"all");
    $sql="update ".$new_table." set active=0,report=0,invalid_email=1,status=2 where 1 and to_email LIKE  '%..%' ;";
    $genarate->query($sql,"all");
    $sql="update ".$new_table." set active=0,report=0,invalid_email=1,status=2 where 1 and to_email LIKE  '%.@%' ;";
    $genarate->query($sql,"all");
    $sql="update ".$new_table." set active=0,report=0,invalid_email=1,status=2 where 1 and to_email = '' ;";
    $genarate->query($sql,"all");



    //yind add เช็คต้องการรับข่าวสาร

    $sql="update ".$new_table." set status= 2 where 1 and active = 0 and report = 0 and unsubscribe = 1;";
    $genarate->query($sql,"all");

    $sql="update ".$new_table." set status= 0 where 1 and active= 1 and report = 1 unsubscribe = 0;";
    $genarate->query($sql,"all");





    //set hotmail,gmail,yahoo status=3=================================================
//จัดการข้อมูล email ซ้ำ===========================================================================	
    $sql="
		CREATE TABLE ".$new_table."_click (
		id int(11) NOT NULL auto_increment,
		`dateclick` varchar(30) default NULL,
		`remoteip` varchar(150) default NULL,
		`page` varchar(150) default NULL,
		`device` varchar(150) default NULL,
		`device_all` varchar(250) default NULL,
		`ctnum` int(11) default NULL,
		`uniqueid` int(11) default NULL,
		`email` varchar(150) default NULL,
		PRIMARY KEY  (id),
		KEY id (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		";
    if($genarate->query($sql,"all")){}

    $sql="
		CREATE TABLE ".$new_table."_open (
		id int(11) NOT NULL auto_increment,
		`dateopen` varchar(30) default NULL,
		`remoteip` varchar(150) default NULL,
		`device` varchar(150) default NULL,
		`device_all` varchar(250) default NULL,
		`ctnum` int(11) default NULL,
		`uniqueid` int(11) default NULL,
		`email` varchar(150) default NULL,
		PRIMARY KEY  (id),
		KEY id (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		";
    if($genarate->query($sql,"all")){}

    //1.เพิ่มฟิวส์
    $sql="
		CREATE TABLE ".$new_table."_report (
		  `id` int(11) NOT NULL,
		  `emailtargetlistid` int(19) NOT NULL,
		  `from_module` varchar(250) NOT NULL,
		  `from_id` int(19) NOT NULL,
		  `to_name` varchar(250) NOT NULL,
		  `to_email` varchar(250) NOT NULL,
		  `domain_name` varchar(250) NOT NULL,
		  `active` int(20) DEFAULT '0' COMMENT 'ใช้งานหรือไม่ใช้งาน',
		  `report` int(20) DEFAULT '0' COMMENT 'ออกรายงานหรือไม่ออก',
		  `unsubscribe` int(20) DEFAULT '0' COMMENT 'งดรับเมล์',
		  `unsubscribe_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'วันยกเลิกข่าวสาร',
		  `bounce` varchar(1) NOT NULL DEFAULT '0',
		  `click_true` varchar(1) NOT NULL DEFAULT '0',
		  `report_true` varchar(1) NOT NULL DEFAULT '0',
		  KEY `id` (`id`),
		  KEY `to_email` (`to_email`),
		  KEY `active` (`active`),
		  KEY `report` (`report`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8; ";
    //echo $sql;exit;
    if($genarate->query($sql,"all")){}

    $sql="ALTER TABLE  `".$new_table."` ADD INDEX (  `to_email` );";
    if($genarate->query($sql,"all")){};
    $sql="ALTER TABLE  `".$new_table."` ADD INDEX (  `active` );";
    if($genarate->query($sql,"all")){};
    $sql="ALTER TABLE  `".$new_table."` ADD INDEX (  `report` );";
    if($genarate->query($sql,"all")){};
    $sql="ALTER TABLE  `".$new_table."_open` ADD INDEX (  `uniqueid` );";
    if($genarate->query($sql,"all")){};
    $sql="ALTER TABLE  `".$new_table."_click` ADD INDEX (  `uniqueid` );";
    if($genarate->query($sql,"all")){};

    $sql="ALTER TABLE `".$new_table."` ADD `chk_bounce` INT( 2 ) NOT NULL ;";
    if($genarate->query($sql,"all")){};
    $sql="ALTER TABLE `".$new_table."` ADD `bounce` INT( 2 ) NOT NULL ;";
    if($genarate->query($sql,"all")){};
    $sql="ALTER TABLE `".$new_table."` ADD `chk` INT( 2 ) NOT NULL ;";
    if($genarate->query($sql,"all")){};
    $sql="ALTER TABLE `".$new_table."_open` ADD `bounce` INT( 2 ) NOT NULL ;";
    if($genarate->query($sql,"all")){};
    $sql="ALTER TABLE `".$new_table."_click` ADD `bounce` INT( 2 ) NOT NULL ;";
    if($genarate->query($sql,"all")){};

    $sql="ALTER TABLE `".$new_table."_open` ADD `emailtargetlistid` int(19) NOT NULL ;";
    if($genarate->query($sql,"all")){};
    $sql="ALTER TABLE `".$new_table."_open` ADD `from_id` int(19) NOT NULL ;";
    if($genarate->query($sql,"all")){};
    $sql="ALTER TABLE `".$new_table."_open` ADD `domain_name` varchar(150) NOT NULL ;";
    if($genarate->query($sql,"all")){};

    $sql="ALTER TABLE `".$new_table."_click` ADD `emailtargetlistid` int(19) NOT NULL ;";
    if($genarate->query($sql,"all")){};
    $sql="ALTER TABLE `".$new_table."_click` ADD `from_id` int(19) NOT NULL ;";
    if($genarate->query($sql,"all")){};
    $sql="ALTER TABLE `".$new_table."_click` ADD `domain_name` varchar(150) NOT NULL ;";
    if($genarate->query($sql,"all")){};

    //2.update ว่าข้อมูลที่ open หรือ click เป็นข้อมูลที่เกิดจาก email ที่เป็น bounce
    $sql="update ".$new_table."_open set email =0,bounce=0 ";
    if($genarate->query($sql,"all")){};
    $sql="update ".$new_table."_click set email =0,bounce=0 ";
    if($genarate->query($sql,"all")){};
    $sql="update ".$new_table." set chk=0,bounce=0,chk_bounce=0 ";
    if($genarate->query($sql,"all")){};

    //update EDM tbt_report_tab_1
    $sql="delete from tbt_report_tab_1 where campaign_id='".$campaignid."'";
    if($genarate->query($sql,"all")){};
    //$campaignid=$campaign[$i][2];
    //$campaign_name

    /*###################- select เก็บ ค่าที่ได้ไว้ในตัวแปร ###########################*/


    /*###################- email ทั้งหมด - ###########################*/

    $start_date=$date_start;
    $sql="select 1 from ".$new_table." where campaignid='".$campaignid."' ; ";
    $data_email_import= $genarate->process($sql,"all");

    /*###################- email ไม่ถูกต้อง - ###########################*/

    $sql="select 1 from ".$new_table." where campaignid='".$campaignid."' and report=0 and active=0 and bounce=0 and invalid_email=1; ";
    $data_email_invalid= $genarate->process($sql,"all");

    /*###################- email ไม่ซ้ำ - ###########################*/

    $sql="select 1 from ".$new_table." where campaignid='".$campaignid."' AND `duplicate` = 0 and report=1 and active=1; ";
    $data_email_dup0= $genarate->process($sql,"all");

    //print_r($sql);

    /*###################- email ซ้ำ - ###########################*/

    $sql="SELECT 1 FROM  ".$new_table." where campaignid='".$campaignid."' AND  `duplicate` !=0 AND report =1 AND active =1 GROUP BY  `to_email` ; ";
    $data_email_dup1= $genarate->process($sql,"all");


    /*###################- email ซ้ำทั้งหมด - ###########################*/

    $sql="SELECT 1 FROM  ".$new_table." where campaignid='".$campaignid."' AND `duplicate` !=0 ; ";
    $data_email_dup1_all= $genarate->process($sql,"all");

    /*###################- email ที่ส่งผ่าน - ###########################*/

    $sql="SELECT 1 FROM  ".$new_table." where campaignid='".$campaignid."' AND report =1 AND active =1;";
    $data_email_send= $genarate->process($sql,"all");

    /*###################- email ที่เป็น hotmail - ###########################*/

    $sql="select 1 from ".$new_table." where campaignid='".$campaignid."' and domain_name='hotmail' and report=1 and active=1; ";
    $data_hotmail= $genarate->process($sql,"all");

    /*###################- email ที่เป็น gmail - ###########################*/

    $sql="select 1 from ".$new_table." where campaignid='".$campaignid."' and domain_name='gmail' and report=1 and active=1; ";
    $data_gmail= $genarate->process($sql,"all");

    /*###################- email ที่เป็น yahoo - ###########################*/

    $sql="select 1 from ".$new_table." where campaignid='".$campaignid."' and domain_name='yahoo' and report=1 and active=1; ";
    $data_yahoo= $genarate->process($sql,"all");

    /*###################- email ที่เป็น other - ###########################*/

    $sql="select 1 from ".$new_table." where campaignid='".$campaignid."' and domain_name='others' and report=1 and active=1; ";
    $data_others= $genarate->process($sql,"all");



    //tbt_report_tab_1
    $sql="insert into tbt_report_tab_1(campaign_id,campaign_name,start_date,email_import,email_invalid,email_dup0,email_dup1
		,email_dup1_all,email_send,email_hotmail,email_yahoo,email_gmail,email_others,status)
		values('".$campaignid."','".$campaign_name."','".$start_date."','".count($data_email_import)."','".count($data_email_invalid)."','".count($data_email_dup0)."','".count($data_email_dup1)."'
		,'".count($data_email_dup1_all)."','0','".count($data_hotmail)."','".count($data_yahoo)."','".count($data_gmail)."','".count($data_others)."','Active'
		)
		";
    if($genarate->query($sql,"all")){};

    //tbt_report_tab_3
    $sql1="delete from tbt_report_tab_3 where campaign_id ='".$campaignid."'";
    $genarate->query($sql1);

    $sql="insert into tbt_report_tab_3(campaign_id,email_send,status)
		values('".$campaignid."','".count($data_email_send)."','Active'
		)
		";

    if($genarate->query($sql,"all")){};
    //echo "666";
    // $sql="update aicrm_smartquestionnaire set email_setup =1 where smartquestionnaireid='".$campaignid."'";
    $prepare_date = date('Y-m-d');
    $prepare_time = date('H:i');
    $sql="update aicrm_smartquestionnaire set email_setup=1, email_status='Schedule', prepare_status='yes', prepare_date='".$prepare_date."', prepare_time='".$prepare_time."' where smartquestionnaireid='".$campaignid."'";
//		echo $sql;exit;
    if($genarate->query($sql,"all")){};

}//for $campaign
//get email====================================================================================

echo "<script type='text/javascript'>alert('Set Up Email Complete');window.close();  window.opener.parent.location.replace('index.php?action=DetailView&module=Smartquestionnaire&record=".$crmid."&parenttab=Marketing');</script>";
?>