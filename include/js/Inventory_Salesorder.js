
jQuery(document).ready(function() {
	show_hide_discount();
	show_hide_tax();

	chage_format_quotes();
	
	//set_tax_manual();

	jQuery("#proTab").on("change",".qty",function() {
		var obj = jQuery(this);
		console.log(obj);
	});

	//ประเภทใบเสนอราคา
	jQuery("select[name='pricetype']").change(function() {
		var quotestype = jQuery(this).val();
		change_vat();
		chage_format_quotes();
		//change_checkbox();
		calcTotal();
		set_tax_manual();
		//calcGrandTotal();
	});

	jQuery("select[name='quota_signa']").change(function() {
		var fullname = jQuery(this).val();
		get_user_info(fullname,"quota_signa");
	});

	jQuery("select[name='quota1']").change(function() {
		var username = jQuery(this).val();
		get_user_info(username,"quota1");
	});


});

function change_checkbox()
{
	var quotestype =  jQuery("select[name='pricetype']").val();
	if(quotestype=="Exclude Vat") {
		jQuery('input[name="cf_4352"]').prop('checked', false);
	}else {
		jQuery('input[name="cf_4352"]').prop('checked', true);
	}
}

function chage_format_quotes()
{
	var quotestype = jQuery("select[name='pricetype']").val();
	var quotesformat = jQuery("select[name='quota_form']").val();
	var mode = jQuery("input[name='mode']").val();
	var duplicate_from = jQuery("input[name='duplicate_from']").val();
	//var option = jQuery('<option></option>').attr("value", "option value").text("Text");
	//console.log(option);
	//jQuery("select[name='quota_form']").empty().append(option);
	
	if(quotestype=="Exclude Vat")
	{
		var newOptions = {
            "ใบเสนอราคาแบบไม่มี VAT (TH)": "ใบเสนอราคาแบบไม่มี VAT (TH)",
            "ใบเสนอราคาแบบไม่มี VAT (EN)": "ใบเสนอราคาแบบไม่มี VAT (EN)",
		};
	}
	else
	{
		var newOptions = {
            "ใบเสนอราคาแบบมี VAT (TH)": "ใบเสนอราคาแบบมี VAT (TH)",
            "ใบเสนอราคาแบบมี VAT (EN)": "ใบเสนอราคาแบบมี VAT (EN)",
		};
	
	}

	var $el = jQuery("select[name='quota_form']");
	//console.log(quotesformat);
	$el.empty(); // remove old options
	jQuery.each(newOptions, function(key,value) {
	  $el.append(jQuery("<option></option>").attr("value", value).text(key));
	});
	$el.val(quotesformat);
	
	console.log(mode);
	console.log(duplicate_from);

	if(mode === '' && duplicate_from === ''){//Action '' = create / edit = Edit
		if(quotestype=="Exclude Vat"){
		  	//jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบไม่มี VAT (TH)']").attr("selected",true);
		  	jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบไม่มี VAT (TH)']").prop('selected',true);
		  	//jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบไม่มี VAT (TH)']").attr('selected', 'selected');
		  }else{
		  	jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบมี VAT (TH)']").prop('selected',true);
		  	//jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบมี VAT (TH)']").attr('selected', 'selected');
		}
	}

	/*if(mode === 'edit' && duplicate_from === ''){//Action '' = create / edit = Edit
		if(quotestype=="Exclude Vat"){
		  	//jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบไม่มี VAT (TH)']").attr("selected",true);
		  	jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบไม่มี VAT (TH)']").prop('selected',true);
		  	//jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบไม่มี VAT (TH)']").attr('selected', 'selected');
		  }else{
		  	jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบมี VAT (TH)']").prop('selected',true);
		  	//jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบมี VAT (TH)']").attr('selected', 'selected');
		}
	}*/

}


function chage_format_quotes_edit()
{
	var quotestype = jQuery("select[name='pricetype']").val();
	var quotesformat = jQuery("select[name='quota_form']").val();
	var mode = jQuery("input[name='mode']").val();
	var duplicate_from = jQuery("input[name='duplicate_from']").val();
		
	if(quotestype=="Exclude Vat")
	{
		var newOptions = {
            "ใบเสนอราคาแบบไม่มี VAT (TH)": "ใบเสนอราคาแบบไม่มี VAT (TH)",
            "ใบเสนอราคาแบบไม่มี VAT (EN)": "ใบเสนอราคาแบบไม่มี VAT (EN)",
		};
	}
	else
	{
		var newOptions = {
            "ใบเสนอราคาแบบมี VAT (TH)": "ใบเสนอราคาแบบมี VAT (TH)",
            "ใบเสนอราคาแบบมี VAT (EN)": "ใบเสนอราคาแบบมี VAT (EN)",
		};
	
	}

	var $el = jQuery("select[name='quota_form']");
	//console.log(quotesformat);
	$el.empty(); // remove old options
	jQuery.each(newOptions, function(key,value) {
	  $el.append(jQuery("<option></option>").attr("value", value).text(key));
	});
	$el.val(quotesformat);
	
	
	if(quotestype=="Exclude Vat"){
	  	//jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบไม่มี VAT (TH)']").attr("selected",true);
	  	jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบไม่มี VAT (TH)']").prop('selected',true);
	  	//jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบไม่มี VAT (TH)']").attr('selected', 'selected');
	  }else{
	  	jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบมี VAT (TH)']").prop('selected',true);
	  	//jQuery("select[name='quota_form'] option[value='ใบเสนอราคาแบบมี VAT (TH)']").attr('selected', 'selected');
	}
	
}

function change_vat()
{
	/*var quotestype =  jQuery("select[name='pricetype']").val();
	//listPrice , listprice_inc, listprice_exc
	jQuery(".listPrice").each(function(){
		rowno = jQuery(this).data("rowno");
		var listprice_inc = jQuery('#listprice_inc'+rowno).val();
		var listprice_exc = jQuery('#listprice_exc'+rowno).val();
		
		if(quotestype=="Exclude Vat"){
			var listPrice = isNaN(listprice_exc = parseFloat(listprice_exc)) ? 0:parseFloat(listprice_exc);
		}else{
			var listPrice = isNaN(listprice_inc = parseFloat(listprice_inc)) ? 0:parseFloat(listprice_inc);
		}
		jQuery(this).val(listPrice.toFixed(2));

	});*/
	show_hide_discount();
	show_hide_tax();
}

function show_hide_discount()
{
	//if(jQuery("input[type='checkbox'][name='cf_4126']").is(":checked")) {
         jQuery("#div_discount").css({ display: "table-row" });
		 jQuery("#div_totalafterdiscount").css({ display: "table-row" });
    /*}else{
	 	jQuery("#div_discount").css({ display: "none" });
		jQuery("#div_totalafterdiscount").css({ display: "none" });
		reset_discount();
		var radiodiscount = jQuery("input[type='radio'][name='discount_final']")[0];
		setDiscount(radiodiscount,'_final');
		calcGroupTax();
		calcTotal();
	}*/

}

function show_hide_tax()
{
	//if(jQuery("input[type='checkbox'][name='cf_4052']").is(":checked")) {
         jQuery("#group_tax_row").css({ display: "table-row" });
		 set_tax();
		 calcTotal();
  /*  }else{
	 	jQuery("#group_tax_row").css({ display: "none" });
		reset_tax();
		calcTotal();
	}*/

}

function reset_discount()
{
	jQuery("input[type='radio'][name='discount_final']")[0].checked = true;
}

function reset_tax()
{
	jQuery("#group_tax_percentage1").val('0');
	jQuery("#group_tax_amount1").val('0');
}

function set_tax()
{
	var tax_Val = jQuery("#group_tax_percentage_df1").val();
	jQuery("#group_tax_percentage1").val(tax_Val);
}

function get_user_info(fullname,fieldname)
{
	 jQuery.ajax({
		type: "POST",
		url: "get_data.php",
        cache: false,
		dataType: 'json',
		async: false,
		data:  {
			"fullname": fullname,
			"mod":"get_user"
		},
        success: function(data, textStatus, jqXHR)
        {
        	var username = data.username;
        	var usertel = data.usertel;
        	var userposition = data.userposition;
        	var email = data.email;
        	
        	if(fieldname=='quota_signa'){
                
                jQuery("input[name='quota_signa_position']").val(userposition);

        	}else if(fieldname=='quota1'){
        		jQuery("input[name='quota1_phone']").val(usertel);
        		jQuery("input[name='quota1_email']").val(email);
        	}
        	
       },
       error: function(jqXHR, textStatus, errorThrown)
       {
    	   alert(textStatus);
        }
	});

}