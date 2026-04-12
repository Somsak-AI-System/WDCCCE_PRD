<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends MY_Controller
{
    private $description, $title, $keyword, $screen,$modulename;
    public function __construct()
    {
        parent::__construct();
        $this->template->set_layout('template-master');
        $this->module = 'Dashboard';
        $this->curl->_filename = $this->module;
        $this->lang->load('ai', 'english');
        $this->load->database();
    }

    public function index()
    {
        
        //alert ($current_user); exit();
        // echo USERID; exit;
        $data = array();
        $widget_list = [];
         $this->db->where([
            'aicrm_dashboard.reporttype' => 'chart',
            'aicrm_dashboard.deleted' => 0
        ]);

        $sql = $this->db->get('aicrm_dashboard');
        $widget_list = $sql->result_array();

        $templates = [];
        $this->db->join('aicrm_dashboard', 'aicrm_dashboard.reportid=aicrm_home_dashboard.reportid', 'inner');
        $this->db->where([
            'aicrm_dashboard.reporttype' => 'chart',
            'aicrm_dashboard.deleted' => 0
        ]);
        $sql = $this->db->get('aicrm_home_dashboard');

        $template_home = $sql->result_array();

        $templates['home'] = [
            'title' => 'Home',
            'status' => 'active',
            'owner' => 'admin admin',
            'desciption' => 'Dashboard home'
        ];
        foreach($template_home as $i => $row){
            $row_data = [
                'reportcharttype' => $row['reportcharttype'],
                'reportid' => $row['reportid'],
                'reportname' => $row['reportname'],
                'chartid' => $row['chartid'],
                'x' => $row['x_axis']=='' ? $i*3:$row['x_axis'],
                'y' => $row['y_axis']=='' ? 0:$row['y_axis'],
                'width' => $row['width']=='' ? 3:$row['width'],
                'height' => $row['height']=='' ? 4:$row['height'],
            ];
            $templates['home']['widgets'][] = $row_data;
        }

        
        $this->db->select('aicrm_tab_dashboard.*,concat(aicrm_users.first_name," ",aicrm_users.last_name) as owner',false);
        
        $this->db->join('aicrm_users', 'aicrm_users.id=aicrm_tab_dashboard.userid', 'left');
        $sql = $this->db->get_where('aicrm_tab_dashboard');
        $hometabs = $sql->result_array();

        foreach($hometabs as $tab){
            if($tab['status_active']==1){
                $templates['home']['status'] = '';
            }

            $templates[$tab['id']] = [
                'title' => $tab['tab_name'],
                'status' => $tab['status_active']==0 ? '':'active',
                'owner' => @$tab['owner'],
                'desciption' => $tab['desciption'],
                'widgets' => []
            ];
            // alert($templates); exit;
            $this->db->join('aicrm_dashboard', 'aicrm_dashboard.reportid=aicrm_tabwidget_dashboard.reportid', 'inner');
            $this->db->where([
                'aicrm_dashboard.reporttype' => 'chart',
                'aicrm_dashboard.deleted' => 0,
                'aicrm_tabwidget_dashboard.tabid' => $tab['id']
            ]);
            $sql = $this->db->get('aicrm_tabwidget_dashboard');
            $widgets = $sql->result_array();
            foreach($widgets as $i => $widget){
                $templates[$tab['id']]['widgets'][] = [
                    'reportcharttype' => $widget['reportcharttype'],
                    'reportid' => $widget['reportid'],
                    'reportname' => $widget['reportname'],
                    'chartid' => $widget['chartid'],
                    'x' => $widget['x_axis']=='' ? $i*3:$widget['x_axis'],
                    'y' => $widget['y_axis']=='' ? 0:$widget['y_axis'],
                    'width' => $widget['width']=='' ? 3:$widget['width'],
                    'height' => $widget['height']=='' ? 4:$widget['height']
                ];
            }
        }

        //Menu widgets
        $widget_menu = [];
        $this->db->select('aicrm_widget.widgettype ,aicrm_widget.widgettypename , aicrm_widget_dashboard.*',false);
        $this->db->join('aicrm_widget', 'aicrm_widget.widgetid=aicrm_widget_dashboard.widgetid', 'inner');
        $this->db->where([
            'aicrm_widget_dashboard.deleted' => 0
        ]);
        $this->db->order_by("aicrm_widget_dashboard.parenttabseq asc,aicrm_widget_dashboard.moduleseq asc, aicrm_widget_dashboard.widgetseq asc");
        $sql = $this->db->get('aicrm_widget_dashboard');
        $d_widgets = $sql->result_array();
        
        $module = '';
        $parenttab = '';
        //alert( $this->db); exit;
        foreach ($d_widgets as $k => $v) {
            if($parenttab != $v['parenttab']){
                
                if($module != $v['module']){
                    $widget_menu[$v['parenttab']][$v['module']][] = ['widgetid'=>$v['widgetid'],'widgetname'=>$v['widgetname'],'chartid'=>$v['chartid'],'widgettype'=>$v['widgettype'],'reportid'=>$v['reportid'],'widgettypename'=>$v['widgettypename']];
                }else{
                    $widget_menu[$v['parenttab']][$v['module']][] = ['widgetid'=>$v['widgetid'],'widgetname'=>$v['widgetname'],'chartid'=>$v['chartid'],'widgettype'=>$v['widgettype'],'reportid'=>$v['reportid'],'widgettypename'=>$v['widgettypename']];
                }
                
                $parenttab = $v['parenttab'];
                $module = $v['module'];
            }else{

                if($module != $v['module']){
                    $widget_menu[$v['parenttab']][$v['module']][] = ['widgetid'=>$v['widgetid'],'widgetname'=>$v['widgetname'],'chartid'=>$v['chartid'],'widgettype'=>$v['widgettype'],'reportid'=>$v['reportid'],'widgettypename'=>$v['widgettypename']];
                }else{
                    $widget_menu[$v['parenttab']][$v['module']][] = ['widgetid'=>$v['widgetid'],'widgetname'=>$v['widgetname'],'chartid'=>$v['chartid'],'widgettype'=>$v['widgettype'],'reportid'=>$v['reportid'],'widgettypename'=>$v['widgettypename']];
                }

                $parenttab = $v['parenttab'];
                $module = $v['module'];
            }
        }
        
        $data['widget_menu'] = $widget_menu;
        $data['widgets'] = $widget_list;
        $data['templates'] = $templates;
        $data['userid'] = USERID;
        // $data['owner'] = $tab['owner'];
        // alert($data); exit;
        $this->template->build('index', $data);
    }


    public function addTab()
    {
        $post = $this->input->post();
        $this->db->select('aicrm_tab_dashboard.*,concat(aicrm_users.first_name," ",aicrm_users.last_name) as owner',false);
        $this->db->join('aicrm_users', 'aicrm_users.id=aicrm_tab_dashboard.userid', 'left');
        $sql = $this->db->get_where('aicrm_tab_dashboard', ['tab_name'=>$post['dashboard_title']]);
        //alert($sql->num_rows()); exit;
        $data = [];
        $data['status'] = true;
        $data['tab_name'] = $post['dashboard_title'];
        $data['userid'] = $post['userid'];
        $data['owner'] = FIRSTNAME;
        // $data['owner'];
        if($sql->num_rows() == 0){
            // echo USERID; exit();
            // FIRSTNAME
            $this->db->insert('aicrm_tab_dashboard', ['tab_name'=>$post['dashboard_title'], 'userid'=>$post['userid']]);
            $data['tab_id'] = $this->db->insert_id();
        }else{
            $data['status'] = false;
        }



        echo json_encode($data);
    }

    public function removeTab()
    {
        $post = $this->input->post();
        $tab_id = $post['tab_id'];

        if($tab_id != 'home'){
            $this->db->delete('aicrm_tab_dashboard', ['id'=>$tab_id]);
            $this->db->delete('aicrm_tabwidget_dashboard', ['tabid'=>$tab_id]);
        }
    }

    public function setTabActive()
    {
        $post = $this->input->post();
        $tab_id = $post['tab_id'];

        $this->db->update('aicrm_tab_dashboard', ['status_active'=>0]);
        if($tab_id!='home'){
            $this->db->update('aicrm_tab_dashboard', ['status_active'=>1], ['id'=>$tab_id]);
        }
    }

    public function addTabContent()
    {
        $post = $this->input->post();
        $data = [];
        $tab_id = $post['tab_id'];

        $this->db->where([
            'aicrm_dashboard.reporttype' => 'chart',
            'aicrm_dashboard.deleted' => 0
        ]);
        $sql = $this->db->get('aicrm_dashboard');
        // $widget_list = $sql->result_array();

        // $data['widgets'] = $widget_list;
        $data['key'] = $tab_id;

        $this->load->view('temp_tab', $data);
    }

    public function getData()
    {
        $post = $this->input->post();

        $id = $post['id'];

        $string = file_get_contents(site_url('json/' . $post['file']));
        $result = json_decode($string, true);
        $data = [];

        if ($result['status'] == true) {
            $data['tooltip_pattern'] = @$result['tooltip_pattern'];
            $data['showValues'] = isset($result['showValues']) ? $result['showValues'] : 0;
            $data['numberSuffix'] = @$result['numberSuffix'];
            foreach ($result['data'] as $i => $row) {
                if ($i == 0) {
                    $data['title'] = $row['title'];
                }
                $data['data'][] = [
                    "label" => $row['category'],
                    "value" => $row['value']
                ];
            }
        }

        echo json_encode($data);
    }

    public function getMSData()
    {
        $post = $this->input->post();

        $id = $post['id'];

        $string = file_get_contents(site_url('json/' . $post['file']));
        $result = json_decode($string, true);
        $data = [];

        $categories = [];
        $dataset = [];
        if ($result['status'] == true) {
            $data['tooltip_pattern'] = @$result['tooltip_pattern'];
            $data['showValues'] = isset($result['showValues']) ? $result['showValues'] : 0;
            foreach ($result['data'] as $i => $row) {
                if ($i == 0) {
                    $data['title'] = $row['title'];
                    foreach ($row as $key => $value) {
                        $pattern = '/value/i';
                        if (preg_match($pattern, $key)) {
                            $categories[] = [
                                'label' => $value,
                                'anchorImageUrl' => 'https://static.fusioncharts.com/sampledata/userimages/4.png'
                            ];
                        }
                    }
                    $data['categories'] = [
                        'category' => $categories
                    ];
                } else {
                    $dataserie = [];
                    foreach ($row as $key => $value) {
                        $pattern = '/value/i';
                        if (preg_match($pattern, $key)) {
                            $dataserie[] = [
                                'value' => $value
                            ];
                        }
                    }
                    $dataset[] = [
                        'seriesname' => $row['serie'],
                        'data' => $dataserie
                    ];
                    $data['dataset'] = $dataset;
                }
            }
        }

        echo json_encode($data);
    }

    public function updateTemplate()
    {
        $post = $this->input->post();

        if (isset($post['data']) && count($post['data']) > 0) {
            if($post['data'][0]['tabid'] == 'home'){
                foreach($post['data'] as $row){
                    $this->db->delete('aicrm_home_dashboard', ['reportid' => $row['id']]);
                    $this->db->insert('aicrm_home_dashboard', [
                        'reportid' => $row['id'],
                        'reportcharttype' => $row['type'],
                        'chartid' => $row['chartid'],
                        'x_axis' => $row['x'],
                        'y_axis' => $row['y'],
                        'height' => $row['height'],
                        'width' => $row['width']
                    ]);
                }
            }else{
                foreach($post['data'] as $row){
                    $this->db->delete('aicrm_tabwidget_dashboard', ['tabid'=>$row['tabid'], 'reportid' => $row['id']]);
                    $this->db->insert('aicrm_tabwidget_dashboard', [
                        'tabid' => $row['tabid'],
                        'reportid' => $row['id'],
                        'reportcharttype' => $row['type'],
                        'chartid' => $row['chartid'],
                        'x_axis' => $row['x'],
                        'y_axis' => $row['y'],
                        'height' => $row['height'],
                        'width' => $row['width']
                    ]);
                }
            }
        }

        echo json_encode($post['data']);
    }

    public function removeWidget()
    {
        $post = $this->input->post();
        $tab_id = $post['tab_key'];
        $id = $post['id'];

        if($tab_id=='home'){
            $this->db->delete('aicrm_home_dashboard',['reportid'=>$id]);
        }else{
            $this->db->delete('aicrm_tabwidget_dashboard', ['tabid'=>$tab_id, 'reportid'=>$id]);
        }
    }

    public function addDashboardsdetails() {
        // echo "Add Dashboards Details"; exit;
        $post = $this->input->post();
        $tab_id = $post['tab_id'];
        $dashboardtitle = $post['dashboardtitle'];
        $desciption = $post['desciption'];

        if ($tab_id == 'home') {
            // echo $tab_id; exit;
        } else {
            // echo $post['data']; exit;
            // echo $tab_id; exit;
            $this->db->update('aicrm_tab_dashboard', ['desciption'=>$desciption, 'tab_name'=>$dashboardtitle], ['id'=>$tab_id]);
            // foreach ($post['data'] as $row) {
            //     // echo $row['dashboardtitle']; exit();
            //     $this->db->update('aicrm_tab_dashboard', ['id'=>$tab_id], [
            //         'tab_name' => $row['dashboardtitle'],
            //         'desciption' => $row['desciption'],
            //     ]);
            // }

        }

        // echo json_encode($post['data']);


    }

}