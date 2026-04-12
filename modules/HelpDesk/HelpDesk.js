/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/
document.write("<script type='text/javascript' src='modules/HelpDesk/multifile.js'></"+"script>");
document.write("<script type='text/javascript' src='include/js/Merge.js'></"+"script>");

function verify_data(form) {
	if(! form.createpotential.checked == true)
	{
        	if (form.potential_name.value == "")
		{
                	alert(alert_arr.OPPORTUNITYNAME_CANNOT_BE_EMPTY);
			return false;
		}
		if (form.closedate.value == "")
		{
                	alert(alert_arr.CLOSEDATE_CANNOT_BE_EMPTY);
			return false;
		}
		return dateValidate('closedate','Potential Close Date','GECD');


        }
        return true;
}

function togglePotFields(form)
{
	if (form.createpotential.checked == true)
	{
		form.potential_name.disabled = true;
		form.closedate.disabled = true;

	}
	else
	{
		form.potential_name.disabled = false;
		form.closedate.disabled = false;
	}

}

function toggleAssignType(currType)
{
        if (currType=="U")
        {
                getObj("assign_user").style.display="block"
                getObj("assign_team").style.display="none"
        }
        else
        {
                getObj("assign_user").style.display="none"
                getObj("assign_team").style.display="block"
        }
}

 

function set_return_specific(ticketid,ticket_title, ticket_no,formName,Module,contactid,contactname,position,email,mobile,department,accountid,accountname,addressline1,addressline2,buildings,unitline1,alley,accountroad,ubdistrict,district,province,postalcode,firstname,lastname){
    
    if(Module != 'Quotes' && Module != 'Calendar' && Module != 'Questionnaireanswer'){
        var fldName = getOpenerObj("ticket_title");
        var fldId = getOpenerObj("ticketid");
        fldName.value = ticket_title;
        fldId.value = ticketid;  
    }
    
    
    formName = 'EditView';
    var form = window.opener.document.forms[formName];

    if(typeof(form.contact_id) != 'undefined'){
        form.contact_id.value = contactid;
    }
    if(typeof(form.contact_name) != 'undefined'){
        form.contact_name.value = contactname;
    }
    if(typeof(form.con_position) != 'undefined'){
        form.con_position.value = position;
    }
    if(typeof(form.contact_email) != 'undefined'){
        form.contact_email.value = email;
    }
    if(typeof(form.contact_phone) != 'undefined'){
        form.contact_phone.value = mobile;
    }
    if(typeof(form.con_department) != 'undefined'){
        form.con_department.value = department;
    }
    if(typeof(form.account_id) != 'undefined'){
        form.account_id.value = accountid;
    }
    if(typeof(form.account_name) != 'undefined'){
        form.account_name.value = accountname;
    }
    
    if(typeof(form.account_address) != 'undefined'){
        form.account_address.value = addressline1+" "+addressline2+" "+buildings+" "+unitline1+" "+alley+" "+accountroad+" "+ubdistrict+" "+district+" "+province+" "+postalcode;
    }
    if(typeof(form.mobile) != 'undefined'){
        form.mobile.value = mobile;
    }
    if(typeof(form.email) != 'undefined'){
        form.email.value = email;
    }

    if(typeof(form.first_name) != 'undefined'){
        form.first_name.value = firstname;
    }
    if(typeof(form.last_name) != 'undefined'){
        form.last_name.value = lastname;
    }
    
    if(typeof(form.address) != 'undefined'){
        form.address.value = addressline1+" "+addressline2+" "+buildings+" "+unitline1+" "+alley+" "+accountroad+" "+ubdistrict+" "+district+" "+province+" "+postalcode;
    }

    if(typeof(form.event_name) != 'undefined'){
        form.event_name.value = ticket_no;
    }
    if(typeof(form.event_id) != 'undefined'){
        form.event_id.value = ticketid;
    }
        
    

   /* window.opener.document.EditView.action.value = 'EditView';
    window.opener.document.EditView.convertmode.value = 'update_ticket_val';
	window.opener.document.EditView.submit();*/
	
}

function set_return_specific_event(ticketid, ticketname, ticket_no,formName,Module,contactid,contactname,position,email,mobile,department,accountid,accountname) {
    //getOpenerObj used for DetailView
    if(document.getElementById('from_link').value != '' && typeof(window.opener.document.QcEditView)!= 'undefined' )
    {
        var fldName = window.opener.document.QcEditView.event_name;
        var fldId = window.opener.document.QcEditView.event_id;

    }else if(typeof(window.opener.document.DetailView) != 'undefined')
    {
        var fldName = window.opener.document.DetailView.event_name;
        var fldId = window.opener.document.DetailView.event_id;

    }else
    {
        var fldName = window.opener.document.EditView.event_name;
        var fldId = window.opener.document.EditView.event_id;
    }
    fldName.value = ticket_no;
    fldId.value = ticketid;

    formName = 'EditView';
    var form = window.opener.document.forms[formName];

    if(Module == 'Calendar' || Module == 'Job'){
        if(typeof(form.contact_id) != 'undefined'){
            form.contact_id.value = contactid;
        }
        if(typeof(form.contact_name) != 'undefined'){
            form.contact_name.value = contactname;
        }
        if(typeof(form.con_position) != 'undefined'){
            form.con_position.value = position;
        }
        if(typeof(form.email) != 'undefined'){
            form.email.value = email;
        }
        if(typeof(form.mobile) != 'undefined'){
            form.mobile.value = mobile;
        }
        if(typeof(form.con_department) != 'undefined'){
            form.con_department.value = department;
        }
        if(typeof(form.account_id) != 'undefined'){
            form.account_id.value = accountid;
        }
        if(typeof(form.account_name) != 'undefined'){
            form.account_name.value = accountname;
        }
    }
    if(Module == 'Calendar'){
        if(typeof(form.parentid) != 'undefined'){
            form.parentid.value = accountid;
        }
        if(typeof(form.parent_name) != 'undefined'){
            form.parent_name.value = accountname;
        }
        if(typeof(form.phone) != 'undefined'){
            form.phone.value = mobile;
        }
    }

}


function set_return(crmid, crm_name) {
    if(document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.event_name.value = crm_name;
        window.opener.document.QcEditView.event_id.value = crmid;
    } else {
        window.opener.document.EditView.event_name.value = crm_name;
        window.opener.document.EditView.event_id.value = crmid;
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
