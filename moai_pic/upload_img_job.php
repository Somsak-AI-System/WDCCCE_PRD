<? 
session_start();
if(!isset($_GET['crmid'])){
	exit(0);
}
?>

<style type="text/css">
    .panel-body{
        padding: 15px !important;
        font-size: 14px !important;
    }


</style>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Image </title>
    <link rel="shortcut icon" href="../themes/AICRM.ico" type="image/x-icon">
	<!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bower_components/custom.css" rel="stylesheet">
    
</head>
<body>

<!-- Pre-loading -->
<div id="loadPage" style="display:none">
  <img alt="" src="../asset/images/ajax-loader.gif">
  <span>Loading ...</span> 
</div>

<div id="wrapper" style="padding:20px 20px 20px 20px;margin:0px 20px 20px 20px">

<iframe name="uploadtarget" style="display:none;width:300px;height:350px;overflow:auto"></iframe>
	<div class="row">
        <div class="panel panel-primary">
        	<div class="panel-heading" align="center">Image</div>
        	<div class="panel-body">
	        	<form id="frmUpload" method="post" action="upload_controller.php" enctype="multipart/form-data" target="uploadtarget">                        
                    <input type="file" name="image" class="inputFile">
                    <input type="hidden" name="mode" value="upload_img">
                    <input type="hidden" name="snowid" value="<?=$_SESSION["authenticated_user_id"] ?>">
                    <input type="hidden" name="module" value="<?=$_GET["module"] ?>">
                    <input type="hidden" name="crmid" value="<?=$_GET['crmid']?>">
                    <input type="hidden" name="index" value="">
                    <input type="button" value="UPLOAD" class="button_upload center-block btn-block btn-success">
                </form>
        	</div>
            <!-- /.panel-body -->
     	</div>
        <!-- /.panel -->
    </div>
    <!-- /.row -->
</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="../asset/css/metro-blue/easyui.css">
<link rel="stylesheet" type="text/css" media="all" href="../asset/css/smoothness/jquery-ui-1.10.4.custom.min.css">
<link rel="stylesheet" type="text/css" media="all" href="../asset/css/icon.css">

<script type="text/javascript" src="../asset/js/jquery.min.js"></script>
<script type="text/javascript" src="../asset/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../asset/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="../asset/js/jquery.easyui.min.js"></script>

<!-- bootstrap-multiselect -->
<link rel="stylesheet" href="js/bootstrap-multiselect/css/bootstrap-multiselect.css" type="text/css">
<script type="text/javascript" src="js/bootstrap-multiselect/js/bootstrap-multiselect.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	
	$('.button_upload').click(function() {

        var inputFile	= $(this).siblings('input[name=image]').val();
        var jobid       = $(this).siblings('input[name=jobid]').val();
        //var position   	= $(this).siblings('input[name=position]').val();

        /* If jobid == '' cannot upload so it will close modal */
        if(jobid == "" ){
            $.messager.alert('Warning','เกิดข้อผิดพลาด กรุณาเลือกรูปภาพอีกครั้ง');
			window.close();
        }

        /* If input file not has file cannot upload */
        if(inputFile != ""){
            $(this).parent('form').submit();
            $('#close-upload-modal').trigger('click');
            $("#loadPage").show();
        }else{
            $.messager.alert('Warning','กรุณาเลือกไฟล์');
            return false;
        }  
    });
});

/* after uploaded image already */
function afterUploaded(jobid , error , file_name , index , temp_id){
    
    if(error == false){
        $.messager.confirm({title: 'Message',msg: 'อัพโหลดไฟล์สำเร็จ',
            buttons:[{
                text: 'OK',
                onClick: function(){
                    window.opener.location.reload();
                    window.close();
                }
            }]
        });        
    
    }else{

        var msg;

        switch(error){
            case "error_file_name":     msg = "ไม่มีไฟล์";                 break;
            case "error_file_error":    msg = "ไฟล์ผิดพลาด";               break;
            case "error_file_size":     msg = "ขนาดไฟล์เกินที่กำหนด";     break;
            case "error_file_type":     msg = "ประเภทไฟล์ไม่รองรับ";         break;
            default:                    msg = "ไม่สามารถอัพโหลดไฟล์ได้";    break;
        }
        $.messager.confirm({title: 'Message',msg: 'อัพโหลดไฟล์สำเร็จ',
            buttons:[{
                text: 'OK',
                onClick: function(){
                    window.opener.location.reload();
                    window.close();
                }
            }]
        });   
    }

    $("#loadPage").hide();
}
</script>
</body>
</html>
