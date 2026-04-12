<?php
    include_once("mydb_ext.inc");

	class genarate extends mydb_ext{
		var $total_columns;
		var $total_rows;
		var $sql;
		var $data;
		var $page;
		var $startData;
		var $endData;
		var $datas;
		var $sorts;
		var $key_sort;



		function genarate($dbconfig, $dbname){
		//echo $dbconfig[$dbname]["host"];
			$this->connect( $dbconfig[$dbname]["host"], $dbconfig[$dbname]["username"], $dbconfig[$dbname]["password"])or die("Unable to connect to database");		
			$this->select_db($dbconfig[$dbname]["dbname"]);
		}
		function close(){	
			$this->closeconnect();
		}

	  function unsetVariables(){
		unset($this->total_columns);
		unset($this->total_rows);
		unset($this->sql);
		unset($this->data);
		unset($this->page);
		unset($this->startData);
		unset($this->endData);
		unset($this->datas);
		unset($this->data_sorted);
	  }
	  
	 function unsetVariables2(){
		//unset($this->total_columns);
		//unset($this->total_rows);
		unset($this->sql);
		unset($this->data);
		unset($this->page);
		//unset($this->startData);
		//unset($this->endData);
		unset($this->datas);
		unset($this->data_sorted);
	  }
		function process($sql,$pages){
			$this->query("SET character_set_results=utf8");
			$this->query("SET character_set_client=utf8");
			$this->query("SET character_set_connection=utf8");
			$this->query("SET NAMES UTF8");
			$this->unsetVariables2();
			$this->sql = $sql;
			//echo $sql;
			$this->page = $pages;
			//$this->preparedata();
			//$this->checkPage();
			//return $this->generateData();
			//echo $this->page;
			return $this->preparedata();
		}	
	
		function process_row($sql,$pages){
			$this->unsetVariables();
			//echo $sql;
			$this->query($sql);
			$this->total_columns = $this->num_fields(); 
			$this->total_rows = $this->num_rows(); 
			$this->page = $pages;
			//$this->checkPage();
			
			return $this->num_rows();
		}	
		function order_sort($sorts,$keysort){
			$this->sorts = $sorts;
			$this->key_sort = $keysort;
		}

		function mu_sort($array, $key_sort, $asc_desc) { // start function
			if (func_num_args()<2 || func_num_args()>3) die("Wrong number of parameters for the call of mu_sort()");
			$array = func_get_arg(0);
			$key_sort = func_get_arg(1);
			if (func_num_args()==3)
				$asc_desc = func_get_arg(2);
			else
				$asc_desc = "asc";

			$key_sorta = explode(",", $key_sort); 
			
			$keys = array_keys($array[0]);

			// sets the $key_sort vars to the first
			for($m=0; $m < count($key_sorta); $m++){ 
				$nkeys[$m] = trim($key_sorta[$m]); 
			}

			$n += count($key_sorta);  

			for($i=0; $i < count($keys); $i++){ 
				if(!in_array($keys[$i], $key_sorta)){
					$nkeys[$n] = $keys[$i];
					$n += "1"; 
				} 
			}

			for($u=0;$u<count($array); $u++){ 
				$arr = $array[$u];
				for($s=0; $s<count($nkeys); $s++){
					$k = $nkeys[$s];
					if($k == "") $k =0;
					$output[$u][$k] = $array[$u][$k]; 
				}
			} 

		switch($asc_desc) {
			case "desc":
				rsort($output); break;
			default:
				sort($output);
		}
		//print_r($output);
		return $output;
	} 

		function preparedata(){//generate all data
			$this->results=	$this->query($this->sql);			
			$this->total_columns = $this->num_fields(); 
				$numFields = mysql_num_fields($this->results);
				$index = 0;
				$j = 0;
				while ($j < $numFields) {
					$column = mysql_fetch_field($this->results,$j);
					if (!empty($column->table)) {
						$this->map[$index++] = array($column->table, $column->name);
					} else {
						$this->map[$index++] = array(0, $column->name);
					}
					$j++;
				}
			$k=0;	
			$resultRow = array();						
			while($row = mysql_fetch_row($this->results)){			
					$i = 0;				
					foreach ($row as $index => $field) {
			
						list($table, $column) = $this->map[$index];
						$resultRow[$k][$table][$column] = $row[$index];
						$i++;
					}
			
							$k++;		
				}					
					return $resultRow;
				}
		function checkPage(){
			if(($this->page == "")||($this->page<0)){
				$this->page = 0;
				$this->startData = $this->max_rows*$this->page;
				if($this->total_rows<$this->max_rows) $this->endData = $this->total_rows;
				else $this->endData = ($this->page+1)*$this->max_rows;
			}elseif(($this->page == "all")){
				$this->startData = 0;
				$this->endData = $this->total_rows;
			}else{
				$this->startData = $this->max_rows*$this->page;
				$this->endData = ($this->page+1)*$this->max_rows;
			}
		}
		function generateData(){//generate data limit per page
			for($i=$this->startData;$i<$this->endData;$i++){
				for($j=0;$j<$this->total_columns;$j++){
					$this->datas[$i][$j] = $this->data[$i][$j];
				}
			}
			return $this->datas;
		}

		function isnextpage(){
			//echo $this->total_rows;
		//echo $this->page*$this->max_rows;
		//echo $this->page;
			if((($this->page)*$this->max_rows) < $this->total_rows){
				return true;
			}else{
				return false;
			}
		}

		function ispreviouspage(){
			//echo $this->page; 
			if(($this->page)>1){
				return true;
			}else{
				return false;
			}
		}
    }
?>