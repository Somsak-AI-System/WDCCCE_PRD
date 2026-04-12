<script type="text/javascript">
	jQuery.noConflict();
</script>
<tr>
   	<td colspan='4'>
   		
   		<table class="dvtContentSpace" style="border-bottom:0;" width="100%" cellspacing="0" cellpadding="3" border="0">
   		<tr>
   			<td>
   				<div id="RLContents">
   					
   					
   					<table class="small" style="border-bottom:1px solid #999999;padding:5px;" width="100%" cellspacing="0" cellpadding="0" border="0">
   						<tr>
   							<td valign="bottom"><b>History Status</b></td>						   							
   						</tr>
   					</table>
   					<?php if(empty($data)){?>
   					
   					<?php }else{?>
   					<table class="small" style="background-color:#eaeaea;" width="100%" cellspacing="1" cellpadding="3" border="0">
   						<tr style="height:25px" bgcolor="white">
							<td class="lvtCol" align="left">วันที่</td>
							<td class="lvtCol" align="left">เวลา</td>
							<td class="lvtCol" align="left">สถานะ</td>
							<td class="lvtCol" align="left">User</td>
							<td class="lvtCol" align="left">Assigned To</td>
						</tr>	
						<?php foreach ($data as $k => $v){?>
						<tr bgcolor="white">
							<td align="left"><?php echo $v["log_date"]?></td>
							<td align="left"><?php echo $v["log_time"]?></td>
							<td align="left"><?php echo $v["projectstatus"]?></td>
							<td align="left"><?php echo $v["userlogname"]?></td>
							<td align="left"><?php echo $v["assigntoname"]?></td>
						</tr>	
						<?php }//foreach?>
   					</table>
   					<?php }?>
   				</div>
   			</td>
   		</tr>
   		
   		</table>
   	
   	</td>
</tr>

<script>
jQuery( document ).ready(function() {

});
</script>