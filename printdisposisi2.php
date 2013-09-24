<?php
	require_once("checklogin.php");
	include("include/clsUtility.php");
	include("include/dbconfig.php");
	include_once('class/tcpdf/tcpdf.php');
	include_once("class/PHPJasperXML.inc.php");
    
	$xml =  simplexml_load_file("rptSuratMasuk3.jrxml");
	
	$idsurat = isset($_REQUEST['id'])?$_REQUEST['id']:"";
	
	$PHPJasperXML = new PHPJasperXML();
	//$PHPJasperXML->debugsql=true;
	$PHPJasperXML->arrayParameter=array("idsurat"=>$idsurat);
	$PHPJasperXML->xml_dismantle($xml);

	$PHPJasperXML->transferDBtoArray($dbhost,$dbuser,$dbpassword,$database);
	$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file
?>