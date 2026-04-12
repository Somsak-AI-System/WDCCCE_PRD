<?php
defined('BASEPATH') or exit('No direct script access allowed');
// include(APPPATH . 'libraries/xlsxwriter.class.php');

class Integrate extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library("common");

    }

    public function saveSaleOrder()
    {
        $request_body = file_get_contents('php://input');
        $a_param = json_decode($request_body, true);
		
		$post = $this->input->post();
		if($this->input->post()){
			$a_param = $this->input->post();
		}

        // $data_parram = "Json :: ".print_r(json_encode($a_param), true);
        // $file_name="log-Integrate_Salesorder-".date('Y-m-d').".php";
        // $FileName = "log/info/API/".$file_name;
        // $FileHandle = fopen($FileName, 'a+') or die("can't open file");
        // fwrite($FileHandle, date('Y-m-d H:i:s')." => ".print_r($data_parram, true)."\r\n");
        // fclose($FileHandle);

        $returnResult = ['status'=>'Success'];

        echo json_encode($returnResult);
    }
}

