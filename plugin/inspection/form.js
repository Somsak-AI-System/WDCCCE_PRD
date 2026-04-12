function set_tolerance_amount(tbid){
	CalculateTolerance(tbid);
}

function set_tolerance_type(tbid){
	var selecttole = jQuery("#"+tbid+"_tolerance_type").val();
	jQuery.get("plugin/inspection/load.php?data=tolerance-form-box&tbid="+tbid+"&type="+selecttole , function( data ) {
		jQuery("#"+tbid+"-set-tolerance-form-box").html(data);
		jQuery("#"+tbid+"_set_tole_amount").focus();
		jQuery('.set-tole-amount').keyup(function(){
			set_tolerance_amount(jQuery(this).attr('tbid'));
		});
	});
}

function addMultiplePMList(TbID){
	jQuery('#add_pm_list_table_id').val(TbID);
	jQuery('#add-pm-list').html('เพิ่ม');
	jQuery('#pm_list_names').val('');
	jQuery('#AddPmListFormBox').modal('show');
}

function AddPmList(){
	jQuery('#add-pm-list-form').attr('action', 'save.php');
	jQuery('#add-pm-list-form').ajaxForm({
			beforeSend: function() {
				jQuery('#add-pm-list').html('เพิ่ม <img src="img/loading.gif" />');
			},
			uploadProgress: function(event, position, total, percentComplete) {
				if(jQuery("#AddPmListFormBox").css("display") != "block"){ jQuery('#AddPmListFormBox').modal('show'); }
			},
			success: function() {
				if(jQuery("#AddPmListFormBox").css("display") != "block"){ jQuery('#AddPmListFormBox').modal('show'); }
			},
			complete: function(xhr){
				try{
					var retobj = jQuery.parseJSON(xhr.responseText);
					if(retobj.result == "complete"){
						
						var TbID = jQuery('#add_pm_list_table_id').val();
						jQuery.each(retobj.list_tr, function(index, value) {
							//jQuery('#'+TbID+' tr:last').after(value);
							jQuery('#'+TbID+'-sortable').append(value);
							var number = jQuery('.'+TbID+'_rows').length;
							var newtrid = jQuery('#'+TbID+' tr:last').attr('id');
							jQuery('#'+newtrid+'_no').val(number);
						});
						PMListAutocomplete();
						//PMListSortable(TbID);
								
						/*jQuery.bigBox({
							title : "เพิ่มà¸‚à¹‰à¸­à¸¡à¸¹à¸¥à¸ªà¸³à¹€à¸£à¹‡à¸ˆ",
							color : "#739E73",
							icon : "fa fa-check",
							timeout : 3000
						});*/
						
					}else{
						/*jQuery.bigBox({
							title : "ผิดพลาด",
							content : retobj.massage,
							color : "#C46A69",
							icon : "fa fa-warning shake animated"
						});*/
					}
				}catch(e){
					/*jQuery.bigBox({
						title : "ผิดพลาด",
						content : xhr.responseText,
						color : "#C46A69",
						icon : "fa fa-warning shake animated"
					});*/
				}
				jQuery('#AddPmListFormBox').modal('hide');
			}
	});
	
	if(AddPmListValidate()){
		jQuery("#add-pm-list-form").submit();
	}
}

function AddPmListValidate(){
	var $checkoutForm = jQuery('#add-pm-list-form').validate({
		errorPlacement : function(error, element) {
			error.insertAfter(element);
		}
	});
	return jQuery('#add-pm-list-form').valid();
}

function addFormPmList(TbID){
	jQuery('#PMListBox').modal('show');
}

function save_work(){
	
	var InputTable = false;
	jQuery('.tables_parameter').each(function( index ){
		var TbID = jQuery(this).val();
		var TbType = jQuery('#'+TbID+'_type').val();
		if( TbType == 'calibrate' ){
			if( jQuery('#result-defective').prop('checked')==false ){
				if(CheckRequiredTable(TbID)){
					InputTable = true;
				}
			}else{
				RemoveRequiredTable(TbID);
			}
		}else
		if( TbType == 'pm' ){
			if( jQuery('#result-defective').prop('checked')==false ){
				if(CheckRequiredTablePM(TbID)){
					InputTable = true;
				}
			}else{
				
			}
		}else
		if( TbType == 'standards' ){
			
		}else
		if( TbType == 'note' ){
			if(jQuery('#result-defective').prop('checked')){
				//jQuery("#"+TbID+"_notetext").attr("required", "required");
				jQuery("#work_temperature").removeAttr("required");
				jQuery("#work_humidity").removeAttr("required");
				InputTable = true;
			}else{
				jQuery("#"+TbID+"_notetext").removeAttr("required");
				jQuery("#"+TbID+"_notetext").removeClass("invalid");
				jQuery("#"+TbID+"_notetext").parent().removeClass("state-error");
				jQuery("#work_temperature").attr("required", "required");
				jQuery("#work_humidity").attr("required", "required");
			}
		}else
		if( TbType == 'missing' ){
			CheckRequiredTableMissing(TbID);
			InputTable = true;
		}
	});
	
	if( jQuery('#result-defective').prop('checked') ){
		jQuery("#work_temperature").removeAttr("required");
		jQuery("#work_humidity").removeAttr("required");
		InputTable = true;
	}
	
	jQuery('#record-form').validate().resetForm();
	
	var PmCheckEmpty = false;
	if(jQuery('.pm-list-boxs').length > 0){
		jQuery('.pm-list-boxs').each(function( index ){
			var recheckID = jQuery(this).attr('box-id');
			if( jQuery('#'+recheckID+'-recheck-pass').prop('checked')==false && jQuery('#'+recheckID+'-recheck-fail').prop('checked')==false && jQuery('#'+recheckID+'-recheck-none').prop('checked')==false ){
				PmCheckEmpty = true;
			}
		});
	}
	
	if(PmCheckEmpty == true){
		/*jQuery.bigBox({
			title : "ผิดพลาด",
			content : "à¹€à¸Šà¹‡à¸„à¸‚à¹‰à¸­à¸¡à¸¹à¸¥à¸£à¸²à¸¢à¸à¸²à¸£ PM à¹„à¸¡à¹ˆà¸„à¸£à¸š à¸à¸£à¸¸à¸“à¸²à¸•à¸£à¸§à¸ˆà¸ªà¸­à¸šà¸­à¸µà¸à¸„à¸£à¸±à¹‰à¸‡",
			color : "#C46A69",
			icon : "fa fa-warning shake animated"
		});*/
		return false;
	}
	
	if(InputTable == false){
		/*jQuery.bigBox({
			title : "ผิดพลาด",
			content : "à¸à¸£à¸¸à¸“à¸²à¸•à¸£à¸§à¸ˆà¸ªà¸­à¸šà¸‚à¹‰à¸­à¸¡à¸¹à¸¥",
			color : "#C46A69",
			icon : "fa fa-warning shake animated"
		});*/
		return false;
	}
	
	if(SaveWorkValidate()){
		// Save here
		var Saving = jQuery.post( "save.php", jQuery( "#record-form" ).serialize() );
		
		Saving.done(function( data ) { //alert(data);
			try{
				var retobj = jQuery.parseJSON(data);
				if(retobj.result == 'complete'){
					/*jQuery.bigBox({
						title : "à¸šà¸±à¸™à¸—à¸¶à¸à¸‚à¹‰à¸­à¸¡à¸¹à¸¥à¸ªà¸³à¹€à¸£à¹‡à¸ˆ",
						color : "#739E73",
						icon : "fa fa-check",
						timeout : 3000
					});*/
					
					if(retobj.work_id == 'defective'){
						window.location.href = '?page=plan-equipments&id='+retobj.work_type+'&eq_list=eq_fail';
					}else{
						window.location.href = '?page='+retobj.work_type+'-cert&id='+retobj.work_id+'&hosp='+retobj.hosp;
					}
					
				}else{
					/*jQuery.bigBox({
						title : "ผิดพลาด",
						content : retobj.massage,
						color : "#C46A69",
						icon : "fa fa-warning shake animated"
					});*/
				}
			}catch(e){
				/*jQuery.bigBox({
					title : "ผิดพลาด",
					content : data,
					color : "#C46A69",
					icon : "fa fa-warning shake animated"
				});*/
			}
		});
		
		Saving.fail(function( data ) {
			/*jQuery.bigBox({
				title : "ผิดพลาด",
				content : "à¸à¸²à¸£à¹€à¸Šà¸·à¹ˆà¸­à¸¡à¸•à¹ˆà¸­à¸à¸±à¸š Server à¸¡à¸µà¸›à¸±à¸à¸«à¸² à¸¥à¸­à¸‡à¹ƒà¸«à¸¡à¹ˆà¸­à¸µà¸à¸„à¸£à¸±à¹‰à¸‡",
				color : "#C46A69",
				icon : "fa fa-warning shake animated"
			});*/
		});
		
	}else{
		/*jQuery.bigBox({
			title : "ผิดพลาด",
			content : "ข้อมูลไม่ถูกต้อง ตรวจเช็คข้อมูลอีกครั้ง",
			color : "#C46A69",
			icon : "fa fa-warning shake animated"
		});*/
	}
	
}

function CheckRequiredTablePM(TbID){
	return true;
}

function CheckRequiredTableMissing(TbID){

	jQuery("#"+TbID+"_missing_status").attr("required", "required");	
		
	jQuery("#work_date").removeAttr("required");
	jQuery("#work_due").removeAttr("required");
	jQuery("#work_next_date").removeAttr("required");
	jQuery("#work_temperature").removeAttr("required");
	jQuery("#work_humidity").removeAttr("required");

}

function ChangeStandardDate(){
	var work_date = jQuery('#work_date').val();
	
	jQuery.get("plugin/inspection/load.php?data=all-standards-box&date="+work_date , function( data ) {
		jQuery('.all-stds-box').each(function( index ){
			var StdBoxID = jQuery(this).attr("trid");
			var BeforeChange = jQuery('#'+StdBoxID+'_std_code').val();
			jQuery(this).html('<select name="'+StdBoxID+'_std_code" id="'+StdBoxID+'_std_code" style="width:100%;" class="select2">'+data+'</select>');
			jQuery('#'+StdBoxID+'_std_code').select2();
			jQuery('#'+StdBoxID+'_std_code').select2('val', BeforeChange);
		});
	});
	
	jQuery('.standard-boxs').each(function( index ){
		var boxID = jQuery(this).attr("id");
		var ParamID = jQuery(this).attr("param-id");
		var tableID = jQuery(this).attr("table-id");
		var scode = jQuery('#'+tableID+'_standard_code').val();
		jQuery.get("plugin/inspection/load.php?data=form-standards-box&param_id="+ParamID+"&tbid="+tableID+"&date="+work_date+"&code="+scode , function( data ) {
			jQuery('#'+boxID).html(data);
			jQuery('#'+tableID+'_standard_code').select2();
		});
	});
}

function CheckRequiredTable(TbID){
	
	var HasInput = false;
	jQuery('.'+TbID+'_rows').each(function( index ){
		var RowID = jQuery(this).val();
		if(parseInt(jQuery("#"+RowID+"_has_input").val()) > 0){
			HasInput = true;
		}
	});
	
	if(HasInput){
		return AddRequiredTable(TbID);
	}else{
		return RemoveRequiredTable(TbID);
	}
	
}

function CheckToleranceMode(TbID){
	var ToleranceType = parseInt(jQuery('#'+TbID+'_tolerance_type').val());
	
	if(ToleranceType == 1){
		var Checked = false;
		if(jQuery('#'+TbID+'_check_tolerance_unit').prop('checked')){
			Checked = true;
			jQuery("#"+TbID+"_tolerance_unit").attr("required", "required");
		}else{
			jQuery("#"+TbID+"_tolerance_unit").removeAttr("required");
		}
		
		if(jQuery('#'+TbID+'_check_tolerance_percent').prop('checked')){
			Checked = true;
			jQuery("#"+TbID+"_tolerance_percent").attr("required", "required");
		}else{
			jQuery("#"+TbID+"_tolerance_percent").removeAttr("required");
		}
		
		if(jQuery('#'+TbID+'_check_tolerance_fso').prop('checked')){
			Checked = true;
			jQuery("#"+TbID+"_tolerance_fso_val").attr("required", "required");
		}else{
			jQuery("#"+TbID+"_tolerance_fso_val").removeAttr("required");
		}
		
		if(Checked == true){
			jQuery('#'+TbID+'-tolerance-error').text('');
			return true;
		}else{
			jQuery('#'+TbID+'-tolerance-error').text('à¸à¸£à¸¸à¸“à¸²à¹€à¸¥à¸·à¸­à¸à¹‚à¸«à¸¡à¸”');
			return false;
		}
	}else
	if(ToleranceType <= 5){
		return true;
	}else
	if(ToleranceType == 6){
		return true;
	}else{
		jQuery('#'+TbID+'-tolerance-error').text('ผิดพลาด');
		return false;
	}
}

function AddRequiredTable(TbID){
	jQuery("#"+TbID+"_parameter").attr("required", "required");
	jQuery("#"+TbID+"_standard_code").attr("required", "required");
	jQuery("#"+TbID+"_uuc_resolution").attr("required", "required");
	jQuery("#"+TbID+"_unit").attr("required", "required");
		
	return CheckToleranceMode(TbID);
}

function RemoveRequiredTable(TbID){
	jQuery("#"+TbID+"_parameter").removeAttr("required");
	jQuery("#"+TbID+"_standard_code").removeAttr("required");
	jQuery("#"+TbID+"_uuc_resolution").removeAttr("required");
	jQuery("#"+TbID+"_unit").removeAttr("required");
	
	return false;
}

function SaveWorkValidate(){
	var $checkoutForm = jQuery('#record-form').validate({
		errorPlacement : function(error, element) {
			error.insertAfter(element);
		}
	});
	return jQuery('#record-form').valid();
}

function MissingSelectd(id){
	jQuery("#"+id).load( "plugin/inspection/load.php?data=work-results&val=MISSING" );
}

function DeleteForm(fid){
	if(confirm('Confirm delete form #'+fid)){
		jQuery.post( "save.php", { save_type: "delete_form", id: fid } );
		var Table = jQuery('#forms_table').DataTable();
			Table.row('#list-id-'+fid).remove().draw( false );
	}
}

function PermanentDelete(fid){
	if(confirm('à¸¢à¸·à¸™à¸¢à¸±à¸™à¸à¸²à¸£à¸¥à¸šà¸­à¸­à¸à¸ˆà¸²à¸à¸à¸²à¸™à¸‚à¹‰à¸­à¸¡à¸¹à¸¥ à¹„à¸¡à¹ˆà¸ªà¸²à¸¡à¸²à¸£à¸–à¹€à¸£à¸µà¸¢à¸à¸à¸¥à¸±à¸šà¹„à¸”à¹‰')){
		jQuery.post( "save.php", { save_type: "permanent_delete_form", id: fid } );
		var oTable = jQuery('#forms_table').dataTable();
			oTable.fnDraw(false);
	}
}

function RollbackForm(fid){
	if(confirm('à¸™à¸³à¸à¸¥à¸±à¸šà¸¡à¸²à¹ƒà¸Šà¹‰à¸­à¸µà¸à¸„à¸£à¸±à¹‰à¸‡')){
		jQuery.post( "save.php", { save_type: "rollback_form", id: fid } );
		jQuery("#dtb-select-forms").val('2');
		var oTable = jQuery('#forms_table').dataTable();
		var oSettings = oTable.fnSettings();
			oSettings.sAjaxSource = "plugin/inspection/load.php?data=forms&status=2";
			oTable.fnDraw(false);
		alert('à¹€à¸›à¸¥à¸µà¹ˆà¸¢à¸™à¸ªà¸–à¸²à¸™à¸°à¹€à¸›à¹‡à¸™à¹„à¸¡à¹ˆà¹ƒà¸Šà¹‰à¸‡à¸²à¸™');
	}
}

function rename(obj){
	var form_name = jQuery(obj).text();	
	var form_id = jQuery(obj).closest('td').attr('fid');
	// Back Up Name
	jQuery('#back-form-name').val(form_name);
	
	jQuery('#name-id-'+form_id).html('<form onsubmit="return save_rename('+form_id+');"><input id="rename_form" class="form-control" type="text" value="'+form_name+'"></form>');	
	jQuery( "#rename_form" ).focus();
			
	jQuery('#rename_form').blur(function(){
		save_rename(form_id);
		/*var Table = jQuery('#forms_table').dataTable();
			Table.fnDraw(false);*/
	});
}

function save_rename(form_id){
	var new_form_name = jQuery('#rename_form').val();
	var back_form_name = jQuery('#back-form-name').val();
	
	if(new_form_name!=back_form_name){
		var Saving = jQuery.post( "save.php", { save_type: "rename_form", id: form_id, new_name: new_form_name } );
		Saving.done(function( data ) {
			var retobj = jQuery.parseJSON(data);
			if(retobj.result == 'complete'){
				jQuery('#name-id-'+form_id).html('<span class="form-name" onclick="return rename(this);">'+new_form_name+'</span>');
				/*jQuery.bigBox({
					title : "à¸šà¸±à¸™à¸—à¸¶à¸à¸‚à¹‰à¸­à¸¡à¸¹à¸¥à¸ªà¸³à¹€à¸£à¹‡à¸ˆ",
					color : "#739E73",
					icon : "fa fa-check",
					timeout : 3000
				});*/
			}else{
				jQuery('#name-id-'+form_id).html('<span class="form-name" onclick="return rename(this);">'+retobj.form_name+'</span> <code><i class="fa fa-warning"></i> '+retobj.massage+'</code>');
				/*jQuery.bigBox({
					title : "ผิดพลาด",
					content : retobj.massage,
					color : "#C46A69",
					icon : "fa fa-warning shake animated"
				});*/
			}
		});
		
		Saving.fail(function( data ) {
			jQuery('#name-id-'+form_id).html('<span class="form-name" onclick="return rename(this);">'+back_form_name+'</span> <code><i class="fa fa-warning"></i> server connection failed.</code>');
			/*jQuery.bigBox({
				title : "ผิดพลาด",
				content : "à¸à¸²à¸£à¹€à¸Šà¸·à¹ˆà¸­à¸¡à¸•à¹ˆà¸­à¸à¸±à¸š Server à¸¡à¸µà¸›à¸±à¸à¸«à¸² à¸¥à¸­à¸‡à¹ƒà¸«à¸¡à¹ˆà¸­à¸µà¸à¸„à¸£à¸±à¹‰à¸‡",
				color : "#C46A69",
				icon : "fa fa-warning shake animated"
			});*/
		});
	}else{
		jQuery('#name-id-'+form_id).html('<span class="form-name" onclick="return rename(this);">'+back_form_name+'</span>');
	}
		
	return false;
}

function SavingForm(){
	//console.log(jQuery( "#design-form" ).serialize());
	//var Saving = jQuery.post( "save.php", jQuery("#design-form").serialize());
	alert(123444444);
	
	var data = jQuery("#EditView").serialize();
	jQuery("#data_template").val(data);

	return true;
	/*var Saving = jQuery.post("plugin/inspection/save.php", jQuery("#EditView").serialize());
	jQuery('#last-update-form').html('<img src="img/loading.gif" />');
	Saving.done(function( data ) {
		var retobj = jQuery.parseJSON(data);
		if(retobj.result == 'complete'){
			jQuery('#save-form-id').val(retobj.form_id);
			jQuery('#show-form-name').text(retobj.form_name);
			jQuery('#last-update-form').text(' - (updated '+retobj.updated+')');
			jQuery("#save-form-name").val('');
			if(jQuery("#dialog_save_form_as").dialog("isOpen")){
				jQuery('#dialog_save_form_as').dialog("close");
			}
			jQuery.bigBox({
				title : "à¸šà¸±à¸™à¸—à¸¶à¸à¸‚à¹‰à¸­à¸¡à¸¹à¸¥à¸ªà¸³à¹€à¸£à¹‡à¸ˆ",
				color : "#739E73",
				icon : "fa fa-check",
				timeout : 3000
			});
		}else
		if(retobj.result == 'error'){
			if(jQuery("#dialog_save_form_as").dialog("isOpen")===false){
				jQuery('#dialog_save_form_as').dialog("open");
			}
			jQuery('.help-block').html('<i class="fa fa-warning"></i> '+retobj.massage);
			jQuery("#form-name-box").removeClass("hide-error").addClass("has-error show-error");
			jQuery.bigBox({
				title : "ผิดพลาด",
				content : retobj.massage,
				color : "#C46A69",
				icon : "fa fa-warning shake animated"
			});
		}
	});
	Saving.fail(function( data ) {
		jQuery('.help-block').html('<i class="fa fa-warning"></i> Server connection failed.');
		jQuery("#form-name-box").removeClass("hide-error").addClass("has-error show-error");
		jQuery.bigBox({
			title : "ผิดพลาด",
			content : "à¸à¸²à¸£à¹€à¸Šà¸·à¹ˆà¸­à¸¡à¸•à¹ˆà¸­à¸à¸±à¸š Server à¸¡à¸µà¸›à¸±à¸à¸«à¸² à¸¥à¸­à¸‡à¹ƒà¸«à¸¡à¹ˆà¸­à¸µà¸à¸„à¸£à¸±à¹‰à¸‡",
			color : "#C46A69",
			icon : "fa fa-warning shake animated"
		});
	});*/
}

function SaveForm(){

	if(SaveFormValidate()){		
		
		SavingForm();
		/*var SaveFormID = parseInt(jQuery("#save-form-id").val());
		if(SaveFormID > 0){
			SavingForm();
		}else{
			SaveAsForm();
		}*/
	}else{

		return false;
	}
}

function SaveAsForm(){
	if(SaveFormValidate()){

		jQuery("#input-form-name").val('');
		var formtype = jQuery('#save-form-type').val();
		jQuery("#select-form-type").val(formtype);
		jQuery('#dialog_save_form_as').dialog('open');
	
	}
}

function SaveFormValidate(){
	var $checkoutForm = jQuery('#EditView').validate({
		rules : {
		},
		errorPlacement : function(error, element) {
			error.insertAfter(element);
		}
	});	
	//if( jQuery('#design-form').valid() ){
	if( jQuery('#EditView').valid() ){
		return CheckSourceIsSetting();
	}else{
	
		return false;
		/*jQuery.bigBox({
			title : "ผิดพลาด",
			content :"ข้อมูลไม่ถูกต้อง ตรวจเช็คข้อมูลอีกครั้ง",
			color : "#C46A69",
			icon : "fa fa-warning shake animated"
		});*/
	}
}

function CheckSourceIsSetting(){
	var count_error = 0;
	var error_content = '';
	jQuery(".uncersource_settings").each(function( index ){
		if( jQuery(this).val() == 0 ){
			var TbID = jQuery(this).attr('table-id');
			if( jQuery("#"+TbID+"_report_type").val() == 'average' ){
			
			}else{
				count_error++;
				error_content += '- "' + jQuery('#'+TbID+'_parameter').val() + ' Uncertainty Source<br />';
			}
		}
	});
	
	if(count_error > 0){
		/*jQuery.bigBox({
			title : "ผิดพลาด",
			content : error_content,
			color : "#C46A69",
			icon : "fa fa-warning shake animated"
		});*/
		return false;
	}else{
		return true;
	}
}

function SaveAsFormValidate(){
	var $checkoutForm = jQuery('#save-as-form-box').validate({
		rules : {
		},
		errorPlacement : function(error, element) {
			error.insertAfter(element);
		}
	});
	return jQuery('#save-as-form-box').valid();
}

function CalculateTolerance(TbID){
	var ToleranceType = parseInt(jQuery('#'+TbID+'_tolerance_type').val());
	var SetToleranceAmount = parseInt(jQuery('#'+TbID+'_set_tole_amount').val());
	var ToleranceUnit = jQuery('#'+TbID+'_tolerance_unit').val();
	var TolerancePercent = jQuery('#'+TbID+'_tolerance_percent').val();
	var ToleranceFsoPercent = jQuery('#'+TbID+'_tolerance_fso_percent').val();
	var ToleranceFsoVal = jQuery('#'+TbID+'_tolerance_fso_val').val();
	var RowID;
	var InputSET;
	var PercentToUnit = 0;
	var Tolerance;
	var ToleranceMin;
	var ToleranceMax;
	
	if(!SetToleranceAmount){
		SetToleranceAmount = '-';
	}
	
	jQuery('.'+TbID+'_rows').each(function( index ){
		
		RowID = jQuery(this).val();
		InputSET = parseFloat( jQuery('#'+RowID+'_col1').val() );
		jQuery('#'+RowID+'_min').val('');
		jQuery('#'+RowID+'_max').val('');
		
		if(ToleranceType > 1){
			
			if(ToleranceType==6){
				jQuery('#'+RowID+'_min').val('-');
				jQuery('#'+RowID+'_max').val('-');
			}else
			if(ToleranceType==2 || ToleranceType==3){
				jQuery('#'+RowID+'_min').val(SetToleranceAmount);
				jQuery('#'+RowID+'_max').val('-');
			}else
			if(ToleranceType==4 || ToleranceType==5){
				jQuery('#'+RowID+'_min').val('-');
				jQuery('#'+RowID+'_max').val(SetToleranceAmount);
			}
					
		}else{
		
			if(InputSET>0 || InputSET<0){
				
				var ToleranceArr = [];
				if(jQuery('#'+TbID+'_check_tolerance_unit').prop('checked')){
					ToleranceUnit = parseFloat(ToleranceUnit);
					ToleranceUnit = Math.abs(ToleranceUnit);
					ToleranceArr.push(ToleranceUnit);
				}
				
				if(jQuery('#'+TbID+'_check_tolerance_percent').prop('checked')){
					// Percent
					PercentToUnit = (TolerancePercent*InputSET)/100;
					PercentToUnit = PercentToUnit.toFixed(2);
					PercentToUnit = parseFloat(PercentToUnit);
					PercentToUnit = Math.abs(PercentToUnit);
					ToleranceArr.push(PercentToUnit);
				}
				
				if(jQuery('#'+TbID+'_check_tolerance_fso').prop('checked')){
					FsoToUnit = (ToleranceFsoPercent*ToleranceFsoVal)/100;
					FsoToUnit = FsoToUnit.toFixed(2);
					FsoToUnit = parseFloat(FsoToUnit);
					FsoToUnit = Math.abs(FsoToUnit);
					ToleranceArr.push(FsoToUnit);
				}
				
				if(ToleranceArr.length > 0){
					Tolerance = Math.max.apply( Math, ToleranceArr );
				}else{
					Tolerance = 0;
				}
				
				ToleranceMin = parseFloat(InputSET-Tolerance);
				ToleranceMax = parseFloat(InputSET+Tolerance);
				
				jQuery('#'+RowID+'_min').val(ToleranceMin.toFixed(2));
				jQuery('#'+RowID+'_max').val(ToleranceMax.toFixed(2));
				
			}
		
		}
		
		CalculateAverage(RowID);
	});
}

function CalculateAverage(RowID){
		
	var ReadCount = 0;
	var	ReadVal = 0;
	var	ReadTotal = 0;
	var Average = 0;
	
	var ToleranceMin = parseFloat(jQuery('#'+RowID+'_min').val());
	var ToleranceMax = parseFloat(jQuery('#'+RowID+'_max').val());
			
	jQuery('.read_'+RowID).each(function( index ){
		ReadVal = parseFloat(jQuery(this).val());

		if(ReadVal>0 || ReadVal<0){
			ReadCount++;
			ReadTotal+=ReadVal;
		}
		
	});
	
	if(ReadCount>0){
		Average = ReadTotal/ReadCount;
		
		if(Average>=ToleranceMin && Average<=ToleranceMax){
			jQuery('#'+RowID+'_average').html(Average.toFixed(2));
		}else{
			jQuery('#'+RowID+'_average').html(Average.toFixed(2));	
		}
		
		AddRequiredTagCal(RowID);
		
	}else{
		jQuery('#'+RowID+'_average').text('0.00');
		RemoveRequiredTagCal(RowID);
	}
}

function AddRequiredTagCal(RowID){
	jQuery("#"+RowID+"_col1").attr("required", "required");
	jQuery("#"+RowID+"_col2").attr("required", "required");
	jQuery("#"+RowID+"_col3").attr("required", "required");
	jQuery("#"+RowID+"_col4").attr("required", "required");
	jQuery("#"+RowID+"_col5").attr("required", "required");
	jQuery("#"+RowID+"_min").attr("required", "required");
	jQuery("#"+RowID+"_max").attr("required", "required");
	jQuery("#"+RowID+"_std_resolution").attr("required", "required");
	jQuery("#"+RowID+"_has_input").val(1);
}

function RemoveRequiredTagCal(RowID){
	jQuery("#"+RowID+"_col1").removeAttr("required");
	jQuery("#"+RowID+"_col2").removeAttr("required");
	jQuery("#"+RowID+"_col3").removeAttr("required");
	jQuery("#"+RowID+"_col4").removeAttr("required");
	jQuery("#"+RowID+"_col5").removeAttr("required");
	jQuery("#"+RowID+"_min").removeAttr("required");
	jQuery("#"+RowID+"_max").removeAttr("required");
	jQuery("#"+RowID+"_std_resolution").removeAttr("required");
	jQuery("#"+RowID+"_has_input").val(0);
}

function FormUnitBox(TbID, ParamID){
	
	//jQuery.get( "plugin/inspection/plugin/inspection/load.php?data=form-unit-box&param_id="+ParamID+"&tbid="+TbID , function( data ) {
	jQuery.get("plugin/inspection/load.php?data=tolerance-form-box&tbid="+tbid+"&type="+selecttole , function( data ) {
		
		var retobj = jQuery.parseJSON(JSON.stringify(data));
		
		if(retobj.result == 'complete'){
			jQuery('#'+TbID+'_unit_box').html(retobj.html);
			if(jQuery('#'+TbID+'_parameter').val() == ''){
				jQuery('#'+TbID+'_parameter').val(retobj.parameter.parameter_name);
			}
		}else{
			jQuery('#'+TbID+'_unit_box').html('<div style="color:#FF0000; text-align:center">ผิดพลาด...</div>');
		}

	});
}

function addTable(){
	jQuery.get( "plugin/inspection/load.php?data=form-table-cal" , function( data ) {
		//jQuery('#table-parameter').before(data);
		jQuery('#parameters-sortable').append(data);
		CheckHasTable();
	});
}

function addTablePM(){
	jQuery.get( "plugin/inspection/load.php?data=form-table-pm" , function( data ) {
		
		//console.log(data);
		
		jQuery('#parameters-sortable').append(data);
		CheckHasTable();
	});
}

function addTableSTD(){
	if(jQuery('.std-form-box').length == 0){
		jQuery.get( "plugin/inspection/load.php?data=form-table-std" , function( data ) {
			//jQuery('#table-parameter').before(data);
			jQuery('#parameters-sortable').append(data);
			CheckHasTable();
		});
	}else{
		alert('จำกัดตาราง STANDARD ได้แค่ 1 ตารางเท่านั้น');
	}
}

function addTableMissing(){
	jQuery.get( "plugin/inspection/load.php?data=form-table-missing" , function( data ) {
		//jQuery('#table-parameter').before(data);
		jQuery('#parameters-sortable').append(data);
		CheckHasTable();
	});
}

function addTableNote(){
	jQuery.get( "plugin/inspection/load.php?data=form-table-note" , function( data ) {
		//jQuery('#table-parameter').before(data);
		jQuery('#parameters-sortable').append(data);
		CheckHasTable();
	});
}

function addCustomTable(){
	jQuery.get( "plugin/inspection/load.php?data=form-table-custom" , function( data ) {
		jQuery('#parameters-sortable').append(data);
		CheckHasTable();
	});
}

function addRow(TbID){
	var uniqID = RowsID();
	jQuery.get( "plugin/inspection/load.php?data=form-tr-cal&tbid="+ TbID + "&uniqid=" + uniqID , function( data ) {
		//jQuery('#'+TbID+' tr:last').after(data);
		jQuery('#'+TbID+'-sortable').append(data);
	});
}

function addRowSTD(TbID){
	if(jQuery('.tr-form-'+TbID).length < 5){
		var uniqID = RowsID();
		jQuery.get( "plugin/inspection/load.php?data=form-tr-std&tbid="+ TbID + "&uniqid=" + uniqID , function( data ) {
			jQuery('#std-options-list-'+TbID).before(data);
			CheckHasTable();
		});
	}else{
		alert('à¸ˆà¸³à¸à¸±à¸”à¹„à¸”à¹‰à¸ªà¸¹à¸‡à¸ªà¸¸à¸” 5 à¹à¸–à¸§');
	}
}

function addRowPM(TbID){
	var uniqID = RowsID();
	jQuery.get( "plugin/inspection/load.php?data=form-tr-pm&tbid="+ TbID + "&uniqid=" + uniqID , function( data ) {
		//jQuery('#'+TbID+' tr:last').after(data);
		//jQuery('#'+TbID+' tbody').append(data);
		jQuery('#'+TbID+'-sortable').append(data);
		
		var number = jQuery('.'+TbID+'_rows').length;
		var newtrid = jQuery('#'+TbID+' tr:last').attr('id');
		jQuery('#'+newtrid+'_no').val(number);
		
		PMListAutocomplete();
		//PMListSortable(TbID);		
	});
}

function addRowCustom(TbID){
	var uniqID = RowsID();
	jQuery.get( "plugin/inspection/load.php?data=form-tr-custom&tbid="+ TbID + "&uniqid=" + uniqID , function( data ){
		jQuery('#'+TbID+'-rows').append(data);
	});
}

function RowsID(){
	var CountRowsID = parseInt(jQuery("#CountRowsID").val());
	jQuery("#CountRowsID").val(CountRowsID+1);
	return CountRowsID;
}

function removeTable(rmTb){
	if(confirm('Confirm Remove Table')){
		jQuery('#'+rmTb).remove();
		CheckHasTable();
	}
}

function CheckHasTable(){
	if(jQuery('.table').length == 0){
		jQuery('#table-parameter').css({display:'block'});
		jQuery('.save-form').css({display:'none'});
		jQuery('#control-number').css({display:'none'});
	}else{
		jQuery('#table-parameter').css({display:'none'});
		jQuery('.save-form').css({display:'block'});
		jQuery('#control-number').css({display:'block'});
	}
}

function removeRow(trId, TbID){
	if(jQuery('.tr-form-'+TbID).length > 1){
		if(confirm('Confirm Remove Row')){
			jQuery('#'+trId).remove();
			
			var number = 0;
			jQuery(".tr-form-"+TbID).each(function( index ){
				number++;
				var trid = jQuery(this).attr('id');
				jQuery('#'+trid+'_no').val(number);
			});
		}
	}
}

function ResetPMRowNumber(TbID){
	var number = 0;
	jQuery(".tr-form-"+TbID).each(function( index ){
		number++;
		var trid = jQuery(this).attr('id');
		jQuery('#'+trid+'_no').val(number);
	});
}

function CheckSourceRequiredTags(){
	jQuery(".checked-source-active").each(function( index ){
		CheckSourceRequiredTag(this);
	});
}

function CheckSourceRequiredTag(elm){
	var Symbol = jQuery(elm).attr('symbol');
	if( jQuery(elm).prop('checked') ){
		jQuery("#source_name_"+Symbol).attr("required", "required");
		jQuery("#source_value_"+Symbol).attr("required", "required");
		jQuery("#source_divisor_"+Symbol).attr("required", "required");
		jQuery("#source_ci_"+Symbol).attr("required", "required");
		jQuery("#source_veff_"+Symbol).attr("required", "required");
	}else{
		jQuery("#source_name_"+Symbol).removeAttr("required");
		jQuery("#source_value_"+Symbol).removeAttr("required");
		jQuery("#source_divisor_"+Symbol).removeAttr("required");
		jQuery("#source_ci_"+Symbol).removeAttr("required");
		jQuery("#source_veff_"+Symbol).removeAttr("required");
	}
}

function SourceOfUncertaintyMain(TbID){
	
	jQuery( "#source-of-uncertainty-form" ).validate().resetForm();
	
	var symbol;
	jQuery('#source-of-uncertainty-tbid').val(TbID);
	
	jQuery(".uncer_source_symbol").each(function( index ){
		symbol = jQuery( this ).val();
		
		if( jQuery('#'+TbID+'_source_active_'+symbol).val() == 1){
			jQuery('#source_active_'+symbol).prop('checked', true);
		}else{
			jQuery('#source_active_'+symbol).prop('checked', false);
		}
		
		jQuery('#source_name_'+symbol).val( jQuery('#'+TbID+'_source_name_'+symbol).val() );
		jQuery('#source_value_'+symbol).val( jQuery('#'+TbID+'_source_value_'+symbol).val() );
		jQuery('#source_divisor_'+symbol).val( jQuery('#'+TbID+'_divisor_'+symbol).val() );
		jQuery('#source_ci_'+symbol).val( jQuery('#'+TbID+'_ci_'+symbol).val() );
		jQuery('#source_veff_'+symbol).val( jQuery('#'+TbID+'_veff_'+symbol).val() );
		
	});
	
	if( jQuery("#"+TbID+"_report_type").val() == 'uncertainty' ){
		jQuery('#set_report_uncertainty_box').html('<input name="set_report_type" id="set_report_uncertainty" value="uncertainty" type="radio" checked="checked"><i></i> รายงานค่า Uncertainty');
		jQuery('#set_report_average_box').html('<input name="set_report_type" id="set_report_average" value="average" type="radio"><i></i> รายงานค่า Average');
	}else
	if( jQuery("#"+TbID+"_report_type").val() == 'average' ){
		jQuery('#set_report_uncertainty_box').html('<input name="set_report_type" id="set_report_uncertainty" value="uncertainty" type="radio"><i></i> รายงานค่า Uncertainty');
		jQuery('#set_report_average_box').html('<input name="set_report_type" id="set_report_average" value="average" type="radio" checked="checked"><i></i> รายงานค่า Average');
	}else{
		jQuery('#set_report_uncertainty_box').html('<input name="set_report_type" id="set_report_uncertainty" value="uncertainty" type="radio" checked="checked"><i></i> รายงานค่า Uncertainty');
		jQuery('#set_report_average_box').html('<input name="set_report_type" id="set_report_average" value="average" type="radio"><i></i> รายงานค่า Average');
	}
	
	if( jQuery("#"+TbID+"_adds_phototherapy").val() == 1 ){
		jQuery('#adds_phototherapy').attr("checked", "checked");
	}else{
		jQuery("#adds_phototherapy").removeAttr("checked");
	}
		
	CheckSourceRequiredTags();
	jQuery('#SourceOfUncertainty').modal('show');
	
}

function SubmitSource(){
	var symbol;
	var TbID = jQuery('#source-of-uncertainty-tbid').val();
	if(UncerSourceValidate()){
		var count_source = 0;
		jQuery(".uncer_source_symbol").each(function( index ) {
			symbol = jQuery( this ).val();
			
			if(jQuery('#source_active_'+symbol).prop('checked')){
				count_source++;
				jQuery('#'+TbID+'_source_active_'+symbol).val('1');
			}else{
				jQuery('#'+TbID+'_source_active_'+symbol).val('');
			}
			
			jQuery('#'+TbID+'_source_name_'+symbol).val( jQuery('#source_name_'+symbol).val() );
			jQuery('#'+TbID+'_source_value_'+symbol).val( jQuery('#source_value_'+symbol).val() );
			jQuery('#'+TbID+'_divisor_'+symbol).val( jQuery('#source_divisor_'+symbol).val() );
			jQuery('#'+TbID+'_ci_'+symbol).val( jQuery('#source_ci_'+symbol).val() );
			jQuery('#'+TbID+'_veff_'+symbol).val( jQuery('#source_veff_'+symbol).val() );
		});
		
		if(count_source > 0){
			jQuery('#'+TbID+'_uncersource_setting').val(1);
		}else{
			jQuery('#'+TbID+'_uncersource_setting').val(0);
		}
		
		if( jQuery('#set_report_uncertainty').prop('checked') ){
			jQuery("#"+TbID+"_report_type").val('uncertainty');
		}else
		if( jQuery('#set_report_average').prop('checked') ){
			jQuery("#"+TbID+"_report_type").val('average');
		}else{
			jQuery("#"+TbID+"_report_type").val('uncertainty');
		}
		
		if( jQuery('#adds_phototherapy').prop('checked') ){
			jQuery("#"+TbID+"_adds_phototherapy").val('1');
		}else{
			jQuery("#"+TbID+"_adds_phototherapy").val('');
		}
		
		jQuery('#SourceOfUncertainty').modal('hide');
	}
}

function UncerSourceValidate(){
	var $checkoutForm = jQuery('#source-of-uncertainty-form').validate({
		errorPlacement : function(error, element) {
			error.insertAfter(element);
		}
	});
	return jQuery('#source-of-uncertainty-form').valid();
}

function UncheckPMRow(RowID, TbID){
	jQuery( '#'+RowID+'_result_pass' ).html( '<input class="'+TbID+'-pass click-reset" name="'+RowID+'_result" value="Pass" type="radio"><i></i>' );
	jQuery( '#'+RowID+'_result_fail' ).html( '<input class="'+TbID+'-fail click-reset" name="'+RowID+'_result" value="Fail" type="radio"><i></i>' );
	jQuery( '#'+RowID+'_result_none' ).html( '<input class="'+TbID+'-none click-reset" name="'+RowID+'_result" value="None" type="radio"><i></i>' );
}

function PMSelectAllPass(TbID){
	var RowID;
	jQuery('.'+TbID+'-pass').each(function( index ){
		RowID = jQuery( this ).attr( "name" );
		jQuery( '#'+RowID+'_pass' ).html( '<input class="'+TbID+'-pass click-reset" name="'+RowID+'" value="Pass" type="radio" checked="checked"><i></i>' );
		jQuery( '#'+RowID+'_fail' ).html( '<input class="'+TbID+'-fail click-reset" name="'+RowID+'" value="Fail" type="radio"><i></i>' );
		jQuery( '#'+RowID+'_none' ).html( '<input class="'+TbID+'-none click-reset" name="'+RowID+'" value="None" type="radio"><i></i>' );
	});
	return;
}

function PMSelectAllFail(TbID){	
	var RowID;
	jQuery('.'+TbID+'-fail').each(function( index ){
		RowID = jQuery( this ).attr( "name" );
		jQuery( '#'+RowID+'_pass' ).html( '<input class="'+TbID+'-pass click-reset" name="'+RowID+'" value="Pass" type="radio"><i></i>' );
		jQuery( '#'+RowID+'_fail' ).html( '<input class="'+TbID+'-fail click-reset" name="'+RowID+'" value="Fail" type="radio" checked="checked"><i></i>' );
		jQuery( '#'+RowID+'_none' ).html( '<input class="'+TbID+'-none click-reset" name="'+RowID+'" value="None" type="radio"><i></i>' );
	});
	return;
}

function PMSelectAllNone(TbID){		
	var RowID;
	jQuery('.'+TbID+'-none').each(function( index ){
		RowID = jQuery( this ).attr( "name" );
		jQuery( '#'+RowID+'_pass' ).html( '<input class="'+TbID+'-pass click-reset" name="'+RowID+'" value="Pass" type="radio"><i></i>' );
		jQuery( '#'+RowID+'_fail' ).html( '<input class="'+TbID+'-fail click-reset" name="'+RowID+'" value="Fail" type="radio"><i></i>' );
		jQuery( '#'+RowID+'_none' ).html( '<input class="'+TbID+'-none click-reset" name="'+RowID+'" value="None" type="radio" checked="checked"><i></i>' );
	});
	return;
}