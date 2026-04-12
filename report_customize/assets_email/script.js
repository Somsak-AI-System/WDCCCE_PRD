const getDatePickerTitle = elem => {
    // From the label or the aria-label
    const label = elem.nextElementSibling;
    let titleText = '';
    if (label && label.tagName === 'LABEL') {
        titleText = label.textContent;
    } else {
        titleText = elem.getAttribute('aria-label') || '';
    }
    return titleText;
}

$(function() {
    const elems = $('.datepicker_input');
    for (const elem of elems) {
        const datepicker = new Datepicker(elem, {
            format: 'dd/mm/yyyy', // UK format
            title: getDatePickerTitle(elem)
        });
    }

    $('.select-multi').multiselect({
        buttonClass: 'multiselect-button',
        buttonWidth: '100%',
        includeSelectAllOption: true,
        nonSelectedText: 'เลือก',
        selectAllText: 'เลือกทั้งหมด',
        filterPlaceholder: 'ค้นหา',
        enableFiltering: true,
        numberDisplayed: 5
    });

    $.searchShow = function(modalID, obj) {
        var moduleSelect = $(obj).data('moduleselect')
        var fieldID = $(obj).data('field')
        $(`#${modalID}`).modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
        getPopupList(moduleSelect, fieldID)
    }

    $.setPopupValue = function(fieldID, itemData){
        // console.log(fieldID, itemData)
        $(`#${fieldID}`).val(itemData.id)
        $(`#${fieldID}-input`).val(itemData.name)

        if(itemData.moduleSelect !== undefined){
            switch(itemData.moduleSelect){
                case 'Accounts':
                    $(`#mobile`).val(itemData.mobile)
                    break;
            }
        }

        $(`#${fieldID}-modal`).modal('hide');
    }

    $.closeModal = function(modalID){
        offSet = 0;
        $(`#${modalID}`).modal('hide');
    }

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
})