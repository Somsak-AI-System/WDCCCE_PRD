<?php defined('BASEPATH') OR exit('No direct script access allowed.');
//$this->load->library('ExportData');
include(APPPATH.'libraries/php-export-data.class.php');

if (!function_exists('ExportDataLarge')) {
	
 function ExportDataLarge($data, $filename, $start, $end, $sp_column=array())
  {
     $col = 0;
$excel = new ExportDataExcel('browser');
  //  $excel = new ExportDataExcel('file');
$excel->filename = $filename;

//$excel = new ExportDataExcel('browser');
//$excel->filename = "test.xls";

$data_col =array();
$excel->initialize();
//print_r($data[0]);
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


}
