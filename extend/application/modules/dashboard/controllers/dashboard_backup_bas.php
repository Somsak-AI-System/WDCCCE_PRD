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
        $data = array();
        $widget_list = [];
        $this->db->where([
            'aicrm_report_dashboard.reporttype' => 'chart',
            'aicrm_report_dashboard.deleted' => 0
        ]);

        $sql = $this->db->get('aicrm_report_dashboard');
        $widget_list = $sql->result_array();

        $templates = [];
        $this->db->join('aicrm_report_dashboard', 'aicrm_report_dashboard.reportid=aicrm_homereportchart_dashboard.reportid', 'inner');
        $this->db->where([
            'aicrm_report_dashboard.reporttype' => 'chart',
            'aicrm_report_dashboard.deleted' => 0
        ]);
        $sql = $this->db->get('aicrm_homereportchart_dashboard');
        //alert($this->db); exit;
        $template_home = $sql->result_array();
        
        $templates['home'] = [
            'title' => 'Home',
            'status' => 'active'
        ];
        foreach($template_home as $i => $row){
            $row_data = [
                'reportcharttype' => $row['reportcharttype'],
                'reportid' => $row['reportid'],
                'reportname' => $row['reportname'],
                'x' => $row['x_axis']=='' ? $i*3:$row['x_axis'],
                'y' => $row['y_axis']=='' ? 0:$row['y_axis'],
                'width' => $row['width']=='' ? 3:$row['width'],
                'height' => $row['height']=='' ? 4:$row['height']
            ];
            $templates['home']['widgets'][] = $row_data;
        }

        $sql = $this->db->get_where('aicrm_hometab_dashboard');
        $hometabs = $sql->result_array();
        foreach($hometabs as $tab){
            if($tab['status_active']==1){
                $templates['home']['status'] = '';
            }

            $templates[$tab['id']] = [
                'title' => $tab['tab_name'],
                'status' => $tab['status_active']==0 ? '':'active',
                'widgets' => []
            ];

            $this->db->join('aicrm_report_dashboard', 'aicrm_report_dashboard.reportid=aicrm_homewidget_dashboard.reportid', 'inner');
            $this->db->where([
                'aicrm_report_dashboard.reporttype' => 'chart',
                'aicrm_report_dashboard.deleted' => 0,
                'aicrm_homewidget_dashboard.tabid' => $tab['id']
            ]);
            $sql = $this->db->get('aicrm_homewidget_dashboard');

            $widgets = $sql->result_array();
            foreach($widgets as $i => $widget){
                $templates[$tab['id']]['widgets'][] = [
                    'reportcharttype' => $widget['reportcharttype'],
                    'reportid' => $widget['reportid'],
                    'reportname' => $widget['reportname'],
                    'x' => $widget['x_axis']=='' ? $i*3:$widget['x_axis'],
                    'y' => $widget['y_axis']=='' ? 0:$widget['y_axis'],
                    'width' => $widget['width']=='' ? 3:$widget['width'],
                    'height' => $widget['height']=='' ? 4:$widget['height']
                ];
            }
        }

        //Menu widgets
        /*$widget_menu = [];
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
        
        foreach ($d_widgets as $k => $v) {
            if($parenttab != $v['parenttab']){
                
                if($module != $v['module']){
                    $widget_menu[$v['parenttab']][$v['module']][] = ['widgetid'=>$v['widgetid'],'widgetname'=>$v['widgetname']];
                }else{
                    $widget_menu[$v['parenttab']][$v['module']][] = ['widgetid'=>$v['widgetid'],'widgetname'=>$v['widgetname']];
                }
                
                $parenttab = $v['parenttab'];
                $module = $v['module'];
            }else{

                if($module != $v['module']){
                    $widget_menu[$v['parenttab']][$v['module']][] = ['widgetid'=>$v['widgetid'],'widgetname'=>$v['widgetname']];
                }else{
                    $widget_menu[$v['parenttab']][$v['module']][] = ['widgetid'=>$v['widgetid'],'widgetname'=>$v['widgetname']];
                }

                $parenttab = $v['parenttab'];
                $module = $v['module'];
            }
        }
        //alert($widget_menu); exit;
        $data['widget_menu'] = $widget_menu;*/
        //Menu widgets
        //alert($widget_list); exit;
        //echo 555; exit;
        $data['widgets'] = $widget_list;
        $data['templates'] = $templates; //alert($data); exit();
        $this->template->build('index', $data);
    }

    public function addTab()
    {
        $post = $this->input->post();
        $sql = $this->db->get_where('aicrm_hometab_dashboard', ['tab_name'=>$post['dashboard_title']]);
        //alert($sql->num_rows()); exit;
        $data = [];
        $data['status'] = true;
        if($sql->num_rows() == 0){
            $this->db->insert('aicrm_hometab_dashboard', ['tab_name'=>$post['dashboard_title']]);
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
            $this->db->delete('aicrm_hometab_dashboard', ['id'=>$tab_id]);
            $this->db->delete('aicrm_homewidget_dashboard', ['tabid'=>$tab_id]);
        }
    }

    public function setTabActive()
    {
        $post = $this->input->post();
        $tab_id = $post['tab_id'];

        $this->db->update('aicrm_hometab_dashboard', ['status_active'=>0]);
        if($tab_id!='home'){
            $this->db->update('aicrm_hometab_dashboard', ['status_active'=>1], ['id'=>$tab_id]);
        }
    }

    public function addTabContent()
    {
        $post = $this->input->post();
        $data = [];
        $tab_id = $post['tab_id'];

        $this->db->where([
            'aicrm_report_dashboard.reporttype' => 'chart',
            'aicrm_report_dashboard.deleted' => 0
        ]);
        $sql = $this->db->get('aicrm_report_dashboard');
        $widget_list = $sql->result_array();

        $data['widgets'] = $widget_list;
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
                    $this->db->delete('aicrm_homereportchart_dashboard', ['reportid' => $row['id']]);
                    $this->db->insert('aicrm_homereportchart_dashboard', [
                        'reportid' => $row['id'],
                        'reportcharttype' => $row['type'],
                        'x_axis' => $row['x'],
                        'y_axis' => $row['y'],
                        'height' => $row['height'],
                        'width' => $row['width']
                    ]);
                }
            }else{
                foreach($post['data'] as $row){
                    $this->db->delete('aicrm_homewidget_dashboard', ['tabid'=>$row['tabid'], 'reportid' => $row['id']]);
                    $this->db->insert('aicrm_homewidget_dashboard', [
                        'tabid' => $row['tabid'],
                        'reportid' => $row['id'],
                        'reportcharttype' => $row['type'],
                        'x_axis' => $row['x'],
                        'y_axis' => $row['y'],
                        'height' => $row['height'],
                        'width' => $row['width']
                    ]);
                }
            }
        }
    }

    public function removeWidget()
    {
        $post = $this->input->post();
        $tab_id = $post['tab_key'];
        $id = $post['id'];

        if($tab_id=='home'){
            $this->db->delete('aicrm_homereportchart_dashboard',['reportid'=>$id]);
        }else{
            $this->db->delete('aicrm_homewidget_dashboard', ['tabid'=>$tab_id, 'reportid'=>$id]);
        }
    }

}