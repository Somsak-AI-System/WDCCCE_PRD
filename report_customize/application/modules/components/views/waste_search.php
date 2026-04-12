    <style>
	.col-lg-5 { padding:0 0 0 0  !important; margin:0 0 0 0 !important; background:#FFF; height:350px;}
 	table tr{ cursor:pointer}
	</style>
<div class="col-lg-5 ">

                               
                                    <table id="waste" class="table table-bordered table-hover ">
            <thead>
            <tr>
        
            
            <th width="100px">Waste Code</th>
            <th>Waste Name</th>
          
             </tr>              
            </thead>
           <tbody   >
              <?php  
			$i=1; 
			foreach($waste_list as $row)
			{ ?>
              <tr class="selected" id="<?=$row['wastecode']?>" rel="<?php echo iconv('tis620','utf-8',$row['wastename']) ; ?>" ondblclick="GetValue(this.id);">
            
          
             <td class="text-center"><?php echo iconv('tis620','utf-8',$row['wastecode']) ; ?></td> 
              <td class="text-left"><?php echo iconv('tis620','utf-8',$row['wastename']) ; ?></td> 
           
             </tr>
            <? 
			$i++;
			} ?>
           
              </tbody>
                                 
                                    </table>
                                
                                </div>

        
 <script>
 var GetValue = function(myID){
	 var wastename = $("#"+myID).attr('rel');
	window.parent.$(".waste-popup").val(myID);	
	window.parent.$(".waste-name").val(wastename);	
	window.parent.$(".jqmWindow").jqmHide();	
		return true;
}
 
  	$(function() {
		
		$("#waste").dataTable({	
				 
        			"bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false,
					"iDisplayLength":10,	
					
                });
/* $('#waste .selected').on('dblclick',function (event) {
	  event.preventDefault();	
	 var myID = $(this).attr('id');
	
	window.parent.$(".waste-popup").val(myID);	
	window.parent.$(".jqmWindow").jqmHide();	
	
	return false;
	});
		*/
 
    });
  </script>
