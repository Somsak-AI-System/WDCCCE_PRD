jQuery(document).ready(function() {
	show_hide_block_case();

	jQuery("select[name='case_personname']").change(function() {
		var username = jQuery(this).val();
		get_user_info(username,"case_personname");
	});
	
	
	jQuery("select[name='ticket_type']").change(function() {
		 show_hide_block_case();
	});

});

function show_hide_block_case()
{
	
	var casetype =  jQuery("select[name='ticket_type']").val();
	if(typeof casetype === "undefined"){
		casetype =  jQuery("#case_type_detailview").val();
	}
		
	//alert(casetype);
	//console.log(casetype);
	jQuery(".case-tr-request").css({ display: "none" }); 
	jQuery(".case-tr-service").css({ display: "none" }); 
	jQuery(".case-tr-complain").css({ display: "none" }); 
	
	if(casetype=='ร้องขอสนับสนุน'){
		jQuery(".case-tr-request").css({ display: "table-row"  }); 
	}
	
	if(casetype=='ร้องเรียน'){
		jQuery(".case-tr-complain").css({ display: "table-row"  }); 
	}
	if(casetype!='ร้องเรียน' && casetype!='ร้องขอสนับสนุน' && casetype!='--None--'){
		jQuery(".case-tr-service").css({ display: "table-row"  }); 
	}
}

function get_user_info(username,fieldname)
{
	 jQuery.ajax({
		type: "POST",
		url: "ajax/get_data.php",
        cache: false,
		dataType: 'json',
		async: false,
		data:  {
			"username": username,
			"mod":"get_user"
		},
        success: function(data, textStatus, jqXHR)
        {
        	var username = data.username;
        	var userarea = data.userarea;
        	jQuery("input[name='case_personname']").val(username);
        	jQuery("input[name='case_personno']").val(userarea);
        	
       },
       error: function(jqXHR, textStatus, errorThrown)
       {
    	   alert(textStatus);
        }
	});

}