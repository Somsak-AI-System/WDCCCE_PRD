/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
// jQuery(document).ready(function() {
	
// 		//Advance fee amount
// 		jQuery('input[name="mafee"]').change(function(e) {
// 			get_sum_Advance();
// 		});
// 		jQuery('input[name="mafee_month"]').change(function(e) {
// 		  get_sum_Advance();
// 		});
// 		jQuery('input[name="unit_size"]').change(function(e) {
// 		  get_sum_Advance();
// 		  get_sum_Transfer();
// 		  get_sum_price()
// 		});
// 		jQuery('input[name="transferfee"]').change(function(e) {
// 		  get_sum_Transfer();
// 		});
		
// 		jQuery('input[name="pricesqm"]').change(function(e) {
// 		  get_sum_price();
// 		});
		
// 		jQuery('input[name="cf_3142"]').change(function(e) {
// 		  get_sum_Discount();
// 		});
		
		
// 		jQuery('input[name="cf_3143"]').change(function(e) {
// 		  clear_cf_3142();
// 		});

// 		jQuery("#unit_price").on("input",function() {
// 			var unitprice = jQuery('#unit_price').val();
// 			var vat = ((unitprice*107)/100);
// 				vat = isNaN(vat = parseFloat(vat)) ? 0: parseFloat(vat);
// 			jQuery('#pro_priceinclude').val(formatNumber(vat.toFixed(2)));
// 		});

// });

function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}
function clear_cf_3142()
{
	jQuery('input[name="cf_3142"]').val('');
	
	if(jQuery( "input[name='cf_3143']" ).val() == ''){
		var cf_3143 = 0;
	}else{
		var cf_3143 = parseFloat(jQuery('input[name="cf_3143"').val());
	}
	if(jQuery( "input[name='unit_price']" ).val() == ''){
		var unit_price = 0;
	}else{
		var unit_price = parseFloat(jQuery('input[name="unit_price"').val());
	}
	
	totalprice = (unit_price-cf_3143);
	jQuery('input[name="cf_3362"]').val(totalprice.toFixed(2));
	
	
}

function get_sum_Discount()
{
	if(jQuery( "input[name='unit_price']" ).val() == ''){
		var unit_price = 0;
	}else{
		var unit_price = parseFloat(jQuery('input[name="unit_price"').val());
	}
	if(jQuery( "input[name='cf_3142']" ).val() == ''){
		var cf_3142 = 0;
	}else{
		var cf_3142 = parseFloat(jQuery('input[name="cf_3142"').val());
	}
	
	Discountper = ((unit_price*cf_3142)/100);
	jQuery('input[name="cf_3143"]').val(Discountper.toFixed(2));
	
	get_totalPrice();
	
}
function get_totalPrice()
{
	if(jQuery( "input[name='unit_price']" ).val() == ''){
		var unit_price = 0;
	}else{
		var unit_price = parseFloat(jQuery('input[name="unit_price"').val());
	}
	if(jQuery( "input[name='cf_3143']" ).val() == ''){
		var cf_3143 = 0;
	}else{
		var cf_3143 = parseFloat(jQuery('input[name="cf_3143"').val());
	}
	
	totalPrice = (unit_price - cf_3143);
	jQuery('input[name="cf_3362"]').val(totalPrice.toFixed(2));
	
}
function get_sum_Advance()
{
	var mafee = 0;
	if(jQuery( "input[name='mafee']" ).val() == ''){
		var mafee = 0;
	}else{
		var mafee = parseFloat(jQuery('input[name="mafee"').val());
	}
	
	if(jQuery( "input[name='mafee_month']" ).val() == ''){
		var mafee_month = 0;
	}else{
		var mafee_month = parseFloat(jQuery('input[name="mafee_month"').val());
	}
	if(jQuery( "input[name='unit_size']" ).val() == ''){
		var unit_size = 0;
	}else{
		var unit_size = parseFloat(jQuery('input[name="unit_size"').val());
	}
	
	mafee_total =  parseFloat(mafee*mafee_month*unit_size);
	jQuery('input[name="mafee_total"]').val(mafee_total.toFixed(2));
	
}

function get_sum_Transfer()
{
	
	if(jQuery( "input[name='transferfee']" ).val() == ''){
		var transferfee = 0;
	}else{
		var transferfee = parseFloat(jQuery('input[name="transferfee"').val());
	}
	if(jQuery( "input[name='unit_size']" ).val() == ''){
		var unit_size = 0;
	}else{
		var unit_size = parseFloat(jQuery('input[name="unit_size"').val());
	}
	
	transfer_total = parseFloat(transferfee*unit_size);
	jQuery('input[name="transfer_total"]').val(transfer_total.toFixed(2));
	
}


function get_sum_price()
{
	
	if(jQuery( "input[name='pricesqm']" ).val() == ''){
		var pricesqm = 0;
	}else{
		var pricesqm = parseFloat(jQuery('input[name="pricesqm"').val());
	}
	if(jQuery( "input[name='unit_size']" ).val() == ''){
		var unit_size = 0;
	}else{
		var unit_size = parseFloat(jQuery('input[name="unit_size"').val());
	}
	
	unit_price = parseFloat(unit_size*pricesqm);
	jQuery('input[name="unit_price"]').val(unit_price.toFixed(2));
	
}





