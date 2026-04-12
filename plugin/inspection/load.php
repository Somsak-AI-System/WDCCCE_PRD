<?php
print_r($_REQUEST);
?>
<?php if($_REQUEST['data'] == 'form-table-std' ){
  $text = 'table'.generateRandomString();

?>

    <fieldset id="remove_<?php echo $text; ?>" class="std-form-box" style="padding-bottom:20px;">
        <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $text; ?>" />
        <input type="hidden" name="<?php echo $text; ?>_type" id="<?php echo $text; ?>_type" value="standards" />
            <div class="row">
            <section class="col col-10" style="margin-bottom:5px;">
                <label class="input">
                    <input name="<?php echo $text; ?>_headtext" type="text" value="REFERENCE STANDARD INSTRUMENT:" class="note-head" style="border:0px; border-bottom:1px dotted #CCCCCC; font-style:italic; font-weight:bold; color:#0000FF;">
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
                            <a href="javascript:addRowSTD('<?php echo $text; ?>');"><i class="fa fa-plus-square"></i> เพิ่มแถว</a>
                        </li>
                        <li>
                            <a href="javascript:removeTable('remove_<?php echo $text; ?>');"><i class="fa fa-trash-o"></i> ลบตาราง</a>
                        </li>
                    </ul>
                </div>
            </section>
        </div>

            <section id="std-options-list-<?php echo $text; ?>" class="table"></section>

    <script>
        addRowSTD('<?php echo $text; ?>');
    </script>
    </fieldset>


<?php }elseif($_REQUEST['data'] == 'form-table-cal'){

  $text = 'table'.generateRandomString();

?>

<fieldset id="remove_<?php echo $text; ?>" class="cal-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $text; ?>" />
    <input type="hidden" name="<?php echo $text; ?>_type" id="<?php echo $text; ?>_type" value="calibrate" />
    <input type="hidden" class="uncersource_settings" name="<?php echo $text; ?>_usset" id="<?php echo $text; ?>_uncersource_setting" table-id="<?php echo $text; ?>" value="0" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u1][active]" id="<?php echo $text; ?>_source_active_u1" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u1][name]" id="<?php echo $text; ?>_source_name_u1" value="Repeatability of indication" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u1][value]" id="<?php echo $text; ?>_source_value_u1" value="lock" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u1][divisor]" id="<?php echo $text; ?>_divisor_u1" value="1" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u1][ci]" id="<?php echo $text; ?>_ci_u1" value="1" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u1][veff]" id="<?php echo $text; ?>_veff_u1" value="3" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u2][active]" id="<?php echo $text; ?>_source_active_u2" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u2][name]" id="<?php echo $text; ?>_source_name_u2" value="Resolution of UUC" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u2][value]" id="<?php echo $text; ?>_source_value_u2" value="lock" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u2][divisor]" id="<?php echo $text; ?>_divisor_u2" value="1.732" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u2][ci]" id="<?php echo $text; ?>_ci_u2" value="1" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u2][veff]" id="<?php echo $text; ?>_veff_u2" value="0" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u3][active]" id="<?php echo $text; ?>_source_active_u3" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u3][name]" id="<?php echo $text; ?>_source_name_u3" value="Resolution of Standard" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u3][value]" id="<?php echo $text; ?>_source_value_u3" value="lock" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u3][divisor]" id="<?php echo $text; ?>_divisor_u3" value="1.732" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u3][ci]" id="<?php echo $text; ?>_ci_u3" value="1" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u3][veff]" id="<?php echo $text; ?>_veff_u3" value="0" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u4][active]" id="<?php echo $text; ?>_source_active_u4" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u4][name]" id="<?php echo $text; ?>_source_name_u4" value="Accuracy of Standard (% of reading)" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u4][value]" id="<?php echo $text; ?>_source_value_u4" value="lock" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u4][divisor]" id="<?php echo $text; ?>_divisor_u4" value="1.732" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u4][ci]" id="<?php echo $text; ?>_ci_u4" value="1" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u4][veff]" id="<?php echo $text; ?>_veff_u4" value="0" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u5][active]" id="<?php echo $text; ?>_source_active_u5" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u5][name]" id="<?php echo $text; ?>_source_name_u5" value="Calibration of Standard" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u5][value]" id="<?php echo $text; ?>_source_value_u5" value="lock" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u5][divisor]" id="<?php echo $text; ?>_divisor_u5" value="lock" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u5][ci]" id="<?php echo $text; ?>_ci_u5" value="1" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u5][veff]" id="<?php echo $text; ?>_veff_u5" value="0" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u6][active]" id="<?php echo $text; ?>_source_active_u6" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u6][name]" id="<?php echo $text; ?>_source_name_u6" value="Accuracy of Standard (± Value)" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u6][value]" id="<?php echo $text; ?>_source_value_u6" value="lock" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u6][divisor]" id="<?php echo $text; ?>_divisor_u6" value="1.732" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u6][ci]" id="<?php echo $text; ?>_ci_u6" value="1" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u6][veff]" id="<?php echo $text; ?>_veff_u6" value="0" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u7][active]" id="<?php echo $text; ?>_source_active_u7" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u7][name]" id="<?php echo $text; ?>_source_name_u7" value="Drift of Standard" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u7][value]" id="<?php echo $text; ?>_source_value_u7" value="lock" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u7][divisor]" id="<?php echo $text; ?>_divisor_u7" value="1.732" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u7][ci]" id="<?php echo $text; ?>_ci_u7" value="1" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u7][veff]" id="<?php echo $text; ?>_veff_u7" value="0" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u8][active]" id="<?php echo $text; ?>_source_active_u8" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u8][name]" id="<?php echo $text; ?>_source_name_u8" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u8][value]" id="<?php echo $text; ?>_source_value_u8" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u8][divisor]" id="<?php echo $text; ?>_divisor_u8" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u8][ci]" id="<?php echo $text; ?>_ci_u8" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u8][veff]" id="<?php echo $text; ?>_veff_u8" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u9][active]" id="<?php echo $text; ?>_source_active_u9" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u9][name]" id="<?php echo $text; ?>_source_name_u9" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u9][value]" id="<?php echo $text; ?>_source_value_u9" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u9][divisor]" id="<?php echo $text; ?>_divisor_u9" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u9][ci]" id="<?php echo $text; ?>_ci_u9" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u9][veff]" id="<?php echo $text; ?>_veff_u9" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u10][active]" id="<?php echo $text; ?>_source_active_u10" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u10][name]" id="<?php echo $text; ?>_source_name_u10" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u10][value]" id="<?php echo $text; ?>_source_value_u10" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u10][divisor]" id="<?php echo $text; ?>_divisor_u10" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u10][ci]" id="<?php echo $text; ?>_ci_u10" value="" />
    <input type="hidden" name="<?php echo $text; ?>_uncer_source[u10][veff]" id="<?php echo $text; ?>_veff_u10" value="" />
    <input type="hidden" name="<?php echo $text; ?>_report_type" id="<?php echo $text; ?>_report_type" value="" />
    <input type="hidden" name="<?php echo $text; ?>_adds[phototherapy]" id="<?php echo $text; ?>_adds_phototherapy" class="<?php echo $text; ?>_adds" value="" />

        <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input"> <i class="icon-append fa" style="right:auto; left:5px; border-left:0px;">Parameter:</i>
                <input name="<?php echo $text; ?>_parameter" id="<?php echo $text; ?>_parameter" type="text" style="padding-left:75px; border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic;" value="">
            </label>
        </section>

        <section class="col col-2" style="margin-bottom:5px;">
            <div class="btn-group" style="float:right;">
                <button class="btn dropdown-toggle btn-sm btn-default" data-toggle="dropdown"><i class="fa fa-cog"></i></button>
                <a href="javascript:ShowHideTable('caltb-<?php echo $text; ?>');" id="caltb-<?php echo $text; ?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                <span class="btn btn-sm btn-default"><i class="fa fa-sort" style="cursor:move"></i></span>
                <ul class="dropdown-menu pull-right js-status-update">
                    <li><a href="javascript:addRow('<?php echo $text; ?>');"><i class="fa fa-plus-square"></i> เพิ่มแถว</a></li>
                    <li><a href="javascript:removeTable('remove_<?php echo $text; ?>');"><i class="fa fa-trash-o"></i> ลบตาราง</a></li>
                </ul>
            </div>
        </section>
    </div>

    <div id="caltb-<?php echo $text; ?>" style="display:block">

    <table id="<?php echo $text; ?>" class="table table-bordered table-form table-cal">
         <tr>

            <th colspan="4" style="padding:5px 2px 5px 2px; border-bottom:0px;">
                <div class="row" style="text-align:center">Unit</div>
            </th>
            <th style="padding:5px 2px 5px 2px; border-bottom:0px;" colspan="10">
                <div class="row" style="text-align:center">Tolerance
                    <span id="<?php echo $text; ?>-tolerance-error" class="invalid" style="font-weight:normal;"></span>
                    <select name="<?php echo $text; ?>_tolerance_type" id="<?php echo $text; ?>_tolerance_type" tb-id="<?php echo $text; ?>" style="font-weight:normal;">
                       <option value="1"> ระหว่าง Min และ Max </option>
                       <option value="2"> > มากกว่า </option>
                       <option value="3"> &ge; มากกว่าหรือเท่ากับ </option>
                       <option value="4"> < น้อยกว่า </option>
                       <option value="5"> &le; น้อยกว่าหรือเท่ากับ </option>
                       <option value="6"> N/A </option>
                    </select>
                </div>
            </th>
          </tr>
          <tr>
            <th colspan="4" style="border-top:0px;">
                <div id="<?php echo $text; ?>_unit_box">
                    <label class="select" style="width:80%; margin:0 auto">

                    <select name="<?php echo $text; ?>_unit" id="<?php echo $text; ?>_unit" style="width:100%;" class="select2 select2-offscreen" required="" tabindex="-1" title="">
                       <!-- List units form DB -->
                       <option value="Kg">Kg</option>
                       <option value="N">N</option>
                       <option value="Kgf">Kgf</option>
                       <option value="BPM">BPM</option>
                       <option value="%SPO₂">%SPO₂</option>
                       <option value="L/min">L/min</option>
                       <option value="mV">mV</option>
                       <option value="V">V</option>
                       <option value="hpa">hpa</option>
                       <option value="μW/cm²">μW/cm²</option>
                    </select>
                    </label>

                    <script>$("#<?php echo $text; ?>_unit").select2();</script>
                </div>
            </th>
            <th colspan="10" id="<?php echo $text; ?>-set-tolerance-form-box" style="border-top:0px;">
                <div class="row">
                    <div class="col col-lg-4 col-md-12">
                        <input name="<?php echo $text; ?>_check_tolerance_unit" id="<?php echo $text; ?>_check_tolerance_unit" class="<?php echo $text; ?>_check_tolerance" value="1" type="checkbox" style="float:left; margin-left:10px; margin-top:12px;" />
                        <label class="input" style="margin-left:25px;">
                            <i class="icon-append fa" style="right:auto; left:5px; border-left:0px;">&plusmn;</i>
                            <i class="icon-append fa" style="width:50px; border:0px;">Value</i>
                            <input name="<?php echo $text; ?>_tolerance_unit" id="<?php echo $text; ?>_tolerance_unit" type="text" style="padding-left:30px; border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;" value="0" required number="true">
                        </label>
                    </div>

                    <div class="col col-lg-4 col-md-12">
                        <input name="<?php echo $text; ?>_check_tolerance_percent" id="<?php echo $text; ?>_check_tolerance_percent" class="<?php echo $text; ?>_check_tolerance" value="1" type="checkbox" style="float:left; margin-left:10px; margin-top:12px;" />
                        <label class="input" style="margin-left:25px;">
                            <i class="icon-append fa" style="right:auto; left:5px; border-left:0px;">&plusmn;</i>
                            <i class="icon-append fa" style="width:50px; border:0px;">%</i>
                            <input name="<?php echo $text; ?>_tolerance_percent" id="<?php echo $text; ?>_tolerance_percent" type="text" style="padding-left:30px; border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;" value="0" required number="true">
                        </label>
                    </div>

                    <div class="col col-lg-4 col-md-12">
                        <input name="<?php echo $text; ?>_check_tolerance_fso" id="<?php echo $text; ?>_check_tolerance_fso" class="<?php echo $text; ?>_check_tolerance" value="1" type="checkbox" style="float:left; margin-left:10px; margin-top:12px;" />
                            <label class="input" style="margin-left:25px;">
                            <i class="icon-append fa" style="right:auto; left:5px; border-left:0px;">&plusmn;</i>
                            <i class="icon-append fa" style="width:50px; border:0px;">% FSO</i>
                            <input name="<?php echo $text; ?>_tolerance_fso_percent" id="<?php echo $text; ?>_tolerance_fso_percent" type="text" style="padding-left:30px; border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;" value="0" number="true">
                            <input name="<?php echo $text; ?>_tolerance_fso_val" id="<?php echo $text; ?>_tolerance_fso_val" type="hidden" value="0" />
                        </label>
                    </div>
                </div>
            </th>
          </tr>
          <tr>
            <td>
                <label class="input">
                    <input name="<?php echo $text; ?>_uncer_setting" type="text" class="table-form-head-text" style="font-style:italic;" value="UUC* Setting">
                </label>
            </td>
            <!-- <td colspan="5"> -->
            <td colspan="9">
                <label class="input">
                    <input name="<?php echo $text; ?>_uncer_reading" type="text" class="table-form-head-text" style="font-style:italic;" value="Standard Reading">
                </label>
            </td>
            <td colspan="4" style="text-align:center; font-weight:bold;">Acception</td>
          </tr>

          <tr>
            <td>
                <label class="input">
                    <input name="<?php echo $text; ?>_head_col1" type="text" class="table-form-head-text" style="font-style:italic;" value="Setting">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $text; ?>_head_col2" type="text" class="table-form-head-text" style="font-style:italic;" value="Reading 1">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $text; ?>_head_col3" type="text" class="table-form-head-text" style="font-style:italic;" placeholder="Reading 2" value="">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $text; ?>_head_col4" type="text" class="table-form-head-text" style="font-style:italic;" placeholder="Reading 3" value="">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $text; ?>_head_col5" type="text" class="table-form-head-text" style="font-style:italic;" placeholder="Reading 4" value="">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $text; ?>_head_col6" type="text" class="table-form-head-text" style="font-style:italic;" placeholder="Reading 5" value="">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $text; ?>_head_col7" type="text" class="table-form-head-text" style="font-style:italic;" placeholder="Reading 6" value="">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $text; ?>_head_col8" type="text" class="table-form-head-text" style="font-style:italic;" placeholder="Reading 7" value="">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $text; ?>_head_col9" type="text" class="table-form-head-text" style="font-style:italic;" placeholder="Reading 8" value="">
                </label>
            </td>

            <td><label class="input" style="text-align:center; font-size:95%;">Standard<br />Resolution</label></td>
            <td><label class="input" style="text-align:center">Min</label></td>
            <td><label class="input" style="text-align:center">Max</label></td>
            <td><label class="input" style="text-align:center">Average</label></td>
            <td style="text-align:center;">*</td>
           </tr>

          <tbody id="<?php echo $text; ?>-sortable"></tbody>
    </table>

    </div>

    <script>
    $('#<?php echo $text; ?>_tolerance_type').change(function(){
        var tbid = $(this).attr('tb-id');
        set_tolerance_type(tbid);
        CalculateTolerance(tbid);
    });

    $(function(){
        $( "#<?php echo $text; ?>-sortable" ).sortable();
        //$( "#<?php echo $text; ?>-sortable" ).disableSelection();
    });

    addRow('<?php echo $text; ?>');

      $("#<?php echo $text; ?>_tolerance_unit").keyup(function(){ CalculateTolerance('<?php echo $text; ?>'); });
      $("#<?php echo $text; ?>_tolerance_percent").keyup(function(){ CalculateTolerance('<?php echo $text; ?>'); });
      $("#<?php echo $text; ?>_tolerance_fso_percent").keyup(function(){ CalculateTolerance('<?php echo $text; ?>'); });
      $("#<?php echo $text; ?>_tolerance_fso_val").keyup(function(){ CalculateTolerance('<?php echo $text; ?>'); });
      $(".<?php echo $text; ?>_check_tolerance").change(function(){ CalculateTolerance('<?php echo $text; ?>'); });

    </script>

</fieldset>


<?php }elseif($_REQUEST['data'] == 'form-table-pm' ){

  $text = 'table'.generateRandomString();

?>

  <fieldset id="remove_<?php echo $text; ?>" class="pm-form-box" style="padding-bottom:20px;">
      <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $text; ?>" />
      <input type="hidden" name="<?php echo $text; ?>_type" id="<?php echo $text; ?>_type" value="pm" />
          <div class="row">
          <section class="col col-10" style="margin-bottom:5px;">
              <label class="input">
                  <input name="<?php echo $text; ?>_parameter" type="text" class="pm-inphead" value="" placeholder="Setting Head..." style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;" required>
              </label>
          </section>
          <section class="col col-2" style="margin-bottom:5px;">
              <div class="btn-group" style="float:right;">
                  <button class="btn dropdown-toggle btn-sm btn-default" data-toggle="dropdown">
                      <i class="fa fa-cog"></i>
                  </button>
                  <a href="javascript:ShowHideTable('<?php echo $text; ?>');" id="<?php echo $text; ?>-collapse" class="btn btn-sm btn-default"><i class="fa fa-minus"></i></a>
                  <span class="btn btn-sm btn-default"><i class="fa fa-sort" style="cursor:move"></i></span>
                  <ul class="dropdown-menu pull-right js-status-update">
                      <li>
                          <a href="javascript:addRowPM('<?php echo $text; ?>');"><i class="fa fa-plus-square"></i> เพิ่มแถว</a>
                      </li>
                      <li>
                          <a href="javascript:removeTable('remove_<?php echo $text; ?>');"><i class="fa fa-trash-o"></i> ลบตาราง</a>
                      </li>
                  </ul>
              </div>
          </section>
      </div>

      <table id="<?php echo $text; ?>" style="display:table" class="table table-bordered table-form table-pm" >
          <thead>
              <tr>
                  <td style="width:5%" class="pm-head"><a href="javascript:ResetPMRowNumber('<?php echo $text; ?>')">No. <i class="fa fa-arrow-down"></i></a></td>
                  <td style="width:40%" class="pm-head">List</td>
                  <td style="width:10%" class="pm-head"><a href="javascript:PMSelectAllPass('<?php echo $text; ?>');">Pass</a></td>
                  <td style="width:10%" class="pm-head"><a href="javascript:PMSelectAllFail('<?php echo $text; ?>');">Fail</a></td>
                  <td style="width:10%" class="pm-head"><a href="javascript:PMSelectAllNone('<?php echo $text; ?>');">None</a></td>
                  <td style="width:25%" class="pm-head" colspan="2">Comment</td>
              </tr>
          </thead>
          <tbody id="<?php echo $text; ?>-sortable"></tbody>
      </table>

      <script>
      addRowPM('<?php echo $text; ?>');

      $(function(){
          PMListSortable('<?php echo $text; ?>');
      });
      </script>

  </fieldset>

<?php }elseif($_REQUEST['data']=='form-table-note'){

  $text = 'table'.generateRandomString();

?>
<fieldset id="remove_<?php echo $text; ?>" class="note-form-box" style="padding-bottom:20px;">
    <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $text; ?>" />
    <input type="hidden" name="<?php echo $text; ?>_type" id="<?php echo $text; ?>_type" value="note" />
        <div class="row">
        <section class="col col-10" style="margin-bottom:5px;">
            <label class="input">
                <input name="<?php echo $text; ?>_headtext" type="text" value="Note:" class="note-head" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;">
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
                        <a href="javascript:removeTable('remove_<?php echo $text; ?>');"><i class="fa fa-trash-o"></i> ลบตาราง</a>
                    </li>
                </ul>
            </div>
        </section>
    </div>
        <section id="<?php echo $text; ?>" class="table table-note">
        <label class="textarea textarea-resizable">
            <textarea name="<?php echo $text; ?>_notetext" id="<?php echo $text; ?>_notetext" rows="3" class="custom-scroll note-input"></textarea>
        </label>
    </section>
</fieldset>

<?php }elseif($_REQUEST['data']=='form-tr-template2'){

    $text = 'table'.generateRandomString();

    ?>
    <fieldset id="remove_<?php echo $text; ?>" class="note-form-box" style="padding-bottom:20px;">
        <input type="hidden" name="tables_parameter[]" class="tables_parameter" value="<?php echo $text; ?>" />
        <input type="hidden" name="<?php echo $text; ?>_type" id="<?php echo $text; ?>_type" value="note" />
        <div class="row">
            <section class="col col-10" style="margin-bottom:5px;">
                <label class="input">
                    <input name="<?php echo $text; ?>_headtext" type="text" value="Note:" class="note-head" style="border:0px; border-bottom:1px dotted #CCCCCC; color:#0000FF; font-style:italic; font-weight:bold;">
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
                            <a href="javascript:removeTable('remove_<?php echo $text; ?>');"><i class="fa fa-trash-o"></i> ลบตาราง</a>
                        </li>
                    </ul>
                </div>
            </section>
        </div>
        <section id="<?php echo $text; ?>" class="table table-note">
            <label class="textarea textarea-resizable">
                <textarea name="<?php echo $text; ?>_notetext" id="<?php echo $text; ?>_notetext" rows="3" class="custom-scroll note-input"></textarea>
            </label>
        </section>
    </fieldset>

<?php  } ?>
<!--PTEST END Addrow-->


<?php if($_REQUEST['data'] == 'form-tr-std'){

  $tr = 'tr'.generateRandomString().$_REQUEST['uniqid'];

?>
        <section id="<?php echo $tr; ?>" class="table tr-form-<?php echo $_REQUEST['tbid']; ?>" style="margin-bottom:5px;">
            <input type="hidden" class="<?php echo $_REQUEST['tbid']; ?>_rows" name="<?php echo $_REQUEST['tbid']; ?>_rows[]" value="<?php echo $tr; ?>" />
            <label id="<?php echo $tr; ?>-all-stds-box" trid="<?php echo $tr; ?>" class="select all-stds-box">
                <select name="<?php echo $tr; ?>_std_code" id="<?php echo $tr; ?>_std_code" style="width:100%;" class="select2">
                    <option value="">Choose Standad Instrument</option>
                    <option value="10CO2001">10CO2001 (LG 1812-008) - CARBON DIOXIDE NITROGEN 10%</option>
                    <option value="5CO2001">5CO2001 (LG 1810-001) - CARBON DIOXIDE NITROGEN 5%</option>
                    <option value="DEFA004">DEFA004 (19MD1122) - Defibrillator Analyzer</option>
                    <option value="DEFABMC">DEFABMC (19MD1111) - Defibrillator Analyzer</option>
                    <option value="SOUT-DEFA001">SOUT-DEFA001 (19MD856) - Defibrillator Analyzer</option>
                    <option value="DEFA003">DEFA003 (19MD627) - Defibrillator Analyzer</option>
                    <option value="DEFA001">DEFA001 (19MD455) - Defibrillator Analyzer</option>
                    <option value="DEFA002">DEFA002 (19MD427) - Defibrillator Analyzer</option>
                    <option value="DEFA006 ">DEFA006  (20MD424) - DEFIBRILLATOR ANALYZER </option>
                    <option value="SOUT-TRAC001">SOUT-TRAC001 (19B665) - Digital Force Gaue</option>
                    <option value="TRAC005">TRAC005 (19B832) - Digital Force Gauge</option>
                    <option value="TRAC008">TRAC008 (19B808) - Digital Force Gauge</option>
                    <option value="TRAC007">TRAC007 (19b520) - Digital Force Gauge</option>
                    <option value="TRAC004">TRAC004 (19B448) - Digital Force Gauge</option>
                    <option value="TRAC003">TRAC003 (19B387) - Digital Force Gauge</option>
                    <option value="OSCI006">OSCI006 (Wk1909-126-1) - Digital oscilloscope</option>
                    <option value="TACH008">TACH008 (20E1322) - DIGITAL TACHOMETER</option>
                    <option value="TACH005">TACH005 (19E4894) - Digital Tachometer </option>
                    <option value="TACH004">TACH004 (19E4789) - Digital Tachometer </option>
                    <option value="TACHBMC">TACHBMC (19E4742) - Digital Tachometer</option>
                    <option value="TACH007">TACH007 (19E3865) - Digital Tachometer</option>
                    <option value="SOUT-TACH001">SOUT-TACH001 (19E3850) - Digital Tachometer</option>
                    <option value="TACH003">TACH003 (19E2972) - Digital Tachometer</option>
                    <option value="TACH001">TACH001 (19E2298) - Digital Tachometer</option>
                    <option value="TACH002">TACH002 (19E2263) - Digital Tachometer</option>
                    <option value="SOUT-DTEM001">SOUT-DTEM001 (19T2558) - Digital Thermometer</option>
                    <option value="ELSA004">ELSA004 (19MD116) - Electrical Safety Analyzer</option>
                    <option value="SOUT-ELSA001">SOUT-ELSA001 (19MD857) - Electrical Safety Analyzer</option>
                    <option value="ELSA003">ELSA003 (19MD628) - Electrical Safety Analyzer</option>
                    <option value="ELSABMC">ELSABMC (19MD525) - Electrical Safety Analyzer</option>
                    <option value="ELSA002">ELSA002 (19MD425) - Electrical Safety Analyzer</option>
                    <option value="ELSA007 ">ELSA007  (20MD426) - ELECTRICAL SAFETY ANALYZER </option>
                    <option value="ELEB001">ELEB001 (TH2047-011-110819-AC) - Electronic Balance</option>
                    <option value="ELEA007">ELEA007 (20MD426) - Electrosurgical Analyzer</option>
                    <option value="ELEA004">ELEA004 (19MD1121) - Electrosurgical Analyzer</option>
                    <option value="ELEABMC">ELEABMC (19MD1112) - Electrosurgical Analyzer</option>
                    <option value="SOUT-ELEA001">SOUT-ELEA001 (19MD859) - Electrosurgical Analyzer</option>
                    <option value="ELEA003">ELEA003 (19MD629) - Electrosurgical Analyzer</option>
                    <option value="ELEA001">ELEA001 (19MD456) - Electrosurgical Analyzer</option>
                    <option value="ELEA002">ELEA002 (19MD430) - Electrosurgical Analyzer</option>
                    <option value="FETM004">FETM004 (19MD1116) - Fetal Simulator</option>
                    <option value="FETM007">FETM007 (19MD921) - FETAL SIMULATOR</option>
                    <option value="SOUT-FETS001">SOUT-FETS001 (19MD858) - Fetal Simulator</option>
                    <option value="FETM003">FETM003 (19MD635) - Fetal Simulator</option>
                    <option value="FETM006">FETM006 (19MD523) - Fetal Simulator</option>
                    <option value="FLOT008">FLOT008 (342113) - Flow meter Tester</option>
                    <option value="FLOT006">FLOT006 (19MD1123) - Flowmeter Tester</option>
                    <option value="FLOT009">FLOT009 (342113) - Flowmeter Tester</option>
                    <option value="FLOT004">FLOT004 (19MD636) - Flowmeter Tester</option>
                    <option value="FLOT005">FLOT005 (19MD519) - Flowmeter Tester</option>
                    <option value="FLOT002">FLOT002 (19MD421) - Flowmeter Tester</option>
                    <option value="FLOTBMC">FLOTBMC (19MD458) - Flowmeter Tester with Regulator</option>
                    <option value="FUNG001">FUNG001 (19E4216) - Function Generator</option>
                    <option value="GASA004">GASA004 (19MD1120) - Gas Analyzer</option>
                    <option value="GASA003">GASA003 (19MD631) - Gas Analyzer</option>
                    <option value="GASA002">GASA002 (19MD429) - Gas Analyzer</option>
                    <option value="GASA009 ">GASA009  (19MD349) - GAS ANALYZER</option>
                    <option value="GASA007 ">GASA007  (20MD420) - GAS ANALYZER </option>
                    <option value="GASA008 ">GASA008  (19MD350) - GAS ANALYZER </option>
                    <option value="GASA005">GASA005 (19MD521) - Gas Flow Analyzer</option>
                    <option value="SOUT-GASA001">SOUT-GASA001 (19MD851) - Gas Flow Meter</option>
                    <option value="GASI004">GASI004 (19MD1117) - Gas Indicator</option>
                    <option value="SOUT-GAIN001">SOUT-GAIN001 (19MD855) - GAS Indicator</option>
                    <option value="GASI003">GASI003 (19MD633) - Gas Indicator</option>
                    <option value="GASI001">GASI001 (19MD452) - Gas Indicator</option>
                    <option value="GASI002">GASI002 (19MD422) - Gas Indicator</option>
                    <option value="HYGT004">HYGT004 (19H3050/19P5182) - Hygro-Thermometer</option>
                    <option value="SOUT-HYGR001">SOUT-HYGR001 (19H2533) - Hygro-Thermometer</option>
                    <option value="SOUT-HYGR002">SOUT-HYGR002 (19H2532) - Hygro-Thermometer</option>
                    <option value="HYGT012">HYGT012 (19H2258) - Hygro-Thermometer</option>
                    <option value="HYGT011">HYGT011 (19H2061/19P3390) - Hygro-Thermometer</option>
                    <option value="HYGT003">HYGT003 (19H1925) - Hygro-Thermometer</option>
                    <option value="HYGTBMC">HYGTBMC (19H1748) - Hygro-Thermometer</option>
                    <option value="HYGT013">HYGT013 (19H1451) - Hygro-Thermometer</option>
                    <option value="HYGT005">HYGT005 (19H1441) - Hygro-Thermometer</option>
                    <option value="HYGT002">HYGT002 (19H1870/19P3143) - Hygro-Thermometer (On site)</option>
                    <option value="HYGT006">HYGT006 (20P1558/20H848) - Hygro-Thermometer (Temp.Room)</option>
                    <option value="INFR001">INFR001 (T20-008) - Infrared Temperature Calibrator</option>
                    <option value="INP011">INP011 (19MD943) - Infusion Device Analyzer</option>
                    <option value="INP010">INP010 (19MD922) - Infusion Device Analyzer</option>
                    <option value="INPT009">INPT009 (19MD520) - Infusion Device Analyzer</option>
                    <option value="INPT008">INPT008 (19MD454) - Infusion Device Analyzer</option>
                    <option value="SOUT-INDA001">SOUT-INDA001 (19MD853) - Infusion Device Analyzey</option>
                    <option value="INPT004">INPT004 (19MD1119) - Infusion Pump Tester</option>
                    <option value="INPT003">INPT003 (19MD630) - Infusion Pump Tester</option>
                    <option value="INPT002">INPT002 (19MD428) - Infusion Pump Tester</option>
                    <option value="LUXM003">LUXM003 (19PH727) - LUX Meter</option>
                    <option value="LUXM002">LUXM002 (19PH620) - LUX Meter</option>
                    <option value="LUXM001">LUXM001 (19PH321) - LUX Meter</option>
                    <option value="IAQ0001">IAQ0001 (L1905-598) - Multi-Functional Measurement</option>
                    <option value="MULT009">MULT009 (19E2296) - Multimeter</option>
                    <option value="NIBA001">NIBA001 (19MD980) - NIBP Analyzer</option>
                    <option value="OSCI004">OSCI004 (19E4788) - Oscilloscope</option>
                    <option value="SOUT-OSCI001">SOUT-OSCI001 (19E3852) - Oscilloscope</option>
                    <option value="OSCI003">OSCI003 (19E2963) - Oscilloscope</option>
                    <option value="OSCI001">OSCI001 (19E2566) - Oscilloscope</option>
                    <option value="OSCI002">OSCI002 (19E2239) - Oscilloscope</option>
                    <option value="SOUT-OXGA001">SOUT-OXGA001 (19MD846) - Oxygen Analyzer </option>
                    <option value="OXYA004 ">OXYA004  (20DM423) - OXYGEN ANALYZER</option>
                    <option value="OXYA001">OXYA001 (19MD451) - Oxygen Analyzer</option>
                    <option value="OXYA002">OXYA002 (19MD423) - Oxygen Analyzer</option>
                    <option value="PATS001">PATS001 (19MD979) - Patient Simulator</option>
                    <option value="PHOT004">PHOT004 (19MD1118) - Phototherapy Radiometer</option>
                    <option value="PHOT006">PHOT006 (J1279/D020/0060) - Phototherapy Radiometer</option>
                    <option value="PHOT003">PHOT003 (19MD634) - Phototherapy Radiometer</option>
                    <option value="PHOT001">PHOT001 (19MD453) - Phototherapy Radiometer</option>
                    <option value="PHOT002">PHOT002 (19MD424) - Phototherapy Radiometer</option>
                    <option value="SOUT-PHOT001">SOUT-PHOT001 (19MD850) - Phototherapy Radiomrter </option>
                    <option value="POUP004">POUP004 (19MD1124) - Portable Ultrasound Power Meter</option>
                    <option value="POUPBMC">POUPBMC (15MD526) - Portable Ultrasound Power Meter</option>
                    <option value="POUP002">POUP002 (19MD449) - Portable Ultrasound Power Meter</option>
                    <option value="POUP006">POUP006 (20md422) - Portable Ultrasound therapy Tester</option>
                    <option value="PART007">PART007 (19P5092) - Pressure Calibrator</option>
                    <option value="SOUT-PART001">SOUT-PART001 (19P4079) - Pressure Calibrator</option>
                    <option value="PART004">PART004 (19MD637) - Pressure Calibrator</option>
                    <option value="PART010">PART010 (19p3077) - Pressure Calibrator</option>
                    <option value="PART005">PART005 (19P2621) - Pressure Calibrator</option>
                    <option value="PARTBMC">PARTBMC (19P2288) - Pressure Calibrator</option>
                    <option value="PART006">PART006 (19P2215) - Pressure Calibrator</option>
                    <option value="PART011 ">PART011  (20P1543) - PRESSURE CALIBRATOR </option>
                    <option value="SOUT-PRED001">SOUT-PRED001 (19P4078) - Pressure Data logger    </option>
                    <option value="PRED007">PRED007 (20P1511) - Pressure Data logger</option>
                    <option value="PREDBMC">PREDBMC (P-2002133-1/S1) - Pressure Data logger</option>
                    <option value="PRED002">PRED002 (19P2216) - Pressure Data logger</option>
                    <option value="SOLM004">SOLM004 (20E0489) - Sound Level Meter</option>
                    <option value="SOUT-SOLM001">SOUT-SOLM001 (19E10889) - Sound Level Meter</option>
                    <option value="SOLMBMC">SOLMBMC (EEL.BP.75/0862) - Sound Level Meter</option>
                    <option value="SOLM006">SOLM006 (EEL.BP.76/0862) - Sound Level Meter</option>
                    <option value="STHS001">STHS001 (LAB-H193213) - Stability Temperature and Humidity Systems</option>
                    <option value="STOW004">STOW004 (19E3866) - Stop watch</option>
                    <option value="SOUT-STOW001">SOUT-STOW001 (19E3851) - Stop Watch</option>
                    <option value="STOW005">STOW005 (19E3099) - Stop Watch</option>
                    <option value="STOW006">STOW006 (19E3100) - Stop Watch</option>
                    <option value="STOW003">STOW003 (19E2955) - Stop watch</option>
                    <option value="STOW001">STOW001 (19E2299) - Stop Watch</option>
                    <option value="STBS001">STBS001 (19E2281) - Stroboscope</option>
                    <option value="SYRC001">SYRC001 (19MD522) - Syringe Calibrator</option>
                    <option value="SYRC002">SYRC002 (19MD420) - Syringe Calibrator</option>
                    <option value="HYGT009">HYGT009 (19H1871) - Temperature and Humidity Tester</option>
                    <option value="TEMC006">TEMC006 (T20-011) - Temperature Calibrator </option>
                    <option value="TEMC007">TEMC007 (T19-265) - Temperature Calibrator </option>
                    <option value="SOUT-TEMC001">SOUT-TEMC001 (19I1280) - Temperature Calibrator (Sort)</option>
                    <option value="TEMC009">TEMC009 (T20-009) - Temperature Calibrator (Source)</option>
                    <option value="TEMC005">TEMC005 (19I1612) - Temperature Calibrator (Source)</option>
                    <option value="TEMC010">TEMC010 (T19-396) - Temperature Calibrator (Source)</option>
                    <option value="TEMC004">TEMC004 (19I969) - Temperature Calibrator (Source)</option>
                    <option value="TEMC001">TEMC001 (19I769) - Temperature Calibrator (Source)</option>
                    <option value="TEMC008">TEMC008 (19I757) - Temperature Calibrator (Source)</option>
                    <option value="TEMC011">TEMC011 (19I648) - Temperature Calibrator (Source)</option>
                    <option value="TEMC012">TEMC012 (T19-245) - Temperature Calibrator (Source) with RTD Probe</option>
                    <option value="TEMC002">TEMC002 (20I11) - Temperature Calibrator 12 CH</option>
                    <option value="TEMC003">TEMC003 (19I1312) - Temperature Calibrator 12 CH</option>
                    <option value="TEMT011 ">TEMT011  (20T826) - TEMPERATURE CALIBRATOR </option>
                    <option value="SOUT-TEMD001">SOUT-TEMD001 (19I1304) - Temperature Data logger</option>
                    <option value="TEMD009">TEMD009 (19I681) - Temperature Data logger</option>
                    <option value="TEMT010">TEMT010 (20T853) - Temperature Tester</option>
                    <option value="TEMT005">TEMT005 (DT20010173) - Temperature Tester</option>
                    <option value="TEMT009">TEMT009 (19T2880) - Temperature Tester</option>
                    <option value="TEMT003">TEMT003 (DT19082391) - Temperature Tester</option>
                    <option value="TEMT002">TEMT002 (DT19061839) - Temperature Tester</option>
                    <option value="TEMT008">TEMT008 (19T1633) - Temperature Tester</option>
                    <option value="TEMD006">TEMD006 (19I1617) - Themperature, Data logger</option>
                    <option value="TEMD007">TEMD007 (19I1044) - Themperature, Data logger</option>
                    <option value="TEMD004">TEMD004 (19I1045) - Themperature, Data logger</option>
                    <option value="TEMD001">TEMD001 (19I753) - Themperature, Data logger</option>
                    <option value="TEMD002">TEMD002 (19I754) - Themperature, Data logger</option>
                    <option value="HYGT014">HYGT014 (WK1910-164-1) - Thermohyg Rometer</option>
                    <option value="TEMT007">TEMT007 (19I1452) - Thermometer tester</option>
                    <option value="SOUT-MULT001">SOUT-MULT001 (19E3892) - True RMS Multimeter</option>
                    <option value="SOUT-ULPM001">SOUT-ULPM001 (19MD852) - Ultrasound Power Meter</option>
                    <option value="VTSS006">VTSS006 (20DM421) - Vital Signs Simulator</option>
                    <option value="VTSS004">VTSS004 (19MD1125) - Vitalsign Simulator</option>
                    <option value="SOUT-VTSS001">SOUT-VTSS001 (19MD854) - Vitalsign Simulator</option>
                    <option value="VTSS003">VTSS003 (19MD626) - Vitalsign Simulator</option>
                    <option value="VTSS001">VTSS001 (19MD524) - Vitalsign Simulator</option>
                    <option value="VTSS002">VTSS002 (19MD431) - Vitalsign Simulator</option>
                    <option value="WEIS009">WEIS009 (19-107553) - Weight Set  (M3, 1kg - 120 kg)</option>
                    <option value="WEIS006">WEIS006 (M1906233S) - Weight Set  LAB ( E2, 1g - 500 g)</option>
                    <option value="WEIS010">WEIS010 (19-107541) - Weight Set  LAB (E2, 1 g - 500 g)</option>
                    <option value="WEIS004">WEIS004 (M1908228S) - Weight Set  LAB (E2, 1 g - 500 g)</option>
                    <option value="WEIS003">WEIS003 (M1906232S) - Weight Set  LAB (E2, 1mg - 500 mg)</option>
                    <option value="WEIS001">WEIS001 (M1908173S) - Weight Set  LAB (F1, 50mg - 500g)</option>
                    <option value="WEIS007">WEIS007 (19M1946) - Weight Set (M3, 1kg - 120 kg)</option>
                    <option value="WEIS002">WEIS002 (19M1531) - Weight Set (M3, 1kg - 120 kg)</option>
                    <option value="WEIS005">WEIS005 (19M1539) - Weight Set (M3, 1kg-120 kg)</option>
                    <option value="WEIS013">WEIS013 (M1909173S) - Weight Set LAB (E2, 1 g - 500 g)</option>
                    <option value="WEIS014">WEIS014 (CCM-0495-19-C) - Weights set (1 g - 500 g)</option>
                    <option value="WEIS014">WEIS014 (CCM-0495-19-C) - Weights set (1 g - 500 g)</option>
                    <option value="WEIS015">WEIS015 (CCM-0496-19-C) - Weights set (1 g-500 g)</option>
                    <option value="SOUT-WEIS003">SOUT-WEIS003 (19M2424) - Weights Set (1-120 kg)</option>
                    <option value="SOUT-WEIS001">SOUT-WEIS001 (19M2422) - Weights Set (50 mg-1kg)</option>
                    <option value="SOUT-WEIS002">SOUT-WEIS002 (19M2423) - Weights Set (500g)</option>
                    <option value="remove">--- Remove ---</option>
                </select>
            </label>

        <script>
        $('#<?php echo $tr; ?>_std_code').change(function(){
            if( $('#<?php echo $tr; ?>_std_code').val()=="remove" ){
                if($('.tr-form-<?php echo $_REQUEST['tbid']; ?>').length > 1){
                    $('#<?php echo $tr; ?>').remove();
                }else{
                    $('#<?php echo $tr; ?>_std_code').select2('val', '');
                }
            }
        });

        $('#<?php echo $tr; ?>_std_code').select2();</script>
        </section>


<?php }elseif($_REQUEST['data'] == 'form-tr-cal'){

  $tr = 'tr'.generateRandomString().$_REQUEST['uniqid'];

?>

        <tr id="<?php echo $tr; ?>" class="tr-form tr-form-<?php echo $_REQUEST['tbid']; ?>">
            <td style="position:relative;">
                <input type="hidden" class="<?php echo $_REQUEST['tbid']; ?>_rows" name="<?php echo $_REQUEST['tbid']; ?>_rows[]" value="<?php echo $tr; ?>" />
                <input type="hidden" id="<?php echo $tr; ?>_has_input" value="" />
                <label class="input">
                    <input name="<?php echo $tr; ?>_col1" id="<?php echo $tr; ?>_col1" type="text" class="table-form-input-text set_<?php echo $tr; ?>" value="">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $tr; ?>_col2" id="<?php echo $tr; ?>_col2" type="text" class="table-form-input-text read_<?php echo $tr; ?> warning-over" tb-id="<?php echo $_REQUEST['tbid']; ?>" warning-id="<?php echo $tr; ?>" value="" number="true">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $tr; ?>_col3" id="<?php echo $tr; ?>_col3" type="text" class="table-form-input-text read_<?php echo $tr; ?> warning-over" tb-id="<?php echo $_REQUEST['tbid']; ?>" warning-id="<?php echo $tr; ?>" value="" number="true">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $tr; ?>_col4" id="<?php echo $tr; ?>_col4" type="text" class="table-form-input-text read_<?php echo $tr; ?> warning-over" tb-id="<?php echo $_REQUEST['tbid']; ?>" warning-id="<?php echo $tr; ?>" value="" number="true">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $tr; ?>_col5" id="<?php echo $tr; ?>_col5" type="text" class="table-form-input-text read_<?php echo $tr; ?> warning-over" tb-id="<?php echo $_REQUEST['tbid']; ?>" warning-id="<?php echo $tr; ?>" value="" number="true">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $tr; ?>_col6" id="<?php echo $tr; ?>_col6" type="text" class="table-form-input-text read_<?php echo $tr; ?> warning-over" tb-id="<?php echo $_REQUEST['tbid']; ?>" warning-id="<?php echo $tr; ?>" value="" number="true">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $tr; ?>_col7" id="<?php echo $tr; ?>_col7" type="text" class="table-form-input-text read_<?php echo $tr; ?> warning-over" tb-id="<?php echo $_REQUEST['tbid']; ?>" warning-id="<?php echo $tr; ?>" value="" number="true">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $tr; ?>_col8" id="<?php echo $tr; ?>_col8" type="text" class="table-form-input-text read_<?php echo $tr; ?> warning-over" tb-id="<?php echo $_REQUEST['tbid']; ?>" warning-id="<?php echo $tr; ?>" value="" number="true">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $tr; ?>_col9" id="<?php echo $tr; ?>_col9" type="text" class="table-form-input-text read_<?php echo $tr; ?> warning-over" tb-id="<?php echo $_REQUEST['tbid']; ?>" warning-id="<?php echo $tr; ?>" value="" number="true">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $tr; ?>_std_resolution" id="<?php echo $tr; ?>_std_resolution" type="text" class="table-form-input-text resolution_<?php echo $tr; ?>" value="" number="true">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $tr; ?>_min" id="<?php echo $tr; ?>_min" type="text" class="table-form-input-text caltol_<?php echo $tr; ?>" value="" number="true">
                </label>
            </td>
            <td>
                <label class="input">
                    <input name="<?php echo $tr; ?>_max" id="<?php echo $tr; ?>_max" type="text" class="table-form-input-text caltol_<?php echo $tr; ?>" value="" number="true">
                </label>
            </td>
            <td style="text-align:center">
                <label class="input" id="<?php echo $tr; ?>_average">0.00</label>
            </td>
                <td style="text-align:center; width:35px;">
                <a href="javascript:removeRow('<?php echo $tr; ?>', '<?php echo $_REQUEST['tbid']; ?>');"><i class="fa fa-times txt-color-red"></i></a>
            </td>
            </tr>

        <script>
        $("#<?php echo $tr; ?>_col1").keyup(function(){ CalculateTolerance('<?php echo $_REQUEST['tbid']; ?>'); });
        $(".read_<?php echo $tr; ?>").keyup(function(){ CalculateAverage('<?php echo $tr; ?>'); });
        $(".caltol_<?php echo $tr; ?>").keyup(function(){ CalculateAverage('<?php echo $tr; ?>'); });
        $(".warning-over").blur(function(){
            var TbID = $(this).attr("tb-id");
            var trid = $(this).attr("warning-id");
            var ToleranceType = parseInt($('#'+TbID+'_tolerance_type').val());

            var input = parseFloat($(this).val());
            var accept_min = parseFloat($("#"+trid+"_min").val());
            var accept_max = parseFloat($("#"+trid+"_max").val());

            if(ToleranceType>1){
                if(ToleranceType==2){
                    if(input > accept_min){ $(this).css('background-color', '#FFFFFF'); }else{ $(this).css('background-color', '#EFE1B3'); }
                }else
                if(ToleranceType==3){
                    if(input >= accept_min){ $(this).css('background-color', '#FFFFFF'); }else{ $(this).css('background-color', '#EFE1B3'); }
                }else
                if(ToleranceType==4){
                    if(input < accept_max){ $(this).css('background-color', '#FFFFFF'); }else{ $(this).css('background-color', '#EFE1B3'); }
                }else
                if(ToleranceType==5){
                    if(input <= accept_max){ $(this).css('background-color', '#FFFFFF'); }else{ $(this).css('background-color', '#EFE1B3'); }
                }
            }else{
                if(input < accept_min || input > accept_max){
                    $(this).css('background-color', '#EFE1B3');
                }else{
                    $(this).css('background-color', '#FFFFFF');
                }
            }
        });
        </script>

<?php }elseif($_REQUEST['data'] == 'form-tr-pm' ){

  $tr = 'tr'.generateRandomString().$_REQUEST['uniqid'];

?>

    <tr id="<?php echo $tr; ?>" class="tr-form tr-form-<?php echo $_REQUEST['tbid']; ?>">
       <td width="5%" style="position:relative;">
          <input type="hidden" class="<?php echo $_REQUEST['tbid']; ?>_rows" name="<?php echo $_REQUEST['tbid']; ?>_rows[]" value="<?php echo $tr; ?>" />
          <label class="input">
          <input name="<?php echo $tr; ?>_no" id="<?php echo $tr; ?>_no" type="text" class="pm-center" value="" required>
          </label>
       </td>
       <td width="40%">
          <label class="input">
          <input name="<?php echo $tr; ?>_list" type="text" class="pm-left pm-list-autocomplete" value="" required>
          </label>
       </td>
       <td width="10%" class="pm-list-boxs" box-id="<?php echo $tr; ?>">
          <div style="width:19px; margin:0 auto">
             <label class="radio" id="<?php echo $tr; ?>_result_pass">
             <input class="<?php echo $_REQUEST['tbid']; ?>-pass click-reset" id="<?php echo $tr; ?>-recheck-pass" name="<?php echo $tr; ?>_result" value="Pass" type="radio" disabled="disabled"><i></i>
             </label>
          </div>
       </td>
       <td width="10%">
          <div style="width:19px; margin:0 auto">
             <label class="radio" id="<?php echo $tr; ?>_result_fail">
             <input class="<?php echo $_REQUEST['tbid']; ?>-fail click-reset" id="<?php echo $tr; ?>-recheck-fail" name="<?php echo $tr; ?>_result" value="Fail" type="radio" disabled="disabled"><i></i>
             </label>
          </div>
       </td>
       <td width="10%">
          <div style="width:19px; margin:0 auto">
             <label class="radio" id="<?php echo $tr; ?>_result_none">
             <input class="<?php echo $_REQUEST['tbid']; ?>-none click-reset" id="<?php echo $tr; ?>-recheck-none" name="<?php echo $tr; ?>_result" value="None" type="radio" disabled="disabled"><i></i>
             </label>
          </div>
       </td>
       <td>
          <label class="input">
          <input name="<?php echo $tr; ?>_comment" type="text" class="pm-left" value="" >
          </label>
       </td>
       <td style="text-align:center; width:45px;">
          <a href="javascript:removeRow('<?php echo $tr; ?>', '<?php echo $_REQUEST['tbid']; ?>');"><i class="fa fa-times txt-color-red"></i></a>
       </td>
    </tr>

<?php } ?>

<!-- Action Calibrate -->
<?php

if($_REQUEST['data'] == 'tolerance-form-box' ){

    if($_REQUEST['type']== '1'){ ?>

      <div class="row">
        <div class="col col-lg-4 col-md-12">
          <input name="<?php echo $_REQUEST['tbid']; ?>_check_tolerance_unit" id="<?php echo $_REQUEST['tbid']; ?>_check_tolerance_unit" class="<?php echo $_REQUEST['tbid']; ?>_check_tolerance" value="1" type="checkbox" style="float:left; margin-left:10px; margin-top:12px;" />
          <label class="input" style="margin-left:25px;">
              <i class="icon-append fa" style="right:auto; left:5px; border-left:0px;">&plusmn;</i>
                <i class="icon-append fa" style="width:50px; border:0px;">Value</i>
                <input name="<?php echo $_REQUEST['tbid']; ?>_tolerance_unit" id="<?php echo $_REQUEST['tbid']; ?>_tolerance_unit" type="text" style="padding-left:30px; border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;" value="0" required number="true">
            </label>
        </div>
        <div class="col col-lg-4 col-md-12">
          <input name="<?php echo $_REQUEST['tbid']; ?>_check_tolerance_percent" id="<?php echo $_REQUEST['tbid']; ?>_check_tolerance_percent" class="<?php echo $_REQUEST['tbid']; ?>_check_tolerance" value="1" type="checkbox" style="float:left; margin-left:10px; margin-top:12px;" />
          <label class="input" style="margin-left:25px;">
              <i class="icon-append fa" style="right:auto; left:5px; border-left:0px;">&plusmn;</i>
                <i class="icon-append fa" style="width:50px; border:0px;">%</i>
                <input name="<?php echo $_REQUEST['tbid']; ?>_tolerance_percent" id="<?php echo $_REQUEST['tbid']; ?>_tolerance_percent" type="text" style="padding-left:30px; border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;" value="0" required number="true">
            </label>
        </div>
        <div class="col col-lg-4 col-md-12">
          <input name="<?php echo $_REQUEST['tbid']; ?>_check_tolerance_fso" id="<?php echo $_REQUEST['tbid']; ?>_check_tolerance_fso" class="<?php echo $_REQUEST['tbid']; ?>_check_tolerance" value="1" type="checkbox" style="float:left; margin-left:10px; margin-top:12px;" />
              <label class="input" style="margin-left:25px;">
              <i class="icon-append fa" style="right:auto; left:5px; border-left:0px;">&plusmn;</i>
                <i class="icon-append fa" style="width:50px; border:0px;">% FSO</i>
                <input name="<?php echo $_REQUEST['tbid']; ?>_tolerance_fso_percent" id="<?php echo $_REQUEST['tbid']; ?>_tolerance_fso_percent" type="text" style="padding-left:30px; border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;" value="0" number="true">
              <input name="<?php echo $_REQUEST['tbid']; ?>_tolerance_fso_val" id="<?php echo $_REQUEST['tbid']; ?>_tolerance_fso_val" type="hidden" value="0" />
            </label>
        </div>
      </div>

      <script>
        $("#<?php echo $_REQUEST['tbid']; ?>_tolerance_unit").keyup(function(){ CalculateTolerance('<?php echo $_REQUEST['tbid']; ?>'); });
        $("#<?php echo $_REQUEST['tbid']; ?>_tolerance_percent").keyup(function(){ CalculateTolerance('<?php echo $_REQUEST['tbid']; ?>'); });
        $("#<?php echo $_REQUEST['tbid']; ?>_tolerance_fso_percent").keyup(function(){ CalculateTolerance('<?php echo $_REQUEST['tbid']; ?>'); });
        $("#<?php echo $_REQUEST['tbid']; ?>_tolerance_fso_val").keyup(function(){ CalculateTolerance('<?php echo $_REQUEST['tbid']; ?>'); });
        $(".<?php echo $_REQUEST['tbid']; ?>_check_tolerance").change(function(){ CalculateTolerance('<?php echo $_REQUEST['tbid']; ?>'); });
      </script>

    <?php }else if($_REQUEST['type']== '2'){?>

      <div class="row">
        <div class="col col-3">
          &nbsp;
        </div>
          <div class="col col-2" style="text-align:right">
          <label class="input" style="margin-top:6px;">></label>
        </div>
        <div class="col col-3">
          <label class="input">
            <input name="<?php echo $_REQUEST['tbid']; ?>_set_tole_amount" id="<?php echo $_REQUEST['tbid']; ?>_set_tole_amount" class="set-tole-amount" tbid="<?php echo $_REQUEST['tbid']; ?>" type-id="2" type="text" style="border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;" value="" required number="true">
          </label>
        </div>
      </div>

    <?php }else if($_REQUEST['type']== '3'){?>

      <div class="row">
        <div class="col col-3">
          &nbsp;
        </div>
          <div class="col col-2" style="text-align:right">
          <label class="input" style="margin-top:6px;">&ge;</label>
        </div>
        <div class="col col-3">
          <label class="input">
            <input name="<?php echo $_REQUEST['tbid']; ?>_set_tole_amount" id="<?php echo $_REQUEST['tbid']; ?>_set_tole_amount" class="set-tole-amount" tbid="<?php echo $_REQUEST['tbid']; ?>" type-id="3" type="text" style="border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;" value="" required number="true">
          </label>
        </div>
      </div>

    <?php }else if($_REQUEST['type']== '4'){?>

      <div class="row">
        <div class="col col-3">
          &nbsp;
        </div>
          <div class="col col-2" style="text-align:right">
          <label class="input" style="margin-top:6px;"><</label>
        </div>
        <div class="col col-3">
          <label class="input">
            <input name="<?php echo $_REQUEST['tbid']; ?>_set_tole_amount" id="<?php echo $_REQUEST['tbid']; ?>_set_tole_amount" class="set-tole-amount" tbid="<?php echo $_REQUEST['tbid']; ?>" type-id="4" type="text" style="border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;" value="" required number="true">
          </label>
        </div>
      </div>

    <?php }else if($_REQUEST['type']== '5'){?>

      <div class="row">
        <div class="col col-3">
          &nbsp;
        </div>
          <div class="col col-2" style="text-align:right">
          <label class="input" style="margin-top:6px;">&le;</label>
        </div>
        <div class="col col-3">
          <label class="input">
            <input name="<?php echo $_REQUEST['tbid']; ?>_set_tole_amount" id="<?php echo $_REQUEST['tbid']; ?>_set_tole_amount" class="set-tole-amount" tbid="<?php echo $_REQUEST['tbid']; ?>" type-id="5" type="text" style="border:0px; border-bottom:1px dotted #CCCCCC; text-align:center; font-style:italic; color:#0000FF;" value="" required number="true">
          </label>
        </div>
      </div>

    <?php }else if($_REQUEST['type']== '6'){?>

      <div style="text-align:center; font-weight:normal"><em>N/A</em></div>

    <?php }

}

?>
<!-- Action Calibrate -->

<?php if($_REQUEST['data']== 'form-unit-box'){
//$_REQUEST['param_id']
//$_REQUEST['tbid']
  $data = array();
  $list = array();

  if($_REQUEST['param_id'] == '43'){

    $list[] = array(
      "unit_name"=>"BPM",
      "unit_symbol"=>"BPM",
      "unit_ratio"=>"1"
    );

    $data = array(
      "result"=> "complete",
      "parameter"=> array(
        "parameter_id"=> "43",
        "parameter_name"=> "FETAL RATE",
        "parameter_units" => $list,

      ),
      "html"=>'<label class=\"\select\"\ style=\"\width:80%; margin:0 auto\"\>\r\n                \t<select name=\"table5ec4e118_unit\" id=\"table5ec4e118_unit\" style=\"width:100%;\" class=\"select2\" required>\r\n                    \t<!-- List units form DB --><option value=\"0\">BPM<\/option><\/select>\r\n                <\/label><script>$(\"#table5ec4e118_unit\").select2();<\/script>',

    );
    echo json_encode($data);

  }else if($_REQUEST['data'] == '59'){

  }

}

?>

<?php
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