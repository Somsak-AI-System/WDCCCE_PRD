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
        document.write('<script src="pluzgin/inspection/jquery-ui-1.10.3.min.js"><\/script>');
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
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 1 Single Input
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('2')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 2 Checkbox
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('3')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 3 Radio
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('4')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 4 Dropdown
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('5')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 5 Text Area
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('6')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 6 Rating
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('7')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 7 Single Check Box
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('8')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 8 Check Box & sub row
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('9')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 9
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('10')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 10
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('11')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 11
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('12')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 12
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('13')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 13
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('14')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 14
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('15')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 15
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('16')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 16
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('17')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 17
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" onclick="addTemplate('18')">
                                                <i class="fa fa-plus-square txt-color-green"></i> Add Template 18
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

    function callAllTemplate(i) {
        $.post(`plugin/inspection/template${i}.php`, function(html){
            $('#parameters-sortable').append(html)
        });
    }

    var delay_loop = function (list) {
        (function loop(i) {
            setTimeout(function () {
                console.log(i);
                callAllTemplate(i);
                i += 1;
                if (i) {
                    if(i > 18) return false;
                    loop(i);
                }
            }, 1); // delay 1 sec during each iterate
        }(1));
    };

    var sample_list = [10, 9, 8, 7, 6, 5, 4, 3, 2, 1];
    delay_loop(sample_list);


    function addTemplate(templateID){
        $.post(`plugin/inspection/template${templateID}.php`, function(html){
            $('#parameters-sortable').append(html)
        })
    }

    function addRowPM(TbID){
        //ptest
        var uniqID = RowsID();
        jQuery.get( "plugin/inspection/load.php?data=form-tr-pm&tbid="+ TbID + "&uniqid=" + uniqID , function( data ) {
            jQuery('#'+TbID+'-sortable').append(data);

            var number = jQuery('.'+TbID+'_rows').length;
            var newtrid = jQuery('#'+TbID+' tr:last').attr('id');
            jQuery('#'+newtrid+'_no').val(number);

            // PMListAutocomplete();
        });
    }


    function RowsID(){
        var CountRowsID = parseInt(jQuery("#CountRowsID").val());
        jQuery("#CountRowsID").val(CountRowsID+1);
        return CountRowsID;
    }

    function PMSelectAllPass(TbID){
        var RowID;
        jQuery('.'+TbID+'-pass').each(function( index ){
            RowID = jQuery( this ).attr( "name" );
            jQuery( '#'+RowID+'_pass' ).html( '<input class="'+TbID+'-pass click-reset" name="'+RowID+'" value="Pass" type="radio" checked="checked"><i></i>' );
            jQuery( '#'+RowID+'_fail' ).html( '<input class="'+TbID+'-fail click-reset" name="'+RowID+'" value="Fail" type="radio"><i></i>' );
            jQuery( '#'+RowID+'_none' ).html( '<input class="'+TbID+'-none click-reset" name="'+RowID+'" value="None" type="radio"><i></i>' );
        });
        return;
    }

    function PMSelectAllFail(TbID){
        var RowID;
        jQuery('.'+TbID+'-fail').each(function( index ){
            RowID = jQuery( this ).attr( "name" );
            jQuery( '#'+RowID+'_pass' ).html( '<input class="'+TbID+'-pass click-reset" name="'+RowID+'" value="Pass" type="radio"><i></i>' );
            jQuery( '#'+RowID+'_fail' ).html( '<input class="'+TbID+'-fail click-reset" name="'+RowID+'" value="Fail" type="radio" checked="checked"><i></i>' );
            jQuery( '#'+RowID+'_none' ).html( '<input class="'+TbID+'-none click-reset" name="'+RowID+'" value="None" type="radio"><i></i>' );
        });
        return;
    }

    function PMSelectAllNone(TbID){
        var RowID;
        jQuery('.'+TbID+'-none').each(function( index ){
            RowID = jQuery( this ).attr( "name" );
            jQuery( '#'+RowID+'_pass' ).html( '<input class="'+TbID+'-pass click-reset" name="'+RowID+'" value="Pass" type="radio"><i></i>' );
            jQuery( '#'+RowID+'_fail' ).html( '<input class="'+TbID+'-fail click-reset" name="'+RowID+'" value="Fail" type="radio"><i></i>' );
            jQuery( '#'+RowID+'_none' ).html( '<input class="'+TbID+'-none click-reset" name="'+RowID+'" value="None" type="radio" checked="checked"><i></i>' );
        });
        return;
    }

    function PMListSortable(TbID){
        jQuery( "#"+TbID+"-sortable" ).sortable();
    }

    function removeFieldSet(obj){
        $(obj).parents('fieldset').remove();
    }

    // function removeRow(obj){
    //     var tempID = $(obj).parents('table').attr('id');
    //     $(obj).parents('tr.tr-form').remove();
    //     resetNumRow(tempID)
    // }

    function removeRow(trId, TbID){
        if(jQuery('.tr-form-'+TbID).length > 1){
            if(confirm('Confirm Remove Row')){
                jQuery('#'+trId).remove();

                var number = 0;
                jQuery(".tr-form-"+TbID).each(function( index ){
                    number++;
                    var trid = jQuery(this).attr('id');
                    jQuery('#'+trid+'_no').val(number);
                });
            }
        }
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