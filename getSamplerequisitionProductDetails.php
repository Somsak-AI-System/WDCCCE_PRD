<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include("config.inc.php");

include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/generate_MYSQL.php");

global $generate;
$generate = new generate($dbconfig ,"DB");

if($_REQUEST['function'] == "sr_finish"){
	$sql = "
    SELECT 
	'' AS sr_finish_id, '--None--' AS sr_finish_name  UNION SELECT 
	'1' AS sr_finish_id, 'BK-(sanded 2 sides)-Backer' AS sr_finish_name  UNION SELECT 
	'2' AS sr_finish_id, 'GL-Aligator-GAA' AS sr_finish_name  UNION SELECT 
	'3' AS sr_finish_id, 'GL-Anti Finger print-AFX' AS sr_finish_name  UNION SELECT 
	'4' AS sr_finish_id, 'GL-Aria-WAR' AS sr_finish_name  UNION SELECT 
	'5' AS sr_finish_id, 'GL-Ash-GSH' AS sr_finish_name  UNION SELECT 
	'6' AS sr_finish_id, 'GL-Blazing Delight-SLA' AS sr_finish_name  UNION SELECT 
	'7' AS sr_finish_id, 'GL-Burnished Wood-WBR' AS sr_finish_name  UNION SELECT 
	'8' AS sr_finish_id, 'GL-Cadiz-CDZ,PCD' AS sr_finish_name  UNION SELECT 
	'9' AS sr_finish_id, 'GL-Caravan Leather-GFC' AS sr_finish_name  UNION SELECT 
	'10' AS sr_finish_id, 'GL-Coast Line-CTL' AS sr_finish_name  UNION SELECT 
	'11' AS sr_finish_id, 'GL-Dazzle-GDZ' AS sr_finish_name  UNION SELECT 
	'12' AS sr_finish_id, 'GL-Embossed Fleur-EFL' AS sr_finish_name  UNION SELECT 
	'13' AS sr_finish_id, 'GL-Embossed Interweave-EW' AS sr_finish_name  UNION SELECT 
	'14' AS sr_finish_id, 'GL-Fawn-SFN' AS sr_finish_name  UNION SELECT 
	'15' AS sr_finish_id, 'GL-Gloss-GPG,GPL,MG,PCA,SGA,SGB,WGA' AS sr_finish_name  UNION SELECT 
	'16' AS sr_finish_id, 'GL-Handscraped-WQA' AS sr_finish_name  UNION SELECT 
	'17' AS sr_finish_id, 'GL-High Definition Gloss-HDG' AS sr_finish_name  UNION SELECT 
	'18' AS sr_finish_id, 'GL-High Gloss-GP,PCB' AS sr_finish_name  UNION SELECT 
	'19' AS sr_finish_id, 'GL-Jupiter-PJP,SJP' AS sr_finish_name  UNION SELECT 
	'20' AS sr_finish_id, 'GL-Leather-GSL' AS sr_finish_name  UNION SELECT 
	'21' AS sr_finish_id, 'GL-Matt-GPM,GSM' AS sr_finish_name  UNION SELECT 
	'22' AS sr_finish_id, 'GL-Microlie v-GSI' AS sr_finish_name  UNION SELECT 
	'23' AS sr_finish_id, 'GL-Microline V-GFI' AS sr_finish_name  UNION SELECT 
	'24' AS sr_finish_id, 'GL-microlines v-GWI' AS sr_finish_name  UNION SELECT 
	'25' AS sr_finish_id, 'GL-Onda Horizontal-GYA' AS sr_finish_name  UNION SELECT 
	'26' AS sr_finish_id, 'GL-onda V-GPN,GSN' AS sr_finish_name  UNION SELECT 
	'27' AS sr_finish_id, 'GL-Pacific Trail-PTR,SPP,WPP' AS sr_finish_name  UNION SELECT 
	'28' AS sr_finish_id, 'GL-Parallel streaks-WPA' AS sr_finish_name  UNION SELECT 
	'29' AS sr_finish_id, 'GL-Quarter Cut-GWK' AS sr_finish_name  UNION SELECT 
	'30' AS sr_finish_id, 'GL-Rafia-SRF,WRF' AS sr_finish_name  UNION SELECT 
	'31' AS sr_finish_id, 'GL-Raw silk-GSR,GWR' AS sr_finish_name  UNION SELECT 
	'32' AS sr_finish_id, 'GL-Raw Silk-GPR' AS sr_finish_name  UNION SELECT 
	'33' AS sr_finish_id, 'GL-Retro-SRT' AS sr_finish_name  UNION SELECT 
	'34' AS sr_finish_id, 'GL-Santhia-WSN' AS sr_finish_name  UNION SELECT 
	'35' AS sr_finish_id, 'GL-Satin-SAT' AS sr_finish_name  UNION SELECT 
	'36' AS sr_finish_id, 'GL-Scuff-Resistant Gloss-SR' AS sr_finish_name  UNION SELECT 
	'37' AS sr_finish_id, 'GL-Sierra-SIR' AS sr_finish_name  UNION SELECT 
	'38' AS sr_finish_id, 'GL-Soft Touch-GSS,' AS sr_finish_name  UNION SELECT 
	'39' AS sr_finish_id, 'GL-Soft Touch/Satin-PAT' AS sr_finish_name  UNION SELECT 
	'40' AS sr_finish_id, 'GL-Sparkle-SPR' AS sr_finish_name  UNION SELECT 
	'41' AS sr_finish_id, 'GL-Stone-GFO' AS sr_finish_name  UNION SELECT 
	'42' AS sr_finish_id, 'GL-Suede-GSA-D,GWA-E,GFA,GFS,GPA-C,PCS,WGE' AS sr_finish_name  UNION SELECT 
	'43' AS sr_finish_id, 'GL-Summer Bloom-SUA' AS sr_finish_name  UNION SELECT 
	'44' AS sr_finish_id, 'GL-Super Gloss-HGA, HGP,HGW' AS sr_finish_name  UNION SELECT 
	'45' AS sr_finish_id, 'GL-Super Suede-SSA,WGS' AS sr_finish_name  UNION SELECT 
	'46' AS sr_finish_id, 'GL-Synchro-SY1,SY2' AS sr_finish_name  UNION SELECT 
	'47' AS sr_finish_id, 'GL-Techno steel-GPT,GST' AS sr_finish_name  UNION SELECT 
	'48' AS sr_finish_id, 'GL-Techno Steel-GWT' AS sr_finish_name  UNION SELECT 
	'49' AS sr_finish_id, 'GL-Texmex-GFT,GTM' AS sr_finish_name  UNION SELECT 
	'50' AS sr_finish_id, 'GL-Trace-TRC' AS sr_finish_name  UNION SELECT 
	'51' AS sr_finish_id, 'GL-Veracious Bark-WVB,PVB,GCN' AS sr_finish_name  UNION SELECT 
	'52' AS sr_finish_id, 'GL-Vertical Line-GPV' AS sr_finish_name  UNION SELECT 
	'53' AS sr_finish_id, 'GL-Vertical Lines-GSV' AS sr_finish_name  UNION SELECT 
	'54' AS sr_finish_id, 'GL-Wackey Wicker-SWA' AS sr_finish_name  UNION SELECT 
	'55' AS sr_finish_id, 'GL-Zero Reflection-GWM' AS sr_finish_name  UNION SELECT 
	'56' AS sr_finish_id, 'GL-M-Matt-GCM' AS sr_finish_name  UNION SELECT 
	'57' AS sr_finish_id, 'GL-M-Metalics-GMA,GMC' AS sr_finish_name  UNION SELECT 
	'58' AS sr_finish_id, 'GL-M-MIrror-GM' AS sr_finish_name  UNION SELECT 
	'59' AS sr_finish_id, 'GL-M-Stone-GEO,GGO,GKO,GZO' AS sr_finish_name  UNION SELECT 
	'60' AS sr_finish_id, 'GL-M-Vertical-GEV' AS sr_finish_name  UNION SELECT 
	'61' AS sr_finish_id, 'GL-M-Zero Reflection-GEM,GGM,GKM,GZM' AS sr_finish_name  UNION SELECT 
	'62' AS sr_finish_id, 'NM-Buff Leather-BFL' AS sr_finish_name  UNION SELECT 
	'63' AS sr_finish_id, 'NM-Chiseled Wood-CHW' AS sr_finish_name  UNION SELECT 
	'64' AS sr_finish_id, 'NM-Classic Quilted-QLT' AS sr_finish_name  UNION SELECT 
	'65' AS sr_finish_id, 'NM-Cleaved Stone-CST' AS sr_finish_name  UNION SELECT 
	'66' AS sr_finish_id, 'NM-Cosmic Connection-COC' AS sr_finish_name  UNION SELECT 
	'67' AS sr_finish_id, 'NM-Disco-DSC' AS sr_finish_name  UNION SELECT 
	'68' AS sr_finish_id, 'NM-Engraved-ENG' AS sr_finish_name  UNION SELECT 
	'69' AS sr_finish_id, 'NM-Essentia-NPN' AS sr_finish_name  UNION SELECT 
	'70' AS sr_finish_id, 'NM-Extra Matt-XMT' AS sr_finish_name  UNION SELECT 
	'71' AS sr_finish_id, 'NM-Glazed-GLZ,PWD' AS sr_finish_name  UNION SELECT 
	'72' AS sr_finish_id, 'NM-Gloss-EGM' AS sr_finish_name  UNION SELECT 
	'73' AS sr_finish_id, 'NM-Khadi-KHD,TEX' AS sr_finish_name  UNION SELECT 
	'74' AS sr_finish_id, 'NM-Magical Flow-MF' AS sr_finish_name  UNION SELECT 
	'75' AS sr_finish_id, 'NM-Metal Spell-MTS' AS sr_finish_name  UNION SELECT 
	'76' AS sr_finish_id, 'NM-Novel Gloss-NGL' AS sr_finish_name  UNION SELECT 
	'77' AS sr_finish_id, 'NM-Painted Wood-NPW' AS sr_finish_name  UNION SELECT 
	'78' AS sr_finish_id, 'NM-Pure Grain-PGR' AS sr_finish_name  UNION SELECT 
	'79' AS sr_finish_id, 'NM-Quadro-QUD' AS sr_finish_name  UNION SELECT 
	'80' AS sr_finish_id, 'NM-Raw Bark-RBK' AS sr_finish_name  UNION SELECT 
	'81' AS sr_finish_id, 'NM-Simpatico-SMP' AS sr_finish_name  UNION SELECT 
	'82' AS sr_finish_id, 'NM-M-Soft Buff-SBF' AS sr_finish_name  UNION SELECT 
	'83' AS sr_finish_id, 'NM-Suede-NSB,NPA-B,NWA-B,NCC,NCG' AS sr_finish_name  UNION SELECT 
	'84' AS sr_finish_id, 'NM-Super Gloss-NCG' AS sr_finish_name  UNION SELECT 
	'85' AS sr_finish_id, 'NM-Torrent-TRN' AS sr_finish_name  UNION SELECT 
	'86' AS sr_finish_id, 'NM-Voodo-VOD' AS sr_finish_name  UNION SELECT 
	'87' AS sr_finish_id, 'SP-Crimp-CM' AS sr_finish_name  UNION SELECT 
	'88' AS sr_finish_id, 'SP-Cross Bar-CRB' AS sr_finish_name  UNION SELECT 
	'89' AS sr_finish_id, 'SP-Cross Lines-CRL' AS sr_finish_name  UNION SELECT 
	'90' AS sr_finish_id, 'SP-High Streak-HST' AS sr_finish_name  UNION SELECT 
	'91' AS sr_finish_id, 'SP-Illusion-ILS' AS sr_finish_name  UNION SELECT 
	'92' AS sr_finish_id, 'SP-M-Metal Brushed -NM' AS sr_finish_name  UNION SELECT 
	'93' AS sr_finish_id, 'SP-Microlines Vertical-MLV' AS sr_finish_name  UNION SELECT 
	'94' AS sr_finish_id, 'SP-Organic-OG' AS sr_finish_name  UNION SELECT 
	'95' AS sr_finish_id, 'SP-Paragon-PAR' AS sr_finish_name  UNION SELECT 
	'96' AS sr_finish_id, 'SP-Sculpted-SCT' AS sr_finish_name  UNION SELECT 
	'97' AS sr_finish_id, 'SP-Soft Brushed-NM9030S' AS sr_finish_name  UNION SELECT 
	'98' AS sr_finish_id, 'SP-Suede-U,-' AS sr_finish_name  UNION SELECT 
	'99' AS sr_finish_id, 'SP-Vertical Lines-VER' AS sr_finish_name  UNION SELECT 
	'100' AS sr_finish_id, 'SP-Ariz-ARZ' AS sr_finish_name  UNION SELECT 
	'101' AS sr_finish_id, 'SP-Atune-ATN' AS sr_finish_name  UNION SELECT 
	'102' AS sr_finish_id, 'SP-Brisk-BSK' AS sr_finish_name  UNION SELECT 
	'103' AS sr_finish_id, 'SP-Matt-MAT/P' AS sr_finish_name  UNION SELECT 
	'104' AS sr_finish_id, 'NM-Rocka-RKA' AS sr_finish_name  UNION SELECT 
	'105' AS sr_finish_id, 'NM-Roso-RSP' AS sr_finish_name  UNION SELECT 
	'106' AS sr_finish_id, 'NM-Taurus-TRS' AS sr_finish_name
		
		";
	$data = $generate->process($sql,"all");
	for($i=0;$i<count($data);$i++){
		$data_details[$i]['id'] = $data[$i]['sr_finish_id'];
		$data_details[$i]['name'] = $data[$i]['sr_finish_name'];
	}
		
	echo json_encode($data_details);
}

if($_REQUEST['function'] == "sr_size_mm"){
	$sql = "
	SELECT 
	'' AS sr_size_mm_id, '--None--' AS sr_size_mm_name  UNION SELECT 
	'1' AS sr_size_mm_id, 'A5' AS sr_size_mm_name  UNION SELECT 
	'2' AS sr_size_mm_id, 'A4' AS sr_size_mm_name  UNION SELECT 
	'3' AS sr_size_mm_id, 'A3' AS sr_size_mm_name  UNION SELECT 
	'4' AS sr_size_mm_id, '44x67mm' AS sr_size_mm_name  UNION SELECT 
	'5' AS sr_size_mm_id, '64x128mm' AS sr_size_mm_name  UNION SELECT 
	'6' AS sr_size_mm_id, '89x127mm' AS sr_size_mm_name  UNION SELECT 
	'7' AS sr_size_mm_id, '300x300mm' AS sr_size_mm_name  UNION SELECT 
	'8' AS sr_size_mm_id, '300x600mm' AS sr_size_mm_name  UNION SELECT 
	'9' AS sr_size_mm_id, '100x100mm' AS sr_size_mm_name  UNION SELECT 
	'10' AS sr_size_mm_id, '600x1200mm' AS sr_size_mm_name  UNION SELECT 
	'11' AS sr_size_mm_id, '1220x2440mm' AS sr_size_mm_name  UNION SELECT 
	'12' AS sr_size_mm_id, '1300x3050mm' AS sr_size_mm_name  UNION SELECT 
	'13' AS sr_size_mm_id, '1525x3660mm' AS sr_size_mm_name  UNION SELECT 
	'14' AS sr_size_mm_id, '1830x3660mm' AS sr_size_mm_name  UNION SELECT 
	'15' AS sr_size_mm_id, 'Customized' AS sr_size_mm_name 
		
		";
	$data = $generate->process($sql,"all");
	for($i=0;$i<count($data);$i++){
		$data_details[$i]['id'] = $data[$i]['sr_size_mm_id'];
		$data_details[$i]['name'] = $data[$i]['sr_size_mm_name'];
	}
		
	echo json_encode($data_details);

}


if($_REQUEST['function'] == "sr_thickness_mm"){
	$sql = "
	SELECT 
	'' AS sr_thickness_mm_id, '--None--' AS sr_thickness_mm_name  UNION SELECT 
	'1' AS sr_thickness_mm_id, '0.5PF' AS sr_thickness_mm_name  UNION SELECT 
	'2' AS sr_thickness_mm_id, '0.6' AS sr_thickness_mm_name  UNION SELECT 
	'3' AS sr_thickness_mm_id, '0.6PF' AS sr_thickness_mm_name  UNION SELECT 
	'4' AS sr_thickness_mm_id, '0.7' AS sr_thickness_mm_name  UNION SELECT 
	'5' AS sr_thickness_mm_id, '0.8' AS sr_thickness_mm_name  UNION SELECT 
	'6' AS sr_thickness_mm_id, '0.9' AS sr_thickness_mm_name  UNION SELECT 
	'7' AS sr_thickness_mm_id, '1' AS sr_thickness_mm_name  UNION SELECT 
	'8' AS sr_thickness_mm_id, '1.2' AS sr_thickness_mm_name  UNION SELECT 
	'9' AS sr_thickness_mm_id, '1.5' AS sr_thickness_mm_name  UNION SELECT 
	'10' AS sr_thickness_mm_id, '2' AS sr_thickness_mm_name  UNION SELECT 
	'11' AS sr_thickness_mm_id, '3' AS sr_thickness_mm_name  UNION SELECT 
	'12' AS sr_thickness_mm_id, '4' AS sr_thickness_mm_name  UNION SELECT 
	'13' AS sr_thickness_mm_id, '5' AS sr_thickness_mm_name  UNION SELECT 
	'14' AS sr_thickness_mm_id, '6' AS sr_thickness_mm_name  UNION SELECT 
	'15' AS sr_thickness_mm_id, '8' AS sr_thickness_mm_name  UNION SELECT 
	'16' AS sr_thickness_mm_id, '10' AS sr_thickness_mm_name  UNION SELECT 
	'17' AS sr_thickness_mm_id, '12' AS sr_thickness_mm_name  UNION SELECT 
	'18' AS sr_thickness_mm_id, '13' AS sr_thickness_mm_name  UNION SELECT 
	'19' AS sr_thickness_mm_id, '16' AS sr_thickness_mm_name  UNION SELECT 
	'20' AS sr_thickness_mm_id, '18' AS sr_thickness_mm_name  UNION SELECT 
	'21' AS sr_thickness_mm_id, '20' AS sr_thickness_mm_name  UNION SELECT 
	'22' AS sr_thickness_mm_id, '25' AS sr_thickness_mm_name  UNION SELECT 
	'23' AS sr_thickness_mm_id, '30' AS sr_thickness_mm_name 
		
		";
	$data = $generate->process($sql,"all");
	for($i=0;$i<count($data);$i++){
		$data_details[$i]['id'] = $data[$i]['sr_thickness_mm_id'];
		$data_details[$i]['name'] = $data[$i]['sr_thickness_mm_name'];
	}
		
	echo json_encode($data_details);

}

?>
