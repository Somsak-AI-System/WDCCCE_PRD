<?php
class Report extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template->set_layout('template-master');
        $this->module = 'Report';
        $this->curl->_filename = $this->module;
        $this->lang->load('ai', 'english');
        $this->condition_and = 'and';
        $this->condition_and_id = 1;
        $this->condition_or = 'or';
        $this->condition_or_id = 2;
        $this->load->database();
        $this->load->model('report_model');
    }

    public function index(){
        $data = [];
        $sql = $this->db->get('aicrm_reportfolder_dashboard');
        $rs_folders = $sql->result_array();

        $data['folders'] = $rs_folders;

        $this->template->build('home', $data);
    }

    public function chart(){
        $data = [];
        $sql = $this->db->get('aicrm_reportfolder_dashboard');
        $rs_folders = $sql->result_array();
        $restricted_modules = ['Events','Webmails'];
        // Primary Module
        $modules = $this->report_model->Get_Primary_Module($restricted_modules);
        // Relate Module
        $relate_modules = $this->report_model->Get_Relate_Module($restricted_modules);
        // User Group Role
        $member_group = $this->report_model->Settings_Groups_Member_Model();

        $data['folders'] = $rs_folders;
        $data['primary_modules'] = $modules;
        $data['relate_modules'] = $relate_modules;
        $data['member_group'] = $member_group;

        $this->template->build('chart', $data);
    }

    public function detailreport(){
        $data = [];

        $sql = $this->db->get('aicrm_reportfolder_dashboard');
        $rs_folders = $sql->result_array();

        $restricted_modules = ['Events','Webmails'];

        // Primary Module
        $modules = $this->report_model->Get_Primary_Module($restricted_modules);
        // Relate Module
        $relate_modules = $this->report_model->Get_Relate_Module($restricted_modules);
        // User Group Role
        $member_group = $this->report_model->Settings_Groups_Member_Model();

        $data['folders'] = $rs_folders;
        $data['primary_modules'] = $modules;
        $data['relate_modules'] = $relate_modules;
        $data['member_group'] = $member_group;

        $this->template->build('detailreport', $data);
    }

    public function getBlocksFields(){
        $post = $this->input->post();
        $primary_module = $post['primary_module'];
        $relate_module = isset($post['relate_module']) ? $post['relate_module']:[];

        $modules = [];
        $modules[] = $primary_module;
        $modules = array_merge($modules, $relate_module);

        $fields = [];
        foreach($modules as $module){
            $blocks_fields = $this->report_model->Get_Blocks_Fields($module);
            $fields = array_merge($fields, $blocks_fields);
        }

        $return = [];
        foreach($fields as $row){
            $row['type'] = $row['typeofdata'][0];
            $row['fieldvalue'] = $row['tablename'].':'.$row['columnname'].':'.$row['fieldname'].':'.$row['uitype'].':'.$row['typeofdata'][0];
            $return[] = $row;
        }
        echo json_encode($return);
    }

    public function getGroupbyFields(){
        $post = $this->input->post();
        $primary_module = $post['primary_module'];
        $relate_module = isset($post['relate_module']) ? $post['relate_module']:[];

        $modules = [];
        $modules[] = $primary_module;
        $modules = array_merge($modules, $relate_module);

        $fields = [];
        foreach($modules as $module){
            $blocks_fields = $this->report_model->Get_Blocks_Fields($module);
            $fields = array_merge($fields, $blocks_fields);
        }

        $return = [];
        foreach($fields as $row){
            if($row['typeofdata'][0]=='D' || $row['typeofdata'][0]=='DT'){

                $year = $row;
                $month = $row;

                $row['type'] = $row['typeofdata'][0];
                $row['fieldvalue'] = $row['tablename'].':'.$row['columnname'].':'.$row['fieldname'].':'.$row['uitype'].':'.$row['typeofdata'][0];
                $return[] = $row;

                $month['type'] = $month['typeofdata'][0];
                $month['fieldlabel'] = $month['fieldlabel'].' (month)';
                $month['fieldvalue'] = $month['tablename'].':'.$month['columnname'].':'.$month['fieldname'].':'.$month['uitype'].':'.$month['typeofdata'][0].':MY';
                $return[] = $month;

                $year['type'] = $year['typeofdata'][0];
                $year['fieldlabel'] = $year['fieldlabel'].' (year)';
                $year['fieldvalue'] = $year['tablename'].':'.$year['columnname'].':'.$year['fieldname'].':'.$year['uitype'].':'.$year['typeofdata'][0].':Y';
                $return[] = $year;
            }else if($row['typeofdata'][0]!='I' && $row['typeofdata'][0]!='N' && $row['typeofdata'][0]!='NN'){
                $row['type'] = $row['typeofdata'][0];
                $row['fieldvalue'] = $row['tablename'].':'.$row['columnname'].':'.$row['fieldname'].':'.$row['uitype'].':'.$row['typeofdata'][0];
                $return[] = $row;
            }
        }
        echo json_encode($return);
    }

    public function getSelectDataFields(){
        $post = $this->input->post();
        $primary_module = $post['primary_module'];
        $relate_module = isset($post['relate_module']) ? $post['relate_module']:[];

        $modules = [];
        $modules[] = $primary_module;
        $modules = array_merge($modules, $relate_module);

        $fields = [];
        foreach($modules as $module){
            $blocks_fields = $this->report_model->Get_Blocks_Fields($module);
            $fields = array_merge($fields, $blocks_fields);
        }

        $return = [];
        $return[] = [
            'type' => 'I',
            'fieldvalue' => 'RECORD_COUNT',
            'fieldlabel' => 'Record Count',
            'blocklabel' => '_ALL'
        ];
        $aggregateFunctions = $this->report_model->getAggregateFunctions();
        foreach($fields as $row){
            if($row['typeofdata'][0]=='I' || $row['typeofdata'][0]=='N' || $row['typeofdata'][0]=='NN'){
                foreach($aggregateFunctions as $function){
                    $row_data = $row;
                    $row_data['type'] = $row_data['typeofdata'][0];
                    $field_label = $row_data['fieldlabel'];
                    $row_data['fieldvalue'] = $row_data['tablename'].':'.$row_data['columnname'].':'.$row_data['fieldname'].':'.$row_data['uitype'].':'.$row_data['typeofdata'][0].':'.$function;
                    $row_data['fieldlabel'] = $row_data['fieldlabel'].' ('.$function.')';
                    $return[] = $row_data;
                }
            }
        }
        echo json_encode($return);
    }

    public function getOption(){
        $post = $this->input->post();
        $type = $post['type'];

        $field_types = $this->report_model->getAdvancedFilterOpsByFieldType();
        $field_type = $field_types[$type];

        // getAdvancedFilterOptions
        $filter_options = $this->report_model->getAdvancedFilterOptions();

        $options = [];
        for($i=0; $i<=count($field_type)-1; $i++){
            $options[] = [
                'key' => $field_type[$i],
                'label' => $this->lang->line($filter_options[$field_type[$i]])
            ];
        }
        echo json_encode($options);
    }

    public function getReportList(){
        $post = $this->input->post();
        $this->db->select('aicrm_dashboard.*, aicrm_reportmodules_dashboard.primarymodule, aicrm_reportfolder_dashboard.foldername, aicrm_users.first_name, aicrm_users.last_name');
        $this->db->join('aicrm_users', 'aicrm_users.id=aicrm_dashboard.owner', 'left');
        $this->db->join('aicrm_reportfolder_dashboard', 'aicrm_reportfolder_dashboard.folderid=aicrm_dashboard.folderid', 'left');
        $this->db->join('aicrm_reportmodules_dashboard', 'aicrm_reportmodules_dashboard.reportmodulesid=aicrm_dashboard.reportid', 'inner');
        //$this->db->join('aicrm_reporttype_dashboard', 'aicrm_reporttype_dashboard.reportid=aicrm_dashboard.reportid', 'inner');
        $this->db->where(['aicrm_dashboard.deleted'=>0]);
        $sql = $this->db->get('aicrm_dashboard');
        $result = $sql->result_array();

        // echo json_encode($fields); exit;
        echo $result; exit;

        $return = [];
        foreach($result as $row){

            $sql = $this->db->get_where('aicrm_home_dashboard', ['reportid'=>$row['reportid']]);
            $row['pin'] = $sql->num_rows()==0 ? 0:1;
            $row['graphtype'] = $row['reportcharttype'];
            $row['reporttype'] = $row['reporttype']=='chart' ? 'chart-pie':'chart-bar';
            $row['owner'] = $row['first_name'].' '.$row['last_name'];
            $return[] = $row;
        }
        echo json_encode($return);
    }

    public function generateReport(){
        $post = $this->input->post();

        $primary_module = $this->report_model->getTabInfo($post['primary_module']);
        $primary_module_id = $post['primary_module'];
        $primary_module_name = $primary_module['name'];

        $relate_module = '';
        $relate_module_id = '';
        if(!empty($post['relate_module'])){
            $modules = [];
            $relate_module_id = implode(':', $post['relate_module']);
            foreach($post['relate_module'] as $module){
                $tabinfo = $this->report_model->getTabInfo($module);
                $modules[] = $tabinfo['name'];
            }
            $relate_module = implode(':', $modules);
        }

        $this->db->insert('aicrm_dashboard', [
            'folderid' => $post['report_folder'],
            'reportname' => $post['report_name'],
            'description' => $post['step1_desc'],
            'reporttype' => $post['report_type'],
            'reportcharttype' => $post['graph_type'],
            'state' => 'CUSTOM',
            'owner' => 1,
            'sharingtype' => 'Publilc'
        ]);
        $report_id = $this->db->insert_id();
        $this->db->update('aicrm_dashboard', ['queryid'=>$report_id], ['reportid'=>$report_id]);

        $this->db->insert('aicrm_reportmodules_dashboard', [
            'reportmodulesid' => $report_id,
            'primarymodule_id' => $primary_module_id,
            'primarymodule' => $primary_module_name,
            'secondarymodules_id' => $relate_module_id,
            'secondarymodules' => $relate_module
        ]);

        if(!empty($post['share_report'])){
            foreach($post['share_report'] as $shared){
                $row = explode(':', $shared);
                switch($row[0]){
                    case'Users':
                        $this->db->insert('aicrm_report_shareusers_dashboard', ['reportid'=>$report_id, 'userid'=>$row[1]]);
                        break;
                    case'Groups':
                        $this->db->insert('aicrm_report_sharegroups_dashboard', ['reportid'=>$report_id, 'groupid'=>$row[1]]);
                        break;
                    case'Roles':
                        $this->db->insert('aicrm_report_sharerole_dashboard', ['reportid'=>$report_id, 'roleid'=>$row[1]]);
                        break;
                }
            }
        }

        $this->db->delete('aicrm_relcriteria_dashboard', ['queryid'=>$report_id]);
        $this->db->delete('aicrm_relcriteria_grouping_dashboard', ['queryid'=>$report_id]);
        $columnindex = 0;
        if(!empty($post['condition_all'])){
            $condition_expression = '';
            foreach($post['condition_all'] as $index => $row){
                if($row['all_input_field_'.($index+1)] != ''){
                    $column_condition = count($post['condition_all'])-1 == $index ? '':$this->condition_and;
                    if($index!=0) $condition_expression .= ' '.$this->condition_and.' ';
                    $condition_expression .= $columnindex;
                    $data_relcriteria = [
                        'queryid' => $report_id,
                        'columnindex' => $columnindex,
                        'columnname' => $row['all_input_field_'.($index+1)],
                        'comparator' => $row['all_input_operator_'.($index+1)],
                        'value' => $row['all_input_search_'.($index+1)],
                        'groupid' => $this->condition_and_id,
                        'column_condition' => $column_condition
                    ];
                    $this->db->insert('aicrm_relcriteria_dashboard' ,$data_relcriteria);
                    $columnindex++;
                }
            }

            if($condition_expression!=''){
                $data_relcriteria_grouping = [
                    'groupid' => $this->condition_and_id,
                    'queryid' => $report_id,
                    'group_condition' => $this->condition_and,
                    'condition_expression' => $condition_expression
                ];
                $this->db->insert('aicrm_relcriteria_grouping_dashboard', $data_relcriteria_grouping);
            }
        }

        if(!empty($post['condition_any'])){
            $condition_expression = '';
            foreach($post['condition_any'] as $index => $row){
                if($row['any_input_field_'.($index+1)] != ''){
                    $column_condition = count($post['condition_any'])-1 == $index ? '':$this->condition_or;
                    if($index!=0) $condition_expression .= ' '.$this->condition_or.' ';
                    $condition_expression .= $columnindex;
                    $data_relcriteria = [
                        'queryid' => $report_id,
                        'columnindex' => $columnindex,
                        'columnname' => $row['any_input_field_'.($index+1)],
                        'comparator' => $row['any_input_operator_'.($index+1)],
                        'value' => $row['any_input_search_'.($index+1)],
                        'groupid' => $this->condition_or_id,
                        'column_condition' => $column_condition
                    ];
                    $this->db->insert('aicrm_relcriteria_dashboard' ,$data_relcriteria);
                    $columnindex++;
                }
            }

            if($condition_expression!=''){
                $data_relcriteria_grouping = [
                    'groupid' => $this->condition_or_id,
                    'queryid' => $report_id,
                    'group_condition' => $this->condition_or,
                    'condition_expression' => $condition_expression
                ];
                $this->db->insert('aicrm_relcriteria_grouping_dashboard', $data_relcriteria_grouping);
            }
        }

        $report_type = [
            'type' => $post['graph_type'],
            'groupbyfield' => $post['select_group_by'],
            'datafields' => $post['select_data_fields']
        ];
        $this->db->insert('aicrm_reporttype_dashboard', ['reportid'=>$report_id, 'data'=>json_encode($report_type)]);

        echo json_encode(['report_id'=>$report_id]);
    }

    public function editReport(){
        $post = $this->input->post();
        $report_id = $post['reportid'];

        $this->db->delete('aicrm_relcriteria_dashboard', ['queryid'=>$report_id]);
        $this->db->delete('aicrm_relcriteria_grouping_dashboard', ['queryid'=>$report_id]);
        $columnindex = 0;
        if(!empty($post['condition_all'])){
            $condition_expression = '';
            foreach($post['condition_all'] as $index => $row){
                if($row['all_input_field_'.($index+1)] != ''){
                    $column_condition = count($post['condition_all'])-1 == $index ? '':$this->condition_and;
                    if($index!=0) $condition_expression .= ' '.$this->condition_and.' ';
                    $condition_expression .= $columnindex;
                    $data_relcriteria = [
                        'queryid' => $report_id,
                        'columnindex' => $columnindex,
                        'columnname' => $row['all_input_field_'.($index+1)],
                        'comparator' => $row['all_input_operator_'.($index+1)],
                        'value' => $row['all_input_search_'.($index+1)],
                        'groupid' => $this->condition_and_id,
                        'column_condition' => $column_condition
                    ];
                    $this->db->insert('aicrm_relcriteria_dashboard' ,$data_relcriteria);
                    $columnindex++;
                }
            }

            if($condition_expression!=''){
                $data_relcriteria_grouping = [
                    'groupid' => $this->condition_and_id,
                    'queryid' => $report_id,
                    'group_condition' => $this->condition_and,
                    'condition_expression' => $condition_expression
                ];
                $this->db->insert('aicrm_relcriteria_grouping_dashboard', $data_relcriteria_grouping);
            }
        }

        if(!empty($post['condition_any'])){
            $condition_expression = '';
            foreach($post['condition_any'] as $index => $row){
                if($row['any_input_field_'.($index+1)] != ''){
                    $column_condition = count($post['condition_any'])-1 == $index ? '':$this->condition_or;
                    if($index!=0) $condition_expression .= ' '.$this->condition_or.' ';
                    $condition_expression .= $columnindex;
                    $data_relcriteria = [
                        'queryid' => $report_id,
                        'columnindex' => $columnindex,
                        'columnname' => $row['any_input_field_'.($index+1)],
                        'comparator' => $row['any_input_operator_'.($index+1)],
                        'value' => $row['any_input_search_'.($index+1)],
                        'groupid' => $this->condition_or_id,
                        'column_condition' => $column_condition
                    ];
                    $this->db->insert('aicrm_relcriteria_dashboard' ,$data_relcriteria);
                    $columnindex++;
                }
            }

            if($condition_expression!=''){
                $data_relcriteria_grouping = [
                    'groupid' => $this->condition_or_id,
                    'queryid' => $report_id,
                    'group_condition' => $this->condition_or,
                    'condition_expression' => $condition_expression
                ];
                $this->db->insert('aicrm_relcriteria_grouping_dashboard', $data_relcriteria_grouping);
            }
        }

        $this->db->delete('aicrm_reporttype_dashboard', ['reportid'=>$report_id]);
        $report_type = [
            'type' => $post['graph_type'],
            'groupbyfield' => $post['select_group_by'],
            'datafields' => $post['select_data_fields']
        ];
        $this->db->insert('aicrm_reporttype_dashboard', ['reportid'=>$report_id, 'data'=>json_encode($report_type)]);

        echo json_encode(['report_id'=>$report_id]);
    }

    public function viewcart($id){
        $data = [];
        if($id=='') exit();

        $this->db->select('aicrm_dashboard.*, 
        aicrm_reportmodules_dashboard.*, 
        aicrm_reportfolder_dashboard.*,
        aicrm_reporttype_dashboard.*');
        $this->db->join('aicrm_reporttype_dashboard', 'aicrm_reporttype_dashboard.reportid=aicrm_dashboard.reportid', 'inner');
        $this->db->join('aicrm_reportfolder_dashboard', 'aicrm_reportfolder_dashboard.folderid=aicrm_dashboard.folderid', 'inner');
        $this->db->join('aicrm_reportmodules_dashboard', 'aicrm_reportmodules_dashboard.reportmodulesid=aicrm_dashboard.reportid', 'inner');
        $this->db->where([
            'aicrm_dashboard.reportid' => $id
        ]);
        $sql = $this->db->get('aicrm_dashboard');
        $report = $sql->row_array();

        $this->db->order_by('columnindex asc');
        $sql = $this->db->get_where('aicrm_relcriteria_dashboard', ['queryid'=>$id]);
        $conditions = $sql->result_array();

        $data['report'] = $report;
        $data['conditions'] = $conditions;

        $this->template->build('viewcart', $data);
    }

    public function addFolder(){
        $post = $this->input->post();
        $this->db->insert('aicrm_reportfolder_dashboard', [
            'foldername' => $post['foldername'],
            'state' => 'CUSTOMIZED'
        ]);
    }

    public function pinToDashboard(){
        $post = $this->input->post();
        $this->db->delete('aicrm_home_dashboard', ['reportid'=>$post['reportid']]);
        switch($post['action']){
            case'pin':
                $this->db->insert('aicrm_home_dashboard', ['reportcharttype'=>$post['graphtype'], 'reportid'=>$post['reportid']]);
                break;
            case'unpin':
                break;
        }
        echo json_encode(['status'=>1]);
    }

    public function deleteReport(){
        $post = $this->input->post();
        $this->db->update('aicrm_dashboard', ['deleted'=>1], ['reportid'=>$post['reportid']]);
    }


    public function getMSData($id ,$startdate='',$enddate=''){

        $data = [];
        if($id=='') exit();

        /*$this->db->select('aicrm_dashboard.*, 
        aicrm_reportmodules_dashboard.*, 
        aicrm_reportfolder_dashboard.*,
        aicrm_reporttype_dashboard.*');
        $this->db->join('aicrm_reporttype_dashboard', 'aicrm_reporttype_dashboard.reportid=aicrm_dashboard.reportid', 'inner');
        $this->db->join('aicrm_reportfolder_dashboard', 'aicrm_reportfolder_dashboard.folderid=aicrm_dashboard.folderid', 'inner');
        $this->db->join('aicrm_reportmodules_dashboard', 'aicrm_reportmodules_dashboard.reportmodulesid=aicrm_dashboard.reportid', 'inner');
        $this->db->where([
            'aicrm_dashboard.reportid' => $id
        ]);*/
        
        $this->db->select('aicrm_dashboard.*');
        $this->db->where([
            'aicrm_dashboard.reportid' => $id
        ]);
        $sql = $this->db->get('aicrm_dashboard');
        $report = $sql->row_array();
        //alert( $this->db); exit;
        /*$this->db->order_by('columnindex asc');
        $sql = $this->db->get_where('aicrm_relcriteria_dashboard', ['queryid'=>$id]);
        $conditions = $sql->result_array();*/
        //alert($this->db); exit;
        
        $data['report'] = $report;
       /* $data['conditions'] = $conditions;*/
        //alert($data); exit;
       /* $query = $this->report_model->getReportsQuery($data);
        $sql = $this->db->query($query);
        $result = $sql->result_array();*/
        /*$report_data = json_decode($data['report']['data'], true);
        $field = explode(':',  $report_data['groupbyfield']);
        $category_field = $field[1];*/
        
        $category = [];
        $dataset = [];
        $json = [];
        
        //date("Y-m%", strtotime( date( 'Y-m-01' )." -$i months"))
        if($report['reportcharttype'] == 'line-basic'){

            if($report['reportid'] == '40'){
                $query = "select 
                count(aicrm_crmentity.crmid) as record , 
                WEEK(aicrm_crmentity.createdtime) as week ,
                YEAR(aicrm_crmentity.createdtime) as year ,
                DATE_SUB(
                  DATE_ADD(MAKEDATE(YEAR(aicrm_crmentity.createdtime), 1), INTERVAL WEEK(aicrm_crmentity.createdtime) WEEK),
                  INTERVAL WEEKDAY(
                  DATE_ADD(MAKEDATE(YEAR(aicrm_crmentity.createdtime), 1), INTERVAL WEEK(aicrm_crmentity.createdtime) WEEK)
                ) +1 DAY) as startweek,
                DATE_SUB(
                  DATE_ADD(MAKEDATE(YEAR(aicrm_crmentity.createdtime), 1), INTERVAL WEEK(aicrm_crmentity.createdtime) WEEK),
                  INTERVAL WEEKDAY(
                  DATE_ADD(MAKEDATE(YEAR(aicrm_crmentity.createdtime), 1), INTERVAL WEEK(aicrm_crmentity.createdtime) WEEK)
                ) -5 DAY) as endweek
                from aicrm_leaddetails
                inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
                where aicrm_crmentity.deleted = 0 ";
                //date("Y-m-d", strtotime( date( 'Y-m-d' )." -6 months"))
                if(isset($startdate) && $startdate !=''){
                    $query .= " AND aicrm_crmentity.createdtime >= '".$startdate."'
                                AND aicrm_crmentity.createdtime <= '".$enddate."' ";
                }else{
                    $query .= " AND aicrm_crmentity.createdtime >= '".date("Y-m-d", strtotime( date( 'Y-m-d' )." -6 months"))."'
                                AND aicrm_crmentity.createdtime <= '".date('Y-m-d')."' ";
                }
                
                //$query .= " AND aicrm_crmentity.createdtime >= '2019-01-01' AND aicrm_crmentity.createdtime <= '2021-01-26' ";
                
                $query .= "  group by WEEK(aicrm_crmentity.createdtime),YEAR(aicrm_crmentity.createdtime) order by YEAR(aicrm_crmentity.createdtime) ASC , WEEK(aicrm_crmentity.createdtime)ASC ";
                //echo $query; exit;
                $sql = $this->db->query($query);
                $result = $sql->result_array();
                $data_chart = [];
                $categories = array();
                foreach($result as $key => $value){
                    $data_chart[0]['data'][] = $value['record'];
                    //array_push($data_chart[],$value['record']);
                    $week = date('d-m-Y', strtotime($value['startweek'])).' - '. date('d-m-Y', strtotime($value['endweek']));
                    array_push($categories,$week);
                }
                $data_chart[0]['name'] = 'Count';

            }else if($report['reportid'] == '48'){
                $query = "select 
                    count(aicrm_crmentity.crmid) as record , 
                    MONTH(aicrm_crmentity.createdtime) as month ,
                    YEAR(aicrm_crmentity.createdtime) as year 
                    from aicrm_leaddetails
                    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
                    where aicrm_crmentity.deleted = 0 ";
                //$query .= " AND aicrm_crmentity.createdtime >= '2019-01-01' AND aicrm_crmentity.createdtime <= '2021-12-31' ";
                /*echo date('Y-m-d');
                echo "</br>";
                echo date("Y-m-d", strtotime( date( 'Y-m-d' )." -3 years"));*/
                if(isset($startdate) && $startdate !=''){
                    $query .= " AND aicrm_crmentity.createdtime >= '".$startdate."'
                                AND aicrm_crmentity.createdtime <= '".$enddate."' ";
                }else{
                    $query .= " AND aicrm_crmentity.createdtime >= '".date("Y-m-d", strtotime( date( 'Y-m-d' )." -3 years"))."'
                                AND aicrm_crmentity.createdtime <= '".date('Y-m-d')."' ";
                }
                $query .= " group by MONTH(aicrm_crmentity.createdtime),YEAR(aicrm_crmentity.createdtime) order by YEAR(aicrm_crmentity.createdtime) ASC , MONTH(aicrm_crmentity.createdtime)ASC ";
                //echo $query; exit;
                $sql = $this->db->query($query);
                $result = $sql->result_array();
                
                $data_chart = array();
                $data_temp = array();
                $categories = array();
                //$categories = ['January','February','March','April','May','June','July','August','September','October','November','December'];
                $categories = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                //alert($categories); exit;
                foreach($result as $key => $value){
                    $data_temp[$value['year']][ $value['month']] = $value;
                }
                $i = 0;
                $a = 0;
                foreach ($data_temp as $k => $v) {
                    $data_chart[$a]['name'] = $k;

                    for($n=1;$n<=12;$n++){
                        if(isset($v[$n]) && $v[$n]['month'] == $n){
                            $data_chart[$a]['data'][] = $v[$n]['record'];
                        }else{
                          $data_chart[$a]['data'][] = 0;  
                        }
                    }
                    $a++;
                    $i++;
                }
                
            }

            $json['title'] = $data['report']['reportname'];
            $json['data'] = $data_chart;
            $json['categories'] = $categories;
            //echo json_encode($json); exit;

        }else if($report['reportcharttype'] == 'brush-chart'){
             if($report['reportid'] == '41'){
                $query = "select 
                count(aicrm_crmentity.crmid) as record , 
                LEFT(aicrm_crmentity.createdtime,10) as day 
                from aicrm_leaddetails
                inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
                where aicrm_crmentity.deleted = 0 ";
                //$query .= " AND aicrm_crmentity.createdtime >= '2020-01-01' AND aicrm_crmentity.createdtime <= '2021-01-26' ";
                
                //date("Y-m-d", strtotime( date( 'Y-m-d' )." -3 months"))
                //
                if(isset($startdate) && $startdate !=''){
                    $query .= " AND aicrm_crmentity.createdtime >= '".$startdate."'
                                AND aicrm_crmentity.createdtime <= '".$enddate."' ";
                }else{
                    $query .= " AND aicrm_crmentity.createdtime >= '".date("Y-m-d", strtotime( date( 'Y-m-d' )." -3 months"))."'
                                AND aicrm_crmentity.createdtime <= '".date("Y-m-d")."' ";
                }
                $query .= " group by LEFT(aicrm_crmentity.createdtime,10) order by aicrm_crmentity.createdtime ASC";
                //echo $query; exit;
                $sql = $this->db->query($query);
                $result = $sql->result_array();
                $data_chart = array();
                $categories = array();

                foreach($result as $key => $value){
                    array_push($data_chart,$value['record']);
                    array_push($categories,date('d-m-Y', strtotime($value['day'])));
                }
            }
            $json['title'] = $data['report']['reportname'];
            $json['data'] = $data_chart;
            $json['categories'] = $categories;

        }else if($report['reportcharttype'] == 'stacked-columns'){
            
            $data_chart = array();
            $categories = array();
            
            if($report['reportid'] == '42'){
                $query = "select 
                count(aicrm_crmentity.crmid) as record , 
                aicrm_leaddetails.leadsource , 
                MONTH(aicrm_crmentity.createdtime) as month ,
                YEAR(aicrm_crmentity.createdtime) as year 
                from aicrm_leaddetails
                inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
                where aicrm_crmentity.deleted = 0 ";

                $query .= " AND aicrm_leaddetails.leadsource is not null ";

                //date("Y-m-d", strtotime( date( 'Y-m-d' )." -6 months"))
                if(isset($startdate) && $startdate !=''){
                    $query .= " AND aicrm_crmentity.createdtime >= '".$startdate."'
                                AND aicrm_crmentity.createdtime <= '".$enddate."' ";
                }else{
                    $query .= " AND aicrm_crmentity.createdtime >= '".date("Y-m-d", strtotime( date( 'Y-m-d' )." -6 months"))."'
                                AND aicrm_crmentity.createdtime <= '".date("Y-m-d")."' ";
                }
                $query .= " group by aicrm_leaddetails.leadsource,MONTH(aicrm_crmentity.createdtime),YEAR(aicrm_crmentity.createdtime) order by YEAR(aicrm_crmentity.createdtime) ASC , MONTH(aicrm_crmentity.createdtime)ASC ,aicrm_leaddetails.leadsource ASC";
                //echo $query; exit;
                $sql = $this->db->query($query);
                $result = $sql->result_array();

                //$categories = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                //$leadsource = ['Marketing','Referral','Sales'];
                $leadsource = ['เฟสบุ๊ค','ไลน์ออฟฟิเชียล','ไลน์ส่วนตัว','เฟสบุ๊คส่วนตัว','สาขา','อื่นๆ'];
                /*เฟสบุ๊ค
                ไลน์ออฟฟิเชียล
                ไลน์ส่วนตัว
                เฟสบุ๊คส่วนตัว
                สาขา
                อื่นๆ*/
                
                $month = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
                $data_temp = array();
                $set_temp = array();
                $temp = array();
                $m = '';
                $y = '';
                    
                foreach ($result as $k_result => $v_result) {
                    $set_temp[$v_result['year']][$v_result['month']] ='';                   
                }

                foreach ($set_temp as $k_temp => $v_temp) {
                    
                    foreach ($v_temp as $k_month => $v_month) {
                        
                        foreach ($leadsource as $k_cat) {
                            $temp[$k_temp][$k_month][$k_cat] = ['record'=> 0, 'leadsource'=> $k_cat, 'month'=> $k_month, 'year'=> $k_temp ,'name'=>$month[$k_month]." ".$k_temp];
                        }

                        if($k_temp != $y){ 
                            $categories[] = $month[$k_month]." ".$k_temp;
                        }else if($k_month != $m){
                            $categories[] = $month[$k_month]." ".$k_temp;
                        }
                        $m = $k_month;
                        $y = $k_temp;
                        
                    }
                }
                

                foreach($result as $key => $value){
                    $value['name'] = $month[$value['month']]." ".$value['year'];
                    $data_temp[$value['year']][$value['month']][] = $value;
                }

                foreach ($data_temp as $k_data => $v_data) {
                    foreach ($v_data as $kk => $vv) {
                        foreach ($vv as $kkk => $vvv){
                            $temp[$vvv['year']][$vvv['month']][$vvv['leadsource']]['record'] = $vvv['record'];
                        }
                    }
                }
                //alert($temp); exit;
                $data_t = array();
                foreach ($temp as $key => $value) {
                    foreach ($value as $k => $v) {
                        foreach ($v as $kv => $vv) {
                            $data_t[] = $vv;
                        }
                    }
                
                }
                //alert($data_t); exit;
                $source = '';
                $i=0;
                foreach ($leadsource as $k_lead) {
                    
                    foreach ($data_t as $key => $value) {
                        if($k_lead == $value['leadsource']){
                            $data_chart[$i]['name'] = $value['leadsource'];
                            $data_chart[$i]['data'][] = $value['record'];
                        }
                    }
                    
                    $i++;
                }
                //alert($data_chart); exit;
                /*$n=0;
                foreach ($temp as $k_t => $v_t) {
                    foreach ($v_t as $k_v => $v_v) {
                        $i=0;
                       foreach ($v_v as $kkk => $vvv){
                            $data_chart[$n]['name'] =$vvv['name'];
                            $data_chart[$n]['data'][$i] = $vvv['record'];
                            $i++;
                        }
                       $n++;
                    }
                }*/
                /*name: 'PRODUCT A',
                data: [44, 55, 41, 67, 22, 43]*/
            }else if($report['reportid'] == '45'){

                $query = "select 
                count(aicrm_crmentity.crmid) as record , 
                aicrm_leaddetails.leadstatus , 
                MONTH(aicrm_crmentity.createdtime) as month ,
                YEAR(aicrm_crmentity.createdtime) as year 
                from aicrm_leaddetails
                inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
                where aicrm_crmentity.deleted = 0 ";

                $query .= " AND aicrm_leaddetails.leadstatus is not null ";
                //date("Y-m-d", strtotime( date( 'Y-m-d' )." -6 months"))
                if(isset($startdate) && $startdate !=''){
                    $query .= " AND aicrm_crmentity.createdtime >= '".$startdate."'
                                AND aicrm_crmentity.createdtime <= '".$enddate."' ";
                }else{
                    $query .= " AND aicrm_crmentity.createdtime >= '".date("Y-m-d", strtotime( date( 'Y-m-d' )." -6 months"))."'
                                AND aicrm_crmentity.createdtime <= '".date("Y-m-d")."' ";
                }
                $query .= " group by aicrm_leaddetails.leadstatus,MONTH(aicrm_crmentity.createdtime),YEAR(aicrm_crmentity.createdtime) order by YEAR(aicrm_crmentity.createdtime) ASC , MONTH(aicrm_crmentity.createdtime)ASC,aicrm_leaddetails.leadstatus ASC";
                //echo $query; exit;
                $sql = $this->db->query($query);
                $result = $sql->result_array();

                //$leadsource = ['Contacted','Nuture','Open','Qualified','UnQualified'];
                $leadsource = ['Open','Dead','Converted'];

                $month = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
                $data_temp = array();
                $set_temp = array();
                $temp = array();
                $m = '';
                $y = '';
                    
                foreach ($result as $k_result => $v_result) {
                    $set_temp[$v_result['year']][$v_result['month']] ='';                   
                }

                foreach ($set_temp as $k_temp => $v_temp) {
                    
                    foreach ($v_temp as $k_month => $v_month) {
                        
                        foreach ($leadsource as $k_cat) {
                            $temp[$k_temp][$k_month][$k_cat] = ['record'=> 0, 'leadstatus'=> $k_cat, 'month'=> $k_month, 'year'=> $k_temp ,'name'=>$month[$k_month]." ".$k_temp];
                        }

                        if($k_temp != $y){ 
                            $categories[] = $month[$k_month]." ".$k_temp;
                        }else if($k_month != $m){
                            $categories[] = $month[$k_month]." ".$k_temp;
                        }
                        $m = $k_month;
                        $y = $k_temp;
                        
                    }
                }
                

                foreach($result as $key => $value){
                    $value['name'] = $month[$value['month']]." ".$value['year'];
                    $data_temp[$value['year']][$value['month']][] = $value;
                }

                foreach ($data_temp as $k_data => $v_data) {
                    foreach ($v_data as $kk => $vv) {
                        foreach ($vv as $kkk => $vvv){
                            $temp[$vvv['year']][$vvv['month']][$vvv['leadstatus']]['record'] = $vvv['record'];
                        }
                    }
                }
                //alert($temp); exit;
                $data_t = array();
                foreach ($temp as $key => $value) {
                    foreach ($value as $k => $v) {
                        foreach ($v as $kv => $vv) {
                            $data_t[] = $vv;
                        }
                    }
                
                }
                //alert($data_t); exit;
                $source = '';
                $i=0;
                foreach ($leadsource as $k_lead) {
                    
                    foreach ($data_t as $key => $value) {
                        if($k_lead == $value['leadstatus']){
                            $data_chart[$i]['name'] = $value['leadstatus'];
                            $data_chart[$i]['data'][] = $value['record'];
                        }
                    }
                    
                    $i++;
                }
                //alert($data_chart); exit;
                /*$n=0;
                foreach ($temp as $k_t => $v_t) {
                    foreach ($v_t as $k_v => $v_v) {
                        $i=0;
                       foreach ($v_v as $kkk => $vvv){
                            $data_chart[$n]['name'] =$vvv['name'];
                            $data_chart[$n]['data'][$i] = $vvv['record'];
                            $i++;
                        }
                       $n++;
                    }
                }*/
                /*name: 'PRODUCT A',
                data: [44, 55, 41, 67, 22, 43]*/

            }
            
            $json['title'] = $data['report']['reportname'];
            $json['data'] = $data_chart;
            $json['categories'] = $categories;

        }else if($report['reportcharttype'] == 'grid'){
            $data_grid = array();
            $categories = array();
            $columns = array();

            if($report['reportid'] == '33'){

                $query = "select
                    aicrm_leaddetails.lead_no as Leadno,
                    concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) as Name ,
                    aicrm_leaddetails.mobile as Mobile,
                    aicrm_leaddetails.email as Email,
                    aicrm_leaddetails.leadsource as Leadsource,
                    DATE_FORMAT(aicrm_crmentity.createdtime,'%d-%m-%Y %H:%i') Createdate
                    from aicrm_leaddetails
                    inner join aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
                    where aicrm_crmentity.deleted = 0 
                    order by aicrm_crmentity.createdtime DESC
                    Limit 10 ";

                $sql = $this->db->query($query);
                $result = $sql->result_array();

                foreach ($result as $key => $value) {
                    array_push($data_grid,$value);
                }
                //genLabel('LBL_SAVE');
                //$this->lang->load('ai', 'english');
                //$label = $ci->lang->line('LBL_CALL_IN') == '' ? $label : $ci->lang->line('LBL_CALL_IN');
                $ci = &get_instance();
                $ci->lang->load('ai', 'english');
                /*$label = $ci->lang->line('LBL_CALL_IN') == '' ? $label : $ci->lang->line('LBL_CALL_IN');
                echo $label;*/

                $k_field = array_keys($result[0]); 
                foreach ($k_field as $key => $value) {
                    //$columns[$key]['title']=$value;

                    $columns[$key]['title'] = $ci->lang->line($value) == '' ? $value : $ci->lang->line($value);
                    $columns[$key]['data']=$value;
                }
                $orderby = 0;
                /*alert($data_grid);*/
                
            }else if($report['reportid'] == '44'){
                $query = "select
                    -- aicrm_leaddetails.leadid as id,
                    aicrm_leaddetails.lead_no as Leadno,
                    concat(aicrm_leaddetails.firstname,' ',aicrm_leaddetails.lastname) as Name ,
                    aicrm_leaddetails.mobile as Mobile,
                    aicrm_leaddetails.email as Email,
                    aicrm_leaddetails.leadsource as Leadsource,
                    DATE_FORMAT(aicrm_crmentity.createdtime,'%d-%m-%Y %H:%i') Createdate,
                    DATE_FORMAT(aicrm_convert_lead2acc.createdate,'%d-%m-%Y %H:%i') Convertdate                    
                    from aicrm_leaddetails
                    INNER JOIN aicrm_crmentity on aicrm_crmentity.crmid = aicrm_leaddetails.leadid
                    INNER JOIN aicrm_convert_lead2acc on aicrm_convert_lead2acc.leadid = aicrm_leaddetails.leadid
                    where aicrm_crmentity.deleted = 0 
                    order by aicrm_convert_lead2acc.createdate DESC
                    Limit 10 ";

                $sql = $this->db->query($query);
                $result = $sql->result_array();

                foreach ($result as $key => $value) {
                    array_push($data_grid,$value);
                }
                
                $ci = &get_instance();
                $ci->lang->load('ai', 'english');

                $k_field = array_keys($result[0]); 
                foreach ($k_field as $key => $value) {
                    $columns[$key]['title'] = $ci->lang->line($value) == '' ? $value : $ci->lang->line($value);
                    //$columns[$key]['title']=$value;
                    $columns[$key]['data']=$value;
                }

                $orderby = 6;
                /*alert($data_grid);
                alert($columns);exit;*/
            }

            $json['title'] = $data['report']['reportname'];
            $json['data'] = $data_grid;
            $json['orderby'] = @$orderby;
            $json['columns'] = @$columns;
            $json['categories'] = $categories;
        }

        /*if($report['reportcharttype'] != 'pie3d'){
            // alert($report_data['datafields']);
            // alert($dataset);
            foreach($report_data['datafields'] as $index => $row){
                $field = explode(':', $row);
                $dataset[$index]['seriesname'] = $field[0]=='RECORD_COUNT' ? $field[0]:$field[5].'_'.$field[1];
                $dataset[$index]['data'] = [];
                foreach($result as $datarow){
                    if($index==0) $category[]['label'] = $datarow[$category_field];
                    $dataset[$index]['data'][]['value'] = $datarow[$dataset[$index]['seriesname']];
                }
            }
            $json = [
                'title' => $data['report']['reportname'],
                'categories' => ['category' => $category],
                'dataset' => $dataset
            ];
            // echo $dataset; exit;
        }else{
            foreach($result as $datarow){
                $dataset[] = [
                    // 'label' => $datarow[$category_field],
                    'value' => $datarow[$report_data['datafields'][0]]
                ];
            }
            $json = [
                'title' => $data['report']['reportname'],
                'data' => $dataset
            ];
        }*/
        
        echo json_encode($json);
    }

    public function addDashboards() {
        echo 'Add Dashboards'; exit;
    }

    public function addWidget() {
        echo "Add Widget"; exit;
    }

    public function setasdefault() {
        echo "Set As Default"; exit;
    }

    public function delete() {
        echo "Delete"; exit;
    }

    public function duplicate() {
        echo "Duplicate"; exit;
    }

    public function rename() {
        echo "Rename"; exit;
    }

    public function share() {
        echo "Share"; exit;
    }

    public function export() {
        echo "Export"; exit;
    }

    
}