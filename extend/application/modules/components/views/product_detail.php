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
            <td style="width:30%"><?php echo $product[0]['productname'];?></td>
            <th style="width:20%">Floor</th>
            <td style="width:30%"><?php echo $product[0]['floor_no']; ?></td>
        <tr>
        </tr>
        <tr>
            <th style="width:20%">House No</th>
            <td style="width:30%"><?php echo $product[0]['house_no']; ?></td>
            <th style="width:20%">Room Size</th>
            <td style="width:30%"><?php echo $product[0]['unit_size']; ?></td>
        </tr>
        <tr>
            <th style="width:20%">Customer Name</th>
            <td style="width:30%"><?php echo $product[0]['customer_name']; ?></td>

            <th style="width:20%">Customer Mobile</th>
            <td style="width:30%"><?php echo $product[0]['phone']; ?> </td>
        </tr>
        <tr>
            <th style="width:20%">Customer Mobile Others</th>
            <td style="width:30%"><?php echo $product[0]['phone_other']; ?></td>

            <th style="width:20%">Customer Email</th>
            <td style="width:30%"><?php echo $product[0]['email']; ?> </td>
        </tr>
        

        <tr>
            <!-- <th colspan='4' class="htable" >Inspection List</th> -->
            <table id="pg" width="97%" height='300px' border="0">
              <tr>
                <th width="50%" align="left" class="htable" valign="top" scope="col">Inspection Contractor</th>
                <th width="50%" align="left" class="htable" valign="top" scope="col">Inspection Customer</th>
              </tr>
              <tr>
                <th height="100%" align="left" valign="top" scope="col">
                 <table width="100%" border="0">
                  <tr>
                    <th scope="col" width="15%" style="text-align: center;">Round No.</th>
                    <th scope="col" width="20%" style="text-align: center;">Inspection No</th>
                    <th scope="col" width="25" style="text-align: center;">Inspection Date</th>
                    <th scope="col" width="10" style="text-align: center;">Inspection Time</th>
                    <th scope="col" width="15" style="text-align: center;">Status</th>
                    <th scope="col" width="5" style="text-align: center;"></th>
                  </tr>
                  
                  <?php if(!empty($contractor)){ 
                    foreach ($contractor as $key => $value) { 
                      echo "<tr>";
                      echo "<td style='text-align: center;'>".$value['inspection_timeno']."</td>";
                      echo "<td>".$value['inspection_no']."</td>";
                      echo "<td style='text-align: center;'>".date("d/m/Y", strtotime($value['inspection_date']))."</td>";
                      echo "<td style='text-align: center;'>".$value['inspection_time']."</td>";
                      echo "<td>".$value['inspection_status']."</td>";
                      echo "<td style='text-align: center;'><a href='".site_url('/inspection/detail')."/".$value['inspectionid']." ' target='_blank' >view</a></td>";
                      echo "</tr>";
                    } ?>
                  <?php }else{?>
                    <tr>
                        <td colspan='6' style="text-align: center;" >No Data</td>
                    </tr>
                <?php } ?>
                </table>
                </th>
                <th align="left" valign="top" scope="col">
                 <table width="100%" border="0">
                  <tr>
                    <th scope="col" width="15%" style="text-align: center;">Round No.</th>
                    <th scope="col" width="20%" style="text-align: center;">Inspection No</th>
                    <th scope="col" width="25" style="text-align: center;">Inspection Date</th>
                    <th scope="col" width="10" style="text-align: center;">Inspection Time</th>
                    <th scope="col" width="15" style="text-align: center;">Status</th>
                    <th scope="col" width="5" style="text-align: center;"></th>
                  </tr>
                  <?php if(!empty($customer)){ 
                    foreach ($customer as $key => $value) { 
                      echo "<tr>";
                      echo "<td style='text-align: center;'>".$value['inspection_timeno']."</td>";
                      echo "<td>".$value['inspection_no']."</td>";
                      echo "<td style='text-align: center;'>".date("d/m/Y", strtotime($value['inspection_date']))."</td>";
                      echo "<td style='text-align: center;'>".$value['inspection_time']."</td>";
                      echo "<td>".$value['inspection_status']."</td>";
                      echo "<td style='text-align: center;'><a href='".site_url('/inspection/detail')."/".$value['inspectionid']."' target='_blank'>view</a></td>";
                      echo "</tr>";
                    } ?>
                <?php }else{?>
                  <tr>
                    <td colspan='6' style="text-align: center;" >No Data</td>
                  </tr>
                <?php } ?>


                </table>
                </th>
              </tr>
            </table>
        </tr>
    

    </table>
    <div style="clear:both"></div>
    <div style="text-align: center;"><button type="button" class="btn btn-cancel" onclick="jQuery('#actionwindow').window('close');"><i class="fa fa-close"></i> Cancel</button></div>

</div>


<script>
$(document).ready(function(){


});



</script>



