<?php
	require_once("checklogin.php");
	$type = $_POST['typeinfo'];
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=".$type.".xls");
	header("Pragma: no-cache");

	$buffer = $_POST['csvBuffer'];

	try{
		echo $buffer;
	}catch(Exception $e){

	}
?>