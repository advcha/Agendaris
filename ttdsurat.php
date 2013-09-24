<?php
	require_once("checklogin.php");
	include("include/clsUtility.php");
	include("include/dbconfig.php");
	include("include/JSON.php");
	$json = new Services_JSON();
	$myfunction = new MyFunction();

	$idxttd = isset($_REQUEST['ttd'])?$_REQUEST['ttd']:"";
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	$ttdsurat = "";
	if($idxttd != "")
	{
		$SQL = "SELECT jabatan FROM ttd
			WHERE idxttd = '".$idxttd."' LIMIT 1";
		$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$ttdsurat = $row['jabatan'];
	}
	echo $ttdsurat;
?>