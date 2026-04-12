<?php
function get_ldap($ldap_url,$ldap_domain,$ldap_dn,$usr,$pwd){
         $ret = false;        
         # Ldap Parameters 
         $ldapconfig['host'] =$ldap_url; 
         $ldapconfig['port'] = 389; 
         $ldapconfig['basedn'] =$ldap_dn;      
         # Connect & Search 
         $ds=@ldap_connect($ldapconfig['host'],$ldapconfig['port']);      
         ldap_set_option
($ds,LDAP_OPT_PROTOCOL_VERSION,3); 
         $r = @ldap_search($ds, $ldapconfig['basedn'],'uid='.$usr); 
         $result = @ldap_get_entries( $ds, $r); 
         if ($result["count"] >0){ 
               if (@ldap_bind( $ds, $result[0]['dn'],$pwd)){
				   return $result; 
			   }
         } 
         ldap_unbind($ds);      
         return "";
}
function get_ldap1($ldap_url,$ldap_domain,$ldap_dn,$username,$password){
	$ds = ldap_connect( $ldap_url );
	ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
	ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
	try{
		$login = ldap_bind( $ds, "$username@$ldap_domain", $password);
	}catch(Exception $e){
		return false;
	}
	if($login){
		try{
			$attributes = array("displayname", "mail","department","title","physicaldeliveryofficename","manager");
			$filter = "(&(objectCategory=person)(sAMAccountName=$username))";
			$result = ldap_search($ds, $ldap_dn, $filter, $attributes);
			$entries = ldap_get_entries($ds, $result);
			//print_r($entries);
			if($entries["count"] > 0){
				return $entries;
				//echo "<b>User Information:</b><br/>";
				//echo "User Name: ".$entries[0]['displayname'][0]."<br/>";
				//echo "Email: ".$entries[0]['mail'][0]."<br/>";
				//echo "department: ".$entries[0]['department'][0]."<br/>";
				//echo "title: ".$entries[0]['title'][0]."<br/>";
				//echo "office: ".$entries[0]['physicaldeliveryofficename'][0]."<br/>";
				//echo "manager: ".$entries[$i]['manager'][0]."<br/>";
				//$manager_result = ldap_search($ds,$entries[0]['manager'][0],'(objectCategory=person)',array("displayname"));
				//$manager_entries = ldap_get_entries($ds, $manager_result);
				//if($manager_entries["count"] > 0){
				//	echo "manager: ". $manager_entries[0]['displayname'][0];
				//}
			}
		}catch(Exception $e){
			ldap_unbind($ds);
			return "";
		}
	}else{
		ldap_unbind($ds);
		return "";
	}	
}
?> 