
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<input type="hidden" name="crmid" id="crmid" value="<?php echo $buildingid;?>" >
<input type="hidden" name="action" id="action" value="<?php echo $action ;?>" >
<div id="ww" style="padding-left: 15px;
    padding-right: 15px;">
    <div style="margin:10px 10px 10px 10px;"></div>
    <div class="easyui-panel" style="width:100%; max-width:500px;padding:30px 60px;">
        <label>Branch Name</label>
        <div style="margin-bottom:20px">
            <select class="easyui-combobox qc_branchid" id="qc_branchid" style="width:100%;height:30px;font-size: 16px;" >
              <?php foreach ($branch as $key => $value){ ?>
                <option value="<?php echo $value['branchid']; ?>" <?php echo ($branchid == $value['branchid']) ? 'selected' : '';  ?> ><?php echo $value['branch_name']; ?></option>
              <?php } ?>
            </select>
        </div>
        <label>Building Name</label>
        <div style="margin-bottom:20px">
          <input class="easyui-textbox building_name" id="building_name" value="<?php echo $data['building_name'] ;?>"  style="width:100%;height:30px;font-size: 16px;" data-options="required:true">
        </div>
        <div class="button-save" style="text-align: center;" >
            <button type="button" class="btn btn-info" onclick="add_building()">
            <i class="fa fa-save"></i> Save</button>
            
            <button type="button" class="btn btn-cancel" onclick="jQuery('#actionwindow').window('close');">
            <i class="fa fa-close"></i> Cancel</button>
        </div>
    </div>

</div>  
    
<script>
$(document).ready(function() {
  
});

function add_building(){ 
  
  var building_name = $('#building_name').val(); 
  var branchid = $('#qc_branchid').combobox('getValue');
  var crmid = $('#crmid').val(); 
  var action = $('#action').val(); 
  //console.log(branch_name);
  if(building_name == '' ){
    $('#building_name').focus(); 
    return false;
  }
  $('#loader').fadeIn();
    
  var data = {
    crmid : crmid,
    branchid : branchid,
    action : action,
    building_name : building_name,
  }
   var url = "<?php echo site_url('components/quick_view/save_building'); ?>";  
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
                
                Getbuilding();
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



