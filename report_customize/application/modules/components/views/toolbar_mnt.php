	<div class="toolbar" >
	<div id="p" class="easyui-panel" title="<?php echo lang("LBL_AC_TOOLBAR")?>" >
	
			<div class="ribbon-group">
				<div class="ribbon-toolbar">
					<a href="#" class="easyui-linkbutton" data-options="name:'add',iconCls:'icon-large-undo',iconAlign:'top',size:'large',plain:true," data-url=""  ><?php echo lang("AC_UNDO")?></a>
				</div>
				<div class="ribbon-toolbar">
					<a href="#" class="easyui-linkbutton" data-options="name:'delete',iconCls:'icon-large-redo',iconAlign:'top',size:'large',plain:true" data-url=""><?php echo lang("AC_REDO")?></a>
				</div>	
				<div class="ribbon-toolbar">
					<a href="#" class="easyui-linkbutton" data-options="name:'save',iconCls:'icon-large-refresh',iconAlign:'top',size:'large',plain:true" data-url=""><?php echo lang("AC_REFRESH")?></a>
				</div>
				
				<div class="ribbon-toolbar">
					<a href="#" class="easyui-linkbutton" data-options="name:'save',iconCls:'icon-large-save',iconAlign:'top',size:'large',plain:true" data-url="<?php echo $url."save"?>"><?php echo lang("AC_SAVE")?></a>
				</div>		
				<div class="ribbon-group-title"><?php echo lang("LBL_TOOlBAR_AC")?></div>
				</div>
			<div class="ribbon-group-sep"></div>
					
			
	</div>
	</div>