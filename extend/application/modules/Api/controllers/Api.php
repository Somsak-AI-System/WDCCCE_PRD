<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->params = [
            'UserID' => $this->session->userdata('userid'),
            'ComputerName' => COMPUTER_NAME,
            'Language' => $this->session->userdata('lang')
        ];
    }

    public function changeLang(){
        $post = $this->input->post();
        $this->session->set_userdata('lang', $post['lang']);
        echo $this->session->userdata('lang');
    }

    public function lockNav(){
        if($this->session->userdata('locknav')==false){
            $this->session->set_userdata('locknav', true);
        }else{
            if($this->session->userdata('locknav') == true){
                $this->session->set_userdata('locknav', false);
            }else{
                $this->session->set_userdata('locknav', true);
            }
        }
        alert($this->session->userdata('locknav'));
    }

    public function getDataByID(){
        $post = $this->input->post();
        $module = $post['module'];

        $this->curl->_filename = $module;

        if($module=='Contact'){
            $this->params['ContactID'] = $post['data'][0]['ReturnValue'];
            $result = $this->api_cms->serviceMaster('GetContact', $module, $this->params);
            $result = $result['alldata']['Contact'];
        }

        $return = [
            'module' => $module,
            'data' => $result
        ];
        alertJson($return);
    }

    public function customData(){
        $data = [
            ['id'=>1, 'FirstName'=>'Peter', 'LastName'=>'Parker'],
            ['id'=>2, 'FirstName'=>'John', 'LastName'=>'Mike'],
            ['id'=>3, 'FirstName'=>'Smith', 'LastName'=>'Carter'],
            ['id'=>4, 'FirstName'=>'Louis', 'LastName'=>'Jordan'],
            ['id'=>5, 'FirstName'=>'Micheal', 'LastName'=>'Sam'],
            ['id'=>6, 'FirstName'=>'Jane', 'LastName'=>'Jesica'],
            ['id'=>7, 'FirstName'=>'Kate', 'LastName'=>'Loyoat'],
            ['id'=>8, 'FirstName'=>'Johney', 'LastName'=>'Banner'],
            ['id'=>9, 'FirstName'=>'David', 'LastName'=>'System'],
            ['id'=>10, 'FirstName'=>'Dave', 'LastName'=>'Pateeo']
        ];

        alertJson($data);
    }
}