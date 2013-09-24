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
		<script type="text/javascript">
			$(function() {
				$('.date-picker').datepicker( {
					changeMonth: true,
					changeYear: true,
					showButtonPanel: true,
					dateFormat: 'MM yy',
					onClose: function(dateText, inst) { 
						var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
						var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
						$(this).datepicker('setDate', new Date(year, month, 1));
					}
				});
			});
		</script>
		<style type="text/css">
			.ui-widget {
				font-size:0.7em;
			}
			.ui-datepicker-calendar {
				display: none;
			}
		</style>
	</head>
<body>
<form name="frmsubmit" id="frmsubmit" method="post">
	<div id="status_simpan" style="color:yellow;"></div>
	<table>
		<tr>
			<td>
				<table style="width:190px;border-style:solid;">
					<tr>
						<td>Bulan</td>
						<td>:</td>
						<td style="width:130px"><input name="bln" id="bln" size="20" maxlength="20" type="text" class="date-picker" value="<?php echo date('F Y');?>" style="width:100px">
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
    <input type="hidden" name="FormName" value="pilihbln">
</form>
</body>
</html>