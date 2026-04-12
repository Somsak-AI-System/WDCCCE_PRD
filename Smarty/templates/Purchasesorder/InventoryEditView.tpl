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

<!-- <script type="text/javascript" src="include/js/Inventory.js"></script> -->

<script type="text/javascript" src="include/js/FieldDependencies.js"></script>

<script type="text/javascript" src="asset/js/jquery.min.js"></script>
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
<table border=0 cellspacing=0 cellpadding=0 width=99% align=center>
   <tr>
	<td valign=top><img src="{'showPanelTopLeft.gif'|@aicrm_imageurl:$THEME}"></td>

	<td class="showPanelBg" valign=top width=100%>
		{*<!-- PUBLIC CONTENTS STARTS-->*}
		<div class="small" style="padding:20px">

			{if $OP_MODE eq 'edit_view'}
			   	{assign var="USE_ID_VALUE" value=$MOD_SEQ_ID}
		  		{if $USE_ID_VALUE eq ''} {assign var="USE_ID_VALUE" value=$ID} {/if}

				<span class="lvtHeaderText"><font color="purple">[ {$USE_ID_VALUE} ] </font>{$NAME} - {$APP.LBL_EDITING} {$MOD[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</span> <br>
				{$UPDATEINFO}
			{/if}
			{if $OP_MODE eq 'create_view'}
				<span class="lvtHeaderText">{$APP.LBL_CREATING} {$MOD[$SINGLE_MOD]}</span> <br>
			{/if}

			<!-- <hr noshade size=1> -->
			<hr noshade="" size="1" style="border: 1px solid #F7F7F7;">
			<br>

			{include file='EditViewHidden.tpl'}

			{*<!-- Account details tabs -->*}
			<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
			   <tr>
				<td>
					<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
					   <tr>
						<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
                        	<td class="dvtSelectedCell" align=center nowrap>{$MOD[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</td>
                            <td class="dvtTabCache" style="width:10px">&nbsp;</td>
	                        <td class="dvtTabCache" align="right" style="width:100%">
	                        	
	                        	<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save'; displaydeleted(); return validateInventory('{$MODULE}')" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px">
	                        		<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}
	                        	</button>
	                        	
	                        	<button title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}  ">
	                        		<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px;height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_CANCEL_BUTTON_LABEL}
	                        	</button>
	                        </div>
	                    </td>
					   </tr>
					</table>
				</td>
			   </tr>
			   <tr>
				<td valign=top align=left >
					<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
					   <tr>

						<td align=left style="padding:10px;border-right:1px #CCCCCC;" width=80%>
							{*<!-- content cache -->*}

							<table border=0 cellspacing=0 cellpadding=0 width=100%>
							   <tr>
								<td id ="autocom"></td>
							   </tr>
							   <tr>
								<td style="padding:20px">
								<!-- General details -->
									<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
									   
									   <!-- included to handle the edit fields based on ui types -->
									   {foreach key=header item=data from=$BLOCKS}
									      <tr>
										{if $header== $MOD.LBL_ADDRESS_INFORMATION && ($MODULE == 'Accounts' || $MODULE == 'Contacts'  || $MODULE == 'PurchaseOrder' || $MODULE == 'SalesOrder'|| $MODULE == 'Invoice')}
                                            <td colspan=2 class="detailedViewHeader">
	                                        <img id="aidAccountInformation" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide">&nbsp;&nbsp;&nbsp;<b>{$header}</b>
	                                    	</td>
        	                                <td class="detailedViewHeader">
                	                        <input name="cpy" onclick="return copyAddressLeft(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_RCPY_ADDRESS}</b> </td>
                        	                <td class="detailedViewHeader">
                                	        <input name="cpy" onclick="return copyAddressRight(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_LCPY_ADDRESS}</b></td>
                                        {elseif $MODULE eq 'Quotes' && $header eq 'Billing Address'}
											<td colspan=2 class="detailedViewHeader">
	                                        <img id="aidAccountInformation" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide">&nbsp;&nbsp;&nbsp;<b>{$header}</b> 
	                                        </td>
	                                        <td colspan=2  class="detailedViewHeader">
	                                        <input name="cpy" onclick="return copyAddressLeft(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_RCPY_ADDRESS}</b></td>
                                    	{elseif $MODULE eq 'Quotes' && $header eq 'Shipping Address'}
	                                    	<td colspan=2 class="detailedViewHeader">
	                                        <img id="aidAccountInformation" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide">&nbsp;&nbsp;&nbsp;<b>{$header}</b>
						         			</td>
	                                        <td colspan=2  class="detailedViewHeader">
	                                        <input name="cpy" onclick="return copyAddressRight(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_LCPY_ADDRESS}</b></td>
                                        {else}
											<td colspan=4 class="detailedViewHeader">
											<img id="aidAccountInformation" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide">&nbsp;&nbsp;&nbsp;<b>{$header}</b>
											</td>
										{/if}
										
									      </tr>

										<!-- Handle the ui types display -->
										{include file="DisplayFields.tpl"}

									      <tr style="height:25px"><td>&nbsp;</td></tr>

									   {/foreach}
									   <!-- Added to display the Product Details in Inventory-->
									   <tr>
										<td colspan=4>
											{include file="Purchasesorder/ProductDetailsEditView.tpl"}
										</td>
									   </tr>
									 

									   <tr>
										<td  colspan=4 style="padding:5px">
											<div align="right">
												
												<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  displaydeleted();return validateInventory('{$MODULE}')" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px">
													<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}
												</button>
												
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
								</td>
							   </tr>
							</table>
						</td>
						<!-- Inventory Actions - ends -->
					   </tr>
					</table>
				</td>
			   </tr>
			</table>
		</div>
	</td>
   </tr>
</table>
<input name='search_url' id="search_url" type='hidden' value='{$SEARCH}'>
</form>

<!-- This div is added to get the left and top values to show the tax details-->
<div id="tax_container" style="display:none; position:absolute; z-index:1px;"></div>

<script src="include/ckeditor/ckeditor.js"></script>
<script>

	var fielduitype = new Array({$VALIDATION_DATA_UITYPE})
	
    var fieldname = new Array({$VALIDATION_DATA_FIELDNAME})

    var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL})

    var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE})

    var product_labelarr = {
        ldelim}CLEAR_COMMENT: '{$APP.LBL_CLEAR_COMMENT}',
        DISCOUNT: '{$APP.LBL_DISCOUNT}',
        TOTAL_AFTER_DISCOUNT: '{$APP.LBL_TOTAL_AFTER_DISCOUNT}',
        TAX: '{$APP.LBL_TAX}',
        ZERO_DISCOUNT: '{$APP.LBL_ZERO_DISCOUNT}',
        PERCENT_OF_PRICE: '{$APP.LBL_OF_PRICE}',
        DIRECT_PRICE_REDUCTION: '{$APP.LBL_DIRECT_PRICE_REDUCTION}'{rdelim};

    var ProductImages = new Array();
    var count = 0;

    function delRowEmt(imagename) {ldelim}
        ProductImages[count++] = imagename;
        multi_selector.current_element.disabled = false;
        multi_selector.count--;
        {rdelim}

    function displaydeleted() {ldelim}
        if (ProductImages.length > 0)
            document.EditView.del_file_list.value = ProductImages.join('###');
        {rdelim}

    jQuery(function(){ldelim}
        jQuery('#unit_price').keyup(function(){ldelim}
            checkDec(this)
            {rdelim})
        {rdelim});
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

{if ($MODULE eq 'Products1')}
	<script src="include/ckeditor/ckeditor.js"></script>
{literal}
	<script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('description',
            {
                filebrowserUploadUrl: 'include/ckeditor/plugins/imgupload/imgupload.php'
            }
        );
	</script>
{/literal}
{/if}
<!-- END -->
