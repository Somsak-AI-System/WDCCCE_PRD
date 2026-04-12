<?php

function getData1($crmid){
global $generate;
	$sql="
	SELECT 
	*
	FROM tbt_report_tab_1 WHERE 1
	and campaign_id='".$crmid."'
	";
	$data=$generate->process($sql,"all");
	//print_r($data);
	
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
	fwrite($FileHandle,"<set label='จำนวนอีเมล์ทั้งหมด' value='".$data[0]['email_import']."' color='00BFFF'/>");
	fwrite($FileHandle,"<set label='จำนวนอีเมล์ที่ไม่ถูกต้อง' value='".$data[0]['email_invalid']."' color='FFFF00'/>");
	fwrite($FileHandle,"<set label='จำนวนอีเมล์ที่ถูกส่งออก' value='".$data[0]['email_send']."'color='7FFF00' />");
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
function getData4($crmid){
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
	//print_r($data);
	
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

	fwrite($FileHandle,"<set label='จำนวนเมล์ที่ส่งออกทั้งหมด' value='".$data[0]['email_send']."' color='0000FF'/>");
	fwrite($FileHandle,"<set label='จำนวนเมล์ที่ส่งไม่ผ่านทั้งหมด' value='".$data[0]['email_send_uncomplete']."' color='FF0000'/>");
	fwrite($FileHandle,"<set label='จำนวนเมล์ส่งผ่านทั้งหมด' value='".$data[0]['email_send_complete']."' color='32CD32'/>");
	fwrite($FileHandle,"<set label='จำนวนผู้ที่เปิดเมล์ทั้งหมด' value='".$data[0]['email_open']."' color='FFFF00'/>");
	fwrite($FileHandle,"<set label='จำนวนผู้ที่คลิ๊กลิ๊งค์ทั้งหมด' value='".$data[0]['email_click']."' color='EE82EE'/>");
	fwrite($FileHandle,"<set label='จำนวนผู้ที่ต้องการยกเลิก             การรับข่าวสาร' value='".$data[0]['email_unsun']."' color='FFA500'/>");
	fwrite($FileHandle,'</chart>');
  }
  fclose($FileHandle);
}
?>