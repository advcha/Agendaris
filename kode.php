<?php
	require_once("checklogin.php");
	include("include/dbconfig.php");
	include("include/JSON.php");
	$json = new Services_JSON();

	$searchOn = strip_tags($_REQUEST['search']);
	$idkode = strip_tags($_REQUEST['idkode']);
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	
	if($searchOn == 'true')
	{
		$SQL = "SELECT '0' AS idkode,'--Semua Indeks--' AS kode,'--Semua Hal--' as hal 
			UNION
			SELECT k.idkode,k.kode,k.hal FROM kodesurat k 
			INNER JOIN bagian b ON k.idbagian=b.idbagian where k.idkode = ".$idkode;
	}
	else
	{
		$SQL = "SELECT k.idkode,k.kode,k.hal FROM kodesurat k 
			INNER JOIN bagian b ON k.idbagian=b.idbagian where k.idkode = ".$idkode;
		
		if($idkode == '0')
			$SQL = "SELECT idkode,kode,hal FROM kodesurat ORDER BY idkode limit 1";
	}
	//echo $SQL;
	$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());

	$kode = "";
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
	{
		$kode .= $row['idkode'].":".$row['kode'].":".$row['hal'].";";
	}
	if($kode != "")
		$kode = substr($kode,0,-1);
	echo $kode;
?>