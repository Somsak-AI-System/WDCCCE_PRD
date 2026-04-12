<?php
	session_start();
	
	include("../../config.inc.php");
	require_once("../../config.inc.php");
	require_once("../../library/dbconfig.php");
	include_once("../../library/myLibrary_mysqli.php");
	global $generate;
	$myLibrary_mysqli = new myLibrary_mysqli();
	$myLibrary_mysqli->_dbconfig = $dbconfig;

	$record_id=$_REQUEST["relmod_id1"];
	$cf_1603=$_REQUEST["cf_1603"];
	
	if($_REQUEST["param_page"]==''){
		$_REQUEST["param_page"] = 1;
	}	
	$where="";
	if($_REQUEST["myaction"]=="search"){
		if($_REQUEST["search_pname"]!=""){
			$_SESSION["search_pname"]=$_REQUEST["search_pname"];
			$where.=" and aicrm_products.productname like '%".$_REQUEST["search_pname"]."%'";	
		}else{
			$_SESSION["search_pname"]="";
		}
		if($_REQUEST["search_pno"]!=""){
			$_SESSION["search_pno"]=$_REQUEST["search_pno"];
			$where.=" and aicrm_products.product_no like '%".$_REQUEST["search_pno"]."%'";	
		}else{
			$_SESSION["search_pno"]="";
		}
		if($_REQUEST["search_PC"]!=""){
			$_SESSION["search_PC"]=$_REQUEST["search_PC"];
			$where.=" and aicrm_products.product_code like '%".$_REQUEST["search_PC"]."%'";	
		}else{
			$_SESSION["search_PC"]="";
		}
	}else{
		if($_SESSION["search_pname"]!=""){
			$_SESSION["search_pname"]=$_SESSION["search_pname"];
			$where.=" and aicrm_products.productname like '%".$_SESSION["search_pname"]."%'";	
		}else{
			$_SESSION["search_pname"]="";
		}
		if($_SESSION["search_pno"]!=""){
			$_SESSION["search_pno"]=$_SESSION["search_pno"];
			$where.=" and aicrm_products.product_no like '%".$_SESSION["search_pno"]."%'";	
		}else{
			$_SESSION["search_pno"]="";
		}
		if($_REQUEST["search_PC"]!=""){
			$_SESSION["search_PC"]=$_REQUEST["search_PC"];
			$where.=" and aicrm_products.product_code like '%".$_REQUEST["search_PC"]."%'";	
		}else{
			$_SESSION["search_PC"]="";
		}
	}
?>
<script>
var image_pth = '';

function showAllRecords()
{
        modname = document.getElementById("relmod").name;
        idname= document.getElementById("relmod_id1").name;
        var locate = location.href;
        url_arr = locate.split("?");
        emp_url = url_arr[1].split("&");
        for(i=0;i< emp_url.length;i++)
        {
                if(emp_url[i] != '')
                {
                        split_value = emp_url[i].split("=");
                        if(split_value[0] == modname || split_value[0] == idname )
                                emp_url[i]='';
                        else if(split_value[0] == "fromPotential" || split_value[0] == "acc_id")
                                emp_url[i]='';

                }
        }
        correctUrl =emp_url.join("&");
        Url = "index.php?"+correctUrl;
        return Url;
}

//function added to get all the records when parent record doesn't relate with the selection module records while opening/loading popup.
function redirectWhenNoRelatedRecordsFound()
{
    var loadUrl = showAllRecords();
    window.location.href = loadUrl;
}
</script>
<link rel="SHORTCUT ICON" href="../../themes/AICRM.ico">
<link rel="stylesheet" type="text/css" href="../../themes/softed/style.css">
<script language="JavaScript" type="text/javascript" src="../../include/js/ListView.js"></script>
<script language="JavaScript" type="text/javascript" src="../../include/js/general.js"></script>

<script language="JavaScript" type="text/javascript" src="js/my_function_promotion.js"></script>
<script language="JavaScript" type="text/javascript" src="../../include/js/json.js"></script>
<!-- vtlib customization: Javascript hook -->
<script language="JavaScript" type="text/javascript" src="../../include/js/vtlib.js"></script>
<!-- END -->
<script language="JavaScript" type="text/javascript" src="../../include/js/en_us.lang.js?5.1.0 RC"></script>
<script language="JavaScript" type="text/javascript" src="../../modules/Products/Products.js"></script>
<script language="javascript" type="text/javascript" src="../../include/scriptaculous/prototype.js"></script>
<script type="text/javascript">
function add_data_to_relatedlist(entity_id,recordid,mod) {
        opener.document.location.href="index.php?module=Opportunity&action=updateRelations&destination_module="+mod+"&entityid="+entity_id+"&parentid="+recordid+"&return_module=Opportunity&return_action=parenttab=Inventory";
}

function set_focus() {
	//$('search_txt').focus();
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=tis-620" />
<title>Search Product</title>
</head>
<body  onload=set_focus() class="small" marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 bottommargin=0 rightmargin=0>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="mailClient mailClientBg">
	<tr>
		<td>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="moduleName" width="80%" style="padding-left:10px;">Products</td>
					<td  width=30% nowrap class="componentName" align=right>MOAI-CRM</td>
				</tr>
			</table>
			<div id="status" style="position:absolute;display:none;right:135px;top:15px;height:27px;white-space:nowrap;"><img src="../../themes/softed/images/status.gif"></div>
			<table width="100%" cellpadding="5" cellspacing="0" border="0"  class="homePageMatrixHdr">
				<tr>
					<td style="padding:0px;" >
                   	  <form name="basicSearch" method="post" enctype="multipart/form-data" action="search_pop_product_mySetup_Tab2.php">
                        <input type="hidden" name="myaction" value="" />
                        <input type="hidden" name="searchtype" value="BasicSearch">
                        <input type="hidden" name="module" value="<?=$_REQUEST["module"]?>">
                        <input type="hidden" name="action" value="<?=$_REQUEST["action"]?>">
                        <input type="hidden" name="query" value="true">
                        <input type="hidden" name="select_enable" id="select_enable" value="<?=$_REQUEST["select"]?>">
                        <input type="hidden" name="curr_row" id="curr_row" value="<?=$_REQUEST["curr_row"]?>">
                        <input type="hidden" name="fldname_pb" value="">
                        <input type="hidden" name="productid_pb" value="">
                        <input name="popuptype" id="popup_type" type="hidden" value="<?=$_REQUEST["popuptype"]?>">
                        <input name="recordid" id="recordid" type="hidden" value="">
                        <input name="record_id" id="record_id" type="hidden" value="">
                        <input name="return_module" id="return_module" type="hidden" value="<?=$_REQUEST["return_module"]?>">
                        <input name="from_link" id="from_link" type="hidden" value="">
                        <input name="maintab" id="maintab" type="hidden" value="">
                        <input type="hidden" id="relmod" name="relmod" value="">
                        <input type="hidden" id="relmod_id1" name="relmod_id1" value="<?=$_REQUEST["relmod_id1"]?>">
                        <input type="hidden" id="cf_1603" name="cf_1603" value="<?=$_REQUEST["cf_1603"]?>">
                    	<input type="hidden" name="currencyid" id="currencyid" value="<?=$_REQUEST["currencyid"]?>">
						<!--<form name="basicSearch" action="search_pop_product.php" onSubmit="return false;">-->
						<table width="100%" cellpadding="5" cellspacing="0">
						<tr>
						  <td width="20%" class="dvtCellLabel small">หมายเลขสินค้า</td>
						  <td width="30%" class="dvtCellLabel"><input type="text" name="search_pno" id="search_pno" class="txtBox" value="<?=$_SESSION["search_pno"]?>"></td>
						  <td class="dvtCellLabel small">รหัสสินค้า</td>
						  <td class="dvtCellLabel"><input type="text" name="search_PC" id="search_PC" class="txtBox" value="<?=$_SESSION["search_PC"]?>"></td>
						  </tr>
						<tr>
						  <td class="dvtCellLabel small">ชื่อสินค้า (TH)</td>
						  <td class="dvtCellLabel small"><input type="text" name="search_pname" id="search_pname" class="txtBox" value="<?=$_SESSION["search_pname"]?>"></td>
                            <td width="20%" class="dvtCellLabel small">&nbsp;</td>
						    <td width="30%" class="dvtCellLabel small">&nbsp;</td>
						</tr>
						<tr>
						  <td class="dvtCellLabel small">&nbsp;</td>
						  <td class="dvtCellLabel small">&nbsp;</td>
						  <td class="dvtCellLabel">&nbsp;</td>
						  <td class="dvtCellLabel"><input type="submit" name="search" value=" &nbsp;Search Now&nbsp; " onClick="this.form.myaction.value='search'; " class="crmbutton small create"></td>
						  </tr>
						</table>
						</form>
					</td>
				</tr>
			</table>

		  <div id="ListViewContents">
				<!-- BEGIN: main -->
<form name="selectall" method="POST">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="small">
	<tr>
					<td style="padding-left:5px;" align="left"><input class="crmbutton small save" type="button" value="Select Products" onClick="if(InventorySelectAll_mySetup_Tab2('Promotion',image_pth))window.close();"/></td>
		<td style="padding-right:5px;" align="right"></td></tr>
   	<tr>
	    <td colspan=3 valign="top" style="padding:5px;">
        <input type="hidden" id="relmod_id1" name="relmod_id1" value="<?=$_REQUEST["relmod_id1"]?>">
        <input type="hidden" id="cf_1603" name="cf_1603" value="<?=$_REQUEST["cf_1603"]?>">
       	<input name="module" type="hidden" value="<?=$_REQUEST["return_module"]?>">
        <input name="module" type="hidden" value="<?=$_REQUEST["return_module"]?>">
		<input name="action" type="hidden" value="">
        <input name="pmodule" type="hidden" value="<?=$_REQUEST["module"]?>">
		<input type="hidden" name="curr_row" value="<?=$_REQUEST["curr_row"]?>">	
		<input name="entityid" type="hidden" value="">
		<input name="popuptype" id="popup_type" type="hidden" value="<?=$_REQUEST["popuptype"]?>">
		<input name="idlist" type="hidden" value="">
		<div style="overflow:auto;height:348px;">
        <?
		$new_table="tbt_his_opp_".$_SESSION["authenticated_user_id"];
		$sql = "
		select 
		aicrm_products.*
		
		from aicrm_products
		left join aicrm_productcf on aicrm_productcf.productid=aicrm_products.productid
		left join aicrm_crmentity on aicrm_crmentity.crmid=aicrm_products.productid
		";
		
		$sql.=" where  
		aicrm_crmentity.deleted=0
		and aicrm_products.productstatus=1
		".$where." 
		";

		$sql.="
		group by aicrm_products.productid
		order by aicrm_products.productid 
		";
		$t_totalrows = $myLibrary_mysqli->select($sql);
		$totalrows = count($t_totalrows);
		//$totalrows = $generate->process_row($sql,"all");
		$totalpage = ceil($totalrows/$list_max_entries_per_page);	
		$sql .=  " limit ".$list_max_entries_per_page * ($_REQUEST["param_page"]-1) .",".$list_max_entries_per_page;
		//$data =$generate->process($sql,$_REQUEST["param_page"]);
		$data = $myLibrary_mysqli->select($sql);

		?>
		<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="100%">
		<tbody>
			<tr>
					<td class="lvtCol" width="3%"><input type="checkbox" name="select_all" value="" onClick=toggleSelect(this.checked,"selected_id")></td>
					<!-- <td class="lvtCol"><a href='javascript:;' onClick="getListViewSorted_js('Products','&curr_row=1&popuptype=inventory_prod&select=enable&return_module=Opportunity&order_by=cf_1149&sorder=DESC')" class='listFormHeaderLinks'>Short Name&nbsp;</a></td> -->
					<td class="lvtCol"><a href='javascript:;' onClick="getListViewSorted_js('Products','&curr_row=1&popuptype=inventory_prod&select=enable&return_module=Opportunity&order_by=cf_1149&sorder=DESC')" class='listFormHeaderLinks'>หมายเลขสินค้า&nbsp;</a></td>
					<td class="lvtCol"><a href='javascript:;' onClick="getListViewSorted_js('Products','&curr_row=1&popuptype=inventory_prod&select=enable&return_module=Opportunity&order_by=cf_1149&sorder=DESC')" class='listFormHeaderLinks'>รหัสสินค้า&nbsp;</a></td>
					<td class="lvtCol"><a href='javascript:;' onClick="getListViewSorted_js('Products','&curr_row=1&popuptype=inventory_prod&select=enable&return_module=Opportunity&order_by=productname&sorder=DESC')" class='listFormHeaderLinks'>ชื่อสินค้า (TH)&nbsp;<img src ='../../themes/softed/images/arrow_down.gif' border='0'></a></td>
			    	<td class="lvtCol"><a href='javascript:;' onClick="getListViewSorted_js('Products','&curr_row=1&popuptype=inventory_prod&select=enable&return_module=Opportunity&order_by=cf_1149&sorder=DESC')" class='listFormHeaderLinks'>หน่วยนับ</a></td>
			    	<td class="lvtCol"><a href='javascript:;' onClick="getListViewSorted_js('Products','&curr_row=1&popuptype=inventory_prod&select=enable&return_module=Opportunity&order_by=cf_1804&sorder=DESC')" class='listFormHeaderLinks'>ราคาขาย&nbsp;</a></td>
	        </tr>
                    <?
					/*for($k=0;$k<count($data);$k++){
						$unit_price="0";
						$unit_price=number_format($data[$k]['sellingprice'],2);*/
					foreach ($data as $key => $value) {
						$unit_price="0";
						$unit_price=number_format($value['sellingprice'],2);
					?>            
			        <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'"  >
		   			<td><input type="checkbox" name="selected_id" value="<?=$value['productid']?>" onClick=toggleSelectAll(this.name,"select_all")></td>
		   			<!-- <td><?=$data[$k]['cf_1641']?></td> -->
		   			<td><?=$value['product_no']?></td>
		   			<td><?=$value['product_code']?></td>
		   			<td><a href="javascript:window.close();" id='popup_product_<?=$value['productid']?>' onclick='set_return_inventory_mySetup_Tab2("<?=$value['productid']?>", "<?=str_replace('"','',$value['productname'])?>", "<?=$value['sellingprice']?>", "0.000","tax1=7.000,tax2=10.000,tax3=12.500","<?=$_REQUEST["curr_row"]?>","","::","<?=$value['unit']?>");' vt_prod_arr='{"entityid":"<?=$value['productid']?>","prodname":"<?=str_replace('"','',$value['productname'])?>","unitprice":"<?=$value['sellingprice']?>","qtyinstk":"0.000","taxstring":"tax1=7.000,tax2=10.000,tax3=12.500","rowid":"<?=$_REQUEST["curr_row"]?>","desc":"","subprod_ids":"::","usageunit":"<?=$value['unit']?>"}' ><?=$value['productname']?></a></td>
		            <td><?=$value['unit']?></td>
		            <td><?=$unit_price?></td>
                    </tr>
                    
                    <?
					}
					?>
		      	  </tbody>
	    	</table>
			<div>
	    </td>
	</tr>

</table>
</form>
<table width="100%" align="center" class="reportCreateBottom">
<tr>
	<td align="center" valign="middle" class="small" style="padding: 5px;">
	  <form name="navigatorForm" id="navigatorForm">
	    <input type="hidden" name="param_page" value="<?=$_REQUEST["param_page"]?>" />
	    <input name="module" type="hidden" value="<?=$_REQUEST["return_module"]?>">
		<input name="action" type="hidden" value="">
        <input name="pmodule" type="hidden" value="<?=$_REQUEST["module"]?>">
		<input type="hidden" name="curr_row" value="<?=$_REQUEST["curr_row"]?>">	
		<input name="entityid" type="hidden" value="">
		<input name="popuptype" id="popup_type" type="hidden" value="<?=$_REQUEST["popuptype"]?>">
		<input name="idlist" type="hidden" value="">
        <input type="hidden" id="relmod_id1" name="relmod_id1" value="<?=$_REQUEST["relmod_id1"]?>">
        <input type="hidden" id="search_pname" name="search_pname" value="<?=$_REQUEST["search_pname"]?>">
        <input type="hidden" id="search_pno" name="search_pno" value="<?=$_REQUEST["search_pno"]?>">
	    <input type="hidden" id="myaction" name="myaction" value="<?=$_REQUEST["myaction"]?>">
	    
	    <?php
             if($_REQUEST["param_page"]>1){
       	?>
	    		<img border=0 src="../../themes/softed/images/start.gif" WIDTH=16 HEIGHT=16 hspace=2 onClick="document.navigatorForm.param_page.value=1;document.navigatorForm.submit();" onMouseOver="this.style.cursor='hand'" style="cursor:pointer">
	    		<img border=0 src="../../themes/softed/images/previous.gif" WIDTH=16 HEIGHT=16 hspace=2 onClick="document.navigatorForm.param_page.value=<?=($_REQUEST["param_page"]-1)?>;document.navigatorForm.submit();" onMouseOver="this.style.cursor='hand'" style="cursor:pointer">
	    <?php
            }else{
                echo "<img border=0 src='../../themes/softed/images/start.gif' WIDTH=16 HEIGHT=16 hspace=2>";
                echo "<img border=0 src='../../themes/softed/images/previous.gif' WIDTH=16 HEIGHT=16 hspace=2>";
            }
        ?>
	    
	    <input type=text class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:20px" onKeyPress="NumOnly();if(event.keyCode=='13'){if(this.value>0&&this.value<=<?=$totalpage?>&&this.value!=<?=($_REQUEST["param_page"])?>){document.navigatorForm.param_page.value=this.value;document.navigatorForm.submit();}else{this.value=<?=($_REQUEST["param_page"])?>;}}" size=2 maxlength=5 value="<?=($_REQUEST["param_page"])?>" />&nbsp;of&nbsp;<?=number_format($totalpage,0)?>&nbsp;            
	    
	    <?php
            if($totalpage>1 && $_REQUEST["param_page"]<$totalpage){
        ?>
		    	<img border=0 src="../../themes/softed/images/next.gif"  WIDTH=16 HEIGHT=16 hspace=2 onClick="document.navigatorForm.param_page.value=<?=($_REQUEST["param_page"]+1)?>;document.navigatorForm.submit();" onMouseOver="this.style.cursor='hand'" style="cursor:pointer">
		    	<img border=0 src="../../themes/softed/images/end.gif"  WIDTH=16 HEIGHT=16 hspace=2 onClick="document.navigatorForm.param_page.value=<?=$totalpage?>;document.navigatorForm.submit();" onMouseOver="this.style.cursor='hand'" style="cursor:pointer">
	    <?php
            }else{
                echo "<img border=0 src='../../themes/softed/images/next.gif' WIDTH=16 HEIGHT=16 hspace=2>";
                echo "<img border=0 src='../../themes/softed/images/end.gif' WIDTH=16 HEIGHT=16 hspace=2>";
            }
        ?>
	    </form>
	  </td>	
</tr>
</table>


			</div>
		</td>
	</tr>
</table>

</body>
</html>
<script>
var gPopupAlphaSearchUrl = '';
var gsorder ='';
var gstart ='';
function callSearch(searchtype)
{
    gstart='';
    for(i=1;i<=26;i++)
    {
        var data_td_id = 'alpha_'+ eval(i);
        getObj(data_td_id).className = 'searchAlph';
    }
    gPopupAlphaSearchUrl = '';
    search_fld_val= document.basicSearch.search_field[document.basicSearch.search_field.selectedIndex].value;
    search_txt_val= encodeURIComponent(document.basicSearch.search_text.value.replace(/\'/,"\\'"));
    var urlstring = '';
    if(searchtype == 'Basic')
    {
	urlstring = 'search_field='+search_fld_val+'&searchtype=BasicSearch&search_text='+search_txt_val;
    }
	popuptype = $('popup_type').value;
	act_tab = $('maintab').value;
	urlstring += '&popuptype='+popuptype;
	urlstring += '&maintab='+act_tab;
	urlstring = urlstring +'&query=true&file=Popup&module=Products&action=ProductsAjax&ajax=true&search=true';
	urlstring +=gethiddenelements();
	record_id = document.basicSearch.record_id.value;
	if(record_id!='')
		urlstring += '&record_id='+record_id;
	$("status").style.display="inline";
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
				method: 'post',
				postBody: urlstring,
				onComplete: function(response) {
					$("status").style.display="none";
					$("ListViewContents").innerHTML= response.responseText;
				}
			}
		);
}	
function alphabetic(module,url,dataid)
{
    gstart='';
    document.basicSearch.search_text.value = '';	
    for(i=1;i<=26;i++)
    {
	var data_td_id = 'alpha_'+ eval(i);
	getObj(data_td_id).className = 'searchAlph';
    }
    getObj(dataid).className = 'searchAlphselected';
    gPopupAlphaSearchUrl = '&'+url;	
    var urlstring ="module="+module+"&action="+module+"Ajax&file=Popup&ajax=true&search=true&"+url;
    urlstring +=gethiddenelements();
	record_id = document.basicSearch.record_id.value;
	if(record_id!='')
		urlstring += '&record_id='+record_id;
    $("status").style.display="inline";
    new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: urlstring,
                                onComplete: function(response) {
                                	$("status").style.display="none";
									$("ListViewContents").innerHTML= response.responseText;
				}
			}
		);
}
function gethiddenelements()
{
	gstart='';
	var urlstring=''	
	if(getObj('select_enable').value != '')
		urlstring +='&select=enable';	
	if(document.getElementById('curr_row').value != '')
		urlstring +='&curr_row='+document.getElementById('curr_row').value;	
	if(getObj('fldname_pb').value != '')
		urlstring +='&fldname='+getObj('fldname_pb').value;	
	if(getObj('productid_pb').value != '')
		urlstring +='&productid='+getObj('productid_pb').value;	
	if(getObj('recordid').value != '')
		urlstring +='&recordid='+getObj('recordid').value;
	if(getObj('relmod').value != '')
                urlstring +='&'+getObj('relmod').name+'='+getObj('relmod').value;
    if(getObj('relmod_id1').value != '')
            urlstring +='&'+getObj('relmod_id1').name+'='+getObj('relmod_id1').value;
	
	// vtlib customization: For uitype 10 popup during paging
	if(document.getElementById('popupform'))
		urlstring +='&form='+encodeURIComponent(getObj('popupform').value);
	if(document.getElementById('forfield'))
		urlstring +='&forfield='+encodeURIComponent(getObj('forfield').value);
	if(document.getElementById('srcmodule'))
		urlstring +='&srcmodule='+encodeURIComponent(getObj('srcmodule').value);
	if(document.getElementById('forrecord'))
		urlstring +='&forrecord='+encodeURIComponent(getObj('forrecord').value);
	// END
		
	if(document.getElementById('currencyid') != null && document.getElementById('currencyid').value != '')
		urlstring +='&currencyid='+document.getElementById('currencyid').value;

	var return_module = document.getElementById('return_module').value;
	if(return_module != '')
		urlstring += '&return_module='+return_module;
	return urlstring;
}
																									
function getListViewEntries_js(module,url)
{
	gstart="&"+url;

	popuptype = document.getElementById('popup_type').value;
        var urlstring ="module="+module+"&action="+module+"Ajax&file=Popup&ajax=true&"+url;
    	urlstring +=gethiddenelements();
	
			search_fld_val= document.basicSearch.search_field[document.basicSearch.search_field.selectedIndex].value;
		search_txt_val=document.basicSearch.search_text.value;
		if(search_txt_val != '')
			urlstring += '&query=true&search_field='+search_fld_val+'&searchtype=BasicSearch&search_text='+search_txt_val;
		if(gPopupAlphaSearchUrl != '')
		urlstring += gPopupAlphaSearchUrl;	
	else
		urlstring += '&popuptype='+popuptype;	
	
	record_id = document.basicSearch.record_id.value;
	if(record_id!='')
		urlstring += '&record_id='+record_id;

	urlstring += (gsorder !='') ? gsorder : '';
	$("status").style.display = "";
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: urlstring,
                                onComplete: function(response) {
                                        $("ListViewContents").innerHTML= response.responseText;
									$("status").style.display = "none";
				}
			}
		);
}

function getListViewSorted_js(module,url)
{
	gsorder=url;
        var urlstring ="module="+module+"&action="+module+"Ajax&file=Popup&ajax=true"+url;
	record_id = document.basicSearch.record_id.value;
	if(record_id!='')
		urlstring += '&record_id='+record_id;
	urlstring += (gstart !='') ? gstart : '';
	$("status").style.display = "";
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: urlstring,
                                onComplete: function(response) {
                                        $("ListViewContents").innerHTML= response.responseText;
									$("status").style.display = "none";
				}
			}
		);
}

var product_labelarr = {
	CLEAR_COMMENT:'Clear Comment',
	DISCOUNT:'Discount',
	TOTAL_AFTER_DISCOUNT:'Total After Discount',
	TAX:'Tax',
	ZERO_DISCOUNT:'Zero Discount',
	PERCENT_OF_PRICE:'of Price',
	DIRECT_PRICE_REDUCTION:'Direct Price Reduction'
};
</script>

<!-- stopscrmprint -->

<script>
	var userDateFormat = "dd-mm-yyyy";
	var default_charset = "UTF-8";
</script>
