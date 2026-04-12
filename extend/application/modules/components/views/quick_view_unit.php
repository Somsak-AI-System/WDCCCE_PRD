
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<input type="hidden" name="qcu_branchid" id="qcu_buildingid" value="<?php echo $buildingid;?>" >
<input type="hidden" name="qcu_branchid" id="qcu_branchid" value="<?php echo $branchid;?>" >
<input type="hidden" name="floor_no" id="floor_no" value="<?php echo $floor_no;?>" >

<input type="hidden" name="action" id="action" value="<?php echo $action ;?>" >
<div id="ww" style="padding-left: 15px;
    padding-right: 15px;">
    <div style="margin:10px 10px 10px 10px;"></div>
    <div class="easyui-panel" style="width:100%; max-width:500px;padding:30px 60px;">
        <label><span style="color: red">* </span>Unit No :</label>
        <div style="margin-bottom:20px">
          <input class="easyui-textbox qc_productname" id="qc_productname" value="" data-options="required:true" style="width:100%;height:30px;font-size: 16px;" >
        </div>
        <label><span style="color: red">* </span>House No :</label>
        <div style="margin-bottom:20px">
          <input class="easyui-textbox qc_house_no" id="qc_house_no" value="" data-options="required:true" style="width:100%;height:30px;font-size: 16px;" >
        </div>
        <label><span style="color: red">* </span>Area Size :</label>
        <div style="margin-bottom:20px">
          <input class="easyui-textbox qc_unit_size" id="qc_unit_size" value="" data-options="required:true"  style="width:100%;height:30px;font-size: 16px;" >
        </div>
        <div class="button-save" style="text-align: center;" >
            <button type="button" class="btn btn-info" onclick="add_unit()">
            <i class="fa fa-save"></i> Save</button>
            
            <button type="button" class="btn btn-cancel" onclick="jQuery('#actionwindow').window('close');">
            <i class="fa fa-close"></i> Cancel</button>
        </div>
    </div>

</div>  
    
<script>
$(document).ready(function() {
  
});

function add_unit(){ 
  
  var buildingid = $('#qcu_buildingid').val(); 
  var branchid = $('#qcu_branchid').val(); 

  var floor_no = $('#floor_no').val(); 
  var action = $('#action').val(); 
  var productname = $('#qc_productname').val(); 
  var house_no = $('#qc_house_no').val(); 
  var unit_size = $('#qc_unit_size').val(); 

  if(productname == '' ){
    $('#qc_productname').focus(); 
    return false;
  }
  if(house_no == '' ){
    $('#qc_house_no').focus(); 
    return false;
  }
  if(unit_size == '' ){
    $('#qc_unit_size').focus(); 
    return false;
  }

  //$('#loader').fadeIn();
    
  var data = {
    crmid : '',
    action : action,
    branchid : branchid,
    buildingid : buildingid,
    productname : productname,
    house_no : house_no,
    unit_size : unit_size,
    floor_no : floor_no,
  }
   
      var url = "<?php echo site_url('components/quick_view/save_unit');?>";  
      $.ajax(url, {
         type: 'POST',
         data: data,
         success: function (data){
          var result = jQuery.parseJSON(data);
          console.log(result);

            if(result['Type'] =='S'){
              $('#loader').fadeOut();
              $('#createModal').modal('toggle');

               swal({
                  position: 'center',//'top-end',
                  type: 'success',
                  title: result['Message'],
                  showConfirmButton: false,
                  timer: 2000
                });
                
                getunitmatrix();
                jQuery('#actionwindow').window('close');

            }else{
              $('#loader').fadeOut();
              swal("",result['Message'],"error");   
            }
          
         },
         error: function (data){
          $('#loader').fadeOut();
          swal("",data.Message,"error");
         }
      });

}

</script>



