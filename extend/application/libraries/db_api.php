<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class db_api
{
 	 public $database = array();

	public function __construct() {

    $this->CI = & get_instance();

    }
	   public function insert($table,$data) {
 		$this->CI->load->database();
		$ret_msg= true;
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

	$query = $this->CI->db->query($insert_sql."  ".$value_sql);
		if ($this->CI->db->trans_status() === FALSE)
				{
    			$this->CI->db->trans_rollback();
				$ret_msg= false;
				}else
				{
					$this->CI->db->trans_commit();
					$ret_msg= true;
				}

	$this->CI->db->close();
	return $ret_msg;

    }

	public function update($table,$data,$condition) {
  		$this->CI->load->database();
		$ret_msg= true;
		$insert_sql =" update  ".$table."  set ";
		$i=0;
		 foreach($data as $key=>$value) {

							   if ($i==0)
							   { 	   $insert_sql  .= $key ."="."'".iconv('utf-8','tis620',$value)."'"  ;}
							   else
							   {   	   $insert_sql  .=",". $key ."="."'".iconv('utf-8','tis620',$value)."'";   }


			$i++;
		}

		$query =$this->CI->db->query($insert_sql." ".  $condition );
		if ( $this->CI->db->trans_status() === FALSE)
				{
    				$this->CI->db->trans_rollback();
					$ret_msg= false;
						$ret_msg="Cannot update, please try again later.";
				}else
				{
					$this->CI->db->trans_commit();

					$ret_msg= true;
				}
		  $this->CI->db->close();
			return $ret_msg;


    }
	public function getresult($sql) {


  		$this->CI->load->database();
		$data= array();
			$fields= array();
			$i=1;

		$query =$this->CI->db->query($sql);
		$a_data =  $query->result_array() ;

/*		foreach ($a_data as $row )
		{
			  for ($i = 1; $i <= $query->num_fields(); $i++)
				  {
					//$fields= odbc_field_name($query->result_id, $i);
					//$row[$fields]=iconv('tis620','UTF-8//IGNORE',$row[$fields]);
			//$row[$fields]=$row[$fields];

				  }

			$data[]=$row;
			}*/
		$this->CI->db->close();
		return $a_data;

//	return $data;

	}
	public function getresult_filed($sql) {


		$this->CI->load->database();
		$data= array();
		$fields= array();
		$i=1;

		$query =$this->CI->db->query($sql);
		$a_data =  $query->result_array() ;

		foreach ($a_data as $row )
		{
			$a_row = array();
			for ($i = 1; $i <= $query->num_fields(); $i++)
			{
			$fields= odbc_field_name($query->result_id, $i);
			$fields2 = iconv('tis620','UTF-8//IGNORE',$fields);
			$a_row[$fields2]=iconv('tis620','UTF-8//IGNORE',$row[$fields]);
			//$row[$fields]=$row[$fields];
			}

			$data[]=$a_row;

		}
		 $this->CI->db->close();
		return $data;

	}
public function getresult_noconvert($sql) {


  		$this->CI->load->database();
		$data= array();
			$fields= array();
			$i=1;

		$query =$this->CI->db->query($sql);
		$a_data =  $query->result_array() ;

      $this->CI->db->close();
	return $a_data;

	}
public function query($sql) {
	$this->CI->load->database();
	$query =$this->CI->db->query($sql );
		if ( $this->CI->db->trans_status() === FALSE)
		{
					$this->CI->db->trans_rollback();
				$ret_msg= false;
				//	$ret_msg=$this->CI->db->_error_message();
				}else
				{
					$this->CI->db->trans_commit();
					$ret_msg= true;
				}
 		 $this->CI->db->close();
		return $ret_msg;

	}

public function getfield($sql,$field) {
	$this->CI->load->database();
	$query =$this->CI->db->query($sql );
	$ret="";
		foreach ($query->result_array() as $row )
		{
		$ret=iconv('tis620','utf-8', $row[$field]);
		}
	return $ret;

	}

}