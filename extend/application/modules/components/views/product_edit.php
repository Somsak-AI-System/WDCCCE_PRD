<style>
    #pg{ font-size:0.8em; color:#333; margin:1em 1em;border:1px solid #86aecc; border-collapse: collapse;}
    #pg th{background-color:#d4e4ef; font-weight:600;border:none; padding:5px 5px;
    }
    #pg td{background-color:#FFF; font-weight:normal;border:none; padding:5px 5px;}
    #pg td,#pg th {
        border:1px groove #86aecc;
    }
    #pg th.htable{ font-size:1.2em !important;font-family: "Times New Roman", Georgia, Serif;
        color:#000; font-weight:Bold; border:1px groove #86aecc;
        padding:5px 5px;
        background: rgba(212,228,239,1);
        background: -moz-linear-gradient(top, rgba(212,228,239,1) 0%, rgba(134,174,204,1) 39%, rgba(134,174,204,1) 100%);
        background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(212,228,239,1)), color-stop(39%, rgba(134,174,204,1)), color-stop(100%, rgba(134,174,204,1)));
        background: -webkit-linear-gradient(top, rgba(212,228,239,1) 0%, rgba(134,174,204,1) 39%, rgba(134,174,204,1) 100%);
        background: -o-linear-gradient(top, rgba(212,228,239,1) 0%, rgba(134,174,204,1) 39%, rgba(134,174,204,1) 100%);
        background: -ms-linear-gradient(top, rgba(212,228,239,1) 0%, rgba(134,174,204,1) 39%, rgba(134,174,204,1) 100%);
        background: linear-gradient(to bottom, rgba(212,228,239,1) 0%, rgba(134,174,204,1) 39%, rgba(134,174,204,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d4e4ef', endColorstr='#86aecc', GradientType=0 );
    }
</style>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<div id="ww" >
    <input type="hidden" id="product_crmid" name="product_crmid" value="<?php echo $product[0]['productid']; ?>">
    <input type="hidden" id="action" value="edit">
    <table id="pg" cellspacing="0"  cellpadding="2" style="width:97%">
        <tr>
            <th colspan='4' class="htable" >Unit Detail</th>
        </tr>
        <tr>
            <th style="width:20%">Project Name</th>
            <td style="width:30%"><?php echo $product[0]['branch_name']; ?></td>
            <th style="width:20%">Building Name</th>
            <td style="width:30%"><?php echo $product[0]['building_name']; ?></td>
        </tr>
            <th style="width:20%">Unit No</th>
            <td style="width:30%"><input type="text" class="easyui-textbox" name="productname_edit" id="productname_edit" value="<?php echo $product[0]['productname']; ?>" style="width: 100%;" data-options="required:true"></td><!--  -->
            <th style="width:20%">Floor</th>
            <td style="width:30%"><input type="text" class="easyui-textbox" name="floor_no_edit" id="floor_no_edit" value="<?php echo $product[0]['floor_no']; ?>" style="width: 100%;"></td>
        <tr>
        </tr>
        <tr>
            <th style="width:20%">House No</th>
            <td style="width:30%"><input type="text" class="easyui-textbox" name="house_no_edit" id="house_no_edit" value="<?php echo $product[0]['house_no']; ?>" style="width: 100%;" data-options="required:true" ></td>
            <th style="width:20%">Room Size</th>
            <td style="width:30%"><input type="text" class="easyui-textbox" name="unit_size_edit" id="unit_size_edit" value="<?php echo $product[0]['unit_size']; ?>" style="width: 100%;" data-options="required:true" ></td>
        </tr>
        <tr>
            <th style="width:20%">Customer Name</th>
            <td style="width:30%"><input type="text" class="easyui-textbox" name="customer_name_edit" id="customer_name_edit" value="<?php echo $product[0]['customer_name']; ?>" style="width: 100%;"></td>

            <th style="width:20%">Customer Mobile</th>
            <td style="width:30%"><input type="text" class="easyui-textbox" name="phone_edit" id="phone_edit" value="<?php echo $product[0]['phone']; ?>" style="width: 100%;"></td>
        </tr>
        <tr>
            <th style="width:20%">Customer Mobile Others</th>
            <td style="width:30%"><input type="text" class="easyui-textbox" name="phone_other_edit" id="phone_other_edit" value="<?php echo $product[0]['phone_other']; ?>" style="width: 100%;"></td>

            <th style="width:20%">Customer Email</th>
            <td style="width:30%"><input type="text" class="easyui-textbox" name="email_edit" id="email_edit" value="<?php echo $product[0]['email']; ?>" style="width: 100%;"></td>
        </tr>
        
    </table>
    <div style="clear:both"></div>
    <div style="text-align: center;">
      <div ><button type="button" class="btn btn-info" onclick="edit_product()"><i class="fa fa-save"></i> Save</button> <button type="button" class="btn btn-cancel" onclick="jQuery('#actionwindow').window('close');"><i class="fa fa-close"></i> Cancel</button>
      </div>
    </div> 
</div>
    

<script>
$(document).ready(function(){


});
function edit_product(){ 
  
  
  var action = $('#action').val();
  var floor_no = $('#floor_no_edit').val(); 
  var productname = $('#productname_edit').val(); 
  var house_no = $('#house_no_edit').val(); 
  var unit_size = $('#unit_size_edit').val();
  var customer_name = $('#customer_name_edit').val(); 
  var phone = $('#phone_edit').val(); 
  var phone_other = $('#phone_other_edit').val(); 
  var email = $('#email_edit').val();
  var product_crmid = $('#product_crmid').val();

  if(productname == '' ){
    $('#productname_edit').focus(); 
    return false;
  }
  if(house_no == '' ){
    $('#house_no_edit').focus(); 
    return false;
  }
  if(unit_size == '' ){
    $('#unit_size_edit').focus(); 
    return false;
  }
  //$('#loader').fadeIn(); 
  var data = {
    action : action,
    productname : productname,
    house_no : house_no,
    unit_size : unit_size,
    floor_no : floor_no,
    customer_name:customer_name,
    phone:phone,
    phone_other:phone_other,
    email:email,
    product_crmid : product_crmid,
  }
   
      var url = "<?php echo site_url('components/quick_view/edit_unit');?>";  
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



