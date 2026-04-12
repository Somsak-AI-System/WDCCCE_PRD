<?
session_start();
include("config.inc.php");
include_once("library/dbconfig.php");
include_once("library/myFunction.php");
include_once("library/generate_MYSQL.php");
include_once("library/genarate.inc.php");
global $genarate;
$genarate = new genarate($dbconfig ,"DB");
	if($_REQUEST["param_page"]==''){
		$_REQUEST["param_page"] = 1;
	}
	if($_SESSION['user_name']!="admin" || $_SESSION['user_password']!="admin"){
		echo "<script>window.location.replace('licence.php');</script>";
	}
	if($_REQUEST["myaction"]=="signout"){
		unset($_SESSION['user_name']);
		unset($_SESSION['user_password']);
		echo "<script>window.location.replace('licence.php');</script>";
	}
	
	$where="where 1 and status <>'1' and start_time<>end_time ";
	//$where="where 1  ";
	$_REQUEST["txt_search_ticket"]=trim($_REQUEST["txt_search_ticket"]);
	if($_REQUEST["txt_search_ticket"]!=""){
		$where.=" and (username like '%".$_REQUEST["txt_search_ticket"]."%'";
		$where.=" or ipaddress like '%".$_REQUEST["txt_search_ticket"]."%'";
		$where.=" or start_time like '%".$_REQUEST["txt_search_ticket"]."%'";
		$where.=" or end_time like '%".$_REQUEST["txt_search_ticket"]."%' )";
		$_SESSION["txt_search_ticket"]=$_REQUEST["txt_search_ticket"];
	}else{
		if($_REQUEST["myaction"]=="search"){
			if($_REQUEST["txt_search_ticket"]!=""){
				$where.=" and (username like '%".$_REQUEST["txt_search_ticket"]."%'";
				$where.=" or ipaddress like '%".$_REQUEST["txt_search_ticket"]."%'";
				$where.=" or start_time like '%".$_REQUEST["txt_search_ticket"]."%'";
				$where.=" or end_time like '%".$_REQUEST["txt_search_ticket"]."%' )";
			}
			$_SESSION["txt_search_ticket"]=$_REQUEST["txt_search_ticket"];
		}else{
			if($_SESSION["txt_search_ticket"]!=""){
				$where.=" and (username like '%".$_REQUEST["txt_search_ticket"]."%'";
				$where.=" or ipaddress like '%".$_REQUEST["txt_search_ticket"]."%'";
				$where.=" or start_time like '%".$_REQUEST["txt_search_ticket"]."%'";
				$where.=" or end_time like '%".$_REQUEST["txt_search_ticket"]."%' )";
				$_SESSION["txt_search_ticket"]=$_SESSION["txt_search_ticket"];
			}
		}
	}
	if($_REQUEST["myaction"]=="delete"){
		$query = "update ai_check_user_login set end_time=start_time,status='1' where  id='".$_REQUEST["record"]."'";	
		$genarate->query($query);
		echo "<script>  alert('Sign Out Complete'); </script>";
		echo "<script>window.location.replace('Listview_login.php');</script>";
	}
	if($_REQUEST["myaction"]=="refresh"){
		echo "<script>window.location.replace('Listview_login.php');</script>";
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Users Login View</title>
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
     <TABLE border=0 cellspacing=0 cellpadding=0 width=1000  background="themes/softed/images/Ai-HEAD.jpg" style="background-repeat:no-repeat" height="77">
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
          <tr>
            <form name="inq_trouble_tickets" method="get"  action="Listview_login.php" >
                                    <input type="hidden" name="myaction" value="" />
                                    <input type="hidden" name="record" value="" />
			 <td style="padding-left:10px;padding-right:10px" class=small nowrap align="right"> <a href="Listview_login.php?myaction=signout" onClick="this.form.myaction.value='signout';">Sign Out</a> (admin)</td>
             </form>
          </tr>
        </table>

        </td>
	</tr>
	</TABLE>
 
	<table border=0 cellspacing=0 cellpadding=0 width=1013 align=left>
     <tr>
        <td valign=top><img src="themes/softed/images/showPanelTopLeft.gif"></td>
        <td class="showPanelBg" valign="top" width=100% style="padding:10px;" height="440">
              <table width="800" border="0" align="center" cellpadding="0" cellspacing="6" height="440" >
                              <tr>
                                <td valign="top">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                            <!-- -->
                            
                             <tr>
                                <td >
                                   <div id="container">
                                    <table width="100%" border=0 cellspacing=0 cellpadding=0 class="lvt small">
                                    <form name="inq_trouble_tickets" method="get"  action="Listview_login.php" >
                                    <input type="hidden" name="myaction" value="" />
                                    <input type="hidden" name="record" value="" />
                                      <tr>
                                        <td colspan="14" class="dvInnerHeader"><b>Users Login View</b></td>
                                      </tr>         
                                      <tr>
                                        <td height="30" colspan="2" class="lvtCol">
                                        
                                        <b>&nbsp;&nbsp;Search&nbsp;&nbsp;</b>
                                        <input type="text" name="txt_search_ticket" id="txt_search_ticket" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:150px" value="<?=$_SESSION["txt_search_ticket"]?>"/>
                                        &nbsp;&nbsp;<!--<img src="../../callcenter_themes/images/Find.gif" width="16" height="16" alt="Search Trouble Tickets" onclick="this.form.myaction.value='search';" style="cursor:pointer"/>
                                        <input title="Search" accessKey="S" class="crmbutton small save" onClick="this.form.myaction.value='search'; " type="submit" name="button" value="Search" style="width:70px;cursor: pointer;" >-->
                                        <input title="Search Trouble Tickets" src="themes/softed/images/Find.gif" type="image" name="Search" value=""  tabindex="5" onClick="this.form.myaction.value='search'; ">
                                        &nbsp;
                                        <input title="Refresh Page" src="themes/softed/images/refresh.png" type="image" name="Search2" value=""  tabindex="5" onClick="this.form.myaction.value='refresh'; "></td>
                                      </tr>
                                      </form>        
                                    </table>
                                          
                                    <table id='myTable' width="100%" border=0 cellspacing=1 cellpadding=0 class="lvt small">
                                        <thead>
                                          <tr bgcolor="#FFFFFF">
                                            <th width="5%" class="lvtCol" align="center"><b>No.</b></th>
                                            <th width="25%" class="lvtCol" align="center">User Name</th>
                                            <th width="20%" class="lvtCol" align="center">Computer Name And IP</th>
                                            <th width="20%" class="lvtCol" align="center">Start Time</th>
                                            <th width="20%" class="lvtCol" align="center">End Time</th>
                                            <th width="15%" class="lvtCol" align="center"><b>Sign Out</b></th>
                                          </tr>    
                                          </thead>    
                                           <tbody>
                                <?
								$sql = "
								update ai_check_user_login set status=1
								where end_time < '".$_SESSION["user_start_time"]."'
								";
								$genarate->query($sql);
                                $sql = "
                                select *
                                from ai_check_user_login
                                  
                                ".$where." 
                                order by id 
                                ";
                                
                                $totalrows = $genarate->process_row($sql,"all");
                                $totalpage = ceil($totalrows/$genarate->max_rows);	
                                $sql .=  " limit ".$genarate->max_rows * ($_REQUEST["param_page"]-1) .",".$genarate->max_rows;
                                $data =$genarate->process($sql,$_REQUEST["param_page"]);
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
                                            <td align="center" >
                                            <?
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',($k+1))."<br>";
                                            ?>				
                                            </td>
                                            <td  align="left"><?
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k][$a+2])."<br>";
                                            ?></td>
                                            <td  align="left"><?
                                            if($data[$k][$a+3]!=""){
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k][$a+3])."<br>";
                                            }else{
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k][$a+3])."<br>";
                                            }
                                            ?></td>
                                            <td  align="center"><?
                                            if($data[$k][$a+4]!=""){
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k][$a+4])."<br>";
                                            }else{
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k][$a+4])."<br>";
                                            }
                                            ?></td>
                                            <td  align="center"><?
                                            if($data[$k][$a+5]!=""){
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k][$a+5])."<br>";
                                            }else{
                                            echo  str_ireplace($_SESSION["txt_search_ticket"],'<font style="background-color:#FF9900">'.$_SESSION["txt_search_ticket"].'</font>',$data[$k][$a+5])."<br>";
                                            }
                                            ?>
                                            </td>
                                            <td  align="center">
                                            <input title="Click Sign Out <?=$data[$k][$a+2]?>" src="themes/softed/images/Erase.gif" type="image" name="Search" value=""  tabindex="5" onClick="this.form.myaction.value='delete';this.form.record.value='<?=$data[$k][$a]?>'; "> 
                                            </td>
                                          </tr>

                                          </form>
                                          <?php
                                                }
                                            ?>
                                          <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'">
                                            <td align="center" >&nbsp;</td>
                                            <td  align="left">&nbsp;</td>
                                            <td  align="left">&nbsp;</td>
                                            <td  align="center">&nbsp;</td>
                                            <td  align="center">&nbsp;</td>
                                            <td  align="center">&nbsp;</td>
                                          </tr>                                          
                                        </tbody> 
                                <form name="navigatorForm">
                                <INPUT TYPE="hidden" NAME="param_page" value="<?=$_REQUEST["param_page"]?>">
                                <input type="hidden" name="sort" value="<?=$sort?>">
                                <input type="hidden" name="order" value="<?=$order?>">
                                <tr>
                                    <td colspan="7" align="center" nowrap bgcolor="#FFFFFF" class="small">
                                        Showing Records 
                                        <?php //if($genarate->total_rows != 0) {?>
                                        <? 
                                        echo ($genarate->max_rows * ($_REQUEST["param_page"]-1))+1; 
                                        ?>
                                            -
                                        <? 
                                        echo ($genarate->max_rows * ($_REQUEST["param_page"]-1))+1; 
                                        ?>
                                         From
                                       <?php 
                                       // }
                                        ?>
                                        <?=number_format($genarate->total_rows,',')?> 
                                        Records 
                                 
                                        <?php
                                        if($genarate->ispreviouspage()){
                                        ?>
                                        <img border=0 src="themes/softed/images/start.gif" WIDTH=16 HEIGHT=16 hspace=2 onClick="document.navigatorForm.param_page.value=1;document.navigatorForm.submit();" onMouseOver="this.style.cursor='hand'" style="cursor:pointer">
                                        <img border=0 src="themes/softed/images/previous.gif" WIDTH=16 HEIGHT=16 hspace=2 onClick="document.navigatorForm.param_page.value=<?=($_REQUEST["param_page"]-1)?>;document.navigatorForm.submit();" onMouseOver="this.style.cursor='hand'" style="cursor:pointer">
                                        <?php
                                        }else{
                                                echo "<img border=0 src='themes/softed/images/start.gif' WIDTH=16 HEIGHT=16 hspace=2>";
												echo "<img border=0 src='themes/softed/images/previous.gif' WIDTH=16 HEIGHT=16 hspace=2>";
                                        }
                                        ?>
                                       
                                    <input type=text class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:20px" onkeypress="NumOnly();if(event.keyCode=='13'){if(this.value>0&&this.value<=<?=$totalpage?>&&this.value!=<?=($_REQUEST["param_page"])?>){document.navigatorForm.param_page.value=this.value;document.navigatorForm.submit();}else{this.value=<?=($_REQUEST["param_page"])?>;}}" size=2 maxlength=5 value="<?=($_REQUEST["param_page"])?>" />&nbsp;of&nbsp;<?=number_format($totalpage,0)?>&nbsp;            
                                        <?php
                                          if($genarate->isnextpage()){
                                        ?>
                                        <img border=0 src="themes/softed/images/next.gif"  WIDTH=16 HEIGHT=16 hspace=2 onClick="document.navigatorForm.param_page.value=<?=($_REQUEST["param_page"]+1)?>;document.navigatorForm.submit();" onMouseOver="this.style.cursor='hand'" style="cursor:pointer">
                                        <img border=0 src="themes/softed/images/end.gif"  WIDTH=16 HEIGHT=16 hspace=2 onClick="document.navigatorForm.param_page.value=<?=$totalpage?>;document.navigatorForm.submit();" onMouseOver="this.style.cursor='hand'" style="cursor:pointer">
                                        <?php
                                        }else{
                                                echo "<img border=0 src='themes/softed/images/next.gif' WIDTH=16 HEIGHT=16 hspace=2>";
												echo "<img border=0 src='themes/softed/images/end.gif' WIDTH=16 HEIGHT=16 hspace=2>";
                                        }
                                        ?>
                                    </td>
                                </tr>
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
        <table border=0 cellspacing=0 cellpadding=5 width=1000 class=settingsSelectedUI ><tr><td class=small align=left><span style='color: rgb(153, 153, 153);'>Ai-CRM v1.3</span></td><td class=small align=right><span style='color: rgb(153, 153, 153);'>&copy; 2007-2010 <a href='http://www.aisyst.com' target='_blank'>http://www.aisyst.com</a></span></td></tr>
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

