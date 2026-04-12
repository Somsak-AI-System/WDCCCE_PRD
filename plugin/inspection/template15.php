<?php
require_once('func_php.php');
$temp15 = generateRandomString() . time();
?>
<style>
    .temp-input {
    border:0px;
    border-bottom:1px dotted #CCCCCC;
    color:#0000FF;
    }

</style>
<fieldset id="remove_<?php echo $temp15; ?>" class="template15-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $temp15; ?>"/>
    <input type="hidden" name="<?php echo $temp15; ?>_type" id="<?php echo $temp15; ?>_type" value="template15"/>
    <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input">
                <input name="<?php echo $temp15; ?>_headtext" type="text" class="template15-inphead" value=""
                       placeholder="Template 15..."
                       style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;"
                       required>
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

    <div id="<?php echo $temp15; ?>">
        <input type="hidden" name="template_temp_id[]" value="<?php echo $temp15;?>">
        <table class="table table-bordered " id="<?php echo $temp15;?>">

            <tr>
                <td>
                    <label class="input">
                        <input name="<?php echo $temp15; ?>_head_col0" type="text" class="template15-inphead" value="Text "
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;">
                    </label>
                </td>
                <td>
                    <label class="input">
                        <input name="<?php echo $temp15; ?>_head_col1" type="text" class="template15-inphead" value="Result"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" >
                    </label>
                </td>
                <td>
                    <label class="input">
                        <input name="<?php echo $temp15; ?>_head_col2" type="text" class="template15-inphead" value="" placeholder="Result 2"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;">
                    </label>
                </td>
                <td>
                    <label class="input">
                        <input name="<?php echo $temp15; ?>_head_col3" type="text" class="template15-inphead" value="Acceptable Range"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" >
                    </label>
                </td>
                <td>
                    <label class="input">
                        <input name="<?php echo $temp15; ?>_head_col4" type="text" class="template15-inphead" value="Status"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" >
                    </label>
                </td>
            </tr>

            <tbody class="tbody-<?php echo $temp15;?>">
            </tbody>

            <tfoot>
            <tr>
                <td colspan="9">
                    <a href="javascript:void(0)" onclick="$.addRowTemp15('<?php echo $temp15;?>')"><i class="fa fa-plus-square"></i></a>
                    <a href="javascript:void(0)" onclick="$.removeRowTemp15('<?php echo $temp15;?>')"><i class="fa fa-minus-square"></i></a>
                </td>
            </tr>
            </tfoot>
        </table>

    </div>

</fieldset>
<script>

    $.tempRow15 = function(tempID){
        var index = $(`#${tempID} tbody`).find(`tr.temp-row-${tempID}`).length; //console.log(index)
        var tr = `<tr class="temp-row-${tempID}">
                        <td style="text-align: center;">
                            <input type="text" style="width: 200px; !important;" class="fa fa-square-o temp-input template15-inphead" name="label_${tempID}[]" value="Text_${index+1}" placeholder="Text_${index+1}" />
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        <input type="text" style="width: 200px; !important;" class="fa fa-square-o temp-input template15-inphead" name="accept_range_${tempID}[]" value="Text_${index+1}" placeholder="Text_${index+1}" />
                        </td>
                        <td>
                        </td>
                      </tr>
                      `;

        return tr;
    }

    var tempID = `<?php echo $temp15;?>`;
    $.addRowTemp15 = function(tempID){
        var tr = $.tempRow15(tempID);

        $(`#${tempID} tbody.tbody-${tempID}`).append(tr)
    }

    for(var i=0; i<=4; i++){
        $.addRowTemp15(tempID)
    }

    $.removeRowTemp15 = function(tempID){
        var tbody = $(`tbody.tbody-${tempID}`);

        $(tbody).find('tr').last().remove();

        $(tbody).find(`tr.temp-row-${tempID}`).each(function(index, item){
            $(item).find('span.row-index').html(index+1)
        })
    }

</script>
