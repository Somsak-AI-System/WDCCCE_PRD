/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
jQuery(document).ready(function() {
	
		//Of Total Contract 
		jQuery('input[name="percent_contractsigning"]').change(function(e) {
			get_sum_contact();
			//get_sum_installment();
		});
		jQuery('input[name="payment"]').change(function(e) {
		  get_sum_contact();
		  get_sum_total();
		});
				
		jQuery('input[name="unit_size"]').change(function(e) {
			 get_sum_contact();
			
		});
		jQuery('input[name="pricesqm"]').change(function(e) {
			 get_sum_contact();
			
		});
		
		jQuery('input[name="percent_installmentamount"]').change(function(e) {
			 get_sum_installmentamount();
		
		});
				
		jQuery('input[name="installment"]').change(function(e) {
			 get_sum_months();
		});
		
		jQuery('input[name="percent_transfer"]').change(function(e) {
			 get_sum_tranfer();
		});
			
});

function get_sum_contact()
{

	if(jQuery( "input[name='percent_contractsigning']" ).val() == ''){
		var percent = 0;
	}else{
		var percent = parseFloat(jQuery('input[name="percent_contractsigning"]').val());
	}
	if(jQuery( "input[name='payment']" ).val() == ''){
		var payment = 0;
	}else{
		var payment = jQuery('input[name="payment"]').val();
	}
	if(jQuery( "input[name='pricesqm']" ).val() == ''){
		var pricesqm = 0;
	}else{
		var pricesqm = jQuery('input[name="pricesqm"]').val();
	}
	if(jQuery( "input[name='unit_size']" ).val() == ''){
		var unit_size = 0;
	}else{
		var unit_size = jQuery('input[name="unit_size"]').val();
	}
	//console.log(pricesqm+"/"+unit_size+"/"+percent);

	total_contract =  ( ((percent * (pricesqm*unit_size))/ 100) - payment );
	jQuery('input[name="total_contract"]').val(total_contract.toFixed(2));
	
	get_sum_installmentamount();
	get_sum_total();
	
}

function  get_sum_installmentamount(){

	if(jQuery( "input[name='percent_installmentamount']" ).val() == ''){
		var installmentamount = 0;
	}else{
		var installmentamount = parseFloat(jQuery('input[name="percent_installmentamount"]').val());
	}
	if(jQuery( "input[name='pricesqm']" ).val() == ''){
		var pricesqm = 0;
	}else{
		var pricesqm = jQuery('input[name="pricesqm"]').val();
	}
	if(jQuery( "input[name='unit_size']" ).val() == ''){
		var unit_size = 0;
	}else{
		var unit_size = jQuery('input[name="unit_size"]').val();
	}

	
	total_installment = ((installmentamount * (pricesqm*unit_size))/ 100);
	jQuery('input[name="total_installment"]').val(total_installment.toFixed(2));

	get_sum_months();
	get_sum_total();
}

function get_sum_months(){
	
	if(jQuery( "input[name='total_installment']").val() == ''){
		var Sum_installment = 0;
	}else{
		var Sum_installment = parseFloat(jQuery('input[name="total_installment"]').val());
	}
	if(jQuery( "input[name='installment']").val() == ''){
		var installment = 0;
	}else{
		var installment = jQuery('input[name="installment"]').val();
	}
	
	if(  installment != 0 && Sum_installment != 0){ 
		installment_months = (Sum_installment / installment);
	}else{
		installment_months = 0;
	}
	jQuery('input[name="installment_months"]').val(installment_months.toFixed(2));
	
	get_sum_tranfer();
	get_sum_total();
	
}

function get_sum_tranfer(){
	
	if(jQuery( "input[name='percent_transfer']").val() == ''){
		var transfer = 0;
	}else{
		var transfer = parseFloat(jQuery('input[name="percent_transfer"]').val());
	}
	
	if(jQuery( "input[name='pricesqm']" ).val() == ''){
		var pricesqm = 0;
	}else{
		var pricesqm = jQuery('input[name="pricesqm"]').val();
	}
	if(jQuery( "input[name='unit_size']" ).val() == ''){
		var unit_size = 0;
	}else{
		var unit_size = jQuery('input[name="unit_size"]').val();
	}
	
	total_transfer =  ((transfer*(pricesqm*unit_size))/100 );
	jQuery('input[name="total_transfer"]').val(total_transfer.toFixed(2));
	
	get_sum_total();
}

function get_sum_total(){

	if(jQuery( "input[name='total_contract']" ).val() == ''){
		var total_contract = 0;
	}else{
		var total_contract = parseFloat(jQuery('input[name="total_contract"]').val());
	}
	
	if(jQuery( "input[name='total_installment']" ).val() == ''){
		var total_installment = 0;
	}else{
		var total_installment = parseFloat(jQuery('input[name="total_installment"]').val());
	}
	
	if(jQuery( "input[name='total_transfer']" ).val() == ''){
		var total_transfer = 0;
	}else{
		var total_transfer = parseFloat(jQuery('input[name="total_transfer"]').val());
	}
	
	if(jQuery( "input[name='payment']" ).val() == ''){
		var payment = 0;
	}else{
		var payment = parseFloat(jQuery('input[name="payment"]').val());
	}

	total =  ( total_contract+payment+total_installment+total_transfer);
	jQuery('input[name="total"]').val(total.toFixed(2));
}


	
