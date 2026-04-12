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

 <script language="JavaScript" type="text/javascript">
{literal}
	 jQuery.fn.extend({
		insertAtCaret: function(myValue){
		  return this.each(function(i) {
			if (document.selection) {
			  //For browsers like Internet Explorer
			  this.focus();
			  var sel = document.selection.createRange();
			  sel.text = myValue;
			  this.focus();
			}
			else if (this.selectionStart || this.selectionStart == '0') {
			  //For browsers like Firefox and Webkit based
			  var startPos = this.selectionStart;
			  var endPos = this.selectionEnd;
			  var scrollTop = this.scrollTop;
			  this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
			  this.focus();
			  this.selectionStart = startPos + myValue.length;
			  this.selectionEnd = startPos + myValue.length;
			  this.scrollTop = scrollTop;
			} else {
			  this.value += myValue;
			  this.focus();
			}
		  });
		}
	});
{/literal}
	 var allOptions = null;
  	 function setvalue_testarray(field,value){ldelim}
		if(value !== '--None--'){ldelim}
			if(field == 'sms_message' ){ldelim}
				jQuery('textarea#'+field).insertAtCaret(value);
				calculator();
			{rdelim}
			if (field == 'email_message'){ldelim}
				CKEDITOR.instances[field].insertText(value);
			{rdelim}
		{rdelim}
	 {rdelim}
     function setAllOptions(inputOptions)
     {ldelim}
         allOptions = inputOptions;
     {rdelim}
    function modifyMergeFieldSelect(cause, effect , field)
    {ldelim}
        var selected = cause.options[cause.selectedIndex].value;  id=field
        var s = allOptions[cause.selectedIndex];

        effect.length = s;
        for (var i = 0; i < s; i++)
        {ldelim}
            effect.options[i] = s[i];
            {rdelim}
        //document.getElementById('sms_message').value = '';
        {rdelim}
{literal}

     function init()
     {
         var blankOption = new Option('--None--', '--None--');
         var options = null;
 		{/literal}

                 var allOpts = new Object({$ALL_VARIABLES|@count}+1);
                 {assign var="alloptioncount" value="0"}
                 {foreach key=index item=module from=$ALL_VARIABLES}
                 options = new Object({$module|@count}+1);
                 {assign var="optioncount" value="0"}
             options[{$optioncount}] = blankOption;
             		{foreach key=header item=detail from=$module}
              			{assign var="optioncount" value=$optioncount+1}
                        options[{$optioncount}] = new Option('{$detail.0}', '{$detail.1}');
                    {/foreach}
                {assign var="alloptioncount" value=$alloptioncount+1}
              allOpts[{$alloptioncount}] = options;
             {/foreach}
         setAllOptions(allOpts);
     }
{literal}


{/literal}
</script>

		{assign var="uitype" value="$maindata[0][0]"}
		{assign var="fldlabel" value="$maindata[1][0]"}
		{assign var="fldlabel_sel" value="$maindata[1][1]"}
		{assign var="fldlabel_combo" value="$maindata[1][2]"}
		{assign var="fldlabel_other" value="$maindata[1][3]"}
		{assign var="fldname" value="$maindata[2][0]"}
		{assign var="fldvalue" value="$maindata[3][0]"}
		{assign var="secondvalue" value="$maindata[3][1]"}
		{assign var="thirdvalue" value="$maindata[3][2]"}
		{assign var="typeofdata" value="$maindata[4]"}
	 	{assign var="vt_tab" value="$maindata[5][0]"}

		{if $typeofdata eq 'M'}
			{assign var="mandatory_field" value="*"}
		{else}
			{assign var="mandatory_field" value=""}
		{/if}

		{* vtlib customization: Help information for the fields *}
		{assign var="usefldlabel" value=$fldlabel}
		{assign var="fldhelplink" value=""}
		{if $FIELDHELPINFO && $FIELDHELPINFO.$fldname}
			{assign var="fldhelplinkimg" value='help_icon.gif'|@aicrm_imageurl:$THEME}
			{assign var="fldhelplink" value="<img style='cursor:pointer' onclick='vtlib_field_help_show(this, \"$fldname\");' border=0 src='$fldhelplinkimg'>"}
			{if $uitype neq '10'}
				{assign var="usefldlabel" value="$fldlabel $fldhelplink"}
			{/if}
		{/if}
		{* END *}

		{* vtlib customization *}
		{if $uitype eq '10'}
			<td width=20% class="dvtCellLabel" align=right>
			<font color="red">{$mandatory_field}</font>
			{$fldlabel.displaylabel}

			{if count($fldlabel.options) eq 1}
				{assign var="use_parentmodule" value=$fldlabel.options.0}
				<input type='hidden' class='small' name="{$fldname}_type" value="{$use_parentmodule}">{$APP.$use_parentmodule}
			{else}
			<br>
			{if $fromlink eq 'qcreate'}
			<select id="{$fldname}_type" class="small" name="{$fldname}_type" onChange='document.QcEditView.{$fldname}_display.value=""; document.QcEditView.{$fldname}.value="";'>
			{else}
			<select id="{$fldname}_type" class="small" name="{$fldname}_type" onChange='document.EditView.{$fldname}_display.value=""; document.EditView.{$fldname}.value="";$("qcform").innerHTML=""'>
			{/if}
			{foreach item=option from=$fldlabel.options}
				<option value="{$option}"
				{if $fldlabel.selected == $option}selected{/if}>
				{if $APP.$option neq ''}{$APP.$option}{else}{$option}{/if}
				</option>
			{/foreach}
			</select>
			{/if}
			{if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			{$fldhelplink}

			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input id="{$fldname}" name="{$fldname}" type="hidden" value="{$fldvalue.entityid}" id="{$fldname}">
				<input id="{$fldname}_display" name="{$fldname}_display" id="edit_{$fldname}_display" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue.displayvalue}">&nbsp;

				{if $fromlink eq 'qcreate'}
				<img src="{'select.gif'|@aicrm_imageurl:$THEME}" tabindex="{$vt_tab}"
alt="Select" title="Select" LANGUAGE=javascript  onclick='return window.open("index.php?module="+ document.QcEditView.{$fldname}_type.value +"&action=Popup&html=Popup_picker&form=vtlibPopupView&forfield={$fldname}&srcmodule={$MODULE}&forrecord={$ID}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer; vertical-align: middle;'>&nbsp;
				{else}
				<img src="{'select.gif'|@aicrm_imageurl:$THEME}" tabindex="{$vt_tab}"
alt="Select" title="Select" LANGUAGE=javascript  onclick='return window.open("index.php?module="+ document.EditView.{$fldname}_type.value +"&action=Popup&html=Popup_picker&form=vtlibPopupView&forfield={$fldname}&srcmodule={$MODULE}&forrecord={$ID}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer; vertical-align: middle;'>&nbsp;
				{/if}

				<input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}"
alt="Clear" title="Clear" LANGUAGE=javascript	onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_display.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
			</td>
		{* END *}

		{elseif $uitype eq 2}
			<td width=20% class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small">{/if}
			</td>
			<td width=30% align=left class="dvtCellInfo">
            	{if $MODULE eq 'Accounts'}
				<input type="text" name="{$fldname}" id="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC">
                {else}
				<input type="text" name="{$fldname}" id="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
                {/if}
			</td>

		{elseif $uitype eq 3 || $uitype eq 4}<!-- Non Editable field, only configured value will be loaded -->
				<td width=20% class="dvtCellLabel" align=right><font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small">{/if}</td>
                <td width=30% align=left class="dvtCellInfo"><input readonly type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" {if $MODE eq 'edit'} value="{$fldvalue}" {else} value="{$MOD_SEQ_ID}" {/if} class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>

		{elseif $uitype eq 11 || $uitype eq 1 || $uitype eq 13 || $uitype eq 7 || $uitype eq 9}

		   	{if $fldname neq 'discount_coupon'}
				<td width=20% class="dvtCellLabel" align=right><font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}</td>
			{/if}	
          
			{if $fldname eq 'tickersymbol' && $MODULE eq 'Accounts'}
				<td width=30% align=left class="dvtCellInfo">
					<input type="text" name="{$fldname}" tabindex="{$vt_tab}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn';" onBlur="this.className='detailedViewTextBox';{if $fldname eq 'tickersymbol' && $MODULE eq 'Accounts'}sensex_info(){/if}">
					<span id="vtbusy_info" style="display:none;">
						<img src="{'vtbusy.gif'|@aicrm_imageurl:$THEME}" border="0"></span>
				</td>

            {elseif $fldname eq 'postal_code' && $MODULE eq 'Accounts'}
				<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC"></td>

			{elseif $fldname eq 'discount_coupon' && $MODULE eq 'Quotes'}
				<input type="hidden" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}">

			{elseif $fldname eq 'province' || $fldname eq 'district' || $fldname eq 'mafee_total' || $fldname eq 'transfer_total' || $fldname eq 'total_contract' || $fldname eq 'document' ||  $fldname eq 'account_cpprovince' || $fldname eq 'account_cpdistrict' || $fldname eq 'productcode' || $fldname eq 'email_bounce' || $fldname eq 'email_server' || ($fldname eq 'location' && $MODULE eq 'Calendar') || ($fldname eq 'location_chkout' && $MODULE eq 'Calendar') || $fldname eq 'lead_district' || $fldname eq 'lead_province' || $fldname eq 'checkoutdate'|| $fldname eq 'checkindate'  || $fldname eq 'quotes_district' ||  $fldname eq 'district_shipping' || $fldname eq 'quote_no_rev' || $fldname eq 'rev_no' || $fldname eq 'pro_priceinclude' || $fldname eq 'user_signature_time' || $fldname eq 'customer_signature_time' || $fldname eq 'cf_4749' || $fldname eq 'sale_checkin'  || $fldname eq 'sale_checkoutlocation'  || $fldname eq 'sale_checkindate'  || $fldname eq 'sale_checkoutdate' || $fldname eq 'billingdistrict' || $fldname eq 'billingprovince' || $fldname eq 'shippingdistrict' || $fldname eq 'shippingprovince' || $fldname eq 'leadname' || $fldname eq 'contactname' || $fldname eq 'province_shipping' || $fldname eq 'quotes_province' || $fldname eq 'ref_service_request_no' || $fldname eq 'grouping' || $fldname eq 'business_partner' || $fldname eq 'customer_name' || $fldname eq 'search_term_1_2' || $fldname eq 'streets' || $fldname eq 'street_4' || $fldname eq 'street_5' || $fldname eq 'districts' || $fldname eq 'city' || $fldname eq 'countrys' || $fldname eq 'tax_id' || $fldname eq 'contact_person' || $fldname eq 'telephone' || $fldname eq 'fax' || $fldname eq 'e_mail' || $fldname eq 'sales_organization' || $fldname eq 'distribution_channel' || $fldname eq 'cust_pric_procedure' || ($fldname eq 'customer_group' && $MODULE neq 'Competitor') || $fldname eq 'sales_district' || $fldname eq 'currency' || $fldname eq 'shipping_condition' || $fldname eq 'payment_terms' || $fldname eq 'sales_group' || $fldname eq 'sg_description' || $fldname eq 'sales_office' || $fldname eq 'customer_group_1' || $fldname eq 'cg1_description' || $fldname eq 'agent_payer_1' || $fldname eq 'acct_clerks_tel_no' || $fldname eq 'material' || $fldname eq 'base_unit_of_measure' || $fldname eq 'um_coversion_m2_pcs' || $fldname eq 'description_en' || $fldname eq 'description_th' || $fldname eq 'status' || $fldname eq 'desc_status' || $fldname eq 'valuation_class' || $fldname eq 'valuation_class_description' || $fldname eq 'material_group' || $fldname eq 'mat_group' || $fldname eq 'plant' || $fldname eq 'sales_org' || $fldname eq 'channel' || $fldname eq 'mat_price_grp' || $fldname eq 'mat_price_grp_desc' || $fldname eq 'mat_gp1' || $fldname eq 'mat_gp1_desciption' || $fldname eq 'mat_gp2' || $fldname eq 'mat_gp2_desciption' || $fldname eq 'mat_gp3' || $fldname eq 'mat_gp3_desciption' || $fldname eq 'mat_gp4' || $fldname eq 'mat_gp4_desciption' || $fldname eq 'mat_gp5' || $fldname eq 'mat_gp5_desciption' || $fldname eq 'country' || $fldname eq 'country_of_origin' || $fldname eq 'list_price' || $fldname eq 'piece_per_carton' || $fldname eq 'squaremeters_per_carton' || $fldname eq 'price_per_piece' || $fldname eq 'price_per_squaremeter' || $fldname eq 'quantity' || $fldname eq 'quantity_sheet' || $fldname eq 'revised_no' || $fldname eq 'ref_sample_request' || $fldname eq 'project_name' || $fldname eq 'erp_product_name' || $fldname eq 'erp_product_selling_stock_01_01' || $fldname eq 'erp_product_defects_stock_01_04' || $fldname eq 'erp_product_defects_stock_01_05' || $fldname eq 'erp_product_defects_stock_01_07' || $fldname eq 'erp_product_booking_stock_01_08' || $fldname eq 'erp_product_selling_stock_01_09' || $fldname eq 'erp_product_selling_stock_02_01' || $fldname eq 'erp_product_defects_stock_02_04' || $fldname eq 'erp_product_selling_stock_03_01' || $fldname eq 'erp_product_defects_stock_03_04' || $fldname eq 'erp_product_selling_stock_04_01' || $fldname eq 'erp_product_defects_stock_05_04' || $fldname eq 'productname' ||  $fldname eq 'erp_response_status' || $fldname eq 'reference_id' || $fldname eq 'projects_reference_id'
}

				<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC"></td>

			{elseif ($fldname eq 'cf_25708' || $fldname eq 'phone' || $fldname eq 'email') && $MODULE eq 'Calendar' && $MODE eq 'edit' && $flag_send_report eq 1}
				<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC"></td>

				
			{elseif $fldname eq 'billingsubdistrict'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; ">&nbsp;<img src="{'addrss.gif'|@aicrm_imageurl:$THEME}" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region&f2=billingprovince&f3=billingdistrict&f4=billingsubdistrict&f5=billingpostalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>

			{elseif $fldname eq 'shippingsubdistrict'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; ">&nbsp;<img src="{'addrss.gif'|@aicrm_imageurl:$THEME}" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region&f2=shippingprovince&f3=shippingdistrict&f4=shippingsubdistrict&f5=shippingpostalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>

            {elseif $fldname eq 'sms_character'  || $fldname eq 'sms_credit'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; width:50px"></td>

            {elseif $fldname eq 'prepare_status' || $fldname eq 'email_status' || $fldname eq 'send_status' || $fldname eq 'email_start_date' || $fldname eq 'email_start_time' || $fldname eq 'prepare_date' || $fldname eq 'prepare_time' || $fldname eq 'send_date' || $fldname eq 'send_time' }
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC"></td>

            {elseif $fldname eq 'sub_district' && $MODULE eq 'Quotes'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC;">&nbsp;<img src="{'addrss.gif'|@aicrm_imageurl:$THEME}" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region_shipping&f2=province&f3=district&f4=sub_district&f5=postal_code","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer;"></td>

            {elseif $fldname eq 'quotes_subdistrict' && $MODULE eq 'Quotes'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; " >&nbsp;<img src="{'addrss.gif'|@aicrm_imageurl:$THEME}" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=quotes_region&f2=quotes_province&f3=quotes_district&f4=quotes_subdistrict&f5=quotes_postcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer;"></td>

            {elseif $fldname eq 'subdistrict_shipping' && $MODULE eq 'Quotes'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; " >&nbsp;<img src="{'addrss.gif'|@aicrm_imageurl:$THEME}" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=quotes_region&f2=province_shipping&f3=district_shipping&f4=subdistrict_shipping&f5=postcode_shipping","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer;"></td>

            {elseif $fldname eq 'sub_district' && $MODULE eq 'Servicerequest'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC;">&nbsp;<img src="{'addrss.gif'|@aicrm_imageurl:$THEME}" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region_shipping&f2=province&f3=district&f4=sub_district&f5=postal_code","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer;"></td>

            {elseif $fldname eq 'sub_district' && $MODULE eq 'Salesorder'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC;">&nbsp;<img src="{'addrss.gif'|@aicrm_imageurl:$THEME}" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region_shipping&f2=province&f3=district&f4=sub_district&f5=postal_code","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer;"></td>

            {elseif $fldname eq 'sub_district_shipping' && $MODULE eq 'Salesorder'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC;">&nbsp;<img src="{'addrss.gif'|@aicrm_imageurl:$THEME}" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region_shipping&f2=province_shipping&f3=district_shipping&f4=sub_district_shipping&f5=postal_code_shipping","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer;"></td>

            {elseif $fldname eq 'region'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; " >&nbsp;<img src="{'addrss.gif'|@aicrm_imageurl:$THEME}" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region&f2=province&f3=district&f4=subdistrict&f5=postalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>

            {elseif $fldname eq 'lead_subdistrinct'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; " >&nbsp;<img src="{'addrss.gif'|@aicrm_imageurl:$THEME}" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region&f2=lead_province&f3=lead_district&f4=lead_subdistrinct&f5=lead_postalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>

            {elseif $fldname eq 'subdistrict'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; ">&nbsp;<img src="{'addrss.gif'|@aicrm_imageurl:$THEME}" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=region&f2=province&f3=district&f4=subdistrict&f5=postalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>

            {elseif $fldname eq 'account_cpsubdistrict'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC; ">&nbsp;<img src="{'addrss.gif'|@aicrm_imageurl:$THEME}" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=account_cpregion&f2=account_cpprovince&f3=account_cpdistrict&f4=account_cpsubdistrict&f5=account_cppostalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>

            {elseif $fldname eq 'working_region'}
            	<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color: #CCC;">&nbsp;<img src="{'addrss.gif'|@aicrm_imageurl:$THEME}" border="0" onclick='javascript:window.open("Redemption/Click_Address.php?f1=working_region&f2=working_province&f3=working_district&f4=working_subdistrict&f5=working_postalcode","productWin","width=1000,height=190,resizable=0,scrollbars=0,status=1,top=300,left=300")' style="cursor:pointer; vertical-align: middle;"></td>
			
		    {else}
				<td width=30% align=left class="dvtCellInfo">
                {if ($fldname eq 'firstname' || $fldname eq 'middlename' || $fldname eq 'lastname' || $fldname eq 'f_name_en' || $fldname eq 'l_name_en') && ($MODULE eq 'Accounts') }
                	<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_account_name();">

                {elseif ($fldname eq 'firstname' || $fldname eq 'middlename' || $fldname eq 'lastname') && ($MODULE eq 'Leads') }
                	<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_lead_name();">

                {elseif ($fldname eq 'firstname' || $fldname eq 'middlename' || $fldname eq 'lastname') && ($MODULE eq 'Contacts') }
                	<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_contact_name();">

				{elseif $fldname eq 'product_code_crm' && $MODULE eq 'Products' }
                	<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_product_name();">

				{elseif ($fldname eq 'branch_code') && $MODULE eq 'Accounts'}
           			<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onkeyup="validateBranchCode(this)">

				{elseif ($fldname eq 'claim_from' || $fldname eq 'sale_channel') && $MODULE eq 'Claim'}
           			<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" style="background-color:#dfe8f1" readonly onBlur="this.className='detailedViewTextBox'">

                {else}

                	{if $uitype eq 7}
                		<div class="hide-inputbtns">
                			{if $fldname eq 'number_of_vouchers' || $fldname eq 'vouchers_of_used' || $fldname eq 'vouchers_of_remaining' || $fldname eq 'voucher_amount' || $fldname eq 'voucher_used' || $fldname eq 'voucher_available' || $fldname eq 'generate'}
                				<input name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" type="text" step= "0.000001" class="form-control detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value='{$fldvalue|number_format:0}' readonly="readonly" style="background-color: #CCC;">
							{else}
								<input name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" type="number" step="0.00000000001" class="form-control detailedViewTextBox number" onFocus="this.className='detailedViewTextBox'" onBlur="this.className='detailedViewTextBox'" value="{$fldvalue}" >
							{/if}
						</div>
					{elseif $uitype eq 11}

						<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" maxlength="12" onkeypress="return isPhoneNumber(event)">

                	{else}
                		<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
                	{/if}
                {/if}
                </td>
			{/if}

		{elseif $uitype eq 19 || $uitype eq 20}
			<!-- In Add Comment are we should not display anything -->
			{if $fldlabel eq $MOD.LBL_ADD_COMMENT || $fldlabel eq 'Comment Plan' || $fldname eq "comments" || $fldname eq "history_status"}
				{assign var=fldvalue value=""}
			{/if}
			<td width=20% class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>
				{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td colspan=3>
                {if $fldname eq "email_message" || $fldname eq "sms_message"}
                 <table style="margin-left:10px;">
                    <tr>
                         <td>Module</td>
                         <td></td>
                         <td style="border-left:2px dotted #cccccc;">Column</td>
                         <td></td>
                         <td></td>
                    </tr>
                    <tr>
                        <td>
                         <select style="font-family: Arial, Helvetica, sans-serif;font-size: 11px;color: #000000;border:1px solid #bababa;padding-left:5px;background-color:#ffffff;" id="entityType" class="small"
                         ONCHANGE="modifyMergeFieldSelect(this, document.getElementById('mergeFieldSelect'),{$fldname});" tabindex="6">
                         <OPTION VALUE="0" selected>{$APP.LBL_NONE}
                         <OPTION VALUE="1">{$UMOD.LBL_ACCOUNT_FIELDS}
                         {if $MODULE neq 'Smartquestionnaire'}
                         	<OPTION VALUE="2">{$UMOD.LBL_CONTACT_FIELDS}
                         	{if $MODULE neq 'Surveysmartemail'}
                         		<OPTION VALUE="3" >{$UMOD.LBL_LEAD_FIELDS}
                         	{/if}
                         <!-- <OPTION VALUE="4" >{$UMOD.LBL_OPPORTUNTY_FIELDS} -->
                         {/if}
                         </select>
                        <td>
                        <td style="border-left:2px dotted #cccccc;">
                            <select style="font-family: Arial, Helvetica, sans-serif;font-size: 11px;color: #000000;border:1px solid #bababa;padding-left:5px;background-color:#ffffff;" class="small" id="mergeFieldSelect" onchange="setvalue_testarray('{$fldname}',this.options[this.selectedIndex].value);" tabindex="7"><option value="0" selected>{$APP.LBL_NONE}</select>
                        <td>
                    </tr>
                 </table>
                 {/if}

               
					{if $MODULE eq 'Samplerequisition' && ($fldname eq 'rejected_reason' || $fldname eq 'cancel_reason')}
                		<textarea class="detailedViewTextBox" tabindex="{$vt_tab}" onFocus="this.className='detailedViewTextBoxOn'" name="{$fldname}"  id="{$fldname}"  onBlur="this.className='detailedViewTextBox'" cols="90" rows="8" readonly="readonly" style="background-color:#CCC">{$fldvalue}</textarea>
                	{else}
                		<textarea class="detailedViewTextBox" tabindex="{$vt_tab}" onFocus="this.className='detailedViewTextBoxOn'" name="{$fldname}"  id="{$fldname}"  onBlur="this.className='detailedViewTextBox'" cols="90" rows="8">{$fldvalue}</textarea>
                	{/if}

                

				{if $fldlabel eq $MOD.Solution}
					<input type = "hidden" name="helpdesk_solution" value = '{$fldvalue}'>
				{/if}
			</td>

		{elseif $uitype eq 70 }

			{if $fldname eq "user_signature_time" || $fldname eq "customer_signature_time" }
				<td width=20% class="dvtCellLabel" align=right>
					<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}
					<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small">{/if}
				</td>
                <td width=30% align=left class="dvtCellInfo">
                	<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="background-color:#CCC">
               	</td>
            {/if}

		{elseif $uitype eq 21 || $uitype eq 24}
			{if $fldname eq "erp_product_description"}
				<td width=20% class="dvtCellLabel" align=right>
					<font color="red">{$mandatory_field}</font>
					{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
				</td>
				<td width=30% align=left class="dvtCellInfo" valign="top" style="padding:2px">
					<textarea value="{$fldvalue}" name="{$fldname}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" rows=2  readonly="readonly" style="background-color:#CCC">{$fldvalue}</textarea>
				</td>
			{else}
				<td width=20% class="dvtCellLabel" align=right>
					<font color="red">{$mandatory_field}</font>
					{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
				</td>
				<td width=30% align=left class="dvtCellInfo" valign="top" style="padding:2px">
					<textarea value="{$fldvalue}" name="{$fldname}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" rows=2>{$fldvalue}</textarea>
				</td>
			{/if}

		{elseif $uitype eq 15 || $uitype eq 16}

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>
				{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>

			<td width="30%" align=left class="dvtCellInfo">

            	{if $MODULE eq 'Calendar'}
            		{if $fldname eq 'activitytype' && $MODE eq 'edit'}
                    	<select name="{$fldname}" tabindex="{$vt_tab}" class="small" style="width:160px;;background-color: #CCC" />
            		{else}
            			<select name="{$fldname}" tabindex="{$vt_tab}" class="small" style="width:160px;" />
            		{/if}

                {elseif $MODULE eq 'Accounts' && $fldname eq "approved_status" }
                    <select name="{$fldname}" tabindex="{$vt_tab}" class="small" style="width:160px;background-color: #CCC" />
                {elseif $MODULE eq 'Purchasesorder' && $fldname eq "vender" }
                	<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="small" onchange="get_valuevender();" />
				{elseif $MODULE eq 'Goodsreceive' && $fldname eq "vender" }
                	<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="small" onchange="get_valuevender();" />
                {elseif $MODULE eq 'Purchasesorder' && $fldname eq "buyer" }
                	<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="small" onchange="get_valuebuyer();" />
                {elseif $MODULE eq 'Purchasesorder' && $fldname eq "payment_method" }
                	<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="small"/>
                {elseif $MODULE eq 'Purchasesorder' && $fldname eq "payment_term" }
                	<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="small"/>
                {else}
                    <select name="{$fldname}" tabindex="{$vt_tab}" class="small" >
                {/if}

                {if $fldname eq "quotation_status"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                             <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                             <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="">{$APP.LBL_NONE}</option>
                    {/foreach}
                {elseif $fldname eq "sms_status"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                             <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                             <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="">{$APP.LBL_NONE}</option>
                    {/foreach}
                {elseif $fldname eq "email_status"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                             <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                             <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="">{$APP.LBL_NONE}</option>
                    {/foreach}
                {elseif $fldname eq "smartquestionnaire_status"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                             <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                             <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="">{$APP.LBL_NONE}</option>
                    {/foreach}
                {elseif $fldname eq "announcement_status"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                             <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                             <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="">{$APP.LBL_NONE}</option>
                    {/foreach}                       
                {elseif $fldname eq "salesorder_status"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                             <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                             <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="">{$APP.LBL_NONE}</option>
                    {/foreach}
                {elseif $fldname eq "service_request_status"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                             <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                             <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="">{$APP.LBL_NONE}</option>
                    {/foreach}
                {elseif $fldname eq "job_status"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                             <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                             <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="">{$APP.LBL_NONE}</option>
                    {/foreach}                    
                {elseif $fldname eq "stage"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                             <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                             <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="">{$APP.LBL_NONE}</option>
                    {/foreach}
                {elseif $fldname eq "order_status_order"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                             <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                             <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="">{$APP.LBL_NONE}</option>
                    {/foreach}
               	{elseif $fldname eq "approved_status"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                       		 <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                        	 <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled>{$APP.LBL_NONE}</option>
                    {/foreach}
                {elseif $fldname eq "case_status"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                       		 <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                        	 <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled>{$APP.LBL_NONE}</option>
                    {/foreach}
                {elseif $fldname eq "activitytype" && $MODE eq 'edit'}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                       		 <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                        	 <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled>{$APP.LBL_NONE}</option>
                    {/foreach}
                {elseif $fldname eq "competitor_seq_no"}
                    
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
	                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
	                            {$arr[0]}
	                        </option>
                        {else}

	                        {if $arr[2] neq ''}
	                       		<option value="{$arr[1]}" {$arr[2]}>
	                        {else}
	                        	
	                        	{if $arr[1] eq '0'}
	                        		{if $seq_no0 eq 'true'}
		                        		<option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
		                        	{else}
		                        		<option value="{$arr[1]}" {$arr[2]}>
		                        	{/if}

		                        {elseif $arr[1] eq '1'}
	                        		{if $seq_no1 eq 'true'}
		                        		<option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
		                        	{else}
		                        		<option value="{$arr[1]}" {$arr[2]}>
		                        	{/if}

		                        {elseif $arr[1] eq '2'}
	                        		{if $seq_no2 eq 'true'}
		                        		<option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
		                        	{else}
		                        		<option value="{$arr[1]}" {$arr[2]}>
		                        	{/if}

		                        {elseif $arr[1] eq '3'}
	                        		{if $seq_no3 eq 'true'}
		                        		<option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
		                        	{else}
		                        		<option value="{$arr[1]}" {$arr[2]}>
		                        	{/if}
		                        	
		                        {else}
		                        	<option value="{$arr[1]}" {$arr[2]}>
		                        {/if}

	                        {/if}
                           	{$arr[0]}
                        	</option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled>{$APP.LBL_NONE}</option>
                    {/foreach}
                {elseif $fldname eq "samplerequisition_status"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                             <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                             <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="">{$APP.LBL_NONE}</option>
                    {/foreach}
                {elseif $fldname eq "vender"}
                	{foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} {$arr[3]}>
                            {$arr[0]}
                        </option>
                        {else}
                        <option value="{$arr[1]}" {$arr[2]} {$arr[3]}>
                                {$arr[0]}
                        </option>
                        {/if}

                    {foreachelse}
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled>{$APP.LBL_NONE}</option>
                    {/foreach}
                {elseif $fldname eq "buyer"}
                	{foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} {$arr[3]}>
                            {$arr[0]}
                        </option>
                        {else}
                        <option value="{$arr[1]}" {$arr[2]} {$arr[3]}>
                                {$arr[0]}
                        </option>
                        {/if}

                    {foreachelse}
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled>{$APP.LBL_NONE}</option>
                    {/foreach}
				{elseif $fldname eq "po_status"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                             <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                             <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="">{$APP.LBL_NONE}</option>
                    {/foreach}
				{elseif $fldname eq "payment_terms"}
                    {foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]} style='color: #777777' disabled>
                            {$arr[0]}
                        </option>
                        {else}
                        {if $arr[2] neq ''}
                             <option value="{$arr[1]}" {$arr[2]}>
                        {else}
                             <option value="{$arr[1]}" {$arr[2]} style='color: #777777' disabled>
                        {/if}
                           {$arr[0]}
                        </option>
                        {/if}
                    {foreachelse}
                        <option value=""></option>
                        <option value="">{$APP.LBL_NONE}</option>
                    {/foreach}
                {else}

                	{foreach item=arr from=$fldvalue}
                        {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                        <option value="{$arr[0]}" {$arr[2]}>
                            {$arr[0]}
                        </option>
                        {else}
                        <option value="{$arr[1]}" {$arr[2]}>
                                {$arr[0]}
                        </option>
                        {/if}

                    {foreachelse}
                        <option value=""></option>
                        <option value="" style='color: #777777' disabled>{$APP.LBL_NONE}</option>
                    {/foreach}


                {/if}
                   </select>

				{if $fldname eq 'ticketpriorities'}
                	<br />
                    ร้องเรียน&nbsp;: เร่งด่วน=1,สำคัญ=3,ปกติ=5<br />
                    ร้องขอ&nbsp;&nbsp;&nbsp;&nbsp;: เร่งด่วน=1,สำคัญ=3,ปกติ=7
                {/if}
			</td>


		{elseif $uitype eq 33}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			   <select MULTIPLE name="{$fldname}[]" size="4" style="width:310px;height: 100px;" tabindex="{$vt_tab}" class="small">
				{foreach item=arr from=$fldvalue}
					<option value="{$arr[1]}" {$arr[2]}>
                    	{$arr[0]}
                    </option>
				{/foreach}
			   </select>
			</td>

		{elseif $uitype eq 201}

			{if $MODULE eq 'Calendar' && $MODE eq 'edit' && $flag_send_report eq 1}
				<td width="20%" class="dvtCellLabel" align=right>
					<select class="small" name="parent_type" disabled onChange='document.EditView.parent_name.value=""; document.EditView.parentid.value=""'>
						{section name=combo loop=$fldlabel}
							<option value="{$fldlabel_combo[combo]}" {$fldlabel_sel[combo]} re>{$fldlabel[combo]} </option>
						{/section}
					</select>
					{if $MASS_EDIT eq '1'}<input type="checkbox" name="parent_id_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
				</td>

				<td width="30%" align=left class="dvtCellInfo">
	              <table width="100%" border="0" cellspacing="0" cellpadding="0">
	                 <tr>
	                 	<td>
	                    	<input name="parent_name" id="parent_name" readonly type="text" value="{$fldvalue}" onBlur="this.className='detailedViewTextBox'" style="width:450px;background-color:#CCC " readonly>
	                    	<input name="{$fldname}" type="hidden" value="{$secondvalue}"></td>
	                    <td>
					 	
					</tr>
	              </table>
	            </td>

			{else}
				<td width="20%" class="dvtCellLabel" align=right>
					<select class="small" name="parent_type" onChange='document.EditView.parent_name.value=""; document.EditView.parentid.value=""'>
						{section name=combo loop=$fldlabel}
							<option value="{$fldlabel_combo[combo]}" {$fldlabel_sel[combo]}>{$fldlabel[combo]} </option>
						{/section}
					</select>
					{if $MASS_EDIT eq '1'}<input type="checkbox" name="parent_id_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
				</td>

				<td width="30%" align=left class="dvtCellInfo">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                    	<input name="parent_name" id="parent_name" readonly type="text" value="{$fldvalue}" onBlur="this.className='detailedViewTextBox'" style="width:450px;">
                    	<input name="{$fldname}" type="hidden" value="{$secondvalue}"></td>
                    <td>
	                {if $fromlink eq 'qcreate'}
						<img src="{'select.gif'|@aicrm_imageurl:$THEME}" tabindex="{$vt_tab}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
					{else}
						<img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&popuptype=specific&field={$fldname}&fromlink={$fromlink}&module_return={$MODULE}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
					{/if}
					</td>
                <td>
                <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.parentid.value=''; this.form.parent_name.value='';
                if(typeof(this.form.fullname) != 'undefined' )this.form.fullname.value='';
                if(typeof(this.form.email) != 'undefined' )this.form.email.value='';
                if(typeof(this.form.phone) != 'undefined' )this.form.phone.value='';
                if(typeof(this.form.customer_name) != 'undefined' )this.form.customer_name.value='';
                if(typeof(this.form.taxid_no) != 'undefined' )this.form.taxid_no.value='';
                if(typeof(this.form.mobile) != 'undefined' )this.form.mobile.value='';
                if(typeof(this.form.address) != 'undefined' )this.form.address.value='';
                if(typeof(this.form.street) != 'undefined' )this.form.street.value='';
                if(typeof(this.form.sub_district) != 'undefined' )this.form.sub_district.value='';
                if(typeof(this.form.district) != 'undefined' )this.form.district.value='';
                if(typeof(this.form.province) != 'undefined' )this.form.province.value='';
                if(typeof(this.form.postal_code) != 'undefined' )this.form.postal_code.value='';
                return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
				</td>
			{/if}

		{elseif $uitype eq 800}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectAccounts1("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 801}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id1("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 802}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id2("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 803}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id3("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 804}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id4("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 805}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id5("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 806}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id6("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 807}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id7("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 808}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id8("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 809}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id9("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 810}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id10("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 811}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id11("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 812}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id12("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 813}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id13("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 814}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id14("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 815}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id15("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 816}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id16("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 817}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id17("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 818}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id18("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 819}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id19("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 820}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id20("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 821}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id21("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 822}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id22("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 823}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id23("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 824}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id24("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 825}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id25("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 826}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact_id26("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

		{elseif $uitype eq 900}

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="salesinvoice_no" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Salesinvoice&action=Popup&html=Popup_picker&form=SalesinvoiceEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td>
                    	<input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.salesinvoice_no.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 901}
		    <td width="20%" class="dvtCellLabel" align=right>
			  <font color="red">{$mandatory_field}</font>{$usefldlabel}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
               <select class="small" name="{$fldname}" tabindex="{$vt_tab}">
               <OPTION value=" " ></OPTION>
                {foreach item=itemname key=count from=$fldvalue}
                	{if $itemname.product_select eq $itemname.productname}
                   		{assign var=item_selected value="selected"}
               		{else}
                    	{assign var=item_selected value=""}
               		{/if}
                    <OPTION value="{$itemname.productname}"{$item_selected} >{$itemname.productname}</OPTION>
                {/foreach}
                </select>
			</td>

        {elseif $uitype eq 902}
        	<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
                <select class="small" name="{$fldname}" tabindex="{$vt_tab}" style="width:250px">
                <OPTION value=" " ></OPTION>
                {foreach item=accountname key=count from=$fldvalue}
                    {if $accountname.account_select eq $accountname.accountname}
                   		{assign var=account_selected value="selected"}
               		{else}
                    	{assign var=account_selected value=""}
               		{/if}
                    <OPTION value="{$accountname.accountname}" {$account_selected}>{$accountname.accountname}</OPTION>
                {/foreach}
                </select>
			</td>

        {elseif $uitype eq 903}
        	<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
                <select class="small" name="{$fldname}" tabindex="{$vt_tab}" style="width:250px">
                <option value="">{$APP.LBL_NONE}</option>
                {foreach item=username key=count from=$fldvalue}
                    {if $username.user_select eq $username.username}
                   		{assign var=user_select value="selected"}
               		{else}
                    	{assign var=user_select value=""}
               		{/if}
                    <OPTION value="{$username.username}" {$user_select}>{$username.username}</OPTION>
                {/foreach}
                </select>
			</td>

		{elseif $uitype eq 904}
			
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="projects_no" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Projects&action=Popup&html=Popup_picker&popuptype=specific_case&fromlink={$fromlink}&module_return={$MODULE}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.projects_name.value='',this.form.projects_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 912}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="{$fldname}" type="hidden" value="{$secondvalue}">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><textarea value="{$fldvalue}"   id="product_name1" name="product_name1" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px; height:35px"  >{$fldvalue}</textarea></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific_case&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.product_id1.value=''; this.form.product_name1.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
			</td>

		{elseif $uitype eq 914}
		
			<td width="20%" class="dvtCellLabel" align=right>
				<select class="small" name="parent_types" onChange='document.EditView.event_name.value=""; document.EditView.event_id.value=""'>
					{section name=combo loop=$fldlabel}
						<option value="{$fldlabel_combo[combo]}" {$fldlabel_sel[combo]}>{$fldlabel[combo]} </option>
					{/section}
				</select>
				{if $MASS_EDIT eq '1'}<input type="checkbox" name="parent_id_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>

			<td width="30%" align=left class="dvtCellInfo">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><input name="event_name" id="event_name" readonly type="text" value="{$fldvalue}" onBlur="this.className='detailedViewTextBox'" style="width:450px;">
                    	<input name="{$fldname}" type="hidden" value="{$secondvalue}"></td>
                    <td>
	                {if $fromlink eq 'qcreate'}
						<img src="{'select.gif'|@aicrm_imageurl:$THEME}" tabindex="{$vt_tab}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_types.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
					{else}
						<img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_types.value +"&action=Popup&html=Popup_picker&popuptype=specific&field={$fldname}&fromlink={$fromlink}&module_return={$MODULE}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
					{/if}
					</td>
                <td>
                <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.event_id.value=''; this.form.event_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
			</td>

		{elseif $uitype eq 921}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
					<td><input name="prevacc_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype=prevaccount&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.prevaccid.value=''; this.form.prevacc_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 922}
			<td width="20%" class="dvtCellLabel" align=right>
				<select class="small" name="parent_type" onChange='document.EditView.ref_name.value=""; document.EditView.refid.value=""'>
					{section name=combo loop=$fldlabel}
						<option value="{$fldlabel_combo[combo]}" {$fldlabel_sel[combo]}>{$fldlabel[combo]} </option>
					{/section}
				</select>
				{if $MASS_EDIT eq '1'}<input type="checkbox" name="parent_id_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><textarea value="{$fldvalue}"   id="ref_name" name="ref_name" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px; height:35px" >{$fldvalue}</textarea><input name="{$fldname}" type="hidden" value="{$secondvalue}"></td>
                    <td>
                {if $fromlink eq 'qcreate'}
					<img src="{'select.gif'|@aicrm_imageurl:$THEME}" tabindex="{$vt_tab}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&popuptype=specific_refcode&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				{else}
					<img src="{'select.gif'|@aicrm_imageurl:$THEME}" tabindex="{$vt_tab}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&popuptype=specific_refcode&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				{/if}</td>
                    <td>
                <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.refid.value=''; this.form.ref_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
			</td>

		{elseif $uitype eq 923 || $uitype eq 924}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel}{if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>

			<td width="30%" align=left class="dvtCellInfo">
			{if $fldvalue neq ''}

			{if $MASS_EDIT eq '1'}<br/>{/if}
			{/if}
				<input type="text" name="telephonecountrycode" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:28%;" value= "{$secondvalue}" >
				<input type="text" name="{$fldname}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:58%;" value= "{$fldvalue}" >
			</td>

		{elseif $uitype eq 925 || $uitype eq 926}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel}{if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>

			<td width="30%" align=left class="dvtCellInfo">
			{if $fldvalue neq ''}

			{if $MASS_EDIT eq '1'}<br />{/if}
			{/if}
				<input type="text" name="mobilecountrycode" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:28%;" value= "{$secondvalue}" >
				<input type="text" name="{$fldname}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:58%;" value= "{$fldvalue}" >
			</td>

		{elseif $uitype eq 927 || $uitype eq 928}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel}{if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>

			<td width="30%" align=left class="dvtCellInfo">
			{if $fldvalue neq ''}

			{if $MASS_EDIT eq '1'}<br />{/if}
			{/if}
				<input type="text" name="faxcountrycode" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:28%;" value= "{$secondvalue}" >
				<input type="text" name="{$fldname}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:58%;" value= "{$fldvalue}" >
			</td>

		{elseif $uitype eq 929}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><textarea value="{$fldvalue}"   id="{$fldname}_name" name="{$fldname}_name" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px; height:35px" >{$fldvalue}</textarea></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype=accountfield&field={$fldname}&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

        {elseif $uitype eq 930}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype=accountcode&field={$fldname}&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value='',this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 931}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact1("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value='',this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 933}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small">{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">

			{if $fromlink eq 'qcreate'}
				<input type="time" id="{$fldname}" name="{$fldname}" style="width:80px;" value="{$fldvalue}" class="easyui-timespinner">
			{else}
				{if $MASS_EDIT eq '1'}
					<input type="time" id="{$fldname}" name="{$fldname}" style="width:80px;" value="{$fldvalue}" class="easyui-timespinner">
				{else}
					{if $MODULE eq 'Calendar' && ($fldname eq 'time_start' || $fldname eq 'time_end' ) && $MODE eq 'edit' && $flag_send_report eq 1}
						<input id="{$fldname}" name="{$fldname}" style="width:80px; background-color: #CCC" value="{$fldvalue}" readonly="readonly" class="easyui-timespinner">
						<style type="text/css">
							{literal}
							.validatebox-text.validatebox-readonly{
								background-color: #CCC !important;
							}
							{/literal}
						</style>
					{elseif ($fldname eq 'case_time' || $fldname eq 'execution_time' || $fldname eq 'time_completed' || $fldname eq 'time_incomplete' || $fldname eq 'time_cancelled' || $fldname eq 'closed_time') && $MODULE eq 'HelpDesk'}
						<input id="{$fldname}" name="{$fldname}" style="width:80px; background-color: #CCC" value="{$fldvalue}" readonly="readonly" class="easyui-timespinner">
					{else}
						<input id="{$fldname}" name="{$fldname}" style="width:80px;" value="{$fldvalue}" autocomplete="on" class="easyui-timespinner" data-options="showSeconds:false,editable:true">
					{/if}
				{/if}
			{/if}

			</td>

        {elseif $uitype eq 934}
        	<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="{$fldname}_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact2("false","general",document.EditView,"{$MODULE}","{$fldname}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <!-- <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=contactcode&field={$fldname}&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td> -->
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_name.value='',this.form.{$fldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>
			<!-- <td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">

             	 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><textarea value="{$fldvalue}"   id="projects_name" name="projects_name" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px; height:35px" >{$fldvalue}</textarea><input name="{$fldname}" type="hidden" value="{$secondvalue}"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Projects&action=Popup&html=Popup_picker&popuptype=specific&form=EditView&form_submit=false&field={$fldname}&fromlink={$fromlink}&module_return={$MODULE}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.projects_name.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
            </td> -->

	    {elseif $uitype eq 935}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="serial_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectSerial("false","general",document.EditView,"{$MODULE}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <!-- onclick='return window.open("index.php?module=Serial&action=Popup&html=Popup_picker&popuptype=specific&field={$fldname}&fromlink={$fromlink}&module_return={$MODULE}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' -->
                    <!-- onclick='selectContact1("false","general",document.EditView,"{$MODULE}")'  -->
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.serial_name.value=''; this.form.serialid.value='',this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

	    {elseif $uitype eq 936}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
	          <table width="100%" border="0" cellspacing="0" cellpadding="0">
	             <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
	                <td><input name="sparepart_no" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>

	                <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Sparepart&action=Popup&html=Popup_picker&popuptype=specific&field={$fldname}&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>

	                <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.sparepartname.value='' ; this.form.sparepart_no.value='';
	               	if(typeof(this.form.sparepartlist_name) != 'undefined' )this.form.sparepartlist_name.value='';
	               	if(typeof(this.form.spare_part_no_accounting) != 'undefined' )this.form.spare_part_no_accounting.value='';
	                this.form.sparepartid.value='',this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
	          </table>
	        </td>

	    {elseif $uitype eq 937}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
	          <table width="100%" border="0" cellspacing="0" cellpadding="0">
	             <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
	                <td><input name="errors_no" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>

	                <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Errors&action=Popup&html=Popup_picker&popuptype=specific&field={$fldname}&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>

	                <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.error_name.value='' ; this.form.errors_no.value=''; this.form.errorsid.value='',this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
	          </table>
	        </td>

	    {elseif $uitype eq 938}
				<td width="20%" class="dvtCellLabel" align=right>
					<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
				</td>
				<td width="30%" align=left class="dvtCellInfo">
	              <table width="100%" border="0" cellspacing="0" cellpadding="0">
	                 <tr>
					 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
	                    <td><input name="job_no" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
	                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Job&action=Popup&html=Popup_picker&popuptype=specific&field={$fldname}&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
	                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.job_no.value=''; this.form.jobid.value='',this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

					</tr>
	              </table>
	            </td>

	    {elseif $uitype eq 939}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="ticket_no" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=HelpDesk&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.ticket_no.value=''; this.form.ticketid.value='',this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

		{elseif $uitype eq 910}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	{if $MODULE eq 'Questionnaire' }
				 		<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    	<td><input name="questionnairetemplate_name" readonly type="text" value="{$fldvalue}" style="width:455px; background-color: #CCC;" readonly=""></td>
				 	{else}
					 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
	                    <td><input name="questionnairetemplate_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
	                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Questionnairetemplate&action=Popup&html=Popup_picker&form=QuestionnairetemplateEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
	                    </td>
	                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.questionnairetemplate_name.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    {/if}
				</tr>
              </table>
            </td>
        {elseif $uitype eq 941}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="plant_no" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Plant&action=Popup&html=Popup_picker&form=PlantEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.plant_no.value=''; this.form.plantid.value='',this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

        {elseif $uitype eq 943}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="lead_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Leads&action=Popup&html=Popup_picker&form=LeadsEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.lead_name.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

        {elseif $uitype eq 944}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="activitytype" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Calendar&action=Popup&html=Popup_picker&form=CalendarEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.activitytype.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

		{elseif $uitype eq 963}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
	          <table width="100%" border="0" cellspacing="0" cellpadding="0">
	             <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
	                <td><input name="inspectiontemplate_name" readonly type="text" value="{$fldvalue}" style="width:290px;"></td>
	                <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Inspectiontemplate&action=Popup&html=Popup_picker&popuptype=specific&field={$fldname}&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
	                <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.inspectiontemplate_name.value=''; this.form.inspectiontemplateid.value='',this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
	          </table>
	        </td>

        {elseif $uitype eq 301}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>

			{if $MODULE eq 'Leads'}
				<td width="30%" align=left class="dvtCellInfo">
	              <table width="100%" border="0" cellspacing="0" cellpadding="0">
	                 <tr>
					 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
	                    <td><input name="deal_no" readonly type="text" value="{$fldvalue}" style="width:450px;background-color:#CCC " readonly ></td>
					</tr>
	              </table>
	            </td>
	        {elseif $MODULE eq 'Calendar' && $MODE eq 'edit' && $flag_send_report eq 1}
				<td width="30%" align=left class="dvtCellInfo">
	              <table width="100%" border="0" cellspacing="0" cellpadding="0">
	                 <tr>
					 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
	                    <td><input name="deal_no" readonly type="text" value="{$fldvalue}" style="width:450px;background-color:#CCC " readonly ></td>
					</tr>
	              </table>
	            </td>
			{elseif $MODULE eq 'Quotes' && $fldname eq 'dealid'}
				<td width="30%" align=left class="dvtCellInfo">
	              <table width="100%" border="0" cellspacing="0" cellpadding="0">
	                 <tr>
					 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
	                    <td><input name="deal_no" readonly type="text" value="{$fldvalue}" style="width:450px;background-color:#CCC " readonly ></td>
					</tr>
	              </table>
	            </td>
			{else}
				<td width="30%" align=left class="dvtCellInfo">
	              <table width="100%" border="0" cellspacing="0" cellpadding="0">
	                 <tr>
					 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
	                    <td><input name="deal_no" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
	                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Deal&action=Popup&html=Popup_picker&form=CalendarEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
	                    </td>
	                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.deal_no.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

					</tr>
	              </table>
	            </td>
			{/if}

		{elseif $uitype eq 302}

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="competitor_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Competitor&action=Popup&html=Popup_picker&form=CalendarEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td>
                    	<input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.competitor_name.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 303}

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="promotionvoucher_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Promotionvoucher&action=Popup&html=Popup_picker&form=CalendarEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td>
                    	<input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.promotionvoucher_name.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 304}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="promotion_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Promotion&action=Popup&html=Popup_picker&form=PromotionEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.promotion_name.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>
        {elseif $uitype eq 305}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="salesorder_no" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Salesorder&action=Popup&html=Popup_picker&form=PromotionEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.salesorder_no.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

				</tr>
              </table>
            </td>

        {elseif $uitype eq 306}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="premuimproduct_no" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Premuimproduct&action=Popup&html=Popup_picker&form=PromotionEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.premuimproduct_no.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 307}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="quote_no" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&form=QuotesEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.quote_no.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 308}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="servicerequest_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Servicerequest&action=Popup&html=Popup_picker&form=ServicerequestEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.servicerequest_name.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 309}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="ticket_title" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=HelpDesk&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.ticket_title.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 310}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="activity_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Calendar&action=Popup&html=Popup_picker&form=CalendarEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.activity_name.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>

        {elseif $uitype eq 946}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	{if $MODULE eq 'Questionnaire' }
				 		<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    	<td><input name="questionnairetemplate_name" readonly type="text" value="{$fldvalue}" style="width:455px; background-color: #CCC;" readonly=""></td>
				 	{else}
					 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
	                    <td><input name="questionnairetemplate_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
	                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Questionnairetemplate&action=Popup&html=Popup_picker&form=QuestionnairetemplateEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
	                    </td>
	                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.questionnairetemplate_name.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    {/if}
				</tr>
              </table>
            </td>

        {elseif $uitype eq 947}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr>
				 	<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    <td><input name="questionnaire_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Questionnaire&action=Popup&html=Popup_picker&form=QuestionnaireEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                    </td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.questionnaire_name.value=''; this.form.{$fldname}.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
				</tr>
              </table>
            </td>
        {elseif $uitype eq 948 }

        <!-- <td colspan="2" class="dvtCellInfo" align=right></td> -->
    	</tr>

    	<tr style="height:25px">
	        <td width=20% class="dvtCellLabel" align=right><font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}</td>
	        <td colspan="4" align=left class="dvtCellInfo">
	            <input type="hidden" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
	            <iframe class="mapview" id="mapview" style="width: 100%; height: 250px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
	        </td>
        </tr>

        {elseif $uitype eq 949 }

        	<td width=20% class="dvtCellLabel" align=right><font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}</td>
        	<td colspan="3" align=left class="dvtCellInfo">
            <input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
        
        {elseif $uitype eq 950 }
        {elseif $uitype eq 951 }
        {elseif $uitype eq 952 }
        {elseif $uitype eq 953 }
        {elseif $uitype eq 954 }
        {elseif $uitype eq 955 }
        {elseif $uitype eq 956 }
        {elseif $uitype eq 957 }
        {elseif $uitype eq 958 }
        {elseif $uitype eq 959 }

        {elseif $uitype eq 997 }

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel}
				{if $MASS_EDIT eq '1'}
					<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small"  >
				{/if}
			</td>

			<td width="30%" align=left class="dvtCellInfo">

				<input name="{$fldname}"  type="file" value="{$maindata[3].0.name}" tabindex="{$vt_tab}" onchange="validateFilename(this);validate_size(this);" />
				<input name="{$fldname}_hidden"  type="hidden" value="{$maindata[3].0.name}" />
				<input type="hidden" name="id" value=""/>
				{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
					   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
						<div align="center">
							<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]
							<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
						</div>
				   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
				   	   {/foreach}
				{/if}
				{*if $maindata[3].0.name != "" && $DUPLICATE neq 'true'}
					<div id="replaceimage">[{$maindata[3].0.orgname}] <a href="javascript:;" onClick="delimage({$ID})">Del</a></div>
				{/if*}

			</td>

	    {elseif $uitype eq 998 }

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel}
				{if $MASS_EDIT eq '1'}
					<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small"  >
				{/if}
			</td>

			<td width="30%" align=left class="dvtCellInfo">
				{if $MODULE eq 'Job' && $fldname eq 'image_customer'}

					<input name="{$fldname}"  type="file" value="{$maindata[3].0.name}" tabindex="{$vt_tab}" onchange="validateFilename(this);validate_size(this); " value="" />
					<input name="{$fldname}_hidden"  type="hidden" value="{$maindata[3].0.name}" />
					<input type="hidden" name="id" value=""/>

					{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
					{/if}

				{else}
					<input name="{$fldname}"  type="file" value="{$maindata[3].0.name}" tabindex="{$vt_tab}" onchange="validateFilename(this);" />
					<input name="{$fldname}_hidden"  type="hidden" value="{$maindata[3].0.name}" />
					<input type="hidden" name="id" value=""/>
					{if $maindata[3].0.name != "" && $DUPLICATE neq 'true'}
						<div id="replaceimage">[{$maindata[3].0.orgname}] <a href="javascript:;" onClick="delimage({$ID})">Del</a></div>
					{/if}
				{/if}
			</td>

		{elseif $uitype eq 999 }
			<td width="20%" class="dvtCellLabel" align=right>
			<font color="red">{$mandatory_field}</font>{$usefldlabel}
				{if $MASS_EDIT eq '1'}
					<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small"  >
				{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">

				{if $MODULE eq 'Job' && $fldname eq 'image_user'}

					<input name="{$fldname}"  type="file" value="{$maindata[3].0.name}" tabindex="{$vt_tab}" onchange="validateFilename(this);validate_size(this); " value="" />
					<input name="{$fldname}_hidden"  type="hidden" value="{$maindata[3].0.name}" />
					<input type="hidden" name="id" value=""/>

					{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
					{/if}
				{/if}
			</td>

		{elseif $uitype eq 53} <!--Assigned To -->
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
					{assign var=check value=1}
						{foreach key=key_one item=arr from=$fldvalue}
							{foreach key=sel_value item=value from=$arr}
								{if $value ne ''}
									{assign var=check value=$check*0}
								{else}
									{assign var=check value=$check*1}
								{/if}
							{/foreach}
						{/foreach}
						{if $check eq 0}
							{assign var=select_user value='checked'}
							{assign var=style_user value='display:block'}
							{assign var=style_group value='display:none'}
						{else}
							{assign var=select_group value='checked'}
							{assign var=style_user value='display:none'}
							{assign var=style_group value='display:block'}
						{/if}

						<input type="radio" tabindex="{$vt_tab}" name="assigntype" {$select_user} value="U" onclick="toggleAssignType(this.value)" >&nbsp;{$APP.LBL_USER}

						{if $secondvalue neq ''}
							<input type="radio" name="assigntype" {$select_group} value="T" onclick="toggleAssignType(this.value)">&nbsp;{$APP.LBL_GROUP}
						{/if}

						<span id="assign_user" style="{$style_user}">


							<select name="assigned_user_id" class="small">
								{foreach key=key_one item=arr from=$fldvalue}
									{foreach key=sel_value item=value from=$arr}
										{*{if $MODULE eq 'HelpDesk'}
											{if $value eq ''}
												<option value="{$key_one}" {$value} style='color: #777777' disabled>{$sel_value}</option>
											{else}
												<option value="{$key_one}" {$value}>{$sel_value}</option>
											{/if}
										{else}
											<option value="{$key_one}" {$value}>{$sel_value}</option>
										{/if}*}

										<option value="{$key_one}" {$value}>{$sel_value}</option>
									{/foreach}
								{/foreach}
							</select>
						</span>

						{if $secondvalue neq ''}
							<span id="assign_team" style="{$style_group}">
								<select name="assigned_group_id" class="small">';
									{foreach key=key_one item=arr from=$secondvalue}
										{foreach key=sel_value item=value from=$arr}
											{*{if $MODULE eq 'HelpDesk'}
												{if $value eq ''}
													<option value="{$key_one}" {$value} style='color: #777777' disabled>{$sel_value}</option>
												{else}
													<option value="{$key_one}" {$value}>{$sel_value}</option>
												{/if}
											{else}
												<option value="{$key_one}" {$value}>{$sel_value}</option>
											{/if}*}

											<option value="{$key_one}" {$value}>{$sel_value}</option>
										{/foreach}
									{/foreach}
								</select>
							</span>
						{/if}
			</td>

		{elseif $uitype eq 52 || $uitype eq 77}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{if $uitype eq 52}
					<select name="assigned_user_id" class="small">
				{elseif $uitype eq 77}
					<select name="assigned_user_id1" tabindex="{$vt_tab}" class="small">
				{else}
					<select name="{$fldname}" tabindex="{$vt_tab}" class="small">
				{/if}

				{foreach key=key_one item=arr from=$fldvalue}
					{foreach key=sel_value item=value from=$arr}
						<option value="{$key_one}" {$value}>{$sel_value}</option>
					{/foreach}
				{/foreach}
				</select>
			</td>

		{elseif $uitype eq 51}

			{if $MODULE eq 'Accounts'}
				{assign var='popuptype' value = 'specific_account_address'}
			{else}
				{assign var='popuptype' value = 'specific_contact_account_address'}
			{/if}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="100%">
                    <input name="account_name" readonly id="account_name" type="text" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width: 97%" >
                    <input name="{$fldname}" type="hidden" value="{$secondvalue}"></td>
                    <td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype={$popuptype}&form=TasksEditView&form_submit=false&fromlink={$fromlink}&recordid={$ID}","test","width=1000,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
			</td>

		{elseif $uitype eq 50}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><textarea value="{$fldvalue}"   id="account_name" name="account_name" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px; height:35px" >{$fldvalue}</textarea><input name="{$fldname}" type="hidden" value="{$secondvalue}"></td>
                    <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=TasksEditView&form_submit=false&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    <td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                  </tr>
                </table>
			</td>

		{elseif $uitype eq 73}<!-- Account Name -->
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">

                    {if $MODULE eq 'Leads'}
                       <table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                           <td>
                           	<input name="account_name" readonly id="single_accountid" type="text" value="{$fldvalue}" style="width:450px;background-color:#CCC;">
                           	<input name="{$fldname}" type="hidden" value="{$secondvalue}"></td>
                         </tr>
                       </table>
                    {elseif $MODULE eq 'Projects'}
                      	<table width="100%" border="0" cellspacing="0" cellpadding="0">
			                        <tr>
			                            <td>
			                            	<input name="account_name" readonly id="single_accountid" type="text" value="{$fldvalue}" onBlur="this.className='detailedViewTextBox'" style="width:450px; background-color: #ccc">
		                          			<input name="{$fldname}" type="hidden" value="{$secondvalue}">
		                          		</td>
			                        </tr>
		                        </table>

                    {elseif $MODULE eq 'Order'}
                    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	                        <tr>
	                            <td>
	                            	<input name="account_name" readonly id="single_accountid" type="text" value="{$fldvalue}" onBlur="this.className='detailedViewTextBox'" style="width:440px;">
	                            	<input name="{$fldname}" type="hidden" value="{$secondvalue}">
	                        	</td>
	                        	<td>
	                        		<img src="{'plus_new.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CREATE}" title="{$APP.LBL_CREATE}" LANGUAGE=javascript onclick='Quickcreate("Accounts","{$MODULE}");' align="absmiddle" style='cursor:hand;cursor:pointer'>
	                        	</td>
	                            <td>
	                            	<img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectAccount("false","general",document.EditView)' align="absmiddle" style='cursor:hand;cursor:pointer'>
	                            </td>
	                        	<td>
	                        		<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$fldname}.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
	                        	</td>
	                        </tr>
                        </table>

                    {else}

                    	{if $flagaccount eq true && $MODULE eq 'Calendar'}
	                      	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		                        <tr>
									<td>
			                          	<input name="account_name" readonly id="single_accountid" type="text" value="{$fldvalue}" onBlur="this.className='detailedViewTextBox'" style="width:450px; background-color: #ccc">
			                          	<input name="{$fldname}" type="hidden" value="{$secondvalue}">
			                        </td>
		                        </tr>
	                        </table>
                        {else}
	                        {if $MODE eq 'edit' && $MODULE eq 'Calendar'}
		                       	<table width="100%" border="0" cellspacing="0" cellpadding="0">
			                        <tr>
			                            <td>
			                            	<input name="account_name" readonly id="single_accountid" type="text" value="{$fldvalue}" onBlur="this.className='detailedViewTextBox'" style="width:450px; background-color: #ccc">
		                          			<input name="{$fldname}" type="hidden" value="{$secondvalue}">
		                          		</td>
			                        </tr>
		                        </table>
							{elseif $MODULE eq 'Quotes' && $fldname eq "designerid"}
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
				                  	<tr>
				                    	<td width="100%">
				                    		<input name="designer_name" readonly id="single_designerid" type="text" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width: 97%" >
				                    		<input id="{$fldname}" name="{$fldname}" type="hidden" value="{$secondvalue}">
				                    	</td>
				                    	<td>
				                    		<img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false&fromlink={$fromlink}&module_return={$MODULE}&return_field={$fldname}","test","left=500,width=1000,height=602,top=250,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				                    	</td>
				                    	<td>
				                    		<input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.designerid.value=''; this.form.designer_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				                    	</td>
				                  	</tr>
				                </table>
							{elseif $MODULE eq 'Quotes' && $fldname eq "account_id"}
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
				                  	<tr>
				                    	<td width="100%">
				                    		<input name="account_name" readonly id="single_accountid" type="text" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width: 97%" >
				                    		<input id="{$fldname}" name="{$fldname}" type="hidden" value="{$secondvalue}">
				                    	</td>
				                    	<td>
				                    		<img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false&fromlink={$fromlink}&module_return={$MODULE}","test","left=500,width=1000,height=602,top=250,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				                    	</td>
				                    	<td>
				                    		<input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="
											this.form.account_id.value=''; 
											this.form.account_name.value='';
											this.form.mobile.value='';
											this.form.taxid_no.value='';
											this.form.email.value='';
											this.form.village.value='';
											this.form.room_no.value='';
											this.form.address_no.value='';
											this.form.village_no.value='';
											this.form.street.value='';
											this.form.lane.value='';
											this.form.sub_district.value='';
											this.form.district.value='';
											this.form.province.value='';
											this.form.postal_code.value='';
											return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				                    	</td>
				                  	</tr>
				                </table>
	                        {else}
	                        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
				                  	<tr>
				                    	<td width="100%">
				                    		<input name="account_name" readonly id="single_accountid" type="text" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width: 97%" >
				                    		<input id="{$fldname}" name="{$fldname}" type="hidden" value="{$secondvalue}">
				                    	</td>
				                    	<td>
				                    		<img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false&fromlink={$fromlink}&module_return={$MODULE}","test","left=500,width=1000,height=602,top=250,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				                    	</td>
				                    	<td>
				                    		<input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				                    	</td>
				                  	</tr>
				                </table>

	                        {/if}

                        {/if}
                    {/if}


            </td>

		{elseif $uitype eq 75 || $uitype eq 81}
			<td width="20%" class="dvtCellLabel" align=right>
				{if $uitype eq 81}
					{assign var="pop_type" value="specific_vendor_address"}
					{else}{assign var="pop_type" value="specific"}
				{/if}
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="vendor_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Vendors&action=Popup&html=Popup_picker&popuptype={$pop_type}&form=EditView&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.vendor_id.value='';this.form.vendor_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		{elseif $uitype eq 57}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{if $fromlink eq 'qcreate'}
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
                        <input name="contact_name" readonly type="text" value="{$fldvalue}" onBlur="this.className='detailedViewTextBox'">
                        <input id="{$fldname}" name="{$fldname}" type="hidden" value="{$secondvalue}"></td>
                        <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact("false","general",document.EditView)' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                        <td><input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                      </tr>
                    </table>
                {elseif $MODULE eq 'Leads'}
                       <table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                           <td>
                           	<input name="contact_name" readonly type="text" tabindex="{$vt_tab}" value="{$fldvalue}" onBlur="this.className='detailedViewTextBox'" style="width:450px;background-color: #ccc">
                            	<input id="{$fldname}" name="{$fldname}" type="hidden" value="{$secondvalue}">
                         </tr>
                       </table>
				{else}

                	{if $flagcontact eq true && $MODULE eq 'Calendar'}
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>
                            	<input name="contact_name" readonly type="text" tabindex="{$vt_tab}" value="{$fldvalue}" onBlur="this.className='detailedViewTextBox'" style="width:450px;background-color: #ccc">
                            	<input id="{$fldname}" name="{$fldname}" type="hidden" value="{$secondvalue}">
                            </td>
                          </tr>
                        </table>
                	{else}
                         <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>
                            <input name="contact_name" readonly type="text" tabindex="{$vt_tab}" value="{$fldvalue}" onBlur="this.className='detailedViewTextBox'" style="width:450px;">
                            <input id="{$fldname}" name="{$fldname}" type="hidden" value="{$secondvalue}"></td>
                            <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact("false","general",document.EditView,"{$MODULE}")' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                            <td><input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                          </tr>
                        </table>
                    {/if}

				{/if}
			</td>

		{elseif $uitype eq 58}

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
                 	<tr>
				 		<td><input name="{$fldname}" type="hidden" value="{$secondvalue}" ></td>
                    	<td><input name="campaign_name" readonly type="text" value="{$fldvalue}" style="width:450px;"></td>
                    	<td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Campaigns&action=Popup&html=Popup_picker&popuptype=specific_campaign&form=EditView&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    	<td><input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.campaignid.value=''; this.form.campaign_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
					</tr>
              	</table>
			</td>


		{elseif $uitype eq 80}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="salesorder_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectSalesOrder();' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.salesorder_id.value=''; this.form.salesorder_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		{elseif $uitype eq 78}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small">{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="quote_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}" >&nbsp;<img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectQuote()' align="absmiddle" style='cursor:hand;cursor:pointer' >&nbsp;<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.quote_id.value=''; this.form.quote_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		{elseif $uitype eq 76}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="potential_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectPotential()' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.potential_id.value=''; this.form.potential_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		{elseif $uitype eq 17}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				&nbsp;&nbsp;http://
			<input style="width:74%;" class = 'detailedViewTextBox' type="text" tabindex="{$vt_tab}" name="{$fldname}" style="border:1px solid #bababa;" size="27" onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" onkeyup="validateUrl('{$fldname}');" value="{$fldvalue}">
			</td>

		{elseif $uitype eq 85}
            <td width="20%" class="dvtCellLabel" align=right>
                <font color="red">{$mandatory_field}</font>
                {$usefldlabel}
                {if $MASS_EDIT eq '1'}
                	<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >
                {/if}
            </td>
            <td width="30%" align=left class="dvtCellInfo">
				<img src="{'skype.gif'|@aicrm_imageurl:$THEME}" alt="Skype" title="Skype" LANGUAGE=javascript align="absmiddle"></img>
				<input class='detailedViewTextBox' type="text" tabindex="{$vt_tab}" name="{$fldname}" style="border:1px solid #bababa;" size="27" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="{$fldvalue}">
            </td>

		{elseif $uitype eq 71 || $uitype eq 72 }
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{if $fldname eq "unit_price" && $fromlink neq 'qcreate'}
					<span id="multiple_currencies">
                    {if  $fldname eq 'unit_price'}
                        <input name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'; updateUnitPrice('unit_price', '{$BASE_CURRENCY}');"  readonly="readonly" style="background-color: #CCC; width:60%;">
                    {else}
						<input name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'; updateUnitPrice('unit_price', '{$BASE_CURRENCY}');"  value="{$fldvalue}" style="width:60%;">
					{/if}

                    {if $MASS_EDIT neq 1  && $MODE eq 'Products' }
						&nbsp;<a href="javascript:void(0);" onclick="updateUnitPrice('unit_price', '{$BASE_CURRENCY}'); toggleShowHide('currency_class','multiple_currencies');">{$APP.LBL_MORE_CURRENCIES} &raquo;</a>
					{/if}
					</span>
					{if $MASS_EDIT neq 1}
					<div id="currency_class" class="multiCurrencyEditUI" width="350">
						<input type="hidden" name="base_currency" id="base_currency" value="{$BASE_CURRENCY}" />
						<input type="hidden" name="base_conversion_rate" id="base_currency" value="{$BASE_CURRENCY}" />
						<table width="100%" height="100%" class="small" cellpadding="5">
						<tr class="detailedViewHeader">
							<th colspan="4">
								<b>{$MOD.LBL_PRODUCT_PRICES}</b>
							</th>
							<th align="right">
								<img border="0" style="cursor: pointer;" onclick="toggleShowHide('multiple_currencies','currency_class');" src="{'close.gif'|@aicrm_imageurl:$THEME}"/>
							</th>
						</tr>
						<tr class="detailedViewHeader">
							<th>{$APP.LBL_CURRENCY}</th>
							<th>{$APP.LBL_PRICE}</th>
							<th>{$APP.LBL_CONVERSION_RATE}</th>
							<th>{$APP.LBL_RESET_PRICE}</th>
							<th>{$APP.LBL_BASE_CURRENCY}</th>
						</tr>
						{foreach item=price key=count from=$PRICE_DETAILS}
							<tr>
								{if $price.check_value eq 1 || $price.is_basecurrency eq 1}
									{assign var=check_value value="checked"}
									{assign var=disable_value value=""}
								{else}
									{assign var=check_value value=""}
									{assign var=disable_value value="disabled=true"}
								{/if}

								{if $price.is_basecurrency eq 1}
									{assign var=base_cur_check value="checked"}
								{else}
									{assign var=base_cur_check value=""}
								{/if}

								{if $price.curname eq $BASE_CURRENCY}
									{assign var=call_js_update_func value="updateUnitPrice('$BASE_CURRENCY', 'unit_price');"}
								{else}
									{assign var=call_js_update_func value=""}
								{/if}

								<td align="right" class="dvtCellLabel">
									{$price.currencylabel|@getTranslatedCurrencyString} ({$price.currencysymbol})
									<input type="checkbox" name="cur_{$price.curid}_check" id="cur_{$price.curid}_check" class="small" onclick="fnenableDisable(this,'{$price.curid}'); updateCurrencyValue(this,'{$price.curname}','{$BASE_CURRENCY}','{$price.conversionrate}');" {$check_value}>
								</td>
								<td class="dvtCellInfo" align="left">
									<input {$disable_value} type="text" size="10" class="small" name="{$price.curname}" id="{$price.curname}" value="{$price.curvalue}" onBlur="{$call_js_update_func} fnpriceValidation('{$price.curname}');">
								</td>
								<td class="dvtCellInfo" align="left">
									<input disabled=true type="text" size="10" class="small" name="cur_conv_rate{$price.curid}" value="{$price.conversionrate}">
								</td>
								<td class="dvtCellInfo" align="center">
									<input {$disable_value} type="button" class="crmbutton small edit" id="cur_reset{$price.curid}"  onclick="updateCurrencyValue(this,'{$price.curname}','{$BASE_CURRENCY}','{$price.conversionrate}');" value="{$APP.LBL_RESET}"/>
								</td>
								<td class="dvtCellInfo">
									<input {$disable_value} type="radio" class="detailedViewTextBox no-spinners" id="base_currency{$price.curid}" name="base_currency_input" value="{$price.curname}" {$base_cur_check} onchange="updateBaseCurrencyValue()" />
								</td>
							</tr>
						{/foreach}
						</table>
					</div>
					{/if}
				{else}

					{if $MODE eq 'edit'}
						<div class="hide-inputbtns">
							<input name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" type="number" step="0.001" class="form-control detailedViewTextBox {$fldname}_field" onFocus="this.className='detailedViewTextBox'" onBlur="this.className='detailedViewTextBox'" value="{$fldvalue}" >
						</div>
					{else}
						<div class="hide-inputbtns">
							<input name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" type="number" step="0.001" class="form-control detailedViewTextBox {$fldname}_field" onFocus="this.className='detailedViewTextBox'" onBlur="this.className='detailedViewTextBox'" value="{$fldvalue}">
						</div>
					{/if}
				{/if}
			</td>

		{elseif $uitype eq 56}<!-- Check Box -->
            {php}
                global  $chk_close;
            {/php}

			{if $fldname eq 'passed_inspection' && $MODULE eq 'Accounts' && $HIDDEN_FIELDS eq 0}

			{else}
				<td width="20%" class="dvtCellLabel" align=right>
					<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
				</td>
			{/if}
			{if $fldname eq 'notime' && $ACTIVITY_MODE eq 'Events'}
				{if $fldvalue eq 1}
					<td width="30%" align=left class="dvtCellInfo">
						<input name="{$fldname}" type="checkbox" tabindex="{$vt_tab}" onclick="toggleTime()" checked>
					</td>
				{else}
					<td width="30%" align=left class="dvtCellInfo">
						<input name="{$fldname}" tabindex="{$vt_tab}" type="checkbox" onclick="toggleTime()" >
					</td>
				{/if}

            {elseif $fldname eq 'email_setup' || $fldname eq 'email_send' || $fldname eq 'send_sms' || $fldname eq 'setup_sms'}
            	<td width="30%" align=left class="dvtCellInfo">
                    <input type="text" name="{$fldname}" id="{$fldname}" value="{$fldvalue}" style="background-color: #CCC; width:50px"class=detailedViewTextBox  onBlur="this.className='detailedViewTextBox';" readonly="readonly">
			{elseif ($fldname eq 'flag_erp_response_status' || $fldname eq 'flag_projects')}

			    {if $fldvalue eq 1}
					<td width="30%" align=left class="dvtCellInfo">
						<input name="{$fldname}" type="checkbox" tabindex="{$vt_tab}" checked style="pointer-events: none; background-color: #CCC !important; accent-color: #CCC;" readonly="readonly">
					</td>
				{elseif $fldname eq 'filestatus'&& $MODE eq 'create'}
					<td width="30%" align=left class="dvtCellInfo">
						<input name="{$fldname}" type="checkbox" tabindex="{$vt_tab}" checked style="pointer-events: none; background-color: #CCC !important; accent-color: #CCC;" readonly="readonly">
					</td>
				{else}
					<td width="30%" align=left class="dvtCellInfo">
						<input name="{$fldname}" tabindex="{$vt_tab}" type="checkbox" {if ( $PROD_MODE eq 'create' &&  $fldname|substr:0:3 neq 'cf_') ||( $fldname|substr:0:3 neq 'cf_' && $PRICE_BOOK_MODE eq 'create' ) || $USER_MODE eq 'create'}{/if} style="pointer-events: none; background-color: #CCC !important; accent-color: #CCC;" readonly="readonly">
					</td>
				{/if}
    
			{elseif $fldname eq 'approve_level1' || $fldname eq 'approve_level2' || $fldname eq 'approve_level3' || $fldname eq 'approve_level4'}
					<td width="30%" align=left class="dvtCellInfo">
                    {if $fldvalue eq 1}
						<input name="{$fldname}" type="checkbox" tabindex="{$vt_tab}" onclick="calcGrandTotal()" checked>
					{else}
						<input name="{$fldname}" type="checkbox" tabindex="{$vt_tab}" onclick="calcGrandTotal()"  >
                    {/if}
                    </td>
			{elseif $fldname eq 'passed_inspection' && $MODULE eq 'Accounts' && $HIDDEN_FIELDS eq 0}
				<input type="hidden" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}">

			{else}
				{if $fldvalue eq 1}
					<td width="30%" align=left class="dvtCellInfo">
						<input name="{$fldname}" type="checkbox" tabindex="{$vt_tab}" checked>
					</td>
				{elseif $fldname eq 'filestatus'&& $MODE eq 'create'}
					<td width="30%" align=left class="dvtCellInfo">
						<input name="{$fldname}" type="checkbox" tabindex="{$vt_tab}" checked>
					</td>
				{else}
					<td width="30%" align=left class="dvtCellInfo">
						<input name="{$fldname}" tabindex="{$vt_tab}" type="checkbox" {if ( $PROD_MODE eq 'create' &&  $fldname|substr:0:3 neq 'cf_') ||( $fldname|substr:0:3 neq 'cf_' && $PRICE_BOOK_MODE eq 'create' ) || $USER_MODE eq 'create'}checked{/if}>
					</td>
				{/if}
			{/if}

		{elseif $uitype eq 23 || $uitype eq 5 || $uitype eq 6}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>

			<td width="30%" align=left class="dvtCellInfo">
				{foreach key=date_value item=time_value from=$fldvalue}
					{assign var=date_val value="$date_value"}
					{assign var=time_val value="$time_value"}
				{/foreach}
                {if $date_val eq '00-00-0000' || $date_val eq '0000-00-00'}
                	{assign var=date_val value=""}
                {/if}

				{if $fldname eq 'due_date' || $fldname eq 'checkindate' || $fldname eq 'checkoutdate' || ($fldname eq 'date_start' && $flaglocation eq true && $MODULE eq 'Calendar') || ($MODULE eq 'Quotes' && ($fldname eq 'project_est_s_date' || $fldname eq 'project_est_e_date'))}
						<input name="{$fldname}" tabindex="{$vt_tab}"   id="jscal_field_{$fldname}" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="border:1px solid #bababa;background-color: #CCC" size="11" maxlength="10" value="{$date_val}"  readonly="readonly">
				{elseif ($fldname eq 'case_date' || $fldname eq 'date_of_execution' || $fldname eq 'date_completed' || $fldname eq 'date_incomplete' || $fldname eq 'date_cancelled' || $fldname eq 'closed_date') && $MODULE eq 'HelpDesk'}
					<input name="{$fldname}" tabindex="{$vt_tab}"  id="{$fldname}"  type="text" style="border:1px solid #bababa; background-color: #CCC" size="11" maxlength="10" readonly="readonly" value="{$date_val}" >

                {elseif $fldname eq 'date_start' && $MODULE eq 'Calendar' && $MODE eq 'edit' }
                	{if $flag_send_report eq 1}
                		<input name="{$fldname}" tabindex="{$vt_tab}"  id="jscal_field_{$fldname}"  type="text" size="11" maxlength="10" value="{$date_val}" readonly="readonly" style="border:1px solid #bababa; background-color: #CCC">
                	{else}
                		<input name="{$fldname}" tabindex="{$vt_tab}"  id="jscal_field_{$fldname}"  type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="{$date_val}" >
                	{/if}
                {elseif $fldname eq 'approved_date'}
                	<input name="{$fldname}" tabindex="{$vt_tab}"  id="{$fldname}"  type="text" style="border:1px solid #bababa; background-color: #CCC" size="11" maxlength="10" readonly="readonly" value="{$date_val}" >

                {else}
					<input name="{$fldname}" tabindex="{$vt_tab}"  id="jscal_field_{$fldname}"  type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="{$date_val}" >

                {/if}

                {if $fldname neq 'due_date'}

					{if $fldname eq 'date_start' && $flaglocation eq true && $MODULE eq 'Calendar'}

					{elseif ($fldname eq 'case_date' || $fldname eq 'date_of_execution' || $fldname eq 'date_completed' || $fldname eq 'date_incomplete' || $fldname eq 'date_cancelled' || $fldname eq 'closed_date') && $MODULE eq 'HelpDesk'}
						<img src="{'btnL3Calendar.gif'|@aicrm_imageurl:$THEME}" id="{$fldname}" style="vertical-align: middle;">
					{elseif $fldname eq 'checkindate' || $fldname eq 'checkoutdate' || $fldname eq 'approved_date' || ($MODULE eq 'Quotes' && ($fldname eq 'project_est_s_date' || $fldname eq 'project_est_e_date')) }

					{elseif $fldname eq 'date_start' && $MODULE eq 'Calendar' && $MODE eq 'edit'}
						{if $flag_send_report eq 1}
							<img src="{'btnL3Calendar.gif'|@aicrm_imageurl:$THEME}" id="date_start" style="vertical-align: middle;">
						{else}
							<img src="{'btnL3Calendar.gif'|@aicrm_imageurl:$THEME}" id="jscal_trigger_{$fldname}" style="vertical-align: middle;">
						{/if}
               		{else}
                		<img src="{'btnL3Calendar.gif'|@aicrm_imageurl:$THEME}" id="jscal_trigger_{$fldname}" style="vertical-align: middle;">
					{/if}

                {/if}


				{if $uitype eq 6}
					<input name="time_start" tabindex="{$vt_tab}" style="border:1px solid #bababa;" size="5" maxlength="5" type="text" value="{$time_val}">
				{/if}

				{if $uitype eq 6 && $QCMODULE eq 'Event'}
					<input name="dateFormat" type="hidden" value="{$dateFormat}">
				{/if}
				{if $uitype eq 23 && $QCMODULE eq 'Event'}
					<input name="time_end" style="border:1px solid #bababa;" size="5" maxlength="5" type="text" value="{$time_val}">
				{/if}

				{foreach key=date_format item=date_str from=$secondvalue}
					{assign var=dateFormat value="$date_format"}
					{assign var=dateStr value="$date_str"}
				{/foreach}

				{if $uitype eq 5 || $uitype eq 23}
                	 {if $fldname neq 'due_date' }
						{if $fldname eq 'date_start' && $flaglocation eq true && $MODULE eq 'Calendar'}
               			
               			{elseif $fldname eq 'checkindate' || $fldname eq 'checkoutdate'}
                        
                        {else}
                        	<br><font size=1><em old="(yyyy-mm-dd)">({$dateStr})</em></font>
                     	{/if}
                     {/if}
				{else}
					    <br><font size=1><em old="(yyyy-mm-dd)">({$dateStr})</em></font>
				{/if}

          	 {if $fldname neq 'due_date'  }
          	 		<!-- && ($fldname neq 'date_start' &&  $MODULE neq 'Calendar' &&  $MODE neq 'edit') -->
                    {if $fldname eq 'date_start' && $flaglocation eq true && $MODULE eq 'Calendar' || ($flag_send_report eq 1)}

                    {elseif ($fldname eq 'case_date' || $fldname eq 'date_of_execution' || $fldname eq 'date_completed' || $fldname eq 'date_incomplete' || $fldname eq 'date_cancelled' || $fldname eq 'closed_date') && $MODULE eq 'HelpDesk'}

                    {elseif $fldname eq 'date_start' && $MODULE eq 'Calendar' && $MODE eq 'edit' && $flag_send_report eq 1}

                    {elseif $fldname eq 'checkindate' || $fldname eq 'checkoutdate'|| $fldname eq 'approved_date'}

                    {else}
						<script type="text/javascript" id="massedit_calendar_{$fldname}">
                            Calendar.setup ({ldelim}

                                inputField : "jscal_field_{$fldname}", ifFormat : "{$dateFormat}", showsTime : false, button : "jscal_trigger_{$fldname}", singleClick : true, step : 1 ,

                                 {if $fldname eq 'pricelist_startdate'}
                                     onSelect: function(dateText,selectedDate) {ldelim}
                                            pricelist_startdate(dateText,selectedDate);
                                            return true;
                                    {rdelim}
                                {elseif $fldname eq 'pricelist_enddate'}
                                     onSelect: function(dateText,selectedDate) {ldelim}
                                            pricelist_enddate(dateText,selectedDate);
                                            return true;
                                    {rdelim}

                                {elseif $fldname eq 'jobdate_operate'}
                                     onSelect: function(dateText,selectedDate) {ldelim}
                                            jobdate_operate(dateText,selectedDate);
                                            return true;
                                    {rdelim}
                                {elseif $fldname eq 'close_date' && $MODULE neq 'Job'}
                                     onSelect: function(dateText,selectedDate) {ldelim}
                                            close_date(dateText,selectedDate);
                                            return true;
                                    {rdelim}

                                {elseif $fldname eq 'order_date'}
                                     onSelect: function(dateText,selectedDate) {ldelim}
                                            order_date(dateText,selectedDate);
                                            return true;
                                    {rdelim}
                                {elseif $fldname eq 'date_start' && $MODULE eq 'Calendar'  }
                                     onSelect: function(dateText,selectedDate) {ldelim}
                                            date_start(dateText,selectedDate);
                                            return true;
                                    {rdelim}
								 {elseif $fldname eq 'export_inv_date' && $MODULE eq 'Goodsreceive'  }
                                     onSelect: function(dateText,selectedDate) {ldelim}
                                            export_inv_date(dateText,selectedDate);
                                            return true;
                                    {rdelim}
                                {elseif $fldname eq 'po_date' && $MODULE eq 'Purchasesorder'  }
                                     onSelect: function(dateText,selectedDate) {ldelim}
                                            po_date(dateText,selectedDate);
                                            return true;
                                    {rdelim}
								{elseif $fldname eq 'quotation_date' && $MODULE eq 'Quotes'  }
                                     onSelect: function(dateText,selectedDate) {ldelim}
                                            quotation_date(dateText,selectedDate);
                                            return true;
                                    {rdelim}
                                {/if}

                            {rdelim})
                        </script>
                    {/if}

            {/if}
			</td>

		{elseif $uitype eq 63}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="{$fldname}" type="text" size="2" value="{$fldvalue}" tabindex="{$vt_tab}" >&nbsp;
				<select name="duration_minutes" tabindex="{$vt_tab}" class="small">
					{foreach key=labelval item=selectval from=$secondvalue}
						<option value="{$labelval}" {$selectval}>{$labelval}</option>
					{/foreach}
				</select>

		{elseif $uitype eq 68 || $uitype eq 66 || $uitype eq 62}
			<td width="20%" class="dvtCellLabel" align=right>
				{if $fromlink eq 'qcreate'}
					<select class="small" name="parent_type" onChange='document.QcEditView.parent_name.value=""; document.QcEditView.parent_id.value=""'>
				{else}
					<select class="small" name="parent_type" onChange='document.EditView.parent_name.value=""; document.EditView.parent_id.value=""'>
				{/if}
					{section name=combo loop=$fldlabel}
						<option value="{$fldlabel_combo[combo]}" {$fldlabel_sel[combo]}>{$fldlabel[combo]} </option>
					{/section}
				</select>
				{if $MASS_EDIT eq '1'}<input type="checkbox" name="parent_id_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><textarea value="{$fldvalue}"   id="parentid" name="parent_name" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px; height:35px" >{$fldvalue}</textarea><input name="{$fldname}" type="hidden" value="{$secondvalue}"></td>
                    <td>
	                {if $fromlink eq 'qcreate'}
						<img src="{'select.gif'|@aicrm_imageurl:$THEME}" tabindex="{$vt_tab}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
					{else}
						<img src="{'select.gif'|@aicrm_imageurl:$THEME}" tabindex="{$vt_tab}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
					{/if}
					</td>
                    <td>
	                {if $fromlink eq 'qcreate'}
						<input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
					{else}
						<input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
					{/if}
				   </td>
                  </tr>
                </table>
			</td>

		{elseif $uitype eq 357}
			<td width="20%" class="dvtCellLabel" align=right>To:&nbsp;</td>
			<td width="90%" colspan="3">
				<input name="{$fldname}" type="hidden" value="{$secondvalue}">
				<textarea readonly name="parent_name" cols="70" rows="2">{$fldvalue}</textarea>&nbsp;
				<select name="parent_type" class="small">
					{foreach key=labelval item=selectval from=$fldlabel}
						<option value="{$labelval}" {$selectval}>{$labelval}</option>
					{/foreach}
				</select>
				&nbsp;
				{if $fromlink eq 'qcreate'}
					<img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				{else}
					<img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				{/if}
			</td>
		   <tr style="height:25px">
			<td width="20%" class="dvtCellLabel" align=right>CC:&nbsp;</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="ccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="">
			</td>
			<td width="20%" class="dvtCellLabel" align=right>BCC:&nbsp;</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="bccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="">
			</td>
		   </tr>

		{elseif $uitype eq 59}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                 	 <td>
                 	  <input type="text" tabindex="{$vt_tab}" name="product_name" id ="product_name" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="width:450px;">
                 	 	<input name="{$fldname}" type="hidden" value="{$secondvalue}">
                 	</td>

                   	{*if $MODULE eq 'Job'}
                   		<td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific&fromlink={$fromlink}&brand="+document.EditView.job_brand.value,"test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>

                   	{elseif $MODULE eq 'Competitor'}
                   		<td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific&module_return={$MODULE}&fromlink={$fromlink}&brand="+document.EditView.brand_competitor.value ,"test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>

                    {else*}
                    	<td><img tabindex="{$vt_tab}" src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific&module_return={$MODULE}&fromlink={$fromlink}","test","width=1000,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    {*/if*}


                 	{if $MODULE eq 'Quotation' }
                    	<td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; this.form.unit_size.value='';  this.form.pricesqm.value=''; get_sum_contact(); get_sum_months(); get_sum_tranfer(); return false; " align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                 	{else}
                    	<td><input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; if(typeof(this.form.productcode) != 'undefined' )this.form.productcode.value='' ;  return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
                    {/if}

                  </tr>
                </table>
			</td>

		{elseif $uitype eq 55 || $uitype eq 255}
			<td width="20%" class="dvtCellLabel" align=right>
				{if $MASS_EDIT eq '1' && $fldvalue neq ''}
					{$APP.Salutation}<input type="checkbox" name="salutationtype_mass_edit_check" id="salutationtype_mass_edit_check" class="small" ><br />
				{/if}

				{if $uitype eq 55}
					<font color="red">{$mandatory_field}</font> {$usefldlabel}
	            {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}

	            {elseif $uitype eq 255}
					<font color="red">{$mandatory_field}</font>{$usefldlabel}{if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
				{/if}
			</td>

			<td width="30%" align=left class="dvtCellInfo">
			{if $fldvalue neq ''}
			<select name="salutationtype" class="small" style="width: 20%;">
				{foreach item=arr from=$fldvalue}
				<option value="{$arr[1]}" {$arr[2]}>
				{$arr[0]}
				</option>
				{/foreach}
			</select>
			{if $MASS_EDIT eq '1'}<br />{/if}

			{/if}

			{if ($fldname eq 'firstname') && ($MODULE eq 'Accounts') }
				<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$secondvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_account_name();" style="width: 70%;">

			{elseif ($fldname eq 'lastname') && ($MODULE eq 'Accounts')}
				<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$secondvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_account_name();">

			{elseif ($fldname eq 'firstname') && ($MODULE eq 'Leads') }
				<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$secondvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_lead_name();" style="width: 70%;">

			{elseif ($fldname eq 'lastname') && ($MODULE eq 'Leads') }
				<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$secondvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_lead_name();">

			{elseif ($fldname eq 'firstname') && ($MODULE eq 'Contacts') }
				<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$secondvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_contact_name();" style="width: 70%;">

			{elseif ($fldname eq 'lastname') && ($MODULE eq 'Contacts') }
				<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$secondvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onchange="set_contact_name();">

			{else}

				<input type="text" name="{$fldname}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value= "{$secondvalue}" style="width: 70%;">

			{/if}

		{elseif $uitype eq 22}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<textarea name="{$fldname}" cols="30" tabindex="{$vt_tab}" rows="2">{$fldvalue}</textarea>
			</td>

		{elseif $uitype eq 69 && ($MODULE neq 'HelpDesk')}

			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel}
				{if $MASS_EDIT eq '1'}
					<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small"  >
				{/if}
			</td>

			<td width="30%" align=left class="dvtCellInfo">
				{if $MODULE eq 'Products' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Order' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Announcement' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Accounts' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_1}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)" accept="image/png, image/gif, image/jpeg"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 1 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Contacts' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_1}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)" accept="image/png, image/gif, image/jpeg"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 1 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Leads' }

					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_1}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 1 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Questionnaire' }

					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_1}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 1 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Campaigns' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Deal' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_1}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 1 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Promotion' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Promotionvoucher' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'HelpDesk' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Faq' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'KnowledgeBase' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Competitorproduct' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Premuimproduct' }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

						{assign var=image_count value=0}
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>

				{elseif $MODULE eq 'Job' }

					{if $fldname eq 'imagename'}
						<input name="{$fldname}_hidden" id="{$fldname}_hidden" type="hidden" value="">
						<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
							<input id="my_file_element" type="file" name="{$fldname}_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

							{assign var=image_count value=0}
							{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
							   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
								<div align="center">
									<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]
									<input id="{$fldname}_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt1("{$image_details.orgname}")'>
								</div>
						   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
						   	   {/foreach}
							{/if}
						</div>

						<script>
							{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
							var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
							multi_selector.count = {$image_count}
							{*<!-- Pass in the file element -->*}
							multi_selector.addElement( document.getElementById( 'my_file_element' ),'{$fldname}' );
						</script>

					{elseif $fldname eq 'imagereceipt'}

						<input name="{$fldname}_hidden" id="{$fldname}_hidden"  type="hidden" value="" />
						<div id="new_files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
							<input id="new_file_element" type="file" name="{$fldname}_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>

							<input id="temp_fieldname" type="hidden" name="temp_fieldname" value="{$fldname}" >
							{assign var=image_count value=0}
							{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
							   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
								<div align="center">
									<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]
									<input id="{$fldname}_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt2("{$image_details.orgname}")'>
								</div>
						   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
						   	   {/foreach}
							{/if}
						</div>

						<script>
							var multi_selector2 = new MultiSelector2( document.getElementById( 'new_files_list' ), 6 );
							multi_selector2.count = {$image_count}
							multi_selector2.addElement( document.getElementById( 'new_file_element' ) ,'{$fldname}');
						</script>

					{else}

						<input name="{$fldname}"  type="file" value="{$maindata[3].0.name}" tabindex="{$vt_tab}" onchange="validateFilename(this);" />
						<input name="{$fldname}_hidden"  type="hidden" value="{$maindata[3].0.name}" />
						<input type="hidden" name="id" value=""/>
						{if $maindata[3].0.name != "" && $DUPLICATE neq 'true'}
							<div id="replaceimage">[{$maindata[3].0.orgname}] <a href="javascript:;" onClick="delimage({$ID})">Del</a></div>
						{/if}

					{/if}

				{else}

					<input name="{$fldname}"  type="file" value="{$maindata[3].0.name}" tabindex="{$vt_tab}" onchange="validateFilename(this);" />
					<input name="{$fldname}_hidden"  type="hidden" value="{$maindata[3].0.name}" />
					<input type="hidden" name="id" value=""/>
					{if $maindata[3].0.name != "" && $DUPLICATE neq 'true'}
						<div id="replaceimage">[{$maindata[3].0.orgname}] <a href="javascript:;" onClick="delimage({$ID})">Del</a></div>
					{/if}
				{/if}

			</td>

		{elseif $uitype eq 61}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel}
				{if $MASS_EDIT eq '1'}
					<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small"  disabled >
				{/if}
			</td>

			<td colspan="1" width="30%" align=left class="dvtCellInfo">
				<input name="{$fldname}"  type="file" value="{$secondvalue}" tabindex="{$vt_tab}" onchange="validateFilename(this)"/>
				<input type="hidden" name="{$fldname}_hidden" value="{$secondvalue}"/>
				<input type="hidden" name="id" value=""/>{$fldvalue}
			</td>

		{elseif $uitype eq 156}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
				{if $fldvalue eq 'on'}
					<td width="30%" align=left class="dvtCellInfo">
						{if ($secondvalue eq 1 && $CURRENT_USERID != $smarty.request.record) || ($MODE == 'create')}
							<input name="{$fldname}" tabindex="{$vt_tab}" type="checkbox" checked>
						{else}
							<input name="{$fldname}" type="hidden" value="on">
							<input name="{$fldname}" disabled tabindex="{$vt_tab}" type="checkbox" checked>
						{/if}
					</td>
				{else}
					<td width="30%" align=left class="dvtCellInfo">
						{if ($secondvalue eq 1 && $CURRENT_USERID != $smarty.request.record) || ($MODE == 'create')}
							<input name="{$fldname}" tabindex="{$vt_tab}" type="checkbox">
						{else}
							<input name="{$fldname}" disabled tabindex="{$vt_tab}" type="checkbox">
						{/if}
					</td>
				{/if}

		{elseif $uitype eq 98}<!-- Role Selection Popup -->
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			{if $thirdvalue eq 1}
				<input name="role_name" id="role_name" readonly class="txtBox" tabindex="{$vt_tab}" value="{$secondvalue}" type="text">&nbsp;
				<a href="javascript:openPopup();"><img src="{'select.gif'|@aicrm_imageurl:$THEME}" align="absmiddle" border="0"></a>
			{else}
				<input name="role_name" id="role_name" tabindex="{$vt_tab}" class="txtBox" readonly value="{$secondvalue}" type="text">&nbsp;
			{/if}
				<input name="user_role" id="user_role" value="{$fldvalue}" type="hidden">
			</td>

		{elseif $uitype eq 104}<!-- Mandatory Email Fields -->
			<td width=20% class="dvtCellLabel" align=right>
			<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			 </td>
    	     <td width=30% align=left class="dvtCellInfo"><input type="text" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
		{elseif $uitype eq 115}<!-- for Status field Disabled for nonadmin -->
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			   {if $secondvalue eq 1 && $CURRENT_USERID != $smarty.request.record}
			   	<select id="user_status" name="{$fldname}" tabindex="{$vt_tab}" class="small">
			   {else}
			   	<select id="user_status" disabled name="{$fldname}" class="small">
			   {/if}
				{foreach item=arr from=$fldvalue}
	                <option value="{$arr[1]}" {$arr[2]} >
	                        {$arr[0]}
	                </option>
				{/foreach}
			   </select>
			</td>

		{elseif $uitype eq 105}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{if $MODE eq 'edit' && $IMAGENAME neq ''}
					<input name="{$fldname}"  type="file" value="{$maindata[3].0.name}" tabindex="{$vt_tab}" onchange="validateFilename(this);" />[{$IMAGENAME}]<br>{$APP.LBL_IMG_FORMATS}
					<input name="{$fldname}_hidden"  type="hidden" value="{$IMAGENAME}" />
				{else}
					<input name="{$fldname}"  type="file" value="{$maindata[3].0.name}" tabindex="{$vt_tab}" onchange="validateFilename(this);" /><br>{$APP.LBL_IMG_FORMATS}
					<input name="{$fldname}_hidden"  type="hidden" value="{$maindata[3].0.name}" />
				{/if}
					<input type="hidden" name="id" value=""/>
					{$maindata[3].0.name}
			</td>

		{elseif $uitype eq 103}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" colspan="3" align=left class="dvtCellInfo">
				<input type="text" name="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
			</td>

		{elseif $uitype eq 101}<!-- for reportsto field USERS POPUP -->
			<td width="20%" class="dvtCellLabel" align=right>
		      <font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
            </td>
			<td width="30%" align=left class="dvtCellInfo">
			<input readonly name='reports_to_name' class="small" type="text" value='{$fldvalue}' tabindex="{$vt_tab}" ><input name='reports_to_id' type="hidden" value='{$secondvalue}'>&nbsp;<input title="Change [Alt+C]" accessKey="C" type="button" class="small" value='{$UMOD.LBL_CHANGE}' name=btn1 LANGUAGE=javascript onclick='return window.open("index.php?module=Users&action=Popup&form=UsersEditView&form_submit=false&fromlink={$fromlink}&recordid={$ID}","test","width=1000,height=603,resizable=0,scrollbars=0");'>
            </td>

		{elseif $uitype eq 116 || $uitype eq 117}<!-- for currency in users details-->
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			   {if $secondvalue eq 1 || $uitype eq 117}
			   	<select name="{$fldname}" tabindex="{$vt_tab}" class="small">
			   {else}
			   	<select disabled name="{$fldname}" tabindex="{$vt_tab}" class="small">
			   {/if}

				{foreach item=arr key=uivalueid from=$fldvalue}
					{foreach key=sel_value item=value from=$arr}
						<option value="{$uivalueid}" {$value}>{$sel_value|@getTranslatedCurrencyString}</option>
						<!-- code added to pass Currency field value, if Disabled for nonadmin -->
						{if $value eq 'selected' && $secondvalue neq 1}
							{assign var="curr_stat" value="$uivalueid"}
						{/if}
						<!--code ends -->
					{/foreach}
				{/foreach}
			   </select>
			<!-- code added to pass Currency field value, if Disabled for nonadmin -->
			{if $curr_stat neq '' && $uitype neq 117}
				<input name="{$fldname}" type="hidden" value="{$curr_stat}">
			{/if}
			<!--code ends -->
			</td>

		{elseif $uitype eq 106}
			<td width=20% class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width=30% align=left class="dvtCellInfo">
				{if $MODE eq 'edit'}
				<input type="text" readonly name="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
				{else}
				<input type="text" name="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
				{/if}
			</td>

		{elseif $uitype eq 99}
			{if $MODE eq 'create'}
			<td width=20% class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width=30% align=left class="dvtCellInfo">
				<input type="password" name="{$fldname}" tabindex="{$vt_tab}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
			</td>
			{/if}

		{elseif $uitype eq 30}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td colspan="3" width="30%" align=left class="dvtCellInfo">
				{assign var=check value=$secondvalue[0]}
				{assign var=yes_val value=$secondvalue[1]}
				{assign var=no_val value=$secondvalue[2]}

				<input type="radio" name="set_reminder" tabindex="{$vt_tab}" value="Yes" {$check}>&nbsp;{$yes_val}&nbsp;
				<input type="radio" name="set_reminder" value="No">&nbsp;{$no_val}&nbsp;

				{foreach item=val_arr from=$fldvalue}
					{assign var=start value="$val_arr[0]"}
					{assign var=end value="$val_arr[1]"}
					{assign var=sendname value="$val_arr[2]"}
					{assign var=disp_text value="$val_arr[3]"}
					{assign var=sel_val value="$val_arr[4]"}
					<select name="{$sendname}" class="small">
						{section name=reminder start=$start max=$end loop=$end step=1 }
							{if $smarty.section.reminder.index eq $sel_val}
								{assign var=sel_value value="SELECTED"}
							{else}
								{assign var=sel_value value=""}
							{/if}
							<OPTION VALUE="{$smarty.section.reminder.index}" "{$sel_value}">{$smarty.section.reminder.index}</OPTION>
						{/section}
					</select>
					&nbsp;{$disp_text}
				{/foreach}
			</td>

		{elseif $uitype eq 26}
			<td width="20%" class="dvtCellLabel" align=right>
			<font color="red">{$mandatory_field}</font>{$fldlabel}
			{if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<select name="{$fldname}" tabindex="{$vt_tab}" class="small">
					{foreach item=v key=k from=$fldvalue}
					<option value="{$k}">{$v}</option>
					{/foreach}
				</select>
			</td>
		{elseif $uitype eq 27}
			<td width="20%" class="dvtCellLabel" align="right" >
				<font color="red">{$mandatory_field}</font>{$fldlabel_other}&nbsp;
				{if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<select class="small" name="{$fldname}" onchange="changeDldType((this.value=='I')? 'file': 'text');">
					{section name=combo loop=$fldlabel}
						<option value="{$fldlabel_combo[combo]}" {$fldlabel_sel[combo]} >{$fldlabel[combo]} </option>
					{/section}
				</select>
				<script>
					function aicrm_{$fldname}Init(){ldelim}
						var d = document.getElementsByName('{$fldname}')[0];
						var type = (d.value=='I')? 'file': 'text';

						changeDldType(type, true);
					{rdelim}
					if(typeof window.onload =='function'){ldelim}
						var oldOnLoad = window.onload;
						document.body.onload = function(){ldelim}
							aicrm_{$fldname}Init();
							oldOnLoad();
						{rdelim}
					{rdelim}else{ldelim}
						window.onload = function(){ldelim}
							aicrm_{$fldname}Init();
						{rdelim}
					{rdelim}

				</script>
			</td>

		{elseif $uitype eq 28}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel}
				{if $MASS_EDIT eq '1'}
					<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small"  disabled >
				{/if}
			</td>

			<td colspan="1" width="30%" align="left" class="dvtCellInfo">
			<script type="text/javascript">
				function changeDldType(type, onInit){ldelim}
					var fieldname = '{$fldname}';
					if(!onInit){ldelim}
						var dh = getObj('{$fldname}_hidden');
						if(dh) dh.value = '';
					{rdelim}

					var v1 = document.getElementById(fieldname+'_E__');
					var v2 = document.getElementById(fieldname+'_I__');

					var text = v1.type =="text"? v1: v2;
					var file = v1.type =="file"? v1: v2;
					var filename = document.getElementById(fieldname+'_value');
					{literal}
					if(type == 'file'){
						// Avoid sending two form parameters with same key to server
						file.name = fieldname;
						text.name = '_' + fieldname;

						file.style.display = '';
						text.style.display = 'none';
						text.value = '';
						filename.style.display = '';
					}else{
						// Avoid sending two form parameters with same key to server
						text.name = fieldname;
						file.name = '_' + fieldname;

						file.style.display = 'none';
						text.style.display = '';
						file.value = '';
						filename.style.display = 'none';
						filename.innerHTML="";
					}
					{/literal}
				{rdelim}
			</script>
			<div>
				<input name="{$fldname}" id="{$fldname}_I__" type="file" value="{$secondvalue}" tabindex="{$vt_tab}" onchange="validateFilename(this)" style="display: none;"/>
				<input type="hidden" name="{$fldname}_hidden" value="{$secondvalue}"/>
				<input type="hidden" name="id" value=""/>
				<input type="text" id="{$fldname}_E__" name="{$fldname}" class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="{$secondvalue}" /><br>
				<span id="{$fldname}_value" style="display:none;">
					{if $secondvalue neq ''}
						[{$secondvalue}]
					{/if}
				</span>
			</div>
			</td>

		{elseif $uitype eq 83} <!-- Handle the Tax in Inventory -->
			{foreach item=tax key=count from=$TAX_DETAILS}
				{if $tax.check_value eq 1}
					{assign var=check_value value="checked"}
					{assign var=show_value value="visible"}
				{else}
					{assign var=check_value value=""}
					{assign var=show_value value="hidden"}
				{/if}
				<td align="right" class="dvtCellLabel" style="border:0px solid red;">
					{$tax.taxlabel} {$APP.COVERED_PERCENTAGE}
					<input type="checkbox" name="{$tax.check_name}" id="{$tax.check_name}" class="small" onclick="fnshowHide(this,'{$tax.taxname}')" {$check_value}>
				</td>
				<td class="dvtCellInfo" align="left" style="border:0px solid red;">
					<input type="text" class="detailedViewTextBox" name="{$tax.taxname}" id="{$tax.taxname}" value="{$tax.percentage}" style="visibility:{$show_value};" onBlur="fntaxValidation('{$tax.taxname}')">
				</td>
			   </tr>
			{/foreach}

			<td colspan="2" class="dvtCellInfo">&nbsp;</td>
		{/if}

<!-- <div id="dialog" style="display:none;">Dialog Content.</div> -->

<script type="text/javascript">
{literal}

function validateBranchCode(obj)
{
	var value = jQuery(obj).val()
	if (isNaN(value)) {
		alert('Branch code ต้องเป็นข้อมูลตัวเลขเท่านั้น')
		value = value.replace(/\D/g, '');
	}
	

	if (value.length > 5) {
		alert('Branch code ระบุได้ 5 หลักเท่านั้น')
		value = value.slice(0, 5);
	}

	jQuery(obj).val(value)
}

function Quickcreate(module,remodule){
	var msg = '';
	if(module == 'Accounts' ){
		if(remodule == 'Order'){
			var contact_id = document.EditView.contact_id.value;
			msg = "Quick create "+module;
			url = 'plugin/'+module+'/Quickcreate.php?contact_id='+contact_id;
		}else{
			msg = "Quick create "+module;
			url = 'plugin/'+module+'/Quickcreate.php';
		}
	}
	jQuery('#dialog_create').window({
	    title: msg,
	    width: 550,
	    height: 680,
	    closed: false,
	    cache: false,
	    href: url,
	    modal: true
	});
}//function Quickcreate

jQuery('.ws-inputreplace').keypress(

);
function isPhoneNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function format_num(id){
	jQuery('#'+id).keypress(function (event) {
			var keycode = event.which;
			if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 0 || keycode == 13 || keycode == 37 || keycode == 39 || keycode == 0 || keycode == 45 || (keycode >= 48 && keycode <= 57)))) {
				event.preventDefault();
			}
		});

		 var number = document.getElementById(id).value;

		 number=number.replace(/,/g,'');
		 val = number.split(".");

		 val1 = val[0].split("-");

		 val[0] = val[0].split("").reverse().join("");
		 val[0] = val[0].replace(/(\d{3})/g,"$1,");
		 val[0] = val[0].split("").reverse().join("");
		 val[0] = val[0].indexOf(",")==0?val[0].substring(1):val[0];

		  val[0] = val[0].indexOf(",")==0?val[0].substring(1):val[0];

		 document.getElementById(id).value = val.join(".");
	}

			webshim.setOptions('forms', {
				lazyCustomMessages: true,
				replaceValidationUI: true,
				addValidators: true
			});

			(function(){
				var stateMatches = {
					'true': true,
					'false': false,
					'auto': 'auto'
				};
				var enhanceState = (location.search.match(/enhancelist\=([true|auto|false|nopolyfill]+)/) || ['', 'auto'])[1];
				//console.log(enhanceState);
				webshim.ready('jquery', function(){
					jQuery(function () {

						jQuery('.polyfill-type select')
								.val(enhanceState)
								.on('change', function () {
									location.search = 'enhancelist=' + jQuery(this).val();
								})
						;
						if(typeof(jQuery(".cf_4748_field").val()) != 'undefined'){

							jQuery('.cf_4748_field').prop('readonly', true);
							jQuery(".cf_4748_field").css({"backgroundColor":"#CCCCCC"});
						}


					});
				});

				webshim.setOptions('forms', {
					customDatalist: stateMatches[enhanceState]
				});
				webshim.setOptions('forms-ext', {
					replaceUI: stateMatches[enhanceState]
				});

			})();

			// load the forms polyfill
			webshim.polyfill('forms forms-ext');
{/literal}

init();

</script>


