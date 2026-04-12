<link rel="stylesheet" href="<?php echo site_url('assets/custom.css'); ?>">
<script src="<?php echo site_url('assets/custom.js'); ?>"></script>
<script src="<?php echo site_url('assets/graph.js'); ?>"></script>

<?php $report_data = json_decode($report['data'], true)?>
<div class="row">
    <div class="col-sm-12">
        <div class="pull-right">
            <a href="<?php echo site_url('report'); ?>" class="btn btn-default">View All Report</a>
        </div>
    </div>
</div>
<form id="form_edit">
    <input type="hidden" id="reportid" value="<?php echo $report['reportid']; ?>">
    <input type="hidden" id="graph_type" value="<?php echo $report_data['type']; ?>">
    <div style="border-color:#ccc; background-color:#fff; padding:20px; margin:20px;">
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
                <select class="" id="select_data_fields" name="select_data_fields" style="width:100%">
                </select>
            </div>
        </div>
    </div>

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
    <div class="row">
        <div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </div>
</form>

<div id="graph_container"></div>

<script>
    $(function(){
        var reportid = '<?php echo $report['reportid']; ?>';
        var primarymodule = '<?php echo $report['primarymodule_id']; ?>';
        var relatemodules = '<?php echo $report['secondarymodules_id']; ?>';
        relatemodules = relatemodules.split(':');

        var reporttype_data = JSON.parse('<?php echo $report['data']; ?>');
        var groupbyfield = reporttype_data.groupbyfield;
        var datafields = reporttype_data.datafields;
        var graph_type = reporttype_data.type; //console.log(graph_type);

        var conditions = JSON.parse('<?php echo json_encode($conditions); ?>');

        var selected_module = {
            primary_module: primarymodule,
            relate_module:relatemodules
        }

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

        $.post('<?php echo site_url('report/getGroupbyFields'); ?>', selected_module, function(rs){
            var select_group = $('#select_group_by').data("kendoComboBox");
            var dataSource = new kendo.data.DataSource({
                data: rs,
                group: { field:"blocklabel" }
            });
            select_group.setDataSource(dataSource);

            select_group.value(groupbyfield);
        },'json');

        $.post('<?php echo site_url('report/getSelectDataFields'); ?>', selected_module, function(rs){
            var select_data = $('#select_data_fields').data("kendoMultiSelect");
            var dataSource = new kendo.data.DataSource({
                data: rs,
                group: { field:"blocklabel" }
            });
            select_data.setDataSource(dataSource);

            select_data.dataSource.filter({});
            select_data.value(datafields);
        },'json');

        $(conditions).each(function(i, e){
            if(e.groupid == 1){
                $.addCondition('condition_all', selected_module, e);
            }else if(e.groupid == 2){
                $.addCondition('condition_any', selected_module, e);
            }
        });
        $('#btn_add_all').click(function(){
            $.addCondition('condition_all', selected_module);
        });
        $('#btn_add_any').click(function(){
            $.addCondition('condition_any', selected_module);
        });

        var err_temp = '';
        var elm_form = $("#form_edit");

        $(elm_form).submit(function(event){
            event.preventDefault()
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
            var input_values = {};
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

            input_values['report_type'] = 'chart';
            if(input_values.select_data_fields.length == 0){
                $('#select_data_fields').parent('.k-widget').addClass('k-invalid');
                return false;
            }else{
                $('#select_data_fields').parent('.k-widget').removeClass('k-invalid');
            }

            $.post('<?php echo site_url('report/editReport')?>', input_values, function(rs){
                if(rs.report_id!=undefined && rs.report_id!=''){
                    window.location.href = '<?php echo site_url('report/viewcart'); ?>'+'/'+rs.report_id;
                }
            },'json')
        })

        $.post('<?php echo site_url('report/getMSData/'.$report['reportid']); ?>', function(rs){ console.log(rs)
            $('#graph_container').chartGenerate(graph_type, rs);
        },'json');
    })
</script>

