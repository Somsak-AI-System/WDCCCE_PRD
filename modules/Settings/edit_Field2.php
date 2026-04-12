<?
	session_start();
	
	include("../../config.inc.php");
	include("../../library/dbconfig.php");
	include("../../library/myFunction.php");
	include("../../library/generate_MYSQL.php");
	//include("library/function.php");
	global $generate,$current_user;
	$generate = new generate($dbconfig ,"DB");


	if($_REQUEST["action"]=="edit" and $_REQUEST["field_id"]!="" and $_REQUEST["myaction"]!="save"){
		$field_id=$_REQUEST["field_id"];
		$sql="select * 
		from aicrm_field 
		left join aicrm_tab on aicrm_tab.tabid=aicrm_field.tabid
		where fieldid='".$field_id."'";
		//echo $sql;
		$data = $generate->process($sql,"all");	
		if(count($data)>0){
			$_REQUEST["tbx_fieldlabel"]=$data[0]['fieldlabel'];
			$_REQUEST["tbx_tabid"]=$data[0]['tabid'];
			$_REQUEST["tbx_tabname"]=$data[0]['name'];
			$_REQUEST["tbx_columnname"]=$data[0]['columnname'];
			$_REQUEST["tbx_tablename"]=$data[0]['tablename'];
			$_REQUEST["tbx_uitype"]=$data[0]['uitype'];
			
			$sql="
			SELECT *
			FROM information_schema.columns
			WHERE table_schema =  '".$dbconfig["DB"]["dbname"]."'
			AND table_name =  '".$_REQUEST["tbx_tablename"]."'
			AND column_name =  '".$_REQUEST["tbx_columnname"]."'
			";
			$data_chk = $generate->process($sql,"all");
			$_REQUEST["tbx_comment"]=$data_chk[0]["COLUMN_COMMENT"];
		}
	}
	if($_REQUEST["myaction"]=="save"){
		if($_REQUEST["action"]=="edit"){
			$field_id=$_REQUEST["field_id"];
			$tabid=$_REQUEST["tbx_tabid"];
			$sql="select * from aicrm_field 
			where fieldid != '".$field_id."'
			and  tabid='".$tabid."'
			and fieldlabel='".$_REQUEST["tbx_fieldlabel"]."'
			";
			//echo $sql;exit;
			$data = $generate->process($sql,"all");	
			if(count($data)>0){
				echo "<script type='text/javascript'> alert('Label already exists. Please specify a different Label');</script>";	
			}else{
				$sql="update aicrm_field set 
				fieldlabel='".$_REQUEST["tbx_fieldlabel"]."'
				,tablename='".$_REQUEST["cbo_tablename"]."'
				,columnname='".$_REQUEST["tbx_new_columnname"]."'
				,fieldname='".$_REQUEST["tbx_new_columnname"]."'
				,uitype='".$_REQUEST["tbx_uitype"]."'
				where fieldid='".$field_id."'
				and  tabid='".$tabid."'
				";
				//echo $sql."<br>";
				//echo $sql;exit;
				$generate->query($sql);
				$sql="
				SELECT *
				FROM information_schema.columns
				WHERE table_schema =  '".$dbconfig["DB"]["dbname"]."'
				AND table_name =  '".$_REQUEST["tbx_tablename"]."'
				AND column_name =  '".$_REQUEST["tbx_columnname"]."'
				";
				$data_chk = $generate->process($sql,"all");	
				/*echo "<pre>";
				print_r($data_chk);
				echo "</pre>";*/
				$is_null="NOT NULL";
				if($data_chk[0]["IS_NULLABLE"]=="YES"){
					$is_null="NULL";	
				}
				if($_REQUEST["tbx_tablename"]!=$_REQUEST["cbo_tablename"]){
					$sql="ALTER TABLE  ".$_REQUEST["tbx_tablename"]." DROP  ".$_REQUEST["tbx_columnname"]."; ";
					//echo $sql."<br>";
					$generate->query($sql);
					if($_REQUEST["tbx_uitype"]=="5"){
						$sql="ALTER TABLE  ".$_REQUEST["cbo_tablename"]." ADD  ".$_REQUEST["tbx_new_columnname"]." ".$data_chk[0]["COLUMN_TYPE"]."  COMMENT  '".$_REQUEST["tbx_comment"]."'   ;";
						//echo $sql;exit;
						$generate->query($sql);
					}elseif($_REQUEST["tbx_uitype"]=="7"){
						$sql="ALTER TABLE  ".$_REQUEST["cbo_tablename"]." ADD  ".$_REQUEST["tbx_new_columnname"]." ".$data_chk[0]["COLUMN_TYPE"]." ".$is_null." DEFAULT  '0' COMMENT  '".$_REQUEST["tbx_comment"]."'   ;";
						//echo $sql;exit;
						$generate->query($sql);
					}else{
						$sql="ALTER TABLE  ".$_REQUEST["cbo_tablename"]." ADD  ".$_REQUEST["tbx_new_columnname"]." ".$data_chk[0]["COLUMN_TYPE"]." ".$is_null." DEFAULT  '".$data_chk[0]["COLUMN_DEFAULT"]."' COMMENT  '".$_REQUEST["tbx_comment"]."'   ;";
						//echo $sql;exit;
						$generate->query($sql);
					}
					//echo $sql;exit;
				}else{
					if($_REQUEST["tbx_uitype"]=="5"){
						$sql="ALTER TABLE  ".$_REQUEST["tbx_tablename"]." CHANGE ".$_REQUEST["tbx_columnname"]." ".$_REQUEST["tbx_new_columnname"]." ".$data_chk[0]["COLUMN_TYPE"]." COMMENT  '".$_REQUEST["tbx_comment"]."'   ;";
						//echo $sql;exit;
						$generate->query($sql);
					}elseif($_REQUEST["tbx_uitype"]=="7"){
						$sql="ALTER TABLE  ".$_REQUEST["cbo_tablename"]." ADD  ".$_REQUEST["tbx_new_columnname"]." ".$data_chk[0]["COLUMN_TYPE"]." ".$is_null." DEFAULT  '0' COMMENT  '".$_REQUEST["tbx_comment"]."'   ;";
						//echo $sql;exit;
						$generate->query($sql);
					}else{
						$sql="ALTER TABLE  ".$_REQUEST["tbx_tablename"]." CHANGE ".$_REQUEST["tbx_columnname"]." ".$_REQUEST["tbx_new_columnname"]." ".$data_chk[0]["COLUMN_TYPE"]." ".$is_null." DEFAULT  '".$data_chk[0]["COLUMN_DEFAULT"]."' COMMENT  '".$_REQUEST["tbx_comment"]."'   ;";
						// echo $sql;exit;
						$generate->query($sql);	
					}
					//echo $sql;exit;
				}
				//echo $sql;exit;
				echo "<script type='text/javascript'> alert('Edit Complete');window.close();window.opener.parent.location.replace('../../index.php?module=Settings&action=list_Field2&parenttab=Settings');</script>";	
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../themes/softed/style.css">
<title>Edit Label</title>
</head>
<script> 
function check_number(field) {
	//alert(field);
	var len, digit , chk;
	if(document.getElementById(field).value == " "){
		alert("Pleate Input Number Only!..");
		len=0;
	}else{
		len = document.getElementById(field).value.length;
	}
	for(var i=0 ; i<len ; i++){
		digit = document.getElementById(field).value.charAt(i)
		//alert(digit);
		if((digit >="0" && digit <="9") || digit=="." ){
		}else{
			chk='1';
		}
	}
	if(chk=="1"){
		alert("Pleate Input Number Only!..");
		document.getElementById(field).value="0";
	}	
}				
</script>

<body topmargin="0" leftmargin="0">
<form name="warranty" method="post" enctype="multipart/form-data" action="edit_Field2.php">
<input type="hidden" name="myaction" value="" />
<input type="hidden" name="action" value="<?=$_REQUEST["action"]?>" />
<input type="hidden" name="field_id" value="<?=$_REQUEST["field_id"]?>" />
<input type="hidden" name="tbx_tabid" value="<?=$_REQUEST["tbx_tabid"]?>" />
<table width="100%" border="0" class="crmTable small" cellpadding="5" cellspacing="0">
        <tr>
          <td colspan="2" align="left" bgcolor="#999999" class="dvInnerHeader"><strong>Modules Layout Editor V2.0</strong></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">Module Name</td>
          <td align="left" class="dvtCellInfo"><input name="tbx_tabtbx_tabnamelename" type="text" class="detailedViewTextBox" id ="tbx_tabname" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["tbx_tabname"]?>" readonly="readonly" style="background:#CCC"  /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">Table Name</td>
          <td align="left" class="dvtCellInfo"><input name="tbx_tablename" type="text" class="detailedViewTextBox" id ="tbx_tablename" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["tbx_tablename"]?>" readonly="readonly" style="background:#CCC"    /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">Column Name</td>
          <td align="left" class="dvtCellInfo"><input name="tbx_columnname" type="text" class="detailedViewTextBox" id ="tbx_columnname" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["tbx_columnname"]?>"   readonly="readonly" style="background:#CCC"   /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">New Table Name</td>
          <td align="left" class="dvtCellInfo">
				<?
                $sql = "select tablename from aicrm_field where 1 and tabid='".$_REQUEST["tbx_tabid"]."' and tablename!='aicrm_crmentity' group by tablename";
				//echo $sql;
                $emailsend = $generate->process($sql,"all");
				if($_REQUEST["cbo_tablename"]==""){
					$_REQUEST["cbo_tablename"]=	$_REQUEST["tbx_tablename"];
				}
                ?>
                <select name="cbo_tablename" id="cbo_tablename"  class="small" >
                <?php
                for($k=0;$k<count($emailsend);$k++){
                $a = 0;
                ?>
                <option value="<?=$emailsend[$k]["tablename"]?>" <?php if($emailsend[$k]["tablename"]==$_REQUEST["cbo_tablename"]){ echo "selected";}?>>
                <?=$emailsend[$k]["tablename"]?>
                </option>
                <?
                }
                ?>
                </select>
          </td>
        </tr>
        <tr>
          <td width="40%" align="left" class="dvtCellLabel">Label Name</td>
          <td width="60%" align="left" class="dvtCellInfo"><input name="tbx_fieldlabel" type="text" class="detailedViewTextBox" id ="tbx_fieldlabel" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["tbx_fieldlabel"]?>"    /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">New Column Name</td>
          <td align="left" class="dvtCellInfo"><input name="tbx_new_columnname" type="text" class="detailedViewTextBox" id ="tbx_new_columnname" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["tbx_columnname"]?>"    /></td>
        </tr>
        <tr>
        	<?
			if($_REQUEST["tbx_comment"]==""){
				$_REQUEST["tbx_comment"]=	$_REQUEST["tbx_fieldlabel"];
			}
			?>
          <td align="left" class="dvtCellLabel">Comment</td>
          <td align="left" class="dvtCellInfo"><input name="tbx_comment" type="text" class="detailedViewTextBox" id ="tbx_comment" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["tbx_comment"]?>"    /></td>
        </tr>
        <tr>
          <td align="left" class="dvtCellLabel">Uitype</td>
          <td align="left" class="dvtCellInfo"><input name="tbx_uitype" type="text" class="detailedViewTextBox" id ="tbx_uitype" tabindex="" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="<?=$_REQUEST["tbx_uitype"]?>"  readonly="readonly" style="background:#CCC" /></td>
        </tr>
        <tr>
          <td colspan="2" align="center" class="lvtCol"><input title="Save" accesskey="S" class="crmbutton small save" onclick="this.form.myaction.value='save'; " type="submit" name="button" value="Save" style="width:80px;cursor: pointer;" />
            
            &nbsp;
            <input title="Cancel" accesskey="C" class="crmbutton small cancel" onclick="window.close();" type="submit" name="button3" value="Cancel" style="width:80px;cursor: pointer;" />
            </td>
        </tr>
      </table>
</form>      
</body>
</html>