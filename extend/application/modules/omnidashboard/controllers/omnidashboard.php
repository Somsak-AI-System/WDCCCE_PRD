<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Omnidashboard extends MY_Controller
{
    private $description, $title, $keyword, $screen, $modulename;
    public function __construct()
    {
        parent::__construct();
        $meta = $this->config->item('meta');
        $lang = $this->config->item('lang');
        $this->title = $meta["default"]["title"];
        $this->keyword = $meta["default"]["keyword"];
        $this->description = $meta["default"]["description"];
        $this->load->library('curl');
        $this->lang->load('ai', $lang);
        $this->load->config('api');
        $this->url_service = $this->config->item('service');
        $this->load->model('Omnidashboard_model');
        $this->load->database();
    }

    public function index()
    {
        $this->template->title("Dashboard", $this->title); // 11 words or 70 characters
        $this->template->screen('Dashboard', $this->screen);
        $this->template->modulename('dashboard', $this->modulename);
        $this->template->set_metadata('description', mb_substr(strip_tags($this->description) , 0, 350)); // 70 words (350 characters)
        $this->template->set_metadata('keywords', $this->keyword);
        $this->template->set_layout('report');
        $data = array();
        $this->template->build('index', $data, false, true);
    }

    public function getleadday()
    {   
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');

        $data = array();
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("omnidashboard_model");
        $result = $this->omnidashboard_model->get_leadday($startdate,$enddate);
        $data_chart = array();
        $categories = array();

        foreach ($result as $key => $value)
        {
            array_push($data_chart, $value['record']);
            array_push($categories, date('d-m-Y', strtotime($value['day'])));
        }
        $data['data'] = $data_chart;
        $data['name']['data'] = $data_chart;
        $data['categories'] = $categories;
        echo json_encode($data);
    }

    public function getleadweek()
    {
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');

        $data = array();
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("omnidashboard_model");
        $result = $this->omnidashboard_model->get_leadweek($startdate,$enddate);

        $data_chart = [];
        $categories = array();

        foreach ($result as $key => $value)
        {
            $data_chart[0]['data'][] = $value['record'];
            $week = date('d-m-Y', strtotime($value['startweek'])) . ' - ' . date('d-m-Y', strtotime($value['endweek']));
            array_push($categories, $week);
        }
        $data_chart[0]['name'] = 'Count';
        $data['data'] = $data_chart;
        $data['categories'] = $categories;
        //alert($data); exit;
        echo json_encode($data);
    }

    public function getleadmonth()
    {
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $data = array();
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("omnidashboard_model");
        $result = $this->omnidashboard_model->get_leadmonth($startdate,$enddate);

        //alert($result); exit;
        $data_chart = array();
        $data_temp = array();
        $categories = array();
        //$categories = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $categories = ['ม.ค', 'ก.พ', 'มี.ค', 'เม.ย', 'พ.ค', 'มิ.ย', 'ก.ค', 'ส.ค', 'ก.ย', 'ต.ค', 'พ.ย', 'ธ.ค'];
        $i = 1;
        foreach ($result as $key => $value)
        {
            $data_tmp[$value['month']] = $value['record'];
        }
        //alert($data_tmp); exit;
        for ($i = 1;$i <= 12;$i++)
        {
            if (!empty($data_tmp[$i]))
            {
                $data_chart[] = $data_tmp[$i];
            }
            else
            {
                $data_chart[] = 0;
            }
        }
        //
        $data['data'] = $data_chart;
        $data['categories'] = $categories;
        //alert($data); exit;
        echo json_encode($data);
    }

    public function getleadstatusmonth()
    {

        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');

        $data = array();
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("omnidashboard_model");
        $result = $this->omnidashboard_model->get_leadstatusmonth($startdate,$enddate);
        //alert($result ); exit;
        $data_chart = array();
        $data_temp = array();
        $data_create = array();
        $data_convert = array();
        $categories = array();
        $cat = [1 => 'ม.ค', 2 => 'ก.พ', 3 => 'มี.ค', 4 => 'เม.ย', 5 => 'พ.ค', 6 => 'มิ.ย', 7 => 'ก.ค', 8 => 'ส.ค', 9 => 'ก.ย', 10 => 'ต.ค', 11 => 'พ.ย', 12 => 'ธ.ค'];

        $i = 1;
        foreach ($result as $key => $value)
        {
            //$data_tmp[$value['month']] = $value['record'];
            $data_create[] = $value['record_create'];
            $data_convert[] = $value['record_convert'];
            $categories[] = $cat[$value['month']] . ' ' . $value['year'];
        }
        array_push($data_chart, array(
            'name' => 'เปิด',
            'data' => $data_create
        ));
        array_push($data_chart, array(
            'name' => 'แปลงเป็นลูกค้า',
            'data' => $data_convert
        ));

        $data['data'] = $data_chart;
        $data['categories'] = $categories;
        //alert($data); exit;
        echo json_encode($data);
    }

    public function getleadsourcequater()
    {
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');

        $data = array();
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("omnidashboard_model");
        $result = $this->omnidashboard_model->get_leadsourcequater($startdate,$enddate);

        $data_chart = array();
        $data_temp = array();
        $categories = array();

        $leadsource = array();
        $a_leadsource = $this->omnidashboard_model->get_leadsourcepicklist(); //alert($a_leadsource); exit;
        foreach ($a_leadsource as $key => $value)
        {
            array_push($leadsource, $value['leadsource']);
        }
        array_push($leadsource, '');
        //$month = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
        $catego = [1 => 'ไตรมาสที่ 1', 2 => 'ไตรมาสที่ 2', 3 => 'ไตรมาสที่ 3', 4 => 'ไตรมาสที่ 4'];

        $categories = ['ไตรมาสที่ 1', 'ไตรมาสที่ 2', 'ไตรมาสที่ 3', 'ไตรมาสที่ 4'];
        $data_temp = array();
        $set_temp = array();
        $temp = array();
        $m = '';
        $y = '';

        for ($i = 1;$i <= 4;$i++)
        {
            $set_temp[$i] = '';
        }

        foreach ($set_temp as $k_temp => $v_temp)
        {
            foreach ($leadsource as $k_cat)
            {
                $temp[$k_temp][$k_cat] = ['record' => 0, 'leadsource' => $k_cat, 'quarter' => $k_temp, 'name' => $catego[$k_temp] . " " . $k_temp];
            }
        }

        foreach ($result as $key => $value)
        {
            $value['name'] = $catego[$value['quarter']] . " " . $value['year'];
            $data_temp[$value['quarter']][] = $value;
        }

        foreach ($data_temp as $k_data => $v_data)
        {
            foreach ($v_data as $kk => $vv)
            {
                $temp[$vv['quarter']][$vv['leadsource']]['record'] = $vv['record'];
            }
        }

        $data_t = array();
        foreach ($temp as $key => $value)
        {
            foreach ($value as $k => $v)
            {
                $data_t[] = $v;
            }
        }

        $source = '';
        $i = 0;
        foreach ($leadsource as $k_lead)
        {
            foreach ($data_t as $key => $value)
            {
                if ($k_lead == $value['leadsource'])
                {

                    if ($value['leadsource'] == '')
                    {
                        $data_chart[$i]['name'] = 'ไม่ระบุ';
                    }
                    else
                    {
                        $data_chart[$i]['name'] = $value['leadsource'];
                    }

                    $data_chart[$i]['data'][] = $value['record'];
                }
            }

            $i++;
        }

        $data['data'] = $data_chart;
        $data['categories'] = $categories;
        //alert($data);exit;
        echo json_encode($data);
    }

    public function getleadsourcemonth()
    {
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');

        $data = array();
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("omnidashboard_model");
        $result = $this->omnidashboard_model->get_leadsourcemonth($startdate,$enddate);
        //alert($result); exit;
        $leadsource = array();
        $a_leadsource = $this->omnidashboard_model->get_leadsourcepicklist(); //alert($a_leadsource); exit;
        
        foreach ($a_leadsource as $key => $value)
        {
            array_push($leadsource, $value['leadsource']);
        }
        array_push($leadsource, '');

        //$month = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];
        $month = [1 => 'ม.ค', 2 => 'ก.พ', 3 => 'มี.ค', 4 => 'เม.ย', 5 => 'พ.ค', 6 => 'มิ.ย', 7 => 'ก.ค', 8 => 'ส.ค', 9 => 'ก.ย', 10 => 'ต.ค', 11 => 'พ.ย', 12 => 'ธ.ค'];
        $data_temp = array();
        $set_temp = array();
        $categories = array();
        $data_chart = array();
        $temp = array();
        $m = '';
        $y = '';

        if(!empty($result)){    
            foreach ($result as $k_result => $v_result)
            {
                $set_temp[$v_result['year']][$v_result['month']] = '';
            }

            foreach ($set_temp as $k_temp => $v_temp)
            {

                foreach ($v_temp as $k_month => $v_month)
                {

                    foreach ($leadsource as $k_cat)
                    {
                        $temp[$k_temp][$k_month][$k_cat] = ['record' => 0, 'leadsource' => $k_cat, 'month' => $k_month, 'year' => $k_temp, 'name' => $month[$k_month] . " " . $k_temp];
                    }

                    if ($k_temp != $y)
                    {
                        $categories[] = $month[$k_month] . " " . $k_temp;
                    }
                    else if ($k_month != $m)
                    {
                        $categories[] = $month[$k_month] . " " . $k_temp;
                    }
                    $m = $k_month;
                    $y = $k_temp;

                }
            }

            foreach ($result as $key => $value)
            {
                $value['name'] = $month[$value['month']] . " " . $value['year'];
                $data_temp[$value['year']][$value['month']][] = $value;
            }
            //alert($data_temp); exit;
            foreach ($data_temp as $k_data => $v_data)
            {
                foreach ($v_data as $kk => $vv)
                {
                    foreach ($vv as $kkk => $vvv)
                    {
                        $temp[$vvv['year']][$vvv['month']][$vvv['leadsource']]['record'] = $vvv['record'];
                    }
                }
            }
            //alert($temp); exit;
            $data_t = array();
            foreach ($temp as $key => $value)
            {
                foreach ($value as $k => $v)
                {
                    foreach ($v as $kv => $vv)
                    {
                        $data_t[] = $vv;
                    }
                }

            }
            //alert($data_t); exit;
            $source = '';
            $i = 0;
            foreach ($leadsource as $k_lead)
            {

                foreach ($data_t as $key => $value)
                {
                    if ($k_lead == $value['leadsource'])
                    {
                        if ($value['leadsource'] == '')
                        {
                            $data_chart[$i]['name'] = 'ไม่ระบุ';
                        }
                        else
                        {
                            $data_chart[$i]['name'] = $value['leadsource'];
                        }

                        $data_chart[$i]['data'][] = $value['record'];
                    }
                }

                $i++;
            }
        }
        //alert($data_chart); exit;
        $data['data'] = $data_chart;
        $data['categories'] = $categories;
        //alert($data); exit;
        echo json_encode($data);
    }

    public function get_datacustomer()
    {
        $data = array();
        $this->load->library('db_api');
        $this->load->database();
        $this->load->model("omnidashboard_model");
        $result = $this->omnidashboard_model->get_datacustomer();

        $line = 0;
        $facebook = 0;
        foreach ($result['customer'] as $key => $value) {
            if($value['channel'] == 'facebook'){
                $facebook = $facebook+1;
            }else if($value['channel'] == 'line'){
                $line = $line+1;
            }
        }

        $allchat = $result['chat']['record'];
        $chatline = $result['chat_line']['record'];
        $chatfacebook = $result['chat_facebook']['record'];

        $persenline = number_format((($chatline/$allchat)*100),0);
        $persenfacebook = number_format((($chatfacebook/$allchat)*100),0);

        $data['data'] = count($result['customer']);
        $data['line'] = $line;
        $data['facebook'] = $facebook;
        $data['chat'] = $allchat;
        $data['persenline'] = $persenline;
        $data['persenfacebook'] = $persenfacebook;
        //alert($data);exit;
        echo json_encode($data);

    }

}

