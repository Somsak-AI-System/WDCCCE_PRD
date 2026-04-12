<?php
require_once('func_php.php');
$temp11 = generateRandomString() . time();
?>
<style>
    .temp-input {
        border:0px;
        border-bottom:1px dotted #CCCCCC;
        color:#0000FF;
        font-style:italic;
        font-weight:bold;
        /*width: 50px;*/
    }

</style>
<fieldset id="remove_<?php echo $temp11; ?>" class="template11-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $temp11; ?>"/>
    <input type="hidden" name="<?php echo $temp11; ?>_type" id="<?php echo $temp11; ?>_type" value="template11"/>
    <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input">
                <input name="<?php echo $temp11; ?>_headtext" type="text" class="template11-inphead" value=""
                       placeholder="Template 11..."
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

    <div id="<?php echo $temp11; ?>">
        <input type="hidden" name="template_temp_id[]" value="<?php echo $temp11;?>">
        <table class="table" id="<?php echo $temp11;?>">

            <tbody class="tbody-<?php echo $temp11;?>">
            </tbody>

            <tfoot>
            <tr>
                <td colspan="9">
                    <a href="javascript:void(0)" onclick="$.addRowTemp11('<?php echo $temp11;?>')"><i class="fa fa-plus-square"></i></a>
                    <a href="javascript:void(0)" onclick="$.removeRowTemp11('<?php echo $temp11;?>')"><i class="fa fa-minus-square"></i></a>
                </td>
            </tr>
            </tfoot>
        </table>

    </div>

</fieldset>

<script>

    $.tempRow11 = function(tempID){
        var index = $(`#${tempID} tbody`).find(`tr.temp-row-${tempID}`).length; //console.log(index)
        var tr = `<tr class="temp-row-${tempID}">
                <td width="5%" >
                    <span class="fa fa-minus"></spacn>
                </td>
                <td width="85%">
                    <label class="input">
                        <input type="hidden" class="fa fa-square-o temp-input template11-inphead" name="${tempID}_seq[]" value="${index+1}" />
                        <input type="text" class="fa fa-square-o temp-input template11-inphead" name="label_${tempID}[]" value="" placeholder="label_${index+1}" />
                    </labe>
                </td>
                <td width="10%" class="temp-input">
                    <div style="width:19px; margin:0 auto">
                        <label class="checkbox" >
                            <input name="" value="" type="checkbox" disabled="disabled"  ><i></i>
                        </label>
                    </div>
                </td>
            </tr>`;

        return tr;
    }

    var tempID = `<?php echo $temp11;?>`;
    $.addRowTemp11 = function(tempID){
        var tr = $.tempRow11(tempID);

        $(`#${tempID} tbody.tbody-${tempID}`).append(tr)
    }

    for(var i=0; i<=4; i++){
        $.addRowTemp11(tempID)
    }

    $.removeRowTemp11 = function(tempID){
        var tbody = $(`tbody.tbody-${tempID}`);

        $(tbody).find('tr').last().remove();

        $(tbody).find(`tr.temp-row-${tempID}`).each(function(index, item){
            $(item).find('span.row-index').html(index+1)
        })
    }

</script>