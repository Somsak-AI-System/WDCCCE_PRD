<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/*
$config['useragent']       	 		= 'PHPMailer';              // Mail engine switcher: 'CodeIgniter' or 'PHPMailer'
$config['protocol']         			= 'smtp';                   // 'mail', 'sendmail', or 'smtp'
$config['mailpath']         			= '/usr/sbin/sendmail';
//$config['smtp_host']        		= 'mail.aisyst.com';
$config['_smtp_auth']        		= false;
$config['smtp_host']        		= '10.224.21.243'; 
$config['smtp_user']       		= '';
$config['smtp_pass']        		= '';
$config['smtp_port']        		= 25;
$config['smtp_timeout']     		= 300;                       // (in seconds)
$config['smtp_crypto']      		= '';                       // '' or 'tls' or 'ssl'
$config['smtp_debug']       		=1;                        // PHPMailer's SMTP debug info level: 0 = off, 1 = commands, 2 = commands and data, 3 = as 2 plus connection status, 4 = low level data output.
$config['wordwrap']         		= false;
$config['wrapchars']       		= 200;
$config['mailtype']         			= 'html';                   // 'text' or 'html'
$config['charset']          			= 'utf-8';
$config['validate']         			= true;
$config['priority']        			= 3;                        // 1, 2, 3, 4, 5
$config['crlf']             				= "\n";                     // "\r\n" or "\n" or "\r"
$config['newline']          			= "\n";                     // "\r\n" or "\n" or "\r"
$config['bcc_batch_mode']   	= false;
$config['bcc_batch_size']   		= 200;
$config['encoding']         		= '8bit';                   // The body encoding. For CodeIgniter: '8bit' or '7bit'. For PHPMailer: '8bit', '7bit', 'binary', 'base64', or 'quoted-printable'.
*/
$config['useragent']           = "CodeIgniter";
$config['protocol']              = "smtp";
//$config['smtp_host']           = "mail.aisyst.com";
$config['smtp_host']           = "mail.meditopthailand.com";
$config['smtp_port']            = "587";
$config['smtp_user'] = 'aicrm@meditopthailand.com';
$config['smtp_pass'] = 'FumyG30Yd';
//$config['smtp_user'] = 'support_crm@aisyst.com';
//$config['smtp_pass'] = 'yToKj4Sn';
$config['charset'] = 'utf-8';
$config['wordwrap'] = TRUE;
$config['mailtype'] = 'html';


/*$config['useragent']           = "CodeIgniter";
$config['protocol']            = "smtp";
$config['smtp_host']           = "mail.meditopthailand.com";
$config['smtp_port']           = "5";
$config['smtp_user'] = 'aicrm@meditopthailand.com';
$config['smtp_pass'] = 'FumyG30Yd';
$config['charset'] = 'utf-8';
$config['wordwrap'] = TRUE;
$config['mailtype'] = 'html';*/



$config["mail"]["weeklyplan"]["from"]="support_crm@aisyst.com";
$config["mail"]["weeklyplan"]["to"]="panudda@aisyst.com";
$config["mail"]["weeklyplan"]["from_name"] = "Support Ai-CRM";
$config["mail"]["weeklyplan"]["subject"] = "MEDT ::Weekly Plan";


$config["mail"]["weeklyreport"]["from"]="support_crm@aisyst.com";
$config["mail"]["weeklyreport"]["to"]="panudda@aisyst.com";
$config["mail"]["weeklyreport"]["from_name"] = "Support Ai-CRM";
$config["mail"]["weeklyreport"]["subject"] = "MEDT ::Daily Report";

$config["mail_job"]["job"]["from"]="support_crm@aisyst.com";
$config["mail_job"]["job"]["to"]="nutthawat.t@aisystem.co.th";
$config["mail_job"]["job"]["from_name"] = "Support Ai-CRM";
$config["mail_job"]["job"]["subject"] = "MOSIDTD ::Job E-mail";
