<?php
	include("include/dbconfig.php");
	include("include/JSON.php");
	$json = new Services_JSON();
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	$err = "";
		
	if(isset($_REQUEST['nama_pengguna']) && isset($_REQUEST['pass_pengguna']))
	{
		$pengguna = mysql_real_escape_string(trim($_REQUEST['nama_pengguna']));
		$pass = mysql_real_escape_string(trim($_REQUEST['pass_pengguna']));
		
		$SQL = "SELECT COUNT(iduser) AS count FROM user WHERE user = '".$pengguna."' AND password = '".md5($pass)."' AND status=1";
		$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$count = $row['count'];
		if($count==0)	//login not success
		{
			$err = "Pengguna Dan/Atau Password Salah!!";
		}
		else	//login success
		{
			$SQL = "SELECT a.iduser,a.nama_lengkap,a.user,a.iduserlevel,IF(b.adddata>0,'true','false') AS adddata,IF(b.editdata>0,'true','false') AS editdata,IF(b.deldata>0,'true','false') AS deldata FROM user a inner join useraccess b on a.iduserlevel=b.iduserlevel WHERE a.user = '".$pengguna."' AND a.password = '".md5($pass)."' AND a.status=1";
			//echo "sql:".$SQL."<br>";
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			
			session_start();  
			$_SESSION['CompleteName'] = $row['nama_lengkap'];
			$_SESSION['UserName'] = $row['user'];
			$_SESSION['UserID'] = $row['iduser'];
			$_SESSION['UserLevel'] = $row['iduserlevel'];
			$_SESSION['AddData'] = $row['adddata'];
			$_SESSION['EditData'] = $row['editdata'];
			$_SESSION['DelData'] = $row['deldata'];
			
			header("Location: main.php");
		}
	}
	
?>
<html>
    <head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>SISTEM INFORMASI AGENDARIS STAIN BATUSANGKAR</title>
		<link rel="stylesheet" type="text/css" href="css/jquerycssmenu.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.searchFilter.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="themes/le-frog/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.jqgrid.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.multiselect.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
		<script src="js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/jquerycssmenu.js" type="text/javascript"></script>
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/jquery-ui.min.js" type="text/javascript"></script>
		<script src="js/jquery.layout.js" type="text/javascript"></script>
		<script src="js/i18n/grid.locale-en.js" type="text/javascript"></script>
		<script src="js/ui.multiselect.js" type="text/javascript"></script>
		<script type="text/javascript">
			$.jgrid.no_legacy_api = true;
			$.jgrid.useJSON = true;
		</script>
		<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
		<script src="js/jquery.tablednd.js" type="text/javascript"></script>
		<script src="js/jquery.contextmenu.js" type="text/javascript"></script>
		<script src="js/jquery.jqprint.0.3.js" type="text/javascript"></script>
		<!--[if IE]><script type="text/javascript" src="scripts/jquery.bgiframe.js"></script><![endif]-->
		<script src="js/jquery.simplemodal.1.4.1.min.js" type="text/javascript"></script>
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
						jQuery("#upload").focus();
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
<body class="login">
	<div id="header"></div>
	<div id="content" align="center" style="height:380px">
		<form name="frmlogin" id="frmlogin" method="post" action="frontlogin.php">
			<table class="login">
				<tr align="center">
					<td>
						<fieldset style="margin-top:100px;width:240px">
							<legend></legend>
							<table>
								<tr>
									<td><b>Pengguna:</b></td>
									<td><input name="nama_pengguna" id="nama_pengguna" size="20" maxlength="20" type="text" value=""></td>
								</tr>
								<tr>
									<td><b>Password:</b></td>
									<td><input name="pass_pengguna" id="pass_pengguna" size="20" maxlength="20" type="password" value=""></td>
								</tr>
								<tr align="center">
									<td colspan="2"><br>
										<button id="upload">
											<span class="ui-button-text">Login<span>
										</button>
									</td>
								</tr>
							</table>
						</fieldset>
					</td>
				</tr>
				<?php 
					if($err != "")
					{
				?>
				<tr align="center">
					<td colspan="2"><span class="errorlogin"><?php echo $err; ?></span>
					</td>
				</tr>
				<?php 
					}
				?>
			</table>
		</form>
	</div>
	<div id="footer">
		Copyright @2011 STAIN Prof. Dr. H. Mahmud Yunus Batusangkar<br>
		Jl. Sudirman No. 137 Kuburajo, Lima Kaum<br>
		Batusangkar
	</div>
</body>
</html>
