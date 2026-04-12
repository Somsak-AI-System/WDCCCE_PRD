
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<input type="hidden" name="crmid" id="crmid" value="<?php echo $branchid ;?>">
<input type="hidden" name="action" id="action" value="<?php echo $action ;?>">
<div id="ww" style="padding-left: 15px;
    padding-right: 15px;">
    <div style="margin:10px 10px 10px 10px;"></div>
    <div class="easyui-panel" style="width:100%; max-width:500px;padding:30px 60px;">
        <label>Branch Name</label>
        <div style="margin-bottom:20px">
            <input class="easyui-textbox branch_name" id="branch_name" value="<?php echo $data['branch_name'] ;?>"  style="width:100%;height:30px;font-size: 16px;" data-options="required:true"><!-- data-options="buttonText:'Save',prompt:''" -->
        </div>
        <div class="button-save" style="text-align: center;" >
            <button type="button" class="btn btn-info" onclick="add_branch()">
            <i class="fa fa-save"></i> Save</button>
            
            <button type="button" class="btn btn-cancel" onclick="jQuery('#actionwindow').window('close');">
            <i class="fa fa-close"></i> Cancel</button>
        </div>
    </div>

</div>  
    
<script>
$(document).ready(function() {
    

});

function add_branch(){ 
  /*$("#branch_name").removeAttr("style");*/
  var branch_name = $('#branch_name').val(); 
  var crmid = $('#crmid').val(); 
  var action = $('#action').val(); 
  //console.log(branch_name);
  if(branch_name == '' ){
    /*$('#branch_name').focus();
    $("#branch_name").css("border-color", "red");*/
    $('#branch_name').focus(); 
    return false;
  }
  $('#loader').fadeIn();
    
  var data = {
    crmid : crmid,
    action : action,
    branch_name : branch_name,
  }
   var url = "<?php echo site_url('components/quick_view/save_branch'); ?>";  
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
                
                getBranch();
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



