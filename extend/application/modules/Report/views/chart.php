<link rel="stylesheet" href="<?php echo site_url('assets/custom.css'); ?>">
<script src="<?php echo site_url('assets/custom.js'); ?>"></script>
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
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="button" class="btn" id="btn_add_all"><i class="fa fa-plus"></i> Add Condition</button>
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
                    <div class="col-sm-3 nopad text-center">
                        <label class="image-radio">
                            <img class="img-responsive" src="<?php echo site_url('uploads/images/column.jpg'); ?>" />
                            <input type="radio" name="graph_type" value="mscolumn3d" checked required />
                            <i class="glyphicon glyphicon-ok hidden"></i>
                        </label>
                    </div>
                    <div class="col-sm-3 nopad text-center">
                        <label class="image-radio">
                            <img class="img-responsive" src="<?php echo site_url('uploads/images/bar.jpg'); ?>" />
                            <input type="radio" name="graph_type" value="msbar3d" required />
                            <i class="glyphicon glyphicon-ok hidden"></i>
                        </label>
                    </div>
                    <div class="col-sm-3 nopad text-center">
                        <label class="image-radio">
                            <img class="img-responsive" src="<?php echo site_url('uploads/images/line.jpg'); ?>" />
                            <input type="radio" name="graph_type" value="msline" required />
                            <i class="glyphicon glyphicon-ok hidden"></i>
                        </label>
                    </div>
                    <div class="col-sm-3 nopad text-center">
                        <label class="image-radio">
                            <img class="img-responsive" src="<?php echo site_url('uploads/images/pie.jpg'); ?>" />
                            <input type="radio" name="graph_type" value="pie3d" required />
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
                                <select class="form-control" id="select_data_fields" name="select_data_fields" style="width:100%">
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

        var input_values = {};
        var locaction_href = (window.location.href);
        locaction_href = locaction_href.split('#'); //console.log(locaction_href[1])
        if((locaction_href[1]!='step-0' && locaction_href[1]!=undefined) && $.isEmptyObject(input_values)){
            window.location.href = '<?php echo site_url('report/chart'); ?>';
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

        $('#select_group_by').kendoComboBox({
            filter: "contains",
            placeholder: 'None',
            dataTextField: "fieldlabel",
            dataValueField: "fieldvalue",
            template: "(#: data.tablabel #) #: fieldlabel #"
        }).data("kendoComboBox");

        $("#select_data_fields").kendoMultiSelect({
            placeholder: 'Select Data Fields',
            dataTextField: "fieldlabel",
            dataValueField: "fieldvalue",
            maxSelectedItems: 3
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

            var graph_type = $('input[name="graph_type"]:checked').val();
            if(graph_type=='pie3d'){
//               select_data_fields
                var select_data_fields = $('#select_data_fields').data("kendoMultiSelect");
                select_data_fields.value('');
                select_data_fields.options.maxSelectedItems = 1;
            }else{
                $('#select_data_fields').val('');
                var select_data_fields = $('#select_data_fields').data("kendoMultiSelect");
                select_data_fields.value('');
                select_data_fields.options.maxSelectedItems = 3;
            }

            e.preventDefault();
        });

        // Toolbar extra buttons
        var btnFinish = $('<button></button>',{ id:'btn_finish'}).text('Finish')
            .addClass('btn btn-info').css({'display':'none'})
            .on('click', function(){
                let err_temp = '';
                let elm_form = $("#form-step-2");

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
                var graph_type = $('input[name="graph_type"]:checked').val();
                input_values['graph_type'] = graph_type;
                input_values['report_type'] = 'chart';

                if(input_values.select_data_fields.length == 0){
                    $('#select_data_fields').parent('.k-widget').addClass('k-invalid');
                    return false;
                }else{
                    $('#select_data_fields').parent('.k-widget').removeClass('k-invalid');
                }
//                console.log(input_values);
                $.post('<?php echo site_url('report/generateReport')?>', input_values, function(rs){
//                    console.log(rs);
                    if(rs.report_id!=undefined && rs.report_id!=''){
                        window.location.href = '<?php echo site_url('report/viewcart'); ?>'+'/'+rs.report_id;
                    }
                },'json')
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

                        if(input_id != undefined){
                            var role = $(e).data('role')
                            var val = '';
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

                    // get data for next step
                    var selected_module = {
                        primary_module:input_values.primary_module,
                        relate_module:input_values.relate_module
                    }

                    $('#condition_all_rows').html('');
                    $('#condition_any_rows').html('');
                    $('#btn_add_all').prop("onclick", null).off("click");
                    $('#btn_add_any').prop("onclick", null).off("click");

                    $.addCondition('condition_all', selected_module);
                    $.addCondition('condition_any', selected_module);
                    $('#btn_add_all').click(function(){
                        $.addCondition('condition_all', selected_module);
                    });
                    $('#btn_add_any').click(function(){
                        $.addCondition('condition_any', selected_module);
                    });

                }else if(stepNumber == 1){
                    var condition_all_rows = $(elm_form).find('#condition_all_rows .row');
                    var condition_any_rows = $(elm_form).find('#condition_any_rows .row');

                    var condition_all_data = [];
                    $(condition_all_rows).each(function(i, e){
                        var row = $(e).find('input, select, textarea');
                        var row_data = {};
                        $(row).each(function(index, element){
                            var input_id = $(element).attr('id');

                            if(input_id != undefined){
                                var input_id = 'all_'+input_id;
                                var role = $(element).data('role')
                                var val = '';
                                if(role == undefined){
                                    var val = $(element).val();
                                }else{
                                    switch(role){
                                        case 'combobox':
                                            var val = $(element).data("kendoComboBox").value();
                                            break;
                                        case 'multiselect':
                                            var val = $(element).data("kendoMultiSelect").value();
                                            break;
                                    }
                                }
                                row_data[input_id] = val;
                            }
                        })
                        condition_all_data.push(row_data);
                    })
                    input_values['condition_all'] = condition_all_data;

                    var condition_any_data = [];
                    $(condition_any_rows).each(function(i, e){
                        var row = $(e).find('input, select, textarea');
                        var row_data = {};
                        $(row).each(function(index, element){
                            var input_id = $(element).attr('id');

                            if(input_id != undefined){
                                var input_id = 'any_'+input_id;
                                var role = $(element).data('role')
                                var val = '';
                                if(role == undefined){
                                    var val = $(element).val();
                                }else{
                                    switch(role){
                                        case 'combobox':
                                            var val = $(element).data("kendoComboBox").value();
                                            break;
                                        case 'multiselect':
                                            var val = $(element).data("kendoMultiSelect").value();
                                            break;
                                    }
                                }
                                row_data[input_id] = val;
                            }
                        })
                        condition_any_data.push(row_data);
                    })
                    input_values['condition_any'] = condition_any_data;

                    // get data for next step
                    var selected_module = {
                        primary_module:input_values.primary_module,
                        relate_module:input_values.relate_module
                    }
                    $.post('<?php echo site_url('report/getGroupbyFields'); ?>', selected_module, function(rs){
                        var select_group = $('#select_group_by').data("kendoComboBox");
                        var dataSource = new kendo.data.DataSource({
                            data: rs,
                            group: { field:"blocklabel" }
                        });
                        select_group.setDataSource(dataSource);
                    },'json');

                    $.post('<?php echo site_url('report/getSelectDataFields'); ?>', selected_module, function(rs){
                        var select_data = $('#select_data_fields').data("kendoMultiSelect");
                        var dataSource = new kendo.data.DataSource({
                            data: rs,
                            group: { field:"blocklabel" }
                        });
                        select_data.setDataSource(dataSource);
                    },'json');
                }

                $(nxtForm).trigger("reset")
            }else if(stepDirection === 'backward' && elm_form){
                if(stepNumber == 1){
                    $('#condition_all_rows').html('');
                    $('#condition_any_rows').html('');
                }
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