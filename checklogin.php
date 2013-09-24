<?php
    session_start();  
    $userid = isset($_SESSION["UserID"])?$_SESSION["UserID"]:"";
    if($userid == "" || $userid == null)
    {
		$userid = isset($_REQUEST["sessid"])?$_REQUEST["sessid"]:"";
		if($userid == "" || $userid == null)
		{
			header("Location: index.php");
		}
    }
?>