<?php
//error_reporting(E_ALL  & ~E_NOTICE  & ~E_WARNING );
//ini_set('display_errors', 1);
class libCalendar{
	public  $_dbconfig;
	public function __construct(){


	}

	public function get_image($calendard=null)
	{

		$this->generate = new generate($this->_dbconfig  ,"DB");
		if($calendard =="") return null;
	/*	$sql = "select  activityid,img_type,img_folder,img, position
						 from  aicrm_job_img
						 where  1
						 AND jobid = '".$jobid."' ";
		$sql .= " order by  jobid ";
		//echo $sql;
		$data = $this->generate->process($sql,"all");*/
		if(!empty($data)){
			return $data;
		}else{
			return null;
		}
	}
}
?>
