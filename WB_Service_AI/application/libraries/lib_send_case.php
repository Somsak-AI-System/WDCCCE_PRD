<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lib_send_case
{
    function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->database();
        //$this->ci->load->library('email');
        //$this->ci->config->load('email');
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

    public function send_broadcast_case($method='',$params=[]){
        foreach ($params as $key => $value) {
            
            if($value['customerno'] != ''){

                $return = $this->SendMessageLine($value);
                if($return == true){
                    $sql_update = '';
                    $this->ci->db->query($sql_update);
                }
            }

        }
        return true;
    }

    public function SendMessageLine($data="",$userid="1")
    {
        if($data==""){
            return false;
        }
        $url_new = $this->ci->config->item('url_new');

        $access_token = 'ub5SR9vzRhqbh6Gvf5vaZ7RlZoBfcjvvi7+CW2SfC0zh6RJL35FpCEyGCHd0ww3qDO7VqhnLphe0eax87yCSJPKJ3vPCNjPmpqF4Ptfi2Yw+s6TMw/DAW9uiFkeOu+AMqbzy0lf1oLRvJ1OkqFQTr1GUYhWQfeY8sLGRXgo3xvw=';

        $message_type = 'text';
        $userId = @$data['socialid'];
        
        $text = 'เรียน ลูกค้าคนสำคัญของกรีนวิง<br> ตามสัญญาเลขที่ ' .@$data['contract_number']. ' ท่านมียอดที่ต้องชำระจำนวน ' .number_format(@$data['installment_payment'],2). ' บาท ซึ่งครบกำหนดวันที่ ' .$this->convert_date(@$data['duedate']). ' ขอบพระคุณที่ชำระค่างวดตรงกำหนด และขออภัยหากท่านชำระแล้ว';
        if($message_type=="text"){
            $messages = [
            'type' => $message_type,
            'text' => 'เรียน ลูกค้าคนสำคัญของกรีนวิง<br> ตามสัญญาเลขที่ ' .@$data['contract_number']. ' ท่านมียอดที่ต้องชำระจำนวน ' .number_format(@$data['installment_payment'],2). ' บาท ซึ่งครบกำหนดวันที่ ' .$this->convert_date(@$data['duedate']). ' ขอบพระคุณที่ชำระค่างวดตรงกำหนด และขออภัยหากท่านชำระแล้ว',
            ];

        }
        
        if($data['customerno'] != "" ){

            $sql_chat = "SELECT chatno from message_chathistory WHERE customerid='".$data['customerid']."' order by chatno desc limit 0,1";
            
            $query_chat = $this->ci->db->query($sql_chat);
            $data_chat = $query_chat->result_array();
            $chatno = $data_chat[0]['chatno'];
            $nextchatno = $chatno+1;

            $data_chat = array(  
                'customerid ' => $data['customerid'],  
                'chatno'  => $nextchatno,  
                'customerno'   => $data['customerno'],  
                'chatactionname' => 'Agent',
                'message' => $text,
                'messagetime'=> date('Y-m-d H:i:s'),
                'isagent'=> '2',
                'channel'=> $data['channel'],
                'messageaction'=>'agent',
                'flag_read'=>'1',
                'userid'=>$userid,
                'message_type'=>$message_type,
            );
            $this->ci->db->insert('message_chathistory',$data_chat);
            
            $url = 'https://api.line.me/v2/bot/message/push';
            $data = [
                'to' => $data['customerno'],
                'messages' => [$messages],
            ];

            $post = json_encode($data);
            $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($ch);
            $this->ci->common->set_log(" Send Message Broadcast",$data_chat, $result);
            curl_close($ch);
            return true;
        }else{
            return false;
        }
    }

    public function convert_date($date=''){
        $strYear = date("Y",strtotime($date))+543; 
        $strMonth= date("n",strtotime($date));
        $strDay= date("j",strtotime($date));
        $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        $strMonthThai=$strMonthCut[$strMonth];
        return $strDay." ".$strMonthThai." ".$strYear;
    }
  
}
