<?php
	require_once("checklogin.php");
	include("include/clsUtility.php");
	include("include/dbconfig.php");
    $myfunction = new MyFunction();
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
?>
<html>
    <head>
		<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.jqgrid.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/datePicker.css">
		<script src="js/date.js" type="text/javascript"></script>
		<!--[if IE]><script type="text/javascript" src="scripts/jquery.bgiframe.js"></script><![endif]-->
		<script src="js/jquery.datePicker.js" type="text/javascript"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.date-pick').datePicker({autoFocusNextInput: true,startDate:'01/01/2000'});
				jQuery("#tgl_awal").focus();
				jQuery('#tgl_awal').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_akhir").focus();
					}
				});
				jQuery('#tgl_akhir').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery(":button:last").focus();
					}
				});
			});

		</script>
		<style type="text/css">
			.ui-widget {
				font-size:0.7em;
			}
			
		</style>
	</head>
<body>
<form name="frmsubmit" id="frmsubmit" method="post">
	<div id="status_simpan" style="color:yellow;"></div>
	<table>
		<tr>
			<td>
				<table style="border-style:solid;">
					<tr>
						<td>
							<table>
								<tr>
									<td>Tanggal Awal</td>
									<td>:</td>
									<td><input name="tgl_awal" id="tgl_awal" size="12" maxlength="10" type="text" class="date-pick" value="<?php echo date('d/m/Y');?>">
									</td>
								</tr>
								<tr>
									<td>Tanggal Akhir</td>
									<td>:</td>
									<td><input name="tgl_akhir" id="tgl_akhir" size="12" maxlength="10" type="text" class="date-pick" value="<?php echo date('d/m/Y');?>">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
    <input type="hidden" name="FormName" value="pilihtgl">
</form>
</body>
</html>