how to install
1. create folder ci_api in localhost 
   - rename ci_api  edit file .htaccess
       => RewriteBase /ci_api/
2. create database  crm_test_api
  - rename database edit file application/config/database.php
      => 	$db['default']['hostname'] = 'localhost';
	$db['default']['username'] = 'root';
	$db['default']['password'] = '';
	$db['default']['database'] = 'crm_test_api';
3. data test api_key
    table keys column key


howto enable apikey
config/rest.php
1. $config['rest_enable_keys'] = False; => $config['rest_enable_keys'] = TRUE;
2. create table
   CREATE TABLE `keys` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `key` varchar(40) NOT NULL,
	  `level` int(2) NOT NULL,
	  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
	  `is_private_key` tinyint(1)  NOT NULL DEFAULT '0',
	  `ip_addresses` TEXT NULL DEFAULT NULL,
	  `date_created` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;

3. insert key in column key
$config['rest_key_column'] = 'key';
4. $config['rest_key_name']= "xxx"; => "AI-API-KEY";

ex. http://localhost/ci_api/example/users?AI-API-KEY=1234&format=xml
http://localhost/ci_api/product/products?AI-API-KEY=1234&no=PRO61&offset=0&limit=10&format=json
http://localhost/ci_api/product/products?AI-API-KEY=1234&offset=0&limit=10&format=json&orderby=productid,desc
http://localhost/ci_api/product/products?AI-API-KEY=1234&offset=0&limit=10&format=json&orderby=qtyinstock,desc|productid,asc