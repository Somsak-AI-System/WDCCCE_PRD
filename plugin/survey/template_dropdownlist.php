<?php
require_once ("../../config.inc.php");
$path = $root_directory;

require_once ($path."library/dbconfig.php");
require_once ($path."library/genarate.inc.php");
$genarate = new genarate($dbconfig ,"DB");

$fieldID = $_POST['fieldID'];
$sql = "SELECT * FROM aicrm_field WHERE fieldid='".$fieldID."'";
$result = $genarate->process($sql,"all");

$fieldID = $result[0][1];
$fieldName = $result[0][6];
$fieldLabel = $result[0][7];

$table = "aicrm_".$fieldName;
$query = "SELECT * FROM ".$table;
$options = $genarate->process($query,"all");
?>
<div class="row row-template">
    <div class="col-12">
        <div class="row-title">
            <div style="flex:1">
                <input type="hidden" name="fields[]" value="<?php echo $fieldID; ?>">
                <input type="hidden" name="field_<?php echo $fieldID; ?>_fieldname" value="<?php echo $fieldName; ?>">
                <input type="hidden" name="field_<?php echo $fieldID; ?>_type" value="dropdown">
                <input type="text" class="text-input" name="field_<?php echo $fieldID; ?>_label" value="<?php echo $fieldLabel; ?>" readonly>
            </div>

            <div>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-default" data-fieldid="<?php echo $fieldID; ?>" onclick="$.removeRow(this)"><i class="fa fa-trash"></i></button>
                    <!-- <button type="button" class="btn btn-default sort-handler"><i class="fa fa-arrows"></i></button> -->
                </div>
            </div>
        </div>

        <?php for($i=0; $i<count($options); $i++){ ?>
            <div style="display: flex; align-items:stretch;">
                <div style="width:20px;padding-top:5px;"><?php echo ($i+1); ?></div>
                <div style="flex:1">
                    <input type="text" class="text-input" name="field_<?php echo $fieldID; ?>_choice[]" value="<?php echo $options[$i][1]; ?>" readonly>
                </div>
            </div>
        <?php } ?>
    </div>
</div>