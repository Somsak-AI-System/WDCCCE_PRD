<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function genBlock($blockfield, $params, $quick_create = false)
{
    $blocks = '';
    foreach ($blockfield as $block) {
        if ($quick_create) {
            $blockui = genUiBlockQuickCreate($block['FieldList'], $params);
        } else {
            $blockui = genUiBlock($block['FieldList'], $params);
        }

        if ($blockui == '') continue;
        $blocks .= '<div class="card">
                    <div class="card-header bg-info">
                        <!-- <h4 class="m-b-0 text-white">' . genLabel($block['BlockLabel']) . '</h4>-->
                        <div class="card-actions">
                            <a class="" data-action="collapse"><i class="mdi mdi-chevron-double-up text-white font-18"></i></a>
                            <!--
                            <a class="btn-minimize" data-action="expand"><i class="mdi mdi-arrow-expand"></i></a>
                            <a class="btn-close" data-action="close"><i class="ti-close"></i></a>
                            -->
                        </div>
                    </div>
                    <div class="card-body collapse show border border-info">';
        $blocks .= $blockui;
        $blocks .= '</div>
                </div>';
    }
    return $blocks;
}

function genUiBlockQuickCreate($datas, $params)
{
    $uiblock = '<div class="row">';
    $count = 0;
    if (!empty($datas)) {
        foreach ($datas as $data) {
            if ($data['QuickCreate'] == 1) {
                if ($count > 0 && $count % 2 == 0) {
                    $uiblock .= '</div><div class="row">';
                }

                $data['action'] = $params['action'];
                $data['module'] = @$params['module'];
                $data['showEditButton'] = isset($params['editable']) ? $params['editable']:'true';

                if ($data['UIType'] == '10') {
                    $uiblock .= getUiType($data);
                } else if ($data['Presence'] == 0) {
                    if (($data['action'] != 'Add') && $data['FieldName'] == 'groupid') {
                        $uiblock .= uitype_302($data);
                    } else {
                        $uiblock .= '';
                    }
                } else {
                    $uiblock .= genUiColumn($data);
                }

                $count++;
                if ($data['UIType'] == '10' || $data['Presence'] == 0) $count--;
            }
        }
    }
    $uiblock .= '</div>';
    if ($count == 0) {
        return '';
    } else {
        return $uiblock;
    }
}

function genUiBlock($datas, $params)
{
    $uiblock = '<div class="row">';
    $count = 0;
    if (!empty($datas)) {
        foreach ($datas as $data) {
            if (($params['action'] == 'Add' || $params['action'] == 'Dup') && $data['UIType'] == '59') continue;

            if(in_array(@$data['FieldID'], ['677', '678', '679', '680', '681'])) continue;
            $data = array_merge($data, $params);
            if ($count > 0 && $count % 2 == 0) {
                $uiblock .= '</div><div class="row">';
            }
            $data['action'] = $params['action'];
            $data['module'] = @$params['module'];

            if ($data['UIType'] == '10') {
                $uiblock .= getUiType($data);
            } else if ($data['Presence'] == 0) {
                if (($data['action'] != 'Add') && $data['FieldName'] == 'groupid') {
                    $uiblock .= uitype_302($data);
                } else {
                    $uiblock .= '';
                }
            } else {
                $uiblock .= genUiColumn($data);
            }

            $count++;
            if ($data['UIType'] == '10' || $data['Presence'] == 0) $count--;
        }
    }
    $uiblock .= '</div>';
    if ($count == 0) {
        return '';
    } else {
        return $uiblock;
    }
}

function genUiColumn($data)
{
    $label = genLabel($data['FieldLabel']) . ' : ';
    $star = $data['action'] != 'View' && (isset($data['Mandatory']) && $data['Mandatory'] == '1') ? '<span class="text-danger">*</span>' : '';
    $data['Value'] = $data['action'] == 'Add' ? $data['DefaultValue'] : $data['Value'];
    $uitype = $data['action'] != 'View' ? getUiType($data) : genUiView($data); //.' '.$data['FieldName'].DELIMITER.$data['FieldID']
    $label = $data['UIType'] == '57' ? '' : $label;
    $str = '<div class="col-md-6">
                <div class="form-group row">
                    <label class="control-label col-md-4"> <b>' . $label . '</b> ' . $star . ' <!-- <span class="text-white">' . $data['UIType'] . '</span>--></label>
                    <div class="col-md-8">
                        ' . $uitype . '
                    </div>
                </div>
            </div>';
    return $str;
}

if (!function_exists('load_controller'))
{
    function load_controller($controller, $method = 'index', $data = [])
    {
        require_once(FCPATH . APPPATH . 'modules/'. $controller .'/controllers/' . $controller . '.php');
        $controller = new $controller();
        return $controller->$method($data);
    }
}

function genUiView($data)
{
    $uitype = '';
    if (!empty($data)) {
        if (isset($data['UIType']) && $data['UIType'] != '') {
            switch ($data['UIType']) {
                case'0': //Widget block data
                    $uitype = nl2br($data['Value']);
                    break;
                case'3':
                     $uitype = nl2br('************');
                    break;
                case'5':
                    $uitype = $data['Value'] != '' ? number_format($data['Value']) : $data['Value'];
                    $uitype = '<span id="' . $data['FieldName'] . '">' . $uitype . '</span>';
                    $uitype .= uitype_301($data);
                    break;
                case'6':
                case'8':
                    $uitype = $data['Value'] != '' ? number_format($data['Value'],2) : $data['Value'];
                $uitype = '<span id="' . $data['FieldName'] . '">' . $uitype . '</span>';
                    $uitype .= uitype_301($data);
                    break;
                case'9':
                    $uitype = $data['Value'] != '' ? number_format($data['Value']) : $data['Value'];
                    $uitype = '<span id="' . $data['FieldName'] . '">' . $uitype . '</span>';
                    $uitype .= uitype_301($data);
                    break;
                case'26':
                    $uitype = nl2br($data['Value']);
                    $uitype = '<span id="' . $data['FieldName'] . '">' . $uitype . '</span>';
                    $uitype .= uitype_301($data);
                    break;
                case'27':
                    $data['Value'] = htmlspecialchars_decode($data['Value']);
                    $uitype = $data['Value'];
                    $uitype = '<span id="' . $data['FieldName'] . '">' . $uitype . '</span>';
//                    $uitype .= uitype_301($data);
                    break;
                case'28':
                    $value = getPicklistValue($data);
                    $uitype = '<span id="' . $data['FieldName'] . '_view">' . $value . '</span>';
                    $uitype .= uitype_301($data);
                    break;
//                case'31':
//                    $uitype = '<b><i class="fa fa-ellipsis-h"></i></b>';
//                    $uitype .= uitype_301($data);
//                    break;
                case'33':
                case'34':
                case'35':
                case'36':
                    if (!empty($data['AttachmentList'])) {
                        foreach ($data['AttachmentList'] as $file) {
                            
                            $fieltype = get_mime_type('../'.$file['Path'] . $file['Name']);
                            $uitype .= '<div><a href="' . site_url('../'.$file['Path'] . $file['Name']) . '" target="_blank">';
                            
                            if($fieltype == 'image/png' || $fieltype == 'image/jpeg' || $fieltype == 'image/gif'){
                                $uitype .= '<img  src="' . site_url('../'.$file['Path'] . $file['Name']) . '" height="200px" width="300px">';
                            }else{
                                $uitype .= $file['Name'];
                            }
                            /*}else if($fieltype == 'application/pdf'){
                                $uitype .= '<img  src="' . site_url('assets/images/Icon/pdf.png') . '" height="100px" width="100px">';
                            }else if($fieltype == 'application/msword'){
                                $uitype .= '<img  src="' . site_url('assets/images/Icon/word.png') . '" height="100px" width="100px">';
                            }else if($fieltype == 'application/vnd.ms-excel'){
                                $uitype .= '<img  src="' . site_url('assets/images/Icon/excel.png') . '" height="100px" width="100px">';
                            }else if($fieltype == 'application/vnd.ms-powerpoint'){
                                $uitype .= '<img  src="' . site_url('assets/images/Icon/powerpoint.png') . '" height="100px" width="100px">';
                            }else{
                                $uitype .= '<img  src="' . site_url('assets/images/Icon/other.png') . '" height="100px" width="100px">';
                            }*/
                            $uitype .= '</a></div><br>';
                            
                        }
                    }
                    break;
                case'37':
                case'39':
                    $value = explode(ASSIGN_DELEMITER, $data['Value']);

                    $uitype = nl2br(@$value[1]);
                    $uitype = '<span id="' . $data['FieldName'] . '">' . $uitype . '</span>';

                    $data['Value'] = @$value[0];
                    $uitype .= uitype_301($data);
                    break;
                case'45':
                    $uitype = nl2br(@$data['Value']);
                    $uitype = '<span id="' . $data['FieldName'] . '">' . $uitype . '</span>';

                    $data['Value'] = @$data['Value'];
                    $uitype .= uitype_301($data);
                    break;
                case'46':
//                    alert($data);

                    $valueLabel = [];
                    $values = explode(',', $data['Value']);

                    foreach($values as $v){
                        $value = explode(ASSIGN_DELEMITER, $v);
                        $valueLabel[] = @$value[1];
                    }

                    $valueLabel = implode(', ', $valueLabel);

                    if(isset($data['ReadOnly'])){
                        $uitype = '<input type="text" class="form-control" readonly value="'.nl2br(@$valueLabel).'">';
                    }else{
                        $uitype = nl2br(@$valueLabel);
                        $uitype = '<span id="' . $data['FieldName'] . '">' . $uitype . '</span>';

                        $data['Value'] = @$data['Value'];
                        $uitype .= uitype_301($data);
                    }

                    break;
                case'50':
                    $provinces = Modules::run('Components/FieldData/getProvince');
                    $value = '';
                    foreach ($provinces as $province) {
                        if ($province['ProvinceID'] == $data['Value']) {
                            $value = $province['ProvinceName'];
                        }
                    }
                    $uitype = nl2br($value);
                    $uitype = $uitype;
                    $uitype .= uitype_301(['FieldName' => $data['FieldName'], 'Value' => $data['Value']]);
                    break;
                case'51':
                    $uitype = '<span id="view_' . $data['FieldName'] . '"></span>';
                    $uitype .= uitype_301(['FieldName' => $data['FieldName'], 'Value' => $data['Value']]);
                    break;
                case'52':
                    $uitype = '<span id="view_' . $data['FieldName'] . '"></span>';
                    $uitype .= uitype_301(['FieldName' => $data['FieldName'], 'Value' => $data['Value']]);
                    break;
                case'54':
                    $uitype = nl2br(convertDateTimeToDisplay($data['Value']));
                    $uitype = '<span id="' . $data['FieldName'] . '">' . $uitype . '</span>';
                    $uitype .= uitype_301($data);
                    break;
                case'58':
                default:
                    $uitype = nl2br($data['Value']);
                    $uitype = '<span id="' . $data['FieldName'] . '">' . $uitype . '</span>';
                    $uitype .= uitype_301($data);
                    break;
            }
        }
    }
    return $uitype;
}

function getUiType($data)
{
    $uitype = '';
    if (!empty($data)) {
        if (isset($data['UIType']) && $data['UIType'] != '') {
            switch ($data['UIType']) {
                case'1': // text
                    $uitype = uitype_101($data);
                    break;
                case'60': // text full text search
                    $uitype = uitype_105($data);
                    break;
                case'2': // text autogen
                    $uitype = uitype_102($data);
                    break;
                case'3': // password
                    $uitype = uitype_103($data);
                    break;
                case'4': // text number no format
                    $uitype = uitype_201($data);
                    break;
                case'5': // text number with format
                    $uitype = uitype_202($data);
                    break;
                case'6': // text number with format 2 decimal
                    $uitype = uitype_203($data);
                    break;
                case'7': // text number fix digit
                    $data['len'] = $data['MaximumLength'];
                    $uitype = uitype_204($data);
                    break;
                case'8': // currency
                    $uitype = uitype_205($data);
                    break;
                case'9': // percent
                    $uitype = uitype_206($data);
                    break;
                case'10': // hidden
                    $uitype = uitype_301($data);
                    break;
                case'11': // datepicker d/m/Y
                    $uitype = uitype_401($data);
                    break;
                case'12': // datepicker fix mindate
                    $uitype = uitype_402($data);
                    break;
                case'13': // datepicker max date today
                    $uitype = uitype_403($data);
                    break;
                case'14': // datepicker fix maxdate
                    $uitype = uitype_404($data);
                    break;
                case'15': // daterangepicker d/m/Y
//                    $uitype = uitype_405($data);
                    break;
                case'16': // daterangepicker fix mindate
//                    $uitype = uitype_406($data);
                    break;
                case'17': // daterangepicker max date today
//                    $uitype = uitype_407($data);
                    break;
                case'18': // daterangepicker fix maxdate
//                    $uitype = uitype_408($data);
                    break;
                case'19': // timepicker H:i
                    $uitype = uitype_409($data);
                    break;
                case'20': // timepicker H:i:s
                    $uitype = uitype_410($data);
                    break;
                case'21': // datetimepicker d/m/Y H:i:s
                    $uitype = uitype_411($data);
                    break;
                case'22': // datetimepicker d/m/Y H:i
                    $uitype = uitype_412($data);
                    break;
                case'23': // datepicker month only
                    $uitype = uitype_413($data);
                    break;
                case'24': // email
                    $uitype = uitype_501($data);
                    break;
                case'25': // url
                    $uitype = uitype_502($data);
                    break;
                case'26': // textarea
                    $uitype = uitype_601($data);
                    break;
                case'27': // text editor
                    $uitype = uitype_602($data);
                    break;
                case'28': // picklist
                    $uitype = uitype_701($data);
                    break;
                case'29': // multiselect picklist
                    $uitype = uitype_702($data);
                    break;
                case'45': // picpklist
                    $uitype = uitype_704($data);
                    break;
                case'46': // multiselect picklist
                    $uitype = uitype_705($data);
                    break;
                case'30': // autocomplete picklist
                    $uitype = uitype_703($data);
                    break;
                case'31': // checkbox
                    $uitype = uitype_801($data);
                    break;
                case'32': // radio
                    $uitype = uitype_802($data);
                    break;
                case'33': // file upload
                    $uitype = uitype_901($data);
                    break;
                case'34': // file upload image only
                    $uitype = uitype_902($data);
                    break;
                case'35': // file upload image, doc, xls, pdf
                    $uitype = uitype_903($data);
                    break;
                case'36': // file upload doc, xls, pdf
                    $uitype = uitype_904($data);
                    break;
                case'37': // popup + search
                    $uitype = uitype_905($data);
                    break;
                case'38': // popup + search + add
                    //$uitype = uitype_906($data);
                    break;
                case'39': // popup assign
                    $uitype = uitype_907($data);
                    break;
                case'50': // province
                    $uitype = uitype_1003($data);
                    break;
                case'51': // district
                    $uitype = uitype_1004($data);
                    break;
                case'52': // subdistrict
                    $uitype = uitype_1005($data);
                    break;
                case'53': // postalcode
                    $uitype = uitype_1006($data);
                    break;
                case'54': // datereadonly
                    $uitype = uitype_414($data);
                    break;
                case'55': // Calculated text
                    $uitype = uitype_104($data);
                    break;
                case'56': // Calculated number
                    $uitype = uitype_207($data);
                    break;
                case'57': // Blank Field
                    $uitype = uitype_000($data);
                    break;
                case'58': // popup + search + add + multi
                    $uitype = uitype_909($data);
                    break;
                default:
                    $uitype = nouitype($data);
                    break;
            }
        }
    }
    return $uitype;
}
function get_mime_type($filename) {
    $idx = explode( '.', $filename );
    $count_explode = count($idx);
    $idx = strtolower($idx[$count_explode-1]);

    $mimet = array( 
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',
        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',
        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',
        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',
        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docx' => 'application/msword',
        'xlsx' => 'application/vnd.ms-excel',
        'pptx' => 'application/vnd.ms-powerpoint',
        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );

    if (isset( $mimet[$idx] )) {
     return $mimet[$idx];
    } else {
     return 'application/octet-stream';
    }
 }
function nouitype($data)
{
    return json_encode($data);
}

function uitype_000($data)
{
    $str = '';
    return $str;
}

// Input text
function uitype_101($data)
{
    /*if($data['FieldID'] == '307' || $data['FieldID'] == '310'){
        alert($data); 
    }*/
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $value = @$data['Value'];
    $class = @$data['custom_class'];
    $str = '<input type="text" class="form-control ' . $class . '" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Input text default value "Auto Gen On Save" readonly
function uitype_102($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $value = isset($data['Value']) && $data['Value'] != '' && $data['action'] != 'Dup' ? $data['Value'] : 'Auto Gen On Save';
    $str = '<input type="text" class="form-control" name="' . $input_name . '" id="' . $input_key . '" readonly ' . $required . ' value="' . $value . '">';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Input password
function uitype_103($data)
{
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $value = @$data['Value'];
    $str = '<input type="password" class="form-control" name="' . $input_name . '" id="' . $input_key . '" ' . $readonly . ' ' . $required . ' value="' . $value . '">';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

function uitype_104($data)
{
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $value = @$data['Value'];
    $class = @$data['custom_class'];
    $str = '<input type="text" class="form-control ' . $class . '" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

function uitype_105($data)
{
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $value = @$data['Value'];
    $class = @$data['custom_class'];
    $str = '<input type="text" class="form-control ' . $class . '" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Input number only
function uitype_201($data)
{
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $value = @$data['Value'];
    $class = @$data['custom_class'];
    $inputmask = "'alias':'numeric', 'allowMinus':false, 'digits':0, 'greedy':false";
    $inputmask ="";
    if(isset($data['Format'])) {
        $inputmask .= "'mask' : '".$data['Format']."'";
    }
    $str = '<input type="text" class="form-control ' . $class . '" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-inputmask="' . $inputmask . '" ' . $readonly . ' ' . $required . '>';
    
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Input number with format
function uitype_202($data)
{
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $value = @$data['Value'];
    $class = @$data['custom_class'];
    $inputmask = "'alias':'numeric', 'allowMinus':false, 'digits':0, 'groupSeparator':',', 'autoGroup':true";
    $str = '<input type="text" class="form-control ' . $class . '" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-inputmask="' . $inputmask . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Input number with format and 2 decimal
function uitype_203($data)
{
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $value = @$data['Value'];
    $class = @$data['custom_class'];
    $inputmask = "'alias': 'decimal', 'allowMinus':false, 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false";
    $str = '<input type="text" class="form-control ' . $class . '" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-inputmask="' . $inputmask . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Input number fix digit
function uitype_204($data)
{
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $fix_digit = !isset($data['len']) ? 10 : $data['len'];
    $value = @$data['Value'];
    $class = @$data['custom_class'];
    $inputmask = "'alias':'alphanumeric', 'allowMinus':false, 'digits':0, 'mask':'9', 'repeat':" . $fix_digit; //'numericInput':true,
    $str = '<input type="text" class="form-control ' . $class . '" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-inputmask="' . $inputmask . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Input number currency
function uitype_205($data)
{
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $value = @$data['Value'];
    $class = @$data['custom_class'];
    $inputmask = "'alias':'numeric', 'allowMinus':false, 'digits':0, 'groupSeparator':',', 'autoGroup':true";
    $str = '<input type="text" class="form-control ' . $class . '" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-inputmask="' . $inputmask . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Input number percent
function uitype_206($data)
{
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $value = @$data['Value'];
    $class = @$data['custom_class'];
    $inputmask = "'alias':'numeric', 'allowMinus':false, 'digits':0";
    $str = '<input type="text" class="form-control ' . $class . '" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-inputmask="' . $inputmask . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

function uitype_207($data)
{
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $value = @$data['Value'];
    $class = @$data['custom_class'];
    $inputmask = "'alias':'numeric', 'allowMinus':false, 'digits':0";
    $str = '<input type="text" class="form-control ' . $class . '" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-inputmask="' . $inputmask . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Input hidden
function uitype_301($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $value = @$data['Value'];

    $target = '';
    if (isset($data['PicklistTargetFieldList']) && !empty($data['PicklistTargetFieldList'])) {
        $target = $data['PicklistTargetFieldList'][0]['FieldName'] . DELIMITER . $data['PicklistTargetFieldList'][0]['FieldID'];
    }

    if (isset($data['UIType'])) {
        switch ($data['UIType']) {
            case'31':
                $value = $value == '' ? 0 : $value;
                break;
        }
    }

    $str = '<input type="hidden" class="form-control" name="' . $input_name . '" id="' . $input_key . '" data-target="' . $target . '" value="' . $value . '">';
    return $str;
}

function uitype_302($data)
{
    if ($data['Value'] == '') {
        $value = '';
    } else {
        $value = explode(ASSIGN_DELEMITER, $data['Value']);
        $value = $data['action'] == 'View' ? $value[1] : $value[0];
    }

    $str = '<input type="hidden" class="form-control" name="' . $data['FieldName'] . '" id="' . $data['FieldName'] . '" value="' . @$value . '">';
    return $str;
}

// Date picker
function uitype_401($data)
{
//    alert($data);
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $value = isset($data['DefaultValue']) && $data['DefaultValue'] == 'NOW' ? date('d/m/Y') : @$data['Value'];
    $class = @$data['custom_class'];
    $str = '<input type="text" class="k-form-control kendo-datepicker ' . $class . '" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Date picker fix mindate
function uitype_402($data)
{

    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    //$mindate = '2020-05-03';
    $time = '-' . $data['MinimumLength'] . ' days';
    $date = date('Y-m-d');
    $mindate = date("Y-m-d", strtotime(".$time.", strtotime($date)));
    $value = @$data['Value'];
    $str = '<input type="text" class="k-form-control kendo-datepicker" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-mindate="' . $mindate . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Date picker max date today
function uitype_403($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $value = @$data['Value'];
    $str = '<input type="text" class="k-form-control kendo-datepicker" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-maxdate="' . date('Y-m-d') . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Date picker fix maxdate
function uitype_404($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $value = isset($data['DefaultValue']) && $data['DefaultValue'] == 'NOW' ? date('d/m/Y') : @$data['Value'];
    $maxdate = '';
    $str = '<input type="text" class="k-form-control kendo-datepicker" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-maxdate="' . $maxdate . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Time picker H:i:s
function uitype_409($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $value = isset($data['DefaultValue']) && $data['DefaultValue'] == 'NOW' ? date('H:i:s') : @$data['Value'];
    $str = '<input type="text" class="k-form-control kendo-timepicker" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-timeformat="HH:mm:ss" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Time picker H:i
function uitype_410($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $value = isset($data['DefaultValue']) && $data['DefaultValue'] == 'NOW' ? date('H:i') : @$data['Value'];
    $str = '<input type="text" class="k-form-control kendo-timepicker" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-timeformat="HH:mm" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// DateTime picker H:i:s
function uitype_411($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $value = isset($data['DefaultValue']) && $data['DefaultValue'] == 'NOW' ? date('d/m/Y H:i:s') : @$data['Value'];
    $str = '<input type="text" class="k-form-control kendo-datetimepicker" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-format="dd/MM/yyyy HH:mm:ss" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// DateTime picker H:i
function uitype_412($data)
{ //alert($data);
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $value = isset($data['DefaultValue']) && $data['DefaultValue'] == 'NOW' ? date('d/m/Y H:i') : @$data['Value'];
    $str = '<input type="text" class="k-form-control kendo-datetimepicker" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-format="dd/MM/yyyy HH:mm" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Date picker Montn only
function uitype_413($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $value = @$data['Value'];
    $class = @$data['custom_class'];
    $str = '<input type="text" class="k-form-control kendo-datepicker-monthyear ' . $class . '" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '"  ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Input Datereadonly
function uitype_414($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $value = @convertDateTimeToDisplay($data['Value']);
    $str = '<input type="text" class="form-control" name="' . $input_name . '" id="' . $input_key . '" readonly ' . $required . ' value="' . $value . '">';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Input email
function uitype_501($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $inputmask = "'alias':'email'";
    $value = @$data['Value'];
    $str = '<input type="text" class="form-control" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-inputmask="' . $inputmask . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Input URL
function uitype_502($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $inputmask = "'mask':'http://www.*{1,20}[.*{1,20}]', 'greedy':false";
    $value = @$data['Value'];
    $str = '<input type="text" class="form-control" name="' . $input_name . '" id="' . $input_key . '" value="' . $value . '" data-inputmask="' . $inputmask . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Textarea
function uitype_601($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $value = @$data['Value'];

    $rows = isset($data['rows']) ? $data['rows']:'5';

    $str = '<textarea class="form-control" name="' . $input_name . '" id="' . $input_key . '" ' . $readonly . ' ' . $required . ' rows="'.$rows.'">' . $value . '</textarea>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Text editor
function uitype_602($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $value = @$data['Value'];
    $str = '<textarea class="form-control kendo-texteditor" name="' . $input_name . '" id="' . $input_key . '" ' . $readonly . ' ' . $required . ' rows="5">' . $value . '</textarea>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Combobox
function uitype_701($data)
{ // id value
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $options = genSelectOptions($data);

    $target = '';
    if (isset($data['PicklistTargetFieldList']) && !empty($data['PicklistTargetFieldList'])) {
        $target = $data['PicklistTargetFieldList'][0]['FieldName'] . DELIMITER . $data['PicklistTargetFieldList'][0]['FieldID'];
    }

    $str = '<select class="k-form-control kendo-combobox kendo-combobox-validate" name="' . $input_name . '" id="' . $input_key . '" data-target="' . $target . '" data-value="' . @$data['Value'] . '" ' . $readonly . ' ' . $required . '>' . $options . '</select>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Multiselect
function uitype_702($data)
{ // id value
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $options = genSelectOptions($data);
    $str = '<select class="k-form-control kendo-multiselect" name="' . $input_name . '" id="' . $input_key . '" ' . $readonly . ' ' . $required . '>' . $options . '</select>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Autocomplete
function uitype_703($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $str = '<input type="text" class="k-form-control kendo-autocomplete" name="' . $input_name . '" id="' . $input_key . '" ' . $readonly . ' ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

function uitype_704($data)
{ // text value
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $options = genSelectOptionsText($data);

    $target = '';
    if (isset($data['PicklistTargetFieldList']) && !empty($data['PicklistTargetFieldList'])) {
        $target = $data['PicklistTargetFieldList'][0]['FieldName'] . DELIMITER . $data['PicklistTargetFieldList'][0]['FieldID'];
    }

    $str = '<select class="k-form-control kendo-combobox kendo-combobox-validate" name="' . $input_name . '" id="' . $input_key . '" data-target="' . $target . '" data-value="' . @$data['Value'] . '" ' . $readonly . ' ' . $required . '>' . $options . '</select>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Multiselect
function uitype_705($data)
{
    @$value_multiple = explode(',', $data['Value']);

    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $options = genSelectOptionsText($data);

    $str = '<select data-value="'. @$data['Value'] .'" name="' . $input_name . '[]" id="' . $input_key . '" ' . $readonly . ' ' . $required . ' multiple="multiple"></select>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    
    return $str;
}

// Checkbox
function uitype_801($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $checked = @$data['Value'] == '1' ? 'checked' : '';
    $str = '<input type="checkbox" class="chkboxs" ' . $checked . ' ' . $required . '>';
    $str .= uitype_301($data);
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Radio
function uitype_802($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $str = '<input type="radio" name="' . $input_name . '" id="' . $input_key . '" ' . $required . '>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Upload *
function uitype_901($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $path = 'Attachment,' . $data['TabID'] . ',' . date('YmdHis') . substr(gettimeofday()["usec"], -3) . randomNumber(2);
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $files = [];
    if (!empty($data['AttachmentList'])) {
        foreach ($data['AttachmentList'] as $file) {
            $ext = end(explode('.', $file['Name']));
            $file['name'] = $file['Name'];
            $file['extension'] = $ext;
            $file['size'] = '';
            $files[] = $file;
        }
    }
    $json_files = json_encode($files);
    $str = '<input name="files" data-readonly="' . $readonly . '" class="kendo-upload" type="file" data-path="' . $path . '" data-files=\'' . $json_files . '\' data-name="' . $input_name . '" id="' . $input_key . '" ' . $required . ' data-multiple=true data-ext="*" />';
    $str .= '<input type="hidden" name="' . $input_name . '[]">';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '[]" class="k-invalid-msg"></span></div>';
    return $str;
}

// Upload Image Only
function uitype_902($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $path = 'Attachment,' . $data['TabID'] . ',' . date('YmdHis') . substr(gettimeofday()["usec"], -3) . randomNumber(2);
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $multiple = isset($data['Multiple']) ? $data['Multiple'] : true;
    $files = [];
    if (!empty($data['AttachmentList'])) {
        foreach ($data['AttachmentList'] as $file) {
            $ext = end(explode('.', $file['Name']));
            $file['name'] = $file['Name'];
            $file['extension'] = $ext;
            $file['size'] = '';
            $files[] = $file;
        }
    }
    $json_files = json_encode($files);
    $str = '<input name="files" data-readonly="' . $readonly . '" class="kendo-upload" type="file" data-path="' . $path . '" data-files=\'' . $json_files . '\' data-name="' . $input_name . '" id="' . $input_key . '" ' . $required . ' data-multiple=true data-ext=".jpg,.png,.jpeg,.gif" />';
    $str .= '<input type="hidden" name="' . $input_name . '[]">';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '[]" class="k-invalid-msg"></span></div>';
    return $str;
}

// Upload Documents and Images
function uitype_903($data)
{ //alert($data);
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $path = 'Attachment,' . $data['TabID'] . ',' . date('YmdHis') . substr(gettimeofday()["usec"], -3) . randomNumber(2);
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $multiple = isset($data['Multiple']) ? $data['Multiple'] : true;
    $files = [];
    if (!empty($data['AttachmentList'])) {
        foreach ($data['AttachmentList'] as $file) {
            $ext = end(explode('.', $file['Name']));
            $file['name'] = $file['Name'];
            $file['extension'] = $ext;
            $file['size'] = '';
            $files[] = $file;
        }
    }
    $json_files = json_encode($files);
    $str = '<input name="files" data-readonly="' . $readonly . '" class="kendo-upload" type="file" data-path="' . $path . '" data-files=\'' . $json_files . '\' data-name="' . $input_name . '" id="' . $input_key . '" ' . $required . ' data-multiple=true data-ext=".jpg,.png,.jpeg,.gif,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.pdf,.txt,.mp3,.mp4,.avi,.mov,.mpg,.wmv" />';
    $str .= '<input type="hidden" name="' . $input_name . '[]">';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '[]" class="k-invalid-msg"></span></div>';
    return $str;
}

// Upload Documents Only
function uitype_904($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $path = 'Attachment,' . $data['TabID'] . ',' . date('YmdHis') . substr(gettimeofday()["usec"], -3) . randomNumber(2);
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    if (!empty($data['AttachmentList'])) {
        foreach ($data['AttachmentList'] as $file) {
            $ext = end(explode('.', $file['Name']));
            $file['name'] = $file['Name'];
            $file['extension'] = $ext;
            $file['size'] = '';
            $files[] = $file;
        }
    }
    $json_files = json_encode($files);
    $str = '<input name="files" data-readonly="' . $readonly . '" class="kendo-upload" type="file" data-path="' . $path . '" data-files=\'' . $json_files . '\' data-name="' . $input_name . '" id="' . $input_key . '" ' . $required . ' data-multiple=true data-ext=".doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.txt" />';
    $str .= '<input type="hidden" name="' . $input_name . '[]">';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '[]" class="k-invalid-msg"></span></div>';
    return $str;
}

// Upload Documents and Images Email Attach
function uitype_921($data)
{
    $path = $data['folder'] . ',' . $data['tabid'] . ',' . date('YmdHis') . substr(gettimeofday()["usec"], -3) . randomNumber(2);
    $name = $data['name'];
    $files = [];
    $json_files = json_encode($files);
    $str = '<input name="files" class="kendo-upload" type="file" data-path="' . $path . '" data-files="" data-name="' . $name . '" id="' . $name . '" data-multiple=true data-ext=".jpg,.png,.jpeg,.gif,.doc,.docx,.xls,.xlsx,.pdf" />';
    $str .= '<input type="hidden" name="' . $name . '[]">';
    return $str;
}

// Popup Search
function uitype_905($data)
{ //alert($data); //contact_id
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $data_field = $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['module'] . DELIMITER . $data['TabID'] . DELIMITER . $data['Value'];
    $data_relate = $data['Field2Field']['RelateColumnName'] . DELIMITER . $data['Field2Field']['RelateFieldID'] . DELIMITER . $data['Field2Field']['RelateTabID'];
    $value = explode(ASSIGN_DELEMITER, $data['Value']);
    $editable = isset($data['Editable']) ? $data['Editable']:'true';

    $quickEdit = isset($data['QuickEdit']) ? $data['QuickEdit']:'0';

    $btn_action = '';
    if ($readonly == '') {
        if (in_array($data['FieldName'], ['contact_id','coordinate_id'])) {
            $btn_action .= '<button type="button" class="btn waves-effect waves-light btn-secondary btn-popup-create" data-fielddata="' . $data_field . '" data-fieldrelate="' . $data_relate . '" data-action="Add" data-editable="'. $editable .'" data-toggle="modal" data-target="#modal-quick-create" title="' . genLabel('LBL_BTN_CREATE') . '"><i class="fa fa-plus"></i></button>';
            if($quickEdit == '1') $btn_action .= '<button type="button" class="btn waves-effect waves-light btn-secondary btn-popup-create" data-fielddata="' . $data_field . '" data-fieldrelate="' . $data_relate . '" data-action="Edit" data-editable="'. $editable .'" data-toggle="modal" data-target="#modal-quick-create" title="' . genLabel('LBL_BTN_EDIT') . '"><i class="fa fa-edit"></i></button>';

        }

        if(isset($data['customFilter']) && $data['customFilter'] && $data['module'] != 'Report'){
            $btn_action .= '<button type="button" class="btn waves-effect waves-light btn-secondary btn-popup-filteraccount" title="Account Filter" data-fielddata="' . $data_field . '" data-fieldrelate="' . $data_relate . '" data-toggle="modal" data-target="#modal-custom-popup"><i class="fa fa-search-plus"></i></button>';
        }

        if(isset($data['showEditButton'])){
            if($data['showEditButton'] == 'true'){
                $btn_action .= '<button type="button" class="btn waves-effect waves-light btn-secondary btn-popup-search" data-fielddata="' . $data_field . '" data-fieldrelate="' . $data_relate . '" data-toggle="modal" data-target="#modal-main-popup" title="' . genLabel('LBL_SEARCH') . '"><i class="fa fa-search"></i></button>';
                $btn_action .= '<button type="button" class="btn waves-effect waves-light btn-secondary btn-popup-remove" data-fielddata="' . $data_field . '" title="'  . genLabel('LBL_BTN_REMOVE') . '"><i class="fa fa-times"></i></button>';
            }
        }else{
            if(!isset($data['customFilter'])) $btn_action .= '<button type="button" class="btn waves-effect waves-light btn-secondary btn-popup-search" data-fielddata="' . $data_field . '" data-fieldrelate="' . $data_relate . '" data-toggle="modal" data-target="#modal-main-popup" title="' . genLabel('LBL_SEARCH') . '"><i class="fa fa-search"></i></button>';
            $btn_action .= '<button type="button" class="btn waves-effect waves-light btn-secondary btn-popup-remove" data-fielddata="' . $data_field . '" title="' . genLabel('LBL_BTN_REMOVE') . '"><i class="fa fa-times"></i></button>';
        }
        
    }

    $str = '<div class="input-group">
                <input type="text" class="form-control readonly" name="' . $input_key . '_input" id="' . $input_key . '_input" value="' . @$value[1] . '" ' . $readonly . ' ' . $required . '>
                <input type="hidden" class="" name="' . $input_name . '" id="' . $input_key . '" value="' . @$value[0] . '">
                <div class="input-group-prepend">
                    ' . $btn_action . '
                </div>
            </div>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_key . '_input" class="k-invalid-msg"></span></div>';
    return $str;
}

// Assign to
function uitype_907($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $options = '';

    $target = '';
    if (isset($data['PicklistTargetFieldList']) && !empty($data['PicklistTargetFieldList'])) {
        $target = $data['PicklistTargetFieldList'][0]['FieldName'] . DELIMITER . $data['PicklistTargetFieldList'][0]['FieldID'];
    }
    $value = explode(ASSIGN_DELEMITER, $data['Value']);
    $str = '<select class="k-form-control kendo-combobox-assign kendo-combobox-validate" name="' . $data['FieldName'] . '" id="' . $data['FieldName'] . '" data-type="assignto" data-target="' . $target . '" ' . $readonly . ' ' . $required . '>' . $options . '</select>';
    $str .= '<input type="hidden" class="userid" name="' . $input_name . '" value="' . @$value[0] . '">';
    $str .= '<input type="hidden" class="groupid" name="groupid' . DELIMITER . $data['Field2Field']['RelateFieldID'] . DELIMITER . $data['UIType'] . '">';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

function uitype_908($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $options = '';

    $str = '<textarea class="form-control comment-msg" data-tabid="' . $data['TabID'] . '" data-module="' . $data['module'] . '" data-querystring="' . $data['query_string'] . '" rows="3"></textarea>
            <span><b>Comment List</b></span>
            <button type="button" class="btn waves-effect waves-light btn-info btn-xs float-right btn-comment-post" style="margin-top:-5px;">' . genLabel('LBL_POST_COMMENT') . '</button>
            <div class="comment-list"></div>';
    return $str;
}

function uitype_909($data)
{ //alert($data); //contact_id
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $options = genSelectOptionsText($data);
    $str = '<select name="' . $input_name . '" id="' . $input_key . '" ' . $readonly . ' ' . $required . ' multiple="multiple"></select>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Combobox provice
function uitype_1003($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $options = getProvince($data);
    $runno = explode('_', $data['FieldName']);
    $str = '<select class="k-form-control kendo-combobox-relate kendo-combobox-validate" data-value="' . $data['Value'] . '" data-uitype="1003" data-source="" data-cascade="district_' . $runno[1] . '" name="' . $input_name . '" id="' . $data['FieldName'] . '" ' . $readonly . ' ' . $required . '>' . $options . '</select>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Combobox district
function uitype_1004($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $options = '';
    $runno = explode('_', $data['FieldName']);
    $str = '<select class="k-form-control kendo-combobox-relate kendo-combobox-validate" data-value="' . $data['Value'] . '" data-uitype="1004" data-source="province_' . $runno[1] . '" data-cascade="subdistrict_' . $runno[1] . '" name="' . $input_name . '" id="' . $data['FieldName'] . '" ' . $readonly . ' ' . $required . '>' . $options . '</select>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Combobox subdistrict
function uitype_1005($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $readonly = isset($data['ReadOnly']) && $data['ReadOnly'] == '1' ? 'readonly' : '';
    $options = '';
    $runno = explode('_', $data['FieldName']);
    $str = '<select class="k-form-control kendo-combobox-relate kendo-combobox-validate" data-value="' . $data['Value'] . '" data-uitype="1005" data-source="district_' . $runno[1] . '" data-cascade="postalcode_' . $runno[1] . '" name="' . $input_name . '" id="' . $data['FieldName'] . '" ' . $readonly . ' ' . $required . '>' . $options . '</select>';
    $str .= '<div class="validator-msg"><span data-for="' . $input_name . '" class="k-invalid-msg"></span></div>';
    return $str;
}

// Input postalcode
function uitype_1006($data)
{
    $input_key = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] : $data['FieldName'];
    $input_name = isset($data['FieldID']) ? $data['FieldName'] . DELIMITER . $data['FieldID'] . DELIMITER . $data['UIType'] : $data['FieldName'];
    $required = isset($data['Mandatory']) && $data['Mandatory'] == '1' ? 'required' : '';
    $value = @$data['Value'];
    $str = '<input type="text" class="form-control" name="' . $input_name . '" id="' . $data['FieldName'] . '" ' . $required . ' value="' . $value . '">';

    return $str;
}

function genSelectOptions($data)
{
    $options = '';
    if (!isset($data['DefaultSeledted'])) $options .= '<option value="">' . genLabel('LBL_PLEASE_SELECT') . '</option>';
    $value = @$data['Value'];
    if (isset($data['PicklistList']) && !empty($data['PicklistList'])) {
        foreach ($data['PicklistList'] as $row) {
            $selected = $row['PicklistValueID'] == $value ? 'selected' : '';
            $options .= '<option value="' . $row['PicklistValueID'] . '" ' . $selected . '>' . $row['Name'] . '</option>';
        }
    }
    return $options;
}

function getPicklistValue($data)
{
    $data_value = '';
    $value = @$data['Value'];
    if (isset($data['PicklistList']) && !empty($data['PicklistList'])) {
        foreach ($data['PicklistList'] as $row) {
            if ($row['PicklistValueID'] == $value) {
                $data_value = $row['Name'];
            }
        }
    }
    return $data_value;
}

function genSelectOptionsText($data)
{
    $options = '<option value="">' . genLabel('LBL_PLEASE_SELECT') . '</option>';
    $value = @$data['Value'];

    if (isset($data['PicklistList']) && !empty($data['PicklistList'])) {
        foreach ($data['PicklistList'] as $i => $row) {
            if (isset($data['TabID'])) {
                if ($data['TabID'] == '3' && $data['FieldName'] == 'status' && $data['action'] != 'Dup') {
                    if ($data['Value'] != '') {
                        if ($data['Value'] == 'ดำเนินการ' || $data['Value'] == 'อนุมัติ') {
                            if (!in_array($row['Name'], ['ดำเนินการ', 'อนุมัติ', 'ปิดงาน', 'ยกเลิก'])) continue;
                        } else if ($data['Value'] == 'ปิดงาน') {
                            if (!in_array($row['Name'], ['ปิดงาน'])) continue;
                        } else if ($data['Value'] == 'ยกเลิก') {
                            if (!in_array($row['Name'], ['ยกเลิก'])) continue;
                        }
                    }
                }
            }

            $selected = $row['Name'] == $value ? 'selected' : '';
            $options .= '<option value="' . $row['Name'] . '" ' . $selected . '>' . $row['Name'] . '</option>';
        }
    }
    //print_r($options); exit;
    return $options;
}

function genSelectMultOptionsText($data)
{
    $options = '<option value="">' . genLabel('LBL_PLEASE_SELECT') . '</option>';
    $value = @$data['Value'];

    if (isset($data['PicklistList']) && !empty($data['PicklistList'])) {
        foreach ($data['PicklistList'] as $i => $row) {
            if (isset($data['TabID'])) {
                if ($data['TabID'] == '3' && $data['FieldName'] == 'status' && $data['action'] != 'Dup') {
                    if ($data['Value'] != '') {
                        if ($data['Value'] == 'ดำเนินการ' || $data['Value'] == 'อนุมัติ') {
                            if (!in_array($row['Name'], ['ดำเนินการ', 'อนุมัติ', 'ปิดงาน', 'ยกเลิก'])) continue;
                        } else if ($data['Value'] == 'ปิดงาน') {
                            if (!in_array($row['Name'], ['ปิดงาน'])) continue;
                        } else if ($data['Value'] == 'ยกเลิก') {
                            if (!in_array($row['Name'], ['ยกเลิก'])) continue;
                        }
                    }
                }
            }

            $selected = $row['Name'] == $value ? 'selected' : '';
            $options .= '<option value="' . $row['Name'] . '" ' . $selected . '>' . $row['Name'] . '</option>';
        }
    }
    //print_r($data['PicklistList']); exit;
    return $options;
}

function getProvince($data)
{
    $provinces = Modules::run('Components/FieldData/getProvince');
    $options = '';
    $options .= '<option value="">' . genLabel('LBL_PLEASE_SELECT') . '</option>';
    foreach ($provinces as $row) {
        $selected = ''; //$row['value']==$data['Value'] ? 'selected':'';
        $options .= '<option value="' . $row['value'] . '" ' . $selected . '>' . $row['label'] . '</option>';
    }
    return $options;
}

function genLabel($label)
{
    $ci = &get_instance();
    $ci->lang->load('ai', $ci->session->userdata('lang'));
    $label = $ci->lang->line($label) == '' ? $label : $ci->lang->line($label);
    return $label;
}