<?php defined('BASEPATH') OR exit('No direct script access allowed.');
 $this->load->library("excel");
if (!function_exists('ExportExcel')) {
	
 function ExportExcel($data, $filename, $start, $end, $sp_column=array())
{
 	 	$objPHPExcel = new PHPExcel();
		
		$styleArray = array(
				  'borders' => array(
					  'allborders' => array(
						  'style' => PHPExcel_Style_Border::BORDER_THIN
					  )
					 )
  		);
		$objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('A1:IV1')->getFont()->setName('arial')->setSize(10);	
		$objPHPExcel->getActiveSheet()->getStyle('A1:IV1')->getFont()->setBold(true);
		//$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->getColor()->setRGB('FFFFFF');
		$objPHPExcel->getActiveSheet()->setTitle('Master_Data');
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(25);
		$objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		$objPHPExcel->getProperties()->setTitle("export")->setDescription("none"); 
		$objPHPExcel->getDefaultStyle()->getFont()->setName('arial')->setSize(8);
		$objPHPExcel->getDefaultStyle()->getFont()->getColor()->setRGB('000000'); 
		$objPHPExcel->getDefaultStyle()->getFont()->setBold(false);
		$objPHPExcel->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	/*$objPHPExcel->getActiveSheet()->getStyle('Y1:Y200')
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);*/
//echo $objPHPExcel->getActiveSheet()->stringFromColumnIndex(24);
//$colString = PHPExcel_Cell::stringFromColumnIndex(24);

        $col = 0;
	
		//print_r($result);
	foreach ($data[0] as $key => $val) {
         if ( $col >= $start  and  $col <=$end){
		 		 $fields []=$key ;
          	 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $key);
			 }
            $col++;
        }



		// Fetching the table data
   $row = 2;

  

    	  foreach($data as $datarow)
    	 {
           $col = 0;
           foreach ($fields as $field)
         {
		
			 if ( $col >= $start  and  $col <=$end){
			if (in_array($col,$sp_column )) {
	
					$colString = PHPExcel_Cell::stringFromColumnIndex($col);
							$objPHPExcel->getActiveSheet()->getStyle($colString.$row)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);				
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $datarow[$field], PHPExcel_Style_NumberFormat::FORMAT_GENERAL);
		
				}
				else{
					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $datarow[$field]);
					}
			
		
				
			  
			 }
              $col++;
           }
 
            $row++;
      }
 
      $objPHPExcel->setActiveSheetIndex(0);
 	 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
 
        // Sending headers to force the user to download the file
       header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="'.$filename.'-'.date('dmyhms').'.xls"');
      header('Cache-Control: max-age=0');
 
      $objWriter->save('php://output');

	
	}


}
