<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
<style type="text/css">
.btn_dis{
	background-color: #a3a3a3 !important;
    border: 1px solid #666666 !important;
}
</style>
<div  id="basicTab" >

	<tr>
		<td class="detailedViewHeader" colspan="4">
        
        <?php 	
        	$disabled = '';
        	$class = '';
        	if(isset($data) && $data['Type'] == 'S' && !empty($data['data'][0]['image'])){
        		if(count($data['data'][0]['image']) == 6){
        			$disabled = 'disabled';
        			$class = 'btn_dis';
        		}
        	}
        	
        ?>
        <input class="crmbutton small edit <?=$class ?>" <?=$disabled ?> type="button" value="เพิ่มรูปภาพ" style="height:30px;" onclick="JavaScript: void window.open('<?=$path_pic_std?>/upload_img_job.php?crmid=<?=$_GET["record"]?>&module=<?=$_GET["module"]?>&setype=HelpDesk Image','Application','resizable=yes,left=200,top=50,width=350,height=250,toolbar=no,scrollbars=no,menubar=no,location=no')"/>
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
                      <a href="<?=$v ?>" target="_blank"><img src="<?=$v ?>" alt="" width="150px" height="150px" ></a>
                      <button  class="crmbutton small edit" style="width:70%" type="button" onclick="deleteimage('<?= $k ?>','<?= $_GET["module"] ?>');" id="<?= $k ?>" >ลบ</button>
                    </div>						                         
		<?php 	} 
			} //foreach 
		?>
        	
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
jQuery( document ).ready(function() {

});

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