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
<script type="text/javascript" src="asset/js/polyfiller.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-{$CALENDAR_LANG}.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="include/js/FieldDependencies.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="asset/css/metro-blue/easyui.css">
<link rel="stylesheet" type="text/css" media="all" href="asset/css/icon.css">
<link rel="stylesheet" type="text/css" media="screen" href="asset/css/multi-select.css"  >
<link rel="stylesheet" type="text/css" media="screen" href="asset/css/custom.css"  >

<script type="text/javascript" src="asset/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.multi-select.js"></script>

<script type="text/javascript">
	jQuery.noConflict();
</script>
{if $PICKIST_DEPENDENCY_DATASOURCE neq ''}
<script type="text/javascript">
	jQuery(document).ready(function() {ldelim} (new FieldDependencies({$PICKIST_DEPENDENCY_DATASOURCE})).init() {rdelim});
</script>
{/if}
<!-- overriding the pre-defined #company to avoid clash with aicrm_field in the view -->
{literal}
<style type='text/css'>
	#company {
		width: 90%;
		height: auto;
	}
</style>
{/literal}

<script type="text/javascript">
var gVTModule = '{$smarty.request.module|@vtlib_purify}';
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
	<td valign=top>
		<img src="{'showPanelTopLeft.gif'|@aicrm_imageurl:$THEME}">
	</td>

	<td class="showPanelBg" valign=top width=100%>
	     {*<!-- PUBLIC CONTENTS STARTS-->*}
	     <div class="small" style="padding:20px">
		
		{* vtlib customization: use translation only if present *}
		{assign var="SINGLE_MOD_LABEL" value=$SINGLE_MOD}
		{if $APP.$SINGLE_MOD} {assign var="SINGLE_MOD_LABEL" value=$APP.SINGLE_MOD} {/if}
				
		 {if $OP_MODE eq 'edit_view'}   
			 <span class="lvtHeaderText"><font color="purple">[ {$ID} ] </font>{$NAME} -  {$APP.LBL_EDITING} {$SINGLE_MOD_LABEL} {$APP.LBL_INFORMATION}</span> <br>
			{$UPDATEINFO}	 
		 {/if}

		 {if $OP_MODE eq 'create_view'}
			{if $DUPLICATE neq 'true'}
			{assign var=create_new value="LBL_CREATING_NEW_"|cat:$SINGLE_MOD}
				{* vtlib customization: use translation only if present *}
				{assign var="create_newlabel" value=$APP.$create_new}
				{if $create_newlabel neq ''}
					<span class="lvtHeaderText">{$create_newlabel}</span> <br>
				{else}
					<span class="lvtHeaderText">{$APP.LBL_CREATING} {$APP.LBL_NEW} {$SINGLE_MOD}</span> <br>
				{/if}
		        
			{else}
			<span class="lvtHeaderText">{$APP.LBL_DUPLICATING} "{$NAME}" </span> <br>
			{/if}
		 {/if}

		 <hr noshade size=1 style="border: 1px solid #F7F7F7;">
		 <br> 
		
		{include file='EditViewHidden.tpl'}

		{*<!-- Account details tabs -->*}
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
		   <tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
				   <tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>

					{if $ADVBLOCKS neq ''}	
						<td width=75 style="width:15%" align="center" nowrap class="dvtSelectedCell" id="bi" onclick="fnLoadValues('bi','mi','basicTab','moreTab','normal','{$MODULE}')"><b>{$APP.LBL_BASIC} {$APP.LBL_INFORMATION}</b></td>
                    	<td class="dvtUnSelectedCell" style="width: 100px;" align="center" nowrap id="mi" onclick="fnLoadValues('mi','bi','moreTab','basicTab','normal','{$MODULE}')"><b>{$APP.LBL_MORE} {$APP.LBL_INFORMATION} </b></td>
                   		<td class="dvtTabCache" style="width:65%" nowrap>&nbsp;</td>
					{else}
						<td class="dvtSelectedCell" align=center nowrap>{$APP.LBL_BASIC} {$APP.LBL_INFORMATION}</td>
	                    <td class="dvtTabCache" style="width:100%">
	                    	<div align="right">
	                    			                    		
	                    		<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';return check_save();" type="submit" name="button" style="width:70px">
	                    			<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">
	                    			&nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}
	                    		</button>&nbsp;
	                    		
	                    		
	                    		<button title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button">
	                    			<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px; height: 17px; vertical-align: middle;">
	                    			&nbsp;{$APP.LBL_CANCEL_BUTTON_LABEL}
	                    		</button>
	                    	</div>
	                    </td>
					{/if}
				   <tr>
				</table>
			</td>
		   </tr>
		   <tr>
			<td valign=top align=left >

			    <!-- Basic Information Tab Opened -->
			    <div id="basicTab">

				<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
				   <tr>
					<td align=left>
					<!-- content cache -->
					
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
						   <tr>
							<td id ="autocom"></td>
						   </tr>
						   <tr>
							<td style="padding:20px">
							<!-- General details -->
								<table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
								   <tr>
									<td  colspan=4 style="padding:5px">
									   
									</td>
								   </tr>

								   {foreach key=header item=data from=$BASBLOCKS}
								   <tr>
									{if $header== $MOD.LBL_ADDRESS_INFORMATION && ($MODULE == 'Accounts' || $MODULE == 'Quotes' || $MODULE == 'PurchaseOrder' || $MODULE == 'SalesOrder'|| $MODULE == 'Invoice')}
                                        <td colspan=4 class="detailedViewHeader">
                                            <b>{$header}</b>
                                        </td>
                                        
									{elseif $header== $MOD.LBL_ADDRESS_INFORMATION && $MODULE == 'Contacts'}
										<td colspan=2 class="detailedViewHeader">
                                            <b>{$header}</b>
                                        </td>
                                        <td class="detailedViewHeader">
                                            <input name="cpy" onclick="return copyAddressLeft(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_CPY_OTHER_ADDRESS}</b>
                                        </td>
                                        <td class="detailedViewHeader">
                                            <input name="cpy" onclick="return copyAddressRight(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_CPY_MAILING_ADDRESS}</b>
                                        </td>
                                    {else}
						         		<td colspan=4 class="detailedViewHeader">
						         			<img src="themes/softed/images/slidedown_b.png" border="0" style="width: 15px; height: 12px; margin-top: 2px;">
                                            &nbsp;&nbsp;&nbsp;<b>{$header}</b>
									{/if}
							 		</td>
									</tr>

								   <!-- Here we should include the uitype handlings-->
								   {include file="DisplayFields.tpl"}							
								   <tr style="height:25px"><td>&nbsp;</td></tr>
								   {/foreach}

							
									{*if $MODULE eq 'Promotion' }
									<tr><td colspan=4>
									
									<div>
										<!-- setting product price -->
										<div class="detailedViewHeader">
											<input name="stpromotion" id="stpromotion1" type="radio" value="1" ><b>Setting Product Price</b>
										</div>
										<div class="toggle" style="display: block;">
								            <div class="easyui-tabs" style="width: 1700px;" >
										        <div title="Product Price" style="padding:10px">
										            <table width="100%" id="mySetup_Tab1_1" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
													  <tr>
													    <td colspan="4" class="dvInnerHeader"><strong>Set Up Product Price Detail</strong></td>
													  </tr>
                                  					  <tr valign="top">
                                  					  	<td width=5% align="center" valign="top" class="lvtCol"><b>เครื่องมือ</b></td>
    													<td width="10%" align="center" valign="top" class="lvtCol"><b>ราคาสินค้า จาก</b></td>	
    													<td width="10%" align="center" valign="top" class="lvtCol"><b>ถึง ราคาสินค้า</b></td>
    													<td width="50%" align="center" valign="top" class="lvtCol"><strong>ชื่อพรีเมียม</strong></td>
   													  </tr>
    												  <tr valign="top" id="mySetup_Tab1_1_row1">
    												  	<td width=5% valign="top" class="crmTableRow  small lineOnTop" align="center">
                                                        <input type="hidden" id="mySetup_Tab1_1_deleted1" name="mySetup_Tab1_1_deleted1" value="0"></td>
    													<td width="10%" valign="top" class="crmTableRow small lineOnTop">
                                                        <input type="text"  style="width:100px"  id="mySetup_Tab1_1_productprice_from1" name="mySetup_Tab1_1_productprice_from1" value="" class="detailedViewTextBox" onBlur="this.className='detailedViewTextBox';check_number('mySetup_Tab1_1_productprice_from1');settotalnoofrows_mySetup_Tab1_1();" onFocus="this.className='detailedViewTextBoxOn'" ></td>	
    													<td width="10%" valign="top" class="crmTableRow small lineOnTop" >
                                                        <input type="text" style="width:100px" id="mySetup_Tab1_1_productprice_to1" name="mySetup_Tab1_1_productprice_to1" value="" class="detailedViewTextBox" onBlur="this.className='detailedViewTextBox';check_number('mySetup_Tab1_1_productprice_to1');settotalnoofrows_mySetup_Tab1_1();" onFocus="this.className='detailedViewTextBoxOn'" ></td>
    													<td width="10%" valign="top" class="crmTableRow small lineOnTop" >
                                                        <img id="mySetup_Tab1_1_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab1_1_PremiumList(this,'{$MODULE}',1)" />
                                                        <div style="float:right">เงื่อนไข:
										        		<select id="campaign_fomula1" name="campaign_fomula1" style="width:80px">
    														<option value='and'>and</option>
    														<option value='or'>or</option>
    													</select></div>
                                                        <div class="mySetup_Tab1_1_premium"></div><input type="hidden" name="total_row_premium1" id="total_row_premium1" value="">
                                                        </td>
   													  </tr>	
   												  	</table> 
                                  					<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
													  
													   <tr>
														<td colspan="3">
                                                   		  <input type="hidden" name="mySetup_Tab1_1_totalProductCount" id="mySetup_Tab1_1_totalProductCount" value="">
                                                   		  	<button title="Add New Item" class="crmbutton small save" onclick="fnAdd_mySetup_Tab1_1('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button" style="width:70px">
																<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add
															</button>
														 
														</td>
												      </tr>
                               					  </table> 
						                		</div>

										        <div title="Product" style="padding:10px">
                                                	<div style="float:right">เงื่อนไข:
	                                                    <select id="campaign_fomula_pro" name="campaign_fomula_pro" style="width:80px">
	                                                        <option value='and'>and</option>
	                                                        <option value='or'>or</option>
	                                                    </select>
                                                	</div>
										          	<table width="100%" id="mySetup_Tab1_2" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
													  	<tr>
													    	<td colspan="3" class="dvInnerHeader"><strong>สินค้าที่เข้าร่วมรายการ</strong></td>
													  	</tr>
                                  					  	<tr valign="top">
                                  					    	<td width="20%" align="center" valign="top" class="lvtCol"><b>เครื่องมือ</b></td>
                                  					    	<td width="67%" align="center" valign="top" class="lvtCol"><b>ชื่อสินค้า</b></td>
                                  					    	<td width="30%" align="left" valign="top" class="lvtCol"><b>หน่วยนับ</b></td>
                               					    	</tr>
                                  					  	<tr valign="top" id="mySetup_Tab1_2_row1">
                                  					    	<td valign="top" class="crmTableRow  small lineOnTop" align="center">
                                                        		<input type="hidden" id="mySetup_Tab1_2_deleted1" name="mySetup_Tab1_2_deleted1" value="0" />
                                                        	</td>
                                  					    	<td valign="top" class="crmTableRow small lineOnTop">
                                  					    		<table width="100%"  border="0" cellspacing="0" cellpadding="1">
                                  					    			<tr>
                                  					        			<td class="small">
                                  					        				<input type="text" id="mySetup_Tab1_2_productName1" name="mySetup_Tab1_2_productName1" class="small" style="width:70%" value="{$PRODUCT_NAME}" readonly="readonly" />
                                  					          				<input type="hidden" id="mySetup_Tab1_2_hdnProductId1" name="mySetup_Tab1_2_hdnProductId1" value="{$PRODUCT_ID}" />
                                  					          				&nbsp;<img id="mySetup_Tab1_2_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab1_2_productPickList(this,'{$MODULE}',1)" />
                                  					          			</td>
                               					          			</tr>
                                  					      			<tr valign="bottom">
                                  					        			<td class="small" id="setComment2">
                                  					        				<textarea id="mySetup_Tab1_2_comment1" name="mySetup_Tab1_2_comment1" class="small" style="width:70%;height:40px"></textarea>

                                  					          				<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onclick="{literal}${/literal}('mySetup_Tab1_2_comment1').value=''"; style="cursor:pointer;" />
                                  					          			</td>
                               					          			</tr>
                                					      		</table>
                                					      	</td>
                                  					    	<td align="left" valign="top" class="crmTableRow small lineOnTop" >
                                  					    		<input id="mySetup_Tab1_2_uom1" name="mySetup_Tab1_2_uom1" type="text"  style="width:50px" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn';" onblur="this.className='detailedViewTextBox';"  value=""/>
                                  					    		<input id="mySetup_Tab1_2_qty1" name="mySetup_Tab1_2_qty1" type="hidden" class="detailedViewTextBox"  style="width:50px" onfocus="this.className='detailedViewTextBoxOn'" onblur="settotalnoofrows_Setup_Disc();"/>
                                  					    		<input id="mySetup_Tab1_2_listPrice1" name="mySetup_Tab1_2_listPrice1" value="0.00" type="hidden" class="detailedViewTextBox"  style="width:70px" onblur=""/>
                                  					    	</td>
                               					    	</tr>	
   												    </table> 

    												<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
													   
													   	<tr>
															<td colspan="3">
                                                        		<input type="hidden" name="mySetup_Tab1_2_totalProductCount" id="mySetup_Tab1_2_totalProductCount" value="">
																<button title="Add New Item" class="crmbutton small save" onclick="fnAdd_mySetup_Tab1_2('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button" style="width:70px">
																<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add
																</button>
																
															</td>
													   	</tr>
                                  					</table>
										        </div>
									        </div>
								        </div>
								        <!-- //setting product price -->
								        
								        <!-- setting product  -->
										<div class="detailedViewHeader">
											<input name="stpromotion" id="stpromotion2" type="radio" value="2" ><b>Setting Product</b>
										</div>
										<div class="toggle" style="display: block;">
								            <div class="easyui-tabs" style="width:1700px;">
										        
										        <div title="Product" style="padding:10px">
                                                	<table width="100%" id="myformelement" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
													  	<tr>
													    	<td colspan="4" class="dvInnerHeader"><strong>Set Up Condition</strong></td>
													  	</tr>
													 	<tr>	
															<td align="right" class="dvtCellLabel">เงื่อนไข</td>
															<td align="left" class="dvtCellInfo">
	                                                        	<select id="mySetup_Tab2_campaign_fomula_head" name="mySetup_Tab2_campaign_fomula_head" style="width:80px">
	                                                        		<option value='and'>and</option>
	                                                        		<option value='or'>or</option>
	                                                    		</select>
	                                                    	</td>
														
															<td align="right" class="dvtCellLabel">จำนวน</td>
															<td align="left" class="dvtCellInfo">
																<input type="text" style="width:100px" id="mySetup_Tab2_hearder_qty" name="mySetup_Tab2_hearder_qty" value="0" class="detailedViewTextBox" onblur="this.className='detailedViewTextBox';check_number('mySetup_Tab2_hearder_qty');" onfocus="this.className='detailedViewTextBoxOn'"/>
															</td>
														</tr>	
												  	</table>	
												  	<br>

								            		<table width="100%" id="mySetup_Tab2" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
													  	<tr>
													    	<td colspan="4" class="dvInnerHeader"><strong>Set Up Product Detail</strong></td>
													  	</tr>
                                  					  	<tr valign="top">
                                  					    	<td width="5%" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
                                  					  		<td width="40%" class="lvtCol" align="center"><b>ชื่อสินค้า</b></td>
    														<td width="10%" class="lvtCol" align="left"><b>จำนวน</b></td>	
    														<td width="50%" align="center" class="lvtCol"><strong>ชื่อพรีเมี่ยม</strong></td>
   													  	</tr>
                                  					  	<tr valign="top"  id="mySetup_Tab2_row1">
                                  					    	<td width="5%" class="crmTableRow  small lineOnTop" align="center">
                                                        		<input type="hidden" id="mySetup_Tab2_deleted1" name="mySetup_Tab2_deleted1" value="0" />
                                                        	</td>
    												  		<td width="10%" class="crmTableRow small lineOnTop">
    												  			<table width="100%"  border="0" cellspacing="0" cellpadding="1">
    												  	  			<tr>
    												  	    			<td class="small">
    												  	    				<input type="text" id="mySetup_Tab2_productName1" name="mySetup_Tab2_productName1" class="small" style="width:70%" value="{$PRODUCT_NAME}" readonly="readonly" />
    												  	      				<input type="hidden" id="mySetup_Tab2_hdnProductId1" name="mySetup_Tab2_hdnProductId1" value="{$PRODUCT_ID}" />
    												  	      				&nbsp;<img id="mySetup_Tab2_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab2_productPickList(this,'{$MODULE}',1)" />
    												  	      			</td>
  												  	    			</tr>
    												  	  			<tr valign="bottom">
    												  	    			<td class="small" id="setComment">
                                                              				<textarea id="mySetup_Tab2_comment1" name="mySetup_Tab2_comment1" class="small" style="width:70%;height:40px"></textarea>
    												  	      				<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onclick="{literal}${/literal}('mySetup_Tab2_comment1').value=''"; style="cursor:pointer;" />
    												  	      			</td>
  												  	   	 			</tr>
  												  	  			</table>
  												  	  		</td>
    														<td width="10%" align="left" class="crmTableRow small lineOnTop">
                                                        		<input id="mySetup_Tab2_qty1" name="mySetup_Tab2_qty1" type="text" class="detailedViewTextBox"  style="width:50px" onfocus="this.className='detailedViewTextBoxOn'" onblur="check_number('mySetup_Tab2_qty1');settotalnoofrows_mySetup_Tab2();"/>
                                                        		<input id="mySetup_Tab2_uom1" name="mySetup_Tab2_uom1" type="hidden"  style="width:50px" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn';" onblur="this.className='detailedViewTextBox';"  value=""/>
                                                        		<input id="mySetup_Tab2_listPrice1" name="mySetup_Tab2_listPrice1" value="0.00" type="hidden" class="detailedViewTextBox"  style="width:70px" onblur=""/>
                                                        	</td>	
    														<td width="10%" class="crmTableRow small lineOnTop" >
                                                        		<img id="mySetup_Disc_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab2_PremiumList(this,'{$MODULE}',1)" />
    													  		<div style="float:right">เงื่อนไข:
    													    		<select id="mySetup_Tab2_campaign_fomula1" name="mySetup_Tab2_campaign_fomula1" style="width:80px">
    													      			<option value='and'>and</option>
    													      			<option value='or'>or</option>
  													      			</select>
  													    		</div>
    													  		<div class="mySetup_Tab2_premium"></div>
    													  		<input type="hidden" name="total_row_mySetup_Tab2_1" id="total_row_mySetup_Tab2_1" value="" /></td>
   													 	</tr>	
    	
    												</table> 
    												<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
													   
													   	<tr>
															<td colspan="3">
	                                                        	<input type="hidden" name="mySetup_Tab2_totalProductCount" id="mySetup_Tab2_totalProductCount" value="">
																<button title="Add New Item" class="crmbutton small save" onclick="fnAdd_mySetup_Tab2('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button" style="width:70px">
																<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add
																</button>
																
															</td>
													   </tr>
                               					  	</table>
									          	</div>
										       
									        </div>
								        </div>
								        <!-- //setting product -->
								        
								        <!-- //setting discount -->
								        <div class="detailedViewHeader">
								        	<input name="stpromotion" id="stpromotion3" type="radio"  value="3" ><b>Setting Discount</b>
								        </div>
										<div class="toggle" style="display: block;">
											<div class="easyui-tabs" style="width:1700px;height:auto">
										        
										    	<div title="Product" style="padding:10px">
										          	<table width="100%" id="myformelement" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
													  	<tr>
													    	<td colspan="4" class="dvInnerHeader"><strong>Set Up Discount Product Detail</strong></td>
													  	</tr>
													 	<tr>	
															<td align="right" class="dvtCellLabel">ชื่อส่วนลด</td>
															<td align="left" class="dvtCellInfo">
																<input type="text" style="width:100px" id="mySetup_Disc_Name" name="mySetup_Disc_Name" value="" class="detailedViewTextBox" onBlur="this.className='detailedViewTextBox'" onFocus="this.className='detailedViewTextBoxOn'" >
															</td>
															<td align="right" class="dvtCellLabel">ประเภทส่วนลด</td>
															<td align="left" class="dvtCellInfo">
																<select id="mySetup_Disc_Discount_type" name="mySetup_Disc_Discount_type" class="small"  style="width:80px" onchange="settotalnoofrows_Setup_Disc();">
	                                                                <option value=''></option>
		    														<option value='%'>%</option>
		    														<option value='baht'>baht</option>
	    														</select>
																<input type="text" style="width:100px" id="mySetup_Disc_Dis_value" name="mySetup_Disc_Dis_value" value="0" class="detailedViewTextBox" onblur="this.className='detailedViewTextBox';check_number('mySetup_Disc_Dis_value');settotalnoofrows_Setup_Disc();" onfocus="this.className='detailedViewTextBoxOn'"   />
															</td>
													 	</tr>	
													 	<tr>	
															<td align="right" class="dvtCellLabel">รวมราคา</td>
															<td align="left" class="dvtCellInfo">
																<input name="mySetup_Disc_total" type="text" class="detailedViewTextBox" id="mySetup_Disc_total" style="width:100px;background-color: #CCC" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="0" readonly="readonly" >
															</td>
														
															<td align="right" class="dvtCellLabel">ส่วนลด</td>
															<td align="left" class="dvtCellInfo"><input name="mySetup_Disc_Discount" type="text" class="detailedViewTextBox" id="mySetup_Disc_Discount" style="width:100px;background-color: #CCC" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="0" readonly="readonly" >
															</td>
													 	</tr>	
													 	<tr>	
															<td align="right" class="dvtCellLabel">รวมราคาทั้งสิ้น</td>
															<td align="left" class="dvtCellInfo">
																<input name="mySetup_Disc_Net" type="text" class="detailedViewTextBox" id="mySetup_Disc_Net" style="width:100px;background-color: #CCC" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="0" readonly="readonly" >
															</td>
													 		<td align="right" class="dvtCellLabel"></td>
															<td align="left" class="dvtCellInfo"></td>
													 	</tr>	
												  	</table>	
												  	<br>	  		
										          	<table width="100%" id="mySetup_Disc" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
													  	<tr>
													    	<td colspan="5" class="dvInnerHeader"><strong>Set Up Product Detail</strong></td>
													  	</tr>
                                  					  	<tr valign="top">
                                  					  		<td width=5% valign="top" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
    														<td width="40%" valign="top" class="lvtCol" align="center"><b>ชื่อสินค้า</b></td>	
	    													<td width="10%" valign="top" class="lvtCol" align="left"><b>หน่วยนับ</b></td>
	    													<td width="10%" valign="top" class="lvtCol" align="left"><b>จำนวน</b></td>
	    													<td width="10%" valign="top" class="lvtCol" align="left"><b>ราคาต่อหน่วย</b></td>
	    												</tr>	
    												  	<tr valign="top" id="mySetup_Disc_row1">
    												  		<td width=5% valign="top" class="crmTableRow  small lineOnTop" align="center">
    												  			<input type="hidden" id="mySetup_Disc_deleted1" name="mySetup_Disc_deleted1" value="0">
    												  		</td>
    														<td width="10%" valign="top" class="crmTableRow small lineOnTop">
    															<table width="100%"  border="0" cellspacing="0" cellpadding="1">
    													  			<tr>
    													    			<td class="small">
    													    				<input type="text" id="mySetup_Disc_productName1" name="mySetup_Disc_productName1" class="small" style="width:70%" value="{$PRODUCT_NAME}" readonly="readonly" />
    													      				<input type="hidden" id="mySetup_Disc_hdnProductId1" name="mySetup_Disc_hdnProductId1" value="{$PRODUCT_ID}" />
    													      				&nbsp;<img id="mySetup_Disc_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Disc_productPickList(this,'{$MODULE}',1)" />
    													      			</td>
  													    			</tr>
    													  			<tr valign="bottom">
    													    			<td class="small" id="setComment">
    													    				<textarea id="mySetup_Disc_comment1" name="mySetup_Disc_comment1" class="small" style="width:70%;height:40px"></textarea>
    													     	 			<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onclick="{literal}${/literal}('mySetup_Disc_comment1').value=''"; style="cursor:pointer;" />
    													     	 		</td>
  													    			</tr>
  													  			</table>
  													  		</td>	
    														<td width="10%" align="left" valign="top" class="crmTableRow small lineOnTop" >
    															<input id="mySetup_Disc_uom1" name="mySetup_Disc_uom1" type="text"  style="width:50px" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn';" onblur="this.className='detailedViewTextBox';"  value=""/>
    														</td>
    														<td width="10%" align="left" valign="top" class="crmTableRow small lineOnTop">
    															<input id="mySetup_Disc_qty1" name="mySetup_Disc_qty1" type="text" class="detailedViewTextBox"  style="width:50px" onfocus="this.className='detailedViewTextBoxOn'" onblur="check_number('mySetup_Disc_qty1');settotalnoofrows_Setup_Disc();"/>
    														</td>
    														<td width="10%" align="left" valign="top" class="crmTableRow small lineOnTop">
    															
    															<input id="mySetup_Disc_listPrice1" name="mySetup_Disc_listPrice1" value="0.00" type="text" class="detailedViewTextBox"  style="width:70px" onblur="check_number('mySetup_Disc_listPrice1');settotalnoofrows_Setup_Disc();"/>
    														</td>
    												  	</tr>	
    												</table> 
    												 
    												<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
													   
														<tr>
															<td colspan="3">
                                                   		  		<input type="hidden" name="mySetup_Disc_totalProductCount" id="mySetup_Disc_totalProductCount" value="">
														  		<button title="Add New Item" class="crmbutton small save" onclick="fnAddSetup_Disc('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button" style="width:70px">
																<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add
																</button>
														  		
															</td>
													   	</tr>
                                  					</table>
										        </div>
											</div>
								        <!-- //setting discount -->
									</div>

									</td></tr>
									
									{/if*}
									
									<!-- Added to display the Product Details in Inventory-->
									{if $MODULE eq 'Promotion'}
							   		   <tr>
										<td colspan=4>
	                                    
	                                    <div>
	                                    	<!-- setting product price -->
	                                        <div class="detailedViewHeader"><input name="stpromotion" id="stpromotion1" type="radio"  value="1"><b>Setting Product Price</b></div>
	                                        <div class="toggle" style="display: block;">
	                                            <div class="easyui-tabs" style="width:1700px;">
	                                                <div title="Product Price" style="padding:10px">
	                                                    <table width="100%" id="mySetup_Tab1_1" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
													  <tr>
													    <td colspan="4" class="dvInnerHeader"><strong>Set Up Product Price Detail</strong></td>
													  </tr>
	                              					  <tr valign="top">
	                              					  	<td width=5% align="center" valign="top" class="lvtCol"><b>เครื่องมือ</b></td>
														<td width="10%" align="center" valign="top" class="lvtCol"><b>ราคาสินค้า จาก</b></td>	
														<td width="10%" align="center" valign="top" class="lvtCol"><b>ถึง ราคาสินค้า</b></td>
														<td width="50%" align="center" valign="top" class="lvtCol"><strong>ชื่อพรีเมียม</strong></td>
														  </tr>	
													
	                                        	{foreach key=row_no item=data from=$PROMOTION_TAB1 name=outer1}	
	                                                {assign var="mySetup_Tab1_1_deleted" value="mySetup_Tab1_1_deleted"|cat:$row_no}
	                                                {assign var="mySetup_Tab1_1_productprice_from" value="mySetup_Tab1_1_productprice_from"|cat:$row_no}
	                                                {assign var="mySetup_Tab1_1_productprice_to" value="mySetup_Tab1_1_productprice_to"|cat:$row_no}
	                                                {assign var="campaign_fomula" value="campaign_fomula"|cat:$row_no}
	                                                {assign var="mySetup_Tab1_1_premium" value="mySetup_Tab1_1_premium"|cat:$row_no}
	                                                {assign var="mySetup_Tab1_1_totalProductCount" value="mySetup_Tab1_1_totalProductCount"|cat:$row_no}
													  <tr valign="top" id="mySetup_Tab1_1_row{$row_no}">
													  	<td width=5% valign="top" class="crmTableRow  small lineOnTop" align="center">
	                                                    {if $row_no neq 1}
	                                                        <img src="{'delete.gif'|@aicrm_imageurl:$THEME}" border="0" onclick="mySetup_Tab1_1_deleteRow('{$MODULE}',{$row_no},'{$IMAGE_PATH}')">
	                                                    {/if}<br/><br/>
	                                                    <input type="hidden" id="{$mySetup_Tab1_1_deleted}" name="{$mySetup_Tab1_1_deleted}" value="0">
	                                                    </td>
														<td width="10%" valign="top" class="crmTableRow small lineOnTop">
	                                                    <input type="text"  style="width:100px"  id="{$mySetup_Tab1_1_productprice_from}" name="{$mySetup_Tab1_1_productprice_from}" value="{$data.$mySetup_Tab1_1_productprice_from}" class="detailedViewTextBox" onBlur="this.className='detailedViewTextBox';check_number('{$mySetup_Tab1_1_productprice_from}');settotalnoofrows_mySetup_Tab1_1();" onFocus="this.className='detailedViewTextBoxOn'" ></td>	
														<td width="10%" valign="top" class="crmTableRow small lineOnTop" >
	                                                    <input type="text" style="width:100px" id="{$mySetup_Tab1_1_productprice_to}" name="{$mySetup_Tab1_1_productprice_to}" value="{$data.$mySetup_Tab1_1_productprice_to}" class="detailedViewTextBox" onBlur="this.className='detailedViewTextBox';check_number('{$mySetup_Tab1_1_productprice_to}');settotalnoofrows_mySetup_Tab1_1();" onFocus="this.className='detailedViewTextBoxOn'" ></td>
														<td width="10%" valign="top" class="crmTableRow small lineOnTop" >
	                                                    <img id="mySetup_Tab1_1_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab1_1_PremiumList(this,'{$MODULE}',{$row_no})" />
	                                                    <div style="float:right">เงื่อนไข:
										        		<select id="{$campaign_fomula}" name="{$campaign_fomula}" class="small" style="width:80px">
	                                                        {if $data.$campaign_fomula eq "and"}
	                                                            {assign var=selected1 value="selected"}
	                                                        {else}
	                                                            {assign var=selected1 value=""}
	                                                        {/if}
	                                                        {if $data.$campaign_fomula eq "or"}
	                                                            {assign var=selected2 value="selected"}
	                                                        {else}
	                                                            {assign var=selected2 value=""}
	                                                        {/if}
															<option value='and' {$selected1}>and</option>
															<option value='or' {$selected2}>or</option>
														</select></div>
	                                                    {$data.$mySetup_Tab1_1_premium}
	                                                    </td>
														  </tr>	
	                                        	{/foreach}
	                                        	<!-- Empty Data Tab 1-->
	                                    		{if empty($PROMOTION_TAB1)}
	                                                <tr valign="top" id="mySetup_Tab1_1_row1">
													  	<td width=5% valign="top" class="crmTableRow  small lineOnTop" align="center">
	                                                    <input type="hidden" id="mySetup_Tab1_1_deleted1" name="mySetup_Tab1_1_deleted1" value="0"></td>
														<td width="10%" valign="top" class="crmTableRow small lineOnTop">
	                                                    <input type="text"  style="width:100px"  id="mySetup_Tab1_1_productprice_from1" name="mySetup_Tab1_1_productprice_from1" value="" class="detailedViewTextBox" onBlur="this.className='detailedViewTextBox';check_number('mySetup_Tab1_1_productprice_from1');settotalnoofrows_mySetup_Tab1_1();" onFocus="this.className='detailedViewTextBoxOn'" ></td>	
														<td width="10%" valign="top" class="crmTableRow small lineOnTop" >
	                                                    <input type="text" style="width:100px" id="mySetup_Tab1_1_productprice_to1" name="mySetup_Tab1_1_productprice_to1" value="" class="detailedViewTextBox" onBlur="this.className='detailedViewTextBox';check_number('mySetup_Tab1_1_productprice_to1');settotalnoofrows_mySetup_Tab1_1();" onFocus="this.className='detailedViewTextBoxOn'" ></td>
														<td width="10%" valign="top" class="crmTableRow small lineOnTop" >
	                                                    <img id="mySetup_Tab1_1_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab1_1_PremiumList(this,'{$MODULE}',1)" />
	                                                    <div style="float:right">เงื่อนไข:
										        		<select id="campaign_fomula1" name="campaign_fomula1" class="small" style="width:80px">
															<option value='and'>and</option>
															<option value='or'>or</option>
														</select></div>
	                                                    <div class="mySetup_Tab1_1_premium"></div><input type="hidden" name="total_row_premium1" id="total_row_premium1" value="">
	                                                    </td>
														</tr>	
	                                            {/if}        
													  </table> 
	                                                    <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
	                                                       <!-- Add Product Button -->
	                                                       <tr>
	                                                        <td colspan="3">
	                                                          <input type="hidden" name="mySetup_Tab1_1_totalProductCount" id="mySetup_Tab1_1_totalProductCount" value="{$data.$mySetup_Tab1_1_totalProductCount}">
	                                                          <button title="Add New Item" class="crmbutton small save" onclick="fnAdd_mySetup_Tab1_1('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button" style="width:70px">
																<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add</button>
	                                                          <!-- <input type="button" name="Button" class="crmbutton small create" value="Add" onclick="fnAdd_mySetup_Tab1_1('{$MODULE}','{$IMAGE_PATH}');" /> -->
	                                                        </td>
	                                                      </tr>
	                                                  	</table>
	                                                   
	                                                </div>
	                                                {foreach key=row_no item=data from=$PROMOTION_TAB1_2 name=outer1}	
	                                               		 {assign var="campaign_fomula_pro" value="campaign_fomula_pro"|cat:$row_no}
	                                                {/foreach} 
	                                                
	                                                <div title="Product" style="padding:10px">
	                                                	<div style="float:right">เงื่อนไข:
	                                                    <select id="campaign_fomula_pro" name="campaign_fomula_pro" class="small" style="width:80px">
	                                                    	{if $data.$campaign_fomula_pro eq "and"}
	                                                            {assign var=selected1 value="selected"}
	                                                        {else}
	                                                            {assign var=selected1 value=""}
	                                                        {/if}
	                                                        {if $data.$campaign_fomula_pro eq "or"}
	                                                            {assign var=selected2 value="selected"}
	                                                        {else}
	                                                            {assign var=selected2 value=""}
	                                                        {/if}
	                                                        <option value='and' {$selected1}>and</option>
	                                                        <option value='or' {$selected2}>or</option>
	                                                    </select></div>
	                                                  <table width="100%" id="mySetup_Tab1_2" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
													  <tr>
													    <td colspan="3" class="dvInnerHeader"><strong>สินค้าที่เข้าร่วมรายการ</strong></td>
													  </tr>
	                              					  <tr valign="top">
	                              					    <td width="20%" align="center" valign="top" class="lvtCol"><b>เครื่องมือ</b></td>
	                              					    <td width="67%" align="center" valign="top" class="lvtCol"><b>ชื่อสินค้า</b></td>
	                              					    <td width="30%" align="left" valign="top" class="lvtCol"><b>หน่วยนับ</b></td>
	                           					    </tr>
	                           					    
		                                            {foreach key=row_no item=data from=$PROMOTION_TAB1_2 name=outer1}	
		                                                    {assign var="mySetup_Tab1_2_deleted" value="mySetup_Tab1_2_deleted"|cat:$row_no}
		                                                    {assign var="mySetup_Tab1_2_productName" value="mySetup_Tab1_2_productName"|cat:$row_no}
		                                                    {assign var="mySetup_Tab1_2_hdnProductId" value="mySetup_Tab1_2_hdnProductId"|cat:$row_no}
		                                                    {assign var="mySetup_Tab1_2_comment" value="mySetup_Tab1_2_comment"|cat:$row_no}
		                                                    {assign var="mySetup_Tab1_2_uom" value="mySetup_Tab1_2_uom"|cat:$row_no}
		                                                    {assign var="mySetup_Tab1_2_qty" value="mySetup_Tab1_2_qty"|cat:$row_no}    
		                                                    {assign var="mySetup_Tab1_2_listPrice" value="mySetup_Tab1_2_listPrice"|cat:$row_no} 
		                                                    {assign var="mySetup_Tab1_2_totalProductCount" value="mySetup_Tab1_2_totalProductCount"|cat:$row_no}         
		                                  					  <tr valign="top" id="mySetup_Tab1_2_row{$row_no}">
		                                  					    <td valign="top" class="crmTableRow  small lineOnTop" align="center">
		                                                        {if $row_no neq 1}
		                                                            <img src="{'delete.gif'|@aicrm_imageurl:$THEME}" border="0" onclick="mySetup_Tab1_2_deleteRow('{$MODULE}',{$row_no},'{$IMAGE_PATH}')">
		                                                        {/if}<br/><br/>
		                                                        <input type="hidden" id="{$mySetup_Tab1_2_deleted}" name="{$mySetup_Tab1_2_deleted}" value="0" /></td>
		                                  					    <td valign="top" class="crmTableRow small lineOnTop"><table width="100%"  border="0" cellspacing="0" cellpadding="1">
		                                  					      <tr>
		                                  					        <td class="small"><input type="text" id="{$mySetup_Tab1_2_productName}" name="{$mySetup_Tab1_2_productName}" class="small" style="width:70%" value="{$data.$mySetup_Tab1_2_productName}" readonly="readonly" />
		                                  					          <input type="hidden" id="{$mySetup_Tab1_2_hdnProductId}" name="{$mySetup_Tab1_2_hdnProductId}" value="{$data.$mySetup_Tab1_2_hdnProductId}" />
		                                  					          &nbsp;<img id="mySetup_Tab1_2_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab1_2_productPickList(this,'{$MODULE}',{$row_no})" /></td>
		                               					          </tr>
		                                  					      <tr valign="bottom">
		                                  					        <td class="small" id="setComment2"><textarea id="{$mySetup_Tab1_2_comment}" name="{$mySetup_Tab1_2_comment}" class="small" style="width:70%;height:40px">{$data.$mySetup_Tab1_2_comment}</textarea>

		                                  					          <img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onclick="{literal}${/literal}('{$mySetup_Tab1_2_comment}').value=''"; style="cursor:pointer;" /></td>
		                               					          </tr>
		                                					      </table></td>
		                                  					    <td align="left" valign="top" class="crmTableRow small lineOnTop" ><input id="{$mySetup_Tab1_2_uom}" name="{$mySetup_Tab1_2_uom}" type="text"  style="width:50px" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn';" onblur="this.className='detailedViewTextBox';"  value="{$data.$mySetup_Tab1_2_uom}"/><input id="{$mySetup_Tab1_2_qty}" name="{$mySetup_Tab1_2_qty}" type="hidden" class="detailedViewTextBox"  style="width:50px" onfocus="this.className='detailedViewTextBoxOn'" onblur="settotalnoofrows_Setup_Disc();" value="{$data.$mySetup_Tab1_2_qty}"/><input id="{$mySetup_Tab1_2_listPrice}" name="{$mySetup_Tab1_2_listPrice}" value="{$data.$mySetup_Tab1_2_listPrice}" type="hidden" class="detailedViewTextBox"  style="width:70px" onblur=""/></td>
		                               					    </tr>	
		                                            {/foreach}
		                                            <!-- Empty data Tab 1_2 -->
		                                            {if empty($PROMOTION_TAB1_2)}
	                                                    <tr valign="top" id="mySetup_Tab1_2_row1">
	                              					    	<td valign="top" class="crmTableRow  small lineOnTop" align="center">
	                                                    		<input type="hidden" id="mySetup_Tab1_2_deleted1" name="mySetup_Tab1_2_deleted1" value="0" />
	                                                    	</td>
	                              					    	<td valign="top" class="crmTableRow small lineOnTop">
	                              					    		<table width="100%"  border="0" cellspacing="0" cellpadding="1">
	                              					    			<tr>
	                              					        			<td class="small">
	                              					        				<input type="text" id="mySetup_Tab1_2_productName1" name="mySetup_Tab1_2_productName1" class="small" style="width:70%" value="{$PRODUCT_NAME}" readonly="readonly" />
	                              					          				<input type="hidden" id="mySetup_Tab1_2_hdnProductId1" name="mySetup_Tab1_2_hdnProductId1" value="{$PRODUCT_ID}" />
	                              					          				&nbsp;<img id="mySetup_Tab1_2_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab1_2_productPickList(this,'{$MODULE}',1)" />
	                              					          			</td>
	                           					          			</tr>
	                              					      			<tr valign="bottom">
	                              					        			<td class="small" id="setComment2">
	                              					        				<textarea id="mySetup_Tab1_2_comment1" name="mySetup_Tab1_2_comment1" class="small" style="width:70%;height:40px"></textarea>

	                              					          				<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onclick="{literal}${/literal}('mySetup_Tab1_2_comment1').value=''"; style="cursor:pointer;" />
	                              					          			</td>
	                           					          			</tr>
	                            					      		</table>
	                            					      	</td>
	                              					    	<td align="left" valign="top" class="crmTableRow small lineOnTop" >
	                              					    		<input id="mySetup_Tab1_2_uom1" name="mySetup_Tab1_2_uom1" type="text"  style="width:50px" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn';" onblur="this.className='detailedViewTextBox';"  value=""/>
	                              					    		<input id="mySetup_Tab1_2_qty1" name="mySetup_Tab1_2_qty1" type="hidden" class="detailedViewTextBox"  style="width:50px" onfocus="this.className='detailedViewTextBoxOn'" onblur="settotalnoofrows_Setup_Disc();"/>
	                              					    		<input id="mySetup_Tab1_2_listPrice1" name="mySetup_Tab1_2_listPrice1" value="0.00" type="hidden" class="detailedViewTextBox"  style="width:70px" onblur=""/>
	                              					    	</td>
	                           					    	</tr>
	                                                {/if}   
													    <!-- Empty data Tab 1_2 -->
													     </table> 
													
													 <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
													   <!-- Add Product Button -->
													   <tr>
														<td colspan="3">
	                                                    	<input type="hidden" name="mySetup_Tab1_2_totalProductCount" id="mySetup_Tab1_2_totalProductCount" value="{$data.$mySetup_Tab1_2_totalProductCount}">
	                                                    	<button title="Add New Item" class="crmbutton small save" onclick="fnAdd_mySetup_Tab1_2('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button" style="width:70px">
																<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add</button>
															<!-- <input type="button" name="Button" class="crmbutton small create" value="Add" onclick="fnAdd_mySetup_Tab1_2('{$MODULE}','{$IMAGE_PATH}');" /> -->
														</td>
													   </tr>
	                              					</table>
	                                                </div>
	                                                
	                                            </div>
	                                        </div>
	                                        <!-- //setting product price -->
	                                        
	                                        
	                                        <!-- setting product  -->
	                                        <div class="detailedViewHeader"><input name="stpromotion" id="stpromotion2" type="radio"  value="2"><b>Setting Product</b></div>
	                                        <div class="toggle" style="display: block;">
	                                            <div class="easyui-tabs" style="width:1700px;">
	                                                
	                                                <div title="Product" style="padding:10px">
	                                                  {foreach key=row_no item=data from=$PROMOTION_TAB2 name=outer1}	
	                                                    {assign var="mySetup_Tab2_campaign_fomula_head" value="mySetup_Tab2_campaign_fomula_head"|cat:$row_no}
	                                                    {assign var="mySetup_Tab2_hearder_qty" value="mySetup_Tab2_hearder_qty"|cat:$row_no}
	                                                  {/foreach}
	                                                  <table width="100%" id="myformelement" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
	                                                      <tr>
	                                                        <td colspan="4" class="dvInnerHeader"><strong>Set Up Condition</strong></td>
	                                                      </tr>
	                                                     <tr>	
	                                                        <td align="right" class="dvtCellLabel">เงื่อนไข</td>
	                                                        <td align="left" class="dvtCellInfo">
	                                                        <select id="mySetup_Tab2_campaign_fomula_head" name="mySetup_Tab2_campaign_fomula_head" class="small" style="width:80px">
	                                                       		{if $data.$mySetup_Tab2_campaign_fomula_head eq "and"}
	                                                                {assign var=selected1 value="selected"}
	                                                            {else}
	                                                                {assign var=selected1 value=""}
	                                                            {/if}
	                                                            {if $data.$mySetup_Tab2_campaign_fomula_head eq "or"}
	                                                                {assign var=selected2 value="selected"}
	                                                            {else}
	                                                                {assign var=selected2 value=""}
	                                                            {/if}
	                                                            <option value='and' {$selected1}>and</option>
	                                                            <option value='or' {$selected2}>or</option>
	                                                        </select></td>
	                                                        
	                                                        <td align="right" class="dvtCellLabel">จำนวน</td>
	                                                        <td align="left" class="dvtCellInfo"><input type="text" style="width:100px" id="mySetup_Tab2_hearder_qty" name="mySetup_Tab2_hearder_qty" value="{$data.$mySetup_Tab2_hearder_qty}" class="detailedViewTextBox" onblur="this.className='detailedViewTextBox';check_number('mySetup_Tab2_hearder_qty');" onfocus="this.className='detailedViewTextBoxOn'"   /></td>
	                                                     </tr>	
	                                                  </table>	
	                                                  <br>
	                                                  <table width="100%" id="mySetup_Tab2" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
	                                                      <tr>
	                                                        <td colspan="4" class="dvInnerHeader"><strong>Set Up Product Detail</strong></td>
	                                                      </tr>
	                                                      <tr valign="top">
	                                                        <td width="5%" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
	                                                        <td width="40%" class="lvtCol" align="center"><b>ชื่อสินค้า </b></td>
	                                                        <td width="10%" class="lvtCol" align="left"><b>จำนวน</b></td>	
	                                                        <td width="50%" align="center" class="lvtCol"><strong>ชื่อพรีเมี่ยม</strong></td>
	                                                      </tr>
	                                                
	                                                {foreach key=row_no item=data from=$PROMOTION_TAB2 name=outer1}	
	                                                    {assign var="mySetup_Tab2_deleted" value="mySetup_Tab2_deleted"|cat:$row_no}
	                                                    {assign var="mySetup_Tab2_productName" value="mySetup_Tab2_productName"|cat:$row_no}
	                                                    {assign var="mySetup_Tab2_hdnProductId" value="mySetup_Tab2_hdnProductId"|cat:$row_no}
	                                                    {assign var="mySetup_Tab2_comment" value="mySetup_Tab2_comment"|cat:$row_no}
	                                                    {assign var="mySetup_Tab2_uom" value="mySetup_Tab2_uom"|cat:$row_no}
	                                                    {assign var="mySetup_Tab2_qty" value="mySetup_Tab2_qty"|cat:$row_no}    
	                                                    {assign var="mySetup_Tab2_listPrice" value="mySetup_Tab2_listPrice"|cat:$row_no} 
	                                                
	                                                    {assign var="mySetup_Tab2_campaign_fomula" value="mySetup_Tab2_campaign_fomula"|cat:$row_no}
	                                                    {assign var="mySetup_Tab2_premium" value="mySetup_Tab2_premium"|cat:$row_no}
	                                                    {assign var="mySetup_Tab2_totalProductCount" value="mySetup_Tab2_totalProductCount"|cat:$row_no}
	                                                      <tr valign="top"  id="mySetup_Tab2_row{$row_no}">
	                                                        <td width="5%" class="crmTableRow  small lineOnTop" align="center">
	                                                        {if $row_no neq 1}
	                                                            <img src="{'delete.gif'|@aicrm_imageurl:$THEME}" border="0" onclick="mySetup_Tab2_deleteRow('{$MODULE}',{$row_no},'{$IMAGE_PATH}')">
	                                                        {/if}<br/><br/>
	                                                        {if $row_no neq 1}
	                                                            &nbsp;<a href="javascript:mySetup_Tab2_moveUpDown('UP','{$MODULE}',{$row_no})" title="Move Upward"><img src="{'up_layout.gif'|@aicrm_imageurl:$THEME}" border="0"></a>
	                                                        {/if}
	                                                        {if not $smarty.foreach.outer1.last}
	                                                            &nbsp;<a href="javascript:mySetup_Tab2_moveUpDown('DOWN','{$MODULE}',{$row_no})" title="Move Downward"><img src="{'down_layout.gif'|@aicrm_imageurl:$THEME}" border="0" ></a>
	                                                        {/if}
	                                                        <input type="hidden" id="{$mySetup_Tab2_deleted}" name="{$mySetup_Tab2_deleted}" value="0" />
	                                                        </td>
	                                                        <td width="10%" class="crmTableRow small lineOnTop"><table width="100%"  border="0" cellspacing="0" cellpadding="1">
	                                                          <tr>
	                                                            <td class="small"><input type="text" id="{$mySetup_Tab2_productName}" name="{$mySetup_Tab2_productName}" class="small" style="width:70%" value="{$data.$mySetup_Tab2_productName}" readonly="readonly" />
	                                                              <input type="hidden" id="{$mySetup_Tab2_hdnProductId}" name="{$mySetup_Tab2_hdnProductId}" value="{$data.$mySetup_Tab2_hdnProductId}" />
	                                                              &nbsp;<img id="mySetup_Tab2_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab2_productPickList(this,'{$MODULE}',{$row_no})" /></td>
	                                                        </tr>
	                                                          <tr valign="bottom">
	                                                            <td class="small" id="setComment">
	                                                              <textarea id="{$mySetup_Tab2_comment}" name="{$mySetup_Tab2_comment}" class="small" style="width:70%;height:40px">{$data.$mySetup_Tab2_comment}</textarea>
	                                                              <img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onclick="{literal}${/literal}('{$mySetup_Tab2_comment}').value=''"; style="cursor:pointer;" /></td>
	                                                        </tr>
	                                                      </table></td>
	                                                        <td width="10%" align="left" class="crmTableRow small lineOnTop">
	                                                        <input id="{$mySetup_Tab2_qty}" name="{$mySetup_Tab2_qty}" type="text" class="detailedViewTextBox"  style="width:50px" onfocus="this.className='detailedViewTextBoxOn'" onblur="check_number('{$mySetup_Tab2_qty}');settotalnoofrows_mySetup_Tab2();" value="{$data.$mySetup_Tab2_qty}"/>
	                                                        <input id="{$mySetup_Tab2_uom}" name="{$mySetup_Tab2_uom}" type="hidden"  style="width:50px" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn';" onblur="this.className='detailedViewTextBox';"  value="{$data.$mySetup_Tab2_uom}"/>
	                                                        <input id="{$mySetup_Tab2_listPrice}" name="{$mySetup_Tab2_listPrice}" value="{$data.$mySetup_Tab2_listPrice}" type="hidden" class="detailedViewTextBox"  style="width:70px" onblur=""/>
	                                                        </td>	
	                                                        <td width="10%" class="crmTableRow small lineOnTop" >
	                                                        {if $row_no eq 1}
	                                                            <img id="mySetup_Disc_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab2_PremiumList(this,'{$MODULE}',{$row_no})" />

	                                                            <div style="float:right">เงื่อนไข:
	                                                            <select id="{$mySetup_Tab2_campaign_fomula}" name="{$mySetup_Tab2_campaign_fomula}" class="small" style="width:80px">
	                                                                {if $data.$mySetup_Tab2_campaign_fomula eq "and"}
	                                                                    {assign var=selected1 value="selected"}
	                                                                {else}
	                                                                    {assign var=selected1 value=""}
	                                                                {/if}
	                                                                {if $data.$mySetup_Tab2_campaign_fomula eq "or"}
	                                                                    {assign var=selected2 value="selected"}
	                                                                {else}
	                                                                    {assign var=selected2 value=""}
	                                                                {/if}
	                                                                <option value='and' {$selected1}>and</option>
	                                                                <option value='or' {$selected2}>or</option>
	                                                            </select></div>
	                                                            {$data.$mySetup_Tab2_premium}
	                                                        {/if}
	                                                          </td>
	                                                      </tr>	
	        											{/foreach}
	        											<!-- Empty Data Tab 2 -->
	        											{if empty($PROMOTION_TAB2)}
	                                                		<tr valign="top"  id="mySetup_Tab2_row1">
	                                  					    	<td width="5%" class="crmTableRow  small lineOnTop" align="center">
	                                                        		<input type="hidden" id="mySetup_Tab2_deleted1" name="mySetup_Tab2_deleted1" value="0" />
	                                                        	</td>
	    												  		<td width="10%" class="crmTableRow small lineOnTop">
	    												  			<table width="100%"  border="0" cellspacing="0" cellpadding="1">
	    												  	  			<tr>
	    												  	    			<td class="small">
	    												  	    				<input type="text" id="mySetup_Tab2_productName1" name="mySetup_Tab2_productName1" class="small" style="width:70%" value="{$PRODUCT_NAME}" readonly="readonly" />
	    												  	      				<input type="hidden" id="mySetup_Tab2_hdnProductId1" name="mySetup_Tab2_hdnProductId1" value="{$PRODUCT_ID}" />
	    												  	      				&nbsp;<img id="mySetup_Tab2_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab2_productPickList(this,'{$MODULE}',1)" />
	    												  	      			</td>
	  												  	    			</tr>
	    												  	  			<tr valign="bottom">
	    												  	    			<td class="small" id="setComment">
	                                                              				<textarea id="mySetup_Tab2_comment1" name="mySetup_Tab2_comment1" class="small" style="width:70%;height:40px"></textarea>
	    												  	      				<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onclick="{literal}${/literal}('mySetup_Tab2_comment1').value=''"; style="cursor:pointer;" />
	    												  	      			</td>
	  												  	   	 			</tr>
	  												  	  			</table>
	  												  	  		</td>
	    														<td width="10%" align="left" class="crmTableRow small lineOnTop">
	                                                        		<input id="mySetup_Tab2_qty1" name="mySetup_Tab2_qty1" type="text" class="detailedViewTextBox"  style="width:50px" onfocus="this.className='detailedViewTextBoxOn'" onblur="check_number('mySetup_Tab2_qty1');settotalnoofrows_mySetup_Tab2();"/>
	                                                        		<input id="mySetup_Tab2_uom1" name="mySetup_Tab2_uom1" type="hidden"  style="width:50px" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn';" onblur="this.className='detailedViewTextBox';"  value=""/>
	                                                        		<input id="mySetup_Tab2_listPrice1" name="mySetup_Tab2_listPrice1" value="0.00" type="hidden" class="detailedViewTextBox"  style="width:70px" onblur=""/>
	                                                        	</td>	
	    														<td width="10%" class="crmTableRow small lineOnTop" >
	                                                        		<img id="mySetup_Disc_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab2_PremiumList(this,'{$MODULE}',1)" />
	    													  		<div style="float:right">เงื่อนไข:
	    													    		<select id="mySetup_Tab2_campaign_fomula1" name="mySetup_Tab2_campaign_fomula1" style="width:80px">
	    													      			<option value='and'>and</option>
	    													      			<option value='or'>or</option>
	  													      			</select>
	  													    		</div>
	    													  		<div class="mySetup_Tab2_premium"></div>
	    													  		<input type="hidden" name="total_row_mySetup_Tab2_1" id="total_row_mySetup_Tab2_1" value="" /></td>
	   													 	</tr>
	                                                	{/if}
	                                                	<!-- Empty Data Tab 2 -->
	                                                     </table> 
	                                                     <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
	                                                       <!-- Add Product Button -->
	                                                       <tr>
	                                                        <td colspan="3">
	                                                                <input type="hidden" name="mySetup_Tab2_totalProductCount" id="mySetup_Tab2_totalProductCount" value="{$data.$mySetup_Tab2_totalProductCount}">
	                                                                <button title="Add New Item" class="crmbutton small save" onclick="fnAdd_mySetup_Tab2('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button" style="width:70px">
																	<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add</button>
	                                                                <!-- <input type="button" name="Button" class="crmbutton small create" value="Add" onclick="fnAdd_mySetup_Tab2('{$MODULE}','{$IMAGE_PATH}');" /> -->
	                                                        </td>
	                                                       </tr>
	                                                  </table>
	                                               </div>
	                                               
	                                            </div>
	                                        </div>
	                                        <!-- //setting product -->
	                                        
	                                        <!-- //setting discount -->
	                                        <div class="detailedViewHeader"><input name="stpromotion" id="stpromotion3" type="radio" value="3" ><b>Setting Discount</b></div>
	                                        <div class="toggle" style="display: block;">
	                                        	<div class="easyui-tabs" style="width:1700px;height:auto">
	                                                
	                                                <div title="Product" style="padding:10px">
	                                                  <table width="100%" id="myformelement" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
	                                                      <tr>
	                                                        <td colspan="4" class="dvInnerHeader"><strong>Set Up Discount Product Detail</strong></td>
	                                                      </tr>
	                                                     <tr>	
	                                                      {foreach key=row_no item=data from=$PROMOTION_TAB3 name=outer1}
	                                                      		{assign var="mySetup_Disc_Name" value="mySetup_Disc_Name"|cat:$row_no}
	                                                            {assign var="mySetup_Disc_Discount_type" value="disc_discount_type"|cat:$row_no}
	                                                            {assign var="mySetup_Disc_Dis_value" value="disc_dis_value"|cat:$row_no}
	                                                            {assign var="mySetup_Disc_total" value="disc_total"|cat:$row_no}
	                                                            {assign var="mySetup_Disc_Discount" value="disc_discount"|cat:$row_no}
	                                                            {assign var="mySetup_Disc_Net" value="disc_net"|cat:$row_no}
	                                                      {/foreach}
	                                                      
	                                                        <td align="right" class="dvtCellLabel">ชื่อส่วนลด</td>
	                                                        <td align="left" class="dvtCellInfo"><input type="text" style="width:100px" id="mySetup_Disc_Name" name="mySetup_Disc_Name" value="{$data.$mySetup_Disc_Name}" class="detailedViewTextBox" onBlur="this.className='detailedViewTextBox'" onFocus="this.className='detailedViewTextBoxOn'" ></td>
	                                                        
	                                                        <td align="right" class="dvtCellLabel">ประเภทส่วนลด</td>
	                                                        <td align="left" class="dvtCellInfo">
	                                                       
	                                                            <select id="mySetup_Disc_Discount_type" name="mySetup_Disc_Discount_type" class="small" style="width:80px" onchange="settotalnoofrows_Setup_Disc();">
	                                                            	  {if $data.$mySetup_Disc_Discount_type eq "%"}
	                                                                        {assign var=selected1 value="selected"}
	                                                                  {else}
	                                                                        {assign var=selected1 value=""}
	                                                                  {/if}
	                                                                  {if $data.$mySetup_Disc_Discount_type eq "baht"}
	                                                                        {assign var=selected2 value="selected"}
	                                                                  {else}
	                                                                        {assign var=selected2 value=""}
	                                                                  {/if}
	                                                                <option value=''></option>
	                                                                <option value='%' {$selected1}>%</option>
	                                                                <option value='baht' {$selected2}>baht</option>
	                                                            </select>
	                                                        <input type="text" style="width:100px" id="mySetup_Disc_Dis_value" name="mySetup_Disc_Dis_value" value="{$data.$mySetup_Disc_Dis_value}" class="detailedViewTextBox" onblur="this.className='detailedViewTextBox';check_number('mySetup_Disc_Dis_value');settotalnoofrows_Setup_Disc();" onfocus="this.className='detailedViewTextBoxOn'"   /></td>
	                                                     </tr>	
	                                                     <tr>	
	                                                        <td align="right" class="dvtCellLabel">รวมราคา</td>
	                                                        <td align="left" class="dvtCellInfo"><input name="mySetup_Disc_total" type="text" class="detailedViewTextBox" id="mySetup_Disc_total" style="width:100px;background-color: #CCC" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="{$data.$mySetup_Disc_total}" readonly="readonly"  ></td>
	                                                        
	                                                        <td align="right" class="dvtCellLabel">ส่วนลด</td>
	                                                        <td align="left" class="dvtCellInfo"><input name="mySetup_Disc_Discount" type="text" class="detailedViewTextBox" id="mySetup_Disc_Discount" style="width:100px;background-color: #CCC" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="{$data.$mySetup_Disc_Discount}" readonly="readonly" ></td>
	                                                     </tr>	
	                                                     <tr>	
	                                                        <td align="right" class="dvtCellLabel">รวมราคาทั้งสิ้น</td>
	                                                        <td align="left" class="dvtCellInfo"><input name="mySetup_Disc_Net" type="text" class="detailedViewTextBox" id="mySetup_Disc_Net" style="width:100px;background-color: #CCC" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="{$data.$mySetup_Disc_Net}" readonly="readonly" ></td>
	                                                        <td align="right" class="dvtCellLabel"></td>
	                                                        <td align="left" class="dvtCellInfo"></td>
	                                                     </tr>	
	                                                  </table>	
	                                                  <br>	  		
	                                                  <table width="100%" id="mySetup_Disc" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
	                                                      <tr>
	                                                        <td colspan="5" class="dvInnerHeader"><strong>Set Up Product Detail</strong></td>
	                                                      </tr>
	                                                      <tr valign="top">
	                                                        <td width=5% valign="top" class="lvtCol" align="center"><b>เครื่องมือ</b></td>
	                                                        <td width="40%" valign="top" class="lvtCol" align="center"><b>ชื่อสินค้า</b>
	                                                        
	                                                        </td>	
	                                                        <td width="10%" valign="top" class="lvtCol" align="left"><b>หน่วยนับ</b></td>
	                                                        <td width="10%" valign="top" class="lvtCol" align="left"><b>จำนวน</b></td>
	                                                        <td width="10%" valign="top" class="lvtCol" align="left"><b>ราคาต่อหน่วย</b></td>
	                                                      </tr>
	                                                
	                                                	{foreach key=row_no item=data from=$PROMOTION_TAB3 name=outer1}	
	                                               			{assign var="mySetup_Disc_deleted" value="mySetup_Disc_deleted"|cat:$row_no}
	                                                        {assign var="mySetup_Disc_hdnProductId" value="mySetup_Disc_hdnProductId"|cat:$row_no}
	                                                        {assign var="mySetup_Disc_productName" value="mySetup_Disc_productName"|cat:$row_no}
	                                                        {assign var="mySetup_Disc_comment" value="mySetup_Disc_comment"|cat:$row_no}
	                                                        {assign var="mySetup_Disc_qty" value="mySetup_Disc_qty"|cat:$row_no}
	                                                        {assign var="mySetup_Disc_listPrice" value="mySetup_Disc_listPrice"|cat:$row_no}
	                                                        {assign var="mySetup_Disc_uom" value="mySetup_Disc_uom"|cat:$row_no}
	                                                        {assign var="mySetup_Disc_totalProductCount" value="mySetup_Disc_totalProductCount"|cat:$row_no}
	                                                        
	                                                      <tr valign="top" id="mySetup_Disc_row{$row_no}">
	                                                        <td width=5% valign="top" class="crmTableRow  small lineOnTop" align="center">
	                                                        {if $row_no neq 1}
	                                                            <img src="{'delete.gif'|@aicrm_imageurl:$THEME}" border="0" onclick="mySetup_Disc_deleteRow('{$MODULE}',{$row_no},'{$IMAGE_PATH}')">
	                                                        {/if}<br/><br/>
	                                                        {if $row_no neq 1}
	                                                            &nbsp;<a href="javascript:mySetup_Disc_moveUpDown('UP','{$MODULE}',{$row_no})" title="Move Upward"><img src="{'up_layout.gif'|@aicrm_imageurl:$THEME}" border="0"></a>
	                                                        {/if}
	                                                        {if not $smarty.foreach.outer1.last}
	                                                            &nbsp;<a href="javascript:mySetup_Disc_moveUpDown('DOWN','{$MODULE}',{$row_no})" title="Move Downward"><img src="{'down_layout.gif'|@aicrm_imageurl:$THEME}" border="0" ></a>
	                                                        {/if}
	                                                        <input type="hidden" id="{$mySetup_Disc_deleted}" name="{$mySetup_Disc_deleted}" value="0"></td>
	                                                        <td width="10%" valign="top" class="crmTableRow small lineOnTop"><table width="100%"  border="0" cellspacing="0" cellpadding="1">
	                                                          <tr>
	                                                            <td class="small"><input type="text" id="{$mySetup_Disc_productName}" name="{$mySetup_Disc_productName}" class="small" style="width:70%" value="{$data.$mySetup_Disc_productName}" readonly="readonly" />
	                                                              <input type="hidden" id="{$mySetup_Disc_hdnProductId}" name="{$mySetup_Disc_hdnProductId}" value="{$data.$mySetup_Disc_hdnProductId}" />
	                                                              &nbsp;<img id="mySetup_Disc_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Disc_productPickList(this,'{$MODULE}',1)" /></td>
	                                                        </tr>
	                                                          <tr valign="bottom">
	                                                            <td class="small" id="setComment"><textarea id="{$mySetup_Disc_comment}" name="{$mySetup_Disc_comment}" class="small" style="width:70%;height:40px">{$data.$mySetup_Disc_comment}</textarea>
	                                                              <img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onclick="{literal}${/literal}('{$mySetup_Disc_comment}').value=''"; style="cursor:pointer;" /></td>
	                                                        </tr>
	                                                      </table></td>	
	                                                        <td width="10%" align="left" valign="top" class="crmTableRow small lineOnTop" ><input id="{$mySetup_Disc_uom}" name="{$mySetup_Disc_uom}" type="text"  style="width:50px" class="detailedViewTextBox_Noboder" onfocus="this.className='detailedViewTextBoxOn_Noboder';" onblur="this.className='detailedViewTextBox_Noboder';"  value="{$data.$mySetup_Disc_uom}"/></td>
	                                                        <td width="10%" align="left" valign="top" class="crmTableRow small lineOnTop"><input id="{$mySetup_Disc_qty}" name="{$mySetup_Disc_qty}" type="text" class="small " style="width:50px" onfocus="this.className='detailedViewTextBoxOn'" onblur="check_number('{$mySetup_Disc_qty}');settotalnoofrows_Setup_Disc();" value="{$data.$mySetup_Disc_qty}"/></td>
	                                                            <td width="10%" align="left" valign="top" class="crmTableRow small lineOnTop"><input id="{$mySetup_Disc_listPrice}" name="{$mySetup_Disc_listPrice}" value="{$data.$mySetup_Disc_listPrice}" type="text" class="small " style="width:70px" onblur="check_number('{$mySetup_Disc_listPrice}');settotalnoofrows_Setup_Disc();"/></td>
	                                                        
	                                                      </tr>	
	            										{/foreach}
	            										<!-- Empty Data Tab 3-->
	            										{if empty($PROMOTION_TAB3)}
	                                                    	<tr valign="top" id="mySetup_Disc_row1">
	    												  		<td width=5% valign="top" class="crmTableRow  small lineOnTop" align="center">
	    												  			<input type="hidden" id="mySetup_Disc_deleted1" name="mySetup_Disc_deleted1" value="0">
	    												  		</td>
	    														<td width="10%" valign="top" class="crmTableRow small lineOnTop">
	    															<table width="100%"  border="0" cellspacing="0" cellpadding="1">
	    													  			<tr>
	    													    			<td class="small">
	    													    				<input type="text" id="mySetup_Disc_productName1" name="mySetup_Disc_productName1" class="small" style="width:70%" value="{$PRODUCT_NAME}" readonly="readonly" />
	    													      				<input type="hidden" id="mySetup_Disc_hdnProductId1" name="mySetup_Disc_hdnProductId1" value="{$PRODUCT_ID}" />
	    													      				&nbsp;<img id="mySetup_Disc_searchIcon" title="Products" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Disc_productPickList(this,'{$MODULE}',1)" />
	    													      			</td>
	  													    			</tr>
	    													  			<tr valign="bottom">
	    													    			<td class="small" id="setComment">
	    													    				<textarea id="mySetup_Disc_comment1" name="mySetup_Disc_comment1" class="small" style="width:70%;height:40px"></textarea>
	    													     	 			<img src="{'clear_field.gif'|@aicrm_imageurl:$THEME}" onclick="{literal}${/literal}('mySetup_Disc_comment1').value=''"; style="cursor:pointer;" />
	    													     	 		</td>
	  													    			</tr>
	  													  			</table>
	  													  		</td>	
	    														<td width="10%" align="left" valign="top" class="crmTableRow small lineOnTop" >
	    															<input id="mySetup_Disc_uom1" name="mySetup_Disc_uom1" type="text"  style="width:50px" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn';" onblur="this.className='detailedViewTextBox';"  value=""/>
	    														</td>
	    														<td width="10%" align="left" valign="top" class="crmTableRow small lineOnTop">
	    															<input id="mySetup_Disc_qty1" name="mySetup_Disc_qty1" type="text" class="detailedViewTextBox"  style="width:50px" onfocus="this.className='detailedViewTextBoxOn'" onblur="check_number('mySetup_Disc_qty1');settotalnoofrows_Setup_Disc();"/>
	    														</td>
	    														<td width="10%" align="left" valign="top" class="crmTableRow small lineOnTop">
	    															<!-- <input id="mySetup_Disc_listPrice1" name="mySetup_Disc_listPrice1" value="0.00" type="text" class="detailedViewTextBox"  style="width:70px" onblur="check_number('mySetup_Disc_listPrice1');calcTotal();setDiscount(this,'1'); callTaxCalc(1);calcTotal();"/> -->
	    															<input id="mySetup_Disc_listPrice1" name="mySetup_Disc_listPrice1" value="0.00" type="text" class="detailedViewTextBox"  style="width:70px" onblur="check_number('mySetup_Disc_listPrice1');settotalnoofrows_Setup_Disc();"/>
	    														</td>
	    												  	</tr>	
	                                                	{/if}
	                                                     </table> 
	                                                     
	                                                     <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
	                                                       <!-- Add Product Button -->
	                                                       <tr>
	                                                        <td colspan="3">
	                                                          <input type="hidden" name="mySetup_Disc_totalProductCount" id="mySetup_Disc_totalProductCount" value="{$data.$mySetup_Disc_totalProductCount}">
	                                                          <button title="Add New Item" class="crmbutton small save" onclick="fnAddSetup_Disc('{$MODULE}','{$IMAGE_PATH}');" type="button" name="button" style="width:70px">
																	<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add</button>
	                                                          <!-- <input type="button" name="Button" class="crmbutton small create" value="Add" onclick="fnAddSetup_Disc('{$MODULE}','{$IMAGE_PATH}');" /> -->
	                                                        </td>
	                                                       </tr>
	                                                    </table>
	                                                </div>
	                                        
	                                        </div>
	                                        <!-- //setting discount -->
	                                        
	                                    </div>	

										</td>
							   		   </tr>
									{/if}

								   <tr>
									<td  colspan=4 style="padding:5px">
										<div align="right">
											{if $MODULE eq 'Emails'}
											<input title="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_TITLE}" accessKey="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_KEY}" class="crmbutton small create" onclick="window.open('index.php?module=Users&action=lookupemailtemplates&entityid={$ENTITY_ID}&entity={$ENTITY_TYPE}','emailtemplate','top=100,left=200,height=400,width=300,menubar=no,addressbar=no,status=yes')" type="button" name="button" value="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_LABEL}">
											<input title="{$MOD.LBL_SEND}" accessKey="{$MOD.LBL_SEND}" class="crmbutton small save" onclick="this.form.action.value='Save';this.form.send_mail.value='true'; return formValidate()" type="submit" name="button" value="  {$MOD.LBL_SEND}  " >
											{/if}
											
											<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';return check_save();" type="submit" name="button" style="width:70px">
												<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">
												&nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}
											</button>&nbsp;
											
											<button title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button">
												<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px; height: 17px; vertical-align: middle;">
												&nbsp;{$APP.LBL_CANCEL_BUTTON_LABEL}
											</button>
										</div>
                                       <input type="hidden" name="mySetup_Disc_chk" id="mySetup_Disc_chk" value="">
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
			    <!-- Basic Information Tab Closed -->

			    <!-- More Information Tab Opened -->
			    <div id="moreTab">
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
				   <tr>
					<td align=left>
					{*<!-- content cache -->*}
					
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
										
										<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  return formValidate()" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
										
                                        <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px">

									   </div>
									</td>
								   </tr>

								   {foreach key=header item=data from=$ADVBLOCKS}
								   <tr>
						         		<td colspan=4 class="detailedViewHeader">
                                                	        		<b>{$header}</b>
                                                         		</td>
                                                         	   </tr>

								   <!-- Here we should include the uitype handlings-->
                                                        	   {include file="DisplayFields.tpl"}

							 	   <tr style="height:25px"><td>&nbsp;</td></tr>
								   {/foreach}

								   <tr>
									<td  colspan=4 style="padding:5px">
									   <div align="center">
										{if $MODULE eq 'Emails'}
                                			
                                			<input title="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_TITLE}" accessKey="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_KEY}" class="crmbutton small create" onclick="window.open('index.php?module=Users&action=lookupemailtemplates&entityid={$ENTITY_ID}&entity={$ENTITY_TYPE}','emailtemplate','top=100,left=200,height=400,width=300,menubar=no,addressbar=no,status=yes')" type="button" name="button" value="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_LABEL}">
                                			
                                			<input title="{$MOD.LBL_SEND}" accessKey="{$MOD.LBL_SEND}" class="crmbutton small save" onclick="this.form.action.value='Save';this.form.send_mail.value='true'; return formValidate()" type="submit" name="button" value="  {$MOD.LBL_SEND}  " >
                                		{/if}
							
										<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  return formValidate()" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
										
										<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px">
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
		   </tr>
		</table>
	     </div>
	</td>
	<td align=right valign=top><img src="{'showPanelTopRight.gif'|@aicrm_imageurl:$THEME}"></td>
   </tr>
</table>
</form>

{if ($MODULE eq 'Emails' || 'Documents') and ($FCKEDITOR_DISPLAY eq 'true')}
       <script type="text/javascript" src="include/fckeditor/fckeditor.js"></script>
       <script type="text/javascript" defer="1">

       var oFCKeditor = null;

       {if $MODULE eq 'Documents'}
               oFCKeditor = new FCKeditor( "notecontent" ) ;
       {/if}

       oFCKeditor.BasePath   = "include/fckeditor/" ;
       oFCKeditor.ReplaceTextarea() ;

       </script>
{/if}
{if $MODULE eq 'Accounts'}
<script>
	ScrollEffect.limit = 201;
	ScrollEffect.closelimit= 200;
</script>
{/if}
<script>
	var fielduitype = new Array({$VALIDATION_DATA_UITYPE})
    var fieldname = new Array({$VALIDATION_DATA_FIELDNAME})
    var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL})
    var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE})
	show_tab({$TAB_SHOW});
	//show_tab(1);

	show_tab({$CHK_TAB});
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
<!-- END -->
