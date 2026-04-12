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
echo "<pre>"; print_r($data_template); echo "</pre>"; //exit;
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

    /*.template9-inphead{*/
        /*border:1px;*/
        /*border-bottom:1px dotted #CCCCCC;*/
        /*color:#0000FF;*/
        /*text-align: center;*/
    /*}*/

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

$status_list_template4 = [];

//foreach ()
foreach ($data_template as $key => $value) {
//echo '<pre>';
//print_r($value);
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
                   <input name="<?php echo $table; ?>_template1text" type="text" value="<?php echo (@$value['list'][0]['answer']['detail_template1'] != '') ? @$value['list'][0]['answer']['detail_template1'] : ''; ?>" readonly="readonly">
               </label>
           </section>
       </fieldset>

   <?php }else if($value['choice_type']== 'template2'){ ?>

       <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
           <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
           <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="pm">
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

       </fieldset>

   <?php }else if($value['choice_type']== 'template3'){ ?>

       <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
           <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
           <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="pm">
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
                       <td class="temp-input">
                           <div style="width:19px; margin:0 auto">
                               <label class="radio" >
                                   <input name="<?php echo $tr;?>_result" value="" type="radio" disabled="disabled" <?php echo (@$v['answer']['status_list_template3'] == '1') ? 'checked' : ''; ?> ><i></i>
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

   <?php }else if($value['choice_type']== 'template4'){ ?>

       <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
           <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
           <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="pm">
           <div class="row">
               <section class="col col-10" style="margin-bottom:5px;">
                   <label class="input">
                       <input name="<?php echo $table;?>_parameter" type="text" class="pm-inphead user-success" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" value="<?php echo $value['choice_title'];?>" readonly="readonly">
                   </label>
               </section>
           </div>
           <?php

           foreach($value['list'] as $k =>$v) {
//               echo '<pre>'; print_r($v); echo '</pre>';
               $status_list_template4[] = $v['answer']['status_list_template4'];

           } ?>

           <table id="<?php echo $table;?>" style="display:table" class="table">
               <select name="per1" id="per1" disabled="disabled">
                   <option selected="selected" ><?php echo $status_list_template4[0];?> </option>
               </select>
<!--               <select name="plans">-->
<!--                   <option --><?php //echo ($status_list_template4[0] == 'MAP')?"selected":"" ?><!-- >MAP</option>-->
<!--                   <option --><?php //echo ($status_list_template4[0]  == 'MAP')?"selected":"" ?><!-- >CP</option>-->
<!--                   <option --><?php //echo ($status_list_template4[0]  == 'Text 2')?"selected":"" ?><!-- >CPA</option>-->
<!--                   <option --><?php //echo ($status_list_template4[0]  == 'MAP')?"selected":"" ?><!-- >CPF</option>-->
<!--               </select>-->
           </table>

       </fieldset>

   <?php }else if($value['choice_type']== 'template5'){ ?>

       <fieldset id="remove_<?php echo $table;?>" class="note-form-box" style="padding-bottom:20px;">
           <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
           <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="note">
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
           <section id="<?php echo $table;?>" class="table table-note">
               <label class="textarea textarea-resizable">
                   <textarea name="<?php echo $table;?>_notetext" id="<?php echo $table;?>_notetext" rows="3" class="custom-scroll note-input user-success" readonly="readonly"><?php echo (@$value['list'][0]['answer']['comment'] != '') ? @$value['list'][0]['answer']['comment'] : ''; ?></textarea>
               </label>
           </section>
       </fieldset>

   <?php }else if($value['choice_type']== 'template6'){ ?>

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
                       <td class="<?php echo $tr;?>">
                           <label class="check" >
                               <input type="checkbox" name="<?php echo $tr;?>_list" disabled="disabled" <?php echo (@$v['list'] == $statusCheck) ? 'checked' : ''; ?> value="<?php echo $v['list'].$statusCheck;?>"/>
                               <span><?php echo $v['list'];?></span>
                           </label>
                       </td>

                   <?php } ?>

           </table>


       </fieldset>


   <?php }else if($value['choice_type']== 'template7'){ ?>

       <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
           <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
           <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="pm">
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
                   echo '<pre>'; print_r($v); echo '</pre>';
                   $tr = 'tr'.generateRandomString();
                   ?>

                   <tr id="<?php echo $tr;?>" class="tr-form tr-form-<?php echo $table;?>">
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

       <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
           <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
           <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="pm">
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

                   <tr id="<?php echo $tr;?>" class="tr-form tr-form-<?php echo $table;?>">
                       <td class="temp-input">
                           <div style="width:19px; margin:0 auto">
                               <label class="checkbox" >
                                   <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template8'] == '1') ? 'checked' : ''; ?> ><i></i>
                               </label>
                           </div>

                       </td>
                       <td>
                           <label class="input">
                               <input name="<?php echo $tr;?>_list" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly>
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

   <?php }else if($value['choice_type']== 'template9'){ ?>

       <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
           <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
           <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="pm">
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

           <table id="<?php echo $table;?>" style="display:table" class="table table-bordered ">

               <?php echo '<pre>'; print_r($value); echo '</pre>';?>
               <tr align="center">
                   <td colspan="1">
                       <label class="input">
                       </label>
                   </td>
                   <td colspan="2">
                       <label class="input">
                           <input name="<?php echo $temp9; ?>_head_col0" type="text" class="template9-inphead" value="<?php echo $value['head_col0']?>"
                                  style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="">

                       </label>
                   </td>
                   <?php if ($value['head_col1'] != ''){?>

                       <td colspan="2">
                           <label class="input">
                               <input name="<?php echo $temp9; ?>_head_col1" type="text" class="template9-inphead" value="<?php echo $value['head_col1']?>"
                                      style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="">
                           </label>
                       </td>

                   <?php }
                   ?>

                   <?php if ($value['head_col1'] != ''){?>

                       <td colspan="2">
                           <label class="input">
                               <input name="<?php echo $temp9; ?>_head_col2" type="text" class="template9-inphead" value="<?php echo $value['head_col2']?>"
                                      style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                           </label>
                       </td>

                   <?php }
                   ?>

                   <?php if ($value['head_col1'] != ''){?>

                       <td colspan="2">
                           <label class="input">
                               <input name="<?php echo $temp9; ?>_head_col3" type="text" class="template9-inphead" value="<?php echo $value['head_col3']?>"
                                      style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                           </label>
                       </td>

                   <?php }
                   ?>


               </tr>

               <tr>
                   <td colspan="1">
                       <label class="input">
                       </label>
                   </td>
                   <td colspan="2">
                       <label class="input">
                           <input name="<?php echo $temp9; ?>_head_col4" type="text" class="template9-inphead" value="<?php echo $value['head_col4']?>"
                                  style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                       </label>
                   </td>

                   <?php if ($value['head_col1'] != ''){?>

                       <td colspan="2">
                           <label class="input">
                               <input name="<?php echo $temp9; ?>_head_col5" type="text" class="template9-inphead" value="<?php echo $value['head_col5']?>"
                                      style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                           </label>
                       </td>

                   <?php }
                   ?>

                   <?php if ($value['head_col2'] != ''){?>

                       <td colspan="2">
                           <label class="input">
                               <input name="<?php echo $temp9; ?>_head_col6" type="text" class="template9-inphead" value="<?php echo $value['head_col6']?>"
                                      style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                           </label>
                       </td>

                   <?php }
                   ?>

                   <?php if ($value['head_col3'] != ''){?>

                       <td colspan="2">
                           <label class="input">
                               <input name="<?php echo $temp9; ?>_head_col7" type="text" class="template9-inphead" value="<?php echo $value['head_col7']?>"
                                      style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                           </label>
                       </td>

                   <?php }
                   ?>

               </tr>

               <tr>
                   <td>
                       <label class="input">
                           <input name="<?php echo $temp9; ?>_head_col8" type="text" class="template9-inphead" value="<?php echo $value['head_col8']?>"
                                  style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                       </label>
                   </td>
                   <td>
                       <label class="input">
                           <input name="<?php echo $temp9; ?>_head_col9" type="text" class="template9-inphead" value="<?php echo $value['head_col9']?>"
                                  style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                       </label>
                   </td>
                   <?php if ($value['head_col10'] != ''){?>

                       <td>
                           <label class="input">
                               <input name="<?php echo $temp9; ?>_head_col10" type="text" class="template9-inphead" value="<?php echo $value['head_col10']?>"
                                      style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                           </label>
                       </td>

                   <?php }
                   ?>

                   <?php if ($value['head_col1'] != ''){?>

                       <td>
                           <label class="input">
                               <input name="<?php echo $temp9; ?>_head_col11" type="text" class="template9-inphead" value="<?php echo $value['head_col11']?>"
                                      style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                           </label>
                       </td>

                   <?php }
                   ?>


                   <?php if ($value['head_col1'] != ''){?>

                       <td>
                           <label class="input">
                               <input name="<?php echo $temp9; ?>_head_col12" type="text" class="template9-inphead" value="<?php echo $value['head_col12']?>"
                                      style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                           </label>
                       </td>

                   <?php }
                   ?>

                   <?php if ($value['head_col2'] != ''){?>

                       <td>
                           <label class="input">
                               <input name="<?php echo $temp9; ?>_head_col13" type="text" class="template9-inphead" value="<?php echo $value['head_col13']?>"
                                      style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                           </label>
                       </td>

                   <?php }
                   ?>

                   <?php if ($value['head_col2'] != ''){?>

                       <td>
                           <label class="input">
                               <input name="<?php echo $temp9; ?>_head_col14" type="text" class="template9-inphead" value="<?php echo $value['head_col14']?>"
                                      style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                           </label>
                       </td>

                   <?php }
                   ?>

                   <?php if ($value['head_col3'] != ''){?>

                       <td>
                           <label class="input">
                               <input name="<?php echo $temp9; ?>_head_col15" type="text" class="template9-inphead" value="<?php echo $value['head_col15']?>"
                                      style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                           </label>
                       </td>

                   <?php }
                   ?>


                   <?php if ($value['head_col3'] != ''){?>

                       <td>
                           <label class="input">
                               <input name="<?php echo $temp9; ?>_head_col16" type="text" class="template9-inphead" value="<?php echo $value['head_col16']?>"
                                      style="border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly="readonly">
                           </label>
                       </td>

                   <?php }
                   ?>


               </tr>
               
               <tbody id="<?php echo $table;?>-sortable" class="ui-sortable">
               <?php
               foreach($value['list'] as $k =>$v){
                   $tr = 'tr'.generateRandomString();
                   ?>

                   <tr class="<?php echo $tr;?>">
                       <td style="text-align: center;">
                           <span class="temp-input"><?php echo $v['sequence_detail'];?></span>
                       </td>
                       <td>
                           <span class="temp-input"><?php echo $v['answer']['data_col1']?></span>
                       </td>
                       <td>
                           <span class="temp-input"><?php echo $v['answer']['data_col2']?></span>
                       </td>
                       <?php if ($value['head_col1'] != ''){?>
                           <td>
                               <span class="temp-input"><?php echo $v['answer']['data_col3']?></span>
                           </td>
                           <td>
                               <span class="temp-input"><?php echo $v['answer']['data_col4']?></span>
                           </td>
                       <?php }
                       ?>
                       <?php if ($value['head_col2'] != ''){?>
                           <td>
                               <span class="temp-input"><?php echo $v['answer']['data_col5']?></span>
                           </td>
                           <td>
                               <span class="temp-input"><?php echo $v['answer']['data_col6']?></span>
                           </td>
                       <?php }
                       ?>
                       <?php if ($value['head_col3'] != ''){?>
                           <td>
                               <span class="temp-input"><?php echo $v['answer']['data_col7']?></span>
                           </td>
                           <td>
                               <span class="temp-input"><?php echo $v['answer']['data_col8']?></span>
                           </td>
                       <?php }
                       ?>

                   </tr>

               <?php } ?>

               </tbody>

           </table>

       </fieldset>

   <?php }else if($value['choice_type']== 'template10'){ ?>

       <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
           <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
           <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="pm">
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

           <table id="<?php echo $table;?>" class="table">

               <tbody id="<?php echo $table;?>-sortable">

               <?php
               foreach($value['list'] as $k =>$v){
                   $tr = 'tr'.generateRandomString();
                   ?>

                   <tr class="<?php echo $tr;?>">
                       <td>
                           <input type="text" style="width: 100px; !important;" class="temp-inputText" name="<?php echo $tr;?>_list" value="<?php echo $v['list'];?>" readonly="readonly"/>
                           <input type="text" style="width: 200px; !important;" class="temp-inputText" name="<?php echo $tr;?>_list2" value="<?php echo $v['list2'];?>" readonly="readonly"/>
                           <span class="fa fa-square-o"></spacn>
                               <span class="fa fa-square-o"></spacn>
                                   <span class="fa fa-square-o"></spacn>

                       </td>
                   </tr>


               <?php } ?>
               </tbody>

           </table>

       </fieldset>

   <?php }else if($value['choice_type']== 'pm'){ ?>

<fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
      <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
      <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="pm">    
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
              
      <table id="<?php echo $table;?>" style="display:table" class="table table-bordered table-form table-pm">
          <thead>
              <tr>
                  <!-- <td style="width:5%" class="pm-head"><a href="javascript:ResetPMRowNumber('<?php echo $table;?>')">No. <i class="fa fa-arrow-down"></i></a></td> -->
                  <td style="width:5%" class="pm-head">No. </td>
                  <td style="width:40%" class="pm-head">List</td>
                  <!-- <td style="width:10%" class="pm-head"><a href="javascript:PMSelectAllPass('<?php //echo $table;?>');">Pass</a></td>
                  <td style="width:10%" class="pm-head"><a href="javascript:PMSelectAllFail('<?php //echo $table;?>');">Fail</a></td>
                  <td style="width:10%" class="pm-head"><a href="javascript:PMSelectAllNone('<?php //echo $table;?>');">None</a></td> -->
                  <td style="width:10%" class="pm-head"><a href="#">Pass</a></td>
                  <td style="width:10%" class="pm-head"><a href="#">Fail</a></td>
                  <td style="width:10%" class="pm-head"><a href="#">None</a></td>
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
                <input name="<?php echo $tr;?>_no" id="<?php echo $tr;?>_no" type="text" class="pm-center" value="<?php echo $v['sequence_detail'];?>" readonly="readonly">
                </label>
             </td>
             <td width="40%">
                <label class="input">
                <input name="<?php echo $tr;?>_list" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly="readonly">
                </label>
             </td>
             <td width="10%" class="pm-list-boxs" box-id="<?php echo $tr;?>">
                <div style="width:19px; margin:0 auto">
                   <label class="radio" id="<?php echo $tr;?>_result_pass">
                   <input class="<?php echo $table;?>-pass click-reset" id="<?php echo $tr;?>-recheck-pass" name="<?php echo $tr;?>_result" value="Pass" type="radio" disabled="disabled" <?php echo (@$v['answer']['pm_status'] == 'Pass') ? 'checked' : ''; ?> ><i></i>
                   </label>
                </div>
             </td>
             <td width="10%">
                <div style="width:19px; margin:0 auto">
                   <label class="radio" id="<?php echo $tr;?>_result_fail">
                   <input class="<?php echo $table;?>-fail click-reset" id="<?php echo $tr;?>-recheck-fail" name="<?php echo $tr;?>_result" value="Fail" type="radio" disabled="disabled" <?php echo (@$v['answer']['pm_status'] == 'Fail') ? 'checked' : ''; ?> ><i></i>
                   </label>
                </div>
             </td>
             <td width="10%">
                <div style="width:19px; margin:0 auto">
                   <label class="radio" id="<?php echo $tr;?>_result_none">
                   <input class="<?php echo $table;?>-none click-reset" id="<?php echo $tr;?>-recheck-none" name="<?php echo $tr;?>_result" value="None" type="radio" disabled="disabled" <?php echo (@$v['answer']['pm_status'] == 'None') ? 'checked' : ''; ?>  ><i></i>
                   </label>
                </div>
             </td>
             <td>
                <label class="input">
                <input name="<?php echo $tr;?>_comment" type="text" class="pm-left" value="<?php echo (@$v['answer']['comment'] != '') ? @$v['answer']['comment'] : ''; ?>" readonly="readonly">
                </label>
             </td>
          </tr>

      <?php } ?>
   </tbody>

   </table>

</fieldset> 


   <?php }else if($value['choice_type']== 'note'){ ?>

<fieldset id="remove_<?php echo $table;?>" class="note-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
    <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="note">
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
        <section id="<?php echo $table;?>" class="table table-note">
        <label class="textarea textarea-resizable">                                         
            <textarea name="<?php echo $table;?>_notetext" id="<?php echo $table;?>_notetext" rows="3" class="custom-scroll note-input user-success" readonly="readonly"><?php echo (@$value['list'][0]['list'] != '') ? @$value['list'][0]['list'] : ''; ?></textarea>
        </label>
    </section>
</fieldset>

   <?php }


}

?>


<!-- NOTE -->

<!-- NOTE -->

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