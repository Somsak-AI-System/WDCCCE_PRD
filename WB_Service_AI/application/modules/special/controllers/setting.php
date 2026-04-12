<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function settingUpload()
    {
        $post = $this->input->post();
        $module = $post['module'];
        $field = $post['field'];
        $status = $post['status'];

        $this->db->update('aicrm_setting_upload_channel', [$field=>$status], ['module'=>$module]);
        echo json_encode(['status'=>'Success']);
    }

    public function settingFlags()
    {
        $post = $this->input->post();
        $module = $post['module'];
        $field = $post['field'];
        $status = $post['status'];

        $this->db->update('aicrm_setting_module_flags', [$field=>$status], ['module'=>$module]);
        echo json_encode(['status'=>'Success']);
    }

    public function venderbuyer()
    {   
        $data = array();
        $post = $this->input->post();
        $id = $post['id'];
        $type = $post['type'];

        $this->db->select('*')->from('aicrm_config_vendorbuyer');
        $this->db->where([
            'aicrm_config_vendorbuyer.deleted' => 0 ,
            'aicrm_config_vendorbuyer.id' => $id ,
            'aicrm_config_vendorbuyer.type' => $type ,
        ]);
        $sql = $this->db->get();
        $result = $sql->result_array();
        //alert($result);
        $data['status'] = 'Success';
        $data['data'] = $result;

        echo json_encode($data);
    }

}