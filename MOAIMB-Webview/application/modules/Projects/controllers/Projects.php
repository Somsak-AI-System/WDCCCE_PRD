<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

class Projects extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->template->set_layout('template-project');
        $this->module = 'Projects';
        $this->curl->_filename = $this->module;
        $this->lang->load('ai', 'english');
        $this->load->load->config('config_module');
        $this->load->model('projects_model');
        if ($this->input->get('userid')) {
            $this->session->set_userdata('userID', $this->input->get('userid'));
        }

        $this->params = [
            'AI-API-KEY' => '1234',
            'module' => $this->module,
            'userid' => $this->session->userdata('userID'),
            'limit' => 20,
            'offset' => 0
        ];
        $this->load->config('api');
        $this->link_report_projects_temp = config_item('url_report_projects_temp');
        $this->link_report_projects = config_item('url_report_projects');
    }

    public function index()
    {
        $data = [];
        $this->template->build('index', $data);
    }

    public function create_web()
    {   
        $data = [];
        $blocks = [];

        $this->params['action'] = 'add';
        $this->params['crmid'] = '';
        // echo json_encode($this->params);
        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $this->params);
        // alert($result); exit;
        if ($result['alldata']['Type'] == 'S') {
            $blocks = $result['alldata']['data'][0]['result'];
        }
        $configmodule = $this->config->item('module');
        
        $data['configmodule'] = $configmodule;
        $data['module'] = $this->module;
        $data['blocks'] = $blocks;
        $data['return_module'] = @$this->input->get('return_module');
        $data['dealId'] = @$this->input->get('dealId');
        $data['dealNo'] = @$this->input->get('dealNo');
        $this->template->build('create_web', $data);
    }

    public function create()
    {   
        $data = [];
        $blocks = [];

        $this->params['action'] = 'add';
        $this->params['crmid'] = '';
        //echo json_encode($this->params); exit;
        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $this->params);
        //alert($result); exit;
        if ($result['alldata']['Type'] == 'S') {
            $blocks = $result['alldata']['data'][0]['result'];
        }
        $configmodule = $this->config->item('module');
        
        $data['configmodule'] = $configmodule;
        $data['module'] = $this->module;
        $data['blocks'] = $blocks;
        $this->template->build('create', $data);
    }

    public function checkDup()
    {
        $post = $this->input->post();
        $action = $post['action'];
        $params = $this->params;

        $params = array_merge($params, [
            'action' => $post['action'],
            'crmid' => @$post['crmid'],
            'projects_name' => $post['projects_name'],
            'project_location' => $post['project_location']

        ]);

        $result = $this->api_cms->serviceMaster('projects/checkDup', $this->module, $params);
        $res = $result['alldata'];

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    public function save()
    {
        $post = $this->input->post();
        $action = $post['action'];

        if (isset($post['crmid'])) {
            $this->params['crmid'] = $post['crmid'];
        }
        
        $post['project_open_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['project_open_date'])));
        $post['delivery_from_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['delivery_from_date'])));
        $post['delivery_to_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['delivery_to_date'])));
        $post['project_s_date'] = @$post['project_s_date'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $post['project_s_date']))) : '';
        $post['project_estimate_e_date'] = @$post['project_estimate_e_date'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $post['project_estimate_e_date']))) : '';
        $post['smownerid'] = $post['assign_to'] == 'user' ? $post['assign_to_user'] : $post['assign_to_group'];

        if(is_array(@$post['related_sales_person'])){
            $sales_person = implode(', ', @$post['related_sales_person']);
            $sales_person = str_replace(', ', ' |##| ', $sales_person);
            $post['related_sales_person'] = $sales_person;
        }

        // if($action == 'add' || $action == 'duplicate'){
        //     $post['projectorder_status'] = 'Interior Desinger/ Architect Bidding : DB';
        // }

        $insertData = array_diff_key($post, array_flip(['action', 'crmid', 'assign_to', 'assign_to_user', 'assign_to_group']));
        
        /*Owner*/
        $own = 0;
        $owner = array();
        for($i=1;$i<=$insertData['num_owner'];$i++) {

            if($insertData['deleted_owner'.$i] == 0 && $insertData['owner'.$i] != ''){
                $owner[$own]['sequence_no'] = ($own+1);
                $owner[$own]['accountid'] = $insertData['owner'.$i];
                
                $owner[$own]['owner_no'] = $insertData['owner_no'.$i];
                $owner[$own]['owner_name_th'] = $insertData['owner_name_th'.$i];
                $owner[$own]['owner_name_en'] = $insertData['owner_name_en'.$i];
                $owner[$own]['owner_group'] = $insertData['owner_group'.$i];
                $owner[$own]['owner_industry'] = $insertData['owner_industry'.$i];
                $owner[$own]['owner_grade'] = $insertData['owner_grade'.$i];

                $owner[$own]['contactid'] = $insertData['contact_owner'.$i];
                $owner[$own]['service_level_owner'] = $insertData['service_level_owner'.$i];
                $owner[$own]['sales_owner_name'] = $insertData['sales_owner_name'.$i];
                $owner[$own]['percen_com_sales_owner'] = $insertData['percen_com_sales_owner'.$i];
                $own++;
            }
            unset($insertData['deleted_owner'.$i],$insertData['owner'.$i],$insertData['contact_owner'.$i],$insertData['service_level_owner'.$i],$insertData['sales_owner_name'.$i],$insertData['percen_com_sales_owner'.$i]); 
        }
        $insertData['owner'] = $owner;

        /*Consultant*/
        $cons = 0;
        $consultant = array();
        for($i=1;$i<=$insertData['num_consultant'];$i++) {

            if($insertData['deleted_consultant'.$i] == 0 && $insertData['consultant'.$i] != ''){
                $consultant[$cons]['sequence_no'] = ($cons+1);
                $consultant[$cons]['accountid'] = $insertData['consultant'.$i];

                $consultant[$cons]['consultant_no'] = $insertData['consultant_no'.$i];
                $consultant[$cons]['consultant_name_th'] = $insertData['consultant_name_th'.$i];
                $consultant[$cons]['consultant_name_en'] = $insertData['consultant_name_en'.$i];
                $consultant[$cons]['consultant_group'] = $insertData['consultant_group'.$i];
                $consultant[$cons]['consultant_industry'] = $insertData['consultant_industry'.$i];
                $consultant[$cons]['consultant_grade'] = $insertData['consultant_grade'.$i];

                $consultant[$cons]['contactid'] = $insertData['contact_consultant'.$i];
                $consultant[$cons]['service_level_consultant'] = $insertData['service_level_consultant'.$i];
                $consultant[$cons]['sales_consultant_name'] = $insertData['sales_consultant_name'.$i];
                $consultant[$cons]['percen_com_sales_consultant'] = $insertData['percen_com_sales_consultant'.$i];
                $cons++;
            }
            unset($insertData['deleted_consultant'.$i],$insertData['consultant'.$i],$insertData['contact_consultant'.$i],$insertData['service_level_consultant'.$i],$insertData['sales_consultant_name'.$i],$insertData['percen_com_sales_consultant'.$i]); 
        }
        $insertData['consultant'] = $consultant;

        /*Architecture*/
        $arc = 0;
        $architecture = array();
        for($i=1;$i<=$insertData['num_architecture'];$i++) {

            if($insertData['deleted_architecture'.$i] == 0 && $insertData['architecture'.$i] != ''){
                $architecture[$arc]['sequence_no'] = ($arc+1);
                $architecture[$arc]['accountid'] = $insertData['architecture'.$i];

                $architecture[$arc]['architecture_no'] = $insertData['architecture_no'.$i];
                $architecture[$arc]['architecture_name_th'] = $insertData['architecture_name_th'.$i];
                $architecture[$arc]['architecture_name_en'] = $insertData['architecture_name_en'.$i];
                $architecture[$arc]['architecture_group'] = $insertData['architecture_group'.$i];
                $architecture[$arc]['architecture_industry'] = $insertData['architecture_industry'.$i];
                $architecture[$arc]['architecture_grade'] = $insertData['architecture_grade'.$i];

                $architecture[$arc]['contactid'] = $insertData['contact_architecture'.$i];
                $architecture[$arc]['service_level_architecture'] = $insertData['service_level_architecture'.$i];
                $architecture[$arc]['sales_architecture_name'] = $insertData['sales_architecture_name'.$i];
                $architecture[$arc]['percen_com_sales_architecture'] = $insertData['percen_com_sales_architecture'.$i];
                $arc++;
            }
            unset($insertData['deleted_architecture'.$i],$insertData['architecture'.$i],$insertData['contact_architecture'.$i],$insertData['service_level_architecture'.$i],$insertData['sales_architecture_name'.$i],$insertData['percen_com_sales_architecture'.$i]); 
        }
        $insertData['architecture'] = $architecture;
        
        /*Construction*/
        $const=0;
        $construction = array();
        for($i=1;$i<=$insertData['num_construction'];$i++) {

            if($insertData['deleted_const'.$i] == 0 && $insertData['construction'.$i] != ''){
                $construction[$const]['sequence_no'] = ($const+1);
                $construction[$const]['accountid'] = $insertData['construction'.$i];

                $construction[$const]['construction_no'] = $insertData['construction_no'.$i];
                $construction[$const]['construction_name_th'] = $insertData['construction_name_th'.$i];
                $construction[$const]['construction_name_en'] = $insertData['construction_name_en'.$i];
                $construction[$const]['construction_group'] = $insertData['construction_group'.$i];
                $construction[$const]['construction_industry'] = $insertData['construction_industry'.$i];
                $construction[$const]['construction_grade'] = $insertData['construction_grade'.$i];

                $construction[$const]['contactid'] = $insertData['contact_construction'.$i];
                $construction[$const]['service_level_construction'] = $insertData['service_level_construction'.$i];
                $construction[$const]['sales_construction_name'] = $insertData['sales_construction_name'.$i];
                $construction[$const]['percen_com_sales_construction'] = $insertData['percen_com_sales_construction'.$i];
                $const++;
            }
            unset($insertData['deleted_const'.$i],$insertData['construction'.$i],$insertData['contact_construction'.$i],$insertData['service_level_construction'.$i],$insertData['sales_construction_name'.$i],$insertData['percen_com_sales_construction'.$i]); 
        }
        $insertData['construction'] = $construction;

        /*Designer*/
        $des=0;
        $designer = array();
        for($i=1;$i<=$insertData['num_designer'];$i++) {

            if($insertData['deleted_designer'.$i] == 0 && $insertData['designer'.$i] != ''){
                $designer[$des]['sequence_no'] = ($des+1);
                $designer[$des]['accountid'] = $insertData['designer'.$i];

                $designer[$des]['designer_no'] = $insertData['designer_no'.$i];
                $designer[$des]['designer_name_th'] = $insertData['designer_name_th'.$i];
                $designer[$des]['designer_name_en'] = $insertData['designer_name_en'.$i];
                $designer[$des]['designer_group'] = $insertData['designer_group'.$i];
                $designer[$des]['designer_industry'] = $insertData['designer_industry'.$i];
                $designer[$des]['designer_grade'] = $insertData['designer_grade'.$i];

                $designer[$des]['contactid'] = $insertData['contact_designer'.$i];
                $designer[$des]['service_level_designer'] = $insertData['service_level_designer'.$i];
                $designer[$des]['sales_designer_name'] = $insertData['sales_designer_name'.$i];
                $designer[$des]['percen_com_sales_designer'] = $insertData['percen_com_sales_designer'.$i];
                $des++;
            }
            unset($insertData['deleted_designer'.$i],$insertData['designer'.$i],$insertData['contact_designer'.$i],$insertData['service_level_designer'.$i],$insertData['sales_designer_name'.$i],$insertData['percen_com_sales_designer'.$i]); 
        }
        $insertData['designer'] = $designer;

        /*Contractor*/
        $cont=0;
        $contractor = array();
        for($i=1;$i<=$insertData['num_contractor'];$i++) {

            if($insertData['deleted_contractor'.$i] == 0 && $insertData['contractor'.$i] != ''){
                $contractor[$cont]['sequence_no'] = ($cont+1);
                $contractor[$cont]['accountid'] = $insertData['contractor'.$i];

                $contractor[$cont]['contractor_no'] = $insertData['contractor_no'.$i];
                $contractor[$cont]['contractor_name_th'] = $insertData['contractor_name_th'.$i];
                $contractor[$cont]['contractor_name_en'] = $insertData['contractor_name_en'.$i];
                $contractor[$cont]['contractor_group'] = $insertData['contractor_group'.$i];
                $contractor[$cont]['contractor_industry'] = $insertData['contractor_industry'.$i];
                $contractor[$cont]['contractor_grade'] = $insertData['contractor_grade'.$i];

                $contractor[$cont]['contactid'] = $insertData['contact_contractor'.$i];
                $contractor[$cont]['service_level_contractor'] = $insertData['service_level_contractor'.$i];
                $contractor[$cont]['sales_contractor_name'] = $insertData['sales_contractor_name'.$i];
                $contractor[$cont]['percen_com_sales_contractor'] = $insertData['percen_com_sales_contractor'.$i];
                $cont++;
            }
            unset($insertData['deleted_contractor'.$i],$insertData['contractor'.$i],$insertData['contact_contractor'.$i],$insertData['contractor_type'.$i],$insertData['service_level_contractor'.$i],$insertData['sales_contractor_name'.$i],$insertData['percen_com_sales_contractor'.$i]); 
        }
        $insertData['contractor'] = $contractor;

        /*Sub Contractor*/
        $subcon=0;
        $subcontractor = array();
        for($i=1;$i<=$insertData['num_subcontractor'];$i++) {

            if($insertData['deleted_subcontractor'.$i] == 0 && $insertData['subcontractor'.$i] != ''){
                $subcontractor[$subcon]['sequence_no'] = ($subcon+1);
                $subcontractor[$subcon]['accountid'] = $insertData['subcontractor'.$i];
                
                $subcontractor[$subcon]['sub_contractor_no'] = $insertData['sub_contractor_no'.$i];
                $subcontractor[$subcon]['sub_contractor_name_th'] = $insertData['sub_contractor_name_th'.$i];
                $subcontractor[$subcon]['sub_contractor_name_en'] = $insertData['sub_contractor_name_en'.$i];
                $subcontractor[$subcon]['sub_contractor_group'] = $insertData['sub_contractor_group'.$i];
                $subcontractor[$subcon]['sub_contractor_industry'] = $insertData['sub_contractor_industry'.$i];
                $subcontractor[$subcon]['sub_contractor_grade'] = $insertData['sub_contractor_grade'.$i];

                $subcontractor[$subcon]['contactid'] = $insertData['contact_subcontractor'.$i];
                $subcontractor[$subcon]['service_level_sub_contractor'] = $insertData['service_level_sub_contractor'.$i];
                $subcontractor[$subcon]['sales_sub_contractor_name'] = $insertData['sales_sub_contractor_name'.$i];
                $subcontractor[$subcon]['percen_com_sales_sub_contractor'] = $insertData['percen_com_sales_sub_contractor'.$i];
                $subcon++;
            }
            unset($insertData['deleted_subcontractor'.$i],$insertData['subcontractor'.$i],$insertData['contact_subcontractor'.$i],$insertData['sub_contractor_type'.$i],$insertData['service_level_sub_contractor'.$i],$insertData['sales_sub_contractor_name'.$i],$insertData['percen_com_sales_sub_contractor'.$i]); 
        }
        $insertData['subcontractor'] = $subcontractor;

        /*Products*/
        $pro=0;
        $product = array();
        for($i=1;$i<=$insertData['num_products'];$i++) {

            if($insertData['deleted'.$i] == 0 && $insertData['productid'.$i] != ''){
                $product[$pro]['sequence_no'] = ($pro+1);
                $product[$pro]['productid'] = $insertData['productid'.$i];
                $product[$pro]['comment'] = $insertData['descriptions'.$i];
                $product[$pro]['product_brand'] = $insertData['product_brand'.$i];
                $product[$pro]['product_group'] = $insertData['product_group'.$i];
                $product[$pro]['accountid'] = $insertData['dealerid'.$i];

                //$product[$pro]['first_delivered_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $insertData['first_delivered_date'.$i])));
                //$product[$pro]['last_delivered_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $insertData['last_delivered_date'.$i])));

                $product[$pro]['estimated'] = $insertData['estimated'.$i];
                $product[$pro]['listprice'] = $insertData['listPrice'.$i];

                $product[$pro]['plan'] = @$insertData['plan'.$i];
                $product[$pro]['delivered'] = @$insertData['delivered'.$i];
                $product[$pro]['remain_on_hand'] = @$insertData['remain_on_hand'.$i];
                //plan
                //delivered
                //remain_on_hand
                
                $pro++;
            }
            unset($insertData['deleted'.$i],$insertData['productid'.$i],$insertData['descriptions'.$i],$insertData['product_brand'.$i],$insertData['product_group'.$i],$insertData['dealerid'.$i],$insertData['estimated'.$i],$insertData['listPrice'.$i]);
            //unset($insertData['deleted'.$i],$insertData['productid'.$i],$insertData['descriptions'.$i],$insertData['product_brand'.$i],$insertData['product_group'.$i],$insertData['dealerid'.$i],$insertData['first_delivered_date'.$i],$insertData['last_delivered_date'.$i],$insertData['estimated'.$i],$insertData['listPrice'.$i]); 
        }
        $insertData['product'] = $product;

        /*Compeitor*/
        $compe=0;
        $compeitor = array();
        for($i=1;$i<=$insertData['num_compeitor'];$i++) {

            if($insertData['deleted_com'.$i] == 0 && $insertData['competitorproductid'.$i] != ''){
                $compeitor[$compe]['sequence_no'] = ($compe+1);
                $compeitor[$compe]['competitorproductid'] = $insertData['competitorproductid'.$i];
                $compeitor[$compe]['competitorcomment'] = $insertData['descriptions_com'.$i];
                $compeitor[$compe]['competitor_brand'] = $insertData['competitor_brand'.$i];
                $compeitor[$compe]['comprtitor_product_group'] = $insertData['comprtitor_product_group'.$i];
                $compeitor[$compe]['comprtitor_product_size'] = $insertData['comprtitor_product_size'.$i];
                $compeitor[$compe]['comprtitor_product_thickness'] = $insertData['comprtitor_product_thickness'.$i];
                $compeitor[$compe]['comprtitor_estimated_unit'] = $insertData['comprtitor_estimated_unit'.$i];
                $compeitor[$compe]['competitor_price'] = $insertData['competitor_price'.$i];
                $compe++;
            }
            unset($insertData['deleted_com'.$i],$insertData['competitorproductid'.$i],$insertData['descriptions_com'.$i],$insertData['competitor_brand'.$i],$insertData['comprtitor_product_group'.$i],$insertData['comprtitor_product_size'.$i],$insertData['comprtitor_product_thickness'.$i],$insertData['comprtitor_estimated_unit'.$i],$insertData['competitor_price'.$i]); 
        }
        $insertData['compeitor'] = $compeitor;
        
        $this->params['action'] = $action;
        $this->params['data'] = [$insertData];
        
        // echo json_encode($this->params, JSON_UNESCAPED_UNICODE); exit;
        
        $result = $this->api_cms->serviceMaster('indexinsert/insert_content', $this->module, $this->params);
        if ($result['alldata']['Type'] == 'S') {
            echo json_encode([
                'userID' => $this->session->userdata('userID'),
                'status' => 'Success',
                'data' => $result['alldata']['data']
            ]);
        } else {
            echo json_encode([
                'userID' => $this->session->userdata('userID'),
                'status' => 'Error',
                'message' => $result['alldata']['Message']
            ]);
        }
    }

    public function getPopupList()
    {
        $post = $this->input->post();
        $moduleSelect = $post['moduleSelect'];
        $moduleSelect = $post['moduleSelect'];
        $offSet = $post['offSet'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['offset'] = $offSet;

        //Relate Field
        $this->params['relate_field']['parentid'] = @$post['relate_field_up'];

        if (isset($post['filter'])) $this->params['fieldname'] = $post['filter'];
        if (isset($post['selectfield'])) $this->params['selectfield'] = $post['selectfield'];
        
        // echo json_encode($this->params, JSON_UNESCAPED_UNICODE); exit;
        $result = $this->api_cms->serviceMaster('indexselect/list_select', $this->module, $this->params);

        $returnList = [];
        if ($result['alldata']['Type'] == 'S') {
            $returnList['total'] = $result['alldata']['total'];
            $returnList['offset'] = $result['alldata']['offset'];
            switch ($moduleSelect) {
                case 'Job':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'green';
                        $row['icon'] = 'briefcase';
                        $returnList['row'][] = $row;
                    }
                    break;
                case 'Accounts':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'cyan';
                        $row['icon'] = 'identification-card';
                        $returnList['row'][] = $row;
                    }
                    break;
                case 'Contacts':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'cyan';
                        $row['icon'] = 'user';
                        $returnList['row'][] = $row;
                    }
                    break;
                case 'Deal':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'cyan';
                        $row['icon'] = 'user';
                        $returnList['row'][] = $row;
                    }
                    break;
                case 'Products':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'blue';
                        $row['icon'] = 'cube';
                        $returnList['row'][] = $row;
                    }
                    break;
                case 'Competitorproduct':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'cyan';
                        $row['icon'] = 'user';
                        $returnList['row'][] = $row;
                    }
                    break;
            }
        }else{
            $returnList['offset'] = 0;
            $returnList['total'] = 0;
            $returnList['row'] = array();
        }
        
        echo json_encode($returnList);
    }

    public function getPopupListWeb()
    {
        $post = $this->input->post();
        $moduleSelect = $post['moduleSelect'];
        $this->params['search_module'] = $moduleSelect;
        //Relate Field
        $this->params['relate_field']['parentid'] = @$post['relate_field_up'];
        
        if (isset($post['filter'])) $this->params['name'] = $post['filter'];
        //echo json_encode($this->params); exit;
        $result = $this->api_cms->serviceMaster('indexselect/list_select', $this->module, $this->params);

        $returnList = [];
        if ($result['alldata']['Type'] == 'S') {
            switch ($moduleSelect) {
                case 'Job':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'green';
                        $row['icon'] = 'briefcase';
                        $returnList[] = $row;
                    }
                    break;
                case 'Accounts':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'cyan';
                        $row['icon'] = 'identification-card';
                        $returnList[] = $row;
                    }
                    break;
                case 'Contacts':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'cyan';
                        $row['icon'] = 'user';
                        $returnList[] = $row;
                    }
                    break;
                case 'Products':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'blue';
                        $row['icon'] = 'cube';
                        $returnList[] = $row;
                    }
                    break;
                case 'Competitorproduct':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'cyan';
                        $row['icon'] = 'user';
                        $returnList[] = $row;
                    }
                    break;
            }
        }

        echo json_encode($returnList);
    }

    public function getDetailSummary()
    {
        $post = $this->input->post();
        $moduleSelect = $post['moduleSelect'];
        $crmid = $post['crmID'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['acion'] = 'view';
        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $this->params);
        //alert($result); exit;
        $blocks = array();
        if ($result['alldata']['Type'] == 'S') {
            $blocks = $result['alldata']['data'][0]['result'];
        }
        
        echo json_encode($blocks);
    }

    public function getDetailList(){

        $post = $this->input->post();
        $moduleSelect = $post['moduleSelect'];
        $crmid = $post['crmID'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['acion'] = 'view';

        $result_item = $this->api_cms->serviceMaster('projects/get_detail_list', $this->module, $this->params);
        $itemList = [];
        if ($result_item['alldata']['Type'] == 'S') {
            $itemList = $result_item['alldata']['data']['itemList'];
        }

        echo json_encode($itemList);
    }

    public function view_web($crmID = '')
    {
        global $report_viewer_url;
        if ($crmID == '') {
            redirect('/Projects/create_web?userid=' . $this->session->userdata('userID'));
        }

        if ($this->input->get('userID')) {
            $userID = $this->input->get('userID');
            $this->params['userid'] = $this->input->get('userID');
        } else {
            $userID = $this->session->userdata('userID');
        }
        $display = $this->input->get('display');
        $data = [];

        $user = $this->api_cms->serviceMaster('users/get_role', $this->module, $this->params);
        $role = [];
        if ($user['alldata']['Type'] == 'S') {
            $role = $user['alldata']['data']['role'];
        }

        $this->params['crmid'] = $crmID;

        $this->load->database();
        $this->load->model("projects_model");
        $picklist_status = $this->projects_model->projectorder_status();
        $status = $this->projects_model->get_status($crmID);
        //alert($status);exit;
        $configmodule = $this->config->item('module');
        
        $data['configmodule'] = $configmodule;
        $data['userID'] = $userID;
        $data['rule'] = $role;
        $data['crmID'] = $crmID;
        $data['picklist_status'] = $picklist_status;
        $data['status'] = $status;
        $data['report_viewer_url'] = $report_viewer_url;
        $data['display'] = $display;
        
        $this->template->build('view_web', $data);
    }

    public function view($crmID = '')
    {
        if ($crmID == '') {
            redirect('/Projects/create?userid=' . $this->session->userdata('userID'));
        }

        if ($this->input->get('userID')) {
            $userID = $this->input->get('userID');
            $this->params['userid'] = $this->input->get('userID');
        } else {
            $userID = $this->session->userdata('userID');
        }

        $data = [];
        $user = $this->api_cms->serviceMaster('users/get_role', $this->module, $this->params);
        $role = [];
        if ($user['alldata']['Type'] == 'S') {
            $role = $user['alldata']['data']['role'];
        }
        $this->params['crmid'] = $crmID;

        $recordData = [];
        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $this->params);
        if ($result['alldata']['Type'] == 'S') {
            $blocks = $result['alldata']['data'][0]['result'];
            foreach ($blocks as $block) {
                foreach ($block['form'] as $field) {
                    $recordData[$field['columnname']] = $field['value'];
                }
            }
        }
        
        $result_item = $this->api_cms->serviceMaster('projects/get_detail_list', $this->module, $this->params);
        $itemList = [];
        if ($result_item['alldata']['Type'] == 'S') {
            $itemList = $result_item['alldata']['data']['itemList'];
        }        

        $this->load->database();
        $this->load->model("projects_model");
        $status = $this->projects_model->get_status($crmID);
        $data['userID'] = $userID;
        $data['rule'] = $role;
        $data['module'] = $this->module;
        $data['crmID'] = $crmID;
        $data['status'] = $status;
        $data['recordData'] = $recordData;
        $data['itemList'] = $itemList;
        $data['blocks'] = $blocks;
        $data['url'] = $this->link_report_projects . '&projectsid=' . $crmID . '&__format=pdf';
        //alert($data); exit;
        $this->template->build('view', $data);
    }

    public function edit_web($crmID = '')
    {
        if ($crmID == '') {
            redirect('/Projects/create_web?userid=' . $this->session->userdata('userID'));
        }
        $data = [];
        
        $this->params['action'] = 'edit';
        $this->params['crmid'] = $crmID;
        // echo json_encode($this->params); exit;
        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $this->params);
        if ($result['alldata']['Type'] == 'S') {
            $blocks = $result['alldata']['data'][0]['result'];
        }
        
        $result_item = $this->api_cms->serviceMaster('projects/get_detail_list', $this->module, $this->params);
        $itemList = [];
        if ($result_item['alldata']['Type'] == 'S') {
            $itemList = $result_item['alldata']['data']['itemList'];
        }
        
        $configmodule = $this->config->item('module');
        $data['configmodule'] = $configmodule;

        $data['userID'] = $this->session->userdata('userID');
        $data['crmID'] = $crmID;
        $data['module'] = $this->module;
        $data['blocks'] = $blocks;
        $data['itemList'] = $itemList;

        $flagedit = @$this->input->get('flagedit');
        $data['flagedit'] = $flagedit;
        
        $this->template->build('edit_web', $data);
    }

    public function edit($crmID = '')
    {
        if ($crmID == '') {
            redirect('/Projects/create?userid=' . $this->session->userdata('userID'));
        }
        $data = [];

        $this->params['action'] = 'edit';
        $this->params['crmid'] = $crmID;
        // echo json_encode($this->params); exit;
        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $this->params);
        if ($result['alldata']['Type'] == 'S') {
            $blocks = $result['alldata']['data'][0]['result'];
        }
        
        $result_item = $this->api_cms->serviceMaster('projects/get_detail_list', $this->module, $this->params);
        $itemList = [];
        if ($result_item['alldata']['Type'] == 'S') {
            $itemList = $result_item['alldata']['data']['itemList'];
        }
        
        $configmodule = $this->config->item('module');
        $data['configmodule'] = $configmodule;

        $data['userID'] = $this->session->userdata('userID');
        $data['crmID'] = $crmID;
        $data['module'] = $this->module;
        $data['blocks'] = $blocks;
        $data['itemList'] = $itemList;
        
        $this->template->build('edit', $data);
    }

    public function duplicate_web($crmID = '')
    {
        if ($crmID == '') {
            redirect('/Projects/create_web?userid=' . $this->session->userdata('userID'));
        }
        $data = [];

        $this->params['action'] = 'duplicate';
        $this->params['crmid'] = $crmID;
        // echo json_encode($this->params); exit;
        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $this->params);
        if ($result['alldata']['Type'] == 'S') {
            $blocks = $result['alldata']['data'][0]['result'];
        }
        
        $result_item = $this->api_cms->serviceMaster('projects/get_detail_list', $this->module, $this->params);
        $itemList = [];
        if ($result_item['alldata']['Type'] == 'S') {
            $itemList = $result_item['alldata']['data']['itemList'];
        }
        
        $configmodule = $this->config->item('module');
        $data['configmodule'] = $configmodule;

        $data['userID'] = $this->session->userdata('userID');
        $data['crmID'] = $crmID;
        $data['module'] = $this->module;
        $data['blocks'] = $blocks;
        $data['itemList'] = $itemList;
        
        $this->template->build('duplicate_web', $data);
    }

    public function duplicate($crmID = '')
    {
        if ($crmID == '') {
            redirect('/Projects/create?userid=' . $this->session->userdata('userID'));
        }
        $data = [];

        $this->params['action'] = 'duplicate';
        $this->params['crmid'] = $crmID;
        // echo json_encode($this->params); exit;
        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $this->params);
        if ($result['alldata']['Type'] == 'S') {
            $blocks = $result['alldata']['data'][0]['result'];
        }
        
        $result_item = $this->api_cms->serviceMaster('projects/get_detail_list', $this->module, $this->params);
        $itemList = [];
        if ($result_item['alldata']['Type'] == 'S') {
            $itemList = $result_item['alldata']['data']['itemList'];
        }
        
        $configmodule = $this->config->item('module');
        $data['configmodule'] = $configmodule;

        $data['userID'] = $this->session->userdata('userID');
        $data['crmID'] = $crmID;
        $data['module'] = $this->module;
        $data['blocks'] = $blocks;
        $data['itemList'] = $itemList;
        
        $this->template->build('duplicate', $data);
    }

    public function updateStatus()
    {
        $post = $this->input->post();

        $crmID = $post['crmid'];
        $action = $post['action'];
        $projectorder_status = $post['projectorder_status'];

        $params = $this->params;
        $params['crmid'] = $crmID;
        $params['action'] = $action;
        $params['data'][0]['projectorder_status'] = $projectorder_status;
        if(isset($post['cancelReason']) && $post['cancelReason'] != ''){
            $params['data'][0]['other_reason'] = $post['cancelReason'];
        }

        $result = $this->api_cms->serviceMaster('Projects/update_status', $this->module, $params);
        
        if ($result['alldata']['Type'] == 'S') {
            echo json_encode([
                'userID' => $this->session->userdata('userID'),
                'status' => 'Success',
                'data' => $result['alldata']['data']
            ]);
        } else {
            echo json_encode([
                'userID' => $this->session->userdata('userID'),
                'status' => 'Error',
                'message' => $result['alldata']['Message']
            ]);
        }
    }

    public function getStatus(){
        $post = $this->input->post();
        $moduleSelect = $post['moduleSelect'];
        $crmid = $post['crmID'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['acion'] = 'view';

        $result = $this->api_cms->serviceMaster('Projects/getStatus', $this->module, $this->params);

        $response = array();
        if ($result['alldata']['Type'] == 'S') {
            $response['value'] = $result['alldata']['value'];
            $response['status'] = $result['alldata']['status'];
        }

        echo json_encode($response);
    }

    public function getComments(){
        $post = $this->input->post();
        $moduleSelect = $post['moduleSelect'];
        $crmid = $post['crmID'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['acion'] = 'view';

        $result = $this->api_cms->serviceMaster('Projects/getComments', $this->module, $this->params);

        $comment = array();
        if ($result['alldata']['Type'] == 'S') {
            $comment = $result['alldata']['comment'];
        }
        echo json_encode($comment);
    }

    public function getTimeline(){
        $post = $this->input->post();
        $moduleSelect = $post['moduleSelect'];
        $crmid = $post['crmID'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['acion'] = 'view';

        $result = $this->api_cms->serviceMaster('Projects/getTimeline', $this->module, $this->params);

        if ($result['alldata']['Type'] == 'S') {
            echo json_encode([
                'timeline' => $result['alldata']['timeline'],
                'users' => $result['alldata']['users'],
                'field' => $result['alldata']['field']
            ]);
        } else {
            echo json_encode([
                'timeline' => array(),
                'users' => array(),
                'field' => array()
            ]);
        }
    }

    public function addComment()
    {
        $post = $this->input->post();
        $crmID = $post['crmid'];
        $action = $post['action'];
        $message = $post['message'];
        $projectorder_status = $post['projectorder_status'];

        $params = $this->params;
        $params['crmid'] = $crmID;
        $params['action'] = $action;
        $params['projectorder_status'] = $projectorder_status;
        $params['message'] = $message;

        $result = $this->api_cms->serviceMaster('Projects/addcomment', $this->module, $params);
        $returnData = ['status' => false];

        if ($result['alldata']['Type'] == 'S') {
            $returnData = ['status' => true];
            $returnData['data'] = $result['alldata']['comment'];
        } else {
            $returnData['msg'] = $result['alldata']['Message'];
        }

        echo json_encode($returnData);
    }

    public function getProductPlan(){
        $post = $this->input->post();
        $moduleSelect = $post['moduleSelect'];
        $crmid = $post['crmid'];
        $productid = $post['productid'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['productid'] = $productid;
        $this->params['acion'] = 'view';

        $result = $this->api_cms->serviceMaster('Projects/getProductPlan', $this->module, $this->params);

        $response = array();
        if ($result['alldata']['Type'] == 'S') {
            $response['data'] = $result['alldata']['data'];
            $response['plan'] = $result['alldata']['plan'];
        }
        echo json_encode($response);
    }

    public function savePlan(){
        $post = $this->input->post();
        $action = $post['action'];

        if (isset($post['crmid'])) {
            $this->params['crmid'] = $post['crmid'];
        }

        $post['product_plan_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['product_plan_date'])));        
        $insertData['projectid'] = $post['projectid'];
        $insertData['productid'] = $post['productid'];
        $insertData['product_plan_date'] = $post['product_plan_date'];
        $insertData['product_qty'] = $post['product_qty'];
        $insertData['lineitem'] = $post['lineitem'];
        $insertData['Planlineitem_id'] = @$post['Planlineitem_id'];
        if($post['Planlineitem_id'] != ''){
            $action = 'edit';
        }
        $this->params['action'] = $action;
        $this->params['data'] = [$insertData];
        //echo json_encode($this->params); exit;
        $result = $this->api_cms->serviceMaster('Projects/insert_ProductPlan', $this->module, $this->params);
        
        if ($result['alldata']['Type'] == 'S') {
            echo json_encode([
                'userID' => $this->session->userdata('userID'),
                'status' => 'Success',
                'data' => $result['alldata']['data'],
                'plan' => $result['alldata']['plan']
            ]);
        } else {
            echo json_encode([
                'userID' => $this->session->userdata('userID'),
                'status' => 'Error',
                'message' => $result['alldata']['Message']
            ]);
        }
    }

    public function delProductPlan(){
        $post = $this->input->post();
        $action = $post['action'];
        $moduleSelect = $post['moduleSelect'];
        $lineitem_id = $post['lineitem_id'];
        $productid = $post['productid'];
        $lineitem = $post['lineitem'];
        if (isset($post['crmid'])) {
            $this->params['crmid'] = $post['crmid'];
        }
        
        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $post['crmid'];
        $this->params['productid'] = $productid;
        $this->params['lineitem_id'] = $lineitem_id;
        $this->params['lineitem'] = $lineitem;
        $this->params['acion'] = $action;

        $result = $this->api_cms->serviceMaster('Projects/delProductPlan', $this->module, $this->params);

        if ($result['alldata']['Type'] == 'S') {
            echo json_encode([
                'userID' => $this->session->userdata('userID'),
                'status' => 'Success',
                'data' => $result['alldata']['data'],
                'qty' => $result['alldata']['qty']
            ]);
        } else {
            echo json_encode([
                'userID' => $this->session->userdata('userID'),
                'status' => 'Error',
                'message' => $result['alldata']['Message']
            ]);
        }
    }

    public function getProductDelivered(){
        $post = $this->input->post();
        $moduleSelect = $post['moduleSelect'];
        $crmid = $post['crmid'];
        $productid = $post['productid'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['productid'] = $productid;
        $this->params['acion'] = 'view';

        $result = $this->api_cms->serviceMaster('Projects/getProductDelivered', $this->module, $this->params);

        $response = array();
        if ($result['alldata']['Type'] == 'S') {
            $response['data'] = $result['alldata']['data'];
            $response['deliver'] = $result['alldata']['deliver'];
        }
        echo json_encode($response);
    }

    public function saveDelivered()
    {
        $post = $this->input->post();
        $action = $post['action'];

        if (isset($post['crmid'])) {
            $this->params['crmid'] = $post['crmid'];
        }
        
        $post['product_delivered_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['product_delivered_date'])));        

        $insertData['projectid'] = $post['projectid'];
        $insertData['productid'] = $post['productid'];
        $insertData['accountid'] = $post['accountid'];
        $insertData['product_delivered_date'] = $post['product_delivered_date'];
        $insertData['product_delivered_qty'] = $post['product_delivered_qty'];
        $insertData['lineitem'] = $post['lineitem'];
        $insertData['deliveredlineitem_id'] = @$post['deliveredlineitem_id'];
        if($post['deliveredlineitem_id'] != ''){
            $action = 'edit';
        }
        $this->params['action'] = $action;
        $this->params['data'] = [$insertData];
        
        $result = $this->api_cms->serviceMaster('Projects/insert_ProductDelivered', $this->module, $this->params);
        
        if ($result['alldata']['Type'] == 'S') {
            echo json_encode([
                'userID' => $this->session->userdata('userID'),
                'status' => 'Success',
                'data' => $result['alldata']['data'],
                'deliver' => $result['alldata']['deliver'],
                'delivered_date' => $result['alldata']['delivered_date'][0]
            ]);
        } else {
            echo json_encode([
                'userID' => $this->session->userdata('userID'),
                'status' => 'Error',
                'message' => $result['alldata']['Message']
            ]);
        }
    }

    public function delProductDelivered(){
        $post = $this->input->post();
        $action = $post['action'];
        $moduleSelect = $post['moduleSelect'];
        $lineitem_id = $post['lineitem_id'];
        $productid = $post['productid'];
        $lineitem = $post['lineitem'];

        if (isset($post['crmid'])) {
            $this->params['crmid'] = $post['crmid'];
        }
        
        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $post['crmid'];
        $this->params['productid'] = $productid;
        $this->params['lineitem_id'] = $lineitem_id;
        $this->params['lineitem'] = $lineitem;
        $this->params['acion'] = $action;
        //echo json_encode($this->params); exit;
        $result = $this->api_cms->serviceMaster('Projects/delProductDelivered', $this->module, $this->params);
        //$result['alldata']['delivered_date'][0]
        //alert($result['alldata']['delivered_date']['']); exit;
        if ($result['alldata']['Type'] == 'S') {
            echo json_encode([
                'userID' => $this->session->userdata('userID'),
                'status' => 'Success',
                'data' => $result['alldata']['data'],
                'qty' => $result['alldata']['qty'],
                'clear' => $result['alldata']['clear'],
                'delivered_date' => $result['alldata']['delivered_date'],
            ]);
        } else {
            echo json_encode([
                'userID' => $this->session->userdata('userID'),
                'status' => 'Error',
                'message' => $result['alldata']['Message']
            ]);
        }
    }

    public function getRelatedvisit(){
        $post = $this->input->post();
        $moduleSelect = 'Calendar';
        $crmid = $post['crmID'];
        $offSet = $post['offSet'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['event_id'] = $crmid;
        $this->params['acion'] = 'view';
        $this->params['offset'] = $offSet;

        $result = $this->api_cms->serviceMaster('Projects/getRelated_Activity', $this->module, $this->params);

        $returnList = [];
        $returnList['Type'] = $result['alldata']['Type'];

        if ($result['alldata']['Type'] == 'S') {
            $returnList['total'] = $result['alldata']['total'];
            $returnList['offset'] = $result['alldata']['offset'];
            $returnList['row'] = $result['alldata']['data'];
        }else{
            $returnList['offset'] = 0;
            $returnList['total'] = 0;
            $returnList['row'] = array();
        }
        
        echo json_encode($returnList);
    }

    public function getRelatedquotation(){
        $post = $this->input->post();
        $moduleSelect = 'Quotation';
        $crmid = $post['crmID'];
        $offSet = $post['offSet'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['projectsid'] = $crmid;
        $this->params['acion'] = 'view';
        $this->params['offset'] = $offSet;

        $result = $this->api_cms->serviceMaster('Projects/getRelated_Quotation', $this->module, $this->params);

        $returnList = [];
        $returnList['Type'] = $result['alldata']['Type'];

        if ($result['alldata']['Type'] == 'S') {
            $returnList['total'] = $result['alldata']['total'];
            $returnList['offset'] = $result['alldata']['offset'];
            $returnList['row'] = $result['alldata']['data'];
        }else{
            $returnList['offset'] = 0;
            $returnList['total'] = 0;
            $returnList['row'] = array();
        }
        
        echo json_encode($returnList);
    }

    public function getRelatedDocuments(){
        $post = $this->input->post();
        $moduleSelect = 'Documents';
        $crmid = $post['crmID'];
        $offSet = $post['offSet'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['event_id'] = $crmid;
        $this->params['acion'] = 'view';
        $this->params['offset'] = $offSet;

        $result = $this->api_cms->serviceMaster('Projects/getRelated_Documents', $this->module, $this->params);

        $returnList = [];
        $returnList['Type'] = $result['alldata']['Type'];

        if ($result['alldata']['Type'] == 'S') {
            $returnList['total'] = $result['alldata']['total'];
            $returnList['offset'] = $result['alldata']['offset'];
            $returnList['row'] = $result['alldata']['data'];
        }else{
            $returnList['offset'] = 0;
            $returnList['total'] = 0;
            $returnList['row'] = array();
        }
        
        echo json_encode($returnList);
    }

    public function getRelatedPricelist(){
        $post = $this->input->post();
        $moduleSelect = 'PriceList';
        $crmid = $post['crmID'];
        $offSet = $post['offSet'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['event_id'] = $crmid;
        $this->params['acion'] = 'view';
        $this->params['offset'] = $offSet;

        $result = $this->api_cms->serviceMaster('Projects/getRelated_Pricelist', $this->module, $this->params);

        $returnList = [];
        $returnList['Type'] = $result['alldata']['Type'];

        if ($result['alldata']['Type'] == 'S') {
            $returnList['total'] = $result['alldata']['total'];
            $returnList['offset'] = $result['alldata']['offset'];
            $returnList['row'] = $result['alldata']['data'];
        }else{
            $returnList['offset'] = 0;
            $returnList['total'] = 0;
            $returnList['row'] = array();
        }
        
        echo json_encode($returnList);
    }

    public function getRelatedSamplerequisition(){
        $post = $this->input->post();
        $moduleSelect = 'Samplerequisition';
        $crmid = $post['crmID'];
        $offSet = $post['offSet'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['event_id'] = $crmid;
        $this->params['acion'] = 'view';
        $this->params['offset'] = $offSet;

        $result = $this->api_cms->serviceMaster('Projects/getRelated_Samplerequisition', $this->module, $this->params);

        $returnList = [];
        $returnList['Type'] = $result['alldata']['Type'];

        if ($result['alldata']['Type'] == 'S') {
            $returnList['total'] = $result['alldata']['total'];
            $returnList['offset'] = $result['alldata']['offset'];
            $returnList['row'] = $result['alldata']['data'];
        }else{
            $returnList['offset'] = 0;
            $returnList['total'] = 0;
            $returnList['row'] = array();
        }
        
        echo json_encode($returnList);
    }

    public function getRelatedExpenses(){
        $post = $this->input->post();
        $moduleSelect = 'Expense';
        $crmid = $post['crmID'];
        $offSet = $post['offSet'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['event_id'] = $crmid;
        $this->params['acion'] = 'view';
        $this->params['offset'] = $offSet;

        $result = $this->api_cms->serviceMaster('Projects/getRelated_Expenses', $this->module, $this->params);

        $returnList = [];
        $returnList['Type'] = $result['alldata']['Type'];

        if ($result['alldata']['Type'] == 'S') {
            $returnList['total'] = $result['alldata']['total'];
            $returnList['offset'] = $result['alldata']['offset'];
            $returnList['row'] = $result['alldata']['data'];
        }else{
            $returnList['offset'] = 0;
            $returnList['total'] = 0;
            $returnList['row'] = array();
        }
        
        echo json_encode($returnList);
    }

    public function getRelatedQuestionnaire(){
        $post = $this->input->post();
        $moduleSelect = 'Questionnaire';
        $crmid = $post['crmID'];
        $offSet = $post['offSet'];

        $this->params['search_module'] = $moduleSelect;
        $this->params['crmid'] = $crmid;
        $this->params['event_id'] = $crmid;
        $this->params['acion'] = 'view';
        $this->params['offset'] = $offSet;

        $result = $this->api_cms->serviceMaster('Projects/getRelated_Questionnaire', $this->module, $this->params);

        $returnList = [];
        $returnList['Type'] = $result['alldata']['Type'];

        if ($result['alldata']['Type'] == 'S') {
            $returnList['total'] = $result['alldata']['total'];
            $returnList['offset'] = $result['alldata']['offset'];
            $returnList['row'] = $result['alldata']['data'];
        }else{
            $returnList['offset'] = 0;
            $returnList['total'] = 0;
            $returnList['row'] = array();
        }
        
        echo json_encode($returnList);
    }
    
    public function viewReport($crmID = '')
    {
        $get = $this->input->get();

        if ($crmID == '') {
            redirect('/Projects/create?userid=' . $this->session->userdata('userID'));
        }

        if ($this->input->get('userID')) {
            $userID = $this->input->get('userID');
            $this->params['userid'] = $this->input->get('userID');
        } else {
            $userID = $this->session->userdata('userID');
        }

        $data = [];

        if ($get['action'] == 'viewTempReport') {
            $data['url'] = $this->link_report_projects_temp . htmlspecialchars("&projectsid=") . $crmID . '&__format=pdf';
        } else {
            $data['url'] = $this->link_report_projects . htmlspecialchars("&projectsid=") . $crmID . '&__format=pdf';
        }
        $data['crmid'] = $crmID;
        $data['userid'] = $userID;
        $data['action'] = $get['action'];

        $this->template->build('form-preview-report', $data);
    }

    public function delete()
    {
        $post = $this->input->post();
        $crmID = $post['crmID'];

        $params = $this->params;
        $params['data'] = [["deleted" => "1"]];
        $params['crmid'] = $crmID;
        $params['action'] = "edit";

        $result = $this->api_cms->serviceMaster('indexinsert/insert_content', $this->module, $params);

        $returnData = ['status' => false];
        if ($result['alldata']['Type'] == 'S') {
            $returnData = ['status' => true];
        } else {
            $returnData['msg'] = $result['alldata']['Message'];
        }

        echo json_encode($returnData);
    }
}
