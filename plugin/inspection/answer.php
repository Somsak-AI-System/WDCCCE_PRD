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
//                   alert($v);
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
               $status_list_template4[] = $v['list'];
           } ?>

           <table id="<?php echo $table;?>" style="display:table" class="table">
               <select name="per1" id="per1" disabled="disabled">
                   <option selected="selected" ><?php echo $status_list_template4[0];?> </option>
               </select>

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
//                       echo '<pre>'; print_r($statusCheck); echo '</pre>';

                       if ($v['list'] == $statusCheck){
                           $checked ='checked';
                       }else{
                           $checked ='';
                       }
                       ?>
                       <td class="<?php echo $tr;?>">
                           <label class="check" >
                               <input type="checkbox" name="<?php echo $tr;?>_list" disabled="disabled" <?php echo (@$statusCheck == 1) ? 'checked' : ''; ?> value="<?php echo $v['list'];?>"/>
                               <!--                               <input type="checkbox" name="--><?php //echo $tr;?><!--_list" disabled="disabled" --><?php //echo (@$v['list'] == 1) ? 'checked' : ''; ?><!-- value="--><?php //echo $v['list'];?><!--"/>-->
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
//                   echo '<pre>'; print_r($v); echo '</pre>';
                   $tr = 'tr'.generateRandomString();
                   ?>

                   <tr id="<?php echo $tr;?>" class="tr-form tr-form-<?php echo $table;?>">
                       <td>
                           <label class="radio">
                               <input type="radio" name="<?php echo $table;?>_result" disabled value="0" <?php echo (@$v['answer']['status_list_template7'] == '0') ? 'checked' : ''; ?> ><i></i>
                           </label>
                       </td>
                       <td>
                           <label class="radio">
                               <input type="radio" name="<?php echo $table;?>_result" disabled value="1" <?php echo (@$v['answer']['status_list_template7'] == '1') ? 'checked' : ''; ?> ><i></i>
                           </label>
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

<!--               --><?php //echo '<pre>'; print_r($value); echo '</pre>';?>
               <tr>
                   <th rowspan="4">
                       <label class="input">
                           <input name="<?php echo $table; ?>_head_col0" type="text" class="template9-inphead" value="<?php echo $value['head_col0']; ?>"
                                  style="text-align:center; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                       </label>
                   </th>
                   <th colspan="6">
                       <label class="input">
                           <input name="<?php echo $table; ?>_head_col1" type="text" class="template9-inphead" placeholder="Date:__/__/__-__/__/__" value="<?php echo @$value['list'][0]['answer']['detail_template9'];?>"
                                  style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                       </label>
                   </th>
               </tr>
               <tr>
                   <th rowspan="4">
                       <label class="input">
                           <input name="<?php echo $table; ?>_head_col2" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col2']; ?>"
                                  style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                       </label>
                   </th>
                   <th colspan="2">
                       <label class="input">
                           <input name="<?php echo $table; ?>_head_col3" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col3']; ?>"
                                  style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                       </label>
                   </th>
                   <th colspan="2">
                       <label class="input">
                           <input name="<?php echo $table; ?>_head_col4" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col4']; ?>"
                                  style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                       </label>
                   </th>
                   <th rowspan="4">
                       <label class="input">
                           <input name="<?php echo $table; ?>_head_col5" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col5']; ?>"
                                  style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                       </label>
                   </th>
               </tr>
               <tr>
                   <th colspan="2">
                       <label class="input">
                           <input name="<?php echo $table; ?>_head_col6" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col6']; ?>"
                                  style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                       </label>
                   </th>
                   <th colspan="2">
                       <label class="input">
                           <input name="<?php echo $table; ?>_head_col7" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col7']; ?>"
                                  style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                       </label>
                   </th>


               </tr>

               <tr>
                   <th>
                       <label class="input">
                           <input name="<?php echo $table; ?>_head_col8" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col8']; ?>"
                                  style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                       </label>
                   </th>
                   <th>
                       <label class="input">
                           <input name="<?php echo $table; ?>_head_col9" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col9']; ?>"
                                  style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                       </label>
                   </th>
                   <th>
                       <label class="input">
                           <input name="<?php echo $table; ?>_head_col10" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col10']; ?>"
                                  style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                       </label>
                   </th>
                   <th>
                       <label class="input">
                           <input name="<?php echo $table; ?>_head_col11" type="text" class="template9-inphead" placeholder="" value="<?php echo $value['head_col11']; ?>"
                                  style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                       </label>
                   </th>

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
                       <td style="width: 25%">
                           <label class="input">
                               <input type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" name="<?php echo $tr;?>_list" value='<?php echo $v['list'];?>' readonly="readonly">
                           </label>
                       </td>
                       <td style="width: 45%">
                           <label class="input">
                               <input type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" name="<?php echo $tr;?>_list2" value='<?php echo $v['answer']['detail_template10'];?>' readonly="readonly">
                           </label>
                       </td>
                       <td style="width: 10%">
                           <label class="checkbox" >
                               <input name="<?php echo $table;?>_level1[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" disabled="disabled"  <?php echo (@$v['answer']['status_list_template10'] == '1') ? 'checked' : ''; ?> ><i></i>
                           </label>
                       </td>
                       <td style="width: 10%">
                           <label class="checkbox" >
                               <input name="<?php echo $table;?>_level2[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" disabled="disabled"  <?php echo (@$v['answer']['status_list_template10'] == '2') ? 'checked' : ''; ?> ><i></i>
                           </label>
                       </td>
                       <td style="width: 10%">
                           <label class="checkbox" >
                               <input name="<?php echo $table;?>_level3[]" value="<?php echo $v['choicedetailid'];?>" type="checkbox" disabled="disabled"  <?php echo (@$v['answer']['status_list_template10'] == '3') ? 'checked' : ''; ?> ><i></i>
                           </label>
                       </td>
                   </tr>

               <?php } ?>
               </tbody>

           </table>

       </fieldset>

   <?php }else if($value['choice_type']== 'template11'){ ?>

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
                           <span class="fa fa-minus"></spacn>
                               <input name="<?php echo $tr;?>_list" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo $v['list'];?>' readonly="readonly">
                               <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template11'] == '1') ? 'checked' : ''; ?> ><i></i>
                       </td>
                   </tr>

               <?php } ?>
               </tbody>

           </table>

       </fieldset>

   <?php }else if($value['choice_type']== 'template12'){ ?>

       <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
           <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
           <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template12">
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
                       <td >
                           <label class="checkbox" >
                               <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template12'] == '1') ? 'checked' : ''; ?> ><i></i>
                           </label>
                       </td>
                       <td>
                           <label class="input" >
                               <input name="<?php echo $tr;?>_list2" type="text" class="template12-inphead" value="<?php echo @$v['answer']['data_list_template12'];?>"
                                      style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                           </label>
                       </td>

                       <td>
                       </td>
                   </tr>

               <?php } ?>
               </tbody>

           </table>

       </fieldset>
   <?php }else if($value['choice_type']== 'template13'){ ?>

       <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
           <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
           <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template12">
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

           <table id="<?php echo $table;?>" class="table table-bordered ">

               <tbody id="<?php echo $table;?>-sortable">

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

               <tbody id="<?php echo $table;?>-sortable">

               <?php
               foreach($value['list'] as $k =>$v){
                   $tr = 'tr'.generateRandomString();
                   ?>

                   <tr class="<?php echo $tr;?>">
                       <td >
                           <label class="input" >
                               <input name="<?php echo $tr;?>_list" type="text" class="template13-inphead" value="<?php echo $v['list'];?>"
                                      style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                           </label>
                       </td>
                       <td>
                       </td>
                       <td>
                       </td>
                   </tr>

                   <tr>
                       <td>
                           <label class="input" >
                               <input name="<?php echo $tr;?>_list" type="text" class="template13-inphead" value="<?php echo $v['sublist'];?>"
                                      style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                           </label>
                       </td>
                       <td >
                           <label class="checkbox" >
                               <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template13'] == '1') ? 'checked' : ''; ?> ><i></i>
                           </label>
                       </td>
                       <td>
                           <label class="input" >
                               <input name="<?php echo $tr;?>_list2" type="text" class="template12-inphead" value="<?php echo @$v['answer']['data_list_template13'];?>"
                                      style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                           </label>
                       </td>
                   </tr>

               <?php } ?>
               </tbody>

           </table>

       </fieldset>

   <?php }else if($value['choice_type']== 'template14'){ ?>

       <fieldset id="remove_<?php echo $table;?>" class="pm-form-box" style="padding-bottom:20px;">
           <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $table;?>">
           <input type="hidden" name="<?php echo $table;?>_type" id="<?php echo $table;?>_type" value="template12">
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

           <table id="<?php echo $table;?>" class="table table-bordered ">

               <tbody id="<?php echo $table;?>-sortable">

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

               <tbody id="<?php echo $table;?>-sortable">

               <?php
               foreach($value['list'] as $k =>$v){
                   $tr = 'tr'.generateRandomString();
                   ?>

                   <tr class="<?php echo $tr;?>">
                       <td >
                           <label class="input" >
                               <input name="<?php echo $tr;?>_list" type="text" class="template13-inphead" value="<?php echo $v['list'];?>"
                                      style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; text-align: center;" readonly>
                           </label>
                       </td>
                       <td >
                           <label class="checkbox" >
                               <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template14_1'] == '1') ? 'checked' : ''; ?> ><i></i>
                           </label>
                       </td>
                       <td>
                           <label class="checkbox" >
                               <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template14_2'] == '1') ? 'checked' : ''; ?> ><i></i>
                           </label>
                       </td>

                   </tr>



               <?php } ?>
               </tbody>

           </table>

       </fieldset>

   <?php }else if($value['choice_type']== 'template15'){ ?>

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

               <!--               --><?php //echo '<pre>'; print_r($value); echo '</pre>';?>
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

                   <tr class="<?php echo $tr;?>">
                       <td style="text-align: center;">
                           <span class="temp-input"><?php echo $v['list'];?></span>
                       </td>
                       <td>
                           <label class="input">
                               <input name="<?php echo $tr;?>_list1" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo (@$v['answer']['data_list_template15_1']); ?>' readonly>
                           </label>
                       </td>
                       <?php if ($value['head_col2']  != ''){ ?>
                           <td>
                               <label class="input">
                                   <input name="<?php echo $tr;?>_list2" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo (@$v['answer']['data_list_template15_2']); ?>' readonly>
                               </label>
                           </td>

                       <? } ?>
                       <td>
                           <span class="temp-input"><?php echo $v['accept_range'];?></span>
                       </td>
                       <td>
                           <div >
                               <label class="radio" >
                                   <input name="<?php echo $tr;?>_list3" value="1" type="radio" <?php echo (@$v['answer']['status_list_template15_1'] == '1') ? 'checked' : ''; ?> >Yes<i></i>
                               </label>
                           </div>
                           <div >
                               <label class="radio" >
                                   <input name="<?php echo $tr;?>_list4" value="0" type="radio" <?php echo (@$v['answer']['status_list_template15_1'] == '0') ? 'checked' : ''; ?> >No<i></i>
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
                           <label class="checkbox" >
                               <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template16_1'] == '1') ? 'checked' : ''; ?> ><i></i>
                           </label>
                       </td>
                       <td style="text-align: center;">
                           <label class="checkbox" >
                               <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template16_2'] == '1') ? 'checked' : ''; ?> ><i></i>
                           </label>
                       </td>
                       <td style="text-align: center;">
                           <label class="checkbox" >
                               <input name="<?php echo $tr;?>_result" value="" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template16_3'] == '1') ? 'checked' : ''; ?> ><i></i>
                           </label>
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
                       <td >
                           <label class="checkbox" >
                               <input name="<?php echo $tr;?>_result" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template17_1'] == '1') ? 'checked' : ''; ?> ><i></i>
                           </label>
                           <span class="temp-input" style="padding-left:50px;"><?php echo $v['list'];?></span>
                       </td>
                       <td >
                           <label class="checkbox" >
                               <input name="<?php echo $tr;?>_result" type="checkbox" disabled="disabled" <?php echo (@$v['answer']['status_list_template17_2'] == '1') ? 'checked' : ''; ?> ><i></i>
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
                                  style="text-align:left; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                       </label>
                   </td>
                   <td>
                       <label class="input">
                           <input name="<?php echo $table; ?>_list2" type="text" class="template17-inphead" placeholder="Time .......... Minutes" value="<?php echo @$v['answer']['data_list_template17_2'];?>"
                                  style="text-align:left; border:1px; border-bottom:1px dotted #CCCCCC; color:#0000FF; " readonly>
                       </label>
                   </td>
               </tr>
               </tfoot>
           </table>
       </fieldset>

   <?php }else if($value['choice_type']== 'template18'){ ?>

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
                                   <input name="<?php echo $tr;?>_list1" value="1" type="radio" <?php echo (@$v['answer']['status_list_template18_1'] == '1') ? 'checked' : ''; ?> >Yes<i></i>
                               </label>
                           </div>
                           <div >
                               <label class="radio" >
                                   <input name="<?php echo $tr;?>_list1" value="0" type="radio" <?php echo (@$v['answer']['status_list_template18_1'] == '0') ? 'checked' : ''; ?> >No<i></i>
                               </label>
                           </div>
                       </td>
                       <td>
                           <label class="input">
                               <input name="<?php echo $tr;?>_list2" type="text" class="pm-left pm-list-autocomplete ui-autocomplete-input user-success" value='<?php echo (@$v['answer']['data_list_template18_2']); ?>' readonly>
                           </label>
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