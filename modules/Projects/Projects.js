/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

/*document.write("<script type='text/javascript' src='include/js/Inventory.js'></"+"script>");*/

function set_return(crmid, crm_name) {
    if(document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.event_name.value = crm_name;
        window.opener.document.QcEditView.event_id.value = crmid;
    } else {
        window.opener.document.EditView.event_name.value = crm_name;
        window.opener.document.EditView.event_id.value = crmid;
    }
}
function set_return_specific_event(projectid, projectname,formName,Module,contactid,contactname,email,mobile,accountid,accountname,projects_no,projectorder_status,project_s_date,project_estimate_e_date) {
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
    fldName.value = projectname;
    fldId.value = projectid;

    formName = 'EditView';
    var form = window.opener.document.forms[formName];

    if(Module == 'Calendar'){
        if(typeof(form.contact_id) != 'undefined'){
            form.contact_id.value = contactid;
        }
        if(typeof(form.contact_name) != 'undefined'){
            form.contact_name.value = contactname;
        }
        /*if(typeof(form.con_position) != 'undefined'){
            form.con_position.value = position;
        }*/
        if(typeof(form.email) != 'undefined'){
            form.email.value = email;
        }
        if(typeof(form.phone) != 'undefined'){
            form.phone.value = mobile;
        }
        /*if(typeof(form.con_department) != 'undefined'){
            form.con_department.value = department;
        }*/
        if(typeof(form.parentid) != 'undefined'){
            form.parentid.value = accountid;
        }
        if(typeof(form.parent_name) != 'undefined'){
            form.parent_name.value = accountname;
        }
    }else if(Module == 'Quotes'){
        if(typeof(form.project_name) != 'undefined'){
            form.project_name.value = projectname;
        }
        if(typeof(form.project_status) != 'undefined'){
            form.project_status.value = projectorder_status;
        }
        if(typeof(form.project_est_s_date) != 'undefined'){
            form.project_est_s_date.value = project_s_date;
        }
        if(typeof(form.project_est_e_date) != 'undefined'){
            form.project_est_e_date.value = project_estimate_e_date;
        }
    }else if(Module == 'Questionnaireanswer'){

    }
    
}

function set_return_specific(projects_id, projects_name , form , projects_no, project_s_date,project_estimate_e_date,dealid,deal_no) {

    var fldName = getOpenerObj("projects_no");
    var fldId = getOpenerObj("projectsid");
    fldName.value = projects_name;
    fldId.value = projects_id;

    formName = 'EditView';
    var form = window.opener.document.forms[formName];

    if(typeof(form.project_name) != 'undefined')
        form.project_name.value = projects_name;
    if(typeof(form.purchasing_period) != 'undefined')
        form.purchasing_period.value = project_s_date;

    if(typeof(form.dealid) != 'undefined')
        form.dealid.value = dealid;
    if(typeof(form.deal_no) != 'undefined')
        form.deal_no.value = deal_no;

    if(typeof(form.project_est_s_date) != 'undefined'){
        if(project_s_date != '01-01-1970'){
            form.project_est_s_date.value = project_s_date;
        }
    }
    if(typeof(form.project_est_e_date) != 'undefined'){
        if(project_estimate_e_date != '01-01-1970'){
            form.project_est_e_date.value = project_estimate_e_date;
        }
    }

    window.opener.document.EditView.action.value = 'EditView';
    window.opener.document.EditView.convertmode.value = 'update_quote_val';
	window.opener.document.EditView.submit();
}

function add_data_to_relatedlist(entity_id,recordid) {

        opener.document.location.href="index.php?module=Emails&action=updateRelations&destination_module=Accounts&entityid="+entity_id+"&parentid="+recordid;
}
function set_return_formname_specific(formname,product_id, product_name) {
        window.opener.document.EditView1.quote_name.value = product_name;
        window.opener.document.EditView1.quote_id.value = product_id;
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

function set_return_project_address(project_id,project_name, formName ,account_id,account_name,cf_3995,contact_id,contacts_name,con_department,con_position,email,mobile,phone) {
		
		if (formName == null || formName == '') formName = 'EditView';
		else {
			// In case formName is specified but does not exists then revert to EditView form
			if(window.opener.document.forms[formName] == null) formName = 'EditView';
		}
		var form = window.opener.document.forms[formName];
		
		if(typeof(form.projects_name) != 'undefined')
            form.projects_name.value = project_name;
		if(typeof(form.projectid) != 'undefined')
            form.projectid.value = project_id;
		
		if(typeof(form.account_id) != 'undefined')
            form.account_id.value = account_id;
		if(typeof(form.account_name) != 'undefined')
            form.account_name.value = account_name;
				
		if(typeof(form.contact_id) != 'undefined')
            form.contact_id.value = contact_id;
		if(typeof(form.contact_name) != 'undefined')
            form.contact_name.value = contacts_name;
		if(typeof(form.con_department) != 'undefined')
            form.con_department.value = con_department;
		if(typeof(form.con_position) != 'undefined')
            form.con_position.value = con_position;
		if(typeof(form.email) != 'undefined')
            form.email.value = email;
		if(typeof(form.mobile) != 'undefined')
            form.mobile.value = mobile;
		if(typeof(form.phone) != 'undefined')
            form.phone.value = phone;
			
}


function set_return_inventoryrel(projectsid,projects_name,curr_row,return_module,assignto){

    if(return_module == 'Purchasesorder'){

        window.opener.document.EditView.elements["projects_name"+curr_row].value = projects_name;
        window.opener.document.EditView.elements["projectsid"+curr_row].value = projectsid;
        window.opener.document.EditView.elements["assignto"+curr_row].value = assignto;
    }else if(return_module == 'Goodsreceive'){
        window.opener.document.EditView.elements["projects_name"+curr_row].value = projects_name;
        window.opener.document.EditView.elements["projectsid"+curr_row].value = projectsid;   
    }

}