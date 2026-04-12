$.fn.datebox.defaults.formatter = function(date){
	var y = date.getFullYear();
	var m = date.getMonth()+1;
	var d = date.getDate();
	return (d < 10 ? `0${d}`:d)+'-'+(m < 10 ? `0${m}`:m)+'-'+y;
}
$.fn.datebox.defaults.parser = function(date){
	if (date != ''){
        var parts = date.split('-');
        var d = parseInt(parts[0], 10);
        var m = parseInt(parts[1], 10) - 1;
        var y = parseInt(parts[2], 10);
		return new Date(y, m, d);
	} else {
		return new Date();
	}
}

$('#setting-convert-tabs').tabs({
    border: false,
    width: $(window).width() - 360,
    onSelect: function(title, tabIndex) {
        if(tabIndex === 1){
            $('#log_date_start').datebox();
            $('#log_date_end').datebox();
            $('#log_old_sale').combobox({
                valueField: 'id',
                textField: 'user_name',
                data: userData
            })
            $('#log_new_sale').combobox({
                valueField: 'id',
                textField: 'user_name',
                data: userData
            })
            $('#log_module').combobox({
                valueField: 'id',
                textField: 'label',
                multiple: true,
                data: [
                    { id:6, label:'Account'},
                    { id:20, label:'Quotes'},
                    { id:50, label:'Projects'}
                ],
                formatter:function(row){
                    var opts = $(this).combobox('options');
                    return '<input type="checkbox" class="combobox-checkbox">' + row[opts.textField]
                },
                onLoadSuccess:function(){
                    var opts = $(this).combobox('options');
                    var target = this;
                    var values = $(target).combobox('getValues');
                    $.map(values, function(value){
                    var el = opts.finder.getEl(target, value);
                    el.find('input.combobox-checkbox')._propAttr('checked', true);
                    })
                },
                onSelect:function(row){
                    var opts = $(this).combobox('options');
                    var el = opts.finder.getEl(this, row[opts.valueField]);
                    el.find('input.combobox-checkbox')._propAttr('checked', true);
                },
                onUnselect:function(row){
                    var opts = $(this).combobox('options');
                    var el = opts.finder.getEl(this, row[opts.valueField]);
                    el.find('input.combobox-checkbox')._propAttr('checked', false);
                }
            })
            $('#convert-log').datagrid({
                fitColumns: true,
                columns:[[
                    {field:'convert_date', width: 150, title:'Date Time', filter: {
                        type: 'datebox',
                        options: {
                            formatter: function (date) {
                                var y = date.getFullYear();
                                var m = date.getMonth()+1;
                                var d = date.getDate();
                                return (d < 10 ? `0${d}`:d)+'-'+(m < 10 ? `0${m}`:m)+'-'+y;
                            },
                            parser: function (s) {
                                if (date != ''){
                                    var parts = date.split('-');
                                    var d = parseInt(parts[0], 10);
                                    var m = parseInt(parts[1], 10) - 1;
                                    var y = parseInt(parts[2], 10);
                                    return new Date(y, m, d);
                                } else {
                                    return new Date();
                                }
                            }
                        }
                    }},
                    {field:'module', width: 150, title:'Module'},
                    {field:'no', width: 150, title:'Record No.', formatter: function(value, row, index){
                        var module = ''
                        switch(row.module){
                            case 'Account':
                                module = 'Accounts'
                                break;
                            case 'Quotes':
                                module = 'Quotes'
                                break;
                            case 'Project Order':
                                module = 'Projects'
                                break;
                        }
                        return `<a href="index.php?module=${module}&action=DetailView&record=${row.crmid}&parenttab=Marketing" target="_blank">${value}</a>`
                    }},
                    {field:'name', width: 150, title:'Record Name', formatter: function(value, row, index){
                        var module = ''
                        switch(row.module){
                            case 'Account':
                                module = 'Accounts'
                                break;
                            case 'Quotes':
                                module = 'Quotes'
                                break;
                            case 'Project Order':
                                module = 'Projects'
                                break;
                        }
                        return `<a href="index.php?module=${module}&action=DetailView&record=${row.crmid}&parenttab=Marketing" target="_blank">${value}</a>`
                    }},
                    {field:'old_sales_rep', width: 150, title:'Old Sales Rep.'},
                    {field:'new_sales_rep', width: 150, title:'New Sales Rep.'},
                    {field:'modified_by', width: 150, title:'Modified By'},
                ]],
                onLoadSuccess: function () {
                    $(this).datagrid('resize');
                }
            }).datagrid('enableFilter')
        }
    }
});

$.searchConvertLog = function(){
    var logStartDate = $('#log_date_start').datebox('getValue')
    var logEndDate = $('#log_date_end').datebox('getValue')
    var logOldSale = $('#log_old_sale').combobox('getValue')
    var logNewSale = $('#log_new_sale').combobox('getValue')
    var logModules = $('#log_module').combobox('getValues')

    // console.log(logStartDate, logEndDate, logOldSale, logNewSale, logModules)
    $('#btn-search-log').prop('disabled', true).text('Searching Data...')
    $.post('index.php', {
        module: 'Settings',
        action: 'SettingsAjax',
        ajax: true,
        file: 'SettingConvertSalesRepData',
        type: 'search-log',
        data: {logStartDate, logEndDate, logOldSale, logNewSale, logModules}
    }, function(rs){
        $('#convert-log').datagrid('loadData', rs);
        $('#btn-search-log').prop('disabled', false).text('Search')
    }, 'json')
}

$.exportConvertLog = function(){
    var logStartDate = $('#log_date_start').datebox('getValue')
    var logEndDate = $('#log_date_end').datebox('getValue')
    var logOldSale = $('#log_old_sale').combobox('getValue')
    var logNewSale = $('#log_new_sale').combobox('getValue')
    var logModules = $('#log_module').combobox('getValues')

    // console.log(logStartDate, logEndDate, logOldSale, logNewSale, logModules)
    $('#btn-export-log').prop('disabled', true).text('Processing Data...')
    $.post('index.php', {
        module: 'Settings',
        action: 'SettingsAjax',
        ajax: true,
        file: 'SettingConvertSalesRepData',
        type: 'export-log',
        data: {logStartDate, logEndDate, logOldSale, logNewSale, logModules}
    }, function(rs){
        const link = document.createElement('a');
        link.href = rs.path;
        document.body.appendChild(link);
        link.click();
        // Clean up and remove the link
        link.parentNode.removeChild(link);
        $('#btn-export-log').prop('disabled', false).text('Export to Excel')
    }, 'json')
}

$.runStep1 = function(){
    $('#old_sale_rep').combobox({
        valueField: 'id',
        textField: 'user_name',
        data: userData
    })

    $('#modules').combobox({
        valueField: 'id',
        textField: 'label',
        multiple: false,
        data: [
            { id:6, label:'Account'},
            { id:20, label:'Quotation'},
            // { id:50, label:'Projects'}
        ],
        formatter:function(row){
            var opts = $(this).combobox('options');
            return '<input type="checkbox" class="combobox-checkbox">' + row[opts.textField]
        },
        onLoadSuccess:function(){
            var opts = $(this).combobox('options');
            var target = this;
            var values = $(target).combobox('getValues');
            $.map(values, function(value){
            var el = opts.finder.getEl(target, value);
            el.find('input.combobox-checkbox')._propAttr('checked', true);
            })
        },
        onSelect:function(row){
            var opts = $(this).combobox('options');
            var el = opts.finder.getEl(this, row[opts.valueField]);
            el.find('input.combobox-checkbox')._propAttr('checked', true);
        },
        onUnselect:function(row){
            var opts = $(this).combobox('options');
            var el = opts.finder.getEl(this, row[opts.valueField]);
            el.find('input.combobox-checkbox')._propAttr('checked', false);
        }
    })
    // $('#modules').combobox('setValues', [6]);
}

$.checkStep1 = function(){
    oldSaleRep = $('#old_sale_rep').combobox('getValue')
    modules = $('#modules').combobox('getValues')

    oldSaleRepData = userData.find(function(item) {
        return item.id == oldSaleRep;
    });

    if(oldSaleRep === ''){
        alert('Old sale Rep. is required');
        return false
    }

    if(modules.length === 0){
        alert('Modules  is required');
        return false
    }

    $('#rep-step-first').hide()
    if(modules[0] == '6') {
        $('#rep-step-account').show()
        $.runStepAccount()
    } 
    if(modules[0] == '20') {
        convertQuotationWithSO = $('#convert_quotation_with_so').is(':checked')
        $('#rep-step-quotes').show()
        $.runStepQuotes()
    } 
    if(modules[0] == '50') {
        $('#rep-step-project').show()
        $.runStepProject()
    } 
}

$.runStepAccount = function(){
    $('#account_status').combobox({
        valueField: 'label',
        textField: 'label',
        data: accountStatus
    })

    $('#account_left').datalist({
        title: 'Available Data in Accounts',
        width: 500,
        height: 400,
        singleSelect: false,
        columns: [[
            {field: 'label'}
        ]],
        data: []
    })

    $('#account_right').datalist({
        title: 'Selected Accounts',
        width: 500,
        height: 400,
        singleSelect: false,
        columns: [[
            {field: 'label'}
        ]],
        data: selectedAccount
    })
}    
$.searchAccount = function(){
    // $('#btn-search-account').prop('disabled', true).text('Searching Data')
    var accountNo = $('#account_no').val() 
    var accountNameTH = $('#account_name_th').val()   
    var accountNameEN = $('#account_name_en').val() 
    var accountStatus = $('#account_status').combobox('getValue') 
    // console.log(accountNo, accountNameTH, accountNameEN, accountStatus)
    $.post('index.php', {
        module: 'Settings',
        action: 'SettingsAjax',
        ajax: true,
        file: 'SettingConvertSalesRepData',
        type: 'search-account',
        data: {accountNo, accountNameTH, accountNameEN, accountStatus, oldSaleRep}
    }, function(rs){
        $('#account_left').datalist('loadData', rs);
        $('#btn-search-account').prop('disabled', false).text('Search')
    }, 'json')
}
$.setSelectedAccount = function(type) {
    var allData = $('#account_left').datalist('getData').rows;
    var selectedItems = $('#account_left').datalist('getSelections');
    if(type === 'all'){
        selectedItems = allData
    }
    var newLeftData = []

    selectedItems.map(item => {
        let findAccount = selectedAccount.find(e => e.id === item.id)
        if(findAccount === undefined){
            selectedAccount.push({
                id: item.id,
                label: item.label
            })
        }
    })

    
    allData.map(item => {
        var findSelectedItem = selectedItems.find(e => e.id === item.id)
        if(findSelectedItem === undefined){
            newLeftData.push({
                id: item.id,
                label: item.label
            })
        }
    })

    $('#account_right').datalist('loadData', selectedAccount)
    $('#account_left').datalist('loadData', newLeftData)
}
$.setUnSelectedAccount = function(type){
    var leftData = $('#account_left').datalist('getData').rows;
    var allData = $('#account_right').datalist('getData').rows;
    var selectedItems = $('#account_right').datalist('getSelections');
    if(type === 'all'){
        selectedItems = allData
    }
    var newRightData = []

    selectedAccount = selectedAccount.filter(e => !selectedItems.some(el => e.id === el.id))
    selectedItems.map(item => {
        leftData.push({
                id: item.id,
                label: item.label
            })
    })

    $('#account_right').datalist('loadData', selectedAccount)
    $('#account_left').datalist('loadData', leftData)
}
$.checkStepAccount = function(){
    if(selectedAccount.length === 0) {
        alert('No Account data selected');
        return false
    }
    if(modules.length > 0){
        var index = modules.indexOf('6')
        if(index != -1){
            $('#rep-step-account').hide()
            var nextIndex = index+1
            if(modules[nextIndex] !== undefined){
                if(modules[nextIndex] == '50') {
                    $('#rep-step-project').show()
                    $.runStepProject()
                } 
            } else {
                $.openFinalStep()
            }
        }
    }
}

$.runStepQuotes = function(){
    $('#quotation_status').combobox({
        valueField: 'label',
        textField: 'label',
        data: quotationStatus
    })

    $('#quotes_left').datalist({
        title: 'Available Data in Quotation',
        width: 500,
        height: 400,
        singleSelect: false,
        columns: [[
            {field: 'label'}
        ]],
        data: []
    })

    $('#quotes_right').datalist({
        title: 'Selected Quotation',
        width: 500,
        height: 400,
        singleSelect: false,
        columns: [[
            {field: 'label'}
        ]],
        data: selectedQuotes
    })
}    
$.searchQuotes = function () {

    var quotesNo = $('#quote_no').val();
    var quotesName = $('#quote_name').val();
    var quotationStatus = $('#quotation_status').combobox('getValue');

    // get จาก easyui-datebox (dd-mm-yyyy)
    var dateFromDMY = $('#quotation_date_from').datebox('getValue');
    var dateToDMY   = $('#quotation_date_to').datebox('getValue');

    // แปลงเป็น yyyy-mm-dd
    function dmyToYmd(dmy){
        if (!dmy) return '';
        var p = dmy.split('-'); // [dd, mm, yyyy]
        return p[2] + '-' + p[1] + '-' + p[0];
    }

    var quotation_date_from = dmyToYmd(dateFromDMY);
    var quotation_date_to   = dmyToYmd(dateToDMY);

    // console.log(quotation_date_from, quotation_date_to);

    $.post('index.php', {
        module: 'Settings',
        action: 'SettingsAjax',
        ajax: true,
        file: 'SettingConvertSalesRepData',
        type: 'search-quotes',
        data: {
            quotesNo,
            quotesName,
            quotationStatus,
            quotation_date_from,
            quotation_date_to,
            oldSaleRep,
            convertQuotationWithSO
        }
    }, function (rs) {
        $('#quotes_left').datalist('loadData', rs);
        $('#btn-search-quotes').prop('disabled', false).text('Search');
    }, 'json');
}
$.setSelectedQuotes = function(type) {
    var allData = $('#quotes_left').datalist('getData').rows;
    var selectedItems = $('#quotes_left').datalist('getSelections');
    if(type === 'all'){
        selectedItems = allData
    }
    var newLeftData = []

    selectedItems.map(item => {
        let findQuotes = selectedQuotes.find(e => e.id === item.id)
        if(findQuotes === undefined){
            selectedQuotes.push({
                id: item.id,
                label: item.label
            })
        }
    })

    
    allData.map(item => {
        var findSelectedItem = selectedItems.find(e => e.id === item.id)
        if(findSelectedItem === undefined){
            newLeftData.push({
                id: item.id,
                label: item.label
            })
        }
    })

    $('#quotes_right').datalist('loadData', selectedQuotes)
    $('#quotes_left').datalist('loadData', newLeftData)
}
$.setUnSelectedQuotes = function(type){
    var leftData = $('#quotes_left').datalist('getData').rows;
    var allData = $('#quotes_right').datalist('getData').rows;
    var selectedItems = $('#quotes_right').datalist('getSelections');
    if(type === 'all'){
        selectedItems = allData
    }
    var newRightData = []

    selectedQuotes = selectedQuotes.filter(e => !selectedItems.some(el => e.id === el.id))
    selectedItems.map(item => {
        leftData.push({
                id: item.id,
                label: item.label
            })
    })

    $('#quotes_right').datalist('loadData', selectedQuotes)
    $('#quotes_left').datalist('loadData', leftData)
}
$.checkStepQuotes = function(){
    if(selectedQuotes.length === 0) {
        alert('No Quotes data selected');
        return false
    }
    if(modules.length > 0){
        var index = modules.indexOf('20')
        if(index != -1){
            $('#rep-step-quotes').hide()
            var nextIndex = index+1
            if(modules[nextIndex] !== undefined){
                if(modules[nextIndex] == '6') {
                    $('#rep-step-account').show()
                    $.runStepAccount()
                } 
            } else {
                $.openFinalStep()
            }
        }
    }
}

$.runStepProject = function(){
    $('#project_status').combobox({
        valueField: 'label',
        textField: 'label',
        data: projectStatus
    })

    $('#project_opportunity').combobox({
        valueField: 'label',
        textField: 'label',
        data: projectOpportunity
    })

    $('#project_size').combobox({
        valueField: 'label',
        textField: 'label',
        data: projectSize
    })

    $('#project_left').datalist({
        title: 'Available Data in Project Orders',
        width: 500,
        height: 400,
        singleSelect: false,
        columns: [[
            {field: 'label'}
        ]],
        data: []
    })

    $('#project_right').datalist({
        title: 'Selected Project Orders',
        width: 500,
        height: 400,
        singleSelect: false,
        columns: [[
            {field: 'label'}
        ]],
        data: selectedProject
    })
}
$.searchProject = function(){
    $('#btn-search-project').prop('disabled', true).text('Searching Data')
    var projectNo = $('#project_no').val() 
    var projectName = $('#project_name').val()   
    var projectStatus = $('#project_status').combobox('getValue') 
    var projectOpportunity = $('#project_opportunity').combobox('getValue') 
    var projectSize = $('#project_size').combobox('getValue') 

    $.post('index.php', {
        module: 'Settings',
        action: 'SettingsAjax',
        ajax: true,
        file: 'SettingConvertSalesRepData',
        type: 'search-project',
        data: {projectNo, projectName, projectStatus, projectOpportunity, projectSize, oldSaleRep}
    }, function(rs){
        $('#project_left').datalist('loadData', rs);
        $('#btn-search-project').prop('disabled', false).text('Search')
    }, 'json')
}
$.setSelectedProject = function(type) {
    var allData = $('#project_left').datalist('getData').rows;
    var selectedItems = $('#project_left').datalist('getSelections');
    if(type === 'all'){
        selectedItems = allData
    }
    var newLeftData = []

    selectedItems.map(item => {
        let findProject = selectedProject.find(e => e.id === item.id)
        if(findProject === undefined){
            selectedProject.push({
                id: item.id,
                label: item.label
            })
        }
    })

    
    allData.map(item => {
        var findSelectedItem = selectedItems.find(e => e.id === item.id)
        if(findSelectedItem === undefined){
            newLeftData.push({
                id: item.id,
                label: item.label
            })
        }
    })

    $('#project_right').datalist('loadData', selectedProject)
    $('#project_left').datalist('loadData', newLeftData)
}
$.setUnSelectedProject = function(type){
    var leftData = $('#project_left').datalist('getData').rows;
    var allData = $('#project_right').datalist('getData').rows;
    var selectedItems = $('#project_right').datalist('getSelections');
    if(type === 'all'){
        selectedItems = allData
    }
    var newRightData = []

    selectedProject = selectedProject.filter(e => !selectedItems.some(el => e.id === el.id))
    selectedItems.map(item => {
        leftData.push({
                id: item.id,
                label: item.label
            })
    })

    $('#project_right').datalist('loadData', selectedProject)
    $('#project_left').datalist('loadData', leftData)
}
$.checkStepProject = function(){
    if(selectedProject.length === 0) {
        alert('No Project Orders data selected');
        return false
    }
    if(modules.length > 0){
        var index = modules.indexOf('50')
        if(index != -1){
            $('#rep-step-project').hide()
            var nextIndex = index+1
            if(modules[nextIndex] !== undefined){
                if(modules[nextIndex] == '6') {
                    $('#rep-step-account').show()
                    $.runStepAccount()
                } 
            } else {
                $.openFinalStep()
            }
        }
    }
}

$.backStep = function(obj, tabID){
    var index = modules.indexOf(tabID)
    if(index != -1){
        var prevIndex = index - 1
        if(prevIndex < 0){
            $('.module-step').hide()
            $('.first-step').show()
        } else {
            $(obj).parents('.module-step').hide()
            if(modules[prevIndex] == '6') {
                $('#rep-step-account').show()
            } 
            if(modules[prevIndex] == '20') {
                $('#rep-step-quotes').show()
            } 
            if(modules[prevIndex] == '50') {
                $('#rep-step-project').show()
            } 
        }
    } else {
        if(tabID === 'final'){
            $('#rep-step-final').hide()
            if(modules[modules.length-1] == '6') {
                $('#rep-step-account').show()
            } 
            if(modules[modules.length-1] == '20') {
                $('#rep-step-quotes').show()
            } 
            if(modules[modules.length-1] == '50') {
                $('#rep-step-project').show()
            }
        }
    }
}

$.openFinalStep = function(){
    $('#old_sale_rep_view').val(oldSaleRepData.user_name)
    $('#new_sale_rep').combobox({
        valueField: 'id',
        textField: 'user_name',
        data: userData.filter(e => e.id != oldSaleRepData.id)
    })
    $('#rep-step-final').show()
}

$.checkStepFinal = function(){
    newSaleRep = $('#new_sale_rep').combobox('getValue')
    newSaleRepData = userData.find(function(item) {
        return item.id == newSaleRep;
    });

    if(newSaleRep === ''){
        alert('New sale Rep. is required');
        return false
    }
    if (confirm("Confirm to Convert?")) {
        $('#btn-final-step').prop('disabled', true).text('Processing data please wait...')
        $('.btn-back-step').hide()
        $.post('index.php', {
            module: 'Settings',
            action: 'SettingsAjax',
            ajax: true,
            file: 'SettingConvertSalesRepData',
            type: 'save',
            data: {oldSaleRep, newSaleRep, modules, selectedAccount, selectedQuotes, selectedProject}
        }, function(rs){
            console.log(rs)
            alert('Convert Sale Rep. Completed')
            window.location.reload()
        }, 'json')
    }

    
}