/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
 document.write("<script type='text/javascript' src='modules/Accounts/multifile.js'></"+"script>");
 document.write("<script type='text/javascript' src='include/js/Inventory.js'></"+"script>");
 document.write("<script type='text/javascript' src='include/js/Mail.js'></"+"script>");
 document.write("<script type='text/javascript' src='include/js/Merge.js'></"+"script>");

 document.write("<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyA75RDRnrE_Oq1geJ02Ta4I3WVdCCgHYbQ&callback=myMap&libraries=places&v=3.53&channel=2' defer></script>");

 var map;
 var markers = [];

 function myMap() {
 	var myLatLng = {lat: 13.7248936, lng: 100.4930264}; 	
 	var geocoder = new google.maps.Geocoder();
    // Create the search box and link it to the UI element.
    var input = document.getElementById("addressmap");
    var searchBox = new google.maps.places.SearchBox(input);
    
    let markers = [];
    
    var mode = jQuery('#mode').val();
    if(mode == 'edit'){
    	display_map();
    } else {
		jQuery('#mapview').prop('src', `https://maps.google.com/maps?width=600&height=400&hl=en&q=13.7248936,100.4930264&t=&z=14&ie=UTF8&iwloc=B&output=embed`);
	}
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener("places_changed", () => {
    	// initMap();
    	var places = searchBox.getPlaces();
    	if (places.length == 0) {
    		return;
    	}
    	let markers = [];
        // Clear out the old markers.
        markers.forEach((marker) => {
        	marker.setMap(null);
        });

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        
        places.forEach((place) => {
        	if (!place.geometry || !place.geometry.location) {
        		console.log("Returned place contains no geometry");
        		return;
        	}

        	s_latlong = place.geometry.location.lat()+','+place.geometry.location.lng();
        	jQuery('#mapview').prop('src', `https://maps.google.com/maps?width=600&height=400&hl=en&q=${s_latlong}&t=&z=14&ie=UTF8&iwloc=B&output=embed`);
			jQuery('#latlong').val(s_latlong);
        });
        // map.fitBounds(bounds);
    });

}

function initMap() {
	var myLatLng = {lat: 13.7248936, lng: 100.4930264};
	map = new google.maps.Map(document.getElementById("mapview"), {
		zoom: 12,
		center: myLatLng,
		disableDefaultUI: false,
		mapTypeControl:false,
		streetViewControl:false,
		fullscreenControl: false,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: false,
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.DEFAULT,
		}
	});
};

function display_map() {
	var d_latlong = jQuery('#latlong').val();

	if(d_latlong != ''){
		var lat_long = d_latlong.split(",");
		var myLatLng = { lat: Number(lat_long[0]), lng: Number(lat_long[1]) };
		console.log(myLatLng);
		map = new google.maps.Map(document.getElementById("mapview"), {
			zoom: 12,
			center: myLatLng,
			disableDefaultUI: false,
			mapTypeControl:false,
			streetViewControl:false,
			fullscreenControl: false,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: false,
			mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.DEFAULT,
			}
		});
	    //var markers
	    new google.maps.Marker({
	    	position: myLatLng,
	    	map,
	    	title: "",
	    });
	}
	
};


function set_return(product_id, product_name) {
	if(document.getElementById('from_link').value != '') {
		window.opener.document.QcEditView.parent_name.value = product_name;
		window.opener.document.QcEditView.parent_id.value = product_id;
	} else {
		window.opener.document.EditView.parent_name.value = product_name;
		window.opener.document.EditView.parent_id.value = product_id;
	}
}

function set_return_code(product_id, product_name,id_card) {
	if(document.getElementById('from_link').value != '') {
		window.opener.document.QcEditView.parent_name.value = product_name;
		window.opener.document.QcEditView.parent_id.value = product_id;
		window.opener.document.QcEditView.cf_1257.value = id_card;
	} else {
		window.opener.document.EditView.parent_name.value = product_name;
		window.opener.document.EditView.parent_id.value = product_id;
		window.opener.document.EditView.cf_1257.value = id_card;
	}
}

function set_return_specific_accountcode(account_id,account_code_rms, account_name,fieldid,mobile){

	formName = 'EditView';
	var form = window.opener.document.forms[formName];
	//var fieldcode = fieldid+"_code" ;
	var fieldname = fieldid+"_name" ;

	/*if(typeof(form[fieldcode]) != 'undefined')
		form[fieldcode].value = account_code_rms;*/
	if(typeof(form[fieldid]) != 'undefined')
		form[fieldid].value = account_id;
	if(typeof(form[fieldname]) != 'undefined')
		form[fieldname].value = account_name;

    if(typeof(form.account_name) != 'undefined')
        form.account_name.value = account_name;
    if(typeof(form.account_id) != 'undefined')
        form.account_id.value = account_id;


	if(fieldid == 'account_id1'){
		if(typeof(form[fieldname]) != 'undefined')
			form.owner_mobile.value = mobile;
	}else if(fieldid == 'account_id2'){
		if(typeof(form[fieldname]) != 'undefined')
			form.dealer_mobile.value = mobile;
	}

}

function set_return_specific_accountfield(account_id, account_name,fieldid

	) {

	formName = 'EditView';
	var form = window.opener.document.forms[formName];
	var fieldname = fieldid+"_name" ;

	if(typeof(form[fieldname]) != 'undefined')
		form[fieldname].value = account_name;
	if(typeof(form[fieldid]) != 'undefined')
		form[fieldid].value = account_id;

}

function set_return_specific(accountid,account_no,formName,accountname,mobile,email1,nametitle,firstname,lastname,idcardno,birthdate,gender,mobile2,email2,return_module,address1,street,subdistrict,district,province,postalcode) {
// alert(accountid)

	formName = 'EditView';
	var form = window.opener.document.forms[formName];

	if(document.getElementById('from_link').value != '')
	{
		var fldName = window.opener.document.QcEditView.account_no;
		var fldId = window.opener.document.QcEditView.accountid;
	}else
	{
		var fldName = window.opener.document.EditView.account_no;
		var fldId = window.opener.document.EditView.accountid;
	}
	if(typeof(form.accountid) != 'undefined'){
		fldId.value = accountid;
	}
	if(typeof(form.account_no) != 'undefined'){
		fldName.value = account_no;
	}    

	if(typeof(form.parentid) != 'undefined'){
		form.parentid.value = accountid;
	}
	if(typeof(form.parent_name) != 'undefined'){
		form.parent_name.value = accountname;
	}
	if(typeof(form.phone) != 'undefined'){
		form.phone.value = mobile;
	}
	if(typeof(form.email) != 'undefined'){
		form.email.value = email1;
	}

	if(typeof(form.fullname) != 'undefined'){
		form.fullname.value = firstname+" "+lastname;
	}

	if(return_module == 'Deal'){
		if(typeof(form.firstname) != 'undefined'){
			form.firstname.value = firstname;
		}
		if(typeof(form.lastname) != 'undefined'){
			form.lastname.value = lastname;
		}
		if(typeof(form.lastname) != 'undefined'){
			form.lastname.value = lastname;
		}
		if(typeof(form.idcardno) != 'undefined'){
			form.idcardno.value = idcardno;
		}
		
		if(typeof(form.birthdate) != 'undefined'){
			if(birthdate != '0000-00-00'){
				var c_birthdate = '';
				c_birthdate = birthdate.split("-");
				date = c_birthdate[2]+'-'+c_birthdate[1]+'-'+c_birthdate[0];
				console.log(date);
				form.birthdate.value = date;
			}
		}
		if(typeof(form.gender) != 'undefined'){
			form.gender.value = gender;
		}
		if(typeof(form.mobile2) != 'undefined'){
			form.mobile2.value = mobile2;
		}
		if(typeof(form.email2) != 'undefined'){
			form.email2.value = email2;
		}
		if(typeof(form.mobile) != 'undefined'){
			form.mobile.value = mobile;
		}
		if(typeof(form.email) != 'undefined'){
			form.email.value = email;
		}
	}else if(return_module == 'Quotes'){

        if(typeof(form.customer_name) != 'undefined')
            form.customer_name.value = accountname;
        if(typeof(form.taxid_no) != 'undefined')
            form.taxid_no.value = idcardno;
        if(typeof(form.mobile) != 'undefined')
            form.mobile.value = mobile;
        if(typeof(form.address) != 'undefined')
            form.address.value = address1;
        if(typeof(form.street) != 'undefined')
            form.street.value = street;
        if(typeof(form.sub_district) != 'undefined')
            form.sub_district.value = subdistrict;
        if(typeof(form.district) != 'undefined')
            form.district.value = district;
        if(typeof(form.province) != 'undefined')
            form.province.value = province;
        if(typeof(form.postal_code) != 'undefined')
            form.postal_code.value = postalcode;
	}

}

function add_data_to_relatedlist(entity_id,recordid) {
	opener.document.location.href="index.php?module=Emails&action=updateRelations&destination_module=Accounts&entityid="+entity_id+"&parentid="+recordid;
}

function set_return_formname_specific(formname,product_id, product_name) {
	window.opener.document.EditView1.account_name.value = product_name;
	window.opener.document.EditView1.account_id.value = product_id;
}

function set_return_prevaccount(account_id, account_name,firstname,lastname,salutation,middlename,nickname) {

	formName = 'EditView';
	var form = window.opener.document.forms[formName];
	if(typeof(form.prevacc_name) != 'undefined')
		form.prevacc_name.value = account_name;
	if(typeof(form.prevaccid) != 'undefined')
		form.prevaccid.value = account_id;
	if(typeof(form.prevacc_firstname) != 'undefined')
		form.prevacc_firstname.value = firstname;
	if(typeof(form.prevacc_lastname) != 'undefined')
		form.prevacc_lastname.value = lastname;
	if(typeof(form.prevacc_nickname) != 'undefined')
		form.prevacc_nickname.value = nickname;
	if(typeof(form.prevacc_middlename) != 'undefined')
		form.prevacc_middlename.value = middlename;
	if(typeof(form.prevacc_salutation) != 'undefined'){
		form.prevacc_salutation.value = salutation;
	}
}

function set_return_project(account_id, account_name,formName ,addressline1,addressline2,accountvillage,accountalley,accountroad,region,province,district,subdistrict,postalcode,mobile,phone,module_return) {

	if (formName == null || formName == '') formName = 'EditView';
	else {
		// In case formName is specified but does not exists then revert to EditView form
		if(window.opener.document.forms[formName] == null) formName = 'EditView';
	}
	var form = window.opener.document.forms[formName];

	if(typeof(form.account_name) != 'undefined')
		form.account_name.value = account_name;
	if(typeof(form.account_id) != 'undefined')
		form.account_id.value = account_id;

	if(typeof(form.addressline1) != 'undefined')
		form.addressline1.value = addressline1;
	if(typeof(form.addressline2) != 'undefined')
		form.addressline2.value = addressline2;
	if(typeof(form.accountvillage) != 'undefined')
		form.accountvillage.value = accountvillage;
	if(typeof(form.accountalley) != 'undefined')
		form.accountalley.value = accountalley;

	if(typeof(form.accountroad) != 'undefined')
		form.accountroad.value = accountroad;
	if(typeof(form.region) != 'undefined')
		form.region.value = region;
	if(typeof(form.province) != 'undefined')
		form.province.value = province;
	if(typeof(form.district) != 'undefined')
		form.district.value = district;
	if(typeof(form.subdistrict) != 'undefined')
		form.subdistrict.value = subdistrict;

	if(typeof(form.postalcode) != 'undefined')
		form.postalcode.value = postalcode;

	if(typeof(form.contact_name) != 'undefined')
		form.contact_name.value = '';
	if(typeof(form.contact_id) != 'undefined')
		form.contact_id.value = '';
}

function set_return_address(account_id,account_name,formName,mobile,idcardno ,branch ,nickname ,career ,village ,addressline ,addressline1 
	,villageno ,lane ,street ,subdistrict ,district ,province ,postalcode ,erpaccountid ,email,birthdate,nametitle,firstname,lastname,idcardno,gender,mobile2,email2,address1,address2,lineid,facebookid,billingvillage,billingaddressline,billingaddressline1,billingvillageno,billingstreet,billinglane,billingsubdistrict,billingdistrict,billingprovince,billingpostalcode, module_return,return_field,billing_address,account_payment_term) {
	if(formName == null || formName == ''){
		formName = 'EditView';
	}else{
		// In case formName is specified but does not exists then revert to EditView form
		if(window.opener.document.forms[formName] == null) formName = 'EditView';
	}

	var form = window.opener.document.forms[formName];


	if(module_return == 'Contacts'){
		if(typeof(form.account_name) != 'undefined')
			form.account_name.value = account_name;
		if(typeof(form.account_id) != 'undefined')
			form.account_id.value = account_id;
	}else if(module_return == 'Quotes'){
		if(return_field == "designerid"){
			if(typeof(form.designer_name) != 'undefined')
				form.designer_name.value = account_name;
			if(typeof(form.designerid) != 'undefined')
				form.designerid.value = account_id;
		}else{
			if(typeof(form.billing_address) != 'undefined')
				form.billing_address.value = billing_address;
			if(typeof(form.account_name) != 'undefined')
				form.account_name.value = account_name;
			if(typeof(form.account_id) != 'undefined')
				form.account_id.value = account_id;
			if(typeof(form.accountid) != 'undefined')
				form.accountid.value = account_id;
			if(typeof(form.mobile) != 'undefined')
				form.mobile.value = mobile;
			if(typeof(form.idcardno) != 'undefined')
				form.idcardno.value = idcardno;	
			if(typeof(form.address) != 'undefined')
				form.address.value = address1;
			if(typeof(form.customer_name) != 'undefined')
				form.customer_name.value = account_name;
			if(typeof(form.taxid_no) != 'undefined')
				form.taxid_no.value = idcardno;
			if(typeof(form.village) != 'undefined')
				form.village.value = billingvillage;
			if(typeof(form.room_no) != 'undefined')
				form.room_no.value = billingaddressline;
			if(typeof(form.address_no) != 'undefined')
				form.address_no.value = billingaddressline1;
			if(typeof(form.village_no) != 'undefined')
				form.village_no.value = billingvillageno;
			if(typeof(form.street) != 'undefined')
				form.street.value = billingstreet;
			if(typeof(form.lane) != 'undefined')
				form.lane.value = billinglane;
			if(typeof(form.sub_district) != 'undefined')
				form.sub_district.value = billingsubdistrict;
			if(typeof(form.district) != 'undefined')
				form.district.value = billingdistrict;
			if(typeof(form.province) != 'undefined')
				form.province.value = billingprovince;
			if(typeof(form.postal_code) != 'undefined')
				form.postal_code.value = billingpostalcode;
			if(typeof(form.quotation_acc_cd_no) != 'undefined')
				//form.quotation_acc_cd_no.value = cd_no;
			// Set payment terms
            if (typeof(form.payment_terms) !== 'undefined') {
                form.payment_terms.value = account_payment_term;
                // Get the dropdown and find the selected option
                var paymentTerms = form.payment_terms;
                var selectedOption = paymentTerms.querySelector('option[value="' + account_payment_term + '"]');

                // Remove the disabled attribute from the selected option and reset its color
                if (selectedOption) {
                    selectedOption.removeAttribute('disabled');
                    selectedOption.style.color = ""; // Reset color to default
                }
				
            }

			if(typeof(form.payment_terms_type) != 'undefined')
				form.payment_terms_type.value = payment_terms_type;
		}
	
	}else{
		if(typeof(form.account_name) != 'undefined')
			form.account_name.value = account_name;
		if(typeof(form.account_id) != 'undefined')
			form.account_id.value = account_id;
		if(typeof(form.accountid) != 'undefined')
			form.accountid.value = account_id;

		if(module_return == 'Calendar'){
			if(typeof(form.phone) != 'undefined')
				form.phone.value = mobile;
		}else if(module_return == 'HelpDesk'){
			if(typeof(form.tel) != 'undefined')
				form.tel.value = mobile;
		}else if(module_return == 'Samplerequisition'){
			if(typeof(form.tel) != 'undefined')
				form.tel.value = mobile;
			if(typeof(form.shipping_tel) != 'undefined')
				form.shipping_tel.value = mobile;
		}else{
			if(typeof(form.mobile) != 'undefined')
				form.mobile.value = mobile;
		}
		
		if(typeof(form.idcardno) != 'undefined'){
			form.idcardno.value = idcardno;
			window.opener.jQuery('input[name="idcardno"]').trigger('change');
		}

		if(typeof(form.branch) != 'undefined')
			form.branch.value = branch;
		
		if(typeof(form.nickname) != 'undefined')
			form.nickname.value = nickname;
		if(typeof(form.nname) != 'undefined')
			form.nname.value = nickname;
		if(typeof(form.career) != 'undefined')
			form.career.value = career;



		if(module_return == 'HelpDesk'){
			if(typeof(form.address) != 'undefined'){
				form.address.value = village+' '+addressline+' '+addressline1+' '+villageno+' '+lane+' '+street;
			}
			if(typeof(form.address_no) != 'undefined')
				form.address_no.value = addressline1;
			if(typeof(form.room_no) != 'undefined')
				form.room_no.value = addressline;
			if(typeof(form.village_no) != 'undefined')
				form.village_no.value = villageno;
			
			if(typeof(form.address) != 'undefined')
				form.address.value = address1;
			if(typeof(form.address2) != 'undefined')
				form.address2.value = address2;
			if(typeof(form.line_id) != 'undefined')
				form.line_id.value = lineid;
			if(typeof(form.facebook) != 'undefined')
				form.facebook.value = facebookid;
		}

		if(module_return == 'Samplerequisition'){
			if(typeof(form.address) != 'undefined'){
				form.address.value = address1+' '+village+' '+addressline+' '+addressline1+' '+subdistrict+' '+district+' '+province+' '+postalcode;
			}
			if(typeof(form.shipping_address) != 'undefined'){
				form.shipping_address.value = account_name+' '+address1+' '+village+' '+addressline+' '+addressline1+' '+subdistrict+' '+district+' '+province+' '+postalcode;
			}
			if(typeof(form.shipping_address1) != 'undefined'){
				form.shipping_address1.value = address1;
			}
		}		

		if(module_return == 'Servicerequest'){
			if(typeof(form.address_no) != 'undefined')
				form.address_no.value = address1
			if(typeof(form.sub_district) != 'undefined')
				form.sub_district.value = subdistrict;
			if(typeof(form.postal_code) != 'undefined')
				form.postal_code.value = postalcode;
		}

		if(module_return == 'Claim'){
			if(typeof(form.subdistrict) != 'undefined') form.subdistrict.value = billingsubdistrict
			if(typeof(form.district) != 'undefined') form.district.value = billingdistrict
			if(typeof(form.province) != 'undefined') form.province.value = billingprovince
			if(typeof(form.postalcode) != 'undefined') form.postalcode.value = billingpostalcode
		} else {
			if(typeof(form.village) != 'undefined') form.village.value = village;
			if(typeof(form.addressline) != 'undefined') form.addressline.value = addressline;
			if(typeof(form.addressline1) != 'undefined') form.addressline1.value = addressline1;
			if(typeof(form.villageno) != 'undefined') form.villageno.value = villageno;
			if(typeof(form.lane) != 'undefined') form.lane.value = lane;
			if(typeof(form.street) != 'undefined') form.street.value = street;
			if(typeof(form.subdistrict) != 'undefined') form.subdistrict.value = subdistrict;
			if(typeof(form.district) != 'undefined') form.district.value = district;
			if(typeof(form.province) != 'undefined') form.province.value = province;
			if(typeof(form.postalcode) != 'undefined') form.postalcode.value = postalcode;
			if(typeof(form.erpaccountid) != 'undefined') form.erpaccountid.value = erpaccountid;
		}
		
		if(module_return != 'Samplerequisition'){	
			if(typeof(form.email) != 'undefined')
				form.email.value = email;
		}
		if(typeof(form.birthdate) != 'undefined'){
			if(birthdate== '0000-00-00'){
				form.birthdate.value = '';	
			}else{
				var date = birthdate.split('-');
				var birth = date[2]+'-'+date[1]+'-'+date[0];
				form.birthdate.value = birth;
			}
		}
		if(typeof(form.salutation) != 'undefined'){if(nametitle!=''){form.salutation.value = nametitle};}
		if(typeof(form.firstname) != 'undefined')
			form.firstname.value = firstname;
		if(typeof(form.lastname) != 'undefined')
			form.lastname.value = lastname;
		if(typeof(form.idcardno) != 'undefined')
			form.idcardno.value = idcardno;	
		if(typeof(form.gender) != 'undefined'){if(gender!=''){form.gender.value = gender;};}
		if(typeof(form.mobile2) != 'undefined')
			form.mobile2.value = mobile2;
		if(typeof(form.email2) != 'undefined')
			form.email2.value = email2;
	}
}

//added to populate address
function set_return_contact_address(account_id, account_name, bill_street, ship_street, bill_city, ship_city, bill_state, ship_state, bill_code, ship_code, bill_country, ship_country,bill_pobox,ship_pobox,cf_629,cf_630,cf_631,cf_632,cf_633,cf_634,addressline1,addressline2,accountvillage,accountalley,accountroad,region,province,district,subdistrict,postalcode) {

	if(document.getElementById('from_link').value != '') {
		
		if(typeof(window.opener.document.QcEditView.account_name) != 'undefined')
			window.opener.document.QcEditView.account_name.value = account_name;
		if(typeof(window.opener.document.QcEditView.account_id) != 'undefined')
			window.opener.document.QcEditView.account_id.value = account_id;
		
	} else {
		
		if(typeof(window.opener.document.EditView.account_name) != 'undefined')
			window.opener.document.EditView.account_name.value = account_name;
		if(typeof(window.opener.document.EditView.account_id) != 'undefined')
			window.opener.document.EditView.account_id.value = account_id;
		if(typeof(window.opener.document.EditView.mailingstreet) != 'undefined')
			window.opener.document.EditView.mailingstreet.value = bill_street;
		if(typeof(window.opener.document.EditView.otherstreet) != 'undefined')
			window.opener.document.EditView.otherstreet.value = ship_street;
		if(typeof(window.opener.document.EditView.mailingcity) != 'undefined')
			window.opener.document.EditView.mailingcity.value = bill_city;
		if(typeof(window.opener.document.EditView.othercity) != 'undefined')
			window.opener.document.EditView.othercity.value = ship_city;
		if(typeof(window.opener.document.EditView.mailingstate) != 'undefined')
			window.opener.document.EditView.mailingstate.value = bill_state;
		if(typeof(window.opener.document.EditView.otherstate) != 'undefined')
			window.opener.document.EditView.otherstate.value = ship_state;
		if(typeof(window.opener.document.EditView.mailingzip) != 'undefined')
			window.opener.document.EditView.mailingzip.value = bill_code;
		if(typeof(window.opener.document.EditView.otherzip) != 'undefined')
			window.opener.document.EditView.otherzip.value = ship_code;
		if(typeof(window.opener.document.EditView.mailingcountry) != 'undefined')
			window.opener.document.EditView.mailingcountry.value = bill_country;
		if(typeof(window.opener.document.EditView.othercountry) != 'undefined')
			window.opener.document.EditView.othercountry.value = ship_country;
		if(typeof(window.opener.document.EditView.mailingpobox) != 'undefined')
			window.opener.document.EditView.mailingpobox.value = bill_pobox;
		if(typeof(window.opener.document.EditView.otherpobox) != 'undefined')
			window.opener.document.EditView.otherpobox.value = ship_pobox;
		if(typeof(  window.opener.document.EditView.addressline1) != 'undefined')
			window.opener.document.EditView.addressline1.value = addressline1;
		if(typeof(window.opener.document.EditView.addressline2) != 'undefined')
			window.opener.document.EditView.addressline2.value = addressline2;
		if(typeof(window.opener.document.EditView.accountvillage) != 'undefined')
			window.opener.document.EditView.accountvillage.value = accountvillage;
		if(typeof(window.opener.document.EditView.accountalley) != 'undefined')
			window.opener.document.EditView.accountalley.value = accountalley;
		if(typeof(window.opener.document.EditView.accountroad) != 'undefined')
			window.opener.document.EditView.accountroad.value = accountroad;
		if(typeof(window.opener.document.EditView.region) != 'undefined')
			window.opener.document.EditView.region.value = region;
		if(typeof(  window.opener.document.EditView.province) != 'undefined')
			window.opener.document.EditView.province.value = province;
		if(typeof(window.opener.document.EditView.district) != 'undefined')
			window.opener.document.EditView.district.value = district;
		if(typeof(window.opener.document.EditView.subdistrict) != 'undefined')
			window.opener.document.EditView.subdistrict.value = subdistrict;
		if(typeof(window.opener.document.EditView.postalcode) != 'undefined')
			window.opener.document.EditView.postalcode.value = postalcode;
	}

}

//added by rdhital/Raju for emails
function submitform(id){
	document.massdelete.entityid.value=id;
	document.massdelete.submit();
}

function searchMapLocation(addressType)
{
	var mapParameter = '';
	if (addressType == 'Main')
	{
		if(fieldname.indexOf('bill_street') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('bill_street')]))
				mapParameter = document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('bill_street')]).innerHTML+' ';
		}
		if(fieldname.indexOf('bill_pobox') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('bill_pobox')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('bill_pobox')]).innerHTML+' ';
		}
		if(fieldname.indexOf('bill_city') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('bill_city')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('bill_city')]).innerHTML+' ';
		}
		if(fieldname.indexOf('bill_state') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('bill_state')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('bill_state')]).innerHTML+' ';
		}
		if(fieldname.indexOf('bill_country') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('bill_country')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('bill_country')]).innerHTML+' ';
		}
		if(fieldname.indexOf('bill_code') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('bill_code')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('bill_code')]).innerHTML+' ';
		}

	}
	else if (addressType == 'Other')
	{
		if(fieldname.indexOf('ship_street') > -1)
		{

			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('ship_street')]))
				mapParameter = document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('ship_street')]).innerHTML+' ';
		}

		if(fieldname.indexOf('ship_pobox') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('ship_pobox')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('ship_pobox')]).innerHTML+' ';
		}

		if(fieldname.indexOf('ship_city') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('ship_city')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('ship_city')]).innerHTML+' ';
		}

		if(fieldname.indexOf('ship_state') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('ship_state')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('ship_state')]).innerHTML+' ';
		}
		if(fieldname.indexOf('ship_country') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('ship_country')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('ship_country')]).innerHTML+' ';
		}

		if(fieldname.indexOf('ship_code') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('ship_code')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('ship_code')]).innerHTML+' ';
		}
	}
	mapParameter = removeHTMLFormatting(mapParameter);
	window.open('http://maps.google.com/maps?q='+mapParameter,'goolemap','height=450,width=700,resizable=no,titlebar,location,top=200,left=250');
}
//javascript function will open new window to display traffic details for particular url using alexa.com
function getRelatedLink()
{
	var param='';
	param = getObj("website").value;
	window.open('http://www.alexa.com/data/details/traffic_details?q=&url='+param,'relatedlink','height=400,width=700,resizable=no,titlebar,location,top=250,left=250');
}

/*
* javascript function to populate fieldvalue in account editview
* @param id1 :: div tag ID
* @param id2 :: div tag ID
*/
function populateData(id1,id2)
{
	document.EditView.description.value = document.getElementById('summary').innerHTML;
	document.EditView.employees.value = getObj('emp').value;
	document.EditView.website.value = getObj('site').value;
	document.EditView.phone.value = getObj('Phone').value;
	document.EditView.fax.value = getObj('Fax').value;
	document.EditView.bill_street.value = getObj('address').value;

	showhide(id1,id2);
}
/*
* javascript function to show/hide the div tag
* @param argg1 :: div tag ID
* @param argg2 :: div tag ID
*/
function showhide(argg1,argg2)
{
	var x=document.getElementById(argg1).style;
	var y=document.getElementById(argg2).style;
	if (y.display=="none")
	{
		y.display="block"
		x.display="none"

	}
}

// JavaScript Document
if (document.all) var browser_ie=true
	else if (document.layers) var browser_nn4=true
		else if (document.layers || (!document.all && document.getElementById)) var browser_nn6=true

			function getObj(n,d) {
				var p,i,x;
				if(!d)d=document;
				if((p=n.indexOf("?"))>0&&parent.frames.length) {d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
				if(!(x=d[n])&&d.all)x=d.all[n];
				for(i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
					for(i=0;!x&&d.layers&&i<d.layers.length;i++)  x=getObj(n,d.layers[i].document);
						if(!x && d.getElementById) x=d.getElementById(n);
					return x;
				}


				function findPosX(obj) {
					var curleft = 0;
					if (document.getElementById || document.all) {
						while (obj.offsetParent) { curleft += obj.offsetLeft; obj = obj.offsetParent;}
					}
					else if (document.layers) { curleft += obj.x; }
					return curleft;
				}


				function findPosY(obj) {
					var curtop = 0;
					if (document.getElementById || document.all) {
						while (obj.offsetParent) { curtop += obj.offsetTop; obj = obj.offsetParent; }
					}
					else if (document.layers) {curtop += obj.y;}
					return curtop;
				}

				ScrollEffect = function(){ };
				ScrollEffect.lengthcount=202;
				ScrollEffect.closelimit=0;
				ScrollEffect.limit=0;

				function just(){
					ig=getObj("company");
					if(ScrollEffect.lengthcount > ScrollEffect.closelimit ){closet();return;}
					ig.style.display="block";
					ig.style.height=ScrollEffect.lengthcount+'px';
					ScrollEffect.lengthcount=ScrollEffect.lengthcount+10;
					if(ScrollEffect.lengthcount < ScrollEffect.limit){setTimeout("just()",25);}
					else{ getObj("innerLayer").style.display="block";return;}
				}

				function closet(){
					ig=getObj("company");
					getObj("innerLayer").style.display="none";
					ScrollEffect.lengthcount=ScrollEffect.lengthcount-10;
					ig.style.height=ScrollEffect.lengthcount+'px';
					if(ScrollEffect.lengthcount<20){ig.style.display="none";return;}
					else{setTimeout("closet()", 25);}
				}


				function fnDown(obj){
					var tagName = document.getElementById(obj);
					document.EditView.description.value = document.getElementById('summary').innerHTML;
					document.EditView.employees.value = getObj('emp').value;
					document.EditView.website.value = getObj('site').value;
					document.EditView.phone.value = getObj('Phone').value;
					document.EditView.fax.value = getObj('Fax').value;
					document.EditView.bill_street.value = getObj('address').value;
					if(tagName.style.display == 'none'){
						tagName.style.display = 'block';
					}else{
						tagName.style.display = 'none';
					}
				}

				function set_return_todo(product_id, product_name) {
					window.opener.document.createTodo.task_parent_name.value = product_name;
					window.opener.document.createTodo.task_parent_id.value = product_id;
				}
//When changing the Account Address Information  it should also change the related contact address.
function checkAddress(form,id)
{
	var url='';
	if(typeof(form.bill_street) != 'undefined')
		url +="&bill_street="+form.bill_street.value;
	if(typeof(form.ship_street) != 'undefined')
		url +="&ship_street="+form.ship_street.value;
	if(typeof(form.bill_city) != 'undefined')
		url +="&bill_city="+form.bill_city.value;
	if(typeof(form.ship_city) != 'undefined')
		url +="&ship_city="+form.ship_city.value;
	if(typeof(form.bill_state) != 'undefined')
		url +="&bill_state="+form.bill_state.value;
	if(typeof(form.ship_state) != 'undefined')
		url +="&ship_state="+form.ship_state.value;
	if(typeof(form.bill_code) != 'undefined')
		url +="&bill_code="+ form.bill_code.value;
	if(typeof(form.ship_code) != 'undefined')
		url +="&ship_code="+ form.ship_code.value;
	if(typeof(form.bill_country) != 'undefined')
		url +="&bill_country="+form.bill_country.value;
	if(typeof(form.ship_country) != 'undefined')
		url +="&ship_country="+form.ship_country.value;
	if(typeof(form.bill_pobox) != 'undefined')
		url +="&bill_pobox="+ form.bill_pobox.value;
	if(typeof(form.ship_pobox) != 'undefined')
		url +="&ship_pobox="+ form.ship_pobox.value;

	url +="&record="+id;

}
//Changing account address info - ENDS

function set_return_inventory(accountid,account_name,curr_row){

    window.opener.document.EditView.elements["account_name"+curr_row].value = account_name;
    window.opener.document.EditView.elements["accountid"+curr_row].value = accountid;

}

function set_return_specific_accountrel(account_id,account_name,fieldid,account_no,return_module){
	formName = 'EditView';
    var form = window.opener.document.forms[formName];
    var fieldname = fieldid+"_name" ;

    if(typeof(form[fieldid]) != 'undefined')
        form[fieldid].value = account_id;
    if(typeof(form[fieldname]) != 'undefined')
        form[fieldname].value = account_name;

    if(fieldid == 'account_id1'){
    	if(typeof(form.owner_no_1) != 'undefined')
            form.owner_no_1.value = account_no;
    }else if(fieldid == 'account_id2'){
    	if(typeof(form.owner_no_2) != 'undefined')
            form.owner_no_2.value = account_no;
    }else if(fieldid == 'account_id3'){
    	if(typeof(form.owner_no_3) != 'undefined')
            form.owner_no_3.value = account_no;
    }else if(fieldid == 'account_id4'){
    	if(typeof(form.owner_no_4) != 'undefined')
            form.owner_no_4.value = account_no;
    }else if(fieldid == 'account_id5'){
    	if(typeof(form.consultant_no_1) != 'undefined')
            form.consultant_no_1.value = account_no;
    }else if(fieldid == 'account_id6'){
    	if(typeof(form.architecture_no_1) != 'undefined')
            form.architecture_no_1.value = account_no;
    }else if(fieldid == 'account_id7'){
    	if(typeof(form.architecture_no_2) != 'undefined')
            form.architecture_no_2.value = account_no;
    }else if(fieldid == 'account_id8'){
    	if(typeof(form.construction_no_1) != 'undefined')
            form.construction_no_1.value = account_no;
    }else if(fieldid == 'account_id9'){
    	if(typeof(form.construction_no_2) != 'undefined')
            form.construction_no_2.value = account_no;
    }else if(fieldid == 'account_id10'){
    	if(typeof(form.designer_no_1) != 'undefined')
            form.designer_no_1.value = account_no;
    }else if(fieldid == 'account_id11'){
    	if(typeof(form.designer_no_2) != 'undefined')
            form.designer_no_2.value = account_no;
    }else if(fieldid == 'account_id12'){
    	if(typeof(form.designer_no_3) != 'undefined')
            form.designer_no_3.value = account_no;
    }else if(fieldid == 'account_id13'){
    	if(typeof(form.designer_no_4) != 'undefined')
            form.designer_no_4.value = account_no;
    }else if(fieldid == 'account_id14'){
    	if(typeof(form.contractor_no_1) != 'undefined')
            form.contractor_no_1.value = account_no;
    }else if(fieldid == 'account_id15'){
    	if(typeof(form.contractor_no_2) != 'undefined')
            form.contractor_no_2.value = account_no;
    }else if(fieldid == 'account_id16'){
    	if(typeof(form.contractor_no_3) != 'undefined')
            form.contractor_no_3.value = account_no;
    }else if(fieldid == 'account_id17'){
    	if(typeof(form.contractor_no_4) != 'undefined')
            form.contractor_no_4.value = account_no;
    }else if(fieldid == 'account_id18'){
    	if(typeof(form.contractor_no_5) != 'undefined')
            form.contractor_no_5.value = account_no;
    }else if(fieldid == 'account_id19'){
    	if(typeof(form.contractor_no_6) != 'undefined')
            form.contractor_no_6.value = account_no;
    }else if(fieldid == 'account_id20'){
    	if(typeof(form.contractor_no_7) != 'undefined')
            form.contractor_no_7.value = account_no;
    }else if(fieldid == 'account_id21'){
    	if(typeof(form.contractor_no_8) != 'undefined')
            form.contractor_no_8.value = account_no;
    }else if(fieldid == 'account_id22'){
    	if(typeof(form.contractor_no_9) != 'undefined')
            form.contractor_no_9.value = account_no;
    }else if(fieldid == 'account_id23'){
    	if(typeof(form.contractor_no_10) != 'undefined')
            form.contractor_no_10.value = account_no;
    }else if(fieldid == 'account_id24'){
    	if(typeof(form.sub_contractor_no_1) != 'undefined')
            form.sub_contractor_no_1.value = account_no;
    }else if(fieldid == 'account_id25'){
    	if(typeof(form.sub_contractor_no_2) != 'undefined')
            form.sub_contractor_no_2.value = account_no;
    }else if(fieldid == 'account_id26'){
    	if(typeof(form.sub_contractor_no_3) != 'undefined')
            form.sub_contractor_no_3.value = account_no;
    }

}