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
       
        function change_condition(obj){

            if($( "#condition" ).val()=='false'){
                $("#range").addClass("disable-range");
                $('#range').attr('readonly', true);
            }else{
                $("#range").removeClass("disable-range");
                $('#range').attr('readonly', false);
            }
        }
        function saveCheckin() {

            var condition = jQuery("#condition").val();
            var range = jQuery("#range").val();
            var allow = $("input[name='allow']:checked").val();
            var url = "modules/Calendar/editcheckin.php";
            jQuery.ajax(url, {
                type: "POST",
                data: {condition:condition,range:range,allow:allow},
                success: function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result["status"] == false) {
                        jQuery.messager.alert('Message','ไม่สามารถทำรายการได้','info');
                    }else{
                        jQuery.messager.alert('Message','บันทึกรายการสำเร็จ','info');
                        location.reload();
                    }
                },
                error: function (data) {
                    console.log("error");
                },
            });
        }
    {/literal}
    </script>
    
    {literal}
    <style type="text/css">
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
        input[type=number] {
          -moz-appearance: textfield;
        }
        .disable-range{
            background-color: #ccc;
        }
    </style>
    {/literal}
    
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
                {include file='modules/Calendar/ModuleTitleCheckin.tpl'}
                <input type="hidden" id="pick_module" name="pick_module" value={$FORMODULE} />
    
                <table class="" width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
                    <tr>
                        <td class="" width="15%">
                            <label>Condition of Check-in/out (Meter) : </label>
                        </td>
                        <td>
                            <select id="condition" name="condition" class="small user-success" style="width: 30%" onchange="change_condition(this)">
                                <option value="false" {if $data_checkin.conditions eq 'false'}selected{/if}>Unlimited distance of Check-in/out</option>
                                <option value="true" {if $data_checkin.conditions eq 'true'}selected{/if}>Limited distance of Check-in/out</option>
                            </select>
                        </td>  
                    </tr>

                    <tr>
                        <td class="" width="15%">
                            <label>Limit Range (Meter) : </label>
                        </td>
                        <td>
                            <input type="number" name="range" id="range" class="detailedViewTextBox user-success {if $data_checkin.conditions eq 'false'}disable-range{/if}" style="width: 30%" value="{$data_checkin.ranges}">
                        </td>
                    </tr>
                </table>

                <h3>กรณีจุดหมายปลายทางไม่ได้บันทึกหมุดที่อยู่เอาไว้ ทําให้ระบบไม่สามารถคํานวณระยะห่างได้ โปรดเลือกตัวเลือกด้านล่างดังนี้</h3>

                <table class="" width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
                    <tr>
                        <td class="">
                            <input type="radio" name="allow" {if $data_checkin.allow eq yes}checked{/if} id="allow" value="yes"> <label>อนุญาตให้ Check-in/out โดยไม่สนใจ Condition of Check-in/out ของระบบได้</label>
                        </td>    
                    </tr>

                    <tr>
                        <td class="">
                            <input type="radio" name="allow" {if $data_checkin.allow eq no}checked{/if} id="allow" value="no" > <label>ไม่อนุญาตให้ Check-in/out</label>
                        </td>
                    </tr>
                </table>

                </br>
                <!-- <button type="submit">Save</button> -->
                <input title="Save" accesskey="S" class="crmbutton small save" onclick="saveCheckin();" type="submit" name="button" value="Save" style="width:80px;cursor: pointer;" />

                
            </div>
        </div>
    </tbody>
    </table>
    </div>
    <br>
    
