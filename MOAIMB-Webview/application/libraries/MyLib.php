<?php
defined('BASEPATH') OR exit('Access Denied');
class MyLib
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function checkLogin()
    {
        if (empty($this->CI->session->userdata('is_login')) || $this->CI->session->userdata('is_login') == false) {
            redirect(site_url('Access'));
        } else {
            return "";
        }
    }

    public function ajaxOnly()
    {
        if (!$this->CI->input->is_ajax_request()) {
            redirect(base_url());
        } else {
            return "";
        }
    }

    public function perm($menu_id){
        $filename = FILE_MENU_PATH.FILE_PREFIX_PERMISSION.USERNAME.FILE_TYPE_JSON;
        $string = file_get_contents($filename);
        $perms = json_decode($string, true);

        $perm = [];
        foreach($perms as $row){
            if($row['TabID']==$menu_id){
                $perm = $row;
            }
        }

        return $perm;
    }
}
