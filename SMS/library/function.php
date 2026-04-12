<?php
session_start();
  function getRole($id){
	 global $generate;
	$sql="select * from tbm_syst_roles where id='".$id."' ";
	$data=$genarate->process($sql,"all");
    return $data;
  }
  function getDivision(){
	global $generate;
	$sql="select * from tbm_division;";
	//echo $sql;
	$data=$genarate->process($sql,"all");
	//print_r($data);
	return $data;
  }
  function getGroup(){
	global $generate;
	$sql="select * from tbm_group";
	$data=$genarate->process($sql,"all");
	//print_r($data);
	return $data;
  }
   function getTambol(){
	global $generate;
	$sql="select * from tbm_tambol order by convert(tambol_name using tis620)";
	$data=$genarate->process($sql,"all");
	//print_r($data);
	return $data;
  }
  function getAmphur(){
	global $generate;
	$sql="select * from tbm_amphur order by convert(amphur_name using tis620)";
	$data=$genarate->process($sql,"all");
	//print_r($data);
	return $data;
  }
  function getProvince(){
	global $generate;
	$sql="select * from  tbm_province order by convert(province_name using tis620)";
	$data=$genarate->process($sql,"all");
	//print_r($data);
	return $data;
  }
   function getSurveytype(){
	global $generate;
	$sql="select * from  tbm_surveytype order by convert(surveytypename using tis620)";
	$data=$genarate->process($sql,"all");
	//print_r($data);
	return $data;
  }
   function getBridgetype(){
	global $generate;
	$sql="select * from  tbm_type order by convert(typename using tis620)";
	$data=$genarate->process($sql,"all");
	//print_r($data);
	return $data;
  }
   function getPiertype(){
	global $generate;
	$sql="select * from  tbm_pier order by convert(piername using tis620)";
	$data=$genarate->process($sql,"all");
	//print_r($data);
	return $data;
  }
  function getSlope(){
	global $generate;
	$sql="select * from  tbm_slopeprotection order by convert(slopeprotectionname using tis620)";
	$data=$genarate->process($sql,"all");
	//print_r($data);
	return $data;
  }
   function getSurface(){
	global $generate;
	$sql="select * from tbm_surface order by convert(surfacename using tis620)";
	$data=$genarate->process($sql,"all");
	//print_r($data);
	return $data;
  }

  function CalculateBudget($year,$bridgeid){
	global $genarate;
	
	$sql = " delete t1,t2 from tbt_budget t1
				inner join tbt_budgetdtl t2
				on t1.year = t2.year
			where t1.year = '".$year."' 
			";
	$sql = " delete from tbt_budget where year = '".$year."' ";
	$data=$genarate->process($sql,"all");
	$sql = " delete from tbt_budgetdtl where year = '".$year."' ";
	$data=$genarate->process($sql,"all");

	// Check if exists tbt_budget
	$sql = " select 1 from tbt_budget where year = '".$year."' ";
	echo $sql."<BR>";
	$data=$genarate->process($sql,"all");
	
	echo count($data)."<BR>";
	if (count($data)>0){
	// Exists -- Update
		$sql = "
					update tbt_budget
					inner join
					(
					select sum(totalprice) total
					from
					(
					SELECT rt.bridgeid bridgeid
					,rttreat.treatmentid treatmentid
					,treat.treatment_name treatmentname
					,rttreat.treatmentqty treatmentqty
					,treat.unitprice unitprice
					,(ifnull(rttreat.treatmentqty,0)*ifnull(treat.unitprice,0)) totalprice
					 FROM 
					( select routineid,bridgeid,max(routinedt) from `tbt_routine`
					group by routineid,bridgeid
					) rt left join
					tbt_routinetreat  rttreat
					on rt.routineid = rttreat.routineid
					left join tbm_treatment treat
					on rttreat.treatmentid = treat.treatmentid
					) a
					) data
					set tbt_budget.receive = data.total
					where year = '".$year."' 
				";
	}else{
	// Not Exists -- Insert
		$sql = "
					insert into tbt_budget(year,request)
					select '".$year."',sum(totalprice)
					from
					(
					SELECT rt.bridgeid bridgeid
					,rttreat.treatmentid treatmentid
					,treat.treatment_name treatmentname
					,rttreat.treatmentqty treatmentqty
					,treat.unitprice unitprice
					,(ifnull(rttreat.treatmentqty,0)*ifnull(treat.unitprice,0)) totalprice
					 FROM 
					( select routineid,bridgeid,max(routinedt) from `tbt_routine`
					group by routineid,bridgeid
					) rt left join
					tbt_routinetreat  rttreat
					on rt.routineid = rttreat.routineid
					left join tbm_treatment treat
					on rttreat.treatmentid = treat.treatmentid
					) a
				";
	}
	$genarate->process($sql,"all");
	echo $sql."<BR>";
	// Check if exists tbt_budgetdtl
	$sql = " select 1 from tbt_budgetdtl where year = '".$year."' and bridgeid = ".$bridgeid;
	$data=$genarate->process($sql,"all");
	
	if (count($data)>0){
	// Exists -- Update
		$sql = "
					update tbt_budgetdtl
					inner join
					(
					select sum(totalprice) total
					from
					(
					SELECT rt.bridgeid bridgeid
					,rttreat.treatmentid treatmentid
					,treat.treatment_name treatmentname
					,rttreat.treatmentqty treatmentqty
					,treat.unitprice unitprice
					,(ifnull(rttreat.treatmentqty,0)*ifnull(treat.unitprice,0)) totalprice
					 FROM 
					( select routineid,bridgeid,max(routinedt) from `tbt_routine`
					where bridgeid = ".$bridgeid."
					group by routineid,bridgeid
					) rt left join
					tbt_routinetreat  rttreat
					on rt.routineid = rttreat.routineid
					left join tbm_treatment treat
					on rttreat.treatmentid = treat.treatmentid
					) a
					) data
					set tbt_budget.receive = data.total
					where year = '".$year."' and bridgeid = ".$bridgeid."
				";
	}else{
	// Not Exists -- Insert
		$sql = "
					insert into tbt_budgetdtl(year,bridgeid,request)
					select '".$year."',bridgeid,sum(totalprice)
					from
					(
					SELECT rt.bridgeid bridgeid
					,rttreat.treatmentid treatmentid
					,treat.treatment_name treatmentname
					,rttreat.treatmentqty treatmentqty
					,treat.unitprice unitprice
					,(ifnull(rttreat.treatmentqty,0)*ifnull(treat.unitprice,0)) totalprice
					 FROM 
					( select routineid,bridgeid,max(routinedt) from `tbt_routine`
					group by routineid,bridgeid
					) rt left join
					tbt_routinetreat  rttreat
					on rt.routineid = rttreat.routineid
					left join tbm_treatment treat
					on rttreat.treatmentid = treat.treatmentid
					) a
					group by a.bridgeid
				";
	}
	$genarate->process($sql,"all");
	echo $sql."<BR>";
 }

function recursive_mkdir($path, $mode = 0777) {
	$dirs = explode("\\" , $path);
    $count = count($dirs);
    $path = '.';
    for ($i = 0; $i < $count; ++$i) {
        $path .= DIRECTORY_SEPARATOR . $dirs[$i];
        if (!is_dir($path) && !mkdir($path, $mode)) {
            return false;
        }
    }
    return true;
}

  function getBPI($bridgeid,$strpoint=0,$bridgeneckrate=3,$slopeprotectionrate=3,$surfacepoint=3){
	global $genarate;
	$sql1="select * from tbm_syst_config";
	$data1=$genarate->process($sql1,"all");
	$sql2="select aadt,lane,surfacewidth from tbm_bridgeinventory where bridgeid=".$bridgeid." ";
	$data2=$genarate->process($sql2,"all");
	$result=array_merge($data1,$data2);
	//print_r($result);//exit;
	//echo $strpoint."=".$bridgeneckrate."=".$slopeprotectionrate."=".$surfacepoint;
	$S1=0;
	$S2=0;
	$j=0;
	$a=0;
	$b=0;
	$IR=$result[0]['invemntoryrating'];
	$num1=0;
	$num2=0;
	$num3=0;
	$num4=0;
	$x=0;
	$y=0;
	$h=0;
	$i=0;
	$k=0;
	$aadt=$result[1]['aadt'];
	$lane=$result[1]['lane'];
	$way=$result[0]['highways'];
	$width=$result[1]['surfacewidth'];
	
	//หาค่า A
	if($strpoint==0){
		$a=65;
	}
	else if($strpoint==1){
		$a=40;
	}
	else if($strpoint==2){
		$a=15;
	}
	else if($strpoint>=3){
		$a=0;
	}
	//หาค่า B
	if((25-$IR)==0){
		$b=0;
	}
	else
	{
		$b=((25-$IR)*1.5)*0.56;
	}
	
	$S1=65-($a+$b);//หาค่า S1	
	
	//หาค่า num1
	if($surfacepoint<=2){
		$num1=5;
	}
	else if($surfacepoint==3){
		$num1=2;
	}
	else if($surfacepoint>3){
		$num1=0;
	}
	
	//หาค่า num2
	if($aadt>0 && $aadt<=500){
		if($IR<15.0){
			$num2=1;
		}
		else if($IR>=15.0&&$IR<=18.8){
			$num2=2;
		}
		else if($IR>18.8&&$IR<=24.9){
			$num2=3;
		}
		else if($IR>24.9&&$IR<=32.4){
			$num2=4;
		}
		else if($IR>32.4){
			$num2=5;
		}
	}
	else if($aadt>500 && $aadt<=5000){
		if($IR<15.0){
			$num2=1;
		}
		else if($IR>=15.0&&$IR<=20.9){
			$num2=2;
		}
		else if($IR>21.0&&$IR<=24.9){
			$num2=3;
		}
		else if($IR>24.9&&$IR<=32.4){
			$num2=4;
		}
		else if($IR>32.4){
			$num2=5;
		}
	}
	else if($aadt>5000){
		if($IR<15.0){
			$num2=1;
		}
		else if($IR>=15.0&&$IR<=23.0){
			$num2=2;
		}
		else if($IR>23.0&&$IR<=24.9){
			$num2=3;
		}
		else if($IR>24.9&&$IR<=32.4){
			$num2=4;
		}
		else if($IR>32.4){
			$num2=5;
		}
	}
	
	if($num2<=2){
		$num2=5;
	}
	else if($num2==3){
		$num2=2;
	}
	else 
	{
		$num2=0;
	}
	
	//หาค่า num3
	if($lane<2){//ถ้าถนนน้อยกว่า 2 เลน
		 $num3=0;
	}
	else//ถ้าถนนมากกว่าและเท่ากับ 2 เลน
	{
		if($way==2){//ถ้าเป็นถนนอื่นๆทั่วไป
			if($lane==2){//ถ้าเป็นถนนสองเลน
				if($width<8.2){
					$num3=1;
				}
				else if($width>=8.2 && $width<10.1){
					$num3=2;
				}
				else if($width>=10.1 && $width<11.6){
					$num3=3;
				}
				else if($width>=11.6 && $width<=12.8){
					$num3=4;
				}
				else if($width>12.8){
					$num3=5;
				}
			}
			else//ถ้าถนนมากกว่าสองเลน
			{
				if($width<((3.4*$lane)+1.5)){
					$num3=1;
				}
				else if($width>=((3.4*$lane)+1.5) && $width<((3.4*$lane)+3)){
					$num3=2;
				}
				else if($width>=((3.4*$lane)+3) && $width<((3.7*$lane)+4.6)){
					$num3=3;
				}
				else if($width>=((3.7*$lane)+4.6) && $width<=((3.7*$lane)+5.5)){
					$num3=4;
				}
				else if($width>((3.7*$lane)+5.5)){
					$num3=5;
				}
			}
		}
		else//ถ้าเป็นถนนทางหลวงหรือถนนสายหลัก
		{
			if($lane==2){//ถ้าเป็นถนนสองเลน
				if($width<10.1){
					$num3=1;
				}
				else if($width>=10.1 && $width<11){
					$num3=2;
				}
				else if($width>=11 && $width<12.2){
					$num3=3;
				}
				else if($width>=12.2 && $width<=12.8){
					$num3=4;
				}
				else if($width>12.8){
					$num3=5;
				}
			}
			else//ถ้าถนนมากกว่าสองเลน
			{
				if($width<((3.4*$lane)+3.4)){
					$num3=1;
				}
				else if($width>=((3.4*$lane)+3.4) && $width<((3.7*$lane)+4.3)){
					$num3=2;
				}
				else if($width>=((3.7*$lane)+4.3) && $width<((3.7*$lane)+6.1)){
					$num3=3;
				}
				else if($width>=((3.7*$lane)+6.1) && $width<=((3.7*$lane)+7.3)){
					$num3=4;
				}
				else if($width>((3.7*$lane)+7.3)){
					$num3=5;
				}
			}
		}//เช็คถนนสายหลักหรือถนนอื่นๆ
		if($num3<=2){
			$num3=5;
		}
		else if($num3==3){
			$num3=2;
		}
		else{
			$num3=0;
		}
	}//เช็คถนนมากกว่าสองเลน??
	
	//หาค่า num4
	if($bridgeneckrate<=2){
		$num4=5;
	}
	else if($bridgeneckrate==3){
		$num4=2;
	}
	else if($bridgeneckrate>3){
		$num4=0;
	}
	
	$j=($num1+$num2+$num3+$num4);//หาค่า J
	$x=($aadt/$lane);
	$y=($width/$lane);
	//echo $y;
	//หาค่า H
	if($lane==1){
		if($y<4.3){
			$h=15;
		}
		else if($y>=4.3 && $y<5.5){
			$h=15*((5.5-$y)/1.2);
		}
		else if($y>=5.5){
			$h=0;
		}
	}
	else if($lane==2&&$y>=4.9){
		$h=0;
	}
	else if($lane==3&&$y>=4.6){
		$h=0;
	}
	else if($lane==4&&$y>=4.3){
		$h=0;
	}
	else if($lane==5&&$y>=3.7){
		$h=0;
	}
	else
	{
		if($y<2.7&&$x>50){
			$h=15;
		}
		else if($y<2.7&&$x<=50){
			$h=7.5;
		}
		else if($y>=2.7&&$x<=50){
			$h=0;
		}
		else if($x>50&&$x<=125){
			if($y<3){
				$h=15;
			}
			else if($y>=3&&$y<4){
				$h=15*(4-$y);
			}
			else{
				$h=0;
			}
		}
		else if($x>125&&$x<=375){
			if($y<3.4){
				$h=15;
			}
			else if($y>=3.4&&$y<4.3){
				$h=15*(4.3-$y);
			}
			else{
				$h=0;
			}
		}
		else if($x>375&&$x<=1350){
			if($y<3.7){
				$h=15;
			}
			else if($y>=3.7&&$y<4.9){
				$h=15*((4.9-$y)/1.2);
			}
			else{
				$h=0;
			}
		}
		else if($x>1350){
			if($y<4.6){
				$h=15;
			}
			else if($y>=4.6&&$y<4.9){
				$h=15*((4.9-$y)/1.2);
			}
			else{
				$h=0;
			}
		}
	}//หาค่า H
	
	$S2=35-($j+$h+$i+$k);
	$data=$S1+$S2;
	//echo $data;
	return $data;
  }
?>