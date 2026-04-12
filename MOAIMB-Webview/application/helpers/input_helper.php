<?php
function getUI($params){
    $input = '';
    return $input;
}

function inputView($params){
    $type = $params['uitype'];
    $fieldLabel = $params['fieldlabel'];
    $fieldID = $params['columnname'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? '<span class="text-danger">*</span>':'';
    $value = $params['value'];

    switch($type){
        case '5':
            $value = $value != '' ? date('d/m/Y', strtotime($value)):$value;
            break;
        case '19':
        case '21':
            $value = nl2br($value);
            break;
        case '33':
            $value = is_array($value) ? implode(', ', $value):$value;
            break;
        case '53':
            $value = $params['value_name'];
            break;
        case '6':
        case '56':
            $value = $value == '1' ? 'Yes':'No';
            break;
        case '70':
            $value = $value != '' ? date('d/m/Y H:i:s', strtotime($value)):$value;
            break;
        case '57':
        case '73':
            $value = $params['value_name'];
            break;
        case '904':
            $value = $params['value_name'];
            break;
        case '201':
        case '914':
        case '931':
        case '938':
            $value = $params['name'];
            break;
        default:
            $value = $value;
            break;
    }

    $inputGroup = '<div class="mb-5">
            <label class="pl-5 mb-5 text-gray-3"><span id="label-'.$fieldID.'">'.$fieldLabel.'</span> </label>
            <div class="pl-5 mb-5">'.$value.'</div>
        </div>';

    return $inputGroup;
}

function inputView_Web($params){
    $type = $params['uitype'];
    $fieldLabel = $params['fieldlabel'];
    $fieldID = $params['columnname'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? '<span class="text-danger">*</span>':'';
    $value = $params['value'];

    switch($type){
        case '5':
            $value = $value != '' ? date('d/m/Y', strtotime($value)):$value;
            break;
        case '33':
            $value = is_array($value) ? implode(', ', $value):$value;
            break;
        case '53':
            $value = $params['value_name'];
            break;
        case '6':
        case '56':
            $value = $value == '1' ? 'Yes':'No';
            break;
        case '73':
            $value = $params['value_name'];
            break;
        case '201':
        case '938':
            $value = $params['name'];
            break;
        default:
            $value = $value;
            break;
    }

    $inputGroup = '<div class="row mb-5">
        <div class="col-lg-3 col-12 m-a">
        <label class="pl-5 mb-5 font-14 text-gray-3"><span id="label-'.$fieldID.'">'.$fieldLabel.'</span> '.$required.'</label>
        </div>
        <div class="col-lg-9 col-12">
        '.$value.'
        </div>
        </div>';

        /*$inputGroup = '<div class="mb-5">
            <label class="pl-5 mb-5 text-gray-3"><span id="label-'.$fieldID.'">'.$fieldLabel.'</span>'.$required.'</label>
            <div class="pl-5 mb-5">'.$value.'</div>
        </div>';*/

    return $inputGroup;
}

function inputGroup($params){
    $type = $params['uitype'];
    $fieldLabel = $params['fieldlabel'];
    $fieldID = $params['columnname'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? '<span class="text-danger">*</span>':'';

    $input = '';
    switch($type){
        case '1':
        case '2':
            $input = inputText($params);
            break;
        case '4':
            $input = inputNumbering($params);
            break;
        case '7':
        case '71':
            $input = inputTextNumber($params);
            break;
        case '11':
            $input = inputTextPhoneNumber($params);
            break;
        case '13':
            $input = inputTextEmail($params);
            break;
        case '19':
        case '21':
            $input = inputTextArea($params);
            break;
        case '15':
            $input = inputSelect($params);
            break;           
        case '5':
            $input = inputDatePicker($params);
            break;
        case '33':
            $input = inputSelectMulti($params);
            break;
        case '6':
        case '56':
            $input = inputCheckbox($params);
            break;
        case '914':
            $input = inputPopupSelectEvent($params);
            break;
        case '57':
        case '73':
        case '201':
        case '800':
        case '904':
        case '931':
        case '938':
            $input = inputPopupSelect($params);
            break;
        case '53':
            $input = inputAssignTo($params);
            break;
    }

    $inputGroup = '<div class="mb-5">
        <label class="pl-5 mb-5"><span id="label-'.$fieldID.'">'.$fieldLabel.'</span> '.$required.'</label>
        '.$input.'
        </div>';

    return $inputGroup;
}

function inputGroup_Web($params){
    $type = $params['uitype'];
    $fieldLabel = $params['fieldlabel'];
    $fieldID = $params['columnname'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? '<span class="text-danger">*</span>':'';

    $input = '';
    switch($type){
        case '1':
        case '2':
            $input = inputText($params);
            break;
        case '4':
            $input = inputNumbering($params);
            break;
        case '7':
        case '71':
            $input = inputTextNumber($params);
            break;
        case '11':
            $input = inputTextPhoneNumber($params);
            break;
        case '19':
        case '21':
            $input = inputTextArea($params);
            break;
        case '15':
            $input = inputSelect($params);
            break;           
        case '5':
            $input = inputDatePicker($params);
            break;
        case '33':
            $input = inputSelectMulti($params);
            break;
        case '6':
        case '56':
            $input = inputCheckbox($params);
            break;
        case '57':
        case '73':
        case '201':
        case '301':
        case '800':
        case '938':
        case '1000':
            $input = inputPopupSelect_Web($params);
            break;
        case '53':
            $input = inputAssignTo_Web($params);
            break;
    }

    $inputGroup = '<div class="row mb-5">
        <div class="col-lg-3 col-12 m-a">
        <label class="pl-5 mb-5"><span id="label-'.$fieldID.'">'.$fieldLabel.'</span> '.$required.'</label>
        </div>
        <div class="col-lg-9 col-12">
        '.$input.'
        </div>
        </div>';

    return $inputGroup;
}

function inputView_Placeholder($params)
{
    $type = $params['uitype'];
    $fieldLabel = $params['fieldlabel'];
    $fieldID = $params['columnname'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? '<span class="text-danger">*</span>':'';

    $input = '';
    switch($type){
        case '1':
        case '2':
        case '7':
            $input = inputText_Place($params);
            break;
    }

    $inputGroup = '<div class="mb-5">
        '.$input.'
        </div>';

    return $inputGroup;
}

function inputText_Place($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $fieldLabel = $params['fieldlabel'];
    $value = $params['value'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = @$params['readonly'] == '1' ? 'readonly':'';

    $input = '<input type="text" class="base-input '.$fieldClass.'" id="'.$fieldID.'" name="'.$fieldName.'" value="'.$value.'" '.$required.' '.$readOnly.' placeholder="'.$fieldLabel.'">';
    return $input;
}

function inputNumbering($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $value = $params['value'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = @$params['readonly'] == '1' ? 'readonly':'';

    $input = '<input type="text" class="base-input base-input-text '.$fieldClass.'" id="'.$fieldID.'" name="'.$fieldName.'" value="'.$value.'" '.$required.' '.$readOnly.'>';
    return $input;
}

function inputText($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $value = $params['value'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = @$params['readonly'] == '1' ? 'readonly':'';

    $input = '<input type="text" class="base-input '.$fieldClass.'" id="'.$fieldID.'" name="'.$fieldName.'" value="'.$value.'" '.$required.' '.$readOnly.'>';
    return $input;
}

function inputTextEmail($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $value = $params['value'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = @$params['readonly'] == '1' ? 'readonly':'';

    $input = '<input type="email" class="base-input '.$fieldClass.'" id="'.$fieldID.'" name="'.$fieldName.'" value="'.$value.'" '.$required.' '.$readOnly.'>';
    return $input;
}

function inputTextNumber($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $value = $params['value'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = @$params['readonly'] == '1' ? 'readonly':'';

    if($fieldName == 'listprice'){
        $input = '<input type="text" class="base-input '.$fieldClass.'" id="'.$fieldID.'" name="'.$fieldName.'" value="'.$value.'" '.$required.' '.$readOnly.' onkeyup="$.keyItemAmount(this);" onkeypress=" return isNumberPricelist(event);">';
    }else{
        $input = '<input type="text" class="base-input '.$fieldClass.'" id="'.$fieldID.'" name="'.$fieldName.'" value="'.$value.'" '.$required.' '.$readOnly.' onkeypress="return isPhoneNumber(event)">';
    }
    
    return $input;
}

function inputTextPhoneNumber($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $value = $params['value'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = @$params['readonly'] == '1' ? 'readonly':'';

    $input = '<input type="text" class="base-input '.$fieldClass.'" id="'.$fieldID.'" name="'.$fieldName.'" value="'.$value.'" '.$required.' '.$readOnly.' maxlength="12" onkeypress="return isPhoneNumber(event)">';
    return $input;
}

function inputNumber($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $value = $params['value'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = @$params['readonly'] == '1' ? 'readonly':'';
    
    $input = '<input type="number" class="base-input '.$fieldClass.'" id="'.$fieldID.'" name="'.$fieldName.'" value="'.$value.'" '.$required.' '.$readOnly.'>';
    return $input;
}

function inputTextArea($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    //$value = $params['value'];
    $value = str_replace("&lt;br /&gt;","<br>",$params['value']);
    //str_replace("&lt;br /&gt;","<br>",$col_fields[$fieldname]);
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = @$params['readonly'] == '1' ? 'readonly':'';
    $rows = @$params['rows'] == '' ? '3':$params['rows'];

    $input = '<textarea class="base-input '.$fieldClass.'" id="'.$fieldID.'" name="'.$fieldName.'" '.$required.' '.$readOnly.' rows="'.$rows.'">'.$value.'</textarea>';
    return $input;
}

function inputSelect($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $value = $params['value'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = @$params['readonly'] == '1' ? 'readonly':'';
    $pickListData = $params['value_default'];
    $disabled = @$params['readonly'] == '1' ? 'disabled':'';
    $module = @$params['module'];

    if($fieldName == 'project_status' && $module == 'Quotes'){
        $input = '<input type="text" class="base-input '.$fieldClass.'" id="'.$fieldID.'" name="'.$fieldName.'" value="'.$value.'" '.$required.' '.$readOnly.'>';
    }else{
        $options = '';
        foreach($pickListData as $option){
            $selected = $option == $value ? 'selected':'';
            $options .= '<option '.$selected.' value="'.$option.'" >'.$option.'</option>';
        }

        $input = '<select class="base-select '.$fieldClass.'" id="'.$fieldID.'" name="'.$fieldName.'" '.$required.' '.$readOnly.' '.$disabled.'>'.$options.'</select>';
    }
    

    return $input;
}

function inputSelectMulti($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $value = explode(", ", $params['value']);
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = @$params['readonly'] == '1' ? 'readonly':'';
    $pickListData = $params['value_default'];

    $options = '';
    foreach($pickListData as $option){
        if(is_array($value)){
            if(in_array($option, $value)){
                $selected = 'selected';
            } else {
                $selected = '';
            }
        }else{
            $selected = $option != '' && $option == $value ? 'selected':'';
        }
        
        $options .= '<option '.$selected.' value="'.$option.'">'.$option.'</option>';
    }

    $input = '<select class="base-select select-multi '.$fieldClass.'" id="'.$fieldID.'" name="'.$fieldName.'[]" style="width:100%" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="1" '.$required.' '.$readOnly.'>'.$options.'</select>';
    return $input;
}

function inputDatePicker($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $value = $params['value'] != '' ? date('d/m/Y', strtotime($params['value'])):'';
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = @$params['readonly'] == '1' ? 'readonly':'';

    $input = '<div class="base-input-group">
        <input type="text" class="base-input-text datepicker_input '.$fieldClass.'" id="'.$fieldID.'" value="'.$value.'" name="'.$fieldName.'" '.$required.' '.$readOnly.' placeholder="DD/MM/YYYY">
        <div class="base-input-group-action">
            <i class="ph-calendar-blank cursor-pointer" for="'.$fieldID.'"></i>
        </div>
    </div>';

    return $input;
}

function inputCheckbox($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $value = $params['value'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = @$params['readonly'] == '1' ? 'readonly':'';
    //$pickListData = $params['pickListData'];
    $onclick = @$params['readonly'] == '1' ? 'onclick="return false;"':'';
    $checked = @$params['value'] == '1' ? 'checked':'';
    $checkBoxs = '';
    //foreach($pickListData as $index => $checkbox){
        $checkBoxs .= '<div class="form-check">
            <input class="form-check-input" type="checkbox" value="'.$value.'" name="'.$fieldName.'" id="'.$fieldID.'" '.$required.' '.$readOnly.' '.$checked.' '.$onclick.' >
        </div>';
    //}

    return $checkBoxs;
}

function inputPopupSelect($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $fieldLabel = $params['fieldlabel'];
    $value = $params['value'];
    $valueInput = $params['value_name'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = 'readonly';

    $relate_field_up = @$params['relate_field_up'][0];
    $relate_field_down = @$params['relate_field_down'][0];

    $input = '<div class="base-input-group">
        <input type="text" class="base-input-text '.$fieldClass.'" id="'.$fieldID.'-input" value="'.$valueInput.'" name="'.$fieldName.'-input" '.$required.' '.$readOnly.' placeholder="ค้นหา">
        <input type="hidden" id="'.$fieldID.'" value="'.$value.'" name="'.$fieldName.'">
        
        <div class="base-input-group-action">
            <i class="ph-magnifying-glass cursor-pointer" for="'.$fieldID.'" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.searchShow(\''.$fieldID.'-modal\', this, \''.$relate_field_up.'\', \''.$relate_field_down.'\')"></i>
        </div>
    </div>';

    $input .= '<div class="modal modal-bottom fade popup-search-modal" id="'.$fieldID.'-modal" tabindex="-1" role="dialog" aria-labelledby="bottom_modal" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <div class="flex">
                            <div class="flex-none px-5 cursor-pointer" style="padding-top:3px" onclick="$.closeModal(\''.$fieldID.'-modal\');">
                                <i class="ph-caret-left font-18"></i>
                            </div>
                            <div class="flex-1 pl-5" id="'.$fieldID.'-modal-title">
                                ค้นหา '.$fieldLabel.'
                            </div>
                        </div>                    
                    </div>
                    <span class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ph-x font-18 cursor-pointer" onclick="$.closeModal(\''.$fieldID.'-modal\');"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="row pb-10 modal-sticky-top">
                        <div class="col">
                            <div class="base-input-group bg-white">
                                <input type="text" class="base-input-text bg-white input-popup-search" id="'.$fieldID.'-modal-search-box" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" data-relate-field-down="'.$relate_field_down.'" data-relate-field-up="'.$relate_field_up.'" placeholder="ค้นหา">
                                <div class="base-input-group-action">
                                    <i class="ph-magnifying-glass"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="list-'.$params['module_select'].'-'.$fieldID.'" class="list-item-popup">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>';

    return $input;
}

function inputPopupSelectEvent($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $fieldLabel = $params['fieldlabel'];
    $value = $params['value'];
    $valueInput = $params['value_name'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = 'readonly';
    $value_default = $params['value_default'];
    $module_default = $params['module_select'];

    $options = '';
    foreach($value_default as $option){
       
        if($option == 'Deal'){
            $module_select = 'Deal';
        }else if($option == 'Case'){
            $module_select = 'HelpDesk';
        }else if($option == 'Project Order'){
            $module_select = 'Projects';
        }else{
            $module_select = $option;
        }
        $selected = $module_default == $module_select ? 'selected':'';

        $options .= '<option '.$selected.' value="'.$module_select.'" >'.$option.'</option>';
    }

    $input = '<select class="base-select '.$fieldClass.'" id="event_type" name="event_type" '.$required.' '.$readOnly.'>'.$options.'</select>';

    $input .= '<div class="base-input-group" style="margin-top:10px">
        <input type="text" class="base-input-text '.$fieldClass.'" id="'.$fieldID.'-input" value="'.$valueInput.'" name="'.$fieldName.'-input" '.$required.' '.$readOnly.' placeholder="ค้นหา">
        <input type="hidden" id="'.$fieldID.'" value="'.$value.'" name="'.$fieldName.'">
        
        <div class="base-input-group-action">
            <i class="ph-magnifying-glass cursor-pointer" for="'.$fieldID.'" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.searchShowEvent(\''.$fieldID.'-modal\', this)"></i>
        </div>
    </div>';

    $input .= '<div class="modal modal-bottom fade popup-search-modal" id="'.$fieldID.'-modal" tabindex="-1" role="dialog" aria-labelledby="bottom_modal" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <div class="flex">
                            <div class="flex-none px-5 cursor-pointer" style="padding-top:3px" onclick="$.closeModal(\''.$fieldID.'-modal\');">
                                <i class="ph-caret-left font-18"></i>
                            </div>
                            <div class="flex-1 pl-5" id="'.$fieldID.'-modal-title">
                                ค้นหา '.$fieldLabel.'
                            </div>
                        </div>                    
                    </div>
                    <span class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ph-x font-18 cursor-pointer" onclick="$.closeModal(\''.$fieldID.'-modal\');"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="row pb-10 modal-sticky-top">
                        <div class="col">
                            <div class="base-input-group bg-white">
                                <input type="text" class="base-input-text bg-white input-popup-search-event" id="'.$fieldID.'-modal-search-box" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" placeholder="ค้นหา">
                                <div class="base-input-group-action">
                                    <i class="ph-magnifying-glass"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="list-'.$fieldID.'" class="list-item-popup">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>';

    return $input;
}

function inputPopupAccountMulti($params)
{
    //$fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $fieldName = $params['fieldName'];
    $modal = $params['modal'];
    $fieldLabel = $params['fieldlabel'];
    $value = $params['value'];
    $valueInput = $params['value_name'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = 'readonly';

    $input = '<div class="base-input-group">
        <input type="text" class="base-input-text '.$fieldClass.'" id="'.$fieldID.'-input" value="'.$valueInput.'" name="'.$fieldID.'-input" '.$required.' '.$readOnly.' placeholder="ค้นหา'.$fieldLabel.'">
        <input type="hidden" id="'.$fieldID.'" value="'.$value.'" name="'.$fieldName.'">
        <div class="base-input-group-action">
            <i class="ph-magnifying-glass cursor-pointer" for="'.$fieldID.'" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.searchShow(\''.$modal.'-modal\', this)"></i>
        </div>
    </div>';

    $input .= '<div class="modal modal-bottom fade popup-search-modal" id="'.$modal.'-modal" tabindex="-1" role="dialog" aria-labelledby="bottom_modal" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <div class="flex">
                            <div class="flex-none px-5 cursor-pointer" style="padding-top:3px" onclick="$.closeModal(\''.$modal.'-modal\');">
                                <i class="ph-caret-left font-18"></i>
                            </div>
                            <div class="flex-1 pl-5" id="'.$modal.'-modal-title">
                                ค้นหา '.$fieldLabel.'
                            </div>
                        </div>                    
                    </div>
                    <span class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ph-x font-18 cursor-pointer" onclick="$.closeModal(\''.$modal.'-modal\');"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="row pb-10 modal-sticky-top">
                        <div class="col">
                            <div class="base-input-group bg-white">
                                <input type="text" class="base-input-text bg-white input-popup-search" id="'.$fieldID.'-modal-search-box" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" placeholder="ค้นหา">
                                <div class="base-input-group-action">
                                    <i class="ph-magnifying-glass"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="list-'.$params['module_select'].'-'.$fieldID.'" class="list-item-popup">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>';

    return $input;
}

function inputPopupProductsMulti($params){

}

function inputPopupMulti($params)
{
    $fieldID = $params['columnname'];
    $fieldName = $params['fieldName'];
    $modal = $params['modal'];
    $fieldLabel = $params['fieldlabel'];
    $value = $params['value'];
    $valueInput = $params['value_name'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = 'readonly';
    $filter = @$params['configmodule'][$params['module_select']];
    $Count = @$params['count'];
    $Settype = @$params['settype'];

    $hiddenLabel = '';
    switch($Settype){
        case 'productinventory':
            $hiddenLabel = 'Product items';
            break;
        case 'competitorinventory':
            $hiddenLabel = 'Com. Product items';
            break;
        case 'designerinventory':
            $hiddenLabel = 'ชื่อผู้ออกแบบโครงการ';
            break;
        case 'architectureinventory':
            $hiddenLabel = 'ชื่อสถาปนิกโครงการ';
            break;
        case 'ownerinventory':
            $hiddenLabel = 'ชื่อเจ้าของโครงการ';
            break;
        case 'consultantinventory':
            $hiddenLabel = 'ชื่อที่ปรึกษาโครงการ';
            break;
        case 'constructioninventory':
            $hiddenLabel = 'ชื่อช่างก่อสร้างโครงการ';
            break;
        case 'contractorinventory':
            $hiddenLabel = 'ชื่อผู้รับเหมาโครงการ';
            break;
        case 'subcontractorinventory':
            $hiddenLabel = 'ชื่อผู้รับเหมาย่อยโครงการ';
            break;
    }
    
    $input = '<div class="base-input-group">
        <span id="label-'.$fieldID.'" style="display: none;">'.$hiddenLabel.'</span>
        <input type="text" class="base-input-text '.$fieldClass.'" id="'.$fieldName.'" value="'.$valueInput.'" name="'.$fieldName.'" '.$readOnly.' placeholder="ค้นหา">
        <input type="hidden" id="'.$fieldID.'" value="'.$value.'" '.$required.' name="'.$fieldID.'">
        
        <div class="base-input-group-action">
            <i class="ph-magnifying-glass cursor-pointer" for="'.$fieldID.'" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.searchShowMulti(\''.$fieldID.'-modal\', this ,\''.$Count.'\',\''.$Settype.'\')"></i>
        </div>
    </div>';

    $input .=  '<div class="modal fade" id="'.$fieldID.'-modal" tabindex="-1" role="dialog" aria-labelledby="bottom_modal" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog modal-lg"" role="document">

                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title">
                                <div class="flex">
                                     <!-- <div class="flex-none px-5 cursor-pointer" style="padding-top:3px" onclick="$.closeModal(\''.$fieldID.'-modal\');">
                                       <i class="ph-caret-left font-18"></i>
                                    </div> -->
                                    <div class="flex-1 pl-5" id="'.$fieldID.'-modal-title">
                                        '.$params['module_select'].' Search 
                                    </div>
                                </div>                    
                            </div>
                            <span class="close" data-dismiss="modal" aria-label="Close">
                                <i class="ph-x font-18 cursor-pointer" onclick="$.closeModal(\''.$fieldID.'-modal\');"></i>
                            </span>
                        </div>

                        <div class="modal-body" style="background-color:#FFF !important;">
                            <div class="row pb-5 modal-sticky-top" style="background-color:#FFF !important;">
                                <div class="col-4" style="padding-right:1px!important;">
                                        <select class="base-select" id="'.$fieldID.'-modal-select-box"" name="'.$fieldID.'-modal-select-box"style="background-color:#FFF !important;">';

                                            foreach ($filter['search'] as $key => $value) {
                                                $input .= '<option value="'.$key.'">'.$value.'</option>';
                                            }
                                        
                                        $input .= '</select>
                                    
                                </div>
                                <div class="col-8" style="padding-left:1px!important;padding-right:24px !important;">
                                    <div class="base-input-group bg-white">
                                        <input type="hidden" id="'.$fieldID.'-modal-search-hidden" value="">
                                        <input type="hidden" id="'.$fieldID.'-modal-select-hidden" value="">
                                        <input type="text" class="base-input-text bg-white input-popup-search" id="'.$fieldID.'-modal-search-box" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" placeholder="ค้นหา" onkeypress="myKeyPress(\''.$fieldID.'-modal\', this,\''.$Count.'\',\''.$Settype.'\',event)">

                                        <div class="base-input-group-action cursor-pointer" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.searchRecordMulti(\''.$fieldID.'-modal\', this,\''.$Count.'\',\''.$Settype.'\')">
                                            <i class="ph-magnifying-glass"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8" style="padding-left:1px!important;">

                                </div>
                            </div>

                            <div class="row p-5 " id="header-'.$fieldID.'" style="padding-left:12px !important; padding-right:23px !important;">

                            </div>

                            <div id="list-'.$params['module_select'].'-'.$fieldID.'" class="list-item-popup-web row p-5 px-15 " style="display:block">
                                
                            </div>
                        </div>

                        <div class="modal-footer" style="display:unset;">
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4 txt-center m-a">
                                <label>
                                    <span id="record-start-'.$fieldID.'">0</span> - <span id="record-end-'.$fieldID.'">0</span> of <span id="record-total-'.$fieldID.'">0</span>
                                </label>
                                </div>
                                <div class="col-4 txt-center">
                                    
                                    <i class="ph-bold ph-caret-double-left start-page" role="button" id="start-'.$fieldID.'" data-page="" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.getNavigationMulti(\''.$fieldID.'-modal\', this,\''.$Count.'\',\''.$Settype.'\', event)"></i>
                                    <i class="ph-bold ph-caret-left ml-5 previous-page" role="button" id="previous-'.$fieldID.'" data-page="" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.getNavigationMulti(\''.$fieldID.'-modal\', this,\''.$Count.'\',\''.$Settype.'\', event)"></i>
                                    
                                    <input type="text" class="base-input-text w-20 bg-white pagenumber ml-5 txt-center input-pagenumber" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" id="page-num-'.$fieldID.'" value="1">
                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-'.$fieldID.'"> 1 </span>
                                    
                                    <i class="ph-bold ph-caret-right ml-5 mr-5 next-page" role="button" id="next-'.$fieldID.'" data-page="" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.getNavigationMulti(\''.$fieldID.'-modal\', this,\''.$Count.'\',\''.$Settype.'\', event)"></i>
                                    <i class="ph-bold ph-caret-double-right mr-5 end-page" role="button" id="end-'.$fieldID.'" data-page="" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.getNavigationMulti(\''.$fieldID.'-modal\', this,\''.$Count.'\',\''.$Settype.'\', event)"></i>
                                    
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                </div>';
    return $input;
}

function inputPopupMobileMulti($params)
{
    $fieldID = $params['columnname'];
    $fieldName = $params['fieldName'];
    $modal = $params['modal'];
    $fieldLabel = $params['fieldlabel'];
    $value = $params['value'];
    $valueInput = $params['value_name'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = 'readonly';
    $filter = @$params['configmodule'][$params['module_select']];
    $Count = @$params['count'];
    $Settype = @$params['settype'];

    /*<i class="ph-magnifying-glass cursor-pointer" for="'.$fieldID.'" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.searchShow(\''.$fieldID.'-modal\', this)"></i>*/
    $input = '<div class="base-input-group">
        <input type="text" class="base-input-text '.$fieldClass.'" id="'.$fieldName.'" value="'.$valueInput.'" name="'.$fieldName.'" '.$required.' '.$readOnly.' placeholder="ค้นหา">
        <input type="hidden" id="'.$fieldID.'" value="'.$value.'" name="'.$fieldID.'">
        
        <div class="base-input-group-action">
            <i class="ph-magnifying-glass cursor-pointer" for="'.$fieldID.'" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.searchShowMobileMulti(\''.$fieldID.'-modal\', this ,\''.$Count.'\',\''.$Settype.'\')"></i>
        </div>
    </div>';

    $input .= '<div class="modal modal-bottom fade popup-search-modal" id="'.$fieldID.'-modal" tabindex="-1" role="dialog" aria-labelledby="bottom_modal" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <div class="flex">
                            <div class="flex-none px-5 cursor-pointer" style="padding-top:3px" onclick="$.closeModal(\''.$fieldID.'-modal\');">
                                <i class="ph-caret-left font-18"></i>
                            </div>
                            <div class="flex-1 pl-5" id="'.$fieldID.'-modal-title">
                                ค้นหา '.$fieldLabel.'
                            </div>
                        </div>                    
                    </div>
                    <span class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ph-x font-18 cursor-pointer" onclick="$.closeModal(\''.$fieldID.'-modal\');"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="row pb-10 modal-sticky-top">
                        <div class="col">
                            <div class="base-input-group bg-white">
                                <input type="text" class="base-input-text bg-white input-popup-search" id="'.$fieldID.'-modal-search-box" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" placeholder="ค้นหา">
                                <div class="base-input-group-action">
                                    <i class="ph-magnifying-glass"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="list-'.$params['module_select'].'-'.$fieldID.'" class="list-item-popup">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>';
    return $input;
}

function inputPopupAccountsProducts($params)
{
    $fieldID = $params['columnname'];
    $fieldName = $params['fieldName'];
    $modal = $params['modal'];
    $fieldLabel = $params['fieldlabel'];
    $value = $params['value'];
    $valueInput = $params['value_name'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = 'readonly';

    $input = '<div class="base-input-group">
        <input type="text" class="base-input-text '.$fieldClass.'" id="'.$fieldName.'" value="'.$valueInput.'" name="'.$fieldName.'" '.$required.' '.$readOnly.' placeholder="ค้นหา'.$fieldLabel.'">
        <input type="hidden" id="'.$fieldID.'" value="'.$value.'" name="'.$fieldID.'">
        <div class="base-input-group-action">
            <i class="ph-magnifying-glass cursor-pointer" for="'.$fieldID.'" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.searchShow(\''.$modal.'-modal\', this)"></i>
        </div>
    </div>';

    $input .= '<div class="modal modal-bottom fade popup-search-modal" id="'.$modal.'-modal" tabindex="-1" role="dialog" aria-labelledby="bottom_modal" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <div class="flex">
                            <div class="flex-none px-5 cursor-pointer" style="padding-top:3px" onclick="$.closeModal(\''.$modal.'-modal\');">
                                <i class="ph-caret-left font-18"></i>
                            </div>
                            <div class="flex-1 pl-5" id="'.$modal.'-modal-title">
                                ค้นหา '.$fieldLabel.'
                            </div>
                        </div>                    
                    </div>
                    <span class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ph-x font-18 cursor-pointer" onclick="$.closeModal(\''.$modal.'-modal\');"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="row pb-10 modal-sticky-top">
                        <div class="col">
                            <div class="base-input-group bg-white">
                                <input type="text" class="base-input-text bg-white input-popup-search" id="'.$fieldID.'-modal-search-box" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" placeholder="ค้นหา">
                                <div class="base-input-group-action">
                                    <i class="ph-magnifying-glass"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="list-'.$params['module_select'].'-'.$fieldID.'" class="list-item-popup">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>';

    return $input;
}

function inputAssignTo($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $fieldLabel = $params['fieldlabel'];
    $value = $params['value'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = ''; //@$params['readonly'] == '1' ? 'readonly':'';

    $input = '<div class="btn-group">
            <input type="radio" class="btn-check check-assign" name="assign_to" id="assign-to-user" autocomplete="off" value="user" checked>
            <label class="btn btn-group-label btn-outline-default" for="assign-to-user">ผู้ใช้</label>

            <input type="radio" class="btn-check check-assign" name="assign_to" id="assign-to-group" autocomplete="off" value="group">
            <label class="btn btn-group-label btn-outline-default" for="assign-to-group">กลุ่มผู้ใช้</label>
        </div>
    ';

    $assignToUserList = '';
    $assignToGroupList = '';
    //alert($params); exit;
    foreach($params['value_default'] as $arr){
        switch($arr['type']){
            case 'user':
                foreach($arr['type_value'] as $row){
                    $selected = $value == $row['id'] ? 'selected':'';
                    $assignToUserList .= '<option '.$selected.' value="'.$row['id'].'">'.$row['name'].'</option>';
                }
                break;
            case 'group':
                if(!empty($arr['type_value'])){
                    foreach(@$arr['type_value'] as $row){
                        $selected = @$value == @$row['id'] ? 'selected':'';
                        $assignToGroupList .= '<option '.@$selected.' value="'.@$row['id'].'">'.@$row['name'].'</option>';
                    }
                }
                break;

        }
    }

    $input .= '<div style="margin-top:10px">';
    $input .= '<select class="base-select check-assign-list'.$fieldClass.'" id="assign-to-user-list" name="assign_to_user" '.$required.' '.$readOnly.'>'.$assignToUserList.'</select>';
    $input .= '<select class="base-select check-assign-list'.$fieldClass.'" style="display:none" id="assign-to-group-list" name="assign_to_group" '.$required.' '.$readOnly.'>'.$assignToGroupList.'</select>';
    $input .= '</div>';

    return $input;
}

function inputAssignTo_Web($params)
{
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $fieldLabel = $params['fieldlabel'];
    $value = $params['value'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = ''; //@$params['readonly'] == '1' ? 'readonly':'';

    $input = '<div class="btn-group w-40">
            <input type="radio" class="btn-check check-assign" name="assign_to" id="assign-to-user" autocomplete="off" value="user" checked>
            <label class="btn btn-group-label btn-outline-default" for="assign-to-user">ผู้ใช้</label>

            <input type="radio" class="btn-check check-assign" name="assign_to" id="assign-to-group" autocomplete="off" value="group">
            <label class="btn btn-group-label btn-outline-default" for="assign-to-group">กลุ่มผู้ใช้</label>
        </div>
    ';

    $assignToUserList = '';
    $assignToGroupList = '';

    foreach($params['value_default'] as $arr){
        switch($arr['type']){
            case 'user':
                foreach($arr['type_value'] as $row){
                    $selected = $value == $row['id'] ? 'selected':'';
                    $assignToUserList .= '<option '.$selected.' value="'.$row['id'].'">'.$row['name'].'</option>';
                }
                break;
            case 'group':
                if(!empty($arr['type_value'])){
                    foreach($arr['type_value'] as $row){
                        $selected = $value == $row['id'] ? 'selected':'';
                        $assignToGroupList .= '<option '.$selected.' value="'.$row['id'].'">'.$row['name'].'</option>';
                    }
                }
                break;
        }
    }

    $input .= '<div style="margin-top:10px">';
    $input .= '<select class="base-select check-assign-list'.$fieldClass.'" id="assign-to-user-list" name="assign_to_user" '.$required.' '.$readOnly.'>'.$assignToUserList.'</select>';
    $input .= '<select class="base-select check-assign-list'.$fieldClass.'" style="display:none" id="assign-to-group-list" name="assign_to_group" '.$required.' '.$readOnly.'>'.$assignToGroupList.'</select>';
    $input .= '</div>';

    return $input;
}

function inputPopupSelect_Web($params)
{   
    $fieldName = $params['columnname'];
    $fieldID = $params['columnname'];
    $fieldLabel = $params['fieldlabel'];
    $value = $params['value'];
    $valueInput = $params['value_name'];
    $fieldClass = @$params['fieldClass'];
    $match = preg_match('/M/i', $params['typeofdata']);
    $required = $match ? 'required':'';
    $readOnly = 'readonly';
    $filter = @$params['configmodule'][$params['module_select']];

    $relate_field_up = @$params['relate_field_up'][0];
    $relate_field_down = @$params['relate_field_down'][0];
    
    $input = '<div class="base-input-group">
        <input type="text" class="base-input-text '.$fieldClass.'" id="'.$fieldID.'-input" value="'.$valueInput.'" name="'.$fieldName.'-input" '.$required.' '.$readOnly.' placeholder="ค้นหา">
        <input type="hidden" id="'.$fieldID.'" value="'.$value.'" name="'.$fieldName.'" '.$required.'>
        
        <div class="base-input-group-action">
            <i class="ph-magnifying-glass cursor-pointer" for="'.$fieldID.'" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.searchShow(\''.$fieldID.'-modal\', this, \''.$relate_field_up.'\', \''.$relate_field_down.'\')"></i>
        </div>
    </div>';

    $input .=  '<div class="modal fade" id="'.$fieldID.'-modal" tabindex="-1" role="dialog" aria-labelledby="bottom_modal" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog modal-lg"" role="document">

                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title">
                                <div class="flex">
                                     <!-- <div class="flex-none px-5 cursor-pointer" style="padding-top:3px" onclick="$.closeModal(\''.$fieldID.'-modal\');">
                                       <i class="ph-caret-left font-18"></i>
                                    </div> -->
                                    <div class="flex-1 pl-5" id="'.$fieldID.'-modal-title">
                                        '.$params['module_select'].' Search 
                                    </div>
                                </div>                    
                            </div>
                            <span class="close" data-dismiss="modal" aria-label="Close">
                                <i class="ph-x font-18 cursor-pointer" onclick="$.closeModal(\''.$fieldID.'-modal\');"></i>
                            </span>
                        </div>

                        <div class="modal-body" style="background-color:#FFF !important;">
                            <div class="row pb-5 modal-sticky-top" style="background-color:#FFF !important;">
                                <div class="col-4" style="padding-right:1px!important;">
                                        <select class="base-select" id="'.$fieldID.'-modal-select-box"" name="'.$fieldID.'-modal-select-box"style="background-color:#FFF !important;">';

                                            foreach ($filter['search'] as $key => $value) {
                                                $input .= '<option value="'.$key.'">'.$value.'</option>';
                                            }
                                        
                                        $input .= '</select>
                                    
                                </div>
                                <div class="col-8" style="padding-left:1px!important;padding-right:24px !important;">
                                    <div class="base-input-group bg-white">
                                        <input type="hidden" id="'.$fieldID.'-modal-search-hidden" value="">
                                        <input type="hidden" id="'.$fieldID.'-modal-select-hidden" value="">
                                        <input type="text" class="base-input-text bg-white input-popup-search" id="'.$fieldID.'-modal-search-box" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" data-relate-field-down="'.$relate_field_down.'" data-relate-field-up="'.$relate_field_up.'" placeholder="ค้นหา">
                                        <div class="base-input-group-action cursor-pointer" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.searchRecord(\''.$fieldID.'-modal\', this, \''.$relate_field_up.'\', \''.$relate_field_down.'\')">
                                            <i class="ph-magnifying-glass"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8" style="padding-left:1px!important;">

                                </div>
                            </div>

                            <div class="row p-5 " id="header-'.$fieldID.'" style="padding-left:12px !important; padding-right:23px !important;">

                            </div>

                            <div id="list-'.$params['module_select'].'-'.$fieldID.'" class="list-item-popup-web row p-5 px-15 " style="display:block">
                                
                            </div>
                        </div>

                        <div class="modal-footer" style="display:unset;">
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4 txt-center m-a">
                                <label>
                                    <span id="record-start-'.$fieldID.'">0</span> - <span id="record-end-'.$fieldID.'">0</span> of <span id="record-total-'.$fieldID.'">0</span>
                                </label>
                                </div>
                                <div class="col-4 txt-center">
                                    
                                    <i class="ph-bold ph-caret-double-left start-page" role="button" id="start-'.$fieldID.'" data-page="" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.getNavigation(\''.$fieldID.'-modal\', this, event, \''.$relate_field_up.'\', \''.$relate_field_down.'\')"></i>
                                    <i class="ph-bold ph-caret-left ml-5 previous-page" role="button" id="previous-'.$fieldID.'" data-page="" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.getNavigation(\''.$fieldID.'-modal\', this, event, \''.$relate_field_up.'\', \''.$relate_field_down.'\')"></i>
                                    
                                    <input type="text" class="base-input-text w-20 bg-white pagenumber ml-5 txt-center input-pagenumber" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" data-relate-field-down="'.$relate_field_down.'" data-relate-field-up="'.$relate_field_up.'" id="page-num-'.$fieldID.'" value="1">

                                    <span class="ml-5"> of </span>
                                    <span class="ml-5" id="page-of-'.$fieldID.'"> 1 </span>
                                    
                                    <i class="ph-bold ph-caret-right ml-5 mr-5 next-page" role="button" id="next-'.$fieldID.'" data-page="" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.getNavigation(\''.$fieldID.'-modal\', this, event, \''.$relate_field_up.'\', \''.$relate_field_down.'\')"></i>
                                    <i class="ph-bold ph-caret-double-right mr-5 end-page" role="button" id="end-'.$fieldID.'" data-page="" data-field="'.$fieldID.'" data-moduleselect="'.$params['module_select'].'" onclick="$.getNavigation(\''.$fieldID.'-modal\', this, event, \''.$relate_field_up.'\', \''.$relate_field_down.'\')"></i>
                                    
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                </div>';
    return $input;
}