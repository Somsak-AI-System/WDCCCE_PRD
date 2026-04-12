$(function(){
    var minFont = 10; // กำหนดขนาดตัวอักษรต่ำสุด
    var maxFont = 18; //  กำหนดขนาดตัวอักษรสูงสุด
    var nowFont = 14; // กำหนดขนาดตัวอักษรเริมต้น datagrid-cell datagrid-cell-c1-case_status_name
    var objSet = ['body', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'table', 'button', '.form-control', '.datagrid-cell'];

    $(objSet).each(function (i, e) {
        $(e).css("font-size", nowFont + 'px');
    });

    $(".change-font").click(function () {
        var inCase = $(this).data('type'); //console.log(inCase);
        if (inCase == "increase") {
            if (nowFont < maxFont) {
                nowFont++;
            } else {
                nowFont = maxFont;
            }
            $(objSet).each(function (i, e) {
                $(e).css("font-size", nowFont);
            });
        }
        if (inCase == "decrease") {
            if (nowFont > minFont) {
                nowFont--;
            } else {
                nowFont = minFont;
            }
            $(objSet).each(function (i, e) {
                $(e).css("font-size", nowFont);
            });
        }
    });

    $('.toggle-list').on('click', function () {
        $(this).find('i').toggleClass("fa-chevron-left fa-chevron-right");

        // var left_width = parseInt($('.list-panel').width());
        // var margin_left = parseInt($('.list-panel').css('margin-left'));
        // margin_left = margin_left>=0 ? parseInt('-'+(left_width+20)):0;
        // $('.list-panel').css({ "margin-left": margin_left});
        $('.list-panel').toggle()
        $('.inq-panel').toggleClass("col-sm-10 col-sm-12");
    });

    $.fn.loader = function(){
        var div_preloader = $('<div />', { class:'preloader' });
        var div_loader = $('<div />', { class:'loader' });
        var div_figure = $('<div />', { class:'loader__figure' });
        var div_label = $('<div />', { class:'loader__label' }).html('Loading...');

        $(div_loader).append(div_figure);
        $(div_loader).append(div_label);
        $(div_preloader).append(div_loader);
        $(this).append(div_preloader);
    }

    $.fn.unloader = function(){
        $('.preloader').hide();
        $(this).remove('.preloader');
    }

    $.alertWithSysmsg = function(rs){
        Swal.fire({
            title: rs.msg,
            text: "",
            type: "error",
            showCancelButton: false,
            confirmButtonText: "Ok",
            closeOnConfirm: true,
            footer: '<div class="row"><div class="col-12"><i  id="show-sysmsg" class="fas fa-chevron-circle-down text-info"></i></div></div>' +
            '<div id="sysmsg" style="width:100%;display:none">'+rs.sysmsg+'</div>'
        });

        $('#show-sysmsg').click(function (e) {
            $(this).toggleClass('fa-chevron-circle-down fa-chevron-circle-up')
            $('#sysmsg').toggle();
        })
    }

    $.alertSuccessRedirect = function(rs){
        Swal.fire({
            title: rs.msg,
            text: "",
            type: "success",
            showCancelButton: false,
            confirmButtonText: "Ok"
        }).then(function(result){
            var query_string = ''
            var count_primary = rs.data.length;
            $(rs.data).each(function(i,e){
                query_string += e.FieldName +DELIMITER+ e.FieldID +'='+ e.ReturnValue
                if((count_primary-1) != i) query_string += '&';
            })
            window.location.href = rs.module +'/view?'+ query_string;
        })
    }

    $.changeLang = function ($lang) {
        $.post(site_url('Api/changeLang'), {lang:$lang}, function(rs){
            location.reload();
        })
    }

    $.alertRedirect = function(rs){
        var type = rs.type!=undefined ? rs.type:'success';
        var btn_txt = rs.btn_txt!=undefined ? rs.btn_txt:'Ok';
        Swal.fire({
            title: rs.msg,
            text: "",
            type: type,
            showCancelButton: false,
            confirmButtonText: btn_txt
        }).then(function(result){
            window.location.href = rs.url;
        })
    }

    $.alertDeleteRedirect = function(rs){
        Swal.fire({
            title: rs.msg,
            text: "",
            type: "success",
            showCancelButton: false,
            confirmButtonText: "Ok"
        }).then(function(result){
            window.location.href = rs.module;
        })
    }

    $.alert = function(rs){
        Swal.fire({
            title: rs.msg,
            text: "",
            type: rs.type,
            showCancelButton: false,
            confirmButtonText: "Ok"
        })
    }

    $.editSelected = function (tabid, module) {
        var grid = $("#grid").data("kendoGrid");
        var selectedItems = grid.select();

        $(selectedItems).each(function (i,e) {
            var selectedItem = grid.dataItem(e);
            console.log(selectedItem);
        })
    }

    $.deleteSelected = function (tabid, module) {
        var grid = $("#grid").data("kendoGrid");
        var selectedItems = grid.select();

        var keyField = '';
        var recordIds = []
        $(selectedItems).each(function (i,e) {
            var selectedItem = grid.dataItem(e);
            recordIds.push(selectedItem[selectedItem.primary_key])
            keyField = selectedItem.primary_key;
        })

        if(recordIds.length === 0){
            alert('กรุณาเลือกรายการที่ต้องการลบ');
            return false;
        }

        if(confirm('ยืนยันการลบข้อมูล') === true){
            $.post(site_url('mainAction/massDelete'), {tabid, module, keyField, recordIds}, function(rs){
                if(rs.alldata.Status === RETURN_SECCESS){
                    window.location.reload()
                }
            },'json')
        }
    }

    $.addCondition = function(module, divid, data){
        var row = $('.'+ divid +'_item:last');
        var count = $(row).data('count');

        var field_value = '';
        var operator_value = '';

        count = count==undefined ? 0:count;
        count = count+1;
        var div_row = $('<div />',{ id:'row_'+count, class:'row '+ divid +'_item form-group' }).attr({ 'data-count':count });
        var div_field = $('<div />',{ class:'col-sm-3' });
        var div_operator = $('<div />',{ class:'col-sm-3' });
        var div_search = $('<div />',{ class:'col-sm-5 div-search' });
        var div_btn = $('<div />',{ class:'col-sm-1' });

        var input_field = $('<input />',{ id:'input_field_'+count, name:'input_field[]', class:'input_field form-control' }).css({ 'width':'100%', '-webkit-appearance':'media-slider!important' });
        if(data!=undefined){
            $(input_field).attr({'data-jsondata':JSON.stringify(data)});
        }
        var input_operator = $('<input />',{ id:'input_operator_'+count, name:'input_operator[]', class:'input_operator form-control' }).css({ 'width':'100%', '-webkit-appearance':'media-slider!important' });
        //var input_search = $('<input />',{ id:'input_search_'+count, name:'input_search[]', class:'form-control input_search' });
        var input_search = $('<input />',{ id:'input_search_'+count, name:'input_search[]', class:'form-control input_search' }).css({ 'width':'100%'});
        var btn_remove = $('<i />',{ class:'fa fa-trash icon-remove' }).click(function(){
            $.removeRow(this)
        })

        $(div_field).append(input_field);
        $(div_operator).append(input_operator);
        $(div_search).append(input_search);
        $(div_btn).append(btn_remove);

        $(div_row).append(div_field);
        $(div_row).append(div_operator);
        $(div_row).append(div_search);
        $(div_row).append(div_btn);

        $('#'+divid).append(div_row);

        $.post(site_url(module+'/getFieldList'), function(response){
            var field = $(input_field).kendoComboBox({
                filter: "contains",
                placeholder: "Select Field",
                dataTextField: "FieldLabel",
                dataValueField: "FieldValue",
                dataSource: {
                    data: response
                }
            }).data("kendoComboBox");

            var operator = $(input_operator).kendoComboBox({
                autoBind: false,
                filter: "contains",
                placeholder: "Select Operator",
                dataTextField: "ComparatorName",
                dataValueField: "ComparatorID",
                dataSource: [],
                select: function(e){
                    var data_item = e.dataItem;
                    var field_data = $(input_field).data("kendoComboBox").value();
                    field_data = field_data.split(DELIMITER);
                    $.post(site_url('MainInquiry/genField'), {uitype:data_item.ValueUITypeID, fieldid:field_data[1], count:count}, function(html){
                        $(div_search).html(html)
                        //console.log(data_item.ValueUITypeID);
                        switch(data_item.ValueUITypeID){
                            case'28':
                            case'45':
                                $(div_search).find('.kendo-combobox').kendoComboBox();
                                break;
                            default:
                                break;
                        }
                    })
                }
            }).data("kendoComboBox");
            field.bind("cascade", cascade);

            if(data != undefined){ //console.log(data)
                var field_value = data.FieldName+DELIMITER+data.FieldID+DELIMITER+data.UIType;
                field.value(field_value);
            }
        },'json');
    }

    $.addFilterAccountCondition = function(module, divid, data){ //console.log(data)
        var pageAction = data !== undefined && data.pageAction !== undefined ? data.pageAction:'';
        var readOnly = pageAction === 'View' ? true:false
        var row = $('.'+ divid +'_item:last');
        var count = $(row).data('count');

        var field_value = '';
        var operator_value = '';

        count = count==undefined ? 0:count;
        count = count+1;
        var div_row = $('<div />',{ id:'row_'+count, class:'row '+ divid +'_item form-group' }).attr({ 'data-count':count });
        var div_field = $('<div />',{ class:'col-sm-3' });
        var div_operator = $('<div />',{ class:'col-sm-3' });
        var div_search = $('<div />',{ class:'col-sm-3 div-search' });
        var div_order = $('<div />',{ class:'col-sm-2'});
        var div_btn = $('<div />',{ class:'col-sm-1' });

        var input_field = $('<input />',{ id:'input_field_'+count, name:'input_field[]', class:'input_field form-control', readonly:readOnly }).css({ 'width':'100%', '-webkit-appearance':'media-slider!important' });
        if(data!=undefined){
            $(input_field).attr({'data-jsondata':JSON.stringify(data)});
        }
        var input_operator = $('<input />',{ id:'input_operator_'+count, name:'input_operator[]', class:'input_operator form-control', readonly:readOnly }).css({ 'width':'100%', '-webkit-appearance':'media-slider!important' });
        var input_search = $('<input />',{ id:'input_search_'+count, name:'input_search[]', class:'form-control input_search' }).prop('readonly', readOnly);
        var input_order = $('<input />',{ id:'input_order_'+count, name:'input_order[]', class:'input_order form-control', readonly:readOnly }).css({ 'width':'100%', '-webkit-appearance':'media-slider!important' });
        var btn_remove = $('<i />',{ class:'fa fa-trash icon-remove' }).click(function(){
            $.removeRow(this)
        })

        $(div_field).append(input_field);
        $(div_operator).append(input_operator);
        $(div_search).append(input_search);
        $(div_order).append(input_order);
        if(pageAction !== 'View') $(div_btn).append(btn_remove);

        $(div_row).append(div_field);
        $(div_row).append(div_operator);
        $(div_row).append(div_search);
        $(div_row).append(div_order);
        $(div_row).append(div_btn);

        $('#'+divid).append(div_row);

        $.post(site_url(module+'/getFieldList'), {showHiddenField:'1'}, function(response){
            var field = $(input_field).kendoComboBox({
                filter: "contains",
                placeholder: "Select Field",
                dataTextField: "FieldLabel",
                dataValueField: "FieldValue",
                dataSource: {
                    data: response
                }
            }).data("kendoComboBox");

            var operator = $(input_operator).kendoComboBox({
                autoBind: false,
                filter: "contains",
                placeholder: "Select Operator",
                dataTextField: "ComparatorName",
                dataValueField: "ComparatorID",
                dataSource: [],
                select: function(e){
                    var data_item = e.dataItem;
                    var field_data = $(input_field).data("kendoComboBox").value();
                    field_data = field_data.split(DELIMITER);
                    $.post(site_url('MainInquiry/genField'), {uitype:data_item.ValueUITypeID, fieldid:field_data[1], count:count}, function(html){
                        $(div_search).html(html)
                        //console.log(data_item.ValueUITypeID);
                        switch(data_item.ValueUITypeID){
                            case'28':
                            case'45':
                                $(div_search).find('.kendo-combobox').kendoComboBox();
                                break;
                            default:
                                break;
                        }
                    })
                }
            }).data("kendoComboBox");
            field.bind("cascade", cascade);

            var order = $(input_order).kendoComboBox({
                filter: 'contains',
                placeholder: 'Select Order',
                dataTextField: 'label',
                dataValueField: 'value',
                dataSource: {
                    data: [
                        { label: 'น้อย -> มาก', value:'ASC'},
                        { label: 'มาก -> น้อย', value:'DESC'},
                    ]
                },
                value: data != undefined && data.SortCondition !== undefined ? data.SortCondition:'ASC'
            })

            if(data != undefined){ //console.log(data)
                var field_value = data.FieldName+DELIMITER+data.FieldID+DELIMITER+data.UIType;
                field.value(field_value);
            }
        },'json');
    }

    $.removeRow = function(obj){
        $(obj).parent().parent('.row').remove();
    }

    $.addFilter = function(tabid, module){
        $.post(site_url('MainFilter/viewFilter'), {tabid:tabid, module:module, action:'Add'}, function(html){
            $('#modal-view-filter .modal-body').html(html);

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });
        });
    }

    $.editFilter = function(tabid, module, cvid){
        $.post(site_url('MainFilter/viewFilter'), {tabid:tabid, module:module, cvid:cvid, action:'Edit'}, function(html){
            $('#modal-view-filter .modal-body').html(html);

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });
        });
    }

    $.dupFilter = function(tabid, module, cvid){
        $.post(site_url('MainFilter/viewFilter'), {tabid:tabid, module:module, cvid:cvid, action:'Add'}, function(html){
            $('#modal-view-filter .modal-body').html(html);

            $('.modal-dialog').draggable({
                handle: ".modal-header"
            });
        });
    }

    $.deleteFilter = function(tabid, module, cvid){
        Swal.fire({
            title: 'Confirm Delete Filter?',
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function(result){
            if (result.value) {
                $.post(site_url('MainFilter/deleteFilter'), {tabid:tabid, module:module, cvid:cvid, action:'Delete'}, function(rs){ //console.log(rs);
                    if(rs.status=='Success'){
                        $.post(site_url('MainFilter/listFilter'), {module:module, tabid:tabid, cvid:rs.cvid}, function(html){
                            $('.list-view-group').html(html)
                        });

                        $.post(site_url('MainFilter/getActiveFilter'), {module:module, tabid:tabid, cvid:rs.cvid}, function(res){
                            if(res.active_filter!=undefined){
                                $('#all_condition').html('');
                                $(res.active_filter.ConditionList).each(function(i,e){
                                    $.addCondition(module, 'all_condition', e);
                                });

                                $.createGrid(module, rs.cvid, res.active_filter.ConditionList);
                            }
                        }, 'json')

                        var grid = $("#grid").data("kendoGrid");
                        grid.destroy();
                        $("#grid").empty();
                    }else{
                        $.alert({msg:rs.msg, type:'error'})
                    }
                },'json');
            }
        })
    }

    $.changeFilter = function(obj, tabid, module, cvid){
        $('.list-group-item').removeClass('active');
        $(obj).parents('li').toggleClass('active');
        $('.container-fluid').loader();
        $.get(site_url(module+'/listColumns?cvid='+cvid), function(rs){
            if(rs.status == true){
                $.post(site_url('MainFilter/getActiveFilter'), {module:module, tabid:tabid, cvid:cvid}, function(res){ //console.log(res)
                    if(res.active_filter!=undefined){
                        $('#all_condition').html('');
                        $(res.active_filter.ConditionList).each(function(i,e){
                            // console.log(e)
                            $.addCondition(module, 'all_condition', e);
                        });
                        $('#selected_cvid').val(cvid);
                        $.createGrid(module, cvid, res.active_filter.ConditionList);
                        $('.container-fluid').unloader();
                    }
                }, 'json')

                var grid = $("#grid").data("kendoGrid");
                grid.destroy();
                $("#grid").empty();

                // $.createGrid(module, cvid);
            }else{
                $('.pagerow').unloader();
                chkSessionTimeout();
            }
        });
    }

    $.deleteRecord = function(module, querystring){
        // console.log(querystring);
        Swal.fire({
            title: CONFIRM_DELETE_DATA,
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function(result){
            if (result.value) {
                $.get(site_url(module+'/delete?'+querystring), function(rs){ console.log(rs);
                    if(rs.status==true){
                        if(rs.data == null){
                            $.alertWithSysmsg(rs)
                        }else{
                            rs.module = module;
                            $.alertDeleteRedirect(rs);
                        }
                    }else{
                        $.alertWithSysmsg(rs)
                    }
                },'json');
            }
        })
    }

    $.duplicateVisitQuickCreate = function(module, querystring){

        Swal.fire({
            title: "ยืนยันการคัดลอกข้อมูล",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Duplicate it!'
        }).then(function(result){
            window.location.href = site_url('VisitQuickCreate/duplicate?' + querystring)

        })
    }

    $.deleteRecordVisitQuickCreate = function(module, querystring,accid){
        Swal.fire({
            title: CONFIRM_DELETE_DATA,
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function(result){
            if (result.value) {
                $.get(site_url(module+'/delete?'+querystring), function(rs){ 
                    //console.log(rs);
                    if(rs.status==true){
                        if(rs.data == null){
                            $.alertWithSysmsg(rs)
                        }else{
                            rs.module = module;
                            //window.location.href = site_url('VisitQuickCreate');
                            //Reload Grid
                            $.post(site_url('VisitQuickCreate/getInqVisit'), {module: module,accountid : accid}, function (rs) {
                                if(rs.status==true) {
                                    $('#visit_div').remove();
                                    $('#visit_grid').append('<div id="visit_div"><table id="visit_table"></table></div>');
                                    $('#visit_table').kendoGrid({
                                        columns: [
                                            {
                                                field: "action",
                                                title: "Action",
                                                template: actionButtonsVisitQuick
                                            },
                                            {
                                                field: "status__302__45",
                                                title: "สถานะ",
                                                encoded: false
                                            },
                                            {
                                                field: "plandate__327__22",
                                                title: "วันที่นัดหมาย"
                                            },
                                            {
                                                field: "visitstartdate__331__22",
                                                title: "วันที่ตรวจเยี่ยม"
                                            },
                                            {
                                                field: "visittype__304__45",
                                                title: "ประเภทการตรวจเยี่ยม"
                                            }],
                                        dataSource: rs.rows,
                                        scrollable: true
                                    });
                                }else{
                                    $('#visit_div').remove();
                                    $('#visit_grid').append('<div id="visit_div"><table id="visit_table"></table></div>');
                                    $('#visit_table').kendoGrid({
                                        columns: [
                                            {
                                                field: "action",
                                                title: "Action",
                                                template: actionButtonsVisitQuick
                                            },
                                            {
                                                field: "status__302__45",
                                                title: "สถานะ",
                                                encoded: false
                                            },
                                            {
                                                field: "plandate__327__22",
                                                title: "วันที่นัดหมาย"
                                            },
                                            {
                                                field: "visitstartdate__331__22",
                                                title: "วันที่ตรวจเยี่ยม"
                                            },
                                            {
                                                field: "visittype__304__45",
                                                title: "ประเภทการตรวจเยี่ยม"
                                            }],
                                        dataSource: [],
                                        noRecords: {
                                            template: "No Data Found"
                                        },
                                        scrollable: true
                                    });
                                }

                            }, 'json')
                            //Reload Grid
                        }
                    }else{
                        $.alertWithSysmsg(rs)
                    }
                },'json');
            }
        })
    }

    $.deleteRole = function(roleid){
        Swal.fire({
            title: CONFIRM_DELETE_DATA,
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function(result){
            if (result.value) {
                $.get(site_url('Role/delete?roleid='+roleid), function(rs){ console.log(rs);
                    if(rs.status==true){
                        rs.module = 'Role';
                        $.alertDeleteRedirect(rs);
                    }else{
                        $.alertWithSysmsg(rs)
                    }
                },'json');
            }
        })
    }

    $.deleteProfile = function(profileid){
        Swal.fire({
            title: CONFIRM_DELETE_DATA,
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function(result){
            if (result.value) {
                $.get(site_url('Profile/delete?profileid='+profileid), function(rs){ console.log(rs);
                    if(rs.status==true){
                        rs.module = 'Profile';
                        $.alertDeleteRedirect(rs);
                    }else{
                        $.alertWithSysmsg(rs)
                    }
                },'json');
            }
        })
    }

    var operators = {
        '1':'eq',
        '2':'neq',
        '8':'contains'
    }

    $.createGrid = function(module, cvid, condition_list){ //{field: data.fieldid, operator:"eq", value:data.value}
        $.get(site_url(module+"/listColumns?cvid="+cvid), function(rs){ //console.log(rs)
            if(rs.status == true){
                var columns = []; // console.log(module)
                // if(module=='Ticket' || module=='Visit' || module=='ServiceCase' ){
                    columns.push({ selectable: true, width: "30px" });
                // }
                
                columns.push({ field: "action", title: " ", width: 120, sortable: false, filterable: false, template: actionButtons }); 

                var datasource = {
                    pageSize: PAGESIZE,
                    transport: {
                        read:  {
                            url: site_url(module+"/listData"),
                                dataType: "json",
                                data: {cvid:cvid}
                        }
                    },
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
                    serverPaging: true,
                    serverFiltering: true,
                    serverSorting: true
                }

                var filters = [];

                // if(module === 'AccountFilter' && meadistrictid !== '19'){
                //     filters.push({
                //         field: 'mea_district'+DELIMITER+'664'+DELIMITER+'46',
                //         operator: 'eq',
                //         value: meadistrictid
                //     });
                // }

                // if(module=='ServiceCase' ){
                //     // console.log(meadistrictid)
                //     filters.push({
                //         field: '341'+DELIMITER+'district_id'+DELIMITER+'10',
                //         operator: 'eq',
                //         value: meadistrictid
                //     });
                // }

                if(condition_list!=undefined && condition_list!=null){
                    // console.log(condition_list);
                    if(condition_list.length > 0){
                        $(condition_list).each(function (i,e) {
                            filters.push({
                                field: e.FieldName+DELIMITER+e.FieldID+DELIMITER+e.UIType,
                                operator: operators[e.ComparatorID],
                                value: e.Value
                            });
                        })
                    }
                }
                datasource.filter.filters = filters;

                
                // console.log(filters);
                $.merge(columns, rs.columns); //console.log(columns);
                $('#grid').genKendoGrid(datasource, columns);

                var grid = $("#grid").data("kendoGrid");
                //grid.autoFitColumn("action");

               //var grid = $("#grid").data("kendoGrid");

                

                grid.bind("filter", function(e) {
                    if (e.filter == null) {
                        console.log("filter has been cleared");
                    } else {
                        // console.log(e.filter.logic);
                        // console.log(e.filter.filters[0].field);
                        // console.log(e.filter.filters[0].operator);
                        // console.log(e.filter.filters[0].value);
                    }
                });

                $(window).ready(getHeight);
                $(window).on("resize", getHeight);
            }else{
                $('.pagerow').unloader();
                chkSessionTimeout();
            }
        },'json')
    }

    $.creatRelateGrid = function(data){ //console.log(data)
        $.post(site_url("MainInquiry/listColumns"), data, function(rs){
            if(rs.status == true){
                var columns = [];

                if(data.actionBtn==undefined){
                    columns.push({ field: "action", title: " ", width:"120px", sortable: false, filterable: false, template: actionButtonsRelate })
                }else{
                    columns.push({ field: "action", title: " ", width:"120px", sortable: false, filterable: false, template: actionButtonsViewOnly })
                }

                var filters = [];
                if(data.fieldid!=undefined && data.fieldid!=''){
                    filters.push({field: data.fieldid, operator:"eq", value:data.value})
                }

                var datasource = {
                    pageSize: PAGESIZE_RELATE,
                    transport: {
                        read:  {
                            url: site_url("MainInquiry/listDataVisit"),
                            dataType: "json",
                            data: data
                        }
                    },
                    filter:{
                        logic:"and",
                        filters: filters
                    },
                    schema: {
                        model: {
                            id: ""
                        },
                        data: "data",
                        total: "pagesize"
                    },
                    serverPaging: true,
                    serverFiltering: true,
                    serverSorting: true
                }
                $.merge(columns, rs.columns);
                $('#grid_tab_'+data.tabid).genKendoGrid(datasource, columns);
            }else{
                $('.pagerow').unloader();
                chkSessionTimeout();
            }
        },'json');
    }

    $.tabData = function(obj){
        var tabid = $(obj).data('tabid');
        var json = $(obj).data('json'); //console.log(json)
        var input_id = json.RelateItemList[0].FromFieldName +DELIMITER+ json.RelateItemList[0].FromFieldID;
        var fieldid = json.RelateItemList[0].FromFieldName +DELIMITER+ json.RelateItemList[0].ToFieldID; //console.log(fieldid)

        if(input_id === 'feeder__577'){
            input_id = 'feeder_id__15'
        }
        var value = $('#'+input_id).val();

        var module = $.trim(json.Name.replace(' ', ''));
        // console.log(module);

        var data = {
            tabid: tabid,
            module: module,
            fieldid: fieldid,
            value: value
        }
        $.creatRelateGrid(data);
    }

    $.popWindow = function(obj){
        var target = $(obj).data('target');
        console.log(target);
        if(target!=''){
            var width = 800;
            var height = 600;

            var left  = ($(window).width()/2)-(width/2),
                top   = ($(window).height()/2)-(height/2),
                popup = window.open (target, "popup", "width="+width+", height="+height);

            var h = $(popup).find('.pagerow').height();
        }
    }

    $.provinceData = function(num){
        var provinceid = $('#province_'+num).val();
        if(provinceid!=''){
            var districtid = $('#district_'+num).val(); //console.log(districtid)
            var subdistrictid = $('#subdistrict_'+num).val();
            $.post(site_url('Components/FieldData/getDistrict'), {provinceid:provinceid}, function(rs){
                $(rs).each(function(i,e){
                    if(e.DistrictID == districtid){
                        $('#view_district_'+num).html(e.DistrictName);
                    }
                })
            },'json')

            $.post(site_url('Components/FieldData/getSubDistrict'), {districtid:districtid}, function(rs){
                $(rs).each(function(i,e){
                    if(e.SubdistrictID == subdistrictid){
                        $('#view_subdistrict_'+num).html(e.SubdistrictName);
                    }
                })
            },'json')
        }
    }

    $.viewAssign = function () {
        var group = $('#groupid').val();
        if(group!=''){
            $('#userid').html(group);
        }
    }
});