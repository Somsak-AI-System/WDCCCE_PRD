<?php
	session_start();
	$crmid=$_REQUEST["crmid"];
	$module=$_REQUEST["module"];
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<div data-options="region:'center',iconCls:'icon-ok'" style="width: 100%;" title="service"  id="divplan">
	<form method="POST" name="EditView" id="EditView" ENCTYPE="multipart/form-data">
	<table width="100%" cellpadding="5" cellspacing="0" border="0"  class="homePageMatrixHdr">
		<tr>
			<td style="padding:0px;" >
				
	                <input type="hidden" name="crmid" id="crmid" class="txtBox"  style="width:210px" value="<?php echo $_REQUEST["crmid"]?>">
	                <input type="hidden" name="module" id="module" class="txtBox"  style="width:210px" value="<?php echo $_REQUEST["module"]?>">
	                <tr>
					  <td width="10%" class="small">คอมเม้นต์</td>
					  <td width="90%" class="">
					  	<textarea id="comment" name="comment" cols="8" rows="4"></textarea>
					  	<b class="comment" style="color: red;display: none;">Please insert Comment</b>
					  </td>
					</tr>					
			</td>
			<tr>
			  
			  <td class=""></td>
			  <td class="">
			  	<button title="Save" class="crmbutton small save"type="button" name="button" value="  Save  " style="width:70px"> 
        			<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 15px; vertical-align: middle;">&nbsp;Save
        		</button>
        		<button title="Cancel" class="crmbutton small cancel" type="button" name="button" value="Cancel">
            		<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px;height: 17px; vertical-align: middle;">  Cancel
            	</button>
			  </td>
			</tr>
		</tr>
	</table>
	</form>
</div>

<script>
	
jQuery(function(){

	jQuery('.save').click(function(event) {
	
		var comment = document.getElementById('comment').value;
		comment = comment.trim();
		
		if(comment == ''){
			var flag = false ;
			document.getElementById('comment').focus();
			jQuery(".comment").css("display", "block");
		}
		
		if(flag == false){return false;}
		event.preventDefault();
		//console.log('submit');
		jQuery("#EditView").submit();
	});

	jQuery('#EditView').form({

		url:'plugin/Comment/savecomments.php',
		onSubmit:function(){
			
		},
		success:function(data){
		
			var obj = jQuery.parseJSON(data);
			if(obj.status!==true){
				var errMsg =  "Error: "+ obj.error;
				jQuery.messager.alert('Info', errMsg, 'info');
			}else{
				jQuery( "#comments_div" ).prepend('<div valign="top" style="width:99%;padding-top:10px;" class="dataField">'+obj.comment+'</div><div valign="top" style="width:99%;border-bottom:1px dotted #CCCCCC;padding-bottom:5px;" class="dataLabel"><font color="darkred">Author : '+obj.username+' on '+obj.dateadd+'&nbsp;</font></div>');
				jQuery('#dialog').window('close');
			}
			
			//var errMsg =  obj.msg + " " +obj.error ;
			/*jQuery.messager.alert('Info', errMsg, 'info', function(){
				if(obj.status==true){
					if(obj.url != '')
					{
						//location.reload();
						jQuery('#dialog').window('close');
					}
				}
				else{
					console.log(obj);
				}
			});*/
		}
	});

	jQuery(".cancel").click(function(){
		//location.reload();
		jQuery('#dialog').window('close');

	});

});

</script>