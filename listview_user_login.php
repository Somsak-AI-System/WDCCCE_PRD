<?
session_start();
include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/generate_MYSQL.php");
include_once("library/generate.inc.php");
global $generate;
$generate = new generate($dbconfig ,"DB");
	if($_REQUEST["param_page"]==''){
		$_REQUEST["param_page"] = 1;
	}
	
	$where="";
	//$where="where 1  ";
	if($_REQUEST["txt_start"]==""){
		$_REQUEST["txt_start"]=date('Y-m-d');
	}
	if($_REQUEST["txt_end"]==""){
		$_REQUEST["txt_end"]=date('Y-m-d');
	}
	$_REQUEST["txt_start"]=trim($_REQUEST["txt_start"]);
	if($_REQUEST["txt_start"]!=""){
		$where.=" and left(ai_check_user_login_system.use_date,10)>='".$_REQUEST["txt_start"]."' ";
		$_SESSION["txt_start"]=$_REQUEST["txt_start"];
	}
	$_REQUEST["txt_end"]=trim($_REQUEST["txt_end"]);
	if($_REQUEST["txt_end"]!=""){
		$where.=" and left(ai_check_user_login_system.use_date,10)<='".$_REQUEST["txt_end"]."'	 ";
		$_SESSION["txt_end"]=$_REQUEST["txt_end"];
	}

	if($_REQUEST["myaction"]=="refresh"){
		echo "<script>window.location.replace('listview_user_login.php');</script>";
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Users Login View</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link REL="SHORTCUT ICON" HREF="themes/AICRM.ico">
   <!-- <meta http-equiv="Content-Type" content="text/html; charset=windows-874">-->
	<style type="text/css">@import url("themes/softed/style.css");</style>
	<!-- ActivityReminder customization for callback -->
	
	<style type="text/css">div.fixedLay1 { position:fixed; }</style>
	<!--[if lte IE 6]>
	<style type="text/css">div.fixedLay { position:absolute; }</style>
	<![endif]-->
	
	<!-- End -->
</head>
<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0 class=small>

	<!-- header -->
	<!-- header-vtiger crm name & RSS -->
	<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
    <!-- vtlib customization: Javascript hook -->	
    <script language="JavaScript" type="text/javascript" src="include/js/vtlib.js"></script>
    <!-- END -->
    <script language="JavaScript" type="text/javascript" src="include/js/en_us.lang.js?"></script>
    <script language="JavaScript" type="text/javascript" src="include/js/QuickCreate.js"></script>
    <script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
    <script language="JavaScript" type="text/javascript" src="include/calculator/calc.js"></script>
    <script language="JavaScript" type="text/javascript" src="modules/Calendar/script.js"></script>
    <script language="javascript" type="text/javascript" src="include/scriptaculous/dom-drag.js"></script>
    <script language="JavaScript" type="text/javascript" src="include/js/notificationPopup.js"></script>
    <link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
    <script type="text/javascript" src="jscalendar/calendar.js"></script>
    <script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
    <script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script language="JavaScript">
function NumOnly() {
	if (event.keyCode>='0'.charCodeAt()&&event.keyCode <= '9'.charCodeAt())
		event.returnValue = true;
	else
		event.returnValue = false;
}
</script>
	    <img src="themes/softed/images/layerPopupBg.gif" style="display: none;"/>
<table width="1000" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td>
     <TABLE border=0 cellspacing=0 cellpadding=0 width="100%"  background="themes/softed/images/Ai-HEAD.jpg" style="background-repeat:no-repeat" height="77">
	<tr>
		<td valign="top" nowrap class=small>
        <table width="100%" border="0" cellspacing="2" cellpadding="0">
          <tr>
            <td style="padding-left:10px;padding-right:10px" class=small nowrap align="right">&nbsp;</td>
          </tr>
          <tr>
            <td style="padding-left:10px;padding-right:10px" class=small nowrap align="right">&nbsp;</td>
          </tr>
          <tr>
            <td style="padding-left:10px;padding-right:10px" class=small nowrap align="right">&nbsp;</td>
          </tr>

        </table>

        </td>
	</tr>
	</TABLE>
 
	<table border=0 cellspacing=0 cellpadding=0 width="100%" align=left>
     <tr>
        <td valign=top><img src="themes/softed/images/showPanelTopLeft.gif"></td>
        <td class="showPanelBg" valign="top" width=100% style="padding:10px;" height="440">
              <table width="100%" border="0" align="center" cellpadding="0" cellspacing="6" height="440" >
                              <tr>
                                <td valign="top">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                            <!-- -->
                            
                             <tr>
                                <td >
                                   <div id="container">
                                    <table width="100%" border=0 cellspacing=0 cellpadding=0 class="lvt small">
                                    <form name="inq_trouble_tickets" method="get"  action="listview_user_login.php" >
                                    <input type="hidden" name="myaction" value="" />
                                    <input type="hidden" name="record" value="" />
                                      <tr>
                                        <td colspan="14" class="dvInnerHeader"><b>Users Login View จำนวน :: <?=$list_max_user?> License</b></td>
                                      </tr>         
                                      <tr>
                                        <td height="30" colspan="2" class="lvtCol">
                                        
                                        <b>&nbsp;&nbsp;Search::&nbsp;&nbsp;From</b>
                                        <input type="text" name="txt_start" id="txt_start" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:150px" value="<?=$_SESSION["txt_start"]?>"/>
                                        &nbsp;&nbsp;To
                                        <input type="text" name="txt_end" id="txt_end" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:150px" value="<?=$_SESSION["txt_end"]?>"/>
                                        <input title="Search Trouble Tickets" src="themes/softed/images/Find.gif" type="image" name="Search" value=""  tabindex="5" onClick="this.form.myaction.value='search'; ">
                                        &nbsp;
                                        <input title="Refresh Page" src="themes/softed/images/refresh.png" type="image" name="Search2" value=""  tabindex="5" onClick="this.form.myaction.value='refresh'; "></td>
                                      </tr>
                                      </form>        
                                    </table>
                                          
                                    <table id='myTable' width="100%" border=0 cellspacing=1 cellpadding=0 class="lvt small">
                                        <thead>
                                          <tr bgcolor="#FFFFFF" height="20">
                                            <th width="5%" class="lvtCol" align="center"><b>No.</b></th>
                                            <th width="15%" class="lvtCol" align="center">Time</th>
                                            <th width="15%" class="lvtCol" align="center">System Name</th>
                                            <th width="10%" class="lvtCol" align="center">Total Login</th>
                                            <th width="10%" class="lvtCol" align="center">Total User</th>
                                            <th width="15%" align="center" class="lvtCol">System Name</th>
                                            <th width="10%" align="center" class="lvtCol">Total Login</th>
                                            <th width="10%" align="center" class="lvtCol">Total User</th>
                                            <th width="10%" align="center" class="lvtCol"><strong>Total All</strong></th>
                                          </tr>    
                                          </thead>    
                                           <tbody>
                                <?
								$generate->query($sql);
                                $sql = "
								 select
								 total.datestring
								 , 'CRM' as crm_sysytem_name
								 , sum(case  when sysytem_name='CRM' then numcount else 0 end) as crm_sum_login 
								 , sum(case  when sysytem_name='CRM' then 1 else 0 end) as crm_sum_user_id
								 , 'Mobile' as mobile_sysytem_name
								 , sum(case when sysytem_name='Mobile' then numcount else 0 end) as mobile_sum_login  
								 , sum(case  when sysytem_name='Mobile' then 1 else 0 end) as mobile_sum_user_id
								 , sum(case  when sysytem_name='CRM' then 1 else 0 end)+sum(case  when sysytem_name='Mobile' then 1 else 0 end) as sum_user
								 from(
									select
									sysytem_name,user_id,
									case 
									when RIGHT(  `use_date` , 8 )  BETWEEN '00:00:00' AND '00:59:59' then '00:00:00-00:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '01:00:00' AND '01:59:59' then '01:00:00-01:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '02:00:00' AND '02:59:59' then '02:00:00-02:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '03:00:00' AND '03:59:59' then '03:00:00-03:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '04:00:00' AND '04:59:59' then '04:00:00-04:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '05:00:00' AND '05:59:59' then '05:00:00-05:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '06:00:00' AND '06:59:59' then '06:00:00-06:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '07:00:00' AND '07:59:59' then '07:00:00-07:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '08:00:00' AND '08:59:59' then '08:00:00-08:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '09:00:00' AND '09:59:59' then '09:00:00-09:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '10:00:00' AND '10:59:59' then '10:00:00-10:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '11:00:00' AND '11:59:59' then '11:00:00-11:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '12:00:00' AND '12:59:59' then '12:00:00-12:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '13:00:00' AND '13:59:59' then '13:00:00-13:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '14:00:00' AND '14:59:59' then '14:00:00-14:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '15:00:00' AND '15:59:59' then '15:00:00-15:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '16:00:00' AND '16:59:59' then '16:00:00-16:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '17:00:00' AND '17:59:59' then '17:00:00-17:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '18:00:00' AND '18:59:59' then '18:00:00-18:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '19:00:00' AND '19:59:59' then '19:00:00-19:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '20:00:00' AND '20:59:59' then '20:00:00-20:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '21:00:00' AND '21:59:59' then '21:00:00-21:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '22:00:00' AND '22:59:59' then '22:00:00-22:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '23:00:00' AND '23:59:59' then '23:00:00-23:59:59'
									else ''
									end datestring,
									count(*) as numcount
									from	ai_check_user_login_system
									where 1
									".$where."
									group by sysytem_name,user_id
									,case 
									when RIGHT(  `use_date` , 8 )  BETWEEN '00:00:00' AND '00:59:59' then '00:00:00-00:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '01:00:00' AND '01:59:59' then '01:00:00-01:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '02:00:00' AND '02:59:59' then '02:00:00-02:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '03:00:00' AND '03:59:59' then '03:00:00-03:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '04:00:00' AND '04:59:59' then '04:00:00-04:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '05:00:00' AND '05:59:59' then '05:00:00-05:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '06:00:00' AND '06:59:59' then '06:00:00-06:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '07:00:00' AND '07:59:59' then '07:00:00-07:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '08:00:00' AND '08:59:59' then '08:00:00-08:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '09:00:00' AND '09:59:59' then '09:00:00-09:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '10:00:00' AND '10:59:59' then '10:00:00-10:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '11:00:00' AND '11:59:59' then '11:00:00-11:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '12:00:00' AND '12:59:59' then '12:00:00-12:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '13:00:00' AND '13:59:59' then '13:00:00-13:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '14:00:00' AND '14:59:59' then '14:00:00-14:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '15:00:00' AND '15:59:59' then '15:00:00-15:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '16:00:00' AND '16:59:59' then '16:00:00-16:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '17:00:00' AND '17:59:59' then '17:00:00-17:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '18:00:00' AND '18:59:59' then '18:00:00-18:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '19:00:00' AND '19:59:59' then '19:00:00-19:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '20:00:00' AND '20:59:59' then '20:00:00-20:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '21:00:00' AND '21:59:59' then '21:00:00-21:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '22:00:00' AND '22:59:59' then '22:00:00-22:59:59'
									when RIGHT(  `use_date` , 8 )  BETWEEN '23:00:00' AND '23:59:59' then '23:00:00-23:59:59'
									else ''
									end
								  ) as total   
								group by total.datestring
                                ";
                                $max_rows=50;
                                $totalrows = $generate->process_row($sql,"all");
                                $totalpage = ceil($totalrows/ $max_rows);	
                                $sql .=  " limit ". $max_rows * ($_REQUEST["param_page"]-1) .",". $max_rows;
                                $data =$generate->process($sql,$_REQUEST["param_page"]);
                                //echo $sql;
                                ?>
                            
                                            <?php
                                                for($k=0;$k<count($data);$k++){
                                                    $a = 0;
                                                    if(($k%2)!=0){
                                                        $class = "row0";
                                                    }else{
                                                        $class = "row1";
                                                    }
                                            ?>
                                    <form name="view" method="get"  action="Listview_login.php" >
                                    <input type="hidden" name="myaction" value="" />
                                    <input type="hidden" name="record" value="" />                  
                                          <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'">
                                            <td align="center" height="18">
                                            <?
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',($k+1))."<br>";
                                            ?>				
                                            </td>
                                            <td  align="center">
											<?
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k]['datestring'])."<br>";
                                            ?>
                                            </td>
                                            <td  align="center">
											<?
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k]['crm_sysytem_name'])."<br>";
                                            ?>
                                            </td>
                                            <td  align="center">
											<?
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k]['crm_sum_login'])."<br>";
                                            ?>
                                            </td>
                                            <td  align="center">
											<?
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k]['crm_sum_user_id'])."<br>";
                                            ?>
                                            </td>
                                            <td  align="center">
											<?
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k]['mobile_sysytem_name'])."<br>";
                                            ?>
                                            </td>
                                            <td  align="center">
											<?
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k]['mobile_sum_login'])."<br>";
                                            ?>
                                            </td>
                                            <td  align="center">
											<?
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k]['mobile_sum_user_id'])."<br>";
                                            ?>
                                            </td>
                                            <td  align="center"><strong>
                                            <?
                                            if(($data[$k]['crm_sum_user_id']+$data[$k]['mobile_sum_user_id'])>$list_max_user){
											?>
                                            <font color="#FF0000"><?=($data[$k]['crm_sum_user_id']+$data[$k]['mobile_sum_user_id'])?></font>
                                            <?	
											}else{
											?>
                                            <font color="#000000"><?=($data[$k]['crm_sum_user_id']+$data[$k]['mobile_sum_user_id'])?></font>
                                            <?		
											}
                                            ?>
                                            </strong></td>
                                          </tr>

                                          </form>
                                          <?php
                                                }
                                            ?>
                                          <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'">
                                            <td align="center" >&nbsp;</td>
                                            <td  align="center">&nbsp;</td>
                                            <td  align="center">&nbsp;</td>
                                            <td  align="center">&nbsp;</td>
                                            <td  align="center">&nbsp;</td>
                                            <td  align="center">&nbsp;</td>
                                            <td  align="center">&nbsp;</td>
                                            <td  align="center">&nbsp;</td>
                                            <td  align="center">&nbsp;</td>
                                          </tr>                                          
                                        </tbody> 
                                <form name="navigatorForm">
                                <INPUT TYPE="hidden" NAME="param_page" value="<?=$_REQUEST["param_page"]?>">
                                <input type="hidden" name="sort" value="<?=$sort?>">
                                <input type="hidden" name="order" value="<?=$order?>">
                                </form>             
                                   </table>
                                        </div>   
                                  </td>
                              </tr>
                            </table>
                                </td>
                              </tr>
                            </table>             
        </td>
        <td valign=top><img src="themes/softed/images/showPanelTopRight.gif"></td>
    </tr>
     <tr>
       <td valign=top>&nbsp;</td>
       <td>
<script language = 'JavaScript' type='text/javascript' src = 'include/js/popup.js'></script><br><br><br>
        <table border=0 cellspacing=0 cellpadding=5 width="100%" class=settingsSelectedUI ><tr><td class=small align=left><span style='color: rgb(153, 153, 153);'>MOAI-CRM v.1</span></td><td class=small align=right><span style='color: rgb(153, 153, 153);'>&copy; <?=date('Y')+1?> <a href='http://www.moai-crm.com' target='_blank'>http://www.moai-crm.com</a></span></td></tr>
        </table>		<script>
			var userDateFormat = "dd-mm-yyyy";
			var default_charset = "UTF-8";
		</script>
       </td>
       <td valign=top>&nbsp;</td>
     </tr>
    </table>   
    </td>
  </tr>
</table>


</body>
</html>

