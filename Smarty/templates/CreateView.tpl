{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*********************************************************************************/
-->*}
{*<!-- module header -->*}
<script type="text/javascript" src="asset/js/polyfiller.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-{$CALENDAR_LANG}.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="include/js/FieldDependencies.js"></script>

<!-- <script type="text/javascript" src="include/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="asset/js/jquery-ui-1.10.4.custom.min.js"></script> -->

<script type="text/javascript" src="asset/js/jquery.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>

<link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css">


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

		 <!-- <hr noshade size=1> -->
		 <hr style="border: 1px solid #F7F7F7;">
		 <br>

		{include file='EditViewHidden.tpl'}

		{*<!-- Account details tabs -->*}
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
		   <tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
				   <tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>

					{if $ADVBLOCKS neq ''}
						<td width=75 style="width:15%" align="center" nowrap class="dvtSelectedCell" id="bi" onclick="fnLoadValues('bi','mi','basicTab','moreTab','normal','{$MODULE}')"><b>{$APP.LBL_BASIC} {$APP.LBL_INFORMATION}</b></td>
                    	<td class="dvtUnSelectedCell" style="width: 100px;" align="center" nowrap id="mi" onclick="fnLoadValues('mi','bi','moreTab','basicTab','normal','{$MODULE}')"><b>{$APP.LBL_MORE} {$APP.LBL_INFORMATION} </b></td>
                   		<td class="dvtTabCache" style="width:65%" nowrap>&nbsp;</td>
					{else}
						<td class="dvtSelectedCell" align=center nowrap>{$APP.LBL_BASIC} {$APP.LBL_INFORMATION}</td>
	                    <!-- <td class="dvtTabCache" style="width:65%">&nbsp;</td> -->
	                    <td class="dvtTabCache" align="center" style="width:70%">
		                    {if $MODULE eq 'Smartemail'}
								<font color="#FF0000" size="+1">*** การจัดส่ง E-mail ระบบจะทำการจัดส่งวันละไม่เกิน 40,000 ฉบับ หากรายการ Smart Email ที่ทำการตั้งค่าไว้มีจำนวนมากกว่าที่กำหนด ระบบจะทำการจัดส่งส่วนที่เหลือให้อีกครั้งในวันถัดไป ***</font>
	                        {/if}

	                        {if $MODULE eq 'SmartSms'}
								<font color="#FF0000" size="+1">*** การจัดส่ง SMS ระบบจะทำการจัดส่งครั้งละไม่เกิน 30,000 เบอร์โทร ต่อ 1 รายการ Smart SMS<br /> ในการตั้งเวลาส่ง SMS ให้ตั้งเวลาเพิ่มจากเวลาจริงไปอีกอย่างน้อย 20 นาที ***</font>
	                        {/if}
                    	</td>
	                    <td class="dvtTabCache" align="right" style="width:30%">

	                    	<!-- <button title="Save [Alt+S]" accesskey="S" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidate('Accounts','accountname',this.form);" type="button" name="button" value="  Save  " style="width:70px">
	                    		<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;&nbsp;Save
	                    	</button> -->
	                    	{if $MODULE eq 'Accounts'}
								<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accesskey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidate('Accounts','accountname',this.form);" type="button" name="button" value=" Save " style="width:70px">
	                    			<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 15px; vertical-align: middle;">&nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}
	                    		</button>
                            {elseif $MODULE eq 'Leads'}
	                            <button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accesskey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidateleads('Leads','company','firstname','lastname',this.form);" type="button" name="button" value="  Save  " style="width:70px">
		                    		<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}
		                    	</button>
                            {else}
                            	<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accesskey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save'; return formValidate()" type="submit" name="button" style="width:70px">
		                    		<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}
		                    	</button>
		                    	<!-- <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  return formValidate()" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" > -->
                            {/if}

	                    	<button title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accesskey="X" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="Cancel">
	                    		<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px;height: 17px; vertical-align: middle;">  {$APP.LBL_CANCEL_BUTTON_LABEL}
	                    	</button>
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
							<td style="padding: 20px">
								<!-- General details -->
								<table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
									<!-- <tr>
										<td  colspan=4 style="padding:5px">
											<div align="center">
                                                {if $MODULE eq 'Accounts'}
													<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidate('Accounts','accountname',this.form);" type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
                                                {elseif $MODULE eq 'Leads'}
													<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidateleads('Leads','company','firstname','lastname',this.form);" type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
                                                {else}
													<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  return formValidate()" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
                                                {/if}
												<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" style="width:70px">
											</div>
                                            
										</td>
									</tr> -->
								   {foreach key=header item=data from=$BASBLOCKS}
									{if $header== $MOD.LBL_ADDRESS_INFORMATION && ($MODULE neq 'Accounts' || $MODULE != 'Contacts') && $MODULE != 'Leads' }
                                    <tr>
                                        <td colspan=4 class="detailedViewHeader">
                                         	<img id="aidAccountInformation" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide">
						         			&nbsp;&nbsp;&nbsp;<b>{$header}</b>
                                     	</td>

									</tr>
									{elseif $MODULE eq 'Quotes' && $header eq 'Billing Address'}
										 <td colspan=2 class="detailedViewHeader">
                                            <img id="aidAccountInformation" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide">
						         			&nbsp;&nbsp;&nbsp;<b>{$header}</b></td>
                                            <td class="detailedViewHeader">
                                            <input name="cpy" onclick="return copyAddressLeft(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_RCPY_ADDRESS}</b></td>
                                            <td class="detailedViewHeader">
                                            <input name="cpy" onclick="return copyAddressRight(EditView,'{$MODULE}')" type="radio"><b>{$APP.LBL_LCPY_ADDRESS}</b></td>
									{elseif $header== $MOD.LBL_ADDRESS_INFORMATION && $MODULE neq 'Contacts'}
																		  <tr>
	                                     <td colspan=4 class="detailedViewHeader">
	                                      <img id="aidAccountInformation" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide">
						         			&nbsp;&nbsp;&nbsp;<b>{$header}</b>
	                                      </td>
                                      </tr>
                                     {elseif $MODULE eq 'HelpDesk'}
									    	{if $header eq 'รายละเอียดเรื่องร้องขอ' }
									    		<tr class='case-tr-request'>
									    			<td colspan=4 class="detailedViewHeader">
									    				<img id="aidAccountInformation" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide">
						         			&nbsp;&nbsp;&nbsp;<b>{$header}</b>
									    			</td>
												</tr>
											{elseif $header eq 'รายละเอียดเรื่องแจ้งซ่อม' || $header eq 'ผลการตรวจสอบหรือแก้ไข รายการแจ้งซ่อม'}
												<tr class='case-tr-service'>
									    			<td colspan=4 class="detailedViewHeader">
									    				<img id="aidAccountInformation" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide">
						         			&nbsp;&nbsp;&nbsp;<b>{$header}</b>
									    			</td>
												</tr>
											{elseif $header eq 'รายละเอียดเรื่องร้องเรียน'}
												<tr class='case-tr-complain'>
									    			<td colspan=4 class="detailedViewHeader">
									    				<img id="aidAccountInformation" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide">
						         			&nbsp;&nbsp;&nbsp;<b>{$header}</b>
									    			</td>
												</tr>
									    	{else}
											    <tr>
													<td colspan=4 class="detailedViewHeader">
														<img id="aidAccountInformation" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide">
						         			&nbsp;&nbsp;&nbsp;<b>{$header}</b>
													</td>
												</tr>
									    	{/if}
                                     {else}
                                       <tr>
						         		<td colspan=4 class="detailedViewHeader">
						         			<img id="aidAccountInformation" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide">
						         			&nbsp;&nbsp;&nbsp;<b>{$header}</b>
						         		</td>
						         	   </tr>
						         	 {/if}
									   <!-- Here we should include the uitype handlings-->
									   {include file="DisplayFields.tpl"}

								   <tr style="height:25px"><td>&nbsp;</td></tr>
								   {/foreach}

									{if $MODULE eq 'Claim'}
								   	<tr>
								   		<td colspan="4">
											{include_php file="./modules/Claim/ProductDetail/product_list.php"}
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

										{if $MODULE eq 'Accounts'}
											<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidate('Accounts','accountname',this.form);" type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px">
												<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">
												&nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}
											</button>
										{elseif $MODULE eq 'Leads'}
	                            			<button title="Save [Alt+S]" accesskey="S" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidateleads('Leads','company','firstname','lastname',this.form);" type="button" name="button" value="  Save  " style="width:70px">
		                    				<img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}
		                    				</button>
										{else}
											<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accesskey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save'; return formValidate()" type="submit" name="button" style="width:70px">
					                    	<img src="themes/softed/images/save_button_w.png" border="0" style="width: 17px; height: 17px; vertical-align: middle;">&nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}
					                    	</button>
										{/if}

                                            <button title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  ">
                                            	<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px; height: 17px;vertical-align: middle;">
                                            	&nbsp;{$APP.LBL_CANCEL_BUTTON_LABEL}
                                            </button>
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

										{if $MODULE eq 'Accounts'}
											<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidate('Accounts','accountname',this.form);" type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
										{elseif $MODULE eq 'Leads'}
                                        	<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidateleads('Leads','company','firstname','lastname',this.form);" type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
                                        {else}
											<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate());" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
										{/if}

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

							            {if $MODULE eq 'Accounts'}
											<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidate('Accounts','accountname',this.form);" type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
										{elseif $MODULE eq 'Leads'}
                                        	<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  if(formValidate())AjaxDuplicateValidateleads('Leads','company','firstname','lastname',this.form);" type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
                                        {else}
											<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  return formValidate()" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
										{/if}

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

<script src="include/ckeditor/ckeditor.js"></script>

{if ($MODULE eq 'Emails' || 'Documents') and ($FCKEDITOR_DISPLAY eq 'true')}
       <script src="include/ckeditor/ckeditor.js"></script>
        {literal}
        <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'notecontent',
        {
            filebrowserUploadUrl: 'include/ckeditor/plugins/imgupload/imgupload.php'
        }
        );
        </script>
        {/literal}
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

{if ($MODULE eq 'KnowledgeBase') }
       <script src="include/ckeditor/ckeditor.js"></script>
        {literal}
        <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'know_detail',
        {
            filebrowserUploadUrl: 'include/ckeditor/plugins/imgupload/imgupload.php'
        }
        );
        </script>
        {/literal}
{/if}
{if ($MODULE eq 'KnowledgeBase') }
       <script src="include/ckeditor/ckeditor.js"></script>
        {literal}
        <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'know_detail_en',
        {
            filebrowserUploadUrl: 'include/ckeditor/plugins/imgupload/imgupload.php'
        }
        );
        </script>
        {/literal}
{/if}
{if ($MODULE eq 'Smartemail' || $MODULE eq 'Smartquestionnaire' ) }
       <script src="include/ckeditor/ckeditor.js"></script>
        {literal}
        <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'email_message',
        {
            filebrowserUploadUrl: 'include/ckeditor/plugins/imgupload/imgupload.php'
        }
        );
        </script>
        {/literal}
{/if}

{if ($MODULE eq 'Campaigns') }
       <script src="include/ckeditor/ckeditor.js"></script>
        {literal}
        <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'camp_detail',
        {
            filebrowserUploadUrl: 'include/ckeditor/plugins/imgupload/imgupload.php'
        }
        );
        </script>
        {/literal}
{/if}

{if ($MODULE eq 'Announcement') }
       <script src="include/ckeditor/ckeditor.js"></script>
        {literal}
        <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'detail',
        {
            filebrowserUploadUrl: 'include/ckeditor/plugins/imgupload/imgupload.php'
        }
        );
        </script>
        {/literal}
{/if}

{if ($MODULE eq 'Promotion') }
    <script src="include/ckeditor/ckeditor.js"></script>
    {literal}
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'promotion_detail',
        {
            filebrowserUploadUrl: 'include/ckeditor/plugins/imgupload/imgupload.php'
        }
        );
    </script>
    {/literal}
{/if}
<!-- END -->
