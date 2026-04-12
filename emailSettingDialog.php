<?php
require_once ("config.inc.php");
date_default_timezone_set("Asia/Bangkok");
global $path,$url_path;

ini_set('memory_limit', '4024M');

$path = $root_directory;

require_once ($path."library/dbconfig.php");
require_once ($path."library/genarate.inc.php");
$genarate = new genarate($dbconfig ,"DB");

$sql = "SELECT email_start_time FROM aicrm_email_start_time";
$times = $genarate->process($sql,"all");

?>
<table>
    <tr>
        <td>
            <input class="form-check-input" type="radio" id="type1" name="type" value="1" checked>
            <label class="form-check-label" for="type1">
                ทันที
            </label>
        </td>
    </tr>
    <tr>
        <td>
            <input class="form-check-input" type="radio" id="type2" name="type" value="2">
            <label class="form-check-label" for="type2">
                ตั้งเวลา
            </label>

            <input type="text" id="date" name="datepicker" class="datepicker" value="<?php echo date('d-m-Y')?>"> 
            <select name="time" id="time">
                <?php for($i=0; $i<count($times); $i++){ ?>
                    <option <?php if(date('H:i') == $times[$i][0]) echo 'selected' ?> value="<?php echo $times[$i][0]; ?>"><?php echo $times[$i][0]; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            <button class="crmbutton btncountview" type="button" onclick="saveSettingEmail('<?php echo $_REQUEST['crmID']; ?>', '<?php echo $_REQUEST['Module']; ?>')">Save</button>
            <button class="crmbutton btnsendmail" type="button" onclick="">Cancel</button>
        </td>
    </tr>
</table>

<script>
    jQuery(document).ready(function(){
        jQuery('.datepicker').datepicker({
            showOn: "button",
            buttonImage: "themes/softed/images/btnL3Calendar.gif",
            buttonImageOnly: true,
            buttonText: "Select date",
            dateFormat: "dd-mm-yy"
        })
    })
</script>