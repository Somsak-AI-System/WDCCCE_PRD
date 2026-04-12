/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

document.write("<script type='text/javascript' src='modules/Contacts/multifile.js'></"+"script>");
document.write("<script type='text/javascript' src='include/js/Mail.js'></"+"script>");
document.write("<script type='text/javascript' src='include/js/Merge.js'></"+"script>");

function set_return_inventory(product_id,product_name,unitprice,qtyinstock,taxstr,curr_row,desc,subprod_id) {

	if (!isNaN(curr_row)){
		//curr_row=curr_row;
	}else{
		//curr_row=0;
	}

	var subprod = subprod_id.split("::");
	window.opener.document.EditView.elements["subproduct_ids"+curr_row].value = subprod[0];
	window.opener.document.getElementById("subprod_names"+curr_row).innerHTML = subprod[1];
	window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
	window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
	window.opener.document.EditView.elements["comment"+curr_row].value = desc;
	window.opener.document.EditView.elements["productName"+curr_row].focus()
}

function set_return_inventory_po(product_id,product_name,unitprice,taxstr,curr_row,desc,subprod_id) {
	var subprod = subprod_id.split("::");
	window.opener.document.EditView.elements["subproduct_ids"+curr_row].value = subprod[0];
	window.opener.document.getElementById("subprod_names"+curr_row).innerHTML = subprod[1];

	window.opener.document.EditView.elements["productName"+curr_row].value = product_name;
	window.opener.document.EditView.elements["hdnProductId"+curr_row].value = product_id;
	window.opener.document.EditView.elements["comment"+curr_row].value = desc;
	window.opener.document.EditView.elements["productName"+curr_row].focus()
}

function toggleDisplay(id){

if(this.document.getElementById( id).style.display=='none'){
	this.document.getElementById( id).style.display='inline'
	this.document.getElementById(id+"link").style.display='none';

}else{
	this.document.getElementById(  id).style.display='none'
	this.document.getElementById(id+"link").style.display='none';
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

function add_data_to_relatedlist_incal(id,name)
{
	var idval = window.opener.document.EditView.contactidlist.value;
	var nameval = window.opener.document.EditView.contactlist.value;
	if(idval != '')
	{
		if(idval.indexOf(id) != -1)
                {
                        window.opener.document.EditView.contactidlist.value = idval;
                        window.opener.document.EditView.contactlist.value = nameval;
                }
                else
                {
                        window.opener.document.EditView.contactidlist.value = idval+';'+id;
			if(name != '')
                	{
				// this has been modified to provide delete option for Contacts in Calendar
				//this function is defined in script.js ------- Jeri
				window.opener.addOption(id,name);
                	}

                }
	}
	else
	{
		window.opener.document.EditView.contactidlist.value = id;
		if(name != '')
		{
			window.opener.addOption(id,name);
		}
		//end
	}
}
function set_return_specific(product_id, product_name) {
        //Used for DetailView, Removed 'EditView' formname hardcoding
        var fldName = getOpenerObj("contact_name");
        var fldId = getOpenerObj("contact_id");
        fldName.value = product_name;
        fldId.value = product_id;
}
//only for Todo
function set_return_toDospecific(product_id, product_name) {
        var fldName = getOpenerObj("task_contact_name");
        var fldId = getOpenerObj("task_contact_id");
        fldName.value = product_name;
        fldId.value = product_id;
}

function submitform(id){
		document.massdelete.entityid.value=id;
		document.massdelete.submit();
}

function searchMapLocation(addressType)
{
        var mapParameter = '';
        if (addressType == 'Main')
        {
		if(fieldname.indexOf('mailingstreet') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('mailingstreet')]))
				mapParameter = document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('mailingstreet')]).innerHTML+' ';
		}
		if(fieldname.indexOf('mailingpobox') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('mailingpobox')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('mailingpobox')]).innerHTML+' ';
		}
		if(fieldname.indexOf('mailingcity') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('mailingcity')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('mailingcity')]).innerHTML+' ';
		}
		if(fieldname.indexOf('mailingstate') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('mailingstate')]))
	                        mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('mailingstate')]).innerHTML+' ';
		}
		if(fieldname.indexOf('mailingcountry') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('mailingcountry')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('mailingcountry')]).innerHTML+' ';
		}
		if(fieldname.indexOf('mailingzip') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('mailingzip')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('mailingzip')]).innerHTML;
		}
        }
        else if (addressType == 'Other')
        {
		if(fieldname.indexOf('otherstreet') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('otherstreet')]))
				mapParameter = document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('otherstreet')]).innerHTML+' ';
		}
		if(fieldname.indexOf('otherpobox') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('otherpobox')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('otherpobox')]).innerHTML+' ';
		}
		if(fieldname.indexOf('othercity') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('othercity')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('othercity')]).innerHTML+' ';
		}
		if(fieldname.indexOf('otherstate') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('otherstate')]))
	                        mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('otherstate')]).innerHTML+' ';
		}
                if(fieldname.indexOf('othercountry') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('othercountry')]))
				mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('othercountry')]).innerHTML+' ';
		}
                if(fieldname.indexOf('otherzip') > -1)
		{
			if(document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('otherzip')]))
                        	mapParameter = mapParameter + document.getElementById("dtlview_"+fieldlabel[fieldname.indexOf('otherzip')]).innerHTML;
		}
        }
	mapParameter = removeHTMLFormatting(mapParameter);
	window.open('http://maps.google.com/maps?q='+mapParameter,'goolemap','height=450,width=700,resizable=no,titlebar,location,top=200,left=250');
}

function set_return_contact_address_bak(contact_id,contact_name, mailingstreet, otherstreet, mailingcity, othercity, mailingstate, otherstate, mailingcode, othercode, mailingcountry, othercountry,mailingpobox,otherpobox,formName) {

	if (formName == null || formName == '') formName = 'EditView';
		else {
			// In case formName is specified but does not exists then revert to EditView form
			if(window.opener.document.forms[formName] == null) formName = 'EditView';
		}
		var form = window.opener.document.forms[formName];
		form.contact_name.value = contact_name;
        form.contact_id.value = contact_id;
	if(typeof(form.bill_street) != 'undefined')

	if(confirm(alert_arr.OVERWRITE_EXISTING_CONTACT1+contact_name+alert_arr.OVERWRITE_EXISTING_CONTACT2))
	{
		//made changes to avoid js error -- ref : hidding fields causes js error(ticket#4017)
                if(typeof(form.bill_street) != 'undefined')
                        form.bill_street.value = mailingstreet;
                if(typeof(form.ship_street) != 'undefined')
                        form.ship_street.value = otherstreet;
                if(typeof(form.bill_city) != 'undefined')
                        form.bill_city.value = mailingcity;
                if(typeof(form.ship_city) != 'undefined')
                        form.ship_city.value = othercity;
                if(typeof(form.bill_state) != 'undefined')
                        form.bill_state.value = mailingstate;
                if(typeof(form.ship_state) != 'undefined')
                        form.ship_state.value = otherstate;
                if(typeof(form.bill_code) != 'undefined')
                        form.bill_code.value = mailingcode;
                if(typeof(form.ship_code) != 'undefined')
                        form.ship_code.value = othercode;
				if(typeof(form.bill_country) != 'undefined')
                        form.bill_country.value = mailingcountry;
                if(typeof(form.ship_country) != 'undefined')
                        form.ship_country.value = othercountry;
                if(typeof(form.bill_pobox) != 'undefined')
                        form.bill_pobox.value = mailingpobox;
                if(typeof(form.ship_pobox) != 'undefined')
                        form.ship_pobox.value = otherpobox;
		//end
	}
}

function set_return_specific_contactcode1(contact_id, contact_name , lastname, email ,fieldid, mobile , account_id , account_name ,account_mobile,return_module,account_no,service_level_type,service_level){
    

    formName = 'EditView';
    var form = window.opener.document.forms[formName];
    var fieldname = fieldid+"_name" ;

    // alert(fieldid);
    if(fieldid =='contact_id1'){
        if(typeof(form.account_id1) != 'undefined')
            form.account_id1.value = account_id;
        if(typeof(form.account_id1_name) != 'undefined')
            form.account_id1_name.value = account_name;
        if(typeof(form.owner_no_1) != 'undefined')
            form.owner_no_1.value = account_no;
        if(typeof(form.service_level_type_owner_1) != 'undefined')
            form.service_level_type_owner_1.value = service_level_type;
        if(typeof(form.service_level_owner_1) != 'undefined')
            form.service_level_owner_1.value = service_level;
    }else if(fieldid =='contact_id2'){
        if(typeof(form.account_id2) != 'undefined')
            form.account_id2.value = account_id;
        if(typeof(form.account_id2_name) != 'undefined')
            form.account_id2_name.value = account_name;
        if(typeof(form.owner_no_2) != 'undefined')
            form.owner_no_2.value = account_no;
        if(typeof(form.service_level_type_consultant_1) != 'undefined')
            form.service_level_type_consultant_1.value = service_level_type;
        if(typeof(form.service_level_consultant_1) != 'undefined')
            form.service_level_consultant_1.value = service_level;
    }else if(fieldid =='contact_id3'){
        if(typeof(form.account_id3) != 'undefined')
            form.account_id3.value = account_id;
        if(typeof(form.account_id3_name) != 'undefined')
            form.account_id3_name.value = account_name;
        if(typeof(form.owner_no_3) != 'undefined')
            form.owner_no_3.value = account_no;
        if(typeof(form.service_level_type_architecture_1) != 'undefined')
            form.service_level_type_architecture_1.value = service_level_type;
        if(typeof(form.service_level_architecture_1) != 'undefined')
            form.service_level_architecture_1.value = service_level;
    }else if(fieldid =='contact_id4'){
        if(typeof(form.account_id4) != 'undefined')
            form.account_id4.value = account_id;
        if(typeof(form.account_id4_name) != 'undefined')
            form.account_id4_name.value = account_name;
        if(typeof(form.owner_no_4) != 'undefined')
            form.owner_no_4.value = account_no;
        if(typeof(form.service_level_type_construction_1) != 'undefined')
            form.service_level_type_construction_1.value = service_level_type;
        if(typeof(form.service_level_construction_1) != 'undefined')
            form.service_level_construction_1.value = service_level;
    }else if(fieldid =='contact_id5'){
        if(typeof(form.account_id5) != 'undefined')
            form.account_id5.value = account_id;
        if(typeof(form.account_id5_name) != 'undefined')
            form.account_id5_name.value = account_name;
        if(typeof(form.consultant_no_1) != 'undefined')
            form.consultant_no_1.value = account_no;
        if(typeof(form.service_level_type_designer_1) != 'undefined')
            form.service_level_type_designer_1.value = service_level_type;
        if(typeof(form.service_level_designer_1) != 'undefined')
            form.service_level_designer_1.value = service_level;
    }else if(fieldid =='contact_id6'){
        if(typeof(form.account_id6) != 'undefined')
            form.account_id6.value = account_id;
        if(typeof(form.account_id6_name) != 'undefined')
            form.account_id6_name.value = account_name;
        if(typeof(form.architecture_no_1) != 'undefined')
            form.architecture_no_1.value = account_no;
        if(typeof(form.service_level_type_designer_2) != 'undefined')
            form.service_level_type_designer_2.value = service_level_type;
        if(typeof(form.service_level_designer_2) != 'undefined')
            form.service_level_designer_2.value = service_level;
    }else if(fieldid =='contact_id7'){
        if(typeof(form.account_id7) != 'undefined')
            form.account_id7.value = account_id;
        if(typeof(form.account_id7_name) != 'undefined')
            form.account_id7_name.value = account_name;
        if(typeof(form.architecture_no_2) != 'undefined')
            form.architecture_no_2.value = account_no;
        if(typeof(form.service_level_type_contractor_1) != 'undefined')
            form.service_level_type_contractor_1.value = service_level_type;
        if(typeof(form.service_level_contractor_1) != 'undefined')
            form.service_level_contractor_1.value = service_level;
    }else if(fieldid =='contact_id8'){
        if(typeof(form.account_id8) != 'undefined')
            form.account_id8.value = account_id;
        if(typeof(form.account_id8_name) != 'undefined')
            form.account_id8_name.value = account_name;
        if(typeof(form.construction_no_1) != 'undefined')
            form.construction_no_1.value = account_no;
        if(typeof(form.service_level_type_contractor_2) != 'undefined')
            form.service_level_type_contractor_2.value = service_level_type;
        if(typeof(form.service_level_contractor_2) != 'undefined')
            form.service_level_contractor_2.value = service_level;
    }else if(fieldid =='contact_id9'){
        if(typeof(form.account_id9) != 'undefined')
            form.account_id9.value = account_id;
        if(typeof(form.account_id9_name) != 'undefined')
            form.account_id9_name.value = account_name;
        if(typeof(form.construction_no_2) != 'undefined')
            form.construction_no_2.value = account_no;
        if(typeof(form.service_level_type_sub_contractor_1) != 'undefined')
            form.service_level_type_sub_contractor_1.value = service_level_type;
        if(typeof(form.service_level_sub_contractor_1) != 'undefined')
            form.service_level_sub_contractor_1.value = service_level;
    }else if(fieldid =='contact_id10'){
        if(typeof(form.account_id10) != 'undefined')
            form.account_id10.value = account_id;
        if(typeof(form.account_id10_name) != 'undefined')
            form.account_id10_name.value = account_name;
        if(typeof(form.designer_no_1) != 'undefined')
            form.designer_no_1.value = account_no;
        if(typeof(form.service_level_type_sub_contractor_2) != 'undefined')
            form.service_level_type_sub_contractor_2.value = service_level_type;
        if(typeof(form.service_level_sub_contractor_2) != 'undefined')
            form.service_level_sub_contractor_2.value = service_level;
    }else if(fieldid =='contact_id11'){
        if(typeof(form.account_id11) != 'undefined')
            form.account_id11.value = account_id;
        if(typeof(form.account_id11_name) != 'undefined')
            form.account_id11_name.value = account_name;
        if(typeof(form.designer_no_2) != 'undefined')
            form.designer_no_2.value = account_no;
    }else if(fieldid =='contact_id12'){
        if(typeof(form.account_id12) != 'undefined')
            form.account_id12.value = account_id;
        if(typeof(form.account_id12_name) != 'undefined')
            form.account_id12_name.value = account_name;
        if(typeof(form.designer_no_3) != 'undefined')
            form.designer_no_3.value = account_no;
    }else if(fieldid =='contact_id13'){
        if(typeof(form.account_id13) != 'undefined')
            form.account_id13.value = account_id;
        if(typeof(form.account_id13_name) != 'undefined')
            form.account_id13_name.value = account_name;
        if(typeof(form.designer_no_4) != 'undefined')
            form.designer_no_4.value = account_no;
    }else if(fieldid =='contact_id14'){
        if(typeof(form.account_id14) != 'undefined')
            form.account_id14.value = account_id;
        if(typeof(form.account_id14_name) != 'undefined')
            form.account_id14_name.value = account_name;
        if(typeof(form.contractor_no_1) != 'undefined')
            form.contractor_no_1.value = account_no;
    }else if(fieldid =='contact_id15'){
        if(typeof(form.account_id15) != 'undefined')
            form.account_id15.value = account_id;
        if(typeof(form.account_id15_name) != 'undefined')
            form.account_id15_name.value = account_name;
        if(typeof(form.contractor_no_2) != 'undefined')
            form.contractor_no_2.value = account_no;
    }else if(fieldid =='contact_id16'){
        if(typeof(form.account_id16) != 'undefined')
            form.account_id16.value = account_id;
        if(typeof(form.account_id16_name) != 'undefined')
            form.account_id16_name.value = account_name;
        if(typeof(form.contractor_no_3) != 'undefined')
            form.contractor_no_3.value = account_no;
    }else if(fieldid =='contact_id17'){
        if(typeof(form.account_id17) != 'undefined')
            form.account_id17.value = account_id;
        if(typeof(form.account_id17_name) != 'undefined')
            form.account_id17_name.value = account_name;
        if(typeof(form.contractor_no_4) != 'undefined')
            form.contractor_no_4.value = account_no;
    }else if(fieldid =='contact_id18'){
        if(typeof(form.account_id18) != 'undefined')
            form.account_id18.value = account_id;
        if(typeof(form.account_id18_name) != 'undefined')
            form.account_id18_name.value = account_name;
        if(typeof(form.contractor_no_5) != 'undefined')
            form.contractor_no_5.value = account_no;
    }else if(fieldid =='contact_id19'){
        if(typeof(form.account_id19) != 'undefined')
            form.account_id19.value = account_id;
        if(typeof(form.account_id19_name) != 'undefined')
            form.account_id19_name.value = account_name;
        if(typeof(form.contractor_no_6) != 'undefined')
            form.contractor_no_6.value = account_no;
    }else if(fieldid =='contact_id20'){
        if(typeof(form.account_id20) != 'undefined')
            form.account_id20.value = account_id;
        if(typeof(form.account_id20_name) != 'undefined')
            form.account_id20_name.value = account_name;
        if(typeof(form.contractor_no_7) != 'undefined')
            form.contractor_no_7.value = account_no;
    }else if(fieldid =='contact_id21'){
        if(typeof(form.account_id21) != 'undefined')
            form.account_id21.value = account_id;
        if(typeof(form.account_id21_name) != 'undefined')
            form.account_id21_name.value = account_name;
        if(typeof(form.contractor_no_8) != 'undefined')
            form.contractor_no_8.value = account_no;
    }else if(fieldid =='contact_id22'){
        if(typeof(form.account_id22) != 'undefined')
            form.account_id22.value = account_id;
        if(typeof(form.account_id22_name) != 'undefined')
            form.account_id22_name.value = account_name;
        if(typeof(form.contractor_no_9) != 'undefined')
            form.contractor_no_9.value = account_no;
    }else if(fieldid =='contact_id23'){
        if(typeof(form.account_id23) != 'undefined')
            form.account_id23.value = account_id;
        if(typeof(form.account_id23_name) != 'undefined')
            form.account_id23_name.value = account_name;
        if(typeof(form.contractor_no_10) != 'undefined')
            form.contractor_no_10.value = account_no;
    }else if(fieldid =='contact_id24'){
        if(typeof(form.account_id24) != 'undefined')
            form.account_id24.value = account_id;
        if(typeof(form.account_id24_name) != 'undefined')
            form.account_id24_name.value = account_name;
        if(typeof(form.sub_contractor_no_1) != 'undefined')
            form.sub_contractor_no_1.value = account_no;
    }else if(fieldid =='contact_id25'){
        if(typeof(form.account_id25) != 'undefined')
            form.account_id25.value = account_id;
        if(typeof(form.account_id25_name) != 'undefined')
            form.account_id25_name.value = account_name;
        if(typeof(form.sub_contractor_no_2) != 'undefined')
            form.sub_contractor_no_2.value = account_no;
    }else if(fieldid =='contact_id26'){
        if(typeof(form.account_id26) != 'undefined')
            form.account_id26.value = account_id;
        if(typeof(form.account_id26_name) != 'undefined')
            form.account_id26_name.value = account_name;
        if(typeof(form.sub_contractor_no_3) != 'undefined')
            form.sub_contractor_no_3.value = account_no;
    }

    if(typeof(form[fieldid]) != 'undefined')
        form[fieldid].value = contact_id;
    if(typeof(form[fieldname]) != 'undefined')
        form[fieldname].value = contact_name+' '+lastname;

    if( fieldid == 'contactid1'){
        if(typeof(form.contact_tel) != 'undefined')
            form.contact_tel.value = mobile;
        if(typeof(form.contact_email) != 'undefined')
            form.contact_email.value = email;
    
    }else if( fieldid == 'contactid2'){
        if(typeof(form.contact_tel_2) != 'undefined')
            form.contact_tel_2.value = mobile;
        if(typeof(form.contact_email_2) != 'undefined')
            form.contact_email_2.value = email;
    
    }else if( fieldid == 'contactid3'){
        if(typeof(form.contact_tel_3) != 'undefined')
            form.contact_tel_3.value = mobile;
        if(typeof(form.contact_email_3) != 'undefined')
            form.contact_email_3.value = email;
    }else{

    	/*if(typeof(form.contact_id) != 'undefined')
        form.contact_id.value = contact_id;
	    if(typeof(form.contact_name) != 'undefined')
	        form.contact_name.value = contact_name+' '+lastname;
	    if(typeof(form.contact_tel) != 'undefined')
	        form.contact_tel.value = mobile;*/
    }

    
    
    if(return_module == 'Calendar'){
        if(typeof(form.parentid) != 'undefined')
            form.parentid.value = account_id;
        if(typeof(form.parent_name) != 'undefined')
            form.parent_name.value = account_name;
        if(typeof(form.parent_type) != 'undefined'){
            form.parent_type.value = 'Accounts';
            window.opener.jQuery('input[name="parent_type"]').trigger('change');
        }

        if(typeof(form.phone) != 'undefined')
            form.phone.value = mobile;
        if(typeof(form.email) != 'undefined')
            form.email.value = email;
    }
    
    if(return_module == 'Deal'){
        if(typeof(form.parentid) != 'undefined')
            form.parentid.value = account_id;
        if(typeof(form.parent_name) != 'undefined')
            form.parent_name.value = account_name;
        if(typeof(form.parent_type) != 'undefined'){
            form.parent_type.value = 'Accounts';
            window.opener.jQuery('input[name="parent_type"]').trigger('change');
        }
    }
    
    if(return_module == 'HelpDesk'){
        if(typeof(form.account_id) != 'undefined')
            form.account_id.value = account_id;
        if(typeof(form.account_name) != 'undefined')
            form.account_name.value = account_name;
    
        if(typeof(form.tel) != 'undefined')
            form.tel.value = mobile;
        if(typeof(form.email) != 'undefined')
            form.email.value = email;


    }
    
}


function set_return_specific_contactcode2(contact_id, contact_name, email ,fieldid, mobile , account_id , account_name ,account_mobile){

    formName = 'EditView';
    var form = window.opener.document.forms[formName];
    var fieldname = fieldid+"_name" ;

    if(typeof(form[fieldid]) != 'undefined')
        form[fieldid].value = contact_id;
    if(typeof(form[fieldname]) != 'undefined')
        form[fieldname].value = contact_name;

    if( fieldid == 'contactid1'){
        if(typeof(form.contact_dealer_mobile) != 'undefined')
            form.contact_dealer_mobile.value = mobile;
    }else if( fieldid == 'contactid2'){
        if(typeof(form.sales_dealer_mobile) != 'undefined')
            form.sales_dealer_mobile.value = mobile;
    }

    if(typeof(form.account_id2) != 'undefined')
        form.account_id2.value = account_id;
    if(typeof(form.account_id2_name) != 'undefined')
        form.account_id2_name.value = account_name;
    if(typeof(form.dealer_mobile) != 'undefined')
        form.dealer_mobile.value = account_mobile;
}

function set_return_contact_address(contact_id,name,contact_mobile,contact_email,contactname,formName,account_id,account_name,account_mobile,account_email,return_module,line_id){
    if (formName == null || formName == '') {
        formName = 'EditView';
    }else {
        if(window.opener.document.forms[formName] == null) formName = 'EditView';
    }

    var form = window.opener.document.forms[formName];

    if(typeof(form.contactid) != 'undefined')
        form.contactid.value = contact_id;
    if(typeof(form.contact_name) != 'undefined')
        form.contact_name.value = contactname;
    if(typeof(form.contact_id) != 'undefined')
        form.contact_id.value = contact_id;

    if(typeof(form.account_name) != 'undefined')
        form.account_name.value = account_name;
    if(typeof(form.account_id) != 'undefined')
        form.account_id.value = account_id;

    if(return_module == 'Quotes'){
        if(typeof(form.account_name) != 'undefined')
            form.account_name.value = account_name;
        if(typeof(form.account_id) != 'undefined')
            form.account_id.value = account_id;
    }

    if(return_module == 'Calendar'){
        if(typeof(form.parentid) != 'undefined')
            form.parentid.value = account_id;
        if(typeof(form.parent_name) != 'undefined')
            form.parent_name.value = account_name;
        if(typeof(form.parent_type) != 'undefined'){
            form.parent_type.value = 'Accounts';
            window.opener.jQuery('input[name="parent_type"]').trigger('change');
        }

        if(typeof(form.phone) != 'undefined')
            form.phone.value = account_mobile;
        if(typeof(form.email) != 'undefined')
            form.email.value = account_email;
        if(typeof(form.contact_mobile) != 'undefined')
            form.contact_mobile.value = contact_mobile;
        if(typeof(form.contact_email) != 'undefined')
            form.contact_email.value = contact_email;
        
    }

    if(return_module == 'Samplerequisition'){
        if(typeof(form.tel_contact) != 'undefined')
            form.tel_contact.value = contact_mobile;
        if(typeof(form.email) != 'undefined')
            form.email.value = account_email;
        if(typeof(form.line_id) != 'undefined')
            form.line_id.value = line_id;
        if(typeof(form.shipping_contact_name) != 'undefined')
            form.shipping_contact_name.value = contactname;
        if(typeof(form.shipping_tel_contact) != 'undefined')
            form.shipping_tel_contact.value = contact_mobile;
    }
}

function set_return_contact_address1(contact_id,contact_name, mailingstreet, otherstreet, mailingcity, othercity, mailingstate, otherstate, mailingcode, othercode, mailingcountry, othercountry,
mailingpobox, otherpobox, phone, mobile, email, addressline1, addressline2, accountvillage, accountalley, accountroad, region, province, district, subdistrict, postalcode, formName, lastname ,con_position,
account_id,account_name,account_mobile,account_addressline1,account_addressline2,accountvillage,accountalley,accountroad,region,province,district,subdistrict,postalcode,con_department,account_cpname,
account_cpaddress,account_cpsection,account_cpvillage,account_cpalley,account_cproad,working_region,working_province,working_district,working_subdistrict,working_postalcode,accounttcontact,
account_cptelephone,account_cpfax,cf_3984 ,cf_3985 ,account_bill_name, return_module, con_engname, con_englastname,accountname_en,address_en,facebook,line_id,address1) {

		if (formName == null || formName == '') {
			formName = 'EditView';
		}else {
			// In case formName is specified but does not exists then revert to EditView form
			if(window.opener.document.forms[formName] == null) formName = 'EditView';
		}
		var form = window.opener.document.forms[formName];
		form.contact_name.value = contact_name+" "+lastname;
        form.contact_id.value = contact_id;
		/*Return Data To Module Project Order*/

		if(typeof(form.account_name) != 'undefined')
            form.account_name.value = account_name;
		if(typeof(form.account_id) != 'undefined')
            form.account_id.value = account_id;
        
		if(typeof(form.con_department) != 'undefined' && typeof(form.con_position) != 'undefined' ){
            	form.con_department.value = con_department;
            	form.con_position.value = con_position;
		}else{
			if(typeof(form.con_department) != 'undefined')
				form.con_department.value = account_addressline2;
		}

        if(typeof(form.phone) != 'undefined')
            form.phone.value = phone;
        if(typeof(form.mobile) != 'undefined')
            form.mobile.value = mobile;
        if(typeof(form.email) != 'undefined')
            form.email.value = email;


	/*Puen Add*/
	if (return_module == 'Job'){
        if(typeof(form.contact_email) != 'undefined')
            form.contact_email.value = email;
        if(typeof(form.contact_phone) != 'undefined')
            form.contact_phone.value = mobile;
        if(typeof(form.job_number_house) != 'undefined')
            form.job_number_house.value = account_addressline1;
        if(typeof(form.job_moo) != 'undefined')
            form.job_moo.value = account_addressline2;
        if(typeof(form.job_village) != 'undefined')
            form.job_village.value = accountvillage;
        if(typeof(form.job_alley) != 'undefined')
            form.job_alley.value = accountalley;
        if(typeof(form.job_road) != 'undefined')
            form.job_road.value = accountroad;


     	if(typeof(form.account_address) != 'undefined'){
            var account_address = '';

            if(account_addressline1 != ''){
                account_address += account_addressline1+" ";
            }
            if(account_addressline2 != ''){
                account_address += account_addressline2+" ";
            }
            if(accountvillage != ''){
                account_address += accountvillage+" ";
            }
            if(accountalley != ''){
                account_address += accountalley+" ";
            }
            if(accountroad != ''){
                account_address += accountroad+" ";
            }
            if(subdistrict != ''){
                account_address += subdistrict+" ";
            }
            if(district != ''){
                account_address += district+" ";
            }
            if(province != ''){
                account_address += province+" ";
            }
            if(postalcode != ''){
                account_address += postalcode;
            }

            form.account_address.value = account_address;
    	}

        if(typeof(form.job_contactname_en) != 'undefined'){

            var job_contactname_en = '';
            if(job_contactname_en != ''){
                job_contactname_en = (con_engname +' '+ con_englastname);
            }
			form.job_contactname_en.value = (con_engname +' '+ con_englastname);
        }

        if(typeof(form.job_accountname_en) != 'undefined'){

            var job_accountname_en = '';
            if(job_accountname_en != ''){
                job_accountname_en = accountname_en;
            }
            form.job_accountname_en.value = accountname_en;
        }

        if(typeof(form.job_address_en) != 'undefined'){

            var job_address_en = '';
            if(job_address_en != ''){
                job_address_en = address_en;
            }
            form.job_address_en.value = address_en;
        }
	}

	if(return_module == 'HelpDesk'){
		if(typeof(form.phone) != 'undefined')
        	form.phone.value = mobile;
        if(typeof(form.tel) != 'undefined')
        	form.tel.value = mobile;
       	if(typeof(form.line_id) != 'undefined')
        	form.line_id.value = line_id;
        if(typeof(form.facebook) != 'undefined')
        	form.facebook.value = facebook;

        if(typeof(form.address) != 'undefined')
            form.address.value = address1;
        if(typeof(form.street) != 'undefined')
            form.street.value = accountroad;
        if(typeof(form.subdistrict) != 'undefined')
            form.subdistrict.value = subdistrict;
        if(typeof(form.district) != 'undefined')
            form.district.value = district;
        if(typeof(form.province) != 'undefined')
            form.province.value = province;
        if(typeof(form.postalcode) != 'undefined')
            form.postalcode.value = postalcode;
	}

    if(return_module == 'Servicerequest'){
        if(typeof(form.address_no) != 'undefined')
            form.address_no.value = address1;
        if(typeof(form.street) != 'undefined')
            form.street.value = accountroad;
        if(typeof(form.sub_district) != 'undefined')
            form.sub_district.value = subdistrict;
        if(typeof(form.district) != 'undefined')
            form.district.value = district;
        if(typeof(form.province) != 'undefined')
            form.province.value = province;
        if(typeof(form.postal_code) != 'undefined')
            form.postal_code.value = postalcode;
    }

    if(return_module == 'Calendar'){
        if(typeof(form.parentid) != 'undefined')
            form.parentid.value = account_id;
        if(typeof(form.parent_name) != 'undefined')
            form.parent_name.value = account_name;
        if(typeof(form.parent_type) != 'undefined'){
            form.parent_type.value = 'Accounts';
            window.opener.jQuery('input[name="parent_type"]').trigger('change');
        }
        if(typeof(form.fullname) != 'undefined')
            form.fullname.value = account_name;
        if(typeof(form.phone) != 'undefined')
            form.phone.value = mobile;
    }
	//Quotation
	if(return_module != '' && return_module == 'Quotes'){
	}else{

		if(typeof(form.account_bill_name) != 'undefined')
          form.account_bill_name.value = account_bill_name;
	}

}
