<?
class plantmaster extends MX_Controller {
	 private $description, $title, $keyword, $screen;
 public function __construct()
  {
 
   parent::__construct();
    $meta = $this->config->item('meta');
    $this->_lang = $this->config->item('lang');
    $this->title = $meta["default"]["title"];
    $this->keyword = $meta["default"]["keyword"];
    $this->description = $meta["default"]["description"];
    $this->load->library('curl');
    $this->lang->load('ai',$this->_lang);    
    $this->load->library('logging');
	$this->load->helper('form');
	$this->_section = USERSECTION;
  }


	public function getLoadingPlantCement()
	{
				$sql = " select distinct  plantcd,plantnm_th from tbm_syst_plant where plantcd not in ('1044','1046') and    plantcd  in ('".str_replace(",","','",$this->_section )."') ";
				$data= array();
				$data= $this->db_api->getresult($sql);
	 		    echo json_encode($data);
		}

	public function getLoadingPlant()
	{
				$sql = " select distinct  plantcd,plantnm_th from tbm_syst_plant where plantcd not in ('1046') and    plantcd  in ('".str_replace(",","','",$this->_section )."') ";
				$data= array();
				$data= $this->db_api->getresult($sql);
	 		    echo json_encode($data);
		}

	public function getPackingPlant()
	{
				$total_plant = $this->input->post('total_plant');
				//$total_plant ="1";
				$sql = " select distinct  plantcd,plantnm_th from tbm_syst_plant where  plantcd  in ('".str_replace(",","','",$this->_section )."')  ";
				if($total_plant=="1")
				$sql .= " union select   '1000' as plantcd, 'Total' as plantnm_th  ";
				$data= array();
				$data= $this->db_api->getresult($sql);
	 		    echo json_encode($data);
	}
	public function getPlant4EasyUI()
	{
				
		 $q = isset($_POST['q']) ? strval($_POST['q']) : '';
		 $sql_q="";
		 
		$sql = " select distinct  plantcd,plantnm_th from tbm_syst_plant  where isnull(plantnm_th,'')<>''   ";

		if ( $q <>"")
		{
			$sql_q  .=$sql." and ( plantcd like '%".iconv('utf-8','tis620',$q)."%' )  union "  ;
			$sql_q .=$sql."  and ( plantnm_th like '%".iconv('utf-8','tis620',$q)."%'    )    "  ;
			
		}else
		{
				$sql_q =$sql;
			}
		$data= array();
		$data= $this->db_api->getresult($sql_q);
	   echo json_encode($data);
		
	}
	
	function GetPlantFilter()
	{
			 $data=array();
			 $itemcd =$this->input->post('itemcd');
			 $sql =" select plantcd,plantnm_th from tbm_syst_plant where plantnm_th is not null and
			   plantcd not in ('1046') and   plantcd in ('".str_replace(",","','",$this->_section )."')    "; 	 
			 $data= $this->db_api->getresult($sql);	
			 echo json_encode($data );		
			
		}	

	}

?>