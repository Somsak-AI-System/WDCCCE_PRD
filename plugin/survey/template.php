<?php
global $focus, $adb;

$sql = "SELECT * FROM aicrm_field WHERE block='192'";
$result = $adb->pquery($sql, []);
$rows = $adb->num_rows($result);

$crmid = isset($focus->id) ? $focus->id:'';
$fields = [];
if($crmid != ''){
    $sql = "SELECT * FROM survey_choice WHERE crmid='".$crmid."' ORDER BY sequence";
    $choice = $adb->pquery($sql, []);
    $choiceRows = $adb->num_rows($choice);
}
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" type="text/css" media="screen" href="plugin/survey/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="plugin/survey/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="plugin/survey/css/smartadmin-production-plugins.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="plugin/survey/css/smartadmin-production.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="plugin/survey/css/nhealth-skins.css">
<link rel="stylesheet" type="text/css" media="screen" href="plugin/survey/css/smartadmin-rtl.min.css">

<script src="plugin/survey/js/jquery-3.6.0.min.js"></script>
<script src="plugin/survey/jquery-ui-1.12.1/jquery-ui.min.js"></script>

<style>
/* Style The Dropdown Button */
.dropbtn {
  background-color: #EDEDED;
  color: #000;
  padding: 5px;
  font-size: 12px;
  border: 1px solid #DDD;
  border-radius: 5px;
  cursor: pointer;
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
  display: none;
  position: absolute;
  right: 0px;
  left: auto;
  background-color: #f9f9f9;
  min-width: 150px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: black;
  padding: 5px 10px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #f1f1f1}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
  display: block;
}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {
  /* background-color: #3e8e41; */
}

.row-template {
    padding-top: 10px;
    padding-bottom: 20px;
    padding-left: 5px;
    padding-right: 5px;
}

.row-template:hover {
    background: #EDEDED;
    cursor: move;
}

.row-title {
    display: flex; 
    align-items: stretch;
    margin-top: 10px;
    margin-bottom: 5px;
}

.text-input {
    width: 100%;
    border: none!important;
    border-bottom: 1px dotted #CCCCCC!important;
    font-size: 16;
    font-style: italic;
    font-weight: bold;
    color: #0000FF!important;
}

.myPlaceholder{
    background-color: #EDEDED;
    height:80px;
    margin:10px;
}
</style>

<body>
<div class="row">
    <div class="col-12">
        <div class="panel panel-default" style="margin-top:10px; font-size: 14px;">
            <div class="panel-heading">
                 <div style="display:flex; align-items:stretch">
                    <div style="flex:1"><i class="fa fa-edit"></i> DATA RECORD FORM:</div>
                    <div>
                        <div class="dropdown">
                            <button type="button" class="dropbtn">Action <i class="fa fa-sort-down"></i></button>
                            <div class="dropdown-content">
                                <?php for($i=0; $i<$rows; $i++){ 
                                    $fieldLabel = $adb->query_result($result, $i, "fieldlabel");
                                    $fieldID = $adb->query_result($result, $i, "fieldid");
                                    $fieldName = $adb->query_result($result, $i, "fieldname");
                                    $uitype = $adb->query_result($result, $i, "uitype");
                                ?>
                                <a href="javascript:void(0);" onclick="$.addRow('<?php echo $uitype; ?>', '<?php echo $fieldID; ?>')"><i class="fa fa-plus-square" style="margin-right:10px"></i> <?php echo $fieldLabel; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>
            <div class="panel-body">
                <div id="item-list">
                    <?php if($choiceRows > 0){
                        $html = '';
                        for($i=0; $i<$choiceRows; $i++){ 
                            $fields[] = $adb->query_result($choice, $i, "field_id");
                            $choiceType = $adb->query_result($choice, $i, "choice_type");
                            switch($choiceType){
                                case 'text':
                                    $html .= '<div class="row row-template">
                                        <div class="col-12">
                                            <div class="row-title">
                                                <div style="flex:1">
                                                    <input type="hidden" name="fields[]" value="'.$adb->query_result($choice, $i, "field_id").'">
                                                    <input type="hidden" name="field_'.$adb->query_result($choice, $i, "field_id").'_choiceid" value="'.$adb->query_result($choice, $i, "choiceid").'">
                                                    <input type="hidden" name="field_'.$adb->query_result($choice, $i, "field_id").'_fieldname" value="'.$adb->query_result($choice, $i, "field_name").'">
                                                    <input type="hidden" name="field_'.$adb->query_result($choice, $i, "field_id").'_type" value="text">
                                                    <input type="text" class="text-input" name="field_'.$adb->query_result($choice, $i, "field_id").'_label" value="'.$adb->query_result($choice, $i, "choice_title").'" readonly>
                                                </div>
                                    
                                                <div>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-default" data-fieldid="'.$adb->query_result($choice, $i, "field_id").'" onclick="$.removeRow(this, \''.$adb->query_result($choice, $i, "choiceid").'\')"><i class="fa fa-trash"></i></button>
                                                        <!-- <button type="button" class="btn btn-default sort-handler"><i class="fa fa-arrows"></i></button> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" name="field_'.$adb->query_result($choice, $i, "field_id").'_choice" readonly>
                                        </div>
                                    </div>';
                                break;
                                case 'radio':
                                    $choiceID = $adb->query_result($choice, $i, "choiceid");
                                    $sql = "SELECT * FROM survey_choice_detail WHERE choiceid='".$choiceID."' ORDER BY sequence";
                                    $choiceDetail = $adb->pquery($sql, []);
                                    $choiceDetailRows = $adb->num_rows($choiceDetail);

                                    $html .= '<div class="row row-template">
                                        <div class="col-12">
                                            <div class="row-title">
                                                <div style="flex:1">
                                                    <input type="hidden" name="fields[]" value="'.$adb->query_result($choice, $i, "field_id").'">
                                                    <input type="hidden" name="field_'.$adb->query_result($choice, $i, "field_id").'_choiceid" value="'.$adb->query_result($choice, $i, "choiceid").'">
                                                    <input type="hidden" name="field_'.$adb->query_result($choice, $i, "field_id").'_fieldname" value="'.$adb->query_result($choice, $i, "field_name").'">
                                                    <input type="hidden" name="field_'.$adb->query_result($choice, $i, "field_id").'_type" value="dropdown">
                                                    <input type="text" class="text-input" name="field_'.$adb->query_result($choice, $i, "field_id").'_label" value="'.$adb->query_result($choice, $i, "choice_title").'" readonly>
                                                </div>
                                    
                                                <div>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-default" data-fieldid="'.$adb->query_result($choice, $i, "field_id").'" onclick="$.removeRow(this, \''.$adb->query_result($choice, $i, "choiceid").'\')"><i class="fa fa-trash"></i></button>
                                                        <!-- <button type="button" class="btn btn-default sort-handler"><i class="fa fa-arrows"></i></button> -->
                                                    </div>
                                                </div>
                                            </div>';

                                            if($choiceDetailRows > 0){
                                                for($j=0; $j<$choiceDetailRows; $j++){
                                                    $html .= '<div style="display: flex; align-items:stretch;">
                                                        <div style="width:20px;padding-top:5px">'.($j+1).'</div>
                                                        <div style="flex:1">
                                                            <input type="text" class="text-input" name="field_'.$adb->query_result($choice, $i, "field_id").'_choice[]" value="'.$adb->query_result($choiceDetail, $j, "choicedetail_name").'" readonly>
                                                        </div>
                                                    </div>';
                                                }
                                            }

                                    $html .= '</div></div>';
                                break;
                                case 'checkbox':
                                    $choiceID = $adb->query_result($choice, $i, "choiceid");
                                    $sql = "SELECT * FROM survey_choice_detail WHERE choiceid='".$choiceID."' ORDER BY sequence";
                                    $choiceDetail = $adb->pquery($sql, []);
                                    $choiceDetailRows = $adb->num_rows($choiceDetail);

                                    $html .= '<div class="row row-template">
                                        <div class="col-12">
                                            <div class="row-title">
                                                <div style="flex:1">
                                                    <input type="hidden" name="fields[]" value="'.$adb->query_result($choice, $i, "field_id").'">
                                                    <input type="hidden" name="field_'.$adb->query_result($choice, $i, "field_id").'_choiceid" value="'.$adb->query_result($choice, $i, "choiceid").'">
                                                    <input type="hidden" name="field_'.$adb->query_result($choice, $i, "field_id").'_fieldname" value="'.$adb->query_result($choice, $i, "field_name").'">
                                                    <input type="hidden" name="field_'.$adb->query_result($choice, $i, "field_id").'_type" value="dropdown">
                                                    <input type="text" class="text-input" name="field_'.$adb->query_result($choice, $i, "field_id").'_label" value="'.$adb->query_result($choice, $i, "choice_title").'" readonly>
                                                </div>
                                    
                                                <div>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-default" data-fieldid="'.$adb->query_result($choice, $i, "field_id").'" onclick="$.removeRow(this, \''.$adb->query_result($choice, $i, "choiceid").'\')"><i class="fa fa-trash"></i></button>
                                                        <!-- <button type="button" class="btn btn-default sort-handler"><i class="fa fa-arrows"></i></button> -->
                                                    </div>
                                                </div>
                                            </div>';

                                            if($choiceDetailRows > 0){
                                                for($j=0; $j<$choiceDetailRows; $j++){
                                                    $html .= '<div style="display: flex; align-items:stretch;">
                                                        <div style="width:20px;padding-top:5px">'.($j+1).'</div>
                                                        <div style="flex:1">
                                                            <input type="text" class="text-input" name="field_'.$adb->query_result($choice, $i, "field_id").'_choice[]" value="'.$adb->query_result($choiceDetail, $j, "choicedetail_name").'" readonly>
                                                        </div>
                                                    </div>';
                                                }
                                            }

                                    $html .= '</div></div>';
                                break;
                            }
                        } 
                        echo $html;
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        // $('#item-list').sortable({
        //     items: ".row-template",
        //     helper:'clone',
        //     handle: '.sort-handler',
        //     cursor: 'move'
        // });

        $('#item-list').sortable({
            placeholder: 'myPlaceholder',
            update: function(event, ui) {
                // console.log(event)
            },
        }).disableSelection();
        
        var fields = JSON.parse('<?php echo json_encode($fields); ?>');
        $.addRow = function(type, fieldID){
            var findID = fields.find(id => id === fieldID)
            if(findID !== undefined){
                return false;
            }else{
                fields.push(fieldID)
            }

            var divLoading = $('<div />', {id:'div-loading'}).html('Generating please wait ...').css({ width:'100%', 'text-align':'center'})
            $('#item-list').append(divLoading);
            switch(type){
                case '1':
                    $.post('plugin/survey/template_text.php', {fieldID}, function(html){
                        $('#div-loading').remove();
                        $('#item-list').append(html)
                    })
                    break;
                case '15':
                    $.post('plugin/survey/template_picklist.php', {fieldID}, function(html){
                        $('#div-loading').remove();
                        $('#item-list').append(html)
                    })
                    break;
                case '33':
                    $.post('plugin/survey/template_multi_picklist.php', {fieldID}, function(html){
                        $('#div-loading').remove();
                        $('#item-list').append(html)
                    })
                    break;
            }
        }

        $.removeRow = function(obj, choiceid){
            var fieldid = $(obj).data('fieldid')

            if(confirm('Remove Data ?')){
                $(obj).parents('.row-template').remove();

                if(fieldid !== undefined) fields = fields.filter(id => id != fieldid)
                if(choiceid !== undefined){
                    $.post('plugin/survey/remove_choice.php', {choiceid}, function(rs){
                        console.log(rs)
                    },'json')
                }
            }
        }
    })
</script>
</body>