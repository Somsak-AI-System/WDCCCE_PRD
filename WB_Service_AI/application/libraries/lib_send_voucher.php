<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lib_send_voucher
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

    public function send_broadcast_voucher($method='',$params=[]){
        foreach ($params as $key => $value) {
            
            if($value['customerno'] != ''){

                $return = $this->SendMessageLine($value);
                if($return == true){
                    $sql_update = 'Update aicrm_voucher
                    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_voucher.voucherid 
                    set aicrm_voucher.voucher_status = "ส่งแล้ว" , aicrm_crmentity.modifiedtime = "'.date('Y-m-d h:i:s').'"
                    where aicrm_voucher.voucherid = "'.$value['voucherid'].'" ';
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
        //$message_type = @$data['message_type'];
        $message_type = 'text';
        $userId = @$data['socialid'];
        
        $text = @$data['vouchermessage'].' กดลิ้งเพื่อแสดง Qrcode
'.$url_new.'qrcode.php?gen='.$data['voucher_no'];
        if($message_type=="text"){
            $messages = [
            'type' => $message_type,
            'text' => @$data['vouchermessage'].' กดลิ้งเพื่อแสดง Qrcode
'.$url_new.'qrcode.php?gen='.$data['voucher_no']
            ];

        }/*elseif($message_type=="sticker"){

            $messages = [
            'type' => $message_type, 
            'packageId' => @$data['packageId'], // เลขชุดของสติ๊กเกอร์
            'stickerId' => @$data['stickerId']  // เลขรหัสของสติ๊กเกอร์
            ];
            $text = 'https://stickershop.line-scdn.net/stickershop/v1/sticker/'.@$data['stickerId'].'/android/sticker.png';

        }elseif($message_type=="image"){

            $messages = [
            'type' => $message_type, 
            'originalContentUrl' => @$data['message'], // URL ของรูปภาพที่จะแสดงเมื่อผู้ใช้คลิกรูปพรีวิว ทั้งหมดจะต้องเป็น HTTPS เท่านั้น
            'previewImageUrl' => @$data['message']  // URL ของรูปภาพพรีวิว ทั้งหมดจะต้องเป็น HTTPS เท่านั้น
            ];
            
        }elseif($message_type=="video"){

            $messages = [
            'type' => $message_type, 
            'originalContentUrl' => @$data['originalContentUrl'], // URL ของวิดีโอที่มีนามสกุลเป็น MP4
            'previewImageUrl' => @$data['previewImageUrl'],  // URL ของรูปภาพพรีวิว
            'trackingId' => @$data['trackingId']  
            ];

        }elseif($message_type=="audio"){

            $messages = [
            'type' => $message_type, 
            'originalContentUrl' => @$data['originalContentUrl'], // URL ของไฟล์เสียง เช่น mp3 และ m4a โดยมีความยาวไม่เกิน 1 นาที และขนาดไม่เกิน 10MB
            'duration' => @$data['duration']  // ความยาวของไฟล์เสียง หน่วยเป็น milliseconds
            ];
            
        }elseif($message_type=="file"){

            $ms = [
                "type" => "button",
                "style" => "link",
                "action" => [
                    "type" => "uri",
                    "label" => "ไฟล์แนบ (download)",
                    "uri" => @$data['message']
                ]
            ];
            $contents = [
                "type"=> "bubble",
                "body"=> [
                    "type"=> "box",
                    "layout"=> "vertical",
                    "spacing"=> "md",
                    "contents"=> [
                            0 => $ms
                        ]
                    ]
            ];


            $messages = [
            'type' => 'flex',
            "altText"=> "new message",
            'contents' => $contents  
            ];    
        }*/

        if($data['customerno'] != "" ){

            $sql_chat = "SELECT chatno from message_chathistory WHERE customerid='".$data['customerid']."' order by chatno desc limit 0,1";
            
            $query_chat = $this->ci->db->query($sql_chat);
            $data_chat = $query_chat->result_array();
            $chatno = $data_chat[0]['chatno'];
            $nextchatno = $chatno+1;
            //alert($data_chat); exit;
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

  
}
