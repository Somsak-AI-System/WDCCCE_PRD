<?php
session_start();
include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/generate_MYSQL.php");
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
global $generate,$current_user;

$generate = new generate($dbconfig ,"DB");
$date=date('Y-m-d');
$smownerid=$_SESSION["authenticated_user_id"];
$crmid=$_REQUEST["crmid"];
$sql="
SELECT *,  CONCAT(first_name,' ', last_name) as name
FROM `aicrm_users`
where 1 = 1;";
$a_assign =$generate->process($sql,"all");
$modified_user_id = $smownerid;


$userselected = '';
$groupselected = '';
$userdisplay = 'none';
$groupdisplay = 'none';
$private = '';

if($smownerid != ''){

    global $adb;
    $query = "SELECT * from aicrm_users WHERE id = ?";

    $res = $adb->pquery($query,array($smownerid));
    $rows = $adb->num_rows($res);

    if($rows > 0){
        $userselected = 'checked';
        $userdisplay= 'block';
    }else{
        $groupselected = 'checked';
        $groupdisplay= 'block';
    }
}

if($smownerid != 1){
    require('user_privileges/sharing_privileges_'.$smownerid.'.php');
    $Acc_tabid= getTabid('Accounts');
    $con_tabid = getTabid('Contacts');
    if($defaultOrgSharingPermission[$Acc_tabid] === 0 || $defaultOrgSharingPermission[$Acc_tabid] == 3){
        $private = 'private';
    }elseif($defaultOrgSharingPermission[$con_tabid] === 0 || $defaultOrgSharingPermission[$con_tabid] == 3){
        $private = 'private';
    }
}
?>
<style type="text/css">
    .mailClient{
        height: auto !important;
    }
    body{
        overflow-x:hidden;
    }
</style>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="themes/softed/style.css">
<link rel="stylesheet" type="text/css" media="all" href="asset/css/metro-blue/easyui.css">
<link rel="stylesheet" type="text/css" media="all" href="asset/css/icon.css">
<link rel="stylesheet" type="text/css" media="screen" href="asset/css/multi-select.css"  >

<link href="asset/css/smoothness/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" />
 <link  rel="stylesheet"  href="asset/css/ui.jqgrid.css"/>
 <link rel="stylesheet" type="text/css" media="screen" href="asset/css/custom.css"  >

<script type="text/javascript" src="asset/js/jquery-1.8.3.min.js"></script>
<body class="small" marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 bottommargin=0 rightmargin=0>
   <div id="loadPage">
		<img alt="" src="asset/images/ajax-loader.gif">
		<span>Loading ...</span>
	</div>
<div class="mailClient mailClientBg">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	 	<td class="moduleName" width="70%" style="padding-left:10px;">Job</td>
		<td  width=20% nowrap class="componentName" align=right>Ai-CRM</td>
	</tr>
</table>
</div>

<div data-options="region:'center',iconCls:'icon-ok'" style="width: 100%;" title="service"  id="divplan">
			<table width="100%" cellpadding="5" cellspacing="0" border="0"  class="homePageMatrixHdr">
				<tr>
					<td style="padding:0px;" >
					<form id="EditView" method="POST" name="EditView">
                        <input type="hidden" name="myaction" value="" />
                        <input type="hidden" name="crmid" id="crmid" class="txtBox"  style="width:210px" value="<?php echo $_REQUEST["crmid"]?>">

						<table width="100%" cellpadding="5" cellspacing="0">
						<tr>
						  <!-- <td width="20%" class="dvtCellLabel small">Assign to</td>
						  <td width="30%" class="dvtCellInfo"><input type="text" name="assignto" id="assignto" class="txtBox"  style="width:210px" value=""></td> -->
						  <td width="20%" class="dvtCellLabel small">Handled by</td>
                            <td class="dvtCellInfo small" width="50%">
                                <input type="radio" name="assigntype" value="U" onclick=" toggleAssignType(this.value)" <?php echo $userselected?> />&nbsp;User
                                <input type="radio" name="assigntype" value="T" onclick=" toggleAssignType(this.value)" <?php echo $groupselected?> />&nbsp;Group
                                <span id="assign_user" style="display:<?php echo $userdisplay ?>">
                                    <select name="assigned_user_id" class="detailedViewTextBox"><?php echo get_select_options_with_id(get_user_array(false,"Active", $smownerid),$smownerid)?></select>
                                </span>
                                <span id="assign_team" style="display:<?php echo $groupdisplay ?>">
                                    <select name="assigned_group_id" class="detailedViewTextBox"><?php echo get_select_options_with_id(get_group_array(false,"Active", $smownerid),$smownerid)?></select>
                                </span>
                            </td>
						</tr>

						<tr>
						  <!-- <td width="20%" class="dvtCellLabel small">ส่งเมล์ต่อ</td>
						  <td width="30%" class="dvtCellInfo"><input type="checkbox" name="sendmailto" id="sendmailto" ></td> -->
						  <td width="20%" class="dvtCellLabel small">ส่งเมล์ต่อ</td>
                            <td width="50" class="dvtCellInfo" >
                                <span id="mailToOwner" style="display:<?php echo $userdisplay ?>">
                                    <input type=hidden name="sendmailto" value="0">
                                    <input type=checkbox onchange="this.form.sendmailto.value=this.checked? 1 : 0" >
                                </span>
                                <span id="mailToGroup" style="display:<?php echo $groupdisplay ?>">
                                    <input type=hidden name="sendmailtogroup" value="0" >
                                    <input type=checkbox onchange="this.form.sendmailtogroup.value=this.checked? 2 : 0" >
                                </span>
                            </td>
                            </td>
						</tr>
						
						<tr>
						  <td class="dvtCellLabel">&nbsp;</td>
						  <td class="dvtCellInfo"><input type="button"  id = "save" name="save" value=" &nbsp;save&nbsp; "  class="crmbutton small save"></td>
						  </tr>
						</table>
						</form>
					</td>
				</tr>
							</table>
		</div>
</body>
<script src="asset/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
<script src="asset/js/grid.locale-en.js"></script>
<script type="text/javascript" src="asset/js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="asset/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="asset/js/jquery.jqGrid.min.js" ></script>
<script>
$(function(){

	$('#EditView').form({
		url:'job_status.php?job_status=ส่งต่อ',
		onSubmit:function(){
			$("#loadPage").show();
			if($(this).form('validate')){
				return true;
			}else{
				$("#loadPage").hide();
				return false;
			}
		},
		success:function(data){
			$("#loadPage").hide();
			var obj = $.parseJSON(data);
			//console.log(obj);
			if(obj.status==true){
				var errMsg =  obj.msg + " " +obj.error ;
			}else{
				var errMsg =  obj.msg +" error: "+ obj.error;
			}
			$.messager.alert('Info', errMsg, 'info', function(){
			if(obj.status==true){
				if(obj.url != '')
				{
					window.opener.location.reload();
					window.close();
					//window.location.href = obj.url;
				}
			}
			else{
					console.log(obj);
			}
			});
		}
	});
});
$(document).ready(function(){
	$("#loadPage").hide();
	$('.save').click(function(event) {
		event.preventDefault();
		$( "#EditView" ).submit();
		
	});
});

var dataassign =  JSON.parse('<?php echo json_encode($a_assign)?>');

$('#assignto').combobox({
	valueField:'id',
	textField:'name',
	data: dataassign,
});

    /*Get Obj*/
    function getObj(n,d) {
        var p,i,x;
        if(!d)d=document;
        if((p=n.indexOf("?"))>0&&parent.frames.length) {d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
        if(!(x=d[n])&&d.all)x=d.all[n];
        for(i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
        for(i=0;!x&&d.layers&&i<d.layers.length;i++)  x=getObj(n,d.layers[i].document);
        if(!x && d.getElementById) x=d.getElementById(n);
        return x;
    }

    /*toggle display*/
    function toggleAssignType(currType)
    {
        if (currType=="U")
        {
            getObj("assign_user").style.display="block"
            getObj("assign_team").style.display="none"

            getObj("mailToOwner").style.display="block"
            getObj("mailToGroup").style.display="none"
        }
        else
        {
            getObj("assign_user").style.display="none"
            getObj("assign_team").style.display="block"

            getObj("mailToOwner").style.display="none"
            getObj("mailToGroup").style.display="block"
        }
    }

    /*toggle display*/
    $(function(){

        $(".sendmailto").click(function(){  // เมื่อคลิก checkbox  ใดๆ
            if($(this).prop("checked")==true){ // ตรวจสอบ property  การ ของ
                var indexObj=$(this).index(".sendmailto"); //
                $(".sendmailto").not(":eq("+indexObj+")").prop( "checked", false ); // ยกเลิกการคลิก รายการอื่น
            }
        });
    });

</script>