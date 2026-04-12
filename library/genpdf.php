<?
define('FPDF_FONTPATH','font/');
include_once("fpdf.php");

class pdf extends FPDF{

	var $font_family = "norisi_n";
	var $font_style = "";
	var $font_size = "13";
	var $size10=10;
	var $size11=11;
	var $size12=12;
	var $size13=13;
	var $size14=14;
	var $size15=15;

				
		function prepareReport($page){
			$this->FPDF($page);
			$this->Open();
			//$this->AddPage();
			$this->SetFillColor(255,0,0);
			$this->SetDrawColor(0,0,0);
			$this->SetLineWidth(.3);
			$this->AddFont('norisi_n','','norisi_n.php');
			$this->AddFont('angsa_n','','angsa.php');
			$this->AddFont('angsa_b','','angsab.php');
			$this->SetFont($this->font_family,$this->font_style,$this->font_size);
			$this->SetTextColor(0);
			 //$this->SetThaiFont(); //for set thai font 
		}
		
		function showReport(){
			$this->Output();
		}

		function Report2Header($header){
			$this->Cell(190,5,$header,0,1,'C'); 
		}

//Show All Report
var $widths;
var $aligns;

function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//echo $w.'  '.$txt."<br>";
	//exit;
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	//echo $w.'  '.$txt."  ".$nl."<br>";
	return $nl;
}

function Footer()
{
	global $flg;
	global $who_report;
	global $iso;
	global $report_date;
	global $remark12;
	global $F_127;
	global $F_128;
	global $F_129;
	global $F_130;
	global $F_131;
	global $F_132;
	global $F_133;
	if($flg=="tiket_1"){
		$this->Rect(10,270,95,5);
		$this->Rect(105,270,95,5);
		$this->Rect(10,275,95,15);
		$this->Rect(105,275,95,15);
		
		$this->SetXY(10,269);
		$this->SetFont('angsa_b','',12);
		$this->Cell(95,7,'Customer Acknowledgement',0,2,'C',0);	

		$this->SetXY(10,277);
		$this->SetFont('angsa_b','',12);
		$this->Cell(95,7,'Signature :      _______________________________                                  ',0,2,'R',0);	
		$this->SetXY(10,283);
		$this->SetFont('angsa_b','',12);
		$this->Cell(95,7,'Date :      _______________________________                                  ',0,2,'R',0);	
						
		$this->SetXY(105,269);
		$this->SetFont('angsa_b','',12);
		$this->Cell(95,7,'Optimized Solution Internal Use',0,2,'C',0);	
		
		$this->SetXY(105,277);
		$this->SetFont('angsa_b','',12);
		$this->Cell(95,7,'Technical Manager :      _______________________________                      ',0,2,'R',0);	
		$this->SetXY(105,283);
		$this->SetFont('angsa_b','',12);
		$this->Cell(95,7,'Date :      _______________________________                      ',0,2,'R',0);					
	}else if($flg=="tiket_2"){
		$this->SetXY(53,258);
		$this->SetFont('angsa_n','','12');
		$this->Cell(38,5,$F_127,0,2,'C',0);
	
		$this->SetXY(45,260);
		$this->SetFont('angsa_n','','12');
		$this->Cell(0,5,'ลงชื่อ........................................................ผู้ให้บริการ               ลงชื่อ........................................................ผู้รับบริการ',0,2,'L',0);
		$this->Cell(0,5,'        (........................................................)                                       (........................................................)',0,2,'L',0);
		$this->SetX(47);
		$this->Cell(0,5,'วันที่........................................................                                  วันที่........................................................',0,2,'L',0);
		$this->SetY(-15);
		$this->SetFont('angsa_B','',10);
		$this->Cell(0,10,'Page '.$this->PageNo().' of {nb}',0,0,'C'); 
	
		//$this->SetFont('angsa_b','',10);
		//$this->Cell(0,10,$iso,0,2,'R',0);
		//$this->SetXY(130,285);
		//$this->SetFont('angsa_n','',10);
		//$this->Cell(0,10,'ISSUE DATE 17/05/2010 ระยะการจัดเก็บ 2 ปี',0,2,'R',0);
		
		$this->SetXY(10,285);
		$this->SetFont('angsa_n','',10);
		$this->Cell(50,10,'อนุมัติใช้วันที่  6/9/2553',0,2,'L',0);
		$this->SetXY(150,285);
		$this->SetFont('angsa_n','',10);
		$this->Cell(50,10,'FM-SEV-01Rev.00',0,2,'R',0);		
	}else  if($flg=="qoutation_1" ){
		
	/*		$this->SetXY(25,250);
			$this->SetFont('angsa_b','','12');
			$this->Cell(0,5,'                               ผู้มีอำนาจสั่งซื้อ                                                                                                           ผู้มีอำนาจเสนอขาย',0,2,'L',0);
			$this->SetFont('angsa_n','','12');
			$this->Cell(0,5,'( AUTHORIZED  SIGNATURE  OF  THE  BUYER )                                                ( AUTHORIZED  SIGNATURE  OF  THE  SELLER )',0,2,'L',0);
			$this->SetXY(25,265);
			$this->SetX(25);
			$this->Cell(0,5,'             ........................................................                                                                                   ........................................................',0,2,'L',0);
			$this->Cell(0,5,'            (........................................................)                                                                                              (เนตรศจี  อิงคะวัต)',0,2,'L',0);
			$this->Cell(0,5,'                                                                                                                                                                ผู้อำนวยการฝ่ายการตลาด',0,2,'L',0);
	
			$this->SetXY(10,260);
			$this->Cell(190,25,'','LRB',0);
			$this->SetY(-15);
			$this->SetFont('angsa_B','',10);
			$this->Cell(0,10,'Page '.$this->PageNo().' of {nb}',0,0,'C'); 
			$this->SetXY(10,285);*/
	}else  if($flg=="qoutation_2" ){
		$this->SetY(-15);
		$this->SetFont('angsa_B','',10);
		$this->Cell(0,10,'Page '.$this->PageNo().' of {nb}',0,0,'C'); 
		$this->SetXY(10,285);
		$this->SetFont('angsa_n','',10);
		$this->Cell(50,10,'อนุมัติใช้วันที่ 6/9/2553',0,2,'L',0);
		$this->SetXY(150,285);
		$this->SetFont('angsa_n','',10);
		$this->Cell(50,10,'FM-MKT-02 Rev. 00',0,2,'R',0);
	}
}

		function Header()
		{
				global $flg;
				global $logo_name;
				global $company_name_th;
				global $company_name_eng;
				global $head_page;
				global $report_name;
				global $account_id;
				global $start_acc;
				global $who_acc1;
				global $address1;
				global $address2;
				global $mname;
				global $year;
				global $depart;
				global $day;
				global $f_1;
				global $f_2;
				global $f_3;
				global $f_4;
				global $f_5;
				global $f_6;
				global $f_7;
				global $f_8;
				global $f_9;
				global $f_10;
				global $f_11;
				global $f_12;
				global $f_13;
				global $f_14;
				global $f_15;
				global $f_16;
				global $f_17;
				global $f_18;
				global $f_19;
				global $f_20;
				global $f_21;
				global $F_134;			
			if($flg=="tiket_1" ){
				
				$this->Image("./test/logo/logo.jpg",10,5,55,12);
				
				$this->SetFont('angsa_b','',13);
				$this->SetXY(160,10);
				$this->SetFont('angsa_b','',16);
				$this->Cell(40,5,'Engineer Service Report',0,2,'R',0);	
								
				$this->SetXY(10,17);
				$this->Cell(0,5,$company_name_th,0,2,'L',0);
				$this->SetFont('angsa_n','',13);
				$this->SetXY(70,17);
				//echo $address1;
				$this->Cell(0,5,$address1,0,2,'L',0);
				$this->SetXY(10,22);
				$this->Cell(0,5,$address2,0,2,'L',0);
				
				$this->Cell(190,2,'','B',0);
				
				$this->SetXY(10,$this->GetY()+2);
				$this->SetFont('angsa_b','',12);
				$this->Cell(30,7,'Sale Person :     ',0,2,'L',0);
				$this->SetXY(30,$this->GetY()-7);
				$this->SetFont('angsa_n','',12);
				$this->Cell(30,7,$f_1,0,2,'L',0);
				
				$this->SetXY(100,$this->GetY()-7);
				$this->SetFont('angsa_b','',12);
				$this->Cell(30,7,'D/M/Y :     ',0,2,'L',0);	
				$this->SetXY(115,$this->GetY()-7);		
				$this->SetFont('angsa_n','',12);
				$this->Cell(30,7,date('d/m/Y',strtotime($f_2)),0,2,'L',0);
				
				$this->SetXY(10,$this->GetY()-9);
				$this->Cell(0,10,'Page '.$this->PageNo().' of {nb}   ',0,0,'R'); 	
				
				$this->SetXY(10,$this->GetY()+11);
				$this->SetFont('angsa_b','',12);
				$this->Cell(30,5,'Customer Name ',1,2,'L',0);
				$this->SetXY(40,$this->GetY()-5);
				$this->SetFont('angsa_n','',12);
				$this->Cell(90,5,$f_3,1,2,'L',0);
				$this->SetXY(130,$this->GetY()-5);
				$this->SetFont('angsa_b','',12);
				$this->Cell(40,5,'Contract Person ',1,2,'L',0);
				$this->SetXY(170,$this->GetY()-5);
				$this->SetFont('angsa_n','',12);
				$this->Cell(30,5,$f_4,1,2,'L',0);	
				
				$this->SetXY(10,$this->GetY()-5);
				$this->SetFont('angsa_b','',12);
				$this->Cell(30,15,'Address ',0,2,'L',0);
				$this->SetXY(10,$this->GetY()-10);
				$this->Cell(30,15,'',1,2,'L',0);
				$this->Rect(40,$this->GetY()-15,90,15);
				$this->SetXY(40,$this->GetY()-15);
				$this->SetFont('angsa_n','',12);
				$add=$f_5;
				$this->MultiCell(90,5,$add,0,2,'L',0);
				
				$nb=max($nb,$this->NbLines(90,$add));
				
				$this->SetXY(130,$this->GetY()-($nb*5));
				$this->SetFont('angsa_b','',12);
				$this->Cell(40,5,'Phone ',1,2,'L',0);
				$this->SetXY(170,$this->GetY()-5);
				$this->SetFont('angsa_n','',12);
				$this->Cell(30,5,$f_6,1,2,'L',0);	
				
				$this->SetXY(130,$this->GetY());
				$this->SetFont('angsa_b','',12);
				$this->Cell(40,5,'Fax ',1,2,'L',0);
				$this->SetXY(170,$this->GetY()-5);
				$this->SetFont('angsa_n','',12);
				$this->Cell(30,5,$f_7,1,2,'L',0);		
				
				$this->SetXY(130,$this->GetY());
				$this->SetFont('angsa_b','',12);
				$this->Cell(40,5,'Optimized Solution Engineer ',1,2,'L',0);
				$this->SetXY(170,$this->GetY()-5);
				$this->SetFont('angsa_n','',12);
				$this->Cell(30,5,$f_8,1,2,'L',0);	
				
			
			}else  if($flg=="tiket_2" ){
				$this->SetX(10);
				$this->Cell(190,18,'','B',0);
				
				$this->SetX(10);
				$this->Image("./test/logo/logo.jpg",150,5,50,15);
				$this->SetFont('angsa_b','',$size15);
				$this->SetXY(10,10);
				$this->Cell(0,5,$company_name_th,0,2,'L',0);
				$this->SetFont('angsa_n','',$size15);
				$this->SetXY(10,15);
				$this->Cell(0,5,$address1,0,2,'L',0);
				$this->SetXY(10,20);
				$this->Cell(0,5,$address2,0,2,'L',0);
				
				$this->SetXY(170,20);
				$this->SetFont('angsa_b','',$size15);
				$this->Cell(0,5,'เลขที่เอกสาร '.$F_134,0,2,'L',0);
				
				$this->SetXY(85,30);
				$this->SetFont('angsa_b','',"28");
				$this->Cell(50,7,$report_name,0,2,'C',0);
			}else  if($flg=="qoutation_1" ){
				$this->SetXY(10,31);
				$this->Cell(190,255,'','BLRT',0);
				$this->Image("./test/logo/logo.jpg",10,7,55,12);

				$this->SetFont('angsa_b','',13);
				$this->SetXY(70,7);
				$this->Cell(0,5,$company_name_th,0,2,'L',0);
				$this->SetFont('angsa_n','',13);
				$this->SetXY(70,12);
				//echo $address1;
				$this->Cell(0,5,$address1,0,2,'L',0);
				$this->SetXY(70,17);
				$this->Cell(0,5,$address2,0,2,'L',0);
				
				$this->SetXY(80,$this->GetY());
				$this->SetFont('angsa_b','',"16");
				$this->Cell(50,7,$report_name,0,2,'C',0);
				
				//$this->SetXY(10,$this->GetY());
				//$this->Cell(190,2,'','B',0);
//----------------------------------------------------------------------------------------------------------------------------------				
				$this->SetXY(10,$this->GetY()+2);
				$this->SetFont('angsa_b','',12);
				$this->Cell(30,7,'เรียน / Attention ',1,2,'L',0);
				$this->SetXY(40,$this->GetY()-7);
				$this->SetFont('angsa_n','',12);
				$this->Cell(90,7,$f_1." ".$f_2." ".$f_3,1,2,'L',0);
				$this->SetXY(130,$this->GetY()-7);
				$this->SetFont('angsa_b','',12);
				$this->Cell(40,7,'ใบเสนอราคาเลขที่ / No. ',1,2,'L',0);
				$this->SetXY(170,$this->GetY()-7);
				$this->SetFont('angsa_n','',12);
				$this->Cell(30,7,$f_4,1,2,'L',0);			

				$this->SetXY(10,$this->GetY());
				$this->SetFont('angsa_b','',12);
				$this->Cell(30,7,'บริษัท / Company ',1,2,'L',0);
				$this->SetXY(40,$this->GetY()-7);
				$this->SetFont('angsa_n','',12);
				$this->Cell(90,7,$f_5,1,2,'L',0);
				$this->SetXY(130,$this->GetY()-7);
				$this->SetFont('angsa_b','',12);
				$this->Cell(40,7,'วันที่ / Date ',1,2,'L',0);
				$this->SetXY(170,$this->GetY()-7);
				$this->SetFont('angsa_n','',12);
				$this->Cell(30,7,decodedate1($f_6,0),1,2,'L',0);	
				
				$this->SetXY(10,$this->GetY());
				$this->SetFont('angsa_b','',12);
				$this->Cell(30,14,'ที่อยู่ / Address ',1,2,'L',0);
				$this->SetXY(40,$this->GetY()-14);
				$this->SetFont('angsa_n','',12);
				$this->MultiCell(90,14,$f_7." ".$f_8." ".$f_9." ".$f_10,1,2,'L',0);
				$this->SetXY(130,$this->GetY()-14);
				$this->SetFont('angsa_b','',12);
				$this->Cell(40,7,'กำหนดยืนราคา / Validity ',1,2,'L',0);
				$this->SetXY(170,$this->GetY()-7);
				$this->SetFont('angsa_n','',12);
				$this->Cell(30,7,decodedate1($f_11,0),1,2,'L',0);					

				$this->SetXY(130,$this->GetY());
				$this->SetFont('angsa_b','',12);
				$this->Cell(40,7,'กำหนดส่งของ / Delivery ',1,2,'L',0);
				$this->SetXY(170,$this->GetY()-7);
				$this->SetFont('angsa_n','',12);
				$this->Cell(30,7,decodedate1($f_12,0),1,2,'L',0);	

				$this->SetXY(10,$this->GetY());
				$this->SetFont('angsa_b','',12);
				$this->Cell(30,7,'โทรศัพท์ / Telephone ',1,2,'L',0);
				$this->SetXY(40,$this->GetY()-7);
				$this->SetFont('angsa_n','',12);
				$this->Cell(90,7,$f_13,1,2,'L',0);
				$this->SetXY(130,$this->GetY()-7);
				$this->SetFont('angsa_b','',12);
				$this->Cell(40,7,'การรับประกัน / Warranty ',1,2,'L',0);
				$this->SetXY(170,$this->GetY()-7);
				$this->SetFont('angsa_n','',12);
				if($f_16=="--- None ---"){
					$f_14="";
				}
				$this->Cell(30,7,$f_14,1,2,'L',0);	

				$this->SetXY(10,$this->GetY());
				$this->SetFont('angsa_b','',12);
				$this->Cell(30,7,'แฟ็กซ์ / Fax ',1,2,'L',0);
				$this->SetXY(40,$this->GetY()-7);
				$this->SetFont('angsa_n','',12);
				$this->Cell(90,7,$f_15,1,2,'L',0);
				$this->SetXY(130,$this->GetY()-7);
				$this->SetFont('angsa_b','',12);
				$this->Cell(40,7,'การชำระเงิน / Payment Tern ',1,2,'L',0);
				$this->SetXY(170,$this->GetY()-7);
				$this->SetFont('angsa_n','',12);
				if($f_16=="--- None ---"){
					$f_16="";
				}
				$this->Cell(30,7,$f_16,1,2,'L',0);		
				
				$this->SetXY(10,$this->GetY());
				$this->SetFont('angsa_b','',12);
				$this->Cell(30,7,'Email : ',1,2,'L',0);
				$this->SetXY(40,$this->GetY()-7);
				$this->SetFont('angsa_n','',12);
				$this->Cell(90,7,$f_17,1,2,'L',0);
				$this->SetXY(130,$this->GetY()-7);
				$this->SetFont('angsa_n','',12);
				$this->Cell(70,7,$f_18." ".$f_19." ( ".$f_20." )",1,2,'L',0);																					
			}else  if($flg=="qoutation_2" ){
				$this->SetX(10);
				//$this->Cell(190,255,'','LRT',0);
				$this->Image("./test/logo/logo.jpg",11,7,60,50);

				$this->SetFont('angsa_b','',$size15);
				$this->SetXY(10,23);
				$this->Cell(0,5,'Project  :  '.$company_name_th,0,2,'L',0);
				$this->SetXY(10,28);
				$this->Cell(0,5,'Attn  :  '.$report_name,0,2,'L',0);
				$this->SetXY(173,$this->GetY()-6);
				$this->Cell(50,7,encodedate_slat($address1),0,2,'C',0);
				//$this->SetFont('angsa_n','',$size15);
				//$this->SetXY(65,12);
				
				//$this->SetXY(65,17);
				//$this->Cell(0,5,$address2,0,2,'L',0);
				
				//$this->SetXY(160,16);
				//$this->SetFont('angsa_b','',"12");
				
				//$this->SetX(10);
				//$this->Cell(190,2,'','B',0);
			}
		}

		function totalPage(){
			return ceil($this->total_rows/$this->max_rows); 
		}


		function SetThaiFont(){
			$this->AddFont('AngsanaNew','','angsa.php');
			$this->AddFont('AngsanaNew','B','angsab.php');
			$this->AddFont('AngsanaNew','I','angsai.php');
			$this->AddFont('AngsanaNew','IB','angsaz.php');
			$this->AddFont('CordiaNew','','cordia.php');
			$this->AddFont('CordiaNew','B','cordiab.php');
			$this->AddFont('CordiaNew','I','cordiai.php');
			$this->AddFont('CordiaNew','IB','cordiaz.php');
			$this->AddFont('Tahoma','','tahoma.php');
			$this->AddFont('Tahoma','B','tahomab.php');
			$this->AddFont('BrowalliaNew','','browa.php');
			$this->AddFont('BrowalliaNew','B','browab.php');
			$this->AddFont('BrowalliaNew','I','browai.php');
			$this->AddFont('BrowalliaNew','IB','browaz.php');
			$this->AddFont('KoHmu','','kohmu.php');
			$this->AddFont('KoHmu2','','kohmu2.php');
			$this->AddFont('KoHmu3','','kohmu3.php');
			$this->AddFont('MicrosoftSansSerif','','micross.php');
			$this->AddFont('PLE_Cara','','plecara.php');
			$this->AddFont('PLE_Care','','plecare.php');
			$this->AddFont('PLE_Care','B','plecareb.php');
			$this->AddFont('PLE_Joy','','plejoy.php');
			$this->AddFont('PLE_Tom','','pletom.php');
			$this->AddFont('PLE_Tom','B','pletomb.php');
			$this->AddFont('PLE_TomOutline','','pletomo.php');
			$this->AddFont('PLE_TomWide','','pletomw.php');
			$this->AddFont('DilleniaUPC','','dill.php');
			$this->AddFont('DilleniaUPC','B','dillb.php');
			$this->AddFont('DilleniaUPC','I','dilli.php');
			$this->AddFont('DilleniaUPC','IB','dillz.php');
			$this->AddFont('EucrosiaUPC','','eucro.php');
			$this->AddFont('EucrosiaUPC','B','eucrob.php');
			$this->AddFont('EucrosiaUPC','I','eucroi.php');
			$this->AddFont('EucrosiaUPC','IB','eucroz.php');
			$this->AddFont('FreesiaUPC','','free.php');
			$this->AddFont('FreesiaUPC','B','freeb.php');
			$this->AddFont('FreesiaUPC','I','freei.php');
			$this->AddFont('FreesiaUPC','IB','freez.php');
			$this->AddFont('IrisUPC','','iris.php');
			$this->AddFont('IrisUPC','B','irisb.php');
			$this->AddFont('IrisUPC','I','irisi.php');
			$this->AddFont('IrisUPC','IB','irisz.php');
			$this->AddFont('JasmineUPC','','jasm.php');
			$this->AddFont('JasmineUPC','B','jasmb.php');
			$this->AddFont('JasmineUPC','I','jasmi.php');
			$this->AddFont('JasmineUPC','IB','jasmz.php');
			$this->AddFont('KodchiangUPC','','kodc.php');
			$this->AddFont('KodchiangUPC','B','kodc.php');
			$this->AddFont('KodchiangUPC','I','kodci.php');
			$this->AddFont('KodchiangUPC','IB','kodcz.php');
			$this->AddFont('LilyUPC','','lily.php');
			$this->AddFont('LilyUPC','B','lilyb.php');
			$this->AddFont('LilyUPC','I','lilyi.php');
			$this->AddFont('LilyUPC','IB','lilyz.php');
		}

}//end class

?>