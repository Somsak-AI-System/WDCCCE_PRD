<?php
global $focus, $adb, $root_directory;

$crmid = $focus->column_fields['questionnairetemplateid'];

$sql = "SELECT aicrm_attachments.* FROM aicrm_seattachmentsrel
INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid=aicrm_seattachmentsrel.attachmentsid
INNER JOIN aicrm_attachments ON aicrm_attachments.attachmentsid = aicrm_seattachmentsrel.attachmentsid
WHERE aicrm_crmentity.setype='image_header'
AND aicrm_seattachmentsrel.crmid='" . $crmid . "'";
$imgHeader = $adb->pquery($sql, []);
$imgHeaderID = $adb->query_result($imgHeader, 0, 'attachmentsid');
$imgHeaderName = $adb->query_result($imgHeader, 0, 'name');
$imgHeaderPath = $adb->query_result($imgHeader, 0, 'path');

$sql = "SELECT aicrm_attachments.* FROM aicrm_seattachmentsrel
INNER JOIN aicrm_crmentity ON aicrm_crmentity.crmid=aicrm_seattachmentsrel.attachmentsid
INNER JOIN aicrm_attachments ON aicrm_attachments.attachmentsid = aicrm_seattachmentsrel.attachmentsid
WHERE aicrm_crmentity.setype='image_footer'
AND aicrm_seattachmentsrel.crmid='" . $crmid . "'";
$imgFooter = $adb->pquery($sql, []);
$imgFooterID = $adb->query_result($imgFooter, 0, 'attachmentsid');
$imgFooterName = $adb->query_result($imgFooter, 0, 'name');
$imgFooterPath = $adb->query_result($imgFooter, 0, 'path');
?>      
        
        

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="modules/Questionnaireanswer/survey/js/jquery.js"></script>
        <script src="modules/Questionnaireanswer/survey/js/survey.jquery.js"></script>
        <link rel="stylesheet" href="modules/Questionnaireanswer/survey/css/bootstrap.min.css" />
        <link rel="stylesheet" href="modules/Questionnaireanswer/survey/css/survey.css" />

        <!-- <script type="text/javascript" src="asset/js/jquery-ui-1.10.4.custom.min.js"></script>
        <script type="text/javascript" src="asset/js/jquery-1.10.2.min.js"></script> -->
        <script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
        <link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css">

        <div class="container">
                <div class="row">
                        <div class="col-12">
                                <?php if($imgHeaderID != ''){ ?>
                                        <img style="width:100%" src="<?php echo $imgHeaderPath.$imgHeaderID.'_'.$imgHeaderName; ?>" />
                                <?php } ?>
                        </div>
                </div>

                <div id="surveyElement"></div>
                <div id="surveyResult"></div>

                <div class="row">
                        <div class="col-12">
                                <?php if($imgFooterID != ''){ ?>
                                        <img style="width:100%" src="<?php echo $imgFooterPath.$imgFooterID.'_'.$imgFooterName; ?>" />
                                <?php } ?>
                        </div>
                </div>
        </div>

        <script src="modules/Questionnaireanswer/survey/detail.js"></script>


