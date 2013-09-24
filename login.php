<?php
	require_once("checklogin.php");
	include("include/dbconfig.php");
	include("include/JSON.php");
	$json = new Services_JSON();
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	//check if any user is exist
	$SQL = "SELECT COUNT(iduser) AS count FROM user";
	$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$count = $row['count'];
	if($count==0)	//user is not exist, create a new one
	{
		//check if any user level is exist
		$SQL = "SELECT COUNT(iduserlevel) AS count FROM userlevel";
		$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$count = $row['count'];
		$iduserlevel = 0;
		if($count==0)	//userlevel is not exist, create a new one
		{
			$sqluserlevel = "INSERT INTO userlevel (iduserlevel,userlevel,status) VALUES (1,'Administrator',1)";
			if (!mysql_query($sqluserlevel, $db)) {
				echo mysql_errno($db) . "1: " . mysql_error($db) . "\n";
			}
			$iduserlevel = mysql_insert_id();
			
			//add user access
			$SQL = "SELECT COUNT(iduseraccess) AS count FROM useraccess WHERE iduserlevel=1";
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count==0)	//useraccess is not exist, create a new one
			{
				$sqluseraccess = "INSERT INTO useraccess (iduseraccess,iduserlevel,adddata,editdata,deldata) VALUES (1,1,1,1,1)";
				if (!mysql_query($sqluseraccess, $db)) {
					echo mysql_errno($db) . "1: " . mysql_error($db) . "\n";
				}
			}
		}
		else
		{
			$sqluserlevel = "SELECT iduserlevel FROM userlevel WHERE userlevel = 'Administrator'";
			//echo $SQL;
			$result = mysql_query( $sqluserlevel ) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$iduserlevel = $row[iduserlevel];
		}
		//use md5, crypt do not work to validate password
		$sqluser = "INSERT INTO user (iduser,nama_lengkap,user,password,iduserlevel,status) VALUES (1,'Administrator','admin','".md5('admin')."',".$iduserlevel.",1)";
		if (!mysql_query($sqluser, $db)) {
			echo mysql_errno($db) . "2: " . mysql_error($db) . "\n";
		}
	}
	
	if(isset($_REQUEST['pengguna']) && isset($_REQUEST['pass']))
	{
		$pengguna = mysql_real_escape_string(trim($_REQUEST['pengguna']));
		$pass = mysql_real_escape_string(trim($_REQUEST['pass']));
		
		$SQL = "SELECT COUNT(iduser) AS count FROM user WHERE user = '".$pengguna."' AND password = '".md5($pass)."' AND status=1";
		//echo $SQL;
		$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$count = $row['count'];
		if($count==0)	//login not success
		{
			echo "Pengguna Dan/Atau Password Salah!!";
		}
		else	//login success
		{
			$SQL = "SELECT a.iduser,a.nama_lengkap,a.user,a.iduserlevel,IF(b.adddata>0,'true','false') AS adddata,IF(b.editdata>0,'true','false') AS editdata,IF(b.deldata>0,'true','false') AS deldata FROM user a inner join useraccess b on a.iduserlevel=b.iduserlevel WHERE a.user = '".$pengguna."' AND a.password = '".md5($pass)."' AND a.status=1";
			//echo "sql:".$SQL."<br>";
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);

			echo "result:OK;nama:".$row['nama_lengkap'].";userlevel:".$row['iduserlevel'].";adddata:".$row['adddata'].";editdata:".$row['editdata'].";deldata:".$row['deldata'];
		}
	}
	else
	{
	
?>
<html>
    <head>
		<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.jqgrid.css" />
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#nama_pengguna").focus();
				jQuery('#nama_pengguna').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#pass_pengguna").focus();
					}
				});
				jQuery('#pass_pengguna').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery(":button:last").focus();
					}
				});
			});
		</script>
		<style type="text/css">
			.ui-widget {
				font-size:0.8em;
			}
		</style>
	</head>
<body>
<form name="frmlogin" id="frmlogin" method="post">
	<table>
		<tr>
			<td>
				<fieldset style="width:240px">
					<legend></legend>
					<table>
						<tr>
							<td><b>Pengguna :</b></td>
							<td><input name="nama_pengguna" id="nama_pengguna" size="20" maxlength="20" type="text" value=""></td>
						</tr>
						<tr>
							<td><b>Password :</b></td>
							<td><input name="pass_pengguna" id="pass_pengguna" size="20" maxlength="20" type="password" value=""></td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
</form>
</body>
</html>
<?php
	}
?>