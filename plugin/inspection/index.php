<?php
global $data_template;

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/bootstrap.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/font-awesome.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/smartadmin-production-plugins.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/smartadmin-production.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/nhealth-skins.css">
<link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/smartadmin-rtl.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/your_style.css">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
<script>
    if (!window.jQuery) {
        document.write('<script src="plugin/inspection/jquery-2.1.1.min.js"><\/script>');
    }
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script>
    if (!window.jQuery.ui) {
        document.write('<script src="plugin/inspection/jquery-ui-1.10.3.min.js"><\/script>');
    }
</script>

<body class="nhealth-skin">

<!-- MAIN PANEL -->
<div id="main" role="main">

    <div id="content">
        <section id="widget-grid" class="">

            <div class="row">
                <article class="col-sm-12 col-md-12 col-lg-12">

                    <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false"
                         data-widget-editbutton="false" data-widget-custombutton="false"
                         data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-edit"
                                                          style="margin-top: 7px; font-size: 20px;"></i> </span>
                            <h2>DATA RECORD FORM: <strong id="show-form-name"></strong><span id="last-update-form"
                                                                                             class="last-update-form"></span>
                            </h2>

                            <div class="widget-toolbar">

                                <div class="btn-group">
                                    <button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown"
                                            type="button">ตั้งค่า <i class="fa fa-caret-down"></i></button>
                                    <ul class="dropdown-menu pull-right js-status-update">
                                        <li>
                                            <a href="javascript:addTable();"><i
                                                        class="fa fa-plus-square txt-color-green"></i> เพิ่มตารางข้อมูล
                                                CAL</a>
                                        </li>
                                        <li>
                                            <a href="javascript:addTablePM();"><i
                                                        class="fa fa-plus-square txt-color-green"></i> เพิ่มตารางข้อมูล
                                                PM</a>
                                        </li>
                                        <!-- <li>
                                           <a href="javascript:addTableSTD();"><i class="fa fa-plus-square txt-color-green"></i> เพิ่มตารางข้อมูลเครื่อง Standard</a>
                                        </li> -->
                                        <li>
                                            <a href="javascript:addTableNote();"><i
                                                        class="fa fa-plus-square txt-color-green"></i>
                                                เพิ่มตารางหมายเหตุ</a>
                                        </li>
                                        <!-- <li class="save-form divider"></li>
                                        <li class="save-form">
                                           <a href="javascript:SaveForm();"><i class="fa fa-save txt-color-green"></i> Save</a>
                                        </li>
                                        <li class="save-form">
                                           <a href="javascript:SaveAsForm();" id="save-form-as-btn"><i class="fa fa-copy txt-color-green"></i> Save As...</a>
                                        </li> -->
                                    </ul>
                                </div>
                            </div>
                        </header>
                        <!-- widget div-->
                        <div>

                            <!-- widget content -->
                            <div class="widget-body no-padding" style="min-height:50px;">

                                <!-- <form id="design-form" name="design-form" class="smart-form" enctype="multipart/form-data" novalidate="novalidate"> -->

                                <div class="smart-form" id="design-form" name="design-form">
                                    <input type="hidden" name="save_type" value="form"/>
                                    <input type="hidden" id="save-form-name" name="form_name" value=""/>
                                    <input type="hidden" id="save-form-type" name="form_type" value=""/>
                                    <input type="hidden" id="save-form-id" name="form_id" value=""/>
                                    <input type="hidden" id="CountRowsID" value="0"/>
                                    <div id="parameters-sortable">

                                        <?php

                                        foreach ($data_template as $key => $value) {

                                            $table = 'table' . generateRandomString();

                                            if ($value['choice_type'] == 'calibrate') { ?>

                                                <fieldset id="remove_<?php echo $table; ?>" class="cal-form-box"
                                                          style="padding-bottom: 20px; position: relative; left: 0px; top: 0px;">
                                                    <input type="hidden" name="tables_parameter[]"
                                                           class="tables_parameter" value="<?php echo $table; ?>"/>
                                                    <input type="hidden" name="<?php echo $table; ?>_type"
                                                           id="<?php echo $table; ?>_type" value="calibrate">
                                                    <div class="row">

                                                        <section class="col col-10" style="margin-bottom:5px;">
                                                            <label class="input"> <i class="icon-append fa"
                                                                                     style="right:auto; left:5px; border-left:0px;">Parameter:</i>
                                                                <input name="<?php echo $table; ?>_parameter"
                                                                       id="<?php echo $table; ?>_parameter" type="text"
                                                                       style="padding-left:75px; border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic;"
                                                                       value="<?php echo $value['choice_title']; ?>"
                                                                       class="user-success">
                                                            </label>
                                                        </section>

                                                        <section class="col col-2" style="margin-bottom:5px;">
                                                            <div class="btn-group" style="float:right;">
                                                                <button class="btn dropdown-toggle btn-sm btn-default"
                                                                        data-toggle="dropdown" autocomplete="off"
                                                                        aria-expanded="false"><i class="fa fa-cog"></i>
                                                                </button>
                                                                <a href="javascript:ShowHideTable('caltb-<?php echo $table; ?>');"
                                                                   id="caltb-<?php echo $table; ?>-collapse"
                                                                   class="btn btn-sm btn-default"><i
                                                                            class="fa fa-minus"></i></a>
                                                                <span class="btn btn-sm btn-default"><i
                                                                            class="fa fa-sort" style="cursor:move"></i></span>
                                                                <ul class="dropdown-menu pull-right js-status-update">
                                                                    <li>
                                                                        <a href="javascript:addRow('<?php echo $table; ?>');"><i
                                                                                    class="fa fa-plus-square"></i>
                                                                            เพิ่มแถว</a></li>
                                                                    <li>
                                                                        <a href="javascript:removeTable('remove_<?php echo $table; ?>');"><i
                                                                                    class="fa fa-trash-o"></i>
                                                                            ลบตาราง</a></li>
                                                                </ul>

                                                            </div>
                                                        </section>

                                                    </div>


                                                    <div id="caltb-<?php echo $table; ?>" style="display:block">

                                                        <table id="<?php echo $table; ?>"
                                                               class="table table-bordered table-form table-cal">
                                                            <tbody>
                                                            <tr>

                                                                <th colspan="4"
                                                                    style="padding:5px 2px 5px 2px; border-bottom:0px;">
                                                                    <div class="row" style="text-align:center">Unit
                                                                    </div>
                                                                </th>

                                                                <th style="padding:5px 2px 5px 2px; border-bottom:0px;"
                                                                    colspan="10">
                                                                    <div class="row" style="text-align:center">Tolerance
                                                                        <span id="<?php echo $table; ?>-tolerance-error"
                                                                              style="font-weight:normal;">
                        <select name="<?php echo $table; ?>_tolerance_type" id="<?php echo $table; ?>_tolerance_type"
                                tb-id="<?php echo $table; ?>" style="font-weight:normal;">
                           <option value="1" <?php echo ($value['tolerance_type'] == '1') ? 'selected' : ''; ?> > ระหว่าง Min และ Max </option>
                           <option value="2" <?php echo ($value['tolerance_type'] == '2') ? 'selected' : ''; ?>> &gt; มากกว่า </option>
                           <option value="3" <?php echo ($value['tolerance_type'] == '3') ? 'selected' : ''; ?>> ≥ มากกว่าหรือเท่ากับ </option>
                           <option value="4" <?php echo ($value['tolerance_type'] == '4') ? 'selected' : ''; ?>> &lt; น้อยกว่า </option>
                           <option value="5" <?php echo ($value['tolerance_type'] == '5') ? 'selected' : ''; ?>> ≤ น้อยกว่าหรือเท่ากับ </option>
                           <option value="6" <?php echo ($value['tolerance_type'] == '6') ? 'selected' : ''; ?>> N/A </option>
                        </select>
                       </span>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                            <tr>

                                                                <th colspan="4" style="border-top:0px;">
                                                                    <div id="<?php echo $table; ?>_unit_box">
                                                                        <label class="input"
                                                                               style="width:80%; margin:0 auto;">
                                                                            <select name="<?php echo $table; ?>_unit"
                                                                                    id="<?php echo $table; ?>_unit"
                                                                                    style="width:100%;"
                                                                                    class="select2 select2-offscreen"
                                                                                    required="" tabindex="-1" title="">
                                                                                <option value="Kg" <?php echo ($value['unit'] == 'Kg') ? 'selected' : ''; ?> >
                                                                                    Kg
                                                                                </option>
                                                                                <option value="N" <?php echo ($value['unit'] == 'N') ? 'selected' : ''; ?> >
                                                                                    N
                                                                                </option>
                                                                                <option value="Kgf" <?php echo ($value['unit'] == 'Kgf') ? 'selected' : ''; ?>>
                                                                                    Kgf
                                                                                </option>
                                                                                <option value="BPM" <?php echo ($value['unit'] == 'BPM') ? 'selected' : ''; ?>>
                                                                                    BPM
                                                                                </option>
                                                                                <option value="%SPO₂" <?php echo ($value['unit'] == '%SPO₂') ? 'selected' : ''; ?>>
                                                                                    %SPO₂
                                                                                </option>
                                                                                <option value="L/min" <?php echo ($value['unit'] == 'L/min') ? 'selected' : ''; ?>>
                                                                                    L/min
                                                                                </option>
                                                                                <option value="mV" <?php echo ($value['unit'] == 'mV') ? 'selected' : ''; ?>>
                                                                                    mV
                                                                                </option>
                                                                                <option value="V" <?php echo ($value['unit'] == 'V') ? 'selected' : ''; ?>>
                                                                                    V
                                                                                </option>
                                                                                <option value="hpa" <?php echo ($value['unit'] == 'hpa') ? 'selected' : ''; ?>>
                                                                                    hpa
                                                                                </option>
                                                                                <option value="μW/cm²" <?php echo ($value['unit'] == 'μW/cm²') ? 'selected' : ''; ?>>
                                                                                    μW/cm²
                                                                                </option>
                                                                            </select>
                                                                        </label>
                                                                        <script>jQuery("#<?php echo $table; ?>_unit").select2();</script>
                                                                    </div>
                                                                </th>

                                                                <th colspan="10"
                                                                    id="<?php echo $table; ?>-set-tolerance-form-box"
                                                                    style="border-top:0px;">

                                                                    <?php

                                                                    if ($value['tolerance_type'] == 1) {
                                                                        ?>

                                                                        <div class="row">
                                                                            <div class="col col-lg-4 col-md-12">
                                                                                <input name="<?php echo $table; ?>_check_tolerance_unit"
                                                                                       id="<?php echo $table; ?>_check_tolerance_unit"
                                                                                       class="<?php echo $table; ?>_check_tolerance user-success"
                                                                                       value="1" type="checkbox"
                                                                                       style="float:left; margin-left:10px; margin-top:12px;" <?php echo ($value['check_tolerance_unit'] == '1') ? 'checked' : ''; ?> >
                                                                                <label class="input"
                                                                                       style="margin-left:25px;">
                                                                                    <i class="icon-append fa"
                                                                                       style="right:auto; left:5px; border-left:0px;">±</i>
                                                                                    <i class="icon-append fa"
                                                                                       style="width:50px; border:0px;">Value</i>
                                                                                    <input name="<?php echo $table; ?>_tolerance_unit"
                                                                                           id="<?php echo $table; ?>_tolerance_unit"
                                                                                           type="text"
                                                                                           style="padding-left:30px; border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;"
                                                                                           value="<?php echo $value['tolerance_unit']; ?>"
                                                                                           number="true"
                                                                                           class="user-success">
                                                                                </label>
                                                                            </div>

                                                                            <div class="col col-lg-4 col-md-12">
                                                                                <input name="<?php echo $table; ?>_check_tolerance_percent"
                                                                                       id="<?php echo $table; ?>_check_tolerance_percent"
                                                                                       class="<?php echo $table; ?>_check_tolerance"
                                                                                       value="1" type="checkbox"
                                                                                       style="float:left; margin-left:10px; margin-top:12px;" <?php echo ($value['check_tolerance_percent'] == '1') ? 'checked' : ''; ?> >
                                                                                <label class="input"
                                                                                       style="margin-left:25px;">
                                                                                    <i class="icon-append fa"
                                                                                       style="right:auto; left:5px; border-left:0px;">±</i>
                                                                                    <i class="icon-append fa"
                                                                                       style="width:50px; border:0px;">%</i>
                                                                                    <input name="<?php echo $table; ?>_tolerance_percent"
                                                                                           id="<?php echo $table; ?>_tolerance_percent"
                                                                                           type="text"
                                                                                           style="padding-left:30px; border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;"
                                                                                           value="<?php echo $value['tolerance_percent']; ?>"
                                                                                           number="true"
                                                                                           class="user-success">
                                                                                </label>
                                                                            </div>

                                                                            <div class="col col-lg-4 col-md-12">
                                                                                <input name="<?php echo $table; ?>_check_tolerance_fso"
                                                                                       id="<?php echo $table; ?>_check_tolerance_fso"
                                                                                       class="<?php echo $table; ?>_check_tolerance"
                                                                                       value="1" type="checkbox"
                                                                                       style="float:left; margin-left:10px; margin-top:12px;" <?php echo ($value['check_tolerance_fso'] == '1') ? 'checked' : ''; ?> >
                                                                                <label class="input"
                                                                                       style="margin-left:25px;">
                                                                                    <i class="icon-append fa"
                                                                                       style="right:auto; left:5px; border-left:0px;">±</i>
                                                                                    <i class="icon-append fa"
                                                                                       style="width:50px; border:0px;">%
                                                                                        FSO</i>
                                                                                    <input name="<?php echo $table; ?>_tolerance_fso_percent"
                                                                                           id="<?php echo $table; ?>_tolerance_fso_percent"
                                                                                           type="text"
                                                                                           style="padding-left:30px; border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;"
                                                                                           value="<?php echo $value['tolerance_fso_percent']; ?>"
                                                                                           number="true">
                                                                                    <input name="<?php echo $table; ?>_tolerance_fso_val"
                                                                                           id="<?php echo $table; ?>_tolerance_fso_val"
                                                                                           type="hidden" value="0">
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                    <?php } else if ($value['tolerance_type'] == 2) { ?>

                                                                        <div class="row">
                                                                            <div class="col col-3">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div class="col col-2"
                                                                                 style="text-align:right">
                                                                                <label class="input"
                                                                                       style="margin-top:6px;">&gt;</label>
                                                                            </div>
                                                                            <div class="col col-3">
                                                                                <label class="input">
                                                                                    <input name="<?php echo $table; ?>_set_tole_amount"
                                                                                           id="<?php echo $table; ?>_set_tole_amount"
                                                                                           class="set-tole-amount user-error"
                                                                                           tbid="<?php echo $table; ?>"
                                                                                           type-id="2" type="text"
                                                                                           style="border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;"
                                                                                           value="<?php echo $value['set_tole_amount']; ?>"
                                                                                           required="" number="true">
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                    <?php } else if ($value['tolerance_type'] == 3) { ?>

                                                                        <div class="row">
                                                                            <div class="col col-3">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div class="col col-2"
                                                                                 style="text-align:right">
                                                                                <label class="input"
                                                                                       style="margin-top:6px;">≥</label>
                                                                            </div>
                                                                            <div class="col col-3">
                                                                                <label class="input">
                                                                                    <input name="<?php echo $table; ?>_set_tole_amount"
                                                                                           id="<?php echo $table; ?>_set_tole_amount"
                                                                                           class="set-tole-amount user-error"
                                                                                           tbid="<?php echo $table; ?>"
                                                                                           type-id="3" type="text"
                                                                                           style="border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;"
                                                                                           value="<?php echo $value['set_tole_amount']; ?>"
                                                                                           number="true">
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                    <?php } else if ($value['tolerance_type'] == 4) { ?>

                                                                        <div class="row">
                                                                            <div class="col col-3">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div class="col col-2"
                                                                                 style="text-align:right">
                                                                                <label class="input"
                                                                                       style="margin-top:6px;">&lt;</label>
                                                                            </div>
                                                                            <div class="col col-3">
                                                                                <label class="input">
                                                                                    <input name="<?php echo $table; ?>_set_tole_amount"
                                                                                           id="<?php echo $table; ?>_set_tole_amount"
                                                                                           class="set-tole-amount user-error"
                                                                                           tbid="<?php echo $table; ?>"
                                                                                           type-id="4" type="text"
                                                                                           style="border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;"
                                                                                           value="<?php echo $value['set_tole_amount']; ?>"
                                                                                           number="true">
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                    <?php } else if ($value['tolerance_type'] == 5) { ?>
                                                                        <div class="row">
                                                                            <div class="col col-3">
                                                                                &nbsp;
                                                                            </div>
                                                                            <div class="col col-2"
                                                                                 style="text-align:right">
                                                                                <label class="input"
                                                                                       style="margin-top:6px;">≤</label>
                                                                            </div>
                                                                            <div class="col col-3">
                                                                                <label class="input">
                                                                                    <input name="<?php echo $table; ?>_set_tole_amount"
                                                                                           id="<?php echo $table; ?>_set_tole_amount"
                                                                                           class="set-tole-amount user-error"
                                                                                           tbid="<?php echo $table; ?>"
                                                                                           type-id="5" type="text"
                                                                                           style="border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;"
                                                                                           value="<?php echo $value['set_tole_amount']; ?>"
                                                                                           number="true">
                                                                                </label>
                                                                            </div>
                                                                        </div>

                                                                    <?php } else if ($value['tolerance_type'] == 6) { ?>

                                                                        <div style="text-align:center; font-weight:normal">
                                                                            <em>N/A</em></div>

                                                                    <?php } ?>

                                                                </th>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    <label class="input">
                                                                        <input name="<?php echo $table; ?>_uncer_setting"
                                                                               type="text"
                                                                               class="table-form-head-text user-success"
                                                                               style="font-style:italic;"
                                                                               value="<?php echo $value['uncer_setting']; ?>"
                                                                               placeholder="UUC* Setting">
                                                                    </label>
                                                                </td>

                                                                <td colspan="9">
                                                                    <label class="input">
                                                                        <input name="<?php echo $table; ?>_uncer_reading"
                                                                               type="text"
                                                                               class="table-form-head-text user-success"
                                                                               style="font-style:italic;"
                                                                               value="<?php echo $value['uncer_reading']; ?>"
                                                                               placeholder="Standard Reading">
                                                                    </label>
                                                                </td>
                                                                <td colspan="4"
                                                                    style="text-align:center; font-weight:bold;">
                                                                    Acception
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>
                                                                    <label class="input">
                                                                        <input name="<?php echo $table; ?>_head_col1"
                                                                               type="text"
                                                                               class="table-form-head-text user-success"
                                                                               style="font-style:italic;"
                                                                               value="<?php echo $value['head_col1']; ?>"
                                                                               placeholder="Setting">
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="input">
                                                                        <input name="<?php echo $table; ?>_head_col2"
                                                                               type="text"
                                                                               class="table-form-head-text user-success"
                                                                               style="font-style:italic;"
                                                                               value="<?php echo $value['head_col2']; ?>"
                                                                               placeholder="Reading 1">
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="input">
                                                                        <input name="<?php echo $table; ?>_head_col3"
                                                                               type="text"
                                                                               class="table-form-head-text user-success"
                                                                               style="font-style:italic;"
                                                                               value="<?php echo $value['head_col3']; ?>"
                                                                               placeholder="Reading 2">
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="input">
                                                                        <input name="<?php echo $table; ?>_head_col4"
                                                                               type="text" class="table-form-head-text"
                                                                               style="font-style:italic;"
                                                                               value="<?php echo $value['head_col4']; ?>"
                                                                               placeholder="Reading 3">
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="input">
                                                                        <input name="<?php echo $table; ?>_head_col5"
                                                                               type="text" class="table-form-head-text"
                                                                               style="font-style:italic;"
                                                                               value="<?php echo $value['head_col5']; ?>"
                                                                               placeholder="Reading 4">
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="input">
                                                                        <input name="<?php echo $table; ?>_head_col6"
                                                                               type="text" class="table-form-head-text"
                                                                               style="font-style:italic;"
                                                                               value="<?php echo $value['head_col6']; ?>"
                                                                               placeholder="Reading 5">
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="input">
                                                                        <input name="<?php echo $table; ?>_head_col7"
                                                                               type="text" class="table-form-head-text"
                                                                               style="font-style:italic;"
                                                                               value="<?php echo $value['head_col7']; ?>"
                                                                               placeholder="Reading 6">
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="input">
                                                                        <input name="<?php echo $table; ?>_head_col8"
                                                                               type="text" class="table-form-head-text"
                                                                               style="font-style:italic;"
                                                                               value="<?php echo $value['head_col8']; ?>"
                                                                               placeholder="Reading 7">
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <label class="input">
                                                                        <input name="<?php echo $table; ?>_head_col9"
                                                                               type="text" class="table-form-head-text"
                                                                               style="font-style:italic; border-color: 0px;"
                                                                               value="<?php echo $value['head_col9']; ?>"
                                                                               placeholder="Reading 8">
                                                                    </label>
                                                                </td>

                                                                <td><label class="input"
                                                                           style="text-align:center; font-size:95%;">Standard<br>Resolution</label>
                                                                </td>
                                                                <td><label class="input"
                                                                           style="text-align:center">Min</label></td>
                                                                <td><label class="input"
                                                                           style="text-align:center">Max</label></td>
                                                                <td><label class="input" style="text-align:center">Average</label>
                                                                </td>
                                                                <td style="text-align:center;">*</td>
                                                            </tr>

                                                            </tbody>
                                                            <tbody id="<?php echo $table; ?>-sortable"
                                                                   class="ui-sortable">


                                                            <?php foreach ($value['list'] as $k => $v) {

                                                                $tr = 'tr' . generateRandomString();
                                                                ?>
                                                                <tr id="<?php echo $tr; ?>"
                                                                    class="tr-form tr-form-<?php echo $table; ?>">
                                                                    <td style="position:relative;">
                                                                        <input type="hidden"
                                                                               class="<?php echo $table; ?>_rows"
                                                                               name="<?php echo $table; ?>_rows[]"
                                                                               value="<?php echo $tr; ?>">
                                                                        <input type="hidden"
                                                                               id="<?php echo $tr; ?>_has_input"
                                                                               value="0">
                                                                        <label class="input">
                                                                            <input name="<?php echo $tr; ?>_col1"
                                                                                   id="<?php echo $tr; ?>_col1"
                                                                                   type="text"
                                                                                   class="table-form-input-text set_<?php echo $tr; ?> user-success"
                                                                                   value="<?php echo $v['col1']; ?>">
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="input">
                                                                            <input name="<?php echo $tr; ?>_col2"
                                                                                   id="<?php echo $tr; ?>_col2"
                                                                                   type="text"
                                                                                   class="table-form-input-text read_<?php echo $tr; ?> warning-over"
                                                                                   tb-id="<?php echo $table; ?>"
                                                                                   warning-id="<?php echo $tr; ?>"
                                                                                   value="" number="true">
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="input">
                                                                            <input name="<?php echo $tr; ?>_col3"
                                                                                   id="<?php echo $tr; ?>_col3"
                                                                                   type="text"
                                                                                   class="table-form-input-text read_<?php echo $tr; ?> warning-over"
                                                                                   tb-id="<?php echo $table; ?>"
                                                                                   warning-id="<?php echo $tr; ?>"
                                                                                   value="" number="true">
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="input">
                                                                            <input name="<?php echo $tr; ?>_col4"
                                                                                   id="<?php echo $tr; ?>_col4"
                                                                                   type="text"
                                                                                   class="table-form-input-text read_<?php echo $tr; ?> warning-over"
                                                                                   tb-id="<?php echo $table; ?>"
                                                                                   warning-id="<?php echo $tr; ?>"
                                                                                   value="" number="true">
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="input">
                                                                            <input name="<?php echo $tr; ?>_col5"
                                                                                   id="<?php echo $tr; ?>_col5"
                                                                                   type="text"
                                                                                   class="table-form-input-text read_<?php echo $tr; ?> warning-over"
                                                                                   tb-id="<?php echo $table; ?>"
                                                                                   warning-id="<?php echo $tr; ?>"
                                                                                   value="" number="true">
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="input">
                                                                            <input name="<?php echo $tr; ?>_col6"
                                                                                   id="<?php echo $tr; ?>_col6"
                                                                                   type="text"
                                                                                   class="table-form-input-text read_<?php echo $tr; ?> warning-over"
                                                                                   tb-id="<?php echo $table; ?>"
                                                                                   warning-id="<?php echo $tr; ?>"
                                                                                   value="" number="true">
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="input">
                                                                            <input name="<?php echo $tr; ?>_col7"
                                                                                   id="<?php echo $tr; ?>_col7"
                                                                                   type="text"
                                                                                   class="table-form-input-text read_<?php echo $tr; ?> warning-over"
                                                                                   tb-id="<?php echo $table; ?>"
                                                                                   warning-id="<?php echo $tr; ?>"
                                                                                   value="" number="true">
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="input">
                                                                            <input name="<?php echo $tr; ?>_col8"
                                                                                   id="<?php echo $tr; ?>_col8"
                                                                                   type="text"
                                                                                   class="table-form-input-text read_<?php echo $tr; ?> warning-over"
                                                                                   tb-id="<?php echo $table; ?>"
                                                                                   warning-id="<?php echo $tr; ?>"
                                                                                   value="" number="true">
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="input">
                                                                            <input name="<?php echo $tr; ?>_col9"
                                                                                   id="<?php echo $tr; ?>_col9"
                                                                                   type="text"
                                                                                   class="table-form-input-text read_<?php echo $tr; ?> warning-over"
                                                                                   tb-id="<?php echo $table; ?>"
                                                                                   warning-id="<?php echo $tr; ?>"
                                                                                   value="" number="true">
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="input">
                                                                            <input name="<?php echo $tr; ?>_std_resolution"
                                                                                   id="<?php echo $tr; ?>_std_resolution"
                                                                                   type="text"
                                                                                   class="table-form-input-text resolution_<?php echo $tr; ?> user-success"
                                                                                   value="<?php echo $v['std_resolution']; ?>"
                                                                                   number="true">
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="input">
                                                                            <input name="<?php echo $tr; ?>_min"
                                                                                   id="<?php echo $tr; ?>_min"
                                                                                   type="text"
                                                                                   class="table-form-input-text caltol_<?php echo $tr; ?>"
                                                                                   value="<?php echo $v['min']; ?>">
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <label class="input">
                                                                            <input name="<?php echo $tr; ?>_max"
                                                                                   id="<?php echo $tr; ?>_max"
                                                                                   type="text"
                                                                                   class="table-form-input-text caltol_<?php echo $tr; ?>"
                                                                                   value="<?php echo $v['max']; ?>">
                                                                        </label>
                                                                    </td>
                                                                    <td style="text-align:center">
                                                                        <label class="input"
                                                                               id="<?php echo $tr; ?>_average">0.00</label>
                                                                    </td>
                                                                    <td style="text-align:center; width:35px;">
                                                                        <a href="javascript:removeRow('<?php echo $tr; ?>', '<?php echo $table; ?>');"><i
                                                                                    class="fa fa-times txt-color-red"></i></a>
                                                                    </td>

                                                                    <script>
                                                                        jQuery("#<?php echo $tr;?>_col1").keyup(function () {
                                                                            CalculateTolerance('<?php echo $table;?>');
                                                                        });
                                                                        jQuery(".read_<?php echo $tr;?>").keyup(function () {
                                                                            CalculateAverage('<?php echo $tr;?>');
                                                                        });
                                                                        jQuery(".caltol_<?php echo $tr;?>").keyup(function () {
                                                                            CalculateAverage('<?php echo $tr;?>');
                                                                        });
                                                                        jQuery(".warning-over").blur(function () {
                                                                            var TbID = jQuery(this).attr("tb-id");
                                                                            var trid = jQuery(this).attr("warning-id");
                                                                            var ToleranceType = parseInt(jQuery('#' + TbID + '_tolerance_type').val());

                                                                            var input = parseFloat(jQuery(this).val());
                                                                            var accept_min = parseFloat(jQuery("#" + trid + "_min").val());
                                                                            var accept_max = parseFloat(jQuery("#" + trid + "_max").val());

                                                                            if (ToleranceType > 1) {
                                                                                if (ToleranceType == 2) {
                                                                                    if (input > accept_min) {
                                                                                        $(this).css('background-color', '#FFFFFF');
                                                                                    } else {
                                                                                        jQuery(this).css('background-color', '#EFE1B3');
                                                                                    }
                                                                                } else if (ToleranceType == 3) {
                                                                                    if (input >= accept_min) {
                                                                                        $(this).css('background-color', '#FFFFFF');
                                                                                    } else {
                                                                                        jQuery(this).css('background-color', '#EFE1B3');
                                                                                    }
                                                                                } else if (ToleranceType == 4) {
                                                                                    if (input < accept_max) {
                                                                                        $(this).css('background-color', '#FFFFFF');
                                                                                    } else {
                                                                                        jQuery(this).css('background-color', '#EFE1B3');
                                                                                    }
                                                                                } else if (ToleranceType == 5) {
                                                                                    if (input <= accept_max) {
                                                                                        $(this).css('background-color', '#FFFFFF');
                                                                                    } else {
                                                                                        jQuery(this).css('background-color', '#EFE1B3');
                                                                                    }
                                                                                }
                                                                            } else {
                                                                                if (input < accept_min || input > accept_max) {
                                                                                    jQuery(this).css('background-color', '#EFE1B3');
                                                                                } else {
                                                                                    jQuery(this).css('background-color', '#FFFFFF');
                                                                                }
                                                                            }
                                                                        });
                                                                    </script>

                                                                </tr>

                                                            <?php } ?>

                                                            </tbody>

                                                        </table>

                                                    </div>

                                                    <script>
                                                        jQuery('#<?php echo $table;?>_tolerance_type').change(function () {
                                                            var tbid = jQuery(this).attr('tb-id');
                                                            set_tolerance_type(tbid);
                                                            CalculateTolerance(tbid);
                                                        });

                                                        jQuery(function () {
                                                            jQuery("#<?php echo $table;?>-sortable").sortable();
                                                        });
                                                        //addRow('<?php echo $table;?>');
                                                        jQuery("#<?php echo $table;?>_tolerance_unit").keyup(function () {
                                                            CalculateTolerance('<?php echo $table;?>');
                                                        });
                                                        jQuery("#<?php echo $table;?>_tolerance_percent").keyup(function () {
                                                            CalculateTolerance('<?php echo $table;?>');
                                                        });
                                                        jQuery("#<?php echo $table;?>_tolerance_fso_percent").keyup(function () {
                                                            CalculateTolerance('<?php echo $table;?>');
                                                        });
                                                        jQuery("#<?php echo $table;?>_tolerance_fso_val").keyup(function () {
                                                            CalculateTolerance('<?php echo $table;?>');
                                                        });
                                                        jQuery(".<?php echo $table;?>_check_tolerance").change(function () {
                                                            CalculateTolerance('<?php echo $table;?>');
                                                        });

                                                    </script>

                                                </fieldset>


                                            <?php } else if ($value['choice_type'] == 'pm') { ?>

                                                <fieldset id="remove_<?php echo $table; ?>" class="pm-form-box"
                                                          style="padding-bottom:20px;">
                                                    <input type="hidden" name="tables_parameter[]"
                                                           class="tables_parameter" value="<?php echo $table; ?>">
                                                    <input type="hidden" name="<?php echo $table; ?>_type"
                                                           id="<?php echo $table; ?>_type" value="pm">
                                                    <div class="row">
                                                        <section class="col col-10" style="margin-bottom:5px;">
                                                            <label class="input">
                                                                <input name="<?php echo $table; ?>_parameter"
                                                                       type="text" class="pm-inphead user-success"
                                                                       style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;"
                                                                       value="<?php echo $value['choice_title']; ?>">
                                                            </label>
                                                        </section>

                                                        <section class="col col-2" style="margin-bottom:5px;">
                                                            <div class="btn-group" style="float:right;">

                                                                <button class="btn dropdown-toggle btn-sm btn-default"
                                                                        data-toggle="dropdown" autocomplete="off"
                                                                        aria-expanded="false">
                                                                    <i class="fa fa-cog"></i>
                                                                </button>
                                                                <a href="javascript:ShowHideTable('<?php echo $table; ?>');"
                                                                   id="<?php echo $table; ?>-collapse"
                                                                   class="btn btn-sm btn-default"><i
                                                                            class="fa fa-minus"></i></a>
                                                                <span class="btn btn-sm btn-default"><i
                                                                            class="fa fa-sort" style="cursor:move"></i></span>
                                                                <ul class="dropdown-menu pull-right js-status-update">
                                                                    <li>
                                                                        <a href="javascript:addRowPM('<?php echo $table; ?>');"><i
                                                                                    class="fa fa-plus-square"></i>
                                                                            เพิ่มแถว</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:removeTable('remove_<?php echo $table; ?>');"><i
                                                                                    class="fa fa-trash-o"></i>
                                                                            ลบตาราง</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </section>
                                                    </div>

                                                    <table id="<?php echo $table; ?>" style="display:table"
                                                           class="table table-bordered table-form table-pm">
                                                        <thead>
                                                        <tr>
                                                            <td style="width:5%" class="pm-head"><a
                                                                        href="javascript:ResetPMRowNumber('<?php echo $table; ?>')">No.
                                                                    <i class="fa fa-arrow-down"></i></a></td>
                                                            <td style="width:40%" class="pm-head">List</td>
                                                            <td style="width:10%" class="pm-head"><a
                                                                        href="javascript:PMSelectAllPass('<?php echo $table; ?>');">Pass</a>
                                                            </td>
                                                            <td style="width:10%" class="pm-head"><a
                                                                        href="javascript:PMSelectAllFail('<?php echo $table; ?>');">Fail</a>
                                                            </td>
                                                            <td style="width:10%" class="pm-head"><a
                                                                        href="javascript:PMSelectAllNone('<?php echo $table; ?>');">None</a>
                                                            </td>
                                                            <td style="width:25%" class="pm-head" colspan="2">Comment
                                                            </td>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="<?php echo $table; ?>-sortable" class="ui-sortable">


                                                        <?php
                                                        foreach ($value['list'] as $k => $v) {
                                                            $tr = 'tr' . generateRandomString();
                                                            ?>

                                                            <tr id="<?php echo $tr; ?>"
                                                                class="tr-form tr-form-<?php echo $table; ?>">
                                                                <td width="5%" style="position:relative;">
                                                                    <input type="hidden"
                                                                           class="<?php echo $table; ?>_rows"
                                                                           name="<?php echo $table; ?>_rows[]"
                                                                           value="<?php echo $tr; ?>">
                                                                    <label class="input">
                                                                        <input name="<?php echo $tr; ?>_no"
                                                                               id="<?php echo $tr; ?>_no" type="text"
                                                                               class="pm-center"
                                                                               value="<?php echo $v['sequence_detail']; ?>"
                                                                               required="">
                                                                    </label>
                                                                </td>
                                                                <td width="40%">
                                                                    <label class="input">
                                                                        <!-- <input name="<?php //echo $tr;
                                                                        ?>_list" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php //echo $v['list'];
                                                                        ?>' > -->
                                                                        <span role="status" aria-live="polite"
                                                                              class="ui-helper-hidden-accessible">1 result is available, use up and down arrow keys to navigate.</span>
                                                                        <input name="<?php echo $tr; ?>_list"
                                                                               type="text"
                                                                               class="pm-left pm-list-autocomplete ui-autocomplete-input user-success"
                                                                               value='<?php echo $v['list']; ?>'
                                                                               required="" autocomplete="off">
                                                                    </label>
                                                                </td>
                                                                <td width="10%" class="pm-list-boxs"
                                                                    box-id="<?php echo $tr; ?>">
                                                                    <div style="width:19px; margin:0 auto">
                                                                        <label class="radio"
                                                                               id="<?php echo $tr; ?>_result_pass">
                                                                            <input class="<?php echo $table; ?>-pass click-reset"
                                                                                   id="<?php echo $tr; ?>-recheck-pass"
                                                                                   name="<?php echo $tr; ?>_result"
                                                                                   value="Pass" type="radio"
                                                                                   disabled="disabled"><i></i>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td width="10%">
                                                                    <div style="width:19px; margin:0 auto">
                                                                        <label class="radio"
                                                                               id="<?php echo $tr; ?>_result_fail">
                                                                            <input class="<?php echo $table; ?>-fail click-reset"
                                                                                   id="<?php echo $tr; ?>-recheck-fail"
                                                                                   name="<?php echo $tr; ?>_result"
                                                                                   value="Fail" type="radio"
                                                                                   disabled="disabled"><i></i>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td width="10%">
                                                                    <div style="width:19px; margin:0 auto">
                                                                        <label class="radio"
                                                                               id="<?php echo $tr; ?>_result_none">
                                                                            <input class="<?php echo $table; ?>-none click-reset"
                                                                                   id="<?php echo $tr; ?>-recheck-none"
                                                                                   name="<?php echo $tr; ?>_result"
                                                                                   value="None" type="radio"
                                                                                   disabled="disabled"><i></i>
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <label class="input">
                                                                        <input name="<?php echo $tr; ?>_comment"
                                                                               type="text" class="pm-left" value=""
                                                                               readonly="readonly">
                                                                    </label>
                                                                </td>
                                                                <td style="text-align:center; width:45px;">
                                                                    <!-- <a href="javascript:UncheckPMRow('<?php //echo $tr;
                                                                    ?>', '<?php //echo $table;
                                                                    ?>');"><i class="fa fa-undo txt-color-blue"></i></a> &nbsp; -->
                                                                    <a href="javascript:removeRow('<?php echo $tr; ?>', '<?php echo $table; ?>');"><i
                                                                                class="fa fa-times txt-color-red"></i></a>
                                                                </td>
                                                            </tr>

                                                        <?php } ?>
                                                        </tbody>

                                                    </table>

                                                    <script>
                                                        //addRowPM('<?php echo $table;?>');

                                                        $(function () {
                                                            PMListSortable('<?php echo $table;?>');
                                                        });
                                                    </script>

                                                </fieldset>


                                            <?php } else if ($value['choice_type'] == 'note') { ?>

                                                <fieldset id="remove_<?php echo $table; ?>" class="note-form-box"
                                                          style="padding-bottom:20px;">
                                                    <input type="hidden" name="tables_parameter[]"
                                                           class="tables_parameter" value="<?php echo $table; ?>">
                                                    <input type="hidden" name="<?php echo $table; ?>_type"
                                                           id="<?php echo $table; ?>_type" value="note">
                                                    <div class="row">
                                                        <section class="col col-10" style="margin-bottom:5px;">
                                                            <label class="input">
                                                                <input name="<?php echo $table; ?>_headtext" type="text"
                                                                       value="<?php echo $value['choice_title']; ?>"
                                                                       class="note-head user-success"
                                                                       style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;">
                                                            </label>
                                                        </section>

                                                        <section class="col col-2" style="margin-bottom:5px;">
                                                            <div class="btn-group" style="float:right;">
                                                                <!-- <a href="javascript:ShowHideTable('<?php //echo $table;?>');" id="<?php //echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a> -->
                                                                <button class="btn dropdown-toggle btn-sm btn-default"
                                                                        data-toggle="dropdown">
                                                                    <i class="fa fa-cog"></i>
                                                                </button>
                                                                <span class="btn btn-sm btn-default"><i
                                                                            class="fa fa-sort" style="cursor:move"></i></span>
                                                                <ul class="dropdown-menu pull-right js-status-update">
                                                                    <li>
                                                                        <a href="javascript:removeTable('remove_<?php echo $table; ?>');"><i
                                                                                    class="fa fa-trash-o"></i>
                                                                            ลบตาราง</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </section>
                                                    </div>
                                                    <section id="<?php echo $table; ?>" class="table table-note">
                                                        <label class="textarea textarea-resizable">
                                                            <textarea name="<?php echo $table; ?>_notetext"
                                                                      id="<?php echo $table; ?>_notetext" rows="3"
                                                                      class="custom-scroll note-input user-success"
                                                                      readonly="readonly"></textarea>
                                                        </label>
                                                    </section>
                                                </fieldset>

                                            <?php }


                                        }

                                        ?>


                                    </div>
                                </div>

                                <!-- </form> -->

                            </div>
                            <!-- end widget content -->

                        </div>
                        <!-- end widget div -->
                    </div>
                    <!-- end widget -->
                </article>
                <!-- END COL -->
            </div>
            <!-- END ROW -->
            <!-- START ROW --><!-- END ROW -->
            <!-- NEW ROW --><!-- END ROW-->
        </section>
    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->
<!-- <div id="dialog_save_form_as" title="Save Form As">
   <form id="save-as-form-box">
      <div class="row">
         <div class="col-md-3"><label style="padding-top:6px;">Save As</label></div>
         <div class="col-md-9">
            <input id="input-form-name" type="text" class="form-control" placeholder="Untitled Form" required />
         </div>
      </div>
      <div class="row" style="margin-top:10px;">
         <div class="col-md-3"><label style="padding-top:6px;">Save Type</label></div>
         <div class="col-md-9">
            <select name="select_form_type" id="select-form-type" class="form-control" style="width:100%;" required>
               <option value="CAL">CAL</option>
               <option value="PM">PM</option>
            </select>
            <i></i>
         </div>
      </div>
   </form>
</div> -->
<!-- Modal -->

<div class="modal fade" id="SourceOfUncertainty" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="source-of-uncertainty-form">
                <input type="hidden" id="source-of-uncertainty-tbid" value=""/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">การคำนวนค่า Uncertainty</h4>
                </div>
                <div class="modal-body" style="padding:5px;">
                    <div class="row" style="margin-bottom:10px; margin-top:10px;">
                        <div class="col-md-4 smart-form">
                            <label class="radio">รายงานผล</label>
                        </div>
                        <div class="col-md-4 smart-form">
                            <label class="radio" id="set_report_uncertainty_box">
                                <input name="set_report_type" id="set_report_uncertainty" value="uncertainty"
                                       type="radio"><i></i> รายงานค่า Uncertainty
                            </label>
                        </div>
                        <div class="col-md-4 smart-form">
                            <label class="radio" id="set_report_average_box">
                                <input name="set_report_type" id="set_report_average" value="average"
                                       type="radio"><i></i> รายงานค่า Average
                            </label>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom:20px; margin-top:10px;">
                        <div class="col-md-4 smart-form">
                            <label class="radio">เพิ่มเติม</label>
                        </div>
                        <div class="col-md-8 smart-form">
                            <label class="checkbox"><input name="adds[phototherapy]" id="adds_phototherapy"
                                                           class="report_adds" value="1" type="checkbox"><i></i>
                                PHOTOTHERAPY</label>
                        </div>
                    </div>
                    <strong>Source of Uncertainty</strong>
                    <table class="table-bordered" style="margin-bottom:0px;">
                        <tbody>
                        <tr>
                            <th style="text-align:center; width:6%;">Use</th>
                            <th style="text-align:center; width:10%;">Symbol</th>
                            <th style="text-align:center; width:30%;">Source</th>
                            <th style="text-align:center; width:15%;">&plusmn; Value</th>
                            <th style="text-align:center; width:13%;">Divisor</th>
                            <th style="text-align:center; width:13%;">Ci</th>
                            <th style="text-align:center; width:13%;">Vi or Veff<br/><span
                                        style="font-size:70%; font-weight:normal;">0 = Infinity</span></th>
                        </tr>
                        <tr>
                            <td style="text-align:center"><input class="checked-source-active" symbol="u1"
                                                                 type="checkbox" name="source_active[u1]"
                                                                 id="source_active_u1" style="margin:0px;"
                                                                 checked="checked"/></td>
                            <td style="text-align:center">x1<input type="hidden" class="uncer_source_symbol"
                                                                   value="u1"/></td>
                            <td style="font-size:85%; padding-left:2px; padding-right:2px;">Repeatability of
                                indication<input name="source_name[u1]" id="source_name_u1" type="hidden"/></td>
                            <td><input name="source_value[u1]" id="source_value_u1" class="form-control" type="text"
                                       style="text-align:center" disabled="disabled" number="1"></td>
                            <td><input name="source_divisor[u1]" id="source_divisor_u1" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_ci[u1]" id="source_ci_u1" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_veff[u1]" id="source_veff_u1" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center"><input class="checked-source-active" symbol="u2"
                                                                 type="checkbox" name="source_active[u2]"
                                                                 id="source_active_u2" style="margin:0px;"
                                                                 checked="checked"/></td>
                            <td style="text-align:center">x2<input type="hidden" class="uncer_source_symbol"
                                                                   value="u2"/></td>
                            <td style="font-size:85%; padding-left:2px; padding-right:2px;">Resolution of UUC<input
                                        name="source_name[u2]" id="source_name_u2" type="hidden"/></td>
                            <td><input name="source_value[u2]" id="source_value_u2" class="form-control" type="text"
                                       style="text-align:center" disabled="disabled" number="1"></td>
                            <td><input name="source_divisor[u2]" id="source_divisor_u2" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_ci[u2]" id="source_ci_u2" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_veff[u2]" id="source_veff_u2" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center"><input class="checked-source-active" symbol="u3"
                                                                 type="checkbox" name="source_active[u3]"
                                                                 id="source_active_u3" style="margin:0px;"
                                                                 checked="checked"/></td>
                            <td style="text-align:center">x3<input type="hidden" class="uncer_source_symbol"
                                                                   value="u3"/></td>
                            <td style="font-size:85%; padding-left:2px; padding-right:2px;">Resolution of Standard<input
                                        name="source_name[u3]" id="source_name_u3" type="hidden"/></td>
                            <td><input name="source_value[u3]" id="source_value_u3" class="form-control" type="text"
                                       style="text-align:center" disabled="disabled" number="1"></td>
                            <td><input name="source_divisor[u3]" id="source_divisor_u3" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_ci[u3]" id="source_ci_u3" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_veff[u3]" id="source_veff_u3" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center"><input class="checked-source-active" symbol="u4"
                                                                 type="checkbox" name="source_active[u4]"
                                                                 id="source_active_u4" style="margin:0px;"
                                                                 checked="checked"/></td>
                            <td style="text-align:center">x4<input type="hidden" class="uncer_source_symbol"
                                                                   value="u4"/></td>
                            <td style="font-size:85%; padding-left:2px; padding-right:2px;">Accuracy of Standard (% of
                                reading)<input name="source_name[u4]" id="source_name_u4" type="hidden"/></td>
                            <td><input name="source_value[u4]" id="source_value_u4" class="form-control" type="text"
                                       style="text-align:center" disabled="disabled" number="1"></td>
                            <td><input name="source_divisor[u4]" id="source_divisor_u4" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_ci[u4]" id="source_ci_u4" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_veff[u4]" id="source_veff_u4" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center"><input class="checked-source-active" symbol="u5"
                                                                 type="checkbox" name="source_active[u5]"
                                                                 id="source_active_u5" style="margin:0px;"
                                                                 checked="checked"/></td>
                            <td style="text-align:center">x5<input type="hidden" class="uncer_source_symbol"
                                                                   value="u5"/></td>
                            <td style="font-size:85%; padding-left:2px; padding-right:2px;">Calibration of
                                Standard<input name="source_name[u5]" id="source_name_u5" type="hidden"/></td>
                            <td><input name="source_value[u5]" id="source_value_u5" class="form-control" type="text"
                                       style="text-align:center" disabled="disabled" number="1"></td>
                            <td><input name="source_divisor[u5]" id="source_divisor_u5" class="form-control" type="text"
                                       style="text-align:center" disabled="disabled" number="1"></td>
                            <td><input name="source_ci[u5]" id="source_ci_u5" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_veff[u5]" id="source_veff_u5" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center"><input class="checked-source-active" symbol="u6"
                                                                 type="checkbox" name="source_active[u6]"
                                                                 id="source_active_u6" style="margin:0px;"
                                                                 checked="checked"/></td>
                            <td style="text-align:center">x6<input type="hidden" class="uncer_source_symbol"
                                                                   value="u6"/></td>
                            <td style="font-size:85%; padding-left:2px; padding-right:2px;">Accuracy of Standard (±
                                Value)<input name="source_name[u6]" id="source_name_u6" type="hidden"/></td>
                            <td><input name="source_value[u6]" id="source_value_u6" class="form-control" type="text"
                                       style="text-align:center" disabled="disabled" number="1"></td>
                            <td><input name="source_divisor[u6]" id="source_divisor_u6" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_ci[u6]" id="source_ci_u6" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_veff[u6]" id="source_veff_u6" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center"><input class="checked-source-active" symbol="u7"
                                                                 type="checkbox" name="source_active[u7]"
                                                                 id="source_active_u7" style="margin:0px;"
                                                                 checked="checked"/></td>
                            <td style="text-align:center">x7<input type="hidden" class="uncer_source_symbol"
                                                                   value="u7"/></td>
                            <td style="font-size:85%; padding-left:2px; padding-right:2px;">Drift of Standard<input
                                        name="source_name[u7]" id="source_name_u7" type="hidden"/></td>
                            <td><input name="source_value[u7]" id="source_value_u7" class="form-control" type="text"
                                       style="text-align:center" disabled="disabled" number="1"></td>
                            <td><input name="source_divisor[u7]" id="source_divisor_u7" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_ci[u7]" id="source_ci_u7" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_veff[u7]" id="source_veff_u7" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center"><input class="checked-source-active" symbol="u8"
                                                                 type="checkbox" name="source_active[u8]"
                                                                 id="source_active_u8" style="margin:0px;"
                                                                 checked="checked"/></td>
                            <td style="text-align:center">x8<input type="hidden" class="uncer_source_symbol"
                                                                   value="u8"/></td>
                            <td><input name="source_name[u8]" id="source_name_u8" class="form-control" type="text"
                                       style="font-size:85%; padding-left:2px; padding-right:2px;"></td>
                            <td><input name="source_value[u8]" id="source_value_u8" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_divisor[u8]" id="source_divisor_u8" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_ci[u8]" id="source_ci_u8" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_veff[u8]" id="source_veff_u8" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center"><input class="checked-source-active" symbol="u9"
                                                                 type="checkbox" name="source_active[u9]"
                                                                 id="source_active_u9" style="margin:0px;"
                                                                 checked="checked"/></td>
                            <td style="text-align:center">x9<input type="hidden" class="uncer_source_symbol"
                                                                   value="u9"/></td>
                            <td><input name="source_name[u9]" id="source_name_u9" class="form-control" type="text"
                                       style="font-size:85%; padding-left:2px; padding-right:2px;"></td>
                            <td><input name="source_value[u9]" id="source_value_u9" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_divisor[u9]" id="source_divisor_u9" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_ci[u9]" id="source_ci_u9" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_veff[u9]" id="source_veff_u9" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                        </tr>
                        <tr>
                            <td style="text-align:center"><input class="checked-source-active" symbol="u10"
                                                                 type="checkbox" name="source_active[u10]"
                                                                 id="source_active_u10" style="margin:0px;"
                                                                 checked="checked"/></td>
                            <td style="text-align:center">x10<input type="hidden" class="uncer_source_symbol"
                                                                    value="u10"/></td>
                            <td><input name="source_name[u10]" id="source_name_u10" class="form-control" type="text"
                                       style="font-size:85%; padding-left:2px; padding-right:2px;"></td>
                            <td><input name="source_value[u10]" id="source_value_u10" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_divisor[u10]" id="source_divisor_u10" class="form-control"
                                       type="text" style="text-align:center" number="1"></td>
                            <td><input name="source_ci[u10]" id="source_ci_u10" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                            <td><input name="source_veff[u10]" id="source_veff_u10" class="form-control" type="text"
                                       style="text-align:center" number="1"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        ยกเลิก
                    </button>
                    <button id="save-source-of-uncertainty" type="button" class="btn btn-primary">
                        ตกลง
                    </button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li><strong>Symbol</strong> สัญลักษณ์ของแหล่งความไม่แน่นอนย่อย</li>
                        <li><strong>Source</strong> รายละเอียดของแหล่งความไม่แน่นอน</li>
                        <li><strong>Value</strong> ขนาดของแหล่งความไม่แน่นอนที่ประมาณได้</li>
                        <li>
                            <strong>Divisor</strong> ขนาดของตัวหารเพื่อที่จะทำให้ได้ Standard Uncertainty ของแต่ละตัว
                            <ul>
                                <li>√2 = 1.414</li>
                                <li>√3 = 1.732</li>
                                <li>2√3 = 3.464</li>
                                <li>√6 = 2.449</li>
                            </ul>
                        </li>
                        <li><strong>Ci</strong> ค่า Sensitivity Coefficient</li>
                        <li><strong>Vi or Veff</strong> ค่า Degree of Freedom ของแต่ละตัว</li>
                    </ul>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- Modal -->
<div class="modal fade" id="AddPmListFormBox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="add-pm-list-form" method="post">
                <input type="hidden" name="save_type" value="add_pm_list"/>
                <input type="hidden" name="table_id" id="add_pm_list_table_id" value=""/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title">เพิ่มรายการ</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <textarea name="pm_list_names" id="pm_list_names" class="form-control" rows="10"
                                      required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        ยกเลิก
                    </button>
                    <button id="add-pm-list" type="button" class="btn btn-primary">เพิ่ม</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script src="plugin/inspection/js/app.config.js"></script>
<script src="plugin/inspection/jquery.ui.touch-punch.min.js"></script>
<script src="plugin/inspection/bootstrap.min.js"></script>
<script src="plugin/inspection/SmartNotification.min.js"></script>
<script src="plugin/inspection/jarvis.widget.min.js"></script>
<script src="plugin/inspection/jquery.easy-pie-chart.min.js"></script>
<script src="plugin/inspection/jquery.sparkline.min.js"></script>

<script src="plugin/inspection/jquery.maskedinput.min.js"></script>
<script src="plugin/inspection/select2.min.js"></script>
<script src="plugin/inspection/bootstrap-slider.min.js"></script>
<script src="plugin/inspection/jquery.mb.browser.min.js"></script>
<script src="plugin/inspection/fastclick.min.js"></script>
<script src="plugin/inspection/app.min.js"></script>
<script src="plugin/inspection/voicecommand.min.js"></script>
<script src="plugin/inspection/smart.chat.ui.min.js"></script>
<script src="plugin/inspection/smart.chat.manager.min.js"></script>
<script src="plugin/inspection/jquery.validate.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        //pageSetUp();
    })
</script>
<!-- PAGE RELATED PLUGIN(S) -->
<script src="plugin/inspection/jquery-form.min.js"></script>
<script src="plugin/inspection/form.js"></script>
<script>
    jQuery(document).ready(function () {

        jQuery('#save-source-of-uncertainty').click(function () {
            SubmitSource();
        });
        jQuery('#add-pm-list').click(function () {
            AddPmList();
        });
        jQuery('.checked-source-active').change(function () {
            CheckSourceRequiredTag(this);
        });
        jQuery('.input-upper-case').keyup(function () {
            this.value = this.value.toUpperCase();
        });
        jQuery('.input-upper-first').keyup(function () {
            this.value = this.value.toLowerCase().replace(/\b[a-z]/g, function (letter) {
                return letter.toUpperCase();
            });
        });
        jQuery('.set-tole-amount').keyup(function () {
            set_tolerance_amount(jQuery(this).attr('tbid'));
        });
        //CheckHasTable();
        // PERFORM DATE AND DUE DATE
        jQuery('#performdate').datepicker({
            dateFormat: 'yy-mm-dd',
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onSelect: function (selectedDate) {
                jQuery('#duedate').datepicker('option', 'minDate', selectedDate);
            }
        });

        jQuery('#duedate').datepicker({
            dateFormat: 'yy-mm-dd',
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onSelect: function (selectedDate) {
                jQuery('#performdate').datepicker('option', 'maxDate', selectedDate);
            }
        });

        // Turn Off Autocomplete
        jQuery(document).on('focus', ':input', function () {
            jQuery(this).attr('autocomplete', 'off');
        });

        jQuery('#dialog_save_form_as').dialog({
            autoOpen: false,
            width: 400,
            resizable: false,
            modal: true,
            title: "Save Form As",
            buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Save",
                "class": "btn btn-success",
                click: function () {

                    /*if(SaveAsFormValidate()){
                     var InputFormName = jQuery("#input-form-name").val();
                     jQuery("#save-form-name").val(InputFormName);

                     var InputFormType = jQuery("#select-form-type").val();
                     jQuery("#save-form-type").val(InputFormType);*/

                    SavingForm();
                    /*}*/
                }
            }, {
                html: "<i class='fa fa-times'></i>&nbsp; Cancel",
                "class": "btn btn-default",
                click: function () {
                    jQuery(this).dialog("close");
                }
            }]
        });

        //PMListAutocomplete();
    })

    /*function PMListAutocomplete(){
     var PMLists = ["\"Base Supports","\"Circuit Breaker/Fuse","\"Controls/Switches","\"Door operation","\"Indicators/Displays","\"Labeling","\"Portable Power Supply (transport incubators only)","\"Safety valve","\"Shutoff valves","\"Test cycle","(Capnometers) ±0.4 vol% (±3 mm Hg) or ±10%","(thermistor thermometers) (Temp) ±0.3°C (0.5°F)","(thermistor thermometers) (Temp) ±0.5°C (0.8°F)",", Temp, Capnometers, tcpO2/CO2)","5","AC Plug","AC Plug/Receptacles","Accessories","Accessosies","Accuracy  ±2 vol% or 5%, whichever is greater","Accuracy of Speed Setting±10%","Accuracy on Volunteer (NIBP) ±10 mm Hg of nurse\'s measurement","Adjust","Air Leakage  ≤15 mm Hg/min","Air Leakage (NIBP) ≤15 mm Hg/min","Air-in-Line Detection 50 to 100 μL","Air-Temperature Alarms ≤39°C or mfr spec","Alarm Accuracy (IBP) ±2%","Alarm Accuracy ≤0.5°C","Alarm Delay ≤10 sec","Alarms","Alarms  ±2% oxygen at 21%, ±5% oxygen at 50%","Alarms (Capnometers)","Alarms (ECG)","Alarms (IBP)","Alarms (NIBP)","Alarms (SpO2)","Alarms (tcpO2/CO2)","Alarms (Temp)","Alarms/Interlocks","Alignment Features for Disposable Sets","Amplifier Cable Testing and Temperature Testing (Option).","Amplifier Gain Testing.","Amplitude Accuracy ±10%","APL Valve~1 to ≥30 cm H2O","Apnea Alarm Delay Time ±20%","Atrial-Ventricular Delay ±10% mfr spec","Audible Signals","Auditory Stim  Testing  (Option).","Automated Mode Analysis and Defibrillator Output per mfr/hosp policy","Automatic Controller Switching ±0.5°C or mfr spec","Backup System Data.","Ball Valve","Base Supports","Baseline Spectral Irradiance ≥4 μW/cm2/nm or mfr spec *(≥176 μW/cm2)","Bassinet/Mattress","Battery","Battery Test Feature","Battery/Charger","Bellows or Piston","Blankets","Bleed Valve","Blower","Bolts and nuts","Bolus Accuracy ≤5%","Brake","Breathing System (including filters)","Breathing System ≥30 cm H2O, 30 sec","Cable","Cables","Cables (ECG)","Cables (SpO2, IBP, Temp, Capnometers, tcpO2/CO2)","Calibration/Self-Test","Carbon Dioxide Absorber","Carbon Dioxide Concentration Accuracy","Carbon Dioxide Concentration Accuracy  ±0.4 vol% (±3 mm Hg) or within ±10%, whichever is greater, of the delivered concentration","Carbon Dioxide Display Accuracy(tcpO2/CO2) ±5 mm Hg or 10%","casters","Casters/Brakes","Casters/Wheels","Casters/Wheels/Brakes","Chassis Grounding Resistance≤0.5 Ω","Chassis Leakage Current  ≤500 µA","Chassis Leakage Current (<300 µA)","Chassis Leakage Current (For Installed Equipment) ≤10mA","Chassis Leakage Current (≤300 µA)","Chassis Leakage Current ≤ 500 µA","Chassis Leakage Current ≤300 µA","Chassis Leakage Current ≤500 μA","Chassis Leakage Current≤3,500 µA","Chassis Leakage Current≤500 µA","Chassis/Housin","Chassis/Housing","Chassis/Housing/Bassinet","Chassis/Housing/Frame","Check Frequency","Check output power","Check that all connector are at the correct place and the they are properly fastened.","Check that main switch is properly mounted.","Check that the fuses have the ratings specified.","Check that the LED display","Check the cable electrode","Check the electrical wiring for safety","Check tuning device","Check-Valve Leakage  ≤0.1 L/min","Circuit Breaker/Fuse","Clean","Clean the exterior and accessories","Coincidence Circuit","Compressor","Concentration Check±0.3% vapor or ±10% of the selected value, whichever is greater","Connectivity","Connectors","Continuity >10 Ω / Contact quality 5 Ω -150 Ω","Control Valve","Controls","Controls ±10%","Controls ±10% of control settings","controls/Knobs","Controls/Switches","Cuff Pressure Indicator Accuracy ±5% at 200 and 450 mm Hg","Cuffs","Cycle Time  mfr spec","Data Recording","Data Transfer to Data Management System","Demand-Mode Sensitivity 1-2 mV or mfr spec","Demand-Mode Sensitivity ≤0.5 mV or mfr spec","Direct Current Leakage ≤5 mV","Directional Valves","Dispersive Cable Continuity Monitor","Dispersive Electrode Contact Quality Monitor Resistance","Dispersive Electrode Contact Quality Monitor Resistance Continuity >10 Ω / Contact quality 5 Ω -150 Ω","Dispersive Electrode Grounding Resistance>20 MΩ for","Dispersive Electrode Grounding Resistance>20 MΩ for ground-referenced units","Dispersive Electrodes","Display output show","Display timer test Maximum  30  minute","Door operation","Dual-Bladder","ECG Features per Procedure 493","EEH Electrode","Elapsed-Time Meter/Timer ±2 min after 15 min","Electrode-to-Ground Leakage Current  ≤100 µA grounded;  ≤500µA ungrounded","Electrodes","Electrodes/Transducers","Elevation ±1 %","Emergency Stop","Empty Container","Empty Syringe","Energy after 50 Sec (Manual Mode) ≥85%","equipment","Exhaled Volume Monitor±15% test lung value; ±15% minute volume display","Fail-Safe Oxygen Valves","Fan","Fan/Compressor","Fan/Compressor or Turbine","Fasteners","Fiberoptic Cable Connector","Fiberoptic Pads/Cables","Filters","Filters (Capnometers)","Filters/Heat-Reflecting Mirrors","Fittings/Connectors","Fittings/Connectors (ECG, SpO2, IBP","Fittings/Connectors (NIBP)","Fittings/Connectors and Preventing Misconnection","Flow Accuracy ±0.5 L/min *or ±10%","Flow Accuracy ±10%","Flow Accuracy ±5%","Flow Accuracy±5%","Flow with Loss of an Input   High flow ≥30 L/min; low flow ≥15 L/min","Flow-Stop Mechanism(s)","Flow/Output","Flowmeters","Flowmeters ±10%","Fluid Flow Unit alarms; mfr flow spec, if indicated","Fluid Levels","Fluid Temperature 37°C to 42°C at highest flow setting used","Fluid Temperature Display ±1°C","Footswitch","Free-flow Prevention Mechanism(s)","Frequency Response 0.67 to 100 Hz in the auto report mode","Front End Testing.","Gain ±10%","Gas concentration alarms","Gas cylinders (and gauges and regulators)","Gas Cylinders, Gauges, and Regulators (for transport ventilators)","Gas Cylinders, Gauges, and Regulators (for transport ventilators)\"","Gas Cylinders, Gauges, and Regulators(for transport ventilators)","Gauge/Column","Ground Resistance","Ground Resistance ≤0.5 Ω","ground-referenced units","Grounding Resistance  ≤ 0.5 Ω","Grounding Resistance  ≤0.5 Ω","Grounding Resistance  ≤0.5Ω","Grounding Resistance (<0.5 Ω)","Grounding Resistance (≤0.5 Ω )","Grounding Resistance ≤0.5 Ω","Grounding Resistance ≤0.5 Ω.","Grounding Resistance ≤0.5Ω","Grounding Resistance≤0.5 Ω","Grounding Resistance≤0.5Ω","Halogenated Agent Concentration Accuracy(Capnometers) ±0.25 vol%","Heart Rate Alarm","Heart Rate ±10%","Heart Rate ±5%","Heater","Heater(s)","Heating Element","High-Pressure Leaks negligible pressure drop >30 sec","High-Temperature Alarm  43°C (110°F) solutions / 54°C (130°F) blankets","High-Temperature Alarms  ±1°C","High-Temperature Protection 53°C ±3°C (127°F ±5°F)","High-Temperature Protection ≤43°C","High-Temperature Protection ≤43°C or manufacturer specification","Hood Air Temperature ±1°C","Hood Thermometer","Humidifier","Humidifiers","Indicators/Display","Indicators/Displays","Indicators/Displays (Capnometers)","Indicators/Displays (ECG)","Indicators/Displays (NIBP, IBP, tcpO2/CO2)","Indicators/Displays (SpO2)","Indicators/Displays (Temp)","Inflow","Inflow ferocity >100 fpm","Inflow Velocity > 100 fpm","Infusion Complete","Input Pressure Alarms  Mfr Spec","Integral Output Tester","Interlead Leakage Current (Isolated Lead)  ≤10 μA grounded; ≤50 μA open ground","Interlead Leakage Current (Isolated Lead) (ECG) ≤10 μA grounded; ≤50 μA open ground","Interlead Leakage Current (Isolated Lead) ≤10 μA (grounded); ≤50 μA (ungrounded)","Interlead Leakage Current (Isolated Lead) ≤10 µA grounded;≤50 µA open ground","Interlocks","Intermediate Pressure Leaks no leakage","Intermittent Cycling 20 sec","Intermittent Operation","Internal Paddle Energy Limit ≤50 J","Intrauterine Pressure (IUP) Transducer ±2 mm","Investigate and Save New Hardware Configuration.","Isolate Box  Vac","IV Pole Mount","Keyboard/Switch Testing.","Knob","Labeling","Labeling   Accessories","Lead -to-Ground Leakage Current  ≤100µA grounded; ≤500µA ungrounded","Lead Input Isolation  ≤50 μA","Lead Input Isolation (ECG) ≤50 μA","Lead Input Isolation ≤50 μA (grounded)","Lead Input Isolation ≤50 µA grounded","Lead Off Detection","Lead-to-Ground Leakage Current  ≤100 µA grounded; ≤500 µA ungrounded","Lead-to-Ground Leakage Current (Isolated Lead)  ≤10 µA grounded; ≤50 µA open ground","Lead-to-Ground Leakage Current (Isolated Lead) ≤10 μA (grounded); ≤50 μA (ungrounded)","Lead-to-Ground Leakage Current (Isolated Lead) ≤10 µA grounded; ≤50 µA open ground","Lead-to-Ground Leakage Current (Isolated Lead)(ECG) ≤10 μA grounded; ≤50 μA open  round","Leak Check","Leak Check (Capnometers)","Leaks   ≤6 mL","Line Cord","LKDSJFSDJFLSDKFJLSKDSJ","Lockout Interval","Low Temperature Protection ±1°C of mfr spec","Low-Flow Bleeds","Low-Pressure Leaks<30 mL/min at 30 cm H2O","Low-Temperature Alarms  ±1°C","Lubricate","Manual Mode Defibrillator Output ±4 J low; ±15% high","Manual operation","Manuals","Mattress","Maximum Cuff Pressure ≤550 mm Hg or mfr spec","Maximum Flow   ≥30 L/min at 60% O2","Maximum Free Flow  20 L/min","Maximum Free Flow ≥20 L/min","Maximum Free Flow ≥85 L/min","Maximum Load (for bedside and ceiling- mounted adult/pediatric units)Max rated load for 5 min","Maximum Pressure (NIBP) ≤330 mm Hg","Maximum Pressure ±1 psi mfr spec","Maximum Pressure ±1 psi of manufacturer specification","Maximum Pressure ±1 psi of mfr spec","Maximum Vacuum  >40 mm Hg","Maximum Vacuum > 200 mm Hg","Maximum Vacuum Low-volume/thoracic >40 to 120 mm Hg; surgical/tracheal ≥400 mm Hg.","Maximum Vacuum Low/High: >40/>100 mm Hg","Maximum Vacuum ≥700 mm Hg","Minimum Oxygen Flow and Percentage 100–250 mL/min or mfr\'s specs","Misloaded Set/Syringe/Vial","Misloaded Syringe","Modes and Settings±10% or mfr spec","Monitored Parameters and Alarms displays, ±10% simulated values ; alarms, ±10%  settings","Monitored Parameters and Alarms displays, ±10% simulated values ; alarms, ±10% settings","Monitored Parameters and Alarms displays,±10% simulated values ; alarms, ±10% settings","Monitors and Alarms±10% or mfr spec","Motor","Motor Back","Motor Hi-Lo","Motor Knee","Motor Trendelenburg","Motor/Fan","Motor/Pump","Motor/Pump/Fan/Compressor","Motor/Rotor/Pump","Mount","Mount/Fasteners","Mounts","Mounts/Fasteners","Multiple Discharge Output Energy and Charge Time ±15%,≤10 sec","Network/Wireless Interfaces","Nitrous Oxide Concentration Accuracy (Capnometers) ±5 vol% or ±10%","Nurse Call (verify only if this function is used)","Occlusion","Occlusion alarm","Open Door/Misloaded Infusion Set","Open Electrode Indicator 1,000 to 2,000 Ω","Operational Modes","Operational Sound","or manufacturer specification","Other alarms","Outlet Pressure  mfr spec or 5-7 psig","Output Temperature  ±1°C","Overflow Protection","Oxygen Concentration  ±3%","Oxygen Concentration  ≥93%","Oxygen Concentration Accuracy  ±2 vol% or 5% of the expectedvalue, whichever is greater","Oxygen Concentration Accuracy (Capnometers)","Oxygen Display Accuracy (tcpO2/CO2) ±5 mm Hg or 10%","Oxygen Flush Valve 35–75 L/min; O2 flowmeter drop <1 L/min at 2 L/min; return to 2 L/min <2 sec","Oxygen Tank","Pacemaker Demand-Mode Activation/Inhibition","Pacing Amplitude ±10%","Pacing Pulse Width ±10%","Pacing Rate ±5%","Paddles/Electrodes","Paper Speed  ±2%","Paper Speed ±2%","Paper Transport","Patient Probe Leakage Current (SpO2,Temp) ≤100 μA, ≤500 μA under single fault conditions","Patient Probe Leakage Current (tcpO2/CO2)≤100 μA, ≤500 μA under single fault conditions","Patient Temperature Display and Probe ±1°C","Pediatric Mode Output Energy ≤50 J","PEEP Valve system pressures <1 cm H2O and ±1.5 cm H2O","Plug","Portable Power Supply (transport incubators only) ≤10% voltage decrease","Power Continuity","Power Sources/Internal Battery Charger","Pressure Accuracy (IBP) ±2%  *or mfr spec","Pressure Accuracy ±3 mm Hg","Pressure Display ±10%","Pressure Drop  ≤5 cm H2O for bubble-type humidifiers; less for other units","Pressure Leakage ≤15 mmHg/min","Pressure Modes (IBP)","Pressure Regulator","Pressure Stability 400 ±10 mm Hg >15 min","Pressure Verification  ± 10%","Pressure-Relief Mechanism","Print Quality","Probes","Pulse Width 0.5 to 2.0 msec, typical; ±10% mfr spec, user adjustable","Pulse/Heart Rate ±5 %","Pump","Pump (Capnometers)","QRS Sensitivity ≥0.15 mV","Rate Accuracy ±5%","Rate Alarm (ECG)","Rate Alarm Accuracy ≤20 bpm","Rate Alarm ±5% or 5 bpm at 40 and 120 bpm","Rate Calibration (ECG) ±5%","Rate Calibration ±5% or 5 bpm at 60 bpm and 120 bpm","Rate of Vacuum Rise  30 mm Hg in 4 sec","Rate of Vacuum Rise 150 mm Hg in 5 sec","Rate of Vacuum Rise 30 mm Hg in 30 sec","Ratemeter Accuracy ±5%","Receptacles","Recorder","Recorder (ECG)","Recorder / Printer","Refractory Period mfr spec","Remote control Handheld","Remote control Nurse","Replace","Respiration Rate ±1 breath/min","Response Time  Mfr spec/≤20 sec","RF Output Current/Power ±5 W or ±20%","RF Output Isolation≤150 mA or≤4.5 W","Run Keypoint Test Software.","Safety Thermostat ≤40°C","Safety valve","Sampling Flow Accuracy (Capnometers) mfr spec","Scavenging System Max suction -0.5–0 cm H2O; ≤10 L/min O2, near ambient; APL occluded <10 cm H2O","Self-Test","Self-Test Verification","Sensitivity ≤0.3 Ω at max varies w/setting, no breaths at 0 bpm","Sensor/transducer","Sensors/Sampling Lines","Sensors/Sampling Lines (Capnometers)","Shutoff valves","Side rails","Site Glass, O-Rings, Keyed Filler Mechanism","Skin-Temperature Alarms ±0.5°C","Sleep Senser","Slow exhaust valve  20-40 min","Speed ±5 %","Static Pressure Accuracy (NIBP) ±3 mm Hg*OscillometricPressure Accuracy (NIBP) ±8 mm Hg","Static Pressure Accuracy ±3 mmHg or *Oscillometric Pressure ±8 mmHg","Stimulator Testing.","Stimulator Voltage or Current ±10% or mfr spec","Strain Reliefs","Synchronized Cardioverter","Synchronized Cardioverter Operation ≤60 msec after R-wave","Temperature Accuracy *±1°C","Temperature Accuracy *±1°C Humidity Accuracy *±10%RH","Temperature Accuracy and Control  ±1.0 °C warm up; ± 0.5°C steady state","Temperature Accuracy and Control  ±1°C (2°F)","Temperature Accuracy ±0.3°C","Temperature Accuracy ±0.5°C","Temperature Accuracy ±1°C (2°F) displayed value","Temperature Accuracy, Predictive Mode","Temperature Accuracy, Predictive Mode ±0.3°C","Temperature Accuracy, Steady-State Mode","Temperature Accuracy, Steady-State Mode ±0.3°C","Temperature Accuracy±3°C","Temperature Alarms (Temp) ±0.6°C (1.0°F)","Temperature Alarms ±0.6°C","Temperature Control (tcpO2/CO2) ±0.1°C","Temperature Controller Performance  ±1.0°C warm up; ± 0.5°C steady state","Temperature Display Accuracy (tcpO2/CO2) ±0.3°C","Temperature Sensor (water bath units)","Terminals","Test cycle","Test safety swich","Time/Date Settings","Timer 1 min ±10 sec","Timer Accuracy±10%","Touch Current","Touch Current  ≤500 µA","Touch Current  ≤500 µA [ungrounded] patient-care","Touch Current  ≤500 µA [ungrounded] patient-care equipment","Touch Current  ≤500µA (ungrounded) patient-care Equipment","Touch Current ≤500 µA (ungrounded) patient-care equipment","Touch Current ≤500µA","Traction Force ±10%","Transducers (non-oscillometric units)","Transducers/Electrodes","Transducers/Temperature Sensor","Tubes/Hoses","Tubes/Hoses/Bulb","Tubes/Hoses/Bulbs (NIBP)","Ultrasound Power ±20%","Update Anti-Virus","User Calibration","User Calibration (IBP)","User Calibration (NIBP, Capnometers, tcpO2/CO2)","User Calibration (Temp)","User Calibration/Self Test","User Calibration/Self-Test","Vacuum Gauge Accuracy  ±10%","Vacuum Gauge Accuracy ±10%","Vacuum Gauge Accuracy ±5% FSO(Full Scale output)","Vacuum leak test  ≤1 mm Hg/min","Vacuum/Pressure Gauge Accuracy  ±10%","Vaporizer Back-Pressure Check Valve","Verify Operation of Computer,Keyboard,Monitor and Printer.","Visual Stim Testing  (Option).","Waveform Analysis mfr spec","Weight (Mass) Accuracy for Electronic Scales ±1 %","Weight (Mass) Accuracy for Mechanical Scales ±1 %","Zero Calibration/Electronic Scales Display reads zero","Zero Calibration/Mechanical Scales","Zero Pressure Setting","กล้องบันทึกภาพการเคลื่อนไหว (Camara Record)","การเชื่อมต่อระบบ Server Record","ชุดต่อสายสัญญาณ (Head Box)","ตัวกระตุ้นด้วยเเสง (Electrical Photic)","ตัวขยายสัญญาณ (AMPLIFIER)","ตัวควบคุมหลัก (MAIN CONTROL SYSTEM)","ตัววัดออกซิเจนในเลือด (Pluse Oximeter)","สายต่อต่าง ๆ ในระบบ (ALL CABLE AMP. UNITE)","โปรแกรมตรวจการนอนหลับ (Ultrasom EEG)","โปรแกรมตรวจคลื่นไฟฟ้าสมอง (Aliance EEG)","โปรแกรมรายงานผลการตรวจ (Report generator)","≤10% voltage decrease \"","≤2 vol% or ≤5% expected value",];
     jQuery(".pm-list-autocomplete").autocomplete({
     source: PMLists,
     minLength: 1
     });
     }*/

    function PMListSortable(TbID) {
        jQuery("#" + TbID + "-sortable").sortable();
    }

    function ShowHideTable(TbID) {

        if (jQuery("#" + TbID).css("display") == "none") {
            jQuery('#' + TbID).css({display: 'block'});
            jQuery('#' + TbID + '-collapse').html('<i class="fa fa-minus"></i>');
        } else {
            jQuery('#' + TbID).css({display: 'none'});
            jQuery('#' + TbID + '-collapse').html('<i class="fa fa-plus"></i>');
        }

    }

    jQuery("#parameters-sortable").sortable({cursor: "move"});

</script>