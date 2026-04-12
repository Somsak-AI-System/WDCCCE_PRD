{*<!--
    /*+*******************************************************************************
     * The contents of this file are subject to the vtiger CRM Public License Version 1.0
     * ("License"); You may not use this file except in compliance with the License
     * The Original Code is:  vtiger CRM Open Source
     * The Initial Developer of the Original Code is vtiger.
     * Portions created by vtiger are Copyright (C) vtiger.
     * All Rights Reserved.
     ******************************************************************************/
    -->*}
    <script type="text/javascript" src="asset/js/jquery.min.js"></script>
    <script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
    
    <link rel="stylesheet" type="text/css" href="asset/css/metro-blue/easyui.css">
    
    <script>
    {literal}

    function checkThis(obj){
        var checked = jQuery(obj).is(':checked') ? 1:0;
        var field = jQuery(obj).attr('id');

        jQuery.post('WB_Service_AI/index.php/special/setting/settingFlags',{ module:'Deal', field:field, status:checked }, function(rs){
            // console.log(rs)
        },'json')
    }
    
    {/literal}
    </script>
    
    
    <div style="background-color:#ffffff">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
    <tbody>
        <tr>
            <td valign="top"><img src="{'showPanelTopLeft.gif'|@aicrm_imageurl:$THEME}"></td>
            <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
        <br>
    
        <div align=center>
            {include file='SetMenu.tpl'}
            
            <div id="view">
                {include file='modules/Deal/ModuleTitle.tpl'}
                <input type="hidden" id="pick_module" name="pick_module" value={$FORMODULE} />
    
                <table class="" width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
                    <tbody>
                        <td class=""><input type="checkbox" name="flag_comment" {if $flags.flag_comment eq 1}checked{/if} id="flag_comment" onchange="checkThis(this)"> <label for="flag_comment">Show Comment box</label></td>
                    </tbody>
                </table>
            </div>
        </div>
    </tbody>
    </table>
    </div>
    <br>
    