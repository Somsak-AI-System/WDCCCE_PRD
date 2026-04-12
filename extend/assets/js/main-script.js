function gridChange(e) {
    var element = e.sender.element;
    var rows = e.sender.select();
    rows.each(function (e) {
        var grid = $(element).data("kendoGrid");
        var dataItem = grid.dataItem(this);
        //contact
        $('#contactid').val(dataItem.contactid);
        $('#contact_code').val(dataItem.contact_no);
        
        /*if (dataItem.con_contactstatus == "Active") {
            $('#contact_status').val('Active').trigger('change');
        } else if (dataItem.con_contactstatus == "In Active") {
            $('#contact_status').val('In Active').trigger('change');
        }else{
            
        }*/

        if (dataItem.con_contactstatus == "In Active") {
            $('#contact_status').val('In Active').trigger('change');
        } else{
            $('#contact_status').val('Active').trigger('change');
        }
        
        if(dataItem.con_thainametitle == null || dataItem.con_thainametitle == ''){
        	$('#title_name').val('คุณ').trigger('change');
        }else{
        	$('#title_name').val(dataItem.con_thainametitle).trigger('change');
        }
        
        
        $('#fristname_contact').val(dataItem.firstname);
        $('#lastname_contact').val(dataItem.lastname);
        $('#phone_contact').val(dataItem.mobile);
        $('#email_contact').val(dataItem.email);
        $('#lineid_contact').val(dataItem.line_id);
        $('#facebook_contact').val(dataItem.facebook);
        $('#remark_contact').val(dataItem.remark);

        $('#dateupdate_contact').val( moment(dataItem.register_date).format('DD/MM/YYYY H:mm:ss') );
        if (dataItem.emotion_details == "Natured") {
            $('#natured').prop("checked", true);
        }else if(dataItem.emotion_details == "Normal") {
            $('#normal').prop("checked", true);
        }else if (dataItem.emotion_details == "Morose") {
            $('#morose').prop("checked", true);
        }else{
            console.log("emotion false");
        }

        //console.log(dataItem.contact_type);
        if (dataItem.contact_type == "--None--" || dataItem.contact_type == "null" || dataItem.contact_type == "" || dataItem.contact_type == null) {
            $('#sitecode_contact').val("--None--").trigger('change');
        }else if (dataItem.contact_type == "Owner") {
            $('#sitecode_contact').val("Owner").trigger('change');
        }else if (dataItem.contact_type == "Contractor-Company") {
            $('#sitecode_contact').val("Contractor-Company").trigger('change');
        }else if (dataItem.contact_type == "Contractor-Individual") {
            $('#sitecode_contact').val("Contractor-Individual").trigger('change');
        }

        //billtoinformation
        $('#companyname').val(dataItem.accountname);
        $('#accountid').val(dataItem.accountid);
        $('#brach').val(dataItem.branch);
        $('#taxno').val(dataItem.taxpayer_identification_no_bill_to);
        //$('#telephone').val(dataItem.mobile);
        $('#telephone').val(dataItem.account_phone);
        $('#address').val(dataItem.bill_to_address);
        $('#contact_person').val(dataItem.contact_person);
        $('#contact_tel').val(dataItem.contact_tel);
        $('#address_bill_to').val(dataItem.mailing_address);

        //ordermanagement
        $('#account_name').val(dataItem.accountname);
        $('#contact_name').val(dataItem.firstname);
        $('#address_order').val(dataItem.bill_to_address);
        $('#contact_no_order').val(dataItem.contact_no);
        $('#telephone_order').val(dataItem.mobile);
        $('#tax_address').val(dataItem.bill_to_address);
        $('#mailing_address').val(dataItem.mailing_address);
        $('#taxpayer_identification_no_bill_to').val(dataItem.taxpayer_identification_no_bill_to);
        $('#contact_person_order').val(dataItem.contact_person);
        $('#contact_tel_order').val(dataItem.contact_tel);
        $('#first_name_th').val(dataItem.firstname);
        $('#billing_name').val(dataItem.accountname);
        $('#corporate_registration_number_crn').val(dataItem.corporate_registration_number_crn);  

        //searching
        $('#contactcode_searching').val(dataItem.contact_no);
        // $('#contactdate_searching').val(dataItem.phone);
        $('#contactname_searching').val(dataItem.firstname);
        $('#contacttel_searching').val(dataItem.phone);
        $('#lineid_searching').val(dataItem.line_id);
        $('#facebook_searching').val(dataItem.facebook);

        //savecase
        $('#tel_savecase').val(dataItem.mobile);
        $('#email_savecase').val(dataItem.email);
        $('#line_id_savecase').val(dataItem.line_id);
        $('#facebook_savecase').val(dataItem.facebook);

        var modalAccountsearch = document.getElementById("modalAccountsearch");
        modalAccountsearch.style.display = "none";
        console.log("click");

        $.post(site_url('home/gethistorycase'), {accountid: dataItem.accountid, contactid: dataItem.contactid}, function (rs) { 

            console.log(rs);

            var datasource ={
                pageSize: 10,
                filter:{
                    logic:"and",
                    filters: []
                },
                schema: {
                    model: {
                        id: ""
                    },
                    data: "data",
                    total: "pagesize"
                },

                serverPaging: false,
                serverFiltering: false,
                serverSorting: false
            }

            var columns = [
                {
                    field: "info",
                    title : "More info",
                    template: "<div onclick='$.clickPopupHistoryCase(this);'><i class='fa fa-info-circle k-grid-info'></i></div>",
                    width: 70,
                }, {
                    field: "ticket_no",
                    title : "Case No.",
                    width: 100
                }, {
                    field: "contact_no",
                    title: "Contact ID",
                    width: 100,
                }, {
                    field: "firstname",
                    title: "Name",
                    width: 100,
                }, {
                    field: "case_date",
                    title: "Case Date",
                    template: "#= kendo.toString(kendo.parseDate(case_date, 'yyyy-MM-dd'), 'dd/MM/yyyy') #",
                    width: 100
                }, {
                    field: "task_name",
                    title: "Task Category",
                    width: 100
                }, {
                    field: "case_status",
                    title: "Status",
                    width: 70
                }, {
                    field: "description",
                    title: "Description",
                    width: 100
                }, {
                    field: "user_name",
                    title: "Handled by",
                    width: 100
                }
            ];

            $('#grid_historycase').genKendoGrid(datasource,columns);

            $("#grid_historycase").data('kendoGrid').dataSource.data([]);
            var grid_historycase = $('#grid_historycase').data('kendoGrid');
            $.each(rs.data, function( key, value ) {
                grid_historycase.dataSource.add({
                    ticket_no: value.ticket_no,
                    case_date: value.case_date,
                    task_name: value.task_name,
                    case_status: value.case_status,
                    case_detail: value.case_detail,
                    task_name: value.task_name,
                    priority_case: value.priority_case,
                    description: value.description,
                    notes: value.notes,
                    contact_channel: value.contact_channel,
                    response: value.response,
                    handled_by: value.handled_by,
                    createdtime: value.createdtime,
                    modifiedby: value.modifiedby,
                    modifiedtime: value.modifiedtime,
                    contact_name: value.contact_name,
                    tel: value.tel,
                    email: value.email,
                    line_id: value.line_id,
                    facebook: value.facebook,
                    case_date: value.case_date,
                    case_time: value.case_time,
                    date_of_execution: value.date_of_execution,
                    process_time: value.process_time,
                    date_completed: value.date_completed,
                    time_completed: value.time_completed,
                    date_cancelled: value.date_cancelled,
                    time_cancelled: value.time_cancelled,
                    user_name: value.user_name,
                    contact_no: value.contact_no,
                    firstname: value.firstname,
                    image: value.image,
                    full_name: value.full_name,
                    accountname: value.accountname,
                });
          });


        },'json');

        $.post(site_url('home/gethistoryorder'), {accountid: dataItem.accountid, contactid: dataItem.contactid}, function (rs) { 

            console.log(rs);
            // console.log('test');
            // console.log(rs.data[0]);

            // var datasource = rs.data;
            // console.log(datasource);

            var datasource = {
                pageSize: 10,
                filter:{
                    logic:"and",
                    filters: []
                },
                schema: {
                    model: {
                        id: ""
                    },
                    data: "data",
                    total: "pagesize"
                },

                serverPaging: false,
                serverFiltering: false,
                serverSorting: false
            }

            var columns = [
                {
                    field: "orderno",
                    title: "Order No.",
                    width: 100,
                },
                {
                    field: "project_address",
                    title: "Project / Address",
                    width: 100,
                },
                {
                    field: "plant_name",
                    title: "Plant Name",
                    width: 100,
                },
                {
                    field: "order_status_order",
                    title: "Status",
                    width: 100,
                },
            ];

            $('#grid_historyorder').genKendoGrid(datasource,columns);

            $("#grid_historyorder").data('kendoGrid').dataSource.data([]);
            var grid_historyorder = $('#grid_historyorder').data('kendoGrid');
            $.each(rs.data, function( key, value ) {
                grid_historyorder.dataSource.add({
                   orderno: value.order_no,
                   order_status_order: value.order_status_order,
                   project_address: value.project_address,
                   order_status_order: value.order_status_order,
                   plant_name: value.plant_name,

                });
          });


        },'json');

        $.clickPopupHistoryCase = function(e) {
            console.log("test");
            var modalPopuphistorycase = document.getElementById("modalPopuphistorycase");
            var closepopuphistorycase = document.getElementById("closepopuphistorycase");

            modalPopuphistorycase.style.display = "block";

            closepopuphistorycase.onclick = function() {
                modalPopuphistorycase.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modalPopuphistorycase) {
                    modalPopuphistorycase.style.display = "none";
                }
            }

            var grid = $("#grid_historycase").data("kendoGrid");
            var dataItem = grid.dataItem($(e).closest("tr"));
            console.log(dataItem);

            $('#caseno').val(dataItem.ticket_no);
            $('#taskname').val(dataItem.task_name);
            $('#statushistorycase').val(dataItem.case_status);
            $('#priority').val(dataItem.priority_case);
            $('#description_case').val(dataItem.description);
            $('#notes_case').val(dataItem.notes);
            $('#contactchannel_case').val(dataItem.contact_channel);
            $('#response_case').val(dataItem.response);
            $('#handledby_case').val(dataItem.user_name);
            $('#createdtime_case').val(dataItem.createdtime);
            $('#modifiedby_case').val(dataItem.modifiedby);
            $('#modifiedtime_case').val(dataItem.modifiedtime);

            $('#contact_name_case').val(dataItem.full_name);
            $('#account_name_case').val(dataItem.accountname);
            $('#tel_case').val(dataItem.tel);
            $('#email_case').val(dataItem.email);
            $('#line_id_case').val(dataItem.line_id);
            $('#facebook_case').val(dataItem.facebook);

            if (dataItem.case_date == "0000-00-00") {
                $('#case_date_case').val("");
            } else {
                $('#case_date_case').val(moment(dataItem.case_date).format('DD/MM/YYYY'));
            }

            $('#case_time_case').val(dataItem.case_time);

            if (dataItem.date_of_execution == "0000-00-00") {
                $('#date_of_execution_case').val("");
            } else {
                // $('#date_of_execution_case').val(moment(dataItem.date_of_execution).format('DD/MM/YYYY'));
                $('#date_of_execution_case').val(moment(dataItem.date_of_execution).format('DD/MM/YYYY'));
            }

            $('#process_time_case').val(dataItem.process_time);


            if (dataItem.date_completed == "0000-00-00") {
                $('#date_completed_case').val("");
            } else {
                $('#date_completed_case').val(moment(dataItem.date_completed).format('DD/MM/YYYY'));
            }

            $('#time_completed_case').val(dataItem.time_completed);

            if (dataItem.date_cancelled == "0000-00-00") {
                $('#date_cancelled_case').val("");
            } else {
                $('#date_cancelled_case').val(moment(dataItem.date_cancelled).format('DD/MM/YYYY'));
            }

            $('#time_cancelled_case').val(dataItem.time_cancelled);

            var image = dataItem.image;

            for (var i = 0; i < image.length; i++) {
                console.log(image[i]['name']);
                console.log(image[i]['path']);
                $('#file_upload_name').val(image[i]['name']);
                $('#file_upload_path').val(image[i]['path']);
            }

            // console.log(image['name']);

        }

        // var url ="<?php echo site_url('home/gethistorycase'); ?>";

        // $.ajax(url, {
        //     type: 'POST',
        //     data: "",
        //     success: function (data){
        //         var result = jQuery.parseJSON(data);
        //         console.log(result.data);
        //         if(result['Type'] =='S'){
        //             console.log("success");
        //         }else{
        //             console.log("e");
        //         }
        //     },
        //     error: function (data){
        //         console.log("f");
        //     }
        // });

    });

}

function onChange(e) {
    var rows = e.sender.select();
    rows.each(function(e) {
        var grid = $("#grid_partner").data("kendoGrid");
        var dataItem = grid.dataItem(this);
        var selectedRows = grid.element.find(".k-state-selected");

        var totalSelectedRows = selectedRows.length;
        console.log(totalSelectedRows);

        if (totalSelectedRows > 2) {
            console.log("r");
        }

        console.log(dataItem);
        // console.log(dataItem.plantid);
        // console.log(dataItem.plant_name);
        // console.log(dataItem.pricelist_no);

        $("#plantid").val(dataItem.plantid);
        $("#plant_id").val(dataItem.plant_id);
        $("#plant_name").val(dataItem.plant_name);
        $("#pricelist_no").val(dataItem.pricelist_no);

        $("#zone").val(dataItem.zone);
        $("#km").val(dataItem.km);
        $("#carsize").val(dataItem.truck_size);
        $("#lp").val(dataItem.lp);
        $("#dlv_c").val(dataItem.dlv_c);
        $("#dlv_cvat").val(dataItem.dlv_c_vat);
        $("#dlv_pvat").val(dataItem.dlv_p_vat);
        $("#min").val(dataItem.min);
        $("#c_cost").val(dataItem.c_cost);
        $("#ProductId").val(dataItem.product_id);
        $("#productName1").val(dataItem.productname);
        $("#profit").val(dataItem.profit);
        $("#strength_order").val(dataItem.strength);
        $("#c_price_vat").val(dataItem.c_price_vat);
        $("#dlv_c").val(dataItem.dlv_c);
        $("#dlv_c_vat").val(dataItem.dlv_c_vat);
        $('#dlv_p_vat').val(dataItem.dlv_p_vat);
        $('#discount').val(dataItem.lp_disc);
        $('#afterdiscount1').val(dataItem.c_cost);
        $('#priceperunit1').val(dataItem.c_price_vat);

        $('#vender_plant').val(dataItem.vendor_name);
        $('#vendor_bank').val(dataItem.vendor_bank);
        $('#vendor_bank_account').val(dataItem.vendor_bank_account);
        $('#vendor_register_address').val(dataItem.vendor_register_address);

    });

}

function onChangeVip(e) {
    var rows = e.sender.select();
    rows.each(function(e) {
        var grid = $("#grid_vippartner").data("kendoGrid");
        var dataItem = grid.dataItem(this);
        // var selectedRows = grid.element.find(".k-state-selected");
        console.log(dataItem);
        var selectedRows = grid.element.find(".k-state-selected");

        var totalSelectedRows = selectedRows.length;
        console.log(totalSelectedRows);

        // if (totalSelectedRows > 2) {
        //     console.log("r");
        // }


        // console.log(dataItem);
        // console.log(dataItem.plantid);
        // console.log(dataItem.plant_name);
        // console.log(dataItem.pricelist_no);

        $("#plantid").val(dataItem.plantid);
        $("#plant_id").val(dataItem.plant_id);
        $("#plant_name").val(dataItem.plant_name);
        $("#pricelist_no").val(dataItem.pricelist_no);

        $("#zone").val(dataItem.zone);
        $("#km").val(dataItem.km);
        $("#carsize").val(dataItem.truck_size);
        $("#lp").val(dataItem.lp);
        $("#dlv_c").val(dataItem.dlv_c);
        $("#dlv_cvat").val(dataItem.dlv_c_vat);
        $("#dlv_pvat").val(dataItem.dlv_p_vat);
        $("#min").val(dataItem.min);
        $("#c_cost").val(dataItem.c_cost);
        $("#ProductId").val(dataItem.product_id);
        $("#productName1").val(dataItem.productname);
        $("#profit").val(dataItem.profit);
        $("#strength_order").val(dataItem.strength);
        $("#c_price_vat").val(dataItem.c_price_vat);
        $("#dlv_c").val(dataItem.dlv_c);
        $("#dlv_c_vat").val(dataItem.dlv_c_vat);
        $('#dlv_p_vat').val(dataItem.dlv_p_vat);
        $('#discount').val(dataItem.lp_disc);
        $('#afterdiscount1').val(dataItem.c_cost);
        $('#priceperunit1').val(dataItem.c_price_vat);

        $('#vender_plant').val(dataItem.vendor_name);
        $('#vendor_bank').val(dataItem.vendor_bank);
        $('#vendor_bank_account').val(dataItem.vendor_bank_account);
        $('#vendor_register_address').val(dataItem.vendor_register_address);
        

    });
    
}