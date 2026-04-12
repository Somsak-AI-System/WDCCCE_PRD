<?php defined('BASEPATH') OR exit('No direct script access allowed.');

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


$config["mail"]["forget_password"]["from"]="support@sena.co.th";
$config["mail"]["forget_password"]["email_support_cc_mail"]="";
$config["mail"]["forget_password"]["from_name"] = "NHASNH CRM";
$config["mail"]["forget_password"]["email_to_wecare"] = "support@sena.co.th";
$config["mail"]["forget_password"]["url"] ="http://".$_SERVER['HTTP_HOST']."/NHASNH/resetpassword";
// $config["mail"]["forget_password"]["url"] ="http://".$_SERVER['HTTP_HOST']."/sena/wecare_new/resetpassword";
$config["mail"]["forget_password"]["subject"] = "NHASNH ::Foget Password";
$config["mail"]["forget_password"]["encode"] = "aicrm_reset";


$config["mail"]["reset_password"]["from"]="support@sena.co.th";
$config["mail"]["reset_password"]["email_support_cc_mail"]="";
$config["mail"]["reset_password"]["from_name"] = "NHASNH CRM";
$config["mail"]["reset_password"]["email_to_wecare"] = "support@sena.co.th";
$config["mail"]["reset_password"]["url"] ="http://".$_SERVER['HTTP_HOST']."/NHASNH/";
// $config["mail"]["reset_password"]["url"] ="http://".$_SERVER['HTTP_HOST']."/sena/wecare_new";
$config["mail"]["reset_password"]["subject"] = "NHASNH :: Reset Password";
$config["mail"]["reset_password"]["encode"] = "";

$config["mail"]["weeklyplan"]["from"]="noreply@aisyst.com";
$config["mail"]["weeklyplan"]["to"]="";
$config["mail"]["weeklyplan"]["from_name"] = "Support MOAI-CRM";
$config["mail"]["weeklyplan"]["subject"] = "MOAICRM :: Weekly Plan";

$config["mail"]["monthlyplan"]["from"]="noreply@aisyst.com";
$config["mail"]["monthlyplan"]["to"]="";
$config["mail"]["monthlyplan"]["from_name"] = "Support MOAI-CRM";
$config["mail"]["monthlyplan"]["subject"] = "MOAICRM :: Monthly Plan";


$config["mail"]["weeklyreport"]["from"]="noreply@aisyst.com";
$config["mail"]["weeklyreport"]["to"]="";
$config["mail"]["weeklyreport"]["from_name"] = "Support MOAI-CRM";
$config["mail"]["weeklyreport"]["subject"] = "MOAICRM :: Weekly Report";

$config["mail"]["approve"]["from"]="noreply@aisyst.com";
$config["mail"]["approve"]["to"]="";
$config["mail"]["approve"]["from_name"] = "Support MOAI-CRM";
$config["mail"]["approve"]["subject"] = "";

$config["mail"]["quotation_report"]["from"]="noreply@aisyst.com";
$config["mail"]["quotation_report"]["to"]="";
$config["mail"]["quotation_report"]["from_name"] = "Support MOAI-CRM";
$config["mail"]["quotation_report"]["subject"] = "";