<?php
defined('BASEPATH') or exit('No direct script access allowed');
// require_once('src/OAuth2/Autoloader.php');
require APPPATH . '/libraries/REST_Controller.php';
/**
 *  # Class Smartquestionnaire
 *   ## Webservice Module Smartquestionnaire พัสดุ
 */
class Questionnaire extends REST_Controller
{
    private $crmid;
    private $tab_name = array('aicrm_crmentity','aicrm_questionnaire','aicrm_questionnairecf');
    private $tab_name_index = array('aicrm_crmentity'=>'crmid','aicrm_questionnaire'=>'questionnaireid','aicrm_questionnairecf'=>'questionnaireid');

    private $tab_name_answer = array('aicrm_crmentity','aicrm_questionnaireanswer','aicrm_questionnaireanswercf');
    private $tab_name_index_answer = array('aicrm_crmentity'=>'crmid','aicrm_questionnaireanswer'=>'questionnaireanswerid','aicrm_questionnaireanswercf'=>'questionnaireanswerid');

    private $tab_name_template = array('aicrm_crmentity','aicrm_questionnairetemplate','aicrm_questionnairetemplatecf');
    private $tab_name_index_template = array('aicrm_crmentity'=>'crmid','aicrm_questionnairetemplate'=>'questionnairetemplateid','aicrm_questionnairetemplatecf'=>'questionnairetemplateid');
    function __construct()
    {
        parent::__construct();
        $this->load->library('memcached_library');
        $this->load->library('crmentity');
        $this->load->database();
        $this->load->library("common");
        $this->load->model("questionnaire_model");
        $this->_limit = 0;
        $this->_module = "Questionnaire";
        $this->_format = "array";
        $this->_return = array(
            'Type' => "S",
            'Message' => "Insert Complete",
            'cache_time' => date("Y-m-d H:i:s"),
            'total' => "1",
            'offset' => "0",
            'limit' => "1",
            'data' => array(
                'Crmid' => null,
                'QuestionnaireNo' => null
            ),
        );

        // $dsn  = 'mysql:dbname=' . $this->config->item('oauth_db_database') . ';host=' . $this->config->item('oauth_db_host');
        // $dbusername = $this->config->item('oauth_db_username');
        // $dbpassword = $this->config->item('oauth_db_password');
        // OAuth2\Autoloader::register();

        // // $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
        // $storage = new OAuth2\Storage\Pdo(array(
        // 	'dsn' => $dsn,
        // 	'username' => $dbusername,
        // 	'password' => $dbpassword
        // ));
        // // Pass a storage object or array of storage objects to the OAuth2 server class
        // $this->oauth_server = new OAuth2\Server($storage);
        // // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        // $this->oauth_server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
        // // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        // $this->oauth_server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

    }

    public function insert_content_post(){

        //header('Content-Type:application/json; charset=UTF-8');
        $request_body = file_get_contents('php://input');
        $dataJson     = json_decode($request_body,true);

        $response_data = null;
        $a_request =$dataJson;
        //print_r($a_request);exit();

        $response_data = $this->get_insert_data($a_request);

        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->common->_filename= "Insert_Smartquestionnaire";
        $this->common->set_log($url,$a_request,$response_data);
        if ( $response_data ) {
            $this->response($response_data, 200); // 200 being the HTTP response code
        } else {
            $this->response(array(
                'error' => 'Couldn\'t find Set Content!'
            ), 404);
        }
    }
    public function set_notification($a_data = array(),$crmid="")
    {
        if(empty($a_data)) return "";
        if($crmid=="") return "";
        $a_params["crmid"] = $crmid;
        $a_params["method"] = "smartquestionnaire";
        $this->load->library('lib_notification');
        $a_data = $this->lib_notification->get_value($a_params);
        $this->common->_filename= "Insert_Smartquestionnaire";

    }
    private function get_insert_data($a_request){

        $response_data = null;
        $module=isset($a_request['module']) ? $a_request['module'] : "";
        $crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
        $DocNo=isset($a_request['smartquestionnaire_no']) ? $a_request['smartquestionnaire_no'] : "";
        $action=isset($a_request['action']) ? $a_request['action'] : "";
        $data=isset($a_request['data']) ? $a_request['data'] : "";

        if(count($data[0])>0 and $module=="Smartquestionnaire"){

            list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module,$crmid,$action,$this->tab_name,$this->tab_name_index,$data);


            if($chk=="0"){
                $this->set_notification($data,$crmid);
                $a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
                $a_return["data"] =array(
                    'Crmid' => $crmid,
                    'SmartquestionnaireNo' => $DocNo,

                );
            }else{
                $a_return  =  array(
                    'Type' => 'E',
                    'Message' => 'Unable to complete transaction',
                );
            }
        }else{//echo "ddd";
            $a_return  =  array(
                'Type' => 'E',
                'Message' =>  'Invalid Request!',
            );
        }
        return array_merge($this->_return,$a_return);
    }

    public function delete_item_post(){

        $request_body = file_get_contents('php://input');
        $dataJson     = json_decode($request_body,true);

        $a_request =$dataJson;

        $a_return["Message"] =  "Update Complete";

        $a_return["data"] = array();
        $data = array();

        foreach ($a_request["crmid"] as $crmid) {
            $data = array(
                'crmid' => $crmid,
                'modifiedby' => $a_request["modifiedby"],
                'modifiedtime' => date("Y-m-d H:i:s"),
                'deleted' => "1"
            );

            array_push($a_return["data"] , $data);
        }

        $update_status = $this->db->update_batch('aicrm_crmentity', $a_return["data"] , 'crmid');


        $response_data = array_merge($this->_return,$a_return);

        if ( $response_data ) {
            $this->response($response_data, 200); // 200 being the HTTP response code
        } else {
            $this->response(array(
                'error' => 'Couldn\'t find Set Content!'
            ), 404);
        }
    }

    private function set_param($a_param=array())
    {
        $a_condition = array();
        /*if (isset($a_param["crmid"]) && $a_param["crmid"]!="") {
            $a_condition["aicrm_smartquestionnaire.smartquestionnaireid"] =  $a_param["crmid"] ;
        }

        if (isset($a_param["contactid"]) && $a_param["contactid"]!="") {
            $a_condition["aicrm_smartquestionnaire_answer.relcrmid"] =  $a_param["contactid"] ;
        }

        if (isset($a_param["servicerequestid"]) && $a_param["servicerequestid"]!="") {
            $a_condition["aicrm_smartquestionnaire_answer.servicerequestid"] =  $a_param["servicerequestid"] ;
        }*/

        return $a_condition;
    }
    private function set_order($a_orderby=array())
    {
        if(empty($a_orderby)) return false;

        $a_order = array();
        $a_condition = explode( "|",$a_orderby);

        for ($i =0;$i<count($a_condition) ;$i++)
        {
            list($field,$order) = explode(",", $a_condition[$i]);
            $a_order[$i]["field"] = $field;
            $a_order[$i]["order"] = $order;
        }

        return $a_order;
    }
    private function get_cache($a_params=array())
    {
        $this->load->library('managecached_library');

        $a_cache = array();
        $a_cache["_ctag"] =  $this->_module.'/';
        $a_cache["_ckname"] =$this->_module.'/get_content';

        $a_condition = array();
        $a_condition = $this->set_param($a_params);



        $limit = @$a_params["limit"];
        $offset = @$a_params["offset"];
        $order= @$a_params["orderby"];
        $a_order = $this->set_order($order);


        $a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
        $a_limit["offset"] = ($offset == "") ? 0 :$offset;

        $a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
        $a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
        $a_build["limit"] = http_build_query($a_limit);

        $a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));

        $a_data = $this->managecached_library->get_memcache($a_cache);


        if($a_data===false)
        {

            $a_list=$this->smartquestionnaire_model->get_list($a_condition,$a_order,$a_limit);


            $a_data = $this->get_data($a_list);
            //alert($a_data);exit();
            $a_data["data"] = $a_data["result"];
            $a_data["limit"] = $a_limit["limit"]  ;
            $a_data["offset"] = $a_limit["offset"]  ;
            $a_data["time"] = date("Y-m-d H:i:s");
            $a_cache["data"] = $a_data["result"];
            $a_cache["data"]["time"] = date("Y-m-d H:i:s");
            $this->managecached_library->set_memcache($a_cache,"2400");
        }

        return $a_data;
    }

    private function get_data($a_data)
    {

        if(!empty($a_data["result"]["data"]) && $a_data["status"] ){
            foreach ($a_data["result"]["data"] as $key =>$val){
                $smartquestionnaireid = $val["smartquestionnaireid"];
                $a_return = $val;

                $a_response[] = $a_return;
            }
            $a_data["result"]["data"] = $a_response;
        }

        return $a_data;
    }

    public function list_content_get()
    {
        $a_param =  $this->input->get();
        $a_data =$this->get_cache($a_param);
        $this->return_data($a_data);
    }

    public function list_content_post()
    {
        $request_body = file_get_contents('php://input');
        $a_param     = json_decode($request_body,true);
        //$a_param =  $this->input->post();
        $a_data =$this->get_cache($a_param);
        $this->return_data($a_data);
    }

    public function return_data($a_data)
    {
        if($a_data)
        {
            $format =  $this->input->get("format",true);
            $a_return["Type"] = ($a_data["status"])?"S":"E";
            $a_return["Message"] = $a_data["error"];
            $a_return["total"] = isset($a_data["data"]["total"]) ? $a_data["data"]["total"] : 0;
            $a_return["offset"] = isset($a_data["offset"])?$a_data["offset"]:0;
            $a_return["limit"] = isset($a_data["limit"])?$a_data["limit"]:0;
            $a_return["cachetime"] = $a_data["time"];
            $a_return["data"] = !empty($a_data["data"]["data"]) ? $a_data["data"]["data"] : "" ;
            //alert($a_return["data"]);
            if ($format!="json" && $format!="xml"  ) {
                $this->response($a_return, 200); // 200 being the HTTP response code
            }else{
                $this->response($a_return, 200); // 200 being the HTTP response code
            }
        }
        else
        {
            $this->response(array('error' => 'Couldn\'t find any Smartquestionnaire!'), 404);
        }
    }

    //Template Questionnaire Get
    public function questionnaire_template_get()
    {
        $a_param =  $this->input->get();
        $a_data =$this->get_cache_template($a_param);
        $this->return_data($a_data);
    }
    //Template Questionnaire Post
    public function questionnaire_template_post()
    {
        $request_body = file_get_contents('php://input');
        $a_param     = json_decode($request_body,true);

        $a_data =$this->get_cache_template($a_param);

        $this->return_data($a_data);
    }

    private function get_cache_template($a_params=array())
    {
        if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
            $a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;
            $this->return_data_token($a_data);
        }

        $this->load->library('managecached_library');

        $a_cache = array();
        $a_cache["_ctag"] =  $this->_module.'/';
        $a_cache["_ckname"] =$this->_module.'/get_content';

        $a_condition = array();
        $a_condition = $this->set_param($a_params);

        $limit = @$a_params["limit"];
        $offset = @$a_params["offset"];
        $order= @$a_params["orderby"];
        $a_order = $this->set_order($order);

        $a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
        $a_limit["offset"] = ($offset == "") ? 0 :$offset;

        $a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
        $a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
        $a_build["limit"] = http_build_query($a_limit);

        $a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));

        $a_data = $this->managecached_library->get_memcache($a_cache);

        if($a_data===false)
        {
            $a_list = $this->questionnaire_model->get_list_template($a_condition,$a_order,$a_limit);

            $a_data = $this->get_data_template($a_list);
            //alert($a_data);exit();
            $a_data["data"] = $a_data["result"];
            $a_data["limit"] = $a_limit["limit"]  ;
            $a_data["offset"] = $a_limit["offset"]  ;
            $a_data["time"] = date("Y-m-d H:i:s");
            $a_cache["data"] = $a_data["result"];
            $a_cache["data"]["time"] = date("Y-m-d H:i:s");
            $this->managecached_library->set_memcache($a_cache,"2400");
        }

        return $a_data;
    }

    private function get_data_template($a_data)
    {

        //echo "<pre>"; print_r($a_data["result"]["data"]); echo "</pre>"; exit;
        if(!empty($a_data["result"]["data"]) && $a_data["status"] ){
            $a_data_temp = $a_data["result"]["data"];
            $data_template = array();

            $data_template['questionnairetemplateid'] = $a_data_temp[0]['questionnairetemplateid'];
            $data_template['questionnairetemplatename'] = $a_data_temp[0]['questionnairetemplate_name'];
            $data_template['title'] = $a_data_temp[0]['title_questionnaire'];
            $pageid = '';
            $i=-1;$c=0;
            $k=0;
            foreach($a_data_temp as $key => $val){
                if($pageid != $val['pageid']){
                    $c=0;
                    $i++;
                    $data_template["pages"][$i]['name'] = $val['name_page'];
                    $data_template["pages"][$i]['title'] = $val['title_page'];
                    if($val['choicedetail_other'] == 1){
                        $data_template["pages"][$i]['otherText']  = $val['choicedetail_name'] ; //choicedetail_name
                    }
                    $data_template["pages"][$i]['elements'][$c]['type'] =$val['choice_type'];
                    $data_template["pages"][$i]['elements'][$c]['name'] =$val['choice_name'];
                    $data_template["pages"][$i]['elements'][$c]['title'] =$val['choice_title'];
                    $data_template["pages"][$i]['elements'][$c]['hasOther'] =(isset($val['hasother']) && $val['hasother'] == 1) ? true : false;//0=false ,1=true
                    $data_template["pages"][$i]['elements'][$c]['isRequired'] =(isset($val['required']) && $val['required'] == 1) ? true : false;//0=false ,1=true
                    $data_template["pages"][$i]['elements'][$c]['choiceid'] =$val['choiceid'];
                    //$data_template["pages"][$i]['elements'][$c]['choicedetailid'] =$val['choicedetailid'];
                    if($val['choicedetail_other'] == 1){
                        $data_template["pages"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
                        $data_template["pages"][$i]['elements'][$c]['otherTextid'] = $val['choicedetailid'];
                    }else if($val['choice_type'] != 'text'){
                        //$k=0;
                        $data_template["pages"][$i]['elements'][$c]['choices'][$val['choicedetailid']] = $val['choicedetail_name'];
                    } else if ($val['choice_type'] == 'text'){
                        $data_template["pages"][$i]['elements'][$c]['choicedetailid'] =$val['choicedetailid'];
                    }

                    $pageid = $val['pageid'];
                    $choiceid = $val['choiceid'];
                    //$i++;
                }else if($pageid == $val['pageid']){

                    if($choiceid != $val['choiceid']){
                        $c++;
                        $data_template["pages"][$i]['elements'][$c]['type'] =$val['choice_type'];
                        $data_template["pages"][$i]['elements'][$c]['name'] =$val['choice_name'];
                        $data_template["pages"][$i]['elements'][$c]['title'] =$val['choice_title'];
                        $data_template["pages"][$i]['elements'][$c]['hasOther'] =(isset($val['hasother']) && $val['hasother'] == 1) ? true : false;//0=false ,1=true
                        $data_template["pages"][$i]['elements'][$c]['isRequired'] =(isset($val['required']) && $val['required'] == 1) ? true : false;//0=false ,1=true
                        $data_template["pages"][$i]['elements'][$c]['choiceid'] =$val['choiceid'];
                        //$data_template["pages"][$i]['elements'][$c]['choicedetailid'] =$val['choicedetailid'];

                        if($val['choicedetail_other'] == 1){
                            $data_template["pages"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
                            $data_template["pages"][$i]['elements'][$c]['otherTextid'] = $val['choicedetailid'];
                        }else if($val['choice_type'] != 'text'){
                            $k=0;
                            $data_template["pages"][$i]['elements'][$c]['choices'][$val['choicedetailid']] = $val['choicedetail_name'];
                        } else if ($val['choice_type'] == 'text'){
                            $data_template["pages"][$i]['elements'][$c]['choicedetailid'] =$val['choicedetailid'];
                        }
                    }else{
                        if($val['choicedetail_other'] == 1){
                            $data_template["pages"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
                            $data_template["pages"][$i]['elements'][$c]['otherTextid'] = $val['choicedetailid'];
                        }else if($val['choice_type'] != 'text'){
                            $k++;
                            $data_template["pages"][$i]['elements'][$c]['choices'][$val['choicedetailid']] = $val['choicedetail_name'];
                        } else if ($val['choice_type'] == 'text'){
                            $data_template["pages"][$i]['elements'][$c]['choicedetailid'] =$val['choicedetailid'];
                        }
                    }

                    $pageid = $val['pageid'];
                    $choiceid = $val['choiceid'];
                }
            }//foreach

            $a_data["result"]["data"] = $data_template;
        }

        return $a_data;
    }


    //Account Questionnaire Get
    public function get_account_questionnaire_get()
    {
        $a_param =  $this->input->get();
        $a_data =$this->get_cache_account($a_param);
        $this->return_data($a_data);
    }
    //Account Questionnaire Post
    public function get_account_questionnaire_post()
    {
        $request_body = file_get_contents('php://input');
        $a_param     = json_decode($request_body,true);
        $a_data =$this->get_cache_account($a_param);
        $this->return_data($a_data);
    }

    private function get_cache_account($a_params=array())
    {
        $this->load->library('managecached_library');

        $a_cache = array();
        $a_cache["_ctag"] =  $this->_module.'/';
        $a_cache["_ckname"] =$this->_module.'/get_content';

        $contactid = @$a_params['contactid'];
        $crmid = @$a_params['crmid'];
        $servicerequestid = @$a_params['servicerequestid'];

        if($contactid == '' ){
            $a_return  =  array(
                'status' => '',
                'error' =>  'Invalid Request Contact ID !',
                'result' => '',
                'data' => '',
                'limit' => '',
                'offset' => '0',
                'time' => date('Y-m-d H:i:s'),
            );
            $this->return_data($a_return);
        }
        if($crmid == '' ){
            $a_return  =  array(
                'status' => '',
                'error' =>  'Invalid Request Crm ID !',
                'result' => '',
                'data' => '',
                'limit' => '',
                'offset' => '0',
                'time' => date('Y-m-d H:i:s'),
            );
            $this->return_data($a_return);
        }

        $sql_select = "SELECT * FROM aicrm_smartquestionnairerel WHERE smartquestionnaireid = '".$crmid."' and relcrmid = '".$contactid."' ";

        if(isset($servicerequestid) && $servicerequestid != ''){
            $sql_select .= " and servicerequestid = '".$servicerequestid."' and  relmodule = 'ServiceRequest' ";
        }
        //echo $sql_select; exit;
        $query = $this->db->query($sql_select);
        $data_ques = $query->result_array();

        if(count($data_ques) <= 0 ){
            $a_return  =  array(
                'status' => '',
                'error' =>  'ไม่พบแบบสอบถาม ',
                'result' => '',
                'data' => '',
                'limit' => '',
                'offset' => '0',
                'time' => date('Y-m-d H:i:s'),
            );
            $this->return_data($a_return);
        }

        $a_condition = array();
        $a_condition = $this->set_param($a_params);

        $limit = @$a_params["limit"];
        $offset = @$a_params["offset"];
        $order= @$a_params["orderby"];
        $a_order = $this->set_order($order);

        $a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
        $a_limit["offset"] = ($offset == "") ? 0 :$offset;

        $a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
        $a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
        $a_build["limit"] = http_build_query($a_limit);

        $a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));

        $a_data = $this->managecached_library->get_memcache($a_cache);

        if($a_data===false)
        {
            $a_list = $this->smartquestionnaire_model->get_list_user($a_condition,$a_order,$a_limit);
            $a_data = $this->get_data_user($a_list);
            //alert($a_data);exit();
            $a_data["data"] = $a_data["result"];
            $a_data["limit"] = $a_limit["limit"]  ;
            $a_data["offset"] = $a_limit["offset"]  ;
            $a_data["time"] = date("Y-m-d H:i:s");
            $a_cache["data"] = $a_data["result"];
            $a_cache["data"]["time"] = date("Y-m-d H:i:s");
            $this->managecached_library->set_memcache($a_cache,"2400");
        }

        return $a_data;
    }

    private function get_data_user($a_data)
    {
        //echo "<pre>"; print_r($a_data); echo "</pre>";
        if($a_data['status'] == 1){
            $a_data['status'] = 0;
            $a_data['error'] = 'คุณได้ตอบแบบสอบถามแล้ว';
            $a_data['result']["data"] = array();
        }else{
            $a_data['status'] = 1;
            $a_data['error'] = '';
            $a_data['result']["data"] = array();
        }
        //echo "<pre>"; print_r($a_data); echo "</pre>"; exit;
        return $a_data;
    }

    public function insert_questionnaire_post(){

        $request_body = file_get_contents('php://input');
        $dataJson     = json_decode($request_body,true);

        $response_data = null;
        $a_request =$dataJson;

        $this->common->_filename= "Insert_Questionnaire";
        $this->common->set_log('Input data--->',$a_request,array());

        $response_data = $this->get_insert_questionnaire($a_request);

        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->common->_filename= "Insert_Questionnaire";
        $this->common->set_log($url." Output data --->",$a_request,$response_data);
        if ( $response_data ) {
            $this->response($response_data, 200); // 200 being the HTTP response code
        } else {
            $this->response(array(
                'error' => 'Couldn\'t find Set Content!'
            ), 404);
        }
    }

    private function get_insert_questionnaire($a_request){

        if(!$this->oauth_server->verifyResourceRequest(OAuth2\Request::createFromGlobals())){
            $a_data['status'] = false;
            $a_data['error'] = 'Access Token not found';
            $a_data['time'] = date("Y-m-d H:i:s");
            $a_data["data"]["data"] = '';
            $a_data["data"]['total'] = 0;
            $a_data['offset'] = 0;
            $a_data['limit'] = 0;
            $this->return_data_token($a_data);
        }

        $this->load->library('managecached_library');
        $response_data = null;
        //alert($a_request); exit;
        $module=isset($a_request['module']) ? $a_request['module'] : "";
        $templateid=isset($a_request['templateid']) ? $a_request['templateid'] : "";
        $accountid=isset($a_request['accountid']) ? $a_request['accountid'] : "";
        //$userid=isset($a_request['userid']) ? $a_request['userid'] : "";
        //$servicerequestid = isset($a_request['servicerequestid']) ? $a_request['servicerequestid'] : "";
        $crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
        $action=isset($a_request['action']) ? $a_request['action'] : "";
        $data=isset($a_request['data']) ? $a_request['data'] : "";

        //Insert Questionnaire Answer
        //$tab_name_answer ;
        //$tab_name_index_answer;
        $module_answer = 'Questionnaireanswer';
        $data_answer[0]['questionnaireanswer_name'] = 'แบบสอบถาม Loyalty';
        $data_answer[0]['questionnairetemplateid'] = $templateid;

        list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module_answer,$crmid,$action,$this->tab_name_answer,$this->tab_name_index_answer,$data_answer);

        //alert($data); exit;
        $data_format = $this->set_format_data($data,$templateid,@$crmid,@$userid);
        //alert($data_format);exit;
        //echo count($data_format); exit;
        if(count($data_format) > 0 && $module == "Questionnaire"){
            //Set String Insert
            //echo 555; exit;
            $sql = 'INSERT INTO aicrm_questionnaire_answer (questionnaireid,relcrmid ,relmodule ,choiceid ,choicedetailid ,choicedetail,createdate) VALUES ';
            foreach($data_format as $key => $val){
                $sql .= " ".@$comma." ( '".$val['questionnaireid']."', '".$val['userid']."' , '', '".$val['choiceid']."', '".$val['choicedetailid']."', '".$val['choice']."' , '".date('Y-m-d H:i:s')."' ) ";
                $comma = ',';
            }
            //echo $sql; exit;
            $query = $this->db->query($sql);
            //Data Insert
            /*$sql_update = " UPDATE aicrm_smartquestionnairerel SET date_answer = '".date('Y-m-d H:i:s')."' WHERE smartquestionnaireid = '".$crmid."' and relcrmid = '".$userid."' and  servicerequestid = '".$servicerequestid."' ";
            $this->db->query($sql_update);*/

            //alert($query); exit;
            if($query){

                $a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
                $a_return["data"] =array(
                    'Crmid' => $crmid,
                    'QuestionnaireNo' => $DocNo,

                );
            }else{
                $a_return  =  array(
                    'Type' => 'E',
                    'Message' => 'Unable to complete transaction',
                );
            }
        }else{//echo "ddd";
            $a_return  =  array(
                'Type' => 'E',
                'Message' =>  'Invalid Request!',
            );
        }
        return array_merge($this->_return,$a_return);
    }

    public function set_format_data($a_param=array(),$templateid=NULL,$crmid=NULL,$userid=NULL)
    {
        //Set Answer
        if(count($a_param)>=0){
            foreach ($a_param as $key => $value){

                if($value['type'] == 'dropdown'){
                    if($value["choice"] != false){
                        $a_response["questionnaireid"] = (isset($crmid) && $crmid!="") ?$crmid:"0";
                        $a_response["userid"] = (isset($userid) && $userid!="") ? $userid:"0" ;
                        $a_response["choiceid"] = (isset($value["choiceid"]) && $value["choiceid"]!="") ? $value["choiceid"]:"0" ;
                        $a_response["type"] = (isset($value["type"]) && $value["type"]!="") ? $value["type"]:"" ;
                        foreach($value["choice"] as $k => $v){
                            $data = explode("|##|", $v);
                            $a_response["choicedetailid"] = (isset($data["0"]) && $data["0"]!="") ? $data["0"]:"0" ;
                            $a_response["choice"] = (isset($data["1"]) && $data["1"]!="") ? $data["1"]:"" ;
                            $a_return[$value["choiceid"]][$a_response["choicedetailid"]] = $a_response;
                        }
                    }
                }else if($value['type'] == 'radiogroup'){
                    if($value["choice"] != false){
                        $a_response["questionnaireid"] = (isset($crmid) && $crmid!="") ?$crmid:"0" ;
                        $a_response["userid"] = (isset($userid) && $userid!="") ? $userid:"0" ;
                        $a_response["choiceid"] = (isset($value["choiceid"]) && $value["choiceid"]!="") ? $value["choiceid"]:"0" ;
                        $a_response["type"] = (isset($value["type"]) && $value["type"]!="") ? $value["type"]:"" ;

                        foreach($value["choice"] as $k => $v){
                            $data = explode("|##|", $v);
                            $a_response["choicedetailid"] = (isset($data[0]) && $data[0]!="") ? $data[0]:"0";
                            if(isset($data[2]) && $data[2] == 'get_other'){
                                $a_response["choice"] = (isset($value["otherText"]) && $value["otherText"] !="" ) ? $value["otherText"] : "" ;
                            }else{
                                $a_response["choice"] = (isset($data[1]) && $data[1] !="" ) ? $data[1]: "" ;
                            }

                            $a_return[$value["choiceid"]][$a_response["choicedetailid"]] = $a_response;
                        }
                    }
                }else if($value['type'] == 'checkbox'){
                    if($value["choice"] != false){
                        $a_response["questionnaireid"] = (isset($crmid) && $crmid!="") ?$crmid:"0" ;
                        $a_response["userid"] = (isset($userid) && $userid!="") ? $userid:"0" ;
                        $a_response["choiceid"] = (isset($value["choiceid"]) && $value["choiceid"]!="") ? $value["choiceid"]:"0" ;
                        $a_response["type"] = (isset($value["type"]) && $value["type"]!="") ? $value["type"]:"" ;

                        $temp = $a_response ;
                        foreach($value["choice"] as $k => $v){
                            $data = explode("|##|", $v);

                            $temp["choicedetailid"] = (isset($data[0]) && $data[0]!="") ? $data[0]:"0";

                            if(isset($data[2]) && $data[2] == 'get_other'){
                                $temp["choice"] = (isset($value["otherText"]) && $value["otherText"] !="" ) ? $value["otherText"] : "" ;
                            }else{
                                $temp["choice"] = (isset($data[1]) && $data[1] !="" ) ? $data[1]: "" ;
                            }

                            $a_return[$value["choiceid"]][$temp["choicedetailid"]] = $temp;
                        }
                    }
                }else{//Text
                    if($value["choice"] != false){
                        $a_response["questionnaireid"] = (isset($crmid) && $crmid!="") ?$crmid:"0" ;
                        $a_response["userid"] = (isset($userid) && $userid!="") ? $userid:"0" ;
                        $a_response["choiceid"] = (isset($value["choiceid"]) && $value["choiceid"]!="") ? $value["choiceid"]:"0" ;
                        $a_response["type"] = (isset($value["type"]) && $value["type"]!="") ? $value["type"]:"" ;
                        $a_response["choicedetailid"] = (isset($value["choicedetailid"]) && $value["choicedetailid"]!="") ? $value["choicedetailid"]:"0" ;
                        $a_response["choice"] = (isset($value["choice"]) && $value["choice"]!="") ? $value["choice"]:"" ;

                        $a_return[$value["choiceid"]][$a_response["choicedetailid"]] = $a_response;
                    }

                }
            }// foreach question
        }// if count
        //Set Answer

        //alert($a_return); exit;

        //Get Questionnaire Template
        $sql_tem = 'SELECT 
        aicrm_questionnairetemplate_page.*,
        aicrm_questionnairetemplate_choice.*,
        aicrm_questionnairetemplate_choicedetail.*
    	FROM aicrm_questionnairetemplate
    	LEFT JOIN aicrm_questionnairetemplate_page ON aicrm_questionnairetemplate_page.questionnairetemplateid = aicrm_questionnairetemplate.questionnairetemplateid
    	LEFT JOIN aicrm_questionnairetemplate_choice ON aicrm_questionnairetemplate_choice.pageid = aicrm_questionnairetemplate_page.pageid
    	LEFT JOIN aicrm_questionnairetemplate_choicedetail ON aicrm_questionnairetemplate_choicedetail.choiceid = aicrm_questionnairetemplate_choice.choiceid
    	WHERE aicrm_questionnairetemplate.questionnairetemplateid = "'.$templateid.'" order by aicrm_questionnairetemplate_page.pageid asc, aicrm_questionnairetemplate_choice.choiceid asc , aicrm_questionnairetemplate_choicedetail.choicedetailid asc';
        $query = $this->db->query($sql_tem);
        $data_tem = $query->result_array();
        //alert($data_tem);
        //Set Format
        $fomat_template = $this->set_format($data_tem);
        //alert($fomat_template); exit;
        //Insert New Questionnaire Template
        $title_questionnaire = @$fomat_template['title'];
        foreach($fomat_template['pages'] as $key => $val){

            //inset tabe aicrm_questionnaire_page
            $sql = "insert into aicrm_questionnaire_page (questionnaireid,title_questionnaire,title_page,name_page,sequence_page) ";
            $sql .= " VALUES ('".$crmid."','".$title_questionnaire."','".$val['title']."','".$val['name']."','".($key +1)."'); ";

            $this->db->query($sql);
            $pageid = $this->db->insert_id();
            //echo $pageid; exit;
            foreach($val['elements'] as $k => $v){
                //inset tabe aicrm_questionnaire_choice
                $hasOther = (isset($v['hasOther']) && $v['hasOther'] == true) ? 1 : 0;
                $isRequired =(isset($v['isRequired']) && $v['isRequired'] == true) ? 1 : 0;

                $sql_choice = "insert into aicrm_questionnaire_choice (questionnaireid,choice_type,pageid,choice_title,choice_name,sequence,hasother,required) ";
                $sql_choice .= " VALUES ('".$crmid."','".$v['type']."','".$pageid."','".@$v['title']."','".@$v['name']."','".($k+1)."','".$hasOther."','".$isRequired."'); ";

                $this->db->query($sql_choice);
                $choiceid = $this->db->insert_id();
                $data['pages'][$key]['elements'][$k]['choiceid'] = $choiceid;

                /*if(isset($a_return[$v['choiceid']][$v['choicedetailid']])){
                    $a_return[$v['choiceid']][$v['choicedetailid']]['choiceid'] = $choiceid;
                }*/


                if($v['type'] == 'text'){
                    //inset tabe aicrm_questionnaire_choicedetail (Type Text)
                    $sql_choicedetail = "insert into aicrm_questionnaire_choicedetail (questionnaireid,choiceid,choicedetail_name,sequence_detail,choicedetail_other) ";
                    $sql_choicedetail .= " VALUES ('".$crmid."','".$choiceid."','','1','0'); ";
                    $this->db->query($sql_choicedetail);

                    $choicedetailid = $this->db->insert_id();

                    if(isset($a_return[$v['choiceid']][$v['text']])){
                        $a_return[$v['choiceid']][$v['text']]['choiceid'] = $choiceid;
                        $a_return[$v['choiceid']][$v['text']]['choicedetailid'] = $choicedetailid;
                    }

                    $kc++;
                }else{
                    $kc = 1 ;
                    foreach($v['choices'] as $kchoice => $choice){

                        if(is_array($choice)){
                            $value = $choice['value'];
                            $text = $choice['text'] ;
                        }else{
                            $value = $choice ;
                            $text = $choice ;
                        }
                        //inset tabe aicrm_questionnaire_choicedetail
                        $sql_choicedetail = "insert into aicrm_questionnaire_choicedetail (questionnaireid,choiceid,choicedetail_value,choicedetail_name,sequence_detail,choicedetail_other) ";
                        $sql_choicedetail .= " VALUES ('".$crmid."','".$choiceid."','".$value."','".$text."','".($kchoice+1)."','0'); ";

                        $this->db->query($sql_choicedetail);
                        $choicedetailid = $this->db->insert_id();

                        if(isset($a_return[$v['choiceid']][$v[$choice]])){
                            $a_return[$v['choiceid']][$v[$choice]]['choiceid'] = $choiceid;
                            $a_return[$v['choiceid']][$v[$choice]]['choicedetailid'] = $choicedetailid;
                        }

                        $kc++;
                    }

                    if(isset($v['otherText']) && $v['otherText'] != ''){
                        $sql_choicedetail = "insert into aicrm_questionnaire_choicedetail (questionnaireid,choiceid,choicedetail_name,sequence_detail,choicedetail_other) ";
                        $sql_choicedetail .= " VALUES ('".$crmid."','".$choiceid."','".$v['otherText']."','".$kc."','1'); ";

                        $this->db->query($sql_choicedetail);

                        $choicedetailid = $this->db->insert_id();
                        //alert($v[$v['otherText']]); exit;
                        if(isset($a_return[$v['choiceid']][$v[$v['otherText']]])){
                            $a_return[$v['choiceid']][$v[$v['otherText']]]['choiceid'] = $choiceid;
                            $a_return[$v['choiceid']][$v[$v['otherText']]]['choicedetailid'] = $choicedetailid;
                        }

                    }
                }//else

            }//foreach elements
        }//foreach pages
        //Insert New Questionnaire Template
        //alert($a_return); exit;
        //Set format return
        $data = array();
        foreach ($a_return as $key => $value) {
            foreach ($value as $k => $v) {
                array_push($data,$v);
            }
        }

        //alert($data); exit;

        return @$data;
    }// public

    public function return_data_token($a_data)
    {
        if($a_data)
        {
            $format =  $this->input->get("format",true);
            $a_return["Type"] = ($a_data["status"])?"S":"T";
            $a_return["Message"] = $a_data["error"];
            $a_return["total"] = @$a_data["data"]["total"];
            $a_return["offset"] = $a_data["offset"];
            $a_return["limit"] = $a_data["limit"];
            $a_return["cachetime"] = $a_data["time"];
            $a_return["data"] = @$a_data["data"]["data"];
            if ($format!="json" && $format!="xml"){
                $this->response($a_return, 200); // 200 being the HTTP response code
            }else{
                $this->response($a_return, 200); // 200 being the HTTP response code
            }
        }
        else
        {
            $this->response(array('error' => 'Couldn\'t find any Building!'), 404);
        }
    }

    public function get_template_post()
    {
        $request_body = file_get_contents('php://input');
        $a_param     = json_decode($request_body,true);

        $a_data =$this->get_template($a_param);

        $this->return_data($a_data);
    }

    private function get_template($a_params=array())
    {
        $this->load->library('managecached_library');

        $a_cache = array();
        $a_cache["_ctag"] =  $this->_module.'/';
        $a_cache["_ckname"] =$this->_module.'/get_content';

        $a_condition = array();
        $a_condition = $this->set_param($a_params);

        $limit = @$a_params["limit"];
        $offset = @$a_params["offset"];
        $order= @$a_params["orderby"];
        $a_order = $this->set_order($order);

        $a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
        $a_limit["offset"] = ($offset == "") ? 0 :$offset;

        $a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
        $a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
        $a_build["limit"] = http_build_query($a_limit);

        $a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));

        $a_data = $this->managecached_library->get_memcache($a_cache);

        if($a_data===false)
        {
            $a_list = $this->questionnaire_model->get_list_template($a_condition,$a_order,$a_limit);

            $a_data = $this->get_data_template($a_list);
            // alert($a_data);exit();
            $a_data["data"] = $a_data["result"];
            $a_data["limit"] = $a_limit["limit"]  ;
            $a_data["offset"] = $a_limit["offset"]  ;
            $a_data["time"] = date("Y-m-d H:i:s");
            $a_cache["data"] = $a_data["result"];
            $a_cache["data"]["time"] = date("Y-m-d H:i:s");
            $this->managecached_library->set_memcache($a_cache,"2400");
        }

        return $a_data;
    }

    public function chkAns_post()
    {
        $request_body = file_get_contents('php://input');
        $a_param = json_decode($request_body,true);

        $sql = $this->db->get_where('aicrm_questionnaireanswer', ['questionnairetemplateid'=>$a_param['questionnairetemplateid'], 'accountid'=>$a_param['accountid'], 'smartquestionnaireid'=>$a_param['smartquestionnaireid']]);
        $row = $sql->num_rows();

        $this->response(['row'=>$row], 200);
    }

    public function getTemplateData_post()
    {
        $request_body = file_get_contents('php://input');
        $a_param     = json_decode($request_body,true);

        $a_data =$this->getTemplateData($a_param);

        $this->return_data($a_data);
    }

    private function getTemplateData($a_params=array())
    {
        $this->load->library('managecached_library');

        $a_cache = array();
        $a_cache["_ctag"] =  $this->_module.'/';
        $a_cache["_ckname"] =$this->_module.'/get_content';

        $a_condition = array();
        $a_condition = $this->set_param($a_params);

        $limit = @$a_params["limit"];
        $offset = @$a_params["offset"];
        $order= @$a_params["orderby"];
        $a_order = $this->set_order($order);

        $a_limit["limit"] = ($limit == "") ? $this->_limit:$limit;
        $a_limit["offset"] = ($offset == "") ? 0 :$offset;

        $a_build["condition"] = empty($a_condition) ? $a_condition : http_build_query($a_condition);
        $a_build["order"] = empty($a_order) ?$a_order : http_build_query($a_order);
        $a_build["limit"] = http_build_query($a_limit);

        $a_cache["_ckname"] .='_'.str_replace("&","-",rawurldecode(http_build_query($a_build)));

        $a_data = $this->managecached_library->get_memcache($a_cache);

        if($a_data===false)
        {
            $a_list = $this->questionnaire_model->getTemplateList($a_condition, $a_order, $a_limit, $a_params);

            $a_data = $this->get_data_template($a_list);
            // alert($a_data);exit();
            $a_data["data"] = $a_data["result"];
            $a_data["limit"] = $a_limit["limit"]  ;
            $a_data["offset"] = $a_limit["offset"]  ;
            $a_data["time"] = date("Y-m-d H:i:s");
            $a_cache["data"] = $a_data["result"];
            $a_cache["data"]["time"] = date("Y-m-d H:i:s");
            $this->managecached_library->set_memcache($a_cache,"2400");
        }

        return $a_data;
    }

    public function getTemplateImage_post()
    {
        $request_body = file_get_contents('php://input');
        $a_param     = json_decode($request_body,true);

        $sql = $this->db->query("SELECT aicrm_attachments.* FROM aicrm_seattachmentsrel 
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid=aicrm_seattachmentsrel.attachmentsid 
			INNER JOIN aicrm_attachments ON aicrm_attachments.attachmentsid = aicrm_seattachmentsrel.attachmentsid 
			WHERE aicrm_crmentity.setype='image_header' 
			AND aicrm_seattachmentsrel.crmid='".$a_param['crmid']."'");
        $imgHeader = $sql->row_array();

        $sql = $this->db->query("SELECT aicrm_attachments.* FROM aicrm_seattachmentsrel 
			INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid=aicrm_seattachmentsrel.attachmentsid 
			INNER JOIN aicrm_attachments ON aicrm_attachments.attachmentsid = aicrm_seattachmentsrel.attachmentsid 
			WHERE aicrm_crmentity.setype='image_footer' 
			AND aicrm_seattachmentsrel.crmid='".$a_param['crmid']."'");
        $imgFooter = $sql->row_array();

        $this->response(['imgHeader'=>$imgHeader, 'imgFooter'=>$imgFooter], 200);
    }

    private function set_format($a_data=array())
    {
        $data_template = array();
        $data_template['title'] = $a_data[0]['title_questionnaire'];
        $pageid = '';
        $i=-1;$c=0;
        foreach($a_data as $key => $val){
            if($pageid != $val['pageid']){
                $c=0;
                $i++;
                $data_template["pages"][$i]['name'] = $val['name_page'];
                $data_template["pages"][$i]['title'] = $val['title_page'];
                if($val['choicedetail_other'] == 1){
                    $data_template["pages"][$i]['otherText']  = $val['choicedetail_name'] ; //choicedetail_name
                }
                $data_template["pages"][$i]['elements'][$c]['choiceid'] = $val['choiceid'];
                $data_template["pages"][$i]['elements'][$c]['type'] =$val['choice_type'];
                $data_template["pages"][$i]['elements'][$c]['name'] =$val['choice_name'];
                $data_template["pages"][$i]['elements'][$c]['title'] =$val['choice_title'];
                $data_template["pages"][$i]['elements'][$c]['hasOther'] =(isset($val['hasother']) && $val['hasother'] == 1) ? true : false;//0=false ,1=true
                $data_template["pages"][$i]['elements'][$c]['isRequired'] =(isset($val['required']) && $val['required'] == 1) ? true : false;//0=false ,1=true
                if($val['choicedetail_other'] == 1){
                    $data_template["pages"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
                }else if($val['choice_type'] != 'text'){
                    $k=0;
                    $data_template["pages"][$i]['elements'][$c]['choices'][$k] = $val['choicedetail_name'];
                }
                $choicedetail_name = (isset($val['choicedetail_name']) && $val['choicedetail_name'] == '') ? 'text' : $val['choicedetail_name'] ;
                $data_template["pages"][$i]['elements'][$c][$choicedetail_name ] =  $val['choicedetailid'];

                $pageid = $val['pageid'];
                $choiceid = $val['choiceid'];
                //$i++;
            }else if($pageid == $val['pageid']){

                if($choiceid != $val['choiceid']){
                    $c++;
                    $data_template["pages"][$i]['elements'][$c]['choiceid'] =$val['choiceid'];
                    $data_template["pages"][$i]['elements'][$c]['type'] =$val['choice_type'];
                    $data_template["pages"][$i]['elements'][$c]['name'] =$val['choice_name'];
                    $data_template["pages"][$i]['elements'][$c]['title'] =$val['choice_title'];
                    $data_template["pages"][$i]['elements'][$c]['hasOther'] =(isset($val['hasother']) && $val['hasother'] == 1) ? true : false;//0=false ,1=true
                    $data_template["pages"][$i]['elements'][$c]['isRequired'] =(isset($val['required']) && $val['required'] == 1) ? true : false;//0=false ,1=true
                    if($val['choicedetail_other'] == 1){
                        $data_template["pages"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
                    }else if($val['choice_type'] != 'text'){
                        $k=0;
                        $data_template["pages"][$i]['elements'][$c]['choices'][$k] = $val['choicedetail_name'];
                    }
                }else{
                    if($val['choicedetail_other'] == 1){
                        $data_template["pages"][$i]['elements'][$c]['otherText'] = $val['choicedetail_name'];
                    }else if($val['choice_type'] != 'text'){
                        $k++;
                        $data_template["pages"][$i]['elements'][$c]['choices'][$k] = $val['choicedetail_name'];
                    }
                }

                $choicedetail_name = (isset($val['choicedetail_name']) && $val['choicedetail_name'] == '') ? 'text' : $val['choicedetail_name'] ;
                $data_template["pages"][$i]['elements'][$c][$choicedetail_name] =  $val['choicedetailid'];

                $pageid = $val['pageid'];
                $choiceid = $val['choiceid'];
            }
        }//foreach
        return $data_template;
    }

    public function insert_questionnaire_web_post(){

        $request_body = file_get_contents('php://input');
        $dataJson     = json_decode($request_body,true);

        $response_data = null;
        $a_request =$dataJson;

        $this->common->_filename= "Insert_Questionnaire_web";
        $this->common->set_log('Input data--->',$a_request,array());

        $response_data = $this->get_insert_questionnaire_web($a_request);

        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->common->_filename= "Insert_Questionnaire_web";
        $this->common->set_log($url." Output data --->",$a_request,$response_data);
        if ( $response_data ) {
            $this->response($response_data, 200); // 200 being the HTTP response code
        } else {
            $this->response(array(
                'error' => 'Couldn\'t find Set Content!'
            ), 404);
        }
    }

    private function get_insert_questionnaire_web($a_request){

        $this->load->library('managecached_library');
        $response_data = null;
        // alert($a_request); exit;
        $module=isset($a_request['module']) ? $a_request['module'] : "";
        $templateid=isset($a_request['templateid']) ? $a_request['templateid'] : "";
        $accountid=isset($a_request['accountid']) ? $a_request['accountid'] : "";
        $smartquestionnaireid=isset($a_request['smartquestionnaireid']) ? $a_request['smartquestionnaireid'] : "";
        //$userid=isset($a_request['userid']) ? $a_request['userid'] : "";
        //$servicerequestid = isset($a_request['servicerequestid']) ? $a_request['servicerequestid'] : "";
        $crmid=isset($a_request['crmid']) ? $a_request['crmid'] : "";
        $action=isset($a_request['action']) ? $a_request['action'] : "";
        $data=isset($a_request['data']) ? $a_request['data'] : "";
        $userid=isset($a_request['userid']) ? $a_request['userid'] : "1";
        //Insert Questionnaire Answer
        //$tab_name_answer ;
        //$tab_name_index_answer;
        $module_answer = 'Questionnaireanswer';
        $data_answer[0]['questionnaireanswer_name'] = 'แบบสอบถาม Web';
        $data_answer[0]['questionnairetemplateid'] = $templateid;
        $data_answer[0]['accountid'] = $accountid;
        $data_answer[0]['smartquestionnaireid'] = $smartquestionnaireid;
        $data_answer[0]['smownerid'] = $userid;
        // alert($data_answer[0]); exit;
        
        list($chk,$crmid,$DocNo)=$this->crmentity->Insert_Update($module_answer,$crmid,$action,$this->tab_name_answer,$this->tab_name_index_answer,$data_answer,$userid);

        $this->db->update('aicrm_questionnaireanswer', ['accountid'=>$accountid, 'smartquestionnaireid'=>$smartquestionnaireid], ['questionnaireanswerid'=>$crmid]);

        //alert($data); exit;
        $data_format = $this->set_format_data($data,$templateid,@$crmid,$userid);
        //alert($data_format);exit;
        //echo count($data_format); exit;
        if(count($data_format) > 0 && $module == "Questionnaire"){
            //Set String Insert
            //echo 555; exit;
            $sql = 'INSERT INTO aicrm_questionnaire_answer (questionnaireid,relcrmid ,relmodule ,choiceid ,choicedetailid ,choicedetail,createdate) VALUES ';
            foreach($data_format as $key => $val){
                $sql .= " ".@$comma." ( '".$val['questionnaireid']."', '".$val['userid']."' , '', '".$val['choiceid']."', '".$val['choicedetailid']."', '".$val['choice']."' , '".date('Y-m-d H:i:s')."' ) ";
                $comma = ',';
            }
            //echo $sql; exit;
            $query = $this->db->query($sql);
            //Data Insert
            /*$sql_update = " UPDATE aicrm_smartquestionnairerel SET date_answer = '".date('Y-m-d H:i:s')."' WHERE smartquestionnaireid = '".$crmid."' and relcrmid = '".$userid."' and  servicerequestid = '".$servicerequestid."' ";
            $this->db->query($sql_update);*/

            //alert($query); exit;
            if($query){

                $a_return["Message"] = ($action=="add")?"Insert Complete" : "Update Complete";
                $a_return["data"] =array(
                    'Crmid' => $crmid,
                    'QuestionnaireNo' => $DocNo,

                );
            }else{
                $a_return  =  array(
                    'Type' => 'E',
                    'Message' => 'Unable to complete transaction',
                );
            }
        }else{//echo "ddd";
            $a_return  =  array(
                'Type' => 'E',
                'Message' =>  'Invalid Request!',
            );
        }
        return array_merge($this->_return,$a_return);
    }

}