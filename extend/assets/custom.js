function cascade(e){
    let data_item = e.sender.dataItem();
    if(data_item != undefined){
        let parent = e.sender.element.parents('.form-group');
        let row_no = $(parent).data('count');
        let field = $(parent).find('#input_field_'+row_no).data("kendoComboBox");
        let operator = $(parent).find('#input_operator_'+row_no).data("kendoComboBox");
        operator.value('');
        $.post(site_url('report/getOption'), { type:data_item.type }, function(rs){
            var dataSource = new kendo.data.DataSource({
                data: rs
            });
            operator.setDataSource(dataSource);
        },'json');
    }else{

    }
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
$.addCondition = function(type, selected_module, default_value){
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

    $.post(site_url('report/getBlocksFields'), selected_module ,function(response){
        let field = $(input_field).kendoComboBox({
            filter: "contains",
            placeholder: "Select Field...",
            dataTextField: "fieldlabel",
            dataValueField: "fieldvalue",
            value: default_value!=undefined ? default_value.columnname:'',
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
            dataTextField: "label",
            dataValueField: "key",
            dataSource: []
        }).data("kendoComboBox");
        field.bind("cascade", cascade);

        if(default_value != undefined){
            var item_type = default_value.columnname.split(':');
            item_type = item_type[item_type.length - 1];
            $.post(site_url('report/getOption'), { type:item_type }, function(rs){
                var dataSource = new kendo.data.DataSource({
                    data: rs
                });
                operator.setDataSource(dataSource);
                operator.value(default_value.comparator);
            },'json');
            $(input_search).val(default_value.value);
        }
    },'json')
}

$.removeRow = function(obj){
    $(obj).parents('.row').remove();
}