<?php

if(! defined('BASEPATH')) exit('No direct script access allowed');

class Common
{
  public $_tab = array();

  function __construct()
  {
    $this->CI = & get_instance();
    $this->CI->load->library('logging');
    $this->_filename = "info";
  }

  public function set_log_begin($url=null,$param=null)
  {
  	$config = $this->CI->config->item('log');
  	$config_log = $config['config'];
  	$logger = $this->CI->logging->get_logger('info');
  	if($url=="") return false;
  	$logger->info("Begin:: ".$url);
  }
  public function set_log($url=null,$param=null,$response=null)
  {
  	if($url=="") return false;
  	$method = $this->check_method($url);

	$log_field = $this->check_field($method);
	$log_get = $this->check_get($method);
	$log_save = $this->check_save($method);
	$log_delete = $this->check_delete($method);
	$log_allocate = $this->check_allocate($method);
	$log_release = $this->check_release($method);
	$log_check = $this->check_check($method);
	$log_test = $this->check_test($method);
	$log_create = $this->check_create($method);
	$log_so = $this->check_so($method);
	$log_cancel = $this->check_cancel($method);
	$log_update = $this->check_update($method);
	$log_re = $this->check_re($method);
	$log_import = $this->check_import($method);
	$log_wip = $this->check_wip($method);
	$log_export = $this->check_export($method);



	if($log_field) $this->write_log($url,$param,$response,"field");
	if($log_get) $this->write_log($url,$param,$response,"get");
	if($log_save) $this->write_log($url,$param,$response,"save");
	if($log_delete) $this->write_log($url,$param,$response,"delete");
	if($log_allocate) $this->write_log($url,$param,$response,"allocate");
	if($log_release) $this->write_log($url,$param,$response,"release");
	if($log_check) $this->write_log($url,$param,$response,"check");
	if($log_test) $this->write_log($url,$param,$response,"test");
	if($log_create) $this->write_log($url,$param,$response,"create");
	if($log_so) $this->write_log($url,$param,$response,"so");
	if($log_cancel) $this->write_log($url,$param,$response,"cancel");
	if($log_update) $this->write_log($url,$param,$response,"update");
	if($log_re) $this->write_log($url,$param,$response,"re");
	if($log_import) $this->write_log($url,$param,$response,"import");
	if($log_wip) $this->write_log($url,$param,$response,"wip");
	if($log_export) $this->write_log($url,$param,$response,"export");

  }

  public function set_log_stored($stored=null,$param=null,$response=null,$storedname=null)
  {
  	if($stored=="") return false;
  	$module = "stored";
  	$this->write_log($stored,$param,$response,$module,$storedname);
  }
  public function set_log_error($url=null,$param=null,$response=null)
  {
  	$logger = $this->CI->logging->get_logger('error');
  	//echo $response;
  	$logger->error("URL:: ".$url);
  	$logger->error("Parameter ::".json_encode($param));
  	$logger->error("Response:: ".$response);
  }
  public function set_log_error_criticals($url=null,$param=null,$response=null)
  {
  	$logger = $this->CI->logging->get_logger('email_criticals');
  	//echo $response;
  	$logger->critical("URL:: ".$url);
  	$logger->critical("Parameter ::".json_encode($param));
  	$logger->critical("Response:: ".$response);
  }

  private function check_field($method=null)
  {
  		if ($method=="") return false;
	  	if($method=="GetBlock" || $method=="GetTableField" )
	  	{
	  		return true;
	  	}
	  	return false;
  }

  private function check_get($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith(strtolower($method), "get") && !($method=="GetBlock" || $method=="GetTableField" ) )
	{
  		return true;
  	}
  	return false;
  }

  private function check_save($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith($method, "Save"))
  	{
  		return true;
  	}
  	return false;
  }

  private function check_delete($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith($method, "Delete"))
  	{
  		return true;
  	}
  	return false;
  }

  private function check_allocate($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith($method, "Allocate"))
  	{
  		return true;
  	}
  	return false;
  }

  private function check_release($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith($method, "Release"))
  	{
  		return true;
  	}
  	return false;
  }

  private function check_test($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith(strtolower($method), "test"))
  	{
  		return true;
  	}
  	return false;
  }
  private function check_check($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith(strtolower($method), "check"))
  	{
  		return true;
  	}
  	return false;
  }

  private function check_create($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith(strtolower($method), "create"))
  	{
  		return true;
  	}
	if(startsWith(strtolower($method), "crmsavedata"))
  	{
  		return true;
  	}
  	return false;
  }
  private function check_so($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith(strtolower($method), "so"))
  	{
  		return true;
  	}
  	return false;
  }

  private function check_cancel($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith(strtolower($method), "cancel"))
  	{
  		return true;
  	}
  	return false;
  }

  private function check_update($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith(strtolower($method), "update"))
  	{
  		return true;
  	}
  	return false;
  }

  private function check_re($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith(strtolower($method), "re"))
  	{
  		return true;
  	}
  	return false;
  }
  private function check_import($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith(strtolower($method), "import"))
  	{
  		return true;
  	}
  	return false;
  }
  private function check_wip($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith(strtolower($method), "wip"))
  	{
  		return true;
  	}
  	return false;
  }
  private function check_export($method=null)
  {
  	if ($method=="") return false;
  	if(startsWith(strtolower($method), "export"))
  	{
  		return true;
  	}
  	return false;
  }
  private function write_log($url=null,$param=null,$response=null,$module=null,$stored_name=null)
  {
  	if ($module=="") return false;
  	$config = $this->CI->config->item('log');
  	$config_log = $config['config'];
  	$logger = $this->CI->logging->get_logger($this->_filename);

  	if($config_log[$module]["param"] === true){
  		$logger->info("URL:: ".$url);
  		$logger->info("Parameter ::".json_encode($param));
  	}

  	if($config_log[$module]["response"] === true){
  		if($config_log[$module]["param"] === false){
  			$logger->info("URL:: ".$url);
  		}
  		if($module=="stored"){
  			if($stored_name==""){
  				$logger->info("Response:: ".json_encode($response));
  			}else{
  				$logger->info("Response:: Return Data");
  			}
  		}else{
  			$logger->info("Response:: ".$response);
  		}

  	}
  }

  public function check_method($url=null)
  {
  		if($url=="") return false;
  		$a_url = explode( "/", $url);
  		if (!empty($a_url)) {
  			return end($a_url);
  		}
  		else{
  			return false;
  		}

  }
 public function do_upload($dir="uploads",$allowed_type="gif|jpg|png|jpeg|pdf|doc|xml",$max_size="1000",$max_height=null,$max_width=null)
 {
 	$this->config =  array(
 			'upload_path'     => dirname($_SERVER["SCRIPT_FILENAME"])."/".$dir."/",
 			'upload_url'      => site_url($dir."/"),
 			'allowed_types'   => $allowed_type,
 			/*'overwrite'       => TRUE,*/
 			'max_size'        => $max_size,
 			'max_height'      => $max_height,
 			'max_width'       => $max_width
 	);
 	$this->remove_dir($this->config["upload_path"], false);

 	$this->CI->load->library('upload', $this->config);
 	if($this->CI->upload->do_upload())
 	{
 		$data["status"] = TRUE;
 		$data["message"] = "File Uploaded Successfully";
 		$data["uploaded_file"] = $this->CI->upload->data();
 		/*$this->CI->data['status']->message = "File Uploaded Successfully";
 		$this->CI->data['status']->success = TRUE;
 		$this->CI->data["uploaded_file"] = $this->CI->upload->data();*/
 	}
 	else
 	{
 		$data["status"] = FALSE;
 		$data["message"] = $this->CI->upload->display_errors();
 		/*$this->CI->data['status']->message = $this->CI->upload->display_errors();
 		$this->CI->data['status']->success = FALSE;*/
 	}
 	return $data;
 }
 function remove_dir($dir, $DeleteMe) {
 	if(!$dh = @opendir($dir)) return;
 	while (false !== ($obj = readdir($dh))) {
 		if($obj=='.' || $obj=='..') continue;
 		if (!@unlink($dir.'/'.$obj)) $this->remove_dir($dir.'/'.$obj, true);
 	}

 	closedir($dh);
 	if ($DeleteMe){
 		@rmdir($dir);
 	}

 }
}
