<?php

class email_model extends CI_Model
{

    function __construct()
    {
        $this->CI = get_instance();
    }

    function getUserEmailId($crmid = null, $module = "")
    {

        if ($module == "Job") {

            $sql = "SELECT DISTINCT aicrm_crmentity.smownerid
            from aicrm_jobs
            inner join aicrm_jobscf on aicrm_jobscf.jobid = aicrm_jobs.jobid
            inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_jobs.jobid
            where aicrm_crmentity.deleted = 0 and aicrm_jobs.jobid = '" . $crmid . "' ";
        } elseif ($module == "Quotes") {
            $sql = "SELECT DISTINCT aicrm_crmentity.smownerid
            from aicrm_quotes
            inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_quotes.quoteid
            where aicrm_crmentity.deleted = 0 and aicrm_quotes.quoteid = '" . $crmid . "' ";
        } elseif ($module == "Inspection") {
            $sql = "SELECT DISTINCT aicrm_crmentity.smownerid
            from aicrm_inspection
            inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_inspection.inspectionid
            where aicrm_crmentity.deleted = 0 and aicrm_inspection.inspectionid = '" . $crmid . "' ";
        }

        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);
        return $result;
    }

    function get_email($crmid = null)
    {
        $sql = "SELECT email1
				from aicrm_users
				where  aicrm_users.id = '" . $crmid . "' and status='Active' ";
        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);
        return $result;
    }

    function get_emailAllUser()
    {
        $sql = "SELECT DISTINCT email1
				from aicrm_users
				where  status='Active' ";
        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);
        // alert($result);
        // exit;
        return $result;
    }

    function get_emailAccount($crmid = null, $module = "")
    {
        if ($module == "Job") {

            $sql = "SELECT aicrm_account.email1 FROM aicrm_jobs
                    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_jobs.jobid
                    INNER JOIN aicrm_account ON aicrm_account.accountid=aicrm_jobs.accountid
                    WHERE 1 
                        AND aicrm_crmentity.deleted = 0 
                        AND aicrm_jobs.jobid = '" . $crmid . "' ";
        } elseif ($module == "Quotes") {

            $sql = "SELECT aicrm_account.email1 FROM aicrm_quotes
                    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_quotes.quoteid
                    INNER JOIN aicrm_account ON aicrm_account.accountid=aicrm_quotes.accountid
                    WHERE 1 
                        AND aicrm_crmentity.deleted = 0 
                        AND aicrm_quotes.quoteid = '" . $crmid . "' ";
        } elseif ($module == "Inspection") {

            $sql = "SELECT aicrm_account.email1 FROM aicrm_inspection
                    INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_inspection.inspectionid
                    INNER JOIN aicrm_jobs ON aicrm_jobs.jobid = aicrm_inspection.jobid
                    INNER JOIN aicrm_account ON aicrm_account.accountid = aicrm_jobs.accountid 
                    WHERE 1 
                        AND aicrm_crmentity.deleted = 0 
                        AND aicrm_inspection.inspectionid = '" . $crmid . "' ";
        }
        // echo $sql;
        // exit;
        $a_data = $this->db->query($sql);
        $result = $a_data->result(0);
        return $result;
    }

    function getsetype($crmid = null)
    {
        $query = "SELECT setype FROM aicrm_crmentity WHERE aicrm_crmentity.crmid ='" . $crmid . "' ";
        $result = $this->db->query($query);
        $module = $result->result_array();
        $setype = $module[0]['setype'];
        return $setype;
    }
}
