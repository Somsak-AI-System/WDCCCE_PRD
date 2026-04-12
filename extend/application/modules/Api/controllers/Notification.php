<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->params = [
            'UserID' => $this->session->userdata('userid'),
            'ComputerName' => COMPUTER_NAME,
            'Language' => $this->session->userdata('lang')
        ];
        $this->module = 'Notification';
        $this->curl->_filename = $this->module;
    }

    public function GetNotification(){
        $this->params['TabID'] = '';
        //echo json_encode($this->params); exit;
        $result = $this->api_cms->serviceMaster('GetNotification', $this->module, $this->params);

        alertJson($result['alldata']);
    }

    public function GetNotificationByUser(){
        $this->params['TabID'] = '';
        //echo json_encode($this->params); exit;
        $result = $this->api_cms->serviceMaster('GetNotificationByUser', $this->module, $this->params);

        alertJson($result['alldata']);
    }

    public function UpdateNotification(){
        $post = $this->input->post(); //alert($post); exit;
        $response = [];
        if(isset($post['noti_id'])){
            $this->params['NotificationIDList'] = $post['noti_id'];
            $this->params['Status'] = $post['status'];
            //echo json_encode($this->params); exit;
            $result = $this->api_cms->serviceMaster('UpdateNotification', $this->module, $this->params);
            $response = $result['alldata'];
        }
        alertJson($response);
    }
}