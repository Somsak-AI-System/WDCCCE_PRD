<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Memcached_library
{
  
  private $config;
  private $local_cache = array();
  private $m;
  private $client_type;
  private $ci;
  protected $errors = array();
  
  
  public function __construct()
  {
    $this->ci =& get_instance();
    
    // Lets try to load Memcache or Memcached Class
    $this->client_type = class_exists('Memcache') ? "Memcache" : (class_exists('Memcached') ? "Memcached" : FALSE);
    
    if($this->client_type) 
    {
      $this->ci->load->config('memcached');
      $this->config = $this->ci->config->item('memcached');
      
      // Which one should be loaded
      switch($this->client_type)
      {
        case 'Memcached':
          $this->m = new Memcached();
          break;
        case 'Memcache':
          $this->m = new Memcache();
          // Set Automatic Compression Settings
          if ($this->config['config']['auto_compress_tresh'])
          {
            $this->setcompressthreshold($this->config['config']['auto_compress_tresh'], $this->config['config']['auto_compress_savings']);
          }
          break;
      }
      log_message('debug', "Memcached Library: $this->client_type Class Loaded");
      
      $this->auto_connect();  
    }
    else
    {
      log_message('error', "Memcached Library: Failed to load Memcached or Memcache Class");
    }
  }
  
  /*
  +-------------------------------------+
    Name: auto_connect
    Purpose: runs through all of the servers defined in
    the configuration and attempts to connect to each
    @param return : none
  +-------------------------------------+
  */
  private function auto_connect()
  {
    foreach($this->config['servers'] as $key=>$server)
    {
      if(!$this->add_server($server))
      {
        $this->errors[] = "Memcached Library: Could not connect to the server named $key";
        log_message('error', 'Memcached Library: Could not connect to the server named "'.$key.'"');
      }
      else
      {
        log_message('debug', 'Memcached Library: Successfully connected to the server named "'.$key.'"');
      }
    }
  }
  
  /*
  +-------------------------------------+
    Name: add_server
    Purpose: 
    @param return : TRUE or FALSE
  +-------------------------------------+
  */
  public function add_server($server)
  {
    extract($server);
    return $this->m->addServer($host, $port, $weight);
  }
  
  /*
  +-------------------------------------+
    Name: add
    Purpose: add an item to the memcache server(s)
    @param return : TRUE or FALSE
  +-------------------------------------+
  */
  public function add($key = NULL, $value = NULL, $expiration = NULL)
  {
    if(is_null($expiration))
    {
      $expiration = $this->config['config']['expiration'];
    }
    if(is_array($key))
    {
      foreach($key as $multi)
      {
        if(!isset($multi['expiration']) || $multi['expiration'] == '')
        {
          $multi['expiration'] = $this->config['config']['expiration'];
        }
        $this->add($this->key_name($multi['key']), $multi['value'], $multi['expiration']);
      }
    }
    else
    {
      //$this->local_cache[$this->key_name($key)] = $value;
      switch($this->client_type)
      {
        case 'Memcache':
          $add_status = $this->m->add($this->key_name($key), $value, $this->config['config']['compression'], $expiration);
          break;
          
        default:
        case 'Memcached':
          $add_status = $this->m->add($this->key_name($key), $value, $expiration);
          break;
      }
      
      return $add_status;
    }
  }
  
  /*
  +-------------------------------------+
    Name: set
    Purpose: similar to the add() method but uses set
    @param return : TRUE or FALSE
  +-------------------------------------+
  */
  public function set($key = NULL, $value = NULL, $expiration = NULL)
  {
    if(is_null($expiration))
    {
      $expiration = $this->config['config']['expiration'];
    }
    if(is_array($key))
    {
      foreach($key as $multi)
      {
        if(!isset($multi['expiration']) || $multi['expiration'] == '')
        {
          $multi['expiration'] = $this->config['config']['expiration'];
        }
        $this->set($this->key_name($multi['key']), $multi['value'], $multi['expiration']);
      }
    }
    else
    {
      //$this->local_cache[$this->key_name($key)] = $value;
      switch($this->client_type)
      {
        case 'Memcache':
          $add_status = $this->m->set($this->key_name($key), $value, $this->config['config']['compression'], $expiration);
          break;
          
        default:
        case 'Memcached':
          $add_status = $this->m->set($this->key_name($key), $value, $expiration);
          break;
      }
      
      return $add_status;
    }
  }

  /*
   +-------------------------------------+
  Name: set_with_tag
  Purpose: similar to the add() method but uses set
  @param return : TRUE or FALSE
  +-------------------------------------+
  */
  public function set_with_tag($key = NULL, $value = NULL, $tag = NULL)
  {
    $expiration = $this->config['config']['expiration'];
    if(is_null($tag))
    {
      $this->set($key, $value);
    }
    //$this->local_cache[$this->key_name($key)] = $value;
    switch($this->client_type)
    {
      case 'Memcache':
        $add_status = $this->m->set($this->key_name($key), $value, $this->config['config']['compression'], $expiration);
        break;
      default:
      case 'Memcached':
        $add_status = $this->m->set($this->key_name($key), $value, $expiration);
        
        break;
    }
    if ($tag) {
      	$tag_value_array = $this->get_tag($tag);
//       echo '<pre>3.'; var_dump($tag_value_array); echo '</pre>';
      	$tag_value_array[$key] = $this->key_name($key);
		$this->set_tag($tag,$tag_value_array);
    }
    
    return $add_status;
  }
  
  /*
  +-------------------------------------+
    Name: get
    Purpose: gets the data for a single key or an array of keys
    @param return : array of data or multi-dimensional array of data
  +-------------------------------------+
  */
  public function get($key = NULL)
  {
    if($this->m)
    {
      /*
      if(isset($this->local_cache[$this->key_name($key)]))
      {
        return $this->local_cache[$this->key_name($key)];
      }
      */
      if(is_null($key))
      {
        $this->errors[] = 'The key value cannot be NULL';
        return FALSE;
      }
      
      if(is_array($key))
      {
        foreach($key as $n=>$k)
        {
          $key[$n] = $this->key_name($k);
        }
        return $this->m->getMulti($key);
      }
      else
      {
        return $this->m->get($this->key_name($key));
      }
    }
    return FALSE;    
  }

  /*
   +-------------------------------------+
  Name: get_by_tag
  Purpose: gets the data for a single key or an array of keys
  @param return : array of data or multi-dimensional array of data
  +-------------------------------------+
  */
  public function get_by_tag($tag=null)
  {
    if($this->m)
    {
	    if ($tag) {
	      $tag_value_array = $this->get_tag($tag);
	      $data =array();
	       if (is_array($tag_value_array) && count($tag_value_array) > 0) { 
			     foreach($tag_value_array as $key=> $values) 
			     {  
			      	$data[$key] = $this->get($key);
			      }
			      return $data;
	       }     
	    }
    }
    return FALSE;
  }
  
  /*
  +-------------------------------------+
    Name: delete
    Purpose: deletes a single or multiple data elements from the memached servers
    @param return : none
  +-------------------------------------+
  */
  public function delete($key, $expiration = NULL)
  {
    if(is_null($key))
    {
      $this->errors[] = 'The key value cannot be NULL';
      return FALSE;
    }
    
    if(is_null($expiration))
    {
      $expiration = $this->config['config']['delete_expiration'];
    }
    
    if(is_array($key))
    {
      foreach($key as $multi)
      {
        $this->delete($multi, $expiration);
      }
    }
    else
    {
      //unset($this->local_cache[$this->key_name($key)]);
      return $this->m->delete($this->key_name($key), $expiration);
    }
  }

  /*
   +-------------------------------------+
  Name: delete_with_tag
  Purpose: deletes a single or multiple data elements from the memached servers
  @param return : none
  +-------------------------------------+
  */
  public function delete_with_tag($key, $tag = NULL)
  {
  	$delete_status = true;//false
    if(is_null($key))
    {
      $this->errors[] = 'The key value cannot be NULL';
      return FALSE;
    }
  
    if(is_null($tag))
    {
      $this->delete($key);
    }
  
   	 	//unset($this->local_cache[$this->key_name($key)]);
   	 	$this->m->delete($this->key_name($key));
    	//$delete_status = $this->m->delete($this->key_name($key));
    if ($tag && $delete_status) {
      $tag_value_array = $this->get_tag($tag);
//       echo '<pre>d3.'; var_dump($tag_value_array); echo '</pre>';
      unset($tag_value_array[$key]);
//       echo '<pre>d4.'; var_dump($tag_value_array); echo '</pre>';
	 $this->set_tag($tag,$tag_value_array);
    }

    return $delete_status;
  }
  
/*
   +-------------------------------------+
  Name: delete_by_tag
   Purpose: deletes a muti data by tag from the memached servers
  @param return : none
  +-------------------------------------+
  */
  public function delete_by_tag($tag=null)
  {
	  if(is_null($tag))
	    {
	      $this->errors[] = 'The Tag value cannot be NULL';
	      return FALSE;
	    }

	      $tag_value_array = $this->get_tag($tag);
	      $data =array();
	      $delete_status = array();
	      
	     if (is_array($tag_value_array) && count($tag_value_array) > 0) {
		     foreach($tag_value_array as $key=> $values) 
		     {  
				  $delete_status[] = $this->delete_with_tag($key,$tag);	
		      }
	     }

	     if(!in_array(false,$delete_status))
	     {
	     	$this->delete($tag);
	     }
	      	return $delete_status;
  }
  /*
  +-------------------------------------+
    Name: replace
    Purpose: replaces the value of a key that already exists
    @param return : none
  +-------------------------------------+
  */
  public function replace($key = NULL, $value = NULL, $expiration = NULL)
  {
    if(is_null($expiration))
    {
      $expiration = $this->config['config']['expiration'];
    }
    if(is_array($key))
    {
      foreach($key as $multi)
      {
        if(!isset($multi['expiration']) || $multi['expiration'] == '')
        {
          $multi['expiration'] = $this->config['config']['expiration'];
        }
        $this->replace($multi['key'], $multi['value'], $multi['expiration']);
      }
    }
    else
    {
      //$this->local_cache[$this->key_name($key)] = $value;
      
      switch($this->client_type)
      {
        case 'Memcache':
          $replace_status = $this->m->replace($this->key_name($key), $value, $this->config['config']['compression'], $expiration);
          break;
        
        default:
        case 'Memcached':
          $replace_status = $this->m->replace($this->key_name($key), $value, $expiration);
          break;
      }
      
      return $replace_status;
    }
  }
  
  /*
  +-------------------------------------+
    Name: flush
    Purpose: flushes all items from cache
    @param return : none
  +-------------------------------------+
  */
  public function flush()
  {
    return $this->m->flush();
  }
  
  /*
  +-------------------------------------+
    Name: getversion
    Purpose: Get Server Vesion Number
    @param Returns a string of server version number or FALSE on failure. 
  +-------------------------------------+
  */
  public function getversion()
  {
    return $this->m->getVersion();
  }
  
  /*
  +-------------------------------------+
    Name: getstats
    Purpose: Get Server Stats
    Possible: "reset, malloc, maps, cachedump, slabs, items, sizes"
    @param returns an associative array with server's statistics. Array keys correspond to stats parameters and values to parameter's values.
  +-------------------------------------+
  */
  public function getstats($type="items")
  {
    switch($this->client_type)
    {
      case 'Memcache':
        $stats = $this->m->getStats($type);
        break;
      
      default:
      case 'Memcached':
        $stats = $this->m->getStats();
        break;
    }
    return $stats;
  }
  
  /** 
   * Function to get all memcache keys 
   */ 
  public function getMemcacheKeys() {
  	//Check memcache in prefix;
	$_prefix =  $this->config['config']['prefix'];
	$pattern = "/^".$_prefix.".*/";
    $list = array(); 
    $allSlabs = $this->m->getExtendedStats('slabs'); 
    $items = $this->m->getExtendedStats('items'); 
    $memcache =array();
   
    foreach($allSlabs as $server => $slabs) { 
      foreach($slabs AS $slabId => $slabMeta) { 
      if (!is_int($slabId)) {
            continue;
        }
        $cdump = $this->m->getExtendedStats('cachedump',(int)$slabId); 
        foreach($cdump AS $keys => $arrVal) {
          if (is_array($arrVal) && count($arrVal) > 0) {
            foreach($arrVal AS $k => $v) {
            	//Check memcache in prefix;
            	if(preg_match($pattern,$k) )
            	{
            		$memcache[$k] = $this->m->get($k);
            	}
            	
            	/*echo '<pre>'; var_dump($k); echo '</pre>';
              if ( $k == "tvkmain/index" ) {
                //echo '<pre>'; var_dump($this->m->get($k)); echo '</pre>';
              }*/
              //$this->m->delete($k);
              //echo '<pre>'; var_dump($this->m->get($k)); echo '</pre>';
              //echo '<pre>'; var_dump("------------------------------"); echo '</pre>';
            }
          }
        } 
      } 
    }    
    return $memcache;
  }//EO getMemcacheKeys() 
  
  /*
  +-------------------------------------+
    Name: setcompresstreshold
    Purpose: Set When Automatic compression should kick-in
    @param return TRUE/FALSE
  +-------------------------------------+
  */
  public function setcompressthreshold($tresh, $savings=0.2)
  {
    switch($this->client_type)
    {
      case 'Memcache':
        $setcompressthreshold_status = $this->m->setCompressThreshold($tresh, $savings=0.2);
        break;
        
      default:
        $setcompressthreshold_status = TRUE;
        break;
    }
    return $setcompressthreshold_status;
  }
  
  /*
  +-------------------------------------+
    Name: key_name
    Purpose: standardizes the key names for memcache instances
    @param return : md5 key name
  +-------------------------------------+
  */
  private function key_name($key)
  {
    //return md5(strtolower($this->config['config']['prefix'].$key));
    return strtolower($this->config['config']['prefix'].$key);
  }
  
    /*
  +-------------------------------------+
    Name: get_tag
    Purpose: get data value in tag from memcache server
    @param return : unserialize tag values
  +-------------------------------------+
  */
  public function get_tag($tag)
  {
  	 if ($tag) {
      // echo '<pre>d1.'; var_dump($tag); echo '</pre>';
      $tag_value = $this->get($tag);
       //echo '<pre>d2.'; var_dump($tag_value); echo '</pre>';
      return unserialize($tag_value);
  	 }
  }
  
   /*
  +-------------------------------------+
    Name: set_tag
    Purpose: set data value in tag from memcache server
    @param return : serialize tag values
  +-------------------------------------+
  */
  private function set_tag($tag,$tag_value_array)
  {
  	 if ($tag_value_array) {
      array_unique($tag_value_array);
//       echo '<pre>d5.'; var_dump($tag_value_array); echo '</pre>';
      $tag_value = serialize($tag_value_array);
//       echo '<pre>d6.'; var_dump($tag_value); echo '</pre>';
      //$this->local_cache[$tag] = $tag_value;
      $this->set($tag, $tag_value, 0);
  	 }
  	 else 
  	 {
  	 	$this->delete($tag);
  	 }
  }
}
/* End of file memcached_library.php */
/* Location: ./application/libraries/memcached_library.php */