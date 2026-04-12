$(function () {
    var CONTAINS = 'บางส่วน';
    var EQUAL_TO = 'เท่ากับ';
    var NOT_EQUAL_TO = 'ไม่เท่ากับ';

    kendo.ui.FilterMenu.fn.options.operators.string = {
        contains: CONTAINS,
        eq: EQUAL_TO,
        neq: NOT_EQUAL_TO,
        gteq: 'Greater than or equal',
        lteq: 'Less than or equal'
    };

    kendo.ui.FilterCell.fn.options.template = function (e) {
        e.element.kendoAutoComplete({
            serverFiltering: false,
            valuePrimitive: true,
            noDataTemplate: ''
        });
    }

    $.fn.Datepicker = function () {
        $(this).kendoDatePicker({
            format: "dd/MM/yyyy",
            dateInput: false
        });

        // $(this).getKendoDatePicker()._dateInput.setOptions({
        //     messages: {
        //         year: "",
        //         month: "",
        //         day: "",
        //     }
        // });

        var id = $(this).attr('id');

        var datepicker = $(this).data("kendoDatePicker");

        var mindate = $(this).data('mindate');
        if (mindate != undefined && mindate != '') {
            mindate = mindate.split('-');
            datepicker.min(new Date(mindate[0], mindate[1] - 1, mindate[2]));
        }

        var maxdate = $(this).data('maxdate');
        if (maxdate != undefined && maxdate != '') {
            maxdate = maxdate.split('-');
            datepicker.max(new Date(maxdate[0], maxdate[1] - 1, maxdate[2]));
        }
    }

    $.fn.DateMonthpicker = function () {
        $(this).kendoDatePicker({
            depth: "year",
            start: "year",
            format: "01/MM/yyyy",
            parseFormat: "01/MM/yyyy",
            dateInput: false
        });
    }

    $.fn.DatetimePicker = function () {
        var format = $(this).data('format');
        format = format == undefined ? 'dd/MM/yyyy HH:mm:ss' : format;
        $(this).kendoDateTimePicker({
            format: format,
            timeFormat: "HH:mm",
            interval: 30,
            dateInput: false
        });

        //$(this).prop('readonly', true);
        var datepicker = $(this).data("kendoDateTimePicker");

        var mindate = $(this).data('mindate');
        if (mindate != undefined && mindate != '') {
            mindate = mindate.split('-');
            datepicker.min(new Date(mindate[0], mindate[1] - 1, mindate[2]));
        }

        var maxdate = $(this).data('maxdate');
        if (maxdate != undefined && maxdate != '') {
            maxdate = maxdate.split('-');
            datepicker.max(new Date(maxdate[0], maxdate[1] - 1, maxdate[2]));
        }
        // var name = $(this).prop('name')
        // console.log(name, mindate, maxdate)
    }

    $.fn.Timepicker = function () {
        var format = $(this).data('timeformat')
        $(this).kendoTimePicker({
            dateInput: true,
            format: format
        });
    }

    $.fn.Combobox = function () {
        var target = $(this).data('target');
        var options = {
            filter: "contains",
            placeholder: LBL_PLEASE_SELECT,
            dataTextField: "label",
            dataValueField: "value",
            autoBind: false,
            change: function (e) {
                if (e.sender.value() && e.sender.selectedIndex == -1) {
                    return false;
                }
            }
        };
        var field = $(this).kendoComboBox(options).data('kendoComboBox');
        var value = field.value();

        if (target != '') {
            field.bind("cascade", dynamicCascade);
        }
    }

    $.fn.ComboboxRelate = function () {
        var uitype = $(this).data('uitype');
        var value = $(this).data('value');

        var options = {
            filter: "contains",
            placeholder: LBL_PLEASE_SELECT,
            dataTextField: "label",
            dataValueField: "value",
            autoBind: false
        };
        var field = $(this).kendoComboBox(options).data('kendoComboBox');

        switch (uitype) {
            case 1003:
                field.bind("cascade", provinceToDistrict);
                break;
            case 1004:
                field.bind("cascade", districtToSub);
                break;
            case 1005:
                field.bind("cascade", subdisttictToPostalcode);
                break;
            default:
                break;
        }
    }

    $.fn.ComboboxAssign = function () {
        $(this).kendoComboBox({
            placeholder: LBL_PLEASE_SELECT,
            dataTextField: "label",
            dataValueField: "value",
            filter: "contains",
            autoBind: false,
            minLength: 1,
            dataSource: {
                // type: "type",
                //serverFiltering: true,
                transport: {
                    read: {
                        url: site_url('Components/FieldData/Assignto')
                    }
                },
                group: {field: "type"}
            },
            select: selectAssignTo
        });
    }

    $.fn.ComboboxRemoteData = function (url) {
        $(this).kendoComboBox({
            placeholder: LBL_PLEASE_SELECT,
            dataTextField: "label",
            dataValueField: "value",
            filter: "contains",
            autoBind: false,
            minLength: 1,
            dataSource: {
                // type: "type",
                //serverFiltering: true,
                transport: {
                    read: {
                        url: url
                    }
                },
                group: {field: "type"}
            }
        });
        var value = $(this).data('value'); //console.log(value);
        if (value != '') {
            $(this).data('kendoComboBox').value(value);
        }
    }

    $.fn.MultiSelect = function () {
        $(this).kendoMultiSelect();
    }

    $.fn.Autocomplete = function () {
        $(this).kendoAutoComplete({
            dataSource: {
                serverFiltering: true,
                transport: {
                    read: {
                        dataType: "json",
                        type: 'POST',
                        data: {key: 'value'},
                        url: site_url('Api/customData')
                    }
                }
            },
            dataTextField: 'FirstName',
            height: 400,
            minLength: 3,
            filter: "contains",
            placeholder: "",
            select: function (e) {
                var dataItem = this.dataItem(e.item.index());
                console.log(dataItem)
                var item = e.item;
                console.log(item)
                var text = item.text();
                console.log(text);
            }
            // ,separator: ", "
        });
    }

    $.fn.Uploadfiles = function () {
        var name = $(this).data('name'); //console.log(name);
        var path = $(this).data('path');
        var multiple = $(this).data('multiple');
        var extensions = $(this).data('ext');
        var files = $(this).data('files'); //console.log(files)
        var readonly = $(this).data('readonly');
        extensions = extensions.split(',');

        var options = {
            async: {
                saveUrl: site_url('Uploads/upload?path=' + path),
                removeUrl: site_url('Uploads/uploadRemove?path=' + path),
                autoUpload: true
            },
            files: files,
            // cancel: onCancel,
            // complete: onUploadComplete,
            // error: onUploadError,
            // progress: onUploadProgress,
            // remove: onUploadRemove,
            // select: onUploadSelect,
            success: onUploadSuccess,
            // upload: onUpload,
            multiple: multiple,
            // template: '<span class="k-progress"></span>'+
            //     '<span class="k-file-extension-wrapper">'+
            //     '<span class="k-file-extension">#=files[0].extension#</span>'+
            //     '<span class="k-file-state">uploaded</span>'+
            //     '</span>'+
            //     '<span class="k-file-name-size-wrapper">'+
            //     '<span class="k-file-name" title="#=name#">#=name#</span>'+
            //     '<input type="text" name="'+name+'[]" value="#=name#">'+
            //     '<span class="k-file-size">#=files[0]#</span>'+
            //     '</span>'+
            //     '<strong class="k-upload-status">'+
            //     '<button type="button" class="k-button k-upload-action" aria-label="Remove">'+
            //     '<span class="k-icon k-i-close k-i-x" title="Remove"></span>'+
            //     '</button>'+
            //     '</strong>',
            validation: {
                maxFileSize: 5120000 // 5 MB
            }
        }

        if (extensions[0] != '*') {
            options.validation.allowedExtensions = extensions;
        }

        var upload = $(this).kendoUpload(options).data('kendoUpload');
        if (readonly == 'readonly') upload.disable();

        var li = $('li.k-file');
        $(li).each(function (i, e) { //console.log(files[i])
            if (files[i] != undefined) {
                var img_value = files[i].Path + DELIMITER + files[i].Name + DELIMITER + files[i].Type;
                $(e).append('<input type="hidden" name="' + name + '[]" value="' + img_value + '">');
            }
        })
    }

    $.fn.TextEditor = function () {
        $(this).kendoEditor({
            resizable: true,
            tools: [ //All tools
                "bold", "italic", "underline", "strikethrough", "justifyLeft", "justifyCenter", "justifyRight",
                "justifyFull", "insertUnorderedList", "insertOrderedList", "indent", "outdent", "createLink",
                "unlink", "insertImage", "tableWizard", "createTable", "foreColor", "backColor",
                "addRowAbove", "addRowBelow", "addColumnLeft", "addColumnRight", "deleteRow", "deleteColumn", "fontName",
                {
                    name: "fontSize",
                    items: [
                        {text: "14", value: "14pt"},
                        {text: "16", value: "16pt"},
                        {text: "18", value: "18pt"},
                        {text: "20", value: "20pt"}
                    ]
                }
                // {
                //     name: "fontName",
                //     items: [
                //         { text: "Andale Mono", value: "Andale Mono"},
                //         { text: "Arial", value: "Arial"},
                //         { text: "Arial Black", value: "Arial Black" },
                //         { text: "Book Antiqua", value: "Book Antiqua" },
                //         { text: "Comic Sans MS", value: "Comic Sans MS" },
                //         { text: "Courier New", value: "Courier New" },
                //         { text: "TH SarabunPSK", value:".editor-font1" }
                //     ]
                // }, "viewHtml"
            ]
            // "insertFile", "subscript", "superscript", "viewHtml", "formatting", "cleanFormatting", "print"
            // ,imageBrowser: {
            //     messages: {
            //         dropFilesHere: "Drop files here"
            //     },
            //     transport: {
            //         read: "uploads/ImageBrowser",
            //         destroy: {
            //             url: "/kendo-ui/service/ImageBrowser/Destroy",
            //             type: "POST"
            //         },
            //         create: {
            //             url: "/kendo-ui/service/ImageBrowser/Create",
            //             type: "POST"
            //         },
            //         thumbnailUrl: "/kendo-ui/service/ImageBrowser/Thumbnail",
            //         uploadUrl: "/kendo-ui/service/ImageBrowser/Upload",
            //         imageUrl: "/kendo-ui/service/ImageBrowser/Image?path={0}"
            //     }
            // }
        });
    }

    $.fn.PopWindow = function () {
        $(this).kendoWindow({
            width: "90%",
            title: "Advance Search",
            visible: false,
            resizable: false,
            draggable: true,
            modal: true,
            actions: ["Pin", "Minimize", "Maximize", "Close"],
            position: {top: 50, left: "5%"},
            close: onClose
        }).data("kendoWindow");
    }

    function strip_html_tags(str) {
        if ((str === null) || (str === '') || (str === undefined)) {
            return '';
        } else {
            str = str.toString();
        }

        return str.replace(/<[^>]*>/g, '');
    }

    $.exportDataExcel = function (module) {
        var grid = $("#grid").data("kendoGrid");
       
        // var data = grid.dataSource.data(); console.log(data); return false;

        /*        var rows = grid.select();

         var ticket_ids = [];

         rows.each(function(i, e) {
         var grid = $("#grid").data("kendoGrid");
         var dataItem = grid.dataItem(this);
         console.log(dataItem)
         if(dataItem.ticketid__45__10 == undefined){
         return false;
         }else{
         if(dataItem.ticketid__45__10 != ''){

         ticket_ids.push({
         record_id: dataItem.ticketid__45__10
         });
         }
         }

         });*/
        // console.log(ticket_ids);

        /*        var trs = $("#grid").find('tr');
         for (var i = 0; i < trs.length; i++) {
         if ($(trs[i]).find(":checkbox").is(":checked")) {
         var dataItem = grid.dataItem(trs[i]);
         }
         }*/

        if (grid != undefined) {
            grid.bind("excelExport", function (e) {
                var sheet = e.workbook.sheets[0];
                var gridColumns = grid.columns;
                var gridData = grid.select();
                var data = []

                var columns = gridColumns.map(function (col) {
                    return {
                        value: col.title ? col.title : col.field,
                        autoWidth: true,
                        background: "#7a7a7a",
                        color: "#fff"
                    };
                });

                gridData.each(function (index, row) {
                    var selectedItem = grid.dataItem(row);
                    data.push(selectedItem);
                });

                if (data.length === 0) {
                    data = grid.dataSource.data();
                }

                var rows = [{cells: columns, type: "header"}];

                var newRow = [];
                rows.map(function(item, index){
                    if(item.type === 'header'){
                        var cells = []
                        item.cells.find(function(v, i){
                            if(v.value !== undefined && v.value !== ' '){
                                cells.push(v)
                            }
                        })
                        item.cells = cells;
                    }
                    newRow.push(item)
                })

                // console.log(newRow)
                for (var i = 0; i < data.length; i++) {
                    // console.log(data[i]);
                    var rowCells = [];
                    for (var j = 0; j < gridColumns.length; j++) {
                        var cellValue = strip_html_tags(data[i][gridColumns[j].field]);
                        if(j > 1) rowCells.push({value: cellValue});
                    }
                    newRow.push({cells: rowCells, type: "data"});
                }
                // console.log(newRow)

                // var demoRow = [
                //     {
                //         cells: [
                //             {value: 'column 1', autoWidth: true,
                //                 background: "#7a7a7a",
                //                 color: "#fff"}, {value: 'column 1', autoWidth: true,
                //                 background: "#7a7a7a",
                //                 color: "#fff"}, {value: 'column 1', autoWidth: true,
                //                 background: "#7a7a7a",
                //                 color: "#fff"}, {value: 'column 1', autoWidth: true,
                //                 background: "#7a7a7a",
                //                 color: "#fff"}
                //         ],
                //         type: 'header'
                //     },
                //     {
                //         cells: [
                //             {value: 'data 1'}, {value: 'data 2'}, {value: 'data 3'}, {value: 'data 4'}
                //         ],
                //         type: 'data'
                //     },{
                //         cells: [
                //             {value: 'demo 1'}, {value: 'demo 2'}, {value: 'demo 3'}, {value: 'demo 4'}
                //         ],
                //         type: 'data'
                //     }
                // ]

                sheet.rows = newRow;

                var time = new Date().getTime();
                var filename = module + '_' + time;
                e.workbook.fileName = filename + ".xlsx";
            });
            grid.saveAsExcel();
        }
    }

    $.exportDataPDF = function (module, tabid) {
        var url = $('#form_adv_search').attr('action');
        var form_data = genFormADVData();
        var grid = $("#grid").data("kendoGrid");
        var rows = grid.select();

        var ticket_ids = [];

        rows.each(function (i, e) {
            var grid = $("#grid").data("kendoGrid");
            var dataItem = grid.dataItem(this);
            // console.log(dataItem);
            // alert(ticket_ids); return false;
            if (dataItem.ticketid__45__10 == undefined) {
                return false;
            } else {
                if (dataItem.ticketid__45__10 != '') {

                    ticket_ids.push({
                        record_id: dataItem.ticketid__45__10
                    });
                }
            }
            // console.log(ticket_ids);
        }); //return false;

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: form_data,
            success: function (rs) { //console.log(rs);
                var cvid = $('#selected_cvid').val();
                var params = $.param({module: module, tabid: tabid, cvid: cvid, filters: rs, ticketids: ticket_ids});
                console.log(params);
                var pdf_url = site_url('Components/PdfGenerator/pdfInquiry?' + params);
                window.open(pdf_url, '_blank');
                try {

                } catch (err) {

                }

            }
        });
    }

    $.exportDataPDFView = function (module, tabid, query_string) {
        var pdf_url = site_url('Components/PdfGenerator/pdfView?module=' + module + '&tabid=' + tabid + '&' + query_string);
        window.open(pdf_url, '_blank');
    }

    $.exportOut = function (module, tabid, query_string) {
        var pdf_url = site_url('ServiceCase/exportView?module=' + module + '&tabid=' + tabid);
        window.open(pdf_url, '_blank');
    }

    $.fn.genKendoGrid = function (datasource, columns) {
        $(this).kendoGrid({
            dataSource: datasource,
            filterable: {
                mode: "row",
                operators: {
                    string: {
                        contains: CONTAINS,
                        eq: EQUAL_TO,
                        neq: EQUAL_TO,
                    }
                }
            },
            pageable: {
                pageSizes: [10, 20, 30, 40, 50, 100, 500, 1000],
            },
            scrollable: true,
            persistSelection: true,
            resizable: true,
            sortable: true,
            columns: columns,
            noRecords: {
                template: "No data"
            },
        });
        
    }
    

    $.fn.genKendoGridPartner = function (datasource, columns) {
        $(this).kendoGrid({
            dataSource: datasource,
            filterable: {
                mode: "row",
                operators: {
                    string: {
                        contains: CONTAINS,
                        eq: EQUAL_TO,
                        neq: EQUAL_TO,
                    }
                }
            },
            pageable: {
                pageSizes: [20, 30, 40, 50],
            },
            scrollable: true,
            persistSelection: true,
            resizable: true,
            sortable: false,
            change: onChange,
            // selectable: "row",
            columns: columns
        });
    }

    $.fn.genKendoGridVipPartner = function (datasource, columns) {
        $(this).kendoGrid({
            dataSource: datasource,
            filterable: {
                mode: "row",
                operators: {
                    string: {
                        contains: CONTAINS,
                        eq: EQUAL_TO,
                        neq: EQUAL_TO,
                    }
                }
            },
            pageable: {
                pageSizes: [20, 30, 40, 50],
            },
            scrollable: true,
            persistSelection: true,
            resizable: true,
            sortable: false,
            change: onChangeVip,
            // selectable: "row",
            columns: columns
        });
    }

    $.fn.genKendoGridPopup = function (datasource, columns) {
        $(this).kendoGrid({
            dataSource: datasource,
            filterable: {
                mode: "row",
                operators: {
                    string: {
                        contains: CONTAINS,
                        eq: EQUAL_TO,
                        neq: NOT_EQUAL_TO
                    }
                }
            },
            pageable: {
                pageSizes: [20, 30, 40, 50],
            },
            scrollable: {
                virtual: true
            },
            pageable: true,
            scrollable: true,

            serverPaging: true,
            serverSorting: true,
            /*scrollable: {
                virtual: true
            },*/
            
            persistSelection: true,
            resizable: true,
            sortable: false,
            change: gridChange,
            selectable: "row",
            columns: columns
        });
    }

    $.fn.genKendoGridPopupimport = function (datasource, columns) {
        $(this).kendoGrid({
            dataSource: datasource,
            filterable: {
                mode: "row",
                /*operators: {
                    string: {
                        contains: CONTAINS,
                        eq: EQUAL_TO,
                        neq: NOT_EQUAL_TO
                    }
                }*/
                operators: {
                    string: {
                        contains: CONTAINS,
                        eq: EQUAL_TO,
                        neq: NOT_EQUAL_TO,
                        gteq: 'Greater than or equal',
                        lteq: 'Less than or equal'
                    }
                }
            },
            pageable: {
                pageSizes: [20, 30, 40, 50],
            },
            pageable: true,
            scrollable: true,
            persistSelection: true,
            resizable: true,
            sortable: false,
        });
    }

    $.fn.genKendoGridPopupMult = function (datasource, columns) {
        $(this).kendoGrid({
            dataSource: datasource,
            filterable: {
                mode: "row",
                operators: {
                    string: {
                        contains: CONTAINS,
                        eq: EQUAL_TO,
                        neq: NOT_EQUAL_TO
                    }
                }
            },
            pageable: {
                pageSizes: [20, 30, 40, 50],
            },
            scrollable: true,
            persistSelection: true,
            resizable: true,
            sortable: false,
            // change: gridChangeMult,
            // selectable: "multiple cell",
            columns: columns
        });
    }

    $.extend(true, kendo.ui.validator, {
        messages: {
            //configure the messages here
            //docs.kendoui.com/api/framework/validator#configuration-messages
            // defines a message for the 'custom' validation rule
            custom: "Please enter valid value for my custom rule",

            // overrides the built-in message for the required rule
            required: "This field is required",

            // overrides the built-in message for the email rule
            // with a custom function that returns the actual message
            // email: function(input) {
            //     return getMessage(input);
            // }
        }
    });
    // function getMessage(input) {
    //     return input.data("message");
    // }

    $.fn.serializeObject = function(){

        var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push":     /^$/,
                "fixed":    /^\d+$/,
                "named":    /^[a-zA-Z0-9_]+$/
            };


        this.build = function(base, key, value){
            base[key] = value;
            return base;
        };

        this.push_counter = function(key){
            if(push_counters[key] === undefined){
                push_counters[key] = 0;
            }
            return push_counters[key]++;
        };

        $.each($(this).serializeArray(), function(){

            // Skip invalid keys
            if(!patterns.validate.test(this.name)){
                return;
            }

            var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

            while((k = keys.pop()) !== undefined){

                // Adjust reverse_key
                reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                // Push
                if(k.match(patterns.push)){
                    merge = self.build([], self.push_counter(reverse_key), merge);
                }

                // Fixed
                else if(k.match(patterns.fixed)){
                    merge = self.build([], k, merge);
                }

                // Named
                else if(k.match(patterns.named)){
                    merge = self.build({}, k, merge);
                }
            }

            json = $.extend(true, json, merge);
        });

        return json;
    };

    $.fn.formValidator = function () {
        var form_valid = $(this).kendoValidator({
            rules: {
                validCombobox: function (input) {
                    if ($(input).hasClass("kendo-combobox-validate")) {
                        var div_id = $(input).attr('id');
                        if (div_id != undefined) { //console.log(div_id)
                            var combobox = $('#' + div_id).data("kendoComboBox");
                            if (combobox != undefined) {
                                if (input.val() != "") combobox.value(input.val());
                                var value = combobox.value();
                                //console.log(value);
                                if ((value != "") && combobox.selectedIndex == -1) {
                                    return false;
                                }
                            }
                        }
                    }
                    return true;
                },
                verifyPasswords: function (input) {
                    if (input.is("[name=cf_password]")) {
                        if (input.val() != $('#new_password').val()) {
                            return false;
                        }
                    }
                    return true;
                },
                validDatePicker: function (input) {
                    if ($(input).hasClass("kendo-datepicker") || $(input).hasClass("kendo-datepicker-monthyear")) {
                        var value = $(input).val();
                        var pattern = /^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;

                        if (value != '')
                            return pattern.test(value);
                    }
                    return true;
                },
                validDateTimePicker: function (input) {
                    if ($(input).hasClass("kendo-datetimepicker")) {
                        var value = $(input).val();
                        var pattern = /^([0-9]{2})\/([0-9]{2})\/([0-9]{4}) (20|21|22|23|[0-1]?\d{1}):([0-5]?\d{1})$/;

                        if (value != '')
                            return pattern.test(value);
                    }
                    return true;
                },
                validDateComplaintNo: function (input) {
                    if (input.is("[name=complaint_no__52__1]")) {
                        var value = $(input).val();
                        var pattern = /^[a-zA-Z0-9!@#$%\^&*)(+=._-]+\/[0-9]*$/;

                        if (value != '')
                            return pattern.test(value);
                    }
                    return true;
                },
                email: function (input) {
                    if (input.is("[name=email__34__24]")) {
                        var value = $(input).val();
                        var pattern = /^\w+([\.-]?\w+)+@\w+([\.:]?\w+)+(\.[a-zA-Z0-9]{2,3})+$/;

                        if (value != '')
                            return pattern.test(value);
                    }
                    return true;
                },
                phone: function (input) {
                    if (input.is("[name=mobile__33__7]")) {
                        var value = $(input).val();
                        var pattern = /^[0-9]{10}$/;

                        if (value != '')
                            return pattern.test(value);
                    }/*else if (input.is("[name=phone__32__4]")) {
                        var value = $(input).val();
                        var pattern = /^[0-9]{1,9}$/;

                        if (value != '')
                            return pattern.test(value);
                    }*/
                    return true;
                },
            },
            messages: {
                validCombobox: "Select invalid value",
                verifyPasswords: "Passwords do not match!",
                validDatePicker: "Please valid date format",
                validDateTimePicker: "Please valid datetime format",
                validDateComplaintNo: "Please insert valid format: a-z A-Z 0-9/0-9",
                email: "Not valid email",
                phone: "Please valid phone number",
                // dateValidation: "Invalid date message"
            }
        }).data("kendoValidator");
        return form_valid;
    }

    $.initKendoUI = function (form) {
        if (form != undefined) {
            var inputReadonly = $("#" + form + " .readonly");
            var inputCheckbox = $("#" + form + " .chkboxs");
            var kendoDatepicker = $("#" + form + " .kendo-datepicker");
            var kendoTimepicker = $("#" + form + " .kendo-timepicker");
            var kendoDateTimepicker = $("#" + form + " .kendo-datetimepicker");
            var kendoDateTimepickerMonthyear = $("#" + form + " .kendo-datepicker-monthyear");
            var kendoTextEditor = $("#" + form + " .kendo-texteditor");
            var kendoCombobox = $("#" + form + " .kendo-combobox");
            var kendoComboboxRelate = $("#" + form + " .kendo-combobox-relate");
            var kendoComboboxAssign = $("#" + form + " .kendo-combobox-assign");
            var kendoComboboxRemoteData = $("#" + form + " .kendo-combobox-remote-data");
            var kendoMultiSelect = $("#" + form + " .kendo-multiselect");
            var kendoAutocomplete = $("#" + form + " .kendo-autocomplete");
            var kendoUpload = $("#" + form + " .kendo-upload");
            var btnPopRemove = $("#" + form + " .btn-popup-remove");
            var btnPopCreate = $("#" + form + " .btn-popup-create");
            var btnPopSearch = $("#" + form + " .btn-popup-search");
            var commentBox = $("#" + form + " .comment-msg");
            var commentBtn = $("#" + form + " .btn-comment-post");
            var commentList = $("#" + form + " .view-comment-list");
        } else {
            var inputReadonly = $(".readonly");
            var inputCheckbox = $(".chkboxs");
            var kendoDatepicker = $(".kendo-datepicker");
            var kendoTimepicker = $(".kendo-timepicker");
            var kendoDateTimepicker = $(".kendo-datetimepicker");
            var kendoDateTimepickerMonthyear = $(".kendo-datepicker-monthyear");
            var kendoTextEditor = $(".kendo-texteditor");
            var kendoCombobox = $(".kendo-combobox");
            var kendoComboboxRelate = $(".kendo-combobox-relate");
            var kendoComboboxAssign = $(".kendo-combobox-assign");
            var kendoComboboxRemoteData = $(".kendo-combobox-remote-data");
            var kendoMultiSelect = $(".kendo-multiselect");
            var kendoAutocomplete = $(".kendo-autocomplete");
            var kendoUpload = $(".kendo-upload");
            var btnPopRemove = $(".btn-popup-remove");
            var btnPopCreate = $(".btn-popup-create");
            var btnPopSearch = $(".btn-popup-search");
            var btnPopSearchMult = $(".btn-popup-search-mult");
            var commentBox = $(".comment-msg");
            var commentBtn = $(".btn-comment-post");
            var commentList = $(".view-comment-list");
        }

        $(inputReadonly).keydown(function (e) {
            e.preventDefault();
        });
        $(inputReadonly).focus(function (e) {
            $(this).blur();
            e.preventDefault();
        });

        $(inputCheckbox).change(function () {
            var chkval = $(this).parents('.form-group').find('input[type="hidden"]');

            if ($(this).prop('checked') == true) {
                $(chkval).val(1);
            } else {
                $(chkval).val(0);
            }
        })

        $(commentList).each(function (i, e) {
            var tabid = $(e).data('tabid');
            var module = $(e).data('module');
            var query_string = $(e).data('querystring');

            $.post(site_url('Comments/getCommentRecord?' + query_string), {
                module: module,
                tabid: tabid
            }, function (html) {
                $('.view-comment-list').html(html)
            })
        })

        $(commentBox).each(function (i, e) {
            var tabid = $(e).data('tabid');
            var module = $(e).data('module');
            var query_string = $(e).data('querystring');

            $.post(site_url('Comments/getCommentRecord?' + query_string), {
                module: module,
                tabid: tabid
            }, function (html) {
                $('.comment-list').html(html)
            })
        })

        $(commentBtn).click(function () {
            var comment_msg_box = $(this).parents('.form-group').find('.comment-msg');
            var comment_msg = $(comment_msg_box).val();

            var tabid = $(comment_msg_box).data('tabid');
            var module = $(comment_msg_box).data('module');
            var query_string = $(comment_msg_box).data('querystring');

            if (comment_msg != '') {
                $.post(site_url('Comments/postCommentRecord?' + query_string), {
                    module: module,
                    tabid: tabid,
                    msg: comment_msg
                }, function (rs) {
                    if (rs.Code != undefined && rs.Code == E9010) {
                        $.redirectLogin(rs)
                    } else {
                        $(comment_msg_box).val('');

                        $.post(site_url('Comments/getCommentRecord?' + query_string), {
                            module: module,
                            tabid: tabid
                        }, function (html) {
                            $('.comment-list').html(html)
                        })
                    }
                }, 'json')
            }
        })

        $(kendoDatepicker).each(function (i, e) {
            $(e).Datepicker();
        });

        $(kendoTimepicker).each(function (i, e) {
            $(e).Timepicker();
        });

        $(kendoDateTimepicker).each(function (i, e) {
            $(e).DatetimePicker();
        });

        $(kendoDateTimepickerMonthyear).each(function (i, e) {
            $(e).DateMonthpicker();
        });

        $(kendoTextEditor).each(function (i, e) {
            $(e).TextEditor();
        });

        $(kendoCombobox).each(function (i, e) {
            $(e).Combobox();
        });

        $(kendoComboboxRelate).each(function (i, e) {
            $(e).ComboboxRelate();
        });

        $(kendoComboboxAssign).each(function (i, e) {
            $(e).ComboboxAssign();
        });

        $(kendoComboboxRemoteData).each(function (i, e) {
            var url = site_url('Components/FieldData/sharingRule');
            $(e).ComboboxRemoteData(url);
        });

        $(kendoMultiSelect).each(function (i, e) {
            $(e).kendoMultiSelect();
        });

        $(kendoAutocomplete).each(function (i, e) {
            $(e).Autocomplete();
        });

        $(kendoUpload).each(function (i, e) {
            $(e).Uploadfiles();
        });

        $(btnPopRemove).each(function (i, e) {
            $(this).click(function (ele) {
                var parent = $(this).parents('.input-group'); //console.log(parent);
                $(parent).find('input[type="text"]').val('');
                $(parent).find('input[type="hidden"]').val('');

                var fielddata = $(this).data('fielddata')
                fielddata = fielddata.split(DELIMITER)
                // console.log(fielddata)

                var div_id = $(parent).find('input[type="hidden"]').attr('id'); //console.log(div_id);

                if(fielddata[0] === 'coordinate_id'){
                    $('#coordinate_email').val('')
                    $('#coordinate_mobileno').val('')
                }

                if(fielddata[0] === 'accountid' || fielddata[0] === 'account_id' || fielddata[0] === 'meter'){
                    $("input[type='hidden'][name*='meter']").val('')
                    $("input[type='text'][name*='meter']").val('')
                    $("input[name*='accountname']").val('')
                    $("input[name*='account_address']").val('')
                    $("input[name*='mea_district']").val('')
                    $("input[name*='account_category']").val('')
                    $("input[name*='account_ca_type']").val('')
                    $("input[name*='industrial_estate']").val('')

                    $("input[type='hidden'][name*='account_id']").val('')
                    $("input[type='text'][name*='account_id']").val('')
                    
                    // contact and condinate data
                    $("input[type='hidden'][name*='contact_id']").val('')
                    $("input[type='text'][name*='contact_id']").val('')
                    $('#email').val('')
                    $('#mobileno').val('')
                    $('#address').val('')

                    var supervisor = $('#supervisor').data("kendoMultiSelect");
                    supervisor.value([]);
                    var amteamgroup = $('#amteamgroup').data("kendoMultiSelect");
                    amteamgroup.value([]);

                    $("input[type='hidden'][name*='coordinate_id']").val('')
                    $("input[type='text'][name*='coordinate_id']").val('')
                    $('#coordinate_email').val('')
                    $('#coordinate_mobileno').val('')
                    $("input[type='text'][name*='ca']").val('')

                    var dataSource = new kendo.data.DataSource({
                        data: []
                    });

                    var visit_table = $("#visit_table").data("kendoGrid");
                    var service_case_table = $("#service_case_table").data("kendoGrid");
                    var scada_table = $("#scada_table").data("kendoGrid");
                    if(visit_table !== undefined) visit_table.setDataSource(dataSource);
                    if(service_case_table !== undefined) service_case_table.setDataSource(dataSource);
                    if(scada_table !== undefined) scada_table.setDataSource(dataSource);

                    var planDate = $('#plandate').data("kendoDateTimePicker");
                    var alertDate = $('#alert_date').data("kendoDateTimePicker");
                    var visitStartDate = $('#visitstartdate').data("kendoDateTimePicker");
                    if(planDate !== undefined) planDate.value('');
                    if(alertDate !== undefined) alertDate.value('');
                    if(visitStartDate !== undefined) visitStartDate.value('');
                }
                
                if (div_id == 'departmentgroupid__266') {
                    $('#departmentid__205').val(''); // Departmentid
                    $('#departmentid__205_input').val('');
                    $('#category__58').val(''); // Category
                    $('#category__58_input').val('');
                    $('#subcategory1__59').val(''); // Sub Category1
                    $('#subcategory1__59_input').val('');
                    $('#subcategory2__60').val(''); // Sub Category2
                    $('#subcategory2__60_input').val('');
                } else if (div_id == 'category__58') {
                    $('#subcategory1__59').val(''); // Sub Category1
                    $('#subcategory1__59_input').val('');
                    $('#subcategory2__60').val(''); // Sub Category2
                    $('#subcategory2__60_input').val('');
                } else if (div_id == 'account_id__306') {
                    $('#ca__307').val('');
                    $('#accountname__308').val('');
                    $('#meter__309').val('');
                    $('#accounttype__310').val('');
                    $('#address__318').val('');
                    $('#place__319').val('');
                    $('#cusaddress__616').val('');
                } else if (div_id == 'contact_id__313') {
                    
                    $('#firstname__312').val('');
                    $('#position__314').val('');
                    $('#mobileno__315').val('');
                    $('#telno__316').val('');
                    $('#email__317').val('');

                    $('#email').val('');
                    $('#mobileno').val('');

                    $('#position').val('')
                } else if (div_id == 'coordinate_id__672') {
                    $('#coordinate_firstname__671').val('');
                    $('#coordinate_position__673').val('');
                    $('#coordinate_mobileno__674').val('');
                    $('#coordinate_telno__675').val('');
                    $('#coordinate_email__676').val('');
                    $('#coordinate_position').val('')
                } else if (div_id == 'subcategory1__59') {
                    $('#subcategory2__60').val(''); // Sub Category2
                    $('#subcategory2__60_input').val('');
                } else if (div_id == 'contact_id__47') {
                    $('#contact_id__47_input').val('');
                    $('#contact_name__54').val('');
                    $('#contact_address__55').val('');
                    $('#phone__57').val('');
                    $('#email__56').val('');
                } else if (div_id == 'account_id__394') {
                    $('#accountname__395').val('');
                    $('#ca__396').val('');
                    $('#meter__397').val('');
                    $('#feeder_id__398').val('');
                    $('#industrial_estate__399').val('');
                    //$('#mea_district__399').val('');
                } else if (div_id == 'contact_id__400') {
                    $('#firstname__401').val('');
                    $('#phone__402').val('');
                } else if (div_id == 'accountid__35') {
                    $('#ca__92').val('');
                    $('#meter__94').val('');
                }
            })
        });

        $(btnPopCreate).each(function (i, e) {
            $('#modal-quick-create .modal-body').html('');
            $(this).click(function (ele) {
                var fielddata = $(this).data("fielddata");
                var fieldrelate = $(this).data("fieldrelate");
                var editable = $(this).data("editable");
                var action = $(this).data('action')
                fielddata = fielddata.split(DELIMITER);
                fieldrelate = fieldrelate.split(DELIMITER);

                var fieldname = fielddata[0];
                var fieldid = fielddata[1];
                var tabid = fielddata[3];
                var module = fielddata[2];
                var value = fielddata[4];

                var relate_fieldname = fieldrelate[0];
                var relate_fieldid = fieldrelate[1];
                var relate_tab = fieldrelate[2];

                var fieldIdValue = '';
                if(action === 'Edit'){
                    console.log(`#${fieldname}${DELIMITER}${fieldid}`)
                    fieldIdValue = $(`#${fieldname}${DELIMITER}${fieldid}`).val()
                    // console.log(fieldIdValue)
                }
                // console.log(fielddata, fieldrelate)
                $.post(site_url('Components/quickCreateForm'), {
                    tabid: relate_tab,
                    source_tabid: tabid,
                    editable,
                    action,
                    fieldname,
                    fielddata,
                    fieldrelate,
                    fieldIdValue
                }, function (html) {
                    $('#modal-quick-create .modal-body').html(html);
                })
            })
        })

        $(btnPopSearch).each(function (i, e) {
                $(this).click(function (ele) {
                        $('#grid-modal').html('');
                        var fielddata = $(this).data("fielddata");
                        var fieldrelate = $(this).data("fieldrelate");
                        fielddata = fielddata.split(DELIMITER);
                        fieldrelate = fieldrelate.split(DELIMITER);

                        var fieldname = fielddata[0]; //console.log(fieldname);
                        var fieldid = fielddata[1]; //console.log(fieldid);
                        var tabid = fielddata[3]; //console.log(tabid);
                        var module = fielddata[2];
                        var value = fielddata[4];

                        var relate_fieldname = fieldrelate[0];
                        var relate_fieldid = fieldrelate[1];
                        var relate_tab = fieldrelate[2];
                        // console.log(fielddata, fieldrelate)
                        $.post(site_url("MainInquiry/listColumnsPopup"), {
                            tabid: tabid,
                            module: module,
                            fieldid: fieldid,
                            relate_tab: relate_tab,
                            relate_fieldid: relate_fieldid
                        }, function (rs) { //console.log(rs)
                            if (rs.status == true) {
                                var columns = [
                                    //{ selectable: true, width: "30px" },
                                    // { field: "action", title: " ", width:"100px", sortable: false, filterable: false, template: actionButtons }
                                ];
                                // console.log(module, fieldname);

                                var account_id__306 = $('#account_id__306').val();

                                var search_value = '';
                                if (fieldname == 'departmentid') {
                                    var departmentgroup_id = $('#departmentgroupid__266').val();
                                    if (departmentgroup_id != undefined && departmentgroup_id != '') {
                                        search_value = departmentgroup_id
                                        relate_fieldid = '240'
                                    }

                                } else if (fieldname == 'category') {
                                    var departmentgroup_id = $('#departmentgroupid__266').val();
                                    if (departmentgroup_id != undefined && departmentgroup_id != '') {
                                        search_value = departmentgroup_id
                                        relate_fieldid = '503'
                                    }

                                } else if (fieldname == 'contact_id' && module == 'Visit') {
                                    var accountid__306 = $('#account_id__306').val();
                                    if (accountid__306 != undefined && accountid__306 != '') {
                                        search_value = accountid__306
                                        relate_fieldid = '35'
                                    }
                                } else if (fieldname == 'account_id' && module == 'Visit') {
                                    // var accountid__306 = $('#account_id__306').val();
                                    // if (accountid__306 != undefined && accountid__306 != '') {
                                    //     search_value = accountid__306
                                    //     relate_fieldid = '306'
                                    // }
                                    var mea_districtid__517 = $('#mea_districtid__517').val();
                                    // console.log(mea_districtid__517);
                                    relate_fieldid = '11'
                                    search_value = mea_districtid__517

                                } else if (fieldname == 'subcategory1') {
                                    var cat = $('#category__58').val();
                                    if (cat != undefined && cat != '') {
                                        search_value = cat
                                        relate_fieldid = '485'
                                    }
                                } else if (fieldname == 'subcategory2') {
                                    var subcat1 = $('#subcategory1__59').val();
                                    if (subcat1 != undefined && subcat1 != '') {
                                        search_value = subcat1
                                        relate_fieldid = '496'
                                    }
                                } else if (fieldname == 'contact_id' && module == 'ServiceCase') {
                                    var account_id__394 = $('#account_id__394').val();
                                    if (account_id__394 != undefined && account_id__394 != '') {
                                        search_value = account_id__394
                                        relate_fieldid = '35'
                                    }
                                } else if (fieldname == 'account_id' && module == 'ServiceCase') {
                                    // var account_id__394 = $('#account_id__394').val();
                                    // if (account_id__394 != undefined && account_id__394 != '') {
                                    //     search_value = account_id__394
                                    //     relate_fieldid = '394'
                                    // }
                                    search_value = $('#mea_districtid').val()
                                    relate_fieldid = '11'
                                } else if (fieldname == 'mea_districtid' && module == 'Visit') {
                                    var mea_districtid__517 = $('#mea_districtid__517').val();
                                    search_value = meadistrictid

                                } else if (fieldname == 'account_id' && module == 'VisitQuickCreate') {
                                    var mea_districtid__517 = $('#mea_districtid__517').val();
                                    search_value = meadistrictid
                                    relate_fieldid = '11'

                                } else if (fieldname == 'meter' && module == 'VisitQuickCreate') {
                                    var meter__306 = $('#meter__306').val();
                                    search_value = meadistrictid
                                    relate_fieldid = '11'

                                } else if (fieldname == 'ca' && module == 'VisitQuickCreate') {
                                    var ca__306 = $('#ca__306').val();
                                    search_value = meadistrictid
                                    relate_fieldid = '11'

                                } else if ((fieldname == 'contact_id' || fieldname === 'coordinate_id') && module == 'VisitQuickCreate') {
                                    var account_id__306 = $('#account_id__306').val();

                                    // if(account_id__306 == "")
                                    // {
                                    //     account_id__306 = "@";
                                    // }

                                    search_value = account_id__306
                                    relate_fieldid = '35'

                                } else if (fieldname == 'accountid' && module == 'Contact') {
                                    search_value = meadistrictid
                                    relate_fieldid = '11'

                                }
                                else if (fieldname == 'account_id' && module == 'Visit') {
                                    search_value = meadistrictid
                                   // relate_fieldid = '517'
								   	relate_fieldid = '341'
                                }

                                if(fieldname == 'contact_id' || fieldname === 'coordinate_id'){
                                    var accountFieldHidden = $("input[type='hidden'][name*='account_id']")
                                    var accountFieldText = $("input[type='text'][name*='account_id']")

                                    if(accountFieldHidden !== undefined && accountFieldHidden.val() !== ''){
                                        search_value = accountFieldHidden.val()
                                        relate_fieldid = '35'
                                    }else{
                                        search_value = meadistrictid
                                        relate_fieldid = '35'
                                    }
                                }


                                var filters = [];
                                if (search_value != '') filters.push({
                                    field: relate_fieldname + DELIMITER + relate_fieldid,
                                    operator: "eq",
                                    value: search_value
                                });

                                // console.log(fieldname, module)
                                // console.log(filters);
                                //var columns = [];

                                var datasource = {
                                    pageSize: PAGESIZE,
                                    transport: {
                                        read: {
                                            url: site_url("MainInquiry/listDataPopup"),
                                            dataType: "json",
                                            data: {
                                                tabid: tabid,
                                                module: module,
                                                fieldid: fieldid,
                                                fieldname: fieldname,
                                                value: value,
                                                relate_tab: relate_tab,
                                                relate_fieldname: relate_fieldname
                                            }
                                        }
                                    },
                                    filter: {
                                        logic: "and",
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
                                //columns.push({ selectable: true, width: "30px" });

                                $.merge(columns, rs.columns); //console.log(datasource);
                                $('#grid-modal').html('');
                                $('#grid-modal').genKendoGridPopup(datasource, columns);
                                $('#grid-modal').children(".k-grid-content").css("height", "500px");

                                // var grid = $("#grid-modal").data("kendoGrid");
                                // var selected = grid.dataItem(grid.select());
                                // console.log(selected);

                                // $("#grid-modal tbody").on("click", "tr", function(e) {
                                //     var grid = $("#grid-modal").getKendoGrid();
                                //     var selected = grid.select();
                                //     console.log(selected);
                                // });

                            }
                        }, 'json')
                    }
                )
            }
        )

        $(btnPopSearchMult).each(function (i, e) {
            $(this).click(function (ele) {
                $('#grid-modal-mult').html('');
                var fielddata = $(this).data("fielddata");
                var fieldrelate = $(this).data("fieldrelate");
                fielddata = fielddata.split(DELIMITER);
                fieldrelate = fieldrelate.split(DELIMITER);
//
                var fieldname = fielddata[0]; //console.log(fieldname);
                var fieldid = fielddata[1]; //console.log(fieldid);
                var tabid = fielddata[3]; //console.log(tabid);
                var module = fielddata[2];
                var value = fielddata[4];

                var relate_fieldname = fieldrelate[0];
                var relate_fieldid = fieldrelate[1];
                var relate_tab = fieldrelate[2];

                $.post(site_url("MainInquiry/listColumnsPopup"), {
                    tabid: tabid,
                    module: module,
                    fieldid: fieldid,
                    relate_tab: relate_tab,
                    relate_fieldid: relate_fieldid
                }, function (rs) { // console.log(rs)
                    if (rs.status == true) {
                        var columns = [
                            {selectable: true, width: "30px"},
                            // { field: "action", title: " ", width:"100px", sortable: false, filterable: false, template: actionButtons }
                        ];
                        //console.log(fieldname);
                        //console.log(relate_fieldid);
                        var search_value = '';

                        var filters = [];
                        if (search_value != '') filters.push({
                            field: relate_fieldname + DELIMITER + relate_fieldid,
                            operator: "eq",
                            value: search_value
                        });

                        //var columns = [];
                        var datasource = {
                            pageSize: PAGESIZE,
                            transport: {
                                read: {
                                    url: site_url("MainInquiry/listDataPopup"),
                                    dataType: "json",
                                    data: {
                                        tabid: tabid,
                                        module: module,
                                        fieldid: fieldid,
                                        fieldname: fieldname,
                                        value: value,
                                        relate_tab: relate_tab,
                                        relate_fieldname: relate_fieldname
                                    }
                                }
                            },
                            filter: {
                                logic: "and",
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

                        $.merge(columns, rs.columns); //console.log(datasource);
                        $('#grid-modal-mult').html('');
                        $('#grid-modal-mult').genKendoGridPopupMult(datasource, columns);
                        $('#grid-modal-mult').children(".k-grid-content").css("height", "500px");

                        $('#result-button').click(function () {

                            var grid = $("#grid-modal-mult").data("kendoGrid");
                            var rows = grid.select();

                            var sss = '';
                            rows.each(function (index, row) {
                                var selectedItem = grid.dataItem(row);

                                //console.log(selectedItem); return false;
                                sss += selectedItem.first_name_th
                                var target_value = selectedItem['target_value'];
                                //var target_view = sss;

                                //$('#'+selectedItem.target).val(sss);
                                $('#' + dataItem.target + '_input').val(sss);
                            })

                            //console.log(sss)
                            $("#modal-mult-popup").modal('hide');
                        });

                    }
                }, 'json')
            })
        })
    }
})
;
