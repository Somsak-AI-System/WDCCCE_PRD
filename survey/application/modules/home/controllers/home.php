<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
    private $description, $title, $keyword;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('curl');
        //$this->siteURL = 'https://moaistd.moai-crm.com/';
        $this->siteURL = 'http://localhost:8090/GLAPCRM/';
    }

    public function index(){
        echo 'index';
    }

    public function get_account_questionnaire($templateid="",$accountid="")
    {

        $json_url = $this->siteURL.'WB_Service_AI/Smartquestionnaire/get_account_questionnaire';

        $fields = array(
            'AI-API-KEY'=>"1234",
            'module' => "Smartquestionnaire",
            'crmid' => $crmid,
            'accountid' => $accountid
        );

        $result = $this->postApi($json_url,$fields,array(),"json");

        if($result['Type']=="S"){
            redirect("home/questionnaire_template/".$templateid."/".$accountid."");
        }else{
            $Message = $result['Message'];
            $this->questionnaire_success($Message);
        }
    }

    public function questionnaire_template($templateid="", $accountid="")
    {
        $json_url = $this->siteURL.'WB_Service_AI/questionnaire/get_template';
        $fields = array(
            'AI-API-KEY'=>"1234",
            'module' => "Questionnaire",
            'crmid' => $templateid
        );

        $result = $this->postApi($json_url,$fields,array(),"json");

        if($result['Type']=="S"){

            $data['templateid'] = $templateid;
            $data['accountid'] = $accountid;

            $data['TEXT'] = $result;
            $this->load->view('header');
            $this->load->view('questionnaire',$data);
            $this->load->view('footer');
        
        }else{

            $Message = $result['Message'];
            $this->questionnaire_success($Message);

        }
    }

    public function questionnaire_answer($templateid="", $accountid="", $smartquestionnaireid="")
    {
        $json_url = $this->siteURL.'WB_Service_AI/questionnaire/chkAns';
        $fields = array(
            'AI-API-KEY'=>"1234",
            'module' => "Questionnaire",
            'questionnairetemplateid' => $templateid,
            'accountid' => $accountid,
            'smartquestionnaireid' => $smartquestionnaireid
        );
        $result = $this->postApi($json_url,$fields,array(),"json");

        if($result['row'] > 0){
            $Message = 'คุณได้ตอบแบบสอบถามแล้ว';
            $this->questionnaire_success($Message);
        }else{
            $json_url = $this->siteURL.'WB_Service_AI/questionnaire/getTemplateData';
            $fields = array(
                'AI-API-KEY'=>"1234",
                'module' => "Questionnaire",
                'crmid' => $templateid
            );
            $result = $this->postApi($json_url,$fields,array(),"json");

            $imageUrl = $this->siteURL.'WB_Service_AI/questionnaire/getTemplateImage';
            $rsImage = $this->postApi($imageUrl, $fields, array(), "json");

            if($result['Type']=="S"){

                $data['templateid'] = $templateid;
                $data['templatename'] = $result['data']['questionnairetemplatename'];
                $data['accountid'] = $accountid;
                $data['smartquestionnaireid'] = $smartquestionnaireid;
                $data['image'] = $rsImage;

                $data['TEXT'] = $result;
                $this->load->view('header');
                $this->load->view('questionnaire_answer',$data);
                $this->load->view('footer');

            }else{

                $Message = $result['Message'];
                $this->questionnaire_success($Message);

            }
        }


    }

    public function questionnaire_template_view($templateid="", $accountid="")
    {
        $json_url = $this->siteURL.'WB_Service_AI/questionnaire/getTemplateData';
        $fields = array(
            'AI-API-KEY'=>"1234",
            'module' => "Questionnaire",
            'crmid' => $templateid
        );
        $result = $this->postApi($json_url,$fields,array(),"json");
        //alert($result); exit;
        $imageUrl = $this->siteURL.'WB_Service_AI/questionnaire/getTemplateImage';
        $rsImage = $this->postApi($imageUrl, $fields, array(), "json");

        if($result['Type']=="S"){

            $data['templateid'] = $templateid;
            $data['accountid'] = $accountid;
            $data['image'] = $rsImage;

            $data['TEXT'] = $result;
            $this->load->view('header');
            $this->load->view('questionnaire_view',$data);
            $this->load->view('footer');

        }else{

            $Message = $result['Message'];
            $this->questionnaire_success($Message);

        }
    }

    public function questionnaire_save($id_template="",$id_user="")
    {

        $templateid = $this->input->post('templateid');
        $accountid = $this->input->post('accountid');
        $smartquestionnaireid = $this->input->post('smartquestionnaireid');

        if(is_array($this->input->post('choiceid'))) {

            $k = 0 ;
            foreach ($this->input->post('choiceid') as $elements => $value_elements) {
                $ch1 = $value_elements."[]";
                $ch_show1 = explode(",", $ch1);
                $text = "text".$ch_show1[0];

                $ch = "choice".$value_elements."[]";
                $ch_show = explode(",", $ch);
                //echo "<pre>";print_r($ch_show); echo "</pre>";
                $ch2 = $value_elements."[]";
                $ch_show2 = explode(",", $ch2);

                $array[$k] =
                    array(
                        'templateid' => $ch_show[4],
                        'accountid' => $ch_show[5],
                        'smartquestionnaireid' => $smartquestionnaireid,
                        'choiceid' => $ch_show2[0],
                        'type' => $ch_show[1],
                        'choice' => $this->input->post($ch_show[0]),
                        'otherText' => $this->input->post($text),
                        //'choicedetailid' => $ch_show[6]

                    );
                if($ch_show[1] == 'text'){
                    $array[$k]['choicedetailid'] = $ch_show[6];
                }
                $k++;
            }

        }

        $json_url = $this->siteURL.'WB_Service_AI/questionnaire/insert_questionnaire_web';
        $data_save = array(
            'AI-API-KEY'=>"1234",
            'module' => "Questionnaire",
            'action' => "add",
            'templateid' => $templateid,
            'accountid' => $accountid,
            'smartquestionnaireid' => $smartquestionnaireid,
            'userid' => $accountid,
            'data' => $array,
        );

        $result = $this->postApi($json_url,$data_save,array(),"json");

        $url = $this->siteURL.'WB_Service_AI/special/smartquestionnaire/getData';
        $params = [
            'smartquestionnaireid' => $smartquestionnaireid
        ];
        $result = $this->postApi($url, $params);
        $point = $result['cf_2098'];

        $url = $this->siteURL.'WB_Service_AI/special/point/insertPoint';
        $params = [
            'point_name' => $result['smartquestionnaire_name'],
            'cf_1409' => 'Campaign',
            'cf_1404' => 'Add',
            'cf_1408' => $point,
            'cf_1414' => $point,
            'cf_1488' => $point,
            'cf_1411' => date('Y-m-d'),
            'parent_id' => $accountid,
            'smcreatorid' => '1',
            'smownerid' => '1',
            'modifiedby' => '1'
        ];
        $result = $this->postApi($url, $params);
        // print_r($result); exit();

        $url = $this->siteURL.'WB_Service_AI/point/addjust';
        $params = [
            'action' => 'add',
            'brand' => '',
            'channel' => 'CollectCard',
            'point' => $point,
            'accountid' => $accountid,
            'type' => '',
            'pointid' => $result['crmid'],
        ];
        $result = $this->postApi($url, $params);
        // print_r($result); exit();

        $data['crmid'] = $this->input->post('crmid');
        $data['accountid'] = $this->input->post('accountid');
        //$data['data_json'] = json_encode($array);
        $data['message'] = "บันทึกข้อมูลสำเร็จ";

        $this->load->view('header');
        $this->load->view('questionnaire_thank',$data);
        $this->load->view('footer');

    }

    public function postApi( $url, $param=[] ){
        $param['AI-API-KEY'] = '1234';
        $fields_string = json_encode($param);
        $json_url = $url;

        $json_string = $fields_string;
        $ch = curl_init( $json_url );
        $options = array(
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
            CURLOPT_POSTFIELDS => $json_string,
            CURLOPT_BUFFERSIZE => 1024,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            //CURLOPT_DNS_USE_GLOBAL_CACHE => false,
            CURLOPT_DNS_CACHE_TIMEOUT => 30
        );
        //curl_setopt_array( $ch, $options );
        curl_setopt_array( $ch, $options );
        $result =  curl_exec($ch);
        $return = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result), true );
        return $return;
    }

    public function questionnaire_success($Message="")
    {
        $data['message'] = $Message;
        $this->load->view('header');
        $this->load->view('questionnaire_thank',$data);
        $this->load->view('footer');
    }




}
