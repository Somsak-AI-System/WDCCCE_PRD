/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
jQuery(document).ready(function() {

	jQuery("select[name='jobtype']").change(function() {
		var jobtype = jQuery(this).val();
		jQuery("#job_name").val(jobtype);

	});

	jQuery('input[name="jobdate_operate"]').change(function() {
		var checkInDate =jQuery(this).val();
		
		var start_date = checkInDate; 
		var Asplit_start = start_date.split('-');
		var start_date_f = new Date(Asplit_start[2],Asplit_start[1],Asplit_start[0]); 
		
		var stop_date = jQuery('input[name="close_date"]').val();
		var Asplit_stop = stop_date.split('-');
		var stop_date_f = new Date(Asplit_stop[2],Asplit_stop[1],Asplit_stop[0]); 		
		
		if(stop_date != ''){
			if(start_date_f > stop_date_f){
				jQuery.messager.alert('Message','วันที่เข้าดำเนินการ มากกว่าวันที่ปิด Case','info');
				jQuery('input[name="jobdate_operate"]').val('');
			}else{
				jQuery('input[name="jobdate_operate"]').val(start_date)
			}
		}else{
		        jQuery('input[name="jobdate_operate"]').val(start_date)
		}
					
	 }); 
	   
	   
	  jQuery('input[name="close_date"]').change(function() {
		  var checkInDate =jQuery(this).val();
		   
		  var stop_date = checkInDate; 
		  var Asplit_stop = stop_date.split('-');
		  var stop_date_f = new Date(Asplit_stop[2],Asplit_stop[1],Asplit_stop[0]); 

		  var start_date = jQuery('input[name="jobdate_operate"]').val(); 
		  var Asplit_start = start_date.split('-');
		  var start_date_f = new Date(Asplit_start[2],Asplit_start[1],Asplit_start[0]); 
		  
			if(start_date != '' ){
				
				if(start_date_f > stop_date_f){
					jQuery.messager.alert('Message','วันที่ปิด Case ไม่ถูกต้อง','info');
					jQuery('input[name="close_date"]').val('');
				}else{
					jQuery('input[name="close_date"]').val(stop_date)
				}
				
			}else{
				jQuery('input[name="close_date"]').val(stop_date)
			}
			
	});
});


//Start Date 
function jobdate_operate(dateText,selectedDate){
		
	var checkInDate =selectedDate;
	var Asplit_S = checkInDate.split('-');
	var start_date_s = new Date(Asplit_S[2],Asplit_S[1],Asplit_S[0]); 
	
	var stop_date = jQuery('input[name="close_date"]').val();
	var Asplit_E = stop_date.split('-');
	var start_date_f = new Date(Asplit_E[2],Asplit_E[1],Asplit_E[0]); 

	if(stop_date != '' ){
		
		if(start_date_f < start_date_s){
			jQuery.messager.alert('Message','วันที่เข้าดำเนินการ มากกว่าวันที่ปิด Case','info');
		}else{
			jQuery('input[name="jobdate_operate"]').val(checkInDate)
		}
		
	}else{
		jQuery('input[name="jobdate_operate"]').val(checkInDate)
	}
		
}
//Stop Date 
function close_date(dateText,selectedDate){
	
	var checkInDate =selectedDate;
	var Asplit_E = checkInDate.split('-');
	var start_date_f = new Date(Asplit_E[2],Asplit_E[1],Asplit_E[0]); 

	var start_date = jQuery('input[name="jobdate_operate"]').val();
	var Asplit_S = start_date.split('-');
	var start_date_s = new Date(Asplit_S[2],Asplit_S[1],Asplit_S[0]); 

	if(start_date != '' ){
		
		if(start_date_s > start_date_f){
			jQuery.messager.alert('Message','วันที่ปิด Case ไม่ถูกต้อง','info');
		}else{
			jQuery('input[name="close_date"]').val(checkInDate)
		}
		
	}else{
		jQuery('input[name="close_date"]').val(checkInDate)
	}

}

