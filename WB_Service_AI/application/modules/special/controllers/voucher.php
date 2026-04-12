<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Voucher extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        echo 'special index';
    }

    public function formCreate(){
        $post = $this->input->post();
        $promotionID = $post['promotionID'];
        $userID = $post['userID'];

        $this->db->select('*')->from('aicrm_promotion');
        $this->db->join('aicrm_promotioncf', 'aicrm_promotioncf.promotionid = aicrm_promotion.promotionid', 'inner');
        $this->db->where('aicrm_promotion.promotionid', $promotionID);
        $sql = $this->db->get();

        $result = $sql->row_array();
        $result['userID'] = $userID;
        $this->load->view('add-voucher', $result);
    }

    public function saveTemp(){
        $post = $this->input->post();
        $this->db->join('aicrm_promotioncf', 'aicrm_promotioncf.promotionid = aicrm_promotion.promotionid');
        $sql = $this->db->get_where('aicrm_promotion', ['aicrm_promotion.promotionid'=>$post['promotionID']]);
        $promotionData = $sql->row_array();

        $insertData = [
            'promotionid' => $post['promotionID'],
            'promotion_name' => $post['promotion_name'],
            'voucher_amount' => $post['voucher_amount'],
            'startdate' => $post['startdate'],
            'enddate' => $post['enddate'],
            'voucher_price' => $post['voucher_price'],
            'voucher_remark' => $post['voucher_remark'],
            'autogen_status' => 'Pending',
            'created_by' => $post['userID'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('tbt_promotion_voucher', $insertData);
      
        $voucher_total = $promotionData['voucher_total'] == '' ? 0:$promotionData['voucher_total'];
        $voucher_total = $voucher_total + $post['voucher_amount'];

        $voucher_balance = $promotionData['voucher_balance'] == '' ? 0:$promotionData['voucher_balance'];
        $voucher_balance = $voucher_balance + $post['voucher_amount'];
        
        $this->db->update('aicrm_promotion', [
            'vouchercreate_status' => 'Pending',
            'voucher_total' => $voucher_total,
            'voucher_balance' => $voucher_balance
        ],['promotionid'=>$post['promotionID']]);

        echo json_encode(['status'=>'success']);
    }

    public function autoGenVoucher(){
        $sql = $this->db->get_where('tbt_promotion_voucher', ['autogen_status'=>'Pending']);
        $temps = $sql->result_array();
        
        $datetime = date('Y-m-d H:i:s');
        $this->load->library('crmentity');
        foreach($temps as $temp){
            for($i=1; $i<=$temp['voucher_amount']; $i++){
                $voucherno = $this->generateRandomString(15);

                $data = [[
                    // 'voucherid' => $crmid,
                    'voucher_no' => $voucherno,
                    'voucher_name' => $temp['promotion_name'],
                    'promotionid' => $temp['promotionid'],
                    'startdate' => $temp['startdate'],
                    'enddate' => $temp['enddate'],
                    'value' => $temp['voucher_price'],
                    'voucher_status' => 'สร้าง',
                    'vouchermessage' => $temp['voucher_remark'],
                    'smcreatorid' => $temp['created_by'],
                    'smownerid' => $temp['created_by'],
                    'modifiedby' => $temp['created_by']
                ]];

                $tabName = ['aicrm_crmentity', 'aicrm_voucher', 'aicrm_vouchercf'];
                $tabIndex = ['aicrm_crmentity' => 'crmid', 'aicrm_voucher' => 'voucherid', 'aicrm_vouchercf' => 'voucherid'];
                list($chk, $crmid, $DocNo) = $this->crmentity->Insert_Update('Voucher', $crmid, 'add', $tabName, $tabIndex, $data);

                $this->db->update('aicrm_voucher', ['voucher_no'=>$voucherno], ['voucherid'=>$crmid]);
                //alert($crmid);
            }
            $this->db->update('tbt_promotion_voucher', ['autogen_status'=>'Complete', 'updated_at' => date('Y-m-d H:i:s')],['id'=>$temp['id']]);
            $this->db->update('aicrm_promotion', ['vouchercreate_status'=>'Complete'],['promotionid'=>$temp['promotionid']]);
        }
        
        echo 'Auto Generate Voucher Finished';
    }

    public function generateRandomString($length = 10) {
        $characters = date('YmdHis').'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function get_autorun($prefix, $module, $lenght){
        $sql = $this->db->get_where('ai_running_doc', ['module'=>$module, 'prefix'=>$prefix]);
        $rows = $sql->row_array();
        $count = $sql->num_rows();
       
        if($count>0){
            $running = $rows['running'];
            $running = $running+1;
            $this->db->update('ai_running_doc', ['running'=>$running], ['module'=>$module, 'prefix'=>$prefix]);     
        }else{
            $running = 1;
            $this->db->insert('ai_running_doc', ['module'=>$module, 'prefix'=>$prefix, 'running'=>$running]);
        }
       
        $cd = $prefix."-".str_pad($running, $lenght, "0", STR_PAD_LEFT);
        return $cd;
    }
}