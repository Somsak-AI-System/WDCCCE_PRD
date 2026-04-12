<?
class encrypt extends MX_Controller {
	 private $description, $title, $keyword, $screen;
 public function __construct()
  {
 
   parent::__construct();
    $meta = $this->config->item('meta');
    $this->_lang = $this->config->item('lang');
    $this->title = $meta["default"]["title"];
    $this->keyword = $meta["default"]["keyword"];
    $this->description = $meta["default"]["description"];
    $this->load->library('curl');
    $this->lang->load('ai',$this->_lang);    
    $this->load->library('logging');
 $this->load->helper('form');
  }

public function simple_encrypt($text)
    {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

 public   function simple_decrypt($text)
    {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }


} ?>