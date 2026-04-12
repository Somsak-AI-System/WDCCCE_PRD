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
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="{'showPanelTopLeft.gif'|@aicrm_imageurl:$THEME}"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
<br>
	<form action="index.php?module=Settings&action=add2db" method="post" name="index" enctype="multipart/form-data" onsubmit="VtigerJS_DialogBox.block();">
 	<input type="hidden" name="return_module" value="Settings">
 	<input type="hidden" name="parenttab" value="Settings">
    	<input type="hidden" name="return_action" value="OrganizationConfig">
	<div align=center>
			{include file="SetMenu.tpl"}	
				<!-- DISPLAY -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="{'company.gif'|@aicrm_imageurl:$THEME}" width="48" height="48" border=0 ></td>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > {$MOD.LBL_EDIT} {$MOD.LBL_COMPANY_DETAILS} </b></td>
				</tr>
				<tr>
					<td valign=top class="small">{$MOD.LBL_COMPANY_DESC}</td>
				</tr>
				</table>
				
				<br>
				<table border=0 cellspacing=0 cellpadding=10 width=100% >
				<tr>
				<td>
				
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
					<tr>
						<td class="big"><strong>{$MOD.LBL_COMPANY_DETAILS} </strong>
						{$ERRORFLAG}<br>
						</td>
						<td class="small" align=right>
							<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmButton small save" type="submit" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" onclick="return verify_data(form,'{$MOD.LBL_ORGANIZATION_NAME}');" >
							<input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmButton small cancel" onclick="window.history.back()" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
						</td>
					</tr>
					</table>
					
					<table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
					<tr>
						<td class="small" valign=top ><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td width="20%" class="small cellLabel"><font color="red">*</font><strong>{$MOD.LBL_ORGANIZATION_NAME}</strong></td>
                            <td width="80%" class="small cellText">
				<input type="text" name="organization_name" class="detailedViewTextBox small" value="{$ORGANIZATIONNAME}">
				<input type="hidden" name="org_name" value="{$ORGANIZATIONNAME}">
			    </strong></td>
                          </tr>
                <tr valign="top">
                            <td class="small cellLabel"><strong>{$MOD.LBL_ORGANIZATION_LOGO}</strong></td>
			   				 {if $ORGANIZATIONLOGONAME neq ''}	
                            <td class="small cellText" style="background-image: url(test/logo/{$ORGANIZATIONLOGONAME}); background-position: left; background-repeat: no-repeat;" width="48" height="48" border="0" >
			   				 {else}
                            <td class="small cellText" style="background-image: url(include/images/noimage.gif); background-position: left; background-repeat: no-repeat;" width="48" height="48" border="0" >
			    			 {/if}	
				<br><br><br><br>
                {$MOD.LBL_SELECT_LOGO} 
							<INPUT TYPE="HIDDEN" NAME="MAX_FILE_SIZE" VALUE="800000">
		             		<INPUT TYPE="HIDDEN" NAME="PREV_FILE" VALUE="{$ORGANIZATIONLOGONAME}">	 
                            <input type="file" name="binFile" class="small" value="{$ORGANIZATIONLOGONAME}" onchange="validateFilename(this);">[{$ORGANIZATIONLOGONAME}]
                            <input type="hidden" name="binFile_hidden" value="{$ORGANIZATIONLOGONAME}" />
			      </td>
                </tr>
                          
                          
                          <tr valign="top">
                            	<td class="small cellLabel"><strong>{$MOD.LBL_ORGANIZATION_FOOTER}</strong></td>
						    {if $ORGANIZATIONIMGFOOTER neq ''}	
			                	<td class="small cellText" style="background-image: url(test/footer/{$ORGANIZATIONIMGFOOTER}); background-position: left; background-repeat: no-repeat;" width="48" height="48" border="0" >
						    {else}
			                	<td class="small cellText" style="background-image: url(include/images/noimage.gif); background-position: left; background-repeat: no-repeat;" width="48" height="48" border="0" >
						    {/if}	
							<br><br><br><br>
			                {$MOD.LBL_SELECT_FOOTER} 
					            <INPUT TYPE="HIDDEN" NAME="PREVFOOTER_FILE" VALUE="{$ORGANIZATIONIMGFOOTER}">	 
			                    <input type="file" name="binFooterFile" class="small" value="{$ORGANIZATIONIMGFOOTER}" onchange="validateFilename(this);">[{$ORGANIZATIONIMGFOOTER}]
			                    <input type="hidden" name="binFooterFile_hidden" value="{$ORGANIZATIONIMGFOOTER}" />
			     			 </td>
                          </tr>
                          
                          
                          <tr>
                            <td class="small cellLabel"><strong>{$MOD.LBL_ORGANIZATION_ADDRESS}</strong></td>
                            <td class="small cellText"><input type="text" name="organization_address" class="detailedViewTextBox small" value="{$ORGANIZATIONADDRESS}"></td>
                          </tr>
                          <tr> 
                            <td class="small cellLabel"><strong>{$MOD.LBL_ORGANIZATION_CITY}</strong></td>
                            <td class="small cellText"><input type="text" name="organization_city" class="detailedViewTextBox small" value="{$ORGANIZATIONCITY}"></td>
                          </tr>
                          <tr>
                            <td class="small cellLabel"><strong>{$MOD.LBL_ORGANIZATION_STATE}</strong></td>
                            <td class="small cellText"><input type="text" name="organization_state" class="detailedViewTextBox small" value="{$ORGANIZATIONSTATE}"></td>
                          </tr>
                          <tr>
                            <td class="small cellLabel"><strong>{$MOD.LBL_ORGANIZATION_CODE}</strong></td>
                            <td class="small cellText"><input type="text" name="organization_code" class="detailedViewTextBox small" value="{$ORGANIZATIONCODE}"></td>
                          </tr>
                          <tr>
                            <td class="small cellLabel"><strong>{$MOD.LBL_ORGANIZATION_COUNTRY}</strong></td>
                            <td class="small cellText"><input type="text" name="organization_country" class="detailedViewTextBox small" value="{$ORGANIZATIONCOUNTRY}"></td>

                          </tr>
                          <tr>
                            <td class="small cellLabel"><strong>{$MOD.LBL_ORGANIZATION_PHONE}</strong></td>
                            <td class="small cellText"><input type="text" name="organization_phone" class="detailedViewTextBox small" value="{$ORGANIZATIONPHONE}"></td>
                          </tr>
                          <tr>
                            <td class="small cellLabel"><strong>{$MOD.LBL_ORGANIZATION_FAX}</strong></td>
                            <td class="small cellText"><input type="text" name="organization_fax" class="detailedViewTextBox small" value="{$ORGANIZATIONFAX}"></td>
                          </tr>
                          <tr>
                            <td class="small cellLabel"><strong>{$MOD.LBL_ORGANIZATION_TAX}</strong></td>
                            <td class="small cellText"><input type="text" name="organization_tax" class="detailedViewTextBox small" value="{$ORGANIZATIONTAX}"></td>
                          </tr>
                          <tr>
                            <td class="small cellLabel"><strong>{$MOD.LBL_ORGANIZATION_WEBSITE}</strong></td>
                            <td class="small cellText"><input type="text" name="organization_website" class="detailedViewTextBox small" value="{$ORGANIZATIONWEBSITE}"></td>
                          </tr>
                          <tr>
                            <td class="small cellLabel"><strong>Email</strong></td>
                            <td class="small cellText"><input type="text" name="organization_email" class="detailedViewTextBox small" value="{$ORGANIZATION_EMAIL}"></td>
                          </tr>                          
                        </table>

<!--                    <table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
					<tr>
						<td class="big"><strong>Email Notification</strong></td>
					</tr>
					</table>

					<table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
					<tr>
						<td class="small" valign=top ><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td width="20% "class="small cellLabel"><strong>Email Notification</strong></td>
                            <td width="80%" class="small cellText"><textarea  cols="5" rows="5" name="organization_emailnotify" class="detailedViewTextBox small" />{$ORGANIZATIONEMAILNOTIFY}</textarea>
                            
                            </td>
                          </tr>
                          </td>
                      </tr>
                      </table>-->
                      
					 <table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
					<tr>
						<td class="big"><strong>Company Config</strong></td>
					</tr>
					</table>
					
					<table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
					<tr>
						<td class="small" valign=top >
                        <table width="100%"  border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td width="20%" class="small cellLabel"><strong>Limit Time</strong></td>
                            <td width="80%" class="small cellText"><input type="text" style="width:50px;" name="organization_limit_time" class="detailedViewTextBox small" value="{$ORGANIZATIONLIMITTIME}" /> Minute (<strong>Example</strong> 60 Minute)
                            </td>
                          </tr>
                          <tr>
                            <td width="20%" class="small cellLabel"><strong>Project Code Mobile</strong></td>
                            <td width="80%" class="small cellText"><input type="text" style="width:50px; background-color:#CCC" name="project_code" class="detailedViewTextBox small" value="{$PROJECT_CODE}" readonly="readonly" />
                            </td>
                          </tr>
                          <!--<tr>
                            <td width="20%" class="small cellLabel"><strong>Campaigns Batn/Point</strong></td>
                            <td width="80%" class="small cellText"><input type="text" style="width:50px;" name="organization_cam_bath" class="detailedViewTextBox small" value="{$ORGANIZATION_CAM_BATH}"/>
                            </td>
                          </tr>
                          <tr>
                            <td width="20%" class="small cellLabel"><strong>Point EXP</strong></td>
                            <td width="80%" class="small cellText"><input type="text" style="width:50px;" name="organization_exp_year" class="detailedViewTextBox small" value="{$ORGANIZATION_EXP_YEAR}"/>
                            </td>
                          </tr>
                          <tr>
                            <td width="20%" class="small cellLabel"><strong>SMS URL</strong></td>
                            <td width="80%" class="small cellText"><input type="text" style="" name="organization_sms_url" class="detailedViewTextBox small" value="{$ORGANIZATIONSMSURL}" /> 
                            </td>
                          </tr>                          
                          <tr>
                            <td width="20%" class="small cellLabel"><strong>SMS Sender Name</strong></td>
                            <td width="80%" class="small cellText"><input type="text" style="width:200px;" name="organization_sms_sendername" class="detailedViewTextBox small" value="{$ORGANIZATIONSMSSENDERNAME}" /> 
                            </td>
                          </tr>
                          <tr>
                            <td width="20%" class="small cellLabel"><strong>SMS Username</strong></td>
                            <td width="80%" class="small cellText"><input type="text" style="width:200px;" name="organization_sms_username" class="detailedViewTextBox small" value="{$ORGANIZATIONSMSUSERNAME}" />
                            </td>
                          </tr>
                          <tr>
                            <td width="20%" class="small cellLabel"><strong>SMS Password</strong></td>
                            <td width="80%" class="small cellText"><input type="password" style="width:200px;" name="organization_sms_password" class="detailedViewTextBox small" value="{$ORGANIZATIONSMSPASSWORD}" /> 
                            </td>
                          </tr>
                          <tr>
                            <td width="20%" class="small cellLabel"><strong>Questionnaire Back Up</strong></td>
                            <td width="80%" class="small cellText">
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "01"}{assign var=selected01 value="selected"}{else}{assign var=selected01 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "02"}{assign var=selected02 value="selected"}{else}{assign var=selected02 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "03"}{assign var=selected03 value="selected"}{else}{assign var=selected03 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "04"}{assign var=selected04 value="selected"}{else}{assign var=selected04 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "05"}{assign var=selected05 value="selected"}{else}{assign var=selected05 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "06"}{assign var=selected06 value="selected"}{else}{assign var=selected06 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "07"}{assign var=selected07 value="selected"}{else}{assign var=selected07 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "08"}{assign var=selected08 value="selected"}{else}{assign var=selected08 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "09"}{assign var=selected09 value="selected"}{else}{assign var=selected09 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "10"}{assign var=selected10 value="selected"}{else}{assign var=selected10 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "11"}{assign var=selected11 value="selected"}{else}{assign var=selected11 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "12"}{assign var=selected12 value="selected"}{else}{assign var=selected12 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "13"}{assign var=selected13 value="selected"}{else}{assign var=selected13 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "14"}{assign var=selected14 value="selected"}{else}{assign var=selected14 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "15"}{assign var=selected15 value="selected"}{else}{assign var=selected15 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "16"}{assign var=selected16 value="selected"}{else}{assign var=selected16 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "17"}{assign var=selected17 value="selected"}{else}{assign var=selected17 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "18"}{assign var=selected18 value="selected"}{else}{assign var=selected18 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "19"}{assign var=selected19 value="selected"}{else}{assign var=selected19 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "20"}{assign var=selected20 value="selected"}{else}{assign var=selected20 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "21"}{assign var=selected21 value="selected"}{else}{assign var=selected21 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "22"}{assign var=selected22 value="selected"}{else}{assign var=selected22 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "23"}{assign var=selected23 value="selected"}{else}{assign var=selected23 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "24"}{assign var=selected24 value="selected"}{else}{assign var=selected24 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "25"}{assign var=selected25 value="selected"}{else}{assign var=selected25 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "26"}{assign var=selected26 value="selected"}{else}{assign var=selected26 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "27"}{assign var=selected27 value="selected"}{else}{assign var=selected27 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "28"}{assign var=selected28 value="selected"}{else}{assign var=selected28 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "29"}{assign var=selected29 value="selected"}{else}{assign var=selected29 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "30"}{assign var=selected30 value="selected"}{else}{assign var=selected30 value=""}{/if}
                                {if $ORGANIZATIONQUESTIONNAIREBACKUP eq "31"}{assign var=selected31 value="selected"}{else}{assign var=selected31 value=""}{/if}                           
                                ระบบ Questionnaire On Mobile จะทำการ Back Up ทุกๆวันที่ <select class="small" name="organization_questionnaire_backup" id="organization_questionnaire_backup">
                                    <option>--Select--</option>
                                    <option value="01" {$selected01}>01</option>
                                    <option value="02" {$selected02}>02</option>
                                    <option value="03" {$selected03}>03</option>
                                    <option value="04" {$selected04}>04</option>
                                    <option value="05" {$selected05}>05</option>
                                    <option value="06" {$selected06}>06</option>
                                    <option value="07" {$selected07}>07</option>
                                    <option value="08" {$selected08}>08</option>
                                    <option value="09" {$selected09}>09</option>
                                    <option value="10" {$selected10}>10</option>
                                    <option value="11" {$selected11}>11</option>
                                    <option value="12" {$selected12}>12</option>
                                    <option value="13" {$selected13}>13</option>
                                    <option value="14" {$selected14}>14</option>
                                    <option value="15" {$selected15}>15</option>
                                    <option value="16" {$selected16}>16</option>
                                    <option value="17" {$selected17}>17</option>
                                    <option value="18" {$selected18}>18</option>
                                    <option value="19" {$selected19}>19</option>
                                    <option value="20" {$selected20}>20</option>
                                    <option value="21" {$selected21}>21</option>
                                    <option value="22" {$selected22}>22</option>
                                    <option value="23" {$selected23}>23</option>
                                    <option value="24" {$selected24}>24</option>
                                    <option value="25" {$selected25}>25</option>
                                    <option value="26" {$selected26}>26</option>
                                    <option value="27" {$selected27}>27</option>
                                    <option value="28" {$selected28}>28</option>
                                    <option value="29" {$selected29}>29</option>
                                    <option value="30" {$selected30}>30</option>
                                    <option value="31" {$selected31}>31</option>
                                </select> ของทุกเดือน
                            </td>
                          </tr>-->
                             <!--   <td width="20%" class="small cellLabel"><strong>ต้นทุนซื้อ</strong></td>
                            <td width="80%" class="small cellText"><input type="text" style="width:50px;" name="organization_purchase_cost" class="detailedViewTextBox small" value="{$ORGANIZATION_PURCHASE_COST}" /> &nbsp;%
                            </td>
                          </tr>
                            <td width="20%" class="small cellLabel"><strong>ค่าที่ปรึกษา</strong></td>
                            <td width="80%" class="small cellText"><input type="text" style="width:50px;" name="organization_consultant_cost" class="detailedViewTextBox small" value="{$ORGANIZATION_CONSULTANT_COST}" /> &nbsp;%
                            </td>
                          </tr>-->
                                                                                                       
                         </table>
                         </td>
                      </tr>
                     </table>						
						</td>
					  </tr>
					</table>
					<table border=0 cellspacing=0 cellpadding=5 width=100% >
					<tr>
					  <td class="small" nowrap align=right><a href="#top">{$MOD.LBL_SCROLL}</a></td>
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
	</div>
	</form>		
</td>
        <td valign="top"><img src="{'showPanelTopRight.gif'|@aicrm_imageurl:$THEME}"></td>
   </tr>
</tbody>
</table>
{literal}
<script>
function verify_data(form,company_name)
{
	if (form.organization_name.value == "" )
	{
		{/literal}
                alert(company_name +"{$APP.CANNOT_BE_NONE}");
                form.organization_name.focus();
                return false;
                {literal}
	}
	else if (form.organization_name.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
	{
	{/literal}
                alert(company_name +"{$APP.CANNOT_BE_EMPTY}");
                form.organization_name.focus();
                return false;
                {literal}
	}
	else if (! upload_filter("binFile","jpg|jpeg|JPG|JPEG"))
        {
                form.binFile.focus();
                return false;
        }
	else
	{
		return true;
	}
}
</script>
{/literal}
