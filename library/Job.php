<?php
//error_reporting(E_ALL  & ~E_NOTICE  & ~E_WARNING );
//ini_set('display_errors', 1);
class libJob{
	public  $_dbconfig;
	public function __construct(){


	}
	public function get_image($jobid=null)
	{

		$this->generate = new generate($this->_dbconfig  ,"DB");
		if($jobid =="") return null;
		$sql = "select  jobid,img_type,img_folder,img, position
						 from  aicrm_job_img
						 where  1
						 AND jobid = '".$jobid."' ";
		$sql .= " order by  jobid ";

		$data = $this->generate->process($sql,"all");
		if(!empty($data)){
			return $data;
		}else{
			return null;
		}
	}
}
?>
