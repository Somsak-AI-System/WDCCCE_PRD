<style>
    .sw-theme-arrows {
        border: none;
    }
    .sw_arraw > li {
        width: 300px;
    }
    .sw-theme-arrows > ul.step-anchor > li.done > a {
        border-color: #888888 !important;
        color: #fff !important;
        background: #888888 !important;
    }
    .sw-theme-arrows > ul.step-anchor > li.done > a:after {
        border-left: 30px solid #888888;
    }
    .sw-theme-arrows > ul.step-anchor > li.active > a {
        border-color: #666666 !important;
        color: #fff !important;
        background: #666666 !important;
    }
    .sw-theme-arrows > ul.step-anchor > li.active > a:after {
        border-left: 30px solid #666666 !important;
    }
    .step_box {
        margin-top: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        background-color: #e2e6ea;
    }
    .text-red {
        color: red;
    }

    .k-valid {
        /*border-color: green!important;*/
    }

    .k-popup > .k-group-header {
        text-align: left!important;
    }
    .k-invalid {
        border-color: red!important;
    }
    .k-list > .k-item.k-first {
        padding-top: 2em;
    }

    .k-list > .k-state-hover.k-first {
        padding-top: calc(2em - 1px);
    }

    .k-list > .k-item.k-first > .k-group {
        height: 2em;
        left: 0;
    }

    .k-list > .k-state-hover.k-first > .k-group {
        top: -1px;
        left: -1px;
    }
    .icon-remove {
        cursor: pointer;
    }
    .form-group {
        margin-bottom: 10px;
    }
    .form-control {
        height: 30px!important;
    }

    .image-radio {
        cursor: pointer;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        border: 4px solid transparent;
        margin-bottom: 0;
        outline: 0;
    }
    .image-radio input[type="radio"] {
        display: none;
    }
    .image-radio-checked {
        border-color: #4783B0;
    }
    .image-radio .glyphicon {
        position: absolute;
        color: #4A79A3;
        background-color: #fff;
        padding: 10px;
        top: 0;
        right: 15px;
    }
    .image-radio-checked .glyphicon {
        display: block !important;
    }
    .btn-toolbar .btn, .btn-toolbar .btn-group, .btn-toolbar .input-group {
        float: none;
    }
    .sw-theme-arrows > .sw-toolbar-bottom {
        text-align: center;
    }
</style>

<div id="smartwizard" class="sw-main sw-theme-arrows">
    <ul class="sw_arraw">
        <li><a href="#step-0">1. Report Detail</a></li>
        <li><a href="#step-1">2. Filters</a></li>
        <li><a href="#step-2">3. Select Chart</a></li>
    </ul>

    <div>
        <div id="step-0">
            <div class="step_box">
                <form id="form-step-0" method="post" data-role="validator">
                    <div class="row form-group">
                        <div class="col-sm-4">Report Name <span class="text-red">*</span></div>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="report_name" name="report_name" required="required">
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4">Report Folder <span class="text-red">*</span></div>
                        <div class="col-sm-6">
                            <select class="form-control combobox" id="report_folder" name="report_folder" required="required" placeholder="Select" style="width:100%">
                                <?php foreach($folders as $folder){ ?>
                                    <option value="<?php echo $folder['folderid']; ?>"><?php echo $folder['foldername']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4">Primary Module <span class="text-red">*</span></div>
                        <div class="col-sm-6">
                            <select class="form-control combobox" id="primary_module" name="primary_module" required="required" placeholder="Select" style="width:100%">
                                <?php foreach($primary_modules as $module){ ?>
                                    <option value="<?php echo $module['tabid']; ?>"><?php echo $module['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4">Select Related Modules (MAX 2)</div>
                        <div class="col-sm-6">
                            <select class="form-control" id="relate_module" name="relate_module" style="width:100%">
                            </select>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4">Description</div>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="step1_desc" name="step1_desc" style="height:auto!important;" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-4">Share Report</div>
                        <div class="col-sm-6">
                            <select class="form-control" id="share_report" name="share_report" style="width:100%">
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="step-1">
            <div class="step_box">
                <form id="form-step-1" data-role="validator">
                    <h3>Choose List conditions</h3>
                    <div style="border-color:#ccc; background-color:#fff; padding:20px; margin:20px;">
                        <div id="condition_all">
                            <div class="row">
                                <div class="col-sm-12">
                                    All Conditions (All conditions must be met)
                                </div>
                            </div>
                            <div id="condition_all_rows">
<!--                                <div class="row condition_all_item form-group" data-count="0" id="row_0">-->
<!--                                    <div class="col-sm-4">-->
<!--                                        <input class="input_field" id="input_field_0" name="input_field[]" style="width: 100%;" />-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-3">-->
<!--                                        <input class="input_operator" id="input_operator_0" name="input_opreator[]" style="width: 100%;" />-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-4">-->
<!--                                        <input class="form-control input_search" id="input_search_0" name="input_search[]"/>-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-1">-->
<!--                                        <i class="fa fa-trash icon-remove" onclick="$.removeRow(this)"></i>-->
<!--                                    </div>-->
<!--                                </div>-->
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="button" class="btn" onclick="$.addCondition('condition_all')"><i class="fa fa-plus"></i> Add Condition</button>
                                </div>
                            </div>
                        </div>

                        <div id="condition_any" style="margin-top: 30px">
                            <div class="row">
                                <div class="col-sm-12">
                                    Any Conditions (At least one of the conditions must be met)
                                </div>
                            </div>
                            <div id="condition_any_rows">
<!--                                <div class="row condition_any_item form-group" data-count="0" id="row_0">-->
<!--                                    <div class="col-sm-4">-->
<!--                                        <input class="input_field" id="input_field_0" name="input_field[]" style="width: 100%;" />-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-3">-->
<!--                                        <input class="input_operator" id="input_operator_0" name="input_opreator[]" style="width: 100%;" />-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-4">-->
<!--                                        <input class="form-control input_search" id="input_search_0" name="input_search[]"/>-->
<!--                                    </div>-->
<!--                                    <div class="col-sm-1">-->
<!--                                        <i class="fa fa-trash icon-remove" onclick="$.removeRow(this)"></i>-->
<!--                                    </div>-->
<!--                                </div>-->
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="button" class="btn" id="btn_add_any"><i class="fa fa-plus"></i> Add Condition</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="step-2">
            <div class="step_box">
                <form id="form-step-2" data-role="validator">
                <div class="row">
                    <div class="col-xs-4 col-sm-3 col-md-2 nopad text-center">
                        <label class="image-radio">
                            <img class="img-responsive" src="<?php echo site_url('uploads/images/column.jpg'); ?>" />
                            <input type="radio" name="graph_type" value="column" checked required />
                            <i class="glyphicon glyphicon-ok hidden"></i>
                        </label>
                    </div>
                    <div class="col-xs-4 col-sm-3 col-md-2 nopad text-center">
                        <label class="image-radio">
                            <img class="img-responsive" src="<?php echo site_url('uploads/images/bar.jpg'); ?>" />
                            <input type="radio" name="graph_type" value="bar" required />
                            <i class="glyphicon glyphicon-ok hidden"></i>
                        </label>
                    </div>
                    <div class="col-xs-4 col-sm-3 col-md-2 nopad text-center">
                        <label class="image-radio">
                            <img class="img-responsive" src="<?php echo site_url('uploads/images/line.jpg'); ?>" />
                            <input type="radio" name="graph_type" value="line" required />
                            <i class="glyphicon glyphicon-ok hidden"></i>
                        </label>
                    </div>
                    <div class="col-xs-4 col-sm-3 col-md-2 nopad text-center">
                        <label class="image-radio">
                            <img class="img-responsive" src="<?php echo site_url('uploads/images/pie.jpg'); ?>" />
                            <input type="radio" name="graph_type" value="pie" required />
                            <i class="glyphicon glyphicon-ok hidden"></i>
                        </label>
                    </div>
                    <div class="col-xs-4 col-sm-3 col-md-2 nopad text-center">
                        <label class="image-radio">
                            <img class="img-responsive" src="<?php echo site_url('uploads/images/donut.jpg'); ?>" />
                            <input type="radio" name="graph_type" value="donut" required />
                            <i class="glyphicon glyphicon-ok hidden"></i>
                        </label>
                    </div>
                    <div class="col-xs-4 col-sm-3 col-md-2 nopad text-center">
                        <label class="image-radio">
                            <img class="img-responsive" src="<?php echo site_url('uploads/images/funnel.jpg'); ?>" />
                            <input type="radio" name="graph_type" value="funnel" required />
                            <i class="glyphicon glyphicon-ok hidden"></i>
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div style="border-color:#ccc; background-color:#fff; padding:10px; margin:10px;">
                        <div class="row row_title">
                            <div class="col-sm-6">
                                <label for="field">Select Groupby Field <span class="text-red">*</span></label>
                            </div>
                            <div class="col-sm-6">
                                <label for="operator">Select Data Fields <span class="text-red">*</span></label>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-6">
                                <input class="form-control" id="select_group_by" required="required" style="width: 100%;">
                            </div>
                            <div class="col-sm-6">
                                <select class="form-control multiselect" required="required">
                                    <option value="1">Record Count</option>
                                    <option value="2">Average Count</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('.combobox').kendoComboBox();
        $('.multiselect').kendoMultiSelect();

        var selected_module = {};

        var input_values = [];
        var locaction_href = (window.location.href);
        locaction_href = locaction_href.split('#'); //console.log(locaction_href[1])
        if((locaction_href[1]!='step-0' && locaction_href[1]!=undefined) && $.isEmptyObject(input_values)){
            window.location.href = '<?php echo site_url('report'); ?>';
        }

        var relate_module_data = JSON.parse('<?php echo json_encode($relate_modules); ?>');
        var member_group = JSON.parse('<?php echo json_encode($member_group); ?>');

        var primary_module = $("#primary_module").data("kendoComboBox");
        var selected_primary_module = primary_module.value();
        primary_module.bind("change", function () {
            var value = this.value();
            var dataSource = new kendo.data.DataSource({
                data: relate_module_data[value]
            });
            var relate_module = $("#relate_module").data("kendoMultiSelect");
            relate_module.setDataSource(dataSource);
        });

        $("#relate_module").kendoMultiSelect({
            placeholder: 'Select Related Modules',
            dataSource: relate_module_data[selected_primary_module],
            dataTextField: "name",
            dataValueField: "tabid",
            maxSelectedItems: 2
        });

        $("#share_report").kendoMultiSelect({
            placeholder: 'Add User, Roles, Groups',
            dataSource: {
                data: member_group,
                group: { field:'type' }
            },
            dataTextField: "name",
            dataValueField: "id"
        });

        $(".image-radio").each(function(){
            if($(this).find('input[type="radio"]').first().attr("checked")){
                $(this).addClass('image-radio-checked');
            }else{
                $(this).removeClass('image-radio-checked');
            }
        });

        $(".image-radio").on("click", function(e){
            $(".image-radio").removeClass('image-radio-checked');
            $(this).addClass('image-radio-checked');
            var $radio = $(this).find('input[type="radio"]');
            $radio.prop("checked",!$radio.prop("checked"));

            e.preventDefault();
        });

        var fielddata = [
            { text:'Name', value:1, type:'v' },
            { text:'Surname', value:2, type:'v' },
            { text:'Age', value:3, type:'i' },
            { text:'Phone', value:4, type:'v' },
            { text:'Email', value:5, type:'v' },
            { text:'Birthdate', value:6, type:'d' }
        ]

        var opdata = [
            { text:'equal', value:'equal', type:'v-d-i' },
            { text:'not equal', value:'not equal', type:'v-d-i' },
            { text:'start with', value:'start with', type:'v-d' },
            { text:'end with', value:'end with', type:'v-d' },
            { text:'contain', value:'contain', type:'v' }
        ]

        function cascade(e){
            let data_item = e.sender.dataItem();
            let parent = e.sender.element.parents('.form-group');
            let row_no = $(parent).data('count');
            let field = $(parent).find('#input_field_'+row_no).data("kendoComboBox");
            let operator = $(parent).find('#input_operator_'+row_no).data("kendoComboBox");
            operator.value('');
            if(field.value()){
                operator.dataSource.filter([ //filter the dataSource
                    { field: "type", operator: "contains", value: data_item.type }
                ]);
            }
        }

        $.addCondition = function(type, default_value){
            let row = $('.'+ type +'_item:last');
            let count = $(row).data('count');

            count = count==undefined ? 0:count;
            count = count+1;
            let div_row = $('<div />',{ id:'row_'+count, class:'row '+ type +'_item form-group' }).attr({ 'data-count':count });
            let div_field = $('<div />',{ class:'col-sm-4' });
            let div_operator = $('<div />',{ class:'col-sm-3' });
            let div_search = $('<div />',{ class:'col-sm-4' });
            let div_btn = $('<div />',{ class:'col-sm-1' });

            let input_field = $('<input />',{ id:'input_field_'+count, class:'input_field' }).css({ 'width':'100%' });
            let input_operator = $('<input />',{ id:'input_operator_'+count, class:'input_operator' }).css({ 'width':'100%' });
            let input_search = $('<input />',{ id:'input_search_'+count, class:'form-control input_search' });
            let btn_remove = $('<i />',{ class:'fa fa-trash icon-remove' }).click(function(){
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

            $('#'+ type +'_rows').append(div_row);

            $.post('<?php echo site_url('report/getBlockField'); ?>', selected_module ,function(response){
                let field = $(input_field).kendoComboBox({
                    filter: "contains",
                    placeholder: "Select Field...",
                    dataTextField: "fieldlabel",
                    dataValueField: "fieldid",
                    dataSource: {
                        data: response,
                        group: { field:"blocklabel" }
                    },
                    template: "(#: data.tablabel #) #: fieldlabel #"
                }).data("kendoComboBox");

                let operator = $(input_operator).kendoComboBox({
                    autoBind: false,
                    filter: "contains",
                    placeholder: "Select Operator...",
                    dataTextField: "text",
                    dataValueField: "value",
                    dataSource: opdata
                }).data("kendoComboBox");
                field.bind("cascade", cascade);
            },'json')
        }

        $.removeRow = function(obj){
            $(obj).parents('.row').remove();
        }

        function updateCssOnPropertyChange(e) {
            var element = $(e.target || e.srcElement);
            if($(element).data('role')=='multiselect'){
                $(element).parents('.k-widget').toggleClass("k-invalid", element.hasClass("k-invalid"));
            }else{
                element.siblings("span.k-dropdown-wrap")
                    .add(element.parent("span.k-numeric-wrap"))
                    .toggleClass("k-invalid", element.hasClass("k-invalid"));
            }

        }

        function validateForm(form) {
            /* Bind Mutation Events */
            var elements = $(form).find("[data-role=textarea],[data-role=autocomplete],[data-role=combobox],[data-role=multiselect],[data-role=dropdownlist],[data-role=numerictextbox]");

            //correct mutation event detection
            var hasMutationEvents = ("MutationEvent" in window),
                MutationObserver = window.WebKitMutationObserver || window.MutationObserver;

            if (MutationObserver) {
                var observer = new MutationObserver(function (mutations) {
                        var idx = 0,
                            mutation,
                            length = mutations.length;

                        for (; idx < length; idx++) {
                            mutation = mutations[idx];
                            if (mutation.attributeName === "class") {
                                updateCssOnPropertyChange(mutation);
                            }
                        }
                    }),
                    config = {attributes: true, childList: false, characterData: false};

                elements.each(function () {
                    observer.observe(this, config);
                });
            } else if (hasMutationEvents) {
                elements.bind("DOMAttrModified", updateCssOnPropertyChange);
            } else {
                elements.each(function () {
                    this.attachEvent("onpropertychange", updateCssOnPropertyChange);
                });
            }
        }

        // Toolbar extra buttons
        var btnFinish = $('<button></button>',{ id:'btn_finish'}).text('Finish')
            .addClass('btn btn-info').css({'display':'none'})
            .on('click', function(){
                let err_temp = '';
                let elm_form = $("#main");

                validateForm(elm_form);
                $(elm_form).kendoValidator({
                    validate: function(e){
                        $('.span.k-invalid-msg').hide()
                    },
                    errorTemplate: err_temp,
                }).data('kendoValidator');
                var validator = $(elm_form).data('kendoValidator');

                if (!validator.validate()) {
                    return false;
                }
            });
        var btnCancel = $('<button></button>').text('Cancel')
            .addClass('btn btn-danger')
            .on('click', function(){
                $('#smartwizard').smartWizard("reset");
            });

        // Smart Wizard
        $('#smartwizard').smartWizard({
            selected: 0,
            theme: 'dots',
            transitionEffect:'fade',
            toolbarSettings: {
                toolbarPosition: 'bottom',
                toolbarExtraButtons: [btnFinish, btnCancel]
            },
            lang: { // Language variables for button
                next: 'Next',
                previous: 'Back'
            },
            anchorSettings: {
                markDoneStep: true, // add done css
                markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                removeDoneStepOnNavigateBack: true, // While navigate back done step after active step will be cleared
                enableAnchorOnDoneStep: false // Enable/Disable the done steps navigation
            }
        });

        $("#smartwizard").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
            var err_temp = '';
            var elm_form = $("#form-step-" + stepNumber);
            var net_step = stepNumber + 1;
            var nxtForm = $("#form-step-" + net_step);

            if(stepDirection === 'forward' && elm_form){
                validateForm(elm_form);
                $(elm_form).kendoValidator({
                    validate: function(e){
                        $('.span.k-invalid-msg').hide()
                    },
                    errorTemplate: err_temp,
                }).data('kendoValidator');
                var validator = $(elm_form).data('kendoValidator');

                if (!validator.validate()) {
                    return false;
                }

                if(stepNumber == 0){
                    var inputs = $(elm_form).find('input, select, textarea');
                    $(inputs).each(function(i, e){
                        var input_id = $(e).attr('id');
                        var role = $(e).data('role')
                        var val = '';
                        if(input_id != undefined){
                            if(role == undefined){
                                var val = $(e).val();
                            }else{
                                switch(role){
                                    case 'combobox':
                                        var val = $(e).data("kendoComboBox").value();
                                        break;
                                    case 'multiselect':
                                        var val = $(e).data("kendoMultiSelect").value();
                                        break;
                                }
                            }
                            input_values[input_id] = val;
                        }
                    });

                    selected_module = {
                        primary_module:input_values.primary_module,
                        relate_module:input_values.relate_module
                    }

                    $.addCondition('condition_all');
                    $.addCondition('condition_any')

                }else if(stepNumber == 1){
                    var inputs_all = $(elm_form).find('#condition_all input, #condition_all select');
                    var inputs_any = $(elm_form).find('#condition_any input, #condition_any select');
                }

                console.log(input_values);
                $(nxtForm).trigger("reset")
            }else if(stepDirection === 'backward' && elm_form){
                $('#condition_all_rows').html('');
                $('#condition_any_rows').html('');
                selected_module = {};
            }
            return true;
        });

        $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
            // Enable finish button only on last step
            if(stepNumber == 2){
                $('#btn_finish').show();
            }else{
                $('#btn_finish').hide();
            }
        });
    })
</script>