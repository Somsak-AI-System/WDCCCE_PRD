<?
class users extends MX_Controller {
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
  }



	public function getUserpacking()
	{
			
		$roleid=$this->input->post('roleid');
		 $q = isset($_POST['q']) ? strval($_POST['q']) : '';
		 $sql_q="";
		 
		$sql = "  select usr.user_name, first_name+' ' +ISNULL(last_name,'') as name from tbm_syst_users usr
					inner join tbm_syst_user2role roles on usr.id=roles.userid 
					where roleid is not null
					
  				
  			";
		if ($roleid <>"")
		{
			$sql .=  " and   roleid in 	('". str_replace(",","','",$roleid)."')";
			
			}
		if ( $q <>"")
		{
			$sql_q  .=$sql." and ( user_name like '%".iconv('utf-8','tis620',$q)."%' )  union "  ;
			$sql_q .=$sql."  and ( (first_name + ' '+last_name) like '%".iconv('utf-8','tis620',$q)."%'    )   "  ;
		
		}else
		{
				$sql_q =$sql;
			}
		//	$sql_q =$sql;
		$data= array();
		$data= $this->db_api->getresult($sql_q);
	   echo json_encode($data);
		
	}



	}

?>