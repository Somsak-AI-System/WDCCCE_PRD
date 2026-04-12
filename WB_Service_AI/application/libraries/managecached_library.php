<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Managecached_library
{
private $config;
public function __construct()
{
  $this->_ci =& get_instance();
  $this->_ci->load->config('memcached');
  $this->config = $this->_ci->config->item('memcached');
  $this->_ci->load->library('memcached_library');

}
public function get_memcache($data = array())
{
	  	$clearcache = $this->_ci->input->get("clearcache");
	  	$a_cache = array(
		     			"_ctag" =>$data["_ctag"],
		     			"_ckname" => $data["_ckname"]
		     		);
	    $ctag = isset($data['_ctag']) ? $data['_ctag'] : '';    // cache tag
		if ( $clearcache )
		 {
		  	 $ctag = isset($data['_ctag']) ? $data['_ctag'] : '';    // cache tag
		     @$this->_ci->memcached_library->delete_with_tag(json_encode($a_cache), $ctag);
		 }
	    $a_datacache = @$this->_ci->memcached_library->get(json_encode($a_cache));
	    unset($data);
	    if ($a_datacache)
	    {
	      return $a_datacache;
	    }
	    return false;
	    /*else
	    {
	        $this->_ci->memcached_library->set_with_tag(json_encode($a_cache), $data["data"], $ctag);
	    }*/


  }
  public function set_memcache($data = array(),$cache_time=null)
{
 if (!$this->config['config']['set_cached']) {
 	return false;
 }
  if ($cache_time==null) {
  	$cache_time = $this->config['config']['expiration'];
  }
	$a_cache = array(
		     			"_ctag" =>$data["_ctag"],
		     			"_ckname" => $data["_ckname"]
		     		);
	$this->_ci->memcached_library->set(json_encode($a_cache), $data["data"],$cache_time);
	//$this->_ci->memcached_library->set_with_tag(json_encode($a_cache), $data["data"], $ctag);
}

}
/* End of file memcached_library.php */
/* Location: ./application/libraries/memcached_library.php */
