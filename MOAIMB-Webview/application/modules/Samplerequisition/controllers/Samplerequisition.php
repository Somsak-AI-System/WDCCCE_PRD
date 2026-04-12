<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Samplerequisition extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->template->set_layout('template-master');
        $this->module = 'Samplerequisition';
        $this->curl->_filename = $this->module;
        $this->lang->load('ai', 'english');

        if ($this->input->get('userid')) {
            $this->session->set_userdata('userID', $this->input->get('userid'));
        }

        $this->params = [
            'AI-API-KEY' => '1234',
            'module' => $this->module,
            'userid' => $this->session->userdata('userID'),
            'limit' => 100,
            'offset' => 0
        ];

        $this->load->config('api');
    }

    public function index()
    {
        $data = [];
        $this->template->build('index', $data);
    }

    public function create()
    {
        $get = $this->input->get();   
        $return_module = @$get['return_module'];
        $related_id = @$get['related_id'];

        $data = [];
        $blocks = [];

        $this->params['action'] = 'add';
        $this->params['crmid'] = '';

        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $this->params);

        if ($result['alldata']['Type'] == 'S') {
            $blocks = $result['alldata']['data'][0]['result'];
        }

        if ($this->input->get('userID')) {
            $userID = $this->input->get('userID');
            $this->params['userid'] = $this->input->get('userID');
        } else {
            $userID = $this->session->userdata('userID');
        }
        $a_user = [];
        $user = $this->api_cms->serviceMaster('users/getuser', $this->module, $this->params);
        if ($user['alldata']['Type'] == 'S') {
            $a_user = $user['alldata']['data'][0];
        }

        $data['module'] = $this->module;
        $data['blocks'] = $blocks;
        $data['a_user'] = $a_user;
        $this->template->build('create', $data);
    }

    public function edit($crmID = '')
    {
        if ($crmID == '') {
            redirect('/Samplerequisition/create?userid=' . $this->session->userdata('userID'));
        }

        $data = [];

        $this->params['action'] = 'edit';
        $this->params['crmid'] = $crmID;
        // echo json_encode($this->params); exit;
        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $this->params);
        if ($result['alldata']['Type'] == 'S') {
            $blocks = $result['alldata']['data'][0]['result'];
        }

        $data['userID'] = $this->session->userdata('userID');
        $data['crmID'] = $crmID;
        $data['module'] = $this->module;
        $data['blocks'] = $blocks;
        $this->template->build('edit', $data);
    }

    public function createProduct($crmID = '')
    {
        if ($crmID == '') {
            redirect('/Samplerequisition/create?userid=' . $this->session->userdata('userID'));
        }

        $this->session->set_userdata('crmID', $crmID);
        $data = [];
        $data['crmID'] = $crmID;
        $data['userID'] = $this->session->userdata('userID');

        $params = $this->params;
        $params['crmID'] = $crmID;

        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $params);

        $recordData = [];
        if ($result['alldata']['Type'] == 'S') {
            $blocks = $result['alldata']['data'][0]['result'];

            foreach ($blocks as $block) {
                foreach ($block['form'] as $field) {
                    $recordData[$field['columnname']] = $field['value'];
                }
            }
        }
        
        $result = $this->api_cms->serviceMaster('Samplerequisition/get_product_list', $this->module, $params);
   
        $itemList = [];
        if ($result['alldata']['Type'] == 'S') {
            $itemList = $result['alldata']['data']['itemList'];
        }
        $data['itemList'] = $itemList;
        $data['recordData'] =  $recordData;

        $this->template->build('create-product', $data);
    }

    public function getProductList()
    {
        $post = $this->input->post();
        $crmID = $post['crmID'];

        $params = $this->params;
        $params['crmID'] = $crmID;
        $result = $this->api_cms->serviceMaster('samplerequisition/get_product_list', $this->module, $params);
        
        $status = false;
        $returnData = [];
        $itemList = [];
        if ($result['alldata']['Type'] == 'S') {
            $status = true;
            $rowData = $result['alldata']['data']['rowData'];
            $itemData = $result['alldata']['data']['itemList'];

            foreach ($itemData as $item) {
                $type = strtolower($item['setype']);
                switch ($type) {
                    case 'products':
                        $name = $item['productname'];
                        $no = $item['product_no'];
                        $uom = $item['sr_product_unit'];
                        $remark = $item['comment'];
                        $stock = (int)$item['stockqty'];
                        break;
                    case 'service':
                        $name = $item['service_name'];
                        $no = $item['service_no'];
                        $uom = '';
                        $remark = '';
                        $stock = 1;
                        break;
                    case 'sparepart':
                        $name = $item['sparepart_name'];
                        $no = $item['sparepart_no'];
                        $uom = '';
                        $remark = '';
                        $stock = (int)$item['sparepart_stock_qty'];
                        break;
                }

                $itemList[] = [
                    'amount' => (int)$item['amount_of_sample'],
                    'amount_of_purchase' => (int)$item['amount_of_purchase'],
                    'id' => $item['productid'],
                    'no' => $no,
                    'name' => $name,
                    'uom' => $uom,
                    'price' => 0,
                    'price_display' => number_format(0, 2),
                    'stock' => $stock,
                    'remark' => $remark,
                    'type' => $type,
                    'listprice' =>0,
                    'product_finish' => $item['sr_finish'] != '' ? $item['sr_finish'] : 'Standard',
                    'product_size_mm' => $item['sr_size_mm'] != '' ? $item['sr_size_mm'] : 'Standard',
                    'product_thinkness' => $item['sr_thickness_mm'] != '' ? $item['sr_thickness_mm'] : 'Standard',
                ];
            }

            $returnData = [
                'grandTotal' => (float)$rowData['total_amount_of_sample'],
                'grandTotalUnit' => (float)@$rowData['total_amount_of_purchase'],
                'itemList' => $itemList
            ];
        };
        //alert($returnData); exit;
        echo json_encode(['status' => $status, 'returnData' => $returnData]);
    }

    public function Revise($crmID = '')
    {
        $post = $this->input->post();
        $crmID = $post['crmID'];
        $userID = $post['userID'];
        $action = $post['status'];
        $data = [];
        
        $this->params['action'] = '';
        $this->params['crmid'] = $crmID;

        $params = $this->params;
        $params['crmID'] = $crmID;
        
        $result = $this->api_cms->serviceMaster('samplerequisition/get_product_list', $this->module, $params);
        
        $itemList = [];
        $recordData = [];
        if ($result['alldata']['Type'] == 'S') {
            $itemList = $result['alldata']['data']['itemList'];
            $recordData = $result['alldata']['data']['rowData'];
        }
        $data['itemList'] = $itemList;
        $sample_no_rev = $this->api_cms->serviceMaster('samplerequisition/get_samplerequisition_no_rev', $this->module, $this->params);

        if ($action == "revise") {
            $recordData['revised_no'] = $sample_no_rev['alldata']['data']['dataRev']['data_rev'];
            $recordData['ref_sample_request'] = $sample_no_rev['alldata']['data']['dataRev']['data_sample_no'];

            $recordData['samplerequisition_status'] = "Create";
            $recordData['date'] = date('Y-m-d');
            $recordData['cancel_reason'] = "";
            $recordData['rejected_reason'] = "";
            $recordData['smownerid'] = $userID;
        }

        // alert($recordData);exit;
        $insertData = array_diff_key($recordData, array_flip(['action', 'crmid', 'assign_to', 'assign_to_user', 'assign_to_group']));
        $this->params['action'] = $action;
        $this->params['data'] = [$insertData];
        
        //echo json_encode($this->params); exit;
        $result = $this->api_cms->serviceMaster('indexinsert/insert_content', $this->module, $this->params);
        
        if ($result['alldata']['Type'] == 'S') {
            $crmID = $result['alldata']['data']['Crmid'];
            $this->params['crmid'] = $crmID;
            $params['crmID'] = $crmID;

            $productItemList = [];
            foreach ($data['itemList'] as $x => $item) {
                $x = $x + 1;
                $productItemList[] = [
                    'id' => $crmID,
                    'productid' => $item['productid'],
                    'product_name' => @$item['product_name'],
                    'sequence_no' => $x,
                    'comment' => $item['comment'],
                    'sr_finish' => $item['sr_finish'],
                    'sr_size_mm' => $item['sr_size_mm'],
                    'sr_thickness_mm' => $item['sr_thickness_mm'],
                    'sr_product_unit' => $item['sr_product_unit'],
                    'amount_of_sample' => $item['amount_of_sample'],
                    'amount_of_purchase' => $item['amount_of_purchase'],
                ];
            }

            $params['itemList'] = $productItemList;

            $result = $this->api_cms->serviceMaster('samplerequisition/save_product_list', $this->module, $params);

            $result = $this->api_cms->serviceMaster('samplerequisition/get_product_list', $this->module, $params);

            echo json_encode([
                'userID' => $userID,
                'status' => 'Success',
                'crmID' => $result['alldata']['data']['Crmid'],
                'data' => $result['alldata']['data']
            ]);
        } else {
            echo json_encode([
                'userID' => $this->session->userdata('userID'),
                'status' => 'Error',
                'message' => $result['alldata']['Message']
            ]);
        }

        // $data['userID'] = $this->session->userdata('userID');
        // $data['crmID'] = $crmID;
        // $data['module'] = $this->module;
        // $data['blocks'] = $blocks;
        // $this->template->build('edit', $data);
    }

    public function view($crmID = '')
    {
        if ($crmID == '') {
            redirect('/Samplerequisition/create?userid=' . $this->session->userdata('userID'));
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

        $this->params['action'] = 'edit';
        $this->params['crmid'] = $crmID;

        $approver = $this->api_cms->serviceMaster('samplerequisition/get_approver', $this->module, $this->params);
        $approvers = [];
        $isApprover = false;
        if ($approver['alldata']['Type'] == 'S') {
            $approvers = $approver['alldata']['data']['Approvers'];
            foreach ($approvers as $row) {
                if ($row['appstatus'] == '0') {
                    if ($row['userid'] == $userID) {
                        $isApprover = true;
                    }
                    break;
                }
            }
        }

        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $this->params);
        
        $recordData = [];
        if ($result['alldata']['Type'] == 'S') {
            $blocks = $result['alldata']['data'][0]['result'];

            foreach ($blocks as $block) {
                foreach ($block['form'] as $field) {
                    $recordData[$field['columnname']] = $field['value'];
                }
            }
        }

        $params = $this->params;
        $params['crmID'] = $crmID;
        $result = $this->api_cms->serviceMaster('samplerequisition/get_product_list', $this->module, $params);

        $itemList = [];
        $priceInfo = [];
        if ($result['alldata']['Type'] == 'S') {
            $rowData = $result['alldata']['data']['rowData'];
            $itemData = $result['alldata']['data']['itemList'];

            foreach ($itemData as $item) {
                $type = strtolower($item['setype']);

                switch ($type) {
                    case 'products':
                        $name = $item['productname'];
                        $no = $item['product_no'];
                        $stock = (int)$item['stockqty'];
                        $uom = $item['sr_product_unit'];
                        break;
                    case 'service':
                        $name = $item['service_name'];
                        $no = $item['service_no'];
                        $stock = 1;
                        $uom = '';
                        break;
                    case 'sparepart':
                        $name = $item['sparepart_name'];
                        $no = $item['sparepart_no'];
                        $stock = (int)$item['sparepart_stock_qty'];
                        $uom = '';
                        break;
                }

                $itemList[] = [
                    'amount' => (int)$item['amount_of_sample'],
                    'amount_of_purchase' => (int)$item['amount_of_purchase'],
                    'id' => $item['productid'],
                    'no' => $no,
                    'name' => $name,
                    'price' => 0,
                    'price_display' => number_format(0, 2),
                    'stock' => $stock,
                    'remark' => $item['comment'],
                    'type' => $type,
                    'uom' => $uom,
                    'listprice' => 0,
                    'product_finish' => $item['sr_finish'],
                    'product_size_mm' => $item['sr_size_mm'],
                    'product_thinkness' => $item['sr_thickness_mm'],
                ];
            }

            $priceInfo = [
                'grandTotal' => number_format((float)$rowData['total_amount_of_sample'], 0),
                'grandTotalUnit' => number_format((float)@$rowData['total_amount_of_purchase'], 0),
                'itemList' => $itemList
            ];
        };

        if($rowData['samplerequisition_status'] !='ปิดการขาย' && $rowData['samplerequisition_status'] !='อนุมัติใบเสนอราคา'){
            $watermark = 1;
        }else{
            $watermark = 0;
        }

        $data['userID'] = $userID;
        $data['rule'] = $role;
        $data['crmID'] = $crmID;
        $data['watermark'] = $watermark;
        $data['module'] = $this->module;
        $data['recordData'] = $recordData;
        $data['approvers'] = $approvers;
        $data['isApprover'] = $isApprover;
        $data['blocks'] = $blocks;
        $data['priceInfo'] = $priceInfo;
        $data['url'] = '';
        $this->template->build('view', $data);
    }

    public function save()
    {
        $post = $this->input->post();

        $action = $post['action'];

        if (isset($post['crmid'])) {
            $this->params['crmid'] = $post['crmid'];
        }

        $post['approver'] = isset($post['approver']) ? implode(' |##| ', $post['approver']) : '';
        $post['approver2'] = isset($post['approver2']) ? implode(' |##| ', $post['approver2']) : '';
        $post['approver3'] = isset($post['approver3']) ? implode(' |##| ', $post['approver3']) : '';
        $post['f_approver'] = isset($post['f_approver']) ? implode(' |##| ', $post['f_approver']) : '';
        $post['smownerid'] = $post['assign_to'] == 'user' ? $post['assign_to_user'] : $post['assign_to_group'];

        $post['date'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['date'])));
        $post['purchasing_period'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['purchasing_period'])));
        $post['samplerequisition_status'] = "Create";
        $post['sending_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['sending_date'])));
        $post['confirm_sending_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['confirm_sending_date'])));

        $insertData = array_diff_key($post, array_flip(['action', 'crmid', 'assign_to', 'assign_to_user', 'assign_to_group']));

        $this->params['action'] = $action;
        $this->params['data'] = [$insertData];
       
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

    public function saveItemList()
    {
        $post = $this->input->post();
        $crmID = $this->session->userdata('crmID');

        $params = $this->params;
        $params['crmID'] = $crmID;

        $updateData = [
            'total_amount_of_sample' => $post['grandTotalUnit'],
            'total_amount_of_purchase' => $post['grandTotal']
        ];

        $productItemList = [];
        foreach ($post['itemList'] as $x => $item) {
            $productItemList[] = [
                'id' => $crmID,
                'productid' => $item['id'],
                'product_name' => $item['name'],
                'sequence_no' => $x,
                'comment' => $item['remark'],
                'sr_finish' => $item['product_finish'],
                'sr_size_mm' => $item['product_size_mm'],
                'sr_thickness_mm' => $item['product_thinkness'],
                'sr_product_unit' => $item['uom'],
                'amount_of_sample' => $item['amount'],
                'amount_of_purchase' => $item['amount_of_purchase'],
            ];
        }

        $params['updateData'] = $updateData;
        $params['itemList'] = $productItemList;
        
        //echo json_encode($params); exit;
        
        $result = $this->api_cms->serviceMaster('samplerequisition/save_product_list', $this->module, $params);
        if ($result['alldata']['Type'] == 'S') {
            $returnData = [
                'status' => true,
                'crmID' => $result['alldata']['data']['Crmid']
            ];
        } else {
            $returnData = [
                'status' => false
            ];
        }

        echo json_encode($returnData);
    }

    public function saveTempItemList()
    {
        $post = $this->input->post();
        $crmID = $this->session->userdata('crmID');

        $params = $this->params;
        $params['crmID'] = $crmID;

        $priceType = '';
        if ($post['vatType'] == 'ไม่รวมภาษี') {
            $priceType = 'Exclude Vat';
        } else if ($post['vatType'] == 'รวมภาษี') {
            $priceType = 'Include Vat';
        }

        $updateData = [
            'discount_amount' => $post['discountType'] == '2' ? $post['discountTypeAmount'] : '',
            'discount_percent' => $post['discountType'] == '1' ? $post['discountTypeAmount'] : '',
            'discountTotal_final' => $post['discountAmount'],
            'total_after_discount' => $post['totalAfterDiscount'],
            'pricetype' => $priceType,
            'total_before_tax' => $post['totalBeforeVat'],
            'tax1' => $post['vatPercentage'],
            'total_after_tax' => $post['totalAfterVat'],
            'tax_final' => $post['netVat'],
            'subtotal' => $post['netTotal'],
            'total' => $post['grandTotal']
        ];

        $productItemList = [];
        if (!empty($post['itemList'])) {
            foreach ($post['itemList'] as $x => $item) {
                $productItemList[] = [
                    'id' => $crmID,
                    'productid' => $item['id'],
                    'product_name' => $item['name'],
                    'sequence_no' => $x,
                    'quantity' => $item['amount'],
                    'listprice' => $item['price'],
                    'comment' => $item['remark']
                ];
            }
        }

        $params['updateData'] = $updateData;
        $params['itemList'] = $productItemList;

        $result = $this->api_cms->serviceMaster('samplerequisition/save_product_list_temp', $this->module, $params);
        if ($result['alldata']['Type'] == 'S') {
            $returnData = [
                'status' => true,
                'crmID' => $result['alldata']['data']['Crmid'],
                'url' => @$this->link_report_quotation_temp .$result['alldata']['data']['Crmid'] . '&__format=pdf'
            ];
        } else {
            $returnData = [
                'status' => true
            ];
        }
        echo json_encode($returnData);
    }

    public function getPopupList()
    {
        $post = $this->input->post();
        $moduleSelect = $post['moduleSelect'];
        $this->params['search_module'] = $moduleSelect;
        if (isset($post['filter'])) $this->params['name'] = $post['filter'];
        
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
                case 'Deal':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'green';
                        $row['icon'] = 'handshake';
                        $returnList[] = $row;
                    }
                    break;
                case 'HelpDesk':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'green';
                        $row['icon'] = 'bag-simple';
                        $returnList[] = $row;
                    }
                    break;
                case 'Projects':
                    foreach ($result['alldata']['data'] as $row) {
                        $row['moduleSelect'] = $moduleSelect;
                        $row['color'] = 'orange';
                        $row['icon'] = 'projector-screen-chart';
                        $returnList[] = $row;
                    }
                    break;
            }
        }

        echo json_encode($returnList);
    }

    public function getItemList()
    {
        $post = $this->input->post();
        $params = $this->params;
        $params['search_module'] = $post['module'];
        $params['offset'] = $post['offset'];
        $params['limit'] = 20;
        $params['name'] = @$post['searchKey'];
        //echo json_encode($params); exit;
        $result = $this->api_cms->serviceMaster('indexselect/list_select', $this->module, $params);
        $total = 0;
        $resultList = [];

        if ($result['alldata']['Type'] == 'S') {
            $total = $result['alldata']['total'];
            if (is_array($result['alldata']['data'])) {
                $result = $result['alldata']['data'];

                foreach ($result as $row) {
                    $row['price_display'] = $row['price'] != '' ? number_format($row['price'], 2) : 0;
                    $row['price'] = $row['price'] != '' ? (int)str_replace(',', '', $row['price']) : 0;
                    $row['stock'] = isset($row['stock']) && $row['stock'] != '' ? (int)str_replace(',', '', $row['stock']) : 0;

                    $resultList[] = $row;
                }
            }
        }
        //alert($resultList); exit;
        echo json_encode(['resultList' => $resultList, 'total' => $total]);
    }

    public function updateStatus()
    {
        $post = $this->input->post();
        $crmID = $post['crmID'];
        $status = $post['status'];
        $reason = $post['reason'];

        $params = $this->params;
        $params['crmid'] = $crmID;
        $params['status'] = $status;
        $params['reason'] = $reason;

        $result = $this->api_cms->serviceMaster('samplerequisition/update_status', $this->module, $params);
        $returnData = ['status' => false];
        if ($result['alldata']['Type'] == 'S') {
            $returnData = ['status' => true];
        } else {
            $returnData['msg'] = $result['alldata']['Message'];
        }

        echo json_encode($returnData);
    }

    public function requestApprove()
    {
        $post = $this->input->post();
        $crmID = $post['crmID'];

        $params = $this->params;
        $params['crmid'] = $crmID;
        //echo json_encode($params); exit;
        $result = $this->api_cms->serviceMaster('samplerequisition/request_approve', $this->module, $params);
        $returnData = ['status' => false];
        if ($result['alldata']['Type'] == 'S') {
            $returnData = ['status' => true];
        } else {
            $returnData['msg'] = $result['alldata']['Message'];
        }

        echo json_encode($returnData);
    }

    public function deleteSamplerequisition()
    {
        $post = $this->input->post();
        $crmID = $post['crmID'];

        $params = $this->params;
        $params['data'] = [["deleted" => "1"]];
        $params['crmid'] = $crmID;
        $params['action'] = "edit";

        $result = $this->api_cms->serviceMaster('indexinsert/insert_content', $this->module, $params);

        // alert($result);exit;
        $returnData = ['status' => false];
        if ($result['alldata']['Type'] == 'S') {
            $returnData = ['status' => true];
        } else {
            $returnData['msg'] = $result['alldata']['Message'];
        }

        echo json_encode($returnData);
    }

    /*public function viewReport($crmID = '')
    {
        $get = $this->input->get();
        //alert($get); exit;
        if ($crmID == '') {
            redirect('/Samplerequisition/create?userid=' . $this->session->userdata('userID'));
        }

        if ($this->input->get('userID')) {
            $userID = $this->input->get('userID');
            $this->params['userid'] = $this->input->get('userID');
        } else {
            $userID = $this->session->userdata('userID');
        }

        $data = [];

        if ($get['action'] == 'viewTempReport') {
            $data['url'] = $this->link_report_quotation_temp . htmlspecialchars("&quoteid=") . $crmID . '&__format=pdf&watermark='.@$get['watermark'];;
        } else {
            $data['url'] = $this->link_report_quotation . htmlspecialchars("&quoteid=") . $crmID . '&__format=pdf&watermark='.@$get['watermark'];
        }
        $data['crmid'] = $crmID;
        $data['userid'] = $userID;
        $data['action'] = @$get['action'];
        $data['watermark'] = @$get['watermark'];

        $this->template->build('form-preview-report', $data);
    }*/
}
