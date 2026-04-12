<?php 
	$filename =  $template['modulename'].'_'.date('d-m-Y').'.xls';
	header("Content-Type: application/x-msexcel; name=\"$filename\"");
	header("Content-Disposition: inline; filename=\"$filename\"");
	header("Pragma:no-cache");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body class="<? echo $this->session->userdata('user.theme');  ?>">
	<?php echo $template['body']; ?>
</body>
</html>