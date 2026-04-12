<?php
require_once('func_php.php');
$temp13 = generateRandomString() . time();
?>
<style>
    .temp-input {
        border:0px;
        border-bottom:1px dotted #CCCCCC;
        color:#0000FF;
    }

</style>
<fieldset id="remove_<?php echo $temp13; ?>" class="template13-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $temp13; ?>"/>
    <input type="hidden" name="<?php echo $temp13; ?>_type" id="<?php echo $temp13; ?>_type" value="template13"/>
    <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input">
                <input name="<?php echo $temp13; ?>_headtext" type="text" class="template13-inphead" value=""
                       placeholder="Template 13..."
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

    <div id="<?php echo $temp13; ?>">
        <input type="hidden" name="template_temp_id[]" value="<?php echo $temp13;?>">
        <table class="table table-bordered" id="<?php echo $temp13;?>">

            <tr>
                <td >
                    <label class="input">
                    </label>
                </td>

                <td>
                    <label class="input" >
                        <input name="<?php echo $temp13; ?>_head_col0" type="text" class="template13-inphead" value="Check"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" >
                    </label>
                </td>
                <td>
                    <label class="input" >
                        <input name="<?php echo $temp13; ?>_head_col1" type="text" class="template13-inphead" value="Value"
                               style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" >
                    </label>
                </td>

            </tr>

            <tbody class="tbody-<?php echo $temp13;?>">
            </tbody>

            <tfoot>
            <tr>
                <td colspan="3">
                    <a href="javascript:void(0)" onclick="$.addRowTemp13('<?php echo $temp13;?>')"><i class="fa fa-plus-square"></i></a>
                    <a href="javascript:void(0)" onclick="$.removeRowTemp13('<?php echo $temp13;?>')"><i class="fa fa-minus-square"></i></a>
                </td>
            </tr>
            </tfoot>
        </table>

    </div>

</fieldset>

<script>

    $.tempRow13 = function(tempID){
        var index = $(`#${tempID} tbody`).find(`tr.temp-row-${tempID}`).length; //console.log(index)
        var tr = `<tr class="temp-row-${tempID}">
                    <td colspan="3">
                        <label class="input">
                            <input type="text" class="fa fa-square-o temp-input template13-inphead" name="label_${tempID}[]" value="Text_${index+1}" placeholder="Text_${index+1}" />
                         </labe>
                    </td>
                   </tr>
                   <tr >
                    <td>
                        <label class="input">
                            <input type="text" class="fa fa-square-o temp-input template13-inphead" name="sub_label_${tempID}[]"  value="SubText_${index+1}" placeholder="SubText" />
                        </labe>
                    </td>
                <td width="10%" class="temp-input">
                    <div style="width:19px; margin:0 auto">
                        <label class="checkbox" >
                            <input name="" value="" type="checkbox" disabled="disabled"  ><i></i>
                        </label>
                    </div>
                </td>
                  <td>
                  </td>
                </tr>
                `;

        return tr;
    }

    var tempID = `<?php echo $temp13;?>`;
    $.addRowTemp13 = function(tempID){
        var tr = $.tempRow13(tempID);

        $(`#${tempID} tbody.tbody-${tempID}`).append(tr)
    }

    for(var i=0; i<=4; i++){
        $.addRowTemp13(tempID)
    }

    $.removeRowTemp13 = function(tempID){
        var tbody = $(`tbody.tbody-${tempID}`);

        $(tbody).find('tr').last().remove();

        $(tbody).find(`tr.temp-row-${tempID}`).each(function(index, item){
            $(item).find('span.row-index').html(index+1)
        })
    }

</script>