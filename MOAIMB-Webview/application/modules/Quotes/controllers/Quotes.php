<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Quotes extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->template->set_layout('template-master');
        $this->module = 'Quotes';
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
        $this->link_report_quotation_temp = config_item('url_report_quotation_temp');
        $this->link_report_quotation = config_item('url_report_quotation');
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
        //echo json_encode($this->params); exit;
        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $this->params);
        //alert($result); exit;

        if ($result['alldata']['Type'] == 'S') {
            $blocks = $result['alldata']['data'][0]['result'];
        }

        $data['module'] = $this->module;
        $data['blocks'] = $blocks;
        $this->template->build('create', $data);
    }

    public function edit($crmID = '')
    {
        if ($crmID == '') {
            redirect('/Quotes/create?userid=' . $this->session->userdata('userID'));
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
            redirect('/Quotes/create?userid=' . $this->session->userdata('userID'));
        }

        $this->session->set_userdata('crmID', $crmID);
        $data = [];
        $data['crmID'] = $crmID;
        $data['userID'] = $this->session->userdata('userID');

        $params = $this->params;
        $params['crmID'] = $crmID;
        //echo json_encode($params);exit;
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

        $result = $this->api_cms->serviceMaster('quotes/get_product_list', $this->module, $params);
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
        //echo json_encode($params); exit;
        $result = $this->api_cms->serviceMaster('quotes/get_product_list', $this->module, $params);
        //alert($result); exit;
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
                        $uom = $item['uom'];
                        $competitor_price = $item['competitor_price'];
                        $remark = $item['comment'];
                        //$listprice = $item['listprice'];
                        $stock = (int)$item['stockqty'];
                        break;
                    case 'service':
                        $name = $item['service_name'];
                        $no = $item['service_no'];
                        $uom = '';
                        $remark = '';
                        $competitor_price = '';
                        //$listprice = '';
                        $stock = 1;
                        break;
                    case 'sparepart':
                        $name = $item['sparepart_name'];
                        $no = $item['sparepart_no'];
                        $uom = '';
                        $remark = '';
                        $competitor_price = '';
                        //$listprice = '';
                        $stock = (int)$item['sparepart_stock_qty'];
                        break;
                }

                $itemList[] = [
                    'amount' => (int)$item['quantity'],
                    'id' => $item['productid'],
                    'no' => $no,
                    'name' => $name,
                    'uom' => $uom,
                    'competitor_price' => $item['competitor_price'],
                    //'price' => (float)$item['selling_price'],
                    //'price_display' => number_format((float)$item['selling_price'], 2),
                    'price' => (float)$item['listprice'],
                    'price_display' => number_format((float)$item['listprice'], 2),
                    'stock' => $stock,
                    //'remark' => $item['comment'],
                    'remark' => $remark,
                    'type' => $type,
                    'listprice' => $item['listprice'],
                    'product_finish' => $item['product_finish'],
                    'product_size_mm' => $item['product_size_mm'],
                    'product_thinkness' => $item['product_thinkness'],

                    'competitor_brand' => $item['competitor_brand'],
                    'compet_brand_in_proj' => $item['compet_brand_in_proj'],
                    'compet_brand_in_proj_price' => $item['compet_brand_in_proj_price'],
                    'product_cost_avg' => $item['product_cost_avg'],
                ];
            }

            $discountType = '';
            $discountTypeAmount = '';
            if ((float)$rowData['discount_amount'] != '0') {
                $discountType = '2';
                $discountTypeAmount = $rowData['discount_amount'];
            } else if ((float)$rowData['discount_percent'] != '0') {
                $discountType = '1';
                $discountTypeAmount = $rowData['discount_percent'];
            }

            $vatType = 'ไม่รวมภาษี';
            if ($rowData['pricetype'] == 'Exclude Vat') {
                $vatType = 'ไม่รวมภาษี';
            } else if ($rowData['pricetype'] == 'Include Vat') {
                $vatType = 'รวมภาษี';
            }

            $returnData = [
                'netTotal' => (float)$rowData['subtotal'],
                'discountType' => $discountType,
                'discountTypeAmount' => (float)$discountTypeAmount,
                'discountAmount' => (float)$rowData['discountTotal_final'],
                'totalAfterDiscount' => (float)$rowData['total_after_discount'],
                'totalBeforeVat' => @$rowData['total_before_tax'],
                'vatType' => $vatType,
                //'vatPercentage' => (float)$rowData['tax1'],
                'vatPercentage' => ($rowData['tax1']=='') ? 7 : (float)$rowData['tax1'],
                'netVat' => (float)$rowData['tax_final'],
                'totalAfterVat' => (float)@$rowData['total_after_tax'],
                'grandTotal' => (float)$rowData['total'],
                'itemList' => $itemList
            ];
            //($rowData['tax1']=='') ? 7 : (float)$rowData['tax1'],
            //$module=isset($a_request['module']) ? $a_request['module'] : "";
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

        $result = $this->api_cms->serviceMaster('quotes/get_product_list', $this->module, $params);
        //alert($result); exit;
        $itemList = [];
        $recordData = [];
        if ($result['alldata']['Type'] == 'S') {
            $itemList = $result['alldata']['data']['itemList'];
            $recordData = $result['alldata']['data']['rowData'];
        }
        $data['itemList'] = $itemList;
        $quote_no_rev = $this->api_cms->serviceMaster('quotes/get_quote_no_rev', $this->module, $this->params);
        // alert($quote_no_rev);exit;
        if ($action == "revise") {
            $recordData['rev_no'] = $quote_no_rev['alldata']['data']['dataRev']['data_rev'];
            $recordData['quote_no_rev'] = $quote_no_rev['alldata']['data']['dataRev']['data_quote_no'];

            $recordData['quotation_status'] = "เปิดใบเสนอราคา";
            $recordData['quotation_date'] = date('Y-m-d');
            //$recordData['quotation_enddate'] = date("Y-m-d", strtotime((date("Y-m-d")) . "+7 day"));
            $recordData['quotation_enddate'] = date("Y-12-31");
            $recordData['quota_cancel'] = "";
            $recordData['quota_notapprove'] = "";
            $recordData['smownerid'] = $userID;
        
        }else if($action == "duplicate") {
            $recordData['rev_no'] = '';
            $recordData['quote_no_rev'] = '';
            $recordData['quotation_status'] = "เปิดใบเสนอราคา";
            $recordData['quotation_date'] = date('Y-m-d');
            //$recordData['quotation_enddate'] = date("Y-m-d", strtotime((date("Y-m-d")) . "+7 day"));
            $recordData['quotation_enddate'] = date("Y-12-31");
            $recordData['quota_cancel'] = "";
            $recordData['quota_notapprove'] = "";
            $recordData['smownerid'] = $userID;
        }

        
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
                    'product_name' => $item['product_name'],
                    'sequence_no' => $x,
                    'quantity' => $item['quantity'],
                    'listprice' => $item['listprice'],
                    'comment' => $item['comment'],
                    'tax1' => $item['tax1'],
                    'uom' => $item['uom'],
                    'product_unit' => $item['uom'],
                    'listprice' => $item['listprice'],
                    'selling_price' => $item['listprice'],
                    'product_finish' => $item['product_finish'],
                    'product_size_mm' => $item['product_size_mm'],
                    'product_thinkness' => $item['product_thinkness'],
                    'product_finish' => $item['product_finish'],

                    'competitor_brand' => $item['competitor_brand'],
                    'competitor_price' => $item['competitor_price'],
                    'compet_brand_in_proj' => $item['compet_brand_in_proj'],
                    'compet_brand_in_proj_price' => $item['compet_brand_in_proj_price'],
                    'product_cost_avg' => $item['product_cost_avg'],
                ];
            }

            $updateData = [
                'discount_amount' => $recordData['discount_amount'],
                'discount_percent' => $recordData['discount_percent'],
                'discountTotal_final' => $recordData['discountTotal_final'],
                'total_after_discount' => $recordData['total_after_discount'],
                'pricetype' => $recordData['pricetype'],
                'total_before_tax' => $recordData['total_before_tax'],
                'total_without_vat' => $recordData['total_without_vat'],
                'tax1' => $recordData['tax1'],
                'total_after_tax' => $recordData['total_after_tax'],
                'tax_final' => $recordData['tax_final'],
                'subtotal' => $recordData['subtotal'],
                'total' => $recordData['total'],
            ];

            $params['updateData'] = $updateData;
            $params['itemList'] = $productItemList;

            $result = $this->api_cms->serviceMaster('quotes/save_product_list', $this->module, $params);

            $result = $this->api_cms->serviceMaster('quotes/get_product_list', $this->module, $params);
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
            redirect('/Quotes/create?userid=' . $this->session->userdata('userID'));
        }

        if ($this->input->get('userID')) {
            $userID = $this->input->get('userID');
            $this->params['userid'] = $this->input->get('userID');
        } else {
            $userID = $this->session->userdata('userID');
        }
        // alert($userID);exit;

        $data = [];

        $user = $this->api_cms->serviceMaster('users/get_role', $this->module, $this->params);
        $role = [];
        if ($user['alldata']['Type'] == 'S') {
            $role = $user['alldata']['data']['role'];
        }

        $this->params['action'] = 'edit';
        $this->params['crmid'] = $crmID;

        $approver = $this->api_cms->serviceMaster('quotes/get_approver', $this->module, $this->params);
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
                // if ($row['userid'] == $userID && $row['appstatus'] == '0') {
                //     $isApprover = true;
                // }
            }
        }

        //echo json_encode($this->params);exit;
        $result = $this->api_cms->serviceMaster('api/list_content', $this->module, $this->params);
        //alert($result);exit;
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
        $result = $this->api_cms->serviceMaster('quotes/get_product_list', $this->module, $params);

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
                        $uom = $item['uom'];
                        $competitor_price = $item['competitor_price'];
                        break;
                    case 'service':
                        $name = $item['service_name'];
                        $no = $item['service_no'];
                        $stock = 1;
                        $uom = '';
                        $competitor_price = '';
                        break;
                    case 'sparepart':
                        $name = $item['sparepart_name'];
                        $no = $item['sparepart_no'];
                        $stock = (int)$item['sparepart_stock_qty'];
                        $uom = '';
                        $competitor_price = '';
                        break;
                }

                $itemList[] = [
                    'amount' => (int)$item['quantity'],
                    'id' => $item['productid'],
                    'no' => $no,
                    'name' => $name,
                    'price' => (float)$item['listprice'],
                    'price_display' => number_format((float)$item['listprice'], 2),
                    'stock' => $stock,
                    'remark' => $item['comment'],
                    'type' => $type,
                    'uom' => $uom,
                    'competitor_price' => $competitor_price,
                    'listprice' => (float)$item['listprice'],
                    'product_finish' => $item['product_finish'],
                    'product_size_mm' => $item['product_size_mm'],
                    'product_thinkness' => $item['product_thinkness'],
                ];
            }

            $discountType = '';
            $discountTypeAmount = '';
            if ((float)$rowData['discount_amount'] != '0') {
                $discountType = '2';
                $discountTypeAmount = $rowData['discount_amount'];
            } else if ((float)$rowData['discount_percent'] != '0') {
                $discountType = '1';
                $discountTypeAmount = $rowData['discount_percent'];
            }

            $vatType = 'ไม่รวมภาษี';
            $vatText = '';
            if ($rowData['pricetype'] == 'Exclude Vat') {
                $vatType = 'ไม่รวมภาษี';
                $vatText = $rowData['tax1'] == '' || $rowData['tax1'] == '0' ? '' : '(ไม่รวม ' . number_format((float)$rowData['tax1'], 0) . '%)';
            } else if ($rowData['pricetype'] == 'Include Vat') {
                $vatType = 'รวมภาษี';
                $vatText = $rowData['tax1'] == '' || $rowData['tax1'] == '0' ? '' : '(รวม ' . number_format((float)$rowData['tax1'], 0) . '%)';
            }

            $priceInfo = [
                'netTotal' => number_format((float)$rowData['subtotal'], 2),
                'discountType' => $discountType,
                'discountTypeAmount' => number_format((float)$discountTypeAmount, 2),
                'discountAmount' => number_format((float)$rowData['discountTotal_final'], 2),
                'totalAfterDiscount' => number_format((float)$rowData['total_after_discount'], 2),
                'totalBeforeVat' => number_format((float)@$rowData['total_before_tax'], 2),
                'vatType' => $vatType,
                'vatPercentage' => $vatText,
                'netVat' => number_format((float)$rowData['tax_final'], 2),
                'totalAfterVat' => number_format((float)@$rowData['total_after_tax'], 2),
                'grandTotal' => number_format((float)$rowData['total'], 2),
                'itemList' => $itemList
            ];
        };

        if($rowData['quotation_status'] !='ปิดการขาย' && $rowData['quotation_status'] !='อนุมัติใบเสนอราคา'){
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
        $data['url'] = $this->link_report_quotation . '&quoteid=' . $crmID . '&__format=pdf&watermark='.$watermark;

        $this->template->build('view', $data);
    }

    public function save()
    {
        $post = $this->input->post();

        $action = $post['action'];

        if (isset($post['crmid'])) {
            $this->params['crmid'] = $post['crmid'];
        }

        $post['approve_level1'] = isset($post['approve_level1']) ? implode(' |##| ', $post['approve_level1']) : '';
        $post['approve_level2'] = isset($post['approve_level2']) ? implode(' |##| ', $post['approve_level2']) : '';
        $post['approve_level3'] = isset($post['approve_level3']) ? implode(' |##| ', $post['approve_level3']) : '';
        $post['approve_level4'] = isset($post['approve_level4']) ? implode(' |##| ', $post['approve_level4']) : '';
        $post['smownerid'] = $post['assign_to'] == 'user' ? $post['assign_to_user'] : $post['assign_to_group'];
        $post['quotation_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['quotation_date'])));
        $post['quotation_enddate'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['quotation_enddate'])));
        $post['quotation_valid_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['quotation_valid_date'])));
        $post['quotation_status'] = "เปิดใบเสนอราคา";
        $post['taxtype'] = "group";

        $post['project_est_s_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['project_est_s_date'])));
        $post['project_est_e_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['project_est_e_date'])));
        
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

        $priceType = '';
        if ($post['vatType'] == 'ไม่รวมภาษี') {
            $priceType = 'Exclude Vat';
        } else if ($post['vatType'] == 'รวมภาษี') {
            $priceType = 'Include Vat';
        }
        //alert($post); exit;
        $updateData = [
            'discount_amount' => $post['discountType'] == '2' ? $post['discountTypeAmount'] : '',
            'discount_percent' => $post['discountType'] == '1' ? $post['discountTypeAmount'] : '',
            'discountTotal_final' => $post['discountAmount'],
            'total_after_discount' => $post['totalAfterDiscount'],
            'pricetype' => $priceType,
            'total_before_tax' => $post['totalBeforeVat'],
            'total_without_vat' => $post['totalBeforeVat'],
            'tax1' => $post['vatPercentage'],
            'total_after_tax' => $post['totalAfterVat'],
            'tax_final' => $post['netVat'],
            'subtotal' => $post['netTotal'],
            'total' => $post['grandTotal']
        ];

        $productItemList = [];
        foreach ($post['itemList'] as $x => $item) {
            $productItemList[] = [
                'id' => $crmID,
                'productid' => $item['id'],
                'product_name' => $item['name'],
                'sequence_no' => $x,
                'quantity' => $item['amount'],
                //'listprice' => $item['price'],
                'comment' => $item['remark'],
                'tax1' => $post['vatPercentage'],
                'uom' => $item['uom'],
                'product_unit' => $item['uom'],
                'listprice' => $item['listprice'],
                'selling_price' => $item['listprice'],
                'product_finish' => $item['product_finish'],
                'product_size_mm' => $item['product_size_mm'],
                'product_thinkness' => $item['product_thinkness'],
                'product_finish' => $item['product_finish'],

                'competitor_brand' => $item['competitor_brand'],
                'competitor_price' => $item['competitor_price'],
                'compet_brand_in_proj' => $item['compet_brand_in_proj'],
                'compet_brand_in_proj_price' => $item['compet_brand_in_proj_price'],
                'product_cost_avg' => $item['product_cost_avg'],
            ];
        }

        $params['updateData'] = $updateData;
        $params['itemList'] = $productItemList;

        $result = $this->api_cms->serviceMaster('quotes/save_product_list', $this->module, $params);
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
        // alert($post);exit;
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

        $result = $this->api_cms->serviceMaster('quotes/save_product_list_temp', $this->module, $params);
        if ($result['alldata']['Type'] == 'S') {
            $returnData = [
                'status' => true,
                'crmID' => $result['alldata']['data']['Crmid'],
                'url' => $this->link_report_quotation_temp .$result['alldata']['data']['Crmid'] . '&__format=pdf'
            ];
        } else {
            $returnData = [
                'status' => true
            ];
        }
        // alert($returnData);exit;
        echo json_encode($returnData);
    }

    public function getPopupList()
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
        $params['relatedcrmid'] = @$post['relatedcrmid'];

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
        
        $result = $this->api_cms->serviceMaster('quotes/update_status', $this->module, $params);
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

        $result = $this->api_cms->serviceMaster('quotes/request_approve', $this->module, $params);
        $returnData = ['status' => false];
        if ($result['alldata']['Type'] == 'S') {
            $returnData = ['status' => true];
        } else {
            $returnData['msg'] = $result['alldata']['Message'];
        }

        echo json_encode($returnData);
    }

    public function deleteQuotes()
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

    public function viewReport($crmID = '')
    {
        $get = $this->input->get();
        //alert($get); exit;
        if ($crmID == '') {
            redirect('/Quotes/create?userid=' . $this->session->userdata('userID'));
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
    }

    public function Get_Buyer()
    {
        $post = $this->input->post();
        $params = $this->params;
        $params['quotation_buyer'] = $post['quotation_buyer'];

        //echo json_encode($params); exit;
        $result = $this->api_cms->serviceMaster('quotes/get_buyer', $this->module, $params);
        $total = 0;
        $resultList = [];

        if ($result['alldata']['Type'] == 'S') {
            $status = 'Success'; 
            $result = @$result['alldata']['value'];           
        }
        echo json_encode(['data' => $result,'status' => $status]);
    }

}
