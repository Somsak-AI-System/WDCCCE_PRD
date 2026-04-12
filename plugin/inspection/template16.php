<?php
require_once('func_php.php');
$temp16 = generateRandomString() . time();
?>
<style>
    .temp-input {
    border:0px;
    border-bottom:1px dotted #CCCCCC;
    color:#0000FF;
    }

</style>
<fieldset id="remove_<?php echo $temp16; ?>" class="template16-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $temp16; ?>"/>
    <input type="hidden" name="<?php echo $temp16; ?>_type" id="<?php echo $temp16; ?>_type" value="template16"/>
    <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input">
                <input name="<?php echo $temp16; ?>_headtext" type="text" class="template16-inphead" value=""
                       placeholder="Template 16..."
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

    <div id="<?php echo $temp16; ?>">
        <input type="hidden" name="template_temp_id[]" value="<?php echo $temp16;?>">
        <table class="table table-bordered " id="<?php echo $temp16;?>">

            <tr>
                <th colspan="4">
                    <label class="input">
                        <input name="<?php echo $temp16; ?>_head_col0" type="text" class="template16-inphead" value="Frequency"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" >
                    </label>
                </th>
            </tr>
            <tr>
                <th >
                    <label class="input">
                        <input name="<?php echo $temp16; ?>_head_col1" type="text" class="template16-inphead" value="Text "
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " >
                    </label>
                </th>
                <th >
                    <label class="input">
                        <input name="<?php echo $temp16; ?>_head_col2" type="text" class="template16-inphead" value="Text "
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" >
                    </label>
                </th>
                <th >
                    <label class="input">
                        <input name="<?php echo $temp16; ?>_head_col3" type="text" class="template16-inphead" value="Text "
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" >
                    </label>
                </th>
                <th >
                    <label class="input">
                        <input name="<?php echo $temp16; ?>_head_col4" type="text" class="template16-inphead" value="Text "
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" >
                    </label>
                </th>
            </tr>

            <tbody class="tbody-<?php echo $temp16;?>">
            </tbody>

            <tfoot>
            <tr>
                <th colspan="4">
                    <a href="javascript:void(0)" onclick="$.addRowTemp16('<?php echo $temp16;?>')"><i class="fa fa-plus-square"></i></a>
                    <a href="javascript:void(0)" onclick="$.removeRowTemp16('<?php echo $temp16;?>')"><i class="fa fa-minus-square"></i></a>
                </th>
            </tr>
            </tfoot>
        </table>

    </div>

</fieldset>
<script>

    $.tempRow16 = function(tempID){
        var index = $(`#${tempID} tbody`).find(`tr.temp-row-${tempID}`).length; //console.log(index)
        var tr = `<tr class="temp-row-${tempID}">
                        <td>
                            <input type="text" style="width: 200px; !important;" class="fa fa-square-o temp-input template16-inphead" name="label_${tempID}[]" value="Text_${index+1}" placeholder="Text_${index+1}" />
                        </td>
                        <td style="text-align: center;">
                            <span class="fa fa-square-o"></spacn>
                        </td>
                        <td style="text-align: center;">
                            <span class="fa fa-square-o"></spacn>
                        </td>
                        <td style="text-align: center;">
                            <span class="fa fa-square-o"></spacn>
                        </td>

                      </tr>
                      `;

        return tr;
    }

    var tempID = `<?php echo $temp16;?>`;
    $.addRowTemp16 = function(tempID){
        var tr = $.tempRow16(tempID);

        $(`#${tempID} tbody.tbody-${tempID}`).append(tr)
    }

    for(var i=0; i<=4; i++){
        $.addRowTemp16(tempID)
    }

    $.removeRowTemp16 = function(tempID){
        var tbody = $(`tbody.tbody-${tempID}`);

        $(tbody).find('tr').last().remove();

        $(tbody).find(`tr.temp-row-${tempID}`).each(function(index, item){
            $(item).find('span.row-index').html(index+1)
        })
    }

</script>
