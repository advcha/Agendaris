<?php
	require_once("checklogin.php");
	include("include/dbconfig.php");
	include("include/JSON.php");
	$json = new Services_JSON();

	$jenis = strip_tags($_REQUEST['jenis']);
	$bln = strip_tags($_REQUEST['bln']);
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	
	$SQL = "";
	if($jenis == "0")
	{
		$SQL = "SELECT CAST(0 AS UNSIGNED) AS idbagian,'--Semua Indek' AS bagian
			UNION 
			SELECT DISTINCT b.idbagian,b.bagian FROM suratmasuk s
			INNER JOIN kodesurat k ON s.idkode=k.idkode
			INNER JOIN bagian b ON k.idbagian=b.idbagian
			WHERE SUBSTR(s.tgl_terima,1,7)='".$bln."'";
	}
	else
	{
		$SQL = "SELECT CAST(0 AS UNSIGNED) AS idbagian,'--Semua Indek' AS bagian
			UNION 
			SELECT DISTINCT b.idbagian,b.bagian FROM suratkeluar s
			INNER JOIN kodesurat k ON s.idkode=k.idkode
			INNER JOIN bagian b ON k.idbagian=b.idbagian
			WHERE SUBSTR(s.tgl_surat,1,7)='".$bln."'";
	}
	$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());

	$idx = "";
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
	{
		$idx .= $row['idbagian'].":".$row['bagian'].";";
	}
	if($idx != "")
		$idx = substr($idx,0,-1);
	echo $idx;
?>