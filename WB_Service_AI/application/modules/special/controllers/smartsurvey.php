<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Smartsurvey extends MY_Controller
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

    public function saveSurveyAnswer(){
        $this->load->library('crmentity');
        $request_body = file_get_contents('php://input');
        $post     = json_decode($request_body,true);
        $sql = $this->db->query("SELECT * FROM aicrm_smartsurvey WHERE smartsurveyid=".$post['smartsurveyid']);
        $rs = $sql->row_array();
        $smartsurvey_name = $rs['smartsurvey_name'];

        $sql = $this->db->query("SELECT aicrm_account.accountid, aicrm_account.accountname FROM aicrm_account 
        INNER JOIN aicrm_accountscf ON aicrm_accountscf.accountid = aicrm_account.accountid
        WHERE aicrm_account.accountid =".$post['accountid']);
        $rs = $sql->row_array();
        $accountName = $rs['accountname'];
        
        $insertData = [[
            'smartsurveyanswer_no' => '',
            'smartsurveyanswer_name' => $smartsurvey_name.' '.$accountName,
            'accountid' => $post['accountid'],
            'smartsurveyid' => $post['smartsurveyid'],
            'smcreatorid' => 1,
            'smownerid' => 19330,
            'modifiedby' => 1
        ]];

        $tabName = ['aicrm_crmentity', 'aicrm_smartsurveyanswer', 'aicrm_smartsurveyanswercf'];
        $tabIndex = ['aicrm_crmentity' => 'crmid', 'aicrm_smartsurveyanswer' => 'smartsurveyanswerid', 'aicrm_smartsurveyanswercf' => 'smartsurveyanswerid'];
        list($chk, $crmid, $DocNo) = $this->crmentity->Insert_Update('Smartsurveyanswer', '', 'add', $tabName, $tabIndex, $insertData);
        
        $return = ['status'=>'error', 'crmid'=>''];
        if($crmid != ''){
            $return = ['status'=>'success', 'crmid'=>$crmid];
        }
        echo json_encode($crmid);
    }

    public function saveSurveyAnswerGet($SurveySmartemailID, $accountID){
        $this->load->library('crmentity');

        if($SurveySmartemailID=='' || $accountID=='') return 'Error';

        $sql = $this->db->query("SELECT * FROM aicrm_surveysmartemail WHERE surveysmartemailid=".$SurveySmartemailID);
        $rs = $sql->row_array();
        $surveysmartemail_name = $rs['surveysmartemail_name'];
        $survey_point = $rs['survey_point'];

        $sql = $this->db->query("SELECT aicrm_account.accountid, aicrm_account.accountname FROM aicrm_account 
        INNER JOIN aicrm_accountscf ON aicrm_accountscf.accountid = aicrm_account.accountid
        WHERE aicrm_account.accountid =".$accountID);
        $rs = $sql->row_array();
        $accountName = $rs['accountname'];
        //$accountRefNo = $rs['accountrefno'];

        // Insert Point
        /*$insertPointData = [[
            'point_name' => $surveysmartemail_name,
            'cf_1409' => 'Campaign',
            'cf_1404' => 'Add',
            'cf_1408' => $survey_point,
            'cf_1414' => $survey_point,
            'cf_1488' => $survey_point,
            'cf_1411' => date('Y-m-d'),
            'parent_id' => $accountID,
            'cf_1941' => $accountName,
            'cf_1412' => $accountRefNo, // cf_1580
            'smcreatorid' => '1',
            'smownerid' => '1',
            'modifiedby' => '1'
        ]];
        $tabName = ['aicrm_crmentity', 'aicrm_point', 'aicrm_pointcf'];
        $tabIndex = ['aicrm_crmentity' => 'crmid', 'aicrm_point' => 'pointid', 'aicrm_pointcf' => 'pointid'];
        list($chk, $pointID, $DocNo) = $this->crmentity->Insert_Update('Point', '', 'add', $tabName, $tabIndex, $insertPointData);

        global $site_URL;
        $url = $site_URL.'WB_Service_AI/point/addjust';
        $params = [
            'action' => 'add',
            'brand' => '',
            'channel' => 'Survey',
            'point' => $survey_point,
            'accountid' => $accountID,
            'type' => '',
            'pointid' => $pointID,
        ];
        $rsAPI = $this->postApi($url, $params);*/

        $insertData = [[
            'smartsurveyanswer_name' => $surveysmartemail_name.' '.$accountName,
            'accountid' => $accountID,
            'surveysmartemailid' => $SurveySmartemailID,
            'smcreatorid' => 1,
            'smownerid' => 19330,
            'modifiedby' => 1
        ]];

        $tabName = ['aicrm_crmentity', 'aicrm_smartsurveyanswer', 'aicrm_smartsurveyanswercf'];
        $tabIndex = ['aicrm_crmentity' => 'crmid', 'aicrm_smartsurveyanswer' => 'smartsurveyanswerid', 'aicrm_smartsurveyanswercf' => 'smartsurveyanswerid'];
        list($chk, $crmid, $DocNo) = $this->crmentity->Insert_Update('SmartSurveyAnswer', '', 'add', $tabName, $tabIndex, $insertData);

        $return = ['status'=>'error', 'crmid'=>'', 'docno'=>''];
        if($crmid != ''){
            $return = ['status'=>'success', 'crmid'=>$crmid, 'docno'=>$DocNo];
        }
        
        return $return;
    }

    public function postApi( $url, $param=[] ){
		$param['AI-API-KEY'] = '1234';
		$fields_string = json_encode($param);
		$json_url = $url;
	
		$json_string = $fields_string;
		$ch = curl_init( $json_url );
		$options = array(
			CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
            CURLOPT_POSTFIELDS => $json_string,
            CURLOPT_BUFFERSIZE => 1024,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_DNS_USE_GLOBAL_CACHE => false,
            CURLOPT_DNS_CACHE_TIMEOUT => 2
		);
	
		curl_setopt_array( $ch, $options );
		$result =  curl_exec($ch);
		$return = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $result), true );
		return $return;
	}
}