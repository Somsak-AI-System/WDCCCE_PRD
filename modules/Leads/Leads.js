/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

document.write("<script type='text/javascript' src='modules/Leads/multifile.js'></"+"script>");
document.write("<script type='text/javascript' src='include/js/Mail.js'></"+"script>");
document.write("<script type='text/javascript' src='include/js/Merge.js'></"+"script>");

function request_data(form){

	if (form.account_name.value == ""){
        alert('กรุณาใส่ชื่อลูกค้า');
		return false;			
	}
	return true;
}

function verify_data(form) {
	if(! form.createpotential.checked == true){
        if (trim(form.potential_name.value) == ""){
            alert(alert_arr.OPPORTUNITYNAME_CANNOT_BE_EMPTY);
			return false;	
		}
		
		if(form.closingdate_mandatory != null && form.closingdate_mandatory.value == '*'){
			if (form.closedate.value == ""){
	        	alert(alert_arr.CLOSEDATE_CANNOT_BE_EMPTY);
				return false;	
			}add_data_to_relatedlist
		}
		if (form.closedate.value != "" ){
			var x = dateValidate('closedate','Potential Close Date','DATE');
			if(!x){
				return false;
			}
		}
			
		if(form.amount_mandatory.value == '*'){
			if (form.potential_amount.value == ""){
	            alert(alert_arr.AMOUNT_CANNOT_BE_EMPTY);
				return false;					
			}
		}	
		intval= intValidate('potential_amount','Potential Amount');
		if(!intval){
			return false;
		}
	}
	else{	
		return true;
	}
}

function togglePotFields(form)
{
	if (form.createpotential.checked == true)
	{
		form.potential_name.disabled = true;
		form.closedate.disabled = true;
		form.potential_amount.disabled = true;
		form.potential_sales_stage.disabled = true;
		
	}
	else
	{
		form.potential_name.disabled = false;
		form.closedate.disabled = false;
		form.potential_amount.disabled = false;
		form.potential_sales_stage.disabled = false;
		form.potential_sales_stage.value="";
	}	

}


function set_return(product_id, product_name) {
	if(document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.parent_name.value = product_name;
        window.opener.document.QcEditView.parent_id.value = product_id;
	} else {
        window.opener.document.EditView.parent_name.value = product_name;
        window.opener.document.EditView.parent_id.value = product_id;
}
}

function set_return_todo(product_id, product_name) {
	if(document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.task_parent_name.value = product_name;
        window.opener.document.QcEditView.task_parent_id.value = product_id;
	} else {
        window.opener.document.createTodo.task_parent_name.value = product_name;
        window.opener.document.createTodo.task_parent_id.value = product_id;
	}
}

function set_return_specific(lead_id,lead_no,formName,salutationtype,firstname,lastname,full_name,mobile,email,idcardno,birthdate,gender,return_module,address1,lead_street,lead_subdistrinct,lead_district,lead_province,lead_postalcode) {

    if(document.getElementById('from_link').value != '' && typeof(window.opener.document.QcEditView)!= 'undefined' )
    {
        var fldName = window.opener.document.QcEditView.lead_no;
        var fldId = window.opener.document.QcEditView.lead_id;
    }else if(typeof(window.opener.document.DetailView) != 'undefined'){
        var fldName = window.opener.document.DetailView.lead_no;
        var fldId = window.opener.document.DetailView.lead_id;
    }else{
        var fldName = window.opener.document.EditView.lead_no;
        var fldId = window.opener.document.EditView.lead_id;
    }

    formName = 'EditView';
    var form = window.opener.document.forms[formName];
    if(typeof(form.leadid) != 'undefined'){
        form.leadid.value = lead_id;
    }
    if(typeof(form.lead_name) != 'undefined'){
        form.lead_name.value = full_name;
    }

    if(typeof(form.parentid) != 'undefined'){
        form.parentid.value = lead_id;
    }
    if(typeof(form.parent_name) != 'undefined'){
        form.parent_name.value = full_name;
    }
    if(typeof(form.phone) != 'undefined'){
        form.phone.value = mobile;
    }
    if(typeof(form.email) != 'undefined'){
        form.email.value = email;
    }

    if(typeof(form.fullname) != 'undefined'){
        form.fullname.value = full_name;
    }
    
    if(return_module == 'Deal'){
    	if(typeof(form.salutation) != 'undefined'){
        	form.salutation.value = salutationtype;
    	}
    	if(typeof(form.firstname) != 'undefined'){
        	form.firstname.value = firstname;
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
    	if(typeof(form.mobile) != 'undefined'){
        	form.mobile.value = mobile;
    	}
    	if(typeof(form.email) != 'undefined'){
        	form.email.value = email;
    	}
    }else if(return_module == 'Quotes'){
        if(typeof(form.customer_name) != 'undefined')
            form.customer_name.value = full_name;
        if(typeof(form.taxid_no) != 'undefined')
            form.taxid_no.value = idcardno;
        if(typeof(form.mobile) != 'undefined')
            form.mobile.value = mobile;
        if(typeof(form.address) != 'undefined')
            form.address.value = address1;
        if(typeof(form.street) != 'undefined')
            form.street.value = lead_street;
        if(typeof(form.sub_district) != 'undefined')
            form.sub_district.value = lead_subdistrinct;
        if(typeof(form.district) != 'undefined')
            form.district.value = lead_district;
        if(typeof(form.province) != 'undefined')
            form.province.value = lead_province;
        if(typeof(form.postal_code) != 'undefined')
            form.postal_code.value = lead_postalcode;
	}
}

function add_data_to_relatedlist(entity_id,recordid) {
	opener.document.location.href="index.php?module=Emails&action=updateRelations&destination_module=leads&entityid="+entity_id+"&parentid="+recordid;
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
	if(fieldname.indexOf('lane') > -1)
	{
		if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('lane')]))
                        mapParameter = document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('lane')]).innerHTML+' ';
	}
	if(fieldname.indexOf('pobox') > -1)
	{
		if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('pobox')]))
			mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('pobox')]).innerHTML+' ';
	}
	if(fieldname.indexOf('city') > -1)
	{
		if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('city')]))
			mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('city')]).innerHTML+' ';
	}
	if(fieldname.indexOf('state') > -1)
	{
		if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('state')]))
			mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('state')]).innerHTML+' ';
	}
	if(fieldname.indexOf('country') > -1)
	{
		if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('country')]))
			mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('country')]).innerHTML+' ';
	}
	if(fieldname.indexOf('code') > -1)
	{
		if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('code')]))
            mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('code')]).innerHTML+' ';
		}
    }
	mapParameter = removeHTMLFormatting(mapParameter);
    window.open('http://maps.google.com/maps?q='+mapParameter,'goolemap','height=450,width=700,resizable=no,titlebar,location,top=200,left=250');
}


