<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Point extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        echo 'point index';
    }

    public function formCreate()
    {
        $post = $this->input->post();
        $id = $post['id'];

        $data = $post;
        $gradeSetting = [];
        $sql = $this->db->get('aicrm_account_grade');
        $grades = $sql->result_array();
        $data['grades'] = $grades;

        if ($id != '') {
            $sql = $this->db->get_where('aicrm_account_grade_setting', ['id' => $id]);
            $gradeSetting = $sql->row_array();
        }
        $data['gradeSetting'] = $gradeSetting;

        $this->load->view('formGradeSetting', $data);
    }

    public function setUp2()
    {
        $post = $this->input->post();
        $id = $post['id'];
        $action = $post['action'];

        $data = $post;
        $setUppoint2 = [];
        
        $this->db->select("aicrm_products.product_brand");
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_products.productid');
        $this->db->group_by('product_brand');
        $this->db->order_by('product_brand','asc');
        $this->db->where('product_brand != "" and aicrm_crmentity.deleted = 0');
        $sql1 = $this->db->get('aicrm_products');
        $product_brand = $sql1->result_array();
        $data['product_brand'] = $product_brand;
        //material_type
        $this->db->select("aicrm_products.material_type");
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_products.productid');
        $this->db->group_by('material_type');
        $this->db->order_by('material_type','asc');
        $this->db->where('material_type != "" and aicrm_crmentity.deleted = 0');
        $sql2 = $this->db->get('aicrm_products');
        $material_type = $sql2->result_array();
        $data['material_type'] = $material_type;
        //producttype
        $this->db->order_by('aicrm_producttype.producttype','asc');
        $this->db->where('aicrm_producttype.presence = 1');
        $sql3 = $this->db->get('aicrm_producttype');
        $producttype = $sql3->result_array();
        $data['producttype'] = $producttype;
        //productcategory
        $this->db->order_by('aicrm_productcategory.productcategory','asc');
        $this->db->where('aicrm_productcategory.presence = 1');
        $sql4 = $this->db->get('aicrm_productcategory');
        $productcategory = $sql4->result_array();
        $data['productcategory'] = $productcategory;

        if ($id != '') {
            $sql = $this->db->get_where('aicrm_point_config_type2', ['id' => $id]);
            $setUppoint2 = $sql->row_array();
            $setUppoint2['startdate'] = $this->convertDate($setUppoint2['startdate'], 'd/m/Y H:i');
            $setUppoint2['enddate'] = $this->convertDate($setUppoint2['enddate'], 'd/m/Y H:i');
        }
        $data['setUppoint2'] = $setUppoint2;

        $this->load->view('Addsetup2', $data);

    }

    public function SerialByProduct()
    {
        $post = $this->input->post();
        $id = $post['id'];
        $action = $post['action'];

        $data = $post;
        $recordData = [];

        $sql_brand = $this->db->get_where('aicrm_cf_1731', ['cf_1731 !='=>'--None--']);
        $brand = $sql_brand->result_array();
        $data['brands'] = $brand;

        $sql_material_group = $this->db->get_where('aicrm_cf_1728', ['cf_1728 !='=>'--None--']);
        $material_group = $sql_material_group->result_array();
        $data['materials_group'] = $material_group;

        $sql_category = $this->db->get_where('aicrm_cf_1729', ['cf_1729 !='=>'--None--']);
        $category = $sql_category->result_array();
        $data['categories'] = $category;

        $sql_product_hierarchy	 = $this->db->get_where('aicrm_cf_1730', ['cf_1730 !='=>'--None--']);
        $product_hierarchy = $sql_product_hierarchy->result_array();
        $data['products_hierarchy'] = $product_hierarchy;

        $sql = $this->db->query('SELECT DISTINCT(aicrm_serial.market) as market 
        FROM aicrm_serial
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_serial.serialid
        WHERE aicrm_serial.market != ""
        AND aicrm_crmentity.deleted = 0');
        $market = $sql->result_array();
        $data['markets'] = $market;

        $sql = $this->db->query('SELECT aicrm_campaign.campaignid, aicrm_campaign.campaignname
        FROM aicrm_campaign
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_campaign.campaignid
        WHERE aicrm_crmentity.deleted = 0');
        $campaign = $sql->result_array();
        $data['campaigns'] = $campaign;

        if ($id != '') {
            $sql = $this->db->get_where('aicrm_point_config_serial_by_product', ['id' => $id]);
            $recordData = $sql->row_array();
            $recordData['startdate'] = $this->convertDate($recordData['startdate'], 'd/m/Y H:i');
            $recordData['enddate'] = $this->convertDate($recordData['enddate'], 'd/m/Y H:i');

            $recordData['brand'] = explode(',', $recordData['brand']);
            $recordData['materialgroup'] = explode(',', $recordData['materialgroup']);
            $recordData['category'] = explode(',', $recordData['category']);
            $recordData['producthierachy'] = explode(',', $recordData['producthierachy']);
            $recordData['campaign'] = explode(',', $recordData['campaign']);
            $recordData['market'] = explode(',', $recordData['market']);
        }

        // foreach($recordData as $key => $value){
        //     if (in_array($key, ['category'])){
        //         $value = $this->explodeSharp($value);

        //         $new_category = $value;

        //     }
        // }

        $data['recordData'] = $recordData;

        $this->load->view('FormSetupSerialByProduct', $data);
    }

    private function getCampaignName($str){
        $sql = $this->db->query("SELECT GROUP_CONCAT(campaignname) AS campaignname FROM aicrm_campaign WHERE FIND_IN_SET(campaignid, '".$str."')");
        $rs = $sql->row_array();

        return $rs['campaignname'];
    }

    public function setUp3()
    {
        $post = $this->input->post();
        $id = $post['id'];
        $action = $post['action'];

        $data = $post;
        $setUppoint3 = [];

        $sql = $this->db->get_where('aicrm_accounttype', ['presence'=>1]);
        $accounttype = $sql->result_array();
        $data['accounttype'] = $accounttype;

        if ($id != '') {
            $sql = $this->db->get_where('aicrm_point_config_type3', ['id' => $id]);
            $setUppoint3 = $sql->row_array();
            $setUppoint3['startdate'] = $this->convertDate($setUppoint3['startdate'], 'd/m/Y H:i');
            $setUppoint3['enddate'] = $this->convertDate($setUppoint3['enddate'], 'd/m/Y H:i');
        }
        $data['setUppoint3'] = $setUppoint3;
        $data['new_category'] = $new_category;

        $this->load->view('Addsetup3', $data);

    }

    public function setUp4()
    {
        $post = $this->input->post();
        $id = $post['id'];
        $action = $post['action'];

        $data = $post;
        $setUppoint4 = [];

        if ($id != '') {
            $sql = $this->db->get_where('aicrm_point_config_type4', ['id' => $id]);
            $setUppoint4 = $sql->row_array();
            $setUppoint4['startdate'] = $this->convertDate($setUppoint4['startdate'], 'd/m/Y H:i');
            $setUppoint4['enddate'] = $this->convertDate($setUppoint4['enddate'], 'd/m/Y H:i');
        }

        $sql = $this->db->get_where('aicrm_accountgrade', ['presence'=>1]);
        $accountgrade = $sql->result_array();
        $data['accountgrade'] = $accountgrade;

        $data['setUppoint4'] = $setUppoint4;
        $this->load->view('Addsetup4', $data);
    }

    public function setUp5()
    {
        $post = $this->input->post();
        $id = $post['id'];
        $action = $post['action'];

        $data = $post;
        $setUppoint5 = [];

        if ($id != '') {
            $sql = $this->db->get_where('aicrm_point_config_type5', ['id' => $id]);
            $setUppoint5 = $sql->row_array();
            $setUppoint5['startdate'] = $this->convertDate($setUppoint5['startdate'], 'd/m/Y H:i');
            $setUppoint5['enddate'] = $this->convertDate($setUppoint5['enddate'], 'd/m/Y H:i');
        }

        $this->db->select("aicrm_salesinvoice.main_channel");
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_salesinvoice.salesinvoiceid');
        $this->db->group_by('main_channel');
        $this->db->order_by('main_channel','asc');
        $this->db->where('main_channel != "" and aicrm_crmentity.deleted = 0');
        $sql1 = $this->db->get('aicrm_salesinvoice');
        $main_channel = $sql1->result_array();
        $data['main_channel'] = $main_channel;

        $this->db->select("aicrm_salesinvoice.sub_channel");
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_salesinvoice.salesinvoiceid');
        $this->db->group_by('sub_channel');
        $this->db->order_by('sub_channel','asc');
        $this->db->where('sub_channel != "" and aicrm_crmentity.deleted = 0');
        $sql2 = $this->db->get('aicrm_salesinvoice');
        $sub_channel = $sql2->result_array();
        $data['sub_channel'] = $sub_channel;

        $data['setUppoint5'] = $setUppoint5;
        $this->load->view('Addsetup5', $data);
    }

    public function setUp6()
    {
        $post = $this->input->post();
        $id = $post['id'];
        $action = $post['action'];

        $data = $post;
        $setUppoint6 = [];

        if ($id != '') {
            $sql = $this->db->get_where('aicrm_point_config_type6', ['id' => $id]);
            $setUppoint6 = $sql->row_array();
            $setUppoint6['startdate'] = $this->convertDate($setUppoint6['startdate'], 'd/m/Y H:i');
            $setUppoint6['enddate'] = $this->convertDate($setUppoint6['enddate'], 'd/m/Y H:i');
        }

        $this->db->select("aicrm_salesinvoice.main_channel");
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_salesinvoice.salesinvoiceid');
        $this->db->group_by('main_channel');
        $this->db->order_by('main_channel','asc');
        $this->db->where('main_channel != "" and aicrm_crmentity.deleted = 0');
        $sql1 = $this->db->get('aicrm_salesinvoice');
        $main_channel = $sql1->result_array();
        $data['main_channel'] = $main_channel;

        $this->db->select("aicrm_salesinvoice.sub_channel");
        $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_salesinvoice.salesinvoiceid');
        $this->db->group_by('sub_channel');
        $this->db->order_by('sub_channel','asc');
        $this->db->where('sub_channel != "" and aicrm_crmentity.deleted = 0');
        $sql2 = $this->db->get('aicrm_salesinvoice');
        $sub_channel = $sql2->result_array();
        $data['sub_channel'] = $sub_channel;

        $data['setUppoint6'] = $setUppoint6;
        $this->load->view('Addsetup6', $data);
    }

    public function saveSetpoint2()
    {
        $post = $this->input->post();
        //alert($post); exit;
        $action = $post['action'];
        if($action == 'Add'){
            $result = $this->db->insert('aicrm_point_config_type2', [
                'product_brand' => $post['product_brand'],
                'material_type' => $post['material_type'],
                'producttype' => $post['producttype'],
                'productcategory' => $post['productcategory'],
                'bahtperpoint' => $post['bahtperpoint'],
                'startdate' => $this->convertDate($post['startdate'], 'Y-m-d H:i:s'),
                'enddate' => $this->convertDate($post['enddate'], 'Y-m-d H:i:s'),
                'minimum' => $post['minimum'],
            ]);
        }else{
            $result = $this->db->update('aicrm_point_config_type2', [
                'product_brand' => $post['product_brand'],
                'material_type' => $post['material_type'],
                'producttype' => $post['producttype'],
                'productcategory' => $post['productcategory'],
                'bahtperpoint' => $post['bahtperpoint'],
                'startdate' => $this->convertDate($post['startdate'], 'Y-m-d H:i:s'),
                'enddate' => $this->convertDate($post['enddate'], 'Y-m-d H:i:s'),
                'minimum' => $post['minimum'],
            ], ['id'=>$post['id']]);
        }

        $return['status'] = 'error';
        if($result){
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    public function saveSerialByProduct()
    {
        $post = $this->input->post();
        $action = $post['action'];

        $startdate = $post['startdate'];
        $new_startdate = $this->convertDate($startdate, 'Y-m-d H:i:s');

        $enddate = $post['enddate'];
        $new_enddate = $this->convertDate($enddate, 'Y-m-d H:i:s');

        if($action == 'Add'){
            $result = $this->db->insert('aicrm_point_config_serial_by_product', [
                'brand' => $post['brand'],
                'materialgroup' => $post['material_group'],
                'category' => $post['category'],
                'producthierachy' => $post['product_hierarchy'],
                'productcode' => $post['productcode'],
                'campaign' => $post['campaign'],
                'market' => $post['market'],
                'startdate' => $new_startdate,
                'enddate' => $new_enddate,
                'number' => $post['number'],
                'operator' => $post['operator'],
            ]);
        }else if($action == 'Edit'){
            $result = $this->db->update('aicrm_point_config_serial_by_product', [
                'brand' => $post['brand'],
                'materialgroup' => $post['material_group'],
                'category' => $post['category'],
                'producthierachy' => $post['product_hierarchy'],
                'productcode' => $post['productcode'],
                'campaign' => $post['campaign'],
                'market' => $post['market'],
                'startdate' => $new_startdate,
                'enddate' => $new_enddate,
                'number' => $post['number'],
                'operator' => $post['operator'],
            ], ['id'=>$post['id']]);
        }else if($action == 'Delete'){
            $this->db->delete('aicrm_point_config_serial_by_product', ['id'=>$post['id']]);
            $result = true;
        }

        $return['status'] = 'error';
        if($result){
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    public function saveSetpoint3()
    {
        $post = $this->input->post();
        $action = $post['action'];

        $startdate = $post['startdate'];
        $new_startdate = $this->convertDate($startdate, 'Y-m-d H:i:s');

        $enddate = $post['enddate'];
        $new_enddate = $this->convertDate($enddate, 'Y-m-d H:i:s');

        $category = $post['category'];
        $category_with_sharp = $this->conVertCommaToSharp($category);

        if($action == 'Add'){
            $result = $this->db->insert('aicrm_point_config_type3', [
                'accounttype' => $post['accounttype'],
                'startdate' => $new_startdate,
                'enddate' => $new_enddate,
                'number' => $post['number'],
                'operator' => $post['operator'],
                'minimum' => $post['minimum']
            ]);
        }else{
            $result = $this->db->update('aicrm_point_config_type3', [
                'accounttype' => $post['accounttype'],
                'startdate' => $new_startdate,
                'enddate' => $new_enddate,
                'number' => $post['number'],
                'operator' => $post['operator'],
                'minimum' => $post['minimum']
            ], ['id'=>$post['id']]);

            // echo $this->db->last_query(); exit;
        }

        $return['status'] = 'error';
        if($result){
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    public function saveSetpoint4()
    {
        $post = $this->input->post();
        $action = $post['action'];

        $date = $post['specialday'];
        $newdate = $this->conVertDatetoDB($date);

        if($action == 'Add'){
            $result = $this->db->insert('aicrm_point_config_type4', [
                'accountgrade' => $post['accountgrade'],
                'startdate' => $this->convertDate($post['startdate'], 'Y-m-d H:i:s'),
                'enddate' => $this->convertDate($post['enddate'], 'Y-m-d H:i:s'),
                'number' => $post['number'],
                'operator' => $post['operator'],
                'minimum' => $post['minimum']
            ]);
        }else{
            $result = $this->db->update('aicrm_point_config_type4', [
                'accountgrade' => $post['accountgrade'],
                'startdate' => $this->convertDate($post['startdate'], 'Y-m-d H:i:s'),
                'enddate' => $this->convertDate($post['enddate'], 'Y-m-d H:i:s'),
                'number' => $post['number'],
                'operator' => $post['operator'],
                'minimum' => $post['minimum']
            ], ['id'=>$post['id']]);
        }

        $return['status'] = 'error';
        if($result){
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    public function saveSetpoint5()
    {
        $post = $this->input->post();
        $action = $post['action'];

        /*$date = $post['specialday'];
        $newdate = $this->conVertDatetoDB($date);*/

        if($action == 'Add'){
            $result = $this->db->insert('aicrm_point_config_type5', [
                'main_channel' => $post['main_channel'],
                'sub_channel' => $post['sub_channel'],
                'startdate' => $this->convertDate($post['startdate'], 'Y-m-d H:i:s'),
                'enddate' => $this->convertDate($post['enddate'], 'Y-m-d H:i:s'),
                'number' => $post['number'],
                'operator' => $post['operator'],
                'minimum' => $post['minimum']
            ]);
        }else{
            $result = $this->db->update('aicrm_point_config_type5', [
                'main_channel' => $post['main_channel'],
                'sub_channel' => $post['sub_channel'],
                'startdate' => $this->convertDate($post['startdate'], 'Y-m-d H:i:s'),
                'enddate' => $this->convertDate($post['enddate'], 'Y-m-d H:i:s'),
                'number' => $post['number'],
                'operator' => $post['operator'],
                'minimum' => $post['minimum']
            ], ['id'=>$post['id']]);
        }

        $return['status'] = 'error';
        if($result){
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    public function saveSetpoint6()
    {
        $post = $this->input->post();
        $action = $post['action'];

        if($action == 'Add'){
            $result = $this->db->insert('aicrm_point_config_type6', [
                'main_channel' => $post['main_channel'],
                'sub_channel' => $post['sub_channel'],
                'startdate' => $this->convertDate($post['startdate'], 'Y-m-d H:i:s'),
                'enddate' => $this->convertDate($post['enddate'], 'Y-m-d H:i:s'),
                'number' => $post['number'],
                'operator' => $post['operator']
            ]);
        }else{
            $result = $this->db->update('aicrm_point_config_type6', [
                'main_channel' => $post['main_channel'],
                'sub_channel' => $post['sub_channel'],
                'startdate' => $this->convertDate($post['startdate'], 'Y-m-d H:i:s'),
                'enddate' => $this->convertDate($post['enddate'], 'Y-m-d H:i:s'),
                'number' => $post['number'],
                'operator' => $post['operator']
            ], ['id'=>$post['id']]);
        }

        $return['status'] = 'error';
        if($result){
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    public function setUpGetData()
    {
        $post = $this->input->post();
        $data = $post;

        if ($data['setup'] == 'Setup 2'){
            $sql = $this->db->get('aicrm_point_config_type2');

            $dataSetUp2 = $sql->result_array();
            $newData = [];
            foreach($dataSetUp2 as $row){
                $row['startdate'] = $this->convertDate($row['startdate'], 'd/m/Y H:i');
                $row['enddate'] = $this->convertDate($row['enddate'], 'd/m/Y H:i');
                $newData[] = $row;
            }
            $return_data = $newData;

        }elseif ($data['setup'] == 'Setup 3'){
            $sql = $this->db->get('aicrm_point_config_type3');

            $dataSetUp3= $sql->result_array();
            $newData = [];
            foreach($dataSetUp3 as $row){
                $row['startdate'] = $this->convertDate($row['startdate'], 'd/m/Y H:i');
                $row['enddate'] = $this->convertDate($row['enddate'], 'd/m/Y H:i');
                $newData[] = $row;
            }
            $return_data = $newData;

        }elseif ($data['setup'] == 'Setup 4'){
            $sql = $this->db->get('aicrm_point_config_type4');

            $dataSetUp4 = $sql->result_array();
            $return_data = $dataSetUp4;
        
        }elseif ($data['setup'] == 'Setup 5'){
            $sql = $this->db->get('aicrm_point_config_type5');

            $dataSetUp5 = $sql->result_array();
            $return_data = $dataSetUp5;

        }elseif ($data['setup'] == 'Setup 6'){
            $sql = $this->db->get('aicrm_point_config_type6');

            $dataSetUp6 = $sql->result_array();
            $return_data = $dataSetUp6;
            
        }elseif ($data['setup'] == 'Serial By Product'){
            $sql = $this->db->get('aicrm_point_config_serial_by_product');

            $rs = $sql->result_array();
            $newData = [];
            foreach($rs as $row){
                $row['startdate'] = $this->convertDate($row['startdate'], 'd/m/Y H:i');
                $row['enddate'] = $this->convertDate($row['enddate'], 'd/m/Y H:i');
                $row['campaign'] = $row['campaign'] != '' ? $this->getCampaignName($row['campaign']):$row['campaign'];
                $newData[] = $row;
            }
            $return_data = $newData;
        }

        $response_data = [
            'Type' => 'S',
            'Message' => 'Success',
            'data' => $return_data
        ];

        echo json_encode($response_data);

    }

    public function saveSetup()
    {
        $post = $this->input->post();
        $type = $post['form'];
        if ($type == 'setup1') {

            $result = $this->db->update('aicrm_point_config',
                ['type1' => $post['point_regis']],
                ['id' => 1]
            );

        } elseif ($type == 'setup7'){

            $result = $this->db->update('aicrm_point_config',
                ['type5' => $post['birth_month'], 'type5_operator'=>$post['type5Operator']],
                ['id' => 1]
            );

        }else{

            $result = $this->db->update('aicrm_point_config',
                ['type8' => $post['friend_get_friend']],
                ['id' => 1]
            );

        }

        $return['status'] = 'error';

        if ($result) {
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    public function deleteSetup2()
    {
        $post = $this->input->post();
        $result = $this->db->delete('aicrm_point_config_type2', ['id' => $post['id']]);

        $return['status'] = 'error';
        if ($result) {
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    public function deleteSetup3(){
        $post = $this->input->post();
        $result = $this->db->delete('aicrm_point_config_type3', ['id' => $post['id']]);

        $return['status'] = 'error';
        if ($result) {
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    public function deleteSetup4(){
        $post = $this->input->post();
        $result = $this->db->delete('aicrm_point_config_type4', ['id' => $post['id']]);

        $return['status'] = 'error';
        if ($result) {
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    public function deleteSetup5(){
        $post = $this->input->post();
        $result = $this->db->delete('aicrm_point_config_type5', ['id' => $post['id']]);

        $return['status'] = 'error';
        if ($result) {
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    public function deleteSetup6(){
        $post = $this->input->post();
        $result = $this->db->delete('aicrm_point_config_type6', ['id' => $post['id']]);

        $return['status'] = 'error';
        if ($result) {
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    private function convertDate($date, $format='Y-m-d', $lang='')
    {
        if($date == '') return '';
        if($date == '0000-00-00' || $date == '0000-00-00 00:00:00') return '';
        $date = str_replace('/', '-', $date);
        $date = date($format, strtotime($date));

        return $date;
    }

    public function conVertDatetoDB($date = null){

        $date = explode('/', $date);
        $d = $date[0];
        $m = $date[1];
        $y = $date[2];
        $date_from = $y . '-' . $m . '-' . $d;

        return $date_from;

    }

    public function conVertDatetoView($date = null){

        $date = explode('-', $date);
        $y = $date[0];
        $m = $date[1];
        $d = $date[2];
        $date_from = $d . '/' . $m . '/' . $y;

        return $date_from;

    }

    public function conVertCommaToSharp($data = null){

        $newdata = str_replace(',', '|##|', $data);
        return $newdata;
    }

    public function conVertSharpToComma($data = null){

        $newdata = str_replace('|##|', ',', $data);
        return $newdata;
    }

    public function explodeSharp($data = null){

        $newdata =[];
        $data_temp = explode('|##|',$data);
        $newdata = $data_temp;
        return $newdata;

    }

}