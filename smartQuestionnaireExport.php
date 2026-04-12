<?php
require_once ("config.inc.php");
date_default_timezone_set("Asia/Bangkok");
global $path,$url_path;

// ini_set('memory_limit', '4024M');

$path = $root_directory;

require_once ($path."library/dbconfig.php");
require_once ($path."library/myLibrary_mysqli.php");
include_once ($path."library/xlsxwriter.class.php");

$myLibrary_mysqli = new myLibrary_mysqli();
$myLibrary_mysqli->_dbconfig = $dbconfig;

$crmid = $_POST['crmID'];
$questionnairetemplateid = $_POST['questionnairetemplateid'];

$query = "call p_questionnaire_export(".$questionnairetemplateid.", ".$crmid.")";
$dataexport = $myLibrary_mysqli->select($query);

// echo '<pre>'; print_r($query); echo '</pre>';
// exit();
$header = [];
foreach($dataexport as $index => $row){
    if($index == 0){
        foreach(array_keys($row) as $key){
            $header[$key] = 'string';
        }
    } 
}

$exportDir = 'export_csv/ExportExcel';
if(!is_dir($exportDir)){
    mkdir($exportDir,0777,true);
}

$fileName = date('YmdHms').'.xlsx';
$writer = new XLSXWriter();
$writer->writeSheetHeader('Sheet1', $header );
foreach($dataexport as $row){
    $writer->writeSheetRow('Sheet1', $row );
}

$exportFilePath = $exportDir.'/'.$fileName;
$writer->writeToFile($exportFilePath);

$response_data = [
    'Type' => 'S',
    'Message' => 'Success',
    'fileName'=>$fileName,
    'filePath'=>$exportFilePath
];

echo json_encode($response_data);
