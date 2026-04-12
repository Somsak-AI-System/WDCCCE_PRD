<?php
$config['meta']['default'] = array(
  'title' => 'moai',
  'keyword' => ' ',
  'description' => ''
);

$config['lang'] = 'english';
$pattern = '/^crm.sena-it.com/';
$pattern2 = '/senawecare.com/';
if( preg_match($pattern, $_SERVER['HTTP_HOST']) ) // a-z และ/หรือ 0-9 ตั้งแต่ 1 ตัวขึ้นไปเท่านั้น
{
	$config["url"]["url_main"] = "http://localhost:8090/moai/WB_Service_AI/";
}else if( preg_match($pattern2, $_SERVER['HTTP_HOST']) ) // a-z และ/หรือ 0-9 ตั้งแต่ 1 ตัวขึ้นไปเท่านั้น
{
	$config["url"]["url_main"] = "http://localhost:8090/moai/WB_Service_AI/";
}else{
	$config["url"]["url_main"] ="http://".$_SERVER['HTTP_HOST']."/moai/WB_Service_AI/";
	//$config["url"]["url_main"] ="http://203.151.189.75/sena/WB_Service_AI/";
}


