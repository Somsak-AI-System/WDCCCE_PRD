<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
define('PASSCODE', 'aisystem2014');
/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

//defined('SERVICE_DATACONTROL')            OR define('SERVICE_DATACONTROL', 'http://192.168.0.51:10030/DataControl/');
defined('SERVICE_DATACONTROL')                  OR define('SERVICE_DATACONTROL', 'http://192.168.0.110:10030/DataControl/');
defined('FILE_MENU_PATH')                       OR define('FILE_MENU_PATH', 'user_privileges/');
defined('FILE_MESSAGE_PATH')                    OR define('FILE_MESSAGE_PATH', 'user_privileges/message.json');
defined('FILE_ASSETS')                          OR define('FILE_ASSETS', 'assets/js/');
defined('FILE_PREFIX_MENU')                     OR define('FILE_PREFIX_MENU', 'menu_');
defined('FILE_PREFIX_PERMISSION')               OR define('FILE_PREFIX_PERMISSION', 'permission_');
defined('FILE_TYPE_JSON')                       OR define('FILE_TYPE_JSON', '.json');
/* End of file constants.php */
/* Location: ./application/config/constants.php */

//defined('USERID')                               OR define('USERID', 2);
//defined('USERNAME')                             OR define('USERNAME', 'demouser');
defined('PAGE_SIZE')                            OR define('PAGE_SIZE', 30);
//defined('COMPUTER_NAME')                        OR define('COMPUTER_NAME', gethostbyaddr($_SERVER['REMOTE_ADDR']));
defined('COMPUTER_NAME')                        OR define('COMPUTER_NAME', $_SERVER['REMOTE_ADDR']);
defined('SERVICE_URL')                          OR define('SERVICE_URL', 'http://127.0.0.1:10130/');
defined('SERVICE_PATH')                         OR define('SERVICE_PATH', 'DataControl/');
defined('SOCKET_PATH')                          OR define('SOCKET_PATH', 'http://127.0.0.1:8800');

defined('UPLOAD_DIR')                           OR define('UPLOAD_DIR', 'Y:');
defined('UPLOAD_PATH')                          OR define('UPLOAD_PATH', '/uploads/' );
defined('CRM_VERSION')                          OR define('CRM_VERSION', '1.1.2' );