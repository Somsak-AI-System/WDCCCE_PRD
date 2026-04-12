<?php
	/*session_start();
	include("library/dbconfig.php");
	include("library/genarate.inc.php");
	$genarate = new genarate($dbconfig ,"bmmt");

if($_REQUEST["myaction"]=="signout"){
	unset($_SESSION['id']);
	unset($_SESSION['user_name']);
	unset($_SESSION['pcnm']);
	unset($_SESSION['id']);
	unset($_SESSION['time']);
	unset($_SESSION['Security']);
	echo "<script>window.location.replace('index.php');</script>";
}
if($_REQUEST["myaction"]=="login"){
	$user_name=$_REQUEST['username'];
	$user_password=$_REQUEST['password'];
	//echo $user_name;
	//echo $user_password;
	if($user_name=="" || $user_password==""){
		$login_error = $mod_strings['ERR_INVALID_PASSWORD'];
		echo "<script>  alert('You must specify a valid username and password.'); </script>";
		echo "<script>window.location.replace('index.php');</script>";
		exit;
	}else{
		$sql="select * from tbm_syst_users where username='".$user_name."' and password='".$user_password."'  ";
		$data = $genarate->process($sql,"all");			
		//print_r($data);
		
		if(count($data)>0){
			$_SESSION['id']=$data[0]['id'];
			$_SESSION['user_name']=$data[0]['fristname']." ".$data[0]['lastname'];
			$_SESSION['pcnm']=gethostbyaddr($_SERVER['REMOTE_ADDR']);
			$_SESSION['id']=$_SERVER['REMOTE_ADDR'];
			$_SESSION['time']=date('H:i',strtotime('-1 hour'));
			//echo $_SESSION['empcd'];exit;
		
			$sql="select * from  tbm_syst_securities where rolecd='".$data[0]['rolecd']."' ";
			$Security = $genarate->process($sql,"all");
			//print_r($Security);
			if(count($Security)>0){
				$_SESSION["Security"]=$Security;
			}
			//print_r($_SESSION["Security"]);
			echo "<script>window.location.replace('home.php');</script>";
		}
		else
		{
			echo "<script>alert('Username or Password is incorrect.'); </script>";
			echo "<script>window.location.replace('index.php');</script>";
			exit;
		}
	}
	mysql_close($con);
}//myaction	
	*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>

<!--------------------
LOGIN FORM
by: Amit Jakhu
www.amitjakhu.com
--------------------->
<!--META-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>E-Newsletter</title>

<!--STYLESHEETS-->
<link href="css/style_login.css" rel="stylesheet" type="text/css" />

<!--SCRIPTS-->
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--Slider-in icons-->
<script type="text/javascript">
$(document).ready(function() {
	$(".username").focus(function() {
		$(".user-icon").css("left","-48px");
	});
	$(".username").blur(function() {
		$(".user-icon").css("left","0px");
	});
	
	$(".password").focus(function() {
		$(".pass-icon").css("left","-48px");
	});
	$(".password").blur(function() {
		$(".pass-icon").css("left","0px");
	});
});
</script>
<script>
function fnLogin(){
	//alert(555);
	if(document.login.username.value!="" || document.login.password.value!=""){
		document.login.myaction.value='login';
		document.login.submit();
	}
	else
	{
		alert("กรุณากรอกชื่อผู้ใช้งานหรือรหัสผ่านให้ครบถ้วน");
	}
}
</script>

</head>
<body>

<!--WRAPPER-->
<div id="wrapper">
	<!--SLIDE-IN ICONS-->
<div class="user-icon"></div>
    <div class="pass-icon"></div>
    <!--END SLIDE-IN ICONS-->

<!--LOGIN FORM-->
<form name="login" class="login-form" action="" method="post">
<input name="myaction" id="myaction" type="hidden"/>
	<!--HEADER-->
   <div class="header">
    <!--TITLE-->
    <!--<h1>LoginDESCRIPTION--><!--END DESCRIPTION   </h1> -->
    </div>
    <!--END HEADER-->
	
	<!--CONTENT-->
    <div class="content">
   <div style="position:absolute;  top: 50px; left: 30px;">Username:</div>
	<!--USERNAME--><input name="username" id="username" type="text" class="input username" value="" onfocus="this.value=''" /><!--END USERNAME-->
   <div style=" position:absolute;padding-top:8px;">Password:</div>
    <!--PASSWORD--><input name="password" id="password" type="password" class="input password" value="" onfocus="this.value=''" /><!--END PASSWORD-->
    </div>
    <!--END CONTENT-->
    
    <!--FOOTER-->
    <div class="footer">
    <!--LOGIN BUTTON<input type="button" name="Login" value="Login" class="button" onclick="fnLogin()"/>--><!--END LOGIN BUTTON-->
    <input type="button" name="Login" value="Login" class="button" onclick="window.location='home-1.php'"/>
    </div>
    <!--END FOOTER-->

</form>
<!--END LOGIN FORM-->

</div>
<!--END WRAPPER-->

<!--GRADIENT--><div class="gradient"></div><!--END GRADIENT-->

</body>
</html>