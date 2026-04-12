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
<!-- This file is used to display the fields based on the ui type in detailview -->
		{if $keyid eq '1' || $keyid eq 2 || $keyid eq '11' || $keyid eq '7' || $keyid eq '9' || $keyid eq '55' || $keyid eq '71' || $keyid eq '72' || $keyid eq '103' || $keyid eq '255' || $keyid eq '923' || $keyid eq '924' || $keyid eq '925' || $keyid eq '926' || $keyid eq '927' || $keyid eq '928'}
    <!--TextBox-->
			{if
            $keyfldname eq 'grouping' || $keyfldname eq 'business_partner' || $keyfldname eq 'customer_name' || $keyfldname eq 'search_term_1_2' || $keyfldname eq 'streets' || $keyfldname eq 'street_4' || $keyfldname eq 'street_5' || $keyfldname eq 'districts' || $keyfldname eq 'city' || $keyfldname eq 'countrys' || $keyfldname eq 'tax_id' || $keyfldname eq 'branch_code' || $keyfldname eq 'contact_person' || $keyfldname eq 'telephone' || $keyfldname eq 'fax' || $keyfldname eq 'e_mail' || $keyfldname eq 'sales_organization' || $keyfldname eq 'distribution_channel' || $keyfldname eq 'cust_pric_procedure' || $keyfldname eq 'customer_group' || $keyfldname eq 'sales_district' || $keyfldname eq 'currency' || $keyfldname eq 'shipping_condition' || $keyfldname eq 'payment_terms' || $keyfldname eq 'sales_group' || $keyfldname eq 'sg_description' || $keyfldname eq 'sales_office' || $keyfldname eq 'customer_group_1' || $keyfldname eq 'cg1_description' || $keyfldname eq 'agent_payer_1' || $keyfldname eq 'acct_clerks_tel_no' ||
            $keyfldname eq 'postal_code' ||
            $keyfldname eq 'material' ||
            $keyfldname eq 'base_unit_of_measure' ||
            $keyfldname eq 'um_coversion_m2_pcs' ||
            $keyfldname eq 'description_en' ||
            $keyfldname eq 'description_th' ||
            $keyfldname eq 'status' ||
            $keyfldname eq 'desc_status' ||
            $keyfldname eq 'valuation_class_description' ||
            $keyfldname eq 'valuation_class' ||
            $keyfldname eq 'material_group' ||
            $keyfldname eq 'mat_group' ||
            $keyfldname eq 'plant' ||
            $keyfldname eq 'sales_org' ||
            $keyfldname eq 'channel' ||
            $keyfldname eq 'mat_price_grp' ||
            $keyfldname eq 'mat_price_grp_desc' ||
            $keyfldname eq 'mat_gp2' ||
            $keyfldname eq 'mat_gp2_desciption' ||
            $keyfldname eq 'mat_gp1' ||
            $keyfldname eq 'mat_gp1_desciption' ||
            $keyfldname eq 'mat_gp3' ||
            $keyfldname eq 'mat_gp3_desciption' ||
            $keyfldname eq 'mat_gp4' ||
            $keyfldname eq 'mat_gp4_desciption' ||
            $keyfldname eq 'mat_gp5' ||
            $keyfldname eq 'mat_gp5_desciption' ||
            $keyfldname eq 'country' ||
            $keyfldname eq 'country_of_origin' ||
            $keyfldname eq 'list_price' ||
            $keyfldname eq 'piece_per_carton' ||
            $keyfldname eq 'squaremeters_per_carton' ||
            $keyfldname eq 'price_per_piece' ||
            $keyfldname eq 'price_per_squaremeter' ||
            $keyfldname eq 'quantity' ||
            $keyfldname eq 'quantity_sheet' ||
            $keyfldname eq 'checkindate'|| $keyfldname eq 'checkoutdate' || $keyfldname eq 'latlong' || $keyfldname eq 'ref_service_request_no' }
  			<td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}"  valign="top">
      {else}
			  <td width=25% class="dvtCellInfo {$keyfldname}" align="left" id="mouseArea_{$label}" data-keyid="{$keyid}" data-keyfield="{$keyfldname}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');" valign="top">
      {/if}

			{if $keyid eq '55' || $keyid eq '255'  && ($keyfldname neq 'lastname')}<!--SalutationSymbol-->
				{if $keyaccess eq $APP.LBL_NOT_ACCESSIBLE}
					<font color='red'>{$APP.LBL_NOT_ACCESSIBLE}</font>
				{else}
					{$keysalut}
				{/if}
			{/if}

			{if $keyid eq '923' || $keyid eq '924'}<!--telephonecountrycode-->
				{if $keyaccess eq $APP.LBL_NOT_ACCESSIBLE}
					<font color='red'>{$APP.LBL_NOT_ACCESSIBLE}</font>
				{else}
					{$keysalut}
				{/if}
			{/if}

			{if $keyid eq '925' || $keyid eq '926'}<!--mobilecountrycode-->
				{if $keyaccess eq $APP.LBL_NOT_ACCESSIBLE}
					<font color='red'>{$APP.LBL_NOT_ACCESSIBLE}</font>
				{else}
					{$keysalut}
				{/if}
			{/if}

			{if $keyid eq '927' || $keyid eq '928'}<!--faxcountrycode-->
				{if $keyaccess eq $APP.LBL_NOT_ACCESSIBLE}
					<font color='red'>{$APP.LBL_NOT_ACCESSIBLE}</font>
				{else}
					{$keysalut}
				{/if}
			{/if}

			{if $keyid eq 11}
				{if $USE_ASTERISK eq 'true'}
					<span id="dtlview_{$label}"><a href='javascript:;' onclick='startCall("{$keyval}")'>{$keyval}</a></span>
				{else}
					<span id="dtlview_{$label}">{$keyval}</span>
				{/if}
			{else}
      	{if $keyfldname eq 'cf_1219' || $keyfldname eq 'cf_1485'}
          <span id="dtlview_{$label}"><font color="#FF0000" size="+1" >{$keyval}</font></span>
        {elseif $keyfldname eq 'lostremark'}
          <span id="dtlview_{$label}" class="lostremark">{$keyval}</span>
        {else}
	       <span id="dtlview_{$label}">{$keyval}</span>
        {/if}
			{/if}

      {if $keyfldname neq 'latlong'}
      <div id="editarea_{$label}" style="display:none;">
      	<input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_{$label}" name="{$keyfldname}" maxlength='100' value="{$keyval}"></input><br>
        <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
      </div>
      {/if}

    {if $keyid eq '71' && $keyfldname eq 'unit_price'}
      {if $PRICE_DETAILS|@count > 0}
        <span id="multiple_currencies" width="38%" style="align:right;">
        <a href="javascript:void(0);" onclick="toggleShowHide('currency_class','multiple_currencies');">{$APP.LBL_MORE_CURRENCIES} &raquo;</a>
        </span>

        <div id="currency_class" class="multiCurrencyDetailUI">
          <table width="100%" height="100%" class="small" cellpadding="5">
          <tr>
            <th colspan="2"><b>{$MOD.LBL_PRODUCT_PRICES}</b></th>
            <th align="right">
            <img border="0" style="cursor: pointer;" onclick="toggleShowHide('multiple_currencies','currency_class');" src="{'close.gif'|@aicrm_imageurl:$THEME}"/>
            </th>
          </tr>
          <tr class="detailedViewHeader">
            <th>{$APP.LBL_CURRENCY}</th>
            <th colspan="2">{$APP.LBL_PRICE}</th>
          </tr>
            {foreach item=price key=count from=$PRICE_DETAILS}
              <tr>
                <td class="dvtCellLabel" width="40%">
                {$price.currencylabel|@getTranslatedCurrencyString} ({$price.currencysymbol})
                </td>

                <td class="dvtCellInfo" width="60%" colspan="2">
                {$price.curvalue}
                </td>
              </tr>
            {/foreach}
          </table>
        </div>
      {/if}
    {/if}
    </td>

    {elseif $keyid eq '800'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '801'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '802'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '803'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '804'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '805'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '806'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '807'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '808'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '809'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '810'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '811'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '812'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '813'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '814'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '815'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '816'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '817'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '818'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '819'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '820'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '821'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '822'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '823'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '824'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '825'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '826'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    {elseif $keyid eq '900'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="salesinvoice_no" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Salesinvoice&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.salesinvoiceid.value=''; this.form.salesinvoice_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq 901}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
        <span id="dtlview_{$label}">
          <font color="{$fontval}">{if $APP.$keyval!=''}{$APP.$keyval}{elseif $MOD.$keyval!=''}{$MOD.$keyval}{else}{$keyval}{/if}</font>
        </span>
        <div id="editarea_{$label}" style="display:none;">
          <select id="txtbox_{$label}" name="{$keyfldname}" class="small">
            <OPTION value=" " ></OPTION>
              {foreach item=itemname key=count from=$keyoptions}
                {if $itemname.product_select eq $itemname.productname}
                  {assign var=item_selected value="selected"}
                {else}
                  {assign var=item_selected value=""}
                {/if}
                <OPTION value="{$itemname.productname}"{$item_selected} >{$itemname.productname}</OPTION>
              {/foreach}
            </select>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq 902}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');"><span id="dtlview_{$label}"><font color="{$fontval}">{if $APP.$keyval!=''}{$APP.$keyval}{elseif $MOD.$keyval!=''}{$MOD.$keyval}{else}{$keyval}{/if}</font></span>
        <div id="editarea_{$label}" style="display:none;">
          <select id="txtbox_{$label}" name="{$keyfldname}" class="small" style="width:140px">
            <OPTION value=" " ></OPTION>
              {foreach item=accountname key=count from=$keyoptions}
                {if $accountname.account_select eq $accountname.accountname}
                  {assign var=account_selected value="selected"}
                {else}
                  {assign var=account_selected value=""}
                {/if}
              <OPTION value="{$accountname.accountname}" {$account_selected}>{$accountname.accountname}</OPTION>
              {/foreach}
          </select>

        <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
        <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq 903}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');"><span id="dtlview_{$label}"><font color="{$fontval}">{if $APP.$keyval!=''}{$APP.$keyval}{elseif $MOD.$keyval!=''}{$MOD.$keyval}{else}{$keyval}{/if}</font></span>
        <div id="editarea_{$label}" style="display:none;">
          <select id="txtbox_{$label}" name="{$keyfldname}" class="small" style="width:140px">
            <OPTION value=" " ></OPTION>
            {foreach item=username key=count from=$keyoptions}
              {if $username.user_select eq $username.username}
               {assign var=user_select value="selected"}
              {else}
                {assign var=user_select value=""}
              {/if}
            <OPTION value="{$username.username}" {$user_select}>{$username.username}</OPTION>
            {/foreach}
          </select>
        <br>
        <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
        <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>
    
    {elseif $keyid eq '904'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="salesinvoice_no" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Salesinvoice&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.salesinvoiceid.value=''; this.form.salesinvoice_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>
    {elseif $keyid eq '912'} <!--ProductPopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>

    {elseif $keyid eq '921'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="prevacc_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype=prevaccount","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.prevaccid.value=''; this.form.prevacc_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '929'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="{$keyfldname}_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype==accountfield&field={$keyfldname}","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$keyfldname}.value=''; this.form.{$keyfldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '930'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="{$keyfldname}_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype==accountfield&field={$keyfldname}","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$keyfldname}.value=''; this.form.{$keyfldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '931'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="{$keyfldname}_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
        </div>
      </td>

    {elseif $keyid eq '932'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>

    {elseif $keyid eq '933'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');"><span id="dtlview_{$label}">{$keyval}</span>
      <div id="editarea_{$label}" style="display:none;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <input id="txtbox_{$label}" name="{$keyfldname}"  value="{$keyval}" class="easyui-timespinner" style="width:80px;vertical-align: middle;">
          </td>
        </tr>
      </table>
      <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
      <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
      </div>
      </td>

    {elseif $keyid eq '934'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="{$keyfldname}_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
        </div>
      </td>
      <!-- <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="{$keyfldname}_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Projects&action=Popup&html=Popup_picker&popuptype==accountfield&field={$keyfldname}","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$keyfldname}.value=''; this.form.{$keyfldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td> -->

    {elseif $keyid eq '935'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="serial_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Serial&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.serialid.value=''; this.form.serial_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
        </td>

    {elseif $keyid eq '936'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="sparepart_no" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Sparepart&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.sparepartid.value=''; this.form.sparepart_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '937'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="errors_no" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Errors&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.errorsid.value=''; this.form.errors_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '938'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
        <input id="popuptxt_{$label}" name="job_no" readonly type="text" value="{$keyval}">
        <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
        <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Job&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
        <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.jobid.value=''; this.form.job_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
        <br>
        <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
        <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
    </td>

    {elseif $keyid eq '939'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="ticket_no" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=HelpDesk&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.ticketid.value=''; this.form.ticket_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '940'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>

    {elseif $keyid eq '941'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="plant_no" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Plant&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.plantid.value=''; this.form.plant_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>



    {elseif $keyid eq '943'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="lead_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Leads&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.leadid.value=''; this.form.lead_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '944'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="activitytype" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Calendar&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.activityid.value=''; this.form.activitytype.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '963'}
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
            <div id="editarea_{$label}" style="display:none;">
                <input id="popuptxt_{$label}" name="inspectiontemplate_name" readonly type="text" value="{$keyval}">
                <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
                <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}"
                    LANGUAGE=javascript
                    onclick='return window.open("index.php?module=Inspectiontemplate&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");'
                    align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
                <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}"
                      title="{$APP.LBL_CLEAR}" LANGUAGE=javascript
                      onClick="this.form.jobid.value=''; this.form.job_no.value=''; return false;" align="absmiddle"
                      style='cursor:hand;cursor:pointer'>
                <br>
                <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}"
                      onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')"
                  class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
            </div>
        </td>
        
    {elseif $keyid eq '301'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="deal_no" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Deal&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.dealid.value=''; this.form.deal_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '302'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a class="competitorid" href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="deal_no" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Competitor&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.competitorid.value=''; this.form.competitor_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '303'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="promotionvoucher_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Promotionvoucher&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript  onClick="this.form.{$keyfldname}.value=''; this.form.{$keyfldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '304'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="promotion_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Promotion&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.promotionid.value=''; this.form.promotion_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '305'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="salesorder_no" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Salesorder&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.salesorderid.value=''; this.form.salesorder_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '306'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="premuimproduct_no" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Premuimproduct&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.premuimproductid.value=''; this.form.premuimproduct_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '307'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="quote_no" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.premuimproductid.value=''; this.form.premuimproduct_no.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '308'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="servicerequest_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Servicerequest&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.servicerequestid.value=''; this.form.servicerequest_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '309'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="ticket_title" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=HelpDesk&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.ticketid.value=''; this.form.ticket_title.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '310'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="activity_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Calendar&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.activityid.value=''; this.form.activity_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>
      
    {elseif $keyid eq '946'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="questionnairetemplate_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Questionnairetemplate&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.questionnairetemplateid.value=''; this.form.questionnairetemplate_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '947'}

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="questionnaire_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Questionnairetemplate&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.questionnaireid.value=''; this.form.questionnaire_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '948'}
      <!-- <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}"  valign="top"></td> -->
      </tr>
      <tr style="height:200px">
      <td  colspan="4" class="dvtCellInfo" align="left" id="mouseArea_{$label}"  valign="top"><span id="dtlview_{$label}">{$keyval}</span></td>

    {elseif $keyid eq '949'}

      <td colspan="3" class="dvtCellInfo" align="left" id="mouseArea_{$label}"  valign="top"><span id="dtlview_{$label}">{$keyval}</span></td>

    {elseif $keyid eq '13' || $keyid eq '104'} <!--Email-->
        {if $keyfldname eq 'email_bounce'}
          <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}">
        {else}
          <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
          <span id="dtlview_{$label}">
        {/if}

        {if $smarty.session.internal_mailer eq 1}
          <a href="javascript:InternalMailer({$ID},{$keyfldid},'{$keyfldname}','{$MODULE}','record_id');">{$keyval}</a>
        {else}
          <a href="mailto:{$keyval}" target="_blank" >{$keyval}</a>
        {/if}
          </span>
            <div id="editarea_{$label}" style="display:none;">
              <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_{$label}" name="{$keyfldname}" maxlength='100' value="{$keyval}"></input>
              <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
              <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
            </div>
            <div id="internal_mailer_{$keyfldname}" style="display: none;">{$keyfldid}####{$smarty.session.internal_mailer}</div>
          </td>

    {elseif $keyid eq '15' || $keyid eq '16'} <!--ComboBox-->

        {foreach item=arr from=$keyoptions}
          {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE && $arr[2] eq 'selected'}
            {assign var=keyval value=$APP.LBL_NOT_ACCESSIBLE}
            {assign var=fontval value='red'}
          {else}
            {assign var=fontval value=''}
          {/if}
        {/foreach}

        {php}
          global  $chkfield;
        {/php}

        {if $MODULE eq 'HelpDesk' && ($keyfldname eq 'email_status' || $keyfldname eq 'sms_status')}
            {php} if($chkfield=="1"){{/php}
              <td width=25% class="dvtCellInfo" align="left" >
            {php} }else{{/php}
              <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
            {php} }{/php}
        {else}
          {if $keyfldname eq 'email_status' || $keyfldname eq 'sms_status' || $keyfldname eq 'job_type' || $keyfldname eq 'stage' }<!-- || $keyfldname eq 'sms_sender_name'  -->
            <td width=25% class="dvtCellInfo" align="left" >
          {else}
            <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
          {/if}
        {/if}

        {*if $keyfldname eq 'sms_sender_name' }
          <span id="dtlview_{$label}"><font color="{$fontval}">{if $APP.$keyval!=''}{$APP.$keyval}{elseif $MOD.$keyval!=''}{$MOD.$keyval}{else}{$keyval}{/if}</font></span>
        {elseif $keyfldname eq 'stage'*}

        {if $keyfldname eq 'stage'}
          <span id="dtlview_{$label}"><font color="{$fontval}" class="stage">{if $APP.$keyval!=''}{$APP.$keyval}{elseif $MOD.$keyval!=''}{$MOD.$keyval}{else}{$keyval}{/if}</font></span>
        {elseif $keyfldname eq 'lostreason'  }
          <span id="dtlview_{$label}"><font color="{$fontval}" class="lostreason">{if $APP.$keyval!=''}{$APP.$keyval}{elseif $MOD.$keyval!=''}{$MOD.$keyval}{else}{$keyval}{/if}</font></span>
        {else}
          <span id="dtlview_{$label}"><font color="{$fontval}" class="{$keyfldname}">{if $APP.$keyval!=''}{$APP.$keyval}{elseif $MOD.$keyval!=''}{$MOD.$keyval}{else}{$keyval}{/if}</font></span>
        {/if}

        <div id="editarea_{$label}" style="display:none;">
           <select id="txtbox_{$label}" name="{$keyfldname}" class="small">
            {foreach item=arr from=$keyoptions}
              {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
                <option value="{$arr[0]}" {$arr[2]}>{$arr[0]}</option>
              {else}
                <option value="{$arr[1]}" {$arr[2]}>
                {$arr[0]}
                </option>
              {/if}

            {/foreach}
           </select>

            <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                 <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>

      </td>

    {elseif $keyid eq '33'}<!--Multi Select Combo box-->
    <!--code given by Neil start Ref:http://forums.vtiger.com/viewtopic.php?p=31096#31096-->
    <!--{assign var="MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING" value=", "}  {* Separates Multi-Select Combo Box items *}
    {assign var="DETAILVIEW_WORDWRAP_WIDTH" value="70"} {* No. of chars for word wrapping long lines of Multi-Select Combo Box items *}-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');"><span id="dtlview_{$label}">
        {foreach item=sel_val from=$keyoptions }
          {if $sel_val[2] eq 'selected'}
            {if $selected_val neq ''}
              {assign var=selected_val value=$selected_val|cat:', '}
            {/if}
          {assign var=selected_val value=$selected_val|cat:$sel_val[0]}
          {/if}
        {/foreach}
        {$selected_val|replace:"\n":"<br>&nbsp;&nbsp;"}
      <!-- commented to fix ticket4631 -using wordwrap will affect Not Accessible font color -->
      <!--{$selected_val|replace:$MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING:"\x1"|replace:" ":"\x0"|replace:"\x1":$MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING|wordwrap:$DETAILVIEW_WORDWRAP_WIDTH:"<br>&nbsp;"|replace:"\x0":"&nbsp;"}-->
        </span>
      <!--code given by Neil End-->
        <div id="editarea_{$label}" style="display:none;">
          <select MULTIPLE id="txtbox_{$label}" name="{$keyfldname}" size="4" style="width:160px;" class="small">
            {foreach item=arr from=$keyoptions}
              <option value="{$arr[1]}" {$arr[2]}>{$arr[0]}</option>
            {/foreach}
          </select>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '115'} <!--ComboBox Status edit only for admin Users-->
      <td width=25% class="dvtCellInfo" align="left">{$keyval}</td>

    {elseif $keyid eq '116' || $keyid eq '117'} <!--ComboBox currency id edit only for admin Users-->
      {if $keyadmin eq 1 || $keyid eq '117'}
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
        <span id="dtlview_{$label}">{$keyval}</span>
          <div id="editarea_{$label}" style="display:none;">
          <select id="txtbox_{$label}" name="{$keyfldname}" class="small">
            {foreach item=arr key=uivalueid from=$keyoptions}
              {foreach key=sel_value item=value from=$arr}
                <option value="{$uivalueid}" {$value}>{$sel_value|@getTranslatedCurrencyString}</option>
              {/foreach}
            {/foreach}
          </select>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
          </div>
      {else}
        <td width=25% class="dvtCellInfo" align="left">{$keyval}
      {/if}
        </td>
    {elseif $keyid eq '17'} <!--WebSite-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
      <span id="dtlview_{$label}"><a href="http://{$keyval}" target="_blank">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
        <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" onkeyup="validateUrl('{$keyfldname}');" type="text" id="txtbox_{$label}" name="{$keyfldname}" maxlength='100' value="{$keyval}"></input>
        <br>
        <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
        <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '85'}<!--Skype-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
        <img src="{'skype.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SKYPE}" title="{$APP.LBL_SKYPE}" LANGUAGE=javascript align="absmiddle"></img>
        <span id="dtlview_{$label}"><a href="skype:{$keyval}?call">{$keyval}</a></span>
          <div id="editarea_{$label}" style="display:none;">
          <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_{$label}" name="{$keyfldname}" maxlength='100' value="{$keyval}"></input>
          <br>email_message
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
          </div>
      </td>

    {elseif $keyid eq '19' || $keyid eq '20'} <!--TextArea/Description-->
    <!-- we will empty the value of ticket and faq comment -->
      {if $label eq $MOD.LBL_ADD_COMMENT || $label eq 'Comment Plan' || $keyfldname eq 'comments'}
        {assign var=keyval value=''}
      {/if}
      <!--{assign var="DESCRIPTION_SEPARATOR_STRING" value=" "}  {* Separates Description *}-->
      <!--{assign var="DESCRIPTION_WORDWRAP_WIDTH" value="70"} {* No. of chars for word wrapping long lines of Description *}-->

        {if $MODULE eq 'Documents'}
          <!--To give hyperlink to URL-->
          <td width="100%" colspan="3" class="dvtCellInfo" align="left">{$keyval|regex_replace:"/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/":"\\1<a href=\"\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/":"\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i":"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"|regex_replace:"/,\"|\.\"|\)\"|\)\.\"|\.\)\"/":"\""|replace:"\n":"<br>&nbsp;"}&nbsp;
          </td>
        {else}

        {if $keyfldname eq 'know_detail'}
          <td width="100%" colspan="3" class="dvtCellInfo" align="left">
          <span id="dtlview_{$label}">{$keyval|regex_replace:"/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/":"\\1<a href=\"\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/":"\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i":"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"|regex_replace:"/,\"|\.\"|\)\"|\)\.\"|\.\)\"/":"\""|replace:"\n":""}
          </span>

        {elseif $keyfldname eq 'email_message'}
          <td width="100%" colspan="3" class="dvtCellInfo" align="left">
          <span id="dtlview_{$label}">{$keyval|regex_replace:"/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/":"\\1<a href=\"\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/":"\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i":"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"|regex_replace:"/,\"|\.\"|\)\"|\)\.\"|\.\)\"/":"\""|replace:"\n":""}
          </span>

        {elseif $keyfldname eq 'sms_message'}
          <td width="100%" colspan="3" class="dvtCellInfo" align="left">
          <span id="dtlview_{$label}">{$keyval|regex_replace:"/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/":"\\1<a href=\"\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/":"\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i":"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"|regex_replace:"/,\"|\.\"|\)\"|\)\.\"|\.\)\"/":"\""|replace:"\n":""}
          </span>

        {elseif $keyfldname eq 'camp_detail'}
          <td width="100%" colspan="3" class="dvtCellInfo" align="left">
          <span id="dtlview_{$label}">{$keyval|regex_replace:"/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/":"\\1<a href=\"\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/":"\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i":"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"|regex_replace:"/,\"|\.\"|\)\"|\)\.\"|\.\)\"/":"\""|replace:"\n":""}
          </span>

        {elseif $keyfldname eq 'description'}
          <td width="100%" colspan="3" class="dvtCellInfo" align="left">
          <span id="dtlview_{$label}">{$keyval|regex_replace:"/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/":"\\1<a href=\"\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/":"\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i":"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"|regex_replace:"/,\"|\.\"|\)\"|\)\.\"|\.\)\"/":"\""|replace:"\n":""}
          </span>

        {else}
          <td width="100%" colspan="3" class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');"><span id="dtlview_{$label}">{$keyval|regex_replace:"/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/":"\\1<a href=\"\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/":"\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i":"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"|regex_replace:"/,\"|\.\"|\)\"|\)\.\"|\.\)\"/":"\""|replace:"\n":"<br>&nbsp;"}
          </span>
        {/if}

        <div id="editarea_{$label}" style="display:none;">
          <textarea id="txtbox_{$label}" name="{$keyfldname}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" cols="90" rows="8">{$keyval|replace:"<br>":"\n"}</textarea>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>
    {/if}

    {elseif $keyid eq '21' || $keyid eq '24' || $keyid eq '22'} <!--TextArea/Street-->
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');"><span id="dtlview_{$label}">{$keyval}</span>
          <div id="editarea_{$label}" style="display:none;">
            <textarea id="txtbox_{$label}" name="{$keyfldname}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" rows=2>{$keyval|regex_replace:"/<br\s*\/>/":""}</textarea>
            <br>
            <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
            <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
          </div>
        </td>

    {elseif $keyid eq '50' || $keyid eq '73' || $keyid eq '51'} <!--AccountPopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}"><a href="{$keyseclink}">{$keyval}</a></td>

    {elseif $keyid eq '58'} <!--CampaingPopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}"><a href="{$keyseclink}">{$keyval}</a></td>

    {elseif $keyid eq '57'} <!--ContactPopup-->
    <!-- Ajax edit link not provided for contact - Reports To -->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}"><a href="{$keyseclink}">{$keyval}</a></td>

    {elseif $keyid eq '59'} <!--ProductPopup-->
    <!-- <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');"><span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseout="fnhide('crmspanid');"><span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>
                <textarea   id="popuptxt_{$label}" name="product_name" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" readonly="readonly" style="height:60px"  >{$fldvalue}</textarea>
                <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}"></td>
              <td><img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
              <td>
                <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
              </td>
            </tr>
          </table>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
    </td>

    {elseif $keyid eq '75' || $keyid eq '81'} <!--VendorPopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}"><a href="{$keyseclink}">{$keyval}</a></td>

    {elseif $keyid eq 76} <!--PotentialPopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}"><a href="{$keyseclink}">{$keyval}</a></td>

    {elseif $keyid eq 78} <!--QuotePopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}"><a href="{$keyseclink}">{$keyval}</a></td>

    {elseif $keyid eq 82} <!--Email Body-->
      <td colspan="3" width=100% class="dvtCellInfo" align="left"><div id="dtlview_{$label}" style="width:100%;height:200px;overflow:hidden;border:1px solid gray" class="detailedViewTextBox" onmouseover="this.className='detailedViewTextBoxOn'" onmouseout="this.className='detailedViewTextBox'">{$keyval}</div></td>

    {elseif $keyid eq 80} <!--SalesOrderPopup-->
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}"><a href="{$keyseclink}">{$keyval}</a></td>

    {elseif $keyid eq '52' || $keyid eq '77'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
        <span id="dtlview_{$label}">{$keyval}</span>
        <div id="editarea_{$label}" style="display:none;">
          <select id="txtbox_{$label}" name="{$keyfldname}" class="small">
          {foreach item=arr key=uid from=$keyoptions}
            {foreach key=sel_value item=value from=$arr}
              <option value="{$uid}" {$value}>{if $APP.$sel_value}{$APP.$sel_value}{else}{$sel_value}{/if}</option>
            {/foreach}
          {/foreach}
          </select>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '53'} <!--Assigned To-->
      <td width=25% class="dvtCellInfo" align="left" >
        {if $keyadmin eq 1}
          <a href="{$keyseclink.0}">{$keyval}</a>
        {else}
          {$keyval}
        {/if}

        <div id="editarea_{$label}" style="display:none;">
          <input type="hidden" id="hdtxt_{$label}" value="{$keyval}"></input>

          {if $keyoptions.0 eq 'User'}
            <input name="assigntype" id="assigntype" checked="checked" value="U" onclick="toggleAssignType(this.value),setSelectValue('{$label}');" type="radio">&nbsp;{$APP.LBL_USER}
            {if $keyoptions.2 neq ''}
              <input name="assigntype" id="assigntype" value="T" onclick="toggleAssignType(this.value),setSelectValue('{$label}');" type="radio">&nbsp;{$APP.LBL_GROUP_NAME}
            {/if}
            <span id="assign_user" style="display: block;">
          {else}
            <input name="assigntype" id="assigntype" value="U" onclick="toggleAssignType(this.value),setSelectValue('{$label}');" type="radio">&nbsp;{$APP.LBL_USER}
            <input name="assigntype" checked="checked" id="assigntype" value="T" onclick="toggleAssignType(this.value),setSelectValue('{$label}');" type="radio">&nbsp;{$APP.LBL_GROUP_NAME}
            <span id="assign_user" style="display: none;">
          {/if}

            <select id="txtbox_U{$label}" onchange="setSelectValue('{$label}')" name="{$keyfldname}" class="small">
            {foreach item=arr key=id from=$keyoptions.1}
              {foreach key=sel_value item=value from=$arr}
                <option value="{$id}" {$value}>{$sel_value}</option>
              {/foreach}
            {/foreach}
            </select>
            </span>

          {if $keyoptions.0 eq 'Group'}
            <span id="assign_team" style="display: block;">
          {else}
            <span id="assign_team" style="display: none;">
          {/if}

          <select id="txtbox_G{$label}" onchange="setSelectValue('{$label}')" name="assigned_group_id" class="groupname small">
          {foreach item=arr key=id from=$keyoptions.2}
            {foreach key=sel_value item=value from=$arr}
              <option value="{$id}" {$value}>{$sel_value}</option>
            {/foreach}
          {/foreach}
          </select>
        </span>
        <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '99'}<!-- Password Field-->
      <td width=25% class="dvtCellInfo" align="left">{$CHANGE_PW_BUTTON}</td>

    {elseif $keyid eq '56'} <!--CheckBox-->
      {if $keyfldname eq 'email_setup' ||  $keyfldname eq 'email_send' || $keyfldname eq 'setup_sms' ||  $keyfldname eq 'send_sms' || $keyfldname eq 'passed_inspection'}
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" >
      {else}
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onMouseOver="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
      {/if}
      <span id="dtlview_{$label}">{$keyval}&nbsp;</span>
      <div id="editarea_{$label}" style="display:none;">
      {if $keyval eq 'yes'}
        <input id="txtbox_{$label}" name="{$keyfldname}" type="checkbox" style="border:1px solid #bababa;" checked value="0">
      {else}
        <input id="txtbox_{$label}" type="checkbox" name="{$keyfldname}" style="border:1px solid #bababa;" value="1">
      {/if}
        <br>
        <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');"/> {$APP.LBL_OR}
        <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
      </div>
    </td>

    {elseif $keyid eq '156'} <!--CheckBox for is admin-->
      {if $smarty.request.record neq $CURRENT_USERID && $keyadmin eq 1}
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onMouseOver="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');"><span id="dtlview_{$label}">{if $APP.$keyval!=''}{$APP.$keyval}{elseif $MOD.$keyval!=''}{$MOD.$keyval}{else}{$keyval}{/if}&nbsp;</span>
          <div id="editarea_{$label}" style="display:none;">
          {if $keyval eq 'on'}
            <input id="txtbox_{$label}" name="{$keyfldname}" type="checkbox" style="border:1px solid #bababa;" checked value="1">
          {else}
            <input id="txtbox_{$label}" type="checkbox" name="{$keyfldname}" style="border:1px solid #bababa;" value="0">
          {/if}
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
          </div>
      {else}
        <td width=25% class="dvtCellInfo" align="left">{$keyval}
      {/if}
      </td>

    {elseif $keyid eq 83}<!-- Handle the Tax in Inventory -->
      {foreach item=tax key=count from=$TAX_DETAILS}
        <td align="right" class="dvtCellLabel">
        {$tax.taxlabel} {$APP.COVERED_PERCENTAGE}
        </td>
        <td class="dvtCellInfo" align="left">
        {$tax.percentage}
        </td>
        <td colspan="2" class="dvtCellInfo">&nbsp;</td>
      </tr>
      {/foreach}

    {elseif $keyid eq 5}
    {* Initialize the date format if not present *}

      {if empty($dateFormat)}
        {assign var="dateFormat" value=$APP.NTC_DATE_FORMAT|@parse_calendardate}
      {/if}

      {if $keyfldname eq 'approved_date'}
       <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}">
      {else}
       <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
      {/if}

      <span id="dtlview_{$label}">{$keyval}</span>

      <div id="editarea_{$label}" style="display:none;">
        <input style="border: 1px solid #bababa;border-radius: 3px;color: #2b2f33;background-color: #FFF;padding: 2px;line-height: 20px;" size="11" maxlength="10" type="text" id="txtbox_{$label}" name="{$keyfldname}" maxlength='100' value="{$keyval|regex_replace:'/[^-]*(--)[^-]*$/':''}"></input>
        <img src="{'btnL3Calendar.gif'|@aicrm_imageurl:$THEME}" id="jscal_trigger_{$keyfldname}" style="vertical-align: middle;">
      <br>
      <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
      <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>

      <script type="text/javascript">
        Calendar.setup ({ldelim}
        inputField : "txtbox_{$label}", ifFormat : '{$dateFormat}', showsTime : false, button : "jscal_trigger_{$keyfldname}", singleClick : true, step : 1
        {rdelim})
      </script>

      </div>
    </td>

    {elseif $keyid eq 69}<!-- for Image Reflection -->
      <td align="left" width=25%">{$keyval}</td>
    {elseif $keyid eq 997}
      <td  align="left" width=25%">&nbsp;{$keyval}</td>
    {elseif $keyid eq 998}
      <td  align="left" width=25%">&nbsp;{$keyval}</td>
    {elseif $keyid eq 999}
      <td  align="left" width=25%">&nbsp;{$keyval}</td>
    {else}
      <td class="dvtCellInfo" align="left" width=25%">{$keyval}</td>
    {/if}

