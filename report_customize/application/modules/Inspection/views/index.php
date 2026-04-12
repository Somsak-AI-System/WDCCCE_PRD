<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>

<head>
  <nav>
      <!-- <ul style="text-align:center;background-color:#51b1ff;"> -->
        <ul style="text-align:center; background-color: #f2f2f2;">
        <li> <button type="button" id="submit_from" value="Save" class="btn  btn-success">บันทึก</button> </li>
        <li> <button type="button" id="send_from" value="Save" class="btn  btn-warning">ปิดงานตรวจ</button> </li>
      <ul>
  </nav>
</head>

      <!-- MAIN PANEL -->
      <div id="main" role="main">

         <div id="content" class="content">
            <section id="widget-grid" class="">

               <div class="row">
                  <article class="col-sm-12 col-md-12 col-lg-12">

                    <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">

                        <div>
                           <!-- widget content -->
                           <div class="widget-body no-padding" style="min-height:50px;">

                            <form id="design-form" name="design-form" class="smart-form" method="post" enctype="multipart/form-data" action="#" ><!-- action="save.php" -->

                                <!-- <div class="smart-form" id="design-form" name="design-form"> -->
                                 <input type="hidden" name="crmid" id="crmid" value="<?php echo $crmid; ?>" />
                                 <input type="hidden" name="start_date" id="start_date" value="<?php echo $start_date; ?>">
                                 <input type="hidden" name="start_time" id="start_time" value="<?php echo $start_time; ?>">
                                 <input type="hidden" name="save_type" value="form" />
                                 <input type="hidden" id="save-form-name" name="form_name" value="" />
                                 <input type="hidden" id="save-form-type" name="form_type" value="" />
                                 <input type="hidden" id="save-form-id" name="form_id" value="" />
                                 <input type="hidden" id="CountRowsID" value="0" />
                                 <div id="parameters-sortable">
                                 <div id="parameters-sortable" class="ui-sortable">
<?php

foreach ($data_template as $key => $value) {

   $table = 'table'.generateRandomString();

    if($value['choice_type']== 'template1'){ ?>

        <fieldset id="remove_<?php echo $table;?>" class="template1-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template1">
            <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">
            <input name="<?php echo $table;?>_choicedetailid" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $value['list'][0]['choicedetailid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_headtext" type="text" value="<?php echo $value['choice_title'];?>" class="template1-head user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>
            <section id="<?php echo $table;?>" class="table table-template1">
                <label class="input">
                    <input name="<?php echo $table; ?>_template1text" type="text" value="<?php echo (@$value['list'][0]['answer']['detail_template1'] != '') ? @$value['list'][0]['answer']['detail_template1'] : ''; ?>" >
                </label>
            </section>
        </fieldset>


    <?php }else if($value['choice_type'] == 'template2'){
        ?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template2">
            <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table">

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">

                <?php
                foreach($value['list'] as $k =>$v){
                    $tr = 'tr'.generateRandomString();
                    ?>
                    <input name="<?php echo $table;?>_choicedetailid[]" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">

                    <tr id="<?php echo $tr;?>" class="tr-form tr-form-<?php echo $table;?>">
                        <td class="temp-input">
                            <div style="width:19px; margin:0 auto">
                                <label class="checkbox" >
                                    <input name="<?php echo $table;?>_result[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" <?php echo (@$v['answer']['status_list_template2'] == '1') ? 'checked' : ''; ?> ><i></i>
                                </label>
                            </div>

                        </td>
                        <td>
                            <label class="input">
                                <input name="<?php echo $table;?>_list[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly>
                            </label>
                        </td>


                    </tr>

                <?php } ?>
                </tbody>

            </table>

        </fieldset>


    <?php }else if($value['choice_type'] == 'template3'){
        ?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template3">
            <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table">

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">

                <?php
                foreach($value['list'] as $k =>$v){
                    $tr = 'tr'.generateRandomString();
                    ?>
                    <input name="<?php echo $table;?>_choicedetailid[]" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">

                    <tr id="<?php echo $tr;?>" class="tr-form tr-form-<?php echo $table;?>">
                        <td class="temp-input">
                            <div style="width:19px; margin:0 auto">
                                <label class="radio" >
                                    <input name="<?php echo $table;?>_result[]" value="<?php echo $v['choicedetailid'];?>" type="radio" <?php echo (@$v['answer']['status_list_template3'] == '1') ? 'checked' : ''; ?> ><i></i>
                                </label>
                            </div>

                        </td>
                        <td>
                            <label class="input">
                                <input name="<?php echo $table;?>_list[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly>
                            </label>
                        </td>


                    </tr>

                <?php } ?>
                </tbody>

            </table>

        </fieldset>


    <?php }else if($value['choice_type'] == 'template4'){
        ?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template4">
            <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">
            <input name="<?php echo $table;?>_choicedetailid" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $value['list'][0]['choicedetailid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table">

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">

                <?php

                foreach($value['list'] as $k =>$v) {
                    $status_list_template4[] = $v['answer']['status_list_template4'];
                } ?>

                <table id="<?php echo $table;?>" style="display:table" class="table">

                    <select name="<?php echo $table;?>_result[]" >
                        <?php
                        foreach($value['list'] as $k =>$v) {
                            ?>
                            <option <?= $v['list'] == $status_list_template4[0] ? "selected":"" ?> value="<?= $v['list'] ?>"><?= $v['list'] ?></option>
                            <?php
                        } ?>
                    </select>

                </table>

        </fieldset>
                </tbody>

            </table>

        </fieldset>


    <?php }else if($value['choice_type'] == 'template5'){
        ?>

        <fieldset id="remove_<?php echo $table;?>" class="template1-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template5">
            <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">
            <input name="<?php echo $table;?>_choicedetailid" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $value['list'][0]['choicedetailid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_headtext" type="text" value="<?php echo $value['choice_title'];?>" class="template1-head user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>
            <section id="<?php echo $table;?>" class="table table-note">
                <label class="textarea textarea-resizable">
                    <textarea name="<?php echo $table;?>_notetext" id="<?php echo $table;?>_notetext" rows="3" class="custom-scroll note-input user-success" ><?php echo (@$value['list'][0]['answer']['comment'] != '') ? @$value['list'][0]['answer']['comment'] : ''; ?></textarea>
                </label>
            </section>
        </fieldset>

    <?php }else if($value['choice_type'] == 'template6'){
        ?>

        <fieldset id="remove_<?php echo $table;?>" class="template1-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template6">
            <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">
            <input name="<?php echo $table;?>_choicedetailid" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $value['list'][0]['choicedetailid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_headtext" type="text" value="<?php echo $value['choice_title'];?>" class="template1-head user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>
            <table id="<?php echo $table;?>" class="form-table">

                <tbody id="<?php echo $table;?>-sortable">
                <?php

                foreach($value['list'] as $k =>$v){
//                       echo '<pre>'; print_r($v['list']); echo '</pre>';
                    $tr = 'tr'.generateRandomString();

                    $statusCheck = $v['answer']['status_list_template6'];

                    if ($v['list'] == $statusCheck){
                        $checked ='checked';
                    }else{
                        $checked ='';
                    }
                    ?>
                    <input name="<?php echo $table;?>_choicedetailid[]" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">
                    <td class="<?php echo $tr;?>">
<!--                        <label class="radio" >-->
<!--                            <input type="radio" name="--><?php //echo $table;?><!--_result[]"  --><?php //echo (@$statusCheck == 1) ? 'checked' : ''; ?><!-- value="--><?php //echo $v['choicedetailid'];?><!--"/>-->
<!--                            <span>--><?php //echo $v['list'];?><!--</span>-->
<!--                        </label>-->
                        <label class="radio">
                            <input type="radio" name="<?php echo $table;?>_result[]" value="<?php echo $v['choicedetailid'];?>" <?php echo (@$v['answer']['status_list_template6'] == '1') ? 'checked' : ''; ?> ><i></i>
                            <?php echo $v['list'];?></span>
                        </label>
                    </td>

                <?php } ?>

            </table>
        </fieldset>

    <?php }else if($value['choice_type'] == 'template7'){
//        alert($value);
        ?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template7">
            <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choicedetailid" id="<?php echo $table;?>_choicedetailid" value="<?php echo $value['list'][0]['choicedetailid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table">

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">

                <?php
                foreach($value['list'] as $k =>$v){
                    $tr = 'tr'.generateRandomString();
                    ?>

                    <tr id="<?php echo $tr;?>" class="tr-form tr-form-<?php echo $table;?>">
                        <td>
                            <label class="radio">
                                <input type="radio" name="<?php echo $table;?>_result" value="0" <?php echo (@$v['answer']['status_list_template7'] == '0') ? 'checked' : ''; ?> ><i></i>
                            </label>
                        </td>
                        <td>
                            <label class="radio">
                                <input type="radio" name="<?php echo $table;?>_result" value="1" <?php echo (@$v['answer']['status_list_template7'] == '1') ? 'checked' : ''; ?> ><i></i>
                            </label>
                        </td>
                        <td>
                            <input name="<?php echo $table;?>_list[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly>

                        </td>
                    </tr>
                <?php } ?>
                </tbody>

            </table>

        </fieldset>

    <?php }else if($value['choice_type'] == 'template8'){
        ?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template8">
            <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table">

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">

                <?php
                foreach($value['list'] as $k =>$v){
//                   echo '<pre>'; print_r($v); echo '</pre>';
                    $tr = 'tr'.generateRandomString();
                    ?>
                    <input name="<?php echo $table;?>_choicedetailid[]" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">

                    <tr id="<?php echo $tr;?>" class="tr-form tr-form-<?php echo $table;?>">
                        <td class="temp-input">
                            <div style="width:19px; margin:0 auto">
                                <label class="checkbox" >
                                    <input name="<?php echo $table;?>_result[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" <?php echo (@$v['answer']['status_list_template8'] == '1') ? 'checked' : ''; ?> ><i></i>
                                </label>
                            </div>

                        </td>
                        <td>
                            <label class="input">
                                <input name="<?php echo $table;?>_list[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly>
                            </label>
                            <?php

                            foreach($choiceDetailList[$v['choicedetailid']] as $k => $v){
                                ?>

                                <div style="padding-left:30px;">
                                    <div >
                                        <label class="input">
                                            <input type="text"  class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" name="<?php echo $tr;?>_child" value="<?php echo $v['value'];?>" readonly />

                                        </label>
                                    </div>
                                </div>

                            <?php }?>
                        </td>


                    </tr>

                <?php } ?>
                </tbody>

            </table>

        </fieldset>

    <?php }else if($value['choice_type'] == 'template9'){
        ?>
<!--        --><?php //echo '<pre>'; print_r($value['list'][0]['answer']['detail_template9']); echo '</pre>';?>
        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template9">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table table-bordered">

                <tr>
                    <th rowspan="4">
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col0" type="text" class="template9-inphead" value="Method"
                                   style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                        </label>
                    </th>
                    <th colspan="6">
                        <label class="input">
                            <input name="<?php echo $table; ?>list" type="text" class="template9-inphead" placeholder="Date:__/__/__-__/__/__" value="<?php echo @$value['list'][0]['answer']['detail_template9'];?>"
                                   style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " >
                        </label>
                    </th>
                </tr>
                <tr>
                    <th rowspan="4">
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col2" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col2']?>"
                                   style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                        </label>
                    </th>
                    <th colspan="2">
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col3" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col3']?>"
                                   style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                        </label>
                    </th>
                    <th colspan="2">
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col4" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col4']?>"
                                   style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                        </label>
                    </th>
                    <th rowspan="4">
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col5" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col5']?>"
                                   style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                        </label>
                    </th>
                </tr>
                <tr>
                    <th colspan="2">
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col6" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col6']?>"
                                   style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                        </label>
                    </th>
                    <th colspan="2">
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col7" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col7']?>"
                                   style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                        </label>
                    </th>


                </tr>

                <tr>
                    <th>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col8" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col8']?>"
                                   style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                        </label>
                    </th>
                    <th>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col9" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col9']?>"
                                   style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                        </label>
                    </th>
                    <th>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col10" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col10']?>"
                                   style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                        </label>
                    </th>
                    <th>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col11" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col11']?>"
                                   style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                        </label>
                    </th>

                </tr>

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">
                <?php
                foreach($value['list'] as $k =>$v){
                    $tr = 'tr'.generateRandomString();
                    ?>
                    <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $tr;?>">
                    <input type="hidden" id="<?php echo $tr;?>_has_input" value="0">
                    <input type="hidden" id="<?php echo $tr;?>_choicedetailid" name="<?php echo $tr;?>_choicedetailid" value="<?php echo $v['choicedetailid']; ?>">


                    <tr class="<?php echo $tr;?>">
                        <td style="text-align: center;">
                            <span class="temp-input"><?php echo $v['sequence_detail'];?></span>
                        </td>
                        <td>
                            <label class="input">
                                <input name="<?php echo $tr;?>_data_col1"  type="text" class="temp-input"  value="<?php echo @$v['answer']['data_col1'];  ?>" >
                            </label>
                        </td>
                        <td>
                            <label class="input">
                                <input name="<?php echo $tr;?>_data_col2"  type="text" class="temp-input"  value="<?php echo @$v['answer']['data_col2'];  ?>" >
                            </label>
                        </td>
                        <?php if ($value['head_col1'] != ''){?>
                            <td>
                                <label class="input">
                                    <input name="<?php echo $tr;?>_data_col3"  type="text" class="temp-input"  value="<?php echo @$v['answer']['data_col3'];  ?>" >
                                </label>
                            </td>
                            <td>
                                <label class="input">
                                    <input name="<?php echo $tr;?>_data_col4"  type="text" class="temp-input"  value="<?php echo @$v['answer']['data_col4'];  ?>" >
                                </label>
                            </td>
                        <?php }
                        ?>
                        <?php if ($value['head_col2'] != ''){?>
                            <td>
                                <label class="input">
                                    <input name="<?php echo $tr;?>_data_col5"  type="text" class="temp-input"  value="<?php echo @$v['answer']['data_col5'];  ?>" >
                                </label>
                            </td>
                            <td>
                                <label class="input">
                                    <input name="<?php echo $tr;?>_data_col6"  type="text" class="temp-input"  value="<?php echo @$v['answer']['data_col6'];  ?>" >
                                </label>
                            </td>
                        <?php }
                        ?>
                        <?php if ($value['head_col3'] != ''){?>
                            <td>
                                <label class="input">
                                    <input name="<?php echo $tr;?>_data_col7"  type="text" class="temp-input"  value="<?php echo @$v['answer']['data_col7'];  ?>" >
                                </label>
                            </td>
                            <td>
                                <label class="input">
                                    <input name="<?php echo $tr;?>_data_col8"  type="text" class="temp-input"  value="<?php echo @$v['answer']['data_col8'];  ?>" >
                                </label>
                            </td>
                        <?php }
                        ?>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </fieldset>

    <?php }else if($value['choice_type'] == 'template10'){?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template10">
            <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table">

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">

                <?php
                foreach($value['list'] as $k =>$v){
//                    alert($v);
                    $tr = 'tr'.generateRandomString();
                    ?>
                    <input name="<?php echo $table;?>_choicedetailid[]" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">
                    <tr class="<?php echo $tr;?>">
                        <td>
                            <input type="text" style="width: 100px; !important;" class="temp-inputText" name="<?php echo $table;?>_list" value="<?php echo $v['list'];?>" readonly="readonly"/>
                            <input type="text" style="width: 200px; !important;" class="temp-inputText" name="<?php echo $table;?>_list2_<?php echo $v['choicedetailid']; ?>[]" value="<?php echo $v['answer']['detail_template10'];?>"/>
                            <input type="radio" name="<?php echo $table;?>_list3_<?php echo $k; ?>_<?php echo $v['choicedetailid']; ?>[]" value="1" <?php echo (@$v['answer']['status_list_template10'] == '1') ? 'checked' : ''; ?> ><i></i>
                            <input type="radio" name="<?php echo $table;?>_list3_<?php echo $k; ?>_<?php echo $v['choicedetailid']; ?>[]" value="2" <?php echo (@$v['answer']['status_list_template10'] == '2') ? 'checked' : ''; ?> ><i></i>
                            <input type="radio" name="<?php echo $table;?>_list3_<?php echo $k; ?>_<?php echo $v['choicedetailid']; ?>[]" value="3" <?php echo (@$v['answer']['status_list_template10'] == '3') ? 'checked' : ''; ?> ><i></i>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>

            </table>

<!--            <table id="<?php /*echo $table;*/?>" style="display:table" class="table">

                <tbody id="<?php /*echo $table;*/?>-sortable" class="ui-sortable">

                <?php
/*                foreach($value['list'] as $k =>$v){
                    $tr = 'tr'.generateRandomString();
                    */?>
                    <input name="<?php /*echo $table;*/?>_choicedetailid[]" id="<?php /*echo $table;*/?>_choicedetailid" type="hidden" value="<?php /*echo $v['choicedetailid'];*/?>">

                    <tr class="<?php /*echo $tr;*/?>">
                        <td>
                            <input type="text" style="width: 100px; !important;" class="temp-inputText" name="<?php /*echo $table;*/?>_list" value="<?php /*echo $v['list'];*/?>" readonly="readonly"/>
                            <input type="text" style="width: 200px; !important;" class="temp-inputText" name="<?php /*echo $table;*/?>_list2" value="<?php /*echo $v['list2'];*/?>" readonly="readonly"/>
                            <input name="<?php /*echo $table;*/?>_level1[]" value="<?php /*echo $v['choicedetailid'];*/?>" type="checkbox" <?php /*echo (@$v['answer']['status1_list_template10'] == '1') ? 'checked' : ''; */?> ><i></i>
                            <input name="<?php /*echo $table;*/?>_level2[]" value="<?php /*echo $v['choicedetailid'];*/?>" type="checkbox" <?php /*echo (@$v['answer']['status2_list_template10'] == '1') ? 'checked' : ''; */?> ><i></i>
                            <input name="<?php /*echo $table;*/?>_level3[]" value="<?php /*echo $v['choicedetailid'];*/?>" type="checkbox" <?php /*echo (@$v['answer']['status3_list_template10'] == '1') ? 'checked' : ''; */?> ><i></i>

                        </td>

                    </tr>


                <?php /*} */?>
                </tbody>

            </table>-->

        </fieldset>

    <?php }else if($value['choice_type'] == 'template11'){?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template11">
            <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table">

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">

                <?php
                foreach($value['list'] as $k =>$v){
                    $tr = 'tr'.generateRandomString();
                    ?>
                    <input name="<?php echo $table;?>_choicedetailid[]" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">

                    <tr id="<?php echo $tr;?>" class="tr-form tr-form-<?php echo $table;?>">
                        <td>
                            <span class="fa fa-minus"></spacn>
                        </td>
                        <td>
                            <label class="input">
                                <input name="<?php echo $table;?>_list[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly>
                            </label>
                        </td>
                        <td class="temp-input">
                            <div style="width:19px; margin:0 auto">
                                <label class="checkbox" >
                                    <input name="<?php echo $table;?>_result[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" <?php echo (@$v['answer']['status_list_template11'] == '1') ? 'checked' : ''; ?> ><i></i>
                                </label>
                            </div>

                        </td>


                    </tr>


                <?php } ?>
                </tbody>

            </table>

        </fieldset>

    <?php }else if($value['choice_type'] == 'template12'){?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template12">
            <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table table-bordered">

                <tr>
                    <td>
                        <label class="input">
                        </label>
                    </td>
                    <td>
                        <label class="input" >
                            <input name="<?php echo $table; ?>_head_col0" type="text" class="template12-inphead" value="<?php echo $value['head_col0']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                        </label>
                    </td>
                    <td>
                        <label class="input" >
                            <input name="<?php echo $table; ?>_head_col1" type="text" class="template12-inphead" value="<?php echo $value['head_col1']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                        </label>
                    </td>

                </tr>

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">

                <?php
                foreach($value['list'] as $k =>$v){
//                    alert($v);
                    $tr = 'tr'.generateRandomString();
                    ?>
                    <input name="<?php echo $table;?>_choicedetailid[]" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">

                    <tr class="<?php echo $tr;?>">
                        <td>
                            <label class="input">
                                <input name="<?php echo $table;?>_list[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly>
                            </label>
                        </td>
                        <td >
                                <label class="checkbox" >
                                    <input name="<?php echo $table;?>_level1[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" <?php echo (@$v['answer']['status_list_template12'] == '1') ? 'checked' : ''; ?> ><i></i>
                                </label>
                        </td>
                        <td>
                            <label class="input">
                                <input name="<?php echo $table;?>_level2_<?php echo $v['choicedetailid'];?>[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo @$v['answer']['data_list_template12'];?>' >
                            </label>
                        </td>

                    </tr>

                <?php } ?>
                </tbody>

            </table>

        </fieldset>

    <?php }else if($value['choice_type'] == 'template13'){?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template13">
            <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table table-bordered">

                <tr>
                    <td>
                        <label class="input">
                        </label>
                    </td>
                    <td>
                        <label class="input" >
                            <input name="<?php echo $table; ?>_head_col0" type="text" class="template13-inphead" value="<?php echo $value['head_col0']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                        </label>
                    </td>
                    <td>
                        <label class="input" >
                            <input name="<?php echo $table; ?>_head_col1" type="text" class="template13-inphead" value="<?php echo $value['head_col1']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                        </label>
                    </td>

                </tr>

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">

                <?php
                foreach($value['list'] as $k =>$v){
//                    alert($v);
                    $tr = 'tr'.generateRandomString();
                    ?>
                    <input name="<?php echo $table;?>_choicedetailid[]" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">

                    <tr class="<?php echo $tr;?>">
                        <td>
                            <label class="input">
                                <input name="<?php echo $table;?>_list[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly>
                            </label>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr class="<?php echo $tr;?>">
                        <td>
                            <label class="input">
                                <input name="<?php echo $table;?>_list[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['sublist'];?>' readonly>
                            </label>
                        </td>
                        <td >
                            <label class="checkbox" >
                                <input name="<?php echo $table;?>_level1[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" <?php echo (@$v['answer']['status_list_template13'] == '1') ? 'checked' : ''; ?> ><i></i>
                            </label>
                        </td>
                        <td>
                            <label class="input">
                                <input name="<?php echo $table;?>_level2_<?php echo $v['choicedetailid'];?>[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo @$v['answer']['data_list_template13'];?>' >
                            </label>
                        </td>
                    </tr>

                <?php } ?>
                </tbody>

            </table>

        </fieldset>

    <?php }else if($value['choice_type'] == 'template14'){?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template14">
            <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table table-bordered">

                <tr>
                    <td>
                        <label class="input">
                        </label>
                    </td>
                    <td>
                        <label class="input" >
                            <input name="<?php echo $table; ?>_head_col0" type="text" class="template12-inphead" value="<?php echo $value['head_col0']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                        </label>
                    </td>
                    <td>
                        <label class="input" >
                            <input name="<?php echo $table; ?>_head_col1" type="text" class="template12-inphead" value="<?php echo $value['head_col1']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                        </label>
                    </td>

                </tr>

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">

                <?php
                foreach($value['list'] as $k =>$v){
//                    alert($v);
                    $tr = 'tr'.generateRandomString();
                    ?>
                    <input name="<?php echo $table;?>_choicedetailid[]" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">

                    <tr class="<?php echo $tr;?>">
                        <td>
                            <label class="input">
                                <input name="<?php echo $table;?>_list[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly>
                            </label>
                        </td>

                        <td >
                            <label class="checkbox" >
                                <input name="<?php echo $table;?>_level1[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" <?php echo (@$v['answer']['status_list_template14_1'] == '1') ? 'checked' : ''; ?> ><i></i>
                            </label>
                        </td>
                        <td >
                            <label class="checkbox" >
                                <input name="<?php echo $table;?>_level2[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" <?php echo (@$v['answer']['status_list_template14_2'] == '1') ? 'checked' : ''; ?> ><i></i>
                            </label>
                        </td>
                    </tr>

                <?php } ?>
                </tbody>

            </table>

        </fieldset>

    <?php }else if($value['choice_type'] == 'template15'){
        ?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template15">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table table-bordered">

                <tr>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col0" type="text" class="template15-inphead" value="<?php echo $value['head_col0']; ?>"
                                   style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center" readonly="readonly">
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col1" type="text" class="template15-inphead" value="<?php echo $value['head_col1']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center" readonly="readonly">
                        </label>
                    </td>
                    <?php if ($value['head_col2']  != ''){ ?>
                        <td>
                            <label class="input">
                                <input name="<?php echo $table; ?>_head_col2" type="text" class="template15-inphead" value="<?php echo $value['head_col2']; ?>"
                                       style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center" readonly="readonly">
                            </label>
                        </td>
                    <?php } ?>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col3" type="text" class="template15-inphead" value="<?php echo $value['head_col3']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center" readonly="readonly">
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col4" type="text" class="template15-inphead" value="<?php echo $value['head_col4']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center" readonly="readonly">
                        </label>
                    </td>

                </tr>

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">
                <?php
                foreach($value['list'] as $k =>$v){
                    $tr = 'tr'.generateRandomString();
                    ?>
                    <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $tr;?>">
                    <input type="hidden" id="<?php echo $tr;?>_has_input" value="0">
                    <input name="<?php echo $table;?>_choicedetailid[]" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">


                    <tr class="<?php echo $tr;?>">
                        <td style="text-align: center;">
                            <span class="temp-input"><?php echo $v['list'];?></span>
                        </td>
                        <td>
                            <label class="input">
                                <input name="<?php echo $table;?>_list1_<?php echo $v['choicedetailid']; ?>[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo (@$v['answer']['data_list_template15_1']); ?>' >
                            </label>
                        </td>
                        <?php if ($value['head_col2']  != ''){ ?>
                            <td>
                                <label class="input">
                                    <input name="<?php echo $table;?>_list2_<?php echo $v['choicedetailid']; ?>[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo (@$v['answer']['data_list_template15_2']); ?>' >
                                </label>
                            </td>

                        <? } ?>
                        <td>
                            <span class="temp-input"><?php echo $v['accept_range'];?></span>
                        </td>
                        <td>
                            <div >
                                <label class="radio" >
                                    <input name="<?php echo $table;?>_list3_<?php echo $k; ?>_<?php echo $v['choicedetailid']; ?>[]" value="1" type="radio" <?php echo (@$v['answer']['status_list_template15_1'] == '1') ? 'checked' : ''; ?> >ใช่<i></i>
                                </label>
                            </div>
                            <div >
                                <label class="radio" >
                                    <input name="<?php echo $table;?>_list3_<?php echo $k; ?>_<?php echo $v['choicedetailid']; ?>[]" value="0" type="radio" <?php echo (@$v['answer']['status_list_template15_1'] == '0') ? 'checked' : ''; ?> >ไม่ใช่<i></i>
                                </label>
                            </div>
                        </td>

                    </tr>
                <?php } ?>

                </tbody>

            </table>

        </fieldset>

    <?php }else if($value['choice_type'] == 'template16'){
        ?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template16">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table table-bordered">

                <tr>
                    <th colspan="4">
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col0" type="text" class="template16-inphead" value="<?php echo $value['head_col0']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center" readonly="readonly">
                        </label>
                    </th>
                </tr>
                <tr>
                    <th >
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col1" type="text" class="template16-inphead" value="<?php echo $value['head_col1']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                        </label>
                    </th>
                    <th >
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col2" type="text" class="template16-inphead" value="<?php echo $value['head_col2']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center" readonly="readonly">
                        </label>
                    </th>
                    <th >
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col3" type="text" class="template16-inphead" value="<?php echo $value['head_col3']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center" readonly="readonly">
                        </label>
                    </th>
                    <th >
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col4" type="text" class="template16-inphead" value="<?php echo $value['head_col4']; ?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center" readonly="readonly">
                        </label>
                    </th>
                </tr>

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">

                <?php
                foreach($value['list'] as $k =>$v){
//                    alert($v);
                    $tr = 'tr'.generateRandomString();
                    ?>
                    <input name="<?php echo $table;?>_choicedetailid[]" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">

                    <tr class="<?php echo $tr;?>">
                        <td>
                            <label class="input">
                                <input name="<?php echo $table;?>_list[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly>
                            </label>
                        </td>

                        <td >
                            <label class="checkbox" >
                                <input name="<?php echo $table;?>_level1[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" <?php echo (@$v['answer']['status_list_template16_1'] == '1') ? 'checked' : ''; ?> ><i></i>
                            </label>
                        </td>
                        <td >
                            <label class="checkbox" >
                                <input name="<?php echo $table;?>_level2[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" <?php echo (@$v['answer']['status_list_template16_2'] == '1') ? 'checked' : ''; ?> ><i></i>
                            </label>
                        </td>
                        <td >
                            <label class="checkbox" >
                                <input name="<?php echo $table;?>_level3[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" <?php echo (@$v['answer']['status_list_template16_3'] == '1') ? 'checked' : ''; ?> ><i></i>
                            </label>
                        </td>
                    </tr>

                <?php } ?>
                </tbody>

            </table>

        </fieldset>


    <?php }else if($value['choice_type'] == 'template17'){
        ?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template17">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table table-bordered">

                <tr>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col0" type="text" class="template16-inphead" value="<?php echo $value['head_col0'];?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center" readonly="readonly">
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col1" type="text" class="template16-inphead" value="<?php echo $value['head_col1'];?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center" readonly="readonly">
                        </label>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col2" type="text" class="template16-inphead" value="<?php echo $value['head_col2'];?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col3" type="text" class="template16-inphead" value="<?php echo $value['head_col3'];?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                        </label>
                    </td>
                </tr>

                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">

                <?php
                foreach($value['list'] as $k =>$v){
//                    alert($v);
                    $tr = 'tr'.generateRandomString();
                    ?>
                    <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $tr;?>">
                    <input name="<?php echo $table;?>_choicedetailid[]" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">

                    <tr class="<?php echo $tr;?>">

                        <td >
                            <label class="checkbox" >
                                <input name="<?php echo $table;?>_level1[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" <?php echo (@$v['answer']['status_list_template17_1'] == '1') ? 'checked' : ''; ?> ><i></i>
                            </label>
                            <span class="temp-input" style="padding-left:50px;"><?php echo $v['list'];?></span>

                        </td>
                        <td >
                            <label class="checkbox" >
                                <input name="<?php echo $table;?>_level2[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" <?php echo (@$v['answer']['status_list_template17_2'] == '1') ? 'checked' : ''; ?> ><i></i>
                            </label>
                            <span class="temp-input" style="padding-left:50px;"><?php echo $v['sublist'];?></span>

                        </td>
                    </tr>

                <?php } ?>
                </tbody>

                <tfoot>
                <tr>
                    <td>
                        <label class="input">
                            <span class="temp-input"><?php echo $value['head_col4'];?></span>
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <span class="temp-input"><?php echo $value['head_col5'];?></span>

                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_list1" type="text" class="template17-inphead" placeholder="Time .......... Minutes" value="<?php echo @$v['answer']['data_list_template17_1'];?>"
                                   style="text-align:left; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " >
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_list2" type="text" class="template17-inphead" placeholder="Time .......... Minutes" value="<?php echo @$v['answer']['data_list_template17_2'];?>"
                                   style="text-align:left; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " >
                        </label>
                    </td>
                </tr>

                </tfoot>

            </table>

        </fieldset>

    <?php }else if($value['choice_type'] == 'template18'){
        ?>

        <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template18">
            <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
            <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">

            <div class="row">
                <section class="col col-10" style="margin-bottom:5px;">
                    <label class="input">
                        <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                    </label>
                </section>

                <section class="col col-2" style="margin-bottom:5px;">
                    <div class="btn-group" style="float:right;">
                        <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                    </div>
                </section>
            </div>

            <table id="<?php echo $table;?>" style="display:table" class="table table-bordered">

                <tr>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_parameter" type="text" class="template16-inphead" value="Procedure"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center; " readonly="readonly">
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_parameter" type="text" class="template16-inphead" value="Action (/)"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly="readonly">
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_parameter" type="text" class="template16-inphead" value="Comment"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly="readonly">
                        </label>
                    </td>
                </tr>
                <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">
                <?php
                foreach($value['list'] as $k =>$v){
                    $tr = 'tr'.generateRandomString();
                    ?>
                    <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $tr;?>">
                    <input type="hidden" id="<?php echo $tr;?>_has_input" value="0">
                    <input name="<?php echo $table;?>_choicedetailid[]" id="<?php echo $table;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">


                    <tr class="<?php echo $tr;?>">
                        <td style="text-align: center;">
                            <span class="temp-input"><?php echo $v['list'];?></span>
                        </td>
                        <td>
                            <div >
                                <label class="radio" >
                                    <input name="<?php echo $table;?>_list2_<?php echo $k; ?>_<?php echo $v['choicedetailid']; ?>[]" value="1" type="radio" <?php echo (@$v['answer']['status_list_template18_1'] == '1') ? 'checked' : ''; ?> >Yes<i></i>
                                </label>
                            </div>
                            <div >
                                <label class="radio" >
                                    <input name="<?php echo $table;?>_list2_<?php echo $k; ?>_<?php echo $v['choicedetailid']; ?>[]" value="0" type="radio" <?php echo (@$v['answer']['status_list_template18_1'] == '0') ? 'checked' : ''; ?> >No<i></i>
                                </label>
                            </div>
                        </td>

                        <td>
                            <label class="input">
                                <input name="<?php echo $table;?>_list1_<?php echo $v['choicedetailid']; ?>[]" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo (@$v['answer']['data_list_template18_2']); ?>' >
                            </label>
                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </fieldset>


    <?php }



   if($value['choice_type']== 'calibrate'){

    $colspan_left = 2;
    $colspan_right = 4;
    $col = 1;

    if($value['head_col3'] != ''){
        $colspan_left++;
        $col++;
    }
    if($value['head_col4'] != ''){
        $colspan_left++;
        $col++;
    }
    if($value['head_col5'] != ''){
        $colspan_right++;
        $col++;
    }
    if($value['head_col6'] != ''){
        $colspan_right++;
        $col++;
    }
    if($value['head_col7'] != ''){
        $colspan_right++;
        $col++;
    }
    if($value['head_col8'] != ''){
        $colspan_right++;
        $col++;
    }
    if($value['head_col9'] != ''){
        $colspan_right++;
        $col++;
    }

    ?>
       <fieldset id="remove_<?php echo $table;?>" class="cal-form-box" style="padding-bottom: 20px; position: relative; left: 0px; top: 0px;">
        <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table; ?>" />
        <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="calibrate">
        <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid']; ?>">
        <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">
        <div class="row">

           <section class="col col-10" style="margin-bottom:5px;">
               <label class="input"> <!-- <i class="icon-append fa" style="right:auto; left:5px; border-left:0px;">Parameter:</i> -->
                   <input name="<?php echo $table;?>_parameter" id="<?php echo $table;?>_parameter" type="text" style="/*padding-left:75px;*/ border:0px; border-bottom:1px dotted #CCCCCC; color:#666;/*color:#0000FF;*/font-weight:bold;" value="<?php echo $value['choice_title'];?>" class="user-success" readonly="readonly">
               </label>
           </section>

           <section class="col col-2" style="margin-bottom:5px;">
               <div class="btn-group" style="float:right;">
                   <a href="javascript:ShowHideTable('caltb-<?php echo $table;?>');" id="caltb-<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
               </div>
           </section>

         </div>


      <div id="caltb-<?php echo $table;?>" style="display:block">

       <table id="<?php echo $table;?>" class="table table-bordered table-form table-cal">
            <tbody>
              <tr>

               <!-- <th colspan="4" style="padding:5px 2px 5px 2px; border-bottom:0px;"> --><!--  -->
                <th colspan="<?php echo $colspan_left;?>" style="padding:5px 2px 5px 2px; border-bottom:0px;">
                   <div class="row" style="text-align:center">Unit</div>
               </th>

               <!-- <th style="padding:5px 2px 5px 2px; border-bottom:0px;" colspan="9"> --><!--  -->
                <th style="padding:5px 2px 5px 2px; border-bottom:0px;" colspan="<?php echo $colspan_right;?>">
                   <div class="row" style="text-align:center">Tolerance
                       <span id="<?php echo $table;?>-tolerance-error" style="font-weight:normal;">
                       <?php

                          if($value['tolerance_type'] == 1){
                           echo 'ระหว่าง Min และ Max';
                          }else if($value['tolerance_type'] == 2){
                           echo '&gt; มากกว่า';
                          }else if($value['tolerance_type'] == 3){
                           echo '≥ มากกว่าหรือเท่ากับ';
                          }else if($value['tolerance_type'] == 4){
                           echo '&lt; น้อยกว่า';
                          }else if($value['tolerance_type'] == 5){
                           echo '≤ น้อยกว่าหรือเท่ากับ';
                          }else if($value['tolerance_type'] == 6){
                           echo 'N/A';
                          }
                       ?>
                       </span>
                   </div>
               </th>
             </tr>
             <tr>

               <!-- <th colspan="4" style="border-top:0px;"> --><!--  -->
                <th colspan="<?php echo $colspan_left;?>" style="border-top:0px;">
                   <div id="<?php echo $table;?>_unit_box">
                        <label class="input" style="width:40%; margin:0 auto; text-align:center;">

                          <input name="<?php echo $table;?>_unit" id="<?php echo $table;?>_unit" class="set-tole-amount user-error" tbid="<?php echo $table;?>" type-id="5" type="text" style="border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; color:#666; font-weight: bold;" value="<?php echo $value['unit'];?>" number="true" autocomplete="off" aria-invalid="true" readonly="readonly">

                       </label>

                   </div>
               </th>

               <!-- <th colspan="9" id="<?php //echo $table;?>-set-tolerance-form-box" style="border-top:0px;"> --><!--  -->
                <th colspan="<?php echo $colspan_right;?>" id="<?php echo $table;?>-set-tolerance-form-box" style="border-top:0px;">

                  <?php

                   if($value['tolerance_type'] == 1){?>


                     <div class="row">
                       <div class="col col-lg-4 col-md-12">
                           <input name="<?php echo $table;?>_check_tolerance_unit" id="<?php echo $table;?>_check_tolerance_unit" class="<?php echo $table;?>_check_tolerance user-success" value="1" type="checkbox" style="float:left; margin-left:10px; margin-top:12px;" <?php echo ($value['check_tolerance_unit'] == '1') ? 'checked' : '' ;?> disabled="disabled">
                           <label class="input" style="margin-left:25px;">
                               <i class="icon-append fa" style="right:auto; left:5px; border-left:0px;">±</i>
                               <i class="icon-append fa" style="width:50px; border:0px;">Value</i>
                               <input name="<?php echo $table;?>_tolerance_unit" id="<?php echo $table;?>_tolerance_unit" type="text" style="padding-left:30px; border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; color:#666;" value="<?php echo $value['tolerance_unit'];?>" number="true" class="user-success" readonly="readonly" >
                           </label>
                       </div>

                       <div class="col col-lg-4 col-md-12">
                           <input name="<?php echo $table;?>_check_tolerance_percent" id="<?php echo $table;?>_check_tolerance_percent" class="<?php echo $table;?>_check_tolerance" value="1" type="checkbox" style="float:left; margin-left:10px; margin-top:12px;" <?php echo ($value['check_tolerance_percent'] == '1') ? 'checked' : '' ;?> disabled="disabled">
                           <label class="input" style="margin-left:25px;">
                               <i class="icon-append fa" style="right:auto; left:5px; border-left:0px;">±</i>
                               <i class="icon-append fa" style="width:50px; border:0px;">%</i>
                               <input name="<?php echo $table;?>_tolerance_percent" id="<?php echo $table;?>_tolerance_percent" type="text" style="padding-left:30px; border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; color:#666;" value="<?php echo $value['tolerance_percent'];?>" number="true" class="user-success" readonly="readonly">
                           </label>
                       </div>

                       <div class="col col-lg-4 col-md-12">
                           <input name="<?php echo $table;?>_check_tolerance_fso" id="<?php echo $table;?>_check_tolerance_fso" class="<?php echo $table;?>_check_tolerance" value="1" type="checkbox" style="float:left; margin-left:10px; margin-top:12px;" <?php echo ($value['check_tolerance_fso'] == '1') ? 'checked' : '' ;?> disabled="disabled">
                               <label class="input" style="margin-left:25px;">
                               <i class="icon-append fa" style="right:auto; left:5px; border-left:0px;">±</i>
                               <i class="icon-append fa" style="width:50px; border:0px;">% FSO</i>
                               <input name="<?php echo $table;?>_tolerance_fso_percent" id="<?php echo $table;?>_tolerance_fso_percent" type="text" style="padding-left:30px; border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; color:#666;" value="<?php echo $value['tolerance_fso_percent'];?>" number="true" readonly="readonly">
                           </label>
                       </div>

                     </div>


                   <?php }else if($value['tolerance_type'] == 2){ ?>

                     <div class="row">
                       <div class="col col-3">
                         &nbsp;
                       </div>
                         <div class="col col-2" style="text-align:right">
                         <label class="input" style="margin-top:6px;">&gt;</label>
                       </div>
                       <div class="col col-3">
                         <label class="input">
                           <input name="<?php echo $table;?>_set_tole_amount" id="<?php echo $table;?>_set_tole_amount" class="set-tole-amount user-error" tbid="<?php echo $table;?>" type-id="2" type="text" style="border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; color:#0000FF;" value="<?php echo $value['set_tole_amount']; ?>" required="" number="true">
                         </label>
                       </div>
                     </div>

                    <?php }else if($value['tolerance_type'] == 3){ ?>

                     <div class="row">
                       <div class="col col-3">
                         &nbsp;
                       </div>
                         <div class="col col-2" style="text-align:right">
                         <label class="input" style="margin-top:6px;">≥</label>
                       </div>
                       <div class="col col-3">
                         <label class="input">
                           <input name="<?php echo $table;?>_set_tole_amount" id="<?php echo $table;?>_set_tole_amount" class="set-tole-amount user-error" tbid="<?php echo $table;?>" type-id="3" type="text" style="border:0px; border-bottom:1px dotted #CCCCCC; text-align:center;  color:#0000FF;" value="<?php echo $value['set_tole_amount']; ?>" number="true">
                         </label>
                       </div>
                     </div>

                    <?php }else if($value['tolerance_type'] == 4){ ?>

                        <div class="row">
                          <div class="col col-3">
                            &nbsp;
                          </div>
                            <div class="col col-2" style="text-align:right">
                            <label class="input" style="margin-top:6px;">&lt;</label>
                          </div>
                          <div class="col col-3">
                            <label class="input">
                              <input name="<?php echo $table;?>_set_tole_amount" id="<?php echo $table;?>_set_tole_amount" class="set-tole-amount user-error" tbid="<?php echo $table;?>" type-id="4" type="text" style="border:0px; border-bottom:1px dotted #CCCCCC; text-align:center;  color:#0000FF;" value="<?php echo $value['set_tole_amount']; ?>" number="true">
                            </label>
                          </div>
                        </div>

                    <?php }else if($value['tolerance_type'] == 5){ ?>
                        <div class="row">
                          <div class="col col-3">
                            &nbsp;
                          </div>
                            <div class="col col-2" style="text-align:right">
                            <label class="input" style="margin-top:6px;">≤</label>
                          </div>
                          <div class="col col-3">
                            <label class="input">
                              <input name="<?php echo $table;?>_set_tole_amount" id="<?php echo $table;?>_set_tole_amount" class="set-tole-amount user-error" tbid="<?php echo $table;?>" type-id="5" type="text" style="border:0px; border-bottom:1px dotted #CCCCCC; text-align:center;  color:#0000FF;" value="<?php echo $value['set_tole_amount']; ?>" number="true" readonly="readonly">
                            </label>
                          </div>
                        </div>

                    <?php }else if($value['tolerance_type'] == 6){ ?>

                     <div style="text-align:center; font-weight:normal"><em>N/A</em></div>

                    <?php }

                  ?>

               </th>
             </tr>

             <tr>
               <td colspan="2">
                   <label class="input">
                       <input name="<?php echo $table;?>_uncer_setting" type="text" class="table-form-head-text user-success" value="<?php echo $value['uncer_setting']; ?>" readonly="readonly" placeholder="UUC* Setting">
                   </label>
               </td>

               <!-- <td colspan="9"> --><!--  -->
                <td colspan="<?php echo $col;?>">
                   <label class="input">
                       <input name="<?php echo $table;?>_uncer_reading" type="text" class="table-form-head-text user-success" value="<?php echo $value['uncer_reading']; ?>" readonly="readonly" placeholder="Standard Reading">
                   </label>
               </td>
               <td colspan="3" style="text-align:center; font-weight:bold;">Acception</td><!--  -->
             </tr>

             <tr>
              <td>
                   <label class="input">
                       <input name="<?php echo $table;?>_head_col0" type="text" class="table-form-head-text user-success" value="<?php echo $value['head_col0']; ?>" readonly="readonly" >
                   </label>
               </td>

               <td>
                   <label class="input">
                       <input name="<?php echo $table;?>_head_col1" type="text" class="table-form-head-text user-success" value="<?php echo $value['head_col1']; ?>" readonly="readonly" placeholder="Setting">
                   </label>
               </td>

               <td>
                   <label class="input">
                       <input name="<?php echo $table;?>_head_col2" type="text" class="table-form-head-text user-success" value="<?php echo $value['head_col2']; ?>" readonly="readonly" placeholder="Reading 1">
                   </label>
               </td>

               <?php if($value['head_col3'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $table;?>_head_col3" type="text" class="table-form-head-text user-success" value="<?php echo $value['head_col3']; ?>" readonly="readonly" placeholder="Reading 2">
                   </label>
               </td>
               <?php } ?>
               <?php if($value['head_col4'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $table;?>_head_col4" type="text" class="table-form-head-text" value="<?php echo $value['head_col4']; ?>" readonly="readonly" placeholder="Reading 3">
                   </label>
               </td>
               <?php } ?>
               <?php if($value['head_col5'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $table;?>_head_col5" type="text" class="table-form-head-text" value="<?php echo $value['head_col5']; ?>" readonly="readonly" placeholder="Reading 4">
                   </label>
               </td>
               <?php } ?>
               <?php if($value['head_col6'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $table;?>_head_col6" type="text" class="table-form-head-text" value="<?php echo $value['head_col6']; ?>" readonly="readonly" placeholder="Reading 5">
                   </label>
               </td>
               <?php } ?>
               <?php if($value['head_col7'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $table;?>_head_col7" type="text" class="table-form-head-text" value="<?php echo $value['head_col7']; ?>" readonly="readonly" placeholder="Reading 6">
                   </label>
               </td>
               <?php } ?>
               <?php if($value['head_col8'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $table;?>_head_col8" type="text" class="table-form-head-text" value="<?php echo $value['head_col8']; ?>" readonly="readonly" placeholder="Reading 7">
                   </label>
               </td>
               <?php } ?>
               <?php if($value['head_col9'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $table;?>_head_col9" type="text" class="table-form-head-text" style="border-color: 0px;" value="<?php echo $value['head_col9']; ?>" readonly="readonly" placeholder="Reading 8">
                   </label>
               </td>
               <?php } ?>
               <!-- <td><label class="input" style="text-align:center; font-size:95%;">Standard<br>Resolution</label></td> -->
               <td><label class="input" style="text-align:center">Pass</label></td>
               <td><label class="input" style="text-align:center">Fail</label></td>
               <td><label class="input" style="text-align:center;">Comment</label></td>

              </tr>

             </tbody>
            <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">

            <?php foreach($value['list'] as $k =>$v){

             $tr = 'tr'.generateRandomString();
             ?>
            <tr id="<?php echo $tr;?>" class="tr-form tr-form-<?php echo $table;?>">
               <td style="position:relative;">
                   <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $tr;?>">
                   <input type="hidden" id="<?php echo $tr;?>_has_input" value="0">
                   <input type="hidden" id="<?php echo $tr;?>_choicedetailid" name="<?php echo $tr;?>_choicedetailid" value="<?php echo $v['choicedetailid']; ?>">
                   <label class="input">

                     <!-- <input name="<?php echo $tr;?>_col0" id="<?php echo $tr;?>_col0" type="text" class="table-form-input-text set_<?php echo $tr;?> user-success" value="<?php echo $v['col0'];?>" readonly="readonly">-->
					 <!-- <textarea name="<?php echo $tr;?>_col0" id="<?php echo $tr;?>_col0" class="table-form-input-text set_<?php echo $tr;?> user-success" style="resize: none;" readonly="readonly"><?php echo $v['col0'];?></textarea>-->
					 <?php echo $v['col0'];?>
				   </label>
               </td>
                <td>
                   <label class="input">

					 <!-- <input name="<?php echo $tr;?>_col1" id="<?php echo $tr;?>_col1" type="text" class="table-form-input-text set_<?php echo $tr;?> user-success" value="<?php echo $v['col1'];?>" readonly="readonly">-->
					 <!-- <textarea name="<?php echo $tr;?>_col1" id="<?php echo $tr;?>_col1" class="table-form-input-text set_<?php echo $tr;?> user-success" style="resize: none;" readonly="readonly"><?php echo $v['col1'];?></textarea>-->
					 <?php echo $v['col1'];?>
				   </label>
               </td>
               <td>
                   <label class="input">
                       <input name="<?php echo $tr;?>_col2" id="<?php echo $tr;?>_col2" type="text" class="table-form-input-text read_<?php echo $tr;?> warning-over" tb-id="<?php echo $table;?>" warning-id="<?php echo $tr;?>" value="<?php echo (@$v['answer']['data_col1'] == '0.00') ? '' : @$v['answer']['data_col1'];  ?>" number="true">
                   </label>
               </td>

               <?php if($value['head_col3'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $tr;?>_col3" id="<?php echo $tr;?>_col3" type="text" class="table-form-input-text read_<?php echo $tr;?> warning-over" tb-id="<?php echo $table;?>" warning-id="<?php echo $tr;?>" value="<?php echo (@$v['answer']['data_col2'] == '0.00') ? '' : @$v['answer']['data_col2'];  ?>" number="true">
                   </label>
               </td>
               <?php } ?>
               <?php if($value['head_col4'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $tr;?>_col4" id="<?php echo $tr;?>_col4" type="text" class="table-form-input-text read_<?php echo $tr;?> warning-over" tb-id="<?php echo $table;?>" warning-id="<?php echo $tr;?>" value="<?php echo (@$v['answer']['data_col3'] == '0.00') ? '' : @$v['answer']['data_col3'];  ?>" number="true">
                   </label>
               </td>
               <?php } ?>
               <?php if($value['head_col5'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $tr;?>_col5" id="<?php echo $tr;?>_col5" type="text" class="table-form-input-text read_<?php echo $tr;?> warning-over" tb-id="<?php echo $table;?>" warning-id="<?php echo $tr;?>" value="<?php echo (@$v['answer']['data_col4'] == '0.00') ? '' : @$v['answer']['data_col4'];  ?>" number="true">
                   </label>
               </td>
               <?php } ?>
               <?php if($value['head_col6'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $tr;?>_col6" id="<?php echo $tr;?>_col6" type="text" class="table-form-input-text read_<?php echo $tr;?> warning-over" tb-id="<?php echo $table;?>" warning-id="<?php echo $tr;?>" value="<?php echo (@$v['answer']['data_col5'] == '0.00') ? '' : @$v['answer']['data_col5'];  ?>" number="true">
                   </label>
               </td>
               <?php } ?>
               <?php if($value['head_col7'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $tr;?>_col7" id="<?php echo $tr;?>_col7" type="text" class="table-form-input-text read_<?php echo $tr;?> warning-over" tb-id="<?php echo $table;?>" warning-id="<?php echo $tr;?>" value="<?php echo (@$v['answer']['data_col6'] == '0.00') ? '' : @$v['answer']['data_col6'];  ?>" number="true">
                   </label>
               </td>
               <?php } ?>
               <?php if($value['head_col8'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $tr;?>_col8" id="<?php echo $tr;?>_col8" type="text" class="table-form-input-text read_<?php echo $tr;?> warning-over" tb-id="<?php echo $table;?>" warning-id="<?php echo $tr;?>" value="<?php echo (@$v['answer']['data_col7'] == '0.00') ? '' : @$v['answer']['data_col7'];  ?>" number="true">
                   </label>
               </td>
               <?php } ?>
               <?php if($value['head_col9'] != ''){ ?>
               <td>
                   <label class="input">
                       <input name="<?php echo $tr;?>_col9" id="<?php echo $tr;?>_col9" type="text" class="table-form-input-text read_<?php echo $tr;?> warning-over" tb-id="<?php echo $table;?>" warning-id="<?php echo $tr;?>" value="<?php echo (@$v['answer']['data_col8'] == '0.00') ? '' : @$v['answer']['data_col8'];  ?>" number="true">
                   </label>
               </td>
               <?php } ?>
               <!-- <td>
                   <label class="input">
                       <input name="<?php //echo $tr;?>_std_resolution" id="<?php //echo $tr;?>_std_resolution" type="text" class="table-form-input-text resolution_<?php //echo $tr;?> user-success" value="<?php //echo $v['std_resolution'];?>" number="true" readonly="readonly">
                   </label>
               </td> -->
               <td style="width: 10%;">
                   <!-- <label class="input">
                       <input name="<?php echo $tr;?>_min" id="<?php echo $tr;?>_min" type="text" class="table-form-input-text caltol_<?php echo $tr;?>"
                       value="<?php echo $v['min'];?>" readonly="readonly">
                   </label> -->
                  <div style="width:19px; margin:0 auto">
                    <label class="radio" id="<?php echo $tr;?>_result_pass">
                    <input class="<?php echo $table;?>-pass click-reset" id="<?php echo $tr;?>-recheck-pass" name="<?php echo $tr;?>_result" value="Pass" type="radio" <?php echo (@$v['answer']['cal_status'] == 'Pass') ? 'checked' : ''; ?>><i></i>
                    </label>
                  </div>
               </td>
               <td style="width: 10%;">
                   <!-- <label class="input">
                       <input name="<?php //echo $tr;?>_max" id="<?php //echo $tr;?>_max" type="text" class="table-form-input-text caltol_<?php //echo $tr;?>"
                       value="<?php //echo $v['max'];?>" readonly="readonly">
                   </label> -->
                   <div style="width:19px; margin:0 auto">
                    <label class="radio" id="<?php echo $tr;?>_result_fail">
                    <input class="<?php echo $table;?>-fail click-reset" id="<?php echo $tr;?>-recheck-fail" name="<?php echo $tr;?>_result" value="Fail" type="radio" <?php echo (@$v['answer']['cal_status'] == 'Fail') ? 'checked' : ''; ?>><i></i>
                    </label>
                  </div>
               </td>
               <td style="text-align:center;vertical-align: middle;">
                  <label class="input">
                    <input name="<?php echo $tr;?>_comment" type="text" class="pm-left" value="<?php echo @$v['answer']['cal_comment']; ?>" >
                  </label>
               </td>

            </tr>

            <?php } ?>

         </tbody>

       </table>

    </div>

    <script>
    jQuery('#<?php echo $table;?>_tolerance_type').change(function(){
        var tbid = jQuery(this).attr('tb-id');
        set_tolerance_type(tbid);
        CalculateTolerance(tbid);
    });

      jQuery("#<?php echo $table;?>_tolerance_unit").keyup(function(){ CalculateTolerance('<?php echo $table;?>'); });
      jQuery("#<?php echo $table;?>_tolerance_percent").keyup(function(){ CalculateTolerance('<?php echo $table;?>'); });
      jQuery("#<?php echo $table;?>_tolerance_fso_percent").keyup(function(){ CalculateTolerance('<?php echo $table;?>'); });
      jQuery("#<?php echo $table;?>_tolerance_fso_val").keyup(function(){ CalculateTolerance('<?php echo $table;?>'); });
      jQuery(".<?php echo $table;?>_check_tolerance").change(function(){ CalculateTolerance('<?php echo $table;?>'); });

    </script>

</fieldset>


<?php }else if($value['choice_type']== 'pm'){ ?>


<fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
      <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
      <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="pm">
      <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid']; ?>">
      <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid']; ?>">
          <div class="row">
          <section class="col col-10" style="margin-bottom:5px;">
              <label class="input">
                  <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#666;/*color:#0000FF;*/ font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
              </label>
          </section>

          <section class="col col-2" style="margin-bottom:5px;">
              <div class="btn-group" style="float:right;">
                  <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
              </div>
          </section>
      </div>

      <table id="<?php echo $table;?>" style="display:table" class="table table-bordered table-form table-pm">
          <thead>
              <tr>
                <td style="width:5%" class="pm-head">No. </td>
                <td style="width:40%" class="pm-head">List</td>
                <td style="width:10%" class="pm-head"><a href="javascript:PMSelectAllPass('<?php echo $table;?>');">Pass</a></td>
                <td style="width:10%" class="pm-head"><a href="javascript:PMSelectAllFail('<?php echo $table;?>');">Fail</a></td>
                <td style="width:10%" class="pm-head"><a href="javascript:PMSelectAllNone('<?php echo $table;?>');">N/A</a></td>
                <td style="width:25%" class="pm-head" >Comment</td>
              </tr>
          </thead>
          <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">


         <?php
            foreach($value['list'] as $k =>$v){
             $tr = 'tr'.generateRandomString();
         ?>

          <tr id="<?php echo $tr;?>" class="tr-form tr-form-<?php echo $table;?>">
             <td width="5%" style="position:relative;">
                <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $tr;?>">
                <label class="input">
                <input name="<?php echo $tr;?>_choicedetailid" id="<?php echo $tr;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">
                <input name="<?php echo $tr;?>_no" id="<?php echo $tr;?>_no" type="text" class="pm-center" value="<?php echo $v['sequence_detail'];?>" readonly="readonly">
                </label>
             </td>
             <td width="40%">
                <label class="input">
                <textarea name="<?php echo $tr;?>_list" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" style="resize: none;" readonly="readonly"><?php echo $v['list'];?></textarea>
				        </label>
             </td>
             <td width="10%" class="pm-list-boxs" box-id="<?php echo $tr;?>">
                <div style="width:19px; margin:0 auto">
                   <label class="radio" id="<?php echo $tr;?>_result_pass">
                   <input class="<?php echo $table;?>-pass click-reset" id="<?php echo $tr;?>-recheck-pass" name="<?php echo $tr;?>_result" value="Pass" type="radio" <?php echo (@$v['answer']['pm_status'] == 'Pass') ? 'checked' : ''; ?> ><i></i>
                   </label>
                </div>
             </td>
             <td width="10%">
                <div style="width:19px; margin:0 auto">
                   <label class="radio" id="<?php echo $tr;?>_result_fail">
                   <input class="<?php echo $table;?>-fail click-reset" id="<?php echo $tr;?>-recheck-fail" name="<?php echo $tr;?>_result" value="Fail" type="radio" <?php echo (@$v['answer']['pm_status'] == 'Fail') ? 'checked' : ''; ?>><i></i>
                   </label>
                </div>
             </td>
             <td width="10%">
                <div style="width:19px; margin:0 auto">
                   <label class="radio" id="<?php echo $tr;?>_result_none">
                   <input class="<?php echo $table;?>-none click-reset" id="<?php echo $tr;?>-recheck-none" name="<?php echo $tr;?>_result" value="None" type="radio" <?php echo (@$v['answer']['pm_status'] == 'None') ? 'checked' : ''; ?>><i></i>
                   </label>
                </div>
             </td>
             <td>
                <label class="input">
                <input name="<?php echo $tr;?>_comment" type="text" class="pm-left" value="<?php echo (@$v['answer']['comment'] != '') ? @$v['answer']['comment'] : ''; ?>" >
                </label>
             </td>
          </tr>

      <?php } ?>
   </tbody>

   </table>

</fieldset>


<?php }else if($value['choice_type']== 'list'){ ?>

<fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
      <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
      <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="list">
      <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid']; ?>">
      <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid']; ?>">
          <div class="row">
          <section class="col col-10" style="margin-bottom:5px;">
              <label class="input">
                  <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#666;/*color:#0000FF;*/ font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
              </label>
          </section>

          <section class="col col-2" style="margin-bottom:5px;">
              <div class="btn-group" style="float:right;">
                  <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
              </div>
          </section>
      </div>

      <table id="<?php echo $table;?>" style="display:table" class="table table-bordered table-form table-pm">
          <thead>
              <tr>
                  <!-- <td style="width:5%" class="pm-head"><a href="javascript:ResetPMRowNumber('<?php echo $table;?>')">No. <i class="fa fa-arrow-down"></i></a></td> -->
                  <td style="width:5%" class="pm-head">No. </td>
                  <td style="width:55%" class="pm-head">List</td>
                  <td style="width:20%" class="pm-head" colspan="1">Done</td>
                  <td style="width:20%" class="pm-head" colspan="2">N/A</td>
              </tr>
          </thead>
          <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">


         <?php
            foreach($value['list'] as $k =>$v){
             $tr = 'tr'.generateRandomString();
         ?>

          <tr id="<?php echo $tr;?>" class="tr-form tr-form-<?php echo $table;?>">
             <td width="5%" style="position:relative;">
                <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $tr;?>">
                <label class="input">
                <input name="<?php echo $tr;?>_choicedetailid" id="<?php echo $tr;?>_choicedetailid" type="hidden" value="<?php echo $v['choicedetailid'];?>">
                <input name="<?php echo $tr;?>_no" id="<?php echo $tr;?>_no" type="text" class="pm-center" value="<?php echo $v['sequence_detail'];?>" readonly="readonly">
                </label>
             </td>
             <td width="55%">
                <label class="input">
                <!--<input name="<?php echo $tr;?>_list" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly="readonly">-->
                <textarea name="<?php echo $tr;?>_list" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" style="resize: none;" readonly="readonly"><?php echo $v['list'];?></textarea>
				        </label>
             </td>
             <td width="20%" style="text-align:center">
                <div style="margin-left: 50%;">
                  <!-- <div style="text-align:center;margin-left: auto;"> -->
                   <label class="radio" id="<?php echo $tr; ?>_result_none">
                   <input class="<?php echo $table;?>-none click-reset" id="<?php echo $tr; ?>-recheck-none" name="<?php echo $tr; ?>_result" value="Pass" type="radio" <?php echo (@$v['answer']['list_status'] == 'Pass') ? 'checked' : ''; ?> >
                   <i></i>
                   <!-- <i style="left: auto !important; display: initial !important;"></i> -->
                   </label>
                </div>
             </td>
             <td width="20%">
                <div style="margin-left: 50%;">
                  <!-- <div style="text-align:center;margin-left: auto;"> -->
                   <label class="radio" id="<?php echo $tr; ?>_result_none">
                   <input class="<?php echo $table;?>-none click-reset" id="<?php echo $tr; ?>-recheck-none" name="<?php echo $tr; ?>_result" value="None" type="radio" <?php echo (@$v['answer']['list_status'] == 'None') ? 'checked' : ''; ?> >
                   <i></i>
                   <!-- <i style="left: auto !important; display: initial !important;"></i> -->
                   </label>
                </div>
                <!-- <label class="input">
                <input name="<?php //echo $tr;?>_comment" type="text" class="pm-left" value="<?php echo @$v['answer']['list_comment']; ?>" >
                </label> -->
             </td>

          </tr>

      <?php } ?>
   </tbody>

   </table>

</fieldset>



<?php }else if($value['choice_type']== 'note'){

    $tr = 'tr'.generateRandomString();
  ?>


<fieldset id="remove_<?php echo $table;?>" class="note-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
    <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="note">
    <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">
    <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
        <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input">
                <input name="<?php echo $table;?>_headtext" type="text" value="<?php echo $value['choice_title'];?>" class="note-head user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#666;/*color:#0000FF;*/ font-weight:bold;" readonly="readonly">
            </label>
        </section>

        <section class="col col-2" style="margin-bottom:5px;">
           <div class="btn-group" style="float:right;">
               <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
           </div>
       </section>
    </div>
        <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $tr;?>">
        <input type="hidden" id="<?php echo $tr;?>_has_input" value="0">
        <input name="<?php echo $tr;?>_choicedetailid" id="<?php echo $tr;?>_choicedetailid" type="hidden" value="<?php echo $value['list'][0]['choicedetailid'];?>">
        <section id="<?php echo $table;?>" class="table table-note">
        <label class="textarea textarea-resizable">
            <textarea name="<?php echo $table;?>_notetext" id="<?php echo $table;?>_notetext" rows="3" class="custom-scroll note-input user-success"><?php echo (@$value['list'][0]['answer']['comment'] != '') ? @$value['list'][0]['answer']['comment'] : ''; ?></textarea>
        </label>
    </section>
</fieldset>

<?php }else if($value['choice_type']== 'remark'){

    $tr = 'tr'.generateRandomString();
?>

  <fieldset id="remove_<?php echo $table;?>" class="note-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
    <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="remark">
    <input type="hidden" name="<?php echo $table;?>_choiceid" id="<?php echo $table;?>_choiceid" value="<?php echo $value['choiceid'];?>">
    <input type="hidden" name="<?php echo $table;?>_inspectiontemplateid" id="<?php echo $table;?>_inspectiontemplateid" value="<?php echo $value['inspectiontemplateid'];?>">
        <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input">
                <input name="<?php echo $table;?>_headtext" type="text" value="<?php echo $value['choice_title'];?>" class="note-head user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#666;/*color:#0000FF;*/ font-weight:bold;" readonly="readonly">
            </label>
        </section>

        <section class="col col-2" style="margin-bottom:5px;">
           <div class="btn-group" style="float:right;">
               <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
           </div>
       </section>
    </div>
        <input type="hidden" class="<?php echo $table;?>_rows" name="<?php echo $table;?>_rows[]" value="<?php echo $tr;?>">
        <input type="hidden" id="<?php echo $tr;?>_has_input" value="0">
        <input name="<?php echo $tr;?>_choicedetailid" id="<?php echo $tr;?>_choicedetailid" type="hidden" value="<?php echo $value['list'][0]['choicedetailid'];?>">
        <section id="<?php echo $table;?>" class="table table-note">
        <label class="textarea textarea-resizable">
            <textarea name="<?php echo $table;?>_notetext" id="<?php echo $table;?>_notetext" rows="5" class="custom-scroll note-input user-success"><?php echo (@$value['list'][0]['answer']['remark'] != '') ? @$value['list'][0]['answer']['remark'] : ''; ?></textarea>
        </label>
    </section>
</fieldset>



<?php

   }

  }

?>



</div>



                                 </div>

                                </div>

                           </form>

                           </div>
                           <!-- end widget content -->

                        </div>
                        <!-- end widget div -->
                     </div>
                     <!-- end widget -->
                  </article>
                  <!-- END COL -->
               </div>
               <!-- END ROW -->
               <!-- START ROW --><!-- END ROW -->
               <!-- NEW ROW --><!-- END ROW-->
            </section>
         </div>
         <!-- END MAIN CONTENT -->
      </div>
      <!-- END MAIN PANEL -->


<script type="text/javascript">

    // $("input:checkbox").on('click', function() {
    //     var $box = $(this);
    //     if ($box.is(":checked")) {
    //         var group = "input:checkbox[name='" + $box.attr("name") + "']";
    //         $(group).prop("checked", false);
    //         $box.prop("checked", true);
    //     } else {
    //         $box.prop("checked", false);
    //     }
    // });

jQuery("#send_from").click(function(){

  var crmid = $('#crmid').val();
  var form_data = jQuery('#design-form').serialize();

  $.ajax({
      method: "POST",
      dataType :'JSON',
      url: "<?php echo site_url('inspection/save_send');?>",
      data:{ data:form_data }
    })
    .done(function( data ) {
         var msg = jQuery.parseJSON(JSON.stringify(data));

         if(msg['status'] == "1" ){
            $('#loader').fadeOut();
            swal({
               position: 'center',//'top-end',
               type: 'success',
               title: msg['Message'],
               showConfirmButton: false,
               timer: 2000
             });

            setTimeout(function () {

               //location.replace('http://google.com');
               location.replace('inspection/thank?crmid='+crmid);
            }, 2500);


         }else{
            $('#loader').fadeOut();
            swal("","ส่งข้อมูลไม่สำเร็จ","error");

        }

    });


});

jQuery("#submit_from").click(function(){

  var form_data = jQuery('#design-form').serialize();

  $.ajax({
      method: "POST",
      dataType :'JSON',
      url: "<?php echo site_url('inspection/save');?>",
      data:{ data:form_data }
    })
    .done(function( data ) {
         var msg = jQuery.parseJSON(JSON.stringify(data));

         if(msg['status'] == "1" ){
            $('#loader').fadeOut();
            swal({
               position: 'center',//'top-end',
               type: 'success',
               title: msg['Message'],
               showConfirmButton: false,
               timer: 2000
             });

            setTimeout(function () {
               //location.reload();
               window.location.reload(true);
            }, 2500);


         }else{
            $('#loader').fadeOut();
            swal("","ส่งข้อมูลไม่สำเร็จ","error");

        }

    });


});

function ShowHideTable(TbID){

   if( jQuery("#"+TbID).css("display") == "none" ){
      jQuery('#'+TbID).css({display:'block'});
      jQuery('#'+TbID+'-collapse').html('<i class="fa fa-minus"></i>');
   }else{
      jQuery('#'+TbID).css({display:'none'});
      jQuery('#'+TbID+'-collapse').html('<i class="fa fa-plus"></i>');
   }
}
</script>

<style type="text/css">

.header-banner {
    background-color: #333;
    /*background-image: url('https://37.media.tumblr.com/8b4969985e84b2aa1ac8d3449475f1af/tumblr_n3iftvUesn1snvqtdo1_1280.jpg');*/
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    width: 100%;
    height: 300px;
}

header h1 {
    background-color: rgba(18,72,120, 0.8);
    color: #fff;
    padding: 0 1rem;
    position: absolute;
    top: 2rem;
    left: 2rem;
}

.fixed-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
}

nav {
    width: 100%;
    height: 60px;
    background: #292f36;
    postion: fixed;
    z-index: 10;
}

nav div {
    color: white;
    font-size: 2rem;
    line-height: 60px;
    position: absolute;
    top: 0;
    left: 2%;
    visibility: hidden;

}
.visible-title {
    visibility: visible;
}

nav ul {
    list-style-type: none;
    margin: 0 2% auto 0;
    padding-left: 0;
    text-align: right;
    max-width: 100%;
    border-bottom: 1px solid #d6d6d6;
}
nav ul li {
    display: inline-block;
    line-height: 60px;
    margin-left: 10px;
}
nav ul li a {
    text-decoration: none;
    color: #a9abae;
}

/* demo content */
body {
    color: #292f36;
    font-family: helvetica;
    line-height: 1.6;
}
article {
    float: left;
    width: 720px;
}
article p:first-of-type {
    margin-top: 0;
}
aside {
    float: right;
    width: 120px;
}
p img {
    max-width: 100%;
}

@media only screen and (max-width: 960px) {
    .content{
        padding: 2rem 0;
    }
    article {
        float: none;
        margin: 0 auto;
        width: 96%;
    }
    article:last-of-type {
        margin-bottom: 3rem;
    }
    aside {
        float: none;
        text-align: center;
        width: 100%;
    }
}

label.check {
    cursor: pointer
}

label.check input {
    position: absolute;
    top: 0;
    left: 0;
    visibility: hidden;
    pointer-events: none
}

label.check span {
    padding: 7px 14px;
    border: 2px solid #0000FF;
    display: inline-block;
    color: #0000FF;
    border-radius: 3px;
    text-transform: uppercase
}

label.check input:checked+span {
    border-color: #0000FF;
    background-color: #0000FF;
    color: #fff
}
.temp-input {
    /*border:0px;*/
    /*border-bottom:1px dotted #CCCCCC;*/
    text-align: center;
    color:#0000FF;
    width: 20px;
}
.temp-inputText {
    text-align: left;
    color:#0000FF;
    width: 20px;
}

table.table-bordered th:last-child {
    border-right-width: 1px;
}

table.table-bordered tbody th {
    border-left-width: 1px;
    border-bottom-width: 1px;
}
</style>