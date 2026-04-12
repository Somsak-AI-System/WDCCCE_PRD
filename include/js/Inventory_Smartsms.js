/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
jQuery(document).ready(function() {
	
		jQuery('textarea[name="sms_message"]').keyup(function() {
			var Credit  = 0;
			
			 var elem = document.getElementById('sms_message').value;
			 
				  if(elem.match(/^([a-z0-9\_\[\]\//\\.\%\&\*\(\)\+\?\{\}\ \n\^\!\@\#\$\-\:\,\|\;\=\/'\/"\<\>])+$/i))
				  {		
				  		var countText=elem.length;
					    jQuery('input[name="sms_character"]').val(countText);
					    if(countText <= 160 && countText != 0){							
								jQuery('input[name="sms_credit"]').val(1);
						}else if(countText == 0){
								jQuery('input[name="sms_credit"]').val(0);
						}else{
							Credit =Math.ceil(countText / 157);
							jQuery('input[name="sms_credit"]').val(Credit);
						}
						
				  }else{
					    
						var countText=elem.length;
					    jQuery('input[name="sms_character"]').val(countText);						
						if(countText <= 70 && countText != 0){
								jQuery('input[name="sms_credit"]').val(1);
						}else if(countText == 0){
								jQuery('input[name="sms_credit"]').val(0);
						}else{
							Credit = Math.ceil(countText / 67);
							jQuery('input[name="sms_credit"]').val(Credit);
						}
						
				  }		    
		});
		
});//ready