
<form id="form_data" action="#" method="post" autocomplete="off">
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Inspection
        <small><?php echo $mod.' '.($mod=='edit'?'[ '.$record.' ]':''); ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-paste"></i>Inspection <?php echo ($mod=='edit'? ' > '.$record :''); ?></a></li>
      </ol>
    </section>

    <input type="hidden" id="crmid" name="crmid" value="<?php echo $crmid ?>">
    <input type="hidden" id="action" name="action" value="<?php echo $mod ?>">
    <!-- from -->
    <!-- Main content -->
    <section class="content">
        <!-- Block -->
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Inspection Information</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          
          <!-- Field -->
           <div class="box-body">
           <div class="row">
            
            <!-- <label>Inspection No</label>
              <?php if($mod == 'edit'){ ?>
              <input type="text" class="form-control" id="inspection_no" name="inspection_no" disabled value="<?php echo $block['data'][0]['form'][0]['value'] ?>">
              <?php }else{ ?>
              <input type="text" class="form-control" id="inspection_no" name="inspection_no" disabled placeholder="AUTO GEN ON SAVE">
              <?php } ?> -->

            <div class="col-md-3">
              <div class="form-group">    
                <label>Project Name</label><span style="color: red">*</span>
                <select class="form-control select2" id="branchid" name="branchid" style="width: 100% !improtant;" required="true">
                    <?php if($mod == 'edit'){ ?>
                    <?php $value = $block['data'][0]['form'][2]['value']; $value_name = $block['data'][0]['form'][2]['value_name']; ?>
                    <?php foreach($data_branch as $k => $v){ ?>
                    <option value="<?php echo $v['branchid']; ?>" <?php echo $value == $v['branchid'] ?'selected':'' ?> ><?php echo $v['branch_name']; ?></option>
                    <?php } ?>
                    <?php }else{?>
                      <option value="">--None--</option>
                      <?php foreach($data_branch as $k => $v){ ?>
                        <option value="<?php echo $v['branchid']; ?>" alt="<?php echo $v['pj_project_type']; ?>" ><?php echo $v['branch_name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                </select>
              </div>

              <div class="form-group">
                <label>Inspection Date</label><span style="color: red">*</span>
                <div class="input-group-detail date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <?php if($mod == 'edit'){ ?>
                  <input type="text" class="form-control pull-right" id="datepicker" name="inspection_date" required="true" value="<?php echo date('d/m/Y' ,strtotime($block['data'][0]['form'][7]['value']) );?>">
                  <?php }else{ ?>
                  <input type="text" class="form-control pull-right" id="datepicker" name="inspection_date" required="true">
                  <?php }?>
                </div>
              </div>
            </div>


            <div class="col-md-3">
              <div class="form-group">    
                <label>Building Name</label><!-- <span style="color: red">*</span> -->
                 <select class="form-control select2" id="buildingid" name="buildingid" style="width: 100% !improtant;" >
                   <?php if($mod == 'edit'){ ?>
                    <?php $value = $block['data'][0]['form'][3]['value']; $value_name = $block['data'][0]['form'][3]['value_name']; ?>
                    <?php foreach($data_building as $k => $v){ ?>
                        <option value="<?php echo $v['buildingid']; ?>" <?php echo $value== $v['buildingid'] ?'selected':'' ?>/><?php echo $v['building_name']; ?></option>
                    <?php } ?>
                    <?php }else{?>
                        <option value="0">--None--</option>
                    <?php /*foreach($data_building as $k => $v){ ?>
                        <option value="<?php echo $v['buildingid']; ?>" ><?php echo $v['building_name']; ?></option>
                    <?php } */?>
                   <?php } ?>
                </select>
              </div>

              <div class="form-group">
                <div class="bootstrap-timepicker">
                <label>Inspection Time</label><span style="color: red">*</span>
                <div class="input-group">
                  <?php if($mod == 'edit'){ ?>
                  <input type="text" class="form-control timepicker" id="inspection_time" name="inspection_time" required="true" value="<?php echo $block['data'][0]['form'][8]['value'];?>">
                  <?php }else{ ?>
                  <input type="text" class="form-control timepicker" id="inspection_time" name="inspection_time" required="true">
                  <?php }?>
                  <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                </div>
                </div>

              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">    
                <label>Unit Name</label><span style="color: red">*</span>
                 <select class="form-control select2" id="productid" name="productid" style="width: 100% !improtant;" required="true">
                    <?php if($mod == 'edit'){ ?>
                      <?php $value = $block['data'][0]['form'][4]['value']; $value_name = $block['data'][0]['form'][4]['value_name']; ?>
                      <?php foreach($data_unit as $k => $v){ ?>
                        <option value="<?php echo $v['productid']; ?>" <?php echo $value== $v['productid'] ?'selected':'' ?>/><?php echo $v['productname'].' ['.$v['building_name'].']' ?></option>
                      <?php } ?>
                    <?php }else{?>
                        <option value="">--None--</option>
                      <?php /*foreach($data_unit as $k => $v){ ?>
                        <option value="<?php echo $v['productid']; ?>" ><?php echo $v['productname'].' ['.$v['building_name'].']' ;?></option>
                      <?php } */?>
                    <?php } ?>
                  </select>
              </div>

              <div class="form-group">
                <label>Status</label>
                <select class="form-control select2" id="inspection_status" name="inspection_status" style="width: 100% !improtant;">
                  <?php if($mod == 'edit'){ ?>
                  <?php $value = $block['data'][0]['form'][17]['value']; $value_name = $block['data'][0]['form'][17]['value_name']; ?>
                    <?php foreach($inspection_status['value_default'] as $select => $select_value){ ?>
                      <option value="<?php echo $select_value; ?>" <?php echo $value== $select_value ?'selected':'' ?>/><?php echo $select_value; ?></option>
                    <?php } ?>
                  <?php }else{ ?>
                    <?php foreach($inspection_status['value_default'] as $select => $select_value){ ?>
                      <option value="<?php echo $select_value; ?>" ><?php echo $select_value; ?></option>
                    <?php }?>
                <?php } ?>
               </select>
              </div>
            </div>


            <div class="col-md-3">
              <div class="form-group">    
                <label>Inspection Name </label><span style="color: red">*</span>
                <?php if($mod == 'edit'){ ?>
                <input type="text" class="form-control" id="inspection_name" name="inspection_name" value="<?php echo $block['data'][0]['form'][1]['value'] ?>" required="true">
                <?php }else{ ?>
                <input type="text" class="form-control" id="inspection_name" name="inspection_name" required="true">
               <?php } ?>
              </div>

              <div class="form-group">
                <label>Inspection Type</label>
                <select class="form-control select2" id="inspection_type" name="inspection_type" style="width: 100% !improtant;" required="true">
                   <?php if($mod == 'edit'){ ?>
                    <?php $value = $block['data'][0]['form'][6]['value']; $value_name = $block['data'][0]['form'][6]['value_name']; ?>
                    <?php foreach($inspection_type['value_default'] as $select => $select_value){ ?>
                        <option value="<?php echo $select_value; ?>" <?php echo $value== $select_value ?'selected':'' ?> ><?php echo $select_value; ?></option>
                    <?php } ?>
                    <?php }else{?>
                    <?php foreach($inspection_type['value_default'] as $select => $select_value){ ?>
                        <option value="<?php echo $select_value; ?>" ><?php echo $select_value; ?></option>
                    <?php } ?>
                   <?php } ?>
                </select>
              </div>
            </div>

          </div>
          </div>

          <div class="box-body">
           <div class="row">

            <div class="col-md-3">
              <div class="form-group">    
                <label class="cus_name">Customer Name</label><span style="color: red">*</span>
                <?php if($mod == 'edit'){ 
                  if($block['data'][0]['form'][6]['value'] == 'Contractor'){
                    $customer_name = $block['data'][0]['form'][13]['value'];
                  }else{
                    $customer_name = $block['data'][0]['form'][9]['value'];
                  }
                ?>
                <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo $customer_name;?>" required="true">
                <?php }else{ ?>
                 <input type="text" class="form-control" id="customer_name" name="customer_name" required="true">
                <?php } ?>
              </div>

              <div class="form-group">
                <label>Assigned To</label>
                <select class="form-control select2" id="smownerid" name="smownerid" style="width: 100% !improtant;">
                <?php if($mod == 'edit'){ ?>
                  <?php $value = $block['data'][0]['form'][18]['value']; $value_name = $block['data'][0]['form'][18]['value_name']; ?>
                  <?php foreach($smownerid['value_default'][0]['type_value'] as $select => $select_value){ ?>
                    <option value="<?php echo $select_value['id']; ?>" <?php echo $value== $select_value['id'] ?'selected':'' ?>/><?php echo $select_value['name']; ?></option>
                  <?php } ?>
                <?php }else{ ?>
                  <?php foreach($smownerid['value_default'][0]['type_value'] as $select => $select_value){ ?>
                    <option value="<?php echo $select_value['id']; ?>" ><?php echo $select_value['name']; ?></option>
                  <?php } ?>
                <?php } ?>
              </select>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">    
                <label class="cus_phone">Customer Mobile</label><span style="color: red">*</span>
                <?php if($mod == 'edit'){ 
                  if($block['data'][0]['form'][6]['value'] == 'Contractor'){
                    $phone = $block['data'][0]['form'][14]['value'];
                  }else{
                    $phone = $block['data'][0]['form'][10]['value'];
                  }
                ?>
                 <input type="text" onkeypress="return isNumberKey(event)" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" required="true">
                <?php }else{ ?>
                 <input type="text" onkeypress="return isNumberKey(event)" class="form-control" id="phone" name="phone" required="true">
                <?php } ?>
              </div>

              <div class="form-group">
                <label>Inspection Round No</label>
                <?php if($mod == 'edit'){ ?>
                <input type="text" class="form-control" id="inspection_timeno" name="inspection_timeno" disabled value="<?php echo $block['data'][0]['form'][5]['value'] ?>">
                <?php }else{ ?>
                <input type="text" class="form-control" id="inspection_timeno" name="inspection_timeno" disabled placeholder="AUTO GEN ON SAVE">
                <?php } ?>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">    
                <label class="cus_mobile_other">Customer Mobile Others</label>
                <?php if($mod == 'edit'){ 
                  if($block['data'][0]['form'][6]['value'] == 'Contractor'){
                    $phone_other = $block['data'][0]['form'][15]['value'];
                  }else{
                    $phone_other = $block['data'][0]['form'][11]['value'];
                  }
                  ?>
                  <input type="text" onkeypress="return isNumberKey(event)" class="form-control" id="phone_other" name="phone_other" value="<?php echo $phone_other ;?>">
                <?php }else{ ?>
                  <input type="text" onkeypress="return isNumberKey(event)" class="form-control" id="phone_other" name="phone_other" >
                <?php }?>
              </div>

              <div class="form-group">
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">    
                <label class="cus_email">Customer Email</label><span style="color: red">*</span>
                <?php if($mod == 'edit'){ 
                  if($block['data'][0]['form'][6]['value'] == 'Contractor'){
                    $email = $block['data'][0]['form'][16]['value'];
                  }else{
                    $email = $block['data'][0]['form'][12]['value'];
                  }
                  ?>
                  <input type="text" class="form-control" id="email" name="email" value="<?php echo $email ;?>" required="true">
                <?php }else{ ?>
                 <input type="email" class="form-control" id="email" name="email" required="true">
                <?php } ?>
              </div>

              <div class="form-group">
              </div>
            </div>
              
           </div>
           </div>
        </div>

        <!-- Report Config -->
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Config Report</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          
          <!-- Field -->
           <div class="box-body">
           <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label>Show Inspection List</label>
                  <?php 
                      $inspection_list = $block['data'][1]['form'][0]['value'];
                      $inspection_not = '';
                      $inspection_show = '';
                    if($mod == 'edit'){ 
                      if($inspection_list == '1'){
                          $inspection_show = 'selected';
                      }else{
                          $inspection_not = 'selected';
                      }
                    }

                   ?>
                  <select class="form-control select2" id="show_inspection_list" name="show_inspection_list" style="width: 100% !improtant;" required="true">
                   
                   <option value="1" <?php echo $inspection_show; ?> > Show </option>
                   <option value="0" <?php echo $inspection_not; ?> > Do not show </option>
                  </select><!-- Do not show -->
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label>Show Defect List</label>
                  <?php $defect_list =  $block['data'][1]['form'][1]['value'];
                      $defect_not = '';
                      $defect_show = '';
                    if($mod == 'edit'){
                      if($defect_list == '1'){
                          $defect_show = 'selected';
                      }else{
                          $defect_not = 'selected';
                      }
                    }
                  ?>
                  <select class="form-control select2" id="show_defect_list" name="show_defect_list" style="width: 100% !improtant;" required="true">
                   <option value="1" <?php echo $defect_show; ?> > Show </option>
                   <option value="0" <?php echo $defect_not; ?> > Do not show </option>
                  </select>
                </div>
              </div>

            </div>
           </div>
        </div>


        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">Description Information</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          
          <!-- Field -->
           <div class="box-body">
           <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Description</label>
                  <?php if($mod == 'edit'){ ?>
                  <textarea class="form-control" rows="6" id="description" name="description"><?php echo $block['data'][2]['form'][0]['value'];?></textarea>
                  <?php }else{ ?>
                  <textarea class="form-control" rows="6" id="description" name="description"></textarea>
                  <?php } ?>
                </div>
                <div class="form-group"></div>
              </div>
            </div>
           </div>
        </div>

        <div class="box box-default">
          <div class="box-header with-border">
            <div class="text-center">
              <button type="submit" class="btn btn-success btn-sm save"><i class="glyphicon glyphicon-ok"></i> Save</button>
              <button type="button" class="btn btn-danger btn-sm cancel"><i class="glyphicon glyphicon glyphicon-remove"></i> Cancel</button>
            </div>
          </div>
        </div>
      <!-- Data Table/.box -->
    </section>

 </div>
</form>

<script type="text/javascript">

$( document ).ready(function() {
    
    $('#loader').fadeOut();  
    var mod = '<?php echo $mod; ?>';
    if(mod == 'add'){
      $("#datepicker").datepicker( "setDate" , new Date() );
    }
    var inspec_type = jQuery("#inspection_type").val();
    if(inspec_type == 'Contractor'){
      $('.cus_name').html('Contractor Name');
      $('.cus_phone').html('Contractor Mobile');
      $('.cus_mobile_other').html('Contractor Mobile Others');
      $('.cus_email').html('Contractor Email');
    }else{
      $('.cus_name').html('Customer Name');
      $('.cus_phone').html('Customer Mobile');
      $('.cus_mobile_other').html('Customer Mobile Others');
      $('.cus_email').html('Customer Email');
    }
    //console.log( jQuery("#inspection_type").val() );
});
function isNumberKey(evt)
{
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode != 46 && charCode > 31 
  && (charCode < 48 || charCode > 57))
  return false;
  return true;
}

jQuery("#branchid").on('change', function(e) {

  var data = {
    branchid : $('#branchid').val()
  }
  var alt = $('option:selected', this).attr('alt');
  //console.log(alt);
  if(alt == 'แนวสูง'){
    var url = "<?php echo site_url('inspection/get_building_post'); ?>";
    $.ajax(url, {
       type: 'POST',
       dataType : 'json',
       data:  data,
       cache: false,
       encoding:"UTF-8",
       success: function (data) {
        var result = jQuery.parseJSON(JSON.stringify(data));
        //console.log(result);
          $('#buildingid').empty();
          $('#buildingid').append('<option value="0" >--None--</option>');
          $('#productid').empty();
          $('#productid').append('<option value="" >--None--</option>');
          $.each(result,function(index,json){
            $('#buildingid').append('<option value="'+json.buildingid+'" >'+json.building_name+'</option>');
          });   
        
        },
       error: function (data) {
       
       }
     });
  }else if(alt == 'แนวราบ'){
    var url = "<?php echo site_url('inspection/get_unit_post'); ?>";
    var data = {
      branchid : $('#branchid').val(),
      buildingid : '',
    }
    $.ajax(url, {
       type: 'POST',
       dataType : 'json',
       data:  data,
       cache: false,
       encoding:"UTF-8",
       success: function (data) {
        var result = jQuery.parseJSON(JSON.stringify(data));
        //console.log(result);
          $('#buildingid').empty();
          $('#buildingid').append('<option value="0" >--None--</option>');
          $('#productid').empty();
          $('#productid').append('<option value="" >--None--</option>');
          
          $.each(result,function(index,json){
            $('#productid').append('<option value="'+json.productid+'" >'+json.productname+' ['+json.branch_name+']</option>');
          });  
        
        },
       error: function (data) {
       
       }
     });

  }else{
    var url = "<?php echo site_url('inspection/get_building_post'); ?>";
    $.ajax(url, {
       type: 'POST',
       dataType : 'json',
       data:  data,
       cache: false,
       encoding:"UTF-8",
       success: function (data) {
        var result = jQuery.parseJSON(JSON.stringify(data));
        //console.log(result);
          $('#buildingid').empty();
          $('#buildingid').append('<option value="0" >--None--</option>');
          /*$('#productid').empty();
          $('#productid').append('<option value="" >--None--</option>');*/
          $.each(result,function(index,json){
            $('#buildingid').append('<option value="'+json.buildingid+'" >'+json.building_name+'</option>');
          });   
        
        },
       error: function (data) {
       
       }
     });

    var url = "<?php echo site_url('inspection/get_unit_post'); ?>";
    var data = {
      branchid : $('#branchid').val(),
      buildingid : 0 ,
    }
    $.ajax(url, {
       type: 'POST',
       dataType : 'json',
       data:  data,
       cache: false,
       encoding:"UTF-8",
       success: function (data) {
        var result = jQuery.parseJSON(JSON.stringify(data));
          /*$('#buildingid').empty();
          $('#buildingid').append('<option value="" >--None--</option>');*/
          $('#productid').empty();
          $('#productid').append('<option value="" >--None--</option>');
          
          $.each(result,function(index,json){
            $('#productid').append('<option value="'+json.productid+'" >'+json.productname+' ['+json.branch_name+']</option>');
          });  
        
        },
       error: function (data) {
       
       }
     });

  }


});


jQuery("#buildingid").on('change', function(e) {

  var url = "<?php echo site_url('inspection/get_unit_post'); ?>";
  var data = {
    branchid : $('#branchid').val(),
    buildingid : $('#buildingid').val(),
  }
  $.ajax(url, {
     type: 'POST',
     dataType : 'json',
     data:  data,
     cache: false,
     encoding:"UTF-8",
     success: function (data) {
      var result = jQuery.parseJSON(JSON.stringify(data));
      console.log(result);

      $('#productid').empty();
      $('#productid').append('<option value="" >--None--</option>');
      $.each(result,function(index,json){
        $('#productid').append('<option value="'+json.productid+'" >'+json.productname+' ['+json.branch_name+']</option>');
      });  
      },
     error: function (data) {
     }
   
   });
});

jQuery("#productid").on('change', function(e) {
  
  $('#inspection_name').val($(this).find("option:selected").text());
  
});

jQuery("#inspection_type").on('change', function(e) {
    var inspec_type = jQuery("#inspection_type").val();
    if(inspec_type == 'Contractor'){
      $('.cus_name').html('Contractor Name');
      $('.cus_phone').html('Contractor Mobile');
      $('.cus_mobile_other').html('Contractor Mobile Others');
      $('.cus_email').html('Contractor Email');
    }else{
      $('.cus_name').html('Customer Name');
      $('.cus_phone').html('Customer Mobile');
      $('.cus_mobile_other').html('Customer Mobile Others');
      $('.cus_email').html('Customer Email');
    } 
});

$(document).on( 'click', '.cancel', function () {
    window.history.back();
});
//Submit
$("#form_data").submit(function(e){

    var url = "<?php echo site_url('inspection/save'); ?>";            
    var data = $(this).serialize(); 
    $('#loader').fadeIn();

      $.ajax(url, {
         type: 'POST',
         dataType : 'json',
         data:  data,
         cache: false,
         encoding:"UTF-8",
         success: function (data) {
          
          var result = jQuery.parseJSON(JSON.stringify(data));
          console.log(result);

        if(result['Type'] == 'S'){
          $('#loader').fadeOut();
          console.log(result);
           swal({
              position: 'center',//'top-end',
              type: 'success',
              title: result['Message'],
              showConfirmButton: false,
              timer: 3000
            });
          setTimeout(function () { 
            document.location.href = "<?php echo site_url('inspection/detail')?>/"+result['data']['crmid'];
          }, 3000);
          
        }else{
          swal("", result['Message'], "error");
          $('#loader').fadeOut();
         
        }

        },
         error: function (data) {
          $('#loader').fadeOut();
          swal("", data.Message, "error");
         }
       });
      e.preventDefault(); //STOP default action
        //e.unbind(); //unbind. to stop multiple form submit.
});
</script>
