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
function isPhoneNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function isNumberPricelist(evt,obj) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function cha_length(text, count){
    if(text === undefined || text === null || text === '') return ''
    return text.slice(0, count) + (text.length > count ? "..." : "");
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function ConvertDateformat(x){

    if(x==''){
        return '';
    }
    var objectDate = new Date(x);
    var day = objectDate.getDate();
    var month = objectDate.getMonth();
    var year = objectDate.getFullYear();
    return day+"/"+(month + 1)+"/"+year;
}

function validTotalCom(){
    var sumTotalCom = 0
    $('.percent_com').each(function(i, e){
        var rowCom = $(this).val() === '' ? 0:$(this).val()
        sumTotalCom = eval(sumTotalCom) + eval(rowCom)
        // if(sumTotalCom > 100){
        //     $(this).val(0)
        // }
    })
    if(sumTotalCom > 100){
        Swal.fire('', '% Commission ของ Sales เกิน 100% <br> โปรดตรวจสอบและแก้ไข', 'error')
        return false
    } else {
        return true
    }
    
    // console.log(sumTotalCom)
}

$(function() {
    const elems = $('.datepicker_input');
    for (const elem of elems) {
        const datepicker = new Datepicker(elem, {
            format: 'dd/mm/yyyy', // UK format
            title: getDatePickerTitle(elem)
        });
    }

    $.searchShow = function(modalID, obj, relate_field_up, relate_field_down) {
        var moduleSelect = $(obj).data('moduleselect')
        var fieldID = $(obj).data('field')
        $(`#${modalID}`).modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
        getPopupList(moduleSelect, fieldID, '', '', '1', relate_field_up, relate_field_down)
    }

    $.searchShowEvent = function(modalID, obj) {
        var moduleSelect = $('#event_type').val();
        var fieldID = $(obj).data('field')
        $(`#${modalID}`).modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
        getPopupListEvent(moduleSelect, fieldID)
    }

    $.searchRecord = function(modalID, obj, relate_field_up, relate_field_down) {
        var moduleSelect = $(obj).data('moduleselect')
        var fieldID = $(obj).data('field')
        var selectfield = $(`#${fieldID}-modal-select-box`).val()
        var search = $(`#${fieldID}-modal-search-box`).val()
        /**/
        $(`#${fieldID}-modal-select-hidden`).val(selectfield)
        $(`#${fieldID}-modal-search-hidden`).val(search)
        /**/
        $(`#${modalID}`).modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
        getPopupList(moduleSelect,fieldID,search,selectfield, '1', relate_field_up, relate_field_down)
    }

    $.getNavigation = function(modalID, obj, event, relate_field_up, relate_field_down) {
        
        if($(obj).hasClass("disable_click") === true) return false;
        var Page =  eval($(obj).attr('data-page')); 
        var moduleSelect = $(obj).data('moduleselect')
        var fieldID = $(obj).data('field')
        var selectfield = $(`#${fieldID}-modal-select-hidden`).val()
        var search = $(`#${fieldID}-modal-search-hidden`).val()

        $(`#${modalID}`).modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
        getPopupList(moduleSelect,fieldID,search,selectfield,Page,relate_field_up, relate_field_down)
        event.preventDefault()
    }

    $.setPopupValue = function(fieldID, itemData, field_up, field_down){
        //console.log(fieldID, itemData)
        $(`#${fieldID}`).val(itemData.id)
        $(`#${fieldID}-input`).val(itemData.name)
        if(field_down !== '' && field_down !== undefined){
            $(`#${field_down}-input`).val('')
            $(`#${field_down}`).val('')
        }
        if(itemData.moduleSelect !== undefined){
            switch(itemData.moduleSelect){
                case 'Accounts':
                    $(`#mobile`).val(itemData.mobile);
                    $(`#village`).val(itemData.village);
                    $(`#room_no`).val(itemData.addressline);
                    $(`#address_no`).val(itemData.address1);
                    $(`#village_no`).val(itemData.villageno);
                    $(`#lane`).val(itemData.lane);

                    $(`#address`).val(itemData.address);
                    $(`#sub_district`).val(itemData.subdistrict);
                    $(`#district`).val(itemData.district);
                    $(`#province`).val(itemData.province);
                    $(`#postal_code`).val(itemData.postalcode);

                    $(`#email`).val(itemData.email);
                    $(`#taxid_no`).val(itemData.idcardno);

                    $(`#village`).val(itemData.village);
                    $(`#street`).val(itemData.street);
                    $(`#postal_code`).val(itemData.postalcode);
                    $(`#quotation_acc_cd_no`).val(itemData.no+'/'+itemData.cd_no);

                    $(`#payment_terms_type`).val(itemData.payment_terms_type);
                    $(`#payment_terms`).val(itemData.payment_terms);
                    break;
                case 'Projects':
                    $(`#project_name`).val(itemData.name);
                    $(`#project_status`).val(itemData.status);
                    $(`#project_est_s_date`).val(ConvertDateformat(itemData.project_s_date));
                    $(`#project_est_e_date`).val(ConvertDateformat(itemData.project_estimate_e_date));

                    $(`#purchasing_period`).val(ConvertDateformat(itemData.project_s_date));
                    break;
                case 'Contacts':
                    if(field_up != ''){
                        $(`#${field_up}`).val(itemData.accountid);
                        $(`#${field_up}-input`).val(itemData.accountname);
                    }
                    
                    break;
            }
        }

        $(`#${fieldID}-modal`).modal('hide');
    }

    $.setPopupValue_Web = function(fieldID, itemData, field_up, field_down){
        
        switch(itemData?.moduleSelect){
            case 'Deal':
                $(`#${fieldID}`).val(itemData.id)
                $(`#${fieldID}-input`).val(itemData.deal_no)
                $(`#input-${fieldID}`).val(itemData.deal_no)
                break;
            default:
                $(`#${fieldID}`).val(itemData.id)
                $(`#${fieldID}-input`).val(itemData.name)
                $(`#input-${fieldID}`).val(itemData.name)
                break;
        }
        
        if(field_down !== '' && field_down !== undefined){
            $(`#${field_down}-input`).val('')
            $(`#${field_down}`).val('')
        }

        if(itemData.moduleSelect !== undefined){
            switch(itemData.moduleSelect){
                case 'Accounts':
                    $(`#mobile`).val(itemData.mobile);
                    $(`#village`).val(itemData.village);
                    $(`#room_no`).val(itemData.room_no);
                    $(`#address_no`).val(itemData.address_no);
                    $(`#village_no`).val(itemData.village_no);
                    $(`#lane`).val(itemData.lane);

                    $(`#address`).val(itemData.address);
                    $(`#sub_district`).val(itemData.sub_district);
                    $(`#district`).val(itemData.district);
                    $(`#province`).val(itemData.province);
                    $(`#postal_code`).val(itemData.postal_code);
                    break;
                case 'Contacts':
                    $(`#${field_up}`).val(itemData.accountid);
                    $(`#${field_up}-input`).val(itemData.accountname);
                break;
            }
        }

        $(`#${fieldID}-modal`).modal('hide');
    }

    $.searchShowMulti = function(modalID, obj, count, settype) {
        var moduleSelect = $(obj).data('moduleselect')
        var fieldID = $(obj).data('field')
        var selectfield = $(`#${fieldID}-modal-select-box`).val()
        var search = $(`#${fieldID}-modal-search-box`).val()
        /**/
        $(`#${fieldID}-modal-select-hidden`).val(selectfield)
        $(`#${fieldID}-modal-search-hidden`).val(search)
        /**/
        $(`#${modalID}`).modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
        getPopupListMulti(moduleSelect,fieldID,count,settype)
    }

    $.searchShowMobileMulti = function(modalID, obj, count, settype) {
        var moduleSelect = $(obj).data('moduleselect')
        var fieldID = $(obj).data('field')
        
        $(`#${modalID}`).modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
        getPopupListMobileMulti(moduleSelect,fieldID,count,settype)
    }

    

    $.searchRecordMulti = function(modalID, obj, count, settype) {
        var moduleSelect = $(obj).data('moduleselect')
        var fieldID = $(obj).data('field')
        var selectfield = $(`#${fieldID}-modal-select-box`).val()
        var search = $(`#${fieldID}-modal-search-box`).val()
        /**/
        $(`#${fieldID}-modal-select-hidden`).val(selectfield)
        $(`#${fieldID}-modal-search-hidden`).val(search)
        /**/
        $(`#${modalID}`).modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
        getPopupListMulti(moduleSelect,fieldID,count,settype,search,selectfield)
    }

    $.getNavigationMulti = function(modalID, obj, count, settype, event) {
        
        if($(obj).hasClass("disable_click") === true) return false;
        var Page =  eval($(obj).attr('data-page')); 
        var moduleSelect = $(obj).data('moduleselect')
        var fieldID = $(obj).data('field')
        var selectfield = $(`#${fieldID}-modal-select-hidden`).val()
        var search = $(`#${fieldID}-modal-search-hidden`).val()

        $(`#${modalID}`).modal({
            backdrop: 'static',
            keyboard: false
        }).modal('show');
        getPopupListMulti(moduleSelect,fieldID,count,settype,search,selectfield,Page)
        event.preventDefault()
    }

    $.refreshSpecialPrice = function(crmID){
        var productList = []
        $('#proTab').find('input[name=item_productid]').each(function(i, e){
            productList.push($(this).val())
        })

        $('#btn-recalculate').removeClass('btn-outline-primary').addClass('btn-outline-secondary').text('Recalculating...').prop('disabled', true)
        $.post(`${SITE_URL}WB_Service_AI/quotes/update_product_special_price`, {crmID, productList}, function(rs){
            $('#btn-recalculate').text('Recalculate').prop('disabled', false).removeClass('btn-outline-secondary').addClass('btn-outline-primary')
            $.post(`${SITE_URL}MOAIMB-Webview/Projects/getDetailList`, {moduleSelect:'Projects', crmID}, function(res){
                // console.log(res)
                $(`#proTab > tbody`).html('')
                var productList = res?.products ?? []
                var total_est = 0
                var total_plan = 0
                var total_deli = 0
                var total_on_hand = 0
                var sum_onhand_total = 0
                if(productList.length > 0){
                    
                    $(productList).each(function( k,v ) {
                        var DateStart = '';
                        var DateEnd = '';

                        if(v.first_delivered_date != '1970-01-01' && v.first_delivered_date != '0000-00-00'){
                            var objectDate = new Date(v.first_delivered_date);
                            var day = objectDate.getDate();
                            var month = objectDate.getMonth();
                            var year = objectDate.getFullYear();
                            DateStart = day+"/"+(month + 1)+"/"+year;
                        }
                        if(v.last_delivered_date != '1970-01-01' && v.last_delivered_date != '0000-00-00'){
                            var objectDate = new Date(v.last_delivered_date);
                            var day = objectDate.getDate();
                            var month = objectDate.getMonth();
                            var year = objectDate.getFullYear();
                            DateEnd = day+"/"+(month + 1)+"/"+year;
                        }
                        
                        var onhand_total = v.remain_on_hand * v.listprice
                        sum_onhand_total = sum_onhand_total + onhand_total

                        var rowProducts = `<tr id="row${v.sequence_no}" class="row_data">
                            <td class="txt-center txt-middle">
                                <label>${v.sequence_no}</label>
                                <input type="hidden" name="item_productid" value="${v.productid}" />
                            </td>
                            <td>
                                <div class="mb-2">
                                    <input type="text" class="base-input base-input-text" id="productname${v.sequence_no}" name="productname${v.sequence_no}" readonly value="${v.productname}" title="${v.productname}">
                                    <textarea class="base-input base-input-text mt-5" id="descriptions${v.sequence_no}" name="descriptions${v.sequence_no}" rows="2" readonly  title="${v.comment}">${v.comment}</textarea>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text" id="product_brand${v.sequence_no}" name="product_brand${v.sequence_no}" readonly value="${v.product_brand}" title="${v.product_brand}">
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text" id="product_group${v.sequence_no}" name="product_group${v.sequence_no}" readonly value="${v.product_group}" title="${v.product_group}">
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text" id="accountname${v.sequence_no}" name="accountname${v.sequence_no}" readonly value="${v.accountname}" title="${v.accountname}">
                            </td>
                            <td style="display: flex;text-align: center;">
                                <div role="button" class="btn-p" onclick="$.addProductPlan('${v.productid}','${v.id}','${v.lineitem_id}')">P</div>
                                <div role="button" class="btn-d" onclick="$.addProductDelivered('${v.productid}','${v.id}','${v.accountname}','${v.accountid}','${v.lineitem_id}')">D</div>
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text first_delivered-${v.lineitem_id}" id="first_delivered_date${v.sequence_no}" name="first_delivered_date${v.sequence_no}" readonly value="${DateStart}" title="${DateStart}"> 
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text last_delivered-${v.lineitem_id}" id="last_delivered_date${v.sequence_no}" name="last_delivered_date${v.sequence_no}" readonly value="${DateEnd}" title="${DateEnd}"> 
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text estimated estimated-${v.lineitem_id}" id="estimated${v.sequence_no}" name="estimated${v.sequence_no}" readonly value="${v.estimated.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${v.estimated.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text plan plan-${v.lineitem_id}" id="plan${v.sequence_no}" name="plan${v.sequence_no}" readonly value="${v.plan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${v.plan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text delivered delivered-${v.lineitem_id}" id="delivered${v.sequence_no}" name="delivered${v.sequence_no}" readonly value="${v.delivered.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${v.delivered.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text remain_on_hand remain_on_hand-${v.lineitem_id}" id="remain_on_hand${v.sequence_no}" name="remain_on_hand${v.sequence_no}" readonly value="${v.remain_on_hand.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${v.remain_on_hand.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text" id="listprice${v.sequence_no}" name="listprice${v.sequence_no}" readonly value="${v.listprice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${v.listprice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                            </td>
                            <td>
                                <input type="text" class="base-input base-input-text" id="onhand_total${v.sequence_no}" name="onhand_total${v.sequence_no}" readonly value="${onhand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${onhand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                            </td>
                        </tr>`
                        
                        $(`#proTab > tbody`).append(rowProducts)

                        total_est += eval(v.estimated);
                        total_plan += eval(v.plan);
                        total_deli += eval(v.delivered);
                        total_on_hand += eval(v.remain_on_hand);
                    });
                }
                var rowProducts = `<tr id="row" class="row_data">
                        <td class="txt-center txt-middle"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="v-align-middle" align="right">
                            <span class="font-16 font-bold">Total</span></td>
                        <td>
                            <input type="text" class="base-input base-input-text" id="total_est" name="total_est" readonly value="${total_est.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${total_est.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                        </td>
                        <td>
                            <input type="text" class="base-input base-input-text" id="total_plan" name="total_plan" readonly value="${total_plan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${total_plan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                        </td>
                        <td>
                            <input type="text" class="base-input base-input-text" id="total_deli" name="total_deli" readonly value="${total_deli.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${total_deli.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                        </td>
                        <td>
                            <input type="text" class="base-input base-input-text" id="total_on_hand" name="total_on_hand" readonly value="${total_on_hand.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${total_on_hand.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                        </td>
                        <td></td>
                        <td>
                            <input type="text" class="base-input base-input-text" id="sum_onhand_total" name="sum_onhand_total" readonly value="${sum_onhand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}" title="${sum_onhand_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}">
                        </td>
                    </tr>`
                $(`#proTab > tbody`).append(rowProducts)
            }, 'json')
        }, 'json')
    }

    $.setPopupValue_WebMulti = function(fieldID, itemData, Count, Settype){
        $(`#${fieldID}`).val(itemData.id)
        $(`#input-${fieldID}`).val(itemData.name)
        
        if(itemData.moduleSelect !== undefined){
            switch(itemData.moduleSelect){
                case 'Accounts':
                    $(`#mobile`).val(itemData.mobile);
                    $(`#village`).val(itemData.village);
                    $(`#room_no`).val(itemData.room_no);
                    $(`#address_no`).val(itemData.address_no);
                    $(`#village_no`).val(itemData.village_no);
                    $(`#lane`).val(itemData.lane);

                    $(`#address`).val(itemData.address);
                    $(`#sub_district`).val(itemData.sub_district);
                    $(`#district`).val(itemData.district);
                    $(`#province`).val(itemData.province);
                    $(`#postal_code`).val(itemData.postal_code);
                    switch(Settype){
                        case 'ownerinventory':
                            $(`#sales_owner_name${Count}`).val(itemData.sale_owner);
                            $(`#owner_no${Count}`).val(itemData.account_no);
                            $(`#owner_name_th${Count}`).val(itemData.account_name_th);
                            $(`#owner_name_en${Count}`).val(itemData.account_name_en);
                            $(`#owner_group${Count}`).val(itemData.account_group);
                            $(`#owner_industry${Count}`).val(itemData.accountindustry);
                            $(`#owner_grade${Count}`).val(itemData.account_grade);
                        break;
                        case 'consultantinventory':
                            $(`#sales_consultant_name${Count}`).val(itemData.sale_owner);
                            $(`#consultant_no${Count}`).val(itemData.account_no);
                            $(`#consultant_name_th${Count}`).val(itemData.account_name_th);
                            $(`#consultant_name_en${Count}`).val(itemData.account_name_en);
                            $(`#consultant_group${Count}`).val(itemData.account_group);
                            $(`#consultant_industry${Count}`).val(itemData.accountindustry);
                            $(`#consultant_grade${Count}`).val(itemData.account_grade);
                        break;
                        case 'architectureinventory':
                            $(`#sales_architecture_name${Count}`).val(itemData.sale_owner);
                            $(`#architecture_no${Count}`).val(itemData.account_no);
                            $(`#architecture_name_th${Count}`).val(itemData.account_name_th);
                            $(`#architecture_name_en${Count}`).val(itemData.account_name_en);
                            $(`#architecture_group${Count}`).val(itemData.account_group);
                            $(`#architecture_industry${Count}`).val(itemData.accountindustry);
                            $(`#architecture_grade${Count}`).val(itemData.account_grade);
                        break;
                        case 'constructioninventory':
                            $(`#sales_construction_name${Count}`).val(itemData.sale_owner);
                            $(`#construction_no${Count}`).val(itemData.account_no);
                            $(`#construction_name_th${Count}`).val(itemData.account_name_th);
                            $(`#construction_name_en${Count}`).val(itemData.account_name_en);
                            $(`#construction_group${Count}`).val(itemData.account_group);
                            $(`#construction_industry${Count}`).val(itemData.accountindustry);
                            $(`#construction_grade${Count}`).val(itemData.account_grade);
                        break;
                        case 'designerinventory':
                            $(`#sales_designer_name${Count}`).val(itemData.sale_owner);
                            $(`#designer_no${Count}`).val(itemData.account_no);
                            $(`#designer_name_th${Count}`).val(itemData.account_name_th);
                            $(`#designer_name_en${Count}`).val(itemData.account_name_en);
                            $(`#designer_group${Count}`).val(itemData.account_group);
                            $(`#designer_industry${Count}`).val(itemData.accountindustry);
                            $(`#designer_grade${Count}`).val(itemData.account_grade);
                        break;
                        case 'contractorinventory':
                            $(`#sales_contractor_name${Count}`).val(itemData.sale_owner);
                            $(`#contractor_no${Count}`).val(itemData.account_no);
                            $(`#contractor_name_th${Count}`).val(itemData.account_name_th);
                            $(`#contractor_name_en${Count}`).val(itemData.account_name_en);
                            $(`#contractor_group${Count}`).val(itemData.account_group);
                            $(`#contractor_industry${Count}`).val(itemData.accountindustry);
                            $(`#contractor_grade${Count}`).val(itemData.account_grade);
                        break;
                        case 'subcontractorinventory':
                            $(`#sales_sub_contractor_name${Count}`).val(itemData.sale_owner);

                            $(`#sub_contractor_no${Count}`).val(itemData.account_no);
                            $(`#sub_contractor_name_th${Count}`).val(itemData.account_name_th);
                            $(`#sub_contractor_name_en${Count}`).val(itemData.account_name_en);
                            $(`#sub_contractor_group${Count}`).val(itemData.account_group);
                            $(`#sub_contractor_industry${Count}`).val(itemData.accountindustry);
                            $(`#sub_contractor_grade${Count}`).val(itemData.account_grade);

                        break;
                    }
                    break;
                case 'Contacts':
                    switch(Settype){
                        case 'ownerinventory':
                            $(`#service_level_owner${Count}`).val(itemData.service_level);
                        break;
                        case 'consultantinventory':
                            $(`#service_level_consultant${Count}`).val(itemData.service_level);
                        break;
                        case 'architectureinventory':
                            $(`#service_level_architecture${Count}`).val(itemData.service_level);
                        break;
                        case 'constructioninventory':
                            $(`#service_level_construction${Count}`).val(itemData.service_level);
                        break;
                        case 'designerinventory':
                            $(`#service_level_designer${Count}`).val(itemData.service_level);
                        break;
                        case 'contractorinventory':
                            $(`#service_level_contractor${Count}`).val(itemData.service_level);
                        break;
                        case 'subcontractorinventory':
                            $(`#service_level_sub_contractor${Count}`).val(itemData.service_level);
                        break;
                    }
                    break;
                case 'Products':
                    var crmID = $(`input[name=crmid]`).val()
                    var productID = itemData.id

                    var itemPrice = itemData.price
                    $.post(`${SITE_URL}WB_Service_AI/quotes/product_special_price`, {crmID, productID}, function(rs){
                        var sellingPrice = rs?.selling_price ?? ''
                        if(sellingPrice !== '') itemPrice = sellingPrice
                        $(`#listPrice${Count}`).val(itemPrice);
                    }, 'json')
                    switch(Settype){
                        case 'productinventory':
                            $(`#product_brand${Count}`).val(itemData.product_brand);
                            $(`#product_group${Count}`).val(itemData.product_group);
                            $(`#listPrice${Count}`).val(itemPrice);
                        break;
                    }
                    break;
                case 'Competitorproduct':
                    switch(Settype){
                        case 'competitorinventory':
                            $(`#competitor_brand${Count}`).val(itemData.competitor_product_brand);
                            $(`#comprtitor_product_group${Count}`).val(itemData.competitor_product_group);
                            $(`#comprtitor_product_size${Count}`).val(itemData.competitor_product_size);
                            $(`#comprtitor_product_thickness${Count}`).val(itemData.competitor_product_thickness);
                            $(`#competitor_price${Count}`).val(itemData.selling_price);
                        break;
                    
                    }
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