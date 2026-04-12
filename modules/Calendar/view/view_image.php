<!-- <link rel="stylesheet" type="text/css" media="all" href="asset/css/metro-blue/easyui.css">
<link rel="stylesheet" type="text/css" media="all" href="asset/css/smoothness/jquery-ui-1.10.4.custom.min.css">
<link rel="stylesheet" type="text/css" media="all" href="asset/css/icon.css">
<link rel="stylesheet" type="text/css" media="screen" href="asset/css/multi-select.css"  >

<script type="text/javascript" src="asset/js/jquery.min.js"></script>
<script type="text/javascript" src="asset/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="asset/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.multi-select.js" ></script>

<link rel="stylesheet" type="text/css" media="screen" href="asset/css/custom.css"  >

<script type="text/javascript" src="asset/js/my_function_questionnaire.js" ></script>

<script type="text/javascript">
	jQuery.noConflict();
</script> -->
 <script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
 
			<div  id="basicTab" >
			
					<tr>
						<td class="detailedViewHeader" colspan="4">
                        <input class="crmbutton small edit" type="button" value="เพิ่มรูปถ่าย" style="height:30px;" onclick="JavaScript: void window.open('<?=$path_pic_std?>/upload_img_job.php?crmid=<?=$_GET["record"]?>&module=<?=$_GET["module"]?>','Application','resizable=yes,left=200,top=50,width=350,height=250,toolbar=no,scrollbars=no,menubar=no,location=no')"/>
						</td>
					</tr>

					<?php
					if(isset($data) && $data['Type'] == 'S' && !empty($data['data'][0]['image'])){
					?>
						<tr>
							<td class="dvtCellInfo"  valign="top" align="left" colspan="5">
                            
						<?php
							 foreach ($data['data'][0]['image'] as $key => $val){	
								 foreach ($val as $k => $v){		
						?>	
										<div class="imgbox" id="imgbox<?=$k ?>">
                                          <a href="<?=$v ?>" target="_blank"><img src="<?=$v ?>" alt="" width="130px" height="130px" ></a>
                                          <button  class="crmbutton small edit" style="width: 70%" type="button" onclick="deleteimage('<?= $k ?>','<?= $_GET["module"] ?>');" id="<?= $k ?>" >ลบ</button>
                                        </div>						                         
						
					<?php } 
						 	 } //foreach ?>
                        	
							</td>
						</tr>

					<?php } else{ ?>
											
						<tr>
							<td class="dvtCellInfo"  valign="top" align="left" colspan="4">
                            	<img src="include/images/noimage.gif"  width="150"%>
							</td>
						</tr>
		
					<?php }?>
                    
				</div>

<script>
/*jQuery( document ).ready(function() {

});*/

function deleteimage(id,module){	
	
	var dlg = jQuery.messager.confirm({
		title: 'Messager',
		msg: 'Are you sure you want to delete image ?',
		buttons:[{
			text: 'OK',
			onClick: function(){
				jQuery.ajax({
				   type: "POST",
				   url: "delete_image.php" ,
				   cache: false,
				   data: {crmid:id,module:module},
				   dataType: "json",
				   success: function(returndate){
					//alert (returndate['Type']);
					var obj = jQuery.parseJSON(returndate);
						if(obj['Type'] == 'S'){
							jQuery.messager.alert('Messager', 'Delete Success');
							dlg.dialog('destroy');
							location.reload();
						}else{
							jQuery.messager.alert('Messager' , 'Delete Fail !');
							dlg.dialog('destroy');
						}
					}
				 });			
			}
		},{
			text: 'CANCEL',
			onClick: function(){
				dlg.dialog('destroy')
			}
		}]
	});
}
</script>

<style>
.imgbox {
    float: left;
    text-align: center;
    width: 150px;
	height:175px;
    border: 1px solid gray;
    margin: 4px;
    padding: 10px;
}

/*button {
    width: 70%;
}*/
</style>