<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Email extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $meta = $this->config->item('meta');
        $lang = $this->config->item('lang');
        $this->title = $meta["default"]["title"];
        $this->keyword = $meta["default"]["keyword"];
        $this->description = $meta["default"]["description"];
        $this->load->library('curl');
        $this->lang->load('ai', $lang);
        //$this->load->config('api');
        $this->url_service = $this->config->item('service');
        $this->load->model('email_model');
    }

    public function index()
    {
        $crmid = $this->input->get('crmid');
        $userid = $this->input->get('userid');
        $this->template->title("Email | MOAISTD"); // 11 words or 70 characters
        $data = array();
        $data['crmid'] = $crmid;
        $data['userid'] = $userid;

        $module = $this->getsetype($crmid);
        // alert($module);exit;
        $assignto = $this->getUserEmailId($crmid, $module);
        // alert($assignto);
        // exit;
        $data['email_assignto'][] = $this->getEmail($assignto);
        // alert($data);
        // exit;
        $data['email_allUser'] = $this->get_emailAllUser();
        $data['email_allUser'] = array_unique(array_merge($data['email_allUser'], $data['email_assignto']));

        $data['email_account'][] = $this->get_emailAccount($crmid, $module);
        // alert($data['email_account']);
        // exit;
        $data['email_allUser'] = array_unique(array_merge($data['email_allUser'], $data['email_account']));
        // alert($data['email_allUser']);
        // exit;
        $data['reportData'] = $this->getReportCRMID($crmid, $userid, $module);
        // alert($data['reportData']);

        $data['subject'] = $data['reportData']['subject'];

        //alert($data);
        // exit;
        $this->template->set_layout('email');
        $this->template->build('index', $data, FALSE, TRUE);
    }

    private function getReportCRMID($crmID, $userID, $module)
    {

        if ($module == 'Job') {
            $data = $this->getReport($crmID, $userID, $module, "jobreport");
        } elseif ($module == 'Quotes') {
            $data = $this->getReport($crmID, $userID, $module, "quotation_report");
        } elseif ($module == 'Inspection') {
            $data = $this->getReport($crmID, $userID, $module, "inspectionreport");
        }
        // alert($data);
        return $data;
    }

    private function getReport($crmID, $userID, $module, $submodule)
    {
        global $site_URL;
        $fields['AI-API-KEY'] = '1234';
        $fields['crmid'] = $crmID;
        $fields['userid'] = $userID;
        $fields['module'] = $module;
        $fields['report_plan'] = '';
        $fields['submodule'] = $submodule;
        // echo json_encode($fields);exit;
        $url = $site_URL . "WB_Service_AI/crmreport/send_crmreport";
        $fields_string = json_encode($fields);
        $json_url = $url;

        $json_string = $fields_string;
        $ch = curl_init($json_url);
        $options = array(
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json'),
            CURLOPT_POSTFIELDS => $json_string,
            CURLOPT_BUFFERSIZE => 1024
        );

        curl_setopt_array($ch, $options);
        $result =  curl_exec($ch);
        // alert($result);exit;
        $return = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result), true);

        $returnData = [
            'status' => false
        ];
        if (isset($return['Type']) && $return['Type'] == 'S') {
            $fileName = end(explode('/', $return['path_file']));
            $filePath = $return['path_file'];

            $returnData = [
                'status' => true,
                'rootDir' => $return['root_directory'],
                'exportPath' => $return['path'],
                'fileName' => $fileName,
                'filePath' => $filePath,
                'subject' => $return['Subject']
            ];
        }

        return $returnData;
    }

    public function getsetype($crmid = null)
    {
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("email_model");
        $a_result = $this->email_model->getsetype($crmid);
        // alert($a_result); exit;
        return @$a_result;
    }

    public function getUserEmailId($crmid = null, $module = "")
    {
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("email_model");
        $a_result = $this->email_model->getUserEmailId($crmid, $module);
        //alert($a_result); exit;
        return @$a_result[0]['smownerid'];
    }

    public function getEmail($crmid = null)
    {
        $this->load->model("email_model");
        $a_result = $this->email_model->get_email($crmid);
        //alert($a_result); exit;
        return @$a_result[0]['email1'];
    }

    public function get_emailAllUser()
    {
        $this->load->model("email_model");
        $a_result = $this->email_model->get_emailAllUser();

        foreach ($a_result as $a_result2) {
            $email[] = $a_result2['email1'];
        }
        // alert($email);
        // exit;
        return @$email;
    }


    public function get_emailAccount($crmid = null, $module = "")
    {
        $this->load->model("email_model");
        $a_result = $this->email_model->get_emailAccount($crmid, $module);
        // alert($a_result);
        // exit;
        return @$a_result[0]['email1'];
    }

    public function send()
    {
        global $root_directory;
        $a_data = $this->input->post();

        $crmid = $a_data['crmID'];
        $userid = $a_data['userID'];
        // alert($a_data);
        // exit();

        if (isset($a_data['path']) && $a_data['path'] != '') {
            // alert($a_data['path']);
            $temp_filedata = [];
            $temp_pathAttachfile = [];

            $temp_filedata[] = $a_data['path'];
        }
        // alert($temp_filedata);exit;

        $allPathfile = [];

        $this->load->library('email');
        $this->config->load('email');

        $mail = $config_export = $this->config->item('mail_job');
        // alert($mail);exit;

        $from = $mail["job"]["from"];
        $from_name = $mail["job"]["from_name"];
        $subject = $a_data['emailTitle'];

        $this->email->subject(strip_tags($subject));

        $this->email->from(strip_tags($from), strip_tags($from_name));

        if (isset($a_data['emailTo']) && $a_data['emailTo'] != '') {
            $to = $a_data['emailTo'];
            $this->email->to($to);
        }
        if (isset($a_data['emailCC']) && $a_data['emailCC'] != '') {

            $cc_mail = $a_data['emailCC'];
            $this->email->cc($cc_mail);
        }
        if (isset($a_data['emailBCC']) && $a_data['emailBCC'] != '') {

            $cc_mail = $a_data['emailBCC'];
            $this->email->bcc($cc_mail);
        }


        $message = nl2br($a_data['emailBody']);

        // echo $message;
        // exit;
        //Start attch file to email

        //other report
        $allfile = 0;
        // alert($temp_filedata);exit;
        foreach ($temp_filedata as $all_temp_filedata) {
            // alert($all_temp_filedata);exit;
            if (file_exists($all_temp_filedata)) {

                $maxfilesize = 10000000;

                $allfile += filesize($all_temp_filedata);

                if ($allfile > $maxfilesize) {
                    $return['status'] = false;
                    $return['Message'] = "ขนาดไฟล์เที่แนบเกิน 10 MB";
                    $return['MailMSG'] = $this->email->print_debugger();
                    echo json_encode($return);
                    return false;
                } else {
                    $this->email->attach($all_temp_filedata);
                }
            }
        }

        $this->email->message($message);

        if (!$this->email->send()) {
            $return['status'] = false;
            $return['Message'] = "ส่งอีเมลไม่สำเร็จ";
            $return['MailMSG'] = $this->email->print_debugger();
        } else {
            $return['status'] = true;
            $return['Message'] = "ระบบได้ทำการส่งอีเมลสำเร็จ";
            $return['MailMSG'] = $this->email->print_debugger();
        }
        echo json_encode($return);
    }


    public function get_jobreport()
    {

        global $site_URL;
        $jobid = $_POST['jobid'];
        $userid = $_POST['userid'];

        $fields['AI-API-KEY'] = '1234';
        $fields['crmid'] = $jobid;
        $fields['userid'] = $userid;
        $fields['module'] = 'Job';
        $fields['report_plan'] = '';

        $url = $site_URL . "WB_Service_AI/jobreport/send_jobreport";

        $fields_string = json_encode($fields);
        $json_url = $url;

        $json_string = $fields_string;
        // exit();
        $ch = curl_init($json_url);
        $options = array(
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json'),
            CURLOPT_POSTFIELDS => $json_string
        );

        curl_setopt_array($ch, $options);
        $result =  curl_exec($ch);
        $json = json_decode($result, TRUE);

        $data = isset($json['data']) ? $json['data'] : $json;
        $data = empty($data) ? array() : $data;
        //        return $data;
        echo json_encode($data);
    }
}
