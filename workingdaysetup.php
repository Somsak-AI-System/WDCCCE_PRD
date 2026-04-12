
<?php
	header('Content-Type: text/html; charset=tis-620');
	include("config.inc.php");
    global $site_URL;
	$yaer = array();
	$def_year = 2022;
	$current_year = date('Y');
	$dif_year = ( $def_year-$current_year);

	for($i=$dif_year;$i<=3;$i++){
		$yaer[] = ($current_year+$i);
	}
	
?>
<style type="text/css">
	.pd-5{
		padding: 5px;
	}
	.txt-center{
		text-align: center;
	}
	.bg-blue{
		background-color: #deeaf6;
	}
	input[type=number]::-webkit-inner-spin-button {
	  -webkit-appearance: none;
	}
</style>

	<table width="98%" border="0" cellspacing="0" cellpadding="0" >
		<tr>
			<td width="50%" style="text-align: center"><h3 style="font-weight: 400;" class="text-uppercase text-black">Year</h3></td>
			<td width="50%"><!-- <input class='selectbox easyui-combogrid' id="year" name="year" style="width:100px;" data-options="required:true"> -->
				<input type="hidden" name="data_year" id="data_year" value="<?php echo $current_year; ?>">
				<select class="selectbox " name="year" id="year" label="State:" labelPosition="top" onchange="getselect(this);" style="width:100px;">
	                <?php
	                	foreach ($yaer as $key => $value) {
	                ?>		
	                	<option value="<?php echo $value; ?>" <?php if($current_year == $value){ echo 'selected'; } ?> ><?php echo $value; ?></option>
	                <?php
	                	}
	                ?>
	            </select>
			</td>
		</tr>
	</table>

	<table width="90%" border="1" cellspacing="0" cellpadding="0" style="margin-left: 15px;" >
		<tr>
			<td class="pd-5 txt-center bg-blue" width="50%"><label>Month</label></td>
			<td class="pd-5 txt-center bg-blue" width="50%"><label>Working Day</label></td>
		</tr>
		<tr>
			<td class="pd-5 txt-center" width="50%"><label>January</label></td>
			<td class="pd-5" width="50%"><input type="number" id="january" name="january" style="width:100px;"></td>
		</tr>
		<tr>
			<td class="pd-5 txt-center" width="50%"><label>February</label></td>
			<td class="pd-5" width="50%"><input type="number" id="february" name="february" style="width:100px;"></td>
		</tr>
		<tr>
			<td class="pd-5 txt-center" width="50%"><label>March</label></td>
			<td class="pd-5" width="50%"><input type="number" id="march" name="march" style="width:100px;"></td>
		</tr>
		<tr>
			<td class="pd-5 txt-center" width="50%"><label>April</label></td>
			<td class="pd-5" width="50%"><input type="number" id="april" name="april" style="width:100px;"></td>
		</tr>
		<tr>
			<td class="pd-5 txt-center" width="50%"><label>May</label></td>
			<td class="pd-5" width="50%"><input type="number" id="may" name="may" style="width:100px;"></td>
		</tr>
		<tr>
			<td class="pd-5 txt-center" width="50%"><label>June</label></td>
			<td class="pd-5" width="50%"><input type="number" id="june" name="june" style="width:100px;"></td>
		</tr>
		<tr>
			<td class="pd-5 txt-center" width="50%"><label>July</label></td>
			<td class="pd-5" width="50%"><input type="number" id="july" name="july" style="width:100px;"></td>
		</tr>
		<tr>
			<td class="pd-5 txt-center" width="50%"><label>August</label></td>
			<td class="pd-5" width="50%"><input type="number" id="august" name="august" style="width:100px;"></td>
		</tr>
		<tr>
			<td class="pd-5 txt-center" width="50%"><label>September</label></td>
			<td class="pd-5" width="50%"><input type="number" id="september" name="september" style="width:100px;"></td>
		</tr>
		<tr>
			<td class="pd-5 txt-center" width="50%"><label>October</label></td>
			<td class="pd-5" width="50%"><input type="number" id="october" name="october" style="width:100px;"></td>
		</tr>
		<tr>
			<td class="pd-5 txt-center" width="50%"><label>November</label></td>
			<td class="pd-5" width="50%"><input type="number" id="november" name="november" style="width:100px;"></td>
		</tr>
		<tr>
			<td class="pd-5 txt-center" width="50%"><label>December</label></td>
			<td class="pd-5" width="50%"><input type="number" id="december" name="december" style="width:100px;"></td>
		</tr>
		<tr>
			<td class="pd-5 txt-center bg-blue" width="50%"><label>Total</label></td>
			<td class="pd-5 txt-center bg-blue" width="50%"><span style="text-align: center;" id="total" name="total"></span></td>
		</tr>
	</table>
	<br>
	<table width="90%" border="0" cellspacing="0" cellpadding="0" style="margin-left: 15px;">
		<tr>
			<td class="submenu" style="padding-left:5px;">
                <button class="crmbutton small save" type="button" value="Save" style="width: 90%" onclick="save();">Save</button>
            </td>
            <td class="submenu" style="padding-left:5px;">
                <button class="crmbutton small cancel" type="button" value="Cancel" style="width: 90%" onclick="closedlg();">Cancel</button>
            </td>
		</tr>
	</table>
<script type="text/javascript">
jQuery(document).ready(function() {
	getvalue('<?php echo $current_year; ?>');
});

function getselect(data){
	jQuery('#data_year').val(data.value);
	getvalue(data.value);
}

function getvalue(year){
	var url ='<?php echo $site_URL;?>/Get_workingday.php';

	var form_data = {
	    year: year,
	    mode:'get'
	};
	
	jQuery.messager.progress({
		title:'Please waiting',
		msg:'Loading data...',
		text:'LOADING'
	});

	jQuery.ajax(url, {
		type: 'POST',
		dataType: 'json',
		data: form_data,
		success: function (result){
			
			if(result.type == 'S'){       
				jQuery('#january').val(result.data[0].january);
				jQuery('#february').val(result.data[0].february);
				jQuery('#march').val(result.data[0].march);
				jQuery('#april').val(result.data[0].april);
				jQuery('#may').val(result.data[0].may);
				jQuery('#june').val(result.data[0].june);
				jQuery('#july').val(result.data[0].july);
				jQuery('#august').val(result.data[0].august);
				jQuery('#september').val(result.data[0].september);
				jQuery('#october').val(result.data[0].october);
				jQuery('#november').val(result.data[0].november);
				jQuery('#december').val(result.data[0].december);
				jQuery('#total').html(result.data[0].total);		
			}else{
				jQuery('#january').val(0);
				jQuery('#february').val(0);
				jQuery('#march').val(0);
				jQuery('#april').val(0);
				jQuery('#may').val(0);
				jQuery('#june').val(0);
				jQuery('#july').val(0);
				jQuery('#august').val(0);
				jQuery('#september').val(0);
				jQuery('#october').val(0);
				jQuery('#november').val(0);
				jQuery('#december').val(0);
				jQuery('#total').html(0);
			}
			jQuery.messager.progress('close');
		},
		error: function (msg) {
			jQuery.messager.progress('close');
		}
	});

}

function save(){

	var year = jQuery('#data_year').val();
	var january = jQuery('#january').val();
	var february = jQuery('#february').val();
	var march = jQuery('#march').val();
	var april = jQuery('#april').val();
	var may = jQuery('#may').val();
	var june = jQuery('#june').val();
	var july = jQuery('#july').val();
	var august = jQuery('#august').val();
	var september = jQuery('#september').val();
	var october = jQuery('#october').val();
	var november = jQuery('#november').val();
	var december = jQuery('#december').val();

	var url ='<?php echo $site_URL;?>/Get_workingday.php';

	var form_data = {
	    mode:'save',
	    year: year,
	    january: january,
	    february: february,
	    march: march,
	    april: april,
	    may: may,
	    june: june,
	    july: july,
	    august: august,
	    september: september,
	    october: october,
	    november: november,
	    december: december
	};
	jQuery.messager.progress({
		title:'Please waiting',
		msg:'Loading data...',
		text:'LOADING'
	});
	jQuery.ajax(url, {
		type: 'POST',
		dataType: 'json',
		data: form_data,
		success: function (result){
			
			if(result.type == 'S'){       
				jQuery('#total').html(result.data[0].total);		
			}else{
				
				jQuery('#total').html(0);
			}
			jQuery.messager.progress('close');
		},
		error: function (msg) {
			jQuery.messager.progress('close');
		}
	});
}

function closedlg(){
	jQuery('#dialog_working').dialog("close");
}
</script>

