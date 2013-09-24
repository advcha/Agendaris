<?php
	require_once("checklogin.php");
	include("include/clsUtility.php");
	include("include/dbconfig.php");
	include("include/JSON.php");
	$json = new Services_JSON();
	$myfunction = new MyFunction();

	$tgl = $myfunction->dateformatSlashDb($_REQUEST['tgl']);
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	
	$SQL = "SELECT COUNT(idsuratkeluar) AS cnt FROM suratkeluar
		WHERE tgl_surat > '".$tgl."'";
	$result = mysql_query($SQL) or die("Could not execute query.".mysql_error());
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$count = $row['cnt'];
	$nosuratbaru = "";

	if($count > 0 ) 
	{
		$SQL = "SELECT no_surat4 FROM suratkeluar
			WHERE tgl_surat = '".$tgl."' ORDER BY idsuratkeluar DESC LIMIT 1";
		$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$nosuratlama = $row['no_surat4'];
		
		$arrnosurat = explode(".",$nosuratlama);
		if(count($arrnosurat)>1)	//ada .a atau .b, dll
			$nosuratbaru = $arrnosurat[0].".".chr(ord($arrnosurat[1]) + 1);
		else	//tidak ada .a atau .b, dll
			$nosuratbaru = $nosuratlama.".a";
	} 
	else 
	{
		$SQL = "SELECT no_surat4 FROM suratkeluar
			WHERE tgl_surat <= '".$tgl."' ORDER BY tgl_surat DESC,idsuratkeluar DESC LIMIT 1";
		$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$nosuratlama = $row['no_surat4'];
		
		$arrnosurat = explode(".",$nosuratlama);
		//echo "count($arrnosurat) : ".count($arrnosurat);
		if(count($arrnosurat)>1)	//ada .a atau .b, dll
			$nosuratbaru = $arrnosurat[0].".".chr(ord($arrnosurat[1]) + 1);
		else	//tidak ada .a atau .b, dll
			$nosuratbaru = $nosuratlama + 1;
	}
	
	echo $nosuratbaru;
?>