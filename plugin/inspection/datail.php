<?php
global $data_template;
global $choiceDetailList;

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//echo "<pre>"; print_r($data_template); echo "</pre>"; //exit;
//echo "<pre>"; print_r($choiceDetailList); echo "</pre>"; exit;

?>
<style>
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


</style>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/smartadmin-production-plugins.min.css">
      <link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/smartadmin-production.min.css">
      <link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/nhealth-skins.css">
      <link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/smartadmin-rtl.min.css">
      <link rel="stylesheet" type="text/css" media="screen" href="plugin/inspection/your_style.css">
      <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
      <script>
         if (!window.jQuery) {
         	document.write('<script src="plugin/inspection/jquery-2.1.1.min.js"><\/script>');
         }
      </script>
      <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
      <script>
         if (!window.jQuery.ui) {
         	document.write('<script src="plugin/inspection/jquery-ui-1.10.3.min.js"><\/script>');
         }
      </script>

   <body class="nhealth-skin" >

      <!-- MAIN PANEL -->
      <div id="main" role="main">

         <div id="content">
            <section id="widget-grid" class="">

               <div class="row">
                  <article class="col-sm-12 col-md-12 col-lg-12">

                    <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">
                        <header>
                           <span class="widget-icon"> <i class="fa fa-edit" style="margin-top: 7px; font-size: 20px;"></i> </span>
                           <h2>DATA RECORD FORM: <strong id="show-form-name"></strong><span id="last-update-form" class="last-update-form"></span></h2>
                        </header>
                        <!-- widget div-->
                        <div>
                           <!-- widget content -->
                           <div class="widget-body no-padding" style="min-height:50px;">

                                <div class="smart-form" id="design-form" name="design-form">
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
                    <input name="<?php echo $table; ?>_template1text" id="<?php echo $table; ?>_template1text" class="custom-scroll template1-input" readonly="readonly"><?php echo $value['list'][0]['list'];?></input>
                </label>
            </section>
        </fieldset>

    <?php }else if($value['choice_type']== 'template2'){ ?>

        <fieldset id="remove_<?php echo $table;?>" class="template2-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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
            <section id="<?php echo $table;?>" class="table table-template2">
                <table id="<?php echo $table;?>" class="table">

                    <tbody id="<?php echo $table;?>-sortable">

                    <?php
                    foreach($value['list'] as $k =>$v){
                        $tr = 'tr'.generateRandomString();
                        ?>

                        <tr class="<?php echo $tr;?>">
                            <td class="temp-input">
                                <div style="width:19px; margin:0 auto">
                                    <label class="checkbox" >
                                        <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template2'] == '1') ? 'checked' : ''; ?> ><i></i>
                                    </label>
                                </div>

                            </td>
                            <td>
                                <label class="input">
                                    <input name="<?php echo $tr;?>_list" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly="readonly">
                                </label>
                            </td>

                        </tr>

                    <?php } ?>
                    </tbody>

                </table>
            </section>

        </fieldset>

    <?php }else if($value['choice_type']== 'template3'){ ?>

        <fieldset id="remove_<?php echo $table;?>" class="template3-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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

            <section id="<?php echo $table;?>" class="table table-template3">
                <table id="<?php echo $table;?>" class="table">

                    <tbody id="<?php echo $table;?>-sortable">

                    <?php
                    foreach($value['list'] as $k =>$v){
                        $tr = 'tr'.generateRandomString();
                        ?>

                        <tr class="<?php echo $tr;?>">

                            <td class="temp-input">
                                <div style="width:19px; margin:0 auto">
                                    <label class="radio" >
                                        <input name="<?php echo $tr;?>_result" value="" type="radio" disabled="disabled" <?php echo (@$v['answer']['status_list_template2'] == '1') ? 'checked' : ''; ?> ><i></i>
                                    </label>
                                </div>

                            </td>
                            <td>
                                <label class="input">
                                    <input name="<?php echo $tr;?>_list" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly="readonly">
                                </label>
                            </td>

                        </tr>

                    <?php } ?>
                    </tbody>

                </table>
            </section>



        </fieldset>

    <?php }else if($value['choice_type']== 'template4'){ ?>

        <fieldset id="remove_<?php echo $table;?>" class="template1-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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

            <div id="<?php echo $table;?>" >
                <select disabled>
                    <?php
                    foreach($value['list'] as $k =>$v) { ?>
                        <option value="<?= $v['list'] ?>"><?= $v['list'] ?></option>
                        <?php
                    } ?>
                </select>
            </div>

        </fieldset>

      <?php }else if($value['choice_type']== 'template5'){ ?>

          <fieldset id="remove_<?php echo $table;?>" class="template5-form-box" style="padding-bottom:20px;">
              <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
              <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
              <div class="row">
                  <section class="col col-10" style="margin-bottom:5px;">
                      <label class="input">
                          <input name="<?php echo $table;?>_headtext" type="text" value="<?php echo $value['choice_title'];?>" class="note-head user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" readonly="readonly">
                      </label>
                  </section>

                  <section class="col col-2" style="margin-bottom:5px;">
                      <div class="btn-group" style="float:right;">
                          <a href="javascript:ShowHideTable('<?php echo $table;?>');" id="<?php echo $table;?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                      </div>
                  </section>
              </div>
              <section id="<?php echo $table;?>" class="table table-template5">
                  <label class="textarea textarea-resizable">
                      <textarea name="<?php echo $table;?>_notetext" id="<?php echo $table;?>_notetext" rows="3" class="custom-scroll note-input user-success" readonly="readonly"></textarea>
                  </label>
              </section>
          </fieldset>

      <?php }else if($value['choice_type']== 'template6'){ ?>

          <fieldset id="remove_<?php echo $table;?>" class="template6-form-box" style="padding-bottom:20px;">
              <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
              <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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
                      $tr = 'tr'.generateRandomString();
                      ?>

                      <td class="<?php echo $tr;?>">
                      <td>
                          <label class="input">
                              <input type="text" class="temp-input" name="<?php echo $tr;?>_list" value="<?php echo $v['list'];?>" readonly="readonly"/>

                              <!--                              <input name="--><?php //echo $tr;?><!--_list" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='--><?php //echo $v['list'];?><!--' readonly="readonly">-->
                          </label>
                      </td>
<!--                              <input type="text" class="temp-input" name="--><?php //echo $tr;?><!--_list" value="--><?php //echo $v['list'];?><!--" readonly="readonly"/>-->
                      </td>

                  <?php } ?>
                  </tbody>

              </table>


          </fieldset>

      <?php }else if($value['choice_type']== 'template7'){ ?>

          <fieldset id="remove_<?php echo $table;?>" class="template7-form-box" style="padding-bottom:20px;">
              <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
              <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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
              <table id="<?php echo $table;?>" class="table">

                  <tbody id="<?php echo $table;?>-sortable">

                  <?php
                  foreach($value['list'] as $k =>$v){
                      $tr = 'tr'.generateRandomString();
                      ?>

                      <tr class="<?php echo $tr;?>">
                          <td class="temp-input">
                              <div style="width:19px; margin:0 auto">
                                  <label class="checkbox" >
                                      <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template7'] == '1') ? 'checked' : ''; ?> ><i></i>
                                  </label>
                              </div>
                          </td>
                          <td>
                              <label class="input">
                                  <input name="<?php echo $tr;?>_list" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly="readonly">
                              </label>
                          </td>

                      </tr>

                  <?php } ?>
                  </tbody>

              </table>


          </fieldset>

      <?php }else if($value['choice_type']== 'template8'){ ?>

          <fieldset id="remove_<?php echo $table;?>" class="template8-form-box" style="padding-bottom:20px;">
              <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
              <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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
              <table id="<?php echo $table;?>" class="table">

                  <tbody id="<?php echo $table;?>-sortable">

                  <?php

                  foreach($value['list'] as $k => $v){
                      $tr = 'tr'.generateRandomString();
                      ?>

                      <tr class="<?php echo $tr;?>">
                          <td>
                              <div style="width:19px; margin:0 auto">
                                  <label class="checkbox" >
                                      <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template2'] == '1') ? 'checked' : ''; ?> ><i></i>
                                  </label>
                              </div>
                          </td>
                          <td>
                              <label class="input">
                                  <input name="<?php echo $tr;?>_list" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly="readonly">
                              </label>
                              <?php

                              foreach($choiceDetailList[$v['choicedetailid']] as $k => $v){
                                  ?>

                                  <div style="padding-left:30px;">
                                      <label class="input">
                                          <input name="<?php echo $tr;?>_child" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['value'];?>' readonly="readonly">
                                      </label>
                                  </div>

                              <?php }?>
                          </td>

                      </tr>

                  <?php }?>

                  </tbody>

              </table>

          </fieldset>

      <?php }else if($value['choice_type']== 'template9'){ ?>

          <fieldset id="remove_<?php echo $table;?>" class="template1-form-box" style="padding-bottom:20px;">
              <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
              <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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
              <table class="table table-bordered " id="<?php echo $table;?>">

                  <tr>
                      <th rowspan="4">
                          <label class="input">
                              <input name="<?php echo $temp9; ?>_head_col0" type="text" class="template9-inphead" value="<?php echo $value['head_col0']; ?>"
                                     style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
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
                              <input name="<?php echo $temp9; ?>_head_col2" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col2']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </th>
                      <th colspan="2">
                          <label class="input">
                              <input name="<?php echo $temp9; ?>_head_col3" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col3']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </th>
                      <th colspan="2">
                          <label class="input">
                              <input name="<?php echo $temp9; ?>_head_col4" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col4']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </th>
                      <th rowspan="4">
                          <label class="input">
                              <input name="<?php echo $temp9; ?>_head_col5" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col5']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </th>
                  </tr>
                  <tr>
                      <th colspan="2">
                          <label class="input">
                              <input name="<?php echo $temp9; ?>_head_col6" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col6']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                          </label>
                      </th>
                      <th colspan="2">
                          <label class="input">
                              <input name="<?php echo $temp9; ?>_head_col7" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col7']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                          </label>
                      </th>


                  </tr>

                  <tr>
                      <th>
                          <label class="input">
                              <input name="<?php echo $temp9; ?>_head_col8" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col8']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </th>
                      <th>
                          <label class="input">
                              <input name="<?php echo $temp9; ?>_head_col9" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col9']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </th>
                      <th>
                          <label class="input">
                              <input name="<?php echo $temp9; ?>_head_col10" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col10']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </th>
                      <th>
                          <label class="input">
                              <input name="<?php echo $temp9; ?>_head_col11" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col11']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </th>

                  </tr>

                  <tbody class="tbody-<?php echo $table;?>">
                  <?php
                  foreach($value['list'] as $k =>$v){
                      $tr = 'tr'.generateRandomString();
                      ?>

                      <tr class="<?php echo $tr;?>">
                          <td style="text-align: center;">
                              <span class="temp-input"><?php echo $v['sequence_detail'];?></span>
                          </td>
                          <td>
                              <input type="hidden" class="temp-input template9-inphead" name="${tempID}_col0" value="" />
                          </td>
                          <td>
                              <input type="hidden" class="temp-input template9-inphead" name="${tempID}_col1" value="" />
                          </td>
                          <td>
                              <input type="hidden" class="temp-input template9-inphead" name="${tempID}_col2" value="" />
                          </td>
                          <td>
                              <input type="hidden" class="temp-input template9-inphead" name="${tempID}_col3" value="" />
                          </td>
                          <td>
                              <input type="hidden" class="temp-input template9-inphead" name="${tempID}_col4" value="" />
                          </td>
                          <td>
                              <input type="hidden" class="temp-input template9-inphead" name="${tempID}_col5" value="" />
                          </td>

                      </tr>


                  <?php } ?>
                  </tbody>


              </table>


          </fieldset>

      <?php }else if($value['choice_type']== 'template10'){ ?>

          <fieldset id="remove_<?php echo $table;?>" class="template10-form-box" style="padding-bottom:20px;">
              <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
              <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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
              <table id="<?php echo $table;?>" class="table">

                  <tbody id="<?php echo $table;?>-sortable">

                  <?php
                  foreach($value['list'] as $k =>$v){
                      $tr = 'tr'.generateRandomString();
                      ?>

                      <tr class="<?php echo $tr;?>">
                          <td>
                              <label class="input">
                                  <input name="<?php echo $tr;?>_list" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly="readonly">
                              </label>
                          </td>
                          <td>
                              <label class="input">
                                  <input type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success"  readonly="readonly">
                              </label>
                          </td>
                          <td class="temp-input">
                              <div style="width:19px; margin:0 auto">
                                  <label class="checkbox" >
                                      <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template10'] == '1') ? 'checked' : ''; ?> ><i></i>
                                  </label>
                              </div>

                          </td>
                          <td class="temp-input">
                              <div style="width:19px; margin:0 auto">
                                  <label class="checkbox" >
                                      <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template10'] == '1') ? 'checked' : ''; ?> ><i></i>
                                  </label>
                              </div>

                          </td>
                          <td class="temp-input">
                              <div style="width:19px; margin:0 auto">
                                  <label class="checkbox" >
                                      <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template10'] == '1') ? 'checked' : ''; ?> ><i></i>
                                  </label>
                              </div>

                          </td>

<!--                          <td>-->
<!--                              <input type="text" style="width: 100px; !important;" class="temp-inputText" name="--><?php //echo $tr;?><!--_list" value="--><?php //echo $v['list'];?><!--" readonly="readonly"/>-->
<!--                              <input type="text" style="width: 200px; !important;" class="temp-inputText" name="" value="" placeholder="label" readonly="readonly"/>-->
<!--                              <span class="fa fa-square-o"></spacn>-->
<!--                              <span class="fa fa-square-o"></spacn>-->
<!--                              <span class="fa fa-square-o"></spacn>-->
<!---->
<!--                          </td>-->
                      </tr>


                  <?php } ?>
                  </tbody>

              </table>


          </fieldset>

      <?php }else if($value['choice_type']== 'template11'){ ?>

          <fieldset id="remove_<?php echo $table;?>" class="template1-form-box" style="padding-bottom:20px;">
              <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
              <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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
              <table id="<?php echo $table;?>" class="table">

                  <tbody id="<?php echo $table;?>-sortable">

                  <?php
                  foreach($value['list'] as $k =>$v){
                      $tr = 'tr'.generateRandomString();
                      ?>

                      <tr class="<?php echo $tr;?>">
                          <td width="5%" >
                            <span class="fa fa-minus"></spacn>
                          </td>
                          <td width="85%">
                              <label class="input">
                                  <input type="text" class="temp-inputText" name="" value="<?php echo $v['list'];?>" placeholder="label" readonly="readonly"/>
                              </labe>
                          </td>
                          <td width="10%" class="temp-input">
                              <div style="width:19px; margin:0 auto">
                                  <label class="checkbox" >
                                      <input name="" value="" type="checkbox" disabled="disabled"  ><i></i>
                                  </label>
                              </div>
                          </td>

                      </tr>

                  <?php } ?>
                  </tbody>

              </table>


          </fieldset>

      <?php }else if($value['choice_type']== 'template12'){ ?>

          <fieldset id="remove_<?php echo $table;?>" class="template1-form-box" style="padding-bottom:20px;">
              <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
              <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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
              <table class="table table-bordered " id="<?php echo $table;?>">

                  <tr>
                      <td>
                          <label class="input">
                          </label>
                      </td>
                      <td>
                          <label class="input" >
                              <input name="<?php echo $temp12; ?>_head_col0" type="text" class="template12-inphead" value="<?php echo $value['head_col0']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </td>
                      <td>
                          <label class="input" >
                              <input name="<?php echo $temp12; ?>_head_col1" type="text" class="template12-inphead" value="<?php echo $value['head_col1']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </td>

                  </tr>

                  <tbody id="<?php echo $table;?>-sortable">

                  <?php
                  foreach($value['list'] as $k =>$v){
                      $tr = 'tr'.generateRandomString();
                      ?>

                      <tr class="<?php echo $tr;?>">
                          <td>
                              <label class="input" >
                                  <input name="<?php echo $tr;?>_list" type="text" class="template12-inphead" value="<?php echo $v['list'];?>"
                                         style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                              </label>
                          </td>
                          <td style="text-align: center;">
                              <span class="fa fa-square-o" ></spacn>
                          </td>
                          <td>
                          </td>
                      </tr>

                  <?php } ?>
                  </tbody>

              </table>

          </fieldset>

      <?php }else if($value['choice_type']== 'template13'){ ?>

          <fieldset id="remove_<?php echo $table;?>" class="template1-form-box" style="padding-bottom:20px;">
              <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
              <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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
              <table class="table table-bordered " id="<?php echo $table;?>">

                  <tr>
                      <td>
                          <label class="input">
                          </label>
                      </td>
                      <td>
                          <label class="input" >
                              <input name="<?php echo $temp13; ?>_head_col0" type="text" class="template13-inphead" value="<?php echo $value['head_col0']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </td>
                      <td>
                          <label class="input" >
                              <input name="<?php echo $temp13; ?>_head_col1" type="text" class="template13-inphead" value="<?php echo $value['head_col1']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </td>

                  </tr>

                  <tbody id="<?php echo $table;?>-sortable">

                  <?php
                  foreach($value['list'] as $k =>$v){
                      $tr = 'tr'.generateRandomString();
                      ?>

                      <tr class="<?php echo $tr;?>">
                          <td colspan="3">
                              <label class="input" >
                                  <input name="<?php echo $tr;?>_list" type="text" class="template13-inphead" value="<?php echo $v['list'];?>"
                                         style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: left;" readonly>
                              </label>
                          </td>

                      </tr>

                      <tr>
                          <td>
                              <label class="input" >
                                  <input name="<?php echo $tr;?>_list" type="text" class="template13-inphead" value="<?php echo $v['sublist'];?>"
                                         style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: left;" readonly>
                              </label>
                          </td>
                          <td style="text-align: center;">
                            <span class="fa fa-square-o" ></spacn>
                          </td>
                          <td>
                          </td>
                      </tr>

                  <?php } ?>
                  </tbody>

              </table>

          </fieldset>

      <?php }else if($value['choice_type']== 'template14'){ ?>

          <fieldset id="remove_<?php echo $table;?>" class="template1-form-box" style="padding-bottom:20px;">
              <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
              <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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
              <table class="table table-bordered " id="<?php echo $table;?>">

                  <tr>
                      <td>
                          <label class="input">
                          </label>
                      </td>
                      <td>
                          <label class="input" >
                              <input name="<?php echo $temp14; ?>_head_col0" type="text" class="template12-inphead" value="<?php echo $value['head_col0']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </td>
                      <td>
                          <label class="input" >
                              <input name="<?php echo $temp14; ?>_head_col1" type="text" class="template12-inphead" value="<?php echo $value['head_col1']; ?>"
                                     style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                          </label>
                      </td>

                  </tr>

                  <tbody id="<?php echo $table;?>-sortable">

                  <?php
                  foreach($value['list'] as $k =>$v){
                      $tr = 'tr'.generateRandomString();
                      ?>

                      <tr class="<?php echo $tr;?>">
                          <td>
                              <label class="input" >
                                  <input name="<?php echo $tr;?>_list" type="text" class="template12-inphead" value="<?php echo $v['list'];?>"
                                         style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                              </label>
                          </td>
                          <td style="text-align: center;">
                              <span class="fa fa-square-o" ></spacn>
                          </td>
                          <td style="text-align: center;">
                              <span class="fa fa-square-o" ></spacn>
                          </td>
                      </tr>

                  <?php } ?>
                  </tbody>

              </table>

          </fieldset>

      <?php }else if($value['choice_type']== 'template15'){ ?>

          <fieldset id="remove_<?php echo $table;?>" class="template1-form-box" style="padding-bottom:20px;">
              <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
              <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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
              <table class="table table-bordered " id="<?php echo $table;?>">
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

                  <tbody class="tbody-<?php echo $table;?>">
                  <?php
                  foreach($value['list'] as $k =>$v){
                      $tr = 'tr'.generateRandomString();
                      ?>

                      <tr class="<?php echo $tr;?>">
                          <td style="text-align: center;">
                              <span class="temp-input"><?php echo $v['list'];?></span>
                          </td>
                          <td>
                              <input type="hidden" class="temp-input template15-inphead" name="" value="" />
                          </td>
                      <?php if ($value['head_col2']  != ''){ ?>
                          <td>
                              <input type="hidden" class="temp-input template15-inphead" name="" value="" />
                          </td>

                      <? } ?>
                          <td>
                              <span class="temp-input"><?php echo $v['accept_range'];?></span>
                          </td>
                          <td>
                              <div >
                                      <label class="radio" >
                                          <input name="<?php echo $tr;?>_result" value="" type="radio" disabled="disabled" <?php echo (@$v['answer']['status_list_template15_1'] == '1') ? 'checked' : ''; ?> >Yes<i></i>
                                      </label>
                              </div>
                              <div >
                                      <label class="radio" >
                                          <input name="<?php echo $tr;?>_result" value="" type="radio" disabled="disabled" <?php echo (@$v['answer']['status_list_template15_2'] == '1') ? 'checked' : ''; ?> >No<i></i>
                                      </label>
                              </div>
                          </td>


                      </tr>

                  <?php } ?>
                  </tbody>


              </table>


          </fieldset>

      <?php }else if($value['choice_type']== 'template16'){ ?>

          <fieldset id="remove_<?php echo $table;?>" class="template1-form-box" style="padding-bottom:20px;">
              <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
              <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="">
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
              <table class="table table-bordered " id="<?php echo $table;?>">

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

                  <tbody class="tbody-<?php echo $table;?>">
                  <?php
                  foreach($value['list'] as $k =>$v){
                      $tr = 'tr'.generateRandomString();
                      ?>

                      <tr class="<?php echo $tr;?>">
                          <td style="text-align: center;">
                              <span class="temp-input"><?php echo $v['list'];?></span>
                          </td>
                          <td style="text-align: center;">
                              <span class="fa fa-square-o" ></spacn>
                          </td>
                          <td style="text-align: center;">
                              <span class="fa fa-square-o" ></spacn>
                          </td>
                          <td style="text-align: center;">
                              <span class="fa fa-square-o" ></spacn>
                          </td>
                      </tr>


                  <?php } ?>
                  </tbody>


              </table>


          </fieldset>

      <?php }else if($value['choice_type']== 'template17'){ ?>

          <fieldset id="remove_<?php echo $table;?>" class="template1-form-box" style="padding-bottom:20px;">
              <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
              <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template17">
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
              <table class="table table-bordered " id="<?php echo $table;?>">

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

                  <tbody class="tbody-<?php echo $table;?>">
                  <?php
                  foreach($value['list'] as $k =>$v){
                      $tr = 'tr'.generateRandomString();
                      ?>

                      <tr class="<?php echo $tr;?>">
                          <td style="text-align: center;">
                              <span class="fa fa-square-o" ></spacn>
                              <span class="temp-input"><?php echo $v['list'];?></span>
                          </td>
                          <td style="text-align: center;">
                              <span class="fa fa-square-o" ></spacn>
                                  <span class="temp-input"><?php echo $v['sublist'];?></span>
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
                              <span class="temp-input"><?php echo 'Time .......... Minutes';?></span>

                          </label>
                      </td>
                      <td>
                          <label class="input">
                              <span class="temp-input"><?php echo 'Time .......... Minutes';?></span>
                          </label>
                      </td>
                  </tr>

                  </tfoot>



              </table>


          </fieldset>

    <?php }else if($value['choice_type']== 'template18'){ ?>

        <fieldset id="remove_<?php echo $table;?>" class="template1-form-box" style="padding-bottom:20px;">
            <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
            <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template18">
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
            <table class="table table-bordered " id="<?php echo $table;?>">

                <tr>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col0" type="text" class="template16-inphead" value="<?php echo $value['head_col0'];?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center; " readonly="readonly">
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col1" type="text" class="template16-inphead" value="<?php echo $value['head_col1'];?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly="readonly">
                        </label>
                    </td>
                    <td>
                        <label class="input">
                            <input name="<?php echo $table; ?>_head_col2" type="text" class="template16-inphead" value="<?php echo $value['head_col2'];?>"
                                   style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly="readonly">
                        </label>
                    </td>
                </tr>


                <tbody class="tbody-<?php echo $table;?>">
                <?php
                foreach($value['list'] as $k =>$v){
                    $tr = 'tr'.generateRandomString();
                    ?>

                    <tr class="<?php echo $tr;?>">
                        <td style="text-align: center;">
                                  <span class="temp-input"><?php echo $v['list'];?></span>
                        </td>
                        <td>
                            <div >
                                <label class="radio" >
                                    <input name="<?php echo $tr;?>_result" value="" type="radio" disabled="disabled" <?php echo (@$v['answer']['status_list_template15_1'] == '1') ? 'checked' : ''; ?> >Yes<i></i>
                                </label>
                            </div>
                            <div >
                                <label class="radio" >
                                    <input name="<?php echo $tr;?>_result" value="" type="radio" disabled="disabled" <?php echo (@$v['answer']['status_list_template15_2'] == '1') ? 'checked' : ''; ?> >No<i></i>
                                </label>
                            </div>
                        </td>
                        <td style="text-align: center;">

                        </td>

                    </tr>

                <?php } ?>
                </tbody>

            </table>


        </fieldset>

    <?php }





}

?>

</div>



                                 </div>

                                </div>

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
      <style>
          table.table-bordered th:last-child {
              border-right-width: 1px;
          }

          table.table-bordered tbody th {
              border-left-width: 1px;
              border-bottom-width: 1px;
          }

      </style>
      <script src="plugin/inspection/js/app.config.js"></script>
      <script src="plugin/inspection/jquery.ui.touch-punch.min.js"></script>
      <script src="plugin/inspection/bootstrap.min.js"></script>
      <script src="plugin/inspection/SmartNotification.min.js"></script>
      <script src="plugin/inspection/jarvis.widget.min.js"></script>
      <script src="plugin/inspection/jquery.easy-pie-chart.min.js"></script>
      <script src="plugin/inspection/jquery.sparkline.min.js"></script>

      <script src="plugin/inspection/jquery.maskedinput.min.js"></script>
      <script src="plugin/inspection/select2.min.js"></script>
      <script src="plugin/inspection/bootstrap-slider.min.js"></script>
      <script src="plugin/inspection/jquery.mb.browser.min.js"></script>
      <script src="plugin/inspection/fastclick.min.js"></script>
      <script src="plugin/inspection/app.min.js"></script>
      <script src="plugin/inspection/voicecommand.min.js"></script>
      <script src="plugin/inspection/smart.chat.ui.min.js"></script>
      <script src="plugin/inspection/smart.chat.manager.min.js"></script>
      <script src="plugin/inspection/jquery.validate.min.js"></script>
      <script type="text/javascript">
   	  jQuery(document).ready(function() {
   	    //pageSetUp();
   	  })
      </script>
      <!-- PAGE RELATED PLUGIN(S) -->
      <script src="plugin/inspection/jquery-form.min.js"></script>
      <script src="plugin/inspection/form.js"></script>

<script type="text/javascript">

function ShowHideTable(TbID){

   if( jQuery("#"+TbID).css("display") == "none" ){
      jQuery('#'+TbID).css({display:'block'});
      jQuery('#'+TbID+'-collapse').html('<i class="fa fa-minus"></i>');
   }else{
      jQuery('#'+TbID).css({display:'none'});
      jQuery('#'+TbID+'-collapse').html('<i class="fa fa-plus"></i>');
   }

}

//jQuery( "#parameters-sortable" ).sortable({ cursor: "move" });

</script>