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
		{if $keyid eq '1' || $keyid eq 2 || $keyid eq '11' || $keyid eq '7' || $keyid eq '9' || $keyid eq '55' || $keyid eq '71' || $keyid eq '72' || $keyid eq '255'} <!--TextBox-->
			<td width=25% class="dvtCellInfo" align="left">
				{if $keyid eq '55' || $keyid eq '255'  && ($keyfldname neq 'lastname')}<!--SalutationSymbol-->
					{if $keyaccess eq $APP.LBL_NOT_ACCESSIBLE}
						<font color='red'>{$APP.LBL_NOT_ACCESSIBLE}</font>
					{else}
						{$keysalut}
					{/if}
				{*elseif $keyid eq '71' || $keyid eq '72'}  <!--CurrencySymbol-->
					{$keycursymb*}
				{/if}

        {if $keyid eq 7}
          <span id ="dtlview_{$label}">{$keyval}</span>
      	    <!-- <span id="dtlview_{$label}">{$keyval|number_format:2:".":","}</span> -->
        {else}
					   <span id ="dtlview_{$label}">{$keyval}</span>
				{/if}
        
        {if $keyid eq '71' && $keyfldname eq 'unit_price'}
            {if $PRICE_DETAILS|@count > 0}
              <span id="multiple_currencies" width="38%" style="align:right;">
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="toggleShowHide('currency_class','multiple_currencies');">{$APP.LBL_MORE_CURRENCIES} &raquo;</a>
              </span>

            <div id="currency_class" class="multiCurrencyDetailUI">
            <table width="100%" height="100%" class="small" cellpadding="5">
              <tr class="detailedViewHeader">
                <th colspan="2">
                <b>{$MOD.LBL_PRODUCT_PRICES}</b>
                </th>
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
                  {$price.currencylabel} ({$price.currencysymbol})
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

    {elseif $keyid eq '73'} <!--AccountPopup-->
      {if $MODULE eq 'Projects'}
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}">
          {if $display_acc eq 'yes'}
            <a href="{$keyseclink}">{$keyval}</a>
          {else}
            {$keyval}
          {/if}
        </td>
      {else}
        <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}"><a href="{$keyseclink}">{$keyval}</a></td>
      {/if}

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
          <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
      	   <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq 902}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
        <span id="dtlview_{$label}">
          <font color="{$fontval}">{if $APP.$keyval!=''}{$APP.$keyval}{elseif $MOD.$keyval!=''}{$MOD.$keyval}{else}{$keyval}{/if}</font>
        </span>
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
        <br>
        <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
        <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>
    {elseif $keyid eq 903}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
        <span id="dtlview_{$label}">
          <font color="{$fontval}">{if $APP.$keyval!=''}{$APP.$keyval}{elseif $MOD.$keyval!=''}{$MOD.$keyval}{else}{$keyval}{/if}</font>
        </span>
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
         
    {elseif $keyid eq '929'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="{$keyfldname}_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype==accountfield&field={$keyfldname}","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$keyfldname}.value=''; this.form.{$keyfldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

    {elseif $keyid eq '930'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="{$keyfldname}_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&html=Popup_picker&popuptype=accountfield&field={$keyfldname}","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
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
          <input id="popuptxt_{$label}" name="{$keyfldname}_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Projects&action=Popup&html=Popup_picker&popuptype==accountfield&field={$keyfldname}","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.{$keyfldname}.value=''; this.form.{$keyfldname}_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>
    
    {elseif $keyid eq '939'}
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span></td>
    
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

      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
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
      
		{elseif $keyid eq '13'} <!--Email-->
			<td width=25% class="dvtCellInfo" align="left">
				{if $smarty.session.internal_mailer eq 1}
					<a href="javascript:InternalMailer({$ID},{$keyfldid},'{$keyfldname}','{$MODULE}','record_id');">{$keyval}</a>
				{else}
					<a href="mailto:{$keyval}" target="_blank" >{$keyval}</a>
				{/if}
				</span>
			</td>

		{elseif $keyid eq '15' || $keyid eq '16'} 
      <td width=25% class="dvtCellInfo" align="left">&nbsp;
        {foreach item=arr from=$keyoptions}
          {if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
            {assign var=keyval value=$APP.LBL_NOT_ACCESSIBLE}
            {assign var=fontval value='red'}
          {else}
            {assign var=fontval value=''}
          {/if}
        {/foreach}
        <font color="{$fontval}">{$keyval}</font>
      </td>

		{elseif $keyid eq '33'}
			<td width=25% class="dvtCellInfo" align="left">
				{foreach item=sel_val from=$keyoptions }
					{if $sel_val[2] eq 'selected'}
						{if $selected_val neq ''}
							{assign var=selected_val value=$selected_val|cat:', '}
					 	{/if}
						{assign var=selected_val value=$selected_val|cat:$sel_val[0]}
					{/if}
				{/foreach}
				{$selected_val|replace:"\n":"<br>&nbsp;&nbsp;"}
			</td>

		{elseif $keyid eq '17'} <!--WebSite-->
			<td width=25% class="dvtCellInfo" align="left">&nbsp;<a href="http://{$keyval}" target="_blank">{$keyval}</a></td>

		{elseif $keyid eq '85'}<!--Skype-->
			<td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}">
				&nbsp;<img src="{'skype.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SKYPE}" title="{$APP.LBL_SKYPE}" LANGUAGE=javascript align="absmiddle"></img>
				<span id="dtlview_{$label}"><a href="skype:{$keyval}?call">{$keyval}</a></span>
			</td>

		{elseif $keyid eq '19' || $keyid eq '20'} <!--TextArea/Description-->
			{if $label eq $MOD.LBL_ADD_COMMENT || $label eq 'Comment Plan' || $keyfldname eq 'comments'}
        {assign var=keyval value=''}
      {/if}

      {if $keyfldname eq 'quota_notapprove'}
        <td width=100% class="dvtCellInfo" align="left"colspan="3">
          <font color="#FF0000">{$keyval|regex_replace:"/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/":"\\1<a href=\"\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/":"\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i":"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"|regex_replace:"/,\"|\.\"|\)\"|\)\.\"|\.\)\"/":"\""|replace:"\n":"<br>&nbsp;"}
          </font>
        </td>
      {elseif $keyfldname eq 'quota_cancel'}
        <td width=100% class="dvtCellInfo" align="left"colspan="3">
          <font color="#FF0000">{$keyval|regex_replace:"/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/":"\\1<a href=\"\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/":"\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i":"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"|regex_replace:"/,\"|\.\"|\)\"|\)\.\"|\.\)\"/":"\""|replace:"\n":"<br>&nbsp;"}
          </font>
        </td>
      {else}
  			<td width=100% class="dvtCellInfo" align="left"colspan="3">
  				<!--To give hyperlink to URL-->
  				{$keyval|regex_replace:"/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/":"\\1<a href=\"\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/":"\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i":"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"|regex_replace:"/,\"|\.\"|\)\"|\)\.\"|\.\)\"/":"\""|replace:"\n":"<br>&nbsp;"}
  			</td>
      {/if}

		{elseif $keyid eq '21' || $keyid eq '24' || $keyid eq '22'} <!--TextArea/Street-->
			 	
        <td width=25% class="dvtCellInfo" align="left">&nbsp;<span id ="dtlview_{$label}">{$keyval}</span></td>

		{elseif $keyid eq '50' || $keyid eq '51' || $keyid eq '57' || $keyid eq '59' || $keyid eq '75' || $keyid eq '81' || $keyid eq '76' || $keyid eq '78' || $keyid eq '80'} <!--AccountPopup-->

    {if $keyid eq '57' && $MODULE eq 'Projects'}
      <td width=25% class="dvtCellInfo" align="left">
        {if $display_con eq 'yes'}
          <a href="{$keyseclink}">{$keyval}</a>
        {else}
          {$keyval}
        {/if}
      </td>
    {else}
			<td width=25% class="dvtCellInfo" align="left"><a href="{$keyseclink}">{$keyval}</a></td>
    {/if}
		
    {elseif $keyid eq 82} <!--Email Body-->
			<td colspan="3" width=100% class="dvtCellInfo" align="left">{$keyval}</td>

		{elseif $keyid eq '53'} <!--Assigned To-->
      <td width=25% class="dvtCellInfo" align="left">
        {if $keyseclink eq ''}
            {$keyval}
        {else}
           	<a href="{$keyseclink.0}">{$keyval}</a>
        {/if}
	     &nbsp;
      </td>

		{elseif $keyid eq '56'} <!--CheckBox-->
			<td width=25% class="dvtCellInfo" align="left">{$keyval}&nbsp;</td>
    
    {elseif $keyid eq '58'}
      
      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" ><a href="{$keyseclink}">{$keyval}</a></span>
        <div id="editarea_{$label}" style="display:none;">
          <input id="popuptxt_{$label}" name="campaign_name" readonly type="text" value="{$keyval}">
          <input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;
          <img src="{'select.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Campaign&action=Popup&html=Popup_picker&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
          <input type="image" src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.campaignid.value=''; this.form.campaign_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
          <br>
          <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
        </div>
      </td>

		{elseif $keyid eq 83}<!-- Handle the Tax in Inventory -->
				<td align="right" class="dvtCellLabel">
					{$APP.LBL_VAT} {$APP.COVERED_PERCENTAGE}
				</td>
				<td class="dvtCellInfo" align="left">&nbsp;
					{$VAT_TAX}
				</td>
				<td colspan="2" class="dvtCellInfo">&nbsp;</td>
			</tr>
			<!--<tr>
				<td align="right" class="dvtCellLabel">
					{$APP.LBL_SALES} {$APP.LBL_TAX} {$APP.COVERED_PERCENTAGE}
				</td>
				<td class="dvtCellInfo" align="left">&nbsp;
					{$SALES_TAX}
				</td>
				<td colspan="2" class="dvtCellInfo">&nbsp;</td>
			</tr>
			<tr>
				<td align="right" class="dvtCellLabel">
					{$APP.LBL_SERVICE} {$APP.LBL_TAX} {$APP.COVERED_PERCENTAGE}
				</td>
				<td class="dvtCellInfo" align="left" >&nbsp;
					{$SERVICE_TAX}
				</td>-->

		{elseif $keyid eq 69}<!-- for Image Reflection -->
			<td align="left" width=25%">{$keyval}</td>
		{else}
			<td class="dvtCellInfo" align="left" width=25%">{$keyval}</td>
		{/if}
