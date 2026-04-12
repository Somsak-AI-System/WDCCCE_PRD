<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
/**
 * ### Class Social ������Ѻ�֧ API �ͧ Social ��ҧ �
 */
class Api extends REST_Controller
{
  /**
   * crmid ��� crmid � aicrm_crmentity
   */
	private $crmid;
  function __construct()
  {
    parent::__construct();
    $this->load->library('memcached_library');
	$this->load->library('crmentity');
	$this->load->library('lib_api_common');

    $this->load->database();
	$this->load->library("common");
	$this->_format = "array";
	$this->_limit = 100;
	$this->_module = "";
  }

  	private function get_cache($a_params=array())
	{
		$this->load->library('managecached_library');

		$a_cache = array();
		$a_cache["_ctag"] =  $this->_module.'/';
		$a_cache["_ckname"] =$this->_module.'/get_content';
		$module = @$a_params["module"];
		$action = @$a_params["action"];
		$crmid= @$a_params["crmid"];
		$userid= @$a_params["userid"];
		$optimize = @$a_params['optimize'];
		$crm_subid = @$a_params['crm_subid'];

		if($module=="Case"){
		 	$module="HelpDesk";
		}elseif ($module=="Spare Part" || $module=="SparePart" ) {
			$module = "Sparepart";
		}elseif ($module=="Errors List" || $module=="ErrorsList") {
			$module = "Errorslist";
		}elseif ($module=="Spare Part List" || $module=="SparePartList") {
			$module = "Sparepartlist";
		}elseif($module=="Quotation"){
			$module="Quotes";
		}

		$a_data = $this->managecached_library->get_memcache($a_cache);

		if($a_data===false)
		{


			if($module=="Deal" && $action =="view"){

				// fix
				$a_list = Array(
					    "status" => "1",
					    "error" => "",
					    "button" => Array
					        (
					            "Create" => "true",
					            "Edit" => "true",
					            "Duplicate" => "true",
					            "Delete" => "true",
					            "View" => "true"
					        ),
					    "related_button" => Array
					        (
					        	"Tag",
					        	"Documents",
                				"Questionnaire"
					        ),
					    "language" => "TH",
					    "title" => Array
					        (
					            0 => Array
					                (
					                	"description" => "DEL64-0019",
							            "name" => "Implementation",
							            "accountname" => "Ai System",
							            "status" => "Open",
							            "color" => "#000000",
							            "createdate" => "2021-01-05",
							            "custom" => "Honda Scoopy i รุ่น Urban",
							            "amount" => "฿6,520,500"
					                )
					        ),
					    "custom" => Array
					        (
					            "0" => Array
					                (
					                    "header_name" => "Prospecting",
					                    "form" => Array
					                        (
					                            "0" => Array
					                                (
					                                    "columnname" => "Deal Stage",
					                                    "value" => 'Prospecting'
					                                ),
					                            "1" => Array
					                                (
					                                    "columnname" => 'Due Date',
					                                    "value" => '14/02/2021'
					                                ),
					                            "2" => Array
					                                (
					                                    "columnname" => 'Amount',
					                                    "value" =>'฿6,520,500' 
					                                ),
					                             "3" => Array
					                                (
					                                    "columnname" => 'Product Name',
					                                    "value" =>'Honda Scoopy i รุ่น Urban' 
					                                )
					                        )
					                ),
					         	"1" => Array
					                (
					                    "header_name" => 'Qualification',
					                    "form" => Array
					                        (
					                            "0" => Array
					                                (
					                                    "columnname" => 'description',
					                                    "value" => 'รายละเอียดงาน',
					                                )
					                        )
									),
								"2" => Array
					                (
					                    "header_name" => 'Proposal',
					                    "form" => Array
					                        (
					                            "0" => Array
					                                (
					                                    "columnname" => 'description',
					                                    "value" => 'รายละเอียดงาน',
					                                )
					                        )
									),
								"3" => Array
					                (
					                    "header_name" => 'Demo',
					                    "form" => Array
					                        (
					                            "0" => Array
					                                (
					                                    "columnname" => 'description',
					                                    "value" => 'รายละเอียดงาน',
					                                )
					                        )
									),
								"4" => Array
					                (
					                    "header_name" => 'Review',
					                    "form" => Array
					                        (
					                            "0" => Array
					                                (
					                                    "columnname" => 'description',
					                                    "value" => 'รายละเอียดงาน',
					                                )
					                        )
									),
								"5" => Array
					                (
					                    "header_name" => 'Close',
					                    "form" => Array
					                        (
					                            "0" => Array
					                                (
					                                    "columnname" => 'description',
					                                    "value" => 'รายละเอียดงาน',
					                                )
					                        )
									),
					        ),
					    "data" => Array
					        (
					            "0" => Array
					                (
					                    "header_name" => "Deal Information",
					                    "form" => Array
					                        (
					                            "0" => Array
					                                (
					                                    "columnname" => "activitytype",
					                                    "tablename" => "aicrm_activity",
					                                    "fieldlabel" => "หัวข้อเรื่อง",
					                                    "uitype" => "15",
					                                    "typeofdata" => "V~M",
					                                    "type" => "select",
					                                    "keyboard_type" => "default",
					                                    "value_default" => Array
					                                        (
					                                            "0" => "ออดิท",
					                                            "1" => "เครดิตสกอริ่ง",
					                                            "2" => "อื่นๆ"
					                                        ),
					                                    "module_select" => "",
					                                    "value_name" => "",
					                                    "value" => 'ออดิท',
					                                    "check_value" => 'yes',
					                                    "error_message" => 'หัวข้อเรื่อง cannot be empty',
					                                    "readonly" => '1',
					                                    "maximumlength" => '100',
					                                    "format_date" => '',
					                                    "is_array" => 'false',
					                                    "is_phone" => 'false',
					                                    "is_account" => 'false',
					                                    "is_product" => 'false',
					                                    "is_checkin" => 'false',
					                                    "is_hidden" => 'false',
					                                    "link" => 'false',
					                                    "no" => '',
					                                    "name" => '',
					                                    "key_valuename_select" => '',
					                                    "relate_field_up" => Array
					                                        (
					                                        ),
					                                    "relate_field_down" => Array
					                                        (
					                                        )
					                                ),
					                            "1" => Array
					                                (
					                                    "columnname" => 'smownerid',
					                                    "tablename" => 'aicrm_crmentity',
					                                    "fieldlabel" => 'ผู้รับผิดชอบ',
					                                    "uitype" => '53',
					                                    "typeofdata" => 'V~M',
					                                    "type" => 'select_index',
					                                    "keyboard_type" => 'default',
					                                    "value_default" => Array
					                                        (
					                                            "0" => Array
					                                                (
					                                                    "type" => 'user',
					                                                    "type_value" => Array
					                                                        (
					                                                            "0" => Array
					                                                                (
					                                                                    "id" => '1',
					                                                                    "user_name" => 'admin',
					                                                                    "first_name" => 'Admin',
					                                                                    "last_name" => 'Admin',
					                                                                    "name" => 'Admin Admin [admin]',
					                                                                    "area" => 'BKK',
					                                                                    "no" => '',
					                                                                    "section" => 'SYSTEM'
					                                                                ),
					                                                            "1" => Array
					                                                                (
					                                                                    "id" => '2',
					                                                                    "user_name" => 'WM1',
					                                                                    "first_name" => 'Wuttipong',
					                                                                    "last_name" => 'Mejumras',
					                                                                    "name" => 'Wuttipong Mejumras [WM1]',
					                                                                    "area" => '-',
					                                                                    "no" => 'sale',
					                                                                    "section" => 'SYSTEM'
					                                                                )
					                                                        )
					                                                ),
					                                            "1" => Array
					                                                (
					                                                    "type" => 'group',
					                                                    "type_value" => Array()
					                                                )
					                                     	),
					                                    "module_select" => 'Users',
					                                    "value_name" => 'Wuttipong Mejumras [WM2]',
					                                    "value" => '17161',
					                                    "check_value" => 'yes',
					                                    "error_message" => 'ผู้รับผิดชอบ cannot be empty',
					                                    "readonly" => '0',
					                                    "maximumlength" => '100',
					                                    "format_date" => '',
					                                    "is_array" => 'false',
					                                    "is_phone" => 'false',
					                                    "is_account" => 'false',
					                                    "is_product" => 'false',
					                                    "is_checkin" => 'false',
					                                    "is_hidden" => 'false',
					                                    "link" => 'false',
					                                    "no" => '',
					                                    "name" => '',
					                                    "key_valuename_select" => 'user_name',
					                                    "relate_field_up" => Array(),
					                                    "relate_field_down" => Array()
					                                ),
					                            "2" => Array
					                                (
					                                    "columnname" => 'phone',
					                                    "tablename" => 'aicrm_activity',
					                                    "fieldlabel" => 'เบอร์โทรศัพท์',
					                                    "uitype" => '1',
					                                    "typeofdata" => 'V~O~LE~50',
					                                    "type" => 'datetime',
					                                    "keyboard_type" => 'phone',
					                                    "value_default" => '',
					                                    "module_select" => '',
					                                    "value_name" => '',
					                                    "value" =>'' ,
					                                    "check_value" => 'no',
					                                    "error_message" => '',
					                                    "readonly" => '0',
					                                    "maximumlength" => '100',
					                                    "format_date" => 'd MMMM yyyy HH:mm:ss',
					                                    "is_array" => 'false',
					                                    "is_phone" => 'false',
					                                    "is_account" => 'false',
					                                    "is_product" => 'false',
					                                    "is_checkin" => 'false',
					                                    "is_hidden" => 'false',
					                                    "link" => 'false',
					                                    "no" => '',
					                                    "name" => '',
					                                    "key_valuename_select" => '',
					                                    "relate_field_up" => Array
					                                        (
					                                        ),
					                                    "relate_field_down" => Array
					                                        (
					                                        )

					                                )
					                        // )
					                ),
					         	"1" => Array
					                (
					                    "header_name" => 'Step 1 Plan Information',
					                    "form" => Array
					                        (
					                            "0" => Array
					                                (
					                                    "columnname" => 'description',
					                                    "tablename" => 'aicrm_crmentity',
					                                    "fieldlabel" => 'รายละเอียดงาน',
					                                    "uitype" => '19',
					                                    "typeofdata" => 'V~M',
					                                    "type" => 'textarea',
					                                    "keyboard_type" => 'default',
					                                    "value_default" => '',
					                                    "module_select" => '',
					                                    "value_name" => '',
					                                    "value" => 'รายละเอียดงาน',
					                                    "check_value" => 'yes',
					                                    "error_message" => 'รายละเอียดงาน cannot be empty',
					                                    "readonly" => '0',
					                                    "maximumlength" => '500',
					                                    "format_date" => '',
					                                    "is_array" => 'false',
					                                    "is_phone" => 'false',
					                                    "is_account" => 'false',
					                                    "is_product" => 'false',
					                                    "is_checkin" => 'false',
					                                    "is_hidden" => 'false',
					                                    "link" => 'false',
					                                    "no" => '',
					                                    "name" => '',
					                                    'key_valuename_select' =>'' ,
					                                    "relate_field_up" => Array
					                                        (
					                                        ),
					                                   "relate_field_down" => Array
					                                        (
					                                        )
					                                )
					                        )
									)
					        )
						)
					);

				//and fix

			}else{
					$a_list = $this->lib_api_common->Get_Block($module,$action,$crmid,$userid,$crm_subid);
			}

		//	alert($a_list);exit;
			

			$a_data = $this->get_data($a_list);
			$a_cache["data"]["time"] = date("Y-m-d H:i:s");
			$this->managecached_library->set_memcache($a_cache,"2400");
		}
		return $a_data;
	}

	private function get_data($a_data,$optimize='')
	{
		return $a_data;
	}

  	public function return_data($a_data,$action,$module,$a_param)
	{
		if($a_data)
		{
			//alert($a_data);exit;
			$format =  $this->input->get("format",true);
			$a_return["Type"] = ($a_data["status"])?"S":"E";
			$a_return["Message"] =$a_data["error"];
			$a_return["total"] = 0;
			$a_return["offset"] = 0;
			$a_return["limit"] = 0;
			$a_return["cachetime"] = date("Y-m-d H:i:s");
			$a_return["data"][0]["jsonrpc"] = "2.0";
			$a_return["data"][0]["id"] = "";
			$a_return["data"][0]["action_button"] = !empty($a_data["button"]) ? $a_data["button"] : "" ;
			$a_return["data"][0]["no"] = !empty($a_data["title"][0]['no']) ? $a_data["title"][0]['no'] : "" ;
			$a_return["data"][0]["name"] = !empty($a_data["title"][0]['name']) ? $a_data["title"][0]['name'] : "" ;

			$a_return["data"][0]["description"] = !empty($a_data["title"][0]['description']) ? $a_data["title"][0]['description'] : "" ;
			$a_return["data"][0]["title"] = !empty($a_data["title"][0]['accountname']) ? $a_data["title"][0]['accountname'] : "" ;
			$a_return["data"][0]["status"] = !empty($a_data["title"][0]['status']) ? $a_data["title"][0]['status'] : "" ;
			$a_return["data"][0]["color"] = !empty($a_data["title"][0]['color']) ? $a_data["title"][0]['color'] : "" ;
			$a_return["data"][0]["dateAt"] = !empty($a_data["title"][0]['createdate']) ? $a_data["title"][0]['createdate'] : "" ;
			$a_return["data"][0]["subtitle"] = !empty($a_data["title"][0]['custom']) ? $a_data["title"][0]['custom'] : "" ;
			$a_return["data"][0]["bottomTitle"] = !empty($a_data["title"][0]["amount"]) ? $a_data["title"][0]["amount"] : "" ;

			$a_return["data"][0]["related_button"] = !empty($a_data["related_button"]) ? $a_data["related_button"] : "" ;
			$a_return["data"][0]["custom"] = !empty($a_data["custom"]) ? $a_data["custom"] : "" ;

			$a_return["data"][0]["result"] = !empty($a_data["data"]) ? $a_data["data"] : "" ;

			if($action=="view"){
				$log_filename = "Detail_".$module;
				$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				// $this->common->_filename= "Insert_Calendar";
		  		$this->common->_filename= $log_filename;
		  		$this->common->set_log($url,$a_param,$a_return);
			}
			if ($format!="json" && $format!="xml"  ) {
					$this->response($a_return, 200); // 200 being the HTTP response code
			}else{
					$this->response($a_return, 200); // 200 being the HTTP response code
			}
		
		}
		else
		{
			$this->response(array('error' => 'Couldn\'t find any Service Request!'), 404);
		}
	}

  public function list_content_get()
	{
		$a_param =  $this->input->get();
		$a_data =$this->get_cache($a_param);
		$action = $a_param['action'];
		$module = $a_param['module'];

		$this->return_data($a_data,$a_data,$action,$module);
	}

	public function list_content_post()
	{
		$request_body = file_get_contents('php://input');
		$a_param     = json_decode($request_body,true);
		$action = $a_param['action'];

			$module = ($a_param['module'] == 'Sales Visit') ? 'Calendar' : $a_param['module'] ;

		$a_data =$this->get_cache($a_param);
		$this->return_data($a_data,$action,$module,$a_param);
	}



}
