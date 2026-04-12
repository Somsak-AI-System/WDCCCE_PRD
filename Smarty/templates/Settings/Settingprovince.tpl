{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script type="text/javascript" src="asset/js/jquery.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
<link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css">

<script type="text/javascript" src="asset/js/datagrid-filter.js"></script>
<script type="text/javascript" src="asset/js/bootstrap.min_new.js"></script>
<link rel="stylesheet" type="text/css" href="asset/css/icon.css">

<link rel="stylesheet" type="text/css" href="asset/css/style.min.css">
<link rel="stylesheet" type="text/css" href="asset/css/kendo.bootstrap.min.css" >

{literal}
<style type="text/css">
.page-wrapper {
	background-color: #fff;
}

.tab-content {
	margin-top: 10px;
}
span{
	font-size: 12px !important;
}
ul li{
	padding-bottom: 0px !important;
}
label{
	font-size: 12px !important;
}
.tab-content.tabcontent-border{
	margin-left: 10px !important;
}
.active{
	color: #000 !important;
}
</style>
{/literal}
<br>
<div id="dialog" style="display:none;">Dialog Content.</div>

	<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
	<tbody>
	<tr>
		<td valign="top"><img src="{'showPanelTopLeft.gif'|@aicrm_imageurl:$THEME}"></td>
		<td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
		<form action="index.php" method="post" name="EditView" id="form" onsubmit="VtigerJS_DialogBox.block();">
			<input type='hidden' name='module' value='Users'>
			<input type='hidden' name='action' value='EditView'>
			<input type='hidden' name='return_action' value='ListView'>
			<input type='hidden' name='return_module' value='Users'>
			<input type='hidden' name='parenttab' value='Settings'>

			<br>

			<div align=center>

	{include file='SetMenu.tpl'}
	<!-- DISPLAY -->
		<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
			<tr>
				<td width=50 rowspan=2 valign=top><img src="{'Icon_SettingProvince.png'|@aicrm_imageurl:$THEME}" alt="{$MOD.LBL_USERS}" width="48" height="48" border=0 title="{$MOD.LBL_USERS}"></td>
				<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a>  > {$MOD.LBL_PROVINCE}</b></td>
			</tr>
			<tr>
				<td valign=top class="small">{$MOD.LBL_PROVINCE_DESCRIPTION}</td>
			</tr>
		</table>

		<br>

		<table border=0 cellspacing=0 cellpadding=10 width=100% >
			<tr>
				<td>
					<div id="ListViewContents">
						
						<div class="page-wrapper">
							<div class="container-fluid">
								<ul class="nav nav-tabs" role="tablist">
									<li class="nav-item"> 
										<a class="nav-link active" data-toggle="tab" href="#province" role="tab">
											<span class="hidden-xs-down">จังหวัด</span>
										</a> 
									</li>
									<li class="nav-item"> 
										<a class="nav-link" data-toggle="tab" href="#district" role="tab">
											<span class="hidden-xs-down">อำเภอ</span>
										</a> 
									</li>
									<li class="nav-item"> 
										<a class="nav-link" data-toggle="tab" href="#canton" role="tab">
											<span class="hidden-xs-down">ตำบล</span>
										</a> 
									</li>
									<li class="nav-item"> 
										<a class="nav-link" data-toggle="tab" href="#postalcode" role="tab">
											<span class="hidden-xs-down">รหัสไปรษณีย์</span>
										</a>
									</li>
								</ul>

								<div class="tab-content tabcontent-border">
									<div class="tab-pane active" id="province" role="tabpanel">
										<form id="save_province" name="save_province" action="#" method="POST">
											<div class="col-12">
												<div class="row">
													<div class="col-3">
														<div class="form-group row">
															<label class="col-sm-3 control-label col-form-label">
																เลือกภาค
															</label>
															<div class="col-sm-9">
																<select class="form-control" id="sectornameval" name="sectornameval">
																	{foreach name=key item=value from=$REGION}
																		<option value="{$value.REGIONID_NAME}" data-sectorid="{$value.REGIONID}">{$value.REGIONID_NAME}</option>
																	{/foreach}
																</select>
															</div>
														</div>
													</div>
													
													<input type="hidden" name="sector_id" id="sector_id">
													<input type="hidden" name="sector_name" id="sector_name">
													
													<div class="col-6">
														<div class="form-group row">
															<label class="col-sm-2 control-label col-form-label">
																กรอกจังหวัด
															</label>
															<div class="col-sm-10">
																<input type="text" name="provincename" id="provincename" class="form-control">
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-12">
														<button type="button" class="waves-effect waves-light crmButton small save" onclick="saveprovince()"><img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;"> Save</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="tab-pane" id="district" role="tabpanel">
										<form id="form_save_district" action="#" method="POST">
											<div class="col-12">
												<div class="row">
													<div class="col-3">
														<div class="form-group row">
															<label class="col-sm-3 control-label col-form-label">
																เลือกจังหวัด
															</label>
															<div class="col-sm-9">
																<select class="form-control" id="provinceval" name="provinceval">
																	{foreach name=key item=value from=$PROVINCE}
																	<option value="{$value.PROV_NAME}" data-provinceid="{$value.province_id}">{$value.PROV_NAME}</option>
																	{/foreach}
																</select>
															</div>
														</div>
													</div>
													<input type="hidden" name="province_id" id="province_iddistrict">
													<input type="hidden" name="province_name" id="province_name">
													<div class="col-6">
														<div class="form-group row">
															<label class="col-sm-2 control-label col-form-label">
																กรอกอำเภอ
															</label>
															<div class="col-sm-10">
																<input type="text" name="amphur_name" id="amphur_name" class="form-control">
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-12">
														<button type="button" class="waves-effect waves-light crmButton small save" onclick="savedistrict()"><img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;"> Save</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="tab-pane" id="canton" role="tabpanel">
										<form id="form_save_subdistrict" action="#" method="POST">
											<div class="col-12">
												<div class="row">
													<div class="col-3">
														<div class="form-group row">
															<label class="col-sm-3 control-label col-form-label">
																เลือกจังหวัด
															</label>
															<div class="col-sm-9">

																<select class="form-control" onchange="Get_Amphur()" name="provinceamphur" id="provinceamphur">
																	{foreach name=key item=value from=$PROVINCE}
																	<option value="{$value.PROV_NAME}" data-provinceid="{$value.province_id}">{$value.PROV_NAME}</option>
																	{/foreach}
																</select>
															</div>
														</div>
													</div>
													<input type="hidden" name="province_idamphur" id="province_idamphur">
													<input type="hidden" name="province_nameamphur" id="province_nameamphur">
													<input type="hidden" name="amphur_id" id="amphur_id">
													<input type="hidden" name="amphur_name" id="nameamphur">
													<div class="col-3">
														<div class="form-group row">
															<label class="col-sm-3 control-label col-form-label">
																เลือกอำเภอ
															</label>
															<div class="col-sm-9">
																<select class="form-control" name="amphur" id="amphur">
																	
																</select>
															</div>
														</div>
													</div>
													<div class="col-5">
														<div class="form-group row">
															<label class="col-sm-2 control-label col-form-label">
																กรอกตำบล
															</label>
															<div class="col-sm-10">
																<input type="text" name="district_name" id="district_name" class="form-control">
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-12">
														<button type="button" class="waves-effect waves-light crmButton small save" onclick="savesubdistrict()"><img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;"> Save</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="tab-pane" id="postalcode" role="tabpanel">
										<form id="form_save_postcode" action="#" method="POST">
											<div class="col-12">
												<div class="row">
													<div class="col-3">
														<div class="form-group row">
															<label class="col-sm-3 control-label col-form-label">
																เลือกจังหวัด
															</label>
															<div class="col-sm-9">
																<select class="form-control" onchange="getamphurpostalcode()" name="provincepostalcode" id="provincepostalcode">
																	{foreach name=key item=value from=$PROVINCE}
																	<option value="{$value.PROV_NAME}" data-provinceid="{$value.province_id}">{$value.PROV_NAME}</option>
																	{/foreach}
																</select>
															</div>
														</div>
													</div>
													<div class="col-3">
														<div class="form-group row">
															<label class="col-sm-3 control-label col-form-label">
																เลือกอำเภอ
															</label>
															<div class="col-sm-9">
																<select class="form-control" onchange="getdistrictpostalcode()" name="amphurpostalcode" id="amphurpostalcode">
																	
																</select>
															</div>
														</div>
													</div>
													<div class="col-3">
														<div class="form-group row">
															<label class="col-sm-3 control-label col-form-label">
																เลือกตำบล
															</label>
															<div class="col-sm-9">
																<select class="form-control" name="districtpostalcode" id="districtpostalcode">
																	
																</select>
															</div>
														</div>
													</div>
													<input type="hidden" name="district_id" id="district_id">
													<input type="hidden" name="provinceidpostalcoder" id="provinceidpostalcoder">
													<input type="hidden" name="provincenamepostalcode" id="provincenamepostalcode">
													<input type="hidden" name="amphuridpostalcoder" id="amphuridpostalcoder">
													<input type="hidden" name="amphurnamepostalcoder" id="amphurnamepostalcoder">
												</div>
												<div class="row">
													<div class="col-6" style="font-size: 14px;">
														<div class="form-group row" style="font-size: 14px;">
															<label class="col-sm-2 control-label col-form-label">
																กรอกรหัสไปรษณีย์
															</label>
															<div class="col-sm-10" style="font-size: 14px;">
																<input type="text" name="post_code" id="post_code" class="form-control text_number_int" style="font-size: 14px;">
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-12">
														<button type="button" class="waves-effect waves-light crmButton small save" onclick="addPostcode()"><img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;"> Save</button>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>

					</div>	
				</td>
			</tr>
		</table>
	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>

</div>

</td>
<!-- <td valign="top"><img src="{'showPanelTopRight.gif'|@aicrm_imageurl:$THEME}"></td> -->
</tr>
</tbody>
</table>

{literal}
<script>
jQuery(function () {
	var selectedprovince = jQuery("#provinceamphur").find('option:selected');
	var province_idval = selectedprovince.data('provinceid');
	var provincenameamphur = jQuery("#provinceamphur").val();

	jQuery('#province_idamphur').val(province_idval);
	jQuery('#province_nameamphur').val(provincenameamphur);

	var url = "modules/Settings/Province/get_amphur.php";
	var data = jQuery("#form_save_subdistrict").serialize();

	jQuery("#amphur").empty();
	jQuery("#amphur").append();
	jQuery.ajax(url, {
		type: "POST",
		data: data,
		success: function (data) {
			var result = jQuery.parseJSON(data);
			jQuery.each(result, function( index, value ) {
			  jQuery('<option>').val(value.amphur_name).text(value.amphur_name).attr('data-amphurid', value.amphur_id).appendTo('#amphur');
			});
        },
        error: function (data) {
            console.log("error");
        },
    });


	var selectedprovincepostalcode = jQuery("#provincepostalcode").find('option:selected');
	var province_idpostalcode = selectedprovincepostalcode.data('provinceid');
	var provincenamepostalcode = jQuery("#provincepostalcode").val();
	
	jQuery('#provinceidpostalcoder').val(province_idpostalcode);
	jQuery('#provincenamepostalcode').val(provincenamepostalcode);

	var url = "modules/Settings/Province/get_amphur.php";
	var data = jQuery("#form_save_postcode").serialize();

	jQuery("#amphurpostalcode").empty();
	jQuery("#amphurpostalcode").append();

	jQuery.ajax(url, {
		type: "POST",
		data: data,
		success: function (data) {
			var result = jQuery.parseJSON(data);
			jQuery.each(result, function( index, value ) {
			  jQuery('<option>').val(value.amphur_name).text(value.amphur_name).attr('data-amphurid', value.amphur_id).appendTo('#amphurpostalcode');
			});
			getdistrictpostalcode();
        },
        error: function (data) {
            console.log("error");
        },
    });

	jQuery(".text_number_int").on("keypress keyup blur",function (event) {
		jQuery(this).val(jQuery(this).val().replace(/[^\d].+/, ""));
		if ((event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
	});

});

function Get_Amphur() {
	var selectedprovince = jQuery("#provinceamphur").find('option:selected');
	var province_idval = selectedprovince.data('provinceid');
	var provincenameamphur = jQuery("#provinceamphur").val();
	
	jQuery('#province_idamphur').val(province_idval);
	jQuery('#province_nameamphur').val(provincenameamphur);

	var url = "modules/Settings/Province/get_amphur.php";
	var data = jQuery("#form_save_subdistrict").serialize();

	jQuery("#amphur").empty();
	jQuery("#amphur").append();

	jQuery.ajax(url, {
		type: "POST",
		data: data,
		success: function (data) {
			var result = jQuery.parseJSON(data);
			jQuery.each(result, function( index, value ) {
			  jQuery('<option>').val(value.amphur_name).text(value.amphur_name).attr('data-amphurid', value.amphur_id).appendTo('#amphur');
			});
        },
        error: function (data) {
            console.log("error");
        },
    });
}

function getamphurpostalcode() {
	var selectedprovince = $("#provincepostalcode").find('option:selected');
	var province_idpostalcode = selectedprovince.data('provinceid');
	var provincenamepostalcode = jQuery("#provincepostalcode").val();
	
	jQuery('#provinceidpostalcoder').val(province_idpostalcode);
	jQuery('#provincenamepostalcode').val(provincenamepostalcode);

	var url = "modules/Settings/Province/get_amphur.php";
	var data = jQuery("#form_save_postcode").serialize();

	jQuery("#amphurpostalcode").empty();
	jQuery("#amphurpostalcode").append();

	jQuery.ajax(url, {
		type: "POST",
		data: data,
		success: function (data) {
			var result = jQuery.parseJSON(data);
			jQuery.each(result, function( index, value ) {
			  jQuery('<option>').val(value.amphur_name).text(value.amphur_name).attr('data-amphurid', value.amphur_id).appendTo('#amphurpostalcode');
			});
			
			getdistrictpostalcode();

        },
        error: function (data) {
            console.log("error");
        },
    });
}

function getdistrictpostalcode() {

	var selectedamphur = jQuery("#amphurpostalcode").find('option:selected');
	var amphuridpostalcoder = selectedamphur.data('amphurid');
	var amphurnamepostalcoder = jQuery("#amphurpostalcode").val();

	jQuery('#amphuridpostalcoder').val(amphuridpostalcoder);
	jQuery('#amphurnamepostalcoder').val(amphurnamepostalcoder);

	var url = "modules/Settings/Province/get_district.php";
	var data = jQuery("#form_save_postcode").serialize();

	jQuery("#districtpostalcode").empty();
	jQuery("#districtpostalcode").append();

	jQuery.ajax(url, {
		type: "POST",
		data: data,
		success: function (data) {
			var result = jQuery.parseJSON(data);
			jQuery.each(result, function( index, value ) {
			  jQuery('<option>').val(value.district_name).text(value.district_name).attr('data-districtid', value.district_id).appendTo('#districtpostalcode');
			});
        },
        error: function (data) {
            console.log("error");
        },
    });
}

function saveprovince() {

	var selected = jQuery("#sectornameval").find('option:selected');
	var sectorid = selected.data('sectorid');
	var sectorname = jQuery("#sectornameval").val();

	jQuery('#sector_id').val(sectorid);
	jQuery('#sector_name').val(sectorname);

	var url = "modules/Settings/Province/addprovince.php";

	var sector_id = jQuery('#sector_id').val();
	var sector_name = jQuery('#sector_name').val();
	var province_name = jQuery('#provincename').val();

	jQuery.ajax(url, {
		type: "POST",
		//data: data,
		data: {sectorid:sector_id,sectorname:sector_name,provincename:province_name},
		success: function (data) {
			var result = jQuery.parseJSON(data);
			if (result["status"] == false) {
				jQuery.messager.alert('Message','มีจังหวัดนี้อยู่แล้วในระบบ','info');
			}else{
				jQuery.messager.alert('Message','บันทึกรายการสำเร็จ','info');
				location.reload();
			}
        },
        error: function (data) {
            console.log("error");
        },
    });
}

function savedistrict() {

	var selectedprovince = jQuery("#provinceval").find('option:selected');
	var province_id = selectedprovince.data('provinceid');
	var province_name = jQuery("#provinceval").val();
	
	jQuery('#province_iddistrict').val(province_id);
	jQuery('#province_name').val(province_name);

	var url = "modules/Settings/Province/addDistrict.php";
	var data = jQuery("#form_save_district").serialize();
	console.log(data);
	jQuery.ajax(url, {
		type: "POST",
		data: data,
		success: function (data) {
			var result = jQuery.parseJSON(data);
			if (result["status"] == false) {
				jQuery.messager.alert('Message','มีอำเภอนี้อยู่แล้วในระบบ','info');
			}else{
				jQuery.messager.alert('Message','บันทึกรายการสำเร็จ','info');
				location.reload();
			}
        },
        error: function (data) {
            console.log("error");
        },
    });
}

function savesubdistrict() {
	var selectedamphur = jQuery("#amphur").find('option:selected');
	var amphurid = selectedamphur.data('amphurid');
	var nameamphur = jQuery("#amphur").val();
	
	jQuery('#amphur_id').val(amphurid);
	jQuery('#nameamphur').val(nameamphur);

	var url = "modules/Settings/Province/addsubdistrict.php";
	var data = jQuery("#form_save_subdistrict").serialize();

	jQuery.ajax(url, {
		type: "POST",
		data: data,
		success: function (data) {
			var result = jQuery.parseJSON(data);
			if (result["status"] == false) {
				jQuery.messager.alert('Message','มีตำบลนี้อยู่แล้วในระบบ','info');
			}else{
				jQuery.messager.alert('Message','บันทึกรายการสำเร็จ','info');
				location.reload();
			}			
        },
        error: function (data) {
            console.log("error");
        },
    });
}

function addPostcode() {
	var selecteddistrict = jQuery("#districtpostalcode").find('option:selected');
	var districtid = selecteddistrict.data('districtid');
	jQuery("#district_id").val(districtid);

	var url = "modules/Settings/Province/addPostcode.php";
	var data = jQuery("#form_save_postcode").serialize();

	jQuery.ajax(url, {
		type: "POST",
		data: data,
		success: function (data) {
			var result = jQuery.parseJSON(data);
			if (result["status"] == false) {
				jQuery.messager.alert('Message','มีรหัสไปรษณีย์นี้อยู่แล้วในระบบ','info');
			}else{
				jQuery.messager.alert('Message','บันทึกรายการสำเร็จ','info');
				location.reload();
			}	
        },
        error: function (data) {
            console.log("error");
        },
    });
}


</script>
{/literal}


