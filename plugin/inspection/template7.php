<?php
require_once('func_php.php');
$temp7 = generateRandomString() . time();
?>
<style>
    .temp-input {
        border:0px;
        border-bottom:1px dotted #CCCCCC;
        color:#0000FF;
    }
</style>
<fieldset id="remove_<?php echo $temp7; ?>" class="template7-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $temp7; ?>"/>
    <input type="hidden" name="<?php echo $temp7; ?>_type" id="<?php echo $temp7; ?>_type" value="template7"/>
    <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input">
                <input name="<?php echo $temp7; ?>_headtext" type="text" class="template7-inphead" value=""
                       placeholder="Single Check Box Template..."
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

    <div id="<?php echo $temp7; ?>">
        <input type="hidden" name="template_temp_id[]" value="<?php echo $temp7;?>">
        <table class="table" id="<?php echo $temp7;?>">
            <tbody class="tbody-<?php echo $temp7;?>">
            </tbody>
            <tfoot>
            <tr>
                <td>
                </td>
            </tr>
            </tfoot>
        </table>

    </div>

    <script>

        $.tempRow7 = function(tempID){
            var index = $(`#${tempID} tbody`).find('tr').length; //console.log(index)
            var tr = `<tr class="temp-row-${tempID}">
                <td width="5%" class="temp-input">
                    <div style="width:19px; margin:0 auto">
                        <label class="checkbox" >
                            <input name="" value="" type="checkbox" disabled="disabled"  ><i></i>
                        </label>
                    </div>
                </td>
                <td width="95%">
                    <label class="input">
                        <input type="text" class="fa fa-square-o temp-input template7-inphead" name="label_${tempID}[]" value="" placeholder="label_${index+1}" />
                    </label>
                </td>
            </tr>`;

            return tr;
        }

        var tempID = `<?php echo $temp7;?>`;
        $.addRowTemp7 = function(tempID){
            var tr = $.tempRow7(tempID);

            $(`#${tempID} tbody`).append(tr)
        }

        for(var i=0; i<=0; i++){
            $.addRowTemp7(tempID)
        }


    </script>

</fieldset>
