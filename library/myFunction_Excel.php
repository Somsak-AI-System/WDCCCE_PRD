<?php 
function ExportDataLarge1($data, $filename, $start =0, $end=0, $sp_column=array())
{
	$col = 0;
	$excel = new ExportDataExcel('browser');
	$excel->filename = $filename;

	$data_col =array();
	$excel->initialize();
	foreach ($data[0] as $key => $val) {
		$row[]=$key ;
    }
	
	$excel->addRow($row);

  	foreach($data as $datarow)
  	{
		$excel->addRow($datarow);
   	}
  	$excel->finalize();
  	exit;	
}

/*function ExportDataLarge($data, $filename, $start =0, $end=0, $sp_column=array())
{

	$fileName = $filename;
	
	header("Content-Type: application/x-msexcel; name=\"$fileName\"");
	header("Content-Disposition: inline; filename=\"$fileName\"");
	header("Pragma:no-cache");
	
	foreach ($data[0] as $key => $value) {
		$row[]= $key;		
	}
	echo implode("\t", array_values($row)) . "\n"; 

  	exit;	
}*/


function ExportDataLarge($data, $filename, $start =0, $end=0, $sp_column=array())
{
	$fileName = "Reports.csv";

	header("Content-Disposition: attachment; filename=\"$fileName\""); 
	//header("Content-Type:text/csv;charset=UTF-8");
	header("Content-Type:text/csv;");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
	header("Cache-Control: post-check=0, pre-check=0", false );

	foreach ($data[0] as $key => $value) {
		//$row[]= iconv( 'UTF-8' ,'TIS-620',$key);
		$row[]= $key;
	}


	$header = implode("\",\"",array_values($row));
	$header = "\"" .$header;
	$header .= "\"\r\n";
	//echo iconv("UTF-8", "TIS-620", $header);
	echo $header;

	foreach($data as $key => $value) {
	    $body = implode("\",\"",$value);
		$body = "\"" .$body;
		$body .= "\"\r\n";
		//echo iconv( 'UTF-8' ,'TIS-620',$body);
		echo $body;
	}

  	exit;	
}
