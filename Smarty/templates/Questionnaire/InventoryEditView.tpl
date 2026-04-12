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

<script src="modules/Questionnaire/survey/js/jquery.js"></script>
<script src="modules/Questionnaire/survey/js/survey.jquery.js"></script>
<link rel="stylesheet" href="modules/Questionnaire/survey/css/survey.css" />

<script type="text/javascript" src="asset/js/bootstrap.min.js"></script>
<script type="text/javascript" src="asset/js/jquery-ui-1.10.4.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css">
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>

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
			   
				<span class="lvtHeaderText"><font color="purple">[ {$USE_ID_VALUE} ] </font>{$NAME} - {$APP.LBL_EDITING} {$APP.LBL_INFORMATION} {$APP.SINGLE_Questionnaire} </span> <br>
				{$UPDATEINFO}	 
			{/if}
			{if $OP_MODE eq 'create_view'}
				<span class="lvtHeaderText">{$APP.LBL_CREATING} {$MOD[$SINGLE_MOD]}</span> <br>
			{/if}

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
                        <td class="dvtSelectedCell" align=center nowrap>{$APP[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</td>
	                    <!-- <td class="dvtTabCache" style="width:10px">&nbsp;</td>
                	    <td class="dvtTabCache" style="width:100%">&nbsp;</td> -->
                	    <td class="dvtTabCache" align="right" style="width:100%">

                	    	<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accesskey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save'; template_questionnaire(); displaydeleted(); return formValidate()" type="submit" name="button" style="width:70px"> 
		                    <img src="themes/softed/images/save_button_w.png" border="0" style="width: 15px; height: 17px; vertical-align: middle;">  {$APP.LBL_SAVE_BUTTON_LABEL}  
			                </button>

		                    <button title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accesskey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="Cancel">
		                    	<img src="themes/softed/images/cancel_o.png" border="0" style="width: 17px;height: 17px; vertical-align: middle;">  {$APP.LBL_CANCEL_BUTTON_LABEL}  
		                    </button>

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
								<td style="padding:10px">
								<!-- General details -->
									<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
									   <!-- <tr>
										<td  colspan=4 style="padding:5px">
										   <div align="center">
											<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  template_questionnaire(); displaydeleted(); return formValidate();" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
											<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px">
										   </div>
										</td>
									   </tr> -->

									   <!-- included to handle the edit fields based on ui types -->
									   {foreach key=header item=data from=$BLOCKS}
									      

									    {if $header eq $MOD.LBL_COMMENTS || $header eq $MOD.LBL_COMMENT_INFORMATION || $header eq 'Step 1 Plan Information'}
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
                                            <td colspan=4 class="detailedViewHeader">
                                               <img id="aidAccountInformation" src="themes/softed/images/slidedown_b.png" style="border: 0px solid #000000; width: 15px; height: 12px; margin-top: 2px;" alt="Hide" title="Hide">
						         			&nbsp;&nbsp;&nbsp;<b>{$header}</b>
                                            </td>
									    </tr>

										<!-- Handle the ui types display -->
									   {if $header eq "Answer Information" && $MODULE eq "Questionnaire"}
                                       		<input type="hidden" name="data_template" id="data_template" class="data_template" value="{$DATA_TEMPLATE}" >
                                       		<input type="hidden" name="qustionnairetemplate" id="qustionnairetemplate" value="{$DATA_TEMPLATE}" >
                                       		<input type="hidden" name="data_answer" id="data_answer" class="data_answer" value="{$DATA_ANSWER}" >
                                             <tr>
                                                <td  colspan=4 style="padding:5px">
                                                   {include_php file='modules/Questionnaire/survey/edit.html'}
                                                </td>
                                            </tr>
                                       {else}
                                          {include file="DisplayFields.tpl"}
                                       {/if}

									      <tr style="height:25px"><td>&nbsp;</td></tr>

									   {/foreach}

									   <tr>
										<td  colspan=4 style="padding:5px">
											<div align="right">
												<button title="{$APP.LBL_SAVE_BUTTON_TITLE}" accesskey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save'; template_questionnaire(); displaydeleted(); return formValidate()" type="submit" name="button" style="width:70px"> 
							                    	<img src="themes/softed/images/save_button_w.png" border="0" style="width: 17px; height: 17px; vertical-align: middle;">  {$APP.LBL_SAVE_BUTTON_LABEL}  
							                    </button>
							                    
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

<script>
{literal}

function template_questionnaire(){
	jQuery('.data_template').val('');
	jQuery('.data_template').val(JSON.stringify(survey.data));
	//jQuery('.data_template').val(editor.text);
}

{/literal}
	
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

	var ProductImages=new Array();
	var count=0;
	function delRowEmt(imagename)
	{ldelim}
		ProductImages[count++]=imagename;
		multi_selector.current_element.disabled = false;
		multi_selector.count--;
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
