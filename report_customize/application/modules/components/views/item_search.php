    <style>
	.col-lg-5 { padding:0 0 0 0  !important; margin:0 0 0 0 !important; background:#FFF; height:350px;}
 	table tr{ cursor:pointer}
	</style>
<div class="col-lg-5 ">

                               
                                    <table id="item" class="table table-bordered table-hover ">
            <thead>
            <tr>
        
            
            <th width="120px">Item Code</th>
            <th>Item Name</th>
          
             </tr>              
            </thead>
           <tbody   >
              <?php  
			$i=1; 
			foreach($item_list as $row)
			{ ?>
              <tr class="selected" id="<?=$row['itemcd']?>" ondblclick="GetValue(this.id);"  >
            
          
             <td class="text-center"><?php echo iconv('tis620','utf-8',$row['itemcd']) ; ?></td> 
              <td class="text-left"><?php echo iconv('tis620','utf-8',$row['itemnm']) ; ?></td> 
           
             </tr>
            <? 
			$i++;
			} ?>
           
              </tbody>
                                 
                                    </table>
                                
                                </div>

        
 <script>
  var GetValue = function(myID){
	window.parent.$(".waste-popup").val(myID);	
	window.parent.$(".jqmWindow").jqmHide();	
		return true;
}
 
 
  	$(function() {
		
		$("#item").dataTable({	
				 
        			"bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false,
					"iDisplayLength":10,	
					
                });
 $('#item .selected').on('dblclick',function (event) {
	  event.preventDefault();	
	 var myID = $(this).attr('id');
	
	window.parent.$(".item-popup").val(myID);	
	window.parent.$(".jqmWindow").jqmHide();	
	
	return false;
	});
		
 
    });
  </script>
