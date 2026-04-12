<?php
//error_reporting(E_ALL  & ~E_NOTICE  & ~E_WARNING );
//ini_set('display_errors', 1);
class myExcel{
	public  $_dbconfig;
	public function __construct(){

	}
	

	public function gen_excel($a_data=array(),$title=null)
	{		
		$file=$title.".xls";
		$data = "";
		if(empty($a_data)) return null;
		$data = "<table>";
		$data .= "<tr>";
		foreach ($a_data[0] as $key  => $value)
		{
			$data .= "<td>" . $key ."</td>";
		};
		$data .= "</tr>";
		foreach (array_values($a_data) as $lineNumber => $row) {
			$data .= "<tr>";
			foreach (array_values($row) as $colNumber => $v) {
				if(is_numeric($v)) {
					$data .= '<td style =" mso-number-format: \'\@"\'>' .$v .'</td>';
				}else{
					$data .= "<td>" . (string)$v ."</td>";
				}
			}
			$data .= "</tr>";
		}
		$data .= "</table>";
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$file");
		echo $data;
		return $data;
	}
}

?>
