<?php
	require_once("checklogin.php");
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
</head>
<body onload="window.print();">
<form>
<?php
	$type = $_POST['typeinfo'];
	header('Content-Type: text/html; charset=utf-8');
	//header("Content-Disposition: attachment; filename=".$type.".xls");
	header("Pragma: no-cache");

	$buffer = stripslashes($_POST['csvBuffer']);

	try{
		echo $buffer;
	}catch(Exception $e){

	}
?>
	<!--input type="button" value="Print" onclick="window.print();"-->
</form>
</body>
</html>