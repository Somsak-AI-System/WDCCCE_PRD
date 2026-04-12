<?php defined('BASEPATH') OR exit('No direct script access allowed.');

// $ci =& get_instance();
// $ci->load->database();
// $sql = $ci->db->get_where('aicrm_systems', ['server_type'=>'email']);
// $arr = $sql->row_array();

// $mail_server = $arr['server'];
// $mail_server_port = $arr['server_port'];
// $mail_server_username = $arr['server_username'];
// $mail_server_password = $arr['server_password'];
// $smtp_auth = $arr['smtp_auth'];

// $config['useragent']           = "CodeIgniter";
// $config['protocol']            = "smtp";
// $config['smtp_host']           = $mail_server; //"mail.med-one.co.th";
// $config['smtp_port']           = $mail_server_port; //"587";
// $config['smtp_user']           = $mail_server_username; //'support_moai@med-one.co.th';
// $config['smtp_pass']           = $mail_server_password; //'P@ssw0rd';
// $config['charset'] = 'utf-8';
// $config['wordwrap'] = TRUE;
// $config['mailtype'] = 'html';


$config['useragent']        = 'PHPMailer';              // Mail engine switcher: 'CodeIgniter' or 'PHPMailer'
$config['protocol']         = 'smtp';                   // 'mail', 'sendmail', or 'smtp'
$config['mailpath']         = '/usr/sbin/sendmail';


$config['smtp_host']        = 'email-smtp.ap-southeast-1.amazonaws.com';
$config['smtp_auth']        = true;                     // Whether to use SMTP authentication, boolean TRUE/FALSE. If this option is omited or if it is NULL, then SMTP authentication is used when both $config['smtp_user'] and $config['smtp_pass'] are non-empty strings.
$config['smtp_user']        = 'AKIASI6T67C5XSVOUW5Z';
$config['smtp_pass']        = 'BIfG6K9HifoanNx5R01wNPnbPyD1dchsJ7gIaWYc1TWp';                       // Gmail disabled the so-called "Less Secured Applications", your Google password is not to be used directly, XOAUTH2 authentication will be used.


/*$config['smtp_host']        = 'smtp-relay.brevo.com';
$config['smtp_auth']        = true;                     // Whether to use SMTP authentication, boolean TRUE/FALSE. If this option is omited or if it is NULL, then SMTP authentication is used when both $config['smtp_user'] and $config['smtp_pass'] are non-empty strings.
$config['smtp_user']        = '886c84001@smtp-brevo.com';
$config['smtp_pass']        = 'Z0PkXV3LyASG4I72';                       // Gmail disabled the so-called "Less Secured Applications", your Google password is not to be used directly, XOAUTH2 authentication will be used.*/


$config['smtp_port']        = 587;
$config['smtp_timeout']     = 30;                       // (in seconds)
$config['smtp_crypto']      = 'tls';                    // '' or 'tls' or 'ssl'
$config['smtp_debug']       = 0;                        // PHPMailer's SMTP debug info level: 0 = off, 1 = commands, 2 = commands and data, 3 = as 2 plus connection status, 4 = low level data output.
$config['debug_output']     = '';                       // PHPMailer's SMTP debug output: 'html', 'echo', 'error_log' or user defined function with parameter $str and $level. NULL or '' means 'echo' on CLI, 'html' otherwise.
$config['smtp_auto_tls']    = true;                    // Whether to enable TLS encryption automatically if a server supports it, even if `smtp_crypto` is not set to 'tls'.
$config['smtp_conn_options'] = array();                 // SMTP connection options, an array passed to the function stream_context_create() when connecting via SMTP.
$config['wordwrap']         = true;
$config['wrapchars']        = 76;
$config['mailtype']         = 'html';                   // 'text' or 'html'
$config['charset']          = null;                     // 'UTF-8', 'ISO-8859-15', ...; NULL (preferable) means config_item('charset'), i.e. the character set of the site.
$config['validate']         = true;
$config['priority']         = 3;                        // 1, 2, 3, 4, 5; on PHPMailer useragent NULL is a possible option, it means that X-priority header is not set at all, see https://github.com/PHPMailer/PHPMailer/issues/449
$config['crlf']             = "\n";                     // "\r\n" or "\n" or "\r"
$config['newline']          = "\n";                     // "\r\n" or "\n" or "\r"
$config['bcc_batch_mode']   = false;
$config['bcc_batch_size']   = 200;
$config['encoding']         = '8bit';                   // The body encoding. For CodeIgniter: '8bit' or '7bit'. For PHPMailer: '8bit', '7bit', 'binary', 'base64', or 'quoted-printable'.
$config['charset']			= "UTF-8";
// XOAUTH2 mechanism for authentication.
// See https://github.com/PHPMailer/PHPMailer/wiki/Using-Gmail-with-XOAUTH2
$config['oauth_type']          = 'xoauth2_google';      // XOAUTH2 authentication mechanism:
                                                        // ''                  - disabled;
                                                        // 'xoauth2'           - custom implementation;
                                                        // 'xoauth2_google'    - Google provider;
                                                        // 'xoauth2_yahoo'     - Yahoo provider;
                                                        // 'xoauth2_microsoft' - Microsoft provider.
$config['oauth_instance']      = null;                  // Initialized instance of \PHPMailer\PHPMailer\OAuth (OAuthTokenProvider interface) that contains a custom token provider. Needed for 'xoauth2' custom implementation only. 
$config['oauth_user_email']    = '';                    // If this option is an empty string or null, $config['smtp_user'] will be used.
$config['oauth_client_id']     = '237644427849-g8d0pnkd1jh3idcjdbopvkse2hvj0tdp.apps.googleusercontent.com';
$config['oauth_client_secret'] = 'mklHhrns6eF-qjwuiLpSB4DL';
$config['oauth_refresh_token'] = '1/7Jt8_RHX86Pk09VTfQd4O_ZqKbmuV7HpMNz-rqJ4KdQMEudVrK5jSpoR30zcRFq6';

// DKIM Signing
$config['dkim_domain']      = '';                       // DKIM signing domain name, for exmple 'example.com'.
$config['dkim_private']     = '';                       // DKIM private key, set as a file path.
$config['dkim_private_string'] = '';                    // DKIM private key, set directly from a string.
$config['dkim_selector']    = '';                       // DKIM selector.
$config['dkim_passphrase']  = '';                       // DKIM passphrase, used if your key is encrypted.
$config['dkim_identity']    = '';                       // DKIM Identity, usually the email address used as the source of the email.

$config["mail"]["weeklyplan"]["from"]="support_moaicrm@qbiosci.com";
$config["mail"]["weeklyplan"]["to"]="wuttipong@aisyst.com";
$config["mail"]["weeklyplan"]["from_name"] = "Support MOAI";
$config["mail"]["weeklyplan"]["subject"] = "MOAI ::Weekly Plan";

$config["mail"]["weeklyreport"]["from"]="support_moaicrm@qbiosci.com";
$config["mail"]["weeklyreport"]["to"]="wuttipong@aisyst.com";
$config["mail"]["weeklyreport"]["from_name"] = "Support MOAI";
$config["mail"]["weeklyreport"]["subject"] = "MOAI ::Daily Report";
