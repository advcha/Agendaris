<?php
	require_once("checklogin.php");
	include("include/clsUtility.php");
	include("include/dbconfig.php");
    $myfunction = new MyFunction();
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");

	//$row = array() ;
    $idsurat = isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$indek = "";
	$kode = "";
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	
	$SQL = "SELECT s.idsurat,s.tgl_terima,s.no_disposisi,s.pengirim,s.perihal,s.idkode,s.no_surat,
		s.berkas,s.tgl_surat,k.kode,b.bagian,
		IFNULL((SELECT disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=1),'') AS disposisi1,
		IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=1),'') AS tgl_disposisi1,
		IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=1),'') AS tujuan_disposisi1, 
		IFNULL((SELECT disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=2),'') AS disposisi2,
		IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=2),'') AS tgl_disposisi2,
		IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=2),'') AS tujuan_disposisi2, 
		IFNULL((SELECT disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=3),'') AS disposisi3,
		IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=3),'') AS tgl_disposisi3,
		IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=3),'') AS tujuan_disposisi3, 
		IFNULL((SELECT disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=4),'') AS disposisi4,
		IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=4),'') AS tgl_disposisi4,
		IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=4),'') AS tujuan_disposisi4, 
		IFNULL((SELECT disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=5),'') AS disposisi5,
		IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=5),'') AS tgl_disposisi5,
		IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=5),'') AS tujuan_disposisi5, 
		IFNULL((SELECT disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=6),'') AS disposisi6,
		IFNULL((SELECT tgl_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=6),'') AS tgl_disposisi6,
		IFNULL((SELECT tujuan_disposisi FROM disposisi WHERE idsurat=s.idsurat AND disposisi_ke=6),'') AS tujuan_disposisi6 
		FROM suratmasuk s
		LEFT JOIN disposisi d ON s.idsurat=d.idsurat
		LEFT JOIN kodesurat k ON s.idkode=k.idkode
		LEFT JOIN bagian b ON k.idbagian=b.idbagian WHERE s.idsurat = ".$idsurat." 
		GROUP BY s.idsurat,s.tgl_terima,s.no_disposisi,s.pengirim,s.perihal";
	//echo "sql:".$SQL."<br>";
	$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
?>
<html>
    <head>
		<!--link rel="stylesheet" type="text/css" media="screen" href="themes/ui.jqgrid.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/datePicker.css">
		<script src="js/jquery-1.2.6.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/date.js" type="text/javascript"></script-->
		<!--[if IE]><script type="text/javascript" src="scripts/jquery.bgiframe.js"></script><![endif]-->
		<!--script src="js/jquery.datePicker.js" type="text/javascript"></script-->
		<!--link rel="stylesheet" type="text/css" media="print" href="css/print.css" /-->
		<script type="text/javascript">
			//jQuery(document).ready(function(){
				//jQuery('.date-pick').datePicker({autoFocusNextInput: true,startDate:'01/01/2000'});
			//});
			

		</script>
		<style type="text/css">
			/*.ui-widget {*/
			#frmdisposisi {
				font-size:0.8em;
				background-color:#ffffff !important;
				color:#000000 !important;
				/*color:#000;*/
			}
			
		</style>
	</head>
<body onload="window.print();">
<form name="frmsubmit" id="frmsubmit">
	<table width="600px">
		<tr>
			<td>
				<table border="0" width="100%">
					<tr>
						<td width="72%"><b>KEMENTERIAN AGAMA</b></td>
						<td><b>NOMOR URUT : <?php echo isset($row['no_disposisi'])?$row['no_disposisi']:"1";?></b></td>
					</tr>
					<tr>
						<td width="72%"><b>SEKOLAH TINGGI AGAMA ISLAM NEGERI</b></td>
						<td><b>LEMBAR DISPOSISI</b></td>
					</tr>
					<tr>
						<td width="72%"><b>BATUSANGKAR</b></td>
						<td>&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="600px" style="border-color:#000000;border-style:solid;">
					<tr>
						<td>
							<table>
								<tr>
									<td>Indek</td>
									<td>:</td>
									<td width="400px"><?php echo $row['bagian'];?></td>
									<td>Kode</td>
									<td>:</td>
									<td><?php echo $row['kode'];?></td>
								</tr>
								<tr>
									<td>Berkas</td>
									<td>:</td>
									<td><?php echo isset($row['berkas'])?$row['berkas']:"";?></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</table>
							<HR style="border-color:#000000;border-style:solid;">
							<table>
								<tr>
									<td>Tanggal/Nomor</td>
									<td>:</td>
									<td width="80px"><?php if(isset($row['tgl_surat'])) {echo $myfunction->dateformatSlash(isset($row['tgl_surat'])?$row['tgl_surat']:date('d/m/Y'));}?></td>
									<td>&nbsp;/&nbsp;<?php echo isset($row['no_surat'])?$row['no_surat']:"";?></td>
								</tr>
								<tr>
									<td>Asal</td>
									<td>:</td>
									<td colspan="2"><?php echo isset($row['pengirim'])?$row['pengirim']:"";?></td>
								</tr>
								<tr>
									<td>Isi Ringkas</td>
									<td>:</td>
									<td colspan="2"><?php echo isset($row['perihal'])?$row['perihal']:"";?></td>
								</tr>
								<tr>
									<td>Diterima Tgl</td>
									<td>:</td>
									<td colspan="2"><?php if(isset($row['tgl_terima'])) {echo $myfunction->dateformatSlash(isset($row['tgl_terima'])?$row['tgl_terima']:date('d/m/Y'));}?></td>
								</tr>
							</table>
							<HR style="border-color:#000000;border-style:solid;">
							<table>
								<tr>
									<td>Tanggal Penyelesaian</td>
									<td>:</td>
									<td><?php if(isset($row['tgl_selesai'])) {echo $myfunction->dateformatSlash(isset($row['tgl_selesai'])?$row['tgl_selesai']:date('d/m/Y'));}?>
									</td>
								</tr>								
							</table>
							<HR style="border-color:#000000;border-style:solid;">
							<table border="0" width="100%">
								<tr valign="top" align="left">
									<td width="75%"><p>Isi Disposisi :</p>
										<table border="0">
											<tr valign="top" align="left">
												<td>&nbsp;<?php echo isset($row['disposisi1'])?$row['disposisi1']:"";?></td>
											</tr>
											<tr valign="top" align="left">
												<td>&nbsp;<?php echo isset($row['disposisi2'])?$row['disposisi2']:"";?></td>
											</tr>
											<tr valign="top" align="left">
												<td>&nbsp;<?php echo isset($row['disposisi3'])?$row['disposisi3']:"";?></td>
											</tr>
											<tr valign="top" align="left">
												<td>&nbsp;<?php echo isset($row['disposisi4'])?$row['disposisi4']:"";?></td>
											</tr>
											<tr valign="top" align="left">
												<td>&nbsp;<?php echo isset($row['disposisi5'])?$row['disposisi5']:"";?></td>
											</tr>
											<tr valign="top" align="left">
												<td>&nbsp;<?php echo isset($row['disposisi6'])?$row['disposisi6']:"";?></td>
											</tr>
										</table>
									</td>
									<td><IMG SRC="images/black.png" WIDTH="2" HEIGHT="400" BORDER="0"></td>
									<td width="24%"><p>Diteruskan Kepada :</p>
										<table border="0">
											<tr>
												<td>1.</td>
												<td><?php echo isset($row['tujuan_disposisi1'])?$row['tujuan_disposisi1']:"";?></td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>2.</td>
												<td><?php echo isset($row['tujuan_disposisi2'])?$row['tujuan_disposisi2']:"";?></td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>3.</td>
												<td><?php echo isset($row['tujuan_disposisi3'])?$row['tujuan_disposisi3']:"";?></td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>4.</td>
												<td><?php echo isset($row['tujuan_disposisi4'])?$row['tujuan_disposisi4']:"";?></td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>5.</td>
												<td><?php echo isset($row['tujuan_disposisi5'])?$row['tujuan_disposisi5']:"";?></td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td>6.</td>
												<td><?php echo isset($row['tujuan_disposisi6'])?$row['tujuan_disposisi6']:"";?></td>
											</tr>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>								
							</table>
							<HR style="border-color:#000000;border-style:solid;">
							<table>
								<tr>
									<td colspan="3" align="center">Sesudah digunakan harap segera dikembalikan</td>
								</tr>								
								<tr>
									<td width="68px">Kepada</td>
									<td>:</td>
									<td><?php echo isset($row['tujuan_akhir'])?$row['tujuan_akhir']:"";?>
									</td>
								</tr>								
								<tr>
									<td width="68px">Tanggal</td>
									<td>:</td>
									<td><?php if(isset($row['tgl_akhir'])) {echo $myfunction->dateformatSlash(isset($row['tgl_akhir'])?$row['tgl_akhir']:date('d/m/Y'));}?>
									</td>
								</tr>								
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><HR style="border-color:#000000;border-style:dashed;"></td>
		</tr>
		<tr>
			<td>
				<table width="600px" style="border-color:#000000;border-style:solid;">
					<tr>
						<td>
							<table>
								<tr>
									<td>Indek</td>
									<td>:</td>
									<td width="400px"><?php echo $row['bagian'];?></td>
									<td>Kode</td>
									<td>:</td>
									<td><?php echo $row['kode'];?></td>
								</tr>
								<tr>
									<td>Berkas</td>
									<td>:</td>
									<td><?php echo isset($row['berkas'])?$row['berkas']:"";?></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</table>
							<HR style="border-color:#000000;border-style:solid;">
							<table>
								<tr>
									<td>Tanggal/Nomor</td>
									<td>:</td>
									<td width="80px"><?php if(isset($row['tgl_surat'])) {echo $myfunction->dateformatSlash(isset($row['tgl_surat'])?$row['tgl_surat']:date('d/m/Y'));}?></td>
									<td>&nbsp;/&nbsp;<?php echo isset($row['no_surat'])?$row['no_surat']:"";?></td>
								</tr>
								<tr>
									<td>Asal</td>
									<td>:</td>
									<td colspan="2"><?php echo isset($row['pengirim'])?$row['pengirim']:"";?></td>
								</tr>
								<tr>
									<td>Isi Ringkas</td>
									<td>:</td>
									<td colspan="2"><?php echo isset($row['perihal'])?$row['perihal']:"";?></td>
								</tr>
								<tr>
									<td>Diterima Tgl</td>
									<td>:</td>
									<td colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td>Penerima</td>
									<td>:</td>
									<td colspan="2">&nbsp;</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
</body>
</html>