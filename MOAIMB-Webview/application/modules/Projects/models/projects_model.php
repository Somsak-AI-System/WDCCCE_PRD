<?php

class projects_model extends CI_Model
{

    function __construct()
    {
        $this->CI = get_instance();
    }

    function projectorder_status()
    {
         $sql = "select projectorder_status from aicrm_projectorder_status 
                where presence =1 and projectorder_status != '--None--' ";
        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);
        return $result;
    }
    
    function get_status($crmid = null){
        $sql = "select projectorder_status from aicrm_projects where projectsid = '".$crmid."' ;";
        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);
        return $result;
    }
    /*function get_email($crmid = null)
    {
        $sql = "SELECT email1
				from aicrm_users
				where  aicrm_users.id = '" . $crmid . "' and status='Active' ";
        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);
        return $result;
    }*/

}
