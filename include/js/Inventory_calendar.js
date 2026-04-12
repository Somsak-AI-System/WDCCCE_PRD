/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

jQuery(document).ready(function() { 
	//jQuery('#time_start').timespinner('disable');
	jQuery('#time_start').timespinner({
	  onChange: function(value){
	  	var date_start = jQuery("input[name='date_start']").val();
	  	var Asplit_start = date_start.split('-');
		var start_date_f = new Date(Asplit_start[1]+"/"+Asplit_start[0]+"/"+Asplit_start[2]+" "+value); 
		// console.log(start_date_f);

		var end_time = jQuery("input[name='time_end']").val();
		var due_date = jQuery("input[name='due_date']").val();
	 	var Asplit_end = due_date.split('-');
		var end_date_f = new Date(Asplit_end[1]+"/"+Asplit_end[0]+"/"+Asplit_end[2]+" "+end_time); 
	 	// console.log(end_date_f);

	 	if(end_date_f < start_date_f){
	 		jQuery.messager.alert('Message','End time is more than the start time.','warning');
	 		{	
	 			jQuery('#time_start').timespinner('setValue', end_time);
                ///jQuery('#time_start').timespinner({min:-end_time,on:end_time});
                //jQuery('input[name="time_start"]').val(end_time);
                return false;
			}

            // jQuery('#time_start').find('input[name="time_start"]').val(end_time);
            // jQuery('input[name="time_start"]').prop("value",end_time);
	 		// jQuery('input[name="time_start"]').val(end_time);
	 		// return false;
	 	}
	  }
	});

	jQuery('#time_end').timespinner({
        onChange: function(value){

            var start_time = jQuery("input[name='time_start']").val();
            var date_start = jQuery("input[name='date_start']").val();
            var Asplit_start = date_start.split('-');
            var start_date_f = new Date(Asplit_start[1]+"/"+Asplit_start[0]+"/"+Asplit_start[2]+" "+start_time);
            // console.log(start_date_f);

            var end_time = jQuery("input[name='time_end']").val();
            var due_date = jQuery("input[name='due_date']").val();
            var Asplit_end = due_date.split('-');
            var end_date_f = new Date(Asplit_end[1]+"/"+Asplit_end[0]+"/"+Asplit_end[2]+" "+value);
            
            if(end_date_f < start_date_f){
                jQuery.messager.alert('Message','End time is more than the start time.','warning');
                {
                    //jQuery('#time_end').timespinner({min:start_time});
                    //jQuery('input[name="time_end"]').val(start_time);
                    jQuery('#time_end').timespinner('setValue', start_time);
                    return false;
                   
                }

                // jQuery('#time_start').find('input[name="time_start"]').val(end_time);
                // jQuery('input[name="time_start"]').prop("value",end_time);
                // jQuery('input[name="time_start"]').val(end_time);
                // return false;
            }
        }
	});
	
	jQuery('input[name="date_start"]').change(function() {
		var checkInDate =jQuery(this).val();
		var start_date = checkInDate; 
		var Asplit_start = start_date.split('-');
		var start_date_f = new Date(Asplit_start[2],Asplit_start[0],Asplit_start[1]); 
		 
		if(Asplit_start[0].length == 2){
		
			var start_date_f = Asplit_start[0]+'-'+Asplit_start[1]+'-'+Asplit_start[2];
			jQuery('input[name="date_start"]').val(start_date_f);
			jQuery('input[name="due_date"]').val(start_date_f);
		}else{
			alert ('Date format is incorrect (dd-mm-yyyy)');
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth();
			var yyyy = today.getFullYear();
			today = dd+'-'+mm+'-'+yyyy;
			
			jQuery('input[name="date_start"]').val(today);
			jQuery('input[name="due_date"]').val(today);
		}
	 }); 
	   
	  
})

function date_start(dateText,selectedDate){
		
	var checkInDate =selectedDate;
	 var Asplit_start = checkInDate.split('-');
	var start_date_f = new Date(Asplit_start[2],Asplit_start[1],Asplit_start[0]+1); 
	 var start_date_f = Asplit_start[0]+'-'+Asplit_start[1]+'-'+Asplit_start[2];
	jQuery('input[name="date_start"]').val(start_date_f);
	jQuery('input[name="due_date"]').val(start_date_f);
		
}

