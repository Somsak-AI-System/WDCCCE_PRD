<?php

function getData1($crmid, $type=""){
global $generate;
	$sql="
	SELECT 
	*
	FROM tbt_report_tab_1 WHERE 1
	and campaign_id='".$crmid."'
	";
	$data=$generate->process($sql,"all");
	//print_r($data);

	  $sql7="select smartemailid from aicrm_smartemail where smartemailid='".$crmid."' limit 1";
    $data7 = $generate->process($sql7,"all");
    $table="tbt_email_log_smartemailid_".$data7[0]['smartemailid'];

    if($type == 'surveysmartemail'){
      $table="tbt_email_log_surveysmartemailid_".$crmid;
    }

    if($type == 'smartquestionnaire'){
      $table="tbt_email_log_smartquestionnaireid_".$crmid;
    }

    $sql8="select from_module as Module,from_id as CRMID ,to_name as Name ,to_email as Email ,domain_name as Domain ,DATE_FORMAT( date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date  from ".$table ." where 1 AND status =2";
    $data8=$generate->process($sql8,"all");
    $kk = count($data8);


                  $sql="SELECT * from ".$table."";  //จำนวนเมล์ที่ส่งออกทั้งหมด
                $data_all=$generate->process($sql,"all");
                $count_data_all = count($data_all);

                 $sql="SELECT * FROM ".$table."
                WHERE
                    (
                      (duplicate = 1 AND active = 0)
                      OR invalid_email = 1
                      OR unsubscribe = 1
                      OR to_email IS NULL
                    )
                "; 
                $data_problem=$generate->process($sql,"all");
                $count_data_problem = count($data_problem);   //จำนวนเมล์ที่มีปัญหา



                
                $sql="SELECT * FROM ".$table." a
                WHERE
                  NOT a.id IN (
                  SELECT
                    b.id
                  FROM
                    ".$table." b
                  WHERE
                    (
                      (b.duplicate = 1 AND b.active = 0)
                      OR b.invalid_email = 1
                      OR b.unsubscribe = 1
                      OR b.to_email IS NULL
                    )

                  )"; 
                $data_send=$generate->process($sql,"all");
                $count_data_send = count($data_send);   //รายการที่ทำการส่ง

                //print_r($sql);



               /* $sql="SELECT
                  *
                FROM
                  ".$table." a
                WHERE
                  NOT a.id IN (
                  SELECT
                    b.id
                  FROM
                    ".$table." b
                  WHERE
                    (
                     (b.duplicate = 1 AND b.active = 0)
                      OR b.invalid_email = 1
                      OR b.unsubscribe = 1
                      OR b.to_email IS NULL
                    )
                    OR b.status != 2
                  )
                "; */
                 $sql="SELECT
                  *
                FROM
                  ".$table." 
                WHERE
                 status = 2
                  
                "; 
                $data_no_pass=$generate->process($sql,"all");
                $count_data_no_pass = count($data_no_pass);   //จำนวนเมล์ที่ส่งไม่ผ่านทั้งหมด





                /* $sql="SELECT
                  *
                FROM
                  ".$table." a
                WHERE
                  NOT a.id IN (
                  SELECT
                    b.id
                  FROM
                    ".$table." b
                  WHERE
                    (
                      (b.duplicate = 1 AND b.active = 0)
                      OR b.invalid_email = 1
                      OR b.unsubscribe = 1
                      OR b.to_email IS NULL
                    )
                    OR b.status != 1
                  )"; */

                   $sql="SELECT
                  *
                FROM 
                  ".$table." WHERE status = 1
                  "; 
                $data_pass=$generate->process($sql,"all");
                $count_data_pass = count($data_pass);   //จำนวนเมล์ที่ส่งผ่านทั้งหมด
	
	$FileName = "XML/data1.xml";
	$FileHandle = fopen($FileName, 'w') or die("can't open file");
	
	if(count($data) <=0){
		fwrite($FileHandle,'<?xml version="1.0" encoding="UTF-8" ?>');
	}
	else
	{
	fwrite($FileHandle,'<?xml version="1.0" encoding="UTF-8" ?>');
	fwrite($FileHandle,"<chart caption='กราฟผลสรุปการส่ง ".$data[0]['campaign_name']."' xAxisName='' yAxisName='จำนวนอีเมล์' showValues='0' decimals='0'  bordercolor='C6D2DF'
formatNumberScale='0' palette='4'  labelDisplay='ROTATE' slantLabels='1' showLabels='0' > ");
	fwrite($FileHandle,"<set label='จำนวนอีเมล์ทั้งหมด' value='".$count_data_all."' color='00BFFF'/>");
	fwrite($FileHandle,"<set label='รายการที่มีปัญหา' value='".$count_data_problem."' color='3fff00'/>");
	fwrite($FileHandle,"<set label='รายการที่ทำการส่ง' value='".$count_data_send."' color='black'/>");
	fwrite($FileHandle,"<set label='จำนวนอีเมล์ที่ส่งไม่ผ่าน' value='".$count_data_no_pass."' color='FF0000'/>");
	fwrite($FileHandle,"<set label='จำนวนอีเมล์ที่ถูกส่งออก' value='".$count_data_pass."' color='32CD32'/>");
	fwrite($FileHandle,'</chart>');
  }
  fclose($FileHandle);
}
function getData2($crmid){
global $generate;
	$sql="
	SELECT 
	*
	FROM tbt_report_tab_1 WHERE 1
	and campaign_id='".$crmid."'
	";
	$data=$generate->process($sql,"all");
	//print_r($data);
	
	$FileName = "XML/data2.xml";
	$FileHandle = fopen($FileName, 'w') or die("can't open file");
	
	if(count($data) <=0){
		fwrite($FileHandle,'<?xml version="1.0" encoding="UTF-8" ?>');
	}
	else
	{
	fwrite($FileHandle,'<?xml version="1.0" encoding="UTF-8" ?>');
	fwrite($FileHandle,"<chart caption='กราฟวงกลมแยกประเภทของอีเมล์' palette='2' animation='1' formatNumberScale='0' numberPrefix=''  bordercolor='C6D2DF' showLegend='1' 
pieSliceDepth='30' startingAngle='125' showLabels='0' showValues='0'>");
	fwrite($FileHandle,"<set label='hotmail' value='".$data[0]['email_hotmail']."' color='0000FF'/>");
	fwrite($FileHandle,"<set label='yahoo' value='".$data[0]['email_yahoo']."' color='A020F0'/>");
	fwrite($FileHandle,"<set label='gmail' value='".$data[0]['email_gmail']."' color='FFA500'/>");
	fwrite($FileHandle,"<set label='other' value='".$data[0]['email_others']."' color='98FB98'/>");
	fwrite($FileHandle,'    <styles>');
	fwrite($FileHandle,'        <definition>');
	fwrite($FileHandle,'            <style type="font" name="CaptionFont" size="15" color="666666" />');
	fwrite($FileHandle,'            <style type="font" name="SubCaptionFont" bold="0" />');
	fwrite($FileHandle,'        </definition>');
	fwrite($FileHandle,'        <application>');
	fwrite($FileHandle,'            <apply toObject="caption" styles="CaptionFont" />');
	fwrite($FileHandle,'            <apply toObject="SubCaption" styles="SubCaptionFont" />');
	fwrite($FileHandle,'        </application>');
	fwrite($FileHandle,'    </styles>');
	fwrite($FileHandle,'</chart>');
  }
  fclose($FileHandle);
}
function getData3($crmid){
global $generate;
	$sql="
	SELECT 
	*
	FROM tbt_report_tab_2 WHERE 1
	and campaign_id='".$crmid."'
	and status='Active'
	order by link_id
	";
	$data=$generate->process($sql,"all");
	
	$sql="
	SELECT 
	*
	FROM tbt_report_tab_1 WHERE 1
	and campaign_id='".$crmid."'
	";
	$data1=$generate->process($sql,"all");
	//print_r($data);
	
	$FileName = "XML/data4.xml";
	$FileHandle = fopen($FileName, 'w') or die("can't open file");
	
	if(count($data) <=0){
		fwrite($FileHandle,'<?xml version="1.0" encoding="UTF-8" ?>');
	}
	else
	{
	fwrite($FileHandle,'<?xml version="1.0" encoding="UTF-8" ?>');
	fwrite($FileHandle,"<chart caption='กราฟแสดงจำนวน Link ที่มีผู้อ่านอีเมล์คลิ๊กทั้งหมด' palette='10' animation='1' formatNumberScale='0' numberPrefix=''  bordercolor='C6D2DF' showLegend='1' 
pieSliceDepth='30' startingAngle='125' showLabels='0' showValues='0' >");
	
	for($i=0;$i<count($data);$i++){
		fwrite($FileHandle,"<set label='".$data[$i]['link_name']."' value='".$data[$i]['total_click']."' />");
	}
	fwrite($FileHandle,'</chart>');
  }
  fclose($FileHandle);
}
function getData4($crmid, $type=''){
global $generate;
	$sql="
	SELECT 
	*
	FROM tbt_report_tab_3 WHERE 1
	and campaign_id='".$crmid."'
	and status='Active'
	";
	$data=$generate->process($sql,"all");
	
	$sql="
	SELECT 
	*
	FROM tbt_report_tab_1 WHERE 1
	and campaign_id='".$crmid."'
	";
	$data1=$generate->process($sql,"all");
	// print_r($data);

	  $sql7="select smartemailid from aicrm_smartemail where smartemailid='".$crmid."' limit 1";
    $data7 = $generate->process($sql7,"all");
    $table="tbt_email_log_smartemailid_".$data7[0]['smartemailid'];

    if($type == 'surveysmartemail'){
      $table="tbt_email_log_surveysmartemailid_".$crmid;
    }

    if($type == 'smartquestionnaire'){
      $table="tbt_email_log_smartquestionnaireid_".$crmid;
    }

    $sql8="select from_module as Module,from_id as CRMID ,to_name as Name ,to_email as Email ,domain_name as Domain ,DATE_FORMAT( date_start,  '%d-%m-%Y %H:%i:%s' ) as Send_Date  from ".$table ." where 1 AND status =2";
    $data8=$generate->process($sql8,"all");
    $kk = count($data8);


                   $sql="SELECT * from ".$table."";  //จำนวนเมล์ที่ส่งออกทั้งหมด
                $data_all=$generate->process($sql,"all");
                $count_data_all = count($data_all);

                 $sql="SELECT * FROM ".$table."
                WHERE
                    (
                      (duplicate = 1 AND active = 0)
                      OR invalid_email = 1
                      OR unsubscribe = 1
                      OR to_email IS NULL
                    )
                "; 
                $data_problem=$generate->process($sql,"all");
                $count_data_problem = count($data_problem);   //จำนวนเมล์ที่มีปัญหา



                
                $sql="SELECT * FROM ".$table." a
                WHERE
                  NOT a.id IN (
                  SELECT
                    b.id
                  FROM
                    ".$table." b
                  WHERE
                    (
                      (b.duplicate = 1 AND b.active = 0)
                      OR b.invalid_email = 1
                      OR b.unsubscribe = 1
                      OR b.to_email IS NULL
                    )

                  )"; 
                $data_send=$generate->process($sql,"all");
                $count_data_send = count($data_send);   //รายการที่ทำการส่ง

                //print_r($sql);



               /* $sql="SELECT
                  *
                FROM
                  ".$table." a
                WHERE
                  NOT a.id IN (
                  SELECT
                    b.id
                  FROM
                    ".$table." b
                  WHERE
                    (
                      (b.duplicate = 1 AND b.active = 0)
                      OR b.invalid_email = 1
                      OR b.unsubscribe = 1
                      OR b.to_email IS NULL
                    )
                    OR b.status != 2
                  )
                "; */
                 $sql="SELECT
                  *
                FROM
                  ".$table."
                  WHERE
                   status = 2
                  
                "; 
                $data_no_pass=$generate->process($sql,"all");
                $count_data_no_pass = count($data_no_pass);   //จำนวนเมล์ที่ส่งไม่ผ่านทั้งหมด





                /* $sql="SELECT
                  *
                FROM
                  ".$table." a
                WHERE
                  NOT a.id IN (
                  SELECT
                    b.id
                  FROM
                    ".$table." b
                  WHERE
                    (
                      (b.duplicate = 1 AND b.active = 0)
                      OR b.invalid_email = 1
                      OR b.unsubscribe = 1
                      OR b.to_email IS NULL
                    )
                    OR status != 1
                  )"; */
                   $sql="SELECT
                  *
                FROM
                  ".$table." 
                  WHERE
                     status = 1
                  "; 
                $data_pass=$generate->process($sql,"all");
                $count_data_pass = count($data_pass);   //จำนวนเมล์ที่ส่งผ่านทั้งหมด
	
	$FileName = "XML/data3.xml";
	$FileHandle = fopen($FileName, 'w') or die("can't open file");
	
	if(count($data) <=0){
		fwrite($FileHandle,'<?xml version="1.0" encoding="UTF-8" ?>');
	}
	else
	{
	fwrite($FileHandle,'<?xml version="1.0" encoding="UTF-8" ?>');
	fwrite($FileHandle,"<chart caption='กราฟผลสรุปรายละเอียดสถิติการส่ง ".$data1[0]['campaign_name']."' xAxisName='' yAxisName='จำนวนอีเมล์'  bordercolor='C6D2DF'
showValues='0' decimals='0' formatNumberScale='0' palette='4'  >");

	fwrite($FileHandle,"<set label='จำนวนเมล์ที่ส่งออกทั้งหมด' value='".$count_data_all."' color='0000FF'/>");
	fwrite($FileHandle,"<set label='รายการที่มีปัญหา' value='".$count_data_problem."' color='3fff00'/>");
	fwrite($FileHandle,"<set label='รายการที่ทำการส่ง' value='".$count_data_send."' color='black'/>");
	// fwrite($FileHandle,"<set label='จำนวนอีเมล์ที่ซ้ำกัน' value='".$data1[0]['email_dup1']."' color='838f98'/>");
	// fwrite($FileHandle,"<set label='จำนวนอีเมล์ที่ไม่ถูกต้อง' value='".$data1[0]['email_invalid']."' color='A020F0'/>");
	fwrite($FileHandle,"<set label='จำนวนเมล์ที่ส่งไม่ผ่านทั้งหมด' value='".$count_data_no_pass."' color='FF0000'/>");
	fwrite($FileHandle,"<set label='จำนวนเมล์ส่งผ่านทั้งหมด' value='".$count_data_pass."' color='32CD32'/>");
	fwrite($FileHandle,"<set label='จำนวนผู้ที่เปิดเมล์ทั้งหมด' value='".$data[0]['email_open']."' color='FFFF00'/>");
	fwrite($FileHandle,"<set label='จำนวนผู้ที่คลิ๊กลิ๊งค์ทั้งหมด' value='".$data[0]['email_click']."' color='EE82EE'/>");
	fwrite($FileHandle,"<set label='จำนวนผู้ที่ต้องการยกเลิก             การรับข่าวสาร' value='".$data[0]['email_unsun']."' color='FFA500'/>");
	fwrite($FileHandle,'</chart>');
  }
  fclose($FileHandle);
}
?>