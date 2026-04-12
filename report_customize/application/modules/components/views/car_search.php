    <style>
	.col-lg-5 { padding:0 0 0 0  !important; margin:0 0 0 0 !important; background:#FFF; height:350px;}
 	table tr{ cursor:pointer}
table.popup{ font-size:12px; font-family:tahoma !important;}

	</style>
<div class="col-lg-5 ">
  <table id="car" class="table table-bordered table-hover popup">
            <thead>
            <tr>
        
            
            <th width="100px">Licenseno</th>
            <th width="100px">ชนิด</th>
            <th width="150px">ประเภท</th>
             </tr>              
            </thead>
           <tbody   >
              <?php  
			$i=1; 
			foreach($car_list as $row)
			{ ?>
              <tr class="selected" id="<?=iconv('tis620','utf-8',$row['provcd'].' '.$row['licenseno'])?>" ondblclick="GetValue(this.id);">
                     
             <td class="text-center"><?php echo iconv('tis620','utf-8',$row['provcd'].' '.$row['licenseno']) ; ?></td> 
            <td class="text-center"><?php echo iconv('tis620','utf-8',$row['carctgnm']) ; ?></td> 
           <td class="text-center"><?php echo iconv('tis620','utf-8',$row['cartypenm']) ; ?></td>
             </tr>
            <? 
			$i++;
			} ?>
           
              </tbody>
                                 
                                    </table>
                                
                                </div>

        
 <script>
 
var GetValue = function(carcd){
	 window.parent.$(".car-popup").val(carcd);	
	window.parent.$(".jqmWindow").jqmHide();	
		return true;
}
  	$(function() {
		
		$("#car").dataTable({	
				 
        			"bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false,
					"iDisplayLength":10,	
					
                });
/* $('#car .selected').on('dblclick',function (event) {
	 // event.preventDefault();	
	 var myID = $(this).attr('id');
	
	window.parent.$(".car-popup").val(myID);	
	window.parent.$(".jqmWindow").jqmHide();	
	
	return false;
	});
		
 */
    });
  </script>
