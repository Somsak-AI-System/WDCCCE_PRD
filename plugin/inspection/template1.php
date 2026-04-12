<?php
require_once('func_php.php');
$temp1 = generateRandomString() . time();
?>

<fieldset id="remove_<?php echo $temp1; ?>" class="template1-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $temp1;?>">
    <input type="hidden" name="<?php echo $temp1;?>_type" id="<?php echo $temp1;?>_type" value="template1">
    <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input">
                <input name="<?php echo $temp1; ?>_headtext" type="text" value="" placeholder="Single Input..." class="template1-head" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;">
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
    <section id="<?php echo $table;?>" class="table table-template1">
        <label class="input">
            <input name="<?php echo $table; ?>_template1text" type="text" value="<?php echo (@$value['list'][0]['answer']['detail_template1'] != '') ? @$value['list'][0]['answer']['detail_template1'] : ''; ?>" readonly="readonly">
        </label>
    </section>
</fieldset>