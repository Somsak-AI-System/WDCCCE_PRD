<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');
class Omnireport extends MY_Controller
{

  private $description, $title, $keyword, $screen,$modulename;
  public function __construct()
  {
    parent::__construct();
    $meta = $this->config->item('meta');
    $lang = $this->config->item('lang');
    $this->title = $meta["default"]["title"];
    $this->keyword = $meta["default"]["keyword"];
    $this->description = $meta["default"]["description"];
    $this->load->library('curl');
    $this->lang->load('ai',$lang);
    $this->load->config('api');
    $this->url_service= $this->config->item('service');
	  $this->load->model('omnireport_model');
  }

  public function index()
  {
    $this->template->title("Report" ,$this->title);  // 11 words or 70 characters
    $this->template->screen('Report', $this->screen);
    $this->template->modulename('report', $this->modulename);
    $this->template->set_metadata('description', mb_substr(strip_tags($this->description), 0, 350)); // 70 words (350 characters)
    $this->template->set_metadata('keywords', $this->keyword);
    $this->template->set_layout('report');
    $data = array();
    $this->template->build('index', $data, FALSE, TRUE);
  }

  public function getmessage(){

    $startdate = $this->input->post('s_date');
    $enddate = $this->input->post('e_date');
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("omnireport_model");
    $data = $this->omnireport_model->get_message($startdate,$enddate);

    //alert($data); exit;

    $result_data['data']  = $data;    
    $result_data["total"] = count($data);

    echo json_encode($result_data);

  }

  public function  export(){
     $startdate = $this->input->post('s_date');
     $enddate = $this->input->post('e_date');
     $filetype = $this->input->post('filetype');
     $this->load->library('db_api');
     $this->load->database();
     $this->load->model("omnireport_model");
    
     $a_data =array(); 
     
     $submodule="history";
     $a_param = "startdate=".$startdate."&enddate=".$enddate;

     if($filetype == 'excel'){
      $a_data = $this->genxls($submodule,$a_param,$filetype);
     
     }else if($filetype == 'pdf'){
      $a_data = $this->genpdf($submodule,$a_param,$filetype);

     }
     $result_data['data']  = $a_data; 
     echo json_encode($result_data);
     //$data = array();
     //$this->template->build('export', $result_data, FALSE, TRUE);
  }

  public function genpdf($submodule="history",$a_param=array(),$filetype=NULL)
  {
    global $report_viewer_url_service, $root_directory;
    $config_export = $this->config->item('export');

    $birt_link = $config_export[$submodule]["birt_link"];
    $prefix = $config_export[$submodule]["prefix"];
    $path = $config_export[$submodule]['path'];

    //$param = rawurldecode(http_build_query($a_param));
    $param = $a_param;

    $url_file=$report_viewer_url_service.$birt_link."&".$param;
    //echo $url_file; exit;
    $filename = $prefix."_".USERID."_".date('Y-m-d_his').".pdf";
    $pathfile = $root_directory.$path.$filename;
    
    $ch = curl_init($url_file);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $data = curl_exec($ch);
    
    curl_close($ch);
    
    file_put_contents($pathfile, $data);
    
    //export excel
    $a_return["path"] = $path;
    $a_return["filename"] = $filename;
    $a_return["url_file"] = $url_file;
    
    return $a_return;
  }

  public function genxls($submodule="history",$a_param=array(),$filetype=NULL)
  {
    global $report_viewer_url_service, $root_directory;
    $config_export = $this->config->item('export');
    
    //export excel 
    $prefix = $config_export[$submodule]["prefix"];
    $path = $config_export[$submodule]['path'];
    $birt_link_excel = $config_export[$submodule]["birt_link_excel"];
    
    $param = $a_param;

    $url_file_excel=$report_viewer_url_service.$birt_link_excel."&".$param;

    $filename_excel = $prefix."_".USERID."_".date('Y-m-d_his').".xls";
    $pathfile_excel = $root_directory.$path.$filename_excel;  
    
    $ch = curl_init($url_file_excel);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  
    $data = curl_exec($ch);   
    curl_close($ch);
    
    file_put_contents($pathfile_excel, $data);
    //export excel    
    $a_return["path"] = $path;
    $a_return["filename"] = $filename_excel;
    $a_return["url_file"] = $url_file_excel;
    return $a_return;
  }

  /*public function  export(){
     $startdate = $this->input->post('s_date');
     $enddate = $this->input->post('e_date');
     $filetype = $this->input->post('filetype');
     $this->load->library('db_api');
     $this->load->database();
     $this->load->model("omnireport_model");
     $data = $this->omnireport_model->get_message($startdate,$enddate);
     //alert($data); exit;
     $result_data['filetype'] = $filetype;
     $result_data['data']  = $data;  

     //$data = array();
     $this->template->build('export', $result_data, FALSE, TRUE);
  }*/

  /*public function getunitmatrix(){
    
    $branchid = $this->input->post('branchid');
    $buildingid = $this->input->post('buildingid');
    $floorno = $this->input->post('floorno');
    $inspecttype = $this->input->post('inspecttype');
    $floorno =  str_replace(",","','",$floorno);
    //echo $floorno; exit;
    $this->load->library('db_api');
    $this->load->database();
    $this->load->model("home_model");
    $data = $this->home_model->get_unitmatrix($branchid,$buildingid,$floorno,$inspecttype);
    //alert($data); exit;
    $tab ='';
    $floorno =''; 
    $mirror =''; 
    $loopfl ="Y";
    $i=0;

    // getFloor
    $floorlist=array();
    $floor='';
    $totalunit=0;
    $totalNone=0;
    $totalOpen=0;
    $totalClosed=0;
    $totalDealy=0;
    $totalProcessing = 0;
    $i = 0;
    foreach($data as $flr){
      
      if($flr['unit_status']=="None")
      {
        $totalNone=$totalNone+1;
      }
      else if($flr['unit_status']=="Open")
      {
        $totalOpen = $totalOpen+1;
      }
      else if($flr['unit_status']=="Processing")
      {
        $totalProcessing = $totalProcessing+1;
      }
      else if($flr['unit_status']=="Closed")
      {
        $totalClosed = $totalClosed+1;
      }
      
      if($flr['unit_status']=="Open"){

        if($data[$i]['inspection_date'] < date('Y-m-d')){
          $data[$i]['dealy'] = 'Dealy';
          $totalDealy = $totalDealy+1;
        }else{
          $data[$i]['dealy'] = '';
        }
      }else{
        $data[$i]['dealy'] = '';
      }
      
      if($floor !=$flr['floor_no']){
        $floorlist[]['floor'] =  $flr['floor_no'];
        $floor=$flr['floor_no'];
      }

      $data[$i]['inspecttype'] = $inspecttype;
      $i++;
    }
    
    $result_data['rows']  = $data;    
    $result_data["total"] = count($data);
    $result_data['floorlist'] = $floorlist;
    $result_data['totalNone']=$totalNone; 
    $result_data['totalOpen']=$totalOpen;
    $result_data['totalClosed']=$totalClosed;
    $result_data['totalDealy']=$totalDealy;
    $result_data['totalProcessing']=$totalProcessing;
     
    echo json_encode($result_data);
  }*/








  
}
