<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
include(APPPATH . 'libraries/xlsxwriter.class.php');
class Advance extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('memcached_library');
        $this->load->library('crmentity');
        $this->load->database();
        $this->load->library("common");
    }

    public function search_post()
    {
        if($this->input->post()){
            $post = $this->input->post();
        }else{
            $request_body = file_get_contents('php://input');
            $post = json_decode($request_body, true);
        }
        
        $page = isset($post['page']) ? intval($post['page']) : 1;
        $rows = isset($post['rows']) ? intval($post['rows']) : 20;
        $sort = isset($post['sort']) ? $post['sort'] : 'accountname';
        $order = isset($post['order']) ? $post['order'] : 'asc';
        $tabName = isset($post['tabName']) ? $post['tabName'] : '';

        $recordid = isset($post['recordid']) ? $post['recordid'] : "";
        $current_module = isset($post['module']) ? $post['module'] : "";
        $module = isset($post['current_module']) ? $post['current_module'] : "";

        $accountstatus = isset($post['accountstatus']) ? $post['accountstatus'] : "";
        $accounttype = isset($post['accounttype']) ? $post['accounttype'] : "";
        $accountindustry = isset($post['accountindustry']) ? $post['accountindustry'] : "";
        $birth_date = isset($post['birth_date']) ? $post['birth_date'] : "";
        $birth_month = isset($post['birth_month']) ? $post['birth_month'] : "";
        $gender = isset($post['gender']) ? $post['gender'] : "";
        $accountsource = isset($post['accountsource']) ? $post['accountsource'] : "";
        $accountinterest = isset($post['accountinterest']) ? $post['accountinterest'] : "";
        $products_category = isset($post['products_category']) ? $post['products_category'] : "";
        $interested_brand = isset($post['interested_brand']) ? $post['interested_brand'] : "";
        $tbm_provinceid = isset($post['tbm_provinceid']) ? $post['tbm_provinceid'] : "";
        $tbm_amphurid = isset($post['tbm_amphurid']) ? $post['tbm_amphurid'] : "";
        $tbm_districtid = isset($post['tbm_districtid']) ? $post['tbm_districtid'] : "";
        $region = isset($post['region']) ? $post['region'] : "";
        $social_channel = isset($post['social_channel']) ? $post['social_channel'] : "";
        $line_oa_facebook_fan_page_name = isset($post['line_oa_facebook_fan_page_name']) ? $post['line_oa_facebook_fan_page_name'] : "";

        $accountgrade = isset($post['accountgrade']) ? $post['accountgrade'] : "";
        $repeat_buyers = isset($post['repeat_buyers']) ? $post['repeat_buyers'] : "";
        $sales_main_channel = isset($post['sales_main_channel']) ? $post['sales_main_channel'] : "";
        $sales_sub_channel = isset($post['sales_sub_channel']) ? $post['sales_sub_channel'] : "";

        $acc_source = isset($post['acc_source']) ? $post['acc_source'] : "";
        $sales_office = isset($post['sales_office']) ? $post['sales_office'] : "";

        $firstpurchasedate_from = isset($post['firstpurchasedate_from']) ? $this->convertDate2DB($post['firstpurchasedate_from']) : "";
        $firstpurchasedate_to = isset($post['firstpurchasedate_to']) ? $this->convertDate2DB($post['firstpurchasedate_to']) : "";
        $lastpurchasedate_from = isset($post['lastpurchasedate_from']) ? $this->convertDate2DB($post['lastpurchasedate_from']) : "";
        $lastpurchasedate_to = isset($post['lastpurchasedate_to']) ? $this->convertDate2DB($post['lastpurchasedate_to']) : "";

        $total_remain_from = isset($post['total_remain_from']) ? $post['total_remain_from'] : "";
        $total_remain_to = isset($post['total_remain_to']) ? $post['total_remain_to'] : "";

        $purchase_date_diff_from = isset($post['purchase_date_diff_from']) ? $post['purchase_date_diff_from'] : "";
        $purchase_date_diff_to = isset($post['purchase_date_diff_to']) ? $post['purchase_date_diff_to'] : "";

        $accumulatepurchasefrequency_from = isset($post['accumulatepurchasefrequency_from']) ? $post['accumulatepurchasefrequency_from'] : "";
        $accumulatepurchasefrequency_to = isset($post['accumulatepurchasefrequency_to']) ? $post['accumulatepurchasefrequency_to'] : "";
        
        $accumulatepurchaseyearamount_from = isset($post['accumulatepurchaseyearamount_from']) ? $post['accumulatepurchaseyearamount_from'] : "";
        $accumulatepurchaseyearamount_to = isset($post['accumulatepurchaseyearamount_to']) ? $post['accumulatepurchaseyearamount_to'] : "";

        $email_consent = isset($post['email_consent']) ? $post['email_consent'] : "";

        $product_brand = isset($post['product_brand']) ? $post['product_brand'] : "";
        $material_type = isset($post['material_type']) ? $post['material_type'] : "";
        $product_type = isset($post['product_type']) ? $post['product_type'] : "";
        $product_category = isset($post['product_category']) ? $post['product_category'] : "";        
        $invoice_date_from = isset($post['invoice_date_from']) ? $this->convertDate2DB($post['invoice_date_from']) : "";
        $invoice_date_to = isset($post['invoice_date_to']) ? $this->convertDate2DB($post['invoice_date_to']) : "";

        $main_channel = isset($post['main_channel']) ? $post['main_channel'] : "";
        $sub_channel = isset($post['sub_channel']) ? $post['sub_channel'] : "";

        $pagenumber = isset($post['pagenumber']) ? $post['pagenumber'] : "";
        $pagesize = isset($post['pagesize']) ? $post['pagesize'] : "";

        $params = [
            'accountstatus' => $accountstatus,
            'accounttype' => $accounttype,
            'accountindustry' => $accountindustry,
            'birth_date' => $birth_date,
            'birth_month' => $birth_month,
            'gender' => $gender,
            'recordid' => $recordid,
            'current_module' => $current_module,
            'module' => $module,
            'accountsource' => $accountsource,
            'accountinterest' => $accountinterest,
            'products_category' => $products_category,
            'interested_brand' => $interested_brand,
            'tbm_districtid' => $tbm_districtid,
            'tbm_amphurid' => $tbm_amphurid,
            'tbm_provinceid' => $tbm_provinceid,
            'region' => $region,
            'social_channel' => $social_channel,
            'line_oa_facebook_fan_page_name' => $line_oa_facebook_fan_page_name,
            'accountgrade' => $accountgrade,
            'repeat_buyers' => $repeat_buyers,
            'sales_main_channel' => $sales_main_channel,
            'sales_sub_channel' => $sales_sub_channel,
            'acc_source' => $acc_source,
            'sales_office' => $sales_office,
            'firstpurchasedate_from' => $firstpurchasedate_from,
            'firstpurchasedate_to' => $firstpurchasedate_to,
            'lastpurchasedate_from' => $lastpurchasedate_from,
            'lastpurchasedate_to' => $lastpurchasedate_to,

            'total_remain_from' => $total_remain_from,
            'total_remain_to' => $total_remain_to,

            'purchase_date_diff_from' => $purchase_date_diff_from,
            'purchase_date_diff_to' => $purchase_date_diff_to,
            'accumulatepurchasefrequency_from' => $accumulatepurchasefrequency_from,
            'accumulatepurchasefrequency_to' => $accumulatepurchasefrequency_to,
            'accumulatepurchaseyearamount_from' => $accumulatepurchaseyearamount_from,
            'accumulatepurchaseyearamount_to' => $accumulatepurchaseyearamount_to,
            'email_consent' => $email_consent,
            'product_brand' => $product_brand,
            'material_type' => $material_type,
            'product_type' => $product_type,
            'product_category' => $product_category,
            'invoice_date_from' => $invoice_date_from,
            'invoice_date_to' => $invoice_date_to,
            'main_channel' => $main_channel,
            'sub_channel' => $sub_channel,
        ];

        if($pagenumber != '' && $pagesize != ''){
            $offset = ($pagenumber - 1) * $pagesize;
            $rows = $pagesize;
        }else{
            $offset = ($page - 1) * $rows;
        }

        $tab1 = false;
        $tab2 = false;
        if($accountstatus !='' || $accounttype != '' || $accountindustry != '' || $birth_date != '' || $birth_month != '' || $gender != ''
        || $accountsource !='' 
        || $accountinterest !='' 
        || $products_category !='' 
        || $interested_brand !='' 
        || $tbm_districtid !=''
        || $tbm_amphurid !='' 
        || $tbm_provinceid !=''
        || $region !='' 
        || $social_channel !='' 
        || $line_oa_facebook_fan_page_name !=''
        || $accountgrade !='' 
        || $repeat_buyers !='' 
        || $sales_main_channel !='' 
        || $sales_sub_channel !='' 
        || $acc_source !='' 
        || $sales_office !='' 
        || $firstpurchasedate_from !='' 
        || $firstpurchasedate_to !='' 
        || $lastpurchasedate_from !='' 
        || $lastpurchasedate_to !='' 
        || $total_remain_from !='' 
        || $total_remain_to !='' 
        || $purchase_date_diff_from !='' 
        || $purchase_date_diff_to !='' 
        || $accumulatepurchasefrequency_from !='' 
        || $accumulatepurchasefrequency_to !='' 
        || $accumulatepurchaseyearamount_from !=''
        || $accumulatepurchaseyearamount_to !=''
        || $email_consent !='' 
        ){
            $tab1 = true;
        }

        if($product_brand != '' || $material_type != '' || $product_type != '' || $product_category != '' || $invoice_date_from != '' || $invoice_date_to != '' || $main_channel != '' || $sub_channel != ''){
            $tab2 = true;
        }
        
        $rs = [];
        if($tab1 && !$tab2 ){
            $rs = $this->searchQuery1($params);
        }else if(!$tab1 && $tab2){
            $rs = $this->searchQuery2($params);
        }else if(!$tab1 && !$tab2){
            $rs = $this->searchQuery1($params);
        }else {
            $rs = $this->searchQuery3($params);
        }
        //alert($rs); exit;
        $sql_group = " GROUP BY aicrm_account.accountid";
        $sql_order = " ORDER BY $sort $order ";

        $sql_limit = "";
        if($tabName == 'saveLimit'){
            $sql_limit = " LIMIT $offset, 1000";
        }else{
            $sql_limit = " LIMIT $offset, $rows";
        }

        $data = [];
        
        if(!empty($rs)){
            $queryStr = $rs['select'].' '.$rs['from'].' '.$rs['where'].$sql_group.$sql_order.$sql_limit;
            
            /*$this->common->_filename= "Query_Advance_Search";
            $log_query = $queryStr;
            $this->common->set_log('Query_Advance_Search==>',str_replace("\r\n",'', $log_query));*/
           
            $query = $this->db->query($queryStr);
            $total = $this->db->query($rs['select'].' '.$rs['from'].' '.$rs['where'].$sql_group.$sql_order);
            //echo $this->db->last_query(); exit;
            $rows = $total->num_rows();
            $data = $query->result_array();
        }

        array_walk_recursive($data, function (&$item, $key) {
            $item = str_replace('|##|', ',', $item);
        });

        //echo $this->db->last_query();exit;
        $response_data = [
            'Type' => 'S',
            'Message' => 'Success',
            'data' => $data,
            'rows' => $rows
        ];

        if ($response_data) {
            $this->response($response_data, 200); // 200 being the HTTP response code
        } else {
            $this->response(array(
                'error' => 'Couldn\'t find data!',
            ), 404);
        }
    }

    

    public function searchQuery1($params)
    {
        $select = "select 
        aicrm_crmentity.crmid,
        aicrm_crmentity.smownerid,
        aicrm_users.user_name,
        aicrm_account.*,
        aicrm_accountscf.*
        ";
        
        $from ="FROM aicrm_account
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid and aicrm_crmentity.deleted = 0
        INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
        INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
        INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_account aicrm_account2 ON aicrm_account.parentid = aicrm_account2.accountid";

        $where = " WHERE 1 = 1";
        if($params['accountstatus'] != ''){
            $accountstatus = explode(',', $params['accountstatus']);
            $accountstatus = implode("','", $accountstatus);
            $where .= " and aicrm_account.accountstatus in ('". $accountstatus ."')";
        } 
        if($params['accounttype'] != ''){
            $accounttype = explode(',', $params['accounttype']);
            $accounttype = implode("','", $accounttype);
            $where .= " and aicrm_account.accounttype in ('". $accounttype ."')";
        } 
        if($params['accountindustry'] != ''){
            $accountindustry = explode(',', $params['accountindustry']);
            $accountindustry = implode("','", $accountindustry);
            $where .= " and aicrm_account.accountindustry in ('". $accountindustry ."')";
        } 
        if($params['birth_date'] != ''){
            $birth_date = explode(',', $params['birth_date']);
            $birth_date = implode("','", $birth_date);
            $where .= " and DAY(aicrm_account.birthdate) in ('". $birth_date ."')";
        } 
        if($params['birth_month'] != ''){
            $birth_month = explode(',', $params['birth_month']);
            $birth_month = implode("','", $birth_month);
            $where .= " and MONTH(aicrm_account.birthdate) in ('". $birth_month ."')";
        } 
        if($params['gender'] != ''){
            $gender = explode(',', $params['gender']);
            $gender = implode("','", $gender);
            $where .= " and aicrm_account.gender in ('". $gender ."')";
        } 
        if($params['accountsource'] != ''){
            $accountsource = explode(',', $params['accountsource']);
            $accountsource = implode("','", $accountsource);
            $where .= " and aicrm_account.accountsource in ('". $accountsource ."')";
        } 
        if($params['accountinterest'] != ''){
            $accountinterest = explode(',', $params['accountinterest']);
            $accountinterest = implode("','", $accountinterest);
            $where .= " and aicrm_account.accountinterest in ('". $accountinterest ."')";
        }

        if($params['products_category'] != ''){
            $products_category = explode(',', $params['products_category']);
            $products_category = implode("|", $products_category);
            $where .= " and aicrm_account.products_category RLIKE '". $products_category ."'";
        }

        if($params['interested_brand'] != ''){
            $interested_brand = explode(',', $params['interested_brand']);
            $interested_brand = implode("|", $interested_brand);
            $where .= " and aicrm_account.interested_brand RLIKE '". $interested_brand ."'";
        }

        if($params['tbm_districtid'] != '') $where .= " and aicrm_account.subdistrict = '". $params['tbm_districtid'] ."'";
        if($params['tbm_amphurid'] != '') $where .= " and aicrm_account.district = '". $params['tbm_amphurid'] ."'";
        if($params['tbm_provinceid'] != '') $where .= " and aicrm_account.province = '". $params['tbm_provinceid'] ."'";
        
        if($params['region'] != ''){
            $region = explode(',', $params['region']);
            $region = implode("','", $region);
            $where .= " and aicrm_account.region in ('". $region ."')";
        }
        if($params['social_channel'] != ''){
            $social_channel = explode(',', $params['social_channel']);
            $social_channel = implode("','", $social_channel);
            $where .= " and aicrm_account.social_channel in ('". $social_channel ."')";
        }
        if($params['line_oa_facebook_fan_page_name'] != '') $where .= " and aicrm_account.line_oa_facebook_fan_page_name = '". $params['line_oa_facebook_fan_page_name'] ."'";

        if($params['accountgrade'] != ''){
            $accountgrade = explode(',', $params['accountgrade']);
            $accountgrade = implode("','", $accountgrade);
            $where .= " and aicrm_account.accountgrade in ('". $accountgrade ."')";
        }
        if($params['repeat_buyers'] != ''){
            $repeat_buyers = explode(',', $params['repeat_buyers']);
            $repeat_buyers = implode("','", $repeat_buyers);
            $where .= " and aicrm_account.repeat_buyers in ('". $repeat_buyers ."')";
        }
        if($params['sales_main_channel'] != ''){
            $sales_main_channel = explode(',', $params['sales_main_channel']);
            $sales_main_channel = implode("','", $sales_main_channel);
            $where .= " and aicrm_account.sales_main_channel in ('". $sales_main_channel ."')";
        }
        
        if($params['sales_sub_channel'] != ''){
            $sales_sub_channel = explode(',', $params['sales_sub_channel']);
            $sales_sub_channel = implode("|", $sales_sub_channel);
            $where .= " and aicrm_account.sales_sub_channel RLIKE '". $sales_sub_channel ."'";
        }
        

        if($params['acc_source'] != ''){
            $acc_source = explode(',', $params['acc_source']);
            $acc_source = implode("|", $acc_source);
            $where .= " and aicrm_account.acc_source RLIKE '". $acc_source ."'";
        }

        if($params['sales_office'] != ''){
            $sales_office = explode(',', $params['sales_office']);
            $sales_office = implode("|", $sales_office);
            $where .= " and aicrm_account.sales_office RLIKE '". $sales_office ."'";
        }

        if($params['social_channel'] != ''){
            $social_channel = explode(',', $params['social_channel']);
            $social_channel = implode("','", $social_channel);
            $where .= " and aicrm_account.social_channel in ('". $social_channel ."')";
        }

        if($params['firstpurchasedate_from'] != ''){
            $where .= " and aicrm_account.firstpurchasedate >= '".$params['firstpurchasedate_from'] ."'";
        }
        if($params['firstpurchasedate_to'] != ''){
            $where .= " and aicrm_account.firstpurchasedate <= '".$params['firstpurchasedate_to']."'";
        }

        if($params['lastpurchasedate_from'] != ''){
            $where .= " and aicrm_account.lastpurchasedate >= '". $params['lastpurchasedate_from'] ."'";
        }
        if($params['lastpurchasedate_to'] != ''){
            $where .= " and aicrm_account.lastpurchasedate <= '". $params['lastpurchasedate_to'] ."'";
        }

        if($params['total_remain_from'] != ''){
            $where .= " and aicrm_account.point_remaining >= '". $params['total_remain_from'] ."'";
        }
        if($params['total_remain_to'] != ''){
            $where .= " and aicrm_account.point_remaining <= '". $params['total_remain_to'] ."'";
        }

        if($params['purchase_date_diff_from'] != ''){
            $where .= " and aicrm_account.purchase_date_diff >= '". $params['purchase_date_diff_from'] ."'";
        }
        if($params['purchase_date_diff_to'] != ''){
            $where .= " and aicrm_account.purchase_date_diff <= '". $params['purchase_date_diff_to'] ."'";
        }

        if($params['accumulatepurchasefrequency_from'] != ''){
            $where .= " and aicrm_account.accumulatepurchasefrequency >= '". $params['accumulatepurchasefrequency_from'] ."'";
        }
        if($params['accumulatepurchasefrequency_to'] != ''){
            $where .= " and aicrm_account.accumulatepurchasefrequency <= '". $params['accumulatepurchasefrequency_to'] ."'";
        }

        if($params['accumulatepurchaseyearamount_from'] != ''){
            $where .= " and aicrm_account.accumulatepurchaseyearamount >= '". $params['accumulatepurchaseyearamount_from'] ."'";
        }
        if($params['accumulatepurchaseyearamount_to'] != ''){
            $where .= " and aicrm_account.accumulatepurchaseyearamount <= '". $params['accumulatepurchaseyearamount_to'] ."'";
        }

        if($params['email_consent'] != ''){
            $email_consent = explode(',', $params['email_consent']);
            $email_consent = implode("','", $email_consent);
            $where .= " and aicrm_account.email_consent in ('". $email_consent ."')";
        }
        
        return ['select'=>$select, 'from'=>$from, 'where'=>$where];
    }

    public function searchQuery2($params)
    {
        $select = "select 
        aicrm_crmentity.crmid,
        aicrm_crmentity.smownerid,
        aicrm_users.user_name,
        aicrm_account.*,
        aicrm_accountscf.*
        ";
        
        $from ="FROM aicrm_account
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid and aicrm_crmentity.deleted = 0
        INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
        INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
        INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_account aicrm_account2 ON aicrm_account.parentid = aicrm_account2.accountid
        inner JOIN
        (
            select 
            aicrm_salesinvoice.accountid        
            from aicrm_salesinvoice
            inner join aicrm_salesinvoicecf on aicrm_salesinvoice.salesinvoiceid = aicrm_salesinvoicecf.salesinvoiceid
            inner join aicrm_crmentity as crmentity_salesinvoice on crmentity_salesinvoice.crmid = aicrm_salesinvoice.salesinvoiceid and crmentity_salesinvoice.deleted = 0
            INNER JOIN aicrm_inventoryproductrelsalesinvoice on aicrm_inventoryproductrelsalesinvoice.id = aicrm_salesinvoice.salesinvoiceid
            INNER JOIN aicrm_products on aicrm_products.productid = aicrm_inventoryproductrelsalesinvoice.productid
            INNER JOIN aicrm_productcf on aicrm_productcf.productid = aicrm_products.productid
            INNER JOIN aicrm_account on aicrm_account.accountid = aicrm_salesinvoice.accountid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
            INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
            INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
            INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            where 1=1";
        
            if($params['product_brand'] != ''){
                $product_brand = explode(",", $params['product_brand']);
                $product_brand = implode("','", $product_brand);
                $from .= " and aicrm_products.product_brand in ('". $product_brand ."')";
            }

            if($params['material_type'] != ''){
                $material_type = explode(",", $params['material_type']);
                $material_type = implode("','", $material_type);
                $from .= " and aicrm_products.material_type in ('". $material_type ."')";
            }

            if($params['product_type'] != ''){
                $product_type = explode(",", $params['product_type']);
                $product_type = implode("','", $product_type);
                $from .= " and aicrm_products.producttype in ('". $product_type ."')";
            }

            if($params['product_category'] != ''){
                $product_category = explode(",", $params['product_category']);
                $product_category = implode("','", $product_category);
                $from .= " and aicrm_products.productcategory in ('". $product_category ."')";
            }

            if($params['main_channel'] != ''){
                $main_channel = explode(",", $params['main_channel']);
                $main_channel = implode("','", $main_channel);
                $from .= " and aicrm_salesinvoice.main_channel in ('". $main_channel ."')";
            }

            if($params['sub_channel'] != ''){
                $sub_channel = explode(",", $params['sub_channel']);
                $sub_channel = implode("','", $sub_channel);
                $from .= " and aicrm_salesinvoice.sub_channel in ('". $sub_channel ."')";
            }

            if($params['invoice_date_from'] != ''){
                $dateFrom = str_replace('/', '-', $params['invoice_date_from']);
                $dateFrom = date('Y-m-d', strtotime($dateFrom));
                $from .= " and aicrm_salesinvoice.invoice_date >= '".$dateFrom."'";
            }
            if($params['invoice_date_to'] != ''){
                $dateTo = str_replace('/', '-', $params['invoice_date_to']);
                $dateTo = date('Y-m-d', strtotime($dateTo));
                $from .= " and aicrm_salesinvoice.invoice_date <= '".$dateTo."'";
            }
            $from .= " group by aicrm_salesinvoice.accountid
        ) salesinvoice on aicrm_account.accountid = salesinvoice.accountid
        ";

        $where = " WHERE 1 = 1";
        if($params['accountstatus'] != ''){
            $accountstatus = explode(',', $params['accountstatus']);
            $accountstatus = implode("','", $accountstatus);
            $where .= " and aicrm_account.accountstatus in ('". $accountstatus ."')";
        } 
        if($params['accounttype'] != ''){
            $accounttype = explode(',', $params['accounttype']);
            $accounttype = implode("','", $accounttype);
            $where .= " and aicrm_account.accounttype in ('". $accounttype ."')";
        } 
        if($params['accountindustry'] != ''){
            $accountindustry = explode(',', $params['accountindustry']);
            $accountindustry = implode("','", $accountindustry);
            $where .= " and aicrm_account.accountindustry in ('". $accountindustry ."')";
        } 
        if($params['birth_date'] != ''){
            $birth_date = explode(',', $params['birth_date']);
            $birth_date = implode("','", $birth_date);
            $where .= " and DAY(aicrm_account.birthdate) in ('". $birth_date ."')";
        } 
        if($params['birth_month'] != ''){
            $birth_month = explode(',', $params['birth_month']);
            $birth_month = implode("','", $birth_month);
            $where .= " and MONTH(aicrm_account.birthdate) in ('". $birth_month ."')";
        } 
        if($params['gender'] != ''){
            $gender = explode(',', $params['gender']);
            $gender = implode("','", $gender);
            $where .= " and aicrm_account.gender in ('". $gender ."')";
        } 
        if($params['accountsource'] != ''){
            $accountsource = explode(',', $params['accountsource']);
            $accountsource = implode("','", $accountsource);
            $where .= " and aicrm_account.accountsource in ('". $accountsource ."')";
        } 
        if($params['accountinterest'] != ''){
            $accountinterest = explode(',', $params['accountinterest']);
            $accountinterest = implode("','", $accountinterest);
            $where .= " and aicrm_account.accountinterest in ('". $accountinterest ."')";
        }

        if($params['products_category'] != ''){
            $products_category = explode(',', $params['products_category']);
            $products_category = implode("|", $products_category);
            $where .= " and aicrm_account.products_category RLIKE '". $products_category ."'";
        }
        /*if($params['products_category'] != ''){
            $products_category = explode(',', $params['products_category']);
            $products_category = implode("','", $products_category);
            $where .= " and aicrm_account.products_category in ('". $products_category ."')";
        }*/

        if($params['interested_brand'] != ''){
            $interested_brand = explode(',', $params['interested_brand']);
            $interested_brand = implode("|", $interested_brand);
            $where .= " and aicrm_account.interested_brand RLIKE '". $interested_brand ."'";
        }
        /*if($params['interested_brand'] != ''){
            $interested_brand = explode(',', $params['interested_brand']);
            $interested_brand = implode("','", $interested_brand);
            $where .= " and aicrm_account.interested_brand in ('". $interested_brand ."')";
        }*/

        if($params['tbm_districtid'] != '') $where .= " and aicrm_account.subdistrict = '". $params['tbm_districtid'] ."'";
        if($params['tbm_amphurid'] != '') $where .= " and aicrm_account.district = '". $params['tbm_amphurid'] ."'";
        if($params['tbm_provinceid'] != '') $where .= " and aicrm_account.province = '". $params['tbm_provinceid'] ."'";
        
        if($params['region'] != ''){
            $region = explode(',', $params['region']);
            $region = implode("','", $region);
            $where .= " and aicrm_account.region in ('". $region ."')";
        }
        if($params['social_channel'] != ''){
            $social_channel = explode(',', $params['social_channel']);
            $social_channel = implode("','", $social_channel);
            $where .= " and aicrm_account.social_channel in ('". $social_channel ."')";
        }
        if($params['line_oa_facebook_fan_page_name'] != '') $where .= " and aicrm_account.line_oa_facebook_fan_page_name = '". $params['line_oa_facebook_fan_page_name'] ."'";

        if($params['accountgrade'] != ''){
            $accountgrade = explode(',', $params['accountgrade']);
            $accountgrade = implode("','", $accountgrade);
            $where .= " and aicrm_account.accountgrade in ('". $accountgrade ."')";
        }
        if($params['repeat_buyers'] != ''){
            $repeat_buyers = explode(',', $params['repeat_buyers']);
            $repeat_buyers = implode("','", $repeat_buyers);
            $where .= " and aicrm_account.repeat_buyers in ('". $repeat_buyers ."')";
        }
        if($params['sales_main_channel'] != ''){
            $sales_main_channel = explode(',', $params['sales_main_channel']);
            $sales_main_channel = implode("','", $sales_main_channel);
            $where .= " and aicrm_account.sales_main_channel in ('". $sales_main_channel ."')";
        }

        if($params['sales_sub_channel'] != ''){
            $sales_sub_channel = explode(',', $params['sales_sub_channel']);
            $sales_sub_channel = implode("|", $sales_sub_channel);
            $where .= " and aicrm_account.sales_sub_channel RLIKE '". $sales_sub_channel ."'";
        }

        if($params['acc_source'] != ''){
            $acc_source = explode(',', $params['acc_source']);
            $acc_source = implode("|", $acc_source);
            $where .= " and aicrm_account.acc_source RLIKE '". $acc_source ."'";
        }
        /*if($params['acc_source'] != ''){
            $acc_source = explode(',', $params['acc_source']);
            $acc_source = implode("','", $acc_source);
            $where .= " and aicrm_account.acc_source in ('". $acc_source ."')";
        }*/

        if($params['sales_office'] != ''){
            $sales_office = explode(',', $params['sales_office']);
            $sales_office = implode("|", $sales_office);
            $where .= " and aicrm_account.sales_office RLIKE '". $sales_office ."'";
        }

        /*if($params['sales_office'] != ''){
            $sales_office = explode(',', $params['sales_office']);
            $sales_office = implode("','", $sales_office);
            $where .= " and aicrm_account.sales_office in ('". $sales_office ."')";
        }*/

        if($params['social_channel'] != ''){
            $social_channel = explode(',', $params['social_channel']);
            $social_channel = implode("','", $social_channel);
            $where .= " and aicrm_account.social_channel in ('". $social_channel ."')";
        }

        if($params['firstpurchasedate_from'] != ''){
            $where .= " and aicrm_account.firstpurchasedate >= '".$params['firstpurchasedate_from'] ."'";
        }
        if($params['firstpurchasedate_to'] != ''){
            $where .= " and aicrm_account.firstpurchasedate <= '".$params['firstpurchasedate_to']."'";
        }

        if($params['lastpurchasedate_from'] != ''){
            $where .= " and aicrm_account.lastpurchasedate >= '". $params['lastpurchasedate_from'] ."'";
        }
        if($params['lastpurchasedate_to'] != ''){
            $where .= " and aicrm_account.lastpurchasedate <= '". $params['lastpurchasedate_to'] ."'";
        }

        if($params['total_remain_from'] != ''){
            $where .= " and aicrm_account.point_remaining >= '". $params['total_remain_from'] ."'";
        }
        if($params['total_remain_to'] != ''){
            $where .= " and aicrm_account.point_remaining <= '". $params['total_remain_to'] ."'";
        }

        if($params['purchase_date_diff_from'] != ''){
            $where .= " and aicrm_account.purchase_date_diff >= '". $params['purchase_date_diff_from'] ."'";
        }
        if($params['purchase_date_diff_to'] != ''){
            $where .= " and aicrm_account.purchase_date_diff <= '". $params['purchase_date_diff_to'] ."'";
        }

        if($params['accumulatepurchasefrequency_from'] != ''){
            $where .= " and aicrm_account.accumulatepurchasefrequency >= '". $params['accumulatepurchasefrequency_from'] ."'";
        }
        if($params['accumulatepurchasefrequency_to'] != ''){
            $where .= " and aicrm_account.accumulatepurchasefrequency <= '". $params['accumulatepurchasefrequency_to'] ."'";
        }

        if($params['accumulatepurchaseyearamount_from'] != ''){
            $where .= " and aicrm_account.accumulatepurchaseyearamount >= '". $params['accumulatepurchaseyearamount_from'] ."'";
        }
        if($params['accumulatepurchaseyearamount_to'] != ''){
            $where .= " and aicrm_account.accumulatepurchaseyearamount <= '". $params['accumulatepurchaseyearamount_to'] ."'";
        }

        if ($params['recordid'] != "" && $params['current_module'] != '') {
            $where .= $this->getRelCheckquery($params['current_module'], $params['module'], $params['recordid']);
        }

        if($params['email_consent'] != ''){
            $email_consent = explode(',', $params['email_consent']);
            $email_consent = implode("','", $email_consent);
            $where .= " and aicrm_account.email_consent in ('". $email_consent ."')";
        }

        return ['select'=>$select, 'from'=>$from, 'where'=>$where];
    }
   
    public function searchQuery3($params)
    {
        $select = "select 
        aicrm_crmentity.crmid,
        aicrm_crmentity.smownerid,
        aicrm_users.user_name,
        aicrm_account.*,
        aicrm_accountscf.*
        ";
        
        $from ="FROM aicrm_account
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid and aicrm_crmentity.deleted = 0
        INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
        INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
        INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
        LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
        LEFT JOIN aicrm_account aicrm_account2 ON aicrm_account.parentid = aicrm_account2.accountid
        inner JOIN
        (
            select 
            aicrm_salesinvoice.accountid        
            from aicrm_salesinvoice
            inner join aicrm_salesinvoicecf on aicrm_salesinvoice.salesinvoiceid = aicrm_salesinvoicecf.salesinvoiceid
            inner join aicrm_crmentity as crmentity_salesinvoice on crmentity_salesinvoice.crmid = aicrm_salesinvoice.salesinvoiceid and crmentity_salesinvoice.deleted = 0
            INNER JOIN aicrm_inventoryproductrelsalesinvoice on aicrm_inventoryproductrelsalesinvoice.id = aicrm_salesinvoice.salesinvoiceid
            INNER JOIN aicrm_products on aicrm_products.productid = aicrm_inventoryproductrelsalesinvoice.productid
            INNER JOIN aicrm_productcf on aicrm_productcf.productid = aicrm_products.productid
            INNER JOIN aicrm_account on aicrm_account.accountid = aicrm_salesinvoice.accountid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
            INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
            INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
            INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
            LEFT JOIN aicrm_groups ON aicrm_groups.groupid = aicrm_crmentity.smownerid
            LEFT JOIN aicrm_users ON aicrm_users.id = aicrm_crmentity.smownerid
            where 1=1";
        
            if($params['product_brand'] != ''){
                $product_brand = explode(",", $params['product_brand']);
                $product_brand = implode("','", $product_brand);
                $from .= " and aicrm_products.product_brand in ('". $product_brand ."')";
            }

            if($params['material_type'] != ''){
                $material_type = explode(",", $params['material_type']);
                $material_type = implode("','", $material_type);
                $from .= " and aicrm_products.material_type in ('". $material_type ."')";
            }

            if($params['product_type'] != ''){
                $product_type = explode(",", $params['product_type']);
                $product_type = implode("','", $product_type);
                $from .= " and aicrm_products.producttype in ('". $product_type ."')";
            }

            if($params['product_category'] != ''){
                $product_category = explode(",", $params['product_category']);
                $product_category = implode("','", $product_category);
                $from .= " and aicrm_products.productcategory in ('". $product_category ."')";
            }

            if($params['main_channel'] != ''){
                $main_channel = explode(",", $params['main_channel']);
                $main_channel = implode("','", $main_channel);
                $from .= " and aicrm_salesinvoice.main_channel in ('". $main_channel ."')";
            }

            if($params['sub_channel'] != ''){
                $sub_channel = explode(",", $params['sub_channel']);
                $sub_channel = implode("','", $sub_channel);
                $from .= " and aicrm_salesinvoice.sub_channel in ('". $sub_channel ."')";
            }

            if($params['invoice_date_from'] != ''){
                $dateFrom = str_replace('/', '-', $params['invoice_date_from']);
                $dateFrom = date('Y-m-d', strtotime($dateFrom));
                $from .= " and aicrm_salesinvoice.invoice_date >= '".$dateFrom."'";
            }
            if($params['invoice_date_to'] != ''){
                $dateTo = str_replace('/', '-', $params['invoice_date_to']);
                $dateTo = date('Y-m-d', strtotime($dateTo));
                $from .= " and aicrm_salesinvoice.invoice_date <= '".$dateTo."'";
            }
            $from .= " group by aicrm_salesinvoice.accountid
        ) salesinvoice on aicrm_account.accountid = salesinvoice.accountid
        ";

        $where = " WHERE 1 = 1";
        if($params['accountstatus'] != ''){
            $accountstatus = explode(',', $params['accountstatus']);
            $accountstatus = implode("','", $accountstatus);
            $where .= " and aicrm_account.accountstatus in ('". $accountstatus ."')";
        } 
        if($params['accounttype'] != ''){
            $accounttype = explode(',', $params['accounttype']);
            $accounttype = implode("','", $accounttype);
            $where .= " and aicrm_account.accounttype in ('". $accounttype ."')";
        } 
        if($params['accountindustry'] != ''){
            $accountindustry = explode(',', $params['accountindustry']);
            $accountindustry = implode("','", $accountindustry);
            $where .= " and aicrm_account.accountindustry in ('". $accountindustry ."')";
        } 
        if($params['birth_date'] != ''){
            $birth_date = explode(',', $params['birth_date']);
            $birth_date = implode("','", $birth_date);
            $where .= " and DAY(aicrm_account.birthdate) in ('". $birth_date ."')";
        } 
        if($params['birth_month'] != ''){
            $birth_month = explode(',', $params['birth_month']);
            $birth_month = implode("','", $birth_month);
            $where .= " and MONTH(aicrm_account.birthdate) in ('". $birth_month ."')";
        } 
        if($params['gender'] != ''){
            $gender = explode(',', $params['gender']);
            $gender = implode("','", $gender);
            $where .= " and aicrm_account.gender in ('". $gender ."')";
        } 
        if($params['accountsource'] != ''){
            $accountsource = explode(',', $params['accountsource']);
            $accountsource = implode("','", $accountsource);
            $where .= " and aicrm_account.accountsource in ('". $accountsource ."')";
        } 
        if($params['accountinterest'] != ''){
            $accountinterest = explode(',', $params['accountinterest']);
            $accountinterest = implode("','", $accountinterest);
            $where .= " and aicrm_account.accountinterest in ('". $accountinterest ."')";
        }

        if($params['products_category'] != ''){
            $products_category = explode(',', $params['products_category']);
            $products_category = implode("|", $products_category);
            $where .= " and aicrm_account.products_category RLIKE '". $products_category ."'";
        }

        /*if($params['products_category'] != ''){
            $products_category = explode(',', $params['products_category']);
            $products_category = implode("','", $products_category);
            $where .= " and aicrm_account.products_category in ('". $products_category ."')";
        }*/

        if($params['interested_brand'] != ''){
            $interested_brand = explode(',', $params['interested_brand']);
            $interested_brand = implode("|", $interested_brand);
            $where .= " and aicrm_account.interested_brand RLIKE '". $interested_brand ."'";
        }

        /*if($params['interested_brand'] != ''){
            $interested_brand = explode(',', $params['interested_brand']);
            $interested_brand = implode("','", $interested_brand);
            $where .= " and aicrm_account.interested_brand in ('". $interested_brand ."')";
        }*/

        if($params['tbm_districtid'] != '') $where .= " and aicrm_account.subdistrict = '". $params['tbm_districtid'] ."'";
        if($params['tbm_amphurid'] != '') $where .= " and aicrm_account.district = '". $params['tbm_amphurid'] ."'";
        if($params['tbm_provinceid'] != '') $where .= " and aicrm_account.province = '". $params['tbm_provinceid'] ."'";
        
        if($params['region'] != ''){
            $region = explode(',', $params['region']);
            $region = implode("','", $region);
            $where .= " and aicrm_account.region in ('". $region ."')";
        }
        if($params['social_channel'] != ''){
            $social_channel = explode(',', $params['social_channel']);
            $social_channel = implode("','", $social_channel);
            $where .= " and aicrm_account.social_channel in ('". $social_channel ."')";
        }
        if($params['line_oa_facebook_fan_page_name'] != '') $where .= " and aicrm_account.line_oa_facebook_fan_page_name = '". $params['line_oa_facebook_fan_page_name'] ."'";

        if($params['accountgrade'] != ''){
            $accountgrade = explode(',', $params['accountgrade']);
            $accountgrade = implode("','", $accountgrade);
            $where .= " and aicrm_account.accountgrade in ('". $accountgrade ."')";
        }
        if($params['repeat_buyers'] != ''){
            $repeat_buyers = explode(',', $params['repeat_buyers']);
            $repeat_buyers = implode("','", $repeat_buyers);
            $where .= " and aicrm_account.repeat_buyers in ('". $repeat_buyers ."')";
        }
        if($params['sales_main_channel'] != ''){
            $sales_main_channel = explode(',', $params['sales_main_channel']);
            $sales_main_channel = implode("','", $sales_main_channel);
            $where .= " and aicrm_account.sales_main_channel in ('". $sales_main_channel ."')";
        }

        if($params['sales_sub_channel'] != ''){
            $sales_sub_channel = explode(',', $params['sales_sub_channel']);
            $sales_sub_channel = implode("|", $sales_sub_channel);
            $where .= " and aicrm_account.sales_sub_channel RLIKE '". $sales_sub_channel ."'";
        }
        
        if($params['acc_source'] != ''){
            $acc_source = explode(',', $params['acc_source']);
            $acc_source = implode("|", $acc_source);
            $where .= " and aicrm_account.acc_source RLIKE '". $acc_source ."'";
        }
        /*if($params['acc_source'] != ''){
            $acc_source = explode(',', $params['acc_source']);
            $acc_source = implode("','", $acc_source);
            $where .= " and aicrm_account.acc_source in ('". $acc_source ."')";
        }*/

        if($params['sales_office'] != ''){
            $sales_office = explode(',', $params['sales_office']);
            $sales_office = implode("|", $sales_office);
            $where .= " and aicrm_account.sales_office RLIKE '". $sales_office ."'";
        }

        /*if($params['sales_office'] != ''){
            $sales_office = explode(',', $params['sales_office']);
            $sales_office = implode("','", $sales_office);
            $where .= " and aicrm_account.sales_office in ('". $sales_office ."')";
        }*/

        if($params['social_channel'] != ''){
            $social_channel = explode(',', $params['social_channel']);
            $social_channel = implode("','", $social_channel);
            $where .= " and aicrm_account.social_channel in ('". $social_channel ."')";
        }

        if($params['firstpurchasedate_from'] != ''){
            $where .= " and aicrm_account.firstpurchasedate >= '".$params['firstpurchasedate_from'] ."'";
        }
        if($params['firstpurchasedate_to'] != ''){
            $where .= " and aicrm_account.firstpurchasedate <= '".$params['firstpurchasedate_to']."'";
        }

        if($params['lastpurchasedate_from'] != ''){
            $where .= " and aicrm_account.lastpurchasedate >= '". $params['lastpurchasedate_from'] ."'";
        }
        if($params['lastpurchasedate_to'] != ''){
            $where .= " and aicrm_account.lastpurchasedate <= '". $params['lastpurchasedate_to'] ."'";
        }

        if($params['total_remain_from'] != ''){
            $where .= " and aicrm_account.point_remaining >= '". $params['total_remain_from'] ."'";
        }
        if($params['total_remain_to'] != ''){
            $where .= " and aicrm_account.point_remaining <= '". $params['total_remain_to'] ."'";
        }

        if($params['purchase_date_diff_from'] != ''){
            $where .= " and aicrm_account.purchase_date_diff >= '". $params['purchase_date_diff_from'] ."'";
        }
        if($params['purchase_date_diff_to'] != ''){
            $where .= " and aicrm_account.purchase_date_diff <= '". $params['purchase_date_diff_to'] ."'";
        }

        if($params['accumulatepurchasefrequency_from'] != ''){
            $where .= " and aicrm_account.accumulatepurchasefrequency >= '". $params['accumulatepurchasefrequency_from'] ."'";
        }
        if($params['accumulatepurchasefrequency_to'] != ''){
            $where .= " and aicrm_account.accumulatepurchasefrequency <= '". $params['accumulatepurchasefrequency_to'] ."'";
        }

        if($params['accumulatepurchaseyearamount_from'] != ''){
            $where .= " and aicrm_account.accumulatepurchaseyearamount >= '". $params['accumulatepurchaseyearamount_from'] ."'";
        }
        if($params['accumulatepurchaseyearamount_to'] != ''){
            $where .= " and aicrm_account.accumulatepurchaseyearamount <= '". $params['accumulatepurchaseyearamount_to'] ."'";
        }

        if($params['email_consent'] != ''){
            $email_consent = explode(',', $params['email_consent']);
            $email_consent = implode("','", $email_consent);
            $where .= " and aicrm_account.email_consent in ('". $email_consent ."')";
        }

        return ['select'=>$select, 'from'=>$from, 'where'=>$where];

    }

    function conVertAgeToDate($age = null)
    {
        $date_default = date('d-m-Y');
        $date = explode('-', $date_default);
        $d = $date[0];
        $m = $date[1];
        $y = $date[2] - $age;
        $date_from = $y . '-' . $m . '-' . $d;
        return $date_from;
    }

    function getRelCheckquery($currentmodule = null, $returnmodule = null, $recordid = null)
    {
        $where_relquery = "";
        $params = [];

        if (($currentmodule == 'Accounts') && ($returnmodule == 'Smartemail')){

            $reltable = "aicrm_smartemail_accountsrel";
            $selectfield = "accountid";
            $condition = "where smartemailid = $recordid";
            $table = "aicrm_account";
            $field = "accountid";

        } else{

            $reltable = 'aicrm_smartsms_accountsrel';
            $selectfield = 'accountid';
            $condition = "WHERE smartsmsid = $recordid";
            $field = 'accountid';
            $table = 'aicrm_account';

        }


        if ($currentmodule != $returnmodule && $returnmodule != "") {
            $sql_select = "SELECT ".$selectfield." FROM ".$reltable." ".$condition;
            $query = $this->db->query($sql_select);
            $rows = $query->num_rows();
            $data = $query->result_array();
            if ($rows !=0){

                foreach ($data as $k => $v){

                    $skip_id[] = implode(",", $v);
                }

                $skipids = implode(",", $this->constructList($skip_id,'INTEGER'));

                if (count($skip_id) > 0) {
                    $where_relquery = "and ".$table.".".$field." not in (". $skipids .")";
                }

            }
        }
        return $where_relquery;
    }

    function constructList($array,$data_type)
    {
        $list= array();

        if(sizeof($array) > 0)
        {
            $i=0;
            foreach($array as $value)
            {

                if($data_type == "INTEGER")
                {
                    array_push($list, $value);
                }
                elseif($data_type == "VARCHAR")
                {
                    array_push($list, "'".$value."'");
                }
                $i++;
            }
        }
        return $list;
    }

    public function conVertDatetoDB($date = null){

        $date = explode('/', $date);
        $d = $date[0];
        $m = $date[1];
        $y = $date[2];
        $date_from = $y . '-' . $m . '-' . $d;

        return $date_from;
    }

    private function convertDate2DB($date)
    {
        if($date != ''){
            $date = str_replace('/', '-', $date);
            $date = date('Y-m-d', strtotime($date));
        }
        return $date;
    }

}