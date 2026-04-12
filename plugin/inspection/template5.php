<?php
require_once('func_php.php');
$temp5 = generateRandomString();
?>

<fieldset id="remove_<?php echo $temp5; ?>" class="template5-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $temp5;?>">
    <input type="hidden" name="<?php echo $temp5;?>_type" id="<?php echo $temp5;?>_type" value="template5">
    <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input">
                <input name="<?php echo $temp5; ?>_headtext" type="text" value="" placeholder="Text Area..." class="template5-head" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;">
            </label>
        </section>
        <section class="col col-2" style="margin-bottom:5px;">
            <div class="btn-group" style="float:right;">
                <button class="btn dropdown-toggle btn-sm btn-default" data-toggle="dropdown">
                    <i class="fa fa-cog"></i>
                </button>
                <span class="btn btn-sm btn-default"><i class="fa fa-sort" style="cursor:move"></i></span>
                <ul class="dropdown-menu pull-right js-status-update">
                    <li>
                        <a href="javascript:void(0);" onclick="removeFieldSet(this)"><i class="fa fa-trash-o"></i> ลบตาราง</a>
                    </li>
                </ul>
            </div>
        </section>
    </div>
    <section id="<?php echo $temp5; ?>" class="table table-template5">
        <label class="textarea textarea-resizable">
            <textarea name="<?php echo $temp5; ?>_template5text" id="<?php echo $temp5; ?>_template5text" rows="3" class="custom-scroll template5-input" readonly="readonly"></textarea>
        </label>
    </section>
</fieldset>