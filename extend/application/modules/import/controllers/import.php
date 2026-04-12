<?php
if ( !defined('BASEPATH') )
  exit('No direct script access allowed');

	/**
	 * 
	 */
	class Import extends MY_Controller
	{
		private $description, $title, $keyword, $screen,$modulename;
		public function __construct()
		{
			parent::__construct();
			$meta = $this->config->item('meta');
			$this->title = $meta["default"]["title"];
		    $this->keyword = $meta["default"]["keyword"];
		    $this->description = $meta["default"]["description"];
		    $this->module = 'Import';
		}

		public function index()
		{
			$this->template->title('Import',$this->title); 
			$this->template->screen('Import',$this->screen); 
			$this->template->modulename('import', $this->modulename);
		    $this->template->set_metadata('description', "Import"); // 70 words (350 characters)
		    $this->template->set_metadata('keywords', $this->keyword);
		    $this->template->set_metadata('og:image', site_assets_url('images/logo.png'));
		    $this->template->set_metadata('og:title',  'Main | '.$this->title); 
		    $this->template->set_metadata('og:description', $this->description);
			$this->template->set_layout('import');
			$this->template->build('index');
		}

		public function import2(){
			//alert($_FILES); exit;
			//alert($this->input->post()); exit;
			//if ($this->input->post('BSbtninfo')) {
			$post_data = $this->input->post();
			$flag_date =$post_data['import_date'];

			$path = './uploads/';
			require_once APPPATH . "/third_party/PHPExcel.php";
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'xlsx';
			$config['remove_spaces'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			//alert($this->upload); exit;
			if (!$this->upload->do_upload('BSbtninfo')) {
				$error = array('error' => $this->upload->display_errors());
			} else {
				$data = array('upload_data' => $this->upload->data());
			}
			// alert($data); 
			// alert($error); exit;
			if(empty($error)){
				if (!empty($data['upload_data']['file_name'])) {
					$import_xls_file = $data['upload_data']['file_name'];
				} else {
					$import_xls_file = 0;
				}

				$inputFileName = $path . $import_xls_file;
      			//alert($inputFileName); exit;
				try {
					$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($inputFileName);
					$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
					$flag = true;
					$i=0;

					//alert($allDataInSheet); exit();
					foreach ($allDataInSheet as $value) {
						if($flag){
							$flag =false;
							continue;
						}
						if($value['A'] != ''){
							$inserdata[$i]['plant_id'] = trim($value['A']);
							$inserdata[$i]['zone'] = trim($value['B']);
							$inserdata[$i]['distance'] = trim($value['C']);
							$inserdata[$i]['mat_type'] = trim($value['D']);
							$inserdata[$i]['truck_size'] = trim($value['E']);
							$inserdata[$i]['truck_type'] = trim($value['F']);
							$inserdata[$i]['strength'] = trim($value['G']);
							$inserdata[$i]['mat_master'] = trim($value['H']);
							$inserdata[$i]['vendor_product_code'] = trim($value['I']);
							$inserdata[$i]['lp'] = trim(str_replace(",","",$value['J']));
							$inserdata[$i]['lp_dise'] = trim($value['K']);
							$inserdata[$i]['c_cost'] = trim(str_replace(",","",$value['L']));
							$inserdata[$i]['c_cost_vat'] = trim(str_replace(",","",$value['M']));
							$inserdata[$i]['c_price_vat'] = trim(str_replace(",","",$value['N']));
							$inserdata[$i]['min'] = trim($value['O']);
							$inserdata[$i]['div_c'] = trim($value['P']);
							$inserdata[$i]['div_c_vat'] = trim($value['Q']);
							$inserdata[$i]['div_p_vat'] = trim($value['R']);
							$inserdata[$i]['status'] = trim($value['S']);
							//$inserdata[$i]['pricelist_date'] = trim($value['T']);
							$inserdata[$i]['pricelist_date'] = date("Y-m-d", strtotime(str_replace('-', '/', trim($value['T']))));
							$inserdata[$i]['descrtption'] = trim($value['U']);
							
							$i++;
						}
					}            

          			//alert($inserdata); exit;
					$this->load->library('db_api');
					$this->load->database();
					$this->load->model("import_model");
					
					$import = $this->import_model->insert_temp($inserdata ,$flag_date);  
					
					$flag_import = true;
					foreach ($import as $key => $value) {
						if($value['flag'] =='No'){
							$flag_import = false;
						}
					}
					//alert($import); exit;
					/*if($import){
						$data['type'] = 'S';
						$data['data'] = json_encode($import);
						$data['flag_date'] = $flag_date;
						$data['msg'] = "Imported successfully";
					}else{
						$data['type'] = 'E';
						$data['data'] = '';
						$data['flag_date'] = '';
						$data['msg'] = "ERROR !";
					} */
					

					$data['type'] = 'S';
					$data['data'] = $import;
					$data['flag_date'] = $flag_date;
					$data['flag_import'] = $flag_import;
					$data['msg'] = "Imported successfully";        

				}catch (Exception $e) {
					$data['type'] = 'E';
					$data['data'] = '';
					$data['flag_date'] = '';
					$data['flag_import'] = false;
					$data['msg'] = $e->getMessage();
         //die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME). '": ' .$e->getMessage());
				}

			}else{
				$data['type'] = 'E';
				$data['data'] = '';
				$data['flag_date'] = '';
				$data['flag_import'] = false;
				$data['msg'] = ['error'];
			}     
		    
		    echo json_encode($data);
		}



		public function import3()
		{	
			$import_date = $this->input->post('import_date');
			$data = array();		
			if($import_date){ 
			  $this->load->library('db_api');
			  $this->load->database();
			  $this->load->model("import_model");
			  $flag_date = date('Y-m-d H:i:s');  
			  $import = $this->import_model->import_data($import_date); 
			}else{
			  redirect(site_url('home')); 
			}

			if($import){
			  $data['type'] = 'S';
			  $data['data'] = $import;
			  $data['total'] = count($import);
			  $data['flag_date'] = $flag_date;
			  $data['msg'] = "Imported successfully";
			}else{
			  $data['type'] = 'E';
			  $data['data'] = '';
			  $data['total'] = 0;
			  $data['flag_date'] = '';
			  $data['msg'] = "ERROR !";
			}

			echo json_encode($data);

		}


	}

	

?>