<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lib_sendmail
{


    function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library("common");
        $this->ci->load->library('crmentity');

        $this->ci->load->database();

        $this->_return = array(
            'status' => false,
            'error' => "",
            'result' => "",
        );

    }

    public function get_datauser($a_param = array())
    {
        $sql = " select * from aicrm_users 
				 where 1=1 
				 and aicrm_users.deleted=0 ";
        if (!empty($a_param["userid"])) {
            $sql .= " and aicrm_users.id in (" . $a_param["userid"] . ") ";
        }

        if (!empty($a_param["section"])) {
            $sql .= " and FIND_IN_SET (aicrm_users.section,'" . $a_param["section"] . "') ";
        }

        if (!empty($a_param["typemail"])) {
            if ($a_param["typemail"] == "weekly") {
                $sql .= " and aicrm_users.track_report = '1' ";
                $sql .= " and aicrm_users.plan_type = 'Weekly' ";
                $sql .= " and aicrm_users.status = 'Active' ";
            }

            if ($a_param["typemail"] == "monthly") {
                $sql .= " and aicrm_users.track_report = '1' ";
                $sql .= " and aicrm_users.plan_type = 'Monthly' ";
                $sql .= " and aicrm_users.status = 'Active' ";
            }

            if ($a_param["typemail"] == "daily") {
                $sql .= " and aicrm_users.track_report = '1' ";
                $sql .= " and aicrm_users.report_type = 'Daily' ";
                $sql .= " and aicrm_users.status = 'Active' ";
            }
        }
        $sql .= " order by aicrm_users.id ";
        //echo $sql;
        $query = $this->ci->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    public function get_config_weekly($a_param = array())
    {
        $sql = "select * from aicrm_weekly_plan where 1=1 ";
        if (!empty($a_param["date"])) {
            $sql .= " and date_send = '" . $a_param["date"] . "' ";
        }
        if (!empty($a_param["time"])) {
            $sql .= " and time_send = '" . $a_param["time"] . "' ";
        }
        if (!empty($a_param["section"])) {
            $sql .= " and section = '" . $a_param["section"] . "' ";
        }
        $query = $this->ci->db->query($sql);
        $data = $query->result_array();
        return $data;
    }

    public function get_config_monthly($a_param = array())
    {
        $sql = "select * from aicrm_monthly_plan
  			where 1=1 ";
        if (!empty($a_param["date"])) {
            if ($a_param["lastdateflg"] == "1") {
                $sql .= " and (date_send = '" . $a_param["date"] . "' or date_send = '99') ";
            } else {
                $sql .= " and date_send = '" . $a_param["date"] . "' ";
            }

        }
        if (!empty($a_param["time"])) {
            $sql .= " and time_send  = '" . $a_param["time"] . "' ";
        }
        if (!empty($a_param["section"])) {
            $sql .= " and section = '" . $a_param["section"] . "' ";
        }
        $query = $this->ci->db->query($sql);
        $data = $query->result_array();
        return $data;

    }

    public function get_mail_user($a_param = array())
    {
        $a_return = array();
        $a_user = $this->get_datauser($a_param);
        if (!empty($a_user)) {
            foreach ($a_user as $k => $v) {
                if (isset($v["email1"]) && $v["email1"] != "") {
                    $a_return[$k] = $v["email1"];
                } else if (isset($v["email2"]) && $v["email2"] != "") {
                    $a_return[$k] = $v["email2"];
                }

            }
        }
        return implode(",", $a_return);
    }

    public function get_user_data($a_param = array())
    {
        $a_return = array();
        $a_user = $this->get_datauser($a_param);
        if (!empty($a_user)) {
            foreach ($a_user as $k => $v) {
                $id = $v["id"];
                $a_return[$id] = $v;
            }
        }
        return $a_return;
    }

    public function get_log_monthly($userid = "", $date = "")
    {
        $date = date("Y-m-d", strtotime($date));
        $sql = " select aicrm_activity_tran_monthly_plan.*
				 from aicrm_activity_tran_monthly_plan
				 where 1=1
				 
				 and month(monthly_end_date) = month('" . $date . "') ";
        if ($userid != "") {
            $sql .= " and FIND_IN_SET( monthly_user_id,'" . $userid . "')  ";
        }
        //echo $sql;
        $query = $this->ci->db->query($sql);
        $data = $query->result_array();
        $a_return = array();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $id = $v["monthly_user_id"];
                $a_return[$id] = $v;
            }
        }
        return $a_return;
    }

    public function get_log_daily($userid = "", $date = "")
    {

        $date = date("Y-m-d", strtotime($date));
        $sql = " select aicrm_activity_tran_daily_report.*
				 from aicrm_activity_tran_daily_report 
				 where 1=1 
				 and daily_start_date <= '" . $date . "' 
				 and daily_end_date >= '" . $date . "' ";
        if ($userid != "") {
            $sql .= " and FIND_IN_SET(daily_user_id,'" . $userid . "')  ";
        }
        //echo $sql;
        $query = $this->ci->db->query($sql);
        $data = $query->result_array();
        $a_return = array();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $id = $v["daily_user_id"];
                $a_return[$id] = $v;
            }
        }
        return $a_return;
    }

    public function get_log_weekly($userid = "", $date = "")
    {
        //$date ="";
        $date = $date == "" ? date("Y-m-d") : $date;
        $date = date("Y-m-d", strtotime($date));
        $sql = " select aicrm_activity_tran_weekly_plan.*
				,DATE_FORMAT(aicrm_activity_tran_weekly_plan.weekly_send_date,'%Y-%m-%d') as log_send_date
				,DATE_FORMAT(aicrm_activity_tran_weekly_plan.weekly_send_date,'%H:%i') as log_send_time
				 from aicrm_activity_tran_weekly_plan 
				 where 1=1 
				 and weekly_start_date <= '" . $date . "' 
				 and weekly_end_date >= '" . $date . "' ";
        if ($userid != "") {
            $sql .= " and FIND_IN_SET(weekly_user_id,'" . $userid . "')  ";
        }
        $query = $this->ci->db->query($sql);
        $data = $query->result_array();
        $a_return = array();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $id = $v["weekly_user_id"];
                $a_return[$id] = $v;
            }
        }
        return $a_return;
    }

    public function compare_date($date_begin = "", $date_end = "")
    {
        $diff = abs(strtotime($date_end) - strtotime($date_begin));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minuts = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minuts * 60));

        $date_string = "";
        $time_string = "";

        $date_string .= $years != "" ? $years . " ปี" : "";
        $date_string .= $months != "" ? " " . $months . " เดือน" : "";
        $date_string .= $days != "" ? " " . $days . " วัน" : "";
        $time_string .= $hours != "" ? $hours . " ชม." : "";
        $time_string .= $minuts != "" ? " " . $minuts . " นาที" : "";
        return array(trim($date_string), trim($time_string));

    }

    public function set_format_result($a_data = array())
    {
        $a_result["no"] = "";
        $a_result["id"] = isset($a_data["id"]) && $a_data["id"] != "" ? $a_data["id"] : "";
        $a_result["user_name"] = isset($a_data["user_name"]) && $a_data["user_name"] != "" ? $a_data["user_name"] : "";
        $a_result["name"] = @$a_data["first_name"] . " " . @$a_data["last_name"];
        $a_result["email"] = isset($a_data["email1"]) && $a_data["email1"] != "" ? $a_data["email1"] : @$a_data["email2"];
        $a_result["phone"] = isset($a_data["phone_mobile"]) && $a_data["phone_mobile"] != "" ? $a_data["phone_mobile"] : @$a_data["phone_home"];
        $a_result["senddate"] = "";
        $a_result["count_senddate"] = "";
        $a_result["count_sendtime"] = "";
        return $a_result;
    }

    public function getdata_weekly($a_param = array())
    {
        $this->ci->common->_filename = "Sendmail_weeklyplan";
        $date_send_time = $a_param["currentdate"];
        $a_return["total"] = 0;

        $a_return["result"]["ontime"]["data"] = array();
        $a_return["result"]["notsend"]["data"] = array();

        $a_return["result"]["ontime"]["title"] = "รายละเอียดผู้แทนขายที่ส่งรายงาน Weekly Plan ตามเวลา (ทุก" . convert_datetime($a_param["date_send"]) . " ก่อนเวลา " . date("H:i", strtotime($date_send_time)) . ")";
        $a_return["result"]["late"]["title"] = "รายละเอียดผู้แทนขายที่ส่งรายงาน Weekly Plan ไม่ตรงเวลา (ทุก" . convert_datetime($a_param["date_send"]) . " ก่อนเวลา " . date("H:i", strtotime($date_send_time)) . ")";
        $a_return["result"]["notsend"]["title"] = "รายละเอียดผู้แทนขายที่ไม่ส่งรายงาน Weekly Plan(ทุก" . convert_datetime($a_param["date_send"]) . " ก่อนเวลา " . date("H:i", strtotime($date_send_time)) . ")";

        if (empty($a_param)) {
            return $a_return;
        }

        // Get User Data in section
        $a_param_usersend = array();
        $a_param_usersend["section"] = $a_param["section"];
        $a_param_usersend["typemail"] = "weekly";
        $a_user = $this->get_user_data($a_param_usersend);

        $this->ci->common->set_log(" Get User Data", "", $a_user);
        
        if (empty($a_user)) {
            return $a_return;
        }
        $a_userid = array_keys($a_user);
        $userid = implode(",", $a_userid);

        $a_data = $this->get_log_weekly($userid, $date_send_time);
        $this->ci->common->set_log(" Get Log Data", "", $a_data);
        if (empty($a_data)) {
            return $a_return;
        }
        $a_return["total"] = count($a_data);

        $x = 0;
        $y = 0;
        $z = 0;

        foreach ($a_user as $k => $v) {
            $a_result = array();
            $userid = $k;
            $a_data_log = @$a_data[$userid];
            $a_user_data = $a_user[$userid];
            $a_result = $this->set_format_result($a_user_data);
            //alert($a_data_log);
            $a_result["senddate"] = @$a_data_log["weekly_send_date"];

            if(!empty($a_data_log)){
                $a_return["result"]["log"]["start_date"] = @$a_data_log["weekly_start_date"];
                    $a_return["result"]["log"]["end_date"] = @$a_data_log["weekly_end_date"];
               }

            if (isset($a_data_log["weekly_check_send"]) && $a_data_log["weekly_check_send"] == "1") {
                $date_diff = strtotime($date_send_time) - strtotime($a_data_log["weekly_send_date"]);
                if ($date_diff > 0) {
                    //on time
                    $x += 1;
                    $a_result["no"] = $x;
                    list($date, $time) = $this->compare_date($a_data_log["weekly_send_date"], $date_send_time);
                    $a_result["count_senddate"] = $date;
                    $a_result["count_sendtime"] = $time;
                    $a_return["result"]["ontime"]["data"][] = $a_result;
                } else {
                    //late
                    $y += 1;
                    $a_result["no"] = $y;
                    list($date, $time) = $this->compare_date($date_send_time, $a_data_log["weekly_send_date"]);
                    $a_result["count_senddate"] = $date;
                    $a_result["count_sendtime"] = $time;
                    $a_return["result"]["late"]["data"][] = $a_result;
                }
            } else {
                $z += 1;
                $a_result["no"] = $z;
                $a_return["result"]["notsend"]["data"][] = $a_result;
            }
        }
        $this->ci->common->set_log(" Get response data", "", $a_return);
        return $a_return;
    }

    public function getdata_daily($a_param = array())
    {
        $this->ci->common->_filename = "Sendmail_weeklyplan";

        $a_return["total"] = 0;
        $date_send_time = date("Y-m-d H:i", strtotime($a_param["currentdate"]));
        $a_return["result"]["ontime"]["data"] = array();
        $a_return["result"]["late"]["data"] = array();
        $a_return["result"]["notsend"]["data"] = array();

        $a_return["result"]["ontime"]["title"] = "รายละเอียดผู้แทนขายที่ส่งรายงาน Weekly Report ตามเวลา (ทุก" . convert_datetime($a_param["date_send"]) . " ก่อนเวลา " . date("H:i", strtotime($date_send_time)) . ")";
     $a_return["result"]["late"]["title"] = "รายละเอียดผู้แทนขายที่ส่งรายงาน Weekly Report ไม่ตรงเวลา (ทุก" . convert_datetime($a_param["date_send"]) . " ก่อนเวลา " . date("H:i", strtotime($date_send_time)) . ")";
        $a_return["result"]["notsend"]["title"] = "รายละเอียดผู้แทนขายที่ไม่ส่งรายงาน Weekly Report(ทุก" . convert_datetime($a_param["date_send"]) . " ก่อนเวลา " . date("H:i", strtotime($date_send_time)) . ")";

        if (empty($a_param)) {
            return $a_return;
        }

        // Get User Data in section
        $a_param_usersend = array();
        $a_param_usersend["section"] = $a_param["section"];
        $a_param_usersend["typemail"] = "weekly";
        $a_user = $this->get_user_data($a_param_usersend);
        $this->ci->common->set_log(" Get User Data", "", $a_user);

        if (empty($a_user)) {
            return $a_return;
        }
        $a_userid = array_keys($a_user);
        $userid = implode(",", $a_userid);

        //$date_send_time_get_log = date("Y-m-d H:i", strtotime("-7 days", strtotime($a_param["currentdate"])));
        $date_send_time_get_log = date("Y-m-d", strtotime("-7 days", strtotime($date_send_time)));
        $a_data = $this->get_log_daily($userid, $date_send_time_get_log);

        $this->ci->common->set_log(" Get Log Data", "", $a_data);
        if (empty($a_data)) {
            return $a_return;
        }

        $a_return["total"] = count($a_data);
        $x = 0;
        $y = 0;
        $z = 0;
        foreach ($a_user as $k => $v) {
            $a_result = array();
            $userid = $k;
            $a_data_log = @$a_data[$userid];
            $a_user_data = $a_user[$userid];
            $a_result = $this->set_format_result($a_user_data);
            $a_result["senddate"] = @$a_data_log["daily_send_date"];
            if(!empty($a_data_log)){ 
            $a_return["result"]["log"]["start_date"] = @$a_data_log["daily_start_date"];
            $a_return["result"]["log"]["end_date"] = @$a_data_log["daily_end_date"];
            }

            if (isset($a_data_log["daily_check_send"]) && $a_data_log["daily_check_send"] == "1") {
                $date_diff = strtotime($date_send_time) - strtotime($a_data_log["daily_send_date"]);
                if ($date_diff > 0) {
                    //on time
                    $x += 1;
                    $a_result["no"] = $x;
                    list($date, $time) = $this->compare_date($a_data_log["daily_send_date"], $date_send_time);
                    $a_result["count_senddate"] = $date;
                    $a_result["count_sendtime"] = $time;
                    $a_return["result"]["ontime"]["data"][] = $a_result;
                } else {
                    //late
                    $y += 1;
                    $a_result["no"] = $y;
                    list($date, $time) = $this->compare_date($date_send_time, $a_data_log["daily_send_date"]);
                    $a_result["count_senddate"] = $date;
                    $a_result["count_sendtime"] = $time;
                   $a_return["result"]["late"]["data"][] = $a_result;
                }
            } else {
                $z += 1;
                $a_result["no"] = $z;
                $a_return["result"]["notsend"]["data"][] = $a_result;
            }
        }
        $this->ci->common->set_log(" Get response data", "", $a_return);
        return $a_return;
    }


    public function getdata_monthly($a_param = array())
    {
        $this->ci->common->_filename = "Sendmail_monthlyplan";
        $date_send_time = $a_param["currentdate"];
        $a_return["total"] = 0;

        $a_return["result"]["ontime"]["data"] = array();
       $a_return["result"]["late"]["data"] = array();
        $a_return["result"]["notsend"]["data"] = array();

        $send_date = $a_param["date_send"] == "99" ? "วันสิ้นเดือน" : "วันที่ " . $a_param["date_send"] . " ของเดือน";
        $a_return["result"]["ontime"]["title"] = "รายละเอียดผู้แทนขายที่ส่งรายงาน Monthly Plan ตามเวลา (ทุก" . $send_date . " ก่อนเวลา " . date("H:i", strtotime($date_send_time)) . ")";
       $a_return["result"]["late"]["title"] = "รายละเอียดผู้แทนขายที่ส่งรายงาน Monthly Plan ไม่ตรงเวลา (ทุก" . $send_date . " ก่อนเวลา " . date("H:i", strtotime($date_send_time)) . ")";
        $a_return["result"]["notsend"]["title"] = "รายละเอียดผู้แทนขายที่ส่งรายงาน Monthly Plan ไม่ส่ง(ทุก" . $send_date . " ก่อนเวลา " . date("H:i", strtotime($date_send_time)) . ")";


        if (empty($a_param)) {
            return $a_return;
        }

        // Get User Data in section
        $a_param_usersend = array();
        $a_param_usersend["section"] = $a_param["section"];
        $a_param_usersend["typemail"] = "monthly";
        $a_user = $this->get_user_data($a_param_usersend);
        $this->ci->common->set_log(" Get User Data", "", $a_user);
        if (empty($a_user)) {
            return $a_return;
        }
        $a_userid = array_keys($a_user);
        $userid = implode(",", $a_userid);


        $a_data = $this->get_log_monthly($userid, $date_send_time);
        $this->ci->common->set_log(" Get Log Data", "", $a_data);
        if (empty($a_data)) {
            return $a_return;
        }
        $a_return["total"] = count($a_data);

        $x = 0;
        $y = 0;
        $z = 0;
        foreach ($a_user as $k => $v) {
            $a_result = array();
            $userid = $k;
            $a_data_log = @$a_data[$userid];
            $a_user_data = $a_user[$userid];
            $a_result = $this->set_format_result($a_user_data);
            $a_result["senddate"] = @$a_data_log["monthly_send_date"];
            $a_return["result"]["log"]["start_date"] = @$a_data_log["monthly_start_date"];
            $a_return["result"]["log"]["end_date"] = @$a_data_log["monthly_end_date"];
            if (isset($a_data_log["monthly_check_send"]) && $a_data_log["monthly_check_send"] == "1") {
                $date_diff = strtotime($date_send_time) - strtotime($a_data_log["monthly_send_date"]);
                if ($date_diff > 0) {
                    //on time
                    $x += 1;
                    $a_result["no"] = $x;
                    list($date, $time) = $this->compare_date($a_data_log["monthly_send_date"], $date_send_time);
                    $a_result["count_senddate"] = $date;
                    $a_result["count_sendtime"] = $time;
                    $a_return["result"]["ontime"]["data"][] = $a_result;
                } else {
                    //late
                    $y += 1;
                    $a_result["no"] = $y;
                    list($date, $time) = $this->compare_date($date_send_time, $a_data_log["monthly_send_date"]);
                    $a_result["count_senddate"] = $date;
                    $a_result["count_sendtime"] = $time;
                   $a_return["result"]["late"]["data"][] = $a_result;
                }
            } else {
                $z += 1;
                $a_result["no"] = $z;
                $a_return["result"]["notsend"]["data"][] = $a_result;
            }
        }
        $this->ci->common->set_log(" Get response data", "", $a_return);
        return $a_return;
    }
}
