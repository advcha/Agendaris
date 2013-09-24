<?php
	require_once("checklogin.php");
	include("include/dbconfig.php");
	include("include/JSON.php");
	$json = new Services_JSON();

	$searchOn = strip_tags(isset($_REQUEST['search'])?$_REQUEST['search']:"");
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	
	if($searchOn == 'true')
	{
		$SQL = "SELECT '0' AS idbagian,'--Semua Bagian--' AS bagian
			UNION
			SELECT idbagian,bagian FROM bagian";
	}
	else
	{
		$SQL = "select idbagian,bagian FROM bagian order by idbagian";
	}
	$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());

	$bagian = "";
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
	{
		$bagian .= $row['idbagian'].":".$row['bagian'].";";
	}
	if($bagian != "")
		$bagian = substr($bagian,0,-1);
	echo $bagian;
?>