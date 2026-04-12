<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lib_mail_template
{


    function __construct()
    {
        $this->ci = &get_instance();

        $this->ci->load->database();
        $this->ci->load->library('email');
        $this->ci->config->load('email');
        $this->ci->load->library("common");
        $this->_configmail = array(
            "from" => "",
            "from_name" => "",
            "to" => "",
            "cc" => "",
            "subject" => "",
            "msg" => "",
        );
        $this->_return = array(
            "status" => false,
            "message" => "",
            "time" => date("Y-m-d H:i:s"),
            "data" => array("data" => ""),
        );
    }


    private function send_mail()
    {
        $filename = "";
        if (isset($this->ci->common->_filename) || $this->ci->common->_filename != "") {
            $filename = $this->ci->common->_filename;
        }else{
        	$filename = "Send_Mail";
        }
		;
        $this->ci->common->_filename = "Send_Mail";
        $this->ci->email->from(strip_tags($this->_configmail["from"]), $this->_configmail["from_name"]);
		
        //hide for test
        //$this->ci->email->to(strip_tags("rattana@aisyst.com"));
        //$this->ci->email->to(strip_tags("noppawat@aisyst.com"));
        //$this->ci->email->to(strip_tags("wuttipong@aisyst.com"));
        if (isset($this->_configmail["attach"])) {
            foreach ($this->_configmail["attach"] as $key => $value) {
                $this->ci->email->attach($value);
            }
        }

		$mail = $this->ci->config->item('mail');
		// echo $mail["email_cc"]; exit;
		// alert($this->ci->email); exit();
        $this->ci->email->bcc(@$this->_configmail["bcc"]);
        $this->ci->email->cc(@$this->_configmail["cc"]);
		$this->ci->email->cc($mail["email_cc"]);
        $this->ci->email->to($this->_configmail["to"]);
        $this->ci->email->subject($this->_configmail["subject"]);
        $this->ci->email->message($this->_configmail["msg"]);

        // $a_param = $this->ci->email->header();
        $a_param['mail_to'] = $this->_configmail["to"];
		$a_param['mail_cc'] = $mail["email_cc"];
        // $this->ci->common->set_log(" Send Mail ", $a_param, @$a_data);
        // alert($this->ci->email); exit();
        if (!$this->ci->email->send()) {
            $a_data['status'] = false;
            $a_data['message'] = "Can't send e-mail,Please try again";
        } else {
            $a_data['status'] = true;
            $a_data['message'] = "Send E-mail Complete";
        }
        // alert($a_data); exit();
        $a_data["time"] = date("Y-m-d H:i:s");
        $a_data["data"]["data"] = "";

        // $this->ci->common->set_log(" Send Mail ", $a_param, $a_data);
        // $this->ci->common->_filename = $filename;

        return $a_data;
    }

    /////////////////////////
	public function send_mail_forgot_password($params=array())
	{
		$msg = $this->head_mail();
		$msg .= '
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td class="h2">
							เรียนคุณ [Dear]
						</td>
					</tr>
					<tr>
						<td class="bodycopy">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="bodycopy">
						ท่านได้ทำการแจ้งขอเปลี่ยนรหัสผ่าน สำหรับการใช้งานระบบ CRM
						</td>
					</tr>
					<tr>
						<td class="bodycopy">
						<a href="[url]" target="_blank">เปลี่ยนรหัสผ่านคลิกที่นี่</a>
						</td>
					</tr>
					<tr>
						<td class="bodycopy">
							&nbsp;
						</td>
					</tr>
				</table>
			</td>

		</tr>
		';
		$msg .= $this->footer_mail();
	
	
	
		$msg = str_replace("[Dear]", $params["name"], $msg);
		$msg = str_replace("[url]", $params["url"], $msg);

		// echo $params["emailTo"]; exit;
		if(isset($params["crmID"]) && $params["crmID"]!=''){
			$mail = $this->ci->config->item('mail');
			$this->_configmail["to"] = $params["emailTo"];
			$this->_configmail["cc"] = $mail["email_cc"];
			$this->_configmail["from"] = 'noreply@aisyst.com';
			$this->_configmail["from_name"] = 'Ai-CRM';
			$this->_configmail["subject"] = "เปลี่ยนรหัสผ่าน";
			$this->_configmail["msg"] = $msg;
			// alert($this->_configmail);
			// $mail = $this->ci->config->item('mail');
			// alert($mail); exit;
			$a_return = $this->send_mail();
			// print_r($a_return); exit();
			$a_return['html'] =$msg;
			return array_merge($this->_return, $a_return);
		}else{
			return $this->_return;
		}
	}
    /////////////////////////
	public function send_mail_rpt_daily_report($params=array())
	{
		// $msg = $this->head_mail();
		$msg .= '
		<table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				Dear All,
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				&nbsp;&nbsp;&nbsp;&nbsp;ขอนำส่งสรุป Summary Issue Log ประจำวันที่ [date_daily] ตามไฟล์แนบค่ะ
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>A I SYSTEM CO., LTD.</td>
		</tr>
		<tr>
			<td>Lumpini Tower 21st Fl. Unit D,</td>
		</tr>
		<tr>
			<td>1168/59 Rama IV Rd,</td>
		</tr>
		<tr>
			<td>Thung Maha Mek, Sathon</td>
		</tr>
		<tr>
			<td>Bangkok, THAILAND 10120</td>
		</tr>
		<tr>
			<td>Line@: @471dounw</td>
		</tr>
		<tr>
			<td>Email: supportcrm@aisystem.co.th</td>
		</tr>
		<tr>
			<td>Web: <a href="http://www.aisystem.co.th" target="_blank">http://www.aisystem.co.th</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<img src="https://moaistd.Ai-CRM.com/aisystem/asset/images/logomail_new.png" width="600">
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Ai-CRM System:</td>
		</tr>
		<tr>
			<td><a href="https://www.youtube.com/watch?v=rQHI9SzZv3s" target="_blank">https://www.youtube.com/watch?v=rQHI9SzZv3s</a></td>
		</tr>
		<tr>
			<td>PIAS System:</td>
		</tr>
		<tr>
			<td><a href="http://www.youtube.com/watch?v=qRmrXovFUZU" target="_blank">http://www.youtube.com/watch?v=qRmrXovFUZU</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		</table>
		';
		// $msg .= $this->footer_mail();
	
	
	
		$msg = str_replace("[date_daily]", $params["date_daily"], $msg);
		// $msg = str_replace("[url]", $params["url"], $msg);

		// echo $params["emailTo"]; exit;
		if(isset($params["emailTo"]) && $params["emailTo"]!=''){
			$mail = $this->ci->config->item('mail');
			$this->_configmail["attach"] = $params["path_attaches"];
			$this->_configmail["to"] = $params["emailTo"];
			$this->_configmail["cc"] = $mail["email_cc"];
			$this->_configmail["from"] = 'noreply@aisyst.com';
			$this->_configmail["from_name"] = 'Ai-CRM';
			$this->_configmail["subject"] = $params['AccountName']." : Update Summary Issue Log on ".$params['date_daily'];
			$this->_configmail["msg"] = $msg;
			// alert($this->_configmail); exit;
			// $mail = $this->ci->config->item('mail');
			// alert($mail); exit;
			$a_return = $this->send_mail();
			// print_r($a_return); exit();
			$a_return['html'] =$msg;
			return array_merge($this->_return, $a_return);
		}else{
			return $this->_return;
		}
	}

	
	/////////////////////////
	public function send_mail_rpt_weekly_report($params=array())
	{
		// $msg = $this->head_mail();
		$msg .= '
		<table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				Dear All,
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				&nbsp;&nbsp;&nbsp;&nbsp; ขอนำส่งสรุป Summary Issue Log ประจำสัปดาห์ที่ [date_start] - [date_end] ตามไฟล์แนบค่ะ
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>A I SYSTEM CO., LTD.</td>
		</tr>
		<tr>
			<td>Lumpini Tower 21st Fl. Unit D,</td>
		</tr>
		<tr>
			<td>1168/59 Rama IV Rd,</td>
		</tr>
		<tr>
			<td>Thung Maha Mek, Sathon</td>
		</tr>
		<tr>
			<td>Bangkok, THAILAND 10120</td>
		</tr>
		<tr>
			<td>Line@: @471dounw</td>
		</tr>
		<tr>
			<td>Email: supportcrm@aisystem.co.th</td>
		</tr>
		<tr>
			<td>Web: <a href="http://www.aisystem.co.th" target="_blank">http://www.aisystem.co.th</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<img src="https://moaistd.Ai-CRM.com/aisystem/asset/images/logomail_new.png" width="600">
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Ai-CRM System:</td>
		</tr>
		<tr>
			<td><a href="https://www.youtube.com/watch?v=rQHI9SzZv3s" target="_blank">https://www.youtube.com/watch?v=rQHI9SzZv3s</a></td>
		</tr>
		<tr>
			<td>PIAS System:</td>
		</tr>
		<tr>
			<td><a href="http://www.youtube.com/watch?v=qRmrXovFUZU" target="_blank">http://www.youtube.com/watch?v=qRmrXovFUZU</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		</table>
		';
		// $msg .= $this->footer_mail();
	
	
	
		$msg = str_replace("[date_start]", $params["date_start"], $msg);
		$msg = str_replace("[date_end]", $params["date_end"], $msg);

		// echo $params["emailTo"]; exit;
		if(isset($params["emailTo"]) && $params["emailTo"]!=''){
			$mail = $this->ci->config->item('mail');
			$this->_configmail["attach"] = $params["path_attaches"];
			$this->_configmail["to"] = $params["emailTo"];
			$this->_configmail["cc"] = $mail["email_cc"];
			$this->_configmail["from"] = 'noreply@aisyst.com';
			$this->_configmail["from_name"] = 'Ai-CRM';
			$this->_configmail["subject"] = $params['AccountName']." : Update Summary Issue Log on ".$params['date_daily'];
			$this->_configmail["msg"] = $msg;
			// alert($this->_configmail); exit;
			// $mail = $this->ci->config->item('mail');
			// alert($mail); exit;
			$a_return = $this->send_mail();
			// print_r($a_return); exit();
			$a_return['html'] =$msg;
			return array_merge($this->_return, $a_return);
		}else{
			return $this->_return;
		}
	}

	/////////////////////////
	public function send_mail_rpt_monthly_report($params=array())
	{
		// $msg = $this->head_mail();
		$msg .= '
		<table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				Dear All,
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				&nbsp;&nbsp;&nbsp;&nbsp; ขอนำส่งสรุป Summary Issue Log ประจำเดือน [monthly] ตามไฟล์แนบค่ะ
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>A I SYSTEM CO., LTD.</td>
		</tr>
		<tr>
			<td>Lumpini Tower 21st Fl. Unit D,</td>
		</tr>
		<tr>
			<td>1168/59 Rama IV Rd,</td>
		</tr>
		<tr>
			<td>Thung Maha Mek, Sathon</td>
		</tr>
		<tr>
			<td>Bangkok, THAILAND 10120</td>
		</tr>
		<tr>
			<td>Line@: @471dounw</td>
		</tr>
		<tr>
			<td>Email: supportcrm@aisystem.co.th</td>
		</tr>
		<tr>
			<td>Web: <a href="http://www.aisystem.co.th" target="_blank">http://www.aisystem.co.th</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<img src="https://moaistd.Ai-CRM.com/aisystem/asset/images/logomail_new.png" width="600">
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Ai-CRM System:</td>
		</tr>
		<tr>
			<td><a href="https://www.youtube.com/watch?v=rQHI9SzZv3s" target="_blank">https://www.youtube.com/watch?v=rQHI9SzZv3s</a></td>
		</tr>
		<tr>
			<td>PIAS System:</td>
		</tr>
		<tr>
			<td><a href="http://www.youtube.com/watch?v=qRmrXovFUZU" target="_blank">http://www.youtube.com/watch?v=qRmrXovFUZU</a></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		</table>
		';
		// $msg .= $this->footer_mail();
	
	
	
		$msg = str_replace("[monthly]", $params["monthly"], $msg);
		// $msg = str_replace("[url]", $params["url"], $msg);

		// echo $msg; exit;

		// echo $params["emailTo"]; exit;
		if(isset($params["emailTo"]) && $params["emailTo"]!=''){
			$mail = $this->ci->config->item('mail');
			$this->_configmail["attach"] = $params["path_attaches"];
			$this->_configmail["to"] = $params["emailTo"];
			$this->_configmail["cc"] = $mail["email_cc"];
			$this->_configmail["from"] = 'noreply@aisyst.com';
			$this->_configmail["from_name"] = 'Ai-CRM';
			$this->_configmail["subject"] = $params['AccountName']." : Update Summary Issue Log & CR List for ".$params['monthly'];
			$this->_configmail["msg"] = $msg;
			// alert($this->_configmail); exit;
			// $mail = $this->ci->config->item('mail');
			// alert($mail); exit;
			$a_return = $this->send_mail();
			// print_r($a_return); exit();
			$a_return['html'] =$msg;
			return array_merge($this->_return, $a_return);
		}else{
			return $this->_return;
		}
	}

/////////////////////////
public function send_mail_rpt_monthly_expenses($params=array())
{
	// $msg = $this->head_mail();
	$msg .= '
	<table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			เรื่อง Ai-CRM :: Monthly Expenses Report ช่วงวันที่ [date_start] ถึงวันที่ [date_end]
		</td>
	</tr>
	<tr>
		<td>
			ข้อมูล ช่วงวันที่ [date_start] ถึงวันที่ [date_end]
		</td>
	</tr>
	<tr>
		<td>
			ข้อมูล จาก [from]
		</td>
	</tr>
	<tr>
		<td>
			ข้อมูล วันที่ [date_now] เวลา [time_now]
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	</table>
	';
	// $msg .= $this->footer_mail();


	$msg = str_replace("[from]", $params["from"], $msg);
	$msg = str_replace("[date_start]", $params["date_start"], $msg);
	$msg = str_replace("[date_end]", $params["date_end"], $msg);
	$msg = str_replace("[date_now]", $params["date_now"], $msg);
	$msg = str_replace("[time_now]", $params["time_now"], $msg);
	// echo $msg; exit;

	// echo $params["emailTo"]; exit;
	if(isset($params["emailTo"]) && $params["emailTo"]!=''){
		$mail = $this->ci->config->item('mail');
		$this->_configmail["attach"] = $params["path_attaches"];
		$this->_configmail["to"] = $params["emailTo"];
		$this->_configmail["cc"] = $params["emailCC"];
		$this->_configmail["from"] = 'noreply@aisyst.com';
		$this->_configmail["from_name"] = 'Ai-CRM';
		$this->_configmail["subject"] = "Ai-CRM :: Monthly Expenses Report ช่วงวันที่ ".$params['date_start']." ถึงวันที่ ".$params['date_end'];
		$this->_configmail["msg"] = $msg;
		// alert($this->_configmail); exit;
		// $mail = $this->ci->config->item('mail');
		// alert($mail); exit;
		$a_return = $this->send_mail();
		// print_r($a_return); exit();
		$a_return['html'] =$msg;
		return array_merge($this->_return, $a_return);
	}else{
		return $this->_return;
	}
}
	/////////////////////////

	public function send_mail_rpt_sales_forecast_summary_report($params=array())
	{
		// $msg = $this->head_mail();
		$msg .= '
		<table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				เรื่อง Ai-CRM :: Sales Forecast Summary Report ช่วงวันที่ [date_start] ถึงวันที่ [date_end]
			</td>
		</tr>
		<tr>
			<td>
				ข้อมูล ช่วงวันที่ [date_start] ถึงวันที่ [date_end]
			</td>
		</tr>
		<tr>
			<td>
				ข้อมูล จาก [from]
			</td>
		</tr>
		<tr>
			<td>
				ข้อมูล วันที่ [date_now] เวลา [time_now]
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		</table>
		';
		// $msg .= $this->footer_mail();
	
	
		$msg = str_replace("[from]", $params["from"], $msg);
		$msg = str_replace("[date_start]", $params["date_start"], $msg);
		$msg = str_replace("[date_end]", $params["date_end"], $msg);
		$msg = str_replace("[date_now]", $params["date_now"], $msg);
		$msg = str_replace("[time_now]", $params["time_now"], $msg);
		// echo $msg; exit;
	
		// echo $params["emailTo"]; exit;
		if(isset($params["emailTo"]) && $params["emailTo"]!=''){
			$mail = $this->ci->config->item('mail');
			$this->_configmail["attach"] = $params["path_attaches"];
			$this->_configmail["to"] = $params["emailTo"];
			$this->_configmail["cc"] = $params["emailCC"];
			$this->_configmail["from"] = 'noreply@aisyst.com';
			$this->_configmail["from_name"] = 'Ai-CRM';
			$this->_configmail["subject"] = "Ai-CRM :: Sales Forecast Summary Report ช่วงวันที่ ".$params['date_start']." ถึงวันที่ ".$params['date_end'];
			$this->_configmail["msg"] = $msg;
			// alert($this->_configmail); exit;
			// $mail = $this->ci->config->item('mail');
			// alert($mail); exit;
			$a_return = $this->send_mail();
			// print_r($a_return); exit();
			$a_return['html'] =$msg;
			return array_merge($this->_return, $a_return);
		}else{
			return $this->_return;
		}
	}
		/////////////////////////


public function send_mail_rpt_daily_new_project($params=array())
{
	// $msg = $this->head_mail();
	$msg .= '
	<table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			เรื่อง Ai-CRM :: Daily New Project Report วันที่ [date]
		</td>
	</tr>
	<tr>
		<td>
			ข้อมูล วันที่ [date]
		</td>
	</tr>
	<tr>
		<td>
			ข้อมูล จาก [from]
		</td>
	</tr>
	<tr>
		<td>
			ข้อมูล วันที่ [date_now] เวลา [time_now]
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	</table>
	';
	// $msg .= $this->footer_mail();


	$msg = str_replace("[from]", $params["from"], $msg);
	$msg = str_replace("[date]", $params["date"], $msg);
	$msg = str_replace("[date_now]", $params["date_now"], $msg);
	$msg = str_replace("[time_now]", $params["time_now"], $msg);
	// echo $msg; exit;

	// echo $params["emailTo"]; exit;
	if(isset($params["emailTo"]) && $params["emailTo"]!=''){
		$mail = $this->ci->config->item('mail');
		$this->_configmail["attach"] = $params["path_attaches"];
		$this->_configmail["to"] = $params["emailTo"];
		$this->_configmail["cc"] = $params["emailCC"];
		$this->_configmail["from"] = 'noreply@aisyst.com';
		$this->_configmail["from_name"] = 'Ai-CRM';
		$this->_configmail["subject"] = "Ai-CRM :: Daily New Project Report	วันที่ ".$params['date'];
		$this->_configmail["msg"] = $msg;
		// alert($this->_configmail); exit;
		// $mail = $this->ci->config->item('mail');
		// alert($mail); exit;
		$a_return = $this->send_mail();
		// print_r($a_return); exit();
		$a_return['html'] =$msg;
		return array_merge($this->_return, $a_return);
	}else{
		return $this->_return;
	}
}
	/////////////////////////

	public function send_mail_rpt_sales_team_forecast_report_by_stage($params=array())
	{
		// $msg = $this->head_mail();
		$msg .= '
		<table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				เรื่อง Ai-CRM :: Sales Team Forecast Report ช่วงวันที่ [date_from] ถึงวันที่ [date_to]
			</td>
		</tr>
		<tr>
			<td>
				ข้อมูล ช่วงวันที่ [date_from] ถึงวันที่ [date_to]
			</td>
		</tr>
		<tr>
			<td>
				ข้อมูล จาก [from]
			</td>
		</tr>
		<tr>
			<td>
				ข้อมูล วันที่ [date_now] เวลา [time_now]
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		</table>
		';
		// $msg .= $this->footer_mail();
	
	
		$msg = str_replace("[from]", $params["from"], $msg);
		$msg = str_replace("[date_from]", $params["date_from"], $msg);
		$msg = str_replace("[date_end]", $params["date_to"], $msg);
		$msg = str_replace("[date_now]", $params["date_now"], $msg);
		$msg = str_replace("[time_now]", $params["time_now"], $msg);
		// echo $msg; exit;
	
		// echo $params["emailTo"]; exit;
		if(isset($params["emailTo"]) && $params["emailTo"]!=''){
			$mail = $this->ci->config->item('mail');
			$this->_configmail["attach"] = $params["path_attaches"];
			$this->_configmail["to"] = $params["emailTo"];
			$this->_configmail["cc"] = $params["emailCC"];
			$this->_configmail["from"] = 'noreply@aisyst.com';
			$this->_configmail["from_name"] = 'Ai-CRM';
			$this->_configmail["subject"] = "Ai-CRM :: Sales Team Forecast Report ช่วงวันที่ ".$params['date_from']." ถึงวันที่ ".$params['date_to'];
			$this->_configmail["msg"] = $msg;
			// alert($this->_configmail); exit;
			// $mail = $this->ci->config->item('mail');
			// alert($mail); exit;
			$a_return = $this->send_mail();
			// print_r($a_return); exit();
			$a_return['html'] =$msg;
			return array_merge($this->_return, $a_return);
		}else{
			return $this->_return;
		}
	}


    private function head_mail()
    {
        $mail = $this->ci->config->item('mail');
        $message = '<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						 <style type="text/css">
						 			a:hover {cursor:pointer;}
									body {margin: 0; padding: 0; min-width: 100%!important;}
									img {height: auto;}
									.content {width: 100%; max-width: 600px;}
									.header {padding: 10px 20px 20px 20px;}
									.innerpadding {padding: 30px 30px 30px 30px;}
									.borderbottom {border-bottom: 0px solid #f2eeed;}
									.subhead {font-size: 15px; color: #45c0ae; font-family: sans-serif; letter-spacing: 3px;}
									.h1, .h2, .bodycopy {color: #153643; font-family: sans-serif;}
									.h1 {font-size: 24px; line-height: 38px; font-weight: bold;}
									.h2 {padding: 0 0 15px 0; font-size: 14px; line-height: 28px; font-weight:100; font-family:tahoma; font-style:normal; }
									.bodycopy { font-family: sans-serif; font-size: 14px; line-height: 22px;}
									.button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 40px 0 40px; margin-top:20px; color: #ffffff; background-color:#1589FF; border-color:#FFFFFF;}
									.button a {color: #ffffff; text-decoration: none;}
									.footer {padding: 20px 30px 15px 30px; font-family: sans-serif; font-size: 14px;}
									.footercopy {font-family: sans-serif; font-size: 14px; color: #ffffff;}
									.footercopy a {color: #ffffff; text-decoration: underline;}

									@media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
									body[yahoo] .hide {display: none!important;}
									body[yahoo] .buttonwrapper {background-color: transparent!important;}
									body[yahoo] .button {padding: 0px!important;}
									body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}
									body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
									}

									/*@media only screen and (min-device-width: 601px) {
										.content {width: 600px !important;}
										.col425 {width: 425px!important;}
										.col380 {width: 380px!important;}
										}*/
							</style>
						</head>
				<body yahoo bgcolor="#ffffff">
								<table width="100%" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										 <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
													<tr>

													 </tr>';
        /*  <td bgcolor="#ffffff" class="header">
                                                        </td>*/
        return $message;

    }


    private function footer_mail()
    {
        $mail = $this->ci->config->item('mail');
        $message = '<tr>
						<td class="footer" bgcolor="#ffffff">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
								<tr>

								 

								</tr>
							</table>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>';

        $message = str_replace("[email_support]", $mail["email_support"], $message);
        return $message;
    }
}
