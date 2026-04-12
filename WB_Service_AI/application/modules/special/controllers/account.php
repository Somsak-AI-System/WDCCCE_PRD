<?php
defined('BASEPATH') or exit('No direct script access allowed');
include(APPPATH . 'libraries/xlsxwriter.class.php');

class Account extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        echo 'special index';
    }

    public function formCreate(){
        $post = $this->input->post();
        $id = $post['id'];

        $data = $post;
        $gradeSetting = [];
        $sql = $this->db->get('aicrm_account_grade');
        $grades = $sql->result_array();
        $data['grades'] = $grades;

        if($id != ''){
            $sql = $this->db->get_where('aicrm_account_grade_setting', ['id'=>$id]);
            $gradeSetting = $sql->row_array();
        }
        $data['gradeSetting'] = $gradeSetting;
        
        $this->load->view('formGradeSetting', $data);
    }

    public function saveGradeSetting()
    {
        $post = $this->input->post();
        $action = $post['action'];
        if($action == 'Add'){
            $result = $this->db->insert('aicrm_account_grade_setting', [
                'name' => $post['name'],
                'month_period' => $post['monthPeriod'],
                'range_start' => $post['rangeStart'],
                'range_end' => $post['rangeEnd']
            ]);
        }else{
            $result = $this->db->update('aicrm_account_grade_setting', [
                'name' => $post['name'],
                'month_period' => $post['monthPeriod'],
                'range_start' => $post['rangeStart'],
                'range_end' => $post['rangeEnd']
            ], ['id'=>$post['id']]);
        }

        $return['status'] = 'error';
        if($result){
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    public function deleteGradeSetting()
    {
        $post = $this->input->post();
        $result = $this->db->delete('aicrm_account_grade_setting', ['id'=>$post['id']]);

        $return['status'] = 'error';
        if($result){
            $return['status'] = 'success';
        }

        echo json_encode($return);
    }

    public function checkAccountGrade($fixDate='')
    {
        $this->db->select('aicrm_account.accountid, aicrm_account.account_grade,
			aicrm_account.account_grade_change_datetime,
            aicrm_account.grade_expire_date,
			aicrm_account_grade_setting.*,
			aicrm_crmentity.createdtime
		')->from('aicrm_account');
		$this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_account.accountid');
		$this->db->join('aicrm_account_grade_setting', 'aicrm_account_grade_setting.name = aicrm_account.account_grade', 'left');
		$this->db->where([
			'aicrm_crmentity.deleted' => 0
		]);
		$sql = $this->db->get();
		$result = $sql->result_array();

        $return = [];
        foreach($result as $account){
            $monthPeriod = $account['month_period'] == '' ? 180:$account['month_period']; // จำนวนช่วงเวลาคำนวณยอด

            $rangeStart = $account['range_start'];
            $rangeEnd = $account['range_end'];

            // setting period time for sale order
            $dateStart = $account['account_grade_change_datetime'] == '' ? $account['createdtime'] : $account['account_grade_change_datetime'];
            if($fixDate != ''){
                $dateStart = $fixDate.' 00:00:00';
            }
            $dateStart = explode(' ', $dateStart);
            $dateStart = $dateStart[0];
            $dateEnd = date('Y-m-d', strtotime($dateStart .' +'.$monthPeriod.' days'));
            // $dateEnd = date('Y-m-d', strtotime($dateStart .' +180 days'));
            // $dateEnd = date('Y-m-d', strtotime(date('Y-m-d', strtotime($dateStart .' +'.$monthPeriod.' months')).' -1 days'));

            $debug = $account;
            $debug['dateStart'] = $dateStart;
            $debug['dateEnd'] = $dateEnd;
            
            // list sale order
            $this->db->select('SUM(aicrm_salesorder.total) AS total')->from('aicrm_salesorder');
            $this->db->join('aicrm_salesordercf', 'aicrm_salesordercf.salesorderid = aicrm_salesorder.salesorderid');
            $this->db->join('aicrm_crmentity', 'aicrm_crmentity.crmid = aicrm_salesorder.salesorderid');
            $this->db->where([
                'aicrm_crmentity.deleted' => 0,
                'aicrm_salesorder.accountid' => $account['accountid'],
                'aicrm_salesordercf.cf_1318 >=' => $dateStart,
                'aicrm_salesordercf.cf_1318 <=' => $dateEnd,
                'aicrm_salesordercf.cf_1319' => 'Approved'
            ]);
            $sql = $this->db->get();
            // echo $this->db->last_query(); exit();
            $salesOrder = $sql->row_array();
            $totalPrice = $salesOrder['total'] == '' ? 0:$salesOrder['total'];
            $debug['totalPrice'] = $totalPrice;
            
            // เงื่อนไข ถ้าถึงยอดแต่ละขึ้น ให้เปลี่ยนขั้นขึ้น พร้อม เปลี่ยนวันที่เริ่มนับ 
            // ถ้ายังไม่ถึงยอด ยังไม่ต้องเปลี่ยนวันที่เริ่มนับ จนกว่าจะถึงวันที่สิ้นสุดเปลี่ยนขั้น ถ้ายอดยังไม่ถึงให้ปรับขั้นลดลง พร้อม เปลี่ยนวันเริ่มนับ

            if($totalPrice > $rangeEnd){ // ถ้ายอดสั่งซื้อมากกว่ายอดสูงสุด
                $this->db->select('*')->from('aicrm_account_grade_setting')->where([
                    'range_start <=' => $totalPrice,
                    'range_end >=' => $totalPrice
                ])->limit(1);
                $sql = $this->db->get();
                $grade = $sql->row_array();
                $debug['newClass'] = $grade['name'];
                $debug['updateClass'] = 'yes';

                $this->db->update('aicrm_account', ['account_grade'=>$grade['name'], 'grade_expire_date'=>$dateEnd, 'account_grade_change_datetime'=>date('Y-m-d H:i:s')], ['accountid'=>$account['accountid']]);
                $this->db->update('aicrm_crmentity', ['modifiedtime'=>date('Y-m-d H:i:s')], ['crmid'=>$account['accountid']]);
            }else{
                if(date('Y-m-d') > $dateEnd){ // ถ้าครบกำหนดรักษายอด
                    $this->db->select('*')->from('aicrm_account_grade_setting')->where([
                        'range_start <=' => $totalPrice,
                        'range_end >=' => $totalPrice
                    ])->limit(1);
                    $sql = $this->db->get();
                    $grade = $sql->row_array();
                    $debug['newClass'] = $grade['name'];
                    $debug['updateClass'] = 'yes';
                    
                    $this->db->update('aicrm_account', ['account_grade'=>$grade['name'], 'grade_expire_date'=>$dateEnd, 'account_grade_change_datetime'=>date('Y-m-d H:i:s')], ['accountid'=>$account['accountid']]);
                    $this->db->update('aicrm_crmentity', ['modifiedtime'=>date('Y-m-d H:i:s')], ['crmid'=>$account['accountid']]);
                }else{
                    $debug['updateClass'] = 'no';
                    if(empty($account['grade_expire_date'])) $this->db->update('aicrm_account', ['grade_expire_date'=>$dateEnd], ['accountid'=>$account['accountid']]);
                }
            }
            $return[] = $debug;
        }
        echo 'finished';
    }

    public function updateExpiredPoint()
    {
        $this->db->query('call sp_update_expired_point()');
    }

    public function updateFirstExpirePoint(){
 
        $queryUpdate = "UPDATE aicrm_account
            INNER JOIN aicrm_accountscf ON (aicrm_account.accountid = aicrm_accountscf.accountid),
            (SELECT 
                aicrm_transaction_point.accountid,
                SUM(aicrm_transaction_point.balance) AS balance,
                aicrm_transaction_point.dateexpired
                FROM aicrm_transaction_point
                INNER JOIN aicrm_account ON aicrm_account.accountid = aicrm_transaction_point.accountid
                INNER JOIN aicrm_accountscf ON aicrm_accountscf.accountid = aicrm_transaction_point.accountid
                WHERE aicrm_transaction_point.dateexpired > NOW() 
                GROUP BY aicrm_transaction_point.accountid
                HAVING balance > 0
            ) AS point
            SET aicrm_account.point_remaining = point.balance
            WHERE aicrm_account.accountid = point.accountid;";
        
        $this->db->query($queryUpdate);
    }

    public function accountPointTransaction(){
        $get = $this->input->get();
        if(isset($get['id'])){
            $sql = "
                SELECT
                    aicrm_transaction_point.*,
                    aicrm_point.point_name,
                    aicrm_salesorder.salesorder_no
                FROM
                    aicrm_transaction_point
                    INNER JOIN aicrm_point ON aicrm_point.pointid = aicrm_transaction_point.pointid
                    LEFT JOIN aicrm_salesorder ON aicrm_salesorder.salesorderid = aicrm_point.salesorderid 
                WHERE
                    aicrm_transaction_point.accountid = ".$get['id'];
            $sql = $this->db->query($sql);
            $rs = $sql->result_array();

            $this->load->view('account-point-transaction', ['data'=>$rs]);
        }else{
            echo 'No AccountID';
        }
    }

    public function getAccountPoint(){
        $sql = $this->db->query('SELECT aicrm_account.accountid, aicrm_account.accountname
        FROM aicrm_account
        INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_account.accountid
        INNER JOIN aicrm_accountbillads ON aicrm_account.accountid = aicrm_accountbillads.accountaddressid
        INNER JOIN aicrm_accountshipads ON aicrm_account.accountid = aicrm_accountshipads.accountaddressid
        INNER JOIN aicrm_accountscf ON aicrm_account.accountid = aicrm_accountscf.accountid
        WHERE aicrm_crmentity.deleted = 0'); //  AND aicrm_account.accountid = 1285458
        $rs = $sql->result_array();

        $exportDir = '../export_csv/ExportData';
        if(!is_dir($exportDir)){
            mkdir($exportDir,0777,true);
        }
        // alert($rs);
        $rowData = [];
        foreach($rs as $row){
            $res = $this->checkAccountSO($row['accountid']);
            // alert($res);
            $this->db->update('aicrm_accountscf',
                [
                    'cf_1485'=>$res['FinalPoint'],
                    'cf_1484'=>$res['UsedPoint'],
                    'cf_1219'=>$res['FinalPoint'] + $res['UsedPoint']
                ],
                [
                    'accountid' => $row['accountid']
                ]
            );
            // cf_1485 remain cf_1484 used cf_1219 all
            // echo $row['accountname'].' : '.$res['FinalPoint'].' | ';
            // if($res['FinalPoint'] >= 0) continue;
            // $rowData[] = [
            //     $row['accountid'],
            //     $row['accountname'],
            //     $res['FinalPoint']
            // ];
        }
        // alert($rowData);
        // exit();
        // $header = [
        //     'Account ID' => 'string',
        //     'Account Name' => 'string',
        //     'Point' => 'string'
        // ];

        // $fileName = date('YmdHms').'.xlsx';
        // $writer = new XLSXWriter();
        // $writer->writeSheetHeader('Sheet1', $header );
        // foreach($rowData as $row){
        //     $writer->writeSheetRow('Sheet1', $row );
        // }

        // $exportFilePath = $exportDir.'/'.$fileName;
        // $writer->writeToFile($exportFilePath);

        echo 'finished';
    }

    public function checkAccountSO($accountid){
        $query = '(
            SELECT aicrm_salesorder.salesorderid AS crmid
            ,"Sale Order" AS type
            ,aicrm_salesorder.accountid
            ,aicrm_salesorder.salesorder_no AS crmNo
            ,aicrm_salesordercf.cf_1319 AS crmStatus
            ,aicrm_salesordercf.cf_1318 AS crmDate
            ,aicrm_salesordercf.cf_1805 AS startPoint
            ,aicrm_salesordercf.cf_1806 AS earnPoint
            ,aicrm_salesordercf.cf_1808 AS usedPoint
            ,ROUND( ( ( aicrm_salesordercf.cf_1805 + aicrm_salesordercf.cf_1806 ) - aicrm_salesordercf.cf_1808 ), 2 ) AS remainPoint
            -- ,SUM(aicrm_redemptioncf.cf_1430) AS redemPoint
            FROM aicrm_salesorder
            INNER JOIN aicrm_salesordercf ON aicrm_salesordercf.salesorderid = aicrm_salesorder.salesorderid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_salesorder.salesorderid AND aicrm_crmentity.deleted = 0
            -- LEFT JOIN aicrm_redemptioncf ON aicrm_redemptioncf.cf_1754 = aicrm_salesorder.salesorderid
            -- INNER JOIN aicrm_account ON aicrm_account.accountid = aicrm_salesorder.accountid
            WHERE aicrm_salesorder.accountid = '.$accountid.'
            AND aicrm_salesordercf.cf_1318 >= "2015-01-01"
            GROUP BY aicrm_salesorder.salesorderid
        )
        UNION
        (
            SELECT aicrm_point.pointid AS crmid
            ,"Adjust Point" AS type
            ,aicrm_point.parent_id AS accountid
            ,aicrm_point.point_name AS crmNo
            ,aicrm_pointcf.cf_1404 AS crmStatus
            ,aicrm_pointcf.cf_1411 AS crmDate
            ,aicrm_pointcf.cf_1408 AS point
            ,"","",""
            FROM aicrm_point
            INNER JOIN aicrm_pointcf ON aicrm_pointcf.pointid = aicrm_point.pointid
            INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid = aicrm_point.pointid
            WHERE aicrm_point.parent_id = '.$accountid.'
            AND aicrm_point.point_name LIKE "%Adjust Point%"
            AND aicrm_pointcf.cf_1411 >= "2015-01-01"
        )
        ORDER BY crmDate';
        $sql = $this->db->query($query);
        $rs = $sql->result_array();

        $startPoint = 0;
        $remainPoint = 0;
        $usedPoint = 0;
        foreach($rs as $x =>$row){
            if($row['type'] == 'Adjust Point'){
                if($row['crmStatus'] == 'Add'){
                    $remainPoint = $remainPoint + $row['startPoint'];
                }else{
                    $remainPoint = $remainPoint - $row['startPoint'];
                }
            }else{
                if($row['crmStatus'] != 'Cancel'){
                    if($x != 0){
                        $startPoint = $remainPoint;
                    }else{
                        $startPoint = $row['startPoint'];
                    }
                    $remainPoint = ($startPoint + $row['earnPoint']) - $row['usedPoint'];
                    $usedPoint = $usedPoint + $row['usedPoint'];
                }
                // $this->db->update('aicrm_salesordercf', [
                //     'cf_1805' => $startPoint
                // ], ['salesorderid'=>$row['crmid']]);
            }
        }

        $returnData = [
            'FinalPoint' => $remainPoint,
            'UsedPoint' => $usedPoint
        ];
        // echo json_encode($returnData);
        return $returnData;
        // alert($returnData);
        // $this->load->view('listAccountTrans', ['list'=>$rs]);
    }

    public function getAdvFilter(){
        $post = $this->input->post();

        $sql = $this->db->get_where('aicrm_adv_select_filter', ['user_id'=>$post['userID'], 'status'=>1]);
        $rs = $sql->result_array();

        echo json_encode($rs);
    }

    public function getAdvFilterDetail(){
        $post = $this->input->post();

        //$this->db->update('aicrm_adv_select_filter', ['is_default'=>0], ['user_id'=>$post['userID']]);
        //$this->db->update('aicrm_adv_select_filter', ['is_default'=>1], ['id'=>$post['filterID']]);

        $sql = $this->db->get_where('aicrm_adv_select_filter_field', ['filter_id'=>$post['filterID']]);
        $rs = $sql->result_array();

        echo json_encode($rs);
    }

    public function saveAdvFilter(){
        $post = $this->input->post();

        if(!isset($post['user_id']) || $post['user_id']==''){
            echo json_encode(['status'=>'error', 'msg'=>'No UserID']);
            return false;
        }

        $sql = $this->db->get_where('aicrm_users', ['id'=>$post['user_id']]);
        $user = $sql->row_array();

        $returnData = [
            'status' => 'error',
            'msg' => 'Can not save filter',
            'filterID' => '',
            'method' => ''
        ];
        if(isset($post['filter_id']) && $post['filter_id']!=''){
            // update filter
            $filterID = $post['filter_id'];
            $returnData['method'] = 'edit';
            $returnData['filterID'] = $filterID;

            $this->db->delete('aicrm_adv_select_filter_field', ['filter_id'=>$filterID]);

            unset($post['filterName']);
            unset($post['user_id']);
            unset($post['filter_id']);

            foreach($post as $key => $value){
                $this->db->insert('aicrm_adv_select_filter_field', [
                    'filter_id' => $filterID,
                    'field_name' => $key,
                    'field_value' => $value
                ]);
            }

            $returnData['status'] = 'success';
            $returnData['msg'] = 'Update filter completed';
            $returnData['method'] = 'edit';
            $returnData['filterID'] = $filterID;
        }else{
            // insert filter
            /*$sql = $this->db->query("SELECT COUNT(*) AS count FROM aicrm_adv_select_filter WHERE user_id=".$post['user_id']);
            $rs = $sql->row_array();
            $count = $rs['count'] + 1;*/

            $filterName = $post['filterName'];

            $this->db->insert('aicrm_adv_select_filter', [
                'name' => $filterName,
                'user_id' => $post['user_id'],
                //'is_default' => $count == 1 ? 1:0
                'is_default' => 0
            ]);
            $filterID = $this->db->insert_id();

            unset($post['filterName']);
            unset($post['user_id']);
            unset($post['filter_id']);

            foreach($post as $key => $value){
                $this->db->insert('aicrm_adv_select_filter_field', [
                    'filter_id' => $filterID,
                    'field_name' => $key,
                    'field_value' => $value
                ]);
            }

            $returnData['status'] = 'success';
            $returnData['msg'] = 'Add filter completed';
            $returnData['method'] = 'add';
            $returnData['filterName'] = $filterName;
            $returnData['filterID'] = $filterID;
        }

        echo json_encode($returnData);
    }

    public function deleteAdvFilter(){
        $post = $this->input->post();
        $userID = $post['userID'];
        $filterID = $post['filterID'];

        $this->db->delete('aicrm_adv_select_filter', ['id'=>$filterID]);
        $this->db->delete('aicrm_adv_select_filter_field', ['filter_id'=>$filterID]);

        $this->db->select('*')->from('aicrm_adv_select_filter')->where(['user_id'=>$userID])->limit(1);
        $sql = $this->db->get();
        $rs = $sql->row_array();

        if(!empty($rs)){
            //$this->db->update('aicrm_adv_select_filter', ['is_default'=>1], ['id'=>$rs['id']]);
            echo json_encode(['status'=>'success', 'filterID'=>@$rs['id']]);
        }else{
            echo json_encode(['status'=>'error']);
        }
    }

    public function saveAdvFields()
    {
        $post = $this->input->post();
        $data = $post['data'];

        if (isset($data) && $data != '') {
            foreach ($data as $key => $val) {
                $fieldidsTemp[] = $val['fieldid'];
            }
            $fieldidsTemp = implode(",", $fieldidsTemp);
            $sql = $this->db->query("SELECT columnname as field, fieldlabel as title, '12.5%' as width  FROM aicrm_field WHERE  FIND_IN_SET(fieldid,('$fieldidsTemp'))");
            $rs = $sql->result_array();

            $returnData['status'] = 'success';
            $returnData['msg'] = 'Add Fiels completed';
            $returnData['data'] = $rs;

            echo json_encode($returnData);
        }


    }

    public function updateAccountProject()
    {
        if($this->input->post()){
            $post = $this->input->post();
        } else {
            $request_body = file_get_contents('php://input');
            $post = json_decode($request_body, true);
        }
        $accountID = $post['accountID'];
        $oldSale = $post['oldSale'];
        $newSale = $post['newSale'];

        $sql = $this->db->query('SELECT account_no FROM aicrm_account WHERE accountid='.$accountID);
        $rs = $sql->row_array();
        $accountNo = $rs['account_no'];

        $sql = $this->db->query('SELECT user_name FROM aicrm_users WHERE id='.$oldSale);
        $rs = $sql->row_array();
        $oldSaleName = $rs['user_name'];

        $sql = $this->db->query('SELECT user_name FROM aicrm_users WHERE id='.$newSale);
        $rs = $sql->row_array();
        $newSaleName = $rs['user_name'];

        $query = "SELECT 
            tmp2.projectid,
            GROUP_CONCAT(tmp2.field SEPARATOR ', ') AS field
        FROM (
            SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_owner_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventoryowner WHERE owner_no = '".$accountNo."'
            UNION ALL
            SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_consultant_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventoryconsultant WHERE consultant_no = '".$accountNo."'
            UNION ALL
            SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_architecture_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventoryarchitecture WHERE architecture_no = '".$accountNo."'
            UNION ALL
            SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_construction_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventoryconstruction WHERE construction_no = '".$accountNo."'
            UNION ALL
            SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_designer_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventorydesigner WHERE designer_no = '".$accountNo."'
            UNION ALL
            SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_contractor_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventorycontractor WHERE contractor_no = '".$accountNo."'
            UNION ALL
            SELECT id AS projectid, lineitem_id, '".$oldSaleName."' AS oldSale, '".$newSaleName."' AS newSale, CONCAT('aicrm_projects.sales_sub_contractor_name_', sequence_no, ' = \"', '".$newSaleName."', '\"') AS field FROM aicrm_inventorysubcontractor WHERE sub_contractor_no = '".$accountNo."'
        ) AS tmp2
        GROUP BY tmp2.projectid";
        // echo $query; exit();
        $sql = $this->db->query($query);
        $rs = $sql->result_array();
        foreach($rs as $rowData){
            $this->db->query('UPDATE aicrm_projects SET '.$rowData['field'].' WHERE aicrm_projects.projectsid="'.$rowData['projectid'].'"');
        }

        $query = 'SELECT
            tmp.type,
            tmp.column_name,
            GROUP_CONCAT(tmp.lineitem_id SEPARATOR ",") AS str,
            GROUP_CONCAT(tmp.projectid SEPARATOR ",") AS projectid
        FROM (
            SELECT "aicrm_inventoryowner" AS type, id AS projectid, lineitem_id, sequence_no, "sales_owner_name" AS column_name FROM aicrm_inventoryowner WHERE owner_no = "'.$accountNo.'"
            UNION ALL
            SELECT "aicrm_inventoryconsultant" AS type, id AS projectid, lineitem_id, sequence_no, "sales_consultant_name" AS column_name FROM aicrm_inventoryconsultant WHERE consultant_no = "'.$accountNo.'"
            UNION ALL
            SELECT "aicrm_inventoryarchitecture" AS type, id AS projectid, lineitem_id, sequence_no, "sales_architecture_name" AS column_name FROM aicrm_inventoryarchitecture WHERE architecture_no = "'.$accountNo.'"
            UNION ALL
            SELECT "aicrm_inventoryconstruction" AS type, id AS projectid, lineitem_id, sequence_no, "sales_construction_name" AS column_name FROM aicrm_inventoryconstruction WHERE construction_no = "'.$accountNo.'"
            UNION ALL
            SELECT "aicrm_inventorydesigner" AS type, id AS projectid, lineitem_id, sequence_no, "sales_designer_name" AS column_name FROM aicrm_inventorydesigner WHERE designer_no = "'.$accountNo.'"
            UNION ALL
            SELECT "aicrm_inventorycontractor" AS type, id AS projectid, lineitem_id, sequence_no, "sales_contractor_name" AS column_name FROM aicrm_inventorycontractor WHERE contractor_no = "'.$accountNo.'"
            UNION ALL
            SELECT "aicrm_inventorysubcontractor" AS type, id AS projectid, lineitem_id, sequence_no, "sales_sub_contractor_name" AS column_name FROM aicrm_inventorysubcontractor WHERE sub_contractor_no = "'.$accountNo.'"
        ) AS tmp
        GROUP BY tmp.type, tmp.sequence_no';
        $sql = $this->db->query($query);
        $rs = $sql->result_array();
        $projectList = [];
        foreach($rs as $rowData){
            $this->db->query('UPDATE '.$rowData['type'].' SET '.$rowData['column_name'].'="'.$newSaleName.'" WHERE lineitem_id IN ('.$rowData['str'].')');
            $projects = explode(',', $rowData['projectid']);
            $projectList = array_unique(array_merge($projectList, $projects));
        }

        $this->db->query("UPDATE aicrm_projects
        INNER JOIN (
            SELECT
                v_project_sale_com.project_id,
                GROUP_CONCAT( ifnull( v_project_sale_com.sales_name, '-' ) ORDER BY v_project_sale_com.percen_com_sales ASC SEPARATOR ' |##| ' ) related_sales_person 
            FROM
                v_project_sale_com
            WHERE
                v_project_sale_com.project_id  IN (".implode(',', $projectList).")
            GROUP BY
                v_project_sale_com.project_id 
        ) project_relate_person ON aicrm_projects.projectsid = project_relate_person.project_id 
        SET aicrm_projects.related_sales_person = project_relate_person.related_sales_person");
    }
}