<?php
class omnireport_model extends CI_Model { 
	var $my_server;  
 	
 	function get_message($startdate=NULL,$enddate=NULL){
		
		$sql = "select 
			message_chathistory.message ,
			message_chathistory.message_type , 
			message_customer.socialname ,
			DATE_FORMAT(message_chathistory.messagetime,'%d-%m-%Y %H:%i:%s') as messagetime 
			from message_chathistory
			inner join message_customer on message_customer.customerid =  message_chathistory.customerid
			where DATE_FORMAT(message_chathistory.messagetime,'%Y-%m-%d') >= '".$startdate."' and  DATE_FORMAT(message_chathistory.messagetime,'%Y-%m-%d') <= '".$enddate."'
			and message_chathistory.messageaction = 'customer' order by message_chathistory.messagetime ASC";

		$query = $this->db->query($sql);
		
		$result = $query->result(0);
		return $result;
	}

}

?>