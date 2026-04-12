<?php
require_once('func_php.php');
$temp8 = generateRandomString() . time();
?>
<style>
    .temp-input {
        border:0px;
        border-bottom:1px dotted #CCCCCC;
        color:#0000FF;
    }

    
</style>
<fieldset id="remove_<?php echo $temp8; ?>" class="template8-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $temp8; ?>"/>
    <input type="hidden" name="<?php echo $temp8; ?>_type" id="<?php echo $temp8; ?>_type" value="template8"/>
    <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input">
                <input name="<?php echo $temp8; ?>_headtext" type="text" class="template8-inphead" value=""
                       placeholder="Check Box & sub row Template..."
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

    <div id="<?php echo $temp8; ?>">
        <input type="hidden" name="template_temp_id[]" value="<?php echo $temp8;?>">
        <table class="table" id="<?php echo $temp8;?>">
            <tbody class="tbody-<?php echo $temp8;?>">
            </tbody>
            <tfoot>
            <tr>
                <td>
                    <a href="javascript:void(0)" onclick="$.addRowTemp8('<?php echo $temp8;?>')"><i class="fa fa-plus-square"></i></a>
                    <a href="javascript:void(0)" onclick="$.removeLastRowTemp8('<?php echo $temp8;?>')"><i class="fa fa-minus-square"></i></a>

                </td>
            </tr>

            </tfoot>
        </table>

    </div>

</fieldset>

<script>

    $.tempRow8 = function(tempID, UUID){
        var index = $(`#${tempID} tbody`).find('tr').length; //console.log(index)
        var tr = `<tr class="temp-row-${tempID}" id="tr-${UUID}">
                <td width="10%" class="temp-input">
                    <div style="width:19px; margin:0 auto">
                        <label class="checkbox" >
                            <input name="" value="" type="checkbox" disabled="disabled"  ><i></i>
                        </label>
                    </div>
                </td>
                <td width="5%" style="text-align: center;">
                    <span class="row-index">${index+1}</span>
                </td>
                    <td width="85%" >
                    <label class="input">
                        <input type="hidden" name="tr_${tempID}[]" size="100" value="${UUID}">
                        <input type="text" style="text-align: left" class="fa fa-square-o temp-input template8-inphead" name="label_${tempID}[]" value="Text ${index+1}" placeholder="label_${index+1}" />
                        <a href="javascript:void(0);" onclick="$.removeRowTemp8(this, '${tempID}')" class="fa fa-minus-square"></a>
                        <div class="child-row" style="padding-left:20px;">
                        </div>
                    </label>
                </td>
            </tr>
            `;

        return tr;
    }

    var tempID = `<?php echo $temp8;?>`;
    $.addRowTemp8 = function(tempID){
        var UUID = Date.now() + Math.floor((Math.random() * 10) + 1); //console.log(UUID)
        var tr = $.tempRow8(tempID, UUID);

        $(`#${tempID} tbody`).append(tr)
        $.addRowChild(UUID);
    }

    $.addRowChild = function(UUID){
        var div = `<div class="childs">
                    <input type="text" style="text-align: left" class="fa fa-square-o temp-input template8-inphead" name="child_label_${UUID}[]" size="100" value="" placeholder="Sub Label" />
                    <a href="javascript:void(0);" onclick="$.removeRowChild(this, '${UUID}')"><i class="fa fa-minus-square"></i></a>
                    <a href="javascript:void(0);" onclick="$.addRowChild('${UUID}')"><i class="fa fa-plus-square"></i></a>
               </div>`;

        var childParent = $(`tr#tr-${UUID}`).find('.child-row');
        $(childParent).find('.fa-plus-square').parent('a').remove();
        $(childParent).append(div);

        var len = $(childParent).find('.childs').length;
        var firstChild = $(childParent).find('.childs:first-child');
        if(len === 1){
            if($(firstChild).find('.fa-minus-square').length !== 1){
                $(firstChild)
                    .append(`<a href="javascript:void(0);" onclick="$.addRowChild('${UUID}')"><i class="fa fa-plus-square"></i></a>`)
                    .find('.fa-minus-square').parent('a').remove();
            }
            $(childParent).find('.fa-minus-square').parent('a').remove();
        }else{
            if($(firstChild).find('.fa-minus-square').length !== 1){
                $(firstChild)
                    .append(`<a href="javascript:void(0);" onclick="$.removeRowChild(this, '${UUID}')"><i class="fa fa-minus-square"></i></a>`)
                    .find('.fa-plus-square').parent('a').remove();
            }
        }


    }

    for(var i=0; i<=1; i++){
        $.addRowTemp8(tempID)
    }

    $.removeLastRowTemp8 = function(tempID){
        var tbody = $(`tbody.tbody-${tempID}`);

        $(tbody).find('tr').last().remove();

        $(tbody).find(`tr.temp-row-${tempID}`).each(function(index, item){
            $(item).find('span.row-index').html(index+1)
        })
    }

    $.removeRowTemp8 = function(obj, tempID){
        var tbody = $(obj).parents(`tbody.tbody-${tempID}`);
        $(obj).parents(`tr.temp-row-${tempID}`).remove();

        $(tbody).find('tr').each(function(index, item){
            $(item).find('span.row-index').html(index+1)
        })
    }

    $.removeRowChild = function(obj, UUID){
        var childParent = $(`tr#tr-${UUID}`).find('.child-row');
        $(obj).parent('div').remove();

        var lastChild = $(childParent).find('.childs:last-child');
        var iconPlus = $(lastChild).find('.fa-plus-square');
        if(iconPlus.length !== undefined && iconPlus.length === 0){
            $(lastChild).append(`<a href="javascript:void(0);" onclick="$.addRowChild('${UUID}')"><i class="fa fa-plus-square"></i></a>`);
        }

        var len = $(childParent).find('.childs').length;
        if(len === 1){
            $(lastChild).find('.fa-minus-square').parent('a').remove();
        }
    }

</script>
