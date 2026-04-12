<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class db_updated
{
 	private $my_server;
	public function __construct() {

    }
	   public function insert($table,$data) {

		$insert_sql =" insert into  ".$table." (";
		$value_sql =	" values (";	
		$i=0;
		 foreach($data as $key=>$value) {

							   if ($i==0)
							   { 	   $insert_sql  .= $key; }
							   else
							   {    $insert_sql    .=",". $key; }

		
	
							   if ($i==0)
							   { 	   $value_sql  .=iconv('utf-8','tis620', "'".$value."'");
							    }
							   else
							   {    $value_sql .=",". iconv('utf-8','tis620', "N'".$value."'");
					
								}
			$i++;
		}
	$insert_sql.=")";
	$value_sql.=")";
		return $insert_sql." ".  $value_sql ;

    }
	
	public function update($table,$data,$condition) {

		$insert_sql =" update  ".$table."  set ";
	
		$i=0;
		 foreach($data as $key=>$value) {

							   if ($i==0)
							   { 	   $insert_sql  .= $key ."="."'".$value."'"  ;}
							   else
							   {   	   $insert_sql  .=",". $key ."="."'".$value."'";   }

		
	
			$i++;
		}

		return $insert_sql." ".  $condition ;

    }

	
	

  
}