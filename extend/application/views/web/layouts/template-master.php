<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo site_url('assets/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo site_url('assets/bootstrap3/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo site_url('assets/jquery-ui-1.12.1/jquery-ui.min.css'); ?>" rel="stylesheet">

    <!-- Kendo UI Demo -->
    <link href="<?php echo site_url('assets/kendoui/styles/kendo.common.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo site_url('assets/kendoui/styles/kendo.rtl.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo site_url('assets/kendoui/styles/kendo.bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo site_url('assets/kendoui/styles/kendo.bootstrap.mobile.min.css'); ?>" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="<?php echo site_url('assets/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo site_url('assets/fontawesome-free/css/v4-shims.min.css'); ?>" rel="stylesheet" type="text/css">

    <script src="<?php echo site_url('assets/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/jquery-ui-1.12.1/jquery-ui.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/bootstrap3/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/lodash.js'); ?>"></script>

    <script src="<?php echo site_url('assets/kendoui/js/jszip.min.js'); ?>"></script>
    <script src="<?php echo site_url('assets/kendoui/js/kendo.all.min.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo site_url('assets/fusioncharts/fusioncharts.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo site_url('assets/fusioncharts/fusioncharts.charts.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo site_url('assets/fusioncharts/themes/fusioncharts.theme.fint.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo site_url('assets/fusioncharts/themes/fusioncharts.theme.ocean.js'); ?>"></script>

    <link href="<?php echo site_url('assets/smartwizard/css/smart_wizard.css'); ?>" rel="stylesheet">
    <link href="<?php echo site_url('assets/smartwizard/css/smart_wizard_theme_arrows.css'); ?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo site_url('assets/smartwizard/js/jquery.smartWizard.js'); ?>"></script>

    <link href="<?php echo site_url('assets/gridstack/gridstack.min.css'); ?>" rel="stylesheet" type="text/css">
    <script src="<?php echo site_url('assets/gridstack/gridstack.js'); ?>"></script>
    <script src="<?php echo site_url('assets/gridstack/gridstack.jQueryUI.min.js'); ?>"></script>

    <link href="<?php echo site_url('assets/select2/css/select2.min.css'); ?>" rel="stylesheet" type="text/css">
    <script src="<?php echo site_url('assets/select2/js/select2.min.js'); ?>"></script>

    <script src="<?php echo site_url('assets/loadingoverlay.js'); ?>"></script>
    <script>
        var site_url = function(path){
            return '<?php echo site_url(); ?>'+path;
        }

        $(function(){
            $.fn.formValidator = function(){
                var form_valid = $(this).kendoValidator({
                    rules: {
                        validCombobox: function (input) {
                            if($(input).hasClass("kendo-combobox-validate"))
                            {
                                var div_id = $(input).attr('id');
                                if(div_id != undefined){ //console.log(div_id)
                                    var combobox = $('#'+div_id).data("kendoComboBox");
                                    if(combobox != undefined){
                                        if(input.val()!= "") combobox.value(input.val());
                                        var value = combobox.value();
                                        //console.log(value);
                                        if ((value!= "") && combobox.selectedIndex == -1)
                                        {
                                            return false;
                                        }
                                    }
                                }
                            }
                            return true;
                        },
                        verifyPasswords: function(input){
                            if (input.is("[name=cf_password]")) {
                                if(input.val() != $('#new_password').val()){
                                    return false;
                                }
                            }
                            return true;
                        },
                        validDatePicker: function(input){
                            if($(input).hasClass("kendo-datepicker") || $(input).hasClass("kendo-datepicker-monthyear")){
                                var value = $(input).val();
                                var pattern =/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;

                                if(value!='')
                                    return pattern.test(value);
                            }
                            return true;
                        },
                        validDateTimePicker: function(input){
                            if($(input).hasClass("kendo-datetimepicker")){
                                var value = $(input).val();
                                var pattern =/^([0-9]{2})\/([0-9]{2})\/([0-9]{4}) (20|21|22|23|[0-1]?\d{1}):([0-5]?\d{1})$/;

                                if(value!='')
                                    return pattern.test(value);
                            }
                            return true;
                        },
                        validDateComplaintNo: function (input) {
                            if (input.is("[name=complaint_no__52__1]")){
                                var value = $(input).val();
                                var pattern =/^[a-zA-Z0-9!@#$%\^&*)(+=._-]+\/[0-9]*$/;

                                if(value!='')
                                    return pattern.test(value);
                            }
                            return true;
                        }
                    },
                    messages: {
                        validCombobox: "Select invalid value",
                        verifyPasswords: "Passwords do not match!",
                        validDatePicker: "Please valid date format",
                        validDateTimePicker: "Please valid datetime format",
                        validDateComplaintNo: "Please insert valid format: a-z A-Z 0-9/0-9"
                        // dateValidation: "Invalid date message"
                    }
                }).data("kendoValidator");
                return form_valid;
            }
        })
    </script>

</head>

<body id="page-top">

<div class="container-fluid">
    <?php echo $template['body']; ?>
</div>

</body>

</html>
