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
{*<!-- module header -->*}
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-{$CALENDAR_LANG}.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="include/js/Inventory.js"></script>
<script type="text/javascript" src="include/js/FieldDependencies.js"></script>

<script type="text/javascript" src="asset/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="asset/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>

<script type="text/javascript" src="asset/js/polyfiller.js"></script>

<link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css">

<script type="text/javascript">
	jQuery.noConflict();
</script>

{if $PICKIST_DEPENDENCY_DATASOURCE neq ''}
<script type="text/javascript">
	jQuery(document).ready(function() {ldelim} (new FieldDependencies({$PICKIST_DEPENDENCY_DATASOURCE})).init() {rdelim});
</script>
{/if}

<script type="text/javascript">
function sensex_info()
{ldelim}
        var Ticker = $('tickersymbol').value;
        if(Ticker!='')
        {ldelim}
		$("vtbusy_info").style.display="inline";
		new Ajax.Request(
          	      'index.php',
                      {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                      		method: 'post',
                        	postBody: 'module={$MODULE}&action=Tickerdetail&tickersymbol='+Ticker,
	                        onComplete: function(response) {ldelim}
					$('autocom').innerHTML = response.responseText;
				        $('autocom').style.display="block";
        	                        $("vtbusy_info").style.display="none";
                	        {rdelim}
                	{rdelim}
        	);
        {rdelim}
{rdelim}

</script>

{include file='Buttons_List1.tpl'}

{*<!-- Contents -->*}
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
   <tr>
	<td valign=top>
		<img src="{'showPanelTopLeft.gif'|@aicrm_imageurl:$THEME}">
	</td>

	<td class="showPanelBg" valign=top width=100%>
	     {*<!-- PUBLIC CONTENTS STARTS-->*}
		{include file='EditViewHidden.tpl'}
	     <div class="small" style="padding:20px">

		 {if $OP_MODE eq 'edit_view'}
			 <span class="lvtHeaderText"><font color="purple">[ {$ID} ] </font>{$NAME} -  {$APP.LBL_EDITING} {$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</span> <br>
			{$UPDATEINFO}
		 {/if}
		 
		 {if $OP_MODE eq 'create_view'}
			{if $DUPLICATE neq 'true' && $REVISE neq 'true'}
            <span class="lvtHeaderText">{$APP.LBL_CREATING} {$APP.LBL_NEW} {$SINGLE_MOD|@getTranslatedString:$MODULE}</span> <br>
			{else}
            	{if $DUPLICATE eq 'true'}
            	<span class="lvtHeaderText">{$APP.LBL_DUPLICATING} "{$NAME}" </span> <br>
                {else}
                <span class="lvtHeaderText">Revising "{$NAME}" </span> <br>
                {/if}
			{/if}
		 {/if}

		 <hr noshade size=1>
		 <br>



		{*<!-- Account details tabs -->*}
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
		   <tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
				   <tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>

					{if $BLOCKS_COUNT eq 2}
						<td width=75 style="width:15%" align="center" nowrap class="dvtSelectedCell" id="bi" onclick="fnLoadValues('bi','mi','basicTab','moreTab','inventory','{$MODULE}')"><b>{$APP.LBL_BASIC} {$APP.LBL_INFORMATION}</b></td>
                    				<td class="dvtUnSelectedCell" style="width: 100px;" align="center" nowrap id="mi" onclick="fnLoadValues('mi','bi','moreTab','basicTab','inventory','{$MODULE}')"><b>{$APP.LBL_MORE} {$APP.LBL_INFORMATION} </b></td>
                   				<td class="dvtTabCache" style="width:65%" nowrap>&nbsp;</td>
					{else}
						<td class="dvtSelectedCell" align=center nowrap>{$APP.LBL_BASIC} {$APP.LBL_INFORMATION}</td>
	                    <td class="dvtTabCache" align="right" style="width:100%">
	                    	<!-- <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  return validateInventory('{$MODULE}')" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" > -->
	                    	<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  return validateInventory('{$MODULE}')" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px">
	                    		<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}
	                    	</button>
	                    	<!-- <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px"> -->
	                    	<button title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " >
	                    		<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_CANCEL_BUTTON_LABEL}
	                    	</button>
	                    </td>
					{/if}
				   <tr>
				</table>
			</td>
		   </tr>
		   <tr>
			<td valign=top align=left >

			    {foreach item=blockInfo key=divName from=$BLOCKS}
			    <!-- Basic and More Information Tab Opened -->
			    <div id="{$divName}">

				<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
				   <tr>
					<!--this td is to display the entity details -->
					<td align=left>
					<!-- content cache -->

						<table border=0 cellspacing=0 cellpadding=0 width=100%>
						   <tr>
							<td id ="autocom"></td>
						   </tr>
						   <tr>
							<td style="padding:10px">
							<!-- General details -->
								<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
								   <tr>
									<td  colspan=4 style="padding:5px">
									   <div align="center">
										<!-- <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  return validateInventory('{$MODULE}')" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
                                        <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px"> -->
									   </div>
									</td>
								   </tr>

								   {foreach key=header item=data from=$blockInfo}
								   <tr>
									{if $header== $MOD.LBL_ADDRESS_INFORMATION && ($MODULE == 'Accounts' || $MODULE == 'Contacts'|| $MODULE == 'PurchaseOrder' || $MODULE == 'SalesOrder'|| $MODULE == 'Invoice' || $MODULE == 'Order')}
                                        <td colspan=4 class="detailedViewHeader">
                                        <b>{$header}</b></td>
                                    {elseif $MODULE eq 'Order' && $header eq 'Billing Address'}
										 <td colspan=2 class="detailedViewHeader">
                                         <b>{$header}</b></td>
                                         <td colspan=2  class="detailedViewHeader">
                                         <input name="cpy" onclick="return copyAddressLeft(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_RCPY_ADDRESS}</b></td>
                                    {elseif $MODULE eq 'Order' && $header eq 'Shipping Address'}
                                    	 <td colspan=2 class="detailedViewHeader">
                                         <b>{$header}</b></td>
                                          <td colspan=2  class="detailedViewHeader">
                                         <input name="cpy" onclick="return copyAddressRight(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_LCPY_ADDRESS}</b></td>
                                    {else}
						         		<td colspan=4 class="detailedViewHeader">
                                        <b>{$header}</b>
									{/if}
									</td>
								   </tr>

								   <!-- Here we should include the uitype handlings-->
								   {include file="DisplayFields.tpl"}

								   <tr style="height:25px"><td>&nbsp;</td></tr>
								   {/foreach}

								   <!-- This if is added to restrict display in more tab-->
								   {if $divName eq 'basicTab'}
										{if $MODULE eq 'Order'}
										   	<!-- Added to display the product details -->
											<!-- This if is added when we want to populate product details from the related entity  for ex. populate product details in new SO page when select Quote -->
											{if $AVAILABLE_PRODUCTS eq true}
												{include file="Order/ProductDetailsEditView.tpl"}
											{else}
												{include file="Order/ProductDetails.tpl"}
											{/if}

								   		{/if}
								   {/if}

								   <tr>
									<td  colspan=4 style="padding:5px">
									   <div align="right">
										<!-- <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save'; return validateInventory('{$MODULE}')" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" > -->
										<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save'; return validateInventory('{$MODULE}')" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px">
											<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}
										</button>
										<!-- <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px"> -->
										<button title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  ">
											<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_CANCEL_BUTTON_LABEL}
										</button>
										<input type="hidden" name="convert_from" value="{$CONVERT_MODE}">
                                        <input type="hidden" name="duplicate_from" value="{$DUPLICATE_FROM}">
                                        <input type="hidden" name="revise_from" value="{$REVISE_FROM}">
									   </div>
									</td>
								   </tr>
								</table>
								<!-- General details - end -->
							</td>
						   </tr>
						</table>
					</td>
				   </tr>
				</table>

			    </div>
			    {/foreach}
			</td>
		   </tr>
		</table>
	 </div>
	</td>
	<td align=right valign=top><img src="{'showPanelTopRight.gif'|@aicrm_imageurl:$THEME}"></td>
   </tr>
</table>
</form>

<!-- This div is added to get the left and top values to show the tax details-->
<div id="tax_container" style="display:none; position:absolute; z-index:1px;"></div>

<div id="dialog_create" style="display:none;">Dialog Content.</div>

<script>
	var fielduitype = new Array({$VALIDATION_DATA_UITYPE})
    var fieldname = new Array({$VALIDATION_DATA_FIELDNAME})

    var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL})

    var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE})

	var product_labelarr = {ldelim}CLEAR_COMMENT:'{$APP.LBL_CLEAR_COMMENT}',
                                DISCOUNT:'{$APP.LBL_DISCOUNT}',
                                TOTAL_AFTER_DISCOUNT:'{$APP.LBL_TOTAL_AFTER_DISCOUNT}',
                                TAX:'{$APP.LBL_TAX}',
                                ZERO_DISCOUNT:'{$APP.LBL_ZERO_DISCOUNT}',
                                PERCENT_OF_PRICE:'{$APP.LBL_OF_PRICE}',
                                DIRECT_PRICE_REDUCTION:'{$APP.LBL_DIRECT_PRICE_REDUCTION}'{rdelim};

        {if $isRevise eq 'true'}
        	jQuery('select[name="assigned_user_id"]').val('{$assigned_user_id}')
        	jQuery('input[name="quotation_date"]').val('{$quotation_date}')
		{/if}

</script>

<!-- vtlib customization: Help information assocaited with the fields -->
{if $FIELDHELPINFO}
<script type='text/javascript'>
{literal}var fieldhelpinfo = {}; {/literal}
{foreach item=FIELDHELPVAL key=FIELDHELPKEY from=$FIELDHELPINFO}
	fieldhelpinfo["{$FIELDHELPKEY}"] = "{$FIELDHELPVAL}";
{/foreach}
</script>
{/if}

{if ($MODULE eq 'Order') }
	<script>
        jQuery('input[name="show_dear"]').prop('checked', true);
	</script>
{/if}

{*if ($MODULE eq 'Products') }
       <script src="include/ckeditor/ckeditor.js"></script>
        {literal}
        <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'Products1',
        {
            filebrowserUploadUrl: 'include/ckeditor/plugins/imgupload/imgupload.php'
        }
        );
        </script>
        {/literal}
{/if*}
<!-- END -->
