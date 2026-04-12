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
<!-- <script type="text/javascript" src="asset/js/polyfiller.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="asset/css/metro-blue/easyui.css">
<link rel="stylesheet" type="text/css" media="all" href="asset/css/icon.css">
<link rel="stylesheet" type="text/css" media="screen" href="asset/css/multi-select.css"  >
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<link rel="stylesheet" type="text/css" media="screen" href="asset/css/custom.css"  >

<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-{$CALENDAR_LANG}.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="include/js/FieldDependencies.js"></script>

<script type="text/javascript" src="asset/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.multi-select.js"></script> -->
<script type="text/javascript">
	var ProductImages=new Array();
	var count_p=0;
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
	height: auto;
	width: 90%;
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
function AddressSync(Addform,id)
{ldelim}
	checkAddress(Addform,id);
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
			{* vtlib customization: use translated label if available *}
			{assign var="SINGLE_MOD_LABEL" value=$SINGLE_MOD}
			{if $APP.$SINGLE_MOD} {assign var="SINGLE_MOD_LABEL" value=$APP.SINGLE_MOD} {/if}
				
			{if $OP_MODE eq 'edit_view'} 
				{assign var="USE_ID_VALUE" value=$MOD_SEQ_ID}
		  		{if $USE_ID_VALUE eq ''} {assign var="USE_ID_VALUE" value=$ID} {/if}			
				<span class="lvtHeaderText"><font color="purple">[ {$USE_ID_VALUE} ] </font>{$NAME} - {$APP.LBL_EDITING} {$SINGLE_MOD_LABEL} {$APP.LBL_INFORMATION}</span> <br>
				{$UPDATEINFO}	 
			{/if}
			{if $OP_MODE eq 'create_view'}
				<span class="lvtHeaderText">{$APP.LBL_CREATING} {$SINGLE_MOD_LABEL}</span> <br>
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
						<!-- <td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td> -->
						<td class="dvtSelectedCell" align=center nowrap>{$APP[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</td>
						<td class="dvtTabCache" style="width:10px">&nbsp;</td>
						<td class="dvtTabCache" style="width:100%">
							<div align="right">
								
								<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';displaydeleted();return check_save();"  type="submit" name="button" style="width:70px">
									<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">
									&nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}
								</button>&nbsp;
								
								<button title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button">
									<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px; height: 17px; vertical-align: middle;">
									&nbsp;{$APP.LBL_CANCEL_BUTTON_LABEL}
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

						<td align=left>
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



							<!-- This is added to display the existing comments -->
							{if $header eq $MOD.LBL_COMMENTS || $header eq $MOD.LBL_COMMENT_INFORMATION}
							   <tr><td>&nbsp;</td></tr>
							   <tr>
								<td colspan=4 class="dvInnerHeader">
							        	<b>{$MOD.LBL_COMMENT_INFORMATION}</b>
								</td>
							   </tr>
							   <tr>
								<td colspan=4 class="dvtCellInfo">{$COMMENT_BLOCK}</td>
							   </tr>
							   <tr><td>&nbsp;</td></tr>
							{/if}
								<tr>
										{if $header== $MOD.LBL_ADDRESS_INFORMATION && ($MODULE == 'Accounts' || $MODULE == 'Quotes' || $MODULE == 'PurchaseOrder' || $MODULE == 'SalesOrder'|| $MODULE == 'Invoice'|| $MODULE == 'Questionnaire'|| $MODULE == 'Member'|| $MODULE == 'EmailTarget'|| $MODULE == 'EmailTargetList')}
                                            <td colspan=2 class="detailedViewHeader">
                                            <b>{$header}</b></td>
                                           
                                            <td class="detailedViewHeader">
                                            <input name="cpy" onclick="return copyAddressLeft(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_RCPY_ADDRESS}</b></td>
                                            <td class="detailedViewHeader">
                                            <input name="cpy" onclick="return copyAddressRight(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_LCPY_ADDRESS}</b></td>
										{elseif $header== $MOD.LBL_ADDRESS_INFORMATION && $MODULE == 'Contacts'}
											<td colspan=2 class="detailedViewHeader">
	                                        <b>{$header}</b></td>
	                                        <td class="detailedViewHeader">
	                                        <input name="cpy" onclick="return copyAddressLeft(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_CPY_OTHER_ADDRESS}</b></td>
	                                        <td class="detailedViewHeader">
	                                        <input name="cpy" onclick="return copyAddressRight(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_CPY_MAILING_ADDRESS}</b></td>
                                        {else}
										<td colspan=4 class="detailedViewHeader">
											<b>{$header}</b>
										{/if}
										</td>
									      </tr>

										<!-- Handle the ui types display -->
										{include file="DisplayFields.tpl"}
										<tr style="height:25px"><td>&nbsp;</td></tr>
									   {/foreach}


									   <!-- Added to display the Product Details in Inventory-->
									   {if $MODULE eq 'Campaigns'}
							   		   <tr>
										<td colspan=4>
                                        
                                        <div>
                                        	
                                            <div class="detailedViewHeader"><b>Promotion Information</b></div>
                                            <div class="toggle" style="display: block;"> 
                                                
                                              	<table width="100%" id="mySetup_Tab1_2" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
                                                  	<tr>
                                                    	<td colspan="12" class="dvInnerHeader"></td>
                                                  	</tr>
                                                  	<tr valign="top">
	                              					    <td width="5%" align="center" valign="top" class="lvtCol"><b>เครื่องมือ</b></td>
	                              					    <td width="20%" align="center" valign="top" class="lvtCol"><b>ชื่อโปรโมชั่น</b></td>
	                              					    <td width="7%" align="center" valign="top" class="lvtCol"><b>ประเภทโปรโมชั่น</b></td>
	                              					    <td width="7%" align="center" valign="top" class="lvtCol"><b>วันที่เริ่มโปรโมชั่น</b></td>
	                              					    <td width="7%" align="center" valign="top" class="lvtCol"><b>วันที่สิ้นสุดโปรโมชั่น</b></td>
	                              					    <td width="7%" align="center" valign="top" class="lvtCol"><b>สถานะโปรโมชั่น</b></td>
	                              					    <td width="8%" align="center" valign="top" class="lvtCol"><b>ต้นทุน (งบประมาณ)</b></td>
	                              					    <td width="7%" align="center" valign="top" class="lvtCol"><b>ต้นทุน (จริง)</b></td>
	                              					    <td width="8%" align="center" valign="top" class="lvtCol"><b>จำนวนผู้เข้าร่วม (คาดหวัง)</b></td>
	                              					    <td width="8%" align="center" valign="top" class="lvtCol"><b>จำนวนผู้เข้าร่วม (จริง)</b></td>
	                              					    <td width="8%" align="center" valign="top" class="lvtCol"><b>รายได้ (คาดหวัง)</b></td>
	                              					    <td width="8%" align="center" valign="top" class="lvtCol"><b>รายได้ (จริง)</b></td>
                           					      	</tr>
                                            
                                                {foreach key=row_no item=data from=$PROMOTION_TAB name=outer1}	
                                                    {assign var="mySetup_Tab1_2_deleted" value="mySetup_Tab1_2_deleted"|cat:$row_no}
                                                    {assign var="mySetup_Tab1_2_hdnpromotionid" value="mySetup_Tab1_2_hdnpromotionid"|cat:$row_no}
                                                    {assign var="mySetup_Tab1_2_promotionname" value="mySetup_Tab1_2_promotionname"|cat:$row_no}
                                                    {assign var="promotiontype" value="promotiontype"|cat:$row_no}
                                                    {assign var="startdate" value="startdate"|cat:$row_no}
                                                    {assign var="enddate" value="enddate"|cat:$row_no}
                                                    {assign var="status" value="status"|cat:$row_no}
                                                    {assign var="budgetcost" value="budgetcost"|cat:$row_no}
                                                    {assign var="actualcost" value="actualcost"|cat:$row_no}
                                                    {assign var="expectedaudience" value="expectedaudience"|cat:$row_no}
                                                    {assign var="actualaudience" value="actualaudience"|cat:$row_no}
                                                    {assign var="expectedrevenue" value="expectedrevenue"|cat:$row_no}
                                                    {assign var="actualrevenue" value="actualrevenue"|cat:$row_no}
                                                    
                                                    {assign var="mySetup_Tab1_2_totalPromotionCount" value="mySetup_Tab1_2_totalPromotionCount"|cat:$row_no}

                                                      <tr valign="top"  id="mySetup_Tab1_2_row{$row_no}">
                                                        <td width="5%" class="crmTableRow  small lineOnTop" align="center">
                                                        {if $row_no neq 1}
                                                            <img src="{'delete.gif'|@aicrm_imageurl:$THEME}" border="0" onclick="mySetup_Tab1_2_deleteRow('{$MODULE}',{$row_no},'{$IMAGE_PATH}')">
                                                        {/if}<br/><br/>

                                                        {if $row_no neq 1}
                                                            &nbsp;<a href="javascript:mySetup_Tab1_2_moveUpDown('UP','{$MODULE}',{$row_no})" title="Move Upward"><img src="{'up_layout.gif'|@aicrm_imageurl:$THEME}" border="0"></a>
                                                        {/if}
                                                        
                                                        {if not $smarty.foreach.outer1.last}
                                                            &nbsp;<a href="javascript:mySetup_Tab1_2_moveUpDown('DOWN','{$MODULE}',{$row_no})" title="Move Downward"><img src="{'down_layout.gif'|@aicrm_imageurl:$THEME}" border="0" ></a>
                                                        {/if}
                                                        <input type="hidden" id="{$mySetup_Tab1_2_deleted}" name="{$mySetup_Tab1_2_deleted}" value="0" />
                                                        </td>

                                                        <td width="10%" class="crmTableRow small lineOnTop text-middle">
                                                        	<table width="100%"  border="0" cellspacing="0" cellpadding="1">
                                                          		<tr>
                                                            		<td class="small"><input type="text" id="{$mySetup_Tab1_2_promotionname}" name="{$mySetup_Tab1_2_promotionname}" class="small" style="width:85%" value="{$data.$mySetup_Tab1_2_promotionname}" readonly="readonly" />
                                                              		<input type="hidden" id="{$mySetup_Tab1_2_hdnpromotionid}" name="{$mySetup_Tab1_2_hdnpromotionid}" value="{$data.$mySetup_Tab1_2_hdnpromotionid}" />&nbsp;<img id="mySetup_Tab1_2_searchIcon" title="Promotion" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab1_2_productPickList(this,'{$MODULE}',{$row_no},'{$ID}')" />
                                                              		</td>
                                                        		</tr>
	                                                      	</table>
                                                  		</td>
                                                        <td align="left" class="crmTableRow small lineOnTop text-middle">
                                                        	<span id="{$promotiontype}">{$data.$promotiontype}</span>
                                                        </td>	
                                                        <td align="center" class="crmTableRow small lineOnTop text-middle">
                                                        	<span id="{$startdate}">{$data.$startdate}</span>
                                                        </td>
                                                        <td align="center" class="crmTableRow small lineOnTop text-middle">
                                                        	<span id="{$enddate}">{$data.$enddate}</span>
                                                        </td>
                                                        <td align="left" class="crmTableRow small lineOnTop text-middle">
                                                        	<span id="{$status}">{$data.$status}</span>
                                                        </td>
                                                        <td align="right" class="crmTableRow small lineOnTop text-middle">
                                                        	<span id="{$budgetcost}">{$data.$budgetcost}</span>
                                                        </td>
                                                        <td align="right" class="crmTableRow small lineOnTop text-middle">
                                                        	<span id="{$actualcost}">{$data.$actualcost}</span>
                                                        </td>
                                                        <td align="right" class="crmTableRow small lineOnTop text-middle">
                                                        	<span id="{$expectedaudience}">{$data.$expectedaudience}</span>
                                                        </td>
                                                        <td align="right" class="crmTableRow small lineOnTop text-middle">
                                                        	<span id="{$actualaudience}">{$data.$actualaudience}</span>
                                                        </td>
                                                        <td align="right" class="crmTableRow small lineOnTop text-middle">
                                                        	<span id="{$expectedrevenue}">{$data.$expectedrevenue}</span>
                                                        </td>
                                                        <td align="right" class="crmTableRow small lineOnTop text-middle">
                                                        	<span id="{$actualrevenue}">{$data.$actualrevenue}</span>
                                                        </td>
                                                      </tr>	
        										{/foreach}
    											<!-- Empty Data -->
    											{if empty($PROMOTION_TAB)}
                                            		<tr valign="top" id="mySetup_Tab1_2_row1">
                              					    	<td valign="top" class="crmTableRow small lineOnTop" align="center">
                                                    		<input type="hidden" id="mySetup_Tab1_2_deleted1" name="mySetup_Tab1_2_deleted1" value="0" />
                                                    	</td>
                              					    	<td valign="top" class="crmTableRow small lineOnTop text-middle">
                              					    		<table width="100%"  border="0" cellspacing="0" cellpadding="1">
                              					    			<tr>
                              					        			<td class="small">
                              					        				<input type="text" id="mySetup_Tab1_2_promotionname1" name="mySetup_Tab1_2_promotionname1" class="small" style="width:85%" value="" readonly="readonly" />
                              					          				<input type="hidden" id="mySetup_Tab1_2_hdnpromotionid1" name="mySetup_Tab1_2_hdnpromotionid1"   value="" />
                              					          				&nbsp;<img id="mySetup_Tab1_2_searchIcon" title="Promotion" src="{'products.gif'|@aicrm_imageurl:$THEME}" style="cursor: pointer;" align="absmiddle" onclick="mySetup_Tab1_2_productPickList(this,'{$MODULE}',1,'{$ID}')" />
                              					          			</td>
                           					          			</tr>
                            					      		</table>
                            					      	</td>
                            					      	<!-- ประเภทโปรโมชั่น -->
                              					    	<td align="left" class="crmTableRow small lineOnTop text-middle" >
                              					    		<span id="promotiontype1"></span>
                              					    	</td>
                              					    	<!-- วันที่เริ่มโปรโมชั่น -->
                              					    	<td align="center" class="crmTableRow small lineOnTop text-middle" >
                              					    		<span id="startdate1" ></span>
                              					    	</td>
                              					    	<!-- วันที่สิ้นสุดโปรโมชั่น -->
                              					    	<td align="center"  class="crmTableRow small lineOnTop text-middle" >
                              					    		<span id="enddate1" ></span>
                              					    	</td>
                              					    	<!-- สถานะโปรโมชั่น -->
                              					    	<td align="left"  class="crmTableRow small lineOnTop text-middle" >
                              					    		<span id="status1" ></span>
                              					    	</td>
                              					    	<!-- ต้นทุน -->
                              					    	<td align="right"  class="crmTableRow small lineOnTop text-middle" >
                              					    		<span id="budgetcost1" ></span>
                              					    	</td>
                              					    	<!-- ต้นทุน (จริง) -->
                              					    	<td align="right"  class="crmTableRow small lineOnTop text-middle" >
                              					    		<span id="actualcost1" ></span>
                              					    	</td>
                              					    	<!-- จำนวนผู้เข้าร่วม (คาดหวัง) -->
                              					    	<td align="right"  class="crmTableRow small lineOnTop text-middle" >
                              					    		<span id="expectedaudience1" ></span>
                              					    	</td>
                              					    	<!-- จำนวนผู้เข้าร่วม (จริง) -->
                              					    	<td align="right"  class="crmTableRow small lineOnTop text-middle" >
                              					    		<span id="actualaudience1" ></span>
                              					    	</td>
                              					    	<!-- รายได้ (คาดหวัง) -->
                              					    	<td align="right"  class="crmTableRow small lineOnTop text-middle" >
                              					    		<span id="expectedrevenue1" ></span>
                              					    	</td>
                              					    	<!-- รายได้ (จริง) -->
                              					    	<td align="right"  class="crmTableRow small lineOnTop text-middle" >
                              					    		<span id="actualrevenue1" ></span>
                              					    	</td>
                           					      	</tr>
                                            	{/if}
                                            	<!-- Empty Data -->
                                                </table>
                                                
                                                <!-- Sumary -->
                                                {foreach key=row_no item=data from=$PROMOTION_TAB_SUM name=outer2}
                                                	{assign var="totalbudgetcost" value="totalbudgetcost"}
                                                    {assign var="totalactualcost" value="totalactualcost"}
                                                    {assign var="totalexpectedaudience" value="totalexpectedaudience"}
                                                    {assign var="totalactualaudience" value="totalactualaudience"}
                                                    {assign var="totalexpectedrevenue" value="totalexpectedrevenue"}
                                                    {assign var="totalactualrevenue" value="totalactualrevenue"}
                                                    <table id="totalPromotion" width="100%" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
														<tr valign="top">
	                              					    	<td width="5%" class="crmTableRow small lineOnTop" align="center">
	                                                    		<span >Total</span>
	                                                    	</td>
	                              					    	<td width="20%" valign="top" class="crmTableRow small lineOnTop">	
	                            					      	</td>
	                            					      	<!-- ประเภทโปรโมชั่น -->
	                              					    	<td width="7%" align="left" class="crmTableRow small lineOnTop text-middle"></td>
	                              					    	<!-- วันที่เริ่มโปรโมชั่น -->
	                              					    	<td width="7%" align="center" class="crmTableRow small lineOnTop text-middle"></td>
	                              					    	<!-- วันที่สิ้นสุดโปรโมชั่น -->
	                              					    	<td width="7%" align="center"  class="crmTableRow small lineOnTop text-middle"></td>
	                              					    	<!-- สถานะโปรโมชั่น -->
	                              					    	<td width="7%" align="left"  class="crmTableRow small lineOnTop text-middle"></td>
	                              					    	<!-- ต้นทุน -->
	                              					    	<td width="8%" align="right"  class="crmTableRow small lineOnTop text-middle">
	                              					    		<span id="{$totalbudgetcost}">{$data.$totalbudgetcost}</span>
	                              					    	</td>
	                              					    	<!-- ต้นทุน (จริง) -->
	                              					    	<td width="7%" align="right"  class="crmTableRow small lineOnTop text-middle">
	                              					    		<span id="{$totalactualcost}">{$data.$totalactualcost}</span>
	                              					    	</td>
	                              					    	<!-- จำนวนผู้เข้าร่วม (คาดหวัง) -->
	                              					    	<td width="8%" align="right"  class="crmTableRow small lineOnTop text-middle">
	                              					    		<span id="{$totalexpectedaudience}">{$data.$totalexpectedaudience}</span>
	                              					    	</td>
	                              					    	<!-- จำนวนผู้เข้าร่วม (จริง) -->
	                              					    	<td width="8%" align="right"  class="crmTableRow small lineOnTop text-middle">
	                              					    		<span id="{$totalactualaudience}">{$data.$totalactualaudience}</span>
	                              					    	</td>
	                              					    	<!-- รายได้ (คาดหวัง) -->
	                              					    	<td width="8%" align="right"  class="crmTableRow small lineOnTop text-middle">
	                              					    		<span id="{$totalexpectedrevenue}">{$data.$totalexpectedrevenue}</span>
	                              					    	</td>
	                              					    	<!-- รายได้ (จริง) -->
	                              					    	<td width="8%" align="right"  class="crmTableRow small lineOnTop text-middle">
	                              					    		<span id="{$totalactualrevenue}">{$data.$totalactualrevenue}</span>
	                              					    	</td>
	                           					      	</tr>
													</table>
                                                {/foreach}
                                                <!-- Sumary -->

                                                {if empty($PROMOTION_TAB_SUM)}
                                                	<table id="totalPromotion" width="100%" border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
														<tr valign="top">
	                              					    	<td width="5%" class="crmTableRow small lineOnTop" align="center">
	                                                    		<span >Total</span>
	                                                    	</td>
	                              					    	<td width="20%" valign="top" class="crmTableRow small lineOnTop">	
	                            					      	</td>
	                            					      	<!-- ประเภทโปรโมชั่น -->
	                              					    	<td width="7%" align="left" class="crmTableRow small lineOnTop text-middle"></td>
	                              					    	<!-- วันที่เริ่มโปรโมชั่น -->
	                              					    	<td width="7%" align="center" class="crmTableRow small lineOnTop text-middle"></td>
	                              					    	<!-- วันที่สิ้นสุดโปรโมชั่น -->
	                              					    	<td width="7%" align="center"  class="crmTableRow small lineOnTop text-middle"></td>
	                              					    	<!-- สถานะโปรโมชั่น -->
	                              					    	<td width="7%" align="left"  class="crmTableRow small lineOnTop text-middle"></td>
	                              					    	<!-- ต้นทุน -->
	                              					    	<td width="8%" align="right"  class="crmTableRow small lineOnTop text-middle">
	                              					    		<span id="totalbudgetcost" ></span>
	                              					    	</td>
	                              					    	<!-- ต้นทุน (จริง) -->
	                              					    	<td width="7%" align="right"  class="crmTableRow small lineOnTop text-middle">
	                              					    		<span id="totalactualcost" ></span>
	                              					    	</td>
	                              					    	<!-- จำนวนผู้เข้าร่วม (คาดหวัง) -->
	                              					    	<td width="8%" align="right"  class="crmTableRow small lineOnTop text-middle">
	                              					    		<span id="totalexpectedaudience" ></span>
	                              					    	</td>
	                              					    	<!-- จำนวนผู้เข้าร่วม (จริง) -->
	                              					    	<td width="8%" align="right"  class="crmTableRow small lineOnTop text-middle">
	                              					    		<span id="totalactualaudience" ></span>
	                              					    	</td>
	                              					    	<!-- รายได้ (คาดหวัง) -->
	                              					    	<td width="8%" align="right"  class="crmTableRow small lineOnTop text-middle">
	                              					    		<span id="totalexpectedrevenue" ></span>
	                              					    	</td>
	                              					    	<!-- รายได้ (จริง) -->
	                              					    	<td width="8%" align="right"  class="crmTableRow small lineOnTop text-middle">
	                              					    		<span id="totalactualrevenue" ></span>
	                              					    	</td>
	                           					      	</tr>
													</table>
                                                {/if}
                                                
                                                <table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
												   	<tr>
														<td colspan="3">
	                                                    	<input type="hidden" name="mySetup_Tab1_2_totalPromotionCount" id="mySetup_Tab1_2_totalPromotionCount" value="{$COUNTPROMOTION_TAB}">
	                                                    	<button title="Add New Item" class="crmbutton small save" onclick="fnAdd_mySetup_Tab1_2('{$MODULE}','{$IMAGE_PATH}','{$ID}');" type="button" name="button" style="width:70px">
																<img src="themes/softed/images/plus_w.png" border="0" style="width: 10px; height: 10px; vertical-align: middle;">&nbsp;Add</button>
														</td>
												   	</tr>
                              					</table>
                                            </div>	

										</td>
							   		   </tr>
									   {/if}

									   <tr>
										<td  colspan=4 style="padding:5px">
											<div align="right">
                                                
                                                <button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';displaydeleted();return check_save();"  type="submit" name="button" style="width:70px">
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
				</td>
			   </tr>
			</table>
		<div>
	</td>
	<td align=right valign=top><img src="{'showPanelTopRight.gif'|@aicrm_imageurl:$THEME}"></td>
   </tr>
</table>
<!--added to fix 4600-->
<input name='search_url' id="search_url" type='hidden' value='{$SEARCH}'>
</form>

{if ($MODULE eq 'Emails' || $MODULE eq  'Documents') and ($FCKEDITOR_DISPLAY eq 'true')}
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
	function delRowEmt(imagename)
	{ldelim}
		ProductImages[count_p++]=imagename;
		multi_selector.current_element.disabled = false;
		multi_selector.count_p--;
	{rdelim}
	function displaydeleted()
	{ldelim}
		if(ProductImages.length > 0)
			document.EditView.del_file_list.value=ProductImages.join('###');
	{rdelim}

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
