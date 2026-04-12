<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_cms
{
    public $current_get_api = array();
    public $current_post_api = array();
    public $current_post_params = array();
    public $debug = FALSE;

    public $func = array(
        "getFilter" => "GetFilter",
        "getTableData" => "GetInquiryData",
        "getTableHeader" => "GetInquiryHeader",
        "getBlockFields" => "GetBlockFields",
        "getPickListData" => "GetPickListData",
        "saveData" => "SaveData",
        "deleteData" => "DeleteData",
    );

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->config('api');
        $this->CI->load->library('managecached_library');
    }

    public function get_cache($a_params = array(), $url = null, $method = null, $module = null)
    {
        $a_cache = array();
        $a_cache["_ctag"] = $module . '/';
        $a_cache["_ckname"] = $module . '/' . $method;

        if (!empty($a_params)) {
            $a_cache["_ckname"] .= '_' . str_replace("&", "-", rawurldecode(http_build_query($a_params)));
        }

        $a_data = $this->CI->managecached_library->get_memcache($a_cache);

        if ($a_data === false) {

            $url = $url . $method;
            /*if($method == 'quotes/get_product_list'){
                echo $url;
                echo json_encode($a_params); exit;
            }*/
            //echo $url;
            //echo json_encode($a_params); exit;
            $response = $this->CI->curl->simple_post($url, $a_params, array(), "json");
            // echo $response; exit();
            $a_data = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response), true ); // json_decode($response, true);
            // alert($a_data); exit();
            $a_cache["data"] = $a_data;
            $a_cache["data"]["time"] = date("Y-m-d H:i:s");
            if ($module == "block" || $module == "field") {
                $this->CI->managecached_library->set_memcache($a_cache);
            }

        } else {
            if (isset($_GET['debug']) && isset($_GET['passcode']) && $_GET['passcode'] == PASSCODE) {
                echo "<code>" . ($url . $method . '?' . rawurldecode(http_build_query($a_params))) . "</code><br/>\n\n";
            }
        }
        return $a_data;
    }

    function serviceMaster($method = NULL, $module = NULL, $params = array(), $debug = FALSE){
        $this->debug = $debug;
        if (!$method) return 'Method require';

        $param['CallTimeStamp'] = date('YmdHis');

        $url = config_item('service_master_url');
        $path = config_item('service_master_path');
        return $this->post_api($url . $path, $method, $module, $params);
    }

    function serviceBusiness($method = NULL, $module = NULL, $params = array(), $debug = FALSE){
        $this->debug = $debug;
        if (!$method) return 'Method require';

        $param['CallTimeStamp'] = date('YmdHis');

        $url = config_item('service_business_url');
        $path = config_item('service_business_path');
        return $this->post_api($url . $path, $method, $module, $params);
    }

    function get_content_email($method = NULL, $module = NULL, $params = array(), $debug = FALSE)
    {
        $this->debug = $debug;
        if (!$method) return 'Method require';
        $url = config_item('service_email_url');
        $path = config_item('service_email_path');
        return $this->post_api($url . $path, $method, $module, $params);
    }

    function get_server_name()
    {
        $s_hostname = $this->CI->session->userdata('user.hostname');
        if ($s_hostname != "") {
            return $s_hostname;
        } else {
            $a_data['user.hostname'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $this->CI->session->set_userdata($a_data);
            return $a_data['user.hostname'];
        }


    }

    /**
     * Api Post Api
     * Call api from get
     * $method = method call
     * $param = option
     */
    public function post_api($url = NULL, $method = null, $module = NULL, $params = array())
    {
        if (!$url) return 'Method require';

        $load_page = (isset($params['load_page'])) ? $params['load_page'] : NULL;
        unset($params['load_page']);
        // end set for fix load page more

//        if (APIREFRESH) {
//            $params['time'] = rand(1000, 9999);
//        }

        $a_data = $this->get_cache($params, $url, $method, $module);
        //alert($a_data);

        $data['alldata'] = @$a_data;
        $data["result"] = @$a_data["ResultList"];
        $data["total"] = @$a_data["RowCount"];
        $data["msg"] = @$a_data["ErrorMessage"];
        $data["status"] = isset($a_data["IsError"])&&$a_data["IsError"] ? false : true;
        return $data;
    }

    function set_single_signon($method = NULL, $module = NULL, $params = array(), $debug = FALSE)
    {
        $this->debug = $debug;
        if (!$method) return 'Method require';
        $url = 'http://localhost/ai-meao-callcenter/login/';
        $path = '';
        return $this->set_post_api($url . $path, $method, $module, $params);
    }

    function set_content_master($method = NULL, $module = NULL, $params = array(), $debug = FALSE)
    {
        $this->debug = $debug;
        if (!$method) return 'Method require';
        $url = config_item('service_master_url');
        $path = config_item('service_master_path');
        return $this->set_post_api($url . $path, $method, $module, $params);
    }

    function set_content_production($method = NULL, $module = NULL, $params = array(), $debug = FALSE)
    {
        $this->debug = $debug;
        if (!$method) return 'Method require';
        $url = config_item('service_production_url');
        $path = config_item('service_production_path');
        return $this->set_post_api($url . $path, $method, $module, $params);
    }

    public function set_post_api($url = NULL, $method = null, $module = NULL, $params = array())
    {
        if (!$url) return 'Url require';
        if (!$method) return 'Method require';

        $load_page = (isset($params['load_page'])) ? $params['load_page'] : NULL;
        unset($params['load_page']);


        if (APIREFRESH) {
            $params['time'] = rand(1000, 9999);
        }
        //echo $url.$method;
        //alert($params);
        $response = $this->CI->curl->simple_post($url . $method, $params, array(), "json");
        $a_data = json_decode($response, true);
        //echo $response;exit();
        $data["status"] = ($a_data["IsError"]) ? false : true;
        $data["error"] = $$a_data["ErrorMessage"];
        $data["result"] = @$a_data["ResultList"];
        return $data;
    }

    public function set_param($tabid = null, $module = null, $a_data = array(), $a_adv = array())
    {
        if ($tabid == "") return false;
        if ($module == "") return false;
        if (empty($a_data)) return false;
        /*	$this->CI->load->library('ui');
            $field = $this->CI->ui->get_field($tabid);

            foreach($field as $k=> $v){
                $column = $v["Columnname"];
              if(isset($a_data[$column])){
                  $a_param[ucwords($column)] = (trim($a_data[$column]) =="")?"":$a_data[$column];
              }else{
                  $a_param[ucwords($column)] =  null;
              }
            }*/

        $a_param = $this->set_param_field($tabid, $module, $a_data);


        if ($a_data["action"] == "add") {
            $a_param["Addempcd"] = USERNAME;
            $a_param["Addpcnm"] = $this->get_server_name();
            $a_param["Id"] = 0;
        } else {
            $a_param["Updempcd"] = USERNAME;
            $a_param["Updpcnm"] = $this->get_server_name();
            $a_param["Id"] = $a_data["id"];
        }
        $param["InputList"][0] = $a_param;
        $param["Setype"] = $module;
        $param["UserID"] = USERID;
        $param["UserName"] = USERNAME;

        return $param;
    }

    public function set_param_multi($tabid = null, $module = null, $a_data = array(), $a_adv = array())
    {
        if ($tabid == "") return false;
        if ($module == "") return false;
        if (empty($a_data)) return false;
        $pcnm = $this->get_server_name();
        $i = 0;
        //alert($a_data);
        foreach ($a_data as $key => $value) {
            $a_param[$i] = $this->set_param_field($tabid, $module, $value);
            $action = $value["action"];
            if ($action == "add") {
                $a_param[$i]["Addempcd"] = USERNAME;
                $a_param[$i]["Addpcnm"] = $pcnm;
                $a_param[$i]["Id"] = 0;
            } else {
                $a_param[$i]["Updempcd"] = USERNAME;
                $a_param[$i]["Updpcnm"] = $pcnm;
                $a_param[$i]["Id"] = $value["id"];
            }
            $i++;
        }


        $param["InputList"] = $a_param;
        $param["Setype"] = $module;
        $param["UserID"] = USERID;
        $param["UserName"] = USERNAME;
        return $param;
    }

    public function set_param_field($tabid = null, $module = null, $a_data = array(), $a_extra = array())
    {
        if ($tabid == "") return false;
        if ($module == "") return false;
        if (empty($a_data)) return false;
        $this->CI->load->library('ui');
        $field = $this->CI->ui->get_field($tabid);
        foreach ($field as $k => $v) {
            $column = $v["Columnname"];
            $uitype = $v["Uitype"];
            $defaultvalue = $v["Defaultvalue"];
            if (isset($a_data[$column])) {
                if ($uitype == "5" || $uitype == "305" || $uitype == "5" || $uitype == "601") {
                    if (isset($a_extra["format_date_flg"]) && $a_extra["format_date_flg"]) {
                        $a_param[ucwords($column)] = isset($a_data[$column]) ? date_get($a_data[$column], "Y-m-d") : null;
                    } else {
                        $a_param[ucwords($column)] = isset($a_data[$column]) ? date_set($a_data[$column], "Y-m-d") : null;
                    }

                } else {
                    $a_param[ucwords($column)] = (trim($a_data[$column]) == "") ? $defaultvalue : $a_data[$column];
                }
            } else {
                $a_param[ucwords($column)] = ($defaultvalue != "") ? $defaultvalue : null;
            }
        }
        //alert($a_param);
        return $a_param;
    }

    public function call_stored($storename = null, $a_param = array())
    {
        $this->CI->load->database();
        $log = $this->CI->config->item('log');

        if (empty($a_param)) return false;

        try {
            $param = implode("','", $a_param);
            $sql = " execute " . $storename . "  '" . $param . "'  ";
            if ($log["status"]) {
                $this->CI->load->library("common");
                $this->CI->common->set_log_begin($sql, $a_param);
            }
            $query = $this->CI->db->query($sql);
            $a_data = array();
            if (!$query) {
                $a_response["status"] = false;
                $a_response["msg"] = $this->CI->db->_error_message();
                $a_response["result"] = "";
            } else {
                $a_data = $query->result_array();
                $a_response["status"] = true;
                $a_response["msg"] = "";
                $a_response["result"] = $a_data;
            }
        } catch (Exception $e) {
            $a_response["status"] = false;
            $a_response["msg"] = $e->getMessage();
            $a_response["result"] = "";
        }

        if ($log["status"]) {
            $this->CI->common->set_log_stored($sql, $a_param, $a_response, $storename);
        }
        return $a_response;
    }

    public function call_stored_export_csv($storename = null, $a_param = array(), $filename = null)
    {
        $this->CI->load->database();
        //$this->CI->load->dbutil();
        $this->CI->load->helper('file');
        $this->CI->load->helper('download');
        $this->CI->load->helper('csv');

        $log = $this->CI->config->item('log');
        $export = $this->CI->config->item('export');

        if (empty($a_param)) return false;

        try {
            $param = implode("','", $a_param);
            $sql = " execute " . $storename . "  '" . $param . "'  ";
            if ($log["status"]) {
                $this->CI->load->library("common");
                $this->CI->common->set_log_begin($sql, $a_param);
            }
            $query = $this->CI->db->query($sql);


            $a_data = array();
            if (!$query) {
                $a_response["status"] = false;
                $a_response["msg"] = $this->CI->db->_error_message();
                $a_response["result"] = "";
            } else {
                $delimiter = ",";
                $newline = "\r\n";
                //alert( $query->result_array() );exit();
                //$a_data = $this->CI->dbutil->csv_from_result($query, $delimiter, $newline);
                //$a_data = ltrim(strstr($this->CI->dbutil->csv_from_result($query, $delimiter, $newline), '"",'));
                //$a_data =  $query->result_array() ;

                $a_data = query_to_csv($query, true, $delimiter, $newline);
                write_file_csv($export['path'] . $filename, $a_data);
                //echo $query;
                //alert($a_data);
                //exit();
                //force_download($filename, $a_data);

                //$a_data =  $query->result_array() ;
                $a_response["status"] = true;
                $a_response["msg"] = "";
                $a_response["result"] = $a_data;
            }
        } catch (Exception $e) {
            $a_response["status"] = false;
            $a_response["msg"] = $e->getMessage();
            $a_response["result"] = "";
        }

        if ($log["status"]) {
            $this->CI->common->set_log_stored($sql, $a_param, $a_response, $storename);
        }
        return $a_response;
    }


    function get_report($method = NULL, $module = NULL, $params = array(), $debug = FALSE)
    {

        $this->debug = $debug;
        if (!$method) return 'Method require';
        $url = config_item('service_report_url');
        $path = config_item('service_report_path');
        //        alert($method); exit();
        return $this->post_api($url . $path, $method, $module, $params);
    }
}
