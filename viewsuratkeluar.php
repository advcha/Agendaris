<?php
	require_once("checklogin.php");
	include("include/clsUtility.php");
	include("include/dbconfig.php");
    $myfunction = new MyFunction();
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");

	$hal = "";
	$listfile = "";
    $idsurat = isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	
	$SQL = "SELECT s.tgl_surat,s.no_surat,s.pengirim,s.perihal,s.tujuan,s.penandatangan,
		s.penyimpanan,s.arsip,s.file_loc,s.idkode,CONCAT(b.bagian,' (KODE:',k.kode,')') AS kode,
		status_surat,kondisi_surat,lampiran
		FROM suratkeluar s 
		INNER JOIN kodesurat k ON s.idkode=k.idkode 
		INNER JOIN bagian b ON k.idbagian=b.idbagian
		WHERE s.idsuratkeluar = ".$idsurat;
	//echo "sql:".$SQL."<br>";
	$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
	$namafile = isset($row['file_loc'])?$row['file_loc']:"";
	if($namafile != "")
	{
		$arrnamafile = explode(";",$namafile);
		if(count($arrnamafile) > 1)
		{
			for($j=0;$j<count($arrnamafile);$j++)
			{
				$listfile .= "<li id='li_".$j."'><a href='#' onclick='openfile(\"".$arrnamafile[$j]."\");'>".$arrnamafile[$j]."</a></li>";
			}
		}
		else
		{
			$listfile = "<li id='li_0'><a href='#' onclick='openfile(\"".$namafile."\");'>".$namafile."</a></li>";
		}
	}
?>
<html>
    <head>
		<!--link rel="stylesheet" type="text/css" media="screen" href="themes/ui.jqgrid.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/datePicker.css"-->
		<!--script src="js/jquery-1.2.6.min.js" type="text/javascript" charset="utf-8"></script-->
		<!--script src="js/date.js" type="text/javascript"></script-->
		<!--[if IE]><script type="text/javascript" src="scripts/jquery.bgiframe.js"></script><![endif]-->
		<!--script src="js/jquery.datePicker.js" type="text/javascript"></script-->
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.date-pick').datePicker({autoFocusNextInput: true,startDate:'01/01/2000'});
			});
			
			function openfile(file)
			{
				//window.location.href = "suratkeluar/"+file;
				window2=open("suratkeluar/"+file,"file","scrollbars=yes,width=600, height=500")
			}
		</script>
		<style type="text/css">
			.ui-widget {
				font-size:0.7em;
			}
			
		</style>
	</head>
<body style="background-color:#ffffff;">
<form name="frmsubmit" id="frmsubmit" method="post" enctype="multipart/form-data" action="frmsuratkeluar.php">
	<table>
		<tr>
			<td>
				<table style="width:400px;border-color:#000000;border-style:solid;">
					<tr>
						<td>
							<table>
								<tr>
									<td>Indek</td>
									<td>:</td>
									<td>
										<?php echo $row['kode'];?>
									</td>
								</tr>
								<tr>
									<td>Tanggal Surat</td>
									<td>:</td>
									<td><?php if(isset($row['tgl_surat'])) {echo $myfunction->dateformatSlash(isset($row['tgl_surat'])?$row['tgl_surat']:date('d/m/Y'));}?>
									</td>
								</tr>
								<tr>
									<td>Nomor Surat</td>
									<td>:</td>
									<td><?php echo isset($row['no_surat'])?$row['no_surat']:"";?>
									</td>
								</tr>
								<tr>
									<td>Pengirim</td>
									<td>:</td>
									<td><?php echo isset($row['pengirim'])?$row['pengirim']:"";?></td>
								</tr>
								<tr>
									<td>Perihal</td>
									<td>:</td>
									<td><?php echo isset($row['perihal'])?$row['perihal']:"";?></td>
								</tr>
								<tr>
									<td>Tujuan</td>
									<td>:</td>
									<td><?php echo isset($row['tujuan'])?$row['tujuan']:"";?></td>
								</tr>
								<tr>
									<td>Penandatangan</td>
									<td>:</td>
									<td><?php echo isset($row['penandatangan'])?$row['penandatangan']:"";?></td>
								</tr>
								<tr>
									<td>Penyimpanan</td>
									<td>:</td>
									<td><?php echo isset($row['penyimpanan'])?$row['penyimpanan']:"";?>
									</td>
								</tr>
								<tr>
									<td>Arsip</td>
									<td>:</td>
									<td><?php if(isset($row)){if($row['arsip']=='0'){echo 'Tidak Ada';}else{echo 'Ada';}}?>
									</td>
								</tr>
								<tr>
									<td>Status Surat</td>
									<td>:</td>
									<td><?php echo isset($row['status_surat'])?$row['status_surat']:"";?>
									</td>
								</tr>
								<tr>
									<td>Kondisi Surat</td>
									<td>:</td>
									<td><?php echo isset($row['kondisi_surat'])?$row['kondisi_surat']:"";?>
									</td>
								</tr>
								<tr>
									<td>Lampiran</td>
									<td>:</td>
									<td><?php echo isset($row['lampiran'])?$row['lampiran']:"";?>
									</td>
								</tr>
								<tr>
									<td>Scan Surat</td>
									<td>:</td>
									<td><ul id="files"><?php echo $listfile;?></ul></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
    <input type="hidden" name="FormName" value="suratkeluar">
	<input type="hidden" name="id" value="<?php echo $idsurat;?>">
	<input type="hidden" name="dontclose" id="dontclose" value="0">
</form>
</body>
</html>