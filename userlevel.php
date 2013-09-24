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
		$SQL = "SELECT '0' AS iduserlevel,'--Semua Tingkatan--' AS userlevel
			UNION
			SELECT iduserlevel,userlevel FROM userlevel";
	}
	else
	{
		$SQL = "select iduserlevel,userlevel FROM userlevel order by iduserlevel";
	}
	$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());

	$userlevel = "";
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
	{
		$userlevel .= $row['iduserlevel'].":".$row['userlevel'].";";
	}
	if($userlevel != "")
		$userlevel = substr($userlevel,0,-1);
	echo $userlevel;
?>