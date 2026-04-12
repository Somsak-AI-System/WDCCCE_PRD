<?php
global $data_template;
//echo "<pre>"; print_r($data_template); echo "</pre>"; exit;

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
                            <span class="widget-icon">
                                <i class="fa fa-edit" style="margin-top: 7px; font-size: 20px;"></i>
                            </span>
                            <h2>DATA RECORD FORM : <strong id="show-form-name"></strong>
                                <span id="last-update-form" class="last-update-form"></span>
                            </h2>
                            <div class="widget-toolbar">
                                <div class="btn-group">
                                    <button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown" type="button">ตั้งค่า <i class="fa fa-caret-down"></i></button>
                                    <ul class="dropdown-menu pull-right js-status-update">
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('1')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('2')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 2
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </header>

                        <div>
                            <div class="widget-body no-padding" style="min-height:50px;">
                                <div class="smart-form" id="design-form" name="design-form">
                                    <div id="parameters-sortable">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
</div>


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

<script>
    function addTemplate(templateID){
        $.post(`plugin/inspection/template${templateID}.php`, function(html){
            $('#parameters-sortable').append(html)
        })
    }

    function addRow(tempID){
        var tr = `<tr class="tr-form">
                    <td>
                        <label class="input">
                            <input name="input0" type="text" class="pm-left pm-center pm-list-autocomplete" value="1" readonly>
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="input1" type="text" class="pm-left pm-list-autocomplete" value="">
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="input2" type="text" class="pm-left pm-list-autocomplete" value="">
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="input3" type="text" class="pm-left pm-list-autocomplete" value="">
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="input4" type="text" class="pm-left pm-list-autocomplete" value="">
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="input5" type="text" class="pm-left pm-list-autocomplete" value="">
                        </label>
                    </td>
                    <td style="text-align:center; width:80px;">
                        <button href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fa fa-times"></i></button>
                        <span class="btn btn-sm btn-default" style="cursor:move"><i class="fa fa-sort"></i></span>
                    </td>
                </tr>`;

        $(`tbody#${tempID}-sortable`).append(tr);
        resetNumRow(tempID)
        sortable(tempID)
    }

    function removeFieldSet(obj){
        $(obj).parents('fieldset').remove();
    }

    function removeRow(obj){
        var tempID = $(obj).parents('table').attr('id');
        $(obj).parents('tr.tr-form').remove();
        resetNumRow(tempID)
    }

    function resetNumRow(tempID){
        $(`#${tempID}-sortable tr.tr-form`).each(function( i, e ){
            var numRow = i + 1;
            var td = $(this).find('td:first');
            $(td).find('input').val(numRow)
        });
    }

    $('#parameters-sortable').sortable();
    function sortable(tempID) {
        $(`#${tempID}-sortable`).sortable({
            update: function(){
                resetNumRow(tempID)
            }
        });
    }
</script>

</body>