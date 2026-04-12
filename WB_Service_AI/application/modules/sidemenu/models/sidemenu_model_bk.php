<?php
class sidemenu_model extends CI_Model
{
  var $ci;

  function __construct()
  {
    parent::__construct();
    $this->ci = & get_instance();
	$this->load->library("common");
    $this->_limit = "10";
  }




//   function get_list($a_condition=array(),$a_order=array(),$a_limit=array(),$a_conditionin=array())
  function get_menu($a_params=array())
  {

		try{
			if($a_params['userid']!=''){

			$querymenu ="SELECT * FROM  aicrm_usertabmenu WHERE aicrm_usertabmenu.userid = '".$a_params['userid']."' ";

			$check_menu = $this->db->query($querymenu);
			$a_resultmenu  = $check_menu->result_array() ;

			if(!empty($a_resultmenu)){
      //  // $querymenu = "SELECT aicrm_tab.tablabel,aicrm_tab.tabid,aicrm_usertabmenu.mobile_seq FROM aicrm_users
      //  // INNER JOIN aicrm_usertabmenu ON aicrm_usertabmenu.tabid = aicrm_tab.tabid
      //  // AND aicrm_tab.presence=0
      //  // 	ORDER BY aicrm_usertabmenu.mobile_seq ASC
				// $querymenu = "SELECT aicrm_tab.tablabel,aicrm_tab.tabid,aicrm_usertabmenu.mobile_seq,aicrm_tab.presence FROM aicrm_users
				// INNER JOIN aicrm_user2role ON aicrm_users.id =aicrm_user2role.userid
				// INNER JOIN aicrm_role2profile ON aicrm_role2profile.roleid = aicrm_user2role.roleid
				// INNER JOIN aicrm_profile2tab ON aicrm_profile2tab.profileid = aicrm_role2profile.profileid
				// INNER JOIN aicrm_tab ON aicrm_tab.tabid = aicrm_profile2tab.tabid
				// LEFT JOIN aicrm_usertabmenu ON aicrm_usertabmenu.tabid = aicrm_tab.tabid
				// WHERE aicrm_users.id = '".$a_params['userid']."' AND aicrm_tab.mobile_seq!=0
				// ORDER BY aicrm_usertabmenu.mobile_seq ASC ";
        $querymenu = "SELECT aicrm_tab.tablabel,aicrm_tab.tabid,  aicrm_usertabmenu.mobile_seq,aicrm_tab.presence  from aicrm_tab
        LEFT JOIN aicrm_usertabmenu ON aicrm_usertabmenu.tabid = aicrm_tab.tabid
        WHERE aicrm_usertabmenu.userid = '".$a_params['userid']."' AND aicrm_tab.mobile_seq!=0
        ORDER BY aicrm_usertabmenu.mobile_seq ASC ";
					$check_menu = $this->db->query($querymenu);
					$a_result  = $check_menu->result_array() ;

			}else{
        //  AND aicrm_tab.presence = 0
					$querymenu = "SELECT aicrm_tab.tabid,aicrm_tab.tablabel,aicrm_tab.mobile_seq,aicrm_tab.presence FROM aicrm_users
					INNER JOIN aicrm_user2role ON aicrm_users.id =aicrm_user2role.userid
					INNER JOIN aicrm_role2profile ON aicrm_role2profile.roleid = aicrm_user2role.roleid
					INNER JOIN aicrm_profile2tab ON aicrm_profile2tab.profileid = aicrm_role2profile.profileid
					INNER JOIN aicrm_tab ON aicrm_tab.tabid = aicrm_profile2tab.tabid
					WHERE aicrm_users.id = '".$a_params['userid']."' AND aicrm_tab.mobile_seq!=0
					ORDER BY aicrm_tab.mobile_seq ASC ";

						$check_menu = $this->db->query($querymenu);
						$a_result  = $check_menu->result_array() ;



			}

			//fix
			$fix_deal = array(
				'tabid' => "111",
				'tablabel' => "Deal",
				'mobile_seq' => "5",
				'presence' => "1"
				 );

			$a_result[] = $fix_deal;

			//alert($a_result);exit;
			// end fix

  		if(!$querymenu){
			$a_return["status"] = false;
			$a_return["error"] = $this->db->_error_message();
			$a_return["result"] = "";
			}else{

		  if (!empty($a_result)) {
				$a_return["status"] = true;
        		$a_return["error"] =  "";
				$a_return["result"] = $a_result;
			}else{
				$a_return["status"] = false;
				$a_return["error"] =  "No Data";
				$a_return["result"] = "";
			}
		}
		}else{
			$a_return["status"] = false;
			$a_return["error"] =  "No Data";
			$a_return["result"] = "";
		}
		}catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		  }
		return $a_return;
	}



	function update_tabmenu($a_params=array()){
  	$userid=isset($a_params['userid']) ? $a_params['userid'] : "";
		$data=isset($a_params['data']) ? $a_params['data'] : "";
		try{
					$chk=0;
			if($userid!=''){

				$querymenu ="SELECT * FROM  aicrm_usertabmenu WHERE aicrm_usertabmenu.userid = '".$userid."' ";

				$check_menu = $this->db->query($querymenu);
				$a_resultmenu  = $check_menu->result_array() ;


				if($a_resultmenu!=null){
					if($data!=""){
						$query = "INSERT INTO aicrm_usertabmenu (userid, tabid, mobile_seq, deleted) VALUES ";
						foreach($data as $key => $val){
							$tabid = $val['tabid'];
							$mobile_seq = $val['mobile_seq'];
							if($key == 0){
								$query .= "('".$userid."', '".$tabid."', '".$mobile_seq."','1')";
							}else{
								$query .= ",('".$userid."', '".$tabid."', '".$mobile_seq."','1')";
							}

						}

						if($this->ci->db->query($query)){
							$queryDelete = "SELECT * FROM  aicrm_usertabmenu WHERE aicrm_usertabmenu.userid = '".$userid."'
							 AND aicrm_usertabmenu.deleted = 0 ";

							$check_menu = $this->db->query($queryDelete);
							$a_result1  = $check_menu->result_array() ;
								if($a_result1!=null){
									$queryDeleteOld  = "DELETE FROM aicrm_usertabmenu WHERE aicrm_usertabmenu.userid = '".$userid."' AND aicrm_usertabmenu.deleted=0";
									if($this->ci->db->query($queryDeleteOld)){
													$queryUpdate  = "UPDATE aicrm_usertabmenu SET deleted = 0 WHERE aicrm_usertabmenu.userid = '".$userid."'";
													if($this->ci->db->query($queryUpdate)){
														$chk = 0;
													}else{
														$chk=1;
													}
									}else{
										$chk=1;
									}
								}

							// DELETE FROM `aicrm_usertabmenu` WHERE deleted=0
						}else{
							$chk=1;
						}

					}


				}else{
					if($data!=""){
						$query = "INSERT INTO aicrm_usertabmenu (userid, tabid, mobile_seq, deleted) VALUES ";
						foreach($data as $key => $val){
							$tabid = $val['tabid'];
							$mobile_seq = $val['mobile_seq'];
							if($key == 0){
								$query .= "('".$userid."', '".$tabid."', '".$mobile_seq."','0')";
							}else{
								$query .= ",('".$userid."', '".$tabid."', '".$mobile_seq."','0')";
							}

						}

						if($this->ci->db->query($query)){
						}else{
							$chk=1;
						}

					}

				}

  		if($chk==1){
			$a_return["status"] = false;
			$a_return["error"] = $this->db->_error_message();
			$a_return["result"] = "";
			}else{

		  if ($chk==0) {
				$a_return["status"] = true;
				$a_return["error"] =  "";
				$a_return["result"] = 'Insert Success';
			}else{
				$a_return["status"] = false;
				$a_return["error"] =  "No Data";
				$a_return["result"] = "";
			}
		}
		}else{
			$a_return["status"] = false;
			$a_return["error"] =  "No Data";
			$a_return["result"] = "";
		}
		}catch (Exception $e) {
			$a_return["status"] = false;
			$a_return["error"] =  $e->getMessage();
			$a_return["result"] = "";
		  }
		return $a_return;

	}



}
