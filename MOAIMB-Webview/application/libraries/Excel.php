<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Excel {

    private $excel;

    public function __construct() {
        require_once APPPATH . 'third_party/PHPExcel.php';
        $this->excel = new PHPExcel();
        $this->CI = & get_instance();

    }

    public function load($path) {
    	$extension = end(explode('.',$path));
    	//if($extension=="csv"){
        //	$objReader = PHPExcel_IOFactory::createReader('CSV');
    	//}else{
    		$objReader = PHPExcel_IOFactory::createReader('Excel5');
    	//}
        $this->excel = $objReader->load($path);
    }

    public function save($path) {
    	$extension = end(explode('.',$path));
    	//if($extension=="csv"){
    	//	$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'CSV');
    	//}else{
    		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
    	//}

        $objWriter->save($path);
    }

    public function stream($filename, $data = null) {
    	$extension = end(explode('.',$filename));
		//echo $extension;exit();
    	$config_filed = $this->CI ->config->item('export');
    	$a_field_string = $config_filed['string'];
        if ($data != null) {
            $col = 'A';
            foreach ($data[0] as $key => $val) {
            	if($key!="id"){
	                $objRichText = new PHPExcel_RichText();
	                $objPayable = $objRichText->createTextRun(str_replace("_", " ", $key));
	                $this->excel->getActiveSheet()->getCell($col . '1')->setValue($objRichText);
	                $col++;
            	}
            }

            $rowNumber = 2;
            foreach ($data as $row) {
                $col = 'A';

                foreach ($row as $key => $cell) {
                    //$this->excel->getActiveSheet()->setCellValue($col . $rowNumber,$cell)->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
                    if($key!="id"){
	                	if( in_array($key,$a_field_string)){
	                    	$this->excel->getActiveSheet()->setCellValueExplicit($col . $rowNumber,$cell,PHPExcel_Cell_DataType::TYPE_STRING);
	                    } else{
	                		$this->excel->getActiveSheet()->setCellValue($col . $rowNumber,@iconv("tis-620","UTF-8",$cell));
	                    }
	                	$col++;
                    }
                }
                $rowNumber++;
            }
        }

		/*exit();
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
*/
		//if($extension=="csv"){
		//	header('Content-type: text/csv');
		//}else{
			header('Content-type: application/ms-excel');
		//}

        header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
        header("Cache-control: private");
        header("Content-Transfer-Encoding: BINARY");

        //if($extension=="csv"){
        //	$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'CSV');
        //}else  if($extension=="xls"){
        	$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        //}

        //force user to download the Excel file without writing it to server's HD
        //$objWriter->save('php://output');
        $objWriter->save("export/".$filename);
        return "export/".$filename;

		/*
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save("export/$filename");
        header("location: " . base_url() . "export/$filename");
        unlink(base_url() . "export/$filename");
        */
    }

    public function __call($name, $arguments) {
        if (method_exists($this->excel, $name)) {
            return call_user_func_array(array($this->excel, $name), $arguments);
        }
        return null;
    }
}