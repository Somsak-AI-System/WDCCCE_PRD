<?php
require_once('func_php.php');
$temp9 = generateRandomString() . time();
?>

<fieldset id="remove_<?php echo $temp9; ?>" class="template9-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $temp9; ?>"/>
    <input type="hidden" name="<?php echo $temp9; ?>_type" id="<?php echo $temp9; ?>_type" value="template9"/>
    <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input">
                <input name="<?php echo $temp9; ?>_headtext" type="text" class="template9-inphead" value=""
                       placeholder="Template 9..."
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

    <div id="<?php echo $temp9; ?>">
        <input type="hidden" name="template_temp_id[]" value="<?php echo $temp9;?>">
        <table class="table table-bordered " id="<?php echo $temp9;?>">

            <tr>
                <th rowspan="4">
                    <label class="input">
                        <input name="<?php echo $temp9; ?>_head_col0" type="text" class="template9-inphead" value="Method"
                               style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; ">
                    </label>
                </th>
                <th colspan="6">
                    <label class="input">
                        <input name="<?php echo $temp9; ?>_head_col1" type="text" class="template9-inphead" placeholder="Date:__/__/__-__/__/__" value=""
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                    </label>
                </th>
            </tr>
            <tr>
                <th rowspan="4">
                    <label class="input">
                        <input name="<?php echo $temp9; ?>_head_col2" type="text" class="template9-inphead" placeholder="" value="CV % Acceptable Limit"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;"">
                    </label>
                </th>
                <th colspan="2">
                    <label class="input">
                        <input name="<?php echo $temp9; ?>_head_col3" type="text" class="template9-inphead" placeholder="" value="Level 2"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;"">
                    </label>
                </th>
                <th colspan="2">
                    <label class="input">
                        <input name="<?php echo $temp9; ?>_head_col4" type="text" class="template9-inphead" placeholder="" value="Level 3"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;"">
                    </label>
                </th>
                <th rowspan="4">
                    <label class="input">
                        <input name="<?php echo $temp9; ?>_head_col5" type="text" class="template9-inphead" placeholder="" value="Status"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;"">
                    </label>
                </th>
            </tr>
            <tr>
                <th colspan="2">
                    <label class="input">
                        <input name="<?php echo $temp9; ?>_head_col6" type="text" class="template9-inphead" placeholder="" value="Lot No:"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; "">
                    </label>
                </th>
                <th colspan="2">
                    <label class="input">
                        <input name="<?php echo $temp9; ?>_head_col7" type="text" class="template9-inphead" placeholder="" value="Lot No:"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; "">
                    </label>
                </th>


            </tr>

            <tr>
                <th>
                    <label class="input">
                        <input name="<?php echo $temp9; ?>_head_col8" type="text" class="template9-inphead" placeholder="" value="Mean"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;"">
                    </label>
                </th>
                <th>
                    <label class="input">
                        <input name="<?php echo $temp9; ?>_head_col9" type="text" class="template9-inphead" placeholder="" value="CV%"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;"">
                    </label>
                </th>
                <th>
                    <label class="input">
                        <input name="<?php echo $temp9; ?>_head_col10" type="text" class="template9-inphead" placeholder="" value="Mean"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;"">
                    </label>
                </th>
                <th>
                    <label class="input">
                        <input name="<?php echo $temp9; ?>_head_col11" type="text" class="template9-inphead" placeholder="" value="CV%"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;"">
                    </label>
                </th>

            </tr>

            <tbody class="tbody-<?php echo $temp9;?>">
            </tbody>

            <tfoot>
            <tr>
                <td colspan="9">
                    <a href="javascript:void(0)" onclick="$.addRowTemp9('<?php echo $temp9;?>')"><i class="fa fa-plus-square"></i></a>
                    <a href="javascript:void(0)" onclick="$.removeRowTemp9('<?php echo $temp9;?>')"><i class="fa fa-minus-square"></i></a>
                </td>
            </tr>
            </tfoot>
        </table>

    </div>

</fieldset>
<style>
    table.table-bordered th:last-child {
        border-right-width: 1px;
    }

    table.table-bordered tbody th {
        border-left-width: 1px;
        border-bottom-width: 1px;
    }

</style>
<script>

    $.tempRow9 = function(tempID){
        var index = $(`#${tempID} tbody`).find(`tr.temp-row-${tempID}`).length; //console.log(index)
        var tr = `<tr class="temp-row-${tempID}">
                        <td style="text-align: center;">
                            <span class="row-index">${index+1}</span>
                            <input type="hidden" class="fa fa-square-o temp-input template9-inphead" name="${tempID}_seq[]" value="${index+1}" />
                        </td>
                        <td>
                             <input type="hidden" class="fa fa-square-o temp-input template9-inphead" name="${tempID}_col0" value="" />
                        </td>
                        <td>
                             <input type="hidden" class="fa fa-square-o temp-input template9-inphead" name="${tempID}_col1" value="" />
                        </td>
                        <td>
                             <input type="hidden" class="fa fa-square-o temp-input template9-inphead" name="${tempID}_col2" value="" />
                        </td>
                        <td>
                             <input type="hidden" class="fa fa-square-o temp-input template9-inphead" name="${tempID}_col3" value="" />
                        </td>
                        <td>
                             <input type="hidden" class="fa fa-square-o temp-input template9-inphead" name="${tempID}_col4" value="" />
                        </td>
                        <td>
                             <input type="hidden" class="fa fa-square-o temp-input template9-inphead" name="${tempID}_col5" value="" />
                        </td>

                      </tr>
                      `;

        return tr;
    }

    var tempID = `<?php echo $temp9;?>`;
    $.addRowTemp9 = function(tempID){
        var tr = $.tempRow9(tempID);

        $(`#${tempID} tbody.tbody-${tempID}`).append(tr)
    }

    for(var i=0; i<=4; i++){
        $.addRowTemp9(tempID)
    }

    $.removeRowTemp9 = function(tempID){
        var tbody = $(`tbody.tbody-${tempID}`);

        $(tbody).find('tr').last().remove();

        $(tbody).find(`tr.temp-row-${tempID}`).each(function(index, item){
            $(item).find('span.row-index').html(index+1)
        })
    }

</script>
