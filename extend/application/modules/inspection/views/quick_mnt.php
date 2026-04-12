
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
                <input type="text" class="form-control" id="branch_name" name="branch_name" required="true" readonly="">
                <input type="hidden" class="form-control" id="branchid" name="branchid">
              </div>

              <div class="form-group">
                <label>Inspection Date</label><span style="color: red">*</span>
                <div class="input-group-detail date">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input type="text" class="form-control pull-right" id="datepicker" name="inspection_date" required="true">
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">    
                <label>Building Name</label><span style="color: red">*</span>
                <input type="text" class="form-control" id="building_name" name="building_name" required="true" readonly="">
                <input type="hidden" class="form-control" id="buildingid" name="buildingid">
              </div>

              <div class="form-group">
                <div class="bootstrap-timepicker">
                <label>Inspection Time</label><span style="color: red">*</span>
                <div class="input-group">
                  
                  <input type="text" class="form-control timepicker" id="inspection_time" name="inspection_time" required="true">
                  
                  <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                </div>
                </div>

              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">    
                <label>Unit Name</label><span style="color: red">*</span>
                <input type="text" class="form-control" id="productname" name="productname" required="true" readonly="">
                <input type="hidden" class="form-control" id="productid" name="productid">
              </div>

              <div class="form-group">
                <label>Status</label>
                <select class="form-control select2" id="inspection_status" name="inspection_status" style="width: 100% !improtant;">
                  <?php foreach($inspection_status['value_default'] as $select => $select_value){ ?>
                    <option value="<?php echo $select_value; ?>" ><?php echo $select_value; ?></option>
                  <?php }?>
               </select>
              </div>
            </div>


            <div class="col-md-3">
              <div class="form-group">    
                <label>Inspection Name </label><span style="color: red">*</span>
                <input type="text" class="form-control" id="inspection_name" name="inspection_name" required="true">
              </div>

              <div class="form-group">
                <label>Inspection Type</label>
                <input type="text" class="form-control pull-right" id="inspection_type" name="inspection_type" required="true" readonly="">
              </div>
            </div>

          </div>
          </div>

          <div class="box-body">
           <div class="row">

            <div class="col-md-3">
              <div class="form-group">    
                <label class="cus_name">Customer Name</label><span style="color: red">*</span>
      
                 <input type="text" class="form-control" id="customer_name" name="customer_name" required="true">
                
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
                
                 <input type="text" onkeypress="return isNumberKey(event)" class="form-control" id="phone" name="phone" required="true">
            
              </div>

              <div class="form-group">
                <label>Inspection Round No</label>
                
                <input type="text" class="form-control" id="inspection_timeno" name="inspection_timeno" disabled placeholder="AUTO GEN ON SAVE">
               
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">    
                <label class="cus_mobile_other">Customer Mobile Others</label>
                
                  <input type="text" onkeypress="return isNumberKey(event)" class="form-control" id="phone_other" name="phone_other" >
              
              </div>

              <div class="form-group">
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">    
                <label class="cus_email">Customer Email</label>
                
                 <input type="email" class="form-control" id="email" name="email" >
             
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
    
    jQuery("#branchid").val('<?php echo $product_detail[0]['branchid']; ?>');
    jQuery("#branch_name").val('<?php echo $product_detail[0]['branch_name']; ?>');
    jQuery("#buildingid").val('<?php echo $product_detail[0]['buildingid']; ?>');
    jQuery("#building_name").val('<?php echo $product_detail[0]['building_name']; ?>');
    jQuery("#productid").val('<?php echo $product_detail[0]['productid']; ?>');
    jQuery("#productname").val('<?php echo $product_detail[0]['inspection_name']; ?>');
    jQuery("#inspection_name").val('<?php echo $product_detail[0]['inspection_name']; ?>');
    jQuery("#inspection_type").val('<?php echo $type; ?>');

    
    $('#loader').fadeOut();  
    var mod = '<?php echo $mod; ?>';
    $("#datepicker").datepicker( "setDate" , new Date() );
    
    var inspec_type = '<?php echo $type; ?>';
    //alert(inspec_type);
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

      jQuery("#customer_name").val('<?php echo $product_detail[0]['customer_name']; ?>');
      jQuery("#phone").val('<?php echo $product_detail[0]['phone']; ?>');
      jQuery("#phone_other").val('<?php echo $product_detail[0]['phone_other']; ?>');
      jQuery("#email").val('<?php echo $product_detail[0]['email']; ?>');
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

  var url = "<?php echo site_url('inspection/get_building_post'); ?>";
  var data = {
    branchid : $('#branchid').val(),
    /*buildingid : $('#buildingid').val(),
    productid : $('#productid').val(),*/
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
        $('#buildingid').empty();
        $('#buildingid').append('<option value="" >--None--</option>');
        $('#productid').empty();
        $('#productid').append('<option value="" >--None--</option>');
        $.each(result,function(index,json){
          $('#buildingid').append('<option value="'+json.buildingid+'" >'+json.building_name+'</option>');
        });   
      },
     error: function (data) {
     
     }
   
   });

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
      /*$('#buildingid').empty();
      $('#buildingid').append('<option value="" >--None--</option>');
      $('#branchid').empty();
      $('#branchid').append('<option value="" >--None--</option>');*/
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

  /*
  var url = "<?php echo site_url('inspection/get_brunch_building'); ?>";
  var data = {
    productid : $('#productid').val(),
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

      $("#branchid").select2();
      $("#branchid").val(result[0]['branchid']).trigger("change");
      $("#buildingid").select2();
      $("#buildingid").val(result[0]['buildingid']).trigger("change");      
      },
     error: function (data) {
     }
   
   });*/

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
    document.location.href = "<?php echo site_url('inspection/index')?>";
});
//Submit
$("#form_data").submit(function(e){

    var url = "<?php echo site_url('inspection/save_quick'); ?>";            
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
              timer: 2000
            });
          setTimeout(function () { 
            document.location.href = "<?php echo site_url('inspection/detail')?>/"+result['data']['crmid'];
          }, 2500);
          
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
