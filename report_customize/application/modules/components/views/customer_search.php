    <style>
	.col-lg-5 { padding:0 0 0 0  !important; margin:0 0 0 0 !important; background:#FFF; height:350px;}
 	table tr{ cursor:pointer}
	</style>
<div class="col-lg-5 ">

                               
                                    <table id="customer" class="table table-bordered table-hover ">
            <thead>
            <tr>
        
            <th  width="70px">โรงงาน</th>
            <th width="80px">รหัสลูกค้า</th>
            <th>ชื่อลูกค้า</th>
          
             </tr>              
            </thead>
           <tbody   >
              <?php  
			$i=1; 
			foreach($customer_list as $row)
			{ ?>
              <tr class="selected" id="<?=$row['cuscd']?>" rel="<?php echo iconv('tis620','utf-8',$row['cusnm']) ; ?>"  ondblclick="GetValue(this.id);" >
            
          
             <td class="text-center"><?php echo iconv('tis620','utf-8',$row['plantcd']) ; ?></td> 
              <td class="text-left"><?php echo iconv('tis620','utf-8',$row['cuscd']) ; ?></td> 
             <td class="text-left"><?php echo iconv('tis620','utf-8',$row['cusnm']) ; ?></td>  
             </tr>
            <? 
			$i++;
			} ?>
           
              </tbody>
                                 
                                    </table>
                                
                                </div>

        
 <script>
   var GetValue = function(myID){
	 var cusnm = $("#"+myID).attr('rel');
	window.parent.$(".cust-popup").val(myID);	
	window.parent.$(".cust-name").val(cusnm);	
	window.parent.$(".jqmWindow").jqmHide();	
		return true;
}
 
 
  	$(function() {
		
		$("#customer").dataTable({	
				 
        			"bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false,
					"iDisplayLength":10,	
					
                });
 /*$('#customer .selected').on('dblclick',function (event) {
	  event.preventDefault();	
	 var myID = $(this).attr('id');
	
	window.parent.$(".cust-popup").val(myID);	
	window.parent.$(".jqmWindow").jqmHide();	
	
	return false;
	});
		
 */
    });
  </script>
