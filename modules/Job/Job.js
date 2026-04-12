/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/
document.write("<script type='text/javascript' src='modules/Job/multifile.js'></"+"script>");
document.write("<script type='text/javascript' src='include/js/Inventory_Job.js'></"+"script>");

function set_return(crmid, crm_name) {
    if(document.getElementById('from_link').value != '') {
        window.opener.document.QcEditView.event_name.value = crm_name;
        window.opener.document.QcEditView.event_id.value = crmid;
    } else {
        window.opener.document.EditView.event_name.value = crm_name;
        window.opener.document.EditView.event_id.value = crmid;
    }
}

function set_return_specific_event(job_id, job_name,formName,Module,contactid,contactname,position,email,mobile,department,accountid,accountname,jobdate_operate,start_time,end_time) {
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
    fldName.value = job_name;
    fldId.value = job_id;

    formName = 'EditView';
    var form = window.opener.document.forms[formName];

    if(Module == 'Calendar'){
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
        if(typeof(form.phone) != 'undefined'){
            form.phone.value = mobile;
        }
        if(typeof(form.con_department) != 'undefined'){
            form.con_department.value = department;
        }
        if(typeof(form.parentid) != 'undefined'){
            form.parentid.value = accountid;
        }
        if(typeof(form.parent_name) != 'undefined'){
            form.parent_name.value = accountname;
        }
        
        if(typeof(form.parent_type) != 'undefined'){
            form.parent_type.value = 'Accounts';
            window.opener.jQuery('input[name="parent_type"]').trigger('change');
        }
        
        var jobdate_start = jobdate_operate.split('-');
        var jobdate = jobdate_start[2]+'-'+jobdate_start[1]+'-'+jobdate_start[0];

        if(typeof(form.date_start) != 'undefined'){
            form.date_start.value = jobdate;
        }
        if(typeof(form.due_date) != 'undefined'){
            form.due_date.value = jobdate;
        }

        if(typeof(form.time_end) != 'undefined'){
             window.opener.set_time('time_end',end_time);
        }
        
        if(typeof(form.time_start) != 'undefined'){
            window.opener.set_time('time_start',start_time);
        }
        

    }
}

function set_return_specific(jobid, job_no) {
        
        var fldName = getOpenerObj("job_no");
        var fldId = getOpenerObj("jobid");
        fldName.value = job_no;
        fldId.value = jobid;
        window.opener.document.EditView.action.value = 'EditView';
        window.opener.document.EditView.convertmode.value = 'update_job_val';
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

