<?php
require_once('func_php.php');
$temp6 = generateRandomString() . time();
?>
<style>
    .temp-input {
        /*border:0px;*/
        /*border-bottom:1px dotted #CCCCCC;*/
        text-align: center;
        color:#0000FF;
        width: 20px;
    }
</style>
<fieldset id="remove_<?php echo $temp6; ?>" class="template6-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $temp6; ?>"/>
    <input type="hidden" name="<?php echo $temp6; ?>_type" id="<?php echo $temp6; ?>_type" value="template6"/>
    <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input">
                <input name="<?php echo $temp6; ?>_headtext" type="text" class="template6-inphead" value=""
                       placeholder="Rating Template..."
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

    <div id="<?php echo $temp6; ?>">
        <input type="hidden" name="template_temp_id[]" value="<?php echo $temp6;?>">
        <table class="form-table" id="<?php echo $temp6;?>">
            <tbody class="tbody-<?php echo $temp6;?>">
            </tbody>
            <tfoot>
            <th>
                <a href="javascript:void(0)" onclick="$.addRowTemp6('<?php echo $temp6;?>')"><i class="fa fa-plus-square"></i></a>
            </th>
            </tfoot>
        </table>

    </div>

    <script>

        $.tempRow6 = function(tempID){
            var index = $(`#${tempID} tbody`).find('td').length; //console.log(index)
            var tr = `
                <td class="temp-row-${tempID}">
                    <input type="text" class="fa fa-square-o temp-input template6-inphead" name="label_${tempID}[]" value="${index+1}" placeholder="label_${index+1}" />
                    <a href="javascript:void(0);" onclick="$.removeRowTemp6(this, '${tempID}')" class="remCF fa fa-remove"></a>
                </td>
            `;

            return tr;
        }

        var tempID = `<?php echo $temp6;?>`;
        $.addRowTemp6 = function(tempID){
            var tr = $.tempRow6(tempID);

            $(`#${tempID} tbody`).append(tr)
        }

        for(var i=0; i<=4; i++){
            $.addRowTemp6(tempID)
        }

        $.removeRowTemp6 = function(obj, tempID){
            // console.log(obj)
            var tbody = $(obj).parents(`tbody.tbody-${tempID}`);
            console.log($(tbody).find('tr:last-child'))


            $(obj).parents(`td.temp-row-${tempID}`).remove();

            $(tbody).find('td').each(function(index, item){
                $(item).find('span.row-index').html(index+1)
            })
        }

    </script>

</fieldset>
